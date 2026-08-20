<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : MARKETPLACE MENU                                          ##
##  Type           : BUILDING TEMPLATE                                         ##
## --------------------------------------------------------------------------- ##
##  Refactored by  : Shadow                                                    ##
##  Redesign by    : Shadow                                                    ##
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

global $database, $session, $id;

$t = isset($_GET['t'])? (int)$_GET['t'] : 0;
$hasGold = (int)$session->userinfo['gold'] > 2;
$hasRoutes = $session->goldclub == 1 && count($database->getProfileVillages($session->uid)) > 1;
?>
<div id="textmenu">
    <a href="build.php?id=<?php echo (int)$id;?>" <?php if($t===0) echo 'class="selected"';?>><?php echo SEND_RESOURCES;?></a>
    | <a href="build.php?id=<?php echo (int)$id;?>&amp;t=1" <?php if($t===1) echo 'class="selected"';?>><?php echo BUY;?></a>
    | <a href="build.php?id=<?php echo (int)$id;?>&amp;t=2" <?php if($t===2) echo 'class="selected"';?>><?php echo OFFER;?></a>
    <?php if ($hasGold):?>
    | <a href="build.php?id=<?php echo (int)$id;?>&amp;t=3" <?php if($t===3) echo 'class="selected"';?>><?php echo NPC_TRADING;?></a>
    <?php endif;?>
    <?php if ($hasRoutes):?>
    | <a href="build.php?id=<?php echo (int)$id;?>&amp;t=4" <?php if($t===4) echo 'class="selected"';?>><?php echo TRADE_ROUTES;?></a>
    <?php endif;?>
    <?php
    // Item 10 (Feature Flags): admin-controlled at runtime; GOLD_RES_PURCHASE_ENABLED
    // remains the fallback default.
    $goldResPurchaseOn = class_exists('FeatureFlags')
        ? FeatureFlags::isEnabled('gold_res_purchase',
            defined('GOLD_RES_PURCHASE_ENABLED') && GOLD_RES_PURCHASE_ENABLED)
        : (defined('GOLD_RES_PURCHASE_ENABLED') && GOLD_RES_PURCHASE_ENABLED);
    ?>
    <?php if ($goldResPurchaseOn && $hasGold):?>
    | <a href="build.php?id=<?php echo (int)$id;?>&amp;t=5" <?php if($t===5) echo 'class="selected"';?>><?php echo GOLD_BUY_RESOURCES;?></a>
    <?php endif;?>
</div>