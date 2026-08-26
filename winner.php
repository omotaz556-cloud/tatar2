<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##  Filename       : winner.php                                                ##
##  End-of-era victory report (Wonder of the World level 100).                 ##
#################################################################################

use App\Utils\AccessLogger;

if (!function_exists('mysqli_result')) {
    function mysqli_result($res, $row, $field = 0) {
        $res->data_seek($row);
        $datarow = $res->fetch_array();
        return $datarow[$field];
    }
}

include_once("GameEngine/Village.php");
AccessLogger::logRequest();

if (isset($_GET['newdid'])) {
    $_SESSION['wid'] = $_GET['newdid'];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/** Acknowledge report and return to the village. */
if (isset($_GET['ok'])) {
    $_SESSION['winner_ack'] = 1;
    header('Location: dorf1.php');
    exit;
}

/**
 * Preview mode (?preview=1) — show the report with sample data so design
 * can be reviewed without a real WW level 100. Logged-in players only.
 */
$isPreview = isset($_GET['preview']) && (string) $_GET['preview'] === '1';

$sql = mysqli_query(
    $database->dblink,
    "SELECT 1 FROM " . TB_PREFIX . "fdata WHERE f99 = '100' AND f99t = '40' LIMIT 1"
);
$winner = $sql ? mysqli_fetch_row($sql) : false;
if (!$winner && !$isPreview) {
    header("Location: dorf1.php");
    exit;
}

$accessLimit = (defined('INCLUDE_ADMIN') && INCLUDE_ADMIN) ? 10 : 8;
$tribeFilter = "u.tribe IN (1,2,3,6,7,8,9)";

$topPop = null;
$topAtt = null;
$topDef = null;
$topHero = null;
$vref = 0;
$winningvillagename = '';
$wwWinnerUid = 0;
$wwWinnerName = '';
$allianceid = 0;
$winningalliance = '';
$winningalliancetag = '';
$finishconstruction = 0;

$serverName = defined('SERVER_NAME') ? SERVER_NAME : '';
$worldLabel = defined('SERVER_WORLD_NUMBER') ? SERVER_WORLD_NUMBER : '';
$goldPrize = defined('WW_WINNER_GOLD_PRIZE') ? (int) WW_WINNER_GOLD_PRIZE : 50000;

if ($isPreview && !$winner) {
    // Demo content matching the classic Arabic victory scroll.
    if ($worldLabel === '' || $worldLabel === null) {
        $worldLabel = '7';
    }
    $goldPrize = 60000;
    $wwWinnerUid = 0;
    $wwWinnerName = 'بيترلس';
    $allianceid = 0;
    $winningalliancetag = 'J. R. M';
    $winningalliance = 'J. R. M';
    $winningvillagename = 'قرية العجيبة';
    $vref = 0;
    $finishconstruction = time();
    $topPop = ['userid' => 0, 'username' => 'قراقوش'];
    $topAtt = ['userid' => 0, 'username' => 'المفترس'];
    $topDef = ['userid' => 0, 'username' => 'بيترلس'];
    $topHero = ['userid' => 0, 'username' => 'المفترس'];
} else {
    /**
     * Top population empire
     */
    $datas = [];
    $q = "SELECT 
        u.id AS userid,
        u.username,
        u.alliance,
        (SELECT SUM(v.pop) FROM " . TB_PREFIX . "vdata v WHERE v.owner = u.id) AS totalpop,
        (SELECT COUNT(v.wref) FROM " . TB_PREFIX . "vdata v WHERE v.owner = u.id AND v.type != 99) AS totalvillages,
        (SELECT a.tag FROM " . TB_PREFIX . "alidata a WHERE a.id = u.alliance) AS allitag
    FROM " . TB_PREFIX . "users u
    WHERE u.access < " . (int) $accessLimit . " AND " . $tribeFilter . "
    ORDER BY totalpop DESC, totalvillages DESC, u.username ASC
    LIMIT 3";
    $result = mysqli_query($database->dblink, $q);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $datas[] = $row;
        }
    }
    $topPop = isset($datas[0]) ? $datas[0] : null;

    /**
     * Top attacker
     */
    $attacker = [];
    $q = "SELECT 
        u.id AS userid,
        u.username,
        u.apall,
        (SELECT COUNT(v.wref) FROM " . TB_PREFIX . "vdata v WHERE v.owner = u.id AND v.type != 99) AS totalvillages,
        (SELECT SUM(v.pop) FROM " . TB_PREFIX . "vdata v WHERE v.owner = u.id) AS pop
    FROM " . TB_PREFIX . "users u
    WHERE u.apall >= 0 AND u.access < " . (int) $accessLimit . " AND " . $tribeFilter . "
    ORDER BY u.apall DESC, pop DESC, u.username ASC
    LIMIT 3";
    $result = mysqli_query($database->dblink, $q);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $attacker[] = $row;
        }
    }
    $topAtt = isset($attacker[0]) ? $attacker[0] : null;

    /**
     * Top defender
     */
    $defender = [];
    $q = "SELECT 
        u.id AS userid,
        u.username,
        u.dpall,
        (SELECT COUNT(v.wref) FROM " . TB_PREFIX . "vdata v WHERE v.owner = u.id AND v.type != 99) AS totalvillages,
        (SELECT SUM(v.pop) FROM " . TB_PREFIX . "vdata v WHERE v.owner = u.id) AS pop
    FROM " . TB_PREFIX . "users u
    WHERE u.dpall >= 0 AND u.access < " . (int) $accessLimit . " AND " . $tribeFilter . "
    ORDER BY u.dpall DESC, pop DESC, u.username ASC
    LIMIT 3";
    $result = mysqli_query($database->dblink, $q);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $defender[] = $row;
        }
    }
    $topDef = isset($defender[0]) ? $defender[0] : null;

    /**
     * Top hero (by experience)
     */
    $q = "SELECT h.uid AS userid, h.experience, h.name AS hero_name, u.username
    FROM " . TB_PREFIX . "hero h
    INNER JOIN " . TB_PREFIX . "users u ON u.id = h.uid
    WHERE h.dead = 0 AND u.access < " . (int) $accessLimit . " AND " . $tribeFilter . "
    ORDER BY h.experience DESC, u.username ASC
    LIMIT 1";
    $result = mysqli_query($database->dblink, $q);
    if ($result) {
        $topHero = mysqli_fetch_assoc($result) ?: null;
    }

    /**
     * WW winner details
     */
    $q = "SELECT 
        f.vref,
        f.ww_lastupdate,
        v.name AS village_name,
        v.owner AS owner_id,
        u.username,
        a.id AS alliance_id,
        a.name AS alliance_name,
        a.tag AS alliance_tag
    FROM " . TB_PREFIX . "fdata f
    LEFT JOIN " . TB_PREFIX . "vdata v ON v.wref = f.vref
    LEFT JOIN " . TB_PREFIX . "users u ON u.id = v.owner
    LEFT JOIN " . TB_PREFIX . "alidata a ON a.id = u.alliance
    WHERE f.f99 = '100' AND f.f99t = '40'
    LIMIT 1";

    $result = mysqli_query($database->dblink, $q);
    $row = $result ? mysqli_fetch_assoc($result) : null;

    if ($row) {
        // Dedicated winner vars — menu.tpl overwrites $username with the session user.
        $vref = (int) $row['vref'];
        $winningvillagename = $row['village_name'];
        $wwWinnerUid = (int) $row['owner_id'];
        $wwWinnerName = $row['username'];
        $allianceid = (int) $row['alliance_id'];
        $winningalliance = $row['alliance_name'];
        $winningalliancetag = $row['alliance_tag'];
        $finishconstruction = (int) $row['ww_lastupdate'];
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo tz_html_dir_attrs(); ?>>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($serverName, ENT_QUOTES, 'UTF-8'); ?> - <?php
        echo defined('WINNER_RPT_PAGE_TITLE') ? WINNER_RPT_PAGE_TITLE : 'نهاية العالم';
    ?></title>
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
    // Same cascade as spieler.php / dorf2.php: lang → compact → novaterra →
    // lang again (restores 700px #mtop from new_layout after compact's 570px).
    echo "
    <link href='" . GP_LOCATE . "novaterra.css?e21d2' rel='stylesheet' type='text/css' />
    <link href='" . GP_LOCATE . "lang/en/lang.css?e21d2' rel='stylesheet' type='text/css' />";
    ?>
    <script type="text/javascript">window.addEvent('domready', start);</script>
    <?php echo tz_rtl_stylesheet_tag(); ?>
</head>
<body class="v35 ie ie8 pg-winner">
<div class="wrapper">
    <img style="filter:chroma();" src="img/x.gif" id="msfilter" alt="" />
    <div id="dynamic_header">
    </div>
    <?php include("Templates/header.tpl"); ?>
    <div id="mid">
        <?php include("Templates/menu.tpl"); ?>
        <div id="content" class="player tz-winner-page">
            <?php include("Templates/Winner/report.tpl"); ?>
        </div>
        <div id="side_info">
            <?php
            include("Templates/multivillage.tpl");
            include("Templates/quest.tpl");
            include("Templates/news.tpl");
            if (!NEW_FUNCTIONS_DISPLAY_LINKS) {
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
                <?php echo CALCULATED_IN; ?> <b><?php
                    echo round(($generator->pageLoadTimeEnd() - $start_timer) * 1000);
                ?></b> ms
                <br /><?php echo SERVER_TIME; ?> <span id="tp1" class="b"><?php echo date('H:i:s'); ?></span>
            </div>
        </div>
    </div>
    <div id="ce"></div>
</div>
</body>
</html>
