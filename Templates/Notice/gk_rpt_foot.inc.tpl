<?php
if (!empty($GLOBALS['gkBerichteLiteralPage']) && class_exists('GreekBerichte')) {
    GreekBerichte::reportFooter($message->readingNotice);
}
?>
