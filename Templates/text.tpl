<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       text_format.tpl                                             ##
##  Developed by:  Dixie                                                       ##
##  Refactored by: Shadow Incremental Refactor 			                       ##
##  License:       Novaterra Project                                            ##
##  Copyright:     Novaterra (c) 2010-2026. All rights reserved.                ##
##                                                                             ##
##  Incremental Refactor Notes:                                                ##
##  - Preserved original placeholder logic (the message placeholder)          ##
##  - Improved BBCode parsing using str_replace                                ##
##  - Reduced repeated preg_replace calls                                      ##
##  - Added safety structure for PHP 7+                                        ##
##                                                                             ##
#################################################################################

/**
 * ---------------------------------------------------------
 * Input text (template placeholder)
 * ---------------------------------------------------------
 */
$txt = "<h1><b>خطط بناء عجائب العالم</b></h1>\n\n\nمنذ عصور بعيدة فوجئت قبائل ترافيان بعودة الناتار. استعد الأحرار لحرب أخيرة، وظهرت أسطورة عجائب العالم بوصفها طريقًا لاستعادة السلام.\n\nلكن بناء هذه العجيبة يحتاج إلى خطط بناء. لذلك شُيّدت خزائن كنوز في المدن لحفظها، وظلت أماكن الخطط مجهولة حتى عثر عليها كشافة القبائل في واحات تحرسها جيوش الناتار.\n\nلن يحصل على الخطة إلا الأبطال الأشد شجاعة. وفي النهاية ستقرر المعركة هل تنجح قبائل الأحرار في هزيمة الناتار مرة أخرى، فالناتار لن ينسحبوا من دون قتال!\n\n<img src=\"/img/x.gif\" class=\"WWBuildingPlansAnnouncement\" title=\"خطة البناء القديمة\" alt=\"خطة البناء القديمة\">\n\nلانتزاع خطة بناء من الناتار:\n- هاجم القرية بهجوم كامل، وليس غارة.\n- انتصر في الهجوم.\n- دمّر خزينة الكنوز.\n- يجب أن يكون بطلك ضمن الهجوم لأنه الوحيد القادر على حمل الخطة.\n- يجب أن توجد خزينة كنوز فارغة من المستوى 10 في قرية الانطلاق.\nملاحظة: إذا لم تكتمل الشروط، فسيحصل الهجوم التالي المستوفي لها على خطة البناء.\n\n\n\nلبناء خزينة كنوز، تحتاج إلى مبنى رئيسي من المستوى 10، ويجب ألا تحتوي القرية على عجيبة العالم.\n\nلبناء عجيبة العالم، يجب أن تملك خطة البناء من المستوى 0 إلى 50، ثم تحتاج إلى مجموعة إضافية في تحالفك من المستوى 51 إلى 100. لا تكفي مجموعتان في حساب قرية العجيبة!";

/**
 * ---------------------------------------------------------
 * BBCode -> HTML conversion
 * (kept compatible with legacy regex behavior)
 * ---------------------------------------------------------
 */
$bbMap = array(
    '[b]'  => '<b>',
    '[/b]' => '</b>',
    '[i]'  => '<i>',
    '[/i]' => '</i>',
    '[u]'  => '<u>',
    '[/u]' => '</u>',
);

/**
 * Apply replacements (faster + cleaner than multiple preg_replace)
 */
$txt = str_replace(array_keys($bbMap), array_values($bbMap), $txt);

/**
 * ---------------------------------------------------------
 * Culoare: [color=#rrggbb]...[/color]
 *
 * Se accepta DOAR cod hexazecimal, verificat cu expresie regulata. Fara asta,
 * un mesaj de sistem ar putea strecura CSS arbitrar in pagina fiecarui jucator.
 * ---------------------------------------------------------
 */
$txt = preg_replace(
    '/\[color=(#[0-9a-fA-F]{3,6})\]/',
    '<span style="color:$1">',
    $txt
);
// Inchidem doar atatea etichete cate s-au deschis: un cod de culoare invalid
// nu trebuie sa lase un </span> orfan in pagina jucatorului.
$colorOpen = substr_count($txt, '<span style="color:');
$txt = preg_replace('/\[\/color\]/', '</span>', $txt, $colorOpen);
$txt = str_replace('[/color]', '', $txt);

/**
 * ---------------------------------------------------------
 * Preserve line breaks as in original implementation
 * ---------------------------------------------------------
 */
echo nl2br($txt);
?>