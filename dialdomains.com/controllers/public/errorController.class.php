<?php
class errorController extends adfBaseController {
    public function errorNoRouteAction() {
        header("HTTP/1.1 500 Internal Server Error");
        include $this->tpl->page('errors/500.php');
    }

    public function error404Action() {
        header("HTTP/1.0 404 Not Found");
        include $this->tpl->page('errors/404.php');
    }

    public function forbiddenAction() {
        header("HTTP/1.0 403 Forbidden");
        include $this->tpl->page('errors/403.php');
    }
}