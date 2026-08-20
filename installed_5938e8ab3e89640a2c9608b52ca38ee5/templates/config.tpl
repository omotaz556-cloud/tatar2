<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : config.tpl                                                ##
##  Type           : Install Panel Frontend & Backend                          ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Dzoki (Original)                                          ##
##  Refactored by  : Shadow                                                    ##
##  Redesign by    : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : Novaterra Project                                          ##
##  Copyright      : Novaterra (c) 2010-2025. All rights reserved.              ##
## --------------------------------------------------------------------------- ##
#################################################################################

if(isset($_GET['c']) && $_GET['c'] == 1) {
$errConst = install_is_rtl() ? 'خطأ أثناء إنشاء constant.php، تحقق من صلاحيات المجلد (chmod).' : 'Error creating constant.php check cmod.';
echo "<div class=\"headline\"><span class=\"f10 c5\">$errConst</span></div><br>";
}

@session_start();

$envPath = dirname(__DIR__, 2). '/.env';
$envDefaults = [];
if(file_exists($envPath)) {
    $lines = @file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if(is_array($lines)) {
        foreach($lines as $line) {
            $line = trim($line);
            if($line === '' || $line[0] === '#') {
                continue;
            }

            $eqPos = strpos($line, '=');
            if($eqPos === false) {
                continue;
            }

            $key = trim(substr($line, 0, $eqPos));
            $value = trim(substr($line, $eqPos + 1));
            if($key === '') {
                continue;
            }

            if((strlen($value) >= 2) && (($value[0] === '"' && substr($value, -1) === '"') || ($value[0] === "'" && substr($value, -1) === "'"))) {
                $value = substr($value, 1, -1);
            }

            $envDefaults[$key] = $value;
        }

        // Resolve ${VAR} references using parsed values first, then process env.
        foreach($envDefaults as $key => $value) {
            $envDefaults[$key] = preg_replace_callback('/\$\{([A-Z0-9_]+)\}/i', function($m) use ($envDefaults) {
                $ref = $m[1];
                if(isset($envDefaults[$ref])) return $envDefaults[$ref];
                $fromEnv = getenv($ref);
                return ($fromEnv!== false)? $fromEnv : '';
            }, $value);
        }
    }
}

$dbHost = $envDefaults['DB_HOST']?? 'localhost';
$dbPort = $envDefaults['DB_PORT']?? '3306';
$dbUser = $envDefaults['MARIADB_USER']?? ($envDefaults['MYSQL_USER']?? '');
$dbPass = $envDefaults['MARIADB_PASSWORD']?? ($envDefaults['MYSQL_PASSWORD']?? '');
$dbName = $envDefaults['MARIADB_DATABASE']?? ($envDefaults['MYSQL_DATABASE']?? '');

if(empty($_SESSION['install_random_prefix'])) {
    try {
        $_SESSION['install_random_prefix'] = 's'. substr(bin2hex(random_bytes(2)), 0, 4). '_';
    } catch (Throwable $e) {
        $_SESSION['install_random_prefix'] = 's'. str_pad((string) mt_rand(0, 9999), 4, '0', STR_PAD_LEFT). '_';
    }
}
$dbPrefix = $_SESSION['install_random_prefix'];
?>

<form action="process.php" method="post" id="dataform">
<input type="hidden" name="subconst" value="1">

