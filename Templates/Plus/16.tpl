<?php

#################################################################################
##  Filename       : 16.tpl                                                    ##
##  Type           : Plus - Transfer Gold                                      ##
#################################################################################

include("Templates/Plus/pmenu.tpl");

$plusRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
$t = function ($ar, $en) use ($plusRtl) {
    return $plusRtl ? $ar : $en;
};

$centralOn = class_exists('CentralGold') && CentralGold::isConfigured();
$fromEmail = trim((string) ($session->userinfo['email'] ?? $session->email ?? ''));
$paidBalance = 0;
$localBalance = (int) ($session->gold ?? 0);

if ($centralOn && $fromEmail !== '') {
    $paidBalance = (int) CentralGold::balance($fromEmail);
}

if (empty($_SESSION['plus_csrf'])) {
    $_SESSION['plus_csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['plus_csrf'];
?>

<p><?php echo $t(
    'حوّل الذهب إلى لاعب آخر باستخدام اسم المستخدم. الذهب المدفوع يُحوَّل عبر الرصيد المركزي عند تفعيله؛ وإلا يُحوَّل الذهب المحلي لهذا العالم.',
    'Transfer gold to another player by username. Paid gold moves through the central balance when enabled; otherwise local world gold is transferred.'
); ?></p>

<table class="rate_details" cellpadding="1" cellspacing="1">
    <thead>
        <tr><th colspan="2"><?php echo $t('رصيدك', 'Your balance'); ?></th></tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo $t('الذهب المحلي', 'Local gold'); ?></td>
            <td><img src="img/x.gif" class="gold" alt="" /> <b><?php echo $localBalance; ?></b></td>
        </tr>
        <?php if ($centralOn): ?>
        <tr>
            <td><?php echo $t('الذهب المدفوع (مركزي)', 'Paid gold (central)'); ?></td>
            <td><img src="img/x.gif" class="gold" alt="" /> <b><?php echo $paidBalance; ?></b></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php if (!empty($transferMsg)): ?>
<p class="<?php echo !empty($transferOk) ? 'info' : 'error'; ?>" style="margin:10px 0;<?php echo !empty($transferOk) ? 'color:#228B22;' : 'color:#c00;'; ?>">
    <?php echo htmlspecialchars($transferMsg, ENT_QUOTES, 'UTF-8'); ?>
</p>
<?php endif; ?>

<form method="post" action="plus.php?id=16" style="margin-top:12px;">
    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>" />
    <table class="rate_details" cellpadding="1" cellspacing="1">
        <thead>
            <tr><th colspan="2"><?php echo $t('تحويل الذهب', 'Transfer gold'); ?></th></tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $t('اسم المستخدم المستلم', 'Recipient username'); ?></td>
                <td><input type="text" name="to_username" maxlength="45" required value="<?php echo htmlspecialchars($_POST['to_username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" /></td>
            </tr>
            <tr>
                <td><?php echo $t('الكمية', 'Amount'); ?></td>
                <td><input type="number" name="amount" min="1" step="1" required value="<?php echo (int) ($_POST['amount'] ?? 1); ?>" /></td>
            </tr>
            <tr>
                <td colspan="2">
                    <label>
                        <input type="checkbox" name="confirm_transfer" value="1" required />
                        <?php echo $t('أؤكد أن التحويل نهائي وغير قابل للإلغاء', 'I confirm this transfer is final and cannot be undone'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;">
                    <button type="submit" name="transfer_gold" value="1"><?php echo $t('تحويل', 'Transfer'); ?></button>
                </td>
            </tr>
        </tbody>
    </table>
</form>
</div>
