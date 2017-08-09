<?php
class adfPasswordHash {
    public function setAlgo($algo) {
        return false;
    }

    public function getAlgo() {
        return false;
    }

    public function get($password, $hash = null) {
        return $this->encrypt($password);
    }

    public function encrypt($password) {
        return encrypt($password);
    }

    public function decrypt($password){
        return decrypt($password);
    }
}