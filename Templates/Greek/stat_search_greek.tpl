<?php
/**
 * Greek.sa literal statistics search / pagination footer.
 */

$gkStatPageId = isset($_GET['id']) ? (int) $_GET['id'] : 1;
if (!isset($_GET['id'])) {
    $gkStatHrefBase = 'statistiken.php';
} elseif ($gkStatPageId === 0) {
    $gkStatHrefBase = 'statistiken.php?id=0';
} else {
    $gkStatHrefBase = 'statistiken.php?id=' . $gkStatPageId;
}
$gkRankSep = (strpos($gkStatHrefBase, '?') !== false) ? '&' : '?';
$gkLastRank = (isset($rankArray) && count($rankArray) > 1) ? count($rankArray) - 1 : 1;
$gkSearchVal = isset($search) ? $search : 0;
$gkStartVal = isset($start) ? $start : 1;
$gkTopHref = $gkStatHrefBase . $gkRankSep . 'rank=1';
$gkBottomHref = $gkStatHrefBase . $gkRankSep . 'rank=' . (int) $gkLastRank;
$gkFt = 'r' . (int) $gkStatPageId;
?>
<table class="gk-sta-foot"><tbody><tr>
<td class="gk-sta-search">
<form method="post" action="<?php echo htmlspecialchars($gkStatHrefBase, ENT_QUOTES, 'UTF-8'); ?>">
<?php echo RANK; ?><input type="text" class="text ra" maxlength="5" name="rank" value="<?php echo ($gkSearchVal === 0 || !is_numeric($gkSearchVal)) ? (int) $gkStartVal : (int) $gkSearchVal; ?>" />
<?php echo constant('OR'); ?> <?php echo NAME; ?><input type="text" class="text name" maxlength="30" name="name" value="<?php if (!is_numeric($gkSearchVal) && $gkSearchVal !== 0 && $gkSearchVal !== '0') { echo htmlspecialchars((string) $gkSearchVal, ENT_QUOTES, 'UTF-8'); } ?>" />
<input type="hidden" name="ft" value="<?php echo htmlspecialchars($gkFt, ENT_QUOTES, 'UTF-8'); ?>" />
<button type="submit" name="submit" value="1" class="OffSu"><?php echo SEARCH; ?></button>
</form>
</td>
<td class="gk-sta-navi">
<a href="<?php echo htmlspecialchars($gkTopHref, ENT_QUOTES, 'UTF-8'); ?>">أعلى</a> |
<a href="<?php echo htmlspecialchars($gkBottomHref, ENT_QUOTES, 'UTF-8'); ?>">أسفل</a>
</td>
</tr></tbody></table>
