<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : FeedingSystem.php                                         ##
##  Type           : Linked-account ("feeding") engine                        ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow                                                    ##
##  Project        : Novaterra                                                 ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################

/**
 * FeedingSystem
 * -------------------------------------------------------------------------
 * Lets a player declare one or more of their own alternate/second accounts
 * on the SAME world as "linked" (fed) accounts, so that raiding them is not
 * capped by cranny/warehouse loot protection - i.e. the owner can pull
 * resources from a linked account without the normal raid limit.
 *
 * This is a DIFFERENT, INDEPENDENT feature from MultiAccount.php.
 * MultiAccount.php is anti-cheat detection (heuristic risk scoring for
 * suspicious pairs, reviewed manually by an admin/multihunter - it never
 * blocks or changes gameplay). FeedingSystem.php is an opt-in, admin-capped
 * gameplay allowance the client explicitly asked for. Neither file reads
 * from nor writes to the other's tables. Do not merge them.
 *
 * Scope: local to a single world's database only (unlike CentralGold, which
 * is one database shared across every world). A "linked accounts" pair only
 * ever makes sense between two accounts fighting each other on the same
 * world, so there is no cross-world concept here.
 *
 * Where this plugs into the battle engine: exactly one call site, added in
 * AutomationBattleResolution::resolveResourcesAfterBattle() - when the
 * attacker and defender of a raid are a declared linked pair (attacker is
 * the declared owner side), cranny/warehouse loot protection ($cranny_eff)
 * is skipped entirely for that raid, so 100% of the target's resources
 * become lootable subject only to the attacking troops' own carry capacity.
 * No other file in GameEngine/ is touched.
 *
 * Tables (created lazily via ensureSchema(), same pattern as
 * MultiAccount::ensureSchema() / mad_session - no manual SQL run needed on
 * existing installs): `linked_accounts`, `feeding_settings`.
 */
