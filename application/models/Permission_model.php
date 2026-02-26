<?php

class Permission_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'permissions';
        $this->primary_key = 'id';
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
    public function _config(){
        
    }
    
    public function _form() {
        $this->has_one['batch'] = array('Permission_batch_model', 'id', 'permissions_batch_id');
    }
    
    public function _relations() {
        ;
    }
}

