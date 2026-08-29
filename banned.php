<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : banned.php                      	                       ##
##  Type           : In Game Banned Page                                       ##
## --------------------------------------------------------------------------- ##
##  Developed by   : yi12345     				                               ##
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

include_once("GameEngine/Village.php");
AccessLogger::logRequest();

if($session->access == BANNED){
$gkShell = true;
$gkPageTitle = SERVER_NAME . ' - Player Banned';
tz_greek_shell_head($gkPageTitle, 'pg-banned', array('includeNew2Js' => false));
tz_greek_shell_open('village1', array('contentWrap' => true));
include("Admin/Templates/ban_msg.tpl");
?>
<?php
tz_greek_shell_close(array('buildPopup' => false, 'timer' => $start_timer));
<?php
}
else{header("Location: dorf1.php");exit;}?>
