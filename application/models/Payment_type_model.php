<?php

class Payment_type_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'paymentType';
        $this->primary_key = 'id';

        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';

        $this->_config();
        $this->_form();
        $this->_relations();
    }
    protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    }
    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    }
    public function _config()
    {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }

    public function _relations()
    {

    }

    public function _form()
    {

    }

    public function getPaymentType($orderId)
    {
        $this->db->select('pm.name as paymentType');
        $this->db->from('ecom_payments');
        $this->db->join('payment_methods pm', 'pm ON pm.id=ecom_payments.payment_method_id', 'inner');
        $this->db->where('ecom_payments.ecom_order_id', $orderId);
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
}

