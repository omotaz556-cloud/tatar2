<?php
/**
 * Greek.sa literal villages ranking table — matches reference screenshot.
 */

global $ranking, $session, $village, $generator, $database;

$search = 0;
if (!isset($_SESSION['search']) || !is_numeric($_SESSION['search'])) {
    if (!empty($_SESSION['search'])) {
        echo '<p class="gk-sta-err">' . TZ_THE_VILLAGE . ' <b>"'
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

$gkWref = isset($village->wid) ? (int) $village->wid : 0;
$gkFromX = (!empty($village) && is_object($village) && isset($village->coor['x']))
    ? (int) $village->coor['x'] : 0;
$gkFromY = (!empty($village) && is_object($village) && isset($village->coor['y']))
    ? (int) $village->coor['y'] : 0;
$colOwner = defined('OWNER') ? OWNER : 'المالك';
$colDistance = defined('TZ_STAT_VIL_DISTANCE') ? TZ_STAT_VIL_DISTANCE : 'البعد';
$colStability = defined('TZ_STAT_VIL_STABILITY') ? TZ_STAT_VIL_STABILITY : 'الصلابة';
?>
<table class="b0 fl L6_S gk-sta-vil">
<colgroup>
    <col class="gk-sta-vil-rank-col" />
    <col class="gk-sta-vil-name-col" />
    <col class="gk-sta-vil-owner-col" />
    <col class="gk-sta-vil-pop-col" />
    <col class="gk-sta-vil-coord-col" />
    <col class="gk-sta-vil-dist-col" />
    <col class="gk-sta-vil-stb-col" />
</colgroup>
<tbody>
<tr class="gk-sta-head"><th colspan="7" class="gk-sta-vil-title"><?php echo TZ_THE_LARGEST_VILLAGES; ?></th></tr>
<tr class="gk-sta-cols">
    <th class="ra">#</th>
    <th class="vil"><?php echo VILLAGE; ?></th>
    <th class="pla"><?php echo $colOwner; ?></th>
    <th class="hab"><?php echo INHABITANTS; ?></th>
    <th class="coo"><?php echo COORDINATES; ?></th>
    <th class="dst"><?php echo $colDistance; ?></th>
    <th class="stb"><?php echo $colStability; ?></th>
</tr>
<?php
if (count($rankArray) > 1) {
    for ($i = $start; $i < $start + 20; $i++) {
        if (!isset($rankArray[$i]['wref'])) {
            continue;
        }
        $row = $rankArray[$i];
        $wref = (int) ($row['wref'] ?? 0);
        $owner = (int) ($row['owner'] ?? 0);
        $vx = (int) ($row['x'] ?? 0);
        $vy = (int) ($row['y'] ?? 0);
        $pop = (int) ($row['pop'] ?? 0);
        $cp = (int) ($row['cp'] ?? 0);
        $dist = ($gkFromX || $gkFromY || $vx || $vy)
            ? (int) round($database->getDistance($gkFromX, $gkFromY, $vx, $vy))
            : 0;
        $isHighlight = ($i === $search) || ($gkWref > 0 && $wref === $gkWref);
        $hlCls = $isHighlight ? ' hl' : '';
        $fcCls = $isHighlight ? ' fc' : '';
        $lcCls = $isHighlight ? ' lc' : '';

        echo '<tr class="' . trim($hlCls) . '">';
        echo '<th class="ra' . $fcCls . '">' . $i . '</th>';
        echo '<th class="vil"><a href="karte.php?d=' . $wref . '&amp;c='
            . $generator->getMapCheck($wref) . '">'
            . htmlspecialchars((string) ($row['name'] ?? ''), ENT_QUOTES, 'UTF-8') . '</a></th>';
        echo '<th class="pla"><a href="spieler.php?uid=' . $owner . '">'
            . htmlspecialchars((string) ($row['user'] ?? ''), ENT_QUOTES, 'UTF-8') . '</a></th>';
        echo '<th class="hab"><bdi dir="ltr">' . $pop . '</bdi></th>';
        echo '<th class="coo aligned_coords">'
            . '<span class="gk-coo-ltr">' . "\xE2\x80\x8E" . '(' . $vx . ',' . $vy . ')</span>'
            . '</th>';
        echo '<th class="dst"><bdi dir="ltr">' . $dist . '</bdi></th>';
        echo '<th class="stb' . $lcCls . '"><bdi dir="ltr">' . number_format($cp) . '</bdi></th>';
        echo '</tr>';
    }
} else {
    echo '<tr><th class="none" colspan="7">' . TZ_NO_VILLAGES_FOUND . '</th></tr>';
}
?>
</tbody></table>
<?php include __DIR__ . '/stat_search_greek.tpl'; ?>
