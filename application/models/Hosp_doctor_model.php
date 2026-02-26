<?php

class Hosp_doctor_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'hosp_doctors';
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
        $this->has_one['speciality'] = array('hosp_speciality_model', 'id', 'speciality_id');
        $this->has_many['service_timings'] = array(
            'foreign_model' => 'Service_timings_model',
            'foreign_table' => 'services_timings',
            'local_key' => 'id',
            'foreign_key' => 'ref_id'
        );
         $this->has_many['hosp_speciality'] = array(
            'foreign_model' => 'hosp_speciality_model',
            'foreign_table' => 'hosp_specialities',
            'local_key' => 'hosp_specialty_id',
            'foreign_key' => 'id'
        );
        
        $this->has_many['doctor_details'] = array(
            'foreign_model' => 'Hosp_doctor_details_model',
            'foreign_table' => 'hosp_doctors_details',
            'local_key' => 'id',
            'foreign_key' => 'hosp_doctor_id'
        );
    }
    
    public function _form(){
        
    }
}

