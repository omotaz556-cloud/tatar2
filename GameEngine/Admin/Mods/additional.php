<?php

#################################################################################
## -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                              ##
## --------------------------------------------------------------------------- ##
## Project:     Novaterra (Refactor incremental)                                ##
## File:        additional.tpl                                                 ##
## Type         BACKEND                                                        ##
## Description: Implement Gold Log			                                   ##
## Made by:     Shadow  													   ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## URLs:        https://novaterra.example                                           ##
##              https://github.com/omotaz556-cloud/tatar                        ##
## 				                                                               ##
#################################################################################

include_once("../../config.php");
include_once("../../Database.php");
include_once("../../CentralGold.php");

// #299: load CSRF helpers + admin_deny() before the access check below.
require_once(__DIR__ . '/../csrf.php');
if (!isset($_SESSION)) session_start();
if(($_SESSION['access']?? 0) < ADMIN) admin_deny('You must be signed in as an administrator to view this page. Your session may have expired — please return to the admin panel and sign in again.');

// Issue #139: this Mod is POSTed to directly, so it must verify the CSRF token
// itself (it does not go through admin.php's central csrf_verify()).
require_once(__DIR__ . '/../csrf.php');
csrf_verify();

// --- INPUT ---
$id = (int)($_POST['id']?? 0);
$admid = (int)($_POST['admid']?? 0);
$access = (int)($_POST['access']?? 2);
$newGold = (int)($_POST['gold']?? 0);
$sit1 = (int)($_POST['sitter1']?? 0);
$sit2 = (int)($_POST['sitter2']?? 0);
$protect = time() + ((int)($_POST['protect']?? 0) * 86400);
$cp = (int)($_POST['cp']?? 0);
$ap = (int)($_POST['off']?? 0);
$dp = (int)($_POST['def']?? 0);
$rr = (int)($_POST['res']?? 0);
$apall = (int)($_POST['ooff']?? 0);
$dpall = (int)($_POST['odef']?? 0);
$vac_mode = (int)($_POST['vac_mode']?? 0);
// Issue: enabling Vacation from the Admin Panel only ever wrote vac_mode
// via the raw UPDATE below, never vac_time. The real vacation flow
// (Profile::setvactionmode -> Database::setvacmode) always sets vac_mode=1
// TOGETHER WITH a future vac_time; the login check in Account.php only
// blocks login (and so only preserves vac_mode) while vac_time > time().
// With vac_time left at its old/zero value, login was never blocked, so
// the very next successful login unconditionally cleared vac_mode via
// Database::removevacationmode(). Fix: drive vac_mode/vac_time through the
// SAME functions the real player-facing vacation flow uses, so an
// admin-enabled vacation behaves identically to a player-activated one
// (including surviving logout/login) instead of being a separate,
// non-persistent code path.
$vac_days = (int)($_POST['vac_days'] ?? 7);
if ($vac_days < 2) $vac_days = 2;
if ($vac_days > 14) $vac_days = 14;

if($id <= 0) die("Invalid user");

// --- GOLD LOGIC ---
// Bug found during testing (verified against a live DB, not just by
// inspection): mode=1 here made getUserField() look this user up BY
// USERNAME using the numeric id as the username string, which never
// matches, so $oldGold silently always read as 0 - $diffGold was really
// just $newGold, not a delta. That corrupted the gold_fin_log entries AND
// (post-fix) would have hugely overcounted the CentralGold admin_grant
// mirror below, crediting the player's FULL gold total on every single
// save of this form, not the actual change. mode=0 is the correct
// "look up BY ID" mode, matching the getUserField($id, 'username', 0)
// call already used correctly further down in this same file.
$oldGold = (int)$database->getUserField($id, 'gold', 0);
$diffGold = $newGold - $oldGold;

