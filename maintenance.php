<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : maintenance.php                      	                   ##
##  Type           : In Game Maintenance Page                                  ##
## --------------------------------------------------------------------------- ##
##  Developed by   : iopietro 						                           ##
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

if (!function_exists('mysqli_result')) {
    function mysqli_result($res, $row, $field = 0) {
        $res->data_seek($row);
        $datarow = $res->fetch_array();
        return $datarow[$field];
    }
}

include_once("GameEngine/Village.php");
AccessLogger::logRequest();

if(isset($_GET['newdid'])) {
	$_SESSION['wid'] = $_GET['newdid'];
	header("Location: ".$_SERVER['PHP_SELF']);
	exit;
}

// ==== NOUA VERIFICARE (in loc de $_SESSION['ok']) ====
$maintenance = $database->getMaintenance();
if($maintenance['active'] == 1 && $session->access < 9){
$gkShell = true;
$gkPageTitle = SERVER_NAME . ' - Game Over';
$gkMaintenanceStyle = '#content.village2 { font-size: 20pt; text-align: center; }';
tz_greek_shell_head($gkPageTitle, 'pg-maintenance', array('includeNew2Js' => false, 'inlineStyle' => $gkMaintenanceStyle));
tz_greek_shell_open('village2', array('contentWrap' => true));
?>
					<p><b>Presently, the server is not available due to maintenance.</b></p>
					<img src="img/maintenance.png">
					<p><b>This take a few minutes. In the meantime you can drink a coffee.</b></p>
<?php
tz_greek_shell_close(array('buildPopup' => false, 'timer' => $start_timer));
}else{
    header("Location: dorf1.php");
    exit;
}
?>