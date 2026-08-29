<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Project:       Novaterra                                                    ##
##  Filename       packages.php                                                ##
##  Developed by:  yi12345                                                     ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
##  URLs:          http://novaterra.example                		           ##
##  Source code:   https://github.com/omotaz556-cloud/tatar		                ##
##                                                                             ##
#################################################################################

use App\Utils\AccessLogger;

include_once("GameEngine/Village.php");
AccessLogger::logRequest();

$id = $_GET['id'];
$gkShell = true;
$gkPageTitle = SERVER_NAME . ' - PLUS packages';
tz_greek_shell_head($gkPageTitle, 'pg-packages', array('includeNew2Js' => false));
tz_greek_shell_open('village1', array('contentWrap' => true));
if ($id == "") {
include("Templates/Plus/1.tpl");
}
if ($id == 1) {
include("Templates/Packages/3.tpl");
}
if ($id == 2) {
include("Templates/Packages/2.tpl");
}
if ($id == 3) {
include("Templates/Packages/3.tpl");
}
if ($id == 4) {
include("Templates/Packages/4.tpl");
}
if ($id == 5) {
include("Templates/Packages/5.tpl");
}
?>
<?php
tz_greek_shell_close(array('buildPopup' => false, 'timer' => $start_timer));