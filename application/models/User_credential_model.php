<?php

class User_credential_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'user_credentials';
        $this->primary_key = 'id';
        
       $this->_config();
       $this->_relations();
    }
    private function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }
    
    private function _relations(){
    }
}