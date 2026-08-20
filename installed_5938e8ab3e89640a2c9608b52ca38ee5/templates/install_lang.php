<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : install_lang.php                                          ##
##  Type           : Install Panel Frontend & Backend                          ##
## --------------------------------------------------------------------------- ##
##  Purpose        : Arabic/English UI language switch for the installer only  ##
##                   (separate from the "Language" dropdown in config.tpl,     ##
##                   which sets the GAME's language, not the installer's UI)   ##
## --------------------------------------------------------------------------- ##
##  Project        : Novaterra                                                  ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
#################################################################################

if(session_status() !== PHP_SESSION_ACTIVE) {
    @session_start();
}

// Allow switching via ?ilang=ar / ?ilang=en, persisted in session for the rest of install
if(isset($_GET['ilang']) && in_array($_GET['ilang'], ['ar', 'en'], true)) {
    $_SESSION['install_ui_lang'] = $_GET['ilang'];
}

$GLOBALS['INSTALL_UI_LANG'] = $_SESSION['install_ui_lang'] ?? 'ar'; // Arabic default for this build

$GLOBALS['INSTALL_STRINGS'] = [
    'en' => [
        'page_title'        => 'Novaterra Installation',
        'install_script'    => 'Novaterra Installation Script',
        'step_of'           => 'Step %d from %d ',
        'steps'             => ['Intro', 'Configuration', 'Database', 'World Data', 'Accounts', 'End'],

        // greet.tpl
        'disclaimer_title'  => '⚠️ Disclaimer',
        'disclaimer_1'      => 'Along with the installation/usage of this game, you shall be fully responsible for any legal results...',
        'disclaimer_2'      => 'Neither the team that created this script nor the team that customised it shall be responsible for any damage...',
        'disclaimer_3'      => 'All code was confirmed to be running correctly by the creation team...',
        'disclaimer_4'      => 'Users are asked to review the code on their own accord.',
        'disclaimer_5'      => "Any customization to the source code are the property of each author's discretion.",
        'disclaimer_6'      => 'You have no rights to edit copyright notices or/and claim this script as your own.',
        'disclaimer_7'      => 'Last but not least, Enjoy.',
        'before_install'    => '🐧 Before Installation (Linux)',
        'after_install'     => '✅ After Installation',
        'protect_admin'     => '🔒 Protect /Admin',
        'protect_admin_txt' => 'with password protect directory (.htaccess).',
        'next'              => 'Next →',
        'team_signature'    => 'Novaterra Team',

        // dataform.tpl / wdata.tpl (db + world data steps)
        'err_import_db'     => 'Error importing database. Check configuration.',
        'err_struct_found'  => 'Existing structure found! Please remove tables with prefix',
        'from_database'     => 'from database',
        'create_db_struct'  => 'Create Database Structure',
        'warning_wait'      => 'Warning',
        'warning_wait_txt'  => 'This can take some time. Please wait until the next page loads.',
        'create_db_btn'     => 'Create Database...',
        'err_create_wdata'  => 'Error creating wdata. Check configuration or file.',
        'err_wdata_found'   => 'Existing World Data found! Empty tables',
        'create_world_data' => 'Create World Data',
        'warning_wait_txt2' => 'This can take some time. Please wait until the next page has been loaded.',
        'create_world_btn'  => 'Create World...',
        'building_croppers' => 'Building croppers…',
        'starting'          => 'Starting…',
        'proceeding_in'     => 'Proceeding to next step in',

        // accounts.tpl
        'err_mh_support'    => 'At least Multihunter & Support password are required.',
        'err_natars_reserv' => 'Natars is reserved. Choose different admin username.',
        'multihunter_acc'   => 'Multihunter account',
        'support_acc'       => 'Support account',
        'admin_acc'         => 'Admin account',
        'name'              => 'Name',
        'password'          => 'Password',
        'admin_name'        => 'Admin name',
        'admin_email'       => 'Admin email',
        'admin_password'    => 'Admin password',
        'tribe'             => 'Tribe',
        'show_in_stats'     => 'Show in stats',
        'include_support'   => 'Include Support Msgs',
        'allow_raidable'    => 'Allow Raidable',
        'skip_admin_note'   => 'Note: leave empty if you want to skip admin creation.',
        'create_accounts'   => 'Create Accounts',

        // end.tpl
        'install_complete'  => '🎉 Installation Complete!',
        'thanks_install'    => 'Thanks for installing Novaterra. Please remove the install folder.',
        'go_home'           => 'Go to Homepage →',
        'next_steps'        => '🚀 Next Steps',
        'secure_server'     => '🔒 Secure Your Server',
        'secure_server_txt' => 'Protect /Admin with .htpasswd, enable HTTPS, and set cronjobs for automated tasks.',
        'read_docs'         => '📖 Read the Docs',
        'read_docs_txt'     => 'Learn about speed settings, Natars, and world configuration in the GitHub Wiki.',
        'join_community'    => '💬 Join Community',
        'join_community_txt'=> 'Get help, share mods, and report bugs on GitHub Discussions.',
        'star_github'       => '⭐ Star on GitHub',
        'star_github_txt'   => 'If you like Novaterra, star the repo to support the project.',
        'contact_support'   => 'Contact Support',
        'documentation'     => 'Documentation',

        // config.tpl section headers + labels (most-used ones)
        'server_related'    => 'SERVER RELATED',
        'server_name'       => 'Server name',
        'timezone'          => 'Timezone',
        'server_speed'      => 'Server speed',
        'troop_speed'       => 'Troop speed',
        'evasion_speed'     => 'Evasion speed',
        'installer_ui_lang' => 'Installer language',
        'err_generic'       => 'ERROR!',
        'err_write_config'  => "It's not possible to write the config file. Change the permission to '777'. After that, refresh this page!",
        'err_already_done'  => 'Installation appears to have been completed.',
        'err_already_done2' => 'If this is an error remove /var/installed file in install directory.',
        'save_config'       => 'Save Configuration →',
        'natars_map'        => 'NATARS & MAP',
        'oasis_storage'     => 'OASIS & STORAGE',
        'sql_related'       => 'SQL RELATED',
        'server_urls'       => 'SERVER URLS',
        'new_mechanics'     => 'NEW MECHANICS AND FUNCTIONS',
        'plus_packages'     => 'PLUS GOLD PACKAGES',
        'newsbox_options'   => 'NEWSBOX OPTIONS',
        'log_related'       => 'LOG RELATED (You should disable them)',
        'extra_options'     => 'EXTRA OPTIONS',
        'cron_automation'   => 'CRON & AUTOMATION',
    ],
    'ar' => [
        'page_title'        => 'تثبيت نوفاتيرا',
        'install_script'    => 'سكربت تثبيت نوفاتيرا',
        'step_of'           => 'الخطوة %d من %d ',
        'steps'             => ['مقدمة', 'الإعدادات', 'قاعدة البيانات', 'بيانات العالم', 'الحسابات', 'الانتهاء'],

        // greet.tpl
        'disclaimer_title'  => '⚠️ إخلاء مسؤولية',
        'disclaimer_1'      => 'مع تثبيت/استخدام هذه اللعبة، أنت تتحمل المسؤولية الكاملة عن أي نتائج قانونية...',
        'disclaimer_2'      => 'لا الفريق الذي أنشأ هذا السكربت ولا الفريق الذي خصّصه مسؤول عن أي ضرر...',
        'disclaimer_3'      => 'تم التأكد من أن الكود يعمل بشكل صحيح من قِبل فريق الإنشاء...',
        'disclaimer_4'      => 'يُطلب من المستخدمين مراجعة الكود بأنفسهم.',
        'disclaimer_5'      => 'أي تخصيص للكود المصدري يعود لتقدير كل مطوّر.',
        'disclaimer_6'      => 'لا يحق لك تعديل إشعارات حقوق النشر أو ادعاء ملكية هذا السكربت.',
        'disclaimer_7'      => 'وأخيرًا، استمتع.',
        'before_install'    => '🐧 قبل التثبيت (Linux)',
        'after_install'     => '✅ بعد التثبيت',
        'protect_admin'     => '🔒 احمِ مجلد /Admin',
        'protect_admin_txt' => 'بحماية كلمة مرور للمجلد (.htaccess).',
        'next'              => '→ التالي',
        'team_signature'    => 'فريق نوفاتيرا',

        'err_import_db'     => 'خطأ أثناء استيراد قاعدة البيانات. تحقق من الإعدادات.',
        'err_struct_found'  => 'تم العثور على بنية جداول موجودة مسبقًا! يرجى حذف الجداول ذات البادئة',
        'from_database'     => 'من قاعدة البيانات',
        'create_db_struct'  => 'إنشاء بنية قاعدة البيانات',
        'warning_wait'      => 'تنبيه',
        'warning_wait_txt'  => 'قد يستغرق هذا بعض الوقت. يرجى الانتظار حتى تحميل الصفحة التالية.',
        'create_db_btn'     => 'جاري إنشاء قاعدة البيانات...',
        'err_create_wdata'  => 'خطأ أثناء إنشاء بيانات العالم. تحقق من الإعدادات أو الملف.',
        'err_wdata_found'   => 'تم العثور على بيانات عالم موجودة مسبقًا! أفرغ الجداول',
        'create_world_data' => 'إنشاء بيانات العالم',
        'warning_wait_txt2' => 'قد يستغرق هذا بعض الوقت. يرجى الانتظار حتى تُحمَّل الصفحة التالية.',
        'create_world_btn'  => 'جاري إنشاء العالم...',
        'building_croppers' => 'جاري إنشاء المحاصيل (Croppers)…',
        'starting'          => 'جارٍ البدء…',
        'proceeding_in'     => 'الانتقال للخطوة التالية خلال',

        'err_mh_support'    => 'كلمة مرور Multihunter وSupport مطلوبة على الأقل.',
        'err_natars_reserv' => 'اسم Natars محجوز. اختر اسم مستخدم أدمن مختلف.',
        'multihunter_acc'   => 'حساب Multihunter',
        'support_acc'       => 'حساب الدعم (Support)',
        'admin_acc'         => 'حساب الأدمن',
        'name'              => 'الاسم',
        'password'          => 'كلمة المرور',
        'admin_name'        => 'اسم الأدمن',
        'admin_email'       => 'بريد الأدمن الإلكتروني',
        'admin_password'    => 'كلمة مرور الأدمن',
        'tribe'             => 'القبيلة',
        'show_in_stats'     => 'إظهار في الإحصائيات',
        'include_support'   => 'تضمين رسائل الدعم',
        'allow_raidable'    => 'السماح بالغزو',
        'skip_admin_note'   => 'ملاحظة: اتركه فارغًا لتخطي إنشاء حساب الأدمن.',
        'create_accounts'   => 'إنشاء الحسابات',

        'install_complete'  => '🎉 اكتمل التثبيت!',
        'thanks_install'    => 'شكرًا لتثبيتك نوفاتيرا. يرجى حذف مجلد install.',
        'go_home'           => '→ الذهاب للصفحة الرئيسية',
        'next_steps'        => '🚀 الخطوات التالية',
        'secure_server'     => '🔒 أمّن سيرفرك',
        'secure_server_txt' => 'احمِ مجلد /Admin بملف .htpasswd، فعّل HTTPS، واضبط مهام الـ cron التلقائية.',
        'read_docs'         => '📖 اقرأ التوثيق',
        'read_docs_txt'     => 'تعرّف على إعدادات السرعة وNatars وإعدادات العالم في GitHub Wiki.',
        'join_community'    => '💬 انضم للمجتمع',
        'join_community_txt'=> 'احصل على مساعدة، شارك التعديلات، وبلّغ عن المشاكل عبر GitHub Discussions.',
        'star_github'       => '⭐ ضع نجمة على GitHub',
        'star_github_txt'   => 'إذا أعجبك نوفاتيرا، ضع نجمة للمستودع لدعم المشروع.',
        'contact_support'   => 'تواصل مع الدعم',
        'documentation'     => 'التوثيق',

        'server_related'    => 'إعدادات السيرفر',
        'server_name'       => 'اسم السيرفر',
        'timezone'          => 'المنطقة الزمنية',
        'server_speed'      => 'سرعة السيرفر',
        'troop_speed'       => 'سرعة الجيوش',
        'evasion_speed'     => 'سرعة المراوغة',
        'installer_ui_lang' => 'لغة صفحة التثبيت',
        'err_generic'       => 'خطأ!',
        'err_write_config'  => "لا يمكن كتابة ملف الإعدادات. غيّر صلاحية المجلد إلى '777'، ثم أعد تحميل الصفحة!",
        'err_already_done'  => 'يبدو أن التثبيت قد اكتمل بالفعل.',
        'err_already_done2' => 'إذا كان هذا خطأ، احذف ملف /var/installed من مجلد التثبيت.',
        'save_config'       => '→ حفظ الإعدادات',
        'natars_map'        => 'نظام Natars والخريطة',
        'oasis_storage'     => 'الواحات والمخازن',
        'sql_related'       => 'إعدادات قاعدة البيانات (SQL)',
        'server_urls'       => 'روابط السيرفر',
        'new_mechanics'     => 'آليات وميزات إضافية',
        'plus_packages'     => 'باقات الذهب (Plus)',
        'newsbox_options'   => 'إعدادات صندوق الأخبار',
        'log_related'       => 'إعدادات السجلات (يُفضّل تعطيلها)',
        'extra_options'     => 'إعدادات إضافية',
        'cron_automation'   => 'الأتمتة وCron',
    ],
];

