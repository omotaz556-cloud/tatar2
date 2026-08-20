<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : MARKETPLACE GOLD -> RESOURCES PURCHASE                    ##
##  Type           : BUILDING TEMPLATE                                         ##
## --------------------------------------------------------------------------- ##
##  Project        : Novaterra                                                  ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : Novaterra Project                                          ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.              ##
## --------------------------------------------------------------------------- ##
#################################################################################

global $database, $session, $village, $id;

// Item 10 (Feature Flags): admin-controlled at runtime; GOLD_RES_PURCHASE_ENABLED
// remains the fallback default (see GameEngine/Market.php for the same check
// on the actual purchase action). seed() makes the flag show up on the admin
// Feature Flags page the first time this page is visited, pre-set to whatever
// config.php currently says, without ever overwriting an admin's later choice.
if (class_exists('FeatureFlags')) {
    FeatureFlags::seed('gold_res_purchase',
        defined('GOLD_RES_PURCHASE_ENABLED') && GOLD_RES_PURCHASE_ENABLED,
        'Gold -> Resources purchase',
        'Marketplace: convert gold into wood/clay/iron/crop (item 8).');
}

$goldResPurchaseOn = class_exists('FeatureFlags')
    ? FeatureFlags::isEnabled('gold_res_purchase',
        defined('GOLD_RES_PURCHASE_ENABLED') && GOLD_RES_PURCHASE_ENABLED)
    : (defined('GOLD_RES_PURCHASE_ENABLED') && GOLD_RES_PURCHASE_ENABLED);

if (!$goldResPurchaseOn) {
    header("Location: build.php?id=" . (int) $_GET['id']);
    exit;
}

if ($session->gold <= 0) {
    header("Location: build.php?id=" . (int) $_GET['id']);
    exit;
}

$level = (int) $village->resarray['f' . $id];

$unit    = defined('GOLD_RES_UNIT') ? max(1, (int) GOLD_RES_UNIT) : 100;
$minGold = defined('GOLD_RES_MIN_GOLD') ? max(1, (int) GOLD_RES_MIN_GOLD) : 1;
$maxGold = defined('GOLD_RES_MAX_GOLD') ? max(0, (int) GOLD_RES_MAX_GOLD) : 0;

$wwvillage = $database->getResourceLevel($village->wid);
$isWW      = ($wwvillage['f99t'] == 40);

$completed = isset($_GET['c']);
$error     = isset($_GET['e']) ? (string) $_GET['e'] : '';

$maxstore = max(0, (int) $village->maxstore);
$maxcrop  = max(0, (int) $village->maxcrop);
?>
<div id="build" class="gid17">
    <a href="#" onClick="return Popup(17,4);" class="build_logo">
        <img class="building g17" src="img/x.gif" alt="<?php echo MARKETPLACE;?>" title="<?php echo MARKETPLACE;?>" />
    </a>
    <h1><?php echo MARKETPLACE;?> <span class="level"><?php echo LEVEL;?> <?php echo $level;?></span></h1>
    <p class="build_desc"><?php echo MARKETPLACE_DESC;?></p>

    <?php include("17_menu.tpl");?>

    <?php if ($completed):?>
        <p><b><?php echo GOLD_BUY_COMPLETED;?>.</b></p>
        <a href="javascript: history.go(-2)"><?php echo BACK_BUILDING;?></a>

    <?php elseif ($isWW):?>
        <br /><br /><?php echo YOU_CAN_NAT_NPC_WW;?>

    <?php else:?>
        <p><?php echo GOLD_BUY_RESOURCES_DESC;?></p>

        <?php if ($error === 'gold'):?>
            <p style="color:#ff0000;"><?php echo GOLD_BUY_ERR_GOLD;?></p>
        <?php elseif ($error === 'amount'):?>
            <p style="color:#ff0000;"><?php echo GOLD_BUY_ERR_AMOUNT;?></p>
        <?php elseif ($error === 'full'):?>
            <p style="color:#ff0000;"><?php echo GOLD_BUY_ERR_FULL;?></p>
        <?php elseif ($error === 'disabled'):?>
            <p style="color:#ff0000;"><?php echo GOLD_BUY_ERR_DISABLED;?></p>
        <?php endif;?>

        <form method="post" name="goldbuy" action="build.php">
            <input type="hidden" name="id" value="<?php echo (int) $id;?>" />
            <input type="hidden" name="ft" value="mk4" />
            <input type="hidden" name="t" value="5" />

            <table id="goldbuy" cellpadding="1" cellspacing="1">
                <thead>
                    <tr><th colspan="2"><?php echo GOLD_BUY_RESOURCES;?></th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo GOLD_BUY_CHOOSE_RESOURCE;?></td>
                        <td>
                            <select name="restype">
                                <option value="mix"><?php echo GOLD_BUY_MIX;?></option>
                                <option value="w"><?php echo LUMBER;?></option>
                                <option value="c"><?php echo CLAY;?></option>
                                <option value="i"><?php echo IRON;?></option>
                                <option value="r"><?php echo CROP;?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo GOLD_BUY_HOW_MUCH_GOLD;?></td>
                        <td>
                            <input type="number" class="text" name="goldamt"
                                   min="<?php echo $minGold;?>"
                                   <?php if ($maxGold > 0):?>max="<?php echo min($maxGold, (int) $session->gold);?>"<?php else:?>max="<?php echo (int) $session->gold;?>"<?php endif;?>
                                   value="<?php echo $minGold;?>" />
                            <span class="none">
                                (<?php echo AVAILABLE;?>: <img src="img/x.gif" class="gold_g" alt="<?php echo GOLD;?>" title="<?php echo GOLD;?>" /><b><?php echo (int) $session->gold;?></b>)
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php echo GOLD_BUY_RATE_NOTE_PREFIX;?>
                            <b>1</b><img src="img/x.gif" class="gold_g" alt="<?php echo GOLD;?>" title="<?php echo GOLD;?>" />
                            = <b><?php echo $unit;?></b> <?php echo GOLD_BUY_RATE_NOTE_SUFFIX;?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p id="submitButton">
                <a href="javascript:document.goldbuy.submit();"><?php echo GOLD_BUY_RESOURCES;?></a>
            </p>
        </form>

    <?php endif;?>
</div>
