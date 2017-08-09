<?php
abstract class adfSessionAdaptor {
    public function __construct($lifetime) { }
    public function read($key) { }
    public function write($key, $data) { }
    public function destroy($key) { }
    public function gc() { }
}
