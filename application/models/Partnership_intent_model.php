<?php

class Partnership_intent_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'partnership_intents';
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
        // $this->has_one['document'] = array(
        //     'foreign_model' => 'Document_type_model',
        //     'local_key' => 'doc_type_id',
        //     'foreign_key' => 'id'
        // );
    }

    public function save($postData){
        try{
            $this->load->model('user_role_model');
            $this->load->model('user_account_model');
            $this->db->trans_begin();
            $check_existing = $this->where('email', $postData['email'])
            ->or_where('mobile', $postData['mobile'])
            ->get();
            if($check_existing){
                throw new Error('USER_ALREADY_EXISTS');
            }else{
                $userID = $this->insert($postData);
                $this->user_role_model->saveUserRole($userID);
                $this->user_account_model->create($userID);
                if($postData['primary_intent'] != 'user'){
                    $this->user_role_model->saveRole($userID, $postData['primary_intent']);
                }
            }
            $this->db->trans_complete();
        }catch(Exception $ex){
            $this->db->trans_rollback();
        }
    }
}

