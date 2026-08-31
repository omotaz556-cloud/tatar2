<?php
/**
 * Shared village list (قائمة القرى) — same markup on every Greek-shell page.
 */
global $database, $village, $session;

if (!isset($gkSelfPage)) {
    $gkSelfPage = basename($_SERVER['PHP_SELF']);
}

if (!isset($vDisplayName)) {
    if (!empty($village) && is_object($village) && isset($village->vname)) {
        $vDisplayName = function_exists('tz_display_village_name')
            ? tz_display_village_name($village->vname, $session->username ?? null)
            : $village->vname;
    } else {
        $vDisplayName = '';
    }
}

$gkCapLabel = defined('TZ_PROF_CAP_SHORT') ? TZ_PROF_CAP_SHORT : (defined('CAPITAL1') ? CAPITAL1 : 'عاصمة');
$gkCx = (!empty($village) && is_object($village) && isset($village->coor['x'])) ? (int) $village->coor['x'] : 0;
$gkCy = (!empty($village) && is_object($village) && isset($village->coor['y'])) ? (int) $village->coor['y'] : 0;
$gkIsCap = (!empty($village) && is_object($village) && !empty($village->capital));
$gkCur = isset($_SESSION['wid']) ? (int) $_SESSION['wid'] : (
    (!empty($village) && is_object($village) && isset($village->wid)) ? (int) $village->wid : 0
);

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

$gkMarketTitle = defined('MARKET') ? MARKET : 'السوق';
$gkMapTitle = defined('MAP') ? MAP : 'الخريطة';
$gkVilRows = (isset($database) && is_object($database) && method_exists($database, 'getArrayMemberVillage'))
    ? $database->getArrayMemberVillage($session->uid)
    : array();
?>
<div class="gk-vlist-side">
    <table class="MyVs" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <th colspan="2">
                    <a href="dorf3.php" class="C_K">قائمة القرى</a>
                </th>
            </tr>
            <?php
            if (is_array($gkVilRows) && count($gkVilRows) > 0) {
                foreach ($gkVilRows as $gkVilRow) {
                    $gkVid = (int) $gkVilRow['wref'];
                    $gkIsCur = ($gkVid === $gkCur);
                    $gkVnm = htmlspecialchars(
                        tz_display_village_name($gkVilRow['name'], $session->username ?? null),
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    $gkVx = isset($gkVilRow['x']) ? (int) $gkVilRow['x'] : 0;
                    $gkVy = isset($gkVilRow['y']) ? (int) $gkVilRow['y'] : 0;
                    $gkVcap = !empty($gkVilRow['capital']);
                    $gkNameCls = $gkIsCur ? 'C_O' : '';
                    $gkHref = htmlspecialchars($gkSelfPage, ENT_QUOTES, 'UTF-8') . '?newdid=' . $gkVid . $gkBuildQs;
                    ?>
            <tr>
                <th colspan="2" class="gk-vil-row">
                    <div class="gk-vil-line"><a<?php echo $gkNameCls !== '' ? ' class="' . $gkNameCls . '"' : ''; ?> href="<?php echo htmlspecialchars($gkHref, ENT_QUOTES, 'UTF-8'); ?>"><?php echo $gkVnm; ?></a><?php if ($gkVcap) { ?><span class="fl">(<?php echo $gkCapLabel; ?>)</span><?php } ?><span class="gk-vil-actions-inline"><a href="build.php?id=39" title="<?php echo htmlspecialchars($gkMarketTitle, ENT_QUOTES, 'UTF-8'); ?>"><p class="Rs x6"></p></a><a href="karte.php?x=<?php echo $gkVx; ?>&amp;y=<?php echo $gkVy; ?>" title="<?php echo htmlspecialchars($gkMapTitle, ENT_QUOTES, 'UTF-8'); ?>"><p class="Rs x5"></p></a></span><bdi class="gk-vil-coords">(<?php echo $gkVx; ?>,<?php echo $gkVy; ?>)</bdi></div>
                </th>
            </tr>
                    <?php
                }
            } else {
                $gkHref = htmlspecialchars($gkSelfPage, ENT_QUOTES, 'UTF-8');
                ?>
            <tr>
                <th colspan="2" class="gk-vil-row">
                    <div class="gk-vil-line"><a class="C_O" href="<?php echo $gkHref; ?>"><?php echo htmlspecialchars($vDisplayName, ENT_QUOTES, 'UTF-8'); ?></a><?php if ($gkIsCap) { ?><span class="fl">(<?php echo $gkCapLabel; ?>)</span><?php } ?><span class="gk-vil-actions-inline"><a href="build.php?id=39" title="<?php echo htmlspecialchars($gkMarketTitle, ENT_QUOTES, 'UTF-8'); ?>"><p class="Rs x6"></p></a><a href="karte.php?x=<?php echo $gkCx; ?>&amp;y=<?php echo $gkCy; ?>" title="<?php echo htmlspecialchars($gkMapTitle, ENT_QUOTES, 'UTF-8'); ?>"><p class="Rs x5"></p></a></span><bdi class="gk-vil-coords">(<?php echo $gkCx; ?>,<?php echo $gkCy; ?>)</bdi></div>
                </th>
            </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
