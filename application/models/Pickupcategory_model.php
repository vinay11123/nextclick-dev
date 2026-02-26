<?php

class Pickupcategory_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'pickupanddropcategories';
        $this->primary_key = 'id';
        $this->foreign_key = 'cat_id';
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
       /* $this->has_many_pivot['services'] = array(
            'foreign_model' => 'Service_model',
            'pivot_table' => 'categories_services',
            'local_key' => 'id',
            'pivot_local_key' => 'cat_id',
            'pivot_foreign_key' => 'service_id',
            'foreign_key' => 'id',
            'get_relate' => FALSE
        );
        
        $this->has_many_pivot['brands'] = array(
            'foreign_model' => 'Brand_model',
            'pivot_table' => 'categories_brands',
            'local_key' => 'id',
            'pivot_local_key' => 'cat_id',
            'pivot_foreign_key' => 'brand_id',
            'foreign_key' => 'id',
            'get_relate' => FALSE
        );
        
        $this->has_many['amenities'] = array(
            'foreign_model' => 'Amenity_model',
            'foreign_table' => 'amenities',
            'local_key' => 'id',
            'foreign_key' => 'cat_id',
            'get_relate' => FALSE
        );
        
        $this->has_many['sub_categories'] = array(
            'foreign_model' => 'Sub_category_model',
            'foreign_table' => 'sub_categories',
            'local_key' => 'id',
            'foreign_key' => 'cat_id',
            'get_relate' => FALSE
        );*/
    }
    
   
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim|regex_match[/^[a-zA-Z]/]|required',
                'errors' => array(
                    'regex_match' => 'You must provide characters only',
                    'required' => 'You must provide a %s.',
                   )
            ),
            array(
                'field' => 'desc',
                'lable' => 'Description',
                'rules' => 'trim|required|max_length[200]',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            )
        );
    }
}

