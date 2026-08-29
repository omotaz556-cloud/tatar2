<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : 3.tpl                                                     ##
##  Type           : Plus Functions - Purchase and Status Overview             ##
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

/**
 * TODO REZOLVAT: "Reduce this file by a lot, by using arrays".
 *
 * Cele patru bonusuri de productie erau scrise de mana, unul cate unul, aproape
 * identice - se schimbau doar coloana din baza, id-ul din link si eticheta.
 * Acum sunt un array parcurs intr-o bucla.
 *
 * Reparat pe parcurs:
 *   - uid-ul intra in SQL fara conversie ("WHERE id='$uid'");
 *   - "or die(mysqli_error())" arata jucatorului erori de baza de date;
 *   - blocul de lemn compara cu $datetime1 in loc de $tl_b1 (aceeasi valoare,
 *     dar inconsecvent - la o modificare viitoare devine bug);
 *   - expirarea abonamentului Plus se scria in baza IN TIMPUL randarii, adica
 *     un sablon facea modificari; a fost mutata inainte de afisare, ca sa fie
 *     clar ce se intampla.
 */

$plusUid = (int) $session->uid;
$plusRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
$plusText = $plusRtl ? array(
    'remaining' => 'المتبقي', 'until' => 'حتى', 'seconds' => 'ثانية',
    'days' => 'أيام', 'hours' => 'ساعات', 'noGold' => 'لا تملك ذهبًا حاليًا.',
    'functions' => 'وظائف بلاس', 'ended' => 'انتهت ميزة بلس.',
    'goldBalance' => 'انت تملك حاليا',
    'get' => 'احصل على بلس', 'code' => 'استبدل رمز ذهب',
    'enter' => 'أدخل الرمز', 'redeem' => 'استبدال'
) : array(
    'remaining' => 'Remaining', 'until' => 'until', 'seconds' => 'secs',
    'days' => 'Days', 'hours' => 'Hours', 'noGold' => "You currently don't own gold.",
    'functions' => 'Plus functions', 'ended' => 'Your PLUS advantage has ended.',
    'goldBalance' => 'You currently have',
    'get' => 'Get PLUS', 'code' => 'Redeem a gold code',
    'enter' => 'Enter code', 'redeem' => 'Redeem'
);

$plusRes = mysqli_query(
    $database->dblink,
    "SELECT gold, plus, b1, b2, b3, b4, goldclub FROM " . TB_PREFIX . "users WHERE id = " . $plusUid . " LIMIT 1"
);

$golds = $plusRes ? mysqli_fetch_assoc($plusRes) : null;

if (!empty($buyResOk) && isset($session->gold)) {
    if (!is_array($golds)) {
        $golds = array();
    }
    $golds['gold'] = (int) $session->gold;
}

if (!$golds) {
    // Fara randul utilizatorului nu avem ce afisa. Nu aratam eroarea bazei de
    // date jucatorului; o lasam in log, unde ii e locul.
    error_log('[Novaterra] plus/3.tpl: nu am putut citi datele utilizatorului ' . $plusUid
        . ': ' . mysqli_error($database->dblink));

    $golds = array('gold' => 0, 'plus' => 0, 'b1' => 0, 'b2' => 0, 'b3' => 0, 'b4' => 0, 'goldclub' => 0);
}

$GLOBALS['gkPlusLiteralPage'] = !empty($GLOBALS['gkShell']) && $plusRtl;

include("Templates/Plus/pmenu.tpl");

$gkPlusTable = class_exists('GreekPlus') && GreekPlus::isGreekPlusUi();
$colDesc = $gkPlusTable ? 'الوصف' : DESCRIPTION;
$colDur = $gkPlusTable ? 'المده' : DURATION;
$colGold = GOLD;
$colAct = $gkPlusTable ? 'فعل' : ACTION;

$date2 = time();

/**
 * Abonamentul Plus expirat se marcheaza ACUM, inainte de afisare.
 * Inainte, actualizarea se facea la mijlocul randarii tabelului.
 */
if ((int) $golds['plus'] > 0 && (int) $golds['plus'] <= $date2) {
    mysqli_query($database->dblink,
        "UPDATE " . TB_PREFIX . "users SET plus = 0 WHERE id = " . $plusUid . " LIMIT 1");

    $golds['plus'] = 0;
    $plusJustExpired = true;
} else {
    $plusJustExpired = false;
}

