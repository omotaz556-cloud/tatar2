<?php

#################################################################################
##  Filename       : 16.tpl                                                    ##
##  Type           : Plus - Gold transfer (player to player)                   ##
#################################################################################

$plusUid = (int) $session->uid;
$plusRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
$GLOBALS['gkPlusLiteralPage'] = !empty($GLOBALS['gkShell']) && $plusRtl;

if (!isset($gkTransferable)) {
    $gkTransferable = 0;
}
if (!isset($gkWorldOptions) || !is_array($gkWorldOptions)) {
    $gkWorldKey = class_exists('CentralGold') ? CentralGold::worldKey() : (defined('SQL_DB') ? SQL_DB : 'world');
    $gkWorldLabel = defined('SERVER_NAME') ? SERVER_NAME : 'Novaterra';
    $gkWorldOptions = array($gkWorldKey => $gkWorldLabel);
}

include('Templates/Plus/pmenu.tpl');

$gkPlusTransferUi = class_exists('GreekPlus') && GreekPlus::isGreekPlusUi();

if ($gkPlusTransferUi) {
    include dirname(__DIR__) . '/Greek/plus_transfer_greek.tpl';
    GreekPlus::menuClose();
    return;
}

$transferMsg = isset($transferMsg) ? $transferMsg : '';
$transferOk = !empty($transferOk);
$gkPostedPlayer = isset($gkPostedPlayer) ? $gkPostedPlayer : '';
$gkPostedAmount = isset($gkPostedAmount) ? $gkPostedAmount : '';
$gkPostedWorld = isset($gkPostedWorld) ? $gkPostedWorld : '';
?>
<?php if ($transferMsg !== '') { ?>
<p class="<?php echo $transferOk ? 'success' : 'error'; ?>"><?php
    echo htmlspecialchars($transferMsg, ENT_QUOTES, 'UTF-8');
?></p>
<?php } ?>

<form method="post" action="plus.php?id=16">
<input type="hidden" name="gk_gold_transfer" value="1" />
<table cellpadding="1" cellspacing="1" class="plusFunctions">
    <thead><tr><th colspan="2"><?php echo $plusRtl ? 'تحويل الذهب' : 'Gold transfer'; ?></th></tr></thead>
    <tbody>
        <tr>
            <td><?php echo $plusRtl ? 'اسم اللاعب' : 'Player name'; ?></td>
            <td><input type="text" name="gk_transfer_player" value="<?php
                echo htmlspecialchars($gkPostedPlayer, ENT_QUOTES, 'UTF-8');
            ?>" maxlength="100" required="required" /></td>
        </tr>
        <tr>
            <td><?php echo $plusRtl ? 'عدد الذهب' : 'Gold amount'; ?></td>
            <td><input type="text" name="gk_transfer_amount" value="<?php
                echo htmlspecialchars($gkPostedAmount, ENT_QUOTES, 'UTF-8');
            ?>" maxlength="12" required="required" /></td>
        </tr>
        <tr>
            <td><?php echo $plusRtl ? 'العالم' : 'World'; ?></td>
            <td><select name="gk_transfer_world">
<?php foreach ($gkWorldOptions as $gkWKey => $gkWLabel) { ?>
                <option value="<?php echo htmlspecialchars((string) $gkWKey, ENT_QUOTES, 'UTF-8'); ?>"<?php
                    echo ((string) $gkWKey === (string) $gkPostedWorld) ? ' selected="selected"' : '';
                ?>><?php echo htmlspecialchars((string) $gkWLabel, ENT_QUOTES, 'UTF-8'); ?></option>
<?php } ?>
            </select></td>
        </tr>
        <tr>
            <td><?php echo $plusRtl ? 'كلمة المرور' : 'Password'; ?></td>
            <td><input type="password" name="gk_transfer_password" maxlength="100" required="required" /></td>
        </tr>
        <tr><td colspan="2"><button type="submit"><?php echo $plusRtl ? 'إرسال' : 'Send'; ?></button></td></tr>
    </tbody>
</table>
</form>

