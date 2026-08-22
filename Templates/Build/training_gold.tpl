<style type="text/css">
#build .build_logo img{position:absolute!important;top:32px!important;right:13px!important;}
#build.gid19,#build.gid20,#build.gid21,#build.gid29,#build.gid30{font-size:13px;}
#build.gid19 h1,#build.gid20 h1,#build.gid21 h1,#build.gid29 h1,#build.gid30 h1{font-size:24px;}
#build.gid19 .build_desc,#build.gid20 .build_desc,#build.gid21 .build_desc,#build.gid29 .build_desc,#build.gid30 .build_desc{font-size:13px;}
#build .training-gold-action{display:inline-block;margin-right:8px;vertical-align:middle;}
#build .training-gold-action a{display:inline-block;padding:2px;border:1px solid #d2b35a;background:#fff8d7;color:#8a5a00;font-weight:bold;line-height:16px;text-decoration:none;}
#build .training-gold-action img{width:16px;height:16px;vertical-align:middle;}
#build .training-gold-action a:hover{background:#fff0a6;}
</style>
<?php $hasTraining = !empty($trainlist); if ($session->gold >= 1 && $session->sit == 0) { ?>
<a class="training-gold-action" href="build.php?id=<?php echo (int)$id; ?>&amp;finishTraining=1" onclick="return <?php echo $hasTraining ? "confirm('تسريع تدريب القوات مقابل 1 ذهب؟')" : 'false'; ?>;" title="<?php echo $hasTraining ? 'تسريع التدريب مقابل 1 ذهب' : 'لا يوجد تدريب حاليًا'; ?>">
    <img src="<?php echo GP_LOCATE; ?>img/a/gold.gif" alt="تسريع التدريب" />
</a>
<?php } ?>