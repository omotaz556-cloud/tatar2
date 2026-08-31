<?php

/**
 * POST handler for Greek farm add form (shared by farmlist.tpl and addraid flow).
 * Expects: action=addSlot, x, y, unit_pick + troop_amount (or t1–t6).
 */

$farmAddError = $farmAddError ?? null;

if (!isset($_POST['action']) || $_POST['action'] !== 'addSlot') {
    return;
}

$lid = (int) ($_POST['lid'] ?? 0);

if ($lid <= 0) {
    $lidRow = mysqli_query(
        $database->dblink,
        'SELECT id FROM ' . TB_PREFIX . 'farmlist WHERE owner = ' . (int) $session->uid
        . ' AND wref = ' . (int) $village->wid . ' ORDER BY id ASC LIMIT 1'
    );
    if ($lidRow && ($lidData = mysqli_fetch_assoc($lidRow))) {
        $lid = (int) $lidData['id'];
    }
}

if ($lid <= 0) {
    $farmListName = defined('TZ_RALLY_FARMS') ? TZ_RALLY_FARMS : 'Farms';
    $database->createFarmList((int) $village->wid, (int) $session->uid, $farmListName);
    $lid = (int) mysqli_insert_id($database->dblink);
}

if ($lid <= 0) {
    $farmAddError = defined('TZ_FARM_LIST_CREATE_FAIL') ? TZ_FARM_LIST_CREATE_FAIL : 'Could not create farm list.';
    return;
}

if (isset($_POST['unit_pick'], $_POST['troop_amount'])) {
    $unitPick = (int) $_POST['unit_pick'];
    $troopAmount = max(0, (int) $_POST['troop_amount']);
    for ($ti = 1; $ti <= 6; $ti++) {
        $_POST['t' . $ti] = ($ti === $unitPick) ? $troopAmount : 0;
    }
}

$FLData = $database->getFLData($lid);

if (!$FLData || (int) $FLData['owner'] !== (int) $session->uid) {
    $farmAddError = defined('TZ_FARM_LIST_INVALID') ? TZ_FARM_LIST_INVALID : 'Invalid farm list.';
    return;
}

$troops = 0;
$tribeOffset = ($session->tribe - 1) * 10;

for ($ti = 1; $ti <= 6; $ti++) {
    $unitId = $ti + $tribeOffset;
    if (!in_array($unitId, [4, 14, 23, 44, 52, 64, 74, 82], true)) {
        $troops += (int) ($_POST['t' . $ti] ?? 0);
    }
}

$Wref = null;
$WrefX = null;
$WrefY = null;
$vdata = null;
$oasistype = null;

$targetId = $_POST['target_id'] ?? '';
$x = isset($_POST['x']) ? trim((string) $_POST['x']) : '';
$y = isset($_POST['y']) ? trim((string) $_POST['y']) : '';

if (!empty($targetId)) {
    $Wref = (int) $targetId;
    $coor = $database->getCoor($Wref);
    $WrefX = $coor['x'];
    $WrefY = $coor['y'];
} elseif ($x !== '' && $y !== '' && is_numeric($x) && is_numeric($y)
    && (int) $x <= WORLD_MAX && (int) $y <= WORLD_MAX) {
    $Wref = $database->getVilWref($x, $y);
    $WrefX = (int) $x;
    $WrefY = (int) $y;
}

if ($Wref) {
    $oasistype = $database->getVillageType2($Wref);
    $vdata = $database->getVillage($Wref);
}

if ($x === '' && $y === '' && empty($targetId)) {
    $farmAddError = defined('TZ_FARM_ENTER_COORDS') ? TZ_FARM_ENTER_COORDS : 'Enter coordinates.';
} elseif (($x === '' || $y === '') && empty($targetId)) {
    $farmAddError = defined('TZ_FARM_COORDS_INVALID') ? TZ_FARM_COORDS_INVALID : 'Enter valid coordinates.';
} elseif ($oasistype == 0 && !$vdata) {
    $farmAddError = defined('TZ_FARM_NO_VILLAGE') ? TZ_FARM_NO_VILLAGE : 'There is no village on those coordinates.';
} elseif ($troops === 0) {
    $farmAddError = defined('TZ_FARM_NO_TROOPS') ? TZ_FARM_NO_TROOPS : 'No troops selected.';
} elseif ($database->hasBeginnerProtection($Wref)) {
    $farmAddError = defined('TZ_FARM_BEGINNER_PROT') ? TZ_FARM_BEGINNER_PROT : 'Player under protection.';
} elseif ((int) $targetId === (int) $FLData['wref'] || ((int) ($vdata['wref'] ?? 0) === (int) $FLData['wref'])) {
    $farmAddError = defined('TZ_FARM_SAME_VILLAGE') ? TZ_FARM_SAME_VILLAGE : 'You cannot attack the same village you send troops from.';
} else {
    $coor = $database->getCoor($village->wid);
    $distance = $database->getDistance($coor['x'], $coor['y'], $WrefX, $WrefY);

    $database->addSlotFarm(
        $lid,
        $Wref,
        $WrefX,
        $WrefY,
        $distance,
        (int) ($_POST['t1'] ?? 0),
        (int) ($_POST['t2'] ?? 0),
        (int) ($_POST['t3'] ?? 0),
        (int) ($_POST['t4'] ?? 0),
        (int) ($_POST['t5'] ?? 0),
        (int) ($_POST['t6'] ?? 0)
    );

    header('Location: build.php?id=39&t=99');
    exit;
}
