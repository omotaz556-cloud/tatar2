<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : RALLYPOINT MENU                                           ##
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

$t = isset($_GET['t']) ? (int) $_GET['t'] : 0;
$isOverview = ($t === 0);
$isGold = ($t === 99);
$isA2bSend = !empty($a2bSendPage);
$isWarsim = !empty($warsimPage);
$rallyMenuId = isset($rallyFieldId) ? (int) $rallyFieldId : (isset($id) ? (int) $id : 39);
$sendTroopsLabel = defined('TZ_RALLY_SEND_TROOPS') ? TZ_RALLY_SEND_TROOPS : SEND_TROOPS;
$battleSimLabel = defined('TZ_BATTLE_SIMULATOR') ? TZ_BATTLE_SIMULATOR : Q20_RESP1;
$farmsLabel = defined('TZ_RALLY_FARMS') ? TZ_RALLY_FARMS : GOLD_CLUB;
?>
<div id="textmenu" class="gk-rally-nav">
    <a href="build.php?id=<?php echo $rallyMenuId; ?>"<?php if ($isOverview && !$isA2bSend && !$isWarsim) echo ' class="selected"'; ?>><?php echo OVERVIEW; ?></a>
    | <a href="a2b.php"<?php if ($isA2bSend) echo ' class="selected"'; ?>><?php echo $sendTroopsLabel; ?></a>
    | <a href="warsim.php"<?php if ($isWarsim) echo ' class="selected"'; ?>><?php echo $battleSimLabel; ?></a>
    | <a href="build.php?id=<?php echo $rallyMenuId; ?>&amp;t=99"<?php if ($isGold) echo ' class="selected"'; ?>><?php echo $farmsLabel; ?></a>
</div>
