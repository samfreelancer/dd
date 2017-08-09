<?php
class adfAuthentication {
    # last error message
    private $_last_error = null;
    private static $paths = array();

    public function __construct() {

    }

    public static function currentUserCan($app) {
        $group = self::getCurrentPermissionGroupId();

        $gPerm = new groupPermissions();
        if ($group == 3 || $gPerm->groupCan($group, $app)) {
            return true;
        }

        return false;
    }

    public static function getCurrentPermissionGroupId() {
        if (self::isLoggedIn()) {
            $userData = self::getUserData();
            $group = $userData['group_id'];
        } else {
            $group = 1;
        }

        return $group;
    }

    public static function getCurrentUserId() {
        if (self::isLoggedIn()) {
            $userData = self::getUserData();
            return $userData['id'];
        } else {
            return 0;
        }
    }

    public function login(user $user, adfPasswordHash $hasher, $username, $password) {
        try {
            if (false == $userData = $user->getOneByField('username', $username)) {
                throw new Exception("Sorry, invalid credentials.");
            }

            if ($userData['ip_restricted'] == 'yes') {
                $ips = array();
                for($i=0; defined('IP_RESTRICTED_ALLOWED_' . $i); ++$i){
                    $ips[] = constant('IP_RESTRICTED_ALLOWED_' . $i);
                }

                if (!in_array($_SERVER['REMOTE_ADDR'], $ips)) {
                    throw new Exception("Sorry, this system cannot be accessed from your current location.");
                }
            }

            if ($userData['status'] != 'active') {
                throw new Exception("Sorry, invalid credentials.");
            }

            $hash = $hasher->get($password);

            if (!$this->checkPassword($hasher, $userData['password'], $password)) {
                throw new Exception("Sorry, invalid credentials.");
            }

            # if the user is already logged in we log them out
            $this->logoffById($userData['id']);

            # add a session entry for the user data and an index entry to allow testing for multiple logins
            adfSession::write(session_id() . 'data', $userData);
            adfSession::write($userData['id'], session_id() . 'data');

            # update last login
            $user->updateLastLoginById($userData['id']);
        } catch (Exception $e) {
            sleep(2);
            $this->_last_error = $e->getMessage();
            return false;
        }

        return true;
    }

    public static function getUserData() {
        return adfSession::read(session_id() . 'data');
    }

    public static function isLoggedIn() {
        if (false == $userData = adfSession::read(session_id() . 'data')) {
            return false;
        }

        adfSession::write(session_id() . 'data', $userData);
        adfSession::write($userData['id'], session_id() . 'data');

        return $userData;
    }

    public static function updateUserSessionData($userId, $data) {
        if (false == $sid = adfSession::read($userId)) {
            return false;
        }

        if (false == $userData = adfSession::read($sid)) {
            return false;
        }

        foreach ($userData as $key => $value) {
            if (isset($data[$key])) {
                $userData[$key] = $data[$key];
            }
        }

        adfSession::write($sid, $userData);
        adfSession::write($userData['id'], $sid);

        return true;
    }

    public static function isLoggedInById($id) {
        if (false !== ($sid = adfSession::read($id)) && (false !== adfSession::read($sid))) {
            # we need to write to the session index so it doesn't expire before the session
            adfSession::write($id, $sid);
            return true;
        }

        return false;
    }

    public static function logoffById($id) {
        if (false !== ($sid = adfSession::read($id))) {
            # logoff user session
            adfSession::destroy($sid);

            # remove session index entry
            adfSession::destroy($id);
        }
    }

    public static function logoffCurrentUser() {
        if (false != $userData = adfSession::read(session_id() . 'data')) {
            adfSession::destroy($userData['id']);
            adfSession::destroy(session_id() . 'data');
        }

        # clear session variables
        $_SESSION = array();

        # clear any session cookies
        if (isset($_COOKIE['user_session'])) {
            setcookie('user_session', '', time()-3600);
        }

        # destroy the session
        session_destroy();
        session_write_close();

        return true;
    }

    public static function checkPassword(adfPasswordHash $hasher, $hash, $password) {
        return ($hash == $hasher->get($password, $hash)) ? true : false;
    }

    public function lastError() {
        return $this->_last_error;
    }
    
    public static function setPaths($pathLogin, $path404, $path401){
        self::$paths = array(
            'pathLogin' => $pathLogin,
            'path404' => $path404,
            'path401' => $path401
        );
    }
    
    public static function get404Path(){
        if (!empty(self::$paths['path404'])){
            return self::$paths['path404'];
        } else {
            return '/error/error404';
        }
    }
    
    public static function get401Path(){
        if (!empty(self::$paths['path401'])){
            return self::$paths['path401'];
        } else {
            return '/error/forbidden';
        }
    }
    
    public static function getLoginPath(){
        if (!empty(self::$paths['pathLogin'])){
            return self::$paths['pathLogin'];
        } else {
            return '/test';
        }
    }

}