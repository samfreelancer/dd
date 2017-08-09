<?php

class user extends adfModelBase {

    public function getByVerificationKey($vkey){
        return $this->getOneWhere("verification_key = '".$this->db->escape($vkey)."'");
    }    
    public function getByUserName($username){
        return $this->getOneWhere("username = '".$this->db->escape($username)."'");
    }

    public function resetPassword($id, $data) {
        try {
            $hasher = new adfPasswordHash();
            $data['password_length'] = strlen($data['password']); // for validation;
            $data['password'] = $hasher->get($data['password']);
            $data['password_confirm'] = $hasher->get($data['password_confirm']);
            return parent::update($id, $data);
        }
        catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        try {
            
            if (!empty($data['password'])) {
                $hasher = new adfPasswordHash();
                $data['password_length'] = strlen($data['password']); // for validation;
                $data['password'] = $hasher->get($data['password']);
                $data['password_confirm'] = $hasher->get($data['password_confirm']);
            }
            else {
                unset($data['password']);
            }
            
            return parent::update($id, $data);
        }
        catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    public function getByEmail($email) {
        return $this->getOneByField('email', $email);
    }

    public function add($data) {
        try {
            $hasher = new adfPasswordHash();
            $data['password_length'] = strlen($data['password']); // for validation;
            $data['password'] = $hasher->get($data['password']);
            $data['password_confirm'] = $hasher->get($data['password_confirm']);
            
            
            return parent::add($data);
        }
        catch (Exception $e) {
            $this->db->rollback();
            $this->setError($e->getMessage());
            return false;
        }
    }

    public function delete() {
        return $this->updateFieldById(lib_request('id'), 'status', 'disabled');
    }

    public function updateLastLoginById($id) {
        return $this->db->query("UPDATE `{$this->_table}` SET last_login = NOW() WHERE `id` = '" . $this->db->escape($id) . "'");
    }
    
    public function updateBuyerProfile($paymentResponse, $userData) {
        $u_data = [];
        if (!empty($paymentResponse->profileResponse)) {
            if (!empty($paymentResponse->profileResponse->customerProfileId) && empty($userData['profile_id'])) {
                $u_data['profile_id'] = $paymentResponse->profileResponse->customerProfileId;
            }
            if (!empty($paymentResponse->profileResponse->customerPaymentProfileIdList)) {
                $u_data['payment_profile_id_list'] = json_encode($paymentResponse->profileResponse->customerPaymentProfileIdList);
            }
            if (!empty($paymentResponse->profileResponse->customerShippingAddressIdList)) {
                $u_data['shipping_address_id_list'] = json_encode($paymentResponse->profileResponse->customerShippingAddressIdList);
            }
            if (count($u_data)) {
                return $this->update($userData['id'], $u_data);
            }    
        }
        return false;
    }
    
    public function getCountries(){
        return  array("Bangladesh","Belgium","Burkina Faso","Bulgaria","Bosnia and Herzegovina","Barbados","Wallis and Futuna",
            "Saint Bartelemey","Bermuda","Brunei Darussalam","Bolivia","Bahrain","Burundi","Benin","Bhutan","Jamaica","Bouvet Island",
            "Botswana","Samoa","Brazil","Bahamas","Jersey","Belarus","Other Country","Latvia","Rwanda","Serbia","Timor-Leste","Reunion",
            "Luxembourg","Tajikistan","Romania","Papua New Guinea","Guinea-Bissau","Guam","Guatemala",
            "South Georgia and the South Sandwich Islands","Greece","Equatorial Guinea","Guadeloupe","Japan","Guyana","Guernsey",
            "French Guiana","Georgia","Grenada","United Kingdom","Gabon","El Salvador","Guinea","Gambia","Greenland","Gibraltar",
            "Ghana","Oman","Tunisia","Jordan","Croatia","Haiti","Hungary","Hong Kong","Honduras","Heard Island and McDonald Islands",
            "Venezuela","Puerto Rico","Palestinian Territory","Palau","Portugal","Svalbard and Jan Mayen","Paraguay","Iraq","Panama",
            "French Polynesia","Belize","Peru","Pakistan","Philippines","Pitcairn","Turkmenistan","Poland","Saint Pierre and Miquelon",
            "Zambia","Western Sahara","Russian Federation","Estonia","Egypt","Tokelau","South Africa","Ecuador","Italy","Vietnam",
            "Solomon Islands","Europe","Ethiopia","Somalia","Zimbabwe","Saudi Arabia","Spain","Eritrea","Montenegro","Moldova, Republic of",
            "Madagascar","Saint Martin","Morocco","Monaco","Uzbekistan","Myanmar","Mali","Macao","Mongolia","Marshall Islands","Macedonia",
            "Mauritius","Malta","Malawi","Maldives","Martinique","Northern Mariana Islands","Montserrat","Mauritania","Isle of Man","Uganda",
            "Tanzania, United Republic of","Malaysia","Mexico","Israel","France","British Indian Ocean Territory","France, Metropolitan",
            "Saint Helena","Finland","Fiji","Falkland Islands (Malvinas)","Micronesia, Federated States of","Faroe Islands","Nicaragua",
            "Netherlands","Norway","Namibia","Vanuatu","New Caledonia","Niger","Norfolk Island","Nigeria","New Zealand","Nepal","Nauru",
            "Niue","Cook Islands","Cote d'Ivoire","Switzerland","Colombia","China","Cameroon","Chile","Cocos (Keeling) Islands","Canada",
            "Congo","Central African Republic","Congo, The Democratic Republic of the","Czech Republic","Cyprus","Christmas Island",
            "Costa Rica","Cape Verde","Cuba","Swaziland","Syrian Arab Republic","Kyrgyzstan","Kenya","Suriname","Kiribati","Cambodia",
            "Saint Kitts and Nevis","Comoros","Sao Tome and Principe","Slovakia","Korea, Republic of","Slovenia",
            "Korea, Democratic People's Republic of","Kuwait","Senegal","San Marino","Sierra Leone","Seychelles","Kazakhstan",
            "Cayman Islands","Singapore","Sweden","Sudan","Dominican Republic","Dominica","Djibouti","Denmark","Virgin Islands, British",
            "Germany","Yemen","Algeria","United States","Uruguay","Mayotte","United States Minor Outlying Islands","Lebanon","Saint Lucia",
            "Lao People's Democratic Republic","Tuvalu","Taiwan","Trinidad and Tobago","Turkey","Sri Lanka","Liechtenstein","Anonymous Proxy",
            "Tonga","Lithuania","Satellite Provider","Liberia","Lesotho","Thailand","French Southern Territories","Togo","Chad",
            "Turks and Caicos Islands","Libyan Arab Jamahiriya","Holy See (Vatican City State)","Saint Vincent and the Grenadines",
            "United Arab Emirates","Andorra","Antigua and Barbuda","Afghanistan","Anguilla","Virgin Islands, U.S.","Iceland",
            "Iran, Islamic Republic of","Armenia","Albania","Angola","Netherlands Antilles","Antarctica","Asia/Pacific Region","
            American Samoa","Argentina","Australia","Austria","Aruba","India","Aland Islands","Azerbaijan","Ireland","Indonesia",
            "Ukraine","Qatar","Mozambique");
        }
}