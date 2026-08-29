<?php
$gkCssVer = @filemtime(__DIR__ . '/../../css/dorf1_greek.css') ?: time();
$gkHeadOpts = isset($gkShellHeadOpts) && is_array($gkShellHeadOpts) ? $gkShellHeadOpts : array();
$gkIncludeNew2 = !isset($gkHeadOpts['includeNew2Js']) || $gkHeadOpts['includeNew2Js'];
$gkInlineStyle = isset($gkHeadOpts['inlineStyle']) ? $gkHeadOpts['inlineStyle'] : '';
$gkExtraCss = isset($gkHeadOpts['extraCss']) && is_array($gkHeadOpts['extraCss']) ? $gkHeadOpts['extraCss'] : array();
$gkBodyExtra = isset($gkBodyClass) ? trim($gkBodyClass) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo tz_html_dir_attrs(); ?> class="pg-gk">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo $gkPageTitle; ?></title>
	<link rel="shortcut icon" href="favicon.ico" />
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="imagetoolbar" content="no" />
	<script src="mt-full.js?0faab" type="text/javascript"></script>
	<script src="unx.js?f4b7i" type="text/javascript"></script>
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
<?php foreach ($gkExtraCss as $gkCssHref) { ?>
	<link href="<?php echo htmlspecialchars($gkCssHref, ENT_QUOTES, 'UTF-8'); ?>" rel="stylesheet" type="text/css" />
<?php } ?>
	<?php echo tz_rtl_stylesheet_tag(); ?>
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
