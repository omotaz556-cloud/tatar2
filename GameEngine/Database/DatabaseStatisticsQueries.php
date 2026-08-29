<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Project:       Novaterra                                                    ##
##  Filename:      DatabaseStatisticsQueries.php                               ##
##  Split&Refactor Shadow                                                      ##
##  Purpose:       Rankings, points, medals, statistics counters               ##
##                                                                             ##
##  Phase S1: Trait extracted from GameEngine/Database.php                     ##
##            (MYSQLi_DB class).                                               ##
##  Methods were moved IDENTICALLY, with no logic changes.                     ##
##                                                                             ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
##  URLs:          https://novaterra.example                                        ##
##                 https://github.com/omotaz556-cloud/tatar                     ##
#################################################################################

trait DatabaseStatisticsQueries {


	// no need to refactor this method
	function getProfileMedal($uid) {
	    list($uid) = $this->escape_input((int) $uid);

		$q = "SELECT id,categorie,plaats,week,img,points from " . TB_PREFIX . "medal where userid = $uid and del = 0 order by id desc";
		$result = mysqli_query($this->dblink,$q);
		return $this->mysqli_fetch_all($result);

	}

    // no need to refactor this method
	function getProfileMedalAlly($uid) {
	    list($uid) = $this->escape_input((int) $uid);

		$q = "SELECT id,categorie,plaats,week,img,points from " . TB_PREFIX . "allimedal where allyid = $uid and del = 0 order by id desc";
		$result = mysqli_query($this->dblink,$q);
		return $this->mysqli_fetch_all($result);

	}

	function modifyPoints($aid, $points, $amt) {
	    $aid = (int) $aid;

	    if (!is_array($points)) {
	        $points = [$points];
	        $amt    = [$amt];
        }

        $updates = [];
        foreach ($points as $index => $value) {
            $value = $this->escape($value);
	        $updates[] = $value.' = ' . $value . ' + ' . (int) $amt[$index];
        }

		$q = "UPDATE " . TB_PREFIX . "users SET ".implode(', ', $updates)." WHERE id = $aid";
		return mysqli_query($this->dblink,$q);
	}

	function modifyPointsAlly($aid, $points, $amt) {
        $aid = (int) $aid;

        if (!is_array($points)) {
            $points = [$points];
            $amt    = [$amt];
        }

        $updates = [];
        foreach ($points as $index => $value) {
            $value = $this->escape($value);
            $updates[] = $value.' = ' . $value . ' + ' . (int) $amt[$index];
        }

		$q = "UPDATE " . TB_PREFIX . "alidata SET ".implode(', ', $updates)." WHERE id = $aid";
		return mysqli_query($this->dblink,$q);
	}

    function isThereAWinner(){
    	$q = "SELECT Count(*) as Total FROM ".TB_PREFIX."fdata WHERE f99 = 100 and f99t = 40";
    	$result = mysqli_fetch_array(mysqli_query($this->dblink, $q), MYSQLI_ASSOC);
    	return $result['Total'] > 0;
    }

    // no need to cache this method
	function getVRanking() {
	    /**
	     * BUG REPARAT: lista de triburi era 1,2,3 (+5 pentru Natari), adica
	     * dinainte de adaugarea triburilor noi. Satele hunilor, egiptenilor,
	     * spartanilor si vikingilor (6-9) NU apareau deloc in clasament -
	     * jucatorii acelor triburi nu-si vedeau niciodata satele acolo.
	     *
	     * Aceeasi lista completa e folosita deja in winner.php.
	     */
	    $q = "SELECT v.wref,v.name,v.owner,v.pop,v.cp FROM " . TB_PREFIX . "vdata AS v," . TB_PREFIX . "users AS u WHERE v.owner=u.id AND u.tribe IN(1,2,3,6,7,8,9".(SHOW_NATARS ? ',5' : '').") AND v.wref != '' AND u.access<" . (INCLUDE_ADMIN ? "10" : "8");
		$result = mysqli_query($this->dblink,$q);
		return $this->mysqli_fetch_all($result);
	}

