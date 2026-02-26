<?php

class Request_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'request_type';
        $this->primary_key = 'id';
         
       $this->_config();
       $this->_form();
       $this->_relations();
    }
   
    private function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
    
    private function _relations(){
       
    }
    
    private function _form(){
        $this->rules = array(
            array(
                'field' => 'title',
                'lable' => 'title',
               'rules' => 'trim|required|min_length[5]',
               'errors'=>array(
                 'min_length'=>'Please give at least 5 characters'
                )
            ),
        );
    }
}

