<?php

/**
 * A collection of useful functions.
 *
 * For assistance with this code, license inquiries or development services,
 * please contact Benjamin at net@p9b.org.
 *
 * @author      Benjamin Lewis <net@p9b.org>
 * @version     10.0.0
 * @license     Proprietary, Authorized Users Only
 * @copyright   Benjamin Lewis.  All Rights Reserved. Redistribution Prohibited.
 * @package     Miscellaneous
 */

/**
 * Adds an autoloader to the autoloader collection stack.
 *
 * @param ojbect $class An instance of the autoloader class
 * @return void
 */
function adfAutoload($class) {
    adfAutoLoaderCollection::load($class);
}

/**
 * A shortcut function used to retrieve the value of a $_GET variable without
 * having to first check if it is set.
 *
 * @param string $i The $_GET array key
 * @return mixed The value of the variable or null
 */
function lib_get($i) {
    return (isset($_GET[$i])) ? trim($_GET[$i]) : null;
}

/**
 * A shortcut function used to retrieve the value of a $_POST variable without
 * having to first check if it is set.
 *
 * @param string $i The $_POST array key
 * @return mixed The value of the variable or null
 */
function lib_post($i) {

    preg_match("/(.*)\[(.*)\]/", $i, $matches);
    if (empty($matches)) {
        return (isset($_POST[$i])) ? (is_array($_POST[$i])) ? $_POST[$i] : stripslashes(trim($_POST[$i]))  : null;
    }
    else {
        eval('$v=(isset($_POST["{$matches[1]}"]["{$matches[2]}"])) ? $_POST["{$matches[1]}"]["{$matches[2]}"] : null;');
        return $v;
    }
}

/**
 * A shortcut function used to retrieve the value of a $_REQUEST variable without
 * having to first check if it is set.
 *
 * @param string $i The $_REQUEST array key
 * @return mixed The value of the variable or null
 */
function lib_request($i) {
    if (isset($_REQUEST[$i])) {
        return $_REQUEST[$i];
    }
    elseif (isset($_POST[$i])) {
        return $_POST[$i];
    }
    elseif (isset($_GET[$i])) {
        return $_GET[$i];
    }
    else {
        return null;
    }
}

function lib_clean_phone($phone){
   return str_replace('-','',$phone); 
}
/**
 * A shortcut function used to retrieve the value of a $_COOKIE variable without
 * having to first check if it is set.
 *
 * @param string $i The $_COOKIE array key
 * @return mixed The value of the variable or null
 */
function lib_cookie($i) {
    return (isset($_COOKIE[$i])) ? $_COOKIE[$i] : null;
}

/**
 * A shortcut function used to retrieve the value of a $_SESSION variable without
 * having to first check if it is set.
 *
 * @param string $i The $_SESSION array key
 * @return mixed The value of the variable or null
 */
function lib_session($i) {
    return (isset($_SESSION[$i])) ? trim($_SESSION[$i]) : null;
}

/**
 * For internal use only.  Returns the name of the function used to retrieve
 * the corresponding variable type.
 *
 * @staticvar array $methods An array of method types
 * @param string $method The requested method type
 * @return string The name of the function used to retrieve the data
 */
function lib_method($method) {
    static $methods = array('get', 'post', 'request');
    return (in_array(strtolower($method), $methods)) ? "lib_$method" : 'lib_post';
}

/**
 * Creates a dropdown select menu and automatically selects the option if the
 * value is set.
 *
 * @param string $name The name of the select menu, also used as the id.
 * @param array $values An array of key/value pairs used to populate the dropdown options.
 * @param string $method The input method to use.  e.g. get, post, request.
 * @param string $class The optional css class name(s) to use.  Separate multiple names with a space.
 * @param string $javascript Optional javascript code to add to the <select> tag.  e.g. onchange events.
 * @param boolean $disabled True to set select as disabled for display
 * @return string The complete dropdown menu
 */
function lib_select($name, $values, $method = 'post', $class = null, $javascript = null, $disabled = false) {
    $method = lib_method($method);

    $form = '<select id="' . $name . '" ' . (($class === null) ? '' : 'class="' . trim($class) . '" ') . (($javascript !== null) ? $javascript : '') . ' name="' . $name . '" ' . ($disabled? 'disabled="disabled"' : '') . '>';
    foreach ($values as $key => $value) {
        $form .= ((($method($name) !== null) ? $method($name) : '') == (string) $key) ? '<option selected="selected" value="' . $key . '">' . $value . '</option>' : '<option value="' . $key . '">' . $value . '</option>';
    }
    $form .= "</select>";
    return $form;
}

/**
 * Creates an input text box and automatically populates the value if it is set.
 *
 * @param string $name The name of the input field, also used as the id.
 * @param string $method The input method to use.  e.g. get, post, request.
 * @param string $class The optional css class name(s) to use.  Separate multiple names with a space.
 * @param boolean $readonly True to set input as readonly for display
 * @return string The complete input box
 */
function lib_input($name, $method = 'post', $class = null, $readonly = false, $type = 'text') {

    $method = lib_method($method);
    return '<input id="' . $name . '" ' . (($class === null) ? '' : 'class="' . trim($class) . '" ') . 'type="'.$type.'" name="' . $name . '" value="' . htmlentities(call_user_func($method, $name)) . '" ' . ($readonly? 'readonly="readonly"' : '') .' />';
}
/**
 * Creates an input textarea and automatically populates the value if it is set.
 *
 * @param string $name The name of the input field, also used as the id.
 * @param string $method The input method to use.  e.g. get, post, request.
 * @param string $class The optional css class name(s) to use.  Separate multiple names with a space.
 * @param integer $rows Default number of rows for textarea
 * @param integer $cols Default number of columns for textarea
 * @param boolean $readonly True to set input as readonly for display
 * @return string The complete textarea
 */
function lib_textarea($name, $method='post', $class=null, $rows = 8, $cols = 60, $readonly = false){
    $method = lib_method($method);
    return '<textarea name="' . $name . '" id="' . $name . '" ' . (($class === null) ? '' : 'class="' . trim($class) . '" ') . 'rows="'.$rows.'" cols="'.$cols.'"' . ($readonly? 'readonly="readonly"' : '') .'>'.htmlentities(call_user_func($method, $name)).'</textarea>';
}

