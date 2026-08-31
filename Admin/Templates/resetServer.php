<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : resetServer.php 			                               ##
##  Type           : Admin Panel Frontend                                      ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Dzoki (Original)                                          ##
##  Refactored by  : Shadow                                                    ##
##  Redesign by    : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2025; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################

include_once("../../GameEngine/config.php");
include_once("../../GameEngine/Database.php");
include_once("../../GameEngine/Admin/csrf.php");
require_once("../../GameEngine/Admin/ServerResetPreserve.php");

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION['access']) || (int)$_SESSION['access'] < ADMIN) {
    die("<h1><font color=\"red\">Access Denied: You are not Admin!</font></h1>");
}
csrf_verify();
set_time_limit(0);

$performingAdminId = (int) ($_SESSION['id'] ?? 0);

// Preserve final player stats, medals, and paid-gold ownership before the
// world tables are reset. CentralGold is a separate database and is never
// truncated, so purchased balances remain portable to the new server.
$archiveTable = TB_PREFIX . 'server_archive';
mysqli_query($GLOBALS["link"], "CREATE TABLE IF NOT EXISTS `" . $archiveTable . "` (id int(11) NOT NULL AUTO_INCREMENT, archive_key varchar(32) NOT NULL, payload longtext NOT NULL, created int(11) NOT NULL, PRIMARY KEY(id), KEY archive_key(archive_key)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
foreach (['users', 'medal', 'allimedal'] as $archiveName) {
    $archiveRows = [];
    $archiveSelect = $archiveName === 'users' ? 'id,username,email,tribe,access,gold,plus,protect,timestamp,regtime' : '*';
    $archiveResult = mysqli_query($GLOBALS["link"], "SELECT " . $archiveSelect . " FROM `" . TB_PREFIX . $archiveName . "`");
    while ($archiveResult && ($archiveRow = mysqli_fetch_assoc($archiveResult))) $archiveRows[] = $archiveRow;
    $archivePayload = mysqli_real_escape_string($GLOBALS["link"], json_encode($archiveRows, JSON_UNESCAPED_UNICODE));
    mysqli_query($GLOBALS["link"], "INSERT INTO `" . $archiveTable . "` (archive_key,payload,created) VALUES ('" . $archiveName . "','" . $archivePayload . "'," . time() . ")");
}

// Always preserve every administrator account (access >= ADMIN).
$preservedAdmins = tz_collect_preserved_admin_users($GLOBALS["link"]);

// 1. Golim tot - fără FK checks
mysqli_query($GLOBALS["link"], "SET FOREIGN_KEY_CHECKS=0");

$tables = ["a2b","abdata","activate","active","admin_log","alidata","ali_invite","ali_log","ali_permission","allimedal","artefacts","attacks","banlist","bdata","build_log","chat","deleting","demolition","diplomacy","enforcement","farmlist","fdata","forum_cat","forum_edit","forum_post","forum_survey","forum_topic","general","gold_fin_log","hero","illegal_log","links","login_log","market","market_log","mdata","medal","movement","ndata","online","odata","password","prisoners","raidlist","research","route","send","tdata","tech_log","training","units","vdata","wdata","ww_attacks","croppers","users"];

foreach($tables as $t){
    $tableName = TB_PREFIX . $t;
    $tableNameSql = mysqli_real_escape_string($GLOBALS["link"], $tableName);
    $tableCheck = mysqli_query($GLOBALS["link"], "SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = '" . $tableNameSql . "' LIMIT 1");
    if ($tableCheck && mysqli_num_rows($tableCheck) > 0) {
        mysqli_query($GLOBALS["link"], "TRUNCATE TABLE `" . $tableName . "`");
    }
}

mysqli_query($GLOBALS["link"], "SET FOREIGN_KEY_CHECKS=1");

// 2. Recreăm structura și harta
$database->createDbStructure();
$database->populateWorldData();

// 3. Multihunter password (struct.sql seeds system users 1,2,4,5 — do not re-insert)
$mh_plain_password = bin2hex(random_bytes(6));
$passw = password_hash($mh_plain_password, PASSWORD_BCRYPT, ['cost' => 12]);
$_SESSION['mh_reset_password'] = $mh_plain_password;
$passwEsc = mysqli_real_escape_string($GLOBALS["link"], $passw);
mysqli_query(
    $GLOBALS["link"],
    "UPDATE `" . TB_PREFIX . "users` SET `password` = '$passwEsc', `access` = 9, `is_bcrypt` = 1 WHERE `id` = 5 LIMIT 1"
);

// 4. Restore administrator accounts + starter villages so login still works
$adminIdMap = tz_restore_preserved_admin_users($database, $preservedAdmins);
if ($performingAdminId > 0 && isset($adminIdMap[$performingAdminId])) {
    $_SESSION['id'] = $adminIdMap[$performingAdminId];
    $_SESSION['admin_preserved'] = 1;
} elseif ($preservedAdmins) {
    $_SESSION['admin_preserved'] = 1;
}

// 5. Turn maintenance off so admins are not locked out of the game UI
$database->setMaintenance(0, $performingAdminId > 0 ? $performingAdminId : 0);

// 6. Log (proxy-aware, issue #185)
$resetIp = \App\Utils\IpResolver::getClientIp() ?? ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
$keptCount = count($preservedAdmins);
$logUserId = (int) ($_SESSION['id'] ?? $performingAdminId);
mysqli_query($GLOBALS["link"], "INSERT INTO `" . TB_PREFIX . "admin_log` (user, ip, time, action) VALUES (".$logUserId.", '".$resetIp."', ".time().", 'Server reset (".$keptCount." admin account(s) preserved)')");

header("Location: ../admin.php?p=resetdone");
exit;
?>
