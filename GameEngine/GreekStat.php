<?php

/**
 * Greek.sa statistics markup (MaxB / greek.sa reference).
 */

class GreekStat
{
    public static function isGreekStatUi()
    {
        return !empty($GLOBALS['gkStatLiteralPage'])
            && !empty($GLOBALS['gkShell'])
            && function_exists('tz_is_rtl_lang')
            && tz_is_rtl_lang();
    }

    public static function menuOpen($statMenuId)
    {
        $statMenuId = (int) $statMenuId;
        $tabs = array(
            array(
                'href' => 'statistiken.php',
                'label' => defined('PLAYERS') ? PLAYERS : 'اللاعبون',
                'sel' => ($statMenuId === -1 || $statMenuId === 1 || $statMenuId === 7
                    || $statMenuId === 31 || $statMenuId === 32 || $statMenuId === 50
                    || ($statMenuId >= 11 && $statMenuId <= 19)),
            ),
            array(
                'href' => 'statistiken.php?id=4',
                'label' => defined('ALLIANCES') ? ALLIANCES : 'التحالفات',
                'sel' => ($statMenuId === 4 || ($statMenuId >= 41 && $statMenuId <= 43)),
            ),
            array(
                'href' => 'statistiken.php?id=2',
                'label' => defined('VILLAGES') ? VILLAGES : 'القرى',
                'sel' => ($statMenuId === 2),
            ),
            array(
                'href' => 'statistiken.php?id=8',
                'label' => defined('HEROES') ? HEROES : 'أبطال',
                'sel' => ($statMenuId === 8),
            ),
            array(
                'href' => 'statistiken.php?id=3',
                'label' => defined('MILESTONES') ? MILESTONES : 'قائمة لم',
                'sel' => ($statMenuId === 3),
            ),
            array(
                'href' => 'statistiken.php?id=99',
                'label' => defined('TZ_STAT_NATARS_TAB') ? TZ_STAT_NATARS_TAB : 'التتار',
                'sel' => ($statMenuId === 99),
            ),
            array(
                'href' => 'statistiken.php?id=0',
                'label' => defined('GENERAL') ? GENERAL : 'عام',
                'sel' => ($statMenuId === 0),
            ),
            array(
                'href' => 'statistiken.php?id=98',
                'label' => defined('NEWS') ? NEWS : 'أخبار',
                'sel' => ($statMenuId === 98),
            ),
        );

        echo '<div id="BP"><div class="Bod">';
        echo '<div class="PaNa">الإحصائيات</div>';
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
        echo '</div></div>';
    }
}
