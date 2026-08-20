<?php
#################################################################################
##                                                                             ##
##  Project:       Novaterra                                                   ##
##  Filename:      Templates/Tutorial/3.tpl                                    ##
##  Purpose:       In-game tutorial, step 3 of 5 — buildings                   ##
##  License:       Proprietary — original work, part of Novaterra project      ##
##                 (rewritten from scratch, no derivation from prior authors)  ##
##                                                                             ##
#################################################################################
?>
<h2><?php echo TZ_N_3_5_BUILDINGS; ?></h2>
<section class="tutorial-step">
    <div class="tutorial-step__visuals">
        <figure>
            <img src="img/en/tut/dorfzentrum1.gif" alt="" />
            <figcaption><?php echo TZ_N_1_CHOOSE_BUILDING_SITE; ?></figcaption>
        </figure>
        <figure>
            <img src="img/en/tut/dorfzentrum2.gif" alt="" />
            <figcaption><?php echo TZ_N_2_CONSTRUCT_A_BUILDING; ?></figcaption>
        </figure>
    </div>
    <div class="tutorial-step__text">
        <p><?php echo TZ_AFTER_TAKING_CARE_OF_YOUR_RESOURCE; ?></p>
        <p><?php echo TZ_A_WAREHOUSE_AND_A_GRANARY_ENABLE_Y; ?></p>
    </div>
</section>
<nav id="tutorial_nav" class="tutorial-nav">
    <a class="tutorial-nav__prev" href="tutorial.php?s=2" title="<?php echo BACK; ?>">&laquo; back</a>
    <a class="tutorial-nav__next" href="tutorial.php?s=4" title="<?php echo TZ_FORWARD; ?>">forward &raquo;</a>
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
