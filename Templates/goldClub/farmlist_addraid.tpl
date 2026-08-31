<?php

include __DIR__ . '/farmlist_add_farm_process.inc.tpl';

if (!$database->getVilFarmlist($session->uid) && empty($_POST['action'])) {
    // Allow page to render — list is auto-created on first add.
}

$showFarmEmptyMsg = false;
include __DIR__ . '/farmlist_add_farm_form.tpl';
