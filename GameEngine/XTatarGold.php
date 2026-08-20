<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : XTatarGold.php                                            ##
##  Type           : Activity-based FREE gold system (X-Tatar.com)             ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow                                                    ##
##  Project        : Novaterra                                                 ##
##  License        : Novaterra Project                                        ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.             ##
## --------------------------------------------------------------------------- ##
#################################################################################

/**
 * XTatarGold
 * -------------------------------------------------------------------------
 * Players earn FREE gold based on their activity on X-Tatar.com and/or
 * in-game. Unlike CentralGold (paid gold, shared across every world), this
 * balance is intentionally LOCAL to a single world: it is credited straight
 * into this world's `users.gold`, and per the client brief the whole
 * feature can be switched on/off from the admin panel.
 *
 * Design: players accumulate whole-number "activity points" from one or more
 * sources (in-game daily login, external X-Tatar.com activity via a signed
 * webhook, or a manual admin adjustment). Points convert to gold in whole-
 * gold chunks the moment enough points have accumulated (points_per_gold,
 * admin-configurable) — the leftover remainder under a full gold's worth of
 * points is kept so nothing is ever lost between conversions.
 *
 * Self-contained (static, resolves the world's existing mysqli link from
 * globals, self-creates its tables) so it behaves like GoldShop/CentralGold
 * and can be dropped into any page without a bigger refactor.
 *
 * External integration: X-Tatar.com is expected to award points by POSTing
 * to xtatar_gold_webhook.php (see that file) with a shared secret configured
 * in xtatar_gold_settings.webhook_secret. Until that secret is set from the
 * admin panel, the webhook endpoint rejects every request — in-game sources
 * (daily login) keep working regardless, so the feature is safe to enable
 * before the website side is wired up.
 */
class XTatarGold
{
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

