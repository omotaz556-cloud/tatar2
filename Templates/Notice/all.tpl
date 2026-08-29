<?php

#################################################################################
##                -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-               ##
## --------------------------------------------------------------------------- ##
##  Filename       : all.tpl                                                   ##
##  Type           : Reports Inbox - List and Filters                          ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  URLs:          : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : Novaterra Project                                          ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.              ##
## --------------------------------------------------------------------------- ##
#################################################################################

// ======================== NOTICE TYPES ========================
$noticeClass = [
    TZ_RPT_SCOUT,
    TZ_RPT_WON_ATK_NOLOSS,
    TZ_RPT_WON_ATK_LOSS,
    TZ_RPT_LOST_ATK_LOSS,
    TZ_RPT_WON_DEF_NOLOSS,
    TZ_RPT_WON_DEF_LOSS,
    TZ_RPT_LOST_DEF_LOSS,
    TZ_RPT_LOST_DEF_NOLOSS,
    TZ_RPT_REINF_ARRIVED,
    "",
    TZ_RPT_WOOD_DELIVERED,
    TZ_RPT_CLAY_DELIVERED,
    TZ_RPT_IRON_DELIVERED,
    TZ_RPT_CROP_DELIVERED,
    "",
    TZ_RPT_WON_DEF_NOLOSS,
    TZ_RPT_WON_DEF_LOSS,
    TZ_RPT_LOST_DEF_LOSS,
    TZ_RPT_WON_SCOUT_ATK,
    TZ_RPT_LOST_SCOUT_ATK,
    TZ_RPT_WON_SCOUT_DEF,
    TZ_RPT_LOST_SCOUT_DEF,
    TZ_RPT_SCOUT
];

// Settler reports (issue #178) - sparse indices 24/25
$noticeClass[24] = TZ_RT_NEW_VILLAGE;
$noticeClass[25] = TZ_RT_VALLEY_OCCUPIED;
// HERO T4 REPORTS - sparse indices 24/25
$noticeClass[26] = HERO_ADV_MOV_OUT;
$noticeClass[27] = HERO_ADV_MOV_BACK;       

// ======================== GOLD CHECK (cached query) ========================
$uid = (int)$session->uid;

$MyGold = mysqli_query(
    $database->dblink,
    "SELECT plus FROM ".TB_PREFIX."users WHERE id='".$uid."'"
);

$golds = mysqli_fetch_array($MyGold);

// ======================== PAGINATION ========================
$s = isset($_GET['s']) ? (int)$_GET['s'] : 0;
$t = isset($_GET['t']) ? (int)$_GET['t'] : 0;
$o = isset($_GET['o']) ? (int)$_GET['o'] : 0;

// ======================== URL BUILD HELP ========================
// Baza pentru linkurile de paginare. Pastreaza si filtrul de rezultat (f),
// altfel trecerea la pagina urmatoare l-ar pierde si ai vedea din nou toate
// rapoartele de lupta.
$queryBase = (!empty($_GET['t'])) ? 't='.(int)$_GET['t'].'&amp;' : '';

if (!empty($_GET['t']) && (int)$_GET['t'] === 3 && !empty($_GET['f'])) {
    $queryBase .= 'f='.(int)$_GET['f'].'&amp;';
}

$gkRptGreek = !empty($GLOBALS['gkBerichteLiteralPage']);
$gkRptTableClass = $gkRptGreek ? 'gk-rpt-overview' : 'row_table_data';

$gkRptReadAllHref = 'berichte.php?readall=1';
if ($t > 0) {
    $gkRptReadAllHref .= '&amp;t=' . $t;
}
if ($t === 3 && !empty($_GET['f'])) {
    $gkRptReadAllHref .= '&amp;f=' . (int) $_GET['f'];
}
$gkRptReadAllLabel = defined('TZ_MARK_ALL_READ') ? TZ_MARK_ALL_READ : 'اجعلها مقروءة';
$gkRptReadOrPrefix = defined('TZ_RPT_MARK_ALL_READ_OR_PREFIX') ? TZ_RPT_MARK_ALL_READ_OR_PREFIX : 'أو';
$gkRptReadAllLinkText = defined('TZ_RPT_MARK_ALL_READ_LINK') ? TZ_RPT_MARK_ALL_READ_LINK : 'إجعله مقروءة';

