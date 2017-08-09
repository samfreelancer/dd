<?php
class defaultController extends adfBaseController {

    private $gd;
    private $user;
    private $domainM;
    private $_cart;
    private $_cartItems;
    private $name;
    public function __construct() {
        $this->domainM = new domain();
        //$this->gd = new goDaddyToolbox();
        $this->user = new User();
        $this->_cart =  new cart();
        $this->_cartItems = new cartItems(); 
        $this->name = new nameComApi();
        $this->name->login(NAME_USERNAME, NAME_API_TOKEN);
        parent::__construct();
    }

    public function indexAction() {
        if (adfAuthentication::isLoggedIn()) {
            lib_redirect('account/default/home');
        } else {
//            lib_redirect('/home/test');
            include $this->tpl->page('test/index.php');
        }
    }
    public function aboutAction() {
        include $this->tpl->page('test/about.php');
    }
     public function welcomeAction() {
        include $this->tpl->page('welcome/index.php');
    }

    public function privacyAction() {
        include $this->tpl->page('test/privacy.php');
    }

    public function servicesAction() {
        include $this->tpl->page('test/services.php');
    }
    public function landingAction() {
        include $this->tpl->page('landing/index.html');
    }

    public function siteAction() {
        include $this->tpl->page('site/panel.php');
    }

    public function testAction() {
        include $this->tpl->page('test/index.php');
    }

