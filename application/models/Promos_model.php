<?php

class Promos_model extends MY_Model
{
    public $rules, $user_id;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'promotion_codes';
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
       
        $this->has_many['promo_products'] = array(
            'foreign_model' => 'Promotion_code_products_model',
            'foreign_table' => 'promotion_code_products',
            'local_key' => 'id',
            'foreign_key' => 'promotion_code_id',
            'get_relate' => FALSE
        );
        $this->has_many['no_of_uses'] = array(
            'foreign_model' => 'used_promo_codes_model',
            'foreign_table' => 'used_promo_codes',
            'local_key' => 'id',
            'foreign_key' => 'promo_id',
            'get_relate' => FALSE
        );

        $this->has_one['category'] = array('Category_model', 'id', 'category');
        $this->has_one['vendor'] = array('Vendor_list_model', 'vendor_user_id', 'created_user_id');
        $this->has_one['shop_by_category'] = array('Sub_category_model', 'id', 'shop_by_category');
        $this->has_one['promo_image'] = array('Promotion_scratch_cards_model', 'id', 'promo_image');
        $this->has_one['promo_products'] = array('Promotion_code_products_model', 'promotion_code_id', 'promo_code');
    }
    
    
    
    public function _form(){
        $this->rules['create_rules'] = array(
            array(
                'field' => 'promo_code',
                'lable' => 'Promo Code',
                'rules' => 'trim|required|callback_check_promo_code',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'is_unique' => 'This %s already exists.'
                )
            ),
            array(
                'field' => 'promo_title',
                'lable' => 'Promo Title',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            ),

            
            array(
                'field' => 'start_date',
                'lable' => 'Start Date',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                )
            ),
            array(
                'field' => 'end_date',
                'lable' => 'End Date',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            ),
            array(
                'field' => 'discount_type',
                'lable' => 'Discount Type',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            ),
            array(
                'field' => 'discount',
                'lable' => 'Discount',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            ),
           
        );
        $this->rules['update_rules'] = array(
           
            array(
               'field' => 'id',
               'lable' => 'id',
               'rules' => 'trim|required'
           ),
           
        );
           
    
    }
    
}

