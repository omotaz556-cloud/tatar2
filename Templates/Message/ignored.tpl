<?php
#################################################################################
##  Ignored players list (Greek messages shell)                                 ##
#################################################################################

$gkMsgGreek = !empty($GLOBALS['gkNachrichtenLiteralPage']);
$gkMsgIgnoredTitle = defined('TZ_MSG_IGNORED_TITLE') ? TZ_MSG_IGNORED_TITLE : 'المتجاهلون';
$gkMsgNoIgnored = defined('TZ_NO_IGNORED_PLAYERS') ? TZ_NO_IGNORED_PLAYERS : 'لا يوجد لاعبين متجاهلين.';

if (!$gkMsgGreek) {
    echo '<div id="content" class="messages">';
    echo '<h1>' . MESSAGES . '</h1>';
    include('menu.tpl');
} else {
    echo '<div class="gk-msg-panel gk-msg-ignored">';
    echo '<div class="gk-msg-panel-title">' . htmlspecialchars($gkMsgIgnoredTitle, ENT_QUOTES, 'UTF-8') . '</div>';
    echo '<div class="gk-msg-panel-body">';
}
?>

<div class="gk-msg-ignored-empty">
    <?php echo htmlspecialchars($gkMsgNoIgnored, ENT_QUOTES, 'UTF-8'); ?>
</div>

<?php
if ($gkMsgGreek) {
    echo '</div></div>';
} else {
    echo '</div>';
}
?>
