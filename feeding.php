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
##  Lets a player declare their own alternate account(s) on this world as     ##
##  "linked" (fed) accounts, so raiding them ignores the usual warehouse/     ##
##  cranny loot cap. Self-service only, capped by the admin-configured        ##
##  max_linked_per_player (GameEngine/FeedingSystem.php). Separate feature    ##
##  from MultiAccount.php (anti-cheat detection) — see that file's docblock.  ##
## --------------------------------------------------------------------------- ##
#################################################################################

use App\Utils\AccessLogger;

ob_start();
include_once("GameEngine/Village.php");
include_once("GameEngine/FeedingSystem.php");
AccessLogger::logRequest();

/**
 * Sitters must not be able to link/unlink accounts on the owner's behalf —
 * same restriction pattern used by spieler.php for account-level actions
 * (Profile / Preferences / Account tabs), since this changes who can raid
 * whom without the normal loot cap, not just gameplay state.
 */
if (isset($session) && is_object($session) && method_exists($session, 'isSitterSession')
    && $session->isSitterSession()) {
    header("Location: dorf1.php");
    exit;
}

$uid = isset($session) && isset($session->uid) ? (int) $session->uid : 0;

$feedingMsg = '';
$feedingOk  = false;

if ($uid > 0 && $_SERVER['REQUEST_METHOD'] === 'POST' && class_exists('FeedingSystem')) {

    if (isset($_POST['fs_add_username'])) {
        $targetUsername = trim((string) $_POST['fs_add_username']);
        if ($targetUsername === '') {
            $feedingMsg = FS_ERR_ACCOUNT_NOT_FOUND;
        } else {
            // No existing $database helper resolves username -> uid alone,
            // so resolve it directly here (same prepared-statement pattern
            // used by fs_admin_uid_by_username() in feedingSystemAdmin.php).
            $targetUid = 0;
            if (isset($GLOBALS['link']) && $GLOBALS['link']) {
                $stmt = mysqli_prepare($GLOBALS['link'], "SELECT id FROM " . TB_PREFIX . "users WHERE username = ? LIMIT 1");
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 's', $targetUsername);
                    mysqli_stmt_execute($stmt);
                    $res = mysqli_stmt_get_result($stmt);
                    $row = $res ? mysqli_fetch_assoc($res) : null;
                    mysqli_stmt_close($stmt);
                    $targetUid = $row ? (int) $row['id'] : 0;
                }
            }

            if ($targetUid === 0) {
                $feedingMsg = FS_ERR_ACCOUNT_NOT_FOUND;
            } else {
                $result = FeedingSystem::addLink($uid, $targetUid, 0);
                if ($result['ok']) {
                    $feedingOk  = true;
                    $feedingMsg = FS_SUCCESS_ADDED;
                } else {
                    $errorConstants = [
                        'FEATURE_DISABLED'  => 'FS_ERR_FEATURE_DISABLED',
                        'CANNOT_LINK_SELF'  => 'FS_ERR_CANNOT_LINK_SELF',
                        'ACCOUNT_NOT_FOUND' => 'FS_ERR_ACCOUNT_NOT_FOUND',
                        'ALREADY_LINKED'    => 'FS_ERR_ALREADY_LINKED',
                        'LIMIT_REACHED'     => 'FS_ERR_LIMIT_REACHED',
                        'INVALID_ACCOUNT'   => 'FS_ERR_INVALID_ACCOUNT',
                    ];
                    $constName  = $errorConstants[$result['error']] ?? 'FS_ERR_GENERIC';
                    $feedingMsg = defined($constName) ? constant($constName) : $result['error'];
                }
            }
        }
    } elseif (isset($_POST['fs_remove_uid'])) {
        $targetUid = (int) $_POST['fs_remove_uid'];
        if ($targetUid > 0 && FeedingSystem::removeLink($uid, $targetUid)) {
            $feedingOk  = true;
            $feedingMsg = FS_SUCCESS_REMOVED;
        } else {
            $feedingMsg = FS_ERR_GENERIC;
        }
    }
}

