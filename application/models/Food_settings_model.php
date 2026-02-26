<?php

class Food_settings_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'food_settings';
        $this->primary_key = 'id';
        $this->foreign_key = 'vendor_id';
        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';
        
       $this->_config();
       $this->_form();
       $this->_relations();
    }
    protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    }
    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    } 
    public function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
        $this->has_one['vendor'] = array('Vendor_list_model', 'vendor_user_id', 'vendor_id');
       /* $this->has_many_pivot['services'] = array(
            'foreign_model' => 'Service_model',
            'pivot_table' => 'vendor_services',
            'local_key' => 'vendor_id',
            'pivot_local_key' => 'list_id',
            'pivot_foreign_key' => 'service_id',
            'foreign_key' => 'id'
        );*/
        $this->has_one['services'] = array('Vendor_service_model', 'list_id', 'vendor_id');
        /*$this->has_one['permissions'] = array('Users_permissions_model', 'user_id', 'id');*/
    }
    
   
    
    public function _form(){
        $this->rules = array(
            /*array(
                'field' => 'min_order_price',
                'lable' => 'Min Order Price',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'delivery_free_range',
                'lable' => 'Delivery Free Range (Km)',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'min_delivery_fee',
                'lable' => 'Min Delivery Fee',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'ext_delivery_fee',
                'lable' => 'Extra Delivery Fee (per km)',
                'rules' => 'trim|required'
            ),*/
            array(
                'field' => 'preparation_time',
                'lable' => 'Preparation Time (in Minutes)',
                'rules' => 'trim|required'
            )
        );
    }
}

