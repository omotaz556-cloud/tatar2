<?php
/**
 * Greek.sa literal player ranking table — matches reference screenshot.
 */

global $ranking, $session;

$search = 0;
if (!isset($_SESSION['search']) || !is_numeric($_SESSION['search'])) {
    if (!empty($_SESSION['search'])) {
        echo '<p class="gk-sta-err">' . TZ_THE_USER . ' <b>"'
            . htmlspecialchars((string) $_SESSION['search'], ENT_QUOTES, 'UTF-8') . '"</b> '
            . TZ_DOES_NOT_EXIST . '</p>';
    }
    $search = 0;
} else {
    $search = (int) $_SESSION['search'];
}

$rankArray = $ranking->getRank();

if (isset($_GET['rank']) && is_numeric($_GET['rank'])) {
    $rank = (int) $_GET['rank'];
    $count = count($rankArray);
    if ($rank > $count) {
        $rank = max(1, $count - 1);
    }
    $multiplier = 1;
    while ($rank > (20 * $multiplier)) {
        $multiplier++;
    }
    $start = 20 * $multiplier - 19;
} else {
    $start = ($_SESSION['start'] ?? 0) + 1;
}

$gkUid = isset($session->uid) ? (int) $session->uid : 0;
?>
<table class="b0 fl L6_S gk-sta-players">
<colgroup>
    <col class="gk-sta-rank-col" />
    <col class="gk-sta-pla-col" />
    <col class="gk-sta-al-col" />
    <col class="gk-sta-vil-col" />
    <col class="gk-sta-pop-col" />
    <col class="gk-sta-msg-col" />
