<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : karte.php                      	                       ##
##  Type           : In Game Map View Page                                     ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Dzoki 						                               ##
##  Refactored by  : Shadow                                                    ##
##  Redesign by    : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  URLs:          : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################

use App\Utils\AccessLogger;

if(isset($_GET['z']) && !is_numeric($_GET['z'])) die('Hacking Attempt');
include_once("GameEngine/Village.php");
AccessLogger::logRequest();

if(isset($_GET['newdid'])) {
	$_SESSION['wid'] = $_GET['newdid'];
if(isset($_GET['d']) && isset($_GET['c'])){
		header("Location: ".$_SERVER['PHP_SELF']."?d=".preg_replace("/[^a-zA-Z0-9_-]/","",$_GET['d'])."&c=".preg_replace("/[^a-zA-Z0-9_-]/","",$_GET['c']));
		exit;
}
else if(isset($_GET['d'])){
		header("Location: ".$_SERVER['PHP_SELF']."?d=".preg_replace("/[^a-zA-Z0-9_-]/","",$_GET['d']));
		exit;
}
else{
	header("Location: ".$_SERVER['PHP_SELF']);
	exit;
}
} else {
	$building->procBuild($_GET);
}
$gkShell = true;
$gkPageTitle = SERVER_NAME . ' - World Map';
tz_greek_shell_head($gkPageTitle, 'pg-map', array('includeNew2Js' => false));
tz_greek_shell_open('map', array('contentWrap' => true));
if(isset($_GET['d']) && !empty($_GET['d']) && isset($_GET['c']) && !empty($_GET['c'])) {
    if($generator->getMapCheck($_GET['d']) == $_GET['c']) include("Templates/Map/vilview.tpl");
	else 
	{
		header("Location: dorf1.php");
		exit;
	}
}
else {
	include("Templates/Map/mapview.tpl");
}
?>
<?php
tz_greek_shell_close(array('buildPopup' => false, 'timer' => $start_timer));
