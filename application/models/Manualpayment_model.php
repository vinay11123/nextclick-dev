<?php

class Manualpayment_model extends MY_Model {

    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table="manual_payments";
        $this->primary_key="id";
        
        // $this->before_create[] = '_add_created_by';
        // $this->before_update[] = '_add_updated_by';
        
        
        $this->_config();
        $this->_form();
        $this->_relations();
        
    }

    private function _config()
    {
        $this->timestamps = TRUE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
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

    public function _form(){
        $this->rules['save'] = array(
            array(
                'field'=>'payment_intent',
                'label'=>'Payment Intent',
                'rules'=>'trim|required'
            ), 
            array(
                'field'=>'payment_txn_id',
                'label'=>'Payment Transaction ID',
                'rules'=>'trim|required'
            ),
            array(
                'field'=>'amount',
                'label'=>'Amount',
                'rules'=>'trim|required'
            ),
            array(
                'field'=>'status',
                'label'=>'Status',
                'rules'=>'trim|required'
            ),
            array(
                'field'=>'info',
                'label'=>'Info',
                'rules'=>'trim|optional'
            )
        );
    }

    protected function _relations(){

    }

    public function getAll() {
        return $this->get_all();
    }

    public function getPendingPayments() {
        //where_in('id', ['20','15','22','42','86']);
        $result = $this->where('status',[1,2])->order_by('created_at', 'DESC')->get_all();
        //echo $this->db->last_query();die();
        return ($result) ? $result : [];
    }

}