<?php

#################################################################################
##  Filename       : login.php
##  Project        : Novaterra
##  License        : GPLv3 (derived from TravianZ)
#################################################################################

use App\Utils\AccessLogger;

if (!file_exists('var/installed') && @opendir('install')) {
    header('Location: install/');
    exit;
}

include('GameEngine/Account.php');
AccessLogger::logRequest();

if (isset($_GET['del_cookie'])) {
    setcookie('COOKUSR', '', time() - 3600 * 24, '/');
    header('Location: login.php');
    exit;
}
if (!isset($_COOKIE['COOKUSR'])) {
    $_COOKIE['COOKUSR'] = '';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['csrf']) || $_SESSION['csrf'] !== $_POST['csrf']) {
        throw new RuntimeException('CSRF attack');
    }
}
$key = sha1(microtime());
$_SESSION['csrf'] = $key;

function tz_login_days_label($days)
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
$userVal = htmlspecialchars($form->getDiff('user', $_COOKIE['COOKUSR']));
$pwVal = htmlspecialchars($form->getValue('pw'), ENT_QUOTES, 'UTF-8');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo tz_html_dir_attrs(); ?> class="pg-login">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo SERVER_NAME; ?> - <?php echo LOGIN; ?></title>
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@400;600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="css/login_page.css?v=<?php echo @filemtime(__DIR__ . '/css/login_page.css') ?: time(); ?>" rel="stylesheet" type="text/css" />
    <style type="text/css">
    body.pg-login,
    body.pg-login input,
    body.pg-login button,
    body.pg-login label,
    body.pg-login td,
    body.pg-login th,
    body.pg-login a {
        font-family: "Changa", Tahoma, Arial, Helvetica, sans-serif !important;
    }
    body.pg-login .login-statusbar,
    body.pg-login .login-statusbar * {
        font-family: Tahoma, Arial, Helvetica, sans-serif !important;
    }
    body.pg-login .login-side {
        padding-top: 112px !important;
    }
    body.pg-login .login-col {
        flex: 1 1 auto !important;
        min-width: 0 !important;
        max-width: 560px !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
    }
    body.pg-login .login-title {
        margin: 0 0 14px !important;
        padding: 0 !important;
        font-size: 28px !important;
        font-weight: 800 !important;
        line-height: 1.25 !important;
        text-align: center !important;
        font-family: "Changa", Tahoma, Arial, Helvetica, sans-serif !important;
        background-image: repeating-linear-gradient(180deg, #1f1f1f 0px, #1f1f1f 1px, #6e6e6e 1px, #6e6e6e 2px) !important;
        -webkit-background-clip: text !important;
        background-clip: text !important;
        color: transparent !important;
        -webkit-text-fill-color: transparent !important;
        background-color: transparent !important;
    }
    body.pg-login .login-main {
        width: 100% !important;
        max-width: none !important;
        background: #fff !important;
        box-shadow: -16px 0 26px -20px rgba(0,0,0,.2), 16px 0 26px -20px rgba(0,0,0,.2) !important;
        padding: 18px 22px 150px !important;
        min-height: 0 !important;
        box-sizing: border-box !important;
    }
    body.pg-login .login-help {
        text-align: center !important;
    }
    body.pg-login .login-btn {
        display: inline-block !important;
        margin: 12px auto 14px !important;
        padding: 3px 26px 4px !important;
        border: 1px solid #8edf27 !important;
        border-radius: 999px !important;
        background: linear-gradient(180deg, #f7f7f6 0%, #ececeb 48%, #dededd 100%) !important;
        color: #5a5a5a !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        text-shadow: 0 1px 0 #fff !important;
        box-shadow: inset 0 1px 0 rgba(255,255,255,.9) !important;
        cursor: pointer !important;
        height: auto !important;
        min-width: 0 !important;
        line-height: 1.4 !important;
        float: none !important;
    }
    body.pg-login .login-stats-wrap {
        display: grid !important;
        grid-template-columns: max-content max-content !important;
        column-gap: 48px !important;
        row-gap: 0 !important;
        justify-content: start !important;
        width: 100% !important;
        box-sizing: border-box !important;
        margin: 0 !important;
        border: 1px dashed #c8c8c8 !important;
        background: #fff !important;
        padding: 8px 12px !important;
        direction: rtl !important;
        float: none !important;
    }
    body.pg-login .login-stats-wrap .st-row {
        display: contents !important;
    }
    body.pg-login .login-stats-wrap .st-lab {
        display: block !important;
        font-weight: 400 !important;
        text-align: right !important;
        font-size: 13px !important;
        line-height: 1.7 !important;
        color: #000 !important;
        float: none !important;
        margin: 0 !important;
        padding: 0 !important;
        white-space: nowrap !important;
    }
    body.pg-login .login-stats-wrap .st-val {
        display: block !important;
        font-weight: 700 !important;
        text-align: right !important;
        font-size: 13px !important;
        line-height: 1.7 !important;
        color: #000 !important;
        float: none !important;
        margin: 0 !important;
        padding: 0 !important;
        white-space: nowrap !important;
    }
    body.pg-login .login-stats-wrap .st-gap {
        grid-column: 1 / -1 !important;
        height: 12px !important;
        width: auto !important;
    }
    </style>
</head>
<body class="pg-login">

<div class="login-banner" role="img" aria-label="<?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?>"></div>

<div class="login-statusbar">
    <div class="login-statusbar__inner">
        <div class="login-world">
            <span class="login-moon" aria-hidden="true"></span>
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
        <a href="index.php">الصفحة الرئيسية</a>
        <a href="anleitung.php">الدليل السريع</a>
        <a href="manual.php">شرح اللعبة</a>
        <a href="login.php" class="is-current">دخول</a>
        <a href="anmelden.php" class="is-reg">سجل الآن</a>
    </aside>

    <div class="login-col">
        <h1 class="login-title"><?php echo defined('LOGIN_PAGE_TITLE') ? LOGIN_PAGE_TITLE : 'الدخول الى اللعبة'; ?></h1>
        <main class="login-main">

        <?php if (COMMENCE > $time) { ?>
            <p class="login-alert"><?php echo NOT_OPENED_YET; ?></p>
        <?php } else {
            $stime = strtotime(date('m/d/Y H:i', strtotime(START_DATE . ' ' . START_TIME)));
            if ($stime > $time) {
                echo '<p class="login-alert">' . SERVER_STARTS_IN . '</p>';
            } else {
                ?>
                <p class="login-help"><?php echo defined('LOGIN_HELP_TEXT') ? LOGIN_HELP_TEXT : COOKIES; ?></p>

                <form method="post" name="snd" action="login.php" autocomplete="off">
                    <input type="hidden" name="ft" value="a4" />
                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>" />

                    <table class="login-box">
                        <tr>
                            <td class="lbl"><?php echo defined('LOGIN_USER_OR_EMAIL') ? LOGIN_USER_OR_EMAIL : NAME; ?></td>
                            <td>
                                <input class="fi" type="text" name="user" value="<?php echo $userVal; ?>" maxlength="30" autocomplete="off" />
                                <?php
                                $errUser = $form->getError('user');
                                if ($errUser !== '' && $errUser !== null) {
                                    echo '<span class="err">' . $errUser . '</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="lbl"><?php echo PASSWORD; ?>:</td>
                            <td>
                                <span class="pw">
                                    <input class="fi" type="password" name="pw" id="i_Pwd" value="<?php echo $pwVal; ?>" maxlength="100" autocomplete="off" />
                                    <img class="pw-eye" id="EyePwd_Eye" src="img/login_eye.svg" alt="" />
                                </span>
                                <?php
                                $errPw = $form->getError('pw');
                                if ($errPw !== '' && $errPw !== null && $errPw !== LOGIN_PW_ERROR) {
                                    echo '<span class="err">' . $errPw . '</span>';
                                }
                                ?>
                            </td>
                        </tr>
                    </table>

                    <button type="submit" name="s1" value="login" class="login-btn"><?php echo defined('LOGIN_SUBMIT') ? LOGIN_SUBMIT : LOGIN; ?></button>
                </form>
                <?php
            }
        }

        if ($form->getError('pw') == LOGIN_PW_ERROR) {
            echo '<p class="err-box"><span class="err">' . PW_FORGOTTEN . '</span><br />'
                . PW_REQUEST . '<br />'
                . '<a href="password.php?npw=' . $database->getUserField($form->getValue('user'), 'id', 1) . '">'
                . PW_GENERATE . '</a></p>';
        }
        if ($form->getError('activate') != '') {
            echo '<p class="err-box"><span class="err">' . EMAIL_NOT_VERIFIED . '</span><br />'
                . EMAIL_FOLLOW . '<br />'
                . '<a href="activate.php?usr=' . urlencode($form->getError('activate')) . '">'
                . VERIFY_EMAIL . '</a></p>';
        }
        if ($form->getError('vacation') != '') {
            echo '<p class="err-box"><span class="err">'
                . htmlspecialchars($form->getError('vacation'), ENT_QUOTES, 'UTF-8')
                . '</span></p>';
        }
        ?>

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
                <span class="st-val"><?php echo tz_login_days_label($worldAgeDays); ?></span>
            </div>
            <div class="st-row">
                <span class="st-lab"><?php echo $artefactsSpawned
                    ? (defined('LOGIN_STAT_ARTEFACTS_SINCE') ? LOGIN_STAT_ARTEFACTS_SINCE : '')
                    : (defined('LOGIN_STAT_ARTEFACTS_LEFT') ? LOGIN_STAT_ARTEFACTS_LEFT : ''); ?></span>
                <span class="st-val"><?php echo $artefactsSpawned
                    ? tz_login_days_label(max(0, $daysSinceSpawn))
                    : tz_login_days_label(max(0, $daysToSpawn)); ?></span>
            </div>
            <div class="st-row">
                <span class="st-lab"><?php echo ($daysToSpawn > 0)
                    ? (defined('LOGIN_STAT_NATARS_LEFT') ? LOGIN_STAT_NATARS_LEFT : '')
                    : (defined('LOGIN_STAT_NATARS_SINCE') ? LOGIN_STAT_NATARS_SINCE : ''); ?></span>
                <span class="st-val"><?php echo ($daysToSpawn > 0)
                    ? tz_login_days_label($daysToSpawn)
                    : tz_login_days_label(max(0, -$daysToSpawn)); ?></span>
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
    var eye = document.getElementById('EyePwd_Eye');
    var pw = document.getElementById('i_Pwd');
    if (eye && pw) {
        eye.onclick = function () {
            pw.type = (pw.type === 'password') ? 'text' : 'password';
        };
    }
})();
</script>
</body>
</html>
