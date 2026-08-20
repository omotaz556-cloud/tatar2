<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : multiAccountPlayerActions.php                             ##
##  Type           : BACKEND (Multi Account Admin — per-player actions)       ##
##  Developed by   : Shadow                                                    ##
##  Project        : Novaterra                                                 ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
##  Handles the admin actions available from the "Multi Account Admin" player  ##
##  dashboard (Admin/Templates/multiaccPlayer.tpl):                            ##
##    - ban / unban an account (reuses MultiAccount::banAccount/unbanAccount)  ##
##    - mark two accounts as related / remove an existing relation (reuses     ##
##      RelatedAccountProtection::addRelation/removeRelationById)              ##
##  No new game logic lives in this file — it is a thin controller over the    ##
##  two existing engines, same pattern as multiAccountSettings.php and         ##
##  relatedAccountProtectionAdmin.php. Full-admin only (access=9); a           ##
##  multihunter (access=8) can VIEW the dashboard but not act from it.         ##
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
include_once($autoprefix . "GameEngine/MultiAccount.php");
include_once($autoprefix . "GameEngine/RelatedAccountProtection.php");

$admid = (int) ($_SESSION['id'] ?? 0);

// Re-confirm the session's access level against the DB (defence in depth),
// same pattern used by the other admin Mods.
$check = mysqli_query($GLOBALS['link'],
    "SELECT access FROM " . TB_PREFIX . "users WHERE id = " . $admid);
$acc = $check ? mysqli_fetch_assoc($check) : null;
if (!$acc || (int) $acc['access'] < 9) {
    admin_deny('Your session may have expired — please sign in again.');
}

function mapa_admin_log($admid, $text)
{
    $logMsg = mysqli_real_escape_string($GLOBALS['link'], $text);
    mysqli_query($GLOBALS['link'],
        "INSERT INTO " . TB_PREFIX . "admin_log VALUES (0, " . (int) $admid . ", '" . $logMsg . "', " . time() . ")");
}

$do  = $_POST['do'] ?? '';
$uid = (int) ($_POST['uid'] ?? 0);   // the player the dashboard is focused on — used for the redirect
$msg = '';

if ($do === 'ban') {
    $targetUid = (int) ($_POST['target_uid'] ?? 0);
    $reason    = trim((string) ($_POST['reason'] ?? 'Multi-account: manual ban from player dashboard'));

    if ($targetUid <= 3) {
        $msg = 'Invalid account.';
    } elseif (MultiAccount::banAccount($targetUid, $admid, $reason)) {
        $msg = 'Account #' . $targetUid . ' has been banned.';
        mapa_admin_log($admid, 'Multi Account Admin: banned uid ' . $targetUid . ' from player dashboard — ' . $reason);
    } else {
        $msg = 'Could not ban account #' . $targetUid . ' (not found, or is staff).';
    }
} elseif ($do === 'unban') {
    $targetUid = (int) ($_POST['target_uid'] ?? 0);

    if ($targetUid <= 3) {
        $msg = 'Invalid account.';
    } elseif (MultiAccount::unbanAccount($targetUid, $admid, 'unbanned from player dashboard')) {
        $msg = 'Account #' . $targetUid . ' has been unbanned.';
        mapa_admin_log($admid, 'Multi Account Admin: unbanned uid ' . $targetUid . ' from player dashboard');
    } else {
        $msg = 'Could not unban account #' . $targetUid . '.';
    }
} elseif ($do === 'mark_related') {
    $otherUid = (int) ($_POST['other_uid'] ?? 0);
    $reason   = trim((string) ($_POST['reason'] ?? ''));

    if ($uid <= 0 || $otherUid <= 0) {
        $msg = 'Both accounts are required.';
    } else {
        $result = RelatedAccountProtection::addRelation($uid, $otherUid, $admid, $reason);
        if ($result['ok']) {
            $msg = 'Accounts #' . $uid . ' and #' . $otherUid . ' marked as related — raiding between them is now blocked.';
            mapa_admin_log($admid, 'Multi Account Admin: marked uid ' . $uid . ' <-> ' . $otherUid . ' as related');
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
} elseif ($do === 'remove_related') {
    $relId = (int) ($_POST['rel_id'] ?? 0);
    if ($relId > 0 && RelatedAccountProtection::removeRelationById($relId)) {
        $msg = 'Relation removed — raiding between these accounts is no longer blocked.';
        mapa_admin_log($admid, 'Multi Account Admin: removed relation #' . $relId . ' from player dashboard');
    } else {
        $msg = 'Could not remove relation.';
    }
}

$redirect = '../../../Admin/admin.php?p=multiaccPlayer&uid=' . $uid . '&msg=' . urlencode($msg);
header("Location: " . $redirect);
exit;
