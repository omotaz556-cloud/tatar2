<?php

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : PALACE MENU                                               ##
##  Type           : BUILDING TEMPLATE                                         ##
## --------------------------------------------------------------------------- ##
##  Refactored by  : Shadow                                                    ##
##  Redesign by    : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  Test Server    : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : Novaterra Project                                          ##
##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.              ##
## --------------------------------------------------------------------------- ##
#################################################################################

global $id;

$current = $_GET['s'] ?? '';
$menu = [
    ''  => TRAIN,
    '2' => CULTURE_POINTS,
    '3' => LOYALTY,
    '4' => EXPANSION,
];
?>
<div id="textmenu">
<?php 
$first = true;
foreach ($menu as $s => $label):
    if (!$first) echo ' | ';
    $first = false;
    
    $url = 'build.php?id='.(int)$id.($s !== '' ? '&amp;s='.$s : '');
    $selected = ($current === (string)$s) ? ' class="selected"' : '';
?>
    <a href="<?php echo $url;?>"<?php echo $selected;?>><?php echo $label;?></a>
<?php endforeach; ?>
</div>