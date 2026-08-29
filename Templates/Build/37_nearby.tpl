<?php

#################################################################################
##  Nearby conquerable oases — inline table for Hero Mansion (Greek.sa)        ##
#################################################################################

global $database, $village, $generator, $session;

$vx = isset($village->coor['x']) ? (int) $village->coor['x'] : 0;
$vy = isset($village->coor['y']) ? (int) $village->coor['y'] : 0;
$mapSpan = defined('WORLD_MAX') ? (2 * (int) WORLD_MAX + 1) : 401;

$oasisTypeBonuses = [
	1  => [['r1', 25]],
	2  => [['r1', 25]],
	3  => [['r1', 25], ['r4', 25]],
	4  => [['r2', 25]],
	5  => [['r2', 25]],
	6  => [['r2', 25], ['r4', 25]],
	7  => [['r3', 25]],
	8  => [['r3', 25]],
	9  => [['r3', 25], ['r4', 25]],
	10 => [['r4', 25]],
	11 => [['r4', 25]],
	12 => [['r4', 50]],
];

$wrapDist = static function ($a, $b, $span) {
	$d = abs((int) $a - (int) $b);
	return min($d, $span - $d);
};

$squareLabel = static function ($chebyshev) {
	if ($chebyshev <= 1) {
		return '3 x 3';
	}
	if ($chebyshev <= 2) {
		return '5 x 5';
	}
	return '7 x 7';
};

$q = "
	SELECT w.id AS wref, w.x, w.y, o.type, o.conqured, o.owner, o.name, u.username
	FROM " . TB_PREFIX . "wdata AS w
	INNER JOIN " . TB_PREFIX . "odata AS o ON o.wref = w.id
	LEFT JOIN " . TB_PREFIX . "users AS u ON u.id = o.owner
	WHERE w.fieldtype = 0
	  AND (
		(ABS(w.x - " . (int) $vx . ") <= 3 OR ABS(w.x - " . (int) $vx . ") >= " . (int) ($mapSpan - 3) . ")
		AND
		(ABS(w.y - " . (int) $vy . ") <= 3 OR ABS(w.y - " . (int) $vy . ") >= " . (int) ($mapSpan - 3) . ")
	  )
";
$res = mysqli_query($database->dblink, $q);
$nearby = [];
if ($res) {
	while ($row = mysqli_fetch_assoc($res)) {
		$dx = $wrapDist($row['x'], $vx, $mapSpan);
		$dy = $wrapDist($row['y'], $vy, $mapSpan);
		$cheb = max($dx, $dy);
		if ($cheb > 3) {
			continue;
		}
		$row['_cheb'] = $cheb;
		$nearby[] = $row;
	}
}

usort($nearby, static function ($a, $b) {
	if ($a['_cheb'] !== $b['_cheb']) {
		return $a['_cheb'] - $b['_cheb'];
	}
	if ((int) $a['x'] !== (int) $b['x']) {
		return (int) $a['x'] - (int) $b['x'];
	}
	return (int) $a['y'] - (int) $b['y'];
});

$colOwner = defined('OWNER') ? OWNER : 'المالك';
$colCoords = defined('COORDINATES') ? COORDINATES : 'الإحداثيات';
$colRes = defined('RESOURCES') ? RESOURCES : 'الموارد';
$colSquare = defined('OASIS_IN_SQUARE') ? OASIS_IN_SQUARE : 'ضمن المربع';
$tableTitle = defined('OASIS_VIEW_NEARBY') ? OASIS_VIEW_NEARBY : 'الواحات القريبة منك التي يمكنك احتلالها';
$unoccLabel = 'غير محتلة';
$noNearby = defined('NO_OASIS') ? NO_OASIS : 'لا توجد واحات قريبة.';
?>
<table id="gk-nearby-oases" class="gk-nearby-oases" cellpadding="1" cellspacing="1">
	<thead>
		<tr>
			<th colspan="4"><?php echo htmlspecialchars($tableTitle, ENT_QUOTES, 'UTF-8'); ?></th>
		</tr>
		<tr>
			<td><?php echo htmlspecialchars($colOwner, ENT_QUOTES, 'UTF-8'); ?></td>
			<td><?php echo htmlspecialchars($colCoords, ENT_QUOTES, 'UTF-8'); ?></td>
			<td><?php echo htmlspecialchars($colRes, ENT_QUOTES, 'UTF-8'); ?></td>
			<td><?php echo htmlspecialchars($colSquare, ENT_QUOTES, 'UTF-8'); ?></td>
		</tr>
	</thead>
	<tbody>
<?php if (count($nearby) === 0) { ?>
		<tr><td class="none" colspan="4"><?php echo htmlspecialchars($noNearby, ENT_QUOTES, 'UTF-8'); ?></td></tr>
<?php } else {
	foreach ($nearby as $row) {
		$ox = (int) $row['x'];
		$oy = (int) $row['y'];
		$wref = (int) $row['wref'];
		$type = (int) $row['type'];
		$isFree = ((int) $row['conqured'] === 0) || ((int) $row['owner'] === 2);
		$mapHref = 'karte.php?d=' . $wref . '&amp;c=' . $generator->getMapCheck($wref);

		if ($isFree) {
			$ownerHtml = '<span class="none">' . htmlspecialchars($unoccLabel, ENT_QUOTES, 'UTF-8') . '</span>';
		} else {
			$uid = (int) $row['owner'];
			$uname = htmlspecialchars((string) ($row['username'] ?? ''), ENT_QUOTES, 'UTF-8');
			$ownerHtml = '<a href="spieler.php?uid=' . $uid . '">' . $uname . '</a>';
		}

		$resHtml = '';
		if (isset($oasisTypeBonuses[$type])) {
			foreach ($oasisTypeBonuses[$type] as $bonus) {
				$resHtml .= '<img class="' . $bonus[0] . '" src="img/x.gif" alt="" /><bdi dir="ltr">+' . (int) $bonus[1] . '%</bdi> ';
			}
		}
		$resHtml = trim($resHtml);
?>
		<tr>
			<td class="own"><?php echo $ownerHtml; ?></td>
			<td class="coords"><a href="<?php echo $mapHref; ?>" dir="ltr">(<?php echo $ox; ?>|<?php echo $oy; ?>)</a></td>
			<td class="res"><?php echo $resHtml !== '' ? $resHtml : '-'; ?></td>
			<td class="sq"><bdi dir="ltr"><?php echo $squareLabel((int) $row['_cheb']); ?></bdi></td>
		</tr>
<?php
	}
} ?>
	</tbody>
</table>
