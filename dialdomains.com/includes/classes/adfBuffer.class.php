<?php
class adfBuffer {
    private static $open_buffers = 0;

    public function __construct() {
    }

    public function start($callback = null) {
        ob_start($callback);
        self::$open_buffers++;
    }

    public function restart() {
        $this->stop_all();
        $this->start();
    }

    public function render() {
        if (self::$open_buffers > 0) {
            ob_end_flush();
            self::$open_buffers--;
        }
    }

    public function read() {
        return ob_get_contents();
    }

    public function stop() {
        if (self::$open_buffers > 0) {
            $contents = $this->read();
            ob_end_clean();
            self::$open_buffers--;
            return $contents;
        }
    }

    public function stop_all() {
        while (self::$open_buffers > 0) {
            $this->stop();
        }
    }

    public function __destruct() {
        $this->stop_all();
    }
}