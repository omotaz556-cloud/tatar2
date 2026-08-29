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

$packageIds = ['A' => 110, 'B' => 111, 'C' => 112, 'D' => 113, 'E' => 3110];
$packages = [];
foreach (PaymentShop::packages() as $key => $package) {
    $packages[] = ['id' => $packageIds[$key], 'key' => $key, 'gold' => $package['gold'], 'price' => number_format($package['amount'], 2, ',', '.')];
}

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
            <tr><td class="pic"><a href="myfatoorah.php?package=<?= urlencode($p['key']) ?>"><img src="img/bezahlung/payment_generic.png" style="width:99px;height:99px;" alt="<?= $packageLabel ?> <?= $p['key'] ?>" /></a></td></tr>
            <tr><td><?= $p['gold'] ?>&nbsp;<?= $goldLabel ?></td></tr>
            <tr><td><?= $p['price'] ?>&nbsp;<?= $currency ?></td></tr>
            <tr><td><a href="myfatoorah.php?package=<?= urlencode($p['key']) ?>" onclick="if(this.dataset.c) return false; this.dataset.c=1;">&raquo; <?= $buyLabel ?></a></td></tr>
        </tbody>
    </table>
<?php endforeach; ?>
<div class="clear"></div>
<div style="padding:10px;font-style:italic;font-size:10px;color:#F00;"><b><?php echo TZ_NONE_OF_THE_PACKAGES_ARE_REFUNDABL; ?></b></div>
</div>

<?php if (!empty($purchaseMsg)) echo '<p class="error">' . htmlspecialchars($purchaseMsg, ENT_QUOTES, 'UTF-8') . '</p>'; ?>
<?php if (isset($session->uid)): $history = PaymentShop::history($session->uid); ?>
<table class="rate_details <?php echo $plusRtl ? 'lang_rtl' : 'lang_ltr'; ?>" cellpadding="1" cellspacing="1">
    <thead><tr><th colspan="5"><?php echo $plusRtl ? 'سجل مشتريات اللاعب' : 'Player purchase history'; ?></th></tr></thead>
    <tbody>
    <?php if (!$history): ?><tr><td colspan="5"><?php echo $plusRtl ? 'لا توجد مشتريات بعد.' : 'No purchases yet.'; ?></td></tr><?php endif; ?>
    <?php foreach ($history as $purchase): ?>
    <tr>
        <td><?php echo htmlspecialchars($purchase['package_key'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo (int) $purchase['gold']; ?> <?php echo $goldLabel; ?></td>
        <td><?php echo htmlspecialchars($purchase['amount'] . ' ' . $purchase['currency'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($purchase['status'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php if ($purchase['status'] === 'paid'): ?><form method="post"><input type="hidden" name="refund_purchase" value="<?php echo (int) $purchase['id']; ?>"><input type="text" name="refund_reason" maxlength="255" placeholder="<?php echo $plusRtl ? 'سبب الاسترداد' : 'Refund reason'; ?>"><button type="submit"><?php echo $plusRtl ? 'طلب استرداد' : 'Request refund'; ?></button></form><?php endif; ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
