<?php

#################################################################################
##                -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-               ##
## --------------------------------------------------------------------------- ##
##  Filename       : 8.tpl                                                     ##
##  Type           : Plus - Activate Plus Account                              ##
## --------------------------------------------------------------------------- ##
##  Developed by   : alq0rsan & evader & Shadow                                ##
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

// PERMISIUNI SITTER: cumparaturile cu aur.
// Inlocuieste vechiul "$session->sit", care citea un flag din tabela online
// scris cu INSERT IGNORE - deci nu se actualiza daca randul exista deja.
// sitterCan() se bazeaza pe sesiune, deci e mereu corect.
if($session->sitterCan(SITTER_PERM_GOLD)) {
    $now  = time();
    $uid  = (int)$session->uid;
    $wid  = (int)$village->wid;
    $cost = 10;

    // 1. scade gold și prelungește plus-ul ATOMIC
    $sql = "UPDATE ".TB_PREFIX."users 
            SET gold = gold - $cost,
                plus = IF(plus > $now, plus + ".PLUS_TIME.", $now + ".PLUS_TIME.")
            WHERE id = $uid AND gold >= $cost";

    mysqli_query($database->dblink, $sql);

    if(mysqli_affected_rows($database->dblink) == 1) {
        // 2. update sesiune
        $session->gold -= $cost;
        $_SESSION['gold'] = $session->gold;
        $session->plus = ($session->plus > $now ? $session->plus : $now) + PLUS_TIME;

        // 3. LOG pentru a2b2.php
        $vname = mysqli_real_escape_string($database->dblink, $village->vname);
		mysqli_query($database->dblink,
		"INSERT INTO ".TB_PREFIX."gold_fin_log 
		(uid, wid, action, gold, time, details) 
		VALUES ($uid, $wid, 'Buy Novaterra Plus', -$cost, $now, 'Buy Novaterra Plus')"
		);

        // 4. curăță cache
        if(method_exists($database, 'clearUserCache')) {
            $database->clearUserCache($uid);
        }
    }
}
header("Location: plus.php?id=3");
exit;
?>