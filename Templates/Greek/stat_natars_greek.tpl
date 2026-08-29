<?php
/**
 * Greek.sa Natars countdown — matches reference screenshot (stat?S=natars).
 */

global $generator, $database;

$speed = max(1, (float) (defined('SPEED') ? SPEED : 1));
$spawnAt = (int) strtotime(START_DATE) + (int) round((int) NATARS_SPAWN_TIME * 86400 / $speed);
$serverNow = time();
$natarsSpawned = ($serverNow >= $spawnAt);

if ($natarsSpawned) {
    $timerSeconds = max(0, $serverNow - $spawnAt);
    $label = defined('TZ_STAT_NATARS_SINCE') ? TZ_STAT_NATARS_SINCE : 'مضى على نزول التتار';
} else {
    $timerSeconds = max(0, $spawnAt - $serverNow);
    $label = defined('TZ_STAT_NATARS_LEFT') ? TZ_STAT_NATARS_LEFT : 'بقي على نزول التتار';
}

$initialClock = $generator->getTimeFormat($timerSeconds);
$sinceLabel = defined('TZ_STAT_NATARS_SINCE') ? TZ_STAT_NATARS_SINCE : 'مضى على نزول التتار';
?>
<div id="gkStaNatars" class="gk-sta-natars"
     data-spawn-at="<?php echo (int) $spawnAt; ?>"
     data-server-now="<?php echo (int) $serverNow; ?>"
     data-spawned="<?php echo $natarsSpawned ? '1' : '0'; ?>"
     data-label-left="<?php echo htmlspecialchars(
         defined('TZ_STAT_NATARS_LEFT') ? TZ_STAT_NATARS_LEFT : 'بقي على نزول التتار',
         ENT_QUOTES,
         'UTF-8'
     ); ?>"
     data-label-since="<?php echo htmlspecialchars($sinceLabel, ENT_QUOTES, 'UTF-8'); ?>">
    <p id="gkStaNatarsLab" class="gk-sta-natars-lab"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></p>
    <p id="gkStaNatarsClock" class="gk-sta-natars-clock" dir="ltr"><?php echo htmlspecialchars($initialClock, ENT_QUOTES, 'UTF-8'); ?></p>
</div>
<script type="text/javascript">
(function () {
    var box = document.getElementById('gkStaNatars');
    var clock = document.getElementById('gkStaNatarsClock');
    var lab = document.getElementById('gkStaNatarsLab');
    if (!box || !clock) {
        return;
    }

    var spawnAt = parseInt(box.getAttribute('data-spawn-at'), 10);
    var serverNow = parseInt(box.getAttribute('data-server-now'), 10);
    var spawned = box.getAttribute('data-spawned') === '1';
    var labelLeft = box.getAttribute('data-label-left') || '';
    var labelSince = box.getAttribute('data-label-since') || '';
    var clientStart = Math.floor(Date.now() / 1000);

    function formatTime(sec) {
        sec = Math.max(0, sec | 0);
        var h = Math.floor(sec / 3600);
        var m = Math.floor((sec % 3600) / 60);
        var s = sec % 60;
        return h + ':' + (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    }

    function tick() {
        var now = serverNow + Math.floor(Date.now() / 1000) - clientStart;
        var remaining = Math.max(0, spawnAt - now);

        if (!spawned && remaining <= 0) {
            spawned = true;
            box.setAttribute('data-spawned', '1');
            if (lab && labelSince) {
                lab.textContent = labelSince;
            }
        }

        var seconds = spawned ? Math.max(0, now - spawnAt) : remaining;
        clock.textContent = formatTime(seconds);
        window.setTimeout(tick, 1000);
    }

    tick();
}());
</script>
