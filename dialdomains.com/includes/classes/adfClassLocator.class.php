<?php
/**
 * A high performance class locator.
 *
 * Scans the specified directory for files matching #^([^/\.]+)\.class.php$#i
 * and caches the result to a file for increased performance.  A rescan
 * is automatically performed if a class does not exist in the cache file.
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
class adfClassLocator implements adfAutoLoaderInterface {
    /**
     * The current instance
     *
     * Contains the instance of adfClassLocator.
     *
     * @access private
     * @var object
     */
    private $_instance = false;

    /**
    * An array of classes.
    *
    * Contains class names as the keys and their associated path as the values.
    *
    * @access private
    * @var array
    */
    private $_classList = null;

    /**
    * Indicates whether a system scan has been performed.
    *
    * If a class cannot be found, a rescan will automatically be performed.
    * This will only happen once per instance since a single scan will locate
    * all classes.
    *
    * @access private
    * @var bool
    */
    private $_hasScanned = false;

    /**
    * Constructor
    *
    * @param string $cachePath The path to cache class list
    * @param string $scanPath The path to scan
    */
    public function __construct($cachePath, $scanPath, $recursive = true) {
        $this->scanPath         = $scanPath;
        $this->_recursive       = $recursive;
        $this->cacheFilePath    = $cachePath . 'class_cache_' . md5($this->scanPath);

        if (file_exists($this->cacheFilePath)) {
            $this->_classList = unserialize(file_get_contents($this->cacheFilePath));
        } else {
            $this->scanPath($scanPath);
        }
    }

    /**
    * Indicates whether a class can be found
    *
    * To test if a class can be located:
    *
    * <code>
    * $adfClassLocator = adfClassLocator::getInstance();
    *
    * if ($adfClassLocator->canLocate('className')) {
    *     # continue processing
    * }
    * </code>
    *
    * @param string $className The name of the class
    * @return bool true or false
    */
    public function canLocate($className) {
        $className = strtolower($className);

        if (!isset($this->_classList[$className]) && !$this->_hasScanned) {
            $this->scanPath($this->scanPath);
        }

        return isset($this->_classList[$className]);
    }

    /**
    * Get the path to a class
    *
    * Returns an absolute path to the given class name.
    *
    * <b>NOTE:</b> canLocate() should be called first to ensure the class is in
    * the $_classList array.  Existence of the class is not tested and will
    * result in a PHP Notice being thrown if it's not in the array.
    *
    * To get the path to a class:
    *
    * <code>
    * $adfClassLocator = adfClassLocator::getInstance();
    *
    * if ($adfClassLocator->canLocate('className')) {
    *     $path = $adfClassLocator->getPath('className');
    * }
    * </code>
    *
    * @param string $className The name of the class.
    * @return string The absolute path to the class.
    */
    public function getPath($className) {
        return $this->_classList[strtolower($className)];
    }

    /**
    * Populates the $_classList array.
    *
    * Performs a file system scan and calls writeCacheFile() which caches
    * the results to disk.
    */
    private function scanPath($scanPath) {
        $this->_hasScanned = true;
        $this->_classList = array();

        $this->_recursiveDirectoryScan($scanPath);

        $this->writeCacheFile();
    }

    /**
     * Caches the class list
     *
     * Saves the contents of $_classList to disk as a serialized array.
     */
    private function writeCacheFile() {
        $pathInfo = pathinfo($this->cacheFilePath);

        if (!is_writable($pathInfo['dirname']) || !file_put_contents($this->cacheFilePath, serialize($this->_classList))) {
            trigger_error("Cannot write class cache to '" . $this->cacheFilePath . "'.", E_USER_WARNING);
        }

        @chmod($this->cacheFilePath, 0666);
    }

    /**
     * Recursively scans the specified path.
     *
     * Finds all PHP files matching .*.class.php in a specific path.
     *
     * @param string $path The directory path to scan.
     */
    private function _recursiveDirectoryScan($path) {
        if (!is_dir($path) || !is_readable($path)) {
            return false;
        }

        $dh = opendir($path);

        while (false !== ($file = readdir($dh))) {
            if (is_dir("$path/$file") && !preg_match('#^\.*$#', $file)) {
                if ($this->_recursive) {
                    $this->_recursiveDirectoryScan("$path/$file");
                }
            } elseif (preg_match('#^([^/\.]+)\.class.php$#i', $file, $matches)) {
                $this->_classList[strtolower($matches[1])] = "$path/$file";
            } /*elseif (stripos($path, 'vendor') !== false) {
                if (preg_match('#^([^/\.]+)\.php$#i', $file, $matches)) {
                    echo $path.'/'.$file."<br />";
                    $this->_classList[strtolower($matches[1])] = "$path/$file";
                }
            }*/
        }

        closedir($dh);
    }
}
