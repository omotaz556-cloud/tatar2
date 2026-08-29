<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : a2b2.php                      	                           ##
##  Type           : In Game Account Gold Statement                            ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow						                               ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  URLs:          : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################

use App\Utils\AccessLogger;
include_once("GameEngine/Village.php");
AccessLogger::logRequest();

$uid = (int)$session->uid;
$amount = (int)($_SESSION['amount'] ?? 0);
$accountRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();
function accountText($arabic, $english) {
    global $accountRtl;
    return $accountRtl ? $arabic : $english;
}

function accountTranslateAction($action) {
    global $accountRtl;
    if (!$accountRtl || $action === '') {
        return $action;
    }

    static $map = array(
        'Finish all constructions' => 'إنهاء جميع البناءات',
        'Speed up troop training' => 'تسريع تدريب القوات',
        'Gold resource purchase' => 'شراء موارد بالذهب',
        'Gold transfer out' => 'تحويل ذهب صادر',
        'Gold transfer in' => 'تحويل ذهب وارد',
        'Buy Novaterra Plus' => 'شراء ميزة بلس',
        'Use 100 gold for Gold Club' => 'استخدام 100 ذهب لنادي الذهب',
        'Admin added Gold' => 'إضافة ذهب من الإدارة',
        'Admin Gift' => 'هدية من الإدارة',
        'Admin Gift (All)' => 'هدية جماعية من الإدارة',
        'Registration bonus Gold' => 'مكافأة تسجيل ذهب',
        'Biweekly medal reward' => 'مكافأة ميدالية نصف شهرية',
        'World Wonder alliance leader reward' => 'مكافأة قائد تحالف أعجوبة العالم',
        'World Wonder alliance member reward' => 'مكافأة عضو تحالف أعجوبة العالم',
        'Gold promo code redemption' => 'استبدال رمز ذهب',
        'Gold purchase completed' => 'اكتمال شراء الذهب',
    );

    if (isset($map[$action])) {
        return $map[$action];
    }

    if (preg_match('/^Use (\d+) gold for \+25% (Lumber|Clay|Iron|Crop)$/i', $action, $m)) {
        $resources = array(
            'Lumber' => 'الخشب',
            'Clay' => 'الطين',
            'Iron' => 'الحديد',
            'Crop' => 'المحاصيل',
        );
        $res = $resources[$m[2]] ?? $m[2];
        return 'استخدام ' . $m[1] . ' ذهب لمكافأة ' . $res . ' +25%';
    }

    if (preg_match('/^Bought (.+) for (\d+) gold$/i', $action, $m)) {
        return 'شراء ' . $m[1] . ' مقابل ' . $m[2] . ' ذهب';
    }

    return $action;
}

function accountTranslateDetails($details) {
    global $accountRtl;
    if (!$accountRtl || $details === '') {
        return $details;
    }

    static $map = array(
        'Gold Club activated' => 'تم تفعيل نادي الذهب',
        'Buy Novaterra Plus' => 'شراء ميزة بلس',
        'Finish construction and research with gold' => 'إنهاء البناء والأبحاث بالذهب',
        'Speed up troop training with gold' => 'تسريع تدريب القوات بالذهب',
        'World Wonder completed' => 'اكتمال أعجوبة العالم',
        'Registration bonus' => 'مكافأة التسجيل',
    );

    if (isset($map[$details])) {
        return $map[$details];
    }

    if (preg_match('/^\+25% Production: (Lumber|Clay|Iron|Crop)$/i', $details, $m)) {
        $resources = array(
            'Lumber' => 'الخشب',
            'Clay' => 'الطين',
            'Iron' => 'الحديد',
            'Crop' => 'المحاصيل',
        );
        $res = $resources[$m[1]] ?? $m[1];
        return 'إنتاج ' . $res . ' +25%';
    }

    if (preg_match('/^To (.+)$/i', $details, $m)) {
        return 'إلى ' . $m[1];
    }

    if (preg_match('/^From (.+)$/i', $details, $m)) {
        return 'من ' . $m[1];
    }

    if (preg_match('/^wood \+(\d+), clay \+(\d+), iron \+(\d+), crop \+(\d+)(?: \(each per (\d+) gold\))?$/i', $details, $m)) {
        $line = 'خشب +' . $m[1] . '، طين +' . $m[2] . '، حديد +' . $m[3] . '، محاصيل +' . $m[4];
        if (!empty($m[5])) {
            $line .= ' (لكل ' . $m[5] . ' ذهب)';
        }
        return $line;
    }

    if (preg_match('/^Medal category: (.+), place: (.+)$/i', $details, $m)) {
        return 'فئة الميدالية: ' . $m[1] . '، المركز: ' . $m[2];
    }

    if (preg_match('/^Mass gift by (.+)$/i', $details, $m)) {
        return 'هدية جماعية من ' . $m[1];
    }

    if (preg_match('/^gift by (.+)$/i', $details, $m)) {
        return 'هدية من ' . $m[1];
    }

    if (preg_match('/^by (.+)$/i', $details, $m)) {
        return 'من ' . $m[1];
    }

    return $details;
}

