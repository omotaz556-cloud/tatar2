<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : Punishment.php                                            ##
##  Type           : Punishment / Restriction engine (mute, market, army)      ##
## --------------------------------------------------------------------------- ##
##  Purpose        : Extends the existing full-account ban system (banlist)    ##
##                    with lighter, targeted restrictions an admin can apply   ##
##                    to a specific player without taking their account away:  ##
##                      - mute        : blocks alliance/public chat            ##
##                      - market      : blocks all Marketplace actions         ##
##                      - army        : blocks sending troops out + training   ##
##                    Every restriction has a reason, an admin, a start time   ##
##                    and an expiry (0 = permanent, lifted manually).          ##
## --------------------------------------------------------------------------- ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################

class Punishment
{
    const TYPE_MUTE   = 'mute';
    const TYPE_MARKET = 'market';
    const TYPE_ARMY   = 'army';

    /** All valid restriction types (does NOT include full ban - that stays in banlist/MultiAccount). */
    public static function validTypes()
    {
        return [self::TYPE_MUTE, self::TYPE_MARKET, self::TYPE_ARMY];
    }

    /** Resolve the raw mysqli link from whatever context we run in. */
    private static function link()
    {
        if (isset($GLOBALS['database']) && isset($GLOBALS['database']->dblink)) {
            return $GLOBALS['database']->dblink;
        }
        return null;
    }

