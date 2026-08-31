<?php
$rallyFieldId = isset($rallyFieldId) ? (int) $rallyFieldId : (isset($id) ? (int) $id : 39);
$rallyTt = isset($rallyTt) ? (int) $rallyTt : 1;
$incomingBadge = isset($totalIncoming) ? (int) $totalIncoming : 0;
$inVillageLbl = defined('TZ_RALLY_IN_VILLAGE') ? TZ_RALLY_IN_VILLAGE : TROOPS_IN_THE_VILLAGE;
$incomingLbl = defined('TZ_RALLY_INCOMING') ? TZ_RALLY_INCOMING : INCOMING_TROOPS;
$outgoingLbl = defined('TZ_RALLY_OUTGOING') ? TZ_RALLY_OUTGOING : TROOPS_ON_THEIR_WAY;
$inOtherLbl = defined('TZ_RALLY_IN_OTHER') ? TZ_RALLY_IN_OTHER : TROOPS_IN_OTHER_VILLAGE;
$ovBase = 'build.php?id=' . $rallyFieldId;
?>
<div id="textmenu" class="gk-rally-sub">
    <a href="<?php echo $ovBase; ?>"<?php if ($rallyTt === 1) echo ' class="selected"'; ?>><?php echo $inVillageLbl; ?></a>
    | <a href="<?php echo $ovBase; ?>&amp;tt=2"<?php if ($rallyTt === 2) echo ' class="selected"'; ?>><?php echo $incomingLbl; ?><?php if ($incomingBadge > 0): ?><sup><?php echo $incomingBadge; ?></sup><?php endif; ?></a>
    | <a href="<?php echo $ovBase; ?>&amp;tt=3"<?php if ($rallyTt === 3) echo ' class="selected"'; ?>><?php echo $outgoingLbl; ?></a>
    | <a href="<?php echo $ovBase; ?>&amp;tt=4"<?php if ($rallyTt === 4) echo ' class="selected"'; ?>><?php echo $inOtherLbl; ?></a>
</div>