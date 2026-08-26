<?php

#################################################################################
##                -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-               ##
## --------------------------------------------------------------------------- ##
##  Filename       : chat.tpl                                                  ##
##  Type           : Alliance Internal Chat                                    ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  URLs:          : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : Novaterra Project                                          ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.              ##
## --------------------------------------------------------------------------- ##
#################################################################################

// -------------------------------------------------
// Chat scope: alliance chat or public chat
// -------------------------------------------------
$isPublicChat = !empty($_GET['public']) || basename($_SERVER['PHP_SELF'] ?? '') === 'public_chat.php';
if (!isset($aid)) {
    $aid = (int)$session->alliance;
}

if (!empty($session->uid)) {
    include_once('GameEngine/ChatRead.php');
    $tzChatScope = $isPublicChat
        ? ChatRead::PUBLIC_SCOPE
        : (string) ($aid > 0 ? $aid : ($session->alliance ?? 0));
    ChatRead::markRead((int) $session->uid, $tzChatScope);
}

// -------------------------------------------------
// LOAD ALLIANCE DATA
// -------------------------------------------------
$allianceinfo = $isPublicChat ? [] : $database->getAlliance($aid);

// header (XSS safe)
if ($isPublicChat) {
    echo '<h1>الدردشة العامة</h1>';
} else {
    echo "<h1>"
        . htmlspecialchars($allianceinfo['tag'], ENT_QUOTES, 'UTF-8')
        . " - "
        . htmlspecialchars($allianceinfo['name'], ENT_QUOTES, 'UTF-8')
        . "</h1>";
}

// menu
if (!$isPublicChat) {
    include("alli_menu.tpl");
}
?>

<script type="text/javascript">
<?php sajax_show_javascript(); ?>

// -------------------------------------------------
// CALLBACK: receive chat HTML
// -------------------------------------------------
function show_data_cb(text) {
    document.getElementById("masnun").innerHTML = text;
}

// -------------------------------------------------
// POLLING LOOP (fixed setTimeout)
// -------------------------------------------------
function start_it() {
    x_get_data(show_data_cb);
    setTimeout(start_it, 1000);
}

// -------------------------------------------------
// EMPTY CALLBACK (kept for compatibility)
// -------------------------------------------------
function add_cb() {}

// -------------------------------------------------
// SEND MESSAGE (safe version)
// -------------------------------------------------
function send_data() {

    var msgField = document.form1.msg;
    var msg = msgField.value.trim();

    // prevent empty messages
    if (msg.length === 0) {
        return false;
    }

    if (/(https?:\/\/|ftp:\/\/|www\.|\b[a-z0-9-]+\.(com|net|org|io|co|me|tv|ly|ru|de|uk|us|info|biz|app|dev)([\/:?#]|$))/i.test(msg)) {
        alert("لا يُسمح بنشر الروابط في الدردشة.");
        return false;
    }

    // optional spam protection (client-side)
    if (msg.length > 250) {
        msg = msg.substring(0, 250);
    }

    // send via SAJAX
    x_add_data(msg, add_cb);

    // clear field
    msgField.value = "";

    return false; // prevent form submit reload
}
</script>

<!-- IMPORTANT: no <body> tag here (TPL file) -->

<form name="form1" onsubmit="return send_data();">

    <div id="TitleName" class="chatHeader"><?php echo $isPublicChat ? 'الدردشة العامة' : TZ_ALLY_CHAT; ?></div>

    <div id="chatContainer"
         style="position:relative; height:220px; width:500px; overflow:hidden; background:#FFF; border:1px solid #C0C0C0;">

        <div id="masnun"
             style="position:absolute; top:0; right:5px; width:470px; background:#FFF;"></div>

        <div id="scrollbarbackground2"
             style="position:absolute; top:0; right:481px; width:17px; height:198px;"></div>

        <div id="scrollbarbackground"
             style="position:absolute; top:0; right:489px; width:1px; height:198px; border:1px solid #71D000; background:#FFF;"></div>

        <div id="scrollbar"
             style="position:absolute; top:0; right:481px; width:17px; height:198px; border:1px solid #71D000; background:#F0FFF0;"></div>

        <input id="scrollCheckbox"
               class="fm"
               checked="checked"
               type="checkbox"
               style="position:absolute; top:200px; right:481px;" />
    </div>

    <div style="margin:10px 0;">
        <table cellpadding="1" cellspacing="1">
            <tr>
                <td>
                    <input name="s" value="6" type="hidden" />
                    <input class="text"
                           type="text"
                           name="msg"
                           maxlength="250"
                           style="width:415px;" />
                </td>
                <td>
                    <?php $tzChatRtl = function_exists('tz_is_rtl_lang') && tz_is_rtl_lang(); ?>
                    <input type="button"
                           id="btn_ok"
                           style="border:0; float:<?php echo $tzChatRtl ? 'right' : 'left'; ?>;"
                           alt="<?php echo TZ_OK_3; ?>"
                           onclick="send_data();" />
                </td>
            </tr>
        </table>
    </div>

</form>

<!-- extra container (kept for compatibility) -->
<div id="rooms"></div>

<script>
// start chat after DOM ready
start_it();
</script>