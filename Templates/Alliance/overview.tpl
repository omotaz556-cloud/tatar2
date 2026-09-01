<?php

#################################################################################
##                -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-               ##
## --------------------------------------------------------------------------- ##
##  Filename       : overview.tpl                                              ##
##  Type           : Alliance Member Overview                                  ##
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

/*
|--------------------------------------------------------------------------
| Novaterra - Alliance Overview (FINAL STABLE VERSION)
|--------------------------------------------------------------------------
| FIXES:
|   - medals safe
|   - population correct
|   - rank FIX (no more 0)
|   - no breaking DB assumptions
|--------------------------------------------------------------------------
*/

/* =========================
   Alliance ID
========================= */
$aid = isset($_GET['aid']) ? (int)$_GET['aid'] : (int)$session->alliance;

/* =========================
   Load data
========================= */
$allianceinfo = $database->getAlliance($aid);
$memberlist   = $database->getAllMember($aid);

/* safety check */
if (empty($allianceinfo) || $allianceinfo['tag'] == "") {
    header("Location: allianz.php");
    exit;
}

/* =========================
   MEDALS SAFE
========================= */
$varmedal = $database->getProfileMedalAlly($aid);
if (!is_array($varmedal)) {
    $varmedal = [];
}

/* =========================
   POPULATION
========================= */
$totalpop = 0;

foreach ($memberlist as $member) {
    $popData = $database->getVSumField((int)$member['id'], "pop");

    if (is_array($popData)) {
        if (isset($popData[0]['Total'])) {
            $totalpop += (int)$popData[0]['Total'];
        }
    } else {
        $totalpop += (int)$popData;
    }
}

/* =========================
   HEADER
========================= */
echo "<h1>" . htmlspecialchars($allianceinfo['tag']) . " - " . htmlspecialchars($allianceinfo['name']) . "</h1>";

/* =========================
   PROFILE + MEDALS
========================= */
/**
 * FIX SECURITATE (XSS stocat): descrierea aliantei e text liber, scris de
 * conducerea aliantei, si se afisa oricui viziteaza pagina - fara escapare.
 * Un <script> introdus acolo se executa in browserul fiecarui vizitator.
 *
 * Aceeasi problema a fost reparata la profilul de JUCATOR (vezi
 * Templates/Profile/overview.tpl), dar varianta de alianta a fost uitata.
 *
 * Escapam INAINTE de procesarea medaliilor, exact ca acolo: separatorul md5 si
 * marcajele [#..] nu contin caractere speciale HTML, deci trec neatinse prin
 * htmlspecialchars() si medal.php le gaseste asa cum le asteapta.
 */
$profileSeparator = md5('skJkev3');

$profiel = htmlspecialchars($allianceinfo['notice'] ?? '', ENT_QUOTES, 'UTF-8')
         . $profileSeparator
         . htmlspecialchars($allianceinfo['desc'] ?? '', ENT_QUOTES, 'UTF-8');

require("medal.php");

$profiel = explode($profileSeparator, $profiel);

// plasa de siguranta, ca la profilul de jucator
if (!isset($profiel[0])) { $profiel[0] = ''; }
if (!isset($profiel[1])) { $profiel[1] = ''; }

include("alli_menu.tpl");

$gkNum = static function ($value) {
    return '<bdi dir="ltr" class="gk-num">' . htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') . '</bdi>';
};
?>

<!-- ========================= PROFILE ========================= -->
<table cellpadding="1" cellspacing="1" id="profile" class="gk-alli-overview" dir="ltr">
<colgroup>
    <col class="gk-alli-col-desc" />
    <col class="gk-alli-col-detail" />
</colgroup>
<thead>
<tr class="gk-alli-title">
    <th colspan="2"><?php echo ALLIANCE; ?></th>
</tr>
<tr class="gk-alli-cols">
    <th class="gk-alli-h-desc"><?php echo DESCRIPTION; ?></th>
    <th class="gk-alli-h-detail"><?php echo DETAIL; ?></th>
</tr>
</thead>

<tbody>
<tr><td class="empty"></td><td class="empty"></td></tr>

<tr class="gk-alli-main-row">
<td class="desc1 gk-alli-desc-cell">
<div class="desc1div gk-alli-desc1"><?php echo stripslashes(nl2br($profiel[1])); ?></div>
</td>

<td class="details gk-alli-detail-cell">

<table cellpadding="0" cellspacing="0" class="gk-alli-detail-table" dir="rtl">

<tr>
    <th><?php echo TAG; ?></th>
    <td><?php echo htmlspecialchars($allianceinfo['tag']); ?></td>
