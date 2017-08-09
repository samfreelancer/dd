<?php
class adfSubMenu {
    protected $_menu = array();
    
    public function __construct() {
        $this->registry = adfRegistry::getInstance();
    }

    public function addMenuItem($path, $text) {
        $this->_menu[] = array(
            'path'  => $path,
            'text'  => $text,
        );
    }

    public function display($template = 'default') {
        $route = $this->registry->route;

        if (empty($route['app'])) {
            unset($route['app']);
        }

        $currentPath = implode('/', $route);

        include lib_get_path('blocks') . "navigation/{$template}Template.php";
    }
}