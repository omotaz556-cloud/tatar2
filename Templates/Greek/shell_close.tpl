<?php
global $gkShellOpenOpts, $session, $uid;
$gkScaleJsVer = @filemtime(dirname(__DIR__) . '/../js/gk_scale.js') ?: time();
$gkClose = isset($gkShellCloseOpts) && is_array($gkShellCloseOpts) ? $gkShellCloseOpts : array();
$gkWrapContent = isset($gkShellOpenOpts) && is_array($gkShellOpenOpts) && !empty($gkShellOpenOpts['contentWrap']);
$gkBuildPopup = !isset($gkClose['buildPopup']) || $gkClose['buildPopup'];
$gkExtraScripts = isset($gkClose['extraScripts']) ? $gkClose['extraScripts'] : '';
$gkExtraScriptTags = isset($gkClose['extraScriptTags']) ? $gkClose['extraScriptTags'] : '';
$gkTimer = isset($gkClose['timer']) ? $gkClose['timer'] : null;
$gkNavUid = (isset($session) && is_object($session) && isset($session->uid)) ? (int) $session->uid : 0;
?>
<?php if ($gkWrapContent) { ?>
		</div>
<?php } ?>
	</td>
<?php include __DIR__ . '/gk_nav.tpl'; ?>
</tr>
</table>

<?php if ($gkBuildPopup && is_file(dirname(__DIR__) . '/BuildPopup.tpl')) {
    include dirname(__DIR__) . '/BuildPopup.tpl';
} ?>
<div id="ce"></div>

<script type="text/javascript">
function gkToggleVnameForm(show) {
	var label = document.getElementById('gkVnameLabel');
	var form = document.getElementById('gkVnameForm');
	if (!label || !form) return;
	if (show) {
		label.style.display = 'none';
		form.style.display = 'inline-flex';
		var input = form.querySelector('input[name="newVNa"]');
		if (input) { input.focus(); input.select(); }
	} else {
		label.style.display = '';
		form.style.display = 'none';
	}
}
(function () {
	var el = document.getElementById('_Clock');
	if (!el) return;
	setInterval(function () {
		var d = new Date();
		function z(n) { return n < 10 ? '0' + n : '' + n; }
		el.textContent = z(d.getHours()) + ':' + z(d.getMinutes()) + ':' + z(d.getSeconds());
	}, 1000);
})();
(function () {
	var wrap = document.querySelector('.gk-char-portrait');
	if (!wrap) return;
	var src = wrap.getAttribute('data-char-src');
	var title = wrap.getAttribute('data-char-title') || '';
	if (!src) return;
	function gkEnsureCharPortrait() {
		var img = document.getElementById('gkCharImg');
		if (img && wrap.contains(img)) return;
		wrap.innerHTML = '<img id="gkCharImg" onclick="qst_handle();" src="'
			+ src + '" title="' + title + '" alt="' + title
			+ '" style="display:block;height:174px;width:auto;max-width:145px;margin:0 auto;cursor:pointer;border:0;" />';
	}
	gkEnsureCharPortrait();
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', gkEnsureCharPortrait);
	}
	window.addEventListener('load', gkEnsureCharPortrait);
	setTimeout(gkEnsureCharPortrait, 0);
	setTimeout(gkEnsureCharPortrait, 400);
	setTimeout(gkEnsureCharPortrait, 1500);
})();
<?php echo $gkExtraScripts; ?>
</script>
<?php echo $gkExtraScriptTags; ?>
<script src="js/gk_scale.js?v=<?php echo (int) $gkScaleJsVer; ?>" type="text/javascript"></script>
</div>
</body>
</html>
