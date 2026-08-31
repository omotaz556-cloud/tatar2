<?php
/**
 * Greek.sa undefeated-in-defense ranking (statistiken.php?id=3).
 * Columns: # | اللاعب | التحالف | منذ | النقاط | كسب كل يوم | كسر
 */

global $ranking, $session, $generator;

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
$pageTitle = defined('TZ_STAT_UNDEFEATED_TITLE')
    ? TZ_STAT_UNDEFEATED_TITLE
    : 'لم يُهزموا في الدفاع منذ بداية العالم';
$colSince = defined('TZ_STAT_UNDEFEATED_SINCE') ? TZ_STAT_UNDEFEATED_SINCE : 'منذ';
$colPoints = defined('POINTS') ? POINTS : 'النقاط';
$colDaily = defined('TZ_STAT_UNDEFEATED_DAILY') ? TZ_STAT_UNDEFEATED_DAILY : 'كسب كل يوم';
$colBreak = defined('TZ_STAT_UNDEFEATED_BREAK') ? TZ_STAT_UNDEFEATED_BREAK : 'كسر';
$atkLabel = defined('TZ_STAT_UNDEFEATED_ATTACK') ? TZ_STAT_UNDEFEATED_ATTACK : 'إهجم';
$goldTitle = defined('GOLD') ? GOLD : 'ذهب';
$now = time();
?>
<table class="b0 fl L6_S gk-sta-players gk-sta-undef">
<colgroup>
    <col class="gk-sta-rank-col" />
    <col class="gk-sta-pla-col" />
    <col class="gk-sta-al-col" />
    <col class="gk-sta-since-col" />
    <col class="gk-sta-points-col" />
    <col class="gk-sta-daily-col" />
    <col class="gk-sta-break-col" />
</colgroup>
<tbody>
<tr class="gk-sta-head"><th colspan="7"><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></th></tr>
<tr class="gk-sta-cols">
    <th class="ra">#</th>
    <th class="pla"><?php echo PLAYER; ?></th>
    <th class="al"><?php echo ALLIANCE; ?></th>
    <th class="since"><?php echo htmlspecialchars($colSince, ENT_QUOTES, 'UTF-8'); ?></th>
    <th class="po"><?php echo htmlspecialchars($colPoints, ENT_QUOTES, 'UTF-8'); ?></th>
    <th class="daily"><?php echo htmlspecialchars($colDaily, ENT_QUOTES, 'UTF-8'); ?></th>
    <th class="brk"><?php echo htmlspecialchars($colBreak, ENT_QUOTES, 'UTF-8'); ?></th>
</tr>
<?php
if (count($rankArray) > 1) {
    for ($i = $start; $i < $start + 20; $i++) {
        if (!isset($rankArray[$i]['username']) || $rankArray[$i] === 'pad') {
            continue;
        }
        $row = $rankArray[$i];
        $rowUid = (int) ($row['userid'] ?? $row['id'] ?? 0);
        $isHighlight = ($i === $search) || ($rowUid === $gkUid);
        $hlCls = $isHighlight ? ' hl' : '';
        $fcCls = $isHighlight ? ' fc' : '';
        $lcCls = $isHighlight ? ' lc' : '';

        $since = (int) ($row['since'] ?? 0);
        $elapsed = max(0, $now - $since);
        $clock = $generator->getTimeFormat($elapsed);
        $points = (int) ($row['points'] ?? 0);
        $dailyGold = (int) ($row['daily_gold'] ?? 1000);
        $capital = (int) ($row['capital'] ?? 0);
        $allyId = (int) ($row['alliance'] ?? 0);
        $allyTag = trim((string) ($row['allitag'] ?? ''));

        echo '<tr class="' . trim($hlCls) . '">';
        echo '<th class="ra' . $fcCls . '">' . $i . '</th>';

        echo '<th class="pla">';
        $username = htmlspecialchars((string) $row['username'], ENT_QUOTES, 'UTF-8');
        echo '<a href="spieler.php?uid=' . $rowUid . '">' . $username . '</a>';
        echo '</th>';

        echo '<th class="al">';
        if ($allyId > 0 && $allyTag !== '') {
            echo '<a href="allianz.php?aid=' . $allyId . '">'
                . htmlspecialchars($allyTag, ENT_QUOTES, 'UTF-8') . '</a>';
        } else {
            echo '-';
        }
        echo '</th>';

        echo '<th class="since"><bdi dir="ltr">'
            . htmlspecialchars($clock, ENT_QUOTES, 'UTF-8') . '</bdi></th>';

        echo '<th class="po"><bdi dir="ltr">' . number_format($points) . '</bdi></th>';

        echo '<th class="daily"><bdi dir="ltr">' . number_format($dailyGold)
            . '</bdi> <img src="img/x.gif" class="gold" alt="" title="'
            . htmlspecialchars($goldTitle, ENT_QUOTES, 'UTF-8') . '" /></th>';

        echo '<th class="brk' . $lcCls . '">';
        if ($capital > 0 && $rowUid !== $gkUid) {
            echo '<a class="gk-sta-atk" href="a2b.php?z=' . $capital . '">'
                . htmlspecialchars($atkLabel, ENT_QUOTES, 'UTF-8') . '</a>';
        } else {
            echo '-';
        }
        echo '</th>';

        echo '</tr>';
    }
} else {
    echo '<tr><th class="none" colspan="7">' . TZ_NO_USERS_FOUND . '</th></tr>';
}
?>
</tbody></table>
<?php include __DIR__ . '/stat_search_greek.tpl'; ?>
