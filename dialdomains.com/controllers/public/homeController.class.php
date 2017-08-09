<?php

class homeController extends adfBaseController {
    
    private $gd;
    private $user;
    private $domainM;
    public function __construct() {
        $this->domainM = new domain();
        $this->gd = new goDaddyToolbox();
        $this->user = new User();
        parent::__construct();
    }
    
//    public function welcomeAction() {
//        include $this->tpl->page('welcome/index.php');
//    }
//    
//    public function privacyAction() {
//        include $this->tpl->page('test/privacy.php');
//    }   
//    
//    public function servicesAction() {
//        include $this->tpl->page('test/services.php');
//    }
//
//    public function loginAction() {
//        include $this->tpl->page('test/login.php');
//    }

//    public function aboutAction() {
//        include $this->tpl->page('test/about.php');
//    }

//    public function landingAction() {
//        include $this->tpl->page('landing/index.html');
//    }
//
//    public function siteAction() {
//        include $this->tpl->page('site/panel.php');
//    }
//
//    public function testAction() {
//        include $this->tpl->page('test/index.php');
//    }
//
//    public function registerAction() {
//        if (lib_is_post()){
//            //add registration verification hash
//            $salt = SALT;
//            $value = lib_post('email');
//            $hashed_value = md5($salt . $value);
//
//
//            //adds new user to database
//            $data = array(
//                'username' => lib_post('user'),
//                'first_name' => lib_post('first_name'),
//                'last_name' => lib_post('last_name'),
//                'email' => lib_post('email'),
//                'password' => lib_post('password'),
//                'password_confirm' => lib_post('password_confirm'),
//                'verification_key' => $hashed_value,
//                'group_id' => 4
//            );
//            
//            
//            $usernameExist = $this->user->getOneByField('username', lib_post('user'));
//            if(!$usernameExist){
//                $emailExist = $this->user->getOneByField('username', lib_post('user'));
//                if(!$emailExist){
//                    $add = $this->user->add($data);
//                    if($add){
//                        $subject = "Verify Your Email Address";
//                        $part_plain = "1";
//                        $part_html = "Please click this link to verify your email address.\r\n dialdomains.us/home/verify?account=" . $hashed_value;
//                        $replace_vars = "";
//                        $recipient_email = lib_post('email');
//                        $sender_email = "adsmoneytreeleadsystems@gmail.com";
//
//                        $mailer = new adfSwiftWrapper();
//
//                        $mailer->send_message($subject, $part_plain, $part_html, array(), $recipient_email, $sender_email);
//                        header('Location: /home/login');
//                    }
//                }  else {
//                    $_POST['user'] = 'email';
//                    $this->status->error("This email is already used"); 
//                }
//            }  else {
//                $_POST['user'] = '';
//                $this->status->error("This username is already used"); 
//            }
//            
//        }
//        
//        include $this->tpl->page('test/register.php');
//        
//        //if registration was not succesful
//        //include $this->tpl->page('test/login.php');	
//        //if not registration attempt was made
//        //include $this->tpl->page('test/register.php');	
//        //if registraion was successful		
//        //include $this->tpl->page('test/login.php');	
//
//        
//    }

//    public function forgotpasswordAction() {
//        $theme = adfTheme::getInstance();
//        $theme->setTemplate('metronic');
//        if (isset($_POST['submit'])) {
//            $recover_attempt = true;
//
//            //Generate new random password
//            $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
//            $pass = array(); //remember to declare $pass as an array
//            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
//            for ($i = 0; $i < 8; $i++) {
//                $n = rand(0, $alphaLength);
//                $pass[] = $alphabet[$n];
//            }
//
//
//            //set password   
//            $userM = new user();
//            //check if  email was submitted
//            if (null !== lib_post('email')) {
//                $user = $userM->getByEmail($_POST['email']);
//                $userM->update($user['id'], array('password' => $new_password, 'password_confirm' => $new_password));
//            }
//
//            //send email with new password
//            $subject = "Password Recovery";
//            $part_plain = "";
//            $part_html = "Your Password has been reset to " . $new_password . " Please click this link to log into your account. dialdomains.us/home/login";
//            $replace_vars = "";
//            $recipient_email = lib_post('email');
//            $sender_email = "adsmoneytreeleadsystems@gmail.com";
//            $mailer = new adfSwiftWrapper();
//            $mailer->send_message($subject, $part_plain, $part_html, array(), $recipient_email, $sender_email);
//            // display view
//        }
//        include $this->tpl->page('test/forgot_password.php');
//    }

//    public function verifyAction() {
//
//        if (isset($_GET['account'])) {
//            $vkey = $_GET['account'];
//            $userM = new user();
//            $user = $userM->getByVerificationKey($vkey);
//            $userM->update($user['id'], array('verification_status' => '1'));
//
//            include $this->tpl->page('test/verify.php');
//        } else {
//            header('Location: /home/test');
//        }
//    }
    
//    public function searchDomainAction(){
//        $domainName = '';
//        
//        if (lib_is_post()){
//            $domainName = lib_post('domain_name');
//            $domainName = validateDomainName($domainName);
//            $domain = $domainName["domain"];
//            $isAvaliable = FALSE;
//            $result = $this->gd->domainAvailable(array($domain));
//            if(isset($result[$domain]) && $result[$domain]){
//                $isAvaliable = TRUE;
//            }  else {
//                $nameGenerates =  $this->gd->nameGenerate($domain);
//            }
//        }
////        $isAvaliable = FALSE;
////        if (lib_is_post()) {            
////            $domainNames = validateDomainName(lib_post('domain_name'));
////            
////            $_POST['domain'] = $domainNames['domain'];
////            $result = $this->gd->domainAvailable(array($domainNames['domain']));            
////            if ($result[$domainNames['domain']]) {
////                $isAvaliable = TRUE;
////                $_POST['finalize_domain'] = $domainNames['domain'];
////            }
////            $nameGenerates = $this->gd->nameGenerate($domainNames['domain']);            
////        }
////        $tlds = $this->domainM->getAvailableTlds();
//        include $this->tpl->page('test/searchDomain.php');
//    }
//        public function addDomainToCartAction() {
//        if (lib_is_ajax_request()) {
//            $domain = lib_post('domain');
//            $data = array();
//            if (!in_array($domain, $this->shoppingCart)) {
//                $this->shoppingCart[] = $domain;
//                setcookie("shoppingCart", json_encode($this->shoppingCart), COOKIE_TIME);
//                setcookie("shoppingCart111", json_encode($this->shoppingCart), COOKIE_TIME);
//                $status = TRUE;
//                $message = 'Added!';
//                $data = array('shopingCart' => json_encode($this->shoppingCart));
//            } else {
//                $status = FALSE;
//                $message = 'Already added!';
//            }
//        } else {
//            $status = FALSE;
//            $message = 'Invalid request!';
//        }
//        sendResponse($status, $message, $data);
//    }
//      public function shoppingCartAction() {
//
//        $shoppingCart = $this->getShoppingCart();
//
//        include $this->tpl->page('test/shopping_cart.php');
//    }
//      public function removeFromCartAction() {
//        if (lib_is_ajax_request()) {
//            $domain = $_POST['domain'];
//            $shoppingCart = $this->getShoppingCart();
//            if (!empty($shoppingCart)) {
//                $key = array_search($domain, $shoppingCart);
//                if ($key !== FALSE) {
//                    unset($shoppingCart[$key]);
//                    setcookie("shoppingCart", json_encode($shoppingCart), time() + 3600 * 24 * 30);
//                    $this->status->message($domain . ' successfully removed from shopping cart.');
//                }
//            }
//        }

//            if(lib_request('item')){
//                $item = lib_request('item');
//                $shoppingCart = $this->getShoppingCart();
//                if(!empty($shoppingCart)){
//                    $key = array_search($item,$shoppingCart);
//                    if($key !== FALSE){
//                        unset($shoppingCart[$key]);
//                        setcookie( "shoppingCart", json_encode($shoppingCart), time()+3600*24*30); 
//                        $this->status->message($item.' successfully removed from shopping cart.');
//                    }
//                }
//            }
//            lib_redirect('account/default/shoppingCart');
//    }

}
