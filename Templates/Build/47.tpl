<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : DEFENSIVEWALL                                             ##
##  Type           : BUILDING TEMPLATE                                         ##
## --------------------------------------------------------------------------- ##
##  Created by     : Shadow                                                    ##
##  Designed by    : Shadow                                                    ##
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

global $village, $building, $bid47, $id, $loopsame, $doublebuild, $master;

include("next.tpl");

$level = (int)$village->resarray['f'.$id];
$current = $level > 0 ? (int)$bid47[$level]['attri'] : 0;
?>
<div id="build" class="gid47">
    <img class="wall-model" src="gpack/novaterra_classic/img/g/g47.gif" alt="<?php echo DEFENSIVEWALL;?>" style="float:right;margin:2px 0 10px 14px;max-width:110px;height:auto;" />
    <h1><?php echo DEFENSIVEWALL;?> <span class="level"><?php echo LEVEL;?> <?php echo $level;?></span></h1>
    <p class="build_desc"><?php echo DEFENSIVEWALL_DESC;?></p>

    <table cellpadding="1" cellspacing="1" id="build_value">
        <tr>
            <th><?php echo DEFENCE_NOW;?></th>
            <td><b><?php echo $current;?></b> <?php echo PERCENT;?></td>
        </tr>
        <?php if (!$building->isMax($village->resarray['f'.$id.'t'], $id)):
            $next = $level + 1 + $loopsame + $doublebuild + $master;
            $next = $next > 20 ? 20 : $next;
        ?>
        <tr>
            <th><?php echo DEFENCE_LEVEL;?> <?php echo $next;?>:</th>
            <td><b><?php echo (int)$bid47[$next]['attri'];?></b> <?php echo PERCENT;?></td>
        </tr>
        <?php endif;?>
    </table>

    <?php include("upgrade.tpl");?>
</div>