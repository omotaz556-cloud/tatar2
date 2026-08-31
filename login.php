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

$stats = function_exists('tz_portal_player_stats') ? tz_portal_player_stats() : array('players' => 0, 'active' => 0, 'online' => 0);
$world = function_exists('tz_portal_world_meta') ? tz_portal_world_meta() : array();
$time = isset($world['time']) ? (int) $world['time'] : time();

$xgif = function_exists('tz_portal_xgif') ? tz_portal_xgif() : 'img/x.gif';
$userVal = htmlspecialchars($form->getDiff('user', $_COOKIE['COOKUSR']));
$pwVal = htmlspecialchars($form->getValue('pw'), ENT_QUOTES, 'UTF-8');
$helpText = defined('LOGIN_HELP_TEXT') ? LOGIN_HELP_TEXT : COOKIES;
$userLabel = defined('LOGIN_USER_OR_EMAIL') ? LOGIN_USER_OR_EMAIL : NAME;
$loginTitle = defined('LOGIN_TO') ? LOGIN_TO : (defined('LOGIN') ? LOGIN : 'تسجيل الدخول');
$showTerms = isset($_GET['terms']);
$pageClass = $showTerms ? 'pg-portal-terms' : 'pg-portal-login';
$pageTitle = $showTerms ? 'قوانين اللعبة' : LOGIN;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo tz_html_dir_attrs(); ?> class="<?php echo $pageClass; ?>">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo SERVER_NAME; ?> - <?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="shortcut icon" href="favicon.ico" />
    <?php echo function_exists('tz_portal_classic_stylesheet_tag') ? tz_portal_classic_stylesheet_tag() : ''; ?>
</head>
<body class="webkit v35 <?php echo $pageClass; ?>">

<?php echo tz_portal_form_shell_open($showTerms ? 'terms' : 'login'); ?>

<?php if ($showTerms) {
    include __DIR__ . '/Templates/Portal/terms.tpl';
} else { ?>

<h1><img src="<?php echo htmlspecialchars($xgif, ENT_QUOTES, 'UTF-8'); ?>" class="img_login" alt="<?php echo htmlspecialchars($loginTitle, ENT_QUOTES, 'UTF-8'); ?>" /></h1>
<h5><img class="img_u04" src="<?php echo htmlspecialchars($xgif, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($loginTitle, ENT_QUOTES, 'UTF-8'); ?>" /></h5>

<?php if (COMMENCE > $time) { ?>
    <p class="error_box"><?php echo NOT_OPENED_YET; ?></p>
<?php } else {
    $stime = strtotime(date('m/d/Y H:i', strtotime(START_DATE . ' ' . START_TIME)));
    if ($stime > $time) {
        echo '<p class="error_box">' . SERVER_STARTS_IN . '</p>';
    } else {
        ?>
        <p><?php echo $helpText; ?></p>

        <form method="post" name="snd" action="login.php" autocomplete="off">
            <input type="hidden" name="ft" value="a4" />
            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>" />

            <table cellpadding="1" cellspacing="1" id="login_form">
                <tbody>
                <tr class="top">
                    <th><?php echo htmlspecialchars($userLabel, ENT_QUOTES, 'UTF-8'); ?> :</th>
                    <td>
                        <input class="text" type="text" name="user" value="<?php echo $userVal; ?>" maxlength="30" autocomplete="off" title="<?php echo htmlspecialchars($userLabel, ENT_QUOTES, 'UTF-8'); ?>" />
                        <?php
                        $errUser = $form->getError('user');
                        if ($errUser !== '' && $errUser !== null) {
                            echo '<span class="error">' . $errUser . '</span>';
                        }
                        ?>
                    </td>
                </tr>
                <tr class="btm">
                    <th><?php echo PASSWORD; ?> :</th>
                    <td>
                        <input class="text" type="password" name="pw" value="<?php echo $pwVal; ?>" maxlength="100" autocomplete="off" title="<?php echo PASSWORD; ?>" />
                        <?php
                        $errPw = $form->getError('pw');
                        if ($errPw !== '' && $errPw !== null && $errPw !== LOGIN_PW_ERROR) {
                            echo '<span class="error">' . $errPw . '</span>';
                        }
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>

            <p class="btn">
                <input id="btn_login" class="dynamic_img" type="image" alt="<?php echo defined('LOGIN_SUBMIT') ? LOGIN_SUBMIT : LOGIN; ?>" src="<?php echo htmlspecialchars($xgif, ENT_QUOTES, 'UTF-8'); ?>" name="s1" value="login" />
            </p>
        </form>
        <?php
    }
}

if ($form->getError('pw') == LOGIN_PW_ERROR) {
    echo '<p class="error_box"><span class="error">' . PW_FORGOTTEN . '</span><br />'
        . PW_REQUEST . '<br />'
        . '<a class="portal-link" href="password.php?npw=' . $database->getUserField($form->getValue('user'), 'id', 1) . '">'
        . PW_GENERATE . '</a></p>';
}
if ($form->getError('activate') != '') {
    echo '<p class="error_box"><span class="error">' . EMAIL_NOT_VERIFIED . '</span><br />'
        . EMAIL_FOLLOW . '<br />'
        . '<a class="portal-link" href="activate.php?usr=' . urlencode($form->getError('activate')) . '">'
        . VERIFY_EMAIL . '</a></p>';
}
if ($form->getError('vacation') != '') {
    echo '<p class="error_box"><span class="error">'
        . htmlspecialchars($form->getError('vacation'), ENT_QUOTES, 'UTF-8')
        . '</span></p>';
}
?>

<?php echo function_exists('tz_portal_classic_login_stats_html') ? tz_portal_classic_login_stats_html($stats, $world) : ''; ?>

<?php } ?>

<?php echo tz_portal_form_shell_close(); ?>

</body>
</html>
