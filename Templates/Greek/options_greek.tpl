<?php
/**
 * Greek.sa options page (spieler.php?uid=X&hub=1) — خيارات.
 */

$ui = function_exists('tz_user_display_prefs_values')
    ? tz_user_display_prefs_values($session)
    : (is_array($session->userinfo ?? null) ? $session->userinfo : array());
$gkUid = (int) $session->uid;

$gkMobile = (int) ($ui['mobile_mode'] ?? 0);
$gkTimerRefresh = !empty($ui['timer_refresh']) ? 1 : 0;
$gkInvert = !empty($ui['invert_colors']) ? 1 : 0;
$gkStats = (int) ($ui['stats_format'] ?? 0);

$gkSaved = !empty($_GET['saved']);

$gkLblTitle = defined('PREF_OPTIONS') ? PREF_OPTIONS : 'خيارات';
$gkLblColOpt = defined('TZ_GK_OPT_COL_OPTION') ? TZ_GK_OPT_COL_OPTION : 'الخيار';
$gkLblColVal = defined('TZ_GK_OPT_COL_VALUES') ? TZ_GK_OPT_COL_VALUES : 'القيم';
$gkLblMobile = defined('PREF_MOBILE_MODE') ? PREF_MOBILE_MODE : 'وضع الجوال';
$gkLblMobileAuto = defined('PREF_MOBILE_AUTO') ? PREF_MOBILE_AUTO : 'تلقائي';
$gkLblMobileDesk = defined('PREF_MOBILE_DESKTOP') ? PREF_MOBILE_DESKTOP : 'الكمبيوتر';
$gkLblMobilePhone = defined('PREF_MOBILE_PHONE') ? PREF_MOBILE_PHONE : 'الجوال';
$gkLblTimer = defined('PREF_TIMER_REFRESH') ? PREF_TIMER_REFRESH : 'التحديث عند انتهاء الوقت';
$gkLblYes = defined('PREF_YES') ? PREF_YES : 'نعم';
$gkLblNo = defined('TZ_GK_OPT_TIMER_NO_EXAMPLE') ? TZ_GK_OPT_TIMER_NO_EXAMPLE : 'لا (مثال:00:00:10)';
$gkLblInvert = defined('PREF_INVERT_COLORS') ? PREF_INVERT_COLORS : 'عكس الألوان';
$gkLblNoPlain = defined('PREF_NO') ? PREF_NO : 'لا';
$gkLblStats = defined('TZ_GK_OPT_STATS_SHAPE') ? TZ_GK_OPT_STATS_SHAPE : (defined('PREF_STATS_FORMAT') ? PREF_STATS_FORMAT : 'شكل الاحصائيات');
$gkLblStatsAuto = defined('PREF_STATS_AUTO') ? PREF_STATS_AUTO : 'تلقائي';
$gkLblStatsClassic = defined('PREF_STATS_CLASSIC') ? PREF_STATS_CLASSIC : 'كلاسيكي';
$gkLblStatsCompact = defined('PREF_STATS_COMPACT') ? PREF_STATS_COMPACT : 'مختصر';
$gkLblSave = defined('SAVE') ? SAVE : 'حفظ';
$gkLblSaved = defined('TZ_GK_OPT_SAVED') ? TZ_GK_OPT_SAVED : 'تم حفظ الخيارات.';
?>
<?php if ($gkSaved) { ?>
<p class="gk-options-saved"><?php echo htmlspecialchars($gkLblSaved, ENT_QUOTES, 'UTF-8'); ?></p>
<?php } ?>
<form action="spieler.php?uid=<?php echo $gkUid; ?>&amp;hub=1" method="post" class="gk-options-form">
<input type="hidden" name="ft" value="p2" />
<input type="hidden" name="gk_options_only" value="1" />

