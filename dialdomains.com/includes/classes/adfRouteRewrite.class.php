<?php
class adfRouteRewrite {
    private static $_instance = false;

    private function __construct() {
        self::$_instance = $this;
        $this->db = new adfDb(adfRegistry::get('DB_LINK'));

        $this->cache = new adfCache(adfRegistry::get('NAME_REWRITE_CACHE'), adfRegistry::get('REWRITE_CACHE_EXP'), adfRegistry::get('PATH_REWRITE_CACHE'));

        if (false == $this->rewrites = $this->cache->get()) {
            if (false == $this->rewrites = $this->db->getRecords("SELECT match_in, rewrite_in, match_out, rewrite_out FROM routes WHERE active = 'active' ORDER BY sort_order ASC")) {
                $this->rewrites = array();
            }
            $this->cache->save($this->rewrites);
        }
    }

    public static function getInstance() {
        return (self::$_instance === false) ? new adfRouteRewrite() : self::$_instance;
    }

    public function getInternalRoute($request, $do301 = true) {
        foreach ($this->rewrites as $route) {
            if (preg_match('#' . $route['match_out'] . '#', $request)) {
                $request = preg_replace('#' . $route['match_out'] . '#', $route['rewrite_out'], $request);

                if ($do301) {
                    lib_redirect($request);
                }

                return $request;
            }

            if (preg_match('#' . $route['match_in'] . '#', $request)) {
                return preg_replace('#' . $route['match_in'] . '#', $route['rewrite_in'], $request);
            }
        }

        return $request;
    }

    public function getExternalRoute($request) {
        foreach ($this->rewrites as $route) {
            if (preg_match('#' . $route['match_out'] . '#', $request)) {
                return preg_replace('#' . $route['match_out'] . '#', $route['rewrite_out'], $request);
            }
        }

        return $request;
    }

    public static function getSystemPath($request, $do301 = true) {
        static $cache = null;
        static $self  = null;

        $changed = false;

        if (is_null($cache)) {
            $cache = new adfCache(adfRegistry::get('NAME_REWRITE_IN'), adfRegistry::get('REWRITE_CACHE_EXP'), adfRegistry::get('PATH_REWRITE_CACHE'));
        }

        if (false == $rewrites = $cache->get()) {
            $rewrites = array();
            $changed = true;
        }

        if (array_key_exists($request, $rewrites)) {
            $path = $rewrites[$request];
        } else {
            if (is_null($self)) {
                $self = adfRouteRewrite::getInstance();
            }

            $path = $self->getInternalRoute($request, $do301);

            $rewrites[$request] = $path;
            $changed            = true;
        }

        if ($changed) {
            $cache->save($rewrites);
        }

        return $path;
    }

    public static function getLink($request) {
        static $cache = null;
        static $self  = null;

        $changed = false;

        if (is_null($cache)) {
            $cache = new adfCache(adfRegistry::get('NAME_REWRITE_OUT'), adfRegistry::get('REWRITE_CACHE_EXP'), adfRegistry::get('PATH_REWRITE_CACHE'));
        }

        if (false == $rewrites = $cache->get()) {
            $rewrites = array();
            $changed = true;
        }

        if (array_key_exists($request, $rewrites)) {
            $path = $rewrites[$request];
        } else {
            if (is_null($self)) {
                $self = adfRouteRewrite::getInstance();
            }

            $path = $self->getExternalRoute($request);

            $rewrites[$request] = $path;
            $changed            = true;
        }

        if ($changed) {
            $cache->save($rewrites);
        }

        return $path;
    }

}