<?php

if (!function_exists('tz_def')) {
    function tz_def($k, $v) { if (!defined($k)) { define($k, $v); } }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////
//                                             NOVATERRA                                             //
//            Only for advanced users, do not edit if you dont know what are you doing!             //
//                                Made by: Dzoki & Dixie (Novaterra)                                 //
//                              - Novaterra = Novaterra Clone Project -                                //
//                                 DO NOT REMOVE COPYRIGHT NOTICE!                                  //
//                                Adding tasks, constructions and artefact  by: Armando             //
//                                Modified , added , fixed , implementd  by: Shadow and ronix       //
//                                                                             						//
//  					URLs:           https://novaterra.example                                        //
//                 						https://github.com/omotaz556-cloud/tatar                        //
//                                                                             						//
//////////////////////////////////////////////////////////////////////////////////////////////////////
									//                         //
									//         ARABIC          //
									//   FIRST PASS (partial)  //
									//////////////////////////////

// ============================================================================
// ARABIC LANGUAGE FILE - FIRST PASS (نسخة أولى)
//
// This file is NOT a full translation. It covers only the highest-visibility
// strings: main menu, tribe/unit names, core errors, index/login/register
// pages, attack & map screens, and the most common buildings.
//
// Every key intentionally left out of this file automatically falls back to
// English via GameEngine/Lang/loader.php (tz_load_language merges English
// underneath the selected language, so undefined keys are never blank).
//
// See _TATARS_RENAME_NOTE.md for the list of remaining sections (quest lore,
// full building descriptions, admin panel templates, WW story text, etc.)
// that still need translation in a follow-up pass.
// ============================================================================

//MAIN MENU
tz_def('TRIBE1', 'الرومان');
tz_def('TRIBE2', 'التوتون');
tz_def('TRIBE3', 'الغال');
tz_def('TRIBE4', 'الطبيعة');
tz_def('TRIBE5', 'التتار');
tz_def('TRIBE6', 'الهون');
tz_def('TRIBE7', 'المصريون');
tz_def('TRIBE8', 'الإسبرطيون');
tz_def('TRIBE9', 'الفايكنج');

tz_def('HOME', 'الصفحة الرئيسية');
tz_def('INSTRUCT', 'التعليمات');
tz_def('ADMIN_PANEL', 'لوحة الإدارة');
tz_def('MH_PANEL', 'لوحة صائد الغش');
tz_def('MASS_MESSAGE', 'رسالة جماعية');
tz_def('LOGOUT', 'تسجيل الخروج');
tz_def('PROFILE', 'الملف الشخصي');
tz_def('SUPPORT', 'الدعم');
tz_def('UPDATE_T_10', 'تحديث أفضل 10');
tz_def('SYSTEM_MESSAGE', 'رسالة النظام');
tz_def('NOVATERRA_PLUS', 'نوفاتيرا <b><span class="plus_g">پ</span><span class="plus_o">ل</span><span class="plus_g">ا</span><span class="plus_o">س</span></span></span></b>');
tz_def('CONTACT', 'اتصل بنا!');
tz_def('GAME_RULES', 'قواعد اللعبة');

//MENU
tz_def('REG', 'تسجيل حساب جديد');
tz_def('FORUM', 'المنتدى');
tz_def('CHAT', 'الدردشة');
tz_def('IMPRINT', 'بيانات الموقع');
tz_def('MORE_LINKS', 'روابط أخرى');
tz_def('TOUR', 'جولة في اللعبة');

//ERRORS
tz_def('USRNM_EMPTY', '(اسم المستخدم فارغ)');
tz_def('USRNM_TAKEN', '(هذا الاسم مستخدم بالفعل)');
tz_def('USRNM_SHORT', '(بحد أدنى '.USRNM_MIN_LENGTH.' أحرف)');
tz_def('USRNM_CHAR', '(أحرف غير صالحة)');
tz_def('PW_EMPTY', '(كلمة المرور فارغة)');
tz_def('PW_SHORT', '(بحد أدنى '.PW_MIN_LENGTH.' أحرف)');
tz_def('PW_INSECURE', '(كلمة المرور غير آمنة. الرجاء اختيار كلمة أقوى)');
tz_def('EMAIL_EMPTY', '(البريد الإلكتروني فارغ)');
tz_def('EMAIL_INVALID', '(عنوان بريد إلكتروني غير صالح)');
tz_def('EMAIL_TAKEN', '(هذا البريد الإلكتروني مستخدم بالفعل)');
tz_def('WINNER_ERROR', '<li>انتهى الخادم! لا يمكن إجراء تسجيلات جديدة.</li>');
tz_def('TRIBE_EMPTY', '<li>الرجاء اختيار قبيلة.</li>');
tz_def('AGREE_ERROR', '<li>يجب الموافقة على قواعد اللعبة والشروط والأحكام العامة للتسجيل.</li>');
tz_def('LOGIN_USR_EMPTY', 'أدخل الاسم.');
tz_def('LOGIN_PASS_EMPTY', 'أدخل كلمة المرور.');
tz_def('LOGIN_VACATION', 'وضع الإجازة لا يزال مفعّلاً.');
tz_def('EMAIL_ERROR', 'البريد الإلكتروني غير مطابق للمسجل');
tz_def('PASS_MISMATCH', 'كلمتا المرور غير متطابقتين');
tz_def('ALLI_OWNER', 'الرجاء تعيين مالك للتحالف قبل الحذف');
tz_def('SIT_ERROR', 'الوصي معيّن بالفعل أو اللاعب غير موجود');
tz_def('USR_NT_FOUND', 'الاسم غير موجود.');
tz_def('LOGIN_PW_ERROR', 'كلمة المرور غير صحيحة.');
tz_def('WEL_TOPIC', 'نصائح ومعلومات مفيدة');
tz_def('ATAG_EMPTY', 'الرمز فارغ');
tz_def('ANAME_EMPTY', 'الاسم فارغ');
tz_def('ATAG_EXIST', 'الرمز مستخدم بالفعل');
tz_def('ANAME_EXIST', 'الاسم مستخدم بالفعل');
tz_def('ALREADY_ALLY_MEMBER', 'أنت بالفعل عضو في تحالف');
tz_def('ALLY_TOO_LOW', 'يجب أن يكون لديك سفارة من المستوى 3 أو أعلى');
tz_def('USER_NOT_IN_YOUR_ALLY', 'هذا المستخدم ليس في تحالفك!');
tz_def('CANT_EDIT_YOUR_PERMISSIONS', 'لا يمكنك تعديل صلاحياتك الخاصة!');
tz_def('CANT_EDIT_LEADER_PERMISSIONS', 'لا يمكن تعديل صلاحيات قائد التحالف!');
tz_def('CANT_REMOVE_LEADER', 'لا يمكنك طرد مؤسس التحالف!');
tz_def('FOUNDER_LEAVE_NEW', 'لم يتم اختيار المؤسس الجديد!');
tz_def('FOUNDER_LEAVE_INVALID', 'مؤسس غير صالح!');
tz_def('NAME_EMPTY', 'الرجاء إدخال الاسم');
tz_def('NO_PERMISSION', 'ليس لديك صلاحيات كافية!');
tz_def('NAME_OR_DIPL_EMPTY', 'الاسم أو الدبلوماسية فارغة');
tz_def('ALLY_DOESNT_EXISTS', 'التحالف غير موجود');
tz_def('CANNOT_INVITE_SAME_ALLY', 'لا يمكنك دعوة تحالفك الخاص');
tz_def('WRONG_DIPLOMACY', 'اختيار غير صحيح');
tz_def('INVITE_ALREADY_SENT', 'إما أنك أرسلت بالفعل ميثاقًا لهذا التحالف، أو أنهم أرسلوه لتحالفك، أو أن لديك بالفعل ميثاقًا معهم');
tz_def('INVITE_SENT', 'تم إرسال الدعوة');
tz_def('DECLARED_WAR_ON', 'أعلن الحرب على');
tz_def('OFFERED_NON_AGGRESION_PACT_TO', 'عرض ميثاق عدم اعتداء على');
tz_def('OFFERED_CONFED_TO', 'عرض اتحادًا على');
tz_def('ALLY_TOO_MUCH_PACTS', 'إما أنه لا يمكنك عرض المزيد من المواثيق من هذا النوع، أو أن هذا التحالف بلغ الحد الأقصى لهذا النوع من المواثيق');
tz_def('ALLY_PERMISSIONS_UPDATED', 'تم تحديث الصلاحيات');
tz_def('ALLY_FORUM_LINK_UPDATED', 'تم تحديث رابط المنتدى');
tz_def('NO_FORUMS_YET', 'لا توجد منتديات بعد.');
tz_def('ALLY_USER_KICKED', ' تم طرده من التحالف');
tz_def('NOT_OPENED_YET', 'الخادم لم يبدأ بعد.');
tz_def('REGISTER_CLOSED', 'التسجيل مغلق. لا يمكنك التسجيل في هذا الخادم.');
tz_def('NAME_NO_EXIST', 'لا يوجد مستخدم بهذا الاسم ');
tz_def('ID_NO_EXIST', 'لا يوجد مستخدم بهذا المعرّف ');
tz_def('SAME_NAME', 'لا يمكنك دعوة نفسك');
tz_def('ALREADY_INVITED', ' مدعوّ بالفعل');
tz_def('ALREADY_IN_ALLY', ' موجود بالفعل في هذا التحالف');
tz_def('ALREADY_IN_AN_ALLY', ' موجود بالفعل في تحالف');
tz_def('NAME_OR_TAG_CHANGED', 'تم تغيير الاسم أو الرمز');
tz_def('VAC_MODE_WRONG_DAYS', 'لقد أدخلت عددًا غير صحيح من الأيام');

//COPYRIGHT
tz_def('NOVATERRA_COPYRIGHT', 'Novaterra نسخة مفتوحة المصدر بالكامل من Novaterra.');

//BUILD.TPL
tz_def('CUR_PROD', 'الإنتاج الحالي');
tz_def('NEXT_PROD', 'الإنتاج عند المستوى ');
tz_def('CONSTRUCT_BUILD', 'إنشاء مبنى');

//DORF1
tz_def('LUMBER', 'خشب');
tz_def('CLAY', 'طين');
tz_def('IRON', 'حديد');
tz_def('CROP', 'محصول');
tz_def('LEVEL', 'المستوى');
tz_def('CROP_COM', 'استهلاك '.CROP);
tz_def('PER_HR', 'في الساعة');
tz_def('PRODUCTION', 'الإنتاج');
tz_def('CAPITAL1', 'العاصمة');
tz_def('VILLAGES', 'القرى');
tz_def('ANNOUNCEMENT', 'إعلان');
tz_def('GO2MY_VILLAGE', 'الذهاب إلى قريتي');
tz_def('VILLAGE_CENTER', 'مركز القرية');
tz_def('FINISH_GOLD', 'إنهاء كل أوامر البناء والأبحاث في هذه القرية فورًا مقابل 2 ذهب؟');
tz_def('WAITING_LOOP', '(في قائمة الانتظار)');
tz_def('CROP_NEGATIVE', 'إنتاج المحصول لديك سالب، لن تصل أبدًا إلى الكمية المطلوبة من الموارد.');
tz_def('HR', 'س.');
tz_def('HRS', '(ساعات)');
tz_def('DONE_AT', 'ينتهي عند');
tz_def('CANCEL', 'إلغاء');
tz_def('LOYALTY', 'الولاء');
tz_def('CALCULATED_IN', 'يُحسب خلال');
tz_def('HI', 'مرحبًا');
tz_def('P_IN', 'في');
tz_def('MS', 'مللي ثانية');
tz_def('SERVER_TIME', 'وقت الخادم:');
tz_def('LOCAL_TIME', 'الوقت المحلي:');
tz_def('REMAINING_GOLD', 'الذهب المتبقي');

//======================================================//
//================ UNITS - DO NOT EDIT! ================//
//======================================================//
tz_def('U0', 'البطل');

//ROMAN UNITS
tz_def('U1', 'جندي فيلق');
tz_def('U2', 'حارس متميز');
tz_def('U3', 'إمبيريان');
tz_def('U4', 'فارس استطلاع');
tz_def('U5', 'فارس إمبراطوري');
tz_def('U6', 'فارس قيصري');
tz_def('U7', 'كبش هدم');
tz_def('U8', 'منجنيق ناري');
tz_def('U9', 'شيخ مجلس الشيوخ');
tz_def('U10', 'مستوطن');

//TEUTON UNITS
tz_def('U11', 'حامل الهراوة');
tz_def('U12', 'رامح');
tz_def('U13', 'حامل فأس');
tz_def('U14', 'كشّاف');
tz_def('U15', 'فارس مقدّس');
tz_def('U16', 'فارس توتوني');
tz_def('U17', 'كبش هدم');
tz_def('U18', 'منجنيق');
tz_def('U19', 'الزعيم');
tz_def('U20', 'مستوطن');

//GAUL UNITS
tz_def('U21', 'فالانكس');
tz_def('U22', 'مبارز بالسيف');
tz_def('U23', 'رائد استكشاف');
tz_def('U24', 'رعد ثيوتاتيس');
tz_def('U25', 'فارس الكهنة');
tz_def('U26', 'هيدوان');
tz_def('U27', 'كبش هدم');
tz_def('U28', 'منجنيق ثقيل');
tz_def('U29', 'الزعيم القبلي');
tz_def('U30', 'مستوطن');
tz_def('U99', 'فخ');

//NATURE UNITS
tz_def('U31', 'جرذ');
tz_def('U32', 'عنكبوت');
tz_def('U33', 'ثعبان');
tz_def('U34', 'خفاش');
tz_def('U35', 'خنزير بري');
tz_def('U36', 'ذئب');
tz_def('U37', 'دب');
tz_def('U38', 'تمساح');
tz_def('U39', 'نمر');
tz_def('U40', 'فيل');

//TATARS UNITS
tz_def('U41', 'رامح مشاة');
tz_def('U42', 'محارب شائك');
tz_def('U43', 'حارس');
tz_def('U44', 'طيور جارحة');
tz_def('U45', 'فارس فأس');
tz_def('U46', 'فارس تتاري');
tz_def('U47', 'فيل حربي');
tz_def('U48', 'باليستا');
tz_def('U49', 'الإمبراطور التتري');
tz_def('U50', 'مستوطن تتاري');
//HUNS (TRIBE 6)
tz_def('U51', 'محارب هوني');
tz_def('U52', 'فارس استطلاع');
tz_def('U53', 'رامي سهام على حصان');
tz_def('U54', 'فارس السهوب');
tz_def('U55', 'رمّاح هوني');
tz_def('U56', 'فارس نخبة');
tz_def('U57', 'كبش هدم');
tz_def('U58', 'منجنيق');
tz_def('U59', 'زعيم القبيلة');
tz_def('U60', 'مستوطن هوني');
//EGYPTIANS (TRIBE 7)
tz_def('U61', 'عبد مسلّح');
tz_def('U62', 'مقاتل مصري');
tz_def('U63', 'حارس المعبد');
tz_def('U64', 'كشّاف على حصان');
tz_def('U65', 'عربة حربية');
tz_def('U66', 'العربة الملكية');
tz_def('U67', 'كبش هدم');
tz_def('U68', 'منجنيق');
tz_def('U69', 'حاكم الإقليم');
tz_def('U70', 'مستوطن مصري');
//SPARTANS (TRIBE 8)
tz_def('U71', 'محارب هوبليت إسبرطي');
tz_def('U72', 'محارب أگوجي');
tz_def('U73', 'هومويوي');
tz_def('U74', 'كشّاف بيريويكوي');
tz_def('U75', 'فارس إسبرطي');
tz_def('U76', 'هيبيس');
tz_def('U77', 'كبش هدم');
tz_def('U78', 'منجنيق');
tz_def('U79', 'الإيفور');
tz_def('U80', 'مستوطن إسبرطي');
//VIKINGS (TRIBE 9)
tz_def('U81', 'غازي فايكنج');
tz_def('U82', 'كشّاف فايكنج');
tz_def('U83', 'حامل فأس');
tz_def('U84', 'برسركر');
tz_def('U85', 'فارس فايكنج');
tz_def('U86', 'هوسكارل');
tz_def('U87', 'كبش هدم');
tz_def('U88', 'منجنيق');
tz_def('U89', 'الإيرل');
tz_def('U90', 'مستوطن فايكنج');

//INDEX.php
tz_def('LOGIN', 'تسجيل الدخول');
tz_def('PLAYERS', 'اللاعبون');
tz_def('MODERATOR', 'مشرف');
tz_def('ACTIVE', 'نشط');
tz_def('ONLINE', 'متصل');
tz_def('TUTORIAL', 'الدليل التعليمي');
if(!defined('FAQ')) tz_def('FAQ', 'الأسئلة الشائعة');
if(!defined('SPIELREGELN')) tz_def('SPIELREGELN', 'قواعد اللعبة');
tz_def('PLAYER_STATISTICS', 'إحصائيات اللاعبين');
tz_def('ACTIVE_PLAYERS', 'اللاعبون النشطون');
tz_def('REGISTER_FOR_FREE', 'سجّل هنا مجانًا!');
tz_def('LATEST_GAME_WORLD', 'آخر عالم لعبة');
tz_def('COMUNITY', 'المجتمع');
tz_def('NEWS', 'الأخبار');
tz_def('SCREENSHOTS', 'لقطات الشاشة');
tz_def('AGB', 'الشروط والأحكام');
tz_def('P_ONLINE', 'اللاعبون المتصلون: ');
tz_def('P_TOTAL', 'إجمالي اللاعبين: ');
tz_def('CHOOSE', 'الرجاء اختيار خادم.');

//ANMELDEN.php
tz_def('NICKNAME', 'الاسم المستعار');
tz_def('EMAIL', 'البريد الإلكتروني');
tz_def('PASSWORD', 'كلمة المرور');
tz_def('NW', 'الشمال الغربي');
tz_def('NE', 'الشمال الشرقي');
tz_def('SW', 'الجنوب الغربي');
tz_def('SE', 'الجنوب الشرقي');
tz_def('RANDOM', 'عشوائي');
tz_def('ACCEPT_RULES', ' أوافق على قواعد اللعبة والشروط والأحكام العامة.');
tz_def('ONE_PER_SERVER', 'يجوز لكل لاعب امتلاك حساب واحد فقط لكل خادم.');
tz_def('BUILDING_UPGRADING', 'قيد الإنشاء:');
tz_def('HOURS', 'ساعات');

//ATTACKS ETC.
tz_def('TROOP_MOVEMENTS', 'تحركات الجيوش:');
tz_def('ARRIVING_REINF_TROOPS', 'تعزيزات قادمة');
tz_def('ARRIVING_ATTACKING_TROOPS', 'قوات مهاجمة قادمة');
tz_def('ARRIVING_REINF_TROOPS_SHORT', 'تعزيز.');
tz_def('OWN_ATTACKING_TROOPS', 'قواتك المهاجمة');
tz_def('ATTACK', 'هجوم');
tz_def('OWN_REINFORCING_TROOPS', 'تعزيزاتك');
tz_def('NEWVILLAGE', 'قرية جديدة.');
tz_def('FOUNDNEWVILLAGE', 'تأسيس قرية جديدة');
tz_def('UNDERATTACK', 'القرية تتعرض لهجوم');
tz_def('OASISATTACK', 'الواحة تتعرض لهجوم');
tz_def('RETURNFROM', 'عائد من');
tz_def('REINFORCEMENTFOR', 'تعزيز إلى');
tz_def('ATTACK_ON', 'هجوم على');
tz_def('RAID_ON', 'غارة على');
tz_def('SCOUTING', 'استطلاع');
tz_def('PRISONERS', 'الأسرى');
tz_def('TROOPS', 'الجيوش');
tz_def('BOUNTY', 'الغنيمة');
tz_def('ARRIVAL', 'الوصول');
tz_def('INCOMING_TROOPS', 'جيوش قادمة');
tz_def('OWN_TROOPS', 'جيوشك');
tz_def('UPKEEP', 'إعاشة');
tz_def('SEND_BACK', 'إعادة الإرسال');
tz_def('KILL', 'إبادة');
tz_def('FROM', 'من');
tz_def('SEND_TROOPS', 'إرسال الجيوش');

//SEND TROOP
tz_def('REINFORCE', 'تعزيز');
tz_def('NORMALATTACK', 'هجوم عادي');
tz_def('RAID', 'غارة');
tz_def('OR', 'أو');
tz_def('SENDTROOP', 'إرسال الجيوش');
tz_def('NOTROOP', 'لا توجد جيوش');

//map
tz_def('DETAIL', 'التفاصيل');
tz_def('ABANDVALLEY', 'وادٍ مهجور');
tz_def('OCCUPIED', 'مأهول');
tz_def('UNOCCUPIED', 'غير مأهول');
tz_def('UNOCCUOASIS', 'واحة غير مأهولة');
tz_def('OCCUOASIS', 'واحة مأهولة');
tz_def('LANDDIST', 'توزيع الأراضي');
tz_def('TRIBE', 'القبيلة');
tz_def('ALLIANCE', 'التحالف');
tz_def('POP', 'عدد السكان');
tz_def('REPORT', 'تقرير');
tz_def('OPTION', 'خيارات');
tz_def('CENTREMAP', 'توسيط الخريطة');
tz_def('FNEWVILLAGE', 'تأسيس قرية جديدة');
tz_def('SENDMERC', 'إرسال تجار');
tz_def('BAN', 'اللاعب محظور');
tz_def('PERHOUR', 'في الساعة');
tz_def('BONUS', 'مكافأة');
tz_def('MAP', 'الخريطة');
tz_def('LARGE_MAP', 'الخريطة الكبيرة');
tz_def('CROPFINDER', 'باحث المحاصيل');
tz_def('NORTH', 'الشمال');
tz_def('EAST', 'الشرق');
tz_def('SOUTH', 'الجنوب');
tz_def('WEST', 'الغرب');
tz_def('CLOSE_MAP', 'إغلاق الخريطة');
tz_def('AND', 'و');

//other
tz_def('VILLAGE', 'القرية');
tz_def('STATISTICS', 'الإحصائيات');
tz_def('ALLIANCES', 'التحالفات');
tz_def('HEROES', 'الأبطال');
tz_def('GENERAL', 'عام');
tz_def('WWS', 'عجائب الدنيا');
tz_def('TOP10P', 'أفضل 10 لاعبين');
tz_def('TOP10PA', 'أفضل 10 مهاجمين');
tz_def('TOP10PD', 'أفضل 10 مدافعين');
tz_def('TOP10A', 'أفضل 10 تحالفات');
tz_def('MILESTONES', 'الإنجازات');
tz_def('OASIS', 'الواحة');
tz_def('NO_OASIS', 'لا تملك أي واحات.');
tz_def('NO_VILLAGES', 'لا توجد قرى.');
tz_def('PLAYER', 'اللاعب');

//LOGIN.php
tz_def('NAME', 'الاسم');
tz_def('PW_FORGOTTEN', 'نسيت كلمة المرور؟');
tz_def('PW_GENERATE', 'إنشاء كلمة مرور جديدة.');
tz_def('EMAIL_NOT_VERIFIED', 'البريد الإلكتروني غير مُفعّل!');
tz_def('VERIFY_EMAIL', 'تفعيل البريد الإلكتروني.');
tz_def('SERVER_STARTS_IN', 'يبدأ الخادم خلال: ');
tz_def('START_NOW', 'ابدأ الآن');

//404.php
tz_def('NOTHING_HERE', 'لا يوجد شيء هنا!');

//INDEX PAGE (legacy $lang array — merged over English by loader.php)
$lang['index'][0][1] = 'مرحبًا بك';
$lang['index'][0][2] = 'الدليل';
$lang['index'][0][3] = 'العب الآن مجانًا!';
$lang['index'][0][4] = 'ما هي هذه اللعبة';
$lang['index'][0][5] = 'هي <b>لعبة متصفح</b> تقدّم عالمًا قديمًا شيقًا مع آلاف اللاعبين الحقيقيين الآخرين.</p><p><strong>مجانية اللعب</strong> بالكامل ولا تتطلب <strong>أي تحميل</strong>.';
$lang['index'][0][6] = 'اضغط هنا للعب الآن';
$lang['index'][0][7] = 'إجمالي اللاعبين';
$lang['index'][0][8] = 'اللاعبون النشطون';
$lang['index'][0][9] = 'اللاعبون المتصلون';
$lang['index'][0][10] = 'عن اللعبة';
$lang['index'][0][11] = 'ستبدأ كزعيم لقرية صغيرة وتنطلق في مغامرة شيقة.';
$lang['index'][0][12] = 'ابنِ قراك، خض الحروب، أو أقم طرق التجارة مع جيرانك.';
$lang['index'][0][13] = 'العب مع وضد آلاف اللاعبين الحقيقيين الآخرين، واحتلّ عالم اللعبة.';
$lang['index'][0][14] = 'الأخبار';
$lang['index'][0][15] = 'الأسئلة الشائعة';
$lang['index'][0][16] = 'لقطات الشاشة';

$lang['screenshots']['title1'] = 'القرية';
$lang['screenshots']['desc1'] = 'بناء القرية';
$lang['screenshots']['title2'] = 'الموارد';
$lang['screenshots']['desc2'] = 'موارد القرية هي الخشب، الطين، الحديد والمحاصيل';
$lang['screenshots']['title3'] = 'الخريطة';
$lang['screenshots']['desc3'] = 'حدد موقع قريتك على الخريطة';
$lang['screenshots']['title4'] = 'تشييد المباني';
$lang['screenshots']['desc4'] = 'كيفية تشييد مبنى أو رفع مستوى مورد';
$lang['screenshots']['title5'] = 'التقارير';
$lang['screenshots']['desc5'] = 'تقرير هجومك';
$lang['screenshots']['title6'] = 'الإحصائيات';
$lang['screenshots']['desc6'] = 'تصفّح ترتيبك في الإحصائيات';
$lang['screenshots']['title7'] = 'السلاح أم الاقتصاد';
$lang['screenshots']['desc7'] = 'يمكنك اختيار اللعب كقوة عسكرية أو كقوة اقتصادية';
$lang['forum'] = 'المنتدى';
$lang['register'] = 'تسجيل حساب';
$lang['login'] = 'تسجيل الدخول';

//BUILDINGS
tz_def('WOODCUTTER', 'مقطع الأخشاب');
tz_def('WOODCUTTER_DESC', 'يقطع مقطع الأخشاب الأشجار لإنتاج الخشب. كلما زاد مستوى المقطع زاد إنتاج الخشب.<br>ببناء منشرة يمكنك زيادة الإنتاج أكثر');
tz_def('CLAYPIT', 'حفرة الطين');
tz_def('CLAYPIT_DESC', 'هنا يُستخرج الطين. برفع مستواها تزيد إنتاج الطين.<br>ببناء مصنع طوب يمكنك زيادة الإنتاج أكثر');
tz_def('IRONMINE', 'منجم الحديد');
tz_def('IRONMINE_DESC', 'هنا يجمع العمال معدن الحديد الثمين. برفع مستوى المنجم تزيد إنتاج الحديد.<br>ببناء مصهر حديد يمكنك زيادة الإنتاج أكثر');
tz_def('CROPLAND', 'حقل المحاصيل');
tz_def('CROPLAND_DESC', 'هنا يُنتج غذاء سكانك. برفع مستوى الحقل تزيد إنتاج المحاصيل.<br>ببناء طاحونة ومخبز يمكنك زيادة الإنتاج أكثر');

tz_def('SAWMILL', 'المنشرة');
tz_def('SAWMILL_DESC', 'يُعالَج هنا الخشب المقطوع من الحطابين. تعزز المنشرة إنتاج الخشب في القرية. في المستوى 1 تزيد الإنتاج بنسبة 5%، وكل ترقية تضيف 5% أخرى، أي 25% إجمالاً بعد 5 مستويات.<br>تسري مكافأة المنشرة وكل مباني المكافآت على قرية المبنى فقط.<br>لاحظ أن مكافأة المنشرة لا تسري على مكافآت أخرى مثل دخل الواحات أو مكافأة البلس 10%.<br>هناك أيضًا قرى بها 3 أو 5 حقول خشب. كلما زادت الحقول، زادت فعالية مستويات المنشرة');
tz_def('CURRENT_WOOD_BONUS', 'مكافأة الخشب الحالية:');
tz_def('WOOD_BONUS_LEVEL', 'مكافأة الخشب عند المستوى');
tz_def('MAX_LEVEL', 'المبنى بالفعل عند أقصى مستوى');
tz_def('PERCENT', 'النسبة المئوية');

tz_def('BRICKYARD', 'مصنع الطوب');
tz_def('CURRENT_CLAY_BONUS', 'مكافأة الطين الحالية:');
tz_def('CLAY_BONUS_LEVEL', 'مكافأة الطين عند المستوى');
tz_def('BRICKYARD_DESC', 'يتحول الطين هنا إلى طوب. يعزز مصنع الطوب إنتاج الطين في القرية. في المستوى 1 يزيد الإنتاج بنسبة 5%، وكل ترقية تضيف 5% أخرى، أي 25% إجمالاً بعد 5 مستويات.<br>تسري مكافأة مصنع الطوب وكل مباني المكافآت على قرية المبنى فقط.<br>لاحظ أن هذه المكافأة لا تسري على مكافآت أخرى مثل دخل الواحات أو مكافأة البلس 10%.<br>هناك أيضًا قرى بها 3 أو 5 حقول طين. كلما زادت الحقول، زادت فعالية المستويات');

tz_def('IRONFOUNDRY', 'مصهر الحديد');
tz_def('CURRENT_IRON_BONUS', 'مكافأة الحديد الحالية:');
tz_def('IRON_BONUS_LEVEL', 'مكافأة الحديد عند المستوى');
tz_def('IRONFOUNDRY_DESC', 'يُصهر الحديد هنا. يعزز مصهر الحديد إنتاج الحديد في القرية. في المستوى 1 يزيد الإنتاج بنسبة 5%، وكل ترقية تضيف 5% أخرى، أي 25% إجمالاً بعد 5 مستويات.<br>تسري مكافأة المصهر وكل مباني المكافآت على قرية المبنى فقط.<br>لاحظ أن هذه المكافأة لا تسري على مكافآت أخرى مثل دخل الواحات أو مكافأة البلس 10%.<br>هناك أيضًا قرى بها 3 أو 5 حقول حديد. كلما زادت الحقول، زادت فعالية المستويات');

tz_def('GRAINMILL', 'الطاحونة');
tz_def('CURRENT_CROP_BONUS', 'مكافأة المحاصيل الحالية:');
tz_def('CROP_BONUS_LEVEL', 'مكافأة المحاصيل عند المستوى');
tz_def('GRAINMILL_DESC', 'تُطحن الحبوب هنا إلى دقيق. تعزز الطاحونة إنتاج الغذاء في القرية. في المستوى 1 تزيد الإنتاج بنسبة 5%، وكل ترقية تضيف 5% أخرى، أي 25% إجمالاً بعد 5 مستويات.<br>استخدمها مع المخبز لزيادة إجمالية في إنتاج المحاصيل تصل إلى 50%.<br>تسري مكافأة الطاحونة وكل مباني المكافآت على قرية المبنى فقط.<br>لاحظ أن هذه المكافأة لا تسري على مكافآت أخرى مثل دخل الواحات أو مكافأة البلس 10%.<br>هناك أيضًا قرى بها 9 أو 15 حقل محاصيل. كلما زادت الحقول، زادت فعالية المستويات');

tz_def('BAKERY', 'المخبز');
tz_def('BAKERY_DESC', 'يُخبز الخبز هنا من الدقيق. يعزز المخبز إنتاج الغذاء في القرية. في المستوى 1 يزيد الإنتاج بنسبة 5%، وكل ترقية تضيف 5% أخرى، أي 25% إجمالاً بعد 5 مستويات.<br>عند استخدامه مع الطاحونة يمكن أن يزيد إنتاج المحاصيل بنسبة تصل إلى 50%.<br>تسري مكافأة المخبز وكل مباني المكافآت على قرية المبنى فقط.<br>لاحظ أن هذه المكافأة لا تسري على مكافآت أخرى مثل دخل الواحات أو مكافأة البلس 10%.<br>هناك أيضًا قرى بها 9 أو 15 حقل محاصيل. كلما زادت الحقول، زادت فعالية المستويات');

tz_def('WAREHOUSE', 'المخزن');
tz_def('CURRENT_CAPACITY', 'السعة الحالية:');
tz_def('CAPACITY_LEVEL', 'السعة عند المستوى');
tz_def('RESOURCE_UNITS', 'وحدات الموارد');
tz_def('WAREHOUSE_DESC', 'يُخزَّن في المخزن الخشب والطين والحديد. برفع مستواه تزيد سعة المخزن. يمكن بناء أكثر من واحد، بمجرد إتمام واحد إلى أقصى مستوى');

tz_def('GRANARY', 'الصومعة');
tz_def('CROP_UNITS', 'وحدات المحاصيل');
tz_def('GRANARY_DESC', 'تُخزَّن المحاصيل المنتجة في مزارعك بالصومعة. برفع مستواها تزيد سعتها. يمكن بناء أكثر من واحدة، بمجرد إتمام واحدة إلى أقصى مستوى');

tz_def('BLACKSMITH', 'الحدادة');
tz_def('ACTION', 'الإجراء');
tz_def('UPGRADE', 'ترقية');
tz_def('UPGRADE_IN_PROGRESS', 'الترقية<br>قيد التنفيذ');
tz_def('UPGRADE_BLACKSMITH', 'ترقية<br>الحدادة');
tz_def('UPGRADES_COMMENCE_BLACKSMITH', 'يمكن بدء الترقيات عند اكتمال الحدادة.');
tz_def('MAXIMUM_LEVEL', 'أقصى<br>مستوى');
tz_def('EXPAND_WAREHOUSE', 'توسيع<br>المخزن');
tz_def('EXPAND_GRANARY', 'توسيع<br>الصومعة');
tz_def('ENOUGH_RESOURCES', 'موارد كافية');
tz_def('CROP_NEGATIVE ', 'إنتاج المحاصيل سالب لذا لن تصل أبدًا إلى الموارد المطلوبة');
tz_def('TOO_FEW_RESOURCES', 'موارد<br>غير كافية');
tz_def('UPGRADING', 'الترقية جارية');
tz_def('DURATION', 'المدة');
tz_def('COMPLETE', 'اكتمال');
tz_def('BLACKSMITH_DESC', 'تُحسَّن أسلحة محاربيك في أفران الحدادة المنصهرة. برفع مستواها يمكنك طلب صناعة أسلحة أفضل');

tz_def('ARMOURY', 'الدرع');
tz_def('UPGRADE_ARMOURY', 'ترقية<br>الدرع');
tz_def('UPGRADES_COMMENCE_ARMOURY', 'يمكن بدء الترقيات عند اكتمال ورشة الدرع.');
tz_def('ARMOURY_DESC', 'تُحسَّن دروع محاربيك في أفران ورشة الدرع المنصهرة. برفع مستواها يمكنك طلب صناعة دروع أفضل');

tz_def('TOURNAMENTSQUARE', 'ساحة المسابقات');
tz_def('CURRENT_SPEED', 'مكافأة السرعة الحالية:');
tz_def('SPEED_LEVEL', 'مكافأة السرعة عند المستوى');
tz_def('TOURNAMENTSQUARE_DESC', 'يمكن لجيوشك زيادة قدرتها على التحمل في ساحة المسابقات. كلما زاد مستوى المبنى، أصبحت جيوشك أسرع بعد مسافة أدنى قدرها '.TS_THRESHOLD.' مربعًا');

tz_def('MAINBUILDING', 'المبنى الرئيسي');
tz_def('CURRENT_CONSTRUCTION_TIME', 'زمن البناء الحالي:');
tz_def('CONSTRUCTION_TIME_LEVEL', 'زمن البناء عند المستوى');
tz_def('DEMOLITION_BUILDING', 'هدم المبنى:</h2><p>إذا لم تعد بحاجة لمبنى، يمكنك طلب هدمه.</p>');
tz_def('DEMOLISH', 'هدم');
tz_def('DEMOLITION_OF', 'هدم ');
tz_def('MAINBUILDING_DESC', 'يعيش كبار البنائين في القرية بالمبنى الرئيسي. كلما زاد مستواه، أنجز البنّاؤون بناء المباني الجديدة أسرع.');

tz_def('RALLYPOINT', 'نقطة التجمع');
tz_def('RALLYPOINT_COMMENCE', 'ستظهر تحركات الجيوش عند اكتمال '.RALLYPOINT);
tz_def('OVERVIEW', 'نظرة عامة');
tz_def('REINFORCEMENT', 'تعزيز');
tz_def('EVASION_SETTINGS', 'إعدادات المراوغة');
tz_def('SEND_TROOPS_AWAY_MAX', 'إرسال الجيوش بعيدًا كحد أقصى');
tz_def('TIMES', 'مرات');
tz_def('PER_EVASION', 'لكل مراوغة');
tz_def('RALLYPOINT_DESC', 'تتجمع جيوش قريتك هنا. من هنا يمكنك إرسالها لغزو قرى أخرى أو غزوها أو تعزيزها.<br>إذا كان عدد الوحدات المهاجمة أقل من مستوى نقطة التجمع، يمكنك رؤية نوع الوحدة المهاجمة.');
tz_def('COMBAT_SIMULATOR', 'محاكي المعارك');

tz_def('MARKETPLACE', 'السوق');
tz_def('MERCHANT', 'التجار');
tz_def('OR_', 'أو');
tz_def('GO', 'اذهب');
tz_def('UNITS_OF_RESOURCE', 'وحدات من المورد');
tz_def('MERCHANT_CARRY', 'يمكن لكل تاجر حمل');
tz_def('MERCHANT_COMING', 'تجار قادمون');
tz_def('TRANSPORT_FROM', 'نقل من');
tz_def('ARRIVAL_IN', 'الوصول خلال');
tz_def('NO_COORDINATES_SELECTED', 'لم يتم اختيار إحداثيات');
tz_def('CANNOT_SEND_RESOURCES', 'لا يمكنك إرسال موارد لنفس القرية');
tz_def('BANNED_CANNOT_SEND_RESOURCES', 'اللاعب محظور. لا يمكنك إرسال موارد له');
tz_def('RELATED_ACCOUNT_TRANSFER_BLOCKED', 'غير مسموح بتحويل الموارد بين هذين الحسابين.');
tz_def('RESOURCES_NO_SELECTED', 'لم يتم اختيار موارد');
tz_def('ENTER_COORDINATES', 'أدخل الإحداثيات أو اسم القرية');
tz_def('TOO_FEW_MERCHANTS', 'عدد التجار غير كافٍ');
tz_def('OWN_MERCHANTS_ONWAY', 'تجارك في الطريق');
tz_def('MERCHANTS_RETURNING', 'تجار عائدون');
tz_def('TRANSPORT_TO', 'نقل إلى');
tz_def('I_AN_SEARCHING', 'أبحث عن');
tz_def('I_AN_OFFERING', 'أعرض');
tz_def('OFFERS_MARKETPLACE', 'عروض السوق');
tz_def('NO_AVAILABLE_OFFERS', 'لا توجد عروض في السوق');
tz_def('OFFERED_TO_ME', 'معروض<br>عليّ');
tz_def('WANTED_TO_ME', 'مطلوب<br>مني');
tz_def('NOT_ENOUGH_MERCHANTS', 'عدد التجار غير كافٍ');
tz_def('ACCEP_OFFER', 'قبول العرض');
tz_def('NO_AVALIBLE_OFFERS', 'لا توجد عروض متاحة في السوق');
tz_def('SEARCHING', 'يبحث عن');
tz_def('OFFERING', 'يعرض');
tz_def('MAX_TIME_TRANSPORT', 'أقصى زمن نقل');
tz_def('OWN_ALLIANCE_ONLY', 'تحالفي فقط');
tz_def('INVALID_OFFER', 'عرض غير صالح');
tz_def('INVALID_MERCHANTS_REPETITION', 'معدل تكرار تجار غير صالح');
tz_def('USER_ON_VACATION', 'اللاعب في وضع الإجازة');
tz_def('VACATION_MODE', 'وضع الإجازة');
tz_def('VACATION_DESC', 'إذا كنت تخطط للغياب لفترة طويلة ولا ترغب بتعيين وصي، يمكنك تفعيل وضع الإجازة. خلال هذه الفترة يتوقف حسابك عن إنتاج الموارد ونقاط الثقافة والأبحاث والجيوش وغيرها، ويتوقف عن استقبال الهجمات والتعزيزات والغارات، أي يتجمد حسابك تمامًا. تذكر أن هذا يجمد لعبتك فقط، لا الزمن. إن كنت عضوًا في نادي الذهب فسينتهي اشتراكك خلال هذه الفترة، وإذا كان التجديد التلقائي مفعّلاً فسيُلغى أثناء وضع الإجازة. يرجى ملاحظة أن الحد الأدنى لوضع الإجازة يومان والحد الأقصى 14 يومًا');
tz_def('VACATION_DESC2', 'استخدم وضع الإجازة لحماية قراك أثناء غيابك.<br>خلال الإجازة تُعطَّل الإجراءات التالية:');
tz_def('VAC_OP1', 'إرسال أو استقبال الجيوش');
tz_def('VAC_OP2', 'بدء طلب بناء جديد');
tz_def('VAC_OP3', 'استخدام السوق');
tz_def('VAC_OP4', 'تدريب جيوش جديدة');
tz_def('VAC_OP5', 'الانضمام إلى تحالف');
tz_def('VAC_OP6', 'حذف الحساب');
tz_def('VAC_COND1', 'عدم وجود جيوش متحركة');
tz_def('VAC_COND2', 'عدم وجود جيوش متجهة لقرى أخرى');
tz_def('VAC_COND3', 'عدم وجود جيوش مرسلة لتعزيز قرى أخرى');
tz_def('VAC_COND4', 'عدم وجود لاعب لديه تعزيزات في قراك');
tz_def('VAC_COND5', 'عدم امتلاك عجيبة الدنيا');
tz_def('VAC_COND6', 'عدم امتلاك أي تحف');
tz_def('VAC_COND7', 'لست تحت حماية اللاعبين الجدد بعد الآن');
tz_def('VAC_COND8', 'عدم وجود جيوش في مصائدك');
tz_def('VAC_COND9', 'حسابك ليس قيد الحذف حاليًا');
tz_def('NOT_ENOUGH_RESOURCES', 'موارد غير كافية');
tz_def('OFFER', 'عرض');
tz_def('SEARCH', 'بحث');
tz_def('OWN_OFFERS', 'عروضي');
tz_def('ALL', 'الكل');
tz_def('NPC_TRADE', 'تجارة NPC');
tz_def('SUM', 'المجموع');
tz_def('REST', 'المتبقي');
tz_def('TRADE_RESOURCES', 'تبادل الموارد عند (الخطوة 2 من 2)');
tz_def('DISTRIBUTE_RESOURCES', 'توزيع الموارد عند (الخطوة 1 من 2)');
tz_def('OF', 'من');
tz_def('NPC_COMPLETED', 'اكتملت تجارة NPC');
tz_def('BACK_BUILDING', 'العودة إلى المبنى');
tz_def('YOU_CAN_NAT_NPC_WW', 'لا يمكنك استخدام تجارة NPC في قرية عجيبة الدنيا.');
tz_def('NPC_TRADING', 'تجارة NPC');
tz_def('SEND_RESOURCES', 'إرسال الموارد');
tz_def('BUY', 'شراء');
tz_def('TRADE_ROUTES', 'طرق التجارة');

// شراء الموارد بالذهب (تبويب "شراء بالذهب" في السوق - بند 8)
tz_def('GOLD_BUY_RESOURCES', 'شراء الموارد بالذهب');
tz_def('GOLD_BUY_RESOURCES_DESC', 'أضف موارد فورًا لهذه القرية، مقابل الذهب.');
tz_def('GOLD_BUY_CHOOSE_RESOURCE', 'المورد');
tz_def('GOLD_BUY_MIX', 'توزيع متساوٍ (على المصادر الأربعة)');
tz_def('GOLD_BUY_HOW_MUCH_GOLD', 'كمية الذهب');
tz_def('GOLD_BUY_RATE_NOTE_PREFIX', 'سعر الصرف:');
tz_def('GOLD_BUY_RATE_NOTE_SUFFIX', 'مورد.');
tz_def('GOLD_BUY_COMPLETED', 'تم شراء الموارد');
tz_def('GOLD_BUY_ERR_GOLD', 'لا يوجد ذهب كافٍ لإتمام هذه العملية.');
tz_def('GOLD_BUY_ERR_AMOUNT', 'الرجاء إدخال كمية ذهب صحيحة.');
tz_def('GOLD_BUY_ERR_FULL', 'المخزن/الحظيرة ممتلئة بالفعل - لم يتم خصم أي ذهب.');
tz_def('GOLD_BUY_ERR_DISABLED', 'هذه الميزة معطّلة حاليًا.');
tz_def('DESCRIPTION', 'الوصف');
tz_def('G_DESCR', 'وصف عام');
tz_def('TIME_LEFT', 'الوقت المتبقي');
tz_def('START', 'بدء');
tz_def('NO_TRADE_ROUTES', 'لا توجد طرق تجارة نشطة');
tz_def('TRADE_ROUTE_TO', 'طريق تجارة إلى');
tz_def('CHECKED', 'محدد');
tz_def('DAYS', 'أيام');
tz_def('EXTEND', 'تمديد');
tz_def('EDIT', 'تعديل');
tz_def('EXTEND_TRADE_ROUTES', 'تمديد طريق التجارة لمدة <b>7</b> أيام مقابل');
tz_def('CREATE_TRADE_ROUTES', 'إنشاء طريق تجارة جديد');
tz_def('DELIVERIES', 'التوصيلات');
tz_def('START_TIME_TRADE', 'وقت البدء');
tz_def('CREATE_TRADE_ROUTE', 'إنشاء طريق تجارة');
tz_def('TARGET_VILLAGE', 'القرية المستهدفة');
tz_def('EDIT_TRADE_ROUTES', 'تعديل طريق التجارة');
tz_def('TRADE_ROUTES_DESC', 'يتيح لك طريق التجارة تحديد مسارات لتاجرك يسلكها كل يوم في ساعة معينة. <br><br> تستمر افتراضيًا <b>7</b> أيام، ويمكنك تمديدها <b>7</b> أيام أخرى مقابل');
tz_def('NPC_TRADE_DESC', 'باستخدام تاجر NPC يمكنك إعادة توزيع الموارد في مخزنك كما تشاء. <br><br> يعرض السطر الأول المخزون الحالي. في السطر الثاني يمكنك اختيار توزيع آخر. يعرض السطر الثالث الفرق بين المخزون القديم والجديد.');
tz_def('MARKETPLACE_DESC', 'في السوق يمكنك تبادل الموارد مع لاعبين آخرين. كلما زاد مستواه، زادت كمية الموارد التي ينقلها تجارك في المرة الواحدة');

tz_def('EMBASSY', 'السفارة');
tz_def('TAG', 'الشعار');
tz_def('TO_THE_ALLIANCE', 'إلى التحالف');
tz_def('JOIN_ALLIANCE', 'الانضمام إلى تحالف');
tz_def('REFUSE', 'رفض');
tz_def('ACCEPT', 'قبول');
tz_def('NO_INVITATIONS', 'لا توجد دعوات متاحة.');
tz_def('NO_CREATE_ALLIANCE', 'لا يمكن للاعب المحظور إنشاء تحالف.');
tz_def('FOUND_ALLIANCE', 'تأسيس تحالف');
tz_def('EMBASSY_DESC', 'السفارة مكان للدبلوماسيين. في المستوى 1 يمكنك الانضمام إلى تحالف، وبعد رفعها للمستوى 3 يمكنك تأسيس تحالف بنفسك.<br>الحد الأقصى لأعضاء التحالف هو 60');

tz_def('BARRACKS', 'الثكنة');
tz_def('QUANTITY', 'الكمية');
tz_def('MAX', 'الحد الأقصى');
tz_def('TRAINING', 'التدريب');
tz_def('FINISHED', 'اكتمل');
tz_def('UNIT_FINISHED', 'ستنتهي الوحدة التالية خلال');
tz_def('AVAILABLE', 'متاح');
tz_def('TRAINING_COMMENCE_BARRACKS', 'يمكن بدء التدريب عند اكتمال الثكنة.');
tz_def('BARRACKS_DESC', 'يمكن تدريب المشاة في الثكنة. كلما زاد مستواها، تدرّب الجيوش أسرع');

tz_def('STABLE', 'الإسطبل');
tz_def('AVAILABLE_ACADEMY', 'لا توجد وحدات متاحة. ابحث في الأكاديمية');
tz_def('TRAINING_COMMENCE_STABLE', 'يمكن بدء التدريب عند اكتمال الإسطبل.');
tz_def('STABLE_DESC', 'يمكن تدريب الفرسان في الإسطبل. كلما زاد مستواه، تدرّب الجيوش أسرع');

tz_def('WORKSHOP', 'الورشة');
tz_def('TRAINING_COMMENCE_WORKSHOP', 'يمكن بدء التدريب عند اكتمال الورشة.');
tz_def('WORKSHOP_DESC', 'يمكن بناء آلات الحصار كالمنجنيقات والكباش في الورشة. كلما زاد مستواها، أُنتجت هذه الوحدات أسرع');

tz_def('ACADEMY', 'الأكاديمية');
tz_def('RESEARCH_AVAILABLE', 'لا توجد أبحاث متاحة');
tz_def('RESEARCH_COMMENCE_ACADEMY', 'يمكن بدء الأبحاث عند اكتمال الأكاديمية.');
tz_def('RESEARCH', 'بحث');
tz_def('EXPAND_WAREHOUSE1', 'توسيع المخزن');
tz_def('EXPAND_GRANARY1', 'توسيع الصومعة');
tz_def('RESEARCH_IN_PROGRESS', 'البحث<br>قيد التنفيذ');
tz_def('RESEARCHING', 'جارٍ البحث');
tz_def('PREREQUISITES', 'المتطلبات');
tz_def('SHOW_MORE', 'عرض المزيد');
tz_def('HIDE_MORE', 'إخفاء');
tz_def('ACADEMY_DESC', 'يمكن البحث عن أنواع وحدات جديدة في الأكاديمية. برفع مستواها يمكنك طلب أبحاث وحدات أفضل');

tz_def('CRANNY', 'المخبأ');
tz_def('CURRENT_HIDDEN_UNITS', 'الوحدات المخبأة حاليًا لكل مورد:');
tz_def('HIDDEN_UNITS_LEVEL', 'الوحدات المخبأة لكل مورد عند المستوى');
tz_def('UNITS', 'وحدات');
tz_def('CRANNY_DESC', 'يخفي المخبأ بعض مواردك في حال تعرضت القرية لهجوم. لا يمكن سرقة هذه الموارد.<br>في المستوى 1 يمكن للمخبأ حفظ '.(100*((int)CRANNY_CAPACITY)).' من كل مورد. سعة مخابئ الغال أكبر بـ 1.5 مرة.<br>إذا هاجم بطل توتوني قرية، فلا يمكن للمخابئ إخفاء سوى 80% من سعتها المعتادة');

tz_def('TOWNHALL', 'قاعة المدينة');
tz_def('CELEBRATIONS_COMMENCE_TOWNHALL', 'يمكن بدء الاحتفالات عند اكتمال قاعة المدينة.');
tz_def('GREAT_CELEBRATIONS', 'احتفال كبير');
tz_def('CULTURE_POINTS', 'نقاط الثقافة');
tz_def('HOLD', 'إقامة');
tz_def('CELEBRATIONS_IN_PROGRESS', 'الاحتفال<br>قيد التنفيذ');
tz_def('CELEBRATIONS', 'الاحتفالات');
tz_def('TOWNHALL_DESC', 'في قاعة المدينة يمكنك إقامة احتفالات فخمة. يزيد هذا الاحتفال نقاط ثقافتك.<br>نقاط الثقافة ضرورية لتأسيس أو غزو قرى جديدة. كل مبنى ينتج نقاط ثقافة، وكلما زاد مستواه زاد إنتاجها');

tz_def('RESIDENCE', 'المقر');
tz_def('CAPITAL', 'هذه عاصمتك');
tz_def('RESIDENCE_TRAIN_DESC', 'لتأسيس قرية جديدة تحتاج مقرًا بمستوى 10 أو 20 و3 مستوطنين. لغزو قرية جديدة تحتاج مقرًا بمستوى 10 أو 20 وشيخ مجلس شيوخ أو زعيم أو زعيم قبلي.');
tz_def('PRODUCTION_POINTS', 'إنتاج هذه القرية:');
tz_def('PRODUCTION_ALL_POINTS', 'إنتاج كل القرى:');
tz_def('POINTS_DAY', 'نقاط الثقافة يوميًا');
tz_def('VILLAGES_PRODUCED', 'أنتجت قراك');
tz_def('POINTS_NEED', 'نقطة إجمالاً. لتأسيس أو غزو قرية جديدة تحتاج');
tz_def('POINTS', 'نقطة');
tz_def('INHABITANTS', 'السكان');
tz_def('COORDINATES', 'الإحداثيات');
tz_def('EXPANSION', 'التوسع');
tz_def('TRAIN', 'تدريب');
tz_def('DATE', 'التاريخ');
tz_def('CONQUERED_BY_VILLAGE', 'القرى التي أسستها أو غزتها هذه القرية');
tz_def('NONE_CONQUERED_BY_VILLAGE', 'لم تؤسس أو تغزُ هذه القرية أي قرية أخرى بعد.');
tz_def('RESIDENCE_CULTURE_DESC', 'لتوسيع إمبراطوريتك تحتاج نقاط ثقافة. تزداد هذه النقاط بمرور الوقت وبشكل أسرع كلما زادت مستويات مبانيك.');
tz_def('RESIDENCE_LOYALTY_DESC', 'بالهجوم بشيوخ مجلس الشيوخ أو الزعماء أو الزعماء القبليين يمكن خفض ولاء قرية. إذا وصل للصفر، تنضم القرية لمملكة المهاجم. ولاء هذه القرية حاليًا هو ');
tz_def('RESIDENCE_DESC', 'يحمي المقر القرية من الغزو المعادي. يمكن بناء مقر واحد لكل قرية. يمكن تدريب الوحدات القادرة على تأسيس قرية جديدة أو غزو قرى موجودة هنا.<br>كما يوفر المقر فتحة توسع عند المستويين 10 و20');

tz_def('PALACE', 'القصر');
tz_def('PALACE_CONSTRUCTION', 'القصر قيد الإنشاء');
tz_def('PALACE_TRAIN_DESC', 'لتأسيس قرية جديدة تحتاج قصرًا بمستوى 10 أو 15 أو 20 و3 مستوطنين. لغزو قرية جديدة تحتاج قصرًا بمستوى 10 أو 15 أو 20 وشيخ مجلس شيوخ أو زعيم أو زعيم قبلي.');
tz_def('CHANGE_CAPITAL', 'تغيير العاصمة');
tz_def('SECURITY_CHANGE_CAPITAL', 'هل أنت متأكد أنك تريد تغيير عاصمتك؟<br><b>لا يمكن التراجع عن هذا!</b><br>للأمان يجب إدخال كلمة المرور للتأكيد:<br>');
tz_def('PALACE_DESC', 'مبنى القصر فريد. يمكنك بناء واحد فقط في كامل مملكتك، ويمكنك إعلان تلك القرية عاصمتك. كما يحمي القرية من الغزو المعادي. يمكن تدريب الوحدات القادرة على تأسيس قرية جديدة أو غزو قرى موجودة هنا.<br>كما يوفر القصر فتحة توسع عند المستويات 10 و15 و20');

tz_def('TREASURY', 'الخزانة');
tz_def('TREASURY_COMMENCE', 'يمكن عرض التحف عند اكتمال الخزانة.');
tz_def('ARTEFACTS_AREA', 'التحف في منطقتك');
tz_def('NO_ARTEFACTS_AREA', 'لا توجد تحف في منطقتك.');
tz_def('OWN_ARTEFACTS', 'تحفك');
tz_def('CONQUERED', 'مُستولى عليها');
tz_def('DISTANCE', 'المسافة');
tz_def('EFFECT', 'التأثير');
tz_def('ACCOUNT', 'الحساب');
tz_def('SMALL_ARTEFACTS', 'التحف الصغيرة');
tz_def('LARGE_ARTEFACTS', 'التحف الكبيرة');
tz_def('NO_ARTEFACTS', 'لا توجد تحف.');
tz_def('ANY_ARTEFACTS', 'لا تملك أي تحف.');
tz_def('OWNER', 'المالك');
tz_def('AREA_EFFECT', 'تأثير المنطقة');
tz_def('VILLAGE_EFFECT', 'تأثير القرية');
tz_def('ACCOUNT_EFFECT', 'تأثير الحساب');
tz_def('UNIQUE_EFFECT', 'تأثير فريد');
tz_def('REQUIRED_LEVEL', 'المستوى المطلوب');
tz_def('TIME_CONQUER', 'وقت الاستيلاء');
tz_def('TIME_ACTIVATION', 'وقت التفعيل');
tz_def('NEXT_EFFECT', ' التأثير التالي');
tz_def('FORMER_OWNER', 'المالك (المالكون) السابق');
tz_def('BUILDING_STRONGER', 'يجعل المبنى أقوى بمقدار');
tz_def('BUILDING_WEAKER', 'يجعل المبنى أضعف بمقدار');
tz_def('TROOPS_FASTER', 'يجعل الجيوش أسرع بمقدار');
tz_def('TROOPS_SLOWEST', 'يجعل الجيوش أبطأ بمقدار');
tz_def('SPIES_INCREASE', 'يزيد قدرة الجواسيس بمقدار');
tz_def('SPIES_DECRESE', 'يقلل قدرة الجواسيس بمقدار');
tz_def('CONSUME_LESS', 'تستهلك كل الجيوش أقل بمقدار');
tz_def('CONSUME_HIGH', 'تستهلك كل الجيوش أكثر بمقدار');
tz_def('TROOPS_MAKE_FASTER', 'يجعل تدريب الجيوش أسرع بمقدار');
tz_def('TROOPS_MAKE_SLOWEST', 'يجعل تدريب الجيوش أبطأ بمقدار');
tz_def('YOU_CONSTRUCT', 'يمكنك بناء ');
tz_def('CRANNY_INCREASED', 'تزيد سعة المخبأ بمقدار');
tz_def('CRANNY_DECRESE', 'تنقص سعة المخبأ بمقدار');
tz_def('WW_BUILDING_PLAN', 'يمكنك بناء عجيبة الدنيا');
tz_def('NO_WW', 'لا توجد عجائب دنيا');
tz_def('NO_PREVIOUS_OWNERS', 'لا يوجد مالكون سابقون.');
tz_def('TREASURY_DESC', 'تُحفظ ثروات إمبراطوريتك في الخزانة. يمكن للخزانة تخزين تحفة واحدة فقط في المرة الواحدة.<br>تحتاج خزانة بمستوى 10 لتحفة صغيرة، أو مستوى 20 لتحفة كبيرة');

tz_def('TRADEOFFICE', 'مكتب التجارة');
tz_def('CURRENT_MERCHANT', 'حمولة التاجر الحالية:');
tz_def('MERCHANT_LEVEL', 'حمولة التاجر عند المستوى');
tz_def('TRADEOFFICE_DESC', 'في مكتب التجارة تُحسَّن عربات التجار وتُجهَّز بخيول أقوى. كلما زاد مستواه، زادت قدرة تجارك على الحمل');

tz_def('GREATBARRACKS', 'الثكنة الكبرى');
tz_def('TRAINING_COMMENCE_GREATBARRACKS', 'يمكن بدء التدريب عند اكتمال الثكنة الكبرى.');
tz_def('GREATBARRACKS_DESC', 'تتيح لك الثكنة الكبرى بناء ثكنة ثانية في نفس القرية، لكن الجيوش تكلف 3 أضعاف السعر الأصلي.<br>مع الثكنة العادية، يمكنك تدريب جيوشك ضعف السرعة في قرية واحدة');

tz_def('GREATSTABLE', 'الإسطبل الكبير');
tz_def('TRAINING_COMMENCE_GREATSTABLE', 'يمكن بدء التدريب عند اكتمال الإسطبل الكبير.');
tz_def('GREATSTABLE_DESC', 'يتيح لك الإسطبل الكبير بناء إسطبل ثانٍ في نفس القرية، لكن الجيوش تكلف 3 أضعاف السعر الأصلي.<br>مع الإسطبل العادي، يمكنك تدريب جيوشك ضعف السرعة في قرية واحدة');

tz_def('CITYWALL', 'سور المدينة');
tz_def('DEFENCE_NOW', 'مكافأة الدفاع الآن:');
tz_def('DEFENCE_LEVEL', 'مكافأة الدفاع عند المستوى');
tz_def('CITYWALL_DESC', 'يمنح جيوشك مكافأة دفاعية (((1.03 ^ المستوى) * 100)% + 10) نقطة دفاع لكل مستوى إلى القيمة الدفاعية الأساسية للقرية. كلما ارتفع مستوى السور زادت مكافأة الدفاع لجيوشك.<br>خاص بقبيلة: الرومان فقط');

tz_def('EARTHWALL', 'السور الترابي');
tz_def('EARTHWALL_DESC', 'يمنح جيوشك مكافأة دفاعية (((1.02 ^ المستوى) * 100)% + 6) نقطة دفاع لكل مستوى إلى القيمة الدفاعية الأساسية للقرية. السور الترابي بمستوى أعلى يمنح جيوشك مكافأة دفاع أعلى.<br>خاص بقبيلة: التوتون فقط');

tz_def('PALISADE', 'السياج الخشبي');
tz_def('PALISADE_DESC', 'يمنح جيوشك مكافأة دفاعية (((1.025 ^ المستوى) * 100)% + 8) نقطة دفاع لكل مستوى إلى القيمة الدفاعية الأساسية للقرية. السياج بمستوى أعلى يمنح جيوشك مكافأة دفاع أعلى.<br>خاص بقبيلة: الغال فقط');

tz_def('STONEMASON', 'نُزل الحجّار');
tz_def('CURRENT_STABILITY', 'مكافأة المتانة الحالية:');
tz_def('STABILITY_LEVEL', 'مكافأة المتانة عند المستوى');
tz_def('STONEMASON_DESC', 'الحجّار خبير في نحت الحجر. كلما زاد مستوى نُزل الحجّار، زادت متانة مباني قريتك. كل مستوى يزيد المتانة بنسبة 10% حتى أقصى متانة 200% لمبانيك.<br>يمكن بناء هذا المبنى فقط في عاصمة الحساب');

tz_def('BREWERY', 'المخمرة');
tz_def('CURRENT_BONUS', 'المكافأة الحالية:');
tz_def('WATERWORKS_HINT', '(معزَّزة بمرافق المياه)');
tz_def('WATERWORKS_AFFECTED', 'الواحات المضمومة تستفيد من هذه المكافأة.');
tz_def('OASIS_EFFECTIVE_BONUS', 'مكافأة الواحة الفعلية:');
tz_def('BONUS_LEVEL', 'المكافأة عند المستوى');
tz_def('BREWERY_DESC', 'يُخمَّر هنا شراب المياد اللذيذ. تجعل المشروبات جنودك أكثر شجاعة وقوة عند مهاجمة الآخرين (1% لكل مستوى من المخمرة). للأسف تنخفض قوة إقناع القادة بنسبة 50% ولا يمكن للمنجنيقات إلا إصابة أهداف عشوائية. يمكن بناؤها في العاصمة فقط، لكنها تؤثر على كل قراك. يستمر مهرجان الشراب دائمًا 72 ساعة.<br>خاص بقبيلة: التوتون فقط');
tz_def('MEAD_FESTIVAL', 'مهرجان الشراب');
tz_def('MEAD_FESTIVAL_IN_PROGRESS', 'مهرجان الشراب<br>قيد التنفيذ');
tz_def('MEAD_FESTIVAL_COMMENCE_BREWERY', 'يمكن بدء مهرجان الشراب بمجرد اكتمال المخمرة.');

tz_def('TRAPPER', 'الفخّاخ');
tz_def('CURRENT_TRAPS', 'الحد الأقصى الحالي للفخاخ القابلة للتدريب:');
tz_def('TRAPS_LEVEL', 'الحد الأقصى للفخاخ عند المستوى');
tz_def('TRAPS', 'الفخاخ');
tz_def('TRAP', 'فخ');
tz_def('CURRENT_HAVE', 'تملك حاليًا');
tz_def('WHICH_OCCUPIED', 'منها مشغولة.');
tz_def('TRAINING_COMMENCE_TRAPPER', 'يمكن بدء التدريب عند اكتمال الفخّاخ.');
tz_def('TRAPPER_DESC', 'يحمي الفخّاخ قريتك بفخاخ مموهة جيدًا. هذا يعني أن الأعداء غير الحذرين يمكن حبسهم ولن يستطيعوا إيذاء قريتك بعد الآن.<br>لا يمكن تحرير الجيوش المحبوسة بغارة. إذا حرر مالك الفخاخ الأسرى، تُصلَح كل الفخاخ تلقائيًا.<br>خاص بقبيلة: الغال فقط');

tz_def("HEROSMANSION", "منزل البطل");
tz_def('HERO_READY', 'سيكون البطل جاهزًا خلال ');
tz_def('NAME_CHANGED', 'تم تغيير اسم البطل');
tz_def('NOT_UNITS', 'وحدات غير متاحة');
tz_def('NOT', 'ليس ');
tz_def('TRAIN_HERO', 'تدريب بطل جديد');
tz_def('REVIVE', 'إحياء');
tz_def('OASES', 'الواحات');
tz_def('DELETE', 'حذف');
tz_def('RESOURCES', 'الموارد');
tz_def('OFFENCE', 'الهجوم');
tz_def('DEFENCE', 'الدفاع');
tz_def('OFF_BONUS', 'مكافأة الهجوم');
tz_def('DEF_BONUS', 'مكافأة الدفاع');
tz_def('REGENERATION', 'التجدد');
tz_def('DAY', 'يوم');
tz_def('EXPERIENCE', 'الخبرة');
tz_def('YOU_CAN', 'يمكنك ');
tz_def('RESET', 'إعادة تعيين');
tz_def('YOUR_POINT_UNTIL', ' نقاطك حتى تصبح بمستوى ');
tz_def('OR_LOWER', ' أو أقل!');
tz_def('YOUR_HERO_HAS', 'يملك بطلك ');
tz_def('OF_HIT_POINTS', 'من نقاط حياته');
tz_def('ERROR_NAME_SHORT', 'خطأ: الاسم قصير جدًا');
tz_def('HEROSMANSION_DESC', 'منزل البطل هو بيت بطلك المجيد.<br>عند مستويات المبنى 10 و15 و20، يمكنك استخدام بطلك لضم واحة غير مأهولة لقريتك، واحدة عند كل من هذه المستويات على التوالي. حسب الواحة، ستحصل على زيادة إنتاج لنوع معين من الموارد (أو حتى موردين من بعض الواحات)');

tz_def('GREATWAREHOUSE', 'المخزن الكبير');
tz_def('GREATWAREHOUSE_DESC', 'سعة المخزن الكبير 3 أضعاف المخزن العادي.<br>لا يمكن بناء هذا المبنى إلا في قرى عجيبة الدنيا أو بتحفة تترية خاصة');

tz_def('GREATGRANARY', 'الصومعة الكبرى');
tz_def('GREATGRANARY_DESC', 'سعة الصومعة الكبرى 3 أضعاف الصومعة العادية.<br>لا يمكن بناء هذا المبنى إلا في قرى عجيبة الدنيا أو بتحفة تترية خاصة');

tz_def('WONDER', 'عجيبة الدنيا');
tz_def('WORLD_WONDER', 'عجيبة الدنيا');
tz_def('WONDER_DESC', 'عجيبة الدنيا (تُعرف أيضًا اختصارًا WW) مذهلة كما يبدو اسمها. كل مستوى يكلف كمًا هائلاً من الموارد. يكاد يكون مستحيلاً على لاعب واحد بناء عجيبة دنيا بمفرده. السبب أنك لا تحتاج فقط لموارد ضخمة، بل أيضًا جيوش لحماية مبناك الثمين.<br>لبناء عجيبة الدنيا تحتاج مخططًا للبناء القديم. يمكنك الحصول عليه بمهاجمة قرية تترية ببطلك. تحتاج خزانة فارغة بمستوى 10 وأن ينجو بطلك. بهذا المخطط ومستوى مرتفع جدًا من الموارد، يمكنك بدء عجيبة الدنيا.<br>عند وصولها للمستوى 50، ستحتاج شخصًا آخر في تحالفك يملك مخططًا ثانيًا نشطًا. لا يمكنك فعل ذلك بمفردك.<br>عند إتمام عجيبة الدنيا للمستوى 100، تفوز بخادم نوفاتيرا وينتهي عالم اللعبة.<br>عند الانتهاء، ستظهر رسالة تخبر من فاز مع الإحصائيات. لن يمكنك البناء بعد ذلك، لكن يمكنك مراسلة اللاعبين حتى إعادة تشغيل الخادم');
tz_def('WORLD_WONDER_CHANGE_NAME', 'تحتاج عجيبة دنيا بمستوى 1 على الأقل لتتمكن من تغيير اسمها');
tz_def('WORLD_WONDER_NAME', 'اسم عجيبة الدنيا');
tz_def('WORLD_WONDER_NOTCHANGE_NAME', 'لا يمكنك تغيير اسم عجيبة الدنيا بعد المستوى 10');
tz_def('WORLD_WONDER_NAME_CHANGED', 'تم تغيير الاسم');

tz_def('HORSEDRINKING', 'حوض شرب الخيول');
tz_def('HORSEDRINKING_DESC', 'يقلل وقت تدريب الفرسان وإعاشتهم. يمكن بناؤه أيضًا في قرى عجيبة الدنيا الرومانية.<br>يسرّع وقت تدريب وحدات الفرسان بنسبة 1% لكل مستوى ويقلل استهلاك المحاصيل لبعض الوحدات حسب المستوى.<br>خاص بقبيلة: الرومان فقط');

tz_def('GREATWORKSHOP', 'الورشة الكبرى');
tz_def('TRAINING_COMMENCE_GREATWORKSHOP', 'يمكن بدء التدريب عند اكتمال الورشة الكبرى.');
tz_def('GREATWORKSHOP_DESC', 'تتيح لك الورشة الكبرى بناء ورشة ثانية في نفس القرية، لكن المنجنيقات والكباش تكلف 3 أضعاف السعر الأصلي.<br>مع الورشة العادية، يمكنك تدريب جيوشك ضعف السرعة في قرية واحدة');

tz_def('STONEWALL', 'السور الحجري');
tz_def('STONEWALL_DESC', 'يحمي السور الحجري قريتك من هجمات اللاعبين الآخرين. بناؤه الصلب يمنح مكافأة دفاع عالية.<br>خاص بقبيلة: المصريون فقط');
tz_def('MAKESHIFTWALL', 'السور المؤقت');
tz_def('MAKESHIFTWALL_DESC', 'يوفر السور المؤقت حماية أساسية لقريتك. رخيص وسريع البناء، لكنه يمنح مكافأة دفاع صغيرة فقط.<br>خاص بقبيلة: الهون فقط');
tz_def('COMMANDCENTER', 'مركز القيادة');
tz_def('COMMANDCENTER_TRAIN_DESC', 'تحتاج مستوى 10 على الأقل لتدريب المستوطنين والزعماء في مركز القيادة.');
tz_def('COMMANDCENTER_CULTURE_DESC', 'تحدد نقاط الثقافة عدد القرى التي يمكنك تأسيسها أو غزوها.');
tz_def('COMMANDCENTER_LOYALTY_DESC', 'يحمي مركز القيادة القرية من الزعماء الأعداء. الولاء الحالي:');
tz_def('COMMANDCENTER_DESC', 'مركز القيادة هو مقعد السلطة في قرية هونية. يتيح لك تدريب المستوطنين والزعماء والتحكم بتوسعك دون الحاجة لمقر أو قصر.<br>خاص بقبيلة: الهون فقط');
tz_def('WATERWORKS', 'مرافق المياه');
tz_def('WATERWORKS_DESC', 'تزيد مرافق المياه المكافأة الممنوحة من الواحات المضمومة لهذه القرية بنسبة 5% لكل مستوى.<br>خاص بقبيلة: المصريون فقط');
tz_def('HOSPITAL', 'المستشفى');
tz_def('HOSPITAL_DESC', 'يعتني المستشفى بجيوشك الجريحة. يمكن شفاء جزء من الوحدات المفقودة أثناء الدفاع أو الهجوم هنا بدلاً من فقدانها للأبد. المستويات الأعلى تقلل زمن الشفاء.');
tz_def('DEFENSIVEWALL', 'السور الدفاعي');
tz_def('DEFENSIVEWALL_DESC', 'يحمي السور الدفاعي قريتك من هجمات اللاعبين الآخرين. مبني على تقليد التحصينات الإسبرطية العظيمة، يمنح مكافأة دفاع قوية.<br>خاص بقبيلة: الإسبرطيون فقط');
tz_def('BIGHOSPITAL', 'المستشفى الكبير');
tz_def('BIGHOSPITAL_DESC', 'المستشفى الكبير نسخة أكبر من المستشفى العادي، ويتيح شفاء عدد أكبر من جيوشك الجريحة بعد المعركة. المستويات الأعلى تقلل زمن الشفاء.<br>خاص بقبيلة: الإسبرطيون والفايكنج فقط');
tz_def('BARRICADE', 'المتراس');
tz_def('HEALING_TIME_NOW', 'زمن الشفاء الآن');
tz_def('WOUNDED_TROOPS', 'الجيوش الجريحة');
tz_def('NO_WOUNDED', 'لا توجد جيوش جريحة في المستشفى.');
tz_def('HEAL_BUTTON', 'شفاء');
tz_def('HEAL_COST_HINT', 'يكلف الشفاء 50% من تكلفة تدريب الوحدة.');
tz_def('HEALING_IN_PROGRESS', 'الشفاء جارٍ');
tz_def('HEALING_TIME_LEVEL', 'زمن الشفاء عند المستوى');
tz_def('BARRICADE_DESC', 'يحمي المتراس قريتك من هجمات اللاعبين الآخرين. بناؤه الخشبي المتراص يمنح مكافأة دفاع متينة.<br>خاص بقبيلة: الفايكنج فقط');

tz_def('BUILDING_MAX_LEVEL_UNDER', 'المبنى عند أقصى مستوى قيد الإنشاء');
tz_def('BUILDING_BEING_DEMOLISHED', 'المبنى قيد الهدم حاليًا');
tz_def('COSTS_UPGRADING_LEVEL', 'تكلفة</b> الترقية إلى المستوى');
tz_def('WORKERS_ALREADY_WORK', 'العمال يعملون بالفعل.');
tz_def('CONSTRUCTING_MASTER_BUILDER', 'يجري البناء بواسطة كبير البنائين ');
tz_def('COSTS', 'التكلفة');
tz_def('WORKERS_ALREADY_WORK_WAITING', 'العمال يعملون بالفعل. (حلقة انتظار)');
tz_def('ENOUGH_FOOD_EXPAND_CROPLAND', 'غذاء غير كافٍ. وسّع حقول المحاصيل.');
tz_def('UPGRADE_WAREHOUSE', 'ترقية المخزن');
tz_def('UPGRADE_GRANARY', 'ترقية الصومعة');
tz_def('YOUR_CROP_NEGATIVE', 'إنتاج محاصيلك سالب، لن تحصل أبدًا على الموارد المطلوبة.');
tz_def('UPGRADE_LEVEL', 'ترقية إلى المستوى ');
tz_def('WAITING', '(حلقة انتظار)');
tz_def('NEED_WWCONSTRUCTION_PLAN', 'تحتاج مخطط بناء عجيبة الدنيا');
tz_def('NEED_MORE_WWCONSTRUCTION_PLAN', 'تحتاج المزيد من مخططات بناء عجيبة الدنيا');
tz_def('CONSTRUCT_NEW_BUILDING', 'بناء مبنى جديد');
tz_def('SHOWSOON_AVAILABLE_BUILDINGS', 'عرض المباني المتاحة قريبًا');
tz_def('HIDESOON_AVAILABLE_BUILDINGS', 'إخفاء المباني المتاحة قريبًا');

// gold plus
tz_def('GOLD_SHOP', 'متجر الذهب');
tz_def('PACKAGE_A', 'الحزمة أ');
tz_def('PACKAGE_B', 'الحزمة ب');
tz_def('PACKAGE_C', 'الحزمة ج');
tz_def('PACKAGE_D', 'الحزمة د');
tz_def('PACKAGE_E', 'الحزمة هـ');
tz_def('PAYMENT_METHOD', 'طريقة الدفع');
tz_def('PACKAGES_NOT_REFUND', 'لا يمكن استرداد أي من الحزم');
tz_def('PLUS_FUNC', 'ميزة بلس');
tz_def('REMAINING', 'المتبقي');
tz_def('MINS', 'دقيقة');
tz_def('ACTIVATE', 'تفعيل');
tz_def('TOO_LITTLE_GOLD', 'ذهب غير كافٍ');
tz_def('GOLD_ON', 'مفعّل على');
tz_def('PLUS_END', 'انتهت ميزة البلس لديك');
tz_def('NPC', 'التاجر الآلي');
tz_def('NO_GOLD', 'لا تملك ذهبًا حاليًا');
tz_def('GOLD_CLUB', 'نادي الذهب');
tz_def('NOW', 'الآن');
tz_def('NPC_TRADE_GOLD', 'التبادل مع التاجر الآلي');
tz_def('COMPLETE_CONSTRUCTION_R_GOLD', 'إنهاء أوامر البناء والأبحاث في هذه القرية الآن (لا يعمل مع القصر والمقر)');
tz_def('FOR_GAME_SERVER', 'الجولة الكاملة للخادم');
tz_def('HAVE_NO_INVITED', 'لم تُحضر أي لاعبين جدد بعد');
tz_def('INVITE_FRIENDS_GOLD', 'ادعُ أصدقاءك واحصل على ذهب مجاني');
tz_def('NEED_MORE_GOLD', 'تحتاج إلى المزيد من الذهب');
tz_def('ADD_PLUS_FAIL', 'فشلت محاولة تفعيل البلس');
tz_def('ADD_BONUS_LUMBER_FAIL', 'فشلت محاولة مكافأة الخشب');
tz_def('ADD_BONUS_CLAY_FAIL', 'فشلت محاولة مكافأة الطين');
tz_def('ADD_BONUS_IRON_FAIL', 'فشلت محاولة مكافأة الحديد');
tz_def('ADD_BONUS_CROP_FAIL', 'فشلت محاولة مكافأة المحصول');
tz_def('SELECT_GOLD_OPTION', 'الرجاء اختيار الخيار الذي ترغب في تفعيله أو تمديده');
tz_def('GET_NOW', 'احصل عليه الآن');
tz_def('BUY_NOW', 'اشترِ الآن');
tz_def('SELECT_REWARD', 'اختر المكافأة');
tz_def('VIP_ACCOUNT', 'حساب VIP');
tz_def('USER_NOT_EXISTS', 'اسم الحساب الذي أدخلته غير موجود');
tz_def('STATUS_UPDATED', 'تم تحديث حالتك');

//LANGUAGE NAMES (used in profile / admin dropdowns)
tz_def('TZ_ARABIC', 'العربية');
tz_def('TZ_ENGLISH', 'الإنجليزية');
tz_def('TZ_FRENCH', 'الفرنسية');
tz_def('TZ_ITALIAN', 'الإيطالية');
tz_def('TZ_SPANISH', 'الإسبانية');
tz_def('TZ_ROMANIAN', 'الرومانية');
tz_def('TZ_CHINESE', 'الصينية');
tz_def('TZ_GAME_LANGUAGE', 'لغة اللعبة');
tz_def('TZ_LANGUAGE_SETTINGS', 'إعدادات اللغة');

// profile
tz_def('PREFERENCES', 'التفضيلات');
tz_def('VACATION', 'وضع الإجازة');
tz_def('ACTIVATE_VACATION', 'هل تريد تفعيل وضع الإجازة');
tz_def('GRAPH_PACK', 'الحزمة الرسومية');
tz_def('PLAYER_PROFILE', 'الملف الشخصي للاعب');
tz_def('CHANGE_PASSWORD', 'تغيير كلمة المرور');
tz_def('OLD_PASSWORD', 'كلمة المرور القديمة');
tz_def('NEW_PASSWORD', 'كلمة المرور الجديدة');
tz_def('CHANGE_EMAIL', 'تغيير البريد الإلكتروني');
tz_def('CHANGE_EMAIL2', 'الرجاء إدخال بريدك الإلكتروني القديم والجديد. ستصلك رسالة تحقق على كلا البريدين يجب إدخالها هنا');
tz_def('CURRENT_EMAIL', 'البريد الإلكتروني الحالي');
tz_def('OLD_EMAIL', 'البريد الإلكتروني القديم');
tz_def('NEW_EMAIL', 'البريد الإلكتروني الجديد');
tz_def('ACCOUNT_SITTERS', 'الوكلاء');
tz_def('ACCOUNT_SITTERS2', 'يمكن للوكيل الدخول إلى حسابك باستخدام اسمك وكلمة مروره الخاصة. يمكنك إضافة وكيلين كحد أقصى');
tz_def('SITTER_NAME', 'اسم الوكيل');
tz_def('NO_SITTERS', 'ليس لديك وكلاء');
tz_def('RM_SITTER', 'إزالة الوكيل');
tz_def('YOU_ARE_SITTER', 'تمت إضافتك كوكيل في الحسابات التالية. يمكنك إلغاء ذلك بالنقر على علامة X الحمراء');
tz_def('DELETE_ACCOUNT', 'حذف الحساب');
tz_def('DELETE_ACCOUNT2', 'يمكنك حذف حسابك من هنا. بعد بدء عملية الحذف ستستغرق ثلاثة أيام لإتمامها. يمكنك إلغاء العملية خلال أول 24 ساعة');
tz_def('YES', 'نعم');
tz_def('NO', 'لا');
tz_def('CONFIRM_W_PASS', 'التأكيد بكلمة المرور');
tz_def('MEDALS', 'الأوسمة');
tz_def('PLAYER_HAS', 'لدى هذا اللاعب'); // bird 1
tz_def('HOURS_OF_BG_PROT', 'ساعات متبقية من حماية المبتدئين'); // bird 1
tz_def('PLAYER_WAS_REG_ON', 'سجّل هذا اللاعب حسابه بتاريخ'); // bird 2
tz_def('NATARS_ACC', 'حساب رسمي للتتار'); // natars
tz_def('WW_V_M', 'قرية عجيبة الدنيا الرسمية'); // WW Village
tz_def('ROMAN_T_M', 'الرومان: بفضل مستواهم العالي من التطور الاجتماعي والتقني، يُعد الرومان أساتذة في البناء وتنسيقه. كما أن قواتهم من نخبة قوات نوفاتيرا. متوازنون جدًا ومفيدون في الهجوم والدفاع'); // roman tribe medal
tz_def('TEUTON_T_M', 'التوتون: أكثر القبائل عدوانية. قواتهم مشهورة ومخيفة لغضبها وجنونها أثناء الهجوم. يتحركون كقطيع نهب لا يخشى الموت'); // teuton tribe medal
tz_def('GAUL_T_M', 'الغال: أكثر القبائل الثلاث سلمًا في نوفاتيرا. قواتهم مدربة على دفاع ممتاز، لكن قدرتها الهجومية لا تزال تنافس القبيلتين الأخريين. الغال فرسان بالفطرة وخيولهم مشهورة بسرعتها، ما يمكّن فرسانهم من ضرب العدو في أضعف نقاطه والتعامل معه بسرعة'); // gaul tribe medal
tz_def('ADMIN_M', 'مسؤول الخادم الرسمي');
tz_def('MH_M', 'صياد المتعددين العالمي الرسمي للخادم');
tz_def('MH_M2', 'صياد المتعددين هو منصب رسمي في نوفاتيرا يُستخدم أساسًا لفرض قواعد نوفاتيرا داخل الخادم. يستخدم جميع صيادي المتعددين حسابًا باسم Multihunter وقريته الوحيدة تقع في (0|0). لا يجوز لصياد المتعددين اللعب على الخادم الذي يشغل فيه هذا المنصب، لكن يمكنه اللعب بنشاط على خوادم أخرى');
tz_def('NATURE_M2', 'قوات الطبيعة هي الحيوانات التي تعيش في الواحات غير المأهولة. يمكنك استخدام محاكي القتال لمعرفة ما إذا كانت لديك قوات كافية لهزيمة حيوانات الواحة التي تريد غزوها، لكن تذكّر أنه يمكنك فقط نهب الواحات. ضع في اعتبارك أن جميع الحيوانات الأعلى من الدب يمكنها قتل أقوى قوة معاصرة من نوفاتيرا في قتال فردي');
tz_def('TASKMASTER_M', 'حساب مدير المهام');
tz_def('VETERAN_P', 'لاعب مخضرم');
tz_def('VETERAN_3_M', 'وسام يُمنح للعب 3 سنوات في نوفاتيرا');
tz_def('VETERAN_5_M', 'وسام يُمنح للعب 5 سنوات في نوفاتيرا');
tz_def('VETERAN_10_M', 'وسام يُمنح للعب 10 سنوات في نوفاتيرا');
tz_def('ATT_W_M', 'مهاجمو الأسبوع');
tz_def('DEF_W_M', 'مدافعو الأسبوع');
tz_def('POP_W_M', 'متسلقو السكان للأسبوع');
tz_def('ROB_W_M', 'ناهبو الأسبوع');
tz_def('CLIMB_W_M', 'متسلقو الترتيب للأسبوع');
tz_def('ATT_DEF_10_W_M', 'الحصول على هذا الوسام يعني أنك كنت ضمن أفضل 10 مهاجمين ومدافعين للأسبوع');
tz_def('ATT_3_W_M', 'الحصول على هذا الوسام يعني أنك كنت ضمن أفضل 3 مهاجمين للأسبوع');
tz_def('DEF_3_W_M', 'الحصول على هذا الوسام يعني أنك كنت ضمن أفضل 3 مدافعين للأسبوع');
tz_def('POP_3_W_M', 'الحصول على هذا الوسام يعني أنك كنت ضمن أفضل 3 متسلقي سكان للأسبوع');
tz_def('ROB_3_W_M', 'الحصول على هذا الوسام يعني أنك كنت ضمن أفضل 3 ناهبين للأسبوع');
tz_def('CLIMB_3_W_M', 'الحصول على هذا الوسام يعني أنك كنت ضمن أفضل 3 متسلقي ترتيب للأسبوع');
tz_def('ATT_10_W_M', 'الحصول على هذا الوسام يعني أنك كنت ضمن أفضل 10 مهاجمين للأسبوع');
tz_def('DEF_10_W_M', 'الحصول على هذا الوسام يعني أنك كنت ضمن أفضل 10 مدافعين للأسبوع');
tz_def('POP_10_W_M', 'الحصول على هذا الوسام يعني أنك كنت ضمن أفضل 10 متسلقي سكان للأسبوع');
tz_def('ROB_10_W_M', 'الحصول على هذا الوسام يعني أنك كنت ضمن أفضل 10 ناهبين للأسبوع');
tz_def('CLIMB_10_W_M', 'الحصول على هذا الوسام يعني أنك كنت ضمن أفضل 10 متسلقي ترتيب للأسبوع');
tz_def('RECEIVED_IN_W', 'تم الحصول عليه في الأسبوع');
tz_def('POINTS_M', 'النقاط');
tz_def('RANKS', 'الرتب');
tz_def('WEEK', 'الأسبوع');
tz_def('CATEGORY', 'الفئة');
tz_def('RANK', 'الرتبة');
tz_def('BB_CODE', 'كود BB');
tz_def('IN_ROW', 'على التوالي');
tz_def('ADMIN1', 'مسؤول');
tz_def('MULTIH1', 'صياد متعددين');
tz_def('PLAYER_ADMIN', 'هذا اللاعب مسؤول');
tz_def('PLAYER_MH', 'هذا اللاعب صياد متعددين');
tz_def('PLAYER_BANNED', 'هذا اللاعب محظور');
tz_def('PLAYER_VACATION', 'هذا اللاعب في وضع الإجازة');
if(!defined('BANNED')) tz_def('BANNED', 'محظور');
tz_def('GENDER', 'الجنس');
tz_def('GENDER0', 'غير محدد');
tz_def('MALE0', 'ذ');
tz_def('MALE', 'ذكر');
tz_def('FEMALE0', 'أ');
tz_def('FEMALE', 'أنثى');
tz_def('LOCATION', 'الموقع');
tz_def('DIRECT_LINKS', 'روابط مباشرة');
tz_def('NUMBER0', 'لا');
tz_def('LINK_NAME', 'اسم الرابط');
tz_def('LINK_TARGET', 'وجهة الرابط');
tz_def('TZ_LINK_GENERATOR', 'مولّد روابط اللعبة');
tz_def('TZ_LINK_GENERATOR_DESC', 'ينشئ رابطًا حسب نوع المبنى بدلًا من موقع الخانة، فيعمل في أي قرية بغض النظر عن مكان بناء المبنى فيها. انسخ النتيجة أدناه إلى حقل وجهة الرابط.');
tz_def('TZ_TAB_OPTIONAL', 'التبويب (اختياري)');
tz_def('TZ_GENERATED_LINK', 'الرابط المُنشأ');
tz_def('AUTO_COMPL', 'الإكمال التلقائي');
tz_def('AUTO_COMPL2', 'يُستخدم لنقطة التجمع والسوق');
tz_def('OWN_VILLAGES', 'قراك الخاصة');
tz_def('VILLAGES_NEAR', 'قرى المحيط');
tz_def('VILLAGES_ALLI_PLAYERS', 'قرى لاعبي التحالف');
tz_def('REPORT_FILTER', 'فلتر التقارير');
tz_def('NO_REPORTS_TO_OWN', 'لا توجد تقارير لتحويلات إلى قراك الخاصة');
tz_def('NO_REPORTS_TO_OTH', 'لا توجد تقارير لتحويلات إلى قرى أخرى');
tz_def('NO_REPORTS_FROM_OTH', 'لا توجد تقارير لتحويلات من قرى أخرى');
tz_def('CHANGE_PROFILE', 'تعديل الملف الشخصي');
tz_def('WRITE_MESSAGE', 'كتابة رسالة');
tz_def('REPORT_PLAYER', 'الإبلاغ عن اللاعب');
tz_def('ARTEFACT1', 'قطعة أثرية');
tz_def('WoW1', 'عجيبة الدنيا');
tz_def('VILLAGE_NAME', 'اسم القرية');
tz_def('BDAY', 'عيد الميلاد');
tz_def('CONDITIONS', 'الشروط');
tz_def('TIME_PREF', 'تفضيلات الوقت');
tz_def('TIME_ZONES_DESC', 'من هنا يمكنك تغيير الوقت المعروض في نوفاتيرا ليطابق منطقتك الزمنية');
tz_def('TIME_ZONE_L1', 'أوروبا');
tz_def('TIME_ZONE_L2', 'المملكة المتحدة');
tz_def('TIME_ZONE_L3', 'تركيا');
tz_def('TIME_ZONE_L4', 'آسيا/كولكاتا');
tz_def('TIME_ZONE_L5', 'آسيا/بانكوك');
tz_def('TIME_ZONE_L6', 'أمريكا/نيويورك');
tz_def('TIME_ZONE_L7', 'أمريكا/شيكاغو');
tz_def('TIME_ZONE_L8', 'نيوزيلندا');
tz_def('MONTH1', 'يناير');
tz_def('MONTH2', 'فبراير');
tz_def('MONTH3', 'مارس');
tz_def('MONTH4', 'أبريل');
tz_def('MONTH5', 'مايو');
tz_def('MONTH6', 'يونيو');
tz_def('MONTH7', 'يوليو');
tz_def('MONTH8', 'أغسطس');
tz_def('MONTH9', 'سبتمبر');
tz_def('MONTH10', 'أكتوبر');
tz_def('MONTH11', 'نوفمبر');
tz_def('MONTH12', 'ديسمبر');

//artefact
tz_def('ARCHITECTS_DESC', 'جميع المباني ضمن نطاق التأثير تصبح أقوى. هذا يعني أنك ستحتاج مزيدًا من المنجنيقات لإلحاق الضرر بالمباني المحمية بقوة هذه القطعة الأثرية.');
tz_def('ARCHITECTS_SMALL', 'سر المعماري الصغير');
tz_def('ARCHITECTS_SMALLVILLAGE', 'إزميل الألماس');
tz_def('ARCHITECTS_LARGE', 'سر المعماري العظيم');
tz_def('ARCHITECTS_LARGEVILLAGE', 'مطرقة الرخام العملاقة');
tz_def('ARCHITECTS_UNIQUE', 'سر المعماري الفريد');
tz_def('ARCHITECTS_UNIQUEVILLAGE', 'مخطوطات هيمون');
tz_def('HASTE_DESC', 'جميع القوات ضمن نطاق التأثير تتحرك بسرعة أكبر.');
tz_def('HASTE_SMALL', 'حذاء العملاق الصغير');
tz_def('HASTE_SMALLVILLAGE', 'حدوة الأوبال');
tz_def('HASTE_LARGE', 'حذاء العملاق الكبير');
tz_def('HASTE_LARGEVILLAGE', 'العربة الذهبية');
tz_def('HASTE_UNIQUE', 'حذاء العملاق الفريد');
tz_def('HASTE_UNIQUEVILLAGE', 'صنادل فيديبيدس');
tz_def('EYESIGHT_DESC', 'جميع الجواسيس (الكشافة، المستطلعون، وفرسان الإكويتس) تزداد قدرتهم على التجسس. بالإضافة إلى ذلك، تتيح لك جميع نسخ هذه القطعة الأثرية رؤية نوع القوات القادمة دون معرفة عددها.');
tz_def('EYESIGHT_SMALL', 'عين النسر الصغيرة');
tz_def('EYESIGHT_SMALLVILLAGE', 'حكاية الفأر');
tz_def('EYESIGHT_LARGE', 'عين النسر الكبيرة');
tz_def('EYESIGHT_LARGEVILLAGE', 'رسالة الجنرال');
tz_def('EYESIGHT_UNIQUE', 'عين النسر الفريدة');
tz_def('EYESIGHT_UNIQUEVILLAGE', 'يوميات صن تزو');
tz_def('DIET_DESC', 'جميع القوات ضمن نطاق القطعة الأثرية تستهلك كمية أقل من القمح، ما يتيح الحفاظ على جيش أكبر.');
tz_def('DIET_SMALL', 'تحكم غذائي طفيف');
tz_def('DIET_SMALLVILLAGE', 'الصينية الفضية');
tz_def('DIET_LARGE', 'تحكم غذائي كبير');
tz_def('DIET_LARGEVILLAGE', 'قوس الصيد المقدس');
tz_def('DIET_UNIQUE', 'تحكم غذائي فريد');
tz_def('DIET_UNIQUEVILLAGE', 'كأس الملك آرثر');
tz_def('ACADEMIC_DESC', 'تُبنى القوات بنسبة أسرع معينة ضمن نطاق القطعة الأثرية.');
tz_def('ACADEMIC_SMALL', 'موهبة المدرّب الطفيفة');
tz_def('ACADEMIC_SMALLVILLAGE', 'قسم الجندي المكتوب');
tz_def('ACADEMIC_LARGE', 'موهبة المدرّب العظيمة');
tz_def('ACADEMIC_LARGEVILLAGE', 'إعلان الحرب');
tz_def('ACADEMIC_UNIQUE', 'موهبة المدرّب الفريدة');
tz_def('ACADEMIC_UNIQUEVILLAGE', 'مذكرات الإسكندر الأكبر');
tz_def('STORAGE_DESC', 'بمخطط البناء هذا يمكنك بناء المخزن العظيم أو الصومعة العظيمة في القرية التي تحوي القطعة الأثرية، أو في الحساب كاملاً حسب نوع القطعة. ما دمت تملك تلك القطعة الأثرية يمكنك بناء وتوسيع هذه المباني.');
tz_def('STORAGE_SMALL', 'مخطط تخزين طفيف');
tz_def('STORAGE_SMALLVILLAGE', 'رسم البنّاء');
tz_def('STORAGE_LARGE', 'مخطط تخزين عظيم');
tz_def('STORAGE_LARGEVILLAGE', 'لوح بابلي');
tz_def('CONFUSION_DESC', 'تزداد سعة المخبأ بمقدار معين لكل نوع من القطع الأثرية. يمكن للمنجنيقات أن تصيب فقط أهدافًا عشوائية في القرى الواقعة ضمن نطاق قوة هذه القطعة الأثرية. الاستثناءات هي عجيبة الدنيا التي يمكن استهدافها دائمًا، وغرفة الكنوز التي يمكن استهدافها دائمًا إلا مع القطعة الأثرية الفريدة. عند استهداف حقل موارد يمكن إصابة حقول موارد عشوائية فقط، وعند استهداف مبنى يمكن إصابة مبانٍ عشوائية فقط.');
tz_def('CONFUSION_SMALL', 'ارتباك الخصم الطفيف');
tz_def('CONFUSION_SMALLVILLAGE', 'خريطة الكهوف المخفية');
tz_def('CONFUSION_LARGE', 'ارتباك الخصم الكبير');
tz_def('CONFUSION_LARGEVILLAGE', 'الحقيبة بلا قاع');
tz_def('CONFUSION_UNIQUE', 'ارتباك الخصم الفريد');
tz_def('CONFUSION_UNIQUEVILLAGE', 'حصان طروادة');
tz_def('FOOL_DESC', 'كل 24 ساعة تحصل على تأثير عشوائي، إما مكافأة أو عقوبة (كل الاحتمالات ممكنة باستثناء مخططات المخزن العظيم، الصومعة العظيمة، وعجيبة الدنيا). يتغير التأثير والنطاق كل 24 ساعة. القطعة الأثرية الفريدة تأخذ دائمًا مكافآت إيجابية.');
tz_def('FOOL_SMALL', 'قطعة الأحمق الصغيرة');
tz_def('FOOL_SMALLVILLAGE', 'قلادة الأذى');
tz_def('FOOL_UNIQUE', 'قطعة الأحمق الفريدة');
tz_def('FOOL_UNIQUEVILLAGE', 'المخطوطة المحرّمة');
tz_def('WWVILLAGE', 'قرية عجيبة الدنيا');
tz_def('ARTEFACT', '<h1><b>قطع التتار الأثرية</b></h1>

تتردد همسات وشائعات عبر القرى، تحكي أساطير لا يرويها إلا أمهر الرواة. إنها تتحدث عن التتار، أشد محاربي عالم نوفاتيرا رهبة. قتلهم حلم كل بطل وغاية كل مقاتل. لا أحد يعرف كيف نال التتار هذه القوة، ولا كيف أصبح محاربوهم بهذه القسوة. عزمًا منهم على اكتشاف مصدر قوة التتار، أرسل المقاتلون مجموعة من الجواسيس النخبة للتجسس عليهم. لم تمضِ ساعات طويلة حتى عادوا بأعين مليئة بالخوف يوازنون بين نظريات خيالية: يبدو أن قوتهم الخارقة تأتي من أغراض غامضة يسمونها القطع الأثرية، سرقوها من أجدادنا. حاول أن تسلبهم إياها، وستتمكن من التحكم بقوتها.

<img src="/img/x.gif" class="ArtefactsAnnouncement">

حان وقت المطالبة بالقطع الأثرية. تعاون مع تحالفك وأرسل محاربيك للحصول على هذه الأغراض المنشودة. لكن التتار لن يتخلوا عنها دون قتال... ولا أعداؤك أيضًا. إذا نجحت في استرداد القطع الأثرية وصددت الأعداء، ستتمكن من جني المكافآت. ستصبح مبانيك أقوى بشكل مذهل، وستتحرك قواتك أسرع وتستهلك طعامًا أقل. استولِ على القطع الأثرية، وحقق المجد لإمبراطوريتك وكن أسطورة جديدة لأتباعك.

لسرقة إحدى القطع الأثرية، يجب أن يحدث ما يلي:

1. يجب أن تهاجم القرية (وليس نهبًا!)
2. الفوز بالهجوم
3. تدمير الخزينة
4. يجب أن تكون هناك خزينة فارغة من المستوى 10 للقطع الأثرية الصغيرة، والمستوى 20 للقطع الأثرية الكبيرة، في القرية التي انطلق منها الهجوم
5. وجود بطل ضمن الهجوم

إذا لم يتحقق ذلك، فإن الهجوم التالي على تلك القرية الذي يفوز ببطل وخزينة فارغة سيأخذ القطعة الأثرية.

لبناء عجيبة الدنيا، يجب أن تملك المخطط بنفسك (أنت = مالك قرية عجيبة الدنيا) من المستوى 0 إلى 50، ومن 51 إلى 100 تحتاج مخططًا إضافيًا داخل تحالفك! امتلاك مخططين في حساب قرية عجيبة الدنيا نفسها لن يجدي نفعًا!

مخططات البناء قابلة للغزو فور ظهورها في الخادم.

سيظهر عد تنازلي داخل اللعبة يوضح الوقت الدقيق للإصدار، قبل 5 أيام من الإطلاق. ');

//WW Village Release Message
tz_def('WWVILLAGEMSG', '<h1><b>قرى عجائب الدنيا</b></h1>

مرت أيام لا تُحصى منذ المعارك الأولى فوق أسوار القرى الملعونة التابعة للتتار الرهيبين، وقاتلت جيوش عديدة من الأحرار والإمبراطورية التتارية وماتت أمام أسوار المعاقل العديدة التي حكم منها التتار كل الخليقة يومًا ما. والآن بعد أن هدأ الغبار وحلّ هدوء نسبي، بدأت الجيوش تحصي خسائرها وتجمع موتاها، ورائحة القتال لا تزال عالقة في هواء الليل، رائحة مجزرة لا تُنسى في اتساعها ووحشيتها، لكنها ستُصبح قريبًا صغيرة أمام ما هو آت. كانت أعظم جيوش الأحرار والتتار الرهيبين تحتشد لهجوم متجدد آخر على المعاقل السابقة المرغوبة للإمبراطورية التتارية.
سرعان ما وصل الكشافة يحملون أخبار مشهد مهيب وتذكيرًا مرعبًا: رُصد جيش رهيب بحجم لا يُتصور يحتشد عند نهاية العالم، عاصمة التتار، قوة عظيمة لا تُقهر بحيث يخنق الغبار المتصاعد من مسيرتها كل ضوء، قوة وحشية لا ترحم تسحق كل أمل. أدرك الأحرار أنهم بحاجة للسباق الآن، سباق مع الزمن ومع حشود الإمبراطورية التتارية التي لا تنتهي، لإقامة عجيبة من عجائب الدنيا تعيد السلام للعالم وتقضي على تهديد التتار.
لكن إقامة عجيبة عظيمة كهذه لن تكون مهمة سهلة، فالأمر يتطلب مخططات بناء أُنشئت في الماضي البعيد، مخططات من طبيعة غامضة لدرجة أن أحكم الحكماء لم يعرفوا محتواها أو مكانها.
جاب عشرات الآلاف من الكشافة كل أرجاء الوجود بحثًا عبثًا عن هذه المخططات الغامضة، بحثوا في كل مكان إلا عاصمة التتار الرهيبة، ولم يجدوها. أما اليوم، فقد عادوا بأخبار سارة، عادوا حاملين مواقع المخططات، التي أخفتها جيوش التتار داخل معاقل سرية بُنيت لتكون بعيدة عن أعين البشر.
والآن تبدأ المرحلة الأخيرة، حين تتصادم أعظم جيوش الأحرار والتتار عبر العالم من أجل مصير كل ما يقع تحت السماء. هذه هي الحرب التي ستتردد أصداؤها عبر العصور، هذه حربك، وهنا ستنقش اسمك في التاريخ، هنا ستصبح أسطورة.

<img src="/img/x.gif" class="WWVillagesAnnouncement" title="'.WWVILLAGE.'" alt="'.WWVILLAGE.'">

لغزو إحدى القرى، يجب أن يحدث ما يلي:

1. يجب أن تهاجم القرية (وليس نهبًا!)
2. الفوز بالهجوم
3. تدمير مبنى الإقامة
4. يجب خفض الولاء إلى 0 باستخدام: أصحاب الشيوخ، القائد، رئيس القبيلة
5. يجب أن تملك نقاط ثقافة كافية لغزو القرية

إذا لم يتحقق ذلك، فإن الهجوم التالي على تلك القرية الذي يفوز بأصحاب الشيوخ أو القائد أو رئيس القبيلة وخانات فارغة في الإقامة/القصر سيأخذ القرية.

لبناء عجيبة الدنيا، يجب أن تملك المخطط بنفسك (أنت = مالك قرية عجيبة الدنيا) من المستوى 0 إلى 50، ومن 51 إلى 100 تحتاج مخططًا إضافيًا داخل تحالفك! امتلاك مخططين في حساب قرية عجيبة الدنيا نفسها لن يجدي نفعًا!

مخططات البناء قابلة للغزو فور ظهورها في الخادم.

سيظهر عد تنازلي داخل اللعبة يوضح الوقت الدقيق للإصدار، '.(5 / SPEED).' أيام قبل الإطلاق.');

//Building Plans
tz_def('WILL_SPAWN_IN', 'سيظهر خلال');
tz_def('PLAN', 'مخطط بناء قديم');
tz_def('PLANVILLAGE', 'مخطط بناء عجيبة الدنيا');
tz_def('PLAN_DESC', 'بهذا المخطط القديم ستتمكن من بناء عجيبة الدنيا حتى المستوى 50. للبناء أكثر من ذلك، يجب أن يملك تحالفك مخططين على الأقل.');
tz_def('PLAN_INFO', '<h1><b>مخططات بناء عجيبة الدنيا</b></h1>


منذ أقمار عديدة، فوجئت قبائل نوفاتيرا بعودة التتار غير المتوقعة. كانت هذه القبيلة، التي تفوقت منذ الأزل على الجميع في الحكمة والقوة والمجد، على وشك إزعاج الأحرار من جديد. لذا بذلوا كل جهدهم لتحضير حرب أخيرة ضد التتار والقضاء عليهم إلى الأبد. اعتقد كثيرون أن ما يُسمى "عجائب الدنيا"، وهي بناء أسطوري، هو الحل الوحيد. قيل إن من يُتمّه سيصبح لا يُقهر، وفي النهاية يصبح البُناة حكام وغزاة كل نوفاتيرا المعروفة.

لكن قيل أيضًا إن الأمر يتطلب مخططات بناء لإقامة مثل هذا الصرح. ولهذا، ابتكر المعماريون خططًا ذكية لتخزينها بأمان. وبعد فترة، بدأت تظهر مبانٍ شبيهة بالمعابد في كثير من المدن والحواضر - غرف الكنوز (الخزائن).

للأسف، لم يكن أحد - ولا حتى أحكم الحكماء - يعرف أين يجد هذه المخططات. وكلما حاول الناس تحديد مكانها بجهد أكبر، بدت وكأنها مجرد أساطير.

أما اليوم، فسيُكشف هذا السر الأخير. لن تذهب معاناة الماضي ومساعيه هباءً، إذ نجح اليوم كشافة عدة قبائل في الحصول على مواقع مخططات البناء. محروسة جيدًا من قبل التتار، تكمن مخفية في واحات عديدة منتشرة في أرجاء نوفاتيرا. لن يتمكن من تأمين مثل هذا المخطط وإحضاره سالمًا إلى الديار سوى أشجع الأبطال، ليبدأ البناء.

في النهاية، سنرى ما إذا كانت قبائل نوفاتيرا الحرة قادرة مجددًا على التفوق بالحيلة على التتار والقضاء عليهم نهائيًا. لكن لا تكن غافلًا لتظن أن التتار سيتركونها دون قتال!

<img src="/img/x.gif" class="WWBuildingPlansAnnouncement" title="'.PLAN.'" alt="'.PLAN.'">

لسرقة مجموعة من مخططات البناء من التتار، يجب أن يحدث ما يلي:
- يجب أن تهاجم القرية (وليس نهبًا!)
- يجب أن تفوز بالهجوم
- يجب أن تدمّر غرفة الكنوز (الخزينة)
- يجب أن يكون بطلك ضمن ذلك الهجوم، فهو الوحيد القادر على حمل مخططات البناء
- يجب أن تكون هناك غرفة كنوز فارغة من المستوى 10 في القرية التي انطلق منها الهجوم
ملاحظة: إذا لم تتحقق الشروط أعلاه أثناء الهجوم، فإن الهجوم التالي على تلك القرية والذي يستوفي الشروط سيأخذ مخططات البناء.



لبناء غرفة كنوز (خزينة)، ستحتاج إلى مبنى رئيسي من المستوى 10 ويجب ألا تحتوي القرية على عجيبة دنيا.

لبناء عجيبة دنيا، يجب أن تملك مخططات البناء بنفسك (أنت = مالك قرية عجيبة الدنيا) من المستوى 0 إلى 50، ثم من المستوى 51 إلى 100 ستحتاج مجموعة إضافية من مخططات البناء داخل تحالفك! مجموعتان من مخططات البناء في حساب قرية عجيبة الدنيا نفسها لن تُجديا نفعًا!');

//QUEST
tz_def('Q_CONTINUE', 'تابع إلى المهمة التالية.');
tz_def('Q_REWARD', 'مكافأتك:');
tz_def('Q_BUTN', 'إتمام المهمة');
tz_def('Q0', 'مرحبًا بك في ');
tz_def('Q0_DESC', 'أرى أنك أصبحت زعيمًا لهذه القرية الصغيرة. سأكون مستشارك في الأيام القليلة الأولى ولن أفارق جانبك.');
tz_def('Q0_OPT1', 'إلى المهمة الأولى.');
tz_def('Q0_OPT2', 'استكشف بنفسك.');
tz_def('Q0_OPT3', 'لا ألعب المهام.');

tz_def('Q1', 'المهمة 1: الحطّاب');
tz_def('Q1_DESC', 'توجد أربع غابات خضراء حول قريتك. ابنِ منشرة خشب على إحداها. الخشب مورد مهم لمستوطنتنا الجديدة.');
tz_def('Q1_ORDER', 'الأمر:</p>ابنِ منشرة خشب.');
tz_def('Q1_RESP', 'نعم، بهذه الطريقة تحصل على مزيد من الخشب. لقد ساعدتك قليلاً وأتممت الأمر فورًا.');
tz_def('Q1_REWARD', 'اكتملت منشرة الخشب فورًا.');

tz_def('Q2', 'المهمة 2: المحاصيل');
tz_def('Q2_DESC', 'أصبح رعاياك جائعين بعد العمل طوال اليوم. وسّع حقل محاصيل لتحسين إمداد رعاياك. عد إلى هنا بعد اكتمال المبنى.');
tz_def('Q2_ORDER', 'الأمر:</p>وسّع حقل محاصيل واحد.');
tz_def('Q2_RESP', 'ممتاز جدًا. الآن لدى رعاياك ما يكفي من الطعام مجددًا...');
tz_def('Q2_REWARD', 'مكافأتك:</p>يوم واحد من نوفاتيرا');

tz_def('Q3', 'المهمة 3: اسم قريتك');
tz_def('Q3_DESC', 'بما أنك مبدع يمكنك منح قريتك الاسم المثالي.<br><br>انقر على `الملف الشخصي` في القائمة اليسرى ثم اختر `تعديل الملف الشخصي`...');
tz_def('Q3_ORDER', 'الأمر:</p>غيّر اسم قريتك إلى شيء جميل.');
tz_def('Q3_RESP', 'رائع، اسم عظيم لقريتهم. كان يمكن أن يكون اسم قريتي!...');

tz_def('Q4', 'المهمة 4: لاعبون آخرون');
tz_def('Q4_DESC', 'في '.SERVER_NAME.' تلعب جنبًا إلى جنب مع مليارات اللاعبين الآخرين. انقر على `الإحصائيات` في القائمة العلوية للاطلاع على رتبتك وأدخلها هنا.');
tz_def('Q4_ORDER', 'الأمر:</p>ابحث عن رتبتك في الإحصائيات وأدخلها هنا.');
tz_def('Q4_BUTN', 'إتمام المهمة');
tz_def('Q4_RESP', 'بالضبط! هذه رتبتك.');

tz_def('Q5', 'المهمة 5: أمران للبناء');
tz_def('Q5_DESC', 'ابنِ منجم حديد وحفرة طين. من الحديد والطين لا يمكن أن يكون لديك ما يكفي أبدًا.');
tz_def('Q5_ORDER', 'الأمر:</p><ul><li>وسّع منجم حديد واحد.</li><li>وسّع حفرة طين واحدة.</li></ul>');
tz_def('Q5_RESP', 'كما لاحظت، أوامر البناء تستغرق وقتًا طويلاً نسبيًا. سيستمر عالم '.SERVER_NAME.' في الدوران حتى وأنت غير متصل. حتى بعد بضعة أشهر ستجد أشياء جديدة كثيرة لاكتشافها.<br><br>أفضل شيء هو تفقّد قريتك من حين لآخر وإعطاء رعاياك مهام جديدة.');

tz_def('Q6', 'المهمة 6: الرسائل');
tz_def('Q6_DESC', 'يمكنك التحدث مع لاعبين آخرين باستخدام نظام الرسائل. أرسلت لك رسالة. اقرأها وعد إلى هنا.<br><br>ملاحظة: لا تنسَ، على اليسار التقارير، وعلى اليمين الرسائل.');
tz_def('Q6_ORDER', 'الأمر:</p>اقرأ رسالتك الجديدة.');
tz_def('Q6_RESP', 'استلمتها؟ ممتاز جدًا.<br><br>إليك بعض الذهب. بالذهب يمكنك فعل أشياء عديدة، مثل توسيع في القائمة اليسرى.');
tz_def('Q6_RESP1', '-الحساب أو زيادة إنتاج مواردك. للقيام بذلك انقر على ');
tz_def('Q6_RESP2', 'في القائمة اليسرى.');
tz_def('Q6_SUBJECT', 'رسالة من مدير المهام');
tz_def('Q6_MESSAGE', 'نحيطك علمًا بأن مكافأة لطيفة تنتظرك عند مدير المهام.<br><br>ملاحظة: تم إنشاء هذه الرسالة تلقائيًا. لا داعي للرد.');

tz_def('Q7', 'المهمة 7: واحد من كل نوع!');
tz_def('Q7_DESC', 'الآن يجب أن نزيد إنتاج مواردك قليلاً. ابنِ منشرة خشب إضافية، وحفرة طين، ومنجم حديد، وحقل محاصيل إلى المستوى 1.');
tz_def('Q7_ORDER', 'الأمر:</p>وسّع واحدًا إضافيًا من كل حقل مورد إلى المستوى 1.');
tz_def('Q7_RESP', 'ممتاز جدًا، تطور رائع في إنتاج الموارد.');

tz_def('Q8', 'المهمة 8: جيش ضخم!');
tz_def('Q8_DESC', 'لدي الآن مهمة خاصة جدًا لك. أنا جائع. أعطني 200 من المحاصيل!<br><br>في المقابل سأحاول تنظيم جيش ضخم لحماية قريتك.');
tz_def('Q8_ORDER', 'الأمر:</p>أرسل 200 من المحاصيل إلى مدير المهام.');
tz_def('Q8_BUTN', 'إرسال المحاصيل');
tz_def('Q8_NOCROP', 'لا يوجد محاصيل كافية!');

tz_def('Q9', 'المهمة 9: كل شيء إلى المستوى 1.');
tz_def('Q9_DESC', 'في نوفاتيرا يوجد دائمًا ما تفعله! بينما تنتظر وصول الجيش الضخم، يجب أن نزيد إنتاج مواردك قليلاً. وسّع جميع حقول مواردك إلى المستوى 1.');
tz_def('Q9_ORDER', 'الأمر:</p>وسّع جميع حقول الموارد إلى المستوى 1.');
tz_def('Q9_RESP', 'ممتاز جدًا، إنتاج مواردك يزدهر.<br><br>قريبًا يمكننا البدء ببناء المباني في القرية.');

tz_def('Q10', 'المهمة 10: حمامة السلام');
tz_def('Q10_DESC', 'في الأيام الأولى بعد التسجيل تكون محميًا من هجمات زملائك اللاعبين. يمكنك معرفة مدة استمرار هذه الحماية بإضافة الكود <b>[#0]</b> إلى ملفك الشخصي.');
tz_def('Q10_ORDER', 'الأمر:</p>اكتب الكود <b>[#0]</b> في ملفك الشخصي بإضافته إلى أحد حقلي الوصف.');
tz_def('Q10_RESP', 'أحسنت! الآن يمكن للجميع رؤية أي محارب عظيم يقترب من العالم.');
tz_def('Q10_REWARD', 'مكافأتك:</p>يومان من نوفاتيرا');

tz_def('Q11', 'المهمة 11: الجيران!');
tz_def('Q11_DESC', 'من حولك توجد قرى عديدة مختلفة. إحداها تُسمى. ');
tz_def('Q11_DESC1', ' انقر على `الخريطة` في القائمة العلوية وابحث عن تلك القرية. يمكن رؤية اسم قرى جيرانك بتمرير الفأرة فوق أي منها.');
tz_def('Q11_ORDER', 'الأمر:</p>ابحث عن إحداثيات ');
tz_def('Q11_ORDER1', 'وأدخلها هنا.');
tz_def('Q11_RESP', 'بالضبط، هناك ');
tz_def('Q11_RESP1', ' قرية! بقدر ما تصل من موارد إلى هذه القرية. حسنًا، تقريبًا بنفس القدر...');
tz_def('Q11_BUTN', 'إتمام المهمة');

tz_def('Q12', 'المهمة 12: المخبأ');
tz_def('Q12_DESC', 'حان الوقت لبناء مخبأ. عالم '.SERVER_NAME.' خطير.<br><br>يعيش كثير من اللاعبين على سرقة موارد الآخرين. ابنِ مخبأً لإخفاء بعض مواردك عن الأعداء.');
tz_def('Q12_ORDER', 'الأمر:</p>ابنِ مخبأً.');
tz_def('Q12_RESP', 'أحسنت، أصبح من الأصعب على زملائك اللاعبين الأشرار نهب قريتك.<br><br>عند التعرض للهجوم، سيخفي سكان قريتك الموارد في المخبأ من تلقاء أنفسهم.');

tz_def('Q13', 'المهمة 13: إلى اثنين.');
tz_def('Q13_DESC', 'في '.SERVER_NAME.' يوجد دائمًا ما تفعله! وسّع منشرة خشب واحدة، وحفرة طين واحدة، ومنجم حديد واحد، وحقل محاصيل واحد إلى المستوى 2 لكل منها.');
tz_def('Q13_ORDER', 'الأمر:</p>وسّع واحدًا من كل حقل مورد إلى المستوى 2.');
tz_def('Q13_RESP', 'ممتاز جدًا، قريتك تنمو وتزدهر!');

tz_def('Q14', 'المهمة 14: الإرشادات');
tz_def('Q14_DESC', 'في إرشادات اللعبة يمكنك إيجاد نصوص معلومات مختصرة عن مختلف المباني وأنواع الوحدات.<br><br>انقر على `الإرشادات` على اليسار لمعرفة كمية الخشب المطلوبة للثكنة.');
tz_def('Q14_ORDER', 'الأمر:</p>أدخل كمية الخشب التي تكلفها الثكنة');
tz_def('Q14_BUTN', 'إتمام المهمة');
tz_def('Q14_RESP', 'بالضبط! تكلف الثكنة 210 وحدات خشب.');

tz_def('Q15', 'المهمة 15: المبنى الرئيسي');
tz_def('Q15_DESC', 'يحتاج كبار البنائين إلى مبنى رئيسي من المستوى 3 لإقامة مبانٍ مهمة مثل السوق أو الثكنة.');
tz_def('Q15_ORDER', 'الأمر:</p>وسّع مبناك الرئيسي إلى المستوى 3.');
tz_def('Q15_RESP', 'أحسنت. اكتمل المبنى الرئيسي بالمستوى 3.<br><br>بهذه الترقية لن يتمكن كبار البنائين من بناء مزيد من أنواع المباني فحسب، بل سيفعلون ذلك أسرع أيضًا.');

tz_def('Q16', 'المهمة 16: تقدّم!');
tz_def('Q16_DESC', 'ابحث مجددًا عن رتبتك في إحصائيات اللاعبين واستمتع بتقدمك.');
tz_def('Q16_ORDER', 'الأمر:</p>ابحث عن رتبتك في الإحصائيات وأدخلها هنا.');
tz_def('Q16_RESP', 'أحسنت! هذه رتبتك الحالية.');

tz_def('Q17', 'المهمة 17: أسلحة أم عجين');
tz_def('Q17_DESC', 'عليك الآن اتخاذ قرار: إما التجارة بسلام أو أن تصبح محاربًا مرعبًا.<br><br>للسوق تحتاج صومعة، وللثكنة تحتاج نقطة تجمع.');
tz_def('Q17_BUTN', 'اقتصاد');
tz_def('Q17_BUTN1', 'عسكري');

tz_def('Q18', 'المهمة 18: عسكري');
tz_def('Q18_DESC', 'قرار شجاع. لتتمكن من إرسال القوات تحتاج نقطة تجمع.<br><br>يجب بناء نقطة التجمع في موقع بناء محدد. موقع البناء ');
tz_def('Q18_DESC1', '.');
tz_def('Q18_DESC2', ' يقع على يمين المبنى الرئيسي، أسفله قليلاً. موقع البناء نفسه منحنٍ.');
tz_def('Q18_ORDER', 'الأمر:</p>ابنِ نقطة تجمع.');
tz_def('Q18_RESP', 'أُقيمت نقطة تجمعك! خطوة جيدة نحو السيطرة على العالم!');

tz_def('Q19', 'المهمة 19: الثكنة');
tz_def('Q19_DESC', 'الآن لديك مبنى رئيسي من المستوى 3 ونقطة تجمع. هذا يعني أن جميع متطلبات بناء الثكنة قد تحققت.<br><br>يمكنك استخدام الثكنة لتدريب قوات للقتال.');
tz_def('Q19_ORDER', 'الأمر:</p>ابنِ ثكنة.');
tz_def('Q19_RESP', 'أحسنت... اجتمع أفضل المدربين من كل البلاد لتدريب رجالك على أعلى مستوى من مهارات القتال.');

tz_def('Q20', 'المهمة 20: تدريب.');
tz_def('Q20_DESC', 'الآن بعد أن أصبحت لديك ثكنة يمكنك بدء تدريب القوات. درّب اثنين من ');
tz_def('Q20_ORDER', 'يُرجى تدريب 2 من ');
tz_def('Q20_RESP', 'وُضع أساس جيشك المجيد.<br><br>قبل إرسال جيشك للنهب يجب أن تراجع.');
tz_def('Q20_RESP1', 'محاكي القتال');
tz_def('Q20_RESP2', 'لترى كم قوة تحتاج للانتصار على فأر واحد دون خسائر.');

tz_def('Q21', 'المهمة 18: الاقتصاد');
tz_def('Q21_DESC', 'التجارة والاقتصاد كانا اختيارك. أوقات ذهبية تنتظرك بالتأكيد!');
tz_def('Q21_ORDER', 'الأمر:</p>ابنِ صومعة.');
tz_def('Q21_RESP', 'أحسنت! بالصومعة يمكنك تخزين مزيد من القمح.');

tz_def('Q22', 'المهمة 19: المخزن');
tz_def('Q22_DESC', 'ليست المحاصيل وحدها ما يجب حفظه. يمكن أن تُهدر الموارد الأخرى أيضًا إن لم تُخزَّن بشكل صحيح. ابنِ مخزنًا!');
tz_def('Q22_ORDER', 'الأمر:</p>ابنِ مخزنًا.');
tz_def('Q22_RESP', ';أحسنت، اكتمل مخزنك...&rdquo;</i><br>الآن تحققت جميع المتطلبات اللازمة لبناء سوق.');

tz_def('Q23', 'المهمة 20: السوق.');
tz_def('Q23_DESC', ';ابنِ سوقًا لتتمكن من التجارة مع زملائك اللاعبين.');
tz_def('Q23_ORDER', 'الأمر:</p>يُرجى بناء سوق.');
tz_def('Q23_RESP', ';اكتمل السوق. الآن يمكنك تقديم عروضك الخاصة وقبول عروض الآخرين! عند إنشاء عروضك، فكّر في عرض ما يحتاجه اللاعبون الآخرون أكثر لتحقيق ربح أكبر.');

tz_def('Q24', 'المهمة 21: كل شيء إلى المستوى 2.');
tz_def('Q24_DESC', 'الآن يجب أن نزيد إنتاج مواردك قليلاً. ابنِ منشرة خشب إضافية، وحفرة طين، ومنجم حديد، وحقل محاصيل إلى المستوى 1.');
tz_def('Q24_ORDER', 'الأمر:</p>وسّع جميع حقول الموارد إلى المستوى 2.');
tz_def('Q24_RESP', 'تهانينا! قريتك تنمو وتزدهر...');

tz_def('Q28', 'المهمة 22: التحالف.');
tz_def('Q28_DESC', 'العمل الجماعي مهم في نوفاتيرا. اللاعبون الذين يعملون معًا ينظمون أنفسهم في تحالفات. احصل على دعوة من تحالف في منطقتك وانضم إليه. أو يمكنك تأسيس تحالفك الخاص. للقيام بذلك، تحتاج سفارة من المستوى 3.');
tz_def('Q28_ORDER', 'الأمر:</p>انضم إلى تحالف أو أسّس تحالفًا خاصًا بك.');
tz_def('Q28_RESP', 'جيد! أنت الآن في اتحاد يُدعى');
tz_def('Q28_RESP1', '، وأنت عضو في تحالفهم، وكلما تقدمت أسرع...');

tz_def('Q29', 'المهمة 23: المبنى الرئيسي إلى المستوى 5');
tz_def('Q29_DESC', 'لتتمكن من بناء قصر أو مقر إقامة، ستحتاج مبنى رئيسي من المستوى 5.');
tz_def('Q29_ORDER', 'الأمر:</p>رقّي مبناك الرئيسي إلى المستوى 5.');
tz_def('Q29_RESP', 'المبنى الرئيسي الآن بالمستوى 5 ويمكنك بناء قصر أو مقر إقامة...');

tz_def('Q30', 'المهمة 24: الصومعة إلى المستوى 3.');
tz_def('Q30_DESC', 'حتى لا تخسر محاصيلك، يجب أن ترقّي صومعتك.');
tz_def('Q30_ORDER', 'الأمر:</p>رقّي صومعتك إلى المستوى 3.');
tz_def('Q30_RESP', 'الصومعة الآن بالمستوى 3...');

tz_def('Q31', 'المهمة 25: المخزن إلى المستوى 7');
tz_def('Q31_DESC', ' حتى تتأكد من عدم فيضان مواردك، يجب أن ترقّي مخزنك.');
tz_def('Q31_ORDER', 'الأمر:</p>رقّي مخزنك إلى المستوى 7.');
tz_def('Q31_RESP', 'ترقّى المخزن إلى المستوى 7...');

tz_def('Q32', 'المهمة 26: الكل إلى خمسة!');
tz_def('Q32_DESC', 'ستحتاج دائمًا مزيدًا من الموارد. حقول الموارد مكلفة نوعًا ما لكنها تعود بالفائدة دائمًا على المدى الطويل.');
tz_def('Q32_ORDER', 'الأمر:</p>رقّي جميع حقول الموارد إلى المستوى 5.');
tz_def('Q32_RESP', 'جميع الموارد بالمستوى 5، ممتاز جدًا، قريتك تنمو وتزدهر!');

tz_def('Q33', 'المهمة 27: قصر أم مقر إقامة؟');
tz_def('Q33_DESC', 'لتأسيس قرية جديدة، ستحتاج مستوطنين. يمكنك تدريبهم في قصر أو مقر إقامة.');
tz_def('Q33_ORDER', 'الأمر:</p>ابنِ قصرًا أو مقر إقامة إلى المستوى 10.');
tz_def('Q33_RESP', 'وصل إلى المستوى 10، يمكنك الآن تدريب المستوطنين وتأسيس قريتك الثانية. لاحظ النقاط الثقافية...');

tz_def('Q34', 'المهمة 28: 3 مستوطنين.');
tz_def('Q34_DESC', 'لتأسيس قرية جديدة، ستحتاج مستوطنين. يمكن تدريبهم في قصر أو مقر إقامة.');
tz_def('Q34_ORDER', 'الأمر:</p>درّب 3 مستوطنين.');
tz_def('Q34_RESP', 'تم تدريب 3 مستوطنين. لتأسيس قرية جديدة تحتاج على الأقل');
tz_def('Q34_RESP1', 'نقاط ثقافية...');

tz_def('Q35', 'المهمة 29: قرية جديدة.');
tz_def('Q35_DESC', 'توجد الكثير من الخانات الفارغة على الخريطة. ابحث عن واحدة تناسبك وأسّس قرية جديدة');
tz_def('Q35_ORDER', 'الأمر:</p>أسّس قرية جديدة.');
tz_def('Q35_RESP', 'أنا فخور بك! الآن لديك قريتان ولديك كل الإمكانات لبناء إمبراطورية عظيمة. أتمنى لك التوفيق في ذلك.');

tz_def('Q36', ' المهمة 30: ابنِ ');
tz_def('Q36_DESC', 'الآن بعد أن درّبت بعض الجنود، يجب أن تبني ');
tz_def('Q36_DESC1', ' أيضًا. يزيد الدفاع الأساسي وسيحصل جنودك على مكافأة دفاعية.');
tz_def('Q36_ORDER', 'الأمر:</p>ابنِ ');
tz_def('Q36_RESP', 'هذا ما أتحدث عنه. ');
tz_def('Q36_RESP1', ' مفيد جدًا. يزيد دفاع القوات في القرية.');

tz_def('Q37', 'المهام');
tz_def('Q37_DESC', 'تم إنجاز جميع المهام!');

tz_def('RESOURCES_OVERVIEW', 'نظرة عامة على الموارد');
tz_def('YOUR_RES_DELIVERIES', 'شحنات مواردك');
tz_def('DELIVERY', 'الشحنة');
tz_def('DELIVERY_TIME', 'وقت التسليم');
tz_def('STATUS', 'الحالة');
tz_def('FETCH', 'إحضار');
tz_def('FETCHED', 'تم الإحضار');
tz_def('ON_HOLD', 'قيد الانتظار');
tz_def('ONE_DAY_OF_NOVATERRA', 'يوم واحد من نوفاتيرا ');
tz_def('TWO_DAYS_OF_NOVATERRA', 'يومان من نوفاتيرا ');

//Quest 25
tz_def('Q25_7', 'المهمة 7: الجيران!');
tz_def('Q25_7_DESC', 'من حولك توجد قرى عديدة مختلفة. إحداها تُسمى. ');
tz_def('Q25_7_DESC1', 'انقر على `الخريطة` في القائمة العلوية وابحث عن تلك القرية. يمكن رؤية اسم قرى جيرانك بتمرير الفأرة فوق أي منها.');
tz_def('Q25_7_ORDER', '</p><b>الأمر:</b><br>ابحث عن إحداثيات ');
tz_def('Q25_7_ORDER1', 'وأدخلها هنا.');
tz_def('Q25_7_RESP', 'بالضبط، هناك ');
tz_def('Q25_7_RESP1', ' قرية! بقدر ما تصل من موارد إلى هذه القرية. حسنًا، تقريبًا بنفس القدر...');

tz_def('Q25_8', 'المهمة 8: جيش ضخم!');
tz_def('Q25_8_DESC', 'لدي الآن مهمة خاصة جدًا لك. أنا جائع. أعطني 200 من المحاصيل!<br><br>في المقابل سأحاول تنظيم جيش ضخم لحماية قريتك.');
tz_def('Q25_8_ORDER', 'الأمر:</p>أرسل 200 من المحاصيل إلى مدير المهام.');
tz_def('Q25_8_BUTN', 'إرسال المحاصيل');
tz_def('Q25_8_NOCROP', 'لا يوجد محاصيل كافية!');

tz_def('Q25_9', 'المهمة 9: واحد من كل نوع!');
tz_def('Q25_9_DESC', 'في '.SERVER_NAME.' يوجد دائمًا ما تفعله! بينما تنتظر جيشك الجديد،<br><br>وسّع منشرة خشب إضافية، وحفرة طين، ومنجم حديد، وحقل محاصيل إلى المستوى 1');
tz_def('Q25_9_ORDER', 'الأمر:</p>وسّع واحدًا إضافيًا من كل حقل مورد إلى المستوى 1.');
tz_def('Q25_9_RESP', 'ممتاز جدًا، تطور رائع في إنتاج الموارد.');

tz_def('Q25_10', 'المهمة 10: قريبًا!');
tz_def('Q25_10_DESC', 'حان الوقت لاستراحة قصيرة إلى أن يصل الجيش الضخم الذي أرسلته لك.<br><br>حتى ذلك الحين يمكنك استكشاف الخريطة أو توسيع بعض حقول الموارد.');
tz_def('Q25_10_ORDER', 'الأمر:</p>انتظر وصول جيش مدير المهام');
tz_def('Q25_10_RESP', 'وصل الآن جيش ضخم من مدير المهام لحماية قريتك');
tz_def('Q25_10_REWARD', 'مكافأتك:</p>يومان إضافيان من نوفاتيرا');

tz_def('Q25_11', 'المهمة 11: التقارير');
tz_def('Q25_11_DESC', 'في كل مرة يحدث فيها شيء مهم لحسابك ستصلك رسالة تقرير.<br><br>يمكنك رؤيتها بالنقر على النصف الأيسر من الزر الخامس (من اليسار إلى اليمين). اقرأ التقرير وعد إلى هنا.');
tz_def('Q25_11_ORDER', 'الأمر:</p>اقرأ أحدث تقاريرك.');
tz_def('Q25_11_RESP', 'استلمته؟ ممتاز جدًا. إليك مكافأتك.');

tz_def('Q25_12', 'المهمة 12: كل شيء إلى المستوى 1.');
tz_def('Q25_12_DESC', 'الآن يجب أن نزيد إنتاج مواردك قليلاً.');
tz_def('Q25_12_ORDER', 'الأمر:</p>وسّع جميع حقول الموارد إلى المستوى 1.');
tz_def('Q25_12_RESP', 'ممتاز جدًا، إنتاج مواردك يزدهر.<br><br>قريبًا يمكننا البدء ببناء المباني في القرية.');

tz_def('Q25_13', 'المهمة 13: حمامة السلام');
tz_def('Q25_13_DESC', 'في الأيام الأولى بعد التسجيل تكون محميًا من هجمات زملائك اللاعبين. يمكنك معرفة مدة استمرار هذه الحماية بإضافة الكود <b>[#0]</b> إلى ملفك الشخصي.');
tz_def('Q25_13_ORDER', 'الأمر:</p>اكتب الكود <b>[#0]</b> في ملفك الشخصي بإضافته إلى أحد حقلي الوصف.');
tz_def('Q25_13_RESP', 'أحسنت! الآن يمكن للجميع رؤية أي محارب عظيم يقترب من العالم.');

tz_def('Q25_14', 'المهمة 14: المخبأ');
tz_def('Q25_14_DESC', 'حان الوقت لبناء مخبأ. عالم <b>'.SERVER_NAME.'</b> خطير.<br><br>يعيش كثير من اللاعبين على سرقة موارد الآخرين. ابنِ مخبأً لإخفاء بعض مواردك عن الأعداء.');
tz_def('Q25_14_ORDER', 'الأمر:</p>ابنِ مخبأً.');
tz_def('Q25_14_RESP', 'أحسنت، أصبح من الأصعب على زملائك اللاعبين الأشرار نهب قريتك.<br><br>عند التعرض للهجوم، سيخفي سكان قريتك الموارد في المخبأ من تلقاء أنفسهم.');

tz_def('Q25_15', 'المهمة 15: إلى اثنين.');
tz_def('Q25_15_DESC', 'في <b>'.SERVER_NAME.'</b> يوجد دائمًا ما تفعله! وسّع منشرة خشب واحدة، وحفرة طين واحدة، ومنجم حديد واحد، وحقل محاصيل واحد إلى المستوى 2 لكل منها.');
tz_def('Q25_15_ORDER', 'الأمر:</p>وسّع واحدًا من كل حقل مورد إلى المستوى 2.');
tz_def('Q25_15_RESP', 'ممتاز جدًا، قريتك تنمو وتزدهر!');

tz_def('Q25_16', 'المهمة 16: الإرشادات');
tz_def('Q25_16_DESC', 'في إرشادات اللعبة يمكنك إيجاد نصوص معلومات مختصرة عن مختلف المباني وأنواع الوحدات.<br><br>انقر على `الإرشادات` على اليسار لمعرفة كمية الخشب المطلوبة للثكنة.');
tz_def('Q25_16_ORDER', 'الأمر:</p>أدخل كمية الخشب التي تكلفها الثكنة');
tz_def('Q25_16_BUTN', 'إتمام المهمة');
tz_def('Q25_16_RESP', 'بالضبط! تكلف الثكنة 210 وحدات خشب.');

tz_def('Q25_17', 'المهمة 17: المبنى الرئيسي');
tz_def('Q25_17_DESC', 'يحتاج كبار البنائين إلى مبنى رئيسي من المستوى 3 لإقامة مبانٍ مهمة مثل السوق أو الثكنة.');
tz_def('Q25_17_ORDER', 'الأمر:</p>وسّع مبناك الرئيسي إلى المستوى 3.');
tz_def('Q25_17_RESP', 'أحسنت. اكتمل المبنى الرئيسي بالمستوى 3.<br><br>بهذه الترقية يمكن لكبار البنائين بناء مزيد من أنواع المباني وأيضًا بشكل أسرع.');

tz_def('Q25_18', 'المهمة 18: تقدّم!');
tz_def('Q25_18_DESC', 'ابحث مجددًا عن رتبتك في إحصائيات اللاعبين واستمتع بتقدمك.');
tz_def('Q25_18_ORDER', 'الأمر:</p>ابحث عن رتبتك في الإحصائيات وأدخلها هنا.');
tz_def('Q25_18_RESP', 'أحسنت! هذه رتبتك الحالية.');

tz_def('Q25_19', 'المهمة 19: أسلحة أم عجين');
tz_def('Q25_19_DESC', 'عليك الآن اتخاذ قرار: إما التجارة بسلام أو أن تصبح محاربًا مرعبًا.<br><br>للسوق تحتاج صومعة، وللثكنة تحتاج نقطة تجمع.');
tz_def('Q25_19_BUTN', 'اقتصاد');
tz_def('Q25_19_BUTN1', 'عسكري');

tz_def('Q25_20', 'المهمة 19: الاقتصاد');
tz_def('Q25_20_DESC', 'التجارة والاقتصاد كانا اختيارك. أوقات ذهبية تنتظرك بالتأكيد!');
tz_def('Q25_20_ORDER', 'الأمر:</p>ابنِ صومعة.');
tz_def('Q25_20_RESP', 'أحسنت! بالصومعة يمكنك تخزين مزيد من القمح.');

tz_def('Q25_21', 'المهمة 20: المخزن');
tz_def('Q25_21_DESC', 'ليست المحاصيل وحدها ما يجب حفظه. يمكن أن تُهدر الموارد الأخرى أيضًا إن لم تُخزَّن بشكل صحيح. ابنِ مخزنًا!');
tz_def('Q25_21_ORDER', 'الأمر:</p>ابنِ مخزنًا.');
tz_def('Q25_21_RESP', ';أحسنت، اكتمل مخزنك...&rdquo;</i><br>الآن تحققت جميع المتطلبات اللازمة لبناء سوق.');

tz_def('Q25_22', 'المهمة 21: السوق.');
tz_def('Q25_22_DESC', ';ابنِ سوقًا لتتمكن من التجارة مع زملائك اللاعبين.');
tz_def('Q25_22_ORDER', 'الأمر:</p>يُرجى بناء سوق.');
tz_def('Q25_22_RESP', 'اكتمل السوق. الآن يمكنك تقديم عروضك الخاصة وقبول عروض الآخرين! عند إنشاء عروضك، فكّر في عرض ما يحتاجه اللاعبون الآخرون أكثر لتحقيق ربح أكبر.');

tz_def('Q25_23', 'المهمة 19: عسكري');
tz_def('Q25_23_DESC', 'قرار شجاع. لتتمكن من إرسال القوات تحتاج نقطة تجمع.<br><br>يجب بناء نقطة التجمع في موقع بناء محدد. موقع البناء ');
tz_def('Q25_23_DESC1', '.');
tz_def('Q25_23_DESC2', ' يقع على يمين المبنى الرئيسي، أسفله قليلاً. موقع البناء نفسه منحنٍ.');
tz_def('Q25_23_ORDER', 'الأمر:</p>ابنِ نقطة تجمع.');
tz_def('Q25_23_RESP', 'أُقيمت نقطة تجمعك! خطوة جيدة نحو السيطرة على العالم!');

tz_def('Q25_24', 'المهمة 20: الثكنة');
tz_def('Q25_24_DESC', 'الآن لديك مبنى رئيسي من المستوى 3 ونقطة تجمع. هذا يعني أن جميع متطلبات بناء الثكنة قد تحققت.<br><br>يمكنك استخدام الثكنة لتدريب قوات للقتال.');
tz_def('Q25_24_ORDER', 'الأمر:</p>ابنِ ثكنة.');
tz_def('Q25_24_RESP', 'أحسنت... اجتمع أفضل المدربين من كل البلاد لتدريب رجالك على أعلى مستوى من مهارات القتال.');

tz_def('Q25_25', 'المهمة 21: تدريب.');
tz_def('Q25_25_DESC', 'الآن بعد أن أصبحت لديك ثكنة يمكنك بدء تدريب القوات. درّب اثنين من ');
tz_def('Q25_25_ORDER', 'يُرجى تدريب 2 من ');
tz_def('Q25_25_RESP', 'وُضع أساس جيشك المجيد.<br><br>قبل إرسال جيشك للنهب يجب أن تراجع');
tz_def('Q25_25_RESP1', 'محاكي القتال');
tz_def('Q25_25_RESP2', 'لترى كم قوة تحتاج للانتصار على فأر واحد دون خسائر.');

tz_def('Q25_26', 'المهمة 22: كل شيء إلى المستوى 2.');
tz_def('Q25_26_DESC', 'حان الوقت مجددًا لتوسيع ركائز القوة والثروة! هذه المرة المستوى 1 لا يكفي... سيستغرق الأمر وقتًا لكنه سيستحق العناء في النهاية. وسّع جميع حقول مواردك إلى المستوى 2!');
tz_def('Q25_26_ORDER', 'الأمر:</p>وسّع جميع حقول الموارد إلى المستوى 2.');
tz_def('Q25_26_RESP', 'تهانينا! قريتك تنمو وتزدهر...');

// GOLD / GOLD_IMG must be defined before first use below (originally defined much
// later in this file, around the old line 1771-1772, which caused a fatal
// "Undefined constant GOLD_IMG" error on PHP 8.3). Moved here, directly above
// their first use, with no change to the translated text or definitions.
tz_def('GOLD', 'ذهب');
tz_def('GOLD_IMG', '<img src=\"/img/x.gif\" class=\"gold\" alt=\"'.GOLD.'\" title=\"'.GOLD.'\">');

tz_def('Q25_27', 'المهمة 23: الأصدقاء.');
tz_def('Q25_27_DESC', 'من الصعب على اللاعب المنفرد منافسة المهاجمين. من مصلحتك أن يحبك جيرانك.<br><br>والأفضل من ذلك أن تلعب مع أصدقاء. هل تعلم أنه يمكنك كسب '.GOLD_IMG.' بدعوة الأصدقاء؟');
tz_def('Q25_27_ORDER', 'الأمر:</p>كم من '.GOLD_IMG.' تكسب مقابل دعوة صديق؟');
tz_def('Q25_27_RESP', 'صحيح! تحصل على 50 '.GOLD_IMG.' إذا كان لدى الصديق الذي دعوته قريتان.');

tz_def('Q25_28', 'المهمة 24: بناء سفارة.');
tz_def('Q25_28_DESC', 'عالم نوفاتيرا خطير. لقد بنيت بالفعل مخبأً لحمايتك من المهاجمين.<br><br>سيمنحك تحالف جيد حماية أفضل.');
tz_def('Q25_28_ORDER', 'الأمر:</p>لقبول دعوات التحالفات، ابنِ سفارة.');
tz_def('Q25_28_RESP', 'نعم! يمكنك انتظار دعوة من تحالف أو إنشاء تحالفك الخاص إذا كانت السفارة بالمستوى 3');

tz_def('Q25_29', 'المهمة 25: التحالف.');
tz_def('Q25_29_DESC', 'العمل الجماعي مهم في نوفاتيرا. اللاعبون الذين يعملون معًا ينظمون أنفسهم في تحالفات. احصل على دعوة من تحالف في منطقتك وانضم إليه. أو يمكنك تأسيس تحالفك الخاص. للقيام بذلك، تحتاج سفارة من المستوى 3.');
tz_def('Q25_29_ORDER', 'الأمر:</p>انضم إلى تحالف أو أسّس تحالفك الخاص.');
tz_def('Q25_29_RESP', 'أحسنت! أنت الآن في اتحاد يُدعى');
tz_def('Q25_29_RESP1', '، وأنت عضو في تحالفهم.<br>بالعمل معًا ستتقدمون جميعًا أسرع...');

tz_def('Q25_30', 'المهام');
tz_def('Q25_30_DESC', 'تم إنجاز جميع المهام!');

//INDEX.php (تكملة)
tz_def('TOTAL_PLAYERS', PLAYERS.' بالإجمالي');
tz_def('ONLINE_PLAYERS', PLAYERS.' متصل الآن');
tz_def('MP_STRATEGY_GAME', SERVER_NAME.' - لعبة الإستراتيجية متعددة اللاعبين');
tz_def('WHAT_IS', SERVER_NAME.' هي واحدة من أشهر ألعاب المتصفح في العالم. كلاعب في '.SERVER_NAME.'، ستبني إمبراطوريتك الخاصة، وتجند جيشًا عظيمًا، وتقاتل جنبًا إلى جنب مع حلفائك من أجل السيطرة على عالم اللعبة.');
tz_def('LATEST_GAME_WORLD2', 'سجّل في أحدث<br>عالم لعبة واستمتع<br>بمزايا كونك<br>من أوائل<br>اللاعبين.');
tz_def('PLAY_NOW', 'العب الآن');
tz_def('LEARN_MORE', 'تعرّف على المزيد <br>عن '.SERVER_NAME.'!');
tz_def('LEARN_MORE2', 'الآن مع نظام خوادم<br>ثوري ورسومات<br>جديدة تمامًا<br>هذا الاستنساخ هو الأفضل!');
tz_def('BECOME_COMUNITY', 'كن جزءًا من مجتمعنا الآن!');
tz_def('BECOME_COMUNITY2', 'كن جزءًا من واحد<br>من أكبر مجتمعات<br>الألعاب في<br>العالم.');
tz_def('LEARN1', 'رقّي حقولك ومناجمك لزيادة إنتاج مواردك. ستحتاج الموارد لبناء المباني وتدريب الجنود.');
tz_def('LEARN2', 'ابنِ ووسّع المباني في قريتك. تحسّن المباني بنيتك التحتية العامة، وتزيد إنتاج مواردك، وتتيح لك البحث وتدريب وترقية قواتك.');
tz_def('LEARN3', 'شاهد وتفاعل مع محيطك. يمكنك تكوين أصدقاء جدد أو أعداء جدد، والاستفادة من الواحات القريبة، ومراقبة نمو إمبراطوريتك وازدياد قوتها.');
tz_def('LEARN4', 'تابع تقدمك ونجاحك وقارن نفسك باللاعبين الآخرين. اطّلع على تصنيفات أفضل 10 وقاتل للفوز بوسام أسبوعي.');
tz_def('LEARN5', 'استلم تقارير مفصلة عن مغامراتك وصفقاتك ومعاركك. لا تنسَ الاطلاع على التقارير الجديدة عن الأحداث في محيطك.');
tz_def('LEARN6', 'تبادل المعلومات ومارس الدبلوماسية مع اللاعبين الآخرين. تذكّر دائمًا أن التواصل هو مفتاح كسب أصدقاء جدد وحل الخلافات القديمة.');
tz_def('LOGIN_TO', 'تسجيل الدخول إلى '.SERVER_NAME);
tz_def('REGIN_TO', 'التسجيل في '.SERVER_NAME);
tz_def('STARTED', ' بدأ الخادم منذ '. round((time() - COMMENCE) / 86400) .' يومًا.');

//ANMELDEN.php (تكملة)
tz_def('BEFORE_REGISTER', 'قبل تسجيل حساب، يجب أن تقرأ <a href="/anleitung.php" target="_blank">إرشادات</a> Novaterra ro1 لمعرفة مزايا وعيوب كل قبيلة.');

//ATTACKS ETC. (تكملة)
tz_def('OASISATTACKS', 'هجمات الواحة');
tz_def('MARK_ATTACK', 'وسم هذا الهجوم (الخطورة)');
tz_def('PRISONERSIN', 'أسرى في');
tz_def('PRISONERSFROM', 'أسرى من');
tz_def('CATAPULT_TARGET', 'هدف/أهداف المنجنيق');
tz_def('TROOPS_ON_THEIR_WAY', 'قوات في طريقها');
tz_def('ON', 'في');
tz_def('AT', 'عند');
tz_def('TROOPS_IN_THE_VILLAGE', 'قوات في القرية');
tz_def('TROOPS_IN_OTHER_VILLAGE', 'قوات في قرية أخرى');
tz_def('TROOPS_IN_OASIS', 'قوات في الواحة');
tz_def('TASKMASTER', 'مدير المهام');
tz_def('TO_THE_TASK', 'إلى المهمة');
tz_def('VILLAGE_OF_THE_ELDERS', 'قرية الشيوخ');
tz_def('VILLAGE_OF_THE_ELDERS_TROOPS', 'قوات قرية الشيوخ');

//map (تكملة)
tz_def('THERENOINFO', 'لا توجد<br>معلومات متاحة.');
tz_def('CULTUREPOINT', 'نقاط ثقافية');
tz_def('BUILDRALLY', 'ابنِ نقطة تجمع');
tz_def('SETTLERSAVAIL', 'مستوطنون متاحون');
tz_def('BEGINPRO', 'حماية المبتدئين');
tz_def('BUILDMARKET', 'بناء سوق');
tz_def('LARGE_MAP_DESC', 'عرض الخريطة الكبيرة في نافذة إضافية');

//other (تكملة)
tz_def('TOP10AA', 'أفضل 10 تحالفات مهاجمة');
tz_def('TOP10AD', 'أفضل 10 تحالفات مدافعة');

//LOGIN.php (تكملة)
tz_def('COOKIES', 'يجب تفعيل ملفات تعريف الارتباط (كوكيز) لتتمكن من تسجيل الدخول. إذا كنت تشارك هذا الجهاز مع آخرين، يُستحسن تسجيل الخروج بعد كل جلسة لسلامتك.');
tz_def('PW_REQUEST', 'يمكنك عندها طلب كلمة مرور جديدة ستُرسل إلى بريدك الإلكتروني.');
tz_def('EMAIL_FOLLOW', 'اتبع هذا الرابط لتفعيل حسابك.');

//404.php (تكملة)
tz_def('WE_LOOKED', 'بحثنا 404 مرة بالفعل ولم نجد شيئًا');

//MASSMESSAGE.php (تكملة)
tz_def('MASS', 'محتوى الرسالة');
tz_def('MASS_SUBJECT', 'الموضوع:');
tz_def('MASS_COLOR', 'لون الرسالة:');
tz_def('MASS_REQUIRED', 'جميع الحقول مطلوبة');
tz_def('MASS_UNITS', 'صور (وحدات):');
tz_def('MASS_SHOWHIDE', 'إظهار/إخفاء');
tz_def('MASS_READ', 'اقرأ هذا: بعد إضافة الرمز التعبيري، يجب إضافة كلمة left أو right بعد الرقم وإلا فلن تعمل الصورة');
tz_def('MASS_CONFIRM', 'التأكيد');
tz_def('MASS_REALLY', 'هل تريد فعلاً إرسال رسالة جماعية؟');
tz_def('MASS_ABORT', 'الإلغاء الآن');
tz_def('MASS_SENT', 'تم إرسال الرسالة الجماعية');

// HEADER && MENU && Messages && Reports
tz_def('REPORTS', 'التقارير');
tz_def('MESSAGES', 'الرسائل');
tz_def('PLUS_MENU', 'قائمة بلس');
tz_def('LINKS', 'روابط');
tz_def('CANCEL_PROCESS', 'إلغاء العملية');
tz_def('ACCOUNT_DELETING', 'سيتم حذف الحساب خلال');
tz_def('INBOX', 'صندوق الوارد');
tz_def('WRITE', 'كتابة');
tz_def('SENT', 'المرسلة');
tz_def('SEND', 'إرسال');
tz_def('ARCHIVE', 'الأرشيف');
tz_def('NOTES', 'ملاحظات');
tz_def('SUBJECT', 'الموضوع');
tz_def('SENDER', 'المرسل');
tz_def('RECIPIENT', 'المستلم');
tz_def('BACK', 'رجوع');
tz_def('NEW', 'جديد');
tz_def('UNREAD', 'غير مقروءة');
tz_def('NO_MESS', 'لا توجد رسائل متاحة');
tz_def('NO_MESS_IN_ARCHIVE', NO_MESS.' في الأرشيف');
tz_def('NO_MESS_SENT', 'لا توجد رسائل مرسلة متاحة');
tz_def('MESS_FOR_SUP', 'رسالة إلى الدعم');
tz_def('MESS_FOR_MH', 'رسالة إلى صياد المتعددين');
tz_def('SEND_AS_SUP', 'إرسال كدعم فني');
tz_def('SEND_AS_MH', 'إرسال كصياد متعددين');
tz_def('SAVE', 'حفظ');
tz_def('ANSWER', 'إجابة');
tz_def('REPLY', 'رد');
tz_def('ADDRESSBOOK', 'دفتر العناوين');
tz_def('CLOSE_ADDRESSBOOK', 'إغلاق دفتر العناوين');
tz_def('ONLINE_S1', 'متصل الآن');
tz_def('ONLINE_S2', 'غير متصل');
tz_def('ONLINE_S3', 'آخر 3 أيام');
tz_def('ONLINE_S4', 'آخر 7 أيام');
tz_def('ONLINE_S5', 'غير نشط');
tz_def('WAIT_FOR_CONFIRM', 'انتظار التأكيد');
tz_def('CONFIRM', 'تأكيد');
tz_def('WRITE_MESS_WARN', '<b>تحذير:</b> لا يمكنك استخدام القيمتين <b>[message]</b> أو <b>[/message]</b> في رسالتك لأنها قد تسبب مشكلة مع نظام bbcode');
tz_def('NO_REPORTS', 'لا توجد تقارير متاحة');
tz_def('ATTACKER', 'المهاجم');
tz_def('NATAR_COUNTERFORCE', 'قوة التتار المضادة');
tz_def('FROM_THE_VILL', 'من القرية');
tz_def('CASUALTIES', 'الخسائر');
tz_def('INFORMATION', 'معلومات');
// === Battle report strings (issue: i18n of combat reports) ===
tz_def('RC_HERO', 'البطل');
tz_def('RC_CATAPULT', 'المنجنيق');
tz_def('RC_TRAP', 'الفخ');
tz_def('RC_WALL', 'السور');
tz_def('TZ_AT', 'عند');
// Catapults
tz_def('RC_DESTROYED', 'دُمّر');
tz_def('RC_NOT_DAMAGED', 'لم يتضرر.');
tz_def('RC_DAMAGED_FROM_TO', 'تضرر من المستوى <b>%s</b> إلى المستوى <b>%s</b>.');
tz_def('RC_NO_BUILDINGS', 'لا توجد مبانٍ متبقية لتدميرها');
tz_def('RC_VILLAGE_ALREADY_DESTROYED', 'القرية مدمرة بالفعل.');
tz_def('RC_VILLAGE_CANT_DESTROY', "لا يمكن تدمير القرية.");
tz_def('RC_VILLAGE_CANT_BE', "لا يمكن أن تكون القرية");
tz_def('RC_VILLAGE_DESTROYED', 'دُمّرت القرية.');
// Rams
tz_def('RC_NO_WALL', 'لا يوجد سور لتدميره.');
tz_def('RC_WALL_DESTROYED', 'السور <b>مدمَّر</b>.');
tz_def('RC_WALL_NOT_DAMAGED', 'لم يتضرر السور.');
tz_def('RC_WALL_DAMAGED_FROM_TO', 'تضرر السور من المستوى <b>%s</b> إلى المستوى <b>%s</b>.');
// Conquest / chief
tz_def('RC_NO_REDUCE_CP_RAID', 'تعذر خفض النقاط الثقافية أثناء النهب');
tz_def('RC_NOT_ENOUGH_CP', 'نقاط ثقافية غير كافية.');
tz_def('RC_CANT_TAKEOVER', 'لا يمكنك الاستيلاء على هذه القرية.');
tz_def('RC_RESIDENCE_NOT_DESTROYED', "القصر/مقر الإقامة لم يُدمَّر بعد!");
tz_def('RC_LOYALTY_LOWERED', 'انخفض الولاء من <b>%s</b> إلى <b>%s</b>.');
tz_def('RC_INHABITANTS_JOIN', 'قرر سكان قرية %s الانضمام إلى إمبراطوريتك.');
// Hero
tz_def('RC_HERO_NO_KILL', 'لم يكن لدى بطلك ما يقتله لذا لم يكسب أي نقاط خبرة.');
tz_def('RC_HERO_GAINED_XP', 'اكتسب بطلك <b>%s</b> نقطة خبرة.');
tz_def('RC_HERO_CONQUERED_OASIS', 'غزا بطلك هذه الواحة');
tz_def('RC_HERO_REDUCED_OASIS_LOYALTY', 'خفض بطلك ولاء الواحة إلى %s من %s');
tz_def('RC_NO_REDUCE_LOYALTY_RAID', 'تعذر خفض الولاء أثناء النهب');
tz_def('RC_HERO_CARRYING_ARTIFACT', 'يحمل بطلك القطعة الأثرية <b>%s</b> عائدًا إلى الديار و');
tz_def('RC_HERO_NO_ARTIFACT_RAID', 'لم يتمكن بطلك من الحصول على قطعة أثرية أثناء النهب');
tz_def('RC_HERO_AND_GAINED_XP_BATTLE', 'واكتسب <b>%s</b> نقطة خبرة من المعركة.');
tz_def('RC_HERO_NO_XP_BATTLE', 'لا نقاط خبرة من المعركة.');
tz_def('RC_HERO_GAINED_XP_BATTLE', 'اكتسب <b>%s</b> نقطة خبرة من المعركة.');
tz_def('RC_HERO_BUT_GAINED_XP_BATTLE', 'لكنه اكتسب <b>%s</b> نقطة خبرة من المعركة.');
tz_def('RC_HERO_TRAPPED', 'وقع بطلك في فخ');
tz_def('RC_HERO_DIED', 'مات بطلك');
// Scout report
tz_def('RC_TOTAL_RESOURCES', 'إجمالي الموارد:');
tz_def('RC_RESIDENCE_LEVEL', 'مستوى مقر الإقامة:');
tz_def('RC_PALACE_LEVEL', 'مستوى القصر:');
tz_def('RC_WALL_LEVEL', 'مستوى السور:');
tz_def('RC_CRANNY_CAPACITY', 'إجمالي سعة المخابئ:');
tz_def('RC_NO_INFO', 'لا توجد معلومات لعرضها');
// Prisoners / traps
tz_def('RC_OF_WHICH_SAVED', 'منها <b>%s</b> تم إنقاذها');
tz_def('RC_FREED_FROM_HIS_TROOPS', 'حرر <b>%s</b> من قواته');
tz_def('RC_FREED_FRIENDLY_TROOPS', 'حرر <b>%s</b> من القوات الصديقة');
tz_def('RC_AND_FRIENDLY_TROOPS', 'و<b>%s</b> من القوات الصديقة');
// Troop return
tz_def('RC_NONE_RETURNED', 'لم يعد أي من جنودك.');
// === End battle report strings ===
// === System / alliance in-game messages (sendMessage), rendered per reader ===
tz_def('MSG_INVITE_ALLIANCE', 'دعوة إلى تحالف');
tz_def('MSG_FORUM_NEW_TITLE', 'رسالة جديدة في المنتدى');
tz_def('MSG_FORUM_NEW_BODY', "مرحبًا!\n\nقام <a href=\"%s\">%s</a> بنشر رسالة جديدة في موضوعكم المشترك. إليك رابط يوصلك إلى هناك: <a href=\"%s\">رابط المنتدى</a>\n\nمع خالص التحية،\n<i>روبوت الخادم :)</i>");
tz_def('MSG_LEFT_ALLIANCE_TITLE', 'غادرت التحالف');
tz_def('MSG_FORCED_LEAVE_TITLE', 'أجبرك هجوم على مغادرة التحالف');
tz_def('MSG_LEFT_DEMOLITION_BODY', "مرحبًا، %s!\n\nنحيطك علمًا أنه بسبب اكتمال هدم سفارتك الأخيرة، فقد غادرت تحالفك بنجاح.\n\nمع خالص التحية،\n<i>روبوت الخادم :)</i>");
tz_def('MSG_LEFT_ATTACK_BODY', "مرحبًا، %s!\n\nنحيطك علمًا أنه بسبب هجوم ناجح ودمار سفارتك الأخيرة، أُجبرت على مغادرة تحالفك.\n\nلاستعادة موقعك في هذا التحالف، ستحتاج إلى بناء سفارة جديدة وطلب دعوة جديدة من القائد.\n\nمع خالص التحية،\n<i>روبوت الخادم :)</i>");
tz_def('MSG_DISBAND_TITLE', 'تم حل تحالفك');
tz_def('MSG_DISBAND_OWNER_BODY', "مرحبًا، %s!\n\nنحيطك علمًا أنه بسبب اكتمال هدم سفارتك الأخيرة من المستوى 3، وكونك قائد التحالف، فقد تم حل هذا التحالف.\n\nلتأسيس تحالف جديد، يُرجى بناء سفارة من المستوى 3 مجددًا في إحدى قراك.\n\nمع خالص التحية،\n<i>روبوت الخادم :)</i>");
tz_def('MSG_DISBAND_MEMBER_BODY', "مرحبًا، %s!\n\nنحيطك علمًا أنه بسبب هدم سفارة مؤسس تحالفك الأخيرة إلى ما دون المستوى 3، فقد تم حل هذا التحالف.\n\nيمكنك الآن قبول دعوات من تحالفات أخرى أو تأسيس تحالف جديد بنفسك.\n\nمع خالص التحية،\n<i>روبوت الخادم :)</i>");
tz_def('MSG_NOW_ALLIANCE_LEADER_TITLE', 'أنت الآن قائد التحالف');
tz_def('MSG_NOW_LEADER_TITLE', 'أنت الآن قائد تحالفك');
tz_def('MSG_PROMOTE_BODY', "مرحبًا، %s!\n\nنحيطك علمًا بأنه حدث هجوم ناجح على اللاعب <a href=\"spieler.php?uid=%s\">%s</a> ألحق ضررًا بسفارته لدرجة أنه لم يعد قادرًا على تحمل قيادة تحالفك.\n\nبما أن مستوى سفارتك كافٍ، فقد تم انتخابك تلقائيًا كقائد جديد لتحالفك مع كل الواجبات والمسؤوليات المترتبة على ذلك.\n\nمع خالص التحية،\n<i>روبوت الخادم :)</i>");
tz_def('MSG_DISPERSE_TITLE', 'تم تفكيك تحالفك');
tz_def('MSG_DISPERSE_OWNER_BODY_MANY', "مرحبًا، %s!\n\nنحيطك علمًا أنه بسبب هجوم ناجح خفّض مستوى سفارتك الأخيرة إلى مستوى غير قادر على استيعاب جميع أعضاء التحالف البالغ عددهم %s، ولعدم وجود عضو آخر في التحالف لديه سفارة بمستوى كافٍ لتولي القيادة، فقد تم تفكيك تحالفك.\n\nمع خالص التحية،\n<i>روبوت الخادم :)</i>");
tz_def('MSG_DISPERSE_OWNER_BODY_FEW', "مرحبًا، %s!\n\nنحيطك علمًا أنه بسبب هجوم ناجح خفّض مستوى سفارتك الأخيرة إلى أقل من المستوى 3 - وهو المطلوب لتأسيس تحالفك والحفاظ عليه - فقد تم تفكيك تحالفك.\n\nمع خالص التحية،\n<i>روبوت الخادم :)</i>");
tz_def('MSG_DISPERSE_MEMBER_BODY', "مرحبًا، %s!\n\nنحيطك علمًا أنه بسبب هجوم ناجح على سفارة قائد تحالفك من قبل لاعب آخر خفّض مستواها دون الحد المطلوب لاستيعاب جميع أعضاء التحالف البالغ عددهم %s، ولعدم وجود عضو آخر في التحالف لديه سفارة بمستوى كافٍ لتولي القيادة، فقد تم تفكيك تحالفك.\n\nمع خالص التحية،\n<i>روبوت الخادم :)</i>");
tz_def('MSG_NEW_LEADER_TITLE', 'لتحالفك قائد جديد');
tz_def('MSG_NEWLEADER_OWNER_BODY', "مرحبًا، %s!\n\nنحيطك علمًا أنه بسبب هجوم ناجح خفّض مستوى سفارتك الأخيرة إلى مستوى غير قادر على استيعاب جميع أعضاء التحالف البالغ عددهم %s، تم انتخاب عضو آخر في التحالف يستوفي هذه المعايير تلقائيًا كقائد جديد للتحالف.\n\nكما أنه - بسبب تدمير السفارة - تم طردك قسريًا من تحالفك.\n\nيُرجى إعادة الاتصال بتحالفك عبر بناء سفارة جديدة والتواصل مع <a href=\"spieler.php?uid=%s\">القائد الجديد</a> لطلب دعوة.\n\nمع خالص التحية،\n<i>روبوت الخادم :)</i>");
tz_def('MSG_NEWLEADER_MEMBER_BODY', "مرحبًا، %s!\n\nنحيطك علمًا أنه بسبب هجوم ناجح على سفارة قائد تحالفك من قبل لاعب آخر، تم انتخاب <a href=\"spieler.php?uid=%s\">عضو آخر في التحالف</a> لديه سعة سفارة كافية تلقائيًا كقائد جديد للتحالف.\n\nمع خالص التحية،\n<i>روبوت الخادم :)</i>");
tz_def('MSG_FORCED_LEAVE_BODY', "مرحبًا، %s!\n\nنحيطك علمًا أنه بسبب هجوم ناجح ودمار سفارتك الأخيرة، أُجبرت على مغادرة تحالفك.\n\nلاستعادة موقعك في هذا التحالف، ستحتاج إلى بناء سفارة جديدة وطلب دعوة جديدة من <a href=\"spieler.php?uid=%s\">القائد المنتخب تلقائيًا حديثًا</a>.\n\nمع خالص التحية،\n<i>روبوت الخادم :)</i>");
tz_def('MSG_INVITE_BODY', "مرحبًا، %s!\n\nنحيطك علمًا بأنه تمت دعوتك للانضمام إلى تحالف. لقبول هذه الدعوة، يُرجى زيارة سفارتك.\n\nمع خالص التحية،\n<i>روبوت الخادم :)</i>");
tz_def('MSG_QUIT_REPLACEMENT_BODY', "مرحبًا!\n\nنحيطك علمًا بأن القائد السابق لتحالفك - %s، قرر الانسحاب واختارك بديلًا له. أصبح لديك الآن صلاحية الوصول الكامل والإدارة والمسؤوليات الخاصة بتحالفك.\n\nحظًا موفقًا!\n\nمع خالص التحية،\n<i>روبوت الخادم :)</i>");
// Embassy-destruction status lines, appended to the catapult battle report.
tz_def('MSG_ALLIANCE_DISPERSED_STATUS', "تم تفكيك تحالف هذا اللاعب.");
tz_def('MSG_FORCED_LEAVE_STATUS', 'أُجبر اللاعب على مغادرة تحالفه.');
// Alliance news-feed notices (rendered in Templates/Alliance/news.tpl)
tz_def('MSG_INVITE_NOTICE', 'دعا %s اللاعب %s إلى التحالف.');
tz_def('MSG_ALLIANCE_FOUNDED', 'تأسس التحالف بواسطة %s.');
tz_def('MSG_NEWS_REJECTED', 'رفض %s الدعوة.');
tz_def('MSG_NEWS_DELETED_INVITE', 'حذف %s الدعوة الموجهة إلى %s.');
tz_def('MSG_NEWS_JOINED', 'انضم %s إلى التحالف.');
tz_def('MSG_NEWS_NAME_CHANGED', 'غيّر %s اسم التحالف.');
tz_def('MSG_NEWS_DESC_CHANGED', 'غيّر %s وصف التحالف.');
tz_def('MSG_NEWS_PERMS_CHANGED', 'غيّر %s صلاحيات %s.');
tz_def('MSG_NEWS_EXPELLED', 'تم طرد %s من التحالف بواسطة %s.');
tz_def('MSG_NEWS_QUIT', 'انسحب %s من التحالف.');
tz_def('MSG_NEWS_DIPLO_CONFED', 'عرض %s اتحادًا كونفدراليًا على %s.');
tz_def('MSG_NEWS_DIPLO_NAP', 'عرض %s اتفاقية عدم اعتداء على %s.');
tz_def('MSG_NEWS_DIPLO_WAR', 'أعلن %s الحرب على %s.');
tz_def('CARRY', 'حمل');
tz_def('DEFENDER', 'المدافع');
tz_def('TZ_WARSIM_TYPE_OF_ATTACK', 'نوع الهجوم');
tz_def('TZ_WARSIM_NORMAL', 'عادي');
tz_def('TZ_WARSIM_RAM_HINT', 'تلميح: الكبش لا يعمل أثناء الغارة.');
tz_def('TZ_WARSIM_CATA_HINT', 'تلميح: المنجنيق لا يطلق أثناء الغارة.');
tz_def('TZ_WARSIM_RAM_DAMAGE', 'الضرر الذي أحدثه الكبش: من المستوى <b>%s</b> إلى المستوى <b>%s</b>');
tz_def('TZ_WARSIM_CATA_DAMAGE', 'الضرر الذي أحدثته المنجنيق: من المستوى <b>%s</b> إلى المستوى <b>%s</b>');
tz_def('VISITED', 'زار');
tz_def('HIS_TROOPS', ' قواته');
tz_def('WISHES_YOU', 'يتمنى لك');
tz_def('X_MAS', 'عيد ميلاد مجيد');
tz_def('NEW_YEAR', 'سنة جديدة سعيدة');
tz_def('EASTER', 'عيد فصح سعيد');
if(!defined('PEACE')) tz_def('PEACE', 'سلام');

//Admin setting - Admin/Templates/config.tpl & editServerSet.tpl
tz_def('EDIT_BACK', 'رجوع');
tz_def('SERV_CONFIG', 'إعدادات السيرفر');
tz_def('SERV_SETT', 'إعدادات السيرفر');
tz_def('EDIT_SERV_SETT', 'تعديل إعدادات السيرفر');
tz_def('SERV_VARIABLE', 'المتغيّر');
tz_def('SERV_VALUE', 'القيمة');
tz_def('CONF_SERV_NAME', 'اسم السيرفر');
tz_def('CONF_SERV_NAME_TOOLTIP', 'اسم سيرفر اللعبة.');
tz_def('CONF_SERV_STARTED', 'تاريخ بدء السيرفر');
tz_def('CONF_SERV_STARTED_TOOLTIP', 'الوقت الذي بدأ فيه سيرفر اللعبة. لا يمكن تغيير هذا المتغيّر على سيرفر مثبَّت بالفعل.');
tz_def('CONF_SERV_TIMEZONE', 'المنطقة الزمنية للسيرفر');
tz_def('CONF_SERV_TIMEZONE_TOOLTIP', 'المنطقة الزمنية لسيرفر اللعبة.');
tz_def('CONF_SERV_LANG', 'اللغة');
tz_def('CONF_SERV_LANG_TOOLTIP', 'اللغة المستخدمة في لوحة الأدمن ولجميع اللاعبين على سيرفر اللعبة افتراضيًا.');
tz_def('CONF_SERV_SERVSPEED', 'سرعة السيرفر');
tz_def('CONF_SERV_SERVSPEED_TOOLTIP', 'سرعة سيرفر اللعبة. كلما زادت سرعة السيرفر، كلما بُنيت المباني بشكل أسرع، وتمت الأبحاث والتطويرات في الحدادة بشكل أسرع، وبُنيت الوحدات بسرعة، وزادت إنتاجية جميع الموارد.');
tz_def('CONF_SERV_TROOPSPEED', 'سرعة القوات');
tz_def('CONF_SERV_TROOPSPEED_TOOLTIP', 'سرعة تحرك القوات على سيرفر اللعبة. كلما زاد هذا المؤشر، كلما تحركت القوات أسرع على الخريطة.');
tz_def('CONF_SERV_EVASIONSPEED', 'سرعة المراوغة');
tz_def('CONF_SERV_EVASIONSPEED_TOOLTIP', 'سرعة المراوغة هي الوقت الذي تقضيه القوات في الطريق للعودة إلى الوطن بعد مراوغة هجوم.');
tz_def('CONF_SERV_STORMULTIPLER', 'مضاعِف سعة التخزين');
tz_def('CONF_SERV_STORMULTIPLER_TOOLTIP', 'مضاعِف لسعة تخزين المخزن والمخزن الزراعي. القيمة 1 تساوي سعة 80,000 من كل مورد عند أعلى مستوى. إذا ضبطت القيمة إلى 2، فستكون السعة عند أعلى مستوى 160,000 من كل مورد.<br><b>ملاحظة:</b> كمية الموارد التي ستُنتَج من الواحات غير المحتلة للنهب تعتمد على هذه القيمة. القيمة الافتراضية هي 800. إذا ضبطتها إلى 2، فسيصبح الحد الأقصى المُنتَج لكل مورد 1600.');
tz_def('CONF_SERV_TRADCAPACITY', 'سعة التاجر');
tz_def('CONF_SERV_TRADCAPACITY_TOOLTIP', 'مضاعِف لسعة الموارد التي يمكن لتاجر واحد نقلها. القيمة 1 تساوي سعة 500 للرومان، و750 للغال، و1000 للتيوتون. إذا ضبطت القيمة إلى 2، فستتضاعف سعة الموارد المنقولة وفقًا لذلك: 1000، 1500، 2000.');
tz_def('CONF_SERV_CRANCAPACITY', 'سعة المخبأ');
tz_def('CONF_SERV_CRANCAPACITY_TOOLTIP', 'مضاعِف لسعة الموارد في المخبأ، والتي يمكن حمايتها من النهب. القيمة 1 تساوي 1000 للرومان والتيوتون، و2000 للغال. إذا ضبطت القيمة إلى 2، فستتضاعف سعة المخبأ لتصبح 2000 و4000 على التوالي.');
tz_def('CONF_SERV_TRAPCAPACITY', 'سعة الفخ');
tz_def('CONF_SERV_TRAPCAPACITY_TOOLTIP', 'مضاعِف لسعة فخ الغال، الذي يستطيع أسر جنود العدو حتى قبل مهاجمة القرية. القيمة 1 تساوي سعة 400 عند المستوى 20 من البناء. إذا ضبطت القيمة إلى 2، فستصبح السعة 800.');
tz_def('CONF_SERV_NATUNITSMULTIPLIER', 'مضاعِف وحدات التتار');
tz_def('CONF_SERV_NATUNITSMULTIPLIER_TOOLTIP', 'هذا المتغيّر مسؤول عن عدد قوات التتار، على القطع الأثرية وقرى عجائب الدنيا.');
tz_def('CONF_SERV_NATARS_SPAWN_TIME', 'ظهور التتار');
tz_def('CONF_SERV_NATARS_SPAWN_TIME_TOOLTIP', 'بعد كم يومًا من تاريخ بدء السيرفر سيظهر التتار والقطع الأثرية');
tz_def('CONF_SERV_NATARS_WW_SPAWN_TIME', 'ظهور عجائب الدنيا');
tz_def('CONF_SERV_NATARS_WW_SPAWN_TIME_TOOLTIP', 'بعد كم يومًا من تاريخ بدء السيرفر ستظهر قرى عجائب الدنيا');
tz_def('CONF_SERV_NATARS_WW_BUILDING_PLAN_SPAWN_TIME', 'ظهور مخططات بناء عجائب الدنيا');
tz_def('CONF_SERV_NATARS_WW_BUILDING_PLAN_SPAWN_TIME_TOOLTIP', 'بعد كم يومًا من تاريخ بدء السيرفر ستظهر مخططات بناء عجائب الدنيا');
tz_def('CONF_SERV_MAPSIZE', 'حجم الخريطة');
tz_def('CONF_SERV_MAPSIZE_TOOLTIP', 'حجم خريطة عالم اللعبة. لا يمكن تغييره على سيرفر لعبة مثبَّت بالفعل.');
tz_def('CONF_SERV_VILLEXPSPEED', 'سرعة توسّع القرى');
tz_def('CONF_SERV_VILLEXPSPEED_TOOLTIP', 'سرعة تؤثر على توسّع الإمبراطورية. مع السرعة البطيئة تحتاج إلى نقاط ثقافة أكثر لتأسيس قرية جديدة، ومع السرعة السريعة يقل العدد المطلوب من نقاط الثقافة.');
tz_def('CONF_SERV_BEGINPROTECT', 'حماية المبتدئين');
tz_def('CONF_SERV_BEGINPROTECT_TOOLTIP', 'حماية تمنع مهاجمة قرى اللاعبين الجدد لفترة زمنية معيّنة.');
tz_def('CONF_SERV_REGOPEN', 'التسجيل مفتوح');
tz_def('CONF_SERV_REGOPEN_TOOLTIP', 'يسمح بتفعيل (صحيح) أو تعطيل (خطأ) تسجيل اللاعبين على سيرفر اللعبة.');
tz_def('CONF_SERV_ACTIVMAIL', 'تفعيل البريد الإلكتروني');
tz_def('CONF_SERV_ACTIVMAIL_TOOLTIP', 'إذا كان مفعّلًا (نعم)، سيكون من الضروري عند التسجيل تأكيد البريد الإلكتروني. إذا كان معطّلًا (لا) فلا حاجة لتأكيد البريد الإلكتروني.');
tz_def('CONF_SERV_QUEST', 'المهام');
tz_def('CONF_SERV_QUEST_TOOLTIP', 'تفعيل (نعم) أو تعطيل (لا) نظام المهام على سيرفر اللعبة.');
tz_def('CONF_SERV_QTYPE', 'نوع المهام');
tz_def('CONF_SERV_QTYPE_TOOLTIP', 'نوع المهام يمكن أن يكون رسميًا وهو أقصر قليلًا، أو موسّعًا وهو أطول.');
tz_def('CONF_SERV_DLR', 'الهدم - المستوى المطلوب');
tz_def('CONF_SERV_DLR_TOOLTIP', 'المستوى المطلوب للمبنى الرئيسي، الذي يمكن من خلاله هدم المباني في القرية.');
tz_def('CONF_SERV_WWSTATS', 'عجيبة الدنيا - الإحصائيات');
tz_def('CONF_SERV_WWSTATS_TOOLTIP', 'تفعيل (صحيح) أو تعطيل (خطأ) عرض القرى التي تحتوي على عجيبة من عجائب الدنيا في الإحصائيات.');
tz_def('CONF_SERV_NTRTIME', 'وقت تجدد قوات الطبيعة');
tz_def('CONF_SERV_NTRTIME_TOOLTIP', 'الوقت الذي يتم من خلاله استعادة قوات الطبيعة في الواحات.');
tz_def('CONF_SERV_OASIS_WOOD_PROD_MULT', 'مضاعِف إنتاج الخشب في الواحة');
tz_def('CONF_SERV_OASIS_WOOD_PROD_MULT_TOOLTIP', 'الإنتاج الأساسي للخشب في الواحة');
tz_def('CONF_SERV_OASIS_CLAY_PROD_MULT', 'مضاعِف إنتاج الطين في الواحة');
tz_def('CONF_SERV_OASIS_CLAY_PROD_MULT_TOOLTIP', 'الإنتاج الأساسي للطين في الواحة');
tz_def('CONF_SERV_OASIS_IRON_PROD_MULT', 'مضاعِف إنتاج الحديد في الواحة');
tz_def('CONF_SERV_OASIS_IRON_PROD_MULT_TOOLTIP', 'الإنتاج الأساسي للحديد في الواحة');
tz_def('CONF_SERV_OASIS_CROP_PROD_MULT', 'مضاعِف إنتاج المحاصيل في الواحة');
tz_def('CONF_SERV_OASIS_CROP_PROD_MULT_TOOLTIP', 'الإنتاج الأساسي للمحاصيل في الواحة');
tz_def('CONF_SERV_MEDALINTERVAL', 'الفاصل الزمني للأوسمة');
tz_def('CONF_SERV_MEDALINTERVAL_TOOLTIP', 'الفاصل الزمني لمنح الأوسمة لأفضل اللاعبين والتحالفات. إذا تم تغيير هذا المتغيّر على سيرفر مثبَّت بالفعل، فسيتغيّر الفاصل الزمني بعد عملية منح الأوسمة التالية.');
tz_def('CONF_SERV_TOURNTHRES', 'حد ساحة البطولة');
tz_def('CONF_SERV_TOURNTHRES_TOOLTIP', 'عدد المربعات على خريطة اللعبة، والذي بعده تبدأ ساحة البطولة في العمل.');
tz_def('CONF_SERV_GWORKSHOP', 'الورشة العظيمة');
tz_def('CONF_SERV_GWORKSHOP_TOOLTIP', 'تفعيل (صحيح) أو تعطيل (خطأ) استخدام الورشة العظيمة في اللعبة.');
tz_def('CONF_SERV_NATARSTAT', 'إظهار التتار في الإحصائيات');
tz_def('CONF_SERV_NATARSTAT_TOOLTIP', 'تفعيل (صحيح) أو تعطيل (خطأ) إظهار حساب التتار في الإحصائيات.');
tz_def('CONF_SERV_PEACESYST', 'نظام السلام');
tz_def('CONF_SERV_PEACESYST_TOOLTIP', 'تفعيل أو تعطيل نظام السلام. عند تفعيل نظام السلام، سيتمكن اللاعبون من مهاجمة بعضهم البعض، ولكن بدلًا من أي أضرار في التقارير ستظهر عبارة تهنئة. ولن تموت القوات جوعًا.');
tz_def('CONF_SERV_GRAPHICPACK', 'حزمة الرسوميات');
tz_def('CONF_SERV_GRAPHICPACK_TOOLTIP', 'تفعيل (نعم) أو تعطيل (لا) إمكانية استخدام حزمة الرسوميات.');
tz_def('CONF_SERV_ERRORREPORT', 'الإبلاغ عن الأخطاء');
tz_def('CONF_SERV_ERRORREPORT_TOOLTIP', 'تفعيل (نعم) أو تعطيل (لا) عرض تقارير الأخطاء على سيرفر اللعبة.');

//Admin setting - Admin/Templates/config.tpl & editPlusSet.tpl
tz_def('PLUS_LOGO', '<b><font color="#71D000">P</font><font color="#FF6F0F">l</font><font color="#71D000">u</font><font color="#FF6F0F">s</font></b>');
tz_def('PLUS_CONFIGURATION', 'إعدادات '.PLUS_LOGO);
tz_def('PLUS_SETT', 'إعدادات '.PLUS_LOGO);
tz_def('EDIT_PLUS_SETT', 'تعديل إعدادات '.PLUS_LOGO);
tz_def('EDIT_PLUS_SETT1', 'تعديل إعدادات PLUS');
tz_def('CONF_PLUS_PAYPALEMAIL', 'البريد الإلكتروني لـ<a href="https://www.paypal.com" target="_blank">PayPal</a>');
tz_def('CONF_PLUS_PAYPALEMAIL_TOOLTIP', 'البريد الإلكتروني المحدد عند التسجيل في PayPal.<br><font color="red"><b>يجب أن يكون حساب Business أو Premier!</b></font>');
tz_def('CONF_PLUS_CURRENCY', 'عملة الدفع');
tz_def('CONF_PLUS_CURRENCY_TOOLTIP', 'العملة التي سيتم استخدامها في الدفع.');
tz_def('CONF_PLUS_PACKAGEGOLDA', 'كمية الذهب لباقة &#34;A&#34;');
tz_def('CONF_PLUS_PACKAGEGOLDA_TOOLTIP', 'كمية الذهب الممنوحة عند دفع ثمن باقة &#34;A&#34;.');
tz_def('CONF_PLUS_PACKAGEPRICEA', 'سعر باقة &#34;A&#34;');
tz_def('CONF_PLUS_PACKAGEPRICEA_TOOLTIP', 'المبلغ اللازم لدفع تكلفة باقة &#34;A&#34;.');
tz_def('CONF_PLUS_PACKAGEGOLDB', 'كمية الذهب لباقة &#34;B&#34;');
tz_def('CONF_PLUS_PACKAGEGOLDB_TOOLTIP', 'كمية الذهب الممنوحة عند دفع ثمن باقة &#34;B&#34;.');
tz_def('CONF_PLUS_PACKAGEPRICEB', 'سعر باقة &#34;B&#34;');
tz_def('CONF_PLUS_PACKAGEPRICEB_TOOLTIP', 'المبلغ اللازم لدفع تكلفة باقة &#34;B&#34;.');
tz_def('CONF_PLUS_PACKAGEGOLDC', 'كمية الذهب لباقة &#34;C&#34;');
tz_def('CONF_PLUS_PACKAGEGOLDC_TOOLTIP', 'كمية الذهب الممنوحة عند دفع ثمن باقة &#34;C&#34;.');
tz_def('CONF_PLUS_PACKAGEPRICEC', 'سعر باقة &#34;C&#34;');
tz_def('CONF_PLUS_PACKAGEPRICEC_TOOLTIP', 'المبلغ اللازم لدفع تكلفة باقة &#34;C&#34;.');
tz_def('CONF_PLUS_PACKAGEGOLDD', 'كمية الذهب لباقة &#34;D&#34;');
tz_def('CONF_PLUS_PACKAGEGOLDD_TOOLTIP', 'كمية الذهب الممنوحة عند دفع ثمن باقة &#34;D&#34;.');
tz_def('CONF_PLUS_PACKAGEPRICED', 'سعر باقة &#34;D&#34;');
tz_def('CONF_PLUS_PACKAGEPRICED_TOOLTIP', 'المبلغ اللازم لدفع تكلفة باقة &#34;D&#34;.');
tz_def('CONF_PLUS_PACKAGEGOLDE', 'كمية الذهب لباقة &#34;E&#34;');
tz_def('CONF_PLUS_PACKAGEGOLDE_TOOLTIP', 'كمية الذهب الممنوحة عند دفع ثمن باقة &#34;E&#34;.');
tz_def('CONF_PLUS_PACKAGEPRICEE', 'سعر باقة &#34;E&#34;');
tz_def('CONF_PLUS_PACKAGEPRICEE_TOOLTIP', 'المبلغ اللازم لدفع تكلفة باقة &#34;E&#34;.');
tz_def('CONF_PLUS_ACCDURATION', 'مدة حساب '.PLUS_LOGO);
tz_def('CONF_PLUS_ACCDURATION_TOOLTIP', 'مدة تفعيل ميزة '.PLUS_LOGO.' للحساب عند تفعيلها من قبل اللاعب.');
tz_def('CONF_PLUS_PRODUCTDURATION', 'مدة زيادة الإنتاج بنسبة +25%');
tz_def('CONF_PLUS_PRODUCTDURATION_TOOLTIP', 'مدة تفعيل ميزة زيادة الإنتاج بنسبة +25% للحساب عند تفعيلها من قبل اللاعب.');

//Admin setting - Admin/Templates/config.tpl & editLogSet.tpl
tz_def('LOG_SETT', 'إعدادات السجلات');
tz_def('EDIT_LOG_SETT', 'تعديل إعدادات السجلات');
tz_def('CONF_LOG_BUILD', 'سجل البناء');
tz_def('CONF_LOG_BUILD_TOOLTIP', 'تفعيل (نعم) أو تعطيل (لا) عرض سجلات بناء المباني في القرية.');
tz_def('CONF_LOG_TECHNOLOGY', 'سجل التقنيات');
tz_def('CONF_LOG_TECHNOLOGY_TOOLTIP', 'تفعيل (نعم) أو تعطيل (لا) عرض سجلات تطوير القوات في الحدادة والدرع.');
tz_def('CONF_LOG_LOGIN', 'سجل تسجيل الدخول');
tz_def('CONF_LOG_LOGIN_TOOLTIP', 'تفعيل (نعم) أو تعطيل (لا) عرض سجلات دخول اللاعبين إلى اللعبة.');
tz_def('CONF_LOG_GOLD', 'سجل الذهب');
tz_def('CONF_LOG_GOLD_TOOLTIP', 'تفعيل (نعم) أو تعطيل (لا) عرض سجلات استخدام الذهب داخل اللعبة من قبل اللاعبين.');
tz_def('CONF_LOG_ADMIN', 'سجل الأدمن');
tz_def('CONF_LOG_ADMIN_TOOLTIP', 'تفعيل (نعم) أو تعطيل (لا) عرض سجلات إجراءات المسؤول في لوحة التحكم.');
tz_def('CONF_LOG_WAR', 'سجل الحروب');
tz_def('CONF_LOG_WAR_TOOLTIP', 'تفعيل (نعم) أو تعطيل (لا) عرض سجلات الهجمات على اللاعبين في اللعبة.');
tz_def('CONF_LOG_MARKET', 'سجل السوق');
tz_def('CONF_LOG_MARKET_TOOLTIP', 'تفعيل (نعم) أو تعطيل (لا) عرض سجلات استخدام السوق في اللعبة من قبل اللاعبين.');
tz_def('CONF_LOG_ILLEGAL', 'سجل المخالفات');
tz_def('CONF_LOG_ILLEGAL_TOOLTIP', 'تفعيل (نعم) أو تعطيل (لا) عرض سجلات المخالفات. (غير معروف بالضبط ماهيتها)');

//Admin setting - Admin/Templates/config.tpl & editNewsboxSet.tpl
tz_def('NEWSBOX_SETT', 'إعدادات صندوق الأخبار');
tz_def('EDIT_NEWSBOX_SETT', 'تعديل إعدادات صندوق الأخبار');
tz_def('EDIT_NEWSBOX1', 'صندوق الأخبار 1');
tz_def('EDIT_NEWSBOX1_TOOLTIP', 'تفعيل أو تعطيل عرض صندوق الأخبار 1. يظهر في صفحة تسجيل الدخول وفي صفحات اللعبة.');
tz_def('EDIT_NEWSBOX2', 'صندوق الأخبار 2');
tz_def('EDIT_NEWSBOX2_TOOLTIP', 'تفعيل أو تعطيل عرض صندوق الأخبار 2. يظهر في صفحة تسجيل الدخول وفي صفحات اللعبة.');
tz_def('EDIT_NEWSBOX3', 'صندوق الأخبار 3');
tz_def('EDIT_NEWSBOX3_TOOLTIP', 'تفعيل أو تعطيل عرض صندوق الأخبار 3. يظهر في صفحة تسجيل الدخول وفي صفحات اللعبة.');

//Admin setting - Admin/Templates/config.tpl SQL Settings
tz_def('SQL_SETTINGS', 'إعدادات SQL');
tz_def('CONF_SQL_HOSTNAME', 'اسم المضيف');
tz_def('CONF_SQL_HOSTNAME_TOOLTIP', 'اسم السيرفر الذي يعمل عليه MySQL (الافتراضي هو: localhost).');
tz_def('CONF_SQL_PORT', 'المنفذ');
tz_def('CONF_SQL_PORT_TOOLTIP', 'منفذ MySQL للاتصال عن بُعد. المنفذ القياسي للاتصال هو: 3306.');
tz_def('CONF_SQL_DBUSER', 'اسم مستخدم قاعدة البيانات');
tz_def('CONF_SQL_DBUSER_TOOLTIP', 'اسم المستخدم للاتصال بقاعدة البيانات.');
tz_def('CONF_SQL_DBPASS', 'كلمة مرور قاعدة البيانات');
tz_def('CONF_SQL_DBPASS_TOOLTIP', 'كلمة مرور المستخدم للاتصال بقاعدة البيانات.');
tz_def('CONF_SQL_DBNAME', 'اسم قاعدة البيانات');
tz_def('CONF_SQL_DBNAME_TOOLTIP', 'اسم قاعدة البيانات التي يتم الاتصال بها.');
tz_def('CONF_SQL_TBPREFIX', 'بادئة الجداول');
tz_def('CONF_SQL_TBPREFIX_TOOLTIP', 'البادئة المستخدمة لجداول قاعدة البيانات.');
tz_def('CONF_SQL_DBTYPE', 'نوع قاعدة البيانات');
tz_def('CONF_SQL_DBTYPE_TOOLTIP', 'نوع قاعدة البيانات المستخدمة.');

//Admin setting - Admin/Templates/config.tpl & editExtraSet.tpl
tz_def('EXTRA_SETT', 'إعدادات إضافية');
tz_def('EDIT_EXTRA_SETT', 'تعديل الإعدادات الإضافية');
tz_def('CONF_EXTRA_LIMITMAIL', 'حد صندوق الرسائل');
tz_def('CONF_EXTRA_LIMITMAIL_TOOLTIP', 'تفعيل (نعم) أو تعطيل (لا) الحد الأقصى لصندوق الرسائل.');
tz_def('CONF_EXTRA_MAXMAIL', 'الحد الأقصى لعدد الرسائل');
tz_def('CONF_EXTRA_MAXMAIL_TOOLTIP', 'العدد الأقصى للرسائل التي يمكن أن تتسع في صندوق الرسائل.');

//Admin setting - Admin/Templates/config.tpl & editAdminInfo.tpl
tz_def('ADMIN_INFO', 'معلومات الأدمن');
tz_def('EDIT_ADMIN_INFO', 'تعديل معلومات الأدمن');
tz_def('CONF_ADMIN_NAME', 'اسم الأدمن');
tz_def('CONF_ADMIN_NAME_TOOLTIP', 'الاسم الخاص بحساب المسؤول.');
tz_def('CONF_ADMIN_EMAIL', 'بريد الأدمن الإلكتروني');
tz_def('CONF_ADMIN_EMAIL_TOOLTIP', 'البريد الإلكتروني الخاص بحساب المسؤول.');
tz_def('CONF_ADMIN_SHOWSTATS', 'إظهار الأدمن في الإحصائيات');
tz_def('CONF_ADMIN_SHOWSTATS_TOOLTIP', 'تفعيل (صحيح) أو تعطيل (خطأ) عرض حساب المسؤول في الإحصائيات العامة للاعبين.');
tz_def('CONF_ADMIN_SUPPMESS', 'تضمين رسائل الدعم');
tz_def('CONF_ADMIN_SUPPMESS_TOOLTIP', 'تفعيل (صحيح) أو تعطيل (خطأ) إرسال الرسائل الموجّهة إلى الدعم إلى صندوق رسائل المسؤول.');
tz_def('CONF_ADMIN_RAIDATT', 'السماح بالنهب والهجوم');
tz_def('CONF_ADMIN_RAIDATT_TOOLTIP', 'تفعيل (صحيح) أو تعطيل (خطأ) إمكانية نهب ومهاجمة المسؤول.');

// ===== i18n nouvelles constantes (etape 2) =====
tz_def('TZ_ACTIVATION_AVAILBLE_IN', 'التفعيل متاح خلال:');
tz_def('TZ_ACTIVATION_CODE', 'رمز التفعيل:');
tz_def('TZ_ADD', 'إضافة');
tz_def('TZ_ADD_2', 'إضافة');
tz_def('TZ_ALLIANCE_ID', 'رقم التحالف');
tz_def('TZ_ARRIVED', 'وصل:');
tz_def('TZ_ASSIGN_TO_POSITION', 'تعيين في منصب');
tz_def('TZ_AS_SOON_AS_A_PLAYER_YOU_INVITED_FO', 'بمجرد أن يقوم لاعب دعوته بتأسيس');
tz_def('TZ_ATTACKS', 'الهجمات');
tz_def('TZ_OVERVIEW', 'نظرة عامة');
tz_def('TZ_FORUM', 'المنتدى');
tz_def('TZ_CHAT', 'الدردشة');
tz_def('TZ_NEWS', 'الأخبار');
tz_def('TZ_OPTIONS', 'الخيارات');
tz_def('TZ_BOLD', 'عريض');
tz_def('TZ_BUILDING', 'مبنى');
tz_def('TZ_CATAPULT_TARGET', 'هدف المنجنيق');
tz_def('TZ_CLICK_TO_COPY', 'انقر للنسخ');
tz_def('TZ_CLIMBERS_OF_THE_WEEK', 'متسلقو الأسبوع');
tz_def('TZ_CLOCK', 'الساعة');
tz_def('TZ_CONTINUE_WITH_THE_NEXT_TASK', 'تابع إلى المهمة التالية.');
tz_def('TZ_CREATE', 'إنشاء');
tz_def('TZ_CREATE_A_NEW_LIST', 'إنشاء قائمة جديدة');
tz_def('TZ_DESTINATION', 'الوجهة:');
tz_def('TZ_DOES_NOT_EXIST', 'غير موجود.');
tz_def('TZ_DOWNLOAD', 'تحميل');
tz_def('TZ_EVENT', 'الحدث');
tz_def('TZ_FEATURES_OF_NOVATERRA', 'مزايا نوفاتيرا');
tz_def('TZ_FORUM_NAME', 'اسم المنتدى');
tz_def('TZ_FORWARD', 'إعادة توجيه');
tz_def('TZ_GOLD', 'ذهب.');
tz_def('TZ_HERO_DEF_BONUS', 'البطل (مكافأة الدفاع)');
tz_def('TZ_HERO_FIGHTING_STRENGTH', 'البطل (قوة القتال)');
tz_def('TZ_HERO_OFF_BONUS', 'البطل (مكافأة الهجوم)');
tz_def('TZ_HOUR', 'ساعة');
tz_def('TZ_HOW_IS_IT_DONE', 'كيف يتم ذلك؟');
tz_def('TZ_HRS', 'س');
tz_def('TZ_HRS_2', 'س.');
tz_def('TZ_IF_YOU_GET_NEW_PLAYERS_TO_OPEN_AN', 'إذا جعلت لاعبين جددًا يفتحون حسابًا ويؤسسون قرية ثانية، ستحصل على');
tz_def('TZ_IN_YOUR_POST_BECAUSE_IT_CAN_CAUSE', 'في منشورك لأنه قد يسبب مشكلة مع نظام bbcode.');
tz_def('TZ_ITALIC', 'مائل');
tz_def('TZ_LAST_TARGETS', 'آخر الأهداف:');
tz_def('TZ_LIST_NAME', 'اسم القائمة:');
tz_def('TZ_LOOK_FOR_YOUR_RANK_IN_THE_STATISTI', 'ابحث عن ترتيبك في الإحصائيات وأدخله هنا.');
tz_def('TZ_MACEMAN', 'حامل الصولجان');
tz_def('TZ_MEMBERS', 'الأعضاء');
tz_def('TZ_MEMBER_SINCE', 'عضو منذ');
tz_def('TZ_NAME', 'الاسم:');
tz_def('TZ_NO', 'رقم.');
tz_def('TZ_NOT_CODED_YET', '(لم تتم برمجته بعد)');
tz_def('TZ_NO_EMAIL_RECEIVED', 'لم تستلم بريدًا إلكترونيًا؟');
tz_def('TZ_N_15_GOLD', '15 ذهب');
tz_def('TZ_N_1_INVITE_YOUR_FRIENDS_VIA_EMAIL', '1) ادعُ أصدقاءك عبر البريد الإلكتروني');
tz_def('TZ_N_20_GOLD', '20 ذهب');
tz_def('TZ_N_2_COPY_YOUR_PERSONAL_REF_LINK_AN', '2) انسخ رابط الإحالة الشخصي وشاركه!');
tz_def('TZ_N_50_GOLD', '50 ذهب');
tz_def('TZ_OK_2', 'موافق');
tz_def('TZ_OPEN_FORUM_FOR_THE_FOLLOWING_PLAYE', 'فتح المنتدى للاعبين التاليين');
tz_def('TZ_OPEN_FOR_MORE_ALLIANCES', 'مفتوح لمزيد من التحالفات');
tz_def('TZ_ORDER', 'الترتيب:');
tz_def('TZ_OTHER', 'أخرى');
tz_def('TZ_OWNER', 'المالك:');
tz_def('TZ_PAY_SECURELY_WITH_PAYPAL', 'ادفع بأمان عبر PayPal.');
tz_def('TZ_PLAYERS_BROUGHT_IN', 'اللاعبون الذين تمت دعوتهم');
tz_def('TZ_POST_NEW_THREAD', 'نشر موضوع جديد');
tz_def('TZ_PREVIEW', 'معاينة');
tz_def('TZ_PREVIEW_2', 'معاينة');
tz_def('TZ_PROGRESS_OF_YOUR_INVITED_FRIENDS', 'تقدّم أصدقائك المدعوين');
tz_def('TZ_QUIT_ALLIANCE', 'مغادرة التحالف');
tz_def('TZ_REGISTER_FOR_THE_GAME', 'سجّل في اللعبة');
tz_def('TZ_REGISTRATION', 'التسجيل');
tz_def('TZ_SENT', 'أُرسل:');
tz_def('TZ_SMILIES', 'الرموز التعبيرية');
tz_def('TZ_SMILIES_2', 'الرموز التعبيرية');
tz_def('TZ_STONEMASON_S_LODGE', 'نُزل النحّات');
tz_def('TZ_TAG', 'الوسم:');
tz_def('TZ_TARGET_VILLAGE', 'القرية المستهدفة:');
tz_def('TZ_TASK_10_CRANNY', 'المهمة 10: المخبأ');
tz_def('TZ_TASK_11_TO_TWO', 'المهمة 11: إلى اثنين.');
tz_def('TZ_TASK_12_INSTRUCTIONS', 'المهمة 12: تعليمات');
tz_def('TZ_TASK_13_MAIN_BUILDING', 'المهمة 13: المبنى الرئيسي');
tz_def('TZ_TASK_14_ADVANCED', 'المهمة 14: متقدّم!');
tz_def('TZ_TASK_16_ECONOMY', 'المهمة 16: الاقتصاد');
tz_def('TZ_TASK_16_MILITARY', 'المهمة 16: الجيش');
tz_def('TZ_TASK_17_BARRACKS', 'المهمة 17: الثكنة');
tz_def('TZ_TASK_17_WAREHOUSE', 'المهمة 17: المخزن');
tz_def('TZ_TASK_18_MARKETPLACE', 'المهمة 18: السوق.');
tz_def('TZ_TASK_18_TRAIN', 'المهمة 18: التدريب.');
tz_def('TZ_TASK_19_EVERYTHING_TO_2', 'المهمة 19: كل شيء إلى 2.');
tz_def('TZ_TASK_20_ALLIANCE', 'المهمة 20: التحالف.');
tz_def('TZ_TASK_21_MAIN_BUILDING_TO_LEVEL_5', 'المهمة 21: المبنى الرئيسي إلى المستوى 5');
tz_def('TZ_TASK_22_GRANARY_TO_LEVEL_3', 'المهمة 22: المخزن الزراعي إلى المستوى 3.');
tz_def('TZ_TASK_23_WAREHOUSE_TO_LEVEL_7', 'المهمة 23: المخزن إلى المستوى 7.');
tz_def('TZ_TASK_24_ALL_TO_FIVE', 'المهمة 24: كل شيء إلى خمسة!');
tz_def('TZ_TASK_25_PALACE_OR_RESIDENCE', 'المهمة 25: القصر أم مقر الإقامة؟');
tz_def('TZ_TASK_26_3_SETTLERS', 'المهمة 26: 3 مستوطنين.');
tz_def('TZ_TASK_27_NEW_VILLAGE', 'المهمة 27: قرية جديدة.');
tz_def('TZ_TASK_3_YOUR_VILLAGE_S_NAME', 'المهمة 3: اسم قريتك');
tz_def('TZ_TASK_9_DOVE_OF_PEACE', 'المهمة 9: حمامة السلام');
tz_def('TZ_TERMS', 'الشروط');
tz_def('TZ_THE_ALLIANCE', 'التحالف');
tz_def('TZ_THE_LARGEST_ALLIANCES', 'أكبر التحالفات');
tz_def('TZ_THE_USER', 'المستخدم');
tz_def('TZ_THREAD', 'الموضوع');
tz_def('TZ_TOP_10', 'أفضل 10');
tz_def('TZ_TOTAL', 'الإجمالي');
tz_def('TZ_TOWNHALL', 'دار البلدية');
tz_def('TZ_NOVATERRA_NAME', 'نوفاتيرا');
tz_def('TZ_NOVATERRA', 'نوفاتيرا');
tz_def('TZ_GAME_BRAND', 'نوفاتيرا');
tz_def('TZ_UNDERLINE', 'تسطير');
tz_def('TZ_UNDERLINED', 'مسطّر');
tz_def('TZ_UNKNOWN', 'غير معروف');
tz_def('TZ_UNTIL_THE_NEXT_LEVEL', 'حتى المستوى التالي');
tz_def('TZ_USER_ID', 'رقم المستخدم');
tz_def('TZ_VILLAGE', 'القرية:');
tz_def('TZ_VILLAGE_OVERVIEW', 'نظرة عامة على القرية');
tz_def('TZ_ALL_RIGHTS_RESERVED', 'جميع الحقوق محفوظة');
tz_def('TZ_WAIT_INSTANT', 'الانتظار: فوري');
tz_def('TZ_WARNING', 'تحذير:');
tz_def('TZ_WOOD', 'خشب');
tz_def('TZ_YOUR_PERSONAL_REF_LINK', 'رابط الإحالة الشخصي الخاص بك:');
tz_def('TZ_YOU_CAN_T_USE_THE_VALUES', 'لا يمكنك استخدام القيم');
tz_def('TZ_YOU_HAVE_NOT_BROUGHT_IN_ANY_NEW_PL', 'لم تدعُ أي لاعبين جدد بعد.');

// ===== i18n etape 2 (lot suivant) =====
tz_def('TZ_ACCOUNT_IS_ADMIN_OR_MH', 'الحساب أدمن أو صاحب مضيف');
tz_def('TZ_ACCOUNT_IS_NOT_SCHEDULED_FOR_DELET', 'الحساب غير مُجدوَل للحذف');
tz_def('TZ_ACCOUNT_STATEMENT', 'كشف الحساب');
tz_def('TZ_ACTIVATE_VACATION_MODE', 'تفعيل وضع الإجازة');
tz_def('TZ_ADD_RAID', 'إضافة غارة');
tz_def('TZ_ADD_SLOT', 'إضافة فتحة');
tz_def('TZ_ADVANTAGES', 'المزايا');
tz_def('TZ_AFTER_PAYMENT_YOU_WILL_BE_CREDITED', 'بعد الدفع، سيتم إضافة الرصيد إلى حسابك تلقائيًا.');
tz_def('TZ_AGRESOR', 'المعتدي');
tz_def('TZ_ALLIANCE_DIPLOMACY', 'دبلوماسية التحالف');
tz_def('TZ_ALLIANCE_EVENTS', 'أحداث التحالف');
tz_def('TZ_ALLIANCE_FORUM', 'منتدى التحالف');
tz_def('TZ_ALLIANCE_MEMBERS', 'أعضاء التحالف.');
tz_def('TZ_ALLY_CHAT', 'دردشة التحالف');
tz_def('TZ_AM', 'ص');
tz_def('TZ_AND_LATER_YOUR_VILLAGE_COULD_LOOK', '...وفي وقت لاحق يمكن أن تبدو قريتك هكذا.');
tz_def('TZ_AND_QUIT_THE_ALLIANCE_AFTERWARDS', 'ثم مغادرة التحالف بعد ذلك.');
tz_def('TZ_ASSIGN_RIGHTS', 'تعيين الصلاحيات');
tz_def('TZ_ATTENTION_USE_ONLY_TRUSTWORTHY_GRA', 'تنبيه! استخدم فقط حزم رسوميات موثوقة');
tz_def('TZ_AUTHOR', 'المؤلف');
tz_def('TZ_BEGINNERS_PROT', 'حماية المبتدئين');
tz_def('TZ_BEST_PLAYER', 'أفضل لاعب');
tz_def('TZ_BUILDING_SITE', 'موقع البناء');
tz_def('TZ_BUILD_A_PALACE_OR_RESIDENCE_TO_LEV', 'ابنِ قصرًا أو مقر إقامة إلى المستوى 10.');
tz_def('TZ_BUILD_CROPPER', 'بناء قرية محاصيل');
tz_def('TZ_BUY_IT_IN_THE_GOLD_SHOP', 'اشترِها من متجر الذهب');
tz_def('TZ_CELEBRATION_STILL_NEEDS', 'الاحتفال لا يزال يحتاج إلى:');
tz_def('TZ_CENTRE', 'المركز:');
tz_def('TZ_CHANGE_NAME', 'تغيير الاسم');
tz_def('TZ_CHANGE_YOUR_VILLAGE_S_NAME_TO_SOME', 'غيّر اسم قريتك إلى شيء لطيف.');
tz_def('TZ_CLAY_25_5_GOLD', 'طين +25% (5 ذهب)');
tz_def('TZ_CLOSED_FORUM', 'منتدى مغلق');
tz_def('TZ_CLOSE_ADRESSBOOK', 'إغلاق دفتر العناوين');
tz_def('TZ_COMBAT_SIMULATOR', 'محاكي القتال');
tz_def('TZ_COMPLETE_DEMOLITION_10', 'هدم كامل (10');
tz_def('TZ_CONFEDERATION_FORUM', 'منتدى الاتحاد الكونفدرالي');
tz_def('TZ_CONFIRM_WITH_PASSWORD', 'التأكيد بكلمة المرور:');
tz_def('TZ_CONSTRUCT_A_CRANNY', 'ابنِ مخبأً.');
tz_def('TZ_CONSTRUCT_A_GRANARY', 'ابنِ مخزنًا زراعيًا.');
tz_def('TZ_CONSTRUCT_A_RALLY_POINT', 'ابنِ نقطة تجمع.');
tz_def('TZ_CONSTRUCT_A_WOODCUTTER', 'ابنِ منشرة أخشاب.');
tz_def('TZ_CONSTRUCT_BARRACKS', 'ابنِ ثكنة.');
tz_def('TZ_CONSTRUCT_WAREHOUSE', 'ابنِ مخزنًا.');
tz_def('TZ_CP_DAY', 'نقطة ثقافة/يوم');
tz_def('TZ_CROP_25_5_GOLD', 'محاصيل +25% (5 ذهب)');
tz_def('TZ_DATE_AND_TIME', 'التاريخ والوقت');
tz_def('TZ_DEBUG_OFF', 'سجل التصحيح: متوقف');
tz_def('TZ_DEBUG_ON', 'سجل التصحيح: مفعّل');
tz_def('TZ_DECLARE_WAR', 'إعلان الحرب');
tz_def('TZ_DEFAULT', 'الافتراضي:');
tz_def('TZ_DELETE_ACCOUNT', 'حذف الحساب؟');
tz_def('TZ_DIFFERENT_EMAIL_ADDRESS', 'بريد إلكتروني مختلف');
tz_def('TZ_DOWNLOAD_FROM', 'التحميل من');
tz_def('TZ_DO_I_NEED_PLUS_TO_USE_OTHER_FEATUR', 'هل أحتاج إلى Plus لاستخدام الميزات الأخرى؟');
tz_def('TZ_EARN_GOLD', 'اكسب ذهبًا');
tz_def('TZ_EDIT_ANSWER', 'تعديل الإجابة');
tz_def('TZ_EDIT_ANSWER_2', 'تعديل الإجابة');
tz_def('TZ_EDIT_FORUM', 'تعديل المنتدى');
tz_def('TZ_EDIT_SLOT', 'تعديل الفتحة');
tz_def('TZ_EDIT_TOPIC', 'تعديل الموضوع');
tz_def('TZ_ENDS_ON', 'ينتهي في');
tz_def('TZ_ENTER_HOW_MUCH_LUMBER_THE_BARRACKS', 'أدخل تكلفة الثكنة من الخشب');
tz_def('TZ_EU_DD_MM_YY_24H', 'أوروبي (يوم.شهر.سنة 24 ساعة)');
tz_def('TZ_EXAMPLE', 'مثال:');
tz_def('TZ_EXISTING_RELATIONSHIPS', 'العلاقات الحالية');
tz_def('TZ_EXTEND_ALL_RESOURCE_TILES_TO_LEVEL', 'ارفع كل حقول الموارد إلى المستوى 1.');
tz_def('TZ_EXTEND_ALL_RESOURCE_TILES_TO_LEVEL_2', 'ارفع كل حقول الموارد إلى المستوى 2.');
tz_def('TZ_EXTEND_ONE_CLAY_PIT', 'ارفع حفرة طين واحدة.');
tz_def('TZ_EXTEND_ONE_CROPLAND', 'ارفع حقل محاصيل واحد.');
tz_def('TZ_EXTEND_ONE_IRON_MINE', 'ارفع منجم حديد واحد.');
tz_def('TZ_EXTEND_ONE_OF_EACH_RESOURCE_TILE_T', 'ارفع حقلًا واحدًا من كل مورد إلى المستوى 2.');
tz_def('TZ_EXTEND_YOUR_MAIN_BUILDING_TO_LEVEL', 'ارفع مبناك الرئيسي إلى المستوى 3.');
tz_def('TZ_FINISH', 'إنهاء');
tz_def('TZ_FOLLOWING_CAUSES_ARE_POSSIBLE', 'الأسباب المحتملة هي التالية:');
tz_def('TZ_FOLLOW_THIS_LINK_TO', 'اتبع هذا الرابط لـ');
tz_def('TZ_FOREIGN_OFFERS', 'العروض الخارجية');
tz_def('TZ_FORUM_TYPE', 'نوع المنتدى');
tz_def('TZ_FOUND_A_NEW_VILLAGE', 'أسّس قرية جديدة.');
tz_def('TZ_FREE', 'مجانًا!');
tz_def('TZ_FRIEND_EMAIL_COM', 'friend@email.com');
tz_def('TZ_GITHUB', 'Github');
tz_def('TZ_GRAPHIC_PACK_FOUND', 'تم العثور على حزمة الرسوميات.');
tz_def('TZ_GRAPHIC_PACK_SETTINGS', 'إعدادات حزمة الرسوميات');
tz_def('TZ_GREAT_STABLES', 'الإسطبل العظيم');
tz_def('TZ_HERE', 'هنا');
tz_def('TZ_HERE_YOU_CAN_KICK_THE_PLAYERS_FROM', 'هنا يمكنك طرد اللاعبين من تحالفك.');
tz_def('TZ_HERE_YOU_FIND_YOUR_RESOURCE_FIELDS', 'هنا تجد حقول مواردك');
tz_def('TZ_HINT', 'تلميح');
tz_def('TZ_HOW_DO_I_GET_GOLD', 'كيف أحصل على ذهب؟');
tz_def('TZ_INACTIVE_DURING_VACATION', 'غير نشط أثناء الإجازة');
tz_def('TZ_INFORMATION_ON_HAPPENINGS_IN_YOUR', 'معلومات عن الأحداث في قريتك');
tz_def('TZ_INITIATE_PAYMENT_BY_PAYPAL', 'بدء الدفع عبر PayPal');
tz_def('TZ_INVITATIONS', 'الدعوات:');
tz_def('TZ_INVITE_A_PLAYER_INTO_THE_ALLIANCE', 'ادعُ لاعبًا إلى التحالف');
tz_def('TZ_INVITE_BY_E_MAIL_OR_SHARE_YOUR_REF', 'ادعُ عبر البريد الإلكتروني أو شارك رابط الإحالة الخاص بك.');
tz_def('TZ_IN_DESCRIPTION', 'في الوصف.');
tz_def('TZ_IN_THE_VILLAGE_YOU_CAN_BUILD_BUILD', 'في القرية يمكنك بناء المباني');
tz_def('TZ_IRON_25_5_GOLD', 'حديد +25% (5 ذهب)');
tz_def('TZ_ISO_YY_MM_DD_24H', 'ISO (سنة/شهر/يوم 24 ساعة)');
tz_def('TZ_I_ACTIVATED_PLUS_BUT_PRODUCTION_DI', 'فعّلت Plus، لكن الإنتاج لم يزد.');
tz_def('TZ_JOIN_AN_ALLIANCE', 'انضم إلى تحالف');
tz_def('TZ_JOIN_AN_ALLIANCE_OR_FOUND_ONE_ON_Y', 'انضم إلى تحالف أو أسّس واحدًا بنفسك.');
tz_def('TZ_JUL', 'يوليو');
tz_def('TZ_JUN', 'يونيو');
tz_def('TZ_KICK_ALL_MEMBERS', 'طرد كل الأعضاء');
tz_def('TZ_KICK_PLAYER', 'طرد اللاعب:');
tz_def('TZ_LAST_POST', 'آخر مشاركة');
tz_def('TZ_LAST_RAID', 'آخر غارة');
tz_def('TZ_LINKS', 'الروابط:');
tz_def('TZ_LINK_TO_THE_FORUM', 'رابط المنتدى');
tz_def('TZ_LOG_IN', 'تسجيل الدخول');
tz_def('TZ_LUMBER_25_5_GOLD', 'خشب +25% (5 ذهب)');
tz_def('TZ_MAINTENANCE_OFF', 'الصيانة: متوقفة');
tz_def('TZ_MAINTENANCE_ON', 'الصيانة: مفعّلة');
tz_def('TZ_MAJOR_CHANGES', 'تغييرات رئيسية:');
tz_def('TZ_MAP_2', 'الخريطة:');
tz_def('TZ_MAXIMUM_VACATION', 'الحد الأقصى للإجازة:');
tz_def('TZ_MESSAGES', 'الرسائل:');
tz_def('TZ_MESSAGE_3', 'الرسالة');
tz_def('TZ_MILITARY_EVENTS', 'الأحداث العسكرية');
tz_def('TZ_MINIMUM_VACATION', 'الحد الأدنى للإجازة:');
tz_def('TZ_MINOR_CHANGES', 'تغييرات طفيفة:');
tz_def('TZ_MISCELLANEOUS', 'متنوعات');
tz_def('TZ_MORE_GRAPHIC_PACKS', 'المزيد من حزم الرسوميات');
tz_def('TZ_MORE_INFO', 'مزيد من المعلومات:');
tz_def('TZ_MOVE_TOPIC', 'نقل الموضوع');
tz_def('TZ_MULTIHUNTER', 'صياد الحسابات المتعددة:');
tz_def('TZ_NEW_FORUM', 'منتدى جديد');
tz_def('TZ_NONE_OF_THE_PACKAGES_ARE_REFUNDABL', 'لا يمكن استرداد أي من هذه الباقات!');
tz_def('TZ_NOT_ENOUGH_RESOURCE', 'الموارد غير كافية');
tz_def('TZ_NO_MARKETPLACE_ACTIVITY', 'لا يوجد نشاط في السوق');
tz_def('TZ_NO_OWNERSHIP_OF_AN_ARTIFACT_VILLAG', 'لا تملك قرية قطعة أثرية');
tz_def('TZ_NO_OWNERSHIP_OF_A_WONDER_OF_THE_WO', 'لا تملك قرية عجيبة من عجائب الدنيا');
tz_def('TZ_NO_REINFORCING_TROOPS_SENT_RECEIVE', 'لم تُرسَل أو تُستقبَل قوات تعزيز');
tz_def('TZ_NO_REPORTS_FOR_TRANSFERS_FROM_FORE', 'لا توجد تقارير لعمليات النقل من قرى خارجية.');
tz_def('TZ_NO_REPORTS_FOR_TRANSFERS_TO_FOREIG', 'لا توجد تقارير لعمليات النقل إلى قرى خارجية.');
tz_def('TZ_NO_REPORTS_FOR_TRANSFERS_TO_OWN_VI', 'لا توجد تقارير لعمليات النقل إلى قراك الخاصة.');
tz_def('TZ_N_14_DAYS', '14 يومًا');
tz_def('TZ_N_1_1_TRADE_WITH_THE_NPC_MERCHANT', 'تجارة 1:1 مع تاجر NPC');
tz_def('TZ_N_1_5_YOUR_VILLAGE', '(1/5) قريتك');
tz_def('TZ_N_1_CHOOSE_A_RESOURCE_FIELD', '1. اختر حقل موارد');
tz_def('TZ_N_1_CHOOSE_BUILDING_SITE', '1. اختر موقع البناء');
tz_def('TZ_N_2_5_RESOURCES', '(2/5) الموارد');
tz_def('TZ_N_2_CONSTRUCT_A_BUILDING', '2. ابنِ مبنى');
tz_def('TZ_N_2_DAYS', 'يومان');
tz_def('TZ_N_2_EXTEND_THE_RESOURCE_FIELD', '2. ارفع حقل الموارد');
tz_def('TZ_N_3_5_BUILDINGS', '(3/5) المباني');
tz_def('TZ_N_4_5_NEIGHBOURS', '(4/5) الجيران');
tz_def('TZ_N_5_5_NAVIGATION', '(5/5) التنقّل');
tz_def('TZ_OFFER_A_CONFEDERATION', 'عرض اتحاد كونفدرالي');
tz_def('TZ_OFFER_NON_AGGRESSION_PACT', 'عرض اتفاقية عدم اعتداء');
tz_def('TZ_OK_3', 'موافق');
tz_def('TZ_ONLINE_USERS', 'المستخدمون المتصلون');
tz_def('TZ_OPTION_1', 'الخيار 1:');
tz_def('TZ_OPTION_2', 'الخيار 2:');
tz_def('TZ_OPTION_3', 'الخيار 3:');
tz_def('TZ_OPTION_4', 'الخيار 4:');
tz_def('TZ_OPTION_5', 'الخيار 5:');
tz_def('TZ_OPTION_6', 'الخيار 6:');
tz_def('TZ_OPTION_7', 'الخيار 7:');
tz_def('TZ_OPTION_8', 'الخيار 8:');
tz_def('TZ_ORDERED_PACKAGE', 'الباقة المطلوبة');
tz_def('TZ_OR_ASK_THE_SERVER_OWNER', 'أو اسأل مالك السيرفر.');
tz_def('TZ_OWN_TEXT', 'نص خاص:');
tz_def('TZ_PALACE_RESIDENCE', 'القصر/مقر الإقامة');
tz_def('TZ_PASSWORD', 'كلمة المرور:');
tz_def('TZ_PAYMENT_ACCOUNT', 'حساب الدفع');
tz_def('TZ_PAYPAL', 'PayPal');
tz_def('TZ_PAYPAL_PACKAGE_A', 'PayPal – الباقة A');
tz_def('TZ_PAYPAL_PACKAGE_B', 'PayPal – الباقة B');
tz_def('TZ_PAYPAL_PACKAGE_C', 'PayPal – الباقة C');
tz_def('TZ_PAYPAL_PACKAGE_D', 'PayPal – الباقة D');
tz_def('TZ_PAYPAL_PACKAGE_E', 'PayPal – الباقة E');
tz_def('TZ_PLAY_NO_TASKS', 'اللعب بدون مهام.');
tz_def('TZ_PLEASE_BUILD_A_MARKETPLACE', 'يرجى بناء سوق.');
tz_def('TZ_PLUS_FUNCTIONS', 'ميزات Plus');
tz_def('TZ_PM', 'م');
tz_def('TZ_POP', 'السكان');
tz_def('TZ_POSITION', 'المنصب:');
tz_def('TZ_PRODUCTION_CLAY', 'الإنتاج: طين');
tz_def('TZ_PRODUCTION_CROP', 'الإنتاج: محاصيل');
tz_def('TZ_PRODUCTION_IRON', 'الإنتاج: حديد');
tz_def('TZ_PRODUCTION_LUMBER', 'الإنتاج: خشب');
tz_def('TZ_PUBLIC_FORUM', 'منتدى عام');
tz_def('TZ_RAGEZONE_COM', 'RageZone.com');
tz_def('TZ_RANKING_OF_ALL_PLAYERS', 'ترتيب جميع اللاعبين');
tz_def('TZ_RATIO', 'النسبة');
tz_def('TZ_READ_YOUR_NEW_MESSAGE', 'اقرأ رسالتك الجديدة.');
tz_def('TZ_REGISTERED', 'مسجَّل');
tz_def('TZ_REGISTERED_PLAYERS', 'اللاعبون المسجَّلون');
tz_def('TZ_RELEASED_BY_NOVATERRA_TEAM', 'إصدار: فريق نوفاتيرا');
tz_def('TZ_RELEASE_BY_NOVATERRA', '[إصدار: نوفاتيرا]');
tz_def('TZ_REPLIES', 'الردود');
tz_def('TZ_REPORTS', 'التقارير:');
tz_def('TZ_REQUIREMENTS', 'المتطلبات');
tz_def('TZ_SCOUT_DEFENCES_AND_TROOPS', 'استطلاع الدفاعات والقوات');
tz_def('TZ_SCOUT_RESOURCES_AND_TROOPS', 'استطلاع الموارد والقوات');
tz_def('TZ_SCRIPT_PRICE', 'سعر السكربت:');
tz_def('TZ_SELECT_ALL', 'تحديد الكل');
tz_def('TZ_SELECT_REWARD', 'اختر مكافأة...');
tz_def('TZ_SELECT_REWARD_2', 'اختر المكافأة:');
tz_def('TZ_SEND_200_CROP_TO_THE_TASKMASTER', 'أرسل 200 محاصيل إلى مسؤول المهام.');
tz_def('TZ_SEND_AND_RECEIVE_MESSAGES', 'إرسال واستقبال الرسائل');
tz_def('TZ_SEND_UNITS_BACK', 'إعادة الوحدات');
tz_def('TZ_SERVER_START', 'بداية السيرفر');
tz_def('TZ_SHOW_THE_LARGE_MAP_IN_AN_EXTRA_WIN', 'إظهار الخريطة الكبيرة في نافذة إضافية.');
tz_def('TZ_SIZE_IN_MB', 'الحجم بالميجابايت');
tz_def('TZ_SLOTS', 'الفتحات');
tz_def('TZ_START_RAID', 'بدء الغارة');
tz_def('TZ_STATISTICS', 'الإحصائيات:');
tz_def('TZ_SUPPORT', 'الدعم:');
tz_def('TZ_SUPPORT_AND_MULTIHUNTER', 'الدعم وصياد الحسابات المتعددة');
tz_def('TZ_SURVEY', 'استطلاع');
tz_def('TZ_TARIFFS', 'الأسعار');
tz_def('TZ_TASK_7_HUGE_ARMY', 'المهمة 7: جيش ضخم!');
tz_def('TZ_TASK_8_EVERYTHING_TO_1', 'المهمة 8: كل شيء إلى 1.');
tz_def('TZ_THANK_YOU_FOR_USING_OUR_VERSION', 'شكرًا لاستخدامك نسختنا!');
tz_def('TZ_THERE_ARE_NO_INCOMING_TROOPS', 'لا توجد قوات قادمة');
tz_def('TZ_THERE_ARE_NO_OUTGOING_TROOPS', 'لا توجد قوات خارجة');
tz_def('TZ_THE_BEST_ALLIANCES_DEF', 'أفضل التحالفات (دفاع)');
tz_def('TZ_THE_BEST_ALLIANCES_OFF', 'أفضل التحالفات (هجوم)');
tz_def('TZ_THE_BUILDING_WAS_COMPLETELY_DEMOLI', 'تم هدم المبنى بالكامل مقابل 10 ذهب!');
tz_def('TZ_THE_EMAIL_ACCOUNT_S_STORAGE_LIMIT', 'تم الوصول إلى الحد الأقصى لتخزين حساب البريد الإلكتروني');
tz_def('TZ_THE_EMAIL_HAS_BEEN_MOVED_TO_THE_SP', 'تم نقل البريد الإلكتروني إلى مجلد البريد المزعج');
tz_def('TZ_THE_EMAIL_WILL_BE_SENT_TO_FOLLOWIN', 'سيتم إرسال البريد الإلكتروني إلى العنوان التالي:');
tz_def('TZ_THE_E_MAIL_ADDRESS_OF_THE_NEW_OWNE', 'البريد الإلكتروني للمالك الجديد.');
tz_def('TZ_THE_GAME_WORLD_ON_WHICH_THE_ACCOUN', 'عالم اللعبة الذي يوجد فيه الحساب');
tz_def('TZ_THE_HERO', 'البطل');
tz_def('TZ_THE_LARGEST_GAULS', 'أكبر الغال');
tz_def('TZ_THE_LARGEST_PLAYERS', 'أكبر اللاعبين');
tz_def('TZ_THE_LARGEST_ROMANS', 'أكبر الرومان');
tz_def('TZ_THE_LARGEST_TEUTONS', 'أكبر التيوتون');
tz_def('TZ_THE_LARGEST_HUNS', 'أكبر الهون');
tz_def('TZ_THE_LARGEST_EGYPTIANS', 'أكبر المصريين');
tz_def('TZ_THE_LARGEST_SPARTANS', 'أكبر الإسبرطيين');
tz_def('TZ_THE_LARGEST_VIKINGS', 'أكبر الفايكنج');
tz_def('TZ_THE_LARGEST_VILLAGES', 'أكبر القرى');
tz_def('TZ_THE_MOST_EXPERIENCED_HEROES', 'أكثر الأبطال خبرة');
tz_def('TZ_THE_MOST_SUCCESSFUL_ATTACKERS', 'أكثر المهاجمين نجاحًا');
tz_def('TZ_THE_MOST_SUCCESSFUL_DEFENDERS', 'أكثر المدافعين نجاحًا');
tz_def('TZ_THE_MULTIHUNTERS_ARE_RESPONSIBLE_F', 'صيادو الحسابات المتعددة مسؤولون عن الامتثال لـ');
tz_def('TZ_THE_NAVIGATION_BAR', 'شريط التنقّل');
tz_def('TZ_THE_NICKNAME_OF_THE_ACCOUNT', 'اسم مستخدم الحساب');
tz_def('TZ_THE_PATH', 'المسار');
tz_def('TZ_THE_VILLAGE', 'القرية');
tz_def('TZ_THIS_FEATURE_IS_NOT_INCLUDED_IN_TH', 'هذه الميزة غير مشمولة في نادي الذهب!');
tz_def('TZ_THIS_IS_HOW_YOU_START', 'هكذا تبدأ...');
tz_def('TZ_THREADS', 'المواضيع');
tz_def('TZ_TIME_PREFERENCE', 'تفضيل الوقت');
tz_def('TZ_TIME_ZONES', 'المناطق الزمنية');
tz_def('TZ_TIP', 'نصيحة');
tz_def('TZ_TOP_10_ALLIANCES', 'أفضل 10 تحالفات');
tz_def('TZ_TOP_10_PLAYERS', 'أفضل 10 لاعبين');
tz_def('TZ_TOTAL_POPULATION', 'إجمالي السكان');
tz_def('TZ_TOTAL_VILLAGES', 'إجمالي القرى');
tz_def('TZ_TO_THE_FIRST_TASK', 'إلى المهمة الأولى.');
tz_def('TZ_TO_THE_REGISTRATION', 'إلى التسجيل');
tz_def('TZ_TRAIN_3_SETTLERS', 'درّب 3 مستوطنين.');
tz_def('TZ_NOVATERRA_DEFAULT', 'نوفاتيرا الافتراضي');
tz_def('TZ_NOVATERRA_GOLD_CLUB', 'نادي نوفاتيرا الذهبي');
tz_def('TZ_NOVATERRA_T4_STYLE', 'نمط نوفاتيرا T4');
tz_def('TZ_TRIBES', 'القبائل');
tz_def('TZ_TYPOS_IN_THE_EMAIL_ADDRESS', 'أخطاء إملائية في البريد الإلكتروني');
tz_def('TZ_UK_DD_MM_YY_12H', 'بريطاني (يوم/شهر/سنة 12 ساعة)');
tz_def('TZ_UPGRADE_ALL_RESOURCES_TILES_TO_LEV', 'ارفع كل حقول الموارد إلى المستوى 5.');
tz_def('TZ_UPGRADE_YOUR_GRANARY_TO_LEVEL_3', 'ارفع مخزنك الزراعي إلى المستوى 3.');
tz_def('TZ_UPGRADE_YOUR_MAIN_BUILDING_TO_LEVE', 'ارفع مبناك الرئيسي إلى المستوى 5.');
tz_def('TZ_UPGRADE_YOUR_WAREHOUSE_TO_LEVEL_7', 'ارفع مخزنك إلى المستوى 7.');
tz_def('TZ_USE', 'استخدام');
tz_def('TZ_USED_FOR_RALLY_POINT_AND_MARKETPLA', 'يُستخدم لنقطة التجمع والسوق:');
tz_def('TZ_USERNAME', 'اسم المستخدم');
tz_def('TZ_USER_DEFINED_GRAPHIC_PACK', 'حزمة رسوميات معرّفة من المستخدم');
tz_def('TZ_USE_IT_FOR_PLUS_OR_ANY_ADVANTAGE', '. استخدمه لـ Plus أو أي ميزة أخرى.');
tz_def('TZ_US_MM_DD_YY_12H', 'أمريكي (شهر/يوم/سنة 12 ساعة)');
tz_def('TZ_UTC_1', 'UTC+1');
tz_def('TZ_UTC_10', 'UTC+10');
tz_def('TZ_UTC_10_2', 'UTC-10');
tz_def('TZ_UTC_11', 'UTC+11');
tz_def('TZ_UTC_11_2', 'UTC-11');
tz_def('TZ_UTC_12', 'UTC+12');
tz_def('TZ_UTC_1_2', 'UTC-1');
tz_def('TZ_UTC_2', 'UTC+2');
tz_def('TZ_UTC_2_2', 'UTC-2');
tz_def('TZ_UTC_3', 'UTC+3');
tz_def('TZ_UTC_3_2', 'UTC-3');
tz_def('TZ_UTC_4', 'UTC+4');
tz_def('TZ_UTC_4_2', 'UTC-4');
tz_def('TZ_UTC_5', 'UTC+5');
tz_def('TZ_UTC_5_2', 'UTC-5');
tz_def('TZ_UTC_6', 'UTC+6');
tz_def('TZ_UTC_6_2', 'UTC-6');
tz_def('TZ_UTC_7', 'UTC+7');
tz_def('TZ_UTC_7_2', 'UTC-7');
tz_def('TZ_UTC_8', 'UTC+8');
tz_def('TZ_UTC_8_2', 'UTC-8');
tz_def('TZ_UTC_9', 'UTC+9');
tz_def('TZ_UTC_9_2', 'UTC-9');
tz_def('TZ_VERSION', 'الإصدار:');
tz_def('TZ_VILLAGE_EXP', 'خبرة القرية');
tz_def('TZ_VILLAGE_YOU_GET', 'قرية، تحصل على');
tz_def('TZ_VILLAGE_YOU_WILL_BE_CREDITED_WITH', 'قرية، سيتم إضافة رصيدك بمقدار');
tz_def('TZ_VIP_ACCOUNT_10_GOLD_7_DAYS', 'حساب VIP (10 ذهب – 7 أيام)');
tz_def('TZ_VISIT', 'زيارة:');
tz_def('TZ_VOTE', 'تصويت');
tz_def('TZ_WAIT_24H', 'الانتظار: 24 ساعة');
tz_def('TZ_WAIT_INSTANT_AFTER_IPN', 'الانتظار: فوري بعد IPN');
tz_def('TZ_WARNING_CATAPULT_WILL', 'تحذير: سيقوم المنجنيق بـ');
tz_def('TZ_WE_STRIVE_TO_ENSURE_SPEEDY_PROCESS', 'نسعى لضمان معالجة سريعة!');
tz_def('TZ_WHY_CAN_T_I_FINISH_SOME_BUILDINGS', 'لماذا لا أستطيع إنهاء بعض المباني بالذهب؟');
tz_def('TZ_WILL_BE_ATTACKED_BY_CATAPULT_S', '(سيتم مهاجمته بواسطة المنجنيق(المنجنيقات))');
tz_def('TZ_WILL_SPAWN_IN', 'سيظهر خلال:');
tz_def('TZ_WILL_SPAWN_IN_ARTIFACTS', 'القطع الأثرية');
tz_def('TZ_WILL_SPAWN_IN_WW', 'قرى عجائب الدنيا');
tz_def('TZ_WILL_SPAWN_IN_PLAN', 'مخططات بناء عجائب الدنيا');
tz_def('TZ_WOODCUTTER_INSTANTLY_COMPLETED', 'تم إنهاء منشرة الأخشاب فورًا.');
tz_def('TZ_WORLD_STATS', 'إحصائيات العالم');
tz_def('TZ_WRITE_THE_CODE', 'اكتب الرمز');
tz_def('TZ_WRONG_DOMAIN_THERE_IS_E_G_NO_AOL_D', 'نطاق خاطئ: مثلًا لا يوجد @aol.de، فقط @aol.com');
tz_def('TZ_YOUR_ACCOUNT_HAS_BEEN_SUCCESSFULLY', 'تم تفعيل حسابك بنجاح.');
tz_def('TZ_YOUR_VILLAGE_AND_YOUR_NEIGHBOURS', 'قريتك وجيرانك');
tz_def('TZ_YOU_CAN_UNDO_THE_REGISTRATION_AND', 'يمكنك التراجع عن التسجيل وإعادة التسجيل بـ');
tz_def('TZ_YOU_CAN_USE_THIS_GOLD_FOR_PLUS_OR', '. يمكنك استخدام هذا الذهب لـ Plus أو أي ميزة ذهب أخرى.');

// ===== Server Milestones (NEW_FUNCTIONS_MILESTONES) =====
tz_def('TZ_SERVER_MILESTONES', 'إنجازات السيرفر');
tz_def('TZ_MILESTONE_NOT_YET', 'لم يتحقق بعد');
tz_def('TZ_MILESTONE_SECOND_VILLAGE', 'أول من أسّس قرية ثانية');
tz_def('TZ_MILESTONE_POPULATION_1000', 'أول من وصل إلى 1,000 نسمة');
tz_def('TZ_MILESTONE_FIRST_ARTIFACT', 'أول من استولى على قطعة أثرية');
tz_def('TZ_MILESTONE_FIRST_WW', 'أول من احتلّ عجيبة من عجائب الدنيا');
tz_def('TZ_MILESTONE_FIRST_WW_PLAN', 'أول من احتلّ مخطط بناء عجيبة من عجائب الدنيا');
tz_def('TZ_MILESTONE_FIRST_ALLIANCE', 'أول تحالف تأسّس');
tz_def('TZ_MILESTONE_FIRST_PVP_CONQUEST', 'أول قرية تم احتلالها من لاعب');
tz_def('TZ_MILESTONE_FIVE_VILLAGES', 'أول من أسّس 5 قرى');
tz_def('TZ_MILESTONE_FOUNDED_BY', 'أسّسها');
tz_def('TZ_MILESTONE_HERO_MASTER', 'أول سيّد أبطال (تجهيزات كاملة من الفئة 3)');

// ===== i18n etape 2 (lot suivant) =====
tz_def('TZ_ACCOUNT_OR_INCREASE_YOUR_RESOURCE', '-الحساب أو زيادة إنتاج مواردك. للقيام بذلك انقر');
tz_def('TZ_ADDITIONALLY_THE_NOVATERRA_TEAM_WILL', 'بالإضافة إلى ذلك، لن يقدّم فريق نوفاتيرا معلومات عن الحظر لأي شخص سوى مالك الحساب.');
tz_def('TZ_ADVERTISEMENT_OF_ANY_KIND_THAT_HAS', 'الإعلانات من أي نوع لم يُسمح بها من فريق نوفاتيرا غير مسموح بها.');
tz_def('TZ_AFTERWARDS_BOTH_PARTIES_MUST_REQUE', 'بعد ذلك، يجب على كلا الطرفين طلب كلمة مرور حسابهما الجديد عبر وظيفة استعادة كلمة المرور.');
tz_def('TZ_AFTER_TAKING_CARE_OF_YOUR_RESOURCE', 'بعد الاهتمام بمصدر مواردك، يمكنك البدء بتوسيع قريتك.');
tz_def('TZ_ANY_SALES_OR_PURCHASES_CONCERNING', 'غير مسموح بأي بيع أو شراء بأموال حقيقية يخص الحسابات، الوحدات، القرى، الموارد، الخدمات أو أي جانب آخر من نوفاتيرا. بيع حسابات نوفاتيرا وكذلك أي نقل غير مباشر (حتى كهدايا) مرتبط بمواقع المزادات أو معاملات مالية أخرى غير مسموح به.');
tz_def('TZ_AS_A_LEADER_YOU_CAN_ONLY_CHANGE_YO', 'بصفتك قائدًا، يمكنك فقط تغيير لقبك. صلاحياتك تبقى عند أقصى حد.');
tz_def('TZ_A_SITTER_CAN_LOG_INTO_YOUR_ACCOUNT', 'يمكن للوكيل تسجيل الدخول إلى حسابك باستخدام اسمك وكلمة مروره. يمكنك تعيين ما يصل إلى وكيلين لحسابك.');
tz_def('TZ_A_WAREHOUSE_AND_A_GRANARY_ENABLE_Y', 'المخزن والمخزن الزراعي يتيحان لك تخزين المزيد من الموارد. المخبأ يحمي مواردك من السرقة على يد الغزاة الأعداء.');
tz_def('TZ_BECAUSE_YOU_ARE_THE_ALLIANCE_FOUND', 'بما أنك مؤسس التحالف، يجب عليك اختيار مؤسس بديل قبل مغادرتك.');
tz_def('TZ_BEFORE_YOU_EXPAND_YOUR_VILLAGE_S_B', 'قبل توسيع مباني قريتك، يجب عليك تطوير بعض حقول الموارد لزيادة مصدر مواردك.');
tz_def('TZ_BLACKMAILING_PLAYERS_IN_A_WAY_THAT', 'ابتزاز اللاعبين بطريقة تخالف أيًا من قواعد نوفاتيرا وفقًا للشروط والأحكام العامة.');
tz_def('TZ_COMPLETE_CONSTRUCTION_ORDERS_AND_R', 'أكمل أوامر البناء والأبحاث في هذه القرية الآن');
tz_def('TZ_DISPLAYING_BATTLE_REPORTS_OR_MESSA', 'عرض تقارير المعارك أو الرسائل علنًا دون موافقة كلا الطرفين المعنيين.');
tz_def('TZ_EACH_PLAYER_MAY_ONLY_OWN_AND_PLAY', 'يجوز لكل لاعب امتلاك ولعب حساب واحد فقط لكل سيرفر.');
tz_def('TZ_ENGLISH_IS_THE_ONLY_LANGUAGE_TOLER', 'الإنجليزية هي اللغة الوحيدة المسموح بها في الرسائل والأوصاف.');
tz_def('TZ_FOLLOWING_BEHAVIOR_IS_PUNISHABLE_A', 'السلوك التالي يستوجب العقاب وينطبق على جميع الأوصاف، اسم الحساب، أسماء التحالفات، أسماء القرى والرسائل:');
tz_def('TZ_HERE_YOU_CAN_CHANGE_NOVATERRA_S_DISP', 'هنا يمكنك تغيير الوقت المعروض في نوفاتيرا ليتناسب مع منطقتك الزمنية.');
tz_def('TZ_HERE_YOU_CAN_HAVE_A_LOOK_AT_YOUR_V', 'هنا يمكنك إلقاء نظرة على المنطقة المحيطة بقريتك وجيرانك');
tz_def('TZ_HOWEVER_IT_IS_PERMISSIBLE_TO_TRANS', 'ومع ذلك، يُسمح بنقل كلمة مرور حساب إلى شخص أو أشخاص يلعبون على عالم لعبة مختلف (أو لا يلعبون على الإطلاق) من أجل لعب حساب واحد معًا.');
tz_def('TZ_IF_INDIVIDUAL_REGULATIONS_OF_THIS', 'إذا ثبت أن لوائح فردية من مجموعة القواعد هذه غير فعّالة بأي شكل، فذلك لا يؤثر على صلاحية باقي اللوائح في مجموعة القواعد هذه. يلتزم المسؤولون باستبدال اللوائح غير الفعّالة بلوائح جديدة تحل محلها في أسرع وقت ممكن.');
tz_def('TZ_IF_THERE_IS_AN_OFFENCE_AGAINST_THE', 'في حال وجود مخالفة لقواعد اللعبة هذه، سيقوم صيادو الحسابات المتعددة، وعند الضرورة المسؤولون، بحظر الحساب (الحسابات) المعنية وتحديد العقوبة المناسبة. ستتجاوز العقوبات دائمًا الفائدة المكتسبة من مخالفة القواعد.');
tz_def('TZ_IF_YOUR_ALLIANCE_WANTS_TO_USE_AN_E', 'إذا أراد تحالفك استخدام منتدى خارجي، يمكنك إدخال الرابط هنا.');
tz_def('TZ_IMPERSONATING_OFFICIALS_OR_OFFICIA', 'انتحال شخصية المسؤولين أو المناصب الرسمية أمر غير قانوني بأي شكل من الأشكال.');
tz_def('TZ_INCITING_MANIPULATING_ENCOURAGING', 'التحريض أو التلاعب أو التشجيع أو المساعدة أو التآمر مع آخرين لمخالفة أي من قواعد نوفاتيرا غير مسموح به. تنطبق هذه القواعد على اللاعبين الذين سيحذفون حساباتهم أو يقومون حاليًا بحذفها دون استثناء.');
tz_def('TZ_INTO_YOUR_PROFILE_BY_ADDING_IT_TO', 'إلى ملفك الشخصي عن طريق إضافته إلى أحد حقلي الوصف.');
tz_def('TZ_IN_ORDER_TO_ACTIVATE_YOUR_ACCOUNT', 'لتفعيل حسابك، أدخل الرمز أو انقر على الرابط في بريدك الإلكتروني.');
tz_def('TZ_IN_ORDER_TO_PLAY_NOVATERRA_YOU_NEED', 'للعب نوفاتيرا، تحتاج إلى بريد إلكتروني صالح يمكن إرسال رمز التفعيل إليه. هناك حالات استثنائية قد لا يصل فيها هذا البريد الإلكتروني.');
tz_def('TZ_IN_ORDER_TO_QUIT_THE_ALLIANCE_YOU', 'لمغادرة التحالف، يجب عليك إدخال كلمة مرورك مرة أخرى لأسباب أمنية.');
tz_def('TZ_IN_ORDER_TO_SWITCH_AN_ACCOUNT_WITH', 'لتبديل حساب مع شخص آخر على نفس عالم اللعبة، يجب على كلا الشخصين إرسال رسالة بريد إلكتروني إلى admin@novaterra.com من عنوان البريد الإلكتروني المسجّل حاليًا للحساب. يجب أن تحتوي الرسالة على المعلومات التالية:');
tz_def('TZ_IN_THE_BEGINNING_YOUR_SMALL_VILLAG', 'في البداية، ستحتوي قريتك الصغيرة على مبنى واحد فقط.');
tz_def('TZ_IN_NOVATERRA_YOU_ARE_NOT_ALONE_YOU_I', 'في نوفاتيرا لست وحدك؛ فأنت تتفاعل مع آلاف اللاعبين الآخرين في عالم نوفاتيرا.');
tz_def('TZ_IT_S_PART_OF_DIPLOMATIC_ETIQUETTE', 'من آداب الدبلوماسية التحدث إلى تحالف آخر قبل إرسال عرض.');
tz_def('TZ_MULTIACCOUNTS_ON_THE_SPEED_SERVER', 'قد يتم حذف الحسابات المتعددة على السيرفر السريع والحسابات المتعددة التي يقل عدد سكانها عن 100 فور اكتشافها دون إنذار مسبق.');
tz_def('TZ_NOW_YOU_HAVE_FULFILLED_ALL_PREREQU', 'الآن استوفيت جميع المتطلبات اللازمة لبناء سوق.');
tz_def('TZ_NOW_YOU_KNOW_EVERYTHING_IMPORTANT', 'الآن تعرف كل ما هو مهم عن نوفاتيرا. بعد التسجيل يمكنك البدء باللعب!');
tz_def('TZ_NO_EVERY_GOLD_FEATURE_WORKS_STANDA', 'لا. كل ميزة ذهب تعمل بشكل مستقل طالما لديك ذهب كافٍ.');
tz_def('TZ_NO_REAL_WORLD_POLITICS_ARE_ALLOWED', 'لا يُسمح بسياسة العالم الحقيقي في الأسماء والرسائل والأوصاف.');
tz_def('TZ_PARTICIPATION_IN_ABUSIVE_DEFAMATOR', 'المشاركة في لغة مسيئة، تشهيرية، متحيزة جنسيًا، عنصرية أو بذيئة؛ التقليل من شأن أي دين، عرق، أمة، جنس، فئة عمرية، أو ميول جنسية؛ تهديد شخص بأفعال في الحياة الواقعية.');
tz_def('TZ_PLAYERS_MAY_TALK_TO_THE_MULTIHUNTE', 'يجوز للاعبين التحدث مع صياد الحسابات المتعددة الذي حظرهم أو مع أحد المسؤولين عبر الرسائل داخل اللعبة أو البريد الإلكتروني. لا يجوز مناقشة الحظر أو العقوبات أو الحذف علنًا (مثل الدردشة أو المنتديات). يجب كتابة الاستئنافات باللغة الإنجليزية.');
tz_def('TZ_PLEASE_ENTER_YOUR_OLD_AND_YOUR_NEW', 'يرجى إدخال عنوان بريدك الإلكتروني القديم والجديد. ستتلقى بعدها رمزًا في كلا العنوانين يجب عليك إدخاله هنا.');
tz_def('TZ_PLUS_DOES_NOT_INCLUDE_PRODUCTION_B', 'لا يشمل Plus مكافآت الإنتاج. يجب عليك شراء +25% لكل مورد على حدة في');
tz_def('TZ_PROGRAM_ERRORS_ALSO_CALLED_BUGS_MA', 'لا يجوز استغلال أخطاء البرنامج (المعروفة أيضًا بالأخطاء البرمجية) لتحقيق فائدة. قد يؤدي إساءة الاستخدام إلى معاقبة الحساب.');
tz_def('TZ_RESIDENCE_PALACE_AND_WORLD_WONDER', 'قرى مقر الإقامة، القصر، وعجيبة الدنيا مستثناة لأسباب تتعلق بأسلوب اللعب.');
tz_def('TZ_RESOURCES_BUILDINGS_VILLAGES_OR_TR', 'الموارد أو المباني أو القرى أو القوات المفقودة أثناء فترة التعليق لا تُحتسب كعقوبة ولن يتم تعويضها من فريق نوفاتيرا. لا يحق لأي لاعب المطالبة بدفع أو تعويض عن وقت Plus/الذهب المفقود بسبب التعليق.');
tz_def('TZ_SHOOT_WITH_A_NORMAL_ATTACK_THEY_DO', 'يطلق بهجوم عادي (لا يطلق مع الغارات!)');
tz_def('TZ_SOMETIMES_THE_EMAIL_IS_MOVED_TO_TH', 'أحيانًا يتم نقل البريد الإلكتروني إلى مجلد البريد المزعج. لمزيد من المساعدة انقر');
tz_def('TZ_THERE_ARE_FOUR_DIFFERENT_TYPES_OF', 'يوجد أربعة أنواع مختلفة من الموارد في نوفاتيرا: الخشب، الطين، الحديد والمحاصيل.');
tz_def('TZ_THERE_IS_NO_COMPENSATION_FOR_DAMAG', 'لا يوجد تعويض عن الأضرار التي يسببها الوكيل. أصحاب الحسابات مسؤولون بالكامل عن الأفعال التي يقوم بها وكلاء حساباتهم المختارون. في حال عدم التزام أحد الوكلاء بهذه القواعد والشروط والأحكام العامة لنوفاتيرا، يجوز محاسبة كل من صاحب الحساب والوكيل ومعاقبتهما.');
tz_def('TZ_THERE_IS_NO_COMPENSATION_FOR_DAMAG_2', 'لا يوجد تعويض عن الأضرار التي يسببها شخص يعرف كلمة مرور حساب. يخضع الشخص المستلم لكلمة المرور لقواعد نوفاتيرا وكذلك الشروط والأحكام العامة.');
tz_def('TZ_THERE_IS_NO_SPECIAL_TREATMENT_FOR', 'لا توجد معاملة خاصة لمستخدمي نوفاتيرا Plus/الذهب فيما يخص قواعد اللعبة، لا في الوقت اللازم لمعالجة الحالة ولا في العقوبة.');
tz_def('TZ_THE_E_MAIL_ADDRESS_USED_FOR_THE_RE', 'يجب أن يكون عنوان البريد الإلكتروني المستخدم لتسجيل الحساب تحت السيطرة الشخصية والحصرية للشخص الذي سجّل الحساب. الشخص الذي يملك عنوان البريد الإلكتروني المسجّل حاليًا لحساب ما يُعتبر مالك الحساب، بغض النظر عن أي اتفاقيات شخصية أو تحالفية أخرى. مالك الحساب مسؤول بالكامل عن جميع الإجراءات التي يقوم بها الحساب.');
tz_def('TZ_THE_FOLLOWING_SET_OF_RULES_ARE_IN', 'مجموعة القواعد التالية مرتبطة بالشروط والأحكام العامة لنوفاتيرا. يجب عليك الاطلاع على الشروط والأحكام العامة للتحقق مما هو مسموح وما هو ممنوع، خاصة في حالة حساب تم حظره لمخالفة قاعدة ما.');
tz_def('TZ_THE_GAME_MUST_BE_PLAYED_WITH_AN_UN', 'يجب لعب اللعبة باستخدام متصفح إنترنت غير معدَّل. استخدام السكربتات أو البوتات التي تُؤتمت إجراءات الحساب يخالف القواعد.');
tz_def('TZ_THE_OWNER_OF_AN_ACCOUNT_MAY_NOT_TR', 'لا يجوز لمالك الحساب نقل كلمة مرور الحساب إلى أي شخص يلعب على نفس عالم اللعبة (السيرفر). بالإضافة إلى ذلك، اختيار نفس كلمة المرور عمدًا على نفس عالم اللعبة كشخص آخر أمر غير قانوني؛ وتُعتبر أي من هذه الأفعال حسابات متعددة، كما هو محدد في هذه القواعد.');
tz_def('TZ_THE_PLAYERS_IN_YOUR_SURROUNDING_AR', 'اللاعبون في محيطك هم الأكثر أهمية بالنسبة لك. بفضل الخريطة لديك نظرة عامة جيدة عمّن هم.');
tz_def('TZ_THE_REGISTRATION_WAS_SUCCESSFUL_IN', 'تم التسجيل بنجاح. في الدقائق القليلة القادمة ستتلقى بريدًا إلكترونيًا يحتوي على معلومات الوصول.');
tz_def('TZ_THE_SUPPORT_IS_A_GROUP_OF_EXPERIEN', 'الدعم هو مجموعة من اللاعبين ذوي الخبرة الذين سيجيبون على أسئلتك بكل سرور.');
tz_def('TZ_THE_NOVATERRA_TEAM_RESERVES_THE_RIGH', 'يحتفظ فريق نوفاتيرا بحق تغيير القواعد في أي وقت.');
tz_def('TZ_TO_BRING_IN_NEW_PLAYERS_INVITE_THE', 'لجلب لاعبين جدد، ادعُهم عبر البريد الإلكتروني أو شارك رابط الإحالة الخاص بك.');
tz_def('TZ_VACATION_MODE_CANNOT_BE_ACTIVATED', 'لا يمكن تفعيل وضع الإجازة – المتطلبات غير مستوفاة');
tz_def('TZ_WE_WILL_SHOW_YOU_HOW_TO_EXPAND_YOU', 'سنُريك كيفية توسيع قريتك لتصبح مدينة قوية ومزدهرة في الصفحة التالية.');
tz_def('TZ_YOU_CAN_DELETE_YOUR_ACCOUNT_HERE_A', 'يمكنك حذف حسابك هنا. بعد بدء عملية الإلغاء ستستغرق ثلاثة أيام لإتمام إلغاء حسابك. يمكنك إلغاء هذه العملية خلال أول 24 ساعة.');
tz_def('TZ_YOU_DON_T_HAVE_ENOUGH_GOLD_YOU_NEE', 'ليس لديك ذهب كافٍ. تحتاج إلى 10 ذهب للهدم الفوري.');
tz_def('TZ_YOU_HAVE_BEEN_ENTERED_AS_SITTER_ON', 'تم تسجيلك كوكيل على الحسابات التالية. يمكنك إلغاء ذلك بالنقر على X الأحمر.');
// ===== i18n composites (Simulateur) =====
tz_def('TZ_NUMBER', 'رقم');
tz_def('TZ_LVL', 'المستوى');

// ===== i18n reliquat multi-lignes =====
tz_def('TZ_ML_LEADER_DEMOLITION_EMBASSY', 'نظرًا لأنك قائد تحالفك، لا يمكن بدء هدم السفارة الحالية، لأنها ما زالت تحمل كل');
tz_def('TZ_ML_CHANGELOG_120BUGS', 'تم إصلاح أكثر من 120 خطأ، وإصلاح القطع الأثرية بالكامل، وإصلاح المنجنيقات والكباش بالكامل، وأتمتة قرى التتار/القطع الأثرية/عجائب الدنيا/مخططات مباني عجائب الدنيا، ومعادلة معركة جديدة (أكثر دقة من القديمة)، وتفعيل تلقائي للقطع الأثرية، وإعادة كتابة الكثير من الكود. اطّلع على المزيد في ملف readme!');
tz_def('TZ_ML_CHANGELOG_NEWFORUM', 'نظام منتدى جديد، معادلة صياد شبيهة بنوفاتيرا، إصلاح كبير البنائين، طابور بحث مزدوج في الحدادة والدرع مع Plus');
tz_def('TZ_ML_GOLD_RESERVE', 'بشكل أساسي، نحجز كمية الذهب المطلوبة فور إتمام الدفع. إذا واجهت أي مشكلة، يرجى إرسال بريد إلكتروني إلى');
tz_def('TZ_ML_GPACK_NOTFOUND', 'تعذر العثور على حزمة الرسوميات. قد يكون ذلك لأحد الأسباب التالية:');
tz_def('TZ_ML_GPACK_ALLOWED_SAVE', 'تعرض حزمة رسوميات مسموح بها. احفظ اختيارك لتفعيلها.');
tz_def('TZ_ML_GPACK_ALTER_APPEARANCE', 'باستخدام حزمة رسوميات يمكنك تغيير مظهر نوفاتيرا. يمكنك اختيار واحدة من القائمة أو إدخال مسار مخصص.');
tz_def('TZ_ML_QUESTIONS_MULTIHUNTER', '. إذا كانت لديك أسئلة أو تريد الإبلاغ عن مخالفة، يمكنك مراسلة صياد الحسابات المتعددة.');
tz_def('TZ_ML_AWAY_NO_SITTER', 'إذا كنت تخطط للغياب لفترة طويلة ولا ترغب في تعيين وكيل، يمكنك تفعيل');
tz_def('TZ_ML_ACCOUNT_FROZEN', '. خلال هذه الفترة يكون حسابك مجمّدًا فعليًا. لن تتقدم الموارد أو القوات أو الأبحاث ولن يمكن مهاجمة قراك. تذكّر أن هذا يجمّد نوفاتيرا فقط، وليس الوقت.');
tz_def('TZ_ML_ACTIVATION_RESENT', '. سيتم بعدها إرسال رمز التفعيل مرة أخرى');
tz_def('TZ_ML_TWO_SITTERS_RIGHT', 'يحق لكل لاعب تسمية وكيلين يمكنهما لعب الحساب أثناء غياب المالك. يجب على الوكلاء لعب الحساب الذي يديرونه لتحقيق أقصى فائدة له. يُعاقب على إساءة استخدام هذه الميزة.');
tz_def('TZ_ML_SAME_COMPUTER_SITTER', 'اللاعبون الذين يستخدمون نفس الجهاز ويريدون الوصول لحساب بعضهم البعض يجب أن يستخدموا ميزة الوكيل.');
tz_def('TZ_ML_POLITE_TONE', 'يجب على الجميع التواصل بنبرة مهذبة وودية. يجوز لصيادي الحسابات المتعددة تغيير الملفات الشخصية وأسماء القرى غير اللائقة دون إنذار.');
tz_def('TZ_ML_MATERIAL_UNDERAGE', 'نشر أو نقل أي محتوى غير مناسب للقاصرين.');

// ===== i18n reliquat final =====
tz_def('TZ_NO_BEGINNER_PROT2', 'لا توجد حماية مبتدئين');
tz_def('TZ_SERVER_RUNNING_ON', '▶ السيرفر يعمل على');

// ===== task A: re-wired reverted templates =====
tz_def('TZ_HERO', "البطل");
tz_def('TZ_SEND_UNITS_BACK_TO', "إرسال القوات عائدة إلى");
tz_def('TZ_CONFIRM_DEMOLISH_COMPLETE_1', "هل أنت متأكد من رغبتك في الهدم الكامل لـ ");
tz_def('TZ_CONFIRM_DEMOLISH_COMPLETE_2', " مقابل 10 ذهب؟\nسيختفي المبنى فورًا، ولا يمكن التراجع عن ذلك.");
tz_def('TZ_CONFIRM_LAST_EMBASSY_L3', "تحذير!\n\nأنت على وشك هدم آخر سفارة من المستوى 3!\n\nبما أنك قائد تحالفك ولا يوجد أعضاء إضافيون متبقون، سيتم حل التحالف بمجرد اكتمال الهدم.");
tz_def('TZ_CONFIRM_LAST_EMBASSY_L1', "تحذير!\n\nأنت على وشك هدم آخر سفارة لديك!\n\nبما أنك عضو في تحالف، ستغادر ذلك التحالف تلقائيًا بمجرد اكتمال الهدم.");
tz_def('TZ_TRADE', "التجارة");

// ===== reports section (noticeClass tooltips) =====
tz_def('TZ_RPT_SCOUT', "تقرير استطلاع");
tz_def('TZ_RPT_WON_ATK_NOLOSS', "فوز كمهاجم دون خسائر");
tz_def('TZ_RPT_WON_ATK_LOSS', "فوز كمهاجم بخسائر");
tz_def('TZ_RPT_LOST_ATK_LOSS', "خسارة كمهاجم بخسائر");
tz_def('TZ_RPT_WON_DEF_NOLOSS', "فوز كمدافع دون خسائر");
tz_def('TZ_RPT_WON_DEF_LOSS', "فوز كمدافع بخسائر");
tz_def('TZ_RPT_LOST_DEF_LOSS', "خسارة كمدافع بخسائر");
tz_def('TZ_RPT_LOST_DEF_NOLOSS', "خسارة كمدافع دون خسائر");
tz_def('TZ_RPT_REINF_ARRIVED', "وصلت التعزيزات");
tz_def('TZ_RPT_WOOD_DELIVERED', "تم توصيل الخشب");
tz_def('TZ_RPT_CLAY_DELIVERED', "تم توصيل الطين");
tz_def('TZ_RPT_IRON_DELIVERED', "تم توصيل الحديد");
tz_def('TZ_RPT_CROP_DELIVERED', "تم توصيل المحاصيل");
tz_def('TZ_RPT_WON_SCOUT_ATK', "فوز في الاستطلاع كمهاجم");
tz_def('TZ_RPT_LOST_SCOUT_ATK', "خسارة في الاستطلاع كمهاجم");
tz_def('TZ_RPT_WON_SCOUT_DEF', "فوز في الاستطلاع كمدافع");
tz_def('TZ_RPT_LOST_SCOUT_DEF', "خسارة في الاستطلاع كمدافع");

// ===== report topic connectors (display-time localization) =====
tz_def('TZ_RT_ATTACKS', "يهاجم");
tz_def('TZ_RT_REINFORCEMENT', "تعزيزات");
tz_def('TZ_RT_SCOUTS', "يستطلع");
tz_def('TZ_RT_SEND_RES_TO', "إرسال موارد إلى");
tz_def('TZ_RT_WAS_ATTACKED', "تعرضت للهجوم");
tz_def('TZ_RT_REINF_IN', "تعزيزات في");
tz_def('TZ_RT_ELDERS_REINF', "تعزيزات قرية الشيوخ");
tz_def('TZ_RT_UNOCC_OASIS', "واحة غير مأهولة");

// ===== settler reports (issue #178) =====
tz_def('TZ_RT_NEW_VILLAGE', "تم تأسيس قرية جديدة");
tz_def('TZ_RT_VALLEY_OCCUPIED', "فشل الاستيطان (الوادي مأهول)");
tz_def('TZ_NEW_VILLAGE_MSG', "لقد أسّست قرية جديدة:");
tz_def('TZ_VALLEY_OCCUPIED_MSG', "لم يتمكن مستوطنوك من الاستقرار هنا - الوادي مأهول بالفعل من لاعب آخر. هم في طريقهم للعودة.");

// ===== player profile page (#189) =====
tz_def('AGE', 'العمر');
tz_def('CAPITAL_TAG', 'العاصمة');
tz_def('WRITE_MESSAGE_UNAVAILABLE', 'كتابة رسالة غير متاحة');
tz_def('PROFILE_FLAG_ADMIN', 'هذا اللاعب مسؤول (أدمن).');
tz_def('PROFILE_FLAG_MULTIHUNTER', 'هذا اللاعب صياد حسابات متعددة.');
tz_def('PROFILE_FLAG_BANNED', 'هذا اللاعب محظور.');
tz_def('PROFILE_FLAG_VACATION', 'هذا اللاعب في وضع الإجازة.');

// ===== in-game manual overview page (#189) =====
tz_def('BUILDINGS', 'المباني');
tz_def('INFRASTRUCTURE', 'البنية التحتية');
tz_def('FORWARD', 'التالي');
tz_def('NEW_FEATURES', 'الميزات الجديدة');
tz_def('NEW_WINDOW', 'نافذة جديدة');
tz_def('MANUAL_INTRO', 'يمنحك هذا الدليل داخل اللعبة فرصة الاطلاع على المعلومات المهمة في أي وقت.');
tz_def('MANUAL_NEW_FEATURES_DESC', 'هذه ميزات جديدة لن تجدها في النسخة الأصلية من لعبة نوفاتيرا T3.6. هنا يمكنك التعرف على كل الميزات الجديدة بمزيد من التفصيل.');
tz_def('MANUAL_FAQ', 'الأسئلة الشائعة لنوفاتيرا');
tz_def('MANUAL_FAQ_DESC', 'يقدم هذا الدليل داخل اللعبة معلومات موجزة فقط. مزيد من المعلومات متوفر على');

// ===== manual: building pages (PR-A) =====
tz_def('CONSTRUCTION_TIME', "وقت البناء");
tz_def('MANUAL_FOR_LEVEL_1', "للمستوى 1:");
tz_def('CROP_CONSUMPTION', "استهلاك المحاصيل");
tz_def('NONE', "لا يوجد");
tz_def('MANUAL_DESC_TRAPPER', "يحمي الصياد قريتك بأفخاخ مخفية بعناية. هذا يعني أن الأعداء غير الحذرين يمكن حبسهم ولن يتمكنوا من إيذاء قريتك بعد الآن.");
tz_def('MANUAL_ONE_TRAP_COSTS', "يكلف الفخ الواحد");
tz_def('MANUAL_TRAPPER_FREE', "لا يمكن تحرير القوات بغارة. عند تحرير القوات بهجوم عادي ناجح، يتم إصلاح ثلث الأفخاخ تلقائيًا. إذا حرر مالك الأفخاخ الأسرى فيمكن إصلاح كل الأفخاخ.");
tz_def('MANUAL_TRAPPER_GAULS', "لاحظ أن هذا المبنى لا يمكن بناؤه إلا من قِبل الغال.");
tz_def('MANUAL_DESC_WOODCUTTER', "يقطع الحطّاب الأشجار لإنتاج الخشب. كلما زدت من مستوى الحطّاب زاد الخشب الذي ينتجه.<br /><br />ببناء منشرة يمكنك زيادة الإنتاج أكثر.");
tz_def('MANUAL_DESC_CLAYPIT', "يُجمع الطين من حفر الطين. كلما ارتفع مستوى حفرة الطين، زاد إنتاج الطين.");
tz_def('MANUAL_DESC_IRONMINE', "هنا يجمع عمال المناجم معدن الحديد الثمين. بزيادة مستوى المنجم تزيد إنتاجه من الحديد. ببناء مصهر حديد يمكنك زيادة الإنتاج أكثر.<br /><br />");
tz_def('MANUAL_DESC_CROPLAND', "هنا يُنتج طعام سكانك. بزيادة مستوى الحقل الزراعي تزيد إنتاجه من المحاصيل.<br /><br />ببناء مطحنة حبوب ومخبز يمكنك زيادة الإنتاج أكثر.");
tz_def('MANUAL_DESC_SAWMILL', "يُعالَج هنا الخشب الذي يقطعه حطّابوك. حسب مستواها يمكن للمنشرة زيادة إنتاجك من الخشب حتى 25 بالمئة.");
tz_def('MANUAL_DESC_BRICKYARD', "يحوّل مصنع الطوب الطين إلى طوب. حسب مستواه يمكن لمصنع الطوب زيادة إنتاجك من الطين حتى 25 بالمئة.");
tz_def('MANUAL_DESC_IRONFOUNDRY', "يصهر مصهر الحديد الحديد. حسب مستواه يمكن لمصهر الحديد زيادة إنتاجك من الحديد حتى 25 بالمئة.");
tz_def('MANUAL_DESC_GRAINMILL', "تحوّل مطحنة الحبوب الحبوب إلى دقيق. حسب مستواها يمكن لمطحنة الحبوب زيادة إنتاجك من المحاصيل حتى 25 بالمئة.");
tz_def('MANUAL_DESC_BAKERY', "يحوّل المخبز الدقيق إلى خبز. بالإضافة إلى مطحنة الحبوب يمكن أن تصل زيادة إنتاج المحاصيل إلى 50 بالمئة إجماليًا.");
tz_def('MANUAL_DESC_WAREHOUSE', "تُخزَّن موارد الخشب والطين والحديد في المستودع. بزيادة مستواه تزيد سعة مستودعك.");
tz_def('MANUAL_DESC_GRANARY', "تُخزَّن المحاصيل المنتَجة في مزارعك في الحظيرة. بزيادة مستواها تزيد سعة الحظيرة.");
tz_def('MANUAL_DESC_BLACKSMITH', "تُحسَّن أسلحة محاربيك في أفران صهر الحداد. بزيادة مستواها يمكنك طلب تصنيع أسلحة أفضل.");
tz_def('MANUAL_DESC_ARMOURY', "يُحسَّن درع محاربيك في أفران صهر مصنع الدروع. بزيادة مستواه يمكنك طلب تصنيع دروع أفضل.");
tz_def('MANUAL_DESC_MAINBUILDING', "يعيش كبار بنّائي القرية في المبنى الرئيسي. كلما زاد مستواه، أسرع كبار البنّائين في إتمام بناء المباني الجديدة.");
tz_def('MANUAL_DESC_RALLYPOINT', "هنا تجتمع قوات قريتك. من هنا يمكنك إرسالها للغزو أو الغارة أو التعزيز إلى قرى أخرى.<br /><br />لا يمكن بناء نقطة التجمع إلا على الأرض الخضراء أسفل المبنى الرئيسي وإلى يمينه.");
tz_def('MANUAL_DESC_MARKETPLACE', "في السوق يمكنك تبادل الموارد مع لاعبين آخرين. كلما ارتفع مستواه، زادت الموارد التي يمكن نقلها في نفس الوقت.");
tz_def('MANUAL_DESC_EMBASSY', "السفارة مكان للدبلوماسيين. عند المستوى 1 يمكنك الانضمام إلى تحالف، وبعد رفعها إلى المستوى 3 يمكنك حتى تأسيس تحالف بنفسك.<br /><br />الحد الأقصى لعدد الأعضاء الممكن في التحالف يساوي 3 أضعاف مستوى أعلى سفارة داخل ذلك التحالف. لذا مع سفارة من المستوى 20 يمكن أن يصل عدد أعضاء التحالف إلى 60 لاعبًا.");
tz_def('MANUAL_DESC_BARRACKS', "يمكن تدريب المشاة في الثكنات. كلما ارتفع مستواها، زادت سرعة تدريب القوات.");
tz_def('MANUAL_DESC_STABLE', "يمكن تدريب الفرسان في الإسطبل. كلما ارتفع مستواه، زادت سرعة تدريب القوات.");
tz_def('MANUAL_DESC_WORKSHOP', "يمكن بناء أسلحة الحصار كالمنجنيقات والكباش في الورشة. كلما ارتفع مستواها، زادت سرعة إنتاج الوحدات.");
tz_def('MANUAL_DESC_ACADEMY', "يمكن تطوير أنواع وحدات جديدة في الأكاديمية. بزيادة مستواها يمكنك طلب أبحاث وحدات أفضل.");
tz_def('MANUAL_DESC_CRANNY', "يُستخدم المخبأ لإخفاء جزء على الأقل من مواردك عند مهاجمة القرية. لا يمكن سرقة هذه الموارد.<br /><br />عند المستوى 1 يحتفظ المخبأ بـ 100 وحدة من كل مورد. مخابئ الغال ضعف حجم مخابئ الآخرين.<br /><br />ملاحظات<br />في T3 المخبأ فعّال بنسبة 66% ضد التوتون.<br />في T3.5 المخبأ فعّال بنسبة 80% ضد التوتون.");
tz_def('MANUAL_DESC_TOWNHALL', "في دار البلدية يمكنك إقامة احتفالات فخمة. تزيد هذه الاحتفالات من نقاط ثقافتك.<br /><br />نقاط الثقافة ضرورية لتأسيس أو غزو قرى جديدة. كل مبنى ينتج نقاط ثقافة وكلما ارتفع مستواه زاد إنتاجه. بالاحتفالات يمكنك زيادة هذا الإنتاج لفترة قصيرة.");
tz_def('MANUAL_DESC_RESIDENCE', "مقر الإقامة قصر صغير يعيش فيه الملك أو الملكة عند زيارة القرية. يحمي مقر الإقامة القرية من الأعداء الراغبين في غزوها.");
tz_def('MANUAL_DESC_PALACE', "يعيش ملك أو ملكة الإمبراطورية في القصر. لا يمكن أن يوجد أكثر من قصر واحد في مملكتك في وقت واحد. تحتاج إلى قصر لإعلان قرية كعاصمة لك.<br /><br />لا يمكن غزو العاصمة. بالإضافة إلى ذلك، العاصمة هي المكان الوحيد الذي يمكن فيه توسيع حقول الموارد بعد المستوى 10 والمكان الوحيد الذي يمكن فيه بناء محجر الحجّار.");
tz_def('MANUAL_DESC_TREASURY', "تُحفظ ثروات إمبراطوريتك في الخزينة. لا يمكن للخزينة تخزين سوى قطعة أثرية واحدة.<br /><br />تحتاج إلى خزينة من المستوى 10 لقطعة أثرية صغيرة، أو المستوى 20 لقطعة كبيرة.");
tz_def('MANUAL_DESC_TRADEOFFICE', "في مكتب التجارة تُحسَّن عربات التجّار وتُجهَّز بخيول قوية. كلما ارتفع مستواه، زادت قدرة تجّارك على الحمل.");
tz_def('MANUAL_DESC_GREATBARRACKS', "تتيح لك الثكنات الكبرى بناء وحدات أكثر في نفس الوقت لكن بتكلفة ثلاثة أضعاف الأصل.<br /><br />لا يمكن بناؤها في العاصمة.");
tz_def('MANUAL_DESC_GREATSTABLE', "يتيح لك الإسطبل الكبير بناء وحدات أكثر في نفس الوقت لكن بتكلفة ثلاثة أضعاف الأصل.<br /><br />لا يمكن بناؤه في العاصمة.");
tz_def('MANUAL_DESC_STONEMASON', "محجر الحجّار خبير في قطع الحجر. كلما زاد توسيع المبنى، زادت متانة مباني القرية.<br /><br />لا يمكن بناؤه إلا في العاصمة.");
tz_def('MANUAL_DESC_BREWERY', "يُخمَّر ميد لذيذ في المصنع ويُشرب لاحقًا من قِبل الجنود أثناء الاحتفالات.<br /><br />تجعل هذه المشروبات جنودك أكثر شجاعة وقوة في المعارك (1% لكل مستوى). للأسف تقل قدرة الزعماء على الإقناع ولا تستطيع المنجنيقات إلا إحداث إصابات عشوائية.<br /><br />لا يمكن بناؤه إلا من قِبل التوتون وفي عاصمتهم فقط. يؤثر على الإمبراطورية بأكملها.");
tz_def('MANUAL_DESC_HEROSMANSION', "في قصر البطل يمكنك تدريب بطل. لهذا تحتاج جنديًا عاديًا سيصبح البطل، وبالتالي تحتاج ثكنات أو إسطبل.<br /><br />عندما يصل المبنى للمستويات 10 و15 و20 يمكنك ضم 1 و2 و3 واحات غير مأهولة ببطلك. حسب الواحة ستحصل على زيادة في إنتاج مورد معين (أو حتى موردين لبعض الواحات).");
tz_def('MANUAL_DESC_GREATWAREHOUSE', "تُخزَّن موارد الخشب والطين والحديد في مستودعك. يوفر لك المستودع الكبير مساحة أكبر ويحافظ على مواردك أكثر جفافًا وأمانًا من المستودع العادي.<br /><br />لا يمكن بناء هذا المبنى إلا في قرى التتار القديمة أو بقطع أثرية تتارية خاصة.");
tz_def('MANUAL_DESC_GREATGRANARY', "تُخزَّن المحاصيل المنتَجة من مزارعك في الحظيرة. توفر لك الحظيرة الكبرى مساحة أكبر وتحافظ على محاصيلك أكثر جفافًا وأمانًا من العادية.<br /><br />لا يمكن بناء هذا المبنى إلا في قرى التتار القديمة أو بقطع أثرية تتارية خاصة.");
tz_def('MANUAL_DESC_WONDER', "تمثل عجيبة الدنيا فخر الإبداع. فقط الأقوى والأغنى قادرون على بناء مثل هذا التحفة والدفاع عنها ضد الأعداء الحاسدين.<br /><br />لا يمكن تشييد عجائب الدنيا إلا في قرى التتار القديمة. كما يلزم مخطط بناء. بدءًا من المستوى 50 يلزم مخطط إضافي، يجب أن يملكه لاعب آخر في نفس التحالف.");
tz_def('MANUAL_DESC_HORSEDRINKING', "يهتم مسقى الخيول برفاهية خيولك وبالتالي يزيد أيضًا من سرعة تدريبها.<br /><br />يقلل مسقى الخيول من استهلاك المحاصيل بمقدار واحد للجنود التالين: فرسان المندوبين (Equites Legati) من المستوى 10، فرسان الإمبراطور (Equites Imperatoris) من المستوى 15، وفرسان قيصر (Equites Caesaris) من المستوى 20.<br /><br />لا يمكن بناء مسقى الخيول إلا من قِبل الرومان.");
tz_def('MANUAL_DESC_GREATWORKSHOP', "في الورشة الكبرى يمكن بناء أسلحة الحصار كالمنجنيقات والكباش، وإن كانت بثلاثة أضعاف تكلفة الوحدة القياسية. كلما ارتفع مستواها، أسرع إنتاج الوحدات.<br /><br />لا يمكن بناؤها في العاصمة.");

tz_def('MANUAL_ATTACK_VALUE', "قيمة الهجوم");
tz_def('MANUAL_DEF_INFANTRY', "الدفاع ضد المشاة");
tz_def('MANUAL_DEF_CAVALRY', "الدفاع ضد الفرسان");
tz_def('MANUAL_VELOCITY', "السرعة");
tz_def('MANUAL_FIELDS_HOUR', "حقل/ساعة");
tz_def('MANUAL_CAN_CARRY', "يمكنه حمل");
tz_def('MANUAL_TRAINING_DURATION', "مدة التدريب");
tz_def('MANUAL_NPC_NATARS', "الوصف مخصص للرجوع إليه فقط. التتار قبيلة NPC خالصة ولا يمكن لعبها من قِبل اللاعبين.");
tz_def('MANUAL_NPC_NATURE', "الوصف مخصص للرجوع إليه فقط. قبيلة الطبيعة هي قبيلة NPC خالصة ولا يمكن لعبها من قِبل اللاعبين.");
tz_def('MANUAL_UDESC_ANIMAL_EXP', "تُحدَّد الخبرة التي يكتسبها البطل من قتل حيوان بناءً على استهلاك ذلك الحيوان. هذا يعني أن %s سيمنح نقطة خبرة واحدة فقط بمقدار %d.");
tz_def('MANUAL_UDESC_1', "الليجونير هو المشاة البسيط متعدد الاستخدامات في الإمبراطورية الرومانية. بفضل تدريبه المتوازن، يجيد الدفاع والهجوم معًا. لكنه لن يبلغ أبدًا مستويات الوحدات الأكثر تخصصًا.");
tz_def('MANUAL_UDESC_2', "البريتوريون هم حرس الإمبراطور ويدافعون عنه بأرواحهم. بما أن تدريبهم متخصص للدفاع، فهم مهاجمون ضعفاء جدًا.");
tz_def('MANUAL_UDESC_3', "الإمبيريان هو المهاجم الأقوى في الإمبراطورية الرومانية. سريع وقوي، وكابوس لكل المدافعين. لكن تدريبه مكلف ويستغرق وقتًا طويلًا.");
tz_def('MANUAL_UDESC_4', "فرسان المندوبين (Equites Legati) هم قوات الاستطلاع الرومانية. سريعون نسبيًا ويمكنهم التجسس على قرى العدو لرؤية الموارد والقوات. <br /><br /> إذا لم يوجد كشافة أو فرسان مندوبين أو مستكشفون في القرية المستطلَعة، يمر الاستطلاع دون أن يُلاحظ.");
tz_def('MANUAL_UDESC_5', "فرسان الإمبراطور (Equites Imperatoris) هم الفرسان القياسيون في الجيش الروماني ومسلحون جيدًا. ليسوا الأسرع لكنهم رعب للأعداء غير المستعدين. يجب أن تتذكر دائمًا أن إعالة الفارس وحصانه ليست رخيصة.");
tz_def('MANUAL_UDESC_6', "فرسان قيصر (Equites Caesaris) هم الفرسان الثقيلون لروما. مدرَّعون جيدًا جدًا ويحدثون أضرارًا كبيرة، لكن كل هذا الدرع والسلاح له ثمن. بطيئون، يحملون موارد أقل، وإطعامهم مكلف.");
tz_def('MANUAL_UDESC_7', "الكبش هو سلاح دعم ثقيل لمشاتك وفرسانك. مهمته تدمير أسوار العدو وبالتالي زيادة فرص قواتك في التغلب على تحصينات العدو.");
tz_def('MANUAL_UDESC_8', "المنجنيق سلاح بعيد المدى ممتاز؛ يُستخدم لتدمير حقول ومباني قرى العدو. لكن بدون قوات مرافقة يكاد يكون بلا دفاع، فلا تنسَ إرسال بعض قواتك معه. <br /><br /> امتلاك نقطة تجمع بمستوى عالٍ يجعل منجنيقاتك أكثر دقة ويمنحك خيار استهداف مبانٍ إضافية للعدو. مع نقطة تجمع من المستوى 10 يمكن استهداف كل مبنى باستثناء المخبأ ومحجر الحجّار والصياد. <br /> ملاحظة: يمكن للمنجنيقات المشتعلة إصابة المخبأ أو الفخاخ أو محجر الحجّار عند الاستهداف العشوائي.");
tz_def('MANUAL_UDESC_9', "السيناتور هو الزعيم المختار للقبيلة. متحدث بارع ويعرف كيف يقنع الآخرين. قادر على إقناع قرى أخرى بالقتال مع الإمبراطورية. <br /><br /> في كل مرة يتحدث فيها السيناتور إلى سكان قرية، تنخفض قيمة ولاء العدو حتى تصبح القرية ملكك.");
tz_def('MANUAL_UDESC_10', "المستوطنون مواطنون شجعان وجريئون ينتقلون خارج القرية بعد جلسة تدريب طويلة لتأسيس قرية جديدة تكريمًا لك. <br /><br /> بما أن الرحلة وتأسيس القرية الجديدة صعبان جدًا، يلتزم ثلاثة مستوطنين بالبقاء معًا. يحتاجون إلى أساس قدره 750 وحدة من كل مورد.");
tz_def('MANUAL_UDESC_11', "حاملو الهراوات هم أرخص وحدة في نوفاتيرا. يُدرَّبون بسرعة ولديهم قدرات هجومية متوسطة لكن درعهم ليس الأفضل. حاملو الهراوات شبه عزّل أمام الفرسان وسيُداسون بسهولة.");
tz_def('MANUAL_UDESC_12', "في جيش التوتون مهمة الرمّاح هي الدفاع. بارع بشكل خاص ضد الفرسان بفضل طول سلاحه. <br /><br /> لكن لا تستخدمه كوحدة هجومية لأن قدراته الهجومية منخفضة جدًا.");
tz_def('MANUAL_UDESC_13', "هذه أقوى وحدة مشاة عند التوتون. قوي في الهجوم والدفاع معًا لكنه أبطأ وأغلى من الوحدات الأخرى.");
tz_def('MANUAL_UDESC_14', "يتحرك الكشاف بعيدًا أمام قوات التوتون للحصول على انطباع عن قوة العدو وقراه. يتحرك سيرًا على الأقدام، مما يجعله أبطأ من نظرائه الرومان أو الغال. يستطلع وحدات العدو وموارده وتحصيناته. <br /><br /> إذا لم يوجد كشافة أو مستكشفون أو فرسان مندوبين للعدو في القرية المستطلَعة فيمر الاستطلاع دون أن يُلاحظ.");
tz_def('MANUAL_UDESC_15', "بما أن الفرسان المقدَّسين (Paladins) مجهزون بدروع ثقيلة، فهم وحدة دفاعية ممتازة. سيجد المشاة صعوبة خاصة في اختراق درعه. <br /><br /> للأسف قدراتهم الهجومية منخفضة نسبيًا وسرعتهم، مقارنة بوحدات الفرسان الأخرى، أقل من المتوسط. تدريبهم يستغرق وقتًا طويلًا جدًا ومكلف نسبيًا.");
tz_def('MANUAL_UDESC_16', "فارس التوتون محارب هائل يجلب الخوف واليأس لأعدائه. في الدفاع يتميز ضد فرسان العدو. لكن تكلفة تدريبه وإطعامه استثنائية.");
tz_def('MANUAL_UDESC_17', "الكبش سلاح دعم ثقيل لمشاتك وفرسانك. مهمته تدمير أسوار العدو وبالتالي زيادة فرص قواتك في التغلب على تحصينات العدو.");
tz_def('MANUAL_UDESC_18', "المنجنيق سلاح بعيد المدى ممتاز؛ يُستخدم لتدمير حقول ومباني قرى العدو. لكن بدون قوات مرافقة يكاد يكون بلا دفاع، فلا تنسَ إرسال بعض قواتك معه. <br /><br /> امتلاك نقطة تجمع بمستوى عالٍ يجعل منجنيقاتك أكثر دقة ويمنحك خيار استهداف مبانٍ إضافية للعدو. مع نقطة تجمع من المستوى 10 يمكن استهداف كل مبنى باستثناء المخبأ ومحجر الحجّار والصياد. <br /> ملاحظة: يمكن للمنجنيقات إصابة المخبأ أو الفخاخ أو محاجر الحجّار عند الاستهداف العشوائي.");
tz_def('MANUAL_UDESC_19', "من بين صفوفهم يختار التوتون زعيمهم. للاختيار لا تكفي الشجاعة والاستراتيجية؛ يجب أيضًا أن تكون متحدثًا رائعًا، إذ إن الهدف الأساسي للزعيم هو إقناع سكان القرى الأجنبية بالانضمام إلى قبيلته. <br /><br /> كلما تحدث الزعيم أكثر إلى سكان قرية، انخفض ولاء القرية أكثر حتى تنضم أخيرًا إلى قبيلة الزعيم.");
tz_def('MANUAL_UDESC_21', "بما أن الفالانكس مشاة، فهو رخيص وسريع الإنتاج. <br /><br /> رغم أن قوته الهجومية منخفضة، إلا أنه في الدفاع قوي جدًا ضد المشاة والفرسان معًا.");
tz_def('MANUAL_UDESC_22', "حاملو السيوف أغلى من الفالانكس، لكنهم وحدة هجومية. <br /><br /> دفاعيًا هم ضعفاء نسبيًا، خاصة ضد الفرسان.");
tz_def('MANUAL_UDESC_23', "المستكشف هو وحدة الاستطلاع عند الغال. سريعون جدًا ويمكنهم التقدم بحذر نحو وحدات العدو أو موارده أو مبانيه للتجسس عليها. <br /><br /> إذا لم يوجد كشافة أو فرسان مندوبين أو مستكشفون في القرية المستطلَعة، يمر الاستطلاع دون أن يُلاحظ.");
tz_def('MANUAL_UDESC_24', "رعود ثيوتات (Theutates Thunders) فرسان سريعون وقويون جدًا. يمكنهم حمل كمية كبيرة من الموارد مما يجعلهم مغيرين ممتازين أيضًا. <br /><br /> في الدفاع قدراتهم متوسطة في أفضل الأحوال.");
tz_def('MANUAL_UDESC_25', "هذه وحدة الفرسان المتوسطة رائعة في الدفاع. الغرض الرئيسي من فارس الدرويد هو الدفاع ضد مشاة العدو. تكلفته وإعالته مرتفعتان نسبيًا.");
tz_def('MANUAL_UDESC_26', "الهيدوان (Haeduans) هم السلاح الأمثل للغال في الهجوم والدفاع ضد الفرسان. قليلون يستطيعون مجاراتهم في هذه النقاط. <br /><br /> لكن تدريبهم وتجهيزهم مكلفان جدًا أيضًا. يأكلون 3 وحدات محاصيل في الساعة، لذا يجب أن تفكر جيدًا فيما إذا كانوا يستحقون ذلك.");
tz_def('MANUAL_UDESC_28', "المنجنيق العملاق (Trebuchet) سلاح بعيد المدى ممتاز؛ يُستخدم لتدمير حقول ومباني قرى العدو. لكن بدون قوات مرافقة يكاد يكون بلا دفاع، فلا تنسَ إرسال بعض قواتك معه. <br /><br /> امتلاك نقطة تجمع بمستوى عالٍ يجعل منجنيقاتك أكثر دقة ويمنحك خيار استهداف مبانٍ إضافية للعدو. مع نقطة تجمع من المستوى 10 يمكن استهداف كل مبنى باستثناء المخبأ ومحاجر الحجّار والصياد. <br /> ملاحظة: يمكن للمنجنيق العملاق إصابة المخبأ أو الفخاخ أو محاجر الحجّار عند الاستهداف العشوائي.");
tz_def('MANUAL_UDESC_29', "لكل قبيلة مقاتل قديم وذو خبرة يستطيع حضوره وخطبه إقناع سكان قرى العدو بالانضمام إلى قبيلته. <br /><br /> كلما تحدث الزعيم القبلي أكثر أمام أسوار قرية العدو، انخفض ولاؤها أكثر حتى تنضم إلى قبيلة الزعيم القبلي.");
tz_def('MANUAL_UDESC_31', "الجرذان رخيصة وتتكاثر بسرعة كبيرة لكنها لا تستطيع حمل الكثير.<br /><br />هذه على الأرجح أرخص وحدات الطبيعة وأقبحها.");
tz_def('MANUAL_UDESC_44', "يستخدم التتار أسراب الطيور لجمع المعلومات عن أعدائهم. بفضل ميزة الاستطلاع من الجو، يكاد يكون من المستحيل إيقاف فرق استطلاع التتار؛ من ناحية أخرى، حتى القروي البسيط يمكنه بسهولة ملاحظة الأسراب المصفّرة والريشية.");
tz_def('MANUAL_UDESC_41', "رماحهم الطويلة والحادة تُستخدم كخط دفاع رئيسي في أي معركة. حرّاس الرماح التتار محاربون جريئون يستخدمون براعتهم لإسقاط فرسان العدو بسرعة والقضاء عليهم.");
tz_def('MANUAL_UDESC_42', "الامتدادات الشبيهة بالأشواك على خوذاتهم وأساوِرهم وأجزاء أكتاف دروعهم تمنح المحاربين الشائكين اسمهم. الرجال الذين يقاتلون للتتار كمحاربين شائكين مثابرون ومدرَّبون جيدًا، يقدمون معركة دامية لأي شخص يتحلى بالحماقة الكافية لمهاجمتهم.");
tz_def('MANUAL_UDESC_43', "يُعبَده شعبه ويخشاه أعداؤه، يقاتل الحارس بلا مطية لكنه مع ذلك أحد أقيم جنود الجيش التتاري، بفضل تنوع مهاراته. يُعتبرون مقاتلين مدرَّبين جيدًا، ولا يتركون لأعدائهم فرصًا تُذكر للفوز. بفضل دروعهم الثقيلة، يمكن استخدامهم أيضًا كقوات دفاع قوية وموثوقة.");
tz_def('MANUAL_UDESC_45', "لا تفوح إلا رائحة الموت والفناء عندما يمتطي فرسان الفأس خيولهم استعدادًا للحرب. بمهارة مثل الفلاح الذي يستخدم منجله للحصاد، يلوّح فارس الفأس بنصله الجبار. عادةً ما تكفي ضربة واحدة لقطع رأس الخصم وإصراخ من حوله رعبًا.");
tz_def('MANUAL_UDESC_46', "فقط أمهر وأقوى محاربي التتار يبقون على قيد الحياة بعد التدريب ليصبحوا فرسانًا تتاريين. رؤيتهم يقاتلون تملأ المرء بالرهبة وتُظهر معنى الحرب الحقيقية. يستخدمون سيوفهم كما لو كانت جزءًا من أذرعهم وأيديهم ويستخدمون دروعهم كامتداد طبيعي لأجسادهم. حتى الخيول التي يمتطونها مُربّاة ومدرَّبة خصيصًا - لا يمكن لأي حصان عادي أن يتحمل الدرع الذي ترتديه خيول الفرسان، ناهيك عن الفارس نفسه، ويظل قادرًا على الذهاب إلى الحرب. وصلت همسات مجدهم حتى إلى أبعد الممالك، ناشرةً الخوف والرعب.");
tz_def('MANUAL_UDESC_47', "لا تعرف قبيلة أخرى غير التتار كيفية استخدام هذه المخلوقات الرائعة لأغراضهم. لا سور ولا سياج يستطيع الصمود أمام هجمات فيل الحرب. آلة قتل متحركة، تسحق كل ما يحاول الوقوف في طريقها أو معارضتها.");
tz_def('MANUAL_UDESC_48', "حتى كمهندسين، كان التتار ناجحين جدًا. صنعوا آلات الحرب قبل أي شخص آخر بوقت طويل وأتقنوها منذ ذلك الحين من كل النواحي. البالستا، سلاح ضخم شبيه بالقوس المعترض، يطلق مقذوفاته بقوة لا يستطيع أي سور أو درع صدّها. عندما يفكك المهندسون السلاح لنقله إلى ساحة المعركة التالية، لا يبقى عادة سوى الأنقاض حيث أصابت المقذوفات.");
tz_def('MANUAL_UDESC_49', "مزيج من الخوف الخالص والإعجاب والرهبة يحرّك القرويين عندما يتحدث إليهم الإمبراطور التتاري. هذه الشخصية المهيبة والمجهَّزة جيدًا تدرك تمامًا تأثيرها على الآخرين وتعرف كيف تُخضع قرية بأكملها بخطبة واحدة.");
tz_def('MANUAL_UDESC_50', "رحّالة جريئون وكبار بنّائين، مدفوعون بحماس للعمل ومعرفة كل سر صغير عن استصلاح الأرض وبناء القصور وتحصين القرى، يخرج المستوطنون التتار في مجموعات من ثلاثة للمطالبة بالأرض باسم أسيادهم التتار.");
tz_def('MANUAL_UDESC_51', "المحارب الهوني هو عماد مشاة الهون. رخيص وسريع التدريب، وهو جندي متوازن جيد للغارات المبكرة والدفاع الأساسي.");
tz_def('MANUAL_UDESC_52', "فارس الاستطلاع يتجسس على قرى الأعداء بسرعة فائقة. لا يراه إلا الكشافة الآخرون ولا يحمل أي سلاح للقتال.");
tz_def('MANUAL_UDESC_53', "رامي السهام على الحصان يضرب من فوق السرج بسهام قاتلة. مغيرٌ سريع يجمع بين قوة هجومية وسعة حمل جيدتين.");
tz_def('MANUAL_UDESC_54', "فارس السهوب مغيرٌ خفيف سريع في السهول. سرعته تجعله مثاليًا للغارات الخاطفة على القرى غير المحمية.");
tz_def('MANUAL_UDESC_55', "رمّاح هوني يهجم برمح ثقيل. وحدة فرسان هجومية قوية تصمد أيضًا في وجه فرسان الأعداء.");
tz_def('MANUAL_UDESC_56', "فارس النخبة فخر جحافل الهون. قوي للغاية في الهجوم، يسحق كل ما يعترض طريقه، لكن تدريبه مكلف.");
tz_def('MANUAL_UDESC_57', "الكبش آلة حرب ثقيلة تهدم أسوار الأعداء. احمِه جيدًا، فهو بطيء وعاجز عن الدفاع عن نفسه بمفرده.");
tz_def('MANUAL_UDESC_58', "المنجنيق يقذف الحجارة لمسافات بعيدة لتدمير مباني وحقول الأعداء. يجب حمايته جيدًا بقوات أخرى.");
tz_def('MANUAL_UDESC_59', "زعيم القبيلة يخفض ولاء قرى الأعداء بحضوره المرهوب حتى تنضم إلى جحافل الهون.");
tz_def('MANUAL_UDESC_60', "المستوطنون الهون رعايا شجعان يخرجون لتأسيس قرية جديدة للهون. يلزم ثلاثة منهم، مزوّدين بالإمدادات.");
tz_def('MANUAL_UDESC_61', "العبد المسلّح رخيص وسريع التدريب. ضعيف منفردًا، لكنه بأعداد كبيرة يستطيع سحق الدفاعات بتكلفة زهيدة.");
tz_def('MANUAL_UDESC_62', "المقاتل المصري جندي مشاة صلب من جنود الفرعون، مفيد في الهجوم والدفاع عن المملكة على حد سواء.");
tz_def('MANUAL_UDESC_63', "حارس المعبد يحمي الأماكن المقدسة في مصر. وحدة دفاعية ممتازة ضد مشاة الأعداء.");
tz_def('MANUAL_UDESC_64', "الكشّاف على حصان يتقدم الجيش لاستكشاف قرى الأعداء. لا يستطيع إيقافه أو رؤيته سوى كشافة الأعداء.");
tz_def('MANUAL_UDESC_65', "العربة الحربية تنطلق عبر ساحة المعركة، تسحق المشاة تحتها. وحدة فرسان دفاعية قوية في الجيش المصري.");
tz_def('MANUAL_UDESC_66', "العربة الملكية تحمل نخبة الجيش المصري. مدمّرة في الهجوم ورمز لقوة الفرعون.");
tz_def('MANUAL_UDESC_67', "الكبش آلة حرب ثقيلة تهدم أسوار الأعداء. احمِه جيدًا، فهو بطيء وعاجز عن الدفاع عن نفسه بمفرده.");
tz_def('MANUAL_UDESC_68', "المنجنيق يقذف الحجارة لمسافات بعيدة لتدمير مباني وحقول الأعداء. يجب حمايته جيدًا بقوات أخرى.");
tz_def('MANUAL_UDESC_69', "حاكم الإقليم يستميل قرى الأعداء بالهدايا والخطب، ويخفض ولاءها حتى تنضم إلى الإمبراطورية المصرية.");
tz_def('MANUAL_UDESC_70', "المستوطنون المصريون رعايا شجعان يخرجون لتأسيس قرية جديدة لمصر. يلزم ثلاثة منهم، مزوّدين بالإمدادات.");
tz_def('MANUAL_UDESC_71', "محارب الهوبليت الإسبرطي يقاتل في الفالانكس الشهيرة. جندي مشاة متكامل ممتاز، قوي في الهجوم والدفاع معًا.");
tz_def('MANUAL_UDESC_72', "محارب الأگوجي تربّى على الحرب منذ الطفولة. مدافع رخيص وقوي عن وطن إسبرطة.");
tz_def('MANUAL_UDESC_73', "الهومويوي هم النظراء، المواطنون الكاملون في إسبرطة. مشاة ثقيلة بقوة هجومية هائلة.");
tz_def('MANUAL_UDESC_74', "كشّاف البيريويكوي يراقب تحركات الأعداء لصالح إسبرطة. لا يستطيع رؤيته أو إيقافه سوى كشافة الأعداء.");
tz_def('MANUAL_UDESC_75', "الفارس الإسبرطي يجوب حدود لاكيدايمون. وحدة فرسان سريعة بقيم قتالية متوازنة.");
tz_def('MANUAL_UDESC_76', "الهيبيس هم الحرس الملكي لملوك إسبرطة. فرسان نخبة يبرعون في الهجوم.");
tz_def('MANUAL_UDESC_77', "الكبش آلة حرب ثقيلة تهدم أسوار الأعداء. احمِه جيدًا، فهو بطيء وعاجز عن الدفاع عن نفسه بمفرده.");
tz_def('MANUAL_UDESC_78', "المنجنيق يقذف الحجارة لمسافات بعيدة لتدمير مباني وحقول الأعداء. يجب حمايته جيدًا بقوات أخرى.");
tz_def('MANUAL_UDESC_79', "الإيفور يتحدث بسلطة إسبرطة، ويخفض ولاء قرى الأعداء حتى تستسلم.");
tz_def('MANUAL_UDESC_80', "المستوطنون الإسبرطيون رعايا شجعان يخرجون لتأسيس قرية جديدة لإسبرطة. يلزم ثلاثة منهم، مزوّدين بالإمدادات.");
tz_def('MANUAL_UDESC_81', "غازي الفايكنج يعيش من أجل النهب. سريع التدريب ودائمًا جاهز للإبحار نحو الغارة التالية.");
tz_def('MANUAL_UDESC_82', "كشّاف الفايكنج يتسلل خفية إلى أراضي الأعداء لجمع المعلومات. لا يمكن لغير كشافة الأعداء الإمساك به.");
tz_def('MANUAL_UDESC_83', "حامل الفأس يلوّح بفأسه الكبير بقوة وحشية. وحدة مشاة هجومية قوية من الشمال.");
tz_def('MANUAL_UDESC_84', "البرسركر يقاتل في نشوة معركة، لا يخشى ألمًا ولا موتًا. مدمّر في الهجوم، ضعيف في الدفاع.");
tz_def('MANUAL_UDESC_85', "فارس الفايكنج يجمع بين صلابة الشمال وسرعة الحصان. وحدة فرسان متعددة الاستخدامات.");
tz_def('MANUAL_UDESC_86', "الهوسكارل هو الحارس الشخصي المُقسَم لليارل. فرسان ثقيلة من النخبة، يخشاها الجميع عبر كل البحار.");
tz_def('MANUAL_UDESC_87', "الكبش آلة حرب ثقيلة تهدم أسوار الأعداء. احمِه جيدًا، فهو بطيء وعاجز عن الدفاع عن نفسه بمفرده.");
tz_def('MANUAL_UDESC_88', "المنجنيق يقذف الحجارة لمسافات بعيدة لتدمير مباني وحقول الأعداء. يجب حمايته جيدًا بقوات أخرى.");
tz_def('MANUAL_UDESC_89', "الإيرل يُخضع قرى الأعداء لإرادته، ويخفض ولاءها حتى تُقسم بالولاء للشمال.");
tz_def('MANUAL_UDESC_90', "المستوطنون الفايكنج رعايا شجعان يخرجون لتأسيس قرية جديدة للفايكنج. يلزم ثلاثة منهم، مزوّدين بالإمدادات.");

// ===== manual: new-features pages (PR-C) =====
tz_def('MANUAL_NF_ENABLED', "مفعّل");
tz_def('MANUAL_NF_DISABLED', "معطّل");
tz_def('MANUAL_NF_T_11', "عرض الواحة في الملف الشخصي");
tz_def('MANUAL_NF_T_12', "رسالة دعوة التحالف");
tz_def('MANUAL_NF_T_13', "آليات جديدة للتحالف والسفارة");
tz_def('MANUAL_NF_T_14', "رسالة موضوع منتدى جديد");
tz_def('MANUAL_NF_T_15', "صور القبائل في الملف الشخصي");
tz_def('MANUAL_NF_T_16', "صور صيادي الحسابات المتعددة في الملف الشخصي");
tz_def('MANUAL_NF_T_17', "عرض القطعة الأثرية في الملف الشخصي");
tz_def('MANUAL_NF_T_18', "عرض عجيبة الدنيا في الملف الشخصي");
tz_def('MANUAL_NF_T_20', "أهداف المنجنيق");
tz_def('MANUAL_NF_T_21', "دليل عن الطبيعة والتتار");
tz_def('MANUAL_NF_T_22', "وضع الروابط المباشرة");
tz_def('MANUAL_NF_T_23', "وسام لاعب مخضرم");
tz_def('MANUAL_NF_T_24', "وسام لاعب مخضرم 5 سنوات");
tz_def('MANUAL_NF_T_25', "وسام لاعب مخضرم 10 سنوات");
tz_def('MANUAL_NF_T_26', "أوسمة خاصة");
tz_def('MANUAL_NF_D_11', "إذا كانت هناك واحة مستولى عليها في القرية، ستُعرض في الملف الشخصي للاعب أمام القرية المقابلة مع نوع المورد المناسب ومكافأة الإنتاج. تم تقديم هذه الميزة في نوفاتيرا T4.");
tz_def('MANUAL_NF_D_12', "إذا أُرسِلت دعوة لأحد اللاعبين للانضمام إلى التحالف، سيُبلَّغ اللاعب برسالة داخل اللعبة.");
tz_def('MANUAL_NF_D_13', "<h2>مقدمة</h2><br> آليات السفارة والتحالف كانت دائمًا تبدو لي وكأنها نوع من الغش. خاصة أن مبنى السفارة له \"سعة\" محددة، مما يعني أن 3/6/9/12/... وحتى 60 عضوًا فقط يمكن أن يكونوا في التحالف، حسب مستوى سفارتك. <br><br> هذا كله جيد، لكن بمجرد أن تصل بسفارتك إلى المستوى 20 وتبلغ 60 عضوًا في التحالف، كنت حرًا في هدم المبنى بالكامل ولا يحدث شيء أبدًا. لن تستطيع تبديل التحالفات لكن هذا كل ما في الأمر. لم تعد خاصية السعة للسفارة مطبَّقة. لو كانت مطبَّقة، لما استطعت هدمها حتى بمستوى واحد إلى 19 - لأنه مع 60 عضوًا كاملًا، لن تستطيع السفارة استيعابهم عند المستوى 19 بعد الآن. <br><br> لذا قررت أن أضيف بعض التوابل إلى اللعبة، لأجعل السفارة قطعة شطرنج أكثر وضوحًا على اللوحة. <br><br> <h2>آليات جديدة</h2><br> لجعل الأمور مثيرة، طوّرت مجموعة قواعد جديدة كاملة لهدم وتدمير السفارة في المعركة. الأمر معقد بعض الشيء لكنه يعكس خاصية \"السعة\" للتحالف بشكل مثالي. <br><br> التغيير الرئيسي هو أن الناس لا يستطيعون هدم سفاراتهم فعليًا دون تأثير جانبي يتمثل في العقاب بمجرد أن ينخفض المستوى كثيرًا. بالنسبة لعضو التحالف، هذا يعني أن سفارته يجب ألا تنخفض أبدًا عن المستوى 1. إذا حدث ذلك، سيُحذَّر ثم يُزال من تحالفه. <br><br> بالمثل، بالنسبة لمؤسسي التحالف، هذا يمثل تحديًا أكبر فعليًا، لأنهم من يحتاج للحفاظ على سير تحالفهم بسلاسة. بالنسبة لهم، لن يُسمح بهدم السفارة تحت مستوى ما زال قادرًا على استيعاب العدد الحالي لأعضاء التحالف. <br><br> الآن، في الحالة الغريبة عندما يهاجم لاعبون/تحالفات أخرى فعليًا قرية المؤسس التي تقع فيها سفارته، ثم يستهدفون تلك السفارة بمنجنيقاتهم وكباشهم - يمكن لهذا الموقف أن يسبب الكثير من المتاعب. إذا لم توجد سفارات أخرى في قرى مؤسسين آخرين بمستوى كافٍ لاستيعاب جميع أعضاء التحالف، فقد يتفرق التحالف. الاستثناء الوحيد هو إذا كان لدى أي عضو آخر في التحالف سفارة متطورة بشكل كافٍ - في هذه الحالة سيُنتخَب ذلك العضو تلقائيًا لمنصب قيادي وسينقذ التحالف. إذا لم يُعثر على مثل هذا اللاعب، سيتفرق التحالف بالكامل. <br><br> <h2>معلومات مفصّلة</h2><br> لمزيد من المعلومات المرئية والمتعمقة حول كيفية عمل هذا النظام الجديد، يمكنك زيارة هذا <a href=\"https://docs.google.com/presentation/d/1KN1qVAlxVj7aAN6F9QkRai1oliajfxKPIaJ4MSodUac/edit#slide=id.p\" target=\"_blank\">العرض التقديمي على Google</a>.");
tz_def('MANUAL_NF_D_14', "إذا ترك اللاعب رسالة واحدة على الأقل في موضوع المنتدى، سيتلقى رسائل داخل اللعبة تُعلمه أن شخصًا آخر ترك رسالة جديدة في نفس الموضوع (أي أنه \"مشترك\" فيه تقنيًا).");
tz_def('MANUAL_NF_D_15', "باستخدام هذه الميزة، يمكن لأي لاعب أن يضيف إلى وصف ملفه الشخصي صورة قبيلته مع وصف صغير (الرومان، التوتون، الغال).");
tz_def('MANUAL_NF_D_16', "في الواقع، هذه ميزة غير مفيدة للاعبين، لأنها مخصصة فقط للمسؤولين وصيادي الحسابات المتعددة. بها، سيتمكن المسؤولون وصيادو الحسابات المتعددة من إضافة صور مثيرة للاهتمام مع أوصافها إلى أوصاف ملفاتهم الشخصية.");
tz_def('MANUAL_NF_D_17', "إذا كانت هناك قطعة أثرية في إحدى القرى، سيُعرض ذلك في الملف الشخصي للاعب أمام القرية المقابلة التي توجد فيها. تم تقديم هذه الميزة في نوفاتيرا T4.");
tz_def('MANUAL_NF_D_18', "إذا كان في القرية ملعب لبناء عجيبة الدنيا، سيُعرض ذلك في الملف الشخصي مقابل القرية المقابلة. تم تقديم هذه الميزة في نوفاتيرا T4.");
tz_def('MANUAL_NF_D_19', "يتيح لك وضع الإجازة حماية إمبراطوريتك من أي أفعال عدائية من لاعبين آخرين أثناء غيابك الطويل. صحيح أن لذلك شروطًا معينة ستؤدي إلى تأخر في تطوير إمبراطوريتك. تم تقديم هذه الميزة في نوفاتيرا T4.");
tz_def('MANUAL_NF_D_20', "إذا أرسلت المنجنيقات في هجوم عادي، يمكنك رؤية الأهداف التي حددتها للهجوم في نقطة التجمع. تم تقديم هذه الميزة في نوفاتيرا T4.");
tz_def('MANUAL_NF_D_21', "بمساعدة هذه المعلومات في الدليل، يمكنك العثور على وصف لقوى الطبيعة والتتار.");
tz_def('MANUAL_NF_D_22', "موقع الروابط المباشرة يتغير. في نوفاتيرا T3.6 الأصلية، توضع الروابط المباشرة في القائمة اليمنى أسفل قائمة القرى وهو أمر غير مريح تمامًا. إذا فُعِّل هذا الخيار، ستوضع الروابط المباشرة في القائمة اليسرى وهو أكثر ملاءمة بكثير.");
tz_def('MANUAL_NF_D_23', "الوسام الذي يُمنح للاعبين الذين يخسرون بنفس عنوان البريد الإلكتروني منذ 3 سنوات أو أكثر. يمكن إضافته إلى وصف الملف الشخصي. تم تقديم هذه الميزة في نوفاتيرا T4.");
tz_def('MANUAL_NF_D_24', "الوسام الذي يُمنح للاعبين الذين يخسرون بنفس عنوان البريد الإلكتروني منذ 5 سنوات أو أكثر. يمكن إضافته إلى وصف الملف الشخصي. تم تقديم هذه الميزة في نوفاتيرا T4.");
tz_def('MANUAL_NF_D_25', "الوسام الذي يُمنح للاعبين الذين يخسرون بنفس عنوان البريد الإلكتروني منذ 10 سنوات أو أكثر. يمكن إضافته إلى وصف الملف الشخصي. تم تقديم هذه الميزة في نوفاتيرا T4.");
tz_def('MANUAL_NF_D_26', "الوسام الذي يُمنح للاعبين الذين يخسرون بنفس عنوان البريد الإلكتروني منذ 10 سنوات أو أكثر. يمكن إضافته إلى وصف الملف الشخصي. تم تقديم هذه الميزة في نوفاتيرا T4.");

// ===== per-reader localization of battle reports (token layer) =====
// Battle reports are generated server-side at resolution time, in whichever
// player's language happened to trigger the automation tick. To make the
// *body* read in each viewer's own language instead, the report builders store
// language-neutral TOKENS in the notice payload and tz_expand_report() expands
// them at display time in the reader's LANG. Token grammar (no commas, so it
// survives the CSV payload):
//   {{KEY}}            -> CONST                              (e.g. {{RC_WALL_NOT_DAMAGED}})
//   {{KEY|a|b}}        -> vsprintf(CONST, [a, b])            (args are rawurlencoded)
//   {{BLD|type|mode}}  -> building name in the reader's language
// rc_tok()/rc_bld() are the emit-side helpers (used by the report builders).
// Unknown / undefined tokens are left untouched, and old reports stored as
// plain text (pre-token) pass through unchanged.
if (!function_exists('rc_tok')) {
    function rc_tok($key /* , ...$args */) {
        $args = array_slice(func_get_args(), 1);
        if (!$args) return '{{' . $key . '}}';
        return '{{' . $key . '|' . implode('|', array_map('rawurlencode', $args)) . '}}';
    }
}
if (!function_exists('rc_bld')) {
    function rc_bld($type, $mode = 0) {
        return '{{BLD|' . (int) $type . '|' . (int) $mode . '}}';
    }
}
if (!function_exists('tz_build_name')) {
    function tz_build_name($type, $mode = 0) {
        $b = Building::procResType((int) $type);
        return ($b === '' && !$mode) ? RC_VILLAGE_CANT_BE : $b;
    }
}
if (!function_exists('tz_expand_report')) {
    function tz_expand_report($s) {
        if (!is_string($s) || strpos($s, '{{') === false) return $s;
        return preg_replace_callback('/\{\{([A-Z0-9_]+)((?:\|[^|{}]*)*)\}\}/', function ($m) {
            $key  = $m[1];
            $args = ($m[2] === '') ? [] : array_map('rawurldecode', explode('|', substr($m[2], 1)));
            if ($key === 'BLD') {
                return tz_build_name($args[0] ?? 0, $args[1] ?? 0);
            }
            if (!defined($key)) return $m[0];
            return $args ? vsprintf(constant($key), $args) : constant($key);
        }, $s);
    }
}

// ===== display-time localization of stored report topics =====
// Reports are generated server-side at battle resolution and stored in the DB
// (column `topic`) with English connectors. This rewrites them to the viewing
// player's language at display time (works for old AND new reports).
if (!function_exists('tz_loc_topic')) {
    function tz_loc_topic($s) {
        if (!is_string($s) || $s === '') return $s;
        // strtr does longest-match, single-pass (no double substitution).
        $map = array(
            'village of the elders reinforcement ' => TZ_RT_ELDERS_REINF.' ',
            'Reinforcement in '                    => TZ_RT_REINF_IN.' ',
            ' was attacked'                        => ' '.TZ_RT_WAS_ATTACKED,
            ' send resources to '                  => ' '.TZ_RT_SEND_RES_TO.' ',
            ' scouts '                             => ' '.TZ_RT_SCOUTS.' ',
            ' attacks '                            => ' '.TZ_RT_ATTACKS.' ',
            ' reinforcement '                      => ' '.TZ_RT_REINFORCEMENT.' ',
            'Unoccupied Oasis'                     => TZ_RT_UNOCC_OASIS,
            'New village founded'                  => TZ_RT_NEW_VILLAGE,
            'Settlers returned - valley occupied'  => TZ_RT_VALLEY_OCCUPIED,
            // T4 hero port (Phase 6)
            'Hero returned from an adventure'      => TZ_RT_ADV_RETURNED,
            'Hero fell on an adventure'             => TZ_RT_ADV_FELL,
            'Auction won'                          => TZ_RT_AUC_WON,
            'Auction sold'                         => TZ_RT_AUC_SOLD,
            'Auction expired'                      => TZ_RT_AUC_EXPIRED,
        );
        return strtr($s, $map);
    }
}

/* =============================================================================
 * T4 HERO PORT (Phase 6) - items / adventures / auction house
 * ========================================================================== */
tz_def('HERO_T4_TAB_HERO',       'البطل');
tz_def('HERO_T4_TAB_OASIS',      'الواحة');
tz_def('HERO_T4_TAB_ITEMS',      'المخزون');
tz_def('HERO_T4_TAB_ADVENTURES', 'المغامرات');
tz_def('HERO_T4_TAB_AUCTION',    'المزادات');

tz_def('HERO_EXCHANGE', 'مكتب الصرف');
tz_def('HERO_EXCHANGE_G2S', 'ذهب إلى فضة');
tz_def('HERO_EXCHANGE_S2G', 'فضة إلى ذهب');
tz_def('HERO_EXCHANGE_OK', 'اكتمل التبادل.');
tz_def('HERO_EXCHANGE_NOTENOUGH', 'لا تملك ما يكفي لهذا التبادل.');
tz_def('HERO_EXCHANGE_FAIL', 'تعذر إتمام التبادل.');
tz_def('HERO_EXCHANGE_HINT', 'أدخل الكمية التي تريد إعطاءها. الفضة المتبقية أقل من وحدة ذهب واحدة تبقى معك.');
tz_def('HERO_RES_PRODUCTION', 'الموارد');
tz_def('HERO_RES_TYPE', 'المورد المنتَج');
tz_def('HERO_RES_ALL', 'كل الموارد');
tz_def('HERO_RES_TYPE_HINT', 'يمكن تغييره في أي وقت، مجانًا.');
tz_def('HERO_SILVER',            'الفضة');
tz_def('HERO_EXPERIENCE',        'الخبرة');
tz_def('RESOURCES',              'الموارد');

/* Equipment slots */
tz_def('HERO_SLOT_1', 'الخوذة');
tz_def('HERO_SLOT_2', 'الجسد');
tz_def('HERO_SLOT_3', 'اليد اليمنى');
tz_def('HERO_SLOT_4', 'اليد اليسرى');
tz_def('HERO_SLOT_5', 'الحذاء');
tz_def('HERO_SLOT_6', 'الحصان');
tz_def('HERO_SLOT_7', 'الحقيبة');

/* Inventory actions */
tz_def('HERO_ITEMS_EQUIPPED',    'مرتدى');
tz_def('HERO_ITEMS_BAG',         'المخزون');
tz_def('HERO_ITEMS_EMPTY',       'لا يملك بطلك أي عناصر بعد. المغامرات ودار المزادات أماكن جيدة للعثور على بعضها.');
tz_def('HERO_EQUIP',             'ارتداء');
tz_def('HERO_UNEQUIP',           'خلع');
tz_def('HERO_USE_ITEM',          'استخدام');
tz_def('HERO_QUANTITY',          'الكمية');
tz_def('HERO_ITEM_USED_OK',      'تم استخدام العنصر.');
tz_def('HERO_ITEM_USE_FAIL',     'لا يمكن استخدام هذا العنصر الآن.');
tz_def('HERO_ITEM_USE_BATTLE',   'يُستخدم هذا العنصر تلقائيًا (الضمادات تشفي القوات العائدة بعد المعارك).');
tz_def('HERO_EQUIP_OK',          'تم ارتداء العنصر.');
tz_def('HERO_LOCKED_NOHERO', 'ليس لديك بطل بعد. درّب واحدًا في قصر البطل قبل ارتداء العناصر.');
tz_def('HERO_EQUIP_FAIL',        'لا يمكن ارتداء هذا العنصر (وحدة بطل خاطئة أو نوع عنصر خاطئ).');
tz_def('HERO_UNEQUIP_OK',        'تم خلع العنصر.');

/* Adventures */
tz_def('HERO_ADV_NORMAL',        'مغامرة عادية');
tz_def('HERO_ADV_HARD',          'مغامرة صعبة');
tz_def('HERO_ADV_LIST',          'المغامرات المتاحة');
tz_def('HERO_ADV_NONE',          'لا توجد مغامرات متاحة الآن. تظهر مغامرات جديدة بمرور الوقت.');
tz_def('HERO_ADV_DIFFICULTY',    'الصعوبة');
tz_def('HERO_ADV_DIFF_NORMAL',   'عادية');
tz_def('HERO_ADV_DIFF_HARD',     'صعبة');
tz_def('HERO_ADV_PLACE', 'المكان');
tz_def('HERO_ADV_DANGER', 'الخطورة');
tz_def('HERO_ADV_LINK', 'الرابط');
tz_def('HERO_ADV_DURATION',      'وقت السفر (اتجاه واحد)');
tz_def('HERO_ADV_EXPIRES',       'تنتهي خلال');
tz_def('HERO_ADV_GO',            'بدء المغامرة');
tz_def('HERO_ADV_RUNNING',       'بطلك في مغامرة وسيصل خلال');
tz_def('HERO_ADV_START_OK',      'انطلق بطلك في المغامرة.');
tz_def('HERO_ADV_START_NOHERO',  'تحتاج إلى بطل حي لبدء مغامرة.');
tz_def('HERO_ADV_START_AWAY',    'بطلك ليس في المنزل.');
tz_def('HERO_ADV_START_FAIL',    'هذه المغامرة لم تعد متاحة.');
tz_def('HERO_ADV_RETURNED',      'عاد بطلك من');
tz_def('HERO_ADV_REWARD',        'المكافأة');
tz_def('HERO_ADV_AMOUNT',        'الكمية');
tz_def('HERO_ADV_ITEM_FOUND',    'عُثر على عنصر');
tz_def('HERO_ADV_HP_LOST',       'الصحة المفقودة');
tz_def('HERO_ADV_DIED',          'سقط بطلك في');
tz_def('HERO_ADV_DIED_INFO',     "فُقدت كل الغنائم. يمكن إحياء البطل في قصر البطل.");

/* Auction house */
tz_def('HERO_AUC_OPEN',          'المزادات المفتوحة');
tz_def('HERO_AUC_NONE',          'لا توجد مزادات مفتوحة حاليًا.');
tz_def('HERO_AUC_ITEM',          'العنصر');
tz_def('HERO_AUC_PRICE',         'السعر الحالي');
tz_def('HERO_AUC_FINAL_PRICE',   'السعر النهائي');
tz_def('HERO_AUC_TIME_LEFT',     'ينتهي خلال');
tz_def('HERO_AUC_YOUR_MAX',      'الحد الأقصى الخاص بك');
tz_def('HERO_AUC_BID',           'مزايدة');
tz_def('HERO_AUC_BID_OK',        'تم تسجيل مزايدتك. أنت صاحب أعلى مزايدة.');
tz_def('HERO_AUC_BID_OUTBID',    'تم تجاوز مزايدتك فورًا بحد أقصى أعلى.');
tz_def('HERO_AUC_BID_FAIL',      'لم تُقبل مزايدتك.');
tz_def('HERO_AUC_BID_NOSILVER',  'لا تملك فضة كافية لهذه المزايدة.');
tz_def('HERO_AUC_MY_BIDS',       'مزايداتي');
tz_def('HERO_AUC_MY_SALES',      'مبيعاتي');
tz_def('HERO_AUC_SELL',          'بيع عنصر');
tz_def('HERO_AUC_SELL_OK',       'تم إدراج عنصرك.');
tz_def('HERO_AUC_SELL_FAIL',     'لا يمكن إدراج هذا العنصر (يجب خلع العناصر المرتداة أولًا).');
tz_def('HERO_AUC_START_PRICE',   'سعر البداية');
tz_def('HERO_AUC_DURATION',      'المدة');
tz_def('HERO_AUC_LIST',          'إدراج عنصر');
tz_def('HERO_AUC_SELLER_NPC',    'تاجر');
tz_def('HERO_AUC_WON',           'فزت بالمزاد على');
tz_def('HERO_AUC_SOLD',          'بيع مزادك:');
tz_def('HERO_AUC_REFUND',        'مسترد من حد مزايدتك الأقصى');
tz_def('HERO_AUC_FEE',           'رسوم المزاد');
tz_def('HERO_AUC_PAYOUT',        'المستحقات');
tz_def('HERO_AUC_EXPIRED',       'انتهى مزادك دون مزايدات. أُعيد العنصر إلى مخزونك:');

/* Report topics (raw English strings live in ndata.topic) */
tz_def('TZ_RT_ADV_RETURNED',     'عاد البطل من مغامرة');
tz_def('TZ_RT_ADV_FELL',         'سقط البطل في مغامرة');
tz_def('TZ_RT_AUC_WON',          'فوز في المزاد');
tz_def('TZ_RT_AUC_SOLD',         'بيع في المزاد');
tz_def('TZ_RT_AUC_EXPIRED',      'انتهى المزاد');

/* T4 hero port - movement display (dorf1 + rally point) */
tz_def('HERO_ADV_MOV_OUT',   'البطل في مغامرة');
tz_def('HERO_ADV_MOV_BACK',  'البطل عائد من مغامرة');
tz_def('HERO_ADV_MOV_SHORT', 'مغامرة');

/* T4 hero port - tooltip effect fragments (heroItemBonusText) */
tz_def('HB_TXT_FIGHT',      'قوة القتال');
tz_def('HB_TXT_UNIT_OFF',   'الهجوم');
tz_def('HB_TXT_UNIT_DEF',   'الدفاع');
tz_def('HB_TXT_PER',        'لكل');
tz_def('HB_TXT_VS_NATARS',  'قوة ضد التتار');
tz_def('HB_TXT_RAID',       'الموارد المنهوبة');
tz_def('HB_TXT_RETURN',     'سرعة عودة القوات');
tz_def('HB_TXT_SPEED_OWN',  'السرعة بين قراك الخاصة');
tz_def('HB_TXT_SPEED_ALLY', 'السرعة بين قرى الحلفاء');
tz_def('HB_TXT_XP',         'الخبرة');
tz_def('HB_TXT_REGEN',      'نقاط الصحة يوميًا');
tz_def('HB_TXT_CP',         'نقاط الثقافة يوميًا');
tz_def('HB_TXT_TRAIN_CAV',  'وقت تدريب الفرسان');
tz_def('HB_TXT_TRAIN_INF',  'وقت تدريب المشاة');
tz_def('HB_TXT_DMG',        'الضرر الذي يتلقاه البطل');
tz_def('HB_TXT_SPEED20',    'سرعة القوات بعد 20 مربعًا');
tz_def('HB_TXT_MOUNT',      'حقل/ساعة أثناء الامتطاء');
tz_def('HB_TXT_HORSE',      'حقل/ساعة سرعة البطل');
tz_def('HB_TXT_OINTMENT',   'يشفي 1% من صحة البطل لكل وحدة (بحد أقصى 99%)');
tz_def('HB_TXT_SCROLL',     'خبرة البطل لكل واحدة');
tz_def('HB_TXT_BUCKET',     'يُحيي البطل فورًا دون تكلفة');
tz_def('HB_TXT_TABLET',     '+1% ولاء قريتك الخاصة لكل واحدة (بحد أقصى 125%)');
tz_def('HB_TXT_BOOK',       'يعيد ضبط نقاط سمات البطل');
tz_def('HB_TXT_ARTWORK',    'نقاط ثقافة تساوي إنتاجك اليومي');
tz_def('HB_TXT_BANDAGE_A',  'يشفي');
tz_def('HB_TXT_BANDAGE_B',  "من قوات البطل الساقطة بعد المعركة");
tz_def('HB_TXT_CAGE',       'يأسر حيوان واحة واحد لكل قفص');
tz_def('HB_TXT_NEEDS_HORSE','يتطلب حصانًا مرتديًا');
tz_def('HB_TXT_ONLY',       'فقط لـ');
tz_def('HB_TXT_HERO',       'بطل');

/* T4 hero port - captured animals release action */
tz_def('HERO_RELEASE_ANIMALS', 'إطلاق');
tz_def('HERO_RELEASE_CONFIRM', 'إطلاق هذه الحيوانات؟ ستختفي إلى الأبد.');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// PANOU DE ADMINISTRARE - etichete de meniu si titluri de pagina
// (erau scrise direct in Admin/admin.php, deci meniul ramanea in engleza)
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ADMIN_LOGOUT', 'تسجيل الخروج');
tz_def('ADMIN_SERVER_HOMEPAGE', 'الصفحة الرئيسية للخادم');
tz_def('ADMIN_CONTROL_PANEL_HOME', 'الصفحة الرئيسية للوحة التحكم');
tz_def('ADMIN_RETURN_TO_THE_SERVER', 'العودة إلى الخادم');
tz_def('ADMIN_SERVER_INFO', 'معلومات الخادم');
tz_def('ADMIN_ONLINE_USERS', 'المستخدمون المتصلون');
tz_def('ADMIN_PLAYERS_NOT_ACTIVATED', 'اللاعبون غير المُفعَّلين');
tz_def('ADMIN_PLAYERS_INACTIVATE', 'إلغاء تفعيل اللاعبين');
tz_def('ADMIN_PLAYERS_REPORT', 'تقرير اللاعبين');
tz_def('ADMIN_PLAYERS_MESSAGE', 'رسالة إلى اللاعبين');
tz_def('ADMIN_MAP', 'الخريطة');
tz_def('ADMIN_MAP_TILE', 'مربّع الخريطة');
tz_def('ADMIN_NATARS_MANAGEMENT', 'إدارة التتار');
tz_def('ADMIN_SEARCH', 'بحث');
tz_def('ADMIN_GENERAL_SEARCH', 'بحث عام');
tz_def('ADMIN_SEARCH_IGMS_REPORTS', 'بحث في الرسائل/التقارير');
tz_def('ADMIN_MESSAGES', 'الرسائل');
tz_def('ADMIN_CREATE_MASS_MESSAGE', 'إنشاء رسالة جماعية');
tz_def('ADMIN_CREATE_SYSTEM_MESSAGE', 'إنشاء رسالة نظام');
tz_def('ADMIN_BAN', 'حظر');
tz_def('ADMIN_BAN_UNBAN_PLAYERS', 'حظر/إلغاء حظر اللاعبين');
tz_def('ADMIN_CLEAN_BANLIST_DATA', 'تنظيف بيانات قائمة الحظر');
tz_def('ADMIN_GOLD', 'الذهب');
tz_def('ADMIN_GIVE_ALL_FREE_GOLD', 'منح الجميع ذهبًا مجانيًا');
tz_def('ADMIN_GIVE_FREE_GOLD_TO_SPECIFIC_USER', 'منح ذهب مجاني لمستخدم محدد');
tz_def('ADMIN_GOLD_SHOP_PROMO_CODES', 'متجر الذهب وأكواد العروض');
tz_def('ADMIN_FEATURE_FLAGS', 'مفاتيح تفعيل الميزات');
tz_def('ADMIN_RESET_GOLD', 'إعادة تعيين الذهب');
tz_def('ADMIN_CENTRAL_GOLD', 'الذهب المركزي (عبر العوالم)');
tz_def('ADM_CG_NOT_CONFIGURED', 'الذهب المركزي غير مُهيَّأ على هذا الخادم. اضبط ثوابت CENTRAL_GOLD_* في config.php لتفعيل الذهب المدفوع عبر العوالم.');
tz_def('ADM_CG_FREE_GOLD_SETTING', 'الذهب المجاني (X-Tatar)');
tz_def('ADM_CG_FREE_GOLD_DESC', 'يتحكم فيما إذا كان بإمكان اللاعبين الحصول على ذهب مجاني يُضاف من نشاطهم على X-Tatar.com. لا يؤثر هذا على الذهب المدفوع، الذي يبقى مفعّلاً دائمًا بمجرد إعداده.');
tz_def('ADM_CG_FREE_GOLD_ENABLED', 'مفعّل &ndash; يحصل اللاعبون على ذهب مجاني من نشاط X-Tatar');
tz_def('ADM_CG_FREE_GOLD_DISABLED', 'معطّل &ndash; الذهب المجاني مخفي/متوقف لجميع اللاعبين');
tz_def('ADM_CG_SAVE', 'حفظ');
tz_def('ADM_CG_LOOKUP_TITLE', 'البحث عن لاعب');
tz_def('ADM_CG_LOOKUP_DESC', 'ابحث بالبريد الإلكتروني الذي سجّل به اللاعب لرؤية رصيد الذهب المدفوع القابل للنقل والعوالم التي لعب عليها.');
tz_def('ADM_CG_EMAIL', 'البريد الإلكتروني');
tz_def('ADM_CG_SEARCH', 'بحث');
tz_def('ADM_CG_NO_ACCOUNT_FOUND', 'لا يوجد حساب ذهب مركزي لهذا البريد الإلكتروني.');
tz_def('ADM_CG_PAID_BALANCE', 'رصيد الذهب المدفوع');
tz_def('ADM_CG_USERNAME', 'اسم المستخدم');
tz_def('ADM_CG_WORLDS_SEEN', 'العوالم التي شُوهد فيها');
tz_def('ADM_CG_NO_WORLDS', 'غير مرتبط بأي عالم بعد.');
tz_def('ADM_CG_LAST_SEEN', 'آخر ظهور');
tz_def('ADM_CG_TRANSFER_TITLE', 'تحويل الذهب المدفوع');
tz_def('ADM_CG_TRANSFER_DESC', 'انقل الذهب المدفوع من لاعب إلى آخر، بواسطة البريد الإلكتروني، عبر أي عالم. الذهب المدفوع فقط قابل للتحويل &mdash; الذهب المجاني يبقى دائمًا محليًا في العالم الذي كُسب فيه.');
tz_def('ADM_CG_FROM_EMAIL', 'من (البريد الإلكتروني)');
tz_def('ADM_CG_TO_EMAIL', 'إلى (البريد الإلكتروني)');
tz_def('ADM_CG_AMOUNT', 'الكمية');
tz_def('ADM_CG_NOTE_OPTIONAL', 'ملاحظة (اختياري)');
tz_def('ADM_CG_TRANSFER_BTN', 'تحويل');
tz_def('ADM_CG_RECENT_LEDGER', 'السجل الأخير (كل العوالم)');
tz_def('ADM_CG_NO_LEDGER', 'لا توجد سجلات بعد.');
tz_def('ADM_CG_WHEN', 'الوقت');
tz_def('ADM_CG_PLAYER', 'اللاعب');
tz_def('ADM_CG_WORLD', 'العالم');
tz_def('ADM_CG_DELTA', 'التغيير');
tz_def('ADM_CG_BALANCE_AFTER', 'الرصيد بعد العملية');
tz_def('ADM_CG_REASON', 'السبب');
tz_def('ADM_CG_SEARCH_LABEL', 'أو ابحث باسم المستخدم');
tz_def('ADM_CG_SEARCH_PLACEHOLDER', 'ابدأ بكتابة اسم المستخدم...');
tz_def('ADM_CG_SEARCH_NO_RESULTS', 'لا يوجد لاعبون مطابقون.');
tz_def('ADM_CG_SEARCH_MIN_CHARS', 'اكتب حرفين على الأقل للبحث.');

// Linked accounts / Feeding System (see GameEngine/FeedingSystem.php)
// Independent from Multi-Account Detection (ADMIN_MULTI_ACCOUNT_DETECTION) —
// that is anti-cheat heuristics; this is an opt-in, admin-capped gameplay
// allowance that lets a player raid their own declared alt account(s)
// without warehouse/cranny loot protection.
tz_def('ADMIN_FEEDING_SYSTEM', 'الحسابات المرتبطة (التغذية)');
tz_def('ADM_FS_INTRO', 'تتيح للاعب إعلان حساب واحد أو أكثر من حساباته الخاصة في هذا العالم كحسابات "مرتبطة" (تُغذَّى). نهب حساب مرتبط يتجاهل حماية المخزن/المخبأ، لذا يمكن نهب كامل المخزون (محدودًا فقط بسعة حمل القوات المهاجمة نفسها). هذا مستقل عن كشف الحسابات المتعددة أدناه، الذي يكتفي بوضع علامة على الأزواج المشبوهة للمراجعة اليدوية ولا يغيّر أسلوب اللعب أبدًا.');
tz_def('ADM_FS_MULTIACC_NOTE', 'هذا نظام مختلف عن كشف الحسابات المتعددة. تلك الأداة تُقيّم فقط الأزواج المشبوهة ليراجعها إنسان — لا تحظر أحدًا أبدًا ولا تغيّر آلية عمل الغارات. أما هذه الأداة فتغيّر فعليًا سلوك الغارات، لكن فقط بين الحسابات التي رَبطها أدمن أو لاعب صراحةً هنا.');
tz_def('ADM_FS_SETTINGS_TITLE', 'الإعدادات');
tz_def('ADM_FS_SETTINGS_DESC', 'فعّل/عطّل الميزة وحدد عدد الحسابات المسموح لكل لاعب ربطها بنفسه.');
tz_def('ADM_FS_ENABLED', 'مفعّل');
tz_def('ADM_FS_DISABLED', 'معطّل');
tz_def('ADM_FS_MAX_LINKED', 'الحد الأقصى للحسابات المرتبطة لكل لاعب');
tz_def('ADM_FS_ANNOUNCED', 'تم الإعلان عن هذا في قواعد الخادم');
tz_def('ADM_FS_ANNOUNCE_WARNING', 'يُنصح بشدة بالإعلان عن هذا النظام في قواعد الخادم العامة قبل تفعيله، حتى يفهم اللاعبون سبب إمكانية نهب بعض الحسابات دون الحد المعتاد للنهب.');
tz_def('ADM_FS_SAVE', 'حفظ');
tz_def('ADM_FS_ADD_TITLE', 'ربط الحسابات يدويًا');
tz_def('ADM_FS_ADD_DESC', 'اربط حسابين باسم المستخدم مباشرة من لوحة الأدمن. الروابط المضافة هنا تتجاوز الحد الذاتي لكل لاعب.');
tz_def('ADM_FS_OWNER_USERNAME', 'الحساب الرئيسي (المغير)');
tz_def('ADM_FS_LINKED_USERNAME', 'الحساب المرتبط (المُغذّى)');
tz_def('ADM_FS_LINK_BTN', 'ربط');
tz_def('ADM_FS_ALL_LINKS_TITLE', 'كل الأزواج المرتبطة');
tz_def('ADM_FS_NO_LINKS', 'لا توجد حسابات مرتبطة بعد.');
tz_def('ADM_FS_ADDED', 'أُضيف في');
tz_def('ADM_FS_ADDED_BY', 'أُضيف بواسطة');
tz_def('ADM_FS_BADGE_ADMIN', 'أدمن');
tz_def('ADM_FS_BADGE_SELF', 'لاعب');
tz_def('ADM_FS_REMOVE', 'إزالة');
tz_def('ADM_FS_CONFIRM_REMOVE', 'إزالة هذا الرابط؟');

tz_def('ADMIN_RELATED_ACCOUNT_PROTECTION', 'حماية الحسابات المرتبطة');
tz_def('ADM_RAP_INTRO', 'تتيح للأدمن تحديد حسابين يدويًا في هذا العالم كـ"مرتبطين" (نفس المالك، نفس الجهاز، أو تأكد بأي طريقة أخرى أنهما لنفس الشخص). يُمنع النهب وتحويل الموارد عبر السوق في الاتجاهين بين أي زوج مرتبط — تُحسم المعركة بشكل طبيعي لكن بدون أي موارد منهوبة، وأي محاولة تحويل موارد عبر السوق بين الزوج تُرفض تمامًا. هذا النظام مستقل عن كشف الحسابات المتعددة (الذي يكتفي بتقييم الأزواج المشبوهة للمراجعة اليدوية)، وعن العضويات التابعة (Feeding) التي تفعل العكس تمامًا (تلغي حد النهب بين الحسابات التي يوافق عليها اللاعب).');
tz_def('ADM_RAP_PRIORITY_NOTE', 'لو نفس الزوج معلن أيضًا كـ"مرتبط" في نظام العضويات التابعة (Feeding)، فإن هذه الحماية تكسب دائمًا: يُمنع النهب بينهما بغض النظر عن أي إعدادات فيدينج لهذا الزوج. تحويل الموارد عبر السوق بين زوج مرتبط ممنوع دائمًا بدون أي حد جزئي أو سقف يومي أو فترة انتظار — المسموح به صفر.');
tz_def('ADM_RAP_SETTINGS_TITLE', 'الإعدادات');
tz_def('ADM_RAP_SETTINGS_DESC', 'فعّل أو عطّل منع النهب بين الأزواج المرتبطة. هذا لا يحذف أي علاقات أضفتها من قبل — يتحكم فقط في تفعيلها حاليًا.');
tz_def('ADM_RAP_ENABLED', 'مفعّل');
tz_def('ADM_RAP_DISABLED', 'معطّل');
tz_def('ADM_RAP_SAVE', 'حفظ');
tz_def('ADM_RAP_ADD_TITLE', 'تحديد الحسابات كمرتبطة');
tz_def('ADM_RAP_ADD_DESC', 'حدد حسابين باسم المستخدم كمرتبطين. هذا للأدمن فقط — لا يمكن للاعبين فعل ذلك بأنفسهم، والاتجاه لا يهم (يُمنع النهب في الاتجاهين).');
tz_def('ADM_RAP_USERNAME_A', 'الحساب');
tz_def('ADM_RAP_USERNAME_B', 'الحساب المرتبط');
tz_def('ADM_RAP_REASON', 'السبب (اختياري)');
tz_def('ADM_RAP_RELATE_BTN', 'تحديد كمرتبط');
tz_def('ADM_RAP_ALL_TITLE', 'كل الأزواج المرتبطة');
tz_def('ADM_RAP_NO_RELATIONS', 'لا توجد حسابات محددة كمرتبطة بعد.');
tz_def('ADM_RAP_ADDED', 'أُضيف في');
tz_def('ADM_RAP_BADGE_BLOCKED', 'النهب ممنوع');
tz_def('ADM_RAP_REMOVE', 'إزالة');
tz_def('ADM_RAP_CONFIRM_REMOVE', 'إزالة هذه العلاقة؟ لن يُمنع النهب بعدها بين هذين الحسابين.');
tz_def('ADM_RAP_TRANSFERS_TITLE', 'محاولات التحويل المرفوضة');
tz_def('ADM_RAP_TRANSFERS_DESC', 'محاولات إرسال موارد عبر السوق تم رفضها لأن المرسل والمستقبل زوج معلن كمرتبط. الأزواج المرتبطة تحصل دائمًا على صفر مسموح به للتحويل — لا يوجد حد جزئي يُسجَّل مقابله.');
tz_def('ADM_RAP_NO_TRANSFER_VIOLATIONS', 'لا توجد محاولات تحويل مرفوضة مسجَّلة بعد.');
tz_def('ADM_RAP_ATTEMPTED_AMOUNT', 'الكمية المحاولة');
tz_def('ADM_RAP_AUTOBAN_TITLE', 'حظر تلقائي عند المحاولة');
tz_def('ADM_RAP_AUTOBAN_DESC', 'عند التفعيل، أي محاولة نهب أو تحويل موارد عبر السوق بين زوج معلن كمرتبط — حتى لو كانت أصلًا محظورة ولا تنتج عنها أي مكاسب — تُحظر فورًا الحساب المهاجم أو المُرسِل فقط (وليس الهدف)، من أول محاولة. لا تأثير له إذا كانت حماية الحسابات المرتبطة أعلاه غير مفعّلة.');
tz_def('ADM_RAP_AUTOBAN_ACTIVE_NOTE', 'الحظر التلقائي عند المحاولة مفعّل: أي محاولة نهب أو تحويل ضد أحد الأزواج أدناه ستؤدي فورًا لحظر الحساب المهاجم/المُرسِل.');

// Player-facing side (see spieler.php / feeding.tpl)
tz_def('FS_PLAYER_TITLE', 'الحسابات المرتبطة');
tz_def('FS_PLAYER_INTRO', 'يمكنك ربط حسابك (حساباتك) البديلة في هذا العالم حتى تتمكن من نهبها بحرية للتدريب أو مشاركة الموارد، دون الحد المعتاد لنهب المخزن/المخبأ.');
tz_def('FS_PLAYER_DISABLED_NOTICE', 'هذه الميزة معطّلة حاليًا من قِبل أدمن الخادم.');
tz_def('FS_PLAYER_LIMIT_NOTICE', 'يمكنك ربط حتى %d حساب (حسابات). لديك حاليًا %d مرتبط(ة).');
tz_def('FS_PLAYER_USERNAME_LABEL', 'اسم المستخدم المراد ربطه');
tz_def('FS_PLAYER_ADD_BTN', 'إضافة رابط');
tz_def('FS_PLAYER_NO_LINKS', 'لم تربط أي حسابات بعد.');
tz_def('FS_PLAYER_REMOVE', 'إزالة');
tz_def('FS_PLAYER_CONFIRM_REMOVE', 'إزالة هذا الحساب المرتبط؟');
tz_def('FS_PLAYER_LINKED_SINCE', 'مرتبط منذ');
tz_def('FS_ERR_FEATURE_DISABLED', 'هذه الميزة معطّلة حاليًا من قِبل أدمن الخادم.');
tz_def('FS_ERR_CANNOT_LINK_SELF', 'لا يمكنك ربط حسابك بنفسه.');
tz_def('FS_ERR_ACCOUNT_NOT_FOUND', 'لا يوجد حساب بهذا اسم المستخدم.');
tz_def('FS_ERR_ALREADY_LINKED', 'هذا الحساب مرتبط بالفعل.');
tz_def('FS_ERR_LIMIT_REACHED', 'لقد وصلت إلى الحد الأقصى لعدد الحسابات المرتبطة.');
tz_def('FS_ERR_INVALID_ACCOUNT', 'حساب غير صالح.');
tz_def('FS_ERR_GENERIC', 'حدث خطأ ما — الرجاء المحاولة مرة أخرى.');
tz_def('FS_SUCCESS_ADDED', 'تم ربط الحساب بنجاح.');
tz_def('FS_SUCCESS_REMOVED', 'تمت إزالة الرابط.');


// X-Tatar activity-based free gold (see GameEngine/XTatarGold.php)
tz_def('ADM_XG_TITLE', 'ذهب X-Tatar المجاني');
tz_def('ADM_XG_INTRO', 'يكسب اللاعبون ذهبًا مجانيًا بناءً على نشاطهم (تسجيل الدخول اليومي داخل اللعبة، أو نشاط X-Tatar.com عبر webhook). هذا الذهب محلي لهذا العالم ولا يُنقل إلى عوالم أخرى.');
tz_def('ADM_XG_SETTINGS_TITLE', 'الإعدادات');
tz_def('ADM_XG_SETTINGS_DESC', 'فعّل/عطّل الميزة وتحكم في كيفية تحويل نقاط النشاط إلى ذهب.');
tz_def('ADM_XG_ENABLED', 'مفعّل');
tz_def('ADM_XG_DISABLED', 'معطّل');
tz_def('ADM_XG_POINTS_PER_GOLD', 'نقاط مقابل ذهبة واحدة');
tz_def('ADM_XG_DAILY_LOGIN_POINTS', 'نقاط تسجيل الدخول اليومي');
tz_def('ADM_XG_DAILY_CAP_POINTS', 'الحد اليومي (0 = بلا حدود)');
tz_def('ADM_XG_WEBHOOK_SECRET', 'المفتاح السري لـ Webhook');
tz_def('ADM_XG_WEBHOOK_SECRET_PLACEHOLDER', 'اتركه فارغًا لتعطيل الـ webhook');
tz_def('ADM_XG_WEBHOOK_HELP', 'يجب أن يرسل X-Tatar.com طلب POST إلى xtatar_gold_webhook.php بهذا المفتاح السري لمنح نقاط عن النشاط خارج اللعبة. يرفض الـ webhook كل الطلبات حتى يتم ضبط مفتاح سري هنا.');
tz_def('ADM_XG_SAVE', 'حفظ الإعدادات');
tz_def('ADM_XG_ADJUST_TITLE', 'تعديل نقاط اللاعب يدويًا');
tz_def('ADM_XG_ADJUST_DESC', 'أضف أو اخصم نقاط نشاط للاعب محدد (مثلاً لتصحيح فجوة في الـ webhook). أي كمية موجبة تُكمل ذهبة كاملة تُحوَّل فورًا.');
tz_def('ADM_XG_USERNAME', 'اسم المستخدم');
tz_def('ADM_XG_POINTS_DELTA', 'النقاط (+/-)');
tz_def('ADM_XG_NOTE_OPTIONAL', 'ملاحظة (اختياري)');
tz_def('ADM_XG_ADJUST_BTN', 'تطبيق');
tz_def('ADM_XG_NO_PLAYER_FOUND', 'لا يوجد لاعب بهذا اسم المستخدم.');
tz_def('ADM_XG_CURRENT_POINTS', 'النقاط الحالية');
tz_def('ADM_XG_PLAYER_HISTORY', 'سجل اللاعب');
tz_def('ADM_XG_NO_HISTORY', 'لا يوجد سجل بعد.');
tz_def('ADM_XG_TOP_EARNERS', 'الأعلى كسبًا');
tz_def('ADM_XG_NO_DATA', 'لا توجد بيانات بعد.');
tz_def('ADM_XG_TOTAL_EARNED', 'إجمالي النقاط المكتسبة');
tz_def('ADM_XG_TOTAL_GOLD_CONVERTED', 'إجمالي الذهب المُحوَّل');
tz_def('ADM_XG_RECENT_LOG', 'النشاط الأخير (كل اللاعبين)');
tz_def('ADM_XG_WHEN', 'الوقت');
tz_def('ADM_XG_TYPE', 'النوع');
tz_def('ADM_XG_POINTS', 'النقاط');
tz_def('ADM_XG_GOLD', 'الذهب');
tz_def('ADM_XG_SOURCE', 'المصدر');

tz_def('ADMIN_PLUS_RES_BONUS', 'مكافأة بلس والموارد');
tz_def('ADMIN_GIVE_ALL_PLUS', 'منح الجميع بلس');
tz_def('ADMIN_RESET_PLUS', 'إعادة تعيين بلس');
tz_def('ADMIN_GIVE_ALL_RES_BONUS', 'منح الجميع مكافأة الموارد');
tz_def('ADMIN_RESET_RES_BONUS', 'إعادة تعيين مكافأة الموارد');
tz_def('ADMIN_USERS', 'المستخدمون');
tz_def('ADMIN_LIST_USERS', 'قائمة المستخدمين');
tz_def('ADMIN_CREATE_USERS', 'إنشاء مستخدمين');
tz_def('ADMIN_MULTI_ACCOUNT_DETECTION', 'كشف الحسابات المتعددة');
tz_def('ADMIN_PUSH_PROTECTION', 'حماية الدفع');
tz_def('ADMIN_REGISTRATION_BLOCKLIST', 'قائمة حظر التسجيل');
tz_def('ADMIN_ADMIN', 'الأدمن');
tz_def('ADMIN_ADMIN_LOG', 'سجل الأدمن');
tz_def('ADMIN_QUEST_EDITOR', 'محرر المهام');
tz_def('ADMIN_WORLD_MAP_HEATMAP', 'خريطة حرارية للعالم');
tz_def('ADMIN_DEBUG_ERROR_LOG', 'سجل أخطاء التصحيح');
tz_def('ADMIN_SERVER_SETTINGS', 'إعدادات الخادم');
tz_def('ADMIN_SERVER_MAINTENANCE', 'صيانة الخادم');
tz_def('ADMIN_SERVER_RESETTING', 'إعادة تعيين الخادم');
tz_def('ADMIN_IN_GAME_MESSAGES', 'رسائل داخل اللعبة');
tz_def('ADMIN_MSG_REP', 'رسائل/تقارير');
tz_def('ADMIN_MASS_MESSAGE', 'رسالة جماعية');
tz_def('ADMIN_SYSTEM_MESSAGE', 'رسالة نظام');
tz_def('ADMIN_DELETE_PLAYER_MEDALS', 'حذف أوسمة اللاعب');
tz_def('ADMIN_DELETE_ALLY_MEDALS', 'حذف أوسمة التحالف');
tz_def('ADMIN_USERS_LIST', 'قائمة المستخدمين');
tz_def('ADMIN_SERVER_CONFIGURATION', 'إعدادات تكوين الخادم');
tz_def('ADMIN_CRON_AUTOMATION', 'الجدولة والأتمتة (Cron)');
tz_def('ADMIN_PLUS_SETTINGS', 'إعدادات بلس');
tz_def('ADMIN_LOG_SETTINGS', 'إعدادات السجلات');
tz_def('ADMIN_NEWSBOX_SETTINGS', 'إعدادات صندوق الأخبار');
tz_def('ADMIN_NEW_FUNCTIONS_SETTINGS', 'إعدادات الميزات الجديدة');
tz_def('ADMIN_EXTRA_SETTINGS', 'إعدادات إضافية');
tz_def('ADMIN_EDIT_ADMIN_INFORMATION', 'تعديل معلومات الأدمن');
tz_def('ADMIN_PLAYER_DETAILS', 'تفاصيل اللاعب');
tz_def('ADMIN_EDIT_PLAYER', 'تعديل اللاعب');
tz_def('ADMIN_DELETE_PLAYER', 'حذف اللاعب');
tz_def('ADMIN_COMPOSE_MESSAGE', 'إنشاء رسالة');
tz_def('ADMIN_EDIT_PLUS_RESOURCES', 'تعديل بلس والموارد');
tz_def('ADMIN_EDIT_SITTERS', 'تعديل الوكلاء');
tz_def('ADMIN_EDIT_PASSWORD', 'تعديل كلمة المرور');
tz_def('ADMIN_EDIT_PROTECTION', 'تعديل الحماية');
tz_def('ADMIN_EDIT_OFF_DEF', 'تعديل الهجوم والدفاع');
tz_def('ADMIN_EDIT_WEEKLY_OFF_DEF', 'تعديل الهجوم والدفاع الأسبوعي');
tz_def('ADMIN_USER_LOGINS', 'تسجيلات دخول المستخدم');
tz_def('ADMIN_USER_ILLEGALS_LOG', 'سجل مخالفات المستخدم');
tz_def('ADMIN_EDIT_HERO', 'تعديل البطل');
tz_def('ADMIN_T4_HERO_CONTROLS', 'أدوات تحكم بطل T4');
tz_def('ADMIN_EDIT_ADDITIONAL_INFO', 'تعديل معلومات إضافية');
tz_def('ADMIN_EDIT_VILLAGE', 'تعديل القرية');
tz_def('ADMIN_EDIT_RESOURCES', 'تعديل الموارد');
tz_def('ADMIN_EDIT_TROOPS', 'تعديل القوات');
tz_def('ADMIN_UPGRADE_TROOPS', 'ترقية القوات');
tz_def('ADMIN_ALLIANCE', 'التحالف');
tz_def('ADMIN_EDIT_ALLIANCE', 'تعديل التحالف');
tz_def('ADMIN_DELETE_ALLIANCE', 'حذف التحالف');
tz_def('ADMIN_BUILD_LOG', 'سجل البناء');
tz_def('ADMIN_RESEARCH_LOG', 'سجل الأبحاث');
tz_def('ADMIN_NO_PLAYER', 'لا يوجد لاعب');
tz_def('ADMIN_NO_VILLAGE', 'لا توجد قرية');

// Public interface and manual translations
tz_def('PUBLIC_WELCOME_TO', 'مرحبًا بك في');
tz_def('PUBLIC_MANUAL', 'الدليل');
tz_def('PUBLIC_REGISTER', 'تسجيل');
tz_def('PUBLIC_TRIBES', 'القبائل');
tz_def('PUBLIC_CLOSE', 'إغلاق');
tz_def('PUBLIC_BACK', 'رجوع');
tz_def('PUBLIC_FORWARD', 'التالي');
tz_def('PUBLIC_TO_REGISTRATION', 'إلى التسجيل');
tz_def('ANL_ROMANS', 'الرومان');
tz_def('ANL_GAULS', 'الغال');
tz_def('ANL_TEUTONS', 'التوتون');
tz_def('ANL_ROMAN_TROOPS', 'قوات الرومان');
tz_def('ANL_GALLIC_TROOPS', 'قوات الغال');
tz_def('ANL_TEUTONIC_TROOPS', 'قوات التوتون');
tz_def('ANL_SPECIALTIES', 'المميزات الخاصة');
tz_def('ANL_ROMAN_DUAL_BUILD', 'يمكنه إنشاء أو توسيع مبنى وحقل موارد في وقت واحد');
tz_def('ANL_ROMAN_WALL_BONUS', 'يمنح سور المدينة مكافأة دفاع عالية');
tz_def('ANL_GAUL_SPEED_BONUS', 'مكافأة السرعة: أسرع قوات في اللعبة');
tz_def('ANL_GAUL_PALISADE_BONUS', 'يمنح السياج الخشبي مكافأة دفاع متوسطة');
tz_def('ANL_TEUTON_EARTH_WALL', 'السور الترابي شبه غير قابل للتدمير لكنه يمنح مكافأة دفاع ضئيلة فقط');
tz_def('ANL_TEUTON_CHEAP_TROOPS', 'قوات رخيصة وسريعة وممتازة في النهب');
tz_def('ANL_TEUTON_WEAK_DEFENCE', 'دفاع ضعيف');
tz_def('ANL_ROMAN_DESC_1', 'بفضل مستواهم العالي من التطور الاجتماعي والتقني، يبرع الرومان في البناء وتنسيقه. كما أن قواتهم من نخبة قوات نوفاتيرا. متوازنون جدًا ومفيدون في الهجوم والدفاع.');
tz_def('ANL_ROMAN_DESC_2', 'لضمان هذا التنوع، تُدرَّب قوات الرومان لفترة طويلة جدًا وتدريبهم مكلف. مشاتهم أسطورة حية، لكن دفاعهم ضد فرسان الأعداء ليس بجودة القبائل الأخرى. كمية الموارد التي يستطيع التاجر الروماني حملها منخفضة جدًا.');
tz_def('ANL_ROMAN_DESC_3', 'بما أنهم ضعفاء في بداية اللعبة، فهم خيار سيء للمبتدئين.');
tz_def('ANL_GAUL_DESC_1', 'الغال هم أكثر القبائل الثلاث سلمية في نوفاتيرا. تُدرَّب قواتهم على دفاع ممتاز، لكن قدرتهم على الهجوم لا تزال تنافس القبيلتين الأخريين. الغال فرسان بالفطرة وخيولهم مشهورة بسرعتها، مما يعني أن فرسانهم يستطيعون ضرب العدو بدقة في أضعف نقاطه والتعامل معه بسرعة.');
tz_def('ANL_GAUL_DESC_2', 'من السهل جدًا الدفاع عن هذه القبيلة، لكن التكتيك الهجومي ممكن أيضًا. هذا يمنحك فرصة التطور في أي اتجاه استراتيجي ممكن (دفاعي أو هجومي، ذئب منفرد أو مساعد موثوق، تاجر أو ناهب، مشاة أو فرسان، مستوطن أو غازٍ)، لكنك تحتاج قدرًا من الخبرة والموهبة.');
tz_def('ANL_GAUL_DESC_3', 'للمبتدئين، ولمن لا يعرفون بالضبط ما يريدون لعبه، الغال هم الخيار المثالي.');
tz_def('ANL_TEUTON_DESC_1', 'التوتون هم القبيلة الأكثر عدوانية. قواتهم سيئة السمعة ومرهوبة الجانب لغضبها وجنونها أثناء الهجوم. يتحركون كحشد ناهب، لا يخشون حتى الموت.');
tz_def('ANL_TEUTON_DESC_2', 'لكنهم يفتقرون إلى الانضباط العسكري الذي يتمتع به الغال أو الرومان، مما يجعلهم ضعفاء في السرعة والدفاع.');
tz_def('ANL_TEUTON_DESC_3', 'للاعبين العدوانيين وذوي الخبرة، التوتون خيار ممتاز!');
tz_def('ANL_TRIBES_INTRO', 'يمكنك الاختيار بين ثلاث قبائل في نوفاتيرا: الرومان والغال والتوتون. لكل قبيلة مزايا وعيوب، وقواتها مختلفة جدًا أيضًا. من المهم اختيار القبيلة التي تناسبك تمامًا.');
tz_def('ANL_ROMAN_MERCHANT', 'يستطيع التجار حمل 500 وحدة من الموارد (السرعة: 16 حقلاً/ساعة)');
tz_def('ANL_GAUL_MERCHANT', 'يستطيع التجار حمل 750 وحدة من الموارد (السرعة: 24 حقلاً/ساعة)');
tz_def('ANL_TEUTON_MERCHANT', 'يستطيع التجار حمل 1000 وحدة من الموارد (السرعة: 12 حقلاً/ساعة)');
tz_def('ANL_TEUTON_PLUNDER_BONUS', 'مكافأة النهب: مخابئ الأعداء تخفي فقط 66% من الكمية المعتادة في نسخة نوفاتيرا 2.5 و3، و80% من الكمية المعتادة في نسخة نوفاتيرا 3.5.');
tz_def('ANL_ATTACK_VALUE', 'قيمة الهجوم');
tz_def('ANL_DEF_INFANTRY', 'قيمة الدفاع ضد المشاة');
tz_def('ANL_DEF_CAVALRY', 'قيمة الدفاع ضد الفرسان');
tz_def('ANL_SPEED', 'السرعة');
tz_def('ANL_BUILDINGS_INTRO_1', 'في البداية عليك محاولة بناء أساس اقتصادي جيد. لذلك يجب توسيع الـ18 حقل مورد (نظرة عامة على القرية). هناك أربعة أنواع من حقول الموارد: الحطاب، حفرة الطين، منجم الحديد، والأرض الزراعية. إذا نقرت على أحدها، ستحصل على معلومات أكثر وخيار توسيعه.');
tz_def('ANL_BUILDINGS_INTRO_2', 'لاحقًا في اللعبة تصبح مباني القرية مهمة أيضًا. لبناء مبنى جديد يجب النقر على أحد البيضاويات الخضراء. ستظهر قائمة بكل مبنى متاح.');
tz_def('ANL_BUILDINGS_INTRO_3', 'بعض المباني لا يمكن بناؤها إلا إذا استوفيت المتطلبات المسبقة وهي مبانٍ أخرى.');
tz_def('ANL_TRIBE_ADVANTAGE', 'ميزة القبيلة:');
tz_def('ANL_ROMAN_BUILD_ADVANTAGE', 'الرومان: يمكنهم بناء/توسيع حقل مورد ومبنى في القرية في نفس الوقت.');
tz_def('ANL_MAIN_BUILDING_DESC', 'يعيش كبار بنّائي القرية في المبنى الرئيسي. كلما زاد مستواه، أسرع كبار البنّائين في إتمام بناء المباني الجديدة.');
tz_def('ANL_WAREHOUSE_DESC', 'تُخزَّن موارد الخشب والطين والحديد في مخزنك. بزيادة مستواه تزيد سعة المخزن.');
tz_def('ANL_GRANARY_DESC', 'يُخزَّن المحصول المُنتَج من مزارعك في المخبأ. بزيادة مستواه تزيد سعة المخبأ.');
tz_def('ANL_CRANNY_DESC', 'يُستخدم المخبأ لإخفاء جزء من مواردك عند مهاجمة القرية. لا يمكن سرقة هذه الموارد. عند المستوى 1 يُخفى 100 وحدة من الموارد.');
tz_def('ANL_GAUL_CRANNY', 'الغال: المخبأ أكبر مرتين.');
tz_def('ANL_TEUTON_CRANNY', 'التوتون: مخابئ الأعداء تخفي فقط 66% من الكمية المعتادة في نسخة نوفاتيرا 2.5 و3، و80% من الكمية المعتادة في نسخة نوفاتيرا 3.5.');
tz_def('ANL_EMBASSY_DESC', 'للانضمام إلى تحالف تحتاج إلى سفارة. مع سفارة من المستوى 3 يمكنك حتى تأسيس تحالف بنفسك. مع كل مستوى أعلى يزداد الحد الأقصى لحجم التحالف بمقدار 3.');
tz_def('ANL_RALLY_POINT_DESC', 'في نقطة التجمع يمكنك توجيه قواتك ورؤية مرسل القوات القادمة. لا يمكن بناء نقطة التجمع إلا في موقع البناء يمين مركز القرية. كلما زاد مستواها، زادت الأهداف المتاحة للمنجنيقات.');
tz_def('ANL_MARKETPLACE_DESC', 'في السوق يمكنك تبادل الموارد مع لاعبين آخرين. كلما زاد مستواه، زادت كمية الموارد التي يمكن نقلها في نفس الوقت.');
tz_def('ANL_BARRACKS_DESC', 'يمكن تدريب المشاة في الثكنات. كلما زاد مستواها، أسرع تدريب القوات.');
tz_def('ANL_STABLE_DESC', 'تُدرَّب الفرسان في الإسطبل. كلما زاد مستواه، أسرع تدريب القوات.');
tz_def('ANL_WORKSHOP_DESC', 'يمكن بناء آلات الحصار مثل المنجنيقات والكباش في الورشة. كلما زاد مستواها، أسرع إنتاج الوحدات.');
tz_def('ANL_ACADEMY_DESC', 'يمكن تطوير أنواع وحدات جديدة في الأكاديمية. بزيادة مستواها يمكنك طلب أبحاث وحدات أفضل.');
tz_def('ANL_BLACKSMITH_DESC', 'تُحسَّن أسلحة محاربيك في أفران صهر الحداد. بزيادة مستواه يمكنك طلب صناعة أسلحة أفضل.');
tz_def('ANL_ARMOURY_DESC', 'يُحسَّن درع محاربيك في أفران صهر مصنع الدروع. بزيادة مستواه يمكنك طلب صناعة دروع أفضل.');
tz_def('ANL_PALACE_DESC', 'يعيش ملك أو ملكة الإمبراطورية في القصر. لا يمكن بناء القصر إلا في قرية واحدة في كل مرة، ويمكن استخدامه لجعل قرية عاصمةً. لا يمكن غزو العواصم.');
tz_def('ANL_CAPITAL_DESC', 'العاصمة هي المكان الوحيد الذي يمكن فيه إنشاء محجر النحّات. كما أن العاصمة هي القرية الوحيدة التي يمكن فيها توسيع حقول الموارد إلى ما بعد المستوى 10.');
tz_def('ANL_RESIDENCE_DESC', 'المقر قصر صغير يعيش فيه الملك أو الملكة عند زيارة القرية. يحمي المقر القرية من الأعداء الراغبين في غزوها طالما لم يُدمَّر.');
tz_def('ANL_TRADE_OFFICE_DESC', 'في مكتب التجارة تُحسَّن عربات التجار وتُجهَّز بخيول قوية. كلما زاد مستواه، زادت قدرة تجارك على الحمل.');
tz_def('ANL_TRADE_OFFICE_CAPACITY', 'مع كل مستوى تزيد السعة بنسبة 10%. عند المستوى 20 يستطيع تجارك حمل ثلاثة أضعاف الكمية العادية.');
tz_def('ANL_ROMAN_TRADE_OFFICE', 'الرومان: في نسخة نوفاتيرا 3.5 يزيد مكتب التجارة الروماني السعة بنسبة 20% لكل مستوى.');
tz_def('ANL_TOURNAMENT_SQUARE_DESC', 'يمكن لقواتك التدرب لزيادة تحملها في ساحة البطولة. كلما زاد ترقية المبنى، أسرعت قواتك بعد مسافة دنيا قدرها 30 مربعًا.');
tz_def('ANL_FAQ_RALLY_POINT_LOCATION', 'لا يمكن بناء نقطة التجمع إلا في موقع واحد، وهو يمين مركز قريتك في المرج.');
tz_def('ANL_FAQ_ALLIANCE', 'لتأسيس تحالف تحتاج سفارة من المستوى 3. للانضمام إلى تحالف تحتاج فقط سفارة من المستوى 1، وبالطبع دعوة للانضمام إلى التحالف.');
tz_def('ANL_FAQ_RENAME_VILLAGE_Q', 'كيف يمكنني تغيير اسم قريتي؟');
tz_def('ANL_FAQ_RENAME_VILLAGE_A', 'أولاً اختر القرية التي تريدها بالنقر عليها تحت <i><?php echo VILLAGES; ?></i>. ثم انقر <i><?php echo PROFILE; ?></i> على اليسار ثم <i><?php echo CHANGE_PROFILE; ?></i>. غيّر الاسم في خانة <i><?php echo VILLAGE_NAME; ?></i> وانقر موافق.');
tz_def('ANL_FAQ_TRAIN_TROOPS_Q', 'كيف أدرّب القوات؟');
tz_def('ANL_FAQ_TRAIN_TROOPS_A', 'تحتاج نقطة تجمع من المستوى 1 على الأقل ومبنى رئيسي من المستوى 3 على الأقل. عند توفرهما يمكنك بناء الثكنات وهناك يمكنك تدريب المشاة.');
tz_def('ANL_FAQ_DEFEND_VILLAGE_Q', 'كيف أدافع عن قريتي؟');
tz_def('ANL_FAQ_DEFEND_VILLAGE_A', 'طالما توجد قوات (خاصة بك أو مُرسَلة لتعزيزك) في قريتك، فإنها تدافع عن قريتك تلقائيًا.');
tz_def('ANL_FAQ_LOW_PLUNDER_Q', 'لماذا تنهب قواتي موارد قليلة جدًا؟');
tz_def('ANL_FAQ_LOW_PLUNDER_A', 'هناك تفسيران. أولاً، كل نوع قوات يستطيع حمل عدد معين فقط من الموارد. ثانيًا، قد يمتلك ضحيتك <i><?php echo CRANNY; ?></i> حيث يُخفي سكانه تلقائيًا عددًا معينًا من الموارد لا تستطيع حشودك الناهبة إيجادها.');
tz_def('ANL_FAQ_LOSE_VILLAGE_Q', 'هل يمكن أن أخسر قرية؟');
tz_def('ANL_FAQ_LOSE_VILLAGE_A', 'يمكن قصف أي قرية حتى تصل إلى صفر سكان، وطالما لم تكن آخر قرية في حسابك ستختفي بعد وقت قصير. القرية الوحيدة التي لا يمكن غزوها هي العاصمة. لاحظ أن تدمير العاصمة لا يزال ممكنًا.');
tz_def('ANL_FAQ_BUILD_RALLY_POINT_Q', 'كيف يمكنني بناء نقطة تجمع؟');
tz_def('ANL_FAQ_FOUND_ALLIANCE_Q', 'كيف يمكنني تأسيس تحالف؟');
tz_def('ANL_FAQ_FOUND_CONQUER_Q', 'كيف يمكنني تأسيس أو غزو قرى جديدة؟');
tz_def('PUBLIC_ABOUT_PROJECT', 'عن هذا المشروع:');
tz_def('PUBLIC_ADD_TRIBES', '- إضافة قبائل وآليات جديدة');
tz_def('PUBLIC_UNOFFICIAL_PROJECT', 'هذا مشروع غير رسمي من صنع المعجبين وغير تابع لشركة Novaterra GmbH.');
tz_def('PUBLIC_DELETE_COOKIES_NOTICE', 'إذا كان أشخاص آخرون يستخدمون هذا الجهاز أيضًا، يُنصح بحذف ملفات تعريف الارتباط (الكوكيز) الخاصة بك لسلامتك:');
tz_def('PUBLIC_DELETE_COOKIES', 'حذف ملفات تعريف الارتباط');
tz_def('ANL_ROMAN_MERCHANT_500', 'الرومان: يستطيع كل تاجر حمل 500 مورد.');
tz_def('ANL_TEUTON_MERCHANT_1000', 'التوتون: يستطيع كل تاجر حمل 1000 مورد.');
tz_def('ANL_GAUL_MERCHANT_750', 'الغال: يستطيع كل تاجر حمل 750 مورد.');
tz_def('ANL_ROMAN_ALT', 'روماني');
tz_def('ANL_GAUL_ALT', 'الغال');
tz_def('ANL_TEUTON_ALT', 'توتوني');
tz_def('ANL_CRANNY_DOUBLE', 'المخبأ أكبر مرتين');
tz_def('ANL_POPULATION_EXPLAIN', 'كل مبنى يزيد عدد سكان قريتك بعدد معين من السكان الجدد. عدد السكان الجدد الناتج عن رفع/توسيع مبنى يظهر بجانب الرمز.');
tz_def('ANL_CROP_EXAMPLE_1', '(مثلاً 10) يظهر إنتاج المحصول لديك مطروحًا منه استهلاكك للمحصول بسبب (مثلاً 5). يظهر إجمالي إنتاج المحصول لديك في الزاوية العلوية اليمنى. مثلاً 5/15');
tz_def('ANL_CROP_EXAMPLE_2', 'في المثال، 8 يكون استهلاكك من المحصول، 15 إجمالي إنتاجك من المحصول، 15 - 8 = 7 وحدات محصول في الساعة.');
tz_def('PUBLIC_LOGOUT_SUCCESS_TITLE', 'تم تسجيل الخروج بنجاح.');
tz_def('PUBLIC_LOGOUT_THANKS', 'شكرًا لزيارتك.');
tz_def('ANL_POPULATION_EXPLAIN_HTML', 'كل مبنى يزيد عدد سكان قريتك بعدد معين من السكان الجدد. عدد السكان الجدد الناتج عن رفع/توسيع مبنى يظهر بجانب الرمز.');
tz_def('ANL_CROP_EXPLAIN_HTML', 'عند الإنتاج، يظهر إنتاج المحصول لديك مطروحًا منه استهلاكك للمحصول بسبب السكان والقوات. يظهر إجمالي إنتاج المحصول لديك في الزاوية العلوية اليمنى.');
tz_def('ANL_CROP_EXAMPLE_HTML', 'في المثال، 8 يكون استهلاكك من المحصول، 15 إجمالي إنتاجك من المحصول، 15 - 8 = 7 وحدات محصول في الساعة.');
tz_def('ANL_USE_OF_CROP', 'استهلاك المحصول');
tz_def('PUBLIC_TERMS_TITLE', 'الشروط والأحكام');
tz_def('PUBLIC_TERMS_GENERAL_RULES', '1. القواعد العامة');
tz_def('PUBLIC_TERMS_PROJECT', 'هذا مشروع مفتوح المصدر من صنع المعجبين باسم نوفاتيرا من إعداد Shadow.');
tz_def('PUBLIC_TERMS_ACCEPTANCE', 'باستخدامك لخادم اللعبة هذا فإنك توافق على اتباع جميع القواعد المذكورة هنا.');
tz_def('PUBLIC_TERMS_ACCOUNT_RESPONSIBILITY', '2. مسؤولية الحساب');
tz_def('PUBLIC_TERMS_ACCOUNT_FULL_RESPONSIBILITY', 'أنت مسؤول مسؤولية كاملة عن حسابك.');
tz_def('PUBLIC_TERMS_ACCOUNT_SHARING', 'مشاركة الحسابات مسموحة فقط إذا سمحت بها قواعد اللعبة.');
tz_def('PUBLIC_TERMS_CHEATING', 'الغش، استخدام البوتات، إساءة استخدام الحسابات المتعددة، أو استغلال آليات اللعبة');
tz_def('PUBLIC_TERMS_SERVER_STATUS', '4. حالة الخادم');
tz_def('PUBLIC_TERMS_AS_IS', 'يُقدَّم هذا الخادم "كما هو" دون ضمانات لاستمرارية التشغيل أو الاستقرار.');
tz_def('PUBLIC_TERMS_RESET', 'يحتفظ المطوّر بالحق في إعادة تعيين أو تعديل الخادم في أي وقت.');
tz_def('PUBLIC_TERMS_NO_AFFILIATION', 'هذا المشروع غير تابع لنوفاتيرا.');
tz_def('PUBLIC_TERMS_UPDATES', 'قد تُحدَّث هذه الشروط في أي وقت دون إشعار مسبق.');
tz_def('PUBLIC_RULES_TITLE', 'قواعد اللعبة');
tz_def('PUBLIC_RULES_INTRO_1', 'مجموعة القواعد التالية تُضاف إلى الشروط والأحكام العامة لنوفاتيرا. يجب أن تطّلع على الشروط والأحكام العامة للتحقق مما هو مسموح به، خاصة في حال حظر حساب لمخالفة قاعدة ما.');
tz_def('PUBLIC_RULES_INTRO_2', 'يُمنع التحريض أو التلاعب أو التشجيع أو المساعدة أو التآمر مع آخرين لمخالفة أي من قواعد نوفاتيرا. تنطبق هذه القواعد على اللاعبين الذين سيحذفون حساباتهم أو يقومون حاليًا بحذفها دون استثناء.');
tz_def('PUBLIC_RULES_SECTION_1', '&sect;1 كلمة المرور، ملكية التسجيل');
tz_def('PUBLIC_RULES_ONE_ACCOUNT', 'لا يجوز لكل لاعب امتلاك ولعب أكثر من حساب واحد لكل خادم.');
tz_def('PUBLIC_RULES_EMAIL_OWNER', 'يجب أن يكون البريد الإلكتروني المستخدم لتسجيل الحساب تحت السيطرة الشخصية والحصرية للشخص الذي سجّل الحساب. يُعتبر الشخص المالك للبريد الإلكتروني المستخدم لتسجيل الحساب هو مالك الحساب، بصرف النظر عن أي اتفاقات شخصية أو تحالفية أخرى. مالك الحساب مسؤول مسؤولية كاملة عن جميع الإجراءات المتخذة بالحساب.');
tz_def('PUBLIC_RULES_SECTION_1_2', '&sect;1.2 كلمة المرور');
tz_def('PUBLIC_RULES_PASSWORD_SAME_WORLD', 'لا يجوز لمالك الحساب نقل كلمة مرور الحساب لأي شخص يلعب على نفس عالم اللعبة (الخادم). كما أن اختيار نفس كلمة المرور عمدًا على نفس عالم اللعبة كشخص آخر يُعد مخالفًا للقواعد؛ ويُعتبر أي من هذه الأفعال حسابات متعددة كما هو مُعرَّف في هذه القواعد.');
tz_def('PUBLIC_RULES_PASSWORD_OTHER_WORLD', 'مع ذلك، يُسمح بنقل كلمة مرور الحساب لشخص أو أشخاص يلعبون على عالم لعبة مختلف (أو لا يلعبون إطلاقًا) من أجل لعب حساب واحد معًا.');
tz_def('PUBLIC_RULES_PASSWORD_DAMAGE', 'لا يوجد تعويض عن الأضرار الناتجة عن معرفة شخص ما بكلمة مرور حساب. يخضع الشخص الذي يستلم كلمة المرور لقواعد نوفاتيرا وكذلك الشروط والأحكام العامة.');
tz_def('PUBLIC_RULES_SECTION_1_3', '&sect;1.3 تغيير البريد الإلكتروني / نقل الحسابات');
tz_def('PUBLIC_RULES_EMAIL_CHANGE', 'لتغيير عنوان البريد الإلكتروني لحسابك أو نقل حسابك لشخص آخر لا يلعب على نفس الخادم، اذهب إلى الملف الشخصي لحسابك (/spieler.php?s=3) واملأ معلومات البريد الإلكتروني الجديدة.');
tz_def('PUBLIC_RULES_SAME_WORLD_TRANSFER', 'لتبديل حساب مع شخص على نفس عالم اللعبة، يجب على كلا الشخصين إرسال رسالة بريد إلكتروني إلى admin@novaterra.com من عنوان البريد الإلكتروني المسجل حاليًا للحساب. يجب أن تحتوي الرسالة على المعلومات التالية:');
tz_def('PUBLIC_RULES_WORLD_NAME', 'عالم اللعبة الذي يقيم فيه الحساب');
tz_def('PUBLIC_RULES_ACCOUNT_NICKNAME', 'اسم الحساب المستعار');
tz_def('PUBLIC_RULES_NEW_OWNER_EMAIL', 'عنوان البريد الإلكتروني للمالك الجديد.');
tz_def('PUBLIC_RULES_PASSWORD_REQUEST_AFTER_TRANSFER', 'بعد ذلك يجب على كلا الشخصين طلب كلمة المرور لحسابهما الجديد عبر وظيفة استرجاع كلمة المرور.');
tz_def('PUBLIC_RULES_SITTERS', 'يحق لكل لاعب تعيين وصيّين قد يلعبان الحساب أثناء غياب المالك. يجب على الأوصياء لعب الحساب الذي يشرفون عليه بما يحقق أقصى فائدة له. إساءة استخدام هذه الوظيفة يعاقب عليها.');
tz_def('PUBLIC_RULES_SITTER_LOGIN', 'يجب على وصي الحساب الإشراف على الحساب باستخدام وظيفة الإشراف داخل اللعبة. لا يجوز لوصي الحساب رعاية الحساب بتسجيل الدخول بكلمة مرور الحساب الذي يشرف عليه (انظر &sect;1.2).');
tz_def('PUBLIC_RULES_SITTER_DAMAGE', 'لا يوجد تعويض عن الأضرار الناتجة عن وصي. مالكو الحساب مسؤولون مسؤولية كاملة عن أفعال أي أوصياء لحسابهم. في حال عدم اتباع أوصياء الحساب لهذه القواعد والشروط والأحكام العامة لنوفاتيرا، قد يُعاقَب كل من مالك الحساب والوصي.');
tz_def('PUBLIC_RULES_SHARED_COMPUTER', 'يجب على اللاعبين الذين يستخدمون نفس الجهاز ويريدون الوصول لحساب بعضهم البعض استخدام وظيفة الإشراف.');
tz_def('PUBLIC_RULES_BROWSER', 'يجب لعب اللعبة بمتصفح إنترنت غير مُعدَّل. استخدام السكربتات أو البوتات التي تُؤتمِت اللعب مخالف للقواعد.');
tz_def('PUBLIC_RULES_BUGS', 'لا يجوز استغلال أخطاء البرنامج (المعروفة أيضًا بالثغرات) لتحقيق فائدة. الإساءة قد تؤدي إلى معاقبة الحساب.');
tz_def('PUBLIC_RULES_REAL_MONEY', 'يُمنع أي بيع أو شراء بأموال حقيقية يتعلق بالحسابات أو الوحدات أو القرى أو الموارد أو الخدمات أو أي جانب آخر من نوفاتيرا. بيع حسابات نوفاتيرا وكذلك أي نقل غير مباشر (حتى كهدايا) بالاقتران مع مواقع المزادات أو معاملات مالية أخرى مخالف للقواعد.');
tz_def('PUBLIC_RULES_POLITE_COMMUNICATION', 'يجب على الجميع التواصل بنبرة مهذبة وودية. يمكن لصائدي المخالفين تغيير الملفات الشخصية وأسماء القرى غير اللائقة دون إنذار.');
tz_def('PUBLIC_RULES_BEHAVIOUR_INTRO', 'السلوك التالي يعاقب عليه وينطبق على جميع الأوصاف واسم الحساب وأسماء التحالفات وأسماء القرى والرسائل:');
tz_def('PUBLIC_RULES_LANGUAGE', 'اللغة الإنجليزية هي اللغة الرسمية الوحيدة المسموح بها.');
tz_def('PUBLIC_RULES_BLACKMAIL', 'ابتزاز اللاعبين بطريقة تخالف أيًا من قواعد نوفاتيرا أو الشروط والأحكام العامة.');
tz_def('PUBLIC_RULES_ADVERTISING', 'لا يُسمح بأي نوع من الإعلانات لم يُصرَّح به فريق نوفاتيرا.');
tz_def('ANL_FOUND_VILLAGE_Q', 'كيف يمكنني تأسيس قرية جديدة؟');
tz_def('ANL_FOUND_VILLAGE_A', 'لتأسيس قرية جديدة تحتاج إلى 3 مستوطنين، ومكان فارغ في الخريطة، ونقاط ثقافة كافية. لا تحتاج إلى البحث عن المستوطنين حيث يمكنك بناؤهم مباشرة بقصر من المستوى 10 أو 15 أو 20 أو مقر من المستوى 10 أو 20. لبناء القصر تحتاج مبنى رئيسي من المستوى 5 وسفارة. لبناء المقر تحتاج فقط مبنى رئيسي من المستوى 5. بعد تدريب 3 مستوطنين يمكنك اختيار مكان فارغ في الخريطة والنقر "تأسيس قرية جديدة" (ملاحظة: تحتاج 750 وحدة من كل مورد!) ثم سيغادر مستوطنوك قريتهم الأصلية ويؤسسون القرية الجديدة لك.');
tz_def('ANL_CONQUER_VILLAGE_Q', 'كيف يمكنني غزو قرية؟');
tz_def('ANL_CONQUER_VILLAGE_A1', 'لهذا أيضًا، تحتاج على الأقل مقر/قصر من المستوى 10 بالإضافة إلى قرية عدو يمكن غزوها. كما تحتاج نقاط ثقافة كافية - مثل تأسيس قرية جديدة. لا يمكن غزو آخر قرية للاعب ولا عاصمته.');
tz_def('ANL_CONQUER_VILLAGE_A2', 'بمجرد البحث عن حاكم البلدة (شيخ مجلس الشيوخ، الزعيم، زعيم القبيلة) في الأكاديمية، يمكن تدريبه في المقر/القصر مثل المستوطن. لكن لا يجوز أن تكون قد دربت مستوطنين بالفعل حتى تستطيع تدريب حاكم. لغزو قرية يحتاج الحاكم مهاجمة القرية المطلوبة عدة مرات بعد تدمير مقر/قصر العدو. بمجرد وصول ولاء القرية إلى صفر ستصبح ملكًا لك. عندها يجب بناء قصر أو مقر لرفع الولاء مجددًا.');
tz_def('ANL_CULTURE_POINTS_A', 'تحصل على نقاط الثقافة (CPs) ببناء وتوسيع المباني. يمكن رؤية نقاط الثقافة المُنتَجة بالفعل في المقر والقصر. كما تُعرض هناك معلومات عن نقاط الثقافة المطلوبة للقرى الإضافية.');
tz_def('PUBLIC_RULES_PROGRAM_ERRORS_HEADING', '&sect;4 أخطاء البرنامج');
tz_def('PUBLIC_RULES_PUNISHMENT', 'في حال حدوث مخالفة لقواعد اللعبة هذه، سيقوم صائدو المخالفين، وعند الحاجة المسؤولون، بحظر الحساب (الحسابات) المعنية وتحديد العقوبة المناسبة. ستتجاوز العقوبات دائمًا المكسب الناتج عن مخالفة القواعد.');
tz_def('PUBLIC_RULES_NO_REPLACEMENT', 'الموارد أو المباني أو القرى أو القوات المفقودة أثناء فترة الإيقاف لا تُحتسب كعقوبة ولن يعوضها فريق نوفاتيرا. لا يحق لأي لاعب المطالبة بدفع أو تعويض عن وقت بلس/ذهب مفقود بسبب الإيقاف.');
tz_def('PUBLIC_RULES_NO_SPECIAL_TREATMENT', 'لا توجد معاملة خاصة لمستخدمي نوفاتيرا بلس/ذهب فيما يخص قواعد اللعبة، لا في الوقت اللازم للتعامل مع الحالة ولا في العقوبة.');
tz_def('PUBLIC_RULES_APPEALS', 'يمكن للاعبين التحدث مع صائد المخالفين الذي حظرهم أو مع مسؤول عبر رسالة داخل اللعبة أو البريد الإلكتروني. لا يجوز مناقشة الحظر أو العقوبات أو الحذف علنًا (مثل الدردشة أو المنتديات). يجب كتابة الاستئنافات باللغة الإنجليزية.');
tz_def('PUBLIC_RULES_OWNER_INFORMATION', 'كما لن يقدم فريق نوفاتيرا معلومات لأشخاص غير مالك الحساب.');
tz_def('PUBLIC_RULES_MULTI_DELETE', 'يجوز حذف الحسابات المتعددة على خادم السرعة والحسابات المتعددة التي يقل عدد سكانها عن 100 فور اكتشافها دون إنذار.');
tz_def('PUBLIC_RULES_SECTION_8', '&sect;8 تغيير القواعد');
tz_def('PUBLIC_RULES_CHANGE_ANY_TIME', 'يحتفظ فريق نوفاتيرا بالحق في تغيير القواعد في أي وقت.');
tz_def('PUBLIC_RULES_SEVERABILITY', 'إذا كانت أي بنود فردية من مجموعة القواعد هذه غير فعّالة، فإن ذلك لا يؤثر على صحة بقية بنود مجموعة القواعد. يلتزم المسؤولون باستبدال البنود غير الفعّالة ببنود جديدة تحل محلها في أسرع وقت ممكن.');
tz_def('ANL_BUILDINGS', 'المباني');
tz_def('ANL_WAREHOUSE', 'المخزن');
tz_def('ANL_GRANARY', 'المخبأ');
tz_def('ANL_MAIN_BUILDING', 'المبنى الرئيسي');
tz_def('ANL_MARKETPLACE', 'السوق');
tz_def('ANL_BARRACKS', 'الثكنات');
tz_def('ANL_STABLE', 'الإسطبل');
tz_def('ANL_ACADEMY', 'الأكاديمية');
tz_def('ANL_CRANNY', 'المخبأ السري');
tz_def('ANL_RESIDENCE', 'المقر');
tz_def('ANL_PALACE', 'القصر');
tz_def('ANL_GREAT_BARRACKS', 'الثكنات الكبرى');
tz_def('ANL_GREAT_STABLE', 'الإسطبل الكبير');
tz_def('ANL_CULTURE_POINTS_MORE_VILLAGES', 'كلما زاد عدد قرى اللاعب، زادت نقاط الثقافة المطلوبة لتأسيس أو غزو قرى إضافية. لا يمكن خسارة نقاط الثقافة، لكن ستحتاج المزيد منها للقرى الإضافية.');
tz_def('ANL_REQ_MAIN_BUILDING_1', 'المبنى الرئيسي المستوى 1');
tz_def('ANL_REQ_MB3_WH1_GR1', 'المبنى الرئيسي المستوى 3، المخزن المستوى 1، المخبأ المستوى 1');
tz_def('ANL_REQ_RP1_MB3', 'نقطة التجمع المستوى 1، المبنى الرئيسي المستوى 3');
tz_def('ANL_REQ_BLACKSMITH3_ACADEMY5', 'الحداد المستوى 3، الأكاديمية المستوى 5');
tz_def('ANL_REQ_ACADEMY10_MB5', 'الأكاديمية المستوى 10، المبنى الرئيسي المستوى 5');
tz_def('ANL_REQ_BARRACKS3_MB3', 'الثكنات المستوى 3، المبنى الرئيسي المستوى 3');
tz_def('ANL_REQ_MB3_ACADEMY3', 'المبنى الرئيسي المستوى 3، الأكاديمية المستوى 3');
tz_def('ANL_REQ_MB3_ACADEMY1', 'المبنى الرئيسي المستوى 3، الأكاديمية المستوى 1');
tz_def('ANL_REQ_EMBASSY1_MB5', 'السفارة المستوى 1، المبنى الرئيسي المستوى 5،');
tz_def('ANL_REQ_MB5', 'المبنى الرئيسي المستوى 5،');
tz_def('ANL_REQ_MARKET20_STABLE10', 'السوق المستوى 20، الإسطبل المستوى 10');
tz_def('ANL_SPEED_LABEL', 'السرعة');
tz_def('ANL_FAQ_FOUND_CONQUER_FULL', 'تحتاج ثلاثة مستوطنين لتأسيس قرية جديدة. لغزو قرية تحتاج حاكمًا (شيخ مجلس الشيوخ، الزعيم، أو زعيم القبيلة) يمكن تدريبه في قصرك/مقرك عند المستوى 10. كما تحتاج عددًا معينًا من .');
tz_def('ANL_CONSTRUCTION_TIME_LEVEL1', 'وقت البناء للمستوى 1:');
tz_def('ANL_PALACE_SETTLERS_ADMIN', 'عند المستوى 10 و15 و20 يمكن تدريب ثلاثة مستوطنين أو حاكم واحد (شيخ مجلس الشيوخ، الزعيم، أو زعيم القبيلة).');
tz_def('ANL_RESIDENCE_SETTLERS_ADMIN', 'عند المستوى 10 و20 يمكن تدريب ثلاثة مستوطنين أو حاكم واحد (شيخ مجلس الشيوخ، الزعيم، أو زعيم القبيلة).');
tz_def('ANL_REQ_RALLY_POINT_15', 'نقطة التجمع المستوى 15');
tz_def('ANL_FAQ_FOUND_CONQUER_PREFIX', 'تحتاج ثلاثة مستوطنين لتأسيس قرية جديدة. لغزو قرية تحتاج حاكمًا (شيخ مجلس الشيوخ، الزعيم، أو زعيم القبيلة) يمكن تدريبه في قصرك/مقرك عند المستوى 10. كما تحتاج عددًا معينًا من ');
tz_def('ANL_HOW_GET_CULTURE_POINTS', 'كيف أحصل على نقاط الثقافة؟');
tz_def('ANL_LEVEL', 'المستوى');
tz_def('ANL_GREAT', 'الكبرى');
tz_def('ANL_CULTURE_POINTS_NEEDED', 'نقاط الثقافة المطلوبة للقرى الإضافية');
tz_def('PUBLIC_MAIN_GOALS', 'الأهداف الرئيسية:');
tz_def('PUBLIC_TERMS_SHORT', 'الشروط');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// PANOU DE ADMINISTRARE - sabloane (lot 1: home, server_info, online, users,
// search, ban, notregistered, inactive, report, msg)
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ADM_HELLO', 'مرحبًا');
tz_def('ADM_TOTAL_PLAYERS', 'إجمالي اللاعبين');
tz_def('ADM_ONLINE_NOW', 'متصل الآن');
tz_def('ADM_LAST_5_MIN', 'آخر 5 دقائق');
tz_def('ADM_VILLAGES', 'القرى');
tz_def('ADM_GOLD_IN_GAME', 'الذهب في اللعبة');
tz_def('ADM_ACTIVE_BANS', 'الحظر النشط');
tz_def('ADM_MANAGE', 'إدارة');
tz_def('ADM_LAST_REGISTRATION', 'آخر تسجيل');
tz_def('ADM_SERVER_CLOCK', 'ساعة السيرفر');
tz_def('ADM_SERVER_TIMELINE', 'الجدول الزمني للسيرفر');
tz_def('ADM_START_DATE', 'تاريخ البدء:');
tz_def('ADM_NATARS_C', 'التتار:');
tz_def('ADM_ARTEFACTS_C', 'القطع الأثرية:');
tz_def('ADM_WW_PLANS_C', 'مخططات عجيبة الدنيا:');
tz_def('ADM_SERVER_AGE', 'عمر السيرفر:');
tz_def('ADM_NEXT_EVENT', 'الحدث القادم:');
tz_def('ADM_LAST_5_ACTIONS', 'آخر 5 إجراءات إدارية');
tz_def('ADM_VIEW_FULL_LOG', 'عرض السجل الكامل ←');
tz_def('ADM_QUICK_ACTIONS', 'إجراءات سريعة');
tz_def('ADM_QA_SEARCH', '🔍 البحث عن لاعب');
tz_def('ADM_QA_BAN', '🔨 إدارة الحظر');
tz_def('ADM_QA_MAP', '🗺 الخريطة');
tz_def('ADM_QA_NATARS', '🏰 التتار');
tz_def('ADM_QA_ADD_USER', '👤 إضافة مستخدم');
tz_def('ADM_QA_SERVER_INFO', '⚙ معلومات السيرفر');
tz_def('ADM_WORLD_INFO', '🌍 معلومات العالم');
tz_def('ADM_REGISTERED_PLAYERS', 'اللاعبون المسجلون');
tz_def('ADM_ACTIVE_PLAYERS', 'اللاعبون النشطون');
tz_def('ADM_PLAYERS_ONLINE', 'اللاعبون المتصلون');
tz_def('ADM_PLAYERS_BANNED', 'اللاعبون المحظورون');
tz_def('ADM_VILLAGES_SETTLED', 'القرى المؤسسة');
tz_def('ADM_TOTAL_POPULATION', 'إجمالي السكان');
tz_def('ADM_PLAYER_DISTRIBUTION', '👥 توزيع اللاعبين');
tz_def('ADM_SERVER_ECONOMY', '💰 اقتصاد السيرفر');
tz_def('ADM_TOTAL_GOLD', 'إجمالي الذهب');
tz_def('ADM_AVG_GOLD', 'متوسط الذهب لكل لاعب');
tz_def('ADM_SERVER_STARTED', 'بدأ السيرفر');
tz_def('ADM_UPTIME', 'مدة التشغيل');
tz_def('ADM_TROOPS_ON_SERVER', '⚔️ القوات في السيرفر (القرى + التعزيزات)');
tz_def('ADM_ONLINE_USERS_H', '🟢 المستخدمون المتصلون');
tz_def('ADM_NO_ONLINE_USERS', 'لا يوجد مستخدمون متصلون في آخر 5 دقائق');
tz_def('ADM_USERS_H', '👥 المستخدمون');
tz_def('ADM_ALL', 'الكل');
tz_def('ADM_ADMINS', 'المدراء');
tz_def('ADM_NORMAL', 'عادي');
tz_def('ADM_BANNED', 'محظور');
tz_def('ADM_ONLINE', 'متصل');
tz_def('ADM_ID', 'المعرف');
tz_def('ADM_USERNAME', 'اسم المستخدم');
tz_def('ADM_EMAIL', 'البريد الإلكتروني');
tz_def('ADM_ACCESS', 'الصلاحية');
tz_def('ADM_TRIBE', 'القبيلة');
tz_def('ADM_GOLD', 'الذهب');
tz_def('ADM_LAST_ACTIVITY', 'آخر نشاط');
tz_def('ADM_PREV', '« السابق');
tz_def('ADM_NEXT', 'التالي »');
tz_def('ADM_SEARCH_PH', 'بحث...');
tz_def('ADM_ADMIN_SEARCH', 'بحث الإدارة');
tz_def('ADM_SEARCH_HINT', 'أدخل الاسم أو المعرف أو البريد أو الآي بي...');
tz_def('ADM_SEARCH_BTN', 'بحث');
tz_def('ADM_BAN_MANAGEMENT', 'إدارة الحظر');
tz_def('ADM_ACTIVE_USER_BANS', 'حظر المستخدمين النشط');
tz_def('ADM_ACTIVE_IP_BANS', 'حظر الآي بي النشط');
tz_def('ADM_HISTORY_50', 'السجل (آخر 50)');
tz_def('ADM_ADD_NEW_BAN', 'إضافة حظر جديد');
tz_def('ADM_FOREVER', 'دائم');
tz_def('ADM_BAN_USER', 'حظر المستخدم');
tz_def('ADM_BAN_IP_ADDRESS', 'حظر عنوان الآي بي');
tz_def('ADM_BAN_IP', 'حظر الآي بي');
tz_def('ADM_BAN_HISTORY', 'سجل الحظر');
tz_def('ADM_USER_ID', 'معرف المستخدم');
tz_def('ADM_IPV4_OR_IPV6', 'IPv4 أو IPv6');
tz_def('ADM_NOT_ACTIVATED_H', '✉️ اللاعبون غير المفعّلين');
tz_def('ADM_ACTIVATION_CODE', 'كود التفعيل');
tz_def('ADM_ACT2', 'Act2');
tz_def('ADM_TIME', 'الوقت');
tz_def('ADM_SEARCH_USER_EMAIL', 'ابحث باسم المستخدم أو البريد الإلكتروني...');
tz_def('ADM_INACTIVE_USERS', 'المستخدمون غير النشطين');
tz_def('ADM_DELETE_L', 'حذف');
tz_def('ADM_BACK_TO_REPORTS', '← العودة إلى التقارير');
tz_def('ADM_REPORT', 'التقرير');
tz_def('ADM_VIEW_ARROW', 'عرض ←');
tz_def('ADM_BACK_TO_MESSAGES', '← العودة إلى الرسائل');
tz_def('ADM_SENT_TO', 'أُرسلت إلى');
tz_def('ADM_MESSAGE', 'الرسالة');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// PANOU DE ADMINISTRARE - sabloane (lot 2: config, editServerSet, editNewFunctions,
// editCronSet, editPlusSet, editLogSet, editNewsboxSet, editExtraSet)
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ADM_CRON_AUTOMATION', 'الجدولة والأتمتة (Cron)');
tz_def('ADM_AUTOMATION_SOURCE', 'مصدر الأتمتة');
tz_def('ADM_WHERE_THE_GAME_TICK_BATTLES_MOVEMENTS_TRAINI', 'من أين تتم معالجة نبضة اللعبة (المعارك، التحركات، التدريب، البناء). عند تشغيل مهمة الـcron، لا تعود صفحات اللاعبين تحمل عبء المعالجة.');
tz_def('ADM_LAST_CRON_TICK', 'آخر نبضة cron');
tz_def('ADM_WRITTEN_BY_CRON_PHP_ON_EVERY_TICK_INTO_GAMEE', 'تُكتب بواسطة cron.php في كل نبضة داخل GameEngine/Prevention/cron_active.txt. أقل من 90 ثانية يعني أن الـcron يعمل.');
tz_def('ADM_INVOCATION_LENGTH', 'مدة الاستدعاء');
tz_def('ADM_CRON_LOOP_SECONDS_HOW_LONG_ONE_CRON_PHP_INVO', 'CRON_LOOP_SECONDS - مدة استمرار تشغيل استدعاء واحد لملف cron.php. المضيفات التي تسمح بتشغيل cron كل 5 دقائق فقط تحصل بهذه الطريقة على نبضة كل دقيقة. 0 = نبضة واحدة لكل استدعاء.');
tz_def('ADM_TICK_INTERVAL', 'فاصل النبضة');
tz_def('ADM_CRON_TICK_SECONDS_HOW_OFTEN_AUTOMATION_RUNS', 'CRON_TICK_SECONDS - عدد مرات تشغيل الأتمتة داخل استدعاء واحد. تتوقع الأتمتة نفسها أن تعمل كل 60 ثانية تقريبًا.');
tz_def('ADM_HTTP_TRIGGER_KEY', 'مفتاح تفعيل HTTP');
tz_def('ADM_CRON_KEY_ONLY_NEEDED_TO_CALL_CRON_PHP_OVER_H', 'CRON_KEY - مطلوب فقط لاستدعاء cron.php عبر HTTP (خدمة cron خارجية). تشغيله من مهمة cron الخاصة بالسيرفر نفسه لا يستخدم هذا المفتاح. يُنشأ تلقائيًا عند التثبيت.');
tz_def('ADM_NOT_SET', 'غير مضبوط');
tz_def('ADM_SHOW', 'إظهار');
tz_def('ADM_HTTP_TRIGGER_URL', 'رابط تفعيل HTTP');
tz_def('ADM_OPTIONAL_USE_THIS_WITH_AN_EXTERNAL_CRON_SERV', 'اختياري. استخدم هذا مع خدمة cron خارجية إذا كان المضيف لا يستطيع تشغيل مهمة cron محلية. استدعاء HTTP واحد = نبضة واحدة.');
tz_def('ADM_HIDDEN_USE_SHOW_ABOVE', 'مخفي &ndash; استخدم "إظهار" أعلاه');
tz_def('ADM_DATABASE_CLEANUP', 'تنظيف قاعدة البيانات');
tz_def('ADM_AUTOMATION_TRIMS_TABLES_THAT_WOULD_OTHERWISE', 'تقوم الأتمتة بتقليم الجداول التي كانت ستنمو بلا حدود. لا يتم حذف التقارير المؤرشفة أبدًا. القيمة 0 تعطّل القاعدة.');
tz_def('ADM_LAST_CLEANUP_RUN', 'آخر تشغيل للتنظيف');
tz_def('ADM_CLEANUP_RUNS_ONCE_PER_HOUR_FROM_AUTOMATION_A', 'يعمل التنظيف مرة واحدة كل ساعة من الأتمتة ويحذف على دفعات، لذا فإن أول تشغيل على سيرفر قديم يلحق بالمتراكم عبر عدة مرات.');
tz_def('ADM_CRON_JOB_COMMAND', 'أمر مهمة cron');
tz_def('ADM_ADD_THIS_IN_CPANEL_CRON_JOBS_THE_PATH_IS_DET', 'أضف هذا في cPanel &rarr; Cron Jobs. المسار مكتشف تلقائيًا من هذا التثبيت.');
tz_def('ADM_CURRENT_STATUS', 'الحالة الحالية');
tz_def('ADM_WHEN_THE_CRON_JOB_IS_NOT_RUNNING_THE_GAME_ST', 'عندما لا تعمل مهمة cron، تستمر اللعبة في العمل: تقوم صفحات اللاعبين بمعالجة النبضة، تمامًا كما كان الحال قبل إدخال الـcron.');
tz_def('ADM_ADD_THIS_IN_CPANEL_CRON_JOBS_WRITING_TO_CRON', 'أضف هذا في cPanel &rarr; Cron Jobs. الكتابة إلى cron.log بدلًا من /dev/null تتيح لك رؤية الأخطاء إذا فشل التشغيل.');
tz_def('ADM_SETTINGS', 'الإعدادات');
tz_def('ADM_INVOCATION_LENGTH_SECONDS', 'مدة الاستدعاء (ثوانٍ)');
tz_def('ADM_0_A_SINGLE_TICK_PER_INVOCATION_MAXIMUM_3300', '0 = نبضة واحدة لكل استدعاء. الحد الأقصى 3300 (55 دقيقة).');
tz_def('ADM_TICK_INTERVAL_SECONDS', 'فاصل النبضة (ثوانٍ)');
tz_def('ADM_HOW_OFTEN_AUTOMATION_RUNS_INSIDE_ONE_INVOCAT', 'عدد مرات تشغيل الأتمتة داخل استدعاء واحد. آلية الحماية الخاصة بالأتمتة تتوقع نحو 60 ثانية تقريبًا؛ تخفيض القيمة يزيد الحمل غالبًا دون معالجة أي جديد.');
tz_def('ADM_RECOMMENDED_60_ALLOWED_RANGE_15_900', 'الموصى به: 60. النطاق المسموح 15&ndash;900.');
tz_def('ADM_ONLY_NEEDED_WHEN_CALLING_CRON_PHP_OVER_HTTP', 'مطلوب فقط عند استدعاء cron.php عبر HTTP (مثلًا من خدمة cron خارجية). مهمة cron الخاصة بالسيرفر لا تستخدمه. إعادة التوليد تُبطل أي رابط قمت بضبطه في مكان آخر.');
tz_def('ADM_GENERATE_A_NEW_KEY_ON_SAVE', 'توليد مفتاح جديد عند الحفظ');
tz_def('ADM_RUNS_FROM_AUTOMATION_ONCE_PER_HOUR_ROWS_ARE', 'يعمل من الأتمتة مرة واحدة كل ساعة. تُحذف الصفوف على دفعات، لذا فإن أول تشغيل على سيرفر قديم يلحق بالمتراكم عبر عدة مرات.');
tz_def('ADM_BATTLE_REPORTS_DAYS', 'تقارير المعارك (أيام)');
tz_def('ADM_UNARCHIVED_REPORTS_OLDER_THAN_THIS_ARE_DELET', 'يتم حذف التقارير غير المؤرشفة الأقدم من هذا. لا يتم لمس التقارير التي أرشفها اللاعب أبدًا. القيمة 0 تعطّل القاعدة.');
tz_def('ADM_0_KEEP_FOREVER', '0 = الاحتفاظ للأبد.');
tz_def('ADM_CHAT_MESSAGES_DAYS', 'رسائل الدردشة (أيام)');
tz_def('ADM_THE_CHAT_WINDOW_ONLY_EVER_SHOWS_THE_LAST_13', 'نافذة الدردشة تعرض فقط آخر 13 رسالة لكل تحالف، لذا لا يُستخدم السجل الأقدم في أي مكان في اللعبة.');
tz_def('ADM_DELETED_MESSAGES_DAYS', 'الرسائل المحذوفة (أيام)');
tz_def('ADM_ONLY_MESSAGES_DELETED_BY_BOTH_THE_SENDER_AND', 'يتم إزالة الرسائل التي حذفها كل من المرسل والمستلم فقط &mdash; ولم تعد مرئية لأي أحد في اللعبة. معطّل افتراضيًا.');
tz_def('ADM_0_DISABLED_DEFAULT', '0 = معطّل (الافتراضي).');
tz_def('ADM_NEW_MECHANICS_AND_FUNCTIONS', 'آليات ووظائف جديدة');
tz_def('ADM_HERO_BASE_REGENERATION', 'التعافي الأساسي للبطل');
tz_def('ADM_HIT_POINTS_THE_HERO_RECOVERS_PER_DAY_INDEPEN', 'نقاط الحياة التي يستعيدها البطل يوميًا، بغض النظر عن خاصية التعافي والعناصر المجهزة. القيمة 0 تعطّلها.');
tz_def('ADM_HP_DAY', 'نقاط حياة / يوم');
tz_def('ADM_HERO_EXCHANGE_RATES', 'أسعار صرف البطل');
tz_def('ADM_RATES_OF_THE_EXCHANGE_OFFICE_IN_THE_AUCTION', 'أسعار مكتب الصرف في دار المزاد. الفرق بين الاتجاهين هو هامش الدار، لذا فإن التداول ذهابًا وإيابًا يخسر قيمة بدلًا من خلقها.');
tz_def('ADM_EXCHANGE_OFFICE_IN_THE_AUCTION_HOUSE_KEEP_TH', 'مكتب الصرف في دار المزاد. حافظ على أن تكون القيمة الثانية أعلى من الأولى، وإلا يمكن للاعبين التداول ذهابًا وإيابًا لخلق ذهب من العدم.');
tz_def('ADM_1_GOLD', '1 ذهب &rarr;');
tz_def('ADM_SILVER_1_GOLD', 'فضة &rarr; 1 ذهب');
tz_def('ADM_HERO_RESOURCE_PRODUCTION', 'إنتاج البطل من الموارد');
tz_def('ADM_OF_ONE_TYPE', 'من نوع واحد');
tz_def('ADM_DISPLAY_OASIS_IN_PROFILE', 'عرض الواحة في الملف الشخصي');
tz_def('ADM_ENABLE_DISABLE_THE_DISPLAY_OF_OASES_OF_EACH', 'تفعيل (تعطيل) عرض واحات كل قرية في الملف الشخصي للاعب');
tz_def('ADM_ALLIANCE_INVITATION_MESSAGE', 'رسالة دعوة التحالف');
tz_def('ADM_ENABLE_DISABLE_SENDING_AN_IN_GAME_MESSAGE_TO', 'تفعيل (تعطيل) إرسال رسالة داخل اللعبة للاعب عند دعوته للتحالف');
tz_def('ADM_NEW_ALLIANCE_EMBASSY_MECHANICS', 'آليات جديدة للتحالف والسفارة');
tz_def('ADM_NEW_FORUM_POST_MESSAGE', 'رسالة موضوع منتدى جديد');
tz_def('ADM_ENABLE_DISABLE_FORUM_SUBSCRIPTION_MESSAGES', 'تفعيل (تعطيل) رسائل اشتراك المنتدى');
tz_def('ADM_TRIBES_IMAGES_IN_PROFILE', 'صور القبائل في الملف الشخصي');
tz_def('ADM_ENABLE_DISABLE_DISPLAYING_IMAGES_OF_TRIBES', 'تفعيل (تعطيل) عرض صور القبائل');
tz_def('ADM_ENABLE_DISABLE_DISPLAYING_IMAGES_OF_TRIBES_W', 'تفعيل (تعطيل) عرض صور القبائل مع وصف في الملف الشخصي للاعب');
tz_def('ADM_NEW_TRIBE_HUNS', 'قبيلة جديدة: الهون');
tz_def('ADM_ENABLE_DISABLE_THE_HUNS_TRIBE', 'تفعيل (تعطيل) قبيلة الهون');
tz_def('ADM_ENABLE_DISABLE_TRIBE_HUNS', 'تفعيل (تعطيل) قبيلة الهون');
tz_def('ADM_NEW_TRIBE_EGYPTIANS', 'قبيلة جديدة: المصريون');
tz_def('ADM_ENABLE_DISABLE_THE_EGYPTIANS_TRIBE', 'تفعيل (تعطيل) قبيلة المصريين');
tz_def('ADM_ENABLE_DISABLE_TRIBE_EGYPTIANS', 'تفعيل (تعطيل) قبيلة المصريين');
tz_def('ADM_NEW_TRIBE_SPARTANS', 'قبيلة جديدة: الإسبرطيون');
tz_def('ADM_ENABLE_DISABLE_THE_SPARTANS_TRIBE', 'تفعيل (تعطيل) قبيلة الإسبرطيين');
tz_def('ADM_ENABLE_DISABLE_TRIBE_SPARTANS', 'تفعيل (تعطيل) قبيلة الإسبرطيين');
tz_def('ADM_NEW_TRIBE_VIKINGS', 'قبيلة جديدة: الفايكنج');
tz_def('ADM_ENABLE_DISABLE_THE_VIKINGS_TRIBE', 'تفعيل (تعطيل) قبيلة الفايكنج');
tz_def('ADM_ENABLE_DISABLE_TRIBE_VIKINGS', 'تفعيل (تعطيل) قبيلة الفايكنج');
tz_def('ADM_MHS_IMAGES_IN_PROFILE', 'صور المطاردين في الملف الشخصي');
tz_def('ADM_ENABLE_DISABLE_DISPLAYING_IMAGES_OF_MULTIHUN', 'تفعيل (تعطيل) عرض صور المطاردين');
tz_def('ADM_ENABLE_DISABLE_DISPLAYING_IMAGES_OF_MULTIHUN_2', 'تفعيل (تعطيل) عرض صور المطاردين مع وصف في ملفهم الشخصي');
tz_def('ADM_DISPLAY_ARTIFACT_IN_PROFILE', 'عرض القطعة الأثرية في الملف الشخصي');
tz_def('ADM_ENABLE_DISABLE_THE_DISPLAY_OF_THE_ARTIFACT', 'تفعيل (تعطيل) عرض القطعة الأثرية');
tz_def('ADM_ENABLE_DISABLE_THE_DISPLAY_OF_THE_ARTIFACT_I', 'تفعيل (تعطيل) عرض القطعة الأثرية في الملف الشخصي للاعب، مقابل القرية المطابقة التي توجد بها');
tz_def('ADM_DISPLAY_WOW_IN_PROFILE', 'عرض عجيبة الدنيا في الملف الشخصي');
tz_def('ADM_ENABLE_DISABLE_THE_DISPLAY_OF_THE_WONDER', 'تفعيل (تعطيل) عرض عجيبة الدنيا');
tz_def('ADM_ENABLE_DISABLE_THE_DISPLAY_OF_THE_WONDER_IN', 'تفعيل (تعطيل) عرض عجيبة الدنيا في الملف الشخصي للاعب، مقابل القرية المطابقة التي توجد بها');
tz_def('ADM_VACATION_MODE', 'وضع الإجازة');
tz_def('ADM_ENABLE_DISABLE_VACATION_MODE', 'تفعيل (تعطيل) وضع الإجازة');
tz_def('ADM_ENABLE_DISABLE_VACATION_MODE_WILL_BE_DISPLAY', 'تفعيل (تعطيل) وضع الإجازة، سيُعرض أو يُخفى في قائمة الملف الشخصي للاعب');
tz_def('ADM_CATAPULT_TARGETS', 'أهداف المنجنيق');
tz_def('ADM_ENABLE_DISABLE_THE_DISPLAY_OF_CATAPULT_TARGE', 'تفعيل (تعطيل) عرض أهداف المنجنيق');
tz_def('ADM_ENABLE_DISABLE_THE_DISPLAY_OF_THE_TARGETS_OF', 'تفعيل (تعطيل) عرض أهداف المنجنيقات في نقطة التجمع التي أرسلتها بنفسك');
tz_def('ADM_MANUAL_ON_NATURE_AND_NATARS', 'دليل عن الطبيعة والتتار');
tz_def('ADM_ENABLE_DISABLE_MANUAL_INFO', 'تفعيل (تعطيل) معلومات الدليل');
tz_def('ADM_ENABLE_DISABLE_DISPLAYING_INFORMATION_IN_THE', 'تفعيل (تعطيل) عرض معلومات في الدليل عن قوات الطبيعة والتتار');
tz_def('ADM_DIRECT_LINKS_PLACEMENT', 'موضع الروابط المباشرة');
tz_def('ADM_LEFT_MENU_VS_RIGHT_MENU', 'القائمة اليسرى مقابل القائمة اليمنى');
tz_def('ADM_IF_ENABLED_THEN_THE_DIRECT_LINKS_WILL_BE_PLA', 'إذا كان مفعّلًا، توضع الروابط المباشرة في القائمة اليسرى، وإذا كان معطّلًا توضع في القائمة اليمنى كما في نوفاتيرا الأصلية');
tz_def('ADM_MEDAL_VETERAN_PLAYER', 'وسام اللاعب المخضرم');
tz_def('ADM_3_YEARS', '3 سنوات');
tz_def('ADM_ENABLE_DISABLE_MEDAL_ACHIEVED_FOR_PLAYING_3', 'تفعيل (تعطيل) الوسام الذي يُمنح عند اللعب 3 سنوات في نوفاتيرا');
tz_def('ADM_MEDAL_VETERAN_PLAYER_5A', 'وسام اللاعب المخضرم 5أ');
tz_def('ADM_5_YEARS', '5 سنوات');
tz_def('ADM_ENABLE_DISABLE_MEDAL_ACHIEVED_FOR_PLAYING_5', 'تفعيل (تعطيل) الوسام الذي يُمنح عند اللعب 5 سنوات في نوفاتيرا');
tz_def('ADM_MEDAL_VETERAN_PLAYER_10A', 'وسام اللاعب المخضرم 10أ');
tz_def('ADM_10_YEARS', '10 سنوات');
tz_def('ADM_ENABLE_DISABLE_MEDAL_ACHIEVED_FOR_PLAYING_10', 'تفعيل (تعطيل) الوسام الذي يُمنح عند اللعب 10 سنوات في نوفاتيرا');
tz_def('ADM_SPECIAL_MEDALS', 'أوسمة خاصة');
tz_def('ADM_ENABLE_DISABLE_SPECIAL_MEDALS_ARTIFACT_HERO', 'تفعيل (تعطيل) الأوسمة الخاصة (القطعة الأثرية، البطل، عجيبة الدنيا، السور، المخزن الكبير، إلخ)');
tz_def('ADM_SERVER_MILESTONES', 'إنجازات السيرفر');
tz_def('ADM_NOT_PRESENT_IN_CONFIG_PHP_YET_SAVING_THIS_FO', 'غير موجود بعد في config.php &mdash; حفظ هذا النموذج مرة واحدة سيضيفه (تكون القيمة False افتراضيًا حتى ذلك الحين).');
tz_def('ADM_SERVER_MEDAL_RESET_TIMER', 'مؤقت إعادة ضبط أوسمة السيرفر');
tz_def('ADM_ENABLE_DISABLE_THE_SERVER_MEDAL_RESET_TIMER', 'تفعيل (تعطيل) عنصر "مؤقت إعادة ضبط أوسمة السيرفر" في قائمة أفضل 10 لاعبين وأفضل 10 تحالفات');
tz_def('ADM_T4_HERO_ITEMS_ADVENTURES_AUCTION', 'بطل T4 (العناصر، المغامرات، المزاد)');
tz_def('ADM_ENABLE_DISABLE_THE_T4_HERO_ITEMS_ADVENTURES', 'تفعيل (تعطيل) نظام "بطل T4 (العناصر، المغامرات، المزاد)"');
tz_def('ADM_REGISTRATION_BONUS_GOLD', 'ذهب مكافأة التسجيل');
tz_def('ADM_GIVE_EVERY_NEWLY_REGISTERED_PLAYER_A_ONE_TIM', 'منح كل لاعب مسجَّل حديثًا مكافأة ذهب لمرة واحدة عند إنشاء الحساب');
tz_def('ADM_REGISTRATION_BONUS_GOLD_AMOUNT', 'ذهب مكافأة التسجيل &ndash; الكمية');
tz_def('ADM_HOW_MUCH_GOLD_EACH_NEW_PLAYER_RECEIVES_WHEN', 'كمية الذهب التي يحصل عليها كل لاعب جديد عند تفعيل المكافأة');
tz_def('ADM_ENABLE_DISABLE_A_ONE_TIME_GOLD_BONUS_GRANTED', 'تفعيل (تعطيل) مكافأة ذهب لمرة واحدة تُمنح لكل لاعب مسجَّل حديثًا عند إنشاء الحساب (يتم تخطي الحسابات النظامية بمعرف &le;3). تُضبط الكمية في الحقل أدناه.');
tz_def('ADM_HOW_MUCH_GOLD_EACH_NEW_PLAYER_RECEIVES_WHEN_2', 'كمية الذهب التي يحصل عليها كل لاعب جديد عندما يكون المفتاح أعلاه True. الافتراضي: 200.');
tz_def('ADM_FOR_THIS_SETTING_YOU_CAN_FIND_MORE_INFORMATI', 'لهذا الإعداد، يمكنك إيجاد مزيد من المعلومات في الرابط:');
tz_def('ADM_EDIT_CRON_AUTOMATION', 'تعديل الجدولة والأتمتة (Cron)');
tz_def('ADM_EDIT_NEW_MECHANICS_AND_FUNCTIONS', 'تعديل الآليات والوظائف الجديدة');
tz_def('ADM_ENGLISH', 'الإنجليزية');
tz_def('ADM_FRENCH', 'الفرنسية');
tz_def('ADM_ITALIAN', 'الإيطالية');
tz_def('ADM_ROMANIAN', 'الرومانية');
tz_def('ADM_SPANISH', 'الإسبانية');
tz_def('ADM_CHINESE', 'الصينية');
tz_def('ADM_SLOW', 'بطيء');
tz_def('ADM_FAST', 'سريع');
tz_def('ADM_2_HOURS', 'ساعتان');
tz_def('ADM_3_HOURS', '3 ساعات');
tz_def('ADM_5_HOURS', '5 ساعات');
tz_def('ADM_8_HOURS', '8 ساعات');
tz_def('ADM_10_HOURS', '10 ساعات');
tz_def('ADM_12_HOURS', '12 ساعة');
tz_def('ADM_16_HOURS', '16 ساعة');
tz_def('ADM_20_HOURS', '20 ساعة');
tz_def('ADM_24_HOURS_1_DAY', '24 ساعة (يوم واحد)');
tz_def('ADM_48_HOURS_2_DAYS', '48 ساعة (يومان)');
tz_def('ADM_72_HOURS_3_DAYS', '72 ساعة (3 أيام)');
tz_def('ADM_120_HOURS_5_DAYS', '120 ساعة (5 أيام)');
tz_def('ADM_TRUE', 'صحيح');
tz_def('ADM_FALSE', 'خطأ');
tz_def('ADM_YES', 'نعم');
tz_def('ADM_NO', 'لا');
tz_def('ADM_NOVATERRA_OFFICIAL', 'نوفاتيرا الرسمية');
tz_def('ADM_NOVATERRA_EXTENDED', 'نوفاتيرا الموسّعة');
tz_def('ADM_10_DEFAULT', '10 - افتراضي');
tz_def('ADM_NONE', 'لا شيء');
tz_def('ADM_1_DAY', 'يوم واحد');
tz_def('ADM_2_DAYS', 'يومان');
tz_def('ADM_3_DAYS', '3 أيام');
tz_def('ADM_4_DAYS', '4 أيام');
tz_def('ADM_5_DAYS', '5 أيام');
tz_def('ADM_6_DAYS', '6 أيام');
tz_def('ADM_7_DAYS', '7 أيام');
tz_def('ADM_NONE_2', 'لا شيء');
tz_def('ADM_CHRISTMAS', 'عيد الميلاد');
tz_def('ADM_NEW_YEAR', 'رأس السنة');
tz_def('ADM_EASTER', 'عيد الفصح');
tz_def('ADM_ENABLE', 'تفعيل');
tz_def('ADM_DISABLE', 'تعطيل');
tz_def('ADM_SAVE', 'حفظ');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// PANOU DE ADMINISTRARE - sabloane (lot 3: player, editHero, editHeroT4,
// playerheroinfo, village, troops, editVillage, editUser, deletion, editSitter)
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ADM_START', 'البداية');
tz_def('ADM_END', 'النهاية');
tz_def('ADM_DURATION', 'المدة');
tz_def('ADM_REASON', 'السبب');
tz_def('ADM_PLAYER', 'اللاعب:');
tz_def('ADM_BASIC_INFO', '📝 معلومات أساسية');
tz_def('ADM_HERO_NAME', 'اسم البطل');
tz_def('ADM_HERO_UNIT', 'وحدة البطل');
tz_def('ADM_HEALTH', '❤ الصحة');
tz_def('ADM_EXPERIENCE', '✨ الخبرة');
tz_def('ADM_CURRENT_STATS', '📈 الإحصائيات الحالية');
tz_def('ADM_LEVEL', 'المستوى:');
tz_def('ADM_OF', 'من');
tz_def('ADM_FREE', '(متاح:');
tz_def('ADM_OFFENCE', 'الهجوم:');
tz_def('ADM_DEFENCE', 'الدفاع:');
tz_def('ADM_OFF_BONUS', 'مكافأة الهجوم:');
tz_def('ADM_DEF_BONUS', '| مكافأة الدفاع:');
tz_def('ADM_REGEN', 'التعافي:');
tz_def('ADM_RESOURCES', 'الموارد:');
tz_def('ADM_ADD_POINTS', '⚔ إضافة نقاط');
tz_def('ADM_ATTRIBUTE', 'الخاصية');
tz_def('ADM_CURRENT', 'الحالي');
tz_def('ADM_ADD', 'إضافة');
tz_def('ADM_NEW', 'الجديد');
tz_def('ADM_PRODUCED_RESOURCE', 'المورد المنتَج');
tz_def('ADM_LEVEL_NOU', 'المستوى الجديد:');
tz_def('ADM_BACK', '← رجوع');
tz_def('ADM_SAVE_HERO', '💾 حفظ البطل');
tz_def('ADM_CLASSIC_HERO_EDITOR', '&laquo; محرر البطل الكلاسيكي');
tz_def('ADM_ACTION_COMPLETED', 'اكتمل الإجراء.');
tz_def('ADM_ACTION_FAILED_CHECK_THE_VALUES_AND_TRY_AGAIN', 'فشل الإجراء (تحقق من القيم وأعد المحاولة).');
tz_def('ADM_SILVER', 'الفضة');
tz_def('ADM_SET_BALANCE', 'ضبط الرصيد');
tz_def('ADM_GRANT_ITEM', 'منح عنصر');
tz_def('ADM_QTY', 'الكمية:');
tz_def('ADM_GRANT', 'منح');
tz_def('ADM_ROW', 'الصف');
tz_def('ADM_ITEM', 'العنصر');
tz_def('ADM_SLOT', 'الخانة');
tz_def('ADM_QTY_2', 'الكمية');
tz_def('ADM_EQUIPPED', 'مجهّز');
tz_def('ADM_DELETE', 'حذف');
tz_def('ADM_AUCTION', 'المزاد');
tz_def('ADM_ROLE', 'الدور');
tz_def('ADM_PRICE', 'السعر');
tz_def('ADM_ENDS', 'ينتهي');
tz_def('ADM_CANCEL', 'إلغاء');
tz_def('ADM_ADVENTURES', 'المغامرات');
tz_def('ADM_RUNNING', 'جارية:');
tz_def('ADM_OFFER', 'العرض');
tz_def('ADM_DIFFICULTY', 'الصعوبة');
tz_def('ADM_EXPIRES', 'تنتهي');
tz_def('ADM_REMOVE', 'إزالة');
tz_def('ADM_PLAYER_HEROES', 'أبطال اللاعب');
tz_def('ADM_NONE_3', 'لا شيء &nbsp;&nbsp;');
tz_def('ADM_ADD_HERO', 'إضافة بطل');
tz_def('ADM_HERO_LEVEL', 'مستوى البطل');
tz_def('ADM_STATUS', 'الحالة');
tz_def('ADM_ALIVE', 'حي');
tz_def('ADM_DEAD', 'ميت');
tz_def('ADM_DETAILS', 'التفاصيل');
tz_def('ADM_POINT', 'النقطة');
tz_def('ADM_LEVEL_2', 'المستوى');
tz_def('ADM_OFFENCE_2', 'الهجوم');
tz_def('ADM_DEFENCE_2', 'الدفاع');
tz_def('ADM_OFF_BONUS_2', 'مكافأة الهجوم');
tz_def('ADM_DEF_BONUS_2', 'مكافأة الدفاع');
tz_def('ADM_REGENERATION', 'التعافي');
tz_def('ADM_RESOURCES_2', 'الموارد');
tz_def('ADM_HEALTH_2', 'الصحة');
tz_def('ADM_EDIT', 'تعديل');
tz_def('ADM_T4_ITEMS_SILVER_AUCTIONS', 'عناصر / فضة / مزادات T4');
tz_def('ADM_KILL', 'قتل');
tz_def('ADM_REVIVE', 'إحياء');
tz_def('ADM_VILLAGE_INFORMATION', 'معلومات القرية');
tz_def('ADM_OWNER', 'المالك');
tz_def('ADM_NAME', 'الاسم');
tz_def('ADM_POPULATION', 'السكان');
tz_def('ADM_COORDS', 'الإحداثيات');
tz_def('ADM_FIELD', 'الحقل');
tz_def('ADM_RES', 'المورد');
tz_def('ADM_AMT', 'الكمية');
tz_def('ADM_CAP', 'السعة');
tz_def('ADM_PROD', 'الإنتاج');
tz_def('ADM_WOOD', 'الخشب');
tz_def('ADM_CLAY', 'الطين');
tz_def('ADM_IRON', 'الحديد');
tz_def('ADM_CROP', 'المحصول');
tz_def('ADM_EXPANSION', 'التوسّع');
tz_def('ADM_VILLAGE', 'القرية');
tz_def('ADM_POP', 'السكان');
tz_def('ADM_CP', 'نقاط الثقافة');
tz_def('ADM_OASIS', 'الواحة');
tz_def('ADM_LOY', 'الولاء');
tz_def('ADM_BONUS', 'المكافأة');
tz_def('ADM_TROOPS', 'القوات');
tz_def('ADM_UPGRADES', 'الترقيات');
tz_def('ADM_ARTIFACT', 'القطعة الأثرية');
tz_def('ADM_RESOURCE_FIELDS', 'حقول الموارد');
tz_def('ADM_BUILDINGS', 'المباني');
tz_def('ADM_GID', 'GID');
tz_def('ADM_LVL', 'المستوى');
tz_def('ADM_VILLAGE_BUILD_LOG', 'سجل بناء القرية ←');
tz_def('ADM_CHANGE', 'تغيير');
tz_def('ADM_RENAME', 'إعادة تسمية');
tz_def('ADM_SHOW_INSTRUCTIONS', 'عرض التعليمات');
tz_def('ADM_RESOURCE_FIELDS_1_18', 'حقول الموارد (1-18)');
tz_def('ADM_VILLAGE_CENTER_19_38', 'مركز القرية (19-38)');
tz_def('ADM_MODIFY_BUILDINGS', 'تعديل المباني');
tz_def('ADM_SAVE_CHANGES', 'حفظ التغييرات');
tz_def('ADM_CURRENT_LAYOUT_PREVIEW', 'معاينة التخطيط الحالي');
tz_def('ADM_VILLAGE_CENTER', 'مركز القرية');
tz_def('ADM_EDIT_PLAYER', '✏️ تعديل اللاعب:');
tz_def('ADM_ACCOUNT_DETAILS', '👤 تفاصيل الحساب');
tz_def('ADM_USERNAME_2', '👤 اسم المستخدم');
tz_def('ADM_SAVE_2', '💾 حفظ');
tz_def('ADM_TRIBE_2', '⚔️ القبيلة');
tz_def('ADM_1_ROMAN', '1. الروماني');
tz_def('ADM_2_TEUTON', '2. التيوتوني');
tz_def('ADM_3_GAUL', '3. الغالي');
tz_def('ADM_4_NATURE', '4. الطبيعة');
tz_def('ADM_5_NATARS', '5. التتار');
tz_def('ADM_6_HUN', '6. الهوني');
tz_def('ADM_7_EGYPTIAN', '7. المصري');
tz_def('ADM_8_SPARTAN', '8. الإسبرطي');
tz_def('ADM_9_VIKING', '9. الفايكنغي');
tz_def('ADM_LOCATION', '📍 الموقع');
tz_def('ADM_E_MAIL', '✉️ البريد الإلكتروني');
tz_def('ADM_QUEST', '🎯 المهمة');
tz_def('ADM_PROFILE_DESCRIPTION_LEFT', '📝 وصف الملف الشخصي (يسار)');
tz_def('ADM_PROFILE_DESCRIPTION_RIGHT', '📄 وصف الملف الشخصي (يمين)');
tz_def('ADM_SUPORT_BBCODE_FOLOSE_TE_ENTER_PENTRU_LINII_N', 'يدعم BBCode. استخدم Enter للأسطر الجديدة.');
tz_def('ADM_MEDALS', '🏅 الأوسمة');
tz_def('ADM_CATEGORY', 'الفئة');
tz_def('ADM_RANK', 'الترتيب');
tz_def('ADM_WEEK', 'الأسبوع');
tz_def('ADM_BB_CODE', 'BB-Code');
tz_def('ADM_BEGINNERS_PROTECTION', 'حماية المبتدئين');
tz_def('ADM_SAVE_CHANGES_2', '💾 حفظ التغييرات');
tz_def('ADM_GO_BACK_TO_PLAYER', '« العودة إلى اللاعب');
tz_def('ADM_CHANGE_USERNAME', 'تغيير اسم المستخدم');
tz_def('ADM_DELETE_PLAYER_PERMANENTLY', 'حذف اللاعب نهائيًا');
tz_def('ADM_THIS_ACTION_CANNOT_BE_UNDONE', 'لا يمكن التراجع عن هذا الإجراء!');
tz_def('ADM_PLAYER_TO_DELETE', '🗑️ اللاعب المراد حذفه');
tz_def('ADM_PLUS_ENDS', 'ينتهي بلس');
tz_def('ADM_WARNING', 'تحذير:');
tz_def('ADM_PERMANENTLY', 'نهائيًا');
tz_def('ADM_DELETED_THERE_IS_NO_RECOVERY', 'محذوف. لا يمكن الاسترجاع!');
tz_def('ADM_CONFIRM_WITH_YOUR_ADMIN_PASSWORD', 'أكّد بكلمة مرور الأدمن الخاصة بك:');
tz_def('ADM_CANCEL_2', '← إلغاء');
tz_def('ADM_DELETE_PLAYER', '🗑️ حذف اللاعب');
tz_def('ADM_EDIT_SITTERS', 'تعديل الجالسين:');
tz_def('ADM_SITTER_SETTINGS', '⚙️ إعدادات الجالس');
tz_def('ADM_USE_THE_PLAYER_S_UID_ENTER', '💡 استخدم معرّف اللاعب (UID). أدخل');
tz_def('ADM_TO_DELETE_THE_SITTER_YOU_CAN_FIND_THE_UID_IN', 'لحذف الجالس. يمكنك إيجاد المعرّف في البحث.');
tz_def('ADM_SITTER_1', '👤 الجالس 1');
tz_def('ADM_SITTER_2', '👤 الجالس 2');
tz_def('ADM_SAVE_SITTERS', '💾 حفظ الجالسين');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// PANOU DE ADMINISTRARE - sabloane (lot 4: gold, usergold, givePlus, givePlusRes,
// punish, maintenance, resetServer, cleanban, massmessage, sysmessage)
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ADM_GOLD_MANAGEMENT', 'إدارة الذهب');
tz_def('ADM_GIVE_EVERYONE_FREE_GOLD', 'منح الجميع ذهبًا مجانيًا');
tz_def('ADM_THIS_GOLD_WILL_BE_ADDED_TO_ALL_ACTIVE_PLAYER', 'سيُضاف هذا الذهب إلى كل اللاعبين النشطين في السيرفر.');
tz_def('ADM_GIVE_GOLD', 'منح الذهب');
tz_def('ADM_GOLD_HAS_BEEN_SUCCESSFULLY_ADDED_TO_ALL_PLAY', '✓ تمت إضافة الذهب بنجاح لجميع اللاعبين!');
tz_def('ADM_USER_GOLD', 'ذهب المستخدم');
tz_def('ADM_GIVE_GOLD_TO_SPECIFIC_PLAYER', 'منح ذهب للاعب محدد');
tz_def('ADM_ADD_GOLD_ONLY_FOR_SINGLE_ACCOUNT_AFTER_ID', 'إضافة الذهب لحساب واحد فقط (بواسطة المعرّف).');
tz_def('ADM_AMOUNT_GOLD', 'كمية الذهب');
tz_def('ADM_GOLD_LOCAL_ONLY_LABEL', 'محلي فقط (لا يُزامَن مع رصيد الذهب العابر للعوالم لهذا اللاعب)');
tz_def('ADM_GOLD_SUCCESSFULLY_ADDED_FOR_USER', '✓ تمت إضافة الذهب بنجاح للمستخدم!');
tz_def('ADM_PLUS_MANAGEMENT', 'إدارة بلس');
tz_def('ADM_GIVE_EVERYONE_FREE_PLUS', 'منح الجميع بلس مجانًا');
tz_def('ADM_ACTIVATE_NOVATERRA_PLUS_FOR_ALL_PLAYERS_ON_THE', 'تفعيل نوفاتيرا بلس لكل اللاعبين في السيرفر.');
tz_def('ADM_DAYS', 'الأيام');
tz_def('ADM_GIVE_PLUS', 'منح بلس');
tz_def('ADM_PLUS_HAS_BEEN_SUCCESSFULLY_ACTIVATED_FOR_ALL', '✓ تم تفعيل بلس بنجاح لجميع اللاعبين!');
tz_def('ADM_RESOURCE_BONUS', 'مكافأة الموارد');
tz_def('ADM_GIVE_EVERYONE_RESOURCE_BONUS', 'منح الجميع مكافأة موارد');
tz_def('ADM_ACTIVATE_A_25_BONUS_FOR_ALL_RESOURCES_FOR_AL', 'تفعيل مكافأة 25% لكل الموارد لجميع اللاعبين.');
tz_def('ADM_DAYS_2', 'أيام');
tz_def('ADM_GIVE_RESOURCE_BONUS', 'منح مكافأة الموارد');
tz_def('ADM_RESOURCE_BONUSES_HAVE_BEEN_ACTIVATED_FOR_ALL', '✓ تم تفعيل مكافآت الموارد لجميع اللاعبين!');
tz_def('ADM_PUNISH_PLAYER', 'معاقبة اللاعب');
tz_def('ADM_OK', 'موافق');
tz_def('ADM_DELETE_TROOPS', 'حذف القوات');
tz_def('ADM_EMPTY_WAREHOUSES', 'إفراغ المخازن');
tz_def('ADM_STARTED_BY', 'بدأه:');
tz_def('ADM_UID', '(المعرّف:');
tz_def('ADM_ENABLE_MAINTENANCE', 'تفعيل الصيانة');
tz_def('ADM_DISABLE_MAINTENANCE', 'تعطيل الصيانة');
tz_def('ADM_STOP', 'إيقاف');
tz_def('ADM_SERVER_RESETTING', '⚠️ إعادة ضبط السيرفر');
tz_def('ADM_WARNING_IRREVERSIBLE_ACTION', 'تحذير! إجراء لا يمكن التراجع عنه');
tz_def('ADM_THIS_OPERATION_WILL_DELETE_ALL_DATA_PLAYERS', 'ستحذف هذه العملية كل البيانات: اللاعبين، المراقبات، التحالفات، التقارير. سيُعاد ضبط السيرفر للعبة جديدة.');
tz_def('ADM_THIS_SERVER_WILL_BE_RESET_TO_CREATE_NEW_GAME', 'سيُعاد ضبط هذا السيرفر لإنشاء سيرفر لعبة جديد.');
tz_def('ADM_CLICK_BUTTON', 'اضغط الزر');
tz_def('ADM_RESET', 'إعادة ضبط');
tz_def('ADM_TO_PROCEED', 'للمتابعة.');
tz_def('ADM_PLEASE_WAIT_WHILE_THE_SERVER_IS_BEING_RESET', 'الرجاء الانتظار... جارٍ إعادة ضبط السيرفر');
tz_def('ADM_BACK_2', '« رجوع');
tz_def('ADM_RESET_SERVER', 'إعادة ضبط السيرفر');
tz_def('ADM_CLEAR_BANLIST_DATA', 'مسح – بيانات - قائمة الحظر');
tz_def('ADM_TRUNCATE', '(تفريغ الجدول TRUNCATE)');
tz_def('ADM_CLEAN', 'تنظيف');
tz_def('ADM_SEND_MESSAGE_TO_ALL_PLAYERS', 'إرسال رسالة لجميع اللاعبين');
tz_def('ADM_CONFIRM', 'تأكيد:');
tz_def('ADM_ARE_YOU_SURE_YOU_WANT_TO_SEND', 'هل أنت متأكد أنك تريد الإرسال؟');
tz_def('ADM_SUBJECT', 'الموضوع:');
tz_def('ADM_YES_SEND', '✓ نعم، أرسل');
tz_def('ADM_SENDING_MESSAGES', 'جارٍ إرسال الرسائل...');
tz_def('ADM_SUBJECT_2', 'الموضوع');
tz_def('ADM_MESSAGE_COLOR', 'لون الرسالة');
tz_def('ADM_MESSAGE_CONTENT', 'محتوى الرسالة');
tz_def('ADM_CONTINUA', 'متابعة');
tz_def('ADM_MASS_MESSAGE_SUCCESSFULLY_SENT_TO_ALL_PLAYER', '✓ تم إرسال الرسالة الجماعية بنجاح لجميع اللاعبين!');
tz_def('ADM_EX_MAINTENANCE', 'مثال: صيانة');
tz_def('ADM_BLACK_SAU_E67E22', 'black أو #e67e22');
tz_def('ADM_WRITE_THE_MESSAGE_YOU_CAN_USE_URL_IMG', 'اكتب الرسالة... يمكنك استخدام [url][img]');
tz_def('ADM_CONFIRMARE_SYSTEM_MESSAGE', 'تأكيد رسالة النظام');
tz_def('ADM_COLOR', 'اللون:');
tz_def('ADM_SEND_SYSTEM_MESSAGE', '✓ إرسال رسالة نظام');
tz_def('ADM_SENDING_SYSTEM_MESSAGE', 'جارٍ إرسال رسالة النظام...');
tz_def('ADM_COLOR_2', 'اللون');
tz_def('ADM_CONTINUE', 'متابعة');
tz_def('ADM_SYSTEM_MESSAGE_SENT_SUCCESSFULLY', '✓ تم إرسال رسالة النظام بنجاح');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// PANOU DE ADMINISTRARE - sabloane (lot 5: alliance, editAli, delAli, allymedals,
// delallymedal, playermedals, delmedal, admin_log, debug_log, techlog)
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ADM_FOUNDER', '👑 المؤسس:');
tz_def('ADM_MEMBERS', '👥 الأعضاء:');
tz_def('ADM_POINTS', '🏆 النقاط:');
tz_def('ADM_ALLIANCE_DETAILS', '📋 تفاصيل التحالف');
tz_def('ADM_TAG', 'الشعار');
tz_def('ADM_POINTS_2', 'النقاط');
tz_def('ADM_CAPACITY', 'السعة');
tz_def('ADM_ALLIANCE_POSITIONS', '🛡 مناصب التحالف');
tz_def('ADM_EDIT_ALLIANCE', '✏ تعديل التحالف');
tz_def('ADM_DELETE_2', '🗑 حذف');
tz_def('ADM_ALLIANCE_DESCRIPTION', '📖 وصف التحالف');
tz_def('ADM_ALLIANCE_NOTICE', '📢 إشعار التحالف');
tz_def('ADM_PLAYER_2', 'اللاعب');
tz_def('ADM_ALLIANCE_NEWS', '📰 أخبار التحالف');
tz_def('ADM_EVENT', 'الحدث');
tz_def('ADM_DIPLOMACY', '🤝 الدبلوماسية');
tz_def('ADM_TYPE', 'النوع');
tz_def('ADM_EDIT_ALLIANCE_2', '✏️ تعديل التحالف:');
tz_def('ADM_BASIC_SETTINGS', '⚙️ الإعدادات الأساسية');
tz_def('ADM_ALLIANCE_TAG_MAX_8', 'شعار التحالف (8 حروف كحد أقصى)');
tz_def('ADM_ALLIANCE_NAME', 'اسم التحالف');
tz_def('ADM_LEADER_FOUNDER', '👑 القائد (المؤسس)');
tz_def('ADM_SCHIMB_FONDATORUL_ALIAN_EI', 'تغيير مؤسس التحالف');
tz_def('ADM_MAX_MEMBERS', '👥 الحد الأقصى للأعضاء');
tz_def('ADM_ALLIANCE_NOTICE_APARE_SUS', '📢 إشعار التحالف (يظهر أعلى الصفحة)');
tz_def('ADM_ALLIANCE_DESCRIPTION_PAGINA_PUBLIC', '📖 وصف التحالف (الصفحة العامة)');
tz_def('ADM_SAVE_ALLIANCE', '💾 حفظ التحالف');
tz_def('ADM_MESAJ_SCURT_PENTRU_MEMBRI', 'رسالة قصيرة للأعضاء...');
tz_def('ADM_DESCRIERE_LUNG_BBCODE_PERMIS', 'وصف طويل، يدعم BBCode...');
tz_def('ADM_DELETE_ALLIANCE', '🗑 حذف التحالف');
tz_def('ADM_ARE_YOU_SURE_YOU_WANT_TO_PERMANENTLY_DELETE', 'هل أنت متأكد أنك تريد الحذف نهائيًا؟:');
tz_def('ADM_MEMBERS_2', 'الأعضاء:');
tz_def('ADM_POINTS_3', '| النقاط:');
tz_def('ADM_ALL_MEMBERS_WILL_BE_REMOVED_FROM_THE_ALLIANC', '⚠ سيُزال كل الأعضاء من التحالف، وستُحذف الصلاحيات والدبلوماسية والسجلات ومنتدى التحالف. هذا الإجراء لا رجعة فيه!');
tz_def('ADM_YES_DELETE', 'نعم، احذف');
tz_def('ADM_MEDAL', 'الوسام');
tz_def('ADM_THIS_ALLIANCE_HAS_NO_MEDALS_YET', 'هذا التحالف ليس لديه أوسمة بعد');
tz_def('ADM_AVERAGE_RANK', 'متوسط الترتيب');
tz_def('ADM_DELETE_ALL', 'حذف الكل');
tz_def('ADM_MEDAL_INFORMATION', 'معلومات الوسام');
tz_def('ADM_MEDALS_2', 'الأوسمة');
tz_def('ADM_MEDAL_WEEK_BY_WEEK', 'الوسام أسبوعًا بأسبوع');
tz_def('ADM_CATEGORY_2', 'الفئة');
tz_def('ADM_RANK_2', 'الترتيب');
tz_def('ADM_WEEK_2', 'الأسبوع');
tz_def('ADM_POINTS_4', 'النقاط');
tz_def('ADM_MEDAL_2', 'الوسام');
tz_def('ADM_THIS_PLAYER_HAS_NO_MEDALS_YET', 'هذا اللاعب ليس لديه أوسمة بعد');
tz_def('ADM_UNIFIED_ADMIN_LOG_LAST_300_ACTIONS', '📋 سجل الإدارة الموحّد – آخر 300 إجراء');
tz_def('ADM_CATEGORIE', 'الفئة');
tz_def('ADM_DETALII', 'التفاصيل');
tz_def('ADM_DATA', 'التاريخ');
tz_def('ADM_DEBUG_ERROR_LOG', '🐞 سجل أخطاء التصحيح');
tz_def('ADM_CAPTURE_IS', 'التسجيل حاليًا');
tz_def('ADM_AUTO_OFF', 'الإيقاف التلقائي:');
tz_def('ADM_LOG_SIZE', 'حجم السجل:');
tz_def('ADM_TRANSPARENT_TO_PLAYERS_ERRORS_ARE_ONLY_WRITT', 'شفاف تمامًا للاعبين: تُكتب الأخطاء في ملف السجل فقط، ولا تظهر أبدًا داخل اللعبة، ولا يتأثر اللعب بها.');
tz_def('ADM_CAPTURE_SETTINGS', 'إعدادات التسجيل');
tz_def('ADM_WARNINGS', 'التحذيرات');
tz_def('ADM_NOTICES', 'الملاحظات');
tz_def('ADM_DEPRECATED', 'المهملة');
tz_def('ADM_FATAL_ERRORS', 'الأخطاء الفادحة');
tz_def('ADM_MAX_FILE_SIZE_MB', 'الحد الأقصى لحجم الملف (ميجابايت):');
tz_def('ADM_AUTO_OFF_AFTER_HOURS_0_NEVER', 'الإيقاف التلقائي بعد (ساعات، 0 = أبدًا):');
tz_def('ADM_SAVE_SETTINGS', 'حفظ الإعدادات');
tz_def('ADM_BEYOND_THE_SIZE_CAP_THE_FILE_IS_ROTATED_TO_A', 'عند تجاوز الحد الأقصى للحجم، يُنقل الملف إلى نسخة');
tz_def('ADM_BACKUP_SO_THE_TOTAL_VOLUME_STAYS_BOUNDED', 'احتياطية واحدة، بحيث يظل الحجم الإجمالي محدودًا.');
tz_def('ADM_DOWNLOAD_FULL_LOG', '⬇ تحميل السجل الكامل');
tz_def('ADM_CLEAR_LOG', '🗑 مسح السجل');
tz_def('ADM_REFRESH', '↻ تحديث');
tz_def('ADM_BACK_TO_VILLAGE', '← العودة إلى القرية');
tz_def('ADM_RESEARCH_LOG', '— سجل البحث');
tz_def('ADM_LATEST_200_SEARCHES', 'آخر 200 عملية بحث');
tz_def('ADM_NO_REGISTERED_SEARCHES', 'لا توجد عمليات بحث مسجّلة.');
tz_def('ADM_TECH', 'تقني');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// PANOU DE ADMINISTRARE - sabloane (lot 6: editAccess, editPassword, editProtection,
// editPlus, editWeek, editOverall, editAdditional, results_player/alliances/villages)
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ADM_CHANGE_ACCESS', 'تغيير الصلاحية');
tz_def('ADM_NORMAL_USER', 'مستخدم عادي');
tz_def('ADM_MULTIHUNTER', 'مطارد الحسابات المتعددة');
tz_def('ADM_GIVE_PLAYERS_FREE_GOLD', 'منح اللاعبين ذهبًا مجانيًا');
tz_def('ADM_CHANGE_PASSWORD', 'تغيير كلمة المرور:');
tz_def('ADM_THE_PASSWORD_IS_CHANGED_INSTANTLY_THE_PLAYER', 'تُغيَّر كلمة المرور فورًا. سيُسجَّل خروج اللاعب. لا يُرسَل أي بريد إلكتروني تلقائيًا.');
tz_def('ADM_NEW_PASSWORD', 'كلمة المرور الجديدة');
tz_def('ADM_GENERATE_SECURE_PASSWORD', '🎲 توليد كلمة مرور آمنة');
tz_def('ADM_CHANGE_PASSWORD_2', '🔐 تغيير كلمة المرور');
tz_def('ADM_INTRODU_PAROLA_NOU', 'أدخل كلمة المرور الجديدة');
tz_def('ADM_SHOW_HIDE', 'إظهار/إخفاء');
tz_def('ADM_PROTECTION', 'الحماية:');
tz_def('ADM_CURRENT_STATUS_2', 'الحالة الحالية');
tz_def('ADM_1_DAY_2', '+يوم واحد');
tz_def('ADM_3_DAYS_2', '+3 أيام');
tz_def('ADM_7_DAYS_2', '+7 أيام');
tz_def('ADM_SET_TO_0_TO_REMOVE_PROTECTION_PROTECTION_IS', '💡 اضبطها على 0 لإزالة الحماية. تُضاف الحماية من وقت الحفظ.');
tz_def('ADM_SAVE_PROTECTION', '💾 حفظ الحماية');
tz_def('ADM_PLUS_BONUSES', '⭐ بلس والمكافآت:');
tz_def('ADM_THE_VALUES_ADD_UP_PUT_5_TO_ADD_5_DAYS_5_TO_R', 'ℹ️ القيم تتراكم. ضع 5 لإضافة 5 أيام، -5 لإزالة 5 أيام.');
tz_def('ADM_CURRENT_ACTIVE_BONUSES', '📊 المكافآت النشطة حاليًا');
tz_def('ADM_ADD_REMOVE_DAYS', '➕ إضافة / إزالة أيام');
tz_def('ADM_APPLY_BONUSES', '💾 تطبيق المكافآت');
tz_def('ADM_WEEKLY_STATS', 'إحصائيات الأسبوع:');
tz_def('ADM_ACESTEA_SUNT_PUNCTELE', '📊 هذه هي النقاط');
tz_def('ADM_DIN_S_PT_M_NA_CURENT', 'من الأسبوع الحالي');
tz_def('ADM_PENTRU_TOP_10_SE_RESETEAZ_AUTOMAT', 'لأفضل 10. تُعاد ضبطها تلقائيًا.');
tz_def('ADM_ATTACK', 'الهجوم');
tz_def('ADM_RAID', 'الغارة');
tz_def('ADM_SAVE_WEEK', '💾 حفظ الأسبوع');
tz_def('ADM_OVERALL_POINTS', 'النقاط الإجمالية:');
tz_def('ADM_ACESTEA_SUNT_PUNCTELE_2', '🛡️ هذه هي النقاط');
tz_def('ADM_TOTALE', 'الإجمالية');
tz_def('ADM_DIN_STATISTICI_NU_CELE_S_PT_M_NALE_SE_ADUN_D', 'من الإحصائيات (وليست الأسبوعية). تُجمع من كل الهجمات.');
tz_def('ADM_ATTACK_POINTS', 'نقاط الهجوم');
tz_def('ADM_DEFENCE_POINTS', 'نقاط الدفاع');
tz_def('ADM_SAVE_POINTS', '💾 حفظ النقاط');
tz_def('ADM_EDIT_ADDITIONAL', '⚙️ تعديل إضافي:');
tz_def('ADM_ACCOUNT_CONTROL', '🔐 التحكم في الحساب');
tz_def('ADM_ACCESS_LEVEL', 'مستوى الصلاحية');
tz_def('ADM_0_BANNED', '0 - محظور');
tz_def('ADM_2_NORMAL_USER', '2 - مستخدم عادي');
tz_def('ADM_8_MULTIHUNTER', '8 - مطارد الحسابات المتعددة');
tz_def('ADM_9_ADMIN', '9 - أدمن');
tz_def('ADM_VACATION_MODE_2', '🏖 وضع الإجازة');
tz_def('ADM_0_DISABLED', '0 - معطّل');
tz_def('ADM_1_ENABLED', '1 - مفعّل');
tz_def('ADM_PROTECTION_2', '🛡️ الحماية');
tz_def('ADM_ZILE', 'أيام');
tz_def('ADM_SITTERS', '👥 الجالسون');
tz_def('ADM_SITTER_1_UID', 'الجالس 1 (المعرّف)');
tz_def('ADM_SITTER_2_UID', 'الجالس 2 (المعرّف)');
tz_def('ADM_STATISTICS_POINTS', '📊 الإحصائيات والنقاط');
tz_def('ADM_CULTURE_POINTS', '🏛️ نقاط الثقافة');
tz_def('ADM_ATTACK_POINTS_2', '⚔️ نقاط الهجوم');
tz_def('ADM_DEFENCE_POINTS_2', '🛡️ نقاط الدفاع');
tz_def('ADM_RESOURCES_RAIDED', '💎 الموارد المنهوبة');
tz_def('ADM_TOTAL_ATTACK', '⚔️ إجمالي الهجوم');
tz_def('ADM_TOTAL_DEFENCE', '🛡️ إجمالي الدفاع');
tz_def('ADM_GOLD_2', 'ذهب');
tz_def('ADM_UID_2', 'المعرّف');
tz_def('ADM_PLAYER_3', 'اللاعب');
tz_def('ADM_VILLAGES_2', 'القرى');
tz_def('ADM_POP_2', 'السكان');
tz_def('ADM_AID', 'AID');
tz_def('ADM_NAME_2', 'الاسم');
tz_def('ADM_TAG_2', 'الشعار');
tz_def('ADM_FOUNDER_2', 'المؤسس');
tz_def('ADM_VILLAGE_NAME', 'اسم القرية');
tz_def('ADM_OWNER_2', 'المالك');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// لوحة الأدمن - قوالب (الدفعة 7: التتار، الخريطة، مربع الخريطة، القطع الأثرية،
// متجر الذهب، محرر المهام، الحسابات المتعددة، حماية الدفع، حظر التسجيل، خريطة الكثافة)
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ADM_WW_VILLAGES', '🏰 قرى عجائب الدنيا');
tz_def('ADM_NUMBER', 'الرقم');
tz_def('ADM_PLAYER_ID', 'معرّف اللاعب');
tz_def('ADM_ADD_ARTIFACTS', '✨ إضافة قطع أثرية');
tz_def('ADM_ICON', 'الأيقونة');
tz_def('ADM_DELETED_ARTIFACTS', '🗑️ القطع الأثرية المحذوفة');
tz_def('ADM_EFFECT', 'التأثير');
tz_def('ADM_OLD_OWNER', 'المالك السابق');
tz_def('ADM_OLD_VILLAGE', 'القرية السابقة');
tz_def('ADM_ARTIFACTS_OVERVIEW', '📊 نظرة عامة على القطع الأثرية');
tz_def('ADM_RESTORE', 'استعادة');
tz_def('ADM_INTERACTIVE_WORLD_MAP_SEARCH_PLAYERS_VILLAGE', 'خريطة العالم التفاعلية – البحث عن اللاعبين والقرى والقطع الأثرية');
tz_def('ADM_PLAYERS', 'اللاعبون');
tz_def('ADM_ARTIFACTS', 'القطع الأثرية');
tz_def('ADM_APPLY_FILTERS', 'تطبيق الفلاتر');
tz_def('ADM_TRIBES', 'القبائل');
tz_def('ADM_MULTIHUNTERS', 'مطاردو الحسابات المتعددة');
tz_def('ADM_CHECK_EDIT_MAP_TILE', 'فحص وتعديل مربع الخريطة');
tz_def('ADM_CHECK_TILE', 'فحص المربع');
tz_def('ADM_AREA_OF_EFFECT', 'نطاق التأثير');
tz_def('ADM_TIME_OF_CONQUER', 'وقت الاحتلال');
tz_def('ADM_TIME_OF_ACTIVATION', 'وقت التفعيل');
tz_def('ADM_RETURN_TO_NATARS', 'إعادة للتتار');
tz_def('ADM_SHOP_PROMO_CODES', 'المتجر وأكواد العروض');
tz_def('ADM_DIRECTLY', 'مباشرة.');
tz_def('ADM_CREATE_A_CODE', 'إنشاء كود');
tz_def('ADM_CODE', 'الكود');
tz_def('ADM_MAX_USES_0', 'الحد الأقصى للاستخدام (0 = &infin;)');
tz_def('ADM_EXPIRES_IN_DAYS_0_NEVER', 'ينتهي خلال (أيام، 0 = لا ينتهي)');
tz_def('ADM_ONCE_PER_PLAYER', 'مرة واحدة لكل لاعب');
tz_def('ADM_NOTE_OPTIONAL', 'ملاحظة (اختياري)');
tz_def('ADM_CREATE', 'إنشاء');
tz_def('ADM_CODES_ARE_CASE_INSENSITIVE_ALLOWED_CHARACTER', 'الأكواد غير حساسة لحالة الأحرف؛ المسموح: A-Z 0-9 . _ &ndash;');
tz_def('ADM_NO_PROMO_CODES_YET', 'لا توجد أكواد عروض بعد.');
tz_def('ADM_USES', 'مرات الاستخدام');
tz_def('ADM_SCOPE', 'النطاق');
tz_def('ADM_NOTE', 'ملاحظة');
tz_def('ADM_RECENT_REDEMPTIONS', 'آخر عمليات الاستخدام');
tz_def('ADM_NO_REDEMPTIONS_YET', 'لا توجد عمليات استخدام بعد.');
tz_def('ADM_WHEN', 'الوقت');
tz_def('ADM_REASON_CAMPAIGN', 'السبب / الحملة');
tz_def('ADM_QUEST_2', 'المهمة');
tz_def('ADM_EDITOR', 'المحرر');
tz_def('ADM_QTYP_37', 'qtyp&nbsp;37');
tz_def('ADM_GET', 'الحصول على');
tz_def('ADM_EXTENDED', 'موسّعة');
tz_def('ADM_EVERYONE_ELSE', '، البقية');
tz_def('ADM_STANDARD', 'قياسية');
tz_def('ADM_REWARD_VALUES_ARE_LIVE_VIA', 'قيم المكافآت مباشرة عبر');
tz_def('ADM_FIXED', 'ثابتة');
tz_def('ADM_STANDARD_QUEST_CORE25', 'قياسية (quest_core25)');
tz_def('ADM_EXTENDED_QUEST_CORE', 'موسّعة (quest_core)');
tz_def('ADM_ON', 'مفعّل');
tz_def('ADM_PLUS_DAYS', 'بلس (أيام)');
tz_def('ADM_REQ_LEVEL', 'المستوى&nbsp;المطلوب');
tz_def('ADM_QUEST_HOVER', 'المهمة (تلميح)');
tz_def('ADM_SAVE_ALL_CHANGES', 'حفظ كل التغييرات');
tz_def('ADM_SAVES_EVERY_ROW_FOR_THE', 'يحفظ كل صف لنسخة');
tz_def('ADM_VARIANT', 'المهام.');
tz_def('ADM_RESET_THIS_VARIANT_TO_DEFAULTS', 'إعادة هذه النسخة للإعدادات الافتراضية');
tz_def('ADM_MULTI_ACCOUNT', 'الحسابات المتعددة');
tz_def('ADM_DETECTION', 'الكشف');
tz_def('ADM_RISK_SCORE_NOT_PROOF', 'درجة خطورة، وليست إثباتًا');
tz_def('ADM_WINDOW_DAYS', 'النافذة الزمنية (أيام)');
tz_def('ADM_MIN_SCORE', 'الحد الأدنى للدرجة');
tz_def('ADM_FOCUS_ON_UID_OPTIONAL', 'التركيز على معرّف مستخدم (اختياري)');
tz_def('ADM_ANALYSE', 'تحليل');
tz_def('ADM_MAD_ON', 'مفعّل');
tz_def('ADM_MAD_OFF', 'متوقف');
tz_def('ADM_MAD_AUTO_BAN', 'الحظر التلقائي');
tz_def('ADM_MAD_ENABLE_DETECTION', 'كشف الحسابات المتعددة');
tz_def('ADM_MAD_ENABLE_AUTO_BAN', 'الحظر التلقائي');
tz_def('ADM_MAD_AUTO_BAN_THRESHOLD', 'حد درجة الحظر التلقائي');
tz_def('ADM_MAD_AUTO_BAN_HINT', 'عند تفعيل الخيارين معًا، أي زوج حسابات يصل لدرجة الاشتباه المحددة يتم حظره تلقائيًا فور تحميل هذه الصفحة أو حفظ هذه الإعدادات.');
tz_def('ADM_MAD_AUTO_BAN_JUST_RAN', 'تم تنفيذ الحظر التلقائي الآن');
tz_def('ADM_MAD_FULL_ADMIN_ONLY', 'فقط المشرف الكامل يمكنه تغيير هذه الإعدادات.');
tz_def('ADM_MAD_DETECTION_DISABLED_NOTICE', 'كشف الحسابات المتعددة متوقف حاليًا. قم بتفعيله أعلاه للبحث عن أزواج الحسابات المشتبه بها.');
tz_def('ADM_MAD_VIEW_PAIR', 'عرض');
tz_def('ADM_MAD_PLAYER_DASHBOARD', 'إدارة الحسابات المتعددة');
tz_def('ADM_MAD_BACK_TO_OVERVIEW', 'الرجوع لكشف الحسابات المتعددة');
tz_def('ADM_MAD_BANNED', 'محظور');
tz_def('ADM_MAD_ACTIVE', 'نشط');
tz_def('ADM_MAD_RELATED_PROTECTION_OFF_NOTICE', 'حماية الحسابات المرتبطة متوقفة حاليًا — لا يتم منع النهب بين الحسابات المرتبطة.');
tz_def('ADM_MAD_ACCOUNT_ACTIONS', 'الإجراءات');
tz_def('ADM_MAD_BAN_ACCOUNT', 'حظر الحساب');
tz_def('ADM_MAD_UNBAN_ACCOUNT', 'إلغاء الحظر');
tz_def('ADM_MAD_BAN_REASON', 'السبب');
tz_def('ADM_MAD_RELATE_TO_UID', 'ربط بمعرف الحساب');
tz_def('ADM_MAD_REASON_OPTIONAL', 'السبب (اختياري)');
tz_def('ADM_MAD_MARK_AS_RELATED', 'تصنيف كحساب مرتبط');
tz_def('ADM_MAD_SUSPECTED_PAIRS', 'الأزواج المشتبه بها تلقائيًا');
tz_def('ADM_MAD_NO_SUSPECTED_PAIRS', 'لا توجد أزواج مشتبه بها لهذا الحساب.');
tz_def('ADM_MAD_OTHER_ACCOUNT', 'الحساب');
tz_def('ADM_MAD_RELATED_ACCOUNTS', 'الحسابات المرتبطة يدويًا');
tz_def('ADM_MAD_NO_RELATED_ACCOUNTS', 'لا توجد حسابات مرتبطة يدويًا بهذا الحساب.');
tz_def('ADM_MAD_REASON', 'السبب');
tz_def('ADM_MAD_ADDED', 'تمت الإضافة');
tz_def('ADM_MAD_REMOVE', 'إزالة');
tz_def('ADM_MAD_IPS_AND_DEVICES', 'عناوين IP والأجهزة');
tz_def('ADM_MAD_NO_FINGERPRINTS', 'لا توجد بصمات IP/جهاز مسجلة بعد.');
tz_def('ADM_MAD_HITS', 'عدد المرات');
tz_def('ADM_MAD_LAST_SEEN', 'آخر ظهور');
tz_def('ADM_MAD_BLOCKED_ATTEMPTS', 'محاولات النهب/التحويل الممنوعة');
tz_def('ADM_MAD_NO_BLOCKED_ATTEMPTS', 'لا توجد محاولات ممنوعة مسجلة لهذا الحساب.');
tz_def('ADM_MAD_FROM', 'من');
tz_def('ADM_MAD_TO', 'إلى');
tz_def('ADM_MAD_RESOURCES', 'الموارد');
tz_def('ADM_MAD_WHEN', 'الوقت');
tz_def('ADM_MAD_RECENT_LOGINS', 'سجل الدخول الأخير');
tz_def('ADM_MAD_NO_LOGIN_RECORDS', 'لا توجد سجلات دخول.');
tz_def('ADM_MAD_BAN_HISTORY', 'سجل الحظر');
tz_def('ADM_MAD_NO_BAN_HISTORY', 'لا يوجد سجل حظر لهذا الحساب.');
tz_def('ADM_MAD_STATUS', 'الحالة');
tz_def('ADM_MAD_LIFTED', 'مرفوع');
tz_def('ADM_NO_ACCOUNT_PAIRS_AT_OR_ABOVE_THE_CURRENT_SCO', 'لا توجد أزواج حسابات عند أو فوق درجة الخطورة الحالية.');
tz_def('ADM_RISK', 'الخطورة');
tz_def('ADM_ACCOUNT_A', 'الحساب أ');
tz_def('ADM_ACCOUNT_B', 'الحساب ب');
tz_def('ADM_WHY', 'السبب');
tz_def('ADM_LOGIN_LOG', 'سجل الدخول');
tz_def('ADM_FOCUS', 'تركيز');
tz_def('ADM_RESET_2', 'إعادة تعيين');
tz_def('ADM_DASHBOARD', 'لوحة التحكم');
tz_def('ADM_PER_PLAYER', 'لكل لاعب');
tz_def('ADM_7_DAY_RESOURCE_BALANCE', 'رصيد الموارد لـ7 أيام');
tz_def('ADM_HOURS_OF_PROD_ALLOWED', 'ساعات الإنتاج المسموحة');
tz_def('ADM_SHOW_2', 'عرض');
tz_def('ADM_ALL_WITH_ACTIVITY', 'الكل مع النشاط');
tz_def('ADM_OVER_LIMIT_ONLY', 'تجاوز الحد فقط');
tz_def('ADM_WW_ARTEFACT_OVERRIDDEN', 'عجائب الدنيا / قطعة أثرية / تجاوز يدوي');
tz_def('ADM_REFRESH_2', 'تحديث');
tz_def('ADM_NO_INTER_PLAYER_TRANSFERS_RECORDED_IN_THIS_W', 'لا توجد تحويلات بين اللاعبين مسجّلة في هذه النافذة بعد.');
tz_def('ADM_VILLAGES_POP', 'القرى / السكان');
tz_def('ADM_PROD_H', 'الإنتاج/ساعة');
tz_def('ADM_RECEIVED_7D', 'المستلَم (7 أيام)');
tz_def('ADM_LIMIT', 'الحد');
tz_def('ADM_USAGE', 'الاستخدام');
tz_def('ADM_OVERRIDE', 'تجاوز يدوي');
tz_def('ADM_AUTO', 'تلقائي');
tz_def('ADM_CUSTOM_CAP', 'حد مخصص');
tz_def('ADM_SAVE_3', 'حفظ');
tz_def('ADM_REGISTRATION', 'التسجيل');
tz_def('ADM_BLOCKLIST', 'قائمة الحظر');
tz_def('ADM_BLOCK_NEW_REGISTRATIONS_BY_A_SPECIFIC', 'حظر التسجيلات الجديدة عن طريق');
tz_def('ADM_USERNAME_3', 'اسم مستخدم');
tz_def('ADM_A_SPECIFIC', '، محدد');
tz_def('ADM_OR_A_WHOLE', '، أو');
tz_def('ADM_E_MAIL_DOMAIN', 'نطاق بريد إلكتروني كامل');
tz_def('ADM_ADD_A_BLOCK', 'إضافة حظر');
tz_def('ADM_E_MAIL_ADDRESS', 'البريد الإلكتروني');
tz_def('ADM_E_MAIL_DOMAIN_2', 'نطاق البريد الإلكتروني');
tz_def('ADM_VALUE', 'القيمة');
tz_def('ADM_ADD_BLOCK', 'إضافة الحظر');
tz_def('ADM_DOMAIN_ENTER_JUST_THE_DOMAIN', 'النطاق: أدخل النطاق فقط (');
tz_def('ADM_NO_REGISTRATION_BLOCKS_YET', 'لا توجد حظورات تسجيل بعد.');
tz_def('ADM_ADDED', 'تمت الإضافة');
tz_def('ADM_REASON_2', 'السبب');
tz_def('ADM_REMOVE_2', 'إزالة');
tz_def('ADM_WORLD_MAP', 'خريطة العالم');
tz_def('ADM_HEATMAP', 'خريطة الكثافة');
tz_def('ADM_GRID_RESOLUTION', 'دقة الشبكة');
tz_def('ADM_INACTIVE_AFTER_DAYS', 'غير نشط بعد (أيام)');
tz_def('ADM_REBUILD', 'إعادة بناء');
tz_def('ADM_VILLAGE_DENSITY', 'كثافة القرى');
tz_def('ADM_TRIBE_DENSITY', 'كثافة القبائل');
tz_def('ADM_INACTIVITY', 'الخمول');
tz_def('ADM_ATTACKS', 'الهجمات');
tz_def('ADM_SUMMARY', 'الملخص');
tz_def('ADM_PLAYER_VILLAGES', 'قرى اللاعبين');
tz_def('ADM_INACTIVE_VILLAGES', 'القرى الخاملة');
tz_def('ADM_ATTACKS_IN_FLIGHT', 'الهجمات الجارية');
tz_def('ADM_LEGEND', 'وسيلة الإيضاح');
tz_def('ADM_TRIBE_TOTALS', 'إجماليات القبائل');
tz_def('ADM_NO_PLAYER_VILLAGES_FOUND', 'لم يتم العثور على قرى لاعبين.');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// لوحة الأدمن - قوالب (الدفعة 8 والأخيرة: باقي الـ26 قالبًا)
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ADM_404_FILE_NOT_FOUND', '404 - الملف غير موجود');
tz_def('ADM_WE_LOOKED_404_TIMES_ALREADY_BUT_CAN_T_FIND_A', 'بحثنا 404 مرة بالفعل ولم نجد شيئًا، ولا حتى علامة X تدل على المكان.');
tz_def('ADM_THIS_SYSTEM_IS_NOT_COMPLETE_YET_SO_THE_PAGE', 'هذا النظام لم يكتمل بعد. لذا فالصفحة على الأرجح غير موجودة.');
tz_def('ADM_NOT_FOUND', 'غير موجود');
tz_def('ADM_NEW_MESSAGE', 'رسالة جديدة');
tz_def('ADM_TO', 'إلى');
tz_def('ADM_RECIPIENT', 'المستلم');
tz_def('ADM_SEND_MESSAGE', 'إرسال رسالة');
tz_def('ADM_PLAYER_NOT_FOUND', 'اللاعب غير موجود.');
tz_def('ADM_GO_BACK', 'رجوع');
tz_def('ADM_MESSAGE_FROM_ADMIN', 'رسالة من الأدمن');
tz_def('ADM_WRITE_YOUR_MESSAGE_HERE', 'اكتب رسالتك هنا...');
tz_def('ADM_TROOP', 'الوحدة');
tz_def('ADM_ARMOURY', 'الترسانة');
tz_def('ADM_BLACKSMITH', 'الحدادة');
tz_def('ADM_BACK_3', 'رجوع');
tz_def('ADM_NOW', 'الآن:');
tz_def('ADM_CREATE_USERS', '👤 إنشاء مستخدمين');
tz_def('ADM_SUBMITTING_THIS_FORM_WILL_CREATE_NEW_USERS_A', 'إرسال هذا النموذج سينشئ مستخدمين و/أو قرى جدد على السيرفر!');
tz_def('ADM_MANY_ACCOUNTS_1_VILLAGE', 'حسابات متعددة (قرية واحدة)');
tz_def('ADM_1_ACCOUNT_MANY_VILLAGES', 'حساب واحد (قرى متعددة)');
tz_def('ADM_BASE_NAME', 'الاسم الأساسي');
tz_def('ADM_4_20_CHARACTERS_EX_FARM_5_FARM1_FARM5_SINGLE', '4-20 حرفًا. مثال: Farm | 5 → Farm1..Farm5. مثال حساب واحد: FarmLord | 5 قرى.');
tz_def('ADM_WARNING_2', 'تحذير:');
tz_def('ADM_LARGE_VALUES_MAY_FREEZE_THE_SERVER', 'القيم الكبيرة قد تجمّد السيرفر!');
tz_def('ADM_HOW_MANY_ACCOUNTS', 'عدد الحسابات');
tz_def('ADM_HOW_MANY_VILLAGES', 'عدد القرى');
tz_def('ADM_1_200_SINGLE_ONLY', '1 - 200 (حساب واحد فقط)');
tz_def('ADM_ENABLE_BEGINNER_PROTECTION', 'تفعيل حماية المبتدئين');
tz_def('ADM_CREATE_2', '+ إنشاء');
tz_def('ADM_ADD_VILLAGE', 'إضافة قرية');
tz_def('ADM_COORDINATES_X_Y', 'الإحداثيات (X|Y)');
tz_def('ADM_ADD_VILLAGE_2', 'إضافة قرية');
tz_def('ADM_TO_ENSURE_THAT_YOU_WON_T_GET_BANNED_AGAIN_IN', 'لضمان عدم تعرضك للحظر مرة أخرى مستقبلًا، يجب قراءة القواعد بعناية:');
tz_def('ADM_GAME_RULES', '» قواعد اللعبة');
tz_def('ADM_TO_CONTINUE_PLAYING_CONTACT_THE_MULTIHUNTER', 'لمواصلة اللعب، تواصل مع مطارد الحسابات المتعددة وسوّ الأمر معه/معها');
tz_def('ADM_WRITE_MESSAGE', '» كتابة رسالة');
tz_def('ADM_HEED_THE_FOLLOWING_ADVICE_WHEN_WRITING_YOUR', 'انتبه للنصائح التالية عند كتابة رسالتك:');
tz_def('ADM_THERE_IS_ALWAYS_A_REASON_FOR_A_BAN', '● دائمًا يوجد سبب للحظر.');
tz_def('ADM_TRY_TO_THINK_ABOUT_POSSIBLE_REASONS_FOR_THIS', 'حاول التفكير في الأسباب المحتملة لهذا الحظر');
tz_def('ADM_AND_PUT_THINGS_STRAIGHT_WITH_THE_MULTIHUNTER', 'وسوّ الأمر مع مطارد الحسابات المتعددة.');
tz_def('ADM_MULTIHUNTERS_CAN_REVIEW_ENORMOUS_AMOUNTS_OF', '● يمكن لمطاردي الحسابات المتعددة مراجعة كميات هائلة من المعلومات حول الحسابات.');
tz_def('ADM_STICK_TO_THE_TRUTH', 'التزم بالحقيقة');
tz_def('ADM_AND_DO_NOT_MAKE_EXCUSES_TO_JUSTIFY_YOUR_VIOL', 'ولا تختلق أعذارًا لتبرير مخالفتك للقواعد.');
tz_def('ADM_BE_COOPERATIVE_AND_INSIGHTFUL_THIS_MIGHT_RED', '● كن متعاونًا وواعيًا، فقد يقلل ذلك من العقوبة.');
tz_def('ADM_CALM_AND_POLITE', 'هادئًا ومهذبًا');
tz_def('ADM_WHILE_TALKING_TO_THE_MULTIHUNTER_AND_TELLING', 'أثناء التحدث مع مطارد الحسابات المتعددة وشرح وجهة نظرك له/لها.');
tz_def('ADM_INCLUDES_RESOURCES_MAIN_BUILDING_RALLY_POINT', 'يشمل الموارد، المبنى الرئيسي، نقطة التجمع، المخزن، المخزن الغلال، السور، السوق، مقر الإقامة، الجنود (لترقية البطل)، وخبيئة واحدة.');
tz_def('ADM_IF_THE_MULTIHUNTER_DOES_NOT_ANSWER_IMMEDIATE', '● إذا لم يرد مطارد الحسابات المتعددة فورًا، فهو على الأرجح غير متصل. لن يُحل الأمر بشكل أسرع بإرسال عدة رسائل، خاصة إذا لم يقرأ الرسالة الأولى بعد.');
tz_def('ADM_IF_YOU_HAVE_REALLY_BEEN_BANNED_UNJUSTLY_TRY', '● إذا كنت قد تعرضت للحظر ظلمًا فعلًا، حاول أن تبقى');
tz_def('ADM_RESOURCE', 'المورد');
tz_def('ADM_AMOUNT', 'الكمية');
tz_def('ADM_MAXIMUM_CAPACITY', 'السعة القصوى');
tz_def('ADM_BACK_TO_VILLAGE_2', '← رجوع للقرية');
tz_def('ADM_ADMIN_CONTROL', 'التحكم الإداري');
tz_def('ADM_SERVER_ADMINISTRATION', 'إدارة السيرفر');
tz_def('ADM_SECURE_ACCESS_TO_NOVATERRA_PANEL', 'دخول آمن للوحة Novaterra');
tz_def('ADM_PASSWORD', 'كلمة المرور');
tz_def('ADM_ACCESS_PANEL', 'دخول اللوحة');
tz_def('ADM_ENTER_ADMIN_USERNAME', 'أدخل اسم مستخدم الأدمن');
tz_def('ADM_ROMANS', 'الرومان');
tz_def('ADM_TEUTONS', 'التيوتون');
tz_def('ADM_GAULS', 'الغال');
tz_def('ADM_RESET_GOLD_FOR_ALL_PLAYERS', 'إعادة تعيين الذهب لكل اللاعبين');
tz_def('ADM_RESET_ALL_PLAYERS_GOLD', 'إعادة تعيين ذهب كل اللاعبين');
tz_def('ADM_THIS_ACTION_WILL_SET_THE_GOLD_TO', 'هذا الإجراء سيضبط الذهب على');
tz_def('ADM_FOR_ALL_PLAYERS_FROM_THE_SERVER', 'لكل اللاعبين في السيرفر.');
tz_def('ADM_WARNING_THE_ACTION_IS_IRREVERSIBLE_MAKE_SURE', '⚠️ تحذير: هذا الإجراء لا يمكن التراجع عنه! تأكد من وجود نسخة احتياطية أولًا.');
tz_def('ADM_RESET_GOLD_NOW', 'إعادة تعيين الذهب الآن');
tz_def('ADM_ALL_PLAYERS_GOLD_HAS_BEEN_RESET', '✓ تم إعادة تعيين ذهب كل اللاعبين!');
tz_def('ADM_RESET_ALL_PLAYERS_PLUS', 'إعادة تعيين بلس لكل اللاعبين');
tz_def('ADM_THIS_ACTION_WILL_DISABLE_NOVATERRA_PLUS_FOR_AL', 'هذا الإجراء سيعطّل Novaterra Plus لكل اللاعبين.');
tz_def('ADM_PLUS_IT_WILL_BE_SET_TO_0_DAYS_FOR_EVERYONE', '⚠️ سيتم ضبط بلس على 0 أيام للجميع.');
tz_def('ADM_RESET_PLUS_NOW', 'إعادة تعيين بلس الآن');
tz_def('ADM_PLUS_ALL_THE_PLAYERS_HAVE_BEEN_RESET', '✓ تم إعادة تعيين بلس لكل اللاعبين!');
tz_def('ADM_RESET_ALL_RESOURCE_BONUSES', 'إعادة تعيين كل مكافآت الموارد');
tz_def('ADM_THIS_ACTION_WILL_DISABLE_THE_25_BONUS_FOR_AL', 'هذا الإجراء سيعطّل مكافأة الـ25% لكل الموارد عند كل اللاعبين.');
tz_def('ADM_ALL_RESOURCE_BONUSES_WILL_BE_SET_TO_0_DAYS', '⚠️ سيتم ضبط كل مكافآت الموارد على 0 أيام.');
tz_def('ADM_RESET_RESOURCE_BONUS', 'إعادة تعيين مكافأة الموارد');
tz_def('ADM_RESOURCE_BONUSES_HAVE_BEEN_RESET_FOR_ALL_PLA', '✓ تم إعادة تعيين مكافآت الموارد لكل اللاعبين!');
tz_def('ADM_IGM_REPORTS_LOOKUP', 'بحث في الرسائل الداخلية / التقارير');
tz_def('ADM_IGM_BY_ID', 'رسالة داخلية بالمعرّف');
tz_def('ADM_GO', 'انتقال');
tz_def('ADM_REPORT_BY_ID', 'تقرير بالمعرّف');
tz_def('ADM_ENTER_MESSAGE_ID', 'أدخل معرّف الرسالة...');
tz_def('ADM_ENTER_REPORT_ID', 'أدخل معرّف التقرير...');
tz_def('ADM_SITTER_1_2', 'الجالس 1');
tz_def('ADM_SITTER_2_2', 'الجالس 2');
tz_def('ADM_CULTURE_POINTS_2', 'نقاط الثقافة');
tz_def('ADM_ATTACK_POINTS_THIS_WEEK', 'نقاط الهجوم (هذا الأسبوع)');
tz_def('ADM_DEFENCE_POINTS_THIS_WEEK', 'نقاط الدفاع (هذا الأسبوع)');
tz_def('ADM_RESOURCES_RAIDED_THIS_WEEK', 'الموارد المنهوبة (هذا الأسبوع)');
tz_def('ADM_TOTAL_ATTACK_POINTS', 'إجمالي نقاط الهجوم');
tz_def('ADM_TOTAL_DEFENCE_POINTS', 'إجمالي نقاط الدفاع');
tz_def('ADM_EDIT_PLAYER_ADDITIONAL_INFO', 'تعديل معلومات اللاعب الإضافية');
tz_def('ADM_THE_ACCOUNT_WILL_BE_DELETED_IN', 'سيتم حذف الحساب خلال');
tz_def('ADM_CANCEL_3', '✖ إلغاء');
tz_def('ADM_CANCEL_DELETION', 'إلغاء الحذف');
tz_def('ADM_DESCRIPTION', 'الوصف');
tz_def('ADM_AGE', 'العمر');
tz_def('ADM_GENDER', 'الجنس');
tz_def('ADM_LOCATION_2', 'الموقع');
tz_def('ADM_LAST_IP', 'آخر IP');
tz_def('ADM_LANGUAGE', 'اللغة');
tz_def('ADM_BAN_USER_2', '&raquo; حظر المستخدم');
tz_def('ADM_SEND_MESSAGE_2', '&raquo; إرسال رسالة');
tz_def('ADM_EDIT_PLUS_RES_BONUS', '&raquo; تعديل بلس ومكافأة الموارد');
tz_def('ADM_EDIT_SITTERS_2', '&raquo; تعديل الجالسين');
tz_def('ADM_EDIT_PROTECTION', '&raquo; تعديل الحماية');
tz_def('ADM_EDIT_PASSWORD', '&raquo; تعديل كلمة المرور');
tz_def('ADM_EDIT_OVERALL_OFF_DEF', '&raquo; تعديل إجمالي الهجوم والدفاع');
tz_def('ADM_EDIT_WEEKLY_OFF_DEF_RAID', '&raquo; تعديل الهجوم والدفاع والنهب الأسبوعي');
tz_def('ADM_USER_LOGIN_LOG', '&raquo; سجل دخول المستخدم');
tz_def('ADM_USER_ILLEGAL_LOG', '&raquo; سجل مخالفات المستخدم');
tz_def('ADM_RECOUNT_POPULATION', 'إعادة احتساب السكان');
tz_def('ADM_EMAIL_2', 'البريد الإلكتروني');
tz_def('ADM_IP', 'IP');
tz_def('ADM_TRIBE_3', 'القبيلة:');
tz_def('ADM_VILLAGES_3', 'القرى:');
tz_def('ADM_POP_3', 'السكان:');
tz_def('ADM_COORDS_2', 'الإحداثيات:');
tz_def('ADM_ILLEGALS_LOG', '🚨 سجل المخالفات:');
tz_def('ADM_DETECTED_OFFENCES', 'المخالفات المكتشفة');
tz_def('ADM_BACK_TO_PLAYER', '← رجوع للاعب');
tz_def('ADM_LOGIN_LOG_2', '🔐 سجل الدخول:');
tz_def('ADM_RECENT_LOGIN_ATTEMPTS', 'محاولات الدخول الأخيرة');
tz_def('ADM_LOG_ID', 'معرّف السجل');
tz_def('ADM_IP_ADDRESS', 'عنوان IP');
tz_def('ADM_INFO', 'معلومات');
tz_def('ADM_BUILD_LOG', '— سجل البناء');
tz_def('ADM_LATEST_200_ACTION', 'آخر 200 إجراء');
tz_def('ADM_NO_ACTION_RECORDED_YET', 'لا يوجد إجراء مسجّل بعد.');
tz_def('ADM_POPULATION_2', 'السكان');
tz_def('ADM_COORDINATES', 'الإحداثيات');
tz_def('ADM_TROOPS_2', 'الجنود');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// لوحة الأدمن - نصوص طويلة ومتعددة الأسطر (التنظيف الأخير)
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ADM_E_MAIL_ADDRESS_2', 'البريد الإلكتروني');
tz_def('ADM_E_G_AN_OBSCENE_USERNAME_OR_THE_ENTIRE', '(مثال: اسم مستخدم مسيء، أو نطاق بريد إلكتروني كامل');
tz_def('ADM_DOMAIN_MATCHING_IS_EXACT_AND_CASE_INSENSITIV', 'المطابقة دقيقة وغير حساسة لحالة الأحرف. الحسابات الحالية لا تتأثر — فقط التسجيلات الجديدة.');
tz_def('ADM_WITH_OR_WITHOUT_THE_LEADING_E_MAIL_THE_FULL', ')، مع أو بدون علامة "@" في البداية. البريد الإلكتروني: العنوان الكامل. اسم المستخدم: الاسم بالضبط.');
tz_def('ADM_SILVER_2', 'فضة &nbsp;|&nbsp;');
tz_def('ADM_OF_EACH', 'من كل &nbsp;|&nbsp;');
tz_def('ADM_HOURLY_RESOURCES_PRODUCED_BY_EACH_POINT_THE', 'الموارد التي تُنتَج كل ساعة من كل نقطة استثمرها البطل في خاصية الموارد، في القرية التي يوجد بها البطل. يختار اللاعبون بحرية بين توزيعها على الموارد الأربعة أو تركيزها على مورد واحد.');
tz_def('ADM_HOURLY_RESOURCES_PER_POINT_INVESTED_IN_THE_H', 'الموارد بالساعة لكل نقطة مستثمرة في خاصية موارد البطل. القيمة الأولى تُطبَّق عند توزيع اللاعب للمكافأة على الموارد الأربعة، والثانية عند تركيزها على مورد واحد. تتناسب كلتاهما مع سرعة السيرفر.');
tz_def('ADM_HIT_POINTS_THE_HERO_RECOVERS_PER_DAY_ON_TOP', 'نقاط الصحة التي يستعيدها البطل يوميًا بالإضافة إلى النقاط المستثمرة في خاصية التجدد وأي عناصر مجهّزة. بدونها، لن يشفى البطل الذي لا يملك نقاط تجدد أبدًا وسيموت في النهاية أثناء المغامرات. تتناسب مع سرعة السيرفر. القيمة 0 تعطّلها.');
tz_def('ADM_HOW_LONG_ONE_CRON_PHP_INVOCATION_KEEPS_WORKI', 'المدة التي يستمر فيها استدعاء واحد لـ cron.php في العمل. معظم الاستضافات المشتركة تسمح بتشغيل cron كل 5 دقائق فقط، بينما تتوقع الأتمتة تشغيله كل دقيقة تقريبًا — لذا يقوم الاستدعاء الواحد بتشغيل عدة دورات متتالية. استخدم 300 لجدولة "*/5". اضبط القيمة على 0 فقط إذا كانت استضافتك تسمح بتشغيل cron كل دقيقة.');
tz_def('ADM_ENABLE_DISABLE_IF_A_PLAYER_LEAVES_AT_LEAST_O', 'تفعيل (تعطيل) إذا ترك لاعب رسالة واحدة على الأقل في موضوع بالمنتدى، سيتلقى رسائل داخل اللعبة عند ظهور رسائل جديدة في نفس الموضوع (أي أنه "مشترك" فيه تقنيًا)');
tz_def('ADM_ENABLE_DISABLE_THE_SERVER_MILESTONES_WIDGET', 'تفعيل (تعطيل) أداة "إنجازات السيرفر" (أول لاعب يؤسس قرية ثانية، يصل لـ1000 نسمة، يستولي على قطعة أثرية، يحتل عجيبة دنيا، يحتل مخطط بناء عجيبة دنيا، يؤسس تحالفًا، أو يحتل قرية من لاعب آخر) والتي تظهر أعلى صفحة الإحصائيات » عام');
tz_def('ADM_CREATE_PROMO_VOUCHER_CODES_PLAYERS_REDEEM_FO', 'إنشاء أكواد عروض / قسائم يستبدلها اللاعبون بذهب في صفحة بلس. حدد كمية ذهب ثابتة، وحد استخدام إجمالي اختياري، وهل يمكن لكل لاعب استخدامه مرة واحدة، وتاريخ انتهاء اختياري. لمكافأة لمرة واحدة لصائد أخطاء، أنشئ كودًا بحد استخدام أقصى 1 — أو استخدم');
tz_def('ADM_DENSITY_OVERLAYS_ON_THE_WORLD_GRID_TO_HELP_W', 'طبقات كثافة على شبكة العالم للمساعدة في التوازن عند البدء: أماكن تجمع القرى، القبائل المسيطرة على منطقة، أماكن تواجد اللاعبين الخاملين (مناطق ميتة / أماكن جيدة للبدء)، وأماكن سقوط الهجمات حاليًا. الشمال (+y) للأعلى.');
tz_def('ADM_HEURISTIC_CORRELATION_OF_ACCOUNT_PAIRS_BY_SH', 'ربط استدلالي لأزواج الحسابات عبر عنوان IP المشترك، الشبكة الفرعية، بصمة الجهاز/المتصفح (User-Agent)، تداخل أوقات الدخول، وتدفق تحويل الموارد. هذه');
tz_def('ADM_USE_IT_TO_PRIORITISE_WHICH_PAIRS_A_HUMAN_SHO', '&mdash; استخدمها لتحديد أولوية الأزواج التي يجب على مشرف بشري التحقق منها. لا يُحظر أي شيء تلقائيًا إلا إذا قام الأدمن بتفعيل الحظر التلقائي أدناه.');
tz_def('ADM_ROW_CAP_REACHED_WHILE_SCANNING_LOGIN_HISTORY', 'تم الوصول للحد الأقصى للصفوف أثناء فحص سجل الدخول (النافذة الزمنية كبيرة / السيرفر مزدحم جدًا). النتائج لا تزال صالحة لكن قد تكون غير مكتملة — ضيّق النافذة الزمنية لتغطية كاملة.');
tz_def('ADM_RECEIVED_FROM_OTHER_PLAYERS_VERSUS_AN_AUTOMA', '(مستلَم من لاعبين آخرين) مقابل حد تلقائي مشتق من إنتاج اللاعب بالساعة (');
tz_def('ADM_OF_PRODUCTION_PER_WINDOW_WW_VILLAGES_AND_ART', 'من الإنتاج لكل نافذة زمنية). قرى عجائب الدنيا وقرى القطع الأثرية تُصنَّف كاستثناءات إمداد. اضبط تجاوزًا يدويًا لاستثناء لاعب (عجيبة دنيا / موثوق) أو لمنحه حدًا مخصصًا. لا يُحظر شيء تلقائيًا — هذه أداة رؤية وتحكم لمشرف بشري.');
tz_def('ADM_THE_7_DAY_BALANCE_FILLS_IN_AS_MERCHANT_DELIV', 'يمتلئ رصيد الـ7 أيام تدريجيًا مع معالجة توصيلات التجار بين اللاعبين المختلفين بعد نشر هذه الميزة.');
tz_def('ADM_EDIT_THE_REWARD_EACH_QUEST_GRANTS_WOOD_CLAY', 'تعديل المكافأة التي تمنحها كل مهمة (خشب / طين / حديد / محصول / ذهب / أيام بلس) ومستوى المتطلب (مثل مستوى المبنى الرئيسي لمهام البناء). القيم مأخوذة من الإعدادات الافتراضية المرفقة، فلا يتغير شيء حتى تعدّلها. نسختا المهام لهما مهام ومكافآت مختلفة — اختر النسخة التي يستخدمها سيرفرك (اللاعبون على');
tz_def('ADM_IN_THE_QUEST_TEMPLATES_QUESTS_MARKED', 'في قوالب المهام. المهام المعلّمة بـ');
tz_def('ADM_KEEP_THEIR_ORIGINAL_HARDCODED_LOGIC_CONDITIO', 'تحتفظ بمنطقها الأصلي الثابت (مكافآت شرطية، مطالبات إنجازات ذرية، آليات خاصة) ولا تتأثر بالتعديلات هنا. أرقام المكافآت الظاهرة داخل نص كل مهمة على الشاشة هي نصوص قوالب منفصلة — التعديلات هنا تغيّر ما يُمنح فعليًا؛ حدّث نصوص لغة المهام إذا أردت أن تطابق المعاينة.');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// مكافآت التحالف (نقل T4)
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ALLYBONUS_TAB', 'المكافآت');
tz_def('ALLYBONUS_RECRUITMENT', 'التجنيد');
tz_def('ALLYBONUS_PHILOSOPHY', 'الفلسفة');
tz_def('ALLYBONUS_METALLURGY', 'المعادن');
tz_def('ALLYBONUS_COMMERCE', 'التجارة');
tz_def('ALLYBONUS_LEVEL', 'المستوى');
tz_def('ALLYBONUS_NEXT', 'التالي');
tz_def('ALLYBONUS_MAXED', 'مفتوح بالكامل');
tz_def('ALLYBONUS_UNLOCKING', 'جاري فتح المستوى');
tz_def('ALLYBONUS_DONATE', 'تبرّع');
tz_def('ALLYBONUS_TRIPLE', 'مضاعفة هذا التبرع ثلاث مرات');
tz_def('ALLYBONUS_DAILY_LEFT', 'المتبقي من حد تبرعك اليومي');
tz_def('ALLYBONUS_CONTRIBUTORS', 'المساهمات');
tz_def('ALLYBONUS_MEMBER', 'العضو');
tz_def('ALLYBONUS_TOTAL', 'إجمالي التبرع');
tz_def('ALLYBONUS_MSG_OK', 'تم التبرع بالموارد.');
tz_def('ALLYBONUS_MSG_UPGRADING', 'يتم حاليًا فتح هذه المكافأة؛ التبرعات متوقفة مؤقتًا.');
tz_def('ALLYBONUS_MSG_LIMIT', 'هذا سيتجاوز حد تبرعك اليومي.');
tz_def('ALLYBONUS_MSG_RESOURCES', 'لا توجد موارد كافية في هذه القرية.');
tz_def('ALLYBONUS_MSG_NOGOLD', 'لا يوجد ذهب كافٍ لمضاعفة هذا التبرع ثلاث مرات.');
tz_def('ALLYBONUS_MSG_NOALLY', 'أنت لست في تحالف.');
tz_def('ALLYBONUS_MSG_INVALID', 'تبرع غير صالح.');
tz_def('ALLYBONUS_HINT', 'نوع المورد لا يهم، فقط الكمية الإجمالية. أثناء فتح مستوى معين، لا يمكن لتلك المكافأة استقبال تبرعات.');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// لوحة الأدمن - حزمة الرسوميات ومكافآت التحالف
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ADM_SERVER_GRAPHIC_PACK', 'حزمة رسوميات السيرفر');
tz_def('ADM_SERVER_GRAPHIC_PACK_TIP', 'حزمة الرسوميات التي يراها كل لاعب افتراضيًا، تُقرأ من مجلد gpack/.');
tz_def('ADM_PLAYER_GRAPHIC_PACKS', 'حزم رسوميات اللاعبين');
tz_def('ADM_PLAYER_GRAPHIC_PACKS_TIP', 'عند التفعيل، يمكن للاعبين تحديد حزمة رسوميات خاصة بهم من الملف الشخصي (الملف الشخصي ← الرسوميات). عند التعطيل، يرى الجميع حزمة السيرفر.');
tz_def('ADM_ALLIANCE_BONUSES', 'مكافآت التحالف');
tz_def('ADM_ALLIANCE_BONUSES_TIP', 'مكافآت تحالف T4: يتبرع الأعضاء بالموارد لفتح التجنيد، الفلسفة، المعادن، والتجارة.');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// مكافآت التحالف - وصف كل مكافأة
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ALLYBONUS_RECRUITMENT_DESC', 'يسرّع تدريب الجنود في كل مبنى منتج للجنود. يتضاعف مع خوذات البطل.');
tz_def('ALLYBONUS_PHILOSOPHY_DESC', 'يزيد إنتاج نقاط الثقافة من المباني وخوذات البطل. احتفالات دار المدينة لا تتأثر.');
tz_def('ALLYBONUS_METALLURGY_DESC', 'يزيد قوة الهجوم والدفاع لجنودك، إضافة إلى ترقيات الحدادة.');
tz_def('ALLYBONUS_COMMERCE_DESC', 'يزيد ما يمكن أن يحمله كل تاجر. يتضاعف مع مكتب التجارة.');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// الإحصائيات الرسومية (Novaterra Plus)
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('PLUSSTATS_TITLE', 'إحصائيات رسومية');
tz_def('PLUSSTATS_INTRO', 'تطور حسابك عبر الزمن.');
tz_def('PLUSSTATS_RANK', 'الترتيب');
tz_def('PLUSSTATS_POP', 'السكان');
tz_def('PLUSSTATS_ARMY', 'قوة الجيش (استهلاك المحصول بالساعة)');
tz_def('PLUSSTATS_VILLAGES', 'القرى');
tz_def('PLUSSTATS_TROOPS', 'الجنود');
tz_def('PLUSSTATS_UPKEEP', 'استهلاك المحصول بالساعة');
tz_def('PLUSSTATS_METRIC', 'المقياس');
tz_def('PLUSSTATS_NOW', 'الآن');
tz_def('PLUSSTATS_CHANGE', 'التغيّر');
tz_def('PLUSSTATS_NODATA', 'لا توجد بيانات كافية بعد');
tz_def('PLUSSTATS_WAIT', 'يتم حاليًا تسجيل حسابك. تظهر الرسوم البيانية بمجرد وجود لقطتين على الأقل.');
tz_def('PLUSSTATS_NEEDPLUS', 'الإحصائيات الرسومية ميزة حصرية لـ Novaterra Plus. فعّل بلس لترى تطور حسابك.');
tz_def('PLUSSTATS_DISABLED', 'الإحصائيات الرسومية غير مفعّلة على هذا السيرفر.');
tz_def('PLUSSTATS_FOOT', 'تُقاس قوة الجيش باستهلاك المحصول، وهو مقياس الوزن الخاص باللعبة: يحسب الوحدة القوية بقيمة أكبر من الضعيفة. الجنود المرابطون عند لاعبين آخرين لا يزالون محسوبين لك.');
tz_def('ADM_PLUS_STATISTICS', 'إحصائيات بلس الرسومية');
tz_def('ADM_PLUS_STATISTICS_TIP', 'يسجّل الترتيب، السكان، القرى، والجيش لكل لاعب، حتى يرى لاعبو بلس تطور حسابهم. التبويب يظهر فقط مع حساب بلس مفعّل.');
tz_def('ADM_PLUS_STATISTICS_INTERVAL', 'الفاصل الزمني للقطات الإحصائيات (ساعات)');
tz_def('ADM_PLUS_STATISTICS_INTERVAL_TIP', 'عدد المرات التي تُؤخذ فيها لقطة. على السيرفر السريع يمثل اليوم قدرًا كبيرًا من اللعب، لذا تعطي بضع ساعات منحنى مقروء.');
tz_def('ADM_PLUS_STATISTICS_KEEP', 'مدة الاحتفاظ بسجل الإحصائيات (أيام)');
tz_def('ADM_PLUS_STATISTICS_KEEP_TIP', 'القيمة 0 تحتفظ بكل شيء، حتى يرى اللاعبون السجل الكامل لحسابهم منذ بدء التسجيل.');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// مكافآت التحالف - التوفر للأعضاء الجدد
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ALLYBONUS_YOURLEVEL', 'مستواك');
tz_def('ALLYBONUS_UNLOCKS_IN', 'الفتح التالي خلال');
tz_def('ALLYBONUS_NEWMEMBER', 'يحصل الأعضاء الجدد على وصول للمستويات الأعلى تدريجيًا: المستوى 2 بعد 24 ساعة في التحالف، المستوى 3 بعد 48 ساعة، وهكذا.');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// التقارير - فلتر حسب نتيجة المعركة
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('TZ_RPT_ALL_RESULTS', 'كل النتائج');
tz_def('TZ_RPT_F_WON_NOLOSS', 'فوز بدون خسائر');
tz_def('TZ_RPT_F_WON_LOSS', 'فوز مع خسائر');
tz_def('TZ_RPT_F_LOST', 'خسارة');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// البطل - إجمالي التجدد (الأساس + العناصر)
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('TZ_HERO_REGEN_TOTAL', 'تجدد الصحة');
tz_def('TZ_HERO_REGEN_BASE', 'أساسي');
tz_def('TZ_HERO_REGEN_ITEMS', 'من العناصر');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// عجيبة الدنيا الخاصة بالتتار
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('TZ_WW_NATARS', 'التتار');
tz_def('CONF_SERV_NATARS_WW_START_DELAY', 'بداية عجيبة دنيا التتار');
tz_def('CONF_SERV_NATARS_WW_START_DELAY_TOOLTIP', 'عدد الأيام بعد ظهور مخططات البناء التي يبدأ فيها التتار ببناء عجيبة دنياهم الخاصة. يتناسب مع سرعة السيرفر. القيمة 0 تعطّله.');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// المحاكي - المكافآت الخاصة المضمّنة تلقائيًا
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('TZ_WS_APPLIED', 'مضمّن تلقائيًا في هذه المحاكاة:');
tz_def('TZ_WS_ITEM_STRENGTH', 'قوة المعدات');
tz_def('TZ_WS_ITEM_UNIT', 'مكافأة السلاح');
tz_def('TZ_WS_VS_NATARS', 'ضد التتار');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// رسالة النظام - معاينة
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ADM_PREVIEW', 'معاينة');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// قواعد التسجيل (إعدادات السيرفر)
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('CONF_SERV_USRNM_MIN', 'الحد الأدنى لطول اسم المستخدم');
tz_def('CONF_SERV_USRNM_MAX', 'الحد الأقصى لطول اسم المستخدم');
tz_def('CONF_SERV_PW_MIN', 'الحد الأدنى لطول كلمة المرور');
tz_def('CONF_SERV_USRNM_SPECIAL', 'السماح بـ . - _ في أسماء المستخدمين');
tz_def('CONF_SERV_USRNM_SPECIAL_TOOLTIP', 'عند التفعيل (True)، يمكن أن تحتوي أسماء المستخدمين على نقطة أو شرطة أو شرطة سفلية، ومسافات مفردة بين الكلمات. عند التعطيل (False)، يُقبَل فقط الحروف والأرقام.');
tz_def('CONF_SERV_REGRULES_TOOLTIP', 'القواعد التي تُفحَص عند تسجيل اللاعب. تغييرها لا يؤثر على الحسابات الحالية.');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// الإحصائيات - قسم التحالف
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('PLUSSTATS_ALLIANCE', 'تحالفك');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// البطل - عناوين الأعمدة في صفحة العناصر
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('HERO_COL_TYPE', 'النوع');
tz_def('HERO_COL_IMAGE', 'الصورة');
tz_def('HERO_COL_NAME', 'اسم العنصر');
tz_def('HERO_COL_TIER', 'الفئة (المكافأة)');
tz_def('HERO_COL_ACTION', 'الإجراء');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// عجيبة الدنيا - نمط لكل قبيلة
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ADM_WW_IMAGE', 'عجيبة الدنيا الخاصة بكل قبيلة');
tz_def('ADM_WW_IMAGE_TIP', 'كل قبيلة ترى عمل عجيبة الدنيا الفني الخاص بها، سواء على خريطة القرية أو صفحة البناء. القبائل التي لا تملك عملًا فنيًا على القرص تحتفظ بالصورة الأصلية بغض النظر عن هذا الإعداد.');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// عجيبة الدنيا - القرية لا يمكن أن تصبح العاصمة
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('TZ_WW_NO_CAPITAL', 'قرية عجيبة الدنيا لا يمكن أن تصبح عاصمتك.');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// مكافآت التحالف - التبرع بما يتجاوز متطلبات المستوى الأخير
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ALLYBONUS_MSG_OVERMAX', 'هذا هو المستوى الأخير: يمكنك التبرع بحد أقصى %s موارد إضافية.');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// اللاعبون المحميون من الهجمات
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('ADM_PROTECTED_PLAYERS', 'اللاعبون المحميون');
tz_def('ADM_PROTECTED_PLAYERS_TIP', 'أسماء لاعبين مفصولة بفواصل لا يمكن مهاجمتهم أو نهبهم من أي أحد. التعزيزات ما زالت مسموحة. اتركه فارغًا للتعطيل.');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// مكوّن البطل في الشريط العلوي
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('HERO_HEADER_AT_HOME',   'البطل في المنزل');
tz_def('HERO_HEADER_DEAD',      'البطل ميت');
tz_def('HERO_HEADER_TRAINING',  'البطل قيد التدريب');
tz_def('HERO_HEADER_NOHERO',    'لا يوجد بطل بعد');
tz_def('HERO_HEADER_ADVENTURE', 'البطل في مغامرة');
tz_def('HERO_HEADER_ATTACK',    'البطل مع الجيش');
tz_def('HERO_HEADER_REINFORCE', 'البطل يقوم بالتعزيز');
tz_def('HERO_HEADER_IN',        'في');
tz_def('HERO_HEADER_RETURN_ADV',  'البطل عائد من المغامرة');
tz_def('HERO_HEADER_RETURN_HOME', 'البطل عائد إلى المنزل');
tz_def('HERO_HEADER_PER_HOUR',    'في الساعة');
tz_def('TZ_HEALTH',             'الصحة');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// صلاحيات الجالس
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('SITTER_P_ATTACK', 'إرسال هجمات');
tz_def('SITTER_P_RAID',   'إرسال غارات');
tz_def('SITTER_P_REINF',  'إرسال تعزيزات');
tz_def('SITTER_P_RES',    'إرسال موارد للاعبين آخرين');
tz_def('SITTER_P_GOLD',   'إنفاق الذهب');
tz_def('SITTER_P_DENIED', 'صلاحيات الجالس لديك لا تسمح بهذا الإجراء.');
tz_def('SITTER_P_HINT',        'صلاحيات الجالس الجديد:');
tz_def('SITTER_P_NOT_SITTING', 'أنت لست جالسًا على أي حساب.');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// تخفيضات وقت التدريب (الثكنة / الإسطبل)
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('TRAIN_BONUS_ARTIFACT', 'مكافأة القطعة الأثرية');
tz_def('TRAIN_BONUS_HERO',     'مكافأة البطل');
tz_def('TRAIN_BONUS_ALLIANCE', 'مكافأة التحالف');
tz_def('TRAIN_BONUS_FINAL',    'وقت التدريب');

// انظر التعليق في en.php لنفس المفاتيح — كانت مستخدمة في القوالب بدون تعريف
tz_def('TZ_ACTIVE', 'نشط');
tz_def('TZ_HERO_LEVEL', 'مستوى البطل');
tz_def('TZ_POINTS', 'النقاط');

//////////////////////////////////////////////////////////////////////////////////////////////////////
// صفحة التسجيل (anmelden.php) — معالج التسجيل بثلاث خطوات
//////////////////////////////////////////////////////////////////////////////////////////////////////
tz_def('REG_STEP1_TITLE', 'اختر قبيلتك');
tz_def('REG_STEP2_TITLE', 'اختر موقع البداية');
tz_def('REG_STEP3_TITLE', 'أكّد اختيارك');

tz_def('REG_TRIBE_INTRO', 'الإمبراطوريات العظيمة تبدأ بقرارات مهمة! هل أنت مهاجم يعشق التنافس؟ أم أن وقتك محدود؟ هل أنت لاعب جماعي يستمتع ببناء اقتصاد مزدهر لتشكيل مستقبله؟');
tz_def('REG_POS_INTRO', 'أين تريد أن تبدأ بناء إمبراطوريتك؟ استخدم المنطقة "الموصى بها" للحصول على أفضل موقع، أو اختر المنطقة القريبة من أصدقائك وكوّنوا فريقًا!');
tz_def('REG_RECAP_INTRO', 'أكّد اختياراتك، اختر اسم بطلك، وابدأ مغامرتك');

tz_def('REG_AVATAR', 'أدخل اسم بطلك:');
tz_def('REG_AVATAR_HINT', 'هذا هو اسم بطلك في عالم اللعبة.');

tz_def('REG_CONFIRM', 'تأكيد');
tz_def('REG_BACK', 'رجوع');
tz_def('REG_CHANGE', 'تغيير');

// الرومان
tz_def('TRIBE1_L1', 'وقت لعب متوسط');
tz_def('TRIBE1_L2', 'أسرع قبيلة في تطوير القرى');
tz_def('TRIBE1_L3', 'وحدات قوية جدًا لكن مكلفة');
tz_def('TRIBE1_L4', 'صعبة على اللاعبين الجدد');

// التوتون
tz_def('TRIBE2_L1', 'يتطلب وقتًا طويلًا');
tz_def('TRIBE2_L2', 'ممتازون في النهب في بداية اللعبة');
tz_def('TRIBE2_L3', 'مشاة أقوياء وغير مكلفين');
tz_def('TRIBE2_L4', 'مناسبة للاعبين ذوي الأسلوب الهجومي');

// الغال
tz_def('TRIBE3_L1', 'وقت لعب قليل');
tz_def('TRIBE3_L2', 'حماية جيدة للموارد ودفاع قوي');
tz_def('TRIBE3_L3', 'فرسان ممتازون وسريعون');
tz_def('TRIBE3_L4', 'مناسبة جدًا للاعبين الجدد');

// الهون
tz_def('TRIBE6_L1', 'يتطلب وقتًا طويلًا');
tz_def('TRIBE6_L2', 'فرسان أقوياء بشكل مذهل');
tz_def('TRIBE6_L3', 'تعتمد على الآخرين للحماية');
tz_def('TRIBE6_L4', 'غير موصى بها للاعبين الجدد!');

// المصريون
tz_def('TRIBE7_L1', 'وقت لعب قليل');
tz_def('TRIBE7_L2', 'موارد متاحة أكثر');
tz_def('TRIBE7_L3', 'وحدات دفاعية ممتازة');
tz_def('TRIBE7_L4', 'مناسبة جدًا للاعبين الجدد');

// الإسبرطيون
tz_def('TRIBE8_L1', 'وقت لعب متوسط');
tz_def('TRIBE8_L2', 'استهلاك محاصيل فعّال');
tz_def('TRIBE8_L3', 'تعافٍ أسهل بعد المعارك');
tz_def('TRIBE8_L4', 'موصى بها للاعبين الجدد النشطين');

// الفايكنج
tz_def('TRIBE9_L1', 'يتطلب وقتًا طويلًا');
tz_def('TRIBE9_L2', 'مهاجمون شجعان وهجوم قوي');
tz_def('TRIBE9_L3', 'دفاع ضعيف في بداية اللعبة');
tz_def('TRIBE9_L4', 'غير موصى بها للاعبين الجدد!');
// ============================================================================
// NOVATERRA RTL/AR PASS — hardcoded template strings fix (Aug 2026)
// Constants extracted from hardcoded English text found directly inside
// Manual/, Anleitung/, Ranking/, Message/, Alliance/, Profile/ and Simulator/
// .tpl files. Previously these templates bypassed the language system
// entirely, so they always rendered in English regardless of the selected
// language. See _TATARS_RENAME_NOTE.md / hardcoded-strings audit.
// ============================================================================

tz_def('TZ_MANUAL_PLUS_INTRO', 'يمنحك حساب بلس التحسينات التالية:');
tz_def('TZ_MANUAL_WAITING_LOOP', 'قائمة انتظار لأوامر البناء');
tz_def('TZ_MANUAL_LARGER_MAP', 'خريطة أكبر');
tz_def('TZ_MANUAL_ARCHIVE_FUNCTION', 'أرشفة التقارير والرسائل');
tz_def('TZ_MANUAL_SORT_REPORTS', 'فرز التقارير والرسائل');
tz_def('TZ_MANUAL_SORT_MARKET', 'فرز السوق');
tz_def('TZ_MANUAL_AUTOCOMPLETE', 'الإكمال التلقائي');
tz_def('TZ_MANUAL_DIRECT_LINKS_FREE', 'روابط مباشرة قابلة للتخصيص بحرية');
tz_def('TZ_MANUAL_GRAPHIC_STATS', 'إحصاءات بيانية');
tz_def('TZ_MANUAL_CENTRAL_OVERVIEW', 'نظرة عامة مركزية على الحساب');
tz_def('TZ_MANUAL_NOTEPAD', 'دفتر ملاحظات');
tz_def('TZ_MANUAL_BONUS_NOTE', 'لن تُضاف هذه الميزة لكل حقل مورد على حدة، بل إلى مجموع الإنتاج.');
tz_def('TZ_MANUAL_GOLD_CLUB_EXCLUDED', 'هذه الميزة غير مشمولة في نادي الذهب!');
tz_def('TZ_MANUAL_BONUS_TITLE_LUMBER', 'مكافأة إنتاج الخشب');
tz_def('TZ_MANUAL_BONUS_INTRO_LUMBER', 'بهذه الميزة الذهبية سيزداد إنتاج الخشب في جميع قراك بنسبة 25%.');
tz_def('TZ_MANUAL_BONUS_TITLE_CLAY', 'مكافأة إنتاج الطين');
tz_def('TZ_MANUAL_BONUS_INTRO_CLAY', 'بهذه الميزة الذهبية سيزداد إنتاج الطين في جميع قراك بنسبة 25%.');
tz_def('TZ_MANUAL_BONUS_TITLE_IRON', 'مكافأة إنتاج الحديد');
tz_def('TZ_MANUAL_BONUS_INTRO_IRON', 'بهذه الميزة الذهبية سيزداد إنتاج الحديد في جميع قراك بنسبة 25%.');
tz_def('TZ_MANUAL_BONUS_TITLE_CROP', 'مكافأة إنتاج المحاصيل');
tz_def('TZ_MANUAL_BONUS_INTRO_CROP', 'بهذه الميزة الذهبية سيزداد إنتاج المحاصيل في جميع قراك بنسبة 25%.');
tz_def('TZ_MANUAL_INSTANT_TITLE', 'إنهاء أوامر البناء والأبحاث فورًا.');
tz_def('TZ_MANUAL_INSTANT_DESC', 'سيتم إنهاء جميع أوامر البناء والأبحاث الجارية في الأكاديمية وكذلك الحدادة ومستودع التسليح فورًا في القرية الحالية.');
tz_def('TZ_MANUAL_INSTANT_EXCLUDE_PREFIX', 'غير أن <em>مباني</em> المقر والقصر وكذلك');
tz_def('TZ_MANUAL_INSTANT_EXCLUDE_SUFFIX', 'التي تحتوي على إحدى عجائب الدنيا مستثناة من ذلك.');
tz_def('TZ_MANUAL_NPC_TITLE', 'تاجر NPC');
tz_def('TZ_MANUAL_NPC_DESC', 'يقوم تاجر NPC بمبادلة أي كمية تريدها من الموارد في القرية بموارد أخرى بنسبة 1:1.');
tz_def('TZ_MANUAL_GOLDCLUB_INTRO', 'سيتم خصم رسوم الاشتراك في نادي الذهب مرة واحدة فقط. بعدها ستحصل على مجموعة من المزايا الحصرية، بعضها مجاني والبعض الآخر يكلف كمية محددة من الذهب في كل استخدام.');
tz_def('TZ_MANUAL_GOLDCLUB_FREE_HEADER', 'مزايا نادي الذهب المجانية:');
tz_def('TZ_MANUAL_RAID_LIST', 'قائمة الغارات');
tz_def('TZ_MANUAL_RAID_STATS', 'إحصاءات الغارات');
tz_def('TZ_MANUAL_MERCHANTS_THRICE', 'إرسال التجار ثلاث مرات');
tz_def('TZ_MANUAL_SEARCH_CROPPERS', 'البحث عن قرى 9 و15 حقلًا زراعيًا متضمنة الواحات');
tz_def('TZ_MANUAL_GOLDCLUB_PRICED_HEADER', 'مزايا نادي الذهب المدفوعة:');
tz_def('TZ_MANUAL_MASTER_BUILDER_3', 'تكليف كبير البنائين بما يصل إلى 3 أوامر بناء');
tz_def('TZ_MANUAL_AUTO_TRADE', 'طرق تجارية آلية بين قراك الخاصة');
tz_def('TZ_MANUAL_HIDE_TROOPS', 'إخفاء الجنود من الهجمات في الغابة');
tz_def('TZ_MANUAL_HIDE_TROOPS_NOTE', '(ممكن فقط إذا لم تعد أي قوات إلى المنزل خلال آخر <strong>10</strong> ثوانٍ)');
tz_def('TZ_MANUAL_DIRECT_LINKS_DESC', 'باستخدام الروابط المباشرة يمكنك إنشاء روابط لأي وجهة وتسريع التنقل.');
tz_def('TZ_MANUAL_DIRECT_LINKS_EXAMPLE_HEADER', 'مثال على الروابط المباشرة');
tz_def('TZ_MANUAL_DIRECT_LINKS_STAR_NOTE', 'إضافة علامة * إلى الرابط ستجعل الرابط المباشر يفتح في نافذة جديدة.');
tz_def('ANL_ROMAN_TRAIT_INFANTRY', 'مشاة قوية جدًا، وفرسان متوسطون');
tz_def('ANL_ROMAN_TRAIT_DEV', 'التطوير مكلف ويستغرق وقتًا طويلاً.');
tz_def('ANL_GAUL_TRAIT_SIEGE', 'أسلحة حصار مكلفة');
tz_def('ANL_GAUL_TRAIT_SETTLERS', 'مستوطنون رخيصو التكلفة');
tz_def('ANL_UNIT_SPEED_HEADER', 'السرعة');
tz_def('ANL_FAQ_CROP_SINK_Q', 'مساعدة، إنتاج المحاصيل لدي يتراجع أكثر فأكثر!');
tz_def('TZ_PLAYERS_ONLINE', 'اللاعبون المتصلون');
tz_def('TZ_SERVER_INFO', 'معلومات السيرفر');
tz_def('TZ_DAYS_SINCE_START', 'الأيام منذ البداية');
tz_def('TZ_ACTIVE_ALLIANCE', 'التحالفات النشطة');
tz_def('TZ_NATARS_VILLAGES', 'قرى الناتار');
tz_def('TZ_CONQUERED_OASIS', 'الواحات المحتلة');
tz_def('TZ_NEW_PLAYERS_TODAY', 'لاعبون جدد اليوم');
tz_def('TZ_TOP_5_ALLIANCES', 'أفضل 5 تحالفات');
tz_def('TZ_TOP_PLAYERS', 'أفضل اللاعبين');
tz_def('TZ_ENDGAME', 'نهاية اللعبة');
tz_def('TZ_ATTACKERS', 'المهاجمون');
tz_def('TZ_DEFENDERS', 'المدافعون');
tz_def('TZ_ARTEFACTS_LABEL', 'الآثار');
tz_def('TZ_WW_SHORT', 'عجائب الدنيا');
tz_def('TZ_NO_USERS_FOUND', 'لا يوجد لاعبون');
tz_def('TZ_NO_ALLIANCES_FOUND', 'لا توجد تحالفات');
tz_def('TZ_NO_HEROES_FOUND', 'لا يوجد أبطال');
tz_def('TZ_NO_VILLAGES_FOUND', 'لا توجد قرى');
tz_def('TZ_NO_MESSAGES_INBOX', 'لا توجد رسائل متاحة.');
tz_def('TZ_NO_MESSAGES_SENT', 'لا توجد رسائل مرسلة متاحة.');
tz_def('TZ_NO_MESSAGES_ARCHIVE', 'لا توجد رسائل متاحة في الأرشيف.');
tz_def('TZ_REMOVE_SITTER', 'إزالة الجليس');
tz_def('TZ_ACCOUNT_DELETE_TIMER_PREFIX', 'سيتم حذف الحساب خلال');
tz_def('TZ_MEDAL_ATTACKER_WEEK', 'مهاجم الأسبوع');
tz_def('TZ_MEDAL_DEFENDER_WEEK', 'مدافع الأسبوع');
tz_def('TZ_MEDAL_POP_CLIMBER_WEEK', 'متسلق السكان للأسبوع');
tz_def('TZ_MEDAL_ROBBER_WEEK', 'لص الأسبوع');
tz_def('TZ_MEDAL_TOP10_ATT_DEF', 'أفضل 10 هجوم ودفاع');
tz_def('TZ_MEDAL_TOP_ATTACK_STREAK', 'أعلى سلسلة هجوم');
tz_def('TZ_MEDAL_TOP_DEF_STREAK', 'أعلى سلسلة دفاع');
tz_def('TZ_MEDAL_TOP_POP_STREAK', 'أعلى سلسلة سكان');
tz_def('TZ_MEDAL_TOP_ROB_STREAK', 'أعلى سلسلة سرقة');
tz_def('TZ_MEDAL_RANK_CLIMBER', 'متسلق الترتيب');
tz_def('TZ_MEDAL_RANK_STREAK', 'سلسلة الترتيب');
tz_def('TZ_MEDAL_TOP10_ATTACK', 'أفضل 10 هجوم');
tz_def('TZ_MEDAL_TOP10_DEF', 'أفضل 10 دفاع');
tz_def('TZ_MEDAL_TOP10_POP', 'أفضل 10 سكان');
tz_def('TZ_MEDAL_TOP10_ROB', 'أفضل 10 سرقة');
tz_def('TZ_MEDAL_TOP10_RANK', 'أفضل 10 ترتيب');
tz_def('TZ_MEDAL_BONUS_DEFAULT', 'مكافأة');
tz_def('TZ_MEDAL_ARTEFACT_HOLDER', 'حائز على أثر');
tz_def('TZ_MEDAL_WW_BUILDER', 'باني عجائب الدنيا');
tz_def('TZ_MEDAL_WW_WINNER', 'الفائز بعجائب الدنيا');
tz_def('TZ_MEDAL_GREAT_STORE', 'المخزن الكبير');
tz_def('TZ_MEDAL_WALL_MASTER', 'سيد الأسوار');
tz_def('TZ_QUEST_PALACE_LABEL', 'القصر ');
tz_def('TZ_QUEST_RESIDENCE_LABEL', 'المقر ');
tz_def('TZ_MANUAL_PLUS_TITLE', 'حساب بلس');

// --- quest_core.tpl (banned-branch quest log) — Phase 1 hardcoded-text fix ---
tz_def('TZ_TASKS_TITLE', 'المهام');
tz_def('TZ_NOT_LOADED', 'لم يتم التحميل!');
tz_def('TZ_WELCOME_TO', 'أهلاً بك في');
tz_def('TZ_WELCOME_STORY', 'كما ترى، لقد أصبحت زعيمًا لهذه القرية الصغيرة. سأكون مستشارك خلال الأيام القليلة الأولى، ولن أفارق جانبك (اليمين).');
tz_def('TZ_LOOK_AROUND_OWN', 'تجوّل بمفردك.');

// --- quest_core.tpl (banned-branch quest log) — Phase 2: quests 1-12 headers ---
tz_def('TZ_TASK_1_WOODCUTTER', 'المهمة 1: الحطّاب');
tz_def('TZ_TASK_2_CROP', 'المهمة 2: المحصول');
tz_def('TZ_TASK_4_OTHER_PLAYERS', 'المهمة 4: اللاعبون الآخرون');
tz_def('TZ_TASK_5_TWO_BUILDING_ORDERS', 'المهمة 5: أمران بالبناء');
tz_def('TZ_TASK_6_MESSAGES', 'المهمة 6: الرسائل');
tz_def('TZ_Q8_STORY_BEFORE', 'علينا الآن زيادة إنتاج مواردك قليلًا. طوّر جميع حقول الموارد لديك إلى المستوى 1.');
