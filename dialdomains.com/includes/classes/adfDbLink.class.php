<?php
class adfDbLink {
    public static function create($host, $user, $pass, $dbas, $port) {
        if (!is_object(($dbcon = @mysqli_connect($host, $user, $pass, $dbas, $port)))) {
            return mysqli_connect_errno() . ' - ' . mysqli_connect_error();
        }

        $dbcon->databaseName = $dbas;
        return $dbcon;
    }

    public static function check($host, $user, $pass, $dbas, $port){
        $dbcon = @mysqli_connect($host, $user, $pass, $dbas, $port);
        if (!is_object($dbcon)){
            return false;
        } else {
            $dbcon->close();
            return true;
        }
    }
}