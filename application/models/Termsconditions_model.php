<?php

class Termsconditions_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'terms_conditions';
        $this->primary_key = 'id';

        $this->_config();
        $this->_form();
        $this->_relations();
    }
    public function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
       
    }
    
    
    private function _form(){
        $this->rules = array(
            array(
                'field' => 'desc',
                'lable' => 'desc',
                'rules' => 'trim|required'
               
            ),
        );
    }
    public function users_tc($id)
	{
		$this->db->select("title,desc");
		$this->db->from('terms_conditions as tc');
        $this->db->join('users_accepted_tc as utc', 'utc.tc_id=tc.id' ,'left');
		$this->db->where('utc.created_user_id',$id);
		$query = $this->db->get();
		return $query->result_array();
	}

    public function get_executive_register_terms()
	{
		$this->db->select("title,desc");
		$this->db->from('terms_conditions');
		$this->db->where('page_id','1');
        $this->db->where('title','Executive Registration Page T & C');
        
		$query = $this->db->get();
		return $query->result_array();
	}

    

}