<?php

class Promo_vendors_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'promo_vendors';
        $this->primary_key = 'id';
       /* $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';*/
        
       $this->_config();
       $this->_form();
       $this->_relations();
    }
    /*protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    }
    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    } */
    public function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = FALSE;
    }
    
    public function _relations(){
        $this->has_one['promos'] = array('Promos_model','id','promo_id');
        $this->has_one['vendors'] = array('Vendor_list_model','vendor_user_id','vendor_id');
      /*  $this->has_many['subcatt'] = array(
            'foreign_model' => 'Sub_category_model',
            'foreign_table' => 'sub_categories',
            'local_key' => 'id',
            'foreign_key' => 'sub_cat_id',
            'get_relate' => FALSE
        );*/
    }
    
   
    
    public function _form(){

    }
}

