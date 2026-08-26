<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       menu.tpl                                                    ##
##  Developed by:  Dzoki                                                       ##
##  Refactored by: Shadow Incremental Refactor 			                       ##
##  License:       Novaterra Project                                            ##
##  Copyright:     Novaterra (c) 2010-2026. All rights reserved.                ##
##                                                                             ##
##  Incremental Refactor Notes:                                                ##
##  - Preserved original logic and HTML structure                              ##
##  - Compatible with older PHP 7+ environments                                ##
##  - Reduced duplicated conditions and echoes                                 ##
##  - Added safer isset() checks for legacy compatibility                      ##
##  - Added comments for maintainability                                       ##
##  - No functional behavior changed                                           ##
##                                                                             ##
#################################################################################

/**
 * RTL helper for the sidebar nav (#side_navi). Column mirroring (which
 * side the menu sits on) is handled by gpack/novaterra_classic/lang/ar/lang.css;
 * this just marks the container itself as RTL so its own text/icons flow
 * correctly for RTL languages.
 */
$tzMenuRtlAttr = (function_exists('tz_is_rtl_lang') && tz_is_rtl_lang())
    ? ' dir="rtl" class="arabic-text"'
    : '';

include_once("GameEngine/Generator.php");

/**
 * Start page load timer
 */
$start_timer = $generator->pageLoadTimeStart();

/**
 * Helper variables
 * Avoid repeated isset() and direct $_SESSION access
 */
