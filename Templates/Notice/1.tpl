<?php

#################################################################################
##                -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-               ##
## --------------------------------------------------------------------------- ##
##  Filename       : 1.tpl                                                     ##
##  Type           : Battle Report - Attack / Raid                             ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  URLs:          : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : Novaterra Project                                          ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.              ##
## --------------------------------------------------------------------------- ##
#################################################################################

$dataarray = array_map('tz_expand_report', explode(",", $message->readingNotice['data']));
if (function_exists('tz_rpt_enrich_masked_nature_defender')) {
    tz_rpt_enrich_masked_nature_defender($dataarray, $database, (int) ($message->readingNotice['type'] ?? 0));
}
$gkRptGreek = !empty($GLOBALS['gkBerichteLiteralPage']);

// ======================== BASIC SETTINGS ========================
$hasHero = (isset($dataarray[270]) && $dataarray[270] > 0);
$colspan = $hasHero ? 11 : 10;

// Spy detection (unchanged logic)
$spy = !empty($dataarray[269]) && !empty($dataarray[268]) && empty($dataarray[287]);

// ======================== URL SETUP ========================

// detectare admin REAL (nu variabilă nesigură)
$isAdmin = (isset($session) && isset($session->access) && $session->access >= 8);

if ($isAdmin) {
    $mapUrl = "Admin/admin.php?p=village&did=";
    $playerUrl = "Admin/admin.php?p=player&uid=";
} else {
    $mapUrl = "karte.php?d=";
    $playerUrl = "spieler.php?uid=";
}

// ======================== ATTACKER DATA (CACHED) ========================
$attackerId = $dataarray[0];
$attackerName = $database->getUserField($attackerId, 'username', 0);
$attackerUid  = $database->getUserField($attackerId, 'id', 0);
$attackerDisplay = function_exists('tz_loc_report_player')
    ? tz_loc_report_player($attackerName, $attackerId)
    : $attackerName;

if ($attackerName != "[?]") {
    $user_url = "<a href=\"".$playerUrl.$attackerUid."\">".htmlspecialchars($attackerDisplay, ENT_QUOTES, 'UTF-8')."</a>";
} else {
    $user_url = "<font color=\"grey\"><b>[?]</b></font>";
}

$fromVillage = $database->getVillageField($dataarray[1], 'name');

if ($fromVillage != "[?]") {
    $from_url = "<a href=\"".$mapUrl.$dataarray[1]."&c=".$generator->getMapCheck($dataarray[1])."\">".$fromVillage."</a>";
} else {
    $from_url = "<font color=\"grey\"><b>[?]</b></font>";
}

// ======================== DEFENDER DATA (CACHED) ========================
$defId = $dataarray[28];
$defName = $database->getUserField($defId, 'username', 0);
$defUid  = $database->getUserField($defId, 'id', 0);
$defDisplay = function_exists('tz_loc_report_player')
    ? tz_loc_report_player($defName, $defId)
    : $defName;

if ($defName != "[?]" && (int) $defId !== 2) {
    $defuser_url = "<a href=\"".$playerUrl.$defUid."\">".htmlspecialchars($defDisplay, ENT_QUOTES, 'UTF-8')."</a>";
} else {
    $defuser_url = "<span class=\"gk-rpt-npc\">".htmlspecialchars($defDisplay, ENT_QUOTES, 'UTF-8')."</span>";
}

$defVillageName = $database->getVillageField($dataarray[29], 'name');

if ($database->isVillageOases($dataarray[29])) {
    $oasisLabel = function_exists('tz_loc_report_place')
        ? tz_loc_report_place($dataarray[30], $defVillageName)
        : $dataarray[30];
    $deffrom_url = "<a href=\"".$mapUrl.$dataarray[29]."&c=".$generator->getMapCheck($dataarray[29])."\">"
        . htmlspecialchars($oasisLabel, ENT_QUOTES, 'UTF-8') . "</a>";
} elseif ($defVillageName != "[?]") {
    $placeLabel = function_exists('tz_loc_report_place')
        ? tz_loc_report_place($defVillageName, $defVillageName)
        : $defVillageName;
    $deffrom_url = "<a href=\"".$mapUrl.$dataarray[29]."&c=".$generator->getMapCheck($dataarray[29])."\">"
        . htmlspecialchars($placeLabel, ENT_QUOTES, 'UTF-8') . "</a>";
} else {
    $deffrom_url = "<font color=\"grey\"><b>[?]</b></font>";
}

// ======================== HTML START ========================
?>
<?php include __DIR__ . '/gk_rpt_head.inc.tpl'; ?>

<tbody>
<tr><td colspan="<?php echo (int) ($gkRptSurroundCols ?? 2); ?>" class="empty"></td></tr>
<tr><td colspan="<?php echo (int) ($gkRptSurroundCols ?? 2); ?>" class="report_content">

