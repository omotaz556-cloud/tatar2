<?php
$serverLabel = defined('SERVER_NAME') ? SERVER_NAME : 'Novaterra';
$clockNow = date('H:i:s');
$gold = isset($session->gold) ? (int) $session->gold : 0;

$n5class = 'i4';
if (!empty($message->unread) && empty($message->nunread)) {
    $n5class = 'i2';
} elseif (empty($message->unread) && !empty($message->nunread)) {
    $n5class = 'i3';
} elseif (!empty($message->unread) && !empty($message->nunread)) {
    $n5class = 'i1';
}
?>
<div class="gk-topbar">
    <div class="gk-topbar__inner">
        <div class="gk-world">
            <?php echo function_exists('tz_day_night_icon_html') ? tz_day_night_icon_html('gk-daynight') : '<span class="gk-moon" aria-hidden="true"></span>'; ?>
            <span><?php echo htmlspecialchars($serverLabel, ENT_QUOTES, 'UTF-8'); ?></span>
            <span class="gk-clock">(<span id="_Clock"><?php echo htmlspecialchars($clockNow, ENT_QUOTES, 'UTF-8'); ?></span>)</span>
        </div>

        <nav class="gk-vs" aria-label="التنقل">
            <a href="dorf1.php" class="gk-v gk-v1" title="<?php echo defined('TZ_VILLAGE_OVERVIEW') ? TZ_VILLAGE_OVERVIEW : 'حقول القرية'; ?>"></a>
            <a href="dorf2.php" class="gk-v gk-v2" title="<?php echo defined('VILLAGE_CENTER') ? VILLAGE_CENTER : 'مباني القرية'; ?>"></a>
            <a href="karte.php" class="gk-v gk-v3" title="<?php echo defined('MAP') ? MAP : 'الخريطة'; ?>"></a>
            <a href="statistiken.php" class="gk-v gk-v4" title="<?php echo defined('STATISTICS') ? STATISTICS : 'الإحصائيات'; ?>"></a>
            <a href="berichte.php" class="gk-v gk-v5 <?php echo htmlspecialchars($n5class, ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo defined('REPORTS') ? REPORTS : 'التقارير'; ?>"></a>
        </nav>

        <a class="gk-gold-bar" href="plus.php" title="الذهب">
            <span class="gk-gold-ico" aria-hidden="true"></span>
            <?php echo number_format($gold); ?>
        </a>
    </div>
</div>
