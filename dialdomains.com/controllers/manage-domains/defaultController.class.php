<?php

class defaultController extends adfBaseController{
    public function indexAction(){
        
    }

    public function addAction(){
        
        include $this->tpl->page('manage-domains/add.php');
    }
    
    public function editAction(){
         if(!is_null(lib_request('id'))){
             $domainM = new domain();
             if ($data = $domainM->getById(lib_request('id'))) {
                 $_POST['domain'] = $data['domain'];
                
                $this->status->error("The requested user was not found.");
            }
         } 
         if (lib_is_post()) {

         }

         include $this->tpl->page('manage-domains/edit.php');
    }

    public function  domainListAction(){
        $domainM = new domain();
        $domains = $domainM->getDomainsByUserId(adfAuthentication::getCurrentUserId());

        include $this->tpl->page('manage-domains/domain_list.php');
    }

    public function deleteAction(){
        $id = lib_request('id');
        if(!is_null($id)){
             $domainM = new domain();
             $dimain = $domainM->getById($id);
            

            $domainM->updateFieldById($id,'deleted',1);
            $this->status->message('Success Message');

        }
        lib_redirect('/manage-domains/default/domainList');
    }
}