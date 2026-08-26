<?php
/**
 * Finish construction/research with gold — ALWAYS visible when a job exists.
 * Active if gold >= 2; otherwise inactive (links to Plus shop).
 */
if (!isset($session) || !isset($building)) {
    return;
}

$building->loadBuilding();

if (empty($building->NewBuilding) || empty($building->buildArray)) {
    return;
}

$availableGold = (int) ($session->gold ?? 0);
$canFinish = $availableGold >= 2 && (int) ($session->sit ?? 0) === 0;

$finishFieldId = (int) (reset($building->buildArray)['field'] ?? 1);
$finishScript = basename(str_replace('\\', '/', (string) ($_SERVER['PHP_SELF'] ?? 'dorf1.php')));
if (!in_array($finishScript, ['dorf1.php', 'dorf2.php', 'build.php'], true)) {
    $finishScript = 'dorf1.php';
}

if ($finishScript === 'build.php') {
    $qid = isset($id) ? (int) $id : $finishFieldId;
    if ($qid < 1) {
        $qid = $finishFieldId;
    }
    $finishHref = 'build.php?id=' . $qid . '&buildingFinish=1';
} else {
    $finishHref = $finishScript . '?buildingFinish=1';
}

$finishConfirm = htmlspecialchars(
    defined('FINISH_GOLD') ? FINISH_GOLD : 'Finish for 2 gold?',
    ENT_QUOTES,
    'UTF-8'
);
$finishTitle = $canFinish
    ? $finishConfirm
    : htmlspecialchars(
        defined('TOO_LITTLE_GOLD') ? TOO_LITTLE_GOLD : 'Not enough gold',
        ENT_QUOTES,
        'UTF-8'
    );
$finishLabel = htmlspecialchars(
    defined('TZ_FINISH') ? TZ_FINISH : 'Finish',
    ENT_QUOTES,
    'UTF-8'
);
$goldSrc = htmlspecialchars(
    (defined('GP_LOCATE') ? GP_LOCATE : 'gpack/novaterra_classic/') . 'img/a/gold.gif',
    ENT_QUOTES,
    'UTF-8'
);

$btnStyle = 'display:inline-block!important;visibility:visible!important;opacity:1!important;'
    . 'margin:0 8px!important;padding:3px 8px!important;border:1px solid #d2b35a!important;'
    . 'background:#fff8d7!important;color:#8a5a00!important;font-weight:700!important;'
    . 'font-size:12px!important;line-height:16px!important;text-decoration:none!important;'
    . 'white-space:nowrap!important;vertical-align:middle!important;position:relative!important;z-index:99!important;';

if (!$canFinish) {
    $btnStyle .= 'background:#f0f0f0!important;border-color:#bbb!important;color:#666!important;';
    $finishHref = 'plus.php?id=3';
}
?>
<a class="building-gold-finish<?php echo $canFinish ? '' : ' building-gold-finish--off'; ?>"
   href="<?php echo htmlspecialchars($finishHref, ENT_QUOTES, 'UTF-8'); ?>"
   <?php if ($canFinish) { ?>onclick="return confirm('<?php echo $finishConfirm; ?>');"<?php } ?>
   title="<?php echo $finishTitle; ?>"
   style="<?php echo $btnStyle; ?>">
    <img class="clock<?php echo $canFinish ? '' : ' inactive'; ?>" alt="" src="img/x.gif"
         style="display:inline-block!important;width:18px;height:12px;vertical-align:middle;" />
    <img src="<?php echo $goldSrc; ?>" alt="" width="12" height="12"
         style="display:inline-block!important;vertical-align:middle;margin:0 2px;" />
    <strong style="color:inherit;">2</strong>
    <span style="color:inherit;margin-right:3px;"><?php echo $finishLabel; ?></span>
</a>
