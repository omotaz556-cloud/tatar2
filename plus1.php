<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

use App\Utils\AccessLogger;

include_once("GameEngine/Village.php");
AccessLogger::logRequest();

if(isset($_GET['newdid'])) {
	$_SESSION['wid'] = $_GET['newdid'];
	header("Location: ".$_SERVER['PHP_SELF']);
	exit;
}
else {
	$building->procBuild($_GET);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo tz_html_dir_attrs(); ?>>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo SERVER_NAME ?> - PLUS Packages</title>
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
	// Base game CSS: always load the English/base files here, exactly like
	// every other page (dorf1.php, karte.php, plus.php, ...). Arabic/RTL is
	// layered on top afterwards via the single shared tz_rtl_stylesheet_tag()
	// call below - never by swapping out these base links.
	//
	// BUG FIXED (same one plus.php had): this used to pick a per-language
	// folder ($__css_lang = "ar" whenever gpack/.../lang/ar/ exists) and load
	// "lang/ar/lang.css" + "lang/ar/compact.css" instead of the English
	// ones. The "ar" folder only ships a small RTL OVERRIDE stylesheet
	// (lang.css) meant to sit on top of the English base - it has no
	// compact.css of its own, so that second link 404'd and the page's
	// entire base stylesheet silently failed to load for Arabic players.
	?>
	<link href="<?php echo GP_LOCATE; ?>lang/en/lang.css?f4b7d" rel="stylesheet" type="text/css" />
	<link href="<?php echo GP_LOCATE; ?>lang/en/compact.css?f4b7i" rel="stylesheet" type="text/css" />
	<?php
	// GP_LOCATE contine deja pachetul efectiv: alegerea jucatorului cand
	// e permisa si valida, altfel pachetul serverului (vezi config.php).
	echo "
	<link href='".GP_LOCATE."novaterra.css?e21d2' rel='stylesheet' type='text/css' />
	<link href='".GP_LOCATE."lang/en/plus1.override.css?e21d2' rel='stylesheet' type='text/css' />
	<link href='".GP_LOCATE."lang/en/lang.css?e21d2' rel='stylesheet' type='text/css' />";
	?>
	<script type="text/javascript">

		window.addEvent('domready', start);
	</script>
	<?php // Arabic/RTL CSS is loaded through the single shared
	// tz_rtl_stylesheet_tag() mechanism below - see GameEngine/config.php -
	// on top of the English base links above, exactly like every other
	// game page. ?>
	<?php echo tz_rtl_stylesheet_tag(); ?>
</head>


<body class="v35 ie ie8 pg-plus1">
<div class="wrapper">
<img style="filter:chroma();" src="img/x.gif" id="msfilter" alt="" />
<div id="dynamic_header">
	</div>
<?php include("Templates/header.tpl"); ?>
<div id="mid">
<?php include("Templates/menu.tpl"); ?>
<?php
if(isset($_GET['id'])) {
$id = $_GET['id'];
} else {
$id = "";
}
if ($id == 110) {
include("Templates/Plus/110.tpl");
}
if ($id == 111) {
include("Templates/Plus/111.tpl");
}
if ($id == 112) {
include("Templates/Plus/112.tpl");
}
if ($id == 113) {
include("Templates/Plus/113.tpl");
}
if ($id == 114) {
include("Templates/Plus/114.tpl");
}
if ($id == 116) {
include("Templates/Plus/116.tpl");
}
if ($id == 3110) {
include("Templates/Plus/3110.tpl");
}
?>

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