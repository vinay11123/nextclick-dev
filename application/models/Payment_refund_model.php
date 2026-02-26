<?php

class Payment_refund_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'payment_refunds';
        $this->primary_key = 'id';
        $this->table_fields = ["id", "ecom_order_id", "ecom_payment_id", "refund_ref", "is_partial", "amount", "status", "created_at", "updated_at"];
        $this->_config();
        $this->_form();
        $this->_relations();
    }

    public function _config()
    {
        $this->timestamps = TRUE;
        $this->delete_cache_on_save = TRUE;
    }

    public function _relations()
    {

    }

    public function _form()
    {
        $this->rules['create'] = array(
            array(
                'field' => 'ecom_order_id',
                'label' => 'Order ID',
                'rules' => 'required'
            ),

            array(
                'field' => 'ecom_payment_id',
                'label' => 'Payment ID',
                'rules' => 'required'
            ),
            array(
                'field' => 'refund_ref',
                'label' => 'Refund Reference',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'amount',
                'label' => 'Amount',
                'rules' => 'required'
            )
        );
    }
}
