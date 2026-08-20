<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : multiAccountSettings.php                                  ##
##  Type           : BACKEND (Multi-Account Detection admin actions)          ##
##  Developed by   : Shadow                                                    ##
##  Project        : Novaterra                                                 ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
##  Handles the admin actions for the Multi-Account Detection page:           ##
##    - toggle detection on/off                                               ##
##    - toggle automatic ban on/off + set the auto-ban score threshold        ##
##    - run the auto-ban pass immediately after saving (if enabled)           ##
##  Full-admin only (access=9) — a multihunter can VIEW multiacc.tpl but      ##
##  cannot change these settings, same restriction as pushOverride.php uses   ##
##  MULTIHUNTER for viewing vs the stricter 9 used by feedingSystemAdmin.php  ##
##  for settings changes.                                                     ##
#################################################################################

require_once(__DIR__ . '/../csrf.php');
if (!isset($_SESSION)) session_start();
if (($_SESSION['access'] ?? 0) < 9) {
    admin_deny('You must be signed in as an administrator to do this. '
        . 'Your session may have expired — please return to the admin panel and sign in again.');
}

// This Mod is POSTed to directly, so verify the CSRF token itself.
csrf_verify();

$autoprefix = '';
for ($i = 0; $i < 5; $i++) {
    $autoprefix = str_repeat('../', $i);
    if (file_exists($autoprefix . 'autoloader.php')) break;
}
include_once($autoprefix . "GameEngine/config.php");
include_once($autoprefix . "GameEngine/Database.php");
include_once($autoprefix . "GameEngine/MultiAccount.php");

$admid = (int) ($_SESSION['id'] ?? 0);

// Re-confirm the session's access level against the DB (defence in depth),
// same pattern used by feedingSystemAdmin.php / pushOverride.php.
$check = mysqli_query($GLOBALS['link'],
    "SELECT access FROM " . TB_PREFIX . "users WHERE id = " . $admid);
$acc = $check ? mysqli_fetch_assoc($check) : null;
if (!$acc || (int) $acc['access'] < 9) {
    admin_deny('Your session may have expired — please sign in again.');
}

function mad_admin_log($admid, $text)
{
    $logMsg = mysqli_real_escape_string($GLOBALS['link'], $text);
    mysqli_query($GLOBALS['link'],
        "INSERT INTO " . TB_PREFIX . "admin_log VALUES (0, " . (int) $admid . ", '" . $logMsg . "', " . time() . ")");
}

$msg = '';

$enabled      = ((string) ($_POST['enabled'] ?? '0') === '1');
$autoBan      = ((string) ($_POST['auto_ban'] ?? '0') === '1');
$autoBanScore = (int) ($_POST['auto_ban_score'] ?? MultiAccount::DEFAULT_AUTO_BAN_SCORE);

if ($autoBanScore < 1)   $autoBanScore = 1;
if ($autoBanScore > 100) $autoBanScore = 100;

// Auto-ban cannot be ON while detection itself is OFF — normalise so the
// stored state can never be "auto-ban enabled but detection disabled".
if (!$enabled) {
    $autoBan = false;
}

$ok = MultiAccount::saveSettings($enabled, $autoBan, $autoBanScore);
if ($ok) {
    $msg = 'Multi-Account Detection settings saved.';
    mad_admin_log($admid, 'Multi-Account Detection: settings updated — enabled=' . ($enabled ? '1' : '0')
        . ', auto_ban=' . ($autoBan ? '1' : '0') . ', auto_ban_score=' . $autoBanScore);

    // Run the auto-ban pass right away so a newly-enabled toggle takes
    // effect immediately, without waiting for the next page load.
    if ($enabled && $autoBan) {
        $banned = MultiAccount::applyAutoBan();
        if (!empty($banned)) {
            $names = [];
            foreach ($banned as $p) {
                $names[] = $p['name_a'] . ' & ' . $p['name_b'] . ' (score ' . $p['score'] . ')';
            }
            $msg .= ' Auto-banned ' . count($banned) . ' pair(s): ' . implode('; ', $names);
            mad_admin_log($admid, 'Multi-Account Detection: auto-ban triggered on settings save — '
                . implode('; ', $names));
        }
    }
} else {
    $msg = 'Could not save settings (database error).';
}

header("Location: ../../../Admin/admin.php?p=multiacc&msg=" . urlencode($msg));
exit;
?>