<?php
/**
 * Portal world registry for the homepage login/signup overlays.
 *
 * Each entry is an independent game world (own TB_PREFIX / optional external URL).
 * Enable/disable and edit from Admin → Portal Worlds.
 */

if (class_exists('PortalWorlds', false)) {
    return;
}

class PortalWorlds
{
    public const COOKIE = 'tz_portal_world';

    /** @var array|null */
    private static $cache = null;

    public static function storagePath(): string
    {
        return dirname(__DIR__) . '/var/portal_worlds.json';
    }

    /**
     * Default worlds matching the classic multi-world picker layout.
     *
     * @return list<array<string,mixed>>
     */
    public static function defaults(): array
    {
        $now = time();
        $mainPrefix = defined('TB_PREFIX') ? (string) TB_PREFIX : 's1_';

        return [
            self::normalize([
                'id' => 's1',
                'number' => 1,
                'name' => 'Server 1',
                'enabled' => true,
                'local' => true,
                'badge' => 'limited_gold',
                'image' => 'img/en/welten/en1_big.jpg',
                'image_grey' => 'img/en/welten/en1_big_g.jpg',
                'start_time' => $now - (14 * 86400),
                'tb_prefix' => $mainPrefix,
                'provisioned' => true,
                'players' => 0,
                'online' => 0,
                'sort' => 10,
            ]),
            self::normalize([
                'id' => 's3',
                'number' => 3,
                'name' => 'Server 3',
                'enabled' => true,
                'local' => false,
                'badge' => 'limited_gold',
                'image' => 'img/en/welten/en3_big.jpg',
                'image_grey' => 'img/en/welten/en3_big_g.jpg',
                'start_time' => $now - (7 * 86400),
                'tb_prefix' => 'pw3_',
                'provisioned' => false,
                'sort' => 20,
            ]),
            self::normalize([
                'id' => 's5',
                'number' => 5,
                'name' => 'Server 5',
                'enabled' => true,
                'local' => false,
                'badge' => '',
                'image' => 'img/en/welten/en5_big.jpg',
                'image_grey' => 'img/en/welten/en5_big_g.jpg',
                'start_time' => $now + (2 * 86400),
                'tb_prefix' => 'pw5_',
                'provisioned' => false,
                'sort' => 30,
            ]),
            self::normalize([
                'id' => 's7',
                'number' => 7,
                'name' => 'Server 7',
                'enabled' => true,
                'local' => false,
                'badge' => 'newest',
                'image' => 'img/en/welten/en7_big.jpg',
                'image_grey' => 'img/en/welten/en7_big_g.jpg',
                'start_time' => $now - (3 * 86400),
                'tb_prefix' => 'pw7_',
                'provisioned' => false,
                'sort' => 40,
            ]),
            self::normalize([
                'id' => 's9',
                'number' => 9,
                'name' => 'Server 9',
                'enabled' => true,
                'local' => false,
                'badge' => 'permanent',
                'image' => 'img/en/welten/en9_big.jpg',
                'image_grey' => 'img/en/welten/en9_big_g.jpg',
                'start_time' => $now - (90 * 86400),
                'tb_prefix' => 'pw9_',
                'provisioned' => false,
                'sort' => 50,
            ]),
            self::normalize([
                'id' => 's10',
                'number' => 10,
                'name' => 'Server 10',
                'enabled' => true,
                'local' => false,
                'badge' => '',
                'image' => 'img/en/welten/en10_big.jpg',
                'image_grey' => 'img/en/welten/en10_big_g.jpg',
                'start_time' => $now - (5 * 86400),
                'tb_prefix' => 'pw10_',
                'provisioned' => false,
                'sort' => 60,
            ]),
        ];
    }

    /**
     * @return list<array<string,mixed>>
     */
    public static function all(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $path = self::storagePath();
        if (!is_file($path)) {
            self::$cache = self::defaults();
            self::save(self::$cache);
            return self::$cache;
        }

        $raw = @file_get_contents($path);
        $data = is_string($raw) ? json_decode($raw, true) : null;
        if (!is_array($data) || empty($data['worlds']) || !is_array($data['worlds'])) {
            self::$cache = self::defaults();
            return self::$cache;
        }

        $worlds = [];
        foreach ($data['worlds'] as $row) {
            if (is_array($row)) {
                $worlds[] = self::normalize($row);
            }
        }

        if (!$worlds) {
            self::$cache = self::defaults();
            return self::$cache;
        }

        usort($worlds, static function ($a, $b) {
            $sa = (int) ($a['sort'] ?? 0);
            $sb = (int) ($b['sort'] ?? 0);
            if ($sa === $sb) {
                return ((int) $a['number']) <=> ((int) $b['number']);
            }
            return $sa <=> $sb;
        });

        self::$cache = $worlds;
        return self::$cache;
    }

