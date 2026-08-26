<?php
/**
 * ==========================================================
 * Novaterra Newsbox2 - SAFE REFACTOR
 * ==========================================================
 * - păstrează logica originală
 * - elimină calcule repetitive
 * - structură mai clară
 * - compatibil PHP 5+ / 7+
 * ==========================================================
 */

// ======================================================
// STATIC ARRAYS (nemodificate logic)
// ======================================================
$textArray = array(
    array("Natars Spawn", "WW Spawn", "WW Plan Spawn"),
    array("Natars Tribe", "WW Village", "Construction Plan")
);

$spawnTimeArray = array(
    NATARS_SPAWN_TIME,
    NATARS_WW_SPAWN_TIME,
    NATARS_WW_BUILDING_PLAN_SPAWN_TIME
);

// ======================================================
// SPAWN STATES (apeluri DB păstrate)
// ======================================================
$areSpawned = array(
    $database->areArtifactsSpawned(),
    $database->areWWVillagesSpawned(),
    $database->areArtifactsSpawned(true)
);

// ======================================================
// PRECALCULARE TIMP (evităm strtotime în loop)
// ======================================================
$serverStart = function_exists('tz_natars_timer_base')
	? tz_natars_timer_base()
	: strtotime(START_DATE);

// lungime array (evităm count() repetat)
$total = count($spawnTimeArray);
?>

<h5><img src="img/en/t2/newsbox2.gif" alt="<?php echo EDIT_NEWSBOX2; ?>"></h5>

<div class="news">
<table width="100%">

<?php for ($i = 0; $i < $total; $i++) { ?>

<tr>
<td>
<b>
<?php
// ======================================================
// TEXT STATUS (identic logic)
// ======================================================
if (!empty($areSpawned[$i])) {
    echo $textArray[1][$i];
} else {
    echo $textArray[0][$i];
}
?>
</b>
</td>

<td>
<b>: <font color="Red">
<?php
// ======================================================
// DATA / STATUS (identic logic)
// ======================================================
if (!empty($areSpawned[$i])) {
    echo "Released";
} else {
    // Prefer shared helper (includes SPEED + admin Natars reset base).
    if (function_exists('tz_natars_spawn_at')) {
        echo date('d.m.Y', tz_natars_spawn_at((int) $spawnTimeArray[$i]));
    } else {
        $interval = $spawnTimeArray[$i] * 86400;
        echo date('d.m.Y', $serverStart + $interval);
    }
}
?>
</font></b>
</td>

</tr>

<?php } ?>

</table>
</div>