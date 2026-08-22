<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : dorf2.php                      	                       ##
##  Type           : In Game Village View Page                                 ##
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
	header("Location: ".$_SERVER['PHP_SELF']);
	exit;
}
else $building->procBuild($_GET);

/**
 * AJAX SUBMIT MODE - see dorf1.php for full explanation. Same
 * mechanism, needed here too since buildings 19+ upgrade through
 * dorf2.php?a=X&c=checker instead of dorf1.php.
 */
if (
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' &&
    isset($_GET['a'])
) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'error'   => false,
        'fieldId' => (int) $_GET['a'],
    ]);
    exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html <?php echo tz_html_dir_attrs(); ?>>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo SERVER_NAME . ' - ' . VILLAGE_CENTER . ' &raquo; ' . (function_exists('tz_display_village_name') ? tz_display_village_name($village->vname, $session->username ?? null) : $village->vname); ?></title>
	<link rel="shortcut icon" href="favicon.ico"/>
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<script src="mt-full.js?0faab" type="text/javascript"></script>
	<script src="unx.js?f4b7h" type="text/javascript"></script>
	<script src="new.js?0faab" type="text/javascript"></script>
	<link href="<?php echo GP_LOCATE; ?>lang/en/lang.css?f4b7d" rel="stylesheet" type="text/css" />
	<link href="<?php echo GP_LOCATE; ?>lang/en/compact.css?f4b7i" rel="stylesheet" type="text/css" />
	<?php
	// GP_LOCATE contine deja pachetul efectiv: alegerea jucatorului cand
	// e permisa si valida, altfel pachetul serverului (vezi config.php).
	// main.css / main_en.css are required here too: they're what defines
	// .overlay / .overlay .mask / .overlay_content, which is what makes
	// #buildPopup (Templates/BuildPopup.tpl, included below on this page
	// as well) render as an actual floating modal instead of inline
	// page content. See dorf1.php for the same fix.
	echo "
	<link href='".GP_LOCATE."main.css?e21d2' rel='stylesheet' type='text/css' />
	<link href='".GP_LOCATE."main_en.css?e21d2' rel='stylesheet' type='text/css' />
	<link href='".GP_LOCATE."novaterra.css?e21d2' rel='stylesheet' type='text/css' />
	<link href='".GP_LOCATE."lang/en/lang.css?e21d2' rel='stylesheet' type='text/css' />";
	?>
	<script type="text/javascript">

		window.addEvent('domready', start);
	</script>
	<?php echo tz_rtl_stylesheet_tag(); ?>
	<style type="text/css">
		html[dir="rtl"] body.pg-dorf2 #content.village2 { left: 20px !important; }
		html[dir="rtl"] body.pg-dorf2 #content.village2,
		html[dir="rtl"] body.pg-dorf2 #side_info,
		html[dir="rtl"] body.pg-dorf2 #side_navi { font-family: Tahoma, Arial, sans-serif; }
		html[dir="rtl"] body.pg-dorf2 #content.village2 p,
		html[dir="rtl"] body.pg-dorf2 #content.village2 a,
		html[dir="rtl"] body.pg-dorf2 #side_info p,
		html[dir="rtl"] body.pg-dorf2 #side_info a,
		html[dir="rtl"] body.pg-dorf2 #side_info td { font-weight: 400; }
	</style>
</head>


<body class="v35 ie ie8 pg-dorf2">
<div class="wrapper">
<img style="filter:chroma();" src="img/x.gif" id="msfilter" alt="" />
<div id="dynamic_header">
	</div>
<?php include("Templates/header.tpl"); ?>
<div id="mid">
<?php include("Templates/menu.tpl"); ?>
		<div id="content"  class="village2">
<h1><?php echo (function_exists('tz_display_village_name') ? tz_display_village_name($village->vname, $session->username ?? null) : $village->vname); if($village->loyalty!='100'){ if($village->loyalty>'33'){ $color="green"; }else{ $color="red"; } ?><div id="loyality"><span style="color:<?php echo $color; ?>;font-size:xx-small;" size><?php echo LOYALTY; ?> <?php echo floor($village->loyalty); ?>%</span></div><?php } ?></h1>
<div id="village_map_wrap">
<?php include("Templates/dorf2.tpl"); ?>
</div>
<?php
if($building->NewBuilding) {
	include("Templates/Building.tpl");
}
?>
</div>
<br /><br /><br /><br /><div id="side_info">
<?php
include("Templates/quest.tpl");
include("Templates/multivillage.tpl");
include("Templates/news.tpl");
if(!NEW_FUNCTIONS_DISPLAY_LINKS) {
	echo "<br><br><br><br>";
	include("Templates/links.tpl");
}
?>
</div>
<div class="clear"></div>
</div>
<div class="footer-stopper"></div>
<div class="clear"></div>
<?php
include("Templates/footer.tpl");
include("Templates/res.tpl");
include("Templates/BuildPopup.tpl");
?>
<div id="stime">
<div id="ltime">
<div id="ltimeWrap">
<?php echo CALCULATED_IN;?> <b><?php
echo round(($generator->pageLoadTimeEnd()-$start_timer)*1000);
?></b> ms

<br /><?php echo SERVER_TIME;?> <span id="tp1" class="b"><?php echo date('H:i:s'); ?></span>
</div>
	</div>
</div>
<div id="ce"></div>
</body>
</html>