<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : spieler.php                      	                       ##
##  Type           : In Game Profile Page                                      ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Dzoki 						                               ##
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

ob_start();
include_once("GameEngine/Village.php");
AccessLogger::logRequest();

/**
 * RESTRICTIE SITTER pe pagina de profil.
 *
 * Un sitter are voie DOAR la Overview (spieler.php?uid=...). Taburile
 * Profile / Preferences / Account / Vacation / Graphic Pack / Options hub
 * (s=1..6) sunt interzise, inclusiv prin URL scris de mana sau POST.
 *
 * De ce aici si nu la finalul fisierului, unde exista deja o verificare
 * "$_GET['s'] > 5 or $session->sit == 1":
 *   - procProfile($_POST) ruleaza la linia urmatoare si salveaza efectiv
 *     setarile; verificarea de la final se executa mult dupa;
 *   - graphic.tpl si preference.tpl scriu direct in baza de date la include,
 *     tot inainte de acea verificare.
 * Cu ob_start() activ, redirectul de la final ascundea doar PAGINA, nu si
 * efectele - un sitter putea schimba setarile proprietarului fara sa vada
 * vreun ecran.
 */
if (isset($session) && is_object($session) && method_exists($session, 'isSitterSession')
    && $session->isSitterSession() && isset($_GET['s'])) {

    header("Location: " . $_SERVER['PHP_SELF'] . "?uid=" . (int) $session->uid);
    exit;
}

$profile->procProfile($_POST);
$profile->procSpecial($_GET);

