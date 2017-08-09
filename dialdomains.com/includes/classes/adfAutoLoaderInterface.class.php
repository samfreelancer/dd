<?php
/**
 * Autoloader Interface
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
interface adfAutoLoaderInterface {
    /**
     * Inform of whether or not the given class can be found
     * @param string class
     * @return bool
     */
    public function canLocate($class);

    /**
     * Get the path to the class
     * @param string class
     * @return string
     */
    public function getPath($class);
}