    /** Lazily create the punishments table (same pattern as MultiAccount/RegBlock/Feeding). */
    public static function ensureSchema()
    {
        $link = self::link();
        if (!$link) {
            return;
        }

        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "punishments` (
            `id`         int(11)      NOT NULL AUTO_INCREMENT,
            `uid`        int(11)      NOT NULL,
            `type`       varchar(16)  NOT NULL,
            `reason`     varchar(255) NOT NULL DEFAULT '',
            `admin`      int(11)      NOT NULL DEFAULT 0,
            `start`      int(11)      NOT NULL DEFAULT 0,
            `end`        int(11)      NOT NULL DEFAULT 0,
            `active`     tinyint(1)   NOT NULL DEFAULT 1,
            `lifted_by`  int(11)      NOT NULL DEFAULT 0,
            `lifted_at`  int(11)      NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            KEY `uid_type_active` (`uid`, `type`, `active`),
            KEY `end` (`end`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    }

    /**
     * Apply a restriction to a player.
     *
     * @param int    $uid        target player id
     * @param string $type       one of self::validTypes()
     * @param int    $durationH  hours; 0 or less = permanent (until manually lifted)
     * @param string $reason     required, shown to the player and logged
     * @param int    $adminId    acting admin's uid (0 = system/automation)
     * @return bool
     */
    public static function apply($uid, $type, $durationH, $reason, $adminId = 0)
    {
        $uid = (int) $uid;
        $type = (string) $type;
        if ($uid <= 0 || !in_array($type, self::validTypes(), true)) {
            return false;
        }

        $link = self::link();
        if (!$link) {
            return false;
        }
        self::ensureSchema();

        // Replace any existing active restriction of the same type for this user
        // so the admin panel always shows a single, current source of truth.
        self::lift($uid, $type, $adminId, true);

        $now = time();
        $end = $durationH > 0 ? $now + ((int) $durationH * 3600) : 0;
        $reasonEsc = mysqli_real_escape_string($link, trim((string) $reason) !== '' ? trim((string) $reason) : 'No reason given');

        $stmt = $link->prepare(
            "INSERT INTO `" . TB_PREFIX . "punishments` (uid, type, reason, admin, start, end, active) VALUES (?, ?, ?, ?, ?, ?, 1)"
        );
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('issiii', $uid, $type, $reasonEsc, $adminId, $now, $end);
        $ok = $stmt->execute();
        $stmt->close();

        if ($ok) {
            self::logAdmin($adminId, "Applied '$type' punishment to uid=$uid (duration=" . ($durationH > 0 ? $durationH . 'h' : 'permanent') . ", reason='$reasonEsc')");
            if (function_exists('mysqli_query')) {
                // Best-effort in-game notice; ignore failures (e.g. Message class unavailable in cron context).
                if (isset($GLOBALS['database']) && method_exists($GLOBALS['database'], 'sendMessage')) {
                    $label = self::label($type);
                    $until = $end > 0 ? ('until ' . date('Y-m-d H:i', $end) . ' server time') : 'until an admin lifts it';
                    $GLOBALS['database']->sendMessage($uid, 4, 'Account restriction applied', "A $label restriction was applied to your account $until. Reason: $reasonEsc", 0, 0, 0, 0);
                }
            }
        }
        return $ok;
    }

    /** Lift an active restriction of a given type for a user. $silent suppresses the admin log (used internally by apply()). */
    public static function lift($uid, $type, $adminId = 0, $silent = false)
    {
        $uid = (int) $uid;
        $link = self::link();
        if (!$link || $uid <= 0) {
            return false;
        }
        self::ensureSchema();

        $now = time();
        $stmt = $link->prepare(
            "UPDATE `" . TB_PREFIX . "punishments` SET active = 0, lifted_by = ?, lifted_at = ? WHERE uid = ? AND type = ? AND active = 1"
        );
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('iiis', $adminId, $now, $uid, $type);
        $ok = $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();

        if ($ok && $affected > 0 && !$silent) {
            self::logAdmin($adminId, "Lifted '$type' punishment for uid=$uid");
        }
        return $ok;
    }

    /**
     * Is the given restriction currently active for this user?
     * Also lazily expires it if its `end` has passed.
     */
    public static function isActive($uid, $type)
    {
        return self::getActive((int) $uid, (string) $type) !== null;
    }

    /** Returns the active restriction row (array) for uid+type, or null. Auto-expires stale rows. */
    public static function getActive($uid, $type)
    {
        $uid = (int) $uid;
        $link = self::link();
        if (!$link || $uid <= 0) {
            return null;
        }
        self::ensureSchema();

        $stmt = $link->prepare(
            "SELECT * FROM `" . TB_PREFIX . "punishments` WHERE uid = ? AND type = ? AND active = 1 ORDER BY id DESC LIMIT 1"
        );
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('is', $uid, $type);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();

        if (!$row) {
            return null;
        }
        if ((int) $row['end'] > 0 && (int) $row['end'] <= time()) {
            // Expired: deactivate lazily and report as not active.
            self::lift($uid, $type, 0, true);
            return null;
        }
        return $row;
    }

    /** All currently active restrictions for a user, keyed by type. */
    public static function getActiveForUser($uid)
    {
        $out = [];
        foreach (self::validTypes() as $type) {
            $row = self::getActive($uid, $type);
            if ($row) {
                $out[$type] = $row;
            }
        }
        return $out;
    }

    /** For the admin panel: list currently active restrictions, newest first, optionally filtered by type. */
    public static function listActive($type = null, $limit = 200)
    {
        $link = self::link();
        if (!$link) {
            return [];
        }
        self::ensureSchema();
        self::expireOld();

        $limit = (int) $limit;
        if ($type !== null && in_array($type, self::validTypes(), true)) {
            $stmt = $link->prepare(
                "SELECT p.*, u.username FROM `" . TB_PREFIX . "punishments` p
                 LEFT JOIN `" . TB_PREFIX . "users` u ON u.id = p.uid
                 WHERE p.active = 1 AND p.type = ? ORDER BY p.id DESC LIMIT " . $limit
            );
            $stmt->bind_param('s', $type);
            $stmt->execute();
            $res = $stmt->get_result();
        } else {
            $res = mysqli_query($link,
                "SELECT p.*, u.username FROM `" . TB_PREFIX . "punishments` p
                 LEFT JOIN `" . TB_PREFIX . "users` u ON u.id = p.uid
                 WHERE p.active = 1 ORDER BY p.id DESC LIMIT " . $limit
            );
        }

        $rows = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * Cron/automation hook: deactivate everything whose `end` has passed.
     * Safe to call frequently; cheap single UPDATE.
     */
    public static function expireOld()
    {
        $link = self::link();
        if (!$link) {
            return;
        }
        self::ensureSchema();
        $now = time();
        @mysqli_query($link,
            "UPDATE `" . TB_PREFIX . "punishments` SET active = 0, lifted_at = $now
             WHERE active = 1 AND `end` > 0 AND `end` <= $now"
        );
    }

    /** Human-readable label for a type, used in UI/messages. */
    public static function label($type)
    {
        switch ($type) {
            case self::TYPE_MUTE:   return 'chat mute';
            case self::TYPE_MARKET: return 'marketplace';
            case self::TYPE_ARMY:   return 'army freeze';
            default:                return $type;
        }
    }

    private static function logAdmin($adminId, $text)
    {
        $link = self::link();
        if (!$link) {
            return;
        }
        $textEsc = mysqli_real_escape_string($link, $text);
        @mysqli_query($link,
            "INSERT INTO `" . TB_PREFIX . "admin_log` (`id`, `user`, `log`, `time`) VALUES (0, '" . (int) $adminId . "', '$textEsc', " . time() . ")"
        );
    }
}
