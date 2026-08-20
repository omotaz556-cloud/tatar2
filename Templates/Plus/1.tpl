<?php

#################################################################################
##                -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-               ##
## --------------------------------------------------------------------------- ##
##  Filename       : 1.tpl                                                     ##
##  Type           : Plus Overview - Landing Page                              ##
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

// Novaterra - DO NOT REMOVE COPYRIGHT NOTICE!
include("Templates/Plus/pmenu.tpl");

$packages = [
    ['id' => 110,  'key' => 'A', 'gold' => defined('PLUS_PACKAGE_A_GOLD') ? PLUS_PACKAGE_A_GOLD : 60,   'price' => defined('PLUS_PACKAGE_A_PRICE') ? PLUS_PACKAGE_A_PRICE : '1,99'],
    ['id' => 111,  'key' => 'B', 'gold' => defined('PLUS_PACKAGE_B_GOLD') ? PLUS_PACKAGE_B_GOLD : 120,  'price' => defined('PLUS_PACKAGE_B_PRICE') ? PLUS_PACKAGE_B_PRICE : '4,99'],
    ['id' => 112,  'key' => 'C', 'gold' => defined('PLUS_PACKAGE_C_GOLD') ? PLUS_PACKAGE_C_GOLD : 360,  'price' => defined('PLUS_PACKAGE_C_PRICE') ? PLUS_PACKAGE_C_PRICE : '9,99'],
    ['id' => 113,  'key' => 'D', 'gold' => defined('PLUS_PACKAGE_D_GOLD') ? PLUS_PACKAGE_D_GOLD : 1000, 'price' => defined('PLUS_PACKAGE_D_PRICE') ? PLUS_PACKAGE_D_PRICE : '19,99'],
    ['id' => 3110, 'key' => 'E', 'gold' => defined('PLUS_PACKAGE_E_GOLD') ? PLUS_PACKAGE_E_GOLD : 2000, 'price' => defined('PLUS_PACKAGE_E_PRICE') ? PLUS_PACKAGE_E_PRICE : '49,99'],
];

$currency = defined('PAYPAL_CURRENCY') ? PAYPAL_CURRENCY : 'EUR';
$payEmail = (defined('PAYPAL_EMAIL') && PAYPAL_EMAIL !== '@') ? PAYPAL_EMAIL : ADMIN_EMAIL;
$plusRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
$packageLabel = $plusRtl ? 'الباقة' : 'Package';
$goldLabel = $plusRtl ? 'ذهب' : 'Gold';
$buyLabel = $plusRtl ? 'شراء' : 'Buy';
?>
<table class="rate_details <?php echo $plusRtl ? 'lang_rtl' : 'lang_ltr'; ?>" cellpadding="1" cellspacing="1">
	<thead><tr><th colspan="2"><?php echo GOLD_SHOP; ?></th></tr></thead>
	<tbody>
		<tr>
			<td class="pic"><img src="img/bezahlung/payment_generic.png" style="width:99px;height:99px;" alt="<?php echo GOLD_SHOP; ?>" /><div><?php echo GOLD_SHOP; ?></div></td>
			<td class="desc">
				<?php echo TZ_ML_GOLD_RESERVE; ?> 
				<a href="mailto:<?= $payEmail ?>"><?php echo TZ_PAYMENT_ACCOUNT; ?></a>.<br><br>
				<b><?php echo TZ_USERNAME; ?><br><?php echo PAYMENT_METHOD; ?><br><?php echo TZ_ORDERED_PACKAGE; ?><br><?php echo TZ_DATE_AND_TIME; ?></b><br><br>
				<?php echo TZ_WE_STRIVE_TO_ENSURE_SPEEDY_PROCESS; ?>
			</td>
		</tr>
	</tbody>
</table>

<div id="products">
<?php foreach($packages as $p): ?>
    <table class="product <?php echo $plusRtl ? 'lang_rtl' : 'lang_ltr'; ?>" cellpadding="1" cellspacing="1">
        <thead><tr><th><?= $packageLabel ?> <?= $p['key'] ?></th></tr></thead>
        <tbody>
            <tr><td class="pic"><a href="plus1.php?id=<?= $p['id'] ?>"><img src="img/bezahlung/payment_generic.png" style="width:99px;height:99px;" alt="<?= $packageLabel ?> <?= $p['key'] ?>" /></a></td></tr>
            <tr><td><?= $p['gold'] ?>&nbsp;<?= $goldLabel ?></td></tr>
            <tr><td><?= $p['price'] ?>&nbsp;<?= $currency ?></td></tr>
            <tr><td><a href="plus1.php?id=<?= $p['id'] ?>" onclick="if(this.dataset.c) return false; this.dataset.c=1;">&raquo; <?= $buyLabel ?></a></td></tr>
        </tbody>
    </table>
<?php endforeach; ?>
<div class="clear"></div>
<div style="padding:10px;font-style:italic;font-size:10px;color:#F00;"><b><?php echo TZ_NONE_OF_THE_PACKAGES_ARE_REFUNDABL; ?></b></div>
</div>
</div>
