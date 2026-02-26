<?php

class Pickup_orders_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'pickup_orders';
        $this->primary_key = 'id';
        
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
        $this->has_one['pickup_address'] = array(
            'Users_address_model',
            'id',
            'pickup_address_id'
        );
        $this->has_one['delivery_address'] = array(
            'Users_address_model',
            'id',
            'delivery_address_id'
        );
        
        
        /*$this->has_one['customer'] = array(
            'User_model',
            'id',
            'created_user_id'
        );*/
        
        $this->has_one['order_status'] = array(
            'Ecom_order_status_model',
            'id',
            'order_status_id'
        );
        
        
        $this->has_one['payment'] = array(
            'Ecom_payment_model',
            'id',
            'payment_id'
        );

        $this->has_one['pickupanddropcategory'] = array(
            'Pickupcategory_model',
            'id',
            'pickupanddropcategory_id'
        );
        
       /* $this->has_many['reject_request'] = array(
            'foreign_model' => 'Ecom_order_reject_request_model',
            'foreign_table' => 'ecom_order_reject_requests',
            'local_key' => 'id',
            'foreign_key' => 'ecom_order_id',
            'get_relate' => FALSE
        );
        
        $this->has_many['ecom_order_details'] = array(
            'foreign_model' => 'Ecom_order_deatils_model',
            'foreign_table' => 'ecom_order_details',
            'local_key' => 'id',
            'foreign_key' => 'ecom_order_id',
            'get_relate' => FALSE
        );*/
        
    }
    
    public function _form(){
        $this->rules['create'] = array(
            array(
                'field' => 'payment_id',
                'label' => 'Payment Id',
                'rules' => 'trim|required'
            )
        );
        
    }
    

    public function getByTrackID($trackingID){
        try {
            $orderDetails = $this->get(["track_id" => $trackingID]);
            return $orderDetails;
        } catch (Exception $e) {
            return null;
        }
    }

    public function updateOrderStatus($orderID, $deliveryMode, $statusCode){
        try{
            $this->pickup_orders_model->update([
                'id' => $orderID,
                'order_status_id' => $this->ecom_order_status_model->fields('id')->where(['delivery_mode_id' => $deliveryMode, 'serial_number' => $statusCode])->get()['id']
            ], 'id');
            return [
                "success" => true,
                "data"=> [
                    "id"=>$orderID
                ]
            ];
        }catch(Exception $e){
            return [
                "success" =>false,
                "error"=>$e
            ];
        }
    }

    public function get_orders($limit = 0, $offset = 0, $user_id = NULL, $start_date = NULL, $end_date = NULL,
     $last_days = NULL, $last_years = NULL, $status = NULL, $delivery_status = NULL, $is_count = FALSE, $uri = 'order_history')
    {
        $this->_query_orders($user_id, $start_date, $end_date, $last_days, $last_years, $status, $delivery_status, $uri);
        $this->db->order_by('`pickup_orders`.id', 'DESC');
        $this->db->group_by('`pickup_orders`.id');
        
        if($is_count){
            return $this->db->count_all_results($this->table);
        }
        if(! empty($limit)){
            $this->db->limit($limit, $offset);
        }
        
        $rs = $this->db->get($this->table);
        //print_array($this->db->last_query());
        if (! empty($rs))
            $result = $rs->result_array();
        else
            $result = NULL;
                
        return $result;
    }
    
    private function _query_orders($user_id = NULL, $start_date = NULL, $end_date = NULL, $last_days = NULL, $last_years = NULL, $status = NULL, $delivery_status = NULL, $uri = 'order_history')
    {
        $table = '`' . $this->table . '`';
        $selected_colums_list = [];
        if($uri == 'order_history'){
            $this->db->select(' delivery_jobs.id as delivery_job_id, delivery_jobs.job_id, delivery_jobs.status as delivery_job_status');
            $this->db->join('`delivery_jobs`', "pickup_orders.id=delivery_jobs.pickup_order_id", 'left');
            $selected_colums_list = ['id', 'track_id', 'order_delivery_otp',  'payment_id',  'delivery_fee', 'created_at', 'updated_at', 'order_status_id'];
        }elseif ($uri == 'delivery_orders'){
            $this->db->select(' delivery_jobs.id as delivery_job_id, delivery_jobs.job_id, delivery_jobs.status');
            $this->db->join('`delivery_jobs`', "pickup_orders.id=delivery_jobs.pickup_order_id", 'left');
            $selected_colums_list = ['id', 'track_id', 'order_pickup_otp', 'order_pickup_otp', 'pickup_address_id','delivery_address_id','payment_id', 'delivery_fee',  'created_user_id', 'created_at', 'updated_at', 'order_status_id', ];
        }
        
        $str_select_order = '';
        if(! empty($selected_colums_list)){foreach ($selected_colums_list as $v) {
            $str_select_order .= "$table.`$v`,";
        }}else {
            $str_select_order = "$table.*";
        }
        
        $this->db->select($str_select_order."delivery_jobs.status as delivery_job_status, delivery_jobs.id as delivery_job_id");
        $this->db->join('`users`', "pickup_orders.created_user_id=users.id", 'left');
        
        
        if (! empty($last_days)) {
            $this->db->where("$table.created_at BETWEEN CURDATE() - INTERVAL ".$last_days." DAY AND CURDATE()");
        }
        
        if (! empty($last_years)) {
            $this->db->where("$table.created_at BETWEEN CURDATE() - INTERVAL ".$last_years." YEAR AND CURDATE()");
        }
        
        if(! empty($q)){
            $this->db->where("$table.track_id", $q);
        }
        
        if (! empty($start_date) && ! empty($end_date)){
            $this->db->or_where('date(`pickup_orders`.`created_at`) BETWEEN "'. date('Y-m-d', strtotime($start_date)). '" and "'. date('Y-m-d', strtotime($end_date)).'"');
        }elseif (! empty($start_date) &&  empty($end_date)){
            $this->db->or_where("date($table.`created_at`)=",  date('Y-m-d', strtotime($start_date)));
        }
        
        if ($status != NULL) {
            $this->db->where_in($table . '.`order_status_id`', explode(',', $status));
        }
        
        if (($delivery_status != NULL) && ($delivery_status == 500 || $delivery_status == 501 || $delivery_status == 502 || $delivery_status == 503 || $delivery_status == 504 || $delivery_status == 505 || $delivery_status == 506 || $delivery_status == 507 || $delivery_status == 508)) {
            $this->db->where('`delivery_jobs`' . '.`status`', $delivery_status);
        }
        
        if($uri == 'order_history'){
            $this->db->where("$table.created_user_id", $user_id);
        }elseif ($uri == 'vendor_orders'){
            $this->db->where("$table.vendor_user_id", $user_id);
        }elseif ($uri == 'delivery_orders'){
            $this->db->where("delivery_jobs.delivery_boy_user_id", $user_id);
        }
        
        $this->db->where("$table.deleted_at =", NULL);
        return $this;
    }
}

