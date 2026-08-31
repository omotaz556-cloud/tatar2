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

$regTribes = array(
    1 => array('name' => regText('REG_TRIBE_ROMANS', 'الرومان'), 'flag' => true),
    2 => array('name' => regText('REG_TRIBE_TEUTONS', 'الجرمان'), 'flag' => true),
    3 => array('name' => regText('REG_TRIBE_GAULS', 'الأغريق'), 'flag' => true),
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

$world = function_exists('tz_portal_world_meta') ? tz_portal_world_meta() : array();
$ageSec = !empty($world['startTs']) ? max(0, time() - (int) $world['startTs']) : 0;
if ($ageSec < 86400) {
    $hours = max(1, (int) floor($ageSec / 3600));
    $worldAgeLabel = $hours . ' ' . regText('REG_HOUR', 'ساعة');
} else {
    $worldAgeLabel = tz_portal_days_label((int) floor($ageSec / 86400));
}

$beforeRegister = defined('BEFORE_REGISTER')
    ? BEFORE_REGISTER
    : ('قبل التسجيل يمكنك قراءة <a class="portal-link" href="anleitung.php">الدليل</a> لمعرفة ميز وعيوب القبائل');

$xgif = function_exists('tz_portal_xgif') ? tz_portal_xgif() : 'img/x.gif';
$regTitle = regText('TZ_REGISTRATION', 'التسجيل');
$nameVal = htmlspecialchars($form->getValue('name'), ENT_QUOTES, 'UTF-8');
$emailVal = htmlspecialchars(stripslashes((string) $form->getValue('email')), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo tz_html_dir_attrs(); ?> class="pg-portal-register">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo SERVER_NAME; ?> - <?php echo $regTitle; ?></title>
    <link rel="shortcut icon" href="favicon.ico" />
    <?php echo function_exists('tz_portal_classic_stylesheet_tag') ? tz_portal_classic_stylesheet_tag() : ''; ?>
</head>
<body class="webkit v35 pg-portal-register">

<?php echo tz_portal_form_shell_open('register'); ?>

<h1><img class="anmelden" src="<?php echo htmlspecialchars($xgif, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($regTitle, ENT_QUOTES, 'UTF-8'); ?>" /></h1>
<h5><img class="img_u05" src="<?php echo htmlspecialchars($xgif, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($regTitle, ENT_QUOTES, 'UTF-8'); ?>" /></h5>

<?php if (REG_OPEN != true) { ?>
    <p class="error_box"><?php echo REGISTER_CLOSED; ?></p>
<?php } else { ?>

    <img class="roman" src="<?php echo htmlspecialchars($xgif, ENT_QUOTES, 'UTF-8'); ?>" alt="" />
    <p class="info"><?php echo $beforeRegister; ?></p>

    <?php
    foreach (array('winner', 'tribe', 'agree') as $errKey) {
        $errVal = $form->getError($errKey);
        if ($errVal !== '' && $errVal !== null) {
            echo '<p class="error_box"><span class="error">' . $errVal . '</span></p>';
        }
    }
    ?>

    <form method="post" name="snd" action="anmelden.php" autocomplete="off">
        <input type="hidden" name="ft" value="a1" />
        <input type="hidden" name="invited" value="<?php echo htmlspecialchars((string) $invited, ENT_QUOTES, 'UTF-8'); ?>" />
        <input type="hidden" name="agb" value="1" />

        <table id="sign_input" cellpadding="1" cellspacing="1">
            <tbody>
            <tr class="top">
                <th><?php echo defined('NICKNAME') ? NICKNAME : 'الاسم المستعار'; ?> :</th>
                <td>
                    <input class="text" type="text" name="name" value="<?php echo $nameVal; ?>" maxlength="30" autocomplete="off" />
                    <?php
                    $errName = $form->getError('name');
                    if ($errName !== '' && $errName !== null) {
                        echo '<span class="error">' . $errName . '</span>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th><?php echo regText('REG_EMAIL_LABEL', 'البريد الالكتروني'); ?> :</th>
                <td>
                    <input class="text" type="text" name="email" value="<?php echo $emailVal; ?>" maxlength="50" autocomplete="off" />
                    <?php
                    $errEmail = $form->getError('email');
                    if ($errEmail !== '' && $errEmail !== null) {
                        echo '<span class="error">' . $errEmail . '</span>';
                    }
                    ?>
                </td>
            </tr>
            <tr class="btm">
                <th><?php echo regText('REG_PW_LABEL', 'كلمة السر'); ?> :</th>
                <td>
                    <input class="text" type="password" name="pw" value="" maxlength="100" autocomplete="new-password" />
                    <?php
                    $errPw = $form->getError('pw');
                    if ($errPw !== '' && $errPw !== null) {
                        echo '<span class="error">' . $errPw . '</span>';
                    }
                    ?>
                </td>
            </tr>
            </tbody>
        </table>

        <table id="sign_select" cellpadding="1" cellspacing="1">
            <tbody>
            <tr>
                <th><img class="img_u06" src="<?php echo htmlspecialchars($xgif, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo regText('REG_CHOOSE_TRIBE', 'اختر قبيلة'); ?>" /></th>
                <th colspan="2"><img class="img_u07" src="<?php echo htmlspecialchars($xgif, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo regText('REG_START_POS', 'موقع البداية'); ?>" /></th>
            </tr>
            <tr>
                <td rowspan="3" class="nat">
                    <?php
                    foreach ($regTribes as $tid => $tribeData) {
                        $checked = ((int) $tid === $regTribe) ? ' checked="checked"' : '';
                        $tname = htmlspecialchars($tribeData['name'], ENT_QUOTES, 'UTF-8');
                        echo '<label><input class="radio" type="radio" name="vid" value="' . (int) $tid . '"' . $checked . ' />' . $tname . '</label><br />' . "\n";
                    }
                    ?>
                </td>
                <td colspan="2">
                    <label><input class="radio" type="radio" name="kid" value="0"<?php echo $regQuad === 0 ? ' checked="checked"' : ''; ?> /><?php echo htmlspecialchars($regQuadrants[0], ENT_QUOTES, 'UTF-8'); ?></label>
                </td>
            </tr>
            <tr>
                <td class="pos1"><label><input class="radio" type="radio" name="kid" value="2"<?php echo $regQuad === 2 ? ' checked="checked"' : ''; ?> /><?php echo htmlspecialchars($regQuadrants[2], ENT_QUOTES, 'UTF-8'); ?></label></td>
                <td class="pos2"><label><input class="radio" type="radio" name="kid" value="1"<?php echo $regQuad === 1 ? ' checked="checked"' : ''; ?> /><?php echo htmlspecialchars($regQuadrants[1], ENT_QUOTES, 'UTF-8'); ?></label></td>
            </tr>
            <tr>
                <td class="pos1"><label><input class="radio" type="radio" name="kid" value="4"<?php echo $regQuad === 4 ? ' checked="checked"' : ''; ?> /><?php echo htmlspecialchars($regQuadrants[4], ENT_QUOTES, 'UTF-8'); ?></label></td>
                <td class="pos2"><label><input class="radio" type="radio" name="kid" value="3"<?php echo $regQuad === 3 ? ' checked="checked"' : ''; ?> /><?php echo htmlspecialchars($regQuadrants[3], ENT_QUOTES, 'UTF-8'); ?></label></td>
            </tr>
            </tbody>
        </table>

        <p class="btn">
            <input id="btn_signup" class="dynamic_img" type="image" alt="<?php echo defined('TZ_REGISTER_SUBMIT') ? TZ_REGISTER_SUBMIT : (defined('REGISTER') ? REGISTER : 'تسجيل'); ?>" src="<?php echo htmlspecialchars($xgif, ENT_QUOTES, 'UTF-8'); ?>" name="s1" value="anmelden" />
        </p>
    </form>

    <p><?php echo regText('REG_WORLD_STARTED', 'بدأ هذا العالم منذ'); ?> <?php echo htmlspecialchars($worldAgeLabel, ENT_QUOTES, 'UTF-8'); ?></p>

<?php } ?>

<?php echo tz_portal_form_shell_close(); ?>

</body>
</html>
