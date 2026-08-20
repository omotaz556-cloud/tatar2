<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : FeatureFlags.php                                          ##
##  Type           : Generic on/off feature flag engine                        ##
## --------------------------------------------------------------------------- ##
##  Project        : Novaterra                                                  ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################

/**
 * FeatureFlags
 * -------------------------------------------------------------------------
 * Item #10 of the audit: a GENERIC, DB-backed on/off switch system that the
 * admin can extend WITHOUT touching code or config.php.
 *
 * Why not just add another placeholder to config.php / constant_format.tpl
 * like the existing NEW_FUNCTIONS_* switches?
 *   - Every one of those switches needs a developer to add a placeholder to
 *     the template, a form field to editNewFunctions.php/.tpl, and a
 *     fallback entry in config_template.php BEFORE an admin can use it. That
 *     is a code change per flag, not an admin operation.
 *   - config.php is regenerated wholesale on every admin "Save" from ANY of
 *     the settings pages (Server Settings, Log Settings, PLUS Settings,
 *     ...). That already needed the elaborate tz_config_finalize() safety
 *     net in config_template.php to avoid a corrupted/half-written file.
 *     Adding a free-form, admin-defined key/value set into that same
 *     regenerate-the-whole-file flow would reintroduce exactly the
 *     dangerous-placeholder / write-conflict class of bug that system was
 *     built to eliminate.
 *   - A flag an admin adds today has no template placeholder at all, so it
 *     would be silently dropped (or worse, left as literal "%X%" text) by
 *     admin_config_template_contents() the next time ANY settings page save
 *     regenerates config.php.
 *
 * So: flags a developer designs in advance (bonuses, PLUS statistics, tribe
 * toggles, ...) keep living in config.php via NEW_FUNCTIONS_* - that part of
 * the audit ("existing switches work") is untouched. This class adds a
 * SEPARATE, parallel mechanism for flags the ADMIN defines at runtime:
 * arbitrary string key, on/off, optional note - stored in its own table, so
 * config.php's write path is never touched by it.
 *
 * Self-contained (static, resolves DB link from globals, self-creates its
 * table), matching the GoldShop.php convention.
 *
 * Usage anywhere in game code (after Database.php has run, since that's
 * what populates $GLOBALS['link']):
 *
 *     if (FeatureFlags::isEnabled('gold_res_purchase')) { ... }
 *
 * or, for the common case of "an optional message when it's off":
 *
 *     FeatureFlags::isEnabled('some_key', $default = true)
 */
class FeatureFlags
{
    /** In-request cache: avoids one query per isEnabled() call on the same page. */
    private static $cache = null;

    private static function link()
    {
        if (isset($GLOBALS['link']) && $GLOBALS['link']) {
            return $GLOBALS['link'];
        }
        if (isset($GLOBALS['database']) && isset($GLOBALS['database']->dblink)) {
            return $GLOBALS['database']->dblink;
        }
        return null;
    }

