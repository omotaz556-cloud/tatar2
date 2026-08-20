<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : anleitung.php                      	                   ##
##  Type           : In Game Part of Main Page                                 ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Dzoki & Advocaite & Donnchadh                             ##
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

include_once("GameEngine/config.php");
include_once("GameEngine/Database.php");
require_once __DIR__ . "/GameEngine/Lang/loader.php";
tz_load_language(LANG);
AccessLogger::logRequest();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php echo tz_html_dir_attrs(); ?>>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo SERVER_NAME; ?></title>
	<link rel="stylesheet" type="text/css" href="img/tutorial/main.css"/>
	<link rel="stylesheet" type="text/css" href="img/tutorial/flaggs.css"/>
	<meta name="content-language" content="en"/>
	<meta http-equiv="imagetoolbar" content="no"/>
	<script src="mt-core.js" type="text/javascript"></script>
	<script src="new.js" type="text/javascript"></script>
	<style type="text/css" media="screen">

	</style>
	<?php echo tz_rtl_stylesheet_tag(); ?>
</head>
<body class="webkit contentPage">
<div class="wrapper">
<div id="country_select">

</div>
<div id="header">
	<h1><?php echo PUBLIC_WELCOME_TO; ?> <?php echo SERVER_NAME; ?></h1>
</div>

<div id="navigation">

<a href="index.php" class="home"><img src="img/x.gif" alt="Novaterra"/></a>

	<table class="menu">

	<tr>

		<td><a href="tutorial.php"><span><?php echo TUTORIAL; ?></span></a></td>

		<td><a href="anleitung.php"><span><?php echo PUBLIC_MANUAL; ?></span></a></td>

		<td><a href="https://github.com/omotaz556-cloud/tatar/discussions" target="_blank"><span><?php echo FORUM; ?></span></a></td>





		<td><a href="index.php?signup"><span><?php echo PUBLIC_REGISTER; ?></span></a></td>

		<td><a href="index.php?login"><span><?php echo LOGIN; ?></span></a></td>

</tr>

	</table>

</div>






<div id="content">

	<div class="grit">


<h1><?php echo PUBLIC_MANUAL; ?></h1>



<p class="submenu">

<a href="anleitung.php"><?php echo PUBLIC_TRIBES; ?></a> |

<a href="anleitung.php?s=1"><?php echo BUILDINGS; ?></a> |

<a href="anleitung.php?s=3"><?php echo MANUAL_FAQ; ?></a>

</p>



<?php
if(!isset($_GET['s'])) {
$_GET['s'] = ""; }
if ($_GET['s'] == "") {
include("Templates/Anleitung/0.tpl"); }
if ($_GET['s'] == "1") {
include("Templates/Anleitung/1.tpl"); }
if ($_GET['s'] == "3") {
include("Templates/Anleitung/3.tpl"); }
if ($_GET['s'] == "4") {
include("Templates/Anleitung/4.tpl"); }
?>



</ul>

<div class="footer"></div>

</div>

</div>

<div id="iframe_layer" class="overlay">



<div class="mask closer"></div>







<div class="overlay_content">

<a href="index.php" class="closer"><img class="dynamic_img" alt="<?php echo PUBLIC_CLOSE; ?>" src="img/un/x.gif" /></a>

<h2>Anleitung</h2>



<div id="frame_box" >

</div>

<div class="footer"></div>

</div>



</div>




</body>
</html>
