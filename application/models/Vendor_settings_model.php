<?php

class Vendor_settings_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendor_settings';
        $this->primary_key = 'id';
        
        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';
        
       $this->_config();
       $this->_form();
       $this->_relations();
    }
    
    protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id()? $this->ion_auth->get_user_id(): $this->user_id; //add user_id
        return $data;
    }
    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id()? $this->ion_auth->get_user_id(): $this->user_id; //add user_id
        return $data;
    }
    public function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
       
    }
    
    public function _form(){
        $this->rules['food'] = array (
            array (
                'lable' => 'Min Order Price',
                'field' => 'min_order_price',
                'rules' => 'required'
            )
        );
        /*
         * ,
            array (
                'lable' => 'Delivery Free Range (Km)',
                'field' => 'delivery_free_range',
                'rules' => 'trim|required'
            ),
            array (
                'lable' => 'Min Delivery Fee',
                'field' => 'min_delivery_fee',
                'rules' => 'trim|required'
            ),
            array (
                'lable' => 'Extra Delivery Fee (per km)',
                'field' => 'ext_delivery_fee',
                'rules' => 'trim|required'
            )*/
        $this->rules['food_item_label'] = array (
            array (
                'lable' => 'Item',
                'field' => 'item_id',
                'rules' => 'trim|required'
            ),
            array (
                'lable' => 'Lable',
                'field' => 'label',
                'rules' => 'trim|required'
            )
        );
        
        /*$this->rules['sms'] = array (
            array (
                'lable' => 'sms_username',
                'field' => 'sms_username',
                'rules' => 'trim|required'
            ),
            array (
                'lable' => 'Sender',
                'field' => 'sms_sender',
                'rules' => 'trim|required'
            ),
            array (
                'lable' => 'Hash Key',
                'field' => 'sms_hash',
                'rules' => 'trim|required'
            )
        );
           
        $this->rules['smtp'] = array(
            array(
                'label' => 'SMTP Port',
                'field' => 'smtp_port',
                'rules' => 'trim|required'
            ),
            array(
                'label' => 'SMTP Host',
                'field' => 'smtp_host',
                'rules' => 'trim|required'
            ),
            array(
                'label' => 'SMTP Username',
                'field' => 'smtp_username',
                'rules' => 'trim|required'
            ),
            array(
                'label' => 'SMTP Password',
                'field' => 'smtp_password',
                'rules' => 'trim|required'
            )
            
        );*/
    }
}

