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
	<div id="anm" style="width:120px;height:140px;visibility:hidden;" aria-hidden="true"></div>
	<div class="gk-char-portrait" data-char-src="<?php echo $gkCharEsc; ?>" data-char-title="<?php echo $gkCharTitleEsc; ?>">
		<img id="gkCharImg" onclick="qst_handle();" src="<?php echo $gkCharEsc; ?>"
			title="<?php echo $gkCharTitleEsc; ?>" alt="<?php echo $gkCharTitleEsc; ?>" />
	</div>
	<div id="qge" style="display:none;height:0;overflow:hidden;visibility:hidden;" aria-hidden="true"></div>
	<?php
	$GLOBALS['gkQuestPortraitExternal'] = true;
	include dirname(__DIR__) . '/quest.tpl';
	include __DIR__ . '/gk_village_list.tpl';
	?>
</aside>
