<?php

#################################################################################
##  Filename       : anmelden.php
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

$invited = (isset($_GET['uid']))
    ? filter_var($_GET['uid'], FILTER_SANITIZE_NUMBER_INT)
    : $form->getError('invt');

if (!function_exists('regText')) {
    function regText($constant, $fallback)
    {
        return defined($constant) ? constant($constant) : $fallback;
    }
}

function tz_reg_days_label($days)
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

/* Enabled tribes only — no invented tribes (e.g. Arabs). */
$regTribes = array(
    1 => array(
        'name' => regText('REG_TRIBE_ROMANS', 'الرومان'),
        'flag' => true,
    ),
    2 => array(
        'name' => regText('REG_TRIBE_TEUTONS', 'الجرمان'),
        'flag' => true,
    ),
    3 => array(
        'name' => regText('REG_TRIBE_GAULS', 'الأغريق'),
        'flag' => true,
    ),
    7 => array(
        'name' => defined('TRIBE7') ? TRIBE7 : 'Egyptians',
        'flag' => (defined('NEW_FUNCTION_TRIBE_EGIPTEANS') && NEW_FUNCTION_TRIBE_EGIPTEANS),
    ),
    6 => array(
        'name' => defined('TRIBE6') ? TRIBE6 : 'Huns',
        'flag' => (defined('NEW_FUNCTION_TRIBE_HUNS') && NEW_FUNCTION_TRIBE_HUNS),
    ),
    8 => array(
        'name' => defined('TRIBE8') ? TRIBE8 : 'Spartans',
        'flag' => (defined('NEW_FUNCTION_TRIBE_SPARTANS') && NEW_FUNCTION_TRIBE_SPARTANS),
    ),
    9 => array(
        'name' => defined('TRIBE9') ? TRIBE9 : 'Vikings',
        'flag' => (defined('NEW_FUNCTION_TRIBE_VIKINGS') && NEW_FUNCTION_TRIBE_VIKINGS),
    ),
);

foreach ($regTribes as $tribeId => $tribeData) {
    if (!$tribeData['flag']) {
        unset($regTribes[$tribeId]);
    }
}

/* kid: 0=random, 1=NW, 2=NE, 3=SW, 4=SE (engine mapping) */
$regQuadrants = array(
    0 => regText('REG_POS_RANDOM', 'عشوائي'),
    1 => regText('REG_POS_NW', 'شمال غربي'),
    2 => regText('REG_POS_NE', 'شمال شرقي'),
    3 => regText('REG_POS_SW', 'جنوب غربي'),
    4 => regText('REG_POS_SE', 'جنوب شرقي'),
);

$regTribe = (int) $form->getValue('vid');
if (!isset($regTribes[$regTribe])) {
    reset($regTribes);
    $regTribe = (int) key($regTribes);
}
$regQuad = (int) $form->getValue('kid');
if (!isset($regQuadrants[$regQuad])) {
    $regQuad = 0;
}

$time = time();
$startTs = (int) COMMENCE;
if ($startTs <= 0) {
    $startTs = (int) strtotime(START_DATE . ' ' . START_TIME);
}
$ageSec = max(0, $time - $startTs);
if ($ageSec < 86400) {
    $hours = max(1, (int) floor($ageSec / 3600));
    $worldAgeLabel = $hours . ' ' . regText('REG_HOUR', 'ساعة');
} else {
    $worldAgeLabel = tz_reg_days_label((int) floor($ageSec / 86400));
}

$serverLabel = defined('SERVER_NAME') ? SERVER_NAME : 'Novaterra';
$speedLabel = defined('SPEED') ? (string) SPEED : '0';
$clockNow = date('H:i:s');

$nameVal = htmlspecialchars($form->getValue('name'), ENT_QUOTES, 'UTF-8');
$emailVal = htmlspecialchars(stripslashes((string) $form->getValue('email')), ENT_QUOTES, 'UTF-8');

