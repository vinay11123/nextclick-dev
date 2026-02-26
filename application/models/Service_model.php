<?php

class Service_model extends MY_Model
{
    public $rules;
    //public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'services';
        $this->primary_key = 'id';
       // $this->foreign_key = 'service_id';
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
    public function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
        $this->has_many_pivot['permissions'] = array(
            'foreign_model' => 'Permission_model',
            'pivot_table' => 'services_permissions',
            'local_key' => 'id',
            'pivot_local_key' => 'service_id',
            'pivot_foreign_key' => 'perm_id',
            'foreign_key' => 'id',
            'get_relate' => FALSE
        );
        
    }
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim|required',
                'errors'=>array(
                        'min_length'=>'Please give at least 5 characters'
                 )
            ),
        );
    }
}

