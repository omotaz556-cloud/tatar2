<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : MultiAccount.php                                          ##
##  Type           : Multi-Account Detection engine (Admin/Multihunter tool)   ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow 		                                           ##
##  Project        : Novaterra                                                  ##
##  URLs:          : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################

/**
 * MultiAccount
 * -------------------------------------------------------------------------
 * Heuristic multi-account (bot / pushing account) detection. It does NOT ban
 * anyone; it produces a ranked list of suspicious account PAIRS with a risk
 * score (0-100) and a breakdown of WHY, so a human (admin / multihunter) can
 * investigate.
 *
 * Data sources (all optional — the engine degrades gracefully):
 *   - login_log          : existing table (uid, ip, date). Gives shared-IP and
 *                          login-timing signals for history that predates this
 *                          feature.
 *   - mad_session        : NEW table written by recordSession() at every login.
 *                          Adds the User-Agent signal (login_log has no UA).
 *   - movement / send    : in-flight merchant transfers -> "currently pushing"
 *                          trade signal.
 *   - resource_transfer_log : if present (created by the Push-Protection phase),
 *                          gives a richer historical trade-flow signal. Used
 *                          automatically when the table exists.
 *
 * The engine is intentionally self-contained (static methods, resolves the DB
 * link from globals) so it can be called from both the in-game login flow and
 * the admin panel without wiring.
 */
class MultiAccount
{
    /* ---- Tunables (safe to adjust) ------------------------------------- */

    /** How far back to look, in days. */
    const WINDOW_DAYS = 30;

    /** Hard cap on login rows scanned per source (memory / runtime guard). */
    const MAX_ROWS = 60000;

    /** A shared key (IP / subnet / UA) used by MORE than this many accounts is
     *  treated as a public/NAT/proxy artefact: it still counts, but with a
     *  reduced weight and it never explodes pair generation. */
    const POPULAR_KEY_CAP = 12;

    /** Two logins closer than this (seconds) from the SAME IP look like one
     *  person switching accounts. */
    const SWITCH_WINDOW = 900; // 15 minutes

    /** Only pairs at or above this score are returned. */
    const MIN_REPORT_SCORE = 20;

    /** Max pairs returned to the UI. */
    const MAX_PAIRS = 150;

    /** Default auto-ban score threshold (used only if the settings row is missing). */
    const DEFAULT_AUTO_BAN_SCORE = 90;

    /** In-request cache for getSettings(), same pattern as FeedingSystem::$settingsCache. */
    private static $settingsCache = null;

