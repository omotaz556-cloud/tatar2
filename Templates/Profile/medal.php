<?php


#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Project:       Novaterra      					       		 		  	   ##
##  Version:       06.05.2026 						       	 				   ##
##  Filename       medal.php                                                   ##
##  Developed by:  Dzoki                                                       ##
##  Fixed by:      Shadow                                                      ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
##  URLs:          http://novaterra.example      				       	 		   ##
##  Source code:   http://github.com/omotaz556-cloud/tatar/	       	   ##
##                                                                             ##
#################################################################################


    //gp link
    $separator=isset($separator)? $separator:"";

    if (!isset($displayarray) || !is_array($displayarray) || !isset($displayarray['id'])) {
        if (isset($user) && is_array($user) && isset($user['id'])) {
            $displayarray = $user;
        } else {
            $displayarray = [];
        }
    }
    if (!isset($user) || !is_array($user) || !isset($user['id'])) {
        $user = $displayarray;
    }
    $gkProfileOwnerUid = (int) ($displayarray['id'] ?? $user['id'] ?? 0);
    if (!isset($varmedal) && $gkProfileOwnerUid > 0) {
        $varmedal = $database->getProfileMedal($gkProfileOwnerUid);
    }
    if (!is_array($varmedal)) {
        $varmedal = [];
    }

    $profileUser = (isset($user) && is_array($user) && isset($user['id']))
        ? $user
        : $displayarray;
    $gpack_load = isset($profileUser['gpack']) ? $profileUser['gpack']
        : $database->getUserField($_SESSION['username'], 'gpack', 1);
    if($gpack_load== null || GP_ENABLE == false) {
    $gpack= $separator.GP_LOCATE;
    } else {
    $gpack= $separator.$gpack_load;
    }

$profiel = preg_replace('/\[([a-z0-9_]+)#\]/i', '[#$1]', $profiel);

require_once dirname(__DIR__, 2) . '/GameEngine/GreekMedalAssets.php';

$gkProfMedalCompact = !empty($GLOBALS['gkSpielerGreek'])
    || !empty($GLOBALS['gkSpielerProfileGreek']);
if ($gkProfMedalCompact) {
    $gpack = $separator . GP_LOCATE;
}
$gkProfMedalImgClass = $gkProfMedalCompact ? ' class="gk-prof-medal-img"' : '';
$gkMedalTip = function ($text) {
    return addslashes('<table><tr><td>' . $text . '</td></tr></table>');
};
$gkMedalInlineImg = static function ($src, $tipHtml, $extraClass = '') use ($gkProfMedalCompact, $gkProfMedalImgClass) {
    $tip = addslashes($tipHtml);
    $srcEsc = htmlspecialchars((string) $src, ENT_QUOTES, 'UTF-8');
    if ($gkProfMedalCompact) {
        $class = trim('gk-inline-medal ' . $extraClass);
        return '<img src="' . $srcEsc . '" class="' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '" alt="" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],\'' . $tip . '\')">';
    }
    return '<img src="' . $srcEsc . '" border="0"' . $gkProfMedalImgClass . ' onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],\'' . $tip . '\')">';
};
$gkMedalPackImg = static function ($imgName, $fallbackExt = 'jpg') use ($gpack, $gkProfMedalCompact) {
    $imgName = preg_replace('/[^a-zA-Z0-9_.-]/', '', (string) $imgName);
    if ($imgName === '') {
        return '';
    }
    if ($gkProfMedalCompact) {
        if (in_array($imgName, GreekMedalAssets::BANNERS, true)) {
            return '';
        }
        return GreekMedalAssets::url($gpack, $imgName);
    }
    return $gpack . 'img/t/' . $imgName . '.' . $fallbackExt;
};
$gkMedalKeyImg = static function ($key, $tipHtml, $extraClass = '') use ($gpack, $gkProfMedalCompact, $gkMedalInlineImg) {
    if (!$gkProfMedalCompact) {
        return '';
    }
    $src = GreekMedalAssets::url($gpack, $key);
    if ($src === '') {
        return '';
    }
    if ($extraClass === '') {
        $base = GreekMedalAssets::basename($key);
        $extraClass = trim(GreekMedalAssets::extraClass($key) . ' medal ' . ($base ?? ''));
    }
    return $gkMedalInlineImg($src, $tipHtml, $extraClass);
};

