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
        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "activity_rewards` (uid int(11) NOT NULL, last_claim int(11) NOT NULL DEFAULT 0, total_claims int(11) NOT NULL DEFAULT 0, streak int(11) NOT NULL DEFAULT 0, PRIMARY KEY(uid)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        try {
            @mysqli_query($link, "ALTER TABLE `" . TB_PREFIX . "activity_rewards` ADD COLUMN streak int(11) NOT NULL DEFAULT 0");
        } catch (Throwable $e) {
            // The column already exists on current installations. PHP 8 mysqli
            // may throw for this harmless idempotency check.
        }
    }

    public static function interval()
    {
        $speed = defined('SPEED') ? (float) SPEED : 1.0;
        return max(3600, (int) round(86400 / max(1.0, $speed)));
    }

    public static function state($uid)
    {
        $link = self::link(); self::ensureSchema();
        $uid = (int) $uid; $row = ['last_claim' => 0, 'total_claims' => 0, 'streak' => 0];
        if ($link) { $res = mysqli_query($link, "SELECT last_claim,total_claims,streak FROM `" . TB_PREFIX . "activity_rewards` WHERE uid=" . $uid . " LIMIT 1"); $found = $res ? mysqli_fetch_assoc($res) : null; if ($found) $row = $found; }
        $next = (int) $row['last_claim'] + self::interval();
        return ['available' => time() >= $next, 'next_claim' => max(time(), $next), 'total_claims' => (int) $row['total_claims'], 'streak' => (int) $row['streak'], 'reward' => min(5, max(1, (int) $row['streak'] + 1))];
    }

    public static function claim($uid)
    {
        $link = self::link(); self::ensureSchema(); $uid = (int) $uid;
        if (!$link || $uid <= 3) return [false, 'Reward unavailable.'];
        $now = time();
        mysqli_begin_transaction($link);
        $seed = mysqli_prepare($link, "INSERT IGNORE INTO `" . TB_PREFIX . "activity_rewards` (uid,last_claim,total_claims,streak) VALUES (?,0,0,0)");
        if (!$seed) { mysqli_rollback($link); return [false, 'Reward unavailable.']; }
        mysqli_stmt_bind_param($seed, 'i', $uid);
        if (!mysqli_stmt_execute($seed)) { mysqli_stmt_close($seed); mysqli_rollback($link); return [false, 'Reward unavailable.']; }
        mysqli_stmt_close($seed);
        $stmt = mysqli_prepare($link, "SELECT last_claim,total_claims,streak FROM `" . TB_PREFIX . "activity_rewards` WHERE uid=? FOR UPDATE");
        if (!$stmt) { mysqli_rollback($link); return [false, 'Reward unavailable.']; }
        mysqli_stmt_bind_param($stmt, 'i', $uid);
        $row = null;
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $lastClaimValue, $totalClaimsValue, $streakValue);
            if (mysqli_stmt_fetch($stmt)) {
                $row = ['last_claim' => $lastClaimValue, 'total_claims' => $totalClaimsValue, 'streak' => $streakValue];
            }
        }
        mysqli_stmt_close($stmt);
        if (!$row) { mysqli_rollback($link); return [false, 'Reward unavailable.']; }
        $lastClaim = $row ? (int) $row['last_claim'] : 0;
        $oldStreak = $row ? (int) $row['streak'] : 0;
        if ($lastClaim > 0 && $now < $lastClaim + self::interval()) { mysqli_rollback($link); return [false, 'The next activity reward is not ready yet.']; }
        $streak = ($lastClaim > 0 && $now <= $lastClaim + (self::interval() * 2)) ? $oldStreak + 1 : 1;
        $reward = min(5, $streak);
        $stmt = mysqli_prepare($link, "INSERT INTO `" . TB_PREFIX . "activity_rewards` (uid,last_claim,total_claims,streak) VALUES (?, ?, 1, ?) ON DUPLICATE KEY UPDATE last_claim=VALUES(last_claim), total_claims=total_claims+1, streak=VALUES(streak)");
        mysqli_stmt_bind_param($stmt, 'iii', $uid, $now, $streak); $ok = mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt);
        if ($ok && isset($GLOBALS['database']) && method_exists($GLOBALS['database'], 'modifyGold')) $ok = (bool) $GLOBALS['database']->modifyGold($uid, $reward, 1);
        if (!$ok) { mysqli_rollback($link); return [false, 'Reward could not be claimed.']; }
        mysqli_commit($link);
        return [true, 'Activity reward claimed: ' . $reward . ' gold.'];
    }
}