<?php
$gkCssVer = @filemtime(__DIR__ . '/../../css/dorf1_greek.css') ?: time();
$gkHeadOpts = isset($gkShellHeadOpts) && is_array($gkShellHeadOpts) ? $gkShellHeadOpts : array();
$gkIncludeNew2 = !isset($gkHeadOpts['includeNew2Js']) || $gkHeadOpts['includeNew2Js'];
$gkInlineStyle = isset($gkHeadOpts['inlineStyle']) ? $gkHeadOpts['inlineStyle'] : '';
$gkExtraCss = isset($gkHeadOpts['extraCss']) && is_array($gkHeadOpts['extraCss']) ? $gkHeadOpts['extraCss'] : array();
$gkBodyExtra = isset($gkBodyClass) ? trim($gkBodyClass) : '';
$gkLoadDorf1Css = strpos($gkBodyExtra, 'pg-dorf1') !== false;
$gkDorf1CssVer = $gkLoadDorf1Css ? (int) @filemtime(__DIR__ . '/../../gpack/novaterra_classic/lang/en/dorf1.css') : 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo tz_html_dir_attrs(); ?> class="pg-gk">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" id="gk-viewport" />
	<script type="text/javascript">
	(function () {
		var DESIGN_W = 1024, REF = 1.1, DESKTOP_MIN = DESIGN_W * REF;
		var w = window.innerWidth || DESIGN_W;
		var m = document.getElementById('gk-viewport');
		var html = document.documentElement;
		if (!m) return;
		if (w >= DESKTOP_MIN) {
			m.setAttribute('content', 'width=device-width, initial-scale=1.0');
			html.classList.add('gk-desktop-layout');
		} else {
			var s = Math.min(REF, w / DESIGN_W);
			if (s < 0.05) { s = 0.05; }
			m.setAttribute('content', 'width=1024, initial-scale=' + s.toFixed(4) + ', viewport-fit=cover');
			html.classList.add('gk-scaled-layout');
		}
	})();
	</script>
	<title><?php echo $gkPageTitle; ?></title>
	<link rel="shortcut icon" href="favicon.ico" />
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="imagetoolbar" content="no" />
	<script src="mt-full.js?0faab" type="text/javascript"></script>
	<script src="unx.js?f4b7i" type="text/javascript"></script>
<?php
$gkResbarJsVer = (int) @filemtime(__DIR__ . '/../../js/gk_resbar.js');
if ($gkResbarJsVer > 0) {
?>
	<script src="js/gk_resbar.js?v=<?php echo $gkResbarJsVer; ?>" type="text/javascript"></script>
<?php } ?>
	<script src="new.js?0faab" type="text/javascript"></script>
<?php if ($gkIncludeNew2) { ?>
	<script src="new2.js?0faab" type="text/javascript"></script>
<?php } ?>
	<link href="<?php echo GP_LOCATE; ?>lang/en/compact.css?f4b7i" rel="stylesheet" type="text/css" />
	<link href="<?php echo GP_LOCATE; ?>lang/en/lang.css?e21d2" rel="stylesheet" type="text/css" />
	<link href="<?php echo GP_LOCATE; ?>main.css?e21d2" rel="stylesheet" type="text/css" />
	<link href="<?php echo GP_LOCATE; ?>main_en.css?e21d2" rel="stylesheet" type="text/css" />
	<link href="<?php echo GP_LOCATE; ?>novaterra.css?e21d2" rel="stylesheet" type="text/css" />
	<?php echo tz_greek_stylesheet_tag(); ?>
<?php if ($gkLoadDorf1Css) { ?>
	<link href="<?php echo htmlspecialchars(GP_LOCATE . 'lang/en/dorf1.css?v=' . $gkDorf1CssVer, ENT_QUOTES, 'UTF-8'); ?>" rel="stylesheet" type="text/css" />
<?php } ?>
<?php foreach ($gkExtraCss as $gkCssHref) { ?>
	<link href="<?php echo htmlspecialchars($gkCssHref, ENT_QUOTES, 'UTF-8'); ?>" rel="stylesheet" type="text/css" />
<?php } ?>
	<?php echo tz_rtl_stylesheet_tag(); ?>
<?php
$gkResponsiveVer = (int) @filemtime(__DIR__ . '/../../css/responsive.css');
if ($gkResponsiveVer > 0) {
?>
	<link href="css/responsive.css?v=<?php echo $gkResponsiveVer; ?>" rel="stylesheet" type="text/css" />
<?php }
$gkSiteFontVer = (int) @filemtime(__DIR__ . '/../../css/site-font.css');
if ($gkSiteFontVer > 0 && function_exists('tz_is_rtl_lang') && tz_is_rtl_lang()) {
?>
	<link href="css/site-font.css?v=<?php echo $gkSiteFontVer; ?>" rel="stylesheet" type="text/css" />
<?php }
$gkTwThemeVer = (int) @filemtime(__DIR__ . '/../../css/tatarwars_theme.css');
if ($gkTwThemeVer > 0 && function_exists('tz_is_rtl_lang') && tz_is_rtl_lang()) {
?>
	<link href="css/tatarwars_theme.css?v=<?php echo $gkTwThemeVer; ?>" rel="stylesheet" type="text/css" />
<?php }
$gkTwBtnVer = (int) @filemtime(__DIR__ . '/../../css/tatarwars_ar_buttons.css');
if ($gkTwBtnVer > 0 && function_exists('tz_is_rtl_lang') && tz_is_rtl_lang()) {
?>
	<link href="css/tatarwars_ar_buttons.css?v=<?php echo $gkTwBtnVer; ?>" rel="stylesheet" type="text/css" />
<?php } ?>
<?php if ($gkInlineStyle !== '') { ?>
	<style type="text/css"><?php echo $gkInlineStyle; ?></style>
<?php } ?>
<?php
global $session;
if (isset($session) && is_object($session) && !empty($session->logged_in)
    && function_exists('tz_user_display_prefs_render')) {
    tz_user_display_prefs_render($session);
}
?>
	<script type="text/javascript">window.addEvent('domready', start);</script>
</head>
<body class="v35 pg-gk<?php echo $gkBodyExtra !== '' ? ' ' . htmlspecialchars($gkBodyExtra, ENT_QUOTES, 'UTF-8') : ''; ?>">
<div id="gk-scale-root" class="gk-scale-root">
