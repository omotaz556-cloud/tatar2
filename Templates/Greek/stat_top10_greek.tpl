<?php
/**
 * Greek.sa Top 10 — 4 daily tables in 2×2 grid (stat?S=5 / id=7).
 */

global $database, $session;

mysqli_report(MYSQLI_REPORT_OFF);

$gkUid = isset($session->uid) ? (int) $session->uid : 0;
$accessMax = defined('INCLUDE_ADMIN') && INCLUDE_ADMIN ? 10 : 8;
$tribeList = '1,2,3,6,7,8,9';
$userFilter = 'access < ' . (int) $accessMax . ' AND id > 5 AND tribe IN (' . $tribeList . ')';

$titleTop10 = defined('TZ_TOP_10') ? TZ_TOP_10 : 'أفضل 10';
$colRank = '#';
$colPlayer = defined('PLAYER') ? PLAYER : 'اللاعبون';
$colPoints = defined('POINTS') ? POINTS : 'النقاط';
$goldTitle = defined('GOLD') ? GOLD : 'ذهب';

$tableDefs = [
    [
        'id' => 'top10_offs',
        'title' => defined('TZ_STAT_TOP10_ATT_DAY') ? TZ_STAT_TOP10_ATT_DAY : 'مهاجموا اليوم',
        'field' => 'ap',
        'pos' => 'right',
    ],
    [
        'id' => 'top10_defs',
        'title' => defined('TZ_STAT_TOP10_DEF_DAY') ? TZ_STAT_TOP10_DEF_DAY : 'مدافعو اليوم',
        'field' => 'dp',
        'pos' => 'left',
    ],
    [
        'id' => 'top10_climbers',
        'title' => defined('TZ_STAT_TOP10_DEV_DAY') ? TZ_STAT_TOP10_DEV_DAY : 'مطورو اليوم',
        'field' => 'clp',
        'pos' => 'right',
    ],
    [
        'id' => 'top10_raiders',
        'title' => defined('TZ_STAT_TOP10_ROB_DAY') ? TZ_STAT_TOP10_ROB_DAY : 'ناهبو اليوم',
        'field' => 'RR',
        'pos' => 'left',
        'nonNegative' => true,
    ],
];

$gkStatTop10UserRank = static function ($database, $uid, $field, $userFilter) {
    $uid = (int) $uid;
    $allowed = ['ap', 'dp', 'clp', 'RR'];
    if ($uid <= 0 || !in_array($field, $allowed, true)) {
        return '?';
    }
    $q = 'SELECT COUNT(*) + 1 AS rk FROM ' . TB_PREFIX . 'users WHERE ' . $userFilter
        . ' AND ' . $field . ' > (SELECT ' . $field . ' FROM ' . TB_PREFIX . 'users WHERE id = ' . $uid . ')';
    $res = mysqli_query($database->dblink, $q);
    if (!$res) {
        return '?';
    }
    $row = mysqli_fetch_assoc($res);
    return $row ? (int) $row['rk'] : '?';
};

/** Projected daily gold reward shown in the coin column (greek.sa). */
$gkStatTop10Gold = static function ($pts, $rank) {
    $pts = (int) $pts;
    $rank = (int) $rank;
    if ($pts <= 0) {
        return 0;
    }
    $div = defined('STAT_TOP10_GOLD_DIVISOR') ? (int) STAT_TOP10_GOLD_DIVISOR : 55897;
    if ($div < 1) {
        $div = 55897;
    }
    $gold = (int) floor($pts / $div);
    if ($gold < 1) {
        $gold = max(1, 12 - $rank);
    }
    return $gold;
};

$gkStatTop10NextReset = static function ($database) {
    $interval = defined('STAT_TOP10_RESET_INTERVAL') ? (int) STAT_TOP10_RESET_INTERVAL : 86400;

    if ($interval === 86400) {
        $nextReset = strtotime('tomorrow midnight');
        if ($nextReset === false) {
            $nextReset = time() + 86400;
        }
        return [$nextReset, $interval];
    }

    if ($interval <= 0) {
        $interval = defined('MEDALINTERVAL') ? (int) MEDALINTERVAL : 604800;
    }

    $nextReset = time() + $interval;
    $q = mysqli_query($database->dblink, 'SELECT lastgavemedal FROM ' . TB_PREFIX . 'config LIMIT 1');
    if ($q && ($rc = mysqli_fetch_assoc($q))) {
        $last = (int) $rc['lastgavemedal'];
        if ($last > 0) {
            $nextReset = $last;
            while ($nextReset <= time()) {
                $nextReset += $interval;
            }
        } else {
            $setDays = (int) round($interval / 86400);
            $nextReset = $setDays < 7
                ? strtotime(($setDays + 1) . ' day midnight')
                : strtotime('next monday');
            if ($nextReset === false) {
                $nextReset = time() + $interval;
            }
        }
    }

    return [$nextReset, $interval];
};