<div class="card">
  <span class="f10 c"><?=t('server_related')?></span>
  <div class="grid-2" style="margin-top:8px;">
    <div><label><?=t('server_name')?></label><input class="input" name="servername" id="servername" value="Novaterra"></div>
    <div><label><?=t('timezone')?></label>
      <select class="input" name="tzone" onchange="refresh(this.value)">
        <optgroup label="<?=install_is_rtl()?'الشرق الأوسط':'Middle East'?>">
        <option value="15,Asia/Riyadh" <?=$tz==15?'selected':''?>><?=install_is_rtl()?'السعودية (الرياض)':'Saudi Arabia (Riyadh)'?></option>
        <option value="16,Asia/Dubai" <?=$tz==16?'selected':''?>><?=install_is_rtl()?'الإمارات (دبي)':'UAE (Dubai)'?></option>
        <option value="17,Asia/Kuwait" <?=$tz==17?'selected':''?>><?=install_is_rtl()?'الكويت':'Kuwait'?></option>
        <option value="18,Asia/Qatar" <?=$tz==18?'selected':''?>><?=install_is_rtl()?'قطر':'Qatar'?></option>
        <option value="19,Asia/Bahrain" <?=$tz==19?'selected':''?>><?=install_is_rtl()?'البحرين':'Bahrain'?></option>
        <option value="20,Asia/Amman" <?=$tz==20?'selected':''?>><?=install_is_rtl()?'الأردن (عمّان)':'Jordan (Amman)'?></option>
        <option value="21,Africa/Cairo" <?=$tz==21?'selected':''?>><?=install_is_rtl()?'مصر (القاهرة)':'Egypt (Cairo)'?></option>
        <option value="22,Asia/Baghdad" <?=$tz==22?'selected':''?>><?=install_is_rtl()?'العراق (بغداد)':'Iraq (Baghdad)'?></option>
        <option value="23,Asia/Beirut" <?=$tz==23?'selected':''?>><?=install_is_rtl()?'لبنان (بيروت)':'Lebanon (Beirut)'?></option>
        </optgroup>
        <optgroup label="<?=install_is_rtl()?'أخرى':'Other'?>">
        <option value="1,Africa/Dakar" <?=$tz==1?'selected':''?>><?=install_is_rtl()?'أفريقيا':'Africa'?></option>
        <option value="2,America/New_York" <?=$tz==2?'selected':''?>><?=install_is_rtl()?'أمريكا':'America'?></option>
        <option value="13,America/Sao_Paulo" <?=$tz==13?'selected':''?>><?=install_is_rtl()?'البرازيل':'Brazil'?></option>
        <option value="3,Antarctica/Casey" <?=$tz==3?'selected':''?>><?=install_is_rtl()?'القارة القطبية الجنوبية':'Antarctica'?></option>
        <option value="4,Arctic/Longyearbyen" <?=$tz==4?'selected':''?>><?=install_is_rtl()?'القطب الشمالي':'Arctic'?></option>
        <option value="5,Asia/Kuala_Lumpur" <?=$tz==5?'selected':''?>><?=install_is_rtl()?'آسيا':'Asia'?></option>
        <option value="6,Atlantic/Azores" <?=$tz==6?'selected':''?>><?=install_is_rtl()?'الأطلسي':'Atlantic'?></option>
        <option value="7,Australia/Melbourne" <?=$tz==7?'selected':''?>><?=install_is_rtl()?'أستراليا':'Australia'?></option>
        <option value="8,Europe/Bucharest" <?=$tz==8?'selected':''?>><?=install_is_rtl()?'أوروبا (بوخارست)':'Europe (Bucharest)'?></option>
        <option value="9,Europe/London" <?=$tz==9?'selected':''?>><?=install_is_rtl()?'أوروبا (لندن)':'Europe (London)'?></option>
        <option value="14,Europe/Zurich" <?=$tz==14?'selected':''?>><?=install_is_rtl()?'أوروبا (سويسرا)':'Europe (Switzerland)'?></option>
        <option value="10,Europe/Bratislava" <?=$tz==10?'selected':''?>><?=install_is_rtl()?'أوروبا (براتيسلافا)':'Europe (Bratislava)'?></option>
        <option value="11,Indian/Maldives" <?=$tz==11?'selected':''?>><?=install_is_rtl()?'المحيط الهندي':'Indian'?></option>
        <option value="12,Pacific/Fiji" <?=$tz==12?'selected':''?>><?=install_is_rtl()?'المحيط الهادئ':'Pacific'?></option>
        </optgroup>
      </select>
    </div>
    <div><label><?=t('server_speed')?></label><input class="input" name="speed" id="speed" value="1"></div>
    <div><label><?=t('troop_speed')?></label><input class="input" name="incspeed" id="incspeed" value="1"></div>
    <div><label><?=t('evasion_speed')?></label><input class="input" name="evasionspeed" id="evasionspeed" value="1"></div>
    <div><label><?=install_is_rtl()?'سعة التاجر':'Trader capacity'?></label><input class="input" name="tradercap" id="tradercap" value="1"></div>
    <div><label><?=install_is_rtl()?'سعة المخبأ':'Cranny capacity'?></label><input class="input" name="crannycap" id="crannycap" value="1"></div>
    <div><label><?=install_is_rtl()?'سعة القناص':'Trapper capacity'?></label><input class="input" name="trappercap" id="trappercap" value="1"></div>
    <div><label><?=install_is_rtl()?'حجم العالم':'World size'?></label>
      <select class="input" name="wmax">
        <option value="10">10x10</option><option value="25">25x25</option><option value="50">50x50</option>
        <option value="100" selected>100x100</option><option value="150">150x150</option><option value="200">200x200</option>
        <option value="250">250x250</option><option value="300">300x300</option><option value="350">350x350</option><option value="400">400x400</option>
      </select>
    </div>
    <div><label><?=install_is_rtl()?'لغة اللعبة الافتراضية':'Default game language'?></label>
      <select class="input" name="lang"><option value="ar" selected>Arabic — العربية</option><option value="en">English</option><option value="fr">French</option><option value="it">Italian</option><option value="es">Spanish</option><option value="ro">Romanian</option><option value="zh">Chinese</option></select>
      <div style="font-size:11px;color:#94a3b8;margin-top:4px;"><?=install_is_rtl()?'هذه هي اللغة الافتراضية للاعبين عند التسجيل. أي لاعب يقدر يغيّرها لاحقًا من صفحة إعداداته الشخصية.':'This is the default language for players at registration. Each player can change it later from their own profile settings.'?></div>
    </div>
    <div><label><?=install_is_rtl()?'حماية المبتدئين':'Beginners protection'?></label>
      <select class="input" name="beginner">
        <option value="7200"><?=install_is_rtl()?'ساعتين':'2 hours'?></option><option value="10800"><?=install_is_rtl()?'3 ساعات':'3 hours'?></option><option value="18000"><?=install_is_rtl()?'5 ساعات':'5 hours'?></option><option value="28800"><?=install_is_rtl()?'8 ساعات':'8 hours'?></option><option value="36000"><?=install_is_rtl()?'10 ساعات':'10 hours'?></option>
        <option value="43200" selected><?=install_is_rtl()?'12 ساعة':'12 hours'?></option><option value="86400"><?=install_is_rtl()?'24 ساعة (يوم)':'24 hours (1 day)'?></option><option value="172800"><?=install_is_rtl()?'48 ساعة (يومين)':'48 hours (2 days)'?></option><option value="259200"><?=install_is_rtl()?'72 ساعة (3 أيام)':'72 hours (3 days)'?></option><option value="432000"><?=install_is_rtl()?'120 ساعة (5 أيام)':'120 hours (5 days)'?></option>
      </select>
    </div>
    <div><label><?=install_is_rtl()?'التسجيل مفتوح':'Register Open'?></label><select class="input" name="reg_open"><option value="true" selected>true</option><option value="false">false</option></select></div>
  </div>
