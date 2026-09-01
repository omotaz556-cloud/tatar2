<?php

/**
 * Classic tatarwars-style portal shell (index / login / register).
 * Uses novaterra_classic compact.css — not the in-game Greek shell.
 */

if (!function_exists('tz_portal_gp')) {
    function tz_portal_gp()
    {
        return defined('SERVER_GP') ? SERVER_GP : 'gpack/novaterra_classic/';
    }
}

if (!function_exists('tz_portal_xgif')) {
    function tz_portal_xgif()
    {
        return 'img/x.gif';
    }
}

if (!function_exists('tz_portal_days_label')) {
    function tz_portal_days_label($days)
    {
        $days = (int) $days;
        if ($days <= 0) {
            return defined('LOGIN_DAY_ZERO') ? LOGIN_DAY_ZERO : 'اليوم';
        }
        if ($days === 1) {
            return defined('LOGIN_DAY_ONE') ? LOGIN_DAY_ONE : 'يوم';
        }
        if ($days === 2) {
            return defined('LOGIN_DAY_TWO') ? LOGIN_DAY_TWO : 'يومان';
        }
        if ($days >= 3 && $days <= 10) {
            return $days . ' ' . (defined('LOGIN_DAYS_FEW') ? LOGIN_DAYS_FEW : 'أيام');
        }

        return $days . ' ' . (defined('LOGIN_DAY_ONE') ? LOGIN_DAY_ONE : 'يوم');
    }
}

if (!function_exists('tz_portal_classic_stylesheet_tag')) {
    function tz_portal_classic_stylesheet_tag($relPath = '')
    {
        $tag = '';
        $root = dirname(__DIR__);

        $globalDisk = $root . '/css/global.css';
        if (is_file($globalDisk)) {
            $href = $relPath . 'css/global.css';
            $ver = (int) @filemtime($globalDisk);
            $tag .= "\n\t" . '<link href="' . htmlspecialchars($href, ENT_QUOTES)
                . '?v=' . $ver . '" rel="stylesheet" type="text/css" />';
        }

        $lang = defined('LANG') ? LANG : 'ar';
        $gp = tz_portal_gp();

        $compactDisk = $root . '/' . $gp . 'lang/' . $lang . '/compact.css';
        if (is_file($compactDisk)) {
            $href = $relPath . $gp . 'lang/' . $lang . '/compact.css';
            $ver = (int) @filemtime($compactDisk);
            $tag .= "\n\t" . '<link href="' . htmlspecialchars($href, ENT_QUOTES)
                . '?v=' . $ver . '" rel="stylesheet" type="text/css" />';
        }

        $compactB093 = $root . '/' . $gp . 'lang/' . $lang . '/compactb093.css';
        if (is_file($compactB093)) {
            $href = $relPath . $gp . 'lang/' . $lang . '/compactb093.css';
            $ver = (int) @filemtime($compactB093);
            $tag .= "\n\t" . '<link href="' . htmlspecialchars($href, ENT_QUOTES)
                . '?v=' . $ver . '" rel="stylesheet" type="text/css" />';
        }

        $langDisk = $root . '/' . $gp . 'lang/' . $lang . '/lang.css';
        if (is_file($langDisk)) {
            $href = $relPath . $gp . 'lang/' . $lang . '/lang.css';
            $tag .= "\n\t" . '<link href="' . htmlspecialchars($href, ENT_QUOTES)
                . '?portal1" rel="stylesheet" type="text/css" />';
        }

        $fontDisk = $root . '/css/site-font.css';
        if (is_file($fontDisk)) {
            $href = $relPath . 'css/site-font.css';
            $ver = (int) @filemtime($fontDisk);
            $tag .= "\n\t" . '<link href="' . htmlspecialchars($href, ENT_QUOTES)
                . '?v=' . $ver . '" rel="stylesheet" type="text/css" />';
        }

        $portalDisk = $root . '/css/portal_classic.css';
        if (is_file($portalDisk)) {
            $href = $relPath . 'css/portal_classic.css';
            $ver = (int) @filemtime($portalDisk);
            $tag .= "\n\t" . '<link href="' . htmlspecialchars($href, ENT_QUOTES)
                . '?v=' . $ver . '" rel="stylesheet" type="text/css" />';
        }

        if (function_exists('tz_rtl_stylesheet_tag')) {
            $tag .= tz_rtl_stylesheet_tag(null, $relPath);
        }

        $responsiveDisk = $root . '/css/responsive.css';
        if (is_file($responsiveDisk)) {
            $href = $relPath . 'css/responsive.css';
            $ver = (int) @filemtime($responsiveDisk);
            $tag .= "\n\t" . '<link href="' . htmlspecialchars($href, ENT_QUOTES)
                . '?v=' . $ver . '" rel="stylesheet" type="text/css" />';
        }

        return $tag;
    }
}

