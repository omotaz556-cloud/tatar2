<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : dorf1.php                      	                       ##
##  Type           : In Game Resource View Page                                ##
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

if(isset($_GET['ok'])){
	$database->updateUserField($session->uid,'ok', 0, 1);
	$_SESSION['ok'] = '0';
	// Invalidate the 30s session user-cache (see Session::PopulateVar); otherwise
	// it re-seeds $_SESSION['ok'] from the stale row and the welcome/maintenance
	// redirect keeps firing for up to 30s after acknowledging.
	unset($_SESSION['cache_user_' . ($_SESSION['username'] ?? '')]);
}

if(isset($_GET['newdid'])) {
    $_SESSION['wid'] = $_GET['newdid'];
    $database->query("UPDATE ".TB_PREFIX."users SET village_select=".$database->escape((int) $_GET['newdid'])." WHERE id=".$session->uid);  
	header("Location: ".$_SERVER['PHP_SELF']);
	exit;
} 
else $building->procBuild($_GET);

/**
 * AJAX SUBMIT MODE (build popup upgrade action)
 *
 * The upgrade link in Templates/Build/upgrade.tpl points here
 * (dorf1.php?a=X&c=checker) because procBuild() above is what
 * actually performs the upgrade (upgradeBuilding()). Normally this
 * page then renders the full village view below - but when called
 * from the build popup via AJAX, the player never sees this page;
 * we just need to confirm the submit succeeded and tell the popup
 * which field to reload (build.php?id=X, in fragment mode) to show
 * the new state. No HTML is rendered in this branch.
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
	<title><?php echo SERVER_NAME . ' - ' . TZ_VILLAGE_OVERVIEW . ' &raquo; ' . tz_display_village_name($village->vname, $session->username ?? null); ?></title>
	<link rel="shortcut icon" href="favicon.ico"/>
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<script src="mt-full.js?0faab" type="text/javascript"></script>
	<script src="unx.js?f4b7i" type="text/javascript"></script>
	<script src="new.js?0faab" type="text/javascript"></script>
	<script src="new2.js?0faab" type="text/javascript"></script>
	<link href="<?php echo GP_LOCATE; ?>lang/en/compact.css?f4b7i" rel="stylesheet" type="text/css" />
	<link href="<?php echo GP_LOCATE; ?>lang/en/lang.css?e21d2" rel="stylesheet" type="text/css" />
	<?php
	// GP_LOCATE contine deja pachetul efectiv: alegerea jucatorului cand
	// e permisa si valida, altfel pachetul serverului (vezi config.php).
	// main.css / main_en.css are required here (not just on index.php):
	// they're what defines .overlay / .overlay .mask / .overlay_content,
	// which is what makes #buildPopup (Templates/BuildPopup.tpl) render
	// as an actual floating modal instead of inline page content.
	echo "
	<link href='".GP_LOCATE."main.css?e21d2' rel='stylesheet' type='text/css' />
	<link href='".GP_LOCATE."main_en.css?e21d2' rel='stylesheet' type='text/css' />
	<link href='".GP_LOCATE."novaterra.css?e21d2' rel='stylesheet' type='text/css' />
	<link href='".GP_LOCATE."lang/en/lang.css?e21d2' rel='stylesheet' type='text/css' />";
	?>
	<script type="text/javascript">
	window.addEvent('domready', start);
	</script>
	<?php // Arabic/RTL CSS (css/rtl.css) is now loaded through the shared
	// tz_rtl_stylesheet_tag() mechanism below - see GameEngine/config.php -
	// so it is no longer linked here directly. This makes dorf1.php use the
	// exact same RTL loading path as every other game page. ?>
	<?php echo tz_rtl_stylesheet_tag(); ?>
</head>


<body class="v35 ie ie8">
<div class="wrapper">
<img style="filter:chroma();" src="img/x.gif" id="msfilter" alt="" />
<div id="dynamic_header">
	</div>
<?php include("Templates/header.tpl"); ?>
<div id="mid">
<?php include("Templates/menu.tpl"); ?>
<div id="content"  class="village1">
<h1><?php echo tz_display_village_name($village->vname, $session->username ?? null); if($village->loyalty!='100'){ if($village->loyalty>'33'){ $color="gr"; }else{ $color="re"; } ?><div id="loyality" class="<?php echo $color; ?>"><?php echo LOYALTY; ?> <?php echo floor($village->loyalty); ?>%</div><?php } ?></h1>
<div id="cap" align="left"><?php if($village->capital!='0') { echo "<font color=gray>(".CAPITAL1.")</font>"; } ?></div>
<div id="village_map_wrap">
<?php include("Templates/field.tpl"); ?>
</div>
<?php
$timer = 1;
?>
<div id="map_details">
<br /><br />
<?php
include("Templates/movement.tpl");
include("Templates/production.tpl");
include("Templates/troops.tpl");

if($building->NewBuilding) include("Templates/Building.tpl");
?>
</div>
<?php
/**
 * Structural fix (RTL only - see css/rtl.css for the full explanation).
 *
 * Every other page with this same #side_navi / #content / #side_info
 * layout (dorf2.php, dorf3.php, ...) closes #content BEFORE #side_info
 * opens, so #side_info is a sibling of #content under #mid. dorf1.php was
 * the one page where that closing </div> was left until after #side_info
 * instead - which nests the hero sidebar (#side_info: character portrait +
 * village list) INSIDE #content (div.village1: fixed 537px width,
 * overflow:hidden). Floating a nested #side_info can never place it as a
 * real third column outside that box - it just wraps/gets clipped inside
 * the 537px column. That was the actual cause of the Hero/Sidebar being
 * constrained by the central container; the earlier (reverted) fix worked
 * around it by widening the container instead of fixing this.
 *
 * We only reorder these two closing/opening tags, and only when the
 * language is RTL, by closing #content here (matching dorf2.php/dorf3.php)
 * and re-opening the exact same #side_info markup as a sibling. English
 * keeps the original (unchanged) nested structure below - same markup,
 * same order, byte-for-bit identical to before this fix - so its layout
 * cannot be affected by this change.
 */
$__tz_rtl_dorf1 = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
if ($__tz_rtl_dorf1) {
	echo "</div>\n"; // close #content early, so #side_info below is a sibling
}
?>
<br /><br /><br /><br /><div id="side_info">
<?php
include("Templates/multivillage.tpl");
include("Templates/quest.tpl");
include("Templates/news.tpl");
if(!NEW_FUNCTIONS_DISPLAY_LINKS) {
	echo "<br><br><br><br>";
	include("Templates/links.tpl");
}
?>
</div>
<div class="clear"></div>
<?php if (!$__tz_rtl_dorf1) { ?>
</div>
<?php } ?>
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