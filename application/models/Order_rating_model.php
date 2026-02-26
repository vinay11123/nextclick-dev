<?php

class Order_rating_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'order_rating';
        $this->primary_key = 'id';
        $this->foreign_key = 'order_id';
        
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
    }
    
   
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'discount',
                'lable' => 'discount',
                'rules' => 'required',
            ),
            array(
                'field' => 'tax',
                'lable' => 'tax',
                'rules' => 'required',
            ),
            array(
                'field' => 'total',
                'lable' => 'total',
                'rules' => 'required',
            ),
            array(
                'field' => 'payment_method_id',
                'lable' => 'payment method id',
                'rules' => 'required',
            ),
        );
    }
}