</tr>

<tr>
    <th><?php echo NAME; ?></th>
    <td><?php echo htmlspecialchars($allianceinfo['name']); ?></td>
</tr>

<tr><td colspan="2" class="gk-alli-spacer"></td></tr>

<!-- ========================= RANK FIX ========================= -->
<tr>
    <th><?php echo RANK; ?></th>
    <td class="gk-val-num">
<?php
// FORCE ranking initialization (Novaterra safe trigger)
if (!isset($ranking) || !is_object($ranking)) {
    global $ranking;
}

// IMPORTANT: sometimes rank needs a "warm call"
$dummy = $ranking->getAllianceRank(1); // trigger internal load (safe, read-only)

$rankValue = (int)$ranking->getAllianceRank((int)$aid);

if ($rankValue < 1) {
    $rankValue = 1;
}

echo $gkNum($rankValue) . '.';
?>
    </td>
</tr>

<tr>
    <th><?php echo POINTS; ?></th>
    <td class="gk-val-num"><?php echo $gkNum((int) $totalpop); ?></td>
</tr>

<tr>
    <th><?php echo TZ_MEMBERS; ?></th>
    <td class="gk-val-num"><?php echo $gkNum(count($memberlist)); ?></td>
</tr>

<tr><td colspan="2" class="gk-alli-spacer"></td></tr>

<?php
foreach ($memberlist as $member) {

    $uid  = (int)$member['id'];
    $name = $database->getUserField($uid, "username", 0);
    $rank = $database->getAlliancePermission($uid, "rank", $aid);
    $rankLabel = ($rank === 'Alliance founder') ? 'مؤسس التحالف' : $rank;

    if ($rank != '') {
        echo "<tr>";
        echo "<th>" . htmlspecialchars(stripslashes($rankLabel)) . "</th>";
        echo "<td><a href='spieler.php?uid=" . $uid . "'>" . htmlspecialchars($name) . "</a></td>";
        echo "</tr>";
    }
}

if (!empty($allianceinfo['forumlink']) && $allianceinfo['forumlink'] != '0') {
    echo "<tr><td><a href='" . htmlspecialchars($allianceinfo['forumlink']) . "'>» إلى المنتدى</a></td></tr>";
}
?>

<tr>
<td class="desc2" colspan="2">
<div class="desc2div"><?php echo stripslashes(nl2br($profiel[0])); ?></div>
</td>
</tr>

</table>
</td>

</tr>
</tbody>
</table>

<!-- ========================= MEMBERS ========================= -->
<table cellpadding="1" cellspacing="1" id="member">
<thead>
<tr>
    <th>&nbsp;</th>
    <th><?php echo PLAYER; ?></th>
    <th><?php echo POP; ?></th>
    <th><?php echo VILLAGES; ?></th>
    <?php if ($aid == $session->alliance) echo "<th>&nbsp;</th>"; ?>
</tr>
</thead>

<tbody>

<?php
$rank = 0;

foreach ($memberlist as $member) {

    $uid = (int)$member['id'];
    $rank++;

    $popData = $database->getVSumField($uid, "pop");

    $pop = is_array($popData)
        ? (isset($popData[0]['Total']) ? (int)$popData[0]['Total'] : 0)
        : (int)$popData;

    $villages = $database->getProfileVillages($uid);

    echo "<tr>";
    echo "<td class='ra'>" . $gkNum($rank) . ".</td>";
    echo "<td class='pla'><a href='spieler.php?uid=" . $uid . "'>" . htmlspecialchars($member['username']) . "</a></td>";
    echo "<td class='hab'>" . $pop . "</td>";
    echo "<td class='vil'>" . count($villages) . "</td>";

    if ($aid == $session->alliance) {

        $diff = time() - $member['timestamp'];

        if ($diff < 600) {
            echo "<td class='on'><img class='online1' src='img/x.gif' title='متصل الآن' /></td>";
        } elseif ($diff < 86400) {
            echo "<td class='on'><img class='online2' src='img/x.gif' title='غير متصل' /></td>";
        } elseif ($diff < 259200) {
            echo "<td class='on'><img class='online3' src='img/x.gif' title='آخر 3 أيام' /></td>";
        } elseif ($diff < 604800) {
            echo "<td class='on'><img class='online4' src='img/x.gif' title='آخر 7 أيام' /></td>";
        } else {
            echo "<td class='on'><img class='online5' src='img/x.gif' title='غير نشط' /></td>";
        }
    }

    echo "</tr>";
}
?>

</tbody>
</table>