    /** @return list<array<string,mixed>> */
    public static function enabled(): array
    {
        $out = [];
        foreach (self::all() as $world) {
            if (!empty($world['enabled'])) {
                $out[] = $world;
            }
        }
        return $out;
    }

    public static function findById(string $id): ?array
    {
        foreach (self::all() as $world) {
            if ((string) $world['id'] === $id) {
                return $world;
            }
        }
        return null;
    }

    /**
     * World selected via cookie (before DB connect / TB_PREFIX define).
     */
    public static function selectedFromCookie(): ?array
    {
        $id = isset($_COOKIE[self::COOKIE])
            ? preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $_COOKIE[self::COOKIE])
            : '';
        if ($id === '') {
            return null;
        }
        $world = self::findById($id);
        if (!$world || empty($world['enabled'])) {
            return null;
        }
        return $world;
    }

    /**
     * Prefix override for config.php (null = keep default TB_PREFIX).
     * Not applied on the public homepage so the picker always uses the main DB for local stats.
     * Also skipped when the world tables do not exist yet (avoids fatal 500s).
     */
    public static function bootstrapPrefixOverride(): ?string
    {
        $script = strtolower(basename((string) ($_SERVER['SCRIPT_NAME'] ?? '')));
        if (in_array($script, ['index.php', 'portal_enter.php', 'portal_setup.php', ''], true)) {
            return null;
        }

        $world = self::selectedFromCookie();
        if (!$world || !empty($world['local'])) {
            return null;
        }
        $prefix = (string) ($world['tb_prefix'] ?? '');
        if ($prefix === '' || !preg_match('/^[a-zA-Z0-9_]{1,32}$/', $prefix)) {
            return null;
        }
        if (self::isExternal($world)) {
            return null;
        }

        // Only switch if the world users table exists.
        if (!self::prefixHasUsersTable($prefix)) {
            return null;
        }

        return $prefix;
    }

    /** True when `{prefix}users` exists in the configured database. */
    public static function prefixHasUsersTable(string $prefix): bool
    {
        if (!preg_match('/^[a-zA-Z0-9_]{1,32}$/', $prefix)) {
            return false;
        }
        if (!defined('SQL_SERVER') || !defined('SQL_USER') || !defined('SQL_PASS') || !defined('SQL_DB')) {
            return false;
        }
        static $cache = [];
        if (array_key_exists($prefix, $cache)) {
            return $cache[$prefix];
        }
        $port = defined('SQL_PORT') ? (int) SQL_PORT : 3306;
        $link = @mysqli_connect(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB, $port);
        if (!$link) {
            $cache[$prefix] = false;
            return false;
        }
        $table = $prefix . 'users';
        $res = @mysqli_query(
            $link,
            "SHOW TABLES LIKE '" . mysqli_real_escape_string($link, $table) . "'"
        );
        $ok = ($res && mysqli_num_rows($res) > 0);
        if ($res instanceof mysqli_result) {
            mysqli_free_result($res);
        }
        mysqli_close($link);
        $cache[$prefix] = $ok;
        return $ok;
    }

    public static function setCookie(string $id): void
    {
        $id = preg_replace('/[^a-zA-Z0-9_-]/', '', $id);
        if ($id === '') {
            return;
        }
        $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        setcookie(self::COOKIE, $id, [
            'expires' => time() + 86400 * 365,
            'path' => '/',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        $_COOKIE[self::COOKIE] = $id;
    }

    /**
     * @param list<array<string,mixed>> $worlds
     */
    public static function save(array $worlds): bool
    {
        $normalized = [];
        $seenLocal = false;
        foreach ($worlds as $row) {
            if (!is_array($row)) {
                continue;
            }
            $w = self::normalize($row);
            if ($w['local']) {
                if ($seenLocal) {
                    $w['local'] = false;
                } else {
                    $seenLocal = true;
                }
            }
            $normalized[] = $w;
        }

        $dir = dirname(self::storagePath());
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $payload = json_encode(
            ['version' => 2, 'updated_at' => time(), 'worlds' => $normalized],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        if ($payload === false) {
            return false;
        }

        $ok = @file_put_contents(self::storagePath(), $payload . "\n", LOCK_EX) !== false;
        if ($ok) {
            self::$cache = $normalized;
        }
        return $ok;
    }

    public static function setEnabled(string $id, bool $enabled): bool
    {
        $worlds = self::all();
        $found = false;
        foreach ($worlds as &$w) {
            if ((string) $w['id'] === $id) {
                $w['enabled'] = $enabled;
                $found = true;
                break;
            }
        }
        unset($w);
        return $found ? self::save($worlds) : false;
    }

    public static function markProvisioned(string $id, bool $ok = true): bool
    {
        $worlds = self::all();
        $found = false;
        foreach ($worlds as &$w) {
            if ((string) $w['id'] === $id) {
                $w['provisioned'] = $ok;
                $found = true;
                break;
            }
        }
        unset($w);
        return $found ? self::save($worlds) : false;
    }

    /** @return array{players:int,active:int,online:int} */
    public static function localStats($link = null): array
    {
        $stats = ['players' => 0, 'active' => 0, 'online' => 0];
        if ($link === null) {
            $link = $GLOBALS['link'] ?? null;
        }
        if (!$link || !defined('TB_PREFIX')) {
            return $stats;
        }

        $tribe = 'tribe IN(1, 2, 3, 6, 7, 8, 9)';
        $q = mysqli_query($link, 'SELECT COUNT(*) AS Total FROM ' . TB_PREFIX . 'users WHERE ' . $tribe);
        if ($q && ($row = mysqli_fetch_assoc($q))) {
            $stats['players'] = (int) $row['Total'];
        }

        $q = mysqli_query(
            $link,
            'SELECT COUNT(*) AS Total FROM ' . TB_PREFIX . 'users WHERE timestamp > '
            . (time() - (3600 * 24)) . ' AND ' . $tribe
        );
        if ($q && ($row = mysqli_fetch_assoc($q))) {
            $stats['active'] = (int) $row['Total'];
        }

        $q = mysqli_query(
            $link,
            'SELECT COUNT(*) AS Total FROM ' . TB_PREFIX . 'users WHERE timestamp > '
            . (time() - (60 * 10)) . ' AND ' . $tribe
        );
        if ($q && ($row = mysqli_fetch_assoc($q))) {
            $stats['online'] = (int) $row['Total'];
        }

        return $stats;
    }

    /**
     * Player count for a world using its own table prefix (same MySQL DB).
     */
    public static function statsForPrefix($link, string $prefix): array
    {
        $stats = ['players' => 0, 'active' => 0, 'online' => 0];
        if (!$link || !preg_match('/^[a-zA-Z0-9_]{1,32}$/', $prefix)) {
            return $stats;
        }

        $table = $prefix . 'users';
        $check = @mysqli_query($link, "SHOW TABLES LIKE '" . mysqli_real_escape_string($link, $table) . "'");
        if (!$check || mysqli_num_rows($check) < 1) {
            return $stats;
        }

        $tribe = 'tribe IN(1, 2, 3, 6, 7, 8, 9)';
        $q = mysqli_query($link, 'SELECT COUNT(*) AS Total FROM `' . $table . '` WHERE ' . $tribe);
        if ($q && ($row = mysqli_fetch_assoc($q))) {
            $stats['players'] = (int) $row['Total'];
        }
        $q = mysqli_query(
            $link,
            'SELECT COUNT(*) AS Total FROM `' . $table . '` WHERE timestamp > '
            . (time() - (60 * 10)) . ' AND ' . $tribe
        );
        if ($q && ($row = mysqli_fetch_assoc($q))) {
            $stats['online'] = (int) $row['Total'];
        }
        return $stats;
    }

    /**
     * @param array<string,mixed> $world
     * @return array<string,mixed>
     */
    public static function prepareForDisplay(array $world, $link = null): array
    {
        $world = self::normalize($world);
        $now = time();
        $start = (int) $world['start_time'];
        $world['started'] = $start <= $now;
        $world['seconds_to_start'] = $world['started'] ? 0 : max(0, $start - $now);
        $world['age_seconds'] = $world['started'] ? max(0, $now - $start) : 0;

        if (!empty($world['local'])) {
            $stats = self::localStats($link);
            $world['players'] = $stats['players'];
            $world['online'] = $stats['online'];
            $world['active'] = $stats['active'];
        } elseif ($link && !self::isExternal($world)) {
            $stats = self::statsForPrefix($link, (string) $world['tb_prefix']);
            $world['players'] = $stats['players'];
            $world['online'] = $stats['online'];
        }

        $world['bg_image'] = $world['started']
            ? (string) $world['image']
            : (string) ($world['image_grey'] !== '' ? $world['image_grey'] : $world['image']);

        $world['badge_label'] = self::badgeLabel((string) $world['badge']);
        $world['age_label'] = self::formatAge((int) $world['age_seconds']);
        $world['countdown_label'] = self::formatCountdown((int) $world['seconds_to_start']);

        $id = rawurlencode((string) $world['id']);
        if (self::isExternal($world)) {
            $world['login_href'] = (string) $world['login_url'];
            $world['register_href'] = (string) $world['register_url'];
        } else {
            $world['login_href'] = 'portal_enter.php?w=' . $id . '&do=login';
            $world['register_href'] = 'portal_enter.php?w=' . $id . '&do=register';
        }

        return $world;
    }

    public static function isExternal(array $world): bool
    {
        foreach (['login_url', 'register_url'] as $key) {
            $url = trim((string) ($world[$key] ?? ''));
            if ($url === '' || preg_match('#^https?://#i', $url) !== 1) {
                continue;
            }
            $host = strtolower((string) (parse_url($url, PHP_URL_HOST) ?? ''));
            $here = strtolower((string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));
            if ($host !== '' && $host !== $here && $host !== 'localhost' && $host !== '127.0.0.1') {
                return true;
            }
            // Same host but path is only "/" → treat as not a real external game URL.
            $path = (string) (parse_url($url, PHP_URL_PATH) ?? '/');
            if ($path !== '/' && $path !== '') {
                return true;
            }
        }
        return false;
    }

    /**
     * Create independent DB tables (+ map) for a world prefix.
     *
     * @return array{ok:bool,msg:string}
     */
    public static function provision(array $world, $link): array
    {
        $world = self::normalize($world);
        if (!empty($world['local'])) {
            self::markProvisioned((string) $world['id'], true);
            return ['ok' => true, 'msg' => 'local'];
        }
        if (self::isExternal($world)) {
            return ['ok' => true, 'msg' => 'external'];
        }

        $prefix = (string) $world['tb_prefix'];
        if (!preg_match('/^[a-zA-Z0-9_]{1,32}$/', $prefix)) {
            return ['ok' => false, 'msg' => 'bad_prefix'];
        }
        if (!$link) {
            return ['ok' => false, 'msg' => 'no_link'];
        }

        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        @ini_set('mysqli.allow_local_infile', '1');

        // PHP 8+ mysqli throws on SQL errors by default; that turns a recoverable
        // seed conflict into a blank HTTP 500 during world setup.
        $prevReport = null;
        if (function_exists('mysqli_report')) {
            $prevReport = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
            mysqli_report(MYSQLI_REPORT_OFF);
        }

        try {
            return self::provisionInner($world, $link, $prefix);
        } catch (Throwable $e) {
            // If tables were created despite a seed conflict, treat as ready.
            if (self::prefixHasUsersTable($prefix)) {
                self::markProvisioned((string) $world['id'], true);
                return ['ok' => true, 'msg' => 'ready_after_error'];
            }
            return ['ok' => false, 'msg' => 'ex:' . $e->getMessage()];
        } finally {
            if ($prevReport !== null) {
                mysqli_report($prevReport);
            }
        }
    }

    /**
     * @param array<string,mixed> $world
     * @return array{ok:bool,msg:string}
     */
    private static function provisionInner(array $world, $link, string $prefix): array
    {
        $usersTable = $prefix . 'users';
        $wdataTable = $prefix . 'wdata';
        $escUsers = mysqli_real_escape_string($link, $usersTable);
        $escWdata = mysqli_real_escape_string($link, $wdataTable);

        $usersExist = false;
        $exists = @mysqli_query($link, "SHOW TABLES LIKE '{$escUsers}'");
        if ($exists && mysqli_num_rows($exists) > 0) {
            $usersExist = true;
        }
        if ($exists instanceof mysqli_result) {
            mysqli_free_result($exists);
        }

        $root = dirname(__DIR__);

        if (!$usersExist) {
            $struct = @file_get_contents($root . '/var/db/struct.sql');
            if ($struct === false) {
                return ['ok' => false, 'msg' => 'missing_struct'];
            }
            $struct = str_replace('%PREFIX%', $prefix, $struct);
            if (!@mysqli_multi_query($link, $struct)) {
                self::drainMulti($link);
                // Fall through — maybe partial create still left users table.
            } else {
                self::drainMulti($link);
            }
        }

        $exists = @mysqli_query($link, "SHOW TABLES LIKE '{$escUsers}'");
        $usersExist = ($exists && mysqli_num_rows($exists) > 0);
        if ($exists instanceof mysqli_result) {
            mysqli_free_result($exists);
        }
        if (!$usersExist) {
            return ['ok' => false, 'msg' => 'users_missing'];
        }

        $wdataExist = false;
        $wdataHasRows = false;
        $exists = @mysqli_query($link, "SHOW TABLES LIKE '{$escWdata}'");
        if ($exists && mysqli_num_rows($exists) > 0) {
            $wdataExist = true;
            $cnt = @mysqli_query($link, "SELECT 1 FROM `{$wdataTable}` LIMIT 1");
            $wdataHasRows = ($cnt && mysqli_num_rows($cnt) > 0);
            if ($cnt instanceof mysqli_result) {
                mysqli_free_result($cnt);
            }
        }
        if ($exists instanceof mysqli_result) {
            mysqli_free_result($exists);
        }

        if ($wdataExist && !$wdataHasRows) {
            $worldSize = defined('WORLD_MAX') ? max(20, (int) WORLD_MAX) : 100;
            // Cap huge maps during interactive portal setup to avoid timeouts.
            if ($worldSize > 80) {
                $worldSize = 80;
            }
            $datagen = @file_get_contents($root . '/var/db/datagen-world-data.sql');
            if ($datagen !== false) {
                $datagen = str_replace(
                    ['%PREFIX%', '%WORLDSIZE%'],
                    [$prefix, (string) $worldSize],
                    $datagen
                );
                if (@mysqli_multi_query($link, $datagen)) {
                    self::drainMulti($link);
                } else {
                    self::drainMulti($link);
                }
            }
        }

        self::markProvisioned((string) $world['id'], true);
        return ['ok' => true, 'msg' => 'ready'];
    }

    private static function drainMulti($link): void
    {
        do {
            $r = @mysqli_store_result($link);
            if ($r instanceof mysqli_result) {
                mysqli_free_result($r);
            }
        } while (@mysqli_more_results($link) && @mysqli_next_result($link));
    }

    public static function badgeLabel(string $badge): string
    {
        switch ($badge) {
            case 'limited_gold':
                return defined('PORTAL_BADGE_LIMITED_GOLD')
                    ? PORTAL_BADGE_LIMITED_GOLD
                    : 'صرف ذهب محدود';
            case 'permanent':
                return defined('PORTAL_BADGE_PERMANENT')
                    ? PORTAL_BADGE_PERMANENT
                    : 'سيرفر الدوام';
            case 'newest':
                return defined('PORTAL_BADGE_NEWEST')
                    ? PORTAL_BADGE_NEWEST
                    : '[الأجدد]';
            default:
                return '';
        }
    }

    public static function formatAge(int $seconds): string
    {
        if ($seconds < 3600) {
            $n = max(1, (int) floor($seconds / 60));
            return sprintf(defined('PORTAL_AGE_MINUTES') ? PORTAL_AGE_MINUTES : 'منذ %d دقيقة', $n);
        }
        if ($seconds < 86400) {
            $n = max(1, (int) floor($seconds / 3600));
            return sprintf(defined('PORTAL_AGE_HOURS') ? PORTAL_AGE_HOURS : 'منذ %d ساعة', $n);
        }
        $days = (int) floor($seconds / 86400);
        if ($days < 7) {
            return sprintf(defined('PORTAL_AGE_DAYS') ? PORTAL_AGE_DAYS : 'منذ %d يوم', max(1, $days));
        }
        $weeks = (int) floor($days / 7);
        if ($weeks < 8) {
            return sprintf(defined('PORTAL_AGE_WEEKS') ? PORTAL_AGE_WEEKS : 'منذ %d أسبوع', max(1, $weeks));
        }
        $months = max(1, (int) floor($days / 30));
        return sprintf(defined('PORTAL_AGE_MONTHS') ? PORTAL_AGE_MONTHS : 'منذ %d شهر', $months);
    }

    public static function formatCountdown(int $seconds): string
    {
        $seconds = max(0, $seconds);
        $h = (int) floor($seconds / 3600);
        $m = (int) floor(($seconds % 3600) / 60);
        $s = $seconds % 60;
        $clock = sprintf('%d:%02d:%02d', $h, $m, $s);
        return sprintf(defined('PORTAL_STARTS_AFTER') ? PORTAL_STARTS_AFTER : 'يبدأ بعد %s', $clock);
    }

    /**
     * @param array<string,mixed> $row
     * @return array<string,mixed>
     */
    public static function normalize(array $row): array
    {
        $id = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($row['id'] ?? ''));
        if ($id === '') {
            $id = 'w' . substr(md5((string) json_encode($row)), 0, 8);
        }

        $badge = (string) ($row['badge'] ?? '');
        $allowedBadges = ['', 'limited_gold', 'permanent', 'newest'];
        if (!in_array($badge, $allowedBadges, true)) {
            $badge = '';
        }

        $number = (int) ($row['number'] ?? 0);
        if ($number < 1) {
            $number = 1;
        }
        if ($number > 99) {
            $number = 99;
        }

        $imgNum = min(15, max(1, $number));
        $defaultImg = 'img/en/welten/en' . $imgNum . '_big.jpg';
        $defaultGrey = 'img/en/welten/en' . $imgNum . '_big_g.jpg';

        $tb = preg_replace('/[^a-zA-Z0-9_]/', '', (string) ($row['tb_prefix'] ?? ''));
        if ($tb === '') {
            $tb = !empty($row['local'])
                ? (defined('TB_PREFIX') ? (string) TB_PREFIX : 's1_')
                : ('pw' . $number . '_');
        }
        if (substr($tb, -1) !== '_') {
            $tb .= '_';
        }

        return [
            'id' => $id,
            'number' => $number,
            'name' => trim((string) ($row['name'] ?? ('Server ' . $number))),
            'enabled' => !empty($row['enabled']),
            'local' => !empty($row['local']),
            'badge' => $badge,
            'image' => self::safePath((string) ($row['image'] ?? $defaultImg), $defaultImg),
            'image_grey' => self::safePath((string) ($row['image_grey'] ?? $defaultGrey), $defaultGrey),
            'start_time' => (int) ($row['start_time'] ?? time()),
            'login_url' => self::safeUrl((string) ($row['login_url'] ?? ''), ''),
            'register_url' => self::safeUrl((string) ($row['register_url'] ?? ''), ''),
            'tb_prefix' => $tb,
            'provisioned' => !empty($row['provisioned']) || !empty($row['local']),
            'players' => max(0, (int) ($row['players'] ?? 0)),
            'online' => max(0, (int) ($row['online'] ?? 0)),
            'sort' => (int) ($row['sort'] ?? 0),
        ];
    }

    private static function safePath(string $path, string $fallback): string
    {
        $path = str_replace('\\', '/', trim($path));
        if ($path === '' || strpos($path, '..') !== false || preg_match('#^(https?:)?//#i', $path)) {
            return $fallback;
        }
        if (!preg_match('#^[a-zA-Z0-9_./-]+$#', $path)) {
            return $fallback;
        }
        return $path;
    }

    private static function safeUrl(string $url, string $fallback): string
    {
        $url = trim($url);
        if ($url === '') {
            return $fallback;
        }
        if (preg_match('#^(javascript|data):#i', $url)) {
            return $fallback;
        }
        if (preg_match('#^https?://#i', $url) || preg_match('#^[a-zA-Z0-9_./?&=%-]+$#', $url)) {
            return $url;
        }
        return $fallback;
    }
}
