<?php
if (empty($village)) {
    return;
}
$canRename = !(method_exists($session, 'isSitterSession') && $session->isSitterSession());
$vNameRaw = (string) $village->vname;
$renameLabel = defined('TZ_CHANGE_NAME') ? TZ_CHANGE_NAME : 'تغيير الاسم';
if (!isset($vDisplayName)) {
    $vDisplayName = function_exists('tz_display_village_name')
        ? tz_display_village_name($village->vname, $session->username ?? null)
        : $village->vname;
}
$gkIsCapital = !empty($village->capital);
$gkCapLabel = defined('TZ_PROF_CAP_SHORT') ? TZ_PROF_CAP_SHORT : (defined('CAPITAL1') ? CAPITAL1 : 'عاصمة');
?>
<div class="PaNa">
	<bdi>
		<?php if ($canRename) { ?>
		<span class="gk-vname gk-vname-editable" id="gkVnameLabel" onclick="gkToggleVnameForm(true);" title="<?php echo htmlspecialchars($renameLabel, ENT_QUOTES, 'UTF-8'); ?>" style="font-size:inherit;color:inherit;">
			<?php echo htmlspecialchars($vDisplayName, ENT_QUOTES, 'UTF-8'); ?>
		</span>
		<form class="gk-vname-form" id="gkVnameForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" style="display:none;">
			<input type="text" name="newVNa" value="<?php echo htmlspecialchars($vNameRaw, ENT_QUOTES, 'UTF-8'); ?>" maxlength="25" style="width:38%;font-size:inherit;height:auto;margin-left:10px;" />
			<button type="submit" class="gk-vname-btn"><?php echo htmlspecialchars($renameLabel, ENT_QUOTES, 'UTF-8'); ?></button>
		</form>
		<?php } else { ?>
		<span class="gk-vname"><?php echo htmlspecialchars($vDisplayName, ENT_QUOTES, 'UTF-8'); ?></span>
		<?php } ?>
	</bdi><?php if ($gkIsCapital) { ?> <a class="gk-cap">(<?php echo htmlspecialchars($gkCapLabel, ENT_QUOTES, 'UTF-8'); ?>)</a><?php } ?>
</div>
