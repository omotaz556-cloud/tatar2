<?php
/**
 * End-of-era victory report (Wonder of the World level 100).
 *
 * Expected variables (set in winner.php):
 *   $serverName, $worldLabel, $goldPrize
 *   $wwWinnerUid, $wwWinnerName, $allianceid, $winningalliancetag
 *   $vref, $winningvillagename, $finishconstruction
 *   $topPop, $topAtt, $topDef, $topHero
 *   $generator
 */
if (!isset($serverName)) {
    $serverName = defined('SERVER_NAME') ? SERVER_NAME : '';
}
$hl = static function ($html) {
    return '<span class="tz-winner-hl">' . $html . '</span>';
};
$playerLink = static function ($uid, $name) use ($hl) {
    $uid = (int) $uid;
    $safe = htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8');
    if ($safe === '') {
        return $hl('—');
    }
    if ($uid <= 0) {
        return $hl($safe);
    }
    return $hl('<a href="spieler.php?uid=' . $uid . '">' . $safe . '</a>');
};
$allyLink = static function ($aid, $tag) use ($hl) {
    $aid = (int) $aid;
    $safe = htmlspecialchars((string) $tag, ENT_QUOTES, 'UTF-8');
    if ($safe === '') {
        return $hl('—');
    }
    if ($aid <= 0) {
        return $hl($safe);
    }
    return $hl('<a href="allianz.php?aid=' . $aid . '">' . $safe . '</a>');
};

$allyHtml = $allyLink($allianceid ?? 0, $winningalliancetag ?? '');
$winnerHtml = $playerLink($wwWinnerUid ?? 0, $wwWinnerName ?? '');
$popHtml = $playerLink(
    isset($topPop['userid']) ? $topPop['userid'] : 0,
    isset($topPop['username']) ? $topPop['username'] : ''
);
$attHtml = $playerLink(
    isset($topAtt['userid']) ? $topAtt['userid'] : 0,
    isset($topAtt['username']) ? $topAtt['username'] : ''
);
$defHtml = $playerLink(
    isset($topDef['userid']) ? $topDef['userid'] : 0,
    isset($topDef['username']) ? $topDef['username'] : ''
);
$heroHtml = $playerLink(
    isset($topHero['userid']) ? $topHero['userid'] : 0,
    isset($topHero['username']) ? $topHero['username'] : ''
);
$goldFmt = number_format((int) ($goldPrize ?? 50000));
$worldBit = !empty($worldLabel)
    ? ' ' . sprintf(
        defined('WINNER_RPT_WORLD_SUFFIX') ? WINNER_RPT_WORLD_SUFFIX : 'للعالم %s',
        htmlspecialchars((string) $worldLabel, ENT_QUOTES, 'UTF-8')
    )
    : '';
$wwImg = GP_LOCATE . 'img/g/g40_5.gif';
$titleText = sprintf(
    defined('WINNER_RPT_DEAR') ? WINNER_RPT_DEAR : 'اعزائنا لاعبي %s',
    $serverName
);
?>
<div class="tz-winner-wrap">
    <div class="tz-winner-report">
        <div class="tz-winner-title"><?php echo htmlspecialchars($titleText, ENT_QUOTES, 'UTF-8'); ?></div>

        <img class="tz-winner-ww" src="<?php echo htmlspecialchars($wwImg, ENT_QUOTES, 'UTF-8'); ?>" alt="" />

        <p><?php
            echo sprintf(
                defined('WINNER_RPT_INTRO') ? WINNER_RPT_INTRO
                    : 'وبعد أيام طويلة من الجهد والتعب والعمل بشغف استطاع تحالف %1$s أن يبنوا معجزتهم التي نافسهم عليها بقية اللاعبين، حيث رصدوا لها الملايين من الموارد.',
                $allyHtml
            );
        ?></p>

        <p><?php
            echo sprintf(
                defined('WINNER_RPT_WINNER') ? WINNER_RPT_WINNER
                    : 'نتيجة التنسيق الجيد والعمل الجماعي تمكن أخيراً العمال في %1$s من تشييد أروع مبنى في اللعبة وبهذا يستلم %2$s لقب الفائز في هذه العالم وجائزة %3$s ذهبية وسيسجل اسمه وتحالفه في لائحة ابطال %4$s%5$s.',
                $allyHtml,
                $winnerHtml,
                $hl($goldFmt),
                htmlspecialchars($serverName, ENT_QUOTES, 'UTF-8'),
                $worldBit
            );
        ?></p>

        <p><?php
            echo sprintf(
                defined('WINNER_RPT_EMPIRE') ? WINNER_RPT_EMPIRE
                    : 'نستطيع الآن أن نقول بأن %s هو الحاكم المطلق لأكبر امبراطورية في اللعبة.',
                $popHtml
            );
        ?></p>

        <p><?php
            echo sprintf(
                defined('WINNER_RPT_ATTACKER') ? WINNER_RPT_ATTACKER
                    : 'وقام %s بمهاجمة الأعداء وذبحهم أكثر من أي شخص أخر، لذلك يعتبر القائد الأقوى.',
                $attHtml
            );
        ?></p>

        <p><?php
            echo sprintf(
                defined('WINNER_RPT_DEFENDER') ? WINNER_RPT_DEFENDER
                    : 'أما %s فقد كان المدافع الأعظم فقد قام بذبح أعدائه المهاجمين وملأ الأرض حول قراه بدمهم.',
                $defHtml
            );
        ?></p>

        <p><?php
            echo sprintf(
                defined('WINNER_RPT_HERO') ? WINNER_RPT_HERO
                    : 'وكان %s قد درب بطله وهاجم وقضى على الأعداء ويستحق أن نقول أن لديه بطل الأبطال.',
                $heroHtml
            );
        ?></p>

        <p class="tz-winner-thanks"><?php
            echo sprintf(
                defined('WINNER_RPT_THANKS') ? WINNER_RPT_THANKS
                    : 'نحن فريق %s يجب ان نشكر كل من واصل اللعب حتى النهاية باخلاص.',
                htmlspecialchars($serverName, ENT_QUOTES, 'UTF-8')
            );
        ?></p>
    </div>

    <div class="tz-winner-continue">
        <a href="winner.php?ok=1">&raquo; <?php
            echo defined('WINNER_RPT_FORWARD') ? WINNER_RPT_FORWARD : 'إلى الأمام';
        ?></a>
    </div>
</div>
