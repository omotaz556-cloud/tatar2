<?php
/**
 * Enter a portal world. Unprovisioned worlds go through portal_setup.php first.
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

$world = $worldId !== '' ? PortalWorlds::findById($worldId) : null;
if (!$world || empty($world['enabled'])) {
    header('Location: index.php');
    exit;
}

$now = time();
$started = ((int) $world['start_time']) <= $now;
if (!$started && $do === 'register') {
    header('Location: index.php?signup');
    exit;
}

if (PortalWorlds::isExternal($world)) {
    $target = $do === 'register'
        ? (string) ($world['register_url'] ?? '')
        : (string) ($world['login_url'] ?? '');
    if ($target === '') {
        header('Location: index.php');
        exit;
    }
    header('Location: ' . $target);
    exit;
}

// Local world: cookie + go.
if (!empty($world['local'])) {
    PortalWorlds::setCookie((string) $world['id']);
    header('Location: ' . ($do === 'register' ? 'anmelden.php' : 'login.php'));
    exit;
}

// Load main DB credentials (ignore any previous portal cookie for this check).
if (isset($_COOKIE[PortalWorlds::COOKIE])) {
    unset($_COOKIE[PortalWorlds::COOKIE]);
}
require_once __DIR__ . '/GameEngine/config.php';

$prefix = (string) ($world['tb_prefix'] ?? '');
$ready = $prefix !== '' && PortalWorlds::prefixHasUsersTable($prefix);

if (!$ready) {
    header(
        'Location: portal_setup.php?w=' . rawurlencode((string) $world['id'])
        . '&do=' . rawurlencode($do)
    );
    exit;
}

PortalWorlds::setCookie((string) $world['id']);
if (empty($world['provisioned'])) {
    PortalWorlds::markProvisioned((string) $world['id'], true);
}

header('Location: ' . ($do === 'register' ? 'anmelden.php' : 'login.php'));
exit;
