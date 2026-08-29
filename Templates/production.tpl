<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       production.tpl                                              ##
##  Developed by:  Dzoki                                                       ##
##  Refactored by: Shadow Incremental Refactor 			                       ##
##  License:       Novaterra Project                                            ##
##  Copyright:     Novaterra (c) 2010-2026. All rights reserved.                ##
##                                                                             ##
##  Incremental Refactor Notes:                                                ##
##  - Preserved original functionality                                         ##
##  - Compatible with older PHP 7+ environments                                ##
##  - Reduced repeated method calls where possible                             ##
##  - Added small safety layer for village object                              ##
##  - Improved readability                                                     ##
##                                                                             ##
#################################################################################

/**
 * ---------------------------------------------------------
 * Safety checks (legacy PHP compatibility)
 * ---------------------------------------------------------
 */
$woodProd = isset($village) ? $village->getProd("wood") : 0;
$clayProd = isset($village) ? $village->getProd("clay") : 0;
$ironProd = isset($village) ? $village->getProd("iron") : 0;
$cropProd = isset($village) ? $village->getProd("crop") : 0;
$gkProdCols = !empty($gkShell) ? 3 : 4;
?>

<table id="production" cellpadding="1" cellspacing="1"<?php if (!empty($gkShell)) { echo ' class="gk-prud"'; } ?>>

<?php if (!empty($gkShell)) { ?>
    <colgroup>
        <col class="gk-col-per" />
        <col class="gk-col-num" />
        <col class="gk-col-lbl" />
    </colgroup>
<?php } ?>

    <thead>
        <tr>
            <th colspan="<?php echo (int) $gkProdCols; ?>">
                <?php echo PRODUCTION; ?><?php if (empty($gkShell)) { echo ':'; } ?>
            </th>
        </tr>
    </thead>

    <tbody>

<?php if (!empty($gkShell)) { ?>

        <!-- Wood -->
        <tr>
            <td class="per"><?php echo PER_HR; ?></td>
            <td class="num"><b><?php echo $woodProd; ?></b></td>
            <td class="res gk-prod-lbl">
                <img class="r1" src="img/x.gif" alt="<?php echo GK_LUMBER; ?>" title="<?php echo GK_LUMBER; ?>" /><?php echo GK_LUMBER; ?>:
            </td>
        </tr>

        <!-- Clay -->
        <tr>
            <td class="per"><?php echo PER_HR; ?></td>
            <td class="num"><b><?php echo $clayProd; ?></b></td>
            <td class="res gk-prod-lbl">
                <img class="r2" src="img/x.gif" alt="<?php echo GK_CLAY; ?>" title="<?php echo GK_CLAY; ?>" /><?php echo GK_CLAY; ?>:
            </td>
        </tr>

        <!-- Iron -->
        <tr>
            <td class="per"><?php echo PER_HR; ?></td>
            <td class="num"><b><?php echo $ironProd; ?></b></td>
            <td class="res gk-prod-lbl">
                <img class="r3" src="img/x.gif" alt="<?php echo GK_IRON; ?>" title="<?php echo GK_IRON; ?>" /><?php echo GK_IRON; ?>:
            </td>
        </tr>

        <!-- Crop -->
        <tr>
            <td class="per"><?php echo PER_HR; ?></td>
            <td class="num"><b><?php echo $cropProd; ?></b></td>
            <td class="res gk-prod-lbl">
                <img class="r4" src="img/x.gif" alt="<?php echo GK_CROP; ?>" title="<?php echo GK_CROP; ?>" /><?php echo GK_CROP; ?>:
            </td>
        </tr>

<?php } else { ?>

        <!-- Wood -->
        <tr>
            <td class="ico">
                <img class="r1" src="img/x.gif" alt="<?php echo LUMBER; ?>" title="<?php echo LUMBER; ?>" />
            </td>

            <td class="res">
                <?php echo LUMBER; ?>:
            </td>

            <td class="num">
                <?php echo $woodProd; ?>
            </td>

            <td class="per">
                <?php echo PER_HR; ?>
            </td>
        </tr>

        <!-- Clay -->
        <tr>
            <td class="ico">
                <img class="r2" src="img/x.gif" alt="<?php echo CLAY; ?>" title="<?php echo CLAY; ?>" />
            </td>

            <td class="res">
                <?php echo CLAY; ?>:
            </td>

            <td class="num">
                <?php echo $clayProd; ?>
            </td>

            <td class="per">
                <?php echo PER_HR; ?>
            </td>
        </tr>

        <!-- Iron -->
        <tr>
            <td class="ico">
                <img class="r3" src="img/x.gif" alt="<?php echo IRON; ?>" title="<?php echo IRON; ?>" />
            </td>

            <td class="res">
                <?php echo IRON; ?>:
            </td>

            <td class="num">
                <?php echo $ironProd; ?>
            </td>

            <td class="per">
                <?php echo PER_HR; ?>
            </td>
        </tr>

        <!-- Crop -->
        <tr>
            <td class="ico">
                <img class="r4" src="img/x.gif" alt="<?php echo CROP; ?>" title="<?php echo CROP; ?>" />
            </td>

            <td class="res">
                <?php echo CROP; ?>:
            </td>

            <td class="num">
                <?php echo $cropProd; ?>
            </td>

            <td class="per">
                <?php echo PER_HR; ?>
            </td>
        </tr>

<?php } ?>

    </tbody>

</table>
