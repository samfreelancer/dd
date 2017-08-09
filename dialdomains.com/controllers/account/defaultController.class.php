<?php

/**
 * THIS FILE IS USING TABS!!! PLEASE USE 4 SPACES INSTEAD OF TABS.
 */
class defaultController extends adfBaseController {

    private $domainM;
    private $gd;
    private $userModel;
    private $transaction;
    private $transactionItems;
    private $user_id;
    private $_cart;
    private $_cartItems;
    private $name;
    public function __construct() {
        $this->domainM = new domain();
        //$this->gd = new goDaddyToolbox();
        $this->userModel = new User();
        $this->transaction = new transactions();
        $this->transactionItems = new transactionItems();
        $this->user_id = adfAuthentication::getCurrentUserId();
        $this->_cartItems = new cartItems();
        $this->_cart =  new cart();
        $this->name = new nameComApi();
        parent::__construct();
    }

    public function homeAction() {
        $domains = $this->domainM->getDomainsByUserId($this->user_id,'addedon','desc');
        include $this->tpl->page('test/domains.php');
        
        /*$userSession = adfAuthentication::getUserData();
        $countries = $this->userModel->getCountries();
        $user = $this->userModel->getByEmail($userSession['email']);
        include $this->tpl->page('test/internal.php');*/
    }

    public function domainsAction() {
        $domains = $this->domainM->getDomainsByUserId($this->user_id, 'addedon', 'desc');
        include $this->tpl->page('test/domains.php');
    }

