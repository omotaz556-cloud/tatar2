<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : BLACKSMITH                                                ##
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

$field         = 'f' . $id;
$currentLevel  = (int) ($village->resarray[$field] ?? 0);
$hasBlacksmith = $building->getTypeLevel(12) > 0;
?>
<div id="build" class="gid12">
    <a href="#" onclick="return Popup(12,4);" class="build_logo">
        <img class="building g12" src="img/x.gif" alt="<?= BLACKSMITH ?>" title="<?= BLACKSMITH ?>">
    </a>

    <h1>
        <?= BLACKSMITH ?>
        <span class="level"><?= LEVEL ?> <?= $currentLevel ?></span>
    </h1>

    <p class="build_desc"><?= BLACKSMITH_DESC ?></p>

    <?php if ($hasBlacksmith): ?>
        <?php include '12_upgrades.tpl'; ?>
    <?php else: ?>
        <p><b><?= UPGRADES_COMMENCE_BLACKSMITH ?></b></p>
    <?php endif; ?>

    <?php include 'upgrade.tpl'; ?>
</div>