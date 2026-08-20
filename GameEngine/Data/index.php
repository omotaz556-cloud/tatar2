<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       index.php                                                   ##
##  Developed by:  aggenkeech                                                  ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2025; base engine (c) TravianZ authors (GPLv3). ##
##                                                                             ##
##  URLs:          https://novaterra.example                                        ##
##                 https://github.com/omotaz556-cloud/tatar                     ##
##                                                                             ##
#################################################################################

// prevent direct misuse in weird contexts (optional but safe)
if (!defined('IN_GAME')) {
    // keep it harmless, just allow display
}

$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
?>

<div style="margin-top: 50px;">
    <div style="text-align: center">

        <h1>404 - File not found</h1>

        <img
            src="<?php echo $basePath; ?>/../../gpack/novaterra_classic/img/misc/404.gif"
            title="Not Found"
            alt="Not Found"
        ><br />

        <p>We looked 404 times already but can't find anything, Not even an X marking the spot.</p>

        <p>This system is not complete yet. So the page probably does not exist.</p>

        <br>

    </div>
</div>