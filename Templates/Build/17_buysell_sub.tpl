<?php

#################################################################################
##  MARKET — Buy/Sell sub-navigation (t=1 browse offers, t=2 create offer)     ##
#################################################################################

global $id;

$t = isset($_GET['t']) ? (int) $_GET['t'] : 0;
?>
<div id="textmenu" class="gk-market-sub">
	<a href="build.php?id=<?php echo (int) $id; ?>&amp;t=1"<?php if ($t === 1) echo ' class="selected"'; ?>><?php echo BUY; ?></a>
	| <a href="build.php?id=<?php echo (int) $id; ?>&amp;t=2"<?php if ($t === 2) echo ' class="selected"'; ?>><?php echo OFFER; ?></a>
</div>
