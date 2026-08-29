<?php
/**
 * Greek.sa membership page (spieler.php?s=3) — العضوية.
 */

$pwError = $form->getError('pw');
$emailError = $form->getError('email');
$sitterError = $form->getError('sit');
$deleteError = $form->getError('del');
$saveError = $form->getError('save');

$gkPosted = (!empty($form->valuearray) && is_array($form->valuearray)) ? $form->valuearray : array();
$gkEmailNeu = htmlspecialchars((string) ($gkPosted['email_neu'] ?? ''), ENT_QUOTES, 'UTF-8');
$gkV1 = htmlspecialchars((string) ($gkPosted['v1'] ?? ''), ENT_QUOTES, 'UTF-8');
$gkV2 = htmlspecialchars((string) ($gkPosted['v2'] ?? ''), ENT_QUOTES, 'UTF-8');
$gkV5On = array_key_exists('v5', $gkPosted) ? !empty($gkPosted['v5']) : !empty($session->userinfo['v5']);
$gkV6On = array_key_exists('v6', $gkPosted) ? !empty($gkPosted['v6']) : !empty($session->userinfo['v6']);

$gkLblSettings = defined('TZ_GK_MEMBERSHIP_SETTINGS') ? TZ_GK_MEMBERSHIP_SETTINGS : 'تغيير إعدادات العضوية';
$gkLblChangeType = defined('TZ_GK_MEMBERSHIP_CHANGE_TYPE') ? TZ_GK_MEMBERSHIP_CHANGE_TYPE : 'نوع التغيير';
$gkLblNewValue = defined('TZ_GK_MEMBERSHIP_NEW_VALUE') ? TZ_GK_MEMBERSHIP_NEW_VALUE : 'القيمة الجديدة';
$gkLblNewPw = defined('TZ_GK_MEMBERSHIP_NEW_PASSWORD') ? TZ_GK_MEMBERSHIP_NEW_PASSWORD : 'كلمة السر الجديدة';
$gkLblNewEmail = defined('TZ_GK_MEMBERSHIP_NEW_EMAIL') ? TZ_GK_MEMBERSHIP_NEW_EMAIL : 'البريد الإلكتروني الجديد';
$gkLblEmailNote = defined('TZ_GK_MEMBERSHIP_EMAIL_NOTE') ? TZ_GK_MEMBERSHIP_EMAIL_NOTE : '';
$gkLblAgency = defined('TZ_GK_MEMBERSHIP_AGENCY') ? TZ_GK_MEMBERSHIP_AGENCY : 'وكالة العضوية';
$gkLblSit1 = defined('TZ_GK_MEMBERSHIP_SITTER_1') ? TZ_GK_MEMBERSHIP_SITTER_1 : 'الوكيل الأول';
$gkLblSit2 = defined('TZ_GK_MEMBERSHIP_SITTER_2') ? TZ_GK_MEMBERSHIP_SITTER_2 : 'الوكيل الثاني';
$gkLblYouSit = defined('TZ_GK_MEMBERSHIP_YOU_SITTER') ? TZ_GK_MEMBERSHIP_YOU_SITTER : 'أنت وكيل على';
$gkLblOther = defined('TZ_GK_MEMBERSHIP_OTHER') ? TZ_GK_MEMBERSHIP_OTHER : 'إعدادات أخرى';
$gkLblNoSend = defined('TZ_GK_MEMBERSHIP_NO_SEND_REP') ? TZ_GK_MEMBERSHIP_NO_SEND_REP : 'منع تقارير إرسال الموارد';
$gkLblNoRecv = defined('TZ_GK_MEMBERSHIP_NO_RECV_REP') ? TZ_GK_MEMBERSHIP_NO_RECV_REP : 'منع تقارير إستقبال الموارد';
$gkLblCommands = defined('TZ_GK_MEMBERSHIP_COMMANDS') ? TZ_GK_MEMBERSHIP_COMMANDS : 'أوامر العضوية';
$gkLblDelete = defined('TZ_GK_MEMBERSHIP_DELETE') ? TZ_GK_MEMBERSHIP_DELETE : 'حذف العضوية';
$gkLblVacation = defined('TZ_GK_MEMBERSHIP_VACATION') ? TZ_GK_MEMBERSHIP_VACATION : 'تفعيل الإجازة لمدة 48:00:00 ساعة';
$gkLblSavePw = defined('TZ_GK_MEMBERSHIP_SAVE_PW') ? TZ_GK_MEMBERSHIP_SAVE_PW : 'كلمة المرور للحفظ';
$gkLblSitArmy = defined('TZ_GK_MEMBERSHIP_SITTER_ARMY') ? TZ_GK_MEMBERSHIP_SITTER_ARMY : 'الوكيل يمكنه التحكم بالجيش';
$gkLblSitGold = defined('TZ_GK_MEMBERSHIP_SITTER_GOLD') ? TZ_GK_MEMBERSHIP_SITTER_GOLD : 'الوكيل يمكنه التحكم بالذهب';
$gkLblSitMsg = defined('TZ_GK_MEMBERSHIP_SITTER_MSG') ? TZ_GK_MEMBERSHIP_SITTER_MSG : 'الوكيل يمكنه قراءة الرسائل';
$gkLblSitRep = defined('TZ_GK_MEMBERSHIP_SITTER_REP') ? TZ_GK_MEMBERSHIP_SITTER_REP : 'الوكيل يمكنه قراءة التقارير';

