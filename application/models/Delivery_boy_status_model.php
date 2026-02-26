<?php

class Delivery_boy_status_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'delivery_boy_settings';
        $this->primary_key = 'id';
        $this->foreign_key = 'user_id';
        
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
        $this->has_one['deal_boy'] = array('User_model', 'id', 'user_id');
    }
    
   
    
    public function _form(){
        $this->rules = array(
            array(
                'field'=>'user_id',
                'label'=>'Delivery User',
                'rules'=>'trim|required'
            ),
            array(
                'field'=>'delivery_boy_status',
                'label'=>'Delivery Boy Status ',
                'rules'=>'required'
            )
        );
    }
}

