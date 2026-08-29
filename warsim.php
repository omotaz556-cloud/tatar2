<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : warsim.php                                                ##
##  Type           : Attack Simulator File                                     ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Dzoki (Original)                                          ##
##  Refactored by  : Shadow                                                    ##
##  Redesign by    : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  URLs:          : https://novaterra.example                    		           ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################

use App\Utils\AccessLogger;

include_once("GameEngine/Village.php");
AccessLogger::logRequest();

$battle->procSim($_POST);
$gkShell = true;
$gkPageTitle = SERVER_NAME . ' - ' . COMBAT_SIMULATOR;
tz_greek_shell_head($gkPageTitle, 'pg-warsim', array('includeNew2Js' => false));
tz_greek_shell_open('warsim', array('contentWrap' => true));
?>
<h1><?php echo COMBAT_SIMULATOR; ?></h1>
<form action="warsim.php" method="post">
<?php
if(isset($_POST['result'])) {
	$target = isset($_POST['target'])? $_POST['target'] : array();
	$tribe = isset($_POST['mytribe'])? $_POST['mytribe'] : $session->tribe;
	include("Templates/Simulator/res_a".(int)$tribe.".tpl");
    foreach($target as $tar) {
        include("Templates/Simulator/res_d".(int)$tar.".tpl");
    }
    echo "<p>".TZ_WARSIM_TYPE_OF_ATTACK.": <b>";
    echo $form->getValue('ktyp') == 0 ? TZ_WARSIM_NORMAL : RAID;
    echo "</b></p>";
    echo "<p>";
	if (isset($_POST['result'][7]) && isset($_POST['result'][8])){
		if ($form->getValue('ktyp') == 1) {
			echo TZ_WARSIM_RAM_HINT."<br>";
		}elseif ($_POST['result'][7] == 0){
			echo sprintf(TZ_WARSIM_RAM_DAMAGE, $form->getValue('walllevel'), '0')."</p>";
		}elseif ($_POST['result'][7] == $_POST['result'][8]){
			echo sprintf(TZ_WARSIM_RAM_DAMAGE, $form->getValue('walllevel'), $form->getValue('walllevel'))."</p>";
		}else{
			echo sprintf(TZ_WARSIM_RAM_DAMAGE, $form->getValue('walllevel'), (int)$_POST['result'][7])."</p>";
		}
	}

	if (isset($_POST['result'][3]) && isset($_POST['result'][4])){
		if ($form->getValue('ktyp') == 1) {
			echo TZ_WARSIM_CATA_HINT."</p>";
		}elseif ($_POST['result'][3] == 0){
			echo sprintf(TZ_WARSIM_CATA_DAMAGE, $form->getValue('kata'), '0')."</p>";
		}elseif ($_POST['result'][3] == $_POST['result'][4]){
			echo sprintf(TZ_WARSIM_CATA_DAMAGE, $form->getValue('kata'), $form->getValue('kata'))."</p></p>";
		}else{
			echo sprintf(TZ_WARSIM_CATA_DAMAGE, $form->getValue('kata'), (int)$_POST['result'][3])."</p>";
		}
	}
}

if (!empty($_GET['target'])) {
    // this only works for Nature, as GET links like this one will come from an oasis
	if ((int)$_GET['target'] !== 4) {
        $_GET['target'] = 4;
    }

    // fill-in session value-array data
    foreach ($_GET as $key => $value) {
        if ($key[0] === 'u' && is_numeric($value)) {
            $form->setValue('a2_' . substr($key, 1), $value);
        }
    }


}

$target = isset($_POST['target'])? $_POST['target'] : (!empty($_GET['target']) ? array((int) $_GET['target']) : array());
$tribe = isset($_POST['mytribe'])? $_POST['mytribe'] : $session->tribe;
if(count($target) > 0) {
	include("Templates/Simulator/att_".(int)$tribe.".tpl");
	echo "<table id=\"defender\" class=\"fill_in\" cellpadding=\"1\" cellspacing=\"1\">

	<thead>
		<tr>
			<th>
				".DEFENDER." <span class=\"small\"></span>
			</th>
		</tr>
	</thead>";
	foreach($target as $tar) {
		include("Templates/Simulator/def_".(int)$tar.".tpl");
	}
	include("Templates/Simulator/def_end.tpl");
	echo "<div class=\"clear\"></div>";
}

