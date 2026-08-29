<?php
/* Right nav links — screenshot RLisTa order only */
$uid = isset($session->uid) ? (int) $session->uid : 0;
?>
<aside class="gk-side-right">
    <a href="index.php">الصفحة الرئيسية</a>
    <a href="anleitung.php">الدليل السريع</a>
    <a href="spieler.php?uid=<?php echo $uid; ?>">بطاقة العضوية</a>
    <a href="manual.php">شروحات اللعبة</a>
    <a href="allianz.php?s=6&amp;public=1">الشات</a>
    <a href="nachrichten.php?t=1&amp;id=1">مراسلة الإدارة</a>
    <a href="logout.php">خروج</a>
    <a href="plus.php?id=3" class="gk-plus">حساب بلاس</a>
</aside>
