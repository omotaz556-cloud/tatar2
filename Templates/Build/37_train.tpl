<?php

#################################################################################
##  HEROSMANSION TRAIN — Greek layout + server-side validation                 ##
#################################################################################

global $database, $session, $village, $building, $generator, $id;

$result      = mysqli_query($database->dblink, "SELECT * FROM " . TB_PREFIX . "units WHERE `vref` = " . (int) $village->wid . "");
$units_array = mysqli_fetch_array($result);

$count_hero = mysqli_fetch_array(mysqli_query($database->dblink, "SELECT Count(*) as Total FROM " . TB_PREFIX . "hero WHERE `uid` = " . $database->escape($session->uid) . ""), MYSQLI_ASSOC);
$count_hero = (int) $count_hero['Total'];

$unitData = [
	1  => $u1  ?? [],
	2  => $u2  ?? [],
	3  => $u3  ?? [],
	5  => $u5  ?? [],
	6  => $u6  ?? [],
	11 => $u11 ?? [],
	12 => $u12 ?? [],
	13 => $u13 ?? [],
	15 => $u15 ?? [],
	16 => $u16 ?? [],
	21 => $u21 ?? [],
	22 => $u22 ?? [],
	24 => $u24 ?? [],
	25 => $u25 ?? [],
	26 => $u26 ?? [],
	51 => $u51 ?? [],
	53 => $u53 ?? [],
	54 => $u54 ?? [],
	55 => $u55 ?? [],
	56 => $u56 ?? [],
	61 => $u61 ?? [],
	62 => $u62 ?? [],
	63 => $u63 ?? [],
	65 => $u65 ?? [],
	66 => $u66 ?? [],
	71 => $u71 ?? [],
	72 => $u72 ?? [],
	73 => $u73 ?? [],
	75 => $u75 ?? [],
	76 => $u76 ?? [],
	81 => $u81 ?? [],
	83 => $u83 ?? [],
	84 => $u84 ?? [],
	85 => $u85 ?? [],
	86 => $u86 ?? [],
];

$tribeUnits = [
	1 => [1, 2, 3, 5, 6],
	2 => [11, 12, 13, 15, 16],
	3 => [21, 22, 24, 25, 26],
	6 => [51, 53, 54, 55, 56],
	7 => [61, 62, 63, 65, 66],
	8 => [71, 72, 73, 75, 76],
	9 => [81, 83, 84, 85, 86],
];

$heroTypeLabel = defined('HERO_TYPE') ? HERO_TYPE : 'نوع البطل';
$heroActLabel = defined('HERO_ACTIVITY') ? HERO_ACTIVITY : 'النشاط';
$heroNewStarts = defined('HERO_NEW_STARTS') ? HERO_NEW_STARTS : 'بطل جديد يبدأ عند مستوى 1';
$noHeroUnits = defined('NO_HERO_UNITS')
	? NO_HERO_UNITS
	: 'لا يوجد جنود لتدريب بطل، يجب تدريب اي جندي ليمكن ترقيته إلى بطل.';

$fmtRes = static function ($n) {
	return number_format((int) $n);
};

$renderTrainRow = function ($unitID, $unitData, $unitName, $units_array) use ($database, $session, $village, $generator, $id, $heroNewStarts, $fmtRes) {
	$data = $unitData[$unitID] ?? [];
	if ($data === []) {
		return '';
	}

	$safeName = htmlspecialchars($unitName, ENT_QUOTES, 'UTF-8');
	$dur = $generator->getTimeFormat(
		$database->getArtifactsValueInfluence($session->uid, $village->wid, 5, $data['time'] / SPEED) * 3
	);

	$row  = '<tr>';
	$row .= '<td class="desc">';
	$row .= '<div class="tit">';
	$row .= '<img class="unit u' . (int) $unitID . '" src="img/x.gif" alt="' . $safeName . '" title="' . $safeName . '" /> ';
	$row .= '<a href="#" onclick="return Popup(' . (int) $unitID . ',1);">' . $safeName . '</a>';
	$row .= '<span class="info">(' . htmlspecialchars($heroNewStarts, ENT_QUOTES, 'UTF-8') . ')</span>';
	$row .= '</div>';
	$row .= '<div class="details">';
	$row .= '<img class="r1" src="img/x.gif" alt="' . LUMBER . '" title="' . LUMBER . '" />' . $fmtRes($data['wood']);
	$row .= '|<img class="r2" src="img/x.gif" alt="' . CLAY . '" title="' . CLAY . '" />' . $fmtRes($data['clay']);
	$row .= '|<img class="r3" src="img/x.gif" alt="' . IRON . '" title="' . IRON . '" />' . $fmtRes($data['iron']);
	$row .= '|<img class="r4" src="img/x.gif" alt="' . CROP . '" title="' . CROP . '" />' . $fmtRes($data['crop']);
	$row .= '|<img class="clock" src="img/x.gif" alt="' . DURATION . '" title="' . DURATION . '" />' . $dur;
	$row .= '</div></td>';
	$row .= '<td class="act">';

	if ($village->awood < $data['wood'] || $village->aclay < $data['clay'] || $village->airon < $data['iron'] || $village->acrop < $data['crop']) {
		$row .= '<span class="none">' . htmlspecialchars(trim(NOT . ENOUGH_RESOURCES), ENT_QUOTES, 'UTF-8') . '</span>';
	} elseif ((int) ($units_array['u' . $unitID] ?? 0) === 0) {
		$row .= '<span class="none">' . htmlspecialchars(NOT_UNITS, ENT_QUOTES, 'UTF-8') . '</span>';
	} else {
		$row .= '<a href="build.php?id=' . (int) $id . '&amp;train=' . (int) $unitID . '">' . TRAIN . '</a>';
	}

	$row .= '</td></tr>';
	return $row;
};

