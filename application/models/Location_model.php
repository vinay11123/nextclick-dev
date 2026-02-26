<?php

class Location_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'locations';
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
                'field' => 'latitude',
                'lable' => 'Latitude',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'longitude',
                'lable' => 'Longitude',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'address',
                'lable' => 'Address',
                'rules' => 'trim|required'
            )
        );
    }
}

