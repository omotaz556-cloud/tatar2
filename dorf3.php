<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : dorf3.php                      	                       ##
##  Type           : In Game General View Page                                 ##
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

include_once("GameEngine/Village.php");
AccessLogger::logRequest();

if(isset($_GET['newdid'])) {
    $_SESSION['wid'] = $_GET['newdid'];
    $database->query("UPDATE ".TB_PREFIX."users SET village_select=".$database->escape((int) $_GET['newdid'])." WHERE id=".$session->uid);  
	if(isset($_GET['s'])){
	header("Location: ".$_SERVER['PHP_SELF']."?s=".$_GET['s']);
	exit;
	}else{
	header("Location: ".$_SERVER['PHP_SELF']);
	exit;
}
}
$gkShell = true;
$gkPageTitle = SERVER_NAME . ' - Cross-Village Totals';
tz_greek_shell_head($gkPageTitle, 'pg-dorf3', array('includeNew2Js' => false));
tz_greek_shell_open('village3', array('contentWrap' => true));
if($session->plus){
  if(isset($_GET['s'])){
	if($_GET['s'] == 2){
	  include("Templates/dorf3/2.tpl");
	}elseif($_GET['s'] == 3){
	  include("Templates/dorf3/3.tpl");
	}elseif($_GET['s'] == 4){
	  include("Templates/dorf3/4.tpl");
	}elseif($_GET['s'] == 5){
	  include("Templates/dorf3/5.tpl");
	}
  }else{
	include("Templates/dorf3/1.tpl");
  }
}else{
  include("Templates/dorf3/noplus.tpl");
}
?>
<?php
tz_greek_shell_close(array('buildPopup' => false, 'timer' => $start_timer));
