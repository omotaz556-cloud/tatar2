<?php
/**
 * Greek.sa direct links page (spieler.php?s=2&dl=1) — روابط مباشرة.
 */

$query = mysqli_query(
    $database->dblink,
    "SELECT * FROM `" . TB_PREFIX . "links`
     WHERE userid = " . (int) $session->uid . "
     ORDER BY pos ASC"
) or die(mysqli_error($database->dblink));

$links = array();
while ($data = mysqli_fetch_assoc($query)) {
    $links[] = $data;
}

$gkLblQuickLinks = defined('TZ_GK_QUICK_LINKS') ? TZ_GK_QUICK_LINKS : 'روابط سريعة';
$gkLblTitle = defined('TZ_GK_LINK_TITLE') ? TZ_GK_LINK_TITLE : 'العنوان';
$gkLblTarget = defined('TZ_GK_LINK_TARGET') ? TZ_GK_LINK_TARGET : 'الهدف';
$gkLblAdd = defined('TZ_ADD') ? TZ_ADD : 'إضافة';
?>
<form action="spieler.php?s=2&amp;dl=1" method="post" class="gk-links-form">
<input type="hidden" name="ft" value="p2" />
<input type="hidden" name="gk_links_only" value="1" />

<table cellpadding="1" cellspacing="1" id="links" class="gk-links-table gk-acc-table">
    <colgroup>
        <col class="gk-links-col-idx" />
        <col class="gk-links-col-name" />
        <col class="gk-links-col-url" />
    </colgroup>
    <thead>
        <tr>
            <th colspan="3" class="rbg"><?php echo htmlspecialchars($gkLblQuickLinks, ENT_QUOTES, 'UTF-8'); ?></th>
        </tr>
        <tr class="gk-links-cols gk-acc-cols">
            <th class="gk-links-idx">+</th>
            <th><?php echo htmlspecialchars($gkLblTitle, ENT_QUOTES, 'UTF-8'); ?></th>
            <th><?php echo htmlspecialchars($gkLblTarget, ENT_QUOTES, 'UTF-8'); ?></th>
        </tr>
    </thead>
    <tbody>
<?php
$i = 0;
$last_pos = 0;

foreach ($links as $link):
    $last_pos = (int) $link['pos'];
?>
        <tr>
            <td class="gk-links-idx">
                <span class="gk-links-idx-inner">
                <a class="gk-links-del" href="spieler.php?s=2&amp;dl=1&amp;del=<?php echo (int) $link['id']; ?>"
                   title="<?php echo htmlspecialchars(DELETE, ENT_QUOTES, 'UTF-8'); ?>">
                    <img class="del" src="img/x.gif" alt="<?php echo htmlspecialchars(DELETE, ENT_QUOTES, 'UTF-8'); ?>" />
                </a>
                <input class="text gk-links-nr-input" type="text"
                       name="nr<?php echo $i; ?>"
                       value="<?php echo (int) $link['pos']; ?>"
                       size="1" maxlength="3" />
                </span>
                <input type="hidden" name="id<?php echo $i; ?>" value="<?php echo (int) $link['id']; ?>" />
            </td>
            <td class="gk-links-name">
                <input class="text gk-links-input" type="text"
                       name="linkname<?php echo $i; ?>"
                       value="<?php echo htmlspecialchars($link['name'], ENT_QUOTES, 'UTF-8'); ?>"
                       maxlength="30" />
            </td>
            <td class="gk-links-url">
                <input class="text gk-links-input" type="text"
                       name="linkziel<?php echo $i; ?>"
                       value="<?php echo htmlspecialchars($link['url'], ENT_QUOTES, 'UTF-8'); ?>"
                       maxlength="255" />
            </td>
        </tr>
<?php
    $i++;
endforeach;
?>
        <tr>
            <td class="gk-links-idx">
                <span class="gk-links-idx-inner">
                <input class="text gk-links-nr-input" type="text"
                       name="nr<?php echo $i; ?>"
                       value="<?php echo $last_pos + 1; ?>"
                       size="1" maxlength="3" />
                </span>
            </td>
            <td class="gk-links-name">
                <input class="text gk-links-input" type="text"
                       name="linkname<?php echo $i; ?>"
                       value="" maxlength="30" />
            </td>
            <td class="gk-links-url">
                <input class="text gk-links-input" type="text"
                       name="linkziel<?php echo $i; ?>"
                       value="" maxlength="255" />
            </td>
        </tr>
    </tbody>
</table>

<p class="gk-links-btn-wrap">
    <button type="submit" class="gk-prof-save gk-links-add-btn"><?php echo htmlspecialchars($gkLblAdd, ENT_QUOTES, 'UTF-8'); ?></button>
</p>
</form>
