<?php
#################################################################################
##  Filename       links.tpl                                                   ##
##  Player direct-links box (sidebar). Configured in spieler.php?s=2.          ##
#################################################################################

if (!function_exists('safeHTML')) {
    function safeHTML($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('safeLinkUrl')) {
    function safeLinkUrl($url)
    {
        $url = trim($url);
        if (stripos($url, 'javascript:') === 0) {
            return '#';
        }
        return $url;
    }
}

if (!isset($database) || !isset($session) || !isset($session->uid)) {
    return;
}

$query = $database->getLinks($session->uid);
if (!$query || mysqli_num_rows($query) < 1) {
    return;
}

$links = array();
while ($data = mysqli_fetch_assoc($query)) {
    $name = isset($data['name']) ? trim($data['name']) : '';
    $url = isset($data['url']) ? trim($data['url']) : '';
    if ($name === '' || $url === '') {
        continue;
    }
    $links[] = $data;
}

if (count($links) < 1) {
    return;
}

$llistTitle = defined('TZ_PRIVATE_LINKS') ? TZ_PRIVATE_LINKS : (defined('TZ_LINKS') ? TZ_LINKS : 'الروابط الخاصة :');
?>
<div id="llist" class="tz-llist">
    <div class="tz-llist-head">
        <a href="spieler.php?s=2"><?php echo safeHTML($llistTitle); ?></a>
    </div>
    <ul class="tz-llist-items">
<?php
foreach ($links as $link) {
    $linkName = isset($link['name']) ? $link['name'] : '';
    $linkUrl = isset($link['url']) ? $link['url'] : '';
    $isExternal = false;

    if (substr($linkUrl, -1) === '*') {
        $isExternal = true;
        $linkUrl = substr($linkUrl, 0, -1);
    }

    $linkUrl = safeLinkUrl($linkUrl);
    $target = $isExternal ? ' target="_blank" rel="noopener noreferrer"' : '';
    $externalIcon = $isExternal
        ? ' <img src="gpack/novaterra_classic/img/a/external.gif" alt="" title="" class="external" />'
        : '';
?>
        <li>
            <a href="<?php echo safeHTML($linkUrl); ?>"<?php echo $target; ?>><?php
                echo safeHTML($linkName) . $externalIcon;
            ?></a>
        </li>
<?php } ?>
    </ul>
</div>
