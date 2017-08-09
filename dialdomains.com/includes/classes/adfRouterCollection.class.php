<?php
/**
 * A collection of routers
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
class adfRouterCollection {
    /**
     * Contains any attached service locators
     * @var array Locator
     */
    protected static
        $routers        = array(),
        $_route         = null,
        $_appDir        = 'public',
        $_controller    = 'default',
        $_action        = 'index',
        $_args          = null,
        $_fbController  = 'error',
        $_fbAction      = 'error404',
        $_depth         = 0,
        $_debug_mode    = false;

    protected static function _setControllerAndAction($request) {
        self::$_route = explode('/', $request);

        if (count(self::$_route) > 2) {
            if (empty(self::$_route[1])) {
                self::$_route[1] = self::$_controller;
            }

            if (empty(self::$_route[2])) {
                self::$_route[2] = self::$_action;
            }

            self::$_appDir      = self::$_route[0];
            self::$_controller  = self::$_route[1];
            self::$_action      = self::$_route[2];

            if (count(self::$_route) > 3) {
                self::$_args = array_slice(self::$_route, 3);
            }
        } else {
            if (empty(self::$_route[0])) {
                self::$_route[0] = self::$_controller;
            }

            if (empty(self::$_route[1])) {
                self::$_route[1] = self::$_action;
            }

            self::$_appDir      = 'public';
            self::$_controller  = self::$_route[0];
            self::$_action      = self::$_route[1];
        }

        if (self::$_debug_mode) {
            echo lib_debug('Set Controller: ' . self::$_controller . ' Action: ' . self::$_action);
        }
    }

    /**
     * Attach a new type of router
     * @param object Router
     * @param string key
     */
    public static function addRouter(adfRouterInterface $router, $name) {
        self::$routers[$name] = $router;
    }

    /**
     * Remove a router that's been added
     * @param string key
     * @return object
     */
    public static function removeRouter($name) {
        unset(self::$routers[$name]);
        return $this;
    }

    /**
     * Check if a router is currently loaded
     * @param string key
     * @return bool
     */
    public static function hasRouter($name) {
        return isset(self::$routers[$name]);
    }

    public static function isAuthorized($app, $is_sidebar) {
        $status = adfStatus::getInstance();

        if (!$is_sidebar && !empty($app) && $app != 'public') {
            $perm = new permission();
            if (!$perm->getByApp($app)) {
                $data = array(
                    'app' => $app,
                    'name' => $app,
                    'description' => "Allows access to the $app application",
                );

                if (!$perm->add($data)) {
                    $status->error($perm->getError());
                }
            }

            # check permissions
            if (!adfAuthentication::currentUserCan($app)) {
                if (adfAuthentication::isLoggedIn()) {
                    lib_redirect(adfAuthentication::get401Path());
                } else {
                    if (!lib_is_ajax_request()){
                        $_SESSION['LOGIN_REDIRECT'] = $_SERVER['REQUEST_URI'];
                    }
                    lib_redirect(adfAuthentication::getLoginPath());
                }

                return false;
            }
        }

        return true;
    }

    public static function routeRequest($request, $returnOutput = false, $is_sidebar = false, $route404 = true) {
        self::$_depth++;

        if (self::$_depth >= 10) {
            trigger_error('Maximum recursion depth reached in adfRouterCollection', E_USER_ERROR);
        }

        if (self::$_debug_mode) {
            echo lib_debug("Routing request for $request");
        }

        adfRouterCollection::_setControllerAndAction(adfRouteRewrite::getSystemPath($request));
        adfRegistry::set('CURRENT_PAGE_REQUEST', $request);

        foreach (self::$routers as $name => $route) {
            if (self::$_debug_mode) {
                echo lib_debug("Testing route: " . self::$_appDir . '/' . self::$_controller . '/' . self::$_action . " - Using Router $name");
            }

            if ($route->hasRoute(self::$_appDir, self::$_controller, self::$_action, $is_sidebar)) {
                if (self::$_debug_mode) {
                    echo lib_debug("Found route: " . self::$_appDir . '/' . self::$_controller . '/' . self::$_action . " - Using Router $name");
                }


                if (!adfRouterCollection::isAuthorized(self::$_appDir, $is_sidebar)) {
                    return;
                }

                # tell the registry what app, controller and action are being executed
                $argsString = '' ;

                if (is_array(self::$_args) && count(self::$_args) > 0) {
                    $argsString  = '/' . implode('/', self::$_args);
                }

                $rData = array(
                    'resource'      => str_replace('/', '', self::$_appDir) . '/' . self::$_controller . '/' . self::$_action . $argsString,
                    'app'           => str_replace('/', '', self::$_appDir),
                    'controller'    => self::$_controller,
                    'action'        => self::$_action,
                    'args'          => self::$_args
                );

                adfRegistry::set('route', $rData);

                if ($returnOutput) {
                    $buffer = new adfBuffer();
                    $route->executeRoute(self::$_appDir, self::$_controller, self::$_action, $is_sidebar , self::$_args);
                    $content = $buffer->stop();
                    return $content;
                } else {
                    return $route->executeRoute(self::$_appDir, self::$_controller, self::$_action, $is_sidebar , self::$_args);
                }
            }
        }

        # 404 at this point, no routers found the requested resource
        if ($route404){
            self::routeRequest(ltrim(adfAuthentication::get404Path(), '/'), $returnOutput, $is_sidebar, false);
        } else {
            // routing 404 failed, so there's basic and ugly 404
            header('HTTP/1.0 404 Not Found');
            echo "Error 404 - this page does not exist";
            return;
        }
        
    }
}