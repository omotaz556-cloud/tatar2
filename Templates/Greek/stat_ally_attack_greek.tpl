<?php
/**
 * Greek.sa best alliances (attack) — statistiken.php?id=41
 */

global $ranking, $session;

$search = 0;
if (!isset($_SESSION['search']) || !is_numeric($_SESSION['search'])) {
    if (!empty($_SESSION['search'])) {
        echo '<p class="gk-sta-err">' . TZ_THE_ALLIANCE . ' <b>"'
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

$gkAid = isset($session->alliance) ? (int) $session->alliance : 0;
$pageTitle = defined('TZ_THE_BEST_ALLIANCES_OFF')
    ? TZ_THE_BEST_ALLIANCES_OFF : 'أفضل التحالفات (هجوم)';
?>
<table class="b0 fl L6_S gk-sta-ally gk-sta-combat">
<colgroup>
    <col class="gk-sta-rank-col" />
    <col class="gk-sta-al-col" />
    <col class="gk-sta-players-col" />
    <col class="gk-sta-points-col" />
</colgroup>
<tbody>
<tr class="gk-sta-head"><th colspan="4"><div class="gk-sta-bar">
<span class="staRi">
<a title="<?php echo TZ_TOP_10; ?>" href="statistiken.php?id=43"><img class="btn_top10" src="img/x.gif" alt="" /></a>
<a title="<?php echo DEFENDER; ?>" href="statistiken.php?id=42"><img class="btn_def" src="img/x.gif" alt="" /></a>
<a title="<?php echo ATTACKER; ?>" href="statistiken.php?id=41"><img class="btn_off active" src="img/x.gif" alt="" /></a>
</span>
<span class="staTit"><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></span>
<span class="staLe" aria-hidden="true"></span>
</div></th></tr>
<tr class="gk-sta-cols">
    <th class="ra">#</th>
    <th class="al"><?php echo ALLIANCE; ?></th>
    <th class="pla"><?php echo PLAYERS; ?></th>
    <th class="po"><?php echo POINTS; ?></th>
</tr>
<?php
if (count($rankArray) > 1) {
    for ($i = $start; $i < $start + 20; $i++) {
        if (!isset($rankArray[$i]['name']) || $rankArray[$i] === 'pad') {
            continue;
        }
        $row = $rankArray[$i];
        $aid = (int) ($row['id'] ?? 0);
        $isHighlight = ($i === $search) || ($gkAid > 0 && $aid === $gkAid);
        $hlCls = $isHighlight ? ' hl' : '';
        $fcCls = $isHighlight ? ' fc' : '';
        $lcCls = $isHighlight ? ' lc' : '';
        $pts = (int) ($row['Aap'] ?? $row['totalap'] ?? 0);

        echo '<tr class="' . trim($hlCls) . '">';
        echo '<th class="ra' . $fcCls . '">' . $i . '</th>';
        echo '<th class="al"><a href="allianz.php?aid=' . $aid . '">'
            . htmlspecialchars((string) $row['tag'], ENT_QUOTES, 'UTF-8') . '</a></th>';
        echo '<th class="pla">' . (int) ($row['players'] ?? 0) . '</th>';
        echo '<th class="po' . $lcCls . '"><bdi dir="ltr">' . number_format($pts) . '</bdi></th>';
        echo '</tr>';
    }
} else {
    echo '<tr><th class="none" colspan="4">' . TZ_NO_ALLIANCES_FOUND . '</th></tr>';
}
?>
</tbody></table>
<?php include __DIR__ . '/stat_search_greek.tpl'; ?>
