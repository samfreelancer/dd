<?php
/**
 * Sidebar Builder
 *
 * For assistance with this code, license inquiries or development services,
 * please contact Benjamin at net@p9b.org.
 *
 * @author      Benjamin Lewis <net@p9b.org>
 * @version     10.0.0
 * @license     Proprietary, Authorized Users Only
 * @copyright   Benjamin Lewis.  All Rights Reserved. Redistribution Prohibited.
 * @package     Sidebar Builder
 */
class adfSidebar {
	private static $_sidebar_items    = array();
  private static $_sidebar_includes = array();

    public static function addSidebarItem($route, $app = '_ALL_', $controller = '_ALL_', $action = '_ALL_', $priority = 100) {
        self::$_sidebar_items[$app][$controller][$action] = array(
            'route'     => $route,
            'priority'  => $priority,
        );
    }

    public static function addSidebarInclude($path, $order = 0) {
        self::$_sidebar_includes[$path] = $order;
    }

    public static function getSidebarIncludes() {
        if (count(self::$_sidebar_includes) < 1) {
            return false;
        }

        asort(self::$_sidebar_includes);
        return self::$_sidebar_includes;
    }

    public static function hasSidebarItems() {
        $registry = adfRegistry::getInstance();

        if (count(self::$_sidebar_includes) > 0) {
            return true;
        }

//        # are there any sidebars assigned to every page?
        #echo lib_debug(self::$_sidebar_items);
        if (isset(self::$_sidebar_items['_ALL_']['_ALL_']['_ALL_']) && count(self::$_sidebar_items['_ALL_']['_ALL_']['_ALL_']) > 0) {
            return true;
        }

        # are there any sidebars assigned to all routes in this app?
        if (isset(self::$_sidebar_items[$registry->route['app']]['_ALL_']['_ALL_']) && count(self::$_sidebar_items[$registry->route['app']]['_ALL_']['_ALL_']) > 0) {
            return true;
        }

        # are there any sidebars assigned to tall actions in the current controller in the current app?
        if (isset(self::$_sidebar_items[$registry->route['app']][$registry->route['controller']]['_ALL_']) && count(self::$_sidebar_items[$registry->route['app']][$registry->route['controller']]['_ALL_']) > 0) {
            return true;
        }

        # are there any sidebars assigned to the current action in the current controller in the current app?
        if (isset(self::$_sidebar_items[$registry->route['app']][$registry->route['controller']][$registry->route['action']]) && count(self::$_sidebar_items[$registry->route['app']][$registry->route['controller']][$registry->route['action']]) > 0) {
            return true;
        }

        return false;
    }

    public static function render() {
        if (!adfSidebar::hasSidebarItems() || count(self::$_sidebar_includes) > 0) {
            return false;
        }

        $router             = new adfRouter();
        $registry           = adfRegistry::getInstance();
        $sidebarRoutes      = array();
        $output             = '';
        
        foreach (self::$_sidebar_items as $app => $appArray) {
            if (in_array($app, array('_ALL_', $registry->route['app']))) {
                foreach ($appArray as $controller => $controllerArray) {
                    if (in_array($controller, array('_ALL_', $registry->route['controller']))) {
                        foreach ($controllerArray as $action => $actionArray) {
                            if (in_array($action, array('_ALL_', $registry->route['action']))) {
                                $sidebarRoutes[self::$_sidebar_items[$app][$controller][$action]['route']] = self::$_sidebar_items[$app][$controller][$action]['priority'];
                            }
                        }
                    }
                }
            }
        }


        if (count($sidebarRoutes) > 0) {
            asort($sidebarRoutes);
            foreach ($sidebarRoutes as $route => $priority) {
                $output .= $router->routeRequest('sidebar/' . $route, true, true);
            }
        }

        return $output;
    }

    public static function clearSidebar() {
        self::$_sidebar_items = array();
    }

    public static function clearSidebarIncludes() {
        self::$_sidebar_includes = array();
    }

    public static function removeSidebarInclude($path) {
        unset(self::$_sidebar_includes[$path]);
    }

    public static function removeSidebarItem($route, $app = '_ALL_', $controller = '_ALL_', $action = '_ALL_') {
        if (isset(self::$_sidebar_items[$app][$controller][$action])) {
            if (is_array(self::$_sidebar_items[$app][$controller][$action])) {
                #foreach (self::$_sidebar_items[$app][$controller][$action] as $k => $sidebar) {
                    if (self::$_sidebar_items[$app][$controller][$action]['route'] == $route) {
                        unset(self::$_sidebar_items[$app][$controller][$action]);
                    }
                #}
            }
        }
    }
}