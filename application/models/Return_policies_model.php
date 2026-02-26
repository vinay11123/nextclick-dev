<?php

class Return_policies_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'returns_policies';
        $this->primary_key = 'id';
        
       $this->_config();
       $this->_form();
       $this->_relations();
    }
    
    public function _config() {
        $this->timestamps = FALSE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
        $this->has_one['sub_category'] = array('Sub_category_model', 'id', 'sub_cat_id');
        $this->has_one['menu'] = array('Food_menu_model', 'id', 'menu_id');
    }
     
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'sub_cat_id',
                'lable' => 'Sub Category',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                   )
            ),
            array(
                'field' => 'menu_id',
                'lable' => 'Menu',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            )
        );
    }
    
}

