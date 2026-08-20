<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : TOWNHALL TIMING                                           ##
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

global $database, $village, $generator, $session;

$timeleft = (int)$database->getVillageField($village->wid, 'celebration');
if ($timeleft > time()):
?>
<br>
<table cellpadding="0" cellspacing="0" id="building_contract">
    <tr>
        <td><?php echo TZ_CELEBRATION_STILL_NEEDS; ?></td>
        <td><span id="timer<?php echo ++$session->timer;?>"><?php echo $generator->getTimeFormat($timeleft - time());?></span> <?php echo TZ_HRS_2; ?></td>
        <td><?php echo DONE_AT; ?> <?php echo date('H:i', $timeleft);?></td>
    </tr>
</table>
<?php endif;?>