<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : units_common.tpl                                          ##
##  Type           : Rally Point Troop Selection Form (all tribes)             ##
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

$a2bTribe = isset($session->tribe) ? (int) $session->tribe : 1;
if ($a2bTribe < 1 || $a2bTribe > 9) {
    $a2bTribe = 1;
}

$a2bFirstUnit = ($a2bTribe - 1) * 10 + 1;
$a2bShowHero = ($a2bTribe !== 5) && !empty($village->unitarray['hero']);
$rallyFieldId = isset($rallyFieldId) ? (int) $rallyFieldId : 39;
$a2bSendPage = true;
$id = $rallyFieldId;

$a2bLastKey = 'a2b_last_' . (int) $village->wid;
$a2bLastSent = (isset($_SESSION[$a2bLastKey]) && is_array($_SESSION[$a2bLastKey]))
    ? $_SESSION[$a2bLastKey]
    : array();

if (!function_exists('a2b_last_sent_amount')) {
    function a2b_last_sent_amount($slot, $lastSent)
    {
        return isset($lastSent['t' . (int) $slot]) ? (int) $lastSent['t' . (int) $slot] : 0;
    }
}

if (!function_exists('a2b_troop_row')) {
    function a2b_troop_row($slot, $unitId, $isHero = false)
    {
        global $village;

        $slot = (int) $slot;
        $unitId = (int) $unitId;
        $field = 't' . $slot;
        $lastSent = isset($GLOBALS['a2bLastSent']) ? $GLOBALS['a2bLastSent'] : array();
        $last = a2b_last_sent_amount($slot, $lastSent);

        if ($isHero) {
            $have = (int) $village->unitarray['hero'];
            $label = defined('TZ_HERO') ? TZ_HERO : 'Hero';
            $unitClass = 'uhero';
        } else {
            $have = isset($village->unitarray['u' . $unitId])
                ? (int) $village->unitarray['u' . $unitId] : 0;
            $label = defined('U' . $unitId) ? constant('U' . $unitId) : ('u' . $unitId);
            $unitClass = 'u' . $unitId;
        }

        $inputValue = '';
        if (!empty($GLOBALS['a2bForm']) && is_object($GLOBALS['a2bForm'])) {
            $posted = $GLOBALS['a2bForm']->getValue($field);
            if ($posted !== '' && $posted !== null) {
                $inputValue = (string) (int) $posted;
            }
        }

        echo '<tr>';
        echo '<td class="ico">';
        echo '<img class="unit ' . htmlspecialchars($unitClass, ENT_QUOTES, 'UTF-8') . '" src="img/x.gif"'
           . ' title="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '"'
           . ' alt="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '"'
           . ' onclick="document.snd.' . $field . '.value=\'\'; return false;">';
        echo '</td>';
        echo '<td class="send">';
        echo '<input class="text" name="' . $field . '" value="' . htmlspecialchars($inputValue, ENT_QUOTES, 'UTF-8') . '" maxlength="6" type="text"'
           . ($have <= 0 ? ' disabled="disabled"' : '') . '>';
        echo '</td>';
        echo '<td class="hav">';
        if ($have > 0) {
            echo '<a href="#" onclick="document.snd.' . $field . '.value=' . $have
               . '; return false;">' . $have . '</a>';
        } else {
            echo '<span class="none">0</span>';
        }
        echo '</td>';
        echo '<td class="last">';
        if ($last > 0) {
            echo $last;
        } else {
            echo '<span class="none">0</span>';
        }
        echo '</td>';
        echo '</tr>';
    }
}

$GLOBALS['a2bLastSent'] = $a2bLastSent;
$GLOBALS['a2bForm'] = isset($form) ? $form : null;
?>
<div id="build" class="gid16">
    <h1><?php echo RALLYPOINT; ?> <span class="level"><?php echo defined('BUILD_LEVEL_SHORT') ? BUILD_LEVEL_SHORT : LEVEL; ?> <?php echo (int) $village->resarray['f' . $rallyFieldId]; ?></span></h1>
    <div class="gk-build-intro">
        <a href="#" onclick="return Popup(16,4);" class="build_logo">
            <img class="g16" src="img/x.gif" alt="<?php echo RALLYPOINT; ?>" title="<?php echo RALLYPOINT; ?>">
        </a>
        <p class="build_desc"><?php echo RALLYPOINT_DESC; ?></p>
    </div>

    <?php include dirname(__DIR__) . '/Build/16_menu.tpl'; ?>

    <div class="gk-a2b-send-wrap">
    <form method="POST" name="snd" action="a2b.php">
        <input name="b" value="1" type="hidden">
        <div class="gk-a2b-send-inner">
            <div class="gk-a2b-col-troops">
                <table id="a2b_troops" class="gk-a2b-troops" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th colspan="4"><?php echo defined('TZ_RALLY_SEND_TROOPS') ? TZ_RALLY_SEND_TROOPS : SEND_TROOPS; ?></th>
                        </tr>
                        <tr>
                            <th class="ico"></th>
                            <th class="send"><?php echo TZ_A2B_SEND_COL; ?></th>
                            <th class="hav"><?php echo TZ_A2B_AVAILABLE; ?></th>
                            <th class="last"><?php echo TZ_A2B_LAST_SEND; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 1; $i <= 10; $i++) {
                            a2b_troop_row($i, $a2bFirstUnit + $i - 1);
                        }
                        if ($a2bShowHero) {
                            a2b_troop_row(11, 0, true);
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="gk-a2b-col-target">
