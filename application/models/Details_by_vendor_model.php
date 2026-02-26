<?php

class Details_by_vendor_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendor_details';
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
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'customer_name',
                'lable' => 'Customer Name',
                'rules' => 'trim|required',
                'errors'=>array(
                    'required'=>'Please Provide Your Name'
                )
            ),
            array(
                'field' => 'shop_name',
                'lable' => 'Shop/Business Name',
                'rules' => 'trim|required',
                'errors'=>array(
                    'required'=>'Please Provide Shop/Business Name'
                )
            ),
            array(
                'field' => 'landmark',
                'lable' => 'Landmark',
                'rules' => 'trim|required',
                'errors'=>array(
                    'required'=>'Please Provide Landmark'
                )
            ),
            array(
                'field' => 'address',
                'lable' => 'Address',
                'rules' => 'trim|required',
                'errors'=>array(
                    'required'=>'Please Provide Address'
                )
            ),
            
            array(
                'label' => 'Mobile Number',
                'field' => 'phone',
                'rules' => 'required|min_length[10]|max_length[12]|regex_match[/^[0-9]{10}$/]|callback_check_user_phone',
                'errors' => array(
                    'min_length' => 'Please give minimum 10 digits number',
                    'max_length' => 'You can give maximum 10 digits number',
                    'regex_match' => 'Please give a valid number',
                    'is_unique' => 'Sorry! Mobile number is already exist!'
                )
            ),
            array(
                'label' => 'email',
                'field' => 'email',
                'rules' => 'required|valid_email|callback_check_user_email',
                'errors' => array(
                    'valid_email' => 'Please give valid email!',
                    'is_unique' => 'Sorry! Email id is already exist!'
                )
            ),
        );
    }
    
    public function get_vendors($limit = NULL, $offset = NULL, $search = NULL, $till_date = NULL, $mobile = NULL)
    {
        $this->_query_vendors( $search, $till_date, $mobile);
        $this->db->order_by('`vendor_details`.id', 'DESC');
        $this->db->order_by('`vendor_details`.created_at', 'DESC');
        $this->db->order_by('`vendor_details`.updated_at', 'DESC');
        $this->db->group_by('`vendor_details`.`unique_id`');
        $this->db->limit($limit, $offset);
        $rs = $this->db->get($this->table);
        if (! empty($rs))
            $result = $rs->result_array();
            else
                $result = [];
                
        return $result;
}

public function vendor_count( $search = NULL, $till_date = NULL, $mobile = NULL)
{
    $this->_query_vendors( $search, $till_date, $mobile);
    return $this->db->count_all_results($this->table);
}

private function _query_vendors($search = NULL, $till_date = NULL, $mobile = NULL)
{
    $this->load->model(array(
        'location_model',
        'category_model',
    ));
    
    $category_table = '`' . $this->category_model->table . '`';
    $primary_key = '`' . $this->primary_key . '`';
    $table = '`' . $this->table . '`';
    
    $str_select_vendor = '';
    foreach (array(
        'created_at',
        'updated_at',
        'deleted_at',
        'id',
        'list_id',
        'landmark',
        'address',
        'customer_name',
        'email',
        'unique_id',
        'shop_name'
    ) as $v) {
        $str_select_vendor .= "$table.`$v`,";
    }
    
    $this->db->select($str_select_vendor . "$category_table.`id` as category_id");
    $this->db->join("vendors_list", "vendors_list.$primary_key=$table.list_id");
    $this->db->join($category_table, "$category_table.$primary_key=$table.cat_id");
    
    if (! empty($search)) {
        $this->db->or_like($table . '.`customer_name`', $search);
        $this->db->or_like($category_table . '.`name`', $search);
        $this->db->or_like($table . '.`shop_name`', $search);
    }
    if (! empty($till_date)) {
        $this->db->or_where($table . '.`created_at` <=', date('Y-m-d H:i:s', strtotime($till_date)));
        $this->db->or_where($table . '.`updated_at` <=', date('Y-m-d H:i:s', strtotime($till_date)));
    }
    if (! empty($mobile)) {
        $this->db->join('`contacts`', "vendors_list.id=contacts.list_id", 'left');
        $this->db->or_where('contacts.number', $mobile);
    }
    
    $this->db->where("$table.deleted_at !=", NULL);
    return $this;
}
}