    public function payDomainAction() {
        if (lib_post('domains')) {
            $domains = json_decode(lib_post('domains'), true);
            log_me("checkout domains");
            log_me($domains);
            $userData = adfAuthentication::getUserData();
            if (!empty($domains) && !empty($userData)) {
                if (lib_post('creditcard')) {
                    try {
                        foreach ($domains as $dd) {
                            $_POST['total'] = !empty($dd['cart_total']) ? $dd['cart_total'] : 0;
                            break;    
                        }
                        $params = $this->createTransactionRequest($_POST, $domains, $userData);
                        log_me("Payment gateway params");
                        log_me($params);
                        $request = AuthnetApiFactory::getJsonApiHandler(LOGIN_ID_TEST, TRANSACTION_KEY_TEST, AuthnetApiFactory::USE_DEVELOPMENT_SERVER);
                        $paymentResponse = $request->createTransactionRequest($params);
                        log_me("Payment response");
                        log_me($paymentResponse);
                        if ($paymentResponse->messages->resultCode == "Ok") {
                            // update buyer profile
                            $this->userModel->updateBuyerProfile($paymentResponse, $userData);
                            $transaction_id = $paymentResponse->transactionResponse->transId;
                            // add transaction entry
                            $transaction = array(
                                'approval_code' => $paymentResponse->transactionResponse->authCode,
                                'avs_result' => $paymentResponse->transactionResponse->avsResultCode,
                                'cvv_result' => $paymentResponse->transactionResponse->cvvResultCode,
                                'cavv_result' => $paymentResponse->transactionResponse->cavvResultCode,
                                'transaction_id' => $transaction_id,
                                'user_id' => $this->user_id,
                                'status' => 'paid',
                                'amount_paid' => $_POST['total'],
                                'original_trans' => $paymentResponse
                            );
                            $this->transaction->add($transaction);
                            log_me("Transaction added");
                            log_me($transaction);
                            $items = array("items" => array());
                            $new_domains = $transaction_items_added = $voice_domain = $refund_trans = array();
                            $refund_amount = 0;
                            foreach ($domains as $key => $value) {
                                $trans_item = array(
                                    'transaction_id' => $transaction_id,
                                    'domain' => $value['domain'],
                                    'order_type' => 'domain/create',
                                    'quantity' => $value['quantity'],
                                    'unit_price' => $value['unit_price'],
                                    'revised_price' => $value['revised_price'],
                                    'revised_percentage' => PRICE_IMPROVEMENT_PERCENTAGE,
                                    'total_price' => $value['total_price'],
                                    'status' => 'paid',
                                );
                                $transaction_items_added[$value['domain']] = $this->transactionItems->add($trans_item);
                                unset($trans_item);
                                if (!empty($value['is_voice_domain']) && $value['is_voice_domain'] == 1) {
                                    // process all the voice domains purchased
                                    $data = array(
                                        'voice_domain' => $value['domain'],
                                    	'domain' => $value['domain'],
                                        'is_voice_domain' => 1,
                                        'user_id' => $this->user_id,
                                        'phone_number' => $_POST['business_telephone'],
                                        'paid' => $value['total_price'],
                                        'unit_price' => $value['unit_price'],
                                        'revised_price' => $value['revised_price'],
                                        'quantity' => $value['quantity'],
                                        'orderid' => $transaction_id,
                                        'period' => date('Y-m-d H:i:s', strtotime('+' . $value['quantity'] . ' year'))
                                    );
                                    
                                    $re = $this->domainM->addWithReadings($data);
                                    log_me("Domain added");        
                                    log_me($data);
                                    log_me($re);
                                    unset($data, $re);
                                } else {
                                    // no voice domain should go to name.com
                                    $items['items'][] = array(
                                        'order_type' => 'domain/create',
                                        'domain_name' => $value['domain'],
                                        //'nameservers' => array(),
                                        'contacts' => array(
                                            array (
                                                "type" => array("billing"),
                                                "first_name" => $_POST['business_firstname'],
                                                "last_name" => $_POST['business_lastname'],
                                                "organization" => "",
                                                "address_1" => $_POST['business_address'],
                                                "address_2" => "",
                                                "city" => $_POST['business_city'],
                                                "state" => $_POST['business_state'],
                                                "zip" => $_POST['business_zipcode'],
                                                "country" => "US",
                                                "phone" => $_POST['business_telephone'],
                                                "fax" => "",
                                                "email" => $_POST['email']
                                            )
                                        ),
                                        'period' => $value['quantity']
                                    );
                                }
                                $new_domains[$value['domain']] = $value;
                                unset($trans_item);
                            }
                            unset($domains);
                            if (count($items['items'])) {
                                log_me("Items to order on name.com");
                                log_me($items);
                                // domain are there to register on name.com
                                $result = $this->name->order($items);
                                log_me("name.com response");
                                log_me($result);
                                // domain purchased and sent for registration on name.com
                                if (!empty($result['result']['code']) && $result['result']['code'] == 100) {
                                    // success
                                    $this->status->message('Domain '.$value['domain'].' successfully payed.');
                                    foreach ($result['results'] as $item) {
                                        $value = $new_domains[$item['domain_name']];
                                        if ($item['success']) {
                                            // operation for this item was success
                                            // add into domain
                                            $data = array(
                                                'voice_domain' => $value['domain'],
                                                'domain' => $value['domain'],
                                                'user_id' => $this->user_id,
                                                'phone_number' => $_POST['business_telephone'],
                                                'paid' => $value['total_price'],
                                                'unit_price' => $value['unit_price'],
                                                'revised_price' => $value['revised_price'],
                                                'order_type' => $item['order_type'],
                                                'quantity' => $value['quantity'],
                                                'registered' => 1,
                                                'orderid' => $transaction_id,
                                                'period' => date('Y-m-d H:i:s', strtotime('+' . $value['quantity'] . ' year'))
                                            );
                                            switch($item['order_type']) {
                                                case 'domain/create':
                                                    $data['purchased'] = 1;
                                                    break;
                                                default:
                                            }
                                            $re = $this->domainM->add($data);
                                            log_me("Domain added in system");
                                            log_me($data);
                                            log_me($re);
                                            unset($data, $re);
                                        } else {
                                            // operation for this item was failed
                                            // refund the respective amount
                                            // mark the tranaction item refunded
                                            $refund_amount += $value['total_price'];
                                            $refund_trans[$value['domain']] = $transaction_items_added[$value['domain']];
                                            $this->status->error("Payment for domain ".$value['domain']." refunded");
                                        }
                                        unset($value);
                                    }
                                } else {
                                    // some issue while purchasing domain(s) on name.com
                                    // refund the amount for domains registration
                                    // mark all items included in this transaction as refunded
                                    foreach ($items['items'] as $it) {
                                        $value = $new_domains[$it['domain_name']];
                                        $refund_amount += $value['total_price'];
                                        $refund_trans[$value['domain']] = $transaction_items_added[$value['domain']];
                                        $this->status->error("Payment for domain ".$value['domain']." refunded");
                                        unset($value);
                                    }
                                }
                                unset($items, $result);
                            }
                            if ($refund_amount > 0 && count($refund_trans)) {
                                $this->refundGenerate($transaction_id, $refund_amount, $refund_trans, $new_domains, $_POST['creditcard'], $_POST['expiration']);
                            }
                            unset($refund_trans, $refund_amount, $transaction_id, $transaction_items_added, $result, $new_domains);
                            // empty cart
                            $this->_cart  = new cart();
                            $this->_cart->deleteUserCart($this->user_id);
                            lib_redirect('account/default/domains');
                            // lib_redirect('account/default/registerDomain?ids=' . htmlspecialchars(json_encode($ids)));
                        } else {
                            $this->status->error('There was an error processing the transaction.');
                        }
                    } catch (AuthnetAIMException $e) {
                        //$this->status->error($e->__toString());
                        $this->status->error('There was an error processing the transaction.');
                    }
                }
            }
        }
        $shoppingCart= $this->getShoppingCart();
        $userData = adfAuthentication::getUserData();
        $_POST['business_firstname'] = $userData['first_name'];
        $_POST['business_lastname'] = $userData['last_name'];
        $_POST['email'] = $userData['email'];
        $_POST['business_address'] = $userData['address'];
        $_POST['business_city'] = $userData['city'];
        $_POST['business_state'] = $userData['state'];
        $_POST['business_zipcode'] = $userData['zipcode'];
        $_POST['business_telephone'] = $userData['phone'];
        include $this->tpl->page('test/pay_domain.php');
    }