/**
 * Profile hub toggles / cycles (one-page settings).
 * Ensures preference columns exist, writes DB, clears 30s user cache.
 */
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && (isset($_POST['hub_toggle']) || isset($_POST['hub_cycle']))
    && isset($session) && is_object($session)
    && !(method_exists($session, 'isSitterSession') && $session->isSitterSession())
) {
    $uid = (int) $session->uid;

    // Same column bootstrap as Profile::updatePreferences
    foreach ([
        'mobile_mode' => "TINYINT(1) NOT NULL DEFAULT '0'",
        'timer_refresh' => "TINYINT(1) NOT NULL DEFAULT '0'",
        'invert_colors' => "TINYINT(1) NOT NULL DEFAULT '0'",
        'stats_format' => "TINYINT(1) NOT NULL DEFAULT '0'",
        'night_mode' => "TINYINT(1) NOT NULL DEFAULT '0'",
        'upgrade_redirect' => "TINYINT(1) NOT NULL DEFAULT '0'",
        'map' => "TINYINT(1) NOT NULL DEFAULT '0'",
        'v4' => "TINYINT(1) NOT NULL DEFAULT '0'",
        'v5' => "TINYINT(1) NOT NULL DEFAULT '0'",
        'v6' => "TINYINT(1) NOT NULL DEFAULT '0'",
        'web_notifications' => "TINYINT(1) NOT NULL DEFAULT '0'",
    ] as $column => $definition) {
        $columnCheck = mysqli_query(
            $database->dblink,
            "SHOW COLUMNS FROM `" . TB_PREFIX . "users` LIKE '" . $column . "'"
        );
        if ($columnCheck && mysqli_num_rows($columnCheck) === 0) {
            mysqli_query(
                $database->dblink,
                "ALTER TABLE `" . TB_PREFIX . "users` ADD COLUMN `" . $column . "` " . $definition
            );
        }
    }

    $setParts = [];
    if (isset($_POST['hub_toggle'])) {
        $field = (string) $_POST['hub_toggle'];
        $on = !empty($_POST['hub_on']);
        if ($field === 'night_mode') {
            // ON = dark (2), OFF = light (1)
            $setParts[] = 'night_mode=' . ($on ? 2 : 1);
        } elseif ($field === 'stop_auto_update') {
            // ON = stop refresh → timer_refresh=0; OFF = allow refresh → 1
            $setParts[] = 'timer_refresh=' . ($on ? 0 : 1);
        } elseif ($field === 'timer_refresh') {
            $setParts[] = 'timer_refresh=' . ($on ? 1 : 0);
        } elseif ($field === 'map') {
            $setParts[] = 'map=' . ($on ? 1 : 0);
        } elseif ($field === 'invert_colors') {
            $setParts[] = 'invert_colors=' . ($on ? 1 : 0);
        } elseif ($field === 'v4') {
            $setParts[] = 'v4=' . ($on ? 1 : 0);
        } elseif ($field === 'v5') {
            $setParts[] = 'v5=' . ($on ? 1 : 0);
        } elseif ($field === 'v6') {
            $setParts[] = 'v6=' . ($on ? 1 : 0);
        } elseif ($field === 'mobile_mode') {
            // 0=auto, 1=desktop, 2=mobile — hub toggle: ON=mobile(2), OFF=desktop(1)
            $setParts[] = 'mobile_mode=' . ($on ? 2 : 1);
        } elseif ($field === 'web_notifications') {
            $setParts[] = 'web_notifications=' . ($on ? 1 : 0);
        }
    }
    if (isset($_POST['hub_cycle'])) {
        $cycle = (string) $_POST['hub_cycle'];
        if ($cycle === 'upgrade_redirect') {
            $cur = (int) ($session->userinfo['upgrade_redirect'] ?? 0);
            $setParts[] = 'upgrade_redirect=' . (($cur + 1) % 3);
        } elseif ($cycle === 'stats_format') {
            $cur = (int) ($session->userinfo['stats_format'] ?? 0);
            $setParts[] = 'stats_format=' . (($cur + 1) % 3);
        } elseif ($cycle === 'night_mode') {
            // 0 auto → 1 light → 2 dark → 0
            $cur = (int) ($session->userinfo['night_mode'] ?? 0);
            $setParts[] = 'night_mode=' . (($cur + 1) % 3);
        }
    }

    if ($setParts) {
        $database->query(
            'UPDATE ' . TB_PREFIX . 'users SET ' . implode(', ', $setParts)
            . ' WHERE id=' . $uid
        );
        $cacheKeyUser = 'cache_user_' . ($_SESSION['username'] ?? '');
        unset($_SESSION[$cacheKeyUser]);
        // Refresh in-memory userinfo for this request / redirect target
        foreach ($setParts as $part) {
            if (preg_match('/^([a-z_]+)=(\d+)$/', $part, $m)) {
                $session->userinfo[$m[1]] = (int) $m[2];
                if ($m[1] === 'night_mode') {
                    $_SESSION['night_mode'] = (int) $m[2];
                }
            }
        }
    }

    header('Location: spieler.php?uid=' . $uid);
    exit;
}

if(isset($_GET['newdid'])){
	$_SESSION['wid'] = (int) $_GET['newdid'];
	// Village list / deep links: open the village overview, not the profile.
	header('Location: dorf1.php');
	exit();
}
else $building->procBuild($_GET);

$gkShell = true;
$GLOBALS['gkShell'] = true;

$gkSpielerRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
$GLOBALS['gkSpielerLiteralPage'] = $gkSpielerRtl;
if ($gkSpielerRtl) {
    $GLOBALS['gkSpielerBarRendered'] = true;
}
include_once('GameEngine/GreekSpieler.php');

$gkSpielerCss = 'css/greek_maxb_spieler.css';
$gkSpielerCssVer = is_file(__DIR__ . '/' . $gkSpielerCss) ? (int) @filemtime(__DIR__ . '/' . $gkSpielerCss) : time();
$gkSpielerGreek = $gkSpielerRtl && class_exists('GreekSpieler');
$GLOBALS['gkSpielerGreek'] = $gkSpielerGreek;
$GLOBALS['gkSpielerNameReserve'] = false;

