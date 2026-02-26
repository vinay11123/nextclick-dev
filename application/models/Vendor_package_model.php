<?php

class Vendor_package_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendor_packages';
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
       $this->has_one['packages'] = array('Package_model', 'id', 'package_id');
	   $this->has_one['services'] = array('Service_model', 'id', 'service_id');
	   $this->has_one['vendors'] = array('Vendor_list_model', 'vendor_user_id', 'created_user_id');
    }
    
    public function _form(){
        
    }
    	
	public function get_packages($limit = NULL, $offset = NULL, $service = NULL, $search = NULL, $package = NULL){

	    $this->_query_packages($service, $search, $package,);
	    $this->db->order_by('`vendor_packages`.id', 'DESC');
	    $this->db->order_by('`vendor_packages`.created_at', 'DESC');
	    $this->db->order_by('`vendor_packages`.updated_at', 'DESC');
	    $this->db->group_by('`vendor_packages`.`id`');
	    $this->db->limit($limit, $offset);
	    $rs     = $this->db->get($this->table);
		
	    return   $rs->result_array();
	}
	
	public function packages_count($service = NULL, $search = NULL, $package = NULL){
	    $this->_query_packages($service
        , $search, $package);
	    return $this->db->count_all_results($this->table);
	}
	
	private function _query_packages($service = NULL, $search = NULL, $package = NULL){
 
	    $this->load->model(array('package_model'));
	    
	    $package_table       = '`' . $this->package_model->table . '`';
	    $package_primary_key = '`' . $this->package_model->primary_key . '`';
	    $package_foreign_key = '`' . 'package_id' . '`';
	    
	    $primary_key = '`' . $this->primary_key . '`';
	    $table       = '`' . $this->table . '`';
	    
	    

	    if ( ! empty($search))
	    {
	        $this->db->or_like($table . '.`first_name`', $search);
	        $this->db->or_like($table . '.`last_name`', $search);
	        $this->db->or_where($table . '.`phone`', $search);
	    }
	    // if ( ! empty($service))
	    // {
	    //     $this->db->where($
		// 	 . '.`service_id`', $service_id);
	    // }
 
      
	    
	    if (! empty($package))
	    {
	    	 
	        $this->db->join($package_table, "$package_table.$primary_key='package.id'", 'left');
	        $this->db->join('vendor_packages',"`vendor_packages`.`package_id`=$table.$primary_key");
	        $this->db->where('`vendor_packages`.`package_id`', $package);
	    }
	    
	    $this->db->where("$table.deleted_at", NULL);
	    return $this;
	}
}

