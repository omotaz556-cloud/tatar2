<?php
$gkOpen = isset($gkShellOpenOpts) && is_array($gkShellOpenOpts) ? $gkShellOpenOpts : array();
$gkWrapContent = !empty($gkOpen['contentWrap']);
$gkShowVTitle = !empty($gkOpen['showVillageTitle']);
$gkNavUid = isset($uid) ? (int) $uid : (isset($session->uid) ? (int) $session->uid : 0);
?>
<table class="gk-shell" cellpadding="0" cellspacing="0">
<tr>
<?php include __DIR__ . '/gk_left.tpl'; ?>
	<td class="gk-td-main">
<?php
if (!empty($gkOpen['resbarInMain'])) {
	include __DIR__ . '/gk_resbar.tpl';
}
?>
<?php if ($gkShowVTitle && empty($gkOpen['villageTitleInMap']) && !empty($village)) {
	include __DIR__ . '/gk_village_title.tpl';
} ?>
<?php if ($gkWrapContent) {
    $gkClassAttr = isset($gkContentClass) && $gkContentClass !== ''
        ? ' class="' . htmlspecialchars($gkContentClass, ENT_QUOTES, 'UTF-8') . '"'
        : '';
?>
		<div id="content"<?php echo $gkClassAttr; ?>>
<?php } ?>
