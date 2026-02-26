<?php

class Partnership_applicable_document_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'partnership_applicable_documents';
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
        $this->has_one['document'] = array(
            'foreign_model' => 'Document_type_model',
            'local_key' => 'doc_type_id',
            'foreign_key' => 'id'
        );
    }

    public function getList($intent){
        try{
            $docsList = $this->where([
                "intent" => $intent
            ])
            ->fields(['order', 'is_required'])
            ->with_document('fields: short_name, display_name, pattern, has_back_page')
            ->order_by('order', 'ASC')->get_all();
            return [
                'success' => true,
                'data'=> $docsList
            ];
        }catch(Exception $ex){
            return [
                'success' => false,
                'error'=> $ex
            ];
        }
    }
}

