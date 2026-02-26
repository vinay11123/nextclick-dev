<?php

class Wallet_transaction_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'wallet_transactions';
        $this->primary_key = 'id';
        
        $this->_config();
        $this->_form();
        $this->_relations();
    }
    
    public function _config(){
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
    
    
    public function _relations() {
        $this->has_one['bank'] = array('Bank_details_model', 'id', 'bank_id');
        $this->has_one['order'] = array('Ecom_order_model', 'id', 'ecom_order_id');
    }
    
    public function _form(){
        
    }
    
    public function all($limit = 0, $offset = 0, $user_id = NULL, $start_date = NULL, $end_date = NULL, $last_days = NULL, $last_years = NULL, $type = NULL, $status = NULL, $is_count = FALSE)
    {

        
        $this->_query_all($user_id, $start_date, $end_date, $last_days, $last_years, $type, $status);

        $this->db->order_by('created_at', 'DESC');
        
        if($is_count){
            return $this->db->count_all_results($this->table);
        }
        if(! empty($limit)){
            $this->db->limit($limit, $offset);
        }
        $rs     = $this->db->get($this->table);
        //print_array($this->db->last_query());
        if (! empty($rs))
            $result = $rs->result_array();
        else
            $result = NULL;
            
        return $result;
    }
    
    private function _query_all($user_id = NULL, $start_date = NULL, $end_date = NULL, $last_days = NULL, $last_years = NULL, $type = NULL, $status = NULL)
    {
        $this->load->model('ecom_order_model'); 
        $table       = '`' . $this->table . '`';
        
        $str_select_wallet = '';
        foreach (array('id', 'txn_id', 'type', 'account_user_id', 'amount', 'balance', 'ecom_order_id', 'message', 'created_at', 'updated_at', 'status') as $v)
        {
            $str_select_wallet .= "$table.`$v`,";
        }
        $str_select_wallet .="track_id,";
        $this->db->select($str_select_wallet);
        $this->db->join($this->ecom_order_model->table, "ecom_order_id=".$this->ecom_order_model->table.".id", "left");
        if (! empty($last_days)) {
            $this->db->where("$table.created_at BETWEEN CURDATE() - INTERVAL ".$last_days." DAY AND CURDATE()");
        }
        
        if (! empty($last_years)) {
            $this->db->where("$table.created_at BETWEEN CURDATE() - INTERVAL ".$last_years." YEAR AND CURDATE()");
        }
        
        if (! empty($start_date) && ! empty($end_date)){
            $this->db->or_where('date(`wallet_transactions`.`created_at`) BETWEEN "'. date('Y-m-d', strtotime($start_date)). '" and "'. date('Y-m-d', strtotime($end_date)).'"');
        }elseif (! empty($start_date) &&  empty($end_date)){
            $this->db->or_where("date(`wallet_transactions`.`created_at`)=",  date('Y-m-d', strtotime($start_date)));
        }
        
        if ($type != NULL) {
            $this->db->where($table . '.`type`', $type);
        }
        
        if ($status != NULL) {
            $this->db->where($table . '.`status`', $status);
        }
        
        $this->db->where("$table.`account_user_id`=", $user_id);
        $this->db->where("$table.`deleted_at` =", NULL);
        return $this;
    }

    public function getWalletTransactionByPaymentID($paymentID){
        try{
            $transaction = $this->where(['ecom_payment_id' =>$paymentID])->get();
            return [
                "success" => true,
                "data" =>$transaction
            ];
        }catch(Exception $ex){
            return [
                "success" => false,
                "error" => $ex
            ];
        }
    }

    public function updateWalletTransactionByID($ID, $data){
        try{
            $this->update($data, $ID);
            return [
                "success" => true
            ];
        }catch(Exception $ex){
            return [
                "success" => false
            ];
        }
    }

    public function getDeleveryBoyEarningTransactions($user_id = NULL, $start_date = NULL, $end_date = NULL)
    {
        
        $sql = "SELECT * FROM `delivery_boy_ecom_earnings_view` where `account_user_id` = ".$user_id." and date(`created_at`) between '".date('Y-m-d',strtotime($start_date))."' and '".date('Y-m-d',strtotime($end_date))."' order by created_at desc";

        $result =	$this->db->query($sql)->result_array();

        return $result;
        
    }

    public function getVendorEarningTransactions($user_id = NULL, $start_date = NULL, $end_date = NULL)
    {
        
        $sql = "SELECT * FROM `vendor_earnings_view` where `account_user_id` = ".$user_id." and date(`created_at`) between '".date('Y-m-d',strtotime($start_date))."' and '".date('Y-m-d',strtotime($end_date))."' order by created_at desc";

        $result =	$this->db->query($sql)->result_array();

        return $result;
        
    }
}
