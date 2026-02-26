<?php

class Food_section_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'food_section';
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
            $this->has_one['menu'] = array('Food_menu_model','id','menu_id');
            $this->has_one['item'] = array('Food_item_model','id','item_id');

         $this->has_many['section_items'] = array(
            'foreign_model' => 'Food_sec_item_model',
            'foreign_table' => 'food_sec_item',
            'local_key' => 'id',
            'foreign_key' => 'sec_id',
            'get_relate' => FALSE
        );
    }
    
    public function _form(){
        $this->rules = array(
            array(
                'field'=>'menu_id',
                'label'=>'Item',
                'rules'=>'trim|required',
                'errors'=>array(
                    'required'=>'Please select Item'
                )
            ),
            array(
                'field'=>'item_id',
                'label'=>'Sub Item',
                'rules'=>'trim|required',
                'errors'=>array(
                    'required'=>'Please select Item'
                )
            ),
            array(
                'field'=>'item_field',
                'label'=>'Item Field',
                'rules'=>'trim|required',
                'errors'=>array(
                    'required'=>'Please select any one'
                )
            ),
            array(
                'field'=>'sec_price',
                'label'=>'Section Price',
                'rules'=>'trim|required',
                'errors'=>array(
                    'required'=>'Please select any one'
                )
            ),
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim|required|min_length[3]',
                'errors'=>array(
                        'min_length'=>'Please give at least 3 characters'
                 )
            )
        );
    }
}

