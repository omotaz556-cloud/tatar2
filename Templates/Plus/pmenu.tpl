<?php

#################################################################################
##                -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-               ##
## --------------------------------------------------------------------------- ##
##  Filename       : pmenu.tpl                                                 ##
##  Type           : Plus - Navigation Menu                                    ##
## --------------------------------------------------------------------------- ##
#################################################################################

?>
<?php $plusRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang(); ?>
<div id="content" class="plus<?php echo $plusRtl ? ' lang_rtl' : ' lang_ltr'; ?>" dir="<?php echo $plusRtl ? 'rtl' : 'ltr'; ?>">
<?php
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$uri = basename(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '');

function sel($cond) {
    return $cond ? 'class="selected"' : '';
}

$isBuyGold = ($uri === 'plus.php' || $uri === '') && ($id === 0 || $id === 1 || $id >= 100);
$isActivate = ($id === 3 || ($id >= 6 && $id <= 15));
$isEarn = ($id === 5);
$isTransfer = ($id === 16);
$isPurchases = ($id === 17 || $uri === 'a2b2.php');
$isTroopGold = ($id === 18);

$plusTitleBuy = $plusRtl
    ? ('شراء <font color="#FF6F0F">الذهب</font>')
    : ('Buy <font color="#FF6F0F">Gold</font>');
$plusTitleActivate = $plusRtl ? 'تفعيل البلاس' : 'Activate Plus';
$plusTitleEarn = $plusRtl ? 'كسب الذهب' : 'Earn Gold';
$plusTitleTransfer = $plusRtl ? 'تحويل الذهب' : 'Transfer Gold';
$plusTitlePurchases = $plusRtl ? 'العمليات الشرائية' : 'Purchase Transactions';
$plusTitleTroops = $plusRtl ? 'تكاليف الجنود بالذهب' : 'Troop Costs in Gold';

if ($isActivate) {
    $plusPageTitle = $plusTitleActivate;
} elseif ($isEarn) {
    $plusPageTitle = $plusTitleEarn;
} elseif ($isTransfer) {
    $plusPageTitle = $plusTitleTransfer;
} elseif ($isPurchases) {
    $plusPageTitle = $plusTitlePurchases;
} elseif ($isTroopGold) {
    $plusPageTitle = $plusTitleTroops;
} else {
    $plusPageTitle = $plusTitleBuy;
}
?>
<h1><?php echo $plusPageTitle; ?></h1>
<div id="textmenu">
   <a href="plus.php" <?= sel($isBuyGold) ?>><?php echo $plusRtl ? 'شراء الذهب' : 'Buy Gold'; ?></a>
 | <a href="plus.php?id=3" <?= sel($isActivate) ?>><?php echo $plusRtl ? 'تفعيل البلاس' : 'Activate Plus'; ?></a>
 | <a href="plus.php?id=5" <?= sel($isEarn) ?>><?php echo $plusRtl ? 'كسب الذهب' : 'Earn Gold'; ?></a>
 | <a href="plus.php?id=16" <?= sel($isTransfer) ?>><?php echo $plusRtl ? 'تحويل الذهب' : 'Transfer Gold'; ?></a>
 | <a href="plus.php?id=17" <?= sel($isPurchases) ?>><?php echo $plusRtl ? 'العمليات الشرائية' : 'Purchase Transactions'; ?></a>
 | <a href="plus.php?id=18" <?= sel($isTroopGold) ?>><?php echo $plusRtl ? 'تكاليف الجنود بالذهب' : 'Troop Costs in Gold'; ?></a>
</div>
