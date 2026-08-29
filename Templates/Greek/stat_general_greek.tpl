<?php
/**
 * Greek.sa literal general statistics — 3 tables (world times, world info, tribes).
 */

global $database, $generator;

mysqli_report(MYSQLI_REPORT_OFF);

$activeTribes = [1, 2, 3];
$tribeFlagMap = [
    6 => 'NEW_FUNCTION_TRIBE_HUNS',
    7 => 'NEW_FUNCTION_TRIBE_EGIPTEANS',
    8 => 'NEW_FUNCTION_TRIBE_SPARTANS',
    9 => 'NEW_FUNCTION_TRIBE_VIKINGS',
];
foreach ($tribeFlagMap as $tid => $flag) {
    if (defined($flag) && constant($flag)) {
        $activeTribes[] = $tid;
    }
}
$tribeDisplayOrder = [3, 2, 7, 6, 8, 9, 1];
$tribeDisplayOrder = array_values(array_filter(
    $tribeDisplayOrder,
    static fn ($tid) => in_array($tid, $activeTribes, true)
));

$tribeInList = implode(',', $activeTribes);
$tribes = array_fill(1, 9, 0);
$tribesRes = mysqli_query(
    $database->dblink,
    'SELECT tribe, COUNT(*) AS Total FROM ' . TB_PREFIX . 'users WHERE tribe IN (' . $tribeInList . ') GROUP BY tribe'
);
if ($tribesRes) {
    while ($row = mysqli_fetch_assoc($tribesRes)) {
        $tribes[(int) $row['tribe']] = (int) $row['Total'];
    }
}

$userRes = mysqli_query(
    $database->dblink,
    'SELECT COUNT(*) AS Total FROM ' . TB_PREFIX . 'users WHERE tribe IN (' . $tribeInList . ')'
);
$playerCount = $userRes ? (int) mysqli_fetch_assoc($userRes)['Total'] : 0;

$onlineRes = mysqli_query(
    $database->dblink,
    'SELECT COUNT(*) AS Total FROM ' . TB_PREFIX . 'users WHERE timestamp > ' . (time() - 600)
    . ' AND tribe IN (' . $tribeInList . ')'
);
$onlineCount = $onlineRes ? (int) mysqli_fetch_assoc($onlineRes)['Total'] : 0;

$speed = max(1, (float) (defined('SPEED') ? SPEED : 1));
$startTs = (int) COMMENCE;
if ($startTs <= 0) {
    $startTs = (int) strtotime(START_DATE . ' ' . (defined('START_TIME') ? START_TIME : '00:00:00'));
}

$natarsSpawnDayCfg = (int) (defined('NATARS_SPAWN_TIME') ? NATARS_SPAWN_TIME : 0);
$artifactSpawnAt = (int) strtotime(START_DATE) + (int) round($natarsSpawnDayCfg * 86400 / $speed / 2);
$natarsSpawnAt = (int) strtotime(START_DATE) + (int) round($natarsSpawnDayCfg * 86400 / $speed);
$serverNow = time();

$artifactsSpawned = method_exists($database, 'areArtifactsSpawned')
    ? (bool) $database->areArtifactsSpawned()
    : ($serverNow >= $artifactSpawnAt);
$natarsSpawned = $serverNow >= $natarsSpawnAt;

$worldAgeSec = max(0, $serverNow - $startTs);

if ($artifactsSpawned) {
    $artifactSec = max(0, $serverNow - $artifactSpawnAt);
    $artifactLabel = defined('TZ_STAT_SINCE_PREFIX') ? TZ_STAT_SINCE_PREFIX : 'منذ';
} else {
    $artifactSec = max(0, $artifactSpawnAt - $serverNow);
    $artifactLabel = defined('TZ_STAT_LEFT_PREFIX') ? TZ_STAT_LEFT_PREFIX : 'بقي';
}

if ($natarsSpawned) {
    $natarsSec = max(0, $serverNow - $natarsSpawnAt);
    $natarsLabel = defined('TZ_STAT_SINCE_PREFIX') ? TZ_STAT_SINCE_PREFIX : 'منذ';
} else {
    $natarsSec = max(0, $natarsSpawnAt - $serverNow);
    $natarsLabel = defined('TZ_STAT_LEFT_PREFIX') ? TZ_STAT_LEFT_PREFIX : 'بقي';
}

$hoursSuffix = defined('TZ_STAT_HOURS_SUFFIX') ? TZ_STAT_HOURS_SUFFIX : 'ساعة';