$gkStatTop10UserGoldTotal = static function (
    $database,
    $gkUid,
    $userFilter,
    $gkStatTop10UserRank,
    $gkStatTop10Gold,
    array $tableDefs
) {
    $gkUid = (int) $gkUid;
    if ($gkUid <= 0) {
        return 0;
    }

    $total = 0;
    foreach ($tableDefs as $def) {
        $field = $def['field'];
        $allowed = ['ap', 'dp', 'clp', 'RR'];
        if (!in_array($field, $allowed, true)) {
            continue;
        }

        $q = 'SELECT ' . $field . ' AS pts FROM ' . TB_PREFIX . 'users WHERE id = ' . $gkUid . ' LIMIT 1';
        $res = mysqli_query($database->dblink, $q);
        if (!$res || !($row = mysqli_fetch_assoc($res))) {
            continue;
        }

        $pts = (int) $row['pts'];
        if (!empty($def['nonNegative']) && $pts < 0) {
            continue;
        }

        $rank = $gkStatTop10UserRank($database, $gkUid, $field, $userFilter);
        $rankNum = is_numeric($rank) ? (int) $rank : 99;
        $total += $gkStatTop10Gold($pts, $rankNum);
    }

    return $total;
};

$gkStatTop10RenderRow = static function (
    $rank,
    $username,
    $uid,
    $pts,
    $gold,
    $isSelf,
    $isEmpty
) {
    $hlCls = $isSelf ? ' hl' : '';
    $fcCls = $isSelf ? ' fc' : '';
    $lcCls = $isSelf ? ' lc' : '';
    $top3Cls = (!$isEmpty && $rank <= 3) ? ' top3' : '';

    echo '<tr class="' . trim($hlCls . ($isEmpty ? ' gk-sta-empty-row' : '')) . '">';
    echo '<th class="ra' . $fcCls . '">' . (int) $rank . '</th>';

    echo '<th class="pla' . ($isEmpty ? ' gk-sta-empty' : '') . '">';
    if ($isEmpty) {
        echo '-';
    } elseif ($isSelf) {
        echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    } else {
        echo '<a class="' . trim($top3Cls) . '" href="spieler.php?uid=' . (int) $uid . '">'
            . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . '</a>';
    }
    echo '</th>';

    $ptsFmt = $isEmpty ? '0' : number_format((int) $pts);
    echo '<th class="po' . ($isEmpty ? ' gk-sta-empty' : '') . '"><bdi dir="ltr">' . $ptsFmt . '</bdi></th>';

    $goldVal = $isEmpty ? 0 : (int) $gold;
    echo '<th class="gold' . ($isEmpty ? ' gk-sta-empty' : '') . $lcCls . '"><bdi dir="ltr">' . $goldVal . '</bdi></th>';
    echo '</tr>';
};

