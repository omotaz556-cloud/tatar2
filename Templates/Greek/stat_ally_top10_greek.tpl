<?php
/**
 * Greek.sa Top 10 alliances — 4 weekly tables in 2×2 grid (statistiken.php?id=43).
 */

global $database, $session;

mysqli_report(MYSQLI_REPORT_OFF);

$gkAid = isset($session->alliance) ? (int) $session->alliance : 0;
$titleTop10 = defined('TZ_TOP_10_ALLIANCES') ? TZ_TOP_10_ALLIANCES : 'أفضل 10 تحالفات';
$colRank = '#';
$colAlly = defined('ALLIANCE') ? ALLIANCE : 'التحالف';
$colPoints = defined('POINTS') ? POINTS : 'النقاط';
$colPop = defined('POP') ? POP : 'السكان';
$colRes = defined('RESOURCES') ? RESOURCES : 'الموارد';

$tableDefs = [
    [
        'id' => 'top10_offs',
        'title' => defined('ATT_W_M') ? ATT_W_M : 'مهاجمو الأسبوع',
        'field' => 'ap',
        'valueLabel' => $colPoints,
    ],
    [
        'id' => 'top10_defs',
        'title' => defined('DEF_W_M') ? DEF_W_M : 'مدافعو الأسبوع',
        'field' => 'dp',
        'valueLabel' => $colPoints,
    ],
    [
        'id' => 'top10_climbers',
        'title' => defined('TZ_CLIMBERS_OF_THE_WEEK') ? TZ_CLIMBERS_OF_THE_WEEK : 'متسلقو الأسبوع',
        'field' => 'clp',
        'valueLabel' => $colPop,
    ],
    [
        'id' => 'top10_raiders',
        'title' => defined('ROB_W_M') ? ROB_W_M : 'ناهبو الأسبوع',
        'field' => 'RR',
        'valueLabel' => $colRes,
        'nonNegative' => true,
    ],
];

$gkAllyTop10Rank = static function ($database, $aid, $field) {
    $aid = (int) $aid;
    $allowed = ['ap', 'dp', 'clp', 'RR'];
    if ($aid <= 0 || !in_array($field, $allowed, true)) {
        return '?';
    }
    $q = 'SELECT COUNT(*) + 1 AS rk FROM ' . TB_PREFIX . 'alidata WHERE id != 0 AND '
        . $field . ' > (SELECT ' . $field . ' FROM ' . TB_PREFIX . 'alidata WHERE id = ' . $aid . ')';
    $res = mysqli_query($database->dblink, $q);
    if (!$res) {
        return '?';
    }
    $row = mysqli_fetch_assoc($res);
    return $row ? (int) $row['rk'] : '?';
};

$gkAllyTop10Render = static function (
    $database,
    $gkAid,
    $gkAllyTop10Rank,
    array $def,
    $colRank,
    $colAlly
) {
    $field = $def['field'];
    $allowed = ['ap', 'dp', 'clp', 'RR'];
    if (!in_array($field, $allowed, true)) {
        return;
    }

    $whereExtra = !empty($def['nonNegative']) ? (' AND ' . $field . ' >= 0') : '';
    $q = 'SELECT id, tag, ' . $field . ' AS pts FROM ' . TB_PREFIX . 'alidata
        WHERE id != 0' . $whereExtra . '
        ORDER BY ' . $field . ' DESC, id DESC LIMIT 10';
    $res = mysqli_query($database->dblink, $q);
    $rows = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $rows[] = $row;
        }
    }

    $ownInTop = false;
    $ownPts = 0;
    $ownTag = '';
    if ($gkAid > 0) {
        $oq = mysqli_query(
            $database->dblink,
            'SELECT tag, ' . $field . ' AS pts FROM ' . TB_PREFIX . 'alidata WHERE id = ' . $gkAid . ' LIMIT 1'
        );
        if ($oq && ($orow = mysqli_fetch_assoc($oq))) {
            $ownTag = (string) $orow['tag'];
            $ownPts = (int) $orow['pts'];
        }
    }

    echo '<table class="b0 fl L6_S gk-sta-top10" id="'
        . htmlspecialchars($def['id'], ENT_QUOTES, 'UTF-8') . '"><tbody>';
    echo '<tr class="gk-sta-head"><th class="gk-sta-top10-help" onclick="return Popup(3,5)">'
        . '<img src="img/x.gif" class="help" alt="" /></th>';
    echo '<th colspan="2" class="gk-sta-top10-title">'
        . htmlspecialchars($def['title'], ENT_QUOTES, 'UTF-8') . '</th></tr>';
    echo '<tr class="gk-sta-cols"><th class="ra">' . htmlspecialchars($colRank, ENT_QUOTES, 'UTF-8')
        . '</th><th class="pla">' . htmlspecialchars($colAlly, ENT_QUOTES, 'UTF-8')
        . '</th><th class="po">' . htmlspecialchars($def['valueLabel'], ENT_QUOTES, 'UTF-8')
        . '</th></tr>';

    $i = 1;
    foreach ($rows as $row) {
        $aid = (int) $row['id'];
        $isOwn = ($gkAid > 0 && $aid === $gkAid);
        if ($isOwn) {
            $ownInTop = true;
        }
        $hl = $isOwn ? ' hl own' : '';
        echo '<tr class="' . trim($hl) . '">';
        echo '<th class="ra">' . $i . '</th>';
        echo '<th class="pla"><a href="allianz.php?aid=' . $aid . '">'
            . htmlspecialchars((string) $row['tag'], ENT_QUOTES, 'UTF-8') . '</a></th>';
        echo '<th class="po"><bdi dir="ltr">' . number_format((int) $row['pts']) . '</bdi></th>';
        echo '</tr>';
        $i++;
    }

    if ($gkAid > 0 && !$ownInTop && $ownTag !== '') {
        $rk = $gkAllyTop10Rank($database, $gkAid, $field);
        echo '<tr class="hl own">';
        echo '<th class="ra">' . htmlspecialchars((string) $rk, ENT_QUOTES, 'UTF-8') . '</th>';
        echo '<th class="pla">' . htmlspecialchars($ownTag, ENT_QUOTES, 'UTF-8') . '</th>';
        echo '<th class="po"><bdi dir="ltr">' . number_format($ownPts) . '</bdi></th>';
        echo '</tr>';
    }

    echo '</tbody></table>';
};
?>
<div class="gk-sta-top10-wrap">
<table class="b0 fl L6_S gk-sta-top10-head"><tbody>
<tr class="gk-sta-head"><th colspan="4"><div class="gk-sta-bar">
<span class="staRi">
<img class="btn_top10 active" src="img/x.gif" alt="" title="<?php echo htmlspecialchars($titleTop10, ENT_QUOTES, 'UTF-8'); ?>" />
<a title="<?php echo DEFENDER; ?>" href="statistiken.php?id=42"><img class="btn_def" src="img/x.gif" alt="" /></a>
<a title="<?php echo ATTACKER; ?>" href="statistiken.php?id=41"><img class="btn_off" src="img/x.gif" alt="" /></a>
</span>
<span class="staTit"><?php echo htmlspecialchars($titleTop10, ENT_QUOTES, 'UTF-8'); ?></span>
<span class="staLe" aria-hidden="true"></span>
</div></th></tr>
</tbody></table>

<div class="gk-sta-top10-grid">
<?php
foreach ($tableDefs as $def) {
    $gkAllyTop10Render($database, $gkAid, $gkAllyTop10Rank, $def, $colRank, $colAlly);
}
?>
<div class="gk-sta-top10-clear"></div>
</div>
</div>
