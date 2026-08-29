<?php

if (!empty($GLOBALS['gkPlusNavOpen']) && class_exists('GreekPlus')) {
    GreekPlus::menuClose();
} elseif (!empty($GLOBALS['gkPlusContentOpen'])) {
    echo '</div>';
    $GLOBALS['gkPlusContentOpen'] = false;
}