// --- UPDATE USER --- (vac_mode/vac_time are handled separately below via
// setvacmode()/removevacationmode(), the same functions the real player
// vacation flow and the existing admin "Cancel Vacation" button use)
$database->query("
    UPDATE ".TB_PREFIX."users SET
        access = $access,
        gold = $newGold,
        sit1 = $sit1,
        sit2 = $sit2,
        protect = $protect,
        cp = $cp,
        ap = $ap,
        dp = $dp,
        RR = $rr,
        apall = $apall,
        dpall = $dpall
    WHERE id = $id
");

if ($vac_mode == 1) {
    $database->setvacmode($id, $vac_days);
} else {
    $database->removevacationmode($id);
}

// Needed by both the gold logging block below and the admin-log block
// further down - computed here (moved up from its original position
// after the gold block) so the CentralGold ledger mirror below can use
// them without relying on variables that weren't defined yet.
$adminUid = $admid > 0? $admid : (int)($_SESSION['id']?? 0); // FIX AICI
$adminName = $database->getUserField($adminUid, 'username', 0)?: 'Admin';
$playerName = $database->getUserField($id, 'username', 0)?: 'Unknown';

// --- LOG GOLD dacă s-a modificat ---
if($diffGold!== 0){
    $vill = $database->getVillagesID($id);
    $wid = $vill[0]?? 0;
    $action = $diffGold > 0? 'Admin added Gold' : 'Admin removed Gold';
    $details = 'Admin adjustment by '.($session->username?? 'Admin');
    $now = time();

    // folosește mysqli_real_escape_string dacă $database->query nu face escape automat
    $action_esc = mysqli_real_escape_string($GLOBALS["link"], $action);
    $details_esc = mysqli_real_escape_string($GLOBALS["link"], $details);

    $database->query("
        INSERT INTO ".TB_PREFIX."gold_fin_log
        (uid, wid, action, gold, time, details)
        VALUES ($id, $wid, '$action_esc', $diffGold, $now, '$details_esc')
    ");

    // Mirror this Gold change into the CentralGold ledger under reason
    // 'admin_grant', the SAME reason centralGoldAdmin.php's "grant" action
    // uses. This does NOT change what users.gold IS (it stays local Free
    // Gold, spendable in-game exactly as before, per CentralGold.php's own
    // documented design) and does NOT touch Paid/Free classification of
    // users.gold anywhere else. It only makes this admin path visible to
    // CentralGold::adminGrantedNet()/nonAdminGrantedBalance(), which is
    // what Vacation Test Mode (VACATION_TEST_MODE_ADMIN_GOLD) reads. Before
    // this, gold granted from Admin -> Users -> Additional was invisible to
    // that ledger entirely, so turning Test Mode ON never affected gold
    // granted from this screen — only gold granted via the separate
    // Central Gold Admin "grant by email" tool. Requires the player to have
    // a registered email (same requirement CentralGold enforces everywhere
    // else); silently skipped otherwise.
    $playerEmailForLedger = (string)$database->getUserField($id, 'email', 0);
    if ($playerEmailForLedger !== '' && class_exists('CentralGold') && CentralGold::isConfigured()) {
        $ledgerNote = 'Admin grant via User List by '.($session->username?? 'Admin');
        if ($diffGold > 0) {
            CentralGold::credit($playerEmailForLedger, $playerName, $id, $diffGold, 'admin_grant', $ledgerNote, $adminUid);
        } else {
            // Debit fails cleanly (no-op) if the admin_grant balance on the
            // ledger is insufficient to cover the reduction - it will never
            // push a player's real purchased gold negative.
            CentralGold::debit($playerEmailForLedger, $playerName, $id, abs($diffGold), 'admin_grant', $ledgerNote, $adminUid);
        }
    }
}

// --- LOG ADMIN (cu UID, nu nume) ---
$protectDays = (int)($_POST['protect']?? 0);

$logParts = [];
$logParts[] = "Gold: $oldGold → $newGold". ($diffGold!=0? " ($diffGold)" : "");
$logParts[] = "VacMode: $vac_mode";
$logParts[] = "Access: $access";
$logParts[] = "Protect: {$protectDays}d";
$logParts[] = "Sitters: $sit1/$sit2";

$logText = "[$adminName] edited Additional for [$playerName] (UID:$id) - ". implode(' | ', $logParts);
$logText = addslashes($logText);

$now = time();
$database->query("
    INSERT INTO ".TB_PREFIX."admin_log
    (`user`, `log`, `time`)
    VALUES ('$adminUid', '$logText', $now)
");

// --- REDIRECT ---
header("Location:../../../Admin/admin.php?p=player&uid=".$id);
exit;
?>