<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : CentralGold.php                                          ##
##  Type           : Cross-world synchronized PAID gold ledger                 ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow                                                    ##
##  Project        : Novaterra                                                 ##
##  License        : Novaterra Project                                        ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.             ##
## --------------------------------------------------------------------------- ##
#################################################################################

/**
 * CentralGold
 * -------------------------------------------------------------------------
 * A player's PAID gold is one single balance shared across every Novaterra
 * world, keyed by e-mail (per client brief: "the gold they bought stays with
 * them if they register with the same name + email on any other world").
 *
 * FREE gold (granted for X-Tatar.com activity) is intentionally NOT part of
 * this ledger — it is credited straight into the local `users.gold` of the
 * world the player is on, because it is earned per-world and the client only
 * asked for the PAID balance to be portable. See XTatarGold.php for that
 * piece and central_gold_settings.free_gold_enabled for the admin toggle.
 *
 * Architecture: every world connects to the SAME physical central-gold
 * database (separate database, same DB server — see CENTRAL_GOLD_* in
 * config.php) using its own short-lived mysqli connection. All balance
 * mutations go through a single atomic UPDATE (conditional on account_id,
 * with balance re-read from the affected row) so two worlds crediting or
 * debiting the same player at the same moment cannot corrupt the balance or
 * let it go negative. Every mutation is written to central_gold_ledger for
 * a full audit trail.
 *
 * This class deliberately does NOT touch the local world's `users` table —
 * callers (GoldShop, the Plus/gold-spending pages, etc.) are responsible for
 * calling CentralGold::spend()/credit() alongside whatever local-world
 * effects they already apply. See GameEngine/GoldShop.php for an example of
 * wiring this into an existing local flow.
 */
class CentralGold
{
    /** @var mysqli|null Cached connection for this request. */
    private static $link = null;

    /** True once we've attempted to connect (success or failure) this request. */
    private static $attempted = false;

    /**
     * Identifies "which world is this" for the world_links / ledger tables.
     * Defaults to the DB name of the local world (always unique per world in
     * this deployment model — separate databases, same server) but can be
     * overridden by defining WORLD_KEY in config.php if a clearer label is
     * wanted (e.g. "novaterra_w3").
     */
    public static function worldKey()
    {
        if (defined('WORLD_KEY') && WORLD_KEY !== '') {
            return (string) WORLD_KEY;
        }
        if (defined('SQL_DB')) {
            return (string) SQL_DB;
        }
        return 'unknown_world';
    }

    /**
     * True only if the central-gold feature has been configured for this
     * deployment. Every call site MUST check this (or just call the public
     * methods, which all fail soft to [false, ...] when not configured) so a
     * single-world install that never set CENTRAL_GOLD_* keeps working
     * exactly as before, untouched.
     */
    public static function isConfigured()
    {
        return defined('CENTRAL_GOLD_HOST')
            && defined('CENTRAL_GOLD_USER')
            && defined('CENTRAL_GOLD_PASS')
            && defined('CENTRAL_GOLD_DB')
            && CENTRAL_GOLD_HOST !== '';
    }

    private static function link()
    {
        if (self::$attempted) {
            return self::$link;
        }
        self::$attempted = true;

        if (!self::isConfigured()) {
            return null;
        }

        $port = defined('CENTRAL_GOLD_PORT') ? (int) CENTRAL_GOLD_PORT : 3306;
        $link = @mysqli_connect(CENTRAL_GOLD_HOST, CENTRAL_GOLD_USER, CENTRAL_GOLD_PASS, CENTRAL_GOLD_DB, $port);
        if (!$link) {
            // Fail soft: the central feature becomes unavailable for this
            // request, but the rest of the game (including local gold)
            // keeps working. Callers surface a friendly "try again" message.
            return null;
        }
        mysqli_query($link, "SET NAMES 'utf8mb4'");
        self::$link = $link;
        return self::$link;
    }

    private static function normEmail($email)
    {
        return strtolower(trim((string) $email));
    }

    /* ---- Account resolution --------------------------------------------- */

