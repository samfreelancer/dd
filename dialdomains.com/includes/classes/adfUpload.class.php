<?php
class adfUpload {
    private $_error_message = null;
    private $config = array(
        'MAX_UPLOAD_SIZE'    => 0,
        'OVERWRITE_IF_EXIST' => false,
        'ALLOWED_EXTENSIONS' => array(),
        'BLOCKED_EXTENSIONS' => array(),
        'DEFAULT_CHMOD'      => 0666,
        'LOWERCASE_EXT'      => false,
        'FORCE_ERROR_MSG'    => false
    );

    public function set_option($key, $value) {
        if (isset($this->config[$key])) {
            $this->config[$key] = $value;
            return true;
        } else {
            return false;
        }
    }

    public function save_file($file, $destination, $new_file_name = null) {

        $this->file            = $file;
        $this->destination     = rtrim($destination, '/') . '/';
        $this->new_file_name   = $new_file_name;

        try {
            $this->check_destination();
            $this->check_file();
            $this->move_file();
            $this->set_permissions();
            return $this->file_name;
        } catch (Exception $e) {
            if ($this->config['FORCE_ERROR_MSG'] == true) {
                echo $e->getMessage();
                exit();
            }

            if ($e->getCode() == 0) {
                $this->_error_message = $e->getMessage();
                return false;
            } else {
                trigger_error($e->getMessage(), E_USER_WARNING);
                $this->_error_message = 'An internal error has occurred.  Please try again later.';
                return false;
            }
        }
    }

    public function get_error() {
        return $this->_error_message;
    }

    private function set_permissions() {
        return chmod($this->destination . $this->file_name, $this->config['DEFAULT_CHMOD']);
    }

    private function move_file() {
        $old = error_reporting(0);

        if (!move_uploaded_file($_FILES[$this->file]['tmp_name'], $this->save_path)) {
            throw new Exception("function move_uploaded_file returned false while trying to move a file from '{$_FILES[$this->file]['tmp_name']}' to '{$this->save_path}'", 1);
        }

        error_reporting($old);
    }

    private function check_destination() {
        if (!is_dir($this->destination)) {
            throw new Exception("The destination '{$this->destination}' does not appear to be a directory.", 1);
        }

        if (!is_writable($this->destination)) {
            throw new Exception("The destination '{$this->destination}' is not writable by user " . get_current_user() . '.', 1);
        }

    }

    private function set_file_info() {
        $this->file_info = pathinfo($_FILES[$this->file]['name']);

        if ($this->new_file_name !== null) {
            $this->file_name = $this->new_file_name;
        } else {
            if ($this->config['LOWERCASE_EXT']) {
                $ext = strtolower($this->file_info['extension']);
            } else {
                $ext = $this->file_info['extension'];
            }

            $this->file_name = $this->file_info['filename'] . ".$ext";
        }


        $this->save_path = $this->destination . $this->file_name;
    }

    private function check_file() {
        // is there a file where we are looking for it?
        if (!isset($_FILES[$this->file])) {
            throw new Exception("\$_FILES['{$this->file}'] is not populated", 1);
        }

        $this->set_file_info();

        // were there any upload errors?
        if ($_FILES[$this->file]['error'] != 0) {
            switch ($_FILES[$this->file]['error']) {
                case 1:
                    throw new Exception('Uploaded files cannot be greater than ' . number_format(ini_get('upload_max_filesize')) . ' bytes.');
                    break;
                case 2:
                    // this data comes from the user, do not trust it.
                    throw new Exception('The uploaded file is too large. Please try uploading a smaller file.');
                    break;
                case 3:
                    throw new Exception('A network error has caused your upload to be only partially received.  Please try again.');
                    break;
                case 4:
                    throw new Exception('An uploaded file was not received.  Please try again.');
                    break;
                case 6:
                    throw new Exception('A temporary folder for saving uploads does not exist.  Please configure a temporary folder in your php.ini file.', 1);
                    break;
                case 7:
                    throw new Exception('An uploaded file could not be written to disk.  Please verify permissions and free disk space.', 1);
                    break;
                case 8:
                    throw new Exception("Files of the extension {$this->file_info['extension']} have been disallowed.");
                    break;
                default:
                    throw new Exception("An unknown error of type {$_FILES[$this->file]['error']} has occurred during a file upload.");
            }
        }

        // is the file too large?
        if ($this->config['MAX_UPLOAD_SIZE'] > 0 && ($_FILES[$this->file]['size'] > $this->config['MAX_UPLOAD_SIZE'])) {
            throw new Exception('Uploaded files cannot be greater than ' . number_format($_FILES[$this->file]['size']) . ' bytes.');
        }

        // does the file already exist?
        if (!$this->config['OVERWRITE_IF_EXIST'] && file_exists($this->save_path)) {
            throw new Exception("The file {$this->file_name} already exists on the server.  Please rename the file.");
        } elseif (file_exists($this->save_path) && !is_writable($this->save_path)) {
          throw new Exception("The file {$this->file_name} cannot be overwritten.  Please rename the file.");
        }

        // does the file have an allowed extension..
        if (count($this->config['ALLOWED_EXTENSIONS']) > 0 && !in_array(strtolower($this->file_info['extension']), array_map('strtolower', $this->config['ALLOWED_EXTENSIONS']))) {
            throw new Exception("The file extension {$this->file_info['extension']} is not an allowed upload type.");
        }

        // does the file have a blocked extension..
        if (count($this->config['BLOCKED_EXTENSIONS']) > 0 && (in_array($this->file_info['extension'], $this->config['BLOCKED_EXTENSIONS']))) {
            throw new Exception("The file extension {$this->file_info['extension']} is not an allowed upload type.");
        }
    }
}