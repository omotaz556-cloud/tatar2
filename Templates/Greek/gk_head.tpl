<div class="gk-head">
<div class="gk-banner" title="<?php echo htmlspecialchars(defined('SERVER_NAME') ? SERVER_NAME : 'حرب التتار', ENT_QUOTES, 'UTF-8'); ?>" role="img" aria-label="حرب التتار"></div>

<div class="gk-bar">
	<div class="gk-bar-inner">
		<div class="gk-bar-world">
			<?php echo function_exists('tz_day_night_icon_html') ? tz_day_night_icon_html('gk-daynight') : '<span class="gk-moon"></span>'; ?>
			<span class="gk-world-name"><?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?></span>
			<span class="gk-world-now">الآن : <b id="_Clock"><?php echo date('H:i:s'); ?></b></span>
		</div>
		<div class="gk-bar-spacer"></div>
		<div class="gk-bar-gold">
			<a href="plus.php?id=3" class="gk-gold" title="<?php echo defined('REG_PLUS_BALANCE') ? REG_PLUS_BALANCE : (defined('GOLD') ? GOLD : 'الرصيد'); ?>">
				<span class="gk-gold-mark" aria-hidden="true">&minus;</span>
				<span class="gk-gold-val"><?php echo number_format($gold); ?></span>
				<img src="img/x.gif" class="gold" alt="" title="<?php echo defined('GOLD') ? GOLD : 'ذهب'; ?>" />
			</a>
		</div>
	</div>
</div>

<div id="mtop" class="gk-mtop">
<?php include dirname(__DIR__) . '/topnav_icons.tpl'; ?>
</div>
<style type="text/css">
/* Village overview (n1, right end): mirror in place only. */
html[dir="rtl"] body.pg-gk .gk-head #topNavIcons > a#n1 img,
html[dir="rtl"] body.pg-gk .gk-head #topNavIcons > a#n1:hover img {
    transform: scaleX(-1) !important;
    -webkit-transform: scaleX(-1) !important;
    transform-origin: center center;
}
/* Reports/messages (n5, left end): original sprite — no extra flip. */
html[dir="rtl"] body.pg-gk .gk-head #topNavIcons > div#n5,
html[dir="rtl"] body.pg-gk .gk-head #topNavIcons div#n5 {
    transform: scaleX(-1) !important;
    -webkit-transform: scaleX(-1) !important;
    transform-origin: center center;
}
</style>
</div>
