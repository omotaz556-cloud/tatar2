<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : feeding.php                                               ##
##  Type           : In Game Linked Accounts (Feeding) Page                    ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow                                                    ##
##  Project        : Novaterra                                                 ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################

use App\Utils\AccessLogger;

ob_start();
include_once("GameEngine/Village.php");
include_once("GameEngine/FeedingSystem.php");
AccessLogger::logRequest();

if (isset($session) && is_object($session) && method_exists($session, 'isSitterSession')
    && $session->isSitterSession()) {
    header("Location: dorf1.php");
    exit;
}

$uid = isset($session) && isset($session->uid) ? (int) $session->uid : 0;

$feedingMsg = '';
$feedingOk  = false;
$gkPostedUser = '';
$gkPostedPw = '';

/**
 * Verify another account's password without starting a login session.
 */
$gkVerifyAccountPassword = static function ($database, $username, $password) {
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
};

if ($uid > 0 && $_SERVER['REQUEST_METHOD'] === 'POST' && class_exists('FeedingSystem')) {

    if (isset($_POST['fs_add_username']) || !empty($_POST['gk_accounts_link'])) {
        $targetUsername = trim((string) ($_POST['fs_add_username'] ?? ''));
        $targetPassword = (string) ($_POST['fs_add_password'] ?? '');
        $gkPostedUser = $targetUsername;
        $gkPostedPw = '';

        if ($targetUsername === '') {
            $feedingMsg = defined('FS_ERR_ACCOUNT_NOT_FOUND') ? FS_ERR_ACCOUNT_NOT_FOUND : 'لا يوجد حساب بهذا الاسم.';
        } else {
            $targetUid = 0;
            if (isset($database) && is_object($database) && method_exists($database, 'getUserArray')) {
                $targetUser = $database->getUserArray($targetUsername, 0, false);
                if (is_array($targetUser) && !empty($targetUser['id'])) {
                    $targetUid = (int) $targetUser['id'];
                }
            }

            if ($targetUid === 0) {
                $feedingMsg = defined('FS_ERR_ACCOUNT_NOT_FOUND') ? FS_ERR_ACCOUNT_NOT_FOUND : 'لا يوجد حساب بهذا الاسم.';
            } elseif (!$gkVerifyAccountPassword($database, $targetUsername, $targetPassword)) {
                $feedingMsg = defined('TZ_GK_ACC_ERR_PASSWORD')
                    ? TZ_GK_ACC_ERR_PASSWORD
                    : 'كلمة المرور غير صحيحة.';
            } else {
                $result = FeedingSystem::addLink($uid, $targetUid, 0);
                if ($result['ok']) {
                    $feedingOk  = true;
                    $feedingMsg = defined('TZ_GK_ACC_SUCCESS')
                        ? TZ_GK_ACC_SUCCESS
                        : (defined('FS_SUCCESS_ADDED') ? FS_SUCCESS_ADDED : 'تم ربط العضوية بنجاح.');
                    $gkPostedUser = '';
                } else {
                    $errorConstants = array(
                        'FEATURE_DISABLED'  => 'FS_ERR_FEATURE_DISABLED',
                        'CANNOT_LINK_SELF'  => 'FS_ERR_CANNOT_LINK_SELF',
                        'ACCOUNT_NOT_FOUND' => 'FS_ERR_ACCOUNT_NOT_FOUND',
                        'ALREADY_LINKED'    => 'FS_ERR_ALREADY_LINKED',
                        'LIMIT_REACHED'     => 'FS_ERR_LIMIT_REACHED',
                        'INVALID_ACCOUNT'   => 'FS_ERR_INVALID_ACCOUNT',
                    );
                    $constName  = $errorConstants[$result['error']] ?? 'FS_ERR_GENERIC';
                    $feedingMsg = defined($constName) ? constant($constName) : $result['error'];
                }
            }
        }
    } elseif (isset($_POST['fs_remove_uid'])) {
        $targetUid = (int) $_POST['fs_remove_uid'];
        if ($targetUid > 0 && FeedingSystem::removeLink($uid, $targetUid)) {
            $feedingOk  = true;
            $feedingMsg = defined('FS_SUCCESS_REMOVED') ? FS_SUCCESS_REMOVED : 'تمت إزالة الرابط.';
        } else {
            $feedingMsg = defined('FS_ERR_GENERIC') ? FS_ERR_GENERIC : 'حدث خطأ ما.';
        }
    }
}