    public static function ensureSchema()
    {
        $link = self::link();
        if (!$link) {
            return;
        }
        @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "feature_flags` (
            `id`         int(11) NOT NULL AUTO_INCREMENT,
            `flag_key`   varchar(80) NOT NULL,
            `enabled`    tinyint(1) NOT NULL DEFAULT 0,
            `label`      varchar(150) NOT NULL DEFAULT '',
            `note`       varchar(255) NOT NULL DEFAULT '',
            `updated_by` int(11) NOT NULL DEFAULT 0,
            `time`       int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            UNIQUE KEY `flag_key` (`flag_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    }

    /** Normalise a flag key: lowercase, snake-ish, safe as a code identifier. */
    public static function normKey($key)
    {
        $key = strtolower(trim((string) $key));
        $key = preg_replace('/[^a-z0-9_]/', '_', $key);
        $key = preg_replace('/_+/', '_', $key);
        return trim($key, '_');
    }

    /**
     * Load all flags into the in-request cache (one query per page load,
     * regardless of how many isEnabled() calls follow).
     */
    private static function loadAll()
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        self::$cache = array();

        $link = self::link();
        if (!$link) {
            return self::$cache;
        }
        self::ensureSchema();

        $res = @mysqli_query($link, "SELECT flag_key, enabled FROM `" . TB_PREFIX . "feature_flags`");
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                self::$cache[$row['flag_key']] = ((int) $row['enabled']) === 1;
            }
        }

        return self::$cache;
    }

    /**
     * Whether a flag is enabled. If the key doesn't exist yet, returns
     * $default (and does NOT auto-create the row - creation is an explicit
     * admin action, see create()).
     */
    public static function isEnabled($key, $default = false)
    {
        $key   = self::normKey($key);
        $flags = self::loadAll();
        return array_key_exists($key, $flags) ? $flags[$key] : (bool) $default;
    }

    /** Force a re-read on next isEnabled() call within the same request. */
    public static function clearCache()
    {
        self::$cache = null;
    }

    /* ---- Admin management ------------------------------------------------ */

    /** @return array all flags, most recently updated first. */
    public static function listAll()
    {
        $link = self::link();
        if (!$link) {
            return array();
        }
        self::ensureSchema();

        $out = array();
        $res = @mysqli_query($link, "SELECT * FROM `" . TB_PREFIX . "feature_flags` ORDER BY `time` DESC, `id` DESC");
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $out[] = $row;
            }
        }
        return $out;
    }

    /**
     * Create a new flag.
     * @return array [ok(bool), message(string)]
     */
    public static function create($key, $enabled, $label, $note, $adminId)
    {
        $link = self::link();
        if (!$link) {
            return array(false, 'No database connection.');
        }
        self::ensureSchema();

        $key = self::normKey($key);
        if ($key === '' || strlen($key) < 2) {
            return array(false, 'Key must be at least 2 characters (letters, numbers, underscore).');
        }
        if (strlen($key) > 80) {
            return array(false, 'Key is too long (max 80 characters).');
        }

        $enabled = $enabled ? 1 : 0;
        $label   = substr((string) $label, 0, 150);
        $note    = substr((string) $note, 0, 255);
        $admin   = (int) $adminId;
        $now     = time();

        $stmt = mysqli_prepare($link,
            "INSERT INTO `" . TB_PREFIX . "feature_flags`
             (flag_key, enabled, label, note, updated_by, time)
             VALUES (?,?,?,?,?,?)");
        if (!$stmt) {
            return array(false, 'Could not prepare statement.');
        }
        mysqli_stmt_bind_param($stmt, 'sissii', $key, $enabled, $label, $note, $admin, $now);
        $ok  = mysqli_stmt_execute($stmt);
        $err = mysqli_stmt_errno($stmt);
        mysqli_stmt_close($stmt);

        if (!$ok) {
            if ($err === 1062) {
                return array(false, 'A flag with that key already exists.');
            }
            return array(false, 'Could not create flag.');
        }

        self::clearCache();
        return array(true, 'Flag "' . $key . '" created.');
    }

    /** Flip a flag on/off. */
    public static function setEnabled($id, $enabled, $adminId)
    {
        $link = self::link();
        if (!$link) {
            return false;
        }
        $id      = (int) $id;
        $enabled = $enabled ? 1 : 0;
        $admin   = (int) $adminId;
        $now     = time();

        $stmt = mysqli_prepare($link,
            "UPDATE `" . TB_PREFIX . "feature_flags` SET enabled = ?, updated_by = ?, time = ? WHERE id = ?");
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'iiii', $enabled, $admin, $now, $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        self::clearCache();
        return $ok;
    }

    /** Update label/note without touching the on/off state. */
    public static function updateMeta($id, $label, $note, $adminId)
    {
        $link = self::link();
        if (!$link) {
            return false;
        }
        $id    = (int) $id;
        $label = substr((string) $label, 0, 150);
        $note  = substr((string) $note, 0, 255);
        $admin = (int) $adminId;
        $now   = time();

        $stmt = mysqli_prepare($link,
            "UPDATE `" . TB_PREFIX . "feature_flags` SET label = ?, note = ?, updated_by = ?, time = ? WHERE id = ?");
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'ssiii', $label, $note, $admin, $now, $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        self::clearCache();
        return $ok;
    }

    public static function delete($id)
    {
        $link = self::link();
        if (!$link) {
            return false;
        }
        $id = (int) $id;

        $stmt = mysqli_prepare($link, "DELETE FROM `" . TB_PREFIX . "feature_flags` WHERE id = ?");
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        self::clearCache();
        return $ok;
    }

    /**
     * Seed a flag if it doesn't exist yet, without overwriting an existing
     * admin choice. Intended to be called once from a feature's own code
     * path (e.g. build.php on first load) so a flag a developer wires up
     * shows up in the admin list automatically instead of needing a manual
     * "Add Flag" click - the admin still fully controls it from then on.
     */
    public static function seed($key, $defaultEnabled, $label, $note = '')
    {
        $link = self::link();
        if (!$link) {
            return;
        }
        self::ensureSchema();

        $key = self::normKey($key);
        if ($key === '') {
            return;
        }

        $stmt = mysqli_prepare($link, "SELECT id FROM `" . TB_PREFIX . "feature_flags` WHERE flag_key = ?");
        if (!$stmt) {
            return;
        }
        mysqli_stmt_bind_param($stmt, 's', $key);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $exists = mysqli_stmt_num_rows($stmt) > 0;
        mysqli_stmt_close($stmt);

        if ($exists) {
            return;
        }

        self::create($key, $defaultEnabled, $label, $note, 0);
    }
}