</div>

<div class="grid-2">
  <div class="card">
    <span class="f10 c"><?=t('natars_map')?></span>
    <div style="margin-top:12px;display:grid;gap:10px;">
      <div><label><?=install_is_rtl()?'مضاعف وحدات Natars':'Natars Units Multiplier'?></label><input class="input" name="natars_units" id="natars_units" value="100"></div>
      <div><label><?=install_is_rtl()?'ظهور Natars (أيام)':'Natars Spawn (days)'?></label><input class="input" name="natars_spawn_time" id="natars_spawn_time" value="260"></div>
      <div><label><?=install_is_rtl()?'ظهور عجيبة العالم (أيام)':'WW Spawn (days)'?></label><input class="input" name="natars_ww_spawn_time" id="natars_ww_spawn_time" value="260"></div>
      <div><label><?=install_is_rtl()?'ظهور مخطط عجيبة العالم (أيام)':'WW BP Spawn (days)'?></label><input class="input" name="natars_ww_building_plan_spawn_time" id="natars_ww_building_plan_spawn_time" value="260"></div>
      <div><label><?=install_is_rtl()?'بدء بناء Natars لعجيبة العالم (أيام)':'Natars start build WW (days)'?></label><input class="input" name="natars_ww_start_delay" id="natars_ww_start_delay" value="10"></div>
      <div><label><?=install_is_rtl()?'تجدد الطبيعة':'Nature regen'?></label><select class="input" name="nature_regtime"><option value="28800"><?=install_is_rtl()?'8 ساعات':'8 hours'?></option><option value="36000"><?=install_is_rtl()?'10 ساعات':'10 hours'?></option><option value="43200" selected><?=install_is_rtl()?'12 ساعة':'12 hours'?></option><option value="57600"><?=install_is_rtl()?'16 ساعة':'16 hours'?></option><option value="72000"><?=install_is_rtl()?'20 ساعة':'20 hours'?></option><option value="86400"><?=install_is_rtl()?'24 ساعة':'24 hours'?></option></select></div>
    </div>
  </div>
  <div class="card">
    <span class="f10 c"><?=t('oasis_storage')?></span>
    <div style="margin-top:12px;display:grid;gap:10px;">
      <div><label><?=install_is_rtl()?'مضاعف الخشب':'Wood multiplier'?></label><input class="input" name="oasis_wood_multiplier" id="oasis_wood_multiplier" value="40"></div>
      <div><label><?=install_is_rtl()?'مضاعف الطين':'Clay multiplier'?></label><input class="input" name="oasis_clay_multiplier" id="oasis_clay_multiplier" value="40"></div>
      <div><label><?=install_is_rtl()?'مضاعف الحديد':'Iron multiplier'?></label><input class="input" name="oasis_iron_multiplier" id="oasis_iron_multiplier" value="40"></div>
      <div><label><?=install_is_rtl()?'مضاعف المحاصيل':'Crop multiplier'?></label><input class="input" name="oasis_crop_multiplier" id="oasis_crop_multiplier" value="40"></div>
      <div><label><?=install_is_rtl()?'مضاعف التخزين':'Storage Multiplier'?></label><input class="input" name="storage_multiplier" id="storage_multiplier" value="1"></div>
      <div><label><?=install_is_rtl()?'حد TS':'TS Threshold'?></label><input class="input" name="ts_threshold" id="ts_threshold" value="20"></div>
    </div>
  </div>
</div>

