<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Project:       Novaterra      					       		 		  	   ##
##  Version:       06.05.2026 						       	 				   ##
##  Filename       profile.tpl                                                 ##
##  Refactored by  Shadow					                                   ##
##  License:       Novaterra Project                                            ##
##  Copyright:     Novaterra (c) 2010-2026. All rights reserved.                ##
##  URLs:          http://novaterra.example						       	 		   ##
##  Source code:   http://github.com/omotaz556-cloud/tatar/         	       	   ##
##                                                                             ##
#################################################################################

// =========================
// MEDALS LOAD
// =========================

$varmedal = $database->getProfileMedal($session->uid);

$gkSpielerGreek = !empty($GLOBALS['gkSpielerGreek']);
$gkHideClassicMenu = !empty($GLOBALS['gkSpielerLiteralPage'])
    || (class_exists('GreekSpieler') && GreekSpieler::suppressClassicMenu());
$gkEditClass = $gkSpielerGreek ? 'gk-prof-edit' : '';
$gkMedalClass = $gkSpielerGreek ? 'tbg gk-prof-medals' : 'tbg';

if (!$gkHideClassicMenu) {
    echo '<h1>' . PLAYER_PROFILE . '</h1>';
    include __DIR__ . '/menu.tpl';
}
?>

<form action="spieler.php" method="POST" class="<?php echo $gkSpielerGreek ? 'gk-prof-form' : ''; ?>">
<input type="hidden" name="ft" value="p1" />
<input type="hidden" name="id" value="<?php echo isset($id) ? (int)$id : 0; ?>" />

<table cellpadding="0" cellspacing="0" id="edit" class="<?php echo $gkEditClass; ?>">

<thead>
<tr class="gk-prof-edit-user">
    <th colspan="3"><?php echo PLAYER; ?> <?php echo htmlspecialchars($session->username, ENT_QUOTES, 'UTF-8'); ?></th>
</tr>
<tr class="gk-prof-edit-cols">
    <td colspan="2"><?php echo DETAIL; ?></td>
    <td><?php echo DESCRIPTION; ?></td>
</tr>
</thead>

<tbody>

<tr>
<td colspan="2" class="empty"></td>
<td class="empty"></td>
</tr>

<?php
// =========================
// BIRTHDAY SAFE PARSE
// =========================
$birthday = $session->userinfo['birthday'] ?? 0;
$bday = ($birthday != 0) ? explode("-", $birthday) : array('', '', '');
?>

<tr>
<th><?php echo BDAY; ?></th>
<td class="birth">

<input tabindex="1" class="text day" type="text" name="tag"
value="<?php echo $bday[2] ?? ''; ?>" maxlength="2" />

<select tabindex="2" name="monat" class="dropdown">
<option value="0"></option>

<?php
$months = [
1=>'يناير',2=>'فبراير',3=>'مارس',4=>'أبريل',5=>'مايو',6=>'يونيو',
7=>'يوليو',8=>'أغسطس',9=>'سبتمبر',10=>'أكتوبر',11=>'نوفمبر',12=>'ديسمبر'
];

foreach ($months as $k => $v) {
    $sel = (isset($bday[1]) && $bday[1] == $k) ? 'selected' : '';
    echo "<option value='$k' $sel>$v</option>";
}
?>

</select>

<input tabindex="3" type="text" name="jahr"
value="<?php echo $bday[0] ?? ''; ?>"
maxlength="4" class="text year">

</td>

