<?php

class Admin_banners_model extends MY_Model
{
    public $rules,$user_id;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'admin_banners';
        $this->primary_key = 'id';

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
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
        $this->has_one['position'] = array('promotion_banner_position_model', 'id', 'promotion_banner_position_id');
        
    }
    
    private function _form(){
         $this->rules['addmin_banner'] = array(
            array(
                'field' => 'promotion_banner_position_id',
                'lable' => 'Position',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Please select Position.',
                   )
            )
        );
        $this->rules['update_rules'] = array(
            array(
                'field' => 'cat_id',
                'lable' => 'Category',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                   )
            )
        );
    }
    
}