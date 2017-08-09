<?php 
/*
 * This is base class can be included in all cron file
 * them.
 */
include_once '../config.php';
include_once '../includes/classes/adfRegistry.class.php';
include_once '../includes/classes/adfDb.class.php';
include_once '../includes/classes/adfModelBase.class.php';
include_once '../includes/init.php';

abstract class baseCron {
    
    public function __construct() {
        
    }
}
?>