    /**
     * Find or create the central account for a given email, and record/refresh
     * this world's link to it. Call this on login/registration for any world
     * user whose local `users.email` is set.
     *
     * @return int|null central account id, or null if central gold is
     *                   unavailable / email invalid.
     */
    public static function resolveAccount($email, $username, $worldUserId)
    {
        $link = self::link();
        if (!$link) {
            return null;
        }
        $email = self::normEmail($email);
        if ($email === '' || strpos($email, '@') === false) {
            return null;
        }
        $username = substr((string) $username, 0, 100);
        $now = time();

        // Find existing.
        $stmt = mysqli_prepare($link, "SELECT id FROM central_gold_accounts WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        if ($row) {
            $accountId = (int) $row['id'];
            // Keep username fresh (players may have registered the email
            // under a different name previously on another world).
            $u = mysqli_prepare($link, "UPDATE central_gold_accounts SET username = ?, updated = ? WHERE id = ?");
            mysqli_stmt_bind_param($u, 'sii', $username, $now, $accountId);
            mysqli_stmt_execute($u);
            mysqli_stmt_close($u);
        } else {
            $ins = mysqli_prepare($link,
                "INSERT INTO central_gold_accounts (email, username, paid_gold, created, updated) VALUES (?,?,0,?,?)");
            mysqli_stmt_bind_param($ins, 'ssii', $email, $username, $now, $now);
            if (!mysqli_stmt_execute($ins)) {
                mysqli_stmt_close($ins);
                // Lost a race with another world creating the same account
                // concurrently — re-select rather than fail.
                $stmt2 = mysqli_prepare($link, "SELECT id FROM central_gold_accounts WHERE email = ? LIMIT 1");
                mysqli_stmt_bind_param($stmt2, 's', $email);
                mysqli_stmt_execute($stmt2);
                $res2 = mysqli_stmt_get_result($stmt2);
                $row2 = $res2 ? mysqli_fetch_assoc($res2) : null;
                mysqli_stmt_close($stmt2);
                if (!$row2) {
                    return null;
                }
                $accountId = (int) $row2['id'];
            } else {
                $accountId = (int) mysqli_insert_id($link);
                mysqli_stmt_close($ins);
            }
        }

        self::linkWorld($accountId, $worldUserId);
        return $accountId;
    }

