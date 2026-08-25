<?php

#################################################################################
##  Filename       : menu.tpl                                                  ##
##  Shown on edit tabs (s=1..5) — home link returns to the one-page hub.       ##
#################################################################################

$menuUid = isset($_GET['uid']) ? (int)$_GET['uid'] : (int)$session->uid;
$sParam = isset($_GET['s']) ? (int)$_GET['s'] : null;
$onDetails = !empty($_GET['details']);

$sitterView = isset($session) && is_object($session)
    && method_exists($session, 'isSitterSession') && $session->isSitterSession();
?>

<div id="textmenu">

    <a href="spieler.php?uid=<?php echo (int) $session->uid; ?>"
       <?php echo (!$sParam && !$onDetails) ? 'class="selected"' : ''; ?>>
        <?php echo OVERVIEW; ?>
    </a>

<?php if (!$sitterView) { ?>
    |
    <a href="spieler.php?uid=<?php echo (int) $session->uid; ?>&amp;details=1"
       <?php echo $onDetails ? 'class="selected"' : ''; ?>>
        <?php echo PLAYER_PROFILE; ?>
    </a>
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

    <?php if (defined('GP_ENABLE') && GP_ENABLE) { ?>
        |
        <a href="spieler.php?s=4"
           <?php echo ($sParam === 4) ? 'class="selected"' : ''; ?>>
            <?php echo GRAPH_PACK; ?>
        </a>
    <?php } ?>
<?php } ?>

</div>
