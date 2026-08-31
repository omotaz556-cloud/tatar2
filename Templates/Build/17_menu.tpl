<?php

#################################################################################
##  MARKETPLACE MENU — Greek 3-tab layout                                      ##
#################################################################################

global $id;

$t = isset($_GET['t']) ? (int) $_GET['t'] : 0;
$buySellActive = ($t === 1 || $t === 2);
$buySellHref = 'build.php?id=' . (int) $id . '&amp;t=1';
?>
<div id="textmenu" class="gk-market-nav">
	<a href="build.php?id=<?php echo (int) $id; ?>"<?php if ($t === 0) echo ' class="selected"'; ?>><?php echo SEND_RESOURCES; ?></a>
	| <a href="<?php echo $buySellHref; ?>"<?php if ($buySellActive) echo ' class="selected"'; ?>><?php echo defined('BUY_AND_SELL') ? BUY_AND_SELL : 'بيع وشراء'; ?></a>
	| <a href="build.php?id=<?php echo (int) $id; ?>&amp;t=3"<?php if ($t === 3) echo ' class="selected"'; ?>><?php echo NPC_TRADING; ?></a>
</div>
