<?php
#################################################################################
##  SAFE INCREMENTAL REFACTOR - Archive Messages                                ##
#################################################################################

$gkMsgGreek = !empty($GLOBALS['gkNachrichtenLiteralPage']);
$gkMsgTableClass = $gkMsgGreek ? 'gk-msg-overview' : 'row_table_data';
$gkMsgArchiveTitle = defined('TZ_MSG_ARCHIVE_TITLE') ? TZ_MSG_ARCHIVE_TITLE : ARCHIVE;

if (!$gkMsgGreek) {
    echo '<div id="content" class="messages">';
    echo '<h1>' . MESSAGES . '</h1>';
    include('menu.tpl');
}
?>

<form method="post" action="nachrichten.php" name="msg">
<input type="hidden" name="ft" value="m5" />

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
    <th colspan="4"><?php echo $gkMsgArchiveTitle; ?></th>
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
        <a href="nachrichten.php?s=0&amp;t=3&amp;o=1"><?php echo SENT; ?></a>
    </th>
</tr>
<?php } ?>
</thead>

<tfoot>
<tr>
<?php if ($gkMsgGreek) { ?>
<th class="gk-msg-foot-sel" colspan="2">
    <input class="check" type="checkbox" id="s10" name="s10" onclick="Allmsg(this.form);" />
    <button type="submit" name="delmsg" value="delete" id="btn_delete" class="gk-msg-del"><?php echo DELETE; ?></button>
    <button type="submit" name="start" value="Back" id="btn_back" class="gk-msg-del gk-msg-back"><?php echo BACK; ?></button>
</th>
<th class="gk-msg-foot-mid"></th>
<th class="navi">
<?php } else { ?>
<th>
    <input class="check" type="checkbox" id="s10" name="s10" onclick="Allmsg(this.form);" />
</th>
<th colspan="2" class="buttons">
    <button name="delmsg" value="delete" id="btn_delete" class="trav_buttons"><?php echo DELETE; ?></button>
    <button name="start" value="Back" id="btn_back" class="trav_buttons"><?php echo BACK; ?></button>
</th>
<th class="navi">
<?php } ?>

<?php
$total = count($message->archived1);
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
<?php
$s = isset($_GET['s']) ? (int) $_GET['s'] : 0;
$name = 1;
$userCache = array();
$totalMessages = count($message->archived1);

for ($i = (1 + $s); $i <= (10 + $s); $i++) {
    if ($totalMessages < $i) {
        continue;
    }

    $msg = $message->archived[$i - 1];

    if ($msg['owner'] == 0) {
        echo '<tr class="sup">';
    } else {
        echo '<tr>';
    }

    echo '<td class="sel"><input class="check" type="checkbox" name="n' . $name . '" value="' . $msg['id'] . '" /></td>';

    echo '<td class="top"><a href="nachrichten.php?id=' . $msg['id'] . '">' . tz_expand_report($msg['topic']) . '</a>';
    if ($msg['viewed'] == 0) {
        $gkMsgNewLabel = defined('TZ_MSG_NEW') ? TZ_MSG_NEW : '(جديد)';
        echo ' ' . htmlspecialchars($gkMsgNewLabel, ENT_QUOTES, 'UTF-8');
    }
    echo '</td>';

    $ownerId = (int) $msg['owner'];
    if (!isset($userCache[$ownerId])) {
        $userCache[$ownerId] = $database->getUserField($ownerId, 'username', 0);
    }
    $username = $userCache[$ownerId];
    $date = $generator->procMtime($msg['time']);

    echo '<td class="send"><a href="spieler.php?uid=' . $ownerId . '">' . $username . '</a></td>';
    echo '<td class="dat"><bdi class="gk-msg-dat"><span class="gk-msg-dat-time">' . htmlspecialchars($date[1], ENT_QUOTES, 'UTF-8') . '</span><span class="gk-msg-dat-day">' . htmlspecialchars($date[0], ENT_QUOTES, 'UTF-8') . '</span></bdi></td></tr>';
    $name++;
}

if ($totalMessages == 0) {
    echo '<tr><td colspan="4" class="none">' . TZ_NO_MESSAGES_ARCHIVE . '</td></tr>';
}
?>
</tbody>
</table>
</form>

<?php if (!$gkMsgGreek) { ?>
</div>
<?php } ?>
