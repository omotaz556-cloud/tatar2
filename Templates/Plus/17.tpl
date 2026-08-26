<?php

#################################################################################
##  Filename       : 17.tpl                                                    ##
##  Type           : Plus - Purchase Transactions                              ##
#################################################################################

include("Templates/Plus/pmenu.tpl");

$plusRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
$t = function ($ar, $en) use ($plusRtl) {
    return $plusRtl ? $ar : $en;
};

$history = class_exists('PaymentShop') ? PaymentShop::history((int) $session->uid, 50) : [];
$statusMap = [
    'pending' => $t('قيد الانتظار', 'Pending'),
    'paid' => $t('مدفوع', 'Paid'),
    'refund_requested' => $t('طلب استرداد', 'Refund requested'),
    'refunded' => $t('مسترد', 'Refunded'),
    'failed' => $t('فشل', 'Failed'),
];
?>

<p><?php echo $t(
    'سجل عمليات شراء باقات الذهب عبر بوابة الدفع. يمكنك طلب استرداد للعمليات المدفوعة.',
    'History of gold package purchases via the payment gateway. You can request a refund for paid orders.'
); ?></p>

<?php if (!empty($purchaseMsg)): ?>
<p class="<?php echo !empty($purchaseOk) ? 'info' : 'error'; ?>" style="margin:10px 0;<?php echo !empty($purchaseOk) ? 'color:#228B22;' : 'color:#c00;'; ?>">
    <?php echo htmlspecialchars($purchaseMsg, ENT_QUOTES, 'UTF-8'); ?>
</p>
<?php endif; ?>

<table class="plusFunctions" cellpadding="1" cellspacing="1">
    <thead>
        <tr><th colspan="6"><?php echo $t('العمليات الشرائية', 'Purchase transactions'); ?></th></tr>
        <tr>
            <td><?php echo $t('التاريخ', 'Date'); ?></td>
            <td><?php echo $t('الباقة', 'Package'); ?></td>
            <td><?php echo GOLD; ?></td>
            <td><?php echo $t('المبلغ', 'Amount'); ?></td>
            <td><?php echo $t('الحالة', 'Status'); ?></td>
            <td><?php echo $t('إجراء', 'Action'); ?></td>
        </tr>
    </thead>
    <tbody>
    <?php if (!$history): ?>
        <tr><td colspan="6" style="text-align:center;padding:10px;"><?php echo $t('لا توجد عمليات شراء بعد.', 'No purchases yet.'); ?></td></tr>
    <?php else: ?>
        <?php foreach ($history as $purchase): ?>
            <?php
            $status = (string) ($purchase['status'] ?? '');
            $statusLabel = $statusMap[$status] ?? $status;
            $created = !empty($purchase['created']) ? date('d.m.Y H:i', (int) $purchase['created']) : '-';
            $paidAt = !empty($purchase['paid_at']) ? date('d.m.Y H:i', (int) $purchase['paid_at']) : $created;
            ?>
            <tr>
                <td style="text-align:center;"><?php echo htmlspecialchars($paidAt, ENT_QUOTES, 'UTF-8'); ?></td>
                <td style="text-align:center;"><?php echo htmlspecialchars((string) $purchase['package_key'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td style="text-align:center;"><?php echo (int) $purchase['gold']; ?></td>
                <td style="text-align:center;"><?php echo htmlspecialchars($purchase['amount'] . ' ' . $purchase['currency'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td style="text-align:center;"><?php echo htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8'); ?></td>
                <td style="text-align:center;">
                    <?php if ($status === 'paid'): ?>
                        <form method="post" action="plus.php?id=17" style="margin:0;">
                            <input type="hidden" name="refund_purchase" value="<?php echo (int) $purchase['id']; ?>" />
                            <input type="text" name="refund_reason" maxlength="255" placeholder="<?php echo $t('سبب الاسترداد', 'Refund reason'); ?>" />
                            <button type="submit"><?php echo $t('طلب استرداد', 'Request refund'); ?></button>
                        </form>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

<p style="margin-top:12px;">
    <a href="plus.php">&raquo; <?php echo $t('العودة لشراء الذهب', 'Back to Buy Gold'); ?></a>
    &nbsp;|&nbsp;
    <a href="a2b2.php">&raquo; <?php echo $t('كشف حساب الذهب (الاستخدام داخل اللعبة)', 'Gold usage statement'); ?></a>
</p>
</div>