$gkStatTop10RenderTable = static function (
    $database,
    $gkUid,
    $userFilter,
    $gkStatTop10UserRank,
    $gkStatTop10Gold,
    $gkStatTop10RenderRow,
    array $def,
    $colRank,
    $colPlayer,
    $colPoints,
    $goldTitle
) {
    $field = $def['field'];
    $tableId = $def['id'];
    $title = $def['title'];
    $nonNegative = !empty($def['nonNegative']);
    $posClass = ($def['pos'] ?? 'right') === 'left' ? 'gk-sta-top10-left' : 'gk-sta-top10-right';

    $q = 'SELECT id, username, ' . $field . ' AS pts FROM ' . TB_PREFIX . 'users WHERE '
        . $userFilter . ' ORDER BY ' . $field . ' DESC, id DESC LIMIT 10';
    $result = mysqli_query($database->dblink, $q);

    $rows = [];
    $userInTop = false;
    $rank = 0;
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($nonNegative && (int) $row['pts'] < 0) {
                continue;
            }
            $rank++;
            $isSelf = ((int) $row['id'] === $gkUid);
            if ($isSelf) {
                $userInTop = true;
            }
            $pts = (int) $row['pts'];
            $rows[] = [
                'rank' => $rank,
                'id' => (int) $row['id'],
                'username' => $row['username'],
                'pts' => $pts,
                'gold' => $gkStatTop10Gold($pts, $rank),
                'self' => $isSelf,
            ];
        }
    }

    $selfRow = null;
    if ($gkUid > 0 && !$userInTop) {
        $selfQ = 'SELECT id, username, ' . $field . ' AS pts FROM ' . TB_PREFIX . 'users WHERE id = ' . $gkUid . ' LIMIT 1';
        $selfRes = mysqli_query($database->dblink, $selfQ);
        if ($selfRes && ($self = mysqli_fetch_assoc($selfRes))) {
            if (!$nonNegative || (int) $self['pts'] >= 0) {
                $selfRank = $gkStatTop10UserRank($database, $gkUid, $field, $userFilter);
                $selfPts = (int) $self['pts'];
                $selfRow = [
                    'rank' => $selfRank,
                    'id' => (int) $self['id'],
                    'username' => $self['username'],
                    'pts' => $selfPts,
                    'gold' => $gkStatTop10Gold($selfPts, is_numeric($selfRank) ? (int) $selfRank : 99),
                    'self' => true,
                ];
            }
        }
    }

    echo '<table id="' . htmlspecialchars($tableId, ENT_QUOTES, 'UTF-8')
        . '" class="b0 fl L6_S gk-sta-top10-mini ' . $posClass . '"><colgroup>';
    echo '<col class="gk-sta-top10-col-rank">';
    echo '<col class="gk-sta-top10-col-pla">';
    echo '<col class="gk-sta-top10-col-po">';
    echo '<col class="gk-sta-top10-col-gold">';
    echo '</colgroup><tbody>';

    echo '<tr class="gk-sta-top10-title-row">';
    echo '<th colspan="4" class="gk-sta-top10-title">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</th>';
    echo '</tr>';

    echo '<tr class="gk-sta-cols">';
    echo '<th class="ra">' . htmlspecialchars($colRank, ENT_QUOTES, 'UTF-8') . '</th>';
    echo '<th class="pla">' . htmlspecialchars($colPlayer, ENT_QUOTES, 'UTF-8') . '</th>';
    echo '<th class="po">' . htmlspecialchars($colPoints, ENT_QUOTES, 'UTF-8') . '</th>';
    echo '<th class="gold"><img src="img/x.gif" class="gold" alt="" title="'
        . htmlspecialchars($goldTitle, ENT_QUOTES, 'UTF-8') . '"></th>';
    echo '</tr>';

    $byRank = [];
    foreach ($rows as $row) {
        $byRank[(int) $row['rank']] = $row;
    }

    for ($slot = 1; $slot <= 10; $slot++) {
        if (isset($byRank[$slot])) {
            $row = $byRank[$slot];
            $gkStatTop10RenderRow(
                $row['rank'],
                $row['username'],
                $row['id'],
                $row['pts'],
                $row['gold'],
                $row['self'],
                false
            );
        } else {
            $gkStatTop10RenderRow($slot, '', 0, 0, 0, false, true);
        }
    }

    if ($selfRow !== null) {
        echo '<tr class="gk-sta-top10-spacer"><th colspan="4"></th></tr>';
        echo '<tr class="none">';
        echo '<th class="ra fc">' . htmlspecialchars((string) $selfRow['rank'], ENT_QUOTES, 'UTF-8') . '</th>';
        echo '<th class="pla">' . htmlspecialchars($selfRow['username'], ENT_QUOTES, 'UTF-8') . '</th>';
        echo '<th class="po gk-sta-empty"><bdi dir="ltr">' . number_format($selfRow['pts']) . '</bdi></th>';
        echo '<th class="gold lc gk-sta-empty"><bdi dir="ltr">' . (int) $selfRow['gold'] . '</bdi></th>';
        echo '</tr>';
    }

    echo '</tbody></table>';
};