<!-- DESCRIPTION RIGHT -->
<td rowspan="<?php echo 7 + count($database->getProfileVillages($session->uid)); ?>" class="desc1">
<textarea tabindex="7" name="be1" id="desc_be1" maxlength="3000" hidden><?= htmlspecialchars($session->userinfo['desc2'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
<div class="profile-editor" contenteditable="true" data-source="be1" tabindex="7"></div>
</td>
</tr>

<!-- =========================
     GENDER
========================= -->
<tr>
<th><?php echo GENDER; ?></th>
<td class="gend">

<label><input class="radio" type="radio" name="mw" value="0"
<?php if (($session->userinfo['gender'] ?? 0) == 0) echo "checked"; ?>> n/a</label>

<label><input class="radio" type="radio" name="mw" value="1"
<?php if (($session->userinfo['gender'] ?? 0) == 1) echo "checked"; ?>> m</label>

<label><input class="radio" type="radio" name="mw" value="2"
<?php if (($session->userinfo['gender'] ?? 0) == 2) echo "checked"; ?>> f</label>

</td>
</tr>

<!-- LOCATION -->
<tr>
<th><?php echo LOCATION; ?></th>
<td>
<input tabindex="5" type="text" name="ort"
value="<?= htmlspecialchars($session->userinfo['location'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
maxlength="30" class="text">
</td>
</tr>

<tr><td colspan="2" class="empty"></td></tr>

<?php
// =========================
// VILLAGES LIST (SAFE BUT ALLOWS ')
// =========================
$varray = $database->getProfileVillages($session->uid);

for ($i = 0; $i < count($varray); $i++):
?>
<tr>
<th><?php echo VILLAGE_NAME; ?></th>
<td>
<input tabindex="6" type="text"
name="dname<?php echo $i; ?>"
value="<?= htmlspecialchars(function_exists('tz_display_village_name') ? tz_display_village_name($varray[$i]['name'], $session->username) : $varray[$i]['name'], ENT_QUOTES, 'UTF-8') ?>"
maxlength="30" class="text">
</td>
</tr>
<?php endfor; ?>

<!-- DESCRIPTION LEFT -->
<tr>
<td colspan="2" class="desc2">
<textarea tabindex="8" name="be2" id="desc_be2" maxlength="3000" hidden><?= htmlspecialchars($session->userinfo['desc1'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
<div class="profile-editor" contenteditable="true" data-source="be2" tabindex="8"></div>
</td>
</tr>

</tbody>
</table>

<p>
<?php include __DIR__ . '/profile_medals_section.tpl'; ?>
</p>

<style>
.profile-editor { min-height: 170px; padding: 4px; border: 1px solid #71d000; background: #fff; overflow: auto; white-space: pre-wrap; word-break: break-word; }
body.pg-gk.pg-spieler .gk-spieler-body .profile-editor { min-height: 220px; border: 1px solid #7cb044; border-radius: 2px; padding: 6px; }
.profile-editor img { width: 64px; height: 64px; object-fit: contain; margin: 0 5px; vertical-align: middle; }
.badge-option { display: inline-block; width: 26px; height: 26px; object-fit: contain; vertical-align: middle; margin: 0 3px; }
.tbg td:last-child { white-space: nowrap; }
</style>

<!-- JS -->
<script>
function renderProfileEditor(textarea, editor) {
    const badges = {
        roman: 'img/t/roman.gif',
        multihunter: 'img/t/t6_1.png',
        mh: 'img/t/MH.png',
        team: 'img/t/team.png'
    };
    editor.innerHTML = '';
    const parts = textarea.value.split(/(\[(?:#?[a-z0-9_]+|[a-z0-9_]+#)\])/ig);
    parts.forEach(function (part) {
        const key = part.replace(/[\[\]#]/g, '').toLowerCase();
        if (badges[key]) {
            const image = document.createElement('img');
            image.src = '<?php echo GP_LOCATE; ?>' + badges[key];
            image.alt = part;
            image.dataset.code = part;
            editor.appendChild(image);
        } else {
            editor.appendChild(document.createTextNode(part));
        }
    });
}

function editorToBBCode(editor) {
    let value = '';
    function walk(node) {
        if (node.nodeType === Node.TEXT_NODE) {
            value += node.textContent;
            return;
        }
        if (node.nodeName === 'IMG' && node.dataset.code) {
            value += node.dataset.code;
            return;
        }
        if (node.nodeName === 'BR') {
            value += '\n';
            return;
        }
        if (node.childNodes && node.childNodes.length) {
            node.childNodes.forEach(walk);
        }
    }
    editor.childNodes.forEach(walk);
    return value.substring(0, 3000);
}

function syncProfileEditor(editor) {
    const textarea = document.getElementById('desc_' + editor.dataset.source);
    if (!textarea) return;
    textarea.value = editorToBBCode(editor);
}

function insertMedal(code) {
    const textarea = document.querySelector('textarea[name="be1"]');
    if (!textarea) return;

    textarea.value += code;
    const editor = document.querySelector('.profile-editor[data-source="be1"]');
    if (editor) renderProfileEditor(textarea, editor);
}

document.querySelectorAll('.profile-editor').forEach(function (editor) {
    const textarea = document.getElementById('desc_' + editor.dataset.source);
    if (!textarea) return;
    renderProfileEditor(textarea, editor);
    editor.addEventListener('input', function () { syncProfileEditor(editor); });
});

(function () {
    var form = document.querySelector('form');
    if (!form) return;
    form.addEventListener('submit', function () {
        document.querySelectorAll('.profile-editor').forEach(function (editor) {
            syncProfileEditor(editor);
        });
    });
})();
</script>

<?php if ($gkSpielerGreek) { ?>
<p class="btn gk-prof-btn-wrap">
<button type="submit" name="s1" id="btn_ok" class="gk-prof-ok">OK</button>
</p>
<?php } else { ?>
<p class="btn">
<input type="image" name="s1" id="btn_ok"
class="dynamic_img" src="img/x.gif" alt="OK">
</p>
<?php } ?>

</form>
