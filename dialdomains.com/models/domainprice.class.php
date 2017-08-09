<?php

class domainprice extends adfModelBase {

    public function serializeReadings($readings) {
        return serialize($readings);
    }

    public function unserializeReading($readings) {
        return unserialize($readings);
    }

    # get domain price 
    public function getDomainsPrice(){
        $domain_index = $this->getAll();
        $domain_index_price  = array();
        foreach ($domain_index as $key => $value) {
            $domain_index_price[$value['domain']] = $value;
        }
        return $domain_index_price;
    }

}
