<?php

/**
 * Greek.sa player profile page markup (spieler.php).
 */
class GreekSpieler
{
    public static function isGreekSpielerUi()
    {
        return !empty($GLOBALS['gkSpielerLiteralPage'])
            && !empty($GLOBALS['gkShell'])
            && function_exists('tz_is_rtl_lang')
            && tz_is_rtl_lang();
    }

    /**
     * Classic #textmenu must not render when Greek BAR5 is active.
     */
    public static function suppressClassicMenu()
    {
        return !empty($GLOBALS['gkSpielerGreek'])
            || !empty($GLOBALS['gkSpielerBarRendered']);
    }

    /**
     * @param int $tab Active tab id
     * @param int $uid Profile owner uid
     * @param int $subTab Active ?s= value (0 = none)
     */
    public static function menuOpen($tab, $uid, $subTab = 0)
    {
        $GLOBALS['gkSpielerBarRendered'] = true;

        $tab = (int) $tab;
        $uid = (int) $uid;
        $subTab = (int) $subTab;
        global $session;

        $viewingSelf = isset($session->uid) && (int) $session->uid === $uid;
        $sitterView = isset($session) && is_object($session)
            && method_exists($session, 'isSitterSession') && $session->isSitterSession();

        $pageTitle = defined('PLAYER_PROFILE') ? PLAYER_PROFILE : 'عضوية اللاعب';

        echo '<div id="BP"><div class="Bod">';
        echo '<div class="PaNa">' . htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . '</div>';
        echo '<style type="text/css">'
            . 'body.pg-gk.pg-spieler #textmenu,'
            . 'body.pg-gk.pg-spieler .gk-spieler-body>h1,'
            . 'body.pg-gk.pg-spieler #content.player #textmenu,'
            . 'body.pg-gk.pg-spieler #content.player>h1'
            . '{display:none!important;}</style>';

        if ($viewingSelf && !$sitterView) {
            $tabs = array(
                array(
                    'href' => 'spieler.php?uid=' . $uid,
                    'label' => defined('TZ_PROF_TAB_OVERVIEW') ? TZ_PROF_TAB_OVERVIEW : 'نظرة عامة',
                    't' => 1,
                ),
                array(
                    'href' => 'spieler.php?s=1',
                    'label' => defined('TZ_PROF_TAB_ABOUT') ? TZ_PROF_TAB_ABOUT : 'من نحن',
                    't' => 2,
                ),
                array(
                    'href' => 'spieler.php?s=3',
                    'label' => defined('TZ_PROF_TAB_MEMBERSHIP') ? TZ_PROF_TAB_MEMBERSHIP : 'العضوية',
                    't' => 3,
                ),
                array(
                    'href' => 'spieler.php?s=2&dl=1',
                    'label' => defined('DIRECT_LINKS') ? DIRECT_LINKS : 'روابط مباشرة',
                    't' => 4,
                ),
                array(
                    'href' => 'spieler.php?s=3&nr=1',
                    'label' => defined('TZ_PROF_TAB_NAME_RESERVE') ? TZ_PROF_TAB_NAME_RESERVE : 'حجز الاسم',
                    't' => 5,
                ),
                array(
                    'href' => 'spieler.php?uid=' . $uid . '&hub=1',
                    'label' => defined('TZ_PROF_TAB_OPTIONS') ? TZ_PROF_TAB_OPTIONS : 'خيارات',
                    't' => 6,
                ),
                array(
                    'href' => 'feeding.php',
                    'label' => defined('TZ_PROF_TAB_ACCOUNTS') ? TZ_PROF_TAB_ACCOUNTS : 'الحسابات',
                    't' => 7,
                ),
            );

            echo '<span class="BAR5"><span class="c-row">';
            foreach ($tabs as $item) {
                $active = ((int) $item['t'] === $tab);
                $cls = $active ? ' class="-cho"' : '';
                echo '<a href="' . htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') . '"' . $cls . '>'
                    . htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') . '</a>';
            }
            echo '</span></span>';
        }

        echo '<div class="gk-spieler-body">';
    }

