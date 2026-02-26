<?php

class Faq_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'faq';
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
                'field' => 'question',
                'lable' => 'question ',
                'rules' => 'trim|required|min_length[5]|max_length[200]',
                'errors'=>array(
                    'min_length'=> 'You should give minimum 5 characters',
                    'max_length'=>'You can give maximum 200 characters'
                )
            ),
            array(
                'field' => 'answer',
                'lable' => 'answer',
                'rules' => 'trim|required|min_length[5]|max_length[500]',
                'errors'=>array(
                    'min_length'=> 'You should give minimum 5 characters',
                    'max_length'=>'You can give maximum 500 characters'
                )
            ),
            
        );
    }
}

