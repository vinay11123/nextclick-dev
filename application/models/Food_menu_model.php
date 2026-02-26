<?php

class Food_menu_model extends MY_Model
{
    public $rules, $user_id;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'food_menu';
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
        $this->has_one['shop_by_category'] = array('Sub_category_model','id','sub_cat_id');
       $this->has_many['items'] = array(
            'foreign_model' => 'Food_item_model',
            'foreign_table' => 'food_item',
            'local_key' => 'id',
            'foreign_key' => 'menu_id',
            'get_relate' => FALSE
        );
    }
    
   
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'sub_cat_id',
                'lable' => 'Sub Category',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            ),
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim|required|min_length[3]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'min_length' => 'you need to give minimum 3 characters'
                )
            ),
            array(
                'field' => 'desc',
                'lable' => 'Description',
                'rules' => 'trim|required|max_length[200]',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            )
        );
    }
}