/**
 * Creates an input password text box and automatically populates the value if it is set.
 *
 * @param string $name The name of the input field, also used as the id.
 * @param string $method The input method to use.  e.g. get, post, request.
 * @param string $class The optional css class name(s) to use.  Separate multiple names with a space.
 * @return string The complete input box
 */
function lib_password($name, $method = 'post', $class = null) {
    $method = lib_method($method);
    return '<input id="' . $name . '" ' . (($class === null) ? '' : 'class="' . trim($class) . '" ') . 'type="password" name="' . $name . '" value="' . htmlentities(call_user_func($method, $name)) . '" />';
}

/**
 * A shortcut to mysql_real_escape_string
 *
 * @param mixed $n The data to escape
 * @return mixed The escaped data
 */
function lib_escape($n) {
    $link = adfRegistry::get('DB_LINK');
    return mysqli_escape_string($link, $n);
}

/**
 * Creates a checkbox and automatically checks it if the value is set.  Value of
 * checked boxes is 1.
 *
 * @param string $k The checkbox name, also used as the id.
 * @param string $v The checkbox text.
 * @param string $class The optional css class name(s) to use.  Separate multiple names with a space.
 * @param boolean $disabled True to set checkbox as disabled for display
 * @return The complete checkbox.
 */
function lib_checkbox($k, $v, $class = null, $disabled = false, $labelUnder = false) {
    $checkbox = '<input class="checkbox" id="' . $k . '" ' . ((lib_request($k) == '1') ? 'checked="checked" ' : '') . 'type="checkbox" name="' . $k . '" value="1" ' . (($class === null) ? '' : 'class="' . trim($class) . '" ') . ($disabled ? 'disabled="disabled"' : '').' />';
    if ($labelUnder) {
        $label = (empty($v)) ? '' : ' <label class="checkbox" for="' . $k . '">' . $v . "</label>\n";
        return $checkbox . $label;
    } else {
        return '<label class="checkbox">' . $checkbox . $v . '</label>';
    }
}

/**
 * Creates a radio option and automaticaly selects the corresponding option if
 * the value is set.
 *
 * @param string $k The option group name
 * @param string $v The option value
 * @param string $text The option caption
 * @param string $div Optional divider, html is fine
 * @return The complete option code
 */
function lib_radio($k, $v, $text, $div = "&nbsp;&nbsp;\n") {
    return '<input class="radio" id="' . $v . '" ' . ((lib_post($k) == $v) ? 'checked="checked" ' : '') . 'type="radio" name="' . $k . '" value="' . $v . '" /> <label for="' . $v . '">' . $text . "</label>$div";
}

/**
 * Always returns false.
 *
 * @return bool false
 */
function lib_return_false() {
    return false;
}

/**
 * Returns a dump of an array, object or scalar in <pre> tags.
 *
 * @param mixed $var An array, object or scalar
 * @return string The dump data
 */
function lib_debug($var) {
    if(LIB_DEBUG){
        if (is_array($var)) {
            return '<pre>' . print_r($var, true) . '</pre>';
        }
        elseif (is_object($var)) {
            return '<pre>' . print_r($var, true) . '</pre>';
        }
        else {
            return '<pre>' . $var . '</pre>';
        }
    }
}

/**
 * A curl wrapper used to retrieve the contents of the specified URL.
 *
 * @param string $url The url to retrieve
 * @param string $useragent Optional The browser useragent to use.
 * @return string The data received from the GET request.
 */
function lib_get_url($url, $useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.13) Gecko/2009073022 Firefox/3.0.13') {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

/**
 * Write a file and set the file permissions.
 *
 * @param string|binary $data The data to write
 * @param string $path The path to write to.
 * @param octal $chmod The permissions to set.
 * @return mixed false or the number of bytes written.
 */
function lib_write_file($data, $path, $chmod = 0666) {
    if (false == $n = file_put_contents($path, $data)) {
        return false;
    }

    @chmod($path, $chmod);

    return $n;
}

/**
 * Checks if a file exists and deletes it if it does.
 *
 * @param string $file The file path
 * @return bool True or False
 */
function lib_delete_file($file) {
    if (file_exists($file) && is_file($file)) {
        return unlink($file);
    }
    return false;
}

/**
 * Checks if a folder exists and deletes it AND ALL CONTENTS if it does.
 *
 * @param string $folder The folder path
 * @return bool True or False
 */
function lib_delete_folder_recursive($folder, $empty = false) {
    $folder = rtrim($folder, '/');

    if(!file_exists($folder) || !is_dir($folder)) {
        return false;
    } elseif(!is_readable($folder)) {
        return false;
    } else {
        $folderHandle = opendir($folder);

        while ($contents = readdir($folderHandle)) {
            if($contents != '.' && $contents != '..') {
                $path = $folder . "/" . $contents;

                if(is_dir($path)) {
                    lib_delete_folder_recursive($path);
                } else {
                    lib_delete_file($path);
                }
            }
        }

        closedir($folderHandle);

        if($empty == false) {
            if(!lib_delete_folder($folder)) {
                return false;
            }
        }

        return true;
    }
}

/**
 * Checks if a folder exists and deletes it if it does.
 *
 * @param string $folder The folder path
 * @return bool True or False
 */
function lib_delete_folder($folder) {
    if (file_exists($folder) && is_dir($folder)) {
        return rmdir($folder);
    }

    return false;
}

/**
 * Moves a file
 *
 * @param string $path_from The existing path
 * @param string $path_to The new path
 * @param octal $chmod The permissions to set.
 * @return bool True or False
 */
function lib_move_file($path_from, $path_to, $chmod = 0666) {
    if (!rename($path_from, $path_to)) {
        return false;
    }

    @chmod($path_to, $chmod);

    return true;
}

/**
 * A simple mailer.  Automatically detects html in emails.  If an email contains
 * any html, the entire email must consist of html formatting.
 *
 * @param string $f The from address
 * @param string $t The to address
 * @param string $s The message subject
 * @param string $b The message body
 * @param string $options Optional cc and bcc recipients.
 * @return bool True or False
 */
