<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       WorldWonderName.php                                         ##
##  Developed by:  Dzoki                                                       ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
##                                                                             ##
##  URLs:          https://novaterra.example                                        ##
##                 https://github.com/omotaz556-cloud/tatar                     ##
##                                                                             ##
#################################################################################

include("../Village.php");
if(isset($_POST['wwname']) && !empty($_POST['wwname']) && $village->natar){
    $database->submitWWname($village->wid,$_POST['wwname']);
    header("Location: ../../build.php?id=99&n");
}else{
    header("Location: ../../dorf2.php");
}


?>