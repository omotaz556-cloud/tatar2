<?php

#################################################################################
##  Filename       : 18.tpl                                                    ##
##  Type           : Plus - Troop Costs in Gold                                ##
#################################################################################

include("Templates/Plus/pmenu.tpl");
include_once("GameEngine/Data/unitdata.php");

$plusRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
$t = function ($ar, $en) use ($plusRtl) {
    return $plusRtl ? $ar : $en;
};

$resPerGold = defined('GOLD_RES_UNIT') ? max(1, (int) GOLD_RES_UNIT) : 100;
$qty = isset($_GET['qty']) ? max(1, min(100000, (int) $_GET['qty'])) : 1;

$tribes = [
    1 => ['name' => defined('TRIBE1') ? TRIBE1 : 'Romans', 'start' => 1],
    2 => ['name' => defined('TRIBE2') ? TRIBE2 : 'Teutons', 'start' => 11],
    3 => ['name' => defined('TRIBE3') ? TRIBE3 : 'Gauls', 'start' => 21],
];
if (defined('NEW_FUNCTION_TRIBE_HUNS') && NEW_FUNCTION_TRIBE_HUNS) {
    $tribes[6] = ['name' => defined('TRIBE6') ? TRIBE6 : 'Huns', 'start' => 51];
}
if (defined('NEW_FUNCTION_TRIBE_EGIPTEANS') && NEW_FUNCTION_TRIBE_EGIPTEANS) {
    $tribes[7] = ['name' => defined('TRIBE7') ? TRIBE7 : 'Egyptians', 'start' => 61];
}
if (defined('NEW_FUNCTION_TRIBE_SPARTANS') && NEW_FUNCTION_TRIBE_SPARTANS) {
    $tribes[8] = ['name' => defined('TRIBE8') ? TRIBE8 : 'Spartans', 'start' => 71];
}
if (defined('NEW_FUNCTION_TRIBE_VIKINGS') && NEW_FUNCTION_TRIBE_VIKINGS) {
    $tribes[9] = ['name' => defined('TRIBE9') ? TRIBE9 : 'Vikings', 'start' => 81];
}

$playerTribe = (int) ($session->tribe ?? 1);
if (!isset($tribes[$playerTribe])) {
    $playerTribe = 1;
}
$selectedTribe = isset($_GET['tribe']) ? (int) $_GET['tribe'] : $playerTribe;
if (!isset($tribes[$selectedTribe])) {
    $selectedTribe = $playerTribe;
}

$unitStart = (int) $tribes[$selectedTribe]['start'];
?>

<p><?php echo $t(
    'احسب تكلفة تدريب الجنود بالذهب حسب سعر تحويل الموارد (',
    'Calculate troop training costs in gold using the resource exchange rate ('
); ?><?php echo (int) $resPerGold; ?> <?php echo $t(
    'مورد = 1 ذهب). الصيغة: مجموع الموارد ÷ السعر، ثم التقريب لأعلى.',
    ' resources = 1 gold). Formula: total resources ÷ rate, rounded up.'
); ?></p>

<form method="get" action="plus.php" style="margin:10px 0;">
    <input type="hidden" name="id" value="18" />
    <label><?php echo $t('القبيلة', 'Tribe'); ?>:
        <select name="tribe" onchange="this.form.submit()">
            <?php foreach ($tribes as $tid => $tribe): ?>
                <option value="<?php echo (int) $tid; ?>" <?php echo $tid === $selectedTribe ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($tribe['name'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    &nbsp;
    <label><?php echo $t('الكمية', 'Quantity'); ?>:
        <input type="number" name="qty" min="1" max="100000" value="<?php echo (int) $qty; ?>" />
    </label>
    <button type="submit"><?php echo $t('احسب', 'Calculate'); ?></button>
</form>

<table class="plusFunctions" cellpadding="1" cellspacing="1">
    <thead>
        <tr>
            <th colspan="7">
                <?php echo $t('تكاليف الجنود بالذهب', 'Troop costs in gold'); ?>
                — <?php echo htmlspecialchars($tribes[$selectedTribe]['name'], ENT_QUOTES, 'UTF-8'); ?>
                (×<?php echo (int) $qty; ?>)
            </th>
        </tr>
        <tr>
            <td><?php echo $t('الوحدة', 'Unit'); ?></td>
            <td><img src="img/x.gif" class="r1" alt="" /></td>
            <td><img src="img/x.gif" class="r2" alt="" /></td>
            <td><img src="img/x.gif" class="r3" alt="" /></td>
            <td><img src="img/x.gif" class="r4" alt="" /></td>
            <td><?php echo $t('المجموع', 'Total'); ?></td>
            <td><img src="img/x.gif" class="gold" alt="<?php echo GOLD; ?>" /></td>
        </tr>
    </thead>
    <tbody>
    <?php for ($i = 0; $i < 10; $i++): ?>
        <?php
        $uid = $unitStart + $i;
        $uVar = 'u' . $uid;
        if (!isset($$uVar) || !is_array($$uVar)) {
            continue;
        }
        $u = $$uVar;
        $wood = (int) ($u['wood'] ?? 0) * $qty;
        $clay = (int) ($u['clay'] ?? 0) * $qty;
        $iron = (int) ($u['iron'] ?? 0) * $qty;
        $crop = (int) ($u['crop'] ?? 0) * $qty;
        $total = $wood + $clay + $iron + $crop;
        $goldCost = $total > 0 ? (int) ceil($total / $resPerGold) : 0;
        $unitName = defined('U' . $uid) ? constant('U' . $uid) : ('U' . $uid);
        ?>
        <tr>
            <td>
                <img class="unit u<?php echo (int) $uid; ?>" src="img/x.gif" alt="" />
                <?php echo htmlspecialchars($unitName, ENT_QUOTES, 'UTF-8'); ?>
            </td>
            <td style="text-align:center;"><?php echo number_format($wood); ?></td>
            <td style="text-align:center;"><?php echo number_format($clay); ?></td>
            <td style="text-align:center;"><?php echo number_format($iron); ?></td>
            <td style="text-align:center;"><?php echo number_format($crop); ?></td>
            <td style="text-align:center;"><?php echo number_format($total); ?></td>
            <td style="text-align:center;"><b><?php echo number_format($goldCost); ?></b></td>
        </tr>
    <?php endfor; ?>
    </tbody>
</table>

<p style="margin-top:10px;font-size:11px;color:#666;">
    <?php echo $t(
        'هذه صفحة مرجعية للحساب فقط. لشراء الموارد بالذهب استخدم السوق إن كانت الميزة مفعّلة.',
        'This page is a reference calculator only. Buy resources with gold from the marketplace when that feature is enabled.'
    ); ?>
</p>
</div>
