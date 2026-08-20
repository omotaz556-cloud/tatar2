<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : HERO T4 NAV BAR                                           ##
##  Type           : BUILDING TEMPLATE                                         ##
## --------------------------------------------------------------------------- ##
##  Created by     : Shadow                                                    ##
##  Designed by    : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  Test Server    : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : Novaterra Project                                          ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.              ##
## --------------------------------------------------------------------------- ##
#################################################################################


$t4HeroItems  = new HeroItems();
$t4Silver     = $t4HeroItems->getSilver($session->uid);
$t4Tabs = [
    'hero'       => ['label' => HERO_T4_TAB_HERO,       'url' => 'build.php?id=' . $id],
    // Oaze: pagina foloseste parametrul "land", nu "t4tab" (flux mai vechi).
    'land'       => ['label' => defined('HERO_T4_TAB_OASIS') ? HERO_T4_TAB_OASIS : 'Oasis',
                     'url'   => 'build.php?id=' . $id . '&land'],
    'items'      => ['label' => HERO_T4_TAB_ITEMS,      'url' => 'build.php?id=' . $id . '&t4tab=items'],
    'adventures' => ['label' => HERO_T4_TAB_ADVENTURES, 'url' => 'build.php?id=' . $id . '&t4tab=adventures'],
    'auction'    => ['label' => HERO_T4_TAB_AUCTION,    'url' => 'build.php?id=' . $id . '&t4tab=auction'],
];
$t4NavRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
// Tab spacing and the silver counter's float side are written inline
// (no shared class) so we mirror them here rather than in CSS: in
// RTL the tab row reads right-to-left, so the gap moves to the start
// side of each tab (margin-left) and the counter floats to the
// opposite edge (left) instead of the far edge (right).
$t4NavGapStyle    = $t4NavRtl ? 'margin-left:14px;'  : 'margin-right:14px;';
$t4NavGapStyleB   = $t4NavRtl ? 'font-weight:bold;margin-left:14px;' : 'font-weight:bold;margin-right:14px;';
$t4NavFloatStyle  = $t4NavRtl ? 'float:left;' : 'float:right;';
?>
<link rel="stylesheet" href="css/hero_items.css" type="text/css">
<div class="heroT4Nav" style="margin:6px 0 10px 0;">
    <?php foreach ($t4Tabs as $key => $tab) { ?>
        <?php if ($key === $t4tab) { ?>
            <span style="<?php echo $t4NavGapStyleB; ?>"><?php echo $tab['label']; ?></span>
        <?php } else { ?>
            <a href="<?php echo $tab['url']; ?>" style="<?php echo $t4NavGapStyle; ?>"><?php echo $tab['label']; ?></a>
        <?php } ?>
    <?php } ?>
    <span style="<?php echo $t4NavFloatStyle; ?>"><b><?php echo HERO_SILVER; ?>:</b> <?php echo number_format($t4Silver); ?></span>
    <div style="clear:both;"></div>
</div>