    public function renewDomainAction() {
        if (lib_request('id')) {
            $id = lib_request('id');
            $domain = $this->domainM->getById($id);
            if ($domain['domain']) {
                $domainName = $domain['domain'];
                $period = $domain['period'];
                $jsonData = json_decode($domain['data'], TRUE);
                $shopperData = $jsonData['shopperData'];
                $renew = $this->gd->orderDomainRenewal($shopperData, $domainName);
                if (!$renew["error"]) {
                    $jsonData['renew'] = $renew;
                    $domainUpdate = $this->domainM->update($id, array('data' => json_encode($jsonData)));

                    if ($domainUpdate) {
                        $this->status->message($domainName . ' domain  successfully renewed!');
                    }
                }
            }
        }
        lib_redirect('account/default/domains');
    }

    public function domainInfoAction() {
        if (lib_request('id')) {
            $id = lib_request('id');
            $domain = $this->domainM->getById($id);
            if ($domain['domain']) {
                $info = $this->gd->getDomainInfo($domain['domain']);
            } else {
                lib_redirect('account/default/domains');
            }
        } else {
            lib_redirect('account/default/domains');
        }
        include $this->tpl->page('test/domain_info.php');
    }

    public function registerDomainAction() {
        if (lib_request('ids') || lib_post('id')) {
            if (lib_request('ids')) {
                $jsonIds = lib_request('ids');
            } elseif (lib_post('id')) {
                $jsonIds = lib_post('id');
            }
            $ids = json_decode($jsonIds);
            $domains = $this->domainM->getUserUnregisteredDomainsByIdArray($ids, $this->user_id);
            $shopperUserData = adfAuthentication::getUserData();
            if (!empty($domains)) {
                $countries = $this->userModel->getCountries();
                if (lib_request('ids')) {
                    $ids = json_decode($jsonIds);
                    $selectedReadings = lib_post('selectedReadings');
                    $phoneNumber = lib_post('phoneNumber');
                    $phoneNumber = lib_clean_phone($phoneNumber);

                    $shopperData['firstname'] = $shopperUserData['first_name'];
                    $shopperData['lastname'] = $shopperUserData['last_name'];
                    $shopperData['email'] = $shopperUserData['email'];
                    $shopperData['password'] = $shopperUserData['domain_password'];
                    $shopperData['phone'] =$shopperUserData['phone'];
                    $shopperData['phone_region'] =$shopperUserData['phone_region'];
                    $shopperData['acceptOrderTOS'] = 'agree';
                    $shopperData['address'] = $shopperUserData['address'];
                    $shopperData['city'] = $shopperUserData['city'];
                    $shopperData['state'] = $shopperUserData['state'];
                    $shopperData['zipcode'] = $shopperUserData['zipcode'];
                    $shopperData['country'] = $shopperUserData['country'];

                    //                    $shopperData['firstname'] = lib_post('first_name');
                    //                    $shopperData['lastname'] = lib_post('last_name');
                    //                    $shopperData['email'] = lib_post('email');
                    //                    $shopperData['password'] = lib_post('domain_password');
                    //                    $shopperData['phone'] =lib_post('phone');
                    //                    $shopperData['phone_region'] =lib_post('phone_region');
                    //                    $shopperData['acceptOrderTOS'] = 'agree';
                    //                    $shopperData['address'] = lib_post('address');
                    //                    $shopperData['city'] = lib_post('city');
                    //                    $shopperData['state'] = lib_post('state');
                    //                    $shopperData['zipcode'] = lib_post('zipcode');
                    //                    $shopperData['country'] = lib_post('country');


                    if (!$shopperUserData['user']) {
                        $shopperData['user'] = "createNew";
                    } else {
                        $shopperData['user'] = $shopperUserData['user'];
                    }
                    $period = lib_post('years');

                    $checkDomains = array();
                    foreach ($domains as $key => $value) {
                        $checkDomains[] = $value['domain'];
                    }
                    
                    $result = $this->gd->domainAvailable($checkDomains);

                    foreach ($domains as $key => $value) {
                        $finalizeDomain = $value['domain'];
                        if (!empty($result[$finalizeDomain])) {

                            $userDomain = $this->gd->purchaseDomain($shopperData, $finalizeDomain, $period, $ns = null);

                            if ($userDomain['orderid'] && !$userDomain['error']) {

                                $data = array(
                                    'user' => $userDomain['user'],
                                );
                                $update = $this->userModel->update($this->user_id, $data);
                                if ($update) {
                                    adfAuthentication::updateUserSessionData($this->user_id, $data);
                                }
                                $data['domain'] = $finalizeDomain;
                                $data['registered'] = 1;
                                $data['user_id'] = $this->user_id;
                                $data['phone_number'] = $shopperData['phone'];
                                $data['addedon'] = date("Y-m-d H:i:s");
                                $data['orderid'] = $userDomain['orderid'];
                                $data['resourceid'] = $userDomain['orderid'];
                                $data['period'] = date('Y-m-d', strtotime('+' . $period . ' year'));
                                $data['data'] = json_encode(array("shopperData" => $shopperData, 'domain' => $userDomain));
                                $domainSave = $this->domainM->update($value['id'], $data);
                                $this->status->message($finalizeDomain . ' Domain successfully registered!');
                            } else {
                                $this->status->error('Something wents wrong while registering ' . $finalizeDomain . ' domain');
                            }
                        } else {
                            $this->status->error($finalizeDomain . ' Domain is not available ');
                        }
                    }
                    lib_redirect('account/default/domains');
                } else {
                    //                    $_POST['firstname'] = $shopperUserData['first_name'];
                    //                    $_POST['lastname'] = $shopperUserData['last_name'];
                    //                    $_POST['email'] = $shopperUserData['email'];
                    //                    $_POST['password'] = $shopperUserData['domain_password'];
                    //                    $_POST['phone'] = $shopperUserData['phone'];
                    //                    $_POST['phone_region'] = $shopperUserData['phone_region'];
                    //                    $_POST['acceptOrderTOS'] = 'agree';
                    //                    $_POST['address'] = $shopperUserData['address'];
                    //                    $_POST['city'] = $shopperUserData['city'];
                    //                    $_POST['state'] = $shopperUserData['state'];
                    //                    $_POST['zipcode'] = $shopperUserData['zipcode'];
                    //                    $_POST['country'] = $shopperUserData['country'];
                }
            } else {
                $this->status->error("Can't find domain in Db!");
                lib_redirect('account/default/domains');
            }
            foreach ($domains as $key => $value) {
                $finalizeDomain = $value['domain'];
                $id = $value['id'];
            }
        } else {
            lib_redirect('account/default/domains');
        }

        include $this->tpl->page('test/register_domain.php');
    }

