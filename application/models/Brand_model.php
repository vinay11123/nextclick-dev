<?php

class Brand_model extends MY_Model
{

    public $rules,$user_id;
    public function __construct()
    {
        parent::__construct();
       
       
            $this->table="brands";
            $this->primary_key="id";
            
            $this->before_create[] = '_add_created_by';
            $this->before_update[] = '_add_updated_by';
            $this->config();
            $this->forms();
            $this->relations();
    }
    protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id()? $this->ion_auth->get_user_id(): $this->user_id; //add user_id
        return $data;
    }

    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id()? $this->ion_auth->get_user_id(): $this->user_id; //add user_id
        return $data;
    } 
    public function config(){
        $this->timestamps = FALSE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
        
    }
 
    public function relations() {
       
    }
    public function forms(){
        $this->rules['create_rules'] = array(
            array(
                'field' => 'name',
                'label' => 'Name',
                //'rules' => 'trim|regex_match[/^[a-zA-Z]/]|required',
                'rules' => 'trim|required',
                'errors' =>  array(
                    //'regex_match' => 'You must provide characters only',
                    'required' => 'You must provide a %s.',
                   
                )
            ),
            array(
                'field' => 'desc',
                'lable' => 'Description',
                'rules' => 'trim|required|max_length[200]',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            )
        );
        $this->rules['update_rules'] = array(
            array(
                'field' => 'name',
                'label' => 'Name',
                //'rules' => 'trim|regex_match[/^[a-zA-Z]/]|required',
                'rules' => 'trim|required',
                'errors' =>  array(
                    //'regex_match' => 'You must provide characters only',
                    'required' => 'You must provide a %s.',
                   
                )
            ),
            array(
                'field' => 'desc',
                'lable' => 'Description',
                'rules' => 'trim|required|max_length[200]',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            )
        );
    }

    public function get_brands($group = NULL, $search = NULL)
	{
		$this->_query_brands($group, $search);
		$this->db->order_by('`brands`.id', 'ASCE');
		$rs  = $this->db->get($this->table);
		return   $rs->result_array();
	}
	
	public function get_brands_data($limit = NULL, $offset = NULL,$group = NULL, $search = NULL)
	{
		$this->_query_brands($group, $search);
		$this->db->order_by('`brands`.id', 'ASCE');
		$this->db->limit($limit, $offset);
		$rs  = $this->db->get($this->table);
		return   $rs->result_array();
	}
	
	public function get_brands_count($group = NULL, $search = NULL)
	{
		$this->_query_brands($group, $search);
		$this->db->order_by('`brands`.id', 'ASCE');
		
		return   $this->db->count_all_results($this->table);
	}
    private function _query_brands($group = NULL, $search = NULL)
	{
		$primary_key = '`' . $this->primary_key . '`';
		$table       = '`' . $this->table . '`';
		$str_select_brands = '';  
		foreach (array('id', 'name', 'desc','created_user_id', 'updated_user_id') as $v) {
			$str_select_brands .= "$table.`$v`,";
		}
        $str_select_brands .= "brands.`status`,";
		$this->db->select($str_select_brands);
		if (!empty($search)) {
			$this->db->or_like($table . '.`name`', $search);
			$this->db->or_like($table . '.`desc`', $search);
			$this->db->or_where($table . '.`id`', $search);
		}
        if (!empty($group) && $group != 0) {
			$this->db->where($table . '.`status`', $group);
		}
		return $this;
	}
}

