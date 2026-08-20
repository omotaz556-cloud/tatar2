<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : xtatar_gold_webhook.php                                   ##
##  Type           : Public webhook endpoint (X-Tatar.com activity -> points)  ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow                                                    ##
##  Project        : Novaterra                                                 ##
##  License        : Novaterra Project                                        ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.             ##
## --------------------------------------------------------------------------- ##
##  X-Tatar.com posts activity events here so an off-game action (visiting,   ##
##  engaging, whatever the site defines) can turn into in-game free gold via  ##
##  GameEngine/XTatarGold.php. This endpoint stays deliberately dumb: it only ##
##  verifies the shared secret, resolves username -> uid, and hands the point ##
##  count to XTatarGold::awardPoints(). All the actual rules (daily cap,      ##
##  points->gold rate, on/off switch) live in XTatarGold / the admin panel,   ##
##  so this file never needs to change when those are tuned.                  ##
## --------------------------------------------------------------------------- ##
##  Request format (POST, JSON body):                                        ##
##    { "secret": "...", "username": "...", "points": 10, "event": "..." }   ##
##  `secret` must match xtatar_gold_settings.webhook_secret (set from the     ##
##  admin panel: ?p=xtatarGold). The endpoint returns 403 for every request   ##
##  until that secret is configured, so it is safe to deploy before the      ##
##  website side is ready.                                                    ##
#################################################################################

header('Content-Type: application/json');

$autoloader_found = false;
for ($i = 0; $i < 5; $i++) {
    $autoprefix = str_repeat('../', $i);
    if (file_exists($autoprefix . 'autoloader.php')) {
        $autoloader_found = true;
        include_once $autoprefix . 'autoloader.php';
        break;
    }
}
if (!$autoloader_found) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'server_misconfigured']);
    exit;
}
include_once($autoprefix . 'GameEngine/config.php');
include_once($autoprefix . 'GameEngine/Database.php');

function xtatar_webhook_fail($code, $error)
{
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $error]);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    xtatar_webhook_fail(405, 'method_not_allowed');
}

$raw = file_get_contents('php://input');
$body = json_decode((string) $raw, true);
if (!is_array($body)) {
    xtatar_webhook_fail(400, 'invalid_json_body');
}

$settings = XTatarGold::settings();
$configuredSecret = (string) ($settings['webhook_secret'] ?? '');
$givenSecret = (string) ($body['secret'] ?? '');

// Constant-time comparison, and an empty configured secret always rejects —
// this is the "feature is safe to enable before the website is wired up"
// guarantee described in XTatarGold.php's file header.
if ($configuredSecret === '' || !hash_equals($configuredSecret, $givenSecret)) {
    xtatar_webhook_fail(403, 'invalid_secret');
}

if (!XTatarGold::isEnabled()) {
    xtatar_webhook_fail(403, 'feature_disabled');
}

$username = trim((string) ($body['username'] ?? ''));
$points = (int) ($body['points'] ?? 0);
$event = trim((string) ($body['event'] ?? 'activity'));

if ($username === '' || $points <= 0) {
    xtatar_webhook_fail(400, 'username_and_positive_points_required');
}

global $database;
$uid = (int) $database->getUserField($username, 'id', 1);
if ($uid <= 3) {
    xtatar_webhook_fail(404, 'user_not_found');
}

$event = substr(preg_replace('/[^a-zA-Z0-9_.:-]/', '', $event), 0, 40);
list($ok, $pointsAwarded, $goldCredited) = XTatarGold::awardPoints(
    $uid, $points, 'xtatar_web:' . $event, 'X-Tatar.com webhook'
);

echo json_encode([
    'ok' => $ok,
    'points_awarded' => $pointsAwarded,
    'gold_credited' => $goldCredited,
]);