</colgroup>
<tbody>
<tr class="gk-sta-head"><th colspan="6"><div class="gk-sta-bar">
<span class="staRi">
<?php
if (defined('NEW_FUNCTIONS_PLUS_STATISTICS') && NEW_FUNCTIONS_PLUS_STATISTICS
    && isset($session->plus) && (int) $session->plus === 1) {
    $psLabel = defined('PLUSSTATS_TITLE') ? PLUSSTATS_TITLE : 'Graphical statistics';
    echo '<a title="' . htmlspecialchars($psLabel, ENT_QUOTES, 'UTF-8') . '" href="statistiken.php?id=50">'
        . '<img class="btn_stats" src="img/x.gif" alt="" /></a>';
}
?>
<a title="<?php echo TZ_TOP_10; ?>" href="statistiken.php?id=7"><img class="btn_top10" src="img/x.gif" alt="" /></a>
<a title="<?php echo DEFENDER; ?>" href="statistiken.php?id=32"><img class="btn_def" src="img/x.gif" alt="" /></a>
<a title="<?php echo ATTACKER; ?>" href="statistiken.php?id=31"><img class="btn_off" src="img/x.gif" alt="" /></a>
</span>
<span class="staTit"><?php echo TZ_THE_LARGEST_PLAYERS; ?></span>
<span class="staLe">
<a title="<?php echo TRIBE1; ?>" href="statistiken.php?id=11"><img class="btn_v1" src="img/x.gif" alt="" /></a>
<a title="<?php echo TRIBE2; ?>" href="statistiken.php?id=12"><img class="btn_v2" src="img/x.gif" alt="" /></a>
<a title="<?php echo TRIBE3; ?>" href="statistiken.php?id=13"><img class="btn_v3" src="img/x.gif" alt="" /></a>
<?php if (defined('NEW_FUNCTION_TRIBE_HUNS') && NEW_FUNCTION_TRIBE_HUNS) { ?>
<a title="<?php echo TRIBE6; ?>" href="statistiken.php?id=16"><img class="btn_v6" src="img/x.gif" alt="" /></a>
<?php } ?>
<?php if (defined('NEW_FUNCTION_TRIBE_EGIPTEANS') && NEW_FUNCTION_TRIBE_EGIPTEANS) { ?>
<a title="<?php echo TRIBE7; ?>" href="statistiken.php?id=17"><img class="btn_v7" src="img/x.gif" alt="" /></a>
<?php } ?>
<?php if (defined('NEW_FUNCTION_TRIBE_SPARTANS') && NEW_FUNCTION_TRIBE_SPARTANS) { ?>
<a title="<?php echo TRIBE8; ?>" href="statistiken.php?id=18"><img class="btn_v8" src="img/x.gif" alt="" /></a>
<?php } ?>
<?php if (defined('NEW_FUNCTION_TRIBE_VIKINGS') && NEW_FUNCTION_TRIBE_VIKINGS) { ?>
<a title="<?php echo TRIBE9; ?>" href="statistiken.php?id=19"><img class="btn_v9" src="img/x.gif" alt="" /></a>
<?php } ?>
</span>
</div></th></tr>
<tr class="gk-sta-cols"><th class="ra">#</th><th class="pla"><?php echo PLAYER; ?></th><th class="al"><?php echo ALLIANCE; ?></th><th class="vil"><?php echo VILLAGES; ?></th><th class="pop"><?php echo POP; ?></th><th class="on"><?php echo defined('STAT_MESSAGE') ? STAT_MESSAGE : 'رسالة'; ?></th></tr>
<?php
if (count($rankArray) > 1) {
    for ($i = $start; $i < $start + 20; $i++) {
        if (!isset($rankArray[$i]['username'])) {
            continue;
        }
        $row = $rankArray[$i];
        $rowUid = (int) ($row['userid'] ?? 0);
        $isHighlight = ($i === $search) || ($rowUid === $gkUid);
        $hlCls = $isHighlight ? ' hl' : '';
        $fcCls = $isHighlight ? ' fc' : '';
        $lcCls = $isHighlight ? ' lc' : '';
        $msgCls = $isHighlight ? ' on lc' : ' on';

        echo '<tr class="' . trim($hlCls) . '">';
        echo '<th class="ra' . $fcCls . '">' . $i . '</th>';
        echo '<th class="pla">';
        $username = htmlspecialchars((string) $row['username'], ENT_QUOTES, 'UTF-8');
        if (!empty($row['access']) && $row['access'] > 2) {
            echo '<u><a href="spieler.php?uid=' . $rowUid . '">' . $username . '</a></u>';
        } else {
            echo '<a href="spieler.php?uid=' . $rowUid . '">' . $username . '</a>';
        }
        echo '</th>';
        echo '<th class="al">';
        if (!empty($row['aname']) && !empty($row['alliance'])) {
            $aid = (int) $row['alliance'];
            echo '<a href="allianz.php?aid=' . $aid . '">'
                . htmlspecialchars((string) $row['aname'], ENT_QUOTES, 'UTF-8') . '</a>';
        }
        echo '</th>';
        echo '<th class="vil">' . (int) ($row['totalvillage'] ?? 0) . '</th>';
        echo '<th class="pop">' . (int) ($row['totalpop'] ?? 0) . '</th>';
        $msgTitle = defined('WRITE_MESSAGE') ? WRITE_MESSAGE : 'Message';
        echo '<th class="' . trim($msgCls) . '"><a href="nachrichten.php?t=1&amp;id=' . $rowUid . '" title="'
            . htmlspecialchars($msgTitle, ENT_QUOTES, 'UTF-8') . '">'
            . '<svg class="statMsgIcon" viewBox="0 0 16 12" width="16" height="12" aria-hidden="true">'
            . '<rect x="0.5" y="0.5" width="15" height="11" rx="1.2" fill="#f0a000" stroke="#c87800" stroke-width="1"/>'
            . '<path d="M1 1.5 8 7 15 1.5" fill="none" stroke="#fff" stroke-width="1.4" stroke-linejoin="round"/>'
            . '</svg></a></th>';
        echo '</tr>';
    }
} else {
    echo '<tr><th class="none" colspan="6">' . TZ_NO_USERS_FOUND . '</th></tr>';
}
?>
</tbody></table>
<?php include __DIR__ . '/stat_search_greek.tpl'; ?>
