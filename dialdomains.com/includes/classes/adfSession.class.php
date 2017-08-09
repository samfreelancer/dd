<?php
class adfSession {
    private static $_session_adaptor;

    public function __construct() {

    }

    public static function startSession($session_name, adfSessionAdaptor $adaptor) {
    	# set the session lifetime in seconds
        self::_set_save_handler();
        self::$_session_adaptor = $adaptor;

        session_name($session_name);

        # private disallows caching by proxies and permits the client to cache the contents.
        ini_set('session.cache_limiter', 'nocache');

        # start the session
        session_start();
    }

    private static function _set_save_handler() {
        session_set_save_handler(
            array('adfSession', 'open'),
            array('adfSession', 'close'),
            array('adfSession', 'read'),
            array('adfSession', 'write'),
            array('adfSession', 'destroy'),
            array('adfSession', 'gc')
        );
    }

    public static function open() {
        return true;
    }

    public static function read($id) {
    	return self::$_session_adaptor->read("sessions/$id");
    }

    public static function write($id, $data) {
        return self::$_session_adaptor->write("sessions/$id", $data);
    }

    public static function destroy($id) {
        return self::$_session_adaptor->destroy("sessions/$id");
    }

    public static function gc() {
        return self::$_session_adaptor->gc();
    }

    public static function close() {
        return true;
    }

    public function __destruct() {
        session_write_close();
    }
}