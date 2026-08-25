<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : accounts.tpl                                              ##
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

if(isset($_GET['err']) && $_GET['err'] == 1) {
    echo '<div class="card" style="background:#fef2f2;border-color:#fecaca;color:#991b1b;margin-bottom:14px;">'.t('err_mh_support').'</div>';
}
if(isset($_GET['err']) && $_GET['err'] == 2) {
    echo '<div class="card" style="background:#fef2f2;border-color:#fecaca;color:#991b1b;margin-bottom:14px;">'.t('err_natars_reserv').'</div>';
}
?>
<form action="include/accounts.php" method="post" id="dataform">
  <div class="grid-2">
    <div class="card">
      <span class="f10 c"><?=t('multihunter_acc')?></span>
      <div style="margin-top:12px;display:grid;gap:10px;">
        <div><label><?=t('name')?></label><input class="input" type="text" value="Multihunter" disabled></div>
        <div><label><?=t('password')?></label><input class="input" type="password" name="mhpw" required></div>
      </div>
    </div>
    <div class="card">
      <span class="f10 c"><?=t('support_acc')?></span>
      <div style="margin-top:12px;display:grid;gap:10px;">
        <div><label><?=t('name')?></label><input class="input" type="text" value="Support" disabled></div>
        <div><label><?=t('password')?></label><input class="input" type="password" name="spw" required></div>
      </div>
    </div>
  </div>

  <div class="card">
    <span class="f10 c"><?=t('admin_acc')?></span>
    <div class="grid-2" style="margin-top:12px;">
      <div><label><?=t('admin_name')?></label><input class="input" name="aname"></div>
      <div><label><?=t('admin_email')?></label><input class="input" name="aemail" type="email"></div>
      <div><label><?=t('admin_password')?></label><input class="input" name="apass" type="password"></div>
      <div><label><?=t('tribe')?></label>
        <select class="input" name="atribe">
          <option value="1" selected>Romans</option>
          <option value="2">Teutons</option>
          <option value="3">Gauls</option>
		  <option value="6">Huns</option>
		  <option value="7">Egyptians</option>
		  <option value="8">Spartans</option>
		  <option value="9">Vikings</option>
        </select>
      </div>
      <div><label><?=t('show_in_stats')?></label>
        <select class="input" name="admin_rank">
          <option value="true">true</option>
          <option value="false" selected>false</option>
        </select>
      </div>
      <div><label><?=t('include_support')?></label>
        <select class="input" name="admin_support_msgs">
          <option value="true" selected>true</option>
          <option value="false">false</option>
        </select>
      </div>
      <div><label><?=t('allow_raidable')?></label>
        <select class="input" name="admin_raidable">
          <option value="true" selected>true</option>
          <option value="false">false</option>
        </select>
      </div>
    </div>
    <p style="color:#64748b;font-size:12px;margin-top:12px;"><?=t('skip_admin_note')?></p>
    <div style="text-align:center;margin-top:12px;">
      <button class="btn" type="submit"><?=t('create_accounts')?></button>
    </div>
  </div>
</form>