function accountNormalizeGiftAction($action, $details) {
    if (stripos($details, 'Mass gift') !== false) {
        return 'Admin Gift (All)';
    }
    if (stripos($details, 'gift by') !== false) {
        return 'Admin Gift';
    }
    return $action;
}

$packages = [
    199  => 60,
    499  => 120,
    999  => 360,
    1999 => 1000,
    4999 => 2000
];

if (isset($_GET['newdid'])) {
    $_SESSION['wid'] = (int)$_GET['newdid'];
    header("Location: a2b2.php");
    exit;
}

$building->procBuild($_GET);

$transactionProcessed = false;
$oldBalance = 0;
$newBalance = 0;
$goldAdded = 0;

if (isset($packages[$amount]) && $amount > 0) {
    $goldAdded = $packages[$amount];

    $result = mysqli_query($database->dblink, "SELECT gold FROM " . TB_PREFIX . "users WHERE id = $uid LIMIT 1");
    $user = mysqli_fetch_assoc($result);
    $oldBalance = (int)$user['gold'];

    mysqli_query($database->dblink, "UPDATE " . TB_PREFIX . "users SET gold = gold + $goldAdded WHERE id = $uid");

    $result = mysqli_query($database->dblink, "SELECT gold FROM " . TB_PREFIX . "users WHERE id = $uid LIMIT 1");
    $user = mysqli_fetch_assoc($result);
    $newBalance = (int)$user['gold'];

    $transactionProcessed = true;
    $_SESSION['amount'] = 0;
}
$gkShell = true;
include_once('GameEngine/GreekPlus.php');
$gkPlusCss = 'css/greek_maxb_plus.css';
$gkPlusCssVer = is_file(__DIR__ . '/' . $gkPlusCss) ? (int) @filemtime(__DIR__ . '/' . $gkPlusCss) : time();
$gkPageTitle = SERVER_NAME . ' - ' . accountText('عمليات سابقة', 'Previous transactions');
tz_greek_shell_head($gkPageTitle, 'pg-plus', array(
    'includeNew2Js' => false,
    'extraCss' => array($gkPlusCss . '?v=' . $gkPlusCssVer),
));
tz_greek_shell_open('', array('contentWrap' => false));
include("Templates/Plus/pmenu.tpl");
?>
<?php if (!class_exists('GreekPlus') || !GreekPlus::isGreekNav()) { ?>
        <h1><?php echo accountText('عمليات سابقة', 'Previous transactions'); ?></h1>
<?php } ?>
        <div id="products" class="gk-plus-history">
            <?php if ($transactionProcessed) { ?>
                <p><?php echo accountText('شكرًا لشرائك من ', 'Thank you for your purchase here at '); ?><?php echo SERVER_NAME; ?>.</p>
                <p><?php echo accountText('فيما يلي سجل العملية، ويعرض رصيد حسابك قبل العملية وبعدها.', 'Below you see the entry record. Out of it, you can observe your old as well as your new account balance.'); ?></p>
                
                <table class="plusFunctions" cellpadding="1" cellspacing="1">
                <thead>
                <tr><th colspan="5" height="20"><?php echo accountText('سجل بتاريخ ', 'Record of '); ?><?php echo date('d.m.Y'); ?></th></tr>
                <tr>
                    <td align="center"><?php echo DESCRIPTION; ?></td>
                    <td align="center"><img src="img/x.gif" class="gold" alt="<?php echo GOLD; ?>" title="<?php echo GOLD; ?>" /></td>
                    <td align="center"><?php echo ACTION; ?></td>
                    <td align="center"><img src="img/x.gif" class="gold" alt="<?php echo GOLD; ?>" title="<?php echo GOLD; ?>" /></td>
                    <td align="center"><?php echo accountText('التاريخ', 'Date'); ?></td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="desc"><b>&nbsp;&nbsp;<?php echo accountText('رصيد الحساب السابق', 'Account Balance (old)'); ?></b></td>
                    <td class="desc"><div style="text-align:center"><?php echo $oldBalance; ?></div></td>
                    <td class="desc"><div style="text-align:center">&nbsp;</div></td>
                    <td class="desc"><div style="text-align:center">&nbsp;</div></td>
                    <td class="act"><div style="text-align:center">&nbsp;</div></td>
                </tr>
                <tr>
                    <td class="desc"><div style="text-align:center">&nbsp;</div></td>
                    <td class="desc"><div style="text-align:center">&nbsp;</div></td>
                    <td class="desc"><div style="text-align:center"><b><font color="#71D000"><?php echo accountText('الباقة', 'Package'); ?></font></b></div></td>
                    <td class="desc"><div style="text-align:center"><?php echo $goldAdded; ?> <?php echo GOLD; ?></div></td>
                    <td class="act"><div style="text-align:center">&nbsp;</div></td>
                </tr>
                <tr>
                    <td class="desc"><b>&nbsp;&nbsp;<?php echo accountText('رصيد الحساب الجديد', 'Account Balance (new)'); ?></b></td>
                    <td class="desc"><div style="text-align:center">&nbsp;</div></td>
                    <td class="desc"><div style="text-align:center">&nbsp;</div></td>
                    <td class="desc"><div style="text-align:center"><?php echo $newBalance; ?></div></td>
                    <td class="act"><div style="text-align:center"><?php echo date('d.m.Y H:i:s'); ?></div></td>
                </tr>
                </tbody>
                </table>

                <p><?php echo accountText('يرجى مراجعة المعلومات وإبلاغنا في حال وجود أي خطأ.', 'Please verify the information. It will let us know if the data is incorrect.'); ?></p>
                <p><?php echo accountText('أرسل اسم المستخدم والباقـة ووقت الطلب والبريد المستخدم إلى ', 'Please mail your username, package, order time and email used to '); ?>
                <a href="mailto:<?php echo (defined('PAYPAL_EMAIL') && PAYPAL_EMAIL !== '@') ? PAYPAL_EMAIL : ADMIN_EMAIL; ?>"><?php echo accountText('عنوان الفوترة', 'our billing address'); ?></a>.</p>

            <?php } else { 
                // --- ISTORIC NORMAL CU FILTRU ---
                $result = mysqli_query($database->dblink, "SELECT gold FROM ".TB_PREFIX."users WHERE id = $uid LIMIT 1");
                $golds = mysqli_fetch_assoc($result);

                $stats = mysqli_fetch_assoc(mysqli_query($database->dblink, "
                    SELECT 
                        SUM(CASE WHEN gold > 0 THEN gold ELSE 0 END) as received,
                        SUM(CASE WHEN gold < 0 THEN -gold ELSE 0 END) as spent 
                    FROM ".TB_PREFIX."gold_fin_log WHERE uid = $uid
                "));
                $received = (int)($stats['received'] ?? 0);
                $spent    = (int)($stats['spent'] ?? 0);

                // FILTRU + PAGINARE
                $perPage = 25;
                $page = isset($_GET['p']) ? max(1,(int)$_GET['p']) : 1;
                $offset = ($page-1)*$perPage;
                $f = $_GET['f'] ?? 'all';

                $where = "l.uid = $uid";
                if($f==='in') $where .= " AND l.gold > 0";
                elseif($f==='out') $where .= " AND l.gold < 0";
                elseif($f==='gift') $where .= " AND (l.action LIKE '%Gift%' OR l.details LIKE '%gift%' OR l.details LIKE '%Admin%')";

                $countRes = mysqli_query($database->dblink, "SELECT COUNT(*) as c FROM ".TB_PREFIX."gold_fin_log l WHERE $where");
                $totalRows = (int)mysqli_fetch_assoc($countRes)['c'];
                $totalPages = max(1, ceil($totalRows / $perPage));
            ?>
                <p><?php echo accountText('هنا يمكنك عرض سجل عمليات الذهب السابقة.', 'Here you can see your previous gold transactions.'); ?></p>
                <p><?php echo accountText('الرصيد الحالي:', 'Current balance:'); ?> <img src="img/x.gif" class="gold" alt="<?php echo GOLD; ?>" /> <b><?php echo (int)$golds['gold']; ?></b>
                &nbsp; | &nbsp; <?php echo accountText('إجمالي المستلم:', 'Total received:'); ?> <b style="color:#71D000;">+<?php echo $received; ?></b>
                &nbsp; | &nbsp; <?php echo accountText('إجمالي المصروف:', 'Total spent:'); ?> <b style="color:#FF6F0F;">-<?php echo $spent; ?></b></p>

                <!-- شريط التصفية -->
                <div class="gk-plus-history-bar" style="background:#f0f0f0; border:1px solid #d0d0d0; padding:6px 8px; margin:10px 0; border-radius:3px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap;">
                    <div style="display:flex; gap:10px; align-items:center;">
                        <a href="a2b2.php?f=all" style="text-decoration:none; padding:3px 8px; <?php if($f=='all') echo 'background:#fff; border:1px solid #aaa; border-radius:3px; font-weight:bold;'; ?>">
                            <img src="img/x.gif" class="gold" style="vertical-align:-2px;"> <?php echo accountText('الكل', 'All'); ?>
                        </a>
                        <a href="a2b2.php?f=in" style="text-decoration:none; padding:3px 8px; color:#228B22; <?php if($f=='in') echo 'background:#fff; border:1px solid #aaa; border-radius:3px; font-weight:bold;'; ?>">
                            <b style="font-size:15px;">+</b> <?php echo accountText('وارد', 'Incoming'); ?>
                        </a>
                        <a href="a2b2.php?f=out" style="text-decoration:none; padding:3px 8px; color:#D00000; <?php if($f=='out') echo 'background:#fff; border:1px solid #aaa; border-radius:3px; font-weight:bold;'; ?>">
                            <b style="font-size:15px;">−</b> <?php echo accountText('صادر', 'Outgoing'); ?>
                        </a>
                        <a href="a2b2.php?f=gift" style="text-decoration:none; padding:3px 8px; color:#0066cc; <?php if($f=='gift') echo 'background:#fff; border:1px solid #aaa; border-radius:3px; font-weight:bold;'; ?>">
                            <?php echo accountText('🎁 هدايا', '🎁 Gifts'); ?>
                        </a>
                    </div>
                    <div>
                        <form method="get" style="margin:0;">
                            <input type="hidden" name="f" value="<?php echo htmlspecialchars($f); ?>">
                            <select name="p" onchange="this.form.submit()" style="font-size:11px; padding:2px;">
                                <?php for($i=1;$i<=$totalPages;$i++){ echo '<option value="'.$i.'"'.($i==$page?' selected':'').'>'.accountText('صفحة ', 'Page ').$i.' / '.$totalPages.'</option>'; } ?>
                            </select>
                        </form>
                    </div>
                </div>

                <table class="plusFunctions" cellpadding="1" cellspacing="1">
                <thead>
                <tr><th colspan="6" height="20"><?php echo accountText('سجل الذهب', 'Gold history'); ?> (<?php echo $totalRows; ?>)</th></tr>
                <tr>
                    <td align="center"><?php echo accountText('التاريخ والوقت', 'Date & Time'); ?></td>
                    <td align="center"><?php echo VILLAGE; ?></td>
                    <td align="center"><?php echo ACTION; ?></td>
                    <td align="center"><?php echo accountText('التفاصيل', 'Details'); ?></td>
                    <td align="center"><img src="img/x.gif" class="gold" alt="<?php echo GOLD; ?>" /></td>
                    <td align="center"><?php echo accountText('الرصيد', 'Balance'); ?></td>
                </tr>
                </thead>
                <tbody>
                <?php
                $q = mysqli_query($database->dblink,
                    "SELECT l.*, v.name as vname
                     FROM ".TB_PREFIX."gold_fin_log l
                     LEFT JOIN ".TB_PREFIX."vdata v ON v.wref = l.wid
                     WHERE $where
                     ORDER BY l.time DESC
                     LIMIT $offset, $perPage");

                $sumBefore = 0;
                if($offset > 0){
                    $sumRes = mysqli_query($database->dblink,
                        "SELECT COALESCE(SUM(gold),0) as s FROM (
                            SELECT gold FROM ".TB_PREFIX."gold_fin_log l WHERE $where ORDER BY l.time DESC LIMIT $offset
                        ) t");
                    $sumBefore = (int)mysqli_fetch_assoc($sumRes)['s'];
                }
                $balance = (int)$golds['gold'] - $sumBefore;

                if(mysqli_num_rows($q) > 0){
                    while($r = mysqli_fetch_assoc($q)){
                        $date = date('d.m.Y H:i:s', $r['time']);
                        $villageName = !empty($r['vname'])
                            ? htmlspecialchars($r['vname'], ENT_QUOTES, 'UTF-8')
                            : accountText('—', '—');
                        $rawAction = accountNormalizeGiftAction((string) $r['action'], (string) ($r['details'] ?? ''));
                        $rawDetails = (string) ($r['details'] ?? '');
                        $action = htmlspecialchars(accountTranslateAction($rawAction), ENT_QUOTES, 'UTF-8');
                        $details = htmlspecialchars(accountTranslateDetails($rawDetails), ENT_QUOTES, 'UTF-8');
                        $gold = (int)$r['gold'];

                        $color = $gold < 0 ? '#FF6F0F' : '#71D000';
                        $sign = $gold > 0 ? '+' : '';

                        echo '<tr>';
                        echo '<td class="desc"><div style="text-align:center">'.$date.'</div></td>';
                        echo '<td class="desc"><div style="text-align:center">'.$villageName.'</div></td>';
                        echo '<td class="desc"><div style="text-align:right"><b>'.$action.'</b></div></td>';
                        echo '<td class="desc"><div style="text-align:right"><span style="color:#666;font-size:11px">'.$details.'</span></div></td>';
                        echo '<td class="desc"><div style="text-align:center"><font color="'.$color.'"><b>'.$sign.$gold.'</b></font></div></td>';
                        echo '<td class="act"><div style="text-align:center">'.$balance.'</div></td>';
                        echo '</tr>';
                        $balance -= $gold;
                    }
                } else {
                    echo '<tr><td colspan="6" class="desc"><div style="text-align:center;padding:8px;">' . accountText('لا توجد عمليات حتى الآن.', 'No transactions yet.') . '</div></td></tr>';
                }
                ?>
                </tbody>
                </table>

                <p><?php echo accountText('يرجى مراجعة المعلومات وإبلاغنا في حال وجود أي خطأ.', 'Please verify the information. It will let us know if the data is incorrect.'); ?></p>
                <p><?php echo accountText('أرسل اسم المستخدم والباقـة ووقت الطلب والبريد المستخدم إلى ', 'Please mail your username, package, order time and email used to '); ?>
                <a href="mailto:<?php echo (defined('PAYPAL_EMAIL') && PAYPAL_EMAIL !== '@') ? PAYPAL_EMAIL : ADMIN_EMAIL; ?>"><?php echo accountText('عنوان الفوترة', 'our billing address'); ?></a>.</p>
            <?php } ?>
        </div>
<?php
include __DIR__ . '/Templates/Plus/pmenu_close.tpl';
tz_greek_shell_close(array('buildPopup' => false, 'timer' => $start_timer));
