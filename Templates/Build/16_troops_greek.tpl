<?php
/**
 * Greek.sa horizontal troop grid — icons row + counts row (always 10 units + hero).
 */

$tribe = (int) $session->tribe;
$start = ($tribe - 1) * 10 + 1;
$end = $tribe * 10;
?>
<tr>
    <?php for ($i = $start; $i <= $end; $i++): ?>
        <td><img src="img/x.gif" class="unit u<?php echo (int) $i; ?>" title="<?php echo htmlspecialchars($technology->getUnitName($i), ENT_QUOTES, 'UTF-8'); ?>" alt=""></td>
    <?php endfor; ?>
    <td><img src="img/x.gif" class="unit uhero" title="<?php echo htmlspecialchars(U0, ENT_QUOTES, 'UTF-8'); ?>" alt=""></td>
</tr>
<tr>
    <?php for ($i = $start; $i <= $end; $i++):
        $cnt = (int) ($village->unitarray['u' . $i] ?? 0);
    ?>
        <td class="<?php echo $cnt === 0 ? 'none' : ''; ?>"><?php echo $cnt; ?></td>
    <?php endfor; ?>
    <td class="<?php echo (int) $village->unitarray['hero'] === 0 ? 'none' : ''; ?>"><?php echo (int) $village->unitarray['hero']; ?></td>
</tr>
