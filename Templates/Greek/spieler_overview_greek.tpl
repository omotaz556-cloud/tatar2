<?php
/**
 * Greek.sa player profile overview (spieler.php?uid=…).
 */

$uid = isset($_GET['uid']) ? (int) $_GET['uid'] : (int) $session->uid;
$ranking->procRankReq($_GET);
$_GET['uid'] = $uid;

$displayarray = $database->getUserArray($uid, 1);
if (!is_array($displayarray) || !isset($displayarray['id'])) {
    return;
}
$varmedal = $database->getProfileMedal($uid);

$profileSeparator = md5('skJkev3');
$input = htmlspecialchars($displayarray['desc1'] ?? '', ENT_QUOTES, 'UTF-8')
    . $profileSeparator
    . htmlspecialchars($displayarray['desc2'] ?? '', ENT_QUOTES, 'UTF-8');
include __DIR__ . '/../../GameEngine/BBCode.php';
$profiel = $bbcoded;
$user = $displayarray;
require __DIR__ . '/../Profile/medal.php';
$profiel = explode($profileSeparator, $profiel);
if (!isset($profiel[0])) {
    $profiel[0] = '';
}
if (!isset($profiel[1])) {
    $profiel[1] = '';
}

$varray = $database->getProfileVillages($uid);
$totalpop = 0;
foreach ($varray as $vil) {
    $totalpop += (int) $vil['pop'];
}

$viewingSelf = ((int) $uid === (int) $session->uid);
$fromWref = isset($_SESSION['wid']) ? (int) $_SESSION['wid'] : 0;
$fromCoor = $fromWref ? $database->getCoor($fromWref) : array('x' => 0, 'y' => 0);

$tribeArrays = array(TRIBE1, TRIBE2, TRIBE3, TRIBE4, TRIBE5, TRIBE6, TRIBE7, TRIBE8, TRIBE9);
$tribeIndex = (int) ($displayarray['tribe'] ?? 0) - 1;
$tribeLabel = isset($tribeArrays[$tribeIndex]) ? $tribeArrays[$tribeIndex] : '-';
$tribeNum = (int) ($displayarray['tribe'] ?? 1);
if ($tribeNum < 1) {
    $tribeNum = 1;
}
$gkTroopUnitClass = 'u' . (($tribeNum - 1) * 10 + 1);

$gkProfResCol = defined('TZ_PROF_RESOURCES_COL') ? TZ_PROF_RESOURCES_COL : 'موارد';
$gkProfTroopCol = defined('TZ_PROF_TROOPS_COL') ? TZ_PROF_TROOPS_COL : 'قوات';
$gkProfDistCol = defined('TZ_PROF_DISTANCE_COL') ? TZ_PROF_DISTANCE_COL : 'مسافة';
$gkProfEditLink = defined('TZ_PROF_EDIT_INFO') ? TZ_PROF_EDIT_INFO : 'تعديل معلوماتك الشخصية';
$gkProfVilNameCol = defined('VILLAGE_NAME') ? VILLAGE_NAME : 'اسم القرية';
$gkProfPopCol = defined('TZ_PROF_POP_COL') ? TZ_PROF_POP_COL : 'سكان';

$gkGenderLabel = '-';
if (!empty($displayarray['gender'])) {
    $gkGenderVal = (int) $displayarray['gender'];
    if ($gkGenderVal === 1) {
        $gkGenderLabel = defined('MALE') ? MALE : 'ذكر';
    } elseif ($gkGenderVal === 2) {
        $gkGenderLabel = defined('FEMALE') ? FEMALE : 'أنثى';
    } else {
        $gkGenderLabel = defined('GENDER0') ? GENDER0 : 'n/a';
    }
}

$gkRankDisp = $ranking->getUserRank($displayarray['id']);
$gkVilCountDisp = count($varray);
$gkPopDisp = $totalpop;

$gkNum = static function ($value) {
    return '<bdi dir="ltr" class="gk-num">' . htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') . '</bdi>';
};
?>

<table cellpadding="1" cellspacing="1" id="profile" class="gk-prof-overview" dir="ltr">
<colgroup>
    <col class="gk-prof-col-desc" />
    <col class="gk-prof-col-detail" />
