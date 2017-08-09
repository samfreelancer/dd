<?php
class adfStatus {
    private $error = array();
    private $message = array();
    private static $_instance = false;

    private function __construct() {
        self::$_instance = $this;

        if (isset($_SESSION['ERROR_MESSAGES'])) {
            $this->error = unserialize($_SESSION['ERROR_MESSAGES']);
            unset($_SESSION['ERROR_MESSAGES']);
        }

        if (isset($_SESSION['SUCCESS_MESSAGES'])) {
            $this->message = unserialize($_SESSION['SUCCESS_MESSAGES']);
            unset($_SESSION['SUCCESS_MESSAGES']);
        }
    }

    public static function getInstance() {
        return (self::$_instance === false) ? new adfStatus() : self::$_instance;
    }

    public function error($error_data) {
        if (is_array($error_data)) {
            foreach ($error_data as $error) if (!empty($error)) $this->error[] = $error;
        } elseif (!empty($error_data)) {
            $this->error[] = $error_data;
        }
    }

    public function message($message) {
        $this->message[] = $message;
    }

    public function reset() {
        $this->error = $this->message = array();
    }

    public function display() {
        ob_start();
        echo $this->_render_error($this->error);
        echo $this->_render_message($this->message);
        $messages = ob_get_contents();
        ob_end_clean();
        $this->reset();
        return $messages;
    }

    public function instant_error($mixed_error) {
        return $this->_render_error($mixed_error);
    }

    public function instant_message($mixed_message) {
        return $this->_render_message($mixed_message);
    }

    public function _prepare_message($m) {
        $replacements = array(
            "\n"    => '',
            "\r"    => '',
            "'"     => '&#39;',
            '"'     => '&#34;',
        );

        return str_replace(array_keys($replacements), $replacements, $m);
    }

    private function _render_message($mixed_message) {
        $message_str = (is_array($mixed_message)) ? (implode("</p><p>", $mixed_message)) : $mixed_message;

        if (!empty($message_str)) {
            return '<div class="row-fluid">
                      <div class="alert alert-success">
                        <button class="close" data-dismiss="alert"></button>'
                        . $this->_prepare_message($message_str) .
                    ' </div>
                    </div>';
        } else {
            return null;
        }
    }

    private function _render_error($mixed_error) {
        $error_str = (is_array($mixed_error)) ? (implode("</p><p>", $mixed_error)) : $mixed_error;

        if (!empty($error_str)) {
            return '<div class="row-fluid">
                      <div class="alert alert-danger">
                        <button class="close" data-dismiss="alert"></button>'
                        .  $this->_prepare_message($error_str) .
                    ' </div>
                    </div>';
        } else {
            return null;
        }
    }

    public function __destruct() {
        if (count($this->error) > 0) $_SESSION['ERROR_MESSAGES'] = serialize($this->error);
        if (count($this->message) > 0) $_SESSION['SUCCESS_MESSAGES'] = serialize($this->message);
    }
}