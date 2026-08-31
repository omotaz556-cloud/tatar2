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
            $rptHref = 'berichte.php?t=3' . ($rptVal > 0 ? '&f=' . $rptVal : '');
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

    /**
     * Prev/next arrows for single-report view.
     */
    public static function reportNav($noticeId)
    {
        global $database, $session;

        $noticeId = (int) $noticeId;
        if ($noticeId <= 0 || empty($session->uid)) {
            return;
        }

        $list = $database->getNotice((int) $session->uid);
        if (!is_array($list) || !$list) {
            return;
        }

        $ids = array();
        foreach ($list as $row) {
            if (isset($row['id'])) {
                $ids[] = (int) $row['id'];
            }
        }

        $pos = array_search($noticeId, $ids, true);
        if ($pos === false) {
            return;
        }

        $qs = '';
        if (isset($_GET['t']) && (int) $_GET['t'] > 0) {
            $qs .= '&amp;t=' . (int) $_GET['t'];
        }
        if (isset($_GET['f']) && (int) $_GET['f'] > 0) {
            $qs .= '&amp;f=' . (int) $_GET['f'];
        }

        echo '<div class="gk-rpt-nav">';
        if ($pos > 0) {
            $prevId = $ids[$pos - 1];
            echo '<a class="gk-rpt-nav-prev" href="berichte.php?id=' . $prevId . $qs . '" title="'
                . htmlspecialchars(defined('TZ_BACK') ? TZ_BACK : 'رجوع', ENT_QUOTES, 'UTF-8') . '">&laquo;</a>';
        }
        if ($pos < count($ids) - 1) {
            $nextId = $ids[$pos + 1];
            echo '<a class="gk-rpt-nav-next" href="berichte.php?id=' . $nextId . $qs . '" title="'
                . htmlspecialchars(defined('TZ_NEXT') ? TZ_NEXT : 'التالي', ENT_QUOTES, 'UTF-8') . '">&raquo;</a>';
        }
        echo '</div>';
    }

    /**
     * Greek report header rows (subject + date).
     *
     * @param array $readingNotice
     * @param array $date procMtime() result (unused; kept for callers)
     */
    public static function reportHeadRows($readingNotice, $date)
    {
        $subjectLabel = defined('SUBJECT') ? SUBJECT : 'الموضوع';
        $dateLabel = defined('DATE') ? DATE : 'التاريخ';

        $topic = function_exists('tz_rpt_format_head_subject_html')
            ? tz_rpt_format_head_subject_html($readingNotice)
            : htmlspecialchars(tz_loc_topic($readingNotice['topic'] ?? ''), ENT_QUOTES, 'UTF-8');

        $reportTime = (int) ($readingNotice['time'] ?? 0);
        $dateMain = self::formatReportDateMain($reportTime);

        $startTs = defined('COMMENCE') ? (int) COMMENCE : 0;
        if ($startTs <= 0 && defined('START_DATE') && defined('START_TIME')) {
            $startTs = (int) strtotime(START_DATE . ' ' . START_TIME);
        }
        $worldDay = ($startTs > 0 && $reportTime > 0)
            ? max(1, (int) floor(($reportTime - $startTs) / 86400) + 1)
            : 0;
        $worldDayLabel = defined('TZ_RPT_WORLD_DAY') ? TZ_RPT_WORLD_DAY : 'اليوم';

        $travelMin = self::reportTravelMinutes($readingNotice);
        $travelLabel = defined('TZ_RPT_TRAVEL') ? TZ_RPT_TRAVEL : 'السفر';
        $minAbbr = defined('TZ_RPT_MIN_ABBR') ? TZ_RPT_MIN_ABBR : 'د';

        echo '<tr class="gk-rpt-head-subject">';
        echo '<td class="gk-rpt-label">' . htmlspecialchars($subjectLabel, ENT_QUOTES, 'UTF-8') . ':</td>';
        echo '<td class="gk-rpt-subject-val">' . $topic . '</td>';
        echo '<td class="gk-rpt-subject-meta">';
        if ($travelMin > 0) {
            echo htmlspecialchars($travelLabel, ENT_QUOTES, 'UTF-8') . ' ' . (int) $travelMin . ' ' . htmlspecialchars($minAbbr, ENT_QUOTES, 'UTF-8');
        }
        echo '</td>';
        echo '</tr>';
        echo '<tr class="gk-rpt-head-date">';
        echo '<td class="gk-rpt-label">' . htmlspecialchars($dateLabel, ENT_QUOTES, 'UTF-8') . ':</td>';
        echo '<td class="gk-rpt-date-val">' . htmlspecialchars($dateMain, ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td class="gk-rpt-date-meta">';
        if ($worldDay > 0) {
            echo htmlspecialchars($worldDayLabel, ENT_QUOTES, 'UTF-8') . ' ' . (int) $worldDay;
        }
        echo '</td>';
        echo '</tr>';
    }

    /**
     * @param int $reportTime Unix timestamp
     */
    public static function formatReportDateMain($reportTime)
    {
        $reportTime = (int) $reportTime;
        if ($reportTime <= 0) {
            return '';
        }

        $zone = self::playerDateTimeZone();
        $dt = new DateTime('@' . $reportTime);
        $dt->setTimezone($zone);

        $main = $dt->format('Y/m/d') . ' - ' . $dt->format('H:i:s');
        $offsetHr = (int) round($dt->getOffset() / 3600);
        if ($offsetHr !== 0) {
            $abbr = defined('TZ_RPT_HOUR_ABBR') ? TZ_RPT_HOUR_ABBR : 'س';
            $main .= ' (' . $offsetHr . ' ' . $abbr . ')';
        }

        return $main;
    }

    /**
     * One-way travel duration in minutes for the report header.
     *
     * @param array $notice
     */
    public static function reportTravelMinutes($notice)
    {
        global $database, $generator;

        $data = (string) ($notice['data'] ?? '');
        if ($data === '' || !isset($database)) {
            return 0;
        }

        $parts = explode(',', $data);
        $fromWref = (int) ($parts[1] ?? 0);
        $toWref = (int) ($parts[29] ?? 0);
        if ($toWref <= 0) {
            $toWref = (int) ($notice['wref'] ?? 0);
        }
        $endTime = (int) ($notice['time'] ?? 0);

        if ($fromWref <= 0 || $toWref <= 0 || $endTime <= 0) {
            return 0;
        }

        $fromWref = (int) $fromWref;
        $toWref = (int) $toWref;
        $endTime = (int) $endTime;

        $q = 'SELECT starttime, endtime FROM ' . TB_PREFIX . 'movement'
            . ' WHERE `from` = ' . $fromWref
            . ' AND `to` = ' . $toWref
            . ' AND endtime = ' . $endTime
            . ' ORDER BY moveid DESC LIMIT 1';
        $result = $database->query($q);
        if ($result && ($row = mysqli_fetch_assoc($result))) {
            $duration = (int) $row['endtime'] - (int) $row['starttime'];
            if ($duration > 0) {
                return max(1, (int) round($duration / 60));
            }
        }

        if (!isset($generator) || !is_object($generator)) {
            return 0;
        }

        $fromCoor = $database->getCoor($fromWref);
        $toCoor = $database->getCoor($toWref);
        if (empty($fromCoor) || empty($toCoor)) {
            return 0;
        }

        $seconds = (int) $generator->procDistanceTime($toCoor, $fromCoor, 1, 0, $fromWref);
        if ($seconds <= 0) {
            return 0;
        }

        return max(1, (int) round($seconds / 60));
    }

    private static function playerDateTimeZone()
    {
        global $session, $generator;

        if (isset($generator) && is_object($generator)) {
            $tzPref = (isset($session) && isset($session->userinfo['timezone']))
                ? $session->userinfo['timezone'] : null;
            $ref = new ReflectionMethod($generator, 'resolveUserTimeZone');
            if ($ref->isPrivate()) {
                $ref->setAccessible(true);
            }
            return $ref->invoke($generator, $tzPref);
        }

        return new DateTimeZone(date_default_timezone_get());
    }

    public static function reportFooter($readingNotice)
    {
        $id = (int) ($readingNotice['id'] ?? 0);
        if ($id <= 0) {
            return;
        }

        $href = 'berichte.php?id=' . $id;
        if (isset($_GET['t']) && (int) $_GET['t'] > 0) {
            $href .= '&t=' . (int) $_GET['t'];
        }
        if (isset($_GET['f']) && (int) $_GET['f'] > 0) {
            $href .= '&f=' . (int) $_GET['f'];
        }

        if (defined('HOMEPAGE') && HOMEPAGE !== '') {
            $base = rtrim((string) HOMEPAGE, '/');
            if (preg_match('#^https?://#i', $base)) {
                $href = $base . '/' . ltrim($href, '/');
            }
        }

        $label = defined('TZ_RPT_LINK') ? TZ_RPT_LINK : 'رابط التقرير';
        $copyTitle = defined('TZ_CLICK_TO_COPY') ? TZ_CLICK_TO_COPY : 'انقر للنسخ';
        $copyDone = defined('TZ_COPIED') ? TZ_COPIED : 'تم نسخ الرابط.';

        echo '<p class="gk-rpt-permalink">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . ': ';
        echo '<span class="gk-rpt-permalink-link" role="button" tabindex="0"'
            . ' title="' . htmlspecialchars($copyTitle, ENT_QUOTES, 'UTF-8') . '"'
            . ' data-copy="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '"'
            . ' data-copied-label="' . htmlspecialchars($copyDone, ENT_QUOTES, 'UTF-8') . '"'
            . ' onclick="gkCopyReportLink(this)"'
            . ' onkeydown="if(event.key===\'Enter\'||event.key===\' \'){event.preventDefault();gkCopyReportLink(this);}">'
            . htmlspecialchars($href, ENT_QUOTES, 'UTF-8')
            . '</span></p>';

        static $gkCopyReportLinkScript = false;
        if (!$gkCopyReportLinkScript) {
            $gkCopyReportLinkScript = true;
            echo '<script>
function gkCopyReportLink(el) {
    if (!el) return;
    var text = el.getAttribute("data-copy") || el.textContent || "";
    var done = el.getAttribute("data-copied-label") || "Copied";
    var ok = function() {
        el.classList.add("gk-rpt-copied");
        var prevTitle = el.getAttribute("title") || "";
        el.setAttribute("title", done);
        window.setTimeout(function() {
            el.classList.remove("gk-rpt-copied");
            el.setAttribute("title", prevTitle);
        }, 1600);
    };
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(ok).catch(function() {
            gkCopyReportLinkFallback(text, el, ok);
        });
    } else {
        gkCopyReportLinkFallback(text, el, ok);
    }
}
function gkCopyReportLinkFallback(text, el, ok) {
    var ta = document.createElement("textarea");
    ta.value = text;
    ta.setAttribute("readonly", "");
    ta.style.position = "fixed";
    ta.style.left = "-9999px";
    document.body.appendChild(ta);
    ta.select();
    try {
        if (document.execCommand("copy")) ok();
    } catch (e) {}
    document.body.removeChild(ta);
}
</script>';
        }
    }
}
