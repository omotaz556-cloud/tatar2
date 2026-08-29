<?php
/**
 * Greek.sa graphical (Plus) statistics page shell + charts.
 */

global $session, $database;

$pageTitle = defined('PLUSSTATS_TITLE') ? PLUSSTATS_TITLE : 'إحصائيات رسومية';
$psHasPlus = (isset($session->plus) && (int) $session->plus === 1);
$psEnabled = defined('NEW_FUNCTIONS_PLUS_STATISTICS') && NEW_FUNCTIONS_PLUS_STATISTICS;
?>
<table class="b0 fl L6_S gk-sta-players gk-sta-plusstats-head">
<tbody>
<tr class="gk-sta-head"><th colspan="1"><div class="gk-sta-bar">
<span class="staRi">
<a title="<?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?>" href="statistiken.php?id=50">
    <img class="btn_stats active" src="img/x.gif" alt="" /></a>
<a title="<?php echo TZ_TOP_10; ?>" href="statistiken.php?id=7"><img class="btn_top10" src="img/x.gif" alt="" /></a>
<a title="<?php echo DEFENDER; ?>" href="statistiken.php?id=32"><img class="btn_def" src="img/x.gif" alt="" /></a>
<a title="<?php echo ATTACKER; ?>" href="statistiken.php?id=31"><img class="btn_off" src="img/x.gif" alt="" /></a>
</span>
<span class="staTit"><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></span>
<span class="staLe" aria-hidden="true"></span>
</div></th></tr>
</tbody></table>

<div class="gk-sta-plusstats">
<?php
if (!function_exists('ps_line_chart')) {
    function ps_line_chart(array $points, $title, $color = '#71d000', $invert = false)
    {
        $w = 560;
        $h = 170;
        $padL = 52;
        $padR = 12;
        $padT = 22;
        $padB = 26;

        $out = '<svg width="' . $w . '" height="' . $h . '" viewBox="0 0 ' . $w . ' ' . $h . '"'
            . ' xmlns="http://www.w3.org/2000/svg" class="gk-ps-svg">';
        $out .= '<rect width="' . $w . '" height="' . $h . '" fill="#fff" stroke="#c9c9c9"/>';
        $out .= '<text x="8" y="14" font-family="Tahoma,Arial" font-size="11" font-weight="bold" fill="#333">'
            . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</text>';

        if (count($points) < 2) {
            $out .= '<text x="' . ($w / 2) . '" y="' . ($h / 2) . '" font-family="Tahoma,Arial" font-size="11"'
                . ' fill="#999" text-anchor="middle">'
                . htmlspecialchars(defined('PLUSSTATS_NODATA') ? PLUSSTATS_NODATA : 'Not enough data yet', ENT_QUOTES, 'UTF-8')
                . '</text></svg>';
            return $out;
        }

        $values = array_map(static function ($p) { return (float) $p[1]; }, $points);
        $times = array_map(static function ($p) { return (int) $p[0]; }, $points);
        $minV = min($values);
        $maxV = max($values);
        if ($maxV - $minV < 0.0001) {
            $maxV = $minV + 1;
        }
        $minT = min($times);
        $maxT = max($times);
        $spanT = max(1, $maxT - $minT);
        $plotW = $w - $padL - $padR;
        $plotH = $h - $padT - $padB;

        $x = static function ($t) use ($padL, $plotW, $minT, $spanT) {
            return $padL + ($t - $minT) / $spanT * $plotW;
        };
        $y = static function ($v) use ($padT, $plotH, $minV, $maxV, $invert) {
            $frac = ($v - $minV) / ($maxV - $minV);
            return $invert ? $padT + $frac * $plotH : $padT + (1 - $frac) * $plotH;
        };

        for ($i = 0; $i <= 3; $i++) {
            $v = $minV + ($maxV - $minV) * $i / 3;
            $yy = $y($v);
            $out .= '<line x1="' . $padL . '" y1="' . round($yy, 1) . '" x2="' . ($w - $padR)
                . '" y2="' . round($yy, 1) . '" stroke="#eee" stroke-width="1"/>';
            $out .= '<text x="' . ($padL - 5) . '" y="' . (round($yy, 1) + 3) . '" font-family="Tahoma,Arial"'
                . ' font-size="9" fill="#888" text-anchor="end">' . number_format($v) . '</text>';
        }

        $d = '';
        foreach ($points as $i => $p) {
            $d .= ($i === 0 ? 'M' : 'L') . round($x($p[0]), 1) . ',' . round($y($p[1]), 1) . ' ';
        }
        $area = $d . 'L' . round($x($times[count($times) - 1]), 1) . ',' . ($padT + $plotH)
            . ' L' . round($x($times[0]), 1) . ',' . ($padT + $plotH) . ' Z';
        $out .= '<path d="' . $area . '" fill="' . $color . '" fill-opacity="0.15"/>';
        $out .= '<path d="' . $d . '" fill="none" stroke="' . $color . '" stroke-width="2"/>';

        $first = $points[0];
        $lastP = $points[count($points) - 1];
        foreach (array($first, $lastP) as $p) {
            $out .= '<circle cx="' . round($x($p[0]), 1) . '" cy="' . round($y($p[1]), 1)
                . '" r="3" fill="' . $color . '"/>';
        }
        $out .= '<text x="' . $padL . '" y="' . ($h - 8) . '" font-family="Tahoma,Arial" font-size="9" fill="#888">'
            . date('d.m', $minT) . '</text>';
        $out .= '<text x="' . ($w - $padR) . '" y="' . ($h - 8) . '" font-family="Tahoma,Arial" font-size="9"'
            . ' fill="#888" text-anchor="end">' . date('d.m', $maxT) . '</text>';
        $out .= '<text x="' . ($w - $padR) . '" y="14" font-family="Tahoma,Arial" font-size="11"'
            . ' font-weight="bold" fill="' . $color . '" text-anchor="end">'
            . number_format($lastP[1]) . '</text>';
        $out .= '</svg>';
        return $out;
    }
}