if (!function_exists('tz_portal_ndix_stylesheet_tag')) {
    function tz_portal_ndix_stylesheet_tag($relPath = '')
    {
        $tag = '';
        $root = dirname(__DIR__);

        $globalDisk = $root . '/css/global.css';
        if (is_file($globalDisk)) {
            $href = $relPath . 'css/global.css';
            $ver = (int) @filemtime($globalDisk);
            $tag .= "\n\t" . '<link href="' . htmlspecialchars($href, ENT_QUOTES)
                . '?v=' . $ver . '" rel="stylesheet" type="text/css" />';
        }

        $fontDisk = $root . '/css/site-font.css';
        if (is_file($fontDisk)) {
            $href = $relPath . 'css/site-font.css';
            $ver = (int) @filemtime($fontDisk);
            $tag .= "\n\t" . '<link href="' . htmlspecialchars($href, ENT_QUOTES)
                . '?v=' . $ver . '" rel="stylesheet" type="text/css" />';
        }

        foreach (array('style.css', 'overrides.css') as $file) {
            $disk = $root . '/css/ndix/' . $file;
            if (!is_file($disk)) {
                continue;
            }
            $href = $relPath . 'css/ndix/' . $file;
            $ver = (int) @filemtime($disk);
            $tag .= "\n\t" . '<link href="' . htmlspecialchars($href, ENT_QUOTES)
                . '?v=' . $ver . '" rel="stylesheet" type="text/css" />';
        }

        if (function_exists('tz_rtl_stylesheet_tag')) {
            $tag .= tz_rtl_stylesheet_tag(null, $relPath);
        }

        $responsiveDisk = $root . '/css/responsive.css';
        if (is_file($responsiveDisk)) {
            $href = $relPath . 'css/responsive.css';
            $ver = (int) @filemtime($responsiveDisk);
            $tag .= "\n\t" . '<link href="' . htmlspecialchars($href, ENT_QUOTES)
                . '?v=' . $ver . '" rel="stylesheet" type="text/css" />';
        }

        return $tag;
    }
}

