/**
 * Greek resource bar — keep amounts & storage caps in sync without page reload.
 */
(function () {
    'use strict';

    var gkResbarBusy = false;
    var gkResbarIntervalMs = 5000;

    function gkFormatNum(n) {
        n = parseInt(n, 10) || 0;
        return String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function gkRestartResTimers() {
        if (typeof mb !== 'function') {
            return;
        }
        mb('l1');
        mb('l2');
        mb('l3');
        mb('l4');
    }

    window.gkRefreshResbar = function () {
        var root = document.getElementById('gkResbar');
        if (!root || gkResbarBusy) {
            return;
        }
        gkResbarBusy = true;

        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'ajax.php?f=resbar&_=' + Date.now(), true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4) {
                return;
            }
            gkResbarBusy = false;
            if (xhr.status !== 200) {
                return;
            }
            try {
                var d = JSON.parse(xhr.responseText);
                if (!d || !d.ok) {
                    return;
                }

                var amounts = { l4: d.wood, l3: d.clay, l2: d.iron, l1: d.crop };
                var prods = { l4: d.prodWood, l3: d.prodClay, l2: d.prodIron, l1: d.prodCrop };
                var maxes = { l4: d.maxStore, l3: d.maxStore, l2: d.maxStore, l1: d.maxCrop };

                Object.keys(amounts).forEach(function (id) {
                    var el = root.querySelector('#' + id);
                    if (!el) {
                        return;
                    }
                    el.textContent = String(amounts[id]);
                    el.setAttribute('data-max', String(maxes[id]));
                    el.title = String(prods[id]);
                });

                var storeCap = root.querySelector('[data-gk-cap="store"]');
                var cropCap = root.querySelector('[data-gk-cap="crop"]');
                if (storeCap) {
                    storeCap.textContent = gkFormatNum(d.maxStore);
                }
                if (cropCap) {
                    cropCap.textContent = gkFormatNum(d.maxCrop);
                }

                gkRestartResTimers();
            } catch (e) {
                /* ignore malformed JSON */
            }
        };
        xhr.send();
    };

    function gkInitResbarPoll() {
        if (!document.getElementById('gkResbar')) {
            return;
        }
        window.setTimeout(window.gkRefreshResbar, 1500);
        window.setInterval(window.gkRefreshResbar, gkResbarIntervalMs);
    }

    if (typeof window.addEvent === 'function') {
        window.addEvent('domready', gkInitResbarPoll);
    } else if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', gkInitResbarPoll);
    } else {
        gkInitResbarPoll();
    }
})();
