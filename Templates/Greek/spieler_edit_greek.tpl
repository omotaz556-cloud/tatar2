<?php
/**
 * Greek.sa profile edit — من نحن (spieler.php?s=1).
 */

$varmedal = $database->getProfileMedal($session->uid);
require_once dirname(__DIR__, 2) . '/GameEngine/GreekMedalLayout.php';
$gkMedalClass = 'gk-prof-medals gk-prof-medals-greek';
$gkMedalGreekLayout = true;
$gkSpielerGreek = true;

$gkMedalWeekMapEdit = GreekMedalLayout::weekMapFromVarmedal($varmedal);
$gkMedalWallText = GreekMedalLayout::layoutBbByWeekRuns(
    (string) ($session->userinfo['desc2'] ?? ''),
    $gkMedalWeekMapEdit
);

$ranking->procRankReq($_GET);

$birthday = $session->userinfo['birthday'] ?? 0;
$bday = ($birthday != 0 && $birthday !== '0') ? explode('-', $birthday) : array('', '', '');
if (count($bday) < 3) {
    $bday = array_pad($bday, 3, '');
}

$gkAge = 0;
if (!empty($bday[0]) && (int) $bday[0] > 0) {
    $gkAge = (int) date('Y') - (int) $bday[0];
    $birthMonth = (int) ($bday[1] ?? 0);
    $birthDay = (int) ($bday[2] ?? 0);
    if ($birthMonth > 0 && (int) date('n') < $birthMonth) {
        $gkAge--;
    } elseif ($birthMonth > 0 && (int) date('n') === $birthMonth
        && $birthDay > 0 && (int) date('j') < $birthDay) {
        $gkAge--;
    }
    if ($gkAge < 0) {
        $gkAge = 0;
    }
}

$gkRank = $ranking->getUserRank($session->uid);
$varray = $database->getProfileVillages($session->uid);
$fromWref = isset($_SESSION['wid']) ? (int) $_SESSION['wid'] : 0;
$fromCoor = $fromWref ? $database->getCoor($fromWref) : array('x' => 0, 'y' => 0);

$gkProfPopCol = defined('TZ_PROF_POP_COL') ? TZ_PROF_POP_COL : 'سكان';
$gkProfDistCol = defined('TZ_PROF_DISTANCE_COL') ? TZ_PROF_DISTANCE_COL : 'مسافة';
$gkProfVilEditTitle = defined('TZ_PROF_EDIT_VILLAGES') ? TZ_PROF_EDIT_VILLAGES : 'تعديل اسماء القرى';
$gkProfCountry = defined('TZ_PROF_COUNTRY') ? TZ_PROF_COUNTRY : 'البلد';
$gkProfCapShort = defined('TZ_PROF_CAP_SHORT') ? TZ_PROF_CAP_SHORT : 'عاصمة';
$gkMale = defined('MALE') ? MALE : 'ذكر';
$gkFemale = defined('FEMALE') ? FEMALE : 'أنثى';
$gkGender = (int) ($session->userinfo['gender'] ?? 0);

$gkNum = static function ($value) {
    return '<bdi dir="ltr" class="gk-num">' . htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') . '</bdi>';
};

$gkBdayTag = htmlspecialchars($bday[2] !== '' ? $bday[2] : '1', ENT_QUOTES, 'UTF-8');
$gkBdayMon = htmlspecialchars($bday[1] !== '' ? $bday[1] : '1', ENT_QUOTES, 'UTF-8');
$gkBdayYear = htmlspecialchars($bday[0] ?? '', ENT_QUOTES, 'UTF-8');
?>

<form action="spieler.php" method="POST" class="gk-prof-form">
<input type="hidden" name="ft" value="p1" />
<input type="hidden" name="id" value="<?php echo isset($id) ? (int) $id : 0; ?>" />
<input type="hidden" name="tag" value="<?php echo $gkBdayTag; ?>" />
<input type="hidden" name="monat" value="<?php echo $gkBdayMon; ?>" />

<table cellpadding="1" cellspacing="1" id="profile" class="gk-prof-overview gk-prof-edit-main" dir="ltr">
<colgroup>
    <col class="gk-prof-col-desc" />
    <col class="gk-prof-col-detail" />
</colgroup>
<thead>
<tr class="gk-prof-user">
    <th colspan="2"><?php echo htmlspecialchars($session->username, ENT_QUOTES, 'UTF-8'); ?></th>
