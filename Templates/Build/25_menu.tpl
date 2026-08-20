<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : RESIDENCE MENU                                            ##
##  Type           : BUILDING TEMPLATE                                         ##
## --------------------------------------------------------------------------- ##
##  Refactored by  : Shadow                                                    ##
##  Redesign by    : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  Test Server    : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : Novaterra Project                                          ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.              ##
## --------------------------------------------------------------------------- ##
#################################################################################

global $id;
$s = $_GET['s'] ?? null;
?>
<div id="textmenu">
   <a href="build.php?id=<?php echo (int)$id;?>" <?php if(!$s) echo 'class="selected"';?>><?php echo TRAIN;?></a>
 | <a href="build.php?id=<?php echo (int)$id;?>&amp;s=2" <?php if($s==2) echo 'class="selected"';?>><?php echo CULTURE_POINTS;?></a>
 | <a href="build.php?id=<?php echo (int)$id;?>&amp;s=3" <?php if($s==3) echo 'class="selected"';?>><?php echo LOYALTY;?></a>
 | <a href="build.php?id=<?php echo (int)$id;?>&amp;s=4" <?php if($s==4) echo 'class="selected"';?>><?php echo EXPANSION;?></a>
</div>