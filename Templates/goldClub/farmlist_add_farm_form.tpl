<?php

if (!function_exists('tz_farm_unit_rows')) {
    function tz_farm_unit_rows()
    {
        global $session, $village, $unitsbytype, $technology;

        $start = ($session->tribe - 1) * 10 + 1;
        $end = $session->tribe * 10;
        $Gtribe = $session->tribe == 1 ? '' : $session->tribe - 1;
        $rows = array();

        for ($i = $start; $i <= $end && count($rows) < 5; $i++) {
            $scoutIds = isset($unitsbytype['scout']) ? $unitsbytype['scout'] : array();
            if (in_array($i, $scoutIds, true)) {
                continue;
            }

            $slot = count($rows) + 1;
            $idx = $i - $start + 1;

            if ($idx < 10) {
                $have = (int) ($village->unitarray['u' . $Gtribe . $idx] ?? 0);
            } else {
                $have = (int) ($village->unitarray['u' . ($session->tribe * 10)] ?? 0);
            }

            $rows[] = array(
                'slot' => $slot,
                'unitId' => $i,
                'name' => $technology->getUnitName($i),
                'have' => $have,
            );
        }

        return $rows;
    }
}

$farmFormLid = isset($farmFormLid) ? (int) $farmFormLid : 0;
if ($farmFormLid <= 0) {
    $lidRes = mysqli_query(
        $database->dblink,
        'SELECT id FROM ' . TB_PREFIX . 'farmlist WHERE owner = ' . (int) $session->uid
        . ' AND wref = ' . (int) $village->wid . ' ORDER BY id ASC LIMIT 1'
    );
    if ($lidRes && ($lidRow = mysqli_fetch_assoc($lidRes))) {
        $farmFormLid = (int) $lidRow['id'];
    }
}

$farmUnitRows = tz_farm_unit_rows();
$farmCoordX = isset($_POST['x']) ? htmlspecialchars((string) $_POST['x'], ENT_QUOTES, 'UTF-8') : '';
$farmCoordY = isset($_POST['y']) ? htmlspecialchars((string) $_POST['y'], ENT_QUOTES, 'UTF-8') : '';
$farmTroopAmount = isset($_POST['troop_amount']) ? (int) $_POST['troop_amount'] : 0;
$farmUnitPick = isset($_POST['unit_pick']) ? (int) $_POST['unit_pick'] : 1;
$farmAddError = $farmAddError ?? null;

$farmPageTitle = defined('TZ_FARMS_ATTACK_ADD') ? TZ_FARMS_ATTACK_ADD : (ATTACK . ' | ' . TZ_ADD_FARM);
$farmEmptyMsg = defined('TZ_NO_FARMS_MUST_ADD') ? TZ_NO_FARMS_MUST_ADD : 'لا يوجد مزارع، يجب إضافة مزرعة';
$farmAddHeader = defined('TZ_ADD_FARM') ? TZ_ADD_FARM : TZ_ADD;
$farmCoordsLbl = defined('TZ_FARM_COORDS') ? TZ_FARM_COORDS : (defined('TZ_OR_COORDINATES_LABEL') ? TZ_OR_COORDINATES_LABEL : 'إحداثيات');
$farmTroopTypeLbl = defined('TZ_FARM_TROOP_TYPE') ? TZ_FARM_TROOP_TYPE : TROOPS;
$farmTroopCountLbl = defined('TZ_FARM_TROOP_COUNT') ? TZ_FARM_TROOP_COUNT : TZ_A2B_SEND_COL;
$coordXLabel = defined('GK_COORD_X') ? GK_COORD_X : 'X';
$coordYLabel = defined('GK_COORD_Y') ? GK_COORD_Y : 'Y';
$showFarmEmptyMsg = !empty($showFarmEmptyMsg);
?>
<div id="gk-farm-add" class="gk-farms-add-wrap">
    <h2 class="gk-farms-page-title"><?php echo htmlspecialchars($farmPageTitle, ENT_QUOTES, 'UTF-8'); ?></h2>

    <?php if ($showFarmEmptyMsg): ?>
    <p class="gk-farms-empty-msg"><?php echo htmlspecialchars($farmEmptyMsg, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <?php if (!empty($farmAddError)): ?>
    <p class="gk-farms-error"><?php echo htmlspecialchars($farmAddError, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form class="gk-farms-add-form" action="build.php?id=39&amp;t=99&amp;action=addraid" method="post">
        <input type="hidden" name="action" value="addSlot">
        <?php if ($farmFormLid > 0): ?>
        <input type="hidden" name="lid" value="<?php echo $farmFormLid; ?>">
        <?php endif; ?>

        <table class="gk-farms-add-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th colspan="3" class="gk-farms-add-title">
                        <?php echo htmlspecialchars($farmAddHeader, ENT_QUOTES, 'UTF-8'); ?>
                        <span class="gk-farms-gold-cost">(3 <img src="img/x.gif" class="gold" alt="<?php echo defined('GOLD') ? GOLD : 'Gold'; ?>" title="<?php echo defined('GOLD') ? GOLD : 'Gold'; ?>" />)</span>
                    </th>
                </tr>
                <tr>
                    <th class="gk-farms-th-coords"><?php echo htmlspecialchars($farmCoordsLbl, ENT_QUOTES, 'UTF-8'); ?></th>
                    <th class="gk-farms-th-type"><?php echo htmlspecialchars($farmTroopTypeLbl, ENT_QUOTES, 'UTF-8'); ?></th>
                    <th class="gk-farms-th-count"><?php echo htmlspecialchars($farmTroopCountLbl, ENT_QUOTES, 'UTF-8'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="gk-farms-coords">
                        <div class="gk-farms-coord-line">
                            <span class="gk-farms-coord-label"><?php echo htmlspecialchars($coordXLabel, ENT_QUOTES, 'UTF-8'); ?>:</span>
                            <input class="text" name="x" value="<?php echo $farmCoordX; ?>" maxlength="4" type="text" tabindex="1">
                        </div>
                        <div class="gk-farms-coord-line">
                            <span class="gk-farms-coord-label"><?php echo htmlspecialchars($coordYLabel, ENT_QUOTES, 'UTF-8'); ?>:</span>
                            <input class="text" name="y" value="<?php echo $farmCoordY; ?>" maxlength="4" type="text" tabindex="2">
                        </div>
                    </td>
                    <td class="gk-farms-troop-type">
                        <div class="gk-farms-troop-list">
                            <?php foreach ($farmUnitRows as $unitRow): ?>
                            <label class="gk-farms-troop-label">
                                <input class="radio" name="unit_pick" value="<?php echo (int) $unitRow['slot']; ?>" type="radio"
                                    <?php if ($farmUnitPick === (int) $unitRow['slot']) echo 'checked="checked"'; ?>>
                                <img class="unit u<?php echo (int) $unitRow['unitId']; ?>" src="img/x.gif"
                                    title="<?php echo htmlspecialchars($unitRow['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                    alt="<?php echo htmlspecialchars($unitRow['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                <span class="gk-farms-troop-name"><?php echo htmlspecialchars($unitRow['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <span class="gk-farms-troop-have">(<?php echo (int) $unitRow['have']; ?>)</span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td class="gk-farms-troop-count">
                        <input class="text" name="troop_amount" value="<?php echo $farmTroopAmount > 0 ? (int) $farmTroopAmount : ''; ?>" maxlength="6" type="text" tabindex="20">
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="gk-farms-ok">
            <button type="submit" class="gk-farms-add-btn" name="s1" value="1"><?php echo TZ_ADD; ?></button>
        </p>
    </form>
</div>
