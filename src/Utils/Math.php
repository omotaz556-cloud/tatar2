<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Project:       Novaterra                                                    ##
##  Filename       Math.php                                                    ##
##  Developed by:  martinambrus                                                ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2017; base engine (c) TravianZ authors (GPLv3). ##
##  URLs:          https://novaterra.martinambrus.com                		       ##
##  Source code:   https://github.com/omotaz556-cloud/tatar		                ##
##                                                                             ##
#################################################################################

namespace App\Utils;

/**
 *
 * Mathematics-related helpers.
 *
 * @author martinambrus
 *
 */
class Math {

    public static function isInt($val) {
        return (is_numeric($val) && intval($val) === $val);
    }

    public static function isFloat($val) {
        return (is_numeric($val) && floatval($val) === $val);
    }

}