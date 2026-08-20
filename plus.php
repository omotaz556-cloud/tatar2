<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : plus.php                      	                           ##
##  Type           : In Game Plus Page                                         ##
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
	header("Location: ".$_SERVER['PHP_SELF']);
	exit;
}
else $building->procBuild($_GET);

// Gold shop: promo-code redemption (player-side). Best-effort; the engine
// self-creates its tables and validates the code (active / expiry / uses /
// once-per-player) before granting gold.
$promoMsg = '';
$promoOk  = false;
if (isset($_POST['redeem_code']) && class_exists('GoldShop')) {
    $__uid = isset($session) && isset($session->uid) ? (int)$session->uid : 0;
    $rr = GoldShop::redeem($__uid, $_POST['redeem_code']);
    $promoOk  = $rr[0];
    $promoMsg = $rr[1];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo tz_html_dir_attrs(); ?>>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php
	echo SERVER_NAME . ' &raquo; &raquo; &raquo; PLUS ';

	if (!empty($_GET['id'])) {
	    switch ($_GET['id']) {
	        case '2':
	            echo 'Advantages';
	            break;

	        case '3':
	            echo 'Gold';
	            break;

	        case '4':
	            echo 'FAQ';
	            break;

	        case '5':
	            echo 'Earn Gold';
	            break;
	    }
	} else {
	    echo 'Tariffs';
	}
	?></title>
	<link rel="shortcut icon" href="favicon.ico"/>
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<script src="mt-full.js?0faab" type="text/javascript"></script>
	<script src="unx.js?f4b7h" type="text/javascript"></script>
	<script src="new.js?0faab" type="text/javascript"></script>
	<?php
	// Base game CSS: ALWAYS load the English/base files here, exactly like
	// every other page (dorf1.php, dorf2.php, karte.php, build.php,
	// berichte.php, nachrichten.php, allianz.php, spieler.php,
	// statistiken.php, ...). Arabic/RTL is layered on top afterwards via the
	// single shared tz_rtl_stylesheet_tag() call below - never by swapping
	// out these base links.
	//
	// BUG FIXED: this page used to pick a per-language folder here
	// ($__css_lang = "ar" whenever gpack/.../lang/ar/ exists) and load
	// "lang/ar/lang.css" + "lang/ar/compact.css" instead of the English
	// ones. The "ar" folder only ships a small RTL OVERRIDE stylesheet
	// (lang.css) meant to sit on top of the English base - it has no
	// compact.css of its own, so that second link 404'd and the entire
	// base stylesheet (all of #content/#side_navi/#side_info's widths and
	// floats, table styling, fonts, etc.) silently failed to load for
	// Arabic players. It also skipped lang/en/lang.css, which is what
	// @imports modules/new_layout_ltr.css (the #mid/#side_navi/#side_info
	// column widths) - so the three-column layout had no widths to lay
	// out against at all.
	//
	// That's why the layout looked broken for Arabic on this page only:
	// #side_navi and #side_info floats were being set (by
	// gpack/.../lang/ar/lang.css and by css/rtl.css) but had no base
	// widths/columns to float within, so #side_info (menu.tpl's "hero"
	// column - multivillage list, quest character image, news) wrapped
	// onto its own line instead of sitting beside #content as the left
	// column, which is also why the character image looked misplaced.
	?>
	<link href="<?php echo GP_LOCATE; ?>lang/en/lang.css?f4b7d" rel="stylesheet" type="text/css" />
	<link href="<?php echo GP_LOCATE; ?>lang/en/compact.css?f4b7i" rel="stylesheet" type="text/css" />
	<?php
	// GP_LOCATE contine deja pachetul efectiv: alegerea jucatorului cand
	// e permisa si valida, altfel pachetul serverului (vezi config.php).
	echo "
	<link href='".GP_LOCATE."novaterra.css?e21d2' rel='stylesheet' type='text/css' />
	<link href='".GP_LOCATE."lang/en/plus.override.css?e21d2' rel='stylesheet' type='text/css' />
	<link href='".GP_LOCATE."lang/en/lang.css?e21d2' rel='stylesheet' type='text/css' />";
	?>
	<script type="text/javascript">

		window.addEvent('domready', start);
	</script>
	<?php // Arabic/RTL CSS (css/rtl.css, plus any per-gpack lang/ar/lang.css
	// override) is loaded through the single shared tz_rtl_stylesheet_tag()
	// mechanism below - see GameEngine/config.php - on top of the English
	// base links above, exactly like every other game page. ?>
	<?php echo tz_rtl_stylesheet_tag(); ?>
</head>


<body class="v35 ie ie8 pg-plus">
<div class="wrapper">
<img style="filter:chroma();" src="img/x.gif" id="msfilter" alt="" />
<div id="dynamic_header">
	</div>
<?php include("Templates/header.tpl"); ?>
<div id="mid">
<?php include("Templates/menu.tpl"); ?>
<?php
/**
 * BUG FIXED: this page never wrapped its Plus/*.tpl includes in a
 * <div id="content"> the way every other page with this same
 * #side_navi / #content / #side_info layout does (spieler.php,
 * statistiken.php, karte.php via mapview.tpl/vilview.tpl,
 * dorf1.php/dorf2.php via menu.tpl). menu.tpl only opens #side_navi
 * here (its own #content/#side_info block is guarded by
 * if($sessionOk) and is only used for the post-registration
 * announcement screen), so without this wrapper #side_info - opened
 * a few lines below - had no #content sibling to float beside.
 * lang/en/compact.css floats #side_navi, #content and #side_info as
 * three siblings; missing the middle one broke that chain and
 * #side_info (menu.tpl's "hero" column, which is where
 * Templates/quest.tpl's #qge character image lives) fell out of the
 * column layout and rendered loose in the page's whitespace instead
 * of sitting in its normal spot in the sidebar.
 */
?>
<div id="content" class="plus">
<?php
if(isset($_GET['id'])){
	$id = preg_replace("/[^a-zA-Z0-9_-]/", "", $_GET['id']);
} 
else $id = "";

if(empty($id)) include ("Templates/Plus/1.tpl");

if($id == 1){
	include ("Templates/Plus/3.tpl");
}
if($id == 2){
	include ("Templates/Plus/2.tpl");
}
if($id == 3){
	include ("Templates/Plus/3.tpl");
}
if($id == 4){
	include ("Templates/Plus/4.tpl");
}
if(isset($_GET['mail']) && $id == 5){
	include ("Templates/Plus/invite.tpl");
}else if($id == 5){
	include ("Templates/Plus/5.tpl");
}
if($id == 7){
	include ("Templates/Plus/7.tpl");
}
if($id == 8){
	include ("Templates/Plus/8.tpl");
}
if($id == 9){
	include ("Templates/Plus/9.tpl");
}
if($id == 10){
	include ("Templates/Plus/10.tpl");
}
if($id == 11){
	include ("Templates/Plus/11.tpl");
}
if($id == 12){
	include ("Templates/Plus/12.tpl");
}
/**
 * BUG REPARAT: id-urile 13 si 14 includeau Templates/Plus/13.tpl si 14.tpl,
 * fisiere care NU EXISTA in proiect. Orice acces la plus.php?id=13 dadea
 * eroare fatala si pagina alba.
 *
 * Verificam existenta inainte de includere; daca lipsesc, aratam pagina
 * obisnuita de functii Plus, ca jucatorul sa aiba unde merge.
 */
if($id == 13 || $id == 14){
	$plusExtraTpl = "Templates/Plus/" . (int) $id . ".tpl";

	if (is_file($plusExtraTpl)) {
		include ($plusExtraTpl);
	} else {
		include ("Templates/Plus/3.tpl");
	}
}
if($id == 15){
	include ("Templates/Plus/15.tpl");
}
if($id > 15){
	include ("Templates/Plus/3.tpl");
}
?>
</div>
<?php
if (isset($_POST['mail'])) {

	$email = trim($_POST['mail']);
	$text = isset($_POST['text']) ? trim($_POST['text']) : '';

	// Blocăm CRLF injection și validăm adresa
	if (
		strpos($email, "\r") === false &&
		strpos($email, "\n") === false &&
		filter_var($email, FILTER_VALIDATE_EMAIL)
	) {
		// Limităm dimensiunea și eliminăm caracterele de control
		$text = substr($text, 0, 2000);
		$text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);

		$mailer->sendInvite($email, $session->uid, $text);
	}
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
</div>
<div class="footer-stopper"></div>
<div class="clear"></div>

<?php
include("Templates/footer.tpl");
include("Templates/res.tpl");
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