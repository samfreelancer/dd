<?php
/**
 * A System initialization script
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

/*
 * Include the core files necessary to start the framework manually.
 */
require SITE_BASE_PATH . '/includes/functions.php';
require SITE_BASE_PATH . '/includes/classes/adfAutoLoaderInterface.class.php';
require SITE_BASE_PATH . '/includes/classes/adfAutoLoaderCollection.class.php';
require SITE_BASE_PATH . '/includes/classes/adfClassLocator.class.php';
require SITE_BASE_PATH . '/includes/classes/authorizeNetCIM.class.php';
//require SITE_BASE_PATH . '/includes/libraries/godaddy/goDaddyToolbox.class.php';
/**
 * Add autoloaders for each of the system directories
 */
spl_autoload_register('adfAutoload');

adfAutoLoaderCollection::addAutoLoader(
    new adfClassLocator(
        SITE_BASE_PATH . '/cache/core/',
        SITE_BASE_PATH . '/includes/'
    ),
    'classes_includes'
);

adfAutoLoaderCollection::addAutoLoader(
    new adfClassLocator(
        SITE_BASE_PATH . '/cache/core/',
        SITE_BASE_PATH . '/models/'
    ),
    'classes_models'
);

adfAutoLoaderCollection::addAutoLoader(
    new adfClassLocator(
        SITE_BASE_PATH . '/cache/core/',
        SITE_BASE_PATH . '/validators/'
    ),
    'classes_validators'
);

$registry = adfRegistry::getInstance();
$registry->paths = array(
    'domain'        => SITE_HTTP_PATH,
    'views'         => SITE_BASE_PATH . '/views/',
    'controllers'   => SITE_BASE_PATH . '/controllers/',
    'models'        => SITE_BASE_PATH . '/models/',
    'includes'      => SITE_BASE_PATH . '/includes/',
    'blocks'        => SITE_BASE_PATH . '/views/blocks/',
    'pages'         => SITE_BASE_PATH . '/views/pages/',
    'tables'        => SITE_BASE_PATH . '/views/blocks/tables/',
	'crons'         => SITE_BASE_PATH . '/crons/',
);

$registry->DB_NAME              = DB_NAME;
$registry->DB_USER              = DB_USER;
$registry->DB_PASSWORD          = DB_PASSWORD;
$registry->DB_HOST              = DB_HOST;
$registry->MYSQL_NOW            = date("Y-m-d H:i:s");
$registry->SITE_BASE_PATH       = SITE_BASE_PATH;
$registry->THEME_PATH           = SITE_BASE_PATH . '/themes/';
$registry->NEW_FILE_CHMOD       = 0666;
$registry->NEW_FOLDER_CHMOD     = 0777;
$registry->PATH_REWRITE_CACHE   = SITE_BASE_PATH . '/cache/core/';
$registry->NAME_REWRITE_CACHE   = 'rewrites.cache';
$registry->NAME_REWRITE_OUT     = 'rewritesOut.cache';
$registry->NAME_REWRITE_IN      = 'rewritesIn.cache';
$registry->REWRITE_CACHE_EXP    = '1800'; # expiration time in seconds

/**
 * Initialize database connection
 */
$registry->DB_LINK = adfDbLink::create($registry->DB_HOST, $registry->DB_USER, $registry->DB_PASSWORD, $registry->DB_NAME, 3306);

if (!is_object($registry->DB_LINK)) {
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: 300');
    ?>
    <html>
        <head>
        <title>503 Service Temporarily Unavailable</title>
        </head>
        <body>
            <h2>503 Service Temporarily Unavailable</h2>
            <p>Please try again in a few minutes.</p>
            <!-- <?php echo $message; ?> -->
        </body>
    </html>
    <?php
    exit();
}


/**
 * Start a session
 * 86400 = 1 hour
 */
adfSession::startSession('adfSession', new adfMySQLSessionAdaptor(new adfDb($registry->DB_LINK), 86400));

/*
 * Populate the information regarding the current user
 */
if (adfAuthentication::isLoggedIn()) {
    $registry->USER_DATA    = adfAuthentication::getUserData();
    $registry->IS_LOGGED_IN = true;
} else {
    $registry->IS_LOGGED_IN = false;
}

//set the default paths to redirect
adfAuthentication::setPaths("login", null, null);

//set campaign id cookie to be used for registration
if(isset($_GET['cid']) && !empty($_GET['cid'])){
    $expires=time()+60*60*24*90 ;   //90 days
    setcookie('cid',$_GET['cid'],$expires,'/');

}

//https issue for internet explorer when moving to other pages  from store pages fixed
if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' && strpos($_SERVER['REQUEST_URI'],'/shop/')===false ){
    $url = "http://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    header("Location: $url");
}

/**
 * Detect and add any event queries
 */
$wf = new adfWorkflow();

/**
 * Set character encoding
 */
header('Content-Type:text/html; charset=UTF-8');

// the order matters, adfRouter is loading controller class to check if action exists
// so it has to be run second to avoid controller redeclaration
adfRouterCollection::addRouter(new adfRouter(), 'adfRouter');


