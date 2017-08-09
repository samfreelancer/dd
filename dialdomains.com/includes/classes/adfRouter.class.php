<?php
/**
 * A request router
 *
 * For assistance with this code, license inquiries or development services,
 * please contact Benjamin at net@p9b.org.
 *
 * @author      Benjamin Lewis <net@p9b.org>
 * @version     10.0.0
 * @license     Proprietary, Authorized Users Only
 * @copyright   Benjamin Lewis.  All Rights Reserved. Redistribution Prohibited.
 * @package     Router
 */
class adfRouter implements adfRouterInterface {
    public function hasRoute($app, $controller, $action, $is_sidebar = false) {
        $controller = "{$controller}Controller";
        $action     = "{$action}Action";

        try {
            $classFile = SITE_BASE_PATH . '/controllers/' . $app . '/' . $controller . '.class.php';

            if (!file_exists($classFile)) {
                throw new Exception("Controller {$controller} not found at path $classFile");
            }

            require_once $classFile;

            if (!class_exists($controller)) {
                throw new Exception("Controller $controller not defined in file $classFile");
            }

            if (!method_exists($controller, $action)) {
                throw new Exception("Action $action is not defined in controller $controller");
            }

            return true;
        }
        catch (Exception $e) {
            if ($is_sidebar) {
                trigger_error($e->getMessage(), E_USER_ERROR);
            }

            return false;
        }
    }

    public function executeRoute($app, $controller, $action, $is_sidebar = false, $args = null) {
        $pageSlag = strtolower($app . "_" . $controller . "_" .$action);
        $controller = "{$controller}Controller";
        $action     = "{$action}Action";
        
        $classFile = SITE_BASE_PATH . '/controllers/' . $app . '/' . $controller . '.class.php';

        # if there's an autorun file, execute it
        if (file_exists(SITE_BASE_PATH . '/controllers/' . $app . '/autorun.php')) {
            require SITE_BASE_PATH . '/controllers/' . $app . '/autorun.php';
        } else {
            #trigger_error(ROOT . '/controllers/' . $app . '/autorun.php not found.', E_USER_WARNING);
        }

        # include it and create an instance
        require_once $classFile;
        $instance = new $controller;

        $instance->setPageMetadata($pageSlag);

        call_user_func(array($instance, $action ) , $args );
        return true;
    }

}