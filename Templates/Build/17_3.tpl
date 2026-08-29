<?php

#################################################################################
##  MARKETPLACE NPC TRADE — Greek row-per-resource layout                      ##
#################################################################################

global $database, $session, $village, $id;

if ($session->gold <= 2) {
	header('Location: build.php?id=' . (int) $_GET['id']);
	exit;
}

$level = (int) $village->resarray['f' . $id];
$lvlLabel = defined('BUILD_LEVEL_SHORT') ? BUILD_LEVEL_SHORT : LEVEL;

$resSafe = static function ($v) {
	$v = (float) $v;
	return (is_finite($v) && $v > 0) ? (int) floor($v) : 0;
};
$awood = $resSafe($village->awood);
$aclay = $resSafe($village->aclay);
$airon = $resSafe($village->airon);
$acrop = $resSafe($village->acrop);

$totalRes = $awood + $aclay + $airon + $acrop;
$maxstore = max(0, (int) $village->maxstore);
$maxcrop = max(0, (int) $village->maxcrop);

$r = [];
for ($i = 1; $i <= 4; $i++) {
	$r[$i] = isset($_GET['r' . $i]) ? max(0, (int) $_GET['r' . $i]) : '';
}
$newsum = ($r[1] !== '' && $r[2] !== '' && $r[3] !== '' && $r[4] !== '') ? array_sum($r) : 0;
$remain = $totalRes - $newsum;

$wwvillage = $database->getResourceLevel($village->wid);
$isWW = ($wwvillage['f99t'] == 40);
$completed = isset($_GET['c']);
$maxLen = max(7, strlen((string) (int) max($maxstore, $maxcrop)));