$fmtClockHtml = static function ($seconds) use ($generator, $hoursSuffix) {
    return '<bdi dir="ltr">' . htmlspecialchars($generator->getTimeFormat($seconds), ENT_QUOTES, 'UTF-8')
        . '</bdi> ' . htmlspecialchars($hoursSuffix, ENT_QUOTES, 'UTF-8');
};

$fmtEventHtml = static function ($seconds, $prefix) use ($generator, $hoursSuffix) {
    return htmlspecialchars($prefix, ENT_QUOTES, 'UTF-8') . ' <bdi dir="ltr">'
        . htmlspecialchars($generator->getTimeFormat($seconds), ENT_QUOTES, 'UTF-8')
        . '</bdi> ' . htmlspecialchars($hoursSuffix, ENT_QUOTES, 'UTF-8');
};

$gameSpeed = (int) round($speed / 45 * 100);
$troopSpeed = (int) max(1, round($speed / 3));
$prodSpeed = $gameSpeed;
$storageSize = (int) round((float) (defined('STORAGE_MULTIPLIER') ? STORAGE_MULTIPLIER : 1) * 100);
$protHours = (int) round((int) (defined('PROTECTION') ? PROTECTION : 0) / 3600);
$natarsSpawnDay = (int) round($natarsSpawnDayCfg / $speed);

$tribeNames = [
    1 => defined('REG_TRIBE_ROMANS') ? REG_TRIBE_ROMANS : (defined('TRIBE1') ? TRIBE1 : 'Tribe 1'),
    2 => defined('REG_TRIBE_TEUTONS') ? REG_TRIBE_TEUTONS : (defined('TRIBE2') ? TRIBE2 : 'Tribe 2'),
    3 => defined('REG_TRIBE_GAULS') ? REG_TRIBE_GAULS : (defined('TRIBE3') ? TRIBE3 : 'Tribe 3'),
    6 => defined('TRIBE6') ? TRIBE6 : 'Tribe 6',
    7 => defined('TZ_STAT_TRIBE_ARABS') ? TZ_STAT_TRIBE_ARABS : (defined('TRIBE7') ? TRIBE7 : 'Tribe 7'),
    8 => defined('TRIBE8') ? TRIBE8 : 'Tribe 8',
    9 => defined('TRIBE9') ? TRIBE9 : 'Tribe 9',
];

$tribeStatPages = [
    1 => 11, 2 => 12, 3 => 13, 6 => 16, 7 => 17, 8 => 18, 9 => 19,
];
$titleTimes = defined('TZ_STAT_WORLD_TIMES') ? TZ_STAT_WORLD_TIMES : 'مواعيد العالم';
$titleInfo = defined('TZ_STAT_WORLD_INFO') ? TZ_STAT_WORLD_INFO : 'معلومات العالم';
$titleTribes = defined('TZ_TRIBES') ? TZ_TRIBES : 'القبائل';
?>
<div class="gk-sta-general-wrap">
<table class="b4 fl gk-sta-gen gk-sta-gen-times" dir="rtl">
<colgroup>
    <col class="gk-sta-gen-col-lab">
    <col class="gk-sta-gen-col-time">
</colgroup>
<tbody>
<tr class="gk-sta-head"><th colspan="2"><?php echo htmlspecialchars($titleTimes, ENT_QUOTES, 'UTF-8'); ?></th></tr>
<tr>
    <th class="gk-sta-gen-lab"><?php echo defined('TZ_STAT_WORLD_STARTED') ? TZ_STAT_WORLD_STARTED : 'بدأ العالم منذ'; ?></th>
    <th class="gk-sta-gen-val gk-sta-gen-time" id="gkStaGenWorldAge"><?php echo $fmtClockHtml($worldAgeSec); ?></th>
</tr>
<tr>
    <th class="gk-sta-gen-lab"><?php echo defined('TZ_STAT_ARTIFACTS_APPEAR') ? TZ_STAT_ARTIFACTS_APPEAR : 'ظهور التحف'; ?></th>
    <th class="gk-sta-gen-val gk-sta-gen-time" id="gkStaGenArtifacts"
        data-spawned="<?php echo $artifactsSpawned ? '1' : '0'; ?>"><?php
        echo $fmtEventHtml($artifactSec, $artifactLabel);
    ?></th>
</tr>
<tr>
    <th class="gk-sta-gen-lab"><?php echo defined('TZ_STAT_NATARS_APPEAR') ? TZ_STAT_NATARS_APPEAR : 'ظهور التتار'; ?></th>
    <th class="gk-sta-gen-val gk-sta-gen-time" id="gkStaGenNatars"
        data-spawned="<?php echo $natarsSpawned ? '1' : '0'; ?>"><?php
        echo $fmtEventHtml($natarsSec, $natarsLabel);
    ?></th>
</tr>
</tbody>
</table>

