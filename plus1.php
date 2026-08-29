<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

use App\Utils\AccessLogger;

include_once("GameEngine/Village.php");
AccessLogger::logRequest();

if (isset($_GET['newdid'])) {
	$_SESSION['wid'] = $_GET['newdid'];
	header("Location: " . $_SERVER['PHP_SELF']);
	exit;
} else {
	$building->procBuild($_GET);
}

$id = isset($_GET['id']) ? preg_replace('/[^0-9]/', '', (string) $_GET['id']) : '';
$allowed = array('110', '111', '112', '113', '3110');
$tpl = ($id !== '' && in_array($id, $allowed, true))
	? ('Templates/Plus/' . $id . '.tpl')
	: '';

if ($tpl === '' || !is_file(__DIR__ . '/' . $tpl)) {
	header('Location: plus.php?id=1');
	exit;
}

$gkShell = true;
include_once('GameEngine/GreekPlus.php');
$gkPlusCss = 'css/greek_maxb_plus.css';
$gkPlusCssVer = is_file(__DIR__ . '/' . $gkPlusCss) ? (int) @filemtime(__DIR__ . '/' . $gkPlusCss) : time();
$gkPageTitle = SERVER_NAME . ' &raquo; &raquo; &raquo; PLUS Tariffs';
tz_greek_shell_head($gkPageTitle, 'pg-plus', array(
	'includeNew2Js' => false,
	'extraCss' => array($gkPlusCss . '?v=' . $gkPlusCssVer),
));
tz_greek_shell_open('', array('contentWrap' => false));
include('Templates/Plus/pmenu.tpl');
include($tpl);
include __DIR__ . '/Templates/Plus/pmenu_close.tpl';
tz_greek_shell_close(array('buildPopup' => false, 'timer' => $start_timer));
