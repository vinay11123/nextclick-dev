<?php

class Food_order_deal_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'food_order_deal';
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
       /* $this->has_one['user'] = array('User_model', 'id', 'user_id');*/
        
        $this->has_one['deal_boy'] = array('User_model', 'id', 'deal_id');
        $this->has_one['order'] = array('Food_orders_model', 'id', 'order_id');

        /*$this->has_many_pivot['user'] = array(
            'foreign_model' => 'User_model',
            'pivot_table' => 'food_orders',
            'local_key' => 'id',
            'pivot_local_key' => 'order_id',
            'pivot_foreign_key' => 'user_id',
            'foreign_key' => 'id',
            'get_relate' => FALSE
        );*/

        /*$this->has_many['user'] = array(
            'foreign_model' => 'User_model',
            'foreign_table' => 'users',
            'local_key' => 'id',
            'foreign_key' => $this->foreign_key,
            'get_relate' => FALSE
        );
        */
        /*$this->has_one['address'] = array('Users_address_model', 'address_id', 'id');
        $this->has_one['user'] = array('User_model', 'id', 'user_id');
        $this->has_one['vendor'] = array('Vendor_list_model', 'id', 'vendor_id');

        $this->has_many['order_items'] = array(
            'foreign_model' => 'Food_order_items_model',
            'foreign_table' => 'food_order_items',
            'local_key' => 'id',
            'foreign_key' => $this->foreign_key,
            'get_relate' => FALSE
        );
        $this->has_many['sub_order_items'] = array(
            'foreign_model' => 'Food_sub_order_items_model',
            'foreign_table' => 'food_sub_order_items',
            'local_key' => 'id',
            'foreign_key' => $this->foreign_key,
            'get_relate' => FALSE
        );*/
    }
    
   
    
    public function _form(){


    }
}

