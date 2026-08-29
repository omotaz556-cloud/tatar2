<?php
#################################################################################
##  SAFE INCREMENTAL REFACTOR - Notes Module                                   ##
#################################################################################

$gkMsgGreek = !empty($GLOBALS['gkNachrichtenLiteralPage']);
$gkMsgNotesTitle = defined('TZ_MSG_NOTES_TITLE') ? TZ_MSG_NOTES_TITLE : NOTES;

if (!$gkMsgGreek) {
    echo '<div id="content" class="messages">';
    echo '<h1>' . MESSAGES . '</h1>';
    include('menu.tpl');
} else {
    echo '<div class="gk-msg-panel gk-msg-notes">';
    echo '<div class="gk-msg-panel-title">' . htmlspecialchars($gkMsgNotesTitle, ENT_QUOTES, 'UTF-8') . '</div>';
    echo '<div class="gk-msg-panel-body">';
}
?>

<form method="post" action="nachrichten.php">

<div id="block" class="<?php echo $gkMsgGreek ? 'gk-msg-notes-block' : ''; ?>">

    <input type="hidden" name="ft" value="m6" />

    <textarea name="notizen" id="notice" class="<?php echo $gkMsgGreek ? 'gk-msg-notes-area' : ''; ?>"><?php echo $message->note; ?></textarea>

    <p class="btn">
        <button id="btn_save" name="s1" class="<?php echo $gkMsgGreek ? 'gk-msg-del gk-msg-save' : 'trav_buttons'; ?>"><?php echo SAVE; ?></button>
        <?php if (!$gkMsgGreek) { ?><br />&nbsp;<?php } ?>
    </p>

</div>

</form>

<?php
if ($gkMsgGreek) {
    echo '</div></div>';
} else {
    echo '</div>';
}
?>