	function getARanking($use_cache = true) {
        // first of all, check if we should be using cache and whether the field
        // required is already cached
        if ($use_cache && ($cachedValue = self::returnCachedContent(self::$allianceRankingCache, 0)) && !is_null($cachedValue)) {
            return $cachedValue;
        }

		$q = "SELECT id,name,tag,oldrank,Aap,Adp FROM " . TB_PREFIX . "alidata where id != '' ORDER BY id DESC";
		$result = mysqli_query($this->dblink,$q);

        self::$allianceRankingCache[0] = $this->mysqli_fetch_all($result);
        return self::$allianceRankingCache[0];
	}

    // no need to cache this method
	function getUserByTribe($tribe) {
	    list($tribe) = $this->escape_input((int) $tribe);
		$q = "SELECT * FROM " . TB_PREFIX . "users where tribe = $tribe";
		$result = mysqli_query($this->dblink,$q);
		return $this->mysqli_fetch_all($result);
	}

    // no need to cache this method
	function getUserByAlliance($aid) {
	    list($aid) = $this->escape_input((int) $aid);
		$q = "SELECT * FROM " . TB_PREFIX . "users where alliance = $aid";
		$result = mysqli_query($this->dblink,$q);
		return $this->mysqli_fetch_all($result);
	}

    // no need to cache this method
	function getHeroRanking() {
		$q = "SELECT * FROM " . TB_PREFIX . "hero WHERE uid > 5";
		$result = mysqli_query($this->dblink,$q);
		return $this->mysqli_fetch_all($result);
	}

	//medal functions
	function addclimberrankpop($user, $cp) {
	    list($user, $cp) = $this->escape_input((int) $user, (int) $cp);

		$q = "UPDATE " . TB_PREFIX . "users set clp = clp + $cp where id = $user";
		return mysqli_query($this->dblink,$q);
	}

	function removeclimberrankpop($user, $cp) {
	    list($user, $cp) = $this->escape_input((int) $user, (int) $cp);

		$q = "UPDATE " . TB_PREFIX . "users set clp = clp - $cp where id = $user";
		return mysqli_query($this->dblink,$q);
	}

	function setclimberrankpop($user, $cp) {
	    list($user, $cp) = $this->escape_input((int) $user, (int) $cp);

		$q = "UPDATE " . TB_PREFIX . "users set clp = $cp where id = $user";
		return mysqli_query($this->dblink,$q);
	}

	function updateoldrank($user, $cp) {
	    list($user, $cp) = $this->escape_input((int) $user, (int) $cp);

		$q = "UPDATE " . TB_PREFIX . "users set oldrank = $cp where id = $user";
		return mysqli_query($this->dblink,$q);
	}

	// ALLIANCE MEDAL FUNCTIONS
	function addclimberrankpopAlly($user, $cp) {
	    list($user, $cp) = $this->escape_input((int) $user, (int) $cp);

		$q = "UPDATE " . TB_PREFIX . "alidata set clp = clp + $cp where id = $user";
		return mysqli_query($this->dblink,$q);
	}

	function removeclimberrankpopAlly($user, $cp) {
	    list($user, $cp) = $this->escape_input((int) $user, (int) $cp);

		$q = "UPDATE " . TB_PREFIX . "alidata set clp = clp - $cp where id = $user";
		return mysqli_query($this->dblink,$q);
	}

	function updateoldrankAlly($user, $cp) {
	    list($user, $cp) = $this->escape_input((int) $user, (int) $cp);

		$q = "UPDATE " . TB_PREFIX . "alidata set oldrank = $cp where id = $user";
		return mysqli_query($this->dblink,$q);
	}

	function countUser($use_cache = true) {
        // first of all, check if we should be using cache and whether the field
        // required is already cached
        if ($use_cache && ($cachedValue = self::returnCachedContent(self::$usersCountCache, 0)) && !is_null($cachedValue)) {
            return $cachedValue;
        }

		$q = "SELECT count(id) FROM " . TB_PREFIX . "users where id > 5";
		$result = mysqli_query($this->dblink,$q);
		$row = mysqli_fetch_row($result);

        self::$usersCountCache[0] = $row[0];
        return self::$usersCountCache[0];
	}

	function countAlli($use_cache = true) {
        // first of all, check if we should be using cache and whether the field
        // required is already cached
        if ($use_cache && ($cachedValue = self::returnCachedContent(self::$allianceCountCache, 0)) && !is_null($cachedValue)) {
            return $cachedValue;
        }

		$q = "SELECT count(id) FROM " . TB_PREFIX . "alidata where id != 0";
		$result = mysqli_query($this->dblink,$q);
		$row = mysqli_fetch_row($result);

        self::$allianceCountCache[0] = $row[0];
        return self::$allianceCountCache[0];
	}

