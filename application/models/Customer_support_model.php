<?php

class Customer_support_model extends MY_Model
{
    public $rules , $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'customer_support';
        $this->primary_key = 'id';
        $this->before_update[] = '_add_updated_by';
        
       $this->_config();
       $this->_form();
       $this->_relations();
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
        $this->has_many['support'] = array(
            'foreign_model' => 'Customer_support_model',
            'foreign_table' => 'customer_support',
            'local_key' => 'id',
            'foreign_key' => 'app_details_id'
        );
		
		$this->has_one['assigned'] = array('User_model', 'id', 'assigned_to');
    }
    
    private function _form(){

        $this->rules['create_rules']  = array(
            array(
                'field' => 'title',
                'lable' => 'title',
                'rules' => 'trim|required'
            ),
			array(
                'field' => 'severity',
                'lable' => 'severity',
                'rules' => 'trim|required'
            ),
			array(
                'field' => 'request_type',
                'lable' => 'request_type',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'app_details_id',
                'lable' => 'app_details_id',
                'rules' => 'trim|required'
            ),
        );
        $this->rules['update_rules'] = array(
           
             array(
                'field' => 'email',
                'lable' => 'contact mail',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'mobile',
                'lable' => 'mobile',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'subject',
                'lable' => 'subject',
                'rules' => 'trim|required'
            ),
        );
    }
}

