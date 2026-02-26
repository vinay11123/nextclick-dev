<?php

class Vendor_rating_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendor_ratings';
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
        $this->has_one['user'] = array('User_model', 'id', 'user_id');
        $this->has_many['vendors'] = array(
            'foreign_model' => 'Vendor_list_model',
            'foreign_table' => 'vendors_list',
            'local_key' => 'vendor_id',
            'foreign_key' => 'vendor_user_id'
        );
    }
    
    public function _form(){
        
    }
}

