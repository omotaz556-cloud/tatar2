<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       sysmessage.php                                              ##
##  Type           BACKEND                                                     ##
##  Purpose        Handler for the Admin Panel "Create System Message" form    ##
##                 (Admin/Templates/sysmessage.tpl). Delivers a message to     ##
##                 every player's normal inbox (mdata), from the System        ##
##                 account, same delivery mechanism as massmessage.php.        ##
##                                                                             ##
##  FIX: this used to write Templates/text.tpl (from text_format.tpl) and set  ##
##  users.ok = 1, which forces menu.tpl to swap EVERY page for every player    ##
##  with a raw PHP include() of that generated file instead of the normal     ##
##  village view (see menu.tpl ~line 362, "$sessionOk" block). Any message     ##
##  content that didn't survive the string-escaping perfectly broke that      ##
##  include for the whole server ("broken screen" on returning to the game),  ##
##  and even when it didn't, replacing the whole page with a bare announcement ##
##  box looked broken. Sending as a normal inbox message avoids all of that.   ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
##                                                                             ##
#################################################################################

// #299: load CSRF helpers + admin_deny() before the access check below.
require_once(__DIR__ . '/../csrf.php');
session_start();

include_once("../../config.php");
include_once("../../Database.php");

if (!isset($_SESSION['access']) || $_SESSION['access'] < ADMIN) {
    admin_deny('You must be signed in as an administrator to view this page. Your session may have expired — please return to the admin panel and sign in again.');
}

// Issue #139: this Mod is POSTed to directly, so it must verify the CSRF token
// itself (it does not go through admin.php's central csrf_verify()).
require_once(__DIR__ . '/../csrf.php');
csrf_verify();

/*
|--------------------------------------------------------------------------
| STEP 1 - PREPARE (show confirmation)
|--------------------------------------------------------------------------
*/
if (isset($_POST['action']) && $_POST['action'] == 'prepare') {

    if (empty($_POST['subject']))  die("Subject required");
    if (empty($_POST['message']))  die("Message required");

    $_SESSION['sys_subject'] = trim($_POST['subject']);
    $_SESSION['sys_message'] = trim($_POST['message']);

    // Same whitelist as massmessage.php, so the subject color is handled
    // identically for both admin message forms.
    $allowedColors = array('black', 'red', 'green', 'blue', 'orange', 'purple', 'brown');
    $color = strtolower(trim($_POST['color'] ?? ''));
    if (!in_array($color, $allowedColors, true)) {
        $color = 'black';
    }
    $_SESSION['sys_color'] = $color;

    header("Location: ../../../Admin/admin.php?p=sysmessage&confirm=1");
    exit;
}

/*
|--------------------------------------------------------------------------
| STEP 2 - EXECUTE (deliver the system message to every player's inbox)
|--------------------------------------------------------------------------
*/
if (isset($_POST['action']) && $_POST['action'] == 'execute') {

    // Cancel button
    if (isset($_POST['confirm']) && $_POST['confirm'] == 'No') {
        unset($_SESSION['sys_subject'], $_SESSION['sys_message'], $_SESSION['sys_color']);
        header("Location: ../../../Admin/admin.php?p=sysmessage");
        exit;
    }

    if (empty($_SESSION['sys_subject']) || empty($_SESSION['sys_message'])) {
        header("Location: ../../../Admin/admin.php?p=sysmessage");
        exit;
    }

    $subject = $database->escape($_SESSION['sys_subject']);
    $message = $_SESSION['sys_message'];
    $color   = $database->escape($_SESSION['sys_color'] ?: 'black');

    /*
    |--------------------------------------------------------------------------
    | [color=#hex]...[/color] -> inline <span>, same conversion sysmessage.tpl's
    | own preview already does. BBCode.php (the parser that renders every
    | inbox message) has no [color] support, so leaving the tag as-is would
    | show the raw bbcode instead of colored text.
    |--------------------------------------------------------------------------
    */
    $message = preg_replace('/\[color=(#[0-9a-fA-F]{3,6})\]/', '<span style="color:$1">', $message);
    $openSpans = substr_count($message, '<span style="color:');
    $message = preg_replace('/\[\/color\]/', '</span>', $message, $openSpans);
    $message = str_replace('[/color]', '', $message);

    $message = "[message]" . $message . "[/message]";
    $message = $database->escape($message);

    /*
    |--------------------------------------------------------------------------
    | ALL PLAYERS (same target set + row shape as massmessage.php, so this
    | shows up as a normal inbox message "from" the System account instead of
    | the old full-page text.tpl takeover.)
    |--------------------------------------------------------------------------
    */
    $result = mysqli_query(
        $database->dblink,
        "SELECT id
         FROM ".TB_PREFIX."users
         WHERE id > 5
         ORDER BY id ASC"
    );

    $rows = [];
    $time = time();

    while ($user = mysqli_fetch_assoc($result)) {

        $uid = (int)$user['id'];

        $rows[] =
        "(".
            $uid.",".
            "1,".
            "'<span style=\"color:".$color.";\">".$subject."</span>',".
            "'".$message."',".
            "0,".
            "0,".
            "0,".
            $time.",".
            "0,".
            "0,".
            "0,".
            "0,".
            "0,".
            "0".
        ")";
    }

    if (!empty($rows)) {

        $sql =
        "INSERT INTO ".TB_PREFIX."mdata
        (
            target,
            owner,
            topic,
            message,
            viewed,
            archived,
            send,
            time,
            deltarget,
            delowner,
            alliance,
            player,
            coor,
            report
        )
        VALUES
        ".implode(",", $rows);

        mysqli_query(
            $database->dblink,
            $sql
        );
    }

    unset($_SESSION['sys_subject'], $_SESSION['sys_message'], $_SESSION['sys_color']);

    header("Location: ../../../Admin/admin.php?p=sysmessage&done=1");
    exit;
}

// Fallback
header("Location: ../../../Admin/admin.php?p=sysmessage");
exit;
?>