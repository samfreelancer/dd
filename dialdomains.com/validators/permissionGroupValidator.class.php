<?php

class permissionGroupValidator extends adfBaseValidator{
    protected function name($val){
        if (empty($val) || strlen($val) > 45) {
            throw new Exception("Group Name cannot be empty and must be 45 characters or less");
        }
    }
}