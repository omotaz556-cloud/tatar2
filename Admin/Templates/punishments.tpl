<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : punishments.tpl                                           ##
##  Type           : Admin Panel Frontend                                      ##
## --------------------------------------------------------------------------- ##
##  Purpose        : Apply / lift targeted restrictions (mute, marketplace,    ##
##                    army freeze) without a full account ban. See             ##
##                    GameEngine/Punishment.php for the engine.                ##
## --------------------------------------------------------------------------- ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################
if($_SESSION['access'] < MULTIHUNTER) die(ADM_ACCESS_DENIED_BANG);

if (!class_exists('Punishment')) {
    require_once __DIR__ . '/../../GameEngine/Punishment.php';
}

$error = '';
$success = '';
$adminId = (int) ($_SESSION['id'] ?? 0);
$validTypes = Punishment::validTypes();

// Localized labels for display only; Punishment::label() / TYPE_* stay
// untouched since they're also used internally by GameEngine/Punishment.php.
$punLabels = [
    Punishment::TYPE_MUTE   => ADM_PUN_LABEL_MUTE,
    Punishment::TYPE_MARKET => ADM_PUN_LABEL_MARKET,
    Punishment::TYPE_ARMY   => ADM_PUN_LABEL_ARMY,
];

// ========================= HANDLE APPLY =========================
// Note: admin.php already runs csrf_verify() on every POST before this
// template is included (see the CheckLogin() block), so no extra check
// is needed here — matches the existing convention in ban.tpl.
if (isset($_POST['action']) && $_POST['action'] === 'applyPunishment') {
    $uid    = (int) ($_POST['uid'] ?? 0);
    $type   = (string) ($_POST['type'] ?? '');
    $hours  = (int) ($_POST['duration'] ?? 0);
    $reason = trim((string) ($_POST['reason'] ?? ''));
    $blocked = [1, 2, 3, 4, 5];

    if ($uid <= 0) {
        $error = ADM_PUN_ERR_INVALID_UID;
    } elseif (in_array($uid, $blocked, true)) {
        $error = ADM_PUN_ERR_SYSTEM_ACC;
    } elseif (!in_array($type, $validTypes, true)) {
        $error = ADM_PUN_ERR_INVALID_TYPE;
    } elseif ($reason === '') {
        $error = ADM_PUN_ERR_REASON_REQUIRED;
    } else {
        $userCheck = mysqli_query($database->dblink, "SELECT id, username FROM " . TB_PREFIX . "users WHERE id=$uid LIMIT 1");
        if (!$userCheck || mysqli_num_rows($userCheck) == 0) {
            $error = ADM_PUN_ERR_NO_USER;
        } else {
            $user = mysqli_fetch_assoc($userCheck);
            if (Punishment::apply($uid, $type, $hours, $reason, $adminId)) {
                $success = sprintf(ADM_PUN_SUCCESS_APPLY, htmlspecialchars($punLabels[$type] ?? Punishment::label($type)), htmlspecialchars($user['username']));
            } else {
                $error = ADM_PUN_ERR_APPLY_FAILED;
            }
        }
    }
}

// ========================= HANDLE LIFT =========================
// GET action, same convention as ban.tpl's delBan/delIpBan links (state
// changes via a confirmed GET link; admin.php only enforces CSRF on POST).
if (isset($_GET['action']) && $_GET['action'] === 'liftPunishment') {
    $uid  = (int) ($_GET['uid'] ?? 0);
    $type = (string) ($_GET['type'] ?? '');
    if ($uid > 0 && in_array($type, $validTypes, true)) {
        Punishment::lift($uid, $type, $adminId);
        $success = ADM_PUN_LIFTED;
    } else {
        $error = ADM_PUN_ERR_INVALID_REQUEST;
    }
}

