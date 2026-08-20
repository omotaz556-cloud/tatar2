<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Project:       Novaterra      					       		 		  	   ##
##  Version:       06.05.2026 						       	 				   ##
##  Filename       notfound.tpl                                                ##
##  Refactored by  Shadow					                                   ##
##  License:       Novaterra Project                                            ##
##  Copyright:     Novaterra (c) 2010-2026. All rights reserved.                ##
##  URLs:          http://novaterra.example						       	 		   ##
##  Source code:   http://github.com/omotaz556-cloud/tatar/         	       	   ##
##                                                                             ##
#################################################################################

// Evităm warning dacă output a început deja
if (!headers_sent()) {
    header("Location: dorf1.php");
    exit;
} else {
    // fallback sigur dacă headers already sent
    echo '<script>window.location.href="dorf1.php";</script>';
    exit;
}
?>