<div class="grid-2">
  <div class="card">
    <span class="f10 c"><?=t('sql_related')?></span>
    <div style="margin-top:12px;display:grid;gap:10px;">
      <div><label><?=install_is_rtl()?'اسم المضيف':'Hostname'?></label><input class="input" name="sserver" id="sserver" value="<?=htmlspecialchars($dbHost, ENT_QUOTES, 'UTF-8')?>"></div>
      <div><label><?=install_is_rtl()?'المنفذ':'Port'?></label><input class="input" name="sport" id="sport" value="<?=htmlspecialchars($dbPort, ENT_QUOTES, 'UTF-8')?>"></div>
      <div><label><?=install_is_rtl()?'اسم المستخدم':'Username'?></label><input class="input" name="suser" id="suser" value="<?=htmlspecialchars($dbUser, ENT_QUOTES, 'UTF-8')?>"></div>
      <div><label><?=t('password')?></label><input class="input" type="password" name="spass" id="spass" value="<?=htmlspecialchars($dbPass, ENT_QUOTES, 'UTF-8')?>"></div>
      <div><label><?=install_is_rtl()?'اسم قاعدة البيانات':'DB name'?></label><input class="input" name="sdb" id="sdb" value="<?=htmlspecialchars($dbName, ENT_QUOTES, 'UTF-8')?>"></div>
      <div><label><?=install_is_rtl()?'البادئة':'Prefix'?></label><input class="input" name="prefix" id="prefix" value="<?=htmlspecialchars($dbPrefix, ENT_QUOTES, 'UTF-8')?>" size="7"></div>
      <div><label><?=install_is_rtl()?'النوع':'Type'?></label><select class="input" name="connectt"><option value="0" disabled>MYSQL (deprecated, removed in PHP 7)</option><option value="1" selected>MYSQLi</option></select></div>
    </div>
  </div>
  <div class="card">
    <span class="f10 c"><?=t('server_urls')?></span>
    <div style="margin-top:12px;display:grid;gap:10px;">
      <div><label><?=install_is_rtl()?'السيرفر':'Server'?></label><input class="input" name="server" id="homepage" value="http://<?=$_SERVER['HTTP_HOST']?>/"></div>
      <div><label><?=install_is_rtl()?'النطاق':'Domain'?></label><input class="input" name="domain" id="homepage" value="http://<?=$_SERVER['HTTP_HOST']?>/"></div>
      <div><label><?=install_is_rtl()?'الصفحة الرئيسية':'Homepage'?></label><input class="input" name="homepage" id="homepage" value="http://<?=$_SERVER['HTTP_HOST']?>/"></div>
      <div><label><?=install_is_rtl()?'فترة الميداليات':'Medal Interval'?></label><select class="input" name="medalinterval"><option value="0"><?=install_is_rtl()?'لا شيء':'none'?></option><option value="(3600*24)"><?=install_is_rtl()?'يوم واحد':'1 day'?></option><option value="(3600*24*2)"><?=install_is_rtl()?'يومين':'2 days'?></option><option value="(3600*24*3)"><?=install_is_rtl()?'3 أيام':'3 days'?></option><option value="(3600*24*4)"><?=install_is_rtl()?'4 أيام':'4 days'?></option><option value="(3600*24*5)"><?=install_is_rtl()?'5 أيام':'5 days'?></option><option value="(3600*24*6)"><?=install_is_rtl()?'6 أيام':'6 days'?></option><option value="(3600*24*7)" selected><?=install_is_rtl()?'7 أيام':'7 days'?></option></select></div>
      <div><label><?=install_is_rtl()?'الورشة العظمى':'Great Workshop'?></label><select class="input" name="great_wks"><option value="true">true</option><option value="false" selected>false</option></select></div>
      <div><label><?=install_is_rtl()?'تفعيل عجيبة العالم':'WW enabled'?></label><select class="input" name="ww"><option value="true">true</option><option value="false" selected>false</option></select></div>
      <div><label><?=install_is_rtl()?'إظهار Natars':'Show Natars'?></label><select class="input" name="show_natars"><option value="true">true</option><option value="false" selected>false</option></select></div>
      <div><label><?=install_is_rtl()?'نظام الهدنة':'Peace system'?></label><select class="input" name="peace"><option value="0" selected><?=install_is_rtl()?'بلا':'None'?></option><option value="1"><?=install_is_rtl()?'عادي':'Normal'?></option><option value="2"><?=install_is_rtl()?'الكريسماس':'Christmas'?></option><option value="3"><?=install_is_rtl()?'رأس السنة':'New Year'?></option><option value="4"><?=install_is_rtl()?'عيد الفصح':'Easter'?></option></select></div>
    </div>
  </div>
</div>

<div class="card">
  <span class="f10 c"><?=t('new_mechanics')?></span>
	<div class="grid-1" style="margin-top:12px;display:flex;flex-direction:column;gap:10px;">
<?php
$mechs = install_is_rtl() ? [
    'new_functions_oasis'                 => 'إظهار الواحة في الملف الشخصي',
    'new_functions_alliance_invitation'   => 'دعوة التحالف',
    'new_functions_embassy_mechanics'     => 'آليات السفارة',
    'new_functions_forum_post_message'    => 'نشر رسالة في المنتدى',
    'new_functions_tribe_images'          => 'صور القبائل',
    'new_functions_mhs_images'            => 'صور Multihunter',
    'new_functions_display_artifact'      => 'إظهار القطعة الأثرية',
    'new_functions_display_wonder'        => 'إظهار عجيبة العالم',
    'new_functions_vacation'              => 'وضع الإجازة',
    'new_functions_display_catapult_target'=> 'أهداف المنجنيق',
    'new_functions_manual_naturenatars'   => 'التحكم اليدوي بالطبيعة/Natars',
    'new_functions_display_links'         => 'روابط مباشرة',
    'new_functions_medal_3year'           => 'ميدالية 3 سنوات',
    'new_functions_medal_5year'           => 'ميدالية 5 سنوات',
    'new_functions_medal_10year'          => 'ميدالية 10 سنوات',
	'new_functions_special_medals_system' => 'نظام الميداليات الخاصة',
	'new_functions_medal_reset' 		  => 'إعادة ضبط الميداليات',
	'new_functions_milestones'            => 'إنجازات السيرفر',
	'new_functions_hero_t4'               => 'بطل T4 (عناصر، مغامرات، مزاد)',
	'new_function_tribe_huns'             => 'قبيلة جديدة: الهون',
	'new_function_tribe_egipteans'        => 'قبيلة جديدة: المصريون',
	'new_function_tribe_spartans'         => 'قبيلة جديدة: الأسبرطيون',
	'new_function_tribe_vikings'          => 'قبيلة جديدة: الفايكنج',
	'new_function_registration_gold'      => 'ذهب مكافأة التسجيل'
] : [
    'new_functions_oasis'                 => 'Display oasis in profile',
    'new_functions_alliance_invitation'   => 'Alliance invitation',
    'new_functions_embassy_mechanics'     => 'Embassy mechanics',
    'new_functions_forum_post_message'    => 'Forum post message',
    'new_functions_tribe_images'          => 'Tribes images',
    'new_functions_mhs_images'            => 'MHs images',
    'new_functions_display_artifact'      => 'Display artifact',
    'new_functions_display_wonder'        => 'Display wonder',
    'new_functions_vacation'              => 'Vacation Mode',
    'new_functions_display_catapult_target'=> 'Catapult targets',
    'new_functions_manual_naturenatars'   => 'Manual Nature/Natars',
    'new_functions_display_links'         => 'Direct links',
    'new_functions_medal_3year'           => 'Medal 3y',
    'new_functions_medal_5year'           => 'Medal 5y',
    'new_functions_medal_10year'          => 'Medal 10y',
	'new_functions_special_medals_system' => 'Special Medals System',
	'new_functions_medal_reset' 		  => 'Medal Reset',
	'new_functions_milestones'            => 'Server Milestones',
	'new_functions_hero_t4'               => 'T4 Hero (items, adventures, auction)',
	'new_function_tribe_huns'             => 'New Tribe: Huns',
	'new_function_tribe_egipteans'        => 'New Tribe: Egyptians',
	'new_function_tribe_spartans'         => 'New Tribe: Spartans',
	'new_function_tribe_vikings'          => 'New Tribe: Vikings',
	'new_function_registration_gold'      => 'Registration Bonus Gold'
];

