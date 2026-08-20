<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       gold_1.php                                                  ##
##  Type           BACKEND                                                     ##
##  Developed by:  aggenkeech                                                  ##
##  Refactored by: Shadow                                                      ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2025; base engine (c) TravianZ authors (GPLv3). ##
##                                                                             ##
#################################################################################

// #299: load CSRF helpers + admin_deny() before the access check below.
require_once(__DIR__ . '/../csrf.php');
if (!isset($_SESSION)) session_start();
if($_SESSION['access'] < 9) admin_deny('You must be signed in as an administrator to view this page. Your session may have expired — please return to the admin panel and sign in again.');

// Issue #139: this Mod is POSTed to directly, so it must verify the CSRF token
// itself (it does not go through admin.php's central csrf_verify()).
require_once(__DIR__ . '/../csrf.php');
csrf_verify();

include_once("../../config.php");
include_once("../../Database.php");
include_once("../../CentralGold.php");

$admid  = (int)($_POST['admid'] ?? 0);
$id     = (int)($_POST['id'] ?? 0);
$amount = (int)($_POST['gold'] ?? 0);
// Admin-granted gold is treated as PAID gold (it stands in for a real
// purchase/refund the admin is applying by hand), so per the client brief
// it must be portable across worlds too. Default ON; an admin who really
// wants a world-local-only adjustment can pass local_only=1.
$localOnly = ((string) ($_POST['local_only'] ?? '0') === '1');

if($id <= 0 || $amount == 0){
    header("Location: ../../../Admin/admin.php?p=usergold");
    exit;
}

// verificare admin
$check = mysqli_query($GLOBALS["link"], "SELECT access, username FROM ".TB_PREFIX."users WHERE id = $admid");
$acc = mysqli_fetch_assoc($check);
if(!$acc || $acc['access'] != 9) admin_deny('You must be signed in as an administrator to view this page. Your session may have expired — please return to the admin panel and sign in again.');

// 1. UPDATE GOLD
mysqli_query($GLOBALS["link"], "UPDATE ".TB_PREFIX."users SET gold = gold + $amount WHERE id = $id") or die(mysqli_error($GLOBALS["link"]));

// 1b. Mirror into the cross-world paid-gold ledger (see GameEngine/CentralGold.php)
// so this grant follows the player if they register on another Novaterra world
// with the same email. Fails soft: if central gold isn't configured, or the
// player has no email on file yet, the local grant above still stands — this
// world just won't be able to offer portability for it.
$userRow = mysqli_fetch_assoc(mysqli_query($GLOBALS["link"],
    "SELECT username, email FROM ".TB_PREFIX."users WHERE id = $id"));
if ($userRow && !$localOnly && class_exists('CentralGold') && CentralGold::isConfigured()
    && !empty($userRow['email'])) {
    if ($amount > 0) {
        CentralGold::credit($userRow['email'], $userRow['username'], $id, $amount,
            'admin_grant', 'Admin gift by ' . ($acc['username'] ?? ''), $admid);
    } else {
        // Negative amount = admin deduction. debit() never takes the central
        // balance below zero; if the player's central balance is lower than
        // the local deduction (e.g. they already spent central gold on
        // another world), it simply debits what's available rather than
        // failing the whole request — the local users.gold change above is
        // authoritative for this world either way.
        CentralGold::debit($userRow['email'], $userRow['username'], $id, abs($amount),
            'admin_deduct', 'Admin deduction by ' . ($acc['username'] ?? ''), $admid);
    }
}

// 2. ADMIN LOG
$name = $userRow['username'] ?? '';
$name = mysqli_real_escape_string($GLOBALS["link"], $name);
mysqli_query($GLOBALS["link"], "INSERT INTO ".TB_PREFIX."admin_log VALUES (0, $admid, 'Added <b>$amount</b> gold to user <a href=\'admin.php?p=player&uid=$id\'>$name</a>', ".time().")");

// 3. GOLD_FIN_LOG (pentru a2b2.php)
$vill = mysqli_fetch_assoc(mysqli_query($GLOBALS["link"], "SELECT wref FROM ".TB_PREFIX."vdata WHERE owner = $id LIMIT 1"));
$wid = (int)($vill['wref'] ?? 0);
$action = $amount > 0 ? 'Admin added Gold' : 'Admin removed Gold';
$adminName = $acc['username'];
$details = mysqli_real_escape_string($GLOBALS["link"], 'Admin gift by '.$adminName);
$now = time();

mysqli_query($GLOBALS["link"], "INSERT INTO ".TB_PREFIX."gold_fin_log (wid, uid, action, gold, time, details) VALUES ($wid, $id, '$action', $amount, $now, '$details')") or die(mysqli_error($GLOBALS["link"]));

header("Location: ../../../Admin/admin.php?p=usergold&g");
exit;
?>