$gkGoldWord = defined('GOLD') ? GOLD : 'ذهب';
$gkGoldSrc = htmlspecialchars(GP_LOCATE . 'img/x.gif', ENT_QUOTES, 'UTF-8');

$sitSlots = array(
    1 => array('key' => 'sit1', 'label' => $gkLblSit1, 'field' => 'v1'),
    2 => array('key' => 'sit2', 'label' => $gkLblSit2, 'field' => 'v2'),
);

$gkRenderSitterCol = static function ($slot, $slotInfo) use ($session, $database, $gkLblSitArmy, $gkLblSitGold, $gkLblSitMsg, $gkLblSitRep, $gkPosted, $gkV1, $gkV2) {
    $key = $slotInfo['key'];
    $uid = (int) ($session->userinfo[$key] ?? 0);
    $permMask = isset($session->userinfo[$key . '_perm'])
        ? (int) $session->userinfo[$key . '_perm']
        : SITTER_PERM_ALL;

    $armyOn = ($permMask & (SITTER_PERM_ATTACK | SITTER_PERM_RAID | SITTER_PERM_REINF)) !== 0;
    $goldOn = ($permMask & SITTER_PERM_GOLD) !== 0;
    if (!empty($gkPosted['gk_perm' . $slot . '_sent'])) {
        $armyOn = !empty($gkPosted['gk_army_' . $slot]);
        $goldOn = !empty($gkPosted['gk_gold_' . $slot]);
    }

    echo '<div class="gk-acc-sitter-col">';
    echo '<div class="gk-acc-sitter-col-title">' . htmlspecialchars($slotInfo['label'], ENT_QUOTES, 'UTF-8') . '</div>';

    if ($uid > 0) {
        $uname = $database->getUserField($uid, 'username', 0);
        echo '<div class="gk-acc-sitter-name-row">';
        echo '<a href="spieler.php?s=3&amp;e=3&amp;id=' . $uid . '&amp;a=' . (int) $session->checker . '&amp;type=' . $slot . '">';
        echo '<img class="del" src="img/x.gif" alt="" title="' . htmlspecialchars(TZ_REMOVE_SITTER, ENT_QUOTES, 'UTF-8') . '" />';
        echo '</a>';
        echo '<input class="text gk-acc-sitter-input" type="text" value="'
            . htmlspecialchars($uname, ENT_QUOTES, 'UTF-8') . '" readonly />';
        echo '</div>';
        echo '<input type="hidden" name="gk_perm' . $slot . '_sent" value="1" />';
        echo '<ul class="gk-acc-sitter-perms">';
        echo '<li><label><input type="checkbox" name="gk_army_' . $slot . '" value="1"' . ($armyOn ? ' checked' : '') . ' /> '
            . htmlspecialchars($gkLblSitArmy, ENT_QUOTES, 'UTF-8') . '</label></li>';
        echo '<li><label><input type="checkbox" name="gk_gold_' . $slot . '" value="1"' . ($goldOn ? ' checked' : '') . ' /> '
            . htmlspecialchars($gkLblSitGold, ENT_QUOTES, 'UTF-8') . '</label></li>';
        echo '<li><label><input type="checkbox" name="gk_msg_' . $slot . '" value="1" disabled /> '
            . htmlspecialchars($gkLblSitMsg, ENT_QUOTES, 'UTF-8') . '</label></li>';
        echo '<li><label><input type="checkbox" name="gk_rep_' . $slot . '" value="1" disabled /> '
            . htmlspecialchars($gkLblSitRep, ENT_QUOTES, 'UTF-8') . '</label></li>';
        echo '</ul>';
    } else {
        $fieldVal = ($slot === 1) ? $gkV1 : $gkV2;
        echo '<input class="text gk-acc-sitter-input" type="text" name="' . htmlspecialchars($slotInfo['field'], ENT_QUOTES, 'UTF-8')
            . '" maxlength="15" value="' . $fieldVal . '" />';
        $armyNew = array_key_exists('gk_army_new_' . $slot, $gkPosted)
            ? !empty($gkPosted['gk_army_new_' . $slot]) : true;
        $goldNew = array_key_exists('gk_gold_new_' . $slot, $gkPosted)
            ? !empty($gkPosted['gk_gold_new_' . $slot]) : true;
        echo '<input type="hidden" name="gk_perm_new_' . $slot . '_sent" value="1" />';
        echo '<ul class="gk-acc-sitter-perms">';
        echo '<li><label><input type="checkbox" name="gk_army_new_' . $slot . '" value="1"' . ($armyNew ? ' checked' : '') . ' /> '
            . htmlspecialchars($gkLblSitArmy, ENT_QUOTES, 'UTF-8') . '</label></li>';
        echo '<li><label><input type="checkbox" name="gk_gold_new_' . $slot . '" value="1"' . ($goldNew ? ' checked' : '') . ' /> '
            . htmlspecialchars($gkLblSitGold, ENT_QUOTES, 'UTF-8') . '</label></li>';
        echo '<li><label><input type="checkbox" disabled checked /> '
            . htmlspecialchars($gkLblSitMsg, ENT_QUOTES, 'UTF-8') . '</label></li>';
        echo '<li><label><input type="checkbox" disabled checked /> '
            . htmlspecialchars($gkLblSitRep, ENT_QUOTES, 'UTF-8') . '</label></li>';
        echo '</ul>';
    }

    echo '</div>';
};