foreach($mechs as $k => $l){
    echo "
    <div style='display:flex;flex-direction:column;gap:4px;'>
        <label>$l</label>
        <select class='input' name='$k'>
            <option value='true'>true</option>
            <option value='false' selected>false</option>
        </select>
    </div>";
}
?>
<div style='display:flex;flex-direction:column;gap:4px;margin-top:4px;'>
    <label><?=install_is_rtl()?'ذهب مكافأة التسجيل - الكمية (يُستخدم فقط عند تفعيل الخيار أعلاه)':'Registration Bonus Gold &ndash; amount (used only when the toggle above is true)'?></label>
    <input class='input' type='number' min='0' step='1' name='new_function_registration_gold_value' id='new_function_registration_gold_value' value='200'>
</div>                      
</div>
</div>

<div class="card">
  <span class="f10 c"><?=t('plus_packages')?></span>
  <div class="grid-2" style="margin-top:12px;">
    <div><label><?=install_is_rtl()?'بريد PayPal':'PayPal Email'?></label><input class="input" name="paypal-email" id="paypal-email" value="@"></div>
    <div><label><?=install_is_rtl()?'العملة':'Currency'?></label><input class="input" name="paypal-currency" id="paypal-currency" value="EUR"></div>
    <div><label><?=install_is_rtl()?'ذهب باقة A':'Package A Gold'?></label><input class="input" name="plus-a-gold" id="plus-a-gold" value="60"></div>
    <div><label><?=install_is_rtl()?'سعر باقة A':'Package A Price'?></label><input class="input" name="plus-a-price" id="plus-a-price" value="1,99"></div>
    <div><label><?=install_is_rtl()?'ذهب باقة B':'Package B Gold'?></label><input class="input" name="plus-b-gold" id="plus-b-gold" value="120"></div>
    <div><label><?=install_is_rtl()?'سعر باقة B':'Package B Price'?></label><input class="input" name="plus-b-price" id="plus-b-price" value="4,99"></div>
    <div><label><?=install_is_rtl()?'ذهب باقة C':'Package C Gold'?></label><input class="input" name="plus-c-gold" id="plus-c-gold" value="360"></div>
    <div><label><?=install_is_rtl()?'سعر باقة C':'Package C Price'?></label><input class="input" name="plus-c-price" id="plus-c-price" value="9,99"></div>
    <div><label><?=install_is_rtl()?'ذهب باقة D':'Package D Gold'?></label><input class="input" name="plus-d-gold" id="plus-d-gold" value="1000"></div>
    <div><label><?=install_is_rtl()?'سعر باقة D':'Package D Price'?></label><input class="input" name="plus-d-price" id="plus-d-price" value="19,99"></div>
    <div><label><?=install_is_rtl()?'ذهب باقة E':'Package E Gold'?></label><input class="input" name="plus-e-gold" id="plus-e-gold" value="2000"></div>
    <div><label><?=install_is_rtl()?'سعر باقة E':'Package E Price'?></label><input class="input" name="plus-e-price" id="plus-e-price" value="49,99"></div>
    <div><label><?=install_is_rtl()?'مدة Plus':'Plus length'?></label><select class="input" name="plus_time"><option value="(3600*12)"><?=install_is_rtl()?'12 ساعة':'12 hours'?></option><option value="(3600*24)"><?=install_is_rtl()?'يوم واحد':'1 day'?></option><option value="(3600*24*2)"><?=install_is_rtl()?'يومين':'2 days'?></option><option value="(3600*24*3)"><?=install_is_rtl()?'3 أيام':'3 days'?></option><option value="(3600*24*4)"><?=install_is_rtl()?'4 أيام':'4 days'?></option><option value="(3600*24*5)"><?=install_is_rtl()?'5 أيام':'5 days'?></option><option value="(3600*24*6)"><?=install_is_rtl()?'6 أيام':'6 days'?></option><option value="(3600*24*7)" selected><?=install_is_rtl()?'7 أيام':'7 days'?></option></select></div>
    <div><label><?=install_is_rtl()?'+25% إنتاج':'+25% production'?></label><select class="input" name="plus_production"><option value="(3600*12)"><?=install_is_rtl()?'12 ساعة':'12 hours'?></option><option value="(3600*24)"><?=install_is_rtl()?'يوم واحد':'1 day'?></option><option value="(3600*24*2)"><?=install_is_rtl()?'يومين':'2 days'?></option><option value="(3600*24*3)"><?=install_is_rtl()?'3 أيام':'3 days'?></option><option value="(3600*24*4)"><?=install_is_rtl()?'4 أيام':'4 days'?></option><option value="(3600*24*5)"><?=install_is_rtl()?'5 أيام':'5 days'?></option><option value="(3600*24*6)"><?=install_is_rtl()?'6 أيام':'6 days'?></option><option value="(3600*24*7)" selected><?=install_is_rtl()?'7 أيام':'7 days'?></option></select></div>
  </div>
