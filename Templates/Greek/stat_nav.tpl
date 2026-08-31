<?php
$gkNavUid = (isset($session) && is_object($session) && isset($session->uid)) ? (int) $session->uid : 0;
?>
<nav class="gk-stat-nav" aria-label="قائمة الموقع">
	<div class="gk-rlista">
		<a href="index.php"><span class="gk-rarr">◂</span>الصفحة الرئيسية</a>
		<a href="anleitung.php"><span class="gk-rarr">◂</span>الدليل السريع</a>
		<a href="spieler.php?uid=<?php echo $gkNavUid; ?>"><span class="gk-rarr">◂</span>بطاقة العضوية</a>
		<a href="manual.php"><span class="gk-rarr">◂</span>شروحات للعبة</a>
		<a href="allianz.php?s=6&amp;public=1"><span class="gk-rarr">◂</span>الشات</a>
		<a href="nachrichten.php?t=1&amp;id=1"><span class="gk-rarr">◂</span>مراسلة الإدارة</a>
		<a href="logout.php"><span class="gk-rarr">◂</span>خروج</a>
		<a class="C_G plus" href="plus.php">حساب بلاس</a>
	</div>
</nav>
