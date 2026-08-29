<?php
global $database, $village, $session;

$gkSelfPage = basename($_SERVER['PHP_SELF']);
$gkTribe = 1;
if (isset($session->tribe) && (int) $session->tribe > 0) {
    $gkTribe = (int) $session->tribe;
} elseif (isset($session->userinfo['tribe']) && (int) $session->userinfo['tribe'] > 0) {
    $gkTribe = (int) $session->userinfo['tribe'];
}

$gkQst = isset($_SESSION['qst']) ? (int) $_SESSION['qst'] : 0;
$gkQstNew = isset($_SESSION['qstnew']) ? (int) $_SESSION['qstnew'] : 0;
if ($gkQst === 0 || $gkQstNew === 1) {
    $gkCharImg = GP_LOCATE . 'img/q/l' . $gkTribe . 'g.jpg';
} else {
    $gkCharImg = GP_LOCATE . 'img/q/l' . $gkTribe . '.jpg';
}

$gkCharTitle = defined('TO_THE_TASK') ? TO_THE_TASK : 'المهام';
$gkCharSrc = $gkCharImg;
if ($gkCharSrc !== '' && $gkCharSrc[0] !== '/' && !preg_match('#^https?://#i', $gkCharSrc)) {
    $gkCharSrc = '/' . ltrim($gkCharSrc, '/');
}
$gkCharEsc = htmlspecialchars($gkCharSrc, ENT_QUOTES, 'UTF-8');
$gkCharTitleEsc = htmlspecialchars($gkCharTitle, ENT_QUOTES, 'UTF-8');

if (!isset($vDisplayName)) {
    if (!empty($village) && is_object($village) && isset($village->vname)) {
        $vDisplayName = function_exists('tz_display_village_name')
            ? tz_display_village_name($village->vname, $session->username ?? null)
            : $village->vname;
    } else {
        $vDisplayName = '';
    }
}

$gkCapLabel = defined('TZ_PROF_CAP_SHORT') ? TZ_PROF_CAP_SHORT : (defined('CAPITAL1') ? CAPITAL1 : 'عاصمة');
$gkCx = (!empty($village) && is_object($village) && isset($village->coor['x'])) ? (int) $village->coor['x'] : 0;
$gkCy = (!empty($village) && is_object($village) && isset($village->coor['y'])) ? (int) $village->coor['y'] : 0;
$gkIsCap = (!empty($village) && is_object($village) && !empty($village->capital));
?>
<aside class="gk-stat-left">
	<div id="anm" style="width:0;height:0;visibility:hidden;overflow:hidden;" aria-hidden="true"></div>
	<div class="gk-char-portrait" data-char-src="<?php echo $gkCharEsc; ?>" data-char-title="<?php echo $gkCharTitleEsc; ?>">
		<img id="gkCharImg" onclick="qst_handle();" src="<?php echo $gkCharEsc; ?>"
			title="<?php echo $gkCharTitleEsc; ?>" alt="<?php echo $gkCharTitleEsc; ?>" />
	</div>
	<div id="qge" style="display:none;height:0;overflow:hidden;visibility:hidden;" aria-hidden="true"></div>
	<?php
	$GLOBALS['gkQuestPortraitExternal'] = true;
	include dirname(__DIR__) . '/quest.tpl';
	?>
	<div class="gk-stat-side">
		<table class="MyVs"><tbody>
			<tr><th colspan="2"><a href="dorf3.php" class="C_K">قائمة القرى</a></th></tr>
			<?php
			$gkVilRows = (isset($database) && is_object($database) && method_exists($database, 'getArrayMemberVillage'))
				? $database->getArrayMemberVillage($session->uid)
				: array();
			if (is_array($gkVilRows) && count($gkVilRows) > 0) {
				foreach ($gkVilRows as $gkVilRow) {
					$gkVid = (int) $gkVilRow['wref'];
					$gkVnm = htmlspecialchars(tz_display_village_name($gkVilRow['name'], $session->username ?? null), ENT_QUOTES, 'UTF-8');
					$gkVx = isset($gkVilRow['x']) ? (int) $gkVilRow['x'] : 0;
					$gkVy = isset($gkVilRow['y']) ? (int) $gkVilRow['y'] : 0;
					$gkVcap = !empty($gkVilRow['capital']);
					?>
			<tr><th><a class="C_O" href="<?php echo htmlspecialchars($gkSelfPage, ENT_QUOTES, 'UTF-8'); ?>?newdid=<?php echo $gkVid; ?>"> <?php echo $gkVnm; ?></a><?php if ($gkVcap) { ?><a class="fl"> (<?php echo $gkCapLabel; ?>) </a><?php } ?></th><th class="gk-vcoords"><a href="build.php?id=39" title="<?php echo defined('MARKET') ? MARKET : 'السوق'; ?>"><p class="Rs x6"></p></a><a href="karte.php?x=<?php echo $gkVx; ?>&amp;y=<?php echo $gkVy; ?>" title="<?php echo defined('MAP') ? MAP : 'الخريطة'; ?>"><p class="Rs x5"></p></a><b><bdi>(<?php echo $gkVx; ?>|<?php echo $gkVy; ?>)</bdi></b></th></tr>
					<?php
				}
			} else {
				?>
			<tr><th><a class="C_O" href="dorf1.php"> <?php echo htmlspecialchars($vDisplayName, ENT_QUOTES, 'UTF-8'); ?></a><?php if ($gkIsCap) { ?><a class="fl"> (<?php echo $gkCapLabel; ?>) </a><?php } ?></th><th class="gk-vcoords"><a href="build.php?id=39" title="<?php echo defined('MARKET') ? MARKET : 'السوق'; ?>"><p class="Rs x6"></p></a><a href="karte.php?x=<?php echo $gkCx; ?>&amp;y=<?php echo $gkCy; ?>" title="<?php echo defined('MAP') ? MAP : 'الخريطة'; ?>"><p class="Rs x5"></p></a><b><bdi>(<?php echo $gkCx; ?>|<?php echo $gkCy; ?>)</bdi></b></th></tr>
				<?php
			}
			?>
		</tbody></table>
	</div>
</aside>