<table cellpadding="1" cellspacing="1" id="attacker">
<thead>
<tr>
    <td class="role"><?php echo $gkRptGreek && defined('TZ_RPT_ATTACKER_SHORT') ? TZ_RPT_ATTACKER_SHORT : ATTACKER; ?></td>
    <td colspan="<?php echo $colspan ?>">
        <?php
        echo function_exists('tz_rpt_from_village_line')
            ? tz_rpt_from_village_line($user_url, $from_url, $fromVillage)
            : ($user_url . ($from_url ? ' ' . FROM_THE_VILL . ' ' . $from_url : ''));
        ?>
    </td>
</tr>
</thead>

<tbody class="units">
<tr>
<th><?php echo ($gkRptGreek && defined('TZ_RPT_TYPES')) ? TZ_RPT_TYPES : '&nbsp;'; ?></th>

<?php
$tribe = !empty($dataarray[2]) ? $dataarray[2] : 5;
$start = ($tribe - 1) * 10 + 1;

// UNIT ICONS
for ($i = $start; $i <= ($start + 9); $i++) {
    $unitName = $technology->getUnitName($i);
    echo "<td><img src=\"img/x.gif\" class=\"unit u$i\" title=\"$unitName\" alt=\"$unitName\" /></td>";
}

if ($hasHero) {
    echo "<td><img src=\"img/x.gif\" class=\"unit uhero\" title=\"".RC_HERO."\" alt=\"".RC_HERO."\" /></td>";
}

echo "</tr><tr><th>".TROOPS."</th>";

// TROOPS
for ($i = 3; $i <= 12; $i++) {
    echo ($dataarray[$i] == 0)
        ? "<td class=\"none\">0</td>"
        : "<td>".$dataarray[$i]."</td>";
}

if ($hasHero) {
    echo "<td>".$dataarray[270]."</td>";
}

// CASUALTIES
echo "</tr><tr><th>".CASUALTIES."</th>";

for ($i = 13; $i <= 22; $i++) {
    echo ($dataarray[$i] == 0)
        ? "<td class=\"none\">0</td>"
        : "<td>".$dataarray[$i]."</td>";
}

if ($hasHero) {
    $tdclass = ($dataarray[271] == 0) ? 'class="none"' : '';
    echo "<td $tdclass>".$dataarray[271]."</td>";
}

// PRISONERS (unchanged logic but safer sum)
// Cast to int: some reports carry non-numeric text in these slots (e.g. the
// "Information" line shifts indices), which made array_sum() warn under PHP 8.
if (!$spy && array_sum(array_map('intval', array_slice($dataarray, 274, 11))) > 0) {
    echo "</tr><tr><th>".PRISONERS."</th>";

    for ($i = 274; $i <= 283; $i++) {
        echo ($dataarray[$i] == 0)
            ? "<td class=\"none\">0</td>"
            : "<td>".$dataarray[$i]."</td>";
    }

    if ($hasHero) {
        $tdclass = ($dataarray[284] == 0) ? 'class="none"' : '';
        echo "<td $tdclass>".$dataarray[284]."</td>";
    }
}
?>
</tr>
</tbody>

<?php
// ======================== SPECIAL ACTIONS ========================
if (!empty($dataarray[262]) && !empty($dataarray[263])) {
?>
<tbody class="goods">
<tr>
<th><?php echo INFORMATION; ?></th>
<td colspan="<?php echo $colspan; ?>">
<img class="unit u<?php echo $dataarray[262]; ?>" src="img/x.gif" alt="<?php echo U17; ?>" title="<?php echo U17; ?>" />
<?php echo $dataarray[263]; ?>
</td>
</tr>
</tbody>
<?php }

if (!empty($dataarray[264]) && !empty($dataarray[265])) {
?>
<tbody class="goods">
<tr>
<th><?php echo INFORMATION; ?></th>
<td colspan="<?php echo $colspan; ?>">
<img class="unit u<?php echo $dataarray[264]; ?>" src="img/x.gif" alt="<?php echo U18; ?>" title="<?php echo U18; ?>" />
<?php echo $dataarray[265]; ?>
</td>
</tr>
</tbody>
<?php }

if (!empty($dataarray[266]) && !empty($dataarray[267])) {
?>
<tbody class="goods">
<tr>
<th><?php echo INFORMATION; ?></th>
<td colspan="<?php echo $colspan; ?>">
<img class="unit u<?php echo $dataarray[266]; ?>" src="img/x.gif" alt="<?php echo U19; ?>" title="<?php echo U19; ?>" />
<?php echo $dataarray[267]; ?>
</td>
</tr>
</tbody>
<?php }

if ($spy) {
?>
<tbody class="goods">
<tr>
<th><?php echo INFORMATION; ?></th>
<td colspan="<?php echo $colspan; ?>">
<?php echo $dataarray[269]; ?>
</td>
</tr>
</tbody>
<?php }

if (!empty($dataarray[285])) {
?>
<tbody class="goods">
<tr>
<th><?php echo INFORMATION; ?></th>
<td colspan="<?php echo $colspan; ?>">
<?php echo $dataarray[285]; ?>
</td>
</tr>
</tbody>
<?php }

