<?php
#################################################################################
##  Filename       : portalWorlds.tpl                                          ##
##  Type           : Admin — enable/edit portal world picker entries           ##
#################################################################################

if (!isset($_SESSION['access']) || $_SESSION['access'] < ADMIN) {
    echo '<p style="color:#f87171;padding:16px;">' . ADMIN_ACCESS_DENIED . '</p>';
    return;
}

if (!class_exists('PortalWorlds', false)) {
    require_once dirname(__DIR__, 2) . '/GameEngine/PortalWorlds.php';
}

$worlds = PortalWorlds::all();
$msg = isset($_GET['msg']) ? (string) $_GET['msg'] : '';
$badgeOptions = [
    '' => defined('PORTAL_BADGE_NONE') ? PORTAL_BADGE_NONE : '—',
    'limited_gold' => defined('PORTAL_BADGE_LIMITED_GOLD') ? PORTAL_BADGE_LIMITED_GOLD : 'صرف ذهب محدود',
    'permanent' => defined('PORTAL_BADGE_PERMANENT') ? PORTAL_BADGE_PERMANENT : 'سيرفر الدوام',
    'newest' => defined('PORTAL_BADGE_NEWEST') ? PORTAL_BADGE_NEWEST : '[الأجدد]',
];
?>
<style>
.pw-wrap{color:#e2e8f0;font-family:Verdana,Arial,sans-serif;font-size:12px;padding:6px 4px 26px;}
.pw-wrap h2{font-size:18px;margin:0 0 4px;color:#fff;}
.pw-wrap h2 span{color:#38bdf8;}
.pw-intro{color:#94a3b8;font-size:11px;margin:0 0 14px;max-width:920px;line-height:1.55;}
.pw-msg{background:#14532d;border:1px solid #166534;color:#bbf7d0;border-radius:6px;padding:8px 12px;font-size:11px;margin-bottom:14px;}
.pw-card{background:#111827;border:1px solid #1f2937;border-radius:8px;padding:14px 16px;margin-bottom:18px;}
.pw-table{width:100%;border-collapse:collapse;}
.pw-table th{background:#0b1220;text-align:left;padding:8px 6px;font-size:9px;text-transform:uppercase;letter-spacing:.35px;color:#94a3b8;border-bottom:1px solid #1f2937;}
.pw-table td{padding:8px 6px;border-bottom:1px solid #14203a;vertical-align:top;}
.pw-table input[type=text],.pw-table input[type=number],.pw-table input[type=datetime-local],.pw-table select{
  background:#0b1220;border:1px solid #334155;border-radius:5px;color:#e2e8f0;padding:5px 7px;width:100%;box-sizing:border-box;font-size:11px;
}
.pw-table input.num{width:56px;}
.pw-table .chk{display:flex;align-items:center;gap:6px;white-space:nowrap;}
.pw-actions{margin-top:12px;display:flex;gap:10px;flex-wrap:wrap;}
.pw-actions button{background:#38bdf8;color:#0b1220;font-weight:bold;border:0;border-radius:6px;padding:9px 18px;cursor:pointer;}
.pw-hint{color:#64748b;font-size:10px;margin-top:10px;line-height:1.45;}
.badge{display:inline-block;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:bold;}
.st-on{background:#14532d;color:#bbf7d0;}
.st-off{background:#334155;color:#cbd5e1;}
.pw-id{font-family:monospace;color:#7dd3fc;}
</style>

<div class="pw-wrap">
  <h2><?php echo defined('PORTAL_ADM_TITLE') ? PORTAL_ADM_TITLE : 'Portal'; ?><span><?php
    echo defined('PORTAL_ADM_WORLDS') ? PORTAL_ADM_WORLDS : 'Worlds';
  ?></span></h2>
  <p class="pw-intro"><?php
    echo defined('PORTAL_ADM_INTRO')
      ? PORTAL_ADM_INTRO
      : 'هذه العوالم تظهر في قائمة اختيار العالم عند التسجيل/الدخول في الصفحة الرئيسية. كل عالم مستقل (عادة تثبيت ولعبة وقاعدة بيانات خاصة). فعّل أو عطّل أي عالم من هنا. العالم المحلي فقط يستخدم إحصائيات هذا السيرفر.';
  ?></p>

  <?php if ($msg !== '') { ?>
    <div class="pw-msg"><?php echo e($msg); ?></div>
  <?php } ?>

  <form method="post" action="../GameEngine/Admin/Mods/portalWorlds.php" class="pw-card">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="do" value="save">
    <div style="overflow-x:auto">
      <table class="pw-table">
        <thead>
          <tr>
            <th><?php echo defined('PORTAL_ADM_COL_ON') ? PORTAL_ADM_COL_ON : 'تفعيل'; ?></th>
            <th>ID</th>
            <th>#</th>
            <th><?php echo defined('PORTAL_ADM_COL_NAME') ? PORTAL_ADM_COL_NAME : 'الاسم'; ?></th>
            <th><?php echo defined('PORTAL_ADM_COL_LOCAL') ? PORTAL_ADM_COL_LOCAL : 'محلي'; ?></th>
            <th><?php echo defined('PORTAL_ADM_COL_BADGE') ? PORTAL_ADM_COL_BADGE : 'شارة'; ?></th>
            <th><?php echo defined('PORTAL_ADM_COL_START') ? PORTAL_ADM_COL_START : 'وقت البدء'; ?></th>
            <th><?php echo defined('PORTAL_ADM_COL_LOGIN') ? PORTAL_ADM_COL_LOGIN : 'رابط الدخول'; ?></th>
            <th><?php echo defined('PORTAL_ADM_COL_REG') ? PORTAL_ADM_COL_REG : 'رابط التسجيل'; ?></th>
            <th><?php echo defined('PORTAL_ADM_COL_PLAYERS') ? PORTAL_ADM_COL_PLAYERS : 'لاعبون'; ?></th>
            <th><?php echo defined('PORTAL_ADM_COL_SORT') ? PORTAL_ADM_COL_SORT : 'ترتيب'; ?></th>
            <th><?php echo defined('PORTAL_ADM_COL_DB') ? PORTAL_ADM_COL_DB : 'قاعدة البيانات'; ?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($worlds as $w) {
            $id = (string) $w['id'];
            $startVal = date('Y-m-d\TH:i', (int) $w['start_time']);
            ?>
          <tr>
            <td>
              <label class="chk">
                <input type="checkbox" name="worlds[<?php echo e($id); ?>][enabled]" value="1"
                  <?php echo !empty($w['enabled']) ? 'checked' : ''; ?>>
                <span class="badge <?php echo !empty($w['enabled']) ? 'st-on' : 'st-off'; ?>">
                  <?php echo !empty($w['enabled'])
                    ? (defined('ADMIN_ENABLED') ? ADMIN_ENABLED : 'ON')
                    : (defined('ADMIN_DISABLED') ? ADMIN_DISABLED : 'OFF'); ?>
                </span>
              </label>
            </td>
            <td class="pw-id"><?php echo e($id); ?></td>
            <td>
              <input class="num" type="number" min="1" max="99"
                     name="worlds[<?php echo e($id); ?>][number]"
                     value="<?php echo (int) $w['number']; ?>">
            </td>
            <td>
              <input type="text" maxlength="60"
                     name="worlds[<?php echo e($id); ?>][name]"
                     value="<?php echo e((string) $w['name']); ?>">
            </td>
            <td>
              <label class="chk">
                <input type="checkbox" name="worlds[<?php echo e($id); ?>][local]" value="1"
                  <?php echo !empty($w['local']) ? 'checked' : ''; ?>>
                <?php echo defined('PORTAL_ADM_THIS_INSTALL') ? PORTAL_ADM_THIS_INSTALL : 'هذا التثبيت'; ?>
              </label>
            </td>
            <td>
              <select name="worlds[<?php echo e($id); ?>][badge]">
                <?php foreach ($badgeOptions as $bk => $bl) { ?>
                  <option value="<?php echo e($bk); ?>"<?php echo ((string) $w['badge'] === (string) $bk) ? ' selected' : ''; ?>>
                    <?php echo e($bl); ?>
                  </option>
                <?php } ?>
              </select>
              <input type="hidden" name="worlds[<?php echo e($id); ?>][image]" value="<?php echo e((string) $w['image']); ?>">
              <input type="hidden" name="worlds[<?php echo e($id); ?>][image_grey]" value="<?php echo e((string) $w['image_grey']); ?>">
            </td>
            <td>
              <input type="datetime-local"
                     name="worlds[<?php echo e($id); ?>][start_time]"
                     value="<?php echo e($startVal); ?>">
            </td>
            <td>
              <input type="text" maxlength="255"
                     name="worlds[<?php echo e($id); ?>][login_url]"
                     value="<?php echo e((string) $w['login_url']); ?>"
                     placeholder="portal_enter">
            </td>
            <td>
              <input type="text" maxlength="255"
                     name="worlds[<?php echo e($id); ?>][register_url]"
                     value="<?php echo e((string) $w['register_url']); ?>"
                     placeholder="portal_enter">
            </td>
            <td>
              <input class="num" type="number" min="0" max="999999"
                     name="worlds[<?php echo e($id); ?>][players]"
                     value="<?php echo (int) $w['players']; ?>">
              <input type="hidden" name="worlds[<?php echo e($id); ?>][online]" value="<?php echo (int) $w['online']; ?>">
            </td>
            <td>
              <input class="num" type="number" min="0" max="9999"
                     name="worlds[<?php echo e($id); ?>][sort]"
                     value="<?php echo (int) $w['sort']; ?>">
            </td>
            <td style="white-space:nowrap">
              <code class="pw-id"><?php echo e((string) ($w['tb_prefix'] ?? '')); ?></code><br>
              <?php if (!empty($w['local']) || !empty($w['provisioned'])) { ?>
                <span class="badge st-on"><?php echo defined('PORTAL_ADM_READY') ? PORTAL_ADM_READY : 'جاهز'; ?></span>
              <?php } else { ?>
                <span class="badge st-off"><?php echo defined('PORTAL_ADM_NEED_PROVISION') ? PORTAL_ADM_NEED_PROVISION : 'يحتاج تهيئة'; ?></span>
              <?php } ?>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
    <div class="pw-actions">
      <button type="submit"><?php echo defined('ADM_SAVE') ? ADM_SAVE : (defined('SAVE') ? SAVE : 'حفظ'); ?></button>
    </div>
    <p class="pw-hint"><?php
      echo defined('PORTAL_ADM_HINT')
        ? PORTAL_ADM_HINT
        : 'عيّن عالماً واحداً فقط كـ «محلي». باقي العوالم تُنشأ بجداول منفصلة عند أول دخول أو من أزرار التهيئة.';
    ?></p>
  </form>

  <div class="pw-card">
    <h3 style="margin:0 0 10px;color:#fff;font-size:13px"><?php
      echo defined('PORTAL_ADM_PROVISION_TITLE') ? PORTAL_ADM_PROVISION_TITLE : 'تهيئة قواعد بيانات العوالم';
    ?></h3>
    <?php foreach ($worlds as $w) {
        if (!empty($w['local'])) {
            continue;
        }
        ?>
      <form method="post" action="../GameEngine/Admin/Mods/portalWorlds.php" style="display:inline-block;margin:4px 6px 4px 0">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="do" value="provision">
        <input type="hidden" name="id" value="<?php echo e((string) $w['id']); ?>">
        <button type="submit" style="background:#334155;color:#fff;border:0;border-radius:6px;padding:7px 12px;cursor:pointer">
          <?php echo e((string) $w['name']); ?> —
          <?php echo defined('PORTAL_ADM_PROVISION') ? PORTAL_ADM_PROVISION : 'تهيئة الآن'; ?>
        </button>
      </form>
    <?php } ?>
  </div>
</div>
