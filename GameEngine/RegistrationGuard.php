<?php

class RegistrationGuard
{
    public static function ensureSchema($link)
    {
        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "registration_fingerprints` (ip varchar(45) NOT NULL, ua_hash char(32) NOT NULL, uid int(11) NOT NULL DEFAULT 0, created int(11) NOT NULL, KEY ip_created(ip,created), KEY ua_created(ua_hash,created)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    public static function allowed($link, $ip, $userAgent)
    {
        self::ensureSchema($link);
        $window = time() - (defined('REGISTRATION_LIMIT_WINDOW') ? (int) REGISTRATION_LIMIT_WINDOW : 86400);
        $maxIp = defined('REGISTRATION_MAX_PER_IP') ? (int) REGISTRATION_MAX_PER_IP : 2;
        $maxDevice = defined('REGISTRATION_MAX_PER_DEVICE') ? (int) REGISTRATION_MAX_PER_DEVICE : 3;
        $ip = substr((string) $ip, 0, 45); $uaHash = md5((string) $userAgent);
        $stmt = mysqli_prepare($link, "SELECT COUNT(*) FROM `" . TB_PREFIX . "registration_fingerprints` WHERE ip=? AND created>=?"); mysqli_stmt_bind_param($stmt, 'si', $ip, $window); mysqli_stmt_execute($stmt); mysqli_stmt_bind_result($stmt, $ipCount); mysqli_stmt_fetch($stmt); mysqli_stmt_close($stmt);
        $stmt = mysqli_prepare($link, "SELECT COUNT(*) FROM `" . TB_PREFIX . "registration_fingerprints` WHERE ua_hash=? AND created>=?"); mysqli_stmt_bind_param($stmt, 'si', $uaHash, $window); mysqli_stmt_execute($stmt); mysqli_stmt_bind_result($stmt, $deviceCount); mysqli_stmt_fetch($stmt); mysqli_stmt_close($stmt);
        return ((int) $ipCount < $maxIp && (int) $deviceCount < $maxDevice);
    }

    public static function record($link, $ip, $userAgent, $uid)
    {
        self::ensureSchema($link); $ip = substr((string) $ip, 0, 45); $uaHash = md5((string) $userAgent); $uid = (int) $uid; $now = time();
        $stmt = mysqli_prepare($link, "INSERT INTO `" . TB_PREFIX . "registration_fingerprints` (ip,ua_hash,uid,created) VALUES (?,?,?,?)"); mysqli_stmt_bind_param($stmt, 'ssii', $ip, $uaHash, $uid, $now); mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt);
    }
}