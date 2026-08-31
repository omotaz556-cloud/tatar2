<?php
#################################################################################
##  SAFE INCREMENTAL REFACTOR - Read Message                                   ##
#################################################################################

$reading  = $message->reading;

$input    = tz_expand_report($reading['message']);
$alliance = $reading['alliance'];
$player   = $reading['player'];
$coor     = $reading['coor'];
$report   = $reading['report'];

include('GameEngine/BBCode.php');

$userCache = array();
function getCachedUsername($uid, $database, &$cache) {
    $uid = (int) $uid;
    if (!isset($cache[$uid])) {
        $cache[$uid] = $database->getUserField($uid, 'username', 0);
    }
    return $cache[$uid];
}

$ownerId = (int) $reading['owner'];
$linkSender = ($ownerId != 2 && $ownerId != 4);
$date = $generator->procMtime($reading['time']);

$gkMsgGreek = !empty($GLOBALS['gkNachrichtenLiteralPage']);
$gkMsgReadTitle = tz_expand_report($reading['topic']);

if (!$gkMsgGreek) {
    echo '<div id="content" class="messages">';
    echo '<h1>' . MESSAGES . '</h1>';
    include('menu.tpl');
} else {
    echo '<div class="gk-msg-panel gk-msg-read">';
    echo '<div class="gk-msg-panel-title">' . htmlspecialchars($gkMsgReadTitle, ENT_QUOTES, 'UTF-8') . '</div>';
    echo '<div class="gk-msg-panel-body">';
}
?>

<form method="post" action="nachrichten.php">

<?php if (!$gkMsgGreek) { ?><div id="read_head" class="msg_head"></div><?php } ?>

<div id="read_content" class="msg_content<?php echo $gkMsgGreek ? ' gk-msg-read-content' : ''; ?>">

<?php if (!$gkMsgGreek) { ?><img src="img/x.gif" id="label" class="read" alt="" /><?php } ?>

<div id="heading" class="<?php echo $gkMsgGreek ? 'gk-msg-read-heading' : ''; ?>">

    <div class="gk-msg-read-sender">
        <?php
        if ($ownerId <= 1) {
            $gkReadSender = defined('SUPPORT') ? SUPPORT : 'الدعم';
            echo '<a href="' . rtrim(SERVER, '/') . '/spieler.php?uid=1">'
                . htmlspecialchars($gkReadSender, ENT_QUOTES, 'UTF-8') . '</a>';
        } elseif ($linkSender) {
            echo '<a href="' . rtrim(SERVER, '/') . '/spieler.php?uid=' . $ownerId . '">';
            echo getCachedUsername($ownerId, $database, $userCache);
            echo '</a>';
        } else {
            echo getCachedUsername($ownerId, $database, $userCache);
        }
        ?>
    </div>

    <?php if (!$gkMsgGreek) { ?>
    <div><?php echo tz_expand_report($reading['topic']); ?></div>
    <?php } ?>

</div>

<div id="time" class="<?php echo $gkMsgGreek ? 'gk-msg-read-time' : ''; ?>">
    <div><?php echo $date[0]; ?></div>
    <div><?php echo $date[1]; ?></div>
</div>

<div class="clear"></div>
<div class="line"></div>

<div class="message<?php echo $gkMsgGreek ? ' gk-msg-read-text' : ''; ?>">
<?php
echo stripslashes(nl2br($bbcoded));
?>
</div>

<input type="hidden" name="id" value="<?php echo $reading['id']; ?>" />
<input type="hidden" name="ft" value="m1" />
<input type="hidden" name="t" value="1" />

<p class="btn">
    <button name="s1" id="btn_reply" class="<?php echo $gkMsgGreek ? 'gk-msg-del gk-msg-reply' : 'trav_buttons'; ?>"><?php echo ANSWER; ?></button>
</p>

</div>

<?php if (!$gkMsgGreek) { ?><div id="read_foot" class="msg_foot"></div><?php } ?>

</form>

<?php
if ($gkMsgGreek) {
    echo '</div></div>';
} else {
    echo '</div>';
}
?>
