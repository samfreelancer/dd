<?php

include "wwReseller/init.php";

class goDaddyToolbox {

    // Change credentials and url to leave the sandbox
    private $_clientLogin = GD_CLIENT_LOGIN;
    private $_clientPass  = GD_CLIENT_PASS;
    public $_apiURL      = WildWest_Reseller_Client::WSDL_OTE_TESTING;

    private $_client = null;

    public function __construct() {

        $this->_client = new WildWest_Reseller_Client(
            $this->_apiURL,
            $this->_clientLogin,
            $this->_clientPass
        );

        if (!$this->_client) {
            throw new Exception("Unable to connect!");
        }
    }

    /**
     *  Checks availability of specified domain(s).
     *
     *  @param array or string $domains
     *  @return array(string domain_name => bool is_available)
     */
    public function domainAvailable($domains) {

        $domainsArr = is_array($domains) ? $domains : array($domains);
        try {
            $res = $this->_client->CheckAvailability($domainsArr);
        } catch (WildWest_Reseller_Exception $e) {
            $response = array(
                'error' => $e->getCode(),
                'message' => $e->getMessage()
            );
            return $response;
        }
        if (!is_array($domains)) {
            return $res[$domains] == '1';
        } else {
            return array_map(function ($el) { return $el == '1'; }, $res);
        }
    }
    /**
     *  Used to get the lists of alternative domain names based on a given name (Domains Bot).
     *
     *  @param  string $domain
     *  @return array()
     */
    public function nameGenerate($domain) {

        try {
            $res = $this->_client->NameGenDB($domain);
            
        } catch (WildWest_Reseller_Exception $e) {
            $response = array(
                'error' => $e->getCode(),
                'message' => $e->getMessage()
            );
            return $response;
        }
        if ($res) {
            return $res;
        } else {
            return array();
        }
    }
    
    public function CheckUser($sUser,$sPwd){
        try {
            $user = $this->_client->CheckUser($sUser,$sPwd);
            
        } catch (WildWest_Reseller_Exception $e) {
            $response = array(
                'error' => $e->getCode(),
                'message' => $e->getMessage()
            );
            return $response;
        }
        return $user;
    }

    /**
     *  Creates shopper object for purchase. If it's a new shopper
     *  (no shopper id) it will be registered alongside the purchase.
     *
     *  @param array $shopperData
     *  @return WildWest_Reseller_Shopper
     */
    public function createShopper($shopperData) {

        $shopper = new WildWest_Reseller_Shopper();
        $shopper->firstname = $shopperData['firstname'];
        $shopper->lastname  = $shopperData['lastname'];
        $shopper->email     = $shopperData['email'];
        $shopper->user      = isset($shopperData['user']) ? $shopperData['user'] : 'createNew';
        $shopper->pwd       = $shopperData['password'];
        $shopper->phone     = '+' . $shopperData['phone_region'] . $shopperData['phone'];
        $shopper->acceptOrderTOS = $shopperData['acceptOrderTOS'];

        return $shopper;
    }

    /**
     *  Creates contact info object.
     *
     *  @param array $shopperData
     *  @return WildWest_Reseller_Shopper
     */
    public function createContactInfo($shopperData) {

        $info        = new WildWest_Reseller_ContactInfo();
        $info->fname = $shopperData['firstname'];
        $info->lname = $shopperData['lastname'];
        $info->email = $shopperData['email'];
        $info->sa1   = $shopperData['address'];
        $info->city  = $shopperData['city'];
        $info->sp    = $shopperData['state'];
        $info->phone = '+' . $shopperData['phone_region'] . '.' . $shopperData['phone'];
        $info->fax = '+' . $shopperData['phone_region'] . '.' . $shopperData['phone'];
        $info->pc    = $shopperData['zipcode'];
        $info->cc    = $shopperData['country'];

        return $info;
    }

