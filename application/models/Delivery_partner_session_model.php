<?php

class Delivery_partner_session_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'delivery_partner_sessions';
        $this->primary_key = 'id';
        
        $this->before_create[] = '_add_created_by';
        
       $this->_config();
       $this->_relations();
    }
    
    protected function _add_created_by($data)
    {
        $data['delivery_partner_user_id'] = $this->ion_auth->get_user_id()? $this->ion_auth->get_user_id(): $this->user_id; //add user_id
        return $data;
    }
    
    
    public function _config() {
        $this->timestamps = FALSE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
        $this->has_one['delivery_partner'] = array('User_model', 'id', 'delivery_partner_user_id');
    }
    
}

