<?php
if (empty($_SESSION['access']) || (int) $_SESSION['access'] < 9) die(ADM_ACCESS_DENIED_DOT);
$grantError = (string) ($_GET['error'] ?? '');
$grantSuccess = isset($_GET['success']);
?>
<style>
.grant-resources{max-width:680px;margin:24px auto;font-family:Arial,sans-serif;direction:rtl}.grant-card{background:#fff;border:1px solid #dbe3ea;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06)}.grant-head{padding:14px 18px;background:#edf5f8;color:#234d62;font-size:18px;font-weight:bold}.grant-body{padding:18px}.grant-note{padding:10px 12px;background:#fff8df;border:1px solid #ecd68a;color:#695313;margin-bottom:16px}.grant-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}.grant-field{display:flex;flex-direction:column;gap:5px}.grant-field label{font-size:12px;font-weight:bold;color:#44515a}.grant-field input{padding:9px;border:1px solid #c8d2da;border-radius:4px;font-size:14px}.grant-submit{margin-top:18px;width:100%;padding:11px;border:0;border-radius:4px;background:#287da0;color:#fff;font-weight:bold;cursor:pointer}.grant-submit:hover{background:#1f637f}.grant-msg{padding:10px;margin-bottom:14px;border-radius:4px;background:#e8f7eb;color:#236333}.grant-error{padding:10px;margin-bottom:14px;border-radius:4px;background:#fdeaea;color:#8a2c2c}.grant-separate{margin-top:18px;padding-top:14px;border-top:1px solid #e5e7eb;color:#596773;font-size:12px}.grant-separate a{color:#287da0;font-weight:bold;text-decoration:none}@media(max-width:600px){.grant-grid{grid-template-columns:1fr}}
</style>
<div class="grant-resources"><div class="grant-card"><div class="grant-head">منح موارد للاعب</div><div class="grant-body">
<?php if ($grantSuccess): ?><div class="grant-msg">تم منح الموارد بنجاح للاعب رقم <?php echo (int) ($_GET['player'] ?? 0); ?> في جميع قراه.</div><?php endif; ?>
<?php if ($grantError): ?><div class="grant-error">تعذر تنفيذ الطلب. تحقق من رقم اللاعب وأدخل كمية موجبة واحدة على الأقل.</div><?php endif; ?>
<div class="grant-note">تُضاف الكميات إلى جميع قرى اللاعب، مع عدم تجاوز السعة الحالية للمخازن.</div>
<form action="../GameEngine/Admin/Mods/grantResources.php" method="post"><?php echo csrf_field(); ?><input type="hidden" name="admid" value="<?php echo (int) $_SESSION['id']; ?>"><div class="grant-grid">
<div class="grant-field"><label>رقم اللاعب</label><input type="number" name="uid" min="4" required></div>
<div class="grant-field"><label>الخشب</label><input type="number" name="wood" min="0" value="0"></div>
<div class="grant-field"><label>الطين</label><input type="number" name="clay" min="0" value="0"></div>
<div class="grant-field"><label>الحديد</label><input type="number" name="iron" min="0" value="0"></div>
<div class="grant-field"><label>المحصول</label><input type="number" name="crop" min="0" value="0"></div>
</div><button class="grant-submit" type="submit">منح الموارد</button></form></div></div></div>
<div class="grant-separate">الذهب والفضة ليست موارد قرية. لإدارتها استخدم <a href="?p=usergold">منح الذهب</a> أو محرر البطل من صفحة اللاعب.</div>