    private static function linkWorld($accountId, $worldUserId)
    {
        $link = self::link();
        if (!$link) {
            return;
        }
        $worldKey = self::worldKey();
        $worldUserId = (int) $worldUserId;
        $now = time();

        $stmt = mysqli_prepare($link,
            "INSERT INTO central_gold_world_links (account_id, world_key, world_user_id, first_seen, last_seen)
             VALUES (?,?,?,?,?)
             ON DUPLICATE KEY UPDATE world_user_id = VALUES(world_user_id), last_seen = VALUES(last_seen)");
        mysqli_stmt_bind_param($stmt, 'isiii', $accountId, $worldKey, $worldUserId, $now, $now);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    /* ---- Balance read ----------------------------------------------------- */

    /** Current paid-gold balance for an email, or null if unavailable. */
    public static function balance($email)
    {
        $link = self::link();
        if (!$link) {
            return null;
        }
        $email = self::normEmail($email);
        $stmt = mysqli_prepare($link, "SELECT paid_gold FROM central_gold_accounts WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
        return $row ? (int) $row['paid_gold'] : 0;
    }

    /* ---- Balance mutation (atomic) ---------------------------------------- */

    /**
     * Credit paid gold (purchase, admin grant, incoming transfer).
     * @return array [ok(bool), message(string), newBalance(int)]
     */
    public static function credit($email, $username, $worldUserId, $amount, $reason, $note = '', $adminId = 0)
    {
        return self::applyDelta($email, $username, $worldUserId, abs((int) $amount), $reason, $note, $adminId);
    }

    /**
     * Debit paid gold (spend in-game, admin deduction, outgoing transfer).
     * Fails cleanly (does not go negative) if balance is insufficient.
     * @return array [ok(bool), message(string), newBalance(int)]
     */
    public static function debit($email, $username, $worldUserId, $amount, $reason, $note = '', $adminId = 0)
    {
        return self::applyDelta($email, $username, $worldUserId, -abs((int) $amount), $reason, $note, $adminId);
    }

    private static function applyDelta($email, $username, $worldUserId, $delta, $reason, $note, $adminId)
    {
        $link = self::link();
        if (!$link) {
            return [false, 'Central gold service is unavailable, try again later.', 0];
        }

        $accountId = self::resolveAccount($email, $username, $worldUserId);
        if (!$accountId) {
            return [false, 'A valid email is required for gold to be portable across worlds.', 0];
        }

        $delta = (int) $delta;
        if ($delta === 0) {
            return [false, 'Nothing to apply.', self::balanceById($accountId)];
        }

        mysqli_begin_transaction($link);
        try {
            // Atomic, race-safe update: the WHERE clause itself guards
            // against a debit taking the balance negative under concurrency
            // (two worlds spending the same player's gold at once).
            if ($delta > 0) {
                $stmt = mysqli_prepare($link,
                    "UPDATE central_gold_accounts SET paid_gold = paid_gold + ?, updated = ? WHERE id = ?");
                $now = time();
                mysqli_stmt_bind_param($stmt, 'iii', $delta, $now, $accountId);
            } else {
                $abs = abs($delta);
                $stmt = mysqli_prepare($link,
                    "UPDATE central_gold_accounts SET paid_gold = paid_gold - ?, updated = ?
                     WHERE id = ? AND paid_gold >= ?");
                $now = time();
                mysqli_stmt_bind_param($stmt, 'iiii', $abs, $now, $accountId, $abs);
            }
            mysqli_stmt_execute($stmt);
            $affected = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);

            if ($affected !== 1) {
                mysqli_rollback($link);
                return [false, 'Insufficient gold balance.', self::balanceById($accountId)];
            }

            $newBalance = self::balanceById($accountId);

            $worldKey = self::worldKey();
            $note = substr((string) $note, 0, 255);
            $adminId = (int) $adminId;
            $now2 = time();
            $ins = mysqli_prepare($link,
                "INSERT INTO central_gold_ledger
                 (account_id, world_key, delta, balance_after, reason, note, admin_id, time)
                 VALUES (?,?,?,?,?,?,?,?)");
            mysqli_stmt_bind_param($ins, 'isiissii',
                $accountId, $worldKey, $delta, $newBalance, $reason, $note, $adminId, $now2);
            mysqli_stmt_execute($ins);
            mysqli_stmt_close($ins);

            mysqli_commit($link);
            return [true, 'OK', $newBalance];
        } catch (\Throwable $e) {
            mysqli_rollback($link);
            return [false, 'Central gold update failed, try again later.', self::balanceById($accountId)];
        }
    }

    private static function balanceById($accountId)
    {
        $link = self::link();
        if (!$link) {
            return 0;
        }
        $stmt = mysqli_prepare($link, "SELECT paid_gold FROM central_gold_accounts WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'i', $accountId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
        return $row ? (int) $row['paid_gold'] : 0;
    }

    /* ---- Player-to-player transfer ---------------------------------------- */

    /**
     * Transfer paid gold from one player (by email) to another. Only paid
     * gold is ever transferable, per client brief. Implemented as a debit +
     * credit inside one method so a failed credit leg cannot happen after a
     * successful debit leg silently destroys gold.
     *
     * @return array [ok(bool), message(string)]
     */
    public static function transfer($fromEmail, $fromUsername, $fromWorldUserId,
                                     $toEmail, $toUsername, $toWorldUserId,
                                     $amount, $adminId = 0, $note = '')
    {
        $amount = (int) $amount;
        if ($amount <= 0) {
            return [false, 'Transfer amount must be positive.'];
        }
        $fromEmail = self::normEmail($fromEmail);
        $toEmail = self::normEmail($toEmail);
        if ($fromEmail === $toEmail) {
            return [false, 'Cannot transfer gold to the same account.'];
        }

        $fromAccountId = self::resolveAccount($fromEmail, $fromUsername, $fromWorldUserId);
        $toAccountId = self::resolveAccount($toEmail, $toUsername, $toWorldUserId);
        if (!$fromAccountId || !$toAccountId) {
            return [false, 'Both accounts must have a valid, registered email.'];
        }

        list($okDebit, $msgDebit) = self::debit(
            $fromEmail, $fromUsername, $fromWorldUserId, $amount,
            'transfer_out', $note !== '' ? $note : ('to ' . $toEmail), $adminId
        );
        if (!$okDebit) {
            return [false, $msgDebit];
        }

        list($okCredit, $msgCredit) = self::credit(
            $toEmail, $toUsername, $toWorldUserId, $amount,
            'transfer_in', $note !== '' ? $note : ('from ' . $fromEmail), $adminId
        );
        if (!$okCredit) {
            // Credit leg failed after a successful debit — refund immediately
            // so gold is never lost. This is the one case where we accept a
            // second ledger entry (refund) rather than leaving the player short.
            self::credit($fromEmail, $fromUsername, $fromWorldUserId, $amount,
                'transfer_refund', 'refund: ' . $msgCredit, $adminId);
            return [false, 'Transfer failed and was refunded: ' . $msgCredit];
        }

        return [true, 'Transferred ' . $amount . ' gold.'];
    }

    /* ---- Admin: settings & visibility -------------------------------------- */

    public static function isFreeGoldEnabled()
    {
        $link = self::link();
        if (!$link) {
            // If central gold isn't configured at all, free gold is a
            // separate local concern (XTatarGold.php) and defaults to on.
            return true;
        }
        $res = @mysqli_query($link, "SELECT free_gold_enabled FROM central_gold_settings WHERE id = 1 LIMIT 1");
        $row = $res ? mysqli_fetch_assoc($res) : null;
        return $row ? ((int) $row['free_gold_enabled'] === 1) : true;
    }

    public static function setFreeGoldEnabled($enabled)
    {
        $link = self::link();
        if (!$link) {
            return false;
        }
        $enabled = $enabled ? 1 : 0;
        $now = time();
        $stmt = mysqli_prepare($link,
            "UPDATE central_gold_settings SET free_gold_enabled = ?, updated = ? WHERE id = 1");
        mysqli_stmt_bind_param($stmt, 'iii', $enabled, $now);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    /** Recent ledger entries for the admin view (all worlds). */
    public static function recentLedger($limit = 50)
    {
        $link = self::link();
        $out = [];
        if (!$link) {
            return $out;
        }
        $limit = max(1, min(300, (int) $limit));
        $r = @mysqli_query($link,
            "SELECT l.*, a.email, a.username
             FROM central_gold_ledger l
             JOIN central_gold_accounts a ON a.id = l.account_id
             ORDER BY l.id DESC LIMIT " . $limit);
        if ($r) {
            while ($row = mysqli_fetch_assoc($r)) {
                $out[] = $row;
            }
            mysqli_free_result($r);
        }
        return $out;
    }

    /** Which worlds a given account has been seen on (for admin lookups). */
    public static function worldsForAccount($accountId)
    {
        $link = self::link();
        $out = [];
        if (!$link) {
            return $out;
        }
        $accountId = (int) $accountId;
        $r = mysqli_query($link,
            "SELECT world_key, world_user_id, first_seen, last_seen
             FROM central_gold_world_links WHERE account_id = " . $accountId . " ORDER BY last_seen DESC");
        if ($r) {
            while ($row = mysqli_fetch_assoc($r)) {
                $out[] = $row;
            }
            mysqli_free_result($r);
        }
        return $out;
    }

    /** Lookup an account by email for the admin transfer/search UI. */
    public static function findByEmail($email)
    {
        $link = self::link();
        if (!$link) {
            return null;
        }
        $email = self::normEmail($email);
        $stmt = mysqli_prepare($link, "SELECT * FROM central_gold_accounts WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
        return $row ?: null;
    }

    /**
     * Search central accounts by username (partial, case-insensitive) for the
     * admin "search as you type" picker. Central accounts are keyed by email,
     * but an admin on any single world only ever knows a player's in-game
     * name, not necessarily the email they registered with (which may even
     * belong to a different world than the one the admin is currently in) —
     * so this is the lookup that actually matches what the admin has on hand.
     *
     * @return array<int, array{id:int, email:string, username:string, paid_gold:int}>
     */
    public static function searchByUsername($query, $limit = 10)
    {
        $link = self::link();
        if (!$link) {
            return [];
        }
        $query = trim((string) $query);
        if ($query === '') {
            return [];
        }
        $limit = max(1, min(25, (int) $limit));

        // Escape SQL LIKE wildcards the user might type literally, then wrap
        // for a "contains" match.
        $like = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $query);
        $like = '%' . $like . '%';

        $stmt = mysqli_prepare($link,
            "SELECT id, email, username, paid_gold FROM central_gold_accounts
             WHERE username LIKE ? ORDER BY username ASC LIMIT ?");
        mysqli_stmt_bind_param($stmt, 'si', $like, $limit);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $out = [];
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $out[] = [
                    'id'        => (int) $row['id'],
                    'email'     => (string) $row['email'],
                    'username'  => (string) $row['username'],
                    'paid_gold' => (int) $row['paid_gold'],
                ];
            }
        }
        mysqli_stmt_close($stmt);
        return $out;
    }
}
