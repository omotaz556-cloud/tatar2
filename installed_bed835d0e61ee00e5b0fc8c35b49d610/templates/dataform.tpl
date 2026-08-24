<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : dataform.tpl                                              ##
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

include_once('../GameEngine/config.php');
if(isset($_GET['c']) && $_GET['c'] == 1) {
    echo '<div class="card" style="border-color:#fecaca;background:#fef2f2;color:#991b1b;"><b>'.t('err_import_db').'</b></div>';
}
if(isset($_GET['err']) && $_GET['err'] == 1) {
    echo '<div class="card" style="border-color:#fecaca;background:#fef2f2;color:#991b1b;">'.t('err_struct_found').' <b>'.TB_PREFIX.'</b> '.t('from_database').' <b>'.SQL_DB.'</b>.</div>';
}
?>
<form action="process.php" method="post" id="dataform">
  <input type="hidden" name="substruc" value="1">
  <div class="card">
    <span class="f10 c"><?=t('create_db_struct')?></span>
    <p style="color:#475569;"><b><?=t('warning_wait')?></b>: <?=t('warning_wait_txt')?></p>
    <div style="text-align:center;margin-top:12px;">
      <button class="btn" id="Submit" onclick="return proceed()"><?=t('create_db_btn')?></button>
    </div>
  </div>
</form>