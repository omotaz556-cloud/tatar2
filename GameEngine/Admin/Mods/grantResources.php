<?php
require_once __DIR__ . '/../csrf.php';
if (!isset($_SESSION)) session_start();
if (empty($_SESSION['access']) || (int) $_SESSION['access'] < 9) admin_deny('Admin access required.');
csrf_verify();
include_once '../../config.php';
include_once '../../Database.php';

$adminId = (int) ($_SESSION['id'] ?? 0);
$uid = (int) ($_POST['uid'] ?? 0);
$wood = max(0, (int) ($_POST['wood'] ?? 0));
$clay = max(0, (int) ($_POST['clay'] ?? 0));
$iron = max(0, (int) ($_POST['iron'] ?? 0));
$crop = max(0, (int) ($_POST['crop'] ?? 0));

if ($uid <= 3 || ($wood + $clay + $iron + $crop) <= 0) {
    header('Location: ../../../Admin/admin.php?p=grantResources&error=invalid');
    exit;
}

$playerStmt = mysqli_prepare($GLOBALS['link'], 'SELECT username FROM `' . TB_PREFIX . 'users` WHERE id = ? LIMIT 1');
mysqli_stmt_bind_param($playerStmt, 'i', $uid);
mysqli_stmt_execute($playerStmt);
$playerResult = mysqli_stmt_get_result($playerStmt);
$player = $playerResult ? mysqli_fetch_assoc($playerResult) : null;
mysqli_stmt_close($playerStmt);
if (!$player) {
    header('Location: ../../../Admin/admin.php?p=grantResources&error=player');
    exit;
}

$villages = [];
$villageStmt = mysqli_prepare($GLOBALS['link'], 'SELECT wref, name FROM `' . TB_PREFIX . 'vdata` WHERE owner = ?');
mysqli_stmt_bind_param($villageStmt, 'i', $uid);
mysqli_stmt_execute($villageStmt);
$villageResult = mysqli_stmt_get_result($villageStmt);
while ($villageResult && ($row = mysqli_fetch_assoc($villageResult))) $villages[] = $row;
mysqli_stmt_close($villageStmt);

mysqli_begin_transaction($GLOBALS['link']);
$update = mysqli_prepare($GLOBALS['link'], 'UPDATE `' . TB_PREFIX . 'vdata` SET wood=LEAST(maxstore,wood+?), clay=LEAST(maxstore,clay+?), iron=LEAST(maxstore,iron+?), crop=LEAST(maxcrop,crop+?) WHERE wref=? AND owner=?');
foreach ($villages as $village) {
    $wref = (int) $village['wref'];
    mysqli_stmt_bind_param($update, 'iiiiii', $wood, $clay, $iron, $crop, $wref, $uid);
    if (!mysqli_stmt_execute($update)) { mysqli_stmt_close($update); mysqli_rollback($GLOBALS['link']); header('Location: ../../../Admin/admin.php?p=grantResources&error=save'); exit; }
}
mysqli_stmt_close($update);
$safeName = mysqli_real_escape_string($GLOBALS['link'], (string) $player['username']);
$log = mysqli_real_escape_string($GLOBALS['link'], 'Granted resources to player ' . $player['username'] . ' (ID ' . $uid . '): wood +' . $wood . ', clay +' . $clay . ', iron +' . $iron . ', crop +' . $crop . ' across ' . count($villages) . ' village(s).');
mysqli_query($GLOBALS['link'], "INSERT INTO `" . TB_PREFIX . "admin_log` (`id`,`user`,`log`,`time`) VALUES (0," . $adminId . ",'" . $log . "'," . time() . ")");
mysqli_commit($GLOBALS['link']);
header('Location: ../../../Admin/admin.php?p=grantResources&success=1&player=' . $uid);
exit;