    /**
     *  Registers domain purchase.
     *  Shopper data must contain:
     *  - firstname
     *  - lastname
     *  - email
     *  - user (shoppers id if it's registered, otherwise leave blank)
     *  - password
     *  - phone (example: '8885551212')
     *  - phone_region (example: '1')
     *  - address (street address)
     *  - city
     *  - state (state or province)
     *  - zipcode (postal code)
     *  - country (country, must much goDaddy countries, example: 'United States'),
     *  - acceptOrderTOS (must be equal 'agree')
     *
     *  @param array $shopperData
     *  @param string $domain
     *  @param string $period = '1'
     *  @param string $ns = array( string ns1, string ns2)
     *  @return array('orderid', 'user') containing orderId and shopperId
     */
    public function purchaseDomain($shopperData, $domain, $period = '1', $ns = null) {

        $shopper = $this->createShopper($shopperData);
        $contactInfo = $this->createContactInfo($shopperData);
        
        $domainArr = explode('.', $domain);
        $sld = array_shift($domainArr);
        $tld = implode('.', $domainArr);
        
        $productId = $this->getProductId($tld);
        
        if (!$productId) {
            throw new Exception('Unknown product.');
        }

        $order = new WildWest_Reseller_OrderItem();
        $order->productid = $productId;
        $order->quantity  = '1';
        
        $registration             = new WildWest_Reseller_DomainRegistration();
        $registration->order      = $order;
        $registration->registrant = clone $contactInfo;
        $registration->admin      = clone $contactInfo;
        $registration->tech       = clone $contactInfo;
        $registration->billing    = clone $contactInfo;
        $registration->period     = 1;
        $registration->sld        = $sld;
        $registration->tld        = $tld;
        $registration->autorenewflag = 1;
        
        if (!empty($ns)) {
            $nsArr = array();
            foreach ($ns as $n) {
                $wwd_ns = new WildWest_Reseller_NS();
                $wwd_ns->name = $n;
                $nsArr[] = $wwd_ns;
            }
            $registration->nsArray    = $nsArr;
        }
        try {
            $response = $this->_client->OrderDomains($shopper, array($registration));
            $response['error'] = 0;
        } catch (WildWest_Reseller_Exception $e) {
            $response = array(
                'error' => $e->getCode(),
                'message' => $e->getMessage()
            );
        }
        
        return $response;
    }
   
    /**
     * Fetches messages from API
     */
    public function poll() {
        return $this->_client->Poll();
    }

    /**
     * Orders Privacy for a given domain.
     * Check purchaseDomain method for requirements on shopperData.
     *
     * @param  array $shopperData
     * @param  string $domain
     * @param  string $resourceId - resource Id is returned after purchase
     * @param  array $privacyData - password, email, user (id)
     * @return bool
     */
    public function orderDomainPrivacy($shopperData, $domain, $privacyData) {
        
        $shopper = $this->createShopper($shopperData);
        $productId = $this->getProductId(null, 'private_registration');
        $order = new WildWest_Reseller_OrderItem();
        $order->productid = $productId;
        $order->quantity  = '1';
        $order->duration  = $this->getDurationForDomainPrivacy($domain);
        
        if (!$productId) {
            throw new Exception('Unknown product.');
        }

        $info = $this->getDomainInfo($domain);
        $resourceId = $info['resourceid'];
        
        $domainArr = explode('.', $domain);
        $sld = array_shift($domainArr);
        $tld = implode('.', $domainArr);

        $dbp = new WildWest_Reseller_DomainByProxy();
        $dbp->sld        = $sld;
        $dbp->tld        = $tld;
        $dbp->order      = $order;
        $dbp->resourceid = $resourceId;
        
        $shopper->dbpuser   = isset($privacyData['user']) && $privacyData['user'] ? $privacyData['user'] : 'createNew';
        $shopper->dbppwd    = $privacyData['password'];
        $shopper->dbpemail  = $privacyData['email'];
        
        try {
            $res = $this->_client->OrderDomainPrivacy($shopper, array($dbp));
            $response = array(
                'error' => 0,
                'user' => $res['user'],
                'dbpuser' => $res['dbpuser'],
                'orderid' => $res['orderid']
            );
        } catch (WildWest_Reseller_Exception $e) {
            $response = array(
                'error' => $e->getCode(),
                'message' => $e->getMessage()
            );
        }
        return $response;
    }

    /**
     * Renew domain by 1 year. If domain is private privacy is renewed too.
     * Check purchaseDomain method for requirements on shopperData.
     *
     * @param array $shopperData
     * @param string $domain
     */
    public function orderDomainRenewal($shopperData, $domain) {

        $domainArr = explode('.', $domain);
        $sld = array_shift($domainArr);
        $tld = implode('.', $domainArr);

        $shopper = $this->createShopper($shopperData);
        $productId = $this->getProductId($tld, 'renewal');
        $domainInfo = $this->getDomainInfo($domain);
        
        if (!$productId) {
            throw new Exception('Unknown product.');
        }

        $order = new WildWest_Reseller_OrderItem();
        $order->productid = $productId;
        $order->quantity = '1';

        $item = new WildWest_Reseller_DomainRenewal();
        $item->resourceid = $domainInfo['resourceid'];
        $item->sld = $sld;
        $item->tld = $tld;
        $item->order = $order;
        $item->period = '1';

        $dbp = array();
        if ($domainInfo['private'] && isset($domainInfo['dbp_id'])) {
            // Renew privacy for the given domain
            $orderDbp = new WildWest_Reseller_OrderItem();
            $orderDbp->productid = $this->getProductId($tld, 'private_renewal');
            $orderDbp->quantity = '1';

            $itemDbp = new WildWest_Reseller_ResourceRenewal();
            $itemDbp->resourceid = $domainInfo['dbp_id'];
            $itemDbp->sld = $sld;
            $itemDbp->tld = $tld;
            $itemDbp->order = $orderDbp;
            $itemDbp->period = '1';

            $dbp[] = $itemDbp;
        }

        try {
            $response = $this->_client->OrderPrivateDomainRenewals($shopper, array($item), $dbp);
            $response['error'] = 0;
        } catch (WildWest_Reseller_Exception $e) {
            $response = array(
                'error' => $e->getCode(),
                'message' => $e->getMessage()
            );
        }
        return $response;

    }

