<?php
/**
 * Greek.sa world attack news — Statistics » News (stat?S=8).
 */

global $database;

$newsLimit = defined('WORLD_NEWS_MAX_ITEMS') ? (int) WORLD_NEWS_MAX_ITEMS : 50;
$newsItems = method_exists($database, 'getWorldNews') ? $database->getWorldNews($newsLimit) : [];

$titleNews = defined('TZ_STAT_WORLD_NEWS') ? TZ_STAT_WORLD_NEWS : 'أهم أخبار العالم';
$colNews = defined('TZ_STAT_NEWS_COL') ? TZ_STAT_NEWS_COL : 'الخبر';
$colSince = defined('TZ_STAT_NEWS_SINCE') ? TZ_STAT_NEWS_SINCE : 'منذ';
$footnote = defined('TZ_STAT_NEWS_FOOTNOTE')
    ? TZ_STAT_NEWS_FOOTNOTE
    : '* الهجمات ذات نقاط القتل القليله لاتظهر في هذه القائمة';
$emptyMsg = defined('TZ_STAT_NEWS_EMPTY') ? TZ_STAT_NEWS_EMPTY : 'لا توجد أخبار هجمات كبيرة حالياً.';

$fmtSince = static function ($ts) {
    $diff = max(0, time() - (int) $ts);
    if ($diff < 3600) {
        $m = max(1, (int) floor($diff / 60));
        return $m . ' د';
    }
    if ($diff < 86400) {
        $h = max(1, (int) floor($diff / 3600));
        return $h . ' س';
    }
    $d = max(1, (int) floor($diff / 86400));
    return $d . ' ي';
};

$buildNewsHtml = static function (array $row) use ($database) {
    $attackerUid = (int) ($row['attacker_uid'] ?? 0);
    $attackerName = htmlspecialchars((string) ($row['attacker_name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $defenderWref = (int) ($row['defender_wref'] ?? 0);
    $defenderVname = htmlspecialchars((string) ($row['defender_vname'] ?? ''), ENT_QUOTES, 'UTF-8');
    $kills = number_format((int) ($row['kills'] ?? 0));

    $attackerLink = '<a href="spieler.php?uid=' . $attackerUid . '">' . $attackerName . '</a>';

    $villageHref = 'karte.php';
    if ($defenderWref > 0 && method_exists($database, 'getCoor')) {
        $coor = $database->getCoor($defenderWref);
        if (is_array($coor) && array_key_exists('x', $coor) && array_key_exists('y', $coor)) {
            $villageHref = 'karte.php?x=' . (int) $coor['x'] . '&amp;y=' . (int) $coor['y'];
        }
    }
    $villageLink = '<a href="' . $villageHref . '">' . $defenderVname . '</a>';

    return 'قام اللاعب ' . $attackerLink
        . ' بالهجوم على ' . $villageLink
        . ' ومسح دفاعاته بقتل <b><bdi dir="ltr">' . $kills . '</bdi></b> جندي';
};
?>
<div class="gk-sta-news-wrap">
<table class="a b0 gk-sta-gen gk-sta-news" dir="rtl">
<colgroup>
    <col class="gk-sta-news-col-since">
    <col class="gk-sta-news-col-text">
</colgroup>
<tbody>
<tr class="gk-sta-head">
    <th colspan="2"><?php echo htmlspecialchars($titleNews, ENT_QUOTES, 'UTF-8'); ?></th>
</tr>
<tr class="gk-sta-cols">
    <th class="gk-sta-news-since"><?php echo htmlspecialchars($colSince, ENT_QUOTES, 'UTF-8'); ?></th>
    <th class="gk-sta-news-text"><?php echo htmlspecialchars($colNews, ENT_QUOTES, 'UTF-8'); ?></th>
</tr>
<?php if (empty($newsItems)) { ?>
<tr>
    <th class="gk-sta-news-since">—</th>
    <th class="gk-sta-news-text gk-sta-news-empty"><?php echo htmlspecialchars($emptyMsg, ENT_QUOTES, 'UTF-8'); ?></th>
</tr>
<?php } else {
    foreach ($newsItems as $row) { ?>
<tr>
    <th class="gk-sta-news-since"><?php echo htmlspecialchars($fmtSince($row['time'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></th>
    <th class="gk-sta-news-text"><?php echo $buildNewsHtml($row); ?></th>
</tr>
<?php }
} ?>
</tbody>
</table>
<p class="gk-sta-news-foot"><?php echo htmlspecialchars($footnote, ENT_QUOTES, 'UTF-8'); ?></p>
</div>
