<?php
/**
 * This file processes all web requests where the content does not exist on disk.
 *
 * For assistance with this code, license inquiries or development services,
 * please contact Benjamin at net@p9b.org.
 *
 * @author      Benjamin Lewis <net@p9b.org>
 * @version     10.0.0
 * @license     Proprietary, Authorized Users Only
 * @copyright   Benjamin Lewis.  All Rights Reserved. Redistribution Prohibited.
 * @package     Core System
 */
require 'config.php';

/**
 * Pass this request to the router and render the output
 */
adfRouterCollection::routeRequest(lib_request('REQUEST'));