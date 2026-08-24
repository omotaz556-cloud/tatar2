<?php
require 'GameEngine/config.php';
require 'GameEngine/CentralGold.php';

$link = mysqli_connect(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB);
$email = 'omotaz3112311@gmail.com';

$res = mysqli_query($link, "SELECT id, username FROM " . TB_PREFIX . "users WHERE email = '$email' LIMIT 1");
$row = mysqli_fetch_assoc($res);

if (!$row) {
    echo "NO USER FOUND WITH THIS EMAIL IN users TABLE\n";
} else {
    var_dump($row);
    $result = CentralGold::credit($email, $row['username'], (int) $row['id'], 50, 'admin_grant', 'test grant via docker');
    var_dump($result);
}