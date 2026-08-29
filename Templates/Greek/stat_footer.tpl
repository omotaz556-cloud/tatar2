<div id="ce"></div>
<script type="text/javascript">
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
})();
</script>
</body>
</html>
