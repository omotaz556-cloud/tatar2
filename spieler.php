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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo tz_html_dir_attrs(); ?>>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo SERVER_NAME ?></title>
	<link rel="shortcut icon" href="favicon.ico"/>
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<script src="mt-full.js?0faab" type="text/javascript"></script>
	<script src="unx.js?f4b7h" type="text/javascript"></script>
	<script src="new.js?0faab" type="text/javascript"></script>
	<link href="<?php echo GP_LOCATE; ?>lang/en/lang.css?f4b7d" rel="stylesheet" type="text/css" />
	<link href="<?php echo GP_LOCATE; ?>lang/en/compact.css?f4b7i" rel="stylesheet" type="text/css" />
	<?php
	// GP_LOCATE contine deja pachetul efectiv: alegerea jucatorului cand
	// e permisa si valida, altfel pachetul serverului (vezi config.php).
	echo "
	<link href='".GP_LOCATE."novaterra.css?e21d2' rel='stylesheet' type='text/css' />
	<link href='".GP_LOCATE."lang/en/lang.css?e21d2' rel='stylesheet' type='text/css' />";
	?>
	<script type="text/javascript">

		window.addEvent('domready', start);
	</script>
	<?php echo tz_rtl_stylesheet_tag(); ?>
</head>


<body class="v35 ie ie8 pg-spieler">
<div class="wrapper">
<img style="filter:chroma();" src="img/x.gif" id="msfilter" alt="" />
<div id="dynamic_header">
	</div>
<?php include("Templates/header.tpl"); ?>
<div id="mid">
<?php include("Templates/menu.tpl"); ?>
<script type="text/javascript">
				function getMouseCoords(e) {
					var coords = {};
					if (!e) var e = window.event;
					if (e.pageX || e.pageY) 	{
						coords.x = e.pageX;
						coords.y = e.pageY;
					}
					else if (e.clientX || e.clientY) 	{
						coords.x = e.clientX + document.body.scrollLeft
							+ document.documentElement.scrollLeft;
						coords.y = e.clientY + document.body.scrollTop
							+ document.documentElement.scrollTop;
					}
					return coords;
				}

				function med_mouseMoveHandler(e, desc_string){
					var coords = getMouseCoords(e);
					med_showDescription(coords, desc_string);
				}

				function med_closeDescription(){
					var layer = document.getElementById("medal_mouseover");
					layer.className = "hide";
				}

				function init_local(){
					med_init();
				}

				function med_init(){
					layer = document.createElement("div");
					layer.id = "medal_mouseover";
					layer.className = "hide";
					document.body.appendChild(layer);
				}

				function med_showDescription(coords, desc_string){
					var layer = document.getElementById("medal_mouseover");
					layer.style.top = (coords.y + 25)+ "px";
					layer.style.left = (coords.x - 20) + "px";
					layer.className = "";
					layer.innerHTML = desc_string;
				}
			   </script>
		<div id="content"  class="player">
<?php
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
            if ($viewingSelf && !$wantDetails
                && !(method_exists($session, 'isSitterSession') && $session->isSitterSession())
            ) {
                include("Templates/Profile/settings_hub.tpl");
            } else {
                include("Templates/Profile/overview.tpl");
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

        include("Templates/Profile/profile.tpl");
    }

    if($_GET['s'] == 2) {
        include("Templates/Profile/preference.tpl");
    }

    if($_GET['s'] == 3) {
        include("Templates/Profile/account.tpl");
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
</div>

<br /><br /><br /><br /><div id="side_info">
<?php
include("Templates/multivillage.tpl");
include("Templates/quest.tpl");
include("Templates/news.tpl");
if(!NEW_FUNCTIONS_DISPLAY_LINKS) {
	echo "<br><br><br><br>";
	include("Templates/links.tpl");
}
?>
 </div>
<div class="clear"></div>
</div>
<div class="footer-stopper"></div>
<div class="clear"></div>

<?php
include("Templates/footer.tpl");
include("Templates/res.tpl");
?>
<div id="stime">
<div id="ltime">
<div id="ltimeWrap">
<?php echo CALCULATED_IN;?> <b><?php
echo round(($generator->pageLoadTimeEnd()-$start_timer)*1000);
?></b> ms

<br /><?php echo SERVER_TIME;?> <span id="tp1" class="b"><?php echo date('H:i:s'); ?></span>
</div>
	</div>
</div>

<div id="ce"></div>
</body>
</html>
