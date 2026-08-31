<?php
#################################################################################
##  SAFE INCREMENTAL REFACTOR - Sent Message                                   ##
#################################################################################

$gkMsgGreek = !empty($GLOBALS['gkNachrichtenLiteralPage']);
$gkMsgTableClass = $gkMsgGreek ? 'gk-msg-overview' : 'row_table_data';
$gkMsgTab = isset($_GET['t']) ? (int) $_GET['t'] : 2;
$gkMsgSentTitle = defined('TZ_MSG_SENT_TITLE') ? TZ_MSG_SENT_TITLE : 'الرسائل الصادرة';

$s = isset($_GET['s']) ? (int) $_GET['s'] : 0;
$o = !empty($_GET['o']) ? (int) $_GET['o'] : 0;

$userCache = array();
function getCachedUsername($uid, $database, &$cache) {
    $uid = (int) $uid;
    if (!isset($cache[$uid])) {
        $cache[$uid] = $database->getUserField($uid, 'username', 0);
    }
    return $cache[$uid];
}

if (!$gkMsgGreek) {
    echo '<div id="content" class="messages">';
    echo '<h1>' . MESSAGES . '</h1>';
    include('menu.tpl');
}
?>

<form method="post" action="nachrichten.php" name="msg">
<input type="hidden" name="ft" value="m4" />

<table cellpadding="0" cellspacing="0" id="overview" class="<?php echo $gkMsgTableClass; ?>">
<?php if ($gkMsgGreek) { ?>
<colgroup>
    <col class="gk-msg-c-sel" />
    <col class="gk-msg-c-sub" />
    <col class="gk-msg-c-send" />
    <col class="gk-msg-c-dat" />
</colgroup>
<?php } ?>

<thead>
<?php if ($gkMsgGreek) { ?>
<tr class="gk-msg-title">
    <th colspan="4"><?php echo $gkMsgSentTitle; ?></th>
</tr>
<tr class="gk-msg-cols">
    <th colspan="2" class="gk-msg-h-sub"><?php echo SUBJECT; ?></th>
    <th class="gk-msg-h-send"><?php echo RECIPIENT; ?></th>
    <th class="sent gk-msg-h-dat"><?php echo DATE; ?></th>
</tr>
<?php } else { ?>
<tr>
    <th></th>
    <th><?php echo SUBJECT; ?></th>
    <th><?php echo RECIPIENT; ?></th>
    <th class="sent">
        <a href="nachrichten.php?t=2&s=0&amp;t=2&amp;o=1"><?php echo SENT; ?></a>
    </th>
</tr>
<?php } ?>
</thead>

<tfoot>
<tr>
<?php
$userId = (int) $session->uid;
$MyGold = mysqli_query(
    $database->dblink,
    "SELECT plus FROM " . TB_PREFIX . "users WHERE id='$userId' LIMIT 1"
) or die(mysqli_error($database->dblink));
$golds = mysqli_fetch_array($MyGold);
$date2 = strtotime('NOW');

if ($gkMsgGreek) {
?>
<th class="gk-msg-foot-sel" colspan="3">
<?php if ($golds['plus'] > $date2) { ?>
    <input class="check" type="checkbox" id="s10" name="s10" onclick="Allmsg(this.form);" />
<?php } ?>
    <button type="submit" name="delmsg" value="delete" id="btn_delete" class="gk-msg-del"><?php echo DELETE; ?></button>
</th>
<th class="navi">
<?php
} else {
?>
<th>
<?php if ($golds['plus'] > $date2) { ?>
    <input class="check" type="checkbox" id="s10" name="s10" onclick="Allmsg(this.form);" />
<?php } ?>
</th>
<th colspan="2" class="buttons">
    <button value="delete" name="delmsg" id="btn_delete" class="trav_buttons"><?php echo DELETE; ?></button>
</th>
<th class="navi">
<?php } ?>

<?php
$total = count($message->sent1);

