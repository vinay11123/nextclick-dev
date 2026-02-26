<?php

class Promotion_banner_images_model extends MY_Model
{
    public $rules,$user_id;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'promotion_banner_images';
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
        $this->has_one['category'] = array('Category_model', 'id', 'cat_id');
        
    }
    
    private function _form(){
         $this->rules['create_rules'] = array(
            array(
                'field' => 'cat_id',
                'lable' => 'Category',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'You must provide a %s.',
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