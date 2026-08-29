<?php

#################################################################################
##  Filename       : statistiken.php                                           ##
##  Type           : In Game Statistics Frontend                               ##
#################################################################################

use App\Utils\AccessLogger;

include_once("GameEngine/Village.php");
AccessLogger::logRequest();

$__start = $generator->pageLoadTimeStart();
if (isset($_GET['rank'])) {
    $_POST['rank'] = $_GET['rank'];
    // Sync session when navigating via أعلى / أسفل (GET rank has no form ft).
    if (!isset($_POST['ft'])) {
        $_POST['ft'] = 'r' . (isset($_GET['id']) ? (int) $_GET['id'] : 1);
    }
}
$_GET['aid'] = $session->alliance;
$_GET['hero'] = count($database->getHero($session->uid));
$ranking->procRankReq($_GET);
$ranking->procRank($_POST);
if (isset($_GET['newdid'])) {
    $_SESSION['wid'] = $_GET['newdid'];
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $_GET['id']);
    exit;
}

$gkShell = true;
$GLOBALS['gkShell'] = true;
$gkStatRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
$GLOBALS['gkStatLiteralPage'] = $gkStatRtl;
include_once('GameEngine/GreekStat.php');

$gkStatCss = 'css/greek_maxb_stat.css';
$gkStatCssVer = is_file(__DIR__ . '/' . $gkStatCss) ? (int) @filemtime(__DIR__ . '/' . $gkStatCss) : time();

$gkPageTitle = SERVER_NAME . ' &raquo; &raquo; &raquo; ' . STATISTICS . ' (';
if (isset($_GET['id'])) {
    switch ((int) $_GET['id']) {
        case 4: $gkPageTitle .= ALLIANCES; break;
        case 2: $gkPageTitle .= VILLAGES; break;
        case 8: $gkPageTitle .= HEROES; break;
        case 50: $gkPageTitle .= defined('PLUSSTATS_TITLE') ? PLUSSTATS_TITLE : 'Graphical statistics'; break;
        case 0: $gkPageTitle .= GENERAL; break;
        case 98: $gkPageTitle .= defined('NEWS') ? NEWS : 'الأخبار'; break;
        case 99: $gkPageTitle .= defined('TZ_STAT_NATARS_TAB') ? TZ_STAT_NATARS_TAB : 'التتار'; break;
        case 7: $gkPageTitle .= defined('TZ_TOP_10') ? TZ_TOP_10 : 'أفضل 10'; break;
        case 31: $gkPageTitle .= TOP10PA; break;
        case 32: $gkPageTitle .= TOP10PD; break;
        case 41: $gkPageTitle .= defined('TZ_THE_BEST_ALLIANCES_OFF') ? TZ_THE_BEST_ALLIANCES_OFF : TOP10AA; break;
        case 42: $gkPageTitle .= defined('TZ_THE_BEST_ALLIANCES_DEF') ? TZ_THE_BEST_ALLIANCES_DEF : TOP10AD; break;
        case 43: $gkPageTitle .= defined('TZ_TOP_10_ALLIANCES') ? TZ_TOP_10_ALLIANCES : TOP10A; break;
        case 11: $gkPageTitle .= defined('TZ_THE_LARGEST_ROMANS') ? TZ_THE_LARGEST_ROMANS : TRIBE1; break;
        case 12: $gkPageTitle .= defined('TZ_THE_LARGEST_TEUTONS') ? TZ_THE_LARGEST_TEUTONS : TRIBE2; break;
        case 13: $gkPageTitle .= defined('TZ_THE_LARGEST_GAULS') ? TZ_THE_LARGEST_GAULS : TRIBE3; break;
        case 16: $gkPageTitle .= defined('TRIBE6') ? TRIBE6 : 'Huns'; break;
        case 17: $gkPageTitle .= defined('TRIBE7') ? TRIBE7 : 'Egyptians'; break;
        case 18: $gkPageTitle .= defined('TRIBE8') ? TRIBE8 : 'Spartans'; break;
        case 19: $gkPageTitle .= defined('TRIBE9') ? TRIBE9 : 'Vikings'; break;
        case 1: $gkPageTitle .= PLAYERS; break;
        case 3: $gkPageTitle .= MILESTONES; break;
        default: $gkPageTitle .= PLAYERS; break;
    }
} else {
    $gkPageTitle .= PLAYERS;
}
$gkPageTitle .= ')';

