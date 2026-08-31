<?php
$count="0";
include_once("GameEngine/Config.php");

// go max 5 levels up - we don't have folders that go deeper than that
$autoprefix = '';
for ($i = 0; $i < 5; $i++) {
    $autoprefix = str_repeat('../', $i);
    if (file_exists($autoprefix.'autoloader.php')) {
        // we have our path, let's leave
        break;
    }
}

include_once($autoprefix."GameEngine/Database.php");

$q = "SELECT Count(*) as Total FROM ".TB_PREFIX."movement where endtime < ".time()." and proc = 0";
$result = mysqli_fetch_array(mysqli_query($GLOBALS["link"], $q), MYSQLI_ASSOC);
$count=$result['Total'];

?>

<h1><img class="point" src="img/x.gif" alt="" title="" /> ازدحام الأحداث (00:00:0?)</h1>

<p>
تُحسب جميع الأحداث التي تحدث في وقت لاحق عبر نظام الأحداث. في حال زيادة الحمل على الخادم أو ضعف الاتصال بين خادم الويب وقاعدة البيانات قد ينتج عن ذلك ازدحام في الأحداث.
<br />
بمجرد استقبال أحداث في الثانية أكثر مما يمكن حسابه في الثانية، تُوضع الأحداث (مثل «انتهاء البناء» أو «وصول القوات») في حلقة انتظار.
<br />
يظل الترتيب الزمني لحركات القوات كما هو حتى أثناء ازدحام الأحداث، فالقوات التي كانت ستصل أولاً في الوضع الطبيعي ستصل أولاً أيضاً.
<br />
كلاعب، لا يمكن فعل شيء ضد ازدحام الأحداث سوى الانتظار. عادةً تُحل هذه المشاكل تلقائياً خلال دقائق قليلة. حالياً يوجد <b><?php echo $count; ?></b> حدثاً في الانتظار.
</p>
<map id="nav" name="nav">
    <area href="manual.php?s=1" title="<?php echo BACK; ?>" coords="0,0,45,18" shape="rect" alt="" />
    <area href="manual.php?s=1" title="<?php echo OVERVIEW; ?>" coords="46,0,70,18" shape="rect" alt="" />
    <area href="manual.php?s=1" title="<?php echo FORWARD; ?>" coords="71,0,116,18" shape="rect" alt="" />
</map>
<img usemap="#nav" src="img/x.gif" class="navi" alt="" />
