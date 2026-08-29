<?php

/**
 * Greek.sa messages page markup (nachrichten.php).
 */
class GreekNachrichten
{
    public static function isGreekNachrichtenUi()
    {
        return !empty($GLOBALS['gkNachrichtenLiteralPage'])
            && !empty($GLOBALS['gkShell'])
            && function_exists('tz_is_rtl_lang')
            && tz_is_rtl_lang();
    }

    /**
     * @param int $tab Current ?t= value (0 = inbox)
     */
    public static function menuOpen($tab)
    {
        $tab = (int) $tab;
        global $session;

        $tabs = array(
            array('href' => 'nachrichten.php', 'label' => defined('TZ_MSG_INBOX_TAB') ? TZ_MSG_INBOX_TAB : 'الوارد', 't' => 0),
            array('href' => 'nachrichten.php?t=2', 'label' => defined('TZ_MSG_SENT_TAB') ? TZ_MSG_SENT_TAB : 'الصادر', 't' => 2),
            array('href' => 'nachrichten.php?t=1', 'label' => defined('TZ_MSG_WRITE_TAB') ? TZ_MSG_WRITE_TAB : 'أكتب', 't' => 1),
            array('href' => 'allianz.php', 'label' => defined('TZ_MSG_CHAT_TAB') ? TZ_MSG_CHAT_TAB : 'الشات', 't' => -1),
            array('href' => 'nachrichten.php?t=4', 'label' => defined('TZ_MSG_NOTES_TAB') ? TZ_MSG_NOTES_TAB : 'دفتر الملاحظات', 't' => 4),
            array('href' => 'nachrichten.php?t=1', 'label' => defined('TZ_MSG_IGNORED_TAB') ? TZ_MSG_IGNORED_TAB : 'المتجاهلون', 't' => 5),
        );

        echo '<div id="BP"><div class="Bod">';
        echo '<div class="PaNa">' . htmlspecialchars(defined('MESSAGES') ? MESSAGES : 'الرسائل', ENT_QUOTES, 'UTF-8') . '</div>';
        echo '<span class="BAR5"><span class="c-row">';
        foreach ($tabs as $item) {
            $itemTab = (int) $item['t'];
            $active = ($itemTab === $tab);
            if ($itemTab === 5) {
                $active = false;
            }
            if ($itemTab === 1 && $tab === 1) {
                $active = true;
            }
            if ($itemTab === 4 && $tab === 4) {
                $active = true;
            }
            $cls = $active ? ' class="-cho"' : '';
            echo '<a href="' . htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') . '"' . $cls . '>'
                . htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') . '</a>';
        }
        echo '</span></span>';
    }

    public static function menuClose()
    {
        echo '</div></div>';
    }
}
