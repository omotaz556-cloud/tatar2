<?php

/**
 * Greek.sa reports page markup (berichte.php).
 */
class GreekBerichte
{
    public static function isGreekBerichteUi()
    {
        return !empty($GLOBALS['gkBerichteLiteralPage'])
            && !empty($GLOBALS['gkShell'])
            && function_exists('tz_is_rtl_lang')
            && tz_is_rtl_lang();
    }

    /**
     * @param int $tab Current ?t= filter (0 = all)
     */
    public static function menuOpen($tab)
    {
        $tab = (int) $tab;
        global $session;

        $tabs = array(
            array('href' => 'berichte.php', 'label' => defined('ALL') ? ALL : 'الكل', 't' => 0),
            array('href' => 'berichte.php?t=2', 'label' => defined('TZ_TRADE') ? TZ_TRADE : 'التجارة', 't' => 2),
            array(
                'href' => 'berichte.php?t=1',
                'label' => defined('TZ_RT_REINFORCEMENT') ? TZ_RT_REINFORCEMENT : (defined('REINFORCEMENT') ? REINFORCEMENT : 'تعزيزات'),
                't' => 1,
            ),
            array('href' => 'berichte.php?t=3', 'label' => defined('TZ_RPT_ATTACK_TAB') ? TZ_RPT_ATTACK_TAB : (defined('TZ_ATTACKS') ? TZ_ATTACKS : 'الهجوم'), 't' => 3),
            array('href' => 'berichte.php?t=6', 'label' => defined('TZ_RPT_DEFENSE') ? TZ_RPT_DEFENSE : 'الدفاع', 't' => 6),
            array('href' => 'berichte.php?t=7', 'label' => defined('TZ_RPT_SCOUT_TAB') ? TZ_RPT_SCOUT_TAB : 'التجسس', 't' => 7),
            array('href' => 'berichte.php?t=4', 'label' => defined('TZ_OTHER') ? TZ_OTHER : 'أخرى', 't' => 4),
            array('href' => 'berichte.php?t=8', 'label' => defined('TZ_RPT_MISSION') ? TZ_RPT_MISSION : 'مهمة', 't' => 8),
        );

        if (!empty($session->plus)) {
            $tabs[] = array(
                'href' => 'berichte.php?t=5',
                'label' => defined('ARCHIVE') ? ARCHIVE : 'الأرشيف',
                't' => 5,
            );
        }

        echo '<div id="BP"><div class="Bod">';
        echo '<div class="PaNa">' . htmlspecialchars(defined('REPORTS') ? REPORTS : 'التقارير', ENT_QUOTES, 'UTF-8') . '</div>';
        echo '<span class="BAR5"><span class="c-row">';
        foreach ($tabs as $item) {
            $cls = ((int) $item['t'] === $tab) ? ' class="-cho"' : '';
            echo '<a href="' . htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') . '"' . $cls . '>'
                . htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') . '</a>';
        }
        echo '</span></span>';
    }

    public static function attackFilterOpen()
    {
        if (!isset($_GET['t']) || (int) $_GET['t'] !== 3) {
            return;
        }

        $rptFilters = array(
            0 => defined('TZ_RPT_ALL_RESULTS') ? TZ_RPT_ALL_RESULTS : 'الكل',
            1 => defined('TZ_RPT_F_WON_NOLOSS') ? TZ_RPT_F_WON_NOLOSS : 'فوز بدون خسائر',
            2 => defined('TZ_RPT_F_WON_LOSS') ? TZ_RPT_F_WON_LOSS : 'فوز بخسائر',
            3 => defined('TZ_RPT_F_LOST') ? TZ_RPT_F_LOST : 'هزيمة',
        );

        $rptCurrent = isset($_GET['f']) ? (int) $_GET['f'] : 0;

        echo '<span class="BAR5 gk-rpt-filter"><span class="c-row">';
        foreach ($rptFilters as $rptVal => $rptLabel) {
            $rptHref = 'berichte.php?t=3' . ($rptVal > 0 ? '&amp;f=' . $rptVal : '');
            $cls = ($rptCurrent === $rptVal) ? ' class="-cho"' : '';
            echo '<a href="' . htmlspecialchars($rptHref, ENT_QUOTES, 'UTF-8') . '"' . $cls . '>'
                . htmlspecialchars($rptLabel, ENT_QUOTES, 'UTF-8') . '</a>';
        }
        echo '</span></span>';
    }

    public static function readAllLink($tab)
    {
        $href = 'berichte.php?readall=1';
        if ((int) $tab > 0) {
            $href .= '&amp;t=' . (int) $tab;
        }
        if ((int) $tab === 3 && !empty($_GET['f'])) {
            $href .= '&amp;f=' . (int) $_GET['f'];
        }
        $label = defined('TZ_MARK_ALL_READ') ? TZ_MARK_ALL_READ : 'اجعلها مقروءة';
        echo '<p class="gk-berichte-readall"><a href="' . $href . '">'
            . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</a></p>';
    }

    public static function menuClose()
    {
        echo '</div></div>';
    }
}
