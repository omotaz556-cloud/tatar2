<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : featureFlags.tpl                                         ##
##  Type           : Admin Panel Frontend for generic Feature Flags            ##
## --------------------------------------------------------------------------- ##
##  Project        : Novaterra                                                  ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : Novaterra Project                                          ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.              ##
## --------------------------------------------------------------------------- ##
#################################################################################

if (!isset($_SESSION['access']) || $_SESSION['access'] < ADMIN) {
    echo '<p style="color:#f87171;padding:16px;">Access denied.</p>';
    return;
}

$flags = FeatureFlags::listAll();
$msg   = isset($_GET['msg']) ? (string) $_GET['msg'] : '';
?>
<style>
.ff-wrap{color:#e2e8f0;font-family:Verdana,Arial,sans-serif;font-size:12px;padding:6px 4px 26px;}
.ff-wrap h2{font-size:18px;margin:0 0 4px;color:#fff;}
.ff-wrap h2 span{color:#38bdf8;}
.ff-intro{color:#94a3b8;font-size:11px;margin:0 0 14px;max-width:860px;line-height:1.5;}
.ff-msg{background:#14532d;border:1px solid #166534;color:#bbf7d0;border-radius:6px;padding:8px 12px;font-size:11px;margin-bottom:14px;}
.ff-card{background:#111827;border:1px solid #1f2937;border-radius:8px;padding:14px 16px;margin-bottom:18px;}
.ff-card h3{margin:0 0 10px;font-size:13px;color:#fff;}
.ff-add{display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;}
.ff-add label{display:block;font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:#94a3b8;margin-bottom:4px;}
.ff-add input[type=text]{background:#0b1220;border:1px solid #334155;border-radius:6px;color:#e2e8f0;padding:7px 9px;}
.ff-add input.key{width:170px;font-family:monospace;}
.ff-add input.label{width:190px;}
.ff-add input.note{width:200px;}
.ff-add .chk{display:flex;align-items:center;gap:6px;color:#cbd5e1;font-size:11px;padding-bottom:7px;}
.ff-add button{background:#38bdf8;color:#0b1220;font-weight:bold;border:0;border-radius:6px;padding:9px 18px;cursor:pointer;}
.ff-hint{color:#64748b;font-size:10px;margin-top:8px;}
.ff-table{width:100%;border-collapse:collapse;background:#0b1220;border:1px solid #1f2937;border-radius:8px;}
.ff-table th{background:#111827;text-align:left;padding:7px 7px;font-size:9px;text-transform:uppercase;letter-spacing:.3px;color:#94a3b8;border-bottom:1px solid #1f2937;white-space:nowrap;}
.ff-table td{padding:6px 7px;border-bottom:1px solid #14203a;vertical-align:middle;font-size:11px;}
.ff-table tr:hover td{background:#0f1a30;}
.ff-table td.note-col{max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.ff-scroll{overflow-x:auto;}
.ff-key{font-family:monospace;font-weight:bold;color:#7dd3fc;}
.badge{display:inline-block;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:bold;text-transform:uppercase;letter-spacing:.4px;}
.st-on{background:#14532d;color:#bbf7d0;}
.st-off{background:#334155;color:#cbd5e1;}
.ff-actions{display:flex;gap:6px;}
.ff-actions button{border:0;border-radius:5px;padding:5px 10px;font-size:11px;cursor:pointer;}
.b-toggle{background:#334155;color:#fff;}
.b-toggle:hover{background:#38bdf8;color:#0b1220;}
.b-del{background:#7f1d1d;color:#fecaca;}
.b-del:hover{background:#b91c1c;color:#fff;}
.ff-empty{padding:22px;text-align:center;color:#64748b;}
.num{font-variant-numeric:tabular-nums;}
</style>

<div class="ff-wrap">
    <h2><?php echo ADMIN_ADMIN; ?><span>Feature Flags</span></h2>
    <p class="ff-intro">
        Add or remove on/off switches for any feature here, without needing a code
        or config.php change. Flags apply the moment you toggle them - no server
        restart or config regeneration involved. This is separate from the
        Server / PLUS / Log settings pages: those control fixed, built-in
        switches; this page is for free-form flags you define yourself.
    </p>

    <?php if ($msg !== ''): ?>
        <div class="ff-msg"><?php echo e($msg); ?></div>
    <?php endif; ?>

    <div class="ff-card">
        <h3>Add a new flag</h3>
        <form method="post" action="../GameEngine/Admin/Mods/featureFlags.php" class="ff-add">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="do" value="create">
            <div>
                <label>Key</label>
                <input class="key" type="text" name="flag_key" maxlength="80" placeholder="gold_res_purchase" required>
            </div>
            <div>
                <label>Label</label>
                <input class="label" type="text" name="label" maxlength="150" placeholder="Gold &rarr; Resources purchase">
            </div>
            <div>
                <label>Note (optional)</label>
                <input class="note" type="text" name="note" maxlength="255" placeholder="What this flag controls">
            </div>
            <div class="chk">
                <input type="checkbox" name="enabled" id="ff_enabled_new" checked>
                <label for="ff_enabled_new" style="margin:0;text-transform:none;letter-spacing:0;">Enabled</label>
            </div>
            <button type="submit">Add Flag</button>
        </form>
        <div class="ff-hint">Keys are lowercased and normalised to letters, numbers and underscores. Check a flag in code with <code>FeatureFlags::isEnabled('your_key')</code>.</div>
    </div>

    <div class="ff-card" style="padding:0;">
        <?php if (empty($flags)): ?>
            <div class="ff-empty">No feature flags yet. Add one above.</div>
        <?php else: ?>
            <div class="ff-scroll">
            <table class="ff-table">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Label</th>
                        <th>Status</th>
                        <th>Note</th>
                        <th>Updated</th>
                        <th style="width:150px;"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($flags as $fl):
                    $on = ((int) $fl['enabled']) === 1;
                ?>
                    <tr>
                        <td class="ff-key"><?php echo e($fl['flag_key']); ?></td>
                        <td><?php echo $fl['label'] !== '' ? e($fl['label']) : '&ndash;'; ?></td>
                        <td><span class="badge <?php echo $on ? 'st-on' : 'st-off'; ?>"><?php echo $on ? 'ON' : 'OFF'; ?></span></td>
                        <td class="note-col" style="color:#94a3b8;"><?php echo $fl['note'] !== '' ? e($fl['note']) : '&ndash;'; ?></td>
                        <td class="num" style="color:#94a3b8;"><?php echo $fl['time'] ? date('Y-m-d H:i', (int) $fl['time']) : '&ndash;'; ?></td>
                        <td>
                            <div class="ff-actions">
                                <form method="post" action="../GameEngine/Admin/Mods/featureFlags.php">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="do" value="toggle">
                                    <input type="hidden" name="id" value="<?php echo (int) $fl['id']; ?>">
                                    <input type="hidden" name="enabled" value="<?php echo $on ? 0 : 1; ?>">
                                    <button type="submit" class="b-toggle"><?php echo $on ? 'Disable' : 'Enable'; ?></button>
                                </form>
                                <form method="post" action="../GameEngine/Admin/Mods/featureFlags.php" onsubmit="return confirm('Delete this flag? Any code checking it will fall back to its default value.');">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="do" value="delete">
                                    <input type="hidden" name="id" value="<?php echo (int) $fl['id']; ?>">
                                    <button type="submit" class="b-del">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>
</div>
