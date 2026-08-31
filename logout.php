<?php

#################################################################################
##  Filename       : logout.php
##  Project        : Novaterra
##  License        : GPLv3 (derived from TravianZ)
#################################################################################

use App\Utils\AccessLogger;

include('GameEngine/Account.php');
AccessLogger::logRequest();

if (isset($_GET['del_cookie'])) {
    setcookie('COOKUSR', '', time() - 3600 * 24, '/');
    header('Location: logout.php');
    exit;
}

function tz_logout_days_label($days)
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

$link = method_exists($database, 'return_link') ? $database->return_link() : null;
$tribeFilter = 'tribe IN(1, 2, 3, 6, 7, 8, 9)';
$statPlayers = 0;
$statActive = 0;
$statOnline = 0;
if ($link) {
    $q = mysqli_query($link, 'SELECT Count(*) as Total FROM ' . TB_PREFIX . 'users WHERE ' . $tribeFilter);
    $statPlayers = (!empty($q) ? (int) mysqli_fetch_assoc($q)['Total'] : 0);
    $q = mysqli_query($link, 'SELECT Count(*) as Total FROM ' . TB_PREFIX . 'users WHERE timestamp > ' . (time() - 3600 * 24) . ' AND ' . $tribeFilter);
    $statActive = (!empty($q) ? (int) mysqli_fetch_assoc($q)['Total'] : 0);
    $q = mysqli_query($link, 'SELECT Count(*) as Total FROM ' . TB_PREFIX . 'users WHERE timestamp > ' . (time() - 60 * 10) . ' AND ' . $tribeFilter);
    $statOnline = (!empty($q) ? (int) mysqli_fetch_assoc($q)['Total'] : 0);
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
$artefactsSpawned = method_exists($database, 'areArtifactsSpawned')
    ? (bool) $database->areArtifactsSpawned()
    : ($time >= $spawnAt);

$serverLabel = defined('SERVER_NAME') ? SERVER_NAME : 'Novaterra';
$speedLabel = defined('SPEED') ? (string) SPEED : '0';
$clockNow = date('H:i:s');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo tz_html_dir_attrs(); ?> class="pg-login pg-logout">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo SERVER_NAME; ?> - <?php echo defined('LOGOUT') ? LOGOUT : 'Logout'; ?></title>
    <link rel="shortcut icon" href="favicon.ico" />
    <link href="css/login_page.css?v=<?php echo @filemtime(__DIR__ . '/css/login_page.css') ?: time(); ?>" rel="stylesheet" type="text/css" />
    <?php echo tz_global_stylesheet_tag(); ?>
    <style type="text/css">
    body.pg-login .login-side {
        padding-top: 112px !important;
    }
    body.pg-logout .login-col {
        flex: 1 1 auto !important;
        min-width: 0 !important;
        max-width: 560px !important;
    }
    </style>
</head>
<body class="pg-login pg-logout">

<div class="login-banner" role="img" aria-label="<?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?>"></div>

<div class="login-statusbar">
    <div class="login-statusbar__inner">
        <div class="login-world">
            <?php echo function_exists('tz_day_night_icon_html') ? tz_day_night_icon_html('login-daynight') : '<span class="login-moon" aria-hidden="true"></span>'; ?>
            <span><?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($speedLabel, ENT_QUOTES, 'UTF-8'); ?> م.ت)</span>
            <span class="login-world__chev">▾</span>
        </div>
        <div class="login-now">
            الآن : <span id="_Clock"><?php echo htmlspecialchars($clockNow, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
    </div>
</div>

<div class="login-layout">
    <aside class="login-side">
        <a href="index.php"><?php echo HOME; ?></a>
        <a href="login.php"><?php echo LOGIN; ?></a>
        <a href="anmelden.php" class="is-reg">سجل الآن</a>
    </aside>

    <div class="login-col">
        <h1 class="login-title"><?php echo PUBLIC_LOGOUT_SUCCESS_TITLE; ?></h1>
        <main class="login-main">
            <p class="logout-thanks"><?php echo PUBLIC_LOGOUT_THANKS; ?></p>

            <p class="logout-cookie">
                <?php echo PUBLIC_DELETE_COOKIES_NOTICE; ?><br />
                <a href="logout.php?del_cookie">&raquo; <?php echo PUBLIC_DELETE_COOKIES; ?></a>
            </p>

            <div class="login-stats-wrap">
                <div class="st-row">
                    <span class="st-lab"><?php echo defined('LOGIN_STAT_PLAYERS') ? LOGIN_STAT_PLAYERS : PLAYERS; ?></span>
                    <span class="st-val"><?php echo (int) $statPlayers; ?></span>
                </div>
                <div class="st-row">
                    <span class="st-lab"><?php echo defined('LOGIN_STAT_ACTIVE') ? LOGIN_STAT_ACTIVE : ACTIVE_PLAYERS; ?></span>
                    <span class="st-val"><?php echo (int) $statActive; ?></span>
                </div>
                <div class="st-row">
                    <span class="st-lab"><?php echo defined('LOGIN_STAT_ONLINE') ? LOGIN_STAT_ONLINE : ONLINE; ?></span>
                    <span class="st-val"><?php echo (int) $statOnline; ?></span>
                </div>
                <div class="st-gap"></div>
                <div class="st-row">
                    <span class="st-lab"><?php echo defined('LOGIN_STAT_WORLD_AGE') ? LOGIN_STAT_WORLD_AGE : ''; ?></span>
                    <span class="st-val"><?php echo tz_logout_days_label($worldAgeDays); ?></span>
                </div>
                <div class="st-row">
                    <span class="st-lab"><?php echo $artefactsSpawned
                        ? (defined('LOGIN_STAT_ARTEFACTS_SINCE') ? LOGIN_STAT_ARTEFACTS_SINCE : '')
                        : (defined('LOGIN_STAT_ARTEFACTS_LEFT') ? LOGIN_STAT_ARTEFACTS_LEFT : ''); ?></span>
                    <span class="st-val"><?php echo $artefactsSpawned
                        ? tz_logout_days_label(max(0, $daysSinceSpawn))
                        : tz_logout_days_label(max(0, $daysToSpawn)); ?></span>
                </div>
                <div class="st-row">
                    <span class="st-lab"><?php echo ($daysToSpawn > 0)
                        ? (defined('LOGIN_STAT_NATARS_LEFT') ? LOGIN_STAT_NATARS_LEFT : '')
                        : (defined('LOGIN_STAT_NATARS_SINCE') ? LOGIN_STAT_NATARS_SINCE : ''); ?></span>
                    <span class="st-val"><?php echo ($daysToSpawn > 0)
                        ? tz_logout_days_label($daysToSpawn)
                        : tz_logout_days_label(max(0, -$daysToSpawn)); ?></span>
                </div>
            </div>
        </main>
    </div>
</div>

<script type="text/javascript">
(function () {
    var clock = document.getElementById('_Clock');
    if (clock) {
        setInterval(function () {
            var d = new Date();
            function z(n) { return n < 10 ? '0' + n : '' + n; }
            clock.textContent = z(d.getHours()) + ':' + z(d.getMinutes()) + ':' + z(d.getSeconds());
        }, 1000);
    }
})();
</script>
</body>
</html>
