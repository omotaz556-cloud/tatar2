<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : xtatarGoldAdmin.php                                      ##
##  Type           : BACKEND (X-Tatar activity gold admin actions)            ##
##  Developed by   : Shadow                                                    ##
##  License        : Novaterra Project                                        ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.            ##
## --------------------------------------------------------------------------- ##
##  Handles the admin actions for X-Tatar free gold:                          ##
##    - update settings (on/off, points_per_gold, daily_login_points,         ##
##      daily_cap_points, webhook_secret)                                     ##
##    - manually adjust a player's activity points (+/-)                     ##
##  Both simply call the existing XTatarGold class; no new game logic.        ##
#################################################################################

require_once(__DIR__ . '/../csrf.php');
if (!isset($_SESSION)) session_start();
if (($_SESSION['access'] ?? 0) < 9) {
    admin_deny('You must be signed in as an administrator to do this. '
        . 'Your session may have expired — please return to the admin panel and sign in again.');
}

csrf_verify();

$autoprefix = '';
for ($i = 0; $i < 5; $i++) {
    $autoprefix = str_repeat('../', $i);
    if (file_exists($autoprefix . 'autoloader.php')) break;
}
include_once($autoprefix . "GameEngine/config.php");
include_once($autoprefix . "GameEngine/Database.php");
include_once($autoprefix . "GameEngine/XTatarGold.php");

$admid = (int) ($_SESSION['id'] ?? 0);

// Re-confirm the session's access level against the DB, same pattern used by
// the other admin Mods (centralGoldAdmin.php etc.) — a stale session cookie
// should not be trusted on its own for a privileged action.
$check = mysqli_query($GLOBALS['link'],
    "SELECT access FROM " . TB_PREFIX . "users WHERE id = " . $admid);
$acc = $check ? mysqli_fetch_assoc($check) : null;
if (!$acc || (int) $acc['access'] < 9) {
    admin_deny('Your session may have expired — please sign in again.');
}

$do  = $_POST['do'] ?? '';
$msg = '';
$lookupUser = '';

if ($do === 'update_settings') {
    $enabled = ((string) ($_POST['enabled'] ?? '0') === '1');
    $pointsPerGold = (int) ($_POST['points_per_gold'] ?? 100);
    $dailyLoginPoints = (int) ($_POST['daily_login_points'] ?? 5);
    $dailyCapPoints = (int) ($_POST['daily_cap_points'] ?? 0);
    $webhookSecret = trim((string) ($_POST['webhook_secret'] ?? ''));

    if ($pointsPerGold < 1) {
        $msg = 'Points per gold must be at least 1.';
    } else {
        $ok = XTatarGold::updateSettings(
            $enabled, $pointsPerGold, $dailyLoginPoints, $dailyCapPoints, $webhookSecret
        );
        $msg = $ok ? 'Settings saved.' : 'Could not save settings.';
        if ($ok) {
            $logMsg = mysqli_real_escape_string($GLOBALS['link'],
                'X-Tatar Gold: settings updated (' . ($enabled ? 'enabled' : 'disabled') . ')');
            mysqli_query($GLOBALS['link'],
                "INSERT INTO " . TB_PREFIX . "admin_log VALUES (0, " . $admid . ", '" . $logMsg . "', " . time() . ")");
        }
    }
} elseif ($do === 'adjust_points') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $delta = (int) ($_POST['delta'] ?? 0);
    $note = trim((string) ($_POST['note'] ?? ''));
    $lookupUser = $username;

    if ($username === '' || $delta === 0) {
        $msg = 'Username and a non-zero point amount are required.';
    } else {
        $uid = (int) $database->getUserField($username, 'id', 1);
        if ($uid <= 3) {
            $msg = 'Player not found.';
        } else {
            list($ok, $resultMsg) = XTatarGold::adminAdjustPoints($uid, $delta, $admid, $note !== '' ? $note : 'admin adjustment');
            $msg = $resultMsg;
            if ($ok) {
                $logMsg = mysqli_real_escape_string($GLOBALS['link'],
                    'X-Tatar Gold: admin ' . ($delta > 0 ? 'added' : 'removed') . ' ' . abs($delta) . ' points for ' . $username);
                mysqli_query($GLOBALS['link'],
                    "INSERT INTO " . TB_PREFIX . "admin_log VALUES (0, " . $admid . ", '" . $logMsg . "', " . time() . ")");
            }
        }
    }
}

header("Location: ../../../Admin/admin.php?p=xtatarGold&msg=" . urlencode($msg)
    . ($lookupUser !== '' ? '&lookup=' . urlencode($lookupUser) : ''));
exit;
