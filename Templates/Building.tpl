<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       Building.tpl                                                ##
##  Developed by:  Dzoki                                                       ##
##  Refactored by: Shadow Incremental Refactor 			                       ##
##  License:       Novaterra Project                                            ##
##  Copyright:     Novaterra (c) 2010-2025. All rights reserved.                ##
#################################################################################

$building->loadBuilding();

if (!function_exists('safeHTML')) {
    function safeHTML($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

if (!$building->NewBuilding) {
    return;
}
?>

<table cellpadding="1" cellspacing="1" id="building_contract">

    <thead>
        <tr>
            <th colspan="4">
                <?php echo BUILDING_UPGRADING; ?>
                <?php
                // Inline include so the finish control cannot be skipped by path issues.
                $__gf = __DIR__ . DIRECTORY_SEPARATOR . 'Build' . DIRECTORY_SEPARATOR . 'gold_finish_button.tpl';
                if (is_file($__gf)) {
                    include $__gf;
                } else {
                    echo '<!-- gold_finish_button.tpl missing: ' . htmlspecialchars($__gf, ENT_QUOTES, 'UTF-8') . ' -->';
                }
                ?>
            </th>
        </tr>
    </thead>

    <tbody>

    <?php
    if (!empty($building->buildArray) && is_array($building->buildArray)) {

        foreach ($building->buildArray as $jobs) {

            $jobId     = isset($jobs['id']) ? (int) $jobs['id'] : 0;
            $fieldId   = isset($jobs['field']) ? (int) $jobs['field'] : 0;
            $type      = isset($jobs['type']) ? (int) $jobs['type'] : 0;
            $level     = isset($jobs['level']) ? (int) $jobs['level'] : 0;
            $timestamp = isset($jobs['timestamp']) ? (int) $jobs['timestamp'] : time();
            $master    = isset($jobs['master']) ? (int) $jobs['master'] : 0;
            $loopcon   = isset($jobs['loopcon']) ? (int) $jobs['loopcon'] : 0;

            $buildingName = Building::procResType($type);

            $remainingTime = $timestamp - time();
            if ($remainingTime < 0) {
                $remainingTime = 0;
            }

            $finishTime = date('H:i', $timestamp);
    ?>

        <tr>

            <td class="ico">
                <a href="?d=<?php echo $jobId; ?>&amp;a=0&amp;c=<?php echo safeHTML($session->checker); ?>">
                    <img src="img/x.gif"
                         class="del"
                         title="<?php echo CANCEL; ?>"
                         alt="<?php echo CANCEL; ?>" />
                </a>
            </td>

            <td>

                <?php if ($master == 0) { ?>

                    <a href="build.php?id=<?php echo $fieldId; ?>">
                        <?php echo safeHTML($buildingName); ?>
                    </a>

                    (<?php echo LEVEL . ' ' . $level; ?>)

                    <?php
                    if ($loopcon == 1) {
                        echo WAITING_LOOP;
                    }
                    ?>

                <?php } else { ?>
                    <a href="build.php?id=<?php echo $fieldId; ?>">
                        <?php echo safeHTML($buildingName); ?>
                    </a>
                    <span class="none">
                        (<?php echo LEVEL . ' ' . $level . ' ) (' . CONSTRUCTING_MASTER_BUILDER; ?>)
                    </span>

                <?php } ?>

            </td>

            <?php if ($master == 0) { ?>

                <td>
                    <?php echo P_IN; ?>
                    <span id="timer<?php echo ++$session->timer; ?>">
                        <?php echo $generator->getTimeFormat($remainingTime); ?>
                    </span>
                    <?php echo TZ_HRS_2; ?>
                </td>

                <td>
                    <?php echo DONE_AT . ' ' . $finishTime; ?>
                </td>

            <?php } else { ?>

                <td colspan="2">&nbsp;</td>

            <?php } ?>

        </tr>

    <?php
        }
    }
    ?>

    </tbody>

</table>

<script type="text/javascript">
var bld=[{"stufe":1,"gid":"1","aid":"3"}];
</script>