class FeedingSystem
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
     * Create `linked_accounts` / `feeding_settings` if they don't exist yet.
     * Called lazily at the top of every public method so the feature works
     * on servers that never re-ran the installer.
     */
    public static function ensureSchema()
    {
        $link = self::link();
        if (!$link) {
            return;
        }

        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "linked_accounts` (
            `id`         int(11) NOT NULL AUTO_INCREMENT,
            `owner_uid`  int(11) NOT NULL,
            `linked_uid` int(11) NOT NULL,
            `added`      int(11) NOT NULL DEFAULT 0,
            `added_by`   int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            UNIQUE KEY `owner_linked` (`owner_uid`, `linked_uid`),
            KEY `owner_uid` (`owner_uid`),
            KEY `linked_uid` (`linked_uid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "feeding_settings` (
            `id`                    int(11) NOT NULL DEFAULT 1,
            `enabled`               tinyint(1) NOT NULL DEFAULT 0,
            `max_linked_per_player` int(11) NOT NULL DEFAULT 1,
            `announced_in_rules`    tinyint(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        @mysqli_query($link, "INSERT IGNORE INTO `" . TB_PREFIX . "feeding_settings`
            (`id`, `enabled`, `max_linked_per_player`, `announced_in_rules`) VALUES (1, 0, 1, 0)");
    }

    /* ---- Settings (admin panel) ------------------------------------------ */

    /**
     * @return array{enabled:bool, max_linked_per_player:int, announced_in_rules:bool}
     */
    public static function getSettings()
    {
        if (self::$settingsCache !== null) {
            return self::$settingsCache;
        }

        $default = ['enabled' => false, 'max_linked_per_player' => 1, 'announced_in_rules' => false];

        $link = self::link();
        if (!$link) {
            return self::$settingsCache = $default;
        }
        self::ensureSchema();

        $res = @mysqli_query($link, "SELECT `enabled`, `max_linked_per_player`, `announced_in_rules`
                                      FROM `" . TB_PREFIX . "feeding_settings` WHERE `id` = 1 LIMIT 1");
        $row = $res ? mysqli_fetch_assoc($res) : null;
        if (!$row) {
            return self::$settingsCache = $default;
        }

        return self::$settingsCache = [
            'enabled'                => ((int) $row['enabled']) === 1,
            'max_linked_per_player'  => max(0, (int) $row['max_linked_per_player']),
            'announced_in_rules'     => ((int) $row['announced_in_rules']) === 1,
        ];
    }

    public static function isEnabled()
    {
        return self::getSettings()['enabled'];
    }

    public static function maxLinkedPerPlayer()
    {
        return self::getSettings()['max_linked_per_player'];
    }

    /**
     * Update the admin-configurable settings. Called only from the Admin
     * panel Mod (see GameEngine/Admin/Mods/feedingSystemAdmin.php).
     *
     * @return bool success
     */
    public static function saveSettings($enabled, $maxLinked, $announcedInRules)
    {
        $link = self::link();
        if (!$link) {
            return false;
        }
        self::ensureSchema();

        $enabled   = $enabled ? 1 : 0;
        $maxLinked = max(0, (int) $maxLinked);
        $announced = $announcedInRules ? 1 : 0;

        $stmt = mysqli_prepare(
            $link,
            "UPDATE `" . TB_PREFIX . "feeding_settings`
             SET `enabled` = ?, `max_linked_per_player` = ?, `announced_in_rules` = ?
             WHERE `id` = 1"
        );
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'iii', $enabled, $maxLinked, $announced);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        self::$settingsCache = null; // invalidate cache
        return (bool) $ok;
    }

    /* ---- Link management --------------------------------------------------- */

    /**
     * How many accounts `$ownerUid` currently has linked.
     */
    public static function countLinked($ownerUid)
    {
        $link = self::link();
        if (!$link) {
            return 0;
        }
        self::ensureSchema();

        $ownerUid = (int) $ownerUid;
        $res = mysqli_query($link, "SELECT COUNT(*) AS c FROM `" . TB_PREFIX . "linked_accounts`
                                     WHERE `owner_uid` = " . $ownerUid);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        return $row ? (int) $row['c'] : 0;
    }

    /**
     * All accounts currently linked under `$ownerUid`, with username for display.
     * @return array<int, array{id:int, linked_uid:int, username:string, added:int, added_by:int}>
     */
    public static function listLinked($ownerUid)
    {
        $link = self::link();
        if (!$link) {
            return [];
        }
        self::ensureSchema();

        $ownerUid = (int) $ownerUid;
        $sql = "SELECT la.id, la.linked_uid, la.added, la.added_by, u.username
                FROM `" . TB_PREFIX . "linked_accounts` la
                LEFT JOIN `" . TB_PREFIX . "users` u ON u.id = la.linked_uid
                WHERE la.owner_uid = " . $ownerUid . "
                ORDER BY la.added DESC";
        $res = mysqli_query($link, $sql);
        $out = [];
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $out[] = [
                    'id'         => (int) $row['id'],
                    'linked_uid' => (int) $row['linked_uid'],
                    'username'   => (string) ($row['username'] ?? '(deleted)'),
                    'added'      => (int) $row['added'],
                    'added_by'   => (int) $row['added_by'],
                ];
            }
        }
        return $out;
    }

    /**
     * Declare `$linkedUid` as a linked/fed account of `$ownerUid`.
     * Enforces: feature must be enabled, cap not exceeded, no self-link,
     * target account must exist, target not already linked to this owner.
     *
     * @param int $addedBy 0 = player self-service, admin uid = added via Admin panel (bypasses the cap)
     * @return array{ok:bool, error:string}  error is '' on success
     */
    public static function addLink($ownerUid, $linkedUid, $addedBy = 0)
    {
        $link = self::link();
        if (!$link) {
            return ['ok' => false, 'error' => 'DB_UNAVAILABLE'];
        }
        self::ensureSchema();

        $ownerUid  = (int) $ownerUid;
        $linkedUid = (int) $linkedUid;
        $addedBy   = (int) $addedBy;
        $isAdmin   = $addedBy > 0;

        if (!$isAdmin && !self::isEnabled()) {
            return ['ok' => false, 'error' => 'FEATURE_DISABLED'];
        }
        if ($ownerUid <= 0 || $linkedUid <= 0) {
            return ['ok' => false, 'error' => 'INVALID_ACCOUNT'];
        }
        if ($ownerUid === $linkedUid) {
            return ['ok' => false, 'error' => 'CANNOT_LINK_SELF'];
        }

        // Target account must actually exist.
        $res = mysqli_query($link, "SELECT id FROM `" . TB_PREFIX . "users` WHERE id = " . $linkedUid . " LIMIT 1");
        if (!$res || mysqli_num_rows($res) === 0) {
            return ['ok' => false, 'error' => 'ACCOUNT_NOT_FOUND'];
        }

        // Cap check - admin-added links bypass the self-service cap
        // (the client asked for the admin to be able to set/override this).
        if (!$isAdmin) {
            $max = self::maxLinkedPerPlayer();
            if (self::countLinked($ownerUid) >= $max) {
                return ['ok' => false, 'error' => 'LIMIT_REACHED'];
            }
        }

        $now = time();
        $stmt = mysqli_prepare(
            $link,
            "INSERT IGNORE INTO `" . TB_PREFIX . "linked_accounts`
             (owner_uid, linked_uid, added, added_by) VALUES (?, ?, ?, ?)"
        );
        if (!$stmt) {
            return ['ok' => false, 'error' => 'DB_ERROR'];
        }
        mysqli_stmt_bind_param($stmt, 'iiii', $ownerUid, $linkedUid, $now, $addedBy);
        $ok = mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        if (!$ok) {
            return ['ok' => false, 'error' => 'DB_ERROR'];
        }
        if ($affected === 0) {
            return ['ok' => false, 'error' => 'ALREADY_LINKED'];
        }
        return ['ok' => true, 'error' => ''];
    }

    /**
     * Remove a link. Either the owner themself or an admin can call this;
     * the caller is responsible for the permission check.
     */
    public static function removeLink($ownerUid, $linkedUid)
    {
        $link = self::link();
        if (!$link) {
            return false;
        }
        self::ensureSchema();

        $ownerUid  = (int) $ownerUid;
        $linkedUid = (int) $linkedUid;

        $stmt = mysqli_prepare(
            $link,
            "DELETE FROM `" . TB_PREFIX . "linked_accounts` WHERE owner_uid = ? AND linked_uid = ?"
        );
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'ii', $ownerUid, $linkedUid);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool) $ok;
    }

    /**
     * Admin-only: remove by row id (used by the Admin panel table, where an
     * admin may not know both uids offhand).
     */
    public static function removeLinkById($id)
    {
        $link = self::link();
        if (!$link) {
            return false;
        }
        self::ensureSchema();

        $id = (int) $id;
        $ok = mysqli_query($link, "DELETE FROM `" . TB_PREFIX . "linked_accounts` WHERE id = " . $id);
        return (bool) $ok;
    }

    /**
     * All linked_accounts rows, joined with both usernames, for the Admin
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
        $sql = "SELECT la.id, la.owner_uid, la.linked_uid, la.added, la.added_by,
                       uo.username AS owner_username, ul.username AS linked_username
                FROM `" . TB_PREFIX . "linked_accounts` la
                LEFT JOIN `" . TB_PREFIX . "users` uo ON uo.id = la.owner_uid
                LEFT JOIN `" . TB_PREFIX . "users` ul ON ul.id = la.linked_uid
                ORDER BY la.added DESC
                LIMIT " . $limit;
        $res = mysqli_query($link, $sql);
        $out = [];
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $out[] = [
                    'id'             => (int) $row['id'],
                    'owner_uid'      => (int) $row['owner_uid'],
                    'linked_uid'     => (int) $row['linked_uid'],
                    'owner_username' => (string) ($row['owner_username'] ?? '(deleted)'),
                    'linked_username'=> (string) ($row['linked_username'] ?? '(deleted)'),
                    'added'          => (int) $row['added'],
                    'added_by'       => (int) $row['added_by'],
                ];
            }
        }
        return $out;
    }

    /* ---- Battle-engine hook ------------------------------------------------ */

    /**
     * True if `$attackerUid` has declared `$defenderUid` as one of their
     * linked/fed accounts (i.e. this raid should ignore cranny/warehouse
     * loot protection). Direction matters - see class docblock.
     *
     * This is the ONLY method AutomationBattleResolution.php calls. It is
     * cheap (single indexed lookup) and fails soft to false (normal raid
     * caps apply) on any DB issue or if the feature is disabled.
     */
    public static function isLinkedPair($attackerUid, $defenderUid)
    {
        if (!self::isEnabled()) {
            return false;
        }

        $link = self::link();
        if (!$link) {
            return false;
        }
        self::ensureSchema();

        $attackerUid = (int) $attackerUid;
        $defenderUid = (int) $defenderUid;
        if ($attackerUid <= 0 || $defenderUid <= 0 || $attackerUid === $defenderUid) {
            return false;
        }

        $res = mysqli_query(
            $link,
            "SELECT 1 FROM `" . TB_PREFIX . "linked_accounts`
             WHERE owner_uid = " . $attackerUid . " AND linked_uid = " . $defenderUid . " LIMIT 1"
        );
        return $res && mysqli_num_rows($res) > 0;
    }
}