    /* ---- DB plumbing --------------------------------------------------- */

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
     * Create the mad_session table if it does not exist. Called lazily so the
     * feature works even on servers that never re-ran the installer.
     */
    public static function ensureSchema()
    {
        $link = self::link();
        if (!$link) {
            return;
        }
        $sql = "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "mad_session` (
            `id`         int(11)      NOT NULL AUTO_INCREMENT,
            `uid`        int(11)      NOT NULL,
            `ip`         varbinary(16) DEFAULT NULL,
            `ip_text`    varchar(45)  NOT NULL DEFAULT '',
            `ua_hash`    char(32)     NOT NULL DEFAULT '',
            `ua_text`    varchar(255) NOT NULL DEFAULT '',
            `login_time` int(11)      NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            KEY `uid` (`uid`),
            KEY `ip` (`ip`),
            KEY `ua_hash` (`ua_hash`),
            KEY `login_time` (`login_time`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        @mysqli_query($link, $sql);

        // Single-row settings table (id=1), same lazy-schema pattern as
        // FeedingSystem::ensureSchema() / feeding_settings.
        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "mad_settings` (
            `id`              int(11)    NOT NULL DEFAULT 1,
            `enabled`         tinyint(1) NOT NULL DEFAULT 0,
            `auto_ban`        tinyint(1) NOT NULL DEFAULT 0,
            `auto_ban_score`  int(11)    NOT NULL DEFAULT " . self::DEFAULT_AUTO_BAN_SCORE . ",
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        @mysqli_query($link, "INSERT IGNORE INTO `" . TB_PREFIX . "mad_settings`
            (`id`, `enabled`, `auto_ban`, `auto_ban_score`) VALUES (1, 0, 0, "
            . self::DEFAULT_AUTO_BAN_SCORE . ")");

        // Audit log of pairs already auto-banned, so applyAutoBan() never
        // re-processes (or re-logs) the same pair twice.
        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "mad_autoban_log` (
            `id`        int(11) NOT NULL AUTO_INCREMENT,
            `uid_a`     int(11) NOT NULL,
            `uid_b`     int(11) NOT NULL,
            `score`     int(11) NOT NULL DEFAULT 0,
            `banned_at` int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            UNIQUE KEY `pair` (`uid_a`, `uid_b`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        self::ensureBanlistPrevAccessColumn();
    }

    /**
     * `banlist` predates this feature and has no `prev_access` column on
     * older installs. banAccount() needs it to know what to restore
     * users.access to on unban (see banAccount()/unbanAccount() below), so
     * add it lazily here rather than requiring a manual migration.
     * MySQL 8 / MariaDB 10.5+ support "ADD COLUMN IF NOT EXISTS" directly;
     * older servers fall back to an information_schema check.
     */
    private static function ensureBanlistPrevAccessColumn()
    {
        $link = self::link();
        if (!$link) {
            return;
        }

        $added = @mysqli_query(
            $link,
            "ALTER TABLE `" . TB_PREFIX . "banlist`
             ADD COLUMN IF NOT EXISTS `prev_access` tinyint(3) DEFAULT NULL"
        );
        if ($added) {
            return; // server supports IF NOT EXISTS - done
        }

        // Fallback for older MySQL/MariaDB without "IF NOT EXISTS" support.
        $check = @mysqli_query(
            $link,
            "SELECT 1 FROM information_schema.columns
             WHERE table_schema = DATABASE()
               AND table_name = '" . TB_PREFIX . "banlist'
               AND column_name = 'prev_access' LIMIT 1"
        );
        if ($check && mysqli_num_rows($check) === 0) {
            @mysqli_query(
                $link,
                "ALTER TABLE `" . TB_PREFIX . "banlist` ADD COLUMN `prev_access` tinyint(3) DEFAULT NULL"
            );
        }
    }

    /* ---- Settings (admin panel toggles) --------------------------------- */

    /**
     * @return array{enabled:bool, auto_ban:bool, auto_ban_score:int}
     */
    public static function getSettings()
    {
        if (self::$settingsCache !== null) {
            return self::$settingsCache;
        }

        $default = ['enabled' => false, 'auto_ban' => false, 'auto_ban_score' => self::DEFAULT_AUTO_BAN_SCORE];

        $link = self::link();
        if (!$link) {
            return self::$settingsCache = $default;
        }
        self::ensureSchema();

        $res = @mysqli_query($link, "SELECT `enabled`, `auto_ban`, `auto_ban_score`
                                      FROM `" . TB_PREFIX . "mad_settings` WHERE `id` = 1 LIMIT 1");
        $row = $res ? mysqli_fetch_assoc($res) : null;
        if (!$row) {
            return self::$settingsCache = $default;
        }

        return self::$settingsCache = [
            'enabled'        => ((int) $row['enabled']) === 1,
            'auto_ban'       => ((int) $row['auto_ban']) === 1,
            'auto_ban_score' => max(1, min(100, (int) $row['auto_ban_score'])),
        ];
    }

    public static function isEnabled()
    {
        return self::getSettings()['enabled'];
    }

    public static function isAutoBanEnabled()
    {
        return self::getSettings()['auto_ban'];
    }

    public static function autoBanScore()
    {
        return self::getSettings()['auto_ban_score'];
    }

    /**
     * Update the admin-configurable toggles. Called only from
     * GameEngine/Admin/Mods/multiAccountSettings.php.
     *
     * @return bool success
     */
    public static function saveSettings($enabled, $autoBan, $autoBanScore)
    {
        $link = self::link();
        if (!$link) {
            return false;
        }
        self::ensureSchema();

        $enabled = $enabled ? 1 : 0;
        $autoBan = $autoBan ? 1 : 0;
        $score   = max(1, min(100, (int) $autoBanScore));

        $stmt = mysqli_prepare(
            $link,
            "UPDATE `" . TB_PREFIX . "mad_settings`
             SET `enabled` = ?, `auto_ban` = ?, `auto_ban_score` = ?
             WHERE `id` = 1"
        );
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'iii', $enabled, $autoBan, $score);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        self::$settingsCache = null; // invalidate cache
        return (bool) $ok;
    }

    /* ---- Data collection (called at login) ----------------------------- */

    /**
     * Record one login "session fingerprint". Best-effort: any failure is
     * swallowed so it can never block a login.
     *
     * @param int    $uid       User id.
     * @param string $ipText    Client IP (already resolved by the caller).
     * @param string $userAgent Raw User-Agent header ($_SERVER['HTTP_USER_AGENT']).
     */
    public static function recordSession($uid, $ipText, $userAgent)
    {
        try {
            $uid = (int) $uid;
            if ($uid <= 3) {
                return; // system accounts
            }

            $link = self::link();
            if (!$link) {
                return;
            }

            self::ensureSchema();

            $ipText = (string) $ipText;
            $packed = @inet_pton($ipText);
            if ($packed === false) {
                $packed = null;
            }
            $uaText = substr((string) $userAgent, 0, 255);
            $uaHash = md5($uaText);
            $now    = time();

            $stmt = mysqli_prepare(
                $link,
                "INSERT INTO `" . TB_PREFIX . "mad_session`
                 (uid, ip, ip_text, ua_hash, ua_text, login_time)
                 VALUES (?,?,?,?,?,?)"
            );
            if (!$stmt) {
                return;
            }
            // ip is binary -> bind as blob ('b') via send_long_data-free path:
            // mysqli lets us bind a string to a varbinary column with type 's'.
            $ipBind = $packed === null ? '' : $packed;
            mysqli_stmt_bind_param($stmt, 'issssi', $uid, $ipBind, $ipText, $uaHash, $uaText, $now);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } catch (\Throwable $e) {
            // never break login
        }
    }

    /* ---- Correlation / scoring ---------------------------------------- */

    /**
     * Build the ranked list of suspicious account pairs.
     *
     * @param array $opts  Optional overrides: 'days', 'min_score', 'focus_uid'.
     * @return array {
     *   'pairs'   => list of pair rows (see below), sorted by score desc,
     *   'scanned' => ['login_log'=>int, 'mad_session'=>int],
     *   'window_days' => int,
     *   'truncated' => bool,   // true if a source hit MAX_ROWS
     * }
     *
     * Each pair row:
     *   uid_a, name_a, uid_b, name_b, score (0-100), label,
     *   shared_ips, shared_subnets, shared_uas, switch_events,
     *   trade_gross, trade_dir, reasons (string[])
     */
    public static function riskPairs(array $opts = [])
    {
        $link = self::link();
        if (!$link) {
            return ['pairs' => [], 'scanned' => ['login_log' => 0, 'mad_session' => 0],
                    'window_days' => self::WINDOW_DAYS, 'truncated' => false, 'disabled' => false];
        }
        self::ensureSchema();

        // If the admin has switched detection OFF, skip all scanning/scoring
        // entirely and report the disabled state so the UI can show it.
        if (!self::isEnabled()) {
            return ['pairs' => [], 'scanned' => ['login_log' => 0, 'mad_session' => 0],
                    'window_days' => self::WINDOW_DAYS, 'truncated' => false, 'disabled' => true];
        }

        $days     = isset($opts['days']) ? max(1, (int) $opts['days']) : self::WINDOW_DAYS;
        $minScore = isset($opts['min_score']) ? (int) $opts['min_score'] : self::MIN_REPORT_SCORE;
        $focusUid = isset($opts['focus_uid']) ? (int) $opts['focus_uid'] : 0;
        $since    = time() - $days * 86400;

        // Per-uid aggregates and inverted indices.
        $ips     = [];   // uid => [ip => true]
        $subnets = [];   // uid => [subnet => true]
        $uas     = [];   // uid => [uaHash => true]
        $logins  = [];   // uid => [ [ts, ip], ... ]

        $ipIndex     = []; // ip     => [uid => true]
        $subnetIndex = []; // subnet => [uid => true]
        $uaIndex     = []; // uaHash => [uid => true]

        $scannedLogin = 0;
        $scannedMad   = 0;
        $truncated    = false;

        // ---- Source 1: login_log (uid, ip text, date) ----
        $cap = self::MAX_ROWS;
        $q = "SELECT uid, ip, UNIX_TIMESTAMP(`date`) AS ts
              FROM `" . TB_PREFIX . "login_log`
              WHERE uid > 3 AND UNIX_TIMESTAMP(`date`) >= " . (int) $since . "
              ORDER BY `date` DESC
              LIMIT " . (int) $cap;
        if ($res = @mysqli_query($link, $q)) {
            while ($row = mysqli_fetch_assoc($res)) {
                $scannedLogin++;
                self::ingest((int) $row['uid'], (string) $row['ip'], (int) $row['ts'], '',
                    $ips, $subnets, $uas, $logins, $ipIndex, $subnetIndex, $uaIndex);
            }
            mysqli_free_result($res);
            if ($scannedLogin >= $cap) {
                $truncated = true;
            }
        }

        // ---- Source 2: mad_session (adds UA) ----
        $q = "SELECT uid, ip_text, ua_hash, login_time
              FROM `" . TB_PREFIX . "mad_session`
              WHERE uid > 3 AND login_time >= " . (int) $since . "
              ORDER BY login_time DESC
              LIMIT " . (int) $cap;
        if ($res = @mysqli_query($link, $q)) {
            while ($row = mysqli_fetch_assoc($res)) {
                $scannedMad++;
                self::ingest((int) $row['uid'], (string) $row['ip_text'], (int) $row['login_time'],
                    (string) $row['ua_hash'],
                    $ips, $subnets, $uas, $logins, $ipIndex, $subnetIndex, $uaIndex);
            }
            mysqli_free_result($res);
            if ($scannedMad >= $cap) {
                $truncated = true;
            }
        }

        // ---- Candidate pair generation from shared keys ----
        $candidates = []; // "a:b" => true
        self::pairsFromIndex($ipIndex, $candidates, $focusUid);
        self::pairsFromIndex($subnetIndex, $candidates, $focusUid);
        self::pairsFromIndex($uaIndex, $candidates, $focusUid);

        // ---- Score each candidate pair ----
        $pairs = [];
        foreach ($candidates as $key => $_) {
            list($a, $b) = explode(':', $key);
            $a = (int) $a;
            $b = (int) $b;

            $scored = self::scorePair($a, $b, $ips, $subnets, $uas, $logins, $link, $days);
            if ($scored['score'] >= $minScore) {
                $pairs[] = $scored;
            }
        }

        // Resolve names for the pairs we keep (bounded set).
        self::attachNames($pairs, $link);

        // Sort by score desc, then trade gross desc.
        usort($pairs, function ($x, $y) {
            if ($x['score'] === $y['score']) {
                return $y['trade_gross'] <=> $x['trade_gross'];
            }
            return $y['score'] <=> $x['score'];
        });

        if (count($pairs) > self::MAX_PAIRS) {
            $pairs = array_slice($pairs, 0, self::MAX_PAIRS);
        }

        return [
            'pairs'       => $pairs,
            'scanned'     => ['login_log' => $scannedLogin, 'mad_session' => $scannedMad],
            'window_days' => $days,
            'truncated'   => $truncated,
            'disabled'    => false,
        ];
    }

    /* ---- Auto-ban -------------------------------------------------------- */

    /**
     * Ban both accounts of any pair whose score has reached the configured
     * auto-ban threshold, using the CURRENT default-window scoring.
     *
     * Deliberately kept OUT of riskPairs() itself: riskPairs() stays a pure,
     * read-only scoring pass (documented behaviour, relied upon elsewhere).
     * This method is the only place that can actually write a ban, and it
     * is only ever invoked from the admin Mod that saves/loads the multiacc
     * page (see GameEngine/Admin/Mods/multiAccountSettings.php and the
     * multiacc.tpl page load) — i.e. it runs when an admin/multihunter visits
     * the page, not as a background job. See MultiAccount.php class docblock
     * / the gap-analysis note for the cron follow-up.
     *
     * Ban attribution uses uid 0 ("System") rather than $_SESSION, so this
     * still works correctly if it is later wired into a cron/background
     * context that has no admin session.
     *
     * @return array List of pairs that were newly auto-banned in this call
     *                (each: uid_a, name_a, uid_b, name_b, score).
     */
    public static function applyAutoBan()
    {
        $link = self::link();
        if (!$link) {
            return [];
        }
        self::ensureSchema();

        if (!self::isEnabled() || !self::isAutoBanEnabled()) {
            return [];
        }
        $threshold = self::autoBanScore();

        $data = self::riskPairs(['min_score' => $threshold]);
        if (empty($data['pairs'])) {
            return [];
        }

        $newlyBanned = [];
        foreach ($data['pairs'] as $p) {
            if ($p['score'] < $threshold) {
                continue;
            }
            $a = min((int) $p['uid_a'], (int) $p['uid_b']);
            $b = max((int) $p['uid_a'], (int) $p['uid_b']);

            // Skip a pair we've already auto-banned before (idempotent).
            $chk = mysqli_prepare($link,
                "SELECT id FROM `" . TB_PREFIX . "mad_autoban_log` WHERE uid_a = ? AND uid_b = ? LIMIT 1");
            if (!$chk) {
                continue;
            }
            mysqli_stmt_bind_param($chk, 'ii', $a, $b);
            mysqli_stmt_execute($chk);
            mysqli_stmt_store_result($chk);
            $already = mysqli_stmt_num_rows($chk) > 0;
            mysqli_stmt_close($chk);
            if ($already) {
                continue;
            }

            if (self::banAccount($a, 0, 'Auto-ban: multi-account risk score ' . $p['score'] . '/100')
                && self::banAccount($b, 0, 'Auto-ban: multi-account risk score ' . $p['score'] . '/100')) {

                $ins = mysqli_prepare($link,
                    "INSERT IGNORE INTO `" . TB_PREFIX . "mad_autoban_log`
                     (uid_a, uid_b, score, banned_at) VALUES (?,?,?,?)");
                if ($ins) {
                    $now = time();
                    mysqli_stmt_bind_param($ins, 'iiii', $a, $b, $p['score'], $now);
                    mysqli_stmt_execute($ins);
                    mysqli_stmt_close($ins);
                }
                $newlyBanned[] = $p;
            }
        }

        return $newlyBanned;
    }

    /**
     * Ban a single account: inserts/reactivates its row in `banlist` (audit
     * trail — see GameEngine/Admin/Mods/mainteneceBan.php for the same
     * table) AND sets `users.access = BANNED` so the ban is actually
     * enforced. $adminUid = 0 is used for system/auto actions ("System").
     *
     * IMPORTANT — fixes a real enforcement gap: `Session::isBanned()` only
     * ever checks `users.access == BANNED`; nothing in the codebase wrote
     * to `users.access` from `banlist` before this. A `banlist` row alone
     * (the previous behaviour of this method) was recorded but did NOT
     * stop the player from logging in and playing normally. Both writes
     * now happen together so a ban is real, not just logged.
     *
     * Refuses to ban an account currently at MULTIHUNTER (8) or ADMIN (9)
     * access — an auto-ban path should never be able to lock out staff.
     * The account's pre-ban access is stored in `banlist.prev_access` so
     * unbanAccount() can restore it exactly rather than assuming USER (2).
     *
     * A permanent ban is modelled the same way the rest of the codebase
     * would model "indefinite": a far-future `end` timestamp, since
     * `banlist` has no separate "permanent" flag.
     */
    public static function banAccount($uid, $adminUid, $reason)
    {
        $link = self::link();
        if (!$link) {
            return false;
        }
        self::ensureSchema(); // lazy-adds banlist.prev_access on older installs
        $uid = (int) $uid;
        if ($uid <= 3) {
            return false; // never touch system accounts
        }

        $nameRes = mysqli_query($link, "SELECT username, access FROM `" . TB_PREFIX . "users` WHERE id = " . $uid);
        $nameRow = $nameRes ? mysqli_fetch_assoc($nameRes) : null;
        if (!$nameRow) {
            return false; // account does not exist
        }
        $username   = $nameRow['username'];
        $prevAccess = (int) $nameRow['access'];

        if ($prevAccess >= MULTIHUNTER) {
            return false; // never auto-ban staff (multihunter/admin) accounts
        }
        if ($prevAccess === BANNED) {
            $prevAccess = USER; // already banned elsewhere; restore to a sane default later
        }

        $now       = time();
        $farFuture = $now + (10 * 365 * 86400); // ~10 years, effectively permanent

        // `banlist`.`reason` is varchar(30) (see struct.sql). Truncate here,
        // centrally, so every caller (applyAutoBan(), banAttacker() via
        // RelatedAccountProtection, manual admin bans, etc.) is protected
        // without each one needing to remember the column limit. Confirmed
        // by an actual integration test: without this, the INSERT/UPDATE
        // below throws under mysqli's default exception-reporting mode
        // (PHP 8.1+), and callers that wrap this in a try/catch (like
        // RelatedAccountProtection::banAttacker()) end up silently
        // swallowing the failure and returning false with NO ban applied
        // and no visible error anywhere.
        // Plain substr() (byte-based), not mb_substr(): mbstring is not
        // guaranteed to be installed, and this matches the existing
        // pattern used in RelatedAccountProtection::addRelation()
        // (substr((string)$reason, 0, 255)).
        $reason      = substr((string) $reason, 0, 30);
        $reasonEsc   = mysqli_real_escape_string($link, $reason);
        $usernameEsc = mysqli_real_escape_string($link, (string) $username);

        // `banlist` has no UNIQUE key on uid (verified against struct.sql),
        // so "ON DUPLICATE KEY UPDATE" would not reliably catch an existing
        // active ban row for this uid. Check first, then UPDATE or INSERT —
        // avoids creating duplicate active rows for the same account.
        $existing = mysqli_query($link,
            "SELECT id FROM `" . TB_PREFIX . "banlist` WHERE uid = " . $uid . " AND active = 1 LIMIT 1");
        $existingRow = $existing ? mysqli_fetch_assoc($existing) : null;

        if ($existingRow) {
            // Already actively banned — keep the original prev_access (don't
            // overwrite it with BANNED from a prior ban), just refresh reason/end.
            $ok = mysqli_query($link,
                "UPDATE `" . TB_PREFIX . "banlist`
                 SET reason = '" . $reasonEsc . "', time = " . $now . ", end = " . $farFuture . ",
                     admin = " . (int) $adminUid . ", active = 1
                 WHERE id = " . (int) $existingRow['id']);
        } else {
            $ok = mysqli_query($link,
                "INSERT INTO `" . TB_PREFIX . "banlist` (uid, name, reason, time, end, admin, active, prev_access)
                 VALUES (" . $uid . ", '" . $usernameEsc . "', '" . $reasonEsc . "', " . $now . ", " . $farFuture . ", " . (int) $adminUid . ", 1, " . $prevAccess . ")");
        }

        if ($ok) {
            // The actual enforcement write — see docblock above.
            mysqli_query($link, "UPDATE `" . TB_PREFIX . "users` SET access = " . BANNED . " WHERE id = " . $uid);
            self::adminLog((int) $adminUid, 'Multi-Account: banned uid ' . $uid . ' (' . $username . ') — ' . $reason);
        }
        return (bool) $ok;
    }

    /**
     * Unban a single account: deactivates its active banlist row(s) AND
     * restores `users.access` to what it was before the ban (from
     * `banlist.prev_access`), rather than leaving it at BANNED — the
     * counterpart fix to banAccount() above. Falls back to USER (2) if no
     * prev_access is on record (e.g. a row from before this column existed).
     */
    public static function unbanAccount($uid, $adminUid, $reason = 'manual unban')
    {
        $link = self::link();
        if (!$link) {
            return false;
        }
        self::ensureSchema();
        $uid = (int) $uid;
        if ($uid <= 3) {
            return false;
        }

        $prevRes = mysqli_query($link,
            "SELECT prev_access FROM `" . TB_PREFIX . "banlist`
             WHERE uid = " . $uid . " AND active = 1 ORDER BY id DESC LIMIT 1");
        $prevRow = $prevRes ? mysqli_fetch_assoc($prevRes) : null;
        $restoreAccess = ($prevRow && $prevRow['prev_access'] !== null)
            ? (int) $prevRow['prev_access']
            : USER;
        if ($restoreAccess >= MULTIHUNTER) {
            $restoreAccess = USER; // safety net — never restore to staff level from this path
        }

        $now = time();
        $ok = mysqli_query($link,
            "UPDATE `" . TB_PREFIX . "banlist` SET active = 0, end = " . $now . "
             WHERE uid = " . $uid . " AND active = 1");

        if ($ok) {
            mysqli_query($link, "UPDATE `" . TB_PREFIX . "users` SET access = " . $restoreAccess . " WHERE id = " . $uid);
            self::adminLog((int) $adminUid, 'Multi-Account: unbanned uid ' . $uid . ' — ' . $reason);
        }
        return (bool) $ok;
    }

    /** Shared admin_log writer, same INSERT pattern used by pushOverride.php etc. */
    private static function adminLog($adminUid, $text)
    {
        $link = self::link();
        if (!$link) {
            return;
        }
        $logMsg = mysqli_real_escape_string($link, (string) $text);
        @mysqli_query($link,
            "INSERT INTO `" . TB_PREFIX . "admin_log` VALUES (0, " . (int) $adminUid . ", '" . $logMsg . "', " . time() . ")");
    }

    /** Fold one login event into all aggregates + indices. */
    private static function ingest($uid, $ip, $ts, $uaHash,
        &$ips, &$subnets, &$uas, &$logins, &$ipIndex, &$subnetIndex, &$uaIndex)
    {
        if ($uid <= 3) {
            return;
        }
        $ip = trim($ip);
        if ($ip !== '' && $ip !== '0.0.0.0') {
            $ips[$uid][$ip]         = true;
            $ipIndex[$ip][$uid]     = true;
            $sub = self::subnet($ip);
            if ($sub !== '') {
                $subnets[$uid][$sub]      = true;
                $subnetIndex[$sub][$uid]  = true;
            }
        }
        if ($uaHash !== '' && $uaHash !== md5('')) {
            $uas[$uid][$uaHash]     = true;
            $uaIndex[$uaHash][$uid] = true;
        }
        if ($ts > 0) {
            $logins[$uid][] = [$ts, $ip];
        }
    }

    /** /24 for IPv4, /48-ish (first 3 hextets) for IPv6. */
    private static function subnet($ip)
    {
        if (strpos($ip, '.') !== false) {
            $p = explode('.', $ip);
            if (count($p) === 4) {
                return $p[0] . '.' . $p[1] . '.' . $p[2] . '.';
            }
        } elseif (strpos($ip, ':') !== false) {
            $p = explode(':', $ip);
            return $p[0] . ':' . ($p[1] ?? '') . ':' . ($p[2] ?? '') . '::';
        }
        return '';
    }

    /**
     * Turn an inverted index (key => [uid => true]) into candidate pairs.
     * Skips popular keys (public NAT/proxy) to avoid noise and O(n^2) blowup.
     */
    private static function pairsFromIndex(array $index, array &$candidates, $focusUid)
    {
        foreach ($index as $key => $uidSet) {
            $uids = array_keys($uidSet);
            $n = count($uids);
            if ($n < 2 || $n > self::POPULAR_KEY_CAP) {
                continue;
            }
            sort($uids);
            for ($i = 0; $i < $n; $i++) {
                for ($j = $i + 1; $j < $n; $j++) {
                    $a = $uids[$i];
                    $b = $uids[$j];
                    if ($focusUid && $a !== $focusUid && $b !== $focusUid) {
                        continue;
                    }
                    $candidates[$a . ':' . $b] = true;
                }
            }
        }
    }

    /** Score a single (a,b) pair and produce a reason breakdown. */
    private static function scorePair($a, $b, $ips, $subnets, $uas, $logins, $link, $days)
    {
        $sharedIps = array_keys(array_intersect_key(
            $ips[$a] ?? [], $ips[$b] ?? []
        ));
        $sharedSubs = array_keys(array_intersect_key(
            $subnets[$a] ?? [], $subnets[$b] ?? []
        ));
        $sharedUas = array_keys(array_intersect_key(
            $uas[$a] ?? [], $uas[$b] ?? []
        ));

        // Subnets that are shared but NOT already covered by a shared full IP.
        // Compare exact subnets (self::subnet) — a string-prefix test would treat
        // e.g. "85.204.1." as a prefix of IP "85.204.13.9" (false positive).
        $subOnly = [];
        foreach ($sharedSubs as $s) {
            $hasFull = false;
            foreach ($sharedIps as $ip) {
                if (self::subnet($ip) === $s) {
                    $hasFull = true;
                    break;
                }
            }
            if (!$hasFull) {
                $subOnly[] = $s;
            }
        }

        // Login "switch" events: logins of a and b from the same shared IP within
        // SWITCH_WINDOW seconds of each other.
        $switch = self::switchEvents($logins[$a] ?? [], $logins[$b] ?? [], $sharedIps);

        // Trade flow between the pair (villages resolved inside).
        $trade = self::tradeFlow($a, $b, $link, $days);

        // ---- Weighted score ----
        $score   = 0;
        $reasons = [];

        if (count($sharedIps) > 0) {
            $add = min(50, 35 + 5 * (count($sharedIps) - 1));
            $score += $add;
            $reasons[] = count($sharedIps) . ' shared IP' . (count($sharedIps) > 1 ? 's' : '');
        }
        if (count($sharedUas) > 0) {
            $add = min(30, 20 + 5 * (count($sharedUas) - 1));
            $score += $add;
            $reasons[] = count($sharedUas) . ' identical device/browser fingerprint'
                . (count($sharedUas) > 1 ? 's' : '');
        }
        if (count($subOnly) > 0) {
            $score += 10;
            $reasons[] = 'same /24 subnet';
        }
        if ($switch > 0) {
            $add = min(30, 15 + 5 * $switch);
            $score += $add;
            $reasons[] = $switch . ' rapid account switch' . ($switch > 1 ? 'es' : '')
                . ' from one IP';
        }
        if ($trade['gross'] > 0 && $trade['directional']) {
            $score += 20;
            $reasons[] = 'one-directional resource transfers';
        } elseif ($trade['gross'] > 0) {
            $score += 8;
            $reasons[] = 'resource transfers between them';
        }

        $score = (int) min(100, $score);
        $label = $score >= 70 ? 'High' : ($score >= 40 ? 'Medium' : 'Low');

        return [
            'uid_a'          => $a,
            'uid_b'          => $b,
            'name_a'         => '',
            'name_b'         => '',
            'score'          => $score,
            'label'          => $label,
            'shared_ips'     => count($sharedIps),
            'shared_ip_list' => array_slice($sharedIps, 0, 6),
            'shared_subnets' => count($subOnly),
            'shared_uas'     => count($sharedUas),
            'switch_events'  => $switch,
            'trade_gross'    => (int) $trade['gross'],
            'trade_dir'      => $trade['directional'] ? 1 : 0,
            'reasons'        => $reasons,
        ];
    }

    /** Count login "switches" from a shared IP within SWITCH_WINDOW seconds. */
    private static function switchEvents(array $la, array $lb, array $sharedIps)
    {
        if (empty($sharedIps) || empty($la) || empty($lb)) {
            return 0;
        }
        $sharedSet = array_flip($sharedIps);

        // Keep only events from shared IPs.
        $ea = [];
        foreach ($la as $e) {
            if (isset($sharedSet[$e[1]])) {
                $ea[] = $e[0];
            }
        }
        $eb = [];
        foreach ($lb as $e) {
            if (isset($sharedSet[$e[1]])) {
                $eb[] = $e[0];
            }
        }
        if (empty($ea) || empty($eb)) {
            return 0;
        }
        sort($ea);
        sort($eb);

        // Two-pointer count of a<->b logins within the window.
        $count = 0;
        $j = 0;
        $m = count($eb);
        foreach ($ea as $ta) {
            while ($j < $m && $eb[$j] < $ta - self::SWITCH_WINDOW) {
                $j++;
            }
            $k = $j;
            while ($k < $m && $eb[$k] <= $ta + self::SWITCH_WINDOW) {
                $count++;
                $k++;
                if ($count >= 20) {
                    return 20; // cap
                }
            }
        }
        return $count;
    }

    /**
     * Resource-transfer signal between two players.
     * Prefers resource_transfer_log (Push-Protection phase) when present, else
     * falls back to in-flight merchant movements. Returns gross total moved and
     * whether flow is strongly one-directional.
     */
    private static function tradeFlow($a, $b, $link, $days)
    {
        $out = ['gross' => 0, 'directional' => false];

        // Village ids owned by each player.
        $va = self::villagesOf($a, $link);
        $vb = self::villagesOf($b, $link);
        if (empty($va) || empty($vb)) {
            return $out;
        }
        $vaIn = implode(',', array_map('intval', $va));
        $vbIn = implode(',', array_map('intval', $vb));
        $since = time() - $days * 86400;

        $aToB = 0;
        $bToA = 0;

        // Preferred: persistent transfer log (created by push protection).
        $hasLog = @mysqli_query($link,
            "SELECT 1 FROM `" . TB_PREFIX . "resource_transfer_log` LIMIT 1");
        if ($hasLog !== false) {
            @mysqli_free_result($hasLog);
            $sumCols = "(wood+clay+iron+crop)";
            $r = @mysqli_query($link,
                "SELECT COALESCE(SUM($sumCols),0) FROM `" . TB_PREFIX . "resource_transfer_log`
                 WHERE from_vref IN ($vaIn) AND to_vref IN ($vbIn) AND `time` >= " . (int) $since);
            if ($r && ($row = mysqli_fetch_row($r))) {
                $aToB = (int) $row[0];
            }
            $r = @mysqli_query($link,
                "SELECT COALESCE(SUM($sumCols),0) FROM `" . TB_PREFIX . "resource_transfer_log`
                 WHERE from_vref IN ($vbIn) AND to_vref IN ($vaIn) AND `time` >= " . (int) $since);
            if ($r && ($row = mysqli_fetch_row($r))) {
                $bToA = (int) $row[0];
            }
        } else {
            // Fallback: in-flight merchant transfers (movement sort_type = 0),
            // resources live in the linked `send` row (m.ref = s.id).
            $sql = "SELECT COALESCE(SUM(s.wood+s.clay+s.iron+s.crop),0)
                    FROM `" . TB_PREFIX . "movement` m
                    JOIN `" . TB_PREFIX . "send` s ON m.ref = s.id
                    WHERE m.sort_type = 0 AND m.proc = 0
                      AND m.`from` IN (%FROM%) AND m.`to` IN (%TO%)";
            $r = @mysqli_query($link, str_replace(['%FROM%', '%TO%'], [$vaIn, $vbIn], $sql));
            if ($r && ($row = mysqli_fetch_row($r))) {
                $aToB = (int) $row[0];
            }
            $r = @mysqli_query($link, str_replace(['%FROM%', '%TO%'], [$vbIn, $vaIn], $sql));
            if ($r && ($row = mysqli_fetch_row($r))) {
                $bToA = (int) $row[0];
            }
        }

        $gross = $aToB + $bToA;
        $out['gross'] = $gross;
        if ($gross > 0) {
            $maxDir = max($aToB, $bToA);
            // Strongly one-directional if >= 85% flows one way and it is material.
            $out['directional'] = ($maxDir >= 0.85 * $gross) && ($gross >= 5000);
        }
        return $out;
    }

    /** Village wrefs owned by a user (cached per-request). */
    private static function villagesOf($uid, $link)
    {
        static $cache = [];
        $uid = (int) $uid;
        if (isset($cache[$uid])) {
            return $cache[$uid];
        }
        $out = [];
        $r = @mysqli_query($link,
            "SELECT wref FROM `" . TB_PREFIX . "vdata` WHERE owner = " . $uid);
        if ($r) {
            while ($row = mysqli_fetch_row($r)) {
                $out[] = (int) $row[0];
            }
            mysqli_free_result($r);
        }
        return $cache[$uid] = $out;
    }

    /** Fill name_a / name_b for the reported pairs in one query. */
    private static function attachNames(array &$pairs, $link)
    {
        if (empty($pairs)) {
            return;
        }
        $ids = [];
        foreach ($pairs as $p) {
            $ids[$p['uid_a']] = true;
            $ids[$p['uid_b']] = true;
        }
        $in = implode(',', array_map('intval', array_keys($ids)));
        $names = [];
        $r = @mysqli_query($link,
            "SELECT id, username FROM `" . TB_PREFIX . "users` WHERE id IN ($in)");
        if ($r) {
            while ($row = mysqli_fetch_assoc($r)) {
                $names[(int) $row['id']] = $row['username'];
            }
            mysqli_free_result($r);
        }
        foreach ($pairs as &$p) {
            $p['name_a'] = $names[$p['uid_a']] ?? ('#' . $p['uid_a']);
            $p['name_b'] = $names[$p['uid_b']] ?? ('#' . $p['uid_b']);
        }
        unset($p);
    }
}