$isLoggedIn  = !empty($session->logged_in);
$isPlus      = !empty($session->plus);
$isAdmin     = (isset($session->access) && $session->access == ADMIN);
$isMH        = (isset($session->access) && $session->access == MULTIHUNTER);
$userId      = isset($session->uid) ? (int)$session->uid : 0;
$username    = isset($session->username) ? $session->username : '';
$sessionOk   = (isset($_SESSION['ok']) && $_SESSION['ok'] == 1 && empty($_SESSION['sysmsg_ack']));
$idUser      = isset($_SESSION['id_user']) ? (int)$_SESSION['id_user'] : 0;
$tzChatUnread = 0;
if ($isLoggedIn && $userId > 0) {
    include_once('GameEngine/ChatRead.php');
    $tzChatUnread = ChatRead::getSidebarUnreadCount($userId);
}
?>
<?php if ($isLoggedIn) { ?>
<style type="text/css">
html.tz-mobile-mode body{min-width:0 !important;}
html.tz-mobile-mode #mid{width:100% !important;min-width:0 !important;}
html.tz-mobile-mode #side_navi,html.tz-mobile-mode #content,html.tz-mobile-mode #side_info{float:none !important;width:100% !important;max-width:100% !important;box-sizing:border-box;}
html.tz-invert-colors body{filter:invert(1) hue-rotate(180deg);}
html.tz-dark-mode body{background:#20252b !important;color:#e7ecef !important;}
html.tz-dark-mode #mid,html.tz-dark-mode #content,html.tz-dark-mode #side_navi,html.tz-dark-mode #side_info{background:#20252b !important;color:#e7ecef !important;}
html.tz-dark-mode table,html.tz-dark-mode td,html.tz-dark-mode th{background-color:#2b3239 !important;color:#e7ecef !important;border-color:#46515a !important;}
html.tz-stats-compact body #content.statistics table{font-size:11px !important;}
html.tz-stats-compact body #content.statistics th,html.tz-stats-compact body #content.statistics td{padding:3px 5px !important;}
html.tz-stats-classic body #content.statistics table{font-size:14px !important;}
html.tz-stats-classic body #content.statistics th,html.tz-stats-classic body #content.statistics td{padding:9px 10px !important;line-height:1.5;}
#side_navi .tz-chat-unread{display:inline-block;min-width:18px;height:18px;padding:0 6px;margin-inline-start:6px;border-radius:9px;background:#e53935;color:#fff !important;font-size:11px;font-weight:700;line-height:18px;text-align:center;vertical-align:middle;box-shadow:0 0 0 1px #fff,0 1px 3px rgba(0,0,0,.35);}
#side_navi p a.tz-chat-link{position:relative;}
</style>
<script type="text/javascript">
(function () {
    var root = document.documentElement;
    var mobileMode = <?php echo (int)($session->userinfo['mobile_mode'] ?? 0); ?>;
    var timerRefresh = <?php echo !empty($session->userinfo['timer_refresh']) ? 'true' : 'false'; ?>;
    var invertColors = <?php echo !empty($session->userinfo['invert_colors']) ? 'true' : 'false'; ?>;
    var statsFormat = <?php echo (int)($session->userinfo['stats_format'] ?? 0); ?>;
    var nightMode = <?php echo (int)($session->userinfo['night_mode'] ?? 0); ?>;

    if (mobileMode === 2 || (mobileMode === 0 && window.innerWidth <= 700)) {
        root.className += ' tz-mobile-mode';
    }
    if (invertColors) {
        root.className += ' tz-invert-colors';
    }
    if (nightMode === 2) {
        root.className += ' tz-dark-mode';
    }
    if (statsFormat === 2 && /(?:^|\/)statistiken\.php(?:$|\?)/.test(window.location.pathname + window.location.search)) {
        root.className += ' tz-stats-compact';
    }
    if (statsFormat === 1 && /(?:^|\/)statistiken\.php(?:$|\?)/.test(window.location.pathname + window.location.search)) {
        root.className += ' tz-stats-classic';
    }
    root.setAttribute('data-stats-format', String(statsFormat));

    if (timerRefresh) {
        window.setInterval(function () {
            var timers = document.querySelectorAll('[id^="timer"]');
            for (var i = 0; i < timers.length; i++) {
                if (/^(0+:)?00:00(?::00)?$/.test((timers[i].textContent || '').trim())) {
                    window.location.reload();
                    return;
                }
            }
        }, 1000);
    }
})();
(function () {
    function applyChatUnread(count) {
        var n = parseInt(count, 10) || 0;
        var nav = document.getElementById('tzChatUnreadBadge');
        var popup = document.getElementById('tzChatPopupBadge');
        [nav, popup].forEach(function (el) {
            if (!el) {
                return;
            }
            if (n > 0) {
                el.style.display = '';
                el.textContent = String(n);
                el.title = String(n);
            } else {
                el.style.display = 'none';
                el.textContent = '0';
            }
        });
    }
    function pollChatUnread() {
        fetch('chat_unread.php', { credentials: 'same-origin', cache: 'no-store' })
            .then(function (r) { return r.json(); })
            .then(function (data) { applyChatUnread(data && data.count); })
            .catch(function () {});
    }
    pollChatUnread();
    setInterval(pollChatUnread, 15000);
})();
</script>
<?php } ?>
<?php if(!$isLoggedIn) { ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title></title>

    <style type="text/css">
        div.c1 {
            text-align: center;
        }
    </style>
</head>

<body>

<div id="side_navi"<?php echo $tzMenuRtlAttr; ?>>

    <a id="logo" href="<?php echo HOMEPAGE; ?>" name="logo">
        <img src="img/x.gif" alt="<?php echo TZ_NOVATERRA_NAME; ?>">
    </a>

    <p>
        <a href="<?php echo HOMEPAGE; ?>">
            <?php echo HOME; ?>
        </a>

        <a href="login.php">
            <?php echo LOGIN; ?>
        </a>

        <a href="anmelden.php">
            <?php echo REG; ?>
        </a>
    </p>

</div>

<?php } else { ?>

<div id="side_navi"<?php echo $tzMenuRtlAttr; ?>>

    <!-- Logo -->
    <a id="logo" href="<?php echo HOMEPAGE; ?>" name="logo">
        <img
            src="img/x.gif"
            <?php if($isPlus) { echo 'class="logo_plus"'; } ?>
            alt="<?php echo TZ_NOVATERRA_NAME; ?>"
        >
    </a>

    <!-- Main navigation -->
    <p>

        <a href="<?php echo HOMEPAGE; ?>">
            <?php echo HOME; ?>
        </a>

        <a href="#" onclick="return Popup(0,0,1);">
            <?php echo $lang['index'][0][2]; ?>
        </a>

        <a href="spieler.php?uid=<?php echo $userId; ?>">
            <?php echo PROFILE; ?>
        </a>

        <?php
        /**
         * Multihunter links
         */
        if($isMH) {
            ?>
            <a href="Admin/admin.php">
                <font color="Blue"><?php echo MH_PANEL; ?></font>
            </a>
        <?php
        }

        /**
         * Admin links
         */
        if($isAdmin) {
            ?>
            <a href="Admin/admin.php">
                <font color="Red"><?php echo ADMIN_PANEL; ?></font>
            </a>

            <a href="build_croppers.php">
                <?php echo TZ_BUILD_CROPPER; ?>
            </a>
        <?php
        }
        ?>

        <a href="logout.php">
            <?php echo LOGOUT; ?>
        </a>

    </p>

    <!-- Forum -->
    <p>
        <a href="allianz.php?s=2">
            <?php echo FORUM; ?>
        </a>
    </p>


    <p class="tz-chat-nav" id="tzChatNavWrap">
        <a href="#" class="tz-chat-link" id="tzChatNavLink">
            <?php echo defined('CHAT') ? CHAT : 'الدردشة'; ?><span class="tz-chat-unread" id="tzChatUnreadBadge" title="<?php echo (int) $tzChatUnread; ?>"<?php if ($tzChatUnread <= 0) { echo ' style="display:none"'; } ?>><?php echo (int) $tzChatUnread; ?></span>
        </a>
        <div id="socialPopup" class="tz-chat-popup" hidden>
            <strong class="tz-chat-popup-title"><?php echo defined('CHAT') ? CHAT : 'الدردشة'; ?></strong>
            <a class="tz-chat-popup-link" href="allianz.php?s=6&amp;public=1">الدردشة العامة<span class="tz-chat-unread" id="tzChatPopupBadge"<?php if ($tzChatUnread <= 0) { echo ' style="display:none"'; } ?>><?php echo (int) $tzChatUnread; ?></span></a>
            <a class="tz-chat-popup-close" href="#" id="tzChatPopupClose">إغلاق</a>
        </div>
    </p>
    <style type="text/css">
    #side_navi .tz-chat-nav{position:relative;z-index:30;}
    #side_navi .tz-chat-popup{
        position:absolute;
        top:-4px;
        width:190px;
        padding:10px 12px;
        background:#fff;
        border:1px solid #b8c9d1;
        box-shadow:0 3px 12px rgba(0,0,0,.25);
        text-align:right;
        direction:rtl;
        z-index:10000;
    }
    #side_navi .tz-chat-popup[hidden]{display:none !important;}
    #side_navi .tz-chat-popup-title{display:block;margin:0 0 8px;color:#28566a;font-weight:700;}
    #side_navi .tz-chat-popup-link{display:block;padding:6px 0;color:#71d000;text-decoration:none;}
    #side_navi .tz-chat-popup-link:hover{text-decoration:underline;}
    #side_navi .tz-chat-popup-close{display:block;padding-top:8px;margin-top:4px;border-top:1px solid #e1e8eb;color:#71d000;text-decoration:none;}
    #side_navi .tz-chat-popup-close:hover{text-decoration:underline;}
    /* Sidebar is on the right in RTL → open popup toward the content (left). */
    html[dir="rtl"] #side_navi .tz-chat-popup{right:100%;left:auto;margin-right:8px;margin-left:0;}
    html[dir="ltr"] #side_navi .tz-chat-popup{left:100%;right:auto;margin-left:8px;margin-right:0;}
    </style>
    <script type="text/javascript">
    (function () {
        var link = document.getElementById('tzChatNavLink');
        var popup = document.getElementById('socialPopup');
        var closeBtn = document.getElementById('tzChatPopupClose');
        if (!link || !popup) return;

        function openPopup(e) {
            if (e) e.preventDefault();
            popup.hidden = false;
        }
        function closePopup(e) {
            if (e) e.preventDefault();
            popup.hidden = true;
        }

        link.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            popup.hidden = !popup.hidden;
        });
        if (closeBtn) closeBtn.addEventListener('click', closePopup);
        document.addEventListener('click', function (e) {
            var wrap = document.getElementById('tzChatNavWrap');
            if (!wrap || wrap.contains(e.target)) return;
            popup.hidden = true;
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') popup.hidden = true;
        });
    })();
    </script>
	
	<!-- Discord -->
	<p>
		<a href="https://discord.gg/HzU2HfqaG" target="_blank" rel="noopener noreferrer" style="color: #d32f2f; font-weight: bold;">
			Discord
		</a>
	</p>

    <!-- Plus / Support / Custom links -->
    <p>
        <a href="activity.php">مكافأة النشاط</a>
    </p>

    <p>

        <?php
        /**
         * Hide support-only links from support account
         */
        if($idUser != 1) {
        ?>
            <a href="plus.php?id=3">
                <?php echo TZ_NOVATERRA; ?>
                <b>
                    <span class="plus_g">P</span>
                    <span class="plus_o">l</span>
                    <span class="plus_g">u</span>
                    <span class="plus_o">s</span>
                </b>
            </a>
        <?php
        }

        /**
         * Support profile link
         */
        if($idUser != 1) {
        ?>
            <a href="spieler.php?uid=1">
                <?php echo SUPPORT; ?>
            </a>
        <?php
        }

        /**
         * Linked Accounts (Feeding System) — only shown when the admin has
         * enabled the feature (GameEngine/FeedingSystem.php). Hidden for the
         * support account like the links above it.
         */
        if($idUser != 1 && class_exists('FeedingSystem') && FeedingSystem::isEnabled()) {
        ?>
            <a href="feeding.php">
                <?php echo FS_PLAYER_TITLE; ?>
            </a>
        <?php
        }

        if (isset($database) && method_exists($database, 'isThereAWinner') && $database->isThereAWinner()) {
        ?>
            <a href="winner.php" style="color:#71D000;font-weight:bold;">
                <?php echo defined('WINNER_RPT_MENU') ? WINNER_RPT_MENU : 'تقرير النهاية'; ?>
            </a>
        <?php
        }

        /**
         * Natars include
         */
        include("Templates/natars.tpl");
		
		/**
		* Maintenance status for admins
		*/
		include("Templates/maintenance_status.tpl");

		/**
		* Debug Error Log quick toggle for admins
		*/
		include("Templates/debug_status.tpl");

        ?>

    </p>

    <?php
    /**
     * Direct links box in the side menu (outside <p> so markup stays valid).
     * Toggle: NEW_FUNCTIONS_DISPLAY_LINKS — true = left menu, false = #side_info.
     */
    if (defined('NEW_FUNCTIONS_DISPLAY_LINKS') && NEW_FUNCTIONS_DISPLAY_LINKS) {
        include("Templates/links.tpl");
    }
    ?>

    <?php
    /**
     * Account deletion countdown
     */
    $timestamp = $database->isDeleting($userId);

    if($timestamp) {

        echo '<br /><td colspan="2" class="count">';

        /**
         * Allow cancellation if more than 48h remain
         */
        if($timestamp > (time() + 172800)) {

            echo '<a href="spieler.php?s=3&id=' . $userId . '&a=1&e=4">
                    <img
                        class="del"
                        src="img/x.gif"
                        alt="'.CANCEL_PROCESS.'"
                        title="'.CANCEL_PROCESS.'"
                    />
                  </a>';
        }

        /**
         * Remaining deletion time
         */
        $time = $generator->getTimeFormat($timestamp - time());

        echo '<a href="spieler.php?s=3">
                The account will be deleted in
                <span id="timer' . ++$session->timer . '">
                    ' . $time . '
                </span>.
              </a>';

        echo '</td><br />';
    }
    ?>

