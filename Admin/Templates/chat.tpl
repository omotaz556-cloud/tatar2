<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : chat.tpl                                                  ##
##  Type           : Admin moderation dashboard for alliance chat violations   ##
## --------------------------------------------------------------------------- ##
#################################################################################

if (!isset($_SESSION['access']) || $_SESSION['access'] < ADMIN) {
    echo '<p style="color:#f87171;padding:16px;">'.ADM_ACCESS_DENIED_DOT.'</p>';
    return;
}

include_once(__DIR__ . '/../../GameEngine/Chat.php');
$violations = ChatModeration::recentViolations(50);
?>
<style>
.chat-mod-wrap{color:#e2e8f0;font-family:Verdana,Arial,sans-serif;font-size:12px;padding:8px 6px 30px;}
.chat-mod-wrap h2{font-size:18px;margin:0 0 8px;color:#fff;}
.chat-mod-wrap .note{background:#1e293b;border:1px solid #334155;color:#cbd5e1;border-radius:8px;padding:8px 12px;margin-bottom:14px;font-size:11px;line-height:1.5;}
.chat-mod-table{width:100%;border-collapse:collapse;background:#0b1220;border:1px solid #1f2937;}
.chat-mod-table th{background:#111827;color:#94a3b8;font-size:9px;text-transform:uppercase;letter-spacing:.3px;padding:8px 7px;text-align:left;border-bottom:1px solid #1f2937;}
.chat-mod-table td{padding:8px 7px;border-bottom:1px solid #14203a;font-size:11px;vertical-align:top;word-break:break-word;}
.chat-mod-table tr:hover td{background:#0f1a30;}
.chat-mod-empty{padding:18px;text-align:center;color:#64748b;font-size:11px;}
.chat-badge{display:inline-block;padding:2px 8px;border-radius:999px;background:#7f1d1d;color:#fecaca;font-size:9px;font-weight:bold;}
</style>

<div class="chat-mod-wrap">
    <h2><?php echo ADM_CHAT_MODERATION; ?></h2>
    <div class="note"><?php echo ADM_CHAT_MODERATION_NOTE; ?></div>

    <?php if (empty($violations)): ?>
        <div class="chat-mod-empty"><?php echo ADM_NO_CHAT_VIOLATIONS; ?></div>
    <?php else: ?>
        <table class="chat-mod-table">
            <thead>
                <tr>
                    <th><?php echo ADM_TIME; ?></th>
                    <th><?php echo ADM_USER; ?></th>
                    <th><?php echo ADM_ALLIANCE; ?></th>
                    <th><?php echo ADM_TYPE; ?></th>
                    <th><?php echo ADM_SCORE; ?></th>
                    <th><?php echo ADM_ACTION; ?></th>
                    <th><?php echo ADM_MESSAGE; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($violations as $row): ?>
                    <tr>
                        <td><?php echo date('Y-m-d H:i:s', (int)($row['created'] ?? 0)); ?></td>
                        <td>#<?php echo (int)($row['uid'] ?? 0); ?> / <?php echo htmlspecialchars((string)($row['username'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars((string)($row['alliance'] ?? '')); ?></td>
                        <td><span class="chat-badge"><?php echo htmlspecialchars((string)($row['offense'] ?? ADM_UNKNOWN_LC)); ?></span></td>
                        <td><?php echo (int)($row['score'] ?? 0); ?></td>
                        <td><?php echo htmlspecialchars((string)($row['action'] ?? ADM_BLOCKED)); ?></td>
                        <td><?php echo htmlspecialchars((string)($row['message'] ?? '')); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