    public function domainNamePrivacyPurchaseAction() {
        if (lib_request('id')) {
            $id = lib_request('id');
            $domain = $this->domainM->getById($id);
            if ($domain) {
                $userData = adfAuthentication::getUserData();
                $data = json_decode($domain["data"], TRUE);
                $shopperData = $data['shopperData'];
                $domainName = $domain['domain'];
                if ($userData['dbpuser']) {
                    $privacyData['user'] = $userData['dbpuser'];
                }
                $privacyData['password'] = $shopperData['password'];
                $privacyData['email'] = 'info@' . $domainName;
                $privacy = $this->gd->orderDomainPrivacy($shopperData, $domainName, $privacyData);

                if ($privacy && !$privacy['error']) {
                    $updateData = array(
                        'dbpuser' => $privacy['dbpuser'],
                    );
                    $this->userModel->update($userData['id'], $updateData);

                    $data['purchase'] = $privacy;
                    $domainUpdate = $this->domainM->update($id, array('purchased' => 1, 'data' => json_encode($data)));

                    adfAuthentication::updateUserSessionData($shopperData['id'], $updateData);
                    $this->status->message('Domain successfully Purchased.');
                } else {
                    $this->status->error("Something went wrong! Please try later.");
                }
            } else {
                $this->status->error("Can't find domain");
            }
        } else {
            $this->status->error("Can't find domain id");
        }
        lib_redirect('account/default/domains');
    }

