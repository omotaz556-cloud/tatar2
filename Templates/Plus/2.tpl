<?php

#################################################################################
##                -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-               ##
## --------------------------------------------------------------------------- ##
##  Filename       : 2.tpl                                                     ##
##  Type           : Plus - Gold Purchase Packages                             ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  URLs:          : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : Novaterra Project                                          ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.              ##
## --------------------------------------------------------------------------- ##
#################################################################################

include("Templates/Plus/pmenu.tpl");

$plusRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
if ($plusRtl) {
    $plusFeatures = [
        ['title'=>'قائمة انتظار البناء', 'img'=>'p1', 'text'=>'تسمح لك قائمة الانتظار بإعطاء البنّائين أمرًا إضافيًا لبناء أو تطوير مبنى ثانٍ. بعد إنهاء مهمتهم الأولى وفترة قصيرة (60 ثانية)، يبدأون المهمة الثانية.'],
        ['title'=>'خريطة أكبر', 'img'=>'xxl_map', 'text'=>'يمكنك تكبير الخريطة للحصول على رؤية أفضل. بدلًا من 7×7 حقول تستطيع عرض خريطة 13×13، وتظهر التحالفات الحليفة أو ذات اتفاق عدم الاعتداء بألوان خاصة.'],
        ['title'=>'أرشفة التقارير والرسائل', 'img'=>'p5', 'text'=>'يمكن أرشفة التقارير والرسائل المهمة للوصول إليها بسرعة. كما يمكنك تحديد عدة رسائل أو تقارير وأرشفتها أو حذفها دفعة واحدة.'],
        ['title'=>'فرز التقارير والرسائل', 'img'=>'sort', 'text'=>'بالنقر على عنوان جدول «المرسل» يمكنك عكس ترتيب التقارير والرسائل، وتستخدم هذه الميزة أيضًا داخل الأرشيف.'],
        ['title'=>'فرز السوق', 'img'=>'p6', 'text'=>'يمكنك تصفية عروض السوق حسب مورد محدد، واستخدام فلتر النسبة لرؤية عروض 1:1 فقط.'],
        ['title'=>'الإكمال التلقائي', 'img'=>'autovv', 'text'=>'اكتب اسم القرية كاملًا باستخدام أحرف قليلة جدًا. ويمكن ضبطها للقرى الخاصة أو قرى أعضاء التحالف أو القرى المحيطة.'],
        ['title'=>'فلتر التقارير', 'img'=>'bfilter', 'text'=>'أخفِ التقارير غير المرغوبة الخاصة بتبادل السوق، سواءً بين قراك أو مع القرى الأخرى.'],
        ['title'=>'روابط مباشرة قابلة للتخصيص', 'img'=>'p7', 'text'=>'أنشئ روابط للصفحات التي تستخدمها كثيرًا للوصول إليها بنقرة واحدة، مثل التحالف أو الثكنة أو عروض السوق.'],
        ['title'=>'إحصاءات رسومية', 'img'=>'st1', 'text'=>'تعرض هذه الإحصاءات تطور حسابك زمنيًا، مثل الترتيب وقوة الجيش وتطور السكان.'],
        ['title'=>'نظرة عامة مركزية للحساب', 'img'=>'dorf3', 'text'=>'تابع كل قراك من مكان واحد: القوات، العاملون، وسعة المخازن، حتى لا يفوتك أي شيء.'],
        ['title'=>'المفكرة', 'img'=>'p8', 'text'=>'دوّن الملاحظات والأمور المهمة داخل اللعبة لتعود إليها في أي وقت.'],
    ];
    $goldFeatures = [
        ['title'=>'مكافأة إنتاج الخشب', 'img'=>'p1_25', 'text'=>'تزيد إنتاج الخشب في جميع قراك بنسبة 25%.<br><br>تُضاف المكافأة إلى إجمالي الإنتاج وليس إلى كل حقل منفرد.'],
        ['title'=>'مكافأة إنتاج الطين', 'img'=>'p2_25', 'text'=>'تزيد إنتاج الطين في جميع قراك بنسبة 25%.<br><br>تُضاف المكافأة إلى إجمالي الإنتاج وليس إلى كل حقل منفرد.'],
        ['title'=>'مكافأة إنتاج الحديد', 'img'=>'p3_25', 'text'=>'تزيد إنتاج الحديد في جميع قراك بنسبة 25%.<br><br>تُضاف المكافأة إلى إجمالي الإنتاج وليس إلى كل حقل منفرد.'],
        ['title'=>'مكافأة إنتاج المحاصيل', 'img'=>'p4_25', 'text'=>'تزيد إنتاج المحاصيل في جميع قراك بنسبة 25%.<br><br>تُضاف المكافأة إلى إجمالي الإنتاج وليس إلى كل حقل منفرد.'],
        ['title'=>'إنهاء البناء والأبحاث فورًا', 'img'=>'bau0', 'text'=>'يُنهي جميع أوامر البناء وأبحاث الأكاديمية والحدادة وورشة الدروع في القرية الحالية فورًا.<br><br>لا يشمل مباني <i>السكن</i> والقصر و<i>'.VILLAGES.'</i> التي تضم أعجوبة العالم.'],
        ['title'=>'تاجر NPC', 'img'=>'npc', 'text'=>'يبدّل تاجر NPC أي كمية من الموارد في القرية بموارد أخرى بنسبة 1:1.'],
    ];
} else {
    $plusFeatures = [
        ['title'=>'Waiting loop for constructions', 'img'=>'p1', 'text'=>'The waiting loop enables you to give your builders another order to raise or extend a second building. After completing their first task and a short break (60s), they will start to take care of this second job.'],
        ['title'=>'Larger map', 'img'=>'xxl_map', 'text'=>'You can enlarge the map to get a better overview. Instead of 7x7 fields you can have a map of 13x13 fields. Other alliances which are allied or have a non-aggression pact (NAP) with you are shown in special colours.'],
        ['title'=>'Archive function for reports and messages', 'img'=>'p5', 'text'=>'Important reports and messages can be archived and thereby be looked up faster. Additionally, you can choose several messages or reports and archive or delete them at once.'],
        ['title'=>'Sorting function for reports and messages', 'img'=>'sort', 'text'=>'By clicking the table heading "Sent" you can reverse the sorting of reports and messages. If you get many messages a day and need to look up older ones you are able to do so very fast with this function. It can also be used in the archives.'],
        ['title'=>'Sorting function for the marketplace', 'img'=>'p6', 'text'=>'To use the marketplace more efficiently, you can filter the offers for certain resources only. Additionally you can use a ratio filter to only see 1:1 offers.'],
        ['title'=>'Auto-completion', 'img'=>'autovv', 'text'=>'By using the auto-completion you can easily "write" a whole village name by using very few figures. Depending on your preferences you can use this function in any combination for own villages, villages of alliance members or villages of your surroundings.'],
        ['title'=>'Report filter', 'img'=>'bfilter', 'text'=>'Thanks to the report filter unwanted reports concerning marketplace transactions are a problem of the past. Depending on your personal preferences you can easily switch off reports concerning trades from/to other villages or between your own villages.'],
        ['title'=>'Freely definable direct links', 'img'=>'p7', 'text'=>'Thanks to these links, you can reach every page you want with just one click.'],
        ['title'=>'Graphical statistics', 'img'=>'st1', 'text'=>'These statistics show you the chronological development of your account.'],
        ['title'=>'Central account overview', 'img'=>'dorf3', 'text'=>'Check all your villages at once from your central village overview.'],
        ['title'=>'Notepad', 'img'=>'p8', 'text'=>'Use your notebook to keep important notes.'],
    ];
    $goldFeatures = [
        ['title'=>'Production bonus for lumber', 'img'=>'p1_25', 'text'=>'With this Gold advantage all your villages\' lumber production will be increased by 25%.<br><br>The bonus will not be added to the single resource fields but to the sum of the production.'],
        ['title'=>'Production bonus for clay', 'img'=>'p2_25', 'text'=>'With this Gold advantage all your villages\' clay production will be increased by 25%.<br><br>The bonus will not be added to the single resource fields but to the sum of the production.'],
        ['title'=>'Production bonus for iron', 'img'=>'p3_25', 'text'=>'With this Gold advantage all your villages\' iron production will be increased by 25%.<br><br>The bonus will not be added to the single resource fields but to the sum of the production.'],
        ['title'=>'Production bonus for crop', 'img'=>'p4_25', 'text'=>'With this Gold advantage all your villages\' crop production will be increased by 25%.<br><br>The bonus will not be added to the single resource fields but to the sum of the production.'],
        ['title'=>'Complete construction orders & research immediately.', 'img'=>'bau0', 'text'=>'In the current village all construction orders and research will be completed immediately.'],
        ['title'=>'NPC Merchant', 'img'=>'npc', 'text'=>'The NPC Merchant exchanges resources at a ratio of 1:1.'],
    ];
}
?>
<table id="plus_features" class="features" cellpadding="1" cellspacing="1">
<thead><tr><th colspan="2"><?php echo TZ_FEATURES_OF_NOVATERRA; ?> <span class="plus_g">P</span><span class="plus_o">l</span><span class="plus_g">u</span><span class="plus_o">s</span></th></tr></thead>
<tbody>
<tr><td colspan="2" class="empty"></td></tr>
<?php foreach($plusFeatures as $f): ?>
<tr><th colspan="2"><?= $f['title'] ?></th></tr>
<tr>
    <td class="preview"><a href="plus.php?id=3"><img class="<?= $f['img'] ?>" src="img/x.gif" alt="<?= $f['title'] ?>" /></a></td>
    <td class="text"><?= $f['text'] ?></td>
</tr>
<tr><td colspan="2" class="empty"></td></tr>
<?php endforeach; ?>
</tbody></table>

<table id="gold_features" class="features" cellpadding="1" cellspacing="1">
<thead><tr><th colspan="2"><?php echo TZ_FEATURES_OF_NOVATERRA; ?> <font color="#71D000">G</font><font color="#FF6F0F">o</font><font color="#71D000">l</font><font color="#FF6F0F">d</font></th></tr></thead>
<tbody>
<tr><td colspan="2" class="empty"></td></tr>
<?php foreach($goldFeatures as $f): ?>
<tr><th colspan="2"><?= $f['title'] ?></th></tr>
<tr>
    <td class="preview"><a href="plus.php?id=3"><img class="<?= $f['img'] ?>" src="img/x.gif" alt="<?= $f['title'] ?>" /></a></td>
    <td class="text"><?= $f['text'] ?><br><br><span style="color:#F00"><?php echo TZ_THIS_FEATURE_IS_NOT_INCLUDED_IN_TH; ?></span></td>
</tr>
<tr><td colspan="2" class="empty"></td></tr>
<?php endforeach; ?>
</tbody></table>
</div>
