<?php

header('Content-Type: application/json; charset=UTF-8');

if (empty($session->logged_in) || empty($village)) {
    echo json_encode(['ok' => false]);
    return;
}

$resFloor = static function ($v) {
    $v = (float) $v;
    return (is_finite($v) && $v > 0) ? (int) floor($v) : 0;
};

echo json_encode([
    'ok'       => true,
    'wood'     => $resFloor($village->awood),
    'clay'     => $resFloor($village->aclay),
    'iron'     => $resFloor($village->airon),
    'crop'     => $resFloor($village->acrop),
    'maxStore' => (int) $village->maxstore,
    'maxCrop'  => (int) $village->maxcrop,
    'prodWood' => (int) round($village->getProd('wood')),
    'prodClay' => (int) round($village->getProd('clay')),
    'prodIron' => (int) round($village->getProd('iron')),
    'prodCrop' => (int) round($village->getProd('crop')),
]);
