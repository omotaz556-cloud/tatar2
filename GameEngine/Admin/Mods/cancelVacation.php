<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : cancelVacation.php                                       ##
##  Type           : BACKEND (Admin quick-action)                             ##
## --------------------------------------------------------------------------- ##
##  Cancels Vacation Mode for a single player straight from the Admin Panel   ##
##  (Player Details page), so this no longer requires phpMyAdmin/SQL.         ##
##  Equivalent to: vac_mode = 0, vac_time = 0 for that player.                ##
#################################################################################

if (!isset($_SESSION)) session_start();

// Issue #139 pattern: this Mod is POSTed to directly, so it verifies the
// CSRF token itself (it does not go through admin.php's central csrf_verify()).
require_once(__DIR__ . '/../csrf.php');
// NOTE: ADMIN (the access-level constant) is defined in config.php, which we
// haven't included yet at this point — compare against the literal 9 here,
// same pattern used by centralGoldAdmin.php, to avoid an undefined-constant
// warning on this very first check.
if (($_SESSION['access'] ?? 0) < 9) {
    admin_deny('You must be signed in as an administrator to do this. '
        . 'Your session may have expired — please return to the admin panel and sign in again.');
}
csrf_verify();

include_once(__DIR__ . '/../../config.php');
include_once(__DIR__ . '/../../Database.php');

$uid = (int) ($_POST['uid'] ?? 0);
if ($uid <= 0) {
    die('Invalid user');
}

$playerName = $database->getUserField($uid, 'username', 0) ?: 'Unknown';

// Same effect as the player-facing "cancel vacation" action
// (DatabaseUserQueries::removevacationmode): vac_mode = 0, vac_time = 0.
$database->removevacationmode($uid);

$adminUid  = (int) ($_SESSION['id'] ?? 0);
$adminName = $database->getUserField($adminUid, 'username', 0) ?: 'Admin';
$logText   = addslashes("[$adminName] cancelled Vacation Mode for [$playerName] (UID:$uid)");
$now       = time();
$database->query("
    INSERT INTO " . TB_PREFIX . "admin_log
    (`user`, `log`, `time`)
    VALUES ('$adminUid', '$logText', $now)
");

header("Location: ../../../Admin/admin.php?p=player&uid=" . $uid);
exit;