$resData = [
	['r1', $awood, LUMBER],
	['r2', $aclay, CLAY],
	['r3', $airon, IRON],
	['r4', $acrop, CROP],
];
$colRes = 'المورد';
$colHave = 'الموجود';
$colWant = 'المطلوب';
$colRest = defined('REST') ? REST : 'الباقي';
?>
<div id="build" class="gid17">
	<a href="#" onClick="return Popup(17,4);" class="build_logo">
		<img class="building g17" src="img/x.gif" alt="<?php echo MARKETPLACE; ?>" title="<?php echo MARKETPLACE; ?>" />
	</a>
	<h1><?php echo MARKETPLACE; ?> <span class="level"><?php echo $lvlLabel; ?> <?php echo $level; ?></span></h1>
	<p class="build_desc"><?php echo MARKETPLACE_DESC; ?></p>

	<?php include '17_menu.tpl'; ?>

	<?php if ($completed) { ?>
		<p><b><?php echo NPC_COMPLETED; ?>.</b> <?php echo COSTS; ?> 3<img src="img/x.gif" class="gold" alt="<?php echo GOLD; ?>" title="<?php echo GOLD; ?>" /></p>
		<a href="build.php?id=<?php echo (int) $id; ?>&amp;t=3"><?php echo BACK_BUILDING; ?></a>
	<?php } else { ?>
		<p><?php echo NPC_TRADE_DESC; ?></p>

		<script type="text/javascript">
		var overall;
		function npcDiv(a, b) { return (b > 0 && isFinite(a)) ? Math.round(a / b) : 0; }
		function calculateRest() {
			resObj = document.getElementsByName("m2[]"); overall = 0;
			for (i = 0; i < resObj.length; i++) {
				var tmp = "";
				for (j = 0; j < resObj[i].value.length; j++) {
					if ((resObj[i].value.charAt(j) >= "0") && (resObj[i].value.charAt(j) <= "9")) tmp = tmp + resObj[i].value.charAt(j);
				}
				if (tmp === "") { tmp = "0"; newRes = 0; resObj[i].value = ""; }
				else {
					newRes = parseInt(tmp, 10);
					if ((i < 3) && (newRes > max123)) newRes = max123;
					if ((i === 3) && (newRes > max4)) newRes = max4;
					resObj[i].value = newRes;
				}
				dif = newRes - parseInt(document.getElementById("org" + i).innerHTML, 10);
				newHTML = dif;
				if (dif > 0) newHTML = "+" + dif;
				document.getElementById("diff" + i).innerHTML = newHTML;
				overall += newRes;
			}
			if (!isFinite(overall)) overall = 0;
			var total = parseInt(document.getElementById("org4").innerHTML, 10);
			if (!isFinite(total)) total = summe;
			rest = total - overall;
			if (!isFinite(rest)) rest = 0;
			document.getElementById("newsum").innerHTML = overall;
			document.getElementById("remain").innerHTML = rest;
			testSum();
		}
		function fillup(nr) {
			resObj = document.getElementsByName("m2[]");
			if (nr < 3) resObj[nr].value = max123;
			else resObj[nr].value = max4;
			calculateRest();
		}
		function portionOut() {
			restRes = parseInt(document.getElementById("remain").innerHTML, 10);
			if (!isFinite(restRes)) restRes = 0;
			rest = restRes;
			resObj = document.getElementsByName("m2[]");
			nullCount = 0; notNullCount = 0;
			for (j = 0; j < resObj.length; j++) {
				if ((restRes > 0) && (resObj[j].value === "")) nullCount++;
				if ((restRes < 0) && (resObj[j].value !== "")) notNullCount++;
			}
			nullCount2 = 0;
			if (restRes > 0) {
				if (nullCount === 0) {
					for (i = 0; i < resObj.length; i++) {
						free = max123 - parseInt(resObj[i].value, 10);
						resObj[i].value = (parseInt(resObj[i].value, 10) + npcDiv(rest, (4 - i)));
						rest = rest - Math.min(free, npcDiv(rest, (4 - i)));
						if ((i < 3) && (parseInt(resObj[i].value, 10) < max123)) nullCount2++;
					}
				} else {
					for (i = 0; i < resObj.length; i++) {
						if (resObj[i].value === "") {
							resObj[i].value = npcDiv(rest, nullCount);
							rest = rest - npcDiv(rest, nullCount);
							nullCount--;
						}
						if ((i < 3) && (parseInt(resObj[i].value, 10) < max123)) nullCount2++;
					}
				}
			} else {
				for (j = 0; j < resObj.length; j++) {
					if (parseInt(resObj[j].value, 10) > 0) {
						resObj[j].value = (parseInt(resObj[j].value, 10) + npcDiv(rest, notNullCount));
						rest = rest - npcDiv(rest, notNullCount);
						notNullCount--;
					}
				}
			}
			calculateRest();
			if (rest > 0) {
				if (max123 > max4) {
					for (j = 0; j < 3; j++) {
						if (parseInt(resObj[j].value, 10) < max123) {
							resObj[j].value = (parseInt(resObj[j].value, 10) + npcDiv(rest, nullCount2));
							rest = rest - npcDiv(rest, nullCount2);
							nullCount2--;
						}
					}
				} else {
					resObj[3].value = ((parseInt(resObj[3].value, 10) || 0) + (isFinite(rest) ? rest : 0));
				}
			}
			calculateRest();
		}
		function testSum() {
			if (document.getElementById("remain").innerHTML != 0) {
				document.getElementById("submitText").innerHTML = "<a href='javascript:portionOut();'><?php echo DISTRIBUTE_RESOURCES; ?></a>";
				document.getElementById("submitText").style.display = "block";
				document.getElementById("submitButton").style.display = "none";
			} else {
				document.getElementById("submitText").innerHTML = "";
				document.getElementById("submitText").style.display = "none";
				document.getElementById("submitButton").style.display = "block";
			}
		}
		var summe = <?php echo (int) $totalRes; ?>;
		var max123 = <?php echo (int) $maxstore; ?>;
		var max4 = <?php echo (int) $maxcrop; ?>;
		</script>

		<?php if (!$isWW) { ?>
		<form method="post" name="snd" action="build.php">
			<input type="hidden" name="id" value="<?php echo (int) $id; ?>" />
			<input type="hidden" name="ft" value="mk3" />
			<input type="hidden" name="t" value="3" />

			<table id="npc" class="gk-npc" cellpadding="1" cellspacing="1">
				<thead>
					<tr><th colspan="4"><?php echo NPC_TRADE; ?></th></tr>
					<tr>
						<th><?php echo htmlspecialchars($colRes, ENT_QUOTES, 'UTF-8'); ?></th>
						<th><?php echo htmlspecialchars($colHave, ENT_QUOTES, 'UTF-8'); ?></th>
						<th><?php echo htmlspecialchars($colWant, ENT_QUOTES, 'UTF-8'); ?></th>
						<th><?php echo htmlspecialchars($colRest, ENT_QUOTES, 'UTF-8'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($resData as $idx => $rd) {
						$val = $r[$idx + 1] !== '' ? $r[$idx + 1] : '';
						$orig = (int) floor($rd[1]);
					?>
					<tr>
						<td class="res">
							<a href="javascript:fillup(<?php echo (int) $idx; ?>);"><img class="<?php echo htmlspecialchars($rd[0], ENT_QUOTES, 'UTF-8'); ?>" src="img/x.gif" alt="<?php echo htmlspecialchars($rd[2], ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars($rd[2], ENT_QUOTES, 'UTF-8'); ?>" /></a>
							<?php echo htmlspecialchars($rd[2], ENT_QUOTES, 'UTF-8'); ?>
						</td>
						<td><span id="org<?php echo (int) $idx; ?>"><?php echo $orig; ?></span></td>
						<td class="sel">
							<input class="text" onkeyup="calculateRest();" name="m2[]" size="5" maxlength="<?php echo (int) $maxLen; ?>" value="<?php echo htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8'); ?>" />
							<input type="hidden" name="m1[]" value="<?php echo $orig; ?>" />
						</td>
						<td class="rem"><span id="diff<?php echo (int) $idx; ?>"><?php echo (0 - $orig); ?></span></td>
					</tr>
					<?php } ?>
					<tr>
						<td class="sum"><?php echo SUM; ?></td>
						<td><span id="org4"><?php echo (int) $totalRes; ?></span></td>
						<td><span id="newsum"><?php echo (int) $newsum; ?></span></td>
						<td><?php echo REST; ?>: <span id="remain"><?php echo (int) $remain; ?></span></td>
					</tr>
				</tbody>
			</table>

			<p id="submitButton">
				<?php if ((int) $session->userinfo['gold'] >= 3) { ?>
					<a href="javascript:document.snd.submit();"><?php echo TRADE_RESOURCES; ?> 3<img src="img/x.gif" class="gold" alt="<?php echo GOLD; ?>" title="<?php echo GOLD; ?>" /></a>
				<?php } else { ?>
					<span class="none"><?php echo TRADE_RESOURCES; ?> 3<img src="img/x.gif" class="gold_g" alt="<?php echo GOLD; ?>" title="<?php echo GOLD; ?>" /></span>
				<?php } ?>
			</p>
			<p id="submitText"></p>
		</form>
		<script type="text/javascript">testSum();</script>
		<?php } else { ?>
			<br /><br /><?php echo YOU_CAN_NAT_NPC_WW; ?>
		<?php } ?>
	<?php } ?>

</div>
