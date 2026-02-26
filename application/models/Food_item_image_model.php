<?php

class Food_item_image_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'food_item_images';
        $this->primary_key = 'id';
      //   $this->before_create[] = '_add_created_by';
      //  $this->before_update[] = '_add_updated_by';

        
        
       $this->_config();
       $this->_form();
       $this->_relations();
    }
     
    public function _config() {
        $this->timestamps = FALSE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = FALSE;
    }
    

   /*     protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id()? $this->ion_auth->get_user_id(): $this->user_id; //add user_id
        return $data;
    }

    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id()? $this->ion_auth->get_user_id(): $this->user_id; //add user_id
        return $data;
    }

*/

    public function _relations(){
       
    }
    
    public function _form(){
        
    }

    public function deletedata($id)
    {
         $this->db->where('item_id', $id);
         $this->db->delete('food_item_images'); 

    }
}

