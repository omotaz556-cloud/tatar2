<?php
/**
 * Shared Greek.sa shell (gk-head, gk-resbar, gk-shell) for in-game pages.
 */

if (!function_exists('tz_greek_shell_enabled')) {
    function tz_greek_shell_enabled() {
        return !defined('GREEK_SHELL') || GREEK_SHELL;
    }
}

if (!function_exists('tz_greek_stylesheet_tag')) {
    function tz_greek_stylesheet_tag($relPath = '') {
        if (!tz_greek_shell_enabled()) {
            return '';
        }
        $path = dirname(__DIR__) . '/css/dorf1_greek.css';
        if (!is_file($path)) {
            return '';
        }
        $href = $relPath . 'css/dorf1_greek.css';
        $ver = (int) @filemtime($path);
        return '<link href="' . htmlspecialchars($href, ENT_QUOTES) . '?v=' . $ver . '" rel="stylesheet" type="text/css" />';
    }
}

if (!function_exists('tz_greek_shell_head')) {
    /**
     * @param string $pageTitle Full <title> text
     * @param string $bodyClass  e.g. pg-dorf2 (pg-gk added automatically)
     * @param array  $opts extraCss[], inlineStyle[], includeNew2Js
     */
    function tz_greek_shell_head($pageTitle, $bodyClass = '', array $opts = array()) {
        global $gkBodyClass;
        $gkBodyClass = trim($bodyClass);
        $gkPageTitle = $pageTitle;
        $gkShellHeadOpts = $opts;
        $gkShell = true;
        $GLOBALS['gkShell'] = true;
        include dirname(__DIR__) . '/Templates/Greek/shell_head.tpl';
    }
}

if (!function_exists('tz_greek_shell_open')) {
    /**
     * Opens body + gk-head + resbar + 3-col shell into gk-td-main.
     *
     * @param string $contentClass #content class (empty = no #content wrapper)
     * @param array  $opts contentWrap (bool), showVillageTitle (bool)
     */
    function tz_greek_shell_open($contentClass = '', array $opts = array()) {
        global $gkShell, $gkContentClass, $gkShellOpenOpts, $session, $village, $database, $message, $uid, $gold, $serverLabel, $vDisplayName;
        $gkShell = true;
        $GLOBALS['gkShell'] = true;
        $gkContentClass = $contentClass;
        $gkShellOpenOpts = array_merge(array(
            'contentWrap' => ($contentClass !== ''),
            'showVillageTitle' => false,
            'resbarInMain' => false,
        ), $opts);

        if (!isset($uid) && isset($session->uid)) {
            $uid = (int) $session->uid;
        }
        if (!isset($gold) && isset($session->gold)) {
            $gold = (int) $session->gold;
        }
        if (!isset($serverLabel)) {
            $serverLabel = defined('SERVER_NAME') ? SERVER_NAME : 'Novaterra';
        }
        if (!isset($vDisplayName) && !empty($village)) {
            $vDisplayName = function_exists('tz_display_village_name')
                ? tz_display_village_name($village->vname, $session->username ?? null)
                : $village->vname;
        }
        // Ensure message counts are available inside this function scope for topnav_icons.tpl
        if (!isset($message) && isset($GLOBALS['message'])) {
            $message = $GLOBALS['message'];
        }

        include dirname(__DIR__) . '/Templates/Greek/gk_head.tpl';
        if (empty($gkShellOpenOpts['resbarInMain'])) {
            include dirname(__DIR__) . '/Templates/Greek/gk_resbar.tpl';
        }
        include dirname(__DIR__) . '/Templates/Greek/shell_open.tpl';
    }
}

if (!function_exists('tz_greek_shell_close')) {
    /**
     * @param array $opts buildPopup (bool), timer (float|null), extraScripts (string)
     */
    function tz_greek_shell_close(array $opts = array()) {
        global $generator, $start_timer, $pagestart, $gkShellCloseOpts;
        $gkShellCloseOpts = array_merge(array(
            'buildPopup' => true,
            'timer' => null,
            'extraScripts' => '',
            'extraScriptTags' => '',
        ), $opts);

        if ($gkShellCloseOpts['timer'] === null) {
            if (isset($start_timer)) {
                $gkShellCloseOpts['timer'] = $start_timer;
            } elseif (isset($pagestart)) {
                $gkShellCloseOpts['timer'] = $pagestart;
            }
        }

        include dirname(__DIR__) . '/Templates/Greek/shell_close.tpl';
    }
}