if (!function_exists('formatRemainingTime')) {
    function formatRemainingTime($endTimestamp, $nowTimestamp)
    {
        $remaining = (int) $endTimestamp - (int) $nowTimestamp;

        if ($remaining <= 0) {
            return '';
        }

        $days = intdiv($remaining, 86400); $remaining %= 86400;
        $hours = intdiv($remaining, 3600);  $remaining %= 3600;
        $mins = intdiv($remaining, 60);
        $secs = $remaining % 60;

        global $plusText;
        return $plusText['remaining'] . ': <b>' . $days . '</b> ' . DAYS
             . ' <b>' . $hours . '</b> ' . HOURS
             . ' <b>' . $mins . '</b> ' . MINS
             . ' <b>' . $secs . '</b> ' . $plusText['seconds'] . ' (' . $plusText['until'] . ' ' . date('H:i:s', (int) $endTimestamp) . ')';
    }
}

/**
 * Butonul de actiune, identic pentru toate randurile.
 *
 * @param int  $gold    aurul jucatorului
 * @param int  $cost    cat costa
 * @param int  $until   pana cand e activ bonusul (0 = inactiv)
 * @param int  $linkId  id-ul din plus.php?id=...
 * @param bool $banned  contul e blocat
 */
if (!function_exists('plusActionCell')) {
    function plusActionCell($gold, $cost, $until, $linkId, $banned = false)
    {
        $guard = 'onclick="if(this.dataset.c) return false; this.dataset.c=1;'
               . ' this.style.pointerEvents=\'none\'; this.style.opacity=\'0.5\';"';

        if ($banned) {
            return '<a href="banned.php"><span class="none">' . TOO_LITTLE_GOLD . '</span></a>';
        }

        if ((int) $gold < (int) $cost) {
            return '<a href="plus.php?s=1"><span class="none">' . TOO_LITTLE_GOLD . '</span></a>';
        }

        // activ inca -> prelungeste; altfel -> activeaza
        $label = ((int) $until > time()) ? EXTEND : ACTIVATE;

        return '<a href="plus.php?id=' . (int) $linkId . '" ' . $guard . '><span>' . $label . '</span></a>';
    }
}

/**
 * Cele patru bonusuri de productie. Asta e tot ce se schimba intre ele.
 */
$plusBonuses = array(
    array('css' => 'r1', 'label' => TZ_PRODUCTION_LUMBER, 'field' => 'b1', 'id' => 9,  'popup' => 1),
    array('css' => 'r2', 'label' => TZ_PRODUCTION_CLAY,   'field' => 'b2', 'id' => 10, 'popup' => 2),
    array('css' => 'r3', 'label' => TZ_PRODUCTION_IRON,   'field' => 'b3', 'id' => 11, 'popup' => 3),
    array('css' => 'r4', 'label' => TZ_PRODUCTION_CROP,   'field' => 'b4', 'id' => 12, 'popup' => 4),
);

$plusIsBanned  = (defined('BANNED') && $session->access == BANNED);
$plusDuration  = (PLUS_PRODUCTION >= 86400)
    ? (PLUS_PRODUCTION / 86400) . ' ' . $plusText['days']
    : (PLUS_PRODUCTION / 3600) . ' ' . $plusText['hours'];

$protectionOptions = $plusRtl ? array(
    array('name' => 'الأولى', 'duration' => '24 ساعة', 'cost' => 5000),
    array('name' => 'الثانية', 'duration' => '24 ساعة', 'cost' => 7000),
    array('name' => 'الثالثة', 'duration' => '12 ساعة', 'cost' => 9000),
    array('name' => 'الرابعة', 'duration' => '8 ساعات', 'cost' => 10500),
    array('name' => 'الخامسة', 'duration' => '8 ساعات', 'cost' => 12500)
) : array(
    array('name' => 'First', 'duration' => '24 hours', 'cost' => 5000),
    array('name' => 'Second', 'duration' => '24 hours', 'cost' => 7000),
    array('name' => 'Third', 'duration' => '12 hours', 'cost' => 9000),
    array('name' => 'Fourth', 'duration' => '8 hours', 'cost' => 10500),
    array('name' => 'Fifth', 'duration' => '8 hours', 'cost' => 12500)
);
$protectionMsg = isset($protectionMsg) ? $protectionMsg : '';

if ($gkPlusTable) {
    GreekPlus::goldBalance((int) $golds['gold']);
    include dirname(__DIR__) . '/Greek/plus_3_greek.tpl';
    GreekPlus::menuClose();
    return;
}

