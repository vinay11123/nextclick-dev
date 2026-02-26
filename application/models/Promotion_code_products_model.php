<?php

class Promotion_code_products_model extends MY_Model
{
    public $rules, $user_id;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'promotion_code_products';
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
        $this->has_one['promotion'] = array('promos_model','id','promotion_code_id');
        
        $this->has_many_pivot ['groups'] = array (
            'foreign_model' => 'Food_item_model',
            'pivot_table' => 'food_item',
            'local_key' => 'id',
            'pivot_local_key' => 'product_id',
            'pivot_foreign_key' => 'id',
            'foreign_key' => 'id',
            'get_relate' => FALSE
        );
    
    }
    public function _form(){
    }

 }