    /**
     * Transfer domain to a different shopper account.
     * Check purchaseDomain method for requirements on shopperData.
     *
     * @param array $shopperData
     * @param string $domain
     */
    public function transferDomain($shopperData, $domain) {

        $domainArr = explode('.', $domain);
        $sld = array_shift($domainArr);
        $tld = implode('.', $domainArr);

        $shopper = $this->createShopper($shopperData);
        $productId = $this->getProductId($tld, 'transfer');

        if (!$productId) {
            throw new Exception('Unknown product.');
        }

        $order = new WildWest_Reseller_OrderItem();
        $order->productid = $productId;
        $order->quantity  = '1';

        $transfer = new WildWest_Reseller_DomainTransfer();
        $transfer->sld = $sld;
        $transfer->tld = $tld;
        $transfer->order = $order;

        try {
            $response = $this->_client->OrderDomainTransfers($shopper, array($transfer));
            $response['error'] = 0;
        } catch (WildWest_Reseller_Exception $e) {
            $response = array(
                'error' => $e->getCode(),
                'message' => $e->getMessage()
            );
        }
        return $response;
    }

    /**
     * Returns basic info about domain
     *
     * @param string $domain
     * @return array
     */
    public function getDomainInfo($domain, $resourceId = null, $orderId = null) {
        try {
            $response = $this->_client->Info($resourceId, $domain, $orderId);
            $response['error'] = 0;
        } catch (WildWest_Reseller_Exception $e) {
            $response = array(
                'error' => $e->getCode(),
                'message' => $e->getMessage()
            );
        }
        return $response;
    }

    private function getDurationForDomainPrivacy($domain) {
        try {
            $response = $this->_client->Info(null, $domain, null, 'dbpDuration');
        } catch (WildWest_Reseller_Exception $e) {}
        return isset($response['dbpDuration']) ? $response['dbpDuration'] : false;
    }

    /**
     *  GoDaddy does NOT return it's product ids from any call.
     *  You need to hard code them. Probably.
     *  More here: https://products.secureserver.net/guides/wwdapi_webservice.pdf
     */
    private function getProductId($tld = null, $type = 'registration') {

        $id = false;

        if ($type == 'registration') {
            switch ($tld) {
                case 'es'     : $id = '54001';  break;
                case 'com'    : $id = '350001'; break;
                case 'net'    : $id = '350030'; break;
                case 'info'   : $id = '350051'; break;
                case 'biz'    : $id = '350076'; break;
                case 'org'    : $id = '350150'; break;
            }
        } else if ($type == 'renewal') {
            switch ($tld) {
                case 'bz'     : $id = '15212'; break;
                case 'me'     : $id = '19140'; break;
                case 'in'     : $id = '19850'; break;
                case 'mobi'   : $id = '50801'; break;
                case 'co.in'  : $id = '51000'; break;
                case 'com'    : $id = '350012'; break;
                case 'net'    : $id = '350041'; break;
                case 'info'   : $id = '350062'; break;
                case 'biz'    : $id = '350087'; break;
                case 'us'     : $id = '350137'; break;
                case 'ws'     : $id = '350112'; break;
                case 'org'    : $id = '350161'; break;
            }
        } else if ($type == 'transfer') {
            switch ($tld) {
                case 'bz'     : $id = '15211'; break;
                case 'in'     : $id = '9860'; break;
                case 'mobi'   : $id = '40811'; break;
                case 'com'    : $id = '350011'; break;
                case 'net'    : $id = '350040'; break;
                case 'info'   : $id = '350061'; break;
                case 'biz'    : $id = '350086'; break;
                case 'us'     : $id = '350136'; break;
                case 'ws'     : $id = '350811'; break;
                case 'org'    : $id = '350160'; break;
            }
        } else if ($type == 'private_registration') {
            $id = '377001';
        } else if ($type == 'private_renewal') {
            $id = '387001';
        }

        return $id;
    }
}


?>