<?php

include_once("GameEngine/Generator.php");

$start_timer = $generator->pageLoadTimeStart();



#################################################################################

##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##

#################################################################################



use App\Utils\AccessLogger;



include_once("GameEngine/Village.php");

AccessLogger::logRequest();



$message->procMessage($_POST);



if (isset($_GET['newdid'])) {

	$_SESSION['wid'] = $_GET['newdid'];

	if (isset($_GET['t'])) {

		header("Location: " . $_SERVER['PHP_SELF'] . "?t=" . $_GET['t']);

		exit();

	} elseif ($_GET['id'] != 0) {

		header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $_GET['id']);

		exit();

	} else {

		header("Location: " . $_SERVER['PHP_SELF']);

		exit();

	}

}



if (isset($_GET['delfriend']) && is_numeric($_GET['delfriend'])) {

	$friend = $database->getUserField($session->uid, "friend" . $_GET['delfriend'], 0);



	for ($i = 0; $i <= 19; $i++) {

		$friend1 = $database->getUserField($friend, "friend" . $i, 0);

		if ($friend1 == $session->uid) {

			$database->deleteFriend($friend, "friend" . $i);

		}

		$friendwait1 = $database->getUserField($friend, "friend" . $i . "wait", 0);

		if ($friendwait1 == $session->uid) {

			$database->deleteFriend($friend, "friend" . $i . "wait");

		}

		$database->checkFriends($friend);

	}



	$database->deleteFriend($session->uid, "friend" . $_GET['delfriend']);

	$database->deleteFriend($session->uid, "friend" . $_GET['delfriend'] . "wait");

	$database->checkFriends($session->uid);

	header("Location: " . $_SERVER['PHP_SELF'] . "?t=1");

	exit();

}



if (isset($_GET['confirm']) && is_numeric($_GET['confirm'])) {

	$myid = $database->getUserArray($session->uid, 1);

	$wait = $database->getUserArray($myid['friend' . $_GET['confirm'] . 'wait'], 1);

	$added = 0;



	for ($i = 0; $i < 20; $i++) {

		$user = $database->getUserField($wait['id'], "friend" . $i, 0);

		if ($user == $session->uid && $added == 0) {

			$database->addFriend($wait['id'], "friend" . $i . "wait", 0);

			$added = 1;

		}

	}



	$database->addFriend($session->uid, "friend" . $_GET['confirm'], $wait['id']);

	$database->addFriend($session->uid, "friend" . $_GET['confirm'] . "wait", 0);

	header("Location: " . $_SERVER['PHP_SELF'] . "?t=1");

	exit();

}



$gkNachrichtenRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang();

$GLOBALS['gkNachrichtenLiteralPage'] = $gkNachrichtenRtl;



if ($gkNachrichtenRtl && isset($_GET['readall']) && (int) $_GET['readall'] === 1) {

	$database->markAllMessagesRead($session->uid);

	$redir = 'nachrichten.php';

	if (isset($_GET['t']) && (int) $_GET['t'] > 0) {

		$redir .= '?t=' . (int) $_GET['t'];

	}

	header('Location: ' . $redir);

	exit;

}



$gkShell = true;

$GLOBALS['gkShell'] = true;

include_once('GameEngine/GreekNachrichten.php');



$gkMsgCss = 'css/greek_maxb_nachrichten.css';

$gkMsgCssVer = is_file(__DIR__ . '/' . $gkMsgCss) ? (int) @filemtime(__DIR__ . '/' . $gkMsgCss) : time();

$gkNachrichtenGreek = $gkNachrichtenRtl && class_exists('GreekNachrichten') && GreekNachrichten::isGreekNachrichtenUi();

$gkMsgTab = isset($_GET['t']) ? (int) $_GET['t'] : 0;
if (isset($_GET['id'])) {
    if (isset($_GET['t']) && $_GET['t'] === '2a') {
        $gkMsgTab = 2;
    } elseif (!isset($_GET['t']) || $_GET['t'] === '0' || $_GET['t'] === '') {
        $gkMsgTab = 0;
    }
}



$gkHeadOpts = array('includeNew2Js' => false);

if ($gkNachrichtenGreek) {

	$gkHeadOpts['extraCss'] = array($gkMsgCss . '?v=' . $gkMsgCssVer);

}



$gkPageTitle = SERVER_NAME . ' - ' . (defined('MESSAGES') ? MESSAGES : 'Messages');

tz_greek_shell_head($gkPageTitle, 'pg-nachrichten', $gkHeadOpts);



if ($gkNachrichtenGreek) {

	tz_greek_shell_open('', array('contentWrap' => false));

	GreekNachrichten::menuOpen($gkMsgTab);

	echo '<div class="gk-nachrichten-body">';

} else {

	tz_greek_shell_open('messages', array('contentWrap' => true));

}



if (isset($_GET['id']) && (!isset($_GET['t']) || $_GET['t'] == '2a')) {

	$message->loadMessage($_GET['id']);

	include("Templates/Message/read.tpl");

} elseif (isset($_GET['t'])) {

	switch ($_GET['t']) {

		case 1:

			if (isset($_GET['id'])) {

				$id = preg_replace("/[^a-zA-Z0-9_-]/", "", $_GET['id']);

			}

			include("Templates/Message/write.tpl");

			break;

		case 2:

			include("Templates/Message/sent.tpl");

			break;

		case 3:

			if ($session->plus) {

				include("Templates/Message/archive.tpl");

			}

			break;

		case 4:

			if ($session->plus) {

				$message->loadNotes();

				include("Templates/Message/notes.tpl");

			}

			break;

		case 5:

			include("Templates/Message/ignored.tpl");

			break;

		default:

			include("Templates/Message/inbox.tpl");

			break;

	}

} else {

	include("Templates/Message/inbox.tpl");

}



if ($gkNachrichtenGreek) {

	echo '</div>';

	GreekNachrichten::menuClose();

}



tz_greek_shell_close(array('buildPopup' => false, 'timer' => $start_timer));

