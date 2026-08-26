<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : manual.php                      	                       ##
##  Type           : In Game Manual Page                                       ##
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

include_once("GameEngine/config.php");
require_once __DIR__ . "/GameEngine/Lang/loader.php";
tz_load_language(LANG);

$tzManualLang = defined('LANG') ? LANG : 'en';
$tzManualTitle = defined('PUBLIC_MANUAL') ? PUBLIC_MANUAL : 'Manual';
?>

<html <?php echo tz_html_dir_attrs(); ?>>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo SERVER_NAME; ?> - <?php echo htmlspecialchars($tzManualTitle, ENT_QUOTES, 'UTF-8'); ?></title>
		<link rel="shortcut icon" href="favicon.ico"/>
	<meta name="content-language" content="<?php echo htmlspecialchars($tzManualLang, ENT_QUOTES, 'UTF-8'); ?>" />
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<script src="mt-core.js?0faab" type="text/javascript"></script>
	<script src="mt-more.js?0faab" type="text/javascript"></script>
	<script src="unx.js?f4b7h" type="text/javascript"></script>
	<script src="new.js?0faab" type="text/javascript"></script>
	<!-- Sprite pack stays on en/compact.css; RTL overlays come from tz_rtl_stylesheet_tag(). -->
	<link href="<?php echo GP_LOCATE; ?>lang/en/compact.css?f4b7i" rel="stylesheet" type="text/css" />
	<link href="<?php echo GP_LOCATE; ?>lang/en/lang.css?f4b7d" rel="stylesheet" type="text/css" />
	<link href="<?php echo GP_LOCATE; ?>novaterra.css?f4b7d" rel="stylesheet" type="text/css" />
	   	<?php echo tz_rtl_stylesheet_tag(); ?>
</head>
	<body class="manual pg-manual">
<?php

if (isset($_GET['s']) && !ctype_digit((string)$_GET['s'])) {
	$_GET['s'] = "0";
}
if (isset($_GET['typ']) && !ctype_digit((string)$_GET['typ'])) {
	$_GET['typ'] = null;
}
if(!isset($_GET['typ']) && !isset($_GET['s'])) {
	include("Templates/Manual/00.tpl");
}
else if (!isset($_GET['typ']) && $_GET['s'] == 1) {
	include("Templates/Manual/00.tpl");
}
else if (!isset($_GET['typ']) && $_GET['s'] == 2) {
	include("Templates/Manual/direct.tpl");
}
else if (isset($_GET['typ']) && $_GET['typ'] == 5 && isset($_GET['s']) && $_GET['s'] == 3) {
	include("Templates/Manual/medal.tpl");
}
else {
	if(isset($_GET['gid'])) {
		include("Templates/Manual/".$_GET['typ'].(preg_replace("/[^a-zA-Z0-9_-]/","",$_GET['gid'])).".tpl");
	}
	else {
		if(isset($_GET['typ']) && $_GET['typ'] == 4 && (!isset($_GET['s']) || $_GET['s'] == 0)) {
			$_GET['s'] = 1;
		}
		// Popup(0,0,1) opens typ=0 → use Arabic overview (00.tpl), not English 0.tpl
		if (isset($_GET['typ']) && (string)$_GET['typ'] === '0' && (!isset($_GET['s']) || $_GET['s'] === '' || $_GET['s'] === '0')) {
			include("Templates/Manual/00.tpl");
		} else {
			include("Templates/Manual/".$_GET['typ'].preg_replace("/[^a-zA-Z0-9_-]/","",(isset($_GET['s']) ? $_GET['s'] : '')).".tpl");
		}
	}
}
?>
</body>

</html>
