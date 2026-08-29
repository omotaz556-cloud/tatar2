<?php
/**
 * Greek.sa gold transfer form (plus.php?id=16).
 *
 * Expects: $gkTransferable, $transferMsg, $transferOk,
 *          $gkPostedPlayer, $gkPostedAmount, $gkPostedWorld, $gkWorldOptions
 */

$gkTransferable = isset($gkTransferable) ? (int) $gkTransferable : 0;
$transferMsg = isset($transferMsg) ? (string) $transferMsg : '';
$transferOk = !empty($transferOk);
$gkPostedPlayer = isset($gkPostedPlayer) ? (string) $gkPostedPlayer : '';
$gkPostedAmount = isset($gkPostedAmount) ? (string) $gkPostedAmount : '';
$gkPostedWorld = isset($gkPostedWorld) ? (string) $gkPostedWorld : '';
$gkWorldOptions = isset($gkWorldOptions) && is_array($gkWorldOptions) ? $gkWorldOptions : array();

if ($gkPostedWorld === '' && !empty($gkWorldOptions)) {
    $gkWorldKeys = array_keys($gkWorldOptions);
    $gkPostedWorld = (string) $gkWorldKeys[0];
}

$gkGoldTitle = defined('GOLD') ? GOLD : 'ذهب';
$gkLblTitle = defined('TZ_GK_PLUS_TRANSFER_TITLE')
    ? TZ_GK_PLUS_TRANSFER_TITLE
    : 'تحويل الذهب من لاعب إلى آخر';
$gkLblIntro = defined('TZ_GK_PLUS_TRANSFER_INTRO')
    ? TZ_GK_PLUS_TRANSFER_INTRO
    : 'يمكنك تحويل الذهب المشترى أو ذهب المعجزة فقط. يمكنك تحويل :';
$gkLblPlayer = defined('TZ_GK_PLUS_TRANSFER_PLAYER') ? TZ_GK_PLUS_TRANSFER_PLAYER : 'اسم اللاعب :';
$gkLblAmount = defined('TZ_GK_PLUS_TRANSFER_AMOUNT') ? TZ_GK_PLUS_TRANSFER_AMOUNT : 'عدد الذهب :';
$gkLblWorld = defined('TZ_GK_PLUS_TRANSFER_WORLD') ? TZ_GK_PLUS_TRANSFER_WORLD : 'العالم :';
$gkLblPassword = defined('TZ_GK_PLUS_TRANSFER_PASSWORD') ? TZ_GK_PLUS_TRANSFER_PASSWORD : 'كلمة السر للتأكيد:';
$gkLblSubmit = defined('TZ_GK_PLUS_TRANSFER_SUBMIT') ? TZ_GK_PLUS_TRANSFER_SUBMIT : 'أرسل';
?>
<?php if ($transferMsg !== '') { ?>
<p class="gk-plus-transfer-flash<?php echo $transferOk ? ' ok' : ' err'; ?>"><?php
    echo htmlspecialchars($transferMsg, ENT_QUOTES, 'UTF-8');
?></p>
<?php } ?>

<form method="post" action="plus.php?id=16" class="gk-plus-transfer-form">
<input type="hidden" name="gk_gold_transfer" value="1" />

<table cellpadding="0" cellspacing="0" class="a b0 th1 gk-plus-transfer-table">
    <colgroup>
        <col class="gk-xfer-col-label" />
        <col class="gk-xfer-col-field" />
    </colgroup>
    <thead>
        <tr>
            <th colspan="2"><?php echo htmlspecialchars($gkLblTitle, ENT_QUOTES, 'UTF-8'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2" class="gk-plus-transfer-intro"><?php
                echo htmlspecialchars($gkLblIntro, ENT_QUOTES, 'UTF-8');
            ?> <?php echo number_format($gkTransferable); ?> <p class="Rs x7" title="<?php
                echo htmlspecialchars($gkGoldTitle, ENT_QUOTES, 'UTF-8');
            ?>"></p></td>
        </tr>
        <tr class="gk-xfer-row">
            <th><?php echo htmlspecialchars($gkLblPlayer, ENT_QUOTES, 'UTF-8'); ?></th>
            <td>
                <input class="text gk-xfer-input-name" type="text" name="gk_transfer_player" value="<?php
                    echo htmlspecialchars($gkPostedPlayer, ENT_QUOTES, 'UTF-8');
                ?>" maxlength="100" autocomplete="off" autocorrect="off" autocapitalize="off" required="required" />
            </td>
        </tr>
        <tr class="gk-xfer-row">
            <th><?php echo htmlspecialchars($gkLblAmount, ENT_QUOTES, 'UTF-8'); ?></th>
            <td>
                <input class="text gk-xfer-input-amount" type="text" name="gk_transfer_amount" value="<?php
                    echo htmlspecialchars($gkPostedAmount, ENT_QUOTES, 'UTF-8');
                ?>" maxlength="12" autocomplete="off" autocorrect="off" inputmode="numeric" required="required" />
            </td>
        </tr>
        <tr class="gk-xfer-row">
            <th><?php echo htmlspecialchars($gkLblWorld, ENT_QUOTES, 'UTF-8'); ?></th>
            <td>
                <select class="text gk-xfer-input-world" name="gk_transfer_world" required="required">
<?php foreach ($gkWorldOptions as $gkWKey => $gkWLabel) { ?>
                    <option value="<?php echo htmlspecialchars((string) $gkWKey, ENT_QUOTES, 'UTF-8'); ?>"<?php
                        echo ((string) $gkWKey === (string) $gkPostedWorld) ? ' selected="selected"' : '';
                    ?>><?php echo htmlspecialchars((string) $gkWLabel, ENT_QUOTES, 'UTF-8'); ?></option>
<?php } ?>
                </select>
            </td>
        </tr>
        <tr class="gk-xfer-row">
            <th><?php echo htmlspecialchars($gkLblPassword, ENT_QUOTES, 'UTF-8'); ?></th>
            <td>
                <input class="text gk-xfer-input-password" type="password" name="gk_transfer_password" value="" maxlength="100" autocomplete="new-password" required="required" />
            </td>
        </tr>
    </tbody>
</table>

<p class="gk-plus-transfer-btn-wrap">
    <button type="submit" class="gk-plus-transfer-btn"><?php
        echo htmlspecialchars($gkLblSubmit, ENT_QUOTES, 'UTF-8');
    ?></button>
</p>
</form>
