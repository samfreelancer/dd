<?php

class userValidator extends adfBaseValidator{
    protected function first_name($val){
        if (empty($val) || strlen($val) > 35) {
            $this->fail("First Name cannot be empty and must be 35 characters or less.");
        }
    }
    
    protected function last_name($val){
        if (empty($val) || strlen($val) > 35) {
            $this->fail("Last Name cannot be empty and must be 35 characters or less.");
        }
    }
    
    protected function _finalChecks($data){
        if (isset($data['password']) || $this->event == 'add'){
            if (empty($data['password_length']) || $data['password_length'] < 6) {
                $this->fail("The Password must be at least 6 characters.");
            }

            if ($data['password'] != $data['password_confirm']) {
                $this->fail("The Password and Password Confirmation do not match.");
            }
        }
    }
    
    protected function email($val){
        if (empty($val) || !filter_var($val, FILTER_VALIDATE_EMAIL)) {
            $this->fail("A valid Email Address is required.");
        }
        
        if (false != $row = $this->model->getByEmail($val)) {
            if ($this->event == 'add' || $row['id'] != $this->recid) {
                $this->fail('The email address entered is in use by another user.');
            }
        }
    }
    
    protected function password($val, $data){
        
    }

    protected function username($val){
        if (empty($val) || strlen($val) > 35) {
            $this->fail("username cannot be empty and must be 35 characters or less.");
        }

        if (false != $row = $this->model->getByEmail($val)) {
            if ($this->event == 'add' || $row['id'] != $this->recid) {
                $this->fail('The email address entered is in use by another user.');
            }
        }
    }
}