<?php

class Bank_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'banks';
        $this->primary_key = 'id';
        $this->table_fields = ["id", "name", "code"];
        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';

        $this->_config();
    }

    public function _config()
    {
        $this->timestamps = TRUE;
        // $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
}
