<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : centralGold.tpl                                          ##
##  Type           : Admin Panel Frontend for cross-world paid gold           ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow                                                    ##
##  Project        : Novaterra                                                 ##
##  License        : Novaterra Project                                        ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.            ##
## --------------------------------------------------------------------------- ##
#################################################################################

if (!isset($_SESSION['access']) || $_SESSION['access'] < ADMIN) {
    echo '<p style="color:#f87171;padding:16px;">Access denied.</p>';
    return;
}

include_once(__DIR__ . '/../../GameEngine/CentralGold.php');

$configured = CentralGold::isConfigured();
$msg        = isset($_GET['msg']) ? (string) $_GET['msg'] : '';
$lookupEmail = isset($_GET['lookup']) ? trim((string) $_GET['lookup']) : '';
$lookupAccount = null;
$lookupWorlds  = [];

if ($configured && $lookupEmail !== '') {
    $lookupAccount = CentralGold::findByEmail($lookupEmail);
    if ($lookupAccount) {
        $lookupWorlds = CentralGold::worldsForAccount((int) $lookupAccount['id']);
    }
}

$freeGoldEnabled = $configured ? CentralGold::isFreeGoldEnabled() : true;
$ledger = $configured ? CentralGold::recentLedger(40) : [];
?>
<style>
.cg-wrap{color:#e2e8f0;font-family:Verdana,Arial,sans-serif;font-size:12px;padding:6px 4px 26px;}
.cg-wrap h2{font-size:18px;margin:0 0 4px;color:#fff;}
.cg-wrap h2 span{color:#f59e0b;}
.cg-intro{color:#94a3b8;font-size:11px;margin:0 0 14px;max-width:860px;line-height:1.5;}
.cg-msg{background:#14532d;border:1px solid #166534;color:#bbf7d0;border-radius:6px;padding:8px 12px;font-size:11px;margin-bottom:14px;}
.cg-warn{background:#78350f;border:1px solid #92400e;color:#fde68a;border-radius:6px;padding:8px 12px;font-size:11px;margin-bottom:14px;}
.cg-card{background:#111827;border:1px solid #1f2937;border-radius:8px;padding:14px 16px;margin-bottom:18px;}
.cg-card h3{margin:0 0 4px;font-size:13px;color:#fff;}
.cg-card .cg-desc{color:#64748b;font-size:10px;margin:0 0 12px;max-width:760px;line-height:1.5;}
.cg-toggle-row{display:flex;align-items:center;gap:14px;flex-wrap:wrap;}
.cg-radio{display:flex;align-items:center;gap:6px;font-size:11px;color:#cbd5e1;}
.cg-add{display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;}
.cg-add label{display:block;font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:#94a3b8;margin-bottom:4px;}
.cg-add input[type=text],.cg-add input[type=email],.cg-add input[type=number]{background:#0b1220;border:1px solid #334155;border-radius:6px;color:#e2e8f0;padding:7px 9px;}
.cg-add input.email{width:220px;}
.cg-add input.num{width:110px;}
.cg-add input.note{width:200px;}
.cg-add button{background:#f59e0b;color:#111827;font-weight:bold;border:0;border-radius:6px;padding:9px 18px;cursor:pointer;}
.cg-table{width:100%;border-collapse:collapse;background:#0b1220;border:1px solid #1f2937;border-radius:8px;}
.cg-table th{background:#111827;text-align:left;padding:7px 7px;font-size:9px;text-transform:uppercase;letter-spacing:.3px;color:#94a3b8;border-bottom:1px solid #1f2937;white-space:nowrap;}
.cg-table td{padding:6px 7px;border-bottom:1px solid #14203a;vertical-align:middle;font-size:11px;}
.cg-table tr:hover td{background:#0f1a30;}
.cg-scroll{overflow-x:auto;}
.cg-empty{padding:22px;text-align:center;color:#64748b;}
.num{font-variant-numeric:tabular-nums;}
.cg-gold{color:#fde68a;font-weight:bold;}
.cg-pos{color:#86efac;}
.cg-neg{color:#fca5a5;}
.cg-result-card{background:#0b1220;border:1px solid #334155;border-radius:8px;padding:12px 14px;margin-top:12px;}
.cg-result-card .label{color:#64748b;text-transform:uppercase;font-size:9px;letter-spacing:.5px;}
.cg-uac{position:relative;}
.cg-uac input.uac-input{width:220px;}
.cg-uac-list{display:none;position:absolute;top:100%;left:0;z-index:20;width:260px;max-height:220px;overflow-y:auto;background:#111827;border:1px solid #334155;border-radius:6px;margin-top:4px;box-shadow:0 8px 20px rgba(0,0,0,.4);}
.cg-uac-list.open{display:block;}
.cg-uac-item{padding:7px 10px;cursor:pointer;border-bottom:1px solid #1f2937;font-size:11px;}
.cg-uac-item:last-child{border-bottom:0;}
.cg-uac-item:hover,.cg-uac-item.active{background:#1e293b;}
.cg-uac-item .u{color:#e2e8f0;font-weight:bold;}
.cg-uac-item .e{color:#64748b;font-size:9px;}
.cg-uac-item .g{float:right;color:#fde68a;}
.cg-uac-empty{padding:8px 10px;color:#64748b;font-size:10px;}
</style>

<div class="cg-wrap">
    <h2><?php echo ADMIN_GOLD; ?><span><?php echo ADMIN_CENTRAL_GOLD; ?></span></h2>
    <p class="cg-intro"><?php echo ADM_CG_LOOKUP_DESC; ?></p>

    <?php if ($msg !== ''): ?>
        <div class="cg-msg"><?php echo e($msg); ?></div>
    <?php endif; ?>

    <?php if (!$configured): ?>
        <div class="cg-warn"><?php echo ADM_CG_NOT_CONFIGURED; ?></div>
    <?php endif; ?>

    <!-- Free gold visibility toggle -->
    <div class="cg-card">
        <h3><?php echo ADM_CG_FREE_GOLD_SETTING; ?></h3>
        <p class="cg-desc"><?php echo ADM_CG_FREE_GOLD_DESC; ?></p>
        <form method="post" action="../GameEngine/Admin/Mods/centralGoldAdmin.php" class="cg-toggle-row">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="do" value="toggle_free_gold">
            <label class="cg-radio">
                <input type="radio" name="enabled" value="1" <?php echo $freeGoldEnabled ? 'checked' : ''; ?> onchange="this.form.submit()" <?php echo $configured ? '' : 'disabled'; ?>>
                <?php echo ADM_CG_FREE_GOLD_ENABLED; ?>
            </label>
            <label class="cg-radio">
                <input type="radio" name="enabled" value="0" <?php echo !$freeGoldEnabled ? 'checked' : ''; ?> onchange="this.form.submit()" <?php echo $configured ? '' : 'disabled'; ?>>
                <?php echo ADM_CG_FREE_GOLD_DISABLED; ?>
            </label>
            <noscript><button type="submit"><?php echo ADM_CG_SAVE; ?></button></noscript>
        </form>
    </div>

    <!-- Player lookup -->
    <div class="cg-card">
        <h3><?php echo ADM_CG_LOOKUP_TITLE; ?></h3>
        <p class="cg-desc"><?php echo ADM_CG_LOOKUP_DESC; ?></p>
        <form method="post" action="../GameEngine/Admin/Mods/centralGoldAdmin.php" class="cg-add">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="do" value="lookup">
            <div class="cg-uac">
                <label><?php echo ADM_CG_SEARCH_LABEL; ?></label>
                <input class="email uac-input" type="text" autocomplete="off" data-cg-uac-target="lookup_email" placeholder="<?php echo ADM_CG_SEARCH_PLACEHOLDER; ?>" <?php echo $configured ? '' : 'disabled'; ?>>
                <div class="cg-uac-list" data-cg-uac-list="lookup_email"></div>
            </div>
            <div>
                <label><?php echo ADM_CG_EMAIL; ?></label>
                <input class="email" type="email" name="email" id="lookup_email" value="<?php echo e($lookupEmail); ?>" placeholder="player@example.com" required <?php echo $configured ? '' : 'disabled'; ?>>
            </div>
            <button type="submit" <?php echo $configured ? '' : 'disabled'; ?>><?php echo ADM_CG_SEARCH; ?></button>
        </form>

        <?php if ($lookupEmail !== ''): ?>
            <?php if (!$lookupAccount): ?>
                <div class="cg-result-card"><?php echo ADM_CG_NO_ACCOUNT_FOUND; ?></div>
            <?php else: ?>
                <div class="cg-result-card">
                    <div style="display:flex;gap:32px;flex-wrap:wrap;margin-bottom:10px;">
                        <div>
                            <div class="label"><?php echo ADM_CG_USERNAME; ?></div>
                            <div><?php echo e($lookupAccount['username']); ?></div>
                        </div>
                        <div>
                            <div class="label"><?php echo ADM_CG_EMAIL; ?></div>
                            <div><?php echo e($lookupAccount['email']); ?></div>
                        </div>
                        <div>
                            <div class="label"><?php echo ADM_CG_PAID_BALANCE; ?></div>
                            <div class="cg-gold num"><?php echo number_format((int) $lookupAccount['paid_gold']); ?></div>
                        </div>
                    </div>
                    <div class="label" style="margin-bottom:6px;"><?php echo ADM_CG_WORLDS_SEEN; ?></div>
                    <?php if (empty($lookupWorlds)): ?>
                        <div style="color:#64748b;"><?php echo ADM_CG_NO_WORLDS; ?></div>
                    <?php else: ?>
                        <div class="cg-scroll">
                        <table class="cg-table">
                            <thead>
                                <tr><th><?php echo ADM_CG_WORLD; ?></th><th><?php echo ADM_CG_LAST_SEEN; ?></th></tr>
                            </thead>
                            <tbody>
                            <?php foreach ($lookupWorlds as $w): ?>
                                <tr>
                                    <td><?php echo e($w['world_key']); ?></td>
                                    <td class="num" style="color:#94a3b8;"><?php echo $w['last_seen'] ? date('Y-m-d H:i', (int) $w['last_seen']) : '&ndash;'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Transfer -->
    <div class="cg-card">
        <h3><?php echo ADM_CG_TRANSFER_TITLE; ?></h3>
        <p class="cg-desc"><?php echo ADM_CG_TRANSFER_DESC; ?></p>
        <form method="post" action="../GameEngine/Admin/Mods/centralGoldAdmin.php" class="cg-add">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="do" value="transfer">
            <div class="cg-uac">
                <label><?php echo ADM_CG_SEARCH_LABEL; ?> (<?php echo ADM_CG_FROM_EMAIL; ?>)</label>
                <input class="email uac-input" type="text" autocomplete="off" data-cg-uac-target="transfer_from_email" placeholder="<?php echo ADM_CG_SEARCH_PLACEHOLDER; ?>" <?php echo $configured ? '' : 'disabled'; ?>>
                <div class="cg-uac-list" data-cg-uac-list="transfer_from_email"></div>
            </div>
            <div>
                <label><?php echo ADM_CG_FROM_EMAIL; ?></label>
                <input class="email" type="email" name="from_email" id="transfer_from_email" placeholder="sender@example.com" required <?php echo $configured ? '' : 'disabled'; ?>>
            </div>
            <div class="cg-uac">
                <label><?php echo ADM_CG_SEARCH_LABEL; ?> (<?php echo ADM_CG_TO_EMAIL; ?>)</label>
                <input class="email uac-input" type="text" autocomplete="off" data-cg-uac-target="transfer_to_email" placeholder="<?php echo ADM_CG_SEARCH_PLACEHOLDER; ?>" <?php echo $configured ? '' : 'disabled'; ?>>
                <div class="cg-uac-list" data-cg-uac-list="transfer_to_email"></div>
            </div>
            <div>
                <label><?php echo ADM_CG_TO_EMAIL; ?></label>
                <input class="email" type="email" name="to_email" id="transfer_to_email" placeholder="receiver@example.com" required <?php echo $configured ? '' : 'disabled'; ?>>
            </div>
            <div>
                <label><?php echo ADM_CG_AMOUNT; ?></label>
                <input class="num" type="number" name="amount" min="1" value="100" required <?php echo $configured ? '' : 'disabled'; ?>>
            </div>
            <div>
                <label><?php echo ADM_CG_NOTE_OPTIONAL; ?></label>
                <input class="note" type="text" name="note" maxlength="255" <?php echo $configured ? '' : 'disabled'; ?>>
            </div>
            <button type="submit" <?php echo $configured ? '' : 'disabled'; ?>><?php echo ADM_CG_TRANSFER_BTN; ?></button>
        </form>
    </div>

    <!-- Recent ledger -->
    <div class="cg-card" style="padding:0;">
        <div style="padding:14px 16px 0;">
            <h3><?php echo ADM_CG_RECENT_LEDGER; ?></h3>
        </div>
        <?php if (empty($ledger)): ?>
            <div class="cg-empty"><?php echo ADM_CG_NO_LEDGER; ?></div>
        <?php else: ?>
            <div class="cg-scroll">
            <table class="cg-table">
                <thead>
                    <tr>
                        <th><?php echo ADM_CG_WHEN; ?></th>
                        <th><?php echo ADM_CG_PLAYER; ?></th>
                        <th><?php echo ADM_CG_WORLD; ?></th>
                        <th><?php echo ADM_CG_DELTA; ?></th>
                        <th><?php echo ADM_CG_BALANCE_AFTER; ?></th>
                        <th><?php echo ADM_CG_REASON; ?></th>
                        <th><?php echo ADM_NOTE; ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($ledger as $row):
                    $delta = (int) $row['delta'];
                    $deltaClass = $delta >= 0 ? 'cg-pos' : 'cg-neg';
                    $deltaText = ($delta >= 0 ? '+' : '') . number_format($delta);
                ?>
                    <tr>
                        <td class="num" style="color:#94a3b8;"><?php echo $row['time'] ? date('Y-m-d H:i', (int) $row['time']) : '&ndash;'; ?></td>
                        <td><?php echo e($row['username'] ?? $row['email']); ?></td>
                        <td><?php echo e($row['world_key']); ?></td>
                        <td class="num <?php echo $deltaClass; ?>"><?php echo $deltaText; ?></td>
                        <td class="num"><?php echo number_format((int) $row['balance_after']); ?></td>
                        <td><?php echo e($row['reason']); ?></td>
                        <td style="color:#94a3b8;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo $row['note'] !== '' ? e($row['note']) : '&ndash;'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($configured): ?>
<script>
(function () {
    // Username search-as-you-type for the Central Gold admin picker. Each
    // .cg-uac block has a text input (data-cg-uac-target = id of the real
    // email <input> it fills in) and a results <div> (data-cg-uac-list with
    // the same key). Hits GameEngine/Admin/Mods/centralGoldAdmin.php?do=search
    // (GET, JSON) - see that file for why this one action skips CSRF.
    var SEARCH_URL = '../GameEngine/Admin/Mods/centralGoldAdmin.php?do=search&q=';
    var MIN_CHARS = 2;
    var DEBOUNCE_MS = 250;

    function fmtGold(n) {
        n = parseInt(n, 10) || 0;
        return n.toLocaleString();
    }

    function closeList(list) {
        list.classList.remove('open');
        list.innerHTML = '';
    }

    function renderResults(list, results, targetInput) {
        list.innerHTML = '';
        if (!results.length) {
            var empty = document.createElement('div');
            empty.className = 'cg-uac-empty';
            empty.textContent = '<?php echo addslashes(ADM_CG_SEARCH_NO_RESULTS); ?>';
            list.appendChild(empty);
            list.classList.add('open');
            return;
        }
        results.forEach(function (r) {
            var item = document.createElement('div');
            item.className = 'cg-uac-item';
            item.innerHTML =
                '<span class="g">' + fmtGold(r.paid_gold) + '</span>' +
                '<span class="u">' + escapeHtml(r.username) + '</span><br>' +
                '<span class="e">' + escapeHtml(r.email) + '</span>';
            item.addEventListener('click', function () {
                targetInput.value = r.email;
                closeList(list);
            });
            list.appendChild(item);
        });
        list.classList.add('open');
    }

    function escapeHtml(s) {
        var d = document.createElement('div');
        d.textContent = s == null ? '' : String(s);
        return d.innerHTML;
    }

    document.querySelectorAll('.cg-uac').forEach(function (wrap) {
        var searchInput = wrap.querySelector('.uac-input');
        var key = searchInput.getAttribute('data-cg-uac-target');
        var list = wrap.querySelector('[data-cg-uac-list="' + key + '"]');
        var targetInput = document.getElementById(key);
        if (!searchInput || !list || !targetInput) return;

        var timer = null;
        var currentFetch = 0;

        searchInput.addEventListener('input', function () {
            var q = searchInput.value.trim();
            clearTimeout(timer);
            if (q.length < MIN_CHARS) {
                if (q.length > 0) {
                    list.innerHTML = '<div class="cg-uac-empty"><?php echo addslashes(ADM_CG_SEARCH_MIN_CHARS); ?></div>';
                    list.classList.add('open');
                } else {
                    closeList(list);
                }
                return;
            }
            timer = setTimeout(function () {
                var myFetch = ++currentFetch;
                fetch(SEARCH_URL + encodeURIComponent(q), { credentials: 'same-origin' })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (myFetch !== currentFetch) return; // stale response, a newer keystroke has already fired
                        renderResults(list, (data && data.results) || [], targetInput);
                    })
                    .catch(function () { closeList(list); });
            }, DEBOUNCE_MS);
        });

        document.addEventListener('click', function (e) {
            if (!wrap.contains(e.target)) closeList(list);
        });
    });
})();
</script>
<?php endif; ?>
