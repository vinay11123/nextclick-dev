<?php

class Delivery_job_event_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'delivery_job_events';
        $this->primary_key = 'id';
        
       $this->_config();
        $this->_form();
       $this->_relations();
    }
    
    public function _config() {
    }
    
    public function _relations(){
        $this->has_one['delivery_boy'] = array('User_model', 'id', 'delivery_boy_user_id');
    }

      private function _form(){
        
        // $this->rules['update_rules'] = array(
        //      array(
        //         'field' => 'rating',
        //         'lable' => 'rating',
        //         'rules' => 'trim|required'
        //     ),
        //     array(
        //         'field' => 'feedback',
        //         'lable' => 'feedback',
        //         'rules' => 'trim'
        //     ),
        // );
    }

    
}

