<?php
/**
 * Request Router Interface
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
interface adfRouterInterface {
    /**
     * Determine whether or not the route is available
     * @param string class
     * @return bool
     */
    public function hasRoute($app, $controller, $action, $is_sidebar);

    /**
     * Get the path to the class
     * @param string class
     * @return string
     */
    public function executeRoute($app, $controller, $action, $is_sidebar, $args);
}