    public function editAction() {
        if (lib_is_post()) {
            $user_id = $this->user_id;

            $data = array(
                'username' => lib_post('username'),
                'first_name' => lib_post('first_name'),
                'last_name' => lib_post('last_name'),
                'email' => lib_post('email'),
                'country' => lib_post('country'),
                'zipcode' => lib_post('zipcode'),
                'state' => lib_post('state'),
                'city' => lib_post('city'),
                'address' => lib_post('address'),
                'phone_region' => lib_post('phone_region'),
                'phone' => lib_post('phone'),
                'domain_password' => lib_post('domain_password'),
                'state' => lib_post('state'),
            );

            $update = $this->userModel->update($user_id, $data);

            if ($update) {
                adfAuthentication::updateUserSessionData($user_id, $data);
                $this->status->message('Success Message');
            } else {
                $this->status->error('Error Message');
            }
            lib_redirect('account/default/home');
        }
        $userSession = adfAuthentication::getUserData();
        $user = $this->userModel->getByEmail($userSession['email']);
        include $this->tpl->page('test/edit_account.php');
    }

    public function editDomainAction() {
        if (lib_is_post() && lib_post('finalize_domain')) {
            $domainName = lib_post('finalize_domain');
            $id = lib_post('id');
            $domain = $this->domainM->getById($id);
            $result = $this->gd->domainAvailable(array($domainName));

            $period = 1;
            $jsonData = json_decode($domain['data'], TRUE);
            $shopperData = $jsonData['shopperData'];
            if ($result[$domainName]) {
                $userDomain = $this->gd->purchaseDomain($shopperData, $domainName, $period, $ns = null);

                if ($userDomain['orderid'] && !$userDomain['error']) {
                    /* $data = array(
                     'user' => $userDomain['user'],
                     );
                     $update = $this->userModel->update($shopperData['id'],$data);
                     if($update){
                     adfAuthentication::updateUserSessionData($shopperData['id'], $data);
                     } */
                    $data['domain'] = $domainName;
                    $data['voice_domain'] = $domainName;
                    $data['orderid'] = $userDomain['orderid'];
                    $data['resourceid'] = $userDomain['orderid'];
                    $jsonData['domain'] = $userDomain;
                    $data['data'] = json_encode($jsonData);
                    $domainSave = $this->domainM->update($id, $data);

                    $this->status->message('Domain ' . $domainName . ' successfully edited!');
                    lib_redirect('account/default/domains');
                } else {
                    $this->status->error('Something wents wrong while registering domain');
                    lib_redirect('account/default/editDomain?id=' . $id);
                }
            } else {
                $this->status->error('Domain is not available ');
                lib_redirect('account/default/editDomain?id=' . $id);
            }
        }
        if (!is_null(lib_request('id'))) {
            $domainM = new domain();
            if ($data = $domainM->getById(lib_request('id'))) {
                if ($data['user_id'] == $this->user_id) {
                    $_POST['domain'] = $data['domain'];
                } else {
                    lib_redirect('account/default/domains');
                }
            } else {
                $this->status->error("The requested domain was not found.");
            }
        } else {
            lib_redirect('account/default/domains');
        }

        include $this->tpl->page('test/edit_domain.php');
    }

    public function checkDomainAction() {
        if (lib_is_post()) {
            $isAvaliable = FALSE;
            $domainName = lib_post('domain');
            $domainName = validateDomainName($domainName);
            $domain = $domainName["domain"];
            $result = $this->gd->domainAvailable(array($domain));
            if (isset($result[$domain]) && $result[$domain]) {
                $isAvaliable = TRUE;
                $_POST['finalize_domain'] = $domain;
            } else {
                $this->status->error("This domain isn't available");
                $nameGenerates = $this->gd->nameGenerate($domain);
            }
            include $this->tpl->page('test/check_domain.php');
        } else {
            lib_redirect('account/default/domains');
        }
    }

