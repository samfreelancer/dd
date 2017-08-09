<?php 
/*
 * This file check for domain auto renewal and process
 * them.
 */
include_once 'baseCron.class.php';

class domainAutoRenewal extends baseCron {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function fire() {
        $date_check = date('Y-m-d', strtotime("+7 days"));
        $query = "SELECT u.profile_id,u.email,u.payment_profile_id_list,d.id as domain_id, d.user_id,d.domain,d.voice_domain,d.is_voice_domain,ti.unit_price FROM user as u INNER JOIN domain as d ON u.id = d.user_id INNER JOIN transactions as t ON u.id = t.user_id INNER JOIN transactionItems as ti ON t.original_trans = ti.transaction_id WHERE u.profile_id IS NOT NULL";
        $query .= " AND u.profile_id <> '' AND u.payment_profile_id_list IS NOT NULL AND d.deleted = 0 AND d.auto_renew = 1 AND d.period IS NOT NULL AND date(d.period) between '".date('Y-m-d')."' AND '".$date_check."'";
        //echo $query."\n";exit();
        $items['items'] = [];
        $r_domains = [];
        $domainObj = new domain();
        $data = $domainObj->getByQuery($query);
        //print_r($data);
        if (count($data)) {
            $domainObj = new domain();
            $tlds = $domainObj->getAvailableTlds();
            $domain_priceM = new domainprice();
            $domain_price = $domain_priceM->getDomainsPrice();
            $nameObj = new nameComApi();
            $nameObj->login(NAME_USERNAME, NAME_API_TOKEN);
            foreach ($data as $d) {
                if ($d['is_voice_domain'] != 1) {
                    // not a voice domain
                    //print_r($d);
                    $ar = [];    
                    $price = lib_improve_price($ar, $d['unit_price']);
                    $paymentResponse = $this->createPayment($price, $d);
                    if ($paymentResponse->messages->resultCode == "Ok") {
                        $items['items'][] = $this->addTransaction($paymentResponse, $d, $price);
                    } else {
                        echo  "payment failed for domain ".$d['domain'];
                        //log_me("payment failed for domain ".$d['domain']);
                    }
                } else {
                    // voice domain
                    $domainIndex = substr($d['domain'], strrpos($d['domain'], '.') + 1);
                    $paymentResponse = $this->createPayment($domain_price[$domainIndex]['price'], $d);
                    if ($paymentResponse->messages->resultCode == "Ok") {
                        $this->addTransaction($paymentResponse, $d, $domain_price[$domainIndex]['price']);
                        // renewed voice domain
                        $r_domains[] = $d['domain'];
                    } else {
                        echo "payment failed for voice domain ".$d['domain'];
                        //log_me("payment failed for voice domain ".$d['domain']);
                    }
                }
            }
            
            // renew all items on name.com
            if (count($items['items'])) {
               $result = $nameObj->order($items);
               //print_r($result);
               if (!empty($result['result']['code']) && $result['result']['code'] == 100) {
                   foreach ($result['results'] as $item) {
                       if ($item['success']) {
                           // renewed domain
                           $r_domains[] = $item['domain_name'];
                       }
                   }
               }
            }
            if (count($r_domains)) {
                foreach ($r_domains as $domain) {
                	$sql = "UPDATE domain SET period = DATE_ADD(period, INTERVAL 1 YEAR), last_auto_renewal = NOW() where domain = '".$domain."'";
                	//echo $sql."\n";
                	$domainObj->executeQuery($sql);
                }
            }
        }
    }
    
    private function createPayment($price, $d) {
        $params = $this->createTransactionRequest($price, $d);
        //print_r($params);
        $request = AuthnetApiFactory::getJsonApiHandler(LOGIN_ID_TEST, TRANSACTION_KEY_TEST, AuthnetApiFactory::USE_DEVELOPMENT_SERVER);
        return $request->createTransactionRequest($params);
    }
    private function addTransaction($paymentResponse, $d, $price) {
        // add transaction entry
        $transaction = array(
            'approval_code' => $paymentResponse->transactionResponse->authCode,
            'avs_result' => $paymentResponse->transactionResponse->avsResultCode,
            'cvv_result' => $paymentResponse->transactionResponse->cvvResultCode,
            'cavv_result' => $paymentResponse->transactionResponse->cavvResultCode,
            'transaction_id' => $paymentResponse->transactionResponse->transId,
            'user_id' => $d['user_id'],
            'status' => 'paid',
            'amount_paid' => $price,
            'original_trans' => $paymentResponse->transactionResponse->transId
        );
        $transactionObj = new transactions();
        $transactionObj->add($transaction);
        //log_me($transaction);
        return [
            'order_type' => 'domain/renew',
            'domain_name' => $d['domain'],
            'period' => 1
        ];
    }
    private function createTransactionRequest($price, $domain) {
            
        $ppil = json_decode($domain['payment_profile_id_list']);
        $params = array(
            'transactionRequest' => [
                'transactionType' => 'authCaptureTransaction',
                'amount' => $price,
                'profile' => [
                    'customerProfileId' => $domain['profile_id'],
                    'paymentProfile' => [
                        'paymentProfileId' => $ppil[0],
                    ]
                ],
                'order' => [
                    'invoiceNumber' => time(),
                    'description' => 'this is a renewal domain transaction',
                ],
                'lineItems' => [
                    'lineItem' => [
                        [
                            'itemId' => $domain['domain'],
                            'name' => $domain['domain'],
                            'quantity' => 1,
                            'unitPrice' => $price
                        ]
                    ]
                ],
                'customer' => [
                    'id' => $domain['user_id'],
                    'email' => $domain['email'],
                ],
            ]
        );
        return $params;
    }
}
    
$domainAutoRenewal = new domainAutoRenewal;
$domainAutoRenewal->fire();

?>