if ($count_hero < 3) {
	if (isset($_GET['train'])) {
		$validationArray = $tribeUnits[$session->tribe] ?? [];
		$unitID = (int) $_GET['train'];

		if (in_array($unitID, $validationArray, true) && isset($unitData[$unitID]) && $unitData[$unitID] !== []) {
			$data = $unitData[$unitID];
			$unitsNow = (int) ($units_array['u' . $unitID] ?? 0);
			$researched = ($unitID === $validationArray[0])
				|| ((int) $database->checkIfResearched($village->wid, 't' . $unitID) !== 0);
			$enoughRes = $village->awood >= $data['wood']
				&& $village->aclay >= $data['clay']
				&& $village->airon >= $data['iron']
				&& $village->acrop >= $data['crop'];

			if ($researched && $unitsNow > 0 && $enoughRes) {
				$trainUntil = (int) round(time() + ($data['time'] / SPEED) * 3);
				mysqli_query($database->dblink, "INSERT INTO " . TB_PREFIX . "hero (`uid`, `wref`, `regeneration`, `unit`, `name`, `level`, `points`, `experience`, `dead`, `health`, `attack`, `defence`, `attackbonus`, `defencebonus`, `trainingtime`, `autoregen`, `intraining`) VALUES (" . $database->escape($session->uid) . ", " . (int) $village->wid . ", 0, " . $unitID . ", '" . $database->escape($session->username) . "', 0, 5, 0, 0, 100, 0, 0, 0, 0, " . $trainUntil . ", 50, 1)");
				mysqli_query($database->dblink, "UPDATE " . TB_PREFIX . "units SET `u$unitID` = `u$unitID` - 1 WHERE `vref` = " . (int) $village->wid . " AND `u$unitID` > 0");
				mysqli_query($database->dblink, "
					UPDATE " . TB_PREFIX . "vdata
					SET
						`wood` = `wood` - " . (int) $data['wood'] . ",
						`clay` = `clay` - " . (int) $data['clay'] . ",
						`iron` = `iron` - " . (int) $data['iron'] . ",
						`crop` = `crop` - " . (int) $data['crop'] . "
					WHERE `wref` = " . (int) $village->wid . "
					  AND `wood` >= " . (int) $data['wood'] . "
					  AND `clay` >= " . (int) $data['clay'] . "
					  AND `iron` >= " . (int) $data['iron'] . "
					  AND `crop` >= " . (int) $data['crop']);
			}
		}
		header('Location: build.php?id=' . (int) $id);
		exit;
	}

	$rowsHtml = '';
	$hasAnySoldier = false;
	if (isset($tribeUnits[$session->tribe])) {
		foreach ($tribeUnits[$session->tribe] as $index => $unitID) {
			$isBaseUnit = ($index === 0);
			$unitName   = constant('U' . $unitID);
			$unitsNow   = (int) ($units_array['u' . $unitID] ?? 0);

			if ($isBaseUnit || $database->checkIfResearched($village->wid, 't' . $unitID) != 0) {
				if ($unitsNow > 0) {
					$hasAnySoldier = true;
				}
				$rowsHtml .= $renderTrainRow($unitID, $unitData, $unitName, $units_array);
			}
		}
	}

	echo '<table cellpadding="1" cellspacing="1" class="build_details gk-hero-train">';
	echo '<thead>';
	echo '<tr><th colspan="2">' . TRAIN_HERO . '</th></tr>';
	echo '<tr><td>' . htmlspecialchars($heroTypeLabel, ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($heroActLabel, ENT_QUOTES, 'UTF-8') . '</td></tr>';
	echo '</thead><tbody>';

	if (!$hasAnySoldier || $rowsHtml === '') {
		echo '<tr><td class="none" colspan="2">' . htmlspecialchars($noHeroUnits, ENT_QUOTES, 'UTF-8') . '</td></tr>';
	} else {
		echo $rowsHtml;
	}

	echo '</tbody></table>';
}
?>
