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

    /**
     * Suggested gold for "buy N of each resource" on plus balance (greek.sa green hint).
     * Uses master-builder queue deficits; if none, suggests filling warehouse/granary.
     */
    public static function suggestedGoldForResourcePurchase($village, $database = null)
    {
        if (empty($village) || !is_object($village)) {
            return defined('GOLD_RES_MIN_GOLD') ? max(1, (int) GOLD_RES_MIN_GOLD) : 1;
        }

        if ($database === null) {
            global $database;
        }

        $unit = defined('GOLD_RES_UNIT') ? max(1, (int) GOLD_RES_UNIT) : 20000;
        $maxGoldCfg = defined('GOLD_RES_MAX_GOLD') ? max(0, (int) GOLD_RES_MAX_GOLD) : 0;

        $wood = (int) floor($village->awood);
        $clay = (int) floor($village->aclay);
        $iron = (int) floor($village->airon);
        $crop = (int) floor($village->acrop);
        $maxStore = (int) $village->maxstore;
        $maxCrop = (int) $village->maxcrop;

        $needed = array('wood' => 0, 'clay' => 0, 'iron' => 0, 'crop' => 0);

        if ($database && !empty($village->wid)) {
            $masterJobs = $database->getMasterJobs((int) $village->wid);
            foreach ($masterJobs as $job) {
                $type = (int) $job['type'];
                $level = (int) $job['level'];
                $bid = isset($GLOBALS['bid' . $type]) ? $GLOBALS['bid' . $type] : null;
                if (!$bid || !isset($bid[$level])) {
                    continue;
                }
                $req = $bid[$level];
                $needed['wood'] += (int) $req['wood'];
                $needed['clay'] += (int) $req['clay'];
                $needed['iron'] += (int) $req['iron'];
                $needed['crop'] += (int) $req['crop'];
            }
        }

        $suggest = 0;
        $queueTotal = $needed['wood'] + $needed['clay'] + $needed['iron'] + $needed['crop'];

        if ($queueTotal > 0) {
            $fromQueue = max(
                self::goldUnitsForShortage($needed['wood'], $wood, $maxStore, $unit),
                self::goldUnitsForShortage($needed['clay'], $clay, $maxStore, $unit),
                self::goldUnitsForShortage($needed['iron'], $iron, $maxStore, $unit),
                self::goldUnitsForShortage($needed['crop'], $crop, $maxCrop, $unit)
            );
            $suggest = max($suggest, $fromQueue);
        } else {
            $fill = max(
                self::goldUnitsForShortage($maxStore, $wood, $maxStore, $unit),
                self::goldUnitsForShortage($maxStore, $clay, $maxStore, $unit),
                self::goldUnitsForShortage($maxStore, $iron, $maxStore, $unit),
                self::goldUnitsForShortage($maxCrop, $crop, $maxCrop, $unit)
            );
            $suggest = max($suggest, $fill);
        }

        global $session;
        if (isset($session) && isset($session->gold)) {
            $suggest = min($suggest, (int) $session->gold);
        }
        if ($maxGoldCfg > 0) {
            $suggest = min($suggest, $maxGoldCfg);
        }

        return max(0, (int) $suggest);
    }

    private static function goldUnitsForShortage($required, $current, $capacity, $unit)
    {
        $shortage = max(0, (int) $required - (int) $current);
        $room = max(0, (int) $capacity - (int) $current);
        $shortage = min($shortage, $room);

        if ($shortage <= 0) {
            return 0;
        }

        return (int) ceil($shortage / max(1, (int) $unit));
    }

    /**
     * Live Plus/bonus countdown (greek.sa <c> tag) — updated every second via gk_plus_countdown.js.
     *
     * @param int   $endTimestamp Unix expiry
     * @param int   $nowTimestamp Server time at page render
     * @param array $labels       remaining, until, seconds, days, hours, mins
     */
    public static function renderPlusCountdown($endTimestamp, $nowTimestamp, array $labels)
    {
        $end = (int) $endTimestamp;
        $now = (int) $nowTimestamp;

        if ($end <= $now) {
            return '';
        }

        $untilHms = date('H:i:s', $end);
        $labelsJson = json_encode($labels, JSON_UNESCAPED_UNICODE);
        if ($labelsJson === false) {
            $labelsJson = '{}';
        }

        return '<c class="gk-plus-countdown" data-end="' . $end . '" data-now="' . $now . '"'
            . ' data-until-hms="' . htmlspecialchars($untilHms, ENT_QUOTES, 'UTF-8') . '"'
            . ' data-l10n="' . htmlspecialchars($labelsJson, ENT_QUOTES, 'UTF-8') . '">'
            . self::formatPlusCountdownInner($end, $now, $labels, $untilHms)
            . '</c>';
    }

    private static function formatPlusCountdownInner($end, $now, array $labels, $untilHms)
    {
        $remaining = (int) $end - (int) $now;

        if ($remaining <= 0) {
            return '';
        }

        $days = intdiv($remaining, 86400);
        $remaining %= 86400;
        $hours = intdiv($remaining, 3600);
        $remaining %= 3600;
        $mins = intdiv($remaining, 60);
        $secs = $remaining % 60;

        return $labels['remaining'] . ': <b>' . $days . '</b> ' . $labels['days']
            . ' <b>' . $hours . '</b> ' . $labels['hours']
            . ' <b>' . $mins . '</b> ' . $labels['mins']
            . ' <b>' . $secs . '</b> ' . $labels['seconds']
            . ' (' . $labels['until'] . ' ' . $untilHms . ')';
    }
}
