<?php

#################################################################################
##  Tracks last-read chat message per user/scope for sidebar unread badges.   ##
#################################################################################

class ChatRead
{
    public const PUBLIC_SCOPE = '__public__';

    public static function ensureSchema(): void
    {
        $link = self::link();
        if (!$link) {
            return;
        }

        @mysqli_query($link, 'CREATE TABLE IF NOT EXISTS `' . TB_PREFIX . 'chat_read` (
            `uid` int(11) NOT NULL,
            `scope` varchar(64) NOT NULL,
            `last_id` int(11) NOT NULL DEFAULT 0,
            `updated` int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`uid`, `scope`),
            KEY `scope_last` (`scope`, `last_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
    }

    /** Mark every message in this scope as read for the player. */
    public static function markRead(int $uid, string $scope): void
    {
        $link = self::link();
        if (!$link || $uid <= 0) {
            return;
        }

        self::ensureSchema();

        $uid = (int) $uid;
        $scopeEsc = self::escapeScope($scope);
        $maxId = self::getMaxMessageId($scope);
        $now = time();

        @mysqli_query(
            $link,
            'INSERT INTO `' . TB_PREFIX . 'chat_read` (`uid`, `scope`, `last_id`, `updated`)
             VALUES (' . $uid . ", '" . $scopeEsc . "', " . $maxId . ', ' . $now . ')
             ON DUPLICATE KEY UPDATE
                `last_id` = GREATEST(`last_id`, ' . $maxId . '),
                `updated` = ' . $now
        );
    }

    /** Unread messages from other players in one chat scope. */
    public static function getUnreadCount(int $uid, string $scope): int
    {
        $link = self::link();
        if (!$link || $uid <= 0) {
            return 0;
        }

        self::ensureSchema();

        $lastId = self::resolveLastReadId($uid, $scope);

        $uid = (int) $uid;
        $scopeEsc = self::escapeScope($scope);
        $lastId = (int) $lastId;

        $res = @mysqli_query(
            $link,
            'SELECT COUNT(*) AS cnt FROM `' . TB_PREFIX . 'chat`
             WHERE `alli` = \'' . $scopeEsc . '\'
               AND `id` > ' . $lastId . '
               AND `id_user` <> ' . $uid
        );

        if (!$res) {
            return 0;
        }

        $row = mysqli_fetch_assoc($res);
        mysqli_free_result($res);

        return (int) ($row['cnt'] ?? 0);
    }

    /** Sidebar badge: public chat only (linked from menu.tpl). */
    public static function getSidebarUnreadCount(int $uid): int
    {
        return self::getUnreadCount($uid, self::PUBLIC_SCOPE);
    }

    /** Stored pointer, or a one-time baseline row (does not skip new messages). */
    private static function resolveLastReadId(int $uid, string $scope): int
    {
        $stored = self::getLastReadId($uid, $scope);
        if ($stored !== null) {
            return $stored;
        }

        $baseline = self::getMaxMessageId($scope);
        self::insertBaseline($uid, $scope, $baseline);

        return $baseline;
    }

    private static function getLastReadId(int $uid, string $scope): ?int
    {
        $link = self::link();
        if (!$link) {
            return null;
        }

        $uid = (int) $uid;
        $scopeEsc = self::escapeScope($scope);

        $res = @mysqli_query(
            $link,
            'SELECT `last_id` FROM `' . TB_PREFIX . 'chat_read`
             WHERE `uid` = ' . $uid . " AND `scope` = '" . $scopeEsc . "' LIMIT 1"
        );

        if (!$res) {
            return null;
        }

        $row = mysqli_fetch_assoc($res);
        mysqli_free_result($res);

        return $row ? (int) $row['last_id'] : null;
    }

    private static function insertBaseline(int $uid, string $scope, int $lastId): void
    {
        $link = self::link();
        if (!$link || $uid <= 0) {
            return;
        }

        self::ensureSchema();

        $uid = (int) $uid;
        $scopeEsc = self::escapeScope($scope);
        $lastId = (int) $lastId;
        $now = time();

        @mysqli_query(
            $link,
            'INSERT IGNORE INTO `' . TB_PREFIX . 'chat_read`
             (`uid`, `scope`, `last_id`, `updated`)
             VALUES (' . $uid . ", '" . $scopeEsc . "', " . $lastId . ', ' . $now . ')'
        );
    }

    private static function getMaxMessageId(string $scope): int
    {
        $link = self::link();
        if (!$link) {
            return 0;
        }

        $scopeEsc = self::escapeScope($scope);
        $res = @mysqli_query(
            $link,
            'SELECT MAX(`id`) AS max_id FROM `' . TB_PREFIX . 'chat`
             WHERE `alli` = \'' . $scopeEsc . '\''
        );

        if (!$res) {
            return 0;
        }

        $row = mysqli_fetch_assoc($res);
        mysqli_free_result($res);

        return (int) ($row['max_id'] ?? 0);
    }

    private static function escapeScope(string $scope): string
    {
        $link = self::link();
        $scope = substr($scope, 0, 64);

        return $link ? mysqli_real_escape_string($link, $scope) : addslashes($scope);
    }

    private static function link()
    {
        global $database;

        if (isset($database) && is_object($database) && !empty($database->dblink)) {
            return $database->dblink;
        }

        return $GLOBALS['link'] ?? null;
    }
}
