<?php 

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : end.tpl                                                   ##
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

include("../GameEngine/config.php");

// بند 23 — تحصين قفل التثبيت:
// 1) ملف القفل var/installed هو المرجع الحقيقي (بيتفحص فعليًا في
//    process.php/index.php/ajax_croppers.php) فلازم يتكتب أولًا
//    وبشكل مؤكد، مش @touch من غير تحقق من النتيجة.
// 2) اسم المجلد بعد إعادة التسمية كان مبني على time() فقط، وده
//    قابل للتخمين/الحصر بسهولة (ثواني معدودة وقت التثبيت). استبدلناه
//    بتوكن عشوائي فعليًا (random_bytes) مش قابل للتخمين.
// 3) لو أي من العمليتين فشلت (صلاحيات الملفات مثلاً)، لازم نظهر
//    تحذير واضح للأدمن بدل ما نكمل بصمت ونسيبه يفتكر إن السيرفر آمن.
// 4) طبقة حماية إضافية: نحط .htaccess (Deny all) جوه مجلد التثبيت
//    نفسه حتى لو فشلت إعادة التسمية لأي سبب.

$lockWritten = @touch('../var/installed');

$randomSuffix = bin2hex(random_bytes(16));
$newInstallDir = '../installed_' . $randomSuffix;
$renameOk = @rename('../install/', $newInstallDir);

// دفاع إضافي: امنع أي وصول مباشر لمجلد التثبيت (سواء اتسمى تاني أو لأ)
$targetDirForHtaccess = $renameOk ? $newInstallDir : '../install';
@file_put_contents(
	$targetDirForHtaccess . '/.htaccess',
	"Require all denied\n" . "Order deny,allow\nDeny from all\n"
);

$installLockWarning = '';
if (!$lockWritten) {
	$installLockWarning .= '<p style="color:#b91c1c;font-weight:bold;">'
		. 'تحذير أمني: تعذّر إنشاء ملف القفل var/installed. '
		. 'المُثبِّت لسه ممكن يتشغل تاني! افحص صلاحيات الكتابة على مجلد var وحاول تاني، '
		. 'أو أنشئ الملف يدويًا.</p>';
}
if (!$renameOk) {
	$installLockWarning .= '<p style="color:#b91c1c;font-weight:bold;">'
		. 'تحذير أمني: تعذّر تغيير اسم مجلد install. '
		. 'احذف المجلد يدويًا فورًا (rm -R install) أو تأكد إن صلاحيات '
		. 'المجلد الأب تسمح بإعادة التسمية.</p>';
}
?>
<div class="card" style="text-align:center;">
  <h2 style="margin:0 0 8px;"><?=t('install_complete')?></h2>
  <p style="color:#475569;"><?=t('thanks_install')?></p>
  <?php if (!empty($installLockWarning)) echo $installLockWarning; ?>
  <div style="display:inline-block;text-align:left;background:#0f172a;color:#e2e8f0;border-radius:10px;padding:12px 16px;font-family:ui-monospace;font-size:13px;line-height:1.6;">
    rm -R install<br>
    chmod -R 755 GameEngine<br>
    chmod -R 777 GameEngine/Prevention<br>
    chmod -R 777 GameEngine/Notes<br>
    chmod -R 777 var/log
  </div>
  <div style="margin-top:16px;"><a class="btn" href="<?php echo HOMEPAGE; ?>"><?=t('go_home')?></a></div>
</div>

<div class="card">
  <h3 style="margin:0 0 12px;text-align:center;"><?=t('next_steps')?></h3>
  <div class="grid-2" style="gap:14px;">
    <div style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:12px;padding:14px;">
      <b><?=t('secure_server')?></b>
      <p style="margin:6px 0 0;color:#475569;font-size:14px;"><?=t('secure_server_txt')?></p>
    </div>
    <div style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:12px;padding:14px;">
      <b><?=t('read_docs')?></b>
      <p style="margin:6px 0 0;color:#475569;font-size:14px;"><?=t('read_docs_txt')?></p>
    </div>
    <div style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:12px;padding:14px;">
      <b><?=t('join_community')?></b>
      <p style="margin:6px 0 0;color:#475569;font-size:14px;"><?=t('join_community_txt')?></p>
    </div>
    <div style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:12px;padding:14px;">
      <b><?=t('star_github')?></b>
      <p style="margin:6px 0 0;color:#475569;font-size:14px;"><?=t('star_github_txt')?></p>
    </div>
  </div>
  <div style="text-align:center;margin-top:16px;display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
    <a class="btn" style="background:#0f172a;" href="https://github.com/omotaz556-cloud/tatar" target="_blank">GitHub</a>
    <a class="btn" style="background:#16a34a;" href="https://github.com/omotaz556-cloud/tatar/wiki" target="_blank"><?=t('documentation')?></a>
    <a class="btn" style="background:#64748b;" href="<?php echo defined('ADMIN_EMAIL') ? 'mailto:' . ADMIN_EMAIL : '#'; ?>"><?=t('contact_support')?></a>
  </div>
</div>