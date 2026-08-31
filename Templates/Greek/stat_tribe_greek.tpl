<?php
/**
 * Greek.sa tribe ranking table (Romans / Teutons / Gauls / …).
 */

global $ranking, $session;

$search = 0;
if (!isset($_SESSION['search']) || !is_numeric($_SESSION['search'])) {
    if (!empty($_SESSION['search'])) {
        echo '<p class="gk-sta-err">' . TZ_THE_USER . ' <b>"'
            . htmlspecialchars((string) $_SESSION['search'], ENT_QUOTES, 'UTF-8') . '"</b> '
            . TZ_DOES_NOT_EXIST . '</p>';
    }
    $search = 0;
} else {
    $search = (int) $_SESSION['search'];
}

$rankArray = $ranking->getRank();

if (isset($_GET['rank']) && is_numeric($_GET['rank'])) {
    $rank = (int) $_GET['rank'];
    $count = count($rankArray);
    if ($rank > $count) {
        $rank = max(1, $count - 1);
    }
    $multiplier = 1;
    while ($rank > (20 * $multiplier)) {
        $multiplier++;
    }
    $start = 20 * $multiplier - 19;
} else {
    $start = ($_SESSION['start'] ?? 0) + 1;
}

$gkUid = isset($session->uid) ? (int) $session->uid : 0;
$pageId = isset($_GET['id']) ? (int) $_GET['id'] : 11;

$tribeTitles = array(
    11 => defined('TZ_THE_LARGEST_ROMANS') ? TZ_THE_LARGEST_ROMANS : TRIBE1,
    12 => defined('TZ_THE_LARGEST_TEUTONS') ? TZ_THE_LARGEST_TEUTONS : TRIBE2,
    13 => defined('TZ_THE_LARGEST_GAULS') ? TZ_THE_LARGEST_GAULS : TRIBE3,
    16 => defined('TZ_THE_LARGEST_HUNS') ? TZ_THE_LARGEST_HUNS : (defined('TRIBE6') ? TRIBE6 : 'هون'),
    17 => defined('TZ_THE_LARGEST_EGYPTIANS') ? TZ_THE_LARGEST_EGYPTIANS : (defined('TRIBE7') ? TRIBE7 : 'العرب'),
    18 => defined('TZ_THE_LARGEST_SPARTANS') ? TZ_THE_LARGEST_SPARTANS : (defined('TRIBE8') ? TRIBE8 : 'إسبرطيون'),
    19 => defined('TZ_THE_LARGEST_VIKINGS') ? TZ_THE_LARGEST_VIKINGS : (defined('TRIBE9') ? TRIBE9 : 'فايكنج'),
);
$pageTitle = $tribeTitles[$pageId] ?? TZ_THE_LARGEST_PLAYERS;

$tribeBtn = static function ($id, $btnClass, $label, $pageId) {
    $active = ($pageId === $id) ? ' active' : '';
    echo '<a title="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '" href="statistiken.php?id=' . $id . '">'
        . '<img class="' . $btnClass . $active . '" src="img/x.gif" alt="" /></a>';
};
?>
<table class="b0 fl L6_S gk-sta-players gk-sta-tribe">
<colgroup>
    <col class="gk-sta-rank-col" />
    <col class="gk-sta-pla-col" />
    <col class="gk-sta-al-col" />
    <col class="gk-sta-pop-col" />
    <col class="gk-sta-vil-col" />