if (!function_exists('tz_portal_world_meta')) {
    /**
     * Server age / artefacts / natars counters for portal stats tables.
     *
     * @return array<string, mixed>
     */
    function tz_portal_world_meta()
    {
        global $database;

        if (!isset($database) || !is_object($database)) {
            return array(
                'time' => time(),
                'startTs' => 0,
                'worldAgeDays' => 0,
                'speed' => defined('SPEED') ? (float) SPEED : 1,
                'spawnAt' => 0,
                'daysToSpawn' => 0,
                'daysSinceSpawn' => 0,
                'artefactsSpawned' => false,
                'artSpawnAt' => 0,
                'daysToArt' => 0,
                'daysSinceArt' => 0,
                'artsSpawned' => false,
                'roundDays' => defined('NATARS_SPAWN_TIME') ? (int) NATARS_SPAWN_TIME : 0,
            );
        }

        $time = time();
        $startTs = (int) COMMENCE;
        if ($startTs <= 0) {
            $startTs = (int) strtotime(START_DATE . ' ' . START_TIME);
        }

        $worldAgeDays = max(0, (int) floor(($time - $startTs) / 86400));
        $speed = max(1, (float) SPEED);
        $spawnAt = (int) strtotime(START_DATE) + (int) round(NATARS_SPAWN_TIME * 86400 / $speed);
        $daysToSpawn = (int) ceil(($spawnAt - $time) / 86400);
        $daysSinceSpawn = (int) floor(($time - $spawnAt) / 86400);
        $artefactsSpawned = (isset($database) && is_object($database) && method_exists($database, 'areArtifactsSpawned'))
            ? (bool) $database->areArtifactsSpawned()
            : ($time >= $spawnAt);

        $artSpawnAt = defined('NATARS_WW_BUILDING_PLAN_SPAWN_TIME')
            ? (int) strtotime(START_DATE) + (int) round(NATARS_WW_BUILDING_PLAN_SPAWN_TIME * 86400 / $speed)
            : $spawnAt;
        $daysToArt = (int) ceil(($artSpawnAt - $time) / 86400);
        $daysSinceArt = (int) floor(($time - $artSpawnAt) / 86400);
        $artsSpawned = $time >= $artSpawnAt;

        return array(
            'time' => $time,
            'startTs' => $startTs,
            'worldAgeDays' => $worldAgeDays,
            'speed' => $speed,
            'spawnAt' => $spawnAt,
            'daysToSpawn' => $daysToSpawn,
            'daysSinceSpawn' => $daysSinceSpawn,
            'artefactsSpawned' => $artefactsSpawned,
            'artSpawnAt' => $artSpawnAt,
            'daysToArt' => $daysToArt,
            'daysSinceArt' => $daysSinceArt,
            'artsSpawned' => $artsSpawned,
            'roundDays' => defined('NATARS_SPAWN_TIME') ? (int) NATARS_SPAWN_TIME : 0,
        );
    }
}

if (!function_exists('tz_portal_player_stats')) {
    /**
     * @return array{players:int, active:int, online:int}
     */
    function tz_portal_player_stats()
    {
        global $database;

        $tribeFilter = 'tribe IN(1, 2, 3, 6, 7, 8, 9)';
        $link = (isset($database) && is_object($database) && method_exists($database, 'return_link'))
            ? $database->return_link()
            : null;
        $stats = array('players' => 0, 'active' => 0, 'online' => 0);

        if (!$link) {
            return $stats;
        }

        $q = mysqli_query($link, 'SELECT Count(*) as Total FROM ' . TB_PREFIX . 'users WHERE ' . $tribeFilter);
        $stats['players'] = (!empty($q) ? (int) mysqli_fetch_assoc($q)['Total'] : 0);

        $q = mysqli_query($link, 'SELECT Count(*) as Total FROM ' . TB_PREFIX . 'users WHERE timestamp > '
            . (time() - 3600 * 24) . ' AND ' . $tribeFilter);
        $stats['active'] = (!empty($q) ? (int) mysqli_fetch_assoc($q)['Total'] : 0);

        $q = mysqli_query($link, 'SELECT Count(*) as Total FROM ' . TB_PREFIX . 'users WHERE timestamp > '
            . (time() - 60 * 10) . ' AND ' . $tribeFilter);
        $stats['online'] = (!empty($q) ? (int) mysqli_fetch_assoc($q)['Total'] : 0);

        return $stats;
    }
}

if (!function_exists('tz_portal_dhm_html')) {
    function tz_portal_dhm_html($seconds)
    {
        $seconds = max(0, (int) $seconds);
        $days = (int) floor($seconds / 86400);
        $hours = (int) floor(($seconds % 86400) / 3600);
        $minutes = (int) floor(($seconds % 3600) / 60);

        return '<b>' . $days . '</b> أيام و <b>' . $hours . '</b> ساعة و <b>' . $minutes . '</b> دقيقة';
    }
}

