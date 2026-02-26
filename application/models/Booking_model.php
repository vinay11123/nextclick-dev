<?php

class Booking_model extends MY_Model
{
    public $rules, $user_id;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'bookings';
        $this->primary_key = 'id';
        $this->foreign_key = 'booking_id';
        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';
        
       $this->_config();
       $this->_form();
       $this->_relations();
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
    
    public function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
        $this->has_one['created_user'] = array(
            'User_model',
            'id',
            'created_user_id'
        );
        
        $this->has_many['booking_items'] = array(
            'foreign_model' => 'Booking_item_model',
            'foreign_table' => 'booking_items',
            'local_key' => 'id',
            'foreign_key' => 'booking_id',
            'get_relate' => FALSE
        );
    }
    
   
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'vendor_id',
                'lable' => 'Vendor Id',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            )
        );
    }
    
    public function get_bookings($limit = NULL, $offset = NULL, $q = NULL, $status = NULL, $vendor_unique_id = NULL, $service_id  = 11, $is_count = FALSE)
    {
        $this->_query_bookings($service_id, $q, $status, $vendor_unique_id);
        $this->db->order_by('`bookings`.id', 'DESC');
        $this->db->group_by('`bookings`.`id`');
        if($is_count){
            return $this->db->count_all_results($this->table);
        }
        $this->db->limit($limit, $offset);
        $rs = $this->db->get($this->table);
        if (! empty($rs))
            $result = $rs->result_array();
        else
            $result = [];
                
        return $result;
    }

    private function _query_bookings($service_id  = 11, $q = NULL, $status = NULL,  $vendor_unique_id = NULL)
    {
        

        $primary_key = '`' . $this->primary_key . '`';
        $table = '`' . $this->table . '`';

        $str_select_vendor = '';
        foreach (array( 'created_at', 'id', 'track_id', 'sub_total', 'discount', 'tax', 'total', 'vendor_id', 'booking_status') as $v) {
            $str_select_vendor .= "$table.`$v`,";
        }

        $this->db->select($str_select_vendor."users.first_name as customer_name, users.unique_id as customer_unique_id, vendors_list.unique_id as vendor_unique_id, vendors_list.name as vendor_name");
        $this->db->join('`vendors_list`', "bookings.vendor_id=vendors_list.vendor_user_id", 'left');
        $this->db->join('`users`', "bookings.created_user_id=users.id", 'left');
        $this->db->join('`booking_items`', "bookings.id=booking_items.booking_id", 'left');
        
        if (! empty($vendor_unique_id)) {
            $this->db->or_where('vendors_list.unique_id', $vendor_unique_id);
            $this->db->or_where('users' . '.`unique_id`', $vendor_unique_id);
        }
        
        if(! empty($q)){
            $this->db->where("$table.track_id", $q);
        }

        if ($status == 1 || $status == 2 || $status == 3 ||$status == 4 ||$status == 5 ) {
            $this->db->where($table . '.`booking_status`', $status);
        }
        
        $this->db->where("booking_items.service_id", $service_id);
        $this->db->where("$table.deleted_at =", NULL);
        return $this;
    }
    
}