if (!isset($_GET['s']) && $total < 10) {
    echo '&laquo;&raquo;';
} elseif (!isset($_GET['s']) && $total > 10) {
    echo '&laquo;<a href="?t=2&s=10&o=' . $o . '">&raquo;</a>';
} elseif (isset($_GET['s']) && $total > $_GET['s']) {
    if ($total > ($_GET['s'] + 10) && $_GET['s'] - 10 < $total && $_GET['s'] != 0) {
        echo '<a href="?t=2&s=' . ($_GET['s'] - 10) . '&o=' . $o . '">&laquo;</a>
              <a href="?t=2&s=' . ($_GET['s'] + 10) . '&o=' . $o . '">&raquo;</a>';
    } elseif ($total > $_GET['s'] + 10) {
        echo '&laquo;<a href="?t=2&s=' . ($_GET['s'] + 10) . '&o=' . $o . '">&raquo;</a>';
    } elseif ($total > 10) {
        echo '<a href="?t=2&s=' . ($_GET['s'] - 10) . '&o=' . $o . '">&laquo;</a>&raquo;';
    }
}
?>
</th>
</tr>
</tfoot>

<tbody>
<?php
$name = 1;
$support_messages = ($session->access == ADMIN && ADMIN_RECEIVE_SUPPORT_MESSAGES);
$multihunter_messages = ($session->access == MULTIHUNTER);
$totalMessages = count($message->sent1);

for ($i = (1 + $s); $i <= (10 + $s); $i++) {
    if ($totalMessages < $i) {
        continue;
    }

    $msg = $message->sent1[$i - 1];

    if ($msg['target'] == 0) {
        echo '<tr class="sup">';
    } else {
        echo '<tr>';
    }

    $sent_as_text = '';
    if (
        !$support_messages
        || ($support_messages && $msg['target'] != 1)
        || ($multihunter_messages && $msg['target'] != 5)
    ) {
        $sent_as_text = '<input class="check" type="checkbox" name="n' . $name . '" value="' . $msg['id'] . '" />';
    } elseif ($support_messages) {
        $sent_as_text = '<u><b title="' . htmlspecialchars(SENT_AS_SUP, ENT_QUOTES, 'UTF-8') . '"><i>S</i></b></u>';
    } elseif ($multihunter_messages) {
        $sent_as_text = '<u><b title="' . htmlspecialchars(SENT_AS_MH, ENT_QUOTES, 'UTF-8') . '"><i>M</i></b></u>';
    }

    echo '<td class="sel">' . $sent_as_text . '</td>';

    echo '<td class="top"><a href="nachrichten.php?t=2a&amp;id=' . $msg['id'] . '">' . tz_expand_report($msg['topic']) . '</a>';
    if ($msg['viewed'] == 0) {
        $gkMsgUnreadLabel = defined('TZ_MSG_UNREAD') ? TZ_MSG_UNREAD : '(غير مقروء)';
        echo ' ' . htmlspecialchars($gkMsgUnreadLabel, ENT_QUOTES, 'UTF-8');
    }
    echo '</td>';

    $targetId = (int) $msg['target'];
    $username = getCachedUsername($targetId, $database, $userCache);
    $date = $generator->procMtime($msg['time']);

    echo '<td class="send"><a href="spieler.php?uid=' . $targetId . '">' . $username . '</a></td>';
    echo '<td class="dat"><bdi class="gk-msg-dat"><span class="gk-msg-dat-time">' . htmlspecialchars($date[1], ENT_QUOTES, 'UTF-8') . '</span><span class="gk-msg-dat-day">' . htmlspecialchars($date[0], ENT_QUOTES, 'UTF-8') . '</span></bdi></td></tr>';
    $name++;
}

if ($totalMessages == 0) {
    echo '<tr><td colspan="4" class="none">' . TZ_NO_MESSAGES_SENT . '</td></tr>';
}
?>
</tbody>
</table>
</form>

<?php if (!$gkMsgGreek) { ?>
</div>
<?php } ?>
