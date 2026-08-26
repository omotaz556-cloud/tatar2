<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       portalWorlds.php                                            ##
##  Type           BACKEND (Portal multi-world picker)                         ##
#################################################################################

require_once(__DIR__ . '/../csrf.php');
if (!isset($_SESSION)) {
    session_start();
}
if (($_SESSION['access'] ?? 0) < 9) {
    admin_deny('You must be signed in as an administrator to do this.');
}

csrf_verify();

include_once('../../config.php');

$autoprefix = '';
for ($i = 0; $i < 5; $i++) {
    $autoprefix = str_repeat('../', $i);
    if (file_exists($autoprefix . 'autoloader.php')) {
        break;
    }
}
include_once($autoprefix . 'GameEngine/Database.php');
include_once($autoprefix . 'GameEngine/PortalWorlds.php');

$admid = (int) ($_SESSION['id'] ?? 0);
$check = mysqli_query(
    $GLOBALS['link'],
    'SELECT access FROM ' . TB_PREFIX . 'users WHERE id = ' . $admid
);
$acc = $check ? mysqli_fetch_assoc($check) : null;
if (!$acc || (int) $acc['access'] < 9) {
    admin_deny('Your session may have expired — please sign in again.');
}

$do = (string) ($_POST['do'] ?? '');
$msg = '';

if ($do === 'toggle') {
    $id = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($_POST['id'] ?? ''));
    $enabled = !empty($_POST['enabled']);
    if ($id !== '' && PortalWorlds::setEnabled($id, $enabled)) {
        $msg = $enabled
            ? (defined('PORTAL_ADM_ENABLED_OK') ? PORTAL_ADM_ENABLED_OK : 'World enabled.')
            : (defined('PORTAL_ADM_DISABLED_OK') ? PORTAL_ADM_DISABLED_OK : 'World disabled.');
        $logMsg = mysqli_real_escape_string(
            $GLOBALS['link'],
            'Portal world ' . $id . ' ' . ($enabled ? 'enabled' : 'disabled')
        );
        mysqli_query(
            $GLOBALS['link'],
            'INSERT INTO ' . TB_PREFIX . 'admin_log VALUES (0, ' . $admid . ", '" . $logMsg . "', " . time() . ')'
        );
    } else {
        $msg = defined('PORTAL_ADM_SAVE_ERR') ? PORTAL_ADM_SAVE_ERR : 'Could not update world.';
    }
} elseif ($do === 'save') {
    $posted = $_POST['worlds'] ?? [];
    if (!is_array($posted)) {
        $posted = [];
    }

    $existing = [];
    foreach (PortalWorlds::all() as $w) {
        $existing[(string) $w['id']] = $w;
    }

    $next = [];
    foreach ($posted as $id => $row) {
        if (!is_array($row)) {
            continue;
        }
        $id = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $id);
        if ($id === '' || !isset($existing[$id])) {
            continue;
        }
        $base = $existing[$id];
        $startRaw = trim((string) ($row['start_time'] ?? ''));
        $startTs = $base['start_time'];
        if ($startRaw !== '') {
            $parsed = strtotime($startRaw);
            if ($parsed !== false) {
                $startTs = $parsed;
            }
        }

        $next[] = [
            'id' => $id,
            'number' => (int) ($row['number'] ?? $base['number']),
            'name' => (string) ($row['name'] ?? $base['name']),
            'enabled' => !empty($row['enabled']),
            'local' => !empty($row['local']),
            'badge' => (string) ($row['badge'] ?? $base['badge']),
            'image' => (string) ($row['image'] ?? $base['image']),
            'image_grey' => (string) ($row['image_grey'] ?? $base['image_grey']),
            'start_time' => $startTs,
            'login_url' => (string) ($row['login_url'] ?? $base['login_url']),
            'register_url' => (string) ($row['register_url'] ?? $base['register_url']),
            'tb_prefix' => (string) ($base['tb_prefix'] ?? ''),
            'provisioned' => !empty($base['provisioned']),
            'players' => (int) ($row['players'] ?? $base['players']),
            'online' => (int) ($row['online'] ?? $base['online']),
            'sort' => (int) ($row['sort'] ?? $base['sort']),
        ];
    }

    // Keep any worlds that were not in the form (safety).
    $keptIds = array_column($next, 'id');
    foreach ($existing as $id => $w) {
        if (!in_array($id, $keptIds, true)) {
            $next[] = $w;
        }
    }

    if (PortalWorlds::save($next)) {
        $msg = defined('PORTAL_ADM_SAVED') ? PORTAL_ADM_SAVED : 'Portal worlds saved.';
        mysqli_query(
            $GLOBALS['link'],
            'INSERT INTO ' . TB_PREFIX . 'admin_log VALUES (0, ' . $admid
            . ", 'Portal worlds configuration saved', " . time() . ')'
        );
    } else {
        $msg = defined('PORTAL_ADM_SAVE_ERR')
            ? PORTAL_ADM_SAVE_ERR
            : 'Could not write var/portal_worlds.json — check permissions.';
    }
} elseif ($do === 'provision') {
    $id = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($_POST['id'] ?? ''));
    $world = $id !== '' ? PortalWorlds::findById($id) : null;
    if ($world) {
        $result = PortalWorlds::provision($world, $GLOBALS['link'] ?? null);
        $msg = !empty($result['ok'])
            ? (defined('PORTAL_ADM_PROVISION_OK') ? PORTAL_ADM_PROVISION_OK : 'World database ready.')
            : (defined('PORTAL_ADM_PROVISION_ERR') ? PORTAL_ADM_PROVISION_ERR : 'Provision failed: ')
                . ($result['msg'] ?? '');
    } else {
        $msg = defined('PORTAL_ADM_SAVE_ERR') ? PORTAL_ADM_SAVE_ERR : 'World not found.';
    }
}

header('Location: ../../../Admin/admin.php?p=portalWorlds&msg=' . rawurlencode($msg));
exit;