?>

<form method="post" action="berichte.php" name="msg">

<table cellpadding="0" cellspacing="0" id="overview" class="<?php echo $gkRptTableClass; ?>">
<?php if ($gkRptGreek) { ?>
<colgroup>
    <col class="gk-rpt-c-sel" />
    <col class="gk-rpt-c-sub" />
    <col class="gk-rpt-c-dat" />
</colgroup>
<?php } ?>

<thead>
<?php if ($gkRptGreek) { ?>
<tr class="gk-rpt-title">
    <th colspan="3"><?php echo REPORTS; ?></th>
</tr>
<tr class="gk-rpt-cols">
    <th colspan="2" class="gk-rpt-h-sub"><?php echo SUBJECT; ?></th>
    <th class="sent gk-rpt-h-dat"><?php echo DATE; ?></th>
</tr>
<?php } else { ?>
<tr>
    <th colspan="2"><?php echo SUBJECT; ?>:</th>
    <th class="sent">
        <a href="berichte.php?o=1<?php
            echo (!empty($_GET['t']) ? '&amp;t='.(int)$_GET['t'] : '');
            echo (!empty($_GET['t']) && (int)$_GET['t'] === 3 && !empty($_GET['f']))
                ? '&amp;f='.(int)$_GET['f'] : '';
        ?>"><?php echo SENT; ?></a>
    </th>
</tr>
<?php } ?>
</thead>

<tfoot>
<tr>
<?php if ($gkRptGreek) { ?>
<th class="gk-rpt-foot-sel">
<?php if ($golds['plus'] > strtotime("NOW")) { ?>
    <input class="check" type="checkbox" id="s10" name="s10" onclick="Allmsg(this.form);" />
<?php } ?>
    <input type="submit" name="del_x" value="<?php echo DELETE; ?>" id="btn_delete" class="gk-rpt-del" />
</th>
<th class="gk-rpt-foot-mid">
    <span class="gk-rpt-read-or"><?php echo htmlspecialchars($gkRptReadOrPrefix, ENT_QUOTES, 'UTF-8'); ?></span>
    <a href="<?php echo $gkRptReadAllHref; ?>" class="gk-rpt-read-link"><?php echo htmlspecialchars($gkRptReadAllLinkText, ENT_QUOTES, 'UTF-8'); ?></a>
</th>
<?php } else { ?>
<th>
<?php if ($golds['plus'] > strtotime("NOW")) { ?>
    <input class="check" type="checkbox" id="s10" name="s10" onclick="Allmsg(this.form);" />
<?php } ?>
</th>
<th class="buttons">
    <input name="del" type="image" id="btn_delete" class="dynamic_img"
           src="img/x.gif" value="delete" alt="<?php echo DELETE; ?>" />
    <?php if ($session->plus) { ?>
        <?php if (isset($_GET['t']) && $_GET['t'] == 5) { ?>
            <input name="start" type="image" value="back" alt="<?php echo BACK; ?>"
                   id="btn_back" class="dynamic_img" src="img/x.gif" />
        <?php } else { ?>
            <input name="archive" type="image" value="Archive" alt="<?php echo ARCHIVE; ?>"
                   id="btn_archiv" class="dynamic_img" src="img/x.gif" />
        <?php } ?>
    <?php } ?>
</th>
<?php } ?>
<th class="navi">
<?php
$total = count($message->noticearray);

