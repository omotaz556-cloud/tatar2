<?php
#################################################################################
##  Filename       : Admin/Templates/multiacc.tpl                             ##
##  Type           : Admin page - Multi-Account Detection                     ##
##  Project        : Novaterra                                                 ##
##  License        : Novaterra Project                                         ##
#################################################################################

// Access: admins (9) and multihunters (8) only.
if (!isset($_SESSION['access']) || $_SESSION['access'] < MULTIHUNTER) {
    echo '<p style="color:#f87171;padding:16px;">Access denied.</p>';
    return;
}

// Filters (GET). All optional.
$days     = isset($_GET['days'])     && ctype_digit((string)$_GET['days'])     ? max(1, min(365, (int)$_GET['days']))     : MultiAccount::WINDOW_DAYS;
$minScore = isset($_GET['min'])      && ctype_digit((string)$_GET['min'])      ? max(0, min(100, (int)$_GET['min']))      : MultiAccount::MIN_REPORT_SCORE;
$focusUid = isset($_GET['focus'])    && ctype_digit((string)$_GET['focus'])    ? (int)$_GET['focus']                       : 0;

// Toggles (persisted). Full-admin (access=9) can change them via the form
// below; multihunters (access=8) can view this page but the save form is
// hidden from them (multiAccountSettings.php also re-checks access=9 server-side).
$settings   = MultiAccount::getSettings();
$isFullAdmin = ($_SESSION['access'] ?? 0) >= 9;
$flashMsg   = isset($_GET['msg']) ? (string)$_GET['msg'] : '';

// When detection is ON, the admin-panel visit itself is what triggers the
// auto-ban pass (see MultiAccount::applyAutoBan() docblock) — so any pair
// that crosses the threshold gets banned as soon as an admin/multihunter
// loads this page, not only when settings are explicitly re-saved.
$autoBannedNow = [];
if ($settings['enabled'] && $settings['auto_ban']) {
    $autoBannedNow = MultiAccount::applyAutoBan();
}

