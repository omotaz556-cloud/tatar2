<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : berucgte.php                      	                       ##
##  Type           : In Game Messages Page                                     ##
## --------------------------------------------------------------------------- ##
##  Developed by   : yi12345					                               ##
##  Refactored by  : Shadow                                                    ##
##  Redesign by    : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  URLs:          : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################

use App\Utils\AccessLogger;

include_once("GameEngine/Village.php");
AccessLogger::logRequest();

if (isset($_GET['newdid'])) {
	$_SESSION['wid'] = $_GET['newdid'];
    if (isset($_GET['t'])) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?t=" . $_GET['t']);
        exit;
    } elseif (isset($_GET['vill']) && isset($_GET['id'])) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $_GET['id'] . "&vill=" . $_GET['vill'] . "");
        exit;
    } elseif (isset($_GET['id']) && $_GET['id'] != 0) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $_GET['id']);
        exit;
    } else {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

$gkBerichteRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
$GLOBALS['gkBerichteLiteralPage'] = $gkBerichteRtl;

if ($gkBerichteRtl && isset($_GET['readall']) && (int) $_GET['readall'] === 1) {
    $database->markAllNoticesRead($session->uid);
    $redir = 'berichte.php';
    $qs = array();
    if (isset($_GET['t']) && (int) $_GET['t'] > 0) {
        $qs['t'] = (int) $_GET['t'];
    }
    if (isset($_GET['f']) && (int) $_GET['f'] > 0) {
        $qs['f'] = (int) $_GET['f'];
    }
    if ($qs) {
        $redir .= '?' . http_build_query($qs);
    }
    header('Location: ' . $redir);
    exit;
}

$message->noticeType($_GET);
$message->procNotice($_POST);

$gkShell = true;
$GLOBALS['gkShell'] = true;
include_once('GameEngine/GreekBerichte.php');

$gkBerichteCss = 'css/greek_maxb_berichte.css';
$gkBerichteCssVer = is_file(__DIR__ . '/' . $gkBerichteCss) ? (int) @filemtime(__DIR__ . '/' . $gkBerichteCss) : time();
$gkBerichteGreek = $gkBerichteRtl && class_exists('GreekBerichte') && GreekBerichte::isGreekBerichteUi();
$gkBerichteTab = isset($_GET['t']) ? (int) $_GET['t'] : 0;

$gkHeadOpts = array('includeNew2Js' => false);
if ($gkBerichteGreek) {
    $gkHeadOpts['extraCss'] = array($gkBerichteCss . '?v=' . $gkBerichteCssVer);
}

$gkPageTitle = SERVER_NAME . ' - ' . REPORTS;
tz_greek_shell_head($gkPageTitle, 'pg-berichte', $gkHeadOpts);

if ($gkBerichteGreek) {
    tz_greek_shell_open('', array('contentWrap' => false));
    GreekBerichte::menuOpen($gkBerichteTab);
    GreekBerichte::attackFilterOpen();
    echo '<div class="gk-berichte-body">';
} else {
    tz_greek_shell_open('reports', array('contentWrap' => true));
?>
<h1><?php echo REPORTS; ?></h1>
<div id="textmenu">
   <a href="berichte.php" <?php if (!isset($_GET['t'])) { echo "class=\"selected \""; } ?>><?php echo ALL; ?></a>
 | <a href="berichte.php?t=2" <?php if (isset($_GET['t']) && $_GET['t'] == 2) { echo "class=\"selected \""; } ?>><?php echo TZ_TRADE; ?></a>
 | <a href="berichte.php?t=1" <?php if (isset($_GET['t']) && $_GET['t'] == 1) { echo "class=\"selected \""; } ?>><?php echo REINFORCEMENT; ?></a>
 | <a href="berichte.php?t=3" <?php if (isset($_GET['t']) && $_GET['t'] == 3) { echo "class=\"selected \""; } ?>><?php echo TZ_ATTACKS; ?></a>
 | <a href="berichte.php?t=6" <?php if (isset($_GET['t']) && $_GET['t'] == 6) { echo "class=\"selected \""; } ?>><?php echo defined('TZ_RPT_DEFENSE') ? TZ_RPT_DEFENSE : 'الدفاع'; ?></a>
 | <a href="berichte.php?t=7" <?php if (isset($_GET['t']) && $_GET['t'] == 7) { echo "class=\"selected \""; } ?>><?php echo defined('TZ_RPT_SCOUT_TAB') ? TZ_RPT_SCOUT_TAB : 'التجسس'; ?></a>
 | <a href="berichte.php?t=4" <?php if (isset($_GET['t']) && $_GET['t'] == 4) { echo "class=\"selected \""; } ?>><?php echo defined('TZ_OTHER') ? TZ_OTHER : TZ_MISCELLANEOUS; ?></a>
 | <a href="berichte.php?t=8" <?php if (isset($_GET['t']) && $_GET['t'] == 8) { echo "class=\"selected \""; } ?>><?php echo defined('TZ_RPT_MISSION') ? TZ_RPT_MISSION : 'مهمة'; ?></a>
 <?php if ($session->plus) {
 echo "| <a href=\"berichte.php?t=5\"";
 if (isset($_GET['t']) && $_GET['t'] == 5) { echo "class=\"selected \""; }
 echo ">".ARCHIVE."</a>";
 }
 ?>
</div>

<?php
if (isset($_GET['t']) && (int) $_GET['t'] === 3) {
    $rptFilters = array(
        0 => defined('TZ_RPT_ALL_RESULTS') ? TZ_RPT_ALL_RESULTS : 'All',
        1 => defined('TZ_RPT_F_WON_NOLOSS') ? TZ_RPT_F_WON_NOLOSS : 'Won without losses',
        2 => defined('TZ_RPT_F_WON_LOSS') ? TZ_RPT_F_WON_LOSS : 'Won with losses',
        3 => defined('TZ_RPT_F_LOST') ? TZ_RPT_F_LOST : 'Lost',
    );
    $rptCurrent = isset($_GET['f']) ? (int) $_GET['f'] : 0;
    echo '<div id="textmenu" class="rpt-result-filter">';
    foreach ($rptFilters as $rptVal => $rptLabel) {
        if ($rptVal > 0) {
            echo ' | ';
        }
        $rptHref = 'berichte.php?t=3' . ($rptVal > 0 ? '&amp;f=' . $rptVal : '');
        echo '<a href="' . $rptHref . '"'
           . ($rptCurrent === $rptVal ? ' class="selected "' : '')
           . '>' . htmlspecialchars($rptLabel, ENT_QUOTES, 'UTF-8') . '</a>';
    }
    echo '</div>';
}
}

if (isset($_GET['id'])) {
    if (isset($_GET['aid']) && $_GET['aid'] > 0 && $_GET['aid'] == $session->alliance && $database->getNotice2($_GET['id'], 'ally') == $session->alliance) {
        $type = $database->getNotice2($_GET['id'], 'ntype');
        if ($type >= 10 && $type <= 17) {
            unset($type);
        }
    } elseif (isset($_GET['vill']) && $database->getNotice2($_GET['id'], 'ally') == $session->alliance) {
        $type = $database->getNotice2($_GET['id'], 'ntype');
        if ($type >= 10 && $type <= 17) {
            unset($type);
        }
    } elseif ($database->getNotice2(preg_replace("/[^a-zA-Z0-9_-]/", "", $_GET['id']), 'uid') == $session->uid) {
        $type = ($message->readingNotice['ntype'] == 9) ? $message->readingNotice['archive'] : $message->readingNotice['ntype'];
    }

    if (isset($type)) {
        include("Templates/Notice/" . $message->getReportType($type) . ".tpl");
    }
    unset($type);
} else {
    include("Templates/Notice/all.tpl");
}

if ($gkBerichteGreek) {
    echo '</div>';
    GreekBerichte::menuClose();
}

tz_greek_shell_close(array('buildPopup' => false, 'timer' => $start_timer));
