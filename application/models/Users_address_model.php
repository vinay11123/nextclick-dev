<?php

class Users_address_model extends MY_Model
{
    public $rules, $user_id;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'users_address';
        $this->primary_key = 'id';
        $this->foreign_key = 'location_id';
        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';
        
       $this->_config();
       $this->_form();
       $this->_relations();
    }
  /*  protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    }
    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    }*/

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
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
        $this->has_one['user'] = array('User_model', 'id', 'user_id');
        $this->has_one['location'] = array('Location_model', 'id', 'location_id');
        $this->has_one['state'] = array('State_model', 'id', 'state_id');
        $this->has_one['district'] = array('District_model', 'id', 'district_id');
        $this->has_one['constituency'] = array('Constituency_model', 'id', 'constituency_id');
    }
    
   
    
    public function _form(){
        $this->rules['create_rules'] = array(
            array (
                'lable' => 'Name',
                'field' => 'name',
                'rules' => 'required'
            ),
            array (
                'lable' => 'mobile',
                'field' => 'mobile',
                'rules' => 'min_length[10]|max_length[10]|regex_match[/^[0-9]{10}$/]',
                'errors'=>array(
                    'min_length'=>'Please give minimum 10 digits number',
                    'max_length'=>'You can give maximum 10 digits number',
                    'regex_match'=>'Please give a valid number',
                )
            ),
            array (
                'lable' => 'address',
                'field' => 'address',
                'rules' => 'required'
            ),
            array (
                'lable' => 'email',
                'field' => 'email',
                'rules' => 'required'
            ),
            array (
                'lable' => 'email',
                'field' => 'email',
                'rules' => 'valid_email',
                'errors'=>array(
                    'valid_email'=>'Please give valid email!'
                )
            ),
           
        );

        $this->rules['update_rules'] = array(
            array(
                'field' => 'id',
                'lable' => 'User Id',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must Select a %s.'
                )
            ),
            array (
                'lable' => 'Name',
                'field' => 'name',
                'rules' => 'required'
            ),
            array (
                'lable' => 'mobile',
                'field' => 'mobile',
                'rules' => 'min_length[10]|max_length[10]|regex_match[/^[0-9]{10}$/]',
                'errors'=>array(
                    'min_length'=>'Please give minimum 10 digits number',
                    'max_length'=>'You can give maximum 10 digits number',
                    'regex_match'=>'Please give a valid number',
                )
            ),
            array (
                'lable' => 'address',
                'field' => 'address',
                'rules' => 'required'
            ),
            array (
                'lable' => 'email',
                'field' => 'email',
                'rules' => 'required'
            ),
            array (
                'lable' => 'email',
                'field' => 'email',
                'rules' => 'valid_email',
                'errors'=>array(
                    'valid_email'=>'Please give valid email!'
                )
            ),
           
        );
    }
}

