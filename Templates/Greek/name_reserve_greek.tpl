<?php
/**
 * Greek.sa name reservation (spieler.php?s=3&nr=1) — حجز الاسم.
 * Reference UI: single centered grey notice only.
 */

$gkLblNeedWw = defined('TZ_GK_NAME_RESERVE_NEED_WW')
    ? TZ_GK_NAME_RESERVE_NEED_WW
    : 'يجب ان تكون قد فزت مسبقا بمعجزة العالم بهذا البريد الالكتروني ليمكنك حجز الاسم';
?>
<div id="name_reservation" class="gk-name-reserve">
    <p class="gk-name-reserve-msg"><?php echo htmlspecialchars($gkLblNeedWw, ENT_QUOTES, 'UTF-8'); ?></p>
</div>
