<?php

class App_details_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'app_details';
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
        
    }
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'list_id',
                'lable' => 'List Id',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'std_code',
                'lable' => 'STD Code',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'number',
                'lable' => 'Number',
                'rules' => 'trim|required'
            ),
        );
    }
}