if (!$psEnabled) {
    echo '<div class="ps-locked">'
        . (defined('PLUSSTATS_DISABLED') ? PLUSSTATS_DISABLED
            : 'Graphical statistics are not enabled on this server.')
        . '</div></div>';
    return;
}

if (!$psHasPlus) {
    echo '<div class="ps-locked">'
        . (defined('PLUSSTATS_NEEDPLUS') ? PLUSSTATS_NEEDPLUS
            : 'Graphical statistics are a Novaterra Plus feature. Activate Plus to see the development of your account.')
        . '</div></div>';
    return;
}

$psRows = array();
$psUid = (int) $session->uid;
$psQ = @mysqli_query($database->dblink,
    "SELECT recorded_at, `rank`, population, villages, troop_count, troop_upkeep
       FROM " . TB_PREFIX . "player_statistics_history
      WHERE uid = " . $psUid . "
      ORDER BY recorded_at ASC
      LIMIT 400");
while ($psQ && ($psRow = mysqli_fetch_assoc($psQ))) {
    $psRows[] = $psRow;
}

if (count($psRows) < 2) {
    echo '<div class="ps-locked">'
        . (defined('PLUSSTATS_WAIT') ? PLUSSTATS_WAIT
            : 'Your account is being recorded. The graphs appear once at least two snapshots exist.')
        . '</div></div>';
    return;
}

$psSeries = array(
    'rank' => array(),
    'population' => array(),
    'villages' => array(),
    'troop_upkeep' => array(),
    'troop_count' => array(),
);
foreach ($psRows as $psRow) {
    $t = (int) $psRow['recorded_at'];
    if ((int) $psRow['rank'] > 0) {
        $psSeries['rank'][] = array($t, (int) $psRow['rank']);
    }
    $psSeries['population'][] = array($t, (int) $psRow['population']);
    $psSeries['villages'][] = array($t, (int) $psRow['villages']);
    $psSeries['troop_upkeep'][] = array($t, (int) $psRow['troop_upkeep']);
    $psSeries['troop_count'][] = array($t, (int) $psRow['troop_count']);
}

echo '<div class="ps-note">'
    . (defined('PLUSSTATS_INTRO') ? PLUSSTATS_INTRO : 'The development of your account over time.')
    . '</div>';

echo '<div class="ps-chart">'
    . ps_line_chart($psSeries['rank'], defined('PLUSSTATS_RANK') ? PLUSSTATS_RANK : 'Ranking', '#3b7dd8', true)
    . '</div>';
echo '<div class="ps-chart">'
    . ps_line_chart($psSeries['population'], defined('PLUSSTATS_POP') ? PLUSSTATS_POP : 'Population', '#71d000')
    . '</div>';
echo '<div class="ps-chart">'
    . ps_line_chart($psSeries['villages'], defined('PLUSSTATS_VILLAGES') ? PLUSSTATS_VILLAGES : 'Villages', '#8e6fc4')
    . '</div>';
echo '<div class="ps-chart">'
    . ps_line_chart(
        $psSeries['troop_upkeep'],
        defined('PLUSSTATS_ARMY') ? PLUSSTATS_ARMY : 'Army strength (crop upkeep per hour)',
        '#d9822b'
    )
    . '</div>';

$psAlliance = (int) ($session->alliance ?? 0);
$psAllySeries = array('population' => array(), 'villages' => array(), 'troop_upkeep' => array());
$psAllyName = '';
if ($psAlliance > 0) {
    $psAq = @mysqli_query($database->dblink,
        "SELECT h.recorded_at,
                SUM(h.population)   AS population,
                SUM(h.villages)     AS villages,
                SUM(h.troop_upkeep) AS troop_upkeep
           FROM " . TB_PREFIX . "player_statistics_history h
           JOIN " . TB_PREFIX . "users u ON u.id = h.uid
          WHERE u.alliance = " . $psAlliance . "
          GROUP BY h.recorded_at
          ORDER BY h.recorded_at ASC
          LIMIT 400");
    while ($psAq && ($psAr = mysqli_fetch_assoc($psAq))) {
        $t = (int) $psAr['recorded_at'];
        $psAllySeries['population'][] = array($t, (int) $psAr['population']);
        $psAllySeries['villages'][] = array($t, (int) $psAr['villages']);
        $psAllySeries['troop_upkeep'][] = array($t, (int) $psAr['troop_upkeep']);
    }
    $psAn = @mysqli_query($database->dblink,
        "SELECT tag, name FROM " . TB_PREFIX . "alidata WHERE id = " . $psAlliance . " LIMIT 1");
    $psAnr = $psAn ? mysqli_fetch_assoc($psAn) : null;
    if ($psAnr) {
        $psAllyName = $psAnr['tag'] . ' - ' . $psAnr['name'];
    }
}

if (count($psAllySeries['population']) >= 2) {
    echo '<h4 class="ps-ally-head">'
        . (defined('PLUSSTATS_ALLIANCE') ? PLUSSTATS_ALLIANCE : 'Your alliance');
    if ($psAllyName !== '') {
        echo ' <span class="ps-ally-name">' . htmlspecialchars($psAllyName, ENT_QUOTES, 'UTF-8') . '</span>';
    }
    echo '</h4>';
    echo '<div class="ps-chart">'
        . ps_line_chart($psAllySeries['population'], defined('PLUSSTATS_POP') ? PLUSSTATS_POP : 'Population', '#71d000')
        . '</div>';
    echo '<div class="ps-chart">'
        . ps_line_chart($psAllySeries['villages'], defined('PLUSSTATS_VILLAGES') ? PLUSSTATS_VILLAGES : 'Villages', '#8e6fc4')
        . '</div>';
    echo '<div class="ps-chart">'
        . ps_line_chart($psAllySeries['troop_upkeep'], defined('PLUSSTATS_ARMY') ? PLUSSTATS_ARMY : 'Army strength', '#d9822b')
        . '</div>';
}

$psFirst = $psRows[0];
$psLast = $psRows[count($psRows) - 1];
$psFields = array(
    'rank' => defined('PLUSSTATS_RANK') ? PLUSSTATS_RANK : 'Ranking',
    'population' => defined('PLUSSTATS_POP') ? PLUSSTATS_POP : 'Population',
    'villages' => defined('PLUSSTATS_VILLAGES') ? PLUSSTATS_VILLAGES : 'Villages',
    'troop_count' => defined('PLUSSTATS_TROOPS') ? PLUSSTATS_TROOPS : 'Troops',
    'troop_upkeep' => defined('PLUSSTATS_UPKEEP') ? PLUSSTATS_UPKEEP : 'Crop upkeep per hour',
);
?>
<table class="b0 fl L6_S ps-sum gk-sta-ps-sum">
<colgroup>
    <col class="gk-ps-metric-col" />
    <col class="gk-ps-val-col" />
    <col class="gk-ps-val-col" />
    <col class="gk-ps-val-col" />
</colgroup>
<tbody>
    <tr class="gk-sta-cols">
        <th class="met"><?php echo defined('PLUSSTATS_METRIC') ? PLUSSTATS_METRIC : 'Metric'; ?></th>
        <th class="num"><?php echo date('d.m.Y', (int) $psFirst['recorded_at']); ?></th>
        <th class="num"><?php echo defined('PLUSSTATS_NOW') ? PLUSSTATS_NOW : 'Now'; ?></th>
        <th class="num"><?php echo defined('PLUSSTATS_CHANGE') ? PLUSSTATS_CHANGE : 'Change'; ?></th>
    </tr>
<?php
foreach ($psFields as $psKey => $psLabel) {
    $a = (int) $psFirst[$psKey];
    $b = (int) $psLast[$psKey];
    $d = $b - $a;
    $good = ($psKey === 'rank') ? ($d < 0) : ($d > 0);
    $cls = ($d == 0) ? '' : ($good ? 'ps-delta-up' : 'ps-delta-down');
    $sign = ($d > 0) ? '+' : '';
    echo '<tr>';
    echo '<th class="met">' . htmlspecialchars($psLabel, ENT_QUOTES, 'UTF-8') . '</th>';
    echo '<th class="num"><bdi dir="ltr">' . number_format($a) . '</bdi></th>';
    echo '<th class="num"><bdi dir="ltr">' . number_format($b) . '</bdi></th>';
    echo '<th class="num ' . $cls . '"><bdi dir="ltr">' . $sign . number_format($d) . '</bdi></th>';
    echo '</tr>';
}
?>
</tbody>
</table>
<div class="ps-note">
<?php
echo defined('PLUSSTATS_FOOT') ? PLUSSTATS_FOOT
    : 'Army strength is measured by crop upkeep, which is the game\'s own weighting: it counts a strong unit for more than a weak one. Troops reinforcing other players still count as yours.';
?>
</div>
</div>
