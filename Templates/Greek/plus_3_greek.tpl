<?php
/**
 * Greek.sa literal plus balance table — matches reference + full feature set.
 */

$gkNow = defined('NOW') ? NOW : 'فورا';
$gkPlusCost = 10;
$gkFinishBuildCost = 2;
$gkFinishTrainCost = 35;
$gkNpcCost = 3;
$gkResUnit = defined('GOLD_RES_UNIT') ? (int) GOLD_RES_UNIT : 20000;
$gkResUnitFmt = number_format($gkResUnit);

$gkPlusDur = (defined('PLUS_TIME') && PLUS_TIME >= 86400)
    ? ((int) (PLUS_TIME / 86400)) . ' ' . (isset($plusText['days']) ? $plusText['days'] : 'أيام')
    : ((defined('PLUS_TIME') ? (int) (PLUS_TIME / 3600) : 168) . ' '
        . (isset($plusText['hours']) ? $plusText['hours'] : 'ساعات'));
$gkBonusDur = isset($plusDuration) ? $plusDuration : (
    (defined('PLUS_PRODUCTION') && PLUS_PRODUCTION >= 86400)
        ? ((int) (PLUS_PRODUCTION / 86400)) . ' ' . (isset($plusText['days']) ? $plusText['days'] : 'أيام')
        : '7 أيام'
);

$gkRs = array('b1' => 'x1', 'b2' => 'x2', 'b3' => 'x3', 'b4' => 'x4');
$gkBonusLabels = array(
    'b1' => 'إنتاج الخشب',
    'b2' => 'إنتاج الطين',
    'b3' => 'إنتاج الحديد',
    'b4' => 'إنتاج القمح',
);

$gkMarketId = 0;
if (!empty($village) && is_object($village) && !empty($village->resarray)) {
    for ($gkMi = 19; $gkMi <= 40; $gkMi++) {
        if ((int) ($village->resarray['f' . $gkMi . 't'] ?? 0) === 17) {
            $gkMarketId = $gkMi;
            break;
        }
    }
}

$gkBuyGoldDefault = 1;
if (isset($_POST['buy_gold_resources'])) {
    if (isset($_POST['goldamt'])) {
        $gkBuyGoldDefault = max(1, (int) $_POST['goldamt']);
    } elseif (isset($_POST['X'][0])) {
        $gkBuyGoldDefault = max(1, (int) $_POST['X'][0]);
    }
}

global $database;
$gkBuyGoldHint = class_exists('GreekPlus')
    ? GreekPlus::suggestedGoldForResourcePurchase($village, $database)
    : 1;

$buyResMsg = isset($buyResMsg) ? $buyResMsg : '';
$buyResOk  = isset($buyResOk) ? $buyResOk : false;
$gkPlusUntil = isset($golds['plus']) ? (int) $golds['plus'] : 0;
$gkDate2 = isset($date2) ? (int) $date2 : time();
$gkPlusCdLabels = array(
    'remaining' => isset($plusText['remaining']) ? $plusText['remaining'] : 'المتبقي',
    'until' => isset($plusText['until']) ? $plusText['until'] : 'حتى',
    'seconds' => isset($plusText['seconds']) ? $plusText['seconds'] : 'ثانية',
    'days' => defined('DAYS') ? DAYS : 'أيام',
    'hours' => defined('HOURS') ? HOURS : 'ساعات',
    'mins' => defined('MINS') ? MINS : 'دقيقة',
);
?>
<form method="post" id="Form_buyg" action="plus.php?id=3"></form>
<table class="a b0 th1"><tbody>
<tr><th colspan="4"><?php echo $plusText['functions']; ?></th></tr>
<tr><th>الوصف</th><th>المده</th><th>الذهب</th><th>فعل</th></tr>
<tr class="gk-plus-row" data-expired-msg="<?php echo htmlspecialchars($plusText['ended'], ENT_QUOTES, 'UTF-8'); ?>"><th><g>حساب</g><o> بلاس</o><?php
    /* Match greek.sa: one-line title when inactive; timer only while plus is active */
    if (!empty($plusJustExpired)) {
        echo '<c>' . htmlspecialchars($plusText['ended'], ENT_QUOTES, 'UTF-8') . '</c>';
    } elseif ($gkPlusUntil > $gkDate2 && class_exists('GreekPlus')) {
        echo GreekPlus::renderPlusCountdown($gkPlusUntil, $gkDate2, $gkPlusCdLabels);
    }
?></th><th><?php echo htmlspecialchars($gkPlusDur, ENT_QUOTES, 'UTF-8'); ?></th><th><?php echo GreekPlus::goldCell($gkPlusCost); ?></th><th><?php
    echo GreekPlus::actionLink($golds['gold'], $gkPlusCost, $gkPlusUntil, 8, null, $plusIsBanned);