</tr>
<tr class="gk-prof-cols">
    <th class="gk-prof-h-desc"><?php echo DESCRIPTION; ?></th>
    <th class="gk-prof-h-detail"><?php echo DETAIL; ?></th>
</tr>
</thead>
<tbody>
<tr class="gk-prof-main-row">
<td class="desc1 gk-prof-desc-cell">
<textarea tabindex="1" name="be1" id="desc_be1" maxlength="3000" hidden><?php echo htmlspecialchars($gkMedalWallText, ENT_QUOTES, 'UTF-8'); ?></textarea>
<div class="profile-editor gk-prof-editor gk-prof-medals-wall" contenteditable="true" data-source="be1" tabindex="1"></div>
</td>
<td class="details gk-prof-detail-cell">
<div class="gk-prof-detail-stack">
<table cellpadding="0" cellspacing="0" class="gk-prof-detail-table gk-prof-edit-fields" dir="rtl">
<tr><th><?php echo RANK; ?></th><td class="gk-val-num"><?php echo $gkNum($gkRank); ?></td></tr>
<tr><th><?php echo AGE; ?></th><td class="gk-val-num"><input tabindex="2" type="text" id="gk_prof_age_display" value="<?php echo (int) $gkAge; ?>" maxlength="3" class="text gk-prof-age-input" /><input type="hidden" name="jahr" id="gk_prof_jahr" value="<?php echo $gkBdayYear; ?>" /></td></tr>
<tr><th><?php echo GENDER; ?></th><td class="gend">
<label><input class="radio" type="radio" name="mw" value="1"<?php if ($gkGender === 1 || $gkGender === 0) {
    echo ' checked';
} ?> /> <?php echo htmlspecialchars($gkMale, ENT_QUOTES, 'UTF-8'); ?></label>
<label><input class="radio" type="radio" name="mw" value="2"<?php if ($gkGender === 2) {
    echo ' checked';
} ?> /> <?php echo htmlspecialchars($gkFemale, ENT_QUOTES, 'UTF-8'); ?></label>
</td></tr>
<tr class="gk-prof-edit-last-field"><th><?php echo htmlspecialchars($gkProfCountry, ENT_QUOTES, 'UTF-8'); ?></th><td>
<input tabindex="3" type="text" name="ort" value="<?php echo htmlspecialchars($session->userinfo['location'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" maxlength="30" class="text gk-prof-country-input" />
</td></tr>
</table>
<textarea tabindex="4" name="be2" id="desc_be2" maxlength="3000" hidden><?php echo htmlspecialchars($session->userinfo['desc1'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
<div class="profile-editor gk-prof-editor gk-prof-editor-desc2" contenteditable="true" data-source="be2" tabindex="4"></div>
</div>
</td>
</tr>
</tbody>
</table>

<table cellpadding="1" cellspacing="1" id="villages" class="gk-prof-villages gk-prof-edit-vil" dir="rtl">
<colgroup>
    <col class="gk-prof-col-nam" />
    <col class="gk-prof-col-hab" />
    <col class="gk-prof-col-coords" />
    <col class="gk-prof-col-dist" />
</colgroup>
<thead>
<tr class="gk-prof-vil-title"><th colspan="4"><?php echo htmlspecialchars($gkProfVilEditTitle, ENT_QUOTES, 'UTF-8'); ?></th></tr>
<tr class="gk-prof-vil-cols">
    <th class="gk-prof-col-nam"><?php echo VILLAGE_NAME; ?></th>
    <th class="gk-prof-col-hab"><?php echo $gkProfPopCol; ?></th>
    <th class="gk-prof-col-coords"><?php echo COORDINATES; ?></th>
    <th class="gk-prof-col-dist"><?php echo $gkProfDistCol; ?></th>
</tr>
</thead>
<tbody>
<?php
for ($i = 0, $cnt = count($varray); $i < $cnt; $i++) {
    $vil = $varray[$i];
    $coor = $database->getCoor($vil['wref']);
    $coorX = isset($coor['x']) ? (int) $coor['x'] : 0;
    $coorY = isset($coor['y']) ? (int) $coor['y'] : 0;
    $displayVname = function_exists('tz_display_village_name')
        ? tz_display_village_name($vil['name'], $session->username ?? null)
        : $vil['name'];

    $dist = 0;
    if ($fromWref) {
        $dist = $database->getDistance($fromCoor['x'], $fromCoor['y'], $coorX, $coorY);
    }
    ?>
<tr>
    <td class="nam gk-prof-col-nam">
        <span class="gk-prof-vil-nam-wrap">
            <input tabindex="<?php echo 5 + $i; ?>" type="text" name="dname<?php echo $i; ?>"
                value="<?php echo htmlspecialchars($displayVname, ENT_QUOTES, 'UTF-8'); ?>"
                maxlength="30" class="text gk-prof-vil-input" />
            <?php if (!empty($vil['capital'])) { ?>
            <span class="gk-prof-cap">(<?php echo htmlspecialchars($gkProfCapShort, ENT_QUOTES, 'UTF-8'); ?>)</span>
            <?php } ?>
        </span>
    </td>
    <td class="gk-prof-col-hab gk-val-num"><?php echo $gkNum((int) $vil['pop']); ?></td>
    <td class="gk-prof-col-coords gk-val-num">(<?php echo $gkNum($coorX); ?>,<?php echo $gkNum($coorY); ?>)</td>
    <td class="gk-prof-col-dist gk-val-num"><?php echo $gkNum($dist); ?></td>
</tr>
<?php } ?>
</tbody>
</table>

<p class="btn gk-prof-btn-wrap">
<button type="submit" name="s1" id="btn_save" class="trav_buttons gk-prof-save" tabindex="50"><?php echo SAVE; ?></button>
</p>

<?php include __DIR__ . '/../Profile/profile_medals_section.tpl'; ?>

</form>

<?php
require_once dirname(__DIR__, 2) . '/GameEngine/GreekMedalAssets.php';
require_once dirname(__DIR__, 2) . '/GameEngine/GreekMedalLayout.php';

$gkProfileBadgeMap = [];
foreach (GreekMedalAssets::MAP as $gkBadgeKey => $gkBadgeBase) {
    $gkProfileBadgeMap[$gkBadgeKey] = GreekMedalAssets::url(GP_LOCATE, $gkBadgeKey);
}
$gkProfileBadgeMap += array(
    'artefact' => GP_LOCATE . 'img/gloriamedals/artifact.png',
    'wwbuilder' => GP_LOCATE . 'img/gloriamedals/ww_builder.png',
    'winnerww' => GP_LOCATE . 'img/gloriamedals/ww_winner.png',
    'greatstore' => GP_LOCATE . 'img/gloriamedals/greatstore.png',
    'wallmaster' => GP_LOCATE . 'img/gloriamedals/wallmaster.png',
    'hero100' => GP_LOCATE . 'img/gloriamedals/hero.png',
);
foreach ($database->getProfileMedal($session->uid) as $gkMedalRow) {
    $gkMedalImg = preg_replace('/[^a-zA-Z0-9_.-]/', '', (string) ($gkMedalRow['img'] ?? ''));
    if ($gkMedalImg !== '' && !in_array($gkMedalImg, GreekMedalAssets::BANNERS, true)) {
        $gkProfileBadgeMap[(string) $gkMedalRow['id']] = GreekMedalAssets::url(GP_LOCATE, $gkMedalImg);
    }
}
$gkProfileMedalWeeks = GreekMedalLayout::weekMapFromVarmedal($varmedal);
?>

<script>
window.gkProfileBadgeMap = <?php echo json_encode($gkProfileBadgeMap, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
window.gkProfileMedalWeeks = <?php echo json_encode($gkProfileMedalWeeks, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

function medalKeyFromPart(part) {
    return String(part).replace(/^\[|\]$/g, '').replace(/^#/, '').toLowerCase();
}

function medalGroupKey(key) {
    const weeks = window.gkProfileMedalWeeks || {};
    const normalized = String(key).toLowerCase();
    if (weeks[normalized] !== undefined) {
        return weeks[normalized];
    }
    return 'special:' + normalized;
}

function nodeToBBCode(node) {
    if (node.nodeType === Node.TEXT_NODE) {
        return node.textContent;
    }
    if (node.nodeName === 'IMG' && node.dataset.code) {
        return node.dataset.code;
    }
    if (node.nodeName === 'BR') {
        return '\n';
    }
  let value = '';
    if (node.childNodes && node.childNodes.length) {
        node.childNodes.forEach(function (child) {
            value += nodeToBBCode(child);
        });
    }
    return value;
}

function renderProfileEditor(textarea, editor) {
    const badges = window.gkProfileBadgeMap || {};
    editor.innerHTML = '';
    const lines = textarea.value.split('\n');

    lines.forEach(function (line) {
        if (line === '' && lines.length > 1) {
            return;
        }
        const row = document.createElement('div');
        row.className = 'gk-medal-row';
        const parts = line.split(/(\[(?:#?[\w]+|[\w]+#)\])/ig);
        parts.forEach(function (part) {
            if (!part) {
                return;
            }
            if (!/^\[/.test(part)) {
                if (part) {
                    row.appendChild(document.createTextNode(part));
                }
                return;
            }
            const key = medalKeyFromPart(part);
            const src = badges[key];
            if (src) {
                const image = document.createElement('img');
                image.src = src;
                image.alt = part;
                image.dataset.code = part;
                image.className = 'gk-inline-medal gk-medal-badge';
                image.contentEditable = 'false';
                row.appendChild(image);
            } else {
                row.appendChild(document.createTextNode(part));
            }
        });
        if (row.childNodes.length) {
            editor.appendChild(row);
        }
    });
}

function editorToBBCode(editor) {
    const rows = editor.querySelectorAll('.gk-medal-row');
    if (rows.length) {
        const lines = [];
        rows.forEach(function (row) {
            let line = '';
            row.childNodes.forEach(function (node) {
                line += nodeToBBCode(node);
            });
            lines.push(line);
        });
        return lines.join('\n').replace(/\n+$/, '').substring(0, 3000);
    }

    let value = '';
    editor.childNodes.forEach(function (node) {
        value += nodeToBBCode(node);
    });
    return value.substring(0, 3000);
}

function syncProfileEditor(editor) {
    const textarea = document.getElementById('desc_' + editor.dataset.source);
    if (!textarea) return;
    textarea.value = editorToBBCode(editor);
}

function getLastMedalGroupInEditor(editor) {
    const rows = editor.querySelectorAll('.gk-medal-row');
    if (!rows.length) {
        return null;
    }
    const lastRow = rows[rows.length - 1];
    const imgs = lastRow.querySelectorAll('img[data-code]');
    if (!imgs.length) {
        return null;
    }
    return medalGroupKey(medalKeyFromPart(imgs[imgs.length - 1].dataset.code));
}

function appendMedalToEditor(editor, image, newGroupKey) {
    const lastGroup = getLastMedalGroupInEditor(editor);
    let targetRow = null;

    if (lastGroup !== null && lastGroup === newGroupKey) {
        const rows = editor.querySelectorAll('.gk-medal-row');
        if (rows.length) {
            targetRow = rows[rows.length - 1];
        }
    }

    if (!targetRow) {
        targetRow = document.createElement('div');
        targetRow.className = 'gk-medal-row';
        editor.appendChild(targetRow);
    }

    targetRow.appendChild(image);
}

function insertMedal(code) {
    const textarea = document.querySelector('textarea[name="be1"]');
    if (!textarea) return;
    const editor = document.querySelector('.profile-editor[data-source="be1"]');
    if (!editor) return;
    const key = medalKeyFromPart(code);
    const src = (window.gkProfileBadgeMap || {})[key];
    if (src) {
        const image = document.createElement('img');
        image.src = src;
        image.alt = code;
        image.dataset.code = code;
        image.className = 'gk-inline-medal gk-medal-badge';
        image.contentEditable = 'false';
        appendMedalToEditor(editor, image, medalGroupKey(key));
        syncProfileEditor(editor);
    } else {
        textarea.value = (textarea.value + code).substring(0, 3000);
        renderProfileEditor(textarea, editor);
    }
}

document.querySelectorAll('.profile-editor').forEach(function (editor) {
    const textarea = document.getElementById('desc_' + editor.dataset.source);
    if (!textarea) return;
    renderProfileEditor(textarea, editor);
    editor.addEventListener('input', function () { syncProfileEditor(editor); });
});

(function () {
    var form = document.querySelector('form.gk-prof-form');
    if (!form) return;
    form.addEventListener('submit', function () {
        document.querySelectorAll('.profile-editor').forEach(function (editor) {
            syncProfileEditor(editor);
        });
        var ageEl = document.getElementById('gk_prof_age_display');
        var yearEl = document.getElementById('gk_prof_jahr');
        if (!ageEl || !yearEl) return;
        var age = parseInt(ageEl.value, 10);
        if (!isNaN(age) && age > 0 && age < 120) {
            yearEl.value = String(new Date().getFullYear() - age);
        } else {
            yearEl.value = '0';
        }
    });
})();
</script>
