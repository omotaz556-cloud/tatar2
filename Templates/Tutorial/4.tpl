<?php
#################################################################################
##                                                                             ##
##  Project:       Novaterra                                                   ##
##  Filename:      Templates/Tutorial/4.tpl                                    ##
##  Purpose:       In-game tutorial, step 4 of 5 — neighbours/map              ##
##  License:       Proprietary — original work, part of Novaterra project      ##
##                 (rewritten from scratch, no derivation from prior authors)  ##
##                                                                             ##
#################################################################################
?>
<h2><?php echo TZ_N_4_5_NEIGHBOURS; ?></h2>
<section class="tutorial-step">
    <div class="tutorial-step__visuals tutorial-step__visuals--single">
        <figure>
            <img src="img/en/tut/karte.jpg" alt="" />
            <figcaption><?php echo TZ_YOUR_VILLAGE_AND_YOUR_NEIGHBOURS; ?></figcaption>
        </figure>
    </div>
    <div class="tutorial-step__text">
        <p><?php echo TZ_IN_NOVATERRA_YOU_ARE_NOT_ALONE_YOU_I; ?></p>
        <p><?php echo TZ_THE_PLAYERS_IN_YOUR_SURROUNDING_AR; ?></p>
    </div>
</section>
<nav id="tutorial_nav" class="tutorial-nav">
    <a class="tutorial-nav__prev" href="tutorial.php?s=3" title="<?php echo BACK; ?>">&laquo; back</a>
    <a class="tutorial-nav__next" href="tutorial.php?s=5" title="<?php echo TZ_FORWARD; ?>">forward &raquo;</a>
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
