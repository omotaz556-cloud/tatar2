<?php
// don't let SQL time out when 30-500 seconds (depending on php.ini) is not enough
@set_time_limit(0);

include("templates/install_lang.php");
include("templates/script.tpl");

if(!isset($_GET['s'])) {
	$_GET['s']=0;
}
// Default timezone (15) is now Asia/Riyadh so a fresh install starts in the Middle East zone
// unless the admin explicitly picks another one on the Configuration step.
$tz=(isset($_GET['t']))? (int)$_GET['t'] : 15;
    switch($tz) {
        case 1: $t_zone="Africa/Dakar";break;
        case 2: $t_zone="America/New_York";break;
        case 3: $t_zone="Antarctica/Casey";break;
        case 4: $t_zone="Arctic/Longyearbyen";break;
        case 5: $t_zone="Asia/Kuala_Lumpur";break;
        case 6: $t_zone="Atlantic/Azores";break;
        case 7: $t_zone="Australia/Melbourne";break;
        case 8: $t_zone="Europe/Bucharest";break;
        case 9: $t_zone="Europe/London";break;
		case 10: $t_zone="Europe/Bratislava";break;
		case 11: $t_zone="Indian/Maldives";break;
		case 12: $t_zone="Pacific/Fiji";break;
		case 13: $t_zone="America/Sao_Paulo";break;
		case 14: $t_zone="Europe/Zurich";break;
		case 15: $t_zone="Asia/Riyadh";break;
		case 16: $t_zone="Asia/Dubai";break;
		case 17: $t_zone="Asia/Kuwait";break;
		case 18: $t_zone="Asia/Qatar";break;
		case 19: $t_zone="Asia/Bahrain";break;
		case 20: $t_zone="Asia/Amman";break;
		case 21: $t_zone="Africa/Cairo";break;
		case 22: $t_zone="Asia/Baghdad";break;
		case 23: $t_zone="Asia/Beirut";break;
		default: $t_zone="Asia/Riyadh";break;
    }
date_default_timezone_set($t_zone);
?>

 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="<?=$GLOBALS['INSTALL_UI_LANG']?>" dir="<?=install_is_rtl()?'rtl':'ltr'?>">
<head>
	<title><?=t('page_title')?></title>
	<link rel="shortcut icon" href="favicon.ico" />
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<script src="mt-full.js?0ac37" type="text/javascript"></script>
	<script src="unx.js?f4b7h" type="text/javascript"></script>
	<script src="new.js?0ac37" type="text/javascript"></script>
	<link href="../gpack/novaterra_classic/lang/en/lang.css?f4b7d" rel="stylesheet" type="text/css" />
	<link href="../gpack/novaterra_classic/lang/en/compact.css?f4b7i" rel="stylesheet" type="text/css" />
	<link href="../gpack/novaterra_classic/novaterra.css?e21d2" rel="stylesheet" type="text/css" />
	<link href="../gpack/novaterra_classic/lang/en/lang.css?e21d2" rel="stylesheet" type="text/css" />
	<?php if(install_is_rtl()): ?>
	<style>
		body{direction:rtl;}
		.stepper{direction:rtl;}
		.tz-footer{direction:rtl;}
		.grid-2, .grid-1{direction:rtl;}
		label{text-align:right;}
		.step .num{margin-left:8px;margin-right:0;}
	</style>
	<?php endif; ?>
</head>
<body>
<script LANGUAGE="JavaScript">
function refresh(tz) {
     var dt = new Array();
    dt=tz.split(",");
    tz=dt[0];
    location="?s=1&t="+tz;
}
function proceed() {
	var e = document.getElementById('Submit');

	// if we disable the button right away, we wouldn't be able to submit the form
    setTimeout(function() {
        e.disabled = "disabled";
    }, 200);

    e.value = "<?=install_is_rtl()?'جارٍ المعالجة...':'Processing...'?>";

    return true;
}
</script>
	<div class="wrapper">
		<img class="c1" src="img/x.gif" id="msfilter" alt="" name="msfilter" />

		<div id="dynamic_header"></div>

		<div id="header">
			<div id="mtop"></div>
			<?=install_lang_switch_html()?>
		</div>

		<div id="mid">
			<div id="side_navi">
				<?php include("templates/menu.tpl"); ?>
			</div>

				<div id="content" class="login">
					<?php
					IHG_Progressbar::draw_css();
					$bar = new IHG_Progressbar(6, t('step_of'));
					$bar->draw();
					for($i = 0; $i < ($_GET['s']+1); $i++) {
						$bar->tick();
					}
					?>
				<div class="headline"><center>
				<span class="f18 c5"><?=t('install_script')?></span>
				</center></div>

				<?php
				if(substr(sprintf('%o', fileperms('../')), -4)<'700'){
					echo"<span class='f18 c5'>".t('err_generic')."</span><br />".t('err_write_config');
				} 
				else if (file_exists("../var/installed")) {
					echo"<span class='f18 c5'>".t('err_generic')."</span><br />".t('err_already_done')."<br />".t('err_already_done2');
				}
				else
					switch($_GET['s']){
						case 0:
						include("templates/greet.tpl");
						break;
						case 1:
						include("templates/config.tpl");
						break;
						case 2:
						include("templates/dataform.tpl");
						break;
						case 3:
						include("templates/wdata.tpl");
						break;
						case 4:
					    include("templates/accounts.tpl");
					    break;
						case 5:
						include("templates/end.tpl");
						break;
					}
				?>

			<div id="side_info" class="outgame"></div>

			<div class="clear"></div>
		</div>

		<div class="footer-stopper outgame"></div>

		<div class="clear"></div>

<?php include("../Templates/footer.tpl"); ?>
	</div>

	<div id="ce"></div>
</body>
</html>
