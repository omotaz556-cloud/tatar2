<?php

#################################################################################
##  HEROSMANSION HERO INFO — Greek.sa classic table layout                     ##
#################################################################################

include_once("GameEngine/Data/hero_full.php");
global $database, $technology, $village, $session, $id;

$heroStatColumns = [
	'off'    => 'attack',
	'deff'   => 'defence',
	'obonus' => 'attackbonus',
	'dbonus' => 'defencebonus',
	'reg'    => 'regeneration',
];

$t4HeroRes = defined('NEW_FUNCTIONS_HERO_T4') && NEW_FUNCTIONS_HERO_T4;
if ($t4HeroRes) {
	$heroStatColumns['res'] = 'resources';
}

// --- Process actions BEFORE any HTML (redirects must not follow output) ---

if ($t4HeroRes && isset($_POST['t4restype'])) {
	$t4Type = (int) $_POST['t4restype'];
	if ($t4Type >= 0 && $t4Type <= 4) {
		$t4Stmt = $database->dblink->prepare(
			"UPDATE " . TB_PREFIX . "hero SET `res_type` = ? WHERE `heroid` = ? LIMIT 1"
		);
		if ($t4Stmt) {
			$t4HeroId = (int) $hero_info['heroid'];
			$t4Stmt->bind_param('ii', $t4Type, $t4HeroId);
			$t4Stmt->execute();
			$t4Stmt->close();
		}
	}
	header("Location: build.php?id=" . (int) $id);
	exit;
}

if (isset($_POST['t4points'])) {
	// Optional rename on same submit
	if (isset($_POST['name'])) {
		$newName = trim(stripslashes((string) $_POST['name']));
		$nameLen = function_exists('mb_strlen')
			? mb_strlen($newName, 'UTF-8')
			: count(preg_split('//u', $newName, -1, PREG_SPLIT_NO_EMPTY));
		if ($newName !== '' && $nameLen >= 2) {
			$escName = $database->escape($newName);
			mysqli_query(
				$database->dblink,
				"UPDATE " . TB_PREFIX . "hero SET `name`='" . $escName . "' WHERE `uid`='" . $database->escape($session->uid) . "' AND dead = 0"
			);
			$hero_info['name'] = $newName;
		} elseif ($newName !== '' && $nameLen < 2) {
			header("Location: build.php?id=" . (int) $id . "&e=1");
			exit;
		}
	}

	$t4Alloc = array();
	$t4Total = 0;
	foreach ($heroStatColumns as $t4Key => $t4Col) {
		$t4Value = isset($_POST['p_' . $t4Key]) ? (int) $_POST['p_' . $t4Key] : 0;
		if ($t4Value < 0) { $t4Value = 0; }
		$t4Alloc[$t4Col] = $t4Value;
		$t4Total += $t4Value;
	}
	if ($t4Total > 0) {
		$t4Cols  = array_values($heroStatColumns);
		$t4Set   = array();
		$t4Guard = array();
		foreach ($t4Cols as $t4Col) {
			$t4Set[]   = "`" . $t4Col . "` = `" . $t4Col . "` + ?";
			$t4Guard[] = "`" . $t4Col . "` + ? <= 100";
		}
		$t4Stmt = $database->dblink->prepare(
			"UPDATE " . TB_PREFIX . "hero SET " . implode(", ", $t4Set) . ",
					`points` = `points` - ?
			 WHERE `heroid` = ?
			   AND `points` >= ?
			   AND " . implode(" AND ", $t4Guard)
		);
		if ($t4Stmt) {
			$t4HeroId = (int) $hero_info['heroid'];
			$t4Values = array();
			foreach ($t4Cols as $t4Col) { $t4Values[] = $t4Alloc[$t4Col]; }
			$t4Values[] = $t4Total;
			$t4Values[] = $t4HeroId;
			$t4Values[] = $t4Total;
			foreach ($t4Cols as $t4Col) { $t4Values[] = $t4Alloc[$t4Col]; }
			$t4Stmt->bind_param(str_repeat('i', count($t4Values)), ...$t4Values);
			$t4Stmt->execute();
			$t4Stmt->close();
		}
	}
	header("Location: build.php?id=" . (int) $id);
	exit;
}

