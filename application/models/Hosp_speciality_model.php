<?php

class Hosp_speciality_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'hosp_specialties';
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
       $this->has_many['doctors'] = array(
           'foreign_model' => 'hosp_doctor_model',
           'foreign_table' => 'doctors',
           'local_key' => 'id',
           'foreign_key' => 'speciality_id',
           'get_relate' => FALSE
       );
    }
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'name',
                'lable' => 'Speciality Name',
                'rules' => 'required'
            )
        );
    }
}

