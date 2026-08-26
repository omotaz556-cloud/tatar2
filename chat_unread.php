<?php

include_once __DIR__ . '/GameEngine/Village.php';

header('Content-Type: application/json; charset=UTF-8');

if (empty($session->logged_in) || empty($session->uid)) {
    echo json_encode(['count' => 0]);
    exit;
}

include_once __DIR__ . '/GameEngine/ChatRead.php';

echo json_encode([
    'count' => ChatRead::getSidebarUnreadCount((int) $session->uid),
]);
