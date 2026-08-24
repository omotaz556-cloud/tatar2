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

?>
 
 
<!-- =========================
     PAGE HEADER
========================= -->

<h1><?php echo PLAYER_PROFILE; ?></h1>
<?php include("menu.tpl"); ?>



<form action="spieler.php" method="POST">
<input type="hidden" name="ft" value="p1" />
<input type="hidden" name="id" value="<?php echo isset($id) ? (int)$id : 0; ?>" />

<table cellpadding="1" cellspacing="1" id="edit">

<thead>
<tr>
    <th colspan="3"><?php echo PLAYER; ?> <?php echo htmlspecialchars($session->username, ENT_QUOTES, 'UTF-8'); ?></th>
</tr>
<tr>
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

<!-- =========================
     MEDALS TABLE
========================= -->
<p>
<table cellspacing="1" cellpadding="2" class="tbg">

<tr><td class="rbg" colspan="4"><?php echo MEDALS; ?></td></tr>

<tr>
<td><?php echo CATEGORY; ?></td>
<td><?php echo RANK; ?></td>
<td><?php echo WEEK; ?></td>
<td><?php echo BB_CODE; ?></td>
</tr>

<?php
// =========================
// DYNAMIC MEDALS
// =========================
foreach ($varmedal as $medal) {

    $titel = TZ_MEDAL_BONUS_DEFAULT;

    switch ($medal['categorie']) {
        case "1": $titel=TZ_MEDAL_ATTACKER_WEEK; break;
        case "2": $titel=TZ_MEDAL_DEFENDER_WEEK; break;
        case "3": $titel=TZ_MEDAL_POP_CLIMBER_WEEK; break;
        case "4": $titel=TZ_MEDAL_ROBBER_WEEK; break;
        case "5": $titel=TZ_MEDAL_TOP10_ATT_DEF; break;
        case "6": $titel=TZ_MEDAL_TOP_ATTACK_STREAK." ".$medal['points']; break;
        case "7": $titel=TZ_MEDAL_TOP_DEF_STREAK." ".$medal['points']; break;
        case "8": $titel=TZ_MEDAL_TOP_POP_STREAK." ".$medal['points']; break;
        case "9": $titel=TZ_MEDAL_TOP_ROB_STREAK." ".$medal['points']; break;
        case "10": $titel=TZ_MEDAL_RANK_CLIMBER; break;
        case "11": $titel=TZ_MEDAL_RANK_STREAK." ".$medal['points']; break;
        case "12": $titel=TZ_MEDAL_TOP10_ATTACK; break;
        case "13": $titel=TZ_MEDAL_TOP10_DEF; break;
        case "14": $titel=TZ_MEDAL_TOP10_POP; break;
        case "15": $titel=TZ_MEDAL_TOP10_ROB; break;
        case "16": $titel=TZ_MEDAL_TOP10_RANK; break;
    }

    $medalImage = preg_replace('/[^a-zA-Z0-9_.-]/', '', (string)($medal['img'] ?? ''));
    $medalImageUrl = $medalImage !== ''
        ? GP_LOCATE . 'img/t/' . rawurlencode($medalImage) . '.jpg'
        : '';
    $medalPreview = $medalImageUrl !== ''
        ? '<img class="badge-option" src="' . htmlspecialchars($medalImageUrl, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($titel, ENT_QUOTES, 'UTF-8') . '">'
        : '';
    echo "<tr>
    <td>$titel</td>
    <td>{$medal['plaats']}</td>
    <td>{$medal['week']}</td>
    <td><a href='#' title='إدراج الوسام' onclick=\"insertMedal('[#{$medal['id']}]'); return false;\">" . $medalPreview . "</a></td>
    </tr>";
}
?>

<tr>
<td><?php echo BEGINPRO; ?></td><td>غير متاح</td><td>غير متاح</td>
<td><a href="#" title="[#0]" onclick="insertMedal('[#0]'); return false;"><img class="badge-option" src="<?php echo GP_LOCATE; ?>img/t/tn.gif" alt="[#0]"></a></td>
</tr>

<?php if (NEW_FUNCTIONS_MEDAL_3YEAR): ?>
<tr><td>veteran</td><td></td><td></td><td><a href="#" onclick="insertMedal('[#g2300]'); return false;">[#g2300]</a></td></tr>
<?php endif; ?>

<?php if (NEW_FUNCTIONS_MEDAL_5YEAR): ?>
<tr><td>veteran_5a</td><td></td><td></td><td><a href="#" onclick="insertMedal('[#g2301]'); return false;">[#g2301]</a></td></tr>
<?php endif; ?>

<?php if (NEW_FUNCTIONS_MEDAL_10YEAR): ?>
<tr><td>veteran_10a</td><td></td><td></td><td><a href="#" onclick="insertMedal('[#g2302]'); return false;">[#g2302]</a></td></tr>
<?php endif; ?>

<?php
// =========================
// TRIBE MEDALS
// =========================

$tribeMedals = [];

if (defined('NEW_FUNCTIONS_TRIBE_IMAGES') && NEW_FUNCTIONS_TRIBE_IMAGES) {
    $tribeMedals[1] = [TRIBE1, 'roman'];
    $tribeMedals[2] = [TRIBE2, 'teuton'];
    $tribeMedals[3] = [TRIBE3, 'gaul'];
}

if (defined('NEW_FUNCTION_TRIBE_HUNS') && NEW_FUNCTION_TRIBE_HUNS) {
    $tribeMedals[6] = ['Huns', 'huns'];
}

if (defined('NEW_FUNCTION_TRIBE_EGIPTEANS') && NEW_FUNCTION_TRIBE_EGIPTEANS) {
    $tribeMedals[7] = ['Egyptians', 'egyptians'];
}

if (defined('NEW_FUNCTION_TRIBE_SPARTANS') && NEW_FUNCTION_TRIBE_SPARTANS) {
    $tribeMedals[8] = ['Spartans', 'spartans'];
}

if (defined('NEW_FUNCTION_TRIBE_VIKINGS') && NEW_FUNCTION_TRIBE_VIKINGS) {
    $tribeMedals[9] = ['Vikings', 'vikings'];
}

$tribe = $session->userinfo['tribe'] ?? 0;

if (isset($tribeMedals[$tribe])) {
    [$name, $tag] = $tribeMedals[$tribe];
    $badgeImage = ['roman' => 'roman.gif', 'teuton' => 'teutons.gif', 'gaul' => 'gauls.gif'][$tag] ?? 'roman.gif';

    echo "<tr><td>القبيلة {$name}</td><td>غير متاح</td><td>غير متاح</td>
    <td><a href='#' title='[#{$tag}]' onclick=\"insertMedal('[#{$tag}]'); return false;\"><img class='badge-option' src='" . GP_LOCATE . "img/t/{$badgeImage}' alt='[#{$tag}]'></a></td></tr>";
}

// =========================
// MHS MEDALS
// =========================
if(defined('NEW_FUNCTIONS_MHS_IMAGES') && NEW_FUNCTIONS_MHS_IMAGES){

    if(($session->userinfo['access'] ?? 0) == 9){

        echo "<tr><td>".ADMIN1."</td><td>غير متاح</td><td>غير متاح</td>
        <td><a href='#' title='[#MULTIHUNTER]' onclick=\"insertMedal('[#MULTIHUNTER]'); return false;\"><img class='badge-option' src='" . GP_LOCATE . "img/t/t6_1.png' alt='[#MULTIHUNTER]'></a></td></tr>";

        echo "<tr><td>".ADMIN1."</td><td>غير متاح</td><td>غير متاح</td>
        <td><a href='#' title='[#MH]' onclick=\"insertMedal('[#MH]'); return false;\"><img class='badge-option' src='" . GP_LOCATE . "img/t/MH.png' alt='[#MH]'></a></td></tr>";

        echo "<tr><td>".ADMIN1."</td><td>غير متاح</td><td>غير متاح</td>
        <td><a href='#' title='[#TEAM]' onclick=\"insertMedal('[#TEAM]'); return false;\"><img class='badge-option' src='" . GP_LOCATE . "img/t/team.png' alt='[#TEAM]'></a></td></tr>";

    } elseif(($session->userinfo['access'] ?? 0) == 8){

        echo "<tr><td>".MULTIH1."</td><td>غير متاح</td><td>غير متاح</td>
        <td><a href='#' title='[#MULTIHUNTER]' onclick=\"insertMedal('[#MULTIHUNTER]'); return false;\"><img class='badge-option' src='" . GP_LOCATE . "img/t/t6_1.png' alt='[#MULTIHUNTER]'></a></td></tr>";

        echo "<tr><td>".MULTIH1."</td><td>غير متاح</td><td>غير متاح</td>
        <td><a href='#' title='[#MH]' onclick=\"insertMedal('[#MH]'); return false;\"><img class='badge-option' src='" . GP_LOCATE . "img/t/MH.png' alt='[#MH]'></a></td></tr>";

        echo "<tr><td>".MULTIH1."</td><td>غير متاح</td><td>غير متاح</td>
        <td><a href='#' title='[#TEAM]' onclick=\"insertMedal('[#TEAM]'); return false;\"><img class='badge-option' src='" . GP_LOCATE . "img/t/team.png' alt='[#TEAM]'></a></td></tr>";
    }
}

// =========================
// SHADOW SPECIAL
// =========================
if(($session->userinfo['username'] ?? '') == "Shadow"){

    echo "<tr><td>Shadow</td><td></td><td></td>
    <td><a href='#' onclick=\"insertMedal('[#SHADOW]'); return false;\">[#SHADOW]</a></td></tr>";

    echo "<tr><td>Shadow</td><td></td><td></td>
    <td><a href='#' onclick=\"insertMedal('[#MH]'); return false;\">[#MH]</a></td></tr>";

    echo "<tr><td>Shadow</td><td></td><td></td>
    <td><a href='#' onclick=\"insertMedal('[#TEAM]'); return false;\">[#TEAM]</a></td></tr>";

    echo "<tr><td>Shadow</td><td></td><td></td>
    <td><a href='#' onclick=\"insertMedal('[#EVENT]'); return false;\">[#EVENT]</a></td></tr>";
}

// =========================
// SPECIAL MEDALS
// =========================
if(defined('NEW_FUNCTIONS_SPECIAL_MEDALS_SYSTEM') && NEW_FUNCTIONS_SPECIAL_MEDALS_SYSTEM){
    $uid = (int)$session->uid;

    // 1. Artefact - CORECTAT coloanele (4 td-uri)
    $arte = $database->query("SELECT 1 FROM ".TB_PREFIX."artefacts WHERE owner = $uid LIMIT 1");
    if($arte && $arte->num_rows > 0){
        echo "<tr>
                <td>".TZ_MEDAL_ARTEFACT_HOLDER."</td>
                <td></td>
                <td></td>
                <td><a href='#' onclick=\"insertMedal('[#ARTEFACT]'); return false;\">[#ARTEFACT]</a></td>
              </tr>";
    }

    // 2. WW - CORECTAT la f99 (la tine WW e in f99, nu f40)
    $ww = $database->query("SELECT f.f99 FROM ".TB_PREFIX."vdata v
        INNER JOIN ".TB_PREFIX."fdata f ON f.vref = v.wref
        WHERE v.owner = $uid AND f.f99t = 40 AND f.f99 > 0 LIMIT 1");
    if($ww && $ww->num_rows > 0){
        echo "<tr>
                <td>".TZ_MEDAL_WW_BUILDER."</td>
                <td></td>
                <td></td>
                <td><a href='#' onclick=\"insertMedal('[#WWBUILDER]'); return false;\">[#WWBUILDER]</a></td>
              </tr>";

        $lvl = (int)$ww->fetch_assoc()['f99'];
        if($lvl >= 100){
            echo "<tr>
                    <td>".TZ_MEDAL_WW_WINNER."</td>
                    <td></td>
                    <td></td>
                    <td><a href='#' onclick=\"insertMedal('[#WINNERWW]'); return false;\">[#WINNERWW]</a></td>
                  </tr>";
        }
    }

    // 3. GREATSTORE - DOAR 38 si 39 nivel 20
    $hasGreatStore = false;
    $qgs = $database->query("SELECT f.* FROM ".TB_PREFIX."fdata f 
                             JOIN ".TB_PREFIX."vdata v ON v.wref=f.vref 
                             WHERE v.owner=$uid");
    if($qgs){
        while($f = $qgs->fetch_assoc()){
            $hasWh = $hasGr = false;
            for($i=1; $i<=99; $i++){
                if(!isset($f["f{$i}t"])) continue;
                $type = (int)$f["f{$i}t"];
                $lvl  = (int)$f["f{$i}"];
                if($type == 38 && $lvl == 20) $hasWh = true; // Great Warehouse
                if($type == 39 && $lvl == 20) $hasGr = true; // Great Granary
            }
            if($hasWh && $hasGr){ $hasGreatStore = true; break; }
        }
    }
    if($hasGreatStore){
        echo "<tr>
                <td>".TZ_MEDAL_GREAT_STORE."</td>
                <td></td>
                <td></td>
                <td><a href='#' onclick=\"insertMedal('[#GREATSTORE]'); return false;\">[#GREATSTORE]</a></td>
              </tr>";
    }
	
	// 4. WALLMASTER - 5 sate cu zid 20 (31/32/33), fara f40/f99
	
	$wallCount = 0;
	$qw = $database->query("SELECT f.f40, f.f40t FROM ".TB_PREFIX."fdata f 
                        JOIN ".TB_PREFIX."vdata v ON v.wref=f.vref 
                        WHERE v.owner=$uid");
	if($qw){
    while($r = $qw->fetch_assoc()){
        if((int)$r['f40'] == 20 && in_array((int)$r['f40t'], [31,32,33,42,43,47,50])){
            $wallCount++;
        }
    }
	}
	if($wallCount >= 3){
    echo "<tr>
            <td>".TZ_MEDAL_WALL_MASTER."</td>
            <td></td>
            <td></td>
            <td><a href='#' onclick=\"insertMedal('[#WALLMASTER]'); return false;\">[#WALLMASTER]</a></td>
          </tr>";
	}

	// 5. HERO100 - erou nivel 99+
    $h100 = $database->query("SELECT 1 FROM ".TB_PREFIX."hero WHERE uid=$uid AND level>=99 LIMIT 1");
    if($h100 && $h100->num_rows){
        echo "<tr>
                <td>Hero 99+</td>
                <td></td>
                <td></td>
                <td><a href='#' onclick=\"insertMedal('[#HERO100]'); return false;\">[#HERO100]</a></td>
              </tr>";
    }
}
?>

</table>
</p>

<style>
.profile-editor { min-height: 170px; padding: 4px; border: 1px solid #71d000; background: #fff; overflow: auto; white-space: pre-wrap; word-break: break-word; }
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

function syncProfileEditor(editor) {
    const textarea = document.getElementById('desc_' + editor.dataset.source);
    if (!textarea) return;
    let value = '';
    editor.childNodes.forEach(function (node) {
        value += node.nodeName === 'IMG' ? (node.dataset.code || '') : node.textContent;
    });
    textarea.value = value.substring(0, 3000);
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
</script>

<p class="btn">
<input type="image" name="s1" id="btn_ok"
class="dynamic_img" src="img/x.gif" alt="OK">
</p>

</form>
