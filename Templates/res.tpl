<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       res.tpl                                                     ##
##  Developed by:  Dzoki                                                       ##
##  Refactored by: Shadow Incremental Refactor 			                       ##
##  License:       Novaterra Project                                            ##
##  Copyright:     Novaterra (c) 2010-2026. All rights reserved.                ##
##                                                                             ##
##  Incremental Refactor Notes:                                                ##
##  - Preserved original functionality                                         ##
##  - Added safety checks for legacy PHP                                       ##
##  - Reduced repeated property access                                         ##
##  - Improved readability                                                     ##
##  - Kept UI structure unchanged                                              ##
##                                                                             ##
#################################################################################

/**
 * ---------------------------------------------------------
 * Safety check (avoid undefined village context)
 * ---------------------------------------------------------
 */
if (!empty($village)) {

    /**
     * -----------------------------------------------------
     * Production values (rounded)
     * -----------------------------------------------------
     */
    $wood = round($village->getProd("wood"));
    $clay = round($village->getProd("clay"));
    $iron = round($village->getProd("iron"));
    $crop = round($village->getProd("crop"));

    /**
     * Total crop production capacity
     */
    $totalproduction = $village->allcrop;

    /**
     * Safely cache values to reduce repeated access
     */
    $woodStore = round($village->awood);
    $clayStore = round($village->aclay);
    $ironStore = round($village->airon);
    $cropStore = round($village->acrop);

    $maxStore  = $village->maxstore;
    $maxCrop   = $village->maxcrop;
?>

<div id="res">
<div id="resWrap">

    <!-- ================= RESOURCES ================= -->
    <?php $tzResRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang(); ?>
    <?php if ($tzResRtl) { ?>
    <!-- RTL Resbar-like distribution (design identity):
         warehouse cap | wood | clay | iron | granary cap | crop | upkeep
         Current amounts only in green pills; caps in light chips.
         #l1–#l4 keep data-max for unx.js timers (see unx.js mb()). -->
    <table cellpadding="1" cellspacing="1" class="resbar-rtl">
        <tr>
            <td class="rescap" title="<?php echo defined('WAREHOUSE') ? WAREHOUSE : 'مخزن'; ?>">
                <img src="img/x.gif" class="g10Icon" alt="" />
                <span class="rescap-val"><?php echo number_format((int) $maxStore); ?></span>
            </td>
            <td class="respill">
                <span id="l4" title="<?php echo $wood; ?>" data-max="<?php echo (int) $maxStore; ?>"><?php echo (int) $woodStore; ?></span>
                <img src="img/x.gif" class="r1" alt="<?php echo LUMBER; ?>" title="<?php echo LUMBER; ?>" />
            </td>
            <td class="respill">
                <span id="l3" title="<?php echo $clay; ?>" data-max="<?php echo (int) $maxStore; ?>"><?php echo (int) $clayStore; ?></span>
                <img src="img/x.gif" class="r2" alt="<?php echo CLAY; ?>" title="<?php echo CLAY; ?>" />
            </td>
            <td class="respill">
                <span id="l2" title="<?php echo $iron; ?>" data-max="<?php echo (int) $maxStore; ?>"><?php echo (int) $ironStore; ?></span>
                <img src="img/x.gif" class="r3" alt="<?php echo IRON; ?>" title="<?php echo IRON; ?>" />
            </td>
            <td class="rescap" title="<?php echo defined('GRANARY') ? GRANARY : 'مخزن حبوب'; ?>">
                <img src="img/x.gif" class="g11Icon" alt="" />
                <span class="rescap-val"><?php echo number_format((int) $maxCrop); ?></span>
            </td>
            <td class="respill">
                <?php if ($village->acrop > 0) { ?>
                    <span id="l1" title="<?php echo $crop; ?>" data-max="<?php echo (int) $maxCrop; ?>"><?php echo (int) $cropStore; ?></span>
                <?php } else { ?>
                    <span title="<?php echo $crop; ?>" data-max="<?php echo (int) $maxCrop; ?>">0</span>
                <?php } ?>
                <img src="img/x.gif" class="r4" alt="<?php echo CROP; ?>" title="<?php echo CROP; ?>" />
            </td>
            <td class="respill">
                <span><?php echo (int) ($village->pop + $technology->getUpkeep($village->unitall, 0)); ?>/<?php echo (int) $totalproduction; ?></span>
                <img src="img/x.gif" class="r5" alt="<?php echo CROP_COM; ?>" title="<?php echo CROP_COM; ?>" />
            </td>
        </tr>
    </table>
    <?php } else { ?>
    <table cellpadding="1" cellspacing="1">
        <tr>

            <!-- Wood -->
            <td>
                <img src="img/x.gif" class="r1" alt="<?php echo LUMBER; ?>" title="<?php echo LUMBER; ?>" />
            </td>

            <td id="l4" title="<?php echo $wood; ?>">
                <?php echo $woodStore . "/" . $maxStore; ?>
            </td>

            <!-- Clay -->
            <td>
                <img src="img/x.gif" class="r2" alt="<?php echo CLAY; ?>" title="<?php echo CLAY; ?>" />
            </td>

            <td id="l3" title="<?php echo $clay; ?>">
                <?php echo $clayStore . "/" . $maxStore; ?>
            </td>

            <!-- Iron -->
            <td>
                <img src="img/x.gif" class="r3" alt="<?php echo IRON; ?>" title="<?php echo IRON; ?>" />
            </td>

            <td id="l2" title="<?php echo $iron; ?>">
                <?php echo $ironStore . "/" . $maxStore; ?>
            </td>

            <!-- Crop -->
            <td>
                <img src="img/x.gif" class="r4" alt="<?php echo CROP; ?>" title="<?php echo CROP; ?>" />
            </td>

            <?php if ($village->acrop > 0) { ?>
                <td id="l1" title="<?php echo $crop; ?>">
                    <?php echo $cropStore . "/" . $maxCrop; ?>
                </td>
            <?php } else { ?>
                <td title="<?php echo $crop; ?>">
                    0/<?php echo $maxCrop; ?>
                </td>
            <?php } ?>

            <!-- Crop consumption -->
            <td>
                <img src="img/x.gif" class="r5" alt="<?php echo CROP_COM; ?>" title="<?php echo CROP_COM; ?>" />
            </td>

            <td>
                <?php echo ($village->pop + $technology->getUpkeep($village->unitall, 0)) . "/" . $totalproduction; ?>
            </td>

        </tr>
    </table>
    <?php } ?>

</div>
</div>

<?php } ?>