$sitee = $database->getSitee($session->uid);
$timestamp = $database->isDeleting($session->uid);
?>

<form action="spieler.php" method="POST" class="gk-acc-form" id="gk_acc_form">
<input type="hidden" name="ft" value="p3" />
<input type="hidden" name="gk_membership" value="1" />
<input type="hidden" name="email_alt" id="gk_email_alt" value="<?php echo htmlspecialchars($session->userinfo['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
<input type="hidden" name="pw1" id="gk_pw1" value="" />
<input type="hidden" name="pw2" id="gk_pw2" value="" />
<input type="hidden" name="pw3" id="gk_pw3" value="" />
<input type="hidden" name="del_pw" id="gk_del_pw" value="" />

<table cellpadding="1" cellspacing="1" class="gk-acc-table gk-acc-settings" dir="rtl">
<thead>
<tr><th colspan="2" class="rbg"><?php echo htmlspecialchars($gkLblSettings, ENT_QUOTES, 'UTF-8'); ?></th></tr>
<tr class="gk-acc-cols">
    <th><?php echo htmlspecialchars($gkLblChangeType, ENT_QUOTES, 'UTF-8'); ?></th>
    <th><?php echo htmlspecialchars($gkLblNewValue, ENT_QUOTES, 'UTF-8'); ?></th>
</tr>
</thead>
<tbody>
<tr>
    <th><?php echo htmlspecialchars($gkLblNewPw, ENT_QUOTES, 'UTF-8'); ?></th>
    <td><input class="text" type="password" id="gk_new_pw" maxlength="30" autocomplete="new-password" /></td>
