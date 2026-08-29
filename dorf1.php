<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##  Filename       : dorf1.php
##  Type           : In Game Resource View (Greek shell)
##  Project        : Novaterra
#################################################################################

use App\Utils\AccessLogger;

include_once("GameEngine/Village.php");
AccessLogger::logRequest();

if (isset($_GET['ok'])) {
	$database->updateUserField($session->uid, 'ok', 0, 1);
	$_SESSION['ok'] = '0';
	unset($_SESSION['cache_user_' . ($_SESSION['username'] ?? '')]);
}

if (isset($_GET['newdid'])) {
	$_SESSION['wid'] = $_GET['newdid'];
	$database->query("UPDATE " . TB_PREFIX . "users SET village_select=" . $database->escape((int) $_GET['newdid']) . " WHERE id=" . $session->uid);
	header("Location: " . $_SERVER['PHP_SELF']);
	exit;
} else {
	$building->procBuild($_GET);
}

if (
	$_SERVER['REQUEST_METHOD'] === 'POST'
	&& isset($_POST['newVNa'])
	&& isset($session)
	&& is_object($session)
	&& !(method_exists($session, 'isSitterSession') && $session->isSitterSession())
	&& !empty($village)
	&& (int) ($village->infoarray['owner'] ?? 0) === (int) $session->uid
) {
	$newName = trim((string) $_POST['newVNa']);
	if ($newName !== '') {
		if (function_exists('mb_strlen') && mb_strlen($newName, 'UTF-8') > 25) {
			$newName = mb_substr($newName, 0, 25, 'UTF-8');
		} elseif (strlen($newName) > 25) {
			$newName = substr($newName, 0, 25);
		}
		if (preg_match('/^[\p{L}\p{N}._\-\[\]()]+(?: [\p{L}\p{N}._\-\[\]()]+)*$/u', $newName)) {
			$database->setVillageName((int) $village->wid, $newName);
			unset($_SESSION['cache_user_' . ($_SESSION['username'] ?? '')]);
		}
	}
	header('Location: ' . $_SERVER['PHP_SELF']);
	exit;
}

if (
	isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
	strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' &&
	isset($_GET['a'])
) {
	header('Content-Type: application/json; charset=UTF-8');
	echo json_encode(array('error' => false, 'fieldId' => (int) $_GET['a']));
	exit;
}

$vDisplayName = tz_display_village_name($village->vname, $session->username ?? null);
$serverLabel = defined('SERVER_NAME') ? SERVER_NAME : 'Novaterra';
$gold = isset($session->gold) ? (int) $session->gold : 0;
$uid = isset($session->uid) ? (int) $session->uid : 0;
$timer = 1;
$gkShell = true;
$gkPageTitle = SERVER_NAME . ' - ' . TZ_VILLAGE_OVERVIEW . ' &raquo; ' . htmlspecialchars($vDisplayName, ENT_QUOTES, 'UTF-8');
tz_greek_shell_head($gkPageTitle, 'pg-dorf1');
tz_greek_shell_open('', array(
	'contentWrap' => false,
	'showVillageTitle' => true,
	'villageTitleInMap' => true,
));
?>
<div class="gk-bod">
<?php include __DIR__ . '/Templates/Greek/gk_village_title.tpl'; ?>
		<table class="gk-inner" cellpadding="0" cellspacing="0">
		<tr>
			<td class="gk-stats" id="map_details">
				<?php
				include("Templates/movement.tpl");
				include("Templates/production.tpl");
				include("Templates/troops.tpl");
				?>
			</td>
			<td class="gk-mapcell">
				<div class="village1">
					<?php include("Templates/field.tpl"); ?>
				</div>
			</td>
		</tr>
		</table>
<?php
if ($building->NewBuilding) {
	include("Templates/Building.tpl");
}
?>
</div>
<?php
tz_greek_shell_close(array('buildPopup' => true, 'timer' => $start_timer));
