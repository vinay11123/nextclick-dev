<?php

class Document_type_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'document_types';
        $this->primary_key = 'id';
        
       $this->_config();
       $this->_relations();
    }
    private function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }
    
    private function _relations(){
        // $this->has_many['constituenceis'] = array(
        //     'foreign_model' => 'Constituency_model',
        //     'foreign_table' => 'constituencies',
        //     'local_key' => 'id',
        //     'foreign_key' => 'district_id'
        // );
    }
}

