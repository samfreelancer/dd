<?php
/**
 * Main Navigation Builder
 *
 * For assistance with this code, license inquiries or development services,
 * please contact Benjamin at net@p9b.org.
 *
 * @author      Benjamin Lewis <net@p9b.org>
 * @version     10.0.0
 * @license     Proprietary, Authorized Users Only
 * @copyright   Benjamin Lewis.  All Rights Reserved. Redistribution Prohibited.
 * @package     Main Navigation Builder
 * @todo        rewrite this class
 */
class adfMainNavigation {
	private static
        $_menu_items    = array(),
        $_submenu_items = array();

    public static function render($prefix = '/', $view = 'main.php') {
        $group_id = adfAuthentication::getCurrentPermissionGroupId();

        $groupPermissions = new groupPermissions();
        $authorized = $groupPermissions->getPermissionPathsAsArrayByGroupId($group_id);

        ksort(self::$_menu_items);

        $mainNav = array();
        foreach (self::$_menu_items as $priority => $meta) {
            foreach ($meta as $app => $navItem) {
                foreach ($navItem as $path => $nav) {
                    $mainNav[$app][$path] = $nav;
                    break(2);
                }
            }
        }

        self::$_menu_items = $mainNav;

        $mainNav = array();
        foreach (self::$_menu_items as $app => $nav) {
            if ($app != 'public' && $app != '' && !in_array($app, (array) $authorized) && $group_id != 3) {
                continue;
            }

            foreach ($nav as $path => $meta) {
                $mainNav[$meta['app']]["{$meta['controller']}/{$meta['action']}"] = $meta;
            }
        }

        $subNavTemp = array();

        foreach (self::$_submenu_items as $app => $nav) {
            if ($app != '' && !in_array($app, (array) $authorized) && $group_id != 3) {
                continue;
            }

            foreach ($nav as $meta) {
                $subNavTemp[$meta['parent']][$app]["{$meta['controller']}/{$meta['action']}"] = $meta;
            }
        }
        self::$_submenu_items = $subNavTemp;

        $subNav = array();
        foreach (self::$_submenu_items as $menuItems) {
            foreach ($menuItems as $app => $dropdown) {
                foreach ($dropdown as $meta) {
                    if (in_array($app, (array) $authorized) || $group_id == 3) {
                        $subNav[$meta['parent']][] = $meta;
                    }
                }
            }
        }

        foreach ($subNav as $parent => $items) {
            $subNav[$parent] = lib_mdarray_sort_asc($subNav[$parent], 'priority');
        }

        $tpl = adfTheme::getInstance();
        include $tpl->block('navigation', $view);
    }

    public static function currentApp() {
        $route = adfRegistry::get('route');
        return $route['app'];
    }

    public static function isCurrent($app, $class = 'current') {
        $route = adfRegistry::get('route');

        if ($route['app'] == $app) {
            echo $class;
        }
    }

    public static function addMenuItem($name, $controller, $action, $text, $priority, $app = '') {
        if (array_key_exists($priority, self::$_menu_items)) {
            $priority++;

            while (array_key_exists($priority, self::$_menu_items)) {
                $priority++;
            }
        }

        self::$_menu_items[$priority][$app]["$controller/$action"] = array(
            'name'          => $name,
            'text'          => $text,
            'priority'      => $priority,
            'controller'    => $controller,
            'action'        => $action,
            'app'           => $app,
        );
    }

    public static function removeMenuItem($controller, $action, $app = '_ALL_') {
        foreach (self::$_menu_items as $priority => $item) {
            if (!empty($item[$app]["$controller/$action"])) {
                unset(self::$_menu_items[$priority][$app]["$controller/$action"]);
            }
        }
    }

    public static function addSubMenuItem($main_name, $controller, $action, $text, $priority, $app = '') {
        self::$_submenu_items[$app][] = array(
            'parent'        => $main_name,
            'text'          => $text,
            'priority'      => $priority,
            'controller'    => $controller,
            'action'        => $action,
            'app'           => $app,
        );
    }

    public static function clearAllMenuItems() {
        self::$_menu_items    = array();
        self::$_submenu_items = array();
    }
}