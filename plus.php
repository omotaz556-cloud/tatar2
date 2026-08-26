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

// Player gold transfer (Plus tab: تحويل الذهب).
$transferMsg = '';
$transferOk = false;
if (isset($_POST['transfer_gold']) && isset($session->uid)) {
	$plusRtlMsg = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
	$csrfOk = isset($_POST['csrf'], $_SESSION['plus_csrf'])
		&& hash_equals((string) $_SESSION['plus_csrf'], (string) $_POST['csrf']);
	$confirmed = !empty($_POST['confirm_transfer']);
	$toName = trim((string) ($_POST['to_username'] ?? ''));
	$amount = (int) ($_POST['amount'] ?? 0);
	$fromUid = (int) $session->uid;

	if (!$csrfOk) {
		$transferMsg = $plusRtlMsg ? 'طلب غير صالح. أعد المحاولة.' : 'Invalid request. Please try again.';
	} elseif (!$confirmed) {
		$transferMsg = $plusRtlMsg ? 'يجب تأكيد التحويل.' : 'You must confirm the transfer.';
	} elseif ($amount <= 0) {
		$transferMsg = $plusRtlMsg ? 'أدخل كمية صحيحة.' : 'Enter a valid amount.';
	} elseif ($toName === '') {
		$transferMsg = $plusRtlMsg ? 'أدخل اسم المستخدم المستلم.' : 'Enter the recipient username.';
	} elseif (isset($session) && method_exists($session, 'sitterCan') && !$session->sitterCan(SITTER_PERM_GOLD)) {
		$transferMsg = $plusRtlMsg ? 'الوصي غير مسموح له بتحويل الذهب.' : 'Sitters cannot transfer gold.';
	} else {
		$lastAt = (int) ($_SESSION['plus_transfer_at'] ?? 0);
		if ($lastAt > 0 && (time() - $lastAt) < 5) {
			$transferMsg = $plusRtlMsg ? 'انتظر قليلاً قبل تحويل آخر.' : 'Wait a moment before another transfer.';
		} else {
			$toUser = $database->getUserArray($toName, 0);
			$toUid = is_array($toUser) ? (int) ($toUser['id'] ?? 0) : 0;
			if ($toUid <= 0 || $toUid === $fromUid) {
				$transferMsg = $plusRtlMsg
					? 'المستلم غير موجود أو غير صالح.'
					: 'Recipient not found or invalid.';
			} elseif ($toUid <= 5) {
				$transferMsg = $plusRtlMsg
					? 'لا يمكن التحويل لحسابات النظام.'
					: 'Cannot transfer to system accounts.';
			} else {
				$fromEmail = trim((string) ($session->userinfo['email'] ?? $session->email ?? ''));
				$toEmail = trim((string) ($toUser['email'] ?? ''));
				$usedCentral = false;

				if (class_exists('CentralGold') && CentralGold::isConfigured()) {
					list($ok, $msg) = CentralGold::transfer(
						$fromEmail,
						(string) $session->username,
						$fromUid,
						$toEmail,
						(string) ($toUser['username'] ?? $toName),
						$toUid,
						$amount,
						0,
						'player transfer'
					);
					$usedCentral = true;
					$transferOk = $ok;
					if ($ok) {
						$transferMsg = $plusRtlMsg
							? ('تم تحويل ' . $amount . ' ذهب مدفوع إلى ' . $toName . '.')
							: ('Transferred ' . $amount . ' paid gold to ' . $toName . '.');
						$database->addGoldFinLog(
							(int) ($village->wid ?? 0),
							$fromUid,
							'Gold transfer out',
							-$amount,
							'to ' . $toName
						);
						$database->addGoldFinLog(
							0,
							$toUid,
							'Gold transfer in',
							$amount,
							'from ' . $session->username
						);
					} else {
						$transferMsg = $plusRtlMsg
							? ('تعذر التحويل: ' . $msg)
							: ('Transfer failed: ' . $msg);
					}
				}

				if (!$usedCentral) {
					if (!$database->spendGold($fromUid, $amount, 'Gold transfer to ' . $toName)) {
						$transferMsg = $plusRtlMsg
							? 'لا يوجد ذهب كافٍ للتحويل.'
							: 'Not enough gold to transfer.';
					} else {
						$database->modifyGold($toUid, $amount, 1);
						$database->addGoldFinLog(
							(int) ($village->wid ?? 0),
							$fromUid,
							'Gold transfer out',
							-$amount,
							'to ' . $toName
						);
						$database->addGoldFinLog(
							0,
							$toUid,
							'Gold transfer in',
							$amount,
							'from ' . $session->username
						);
						if (isset($session->gold)) {
							$session->gold -= $amount;
							$_SESSION['gold'] = $session->gold;
						}
						$transferOk = true;
						$transferMsg = $plusRtlMsg
							? ('تم تحويل ' . $amount . ' ذهب إلى ' . $toName . '.')
							: ('Transferred ' . $amount . ' gold to ' . $toName . '.');
					}
				}

				if ($transferOk) {
					$_SESSION['plus_transfer_at'] = time();
					$_SESSION['plus_csrf'] = bin2hex(random_bytes(16));
				}
			}
		}
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo tz_html_dir_attrs(); ?>>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php
	echo SERVER_NAME . ' &raquo; &raquo; &raquo; PLUS ';

	if (!empty($_GET['id'])) {
	    switch ($_GET['id']) {
	        case '2':
	            echo 'Advantages';
	            break;

	        case '3':
	            echo 'Activate Plus';
	            break;

	        case '4':
	            echo 'FAQ';
	            break;

	        case '5':
	            echo 'Earn Gold';
	            break;

	        case '16':
	            echo 'Transfer Gold';
	            break;

	        case '17':
	            echo 'Purchase Transactions';
	            break;

	        case '18':
	            echo 'Troop Costs in Gold';
	            break;
	    }
	} else {
	    echo 'Buy Gold';
	}
	?></title>
	<link rel="shortcut icon" href="favicon.ico"/>
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<script src="mt-full.js?0faab" type="text/javascript"></script>
	<script src="unx.js?f4b7h" type="text/javascript"></script>
	<script src="new.js?0faab" type="text/javascript"></script>
	<?php
	// Base game CSS: ALWAYS load the English/base files here, exactly like
	// every other page (dorf1.php, dorf2.php, karte.php, build.php,
	// berichte.php, nachrichten.php, allianz.php, spieler.php,
	// statistiken.php, ...). Arabic/RTL is layered on top afterwards via the
	// single shared tz_rtl_stylesheet_tag() call below - never by swapping
	// out these base links.
	//
	// BUG FIXED: this page used to pick a per-language folder here
	// ($__css_lang = "ar" whenever gpack/.../lang/ar/ exists) and load
	// "lang/ar/lang.css" + "lang/ar/compact.css" instead of the English
	// ones. The "ar" folder only ships a small RTL OVERRIDE stylesheet
	// (lang.css) meant to sit on top of the English base - it has no
	// compact.css of its own, so that second link 404'd and the entire
	// base stylesheet (all of #content/#side_navi/#side_info's widths and
	// floats, table styling, fonts, etc.) silently failed to load for
	// Arabic players. It also skipped lang/en/lang.css, which is what
	// @imports modules/new_layout_ltr.css (the #mid/#side_navi/#side_info
	// column widths) - so the three-column layout had no widths to lay
	// out against at all.
	//
	// That's why the layout looked broken for Arabic on this page only:
	// #side_navi and #side_info floats were being set (by
	// gpack/.../lang/ar/lang.css and by css/rtl.css) but had no base
	// widths/columns to float within, so #side_info (menu.tpl's "hero"
	// column - multivillage list, quest character image, news) wrapped
	// onto its own line instead of sitting beside #content as the left
	// column, which is also why the character image looked misplaced.
	?>
	<link href="<?php echo GP_LOCATE; ?>lang/en/lang.css?f4b7d" rel="stylesheet" type="text/css" />
	<link href="<?php echo GP_LOCATE; ?>lang/en/compact.css?f4b7i" rel="stylesheet" type="text/css" />
	<?php
	// GP_LOCATE contine deja pachetul efectiv: alegerea jucatorului cand
	// e permisa si valida, altfel pachetul serverului (vezi config.php).
	echo "
	<link href='".GP_LOCATE."novaterra.css?e21d2' rel='stylesheet' type='text/css' />
	<link href='".GP_LOCATE."lang/en/lang.css?e21d2' rel='stylesheet' type='text/css' />";
	?>
	<script type="text/javascript">

		window.addEvent('domready', start);
	</script>
	<?php // Arabic/RTL CSS (css/rtl.css, plus any per-gpack lang/ar/lang.css
	// override) is loaded through the single shared tz_rtl_stylesheet_tag()
	// mechanism below - see GameEngine/config.php - on top of the English
	// base links above, exactly like every other game page. ?>
	<?php echo tz_rtl_stylesheet_tag(); ?>
</head>


<body class="v35 ie ie8 pg-plus">
<div class="wrapper">
<img style="filter:chroma();" src="img/x.gif" id="msfilter" alt="" />
<div id="dynamic_header">
	</div>
<?php include("Templates/header.tpl"); ?>
<div id="mid">
<?php include("Templates/menu.tpl"); ?>
<?php
if(isset($_GET['id'])){
	$id = preg_replace("/[^a-zA-Z0-9_-]/", "", $_GET['id']);
} 
else $id = "";

if ($id === '' || $id === '0' || $id === '1') {
	include ("Templates/Plus/1.tpl");
}
if ($id == 2) {
	include ("Templates/Plus/2.tpl");
}
if ($id == 3) {
	include ("Templates/Plus/3.tpl");
}
if ($id == 4) {
	include ("Templates/Plus/4.tpl");
}
if (isset($_GET['mail']) && $id == 5) {
	include ("Templates/Plus/invite.tpl");
} else if ($id == 5) {
	include ("Templates/Plus/5.tpl");
}
if ($id == 7) {
	include ("Templates/Plus/7.tpl");
}
if ($id == 8) {
	include ("Templates/Plus/8.tpl");
}
if ($id == 9) {
	include ("Templates/Plus/9.tpl");
}
if ($id == 10) {
	include ("Templates/Plus/10.tpl");
}
if ($id == 11) {
	include ("Templates/Plus/11.tpl");
}
if ($id == 12) {
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
if ($id == 13 || $id == 14) {
	$plusExtraTpl = "Templates/Plus/" . (int) $id . ".tpl";

	if (is_file($plusExtraTpl)) {
		include ($plusExtraTpl);
	} else {
		include ("Templates/Plus/3.tpl");
	}
}
if ($id == 15) {
	include ("Templates/Plus/15.tpl");
}
if ($id == 16) {
	include ("Templates/Plus/16.tpl");
}
if ($id == 17) {
	include ("Templates/Plus/17.tpl");
}
if ($id == 18) {
	include ("Templates/Plus/18.tpl");
}
if (is_numeric($id) && (int) $id > 18) {
	include ("Templates/Plus/3.tpl");
}
?>
<?php
if (isset($_POST['mail'])) {

	$email = trim($_POST['mail']);
	$text = isset($_POST['text']) ? trim($_POST['text']) : '';

	// Blocăm CRLF injection și validăm adresa
	if (
		strpos($email, "\r") === false &&
		strpos($email, "\n") === false &&
		filter_var($email, FILTER_VALIDATE_EMAIL)
	) {
		// Limităm dimensiunea și eliminăm caracterele de control
		$text = substr($text, 0, 2000);
		$text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);

		$mailer->sendInvite($email, $session->uid, $text);
	}
}
?>

<br /><br /><br /><br /><div id="side_info">
<?php
include("Templates/multivillage.tpl");
include("Templates/quest.tpl");
include("Templates/news.tpl");
if(!NEW_FUNCTIONS_DISPLAY_LINKS) {
	echo "<br><br><br><br>";
	include("Templates/links.tpl");
}
?>
</div>
<div class="clear"></div>
</div>
<div class="footer-stopper"></div>
<div class="clear"></div>

<?php
include("Templates/footer.tpl");
include("Templates/res.tpl");
?>
<div id="stime">
<div id="ltime">
<div id="ltimeWrap">
<?php echo CALCULATED_IN;?> <b><?php
echo round(($generator->pageLoadTimeEnd()-$start_timer)*1000);
?></b> ms

<br /><?php echo SERVER_TIME;?> <span id="tp1" class="b"><?php echo date('H:i:s'); ?></span>
</div>
	</div>
</div>

<div id="ce"></div>
</body>
</html>