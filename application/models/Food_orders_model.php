<?php

class Food_orders_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'food_orders';
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
        $this->has_one['address'] = array('Users_address_model', 'address_id', 'id');
        $this->has_one['user'] = array('User_model', 'id', 'user_id');
        $this->has_one['vendor'] = array('Vendor_list_model', 'vendor_user_id', 'vendor_id');
        $this->has_one['delvery_boy'] = array('User_model', 'id', 'deal_id');
        $this->has_one['promo'] = array('Promos_model', 'id', 'promo_id');

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
        );
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

         function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}