$fsSettings   = class_exists('FeedingSystem') ? FeedingSystem::getSettings() : ['enabled' => false, 'max_linked_per_player' => 0];
$fsLinked     = ($uid > 0 && class_exists('FeedingSystem')) ? FeedingSystem::listLinked($uid) : [];
$fsCount      = count($fsLinked);
$fsCanAddMore = $fsSettings['enabled'] && $fsCount < $fsSettings['max_linked_per_player'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo tz_html_dir_attrs(); ?>>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo SERVER_NAME . ' &raquo; &raquo; &raquo; ' . FS_PLAYER_TITLE; ?></title>
	<link rel="shortcut icon" href="favicon.ico"/>
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<script src="mt-full.js?0faab" type="text/javascript"></script>
	<script src="unx.js?f4b7h" type="text/javascript"></script>
	<script src="new.js?0faab" type="text/javascript"></script>
	<link href="<?php echo GP_LOCATE; ?>lang/en/lang.css?f4b7d" rel="stylesheet" type="text/css" />
	<link href="<?php echo GP_LOCATE; ?>lang/en/compact.css?f4b7i" rel="stylesheet" type="text/css" />
	<?php
	echo "
	<link href='".GP_LOCATE."novaterra.css?e21d2' rel='stylesheet' type='text/css' />
	<link href='".GP_LOCATE."lang/en/feeding.override.css?e21d2' rel='stylesheet' type='text/css' />
	<link href='".GP_LOCATE."lang/en/lang.css?e21d2' rel='stylesheet' type='text/css' />";
	?>
	<style>
	.fs-p-wrap{color:#e2e8f0;font-family:Verdana,Arial,sans-serif;font-size:12px;padding:14px;max-width:720px;}
	.fs-p-wrap h2{font-size:17px;margin:0 0 6px;color:#2b2214;}
	.fs-p-intro{color:#5a4a34;font-size:11px;margin:0 0 14px;line-height:1.6;}
	.fs-p-msg-ok{background:#d1fae5;border:1px solid #34d399;color:#065f46;border-radius:6px;padding:8px 12px;font-size:11px;margin-bottom:14px;}
	.fs-p-msg-err{background:#fee2e2;border:1px solid #f87171;color:#7f1d1d;border-radius:6px;padding:8px 12px;font-size:11px;margin-bottom:14px;}
	.fs-p-disabled{background:#f3f4f6;border:1px solid #d1d5db;color:#4b5563;border-radius:6px;padding:10px 14px;font-size:11px;margin-bottom:14px;}
	.fs-p-card{background:#fffaf0;border:1px solid #e2d5b8;border-radius:8px;padding:14px 16px;margin-bottom:16px;}
	.fs-p-card h3{margin:0 0 4px;font-size:13px;color:#2b2214;}
	.fs-p-limit{color:#8a7754;font-size:10.5px;margin:0 0 10px;}
	.fs-p-add{display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;}
	.fs-p-add input[type=text]{border:1px solid #c8b78e;border-radius:5px;padding:6px 9px;width:220px;}
	.fs-p-add button{background:#a9762b;color:#fff;font-weight:bold;border:0;border-radius:5px;padding:8px 16px;cursor:pointer;}
	.fs-p-add button[disabled]{background:#c8b78e;cursor:not-allowed;}
	.fs-p-table{width:100%;border-collapse:collapse;margin-top:12px;}
	.fs-p-table th{text-align:left;padding:6px 7px;font-size:9px;text-transform:uppercase;color:#8a7754;border-bottom:1px solid #e2d5b8;}
	html[dir="rtl"] .fs-p-table th{text-align:right;}
	.fs-p-table td{padding:6px 7px;border-bottom:1px solid #eee0c4;font-size:11px;}
	.fs-p-remove{background:#b91c1c;color:#fff;border:0;border-radius:4px;padding:4px 10px;font-size:10px;cursor:pointer;}
	.fs-p-empty{color:#8a7754;font-size:11px;padding:10px 0;}
	</style>
	<script type="text/javascript">
		window.addEvent('domready', start);
	</script>
	<?php echo tz_rtl_stylesheet_tag(); ?>
</head>

<body class="v35 ie ie8 pg-feeding">
<div class="wrapper">
<img style="filter:chroma();" src="img/x.gif" id="msfilter" alt="" />
<div id="dynamic_header">
	</div>
<?php include("Templates/header.tpl"); ?>
<div id="mid">
<?php include("Templates/menu.tpl"); ?>

<div class="fs-p-wrap">
	<h2><?php echo FS_PLAYER_TITLE; ?></h2>
	<p class="fs-p-intro"><?php echo FS_PLAYER_INTRO; ?></p>

	<?php if ($feedingMsg !== ''): ?>
		<div class="<?php echo $feedingOk ? 'fs-p-msg-ok' : 'fs-p-msg-err'; ?>"><?php echo htmlspecialchars($feedingMsg, ENT_QUOTES, 'UTF-8'); ?></div>
	<?php endif; ?>

	<?php if (!$fsSettings['enabled']): ?>
		<div class="fs-p-disabled"><?php echo FS_PLAYER_DISABLED_NOTICE; ?></div>
	<?php else: ?>
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
	<?php endif; ?>

	<div class="fs-p-card">
		<h3><?php echo ADM_FS_ALL_LINKS_TITLE; ?></h3>
		<?php if (empty($fsLinked)): ?>
			<div class="fs-p-empty"><?php echo FS_PLAYER_NO_LINKS; ?></div>
		<?php else: ?>
			<table class="fs-p-table">
				<thead>
					<tr>
						<th><?php echo ADM_FS_LINKED_USERNAME; ?></th>
						<th><?php echo FS_PLAYER_LINKED_SINCE; ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($fsLinked as $row): ?>
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
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
</div>

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
