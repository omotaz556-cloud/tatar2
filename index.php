<?php
use App\Utils\AccessLogger;

#################################################################################
##  Filename       : index.php
##  Type           : Portal homepage (tatarwars ndix layout)
#################################################################################

if (!file_exists('var/installed') && @opendir('install')) {
    header('Location: install/');
    exit;
}

include_once('GameEngine/config.php');

error_reporting(E_ALL || E_NOTICE);

if (file_exists('Security/Security.class.php')) {
    require 'Security/Security.class.php';
    Security::instance();
} else {
    die('Security: Please activate security class!');
}

include_once 'GameEngine/Database.php';
require_once __DIR__ . '/GameEngine/Lang/loader.php';
tz_load_language(LANG);

AccessLogger::logRequest();

$stats = function_exists('tz_portal_player_stats') ? tz_portal_player_stats() : array('players' => 0, 'active' => 0, 'online' => 0);
$world = function_exists('tz_portal_world_meta') ? tz_portal_world_meta() : array();

$ageSec = !empty($world['startTs']) ? max(0, time() - (int) $world['startTs']) : 0;
$worldDays = (int) floor($ageSec / 86400);
$worldHours = (int) floor(($ageSec % 86400) / 3600);

if (!empty($world['time']) && COMMENCE > (int) $world['time']) {
    $serverStartHtml = "<font color='blue'>لم تبدأ بعد</font>";
} elseif ($ageSec > 0) {
    $serverStartHtml = '<b>' . $worldDays . '</b> أيام و <b>' . $worldHours . '</b> ساعة';
} else {
    $serverStartHtml = "<font color='red'>بانتظار البدء</font>";
}

$serverLabel = defined('SERVER_NAME') ? SERVER_NAME : 'حروب التتار';
$pageTitle = $serverLabel . ' | أقوى سيرفرات حرب التتار الكلاسيكية';
$heroTitle = 'لعبة ' . $serverLabel . ' الكلاسيكي';
$heroPara = 'سجل الان في أقوى سيرفرات ' . $serverLabel . ' واستمتع بمنافسة شرسة مع الاف اللاعبين الحقيقين بدون أي بوتات إطلاقاً !';
$aboutTitle = 'ماهي ' . $serverLabel . ' ؟';
$whatIs = defined('TZ_INDEX_WHAT_IS') ? TZ_INDEX_WHAT_IS : ('ما هي لعبة ' . $serverLabel);
$indexDesc = isset($lang['index'][0][5]) ? $lang['index'][0][5] : '';
$ndixVer = (int) (@filemtime(__DIR__ . '/css/ndix/style.css') ?: time());
$jsVer = (int) (@filemtime(__DIR__ . '/js/ndix-portal.js') ?: time());
$weltenImg = is_file(__DIR__ . '/img/ndix/images/welten/en1_big.jpg')
    ? 'img/ndix/images/welten/en1_big.jpg'
    : 'img/en/welten/en1_big.jpg';
$heroImg = is_file(__DIR__ . '/img/ndix/newIndex/imgs/hero.png')
    ? 'img/ndix/newIndex/imgs/hero.png'
    : (is_file(__DIR__ . '/img/login/reg_soldier.png') ? 'img/login/reg_soldier.png' : 'img/en/welten/en1_big.jpg');
?>
<!DOCTYPE html>
<html <?php echo tz_html_dir_attrs(); ?> class="pg-portal-index">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?> هى لعبة مجانية لا تحتاج الى تحميل ,لعبة حرب في عالم مليء باللاعبين الحقيقين الذين يبدأون جميعهم كزعماء لقرى صغيرة." />
    <meta name="content-language" content="<?php echo htmlspecialchars(LANG, ENT_QUOTES, 'UTF-8'); ?>" />
    <link rel="shortcut icon" href="favicon.ico" />
    <?php echo function_exists('tz_portal_ndix_stylesheet_tag') ? tz_portal_ndix_stylesheet_tag() : ''; ?>
    <style id="portal-header-align">
    /* Coordinates from main.jpg 1332×421 — must load after ndix/style.css */
    body.pg-portal-index header .hero-section nav.nav ul,
    body.pg-portal-index header .hero-section .nav ul {
        position: static !important;
        top: 0 !important;
        right: auto !important;
        margin: 0 !important;
        max-width: none !important;
        width: 100% !important;
        justify-content: space-between !important;
        gap: 0 !important;
        padding: 0 14% 0 8% !important;
    }
    body.pg-portal-index header .hero-section nav.nav,
    body.pg-portal-index header .hero-section .nav {
        left: calc(50% + 150px) !important;
        top: calc(10.45% + 55px) !important;
        width: 30% !important;
        max-width: none !important;
        height: 6.2% !important;
        transform: translateX(-50%) !important;
        display: flex !important;
        align-items: center !important;
        background: none !important;
        border: 0 !important;
        box-shadow: none !important;
    }
    body.pg-portal-index header .hero-section .world {
        position: absolute !important;
        display: table !important;
        left: calc(50% - 108px) !important;
        top: calc(61.28% + 50px) !important;
        transform: translateX(-50%) !important;
        margin: 0 auto !important;
    }
    body.pg-portal-index header .hero-section .world a {
        position: relative !important;
        height: 42px !important;
        color: #691009 !important;
        top: auto !important;
        left: auto !important;
    }
    </style>
