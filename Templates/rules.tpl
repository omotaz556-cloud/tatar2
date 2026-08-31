<?php
#################################################################################
##  Filename       : rules.tpl                                                 ##
##  In-game game rules — all text via lang constants (ar.php / en.php)          ##
#################################################################################
?>

<style>
html[dir="rtl"] #rules,
html[dir="rtl"] #rules .rules,
html[dir="rtl"] body.contentPage .rules {
    direction: rtl;
    text-align: right;
}
html[dir="rtl"] #rules .rules,
html[dir="rtl"] body.contentPage .rules {
    margin-right: 0;
    margin-left: 0;
}
html[dir="rtl"] #rules .rules > li,
html[dir="rtl"] body.contentPage .rules > li {
    list-style: none;
    margin-bottom: 1em;
}
html[dir="rtl"] #rules .rules ul,
html[dir="rtl"] body.contentPage .rules ul {
    padding-right: 1.25em;
    padding-left: 0;
    margin-top: 0.5em;
}
html[dir="rtl"] #rules .rules ol,
html[dir="rtl"] body.contentPage .rules ol {
    padding-right: 1.75em;
    padding-left: 0;
    margin: 0.5em 0;
    list-style-type: decimal;
    list-style-position: outside;
}
html[dir="rtl"] #rules .rules ol li,
html[dir="rtl"] body.contentPage .rules ol li {
    margin-bottom: 0.35em;
}
</style>

