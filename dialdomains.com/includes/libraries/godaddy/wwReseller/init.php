<?php

set_include_path(
    get_include_path() . PATH_SEPARATOR .
    realpath(dirname(__FILE__) . '/library')
);

spl_autoload_register(function($class) {
    if(file_exists(dirname(__FILE__). '/library/'.str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php')){
        require_once str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
    }
});