<table class="b4 fl gk-sta-gen gk-sta-gen-info" dir="rtl">
<colgroup>
    <col class="gk-sta-gen-col-lab">
    <col class="gk-sta-gen-col-num">
    <col class="gk-sta-gen-col-lab">
    <col class="gk-sta-gen-col-num">
</colgroup>
<tbody>
<tr class="gk-sta-head"><th colspan="4"><?php echo htmlspecialchars($titleInfo, ENT_QUOTES, 'UTF-8'); ?></th></tr>
<tr>
    <th class="gk-sta-gen-lab"><?php echo defined('TZ_STAT_GAME_SPEED') ? TZ_STAT_GAME_SPEED : 'سرعة اللعبة'; ?></th>
    <th class="gk-sta-gen-num"><?php echo $gameSpeed; ?></th>
    <th class="gk-sta-gen-lab"><?php echo defined('TZ_STAT_PLAYER_COUNT') ? TZ_STAT_PLAYER_COUNT : 'عدد اللاعبين'; ?></th>
    <th class="gk-sta-gen-num"><bdi dir="ltr"><?php echo number_format($playerCount); ?></bdi></th>
</tr>
<tr>
    <th class="gk-sta-gen-lab"><?php echo defined('TZ_STAT_TROOP_SPEED') ? TZ_STAT_TROOP_SPEED : 'سرعة الجيش'; ?></th>
    <th class="gk-sta-gen-num"><?php echo $troopSpeed; ?></th>
    <th class="gk-sta-gen-lab"><?php echo defined('TZ_STAT_ONLINE_NOW') ? TZ_STAT_ONLINE_NOW : 'المتواجدون الآن'; ?></th>
    <th class="gk-sta-gen-num"><bdi dir="ltr"><?php echo number_format($onlineCount); ?></bdi></th>
</tr>
<tr>
    <th class="gk-sta-gen-lab"><?php echo defined('TZ_STAT_PROD_SPEED') ? TZ_STAT_PROD_SPEED : 'سرعة الإنتاج'; ?></th>
    <th class="gk-sta-gen-num"><?php echo $prodSpeed; ?></th>
    <th class="gk-sta-gen-lab"><?php echo defined('TZ_STAT_NATARS_SPAWN_DAY') ? TZ_STAT_NATARS_SPAWN_DAY : 'وقت ظهور التتار منذ بداية العالم باليوم'; ?></th>
    <th class="gk-sta-gen-num"><bdi dir="ltr"><?php echo $natarsSpawnDay; ?></bdi></th>
</tr>
<tr>
    <th class="gk-sta-gen-lab"><?php echo defined('TZ_STAT_STORAGE_SIZE') ? TZ_STAT_STORAGE_SIZE : 'حجم المخازن'; ?></th>
    <th class="gk-sta-gen-num"><?php echo $storageSize; ?></th>
    <th class="gk-sta-gen-lab"><?php echo defined('TZ_STAT_BEGINNER_PROT_H') ? TZ_STAT_BEGINNER_PROT_H : 'حماية المبتدئين بالساعة'; ?></th>
    <th class="gk-sta-gen-num"><bdi dir="ltr"><?php echo $protHours; ?></bdi></th>
</tr>
</tbody>
</table>

<table class="a b0 gk-sta-gen gk-sta-gen-tribes" dir="rtl">
<colgroup>
    <col class="gk-sta-gen-col-tribe">
    <col class="gk-sta-gen-col-players">
    <col class="gk-sta-gen-col-pct">
</colgroup>
<tbody>
<tr class="gk-sta-head"><th colspan="3"><?php echo htmlspecialchars($titleTribes, ENT_QUOTES, 'UTF-8'); ?></th></tr>
<tr class="gk-sta-cols">
    <th><?php echo defined('TRIBE') ? TRIBE : 'القبيلة'; ?></th>
    <th><?php echo defined('TZ_STAT_TRIBE_PLAYERS') ? TZ_STAT_TRIBE_PLAYERS : 'اللاعبون'; ?></th>
    <th><?php echo defined('TZ_STAT_TRIBE_PERCENT') ? TZ_STAT_TRIBE_PERCENT : 'النسبة'; ?></th>
