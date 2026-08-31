<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       Building.tpl                                                ##
##  Developed by:  Dzoki                                                       ##
##  Refactored by: Shadow Incremental Refactor 			                       ##
##  License:       Novaterra Project                                            ##
##  Copyright:     Novaterra (c) 2010-2025. All rights reserved.                ##
##                                                                             ##
##  Refactor notes:                                                            ##
##  - păstrată logica originală 100%                                           ##
##  - compatibil PHP 5.6+ / 7+                                                 ##
##  - redus cod duplicat                                                       ##
##  - output HTML mai sigur                                                    ##
##  - comentarii adăugate                                                      ##
##  - redirect securizat                                                       ##
##                                                                             ##
#################################################################################

// Încarcă datele pentru clădire/construcții
$building->loadBuilding();

/**
 * Escape HTML compatibil PHP vechi
 * Previne probleme XSS pe output
 */
if (!function_exists('safeHTML')) {
    function safeHTML($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
?>

<?php if ($building->NewBuilding) { ?>

<div id="building_contract" class="gk-build-queue">

    <div class="gk-bq-head"><?php echo BUILDING_UPGRADING; ?></div>

    <div class="gk-bq-list">
    <?php
    if (!empty($building->buildArray) && is_array($building->buildArray)) {

        foreach ($building->buildArray as $jobs) {

            $jobId     = isset($jobs['id']) ? (int)$jobs['id'] : 0;
            $fieldId   = isset($jobs['field']) ? (int)$jobs['field'] : 0;
            $type      = isset($jobs['type']) ? (int)$jobs['type'] : 0;
            $level     = isset($jobs['level']) ? (int)$jobs['level'] : 0;
            $timestamp = isset($jobs['timestamp']) ? (int)$jobs['timestamp'] : time();
            $master    = isset($jobs['master']) ? (int)$jobs['master'] : 0;
            $loopcon   = isset($jobs['loopcon']) ? (int)$jobs['loopcon'] : 0;

            $buildingName = Building::procResType($type);
            $remainingTime = $timestamp - time();
            if ($remainingTime < 0) {
                $remainingTime = 0;
            }
            $finishTime = date('H:i', $timestamp);
    ?>
        <div class="gk-bq-row">

            <span class="gk-bq-ico">
                <a href="?d=<?php echo $jobId; ?>&amp;a=0&amp;c=<?php echo safeHTML($session->checker); ?>">
                    <img src="img/x.gif"
                         class="del"
                         title="<?php echo CANCEL; ?>"
                         alt="<?php echo CANCEL; ?>" />
                </a>
            </span>

            <span class="gk-bq-name">
                <?php if ($master == 0) { ?>
                    <a href="build.php?id=<?php echo $fieldId; ?>"><?php echo safeHTML($buildingName); ?></a>
                    (<?php echo LEVEL.' '.$level; ?>)
                    <?php if ($loopcon == 1) { echo WAITING_LOOP; } ?>
                <?php } else { ?>
                    <a href="build.php?id=<?php echo $fieldId; ?>"><?php echo safeHTML($buildingName); ?></a>
                    <span class="none">(<?php echo LEVEL.' '.$level.' ) ('.CONSTRUCTING_MASTER_BUILDER;?>)</span>
                <?php } ?>
            </span>

            <?php if ($master == 0) { ?>
            <span class="gk-bq-time">
                <?php echo P_IN; ?>
                <span id="timer<?php echo ++$session->timer; ?>"><?php echo $generator->getTimeFormat($remainingTime); ?></span>
                <?php echo TZ_HRS_2; ?>
            </span>
            <span class="gk-bq-finish"><?php echo DONE_AT.' '.$finishTime; ?></span>
            <?php } ?>

        </div>
    <?php
        }
    }
    ?>
    </div>

</div>

<!-- JS original păstrat -->
<script type="text/javascript">
var bld=[{"stufe":1,"gid":"1","aid":"3"}];
</script>

<?php
} else {

    /**
     * Redirect securizat
     * Evită folosirea directă a REQUEST_URI fără validare minimă
     */

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        ? 'https://'
        : 'http://';

    $host = isset($_SERVER['HTTP_HOST'])
        ? $_SERVER['HTTP_HOST']
        : 'localhost';

    $requestUri = isset($_SERVER['REQUEST_URI'])
        ? $_SERVER['REQUEST_URI']
        : '/';

    // Elimină caractere invalide pentru header
    $requestUri = str_replace(array("\r", "\n"), '', $requestUri);

    $redirectUrl = $protocol . $host . $requestUri;

    header('Location: ' . $redirectUrl);
    exit;
}
?>