</head>
<body class="pg-portal-index">

<header>
    <div class="hero-section">
        <nav class="nav">
            <ul>
                <li><a href="anmelden.php" onclick="showModal(2);return false;" title="التسجيل">التسجيل</a></li>
                <li><a href="login.php" onclick="showModal(1);return false;" title="الدخول">الدخول</a></li>
                <li><button type="button" onclick="showSide();return false;">صفحات أخرى</button></li>
            </ul>
        </nav>
        <div class="world">
            <a href="anmelden.php" title="سجل في <?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?>">
                <span class="world-title">سجل في أخر عالم</span>
                <span class="world-reg">(<?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?>)</span>
            </a>
        </div>
    </div>
</header>

<div class="container">
    <aside>
        <div class="menu">
            <div class="menu-header">
                <div class="menu-title">قائمة الصفحات</div>
                <span class="close" onclick="closeSide()">X</span>
            </div>
            <div class="menu-body">
                <ul>
                    <li><a href="index.php">الرئيسية</a></li>
                    <li><a href="anmelden.php">سجل الآن</a></li>
                    <li><a href="login.php">دخول</a></li>
                    <li><a href="spielregeln.php"><?php echo defined('SPIELREGELN') ? SPIELREGELN : 'قواعد اللعبة'; ?></a></li>
                    <li><a href="anleitung.php"><?php echo defined('FAQ') ? FAQ : 'الدليل'; ?></a></li>
                    <li><a href="manual.php"><?php echo defined('LOGIN_GAME_GUIDE') ? LOGIN_GAME_GUIDE : 'شرح اللعبة'; ?></a></li>
                </ul>
            </div>
        </div>
    </aside>

    <main>
        <section id="hero-side">
            <div class="main-data">
                <h1><?php echo htmlspecialchars($heroTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
                <p class="para"><?php echo htmlspecialchars($heroPara, ENT_QUOTES, 'UTF-8'); ?></p>
                <a href="anmelden.php" class="btn-primary" title="التسجيل">التسجيل في أخر عالم</a>
                <div class="img-container">
                    <picture>
                        <img width="288" height="298" src="<?php echo htmlspecialchars($heroImg, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?>" />
                    </picture>
                </div>
            </div>
        </section>

        <section id="about">
            <div class="max-width">
                <div class="about">
                    <h2 class="title"><?php echo htmlspecialchars($aboutTitle, ENT_QUOTES, 'UTF-8'); ?></h2>
                    <div class="border-right">
                        <h3 style="font-weight:301">هي لعبة متصفح مجانية لاتحتاج إلى تحميل من العاب <strong>الحرب الاستراتيجية</strong></h3>
                        <h3 style="font-weight:301">وهي عبارة عن لعبة حرب في عالم مليء باللاعبين الحقيقين الذين يبدأون جميعهم كزعماء لقرى صغيرة.</h3>
                        <h3 style="font-weight:301">في <?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?> تبني المباني من الثكن الحربية والسفارات والمخازن تتطور القرى الصغيرة لتصبح ممالك.</h3>
                        <br />
                        <h2 style="text-align:right"><?php echo $whatIs; ?>:</h2>
                        <?php if ($indexDesc !== '') { ?>
                            <div><?php echo $indexDesc; ?></div>
                        <?php } else { ?>
                        <ul>
                            <li style="text-align:right">ستبدأ كرئيس قرية صغيرة</li>
                            <li style="text-align:right">ستبني قريتك وتطور مواردك وجيشك</li>
                            <li style="text-align:right">ستحارب مع أو ضد لاعبين حقيقيين وتنضم لتحالف</li>
                        </ul>
                        <?php } ?>
                    </div>
                    <div class="btn-group">
                        <a class="btn-primary" href="anmelden.php" title="سجل في اللعبة">سجل والعب الان</a>
                        <a class="btn-secondray" href="#latestServer" title="معلومات عن اللعبة">معلومات عن اللعبة</a>
                    </div>
                </div>

                <div class="left-hero">
                    <h2 class="title">احصائات اللعبة</h2>
                    <div class="stat">
                        <span class="icon">&#9679;</span>
                        <article class="card-entry__meta">
                            <strong>عدد اللاعبين:</strong>
                            <p><span><?php echo (int) $stats['players']; ?></span> لاعب</p>
                        </article>
                    </div>
                    <div class="stat">
                        <span class="icon">&#9679;</span>
                        <article class="card-entry__meta">
                            <strong>اللاعبون النشطون:</strong>
                            <p><span class="num"><?php echo (int) $stats['active']; ?></span> لاعب</p>
                        </article>
                    </div>
                    <div class="stat">
                        <span class="icon">&#9679;</span>
                        <article class="card-entry__meta">
                            <strong>المتواجدون الان:</strong>
                            <p><span class="num"><?php echo (int) $stats['online']; ?></span> لاعب</p>
                        </article>
                    </div>
                    <div class="stat">
                        <span class="icon">&#9679;</span>
                        <article class="card-entry__meta">
                            <strong>عدد السيرفرات:</strong>
                            <p><span class="num"><?php echo defined('TZ_INDEX_SERVERS_COUNT') ? TZ_INDEX_SERVERS_COUNT : '1'; ?></span> سيرفر</p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section id="latestServer">
            <h2 class="title">أخر سيرفر تم افتتاحه : <?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?></h2>
            <p>سجل الان في أحدث سيرفرات اللعبة لتكون فرصة نجاحك أعلى لحداثة السيرفر.</p>
            <div class="server-details-box">
                <h3>مواصفات السيرفر:</h3>
                <div class="server-data-grid">
                    <ul>
                        <li>نوع السيرفر: <span>سيرفر عادي</span></li>
                        <li>سرعة السيرفر: <span>X<?php echo htmlspecialchars((string) SPEED, ENT_QUOTES, 'UTF-8'); ?></span></li>
                        <li>عدد اللاعبين: <b><?php echo (int) $stats['players']; ?> لاعب</b></li>
                        <li>بداية السيرفر: <span>منذ <?php echo $serverStartHtml; ?></span></li>
                    </ul>
                    <img loading="lazy" src="<?php echo htmlspecialchars($weltenImg, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?>" width="200" height="120" />
                </div>
                <a href="anmelden.php" class="btn-primary full-width center" title="التسجيل">سجل في أخر سيرفر</a>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-data">
            <ul>
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="login.php">دخول</a></li>
                <li><a href="anmelden.php">التسجيل</a></li>
                <li><a href="spielregeln.php"><?php echo defined('SPIELREGELN') ? SPIELREGELN : 'قوانين اللعب'; ?></a></li>
                <li><a href="manual.php">شرح اللعب</a></li>
            </ul>
        </div>
    </footer>

<div id="register-box">
    <div>اخر عالم تم افتتاحه هو : <b><?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?></b></div>
    <a class="btn-primary" href="anmelden.php" title="سجل الآن">سجل الان</a>
</div>

<div id="modal">
    <div class="musk"></div>
    <div class="modal-data">
        <div class="modal-top">
            <div class="modal-title">أختر عالماً لتسجيل الدخول</div>
            <span class="close" onclick="closeModal()">X</span>
        </div>
        <div class="modal-body">
            <div class="server-item1">
                <div class="serverItem">
                    <a href="login.php" class="server-href" title="<?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?>">
                        <img loading="lazy" src="<?php echo htmlspecialchars($weltenImg, ENT_QUOTES, 'UTF-8'); ?>" alt="" />
                        <div class="serverData">
                            <div class="serverInfos">عدد اللاعبين: <span class="playerCounts"><?php echo (int) $stats['players']; ?> لاعب</span></div>
                            <div class="serverInfos">منذ: <span class="startedSince"><?php echo $serverStartHtml; ?></span></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="server-item2">
                <div class="serverItem">
                    <a href="anmelden.php" class="server-href" title="<?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?>">
                        <img loading="lazy" src="<?php echo htmlspecialchars($weltenImg, ENT_QUOTES, 'UTF-8'); ?>" alt="" />
                        <div class="serverData">
                            <div class="serverInfos">عدد اللاعبين: <span class="playerCounts"><?php echo (int) $stats['players']; ?> لاعب</span></div>
                            <div class="serverInfos">منذ: <span class="startedSince"><?php echo $serverStartHtml; ?></span></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<script src="js/ndix-portal.js?v=<?php echo $jsVer; ?>" type="text/javascript"></script>
<?php if (isset($_GET['login'])) { ?>
<script type="text/javascript">document.addEventListener('DOMContentLoaded', function () { showModal(1); });</script>
<?php } elseif (isset($_GET['signup'])) { ?>
<script type="text/javascript">document.addEventListener('DOMContentLoaded', function () { showModal(2); });</script>
<?php } ?>

</body>
</html>
