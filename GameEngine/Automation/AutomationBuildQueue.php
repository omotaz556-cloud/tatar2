<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Project:       Novaterra                                                    ##
##  Filename:      AutomationBuildQueue.php                                    ##
##  Split&Refactor Shadow													   ##
##  Purpose:       Building completion, WW, demolitions, research              ##
##                                                                             ##
##  Phase S2: Trait extracted from GameEngine/Automation.php                   ##
##            (Automation class).                                              ##
##  Methods were moved IDENTICALLY, with no logic changes.                     ##
##                                                                             ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
##  URLs:          https://novaterra.example                                        ##
##                 https://github.com/omotaz556-cloud/tatar                     ##
#################################################################################

trait AutomationBuildQueue {


    private function buildComplete() {
        global $database;

        $time = time();
        // IDs of villages that were affected by this building completion update,
        // used to calculate statistical data at the end
        $villagesAffected = [];
        // holds additional conditions when updating loopcon records in the bdata table
        $loopconUpdates = [];
        // this will hold IDs of bdata table records to delete
        $dbIdsToDelete = [];

        // get all pending builds that should be complete by now
        $res = $database->query_return(
            "SELECT
                id, wid, field, level, type, timestamp
             FROM
                ".TB_PREFIX."bdata
            WHERE
                timestamp < $time and master = 0"
        );

        // preload village data
        $vilIDs = [];
        foreach($res as $indi) {
            $vilIDs[$indi['wid']] = true;
        }
        $vilIDs = array_keys($vilIDs);
        $database->getProfileVillages($vilIDs, 5);
        $database->getEnforceVillage($vilIDs, 0);

        // complete buildings
        foreach($res as $indi) {
            // store village ID for later for statistical updates
            $villageData = $database->getVillageFields($indi['wid'],'owner, maxcrop, maxstore, starv, pop');
            $villageOwner = $villageData['owner'];
            $villagesAffected[] = (int) $indi['wid'];
            $fieldsToSet = [];
            
            $q = "UPDATE ".TB_PREFIX."fdata SET f".$indi['field']." = ".$indi['level'].", f".$indi['field']."t = ".$indi['type']." WHERE vref = ".(int) $indi['wid'];

            if($database->query($q)) {
                // this will be the level we brought the building to now
                $level = $indi['level'];

                // TODO: magic numbers into constants (for building types below)

                // update capacity if we updated a warehouse or a granary
                if (in_array($indi['type'], [10, 11, 38, 39])) {
                    [$fieldDbName, $max] = $this->updateStorageCapacity($indi['type'], $level, $villageData);
                    $fieldsToSet[$fieldDbName] = $max;
                }

                // if we updated Embassy, update maximum members that the alliance can take
                if($indi['type'] == 18) Automation::updateMax($villageOwner);

                // World Wonder completion handling (Natar attacks, winner lock, last-upgrade time)
                if ($indi['type'] == 40) $this->completeWorldWonder($indi);

                // TODO: find out what exactly these conditions are for
                // no special military conditioning for Teutons and Gauls
                if ($database->getUserField($villageOwner, "tribe", 0) != 1) $loopconUpdates[$indi['wid']] = '';                 
                else
                {
                    // special condition for Roman military buildings
                    if ($indi['field'] > 18) $loopconUpdates[$indi['wid']] = ' AND field > 18';                    
                    else $loopconUpdates[$indi['wid']] = ' AND field < 19';                                      
                }

                $dbIdsToDelete[] = (int) $indi['id'];
            }

            //Update starvation data
            $database->addStarvationData($indi['wid']);

            // update the requested fields, all at once
            $database->setVillageFields($indi['wid'], array_keys($fieldsToSet), array_values($fieldsToSet));
        }

        // update statistical data for affected villages
        foreach ($villagesAffected as $affected_id) $this->recountPop($affected_id, false);

        // update data that can be done in one swoop instead of using multiple update queries
        // no special checks for Romans
        foreach ($loopconUpdates as $villageId => $updateCondition) {
            $database->query(
                "UPDATE
                    ".TB_PREFIX."bdata
                 SET
                    loopcon = 0
                 WHERE
                    loopcon = 1 AND
                    master = 0 AND
                    wid = ".$villageId.$updateCondition);
        }

        // delete all processed entries
        if (count($dbIdsToDelete)) {
            $database->query( "DELETE FROM " . TB_PREFIX . "bdata WHERE id IN(" . implode( ',', $dbIdsToDelete ) . ")" );
        }
    }

    /**
     * Handle the side effects of completing a World Wonder (type 40) build:
     * launch the Natar attack waves, lock out further winners at level 100,
     * and record the last upgrade time.
     */
    private function completeWorldWonder($indi) {
        global $database;

        // Natarii NU se pot ataca pe ei insisi.
        //
        // De cand capitala Natarilor are propria Minune, acest bloc s-ar
        // declansa si pentru ea: la fiecare 5 niveluri ar porni valuri de atac
        // avand ca sursa capitala si ca tinta tot capitala.
        $wwOwner = (int) $database->getVillageField((int) $indi['wid'], 'owner');
        $natarUid = class_exists('Artifacts') ? (int) Artifacts::NATARS_UID : 3;

        if ($wwOwner !== $natarUid
            && ($indi['level'] % 5 == 0 || $indi['level'] > 95) && $indi['level'] != 100) {
            $this->startNatarAttack($indi['level'], $indi['wid'], $indi['timestamp']);
        }

        //now can't be more than one winner if ww to level 100 is build by 2 users or more on same time
        if ($indi['level'] == 100) {
            $this->awardWorldWonderRewards((int) $indi['wid'], $wwOwner);
            $this->broadcastWorldWonderVictoryReport((int) $indi['wid'], $wwOwner);
            mysqli_query($database->dblink,"TRUNCATE ".TB_PREFIX."bdata");
        }

        // Update ww last finish upgrade
        $qW = "UPDATE ".TB_PREFIX."fdata set ww_lastupdate = ".time()." where vref = ".(int) $indi['wid'];
        $database->query($qW);
    }

    /**
     * Award the one-time World Wonder completion rewards.
     *
     * A member qualifies for the alliance reward after 14 days in the
     * alliance or when they contributed an active World Wonder plan.
     */
    private function awardWorldWonderRewards($wid, $ownerId) {
        global $database;

        $ownerId = (int) $ownerId;
        $wid = (int) $wid;
        if ($ownerId <= 5 || $wid <= 0) {
            return;
        }

        $ownerAlliance = (int) $database->getUserField($ownerId, 'alliance', 0);
        if ($ownerAlliance <= 0) {
            return;
        }

        $members = $database->getAllMember($ownerAlliance, 0, false);
        if (!$members) {
            return;
        }

        $cutoff = time() - (14 * 86400);
        $eligibleMembers = [];
        foreach ($members as $member) {
            $memberId = (int) ($member['id'] ?? 0);
            $joinedAt = (int) ($member['alliance_joined'] ?? 0);
            $hasPlan = $database->getWWConstructionPlans($memberId);

            if (($joinedAt > 0 && $joinedAt <= $cutoff) || $hasPlan) {
                $eligibleMembers[] = $memberId;
            }
        }

        // Do not pay a newly assembled alliance with no established member
        // and no actual World Wonder-plan contribution.
        if (!$eligibleMembers) {
            return;
        }

        // milestones.milestone_key is unique, making this one-time guard
        // race-safe when two cron workers finish level 100 together.
        if (!$database->recordMilestoneIfFirst('world_wonder_rewards', $ownerId, $wid, (string) $ownerAlliance)) {
            return;
        }

        $alliance = $database->getAlliance($ownerAlliance, false);
        $leaderId = (int) ($alliance['leader'] ?? 0);
        $ownerPrize = defined('WW_WINNER_GOLD_PRIZE') ? (int) WW_WINNER_GOLD_PRIZE : 50000;
        if ($ownerPrize < 0) {
            $ownerPrize = 0;
        }
        $this->grantWorldWonderGold($wid, $ownerId, $ownerPrize, 'World Wonder owner reward');
        if ($leaderId > 5) {
            $this->grantWorldWonderGold($wid, $leaderId, 30000, 'World Wonder alliance leader reward');
        }
        foreach ($eligibleMembers as $memberId) {
            $this->grantWorldWonderGold($wid, $memberId, 10000, 'World Wonder alliance member reward');
        }
    }

    /**
     * Deliver the Arabic victory report to every player inbox (once).
     */
    private function broadcastWorldWonderVictoryReport($wid, $ownerId) {
        global $database;

        require_once dirname(__DIR__) . '/WinnerReport.php';

        // Ensure Arabic/report strings exist when Automation runs without a web session.
        if (!defined('WINNER_RPT_DEAR')) {
            $langFile = dirname(__DIR__) . '/Lang/loader.php';
            if (is_file($langFile)) {
                require_once $langFile;
                if (function_exists('tz_load_language')) {
                    $lang = defined('LANG') ? LANG : (defined('SERVER_LANG') ? SERVER_LANG : 'ar');
                    tz_load_language($lang);
                }
            }
        }

        tz_winner_report_broadcast_all($database, (int) $wid, (int) $ownerId);
    }

    private function grantWorldWonderGold($wid, $uid, $amount, $action) {
        global $database;

        if ($database->modifyGold((int) $uid, (int) $amount, 1)
            && defined('LOG_GOLD_FIN') && LOG_GOLD_FIN) {
            $database->addGoldFinLog((int) $wid, (int) $uid, $action, (int) $amount, 'World Wonder completed');
        }
    }

    private function researchComplete() {
        global $database;

        $time = time();
        $deleteIDs = [];
        $tdata = [];
        $abdata = [];

        $q = "SELECT tech, vref, id FROM ".TB_PREFIX."research where timestamp < $time";
        $dataarray = $database->query_return($q);

        foreach($dataarray as $data) {
            $sort_type = substr($data['tech'],0,1);
            switch($sort_type) {
                case "t":
                    if (!isset($tdata[$data['vref']])) $tdata[$data['vref']] = [];
                    $tdata[$data['vref']][] = $data['tech'].' = 1';
                    break;
                case "a":
                case "b":
                    if (!isset($abdata[$data['vref']])) $abdata[$data['vref']] = [];
                    $abdata[$data['vref']][] = $data['tech']." = ".$data['tech']." + 1";
                    break;
            }
            $deleteIDs[] = (int) $data['id'];
        }

        // execute queries with consolidated research data
        if (count($tdata)) {
            foreach ( $tdata as $vid => $preparedData ) {
                $q = "UPDATE ".TB_PREFIX."tdata SET ".implode(', ', $preparedData)." WHERE vref = ".$vid;
                $database->query($q);
            }
        }

        if (count($abdata)) {
            foreach ( $abdata as $vid => $preparedData ) {
                $q = "UPDATE ".TB_PREFIX."abdata SET ".implode(', ', $preparedData)." WHERE vref = ".$vid;
                $database->query($q);
            }
        }

        if (count($deleteIDs)) {
            $q = "DELETE FROM " . TB_PREFIX . "research where id IN(" . implode( ', ', $deleteIDs ) . ")";
            $database->query( $q );
        }
    }

    private function demolitionComplete() {
        global $database;

        $varray = $database->getDemolition();
        foreach($varray as $vil) {
			if ($vil['lvl'] < 0) {
		$database->delDemolition($vil['vref'], true);
		continue;
		}
            if ($vil['timetofinish'] <= time()) {
                $type = $database->getFieldType($vil['vref'],$vil['buildnumber']);
                $level = $database->getFieldLevel($vil['vref'],$vil['buildnumber']);

                $newLevel = max(0, $level - 1);

                $buildarray = $GLOBALS["bid".$type];

                // FIX: capacitatea de depozitare se RECALCULEAZA din cladirile
                // ramase, dupa ce nivelul nou e scris mai jos. Scaderea de
                // dinainte lua `attri` al nivelului, dar `attri` e capacitatea
                // TOTALA la acel nivel (1200, 1700, 2300...), nu incrementul -
                // deci taia prea mult. Ignora si STORAGE_MULTIPLIER. Efectul era
                // ascuns de pragul STORAGE_BASE si "reparat" abia la urmatoarea
                // rulare a lui updateStore().
                $needsStorageRecalc = in_array($type, [10, 11, 38, 39]);

                if ($level == 1) $clear = ",f".$vil['buildnumber']."t=0";
                else $clear = "";

                if ($database->getVillageField($vil['vref'], 'natar') == 1 && $type == 40) $clear = ""; //fix by ronix - fixed by iopietro
				$q = "UPDATE ".TB_PREFIX."fdata SET f".$vil['buildnumber']."=".$newLevel." ".$clear." WHERE vref=".(int)$vil['vref'];
                $database->query($q);

                if ($needsStorageRecalc) {
                    $database->recalculateStorage($vil['vref']);
                }

                $pop = $this->getPop($type, $newLevel);
                $database->modifyPop($vil['vref'], $pop[0], 1);
                $this->procClimbers($database->getVillageField($vil['vref'], 'owner'));
                $database->delDemolition($vil['vref'], true);

                if ($type == 18) Automation::updateMax($database->getVillageField($vil['vref'], 'owner'));
            }
        }

    }

    private function MasterBuilder() {
        global $database;
        
        $q = "SELECT id, wid, type, level, field, timestamp FROM ".TB_PREFIX."bdata WHERE master = 1";
        $array = $database->query_return($q);

        foreach($array as $master) {      
            $owner = $database->getVillageField($master['wid'], 'owner');
            $tribe = $database->getUserField($owner, 'tribe', 0);
            $villwood = $database->getVillageField($master['wid'], 'wood');
            $villclay = $database->getVillageField($master['wid'], 'clay');
            $villiron = $database->getVillageField($master['wid'], 'iron');
            $villcrop = $database->getVillageField($master['wid'], 'crop');
            $type = $master['type'];
            $level = $master['level'];
            $buildarray = $GLOBALS["bid".$type];
            $buildwood = $buildarray[$level]['wood'];
            $buildclay = $buildarray[$level]['clay'];
            $buildiron = $buildarray[$level]['iron'];
            $buildcrop = $buildarray[$level]['crop'];
            $ww = count($database->getBuildingByType($master['wid'], 40));

            if($tribe == 1){
                if($master['field'] < 19){
                    $bdata = $database->getDorf1Building($master['wid']);
                    $bdataTotal = count($bdata);
                    $bbdata = count($database->getDorf2Building($master['wid']));
                }else{
                    $bdata = $database->getDorf2Building($master['wid']);
                    $bdataTotal = count($bdata);
                    $bbdata = count($database->getDorf1Building($master['wid']));
                }
            }else{
                $bdata = array_merge($database->getDorf1Building($master['wid']), $database->getDorf2Building($master['wid']));
                $bdataTotal = $bbdata = count($bdata);          
            }

            if($database->getUserField($owner, 'plus', 0) > time() || $ww > 0){
                if($bbdata < 2) $inbuild = 2;                
                else $inbuild = 1;
            }
            else $inbuild = 1;

            $usergold = $database->getUserField($owner, 'gold', 0);

            if($bdataTotal < $inbuild && $buildwood <= $villwood && $buildclay <= $villclay && $buildiron <= $villiron && $buildcrop <= $villcrop && $usergold > 0){
                $time = $master['timestamp'] + time();

                if(!empty($bdata)){
                    foreach($bdata as $masterLoop) $time += ($masterLoop['timestamp'] - time());
                }

                if($bdataTotal == 0) $database->updateBuildingWithMaster($master['id'], $time, 0);                  
                else $database->updateBuildingWithMaster($master['id'], $time, 1);             

                $database->updateUserField($owner, 'gold', --$usergold, 1);
                $database->modifyResource($master['wid'], $buildwood, $buildclay, $buildiron, $buildcrop, 0);
            }
        }
    }
}
