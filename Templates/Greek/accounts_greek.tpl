<?php
/**
 * Greek.sa linked sub-accounts page (feeding.php) — الحسابات.
 *
 * Expects: $fsCanAddMore, $fsSettings, $feedingMsg, $feedingOk, $gkPostedUser, $gkPostedPw
 */

$gkMaxLinked = (int) ($fsSettings['max_linked_per_player'] ?? 3);
if ($gkMaxLinked < 1) {
    $gkMaxLinked = 3;
}

$gkLblIntro = defined('TZ_GK_ACC_INTRO')
    ? sprintf(TZ_GK_ACC_INTRO, $gkMaxLinked)
    : 'العضويات التابعة لك هي عضويات تملكها أنت وليست عضويات لاعبين آخرين ، يسمح للمستخدم بامتلاك '
        . $gkMaxLinked . ' عضويات كحد أقصى، من هنا يمكنك تسجيل عضوياتك وذلك لتستثنى من منع إرسال الموارد عبر العضويات على نفس الانترنت';
$gkLblAddTitle = defined('TZ_GK_ACC_ADD_TITLE') ? TZ_GK_ACC_ADD_TITLE : 'إضافة عضوية تابعة';
$gkLblOtherName = defined('TZ_GK_ACC_OTHER_NAME') ? TZ_GK_ACC_OTHER_NAME : 'اسم العضوية الأخرى';
$gkLblPassword = defined('TZ_GK_ACC_PASSWORD') ? TZ_GK_ACC_PASSWORD : 'كلمة المرور';
$gkLblLinkBtn = defined('TZ_GK_ACC_LINK_BTN') ? TZ_GK_ACC_LINK_BTN : 'ربط العضوية';
$gkLblWarning = defined('TZ_GK_ACC_WARNING') ? TZ_GK_ACC_WARNING : '* لايمكن إلغاء أو تغيير العضويات التابعة لك ، لذلك إحرص على اختيار العضويات بشكل صحيح';
$gkLblDisabled = defined('FS_PLAYER_DISABLED_NOTICE') ? FS_PLAYER_DISABLED_NOTICE : 'هذه الميزة معطّلة حاليًا من قِبل أدمن الخادم.';

$gkPostedUser = isset($gkPostedUser) ? (string) $gkPostedUser : '';
$gkPostedPw = isset($gkPostedPw) ? (string) $gkPostedPw : '';
?>
<p class="gk-acc-intro"><?php echo htmlspecialchars($gkLblIntro, ENT_QUOTES, 'UTF-8'); ?></p>

<?php if ($feedingMsg !== '') { ?>
<p class="gk-acc-flash<?php echo $feedingOk ? ' ok' : ' err'; ?>"><?php echo htmlspecialchars($feedingMsg, ENT_QUOTES, 'UTF-8'); ?></p>
<?php } ?>

<?php if (empty($fsSettings['enabled'])) { ?>
<p class="gk-acc-disabled"><?php echo htmlspecialchars($gkLblDisabled, ENT_QUOTES, 'UTF-8'); ?></p>
<?php } else { ?>
<form action="feeding.php" method="post" class="gk-accounts-form">
<input type="hidden" name="gk_accounts_link" value="1" />

<table cellpadding="1" cellspacing="1" id="linked_accounts" class="gk-acc-table gk-accounts-table">
    <colgroup>
        <col class="gk-acc-col-label" />
        <col class="gk-acc-col-field" />
    </colgroup>
    <thead>
        <tr>
            <th colspan="2" class="rbg"><?php echo htmlspecialchars($gkLblAddTitle, ENT_QUOTES, 'UTF-8'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th><?php echo htmlspecialchars($gkLblOtherName, ENT_QUOTES, 'UTF-8'); ?></th>
            <td>
                <input class="text" type="text" name="fs_add_username" value="<?php echo htmlspecialchars($gkPostedUser, ENT_QUOTES, 'UTF-8'); ?>" maxlength="100" autocomplete="off"<?php echo $fsCanAddMore ? '' : ' disabled="disabled"'; ?> required="required" />
            </td>
        </tr>
        <tr>
            <th><?php echo htmlspecialchars($gkLblPassword, ENT_QUOTES, 'UTF-8'); ?></th>
            <td>
                <input class="text" type="password" name="fs_add_password" value="" maxlength="100" autocomplete="off"<?php echo $fsCanAddMore ? '' : ' disabled="disabled"'; ?> required="required" />
            </td>
        </tr>
        <tr class="gk-acc-link-row">
            <td colspan="2">
                <button type="submit" class="gk-acc-link-btn"<?php echo $fsCanAddMore ? '' : ' disabled="disabled"'; ?>><?php echo htmlspecialchars($gkLblLinkBtn, ENT_QUOTES, 'UTF-8'); ?></button>
            </td>
        </tr>
    </tbody>
</table>
</form>
<?php } ?>

<p class="gk-acc-warning"><?php echo htmlspecialchars($gkLblWarning, ENT_QUOTES, 'UTF-8'); ?></p>
