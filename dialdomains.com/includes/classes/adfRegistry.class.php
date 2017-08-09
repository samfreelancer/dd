<?php
/**
 * Registry
 *
 * For assistance with this code, license inquiries or development services,
 * please contact Benjamin at net@p9b.org.
 *
 * @author      Benjamin Lewis <net@p9b.org>
 * @version     10.0.0
 * @license     Proprietary, Authorized Users Only
 * @copyright   Benjamin Lewis.  All Rights Reserved. Redistribution Prohibited.
 * @package     Registry
 */
class adfRegistry {
    private static $_instance = null;
    private static $_values = array();

    private function __construct() {
        self::$_instance = $this;
    }

    public function __get($key) {
        return (array_key_exists($key, self::$_values)) ? self::$_values[$key] : null;
    }

    public function __set($key, $value) {
        self::$_values[$key] = $value;
    }

    public static function getAll() {
        return self::$_values;
    }

    public static function getInstance() {
        return (self::$_instance === null) ? new adfRegistry() : self::$_instance;
    }

    public static function get($key) {
        return (array_key_exists($key, self::$_values)) ? self::$_values[$key] : null;
    }

    public static function set($key, $value) {
        self::$_values[$key] = $value;
    }
}