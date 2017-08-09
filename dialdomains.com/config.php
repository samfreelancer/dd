<?php
libxml_use_internal_errors(TRUE);

define('DB_NAME', 'dial_master');
define('DB_USER', 'dial_user');
define('DB_PASSWORD', 'M?9Z;i58yrfy');
define('DB_HOST', 'localhost');
define('DB_TIMEZONE', '+04:00');
/**
 * Misc settings
 */
define('THEME_DEFAULT', 'admin');


define('COOKIE_TIME', time()+3600*24*30);
define('COOKIE_PATH', '/');
define('DOMAIN_PRICE', 99.00);

/*define('LIB_DEBUG', TRUE);
define('SALT', 'c74a3');
define('GD_CLIENT_LOGIN', 'rish1990');
define('GD_CLIENT_PASS', 'SAXENArish!!1');
define('TRANSACTION_KEY', '4CtaR2L5m8na2S5w');
define('LOGIN_ID', '9NsnjDu35Zzy');
*/

define('LIB_DEBUG', true);
define('SALT', 'c74a3');
define('GD_CLIENT_LOGIN', '1002334');
define('GD_CLIENT_PASS', 'WDKH4rwMSPyRr3ft');
define('TRANSACTION_KEY', '4j9DX66rq5tR59H8');
define('LOGIN_ID', '9QR4kP9p7tNA');
define('TRANSACTION_KEY_TEST', "45Mr5m7L99dm6HSe");
define('LOGIN_ID_TEST', '89AgGxd32');
define('NAME_USERNAME', 'moneytreeleadsystems-ote');
define('NAME_API_TOKEN', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhcGl0Ijo1NjIwNzcsImV4cCI6MTgwMDgxNTMwMCwianRpIjoxfQ.Wi4Xjl7-TVBznyjtDmmHSUXo6we6U8p2SPdnwt5aZZ4');
define('NAME_URL', 'https://api.dev.name.com/api');
   
/**
 *  Allowed IPS for users with restricted access
 */

define('IP_RESTRICTED_ALLOWED_0','127.0.0.1');
define('IP_RESTRICTED_ALLOWED_1','64.22.234.236');

/**
 * Set the timezone
 */
date_default_timezone_set('Europe/Moscow');

/**
 * Automatically set base path
 */
define('SITE_BASE_PATH', dirname(__FILE__));

define('SITE_HTTP_PATH', 'http://dialdomains.local');
define('SITE_HTTPS_PATH', 'http://dialdomains.local');

/**
 * SMTP settings
 */

define('VOICE_DOMAIN_PRICE', 9.99);

define('SMTP_HOST', '74.91.84.182');
define('SMTP_PORT', '2525');
define('SMTP_USERNAME', 'moneytreeleadsystems2@gmail.com');
define('SMTP_PASSWORD', 'Success1');
define('SMTP_TIMEOUT', '');
//define('EMAIL_BCC', 'send-all-emails-copy@to.me');

/*
 * Domain Price improvements
 */
define('PRICE_IMPROVEMENT_PERCENTAGE', 10);

define('SAVE_DATE_FORMAT','Y-m-d H:i:s');
define('SHOW_DATE_FORMAT','M d, Y');
define('LOG_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR.'tmp');

ini_set('display_errors', true);
error_reporting(E_ALL);

require SITE_BASE_PATH . '/includes/init.php';