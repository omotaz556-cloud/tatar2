<?php

#################################################################################
##  Filename       : settings_hub.tpl                                          ##
##  One-page profile home — every control saves or opens a working editor.     ##
#################################################################################

$hubUid = (int) $session->uid;
$ui = is_array($session->userinfo ?? null) ? $session->userinfo : [];

$heroCount = 0;
$heroCountQuery = $database->query(
    "SELECT COUNT(*) AS total FROM " . TB_PREFIX . "hero WHERE uid=" . $hubUid
);
if ($heroCountQuery) {
    $heroCountRow = $heroCountQuery->fetch_assoc();
    $heroCount = min(2, (int) ($heroCountRow['total'] ?? 0));
}

$nightOn = ((int) ($ui['night_mode'] ?? 0) === 2);
$stopAutoOn = empty($ui['timer_refresh']);
$mapOn = !empty($ui['map']);
$invertOn = !empty($ui['invert_colors']);
$v4On = !empty($ui['v4']);
$v5On = !empty($ui['v5']);
$v6On = !empty($ui['v6']);
$mobileOn = ((int) ($ui['mobile_mode'] ?? 0) === 2);
$webNotifOn = !empty($ui['web_notifications']);

$upgradeRedirect = (int) ($ui['upgrade_redirect'] ?? 0);
$upgradeLabel = defined('PREF_STAY_IN_BUILDING') ? PREF_STAY_IN_BUILDING : TZ_UPGRADE_NAVIGATION_BUILDING;
if ($upgradeRedirect === 1) {
    $upgradeLabel = defined('PREF_GO_TO_MAP') ? PREF_GO_TO_MAP : TZ_UPGRADE_NAVIGATION_MAP;
}

$statsFormat = (int) ($ui['stats_format'] ?? 0);
$statsLabel = PREF_STATS_AUTO;
if ($statsFormat === 1) {
    $statsLabel = PREF_STATS_CLASSIC;
} elseif ($statsFormat === 2) {
    $statsLabel = PREF_STATS_COMPACT;
}

$manage = defined('PREF_MANAGE') ? PREF_MANAGE : 'إدارة';
$hubAction = 'spieler.php?uid=' . $hubUid;
$vacationTitle = defined('PREF_VACATION_TITLE') ? PREF_VACATION_TITLE : VACATION;
$vacationHint = defined('PREF_VACATION_HUB_HINT') ? PREF_VACATION_HUB_HINT : TZ_MINIMUM_VACATION;

$heroMansionLink = 'spieler.php?s=1';
$hasHeroMansion = false;
if (isset($village) && is_object($village) && !empty($village->resarray)) {
    for ($fi = 19; $fi <= 40; $fi++) {
        if ((int) ($village->resarray['f' . $fi . 't'] ?? 0) === 37) {
            $heroMansionLink = 'build.php?id=' . $fi . '&amp;rename';
            $hasHeroMansion = true;
            break;
        }
    }
}

