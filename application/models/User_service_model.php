<?php

class User_service_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'user_services';
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
        $data['created_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    }
    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
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
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim|required'
               
            ),
        );
    }
}

