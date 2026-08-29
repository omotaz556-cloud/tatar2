	<td class="gk-td-left">
		<?php
		global $database, $village, $session;

		$gkSelfPage = basename($_SERVER['PHP_SELF']);
		$gkRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
		$gkIsPlusPage = ($gkSelfPage === 'plus.php' && !empty($GLOBALS['gkShell']) && $gkRtl);
		$gkIsStatPage = ($gkSelfPage === 'statistiken.php' && !empty($GLOBALS['gkShell']) && $gkRtl
			&& !empty($GLOBALS['gkStatLiteralPage']));
		$gkIsBerichtePage = ($gkSelfPage === 'berichte.php' && !empty($GLOBALS['gkShell']) && $gkRtl
			&& !empty($GLOBALS['gkBerichteLiteralPage']));
		$gkIsNachrichtenPage = ($gkSelfPage === 'nachrichten.php' && !empty($GLOBALS['gkShell']) && $gkRtl
			&& !empty($GLOBALS['gkNachrichtenLiteralPage']));
		$gkIsSpielerPage = ($gkSelfPage === 'spieler.php' && !empty($GLOBALS['gkShell']) && $gkRtl
			&& !empty($GLOBALS['gkSpielerLiteralPage']));
		$gkIsFeedingPage = ($gkSelfPage === 'feeding.php' && !empty($GLOBALS['gkShell']) && $gkRtl
			&& !empty($GLOBALS['gkSpielerLiteralPage']));
		$gkSideMyVs = ($gkIsPlusPage || $gkIsStatPage || $gkIsBerichtePage || $gkIsNachrichtenPage || $gkIsSpielerPage || $gkIsFeedingPage);

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
		if ($gkCharSrc !== '' && $gkCharSrc[0] !== '/' && !preg_match('#^https?://#i', $gkCharSrc)) {
			$gkCharSrc = '/' . ltrim($gkCharSrc, '/');
		}
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
		<?php if ($gkSideMyVs) {
			$gkCapLabel = defined('TZ_PROF_CAP_SHORT') ? TZ_PROF_CAP_SHORT : (defined('CAPITAL1') ? CAPITAL1 : 'عاصمة');
			$gkCx = (!empty($village) && is_object($village) && isset($village->coor['x'])) ? (int) $village->coor['x'] : 0;
			$gkCy = (!empty($village) && is_object($village) && isset($village->coor['y'])) ? (int) $village->coor['y'] : 0;
			$gkIsCap = (!empty($village) && is_object($village) && !empty($village->capital));
			$gkSideClass = $gkIsPlusPage ? 'gk-plus-side' : ($gkIsBerichtePage ? 'gk-berichte-side' : ($gkIsNachrichtenPage ? 'gk-nachrichten-side' : (($gkIsSpielerPage || $gkIsFeedingPage) ? 'gk-spieler-side' : 'gk-stat-side')));
		?>
		<div class="<?php echo $gkSideClass; ?>">
			<table class="MyVs"><tbody>
				<tr><th colspan="2"><a href="dorf3.php" class="C_K">قائمة القرى</a></th></tr>
				<?php
				$gkVilRows = (isset($database) && is_object($database) && method_exists($database, 'getArrayMemberVillage'))
					? $database->getArrayMemberVillage($session->uid)
					: array();
				if (is_array($gkVilRows) && count($gkVilRows) > 0) {
					foreach ($gkVilRows as $gkVilRow) {
						$gkVid = (int) $gkVilRow['wref'];
						$gkVnm = htmlspecialchars(tz_display_village_name($gkVilRow['name'], $session->username ?? null), ENT_QUOTES, 'UTF-8');
						$gkVx = isset($gkVilRow['x']) ? (int) $gkVilRow['x'] : 0;
						$gkVy = isset($gkVilRow['y']) ? (int) $gkVilRow['y'] : 0;
						$gkVcap = !empty($gkVilRow['capital']);
						?>
				<tr><th><a class="C_O" href="<?php echo htmlspecialchars($gkSelfPage, ENT_QUOTES, 'UTF-8'); ?>?newdid=<?php echo $gkVid; ?>"><?php echo $gkVnm; ?></a><?php if ($gkVcap) { ?><a class="fl"> (<?php echo $gkCapLabel; ?>) </a><?php } ?></th><th class="gk-vcoords"><a href="build.php?id=39" title="<?php echo defined('MARKET') ? MARKET : 'السوق'; ?>"><p class="Rs x6"></p></a><a href="karte.php?x=<?php echo $gkVx; ?>&amp;y=<?php echo $gkVy; ?>" title="<?php echo defined('MAP') ? MAP : 'الخريطة'; ?>"><p class="Rs x5"></p></a><b><bdi>(<?php echo $gkVx; ?>|<?php echo $gkVy; ?>)</bdi></b></th></tr>
						<?php
					}
				} else {
					?>
				<tr><th><a class="C_O" href="dorf1.php"><?php echo htmlspecialchars($vDisplayName, ENT_QUOTES, 'UTF-8'); ?></a><?php if ($gkIsCap) { ?><a class="fl"> (<?php echo $gkCapLabel; ?>) </a><?php } ?></th><th class="gk-vcoords"><a href="build.php?id=39" title="<?php echo defined('MARKET') ? MARKET : 'السوق'; ?>"><p class="Rs x6"></p></a><a href="karte.php?x=<?php echo $gkCx; ?>&amp;y=<?php echo $gkCy; ?>" title="<?php echo defined('MAP') ? MAP : 'الخريطة'; ?>"><p class="Rs x5"></p></a><b><bdi>(<?php echo $gkCx; ?>|<?php echo $gkCy; ?>)</bdi></b></th></tr>
					<?php
				}
				?>
			</tbody></table>
		</div>
		<?php } ?>
		<?php if (!$gkSideMyVs) { ?>
		<div class="gk-vlist">
			<div class="ttl">قائمة القرى</div>
			<?php
			$rows = (isset($database) && is_object($database) && method_exists($database, 'getArrayMemberVillage'))
				? $database->getArrayMemberVillage($session->uid)
				: array();
			$cur = isset($_SESSION['wid']) ? (int) $_SESSION['wid'] : (
				(!empty($village) && is_object($village) && isset($village->wid)) ? (int) $village->wid : 0
			);
			if (is_array($rows) && count($rows) > 0) {
				$gkBuildQs = '';
				if ($gkSelfPage === 'build.php') {
					if (isset($_GET['id']) && ctype_digit((string) $_GET['id'])) {
						$gkBuildQs .= '&id=' . (int) $_GET['id'];
					} elseif (isset($_GET['gid']) && ctype_digit((string) $_GET['gid'])) {
						$gkBuildQs .= '&gid=' . (int) $_GET['gid'];
					}
					if (isset($_GET['t']) && ctype_digit((string) $_GET['t'])) {
						$gkBuildQs .= '&t=' . (int) $_GET['t'];
					}
					if (isset($_GET['land'])) {
						$gkBuildQs .= '&land';
					}
					if (isset($_GET['t4tab']) && in_array($_GET['t4tab'], array('items', 'adventures', 'auction'), true)) {
						$gkBuildQs .= '&t4tab=' . $_GET['t4tab'];
					}
				}
				foreach ($rows as $row) {
					$vid = (int) $row['wref'];
					$isCur = ($vid === $cur);
					$nm = htmlspecialchars(tz_display_village_name($row['name'], $session->username ?? null), ENT_QUOTES, 'UTF-8');
					$cap = !empty($row['capital']) ? ' <span class="fl">(عاصمة)</span>' : '';
					$cx = isset($row['x']) ? (int) $row['x'] : 0;
					$cy = isset($row['y']) ? (int) $row['y'] : 0;
					$cls = $isCur ? ' class="C_O"' : '';
					echo '<a' . $cls . ' href="' . htmlspecialchars($gkSelfPage, ENT_QUOTES, 'UTF-8') . '?newdid=' . $vid . $gkBuildQs . '">' . $nm . $cap
						. ' <span dir="ltr">(' . $cx . '|' . $cy . ')</span></a>';
				}
			} else {
				$cx = (!empty($village) && is_object($village) && isset($village->coor['x'])) ? (int) $village->coor['x'] : 0;
				$cy = (!empty($village) && is_object($village) && isset($village->coor['y'])) ? (int) $village->coor['y'] : 0;
				echo '<a class="C_O" href="' . htmlspecialchars($gkSelfPage, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($vDisplayName, ENT_QUOTES, 'UTF-8')
					. ' <span dir="ltr">(' . $cx . '|' . $cy . ')</span></a>';
			}
			?>
		</div>
		<?php } ?>
	</td>