    public static function ensureSchema()
    {
        $link = self::link();
        if (!$link) {
            return;
        }

        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `xtatar_gold_settings` (
            `id`                 tinyint(1) NOT NULL DEFAULT 1,
            `enabled`            tinyint(1) NOT NULL DEFAULT 1,
            `points_per_gold`    int(11) NOT NULL DEFAULT 100,
            `daily_login_points` int(11) NOT NULL DEFAULT 5,
            `daily_cap_points`   int(11) NOT NULL DEFAULT 0,
            `webhook_secret`     varchar(128) NOT NULL DEFAULT '',
            `updated`            int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        @mysqli_query($link,
            "INSERT IGNORE INTO `xtatar_gold_settings`
             (`id`, `enabled`, `points_per_gold`, `daily_login_points`, `daily_cap_points`, `webhook_secret`, `updated`)
             VALUES (1, 1, 100, 5, 500, '', " . time() . ")");

        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `xtatar_gold_points` (
            `uid`                   int(11) NOT NULL,
            `points`                int(11) NOT NULL DEFAULT 0,
            `total_earned`          int(11) NOT NULL DEFAULT 0,
            `total_converted_gold`  int(11) NOT NULL DEFAULT 0,
            `last_login_award_date` date DEFAULT NULL,
            `points_today`          int(11) NOT NULL DEFAULT 0,
            `points_today_date`     date DEFAULT NULL,
            `updated`               int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`uid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `xtatar_gold_log` (
            `id`         int(11) NOT NULL AUTO_INCREMENT,
            `uid`        int(11) NOT NULL,
            `type`       enum('points_awarded','gold_converted','admin_adjust') NOT NULL,
            `points`     int(11) NOT NULL DEFAULT 0,
            `gold`       int(11) NOT NULL DEFAULT 0,
            `source`     varchar(64) NOT NULL DEFAULT '',
            `note`       varchar(255) NOT NULL DEFAULT '',
            `admin_id`   int(11) NOT NULL DEFAULT 0,
            `time`       int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            KEY `uid` (`uid`),
            KEY `time` (`time`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    /* ---- Settings ----------------------------------------------------- */

    public static function settings()
    {
        $link = self::link();
        $default = [
            'enabled' => 1, 'points_per_gold' => 100, 'daily_login_points' => 5,
            'daily_cap_points' => 500, 'webhook_secret' => '',
        ];
        if (!$link) {
            return $default;
        }
        self::ensureSchema();
        $res = @mysqli_query($link, "SELECT * FROM xtatar_gold_settings WHERE id = 1 LIMIT 1");
        $row = $res ? mysqli_fetch_assoc($res) : null;
        return $row ?: $default;
    }

    public static function isEnabled()
    {
        return (int) self::settings()['enabled'] === 1;
    }

    /**
     * @return bool ok
     */
    public static function updateSettings($enabled, $pointsPerGold, $dailyLoginPoints, $dailyCapPoints, $webhookSecret)
    {
        $link = self::link();
        if (!$link) {
            return false;
        }
        self::ensureSchema();
        $enabled = $enabled ? 1 : 0;
        $pointsPerGold = max(1, (int) $pointsPerGold);
        $dailyLoginPoints = max(0, (int) $dailyLoginPoints);
        $dailyCapPoints = max(0, (int) $dailyCapPoints);
        $webhookSecret = substr((string) $webhookSecret, 0, 128);
        $now = time();

        $stmt = mysqli_prepare($link,
            "UPDATE xtatar_gold_settings
             SET enabled = ?, points_per_gold = ?, daily_login_points = ?, daily_cap_points = ?, webhook_secret = ?, updated = ?
             WHERE id = 1");
        mysqli_stmt_bind_param($stmt, 'iiiisi',
            $enabled, $pointsPerGold, $dailyLoginPoints, $dailyCapPoints, $webhookSecret, $now);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    /* ---- Point + gold state read ---------------------------------------- */

    private static function rowFor($uid)
    {
        $link = self::link();
        $uid = (int) $uid;
        $stmt = mysqli_prepare($link, "SELECT * FROM xtatar_gold_points WHERE uid = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'i', $uid);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
        if ($row) {
            return $row;
        }
        $ins = mysqli_prepare($link,
            "INSERT IGNORE INTO xtatar_gold_points (uid, points, total_earned, total_converted_gold, updated)
             VALUES (?, 0, 0, 0, ?)");
        $now = time();
        mysqli_stmt_bind_param($ins, 'ii', $uid, $now);
        mysqli_stmt_execute($ins);
        mysqli_stmt_close($ins);
        return [
            'uid' => $uid, 'points' => 0, 'total_earned' => 0, 'total_converted_gold' => 0,
            'last_login_award_date' => null, 'points_today' => 0, 'points_today_date' => null,
        ];
    }

    /** Current point balance (not-yet-converted remainder) for a player. */
    public static function pointBalance($uid)
    {
        return (int) self::rowFor($uid)['points'];
    }

    /* ---- Awarding points --------------------------------------------------
     * Every award path funnels through awardPoints() so the daily cap, the
     * points->gold conversion, and the audit log are all applied uniformly
     * regardless of source.
     */

    /**
     * Award activity points to a player and auto-convert any whole gold
     * earned. Fails soft (no-op) if the feature is disabled or misconfigured
     * so callers never need their own enabled-check before calling this.
     *
     * @return array [ok(bool), pointsAwarded(int), goldCredited(int)]
     */
    public static function awardPoints($uid, $points, $source, $note = '', $adminId = 0)
    {
        $link = self::link();
        if (!$link) {
            return [false, 0, 0];
        }
        self::ensureSchema();

        $settings = self::settings();
        if ((int) $settings['enabled'] !== 1) {
            return [false, 0, 0];
        }

        $uid = (int) $uid;
        $points = (int) $points;
        if ($uid <= 3 || $points <= 0) {
            return [false, 0, 0]; // system accounts, or nothing to award
        }

        $row = self::rowFor($uid);
        $today = date('Y-m-d');
        $pointsToday = ($row['points_today_date'] === $today) ? (int) $row['points_today'] : 0;

        $cap = (int) $settings['daily_cap_points'];
        if ($cap > 0) {
            $remaining = $cap - $pointsToday;
            if ($remaining <= 0) {
                return [false, 0, 0]; // daily cap already reached
            }
            $points = min($points, $remaining);
        }

        $now = time();
        $newPointsToday = $pointsToday + $points;

        $stmt = mysqli_prepare($link,
            "UPDATE xtatar_gold_points
             SET points = points + ?, total_earned = total_earned + ?,
                 points_today = ?, points_today_date = ?, updated = ?
             WHERE uid = ?");
        mysqli_stmt_bind_param($stmt, 'iiisii', $points, $points, $newPointsToday, $today, $now, $uid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        self::log($uid, 'points_awarded', $points, 0, $source, $note, $adminId);

        $goldCredited = self::convertPointsToGold($uid, $settings);

        return [true, $points, $goldCredited];
    }

    /**
     * Convert as much of a player's accumulated points as divide evenly into
     * whole gold, credit that gold into the local `users.gold`, and leave
     * the remainder in xtatar_gold_points.points for next time.
     *
     * @return int gold credited (0 if not enough points yet)
     */
    private static function convertPointsToGold($uid, $settings = null)
    {
        $link = self::link();
        $settings = $settings ?: self::settings();
        $perGold = max(1, (int) $settings['points_per_gold']);

        $row = self::rowFor($uid);
        $points = (int) $row['points'];
        $goldEarned = intdiv($points, $perGold);
        if ($goldEarned <= 0) {
            return 0;
        }
        $pointsSpent = $goldEarned * $perGold;
        $now = time();

        $stmt = mysqli_prepare($link,
            "UPDATE xtatar_gold_points
             SET points = points - ?, total_converted_gold = total_converted_gold + ?, updated = ?
             WHERE uid = ? AND points >= ?");
        mysqli_stmt_bind_param($stmt, 'iiiii', $pointsSpent, $goldEarned, $now, $uid, $pointsSpent);
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        if ($affected !== 1) {
            return 0; // lost a race with a concurrent conversion; try next award
        }

        mysqli_query($link, "UPDATE " . TB_PREFIX . "users SET gold = gold + $goldEarned WHERE id = " . (int) $uid);

        self::log($uid, 'gold_converted', $pointsSpent, $goldEarned, 'auto_convert',
            $goldEarned . ' gold from ' . $pointsSpent . ' points', 0);

        return $goldEarned;
    }

    /* ---- In-game source: daily login --------------------------------------
     * Call once per successful login (see GameEngine/Session.php). No-ops if
     * this player already got their login award today, or if the feature/
     * this specific source is disabled (daily_login_points = 0).
     */
    public static function awardDailyLogin($uid)
    {
        $link = self::link();
        if (!$link) {
            return;
        }
        self::ensureSchema();

        $settings = self::settings();
        if ((int) $settings['enabled'] !== 1 || (int) $settings['daily_login_points'] <= 0) {
            return;
        }

        $uid = (int) $uid;
        $row = self::rowFor($uid);
        $today = date('Y-m-d');
        if ($row['last_login_award_date'] === $today) {
            return; // already awarded today
        }

        // Mark the date first (separately from awardPoints' own UPDATE) so a
        // page reload during the same request can't double-award even if
        // awardPoints() itself is skipped by the daily cap.
        $stmt = mysqli_prepare($link, "UPDATE xtatar_gold_points SET last_login_award_date = ? WHERE uid = ?");
        mysqli_stmt_bind_param($stmt, 'si', $today, $uid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        self::awardPoints($uid, (int) $settings['daily_login_points'], 'daily_login', 'First login of the day');
    }

    /* ---- Admin: manual point adjustment ------------------------------------ */

    /** Admin grants or removes points directly (e.g. correcting a webhook gap). */
    public static function adminAdjustPoints($uid, $delta, $adminId, $note = '')
    {
        $delta = (int) $delta;
        if ($delta === 0) {
            return [false, 'Nothing to apply.'];
        }
        if ($delta > 0) {
            list($ok) = self::awardPoints($uid, $delta, 'admin_adjust', $note, $adminId);
            return [$ok, $ok ? 'Points added.' : 'Feature disabled or invalid user.'];
        }

        $link = self::link();
        if (!$link) {
            return [false, 'No database connection.'];
        }
        $uid = (int) $uid;
        $abs = abs($delta);
        $stmt = mysqli_prepare($link,
            "UPDATE xtatar_gold_points SET points = points - ?, updated = ? WHERE uid = ? AND points >= ?");
        $now = time();
        mysqli_stmt_bind_param($stmt, 'iiii', $abs, $now, $uid, $abs);
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        if ($affected !== 1) {
            return [false, 'Player does not have that many points.'];
        }
        self::log($uid, 'admin_adjust', $delta, 0, 'admin_adjust', $note, $adminId);
        return [true, 'Points removed.'];
    }

    /* ---- Logging + admin views --------------------------------------------- */

    private static function log($uid, $type, $points, $gold, $source, $note, $adminId)
    {
        $link = self::link();
        if (!$link) {
            return;
        }
        $uid = (int) $uid;
        $points = (int) $points;
        $gold = (int) $gold;
        $source = substr((string) $source, 0, 64);
        $note = substr((string) $note, 0, 255);
        $adminId = (int) $adminId;
        $now = time();
        $stmt = mysqli_prepare($link,
            "INSERT INTO xtatar_gold_log (uid, type, points, gold, source, note, admin_id, time)
             VALUES (?,?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'isiissii', $uid, $type, $points, $gold, $source, $note, $adminId, $now);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    /** Recent log entries for the admin view, optionally filtered to one player. */
    public static function recentLog($limit = 50, $uid = 0)
    {
        $link = self::link();
        $out = [];
        if (!$link) {
            return $out;
        }
        $limit = max(1, min(300, (int) $limit));
        $uid = (int) $uid;
        if ($uid > 0) {
            $stmt = mysqli_prepare($link,
                "SELECT l.*, u.username FROM xtatar_gold_log l
                 LEFT JOIN " . TB_PREFIX . "users u ON u.id = l.uid
                 WHERE l.uid = ? ORDER BY l.id DESC LIMIT ?");
            mysqli_stmt_bind_param($stmt, 'ii', $uid, $limit);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
        } else {
            $res = mysqli_query($link,
                "SELECT l.*, u.username FROM xtatar_gold_log l
                 LEFT JOIN " . TB_PREFIX . "users u ON u.id = l.uid
                 ORDER BY l.id DESC LIMIT " . $limit);
        }
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $out[] = $row;
            }
        }
        return $out;
    }

    /** Top players by total points earned (admin leaderboard view). */
    public static function topEarners($limit = 20)
    {
        $link = self::link();
        $out = [];
        if (!$link) {
            return $out;
        }
        $limit = max(1, min(200, (int) $limit));
        $r = @mysqli_query($link,
            "SELECT p.*, u.username FROM xtatar_gold_points p
             JOIN " . TB_PREFIX . "users u ON u.id = p.uid
             ORDER BY p.total_earned DESC LIMIT " . $limit);
        if ($r) {
            while ($row = mysqli_fetch_assoc($r)) {
                $out[] = $row;
            }
        }
        return $out;
    }
}