	/**
	 * @param int $uid
	 * @return int How many villages this user currently owns. Deliberately
	 *             uncached (always a fresh COUNT) since it's used right
	 *             after a village INSERT to decide a one-time milestone.
	 */
	function countVillages($uid) {
	    list($uid) = $this->escape_input((int) $uid);

	    $q = "SELECT COUNT(*) AS total FROM " . TB_PREFIX . "vdata WHERE owner = $uid";
	    $result = mysqli_query($this->dblink, $q);
	    $row = mysqli_fetch_assoc($result);

	    return $row ? (int) $row['total'] : 0;
	}

	function ensureWorldNewsTable()
	{
	    static $ready = false;
	    if ($ready) {
	        return true;
	    }

	    $q = 'CREATE TABLE IF NOT EXISTS ' . TB_PREFIX . 'world_news (
	        id int(11) NOT NULL AUTO_INCREMENT,
	        attacker_uid int(11) NOT NULL,
	        attacker_name varchar(40) NOT NULL,
	        defender_wref int(11) NOT NULL,
	        defender_vname varchar(100) NOT NULL,
	        kills int(11) NOT NULL DEFAULT 0,
	        time int(11) NOT NULL,
	        PRIMARY KEY (id),
	        KEY time (time),
	        KEY kills (kills)
	    ) ENGINE=InnoDB DEFAULT CHARSET=utf8';

