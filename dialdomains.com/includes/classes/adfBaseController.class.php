<?php
class adfBaseController {
    public $shoppingCart;
    public $userId;
    
    private $pageMetadata;


    public function __construct() {
        $this->registry = adfRegistry::getInstance();
        $this->status   = adfStatus::getInstance();
        $this->tpl      = adfTheme::getInstance();
        $this->shoppingCart = $this->getShoppingCart();
        $this->userId = adfAuthentication::getCurrentUserId();
    }

    /**
     * Renders the next page after a user action has been performed.
     *
     * @param string $action The controller to be executed
     * @param string $controller The controller to be executed, only required if different than the caller
     * @param bool $allowAutoOverride Whether or not the framework can override the requested action
     * @return null
     */
    protected function renderNext($action = 'index', $controller = null, $application = null, $allowAutoOverride = false) {
        $wf = new adfWorkflow();

        if ($wf->exists()) {
            $wf->goToNext();
        } else {
            $application    = is_null($application) ? $this->registry->route['app'] : $application;
            $controller     = is_null($controller) ? $this->registry->route['controller'] : $controller;

        	lib_redirect(lib_link("{$application}/{$controller}/$action"));
        }

        exit();
    }

    public function getShoppingCart(){
        $this->userId = adfAuthentication::getCurrentUserId();
        $shoppingCart = array();
        if (!empty($this->userId)){
            $cart =  new cart();
            $shoppingCart = $cart->getUserCart($this->userId,'');
            if (empty($shoppingCart)){
                $shoppingCart = array();
            }
        } else if(empty($this->userId) && !empty(lib_cookie('adfSession')) && lib_cookie('adfSession') != 'null'){
            $session_id = lib_cookie('adfSession');
            $cart =  new cart();
            $shoppingCart = $cart->getUserCart('', $session_id);
            if (empty($shoppingCart)){
                $shoppingCart = array();
            }
        } else{
            $shoppingCart = array();
        }
        return $shoppingCart;
    }
    
    public function setPageMetadata($page) {
        $this->pageMetadata = adfPageMetadata::getInstance()->getPageMetadata($page);
    }
    
    public function getPageTitle() {
        return ($this->pageMetadata !== null && !empty($this->pageMetadata['PageTitle'])) ? $this->pageMetadata['PageTitle'] : 'Create new account | Metronic Shop UI';
    }
}