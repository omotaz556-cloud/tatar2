<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : feedingSystemAdmin.php                                    ##
##  Type           : BACKEND (Feeding System admin actions)                   ##
##  Developed by   : Shadow                                                    ##
##  Project        : Novaterra                                                 ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
##  Handles the admin actions for the linked-accounts (feeding) system:       ##
##    - toggle the feature on/off                                             ##
##    - set the max linked accounts per player (self-service cap)             ##
##    - toggle the "announced in rules" reminder flag                         ##
##    - manually add/remove a linked pair by username (admin bypasses cap)    ##
##  All three simply call the existing FeedingSystem class; no new game logic.##
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
include_once($autoprefix . "GameEngine/FeedingSystem.php");

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

/**
 * Resolve a username to a uid. Returns 0 if not found.
 */
function fs_admin_uid_by_username($username)
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

function fs_admin_log($admid, $text)
{
    $logMsg = mysqli_real_escape_string($GLOBALS['link'], $text);
    mysqli_query($GLOBALS['link'],
        "INSERT INTO " . TB_PREFIX . "admin_log VALUES (0, " . (int) $admid . ", '" . $logMsg . "', " . time() . ")");
}

$do  = $_POST['do'] ?? '';
$msg = '';

if ($do === 'save_settings') {
    $enabled   = ((string) ($_POST['enabled'] ?? '0') === '1');
    $maxLinked = (int) ($_POST['max_linked_per_player'] ?? 1);
    $announced = ((string) ($_POST['announced_in_rules'] ?? '0') === '1');

    if ($maxLinked < 0) $maxLinked = 0;
    if ($maxLinked > 50) $maxLinked = 50; // sanity cap, generous enough for any legitimate use

    $ok = FeedingSystem::saveSettings($enabled, $maxLinked, $announced);
    if ($ok) {
        $msg = 'Feeding system settings saved.';
        fs_admin_log($admid, 'Feeding System: settings updated — enabled=' . ($enabled ? '1' : '0')
            . ', max_linked=' . $maxLinked . ', announced_in_rules=' . ($announced ? '1' : '0'));
    } else {
        $msg = 'Could not save settings (database error).';
    }
} elseif ($do === 'add_link') {
    $ownerName  = trim((string) ($_POST['owner_username'] ?? ''));
    $linkedName = trim((string) ($_POST['linked_username'] ?? ''));

    $ownerUid  = fs_admin_uid_by_username($ownerName);
    $linkedUid = fs_admin_uid_by_username($linkedName);

    if ($ownerName === '' || $linkedName === '') {
        $msg = 'Both usernames are required.';
    } elseif ($ownerUid === 0) {
        $msg = 'Owner account "' . $ownerName . '" not found.';
    } elseif ($linkedUid === 0) {
        $msg = 'Linked account "' . $linkedName . '" not found.';
    } else {
        // addedBy = admin uid, so FeedingSystem::addLink() bypasses the
        // self-service cap (the client asked for the admin to be able to
        // set this up directly).
        $result = FeedingSystem::addLink($ownerUid, $linkedUid, $admid);
        if ($result['ok']) {
            $msg = $ownerName . ' → ' . $linkedName . ' linked successfully.';
            fs_admin_log($admid, 'Feeding System: admin linked ' . $ownerName . ' -> ' . $linkedName);
        } else {
            $errors = [
                'CANNOT_LINK_SELF'  => 'An account cannot be linked to itself.',
                'ACCOUNT_NOT_FOUND' => 'One of the accounts was not found.',
                'ALREADY_LINKED'    => 'These accounts are already linked.',
                'INVALID_ACCOUNT'   => 'Invalid account.',
                'DB_ERROR'          => 'Database error — please try again.',
                'DB_UNAVAILABLE'    => 'Database unavailable — please try again.',
            ];
            $msg = $errors[$result['error']] ?? ('Could not link accounts (' . $result['error'] . ').');
        }
    }
} elseif ($do === 'remove_link') {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id > 0 && FeedingSystem::removeLinkById($id)) {
        $msg = 'Link removed.';
        fs_admin_log($admid, 'Feeding System: admin removed link #' . $id);
    } else {
        $msg = 'Could not remove link.';
    }
}

header("Location: ../../../Admin/admin.php?p=feedingSystem&msg=" . urlencode($msg));
exit;