<table cellpadding="1" cellspacing="1" id="display_options" class="gk-acc-table gk-options-table">
    <colgroup>
        <col class="gk-opt-col-name" />
        <col class="gk-opt-col-values" />
    </colgroup>
    <thead>
        <tr>
            <th colspan="2" class="rbg"><?php echo htmlspecialchars($gkLblTitle, ENT_QUOTES, 'UTF-8'); ?></th>
        </tr>
        <tr class="gk-options-cols gk-acc-cols">
            <th class="gk-opt-h-name"><?php echo htmlspecialchars($gkLblColOpt, ENT_QUOTES, 'UTF-8'); ?></th>
            <th class="gk-opt-h-values"><?php echo htmlspecialchars($gkLblColVal, ENT_QUOTES, 'UTF-8'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th class="gk-opt-name"><?php echo htmlspecialchars($gkLblMobile, ENT_QUOTES, 'UTF-8'); ?></th>
            <td class="gk-opt-values">
                <label><input class="radio" type="radio" name="mobile_mode" value="0"<?php echo $gkMobile === 0 ? ' checked' : ''; ?> /> <?php echo htmlspecialchars($gkLblMobileAuto, ENT_QUOTES, 'UTF-8'); ?></label><br />
                <label><input class="radio" type="radio" name="mobile_mode" value="1"<?php echo $gkMobile === 1 ? ' checked' : ''; ?> /> <?php echo htmlspecialchars($gkLblMobileDesk, ENT_QUOTES, 'UTF-8'); ?></label><br />
                <label><input class="radio" type="radio" name="mobile_mode" value="2"<?php echo $gkMobile === 2 ? ' checked' : ''; ?> /> <?php echo htmlspecialchars($gkLblMobilePhone, ENT_QUOTES, 'UTF-8'); ?></label>
            </td>
        </tr>
        <tr>
            <th class="gk-opt-name"><?php echo htmlspecialchars($gkLblTimer, ENT_QUOTES, 'UTF-8'); ?></th>
            <td class="gk-opt-values">
                <label><input class="radio" type="radio" name="timer_refresh" value="1"<?php echo $gkTimerRefresh === 1 ? ' checked' : ''; ?> /> <?php echo htmlspecialchars($gkLblYes, ENT_QUOTES, 'UTF-8'); ?></label><br />
                <label><input class="radio" type="radio" name="timer_refresh" value="0"<?php echo $gkTimerRefresh === 0 ? ' checked' : ''; ?> /> <?php echo htmlspecialchars($gkLblNo, ENT_QUOTES, 'UTF-8'); ?></label>
            </td>
        </tr>
        <tr>
            <th class="gk-opt-name"><?php echo htmlspecialchars($gkLblInvert, ENT_QUOTES, 'UTF-8'); ?></th>
            <td class="gk-opt-values">
                <label><input class="radio" type="radio" name="invert_colors" value="0"<?php echo $gkInvert === 0 ? ' checked' : ''; ?> /> <?php echo htmlspecialchars($gkLblNoPlain, ENT_QUOTES, 'UTF-8'); ?></label><br />
                <label><input class="radio" type="radio" name="invert_colors" value="1"<?php echo $gkInvert === 1 ? ' checked' : ''; ?> /> <?php echo htmlspecialchars($gkLblYes, ENT_QUOTES, 'UTF-8'); ?></label>
            </td>
        </tr>
        <tr>
            <th class="gk-opt-name"><?php echo htmlspecialchars($gkLblStats, ENT_QUOTES, 'UTF-8'); ?></th>
            <td class="gk-opt-values">
                <label><input class="radio" type="radio" name="stats_format" value="0"<?php echo $gkStats === 0 ? ' checked' : ''; ?> /> <?php echo htmlspecialchars($gkLblStatsAuto, ENT_QUOTES, 'UTF-8'); ?></label><br />
                <label><input class="radio" type="radio" name="stats_format" value="1"<?php echo $gkStats === 1 ? ' checked' : ''; ?> /> <?php echo htmlspecialchars($gkLblStatsClassic, ENT_QUOTES, 'UTF-8'); ?></label><br />
                <label><input class="radio" type="radio" name="stats_format" value="2"<?php echo $gkStats === 2 ? ' checked' : ''; ?> /> <?php echo htmlspecialchars($gkLblStatsCompact, ENT_QUOTES, 'UTF-8'); ?></label>
            </td>
        </tr>
    </tbody>
</table>

<p class="gk-options-btn-wrap">
    <button type="submit" class="gk-prof-save gk-options-save-btn"><?php echo htmlspecialchars($gkLblSave, ENT_QUOTES, 'UTF-8'); ?></button>
</p>
</form>
