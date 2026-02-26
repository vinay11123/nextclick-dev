<?php

class Vendor_od_category_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendors_od_categories';
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
        
    }
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'od_cat_id',
                'lable' => 'Category Id',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'desc',
                'lable' => 'Description',
                'rules' => 'trim|required'
            )
        );
    }
}