    public function deleteDomainAction() {
        $id = lib_request('id');
        if (!is_null($id)) {
            $domainM = new domain();
            $domain = $domainM->getById($id);
            if ($domain['user_id'] == $this->user_id) {
                $delete = $domainM->deleteById($id);
                $this->status->message('Domain successfully deleted.');
            } else {
                $this->status->error("You can't delete this domain!");
            }
        }
        lib_redirect('account/default/domains');
    }

    public function addToCartAction() {
        if (lib_is_post()) {
            $domain = lib_post('finalize_domain');
            //$shoppingCart = $this->shoppingCart;

            if (!in_array($domain, $this->shoppingCart)) {
                $this->shoppingCart[] = $domain;
                setcookie("shoppingCart", json_encode($this->shoppingCart), COOKIE_TIME,COOKIE_PATH);
                lib_redirect('account/default/shoppingCart');
            } else {
                $this->status->error("This domain is already in your shopping cart");
                lib_redirect('account/default/searchdomain');
            }
        } else {
            lib_redirect('account/default/domains');
        }
    }

    public function addDomainToCartAction() {
        if (lib_is_ajax_request()) {
            $domain = lib_post('domain');
            $domain_price = lib_post('domain_price');
            $unit_price = lib_post('unit_price');
            $quantity = lib_post('quantity');    
            $voice_domain = lib_post('voice');      
            // add to cart
            $cartData = array(
                'user_id' => $this->user_id,
                'session_id' => lib_cookie('adfSession'),
                'domain' => $domain,
                'quantity' => $quantity,
                'unit_price' => $unit_price,
                'is_voice_domain' => $voice_domain
            );
            if (false !== $data = $this->_cart->addToCart($cartData)){
                $this->shoppingCart[] = $cartData = $data;
                $status = TRUE;
                $message = 'Added!';
            } else {
                $status = FALSE;
                $message = 'Already added!';
            }   
        } else {
            $status = FALSE;
            $message = 'Invalid request!';
        }
        sendResponse($status, $message, $cartData);
    }

    public function shoppingCartAction() {
        $shoppingCart = $this->getShoppingCart();

        include $this->tpl->page('test/shopping_cart.php');
    }

    public function removeFromCartAction() {
        $domain = '';
        if (lib_is_ajax_request()) {
            $domain = $_POST['domain'];
        }else if(lib_request('item')){
            $domain = lib_request('item');
        }
        $status = FALSE;
        $message = 'Not Available!';
        if (!empty($domain)) {
            $cartData = array(
                'user_id' => $this->user_id,
                'session_id' => lib_cookie('adfSession'),
                'domain' => $domain
            );
            if (true === $cartData = $this->_cart->deleteFromUserCart($cartData)) {
                $status = TRUE;
                $message = "Deleted!";
            }
            sendResponse($status, $message, $cartData);
            //lib_redirect('account/default/shoppingCart');
        }
        sendResponse($status, $message);
    }

    public function updateQuantityCartAction() {
        $data = array();
        if (lib_is_ajax_request()) {
            $domain = lib_post('domain');
            $quantity = lib_post('quantity');
            $cartData = array(
                'user_id' => $this->user_id,
                'session_id' => lib_cookie('adfSession'),
                'domain' => $domain,
                'quantity' => $quantity
            );
            if (false !== $data = $this->_cart->updateQuantity($cartData)){
                $status = TRUE;
                $message = 'Updated!';
            } else {
                $status = FALSE;
                $message = 'Not Available!';
            }
        } else {
            $status = FALSE;
            $message = 'Invalid request!';
        }
        sendResponse($status, $message, $data);
            
    }
    public function changePasswordAction() {
        $uData = adfAuthentication::getUserData();
        if (lib_is_post()) {
            try {
                $hasher = new adfPasswordHash();
                $old_password_hash = $hasher->get(lib_post('old_password'));

                if ($old_password_hash != $uData['password']) {
                    throw new Exception('The password you entered does not match the one on file');
                }

                $data['password_confirm'] = lib_post('new_password_confirm');
                $data['password'] = lib_post('new_password');


                if (false != $change = $this->userModel->resetPassword($uData['id'], $data)) {
                    $uData['password'] = $hasher->get(lib_post('new_password'));
                    adfAuthentication::updateUserSessionData($uData['id'], $data);
                    $this->status->message('Your password has been changed');
                } else {
                    $this->status->message($this->userModel->getError());
                }

                lib_redirect('account/default/home');
            } catch (Exception $e) {
                $this->status->error($e->getMessage());
            }
        }

        include $this->tpl->page('test/change_password.php');
    }
    
