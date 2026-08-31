<?php
/**
 * Greek.sa literal heroes ranking table — matches reference screenshot.
 */

global $ranking, $session;

$search = 0;
if (!isset($_SESSION['search']) || !is_numeric($_SESSION['search'])) {
    if (!empty($_SESSION['search'])) {
        echo '<p class="gk-sta-err">' . TZ_THE_HERO . ' <b>"'
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

$heroBonusPct = static function ($bonusPoints) {
    $pct = round(((1 + 0.002 * (int) $bonusPoints) - 1) * 100, 1);

    return ($pct == (int) $pct) ? (string) (int) $pct : (string) $pct;
};

$heroTitle = defined('TZ_THE_LARGEST_HEROES') ? TZ_THE_LARGEST_HEROES : TZ_THE_MOST_EXPERIENCED_HEROES;
$heroNameCol = defined('TZ_HERO_NAME_COL') ? TZ_HERO_NAME_COL : U0;
$heroOffCol = defined('TZ_HERO_OFF_EFF') ? TZ_HERO_OFF_EFF : OFF_BONUS;
$heroDefCol = defined('TZ_HERO_DEF_EFF') ? TZ_HERO_DEF_EFF : DEF_BONUS;
$heroStrCol = defined('TZ_HERO_IND_STRENGTH') ? TZ_HERO_IND_STRENGTH : TZ_HERO_FIGHTING_STRENGTH;
$heroLifeCol = defined('TZ_HERO_LIFE') ? TZ_HERO_LIFE : 'الحياة';
$heroAlive = defined('TZ_HERO_ALIVE') ? TZ_HERO_ALIVE : 'حي';
$heroDead = defined('TZ_HERO_DEAD') ? TZ_HERO_DEAD : 'ميت';
?>
<div class="gk-sta-hero-wrap">
<table class="b0 fl L6_S gk-sta-hero">
<colgroup>
    <col class="gk-sta-rank-col" />
    <col class="gk-sta-hero-name-col" />
    <col class="gk-sta-hero-owner-col" />
    <col class="gk-sta-hero-lvl-col" />
    <col class="gk-sta-hero-xp-col" />
    <col class="gk-sta-hero-off-col" />
    <col class="gk-sta-hero-def-col" />
    <col class="gk-sta-hero-str-col" />
    <col class="gk-sta-hero-life-col" />
</colgroup>
<tbody>
<tr class="gk-sta-head"><th colspan="9"><div class="gk-sta-bar">
<span class="staRi" aria-hidden="true"></span>
<span class="staTit"><?php echo $heroTitle; ?></span>
<span class="staLe" aria-hidden="true"></span>
</div></th></tr>
<tr class="gk-sta-cols">
    <th class="ra">#</th>
    <th class="hero"><?php echo $heroNameCol; ?></th>
    <th class="ow"><?php echo OWNER; ?></th>
    <th class="lev"><?php echo LEVEL; ?></th>
    <th class="xp"><?php echo EXPERIENCE; ?></th>
    <th class="off"><?php echo $heroOffCol; ?></th>
    <th class="def"><?php echo $heroDefCol; ?></th>
    <th class="str"><?php echo $heroStrCol; ?></th>
    <th class="life"><?php echo $heroLifeCol; ?></th>
</tr>
<?php
if (count($rankArray) > 1) {
    for ($i = $start; $i < $start + 20; $i++) {
        if (!isset($rankArray[$i]['name']) || $rankArray[$i] === 'pad') {
            continue;
        }
        $row = $rankArray[$i];
        $rowUid = (int) ($row['uid'] ?? 0);
        $gkUid = isset($session->uid) ? (int) $session->uid : 0;
        $isHighlight = ($i === $search) || ($rowUid > 0 && $rowUid === $gkUid);
        $hlCls = $isHighlight ? ' hl' : '';
        $fcCls = $isHighlight ? ' fc' : '';
        $lcCls = $isHighlight ? ' lc' : '';

        $unit = (int) ($row['unit'] ?? 0);
        $heroName = htmlspecialchars((string) $row['name'], ENT_QUOTES, 'UTF-8');
        $owner = htmlspecialchars((string) ($row['owner'] ?? ''), ENT_QUOTES, 'UTF-8');
        $offVal = (float) $heroBonusPct($row['attackbonus'] ?? 0);
        $defVal = (float) $heroBonusPct($row['defencebonus'] ?? 0);
        $strVal = (int) ($row['attack'] ?? 0);
        $offPct = $heroBonusPct($row['attackbonus'] ?? 0);
        $defPct = $heroBonusPct($row['defencebonus'] ?? 0);
        $isDead = !empty($row['dead']);
        $offZero = ($offVal <= 0);
        $defZero = ($defVal <= 0);
        $strZero = ($strVal <= 0);
        $iconUnit = $isDead ? 1 : $unit;
        $iconCls = 'unit u' . (int) $iconUnit;

        echo '<tr class="' . trim($hlCls) . '">';
        echo '<th class="ra' . $fcCls . '">' . $i . '</th>';
        echo '<th class="hero">';
        echo '<img class="' . $iconCls . '" alt="" title="" src="img/x.gif" /> ';
        echo $heroName;
        echo '</th>';
        echo '<th class="ow"><a href="spieler.php?uid=' . $rowUid . '">' . $owner . '</a></th>';
        echo '<th class="lev">' . (int) ($row['level'] ?? 0) . '</th>';
        echo '<th class="xp"><bdi dir="ltr">' . number_format((int) ($row['experience'] ?? 0)) . '</bdi></th>';
        echo '<th class="off' . ($offZero ? ' gk-sta-zero' : '') . '">%'
            . htmlspecialchars($offPct, ENT_QUOTES, 'UTF-8') . '</th>';
        echo '<th class="def' . ($defZero ? ' gk-sta-zero' : '') . '">%'
            . htmlspecialchars($defPct, ENT_QUOTES, 'UTF-8') . '</th>';
        echo '<th class="str' . ($strZero ? ' gk-sta-zero' : '') . '">' . $strVal . '</th>';
        if ($isDead) {
            echo '<th class="life dead' . $lcCls . '">' . $heroDead . '</th>';
        } else {
            echo '<th class="life alive' . $lcCls . '">' . $heroAlive . '</th>';
        }
        echo '</tr>';
    }
} else {
    echo '<tr><th class="none" colspan="9">' . TZ_NO_HEROES_FOUND . '</th></tr>';
}
?>
</tbody></table>
</div>
<?php include __DIR__ . '/stat_search_greek.tpl'; ?>