if (isset($_POST['name']) && !empty($_POST['name']) && !isset($_POST['t4points'])) {
	$_POST['name'] = $database->escape(stripslashes($_POST['name']));
	mysqli_query($database->dblink, "UPDATE " . TB_PREFIX . "hero SET `name`='" . $_POST['name'] . "' where `uid`='" . $database->escape($session->uid) . "' AND dead = 0") or die("ERROR:" . mysqli_error($database->dblink));
	$hero_info['name'] = stripslashes($_POST['name']);
	echo "" . NAME_CHANGED . "";
}

if (isset($_GET['add'])) {
	$action = $_GET['add'];
	if ($action == "reset") {
		if ($hero_info['level'] <= 3) {
			$resetCols  = array_values($heroStatColumns);
			$resetSet   = array();
			$resetWhere = array();
			foreach ($resetCols as $resetCol) {
				$resetSet[]   = "`" . $resetCol . "` = 0";
				$resetWhere[] = "`" . $resetCol . "` != 0";
			}
			if (isset($heroStatColumns['res'])) {
				$resetSet[] = "`res_type` = 0";
			}
			mysqli_query($database->dblink,
				"UPDATE " . TB_PREFIX . "hero
				    SET `points` = (`level` * 5) + 5, " . implode(', ', $resetSet) . "
				  WHERE `heroid` = " . (int) $hero_info['heroid'] . "
				    AND `level` <= 3
				    AND (" . implode(' OR ', $resetWhere) . ")");
			header("Location: build.php?id=" . (int) $id);
			exit;
		}
	} elseif (isset($heroStatColumns[$action])) {
		$column = $heroStatColumns[$action];
		mysqli_query($database->dblink, "UPDATE " . TB_PREFIX . "hero SET `$column` = `$column` + 1, `points` = `points` - 1 WHERE `heroid` = " . (int) $hero_info['heroid'] . " AND `points` > 0 AND `$column` < 100");
		header("Location: build.php?id=" . (int) $id);
		exit;
	}
}

// --- Display ---

$unitId   = (int) $hero_info['unit'];
$unitName = $technology->getUnitName($unitId);
$unitData = $GLOBALS['u' . $unitId] ?? [];
$carryCap = (int) ($unitData['cap'] ?? 0);

$curLevel = (int) $hero_info['level'];
$curExp   = (int) $hero_info['experience'];
$t4MaxLevel = max(array_keys($hero_levels)) - 1;
$maxExp     = $hero_levels[$t4MaxLevel];
$expCurrent = $hero_levels[$curLevel] ?? 0;
$expNext    = $hero_levels[$curLevel + 1] ?? $maxExp;

if ($curExp < $maxExp && $expNext > $expCurrent && $curLevel < $t4MaxLevel) {
	$xpPercent = ($curExp - $expCurrent) / ($expNext - $expCurrent) * 100;
	$xpPercent = max(0, min(100, $xpPercent));
} elseif ($curLevel >= $t4MaxLevel) {
	$xpPercent = 100;
} else {
	$xpPercent = 0;
}

$bonusPct = static function ($multiplier) {
	$pct = round(((float) $multiplier - 1) * 100, 1);
	return ($pct == (int) $pct) ? (string) (int) $pct : (string) $pct;
};

$offPct  = $bonusPct($hero_info['ob']);
$defPct  = $bonusPct($hero_info['db']);
$indiv   = (int) $hero_info['attack'];
$regenPct = (int) ($hero_info['regeneration'] * 5 * SPEED);
$pointsAvail = (int) $hero_info['points'];
$healthPct = (int) floor($hero_info['health']);

$heroStatus = HERO_STATUS_READY;
if (!empty($hero_info['dead'])) {
	$heroStatus = defined('HERO_STATUS_DEAD') ? HERO_STATUS_DEAD : 'البطل ميت';
} else {
	$heroInVillage = 0;
	$uq = mysqli_query($database->dblink, "SELECT `hero` FROM " . TB_PREFIX . "units WHERE `vref` = " . (int) $village->wid . " LIMIT 1");
	if ($uq && ($ur = mysqli_fetch_assoc($uq))) {
		$heroInVillage = (int) $ur['hero'];
	}
	if ($heroInVillage < 1) {
		$heroStatus = defined('HERO_STATUS_AWAY') ? HERO_STATUS_AWAY : 'البطل خارج القرية';
	} else {
		$heroStatus = defined('HERO_STATUS_READY') ? HERO_STATUS_READY : 'جاهز للدفاع عن القرية';
	}
}

$titleInfo = defined('HERO_INFO_TITLE') ? HERO_INFO_TITLE : 'معلومات البطل';
$lblName = defined('HERO_LABEL_NAME') ? HERO_LABEL_NAME : NAME;
$lblHealth = defined('HERO_LABEL_HEALTH') ? HERO_LABEL_HEALTH : (defined('TZ_HEALTH') ? TZ_HEALTH : 'Health');
$lblType = defined('HERO_LABEL_TYPE') ? HERO_LABEL_TYPE : 'النوع';
$lblOff = defined('HERO_LABEL_OFF_ARMY') ? HERO_LABEL_OFF_ARMY : OFF_BONUS;
$lblDef = defined('HERO_LABEL_DEF_ARMY') ? HERO_LABEL_DEF_ARMY : DEF_BONUS;
$lblInd = defined('HERO_LABEL_INDIVIDUAL') ? HERO_LABEL_INDIVIDUAL : OFFENCE;
$lblReg = defined('HERO_LABEL_REGEN') ? HERO_LABEL_REGEN : REGENERATION;
$lblStatus = defined('HERO_LABEL_STATUS') ? HERO_LABEL_STATUS : STATUS;
$lblCarry = defined('HERO_LABEL_CARRY') ? HERO_LABEL_CARRY : CARRY;
$lblPtsNow = defined('HERO_LABEL_POINTS_NOW') ? HERO_LABEL_POINTS_NOW : 'نقاط البطل الآن';
$lblPtsNext = defined('HERO_LABEL_POINTS_NEXT')
	? sprintf(HERO_LABEL_POINTS_NEXT, $curLevel + 1)
	: ('نقاط البطل للمستوى ' . ($curLevel + 1));
$resUnit = defined('HERO_RES_UNIT') ? HERO_RES_UNIT : 'مورد';
$ptUnit = defined('HERO_POINT_UNIT') ? HERO_POINT_UNIT : POINTS;
$editLbl = defined('EDIT') ? EDIT : 'تعديل';
$saveLbl = defined('SAVE') ? SAVE : 'حفظ';
$lvlShort = defined('BUILD_LEVEL_SHORT') ? BUILD_LEVEL_SHORT : LEVEL;

$nextLevelPts = (int) ($expNext);
$safeName = htmlspecialchars($hero_info['name'], ENT_QUOTES, 'UTF-8');
$safeUnit = htmlspecialchars($unitName, ENT_QUOTES, 'UTF-8');
?>

<form action="build.php?id=<?php echo (int) $id; ?>" method="POST" id="gkHeroForm" class="gk-hero-form">
	<input type="hidden" name="userid" value="<?php echo (int) $session->uid; ?>" />
	<input type="hidden" name="hero" value="1" />
	<input type="hidden" name="t4points" value="1" />
	<?php foreach ($heroStatColumns as $t4Key => $t4Col) { ?>
		<input type="hidden" name="p_<?php echo $t4Key; ?>" id="t4in_<?php echo $t4Key; ?>" value="0" />
	<?php } ?>

	<style type="text/css">
		/* Pixel-matched to Greek.sa reference — inline so it always wins */
		#build.gid37 table#distribution.gk-hero-info { border: 1px solid #c3c3c3 !important; border-collapse: collapse !important; width: 100% !important; }
		#build.gid37 table#distribution.gk-hero-info thead th {
			text-align: center !important; font-weight: 700 !important; font-size: 13px !important; color: #000 !important;
			padding: 3px 6px !important; height: 22px !important; line-height: 16px !important;
			border: 1px solid #c3c3c3 !important;
			background: #f2f2f2 !important;
			background-image: linear-gradient(to bottom, #fbfbfb 0%, #f2f2f2 45%, #e8e8e8 100%) !important;
		}
		#build.gid37 table#distribution.gk-hero-info tr.gk-hero-xp td.xp-cell {
			background: #dddddd !important; background-image: none !important; text-align: center !important;
			padding: 0 !important; height: 22px !important; line-height: 22px !important;
			border: 1px solid #c3c3c3 !important; vertical-align: middle !important;
		}
		#build.gid37 table#distribution.gk-hero-info tr.gk-hero-xp td.edit-cell {
			background: #ffffff !important; background-image: none !important; text-align: center !important;
			width: 20% !important; min-width: 70px !important; padding: 0 4px !important; height: 22px !important; line-height: 22px !important;
			border: 1px solid #c3c3c3 !important; white-space: nowrap !important; vertical-align: middle !important;
		}
		#build.gid37 table#distribution.gk-hero-info .gk-xp-bar {
			position: relative; display: block; width: 100%; height: 22px !important;
			border: none !important; background: transparent !important; overflow: hidden;
		}
		#build.gid37 table#distribution.gk-hero-info .gk-xp-fill { display: block; height: 100%; background: #c8c8c8; min-width: 0; }
		#build.gid37 table#distribution.gk-hero-info .gk-xp-txt {
			position: absolute; inset: 0; text-align: center; line-height: 22px !important;
			font-size: 12px !important; font-weight: 400 !important; color: #000 !important;
			font-family: "Expo Arabic", Tahoma, Arial, Helvetica, sans-serif !important;
		}
		#build.gid37 table#distribution.gk-hero-info a.gk-edit-link {
			color: #1a2438 !important; font-size: 12px !important; font-weight: 400 !important;
			text-decoration: none !important; display: inline-block !important; width: 100%;
			background: none !important; border: none !important; text-align: center !important;
			line-height: 22px !important; font-family: "Expo Arabic", Tahoma, Arial, Helvetica, sans-serif !important;
			cursor: pointer;
		}
		#build.gid37 table#distribution.gk-hero-info a.gk-edit-link:hover {
			color: #0000cc !important; text-decoration: underline !important;
		}
		/* Vertical divider exactly at 50% — labels | values */
		#build.gid37 table#distribution.gk-hero-info col.gk-col-label { width: 50% !important; }
		#build.gid37 table#distribution.gk-hero-info col.gk-col-val { width: 30% !important; }
		#build.gid37 table#distribution.gk-hero-info col.gk-col-ctrl { width: 20% !important; }
		#build.gid37 table#distribution.gk-hero-info tbody th { width: 50% !important; text-align: center !important; }
		#build.gid37 table#distribution.gk-hero-info td.val { width: 30% !important; }
		#build.gid37 table#distribution.gk-hero-info td.val[colspan="2"] { width: 50% !important; }
		#build.gid37 table#distribution.gk-hero-info td.ctrl { width: 20% !important; }
		#build.gid37 table#distribution.gk-hero-info tr.gk-regen-row th,
		#build.gid37 table#distribution.gk-hero-info tr.gk-regen-row td,
		#build.gid37 table#distribution.gk-hero-info tr.gk-regen-row button.gk-pm {
			background: #f5f5f5 !important;
			background-image: none !important;
		}
	</style>
	<table cellpadding="0" cellspacing="0" id="distribution" class="gk-hero-info">
		<colgroup>
			<col class="gk-col-label" />
			<col class="gk-col-val" />
			<col class="gk-col-ctrl" />
		</colgroup>
		<thead>
			<tr>
				<th colspan="3"><?php echo htmlspecialchars($titleInfo, ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($lvlShort, ENT_QUOTES, 'UTF-8'); ?> <?php echo $curLevel; ?>)</th>
			</tr>
		</thead>
		<tbody>
			<tr class="gk-hero-xp">
				<td colspan="2" class="xp-cell">
					<div class="gk-xp-bar" title="<?php echo (int) $xpPercent; ?>%">
						<span class="gk-xp-fill" style="width:<?php echo (int) $xpPercent; ?>%"></span>
						<span class="gk-xp-txt"><?php echo (int) $xpPercent; ?>%</span>
					</div>
				</td>
				<td class="ctrl edit-cell">
					<a href="#" id="gk-hero-edit" class="gk-edit-link"><?php echo htmlspecialchars($editLbl, ENT_QUOTES, 'UTF-8'); ?></a>
				</td>
			</tr>
			<tr>
				<th><?php echo htmlspecialchars($lblName, ENT_QUOTES, 'UTF-8'); ?>:</th>
				<td class="val"><?php echo $safeName; ?></td>
				<td class="ctrl">
					<input type="text" class="text gk-name-input" name="name" id="gk-hero-name" maxlength="20" value="<?php echo $safeName; ?>" />
				</td>
			</tr>
			<tr>
				<th><?php echo htmlspecialchars($lblHealth, ENT_QUOTES, 'UTF-8'); ?>:</th>
				<td class="val" colspan="2"><b><?php echo $healthPct; ?>%</b></td>
			</tr>
			<tr>
				<th><?php echo htmlspecialchars($lblType, ENT_QUOTES, 'UTF-8'); ?>:</th>
				<td class="val">
					<img class="unit u<?php echo $unitId; ?>" src="img/x.gif" alt="<?php echo $safeUnit; ?>" title="<?php echo $safeUnit; ?>" />
					<a href="#" onclick="return Popup(<?php echo $unitId; ?>,1);" class="gk-unit-link"><?php echo $safeUnit; ?></a>
				</td>
				<td class="ctrl points" id="t4rem"><b><?php echo $pointsAvail; ?></b></td>
			</tr>
			<tr data-stat="obonus">
				<th><?php echo htmlspecialchars($lblOff, ENT_QUOTES, 'UTF-8'); ?>:</th>
				<td class="val green" id="gk-v-obonus"><b><?php echo htmlspecialchars($offPct, ENT_QUOTES, 'UTF-8'); ?> %</b></td>
				<td class="ctrl pm-cell">
					<button type="button" class="gk-pm gk-minus" data-stat="obonus" data-base="<?php echo (int) $hero_info['attackbonus']; ?>">−</button><button type="button" class="gk-pm gk-plus t4AddPoint" data-stat="obonus" data-base="<?php echo (int) $hero_info['attackbonus']; ?>">+</button>
				</td>
			</tr>
			<tr data-stat="dbonus">
				<th><?php echo htmlspecialchars($lblDef, ENT_QUOTES, 'UTF-8'); ?>:</th>
				<td class="val green" id="gk-v-dbonus"><b><?php echo htmlspecialchars($defPct, ENT_QUOTES, 'UTF-8'); ?> %</b></td>
				<td class="ctrl pm-cell">
					<button type="button" class="gk-pm gk-minus" data-stat="dbonus" data-base="<?php echo (int) $hero_info['defencebonus']; ?>">−</button><button type="button" class="gk-pm gk-plus t4AddPoint" data-stat="dbonus" data-base="<?php echo (int) $hero_info['defencebonus']; ?>">+</button>
				</td>
			</tr>
			<tr data-stat="off">
				<th><?php echo htmlspecialchars($lblInd, ENT_QUOTES, 'UTF-8'); ?>:</th>
				<td class="val green" id="gk-v-off"><b><?php echo $indiv; ?></b></td>
				<td class="ctrl pm-cell">
					<button type="button" class="gk-pm gk-minus" data-stat="off" data-base="<?php echo (int) $hero_info['attack']; ?>">−</button><button type="button" class="gk-pm gk-plus t4AddPoint" data-stat="off" data-base="<?php echo (int) $hero_info['attack']; ?>">+</button>
				</td>
			</tr>
			<tr data-stat="reg" class="gk-regen-row">
				<th><?php echo htmlspecialchars($lblReg, ENT_QUOTES, 'UTF-8'); ?>:</th>
				<td class="val green" id="gk-v-reg"><b><?php echo $regenPct; ?> %</b></td>
				<td class="ctrl pm-cell">
					<button type="button" class="gk-pm gk-minus" data-stat="reg" data-base="<?php echo (int) $hero_info['regeneration']; ?>">−</button><button type="button" class="gk-pm gk-plus t4AddPoint" data-stat="reg" data-base="<?php echo (int) $hero_info['regeneration']; ?>">+</button>
				</td>
			</tr>
			<tr class="gk-sep">
				<th><?php echo htmlspecialchars($lblStatus, ENT_QUOTES, 'UTF-8'); ?>:</th>
				<td class="val" colspan="2"><?php echo htmlspecialchars($heroStatus, ENT_QUOTES, 'UTF-8'); ?></td>
			</tr>
			<tr class="gk-status">
				<th><?php echo htmlspecialchars($lblCarry, ENT_QUOTES, 'UTF-8'); ?>:</th>
				<td class="val" colspan="2"><?php echo number_format($carryCap); ?> <?php echo htmlspecialchars($resUnit, ENT_QUOTES, 'UTF-8'); ?></td>
			</tr>
			<tr class="gk-status">
				<th><?php echo htmlspecialchars($lblPtsNow, ENT_QUOTES, 'UTF-8'); ?>:</th>
				<td class="val" colspan="2"><?php echo number_format($curExp); ?> <?php echo htmlspecialchars($ptUnit, ENT_QUOTES, 'UTF-8'); ?></td>
			</tr>
			<tr class="gk-status">
				<th><?php echo htmlspecialchars($lblPtsNext, ENT_QUOTES, 'UTF-8'); ?>:</th>
				<td class="val" colspan="2"><?php echo number_format($nextLevelPts); ?> <?php echo htmlspecialchars($ptUnit, ENT_QUOTES, 'UTF-8'); ?></td>
			</tr>
		</tbody>
	</table>

	<p class="gk-hero-save">
		<button type="submit" class="trav_buttons gk-save-btn"><?php echo htmlspecialchars($saveLbl, ENT_QUOTES, 'UTF-8'); ?></button>
	</p>