// ========================= DATA =========================
$activeByType = [];
foreach ($validTypes as $t) {
    $activeByType[$t] = Punishment::listActive($t, 100);
}
$totalActive = array_sum(array_map('count', $activeByType));
?>
<style>
.pun-wrap{max-width:900px;margin:0 auto;padding:4px 14px;font-family:Verdana,Arial,sans-serif;color:#0f172a}
.pun-head{display:flex;align-items:center;gap:10px;margin:0 0 14px;padding-bottom:10px;border-bottom:1px dashed rgba(255,255,255,.12)}
.pun-head h2{margin:0;font-size:20px;font-weight:700;color:#fff;letter-spacing:.3px}
.alert{padding:11px 14px;border-radius:8px;margin:0 0 14px;font-size:13px;border:1px solid transparent}
.alert.error{background:#fef2f2;border-color:#fecaca;color:#b91c1c}
.alert.success{background:#f0fdf4;border-color:#bbf7d0;color:#166534}
.pun-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:18px}
@media(max-width:640px){.pun-stats{grid-template-columns:1fr}}
.stat{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:12px 14px;box-shadow:0 1px 2px rgba(0,0,0,.04)}
.stat .lbl{font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.5px}
.stat .val{font-size:20px;font-weight:700;color:#111;line-height:1}
.pun-stack{display:flex;flex-direction:column;gap:16px}
.pun-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:16px;box-shadow:0 1px 2px rgba(0,0,0,.04),0 6px 16px rgba(0,0,0,.03)}
.pun-card h3{margin:0 0 12px;font-size:14px;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px}
.badge-count{background:#fee2e2;color:#b91c1c;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:700;border:1px solid #fecaca}
.pun-form{display:grid;gap:10px}
.pun-form .row{display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px}
@media(max-width:640px){.pun-form .row{grid-template-columns:1fr}}
.pun-form input,.pun-form select{width:100%;padding:11px 12px;border:1px solid #d1d5db;border-radius:9px;font-size:13px;background:#fff;color:#111;outline:none}
.pun-form input:focus,.pun-form select:focus{border-color:#c0392b;box-shadow:0 0 0 3px rgba(192,57,43,.13)}
.pun-form button{background:#c0392b;color:#fff;border:0;padding:11px 16px;border-radius:9px;font-weight:700;cursor:pointer;width:100%}
.pun-form button:hover{background:#a93226}
.pun-list{display:flex;flex-direction:column;gap:8px;max-height:340px;overflow:auto;padding-right:4px}
.pun-item{display:grid;grid-template-columns:1fr auto;gap:10px;align-items:center;padding:11px 12px;background:#fafafa;border:1px solid #eee;border-radius:10px}
.pun-item .user a{color:#111;text-decoration:none;font-weight:700;font-size:13px}
.pun-item .meta{color:#6b7280;font-size:11px;margin-top:3px}
.pun-item .reason{padding:4px 9px;background:#fee2e2;color:#991b1b;border:1px solid #fecaca;border-radius:999px;font-size:11px;font-weight:600}
.pun-item .lift{color:#c0392b;text-decoration:none;font-weight:700;padding:6px 8px;border-radius:6px}
.pun-item .lift:hover{background:#fee2e2}
.empty{padding:20px;text-align:center;color:#9ca3af;background:#fafafa;border:1px dashed #e5e7eb;border-radius:10px;font-size:13px}
</style>

<div class="pun-wrap">
  <div class="pun-head">
    <svg viewBox="0 0 24 24" width="26" height="26" fill="none"><circle cx="12" cy="12" r="10" fill="#c0392b"/><path d="M12 7v6M12 16h.01" stroke="#fff" stroke-width="2" stroke-linecap="round"/></svg>
    <h2><?php echo ADM_PUN_TITLE; ?></h2>
  </div>

  <?php if ($error) { ?><div class="alert error"><?php echo htmlspecialchars($error); ?></div><?php } ?>
  <?php if ($success) { ?><div class="alert success"><?php echo $success; ?></div><?php } ?>

  <div class="pun-stats">
    <div class="stat"><div class="lbl"><?php echo ADM_PUN_MUTED_NOW; ?></div><div class="val"><?php echo count($activeByType[Punishment::TYPE_MUTE]); ?></div></div>
    <div class="stat"><div class="lbl"><?php echo ADM_PUN_MARKET_NOW; ?></div><div class="val"><?php echo count($activeByType[Punishment::TYPE_MARKET]); ?></div></div>
    <div class="stat"><div class="lbl"><?php echo ADM_PUN_ARMY_NOW; ?></div><div class="val"><?php echo count($activeByType[Punishment::TYPE_ARMY]); ?></div></div>
  </div>

  <div class="pun-stack">
    <!-- Apply new restriction -->
    <div class="pun-card">
      <h3><?php echo ADM_PUN_APPLY_TITLE; ?></h3>
      <form method="post" class="pun-form">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="action" value="applyPunishment">
        <div class="row">
          <input type="number" name="uid" placeholder="<?php echo ADM_PUN_USER_ID; ?>" required>
          <select name="type" required>
            <option value="mute"><?php echo ADM_PUN_TYPE_MUTE; ?></option>
            <option value="market"><?php echo ADM_PUN_TYPE_MARKET; ?></option>
            <option value="army"><?php echo ADM_PUN_TYPE_ARMY; ?></option>
          </select>
          <select name="duration">
            <?php foreach ([1, 2, 6, 12, 24] as $h) echo "<option value='$h'>$h ".ADM_HOUR_UNIT."</option>"; ?>
            <?php foreach ([2, 5, 10, 30] as $d) echo "<option value='" . ($d * 24) . "'>$d ".ADM_DAY_UNIT."</option>"; ?>
            <option value="0"><?php echo ADM_PUN_PERMANENT; ?></option>
          </select>
        </div>
        <input type="text" name="reason" placeholder="<?php echo ADM_PUN_REASON_PH; ?>" required>
        <button type="submit"><?php echo ADM_PUN_APPLY_BTN; ?></button>
      </form>
    </div>

    <?php foreach ($validTypes as $t) { $list = $activeByType[$t]; $tLabel = $punLabels[$t] ?? Punishment::label($t); ?>
    <!-- Active list per type -->
    <div class="pun-card">
      <h3><?php echo htmlspecialchars(ucfirst($tLabel)); ?> — <?php echo ADM_PUN_ACTIVE; ?><span class="badge-count"><?php echo count($list); ?></span></h3>
      <div class="pun-list">
        <?php if ($list) { foreach ($list as $p) { $end = $p['end'] ? date("d.m H:i", $p['end']) : '∞'; ?>
          <div class="pun-item">
            <div>
              <div class="user"><a href="?p=player&uid=<?php echo (int) $p['uid']; ?>"><?php echo htmlspecialchars($p['username'] ?: ('uid ' . $p['uid'])); ?></a></div>
              <div class="meta"><?php echo date("d.m H:i", $p['start']); ?> → <?php echo $end; ?></div>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
              <span class="reason"><?php echo htmlspecialchars($p['reason']); ?></span>
              <a class="lift" href="?p=punishments&action=liftPunishment&uid=<?php echo (int) $p['uid']; ?>&type=<?php echo $t; ?>" onclick="return confirm('<?php echo ADM_PUN_CONFIRM_LIFT; ?>')">✕</a>
            </div>
          </div>
        <?php } } else { echo '<div class="empty">' . sprintf(ADM_PUN_NO_ACTIVE, htmlspecialchars($tLabel)) . '</div>'; } ?>
      </div>
    </div>
    <?php } ?>
  </div>
</div>
