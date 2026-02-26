<?php
error_reporting(E_ERROR | E_PARSE);
class Dashboard extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->template = 'vendorCrm/master';

        if (!$this->ion_auth->logged_in())
            redirect('auth/login');

        $this->load->model('vendor_list_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('vendor_leads_model');
        $this->load->model('user_model');
        $this->load->model('pickupcategory_model');
    }

    public function index()
    {
        $this->data['users_groups']           =   $this->ion_auth->get_users_groups()->result();
        $this->data['users_permissions']      =   $this->ion_auth_acl->build_acl();
        $this->data['title'] = 'Dashboard';
        $this->data['content'] = 'admin/dashboard';
        $this->data['nav_type'] = 'dashboard';
        $vendor_data_sql = "SELECT * FROM vendors_list WHERE vendor_user_id=" . $this->data['user']->id;
        $query = $this->db->query($vendor_data_sql);
        $this->data['vendor_data'] = $query->result_array();
        $this->_render_page($this->template, $this->data);
    }
    public function notification()
    {
        $this->data['users_groups']           =   $this->ion_auth->get_users_groups()->result();
        $this->data['users_permissions']      =   $this->ion_auth_acl->build_acl();
        $this->data['title'] = 'Notification';
        $this->data['content'] = 'admin/notification';
        $this->data['nav_type'] = 'notification';
        $this->template = 'vendorCrm/notification';
        $notification_update = "update notifications set status=2 where notified_user_id=" . $this->ion_auth->get_user_id() . " and notification_type_id=9";
        $query_update = $this->db->query($notification_update);
        $notification_sql = "SELECT * FROM notifications where notified_user_id=" . $this->ion_auth->get_user_id() . " and notification_type_id=9 order by id desc";
        $query = $this->db->query($notification_sql);
        $this->data['notification_data'] = $query->result_array();
        $this->_render_page($this->template, $this->data);
    }
}
