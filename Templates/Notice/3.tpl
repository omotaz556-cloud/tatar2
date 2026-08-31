<?php

#################################################################################
##                -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-               ##
## --------------------------------------------------------------------------- ##
##  Filename       : 3.tpl                                                     ##
##  Type           : Battle Report - No Troops Returned                        ##
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

// ======================== CONFIG ========================
$hasHero = (isset($dataarray[284]) && $dataarray[284] > 0);
$colspan  = $hasHero ? 11 : 10;
$colspan2 = 10;

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

// ======================== ATTACKER (CACHED DB CALLS) ========================
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

// FROM VILLAGE
$fromVillage = $database->getVillageField($dataarray[1], 'name');

if ($fromVillage != "[?]") {
    $from_url = "<a href=\"".$mapUrl.$dataarray[1]."&c=".$generator->getMapCheck($dataarray[1])."\">".htmlspecialchars($fromVillage, ENT_QUOTES, 'UTF-8')."</a>";
} else {
    $from_url = "<font color=\"grey\"><b>[?]</b></font>";
}

// ======================== DEFENDER (CACHED) ========================
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

// DEF VILLAGE / OASIS HANDLING
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

?>
<?php include __DIR__ . '/gk_rpt_head.inc.tpl'; ?>

<tbody>
<tr><td colspan="<?php echo (int) ($gkRptSurroundCols ?? 2); ?>" class="empty"></td></tr>
<tr><td colspan="<?php echo (int) ($gkRptSurroundCols ?? 2); ?>" class="report_content">

<!-- ======================== ATTACKER ======================== -->
<table cellpadding="1" cellspacing="1" id="attacker">
<thead>
<tr>
<td class="role"><?php echo $gkRptGreek && defined('TZ_RPT_ATTACKER_SHORT') ? TZ_RPT_ATTACKER_SHORT : ATTACKER; ?></td>
<td colspan="<?php echo $colspan; ?>">
    <?php
    echo function_exists('tz_rpt_from_village_line')
        ? tz_rpt_from_village_line($user_url, $from_url, $fromVillage)
        : ($user_url . ' ' . FROM_THE_VILL . ' ' . $from_url);
    ?>
</td>
</tr>
</thead>

<tbody class="units">
<tr>
<th><?php echo ($gkRptGreek && defined('TZ_RPT_TYPES')) ? TZ_RPT_TYPES : '&nbsp;'; ?></th>

<?php
// UNIT DISPLAY (attacker)
$tribe = $dataarray[2];
$start = ($tribe - 1) * 10 + 1;

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
    echo "<td>".$dataarray[284]."</td>";
}

// CASUALTIES
echo "</tr><tr><th>".CASUALTIES."</th>";

for ($i = 13; $i <= 22; $i++) {
    echo ($dataarray[$i] == 0)
        ? "<td class=\"none\">0</td>"
        : "<td>".$dataarray[$i]."</td>";
}

if ($hasHero) {
    $tdclass = ($dataarray[285] == 0) ? 'class="none"' : '';
    echo "<td $tdclass>".$dataarray[285]."</td>";
}

// PRISONERS
if (array_sum(array_slice($dataarray, 286, 11)) > 0) {

    echo "</tr><tr><th>".PRISONERS."</th>";

    for ($i = 286; $i <= 295; $i++) {
        echo ($dataarray[$i] == 0)
            ? "<td class=\"none\">0</td>"
            : "<td>".$dataarray[$i]."</td>";
    }

    if ($hasHero) {
        $tdclass = ($dataarray[296] == 0) ? 'class="none"' : '';
        echo "<td $tdclass>".$dataarray[296]."</td>";
    }
}
?>

</tr>
</tbody>

<?php
// ======================== SPECIAL ACTIONS ========================

if (!empty($dataarray[298]) && !empty($dataarray[299])) {
?>
<tbody class="goods">
<tr>
<th><?php echo INFORMATION; ?></th>
<td colspan="<?php echo $colspan; ?>">
<img class="unit u<?php echo $dataarray[298]; ?>" src="img/x.gif" alt="<?php echo U17; ?>" title="<?php echo U17; ?>" />
<?php echo $dataarray[299]; ?>
</td>
</tr>
</tbody>
<?php }

if (!empty($dataarray[300]) && !empty($dataarray[301])) {
?>
<tbody class="goods">
<tr>
<th><?php echo INFORMATION; ?></th>
<td colspan="<?php echo $colspan; ?>">
<img class="unit u<?php echo $dataarray[300]; ?>" src="img/x.gif" alt="<?php echo U18; ?>" title="<?php echo U18; ?>" />
<?php echo $dataarray[301]; ?>
</td>
</tr>
</tbody>
<?php }

