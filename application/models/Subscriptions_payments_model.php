<?php

class Subscriptions_payments_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'subscriptions_payments';
        $this->primary_key = 'id';

        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';

        $this->_config();
        $this->_form();
        $this->_relations();
    }

    protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id() ? $this->ion_auth->get_user_id() : $this->user_id; //add user_id
        return $data;
    }

    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id() ? $this->ion_auth->get_user_id() : $this->user_id; //add user_id
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
        $this->has_one['payment_method'] = array(
            'Payment_method_model',
            'id',
            'payment_method_id'
        );
    }

    public function _form()
    {
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

    public function updatePaymentStatus($packageID, $userID, $serviceID = 2, $paymentMethodID = null, $amount = null, $orderID = null, $txnID = null, $message = null, $upgrade = 0)
    {
        $this->load->model('package_model');
        $this->load->model('vendor_package_model');
        $package = $this->package_model->where('id', $packageID)->get();
        $this->user_id = $userID;
            $txn_id = (empty($txnID)) ? uniqid() : $txnID;
            $order_id = (empty($orderID)) ? NULL : $orderID;
            $is_inserted = $this->insert([
                'payment_method_id' => $paymentMethodID,
                'txn_id' => $txn_id,
                'amount' => $amount,
                'message' => !empty($message) ? $message : NULL,
                'status' => 2,
            ]);
            $wallet_txn_id = 'NC-' . generate_trasaction_no();
            $amount = $amount;
            $this->user_model->payment_update($this->config->item('super_admin_user_id'), $amount, 'CREDIT', "wallet", $wallet_txn_id, $order_id,!empty($message) ? $message : NULL);
            if ($upgrade == 1) {
                $this->vendor_package_model->update(['status' => 2], [
                    'service_id' => empty($serviceID) ? NULL : $serviceID,
                    'created_user_id' => $userID,
                    'status' => 1
                ]);
            }
            $is_payment = $this->vendor_package_model->insert([
                'service_id' => empty($serviceID) ? NULL : $serviceID,
                'package_id' => empty($packageID) ? NULL : $packageID,
                'payment_method_id' => empty($paymentMethodID) ? NULL : $paymentMethodID,
                'payment_txn_id' => empty($is_inserted) ? NULL : $is_inserted,
                'created_user_id' => $userID
            ]);
            return true;
    }
}