</form>

<?php if (isset($_GET['e'])) { ?>
	<p><font size="1" color="red"><b><?php echo ERROR_NAME_SHORT; ?></b></font></p>
<?php } ?>

<?php if ($hero_info['level'] <= 3) { ?>
	<p><?php echo YOU_CAN; ?> <a href="build.php?id=<?php echo (int) $id; ?>&amp;add=reset"><?php echo RESET; ?></a><?php echo YOUR_POINT_UNTIL; ?> <b>3</b><?php echo OR_LOWER; ?></p>
<?php } ?>

<script type="text/javascript">
(function () {
	var available = <?php echo (int) $pointsAvail; ?>;
	var pending = { off: 0, deff: 0, obonus: 0, dbonus: 0, reg: 0<?php echo $t4HeroRes ? ', res: 0' : ''; ?> };
	var base = {
		off: <?php echo (int) $hero_info['attack']; ?>,
		deff: <?php echo (int) $hero_info['defence']; ?>,
		obonus: <?php echo (int) $hero_info['attackbonus']; ?>,
		dbonus: <?php echo (int) $hero_info['defencebonus']; ?>,
		reg: <?php echo (int) $hero_info['regeneration']; ?>
	};
	var remCell = document.getElementById('t4rem');
	var speed = <?php echo (float) SPEED; ?>;
	var form = document.getElementById('gkHeroForm');

	function usedTotal() {
		var u = 0, k;
		for (k in pending) { if (pending.hasOwnProperty(k)) { u += pending[k]; } }
		return u;
	}

	function displayVal(stat) {
		var pts = base[stat] + pending[stat];
		if (stat === 'obonus' || stat === 'dbonus') {
			// Matches Units::buildHeroStats: 1 + 0.002 * points → % = 0.2 * points
			var pct = Math.round((0.2 * pts) * 10) / 10;
			pct = (pct === Math.floor(pct)) ? String(Math.floor(pct)) : String(pct);
			return pct + ' %';
		}
		if (stat === 'reg') {
			return String(Math.floor(pts * 5 * speed)) + ' %';
		}
		return String(pts);
	}

	function syncButtons() {
		var used = usedTotal();
		var left = available - used;
		var plusBtns = document.querySelectorAll('#gkHeroForm .gk-plus');
		var minusBtns = document.querySelectorAll('#gkHeroForm .gk-minus');
		var i, stat;
		for (i = 0; i < plusBtns.length; i++) {
			stat = plusBtns[i].getAttribute('data-stat');
			plusBtns[i].disabled = (left <= 0 || (base[stat] + pending[stat]) >= 100);
		}
		for (i = 0; i < minusBtns.length; i++) {
			stat = minusBtns[i].getAttribute('data-stat');
			minusBtns[i].disabled = (pending[stat] <= 0);
		}
	}

	function render() {
		var used = usedTotal(), k;
		for (k in pending) {
			if (!pending.hasOwnProperty(k)) { continue; }
			var cell = document.getElementById('gk-v-' + k);
			if (cell) { cell.innerHTML = '<b>' + displayVal(k) + '</b>'; }
			var input = document.getElementById('t4in_' + k);
			if (input) { input.value = String(pending[k]); }
		}
		if (remCell) { remCell.innerHTML = '<b>' + String(available - used) + '</b>'; }
		syncButtons();
	}

	function add(stat, delta) {
		if (!pending.hasOwnProperty(stat)) { return; }
		if (delta > 0) {
			if (usedTotal() >= available) { return; }
			if (base[stat] + pending[stat] >= 100) { return; }
			pending[stat]++;
		} else {
			if (pending[stat] <= 0) { return; }
			pending[stat]--;
		}
		render();
	}

	var plusBtns = document.querySelectorAll('#gkHeroForm .gk-plus');
	var minusBtns = document.querySelectorAll('#gkHeroForm .gk-minus');
	var i;
	for (i = 0; i < plusBtns.length; i++) {
		(function (btn) {
			btn.onclick = function (e) {
				if (e && e.preventDefault) { e.preventDefault(); }
				add(btn.getAttribute('data-stat'), 1);
				return false;
			};
		})(plusBtns[i]);
	}
	for (i = 0; i < minusBtns.length; i++) {
		(function (btn) {
			btn.onclick = function (e) {
				if (e && e.preventDefault) { e.preventDefault(); }
				add(btn.getAttribute('data-stat'), -1);
				return false;
			};
		})(minusBtns[i]);
	}

	var editBtn = document.getElementById('gk-hero-edit');
	var nameInp = document.getElementById('gk-hero-name');
	if (editBtn && nameInp) {
		editBtn.onclick = function (e) {
			if (e && e.preventDefault) { e.preventDefault(); }
			try { nameInp.focus(); nameInp.select(); } catch (err) {}
			return false;
		};
	}

	if (form) {
		form.onsubmit = function () {
			var k;
			for (k in pending) {
				if (!pending.hasOwnProperty(k)) { continue; }
				var input = document.getElementById('t4in_' + k);
				if (input) { input.value = String(pending[k]); }
			}
			return true;
		};
	}

	render();
})();
</script>