if (!empty($dataarray[302]) && !empty($dataarray[303])) {
?>
<tbody class="goods">
<tr>
<th><?php echo INFORMATION; ?></th>
<td colspan="<?php echo $colspan; ?>">
<img class="unit u<?php echo $dataarray[302]; ?>" src="img/x.gif" alt="<?php echo U19; ?>" title="<?php echo U19; ?>" />
<?php echo $dataarray[303]; ?>
</td>
</tr>
</tbody>
<?php }

if (!empty($dataarray[305]) && !empty($dataarray[306])) {
?>
<tbody class="goods">
<tr>
<th><?php echo INFORMATION; ?></th>
<td colspan="<?php echo $colspan; ?>">
<img class="unit u<?php echo $dataarray[305]; ?>" src="img/x.gif" alt="<?php echo U0; ?>" title="<?php echo U0; ?>" />
<?php echo $dataarray[306]; ?>
</td>
</tr>
</tbody>
<?php }

if (!empty($dataarray[304])) {
?>
<tbody class="goods">
<tr>
<th><?php echo INFORMATION; ?></th>
<td colspan="<?php echo $colspan; ?>">
<?php echo tz_expand_report($dataarray[304]); ?>
</td>
</tr>
</tbody>
<?php }
?>

</table>

<!-- ======================== DEFENDER ======================== -->
<?php
$targetTribe = (int) $dataarray[34];
$defUnitStart = ($targetTribe - 1) * 10 + 1;
$troopsStart = 35;
$defHasHero = !empty($dataarray[272]);
$defColspan = $defHasHero ? 11 : 10;
?>

<table cellpadding="1" cellspacing="1" id="defender" class="defender">
<thead>
<tr>
<td class="role"><?php echo $gkRptGreek && defined('TZ_RPT_DEFENDER_SHORT') ? TZ_RPT_DEFENDER_SHORT : DEFENDER; ?></td>
<td colspan="<?php echo $defColspan; ?>">
    <?php
    echo function_exists('tz_rpt_from_village_line')
        ? tz_rpt_from_village_line($defuser_url, $deffrom_url, $database->isVillageOases($dataarray[29]) ? ($dataarray[30] ?? '') : $defVillageName)
        : ($defuser_url . ' ' . FROM_THE_VILL . ' ' . $deffrom_url);
    ?>
</td>
</tr>
</thead>

<tbody class="units">
<tr>
<th><?php echo ($gkRptGreek && defined('TZ_RPT_TYPES')) ? TZ_RPT_TYPES : '&nbsp;'; ?></th>

<?php
for ($i = $defUnitStart; $i <= ($defUnitStart + 9); $i++) {
    $unitName = $technology->getUnitName($i);
    echo "<td><img src=\"img/x.gif\" class=\"unit u$i\" title=\"$unitName\" alt=\"$unitName\" /></td>";
}

if ($defHasHero) {
    echo '<td><img src="img/x.gif" class="unit uhero" /></td>';
}

echo '</tr><tr><th>' . TROOPS . '</th>';

for ($i = $troopsStart; $i <= $troopsStart + 9; $i++) {
    echo tz_rpt_battle_cell($dataarray[$i] ?? '?');
}

if ($defHasHero) {
    echo '<td>' . (int) ($dataarray[272] ?? 0) . '</td>';
}

echo '</tr><tr><th>' . CASUALTIES . '</th>';

for ($i = $troopsStart + 10; $i <= $troopsStart + 19; $i++) {
    echo tz_rpt_battle_cell($dataarray[$i] ?? '?');
}

if ($defHasHero) {
    $heroDead = (int) ($dataarray[273] ?? 0);
    $tdclass1 = $heroDead === 0 ? 'class="none"' : '';
    echo '<td ' . $tdclass1 . '>' . $heroDead . '</td>';
}
?>

</tr>
</tbody>
<?php
$defAllMasked = function_exists('tz_rpt_defender_cells_masked')
    ? tz_rpt_defender_cells_masked($dataarray, $troopsStart)
    : true;
$gkRptNatureLegacyHint = function_exists('tz_rpt_casualties_masked')
    && tz_rpt_casualties_masked($dataarray, $troopsStart)
    && ((int) ($dataarray[34] ?? 0) === 4 || (int) ($dataarray[28] ?? 0) === 2);
if ($gkRptNatureLegacyHint) {
?>
<tbody class="goods">
<tr>
<th><?php echo INFORMATION; ?></th>
<td colspan="<?php echo $defColspan; ?>"><?php echo TZ_RPT_NATURE_LEGACY_HINT; ?></td>
</tr>
</tbody>
<?php } elseif ($defAllMasked) {
?>
<tbody class="goods">
<tr>
<th><?php echo INFORMATION; ?></th>
<td colspan="<?php echo $defColspan; ?>"><?php echo defined('TZ_RPT_NO_DEF_INTEL') ? TZ_RPT_NO_DEF_INTEL : 'No defender data.'; ?></td>
</tr>
</tbody>
<?php } ?>
</table>

</td></tr></tbody></table>
<?php include __DIR__ . '/gk_rpt_foot.inc.tpl'; ?>