    public function registerAction() {
        if (lib_is_post()){
            //add registration verification hash
            $salt = SALT;
            $value = lib_post('email');
            $hashed_value = md5($salt . $value);


            //adds new user to database
            $data = array(
                'username' => lib_post('user'),
                'first_name' => lib_post('first_name'),
                'last_name' => lib_post('last_name'),
                'email' => lib_post('email'),
                'password' => lib_post('password'),
                'password_confirm' => lib_post('password_confirm'),
                'verification_key' => $hashed_value,
                'group_id' => 4,
                'ip_restricted' => 'no'
            );

            //print_r($data);

            $usernameExist = $this->user->getOneByField('username', lib_post('user'));
            if(!$usernameExist){
                $emailExist = $this->user->getOneByField('username', lib_post('user'));
                if(!$emailExist){
                    $add = $this->user->add($data);
                    if($add){
                        $subject = "Verify Your Email Address";
                        $part_plain = "1";
                        $part_html = "Please click this link to verify your email address.\r\n dialdomains.us/verify?account=" . $hashed_value;
                        $replace_vars = "";
                        $recipient_email = lib_post('email');
                        $sender_email = "adsmoneytreeleadsystems@gmail.com";

                        $mailer = new adfSwiftWrapper();

                        $mailer->send_message($subject, $part_plain, $part_html, array(), $recipient_email, $sender_email);
                        header('Location: /login');
                    }
                }  else {
                    $_POST['user'] = 'email';
                    $this->status->error("This email is already used");
                }
            }  else {
                $_POST['user'] = '';
                $this->status->error("This username is already used");
            }

        }

        include $this->tpl->page('test/register.php');

        //if registration was not succesful
        //include $this->tpl->page('test/login.php');
        //if not registration attempt was made
        //include $this->tpl->page('test/register.php');
        //if registraion was successful
        //include $this->tpl->page('test/login.php');


    }
     public function verifyAction() {

        if (isset($_GET['account'])) {
            $vkey = $_GET['account'];
            $userM = new user();
            $user = $userM->getByVerificationKey($vkey);
            echo 'user id : '.$user['id'].'</br>';
            $userM->update($user['id'], array('verification_status' => '1'));
            include $this->tpl->page('test/verify.php');
        } else {
            header('Location: /test');
        }
    }
    public function forgotpasswordAction() {
        $theme = adfTheme::getInstance();
        $theme->setTemplate('metronic');
        if (isset($_POST['submit'])) {
            $recover_attempt = true;

            //Generate new random password
            $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }


            //set password
            $userM = new user();
            //check if  email was submitted
            if (null !== lib_post('email')) {
                $user = $userM->getByEmail($_POST['email']);
                $userM->update($user['id'], array('password' => $new_password, 'password_confirm' => $new_password));
            }

            //send email with new password
            $subject = "Password Recovery";
            $part_plain = "";
            $part_html = "Your Password has been reset to " . $new_password . " Please click this link to log into your account. dialdomains.us/login";
            $replace_vars = "";
            $recipient_email = lib_post('email');
            $sender_email = "adsmoneytreeleadsystems@gmail.com";
            $mailer = new adfSwiftWrapper();
            $mailer->send_message($subject, $part_plain, $part_html, array(), $recipient_email, $sender_email);
            // display view
        }
        include $this->tpl->page('test/forgot_password.php');
    }
    public function loginAction() {
        $user = new user();
       if (lib_is_post() && empty($_POST['confirm'])) {
            if (empty($_SESSION['login_tries'])) {
                $_SESSION['login_tries'] = 1;
            } else {
                $_SESSION['login_tries']++;
            }

            try {
                $auth = new adfAuthentication();
                $shoppingCart = $this->getShoppingCart();
                if ($auth->login(new user(), new adfPasswordHash(), lib_post('username'), lib_post('password'))) {
                    $group_id = adfAuthentication::getCurrentPermissionGroupId();
                    $userId= adfAuthentication::getCurrentUserId();
                    if(!empty($shoppingCart) && $shoppingCart != 'null'){
                        $session = lib_cookie('adfSession');
                        $this->_cart =  new cart();
                        $this->_cart->updateCartUser($session, $userId);
                        $shoppingCart = $this->getShoppingCart();
                    }
                    if(isset($_SESSION['LOGIN_REDIRECT']) && !empty($_SESSION['LOGIN_REDIRECT'])) {
                    	lib_redirect($_SESSION['LOGIN_REDIRECT']);
                    }
                    lib_redirect('account/default/shoppingCart');
                } else {
                    throw new Exception($auth->lastError());
                }
            } catch (Exception $e) {
                $this->status->error($e->getMessage());
            }


        }

        include $this->tpl->page('test/login.php');
    }
    public function loginORregisterAction() {
        include $this->tpl->page('test/login_or_register.php');
    }

    public function logoffAction() {
        adfAuthentication::logoffCurrentUser();
        $this->status->message("You have been logged off.");
        lib_redirect(adfAuthentication::getLoginPath());
    }

    public function recoverAction() {
        include $this->tpl->page('public/recover.php');
    }

    public function resetAction() {
        include $this->tpl->page('public/reset.php');
    }

    public function searchDomainAction() {
        $isAvaliable = FALSE;
        $error = '';
        $availableVoiceDomains = array();
        $tlds = $this->domainM->getAvailableTlds();
        if (lib_is_post()) {
            $domainNames = validateDomainName(lib_post('domain'));
            $_POST['domain'] = $domainNames['domain'];
            $result = $this->name->check_domain($domainNames['domain'], $tlds, array('availability', 'suggested'));
            if (!empty($result)) {
                $result = json_decode(json_encode($result), TRUE);
                log_me($result);
                if(true === lib_check_name_response($result)) {
                    lib_improve_price($result);
                    // success response
                    if (!empty($result['domains'][$domainNames['domain']]['avail'])
                        && true === $result['domains'][$domainNames['domain']]['avail']) {
                        $isAvaliable = TRUE;
                        $_POST['finalize_domain'] = $domainNames['domain'];
                    }
                    if(!empty($result['suggested']) && is_array($result['suggested']) && count($result['suggested']) > 0) {
                        foreach ($result['suggested'] as $alternativeDomain => $meta) {
                            if(FALSE === $this->domainM->getOneWhere("`voice_domain` = '" . $alternativeDomain . "' AND deleted = 0")
                                && TRUE === $meta['avail']) {
                                $result['suggested'][$alternativeDomain]['voice'] = true;
                            }else {
                                $result['suggested'][$alternativeDomain]['voice'] = false;
                            }
                        }
                    }
                    if($isAvaliable === FALSE && $domainNames['available'] === TRUE) {
                        if(is_array($domainNames['availables']) && count($domainNames['availables']) > 0) {
                            $domainNames['availables'][] = $domainNames['domain'];
                            foreach ($domainNames['availables'] as $voiceDomain) {
                                if(FALSE === $this->domainM->getOneWhere("`voice_domain` = '" . $voiceDomain . "' AND deleted = 0")) {
                                   $availableVoiceDomains[] = $voiceDomain;
                                }
                            }
                        }
                    }
                    $postedTlds = lib_post("tlds");
                    $filteredDomains = array();
                    if(count($postedTlds) > 0) {
                        foreach ($result['suggested'] as $index => $value) {
                            $domainParts = explode('.', $index);
                            if (in_array(strtolower($domainParts[count($domainParts) -1]), $postedTlds)) {
                                $filteredDomains[$index] = $value;
                            }
                        }
                        $result['suggested'] = $filteredDomains;
                    }
                } else {
                    // error response
                    $error = !empty($result['result']['message']) ? $result['result']['message'] : 'Search failed'; 
                }
            } else {
                $error = 'Search Failed';
            }
        }
        include $this->tpl->page('test/search_domain.php');
    }
    /*
     * Godaddy Search domain api
     * No more in use after name.com implementation
     * @toDo: can be removed
     */
    /*public function searchDomainAction() {
        $isAvaliable = FALSE;
        $availableVoiceDomains = array();
        if (lib_is_post()) {
            $domainNames = validateDomainName(lib_post('domain'));
            $_POST['domain'] = $domainNames['domain'];
            $result = $this->gd->domainAvailable(array($domainNames['domain']));
            if (isset($result[$domainNames['domain']]) && $result[$domainNames['domain']]) {
                $isAvaliable = TRUE;
                $_POST['finalize_domain'] = $domainNames['domain'];
            }
            
            $nameGenerates = $this->gd->nameGenerate($domainNames['domain']);
            $alternativeDomainsStatus = array();
            if(is_array($nameGenerates) && count($nameGenerates) > 0) {
                foreach ($nameGenerates as $alternativeDomain) {
                    if(FALSE === $this->domainM->getOneWhere("`voice_domain` = '" . $alternativeDomain . "' AND deleted = 0")) {
                        $alternativeDomainsStatus[$alternativeDomain] = array('voice' => true);
                    }else {
                        $alternativeDomainsStatus[$alternativeDomain] = array('voice' => false);
                    }
                }
            }
            
            $nameGenerates = $alternativeDomainsStatus;
            if($isAvaliable === FALSE && $domainNames['available'] === TRUE) {
                if(is_array($domainNames['availables']) && count($domainNames['availables']) > 0) {
                    $domainNames['availables'][] = $domainNames['domain'];
                    foreach ($domainNames['availables'] as $voiceDomain) {
                        if(FALSE === $this->domainM->getOneWhere("`voice_domain` = '" . $voiceDomain . "' AND deleted = 0")) {
                           $availableVoiceDomains[] = $voiceDomain;
                        }
                    }
                }
            }
            $postedTlds = lib_post("tlds");
            $filteredDomains = array();
            if(count($postedTlds) > 0) {
                foreach ($nameGenerates as $index => $value) {
                    $domainParts = explode('.', $value);
                    if (in_array(strtolower($domainParts[count($domainParts) -1]), $postedTlds)) {
                        $filteredDomains[] = $value;
                    }
                }
                $nameGenerates = $filteredDomains;
            }
        }
        $tlds = $this->domainM->getAvailableTlds();
        include $this->tpl->page('test/search_domain.php');
    }*/
    

    /*public function searchDomainAction(){
        $domainName = '';

        if (lib_is_post()){
            $domainName = lib_post('domain_name');
            $domainName = validateDomainName($domainName);
            $domain = $domainName["domain"];
            $isAvaliable = FALSE;
            $result = $this->gd->domainAvailable(array($domain));
            if(isset($result[$domain]) && $result[$domain]){
                $isAvaliable = TRUE;
                $this->status->message('Domain is available.');
            }  else {
                $nameGenerates =  $this->gd->nameGenerate($domain);
                $this->status->error('Sorry, domain is not available.');
            }
        }
//        $isAvaliable = FALSE;
//        if (lib_is_post()) {
//            $domainNames = validateDomainName(lib_post('domain_name'));
//
//            $_POST['domain'] = $domainNames['domain'];
//            $result = $this->gd->domainAvailable(array($domainNames['domain']));
//            if ($result[$domainNames['domain']]) {
//                $isAvaliable = TRUE;
//                $_POST['finalize_domain'] = $domainNames['domain'];
//            }
//            $nameGenerates = $this->gd->nameGenerate($domainNames['domain']);
//        }
//        $tlds = $this->domainM->getAvailableTlds();
        include $this->tpl->page('test/search_domain.php');
    }*/
    public function addDomainToCartAction() {
        if (lib_is_ajax_request()) {
            $domain = lib_post('domain');
            $domain_price = lib_post('domain_price');
            $unit_price = lib_post('unit_price');
            $quantity = lib_post('quantity');          
            $voice_domain = lib_post('voice'); 
            // add to cart
            $cartData = array(
                'user_id' => isset($this->user_id) ? $this->user_id: '',
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
                'user_id' => isset($this->user_id) ? $this->user_id : '',
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
}