if (!function_exists('tz_portal_form_shell_open')) {
    /**
     * tatarwars form.phtml shell — header nav + side_navi + content column.
     *
     * @param 'login'|'register'|'guide'|'terms'|'manual' $page
     */
    function tz_portal_form_shell_open($page = 'login')
    {
        $xgif = tz_portal_xgif();
        $page = (string) $page;
        $isLogin = ($page === 'login');
        $isRegister = ($page === 'register');
        $contentClass = $isRegister ? 'signup' : 'login';
        $serverLabel = defined('SERVER_NAME') ? SERVER_NAME : 'Novaterra';
        $rulesHref = 'spielregeln.php';
        $manualHref = 'anleitung.php';

        ob_start();
        ?>
<div class="wrapper">
<img src="<?php echo htmlspecialchars($xgif, ENT_QUOTES, 'UTF-8'); ?>" id="msfilter" alt="" />
<div id="dynamic_header"></div>
<div id="header">
    <nav class="portal-topnav" aria-label="تنقل البوابة">
        <a href="index.php">الرئيسية</a>
        <a href="login.php"<?php echo $isLogin ? ' class="is-current"' : ''; ?>>الدخول</a>
        <a href="anmelden.php"<?php echo $isRegister ? ' class="is-current"' : ''; ?>>التسجيل</a>
    </nav>
</div>
<div id="mid">
    <div id="side_navi" class="news">
        <a id="logo" href="index.php"><img src="<?php echo htmlspecialchars($xgif, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?>" /></a>
        <p>
            <a href="index.php"><font style="color: black">الصفحة الرئيسية</font></a>
            <a href="manual.php"<?php echo ($page === 'manual') ? ' style="font-weight:700;"' : ''; ?>><font style="color: black">شرح اللعبة</font></a>
            <a href="<?php echo htmlspecialchars($manualHref, ENT_QUOTES, 'UTF-8'); ?>"<?php echo ($page === 'guide') ? ' style="font-weight:700;"' : ''; ?>><font style="color: black">دليل اللعبة</font></a>
            <a href="<?php echo htmlspecialchars($rulesHref, ENT_QUOTES, 'UTF-8'); ?>"<?php echo ($page === 'terms') ? ' style="font-weight:700;"' : ''; ?>><font style="color: black">قوانين اللعبة</font></a>
            <?php if ($isRegister) { ?>
            <a href="login.php"><strong>دخول</strong></a>
            <?php } else { ?>
            <a href="anmelden.php"><font style="color: orange"><strong>سجل الأن</strong></font></a>
            <?php } ?>
        </p>
    </div>
    <div id="content" class="<?php echo htmlspecialchars($contentClass, ENT_QUOTES, 'UTF-8'); ?>">
        <?php
        return ob_get_clean();
    }
}

if (!function_exists('tz_portal_form_shell_close')) {
    function tz_portal_form_shell_close()
    {
        $serverLabel = defined('SERVER_NAME') ? SERVER_NAME : 'Novaterra';
        $newsText = defined('PORTAL_SERVER_NEWS') ? PORTAL_SERVER_NEWS : '';
        if ($newsText === '') {
            $newsText = 'مرحباً بكم في ' . $serverLabel . '.';
        }

        ob_start();
        ?>
</div>
<div id="side_info" class="news">
    <div class="news">
        <h6><center><font color="green">| أخبار السيرفر |</font></center></h6>
        <p><?php echo nl2br(htmlspecialchars($newsText, ENT_QUOTES, 'UTF-8')); ?></p>
        <div class="clear"></div>
    </div>
</div>
</div>
<div class="clear"></div>
</div>
        <?php
        return ob_get_clean();
    }
}