if ($gkSpielerGreek && isset($_GET['s']) && (int) $_GET['s'] === 2) {
    if (isset($_GET['del']) && is_numeric($_GET['del'])
        && !(method_exists($session, 'isSitterSession') && $session->isSitterSession())
    ) {
        $database->removeLinks((int) $_GET['del'], $session->uid);
        header('Location: spieler.php?s=2&dl=1');
        exit;
    }
    if (empty($_GET['dl'])) {
        header('Location: spieler.php?s=2&dl=1');
        exit;
    }
}

$gkSpielerNameReserve = $gkSpielerGreek
    && isset($_GET['s'])
    && (int) $_GET['s'] === 3
    && isset($_GET['nr'])
    && (string) $_GET['nr'] !== ''
    && (string) $_GET['nr'] !== '0';
$GLOBALS['gkSpielerNameReserve'] = $gkSpielerNameReserve;

if ($gkSpielerGreek && isset($_GET['s']) && (int) $_GET['s'] === 3 && isset($_GET['nr']) && !$gkSpielerNameReserve) {
    header('Location: spieler.php?s=3&nr=1');
    exit;
}

$gkSpielerTab = 1;
if (isset($_GET['s'])) {
    $gkSpielerS = (int) $_GET['s'];
    if ($gkSpielerS === 3) {
        $gkSpielerTab = !empty($GLOBALS['gkSpielerNameReserve']) ? 5 : 3;
    } elseif ($gkSpielerS === 2 && !empty($_GET['dl'])) {
        $gkSpielerTab = 4;
    } elseif ($gkSpielerS === 1) {
        $gkSpielerTab = 2;
    } elseif ($gkSpielerS === 2) {
        $gkSpielerTab = !empty($_GET['dl']) ? 4 : 0;
    } elseif (in_array($gkSpielerS, array(4, 5), true)) {
        $gkSpielerTab = 6;
    }
} elseif (isset($_GET['uid'])) {
    $gkViewUid = (int) preg_replace('/[^0-9]/', '', (string) $_GET['uid']);
    if (!empty($_GET['hub']) && $gkViewUid === (int) $session->uid) {
        $gkSpielerTab = 6;
    } else {
        $gkSpielerTab = 1;
    }
}

$gkPageTitle = SERVER_NAME;
$gkMedalScripts = '
function getMouseCoords(e) {
	var coords = {};
	if (!e) var e = window.event;
	if (e.pageX || e.pageY) {
		coords.x = e.pageX;
		coords.y = e.pageY;
	} else if (e.clientX || e.clientY) {
		coords.x = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
		coords.y = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
	}
	return coords;
}
function med_mouseMoveHandler(e, desc_string) {
	var coords = getMouseCoords(e);
	med_showDescription(coords, desc_string);
}
function med_closeDescription() {
	var layer = document.getElementById("medal_mouseover");
	layer.className = "hide";
}
function init_local() { med_init(); }
function med_init() {
	layer = document.createElement("div");
	layer.id = "medal_mouseover";
	layer.className = "hide";
	document.body.appendChild(layer);
}
function med_showDescription(coords, desc_string) {
	var layer = document.getElementById("medal_mouseover");
	layer.style.top = (coords.y + 25) + "px";
	layer.style.left = (coords.x - 20) + "px";
	layer.className = "";
	layer.innerHTML = desc_string;
}';
if ($gkSpielerGreek && isset($_GET['s'])) {
    if ((int) $_GET['s'] === 2 && !empty($_GET['dl'])) {
        $gkMedalScripts .= '
document.addEventListener("DOMContentLoaded", function () {
	var el = document.getElementById("links");
	if (el) { el.scrollIntoView({ block: "start" }); }
});';
    }
    if ((int) $_GET['s'] === 3 && !empty($_GET['nr'])) {
        $gkMedalScripts .= '
document.addEventListener("DOMContentLoaded", function () {
	var el = document.getElementById("name_reservation");
	if (el) { el.scrollIntoView({ block: "start" }); }
});';
    }
}
$gkHeadOpts = array('includeNew2Js' => false);
if ($gkSpielerGreek) {
    $gkHeadOpts['extraCss'] = array($gkSpielerCss . '?v=' . $gkSpielerCssVer);
}
tz_greek_shell_head($gkPageTitle, 'pg-spieler', $gkHeadOpts);

