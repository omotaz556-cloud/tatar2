<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       gold_1.php                                                  ##
##  Type           BACKEND                                                     ##
##  Developed by:  aggenkeech                                                  ##
##  Refactored by: Shadow                                                      ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2025; base engine (c) TravianZ authors (GPLv3). ##
##                                                                             ##
#################################################################################

// #299: load CSRF helpers + admin_deny() before the access check below.
require_once(__DIR__ . '/../csrf.php');
if (!isset($_SESSION)) session_start();
if($_SESSION['access'] < 9) admin_deny('You must be signed in as an administrator to view this page. Your session may have expired — please return to the admin panel and sign in again.');

// Issue #139: this Mod is POSTed to directly, so it must verify the CSRF token
// itself (it does not go through admin.php's central csrf_verify()).
require_once(__DIR__ . '/../csrf.php');
csrf_verify();

include_once("../../config.php");
include_once("../../Database.php");
include_once("../../CentralGold.php");

$admid  = (int)($_POST['admid'] ?? 0);
$id     = (int)($_POST['id'] ?? 0);
$amount = (int)($_POST['gold'] ?? 0);
// Production rule (do not change): admin-granted gold is Free Gold. It is
// added to the local `users.gold` column only and must NEVER satisfy
// paid-gold-gated features such as Vacation Mode
// (GameEngine/Profile.php::setvactionmode(), which checks CentralGold's
// paid_gold balance exclusively). This matches the client PDF: "Vacation
// Mode يجب أن يتطلب Purchased Gold فقط، والـFree Gold لا يكفي".
//
// TEST/DEV EXCEPTION ONLY: when ADMIN_GOLD_TEST_MODE is explicitly enabled
// in config.php (default: false — must stay false in production), an admin
// grant is ALSO mirrored into the cross-world CentralGold ledger under the
// distinct reason 'admin_test_grant' (never 'gold_purchase', so it can
// never be mistaken for real revenue in the ledger/audit trail). This lets
// QA activate Vacation Mode during testing without a real payment. The
// $localOnly checkbox still works as an extra opt-out *within* test mode
// (e.g. to test the "free gold only, no purchased gold" rejection path
// without touching config.php).
$localOnly = ((string) ($_POST['local_only'] ?? '0') === '1');

if($id <= 0 || $amount == 0){
    header("Location: ../../../Admin/admin.php?p=usergold");
    exit;
}

// verificare admin
$check = mysqli_query($GLOBALS["link"], "SELECT access, username FROM ".TB_PREFIX."users WHERE id = $admid");
$acc = mysqli_fetch_assoc($check);
if(!$acc || $acc['access'] != 9) admin_deny('You must be signed in as an administrator to view this page. Your session may have expired — please return to the admin panel and sign in again.');

// 1. UPDATE GOLD (local, world-specific Free Gold balance — always happens,
// in both production and test mode, exactly as before).
mysqli_query($GLOBALS["link"], "UPDATE ".TB_PREFIX."users SET gold = gold + $amount WHERE id = $id") or die(mysqli_error($GLOBALS["link"]));

$userRow = mysqli_fetch_assoc(mysqli_query($GLOBALS["link"],
    "SELECT username, email FROM ".TB_PREFIX."users WHERE id = $id"));

// TEST/DEV-only sync — see the ADMIN_GOLD_TEST_MODE comment above. Gated
// behind an explicit config constant that defaults to false, so production
// behaviour is untouched unless a developer deliberately opts a test/dev
// world into it.
$centralGoldSynced = false;
$centralGoldMessage = '';
$testModeActive = defined('ADMIN_GOLD_TEST_MODE') && ADMIN_GOLD_TEST_MODE === true;
if ($testModeActive && !$localOnly) {
    if (!class_exists('CentralGold')) {
        @include_once __DIR__ . '/../../CentralGold.php';
    }
    $userEmail = trim((string) ($userRow['email'] ?? ''));
    if (class_exists('CentralGold') && CentralGold::isConfigured() && $userEmail !== '') {
        $note = '[TEST MODE] Admin gift by ' . $acc['username'];
        if ($amount > 0) {
            $result = CentralGold::credit($userEmail, $userRow['username'] ?? '', $id, $amount, 'admin_test_grant', $note, $admid);
        } else {
            $result = CentralGold::debit($userEmail, $userRow['username'] ?? '', $id, abs($amount), 'admin_test_grant', $note, $admid);
        }
        $centralGoldSynced = (bool) $result[0];
        $centralGoldMessage = (string) $result[1];
    }
}

// 2. ADMIN LOG
$name = $userRow['username'] ?? '';
$name = mysqli_real_escape_string($GLOBALS["link"], $name);
$logSuffix = '';
if ($testModeActive) {
    $logSuffix = $localOnly
        ? ' (TEST MODE, local only)'
        : ($centralGoldSynced ? ' (TEST MODE: synced as admin_test_grant to CentralGold)' : ' (TEST MODE: CentralGold sync skipped — ' . mysqli_real_escape_string($GLOBALS["link"], $centralGoldMessage !== '' ? $centralGoldMessage : 'no verified email / CentralGold not configured') . ')');
}
mysqli_query($GLOBALS["link"], "INSERT INTO ".TB_PREFIX."admin_log VALUES (0, $admid, 'Added <b>$amount</b> gold to user <a href=\'admin.php?p=player&uid=$id\'>$name</a>$logSuffix', ".time().")");

// 3. GOLD_FIN_LOG (pentru a2b2.php)
$vill = mysqli_fetch_assoc(mysqli_query($GLOBALS["link"], "SELECT wref FROM ".TB_PREFIX."vdata WHERE owner = $id LIMIT 1"));
$wid = (int)($vill['wref'] ?? 0);
$action = $amount > 0 ? 'Admin added Gold' : 'Admin removed Gold';
$adminName = $acc['username'];
$details = mysqli_real_escape_string($GLOBALS["link"], 'Admin gift by '.$adminName);
$now = time();

mysqli_query($GLOBALS["link"], "INSERT INTO ".TB_PREFIX."gold_fin_log (wid, uid, action, gold, time, details) VALUES ($wid, $id, '$action', $amount, $now, '$details')") or die(mysqli_error($GLOBALS["link"]));

header("Location: ../../../Admin/admin.php?p=usergold&g");
exit;
?>