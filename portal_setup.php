<?php
/**
 * One-time setup page for a portal world (creates its DB tables + map).
 * Shown with a visible message so the browser does not look "broken" during setup.
 */

if (!file_exists(__DIR__ . '/var/installed') && @opendir(__DIR__ . '/install')) {
    header('Location: install/');
    exit;
}

require_once __DIR__ . '/GameEngine/PortalWorlds.php';

$worldId = isset($_GET['w'])
    ? preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $_GET['w'])
    : '';
$do = isset($_GET['do']) ? (string) $_GET['do'] : 'login';
if ($do !== 'register') {
    $do = 'login';
}
$run = isset($_GET['run']) && (string) $_GET['run'] === '1';

$world = $worldId !== '' ? PortalWorlds::findById($worldId) : null;
if (!$world || empty($world['enabled']) || !empty($world['local'])) {
    header('Location: index.php');
    exit;
}

$name = htmlspecialchars((string) $world['name'], ENT_QUOTES, 'UTF-8');
$error = '';
$done = false;

if ($run) {
    // Clear portal cookie so config uses the MAIN prefix while we create tables.
    if (isset($_COOKIE[PortalWorlds::COOKIE])) {
        unset($_COOKIE[PortalWorlds::COOKIE]);
    }

    require_once __DIR__ . '/GameEngine/config.php';
    require_once __DIR__ . '/GameEngine/Database.php';

    $link = $GLOBALS['link'] ?? null;
    $result = PortalWorlds::provision($world, $link);
    if (!empty($result['ok'])) {
        PortalWorlds::setCookie((string) $world['id']);
        $done = true;
        $dest = $do === 'register' ? 'anmelden.php' : 'login.php';
        header('Location: ' . $dest);
        exit;
    }
    $error = htmlspecialchars((string) ($result['msg'] ?? 'unknown'), ENT_QUOTES, 'UTF-8');
}

$runUrl = 'portal_setup.php?w=' . rawurlencode($worldId)
    . '&do=' . rawurlencode($do) . '&run=1';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $name; ?> — تجهيز العالم</title>
    <?php if (!$run && $error === '') { ?>
    <meta http-equiv="refresh" content="1;url=<?php echo htmlspecialchars($runUrl, ENT_QUOTES, 'UTF-8'); ?>" />
    <?php } ?>
    <style>
        body{margin:0;font-family:Tahoma,Arial,sans-serif;background:#1a1f18;color:#f2f2f2;
             display:flex;min-height:100vh;align-items:center;justify-content:center}
        .box{background:#24301f;border:1px solid #3d5233;border-radius:10px;padding:28px 32px;
             max-width:420px;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,.35)}
        h1{margin:0 0 10px;font-size:18px;color:#b7e07a}
        p{margin:0 0 14px;font-size:13px;line-height:1.6;color:#d7e0cf}
        .err{color:#ffb4b4;background:#4a1d1d;border:1px solid #7f2d2d;border-radius:6px;
             padding:10px;margin-top:12px;font-size:12px}
        a{color:#9fdf5f}
        .spin{width:28px;height:28px;margin:12px auto;border:3px solid #3d5233;
              border-top-color:#9fdf5f;border-radius:50%;animation:s .8s linear infinite}
        @keyframes s{to{transform:rotate(360deg)}}
    </style>
</head>
<body>
    <div class="box">
        <h1><?php echo $name; ?></h1>
        <?php if ($error !== '') { ?>
            <p>تعذر تجهيز العالم.</p>
            <div class="err"><?php echo $error; ?></div>
            <p style="margin-top:14px"><a href="index.php">العودة للصفحة الرئيسية</a></p>
        <?php } else { ?>
            <p>جاري تجهيز العالم لأول مرة… الرجاء الانتظار لحظات.</p>
            <div class="spin" aria-hidden="true"></div>
            <p style="font-size:11px;color:#9aa890">لا تغلق هذه الصفحة.</p>
            <noscript>
                <p><a href="<?php echo htmlspecialchars($runUrl, ENT_QUOTES, 'UTF-8'); ?>">اضغط هنا للمتابعة</a></p>
            </noscript>
        <?php } ?>
    </div>
</body>
</html>
