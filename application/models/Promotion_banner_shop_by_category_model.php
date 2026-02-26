<?php

class Promotion_banner_shop_by_category_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'promotion_banners_shop_by_categories';
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
        $this->has_one['sub_category'] = array(
            'Sub_category_model',
            'id',
            'sub_cat_id'
        );
    }
    
    public function _form(){
        
    }
}

