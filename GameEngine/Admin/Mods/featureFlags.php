<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       featureFlags.php                                           ##
##  Type           BACKEND (Generic Feature Flags)                            ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
#################################################################################

require_once(__DIR__ . '/../csrf.php');
if (!isset($_SESSION)) session_start();
if ($_SESSION['access'] < 9) {
    admin_deny('You must be signed in as an administrator to do this. '
        . 'Your session may have expired — please return to the admin panel and sign in again.');
}

csrf_verify();

include_once("../../config.php");

$autoprefix = '';
for ($i = 0; $i < 5; $i++) {
    $autoprefix = str_repeat('../', $i);
    if (file_exists($autoprefix . 'autoloader.php')) break;
}
include_once($autoprefix . "GameEngine/Database.php");
include_once($autoprefix . "GameEngine/FeatureFlags.php");

$admid = (int) ($_SESSION['id'] ?? 0);

$check = mysqli_query($GLOBALS['link'],
    "SELECT access FROM " . TB_PREFIX . "users WHERE id = " . $admid);
$acc = $check ? mysqli_fetch_assoc($check) : null;
if (!$acc || (int) $acc['access'] < 9) {
    admin_deny('Your session may have expired — please sign in again.');
}

$do  = $_POST['do'] ?? '';
$msg = '';

if ($do === 'create') {
    $key     = $_POST['flag_key'] ?? '';
    $enabled = isset($_POST['enabled']) ? 1 : 0;
    $label   = $_POST['label'] ?? '';
    $note    = $_POST['note'] ?? '';

    list($ok, $msg) = FeatureFlags::create($key, $enabled, $label, $note, $admid);
    if ($ok) {
        $logMsg = mysqli_real_escape_string($GLOBALS['link'],
            'Feature flag created: ' . FeatureFlags::normKey($key) . ' (' . ($enabled ? 'ON' : 'OFF') . ')');
        mysqli_query($GLOBALS['link'],
            "INSERT INTO " . TB_PREFIX . "admin_log VALUES (0, " . $admid . ", '" . $logMsg . "', " . time() . ")");
    }
} elseif ($do === 'toggle') {
    $id      = (int) ($_POST['id'] ?? 0);
    $enabled = (int) ($_POST['enabled'] ?? 0);
    if ($id > 0) {
        FeatureFlags::setEnabled($id, $enabled, $admid);
        $logMsg = mysqli_real_escape_string($GLOBALS['link'],
            'Feature flag #' . $id . ' switched ' . ($enabled ? 'ON' : 'OFF'));
        mysqli_query($GLOBALS['link'],
            "INSERT INTO " . TB_PREFIX . "admin_log VALUES (0, " . $admid . ", '" . $logMsg . "', " . time() . ")");
        $msg = $enabled ? 'Flag enabled.' : 'Flag disabled.';
    }
} elseif ($do === 'edit') {
    $id    = (int) ($_POST['id'] ?? 0);
    $label = $_POST['label'] ?? '';
    $note  = $_POST['note'] ?? '';
    if ($id > 0) {
        FeatureFlags::updateMeta($id, $label, $note, $admid);
        $msg = 'Flag updated.';
    }
} elseif ($do === 'delete') {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id > 0) {
        FeatureFlags::delete($id);
        mysqli_query($GLOBALS['link'],
            "INSERT INTO " . TB_PREFIX . "admin_log VALUES (0, " . $admid . ", 'Feature flag deleted (id " . $id . ")', " . time() . ")");
        $msg = 'Flag deleted.';
    }
}

header("Location: ../../../Admin/admin.php?p=featureFlags&msg=" . urlencode($msg));
exit;
?>
