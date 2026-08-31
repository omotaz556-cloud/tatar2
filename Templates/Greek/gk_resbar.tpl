<?php
if (empty($village)) {
    return;
}
$woodProd = round($village->getProd('wood'));
$clayProd = round($village->getProd('clay'));
$ironProd = round($village->getProd('iron'));
$cropProd = round($village->getProd('crop'));
$resFloor = static function ($v) {
	$v = (float) $v;
	return (is_finite($v) && $v > 0) ? (int) floor($v) : 0;
};
$woodStore = $resFloor($village->awood);
$clayStore = $resFloor($village->aclay);
$ironStore = $resFloor($village->airon);
$cropStore = $resFloor($village->acrop);
$maxStore = (int) $village->maxstore;
$maxCrop = (int) $village->maxcrop;
$whTitle = defined('WAREHOUSE') ? WAREHOUSE : 'مخزن';
$grTitle = defined('GRANARY') ? GRANARY : 'مخزن حبوب';
?>
<div class="gk-resbar" id="gkResbar">
	<span class="gk-resitem gk-rescap" title="<?php echo htmlspecialchars($whTitle, ENT_QUOTES, 'UTF-8'); ?>">
		<img src="img/x.gif" class="warehouse" alt="" title="<?php echo htmlspecialchars($whTitle, ENT_QUOTES, 'UTF-8'); ?>" />
		<b class="gk-cap" data-gk-cap="store"><?php echo number_format($maxStore); ?></b>
	</span>
	<span class="gk-resitem">
		<img src="img/x.gif" class="r1" alt="<?php echo LUMBER; ?>" title="<?php echo LUMBER; ?>" />
		<b><span id="l4" title="<?php echo $woodProd; ?>" data-max="<?php echo $maxStore; ?>"><?php echo $woodStore; ?></span></b>
	</span>
	<span class="gk-resitem">
		<img src="img/x.gif" class="r2" alt="<?php echo CLAY; ?>" title="<?php echo CLAY; ?>" />
		<b><span id="l3" title="<?php echo $clayProd; ?>" data-max="<?php echo $maxStore; ?>"><?php echo $clayStore; ?></span></b>
	</span>
	<span class="gk-resitem">
		<img src="img/x.gif" class="r3" alt="<?php echo IRON; ?>" title="<?php echo IRON; ?>" />
		<b><span id="l2" title="<?php echo $ironProd; ?>" data-max="<?php echo $maxStore; ?>"><?php echo $ironStore; ?></span></b>
	</span>
	<span class="gk-resitem gk-rescap" title="<?php echo htmlspecialchars($grTitle, ENT_QUOTES, 'UTF-8'); ?>">
		<img src="img/x.gif" class="granary" alt="" title="<?php echo htmlspecialchars($grTitle, ENT_QUOTES, 'UTF-8'); ?>" />
		<b class="gk-cap" data-gk-cap="crop"><?php echo number_format($maxCrop); ?></b>
	</span>
	<span class="gk-resitem">
		<img src="img/x.gif" class="r4" alt="<?php echo CROP; ?>" title="<?php echo CROP; ?>" />
		<b>
			<span id="l1" title="<?php echo $cropProd; ?>" data-max="<?php echo $maxCrop; ?>"><?php echo $cropStore; ?></span>
		</b>
	</span>
</div>
