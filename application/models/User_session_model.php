<?php

class User_session_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'user_sessions';
        $this->primary_key = 'id';
        
       $this->_config();
    //    $this->_relations();
    }
    private function _config() {
        $this->timestamps = FALSE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }

    public function save($userID, $groupID, $token, $time){
        try{
            $userSession = $this->insert([
                'user_id' =>$userID,
                'group_id' =>$groupID,
                'token' => $token,
                'created_time' => $time
            ]);
            return [
                'success' => true,
                'data'=> [
                    'id' =>$userSession
                ]
            ];
        }catch(Exception $ex){
            return [
                'success' => false,
                'error'=> $ex
            ];
        }
    }
}

