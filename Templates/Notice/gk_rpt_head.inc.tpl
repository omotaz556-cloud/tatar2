<?php
$gkRptGreek = !empty($GLOBALS['gkBerichteLiteralPage']);
$gkRptSurroundCols = $gkRptGreek ? 3 : 2;
$GLOBALS['gkRptSurroundCols'] = $gkRptSurroundCols;
$gkRptDate = $generator->procMtime($message->readingNotice['time']);

if ($gkRptGreek && class_exists('GreekBerichte')) {
    GreekBerichte::reportNav((int) ($message->readingNotice['id'] ?? 0));
}
?>
<table cellpadding="0" cellspacing="0" id="report_surround"<?php echo $gkRptGreek ? ' class="gk-rpt-surround"' : ''; ?>>
<?php if ($gkRptGreek) { ?>
<colgroup>
    <col class="gk-rpt-col-label" />
    <col class="gk-rpt-col-main" />
    <col class="gk-rpt-col-meta" />
</colgroup>
<?php } ?>
<thead>
<?php if ($gkRptGreek && class_exists('GreekBerichte')) {
    GreekBerichte::reportHeadRows($message->readingNotice, $gkRptDate);
} else { ?>
<tr>
    <th><?php echo SUBJECT; ?>:</th>
    <th><?php echo tz_loc_topic($message->readingNotice['topic']); ?></th>
</tr>
<tr>
    <td class="sent"><?php echo TZ_SENT; ?></td>
    <td><?php echo ON; ?> <span><?php echo $gkRptDate[0] . ' ' . TZ_AT . ' ' . $gkRptDate[1]; ?></span> <span><?php echo TZ_HOUR; ?></span></td>
</tr>
<?php } ?>
</thead>