    public function searchDomainAction() {
        lib_redirect('searchDomain');
    }

    private function refundGenerate($original_trans, $refund_amount, $refund_trans, $domains, $creditcard, $expiration) {
        $xml = new AuthnetXML(true);
        $params = array(
            'refId' => $original_trans,
            'transactionRequest' => array(
                'transactionType' => 'refundTransaction',
                'amount' => $refund_amount,
                'refTransId' => $original_trans,
                'payment' => array(
                    'creditCard' => array(
                        'cardNumber' => $creditcard,
                        'expirationDate' => $expiration,
                    )
                )
            )
        );
        $count = 0;
        foreach($refund_trans as $domain_name => $trans_id) {
            $domain = $domains[$domain_name];
            $params['transactionRequest']['lineItems'][$count]['itemId'] = $domain['domain'];
            $params['transactionRequest']['lineItems'][$count]['name'] = $domain['domain'];
            $params['transactionRequest']['lineItems'][$count]['quantity'] = $domain['quantity'];
            $params['transactionRequest']['lineItems'][$count]['unitPrice'] = $domain['revised_price'];
            $count++; 
        }
        log_me($params);
        return $xml->createTransactionRequest($params);
    }
    
    private function createTransactionRequest($params, $domains, $userData) {
        
        $total = !empty($domains[0]->cart_total) ? $domains[0]->cart_total : 0;
        $params = array(
			'refId' => time(),
            'transactionRequest' => [
                'transactionType' => 'authCaptureTransaction',
                'amount' => $params['total'],
                'payment' => [
                    'creditCard' => [
                        'cardNumber' => $params['creditcard'],
                        'expirationDate' => $params['expiration'],
                        'cardCode' => $params['cvv']
                    ]
                ],
                'profile' => [
                    'createProfile' => 0,
                ],
                'order' => [
                    'invoiceNumber' => time(),
                    'description' => 'this is a test transaction',
                ],
                'lineItems' => [
                    'lineItem' => [
                    ]
                ],
                'customer' => [
                    'id' => $userData['id'],
                    'email' => $params['email'],
                ],
                'billTo' => [
                    'firstName' => $params['business_firstname'],
                    'lastName' => $params['business_lastname'],
                    'address' => $params['business_address'],
                    'city' => $params['business_city'],
                    'state' => $params['business_state'],
                    'zip' => $params['business_zipcode'],
                    'country' => 'USA',
                    'phoneNumber' => $params['business_telephone']
                ],
                'shipTo' => [
                    'firstName' => $params['business_firstname'],
                    'lastName' => $params['business_lastname'],
                    'address' => $params['business_address'],
                    'city' => $params['business_city'],
                    'state' => $params['business_state'],
                    'zip' => $params['business_zipcode'],
    				'country' => 'USA'
                ],
                'customerIP' => $_SERVER['REMOTE_ADDR'],
            ]
        );
        $count = 0;
        foreach($domains as $key => $domain) {
            $params['transactionRequest']['lineItems']['lineItem'][$count]['itemId'] = $domain['domain'];
            $params['transactionRequest']['lineItems']['lineItem'][$count]['name'] = $domain['domain'];
            $params['transactionRequest']['lineItems']['lineItem'][$count]['quantity'] = $domain['quantity'];
            $params['transactionRequest']['lineItems']['lineItem'][$count]['unitPrice'] = $domain['revised_price'];
            $count++; 
        }
        if (empty($userData['profile_id'])) {
            $params['transactionRequest']['profile']['createProfile'] = 1;
        }
        return $params;
    }
        