if (!empty($dataarray[288]) && !empty($dataarray[289])) {
?>
<tbody class="goods">
<tr>
<th><?php echo INFORMATION; ?></th>
<td colspan="<?php echo $colspan; ?>">
<img class="unit u<?php echo $dataarray[288]; ?>" src="img/x.gif" alt="<?php echo U0; ?>" title="<?php echo U0; ?>" />
<?php echo $dataarray[289]; ?>
</td>
</tr>
</tbody>
<?php }

if (!empty($dataarray[287])) {
?>
<tbody class="goods">
<tr>
<th><?php echo INFORMATION; ?></th>
<td colspan="<?php echo $colspan; ?>">
<?php echo $dataarray[287]; ?>
</td>
</tr>
</tbody>
<?php } elseif (empty($dataarray[268]) && empty($dataarray[269])) { ?>
<tbody class="goods">
<tr>
<th><?php echo ($gkRptGreek && defined('TZ_RPT_RESOURCES_ROW')) ? TZ_RPT_RESOURCES_ROW : BOUNTY; ?></th>
<td colspan="<?php echo $colspan; ?>">
<div class="res">
<img class="r1" src="img/x.gif" /><?php echo $dataarray[23]; ?> |
<img class="r2" src="img/x.gif" /><?php echo $dataarray[24]; ?> |
<img class="r3" src="img/x.gif" /><?php echo $dataarray[25]; ?> |
<img class="r4" src="img/x.gif" /><?php echo $dataarray[26]; ?>
</div>
<div class="carry">
<?php echo ($dataarray[23]+$dataarray[24]+$dataarray[25]+$dataarray[26])."/".$dataarray[27]; ?>
</div>
</td>
</tr>
</tbody>
</table>
<?php } ?>

<?php
// ======================== DEFENDER LOOP ========================
$defArray = [1, $dataarray[55], $dataarray[76], $dataarray[97], $dataarray[118], $dataarray[139], $dataarray[160], $dataarray[181], $dataarray[202], $dataarray[223]];
$targetTribe = $dataarray[34];

foreach ($defArray as $index => $value) {

    if ($value == 0) continue;

    $heroIndex = ($index == 0 ? 272 : 244 + ($index - 1));
    $heroDeadIndex = ($index == 0 ? 1 : 9);

    $target = ($index == 0 ? $targetTribe : $index) - 1;
    $start = $target * 10 + 1;
    $troopsStart = $index * 21 + 35;
?>
<table cellpadding="1" cellspacing="1" id="defender" class="defender">
<thead>
<tr>
    <td class="role"><?php echo $gkRptGreek && defined('TZ_RPT_DEFENDER_SHORT') ? TZ_RPT_DEFENDER_SHORT : DEFENDER; ?></td>
<td colspan="<?php echo (!empty($dataarray[$heroIndex])) ? 11 : 10; ?>">
<?php echo ($index == 0)
    ? (function_exists('tz_rpt_from_village_line')
        ? tz_rpt_from_village_line($defuser_url, $deffrom_url, $database->isVillageOases($dataarray[29]) ? ($dataarray[30] ?? '') : $defVillageName)
        : ($defuser_url . ' ' . FROM_THE_VILL . ' ' . $deffrom_url))
    : REINFORCEMENT; ?>
</td>
</tr>
</thead>

<tbody class="units">
<tr>
<th><?php echo ($gkRptGreek && defined('TZ_RPT_TYPES')) ? TZ_RPT_TYPES : '&nbsp;'; ?></th>

<?php
for ($i = $start; $i <= ($start + 9); $i++) {
    $unitName = $technology->getUnitName($i);
    echo "<td><img src=\"img/x.gif\" class=\"unit u$i\" title=\"$unitName\" alt=\"$unitName\" /></td>";
}

if (!empty($dataarray[$heroIndex])) {
    echo "<td><img src=\"img/x.gif\" class=\"unit uhero\" /></td>";
}

echo "</tr><tr><th>".TROOPS."</th>";

for ($i = $troopsStart; $i <= $troopsStart + 9; $i++) {
    echo ($dataarray[$i] == 0)
        ? "<td class=\"none\">0</td>"
        : "<td>".$dataarray[$i]."</td>";
}

if (!empty($dataarray[$heroIndex])) {
    echo "<td>".$dataarray[$heroIndex]."</td>";
}

echo "</tr><tr><th>".CASUALTIES."</th>";

for ($i = $troopsStart + 10; $i <= $troopsStart + 19; $i++) {
    echo ($dataarray[$i] == 0)
        ? "<td class=\"none\">0</td>"
        : "<td>".$dataarray[$i]."</td>";
}

// SAFE FIX: avoid undefined variable warning
$tdclass1 = '';

if (!empty($dataarray[$heroIndex])) {
    $tdclass1 = ($dataarray[$heroIndex + $heroDeadIndex] == 0) ? 'class="none"' : '';
    echo "<td $tdclass1>".$dataarray[$heroIndex + $heroDeadIndex]."</td>";
}
?>
</tr>
</tbody>
</table>
<?php } ?>

</td></tr></tbody></table>
<?php include __DIR__ . '/gk_rpt_foot.inc.tpl'; ?>