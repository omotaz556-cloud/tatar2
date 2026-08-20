<?php

#################################################################################
##                -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-               ##
## --------------------------------------------------------------------------- ##
##  Filename       : 9.tpl                                                     ##
##  Type           : Report Loader - Archived Report Dispatcher                ##
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

// ======================== SAFE INPUT ========================
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// If no valid ID, stop safely (prevents warnings / injection edge cases)
if ($id <= 0) {
    return;
}

// ======================== GET TEMPLATE ========================
// NOTE: archive field defines which tpl file is loaded
$template = $database->getNotice2($id, 'archive');

// Safety: ensure valid string before include
if (!empty($template)) {
    include($template . ".tpl");
}