<?php

#################################################################################
##                -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-               ##
##  Filename       : 14.tpl                                                    ##
##  Type           : Plus - Finish troop training immediately (35 gold)        ##
#################################################################################

$cost = 35;
$uid  = (int) $session->uid;
$wid  = (int) $village->wid;
$now  = time();

if (!$session->sitterCan(SITTER_PERM_GOLD)) {
    header('Location: plus.php?id=3');
    exit;
}

$training = $database->getTraining($wid);
if (empty($training)) {
    header('Location: plus.php?id=3');
    exit;
}

if (!$database->spendGold($uid, $cost, 'Finish troop training')) {
    header('Location: plus.php?id=3');
    exit;
}

$locked = $database->getTrainingLock($wid);
if ($locked) {
    $database->speedUpTraining($wid);
    $database->completeTraining($wid);
    $database->releaseTrainingLock($wid);
} else {
    $database->speedUpTraining($wid);
    $database->completeTraining($wid);
}

$database->addGoldFinLog(
    $wid,
    $uid,
    'Finish troop training',
    -$cost,
    'Finish troop training with gold'
);

$session->gold -= $cost;
$_SESSION['gold'] = $session->gold;
unset($_SESSION['cache_user_' . (isset($_SESSION['username']) ? $_SESSION['username'] : '')]);

if (method_exists($database, 'clearUserCache')) {
    $database->clearUserCache($uid);
}

header('Location: plus.php?id=3');
exit;