</colgroup>
<thead>
<tr class="gk-prof-user">
    <th colspan="2"><?php echo htmlspecialchars($displayarray['username'], ENT_QUOTES, 'UTF-8'); ?></th>
</tr>
<?php
if ($displayarray['access'] == ADMIN) {
    echo '<tr class="gk-prof-flag"><th colspan="2">' . PROFILE_FLAG_ADMIN . '</th></tr>';
}
if ($displayarray['access'] == MULTIHUNTER) {
    echo '<tr class="gk-prof-flag"><th colspan="2">' . PROFILE_FLAG_MULTIHUNTER . '</th></tr>';
}
if ($displayarray['access'] == BANNED) {
    echo '<tr class="gk-prof-flag"><th colspan="2">' . PROFILE_FLAG_BANNED . '</th></tr>';
}
if ($displayarray['vac_mode'] == 1) {
    echo '<tr class="gk-prof-flag"><th colspan="2">' . PROFILE_FLAG_VACATION . '</th></tr>';
}
?>
<tr class="gk-prof-cols">
    <th class="gk-prof-h-desc" id="gk-prof-desc"><?php echo DESCRIPTION; ?></th>
    <th class="gk-prof-h-detail"><?php echo DETAIL; ?></th>
</tr>
</thead>
<tbody>
<tr class="gk-prof-main-row">
<td class="desc1 gk-prof-desc-cell">
<div class="desc1div messages gk-prof-desc1"><?php echo nl2br($profiel[1]); ?></div>
</td>
<td class="details gk-prof-detail-cell">
<table cellpadding="0" cellspacing="0" class="gk-prof-detail-table" dir="rtl">
<tr><th><?php echo RANK; ?></th><td class="gk-val-num"><?php echo $gkNum($gkRankDisp); ?></td></tr>
<tr><th><?php echo GENDER; ?></th><td><?php echo htmlspecialchars($gkGenderLabel, ENT_QUOTES, 'UTF-8'); ?></td></tr>
<tr><th><?php echo TRIBE; ?></th><td><?php echo htmlspecialchars($tribeLabel, ENT_QUOTES, 'UTF-8'); ?></td></tr>
<tr><th><?php echo VILLAGES; ?></th><td class="gk-val-num"><?php echo $gkNum($gkVilCountDisp); ?></td></tr>
<tr><th><?php echo ALLIANCE; ?></th><td><?php
if ($displayarray['alliance'] == 0) {
    echo '&nbsp;';
} else {
    $displayalliance = $database->getAllianceName($displayarray['alliance']);
    echo '<a href="allianz.php?aid=' . (int) $displayarray['alliance'] . '">'
        . htmlspecialchars($displayalliance, ENT_QUOTES, 'UTF-8') . '</a>';
}
?></td></tr>
<tr><th><?php echo POP; ?></th><td class="gk-val-num"><?php echo $gkNum($gkPopDisp); ?></td></tr>
<?php if (!empty($displayarray['location'])) { ?>
<tr><th><?php echo LOCATION; ?></th><td><?php echo htmlspecialchars($displayarray['location'], ENT_QUOTES, 'UTF-8'); ?></td></tr>
<?php } ?>
<tr><td colspan="2" class="gk-prof-spacer"></td></tr>
<tr><td colspan="2" class="gk-prof-edit-cell"><?php
if ($viewingSelf && $session->sit == 0) {
    echo '<a href="spieler.php?s=1">' . htmlspecialchars($gkProfEditLink, ENT_QUOTES, 'UTF-8') . '</a>';
} elseif (!$viewingSelf) {
    $isNatar = (isset($displayarray['tribe']) && $displayarray['tribe'] == 5);
    $isNature = ($uid == 2);
    if (!$isNatar && !$isNature) {
        echo '<a href="nachrichten.php?t=1&amp;id=' . (int) $uid . '">' . WRITE_MESSAGE . '</a>';
    }
}
?></td></tr>
<?php if (trim($profiel[0]) !== '') { ?>
<tr><td colspan="2" class="desc2 gk-prof-desc2-cell"><div class="gk-prof-desc2-inline messages"><?php echo nl2br($profiel[0]); ?></div></td></tr>
<?php } ?>
</table>
</td>
</tr>
</tbody>
</table>

