<?php
/**
 * Greek.sa most successful attackers ranking.
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
$pageTitle = defined('TZ_THE_MOST_SUCCESSFUL_ATTACKERS')
    ? TZ_THE_MOST_SUCCESSFUL_ATTACKERS : 'أكثر المهاجمين نجاحًا';
?>
<table class="b0 fl L6_S gk-sta-players gk-sta-combat">
<colgroup>
    <col class="gk-sta-rank-col" />
    <col class="gk-sta-pla-col" />
    <col class="gk-sta-pop-col" />
    <col class="gk-sta-vil-col" />
    <col class="gk-sta-points-col" />
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
<a title="<?php echo ATTACKER; ?>" href="statistiken.php?id=31"><img class="btn_off active" src="img/x.gif" alt="" /></a>
</span>
<span class="staTit"><?php echo $pageTitle; ?></span>
<span class="staLe" aria-hidden="true"></span>
</div></th></tr>
<tr class="gk-sta-cols">
    <th class="ra">#</th>
    <th class="pla"><?php echo PLAYER; ?></th>
    <th class="pop"><?php echo POP; ?></th>
    <th class="vil"><?php echo VILLAGES; ?></th>
    <th class="po"><?php echo POINTS; ?></th>
</tr>
<?php
if (count($rankArray) > 1) {
    for ($i = $start; $i < $start + 20; $i++) {
        if (!isset($rankArray[$i]['username']) || $rankArray[$i] === 'pad') {
            continue;
        }
        $row = $rankArray[$i];
        $uid = (int) ($row['userid'] ?? $row['id'] ?? 0);
        $isHighlight = ($i === $search) || ($uid === $gkUid);
        $hlCls = $isHighlight ? ' hl' : '';
        $fcCls = $isHighlight ? ' fc' : '';
        $lcCls = $isHighlight ? ' lc' : '';

        echo '<tr class="' . trim($hlCls) . '">';
        echo '<th class="ra' . $fcCls . '">' . $i . '</th>';
        echo '<th class="pla">';
        $username = htmlspecialchars((string) $row['username'], ENT_QUOTES, 'UTF-8');
        if (!empty($row['access']) && (int) $row['access'] > 2) {
            echo '<u><a href="spieler.php?uid=' . $uid . '">' . $username . '</a></u>';
        } else {
            echo '<a href="spieler.php?uid=' . $uid . '">' . $username . '</a>';
        }
        echo '</th>';
        echo '<th class="pop"><bdi dir="ltr">' . (int) ($row['totalpop'] ?? 0) . '</bdi></th>';
        echo '<th class="vil"><bdi dir="ltr">' . (int) ($row['totalvillages'] ?? 0) . '</bdi></th>';
        echo '<th class="po' . $lcCls . '"><bdi dir="ltr">' . number_format((int) ($row['apall'] ?? 0)) . '</bdi></th>';
        echo '</tr>';
    }
} else {
    echo '<tr><th class="none" colspan="5">' . TZ_NO_USERS_FOUND . '</th></tr>';
}
?>
</tbody></table>
<?php include __DIR__ . '/stat_search_greek.tpl'; ?>