function lib_mailer($f, $t, $s, $b, $options = false) { // from to subject body
    $headers = null;
    $headers .= "Return-Path: $f\r\n";
    if (preg_match("#<.*?>#s", $b)) {
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    }
    $headers .= "From: $f\r\n";
    $headers .= "Reply-To: $f\r\n";

    if (is_array($options)) {
        if (isset($options['cc']) && is_array($options['cc'])) {
            foreach ($options['cc'] as $cc) {
                $headers .= "Cc: $cc\r\n";
            }
        }

        if (isset($options['bcc']) && is_array($options['bcc'])) {
            foreach ($options['bcc'] as $bcc) {
                $headers .= "Bcc: $bcc\r\n";
            }
        }
    }
    return mail($t, $s, $b, $headers);
}

/**
 * Reindexes array keys in a continous way from 0.  Supports multidimensional arrays.
 *
 * @param array The array to reindex.
 * @return array The reindexed array.
 */
function array_reindex($array) {
    $newArray = array();
    foreach ($array as $v) {
        if (is_array($v)) {
            $newArray[] = array_reindex($v);
        }
        else {
            $newArray[] = $v;
        }
    }
    return $newArray;
}

/**
 * Determine if a page request is POST or some other type.  Returns true if
 * the current request is of type POST.
 *
 * @return bool True or False
 */
function lib_is_post() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

/**
 * Sort a multidimensional array in ascending order.
 *
 * @param array $items An array of arrays
 * @param string $key The subarray array key to sort.
 * @return array The sorted arrays.
 */
