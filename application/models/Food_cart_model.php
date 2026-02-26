<?php

class Food_cart_model extends MY_Model
{
    public $rules, $user_id;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'food_cart';
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
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
        $this->has_one['vendor_product_variant'] = array(
            'Vendor_product_variant_model',
            'id',
            'vendor_product_variant_id'
        );
        
        $this->has_one['item'] = array(
            'Food_item_model',
            'id',
            'item_id'
        );
        
        $this->has_one['vendor'] = array(
            'Vendor_list_model',
            'vendor_user_id',
            'vendor_user_id'
        );
        
        $this->has_many['item_images'] = array(
            'foreign_model' => 'Food_item_image_model',
            'foreign_table' => 'food_item_images',
            'local_key' => 'item_id',
            'foreign_key' => 'item_id',
            'get_relate' => FALSE
        );
    }
    
    public function _form(){
        $this->rules['create_rules'] = array(
            array(
                'field' => 'item_id',
                'lable' => 'Item',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must Select a %s.'
                )
            ),
            array(
                'vendor_product_variant_id' => 'vendor_product_variant_id',
                'lable' => 'vendor product variant id',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'qty',
                'lable' => 'Quantity',
                'rules' => 'trim|required|min_length[1]',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            )
        );
        
        $this->rules['update_rules'] = array(
            array(
                'field' => 'id',
                'lable' => 'Cart Product Id',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must Select a %s.'
                )
            ),
            array(
                'field' => 'item_id',
                'lable' => 'Item',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must Select a %s.'
                )
            ),
            array(
                'vendor_product_variant_id' => 'vendor_product_variant_id',
                'lable' => 'vendor product variant id',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'qty',
                'lable' => 'Quantity',
                'rules' => 'trim|required|min_length[1]',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            )
        );
    }
}

