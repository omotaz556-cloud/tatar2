<?php

/* Left column: tribe quest portrait + village list (same logic as gk_left.tpl) */

global $session, $database, $village, $showQuest;



$gkSelfPage = basename($_SERVER['PHP_SELF']);

$vDisplayName = isset($vDisplayName) ? $vDisplayName : (

	function_exists('tz_display_village_name')

		? tz_display_village_name($village->vname, $session->username ?? null)

		: $village->vname

);

?>

<aside class="gk-side-left">

<?php

include dirname(__DIR__) . '/quest.tpl';

if (empty($showQuest)) {

	$tribeFall = 1;

	if (isset($session->tribe) && (int) $session->tribe > 0) {

		$tribeFall = (int) $session->tribe;

	} elseif (isset($session->userinfo['tribe']) && (int) $session->userinfo['tribe'] > 0) {

		$tribeFall = (int) $session->userinfo['tribe'];

	}

	$qImg = GP_LOCATE . 'img/q/l' . $tribeFall . '.jpg';

	echo '<div id="qge"><img onclick="qst_handle();" src="'

		. htmlspecialchars($qImg, ENT_QUOTES, 'UTF-8')

		. '" title="' . (defined('TO_THE_TASK') ? TO_THE_TASK : 'المهام')

		. '" alt="" style="height:174px;cursor:pointer" /></div>';

}

?>

	<div class="gk-vlist">

		<div class="gk-vlist-title">قائمة القرى</div>

<?php

$rows = method_exists($database, 'getArrayMemberVillage')

	? $database->getArrayMemberVillage($session->uid)

	: array();

$cur = isset($_SESSION['wid']) ? (int) $_SESSION['wid'] : (int) $village->wid;

if (is_array($rows) && count($rows) > 0) {

	foreach ($rows as $row) {

		$vid = (int) $row['wref'];

		$name = htmlspecialchars(tz_display_village_name($row['name'], $session->username ?? null), ENT_QUOTES, 'UTF-8');

		$cap = (!empty($row['capital'])) ? ' <span class="gk-cap">(عاصمة)</span>' : '';

		$cx = isset($row['x']) ? (int) $row['x'] : 0;

		$cy = isset($row['y']) ? (int) $row['y'] : 0;

		$cls = ($vid === $cur) ? ' is-current' : '';

		echo '<a class="gk-vitem' . $cls . '" href="' . htmlspecialchars($gkSelfPage, ENT_QUOTES, 'UTF-8') . '?newdid=' . $vid . '">'

			. $name . $cap

			. ' <span class="gk-coords">(' . $cx . '|' . $cy . ')</span>'

			. '</a>';

	}

} else {

	$vx = isset($village->coor['x']) ? (int) $village->coor['x'] : 0;

	$vy = isset($village->coor['y']) ? (int) $village->coor['y'] : 0;

	echo '<a class="gk-vitem is-current" href="' . htmlspecialchars($gkSelfPage, ENT_QUOTES, 'UTF-8') . '">'

		. htmlspecialchars($vDisplayName, ENT_QUOTES, 'UTF-8')

		. ' <span class="gk-coords">(' . $vx . '|' . $vy . ')</span></a>';

}

?>

	</div>

</aside>