/**
 * Ce bonusuri PROPRII a folosit simularea.
 *
 * Simulatorul preia automat itemele echipate ale eroului si bonusurile
 * aliantei. Fara acest panou le-ar aplica in tacere, iar jucatorul n-ar avea
 * cum sa stie daca rezultatul le include sau nu.
 */
$wsLines = array();

if (class_exists('HeroBattleBonus') && HeroBattleBonus::enabled() && !empty($session->uid)) {

    $wsStrength = (int) HeroBattleBonus::statBonus($session->uid);

    if ($wsStrength > 0) {
        $wsLines[] = (defined('TZ_WS_ITEM_STRENGTH') ? TZ_WS_ITEM_STRENGTH : 'Equipment strength')
            . ': <b>+' . number_format($wsStrength) . '</b>';
    }

    $wsMap = HeroBattleBonus::unitBonusMap($session->uid);

    if (!empty($wsMap)) {
        foreach ($wsMap as $wsUnit => $wsPer) {
            // Numele unitatilor sunt constante de limba (U1..U90), nu campuri
            // in unitdata.php - acolo sunt doar valorile de lupta.
            $wsConst = 'U' . (int) $wsUnit;
            $wsName  = defined($wsConst) ? constant($wsConst) : $wsConst;

            $wsLines[] = (defined('TZ_WS_ITEM_UNIT') ? TZ_WS_ITEM_UNIT : 'Weapon bonus')
                . ': <b>+' . (int) $wsPer . '</b> / ' . htmlspecialchars($wsName, ENT_QUOTES, 'UTF-8');
        }
    }

    $wsBon = HeroBattleBonus::bonuses($session->uid);

    if ($wsBon && defined('HB_VS_NATARS') && !empty($wsBon[HB_VS_NATARS])) {
        $wsLines[] = (defined('TZ_WS_VS_NATARS') ? TZ_WS_VS_NATARS : 'Against Natars')
            . ': <b>+' . (int) $wsBon[HB_VS_NATARS] . '%</b>';
    }
}

if (class_exists('AllianceBonus') && AllianceBonus::enabled() && !empty($session->uid)) {
    $wsMetal = AllianceBonus::multiplier($session->uid, AllianceBonus::METALLURGY);

    if ($wsMetal > 1.0) {
        $wsLines[] = (defined('ALLYBONUS_METALLURGY') ? ALLYBONUS_METALLURGY : 'Metallurgy')
            . ': <b>+' . round(($wsMetal - 1) * 100) . '%</b>';
    }
}

if ($wsLines) {
?>
<style>
#ws_own{margin:6px 0 10px 0;padding:5px 9px;border-left:3px solid #7db72f;
    background:#f4faec;font-size:11px;color:#444;max-width:600px}
#ws_own .ws_t{font-weight:bold;color:#333;margin-right:4px}
#ws_own span.ws_i{display:inline-block;margin-right:12px;white-space:nowrap}
</style>
<div id="ws_own">
    <span class="ws_t"><?php echo defined('TZ_WS_APPLIED') ? TZ_WS_APPLIED
        : 'Automatically included in this simulation:'; ?></span>
    <?php foreach ($wsLines as $wsLine) { ?>
        <span class="ws_i"><?php echo $wsLine; ?></span>
    <?php } ?>
</div>
<?php
}
?>
<table id="select" cellpadding="1" cellspacing="1">
<thead><tr>
	<td><?php echo ATTACKER; ?></td>
	<td><?php echo DEFENDER; ?></td>
	<td><?php echo TZ_WARSIM_TYPE_OF_ATTACK; ?></td>