tz_greek_shell_head($gkPageTitle, 'pg-statistics', array(
    'includeNew2Js' => false,
    'extraCss' => array($gkStatCss . '?v=' . $gkStatCssVer),
));

$gkStatMenuId = isset($_GET['id']) ? (int) $_GET['id'] : -1;
$gkStatGreek = $gkStatRtl && class_exists('GreekStat') && GreekStat::isGreekStatUi();
$gkStatUseGreekOverview = $gkStatGreek && ($gkStatMenuId === -1 || $gkStatMenuId === 1);
$gkStatUseGreekAlliance = $gkStatGreek && $gkStatMenuId === 4;
$gkStatUseGreekHeroes = $gkStatGreek && $gkStatMenuId === 8;
$gkStatUseGreekNatars = $gkStatGreek && $gkStatMenuId === 99;
$gkStatUseGreekGeneral = $gkStatGreek && $gkStatMenuId === 0;
$gkStatUseGreekNews = $gkStatGreek && $gkStatMenuId === 98;
$gkStatUseGreekTop10 = $gkStatGreek && $gkStatMenuId === 7;
$gkStatUseGreekVillages = $gkStatGreek && $gkStatMenuId === 2;
$gkStatUseGreekTribe = $gkStatGreek && in_array($gkStatMenuId, array(11, 12, 13, 16, 17, 18, 19), true);
$gkStatUseGreekAttack = $gkStatGreek && $gkStatMenuId === 31;
$gkStatUseGreekDefend = $gkStatGreek && $gkStatMenuId === 32;
$gkStatUseGreekPlusStats = $gkStatGreek && $gkStatMenuId === 50;
$gkStatUseGreekUndefeated = $gkStatGreek && $gkStatMenuId === 3;
$gkStatUseGreekAllyAttack = $gkStatGreek && $gkStatMenuId === 41;
$gkStatUseGreekAllyDefend = $gkStatGreek && $gkStatMenuId === 42;
$gkStatUseGreekAllyTop10 = $gkStatGreek && $gkStatMenuId === 43;

if ($gkStatGreek) {
    tz_greek_shell_open('', array('contentWrap' => false, 'resbarInMain' => false));
} else {
    tz_greek_shell_open('statistics', array('contentWrap' => true));
}

if ($gkStatGreek) {
    GreekStat::menuOpen($gkStatMenuId);
} else {
?>
<h1><?php echo STATISTICS; ?></h1>
<div id="textmenu">
   <a href="statistiken.php" <?php if (!isset($_GET['id']) || (isset($_GET['id']) && ($_GET['id'] == 1 || $_GET['id'] == 31 || $_GET['id'] == 32 || $_GET['id'] == 7))) { echo "class=\"selected \""; } ?>><?php echo PLAYERS; ?></a>
 | <a href="statistiken.php?id=4" <?php if (isset($_GET['id']) && ($_GET['id'] == 4 || $_GET['id'] == 41 || $_GET['id'] == 42 || $_GET['id'] == 43)) { echo "class=\"selected \""; } ?>><?php echo ALLIANCES; ?></a>
 | <a href="statistiken.php?id=2" <?php if (isset($_GET['id']) && $_GET['id'] == 2) { echo "class=\"selected \""; } ?>><?php echo VILLAGES; ?></a>
 | <a href="statistiken.php?id=8" <?php if (isset($_GET['id']) && $_GET['id'] == 8) { echo "class=\"selected \""; } ?>><?php echo HEROES; ?></a>
 | <a href="statistiken.php?id=0" <?php if (isset($_GET['id']) && $_GET['id'] == 0) { echo "class=\"selected \""; } ?>><?php echo GENERAL; ?></a>
 | <a href="statistiken.php?id=3"<?php if (isset($_GET['id']) && $_GET['id'] == 3) echo ' class="selected"'; ?>><?php echo MILESTONES; ?></a>
 | <a href="index.php"<?php if (basename($_SERVER['PHP_SELF']) === 'index.php') echo ' class="selected"'; ?>><?php echo NEWS; ?></a>
 | <a href="statistiken.php?id=99" <?php if (isset($_GET['id']) && $_GET['id'] == 99) echo 'class="selected"'; ?>><?php echo WWS; ?></a>
</div>
<?php
}

