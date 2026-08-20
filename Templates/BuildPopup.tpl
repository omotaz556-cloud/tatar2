<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       BuildPopup.tpl                                              ##
##  License:       Novaterra Project                                           ##
##  Copyright:     Novaterra (c) 2010-2026. All rights reserved.               ##
##                                                                             ##
##  Purpose:                                                                  ##
##  In-page popup used to open/upgrade a field without a full page reload.    ##
##  Included once from dorf1.php and dorf2.php. Empty shell only - content    ##
##  is fetched from build.php (AJAX fragment mode, see build.php) and         ##
##  injected here by new2.js. Hidden by default; #resfeld clicks on the       ##
##  village map open it (see field.tpl / dorf2.tpl data-ajax-build areas).    ##
#################################################################################
?>
<div class="overlay" id="buildPopup" style="display:none;">
    <div class="mask"></div>
    <div class="overlay_content" id="buildPopupContent">
        <div class="closer"><img src="img/x.gif" alt="X" /></div>
        <div id="buildPopupBody">
            <!-- filled by AJAX -->
        </div>
    </div>
</div>