</tr></thead>
<tbody><tr>
	<td>
		<label><input class="radio" type="radio" name="a1_v" value="1" <?php if($tribe == 1) { echo "checked"; } ?>/> <?php echo TRIBE1; ?></label><br/>
		<label><input class="radio" type="radio" name="a1_v" value="2" <?php if($tribe == 2) { echo "checked"; } ?>/> <?php echo TRIBE2; ?></label><br/>
		<label><input class="radio" type="radio" name="a1_v" value="3" <?php if($tribe == 3) { echo "checked"; } ?>/> <?php echo TRIBE3; ?></label><br/>
		<?php if (defined('NEW_FUNCTION_TRIBE_HUNS') && NEW_FUNCTION_TRIBE_HUNS) { ?><label><input class="radio" type="radio" name="a1_v" value="6" <?php if($tribe == 6) { echo "checked"; } ?>/> <?php echo TRIBE6; ?></label><br/><?php } ?>
		<?php if (defined('NEW_FUNCTION_TRIBE_EGIPTEANS') && NEW_FUNCTION_TRIBE_EGIPTEANS) { ?><label><input class="radio" type="radio" name="a1_v" value="7" <?php if($tribe == 7) { echo "checked"; } ?>/> <?php echo TRIBE7; ?></label><br/><?php } ?>
		<?php if (defined('NEW_FUNCTION_TRIBE_SPARTANS') && NEW_FUNCTION_TRIBE_SPARTANS) { ?><label><input class="radio" type="radio" name="a1_v" value="8" <?php if($tribe == 8) { echo "checked"; } ?>/> <?php echo TRIBE8; ?></label><br/><?php } ?>
		<?php if (defined('NEW_FUNCTION_TRIBE_VIKINGS') && NEW_FUNCTION_TRIBE_VIKINGS) { ?><label><input class="radio" type="radio" name="a1_v" value="9" <?php if($tribe == 9) { echo "checked"; } ?>/> <?php echo TRIBE9; ?></label><br/><?php } ?>
		<label><input class="radio" type="radio" name="a1_v" value="5" <?php if($tribe == 5) { echo "checked"; } ?>/> <?php echo TRIBE5; ?></label>
	</td><td>
		<label><input class="check" type="checkbox" name="a2_v1" value="1" <?php if(in_array(1,$target)) { echo "checked"; } ?>/> <?php echo TRIBE1; ?></label><br/>
		<label><input class="check" type="checkbox" name="a2_v2" value="1" <?php if(in_array(2,$target)) { echo "checked"; } ?>/> <?php echo TRIBE2; ?></label><br/>
		<label><input class="check" type="checkbox" name="a2_v3" value="1" <?php if(in_array(3,$target)) { echo "checked"; } ?>/> <?php echo TRIBE3; ?></label><br/>
		<label><input class="check" type="checkbox" name="a2_v4" value="1" <?php if(in_array(4,$target)) { echo "checked"; } ?>/> <?php echo TRIBE4; ?></label><br/>
		<label><input class="check" type="checkbox" name="a2_v5" value="1" <?php if(in_array(5,$target)) { echo "checked"; } ?>/> <?php echo TRIBE5; ?></label><br/>
		<?php if (defined('NEW_FUNCTION_TRIBE_HUNS') && NEW_FUNCTION_TRIBE_HUNS) { ?><label><input class="check" type="checkbox" name="a2_v6" value="1" <?php if(in_array(6,$target)) { echo "checked"; } ?>/> <?php echo TRIBE6; ?></label><?php } ?><br/>
		<?php if (defined('NEW_FUNCTION_TRIBE_EGIPTEANS') && NEW_FUNCTION_TRIBE_EGIPTEANS) { ?><label><input class="check" type="checkbox" name="a2_v7" value="1" <?php if(in_array(7,$target)) { echo "checked"; } ?>/> <?php echo TRIBE7; ?></label><?php } ?><br/>
		<?php if (defined('NEW_FUNCTION_TRIBE_SPARTANS') && NEW_FUNCTION_TRIBE_SPARTANS) { ?><label><input class="check" type="checkbox" name="a2_v8" value="1" <?php if(in_array(8,$target)) { echo "checked"; } ?>/> <?php echo TRIBE8; ?></label><?php } ?><br/>
		<?php if (defined('NEW_FUNCTION_TRIBE_VIKINGS') && NEW_FUNCTION_TRIBE_VIKINGS) { ?><label><input class="check" type="checkbox" name="a2_v9" value="1" <?php if(in_array(9,$target)) { echo "checked"; } ?>/> <?php echo TRIBE9; ?></label><?php } ?>
		</td><td>
		<label><input class="radio" type="radio" name="ktyp" value="0" <?php if($form->getValue('ktyp') == 0 || $form->getValue('ktyp') == "") { echo "checked"; } ?>/> <?php echo TZ_WARSIM_NORMAL; ?></label><br/>

		<label><input class="radio" type="radio" name="ktyp" value="1" <?php if($form->getValue('ktyp') == 1) { echo "checked"; } ?>/> <?php echo RAID; ?></label><br/>
		<label><input type="hidden" name="uid" value="<?php echo $session->uid; ?>" /></label>
	</td>
</tr></tbody>
</table>

<p class="btn"><button value="ok" name="s1" id="btn_ok" class="trav_buttons" alt="<?php echo TZ_OK_2; ?>" /> <?php echo TZ_OK_2; ?> </button></p>
</form>
<?php
tz_greek_shell_close(array('buildPopup' => false, 'timer' => $start_timer));
