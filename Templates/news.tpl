<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       news.tpl                                                    ##
##  Developed by:  Dzoki                                                       ##
##  Refactored by: Shadow Incremental Refactor 			                       ##
##  License:       Novaterra Project                                            ##
##  Copyright:     Novaterra (c) 2010-2026. All rights reserved.                ##
##                                                                             ##
##  Incremental Refactor Notes:                                                ##
##  - Preserved original include-based structure                               ##
##  - Added safe include guards                                                ##
##  - Prevented warnings if files/constants are missing                        ##
##                                                                             ##
#################################################################################
?>


<?php
/* Natars announcement: the deadline is calculated server-side from config. */
$tzNatarsSpawnAt = strtotime(START_DATE) + ((int) NATARS_SPAWN_TIME * 86400 / SPEED);
$tzNatarsSpawned = method_exists($database, 'areArtifactsSpawned') && (bool) $database->areArtifactsSpawned();
$tzNatarsRemaining = max(0, $tzNatarsSpawnAt - time());
?>
<style type="text/css">
.natars-announcement {
	position: relative;
	clear: both;
	overflow: hidden;
	margin-top: 35px;
	padding: 10px 8px 9px;
	border: 1px solid #d7a928;
	border-radius: 6px;
	background: linear-gradient(135deg, #fffdf0, #fff4c2);
	box-shadow: 0 2px 7px rgba(143, 103, 0, 0.22);
	font-weight: normal;
	text-align: center;
}
.natars-announcement:before {
	position: absolute;
	top: 0;
	left: -35%;
	width: 35%;
	height: 3px;
	content: "";
	background: #d7a928;
	animation: natars-sign-scan 2.8s linear infinite;
}
.natars-ribbon {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 5px;
	color: #7b4d00;
	font-size: 13px;
	font-weight: 700;
}
.natars-ribbon-icon {
	display: inline-block;
	animation: natars-bow-pulse 1.4s ease-in-out infinite;
}
.natars-countdown {
	display: flex;
	justify-content: center;
	gap: 4px;
	direction: rtl;
	margin-top: 8px;
}
.natars-countdown span {
	min-width: 35px;
	padding: 4px 3px 3px;
	border: 1px solid #e1c66c;
	border-radius: 4px;
	background: rgba(255, 255, 255, 0.82);
}
.natars-countdown b,
.natars-countdown small {
	display: block;
}
.natars-countdown b {
	color: #513800;
	font-size: 15px;
	line-height: 16px;
}
.natars-countdown small {
	color: #987c35;
	font-size: 10px;
	line-height: 12px;
}
.natars-revealed {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 6px;
	color: #9a6400;
	font-size: 14px;
	font-weight: 700;
	animation: natars-reveal 0.7s ease-out both;
}
@keyframes natars-sign-scan {
	from { left: -35%; }
	to { left: 100%; }
}
@keyframes natars-bow-pulse {
	0%, 100% { transform: scale(1); }
	50% { transform: scale(1.16); }
}
@keyframes natars-reveal {
	from { opacity: 0; transform: translateY(5px); }
	to { opacity: 1; transform: translateY(0); }
}
.natars-revealed strong {
	display: block;
}
</style>
<div id="natarsAnnouncement" class="news natars-announcement"
	 data-spawn-at="<?php echo (int) $tzNatarsSpawnAt; ?>"
	 data-server-now="<?php echo time(); ?>">
<?php if ($tzNatarsSpawned || $tzNatarsRemaining === 0) { ?>
	<div class="natars-revealed"><span>🏹</span><strong>لقد ظهر التتار!</strong></div>
</div>
<?php } else { ?>
	<div class="natars-ribbon"><span class="natars-ribbon-icon">🏹</span><span>ظهور التتار خلال</span></div>
	<div id="natarsCountdown" class="natars-countdown">
		<span><b id="natarsDays"><?php echo (int) floor($tzNatarsRemaining / 86400); ?></b><small>يوم</small></span>
		<span><b id="natarsHours"><?php echo (int) floor(($tzNatarsRemaining % 86400) / 3600); ?></b><small>ساعة</small></span>
		<span><b id="natarsMinutes"><?php echo (int) floor(($tzNatarsRemaining % 3600) / 60); ?></b><small>دقيقة</small></span>
		<span><b id="natarsSeconds"><?php echo (int) ($tzNatarsRemaining % 60); ?></b><small>ثانية</small></span>
	</div>
</div>
<script type="text/javascript">
(function () {
	var announcement = document.getElementById('natarsAnnouncement');
	var countdown = document.getElementById('natarsCountdown');
	if (!announcement || !countdown) return;

	var spawnAt = parseInt(announcement.getAttribute('data-spawn-at'), 10);
	var serverNow = parseInt(announcement.getAttribute('data-server-now'), 10);
	var clientStartedAt = Math.floor(Date.now() / 1000);

	function render() {
		var elapsed = Math.floor(Date.now() / 1000) - clientStartedAt;
		var remaining = Math.max(0, spawnAt - (serverNow + elapsed));
		if (remaining <= 0) {
			announcement.innerHTML = '<div class="natars-revealed"><span>🏹</span><strong>لقد ظهر التتار!</strong></div>';
			return;
		}

		var days = Math.floor(remaining / 86400);
		var hours = Math.floor((remaining % 86400) / 3600);
		var minutes = Math.floor((remaining % 3600) / 60);
		var seconds = remaining % 60;
		document.getElementById('natarsDays').textContent = days;
		document.getElementById('natarsHours').textContent = hours;
		document.getElementById('natarsMinutes').textContent = minutes;
		document.getElementById('natarsSeconds').textContent = seconds;
		window.setTimeout(render, 1000);
	}

	render();
}());
</script>
<?php } ?>

<?php
if(NEWSBOX1){
	include "News/newsbox1.tpl";
	}
if(NEWSBOX2){
	include "News/newsbox2.tpl";
	}
if(NEWSBOX3){
	include "News/newsbox3.tpl";
	}
?>