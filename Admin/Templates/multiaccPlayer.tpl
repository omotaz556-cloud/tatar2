<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : Admin/Templates/multiaccPlayer.tpl                       ##
##  Type           : Admin page - Multi Account Admin (per-player dashboard)  ##
##  Project        : Novaterra                                                 ##
##  License        : Novaterra Project                                         ##
## --------------------------------------------------------------------------- ##
##  Gap analysis item #6: a single page that brings together, for one player:  ##
##    - account info + current ban status                                     ##
##    - auto-detected suspicious pairs (MultiAccount::riskPairs, focused)      ##
##    - manually-declared related accounts (RelatedAccountProtection)         ##
##    - IPs / device fingerprints seen (mad_session)                          ##
##    - recent login history (login_log)                                      ##
##    - blocked raid/transfer attempts against related accounts               ##
##    - admin actions: Ban / Unban, Mark as Related / Remove Relation         ##
##  This page does not introduce any new detection or blocking logic — it is  ##
##  a read/act surface over MultiAccount.php and RelatedAccountProtection.php ##
##  exactly as they already work.                                             ##
#################################################################################

// Access: admins (9) and multihunters (8) can view; only admins (9) act (the
// action forms below are hidden for multihunters, and the Mod re-checks
// access=9 server-side regardless).
if (!isset($_SESSION['access']) || $_SESSION['access'] < MULTIHUNTER) {
    echo '<p style="color:#f87171;padding:16px;">Access denied.</p>';
    return;
}

require_once(__DIR__ . '/../../GameEngine/RelatedAccountProtection.php');

$uid = isset($_GET['uid']) && ctype_digit((string)$_GET['uid']) ? (int)$_GET['uid'] : 0;
$otherUidHint = isset($_GET['other']) && ctype_digit((string)$_GET['other']) ? (int)$_GET['other'] : 0;
$isFullAdmin  = ($_SESSION['access'] ?? 0) >= 9;
$flashMsg     = isset($_GET['msg']) ? (string)$_GET['msg'] : '';

if ($uid <= 0) {
    echo '<p style="color:#f87171;padding:16px;">No account specified.</p>';
    return;
}

$link = $GLOBALS['link'];

// ---- Player + ban status ------------------------------------------------
$userRes = mysqli_query($link, "SELECT id, username, access, email FROM `" . TB_PREFIX . "users` WHERE id = " . $uid);
$player  = $userRes ? mysqli_fetch_assoc($userRes) : null;

if (!$player) {
    echo '<p style="color:#f87171;padding:16px;">Account #' . (int)$uid . ' not found.</p>';
    return;
}

