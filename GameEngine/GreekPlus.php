<?php

/**
 * Greek.sa literal markup helpers for plus.php (MaxB HTML structure).
 */

class GreekPlus
{
    public static function isGreekNav()
    {
        return !empty($GLOBALS['gkShell'])
            && function_exists('tz_is_rtl_lang')
            && tz_is_rtl_lang();
    }

    public static function isGreekPlusUi()
    {
        return !empty($GLOBALS['gkPlusLiteralPage'])
            && self::isGreekNav();
    }

    public static function navPageTitle($id, $uri = 'plus.php')
    {
        $id = (int) $id;
        if ($uri === 'a2b2.php') {
            return defined('REG_PLUS_HISTORY') ? REG_PLUS_HISTORY : 'عمليات سابقة';
        }
        if ($id === 0 || $id === 1 || $id >= 100) {
            return defined('REG_PLUS_CHARGE') ? REG_PLUS_CHARGE : 'شحن';
        }
        if ($id === 2) {
            return defined('REG_PLUS_FEATURES') ? REG_PLUS_FEATURES : 'المميزات';
        }
        if ($id === 3 || ($id >= 6 && $id <= 15)) {
            return defined('REG_PLUS_BALANCE') ? REG_PLUS_BALANCE : 'الرصيد';
        }
        if ($id === 4) {
            return defined('FAQ') ? FAQ : 'الأسئلة الشائعة';
        }
        if ($id === 5) {
            return defined('REG_PLUS_EARN') ? REG_PLUS_EARN : 'كسب الذهب';
        }
        if ($id === 16) {
            return defined('REG_PLUS_TRANSFER') ? REG_PLUS_TRANSFER : 'تحويل الذهب';
        }

        return defined('REG_PLUS_GOLD_TITLE') ? REG_PLUS_GOLD_TITLE : 'الذهب';
    }

    public static function menuOpen($pageTitle, $id, $uri)
    {
        $GLOBALS['gkPlusNavOpen'] = true;
        $tabs = array(
            array('href' => 'plus.php', 'label' => 'شحن', 'sel' => ($id === 0 || $id === 1 || $id >= 100)),
            array('href' => 'plus.php?id=2', 'label' => 'المميزات', 'sel' => ($id === 2)),
            array('href' => 'plus.php?id=3', 'label' => 'الرصيد', 'sel' => ($id === 3 || ($id >= 6 && $id <= 15))),
            array('href' => 'plus.php?id=5', 'label' => 'كسب الذهب', 'sel' => ($id === 5)),
            array('href' => 'plus.php?id=16', 'label' => 'تحويل الذهب', 'sel' => ($id === 16)),
            array('href' => 'plus.php?id=4', 'label' => defined('FAQ') ? FAQ : 'الأسئلة', 'sel' => ($id === 4)),
            array('href' => 'a2b2.php', 'label' => 'عمليات سابقة', 'sel' => ($uri === 'a2b2.php')),
        );

        echo '<div id="BP">';
        $bodClass = 'Bod gk-plus-content';
        if ($id === 2 || $id === 0 || $id === 1 || $id >= 100 || $uri === 'a2b2.php') {
            $bodClass .= ' gk-plus-text-rtl';
        }
        echo '<div class="' . $bodClass . '">';
        echo '<div class="PaNa">' . htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . '</div>';
        echo '<span class="BAR5"><span class="c-row">';
        foreach ($tabs as $tab) {
            $cls = $tab['sel'] ? ' class="-cho"' : '';
            echo '<a href="' . htmlspecialchars($tab['href'], ENT_QUOTES, 'UTF-8') . '"' . $cls . '>'
                . htmlspecialchars($tab['label'], ENT_QUOTES, 'UTF-8') . '</a>';
        }
        echo '</span></span>';
    }

    public static function menuClose()
    {
        if (empty($GLOBALS['gkPlusNavOpen'])) {
            return;
        }
        echo '</div></div>';
        $GLOBALS['gkPlusNavOpen'] = false;
    }

    public static function goldBalance($amount)
    {
        $amount = number_format((int) $amount);
        echo '<b class="diR">انت تملك حاليا ' . $amount . ' <p class="Rs x7" title="'
            . (defined('GOLD') ? GOLD : 'ذهب') . '"></p></b>';
    }

    public static function actionLink($gold, $cost, $until, $linkId, $getLabel = null, $banned = false)
    {
        if ($banned) {
            return '<a href="banned.php">' . (defined('TOO_LITTLE_GOLD') ? TOO_LITTLE_GOLD : '') . '</a>';
        }
        if ((int) $gold < (int) $cost) {
            return '<a href="plus.php?s=1">' . (defined('TOO_LITTLE_GOLD') ? TOO_LITTLE_GOLD : '') . '</a>';
        }
        if ($getLabel !== null && (int) $until <= time()) {
            $label = $getLabel;
        } else {
            $label = ((int) $until > time()) ? (defined('EXTEND') ? EXTEND : 'تمديد') : (defined('ACTIVATE') ? ACTIVATE : 'تفعيل');
        }
        $guard = ' onclick="if(this.dataset.c)return false;this.dataset.c=1;"';
        return '<a href="plus.php?id=' . (int) $linkId . '"' . $guard . '>' . $label . '</a>';
    }

    public static function goldCell($cost)
    {
        return (int) $cost . '<p class="Rs x7" title="' . (defined('GOLD') ? GOLD : 'ذهب') . '"></p>';
    }

    public static function sepRow($cols = 4)
    {
        return '<tr><th colspan="' . (int) $cols . '"><br></th></tr>';
    }
}