if ((int) $golds['gold'] === 0) {
    echo '<p class="gk-plus-balance">' . $plusText['noGold'] . '</p>';
} else {
    echo '<p class="gk-plus-balance">' . $plusText['goldBalance'] . ' <b>'
        . number_format((int) $golds['gold']) . '</b> ' . GOLD . '</p>';
}
?>
<table class="plusFunctions gk-plus-table" cellpadding="1" cellspacing="1">
    <thead>
        <tr><th colspan="5"><?php echo $plusText['functions']; ?></th></tr>
        <tr>
            <td></td>
            <td><?php echo $colDesc; ?></td>
            <td><?php echo $colDur; ?></td>
            <td><?php echo $colGold; ?></td>
            <td><?php echo $colAct; ?></td>
        </tr>
    </thead>
    <tbody>

        <!-- CONT PLUS -->
        <tr>
            <td class="man"><a href="#" onClick="return Popup(0,6);"><img class="help" src="img/x.gif" alt="" /></a></td>
            <td class="desc">
                <b><font color="#71D000">P</font><font color="#FF6F0F">l</font><font color="#71D000">u</font><font color="#FF6F0F">s</font></b>
                <?php echo ACCOUNT; ?><br />
                <span class="run"><?php
                    if ($plusJustExpired) {
                        echo $plusText['ended'] . '<br>';
                    } elseif ((int) $golds['plus'] === 0) {
                        echo $plusText['get'] . '<br>';
                    } else {
                        echo "<font color='#B3B3B3' size='1'>"
                           . formatRemainingTime($golds['plus'], $date2) . '</font>';
                    }
                ?></span>
            </td>
            <td class="dur"><?php
                echo (PLUS_TIME >= 86400) ? (PLUS_TIME / 86400) . ' ' . $plusText['days'] : (PLUS_TIME / 3600) . ' ' . $plusText['hours'];
            ?></td>
            <td class="cost"><img src="img/x.gif" class="gold" />10</td>
            <td class="act"><?php
                echo plusActionCell($golds['gold'], 10, $golds['plus'], 8, false);
            ?></td>
        </tr>

        <tr><td colspan="5" class="empty"></td></tr>

        <?php foreach ($plusBonuses as $plusBonus) {
            $until = (int) $golds[$plusBonus['field']];
        ?>
        <tr>
            <td class="man"><a href="#" onClick="return Popup(<?php echo (int) $plusBonus['popup']; ?>,6);"><img class="help" src="img/x.gif" /></a></td>
            <td class="desc">
                +<b>25</b>% <img class="<?php echo $plusBonus['css']; ?>" src="img/x.gif" />
                <?php echo $plusBonus['label']; ?><br />
                <span class="run"><?php
                    if ($until >= $date2) {
                        echo "<font color='#B3B3B3' size='1'>"
                           . formatRemainingTime($until, $date2) . '</font>';
                    }
                ?></span>
            </td>
            <td class="dur"><?php echo $plusDuration; ?></td>
            <td class="cost"><img src="img/x.gif" class="gold" />5</td>
            <td class="act"><span class="none"><?php
                echo plusActionCell($golds['gold'], 5, $until, $plusBonus['id'], $plusIsBanned);
            ?></span></td>
        </tr>
        <?php } ?>

        <!-- Finalizare instantanee a constructiilor -->
        <tr>
            <td class="man"><a href="#" onClick="return Popup(7,6);"><img class="help" src="img/x.gif" /></a></td>
            <td class="desc"><?php echo TZ_COMPLETE_CONSTRUCTION_ORDERS_AND_R; ?></td>
            <td class="dur"><?php echo NOW; ?></td>
            <td class="cost"><img src="img/x.gif" class="gold" />2</td>
            <td class="act"><span class="none"><?php
                if ((int) $golds['gold'] > 1) {
                    echo '<a href="plus.php?id=7" onclick="if(this.dataset.c) return false;'
                       . ' this.dataset.c=1; this.style.pointerEvents=\'none\';"><span>'
                       . GOLD_ON . '</span></a>';
                } else {
                    echo '<a href="plus.php?s=1"><span class="none">' . TOO_LITTLE_GOLD . '</span></a>';
                }
            ?></span></td>
        </tr>

        <!-- Negustorul NPC -->
        <tr>
            <td class="man"><a href="#" onClick="return Popup(8,6);"><img class="help" src="img/x.gif" /></a></td>
            <td class="desc"><?php echo TZ_N_1_1_TRADE_WITH_THE_NPC_MERCHANT; ?></td>
            <td class="dur"><?php echo NOW; ?></td>
            <td class="cost"><img src="img/x.gif" class="gold" />3</td>
            <td class="act"><span class="none"><?php
                if ((int) $golds['gold'] > 2) {
                    echo '<a href="build.php?gid=17&t=3"><span>' . NPC . '</span></a>';
                } else {
                    echo '<a href="plus.php?s=1"><span class="none">' . TOO_LITTLE_GOLD . '</span></a>';
                }
            ?></span></td>
        </tr>

    </tbody>
</table>

<?php
?>
