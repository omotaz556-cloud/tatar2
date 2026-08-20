<?php
#################################################################################
##                                                                             ##
##  Project:       Novaterra                                                   ##
##  Filename:      Templates/Tutorial/5.tpl                                    ##
##  Purpose:       In-game tutorial, step 5 of 5 — navigation bar overview     ##
##  License:       Proprietary — original work, part of Novaterra project      ##
##                 (rewritten from scratch, no derivation from prior authors)  ##
##                                                                             ##
#################################################################################
?>
<h2><?php echo TZ_N_5_5_NAVIGATION; ?></h2>
<section class="tutorial-step">
    <div class="tutorial-step__visuals tutorial-step__visuals--single">
        <figure>
            <img src="img/en/tut/navi.jpg" alt="" />
            <figcaption><?php echo TZ_THE_NAVIGATION_BAR; ?></figcaption>
        </figure>
    </div>
    <div class="tutorial-step__text">
        <ol class="tutorial-nav-legend">
            <li><strong><?php echo TZ_OVERVIEW; ?></strong> <?php echo TZ_HERE_YOU_FIND_YOUR_RESOURCE_FIELDS; ?></li>
            <li><strong><?php echo TZ_CENTRE; ?></strong> <?php echo TZ_IN_THE_VILLAGE_YOU_CAN_BUILD_BUILD; ?></li>
            <li><strong><?php echo TZ_MAP_2; ?></strong> <?php echo TZ_HERE_YOU_CAN_HAVE_A_LOOK_AT_YOUR_V; ?></li>
            <li><strong><?php echo TZ_STATISTICS; ?></strong> <?php echo TZ_RANKING_OF_ALL_PLAYERS; ?></li>
            <li><strong><?php echo TZ_REPORTS; ?></strong> <?php echo TZ_INFORMATION_ON_HAPPENINGS_IN_YOUR; ?></li>
            <li><strong><?php echo TZ_MESSAGES; ?></strong> <?php echo TZ_SEND_AND_RECEIVE_MESSAGES; ?></li>
        </ol>
        <p><?php echo TZ_NOW_YOU_KNOW_EVERYTHING_IMPORTANT; ?></p>
    </div>
</section>
<nav id="tutorial_nav" class="tutorial-nav">
    <a class="tutorial-nav__prev" href="tutorial.php?s=4" title="<?php echo BACK; ?>">&laquo; back</a>
    <a class="tutorial-nav__next" href="index.php?signup" title="<?php echo TZ_TO_THE_REGISTRATION; ?>">&raquo; to the registration</a>
</nav>
        </div>
        <div class="clear"></div>
    </div>
    <div id="footer">
        <div class="container">
            <a href="#" class="logo"><img src="img/x.gif" alt="<?php echo TZ_GAME_BRAND; ?>" class="logo_gamebrand" /></a>
            <ul class="menu">
                <li><a href="anleitung.php?s=3"><?php echo FAQ; ?></a>|</li>
                <li><a href="index.php?screenshots"><?php echo SCREENSHOTS; ?></a>|</li>
                <li><a href="spielregeln.php"><?php echo GAME_RULES; ?></a>|</li>
                <li><a href="agb.php"><?php echo TZ_TERMS; ?></a>|</li>
                <li><a href="impressum.php"><?php echo IMPRINT; ?></a></li>
                <li class="copyright">&copy; 2010 - <?php echo date('Y') . ' ' . (defined('SERVER_NAME') ? SERVER_NAME : 'Novaterra'); ?> All rights reserved</li>
            </ul>
        </div>
    </div>
</div>
