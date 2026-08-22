<?php

class ActivityRewards
{
    private static function link()
    {
        return isset($GLOBALS['link']) && $GLOBALS['link'] ? $GLOBALS['link'] : null;
    }

    public static function ensureSchema()
    {
        $link = self::link();
        if (!$link) return;
        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "activity_rewards` (uid int(11) NOT NULL, last_claim int(11) NOT NULL DEFAULT 0, total_claims int(11) NOT NULL DEFAULT 0, PRIMARY KEY(uid)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    public static function state($uid)
    {
        $link = self::link(); self::ensureSchema();
        $uid = (int) $uid; $row = ['last_claim' => 0, 'total_claims' => 0];
        if ($link) { $res = mysqli_query($link, "SELECT last_claim,total_claims FROM `" . TB_PREFIX . "activity_rewards` WHERE uid=" . $uid . " LIMIT 1"); $found = $res ? mysqli_fetch_assoc($res) : null; if ($found) $row = $found; }
        $next = (int) $row['last_claim'] + 21600;
        return ['available' => time() >= $next, 'next_claim' => max(time(), $next), 'total_claims' => (int) $row['total_claims']];
    }

    public static function claim($uid)
    {
        $link = self::link(); self::ensureSchema(); $uid = (int) $uid;
        if (!$link || $uid <= 3) return [false, 'Reward unavailable.'];
        $state = self::state($uid); if (!$state['available']) return [false, 'The next activity reward is not ready yet.'];
        $now = time();
        $stmt = mysqli_prepare($link, "INSERT INTO `" . TB_PREFIX . "activity_rewards` (uid,last_claim,total_claims) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE last_claim=VALUES(last_claim), total_claims=total_claims+1");
        mysqli_stmt_bind_param($stmt, 'ii', $uid, $now); $ok = mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt);
        if (!$ok) return [false, 'Reward could not be claimed.'];
        if (isset($GLOBALS['database']) && method_exists($GLOBALS['database'], 'modifyGold')) $GLOBALS['database']->modifyGold($uid, 1, 1);
        return [true, 'Activity reward claimed.'];
    }
}