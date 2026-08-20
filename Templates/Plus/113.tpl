<?php

#################################################################################
##                -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-               ##
## --------------------------------------------------------------------------- ##
##  Filename       : 113.tpl                                                   ##
##  Type           : Plus - Gold Club Result                                   ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  URLs:          : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : Novaterra Project                                          ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.              ##
## --------------------------------------------------------------------------- ##
#################################################################################

include("Templates/Plus/pmenu.tpl");

$uid = (int)$session->uid;
$gold = defined('PLUS_PACKAGE_D_GOLD') ? PLUS_PACKAGE_D_GOLD : 1000;
$price = defined('PLUS_PACKAGE_D_PRICE') ? str_replace(',', '.', PLUS_PACKAGE_D_PRICE) : '19.99';
$currency = defined('PAYPAL_CURRENCY') ? PAYPAL_CURRENCY : 'EUR';
$paypal = (defined('PAYPAL_EMAIL') && PAYPAL_EMAIL !== '@') ? PAYPAL_EMAIL : ADMIN_EMAIL;
$base = rtrim(HOMEPAGE,'/');
?>
<table class="rate_details" cellpadding="1" cellspacing="1">
    <thead><tr><th colspan="2"><?php echo TZ_PAYPAL_PACKAGE_D; ?></th></tr></thead>
    <tbody>
    <tr>
        <td class="pic">
            <img src="img/bezahlung/payment_generic.png" style="width:99px;height:99px;" alt="<?php echo PACKAGE_D; ?>">
            <div>Gold: <?= $gold ?><br>Cost: <?= $price ?> <?= $currency ?><br><?php echo TZ_WAIT_INSTANT; ?></div>
        </td>
        <td class="desc">
            <?php echo TZ_PAY_SECURELY_WITH_PAYPAL; ?><br><br>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="business" value="<?= $paypal ?>">
                <input type="hidden" name="item_name" value="<?= SERVER_NAME ?> Package D">
                <input type="hidden" name="item_number" value="D-<?= $gold ?>">
                <input type="hidden" name="amount" value="<?= $price ?>">
                <input type="hidden" name="currency_code" value="<?= $currency ?>">
                <input type="hidden" name="custom" value="<?= $uid ?>|113|<?= $gold ?>">
                <input type="hidden" name="invoice" value="TX-<?= $uid ?>-<?= time() ?>">
                <input type="hidden" name="notify_url" value="<?= $base ?>/paypal_ipn.php">
                <input type="hidden" name="return" value="<?= $base ?>/plus.php?id=1&paid=1">
                <input type="hidden" name="cancel_return" value="<?= $base ?>/plus.php?id=1">
                <input type="image" src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_107x26.png" name="submit" alt="<?php echo BUY_NOW; ?>">
            </form>
        </td>
    </tr>
    </tbody>
</table>
</div></div>