if ($gkSpielerGreek) {
    $gkProfUid = isset($_GET['uid']) ? (int) preg_replace('/[^0-9]/', '', (string) $_GET['uid']) : (int) $session->uid;
    if ($gkProfUid < 2) {
        $gkProfUid = (int) $session->uid;
    }
    tz_greek_shell_open('', array('contentWrap' => false, 'resbarInMain' => false));
    GreekSpieler::menuOpen($gkSpielerTab, $gkProfUid, isset($_GET['s']) ? (int) $_GET['s'] : 0);
} else {
    tz_greek_shell_open('player', array('contentWrap' => true));
}
if(isset($_GET['uid'])) {

    if($_GET['uid'] >= 2) {

        $user = $database->getUserArray(preg_replace("/[^a-zA-Z0-9_-]/","",$_GET['uid']),1);

        if(isset($user['id'])){

            // 🔴 SHOW VACATION ERROR (IMPORTANT)
            if(isset($_SESSION['vac_error'])){
                echo "<div class='error'>".nl2br($_SESSION['vac_error'])."</div>";
                unset($_SESSION['vac_error']);
            }

            // Own profile home = one-page settings hub (Manage → separate tabs).
            // Classic overview (villages / medals): ?uid=X&details=1
            // Other players always see the classic overview.
            $viewingSelf = ((int) $user['id'] === (int) $session->uid);
            $wantDetails = !empty($_GET['details']);
            $wantHub = !empty($_GET['hub']);
            if ($gkSpielerGreek) {
                if ($wantHub && $viewingSelf
                    && !(method_exists($session, 'isSitterSession') && $session->isSitterSession())
                ) {
                    include('Templates/Greek/options_greek.tpl');
                } else {
                    include('Templates/Greek/spieler_overview_greek.tpl');
                }
            } elseif ($viewingSelf && !$wantDetails
                && !(method_exists($session, 'isSitterSession') && $session->isSitterSession())
            ) {
                include('Templates/Profile/settings_hub.tpl');
            } else {
                include('Templates/Profile/overview.tpl');
            }

        } else {
            include("Templates/Profile/notfound.tpl");
        }

    } else {
        include("Templates/Profile/special.tpl");
    }

}
else if (isset($_GET['s'])) {

    if($_GET['s'] == 1) {

        if ($gkSpielerGreek) {
            include('Templates/Greek/spieler_edit_greek.tpl');
        } else {
            include('Templates/Profile/profile.tpl');
        }
    }

    if($_GET['s'] == 2) {
        if ($gkSpielerGreek && !empty($_GET['dl'])) {
            include('Templates/Greek/links_greek.tpl');
        } else {
            include('Templates/Profile/preference.tpl');
        }
    }

    if($_GET['s'] == 3) {
        if (!empty($GLOBALS['gkSpielerNameReserve'])) {
            include('Templates/Greek/name_reserve_greek.tpl');
        } elseif ($gkSpielerGreek) {
            include('Templates/Greek/account_greek.tpl');
        } else {
            include('Templates/Profile/account.tpl');
        }
    }

    if($_GET['s'] == 4) {
        include("Templates/Profile/graphic.tpl");
    }

    if($_GET['s'] == 5) {

        include("Templates/Profile/vacation.tpl");
    }

    if($_GET['s'] == 6) {
        // Legacy link: options hub is now the profile home
        header('Location: spieler.php?uid=' . (int) $session->uid);
        exit;
    }

    if($_GET['s'] > 6 or $session->sit == 1) {
        header("Location: ".$_SERVER['PHP_SELF']."?uid=".preg_replace("/[^a-zA-Z0-9_-]/","",$session->uid));
        exit;
    }
}
?>
<?php
if ($gkSpielerGreek) {
    GreekSpieler::menuClose();
}
tz_greek_shell_close(array('buildPopup' => false, 'timer' => $start_timer, 'extraScripts' => $gkMedalScripts));