if (!isset($_GET['s']) && $total <= 10) {
    echo "&laquo;&raquo;";
}
elseif (!isset($_GET['s']) && $total > 10) {
    echo "&laquo;<a href=\"?".$queryBase."s=10&amp;o=".$o."\">&raquo;</a>";
}
elseif (isset($_GET['s']) && $total > $s) {

    $prev = $s - 10;
    $next = $s + 10;

    if ($total > $next && $prev >= 0 && $s != 0) {
        echo "<a href=\"?".$queryBase."s=".$prev."&o=".$o."\">&laquo;</a>";
        echo "<a href=\"?".$queryBase."s=".$next."&o=".$o."\">&raquo;</a>";
    }
    elseif ($total > $next) {
        echo "&laquo;<a href=\"?".$queryBase."s=".$next."&o=".$o."\">&raquo;</a>";
    }
    elseif ($total > 10) {
        echo "<a href=\"?".$queryBase."s=".$prev."&o=".$o."\">&laquo;</a>&raquo;";
    }
}
?>

</th>
</tr>
</tfoot>

<tbody>

<?php
// ======================== LISTING ========================
$name = 1;
$count = 0;

for ($i = (1 + $s); $i <= (10 + $s); $i++) {

    if ($total >= $i) {

        $row = $message->noticearray[$i - 1];

        $type = (!empty($_GET['t']) && $_GET['t'] == 5)
            ? $row['archive']
            : $row['ntype'];

        if ($type == 23) $type = 22;

        $iconHtml = '';
        if ($type >= 15 && $type <= 17) {
            $iconType = $type - 11;
            $iconHtml = '<img src="img/x.gif" class="iReport iReport'.$iconType.'" alt="'
                .htmlspecialchars($noticeClass[$iconType], ENT_QUOTES, 'UTF-8').'" title="'
                .htmlspecialchars($noticeClass[$iconType], ENT_QUOTES, 'UTF-8').'" />';
        } elseif ($type >= 18 && $type <= 22) {
            $iconHtml = '<img src="gpack/novaterra_classic/img/scouts/'.$type.'.gif" alt="'
                .htmlspecialchars($noticeClass[$type], ENT_QUOTES, 'UTF-8').'" title="'
                .htmlspecialchars($noticeClass[$type], ENT_QUOTES, 'UTF-8').'" />';
        } elseif ($type == 24 || $type == 25) {
            $iconType = ($type == 24) ? 8 : 3;
            $iconHtml = '<img src="img/x.gif" class="iReport iReport'.$iconType.'" alt="'
                .htmlspecialchars($noticeClass[$type], ENT_QUOTES, 'UTF-8').'" title="'
                .htmlspecialchars($noticeClass[$type], ENT_QUOTES, 'UTF-8').'" />';
        } else {
            $iconHtml = '<img src="img/x.gif" class="iReport iReport'.$type.'" alt="'
                .htmlspecialchars($noticeClass[$type], ENT_QUOTES, 'UTF-8').'" title="'
                .htmlspecialchars($noticeClass[$type], ENT_QUOTES, 'UTF-8').'" />';
        }

        echo "<tr>";

        echo "<td class=\"sel\">
                <input class=\"check\" type=\"checkbox\" name=\"n".$name."\"
                value=\"".$row['id']."\" />
              </td>";

        echo "<td class=\"sub\">";
        if (!$gkRptGreek) {
            echo $iconHtml;
        }
        echo "<div>
                <a href=\"berichte.php?id=".$row['id']."\">".tz_loc_topic($row['topic'])."</a>";

        if (!$gkRptGreek && $row['viewed'] == 0) {
            echo " (new)";
        }

        $date = $generator->procMtime($row['time']);

        echo "</div></td>";

        if ($gkRptGreek) {
            echo "<td class=\"dat\"><span class=\"gk-rpt-dat-cell\">"
                ."<span class=\"gk-rpt-dt\">".$date[0]." ".$date[1]."</span>"
                .$iconHtml
                ."</span></td>";
        } else {
            echo "<td class=\"dat\">".$date[0]." ".$date[1]."</td>";
        }

        echo "</tr>";
    }

    $name++;
}

// ======================== EMPTY STATE ========================
if ($total == 0) {
    echo "<tr><td colspan=\"3\" class=\"none\">".NO_REPORTS.".</td></tr>";
}
?>

</tbody>
</table>

</form>