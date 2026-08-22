<?php

/** Paid gold packages, purchase ledger, and refund requests. */
class PaymentShop
{
    private static function link()
    {
        return isset($GLOBALS['link']) && $GLOBALS['link'] ? $GLOBALS['link'] : null;
    }

    public static function packages()
    {
        return [
            'A' => ['gold' => defined('PLUS_PACKAGE_A_GOLD') ? (int) PLUS_PACKAGE_A_GOLD : 60, 'amount' => 1.99],
            'B' => ['gold' => defined('PLUS_PACKAGE_B_GOLD') ? (int) PLUS_PACKAGE_B_GOLD : 120, 'amount' => 4.99],
            'C' => ['gold' => defined('PLUS_PACKAGE_C_GOLD') ? (int) PLUS_PACKAGE_C_GOLD : 360, 'amount' => 9.99],
            'D' => ['gold' => defined('PLUS_PACKAGE_D_GOLD') ? (int) PLUS_PACKAGE_D_GOLD : 1000, 'amount' => 19.99],
            'E' => ['gold' => defined('PLUS_PACKAGE_E_GOLD') ? (int) PLUS_PACKAGE_E_GOLD : 2000, 'amount' => 49.99],
        ];
    }

    public static function ensureSchema()
    {
        $link = self::link();
        if (!$link) return;
        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "gold_purchases` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uid` int(11) NOT NULL,
            `email` varchar(190) NOT NULL,
            `package_key` char(1) NOT NULL,
            `gold` int(11) NOT NULL,
            `amount` decimal(10,2) NOT NULL,
            `currency` varchar(8) NOT NULL DEFAULT 'EUR',
            `payment_id` varchar(120) NOT NULL,
            `status` enum('pending','paid','refund_requested','refunded','failed') NOT NULL DEFAULT 'pending',
            `created` int(11) NOT NULL,
            `paid_at` int(11) NOT NULL DEFAULT 0,
            `refund_reason` varchar(255) NOT NULL DEFAULT '',
            PRIMARY KEY (`id`), UNIQUE KEY `payment_id` (`payment_id`), KEY `uid_status` (`uid`,`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "gold_purchase_blocks` (
            `uid` int(11) NOT NULL, `reason` varchar(255) NOT NULL DEFAULT '', `created` int(11) NOT NULL,
            PRIMARY KEY (`uid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    public static function create($uid, $email, $packageKey, $paymentId)
    {
        $link = self::link();
        $key = strtoupper(trim((string) $packageKey));
        $packages = self::packages();
        if (!$link || !isset($packages[$key]) || trim((string) $email) === '' || trim((string) $paymentId) === '') return false;
        self::ensureSchema();
        $uid = (int) $uid;
        $blocked = mysqli_query($link, "SELECT uid FROM `" . TB_PREFIX . "gold_purchase_blocks` WHERE uid=" . $uid . " LIMIT 1");
        if ($blocked && mysqli_num_rows($blocked) > 0) return false;
        $p = $packages[$key];
        $stmt = mysqli_prepare($link, "INSERT INTO `" . TB_PREFIX . "gold_purchases` (uid,email,package_key,gold,amount,currency,payment_id,created) VALUES (?,?,?,?,?,?,?,?)");
        if (!$stmt) return false;
        $email = trim((string) $email); $paymentId = substr(trim((string) $paymentId), 0, 120); $currency = defined('PAYPAL_CURRENCY') ? PAYPAL_CURRENCY : 'EUR'; $now = time();
        mysqli_stmt_bind_param($stmt, 'issidssi', $uid, $email, $key, $p['gold'], $p['amount'], $currency, $paymentId, $now);
        $ok = mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt);
        return $ok;
    }

    public static function confirm($paymentId, $status)
    {
        $link = self::link(); self::ensureSchema();
        $status = strtolower((string) $status);
        if (!$link || !in_array($status, ['paid', 'success', 'completed', 'refunded', 'refund'], true)) return false;
        $paymentId = substr(trim((string) $paymentId), 0, 120);
        mysqli_begin_transaction($link);
        $stmt = mysqli_prepare($link, "SELECT * FROM `" . TB_PREFIX . "gold_purchases` WHERE payment_id = ? FOR UPDATE");
        mysqli_stmt_bind_param($stmt, 's', $paymentId); mysqli_stmt_execute($stmt); $res = mysqli_stmt_get_result($stmt); $row = $res ? mysqli_fetch_assoc($res) : null; mysqli_stmt_close($stmt);
        if (!$row || $row['status'] === 'refunded' || ($status !== 'refunded' && $status !== 'refund' && in_array($row['status'], ['paid', 'refund_requested'], true))) { mysqli_commit($link); return (bool) $row; }
        if ($status === 'refunded' || $status === 'refund') {
            if ($row['status'] !== 'paid' && $row['status'] !== 'refund_requested') { mysqli_commit($link); return false; }
            $username = '';
            $userStmt = mysqli_prepare($link, "SELECT username FROM `" . TB_PREFIX . "users` WHERE id = ? LIMIT 1");
            if ($userStmt) {
                $uid = (int) $row['uid'];
                mysqli_stmt_bind_param($userStmt, 'i', $uid);
                mysqli_stmt_execute($userStmt);
                $userRes = mysqli_stmt_get_result($userStmt);
                $userRow = $userRes ? mysqli_fetch_assoc($userRes) : null;
                $username = $userRow ? (string) $userRow['username'] : '';
                mysqli_stmt_close($userStmt);
            }
            $debit = CentralGold::debit($row['email'], $username, (int) $row['uid'], (int) $row['gold'], 'gold_refund', 'MyFatoorah refund ' . $paymentId);
            if (!$debit[0]) { mysqli_rollback($link); return false; }
            $uid = (int) $row['uid']; $now = time();
            mysqli_query($link, "INSERT INTO `" . TB_PREFIX . "gold_purchase_blocks` (uid,reason,created) VALUES (" . $uid . ",'Payment reversed'," . $now . ") ON DUPLICATE KEY UPDATE reason='Payment reversed'");
                $stmt = mysqli_prepare($link, "UPDATE `" . TB_PREFIX . "gold_purchases` SET status='refunded' WHERE id=?"); mysqli_stmt_bind_param($stmt, 'i', $row['id']); $ok = mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt); mysqli_commit($link); if ($ok) self::notify($uid, 'Payment reversed', 'Your payment was reversed. The purchased gold was removed and future purchases are disabled.'); return $ok;
        }
        $username = '';
        $local = self::link();
        $userStmt = mysqli_prepare($local, "SELECT username FROM `" . TB_PREFIX . "users` WHERE id = ? LIMIT 1");
        if ($userStmt) {
            $uid = (int) $row['uid'];
            mysqli_stmt_bind_param($userStmt, 'i', $uid);
            mysqli_stmt_execute($userStmt);
            $userRes = mysqli_stmt_get_result($userStmt);
            $userRow = $userRes ? mysqli_fetch_assoc($userRes) : null;
            $username = $userRow ? (string) $userRow['username'] : '';
            mysqli_stmt_close($userStmt);
        }
        $credited = CentralGold::credit($row['email'], $username, (int) $row['uid'], (int) $row['gold'], 'gold_purchase', 'MyFatoorah payment ' . $paymentId);
        if (!$credited[0]) { mysqli_rollback($link); return false; }
        $now = time(); $stmt = mysqli_prepare($link, "UPDATE `" . TB_PREFIX . "gold_purchases` SET status='paid', paid_at=? WHERE id=? AND status='pending'");
        mysqli_stmt_bind_param($stmt, 'ii', $now, $row['id']); $ok = mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt); mysqli_commit($link);
        if ($ok) self::notify((int) $row['uid'], 'Gold purchase completed', 'Your MyFatoorah payment was confirmed and purchased gold was added.');
        return $ok;
    }

    public static function requestRefund($uid, $purchaseId, $reason)
    {
        $link = self::link(); self::ensureSchema();
        if (!$link) return false;
        $reason = substr(trim((string) $reason), 0, 255); $uid = (int) $uid; $purchaseId = (int) $purchaseId;
        $stmt = mysqli_prepare($link, "UPDATE `" . TB_PREFIX . "gold_purchases` SET status='refund_requested', refund_reason=? WHERE id=? AND uid=? AND status='paid'");
        mysqli_stmt_bind_param($stmt, 'sii', $reason, $purchaseId, $uid); $ok = mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt); return $ok;
    }

    public static function history($uid, $limit = 30)
    {
        $link = self::link(); self::ensureSchema(); $out = [];
        if (!$link) return $out;
        $uid = (int) $uid; $limit = max(1, min(100, (int) $limit));
        $res = mysqli_query($link, "SELECT * FROM `" . TB_PREFIX . "gold_purchases` WHERE uid=" . $uid . " ORDER BY id DESC LIMIT " . $limit);
        while ($res && ($row = mysqli_fetch_assoc($res))) $out[] = $row;
        return $out;
    }

    public static function notify($uid, $subject, $message)
    {
        if (isset($GLOBALS['database']) && method_exists($GLOBALS['database'], 'sendMessage')) {
            $GLOBALS['database']->sendMessage((int) $uid, 4, (string) $subject, (string) $message, 0, 0, 0, 0);
        }
    }
}