</tr>
<?php
foreach ($tribeDisplayOrder as $tid) {
    $cnt = (int) ($tribes[$tid] ?? 0);
    $pct = ($playerCount > 0) ? round(100 * $cnt / $playerCount, 2) : 0;
    $name = $tribeNames[$tid] ?? ('Tribe ' . $tid);
    $statId = $tribeStatPages[$tid] ?? null;
    echo '<tr>';
    echo '<th class="gk-sta-gen-tribe">';
    if ($statId !== null) {
        echo '<a href="statistiken.php?id=' . (int) $statId . '">'
            . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</a>';
    } else {
        echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    }
    echo '</th>';
    echo '<th class="gk-sta-gen-num"><bdi dir="ltr">' . number_format($cnt) . '</bdi></th>';
    echo '<th class="gk-sta-gen-num"><bdi dir="ltr">%' . number_format($pct, 2) . '</bdi></th>';
    echo '</tr>';
}
?>
</tbody>
</table>
</div>

<div id="gkStaGenMeta" class="gk-sta-gen-meta"
     data-start="<?php echo (int) $startTs; ?>"
     data-artifact-spawn="<?php echo (int) $artifactSpawnAt; ?>"
     data-natars-spawn="<?php echo (int) $natarsSpawnAt; ?>"
     data-server-now="<?php echo (int) $serverNow; ?>"
     data-hours-suffix="<?php echo htmlspecialchars($hoursSuffix, ENT_QUOTES, 'UTF-8'); ?>"
     data-since="<?php echo htmlspecialchars(defined('TZ_STAT_SINCE_PREFIX') ? TZ_STAT_SINCE_PREFIX : 'منذ', ENT_QUOTES, 'UTF-8'); ?>"
     data-left="<?php echo htmlspecialchars(defined('TZ_STAT_LEFT_PREFIX') ? TZ_STAT_LEFT_PREFIX : 'بقي', ENT_QUOTES, 'UTF-8'); ?>"
     data-artifact-spawned="<?php echo $artifactsSpawned ? '1' : '0'; ?>"
     data-natars-spawned="<?php echo $natarsSpawned ? '1' : '0'; ?>"></div>
<script type="text/javascript">
(function () {
    var meta = document.getElementById('gkStaGenMeta');
    if (!meta) return;

    var startTs = parseInt(meta.getAttribute('data-start'), 10);
    var artifactSpawnAt = parseInt(meta.getAttribute('data-artifact-spawn'), 10);
    var natarsSpawnAt = parseInt(meta.getAttribute('data-natars-spawn'), 10);
    var serverNow = parseInt(meta.getAttribute('data-server-now'), 10);
    var hoursSuffix = meta.getAttribute('data-hours-suffix') || '';
    var sincePrefix = meta.getAttribute('data-since') || '';
    var leftPrefix = meta.getAttribute('data-left') || '';
    var artifactSpawned = meta.getAttribute('data-artifact-spawned') === '1';
    var natarsSpawned = meta.getAttribute('data-natars-spawned') === '1';
    var clientStart = Math.floor(Date.now() / 1000);

    var worldEl = document.getElementById('gkStaGenWorldAge');
    var artEl = document.getElementById('gkStaGenArtifacts');
    var natEl = document.getElementById('gkStaGenNatars');

    function formatClock(sec) {
        sec = Math.max(0, sec | 0);
        var h = Math.floor(sec / 3600);
        var m = Math.floor((sec % 3600) / 60);
        var s = sec % 60;
        return h + ':' + (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    }

    function formatWorldAge(sec) {
        return '<bdi dir="ltr">' + formatClock(sec) + '</bdi> ' + hoursSuffix;
    }

    function formatEvent(sec, prefix) {
        return prefix + ' <bdi dir="ltr">' + formatClock(sec) + '</bdi> ' + hoursSuffix;
    }

    function tick() {
        var now = serverNow + Math.floor(Date.now() / 1000) - clientStart;
        if (worldEl) {
            worldEl.innerHTML = formatWorldAge(Math.max(0, now - startTs));
        }

        if (!artifactSpawned && now >= artifactSpawnAt) {
            artifactSpawned = true;
            meta.setAttribute('data-artifact-spawned', '1');
        }
        if (!natarsSpawned && now >= natarsSpawnAt) {
            natarsSpawned = true;
            meta.setAttribute('data-natars-spawned', '1');
        }

        if (artEl) {
            var artSec = artifactSpawned
                ? Math.max(0, now - artifactSpawnAt)
                : Math.max(0, artifactSpawnAt - now);
            var artPrefix = artifactSpawned ? sincePrefix : leftPrefix;
            artEl.innerHTML = formatEvent(artSec, artPrefix);
        }

        if (natEl) {
            var natSec = natarsSpawned
                ? Math.max(0, now - natarsSpawnAt)
                : Math.max(0, natarsSpawnAt - now);
            var natPrefix = natarsSpawned ? sincePrefix : leftPrefix;
            natEl.innerHTML = formatEvent(natSec, natPrefix);
        }

        window.setTimeout(tick, 1000);
    }

    tick();
}());
</script>
