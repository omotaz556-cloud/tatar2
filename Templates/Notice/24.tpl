<?php

#################################################################################
##                -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-               ##
## --------------------------------------------------------------------------- ##
##  Filename       : 24.tpl                                                    ##
##  Type           : Settler Report - New Village / Oasis                      ##
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

$ntype  = (int)($message->readingNotice['ntype'] ?? 24);
$coords = explode(',', (string)($message->readingNotice['data'] ?? ''));
$x      = (int)($coords[0] ?? 0);
$y      = (int)($coords[1] ?? 0);
$wref   = (int)($message->readingNotice['toWref'] ?? 0);
$mapCheck = $generator->getMapCheck($wref);
$coordLink = '<a href="karte.php?d=' . $wref . '&c=' . $mapCheck . '">(' . $x . '|' . $y . ')</a>';
?>

<?php include __DIR__ . '/gk_rpt_head.inc.tpl'; ?>

<tbody>
<tr><td colspan="<?php echo (int) ($gkRptSurroundCols ?? 2); ?>" class="empty"></td></tr>
<tr><td colspan="<?php echo (int) ($gkRptSurroundCols ?? 2); ?>" class="report_content">

<?php
if ($ntype == 25) {
    // Settlers could not settle - the valley is already occupied
    echo TZ_VALLEY_OCCUPIED_MSG . ' ' . $coordLink;
} else {
    // New village founded
    $vname = htmlspecialchars((string)$database->getVillageField($wref, 'name'), ENT_QUOTES, 'UTF-8');
    echo TZ_NEW_VILLAGE_MSG . ' <b>' . $vname . '</b> ' . $coordLink;
}
?>

</td></tr>
</tbody>
</table>
<?php include __DIR__ . '/gk_rpt_foot.inc.tpl'; ?>
