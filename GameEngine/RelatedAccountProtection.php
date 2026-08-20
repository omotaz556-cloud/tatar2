<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : RelatedAccountProtection.php                             ##
##  Type           : Related-account raid/loot BLOCKING engine                ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow                                                    ##
##  Project        : Novaterra                                                 ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################

/**
 * RelatedAccountProtection
 * -------------------------------------------------------------------------
 * Lets an admin manually declare two accounts on the SAME world as
 * "related" (same owner / same device / same person), so that raiding
 * between them is BLOCKED at the battle-engine level - the opposite of
 * FeedingSystem.php, which lets a player opt an account IN to being
 * raided freely.
 *
 * This is a DIFFERENT, INDEPENDENT feature from both MultiAccount.php
 * (anti-cheat heuristic scoring - never blocks or changes gameplay) and
 * FeedingSystem.php (opt-in gameplay allowance for a player's own alts).
 * None of the three files read from or write to another's tables.
 *
 * Conflict rule (deliberate product decision): if a pair is declared BOTH
 * "related" here AND "linked" in FeedingSystem, the block here always
 * wins. Protection takes priority over any raid allowance, regardless of
 * which system was configured more recently. See isBlockedPair() and its
 * call site in AutomationBattleResolution::resolveResourcesAfterBattle().
 *
 * Auto-Ban on Attempt (optional, off by default): when enabled via
 * `related_protection_settings.auto_ban_on_attempt`, a BLOCKED raid or
 * transfer attempt between a declared related pair does not just get
 * refused — it immediately bans the attacking/sending account only (not
 * the target), on the very first attempt, no strike counter. This reuses
 * MultiAccount::banAccount() (see its docblock for how a ban is actually
 * enforced) so there is exactly one ban mechanism in the codebase. See
 * banAttacker() below and its call sites in
 * AutomationBattleResolution::resolveResourcesAfterBattle() and
 * Market::isRelatedAccountTransfer().
 *
 * Scope: local to a single world's database only (unlike CentralGold).
 * A "related accounts" pair only ever makes sense between two accounts on
 * the same world, so there is no cross-world concept here.
 *
 * Where this plugs into the battle engine: exactly one call site, in
 * AutomationBattleResolution::resolveResourcesAfterBattle() - when the
 * attacker and defender of a raid are a declared related pair (in EITHER
 * direction - unlike FeedingSystem, direction does not matter here, since
 * the point is to stop a player exploiting resources between their own
 * accounts regardless of which one is "attacking"), the raid yields ZERO
 * loot: cranny/warehouse protection is treated as 100% effective for that
 * raid, exactly as if the target had unlimited cranny capacity. Troops
 * still resolve combat normally; only the resource loot is zeroed.
 *
 * Tables (created lazily via ensureSchema(), same pattern as
 * MultiAccount::ensureSchema() / FeedingSystem::ensureSchema() - no manual
 * SQL run needed on existing installs): `related_accounts`,
 * `related_protection_settings`.
 */
class RelatedAccountProtection
{
    /** @var bool|null Cached settings row for this request. */
    private static $settingsCache = null;

    /* ---- DB plumbing ----------------------------------------------------- */

    /** Resolve the raw mysqli link from whatever context we run in. */
    private static function link()
    {
        if (isset($GLOBALS['link']) && $GLOBALS['link']) {
            return $GLOBALS['link'];
        }
        if (isset($GLOBALS['database']) && isset($GLOBALS['database']->dblink)) {
            return $GLOBALS['database']->dblink;
        }
        return null;
    }