$banRes = mysqli_query($link, "SELECT * FROM `" . TB_PREFIX . "banlist`
                                WHERE uid = " . $uid . " AND active = 1 ORDER BY id DESC LIMIT 1");
$activeBan = $banRes ? mysqli_fetch_assoc($banRes) : null;

$banHistoryRes = mysqli_query($link, "SELECT * FROM `" . TB_PREFIX . "banlist`
                                       WHERE uid = " . $uid . " ORDER BY id DESC LIMIT 20");

// ---- Auto-detected suspicious pairs, focused on this uid -----------------
$madData  = MultiAccount::riskPairs(['focus_uid' => $uid]);
$madPairs = $madData['pairs'];

// ---- Manually related accounts involving this uid -------------------------
$relatedSettings = RelatedAccountProtection::getSettings();
$allRelations = RelatedAccountProtection::listAll(500);
$myRelations = array_values(array_filter($allRelations, function ($r) use ($uid) {
    return $r['uid_a'] === $uid || $r['uid_b'] === $uid;
}));

// ---- IPs / device fingerprints (mad_session) -------------------------------
$fpRes = mysqli_query($link, "SELECT ip_text, ua_text, ua_hash, COUNT(*) AS hits, MAX(login_time) AS last_seen
                               FROM `" . TB_PREFIX . "mad_session`
                               WHERE uid = " . $uid . "
                               GROUP BY ip_text, ua_hash
                               ORDER BY last_seen DESC
                               LIMIT 25");
$fingerprints = [];
if ($fpRes) {
    while ($row = mysqli_fetch_assoc($fpRes)) {
        $fingerprints[] = $row;
    }
}

// ---- Recent login history (login_log) --------------------------------------
$loginRes = mysqli_query($link, "SELECT id, ip, `date` FROM `" . TB_PREFIX . "login_log`
                                  WHERE uid = " . $uid . " ORDER BY id DESC LIMIT 25");
$logins = [];
if ($loginRes) {
    while ($row = mysqli_fetch_assoc($loginRes)) {
        $logins[] = $row;
    }
}

// ---- Blocked raid/transfer attempts involving this uid as sender/attacker --
$allViolations = RelatedAccountProtection::listTransferViolations(500);
$myViolations = array_values(array_filter($allViolations, function ($v) use ($uid) {
    return $v['from_uid'] === $uid || $v['to_uid'] === $uid;
}));

function mapd_fmt_time($ts)
{
    return $ts > 0 ? date('Y-m-d H:i', (int)$ts) : '—';
}
?>
<style>
.mapd-wrap{color:#e2e8f0;font-family:Verdana,Arial,sans-serif;font-size:12px;padding:6px 4px 26px;}
.mapd-head{background:linear-gradient(135deg,#1e293b,#0f172a);border:1px solid #1f2937;border-radius:8px;padding:14px 18px;margin-bottom:14px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;}
.mapd-head h2{margin:0;font-size:18px;color:#fff;}
.mapd-head h2 a{color:#fff;text-decoration:none;}
.mapd-head .uid{color:#64748b;font-size:11px;margin-left:6px;}
.mapd-status{display:inline-block;padding:3px 10px;border-radius:10px;font-size:10px;font-weight:bold;text-transform:uppercase;letter-spacing:.5px;margin-left:6px;}
.mapd-banned{background:#7f1d1d;color:#fecaca;}
.mapd-active{background:#14532d;color:#86efac;}
.mapd-flash{background:#0c2b1a;border:1px solid #14532d;color:#86efac;border-radius:6px;padding:9px 12px;font-size:11px;margin-bottom:12px;}
.mapd-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;}
@media (max-width:900px){.mapd-grid{grid-template-columns:1fr;}}
.mapd-card{background:#111827;border:1px solid #1f2937;border-radius:8px;padding:14px 16px;}
.mapd-card h3{margin:0 0 10px;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:#94a3b8;display:flex;justify-content:space-between;align-items:center;}
.mapd-card h3 .count{background:#1e293b;color:#cbd5e1;padding:1px 8px;border-radius:10px;font-size:10px;}
.mapd-table{width:100%;border-collapse:collapse;font-size:11px;}
.mapd-table th{text-align:left;padding:6px 8px;color:#64748b;text-transform:uppercase;font-size:9px;border-bottom:1px solid #1f2937;}
.mapd-table td{padding:6px 8px;border-bottom:1px solid #14203a;}
.mapd-table tr:last-child td{border-bottom:0;}
.mapd-empty{color:#64748b;padding:14px 4px;text-align:center;font-size:11px;}
.badge{display:inline-block;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:bold;text-transform:uppercase;letter-spacing:.5px;}
.b-high{background:#7f1d1d;color:#fecaca;}
.b-med{background:#78350f;color:#fde68a;}
.b-low{background:#1e3a5f;color:#bfdbfe;}
.chips span{display:inline-block;background:#1e293b;color:#cbd5e1;border-radius:4px;padding:2px 7px;margin:2px 3px 0 0;font-size:10px;}
.mapd-form{display:flex;flex-wrap:wrap;gap:8px;align-items:flex-end;margin-top:10px;padding-top:10px;border-top:1px solid #1f2937;}
.mapd-form label{display:block;font-size:9px;text-transform:uppercase;color:#94a3b8;margin-bottom:3px;}
.mapd-form input{background:#0b1220;border:1px solid #334155;border-radius:5px;color:#e2e8f0;padding:5px 8px;font-size:11px;}
.mapd-btn{border:0;border-radius:5px;padding:6px 12px;font-size:11px;font-weight:bold;cursor:pointer;}
.mapd-btn-ban{background:#7f1d1d;color:#fecaca;}
.mapd-btn-unban{background:#14532d;color:#86efac;}
.mapd-btn-mark{background:#f59e0b;color:#111827;}
.mapd-btn-remove{background:#374151;color:#e2e8f0;}
.mapd-inline-form{display:inline;}
.mapd-back{color:#94a3b8;font-size:11px;text-decoration:none;}
.mapd-back:hover{color:#f59e0b;}
.mapd-notice{background:#422006;border:1px solid #92400e;color:#fbbf24;border-radius:6px;padding:8px 12px;font-size:11px;margin-bottom:12px;}
</style>

<div class="mapd-wrap">
    <p><a class="mapd-back" href="admin.php?p=multiacc">&#8592; <?php echo ADM_MAD_BACK_TO_OVERVIEW; ?></a></p>

    <div class="mapd-head">
        <h2>
            <a href="admin.php?p=player&uid=<?php echo (int)$player['id']; ?>"><?php echo e($player['username']); ?></a>
            <span class="uid">#<?php echo (int)$player['id']; ?></span>
            <?php if ($activeBan): ?>
                <span class="mapd-status mapd-banned"><?php echo ADM_MAD_BANNED; ?></span>
            <?php else: ?>
                <span class="mapd-status mapd-active"><?php echo ADM_MAD_ACTIVE; ?></span>
            <?php endif; ?>
        </h2>
        <div style="display:flex;gap:8px;">
            <a href="admin.php?p=userlogin&uid=<?php echo (int)$player['id']; ?>" style="color:#94a3b8;font-size:11px;text-decoration:none;"><?php echo ADM_LOGIN_LOG; ?> &#8594;</a>
        </div>
    </div>

    <?php if ($flashMsg !== ''): ?>
        <div class="mapd-flash"><?php echo e($flashMsg); ?></div>
    <?php endif; ?>

    <?php if (!$relatedSettings['enabled']): ?>
        <div class="mapd-notice"><?php echo ADM_MAD_RELATED_PROTECTION_OFF_NOTICE; ?></div>
    <?php endif; ?>

    <?php if ($isFullAdmin): ?>
    <div class="mapd-card" style="margin-bottom:14px;">
        <h3><?php echo ADM_MAD_ACCOUNT_ACTIONS; ?></h3>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
            <?php if ($activeBan): ?>
                <form method="post" action="../GameEngine/Admin/Mods/multiAccountPlayerActions.php" class="mapd-inline-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="do" value="unban">
                    <input type="hidden" name="uid" value="<?php echo (int)$player['id']; ?>">
                    <input type="hidden" name="target_uid" value="<?php echo (int)$player['id']; ?>">
                    <button type="submit" class="mapd-btn mapd-btn-unban"><?php echo ADM_MAD_UNBAN_ACCOUNT; ?></button>
                </form>
            <?php else: ?>
                <form method="post" action="../GameEngine/Admin/Mods/multiAccountPlayerActions.php" class="mapd-inline-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="do" value="ban">
                    <input type="hidden" name="uid" value="<?php echo (int)$player['id']; ?>">
                    <input type="hidden" name="target_uid" value="<?php echo (int)$player['id']; ?>">
                    <div style="display:inline-flex;gap:6px;align-items:flex-end;">
                        <div>
                            <label><?php echo ADM_MAD_BAN_REASON; ?></label>
                            <input type="text" name="reason" maxlength="30" placeholder="Multi-account" style="width:180px;">
                        </div>
                        <button type="submit" class="mapd-btn mapd-btn-ban"><?php echo ADM_MAD_BAN_ACCOUNT; ?></button>
                    </div>
                </form>
            <?php endif; ?>

            <form method="post" action="../GameEngine/Admin/Mods/multiAccountPlayerActions.php" class="mapd-form" style="border:0;padding-top:0;margin-top:0;">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="do" value="mark_related">
                <input type="hidden" name="uid" value="<?php echo (int)$player['id']; ?>">
                <div>
                    <label><?php echo ADM_MAD_RELATE_TO_UID; ?></label>
                    <input type="number" name="other_uid" min="1" value="<?php echo $otherUidHint ?: ''; ?>" style="width:90px;">
                </div>
                <div>
                    <label><?php echo ADM_MAD_REASON_OPTIONAL; ?></label>
                    <input type="text" name="reason" maxlength="255" style="width:160px;">
                </div>
                <button type="submit" class="mapd-btn mapd-btn-mark"><?php echo ADM_MAD_MARK_AS_RELATED; ?></button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="mapd-grid">

        <div class="mapd-card">
            <h3><?php echo ADM_MAD_SUSPECTED_PAIRS; ?> <span class="count"><?php echo count($madPairs); ?></span></h3>
            <?php if ($madData['disabled']): ?>
                <div class="mapd-empty"><?php echo ADM_MAD_DETECTION_DISABLED_NOTICE; ?></div>
            <?php elseif (empty($madPairs)): ?>
                <div class="mapd-empty"><?php echo ADM_MAD_NO_SUSPECTED_PAIRS; ?></div>
            <?php else: ?>
                <table class="mapd-table">
                    <thead><tr>
                        <th><?php echo ADM_RISK; ?></th>
                        <th><?php echo ADM_MAD_OTHER_ACCOUNT; ?></th>
                        <th><?php echo ADM_WHY; ?></th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($madPairs as $p):
                        $otherUid  = $p['uid_a'] === $uid ? $p['uid_b'] : $p['uid_a'];
                        $otherName = $p['uid_a'] === $uid ? $p['name_b'] : $p['name_a'];
                        $cls = $p['label'] === 'High' ? 'b-high' : ($p['label'] === 'Medium' ? 'b-med' : 'b-low');
                    ?>
                        <tr>
                            <td><span class="badge <?php echo $cls; ?>"><?php echo (int)$p['score']; ?></span></td>
                            <td><a href="admin.php?p=multiaccPlayer&uid=<?php echo (int)$otherUid; ?>" style="color:#e2e8f0;font-weight:bold;text-decoration:none;"><?php echo e($otherName); ?></a> <span style="color:#475569;">#<?php echo (int)$otherUid; ?></span></td>
                            <td class="chips"><?php foreach ($p['reasons'] as $r): ?><span><?php echo e($r); ?></span><?php endforeach; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="mapd-card">
            <h3><?php echo ADM_MAD_RELATED_ACCOUNTS; ?> <span class="count"><?php echo count($myRelations); ?></span></h3>
            <?php if (empty($myRelations)): ?>
                <div class="mapd-empty"><?php echo ADM_MAD_NO_RELATED_ACCOUNTS; ?></div>
            <?php else: ?>
                <table class="mapd-table">
                    <thead><tr>
                        <th><?php echo ADM_MAD_OTHER_ACCOUNT; ?></th>
                        <th><?php echo ADM_MAD_REASON; ?></th>
                        <th><?php echo ADM_MAD_ADDED; ?></th>
                        <?php if ($isFullAdmin): ?><th></th><?php endif; ?>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($myRelations as $r):
                        $otherUid  = $r['uid_a'] === $uid ? $r['uid_b'] : $r['uid_a'];
                        $otherName = $r['uid_a'] === $uid ? $r['username_b'] : $r['username_a'];
                    ?>
                        <tr>
                            <td><a href="admin.php?p=multiaccPlayer&uid=<?php echo (int)$otherUid; ?>" style="color:#e2e8f0;font-weight:bold;text-decoration:none;"><?php echo e($otherName); ?></a> <span style="color:#475569;">#<?php echo (int)$otherUid; ?></span></td>
                            <td style="color:#94a3b8;"><?php echo e($r['reason'] ?: '—'); ?></td>
                            <td style="color:#64748b;"><?php echo mapd_fmt_time($r['added']); ?></td>
                            <?php if ($isFullAdmin): ?>
                            <td>
                                <form method="post" action="../GameEngine/Admin/Mods/multiAccountPlayerActions.php" class="mapd-inline-form">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="do" value="remove_related">
                                    <input type="hidden" name="uid" value="<?php echo (int)$player['id']; ?>">
                                    <input type="hidden" name="rel_id" value="<?php echo (int)$r['id']; ?>">
                                    <button type="submit" class="mapd-btn mapd-btn-remove" style="padding:3px 8px;font-size:10px;"><?php echo ADM_MAD_REMOVE; ?></button>
                                </form>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="mapd-card">
            <h3><?php echo ADM_MAD_IPS_AND_DEVICES; ?> <span class="count"><?php echo count($fingerprints); ?></span></h3>
            <?php if (empty($fingerprints)): ?>
                <div class="mapd-empty"><?php echo ADM_MAD_NO_FINGERPRINTS; ?></div>
            <?php else: ?>
                <table class="mapd-table">
                    <thead><tr>
                        <th><?php echo ADM_IP_ADDRESS; ?></th>
                        <th><?php echo ADM_MAD_HITS; ?></th>
                        <th><?php echo ADM_MAD_LAST_SEEN; ?></th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($fingerprints as $f): ?>
                        <tr>
                            <td style="font-family:monospace;"><?php echo e($f['ip_text']); ?></td>
                            <td><?php echo (int)$f['hits']; ?></td>
                            <td style="color:#64748b;"><?php echo mapd_fmt_time($f['last_seen']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="mapd-card">
            <h3><?php echo ADM_MAD_BLOCKED_ATTEMPTS; ?> <span class="count"><?php echo count($myViolations); ?></span></h3>
            <?php if (empty($myViolations)): ?>
                <div class="mapd-empty"><?php echo ADM_MAD_NO_BLOCKED_ATTEMPTS; ?></div>
            <?php else: ?>
                <table class="mapd-table">
                    <thead><tr>
                        <th><?php echo ADM_MAD_FROM; ?></th>
                        <th><?php echo ADM_MAD_TO; ?></th>
                        <th><?php echo ADM_MAD_RESOURCES; ?></th>
                        <th><?php echo ADM_MAD_WHEN; ?></th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($myViolations as $v): ?>
                        <tr>
                            <td><?php echo e($v['username_from']); ?></td>
                            <td><?php echo e($v['username_to']); ?></td>
                            <td><?php echo number_format($v['total']); ?></td>
                            <td style="color:#64748b;"><?php echo mapd_fmt_time($v['time']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="mapd-card">
            <h3><?php echo ADM_MAD_RECENT_LOGINS; ?> <span class="count"><?php echo count($logins); ?></span></h3>
            <?php if (empty($logins)): ?>
                <div class="mapd-empty"><?php echo ADM_MAD_NO_LOGIN_RECORDS; ?></div>
            <?php else: ?>
                <table class="mapd-table">
                    <thead><tr>
                        <th><?php echo ADM_IP_ADDRESS; ?></th>
                        <th><?php echo ADM_MAD_WHEN; ?></th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($logins as $l): ?>
                        <tr>
                            <td style="font-family:monospace;"><?php echo e($l['ip']); ?></td>
                            <td style="color:#64748b;"><?php echo e((string)$l['date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="mapd-card">
            <h3><?php echo ADM_MAD_BAN_HISTORY; ?></h3>
            <?php if (!$banHistoryRes || mysqli_num_rows($banHistoryRes) === 0): ?>
                <div class="mapd-empty"><?php echo ADM_MAD_NO_BAN_HISTORY; ?></div>
            <?php else: ?>
                <table class="mapd-table">
                    <thead><tr>
                        <th><?php echo ADM_MAD_REASON; ?></th>
                        <th><?php echo ADM_MAD_ADDED; ?></th>
                        <th><?php echo ADM_MAD_STATUS; ?></th>
                    </tr></thead>
                    <tbody>
                    <?php while ($b = mysqli_fetch_assoc($banHistoryRes)): ?>
                        <tr>
                            <td><?php echo e($b['reason']); ?></td>
                            <td style="color:#64748b;"><?php echo mapd_fmt_time($b['time']); ?></td>
                            <td><?php echo ((int)$b['active'] === 1) ? ADM_MAD_ACTIVE : ADM_MAD_LIFTED; ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>
</div>
