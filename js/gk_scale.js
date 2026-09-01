/**
 * Greek shell — desktop: device-width + CSS zoom 110%.
 * Mobile/tablet: stacked reflow layout (gk-stacked-layout), no viewport shrink.
 */
(function () {
    'use strict';

    var DESIGN_W = 1024;
    var REF_ZOOM = 1.1;
    var DESKTOP_MIN = DESIGN_W * REF_ZOOM;
    var VIEWPORT_DESKTOP = 'width=device-width, initial-scale=1.0, viewport-fit=cover';
    var VIEWPORT_STACKED = 'width=device-width, initial-scale=1.0, maximum-scale=5, viewport-fit=cover';

    function isDesktopLayout(vw) {
        return vw >= DESKTOP_MIN;
    }

    function applyGkScale() {
        if (!document.body || !document.body.classList.contains('pg-gk')) {
            return;
        }

        var html = document.documentElement;
        var vw = window.innerWidth || document.documentElement.clientWidth || DESIGN_W;
        var meta = document.querySelector('meta[name="viewport"]');

        if (isDesktopLayout(vw)) {
            html.classList.remove('gk-stacked-layout');
            html.classList.add('gk-desktop-layout');
            if (meta) {
                meta.setAttribute('content', VIEWPORT_DESKTOP);
            }
            return;
        }

        html.classList.remove('gk-desktop-layout');
        html.classList.add('gk-stacked-layout');
        if (meta) {
            meta.setAttribute('content', VIEWPORT_STACKED);
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