$data  = MultiAccount::riskPairs(['days' => $days, 'min_score' => $minScore, 'focus_uid' => $focusUid]);
$pairs = $data['pairs'];
?>
<style>
.mad-wrap{color:#e2e8f0;font-family:Verdana,Arial,sans-serif;font-size:12px;padding:6px 4px 26px;}
.mad-wrap h2{font-size:18px;margin:0 0 4px;color:#fff;}
.mad-wrap h2 span{color:#f59e0b;}
.mad-intro{color:#94a3b8;font-size:11px;margin:0 0 14px;max-width:820px;line-height:1.5;}
.mad-filter{background:#111827;border:1px solid #1f2937;border-radius:8px;padding:12px 14px;display:flex;flex-wrap:wrap;gap:14px;align-items:flex-end;margin-bottom:16px;}
.mad-filter label{display:block;font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:#94a3b8;margin-bottom:4px;}
.mad-filter input{background:#0b1220;border:1px solid #334155;border-radius:6px;color:#e2e8f0;padding:6px 8px;width:110px;}
.mad-filter button{background:#f59e0b;color:#111827;font-weight:bold;border:0;border-radius:6px;padding:8px 16px;cursor:pointer;}
.mad-filter a.reset{color:#94a3b8;font-size:11px;text-decoration:none;padding:8px 4px;}
.mad-meta{color:#64748b;font-size:11px;margin-bottom:10px;}
.mad-warn{background:#422006;border:1px solid #92400e;color:#fbbf24;border-radius:6px;padding:8px 12px;font-size:11px;margin-bottom:12px;}
.mad-table{width:100%;border-collapse:collapse;background:#0b1220;border:1px solid #1f2937;border-radius:8px;overflow:hidden;}
.mad-table th{background:#111827;text-align:left;padding:9px 10px;font-size:10px;text-transform:uppercase;letter-spacing:.5px;color:#94a3b8;border-bottom:1px solid #1f2937;}
.mad-table td{padding:9px 10px;border-bottom:1px solid #14203a;vertical-align:top;}
.mad-table tr:hover td{background:#0f1a30;}
.mad-acc a{color:#e2e8f0;text-decoration:none;font-weight:bold;}
.mad-acc a:hover{color:#f59e0b;}
.mad-acc .uid{color:#475569;font-weight:normal;font-size:10px;}
.mad-acc .sub{display:block;margin-top:2px;}
.mad-acc .sub a{font-size:10px;color:#64748b;font-weight:normal;margin-right:8px;}
.mad-score{font-weight:bold;font-size:14px;}
.badge{display:inline-block;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:bold;text-transform:uppercase;letter-spacing:.5px;}
.b-high{background:#7f1d1d;color:#fecaca;}
.b-med{background:#78350f;color:#fde68a;}
.b-low{background:#1e3a5f;color:#bfdbfe;}
.chips span{display:inline-block;background:#1e293b;color:#cbd5e1;border-radius:4px;padding:2px 7px;margin:2px 3px 0 0;font-size:10px;}
.mad-empty{padding:26px;text-align:center;color:#64748b;}
.mad-bar{height:6px;background:#1e293b;border-radius:3px;margin-top:4px;overflow:hidden;}
.mad-bar i{display:block;height:100%;}
.mad-panel{background:#111827;border:1px solid #1f2937;border-radius:8px;padding:14px 16px;margin-bottom:14px;}
.mad-panel-row{display:flex;flex-wrap:wrap;gap:22px;align-items:center;}
.mad-toggle{display:flex;align-items:center;gap:8px;}
.mad-toggle label{font-size:12px;color:#e2e8f0;font-weight:bold;cursor:pointer;}
.mad-toggle input[type=checkbox]{width:16px;height:16px;cursor:pointer;}
.mad-score-input{display:flex;align-items:center;gap:6px;}
.mad-score-input label{font-size:11px;color:#94a3b8;}
.mad-score-input input{background:#0b1220;border:1px solid #334155;border-radius:6px;color:#e2e8f0;padding:5px 8px;width:64px;}
.mad-save-btn{background:#f59e0b;color:#111827;font-weight:bold;border:0;border-radius:6px;padding:7px 18px;cursor:pointer;font-size:12px;}
.mad-status-badge{display:inline-block;padding:3px 10px;border-radius:10px;font-size:10px;font-weight:bold;text-transform:uppercase;letter-spacing:.5px;margin-left:6px;}
.mad-on{background:#14532d;color:#86efac;}
.mad-off{background:#374151;color:#9ca3af;}
.mad-flash{background:#0c2b1a;border:1px solid #14532d;color:#86efac;border-radius:6px;padding:9px 12px;font-size:11px;margin-bottom:12px;}
.mad-autoban-banner{background:#450a0a;border:1px solid #7f1d1d;color:#fca5a5;border-radius:6px;padding:9px 12px;font-size:11px;margin-bottom:12px;}
</style>

<div class="mad-wrap">
    <h2><?php echo ADM_MULTI_ACCOUNT; ?> <span><?php echo ADM_DETECTION; ?></span>
        <span class="mad-status-badge <?php echo $settings['enabled'] ? 'mad-on' : 'mad-off'; ?>">
            <?php echo $settings['enabled'] ? ADM_MAD_ON : ADM_MAD_OFF; ?>
        </span>
        <?php if ($settings['enabled']): ?>
        <span class="mad-status-badge <?php echo $settings['auto_ban'] ? 'mad-on' : 'mad-off'; ?>">
            <?php echo ADM_MAD_AUTO_BAN; ?>: <?php echo $settings['auto_ban'] ? ADM_MAD_ON : ADM_MAD_OFF; ?>
        </span>
        <?php endif; ?>
    </h2>
    <p class="mad-intro"><?php echo ADM_HEURISTIC_CORRELATION_OF_ACCOUNT_PAIRS_BY_SH; ?> <b><?php echo ADM_RISK_SCORE_NOT_PROOF; ?></b><?php echo ADM_USE_IT_TO_PRIORITISE_WHICH_PAIRS_A_HUMAN_SHO; ?></p>

    <?php if ($flashMsg !== ''): ?>
        <div class="mad-flash"><?php echo e($flashMsg); ?></div>
    <?php endif; ?>

    <?php if (!empty($autoBannedNow)): ?>
        <div class="mad-autoban-banner">
            <b><?php echo ADM_MAD_AUTO_BAN_JUST_RAN; ?>:</b>
            <?php
            $labels = [];
            foreach ($autoBannedNow as $p) {
                $labels[] = e($p['name_a']) . ' &amp; ' . e($p['name_b']) . ' (' . (int)$p['score'] . '/100)';
            }
            echo implode(', ', $labels);
            ?>
        </div>
    <?php endif; ?>

    <div class="mad-panel">
        <?php if ($isFullAdmin): ?>
        <form method="post" action="../GameEngine/Admin/Mods/multiAccountSettings.php">
            <?php echo csrf_field(); ?>
            <div class="mad-panel-row">
                <div class="mad-toggle">
                    <input type="checkbox" id="mad_enabled" name="enabled" value="1" <?php echo $settings['enabled'] ? 'checked' : ''; ?>>
                    <label for="mad_enabled"><?php echo ADM_MAD_ENABLE_DETECTION; ?></label>
                </div>
                <div class="mad-toggle">
                    <input type="checkbox" id="mad_auto_ban" name="auto_ban" value="1" <?php echo $settings['auto_ban'] ? 'checked' : ''; ?>>
                    <label for="mad_auto_ban"><?php echo ADM_MAD_ENABLE_AUTO_BAN; ?></label>
                </div>
                <div class="mad-score-input">
                    <label><?php echo ADM_MAD_AUTO_BAN_THRESHOLD; ?></label>
                    <input type="number" name="auto_ban_score" min="1" max="100" value="<?php echo (int)$settings['auto_ban_score']; ?>">
                </div>
                <button type="submit" class="mad-save-btn"><?php echo ADM_SAVE; ?></button>
            </div>
            <p class="mad-intro" style="margin:8px 0 0;"><?php echo ADM_MAD_AUTO_BAN_HINT; ?></p>
        </form>
        <?php else: ?>
            <div class="mad-panel-row">
                <div><?php echo ADM_MAD_ENABLE_DETECTION; ?>: <b><?php echo $settings['enabled'] ? ADM_MAD_ON : ADM_MAD_OFF; ?></b></div>
                <div><?php echo ADM_MAD_ENABLE_AUTO_BAN; ?>: <b><?php echo $settings['auto_ban'] ? ADM_MAD_ON : ADM_MAD_OFF; ?></b></div>
                <div style="color:#64748b;font-size:11px;"><?php echo ADM_MAD_FULL_ADMIN_ONLY; ?></div>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!$settings['enabled']): ?>
        <div class="mad-warn"><?php echo ADM_MAD_DETECTION_DISABLED_NOTICE; ?></div>
    <?php endif; ?>

    <form method="get" action="admin.php" class="mad-filter">
        <input type="hidden" name="p" value="multiacc">
        <div>
            <label><?php echo ADM_WINDOW_DAYS; ?></label>
            <input type="number" name="days" min="1" max="365" value="<?php echo (int)$days; ?>">
        </div>
        <div>
            <label><?php echo ADM_MIN_SCORE; ?></label>
            <input type="number" name="min" min="0" max="100" value="<?php echo (int)$minScore; ?>">
        </div>
        <div>
            <label><?php echo ADM_FOCUS_ON_UID_OPTIONAL; ?></label>
            <input type="number" name="focus" min="0" value="<?php echo $focusUid ?: ''; ?>">
        </div>
        <button type="submit"><?php echo ADM_ANALYSE; ?></button>
        <?php if ($focusUid || $days != MultiAccount::WINDOW_DAYS || $minScore != MultiAccount::MIN_REPORT_SCORE): ?>
            <a class="reset" href="admin.php?p=multiacc"><?php echo ADM_RESET_2; ?></a>
        <?php endif; ?>
    </form>

    <?php if ($data['truncated']): ?>
        <div class="mad-warn"><?php echo ADM_ROW_CAP_REACHED_WHILE_SCANNING_LOGIN_HISTORY; ?></div>
    <?php endif; ?>

    <?php if (!$data['disabled']): ?>
    <div class="mad-meta">
        Window: last <?php echo (int)$data['window_days']; ?> days &nbsp;|&nbsp;
        scanned <?php echo (int)$data['scanned']['login_log']; ?> login-log +
        <?php echo (int)$data['scanned']['mad_session']; ?> fingerprint rows &nbsp;|&nbsp;
        <?php echo count($pairs); ?> suspicious pair<?php echo count($pairs) == 1 ? '' : 's'; ?>
        <?php if ($focusUid): ?>&nbsp;|&nbsp;focused on UID <?php echo (int)$focusUid; ?><?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ($data['disabled']): ?>
        <div class="mad-table"><div class="mad-empty"><?php echo ADM_MAD_DETECTION_DISABLED_NOTICE; ?></div></div>
    <?php elseif (empty($pairs)): ?>
        <div class="mad-table"><div class="mad-empty"><?php echo ADM_NO_ACCOUNT_PAIRS_AT_OR_ABOVE_THE_CURRENT_SCO; ?><br>
            <?php if ((int)$data['scanned']['mad_session'] === 0): ?>
                Tip: the User-Agent signal starts filling in only after players log in
                once with this feature deployed. IP &amp; login-time signals work on
                existing history immediately.
            <?php else: ?>
                Try lowering the minimum score or widening the window.
            <?php endif; ?>
        </div></div>
    <?php else: ?>
        <table class="mad-table">
            <thead>
                <tr>
                    <th style="width:80px;"><?php echo ADM_RISK; ?></th>
                    <th><?php echo ADM_ACCOUNT_A; ?></th>
                    <th><?php echo ADM_ACCOUNT_B; ?></th>
                    <th style="width:44%;"><?php echo ADM_WHY; ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($pairs as $p):
                $cls = $p['label'] === 'High' ? 'b-high' : ($p['label'] === 'Medium' ? 'b-med' : 'b-low');
                $barColor = $p['label'] === 'High' ? '#ef4444' : ($p['label'] === 'Medium' ? '#f59e0b' : '#3b82f6');
            ?>
                <tr>
                    <td>
                        <div class="mad-score" style="color:<?php echo $barColor; ?>;"><?php echo (int)$p['score']; ?></div>
                        <span class="badge <?php echo $cls; ?>"><?php echo e($p['label']); ?></span>
                        <div class="mad-bar"><i style="width:<?php echo (int)$p['score']; ?>%;background:<?php echo $barColor; ?>;"></i></div>
                        <a href="admin.php?p=multiaccPlayer&uid=<?php echo (int)$p['uid_a']; ?>&other=<?php echo (int)$p['uid_b']; ?>" style="display:inline-block;margin-top:6px;font-size:10px;color:#f59e0b;text-decoration:none;font-weight:bold;">&#8594; <?php echo ADM_MAD_VIEW_PAIR; ?></a>
                    </td>
                    <td class="mad-acc">
                        <a href="admin.php?p=player&uid=<?php echo (int)$p['uid_a']; ?>"><?php echo e($p['name_a']); ?></a>
                        <span class="uid">#<?php echo (int)$p['uid_a']; ?></span>
                        <span class="sub">
                            <a href="admin.php?p=userlogin&uid=<?php echo (int)$p['uid_a']; ?>"><?php echo ADM_LOGIN_LOG; ?></a>
                            <a href="admin.php?p=multiacc&focus=<?php echo (int)$p['uid_a']; ?>"><?php echo ADM_FOCUS; ?></a>
                        </span>
                    </td>
                    <td class="mad-acc">
                        <a href="admin.php?p=player&uid=<?php echo (int)$p['uid_b']; ?>"><?php echo e($p['name_b']); ?></a>
                        <span class="uid">#<?php echo (int)$p['uid_b']; ?></span>
                        <span class="sub">
                            <a href="admin.php?p=userlogin&uid=<?php echo (int)$p['uid_b']; ?>"><?php echo ADM_LOGIN_LOG; ?></a>
                            <a href="admin.php?p=multiacc&focus=<?php echo (int)$p['uid_b']; ?>"><?php echo ADM_FOCUS; ?></a>
                        </span>
                    </td>
                    <td class="chips">
                        <?php foreach ($p['reasons'] as $r): ?>
                            <span><?php echo e($r); ?></span>
                        <?php endforeach; ?>
                        <?php if (!empty($p['shared_ip_list'])): ?>
                            <div style="margin-top:5px;color:#475569;font-size:10px;">
                                IPs: <?php echo e(implode(', ', $p['shared_ip_list'])); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($p['trade_gross'] > 0): ?>
                            <div style="margin-top:3px;color:#475569;font-size:10px;">
                                transfer volume: <?php echo number_format((int)$p['trade_gross']); ?> res
                                <?php echo $p['trade_dir'] ? '(one-directional)' : '(two-way)'; ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