</div>

<div class="grid-2">
  <div class="card">
    <span class="f10 c"><?=t('newsbox_options')?></span>
    <div style="margin-top:12px;display:grid;gap:10px;">
      <div><label><?=install_is_rtl()?'صندوق الأخبار 1':'Newsbox 1'?></label><select class="input" name="box1"><option value="true"><?=install_is_rtl()?'مفعّل':'Enabled'?></option><option value="false" selected><?=install_is_rtl()?'معطّل':'Disabled'?></option></select></div>
      <div><label><?=install_is_rtl()?'صندوق الأخبار 2':'Newsbox 2'?></label><select class="input" name="box2"><option value="true"><?=install_is_rtl()?'مفعّل':'Enabled'?></option><option value="false" selected><?=install_is_rtl()?'معطّل':'Disabled'?></option></select></div>
      <div><label><?=install_is_rtl()?'صندوق الأخبار 3':'Newsbox 3'?></label><select class="input" name="box3"><option value="true"><?=install_is_rtl()?'مفعّل':'Enabled'?></option><option value="false" selected><?=install_is_rtl()?'معطّل':'Disabled'?></option></select></div>
    </div>
  </div>
  <div class="card">
    <span class="f10 c"><?=t('log_related')?></span>
    <div style="margin-top:12px;display:grid;gap:10px;">
      <div><label><?=install_is_rtl()?'سجل البناء':'Log Building'?></label><select class="input" name="log_build"><option value="true"><?=install_is_rtl()?'نعم':'Yes'?></option><option value="false" selected><?=install_is_rtl()?'لا':'No'?></option></select></div>
      <div><label><?=install_is_rtl()?'سجل التقنيات':'Log Tech'?></label><select class="input" name="log_tech"><option value="true"><?=install_is_rtl()?'نعم':'Yes'?></option><option value="false" selected><?=install_is_rtl()?'لا':'No'?></option></select></div>
      <div><label><?=install_is_rtl()?'سجل الدخول':'Log Login'?></label><select class="input" name="log_login"><option value="true"><?=install_is_rtl()?'نعم':'Yes'?></option><option value="false" selected><?=install_is_rtl()?'لا':'No'?></option></select></div>
      <div><label><?=install_is_rtl()?'سجل الذهب':'Log Gold'?></label><select class="input" name="log_gold_fin"><option value="true"><?=install_is_rtl()?'نعم':'Yes'?></option><option value="false" selected><?=install_is_rtl()?'لا':'No'?></option></select></div>
      <div><label><?=install_is_rtl()?'سجل الأدمن':'Log Admin'?></label><select class="input" name="log_admin"><option value="true"><?=install_is_rtl()?'نعم':'Yes'?></option><option value="false" selected><?=install_is_rtl()?'لا':'No'?></option></select></div>
      <div><label><?=install_is_rtl()?'سجل الحروب':'Log War'?></label><select class="input" name="log_war"><option value="true"><?=install_is_rtl()?'نعم':'Yes'?></option><option value="false" selected><?=install_is_rtl()?'لا':'No'?></option></select></div>
      <div><label><?=install_is_rtl()?'سجل السوق':'Log Market'?></label><select class="input" name="log_market"><option value="true"><?=install_is_rtl()?'نعم':'Yes'?></option><option value="false" selected><?=install_is_rtl()?'لا':'No'?></option></select></div>
      <div><label><?=install_is_rtl()?'سجل المخالفات':'Log Illegal'?></label><select class="input" name="log_illegal"><option value="true"><?=install_is_rtl()?'نعم':'Yes'?></option><option value="false" selected><?=install_is_rtl()?'لا':'No'?></option></select></div>
    </div>
  </div>
</div>

