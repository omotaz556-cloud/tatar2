<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : plus.php                      	                           ##
##  Type           : In Game Plus Page                                         ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Dzoki 						                               ##
##  Refactored by  : Shadow                                                    ##
##  Redesign by    : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  URLs:          : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################


use App\Utils\AccessLogger;

include_once("GameEngine/Village.php");
include_once("GameEngine/CentralGold.php");
include_once("GameEngine/PaymentShop.php");
AccessLogger::logRequest();

if(isset($_GET['newdid'])) {
	$_SESSION['wid'] = $_GET['newdid'];
	header("Location: ".$_SERVER['PHP_SELF']);
	exit;
}
else $building->procBuild($_GET);

// Gold shop: promo-code redemption (player-side). Best-effort; the engine
// self-creates its tables and validates the code (active / expiry / uses /
// once-per-player) before granting gold.
$promoMsg = '';
$promoOk  = false;
$purchaseMsg = '';
$purchaseOk = false;
$plusRtlMsg = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
if (isset($_POST['refund_purchase']) && isset($session->uid)) {
	$purchaseOk = PaymentShop::requestRefund($session->uid, $_POST['refund_purchase'], $_POST['refund_reason'] ?? '');
	$purchaseMsg = $purchaseOk
		? ($plusRtlMsg ? 'تم إرسال طلب الاسترداد للمراجعة.' : 'Refund request submitted for review.')
		: ($plusRtlMsg ? 'لا يمكن طلب استرداد لهذا الطلب.' : 'This purchase cannot be refunded.');
}

