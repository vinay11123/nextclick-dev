<?php

class Shop_by_category_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'shop_by_categories';
        $this->primary_key = 'id';
        
     
       $this->_config();
       $this->_form();
       $this->_relations();
    }
     
    public function _config() {
        $this->timestamps = FALSE;
        
    }
    
    public function _relations(){
        $this->has_one['sub_category'] = array(
            'Sub_category_model',
            'id',
            'sub_cat_id'
        );
    }
    
    public function _form(){
        $this->rules = array(
            
        );
    }
}

