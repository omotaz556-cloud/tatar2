<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : search.tpl                                 			   ##
##  Type           : Rally Point Destination Village Search                    ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  URLs:          : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : Novaterra Project                                          ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.              ##
## --------------------------------------------------------------------------- ##
#################################################################################

if (!isset($disabled)) {
    $disabled = '';
}
if (!isset($disabledr)) {
    $disabledr = '';
}

if (!empty($form) && !empty($form->valuearray)) {
    if (!empty($form->valuearray['disabled'])) {
        $disabled = $form->valuearray['disabled'];
    }
    if (!empty($form->valuearray['disabledr'])) {
        $disabledr = $form->valuearray['disabledr'];
    }
}

$formMission = !empty($form->valuearray['c']) ? (int) $form->valuearray['c'] : 0;
if ($formMission >= 2 && $formMission <= 4) {
    $attackChecked = ($formMission === 3);
    $raidChecked = ($formMission === 4);
    $reinforcementChecked = ($formMission === 2);
} else {
    $attackChecked = empty($disabled);
    $raidChecked = (!empty($disabledr) && !empty($disabled));
    $reinforcementChecked = (!$attackChecked && !$raidChecked && empty($disabledr));
}

if (isset($_GET['z'])) {
    $coor = $database->getCoor($_GET['z']);
} else {
    $coor['x'] = $form->getValue('x');
    $coor['y'] = $form->getValue('y');
}

$coordXLabel = defined('GK_COORD_X') ? GK_COORD_X : 'X';
$coordYLabel = defined('GK_COORD_Y') ? GK_COORD_Y : 'Y';
?>
                <input type="hidden" name="disabledr" value="<?php echo htmlspecialchars($disabledr ?? '', ENT_QUOTES); ?>">
                <input type="hidden" name="disabled" value="<?php echo htmlspecialchars($disabled ?? '', ENT_QUOTES); ?>">
                <table id="coords" class="gk-a2b-target" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo TZ_A2B_TARGET_INFO; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="row-vil">
                                <span class="gk-a2b-label"><?php echo TZ_VILLAGE_NAME_LABEL; ?>:</span>
                                <input class="text" name="dname" value="<?php echo htmlspecialchars($form->getValue('dname'), ENT_QUOTES); ?>" maxlength="20" type="text" list="dnameSuggest" autocomplete="off" tabindex="1">
                                <?php include("Templates/villageAutocomplete.tpl"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="row-coo">
                                <span class="gk-a2b-label"><?php echo TZ_OR_COORDINATES_LABEL; ?>:</span>
                                <span class="gk-a2b-coord-label"><?php echo $coordXLabel; ?>:</span>
                                <input class="text" name="x" value="<?php echo htmlspecialchars($coor['x'] ?? '', ENT_QUOTES); ?>" maxlength="4" type="text" tabindex="2">
                                <span class="gk-a2b-coord-label"><?php echo $coordYLabel; ?>:</span>
                                <input class="text" name="y" value="<?php echo htmlspecialchars($coor['y'] ?? '', ENT_QUOTES); ?>" maxlength="4" type="text" tabindex="3">
                            </td>
                        </tr>
                        <?php if (empty($disabledr)): ?>
                        <tr>
                            <td class="sel">
                                <label>
                                    <input class="radio" name="c" value="2" type="radio"
                                        <?php if ($reinforcementChecked) echo 'checked="checked"'; ?>
                                        tabindex="4">
                                    <?php echo REINFORCEMENT; ?>
                                </label>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="sel">
                                <label>
                                    <input class="radio" name="c" value="3" type="radio"
                                        <?php if ($attackChecked) echo 'checked="checked"'; ?>
                                        <?php echo $disabled; ?>
                                        tabindex="5">
                                    <?php echo TZ_FULL_ATTACK; ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="sel">
                                <label>
                                    <input class="radio" name="c" value="4" type="radio"
                                        <?php if ($raidChecked) echo 'checked="checked"'; ?>
                                        tabindex="6">
                                    <?php echo TZ_RAID_ATTACK; ?>
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="gk-a2b-ok">
                    <button value="ok" name="s1" id="btn_ok" class="gk-a2b-send-btn" type="submit" tabindex="7" onclick="this.disabled=true;this.form.submit();"><?php echo SEND; ?></button>
                </p>
            </div>
        </div>
    </form>
    <p class="error"><?php echo $form->getError('error'); ?></p>
    </div>

    <?php
    $id = isset($rallyFieldId) ? (int) $rallyFieldId : 39;
    include dirname(__DIR__) . '/Build/upgrade.tpl';
    ?>
</div>