list($gkTop10NextReset, $gkTop10ResetInterval) = $gkStatTop10NextReset($database);
$gkTop10MedalSecondsLeft = max(0, (int) $gkTop10NextReset - time());
$gkTop10UserGold = $gkStatTop10UserGoldTotal(
    $database,
    $gkUid,
    $userFilter,
    $gkStatTop10UserRank,
    $gkStatTop10Gold,
    $tableDefs
);
$gkTop10MedalAfter = defined('TZ_STAT_TOP10_MEDAL_AFTER') ? TZ_STAT_TOP10_MEDAL_AFTER : 'بعد';
$gkTop10MedalHours = defined('TZ_STAT_TOP10_MEDAL_HOURS') ? TZ_STAT_TOP10_MEDAL_HOURS : 'ساعة ستوزع الأوسمة';
$gkTop10MedalEarn = defined('TZ_STAT_TOP10_MEDAL_EARN') ? TZ_STAT_TOP10_MEDAL_EARN : 'في نهاية هذه التوزيعة ستكسب';
if ($gkTop10ResetInterval === 86400) {
    $gkTop10MedalFreq = defined('TZ_STAT_TOP10_MEDAL_DAILY') ? TZ_STAT_TOP10_MEDAL_DAILY : 'توزيع الأوسمة يتم كل يوم';
} else {
    $days = max(1, (int) round($gkTop10ResetInterval / 86400));
    if ($days === 1) {
        $periodLabel = defined('LOGIN_DAY_ONE') ? LOGIN_DAY_ONE : 'يوم';
    } elseif ($days === 2) {
        $periodLabel = defined('LOGIN_DAY_TWO') ? LOGIN_DAY_TWO : 'يومان';
    } elseif ($days >= 3 && $days <= 10) {
        $periodLabel = $days . ' ' . (defined('LOGIN_DAYS_FEW') ? LOGIN_DAYS_FEW : 'أيام');
    } else {
        $periodLabel = $days . ' ' . (defined('LOGIN_DAY_ZERO') ? LOGIN_DAY_ZERO : 'يوم');
    }
    $gkTop10MedalFreq = sprintf(
        defined('TZ_STAT_TOP10_MEDAL_EVERY') ? TZ_STAT_TOP10_MEDAL_EVERY : 'توزيع الأوسمة يتم كل %s',
        $periodLabel
    );
}
$gkTop10InitialClock = sprintf(
    '%02d:%02d:%02d',
    (int) floor($gkTop10MedalSecondsLeft / 3600),
    (int) floor(($gkTop10MedalSecondsLeft % 3600) / 60),
    (int) ($gkTop10MedalSecondsLeft % 60)
);
?>
<div class="gk-sta-top10-wrap">
<table class="b0 fl L6_S gk-sta-top10-head"><tbody>
<tr class="gk-sta-head"><th colspan="4"><div class="gk-sta-bar">
<span class="staRi">
<?php
if (defined('NEW_FUNCTIONS_PLUS_STATISTICS') && NEW_FUNCTIONS_PLUS_STATISTICS
    && isset($session->plus) && (int) $session->plus === 1) {
    $psLabel = defined('PLUSSTATS_TITLE') ? PLUSSTATS_TITLE : 'Graphical statistics';
    echo '<a title="' . htmlspecialchars($psLabel, ENT_QUOTES, 'UTF-8') . '" href="statistiken.php?id=50">'
        . '<img class="btn_stats" src="img/x.gif" alt="" /></a>';
}
?>
<img class="btn_top10 active" src="img/x.gif" alt="<?php echo htmlspecialchars($titleTop10, ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars($titleTop10, ENT_QUOTES, 'UTF-8'); ?>">
<a title="<?php echo defined('DEFENDER') ? DEFENDER : 'مدافع'; ?>" href="statistiken.php?id=32"><img class="btn_def" src="img/x.gif" alt="" /></a>
<a title="<?php echo defined('ATTACKER') ? ATTACKER : 'مهاجم'; ?>" href="statistiken.php?id=31"><img class="btn_off" src="img/x.gif" alt="" /></a>
</span>
<span class="staTit"><?php echo htmlspecialchars($titleTop10, ENT_QUOTES, 'UTF-8'); ?></span>
<span class="staLe">
<a title="<?php echo TRIBE1; ?>" href="statistiken.php?id=11"><img class="btn_v1" src="img/x.gif" alt="" /></a>
<a title="<?php echo TRIBE2; ?>" href="statistiken.php?id=12"><img class="btn_v2" src="img/x.gif" alt="" /></a>
<a title="<?php echo TRIBE3; ?>" href="statistiken.php?id=13"><img class="btn_v3" src="img/x.gif" alt="" /></a>
<?php if (defined('NEW_FUNCTION_TRIBE_HUNS') && NEW_FUNCTION_TRIBE_HUNS) { ?>
<a title="<?php echo TRIBE6; ?>" href="statistiken.php?id=16"><img class="btn_v6" src="img/x.gif" alt="" /></a>
<?php } ?>
<?php if (defined('NEW_FUNCTION_TRIBE_EGIPTEANS') && NEW_FUNCTION_TRIBE_EGIPTEANS) { ?>
<a title="<?php echo TRIBE7; ?>" href="statistiken.php?id=17"><img class="btn_v7" src="img/x.gif" alt="" /></a>
<?php } ?>
<?php if (defined('NEW_FUNCTION_TRIBE_SPARTANS') && NEW_FUNCTION_TRIBE_SPARTANS) { ?>
<a title="<?php echo TRIBE8; ?>" href="statistiken.php?id=18"><img class="btn_v8" src="img/x.gif" alt="" /></a>
<?php } ?>
<?php if (defined('NEW_FUNCTION_TRIBE_VIKINGS') && NEW_FUNCTION_TRIBE_VIKINGS) { ?>
<a title="<?php echo TRIBE9; ?>" href="statistiken.php?id=19"><img class="btn_v9" src="img/x.gif" alt="" /></a>
<?php } ?>
</span>
</div></th></tr>
</tbody></table>

