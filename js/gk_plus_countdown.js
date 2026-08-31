/**
 * Live countdown for Plus balance rows (.gk-plus-countdown).
 */
(function () {
    if (window.gkPlusCountdownBooted) {
        return;
    }
    window.gkPlusCountdownBooted = true;

    var clientStart = Math.floor(Date.now() / 1000);

    function serverNowFrom(el) {
        var base = parseInt(el.getAttribute('data-now'), 10) || 0;
        return base + Math.floor(Date.now() / 1000) - clientStart;
    }

    function formatInner(end, now, l10n, untilHms) {
        var rem = Math.max(0, end - now);
        if (rem <= 0) {
            return '';
        }
        var d = Math.floor(rem / 86400);
        rem %= 86400;
        var h = Math.floor(rem / 3600);
        rem %= 3600;
        var m = Math.floor(rem / 60);
        var s = rem % 60;
        return l10n.remaining + ': <b>' + d + '</b> ' + l10n.days
            + ' <b>' + h + '</b> ' + l10n.hours
            + ' <b>' + m + '</b> ' + l10n.mins
            + ' <b>' + s + '</b> ' + l10n.seconds
            + ' (' + l10n.until + ' ' + untilHms + ')';
    }

    function tickAll() {
        var nodes = document.querySelectorAll('.gk-plus-countdown:not(.gk-plus-countdown-done)');
        if (!nodes.length) {
            return;
        }

        nodes.forEach(function (el) {
            var end = parseInt(el.getAttribute('data-end'), 10);
            var l10n;
            try {
                l10n = JSON.parse(el.getAttribute('data-l10n') || '{}');
            } catch (e) {
                l10n = {};
            }
            var untilHms = el.getAttribute('data-until-hms') || '';
            var now = serverNowFrom(el);
            var html = formatInner(end, now, l10n, untilHms);

            if (!html) {
                el.classList.add('gk-plus-countdown-done');
                var row = el.closest('.gk-plus-row');
                var expiredMsg = row ? row.getAttribute('data-expired-msg') : '';
                if (expiredMsg) {
                    el.textContent = expiredMsg;
                } else {
                    el.innerHTML = '';
                }
            } else {
                el.innerHTML = html;
            }
        });
    }

    function boot() {
        if (!document.querySelector('.gk-plus-countdown')) {
            return;
        }
        if (window.gkPlusCountdownInterval) {
            return;
        }
        tickAll();
        window.gkPlusCountdownInterval = window.setInterval(tickAll, 1000);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
    window.addEventListener('load', boot);
}());
