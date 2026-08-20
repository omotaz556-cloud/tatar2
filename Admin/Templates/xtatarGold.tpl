<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : xtatarGold.tpl                                           ##
##  Type           : Admin Panel Frontend for X-Tatar activity free gold      ##
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

include_once(__DIR__ . '/../../GameEngine/XTatarGold.php');

$msg = isset($_GET['msg']) ? (string) $_GET['msg'] : '';
$lookupUser = isset($_GET['lookup']) ? trim((string) $_GET['lookup']) : '';
$lookupUid = 0;
$lookupPoints = null;
$lookupLog = [];

if ($lookupUser !== '') {
    global $database;
    $lookupUid = (int) $database->getUserField($lookupUser, 'id', 1);
    if ($lookupUid > 3) {
        $lookupPoints = XTatarGold::pointBalance($lookupUid);
        $lookupLog = XTatarGold::recentLog(20, $lookupUid);
    }
}

$settings = XTatarGold::settings();
$enabled = (int) $settings['enabled'] === 1;
$log = XTatarGold::recentLog(40);
$topEarners = XTatarGold::topEarners(15);
?>
<style>
.xg-wrap{color:#e2e8f0;font-family:Verdana,Arial,sans-serif;font-size:12px;padding:6px 4px 26px;}
.xg-wrap h2{font-size:18px;margin:0 0 4px;color:#fff;}
.xg-wrap h2 span{color:#f59e0b;}
.xg-intro{color:#94a3b8;font-size:11px;margin:0 0 14px;max-width:860px;line-height:1.5;}
.xg-msg{background:#14532d;border:1px solid #166534;color:#bbf7d0;border-radius:6px;padding:8px 12px;font-size:11px;margin-bottom:14px;}
.xg-card{background:#111827;border:1px solid #1f2937;border-radius:8px;padding:14px 16px;margin-bottom:18px;}
.xg-card h3{margin:0 0 4px;font-size:13px;color:#fff;}
.xg-card .xg-desc{color:#64748b;font-size:10px;margin:0 0 12px;max-width:760px;line-height:1.5;}
.xg-toggle-row{display:flex;align-items:center;gap:14px;flex-wrap:wrap;}
.xg-radio{display:flex;align-items:center;gap:6px;font-size:11px;color:#cbd5e1;}
.xg-add{display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;}
.xg-add label{display:block;font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:#94a3b8;margin-bottom:4px;}
.xg-add input[type=text],.xg-add input[type=number]{background:#0b1220;border:1px solid #334155;border-radius:6px;color:#e2e8f0;padding:7px 9px;}
.xg-add input.user{width:180px;}
.xg-add input.num{width:110px;}
.xg-add input.note{width:200px;}
.xg-add input.secret{width:260px;}
.xg-add button{background:#f59e0b;color:#111827;font-weight:bold;border:0;border-radius:6px;padding:9px 18px;cursor:pointer;}
.xg-table{width:100%;border-collapse:collapse;background:#0b1220;border:1px solid #1f2937;border-radius:8px;}
.xg-table th{background:#111827;text-align:left;padding:7px 7px;font-size:9px;text-transform:uppercase;letter-spacing:.3px;color:#94a3b8;border-bottom:1px solid #1f2937;white-space:nowrap;}
.xg-table td{padding:6px 7px;border-bottom:1px solid #14203a;vertical-align:middle;font-size:11px;}
.xg-table tr:hover td{background:#0f1a30;}
.xg-scroll{overflow-x:auto;}
.xg-empty{padding:22px;text-align:center;color:#64748b;}
.num{font-variant-numeric:tabular-nums;}
.xg-gold{color:#fde68a;font-weight:bold;}
.xg-pos{color:#86efac;}
.xg-neg{color:#fca5a5;}
.xg-result-card{background:#0b1220;border:1px solid #334155;border-radius:8px;padding:12px 14px;margin-top:12px;}
.xg-result-card .label{color:#64748b;text-transform:uppercase;font-size:9px;letter-spacing:.5px;}
</style>

<div class="xg-wrap">
    <h2><?php echo ADMIN_GOLD; ?><span><?php echo ADM_XG_TITLE; ?></span></h2>
    <p class="xg-intro"><?php echo ADM_XG_INTRO; ?></p>

    <?php if ($msg !== ''): ?>
        <div class="xg-msg"><?php echo e($msg); ?></div>
    <?php endif; ?>

    <!-- Settings -->
    <div class="xg-card">
        <h3><?php echo ADM_XG_SETTINGS_TITLE; ?></h3>
        <p class="xg-desc"><?php echo ADM_XG_SETTINGS_DESC; ?></p>
        <form method="post" action="../GameEngine/Admin/Mods/xtatarGoldAdmin.php" class="xg-add">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="do" value="update_settings">

            <div style="width:100%;">
                <div class="xg-toggle-row" style="margin-bottom:10px;">
                    <label class="xg-radio">
                        <input type="radio" name="enabled" value="1" <?php echo $enabled ? 'checked' : ''; ?>>
                        <?php echo ADM_XG_ENABLED; ?>
                    </label>
                    <label class="xg-radio">
                        <input type="radio" name="enabled" value="0" <?php echo !$enabled ? 'checked' : ''; ?>>
                        <?php echo ADM_XG_DISABLED; ?>
                    </label>
                </div>
            </div>

            <div>
                <label><?php echo ADM_XG_POINTS_PER_GOLD; ?></label>
                <input class="num" type="number" name="points_per_gold" min="1" value="<?php echo (int) $settings['points_per_gold']; ?>" required>
            </div>
            <div>
                <label><?php echo ADM_XG_DAILY_LOGIN_POINTS; ?></label>
                <input class="num" type="number" name="daily_login_points" min="0" value="<?php echo (int) $settings['daily_login_points']; ?>" required>
            </div>
            <div>
                <label><?php echo ADM_XG_DAILY_CAP_POINTS; ?></label>
                <input class="num" type="number" name="daily_cap_points" min="0" value="<?php echo (int) $settings['daily_cap_points']; ?>" required>
            </div>
            <div>
                <label><?php echo ADM_XG_WEBHOOK_SECRET; ?></label>
                <input class="secret" type="text" name="webhook_secret" maxlength="128" value="<?php echo e($settings['webhook_secret']); ?>" placeholder="<?php echo ADM_XG_WEBHOOK_SECRET_PLACEHOLDER; ?>">
            </div>

            <button type="submit"><?php echo ADM_XG_SAVE; ?></button>
        </form>
        <p class="xg-desc" style="margin-top:10px;"><?php echo ADM_XG_WEBHOOK_HELP; ?></p>
    </div>

    <!-- Manual point adjustment -->
    <div class="xg-card">
        <h3><?php echo ADM_XG_ADJUST_TITLE; ?></h3>
        <p class="xg-desc"><?php echo ADM_XG_ADJUST_DESC; ?></p>
        <form method="post" action="../GameEngine/Admin/Mods/xtatarGoldAdmin.php" class="xg-add">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="do" value="adjust_points">
            <div>
                <label><?php echo ADM_XG_USERNAME; ?></label>
                <input class="user" type="text" name="username" value="<?php echo e($lookupUser); ?>" required>
            </div>
            <div>
                <label><?php echo ADM_XG_POINTS_DELTA; ?></label>
                <input class="num" type="number" name="delta" value="100" required>
            </div>
            <div>
                <label><?php echo ADM_XG_NOTE_OPTIONAL; ?></label>
                <input class="note" type="text" name="note" maxlength="255">
            </div>
            <button type="submit"><?php echo ADM_XG_ADJUST_BTN; ?></button>
        </form>

        <?php if ($lookupUser !== ''): ?>
            <?php if ($lookupUid <= 3): ?>
                <div class="xg-result-card"><?php echo ADM_XG_NO_PLAYER_FOUND; ?></div>
            <?php else: ?>
                <div class="xg-result-card">
                    <div style="display:flex;gap:32px;flex-wrap:wrap;margin-bottom:10px;">
                        <div>
                            <div class="label"><?php echo ADM_XG_USERNAME; ?></div>
                            <div><?php echo e($lookupUser); ?></div>
                        </div>
                        <div>
                            <div class="label"><?php echo ADM_XG_CURRENT_POINTS; ?></div>
                            <div class="xg-gold num"><?php echo number_format((int) $lookupPoints); ?></div>
                        </div>
                    </div>
                    <div class="label" style="margin-bottom:6px;"><?php echo ADM_XG_PLAYER_HISTORY; ?></div>
                    <?php if (empty($lookupLog)): ?>
                        <div style="color:#64748b;"><?php echo ADM_XG_NO_HISTORY; ?></div>
                    <?php else: ?>
                        <div class="xg-scroll">
                        <table class="xg-table">
                            <thead>
                                <tr><th><?php echo ADM_XG_WHEN; ?></th><th><?php echo ADM_XG_TYPE; ?></th><th><?php echo ADM_XG_POINTS; ?></th><th><?php echo ADM_XG_GOLD; ?></th><th><?php echo ADM_XG_SOURCE; ?></th></tr>
                            </thead>
                            <tbody>
                            <?php foreach ($lookupLog as $row): ?>
                                <tr>
                                    <td class="num" style="color:#94a3b8;"><?php echo $row['time'] ? date('Y-m-d H:i', (int) $row['time']) : '&ndash;'; ?></td>
                                    <td><?php echo e($row['type']); ?></td>
                                    <td class="num"><?php echo number_format((int) $row['points']); ?></td>
                                    <td class="num xg-gold"><?php echo (int) $row['gold'] > 0 ? number_format((int) $row['gold']) : '&ndash;'; ?></td>
                                    <td><?php echo e($row['source']); ?></td>
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

    <!-- Top earners -->
    <div class="xg-card" style="padding:0;">
        <div style="padding:14px 16px 0;">
            <h3><?php echo ADM_XG_TOP_EARNERS; ?></h3>
        </div>
        <?php if (empty($topEarners)): ?>
            <div class="xg-empty"><?php echo ADM_XG_NO_DATA; ?></div>
        <?php else: ?>
            <div class="xg-scroll">
            <table class="xg-table">
                <thead>
                    <tr>
                        <th><?php echo ADM_XG_USERNAME; ?></th>
                        <th><?php echo ADM_XG_CURRENT_POINTS; ?></th>
                        <th><?php echo ADM_XG_TOTAL_EARNED; ?></th>
                        <th><?php echo ADM_XG_TOTAL_GOLD_CONVERTED; ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($topEarners as $row): ?>
                    <tr>
                        <td><?php echo e($row['username']); ?></td>
                        <td class="num"><?php echo number_format((int) $row['points']); ?></td>
                        <td class="num"><?php echo number_format((int) $row['total_earned']); ?></td>
                        <td class="num xg-gold"><?php echo number_format((int) $row['total_converted_gold']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recent activity log -->
    <div class="xg-card" style="padding:0;">
        <div style="padding:14px 16px 0;">
            <h3><?php echo ADM_XG_RECENT_LOG; ?></h3>
        </div>
        <?php if (empty($log)): ?>
            <div class="xg-empty"><?php echo ADM_XG_NO_HISTORY; ?></div>
        <?php else: ?>
            <div class="xg-scroll">
            <table class="xg-table">
                <thead>
                    <tr>
                        <th><?php echo ADM_XG_WHEN; ?></th>
                        <th><?php echo ADM_XG_USERNAME; ?></th>
                        <th><?php echo ADM_XG_TYPE; ?></th>
                        <th><?php echo ADM_XG_POINTS; ?></th>
                        <th><?php echo ADM_XG_GOLD; ?></th>
                        <th><?php echo ADM_XG_SOURCE; ?></th>
                        <th><?php echo ADM_NOTE; ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($log as $row):
                    $points = (int) $row['points'];
                    $pointsClass = $row['type'] === 'admin_adjust' && $points < 0 ? 'xg-neg' : 'xg-pos';
                ?>
                    <tr>
                        <td class="num" style="color:#94a3b8;"><?php echo $row['time'] ? date('Y-m-d H:i', (int) $row['time']) : '&ndash;'; ?></td>
                        <td><?php echo e($row['username'] ?? ('#' . $row['uid'])); ?></td>
                        <td><?php echo e($row['type']); ?></td>
                        <td class="num <?php echo $pointsClass; ?>"><?php echo number_format($points); ?></td>
                        <td class="num xg-gold"><?php echo (int) $row['gold'] > 0 ? number_format((int) $row['gold']) : '&ndash;'; ?></td>
                        <td><?php echo e($row['source']); ?></td>
                        <td style="color:#94a3b8;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo $row['note'] !== '' ? e($row['note']) : '&ndash;'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>
</div>
