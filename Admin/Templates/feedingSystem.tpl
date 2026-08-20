<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : feedingSystem.tpl                                        ##
##  Type           : Admin Panel Frontend for the linked-accounts (feeding)   ##
##                    system                                                   ##
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

include_once(__DIR__ . '/../../GameEngine/FeedingSystem.php');

$msg      = isset($_GET['msg']) ? (string) $_GET['msg'] : '';
$settings = FeedingSystem::getSettings();
$links    = FeedingSystem::listAll(300);
?>
<style>
.fs-wrap{color:#e2e8f0;font-family:Verdana,Arial,sans-serif;font-size:12px;padding:6px 4px 26px;}
.fs-wrap h2{font-size:18px;margin:0 0 4px;color:#fff;}
.fs-wrap h2 span{color:#f59e0b;}
.fs-intro{color:#94a3b8;font-size:11px;margin:0 0 14px;max-width:860px;line-height:1.5;}
.fs-msg{background:#14532d;border:1px solid #166534;color:#bbf7d0;border-radius:6px;padding:8px 12px;font-size:11px;margin-bottom:14px;}
.fs-warn{background:#78350f;border:1px solid #92400e;color:#fde68a;border-radius:6px;padding:8px 12px;font-size:11px;margin-bottom:14px;}
.fs-note{background:#1e293b;border:1px solid #334155;color:#94a3b8;border-radius:6px;padding:8px 12px;font-size:10.5px;margin-bottom:14px;line-height:1.5;}
.fs-card{background:#111827;border:1px solid #1f2937;border-radius:8px;padding:14px 16px;margin-bottom:18px;}
.fs-card h3{margin:0 0 4px;font-size:13px;color:#fff;}
.fs-card .fs-desc{color:#64748b;font-size:10px;margin:0 0 12px;max-width:760px;line-height:1.5;}
.fs-toggle-row{display:flex;align-items:center;gap:14px;flex-wrap:wrap;}
.fs-radio{display:flex;align-items:center;gap:6px;font-size:11px;color:#cbd5e1;}
.fs-settings-row{display:flex;flex-wrap:wrap;gap:16px;align-items:flex-end;margin-top:12px;}
.fs-settings-row label{display:block;font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:#94a3b8;margin-bottom:4px;}
.fs-settings-row input[type=number]{background:#0b1220;border:1px solid #334155;border-radius:6px;color:#e2e8f0;padding:7px 9px;width:110px;}
.fs-settings-row button{background:#f59e0b;color:#111827;font-weight:bold;border:0;border-radius:6px;padding:9px 18px;cursor:pointer;}
.fs-add{display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;}
.fs-add label{display:block;font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:#94a3b8;margin-bottom:4px;}
.fs-add input[type=text]{background:#0b1220;border:1px solid #334155;border-radius:6px;color:#e2e8f0;padding:7px 9px;width:180px;}
.fs-add button{background:#f59e0b;color:#111827;font-weight:bold;border:0;border-radius:6px;padding:9px 18px;cursor:pointer;}
.fs-table{width:100%;border-collapse:collapse;background:#0b1220;border:1px solid #1f2937;border-radius:8px;}
.fs-table th{background:#111827;text-align:left;padding:7px 7px;font-size:9px;text-transform:uppercase;letter-spacing:.3px;color:#94a3b8;border-bottom:1px solid #1f2937;white-space:nowrap;}
.fs-table td{padding:6px 7px;border-bottom:1px solid #14203a;vertical-align:middle;font-size:11px;}
.fs-table tr:hover td{background:#0f1a30;}
.fs-scroll{overflow-x:auto;}
.fs-empty{padding:22px;text-align:center;color:#64748b;}
.fs-del-btn{background:#7f1d1d;color:#fecaca;border:1px solid #991b1b;border-radius:5px;padding:4px 10px;font-size:10px;cursor:pointer;}
.fs-del-btn:hover{background:#991b1b;}
.fs-badge{display:inline-block;padding:2px 8px;border-radius:999px;font-size:9px;font-weight:bold;text-transform:uppercase;letter-spacing:.4px;}
.fs-badge-admin{background:#1e3a8a;color:#bfdbfe;}
.fs-badge-self{background:#166534;color:#bbf7d0;}
</style>

<div class="fs-wrap">
    <h2><?php echo ADMIN_GOLD; ?><span><?php echo ADMIN_FEEDING_SYSTEM; ?></span></h2>
    <p class="fs-intro"><?php echo ADM_FS_INTRO; ?></p>

    <?php if ($msg !== ''): ?>
        <div class="fs-msg"><?php echo e($msg); ?></div>
    <?php endif; ?>

    <div class="fs-note"><?php echo ADM_FS_MULTIACC_NOTE; ?></div>

    <!-- Enable / cap settings -->
    <div class="fs-card">
        <h3><?php echo ADM_FS_SETTINGS_TITLE; ?></h3>
        <p class="fs-desc"><?php echo ADM_FS_SETTINGS_DESC; ?></p>
        <form method="post" action="../GameEngine/Admin/Mods/feedingSystemAdmin.php">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="do" value="save_settings">

            <div class="fs-toggle-row">
                <label class="fs-radio">
                    <input type="radio" name="enabled" value="1" <?php echo $settings['enabled'] ? 'checked' : ''; ?>>
                    <?php echo ADM_FS_ENABLED; ?>
                </label>
                <label class="fs-radio">
                    <input type="radio" name="enabled" value="0" <?php echo !$settings['enabled'] ? 'checked' : ''; ?>>
                    <?php echo ADM_FS_DISABLED; ?>
                </label>
            </div>

            <div class="fs-settings-row">
                <div>
                    <label><?php echo ADM_FS_MAX_LINKED; ?></label>
                    <input type="number" name="max_linked_per_player" min="0" max="50" value="<?php echo (int) $settings['max_linked_per_player']; ?>">
                </div>
                <div class="fs-radio" style="margin-bottom:9px;">
                    <label style="display:inline;text-transform:none;letter-spacing:normal;color:#cbd5e1;margin:0;">
                        <input type="checkbox" name="announced_in_rules" value="1" <?php echo $settings['announced_in_rules'] ? 'checked' : ''; ?>>
                        <?php echo ADM_FS_ANNOUNCED; ?>
                    </label>
                </div>
                <button type="submit"><?php echo ADM_FS_SAVE; ?></button>
            </div>
        </form>
        <?php if (!$settings['announced_in_rules']): ?>
            <div class="fs-warn" style="margin-top:12px;"><?php echo ADM_FS_ANNOUNCE_WARNING; ?></div>
        <?php endif; ?>
    </div>

    <!-- Manual link (admin bypasses the cap) -->
    <div class="fs-card">
        <h3><?php echo ADM_FS_ADD_TITLE; ?></h3>
        <p class="fs-desc"><?php echo ADM_FS_ADD_DESC; ?></p>
        <form method="post" action="../GameEngine/Admin/Mods/feedingSystemAdmin.php" class="fs-add">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="do" value="add_link">
            <div>
                <label><?php echo ADM_FS_OWNER_USERNAME; ?></label>
                <input type="text" name="owner_username" placeholder="<?php echo ADM_FS_OWNER_USERNAME; ?>" required>
            </div>
            <div>
                <label><?php echo ADM_FS_LINKED_USERNAME; ?></label>
                <input type="text" name="linked_username" placeholder="<?php echo ADM_FS_LINKED_USERNAME; ?>" required>
            </div>
            <button type="submit"><?php echo ADM_FS_LINK_BTN; ?></button>
        </form>
    </div>

    <!-- All current links -->
    <div class="fs-card" style="padding:0;">
        <div style="padding:14px 16px 0;">
            <h3><?php echo ADM_FS_ALL_LINKS_TITLE; ?></h3>
        </div>
        <?php if (empty($links)): ?>
            <div class="fs-empty"><?php echo ADM_FS_NO_LINKS; ?></div>
        <?php else: ?>
            <div class="fs-scroll">
            <table class="fs-table">
                <thead>
                    <tr>
                        <th><?php echo ADM_FS_OWNER_USERNAME; ?></th>
                        <th><?php echo ADM_FS_LINKED_USERNAME; ?></th>
                        <th><?php echo ADM_FS_ADDED; ?></th>
                        <th><?php echo ADM_FS_ADDED_BY; ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($links as $row): ?>
                    <tr>
                        <td><?php echo e($row['owner_username']); ?></td>
                        <td><?php echo e($row['linked_username']); ?></td>
                        <td class="num" style="color:#94a3b8;"><?php echo $row['added'] ? date('Y-m-d H:i', (int) $row['added']) : '&ndash;'; ?></td>
                        <td>
                            <?php if ($row['added_by'] > 0): ?>
                                <span class="fs-badge fs-badge-admin"><?php echo ADM_FS_BADGE_ADMIN; ?></span>
                            <?php else: ?>
                                <span class="fs-badge fs-badge-self"><?php echo ADM_FS_BADGE_SELF; ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="post" action="../GameEngine/Admin/Mods/feedingSystemAdmin.php" onsubmit="return confirm('<?php echo ADM_FS_CONFIRM_REMOVE; ?>');">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="do" value="remove_link">
                                <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                <button type="submit" class="fs-del-btn"><?php echo ADM_FS_REMOVE; ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>
</div>
