<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : Protection.php                    	                       ##
##  Type           : Protection System Backend                                 ##
## --------------------------------------------------------------------------- ##
##  Developed by   : SlimShady           			                           ##
##  Refactored by  : Shadow & Ferywir									       ##
##  Thanks to      : ronix, InCube, Akakori, Elmar & Kirilloid                 ##
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

//heef npc uitzondering omdat die met speciaal $_post werken
if(isset($_POST)){
	if(!isset($_POST['ft'])){
	//$_POST = @array_map('mysqli_real_escape_string', $_POST);
	$_POST = array_map('htmlspecialchars', $_POST);
	}
}
			$rsargs=$_GET['rsargs'];
//$_GET = array_map('mysqli_real_escape_string', $_GET);
$_GET = array_map('htmlspecialchars', $_GET);
			$_GET['rsargs']=$rsargs;
//$_COOKIE = array_map('mysqli_real_escape_string', $_COOKIE);
$_COOKIE = array_map('htmlspecialchars', $_COOKIE);
?>