<div class="card">
  <span class="f10 c"><?=t('extra_options')?></span>
  <div class="grid-2" style="margin-top:12px;">
    <div><label><?=install_is_rtl()?'المهام':'Quest'?></label><select class="input" name="quest"><option value="true" selected><?=install_is_rtl()?'نعم':'Yes'?></option><option value="false"><?=install_is_rtl()?'لا':'No'?></option></select></div>
    <div><label><?=install_is_rtl()?'نوع المهام':'Quest Type'?></label><select class="input" name="qtype"><option value="25" selected><?=install_is_rtl()?'نوفاتيرا الرسمية':'Official Novaterra'?></option><option value="37"><?=install_is_rtl()?'نوفاتيرا الموسّعة':'Novaterra Extended'?></option></select></div>
    <div><label><?=install_is_rtl()?'التفعيل':'Activate'?></label><select class="input" name="activate"><option value="true"><?=install_is_rtl()?'نعم':'Yes'?></option><option value="false" selected><?=install_is_rtl()?'لا':'No'?></option></select></div>
    <div><label><?=install_is_rtl()?'تحديد سعة صندوق الرسائل':'Limit Mailbox'?></label><select class="input" name="limit_mailbox"><option value="true"><?=install_is_rtl()?'نعم':'Yes'?></option><option value="false" selected><?=install_is_rtl()?'لا':'No'?></option></select></div>
    <div><label><?=install_is_rtl()?'أقصى عدد رسائل':'Max mails'?></label><input class="input" name="max_mails" id="max_mails" value="30"></div>
    <div><label><?=install_is_rtl()?'الهدم - المستوى المطلوب':'Demolish - lvl required'?></label><select class="input" name="demolish"><option value="5">5</option><option value="10" selected>10 - <?=install_is_rtl()?'افتراضي':'Default'?></option><option value="15">15</option><option value="20">20</option></select></div>
    <div><label><?=install_is_rtl()?'توسّع القرية':'Village Expand'?></label><select class="input" name="village_expand"><option value="1" selected><?=install_is_rtl()?'بطيء':'Slow'?></option><option value="0"><?=install_is_rtl()?'سريع':'Fast'?></option></select></div>
    <div><label><?=install_is_rtl()?'تقارير الأخطاء':'Error Reporting'?></label><select class="input" name="error"><option value="error_reporting (E_ALL ^ E_NOTICE ^ E_DEPRECATED);" selected><?=install_is_rtl()?'نعم':'Yes'?></option><option value="error_reporting (0);"><?=install_is_rtl()?'لا':'No'?></option></select></div>
    <div><label><?=install_is_rtl()?'شاشة "T4 قادمة"':'T4 is Coming screen'?></label><select class="input" name="t4_coming"><option value="true"><?=install_is_rtl()?'نعم':'Yes'?></option><option value="false" selected><?=install_is_rtl()?'لا':'No'?></option></select></div>
    <div><label><?=install_is_rtl()?'تاريخ البدء':'Start Date'?></label><input class="input" name="start_date" id="start_date" value="<?=date('d.m.Y')?>"></div>
    <div><label><?=install_is_rtl()?'وقت البدء':'Start Time'?></label><input class="input" name="start_time" id="start_time" value="<?=date('H:i')?>"></div>
  </div>
</div>

