<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : Chat.php                         	                       ##
##  Type           : Chat System Backend                                       ##
## --------------------------------------------------------------------------- ##
##  Developed by   : TTMMTT           			                               ##
##  Refactored by  : Shadow & Ferywir									       ##
##  Thanks to      : ronix, InCube, Akakori, Elmar & Kirilloid                 ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  URLs:          : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################

if (!isset($SAJAX_INCLUDED)) {

	$GLOBALS['sajax_version'] = '0.12';
	$GLOBALS['sajax_debug_mode'] = 0;
	$GLOBALS['sajax_export_list'] = array();
	$GLOBALS['sajax_request_type'] = 'GET';
	$GLOBALS['sajax_remote_uri'] = '';
	$GLOBALS['sajax_failure_redirect'] = '';


	function sajax_init() {
	}

	function sajax_get_my_uri() {
		return $_SERVER["REQUEST_URI"];
	}
	$sajax_remote_uri = sajax_get_my_uri();


	function sajax_get_js_repr($value) {
		$type = gettype($value);

		if ($type == "boolean") {
			return ($value) ? "Boolean(true)" : "Boolean(false)";
		}
		elseif ($type == "integer") {
			return "parseInt($value)";
		}
		elseif ($type == "double") {
			return "parseFloat($value)";
		}
		elseif ($type == "array" || $type == "object" ) {

			$s = "{ ";
			if ($type == "object") {
				$value = get_object_vars($value);
			}
			foreach ($value as $k=>$v) {
				$esc_key = sajax_esc($k);
				if (is_numeric($k))
					$s .= "$k: " . sajax_get_js_repr($v) . ", ";
				else
					$s .= "\"$esc_key\": " . sajax_get_js_repr($v) . ", ";
			}
			if (count($value))
				$s = substr($s, 0, -2);
			return $s . " }";
		}
		else {
			$esc_val = sajax_esc($value);
			$s = "'$esc_val'";
			return $s;
		}
	}

	function sajax_handle_client_request() {
		global $sajax_export_list;

		$mode = "";

		if (! empty($_GET["rs"]))
			$mode = "get";

		if (!empty($_POST["rs"]))
			$mode = "post";

		if (empty($mode))
			return;

		$target = "";

		if ($mode == "get") {

			header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

			header ("Cache-Control: no-cache, must-revalidate");
			header ("Pragma: no-cache");
			$func_name = $_GET["rs"];
			if (! empty($_GET["rsargs"]))
				$args = $_GET["rsargs"];
			else
				$args = array();
		}
		else {
			$func_name = $_POST["rs"];
			if (! empty($_POST["rsargs"]))
				$args = $_POST["rsargs"];
			else
				$args = array();
		}

		if (! in_array($func_name, $sajax_export_list))
			echo "-:$func_name not callable";
		else {
			echo "+:";
			$result = call_user_func_array($func_name, $args);
			// Emit the result as JSON so the client can JSON.parse() it instead
			// of eval()-ing server output (DOM-XSS / arbitrary code execution).
			echo json_encode($result);
		}
		exit;
	}

	function sajax_get_common_js() {
		global $sajax_debug_mode;
		global $sajax_request_type;
		global $sajax_remote_uri;
		global $sajax_failure_redirect;

		$t = strtoupper($sajax_request_type);
		if ($t != "" && $t != "GET" && $t != "POST")
			return "// Invalid type: $t.. \n\n";

		ob_start();
		?>

		// remote scripting library
		// (c) copyright 2005 modernmethod, inc
		// edited by ttmtt
		var sajax_debug_mode = <?php echo $sajax_debug_mode ? "true" : "false"; ?>;
		var sajax_request_type = "<?php echo $t; ?>";
		var sajax_target_id = "";
		var sajax_failure_redirect = "<?php echo $sajax_failure_redirect; ?>";

		function sajax_debug(text) {
			if (sajax_debug_mode)
				alert(text);
		}

		function sajax_init_object() {
			sajax_debug("sajax_init_object() called..")

			var A;

			var msxmlhttp = new Array(
				'Msxml2.XMLHTTP.5.0',
				'Msxml2.XMLHTTP.4.0',
				'Msxml2.XMLHTTP.3.0',
				'Msxml2.XMLHTTP',
				'Microsoft.XMLHTTP');
			for (var i = 0; i < msxmlhttp.length; i++) {
				try {
					A = new ActiveXObject(msxmlhttp[i]);
				} catch (e) {
					A = null;
				}
			}

			if(!A && typeof XMLHttpRequest != "undefined")
				A = new XMLHttpRequest();
			if (!A)
				sajax_debug("Could not create connection object.");
			return A;
		}

		var sajax_requests = new Array();

		function sajax_cancel() {
			for (var i = 0; i < sajax_requests.length; i++)
				sajax_requests[i].abort();
		}

		function sajax_do_call(func_name, args) {
			var i, x, n;
			var uri;
			var post_data;
			var target_id;

			sajax_debug("in sajax_do_call().." + sajax_request_type + "/" + sajax_target_id);
			target_id = sajax_target_id;
			if (typeof(sajax_request_type) == "undefined" || sajax_request_type == "")
				sajax_request_type = "GET";

			uri = "<?php echo $sajax_remote_uri; ?>";
			if (sajax_request_type == "GET") {
//										alert(args);
				if (uri.indexOf("?") == -1)
					uri += "?rs=" + escape(func_name);
				else
					uri += "&rs=" + escape(func_name);
				uri += "&rst=" + escape(sajax_target_id);
				uri += "&rsrnd=" + new Date().getTime();

				for (i = 0; i < args.length-1; i++) {
					uri += "&rsargs[]=" + args[i];
					}
				post_data = null;
			}
			else if (sajax_request_type == "POST") {
				post_data = "rs=" + escape(func_name);
				post_data += "&rst=" + escape(sajax_target_id);
				post_data += "&rsrnd=" + new Date().getTime();

				for (i = 0; i < args.length-1; i++)
					post_data = post_data + "&rsargs[]=" + escape(args[i]);
			}
			else {
				alert("Illegal request type: " + sajax_request_type);
			}

			x = sajax_init_object();
			if (x == null) {
				if (sajax_failure_redirect != "") {
					location.href = sajax_failure_redirect;
					return false;
				} else {
					sajax_debug("NULL sajax object for user agent:\n" + navigator.userAgent);
					return false;
				}
			} else {
				x.open(sajax_request_type, uri, true);
				// window.open(uri);

				sajax_requests[sajax_requests.length] = x;

				if (sajax_request_type == "POST") {
					x.setRequestHeader("Method", "POST " + uri + " HTTP/1.1");
					x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				}

				x.onreadystatechange = function() {
					if (x.readyState != 4)
						return;

					sajax_debug("received " + x.responseText);

					var status;
					var data;
					var txt = x.responseText.replace(/^\s*|\s*$/g,"");
					status = txt.charAt(0);
					data = txt.substring(2);

					if (status == "") {
						// let's just assume this is a pre-response bailout and let it slide for now
					} else if (status == "-")
						alert("Error: " + data);
					else {
						if (target_id != "")
							document.getElementById(target_id).innerHTML = JSON.parse(data);
						else {
							try {
								var callback;
								var extra_data = false;
								if (typeof args[args.length-1] == "object") {
									callback = args[args.length-1].callback;
									extra_data = args[args.length-1].extra_data;
								} else {
									callback = args[args.length-1];
								}
								callback(JSON.parse(data), extra_data);
							} catch (e) {
								sajax_debug("Caught error " + e + ": Could not eval " + data );
							}
						}
					}
				}
			}

			sajax_debug(func_name + " uri = " + uri + "/post = " + post_data);
			x.send(post_data);
			sajax_debug(func_name + " waiting..");
			delete x;
			return true;
		}

		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function sajax_show_common_js() {
		echo sajax_get_common_js();
	}

	// javascript escape a value
	function sajax_esc($val)
	{
		$val = str_replace("\\", "\\\\", $val);
		$val = str_replace("\r", "\\r", $val);
		$val = str_replace("\n", "\\n", $val);
		$val = str_replace("'", "\\'", $val);
		return str_replace('"', '\\"', $val);
	}

	function sajax_get_one_stub($func_name) {
		ob_start();
		?>

		// wrapper for <?php echo $func_name; ?>

		function x_<?php echo $func_name; ?>() {
			sajax_do_call("<?php echo $func_name; ?>",
				x_<?php echo $func_name; ?>.arguments);
		}

		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function sajax_show_one_stub($func_name) {
		echo sajax_get_one_stub($func_name);
	}

	function sajax_export() {
		global $sajax_export_list;

		$n = func_num_args();
		for ($i = 0; $i < $n; $i++) {
			$sajax_export_list[] = func_get_arg($i);
		}
	}

	$sajax_js_has_been_shown = 0;
	function sajax_get_javascript()
	{
		global $sajax_js_has_been_shown;
		global $sajax_export_list;

		$html = "";
		if (! $sajax_js_has_been_shown) {
			$html .= sajax_get_common_js();
			$sajax_js_has_been_shown = 1;
		}
		foreach ($sajax_export_list as $func) {
			$html .= sajax_get_one_stub($func);
		}
		return $html;
	}

	function sajax_show_javascript()
	{
		echo sajax_get_javascript();
	}


	$SAJAX_INCLUDED = 1;
}

	function add_data($data) {
		global $session,$database;

		//$data = explode("|",$data);
		if (is_array($data)){$msg = htmlspecialchars($data[1]);}else{$msg = htmlspecialchars($data);};
		$msg = trim((string) $msg);
		if ($msg === "") {
			return;
		}
		if (!isset($session->uid) || !$session->uid) {
			return;
		}

		if (!class_exists('MultiAccount')) {
			require_once __DIR__ . '/MultiAccount.php';
		}

		$id_user = (int) $session->uid;

		// Admin-applied mute (Punishment system) takes priority over the
		// message: muted players never reach chat, silently (same UX as a
		// dropped ChatModeration offense below).
		if (!class_exists('Punishment')) {
			require_once __DIR__ . '/Punishment.php';
		}
		if (Punishment::isActive($id_user, Punishment::TYPE_MUTE)) {
			return;
		}

		$name = addslashes((string) $session->username);
		$isPublicChat = !empty($_GET['public']) || basename($_SERVER['PHP_SELF'] ?? '') === 'public_chat.php';
		$chatScope = $isPublicChat ? '__public__' : (string) ($session->alliance ?? '');
		$alliance = $database->escape($chatScope);
		$offense = ChatModeration::enforce($id_user, $session->username ?? '', $chatScope, $msg);
		if ($offense !== false) {
			return;
		}

		$safeMsg = $database->escape($msg);
		$now = time();
		$q = "INSERT into ".TB_PREFIX."chat (id_user,name,alli,date,msg) values ($id_user,'$name','$alliance','$now','$safeMsg')";
		mysqli_query($database->dblink,$q);
	}

	function get_data() {
		global $session,$database;

		// FIX: $data initializat inainte de concatenare - fara asta, PHP 8+
		// arunca "Warning: Undefined variable $data" la fiecare refresh de chat
		// (umple error log-ul; pe PHP 9 devine mai strict).
		$data = '';

		$isPublicChat = !empty($_GET['public']) || basename($_SERVER['PHP_SELF'] ?? '') === 'public_chat.php';
		$chatScope = $isPublicChat ? '__public__' : (string) ($session->alliance ?? '');
		$alliance = $database->escape($chatScope);
		$query = mysqli_query($database->dblink,"select id_user, name, date, msg from ".TB_PREFIX."chat where alli='$alliance' order by id desc limit 0,13");
			while ($r = mysqli_fetch_array($query)) {
			$dates = date("g:i",$r['date']);
			$data .= "[{$dates}] <a href='spieler.php?uid={$r['id_user']}'>{$r['name']}</a>: {$r['msg']} <br>";
			}
		return $data;
	}

	$sajax_request_type = "GET";
	sajax_init();
	sajax_export("add_data","get_data");
	sajax_handle_client_request();

class ChatModeration
{
    public static function ensureSchema()
    {
        $link = isset($GLOBALS['link']) && $GLOBALS['link'] ? $GLOBALS['link'] : null;
        if (!$link) {
            return;
        }

        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "chat_violation_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uid` int(11) NOT NULL DEFAULT 0,
            `username` varchar(255) NOT NULL DEFAULT '',
            `alliance` varchar(255) NOT NULL DEFAULT '',
            `offense` varchar(64) NOT NULL DEFAULT '',
            `score` int(11) NOT NULL DEFAULT 0,
            `action` varchar(32) NOT NULL DEFAULT 'blocked',
            `message` text NOT NULL,
            `created` int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            KEY `uid_time` (`uid`, `created`),
            KEY `offense_time` (`offense`, `created`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    public static function recentViolations($limit = 50)
    {
        $link = isset($GLOBALS['link']) && $GLOBALS['link'] ? $GLOBALS['link'] : null;
        if (!$link) {
            return [];
        }
        self::ensureSchema();

        $limit = max(1, min(200, (int) $limit));
        $res = @mysqli_query($link,
            "SELECT uid, username, alliance, offense, score, action, message, created
             FROM `" . TB_PREFIX . "chat_violation_log`
             ORDER BY id DESC
             LIMIT " . $limit);

        $rows = [];
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $rows[] = $row;
            }
            mysqli_free_result($res);
        }

        return $rows;
    }

    private static function countViolationsInWindow($uid, $seconds)
    {
        $link = isset($GLOBALS['link']) && $GLOBALS['link'] ? $GLOBALS['link'] : null;
        if (!$link) {
            return 0;
        }
        $since = time() - (int) $seconds;
        $res = @mysqli_query($link,
            "SELECT COUNT(*) AS cnt FROM `" . TB_PREFIX . "chat_violation_log`
             WHERE uid = " . (int) $uid . " AND created >= " . $since);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        return $row ? (int) $row['cnt'] : 0;
    }

    private static function logViolation($uid, $username, $alliance, $offense, $score, $action, $message)
    {
        $link = isset($GLOBALS['link']) && $GLOBALS['link'] ? $GLOBALS['link'] : null;
        if (!$link) {
            return;
        }

        self::ensureSchema();

        $uid = (int) $uid;
        $username = substr((string) $username, 0, 255);
        $alliance = substr((string) $alliance, 0, 255);
        $offense = substr((string) $offense, 0, 64);
        $action = substr((string) $action, 0, 32);
        $message = substr((string) $message, 0, 2000);
        $now = time();

        $stmt = mysqli_prepare($link,
            "INSERT INTO `" . TB_PREFIX . "chat_violation_log`
             (uid, username, alliance, offense, score, action, message, created)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            return;
        }
        mysqli_stmt_bind_param($stmt, 'isssissi', $uid, $username, $alliance, $offense, $score, $action, $message, $now);
        @mysqli_stmt_execute($stmt);
        @mysqli_stmt_close($stmt);

        if ($action === 'banned') {
            $logText = 'Chat moderation: banned uid ' . $uid . ' for ' . $offense . ' violation';
            $logMsg = mysqli_real_escape_string($link, $logText);
            @mysqli_query($link,
                "INSERT INTO `" . TB_PREFIX . "admin_log` VALUES (0, 0, '" . $logMsg . "', " . time() . ")");
        }
    }

    public static function detectOffense($uid, $message)
    {
        $msg = trim((string) $message);
        if ($msg === '') {
            return false;
        }

        if (mb_strlen($msg, 'UTF-8') > 220) {
            return ['code' => 'length', 'score' => 20, 'message' => 'Message exceeds 220 chars'];
        }

        $lower = mb_strtolower($msg, 'UTF-8');

		// Links are forbidden in both public and alliance chat. Keep this
		// server-side so the rule cannot be bypassed by disabling JavaScript.
		$linkPattern = '~(?:https?://|ftp://|www\.|(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+(?:com|net|org|io|co|me|tv|ly|ru|de|uk|us|info|biz|app|dev)(?:[/:?#]|$))~iu';
		if (preg_match($linkPattern, $msg)) {
			return ['code' => 'link', 'score' => 30, 'message' => 'Links are not allowed in chat'];
		}

        $adPatterns = [
            'myfatoorah','fatoorah','webhook','website','site','invoice','payment','paypal','pay','buy gold','buy package','gold package','plus package','package gold',
            'شراء الذهب','شراء باقة','شراء جواهر','شراء الذهب','حزمة الذهب','باقة الذهب','فاتورة','الفاتورة','الدفع','الدفع عبر','تجديد الذهب','بيع الذهب','موقع الدفع','متجر الذهب',
            'join now','vip offer','free bonus','offer','discord','telegram','t.me','click here','limited time','promo','حزمة الذهب','تجميع الذهب','شراء الفاتورة'
        ];

        $matchFound = false;
        foreach ($adPatterns as $pattern) {
            if (stripos($lower, (string) $pattern) !== false) {
                $matchFound = true;
                break;
            }
        }

		if ($matchFound) {
            return ['code' => 'ad_spam', 'score' => 30, 'message' => 'Chat spam / payment or promotion pattern'];
        }

        $repeatedChar = preg_match('/(.)\1{8,}/u', $msg);
        if ($repeatedChar) {
            return ['code' => 'repetition', 'score' => 25, 'message' => 'Repeated characters / spam pattern'];
        }

        $wordMatches = preg_split('/\s+/u', $lower, -1, PREG_SPLIT_NO_EMPTY);
        if (count($wordMatches) >= 12 && preg_match('/^(?:[A-Z0-9\s\p{P}\p{S}]+)$/u', $msg) && preg_match('/[A-Z]/u', $msg)) {
            return ['code' => 'caps_spam', 'score' => 20, 'message' => 'Massive caps spam'];
        }

        $link = isset($GLOBALS['link']) && $GLOBALS['link'] ? $GLOBALS['link'] : null;
        if ($link) {
            $uid = (int) $uid;
            $since = time() - 45;
            $stmt = mysqli_prepare($link,
                "SELECT msg FROM `" . TB_PREFIX . "chat`
                 WHERE id_user = ? AND date >= ?
                 ORDER BY id DESC LIMIT 5");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'ii', $uid, $since);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                $recent = [];
                while ($row = mysqli_fetch_assoc($res)) {
                    $recent[] = trim((string) $row['msg']);
                }
                mysqli_stmt_close($stmt);

                $countSame = 0;
                foreach ($recent as $entry) {
                    if ($entry !== '' && mb_strtolower($entry, 'UTF-8') === $lower) {
                        $countSame++;
                    }
                }
                if ($countSame >= 2) {
                    return ['code' => 'duplicate', 'score' => 35, 'message' => 'Duplicate message repeated quickly'];
                }
            }
        }

        return false;
    }

    public static function enforce($uid, $username, $alliance, $message)
    {
        $offense = self::detectOffense($uid, $message);
        if (!$offense) {
            return false;
        }

        $count = self::countViolationsInWindow((int) $uid, 1800);
        $action = ($count >= 2) ? 'banned' : 'blocked';

        if ($action === 'banned' && class_exists('MultiAccount') && method_exists('MultiAccount', 'banAccount')) {
            $reason = 'Chat violation: ' . $offense['code'];
            MultiAccount::banAccount((int) $uid, 0, $reason);
        }

		self::logViolation((int) $uid, (string) $username, (string) $alliance, $offense['code'], (int) $offense['score'], $action, (string) $message);
		if (isset($GLOBALS['database']) && method_exists($GLOBALS['database'], 'sendMessage')) {
			$GLOBALS['database']->sendMessage((int) $uid, 4, 'Chat moderation', 'Your chat message was ' . $action . '. Reason: ' . $offense['code'], 0, 0, 0, 0);
		}

        return ['offense' => $offense['code'], 'action' => $action, 'score' => $offense['score']];
    }
}

?>
