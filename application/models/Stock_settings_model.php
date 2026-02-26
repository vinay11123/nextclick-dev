<?php

class Stock_settings_model extends MY_Model
{
    public $rules , $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'ecom_settings';
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
    private function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }
    
    private function _relations(){
        
        $this->has_many['Stock_settings'] = array(
            'foreign_model' => 'Stock_settings_model',
            'foreign_table' => 'Stock_settings',
            'local_key' => 'id'
             
        );
    }
    
    private function _form(){

        $this->rules['create_rules']  = array(

           
            array(
                'field' => 'min_stock',
                'lable' => 'min_stock',
                'rules' => 'trim|required'
            ),
        );
        $this->rules['update_rules'] = array(
           
            array(
                'field' => 'min_stock',
                'lable' => 'min_stock',
                'rules' => 'trim|required'
            ),
        );
    }
}