// Beginner-protection extensions: five account-wide opportunities.
// Uses central (paid) gold when CENTRAL_GOLD_* is configured; otherwise
// falls back to local users.gold — same as the rest of Plus purchases.
// NOTE: never call install_is_rtl() here (install-only helper → fatal).
$protectionMsg = '';
$protectionOk = false;
if (isset($_POST['buy_protection']) && isset($session->uid)) {
	$protectionOptions = [
		1 => ['seconds' => 86400, 'cost' => 5000],
		2 => ['seconds' => 86400, 'cost' => 7000],
		3 => ['seconds' => 43200, 'cost' => 9000],
		4 => ['seconds' => 28800, 'cost' => 10500],
		5 => ['seconds' => 28800, 'cost' => 12500],
	];
	$option = (int) $_POST['buy_protection'];
	$uid = (int) $session->uid;
	$cost = 0;
	$seconds = 0;
	$usedCentral = false;
	$email = '';

	if (!isset($protectionOptions[$option])) {
		$protectionMsg = $plusRtlMsg ? 'اختيار حماية غير صالح.' : 'Invalid protection option.';
	} else {
		$cost = (int) $protectionOptions[$option]['cost'];
		$seconds = (int) $protectionOptions[$option]['seconds'];

		$database->query("CREATE TABLE IF NOT EXISTS " . TB_PREFIX . "protection_purchases (
			uid int(11) NOT NULL,
			uses tinyint(2) NOT NULL DEFAULT 0,
			PRIMARY KEY (uid)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
		$row = $database->query_return("SELECT uses FROM " . TB_PREFIX . "protection_purchases WHERE uid = $uid LIMIT 1");
		$uses = $row ? (int) $row[0]['uses'] : 0;

		if ($uses >= 5) {
			$protectionMsg = $plusRtlMsg
				? 'لقد استخدمت فرص الحماية الخمس.'
				: 'All five protection opportunities have been used.';
		} else {
			$goldPaid = false;
			$email = trim((string) ($session->userinfo['email'] ?? $session->email ?? ''));

			if (class_exists('CentralGold') && CentralGold::isConfigured()) {
				if ($email === '' || !CentralGold::isEmailVerified($email)) {
					$protectionMsg = $plusRtlMsg
						? 'يجب تأكيد البريد الإلكتروني لاستخدام الذهب المشتَرى.'
						: 'Verify your email before using purchased gold.';
				} else {
					$goldResult = CentralGold::debit(
						$email,
						$session->username,
						$uid,
						$cost,
						'beginner_protection',
						'Protection opportunity ' . ($uses + 1)
					);
					if (!$goldResult[0]) {
						$protectionMsg = $plusRtlMsg
							? 'تحتاج إلى ذهب مشتَرى كافٍ لتفعيل الحماية.'
							: 'You need enough paid gold to activate protection.';
					} else {
						$goldPaid = true;
						$usedCentral = true;
					}
				}
			} else {
				// Local gold (Plus page standard path when central gold is off).
				mysqli_query(
					$database->dblink,
					"UPDATE " . TB_PREFIX . "users
					 SET gold = gold - $cost
					 WHERE id = $uid AND gold >= $cost
					 LIMIT 1"
				);
				if (mysqli_affected_rows($database->dblink) === 1) {
					$goldPaid = true;
					if (isset($session->gold)) {
						$session->gold -= $cost;
						$_SESSION['gold'] = $session->gold;
					}
				} else {
					$protectionMsg = $plusRtlMsg
						? 'تحتاج إلى ذهب كافٍ لتفعيل الحماية.'
						: 'You need enough gold to activate protection.';
				}
			}

			if ($goldPaid) {
				mysqli_begin_transaction($database->dblink);
				$updated = $database->query(
					"INSERT INTO " . TB_PREFIX . "protection_purchases (uid, uses)
					 VALUES ($uid, 1)
					 ON DUPLICATE KEY UPDATE uses = uses + 1"
				);
				$updated = $updated && $database->query(
					"UPDATE " . TB_PREFIX . "users
					 SET protect = GREATEST(protect, UNIX_TIMESTAMP()) + $seconds
					 WHERE id = $uid
					 LIMIT 1"
				);
				if ($updated) {
					mysqli_commit($database->dblink);
					$protectionOk = true;
					$protectionMsg = $plusRtlMsg
						? 'تم تفعيل حماية اللاعب الجديد بنجاح.'
						: 'Beginner protection activated successfully.';
					if (method_exists($database, 'clearUserCache')) {
						$database->clearUserCache($uid);
					}
				} else {
					mysqli_rollback($database->dblink);
					if ($usedCentral) {
						CentralGold::credit(
							$email,
							$session->username,
							$uid,
							$cost,
							'beginner_protection_refund',
							'Protection update failed'
						);
					} else {
						mysqli_query(
							$database->dblink,
							"UPDATE " . TB_PREFIX . "users
							 SET gold = gold + $cost
							 WHERE id = $uid
							 LIMIT 1"
						);
						if (isset($session->gold)) {
							$session->gold += $cost;
							$_SESSION['gold'] = $session->gold;
						}
					}
					$protectionMsg = $plusRtlMsg
						? 'تعذر تفعيل الحماية، وتمت إعادة الذهب.'
						: 'Protection could not be activated; the gold was refunded.';
				}
			}
		}
	}
}
if (isset($_POST['redeem_code']) && class_exists('GoldShop')) {
    $__uid = isset($session) && isset($session->uid) ? (int)$session->uid : 0;
    $rr = GoldShop::redeem($__uid, $_POST['redeem_code']);
    $promoOk  = $rr[0];
    $promoMsg = $rr[1];
}

// Plus page: buy resources with account gold (N of each resource per 1 gold spent).
$buyResMsg = '';
$buyResOk  = false;
if (isset($_POST['buy_gold_resources']) && isset($session->uid) && !empty($village)) {
	$goldResPurchaseOn = !defined('GOLD_RES_PURCHASE_ENABLED') || GOLD_RES_PURCHASE_ENABLED;

	$goldSpend = 0;
	if (isset($_POST['goldamt'])) {
		$goldSpend = (int) $_POST['goldamt'];
	} elseif (isset($_POST['X']) && is_array($_POST['X']) && isset($_POST['X'][0])) {
		$goldSpend = (int) $_POST['X'][0];
	}

	$unit    = defined('GOLD_RES_UNIT') ? max(1, (int) GOLD_RES_UNIT) : 20000;
	$minGold = defined('GOLD_RES_MIN_GOLD') ? max(1, (int) GOLD_RES_MIN_GOLD) : 1;
	$maxGold = defined('GOLD_RES_MAX_GOLD') ? max(0, (int) GOLD_RES_MAX_GOLD) : 0;
	$uid     = (int) $session->uid;
	$wid     = (int) $village->wid;

	if (!$goldResPurchaseOn) {
		$buyResMsg = $plusRtlMsg ? GOLD_BUY_ERR_DISABLED : 'This feature is currently disabled.';
	} elseif (method_exists($session, 'sitterCan') && !$session->sitterCan(SITTER_PERM_GOLD)) {
		$buyResMsg = $plusRtlMsg
			? (defined('SITTER_P_DENIED') ? SITTER_P_DENIED : 'لا تملك صلاحية إنفاق الذهب.')
			: 'Your sitter permissions do not allow this action.';
	} elseif ($maxGold > 0) {
		$goldSpend = min($goldSpend, $maxGold);
	}
	if ($buyResMsg === '' && $goldSpend < $minGold) {
		$buyResMsg = $plusRtlMsg ? GOLD_BUY_ERR_AMOUNT : 'Please enter a valid gold amount.';
	} elseif ($buyResMsg === '') {
		$wwvillage = $database->getResourceLevel($wid);
		if (!empty($wwvillage['f99t']) && (int) $wwvillage['f99t'] === 40) {
			$buyResMsg = $plusRtlMsg ? YOU_CAN_NAT_NPC_WW : 'Cannot buy resources in a WW village.';
		} else {
			// mode 0 = lookup by user id (mode 1 searches by username)
			$userGold = (int) $database->getUserField($uid, 'gold', 0, false);
			if ($userGold < $goldSpend) {
				$buyResMsg = $plusRtlMsg ? GOLD_BUY_ERR_GOLD : 'Not enough gold.';
			} else {
			$perResource = $goldSpend * $unit;
			$maxstore = (int) $village->maxstore;
			$maxcrop  = (int) $village->maxcrop;
			$current = [
				'wood' => (int) round($village->awood),
				'clay' => (int) round($village->aclay),
				'iron' => (int) round($village->airon),
				'crop' => (int) round($village->acrop),
			];
			$add = [
				'wood' => max(0, min($perResource, $maxstore - $current['wood'])),
				'clay' => max(0, min($perResource, $maxstore - $current['clay'])),
				'iron' => max(0, min($perResource, $maxstore - $current['iron'])),
				'crop' => max(0, min($perResource, $maxcrop - $current['crop'])),
			];

			if ($add['wood'] + $add['clay'] + $add['iron'] + $add['crop'] <= 0) {
				$buyResMsg = $plusRtlMsg ? GOLD_BUY_ERR_FULL : 'Storage is full.';
			} elseif (!$database->spendGold($uid, $goldSpend, 'Gold resource purchase (plus)')) {
				$buyResMsg = $plusRtlMsg ? GOLD_BUY_ERR_GOLD : 'Not enough gold.';
			} else {
				$database->setVillageField(
					$wid,
					['wood', 'clay', 'iron', 'crop'],
					[
						$current['wood'] + $add['wood'],
						$current['clay'] + $add['clay'],
						$current['iron'] + $add['iron'],
						$current['crop'] + $add['crop'],
					]
				);
				$database->addGoldFinLog(
					$wid,
					$uid,
					'Gold resource purchase',
					-$goldSpend,
					sprintf(
						'wood +%d, clay +%d, iron +%d, crop +%d (each per %d gold)',
						$add['wood'], $add['clay'], $add['iron'], $add['crop'], $unit
					)
				);
				$session->gold = $userGold - $goldSpend;
				$_SESSION['gold'] = $session->gold;
				$village->awood = $current['wood'] + $add['wood'];
				$village->aclay = $current['clay'] + $add['clay'];
				$village->airon = $current['iron'] + $add['iron'];
				$village->acrop = $current['crop'] + $add['crop'];
				if (method_exists($database, 'clearUserCache')) {
					$database->clearUserCache($uid);
				}
				$buyResOk = true;
				$buyResMsg = $plusRtlMsg
					? ('تم شراء الموارد: +' . number_format($add['wood']) . ' خشب، +' . number_format($add['clay'])
						. ' طين، +' . number_format($add['iron']) . ' حديد، +' . number_format($add['crop']) . ' قمح.')
					: ('Resources purchased: +' . $add['wood'] . ' wood, +' . $add['clay']
						. ' clay, +' . $add['iron'] . ' iron, +' . $add['crop'] . ' crop.');
			}
			}
		}
	}
}

$transferMsg = '';
$transferOk = false;
$gkPostedPlayer = '';
$gkPostedAmount = '';
$gkPostedWorld = '';
$gkTransferable = 0;

$gkWorldKey = class_exists('CentralGold') ? CentralGold::worldKey() : (defined('SQL_DB') ? (string) SQL_DB : 'world');
$gkWorldLabel = defined('SERVER_NAME') ? SERVER_NAME : 'Novaterra';
$gkWorldOptions = array($gkWorldKey => $gkWorldLabel);

if (!function_exists('gk_plus_transferable_gold')) {
	function gk_plus_transferable_gold($database, $session)
	{
		if (!isset($session->uid) || !isset($database) || !is_object($database)) {
			return 0;
		}

		$uid = (int) $session->uid;
		$email = trim((string) ($session->userinfo['email'] ?? $session->email ?? ''));

		if (class_exists('CentralGold') && CentralGold::isConfigured() && $email !== '') {
			return max(0, (int) CentralGold::balance($email));
		}

		$res = mysqli_query(
			$database->dblink,
			'SELECT gold FROM ' . TB_PREFIX . 'users WHERE id = ' . $uid . ' LIMIT 1'
		);
		$row = $res ? mysqli_fetch_assoc($res) : null;

		return $row ? max(0, (int) $row['gold']) : 0;
	}
}

if (!function_exists('gk_plus_verify_user_password')) {
	function gk_plus_verify_user_password($database, $username, $password)
	{
		if (!isset($database) || !is_object($database) || !method_exists($database, 'getUserArray')) {
			return false;
		}

		$username = trim((string) $username);
		$password = (string) $password;
		if ($username === '' || $password === '') {
			return false;
		}

		$user = $database->getUserArray($username, 0, false);
		if (!is_array($user) || empty($user['password'])) {
			return false;
		}

		$pwOk = password_verify($password, (string) $user['password']);
		if (!$pwOk && empty($user['is_bcrypt'])) {
			$pwOk = ((string) $user['password'] === md5($password));
		}

		return $pwOk;
	}
}

if (isset($session->uid)) {
	$gkTransferable = gk_plus_transferable_gold($database, $session);
}

if (isset($_POST['gk_gold_transfer']) && isset($session->uid)) {
	$uid = (int) $session->uid;
	$gkPostedPlayer = trim((string) ($_POST['gk_transfer_player'] ?? ''));
	$gkPostedAmount = trim((string) ($_POST['gk_transfer_amount'] ?? ''));
	$gkPostedWorld = trim((string) ($_POST['gk_transfer_world'] ?? $gkWorldKey));
	$gkTransferPassword = (string) ($_POST['gk_transfer_password'] ?? '');
	$amount = (int) preg_replace('/\D+/', '', $gkPostedAmount);

	if (!gk_plus_verify_user_password($database, $session->username, $gkTransferPassword)) {
		$transferMsg = $plusRtlMsg
			? 'كلمة المرور غير صحيحة.'
			: 'Incorrect password.';
	} elseif ($gkPostedPlayer === '' || strcasecmp($gkPostedPlayer, $session->username) === 0) {
		$transferMsg = $plusRtlMsg
			? 'أدخل اسم لاعب صالح غير اسمك.'
			: 'Enter a valid player name other than your own.';
	} elseif ($amount <= 0) {
		$transferMsg = $plusRtlMsg
			? 'أدخل كمية ذهب صالحة.'
			: 'Enter a valid gold amount.';
	} elseif ($gkPostedWorld !== $gkWorldKey) {
		$transferMsg = $plusRtlMsg
			? 'يمكن التحويل داخل هذا العالم فقط حالياً.'
			: 'Transfers are only supported on this world for now.';
	} elseif ($amount > $gkTransferable) {
		$transferMsg = $plusRtlMsg
			? 'لا تملك ذهباً قابلاً للتحويل بهذه الكمية.'
			: 'You do not have enough transferable gold.';
	} else {
		$targetUser = $database->getUserArray($gkPostedPlayer, 0, false);
		if (!is_array($targetUser) || empty($targetUser['id'])) {
			$transferMsg = $plusRtlMsg
				? 'لا يوجد لاعب بهذا الاسم.'
				: 'No player with that name exists.';
		} elseif ((int) ($targetUser['access'] ?? 0) === (defined('BANNED') ? BANNED : 0)) {
			$transferMsg = $plusRtlMsg
				? 'لا يمكن التحويل إلى هذا اللاعب.'
				: 'Cannot transfer gold to this player.';
		} else {
			$targetUid = (int) $targetUser['id'];
			$fromEmail = trim((string) ($session->userinfo['email'] ?? $session->email ?? ''));
			$toEmail = trim((string) ($targetUser['email'] ?? ''));
			$usedCentral = false;
			$transferDone = false;

			if (class_exists('CentralGold') && CentralGold::isConfigured()) {
				if ($fromEmail === '' || $toEmail === '' || strpos($fromEmail, '@') === false || strpos($toEmail, '@') === false) {
					$transferMsg = $plusRtlMsg
						? 'يجب أن يكون لدى كلا اللاعبين بريد إلكتروني صالح.'
						: 'Both players must have a valid email address.';
				} elseif (!CentralGold::isEmailVerified($fromEmail) || !CentralGold::isEmailVerified($toEmail)) {
					$transferMsg = $plusRtlMsg
						? 'يجب تأكيد البريد الإلكتروني لكلا اللاعبين قبل تحويل الذهب المدفوع.'
						: 'Both email addresses must be verified before paid gold can be transferred.';
				} else {
					list($centralOk, $centralMsg) = CentralGold::transfer(
						$fromEmail,
						$session->username,
						$uid,
						$toEmail,
						$targetUser['username'],
						$targetUid,
						$amount,
						0,
						'Player transfer on ' . $gkWorldLabel
					);
					if (!$centralOk) {
						$transferMsg = $plusRtlMsg
							? 'فشل التحويل: ' . $centralMsg
							: ('Transfer failed: ' . $centralMsg);
					} else {
						$usedCentral = true;
						$transferDone = true;
					}
				}
			} else {
				$transferDone = true;
			}

			if ($transferDone) {
				mysqli_begin_transaction($database->dblink);

				$senderOk = $database->spendGold($uid, $amount, 'Gold transfer to ' . $gkPostedPlayer);
				$receiverOk = false;
				if ($senderOk) {
					$receiverOk = mysqli_query(
						$database->dblink,
						'UPDATE ' . TB_PREFIX . 'users SET gold = gold + ' . $amount
							. ' WHERE id = ' . $targetUid . ' LIMIT 1'
					) && mysqli_affected_rows($database->dblink) === 1;
				}

				if ($senderOk && $receiverOk) {
					mysqli_commit($database->dblink);
					if (method_exists($database, 'addGoldFinLog')) {
						$database->addGoldFinLog(
							0,
							$uid,
							'Gold transfer out',
							-$amount,
							'To ' . $gkPostedPlayer
						);
						$database->addGoldFinLog(
							0,
							$targetUid,
							'Gold transfer in',
							$amount,
							'From ' . $session->username
						);
					}
					if (method_exists($database, 'clearUserCache')) {
						$database->clearUserCache($uid);
						$database->clearUserCache($targetUid, $gkPostedPlayer);
					}
					if (isset($session->gold)) {
						$session->gold = max(0, (int) $session->gold - $amount);
						$_SESSION['gold'] = $session->gold;
					}
					$gkTransferable = gk_plus_transferable_gold($database, $session);
					$transferOk = true;
					$transferMsg = $plusRtlMsg
						? ('تم تحويل ' . number_format($amount) . ' ذهب إلى ' . $gkPostedPlayer . '.')
						: ('Transferred ' . $amount . ' gold to ' . $gkPostedPlayer . '.');
					$gkPostedAmount = '';
					$gkPostedPlayer = '';
				} else {
					mysqli_rollback($database->dblink);
					if ($usedCentral && class_exists('CentralGold') && CentralGold::isConfigured() && $fromEmail !== '') {
						CentralGold::credit(
							$fromEmail,
							$session->username,
							$uid,
							$amount,
							'transfer_refund',
							'Local transfer failed'
						);
					}
					$transferMsg = $plusRtlMsg
						? 'فشل التحويل. حاول مرة أخرى.'
						: 'Transfer failed. Please try again.';
				}
			}
		}
	}
}

$gkShell = true;
include_once('GameEngine/GreekPlus.php');
$gkPlusCss = 'css/greek_maxb_plus.css';
$gkPlusCssVer = is_file(__DIR__ . '/' . $gkPlusCss) ? (int) @filemtime(__DIR__ . '/' . $gkPlusCss) : time();
$gkPageTitle = SERVER_NAME . ' &raquo; &raquo; &raquo; PLUS ';
if (isset($_GET['id'])) {
	switch ((int) $_GET['id']) {
		case 2: $gkPageTitle .= 'Advantages'; break;
		case 3: $gkPageTitle .= 'Gold'; break;
		case 4: $gkPageTitle .= 'FAQ'; break;
		case 5: $gkPageTitle .= 'Earn Gold'; break;
		case 16: $gkPageTitle .= 'Gold Transfer'; break;
		case 1:
		default: $gkPageTitle .= 'Tariffs'; break;
	}
} else {
	$gkPageTitle .= 'Tariffs';
}
tz_greek_shell_head($gkPageTitle, 'pg-plus', array(
	'includeNew2Js' => false,
	'extraCss' => array($gkPlusCss . '?v=' . $gkPlusCssVer),
));
tz_greek_shell_open('', array('contentWrap' => false));

// Invite email: process BEFORE templates so invite.tpl can show feedback.
$inviteMsg = '';
$inviteOk = false;
if (isset($_POST['mail']) && isset($session->uid)) {
	$email = trim((string) $_POST['mail']);
	$text = isset($_POST['text']) ? trim((string) $_POST['text']) : '';
	if (
		strpos($email, "\r") === false &&
		strpos($email, "\n") === false &&
		filter_var($email, FILTER_VALIDATE_EMAIL)
	) {
		$text = substr($text, 0, 2000);
		$text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
		if (isset($mailer) && method_exists($mailer, 'sendInvite')) {
			$mailer->sendInvite($email, $session->uid, $text);
			$inviteOk = true;
			$inviteMsg = $plusRtlMsg
				? 'تم إرسال الدعوة بنجاح.'
				: 'Invite sent successfully.';
		}
	} else {
		$inviteMsg = $plusRtlMsg
			? 'عنوان البريد غير صالح.'
			: 'Invalid email address.';
	}
}

if (isset($_GET['id'])) {
	$id = preg_replace("/[^a-zA-Z0-9_-]/", "", $_GET['id']);
} elseif (isset($_GET['s'])) {
	// Not-enough-gold links use ?s=1 → open packages/shop.
	$id = '1';
} else {
	$id = '';
}

if ($id === '6') {
	header('Location: plus.php?id=3');
	exit;
}

if ($id === '' || $id === '1') {
	include('Templates/Plus/1.tpl');
}
if($id == 2){
	include ("Templates/Plus/2.tpl");
}
if($id == 3){
	include ("Templates/Plus/3.tpl");
}
if($id == 4){
	include ("Templates/Plus/4.tpl");
}
if(isset($_GET['mail']) && $id == 5){
	include ("Templates/Plus/invite.tpl");
}else if($id == 5){
	include ("Templates/Plus/5.tpl");
}
if($id == 16){
	include ("Templates/Plus/16.tpl");
}
if($id == 7){
	include ("Templates/Plus/7.tpl");
}
if($id == 8){
	include ("Templates/Plus/8.tpl");
}
if($id == 9){
	include ("Templates/Plus/9.tpl");
}
if($id == 10){
	include ("Templates/Plus/10.tpl");
}
if($id == 11){
	include ("Templates/Plus/11.tpl");
}
if($id == 12){
	include ("Templates/Plus/12.tpl");
}
/**
 * BUG REPARAT: id-urile 13 si 14 includeau Templates/Plus/13.tpl si 14.tpl,
 * fisiere care NU EXISTA in proiect. Orice acces la plus.php?id=13 dadea
 * eroare fatala si pagina alba.
 *
 * Verificam existenta inainte de includere; daca lipsesc, aratam pagina
 * obisnuita de functii Plus, ca jucatorul sa aiba unde merge.
 */
if($id == 13 || $id == 14){
	$plusExtraTpl = "Templates/Plus/" . (int) $id . ".tpl";

	if (is_file($plusExtraTpl)) {
		include ($plusExtraTpl);
	} else {
		include ("Templates/Plus/3.tpl");
	}
}
if($id == 15){
	include ("Templates/Plus/15.tpl");
}
if (is_numeric($id) && (int) $id > 16) {
	include('Templates/Plus/3.tpl');
}
?>
<?php
include __DIR__ . '/Templates/Plus/pmenu_close.tpl';
tz_greek_shell_close(array('buildPopup' => false, 'timer' => $start_timer));