$fsSettings   = class_exists('FeedingSystem') ? FeedingSystem::getSettings() : array('enabled' => false, 'max_linked_per_player' => 0);
$fsLinked     = ($uid > 0 && class_exists('FeedingSystem')) ? FeedingSystem::listLinked($uid) : array();
$fsCount      = count($fsLinked);
$fsCanAddMore = $fsSettings['enabled'] && $fsCount < (int) $fsSettings['max_linked_per_player'];

$gkShell = true;
$GLOBALS['gkShell'] = true;

$gkSpielerRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
$GLOBALS['gkSpielerLiteralPage'] = $gkSpielerRtl;
if ($gkSpielerRtl) {
    $GLOBALS['gkSpielerBarRendered'] = true;
}
include_once('GameEngine/GreekSpieler.php');

$gkSpielerCss = 'css/greek_maxb_spieler.css';
$gkSpielerCssVer = is_file(__DIR__ . '/' . $gkSpielerCss) ? (int) @filemtime(__DIR__ . '/' . $gkSpielerCss) : time();
$gkSpielerGreek = $gkSpielerRtl && class_exists('GreekSpieler');
$GLOBALS['gkSpielerGreek'] = $gkSpielerGreek;

$gkPageTitle = SERVER_NAME . ' &raquo; &raquo; &raquo; '
    . (defined('TZ_PROF_TAB_ACCOUNTS') ? TZ_PROF_TAB_ACCOUNTS : 'الحسابات');