</div>

<?php
/**
 * Live "local time" clock (issue #198): show a second clock next to the
 * server-time one, ticking in the player's chosen timezone. The server-time
 * block lives in each page's own footer (rendered after this menu), so we wait
 * for the DOM and target the last #tp1 (the visible one). Vanilla JS, driven by
 * Date.now() + the player's UTC offset, so it is independent of the browser
 * timezone and does not touch the unx.js tp+i counters (arrival timers).
 *
 * Skipped entirely when the player's timezone matches the server's, so no
 * redundant line is shown.
 */
$localOffset  = (int) $generator->userTimeZoneOffset();
$serverOffset = (int) date('Z');
if ($localOffset !== $serverOffset):
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var anchors = document.querySelectorAll('#tp1');
    if (!anchors.length) return;
    var tp = anchors[anchors.length - 1];
    var off = <?php echo $localOffset; ?> * 1000;
    var label = <?php echo json_encode(LOCAL_TIME); ?>;

    var br  = document.createElement('br');
    var lbl = document.createElement('span');
    lbl.appendChild(document.createTextNode(label + ' '));
    var val = document.createElement('span');
    val.className = 'b';

    var parent = tp.parentNode, next = tp.nextSibling;
    parent.insertBefore(br, next);
    parent.insertBefore(lbl, next);
    parent.insertBefore(val, next);

    // align the local-time value vertically under the server-time value
    var delta = tp.offsetLeft - val.offsetLeft;
    if (delta > 0) {
        lbl.style.display = 'inline-block';
        lbl.style.width = (lbl.offsetWidth + delta) + 'px';
    }

    // make room for the extra line and lift the block so it stays in the frame
    var box = tp;
    while (box && box.id !== 'ltime') box = box.parentNode;
    if (box) {
        box.style.height = 'auto';
        var top = parseInt(window.getComputedStyle(box).top, 10);
        if (!isNaN(top)) box.style.top = (top - 8) + 'px';
    }

    function p(n) { return n < 10 ? '0' + n : n; }
    function tick() {
        var d = new Date(Date.now() + off);
        val.innerHTML = p(d.getUTCHours()) + ':' + p(d.getUTCMinutes()) + ':' + p(d.getUTCSeconds());
    }
    tick();
    setInterval(tick, 1000);
});
</script>
<?php endif; ?>

