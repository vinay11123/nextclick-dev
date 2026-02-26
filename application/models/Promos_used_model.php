<?php

class Promos_used_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'used_promo_codes';
        $this->primary_key = 'id';
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
        $this->has_one['subcat'] = array('Sub_category_model','id','sub_cat_id');
      /*  $this->has_many['subcatt'] = array(
            'foreign_model' => 'Sub_category_model',
            'foreign_table' => 'sub_categories',
            'local_key' => 'id',
            'foreign_key' => 'sub_cat_id',
            'get_relate' => FALSE
        );*/
    }
    
   
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'promo_code',
                'lable' => 'Promo Code',
                'rules' => 'trim|required',
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
                'field' => 'promo_type',
                'lable' => 'Promo Type',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                )
            ),
            array(
                'field' => 'promo_label',
                'lable' => 'Promo Label',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.',
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
    }
}

