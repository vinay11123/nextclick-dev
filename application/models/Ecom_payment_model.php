<?php

class Ecom_payment_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'ecom_payments';
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
        $this->has_one['payment_method'] = array(
            'Payment_method_model',
            'id',
            'payment_method_id'
        );
    }
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'amount',
                'label' => 'Amount',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'payment_method_id',
                'label' => 'Payment mode',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'status',
                'label' => 'Payment Status',
                'rules' => 'trim|required',
            ),
        );
    }

    public function getPaymentByOrderID($orderID){
        try{
            $paymentInfo = $this->get([
                "ecom_order_id"=>$orderID
            ]);
            return $paymentInfo;
        }catch(Exception $e){
            return null;
        }
    }

    public function markPaid($id, $paymentID, $amountPaid){
        try{
            $this->update([
                "status"=> 2,
                "txn_id"=> $paymentID,
                "amount"=> $amountPaid
            ], $id);
            return true;
        }catch(Exception $e){
            return null;
        }
    }
}

