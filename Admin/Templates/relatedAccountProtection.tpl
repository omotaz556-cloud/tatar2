<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : relatedAccountProtection.tpl                             ##
##  Type           : Admin Panel Frontend for the related-account raid-       ##
##                    blocking system                                         ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow                                                    ##
##  Project        : Novaterra                                                 ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################

if (!isset($_SESSION['access']) || $_SESSION['access'] < ADMIN) {
    echo '<p style="color:#f87171;padding:16px;">Access denied.</p>';
    return;
}

include_once(__DIR__ . '/../../GameEngine/RelatedAccountProtection.php');

$msg       = isset($_GET['msg']) ? (string) $_GET['msg'] : '';
$rapSettings = RelatedAccountProtection::getSettings();
$relations = RelatedAccountProtection::listAll(300);
$transferViolations = RelatedAccountProtection::listTransferViolations(300);
?>
<style>
.rap-wrap{color:#e2e8f0;font-family:Verdana,Arial,sans-serif;font-size:12px;padding:6px 4px 26px;}
.rap-wrap h2{font-size:18px;margin:0 0 4px;color:#fff;}
.rap-wrap h2 span{color:#f59e0b;}
.rap-intro{color:#94a3b8;font-size:11px;margin:0 0 14px;max-width:860px;line-height:1.5;}
.rap-msg{background:#14532d;border:1px solid #166534;color:#bbf7d0;border-radius:6px;padding:8px 12px;font-size:11px;margin-bottom:14px;}
.rap-note{background:#1e293b;border:1px solid #334155;color:#94a3b8;border-radius:6px;padding:8px 12px;font-size:10.5px;margin-bottom:14px;line-height:1.5;}
.rap-card{background:#111827;border:1px solid #1f2937;border-radius:8px;padding:14px 16px;margin-bottom:18px;}
.rap-card h3{margin:0 0 4px;font-size:13px;color:#fff;}
.rap-card .rap-desc{color:#64748b;font-size:10px;margin:0 0 12px;max-width:760px;line-height:1.5;}
.rap-toggle-row{display:flex;align-items:center;gap:14px;flex-wrap:wrap;}
.rap-radio{display:flex;align-items:center;gap:6px;font-size:11px;color:#cbd5e1;}
.rap-settings-row{display:flex;flex-wrap:wrap;gap:16px;align-items:flex-end;margin-top:12px;}
.rap-settings-row button{background:#f59e0b;color:#111827;font-weight:bold;border:0;border-radius:6px;padding:9px 18px;cursor:pointer;}
.rap-add{display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;}
.rap-add label{display:block;font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:#94a3b8;margin-bottom:4px;}
.rap-add input[type=text]{background:#0b1220;border:1px solid #334155;border-radius:6px;color:#e2e8f0;padding:7px 9px;width:180px;}
.rap-add button{background:#f59e0b;color:#111827;font-weight:bold;border:0;border-radius:6px;padding:9px 18px;cursor:pointer;}
.rap-table{width:100%;border-collapse:collapse;background:#0b1220;border:1px solid #1f2937;border-radius:8px;}
.rap-table th{background:#111827;text-align:left;padding:7px 7px;font-size:9px;text-transform:uppercase;letter-spacing:.3px;color:#94a3b8;border-bottom:1px solid #1f2937;white-space:nowrap;}
.rap-table td{padding:6px 7px;border-bottom:1px solid #14203a;vertical-align:middle;font-size:11px;}
.rap-table tr:hover td{background:#0f1a30;}
.rap-scroll{overflow-x:auto;}
.rap-empty{padding:22px;text-align:center;color:#64748b;}
.rap-del-btn{background:#7f1d1d;color:#fecaca;border:1px solid #991b1b;border-radius:5px;padding:4px 10px;font-size:10px;cursor:pointer;}
.rap-del-btn:hover{background:#991b1b;}
.rap-badge{display:inline-block;padding:2px 8px;border-radius:999px;font-size:9px;font-weight:bold;text-transform:uppercase;letter-spacing:.4px;background:#7f1d1d;color:#fecaca;}
</style>

<div class="rap-wrap">
    <h2><?php echo ADMIN_GOLD; ?><span><?php echo ADMIN_RELATED_ACCOUNT_PROTECTION; ?></span></h2>
    <p class="rap-intro"><?php echo ADM_RAP_INTRO; ?></p>

    <?php if ($msg !== ''): ?>
        <div class="rap-msg"><?php echo e($msg); ?></div>
    <?php endif; ?>

    <div class="rap-note"><?php echo ADM_RAP_PRIORITY_NOTE; ?></div>

    <!-- Enable settings -->
    <div class="rap-card">
        <h3><?php echo ADM_RAP_SETTINGS_TITLE; ?></h3>
        <p class="rap-desc"><?php echo ADM_RAP_SETTINGS_DESC; ?></p>
        <form method="post" action="../GameEngine/Admin/Mods/relatedAccountProtectionAdmin.php">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="do" value="save_settings">

            <div class="rap-toggle-row">
                <label class="rap-radio">
                    <input type="radio" name="enabled" value="1" <?php echo $rapSettings['enabled'] ? 'checked' : ''; ?>>
                    <?php echo ADM_RAP_ENABLED; ?>
                </label>
                <label class="rap-radio">
                    <input type="radio" name="enabled" value="0" <?php echo !$rapSettings['enabled'] ? 'checked' : ''; ?>>
                    <?php echo ADM_RAP_DISABLED; ?>
                </label>
            </div>

            <div class="rap-toggle-row" style="margin-top:12px;padding-top:12px;border-top:1px solid #1f2937;">
                <label class="rap-radio">
                    <input type="checkbox" name="auto_ban_on_attempt" value="1" <?php echo $rapSettings['auto_ban_on_attempt'] ? 'checked' : ''; ?>>
                    <strong><?php echo ADM_RAP_AUTOBAN_TITLE; ?></strong>
                </label>
                <div class="rap-settings-row" style="margin:0;">
                    <button type="submit"><?php echo ADM_RAP_SAVE; ?></button>
                </div>
            </div>
            <p class="rap-desc" style="margin:6px 0 0;max-width:760px;"><?php echo ADM_RAP_AUTOBAN_DESC; ?></p>
            <?php if ($rapSettings['enabled'] && $rapSettings['auto_ban_on_attempt']): ?>
                <div class="rap-note" style="margin-top:10px;"><?php echo ADM_RAP_AUTOBAN_ACTIVE_NOTE; ?></div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Manual relation (admin only - no self-service side) -->
    <div class="rap-card">
        <h3><?php echo ADM_RAP_ADD_TITLE; ?></h3>
        <p class="rap-desc"><?php echo ADM_RAP_ADD_DESC; ?></p>
        <form method="post" action="../GameEngine/Admin/Mods/relatedAccountProtectionAdmin.php" class="rap-add">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="do" value="add_relation">
            <div>
                <label><?php echo ADM_RAP_USERNAME_A; ?></label>
                <input type="text" name="username_a" placeholder="<?php echo ADM_RAP_USERNAME_A; ?>" required>
            </div>
            <div>
                <label><?php echo ADM_RAP_USERNAME_B; ?></label>
                <input type="text" name="username_b" placeholder="<?php echo ADM_RAP_USERNAME_B; ?>" required>
            </div>
            <div>
                <label><?php echo ADM_RAP_REASON; ?></label>
                <input type="text" name="reason" placeholder="<?php echo ADM_RAP_REASON; ?>">
            </div>
            <button type="submit"><?php echo ADM_RAP_RELATE_BTN; ?></button>
        </form>
    </div>

    <!-- All current relations -->
    <div class="rap-card" style="padding:0;">
        <div style="padding:14px 16px 0;">
            <h3><?php echo ADM_RAP_ALL_TITLE; ?></h3>
        </div>
        <?php if (empty($relations)): ?>
            <div class="rap-empty"><?php echo ADM_RAP_NO_RELATIONS; ?></div>
        <?php else: ?>
            <div class="rap-scroll">
            <table class="rap-table">
                <thead>
                    <tr>
                        <th><?php echo ADM_RAP_USERNAME_A; ?></th>
                        <th><?php echo ADM_RAP_USERNAME_B; ?></th>
                        <th><?php echo ADM_RAP_REASON; ?></th>
                        <th><?php echo ADM_RAP_ADDED; ?></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($relations as $row): ?>
                    <tr>
                        <td><?php echo e($row['username_a']); ?></td>
                        <td><?php echo e($row['username_b']); ?></td>
                        <td style="color:#94a3b8;"><?php echo $row['reason'] !== '' ? e($row['reason']) : '&ndash;'; ?></td>
                        <td class="num" style="color:#94a3b8;"><?php echo $row['added'] ? date('Y-m-d H:i', (int) $row['added']) : '&ndash;'; ?></td>
                        <td><span class="rap-badge"><?php echo ADM_RAP_BADGE_BLOCKED; ?></span></td>
                        <td>
                            <form method="post" action="../GameEngine/Admin/Mods/relatedAccountProtectionAdmin.php" onsubmit="return confirm('<?php echo ADM_RAP_CONFIRM_REMOVE; ?>');">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="do" value="remove_relation">
                                <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                <button type="submit" class="rap-del-btn"><?php echo ADM_RAP_REMOVE; ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Blocked marketplace transfer attempts between related accounts -->
    <div class="rap-card" style="padding:0;">
        <div style="padding:14px 16px 0;">
            <h3><?php echo ADM_RAP_TRANSFERS_TITLE; ?></h3>
            <p class="rap-desc"><?php echo ADM_RAP_TRANSFERS_DESC; ?></p>
        </div>
        <?php if (empty($transferViolations)): ?>
            <div class="rap-empty"><?php echo ADM_RAP_NO_TRANSFER_VIOLATIONS; ?></div>
        <?php else: ?>
            <div class="rap-scroll">
            <table class="rap-table">
                <thead>
                    <tr>
                        <th><?php echo ADM_RAP_USERNAME_A; ?></th>
                        <th><?php echo ADM_RAP_USERNAME_B; ?></th>
                        <th><?php echo ADM_RAP_ATTEMPTED_AMOUNT; ?></th>
                        <th><?php echo ADM_RAP_ADDED; ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($transferViolations as $row): ?>
                    <tr>
                        <td><?php echo e($row['username_from']); ?></td>
                        <td><?php echo e($row['username_to']); ?></td>
                        <td class="num"><?php echo number_format($row['total']); ?></td>
                        <td class="num" style="color:#94a3b8;"><?php echo $row['time'] ? date('Y-m-d H:i', (int) $row['time']) : '&ndash;'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>
</div>
