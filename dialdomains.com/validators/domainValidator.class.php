<?php
class domainValidator extends adfBaseValidator{
    public function phone_number($val){
        if (strlen($val) != 10){
            $this->fail('Invalid phone number');
        }
    }
}