<div class="card">
  <span class="f10 c"><?=t('cron_automation')?></span>
  <div class="grid-2" style="margin-top:12px;">
    <div><label><?=install_is_rtl()?'مدة تشغيل Cron (ثانية)':'Cron invocation length (sec)'?></label><select class="input" name="cron_loop">
        <option value="300" selected><?=install_is_rtl()?'300 - كل 5 دقائق (افتراضي)':'300 - cron every 5 min (default)'?></option>
        <option value="600"><?=install_is_rtl()?'600 - كل 10 دقائق':'600 - cron every 10 min'?></option>
        <option value="900"><?=install_is_rtl()?'900 - كل 15 دقيقة':'900 - cron every 15 min'?></option>
        <option value="0"><?=install_is_rtl()?'0 - كل دقيقة':'0 - cron every minute'?></option>
    </select></div>
    <div><label><?=install_is_rtl()?'الفاصل الزمني لكل تكة (ثانية)':'Cron tick interval (sec)'?></label><input class="input" name="cron_tick" id="cron_tick" value="60"></div>
    <div><label><?=install_is_rtl()?'حذف التقارير غير المؤرشفة بعد (أيام)':'Delete unarchived reports after (days)'?></label><input class="input" name="cleanup_reports" id="cleanup_reports" value="14"></div>
    <div><label><?=install_is_rtl()?'حذف رسائل الدردشة بعد (أيام)':'Delete chat messages after (days)'?></label><input class="input" name="cleanup_chat" id="cleanup_chat" value="7"></div>
    <div><label><?=install_is_rtl()?'حذف الرسائل المحذوفة من الطرفين (أيام)':'Delete messages erased by both sides (days)'?></label><input class="input" name="cleanup_messages" id="cleanup_messages" value="0"></div>
    <div><label><?=install_is_rtl()?'تجدد طاقة البطل الأساسية (نقطة/يوم)':'Hero base regeneration (HP/day)'?></label><input class="input" name="hero_base_regen" id="hero_base_regen" value="10"></div>
    <div><label><?=install_is_rtl()?'الفضة مقابل كل قطعة ذهب':'Silver received per 1 gold'?></label><input class="input" name="hero_silver_per_gold" id="hero_silver_per_gold" value="10"></div>
    <div><label><?=install_is_rtl()?'الفضة المطلوبة لكل قطعة ذهب':'Silver needed for 1 gold'?></label><input class="input" name="hero_silver_to_gold" id="hero_silver_to_gold" value="25"></div>
    <div><label><?=install_is_rtl()?'الإحصائيات البيانية لـ Plus':'Plus graphical statistics'?></label><select class="input" name="plus_statistics">
        <option value="true" selected><?=install_is_rtl()?'نعم - تسجيل سجل الحساب والرسوم البيانية للاعبي Plus':'Yes &ndash; record account history, graphs for Plus players'?></option>
        <option value="false"><?=install_is_rtl()?'لا':'No'?></option>
    </select></div>
    <div><label><?=install_is_rtl()?'فاصل لقطة الإحصائيات (ساعات)':'Statistics snapshot interval (hours)'?></label><input class="input" type="text" name="plus_stats_hours" value="6"></div>
    <div><label><?=install_is_rtl()?'مدة الاحتفاظ بسجل الإحصائيات (أيام، 0 = للأبد)':'Statistics history kept (days, 0 = forever)'?></label><input class="input" type="text" name="plus_stats_keep" value="0"></div>
    <div><label><?=install_is_rtl()?'مكافآت التحالف (T4)':'Alliance bonuses (T4)'?></label><select class="input" name="alliance_bonuses">
        <option value="false" selected><?=install_is_rtl()?'لا':'No'?></option>
        <option value="true"><?=install_is_rtl()?'نعم':'Yes'?></option>
    </select></div>
    <div><label><?=install_is_rtl()?'أقل طول لاسم المستخدم':'Minimum username length'?></label><input class="input" type="text" name="usrnm_min" value="3"></div>
    <div><label><?=install_is_rtl()?'أقصى طول لاسم المستخدم':'Maximum username length'?></label><input class="input" type="text" name="usrnm_max" value="15"></div>
    <div><label><?=install_is_rtl()?'أقل طول لكلمة المرور':'Minimum password length'?></label><input class="input" type="text" name="pw_min" value="4"></div>
    <div><label><?=install_is_rtl()?'السماح بـ . - _ في أسماء المستخدمين':'Allow . - _ in usernames'?></label><select class="input" name="usrnm_special">
        <option value="true" selected><?=install_is_rtl()?'نعم':'Yes'?></option>
        <option value="false"><?=install_is_rtl()?'لا - حروف وأرقام فقط':'No &ndash; letters and digits only'?></option>
    </select></div>
    <div><label><?=install_is_rtl()?'صورة عجيبة العالم لكل قبيلة':'Per-tribe World Wonder image'?></label><select class="input" name="ww_image">
        <option value="true" selected><?=install_is_rtl()?'نعم':'Yes'?></option>
        <option value="false"><?=install_is_rtl()?'لا - نفس الصورة للجميع':'No &ndash; same image for everyone'?></option>
    </select></div>
    <div><label><?=install_is_rtl()?'لاعبون محميون (بلا هجمات)':'Protected players (no attacks)'?></label><input class="input" type="text" name="protected_players" value="" placeholder="<?=install_is_rtl()?'مثال: Shadow,Multihunter':'e.g. Shadow,Multihunter'?>"></div>
    <div><label><?=install_is_rtl()?'السماح بحزم رسوميات مخصصة للاعبين':'Allow player graphic packs'?></label><select class="input" name="gpack">
        <option value="false" selected><?=install_is_rtl()?'لا - الجميع يرى حزمة السيرفر':'No &ndash; everyone sees the server pack'?></option>
        <option value="true"><?=install_is_rtl()?'نعم - يمكن للاعبين اختيار حزمتهم من الملف الشخصي':'Yes &ndash; players may pick their own in Profile'?></option>
    </select></div>
    <div><label><?=install_is_rtl()?'حزمة الرسوميات':'Graphic pack'?></label><select class="input" name="gp_locate">
        <option value="gpack/novaterra_classic/" selected><?=install_is_rtl()?'نوفاتيرا الافتراضية (شكل T3.6 الكلاسيكي)':'Novaterra Default (classic T3.6 look)'?></option>
        <option value="gpack/novaterra_t4/"><?=install_is_rtl()?'نوفاتيرا T4 (رسوميات بنمط T4)':'Novaterra T4 (T4-styled artwork)'?></option>
    </select></div>
    <div><label><?=install_is_rtl()?'موارد البطل لكل نقطة (الأربعة معًا)':'Hero resources per point (all four)'?></label><input class="input" name="hero_res_all" id="hero_res_all" value="3"></div>
    <div><label><?=install_is_rtl()?'موارد البطل لكل نقطة (نوع واحد)':'Hero resources per point (single type)'?></label><input class="input" name="hero_res_one" id="hero_res_one" value="10"></div>
  </div>
  <div class="f10" style="margin-top:8px;opacity:.75;">
    <?=install_is_rtl()
      ? 'الأتمتة تعمل من ملف cron.php بدلًا من الاعتماد على تحميل صفحات اللاعبين. تشغيلة الـ cron الواحدة تستمر في العمل لمدة "مدة التشغيل" بالثواني وتعالج تكة واحدة كل "الفاصل الزمني للتكة" بالثواني — بحيث الاستضافة التي تسمح بتشغيل cron كل 5 دقائق فقط، تحصل رغم ذلك على معالجة كل دقيقة. القيمة 0 يوم تعطّل قاعدة التنظيف؛ التقارير المؤرشفة من اللاعبين لا تُحذف أبدًا. جميع هذه الإعدادات يمكن تغييرها لاحقًا من لوحة الأدمن.'
      : 'Automation runs from cron.php instead of players\' page loads. One cron invocation keeps working for
    "invocation length" seconds and processes a tick every "tick interval" seconds &mdash; so a host that only
    allows a cron every 5 minutes still gets processing every minute. 0 days disables a cleanup rule;
    reports archived by players are never deleted. All of these can be changed later from the admin panel.'
    ?>
  </div>
</div>

<div style="text-align:center;margin:18px 0;">
  <button class="btn" type="submit" name="Submit" id="Submit"><?=t('save_config')?></button>
</div>
</form>