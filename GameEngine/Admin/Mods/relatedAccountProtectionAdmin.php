<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : relatedAccountProtectionAdmin.php                        ##
##  Type           : BACKEND (Related Account Protection admin actions)       ##
##  Developed by   : Shadow                                                    ##
##  Project        : Novaterra                                                 ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
##  Handles the admin actions for the related-account raid-blocking system:   ##
##    - toggle the feature on/off                                             ##
##    - toggle Auto-Ban on Attempt on/off                                     ##
##    - manually declare/remove a related (blocked) pair by username          ##
##  All actions simply call the existing RelatedAccountProtection class; no   ##
##  new game logic lives in this file.                                        ##
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
include_once($autoprefix . "GameEngine/RelatedAccountProtection.php");

$admid = (int) ($_SESSION['id'] ?? 0);

// Re-confirm the session's access level against the DB, same pattern used by
// the other admin Mods (feedingSystemAdmin.php, centralGoldAdmin.php etc.) -
// a stale session cookie should not be trusted on its own for a privileged
// action.
$check = mysqli_query($GLOBALS['link'],
    "SELECT access FROM " . TB_PREFIX . "users WHERE id = " . $admid);
$acc = $check ? mysqli_fetch_assoc($check) : null;
if (!$acc || (int) $acc['access'] < 9) {
    admin_deny('Your session may have expired — please sign in again.');
}

/**
 * Resolve a username to a uid. Returns 0 if not found.
 */
function rap_admin_uid_by_username($username)
{
    $username = trim((string) $username);
    if ($username === '') {
        return 0;
    }
    $stmt = mysqli_prepare($GLOBALS['link'], "SELECT id FROM " . TB_PREFIX . "users WHERE username = ? LIMIT 1");
    if (!$stmt) {
        return 0;
    }
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = $res ? mysqli_fetch_assoc($res) : null;
    mysqli_stmt_close($stmt);
    return $row ? (int) $row['id'] : 0;
}

function rap_admin_log($admid, $text)
{
    $logMsg = mysqli_real_escape_string($GLOBALS['link'], $text);
    mysqli_query($GLOBALS['link'],
        "INSERT INTO " . TB_PREFIX . "admin_log VALUES (0, " . (int) $admid . ", '" . $logMsg . "', " . time() . ")");
}

$do  = $_POST['do'] ?? '';
$msg = '';

if ($do === 'save_settings') {
    $enabled          = ((string) ($_POST['enabled'] ?? '0') === '1');
    $autoBanOnAttempt = ((string) ($_POST['auto_ban_on_attempt'] ?? '0') === '1');

    $ok = RelatedAccountProtection::saveSettings($enabled, $autoBanOnAttempt);
    if ($ok) {
        $msg = 'Related Account Protection settings saved.';
        rap_admin_log($admid, 'Related Account Protection: settings updated — enabled=' . ($enabled ? '1' : '0')
            . ', auto_ban_on_attempt=' . ($autoBanOnAttempt ? '1' : '0'));
    } else {
        $msg = 'Could not save settings (database error).';
    }
} elseif ($do === 'add_relation') {
    $nameA  = trim((string) ($_POST['username_a'] ?? ''));
    $nameB  = trim((string) ($_POST['username_b'] ?? ''));
    $reason = trim((string) ($_POST['reason'] ?? ''));

    $uidA = rap_admin_uid_by_username($nameA);
    $uidB = rap_admin_uid_by_username($nameB);

    if ($nameA === '' || $nameB === '') {
        $msg = 'Both usernames are required.';
    } elseif ($uidA === 0) {
        $msg = 'Account "' . $nameA . '" not found.';
    } elseif ($uidB === 0) {
        $msg = 'Account "' . $nameB . '" not found.';
    } else {
        $result = RelatedAccountProtection::addRelation($uidA, $uidB, $admid, $reason);
        if ($result['ok']) {
            $msg = $nameA . ' ↔ ' . $nameB . ' marked as related — raiding between them is now blocked.';
            rap_admin_log($admid, 'Related Account Protection: admin related ' . $nameA . ' <-> ' . $nameB);
        } else {
            $errors = [
                'CANNOT_LINK_SELF'  => 'An account cannot be related to itself.',
                'ACCOUNT_NOT_FOUND' => 'One of the accounts was not found.',
                'ALREADY_RELATED'   => 'These accounts are already marked as related.',
                'INVALID_ACCOUNT'   => 'Invalid account.',
                'DB_ERROR'          => 'Database error — please try again.',
                'DB_UNAVAILABLE'    => 'Database unavailable — please try again.',
            ];
            $msg = $errors[$result['error']] ?? ('Could not relate accounts (' . $result['error'] . ').');
        }
    }
} elseif ($do === 'remove_relation') {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id > 0 && RelatedAccountProtection::removeRelationById($id)) {
        $msg = 'Relation removed — raiding between these accounts is no longer blocked.';
        rap_admin_log($admid, 'Related Account Protection: admin removed relation #' . $id);
    } else {
        $msg = 'Could not remove relation.';
    }
}

header("Location: ../../../Admin/admin.php?p=relatedAccountProtection&msg=" . urlencode($msg));
exit;