<?php
/**
 * A collection of autoloaders
 *
 * For assistance with this code, license inquiries or development services,
 * please contact Benjamin at net@p9b.org.
 *
 * @author      Benjamin Lewis <net@p9b.org>
 * @version     10.0.0
 * @license     Proprietary, Authorized Users Only
 * @copyright   Benjamin Lewis.  All Rights Reserved. Redistribution Prohibited.
 * @package     ServiceLocator
 */
class adfAutoLoaderCollection {
    /**
     * Contains any attached service locators
     * @var array Locator
     */
    protected static $autoLoaders = array();

    /**
     * Attach a new type of locator
     * @param object Locator
     * @param string key
     */
    public static function addAutoLoader(adfAutoLoaderInterface $autoLoader, $loaderName) {
        self::$autoLoaders[$loaderName] = $autoLoader;
    }

    /**
     * Remove a locator that's been added
     * @param string key
     * @return object
     */
    public static function removeLoader($loaderName) {
        unset(self::$autoLoaders[$loaderName]);
        return $this;
    }

    /**
     * Check if a locator is currently loaded
     * @param string key
     * @return bool
     */
    public static function hasAutoLoader($loaderName) {
        return isset(self::$autoLoaders[$loaderName]);
    }

    /**
     * Check if a class can be loaded
     *
     * @param string $className The name of the class
     * @return bool true or false
     */
    public static function canLoad($className) {
        foreach (self::$autoLoaders as $autoLoader) {
            if ($autoLoader->canLocate($className)) {
                return true;
            }
        }
    }

    /**
     * Load in the required service by asking all service locators
     * @param string class
     */
    public static function load($className) {
        foreach (self::$autoLoaders as $autoLoader) {
            if ($autoLoader->canLocate($className)) {
                include $autoLoader->getPath($className);
            }
        }
    }
}