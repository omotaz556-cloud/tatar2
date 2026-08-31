/**
 * Greek shell — desktop: original layout (device-width + site zoom).
 * Narrow screens: proportional scaling (layout viewport 1024 + initial-scale).
 */
(function () {
    'use strict';

    var DESIGN_W = 1024;
    var REF_ZOOM = 1.1;
    var DESKTOP_MIN = DESIGN_W * REF_ZOOM;

    function isDesktopLayout(vw) {
        return vw >= DESKTOP_MIN;
    }

    function applyGkScale() {
        if (!document.body.classList.contains('pg-gk')) {
            return;
        }

        var html = document.documentElement;
        var vw = window.innerWidth || document.documentElement.clientWidth || DESIGN_W;
        var meta = document.querySelector('meta[name="viewport"]');

        if (isDesktopLayout(vw)) {
            html.classList.remove('gk-scaled-layout');
            html.classList.add('gk-desktop-layout');
            if (meta) {
                meta.setAttribute('content', 'width=device-width, initial-scale=1.0');
            }
            document.body.style.minHeight = '';
            return;
        }

        html.classList.remove('gk-desktop-layout');
        html.classList.add('gk-scaled-layout');

        var scale = Math.min(REF_ZOOM, vw / DESIGN_W);
        if (scale < 0.05) {
            scale = 0.05;
        }

        if (meta) {
            meta.setAttribute(
                'content',
                'width=' + DESIGN_W + ', initial-scale=' + scale.toFixed(4) + ', viewport-fit=cover'
            );
        }
    }

    function init() {
        applyGkScale();
        window.addEventListener('resize', applyGkScale, { passive: true });
        window.addEventListener('orientationchange', function () {
            setTimeout(applyGkScale, 150);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
