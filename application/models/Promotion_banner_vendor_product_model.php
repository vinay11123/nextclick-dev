<?php

class Promotion_banner_vendor_product_model extends MY_Model
{
    //test
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'promotion_banners_vendor_products';
        $this->primary_key = 'id';
        
        
       $this->_config();
       $this->_form();
       $this->_relations();
    }
    
    public function _config() {
        $this->timestamps = FALSE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
       
    }
    
    public function _form(){
        
    }
}