<div class="gk-sta-top10-grid">
<?php
foreach ($tableDefs as $def) {
    $gkStatTop10RenderTable(
        $database,
        $gkUid,
        $userFilter,
        $gkStatTop10UserRank,
        $gkStatTop10Gold,
        $gkStatTop10RenderRow,
        $def,
        $colRank,
        $colPlayer,
        $colPoints,
        $goldTitle
    );
}
?>
<div class="gk-sta-top10-clear"></div>
</div>

<div class="gk-sta-top10-medal-banner" id="gkStatTop10MedalBanner">
    <p class="gk-sta-top10-medal-line">
        <?php echo htmlspecialchars($gkTop10MedalAfter, ENT_QUOTES, 'UTF-8'); ?>
        <span id="gkStatTop10MedalTimer" class="gk-sta-top10-medal-clock"><?php echo htmlspecialchars($gkTop10InitialClock, ENT_QUOTES, 'UTF-8'); ?></span>
        <?php echo htmlspecialchars($gkTop10MedalHours, ENT_QUOTES, 'UTF-8'); ?>
    </p>
    <p class="gk-sta-top10-medal-line gk-sta-top10-medal-sub">
        <?php echo htmlspecialchars($gkTop10MedalEarn, ENT_QUOTES, 'UTF-8'); ?>
        <bdi dir="ltr" id="gkStatTop10MedalGold"><?php echo (int) $gkTop10UserGold; ?></bdi>
        <img src="img/x.gif" class="gold" alt="" title="<?php echo htmlspecialchars($goldTitle, ENT_QUOTES, 'UTF-8'); ?>">
        <?php echo ' ' . htmlspecialchars($gkTop10MedalFreq, ENT_QUOTES, 'UTF-8'); ?>
    </p>
</div>
<script>
(function () {
    var secs = <?php echo (int) $gkTop10MedalSecondsLeft; ?>;
    var el = document.getElementById('gkStatTop10MedalTimer');
    if (!el) {
        return;
    }
    function pad(n) {
        return String(n).padStart(2, '0');
    }
    function tick() {
        if (secs < 0) {
            secs = 0;
        }
        var h = Math.floor(secs / 3600);
        var m = Math.floor((secs % 3600) / 60);
        var s = secs % 60;
        el.textContent = pad(h) + ':' + pad(m) + ':' + pad(s);
        if (secs > 0) {
            secs--;
        }
    }
    tick();
    setInterval(tick, 1000);
})();
</script>
</div>