</colgroup>
<tbody>
<tr class="gk-sta-head"><th colspan="5"><div class="gk-sta-bar">
<span class="staRi">
<?php
if (defined('NEW_FUNCTIONS_PLUS_STATISTICS') && NEW_FUNCTIONS_PLUS_STATISTICS
    && isset($session->plus) && (int) $session->plus === 1) {
    $psLabel = defined('PLUSSTATS_TITLE') ? PLUSSTATS_TITLE : 'Graphical statistics';
    echo '<a title="' . htmlspecialchars($psLabel, ENT_QUOTES, 'UTF-8') . '" href="statistiken.php?id=50">'
        . '<img class="btn_stats" src="img/x.gif" alt="" /></a>';
}
?>
<a title="<?php echo TZ_TOP_10; ?>" href="statistiken.php?id=7"><img class="btn_top10" src="img/x.gif" alt="" /></a>
<a title="<?php echo DEFENDER; ?>" href="statistiken.php?id=32"><img class="btn_def" src="img/x.gif" alt="" /></a>
<a title="<?php echo ATTACKER; ?>" href="statistiken.php?id=31"><img class="btn_off" src="img/x.gif" alt="" /></a>
</span>
<span class="staTit"><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></span>
<span class="staLe">
<?php
$tribeBtn(11, 'btn_v1', TRIBE1, $pageId);
$tribeBtn(12, 'btn_v2', TRIBE2, $pageId);
$tribeBtn(13, 'btn_v3', TRIBE3, $pageId);
if (defined('NEW_FUNCTION_TRIBE_HUNS') && NEW_FUNCTION_TRIBE_HUNS) {
    $tribeBtn(16, 'btn_v6', TRIBE6, $pageId);
}
if (defined('NEW_FUNCTION_TRIBE_EGIPTEANS') && NEW_FUNCTION_TRIBE_EGIPTEANS) {
    $tribeBtn(17, 'btn_v7', TRIBE7, $pageId);
}
if (defined('NEW_FUNCTION_TRIBE_SPARTANS') && NEW_FUNCTION_TRIBE_SPARTANS) {
    $tribeBtn(18, 'btn_v8', TRIBE8, $pageId);
}
if (defined('NEW_FUNCTION_TRIBE_VIKINGS') && NEW_FUNCTION_TRIBE_VIKINGS) {
    $tribeBtn(19, 'btn_v9', TRIBE9, $pageId);
}
?>
</span>
</div></th></tr>
<tr class="gk-sta-cols">
    <th class="ra">#</th>
    <th class="pla"><?php echo PLAYER; ?></th>
    <th class="al"><?php echo ALLIANCE; ?></th>
    <th class="pop"><?php echo POP; ?></th>
    <th class="vil"><?php echo VILLAGES; ?></th>
</tr>
<?php
if (count($rankArray) > 1) {
    for ($i = $start; $i < $start + 20; $i++) {
        if (!isset($rankArray[$i]['username']) || $rankArray[$i] === 'pad') {
            continue;
        }
        $row = $rankArray[$i];
        $rowUid = (int) ($row['userid'] ?? 0);
        $isHighlight = ($i === $search) || ($rowUid === $gkUid);
        $hlCls = $isHighlight ? ' hl' : '';
        $fcCls = $isHighlight ? ' fc' : '';
        $lcCls = $isHighlight ? ' lc' : '';

        echo '<tr class="' . trim($hlCls) . '">';
        echo '<th class="ra' . $fcCls . '">' . $i . '</th>';
        echo '<th class="pla">';
        $username = htmlspecialchars((string) $row['username'], ENT_QUOTES, 'UTF-8');
        if (!empty($row['access']) && (int) $row['access'] > 2) {
            echo '<u><a href="spieler.php?uid=' . $rowUid . '">' . $username . '</a></u>';
        } else {
            echo '<a href="spieler.php?uid=' . $rowUid . '">' . $username . '</a>';
        }
        echo '</th>';
        echo '<th class="al">';
        if (!empty($row['aname']) && !empty($row['alliance'])) {
            echo '<a href="allianz.php?aid=' . (int) $row['alliance'] . '">'
                . htmlspecialchars((string) $row['aname'], ENT_QUOTES, 'UTF-8') . '</a>';
        } else {
            echo '-';
        }
        echo '</th>';
        echo '<th class="pop"><bdi dir="ltr">' . (int) ($row['totalpop'] ?? 0) . '</bdi></th>';
        echo '<th class="vil' . $lcCls . '"><bdi dir="ltr">' . (int) ($row['totalvillage'] ?? 0) . '</bdi></th>';
        echo '</tr>';
    }
} else {
    echo '<tr><th class="none" colspan="5">' . TZ_NO_USERS_FOUND . '</th></tr>';
}
?>
</tbody></table>
<?php include __DIR__ . '/stat_search_greek.tpl'; ?>