<h3 class="pop popgreen bold"><?php echo GAME_RULES; ?></h3>
<div id="rules">
    <p>
        <?php echo PUBLIC_RULES_INTRO_1; ?>
        <br /><br />
        <?php echo PUBLIC_RULES_INTRO_2; ?>
    </p>
    <ul class="rules">
        <li>
            <strong style="color: #2A720B"><?php echo PUBLIC_RULES_SECTION_1; ?></strong>
            <br />
            <?php echo PUBLIC_RULES_ONE_ACCOUNT; ?>
            <ul>
                <li>
                    <strong style="color: #3BAE18"><?php echo PUBLIC_RULES_SECTION_1_1; ?></strong>
                    <br />
                    <?php echo PUBLIC_RULES_EMAIL_OWNER; ?>
                </li>
                <li>
                    <strong style="color: #3BAE18"><?php echo PUBLIC_RULES_SECTION_1_2; ?></strong>
                    <br />
                    <?php echo PUBLIC_RULES_PASSWORD_SAME_WORLD; ?>
                    <br /><br />
                    <?php echo PUBLIC_RULES_PASSWORD_OTHER_WORLD; ?>
                    <br /><br />
                    <?php echo PUBLIC_RULES_PASSWORD_DAMAGE; ?>
                </li>
                <li>
                    <strong style="color: #3BAE18"><?php echo PUBLIC_RULES_SECTION_1_3; ?></strong>
                    <br />
                    <?php echo PUBLIC_RULES_EMAIL_CHANGE; ?>
                </li>
                <li>
                    <strong style="color: #3BAE18"><?php echo PUBLIC_RULES_SECTION_1_4; ?></strong>
                    <br />
                    <?php echo PUBLIC_RULES_SAME_WORLD_TRANSFER; ?>
                    <ol>
                        <li><?php echo PUBLIC_RULES_WORLD_NAME; ?></li>
                        <li><?php echo PUBLIC_RULES_ACCOUNT_NICKNAME; ?></li>
                        <li><?php echo PUBLIC_RULES_NEW_OWNER_EMAIL; ?></li>
                    </ol>
                    <?php echo PUBLIC_RULES_PASSWORD_REQUEST_AFTER_TRANSFER; ?>
                </li>
            </ul>
        </li>
        <li>
            <strong style="color: #2A720B"><?php echo PUBLIC_RULES_SECTION_2; ?></strong>
            <br />
            <ul>
                <li>
                    <strong style="color: #3BAE18"><?php echo PUBLIC_RULES_SECTION_2_1; ?></strong>
                    <br />
                    <?php echo PUBLIC_RULES_SITTERS; ?>
                    <br />
                    <?php echo PUBLIC_RULES_SITTER_LOGIN; ?>
                    <br />
                    <?php echo PUBLIC_RULES_SITTER_DAMAGE; ?>
                </li>
                <li>
                    <strong style="color: #3BAE18"><?php echo PUBLIC_RULES_SECTION_2_2; ?></strong>
                    <br />
                    <?php echo PUBLIC_RULES_SHARED_COMPUTER; ?>
                </li>
            </ul>
        </li>
        <li>
            <strong style="color: #2A720B"><?php echo PUBLIC_RULES_SECTION_3; ?></strong>
            <br />
            <?php echo PUBLIC_RULES_BROWSER; ?>
        </li>
        <li>
            <strong style="color: #2A720B"><?php echo PUBLIC_RULES_PROGRAM_ERRORS_HEADING; ?></strong>
            <br />
            <?php echo PUBLIC_RULES_BUGS; ?>
        </li>
        <li>
            <strong style="color: #2A720B"><?php echo PUBLIC_RULES_SECTION_5; ?></strong>
            <br />
            <?php echo PUBLIC_RULES_REAL_MONEY; ?>
        </li>
        <li>
            <strong style="color: #2A720B"><?php echo PUBLIC_RULES_SECTION_6; ?></strong>
            <br />
            <?php echo PUBLIC_RULES_POLITE_COMMUNICATION; ?>
            <ol>
                <li>
                    <?php echo PUBLIC_RULES_BEHAVIOUR_INTRO; ?>
                    <br />
                    <?php echo PUBLIC_RULES_DEFAMATORY; ?>
                    <br />
                    <?php echo PUBLIC_RULES_UNDERAGE; ?>
                    <br />
                    <?php echo PUBLIC_RULES_BLACKMAIL; ?>
                    <br />
                    <?php echo PUBLIC_RULES_DISPLAY_REPORTS; ?>
                </li>
                <li><?php echo PUBLIC_RULES_NO_POLITICS; ?></li>
                <li><?php echo PUBLIC_RULES_LANGUAGE; ?></li>
                <li><?php echo PUBLIC_RULES_IMPERSONATION; ?></li>
                <li><?php echo PUBLIC_RULES_ADVERTISING; ?></li>
            </ol>
        </li>
        <li>
            <strong style="color: #2A720B"><?php echo PUBLIC_RULES_SECTION_7; ?></strong>
            <br />
            <?php echo PUBLIC_RULES_PUNISHMENT; ?>
            <br />
            <?php echo PUBLIC_RULES_NO_REPLACEMENT; ?>
            <br />
            <?php echo PUBLIC_RULES_NO_SPECIAL_TREATMENT; ?>
            <br /><br />
            <?php echo PUBLIC_RULES_APPEALS; ?>
            <br />
            <?php echo PUBLIC_RULES_OWNER_INFORMATION; ?>
            <br /><br />
            <?php echo PUBLIC_RULES_MULTI_DELETE; ?>
        </li>
        <li>
            <strong style="color: #2A720B"><?php echo PUBLIC_RULES_SECTION_8; ?></strong>
            <br />
            <?php echo PUBLIC_RULES_CHANGE_ANY_TIME; ?>
        </li>
        <li>
            <strong style="color: #2A720B"><?php echo PUBLIC_RULES_SECTION_9; ?></strong>
            <br />
            <?php echo PUBLIC_RULES_SEVERABILITY; ?>
        </li>
        <li>
            <strong style="color: #2A720B"><?php echo PUBLIC_RULES_SECTION_10; ?></strong>
            <br />
            <?php echo PUBLIC_RULES_SECTION_10_BODY; ?>
        </li>
    </ul>
</div>
