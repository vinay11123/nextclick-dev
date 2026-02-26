<?php

class Vendor_leads_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendor_leads';
        $this->primary_key = 'id';
        //$this->before_create[] = '_add_created_by';
        //$this->before_update[] = '_add_updated_by';
        
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
        $this->has_one['lead'] = array('lead_model', 'id', 'lead_id');
    }
    
    public function _form(){
        $this->rules = array(
            
        );
    }
}