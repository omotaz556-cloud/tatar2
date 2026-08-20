<div class="card">
  <h3 style="margin:0 0 8px;font-size:16px;"><?=t('disclaimer_title')?></h3>
  <ul style="margin:0;padding-left:18px;line-height:1.6;color:#334155;">
    <li><?=t('disclaimer_1')?></li>
    <li><?=t('disclaimer_2')?></li>
    <li><?=t('disclaimer_3')?></li>
    <li><?=t('disclaimer_4')?></li>
    <li><?=t('disclaimer_5')?></li>
    <li><b><?=t('disclaimer_6')?></b></li>
    <li><?=t('disclaimer_7')?></li>
  </ul>
</div>

<div class="grid-2">
  <div class="card">
    <h3 style="margin:0 0 8px;"><?=t('before_install')?></h3>
    <div style="font-family:ui-monospace;background:#0f172a;color:#e2e8f0;padding:10px;border-radius:8px;font-size:13px;">
      chmod -R 777 install<br>chmod -R 777 GameEngine
    </div>
  </div>
  <div class="card">
    <h3 style="margin:0 0 8px;"><?=t('after_install')?></h3>
    <div style="font-family:ui-monospace;background:#0f172a;color:#e2e8f0;padding:10px;border-radius:8px;font-size:13px;">
      rm -R install<br>
      chmod -R 755 GameEngine<br>
      chmod -R 777 GameEngine/Prevention<br>
      chmod -R 777 GameEngine/Notes<br>
      chmod -R 777 var/log
    </div>
  </div>
</div>

<div class="card">
  <b><?=t('protect_admin')?></b> <?=t('protect_admin_txt')?>
</div>

<div style="text-align:center;margin-top:16px;">
  <a class="btn" href="?s=1"><?=t('next')?></a>
  <div style="margin-top:8px;color:#64748b;font-size:12px;"><?=t('team_signature')?></div>
</div>
