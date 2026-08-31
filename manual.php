<?php

#################################################################################
##  Filename       : manual.php
##  Purpose        : Portal game encyclopedia (شرح اللعبة) — tatarwars help.phtml
#################################################################################

use App\Utils\AccessLogger;

include_once('GameEngine/config.php');
require_once __DIR__ . '/GameEngine/Lang/loader.php';
tz_load_language(LANG);
if (!function_exists('tz_portal_form_shell_open')) {
    require_once __DIR__ . '/GameEngine/PortalClassic.php';
}
if (class_exists('App\\Utils\\AccessLogger')) {
    AccessLogger::logRequest();
}

if (isset($_GET['s']) && !ctype_digit((string) $_GET['s'])) {
    $_GET['s'] = '0';
}
if (isset($_GET['typ']) && !ctype_digit((string) $_GET['typ'])) {
    $_GET['typ'] = null;
}

$serverName = defined('SERVER_NAME') ? SERVER_NAME : 'Novaterra';
$pageTitle = defined('LOGIN_GAME_GUIDE') ? LOGIN_GAME_GUIDE : 'شرح اللعبة';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo tz_html_dir_attrs(); ?> class="pg-portal-manual">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($serverName . ' - ' . $pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="shortcut icon" href="favicon.ico" />
    <?php echo tz_portal_classic_stylesheet_tag(); ?>
</head>
<body class="webkit v35 manual pg-portal-manual">

<?php echo tz_portal_form_shell_open('manual'); ?>

<?php
if (!isset($_GET['typ']) && !isset($_GET['s'])) {
    include 'Templates/Manual/00.tpl';
} elseif (!isset($_GET['typ']) && isset($_GET['s']) && (int) $_GET['s'] === 1) {
    include 'Templates/Manual/00.tpl';
} elseif (!isset($_GET['typ']) && isset($_GET['s']) && (int) $_GET['s'] === 2) {
    include 'Templates/Manual/direct.tpl';
} elseif (isset($_GET['typ']) && (int) $_GET['typ'] === 5 && isset($_GET['s']) && (int) $_GET['s'] === 3) {
    include 'Templates/Manual/medal.tpl';
} else {
    if (isset($_GET['gid'])) {
        $gid = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $_GET['gid']);
        include 'Templates/Manual/' . $_GET['typ'] . $gid . '.tpl';
    } else {
        if (isset($_GET['typ']) && (int) $_GET['typ'] === 4 && (!isset($_GET['s']) || (int) $_GET['s'] === 0)) {
            $_GET['s'] = 1;
        }
        $s = isset($_GET['s']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $_GET['s']) : '';
        include 'Templates/Manual/' . $_GET['typ'] . $s . '.tpl';
    }
}
?>

<?php echo tz_portal_form_shell_close(); ?>

</body>
</html>