</tr>
<tr>
    <th><?php echo htmlspecialchars($gkLblNewEmail, ENT_QUOTES, 'UTF-8'); ?></th>
    <td><input class="text" type="email" name="email_neu" maxlength="80" autocomplete="off" value="<?php echo $gkEmailNeu; ?>" /></td>
</tr>
<tr>
    <td colspan="2" class="gk-acc-note"><?php
    echo htmlspecialchars($gkLblEmailNote, ENT_QUOTES, 'UTF-8');
    echo ' <bdi class="gk-num">300</bdi> <img src="' . $gkGoldSrc . '" class="gold gk-acc-gold-ico" alt="" /> '
        . htmlspecialchars($gkGoldWord, ENT_QUOTES, 'UTF-8');
    ?></td>
</tr>
</tbody>
</table>

<?php if (!empty($pwError)) { ?>
<p class="gk-acc-error"><?php echo htmlspecialchars($pwError, ENT_QUOTES, 'UTF-8'); ?></p>
<?php } ?>
<?php if (!empty($emailError)) { ?>
<p class="gk-acc-error"><?php echo htmlspecialchars($emailError, ENT_QUOTES, 'UTF-8'); ?></p>
<?php } ?>

<table cellpadding="1" cellspacing="1" class="gk-acc-table gk-acc-sitters" id="sitter" dir="rtl">
<thead>
<tr><th colspan="2" class="rbg"><?php echo htmlspecialchars($gkLblAgency, ENT_QUOTES, 'UTF-8'); ?></th></tr>
</thead>
<tbody>
<tr>
    <td colspan="2" class="gk-acc-note"><?php echo TZ_A_SITTER_CAN_LOG_INTO_YOUR_ACCOUNT; ?></td>
</tr>
<tr>
    <td colspan="2" class="gk-acc-sitter-grid">
        <?php
        foreach ($sitSlots as $slot => $info) {
            $gkRenderSitterCol($slot, $info);
        }
        ?>
    </td>
</tr>
<tr>
    <td colspan="2" class="gk-acc-note gk-acc-you-sit-label"><?php echo htmlspecialchars($gkLblYouSit, ENT_QUOTES, 'UTF-8'); ?></td>
</tr>
<tr>
    <td colspan="2" class="gk-acc-you-sit-list">
        <?php
        if (count($sitee) === 0) {
            echo '<span class="gk-acc-none">--</span>';
        } else {
            $names = array();
            foreach ($sitee as $sit) {
                $names[] = '<a href="spieler.php?uid=' . (int) $sit['id'] . '">'
                    . htmlspecialchars($database->getUserField($sit['id'], 'username', 0), ENT_QUOTES, 'UTF-8')
                    . '</a>';
            }
            echo implode(' &nbsp;|&nbsp; ', $names);
        }
        ?>
    </td>
</tr>
</tbody>
</table>

<?php if (!empty($sitterError)) { ?>
<p class="gk-acc-error"><?php echo htmlspecialchars($sitterError, ENT_QUOTES, 'UTF-8'); ?></p>
<?php } ?>

<table cellpadding="1" cellspacing="1" class="gk-acc-table gk-acc-other" dir="rtl">
<thead>
<tr><th colspan="2" class="rbg"><?php echo htmlspecialchars($gkLblOther, ENT_QUOTES, 'UTF-8'); ?></th></tr>
</thead>
<tbody>
<tr>
    <th><?php echo htmlspecialchars($gkLblNoSend, ENT_QUOTES, 'UTF-8'); ?></th>
    <td class="gk-acc-check"><input class="check" type="checkbox" name="v5" value="1"<?php if ($gkV5On) {
        echo ' checked';
    } ?> /></td>
</tr>
<tr>
    <th><?php echo htmlspecialchars($gkLblNoRecv, ENT_QUOTES, 'UTF-8'); ?></th>
    <td class="gk-acc-check"><input class="check" type="checkbox" name="v6" value="1"<?php if ($gkV6On) {
        echo ' checked';
    } ?> /></td>
</tr>
</tbody>
</table>