    /**
     * Create `related_accounts` / `related_protection_settings` if they
     * don't exist yet. Called lazily at the top of every public method so
     * the feature works on servers that never re-ran the installer.
     */
    public static function ensureSchema()
    {
        $link = self::link();
        if (!$link) {
            return;
        }

        // Undirected pair: always stored with uid_a < uid_b so a lookup
        // only ever needs to check one row regardless of which account
        // attacks which (see isBlockedPair()).
        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "related_accounts` (
            `id`       int(11) NOT NULL AUTO_INCREMENT,
            `uid_a`    int(11) NOT NULL,
            `uid_b`    int(11) NOT NULL,
            `added`    int(11) NOT NULL DEFAULT 0,
            `added_by` int(11) NOT NULL DEFAULT 0,
            `reason`   varchar(255) NOT NULL DEFAULT '',
            PRIMARY KEY (`id`),
            UNIQUE KEY `pair` (`uid_a`, `uid_b`),
            KEY `uid_a` (`uid_a`),
            KEY `uid_b` (`uid_b`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "related_protection_settings` (
            `id`                  int(11) NOT NULL DEFAULT 1,
            `enabled`             tinyint(1) NOT NULL DEFAULT 0,
            `auto_ban_on_attempt` tinyint(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        @mysqli_query($link, "INSERT IGNORE INTO `" . TB_PREFIX . "related_protection_settings`
            (`id`, `enabled`, `auto_ban_on_attempt`) VALUES (1, 0, 0)");

        self::ensureAutoBanColumn();

        // Item 4 (Resource Transfer Protection): log of every marketplace
        // send blocked because sender+recipient are a related pair. Related
        // pairs get a full block (zero allowance), never a reduced limit,
        // so this is a block log rather than a rate/limit log.
        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "related_transfer_violations` (
            `id`        int(11) NOT NULL AUTO_INCREMENT,
            `from_uid`  int(11) NOT NULL DEFAULT 0,
            `to_uid`    int(11) NOT NULL DEFAULT 0,
            `from_vref` int(11) NOT NULL DEFAULT 0,
            `to_vref`   int(11) NOT NULL DEFAULT 0,
            `wood`      int(11) NOT NULL DEFAULT 0,
            `clay`      int(11) NOT NULL DEFAULT 0,
            `iron`      int(11) NOT NULL DEFAULT 0,
            `crop`      int(11) NOT NULL DEFAULT 0,
            `time`      int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            KEY `pair_time` (`from_uid`, `to_uid`, `time`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    /**
     * `related_protection_settings` predates the Auto-Ban on Attempt
     * feature on installs that already ran the CREATE TABLE above without
     * this column. Same lazy-migration pattern as
     * MultiAccount::ensureBanlistPrevAccessColumn() — try the modern
     * "IF NOT EXISTS" syntax first, fall back to an information_schema
     * check for older MySQL/MariaDB.
     */
    private static function ensureAutoBanColumn()
    {
        $link = self::link();
        if (!$link) {
            return;
        }

        $added = @mysqli_query(
            $link,
            "ALTER TABLE `" . TB_PREFIX . "related_protection_settings`
             ADD COLUMN IF NOT EXISTS `auto_ban_on_attempt` tinyint(1) NOT NULL DEFAULT 0"
        );
        if ($added) {
            return;
        }

        $check = @mysqli_query(
            $link,
            "SELECT 1 FROM information_schema.columns
             WHERE table_schema = DATABASE()
               AND table_name = '" . TB_PREFIX . "related_protection_settings'
               AND column_name = 'auto_ban_on_attempt' LIMIT 1"
        );
        if ($check && mysqli_num_rows($check) === 0) {
            @mysqli_query(
                $link,
                "ALTER TABLE `" . TB_PREFIX . "related_protection_settings`
                 ADD COLUMN `auto_ban_on_attempt` tinyint(1) NOT NULL DEFAULT 0"
            );
        }
    }

    /** Normalise a pair so uid_a is always the smaller id. */
    private static function orderPair($uidA, $uidB)
    {
        $uidA = (int) $uidA;
        $uidB = (int) $uidB;
        return $uidA <= $uidB ? [$uidA, $uidB] : [$uidB, $uidA];
    }

    /* ---- Settings (admin panel) ------------------------------------------ */

    /**
     * @return array{enabled:bool, auto_ban_on_attempt:bool}
     */
    public static function getSettings()
    {
        if (self::$settingsCache !== null) {
            return self::$settingsCache;
        }

        $default = ['enabled' => false, 'auto_ban_on_attempt' => false];

        $link = self::link();
        if (!$link) {
            return self::$settingsCache = $default;
        }
        self::ensureSchema();

        $res = @mysqli_query($link, "SELECT `enabled`, `auto_ban_on_attempt`
                                      FROM `" . TB_PREFIX . "related_protection_settings` WHERE `id` = 1 LIMIT 1");
        $row = $res ? mysqli_fetch_assoc($res) : null;
        if (!$row) {
            return self::$settingsCache = $default;
        }

        return self::$settingsCache = [
            'enabled'             => ((int) $row['enabled']) === 1,
            'auto_ban_on_attempt' => ((int) ($row['auto_ban_on_attempt'] ?? 0)) === 1,
        ];
    }

    public static function isEnabled()
    {
        return self::getSettings()['enabled'];
    }

    /**
     * Auto-Ban on Attempt is meaningless without the base protection being
     * on (there would be nothing to attempt against), so this also
     * requires isEnabled() — same "both must be on" pattern MultiAccount
     * uses for isEnabled()+isAutoBanEnabled().
     */
    public static function isAutoBanOnAttemptEnabled()
    {
        $s = self::getSettings();
        return $s['enabled'] && $s['auto_ban_on_attempt'];
    }

    /**
     * Update the admin-configurable settings. Called only from the Admin
     * panel Mod (see GameEngine/Admin/Mods/relatedAccountProtectionAdmin.php).
     *
     * @return bool success
     */
    public static function saveSettings($enabled, $autoBanOnAttempt = null)
    {
        $link = self::link();
        if (!$link) {
            return false;
        }
        self::ensureSchema();

        $enabled = $enabled ? 1 : 0;

        // Backward-compatible: callers that only pass $enabled keep the
        // auto-ban flag as-is instead of resetting it.
        if ($autoBanOnAttempt === null) {
            $stmt = mysqli_prepare(
                $link,
                "UPDATE `" . TB_PREFIX . "related_protection_settings`
                 SET `enabled` = ?
                 WHERE `id` = 1"
            );
            if (!$stmt) {
                return false;
            }
            mysqli_stmt_bind_param($stmt, 'i', $enabled);
        } else {
            $autoBanOnAttempt = $autoBanOnAttempt ? 1 : 0;
            $stmt = mysqli_prepare(
                $link,
                "UPDATE `" . TB_PREFIX . "related_protection_settings`
                 SET `enabled` = ?, `auto_ban_on_attempt` = ?
                 WHERE `id` = 1"
            );
            if (!$stmt) {
                return false;
            }
            mysqli_stmt_bind_param($stmt, 'ii', $enabled, $autoBanOnAttempt);
        }

        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        self::$settingsCache = null; // invalidate cache
        return (bool) $ok;
    }

    /* ---- Relation management --------------------------------------------- */

    /**
     * Declare `$uidA` and `$uidB` as a related (blocked-from-raiding-each-
     * other) pair. Admin-only feature - there is no self-service side to
     * this, unlike FeedingSystem (protection is not something a player
     * should be able to opt out of on their own account).
     *
     * @return array{ok:bool, error:string} error is '' on success
     */
    public static function addRelation($uidA, $uidB, $adminUid, $reason = '')
    {
        $link = self::link();
        if (!$link) {
            return ['ok' => false, 'error' => 'DB_UNAVAILABLE'];
        }
        self::ensureSchema();

        $uidA = (int) $uidA;
        $uidB = (int) $uidB;
        $adminUid = (int) $adminUid;

        if ($uidA <= 0 || $uidB <= 0) {
            return ['ok' => false, 'error' => 'INVALID_ACCOUNT'];
        }
        if ($uidA === $uidB) {
            return ['ok' => false, 'error' => 'CANNOT_LINK_SELF'];
        }

        // Both accounts must actually exist.
        $check = mysqli_query($link, "SELECT id FROM `" . TB_PREFIX . "users`
                                       WHERE id IN (" . $uidA . ", " . $uidB . ")");
        if (!$check || mysqli_num_rows($check) < 2) {
            return ['ok' => false, 'error' => 'ACCOUNT_NOT_FOUND'];
        }

        [$a, $b] = self::orderPair($uidA, $uidB);
        $now = time();
        $reason = substr((string) $reason, 0, 255);

        $stmt = mysqli_prepare(
            $link,
            "INSERT IGNORE INTO `" . TB_PREFIX . "related_accounts`
             (uid_a, uid_b, added, added_by, reason) VALUES (?, ?, ?, ?, ?)"
        );
        if (!$stmt) {
            return ['ok' => false, 'error' => 'DB_ERROR'];
        }
        mysqli_stmt_bind_param($stmt, 'iiiis', $a, $b, $now, $adminUid, $reason);
        $ok = mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        if (!$ok) {
            return ['ok' => false, 'error' => 'DB_ERROR'];
        }
        if ($affected === 0) {
            return ['ok' => false, 'error' => 'ALREADY_RELATED'];
        }
        return ['ok' => true, 'error' => ''];
    }

    /**
     * Admin-only: remove a relation by row id (the Admin panel table shows
     * usernames but acts on the row id, same pattern as
     * FeedingSystem::removeLinkById()).
     */
    public static function removeRelationById($id)
    {
        $link = self::link();
        if (!$link) {
            return false;
        }
        self::ensureSchema();

        $id = (int) $id;
        $ok = mysqli_query($link, "DELETE FROM `" . TB_PREFIX . "related_accounts` WHERE id = " . $id);
        return (bool) $ok;
    }

    /**
     * All related_accounts rows, joined with both usernames, for the Admin
     * panel overview table.
     */
    public static function listAll($limit = 300)
    {
        $link = self::link();
        if (!$link) {
            return [];
        }
        self::ensureSchema();

        $limit = max(1, (int) $limit);
        $sql = "SELECT ra.id, ra.uid_a, ra.uid_b, ra.added, ra.added_by, ra.reason,
                       ua.username AS username_a, ub.username AS username_b
                FROM `" . TB_PREFIX . "related_accounts` ra
                LEFT JOIN `" . TB_PREFIX . "users` ua ON ua.id = ra.uid_a
                LEFT JOIN `" . TB_PREFIX . "users` ub ON ub.id = ra.uid_b
                ORDER BY ra.added DESC
                LIMIT " . $limit;
        $res = mysqli_query($link, $sql);
        $out = [];
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $out[] = [
                    'id'         => (int) $row['id'],
                    'uid_a'      => (int) $row['uid_a'],
                    'uid_b'      => (int) $row['uid_b'],
                    'username_a' => (string) ($row['username_a'] ?? '(deleted)'),
                    'username_b' => (string) ($row['username_b'] ?? '(deleted)'),
                    'added'      => (int) $row['added'],
                    'added_by'   => (int) $row['added_by'],
                    'reason'     => (string) $row['reason'],
                ];
            }
        }
        return $out;
    }

    /* ---- Battle-engine hook ------------------------------------------------ */

    /**
     * True if `$uidA` and `$uidB` are a declared related pair AND
     * protection is enabled - i.e. this raid must yield zero loot.
     * Direction does NOT matter (unlike FeedingSystem::isLinkedPair()):
     * the whole point is to stop resource extraction between the two
     * accounts regardless of which one initiates the attack.
     *
     * This is the ONLY method AutomationBattleResolution.php calls. It is
     * cheap (single indexed lookup on the normalised pair) and fails soft
     * to false (normal raid rules apply) on any DB issue or if the
     * feature is disabled.
     */
    public static function isBlockedPair($uidA, $uidB)
    {
        if (!self::isEnabled()) {
            return false;
        }

        $link = self::link();
        if (!$link) {
            return false;
        }
        self::ensureSchema();

        $uidA = (int) $uidA;
        $uidB = (int) $uidB;
        if ($uidA <= 0 || $uidB <= 0 || $uidA === $uidB) {
            return false;
        }

        [$a, $b] = self::orderPair($uidA, $uidB);
        $res = mysqli_query(
            $link,
            "SELECT 1 FROM `" . TB_PREFIX . "related_accounts`
             WHERE uid_a = " . $a . " AND uid_b = " . $b . " LIMIT 1"
        );
        return $res && mysqli_num_rows($res) > 0;
    }

    /* ---- Auto-Ban on Attempt ----------------------------------------------- */

    /**
     * Auto-Ban on Attempt: called from a call site AFTER a raid or transfer
     * has already been identified as blocked (isBlockedPair() /
     * isBlockedTransferPair() returned true). Bans ONLY the attacking or
     * sending account ($attackerUid) — never the target — immediately, on
     * the first attempt, with no strike counter.
     *
     * No-op unless isAutoBanOnAttemptEnabled() is true, so a call site can
     * call this unconditionally right after a block without an extra
     * settings check of its own.
     *
     * Reuses MultiAccount::banAccount() rather than duplicating ban logic —
     * see that method's docblock for exactly what a ban does (banlist row +
     * users.access = BANNED). Attribution uses uid 0 ("System"), same
     * pattern as MultiAccount::applyAutoBan(), since this fires from
     * gameplay code with no admin session in context.
     *
     * Fails soft (returns false, never throws) on any issue — a ban
     * failure must never interrupt the raid/transfer flow that already
     * decided to block the resources.
     *
     * @param int    $attackerUid uid of the attacking/sending account only.
     * @param string $context     Short label for the reason text, e.g.
     *                            'raid' or 'transfer'.
     * @return bool True if a new ban was applied.
     */
    public static function banAttacker($attackerUid, $context = 'attempt')
    {
        try {
            if (!self::isAutoBanOnAttemptEnabled()) {
                return false;
            }
            if (!class_exists('MultiAccount')) {
                return false; // engine not deployed -> no enforcement
            }

            $attackerUid = (int) $attackerUid;
            if ($attackerUid <= 3) {
                return false;
            }

            $reason = 'Auto-banned: attempted ' . $context . ' against a declared related account';
            return (bool) MultiAccount::banAccount($attackerUid, 0, $reason);
        } catch (\Throwable $e) {
            return false; // never break the raid/transfer flow that called this
        }
    }

    /* ---- Resource-transfer protection (item 4 of the gap analysis) ------- */

    /**
     * Marketplace-transfer counterpart of isBlockedPair(). Related pairs get
     * a full, permanent block on sending resources to each other - there is
     * no "reduced limit", "daily cap", or "cooldown" concept here, since the
     * product decision is zero transfer allowance, exactly like the raid
     * loot block above. Direction does not matter, same reasoning as
     * isBlockedPair(): stops resource extraction between the two accounts
     * regardless of which one is sending.
     *
     * This is intentionally just an alias of isBlockedPair() - kept as a
     * separate, clearly-named method so the call site in Market.php reads
     * as "is this a related-account transfer" rather than reusing a raid-
     * specific name, and so the two concerns can diverge later without
     * touching Market.php again.
     */
    public static function isBlockedTransferPair($uidA, $uidB)
    {
        return self::isBlockedPair($uidA, $uidB);
    }

    /**
     * Log one marketplace send that was blocked because sender+recipient
     * are a declared related pair. Best-effort; never throws (mirrors
     * PushProtection::logTransfer()'s fail-soft style) so a logging issue
     * can never block or break the send flow that calls this.
     */
    public static function logBlockedTransfer($fromUid, $toUid, $fromVref, $toVref,
        $wood, $clay, $iron, $crop, $time = 0)
    {
        try {
            $link = self::link();
            if (!$link) {
                return;
            }
            self::ensureSchema();

            $fromUid  = (int) $fromUid;
            $toUid    = (int) $toUid;
            $fromVref = (int) $fromVref;
            $toVref   = (int) $toVref;
            $wood = (int) $wood; $clay = (int) $clay; $iron = (int) $iron; $crop = (int) $crop;
            $time = $time > 0 ? (int) $time : time();

            $stmt = mysqli_prepare($link,
                "INSERT INTO `" . TB_PREFIX . "related_transfer_violations`
                 (from_uid, to_uid, from_vref, to_vref, wood, clay, iron, crop, `time`)
                 VALUES (?,?,?,?,?,?,?,?,?)");
            if (!$stmt) {
                return;
            }
            mysqli_stmt_bind_param($stmt, 'iiiiiiiii',
                $fromUid, $toUid, $fromVref, $toVref, $wood, $clay, $iron, $crop, $time);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } catch (\Throwable $e) {
            // never break the market send flow
        }
    }

    /**
     * Recent blocked-transfer attempts, joined with both usernames, for the
     * Admin panel (same shape/purpose as listAll() above but for transfer
     * attempts instead of declared relations).
     */
    public static function listTransferViolations($limit = 300)
    {
        $link = self::link();
        if (!$link) {
            return [];
        }
        self::ensureSchema();

        $limit = max(1, (int) $limit);
        $sql = "SELECT v.id, v.from_uid, v.to_uid, v.wood, v.clay, v.iron, v.crop, v.`time`,
                       ua.username AS username_from, ub.username AS username_to
                FROM `" . TB_PREFIX . "related_transfer_violations` v
                LEFT JOIN `" . TB_PREFIX . "users` ua ON ua.id = v.from_uid
                LEFT JOIN `" . TB_PREFIX . "users` ub ON ub.id = v.to_uid
                ORDER BY v.`time` DESC
                LIMIT " . $limit;
        $res = mysqli_query($link, $sql);
        $out = [];
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $out[] = [
                    'id'            => (int) $row['id'],
                    'from_uid'      => (int) $row['from_uid'],
                    'to_uid'        => (int) $row['to_uid'],
                    'username_from' => (string) ($row['username_from'] ?? '(deleted)'),
                    'username_to'   => (string) ($row['username_to'] ?? '(deleted)'),
                    'wood'          => (int) $row['wood'],
                    'clay'          => (int) $row['clay'],
                    'iron'          => (int) $row['iron'],
                    'crop'          => (int) $row['crop'],
                    'total'         => (int) $row['wood'] + (int) $row['clay'] + (int) $row['iron'] + (int) $row['crop'],
                    'time'          => (int) $row['time'],
                ];
            }
        }
        return $out;
    }
}