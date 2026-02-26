<?php

class Ecom_order_reject_request_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'ecom_order_reject_requests';
        $this->primary_key = 'id';
        
       $this->_config();
       $this->_form();
       $this->_relations();
    }
    
    
    
    public function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
        
    }
    
    public function _form(){
        
    }
}

