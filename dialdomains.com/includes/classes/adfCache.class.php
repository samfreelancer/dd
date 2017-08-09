<?php
class adfCache {
    private
        $_resource_path,
        $_timeout;

    /**
     * Initializes the caching object
     *
     * @param string $resource The name of the cache object
     * @param int $timeout The time in seconds to cache the object for
     * @param string $base_path The path to store cache data
     */
    public function __construct($resource, $timeout = 600, $base_path = null) {
        if (is_null($base_path)) {
            $path = adfRegistry::get('SITE_BASE_PATH') . '/cache/';
        } else {
            $path = $base_path;
        }

        $this->_resource_path   = $path . md5($resource);
        $this->_timeout         = $timeout;
    }

    /**
     * Saves the data to cache
     *
     * @param mixed $data The data to store
     * @return bool True or False
     */
    public function save($data) {
        if (!file_put_contents($this->_resource_path, json_encode(array('data'  => $data)))) {
            return false;
        }

        @chmod($this->_resource_path, adfRegistry::get('NEW_FILE_CHMOD'));

        return true;
    }

    /**
     * Retrieve the cache data
     *
     * @return mixed The cache data or false if it doesn't exist or has expired
     */
    public function get() {
        if ($this->isNotExpired()) {
            $store = json_decode(file_get_contents($this->_resource_path), true);
            return $store['data'];
        } else {
            return false;
        }
    }

    /**
     * Determine whether there is data cached or not
     *
     * @return bool True or False
     */
    public function exists() {
        return (is_file($this->_resource_path) && is_readable($this->_resource_path)) ? true : false;
    }

    /**
     * Determine if the data has expired or not
     *
     * @return bool True or False
     */
    public function isNotExpired() {
        if ($this->exists()) {
            if ($this->_timeout - (time() - filemtime($this->_resource_path)) > 0) {
                return true;
            } else {
                $this->delete();
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Deletes the cached data
     *
     * @return bool True or False
     */
    public function delete() {
        @unlink($this->_resource_path);
    }
}