//de bird
if($displayarray['protect'] > time()){
$secondsDiff      = $displayarray['protect'] - time();
$remainingDay     = floor($secondsDiff/(3600*24));

$left = \App\Utils\DateTime::getTimeFormat($secondsDiff);
$birdTip = '<table><tr><td>'.PLAYER_HAS.' '.$left.' '.HOURS_OF_BG_PROT.'</td></tr></table>';
$profiel = preg_replace("/\[#0]/is", $gkMedalInlineImg($gpack . 'img/t/tn.gif', $birdTip, 'gk-medal-bird'), $profiel);
} else {
$geregistreerd=date('d.m.Y', ($displayarray['regtime']));
$birdTip = '<table><tr><td>'.PLAYER_WAS_REG_ON.' '.$geregistreerd.'.</td></tr></table>';
$profiel = preg_replace("/\[#0]/is", $gkMedalInlineImg($gpack . 'img/t/tnd.gif', $birdTip, 'gk-medal-bird'), $profiel);
}

// Added by Shadow
if (NEW_FUNCTIONS_TRIBE_IMAGES) {

    $tribe = (int)($displayarray['tribe'] ?? 0);

    switch ($tribe) {
        case 1: // Romans
            $romanImg = $gkProfMedalCompact ? GreekMedalAssets::url($gpack, 'roman') : $gpack . '../../img/rpage/Roman1.jpg';
            $tooltip = '<table><tr><td>'.ROMAN_T_M.'</td></tr></table>';
            $replacement = $gkMedalInlineImg($romanImg, $tooltip, 'gk-medal-tribe');
            $profiel = preg_replace("/\[#roman]/is", $replacement, $profiel);
            break;

        case 2: // Teutons
            $teutonImg = $gkProfMedalCompact ? GreekMedalAssets::url($gpack, 'teuton') : $gpack . '../../img/rpage/Teuton1.jpg';
            $tooltip = '<table><tr><td>'.TEUTON_T_M.'</td></tr></table>';
            $replacement = $gkMedalInlineImg($teutonImg, $tooltip, 'gk-medal-tribe');
            $profiel = preg_replace("/\[#teuton]/is", $replacement, $profiel);
            break;

        case 3: // Gauls
            $gaulImg = $gkProfMedalCompact ? GreekMedalAssets::url($gpack, 'gaul') : $gpack . '../../img/rpage/Gaul1.jpg';
            $tooltip = '<table><tr><td>'.GAUL_T_M.'</td></tr></table>';
            $replacement = $gkMedalInlineImg($gaulImg, $tooltip, 'gk-medal-tribe');
            $profiel = preg_replace("/\[#gaul]/is", $replacement, $profiel);
            break;

        // ==================== NOILE TRIBURI ====================
		case 6: // Huns
			$tooltip = '<table><tr><td>'.(defined('HUNS_T_M') ? HUNS_T_M : TRIBE6).'</td></tr></table>';
			$replacement = '<img src="'.$gpack.'../../img/rpage/Huns1.jpg" border="0" '
                 . 'onmouseout="med_closeDescription()" '
                 . 'onmousemove="med_mouseMoveHandler(arguments[0], \''.addslashes($tooltip).'\')">';
			$profiel = preg_replace('/\[#huns\]/i', $replacement, $profiel);
			break;

		case 7: // Egyptians
			$tooltip = '<table><tr><td>'.(defined('EGYPTIANS_T_M') ? EGYPTIANS_T_M : TRIBE7).'</td></tr></table>';
			$replacement = '<img src="'.$gpack.'../../img/rpage/Egyptians1.jpg" border="0" '
                 . 'onmouseout="med_closeDescription()" '
                 . 'onmousemove="med_mouseMoveHandler(arguments[0], \''.addslashes($tooltip).'\')">';
			$profiel = preg_replace('/\[#egyptians\]/i', $replacement, $profiel);
			break;

		case 8: // Spartans
			$tooltip = '<table><tr><td>'.(defined('SPARTANS_T_M') ? SPARTANS_T_M : TRIBE8).'</td></tr></table>';
			$replacement = '<img src="'.$gpack.'../../img/rpage/Spartans1.jpg" border="0" '
                 . 'onmouseout="med_closeDescription()" '
                 . 'onmousemove="med_mouseMoveHandler(arguments[0], \''.addslashes($tooltip).'\')">';
			$profiel = preg_replace('/\[#spartans\]/i', $replacement, $profiel);
			break;

		case 9: // Vikings
			$tooltip = '<table><tr><td>'.(defined('VIKINGS_T_M') ? VIKINGS_T_M : TRIBE9).'</td></tr></table>';
			$replacement = '<img src="'.$gpack.'../../img/rpage/Vikings1.jpg" border="0" '
                 . 'onmouseout="med_closeDescription()" '
                 . 'onmousemove="med_mouseMoveHandler(arguments[0], \''.addslashes($tooltip).'\')">';
			$profiel = preg_replace('/\[#vikings\]/i', $replacement, $profiel);
			break;
    }
}
// =========================
// NEW_FUNCTIONS_SPECIAL_MEDALS_SYSTEM - DYNAMIC
// =========================
if(defined('NEW_FUNCTIONS_SPECIAL_MEDALS_SYSTEM') && NEW_FUNCTIONS_SPECIAL_MEDALS_SYSTEM){

    $uid = (int)$displayarray['id'];
    $username = htmlspecialchars($displayarray['username'], ENT_QUOTES);
    $tribeMap = [1 => TRIBE1, 2 => TRIBE2, 3 => TRIBE3, 6 => TRIBE6, 7 => TRIBE7, 8 => TRIBE8, 9 => TRIBE9];
    $tribeName = $tribeMap[$displayarray['tribe'] ?? 0] ?? ADM_UNKNOWN_2;

    // luam WW real
	$wwLevel = 0;
	$wwName = 'N/A';

	$qww = $database->query("
    SELECT v.name AS village, f.f99 AS lvl
    FROM ".TB_PREFIX."vdata v
    INNER JOIN ".TB_PREFIX."fdata f ON f.vref = v.wref
    WHERE v.owner = $uid
      AND f.f99t = 40 -- tipul 40 = Wonder
      AND f.f99 > 0 -- nivel real
    ORDER BY f.f99 DESC
    LIMIT 1
	");

	if($qww && $row = $qww->fetch_assoc()){
    $wwLevel = (int)$row['lvl'];
    $wwName = htmlspecialchars($row['village'], ENT_QUOTES);
	}

	// [#ARTEFACT]
	$profiel = preg_replace_callback("/\[#ARTEFACT\]/is", function($m) use ($database,$uid,$username,$tribeName,$gpack,$gkMedalInlineImg){
    $q = $database->query("SELECT size, name FROM ".TB_PREFIX."artefacts WHERE owner=$uid");
    if(!$q || !$q->num_rows) return '';
    
    $sizeMap = [
        1 => MEDAL_ARTEFACT_SMALL,
        2 => MEDAL_ARTEFACT_LARGE,
        3 => MEDAL_ARTEFACT_UNIQUE,
    ];
    
    $arts = '';
    while($a = $q->fetch_assoc()){
        $type = $sizeMap[(int)$a['size']] ?? ADM_UNKNOWN_2;
        $aname = htmlspecialchars($a['name'], ENT_QUOTES);
        $arts .= "<tr><td>Type:</td><td>{$type}</td></tr><tr><td>Artefact:</td><td>{$aname}</td></tr>";
    }
    
    $tip = "<table><tr><td>Name:</td><td>{$username}</td></tr><tr><td>Tribe:</td><td>{$tribeName}</td></tr><tr><td>Category:</td><td>Artefact Holder</td></tr>{$arts}</table>";
    
    return $gkMedalInlineImg($gpack . 'img/gloriamedals/artifact.png', $tip, 'gk-medal-special');
	}, $profiel);

    // [#WWBUILDER]
	if($wwLevel > 0){
    $tip = "<table <tr><td>Name:</td><td>{$username}</td></tr><tr><td>Tribe:</td><td>{$tribeName}</td></tr><tr><td>Category:</td><td>World Wonder</td></tr><tr><td>Village:</td><td>{$wwName}</td></tr><tr><td>WW Level:</td><td>{$wwLevel}</td></tr></table>";
    $profiel = preg_replace("/\[#WWBUILDER\]/is", $gkMedalInlineImg($gpack . 'img/gloriamedals/ww_builder.png', $tip, 'gk-medal-special'), $profiel);
	} else {
    $profiel = str_replace("[#WWBUILDER]", "", $profiel);
	}

    // [#WINNERWW]
    if($wwLevel >= 100){
        $tip = "<table><tr><td>Name:</td><td>{$username}</td></tr><tr><td>Tribe:</td><td>{$tribeName}</td></tr><tr><td>Category:</td><td>Winner</td></tr><tr><td>WW Level:</td><td>100</td></tr></table>";
        $profiel = preg_replace("/\[#WINNERWW\]/is", $gkMedalInlineImg($gpack . 'img/gloriamedals/ww_winner.png', $tip, 'gk-medal-special'), $profiel);
    }
	
	//[#GREATSTORE] - DOAR Great Warehouse (38) si Great Granary (39) nivel 20
	$hasGreatStore = false;
	$gsVillage = '';
	$q = $database->query("SELECT v.name, f.* FROM ".TB_PREFIX."fdata f JOIN ".TB_PREFIX."vdata v ON v.wref=f.vref WHERE v.owner=$uid");
	if($q){
    while($f = $q->fetch_assoc()){
        $wh = $gr = false;
        for($i=1; $i<=99; $i++){
            if(!isset($f["f{$i}t"])) continue;
            $t = (int)$f["f{$i}t"];
            $l = (int)$f["f{$i}"];
            if($l == 20 && $t == 38) $wh = true; // Great Warehouse
            if($l == 20 && $t == 39) $gr = true; // Great Granary
        }
        if($wh && $gr){ 
            $hasGreatStore = true; 
            $gsVillage = htmlspecialchars($f['name'], ENT_QUOTES);
            break; 
        }
    }
	}

	if($hasGreatStore){
    $tip = "<table><tr><td>Name:</td><td>{$username}</td></tr><tr><td>Tribe:</td><td>{$tribeName}</td></tr><tr><td>Category:</td><td>Great Store</td></tr><tr><td>Village:</td><td>{$gsVillage}</td></tr><tr><td>Great Warehouse:</td><td>20</td></tr><tr><td>Great Granary:</td><td>20</td></tr></table>";
    $profiel = str_replace("[#GREATSTORE]", $gkMedalInlineImg($gpack . 'img/gloriamedals/greatstore.png', $tip, 'gk-medal-special'), $profiel);
	} else {
    $profiel = str_replace("[#GREATSTORE]", "", $profiel);
	}
	
	// [#HERO100]
	$q = $database->query("SELECT level FROM ".TB_PREFIX."hero WHERE uid=$uid AND level>=99 LIMIT 1");
	if($q && $q->num_rows){
    $heroLvl = (int)$q->fetch_assoc()['level'];
    $tip = "<table><tr><td>Name:</td><td>{$username}</td></tr><tr><td>Tribe:</td><td>{$tribeName}</td></tr><tr><td>Category:</td><td>Hero Level</td></tr><tr><td>Level:</td><td>{$heroLvl}</td></tr></table>";
    $profiel = str_replace("[#HERO100]", $gkMedalInlineImg($gpack . 'img/gloriamedals/hero.png', $tip, 'gk-medal-special'), $profiel);
	} else {
    $profiel = str_replace("[#HERO100]", "", $profiel);
	}
	
	// [#WALLMASTER] - 3 sate cu zid (31/32/33/42/43/47/50) nivel 20 in slotul 40
	$wallCount = 0;
	$q = $database->query("SELECT f.f40, f.f40t FROM ".TB_PREFIX."fdata f 
                       JOIN ".TB_PREFIX."vdata v ON v.wref=f.vref 
                       WHERE v.owner=$uid");
	if($q){
    while($r = $q->fetch_assoc()){
        if((int)$r['f40'] == 20 && in_array((int)$r['f40t'], [31,32,33,42,43,47,50])){
            $wallCount++;
        }
    }
	}

	if($wallCount >= 3){
    $tip = "<table><tr><td>Name:</td><td>{$username}</td></tr><tr><td>Tribe:</td><td>{$tribeName}</td></tr><tr><td>Category:</td><td>Wall Master</td></tr><tr><td>Walls level 20:</td><td>{$wallCount}</td></tr></table>";
    $profiel = str_replace("[#WALLMASTER]", $gkMedalInlineImg($gpack . 'img/gloriamedals/wallmaster.png', $tip, 'gk-medal-special'), $profiel);
	} else {
    $profiel = str_replace("[#WALLMASTER]", "", $profiel);
	}

}

// METHOD CODED IN CONFIG
// Added by Shadow
if(NEW_FUNCTIONS_MHS_IMAGES){
	if($displayarray['access'] == "9"){
		if ($gkProfMedalCompact) {
			$profiel = preg_replace("/\[#MULTIHUNTER]/is", $gkMedalKeyImg('multihunter', '<table><tr><td>Official Server Global Multihunter</td></tr></table>', 'gk-medal-special medal t6_1'), $profiel);
			$profiel = preg_replace("/\[#MH]/is", $gkMedalKeyImg('mh', '<table><tr><td>' . MEDAL_MH_DESC . '</td></tr></table>', 'gk-medal-special medal t6_2'), $profiel);
			$profiel = preg_replace("/\[#TEAM]/is", $gkMedalKeyImg('team', '<table><tr><td>' . MEDAL_TEAM_DESC . '</td></tr></table>', 'gk-medal-special medal t6_3'), $profiel);
		} else {
			$profiel = preg_replace("/\[#MULTIHUNTER]/is",'<img src="'.$gpack.'img/t/t6_1.png" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],\'<table><tr><td>Official Server Global Multihunter</td></tr></table>\')">', $profiel);
			$profiel = preg_replace("/\[#MH]/is",'<img src="'.$gpack.'img/t/MH.png" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],'.$gkMedalTip(MEDAL_MH_DESC).')">', $profiel);
			$profiel = preg_replace("/\[#TEAM]/is",'<img src="'.$gpack.'img/t/team.png" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],'.$gkMedalTip(MEDAL_TEAM_DESC).')">', $profiel);
		}
	}elseif($displayarray['access'] == "8"){
		if ($gkProfMedalCompact) {
			$profiel = preg_replace("/\[#MULTIHUNTER]/is", $gkMedalKeyImg('multihunter', '<table><tr><td>Official Server Global Multihunter</td></tr></table>', 'gk-medal-special medal t6_1'), $profiel);
			$profiel = preg_replace("/\[#MH]/is", $gkMedalKeyImg('mh', '<table><tr><td>' . MEDAL_MH_DESC . '</td></tr></table>', 'gk-medal-special medal t6_2'), $profiel);
			$profiel = preg_replace("/\[#TEAM]/is", $gkMedalKeyImg('team', '<table><tr><td>' . MEDAL_TEAM_DESC . '</td></tr></table>', 'gk-medal-special medal t6_3'), $profiel);
		} else {
			$profiel = preg_replace("/\[#MULTIHUNTER]/is",'<img src="'.$gpack.'img/t/t6_1.png" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],\'<table><tr><td>Official Server Global Multihunter</td></tr></table>\')">', $profiel);
			$profiel = preg_replace("/\[#MH]/is",'<img src="'.$gpack.'img/t/MH.png" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],'.$gkMedalTip(MEDAL_MH_DESC).')">', $profiel);
			$profiel = preg_replace("/\[#TEAM]/is",'<img src="'.$gpack.'img/t/team.png" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],'.$gkMedalTip(MEDAL_TEAM_DESC).')">', $profiel);
		}
	}
}

// METHOD CODED IN CONFIG
// VETERAN & VETERAN 5 YEARS & VETERAN 10 YEARS IMAGES
if(NEW_FUNCTIONS_MEDAL_3YEAR){
	$vet3Tip = '<table><tr><td>Veteran Player 3 Years<br><br>Medal achieved for playing 3 years of Novaterra.</td></tr></table>';
	$vet3Rep = $gkProfMedalCompact
		? $gkMedalKeyImg('g2300', $vet3Tip, 'gk-medal-special medal t10_1')
		: $gkMedalInlineImg($gpack . 'img/t/Veteran_Medal.jpg', $vet3Tip, 'gk-medal-special');
	$profiel = preg_replace("/\[#g2300]/is", $vet3Rep, $profiel);
}
if(NEW_FUNCTIONS_MEDAL_5YEAR){
	$vet5Tip = '<table><tr><td>Veteran Player 5 Years<br><br>Medal achieved for playing 5 years of Novaterra.</td></tr></table>';
	$vet5Rep = $gkProfMedalCompact
		? $gkMedalKeyImg('g2301', $vet5Tip, 'gk-medal-special medal t200_1')
		: $gkMedalInlineImg($gpack . 'img/t/5year_medal.png', $vet5Tip, 'gk-medal-special');
	$profiel = preg_replace("/\[#g2301]/is", $vet5Rep, $profiel);
}
if(NEW_FUNCTIONS_MEDAL_10YEAR){
	$vet10Tip = '<table><tr><td>Veteran Player 10 Years<br><br>Medal achieved for playing 10 years of Novaterra.</td></tr></table>';
	$vet10Rep = $gkProfMedalCompact
		? $gkMedalKeyImg('g2302', $vet10Tip, 'gk-medal-special medal t210_1')
		: $gkMedalInlineImg($gpack . 'img/t/10_year_medal.png', $vet10Tip, 'gk-medal-special');
	$profiel = preg_replace("/\[#g2302]/is", $vet10Rep, $profiel);
}

// NO NEED TO CODE THIS METHOD
// Added by Shadow
if($displayarray['username'] == "Shadow"){
$profiel = preg_replace("/\[#SHADOW]/is",'<img src="'.$gpack.'img/t/shadow.png" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],\'<table><tr><td>Official Server Administrator of Novaterra Project</td></tr></table>\')">', $profiel);
if ($gkProfMedalCompact) {
	$profiel = preg_replace("/\[#MH]/is", $gkMedalKeyImg('mh', '<table><tr><td>' . MEDAL_MH_DESC . '</td></tr></table>', 'gk-medal-special medal t6_2'), $profiel);
	$profiel = preg_replace("/\[#TEAM]/is", $gkMedalKeyImg('team', '<table><tr><td>' . MEDAL_TEAM_DESC . '</td></tr></table>', 'gk-medal-special medal t6_3'), $profiel);
} else {
	$profiel = preg_replace("/\[#MH]/is",'<img src="'.$gpack.'img/t/MH.png" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],'.$gkMedalTip(MEDAL_MH_DESC).')">', $profiel);
	$profiel = preg_replace("/\[#TEAM]/is",'<img src="'.$gpack.'img/t/team.png" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],'.$gkMedalTip(MEDAL_TEAM_DESC).')">', $profiel);
}
$profiel = preg_replace("/\[#EVENT]/is",'<img src="'.$gpack.'img/t/t10_1.jpg" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],\'<table><tr><td>You played on Novaterra Hammelburg Event. Congrats !</td></tr></table>\')">', $profiel);
}

// NO NEED TO CODE THIS METHOD NATARS
// Added by Shadow
if($displayarray['username'] == "Natars"){
$profiel = preg_replace("/\[#natars]/is",'<img src="'.$gpack.'img/t/t10_2.jpg" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],\'<table><tr><td>Official Natar account</td></tr></table>\')">', $profiel);
$profiel = preg_replace("/\[#WW]/is",'<img src="'.$gpack.'img/t/g40_11-ltr.png" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],\'<table><tr><td>Official World Wonder Village</td></tr></table>\')">', $profiel);
}

// NO NEED TO CODE THIS METHOD NATURE
// Added by Shadow
if($displayarray['username'] == "Nature"){
$profiel = preg_replace("/\[#NATURE]/is",'<img src="'.$gpack.'img/t/nature.png" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],\'<table><tr><td>Natures troops are the animals living in unoccupied oases. You can use the combat simulator to see whether you have enough troops to defeat the animals in an oasis you want to conquer, but remember that you can only raid oasis. Keep in mind that all the animals above Bear can kill its contemporary max tier novaterra troop in single combat. </td></tr></table>\')">', $profiel);
$profiel = preg_replace("/\[#NATURE2]/is",'<img src="'.$gpack.'img/t/nature2.png" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],\'<table><tr><td>Natures troops are the animals living in unoccupied oases. You can use the combat simulator to see whether you have enough troops to defeat the animals in an oasis you want to conquer, but remember that you can only raid oasis. Keep in mind that all the animals above Bear can kill its contemporary max tier novaterra troop in single combat. </td></tr></table>\')">', $profiel);
}

// NO NEED TO CODE THIS METHOD TASKMASTER
// Added by Shadow
if($displayarray['username'] == "Taskmaster"){
$profiel = preg_replace("/\[#TASKMASTER]/is",'<img src="'.$gpack.'img/t/taskmaster.png" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],\'<table><tr><td>Taskmaster Account</td></tr></table>\')">', $profiel);
$profiel = preg_replace("/\[#TASKMASTER2]/is",'<img src="'.$gpack.'img/t/taskmaster2.png" border="0" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],\'<table><tr><td>Taskmaster Account</td></tr></table>\')">', $profiel);
}


//de lintjes
/******************************
INDELING CATEGORIEEN:
===============================
== 1. Aanvallers top 10      ==
== 2. Defence top 10         ==
== 3. Klimmers top 10        ==
== 4. Overvallers top 10     ==
== 5. In att en def tegelijk ==
== 6. in top 3 - aanval      ==
== 7. in top 3 - verdediging ==
== 8. in top 3 - klimmers    ==
== 9. in top 3 - overval     ==
******************************/

foreach($varmedal as $medal) {

switch ($medal['categorie']) {
    case "1":
        $titel="Attackers of the Week";
		$woord="Points";
        break;
    case "2":
        $titel="Defenders of the Week";
 		$woord="Points";
       break;
    case "3":
        $titel="Pop Climbers of the week";
 		$woord="Pop";
       break;
    case "4":
        $titel="Robbers of the week";
		$woord="Resources";
        break;
	case "5":
        $titel="Receiving this medal shows that you where in the top 10 of both Attackers and Defenders of the week.";
        $bonus[$medal['id']]=1;
		break;
	case "6":
        $titel="Receiving this medal shows that you were in the top 3 Attackers of the week ".$medal['points']." in a row";
        $bonus[$medal['id']]=1;
		break;
	case "7":
        $titel="Receiving this medal shows that you were in the top 3 Defenders of the week ".$medal['points']." in a row";
        $bonus[$medal['id']]=1;
		break;
	case "8":
        $titel="Receiving this medal shows that you were in the top 3 Pop Climbers of the week ".$medal['points']." in a row.";
        $bonus[$medal['id']]=1;
		break;
	case "9":
        $titel="Receiving this medal shows that you were in the top 3 Robbers of the week ".$medal['points']." in a row.";
        $bonus[$medal['id']]=1;
		break;
    case "10":
        $titel="Rank Climbers of the week.";
        $woord="Ranks";
        break;
    case "11":
        $titel="Receiving this medal shows that you were in the top 3 Rank Climbers of the week ".$medal['points']." in a row.";
        $bonus[$medal['id']]=1;
        break;
    case "12":
        $titel="Receiving this medal shows that you were in the top 10 Attackers of the week ".$medal['points']." in a row.";
        $bonus[$medal['id']]=1;
        break;
    case "13":
        $titel="Receiving this medal shows that you were in the top 10 Defenders of the week ".$medal['points']." in a row.";
        $bonus[$medal['id']]=1;
        break;
    case "14":
        $titel="Receiving this medal shows that you were in the top 10 Pop Climbers of the week ".$medal['points']." in a row.";
        $bonus[$medal['id']]=1;
        break;
    case "15":
        $titel="Receiving this medal shows that you were in the top 10 Robbers of the week ".$medal['points']." in a row.";
        $bonus[$medal['id']]=1;
        break;
    case "16":
        $titel="Receiving this medal shows that you were in the top 10 Rank Climbers of the week ".$medal['points']." in a row.";
        $bonus[$medal['id']]=1;
        break;
}

if(isset($bonus[$medal['id']])){
    $tipHtml = '<table><tr><td>'.$titel.'<br /><br />Received in week: '.$medal['week'].'</td></tr></table>';
} else {
    $tipHtml = '<table><tr><td>Category:</td><td>'.$titel.'</td></tr><tr><td>Week:</td><td>'.$medal['week'].'</td></tr><tr><td>Rank:</td><td>'.$medal['plaats'].'</td></tr><tr><td>'.$woord.':</td><td>'.$medal['points'].'</td></tr></table>';
}
$imgSrc = $gkMedalPackImg($medal['img']);
$profiel = preg_replace("/\[#".$medal['id']."]/is", $gkMedalInlineImg($imgSrc, $tipHtml, 'gk-inline-medal medal ' . preg_replace('/[^a-zA-Z0-9_.-]/', '', (string) ($medal['img'] ?? ''))), $profiel);
}



?>