<?php
/**
 * Announcement screen
 */
if($sessionOk) {
?>
<script type="text/javascript">
/* Mark body so RTL CSS can isolate the announcement layout from dorf1 flex. */
(function () {
    if (document.body) {
        document.body.className += (document.body.className ? ' ' : '') + 'announcementPage';
    }
})();
</script>

<div id="content" class="sysmsg announcement">

    <h1>
        <?php echo ANNOUNCEMENT; ?>
    </h1>

    <br />

    <h3 class="announcement-hello">
        <?php
        $helloName = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        if (function_exists('tz_is_rtl_lang') && tz_is_rtl_lang()) {
            echo 'مرحباً ' . $helloName . '،';
        } else {
            echo (defined('ADM_HELLO') ? ADM_HELLO : 'Hi') . ' ' . $helloName . ',';
        }
        ?>
    </h3>

    <div id="sysmsg" class="announcement-body">
        <?php include("Templates/text.tpl"); ?>
    </div>

    <div class="c1 announcement-actions">

        <br />

        <h3>
            <a href="dorf1.php?ok">
                &raquo; <?php echo GO2MY_VILLAGE; ?>
            </a>
        </h3>

    </div>

</div>

<div id="side_info">

    <?php
    /**
     * Right-side widgets — keep visible on announcement so the page uses
     * the same three-column layout as a normal dorf1 view.
     */
    include("Templates/multivillage.tpl");
    include("Templates/quest.tpl");
    include("Templates/news.tpl");

    /**
     * Show links in sidebar if not displayed above
     */
    if(
        defined('NEW_FUNCTIONS_DISPLAY_LINKS')
        && !NEW_FUNCTIONS_DISPLAY_LINKS
    ) {
        include("Templates/links.tpl");
    }
    ?>

</div>

<div class="clear"></div>
</div><!-- close #mid early (same as normal pages) so #stime is NOT position:absolute inside #mid -->

<div class="footer-stopper"></div>

<div class="clear"></div>

<?php
/**
 * Footer includes
 */
include("Templates/footer.tpl");
include("Templates/res.tpl");
?>

<div id="stime">

    <div id="ltime">

        <div id="ltimeWrap">

            <?php echo CALCULATED_IN; ?>

            <b>
                <?php
                echo round(
                    ($generator->pageLoadTimeEnd() - $start_timer) * 1000
                );
                ?>
            </b>

            ms

            <br />

            <?php echo SERVER_TIME; ?>

            <span id="tp1" class="b">
                <?php echo date('H:i:s'); ?>
            </span>

        </div>

    </div>

</div>

<div id="ce"></div>

</body>
</html>

<?php
    /**
     * Stop execution after announcement page
     */
    die();
}
?>

<?php } ?>