if ($gkStatUseGreekOverview) {
    include('Templates/Greek/stat_overview_greek.tpl');
} elseif ($gkStatUseGreekAlliance) {
    include('Templates/Greek/stat_alliance_greek.tpl');
} elseif ($gkStatUseGreekHeroes) {
    include('Templates/Greek/stat_heroes_greek.tpl');
} elseif ($gkStatUseGreekNatars) {
    include('Templates/Greek/stat_natars_greek.tpl');
} elseif ($gkStatUseGreekGeneral) {
    include('Templates/Greek/stat_general_greek.tpl');
} elseif ($gkStatUseGreekNews) {
    include('Templates/Greek/stat_news_greek.tpl');
} elseif ($gkStatUseGreekTop10) {
    include('Templates/Greek/stat_top10_greek.tpl');
} elseif ($gkStatUseGreekVillages) {
    include('Templates/Greek/stat_villages_greek.tpl');
} elseif ($gkStatUseGreekTribe) {
    include('Templates/Greek/stat_tribe_greek.tpl');
} elseif ($gkStatUseGreekAttack) {
    include('Templates/Greek/stat_attack_greek.tpl');
} elseif ($gkStatUseGreekDefend) {
    include('Templates/Greek/stat_defend_greek.tpl');
} elseif ($gkStatUseGreekPlusStats) {
    include('Templates/Greek/stat_plusstats_greek.tpl');
} elseif ($gkStatUseGreekUndefeated) {
    include('Templates/Greek/stat_undefeated_greek.tpl');
} elseif ($gkStatUseGreekAllyAttack) {
    include('Templates/Greek/stat_ally_attack_greek.tpl');
} elseif ($gkStatUseGreekAllyDefend) {
    include('Templates/Greek/stat_ally_defend_greek.tpl');
} elseif ($gkStatUseGreekAllyTop10) {
    include('Templates/Greek/stat_ally_top10_greek.tpl');
} elseif (isset($_GET['id'])) {
    if ($gkStatGreek) {
        echo '<div class="gk-stat-legacy">';
    }
    switch ((int) $_GET['id']) {
        case 31: include("Templates/Ranking/player_attack.tpl"); break;
        case 32: include("Templates/Ranking/player_defend.tpl"); break;
        case 7: include("Templates/Ranking/player_top10.tpl"); break;
        case 2: include("Templates/Ranking/villages.tpl"); break;
        case 3: include("Templates/Ranking/milestones.tpl"); break;
        case 4: include("Templates/Ranking/alliance.tpl"); break;
        case 8: include("Templates/Ranking/heroes.tpl"); break;
        case 11: include("Templates/Ranking/player_1.tpl"); break;
        case 12: include("Templates/Ranking/player_2.tpl"); break;
        case 13: include("Templates/Ranking/player_3.tpl"); break;
        case 16: include("Templates/Ranking/player_6.tpl"); break;
        case 17: include("Templates/Ranking/player_7.tpl"); break;
        case 18: include("Templates/Ranking/player_8.tpl"); break;
        case 19: include("Templates/Ranking/player_9.tpl"); break;
        case 41: include("Templates/Ranking/alliance_attack.tpl"); break;
        case 42: include("Templates/Ranking/alliance_defend.tpl"); break;
        case 43: include("Templates/Ranking/ally_top10.tpl"); break;
        case 50: include("Templates/Ranking/statistics.tpl"); break;
        case 0: include("Templates/Ranking/general.tpl"); break;
        case 1: include("Templates/Ranking/overview.tpl"); break;
        case 99: include("Templates/Ranking/ww.tpl"); break;
    }
    if ($gkStatGreek) {
        echo '</div>';
    }
} elseif (!$gkStatUseGreekOverview) {
    include("Templates/Ranking/overview.tpl");
}

if ($gkStatGreek) {
    GreekStat::menuClose();
}

tz_greek_shell_close(array('buildPopup' => false, 'timer' => $__start));
