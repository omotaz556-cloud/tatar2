<?php
#################################################################################
##  SAFE INCREMENTAL REFATOR - Messages Inbox                               ##
#################################################################################

$gkMsgGreek = !empty($GLOBALS['gkNachrichtenLiteralPage']);
$gkMsgTableClass = $gkMsgGreek ? 'gk-msg-overview' : 'row_table_data';
$gkMsgTab = isset($_GET['t']) ? (int) $_GET['t'] : 0;

$gkMsgReadAllHref = 'nachrichten.php?readall=1';
if ($gkMsgTab > 0) {
    $gkMsgReadAllHref .= '&amp;t=' . $gkMsgTab;
}
$gkMsgReadOrPrefix = defined('TZ_MSG_MARK_ALL_READ_OR_PREFIX') ? TZ_MSG_MARK_ALL_READ_OR_PREFIX : 'أو';
$gkMsgReadAllLinkText = defined('TZ_MSG_MARK_ALL_READ_LINK') ? TZ_MSG_MARK_ALL_READ_LINK : 'اجعلها مقروءة';
$gkMsgInboxTitle = defined('TZ_MSG_INBOX_TITLE') ? TZ_MSG_INBOX_TITLE : 'الرسائل الواردة';

if (!$gkMsgGreek) {
    echo '<div id="content" class="messages">';
    echo '<h1>' . MESSAGES . '</h1>';
    include('menu.tpl');
}
?>

<form method="post" action="nachrichten.php" name="msg">
<input name="ft" value="m3" type="hidden" />

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
    <th colspan="4"><?php echo $gkMsgInboxTitle; ?></th>
</tr>
<tr class="gk-msg-cols">
    <th colspan="2" class="gk-msg-h-sub"><?php echo SUBJECT; ?></th>
    <th class="gk-msg-h-send"><?php echo SENDER; ?></th>
    <th class="sent gk-msg-h-dat"><?php echo DATE; ?></th>
</tr>
<?php } else { ?>
<tr>
    <th colspan="2"><?php echo SUBJECT; ?></th>
    <th><?php echo SENDER; ?></th>
    <th class="sent">
        <a href="nachrichten.php?o=1"><?php echo SENT; ?></a>
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
    "SELECT plus FROM " . TB_PREFIX . "users WHERE id = '$userId' LIMIT 1"
) or die(mysqli_error($database->dblink));
$golds = mysqli_fetch_array($MyGold);
$date2 = strtotime('NOW');

if ($gkMsgGreek) {
?>
<th class="gk-msg-foot-sel" colspan="2">
<?php if ($golds['plus'] > $date2) { ?>
    <input class="check" type="checkbox" id="s10" name="s10" onclick="Allmsg(this.form);" />
<?php } ?>
    <button type="submit" name="delmsg" value="delete" id="btn_delete" class="gk-msg-del"><?php echo DELETE; ?></button>
</th>
<th class="gk-msg-foot-mid">
    <span class="gk-msg-read-or"><?php echo htmlspecialchars($gkMsgReadOrPrefix, ENT_QUOTES, 'UTF-8'); ?></span>
    <a href="<?php echo $gkMsgReadAllHref; ?>" class="gk-msg-read-link"><?php echo htmlspecialchars($gkMsgReadAllLinkText, ENT_QUOTES, 'UTF-8'); ?></a>
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
    <button name="delmsg" value="delete" id="btn_delete" class="trav_buttons"><?php echo DELETE; ?></button>
    <?php if ($session->plus) { ?>
        <button name="archive" value="Archive" id="btn_archiv" class="trav_buttons"><?php echo ARCHIVE; ?></button>
    <?php } ?>
    <input name="ft" value="m3" type="hidden" />
</th>
<th class="navi">
<?php } ?>

<?php
$total = count($message->inbox1);
$s = isset($_GET['s']) ? (int) $_GET['s'] : 0;
$tParam = !empty($_GET['t']) ? 't=' . $_GET['t'] . '&' : '';

