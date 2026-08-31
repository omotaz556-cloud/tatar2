<?php

#################################################################################
##  Filename       : spielregeln.php
##  Project        : Novaterra
##  Purpose        : Portal game rules (tatarwars login?terms)
#################################################################################

use App\Utils\AccessLogger;

include_once('GameEngine/config.php');
include_once('GameEngine/Database.php');
require_once __DIR__ . '/GameEngine/Lang/loader.php';
tz_load_language(LANG);
if (!function_exists('tz_portal_form_shell_open')) {
    require_once __DIR__ . '/GameEngine/PortalClassic.php';
}
AccessLogger::logRequest();

$serverName = defined('SERVER_NAME') ? SERVER_NAME : 'Novaterra';
$pageTitle = 'قوانين اللعبة';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo tz_html_dir_attrs(); ?> class="pg-portal-terms">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($serverName . ' - ' . $pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="shortcut icon" href="favicon.ico" />
    <?php echo tz_portal_classic_stylesheet_tag(); ?>
</head>
<body class="webkit v35 pg-portal-terms">

<?php echo tz_portal_form_shell_open('terms'); ?>
<?php include __DIR__ . '/Templates/Portal/terms.tpl'; ?>
<?php echo tz_portal_form_shell_close(); ?>

</body>
</html>