if (!function_exists('tz_portal_classic_login_stats_html')) {
    /**
     * Login stats tables — tatarwars login.phtml format.
     *
     * @param array{players:int, active:int, online:int} $stats
     * @param array<string, mixed> $world
     */
    function tz_portal_classic_login_stats_html(array $stats, array $world = array())
    {
        if (empty($world)) {
            return '';
        }

        $now = isset($world['time']) ? (int) $world['time'] : time();
        $startTs = isset($world['startTs']) ? (int) $world['startTs'] : 0;
        $worldAgeSec = $startTs > 0 ? max(0, $now - $startTs) : (int) $world['worldAgeDays'] * 86400;

        $artSpawnAt = isset($world['artSpawnAt']) ? (int) $world['artSpawnAt'] : 0;
        $artRemainingSec = $artSpawnAt > 0 ? max(0, $artSpawnAt - $now) : max(0, (int) $world['daysToArt'] * 86400);

        $spawnAt = isset($world['spawnAt']) ? (int) $world['spawnAt'] : 0;
        $natarsRemainingSec = $spawnAt > 0 ? max(0, $spawnAt - $now) : max(0, (int) $world['daysToSpawn'] * 86400);

        ob_start();
        ?>
<table border="0" cellspacing="0" cellpadding="0" class="stats">
    <tbody>
    <tr class="top">
        <th><strong>عدد اللاعبين:</strong></th>
        <td><?php echo (int) $stats['players']; ?></td>
    </tr>
    <tr>
        <th><strong>اللاعبون النشطون:</strong></th>
        <td><?php echo (int) $stats['active']; ?></td>
    </tr>
    </tbody>
</table>
<br />
<table border="0" cellspacing="0" cellpadding="0" class="stats">
    <tbody>
    <tr class="top">
        <th><strong>بدأ السيرفر منذ:</strong></th>
        <td><span id="timer2"><?php echo tz_portal_dhm_html($worldAgeSec); ?></span></td>
    </tr>
    <tr>
        <th><strong><?php echo !empty($world['artsSpawned']) ? 'نزلت التحف منذ:' : 'سيتم نزول التحف بعد:'; ?></strong></th>
        <td><?php
        if (!empty($world['artsSpawned'])) {
            echo '<span style="color:#f00;">تم نزول التحف</span>';
        } else {
            echo '<span id="timer1">' . tz_portal_dhm_html($artRemainingSec) . '</span>';
        }
        ?></td>
    </tr>
    <tr class="btm">
        <th><strong><?php echo ((int) $world['daysToSpawn'] > 0) ? 'سيتم نزول التتار بعد:' : 'نزل التتار منذ:'; ?></strong></th>
        <td><?php
        if ((int) $world['daysToSpawn'] > 0) {
            echo '<span id="timer1">' . tz_portal_dhm_html($natarsRemainingSec) . '</span>';
        } else {
            echo '<span style="color:#f00;">تم نزول التتار</span>';
        }
        ?></td>
    </tr>
    </tbody>
</table>
<table border="0" cellspacing="0" cellpadding="0" class="stats">
    <tbody>
    <tr class="top">
        <th><strong><span style="color:#f00;">مدة السيرفر :</span></strong></th>
        <td><?php echo (int) $world['roundDays']; ?> يوم</td>
    </tr>
    <tr>
        <th><strong><span style="color:#f00;">سرعة السيرفر :</span></strong></th>
        <td>X<?php echo htmlspecialchars((string) SPEED, ENT_QUOTES, 'UTF-8'); ?></td>
    </tr>
    </tbody>
</table>
        <?php
        return ob_get_clean();
    }
}

if (!function_exists('tz_portal_login_page_stylesheet_tag')) {
    function tz_portal_login_page_stylesheet_tag($relPath = '')
    {
        $tag = '';
        $root = dirname(__DIR__);

        $fontDisk = $root . '/css/site-font.css';
        if (is_file($fontDisk)) {
            $href = $relPath . 'css/site-font.css';
            $ver = (int) @filemtime($fontDisk);
            $tag .= "\n\t" . '<link href="' . htmlspecialchars($href, ENT_QUOTES)
                . '?v=' . $ver . '" rel="stylesheet" type="text/css" />';
        }

        $loginDisk = $root . '/css/login_page.css';
        if (is_file($loginDisk)) {
            $href = $relPath . 'css/login_page.css';
            $ver = (int) @filemtime($loginDisk);
            $tag .= "\n\t" . '<link href="' . htmlspecialchars($href, ENT_QUOTES)
                . '?v=' . $ver . '" rel="stylesheet" type="text/css" />';
        }

        return $tag;
    }
}

if (!function_exists('tz_portal_stat_label')) {
    function tz_portal_stat_label($constant, $fallback)
    {
        return defined($constant) ? constant($constant) : $fallback;
    }
}