if (!isset($_GET['s']) && $total < 10) {
    echo '&laquo;&raquo;';
} elseif (!isset($_GET['s']) && $total > 10) {
    echo '&laquo;<a href="?' . $tParam . 's=10&o=0">&raquo;</a>';
} elseif (isset($_GET['s']) && $total > $_GET['s']) {
    if ($total > ($_GET['s'] + 10) && $_GET['s'] - 10 < $total && $_GET['s'] != 0) {
        echo '<a href="?' . $tParam . 's=' . ($_GET['s'] - 10) . '&o=0">&laquo;</a>
              <a href="?' . $tParam . 's=' . ($_GET['s'] + 10) . '&o=0">&raquo;</a>';
    } elseif ($total > $_GET['s'] + 10) {
        echo '&laquo;<a href="?' . $tParam . 's=' . ($_GET['s'] + 10) . '&o=0">&raquo;</a>';
    } elseif ($total > 10) {
        echo '<a href="?' . $tParam . 's=' . ($_GET['s'] - 10) . '&o=0">&laquo;</a>&raquo;';
    }
}
?>
</th>
</tr>
</tfoot>

<tbody>

<?php if (!$gkMsgGreek) { ?>
<style>
    tr.multihunterMsg td.sel {
        background-color: orange;
    }
</style>
<?php } ?>

<?php
$s = isset($_GET['s']) ? (int) $_GET['s'] : 0;
$name = 1;
$userCache = array();
$support_messages = ($session->access == ADMIN && ADMIN_RECEIVE_SUPPORT_MESSAGES);
$multihunter_messages = ($session->access == MULTIHUNTER);
$totalMessages = count($message->inbox1);

for ($i = (1 + $s); $i <= (10 + $s); $i++) {
    if ($totalMessages < $i) {
        continue;
    }

    $msg = $message->inbox1[$i - 1];

    if ($msg['owner'] <= 1) {
        echo '<tr class="sup">';
    } elseif ($msg['owner'] == 5) {
        echo '<tr class="multihunterMsg">';
    } else {
        echo '<tr>';
    }

    $message_for_text = '';
    if (
        !$support_messages
        || ($support_messages && $msg['target'] != 1)
        || ($multihunter_messages && $msg['target'] != 5)
    ) {
        $message_for_text = '<input class="check" type="checkbox" name="n' . $name . '" value="' . $msg['id'] . '" />';
    } elseif ($support_messages) {
        $message_for_text = '<u><b title="' . MESS_FOR_SUP . '"><i>S</i></b></u>';
    } elseif ($multihunter_messages) {
        $message_for_text = '<u><b title="' . MESS_FOR_MH . '"><i>M</i></b></u>';
    }

    echo '<td class="sel">' . $message_for_text . '</td>';

    echo '<td class="top"><a href="nachrichten.php?id=' . $msg['id'] . '">' . tz_expand_report($msg['topic']) . '</a>';
    if (!$gkMsgGreek && $msg['viewed'] == 0) {
        echo ' (new)';
    }
    echo '</td>';

    $ownerId = (int) $msg['owner'];
    if (!isset($userCache[$ownerId])) {
        $userCache[$ownerId] = $database->getUserField($ownerId, 'username', 0);
    }
    $username = $userCache[$ownerId];
    $date = $generator->procMtime($msg['time']);

    if ($ownerId <= 1) {
        echo '<td class="send"><a href="spieler.php?uid=1"><u>' . $username . '</u></a></td>';
    } else {
        $linkSender = ($ownerId != 2 && $ownerId != 4);
        echo '<td class="send">' . ($linkSender ? '<a href="spieler.php?uid=' . $ownerId . '">' : '<b>')
            . $username . ($linkSender ? '</a>' : '</b>') . '</td>';
    }

    echo '<td class="dat">' . $date[0] . ' ' . $date[1] . '</td></tr>';
    $name++;
}

if ($totalMessages == 0) {
    echo '<tr><td colspan="4" class="none">' . TZ_NO_MESSAGES_INBOX . '</td></tr>';
}
?>

</tbody>
</table>
</form>

<?php if (!$gkMsgGreek) { ?>
</div>
<?php } ?>
