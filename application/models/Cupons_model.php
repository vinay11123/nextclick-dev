<?php

class Cupons_model extends MY_Model
{
    public $rules, $user_id;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'cupons';
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
    }
    
    
    
    public function _form(){
        $this->rules['create_rules'] = array(
            array(
                'field' => 'code',
                'lable' => 'Code',
                'rules' => 'trim|required|callback_check_promo_code',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'is_unique' => 'This %s already exists.'
                )
            ),
            array(
                'field' => 'title',
                'lable' => 'Title',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            ),

            
            array(
                'field' => 'start_date',
                'lable' => 'Start Date',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                )
            ),
            array(
                'field' => 'end_date',
                'lable' => 'End Date',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            ),
            array(
                'field' => 'discount',
                'lable' => 'Discount',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            ),
			array(
                'field' => 'minimum_amount',
                'lable' => 'Minimum Amount',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            ),
			array(
                'field' => 'status',
                'lable' => 'Status',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            ),
           
        );
        $this->rules['update_rules'] = array(
           
            array(
               'field' => 'id',
               'lable' => 'id',
               'rules' => 'trim|required'
           ),
           
        );
           
    
    }
    
}