function lib_mdarray_sort_asc($items, $key) {
    $compare = create_function('$a,$b', '
        if ($a["' . $key . '"] == $b["' . $key . '"]) {
            return 0;
        } else {
            return ($a["' . $key . '"] > $b["' . $key . '"]) ? 1 : -1;
        }
    ');
    uasort($items, $compare);
    return $items;
}

/**
 * Sort a multidimensional array in descending order.
 *
 * @param array $items An array of arrays
 * @param string $key The subarray array key to sort.
 * @return array The sorted arrays.
 */
function lib_mdarray_sort_desc($items, $key) {
    $compare = create_function('$a,$b', '
        if ($a["' . $key . '"] == $b["' . $key . '"]) {
            return 0;
        } else {
            return ($a["' . $key . '"] > $b["' . $key . '"]) ? -1 : 1;
        }
    ');
    uasort($items, $compare);
    return $items;
}

/**
 * Modifies a string for safe use as a url
 *
 * @param string $string The input string
 * @return string The safe string
 */
function lib_url_convert($string) {
    $string = preg_replace('#[^a-z0-9 ]#', '', strtolower($string));
    $string = preg_replace('#\s#', '-', $string);
    $string = preg_replace('#_{2,}#', '-', $string);
    return $string;
}

/**
 * Creates a random string
 *
 * @param int $len The length of the string
 * @return string The random string
 */
function lib_random_string($len) {
    $chars = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
    $cnt = count($chars) - 1;
    $key = '';
    for ($i = 0; $i < $len; $i++)
        $key .= $chars[mt_rand(0, $cnt)];
    return $key;
}

/**
 * Get the page specified in the page variable or default to page 1
 *
 * @return int The requested page
 */
function lib_get_current_page() {
    $n = preg_match('#^\d+$#', lib_request('page')) ? lib_request('page') : 1;

    if ($n == 0) {
        $n = 1;
    }

    return $n;
}

/**
 * Removes all non-ascii characters, as well as symbols and punctuation
 * for generating a search engine query string.
 *
 * @param string $string The string to clean
 * @return string The clean string
 */
function lib_clean_for_search($string) {
    return preg_replace('#[^a-z0-9\s]#i', '', $string);
}

/**
 * Cleans a string for use as an html value or attribute.
 *
 * @param string $str The string to clean
 * @return string The cleaned string.
 */
function lib_clean_for_attribute($str) {
    return htmlentities($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Retrieve the path to an asset folder
 *
 * @param string $to The required asset
 * @return string The path to the asset
 */
function lib_get_path($to) {
    $registry = adfRegistry::getInstance();
    return $registry->paths[$to];
}

/**
 * Formats UK style date as required by MySQL
 *
 * @param string $date The date
 * @return string The formatted date
 */
function lib_date_uk_to_db($date) {
    $n = explode("/", $date);
    if (count($n) != 3) {
        return false;
    }

    return date("Y-m-d", strtotime("{$n[1]}/{$n[0]}/{$n[2]}"));
}

/**
 * Formats date as required by MySQL
 *
 * @param string $date The date
 * @return string The formatted date
 */
function lib_date_to_db($date) {
    $n = explode("/", $date);
    if (count($n) != 3) {
        return false;
    }

    return date("Y-m-d", strtotime("{$n[2]}/{$n[0]}/{$n[1]}"));
}

/**
 * Formats MySQL style date as DD/MM/YYYY (UK Format)
 *
 * @param string $date The date
 * @return string The formatted date
 */
function lib_date_db_to_standard($date) {
    # ymd = y=0, m=1, d=2
    $n = preg_split('#[/\.-]#', $date);

    if (count($n) != 3) {
        return false;
    }

    return date("n/j/Y", strtotime("{$n[1]}/{$n[2]}/{$n[0]}"));
}

/**
 * Formats MySQL style date as DD/MM/YYYY (UK Format)
 *
 * @param string $date The date
 * @return string The formatted date
 */
function lib_date_db_to_uk($date) {
    # ymd = y=0, m=1, d=2
    $n = preg_split('#[/\.-]#', $date);

    if (count($n) != 3) {
        return false;
    }

    return date("j/n/Y", strtotime("{$n[0]}/{$n[1]}/{$n[2]}"));
}

/**
 * Validates a date formatted as YYYY/MM/DD, YYYY-MM-DD or YYYY.MM.DD
 *
 * @param string $date The date to check
 * @return bool True or False
 */
function lib_date_is_valid($date) {
    # ymd = y=0, m=1, d=2
    $n = preg_split('#[/\.-]#', $date);

    if (count($n) != 3) {
        return false;
    }

    if (!checkdate($n[1], $n[2], $n[0])) {
        return false;
    }

    return true;
}

/**
 * Returns an array with Yes/No values for use in an automatically generated
 * dropdown menu.  Keys are true/false.  Values are Yes/no
 *
 * @return array
 */
function lib_get_yes_no_as_array($default = false) {
    if ($default) {
        $n = array(
            'true' => 'Yes',
            'false' => 'No',
        );
    }
    else {
        $n = array(
            'false' => 'No',
            'true' => 'Yes',
        );
    }

    return $n;
}

/**
 * Determine whether data is a whole or decimal number by using a regular
 * expression.  More accurate and reliable than PHPs internal methods.
 *
 * @param mixed $num The value to check
 * @return bool True or False
 */
function lib_is_real_or_decimal_number($num) {
    return preg_match("#^\d{1,}\.\d{0,}$|^\d{1,}$#", $num);
}

/**
 * Determine whether the parameter passed is a real number.  Will return false
 * on negative numbers.
 *
 * @param string $n The value to check
 * @return bool True or False
 */
function lib_is_int($n) {
    return preg_match('#^\d+$#', $n);
}

/**
 * Redirects to another page on the same domain by sending an external header
 * redirect then halt execution.
 *
 * @param string $request A controller/action combination
 */
function lib_redirect($request) {
    $registry = adfRegistry::getInstance();
    $domain = $registry->paths['domain'];
    if (strpos($request, '/') === 0) {
        header("Location: $domain$request");
    }else {
        header("Location: $domain/$request");    
    }
    exit();
}

/**
 * Returns an array of name titles.
 *
 * @return array An array of titles
 */
function lib_get_name_titles() {
    $n = array(
        '' => '- Select -',
        'mr' => 'Mr',
        'mrs' => 'Mrs',
        'miss' => 'Miss',
        'dr' => 'Dr',
        'madam' => 'Madam',
        'ms' => 'Ms',
        'rev' => 'Rev',
        'sgt' => 'Sgt',
        'mr & mrs' => 'Mr & Mrs',
        'sir' => 'Sir',
        'master' => 'Master',
        'professor' => 'Professor'
    );

    return $n;
}

function libCreateFieldName($table, $field) {
    return "$table.$field";
}

/**
 * Increments a lowercase or uppercase letter by 1.
 *
 * @param string $letter The letter to increment
 * @return mixed The next letter or false on failure
 * @author Benjamin Lewis <net@p9b.org>
 */
function lib_letter_increment($letter) {
    if (empty($letter) || strlen($letter) > 1) {
        return false;
    }

    # an array of acceptable ascii codes for letters
    $ascii = array_merge(range(65, 90), range(97, 122));

    # compute the next letter
    $next = ord($letter) + 1;

    # check if it's in range
    if (!in_array($next, $ascii)) {
        return false;
    }

    # convert the ascii code back to a letter and return
    return chr($next);
}

function lib_sidebar_link($target, $text, $active_class = 'current_page_item') {
    $registry   = adfRegistry::getInstance();
    $resource   = $registry->route['resource'];

    if (false === $x = strpos($target, '?')) {
        $query_string   = '';
    } else {
        $n              = explode('?', $target);
        $target         = $n[0];
        $query_string   = "?{$n[1]}";
    }

    $class      = (($target == $resource || $target == '/' . adfRegistry::get('CURRENT_PAGE_REQUEST')) ? " class=\"$active_class\"" : '');
    return '    <li' . $class . '><a href="' . lib_link($target) . $query_string . '"><span>' . $text . '</span></a></li>';
}

/**
 *
 */
function lib_link($request) {
    return adfRouteRewrite::getLink($request);
}

function base64Encode($str) {
    $base64 = base64_encode($str);
    $sniptbase64 = substr($base64, 0, 4);

    $base64 = $sniptbase64 . base64_encode($sniptbase64 . $str);
    return $base64;
}

function base64Decode($p) {

    $decode = substr($p, 4, strlen($p));
    $decode = base64_decode($decode);
    $decode = substr($decode, 4, strlen($decode));
    return $decode;
}

function lib_auto_password($default_length = 8) {
    $chars = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J",
        "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T",
        "U", "V", "W", "X", "Y", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9");
    $textstr = '';
    for ($i = 0, $length = $default_length; $i < $length; $i++) {
        $textstr .= $chars[rand(0, count($chars) - 1)];
    }
    return $textstr;
}

function mdEncript($p) {

    $mdf = md5($p);
    $sniptmdf = substr($mdf, 0, 4);

    $mdf = $sniptmdf . md5($sniptmdf . $p);
    return $mdf;
}

/*
 * Use lib_mailer
 */

function send_mail($from, $to, $subject, $message) {

    if (mail($to, $subject, $message, "From:" . $from . "\nReply-To:" . $from . "\nContent-type: text/html;  charset=UTF-8; format=flowed")) {
        return true;
    }
    else {
        return false;
    }

    //return true;
}

// Haytham Hosney
function tolink($text) {
    $text = html_entity_decode($text);
    $text = " " . stripslashes(str_replace('\n', '</br>', $text));

    if (strpos($text ,'youtube'  ) > 0 || strpos($text,'vimeo') > 0 || strpos($text,'flic') > 0|| strpos($text,'flickr') > 0) {
        $text = preg_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '', $text);
        $text = preg_replace('(((f|ht){1}tps://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '', $text);
        $text = preg_replace('(([\s+]()[{s}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+))', '$0', $text);
    }
    else {
        $text = preg_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="$0">$0</a>', $text);
        $text = preg_replace('(((f|ht){1}tps://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="$0">$0</a>', $text);
        $text = preg_replace('(([\s+]()[{s}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+))', '$0<a href="http://$1">\\$1</a>', $text);
    }
    $text = preg_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})', '<a href="mailto:\\$0">\\$0</a>', $text);

    return $text;
}

function returnOnlyLink($text) {
    $returnString = '';
    $text = html_entity_decode($text);
    $text = " " . $text;
    $text = preg_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="$0">$0</a>', $text);
    $text = preg_replace('(((f|ht){1}tps://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="$0">$0</a>', $text);
    $returnString = preg_replace('(([\s+]()[{s}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+))', '<a href="http://$1">\\$1</a>', $text);

    $xStart = strpos($text, '<a');
    $xEnd = strpos($text, '</a>');
    if ($xStart && $xEnd)
        return trim(substr($text, $xStart, ( $xEnd + 4 ) - $xStart));
    else
        return '';
}

//Loading Comments link with load_updates.php

function time_stamp($session_time) {
    $time_difference = time() - strtotime($session_time);
    $seconds = $time_difference;
    $minutes = round($time_difference / 60);
    $hours = round($time_difference / 3600);
    $days = round($time_difference / 86400);
    $weeks = round($time_difference / 604800);
    $months = round($time_difference / 2419200);
    $years = round($time_difference / 29030400);

    if ($seconds <= 60) {
        echo"$seconds seconds ago";
    }
    else if ($minutes <= 60) {
        if ($minutes == 1) {
            echo"one minute ago";
        }
        else {
            echo"$minutes minutes ago";
        }
    }
    else if ($hours <= 24) {
        if ($hours == 1) {
            echo"one hour ago";
        }
        else {
            echo"$hours hours ago";
        }
    }
    else if ($days <= 7) {
        if ($days == 1) {
            echo"one day ago";
        }
        else {
            echo"$days days ago";
        }
    }
    else if ($weeks <= 4) {
        if ($weeks == 1) {
            echo"one week ago";
        }
        else {
            echo"$weeks weeks ago";
        }
    }
    else if ($months <= 12) {
        if ($months == 1) {
            echo"one month ago";
        }
        else {
            echo"$months months ago";
        }
    }
    else {
        if ($years == 1) {
            echo"one year ago";
        }
        else {
            echo"$years years ago";
        }
    }
}

function lib_is_valid_url($url) {
    return preg_match('#(?:https{0,1}://(?:(?:(?:(?:(?:[a-zA-Z\d](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?)\.'
                     .')*(?:[a-zA-Z](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?))|(?:(?:\d+)(?:\.(?:\d+)'
                     .'){3}))(?::(?:\d+))?)(?:/(?:(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F'
                     .'\d]{2}))|[;:@&=])*)(?:/(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{'
                     .'2}))|[;:@&=])*))*)(?:\?(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{'
                     .'2}))|[;:@&=])*))?)?)|(?:ftp://(?:(?:(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?'
                     .':%[a-fA-F\d]{2}))|[;?&=])*)(?::(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-'
                     .'fA-F\d]{2}))|[;?&=])*))?@)?(?:(?:(?:(?:(?:[a-zA-Z\d](?:(?:[a-zA-Z\d]|-'
                     .')*[a-zA-Z\d])?)\.)*(?:[a-zA-Z](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?))|(?:(?'
                     .':\d+)(?:\.(?:\d+)){3}))(?::(?:\d+))?))(?:/(?:(?:(?:(?:[a-zA-Z\d$\-_.+!'
                     .'*\'(),]|(?:%[a-fA-F\d]{2}))|[?:@&=])*)(?:/(?:(?:(?:[a-zA-Z\d$\-_.+!*\'()'
                     .',]|(?:%[a-fA-F\d]{2}))|[?:@&=])*))*)(?:;type=[AIDaid])?)?)|(?:news:(?:'
                     .'(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))|[;/?:&=])+@(?:(?:('
                     .'?:(?:[a-zA-Z\d](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?)\.)*(?:[a-zA-Z](?:(?:['
                     .'a-zA-Z\d]|-)*[a-zA-Z\d])?))|(?:(?:\d+)(?:\.(?:\d+)){3})))|(?:[a-zA-Z]('
                     .'?:[a-zA-Z\d]|[_.+-])*)|\*))|(?:nntp://(?:(?:(?:(?:(?:[a-zA-Z\d](?:(?:['
                     .'a-zA-Z\d]|-)*[a-zA-Z\d])?)\.)*(?:[a-zA-Z](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d'
                     .'])?))|(?:(?:\d+)(?:\.(?:\d+)){3}))(?::(?:\d+))?)/(?:[a-zA-Z](?:[a-zA-Z'
                     .'\d]|[_.+-])*)(?:/(?:\d+))?)|(?:telnet://(?:(?:(?:(?:(?:[a-zA-Z\d$\-_.+'
                     .'!*\'(),]|(?:%[a-fA-F\d]{2}))|[;?&=])*)(?::(?:(?:(?:[a-zA-Z\d$\-_.+!*\'()'
                     .',]|(?:%[a-fA-F\d]{2}))|[;?&=])*))?@)?(?:(?:(?:(?:(?:[a-zA-Z\d](?:(?:[a'
                     .'-zA-Z\d]|-)*[a-zA-Z\d])?)\.)*(?:[a-zA-Z](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d]'
                     .')?))|(?:(?:\d+)(?:\.(?:\d+)){3}))(?::(?:\d+))?))/?)|(?:gopher://(?:(?:'
                     .'(?:(?:(?:[a-zA-Z\d](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?)\.)*(?:[a-zA-Z](?:'
                     .'(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?))|(?:(?:\d+)(?:\.(?:\d+)){3}))(?::(?:\d+'
                     .'))?)(?:/(?:[a-zA-Z\d$\-_.+!*\'(),;/?:@&=]|(?:%[a-fA-F\d]{2}))(?:(?:(?:['
                     .'a-zA-Z\d$\-_.+!*\'(),;/?:@&=]|(?:%[a-fA-F\d]{2}))*)(?:%09(?:(?:(?:[a-zA'
                     .'-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))|[;:@&=])*)(?:%09(?:(?:[a-zA-Z\d$'
                     .'\-_.+!*\'(),;/?:@&=]|(?:%[a-fA-F\d]{2}))*))?)?)?)?)|(?:wais://(?:(?:(?:'
                     .'(?:(?:[a-zA-Z\d](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?)\.)*(?:[a-zA-Z](?:(?:'
                     .'[a-zA-Z\d]|-)*[a-zA-Z\d])?))|(?:(?:\d+)(?:\.(?:\d+)){3}))(?::(?:\d+))?'
                     .')/(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))*)(?:(?:/(?:(?:[a-zA'
                     .'-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))*)/(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|('
                     .'?:%[a-fA-F\d]{2}))*))|\?(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]'
                     .'{2}))|[;:@&=])*))?)|(?:mailto:(?:(?:[a-zA-Z\d$\-_.+!*\'(),;/?:@&=]|(?:%'
                     .'[a-fA-F\d]{2}))+))|(?:file://(?:(?:(?:(?:(?:[a-zA-Z\d](?:(?:[a-zA-Z\d]'
                     .'|-)*[a-zA-Z\d])?)\.)*(?:[a-zA-Z](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?))|(?:'
                     .'(?:\d+)(?:\.(?:\d+)){3}))|localhost)?/(?:(?:(?:(?:[a-zA-Z\d$\-_.+!*\'()'
                     .',]|(?:%[a-fA-F\d]{2}))|[?:@&=])*)(?:/(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|('
                     .'?:%[a-fA-F\d]{2}))|[?:@&=])*))*))|(?:prospero://(?:(?:(?:(?:(?:[a-zA-Z'
                     .'\d](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?)\.)*(?:[a-zA-Z](?:(?:[a-zA-Z\d]|-)'
                     .'*[a-zA-Z\d])?))|(?:(?:\d+)(?:\.(?:\d+)){3}))(?::(?:\d+))?)/(?:(?:(?:(?'
                     .':[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))|[?:@&=])*)(?:/(?:(?:(?:[a-'
                     .'zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))|[?:@&=])*))*)(?:(?:;(?:(?:(?:['
                     .'a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))|[?:@&])*)=(?:(?:(?:[a-zA-Z\d'
                     .'$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))|[?:@&])*)))*)|(?:ldap://(?:(?:(?:(?:'
                     .'(?:(?:[a-zA-Z\d](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?)\.)*(?:[a-zA-Z](?:(?:'
                     .'[a-zA-Z\d]|-)*[a-zA-Z\d])?))|(?:(?:\d+)(?:\.(?:\d+)){3}))(?::(?:\d+))?'
                     .'))?/(?:(?:(?:(?:(?:(?:(?:[a-zA-Z\d]|%(?:3\d|[46][a-fA-F\d]|[57][Aa\d])'
                     .')|(?:%20))+|(?:OID|oid)\.(?:(?:\d+)(?:\.(?:\d+))*))(?:(?:%0[Aa])?(?:%2'
                     .'0)*)=(?:(?:%0[Aa])?(?:%20)*))?(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F'
                     .'\d]{2}))*))(?:(?:(?:%0[Aa])?(?:%20)*)\+(?:(?:%0[Aa])?(?:%20)*)(?:(?:(?'
                     .':(?:(?:[a-zA-Z\d]|%(?:3\d|[46][a-fA-F\d]|[57][Aa\d]))|(?:%20))+|(?:OID'
                     .'|oid)\.(?:(?:\d+)(?:\.(?:\d+))*))(?:(?:%0[Aa])?(?:%20)*)=(?:(?:%0[Aa])'
                     .'?(?:%20)*))?(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))*)))*)(?:('
                     .'?:(?:(?:%0[Aa])?(?:%20)*)(?:[;,])(?:(?:%0[Aa])?(?:%20)*))(?:(?:(?:(?:('
                     .'?:(?:[a-zA-Z\d]|%(?:3\d|[46][a-fA-F\d]|[57][Aa\d]))|(?:%20))+|(?:OID|o'
                     .'id)\.(?:(?:\d+)(?:\.(?:\d+))*))(?:(?:%0[Aa])?(?:%20)*)=(?:(?:%0[Aa])?('
                     .'?:%20)*))?(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))*))(?:(?:(?:'
                     .'%0[Aa])?(?:%20)*)\+(?:(?:%0[Aa])?(?:%20)*)(?:(?:(?:(?:(?:[a-zA-Z\d]|%('
                     .'?:3\d|[46][a-fA-F\d]|[57][Aa\d]))|(?:%20))+|(?:OID|oid)\.(?:(?:\d+)(?:'
                     .'\.(?:\d+))*))(?:(?:%0[Aa])?(?:%20)*)=(?:(?:%0[Aa])?(?:%20)*))?(?:(?:[a'
                     .'-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))*)))*))*(?:(?:(?:%0[Aa])?(?:%2'
                     .'0)*)(?:[;,])(?:(?:%0[Aa])?(?:%20)*))?)(?:\?(?:(?:(?:(?:[a-zA-Z\d$\-_.+'
                     .'!*\'(),]|(?:%[a-fA-F\d]{2}))+)(?:,(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-f'
                     .'A-F\d]{2}))+))*)?)(?:\?(?:base|one|sub)(?:\?(?:((?:[a-zA-Z\d$\-_.+!*\'('
                     .'),;/?:@&=]|(?:%[a-fA-F\d]{2}))+)))?)?)?)|(?:(?:z39\.50[rs])://(?:(?:(?'
                     .':(?:(?:[a-zA-Z\d](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?)\.)*(?:[a-zA-Z](?:(?'
                     .':[a-zA-Z\d]|-)*[a-zA-Z\d])?))|(?:(?:\d+)(?:\.(?:\d+)){3}))(?::(?:\d+))'
                     .'?)(?:/(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))+)(?:\+(?:(?:'
                     .'[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))+))*(?:\?(?:(?:[a-zA-Z\d$\-_'
                     .'.+!*\'(),]|(?:%[a-fA-F\d]{2}))+))?)?(?:;esn=(?:(?:[a-zA-Z\d$\-_.+!*\'(),'
                     .']|(?:%[a-fA-F\d]{2}))+))?(?:;rs=(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA'
                     .'-F\d]{2}))+)(?:\+(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))+))*)'
                     .'?))|(?:cid:(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))|[;?:@&='
                     .'])*))|(?:mid:(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))|[;?:@'
                     .'&=])*)(?:/(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))|[;?:@&=]'
                     .')*))?)|(?:vemmi://(?:(?:(?:(?:(?:[a-zA-Z\d](?:(?:[a-zA-Z\d]|-)*[a-zA-Z'
                     .'\d])?)\.)*(?:[a-zA-Z](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?))|(?:(?:\d+)(?:\\'
                     .'.(?:\d+)){3}))(?::(?:\d+))?)(?:/(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a'
                     .'-fA-F\d]{2}))|[/?:@&=])*)(?:(?:;(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a'
                     .'-fA-F\d]{2}))|[/?:@&])*)=(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d'
                     .']{2}))|[/?:@&])*))*))?)|(?:imap://(?:(?:(?:(?:(?:(?:(?:[a-zA-Z\d$\-_.+'
                     .'!*\'(),]|(?:%[a-fA-F\d]{2}))|[&=~])+)(?:(?:;[Aa][Uu][Tt][Hh]=(?:\*|(?:('
                     .'?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))|[&=~])+))))?)|(?:(?:;['
                     .'Aa][Uu][Tt][Hh]=(?:\*|(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2'
                     .'}))|[&=~])+)))(?:(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))|['
                     .'&=~])+))?))@)?(?:(?:(?:(?:(?:[a-zA-Z\d](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])'
                     .'?)\.)*(?:[a-zA-Z](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?))|(?:(?:\d+)(?:\.(?:'
                     .'\d+)){3}))(?::(?:\d+))?))/(?:(?:(?:(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:'
                     .'%[a-fA-F\d]{2}))|[&=~:@/])+)?;[Tt][Yy][Pp][Ee]=(?:[Ll](?:[Ii][Ss][Tt]|'
                     .'[Ss][Uu][Bb])))|(?:(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))'
                     .'|[&=~:@/])+)(?:\?(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))|['
                     .'&=~:@/])+))?(?:(?:;[Uu][Ii][Dd][Vv][Aa][Ll][Ii][Dd][Ii][Tt][Yy]=(?:[1-'
                     .'9]\d*)))?)|(?:(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))|[&=~'
                     .':@/])+)(?:(?:;[Uu][Ii][Dd][Vv][Aa][Ll][Ii][Dd][Ii][Tt][Yy]=(?:[1-9]\d*'
                     .')))?(?:/;[Uu][Ii][Dd]=(?:[1-9]\d*))(?:(?:/;[Ss][Ee][Cc][Tt][Ii][Oo][Nn'
                     .']=(?:(?:(?:[a-zA-Z\d$\-_.+!*\'(),]|(?:%[a-fA-F\d]{2}))|[&=~:@/])+)))?))'
                     .')?)|(?:nfs:(?:(?://(?:(?:(?:(?:(?:[a-zA-Z\d](?:(?:[a-zA-Z\d]|-)*[a-zA-'
                     .'Z\d])?)\.)*(?:[a-zA-Z](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?))|(?:(?:\d+)(?:'
                     .'\.(?:\d+)){3}))(?::(?:\d+))?)(?:(?:/(?:(?:(?:(?:(?:[a-zA-Z\d\$\-_.!~*\''
                     .'(),])|(?:%[a-fA-F\d]{2})|[:@&=+])*)(?:/(?:(?:(?:[a-zA-Z\d\$\-_.!~*\'(),'
                     .'])|(?:%[a-fA-F\d]{2})|[:@&=+])*))*)?)))?)|(?:/(?:(?:(?:(?:(?:[a-zA-Z\d'
                     .'\$\-_.!~*\'(),])|(?:%[a-fA-F\d]{2})|[:@&=+])*)(?:/(?:(?:(?:[a-zA-Z\d\$\\'
                     .'-_.!~*\'(),])|(?:%[a-fA-F\d]{2})|[:@&=+])*))*)?))|(?:(?:(?:(?:(?:[a-zA-'
                     .'Z\d\$\-_.!~*\'(),])|(?:%[a-fA-F\d]{2})|[:@&=+])*)(?:/(?:(?:(?:[a-zA-Z\d'
                     .'\$\-_.!~*\'(),])|(?:%[a-fA-F\d]{2})|[:@&=+])*))*)?)))#i', $url);
}

function encrypt($password) {
    $len = strlen($password);

    for($i = 0; $i < $len; $i++) {
        $password[$i] = chr((ord($password[$i]) + $len - $i));
    }

    for($i = 0; $i < 3; $i++) {
        $password .= chr(ord($password[$i]) + $len);
    }

    return base64_encode($password);
}

function decrypt($password){
    $password = base64_decode($password);

    $len = strlen($password) - 3;
    $passwd = "";

    for($i = 0; $i < $len; $i++) {
        $temp = ord($password[$i]) - ($len-$i);
        if($temp < 0) {
            $temp += 128;
        }

        $passwd .= chr($temp);
    }

    return $passwd;
}

function lib_format_text($text) {
    return '<p>' . implode("</p><p>", explode("\n", str_replace("\r", '', $text))) . '</p>';
}

/**
 * Returns the current timestamp, formatted for mysql
 * @return string The current timestamp
 */
function lib_mysql_now() {
    return date("Y-m-d H:i:s");
}

function lib_get_timezones($extra = null) {
    $zones = array(
        'America/Puerto_Rico'   => 'America/Puerto Rico',
        'America/New_York'      => 'America/New York',
        'America/Chicago'       => 'America/Chicago',
        'America/Boise'         => 'America/Boise',
        'America/Phoenix'       => 'America/Phoenix',
        'America/Los_Angeles'   => 'America/Los Angeles',
        'America/Juneau'        => 'America/Juneau',
        'Pacific/Honolulu'      => 'Pacific/Honolulu',
        'Pacific/Guam'          => 'Pacific/Guam',
        'Pacific/Samoa'         => 'Pacific/Samoa',
        'Pacific/Wake'          => 'Pacific/Wake',
    );

    if (!is_null($extra) && is_array($extra)) {
        $zones = array_merge($extra, $zones);
    }

    return $zones;
}

/**
  * Formats a line (passed as a fields  array) as CSV and returns the CSV as a string.
  * Adapted from http://us3.php.net/manual/en/function.fputcsv.php#87120
  */
function arrayToCsv(array &$fields, $delimiter = ';', $enclosure = '"', $encloseAll = false, $nullToMysqlNull = false ) {
    $delimiter_esc = preg_quote($delimiter, '/');
    $enclosure_esc = preg_quote($enclosure, '/');

    $output = array();
    foreach ( $fields as $field ) {
        if ($field === null && $nullToMysqlNull) {
            $output[] = 'NULL';
            continue;
        }

        // Enclose fields containing $delimiter, $enclosure or whitespace
        if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
            $output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
        } else {
            $output[] = $field;
        }
    }

    return implode( $delimiter, $output );
}

function uploadFailed($name) {
    if ($_FILES[$name]['error'] != 0) {
        switch ($_FILES[$name]['error']) {
            case 1:
                return 'Uploaded files cannot be greater than ' . number_format(ini_get('upload_max_filesize')) . ' bytes.';
                break;
            case 2:
                return 'The uploaded file is too large. Please try uploading a smaller file.';
                break;
            case 3:
                return 'A network error has caused your upload to be only partially received.  Please try again.';
                break;
            case 4:
                return 'An uploaded file was not received.  Please try again.';
                break;
            case 6:
                return 'A temporary folder for saving uploads does not exist.  Please configure a temporary folder in your php.ini file.';
                break;
            case 7:
                return 'An uploaded file could not be written to disk.  Please verify permissions and free disk space.';
                break;
            case 8:
                return "Sorry, that file extension is not allowed.";
                break;
        }
    }

    return false;
}

function getExtension($filename) {
    $n = pathinfo($filename);
    return !empty($n['extension']) ? $n['extension'] : '';
}

function cleanPhone($string) {
    return ltrim(preg_replace('#[^\d]#', '', $string), '1');
}

/**
 * Check if current request has standard ajax request headers (it is not 100% accurate!)
 */
function lib_is_ajax_request(){
   return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function deleteDashes($domainName){
    if(substr($domainName, -1) == '-' ){
        $domainName[strlen($domainName)-1] = '';
    }
    if(substr($domainName, 0) == '-' ){
        $domainName[0] = '';
    }
    
    return $domainName;
}

function validateDomainName($domainName){
    $dname = $domainName;
    $tlds = array('com','org','net','info','biz','es');
    $dom_array = explode('.', $domainName);
    $available_d = array();
    $available = TRUE;
    if(count($dom_array) >= 2){
        if(in_array(end($dom_array), $tlds)){
            $name = str_replace('.'.end($dom_array), '', $domainName);
            $name = deleteDashes($name);
            $domainName = preg_replace("/[^a-zA-Z0-9\- ]/", "", $name);
            
            foreach ($tlds as $key => $value) {
                if(end($dom_array) != $value ){
                    $available_d[] = $domainName.'.'.$value;
                }
            }
            $domain = $domainName.'.'.end($dom_array);
        }else{
            $available = FALSE;
            $domainName = preg_replace("/[^a-zA-Z0-9\- ]/", "", $domainName);
            $domainName = deleteDashes($domainName);
            foreach ($tlds as $key => $value) {
                $available_d[] = $domainName.'.'.$value;
            }
            $domain= $dname;
        }
    }else{
        
        $domainName = deleteDashes($domainName);
        $domainName = preg_replace("/[^a-zA-Z0-9\- ]/", "", $domainName);
        foreach ($tlds as $key => $value) {
            if($key!=0)
            $available_d[] = $domainName.'.'.$value;
        }
        $domain= $domainName.'.'.$tlds[0];
    }
   
    return array('available' => $available,'domain' => $domain,'availables' => $available_d);
}

function sendResponse($status,$message = null,$data = array()){
    echo json_encode(array('status' => $status,'message' => $message,'data' => $data));die;
}

/**
 * Check if name.com api response is success or not
 *
 * @param Array $result api response array
 * @return Boolean True on success and false on failed
 */
function lib_check_name_response(&$result) {
    if (!empty($result['result']['code']) && $result['result']['code'] == 100) {
        return true;
    }
    return false;
}

/**
 * Improve the domain  price suggested by name.com api
 *
 * @param Array $result api response array
 * @return true
 */
function lib_improve_price(&$result, $price = 0) {
    if(!empty($result['domains'])) {
        foreach($result['domains'] as $domain => $meta) {
            if (!empty($meta['price'])) {
                $percentage = number_format(($meta['price'] / PRICE_IMPROVEMENT_PERCENTAGE), 2, '.', '');
                $result['domains'][$domain]['new_price'] = $meta['price'] + $percentage;
            } 
        }
    }
    if(!empty($result['suggested'])) {
        foreach($result['suggested'] as $domain => $meta) {
            if (!empty($meta['price'])) {
                $percentage = number_format(($meta['price'] / PRICE_IMPROVEMENT_PERCENTAGE), 2, '.', '');
                $result['suggested'][$domain]['new_price'] = $meta['price'] + $percentage;
            } 
        }
    }
    if ($price > 0) {
        $percentage = number_format(($price / PRICE_IMPROVEMENT_PERCENTAGE), 2, '.', '');
        return $price + $percentage;
    }
    return false;
}

/*
 * Log data in file
 */

function log_me($data) {
    if(LIB_DEBUG) {
        if (is_array($data)) {
            $data = json_encode($data);
        } 
        $filename = LOG_PATH.DIRECTORY_SEPARATOR.'trace.log';
        if (!file_exists($filename)) {
            $fp = fopen($filename, 'w+');
        } else {
            $fp = fopen($filename, 'a+');
        }
        //fwrite($fp, "#####################################################################\n");
        fwrite($fp, $data."\n");
        fclose($fp);
        chmod($filename, 0777);
    }
}

/*
 * Conver the date format for show purpose
 * To have consistant approach across application
 */

function lib_convert_date_show($date) {
    if (!empty($date)) {
        return date(SHOW_DATE_FORMAT, strtotime($date)); 
    }
    return ;
}