<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : RALLY POINT GOLD CLUB                                     ##
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

$hideEvasion = isset($hideevasion) ? (int) $hideevasion : 0;
$user = $database->getUserArray($session->uid, 1);
$rallyFieldId = isset($id) ? (int) $id : 39;
$farmGoldClubActive = !empty($session->goldclub);
?>
<div id="build" class="gid16">
    <h1><?php echo RALLYPOINT; ?> <span class="level"><?php echo defined('BUILD_LEVEL_SHORT') ? BUILD_LEVEL_SHORT : LEVEL; ?> <?php echo (int) $village->resarray['f' . $id]; ?></span></h1>
    <div class="gk-build-intro">
        <a href="#" onclick="return Popup(16,4);" class="build_logo">
            <img class="g16" src="img/x.gif" alt="<?php echo RALLYPOINT; ?>" title="<?php echo RALLYPOINT; ?>">
        </a>
        <p class="build_desc"><?php echo RALLYPOINT_DESC; ?></p>
    </div>

    <?php include '16_menu.tpl'; ?>

    <div id="raidList" class="gk-farms-content">
        <?php include 'Templates/goldClub/farmlist.tpl'; ?>
    </div>

    <?php if ($hideEvasion == 0 && $farmGoldClubActive): ?>
    <table id="raidList" class="gk-farms-evasion" cellpadding="1" cellspacing="1">
        <thead>
            <tr><th colspan="4"><?php echo EVASION_SETTINGS; ?></th></tr>
            <tr>
                <td></td>
                <td><?php echo VILLAGE; ?></td>
                <td><?php echo OWN_TROOPS; ?></td>
                <td><?php echo REINFORCEMENT; ?></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($session->villages as $wref):
                $vname = $database->getVillageField($wref, 'name');
                $vchecked = $database->getVillageField($wref, 'evasion');
                $reinf = $database->getEnforceVillage($wref, 0);
                $checked = $vchecked == 1 ? 'checked' : '';
            ?>
            <tr>
                <td><input type="checkbox" class="check" name="hideShow" onclick="window.location.href='?gid=16&amp;t=99&amp;evasion=<?php echo (int) $wref; ?>';" <?php echo $checked; ?>></td>
                <td><?php echo htmlspecialchars($vname, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><div style="text-align:center"><?php echo (int) $database->getUnitsNumber($wref); ?></div></td>
                <td><div style="text-align:center"><?php echo count($reinf); ?></div></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <form action="build.php?id=39&amp;t=99" method="POST" class="gk-farms-evasion-form">
        <br>
        <?php echo SEND_TROOPS_AWAY_MAX; ?>
        <input class="text" type="text" name="maxevasion" value="<?php echo (int) $user['maxevasion']; ?>" maxlength="3" style="width:50px;">
        <?php echo TIMES; ?>
        <span class="none">(<?php echo COSTS; ?>: <img src="<?php echo GP_LOCATE; ?>img/a/gold_g.gif" alt="<?php echo GOLD; ?>" title="<?php echo GOLD; ?>"><b>2</b> <?php echo PER_EVASION; ?>)</span>
        <div class="clear"></div>
        <p><button value="ok" name="s1" id="btn_ok" class="trav_buttons" tabindex="8">OK</button></p>
    </form>
    <?php endif; ?>
</div>