    public function getNameServerAction() {
        $data = array();
        $status = false;
        $message = "Invalid Request";
        if (lib_is_ajax_request()) {
            $domain = lib_post('domain');
            if (!empty($domain)) {
                $data = $this->name->get_nameserver($domain);
                if (!empty($data["result"]["code"]) && $data["result"]["code"] == 100) {
                    $data = $data["hosts"];
                }
                $status = true;
                $message = "Successful Request";
            }
        }
        sendResponse($status, $message, $data);
    }
    public function addNewNameServerAction() {
        $data = array();
        $status = false;
        $message = "Invalid Request";
        if (lib_is_ajax_request()) {
            $domain = lib_post('domain');
            $nameserver = lib_post('nameserver');
            if (!empty($domain) && !empty($nameserver)) {
                $data = $this->name->add_nameserver($domain, $nameserver, '12.12.12.12');
                if (!empty($data["result"]["code"]) && $data["result"]["code"] == 100) {
                    $status = true;
                    $message = "Successful Request";
                }
            }
        }
        sendResponse($status, $message, $data);
    }
    public function deleteNameServerAction() {
        $data = array();
        $status = false;
        $message = "Invalid Request";
        if (lib_is_ajax_request()) {
            $domain = lib_post('domain');
            $nameserver = lib_post('nameserver');
            if (!empty($domain) && !empty($nameserver)) {
                $data = $this->name->delete_nameserver($domain, $nameserver);
                if (!empty($data["result"]["code"]) && $data["result"]["code"] == 100) {
                    $status = true;
                    $message = "Successful Request";
                }
            }
        }
        sendResponse($status, $message, $data);
    }
    public function getDnsAction() {
        $data = array();
        $status = false;
        $message = "Invalid Request";
        if (lib_is_ajax_request()) {
            $domain = lib_post('domain');
            if (!empty($domain)) {
                $data = $this->name->get_dns($domain);
                if (!empty($data["result"]["code"]) && $data["result"]["code"] == 100) {
                    $data = $data["records"];
                }
                $status = true;
                $message = "Successful Request";
            }
        }
        sendResponse($status, $message, $data);
    }
    public function deleteDnsAction() {
        $data = array();
        $status = false;
        $message = "Invalid Request";
        if (lib_is_ajax_request()) {
            $domain = lib_post('domain');
            $record = lib_post('record');
            if (!empty($domain) && !empty($record)) {
                $data = $this->name->delete_dns($domain, $record);
                if (!empty($data["result"]["code"]) && $data["result"]["code"] == 100) {
                    $data = $data["records"];
                }
                $status = true;
                $message = "Successful Request";
            }
        }
        sendResponse($status, $message, $data);
    }
    public function addDnsAction() {
        $data = array();
        $status = false;
        $message = "Invalid Request";
        if (lib_is_ajax_request()) {
            if (!empty(lib_post('domain')) && !empty(lib_post('content'))
                && !empty(lib_post('hostname')) && !empty(lib_post('type')) 
                && (lib_post('priority')) && !empty(lib_post('ttl'))) {
                    $dns = array(
                        'hostname' => lib_post('hostname'), 
                        'content' => lib_post('content'), 
                        'type' => lib_post('type'), 
                        'priority' => lib_post('priority'), 
                        'ttl' => lib_post('ttl')
                    );
                $data = $this->name->add_dns(lib_post('domain'), $dns);
                if (!empty($data["result"]["code"]) && $data["result"]["code"] == 100) {
                    $status = true;
                    $message = "Successful Request";
                }
            }
        }
        sendResponse($status, $message, $data);
    }
    
    public function autoRenewalAction() {
        $status = false;
        $message = "";
        $data = '';
        if (lib_is_ajax_request()) {
            if (!empty(lib_post('domain'))) {
                $data = $this->domainM->getOneByField('domain', lib_post('domain'));
                if (!empty($data['id'])) {
                    $status = true;
                    $auto_renew = 0;
                    $message = "Auto Renew Off";
                    if ($data['auto_renew'] == 0) {
                        // auto renew off and On it
                        $auto_renew = 1;
                        $message = "Auto Renew On";    
                    }
                    $data = $this->domainM->updateFieldById($data['id'], 'auto_renew', $auto_renew);
                } else {
                    $message = " No domain found";
                }
            }
        }
        sendResponse($status, $message, $data);
    }
    
    public function updatePhoneAction() {
        $status = false;
        $message = "";
        $data = '';
        if (lib_is_ajax_request()) {
            if (!empty(lib_post('domain')) && !empty(lib_post('phone_number'))) {
                $data = $this->domainM->getOneWhere("domain = '".lib_post('domain')."' and user_id = ".$this->user_id);
                //$data = $this->domainM->getOneByField('domain', lib_post('domain'));
                if (!empty($data['id'])) {
                    $status = true;
                    $message = "Successfully updated Phone Number";
                    $data = $this->domainM->updateFieldById($data['id'], 'phone_number', str_ireplace('-', '', lib_post('phone_number')));
                } else {
                    $message = " No domain found";
                }
            }
        }
        sendResponse($status, $message, $data);
    }
}
