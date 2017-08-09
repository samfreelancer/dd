<?php
/**
 * class for page metadata ...
 * @author dileep
 *
 */
class adfPageMetadata {
    /**
     * Static property to hold instance ...
     * @var adfPageMetadata
     */
    private static $_instance = false;
    
    /**
     * Pages metadata. At later stage this can be removed
     * by putting these values into database...
     * @var Array
     */
    private $allPagesMetadata = array(
        'public_default_index' => array('PageTitle' => 'Home | DialDomains'),
        'public_default_login' => array('PageTitle' => 'Login | DialDomains'),
        'public_default_forgotpassword' => array('PageTitle' => 'Forgot Password | DialDomains'),
        'public_default_register' => array('PageTitle' => 'Create new account | DialDomains'),
        'public_default_searchdomain' => array('PageTitle' => 'Search Domain | DialDomains'),
        'public_default_privacy' => array('PageTitle' => 'Privacy Policy | DialDomains'),
        'account_default_domains' => array('PageTitle' => 'Domains | DialDomains'),
        'account_default_home' => array('PageTitle' => 'My Account | DialDomains'),
        'account_default_shoppingcart' => array('PageTitle' => 'Shopping Cart | DialDomains'),
        'account_default_searchdomain' => array('PageTitle' => 'Search Domain | DialDomains'),
        'account_default_paydomain' => array('PageTitle' => 'Pay Domain | DialDomains')
    );
    
    /**
     * Constructor...
     */
    private function __construct() {
        self::$_instance = $this;
    }
    
    /**
     * Return instance of same class ...
     * @return adfPageMetadata instance
     */
    public static function getInstance() {
        return (self::$_instance === false) ? new adfPageMetadata() : self::$_instance;
    }
    
    /**
     * Get page metadata by providing page slug ...
     * @param String $page
     */
    public function getPageMetadata($page) {
        return (array_key_exists($page, $this->allPagesMetadata) && is_array($this->allPagesMetadata[$page])) ? $this->allPagesMetadata[$page] : null;
    }
}