if ($gkSpielerGreek) {
    $gkHeadOpts = array(
        'includeNew2Js' => false,
        'extraCss' => array($gkSpielerCss . '?v=' . $gkSpielerCssVer),
    );
    tz_greek_shell_head($gkPageTitle, 'pg-spieler pg-feeding', $gkHeadOpts);
    tz_greek_shell_open('', array('contentWrap' => false, 'resbarInMain' => false));
    GreekSpieler::menuOpen(7, (int) $uid, 0);
    include __DIR__ . '/Templates/Greek/accounts_greek.tpl';
    GreekSpieler::menuClose();
    tz_greek_shell_close(array('buildPopup' => false, 'timer' => $start_timer));
} else {
$gkFeedingStyle = '
.fs-p-wrap{color:#444;font-family:Verdana,Arial,sans-serif;font-size:12px;padding:14px;width:100%;max-width:560px;margin:0 auto 12px;box-sizing:border-box;}
.fs-p-wrap h2{font-size:17px;margin:0 0 6px;color:#222;}
.fs-p-intro{color:#888;font-size:11px;margin:0 0 14px;line-height:1.6;}
.fs-p-msg-ok{background:#eff9e9;border:1px solid #b8d89f;color:#416b2d;border-radius:4px;padding:8px 12px;font-size:11px;margin-bottom:14px;}
.fs-p-msg-err{background:#fff1f1;border:1px solid #e5bcbc;color:#9b2c2c;border-radius:4px;padding:8px 12px;font-size:11px;margin-bottom:14px;}
.fs-p-disabled{background:#f5f5f5;border:1px solid #d2d2d2;color:#777;border-radius:4px;padding:10px 14px;font-size:11px;margin-bottom:14px;}
.fs-p-card{background:#fff;border:1px solid #d2d2d2;border-radius:0;padding:0 0 12px;margin-bottom:12px;}
.fs-p-card h3{margin:0 0 10px;padding:6px 9px;background:linear-gradient(#eef8e9,#dcefd3);border-bottom:1px solid #b8d89f;font-size:13px;color:#315526;}
.fs-p-limit{color:#888;font-size:10.5px;margin:0 12px 10px;}
.fs-p-add{display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;}
.fs-p-add input[type=text]{border:1px solid #71d000;border-radius:0;padding:6px 9px;width:220px;}
.fs-p-add button{background:linear-gradient(#fff,#eef2f4);color:#416b2d;font-weight:bold;border:1px solid #d1d5db;border-radius:5px;padding:7px 16px;cursor:pointer;}
.fs-p-add button[disabled]{background:#eee;color:#aaa;cursor:not-allowed;}
.fs-p-table{width:calc(100% - 24px);border-collapse:collapse;margin:12px;}
.fs-p-table th{text-align:left;padding:6px 7px;font-size:9px;text-transform:uppercase;color:#777;border-bottom:1px solid #d2d2d2;}
html[dir="rtl"] .fs-p-table th{text-align:right;}
.fs-p-table td{padding:6px 7px;border-bottom:1px solid #ededed;font-size:11px;}
.fs-p-remove{background:#fff1f1;color:#a33;border:1px solid #e5bcbc;border-radius:4px;padding:4px 10px;font-size:10px;cursor:pointer;}
.fs-p-empty{color:#888;font-size:11px;padding:10px 12px;}
html[dir="rtl"] .fs-p-wrap{text-align:right;}';
tz_greek_shell_head($gkPageTitle, 'pg-feeding', array('includeNew2Js' => false, 'inlineStyle' => $gkFeedingStyle));
tz_greek_shell_open('feeding', array('contentWrap' => true));
?>
<div class="fs-p-wrap">
	<h2><?php echo FS_PLAYER_TITLE; ?></h2>
	<p class="fs-p-intro"><?php echo FS_PLAYER_INTRO; ?></p>

	<?php if ($feedingMsg !== '') { ?>
		<div class="<?php echo $feedingOk ? 'fs-p-msg-ok' : 'fs-p-msg-err'; ?>"><?php echo htmlspecialchars($feedingMsg, ENT_QUOTES, 'UTF-8'); ?></div>
	<?php } ?>

	<?php if (!$fsSettings['enabled']) { ?>
		<div class="fs-p-disabled"><?php echo FS_PLAYER_DISABLED_NOTICE; ?></div>
	<?php } else { ?>
		<div class="fs-p-card">
			<h3><?php echo FS_PLAYER_TITLE; ?></h3>
			<p class="fs-p-limit"><?php echo sprintf(FS_PLAYER_LIMIT_NOTICE, (int) $fsSettings['max_linked_per_player'], $fsCount); ?></p>

			<form method="post" action="feeding.php" class="fs-p-add">
				<div>
					<input type="text" name="fs_add_username" placeholder="<?php echo FS_PLAYER_USERNAME_LABEL; ?>" maxlength="100" <?php echo $fsCanAddMore ? '' : 'disabled'; ?> required>
				</div>
				<button type="submit" <?php echo $fsCanAddMore ? '' : 'disabled'; ?>><?php echo FS_PLAYER_ADD_BTN; ?></button>
			</form>
		</div>
	<?php } ?>

	<div class="fs-p-card">
		<h3><?php echo ADM_FS_ALL_LINKS_TITLE; ?></h3>
		<?php if (empty($fsLinked)) { ?>
			<div class="fs-p-empty"><?php echo FS_PLAYER_NO_LINKS; ?></div>
		<?php } else { ?>
			<table class="fs-p-table">
				<thead>
					<tr>
						<th><?php echo ADM_FS_LINKED_USERNAME; ?></th>
						<th><?php echo FS_PLAYER_LINKED_SINCE; ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($fsLinked as $row) { ?>
					<tr>
						<td><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
						<td><?php echo $row['added'] ? date('Y-m-d H:i', (int) $row['added']) : '&ndash;'; ?></td>
						<td>
							<form method="post" action="feeding.php" onsubmit="return confirm('<?php echo FS_PLAYER_CONFIRM_REMOVE; ?>');" style="margin:0;">
								<input type="hidden" name="fs_remove_uid" value="<?php echo (int) $row['linked_uid']; ?>">
								<button type="submit" class="fs-p-remove"><?php echo FS_PLAYER_REMOVE; ?></button>
							</form>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
	</div>
</div>
<?php
tz_greek_shell_close(array('buildPopup' => false, 'timer' => $start_timer));
}
