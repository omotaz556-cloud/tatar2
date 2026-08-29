<?php
/**
 * Profile medals table body (shared by profile.tpl and Greek edit).
 * Expects: $varmedal, $gkMedalClass, $gkSpielerGreek, $session, $database.
 * Optional: $gkMedalGreekLayout (6-column Greek.sa layout).
 */

$gkMedalGreekLayout = !empty($gkMedalGreekLayout);
$gkMedalColspan = $gkMedalGreekLayout ? 6 : 4;
$gkMedalCellPad = $gkMedalGreekLayout ? 1 : 0;
$gkMedalIcoLabel = defined('TZ_PROF_MEDAL_COL') ? TZ_PROF_MEDAL_COL : 'الوسام';
$gkMedalPointsLabel = defined('POINTS_M') ? POINTS_M : 'النقاط';
$gkMedalAddLabel = defined('TZ_ADD') ? TZ_ADD : 'إضافة';
$gkDovePeaceLabel = defined('TZ_PROF_DOVE_PEACE') ? TZ_PROF_DOVE_PEACE : 'حمامة السلام';
$gkMedalNa = 'غير متاح';
$gkMedalZero = '0';

$gkMedalIcon = static function ($src, $alt = '') {
    $src = htmlspecialchars((string) $src, ENT_QUOTES, 'UTF-8');
    $alt = htmlspecialchars((string) $alt, ENT_QUOTES, 'UTF-8');
    return '<img class="gk-medal-badge" src="' . $src . '" alt="' . $alt . '" />';
};

$gkMedalAddLink = static function ($code, $iconHtml = '') use ($gkMedalGreekLayout) {
    $jsCode = str_replace(['\\', "'"], ['\\\\', "\\'"], $code);
    $onclick = "insertMedal('" . $jsCode . "'); return false;";
    if ($iconHtml !== '') {
        return '<a href="#" class="gk-medal-add-link" title="إدراج الوسام" onclick="' . $onclick . '">' . $iconHtml . '</a>';
    }
    $safeCode = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
    return '<a href="#" class="gk-medal-add-link" title="إدراج الوسام" onclick="' . $onclick . '">' . $safeCode . '</a>';
};

