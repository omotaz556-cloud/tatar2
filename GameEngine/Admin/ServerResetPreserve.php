<?php

/**
 * Preserve administrator accounts across server/world reset operations.
 * Ensures admins can still log in (with a starter village) after a wipe.
 */

if (!function_exists('tz_admin_access_threshold')) {
    function tz_admin_access_threshold(): int
    {
        return defined('ADMIN') ? (int) ADMIN : 9;
    }
}

if (!function_exists('tz_sql_exclude_admin_accounts')) {
    /** SQL fragment: exclude full admins from mass player maintenance queries. */
    function tz_sql_exclude_admin_accounts(string $alias = ''): string
    {
        $col = ($alias !== '' ? $alias . '.' : '') . 'access';
        return $col . ' < ' . tz_admin_access_threshold();
    }
}

if (!function_exists('tz_collect_preserved_admin_users')) {
    /**
     * @return list<array<string,mixed>>
     */
    function tz_collect_preserved_admin_users($dbLink): array
    {
        $threshold = tz_admin_access_threshold();
        $systemIds = '1,2,4,5';
        $rows = [];
        $sql = 'SELECT * FROM `' . TB_PREFIX . 'users`'
            . ' WHERE `access` >= ' . $threshold
            . ' AND `id` NOT IN (' . $systemIds . ')'
            . ' ORDER BY `id` ASC';
        $result = mysqli_query($dbLink, $sql);
        while ($result && ($row = mysqli_fetch_assoc($result))) {
            $rows[] = $row;
        }
        return $rows;
    }
}

if (!function_exists('tz_create_starter_village_for_user')) {
    function tz_create_starter_village_for_user($database, $admDB, int $uid, string $username): int
    {
        $uid = (int) $uid;
        $username = trim($username);
        $xcoor = (int) round(WORLD_MAX / 2);
        $ycoor = (int) round(WORLD_MAX / 2);
        $attempts = 0;
        $maxAttempts = (WORLD_MAX * 2) + 10;

        while ($attempts++ < $maxAttempts) {
            $wid = (int) $admDB->getWref($xcoor, $ycoor);
            if ((int) $database->getVillageState($wid) === 0) {
                $database->setFieldTaken($wid);
                $database->addVillage($wid, $uid, $username, 1);
                // Balanced 4-4-4-6 layout (same as install admin village).
                $database->addResourceFields($wid, 3);
                $database->addUnits($wid);
                $database->addTech($wid);
                $database->addABTech($wid);
                $database->query(
                    'UPDATE `' . TB_PREFIX . 'users` SET `village_select` = ' . $wid
                    . ' WHERE `id` = ' . $uid
                );
                return $wid;
            }
            $xcoor++;
            if ($xcoor >= WORLD_MAX) {
                $xcoor = (int) round(WORLD_MAX / 2);
                $ycoor++;
            }
        }

        return 0;
    }
}

if (!function_exists('tz_restore_preserved_admin_users')) {
    /**
     * Re-insert saved admin rows (new ids from 6 upward) and grant each a capital.
     *
     * @param list<array<string,mixed>> $adminRows
     * @return array<int,int> old user id => new user id
     */
    function tz_restore_preserved_admin_users($database, array $adminRows): array
    {
        if (!$adminRows) {
            return [];
        }

        require_once __DIR__ . '/database.php';
        $admDB = new adm_DB();
        $link = $database->return_link();
        $idMap = [];
        $nextId = 6;
        $now = time();
        $protection = $now + (defined('PROTECTION') ? (int) PROTECTION : 86400);

        foreach ($adminRows as $row) {
            $oldId = (int) ($row['id'] ?? 0);
            $newId = $nextId++;
            $username = (string) ($row['username'] ?? '');
            if ($username === '') {
                continue;
            }

            $fields = [
                'id' => $newId,
                'username' => $username,
                'password' => (string) ($row['password'] ?? ''),
                'email' => (string) ($row['email'] ?? ''),
                'lang' => (string) ($row['lang'] ?? (defined('LANG') ? LANG : 'en')),
                'tribe' => (int) ($row['tribe'] ?? 1),
                'access' => max(tz_admin_access_threshold(), (int) ($row['access'] ?? tz_admin_access_threshold())),
                'gold' => (int) ($row['gold'] ?? 0),
                'temporary_gold' => (int) ($row['temporary_gold'] ?? 0),
                'plus' => (int) ($row['plus'] ?? 0),
                'goldclub' => (int) ($row['goldclub'] ?? 0),
                'b1' => (int) ($row['b1'] ?? 0),
                'b2' => (int) ($row['b2'] ?? 0),
                'b3' => (int) ($row['b3'] ?? 0),
                'b4' => (int) ($row['b4'] ?? 0),
                'gpack' => (string) ($row['gpack'] ?? '/gpack/novaterra_classic/'),
                'desc1' => (string) ($row['desc1'] ?? ''),
                'desc2' => (string) ($row['desc2'] ?? ''),
                'is_bcrypt' => (int) ($row['is_bcrypt'] ?? 1),
                'gender' => (int) ($row['gender'] ?? 0),
                'birthday' => (string) ($row['birthday'] ?? '1970-01-01'),
                'location' => (string) ($row['location'] ?? ''),
                'quest' => (int) ($row['quest'] ?? 0),
                'protect' => $protection,
                'regtime' => (int) ($row['regtime'] ?? $now),
                'timestamp' => $now,
                'alliance' => 0,
                'sessid' => '',
                'ap' => 0,
                'apall' => 0,
                'dp' => 0,
                'dpall' => 0,
                'RR' => 0,
                'Rc' => 0,
                'ok' => 0,
                'cp' => 1,
                'village_select' => 0,
            ];

            $columns = [];
            $values = [];
            foreach ($fields as $column => $value) {
                $columns[] = '`' . $column . '`';
                if (is_int($value)) {
                    $values[] = (string) $value;
                } else {
                    $values[] = "'" . mysqli_real_escape_string($link, (string) $value) . "'";
                }
            }

            $insertSql = 'INSERT INTO `' . TB_PREFIX . 'users` (' . implode(', ', $columns) . ')'
                . ' VALUES (' . implode(', ', $values) . ')';
            if (!mysqli_query($link, $insertSql)) {
                continue;
            }

            tz_create_starter_village_for_user($database, $admDB, $newId, $username);
            if ($oldId > 0) {
                $idMap[$oldId] = $newId;
            }
        }

        if ($idMap) {
            $maxId = max(array_values($idMap));
            mysqli_query($link, 'ALTER TABLE `' . TB_PREFIX . 'users` AUTO_INCREMENT = ' . ($maxId + 1));
        }

        return $idMap;
    }
}