if(!function_exists('t')) {
    function t($key) {
        $lang = $GLOBALS['INSTALL_UI_LANG'] ?? 'ar';
        $strings = $GLOBALS['INSTALL_STRINGS'][$lang] ?? $GLOBALS['INSTALL_STRINGS']['en'];
        if(isset($strings[$key])) {
            return $strings[$key];
        }
        // Fall back to English, then to the raw key if truly missing
        return $GLOBALS['INSTALL_STRINGS']['en'][$key] ?? $key;
    }
}

if(!function_exists('install_is_rtl')) {
    function install_is_rtl() {
        return ($GLOBALS['INSTALL_UI_LANG'] ?? 'ar') === 'ar';
    }
}

if(!function_exists('install_lang_switch_html')) {
    function install_lang_switch_html() {
        $cur = $GLOBALS['INSTALL_UI_LANG'] ?? 'ar';
        $qs = $_GET;
        unset($qs['ilang']);
        $base = '?' . http_build_query($qs) . (empty($qs) ? '' : '&');
        $arActive = $cur === 'ar' ? 'font-weight:700;text-decoration:underline;' : 'opacity:.7;';
        $enActive = $cur === 'en' ? 'font-weight:700;text-decoration:underline;' : 'opacity:.7;';
        return '<div style="text-align:'.($cur==='ar'?'left':'right').';margin:8px 16px 0;font-size:13px;">'
            . '<a href="'.$base.'ilang=ar" style="color:#fff;margin:0 6px;'.$arActive.'">العربية</a>'
            . '|'
            . '<a href="'.$base.'ilang=en" style="color:#fff;margin:0 6px;'.$enActive.'">English</a>'
            . '</div>';
    }
}
