<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : TREASURY MENU       	                                   ##
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

?>

<div id="textmenu">
   <a href="build.php?id=<?php echo $id ?? 0; ?>"<?php if(($_GET['t'] ?? 0) == 0 || ($_GET['t'] ?? 0) == 1) echo ' class="selected"'; ?>><?php echo OWN_ARTEFACTS; ?></a>
         
 | <a href="build.php?id=<?php echo $id ?? 0; ?>&t=2"<?php if(($_GET['t'] ?? 0) == 2) echo ' class="selected"'; ?>><?php echo SMALL_ARTEFACTS; ?></a>

 | <a href="build.php?id=<?php echo $id ?? 0; ?>&t=3"<?php if(($_GET['t'] ?? 0) == 3) echo ' class="selected"'; ?>><?php echo LARGE_ARTEFACTS; ?></a>
</div>