if (!function_exists('tz_portal_login_stats_html')) {
    /**
     * Unified server stats block for login / logout portal pages.
     *
     * @param array{players:int, active:int, online:int} $stats
     * @param array<string, mixed> $world
     */
    function tz_portal_login_stats_html(array $stats, array $world = array())
    {
        if (empty($world)) {
            return '';
        }

        $artLabel = !empty($world['artsSpawned'])
            ? tz_portal_stat_label('LOGIN_STAT_ARTEFACTS_SINCE', 'نزلت التحف منذ')
            : tz_portal_stat_label('LOGIN_STAT_ARTEFACTS_LEFT', 'سيتم نزول التحف بعد');
        $artValue = !empty($world['artsSpawned'])
            ? tz_portal_days_label(max(0, (int) $world['daysSinceArt']))
            : tz_portal_days_label(max(0, (int) $world['daysToArt']));

        $natarsLabel = ((int) $world['daysToSpawn'] > 0)
            ? tz_portal_stat_label('LOGIN_STAT_NATARS_LEFT', 'سيتم نزول التتار بعد')
            : tz_portal_stat_label('LOGIN_STAT_NATARS_SINCE', 'نزل التتار منذ');
        $natarsValue = ((int) $world['daysToSpawn'] > 0)
            ? tz_portal_days_label(max(0, (int) $world['daysToSpawn']))
            : tz_portal_days_label(max(0, (int) $world['daysSinceSpawn']));

        ob_start();
        ?>
<div class="login-stats-wrap">
    <div class="st-row">
        <span class="st-lab"><?php echo htmlspecialchars(tz_portal_stat_label('LOGIN_STAT_PLAYERS', PLAYERS), ENT_QUOTES, 'UTF-8'); ?></span>
        <span class="st-val"><?php echo (int) $stats['players']; ?></span>
    </div>
    <div class="st-row">
        <span class="st-lab"><?php echo htmlspecialchars(tz_portal_stat_label('LOGIN_STAT_ACTIVE', ACTIVE_PLAYERS), ENT_QUOTES, 'UTF-8'); ?></span>
        <span class="st-val"><?php echo (int) $stats['active']; ?></span>
    </div>
    <div class="st-row">
        <span class="st-lab"><?php echo htmlspecialchars(tz_portal_stat_label('LOGIN_STAT_ONLINE', ONLINE), ENT_QUOTES, 'UTF-8'); ?></span>
        <span class="st-val"><?php echo (int) $stats['online']; ?></span>
    </div>
    <div class="st-gap"></div>
    <div class="st-row">
        <span class="st-lab"><?php echo htmlspecialchars(tz_portal_stat_label('LOGIN_STAT_WORLD_AGE', 'بدأ السيرفر منذ'), ENT_QUOTES, 'UTF-8'); ?></span>
        <span class="st-val"><?php echo tz_portal_days_label((int) $world['worldAgeDays']); ?></span>
    </div>
    <div class="st-row">
        <span class="st-lab"><?php echo htmlspecialchars($artLabel, ENT_QUOTES, 'UTF-8'); ?></span>
        <span class="st-val"><?php echo $artValue; ?></span>
    </div>
    <div class="st-row">
        <span class="st-lab"><?php echo htmlspecialchars($natarsLabel, ENT_QUOTES, 'UTF-8'); ?></span>
        <span class="st-val"><?php echo $natarsValue; ?></span>
    </div>
    <div class="st-gap"></div>
    <div class="st-row st-row--meta">
        <span class="st-lab st-lab--red"><?php echo htmlspecialchars(tz_portal_stat_label('LOGIN_STAT_ROUND_DAYS', 'مدة السيرفر'), ENT_QUOTES, 'UTF-8'); ?></span>
        <span class="st-val st-val--red"><?php echo (int) $world['roundDays']; ?> <?php echo htmlspecialchars(tz_portal_stat_label('LOGIN_DAYS_FEW', 'يوم'), ENT_QUOTES, 'UTF-8'); ?></span>
    </div>
    <div class="st-row st-row--meta">
        <span class="st-lab st-lab--red"><?php echo htmlspecialchars(tz_portal_stat_label('LOGIN_STAT_SPEED', 'سرعة السيرفر'), ENT_QUOTES, 'UTF-8'); ?></span>
        <span class="st-val st-val--red">X<?php echo htmlspecialchars((string) SPEED, ENT_QUOTES, 'UTF-8'); ?></span>
    </div>
</div>
        <?php
        return ob_get_clean();
    }
}
