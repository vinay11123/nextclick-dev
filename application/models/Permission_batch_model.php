<?php

class Permission_batch_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'permissions_batches';
        $this->primary_key = 'id';
        
        $this->_config();
        $this->_form();
        $this->_relations();
    }
    
    public function _config(){
        
    }
    
    public function _form() {
        ;
    }
    
    public function _relations() {
        ;
    }
}

