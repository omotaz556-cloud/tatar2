<?php

#################################################################################
##  Filename       : menu.tpl                                                  ##
##  Classic profile tabs (non-Greek / LTR only). Greek RTL uses BAR5 in        ##
##  GameEngine/GreekSpieler.php — this file must stay empty on spieler RTL.   ##
#################################################################################

$menuUid = isset($_GET['uid']) ? (int) $_GET['uid'] : (int) $session->uid;
$sParam = isset($_GET['s']) ? (int) $_GET['s'] : null;
$onDetails = !empty($_GET['details']);

$sitterView = isset($session) && is_object($session)
    && method_exists($session, 'isSitterSession') && $session->isSitterSession();

if (!empty($GLOBALS['gkSpielerLiteralPage'])) {
    return;
}

if (class_exists('GreekSpieler') && GreekSpieler::suppressClassicMenu()) {
    return;
}
?>

<div id="textmenu">

    <a href="spieler.php?uid=<?php echo (int) $session->uid; ?>"
       <?php echo (!$sParam && !$onDetails) ? 'class="selected"' : ''; ?>>
        <?php echo OVERVIEW; ?>
    </a>

<?php if (!$sitterView) { ?>
    |
    <a href="spieler.php?s=1"
       <?php echo ($sParam === 1) ? 'class="selected"' : ''; ?>>
        <?php echo PROFILE; ?>
    </a>
    |
    <a href="spieler.php?s=2"
       <?php echo ($sParam === 2) ? 'class="selected"' : ''; ?>>
        <?php echo PREFERENCES; ?>
    </a>
    |
    <a href="spieler.php?s=3"
       <?php echo ($sParam === 3) ? 'class="selected"' : ''; ?>>
        <?php echo ACCOUNT; ?>
    </a>

    <?php if (defined('NEW_FUNCTIONS_VACATION') && NEW_FUNCTIONS_VACATION) { ?>
        |
        <a href="spieler.php?s=5"
           <?php echo ($sParam === 5) ? 'class="selected"' : ''; ?>>
            <?php echo VACATION; ?>
        </a>
    <?php } ?>
<?php } ?>

</div>
