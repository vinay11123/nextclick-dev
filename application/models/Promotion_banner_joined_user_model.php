<?php

class Promotion_banner_joined_user_model extends MY_Model
{
    //test
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'promotion_banners_joined_users';
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
       $this->has_one['promotion_banner'] = array('Promotion_banner_model', 'id', 'promotion_banner_id');
    }
    
    public function _form(){
        
    }
}