if (!function_exists('tzHubToggle')) {
function tzHubToggle($name, $isOn, $action) {
    $on = $isOn ? ' is-on' : '';
    return '<form method="post" action="' . htmlspecialchars($action, ENT_QUOTES, 'UTF-8') . '" class="hubToggleForm">'
        . '<input type="hidden" name="hub_toggle" value="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" />'
        . '<input type="hidden" name="hub_on" value="' . ($isOn ? '0' : '1') . '" />'
        . '<button type="submit" class="hubToggle' . $on . '" title="'
        . ($isOn ? 'ON' : 'OFF') . '" aria-pressed="'
        . ($isOn ? 'true' : 'false') . '"><span class="hubToggleKnob"></span></button>'
        . '</form>';
}
function tzHubBtn($href, $label, $class = 'hubBtn') {
    return '<a class="' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '" href="'
        . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</a>';
}
function tzHubCycle($action, $cycle, $label) {
    return '<form method="post" action="' . htmlspecialchars($action, ENT_QUOTES, 'UTF-8') . '" class="hubToggleForm">'
        . '<input type="hidden" name="hub_cycle" value="' . htmlspecialchars($cycle, ENT_QUOTES, 'UTF-8') . '" />'
        . '<button type="submit" class="hubBtn">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</button>'
        . '</form>';
}
}
?>
<style type="text/css">
.settingsHub {
    width: 502px;
    max-width: 100%;
    margin: 8px auto 20px;
    box-sizing: border-box;
    font-family: "Expo Arabic", Tahoma, "Segoe UI", Arial, sans-serif;
}
.settingsHub .hubSection {
    margin: 0 0 12px;
    background: #fff;
    border: 1px solid #c6d9a8;
    border-radius: 3px;
    overflow: hidden;
}
.settingsHub .hubSectionHead {
    margin: 0;
    padding: 7px 12px;
    background: linear-gradient(#e8f6dc, #cfe8b8);
    border-bottom: 1px solid #a8c98a;
    color: #2f5c1f;
    font-size: 13px;
    font-weight: 700;
    text-align: right;
}
.settingsHub .hubRow {
    display: flex;
    flex-direction: row;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    padding: 11px 12px;
    border-bottom: 1px solid #ececec;
    background: #fff;
    box-sizing: border-box;
    direction: rtl;
}
.settingsHub .hubRow:last-child { border-bottom: 0; }
.settingsHub .hubMeta { flex: 1 1 auto; min-width: 0; text-align: right; order: 1; }
.settingsHub .hubAction {
    flex: 0 0 auto;
    order: 2;
    padding-top: 2px;
}
.settingsHub .hubTitle {
    color: #2a2a2a;
    font-size: 13px;
    font-weight: 700;
    line-height: 1.35;
}
.settingsHub .hubHint {
    margin-top: 4px;
    color: #8a8a8a;
    font-size: 11px;
    line-height: 1.5;
    word-wrap: break-word;
    overflow-wrap: break-word;
}
.settingsHub .hubBtn {
    display: inline-block;
    min-width: 58px;
    padding: 4px 12px;
    background: #f7f7f7;
    border: 1px solid #cfcfcf;
    border-radius: 3px;
    color: #444 !important;
    font-size: 12px;
    font-weight: 700;
    text-align: center;
    text-decoration: none !important;
    cursor: pointer;
    font-family: inherit;
    box-sizing: border-box;
    line-height: 1.4;
}
.settingsHub .hubBtn:hover { background: #efefef; border-color: #bdbdbd; }
.settingsHub .hubBadge {
    display: inline-block;
    padding: 1px 7px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.4;
}
.settingsHub .hubBadge-green { background: #e5f5d4; color: #3d7a22; border: 1px solid #b7d99a; }
.settingsHub .hubBadge-orange { background: #ffe8d4; color: #c45e12; border: 1px solid #f0b889; }
.settingsHub .hubBadge-gray { background: #eee; color: #666; border: 1px solid #ccc; }
.settingsHub .hubToggleForm { margin: 0; display: inline-block; }
.settingsHub .hubToggle {
    position: relative;
    width: 44px;
    height: 24px;
    padding: 0;
    border: 0;
    border-radius: 12px;
    background: #d0d0d0;
    cursor: pointer;
    vertical-align: middle;
}
.settingsHub .hubToggle.is-on { background: #71d000; }
.settingsHub .hubToggleKnob {
    position: absolute;
    top: 2px;
    right: 2px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 2px rgba(0,0,0,.2);
}
.settingsHub .hubToggle.is-on .hubToggleKnob { right: auto; left: 2px; }
html:not([dir="rtl"]) .settingsHub .hubSectionHead,
html:not([dir="rtl"]) .settingsHub .hubMeta { text-align: left; }
html:not([dir="rtl"]) .settingsHub .hubRow { direction: ltr; }
html:not([dir="rtl"]) .settingsHub .hubToggleKnob { right: auto; left: 2px; }
html:not([dir="rtl"]) .settingsHub .hubToggle.is-on .hubToggleKnob { left: auto; right: 2px; }
</style>

<?php
$gkSpielerGreek = !empty($GLOBALS['gkSpielerGreek']);
$gkHideClassicMenu = !empty($GLOBALS['gkSpielerLiteralPage'])
    || (class_exists('GreekSpieler') && GreekSpieler::suppressClassicMenu());
if (!$gkHideClassicMenu) {
    echo '<h1>' . PLAYER_PROFILE . '</h1>';
    include __DIR__ . '/menu.tpl';
}
?>

<div class="settingsHub">

    <section class="hubSection">
        <header class="hubSectionHead"><?php echo OVERVIEW; ?></header>
        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo OVERVIEW; ?></div>
                <div class="hubHint"><?php echo PLAYER_PROFILE; ?> — <?php echo VILLAGES; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubBtn('spieler.php?uid=' . $hubUid . '&amp;details=1', $manage); ?></div>
        </div>
    </section>

    <section class="hubSection">
        <header class="hubSectionHead"><?php echo PROFILE; ?></header>
        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo CHANGE_PROFILE; ?></div>
                <div class="hubHint"><?php echo PREF_CHANGE_NAME_HINT; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubBtn('spieler.php?s=1', $manage); ?></div>
        </div>
        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo PREF_NAME_RESERVATION; ?></div>
                <div class="hubHint"><?php echo sprintf(PREF_NAME_SLOT, $heroCount, 2); ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubBtn(
                !empty($GLOBALS['gkSpielerGreek']) ? 'spieler.php?s=3&nr=1' : $heroMansionLink,
                $manage
            ); ?></div>
        </div>
    </section>

    <section class="hubSection">
        <header class="hubSectionHead"><?php echo PREFERENCES; ?></header>
        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo PREFERENCES; ?></div>
                <div class="hubHint"><?php echo DIRECT_LINKS; ?> · <?php echo REPORT_FILTER; ?> · <?php echo TZ_TIME_PREFERENCE; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubBtn('spieler.php?s=2', $manage); ?></div>
        </div>
    </section>

    <section class="hubSection">
        <header class="hubSectionHead"><?php echo PREF_OPTIONS; ?></header>

        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo PREF_NIGHT_MODE; ?></div>
                <div class="hubHint"><?php echo $nightOn ? PREF_DARK_MODE : PREF_LIGHT_MODE; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubToggle('night_mode', $nightOn, $hubAction); ?></div>
        </div>

        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo PREF_STOP_AUTO_UPDATE; ?></div>
                <div class="hubHint"><?php echo PREF_TIMER_REFRESH; ?>: <?php echo $stopAutoOn ? PREF_NO : PREF_YES; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubToggle('stop_auto_update', $stopAutoOn, $hubAction); ?></div>
        </div>

        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo PREF_INVERT_COLORS; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubToggle('invert_colors', $invertOn, $hubAction); ?></div>
        </div>

        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo PREF_MAP_STYLE; ?></div>
                <div class="hubHint"><?php echo TZ_SHOW_THE_LARGE_MAP_IN_AN_EXTRA_WIN; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubToggle('map', $mapOn, $hubAction); ?></div>
        </div>

        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo PREF_REDIRECT_BEHAVIOR; ?></div>
                <div class="hubHint">
                    <span class="hubBadge hubBadge-green"><?php echo htmlspecialchars($upgradeLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            </div>
            <div class="hubAction"><?php echo tzHubCycle($hubAction, 'upgrade_redirect', $manage); ?></div>
        </div>

        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo PREF_MOBILE_MODE; ?></div>
                <div class="hubHint"><?php echo $mobileOn ? PREF_MOBILE_PHONE : PREF_MOBILE_DESKTOP; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubToggle('mobile_mode', $mobileOn, $hubAction); ?></div>
        </div>

        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo PREF_STATS_FORMAT; ?></div>
                <div class="hubHint">
                    <span class="hubBadge hubBadge-green"><?php echo htmlspecialchars($statsLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            </div>
            <div class="hubAction"><?php echo tzHubCycle($hubAction, 'stats_format', $manage); ?></div>
        </div>

        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo TZ_NO_REPORTS_FOR_TRANSFERS_TO_OWN_VI; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubToggle('v4', $v4On, $hubAction); ?></div>
        </div>
        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo TZ_NO_REPORTS_FOR_TRANSFERS_TO_FOREIG; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubToggle('v5', $v5On, $hubAction); ?></div>
        </div>
        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo TZ_NO_REPORTS_FOR_TRANSFERS_FROM_FORE; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubToggle('v6', $v6On, $hubAction); ?></div>
        </div>
    </section>

    <section class="hubSection">
        <header class="hubSectionHead"><?php echo ACCOUNT; ?></header>
        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo ACCOUNT; ?></div>
                <div class="hubHint"><?php echo defined('PASSWORD') ? PASSWORD : ''; ?> · <?php echo defined('EMAIL') ? EMAIL : ''; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubBtn('spieler.php?s=3', $manage); ?></div>
        </div>
        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo ACCOUNT_SITTERS; ?></div>
                <div class="hubHint"><?php echo ACCOUNT_SITTERS2; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubBtn('spieler.php?s=3#sitter', $manage); ?></div>
        </div>
        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo DELETE_ACCOUNT; ?></div>
                <div class="hubHint"><?php echo DELETE_ACCOUNT2; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubBtn('spieler.php?s=3#del_acc', $manage); ?></div>
        </div>
    </section>

    <section class="hubSection">
        <header class="hubSectionHead"><?php echo PREF_SECTION_NOTIF; ?></header>
        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo PREF_WEB_NOTIFICATIONS; ?></div>
                <div class="hubHint">
                    <span id="hubNotifBadge" class="hubBadge hubBadge-gray">…</span>
                </div>
            </div>
            <div class="hubAction">
                <form method="post" action="<?php echo htmlspecialchars($hubAction, ENT_QUOTES, 'UTF-8'); ?>" class="hubToggleForm" id="hubNotifForm">
                    <input type="hidden" name="hub_toggle" value="web_notifications" />
                    <input type="hidden" name="hub_on" id="hubNotifOn" value="<?php echo $webNotifOn ? '0' : '1'; ?>" />
                    <button type="button" class="hubToggle<?php echo $webNotifOn ? ' is-on' : ''; ?>" id="hubNotifToggle" title="<?php echo $webNotifOn ? 'ON' : 'OFF'; ?>" aria-pressed="<?php echo $webNotifOn ? 'true' : 'false'; ?>">
                        <span class="hubToggleKnob"></span>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section class="hubSection">
        <header class="hubSectionHead"><?php echo PREF_LINKED_ACCOUNTS; ?></header>
        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo PREF_LINKED_ACCOUNTS; ?></div>
                <div class="hubHint"><?php echo PREF_LINKED_ACCOUNTS_SETTINGS; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubBtn('feeding.php', $manage); ?></div>
        </div>
    </section>

    <?php if (defined('NEW_FUNCTIONS_VACATION') && NEW_FUNCTIONS_VACATION) { ?>
    <section class="hubSection">
        <header class="hubSectionHead"><?php echo htmlspecialchars($vacationTitle, ENT_QUOTES, 'UTF-8'); ?></header>
        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo htmlspecialchars($vacationTitle, ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="hubHint"><?php echo htmlspecialchars($vacationHint, ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubBtn('spieler.php?s=5', $manage); ?></div>
        </div>
    </section>
    <?php } ?>

    <?php if (defined('GP_ENABLE') && GP_ENABLE) { ?>
    <section class="hubSection">
        <header class="hubSectionHead"><?php echo GRAPH_PACK; ?></header>
        <div class="hubRow">
            <div class="hubMeta">
                <div class="hubTitle"><?php echo GRAPH_PACK; ?></div>
            </div>
            <div class="hubAction"><?php echo tzHubBtn('spieler.php?s=4', $manage); ?></div>
        </div>
    </section>
    <?php } ?>

</div>

<script type="text/javascript">
(function () {
    var toggle = document.getElementById('hubNotifToggle');
    var form = document.getElementById('hubNotifForm');
    var onField = document.getElementById('hubNotifOn');
    var badge = document.getElementById('hubNotifBadge');
    if (!toggle || !form || !onField || !badge) return;

    var dbOn = <?php echo $webNotifOn ? 'true' : 'false'; ?>;
    var tOn = <?php echo json_encode(PREF_YES); ?>;
    var tOff = <?php echo json_encode(PREF_NO); ?>;
    var tBlocked = <?php echo json_encode(PREF_STATUS_BLOCKED); ?>;
    var tUnsupported = <?php echo json_encode('المتصفح لا يدعم إشعارات الويب'); ?>;
    var nTitle = <?php echo json_encode(defined('PREF_WEB_NOTIF_TITLE') ? PREF_WEB_NOTIF_TITLE : PREF_WEB_NOTIFICATIONS); ?>;
    var nBody = <?php echo json_encode(defined('PREF_WEB_NOTIF_BODY') ? PREF_WEB_NOTIF_BODY : ''); ?>;
    var serverName = <?php echo json_encode(defined('SERVER_NAME') ? SERVER_NAME : 'Novaterra'); ?>;

    function paintBadge(text, cls) {
        badge.className = 'hubBadge ' + cls;
        badge.textContent = text;
    }

    function paintToggle(isOn) {
        if (isOn) {
            toggle.className = 'hubToggle is-on';
            toggle.setAttribute('aria-pressed', 'true');
            toggle.title = 'ON';
            onField.value = '0';
        } else {
            toggle.className = 'hubToggle';
            toggle.setAttribute('aria-pressed', 'false');
            toggle.title = 'OFF';
            onField.value = '1';
        }
    }

    function syncUi() {
        if (!('Notification' in window)) {
            paintBadge(tUnsupported, 'hubBadge-orange');
            paintToggle(false);
            return;
        }
        if (Notification.permission === 'denied') {
            paintBadge(tBlocked, 'hubBadge-orange');
            paintToggle(false);
            return;
        }
        var on = dbOn && Notification.permission === 'granted';
        paintToggle(on);
        paintBadge(on ? tOn : tOff, on ? 'hubBadge-green' : 'hubBadge-gray');
    }

    function fireRealNotification() {
        try {
            var n = new Notification(nTitle || serverName, {
                body: nBody || serverName,
                icon: 'favicon.ico',
                badge: 'favicon.ico',
                tag: 'tz-web-notif-test',
                renotify: true,
                silent: false,
                requireInteraction: false
            });
            n.onclick = function () {
                window.focus();
                n.close();
            };
            setTimeout(function () {
                try { n.close(); } catch (e) {}
            }, 8000);
        } catch (e) {}
    }

    function savePref(turnOn) {
        onField.value = turnOn ? '1' : '0';
        form.submit();
    }

    toggle.onclick = function () {
        if (!('Notification' in window)) {
            syncUi();
            return;
        }
        if (Notification.permission === 'denied') {
            paintBadge(tBlocked, 'hubBadge-orange');
            return;
        }

        var currentlyOn = toggle.className.indexOf('is-on') !== -1;

        if (currentlyOn) {
            savePref(false);
            return;
        }

        if (Notification.permission === 'granted') {
            fireRealNotification();
            setTimeout(function () { savePref(true); }, 500);
            return;
        }

        Notification.requestPermission().then(function (perm) {
            if (perm === 'granted') {
                fireRealNotification();
                setTimeout(function () { savePref(true); }, 500);
            } else {
                paintBadge(tBlocked, 'hubBadge-orange');
                paintToggle(false);
            }
        });
    };

    syncUi();
})();
</script>
