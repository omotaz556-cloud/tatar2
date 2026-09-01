	<td class="gk-td-left">
		<?php
		global $database, $village, $session;

		$gkSelfPage = basename($_SERVER['PHP_SELF']);

		$gkTribe = 1;
		if (isset($session->tribe) && (int) $session->tribe > 0) {
			$gkTribe = (int) $session->tribe;
		} elseif (isset($session->userinfo['tribe']) && (int) $session->userinfo['tribe'] > 0) {
			$gkTribe = (int) $session->userinfo['tribe'];
		}

		$gkQst = isset($_SESSION['qst']) ? (int) $_SESSION['qst'] : 0;
		$gkQstNew = isset($_SESSION['qstnew']) ? (int) $_SESSION['qstnew'] : 0;
		if ($gkQst === 0 || $gkQstNew === 1) {
			$gkCharImg = GP_LOCATE . 'img/q/l' . $gkTribe . 'g.jpg';
		} else {
			$gkCharImg = GP_LOCATE . 'img/q/l' . $gkTribe . '.jpg';
		}

		$gkCharTitle = defined('TO_THE_TASK') ? TO_THE_TASK : 'المهام';
		$gkCharSrc = $gkCharImg;
		$gkCharEsc = htmlspecialchars($gkCharSrc, ENT_QUOTES, 'UTF-8');
		$gkCharTitleEsc = htmlspecialchars($gkCharTitle, ENT_QUOTES, 'UTF-8');

		echo '<div id="anm" style="width:120px;height:140px;visibility:hidden;"></div>';
		echo '<div class="gk-char-portrait" data-char-src="' . $gkCharEsc . '" data-char-title="' . $gkCharTitleEsc
			. '" style="display:block;text-align:center;margin:0 auto 8px;">'
			. '<img id="gkCharImg" onclick="qst_handle();" src="'
			. $gkCharEsc . '" title="' . $gkCharTitleEsc
			. '" alt="' . $gkCharTitleEsc
			. '" style="display:block;height:174px;width:auto;max-width:145px;margin:0 auto;cursor:pointer;border:0;" /></div>';
		echo '<div id="qge" style="display:none;height:0;overflow:hidden;visibility:hidden;" aria-hidden="true"></div>';

		$GLOBALS['gkQuestPortraitExternal'] = true;
		include dirname(__DIR__) . '/quest.tpl';

		if (!isset($vDisplayName)) {
			if (!empty($village) && is_object($village) && isset($village->vname)) {
				$vDisplayName = function_exists('tz_display_village_name')
					? tz_display_village_name($village->vname, $session->username ?? null)
					: $village->vname;
			} else {
				$vDisplayName = '';
			}
		}
		?>
		<?php include __DIR__ . '/gk_village_list.tpl'; ?>
	</td>
