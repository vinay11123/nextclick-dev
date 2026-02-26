<?php

class Od_service_details_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'od_services_details';
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
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
         $this->has_one['od_category'] = array('Od_category_model', 'id', 'od_cat_id');
         $this->has_many['service_timings'] = array(
             'foreign_model' => 'Service_timings_model',
             'foreign_table' => 'services_timings',
             'local_key' => 'id',
             'foreign_key' => 'ref_id'
         );
    }
    
    public function _form(){
         $this->rules = array(
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'required'
            )
        );
         $this->rules['create'] = array(
             array(
                 'field' => 'name',
                 'lable' => 'Service Name',
                 'rules' => 'required'
             )
         );
    }
}

