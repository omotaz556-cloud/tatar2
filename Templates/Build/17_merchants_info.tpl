<?php

#################################################################################
##  MARKET — merchant count (current level / next level)                       ##
#################################################################################

global $village, $building, $bid17, $id;

if (!isset($bid17)) {
    return;
}

if (!isset($loopsame)) {
    include 'next.tpl';
}

$level = (int) $village->resarray['f' . $id];
$buildingType = (int) ($village->resarray['f' . $id . 't'] ?? 0);
$currentMerchants = $level > 0 ? (int) $bid17[$level]['attri'] : 0;
$marketIsMax = $building->isMax($buildingType, $id);
$nextMarketLevel = min($level + 1 + $loopsame + $doublebuild + $master, 20);
$nextMerchants = (!$marketIsMax && isset($bid17[$nextMarketLevel]))
    ? (int) $bid17[$nextMarketLevel]['attri']
    : null;

$merchantsNowLbl = defined('MERCHANTS_COUNT_NOW') ? MERCHANTS_COUNT_NOW : 'عدد التجار الآن:';
$merchantsLevelLbl = defined('MERCHANTS_COUNT_AT_LEVEL') ? MERCHANTS_COUNT_AT_LEVEL : 'عدد التجار في المستوى';
$merchantUnit = defined('MERCHANT_ONE') ? MERCHANT_ONE : 'تاجر';
?>
<div class="gk-market-merchants">
    <div class="gk-market-merchants-line">
        <span class="gk-market-merchants-label"><?php echo $merchantsNowLbl; ?></span>
        <span class="gk-market-merchants-val"><?php echo $currentMerchants; ?> <?php echo $merchantUnit; ?></span>
    </div>
    <?php if ($nextMerchants !== null) { ?>
    <div class="gk-market-merchants-line">
        <span class="gk-market-merchants-label"><?php echo $merchantsLevelLbl; ?> <?php echo (int) $nextMarketLevel; ?>:</span>
        <span class="gk-market-merchants-val"><?php echo $nextMerchants; ?> <?php echo $merchantUnit; ?></span>
    </div>
    <?php } ?>
</div>