<table cellpadding="1" cellspacing="1" id="villages" class="gk-prof-villages" dir="rtl">
<colgroup>
    <col class="gk-prof-col-nam" />
    <col class="gk-prof-col-res" />
    <col class="gk-prof-col-troops" />
    <col class="gk-prof-col-hab" />
    <col class="gk-prof-col-coords" />
    <col class="gk-prof-col-dist" />
</colgroup>
<thead>
<tr class="gk-prof-vil-title"><th colspan="6"><?php echo VILLAGES; ?></th></tr>
<tr class="gk-prof-vil-cols">
    <th class="gk-prof-col-nam"><?php echo $gkProfVilNameCol; ?></th>
    <th class="gk-prof-col-res"><?php echo $gkProfResCol; ?></th>
    <th class="gk-prof-col-troops"><?php echo $gkProfTroopCol; ?></th>
    <th class="gk-prof-col-hab"><?php echo $gkProfPopCol; ?></th>
    <th class="gk-prof-col-coords"><?php echo COORDINATES; ?></th>
    <th class="gk-prof-col-dist"><?php echo $gkProfDistCol; ?></th>
</tr>
</thead>
<tbody>
<?php
foreach ($varray as $vil) {
    $coor = $database->getCoor($vil['wref']);
    $coorX = isset($coor['x']) ? (int) $coor['x'] : 0;
    $coorY = isset($coor['y']) ? (int) $coor['y'] : 0;
    $displayVname = function_exists('tz_display_village_name')
        ? tz_display_village_name($vil['name'], $displayarray['username'] ?? null)
        : $vil['name'];

    $dist = 0;
    if ($fromWref) {
        $dist = $database->getDistance($fromCoor['x'], $fromCoor['y'], $coorX, $coorY);
    }

    $troopIcon = '';
    if ($viewingSelf) {
        $units = $database->getUnit($vil['wref'], false);
        $gkTroopSum = 0;
        if (is_array($units)) {
            for ($u = 1; $u <= 50; $u++) {
                $gkTroopSum += (int) ($units['u' . $u] ?? 0);
            }
        }
        $troopTitle = TROOPS . ($gkTroopSum > 0 ? ' (' . $gkTroopSum . ')' : '');
        $troopIcon = '<a href="build.php?gid=16&amp;newdid=' . (int) $vil['wref'] . '" title="'
            . htmlspecialchars($troopTitle, ENT_QUOTES, 'UTF-8') . '">'
            . '<img class="unit ' . htmlspecialchars($gkTroopUnitClass, ENT_QUOTES, 'UTF-8')
            . ' gk-prof-troop-ico" src="img/x.gif" alt="" /></a>';
    }

    $gkResIcon = '';
    if ($viewingSelf) {
        $gkResTitle = defined('RESOURCES') ? RESOURCES : 'موارد';
        $gkResIcon = '<a href="dorf3.php?newdid=' . (int) $vil['wref'] . '" title="'
            . htmlspecialchars($gkResTitle, ENT_QUOTES, 'UTF-8') . '">'
            . '<img class="carry gk-prof-res-ico" src="img/x.gif" alt="" /></a>';
    }

    echo '<tr>';
    echo '<td class="nam"><a href="karte.php?d=' . (int) $vil['wref'] . '&amp;c=' . $generator->getMapCheck($vil['wref']) . '">'
        . htmlspecialchars($displayVname, ENT_QUOTES, 'UTF-8') . '</a>';
    if ($vil['capital'] == 1) {
        echo '<span class="gk-prof-cap"> (' . CAPITAL_TAG . ')</span>';
    }
    echo '</td>';
    echo '<td class="gk-prof-res">' . $gkResIcon . '</td>';
    echo '<td class="gk-prof-troops">' . $troopIcon . '</td>';
    echo '<td class="hab gk-val-num">' . $gkNum((int) $vil['pop']) . '</td>';
    $coordStr = '(' . $coorX . ',' . $coorY . ')';
    echo '<td class="aligned_coords gk-prof-coords"><a href="karte.php?d=' . (int) $vil['wref'] . '&amp;c=' . $generator->getMapCheck($vil['wref']) . '">'
        . $gkNum($coordStr) . '</a></td>';
    echo '<td class="gk-prof-dist gk-val-num">' . $gkNum($dist) . '</td>';
    echo '</tr>';
}
?>
</tbody>
</table>
