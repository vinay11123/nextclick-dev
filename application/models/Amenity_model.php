<?php

class Amenity_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'amenities';
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
                'field' => 'cat_id',
                'lable' => 'Category Id',
                'rules' => 'trim|required',
                'errors'=>array(
                    'required'=>'Please select category'
                )
                
            ),
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim|required|min_length[3]',
                'errors'=>array(
                    'min_length'=>'Please give minimum 3 characters'
                )
            ),
            array(
                'field' => 'desc',
                'lable' => 'Description',
                'rules' => 'trim|required|max_length[200]',
                'errors'=>array(
                    'max_length'=>'You can give maximum 200 characters'
                )
            )
        );
        $this->rules['update_rules'] = array(
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim|required|min_length[3]',
                'errors'=>array(
                    'min_length'=>'Please give minimum 3 characters'
                )
            ),

        );
    }
}

