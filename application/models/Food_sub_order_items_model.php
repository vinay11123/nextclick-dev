<?php

class Food_sub_order_items_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'food_sub_order_items';
        $this->primary_key = 'id';
        $this->foreign_key = 'order_id';
        
       $this->_config();
       $this->_form();
       $this->_relations();
    }
    public function _config() {
        $this->timestamps = FALSE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
        /*$this->has_many['items'] = array(
            'foreign_model' => 'Sub_category_model',
            'foreign_table' => 'sub_categories',
            'local_key' => 'id',
            'foreign_key' => 'cat_id',
            'get_relate' => FALSE
        );*/
    }
    
   
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim|required|min_length[3]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'min_length' => 'you need to give minimum3 characters'
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

