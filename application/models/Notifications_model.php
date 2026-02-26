<?php

class Notifications_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'notifications';
        $this->primary_key = 'id';

        $this->_config();
        $this->_form();
        $this->_relations();
    }

    public function _config()
    {
        $this->timestamps = FALSE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }

    public function _relations()
    {
        $this->has_one['user'] = array('User_model', 'id', 'user_id');
        $this->has_one['order'] = array('Ecom_order_model', 'id', 'ecom_order_id');
        $this->has_one['pickup_order'] = array('Pickup_orders_model', 'id', 'pickup_order_id');
    }

    public function _form()
    {
        $this->rules = array(
            array(
                'lable' => 'User',
                'field' => 'user_id',
                'rules' => 'required'
            ),
            array(
                'lable' => 'Title',
                'field' => 'title',
                'rules' => 'required'
            ),
            array(
                'lable' => 'Description',
                'field' => 'desc',
                'rules' => 'required'
            ),
        );
    }

    public function getAdminNotifications()
    {
        $this->load->model('manualpayment_model');
        $adminNotificationSummary = [];
        $adminNewVendorNotificationSummary = [];
        $adminNewPartnerNotificationSummary = [];
        $adminNewProductNotificationSummary = [];
        $manualPayments = [];
        $overallCount = 0;
        $manualPayments = $this->manualpayment_model->getPendingPayments();
        $getNew = $this->getNewVendorCreation();
        $getNewPartner = $this->getNewDParnerCreation();
        $getNewProduct = $this->getProductCreation();
        $overallCount += count($manualPayments) + count($getNew) + count($getNewPartner) + count($getNewProduct);
        array_push($adminNewVendorNotificationSummary, [
            'title' => "New Vendor Created",
            'key' => "new_vendor_created",
            'count' => count($getNew)
        ]);

        array_push($adminNotificationSummary, [
            'title' => "Manual Payments",
            'key' => "manual_payments",
            'count' => count($manualPayments)
        ]);

        array_push($adminNewPartnerNotificationSummary, [
            'title' => "New Partner Created",
            'key' => "new_partner_created",
            'count' => count($getNewPartner)
        ]);

        array_push($adminNewProductNotificationSummary, [
            'title' => "New Product Created",
            'key' => "new_product_created",
            'count' => count($getNewProduct)
        ]);

        return [
            "overall_count" => $overallCount,
            "data" => $adminNotificationSummary,
            "vendor" => $adminNewVendorNotificationSummary,
            "Partner" => $adminNewPartnerNotificationSummary,
            "Product" => $adminNewProductNotificationSummary
        ];
    }
    public function getNewVendorCreation()
    {
        $this->db->select("*");
        $this->db->from("notifications");
        $this->db->where('notification_type_id', 26);
        $this->db->where('read_status', '0');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getNewDParnerCreation()
    {
        $this->db->select("*");
        $this->db->from("notifications");
        $this->db->where('notification_type_id', 27);
        $this->db->where('read_status', '0');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getProductCreation()
    {
        $this->db->select("*");
        $this->db->from("notifications");
        $this->db->where('notification_type_id', 28);
        $this->db->where('status', '1');
        $this->db->where('notified_user_id', 1);
        $query = $this->db->get();
        return $query->result_array();
    }
}