	    $ok = mysqli_query($this->dblink, $q);
	    if ($ok) {
	        $ready = true;
	    }
	    return (bool) $ok;
	}

	function addWorldNews($attackerUid, $attackerName, $defenderWref, $defenderVname, $kills, $time = 0)
	{
	    if (!$this->ensureWorldNewsTable()) {
	        return false;
	    }

	    list($attackerUid, $attackerName, $defenderWref, $defenderVname, $kills, $time) = $this->escape_input(
	        (int) $attackerUid,
	        (string) $attackerName,
	        (int) $defenderWref,
	        (string) $defenderVname,
	        (int) $kills,
	        (int) ($time > 0 ? $time : time())
	    );

	    if ($kills <= 0 || $attackerUid <= 0 || $defenderWref <= 0) {
	        return false;
	    }

	    $q = 'INSERT INTO ' . TB_PREFIX . 'world_news
	        (attacker_uid, attacker_name, defender_wref, defender_vname, kills, time)
	        VALUES (' . $attackerUid . ", '" . $attackerName . "', " . $defenderWref . ", '"
	        . $defenderVname . "', " . $kills . ', ' . $time . ')';
	    $ok = mysqli_query($this->dblink, $q);

	    if ($ok && random_int(1, 20) === 1) {
	        $this->pruneWorldNews();
	    }

	    return (bool) $ok;
	}

	function getWorldNews($limit = 50)
	{
	    if (!$this->ensureWorldNewsTable()) {
	        return [];
	    }

	    $limit = max(1, min(200, (int) $limit));
	    $q = 'SELECT id, attacker_uid, attacker_name, defender_wref, defender_vname, kills, time
	        FROM ' . TB_PREFIX . 'world_news
	        ORDER BY time DESC, id DESC
	        LIMIT ' . $limit;
	    $result = mysqli_query($this->dblink, $q);
	    if (!$result) {
	        return [];
	    }

	    return $this->mysqli_fetch_all($result);
	}

	function pruneWorldNews($keepDays = 30, $maxRows = 500)
	{
	    if (!$this->ensureWorldNewsTable()) {
	        return;
	    }

	    $keepDays = max(7, (int) $keepDays);
	    $maxRows = max(50, (int) $maxRows);
	    $cutoff = time() - ($keepDays * 86400);

	    mysqli_query(
	        $this->dblink,
	        'DELETE FROM ' . TB_PREFIX . 'world_news WHERE time < ' . (int) $cutoff
	    );

	    $countRes = mysqli_query($this->dblink, 'SELECT COUNT(*) AS total FROM ' . TB_PREFIX . 'world_news');
	    if (!$countRes) {
	        return;
	    }
	    $countRow = mysqli_fetch_assoc($countRes);
	    $total = (int) ($countRow['total'] ?? 0);
	    if ($total <= $maxRows) {
	        return;
	    }

	    $trim = $total - $maxRows;
	    mysqli_query(
	    $this->dblink,
	        'DELETE FROM ' . TB_PREFIX . 'world_news ORDER BY time ASC, id ASC LIMIT ' . (int) $trim
	    );
	}

	/**
	 * Greek.sa "لم يُهزموا في الدفاع" ranking table.
	 * Tracks each player's undefeated-defense streak start timestamp.
	 */
	function ensureUndefeatedDefTable()
	{
	    static $ready = false;
	    if ($ready) {
	        return true;
	    }

	    $q = 'CREATE TABLE IF NOT EXISTS ' . TB_PREFIX . 'undefeated_def (
	        uid int(11) NOT NULL,
	        since int(11) NOT NULL,
	        PRIMARY KEY (uid),
	        KEY since (since)
	    ) ENGINE=InnoDB DEFAULT CHARSET=utf8';

	    $ok = mysqli_query($this->dblink, $q);
	    if ($ok) {
	        $ready = true;
	    }
	    return (bool) $ok;
	}

	/**
	 * Ensure every eligible player has a streak row (defaults to regtime / COMMENCE).
	 */
	function seedUndefeatedDefRows()
	{
	    if (!$this->ensureUndefeatedDefTable()) {
	        return;
	    }

	    $accessMax = defined('INCLUDE_ADMIN') && INCLUDE_ADMIN ? 10 : 8;
	    $commence = defined('COMMENCE') ? (int) COMMENCE : 0;
	    $q = 'INSERT IGNORE INTO ' . TB_PREFIX . 'undefeated_def (uid, since)
	        SELECT u.id,
	            GREATEST(COALESCE(NULLIF(u.regtime, 0), ' . $commence . '), ' . $commence . ')
	        FROM ' . TB_PREFIX . 'users u
	        WHERE u.id > 5
	          AND u.access < ' . (int) $accessMax . '
	          AND u.tribe IN (1,2,3,6,7,8,9)';
	    mysqli_query($this->dblink, $q);
	}

	/**
	 * Reset a defender's undefeated streak (called when they lose troops in defense).
	 */
	function breakUndefeatedDefense($uid, $when = 0)
	{
	    $uid = (int) $uid;
	    if ($uid <= 5) {
	        return false;
	    }
	    if (!$this->ensureUndefeatedDefTable()) {
	        return false;
	    }

	    $when = (int) ($when > 0 ? $when : time());
	    $q = 'INSERT INTO ' . TB_PREFIX . 'undefeated_def (uid, since) VALUES ('
	        . $uid . ', ' . $when . ')
	        ON DUPLICATE KEY UPDATE since = ' . $when;
	    return (bool) mysqli_query($this->dblink, $q);
	}

	/**
	 * Ranking rows for undefeated defense list (longest / highest points first).
	 * Points = floor(hours_undefeated) * 5. Daily gold reward = 1000 while on list.
	 *
	 * @return array
	 */
	function getUndefeatedDefRanking()
	{
	    if (!$this->ensureUndefeatedDefTable()) {
	        return [];
	    }

	    $this->seedUndefeatedDefRows();

	    $accessMax = defined('INCLUDE_ADMIN') && INCLUDE_ADMIN ? 10 : 8;
	    $now = time();
	    $q = 'SELECT
	            u.id AS userid,
	            u.username,
	            u.alliance,
	            a.tag AS allitag,
	            d.since,
	            FLOOR(GREATEST(0, (' . (int) $now . ' - d.since)) / 3600) * 5 AS points,
	            cap.wref AS capital
	        FROM ' . TB_PREFIX . 'undefeated_def d
	        INNER JOIN ' . TB_PREFIX . 'users u ON u.id = d.uid
	        LEFT JOIN ' . TB_PREFIX . 'alidata a ON a.id = u.alliance
	        LEFT JOIN ' . TB_PREFIX . 'vdata cap ON cap.owner = u.id AND cap.capital = 1
	        WHERE u.id > 5
	          AND u.access < ' . (int) $accessMax . '
	          AND u.tribe IN (1,2,3,6,7,8,9)
	        ORDER BY points DESC, d.since ASC, u.id ASC';

	    $result = mysqli_query($this->dblink, $q);
	    if (!$result) {
	        return [];
	    }

	    return $this->mysqli_fetch_all($result);
	}
}
