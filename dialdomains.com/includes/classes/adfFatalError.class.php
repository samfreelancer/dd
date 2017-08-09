<?php
class adfFatalError {
    public static function databaseConnectionFailed() {
        $buffer = new adfBuffer();
        $buffer->stop_all();

        header("HTTP/1.1 500 Internal Server Error");

        include SITE_BASE_PATH . '/templates/default/fatal.php';
        exit();
    }
}