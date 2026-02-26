<?php

class Vendor_service_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendor_services';
        $this->primary_key = 'id';
        
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
        $this->timestamps = FALSE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
        $this->has_one['service'] = array(
            'Service_model',
            'id',
            'service_id'
        );
    }
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'list_id',
                'lable' => 'List Id',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'service_id',
                'lable' => 'Service Id',
                'rules' => 'trim|required'
            ),
        );
    }
}

