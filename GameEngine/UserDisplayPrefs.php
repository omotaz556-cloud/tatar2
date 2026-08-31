<?php
/**
 * Per-user display preferences (mobile mode, timer refresh, invert colors,
 * stats format, night mode). Shared by classic menu.tpl and Greek shell.
 */

if (!function_exists('tz_user_display_prefs_values')) {
    /**
     * @return array{mobile_mode:int,timer_refresh:int,invert_colors:int,stats_format:int,night_mode:int}
     */
    function tz_user_display_prefs_values($session)
    {
        $ui = (isset($session) && is_object($session) && is_array($session->userinfo ?? null))
            ? $session->userinfo
            : array();

        return array(
            'mobile_mode' => (int) ($ui['mobile_mode'] ?? 0),
            'timer_refresh' => !empty($ui['timer_refresh']) ? 1 : 0,
            'invert_colors' => !empty($ui['invert_colors']) ? 1 : 0,
            'stats_format' => (int) ($ui['stats_format'] ?? 0),
            'night_mode' => (int) ($ui['night_mode'] ?? 0),
            'upgrade_redirect' => ((int) ($ui['upgrade_redirect'] ?? 0) === 1) ? 1 : 0,
        );
    }
}

if (!function_exists('tz_user_display_prefs_render')) {
    /**
     * Output CSS + JS that apply saved display preferences in the browser.
     */
    function tz_user_display_prefs_render($session)
    {
        if (!isset($session) || !is_object($session) || empty($session->logged_in)) {
            return;
        }

        $prefs = tz_user_display_prefs_values($session);
        ?>
<style type="text/css">
html.tz-mobile-mode body{min-width:0 !important;}
html.tz-mobile-mode #mid{width:100% !important;min-width:0 !important;}
html.tz-mobile-mode #side_navi,html.tz-mobile-mode #content,html.tz-mobile-mode #side_info{float:none !important;width:100% !important;max-width:100% !important;box-sizing:border-box;}
html.tz-mobile-mode body.pg-gk{min-width:0 !important;}
html.tz-mobile-mode body.pg-gk .gk-shell,
html.tz-mobile-mode body.pg-gk .gk-shell > tbody,
html.tz-mobile-mode body.pg-gk .gk-shell > tbody > tr{display:block !important;width:100% !important;max-width:100% !important;}
html.tz-mobile-mode body.pg-gk .gk-shell > tbody > tr > td{display:block !important;width:100% !important;max-width:100% !important;box-sizing:border-box !important;}
html.tz-invert-colors body{filter:invert(1) hue-rotate(180deg);}
html.tz-invert-colors body.pg-gk{filter:invert(1) hue-rotate(180deg);}
html.tz-dark-mode body{background:#20252b !important;color:#e7ecef !important;}
html.tz-dark-mode #mid,html.tz-dark-mode #content,html.tz-dark-mode #side_navi,html.tz-dark-mode #side_info{background:#20252b !important;color:#e7ecef !important;}
html.tz-dark-mode table,html.tz-dark-mode td,html.tz-dark-mode th{background-color:#2b3239 !important;color:#e7ecef !important;border-color:#46515a !important;}
html.tz-stats-compact body #content.statistics table,
html.tz-stats-compact body.pg-gk.pg-statistics #content.statistics table,
html.tz-stats-compact body.pg-gk.pg-statistics .gk-stat-body table{font-size:11px !important;}
html.tz-stats-compact body #content.statistics th,
html.tz-stats-compact body #content.statistics td,
html.tz-stats-compact body.pg-gk.pg-statistics #content.statistics th,
html.tz-stats-compact body.pg-gk.pg-statistics #content.statistics td,
html.tz-stats-compact body.pg-gk.pg-statistics .gk-stat-body th,
html.tz-stats-compact body.pg-gk.pg-statistics .gk-stat-body td{padding:3px 5px !important;}
html.tz-stats-classic body #content.statistics table,
html.tz-stats-classic body.pg-gk.pg-statistics #content.statistics table,
html.tz-stats-classic body.pg-gk.pg-statistics .gk-stat-body table{font-size:14px !important;}
html.tz-stats-classic body #content.statistics th,
html.tz-stats-classic body #content.statistics td,
html.tz-stats-classic body.pg-gk.pg-statistics #content.statistics th,
html.tz-stats-classic body.pg-gk.pg-statistics #content.statistics td,
html.tz-stats-classic body.pg-gk.pg-statistics .gk-stat-body th,
html.tz-stats-classic body.pg-gk.pg-statistics .gk-stat-body td{padding:9px 10px !important;line-height:1.5;}
</style>
<script type="text/javascript">
(function () {
    var root = document.documentElement;
    var mobileMode = <?php echo (int) $prefs['mobile_mode']; ?>;
    var timerRefresh = <?php echo $prefs['timer_refresh'] ? 'true' : 'false'; ?>;
    var invertColors = <?php echo $prefs['invert_colors'] ? 'true' : 'false'; ?>;
    var statsFormat = <?php echo (int) $prefs['stats_format']; ?>;
    var nightMode = <?php echo (int) $prefs['night_mode']; ?>;

    if (mobileMode === 2 || (mobileMode === 0 && window.innerWidth <= 700)) {
        root.className += ' tz-mobile-mode';
    }
    if (invertColors) {
        root.className += ' tz-invert-colors';
    }
    if (nightMode === 2) {
        root.className += ' tz-dark-mode';
    }
    if (statsFormat === 2 && /(?:^|\/)statistiken\.php(?:$|\?)/.test(window.location.pathname + window.location.search)) {
        root.className += ' tz-stats-compact';
    }
    if (statsFormat === 1 && /(?:^|\/)statistiken\.php(?:$|\?)/.test(window.location.pathname + window.location.search)) {
        root.className += ' tz-stats-classic';
    }
    root.setAttribute('data-stats-format', String(statsFormat));

    if (timerRefresh) {
        window.setInterval(function () {
            var timers = document.querySelectorAll('[id^="timer"]');
            for (var i = 0; i < timers.length; i++) {
                if (/^(0+:)?00:00(?::00)?$/.test((timers[i].textContent || '').trim())) {
                    window.location.reload();
                    return;
                }
            }
        }, 1000);
    }
})();
</script>
        <?php
    }
}

if (!function_exists('tz_user_display_prefs_refresh_session')) {
    /**
     * Reload the logged-in user row into session after preference updates.
     */
    function tz_user_display_prefs_refresh_session($session, $database)
    {
        if (!isset($session) || !is_object($session) || empty($session->uid)) {
            return;
        }

        $uid = (int) $session->uid;
        $username = (string) ($_SESSION['username'] ?? $session->username ?? '');

        if (isset($database) && is_object($database) && method_exists($database, 'clearUserArrayCache')) {
            $database->clearUserArrayCache($uid, $username);
        }

        $fresh = (isset($database) && is_object($database) && method_exists($database, 'getUserArray'))
            ? $database->getUserArray($uid, 1, false)
            : null;

        if (!is_array($fresh)) {
            return;
        }

        if ($username !== '') {
            $cacheKeyUser = 'cache_user_' . $username;
            $_SESSION[$cacheKeyUser] = array(
                'time' => time(),
                'data' => $fresh,
            );
        }

        $session->userinfo = $fresh;
    }
}