$gkEmitMedalRow = static function (
    $category,
    $rank,
    $week,
    $addCell,
    $iconHtml = '',
    $points = '0'
) use ($gkMedalGreekLayout, $gkSpielerGreek) {
    if (empty($gkSpielerGreek) && function_exists('tz_arabic_digits')) {
        $rank = tz_arabic_digits((string) $rank);
        $week = tz_arabic_digits((string) $week);
        $points = tz_arabic_digits((string) $points);
    }
    if ($gkMedalGreekLayout) {
        echo '<tr>'
            . '<td class="gk-medal-ico">' . $iconHtml . '</td>'
            . '<td>' . $category . '</td>'
            . '<td class="gk-val-num">' . htmlspecialchars((string) $rank, ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td class="gk-val-num">' . htmlspecialchars((string) $week, ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td class="gk-val-num">' . htmlspecialchars((string) $points, ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td class="gk-medal-add">' . $addCell . '</td>'
            . '</tr>';
        return;
    }
    echo '<tr>'
        . '<td>' . $category . '</td>'
        . '<td>' . $rank . '</td>'
        . '<td>' . $week . '</td>'
        . '<td>' . $addCell . '</td>'
        . '</tr>';
};
?>
<table cellpadding="<?php echo (int) $gkMedalCellPad; ?>" cellspacing="<?php echo (int) $gkMedalCellPad; ?>" class="<?php echo $gkMedalClass; ?>">

<tr><td class="rbg" colspan="<?php echo (int) $gkMedalColspan; ?>"><?php echo MEDALS; ?></td></tr>

<tr class="gk-medal-cols">
<?php if ($gkMedalGreekLayout) { ?>
<td><?php echo htmlspecialchars($gkMedalIcoLabel, ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo CATEGORY; ?></td>
<td><?php echo RANK; ?></td>
<td><?php echo WEEK; ?></td>
<td><?php echo htmlspecialchars($gkMedalPointsLabel, ENT_QUOTES, 'UTF-8'); ?></td>
<td class="gk-medal-add-h"><?php echo htmlspecialchars($gkMedalAddLabel, ENT_QUOTES, 'UTF-8'); ?></td>
<?php } else { ?>
<td><?php echo CATEGORY; ?></td>
<td><?php echo RANK; ?></td>
<td><?php echo WEEK; ?></td>
<td><?php echo BB_CODE; ?></td>
<?php } ?>
</tr>

<?php
foreach ($varmedal as $medal) {

    $titel = TZ_MEDAL_BONUS_DEFAULT;

    switch ($medal['categorie']) {
        case "1": $titel=TZ_MEDAL_ATTACKER_WEEK; break;
        case "2": $titel=TZ_MEDAL_DEFENDER_WEEK; break;
        case "3": $titel=TZ_MEDAL_POP_CLIMBER_WEEK; break;
        case "4": $titel=TZ_MEDAL_ROBBER_WEEK; break;
        case "5": $titel=TZ_MEDAL_TOP10_ATT_DEF; break;
        case "6": $titel=TZ_MEDAL_TOP_ATTACK_STREAK." ".$medal['points']; break;
        case "7": $titel=TZ_MEDAL_TOP_DEF_STREAK." ".$medal['points']; break;
        case "8": $titel=TZ_MEDAL_TOP_POP_STREAK." ".$medal['points']; break;
        case "9": $titel=TZ_MEDAL_TOP_ROB_STREAK." ".$medal['points']; break;
        case "10": $titel=TZ_MEDAL_RANK_CLIMBER; break;
        case "11": $titel=TZ_MEDAL_RANK_STREAK." ".$medal['points']; break;
        case "12": $titel=TZ_MEDAL_TOP10_ATTACK; break;
        case "13": $titel=TZ_MEDAL_TOP10_DEF; break;
        case "14": $titel=TZ_MEDAL_TOP10_POP; break;
        case "15": $titel=TZ_MEDAL_TOP10_ROB; break;
        case "16": $titel=TZ_MEDAL_TOP10_RANK; break;
    }

    $medalImage = preg_replace('/[^a-zA-Z0-9_.-]/', '', (string)($medal['img'] ?? ''));
    $medalImageUrl = $medalImage !== ''
        ? GP_LOCATE . 'img/t/' . rawurlencode($medalImage) . '.jpg'
        : '';
    $medalPreview = $medalImageUrl !== ''
        ? $gkMedalIcon($medalImageUrl, $titel)
        : '';
    $medalRank = !empty($medal['plaats']) ? $medal['plaats'] : $gkMedalNa;
    $medalWeek = !empty($medal['week']) ? $medal['week'] : $gkMedalNa;
    $medalPoints = isset($medal['points']) ? (string) $medal['points'] : $gkMedalZero;
    $medalCode = '[#' . $medal['id'] . ']';
    $addCell = $gkMedalAddLink($medalCode, $medalPreview !== '' ? $medalPreview : '');
    $gkEmitMedalRow($titel, $medalRank, $medalWeek, $addCell, $medalPreview, $medalPoints);
}

$doveIcon = $gkMedalIcon(GP_LOCATE . 'img/t/tn.gif', '[#0]');
$gkEmitMedalRow(
    $gkDovePeaceLabel,
    $gkMedalZero,
    $gkMedalZero,
    $gkMedalAddLink('[#0]', $doveIcon),
    $doveIcon,
    $gkMedalZero
);
?>

<?php
$gkVet3Label = defined('ADM_MEDAL_VETERAN_PLAYER') ? ADM_MEDAL_VETERAN_PLAYER : VETERAN_P;
$gkVet5Label = defined('ADM_MEDAL_VETERAN_PLAYER_5A') ? ADM_MEDAL_VETERAN_PLAYER_5A : VETERAN_P;
$gkVet10Label = defined('ADM_MEDAL_VETERAN_PLAYER_10A') ? ADM_MEDAL_VETERAN_PLAYER_10A : VETERAN_P;
?>
<?php if (NEW_FUNCTIONS_MEDAL_3YEAR): ?>
<?php
$vet3Icon = $gkMedalIcon(GP_LOCATE . 'img/t/Veteran_Medal.jpg', '[#g2300]');
$gkEmitMedalRow($gkVet3Label, $gkMedalZero, $gkMedalZero, $gkMedalAddLink('[#g2300]', $vet3Icon), $vet3Icon, $gkMedalZero);
?>
<?php endif; ?>

<?php if (NEW_FUNCTIONS_MEDAL_5YEAR): ?>
<?php
$vet5Icon = $gkMedalIcon(GP_LOCATE . 'img/t/5year_medal.png', '[#g2301]');
$gkEmitMedalRow($gkVet5Label, $gkMedalZero, $gkMedalZero, $gkMedalAddLink('[#g2301]', $vet5Icon), $vet5Icon, $gkMedalZero);
?>
<?php endif; ?>

<?php if (NEW_FUNCTIONS_MEDAL_10YEAR): ?>
<?php
$vet10Icon = $gkMedalIcon(GP_LOCATE . 'img/t/10_year_medal.png', '[#g2302]');
$gkEmitMedalRow($gkVet10Label, $gkMedalZero, $gkMedalZero, $gkMedalAddLink('[#g2302]', $vet10Icon), $vet10Icon, $gkMedalZero);
?>
<?php endif; ?>

<?php
$tribeMedals = [];

if (defined('NEW_FUNCTIONS_TRIBE_IMAGES') && NEW_FUNCTIONS_TRIBE_IMAGES) {
    $tribeMedals[1] = [TRIBE1, 'roman'];
    $tribeMedals[2] = [TRIBE2, 'teuton'];
    $tribeMedals[3] = [TRIBE3, 'gaul'];
}

if (defined('NEW_FUNCTION_TRIBE_HUNS') && NEW_FUNCTION_TRIBE_HUNS) {
    $tribeMedals[6] = ['Huns', 'huns'];
}

if (defined('NEW_FUNCTION_TRIBE_EGIPTEANS') && NEW_FUNCTION_TRIBE_EGIPTEANS) {
    $tribeMedals[7] = ['Egyptians', 'egyptians'];
}

if (defined('NEW_FUNCTION_TRIBE_SPARTANS') && NEW_FUNCTION_TRIBE_SPARTANS) {
    $tribeMedals[8] = ['Spartans', 'spartans'];
}

if (defined('NEW_FUNCTION_TRIBE_VIKINGS') && NEW_FUNCTION_TRIBE_VIKINGS) {
    $tribeMedals[9] = ['Vikings', 'vikings'];
}

$tribe = $session->userinfo['tribe'] ?? 0;

if (isset($tribeMedals[$tribe])) {
    [$name, $tag] = $tribeMedals[$tribe];
    $badgeImage = ['roman' => 'roman.gif', 'teuton' => 'teutons.gif', 'gaul' => 'gauls.gif'][$tag] ?? 'roman.gif';
    $tribeIcon = $gkMedalIcon(GP_LOCATE . 'img/t/' . $badgeImage, '[#' . $tag . ']');
    $tribeLabel = defined('TZ_PROF_TRIBE_MEDAL') ? TZ_PROF_TRIBE_MEDAL . ' ' . $name : 'القبيلة ' . $name;
    $gkEmitMedalRow(
        $tribeLabel,
        $gkMedalNa,
        $gkMedalNa,
        $gkMedalAddLink('[#' . $tag . ']', $tribeIcon),
        $tribeIcon,
        $gkMedalZero
    );
}

if (defined('NEW_FUNCTIONS_MHS_IMAGES') && NEW_FUNCTIONS_MHS_IMAGES) {

    if (($session->userinfo['access'] ?? 0) == 9) {

        $mhIcon = $gkMedalIcon(GP_LOCATE . 'img/t/t6_1.png', '[#MULTIHUNTER]');
        $gkEmitMedalRow(ADMIN1, $gkMedalNa, $gkMedalNa, $gkMedalAddLink('[#MULTIHUNTER]', $mhIcon), $mhIcon, $gkMedalZero);

        $mh2Icon = $gkMedalIcon(GP_LOCATE . 'img/t/MH.png', '[#MH]');
        $gkEmitMedalRow(ADMIN1, $gkMedalNa, $gkMedalNa, $gkMedalAddLink('[#MH]', $mh2Icon), $mh2Icon, $gkMedalZero);

        $teamIcon = $gkMedalIcon(GP_LOCATE . 'img/t/team.png', '[#TEAM]');
        $gkEmitMedalRow(ADMIN1, $gkMedalNa, $gkMedalNa, $gkMedalAddLink('[#TEAM]', $teamIcon), $teamIcon, $gkMedalZero);

    } elseif (($session->userinfo['access'] ?? 0) == 8) {

        $mhIcon = $gkMedalIcon(GP_LOCATE . 'img/t/t6_1.png', '[#MULTIHUNTER]');
        $gkEmitMedalRow(MULTIH1, $gkMedalNa, $gkMedalNa, $gkMedalAddLink('[#MULTIHUNTER]', $mhIcon), $mhIcon, $gkMedalZero);

        $mh2Icon = $gkMedalIcon(GP_LOCATE . 'img/t/MH.png', '[#MH]');
        $gkEmitMedalRow(MULTIH1, $gkMedalNa, $gkMedalNa, $gkMedalAddLink('[#MH]', $mh2Icon), $mh2Icon, $gkMedalZero);

        $teamIcon = $gkMedalIcon(GP_LOCATE . 'img/t/team.png', '[#TEAM]');
        $gkEmitMedalRow(MULTIH1, $gkMedalNa, $gkMedalNa, $gkMedalAddLink('[#TEAM]', $teamIcon), $teamIcon, $gkMedalZero);
    }
}

if (($session->userinfo['username'] ?? '') == "Shadow") {

    $gkEmitMedalRow('Shadow', '', '', $gkMedalAddLink('[#SHADOW]'));
    $gkEmitMedalRow('Shadow', '', '', $gkMedalAddLink('[#MH]'));
    $gkEmitMedalRow('Shadow', '', '', $gkMedalAddLink('[#TEAM]'));
    $gkEmitMedalRow('Shadow', '', '', $gkMedalAddLink('[#EVENT]'));
}

if (defined('NEW_FUNCTIONS_SPECIAL_MEDALS_SYSTEM') && NEW_FUNCTIONS_SPECIAL_MEDALS_SYSTEM) {
    $uid = (int)$session->uid;

    $arte = $database->query("SELECT 1 FROM ".TB_PREFIX."artefacts WHERE owner = $uid LIMIT 1");
    if ($arte && $arte->num_rows > 0) {
        $arteIcon = $gkMedalIcon(GP_LOCATE . 'img/gloriamedals/artifact.png', '[#ARTEFACT]');
        $gkEmitMedalRow(TZ_MEDAL_ARTEFACT_HOLDER, $gkMedalZero, $gkMedalZero, $gkMedalAddLink('[#ARTEFACT]', $arteIcon), $arteIcon, $gkMedalZero);
    }

    $ww = $database->query("SELECT f.f99 FROM ".TB_PREFIX."vdata v
        INNER JOIN ".TB_PREFIX."fdata f ON f.vref = v.wref
        WHERE v.owner = $uid AND f.f99t = 40 AND f.f99 > 0 LIMIT 1");
    if ($ww && $ww->num_rows > 0) {
        $wwIcon = $gkMedalIcon(GP_LOCATE . 'img/gloriamedals/ww_builder.png', '[#WWBUILDER]');
        $gkEmitMedalRow(TZ_MEDAL_WW_BUILDER, $gkMedalZero, $gkMedalZero, $gkMedalAddLink('[#WWBUILDER]', $wwIcon), $wwIcon, $gkMedalZero);

        $lvl = (int)$ww->fetch_assoc()['f99'];
        if ($lvl >= 100) {
            $wwWinIcon = $gkMedalIcon(GP_LOCATE . 'img/gloriamedals/ww_winner.png', '[#WINNERWW]');
            $gkEmitMedalRow(TZ_MEDAL_WW_WINNER, $gkMedalZero, $gkMedalZero, $gkMedalAddLink('[#WINNERWW]', $wwWinIcon), $wwWinIcon, $gkMedalZero);
        }
    }

    $hasGreatStore = false;
    $qgs = $database->query("SELECT f.* FROM ".TB_PREFIX."fdata f 
                             JOIN ".TB_PREFIX."vdata v ON v.wref=f.vref 
                             WHERE v.owner=$uid");
    if ($qgs) {
        while ($f = $qgs->fetch_assoc()) {
            $hasWh = $hasGr = false;
            for ($i = 1; $i <= 99; $i++) {
                if (!isset($f["f{$i}t"])) {
                    continue;
                }
                $type = (int)$f["f{$i}t"];
                $lvl  = (int)$f["f{$i}"];
                if ($type == 38 && $lvl == 20) {
                    $hasWh = true;
                }
                if ($type == 39 && $lvl == 20) {
                    $hasGr = true;
                }
            }
            if ($hasWh && $hasGr) {
                $hasGreatStore = true;
                break;
            }
        }
    }
    if ($hasGreatStore) {
        $gsIcon = $gkMedalIcon(GP_LOCATE . 'img/gloriamedals/greatstore.png', '[#GREATSTORE]');
        $gkEmitMedalRow(TZ_MEDAL_GREAT_STORE, $gkMedalZero, $gkMedalZero, $gkMedalAddLink('[#GREATSTORE]', $gsIcon), $gsIcon, $gkMedalZero);
    }

    $wallCount = 0;
    $qw = $database->query("SELECT f.f40, f.f40t FROM ".TB_PREFIX."fdata f 
                        JOIN ".TB_PREFIX."vdata v ON v.wref=f.vref 
                        WHERE v.owner=$uid");
    if ($qw) {
        while ($r = $qw->fetch_assoc()) {
            if ((int)$r['f40'] == 20 && in_array((int)$r['f40t'], [31, 32, 33, 42, 43, 47, 50])) {
                $wallCount++;
            }
        }
    }
    if ($wallCount >= 3) {
        $wallIcon = $gkMedalIcon(GP_LOCATE . 'img/gloriamedals/wallmaster.png', '[#WALLMASTER]');
        $gkEmitMedalRow(TZ_MEDAL_WALL_MASTER, $gkMedalZero, $gkMedalZero, $gkMedalAddLink('[#WALLMASTER]', $wallIcon), $wallIcon, $gkMedalZero);
    }

    $h100 = $database->query("SELECT 1 FROM ".TB_PREFIX."hero WHERE uid=$uid AND level>=99 LIMIT 1");
    if ($h100 && $h100->num_rows) {
        $heroIcon = $gkMedalIcon(GP_LOCATE . 'img/gloriamedals/hero.png', '[#HERO100]');
        $gkEmitMedalRow('Hero 99+', $gkMedalZero, $gkMedalZero, $gkMedalAddLink('[#HERO100]', $heroIcon), $heroIcon, $gkMedalZero);
    }
}
?>

</table>