?></th></tr>
<?php echo GreekPlus::sepRow(4); ?>
<?php foreach ($plusBonuses as $plusBonus) {
    $until = (int) $golds[$plusBonus['field']];
    $rs = isset($gkRs[$plusBonus['field']]) ? $gkRs[$plusBonus['field']] : 'x1';
    $bl = isset($gkBonusLabels[$plusBonus['field']]) ? $gkBonusLabels[$plusBonus['field']] : $plusBonus['label'];
?>
<tr><th>+25% <p class="Rs <?php echo $rs; ?>" title="<?php echo htmlspecialchars($bl, ENT_QUOTES, 'UTF-8'); ?>"></p> <?php echo htmlspecialchars($bl, ENT_QUOTES, 'UTF-8'); ?><?php
    if ($until >= $gkDate2 && class_exists('GreekPlus')) {
        echo '<br>' . GreekPlus::renderPlusCountdown($until, $gkDate2, $gkPlusCdLabels);
    }
?></th><th><?php echo htmlspecialchars($gkBonusDur, ENT_QUOTES, 'UTF-8'); ?></th><th><?php echo GreekPlus::goldCell(5); ?></th><th><?php
    echo GreekPlus::actionLink($golds['gold'], 5, $until, $plusBonus['id'], null, $plusIsBanned);
?></th></tr>
<?php } ?>
<?php echo GreekPlus::sepRow(4); ?>
<tr><th>إستكمال أوامر البناء والبحث في هذه القرية فورا</th><th><?php echo htmlspecialchars($gkNow, ENT_QUOTES, 'UTF-8'); ?></th><th><?php echo GreekPlus::goldCell($gkFinishBuildCost); ?></th><th><?php
    if ((int) $golds['gold'] >= $gkFinishBuildCost) {
        echo '<a href="plus.php?id=7">تفعيل</a>';
    } else {
        echo '<a href="plus.php?s=1">' . TOO_LITTLE_GOLD . '</a>';
    }
?></th></tr>
<tr><th>التجارة مع تاجر المبادلة</th><th><?php echo htmlspecialchars($gkNow, ENT_QUOTES, 'UTF-8'); ?></th><th><?php echo GreekPlus::goldCell($gkNpcCost); ?></th><th><?php
    if ((int) $golds['gold'] > 2 && $gkMarketId > 0) {
        echo '<a class="gk-grey" href="build.php?id=' . (int) $gkMarketId . '&amp;t=3">' . MARKETPLACE . '</a>';
    } elseif ((int) $golds['gold'] > 2) {
        echo '<a class="gk-grey" href="build.php?gid=17&amp;t=3">' . NPC . '</a>';
    } else {
        echo '<a href="plus.php?s=1">' . TOO_LITTLE_GOLD . '</a>';
    }
?></th></tr>
<tr><th>إنهاء تدريب الجنود فورا</th><th><?php echo htmlspecialchars($gkNow, ENT_QUOTES, 'UTF-8'); ?></th><th><?php echo GreekPlus::goldCell($gkFinishTrainCost); ?></th><th><?php
    if ((int) $golds['gold'] >= $gkFinishTrainCost) {
        echo '<a href="plus.php?id=14">تفعيل</a>';
    } else {
        echo '<a href="plus.php?s=1">' . TOO_LITTLE_GOLD . '</a>';
    }
?></th></tr>
<tr><th>شراء <?php echo $gkResUnitFmt; ?> من كل مورد مقابل 1 <?php echo GOLD; ?> <a href="#" onclick="var e=document.getElementById('Buyg');if(e){e.value='<?php echo (int) $gkBuyGoldHint; ?>';}return false;" class="green" id="gkBuyGoldHint">(<?php echo (int) $gkBuyGoldHint; ?>)</a></th><th><?php echo htmlspecialchars($gkNow, ENT_QUOTES, 'UTF-8'); ?></th><th><input id="Buyg" class="S2" name="goldamt" type="text" value="<?php echo (int) $gkBuyGoldDefault; ?>" autocomplete="off" autocorrect="off" form="Form_buyg" inputmode="numeric" maxlength="6"><p class="Rs x7" title="<?php echo GOLD; ?>"></p><input type="hidden" name="buy_gold_resources" value="1" form="Form_buyg"></th><th><input type="submit" value="شراء" class="OffSu" form="Form_buyg"></th></tr>
</tbody></table>
<?php if ($buyResMsg !== '') { ?>
<p style="font-weight:bold;color:<?php echo $buyResOk ? '#2e7d32' : '#b3261e'; ?>;"><?php echo htmlspecialchars($buyResMsg, ENT_QUOTES, 'UTF-8'); ?></p>
<?php } ?>