<table cellpadding="1" cellspacing="1" class="gk-acc-table gk-acc-commands" id="del_acc" dir="rtl">
<thead>
<tr><th colspan="2" class="rbg"><?php echo htmlspecialchars($gkLblCommands, ENT_QUOTES, 'UTF-8'); ?></th></tr>
</thead>
<tbody>
<?php if ($timestamp) { ?>
<tr>
    <td colspan="2" class="gk-acc-delete-pending">
        <a href="spieler.php?s=3&amp;id=<?php echo (int) $session->uid; ?>&amp;a=<?php echo (int) $session->checker; ?>&amp;e=4">
            <img class="del" src="img/x.gif" alt="<?php echo htmlspecialchars(CANCEL_PROCESS, ENT_QUOTES, 'UTF-8'); ?>" />
        </a>
        <?php
        $time = $generator->getTimeFormat($timestamp - time());
        echo TZ_ACCOUNT_DELETE_TIMER_PREFIX . ' <span id="timer' . (++$session->timer) . '">' . $time . '</span>';
        ?>
    </td>
</tr>
<?php } else { ?>
<tr>
    <th><?php echo htmlspecialchars($gkLblDelete, ENT_QUOTES, 'UTF-8'); ?></th>
    <td class="gk-acc-check"><input class="check" type="checkbox" name="gk_del" id="gk_del" value="1" /></td>
</tr>
<?php if (defined('NEW_FUNCTIONS_VACATION') && NEW_FUNCTIONS_VACATION) { ?>
<tr>
    <th><a href="spieler.php?s=5"><?php echo htmlspecialchars($gkLblVacation, ENT_QUOTES, 'UTF-8'); ?></a></th>
    <td class="gk-acc-check"><input class="check" type="checkbox" onclick="window.location.href='spieler.php?s=5'; return false;" /></td>
</tr>
<?php } ?>
<?php } ?>
</tbody>
</table>

<?php if (!empty($deleteError)) { ?>
<p class="gk-acc-error"><?php echo htmlspecialchars($deleteError, ENT_QUOTES, 'UTF-8'); ?></p>
<?php } ?>

<?php if (!empty($saveError)) { ?>
<p class="gk-acc-error gk-acc-save-error"><?php echo htmlspecialchars($saveError, ENT_QUOTES, 'UTF-8'); ?></p>
<?php } ?>

<table cellpadding="1" cellspacing="1" class="gk-acc-table gk-acc-save-row" dir="rtl">
<tbody>
<tr>
    <td class="gk-acc-save-cell">
        <p class="btn gk-prof-btn-wrap gk-acc-save-wrap">
            <button type="submit" name="s1" id="btn_save" class="trav_buttons gk-prof-save"><?php echo SAVE; ?></button>
            <input class="text gk-acc-save-pw" type="password" id="gk_save_pw" maxlength="30" autocomplete="current-password" />
            <span class="gk-acc-save-label"><?php echo htmlspecialchars($gkLblSavePw, ENT_QUOTES, 'UTF-8'); ?> :</span>
        </p>
    </td>
</tr>
</tbody>
</table>

</form>

<script>
(function () {
    var form = document.getElementById('gk_acc_form');
    if (!form) return;
    form.addEventListener('submit', function (e) {
        var savePw = document.getElementById('gk_save_pw');
        if (!savePw || !String(savePw.value || '').trim()) {
            e.preventDefault();
            savePw && savePw.focus();
            return;
        }
        var newPw = document.getElementById('gk_new_pw');
        var pw1 = document.getElementById('gk_pw1');
        var pw2 = document.getElementById('gk_pw2');
        var pw3 = document.getElementById('gk_pw3');
        var delPw = document.getElementById('gk_del_pw');
        var val = savePw ? savePw.value : '';
        if (newPw && newPw.value && newPw.value.length < 4) {
            e.preventDefault();
            newPw.focus();
            return;
        }
        if (pw1) pw1.value = val;
        if (delPw) delPw.value = val;
        if (newPw && newPw.value) {
            if (pw2) pw2.value = newPw.value;
            if (pw3) pw3.value = newPw.value;
        }
        var del = document.getElementById('gk_del');
        if (del && del.checked) {
            var hiddenDel = document.createElement('input');
            hiddenDel.type = 'hidden';
            hiddenDel.name = 'del';
            hiddenDel.value = '1';
            form.appendChild(hiddenDel);
        }
    });
})();
</script>
