<?php
class User_doc_model extends MY_Model {
	public $rules, $user_id;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'user_docs';
        $this->primary_key = 'id';
        
        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';
        
       $this->_config();
    }
    
    protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->user_id?  $this->user_id : $this->ion_auth->get_user_id(); //add user_id
        return $data;
    }
    
    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->user_id?  $this->user_id : $this->ion_auth->get_user_id(); //add user_id
        return $data;
    }
    
    public function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }
}