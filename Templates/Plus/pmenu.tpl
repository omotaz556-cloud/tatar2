<?php

#################################################################################
##                -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-               ##
## --------------------------------------------------------------------------- ##
##  Filename       : pmenu.tpl                                                 ##
##  Type           : Plus - Navigation Menu                                    ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  URLs:          : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : Novaterra Project                                          ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.              ##
## --------------------------------------------------------------------------- ##
#################################################################################

?>
<?php
$plusRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
$gkPlusShell = !empty($GLOBALS['gkShell']);
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$uri = basename($_SERVER['PHP_SELF'] ?? 'plus.php');

if (!function_exists('plus_menu_sel')) {
    function plus_menu_sel($cond)
    {
        return $cond ? ' class="selected"' : '';
    }
}
?>
<?php
$gkPlusGreekNav = $gkPlusShell && $plusRtl && class_exists('GreekPlus') && GreekPlus::isGreekNav();
if ($gkPlusGreekNav) {
    GreekPlus::menuOpen(GreekPlus::navPageTitle($id, $uri), $id, $uri);
} else {
    $GLOBALS['gkPlusContentOpen'] = true;
?>
<div id="content" class="plus<?php echo $plusRtl ? ' lang_rtl' : ' lang_ltr'; ?><?php echo $gkPlusShell ? ' gk-plus-page' : ''; ?>" dir="<?php echo $plusRtl ? 'rtl' : 'ltr'; ?>">
<h1><?php echo TZ_NOVATERRA_NAME; ?> <font color="#71D000">P</font><font color="#FF6F0F">l</font><font color="#71D000">u</font><font color="#FF6F0F">s</font></h1>
<div id="textmenu">
   <a href="plus.php"<?php echo plus_menu_sel($id === 0 || $id === 1 || $id >= 100); ?>><?php echo TZ_TARIFFS; ?></a>
 | <a href="plus.php?id=2"<?php echo plus_menu_sel($id === 2); ?>><?php echo TZ_ADVANTAGES; ?></a>
 | <a href="plus.php?id=3"<?php echo plus_menu_sel($id === 3 || ($id >= 6 && $id <= 15)); ?>><?php echo GOLD; ?></a>
 | <a href="plus.php?id=4"<?php echo plus_menu_sel($id === 4); ?>><?php echo FAQ; ?></a>
 | <a href="plus.php?id=5"<?php echo plus_menu_sel($id === 5); ?>><?php echo TZ_EARN_GOLD; ?></a>
 | <a href="a2b2.php"<?php echo plus_menu_sel($uri === 'a2b2.php'); ?>><?php echo TZ_ACCOUNT_STATEMENT; ?></a>
</div>
<?php } ?>