$beforeRegister = defined('BEFORE_REGISTER')
    ? BEFORE_REGISTER
    : ('قبل التسجيل في ' . $serverLabel . ' يمكنك قراءة <a href="anleitung.php">الدليل</a> لمعرفة ميز وعيوب القبائل');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo tz_html_dir_attrs(); ?> class="pg-login">
	<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo SERVER_NAME; ?> - <?php echo regText('TZ_REGISTRATION', 'التسجيل'); ?></title>
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@400;600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="css/login_page.css?v=<?php echo @filemtime(__DIR__ . '/css/login_page.css') ?: time(); ?>" rel="stylesheet" type="text/css" />
<style type="text/css">
    body.pg-register,
    body.pg-register input,
    body.pg-register button,
    body.pg-register label,
    body.pg-register th,
    body.pg-register td {
        font-family: "Changa", Tahoma, Arial, Helvetica, sans-serif !important;
    }
    body.pg-register .login-statusbar,
    body.pg-register .login-statusbar * {
        font-family: Tahoma, Arial, Helvetica, sans-serif !important;
    }
    body.pg-register .login-side {
        padding-top: 210px !important;
    }
    body.pg-register .login-col {
        flex: 1 1 auto !important;
        min-width: 0 !important;
        max-width: 560px !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
    }
    /* Title + soldier OUTSIDE the white card */
    body.pg-register .reg-hero {
        position: relative !important;
        width: 100% !important;
        min-height: 130px !important;
        margin: 0 0 10px !important;
        padding: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
    }
    body.pg-register .reg-soldier {
        position: absolute !important;
        left: 8px !important;
        top: 0 !important;
        height: 130px !important;
        width: auto !important;
        pointer-events: none !important;
        z-index: 2 !important;
    }
    body.pg-register .reg-title-img {
        display: block !important;
        margin: 28px auto 0 !important;
        height: 52px !important;
        width: auto !important;
        max-width: 220px !important;
    }
    body.pg-register .login-main {
        width: 100% !important;
        max-width: none !important;
        background: #fff !important;
        box-shadow: -16px 0 26px -20px rgba(0,0,0,.2), 16px 0 26px -20px rgba(0,0,0,.2) !important;
        padding: 18px 22px 150px !important;
        min-height: 0 !important;
        box-sizing: border-box !important;
        text-align: center !important;
    }
    body.pg-register .reg-intro {
        margin: 0 4px 14px !important;
        text-align: center !important;
        color: #000 !important;
        font-size: 15px !important;
        font-weight: 400 !important;
        line-height: 1.7 !important;
    }
    body.pg-register .reg-intro a {
        color: #71d000 !important;
        font-weight: 700 !important;
    }
    body.pg-register table.reg-box {
        width: 100% !important;
        margin: 0 auto 14px !important;
        border-collapse: separate !important;
        border: 2px dashed #c8c8c8 !important;
        background: #fff !important;
        text-align: right !important;
        font-size: 15px !important;
        direction: rtl !important;
    }
    body.pg-register table.reg-box td {
        padding: 7px 10px !important;
        vertical-align: middle !important;
    }
    body.pg-register table.reg-box td.lbl {
        width: 150px !important;
        white-space: nowrap !important;
        font-weight: 400 !important;
    }
    body.pg-register table.reg-box input.fi {
        width: 190px !important;
        height: 26px !important;
        border: 1px solid #8edf27 !important;
        padding: 1px 4px !important;
        font-size: 14px !important;
        font-family: "Changa", Tahoma, Arial, sans-serif !important;
        background: #fff !important;
        box-sizing: border-box !important;
    }
    body.pg-register table.reg-opts {
        width: 100% !important;
        margin: 0 auto 14px !important;
        border-collapse: separate !important;
        border: 2px dashed #c8c8c8 !important;
        background: #fff !important;
        text-align: right !important;
        font-size: 15px !important;
        direction: rtl !important;
    }
    body.pg-register table.reg-opts th {
        padding: 8px 10px 4px !important;
        font-weight: 700 !important;
        color: #e0a010 !important;
        text-align: right !important;
        vertical-align: top !important;
    }
    body.pg-register table.reg-opts td {
        padding: 3px 10px !important;
        text-align: right !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
    }
    body.pg-register table.reg-opts label {
        cursor: pointer !important;
        font-weight: 400 !important;
        color: #000 !important;
    }
    body.pg-register table.reg-opts td.reg-tribes label {
        display: inline-block;
        margin: 0 0 6px;
        white-space: nowrap;
    }
    body.pg-register .login-btn {
        display: inline-block !important;
        margin: 4px auto 12px !important;
        padding: 4px 28px 5px !important;
        border: 1px solid #8edf27 !important;
        border-radius: 999px !important;
        background: linear-gradient(180deg, #f7f7f6 0%, #ececeb 48%, #dededd 100%) !important;
        color: #5a5a5a !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        font-family: "Changa", Tahoma, Arial, sans-serif !important;
        text-shadow: 0 1px 0 #fff !important;
        box-shadow: inset 0 1px 0 rgba(255,255,255,.9) !important;
        cursor: pointer !important;
        height: auto !important;
        min-width: 0 !important;
        line-height: 1.4 !important;
        float: none !important;
    }
    body.pg-register .reg-age {
        margin: 0 !important;
        color: #9a9a9a !important;
        font-size: 18px !important;
        font-weight: 400 !important;
    }
    body.pg-register .login-side a {
        font-family: "Changa", Tahoma, Arial, sans-serif !important;
        font-size: 15px !important;
    }
    body.pg-login.pg-register .login-side {
        padding-top: 210px !important;
    }
</style>
</head>
<body class="pg-login pg-register">

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
        <a href="index.php"><?php echo regText('HOME', 'الصفحة الرئيسية'); ?></a>
        <a href="anleitung.php"><?php echo defined('LOGIN_QUICK_GUIDE') ? LOGIN_QUICK_GUIDE : 'الدليل السريع'; ?></a>
        <a href="manual.php"><?php echo defined('LOGIN_GAME_GUIDE') ? LOGIN_GAME_GUIDE : 'شرح اللعبة'; ?></a>
        <a href="login.php"><?php echo defined('LOGIN_MENU_ENTER') ? LOGIN_MENU_ENTER : 'دخول'; ?></a>
        <a href="anmelden.php" class="is-reg is-current"><?php echo defined('LOGIN_MENU_REG') ? LOGIN_MENU_REG : 'سجل الآن'; ?></a>
    </aside>

    <div class="login-col">

<?php if (REG_OPEN == true) { ?>
        <div class="reg-hero">
            <img class="reg-soldier" src="img/login/reg_soldier.png?v=3" alt="" />
            <img class="reg-title-img" src="img/login/reg_title_clear.png?v=3" alt="<?php echo htmlspecialchars(regText('TZ_REGISTRATION', 'التسجيل'), ENT_QUOTES, 'UTF-8'); ?>" />
			</div>
<?php } ?>

        <main class="login-main">

<?php if (REG_OPEN != true) { ?>
            <p class="login-alert"><?php echo REGISTER_CLOSED; ?></p>
<?php } else { ?>

            <p class="reg-intro"><?php echo $beforeRegister; ?></p>

            <?php
            $errWinner = $form->getError('winner');
            $errTribe = $form->getError('tribe');
            $errAgree = $form->getError('agree');
            if ($errWinner !== '' && $errWinner !== null) {
                echo '<p class="err-box"><span class="err">' . $errWinner . '</span></p>';
            }
            if ($errTribe !== '' && $errTribe !== null) {
                echo '<p class="err-box"><span class="err">' . $errTribe . '</span></p>';
            }
            if ($errAgree !== '' && $errAgree !== null) {
                echo '<p class="err-box"><span class="err">' . $errAgree . '</span></p>';
            }
            ?>

            <form method="post" name="snd" action="anmelden.php" autocomplete="off">
                <input type="hidden" name="ft" value="a1" />
                <input type="hidden" name="invited" value="<?php echo htmlspecialchars((string) $invited, ENT_QUOTES, 'UTF-8'); ?>" />
                <input type="hidden" name="agb" value="1" />

                <table class="reg-box">
                    <tr>
                        <td class="lbl"><?php echo defined('NICKNAME') ? NICKNAME : 'الاسم المستعار'; ?> :</td>
                        <td>
                            <input class="fi" type="text" name="name" value="<?php echo $nameVal; ?>" maxlength="30" autocomplete="off" />
                            <?php
                            $errName = $form->getError('name');
                            if ($errName !== '' && $errName !== null) {
                                echo '<span class="err">' . $errName . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="lbl"><?php echo regText('REG_EMAIL_LABEL', 'البريد الالكتروني'); ?> :</td>
                        <td>
                            <input class="fi" type="text" name="email" value="<?php echo $emailVal; ?>" maxlength="50" autocomplete="off" />
                            <?php
                            $errEmail = $form->getError('email');
                            if ($errEmail !== '' && $errEmail !== null) {
                                echo '<span class="err">' . $errEmail . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="lbl"><?php echo regText('REG_PW_LABEL', 'كلمة السر'); ?> :</td>
                        <td>
                            <input class="fi" type="password" name="pw" value="" maxlength="100" autocomplete="new-password" />
                            <?php
                            $errPw = $form->getError('pw');
                            if ($errPw !== '' && $errPw !== null) {
                                echo '<span class="err">' . $errPw . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                </table>

                <table class="reg-opts">
                    <tr>
                        <th><?php echo regText('REG_CHOOSE_TRIBE', 'إختر قبيلة'); ?><br /><br /></th>
                        <th colspan="2"><?php echo regText('REG_START_POS', 'موقع البداية'); ?><br /><br /></th>
                    </tr>
                    <tr>
                        <td rowspan="3" class="reg-tribes">
<?php
foreach ($regTribes as $tid => $tribeData) {
    $checked = ((int) $tid === $regTribe) ? ' checked="checked"' : '';
    $tname = htmlspecialchars($tribeData['name'], ENT_QUOTES, 'UTF-8');
    echo '<label><input type="radio" name="vid" value="' . (int) $tid . '"' . $checked . ' />' . $tname . '</label><br />' . "\n";
}
?>
                        </td>
                        <td colspan="2">
                            <label><input type="radio" name="kid" value="0"<?php echo $regQuad === 0 ? ' checked="checked"' : ''; ?> /><?php echo htmlspecialchars($regQuadrants[0], ENT_QUOTES, 'UTF-8'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <td><label><input type="radio" name="kid" value="2"<?php echo $regQuad === 2 ? ' checked="checked"' : ''; ?> /><?php echo htmlspecialchars($regQuadrants[2], ENT_QUOTES, 'UTF-8'); ?></label></td>
                        <td><label><input type="radio" name="kid" value="1"<?php echo $regQuad === 1 ? ' checked="checked"' : ''; ?> /><?php echo htmlspecialchars($regQuadrants[1], ENT_QUOTES, 'UTF-8'); ?></label></td>
                    </tr>
                    <tr>
                        <td><label><input type="radio" name="kid" value="4"<?php echo $regQuad === 4 ? ' checked="checked"' : ''; ?> /><?php echo htmlspecialchars($regQuadrants[4], ENT_QUOTES, 'UTF-8'); ?></label></td>
                        <td><label><input type="radio" name="kid" value="3"<?php echo $regQuad === 3 ? ' checked="checked"' : ''; ?> /><?php echo htmlspecialchars($regQuadrants[3], ENT_QUOTES, 'UTF-8'); ?></label></td>
                    </tr>
                </table>

                <button type="submit" name="s1" value="anmelden" class="login-btn"><?php echo defined('LOGIN_SUBMIT') ? LOGIN_SUBMIT : 'تسجيل الدخول'; ?></button>
            </form>

            <p class="reg-age"><?php echo regText('REG_WORLD_STARTED', 'بدأ هذا العالم منذ'); ?> <?php echo htmlspecialchars($worldAgeLabel, ENT_QUOTES, 'UTF-8'); ?></p>

<?php } ?>
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