    public static function menuClose()
    {
        echo '</div></div></div>';
    }

    /**
     * Name reservation (حجز الاسم): eligible if this e-mail won a World Wonder
     * on any account/world we can see (WW lvl 100 owner, WW-reward milestone, or
     * [#WINNERWW] profile medal).
     */
    public static function nameReserveEligible($uid = null)
    {
        global $database, $session;

        if ($uid === null) {
            $uid = (int) ($session->uid ?? 0);
        } else {
            $uid = (int) $uid;
        }
        if ($uid < 2) {
            return false;
        }

        $desc2 = (string) ($session->userinfo['desc2'] ?? '');
        if (stripos($desc2, '[#WINNERWW]') !== false) {
            return true;
        }

        if (self::userOwnsWorldWonderLevel100($uid)) {
            return true;
        }

        $email = strtolower(trim((string) ($session->userinfo['email'] ?? '')));
        if ($email === '') {
            return false;
        }

        return self::emailHasWorldWonderWin($email);
    }

    /**
     * Hero mansion rename URL for name reservation (any village with gid 37).
     */
    public static function heroMansionRenameUrl()
    {
        global $database, $session;

        $uid = (int) ($session->uid ?? 0);
        if ($uid < 2 || !method_exists($database, 'getVillagesID')) {
            return 'build.php?gid=37&rename';
        }

        $vrefs = $database->getVillagesID($uid);
        if (!is_array($vrefs)) {
            return 'build.php?gid=37&rename';
        }

        foreach ($vrefs as $wref) {
            $wref = (int) $wref;
            if ($wref < 1) {
                continue;
            }
            for ($fi = 19; $fi <= 40; $fi++) {
                if ((int) $database->getFieldType($wref, $fi) === 37) {
                    return 'build.php?newdid=' . $wref . '&gid=37&rename';
                }
            }
        }

        return 'build.php?gid=37&rename';
    }

    private static function userOwnsWorldWonderLevel100($uid)
    {
        global $database;

        $uid = (int) $uid;
        if ($uid < 2) {
            return false;
        }

        $q = 'SELECT 1 FROM ' . TB_PREFIX . 'vdata v'
            . ' INNER JOIN ' . TB_PREFIX . 'fdata f ON f.vref = v.wref'
            . ' WHERE v.owner = ' . $uid . ' AND f.f40t = 40 AND f.f40 >= 100 LIMIT 1';
        $res = $database->query($q);

        return $res && mysqli_num_rows($res) > 0;
    }

    private static function emailHasWorldWonderWin($email)
    {
        global $database;

        $email = strtolower(trim((string) $email));
        if ($email === '') {
            return false;
        }

        $emailSql = mysqli_real_escape_string($database->dblink, $email);

        $q = 'SELECT 1 FROM ' . TB_PREFIX . 'milestones m'
            . ' INNER JOIN ' . TB_PREFIX . 'users u ON u.id = m.uid'
            . " WHERE m.milestone_key = 'world_wonder_rewards'"
            . " AND LOWER(TRIM(u.email)) = '" . $emailSql . "' LIMIT 1";
        $res = mysqli_query($database->dblink, $q);
        if ($res && mysqli_num_rows($res) > 0) {
            return true;
        }

        $q2 = 'SELECT 1 FROM ' . TB_PREFIX . 'users u'
            . ' INNER JOIN ' . TB_PREFIX . 'vdata v ON v.owner = u.id'
            . ' INNER JOIN ' . TB_PREFIX . 'fdata f ON f.vref = v.wref'
            . " WHERE LOWER(TRIM(u.email)) = '" . $emailSql . "'"
            . ' AND f.f40t = 40 AND f.f40 >= 100 LIMIT 1';
        $res2 = mysqli_query($database->dblink, $q2);

        return $res2 && mysqli_num_rows($res2) > 0;
    }
}
