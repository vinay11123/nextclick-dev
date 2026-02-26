<?php

class Vendor_support_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendor_support';
        $this->primary_key = 'id';
       
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
        $this->has_many['users'] = array(
            'foreign_model' => 'User_model',
            'foreign_table' => 'users',
            'local_key' => 'vendor_id',
            'foreign_key' => 'id'
            );
    }
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'contact_mail',
                'lable' => 'contact_mail ',
                'rules' => 'trim|required|min_length[5]|max_length[200]',
                'errors'=>array(
                    'min_length'=> 'You should give minimum 5 characters',
                    'max_length'=>'You can give maximum 200 characters'
                )
            ),
            array(
                'field' => 'fullname',
                'lable' => 'fullname',
                'rules' => 'trim|required',
                'errors'=> 'Enter Name'
                )
             
            
        );
    }
}

