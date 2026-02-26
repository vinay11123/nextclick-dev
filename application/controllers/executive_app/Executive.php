<?php
error_reporting(E_ERROR | E_PARSE);
class Executive extends MY_Controller
{
    public $data;
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        if (!$this->ion_auth->logged_in()) // || ! $this->ion_auth->is_admin()
            redirect('auth/login');

        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('category_model');
        $this->load->model('vendor_list_model');
        $this->load->model('user_model');
        $this->load->model('group_model');
        $this->load->model('constituency_model');
        $this->load->model('termsconditions_model');
        $this->load->model('executive_model');
        $this->load->model('executive_type_model');
    }

    public function emp_list($type = 'executive', $status = '')
    {

        if ($type == 'executive') {

            if (isset($_GET['exe_id'])) {
                $executive_id = $this->input->get('exe_id');

                $this->data['title'] = 'Executive Sub List';
                $this->data['nav_type'] = 'executives';
                $this->data['type'] = 'executive_sub_list';

                $this->db->select("CONCAT_WS(' ', u.first_name, u.last_name) as executive_name, ead.executive_type_id");
                $this->db->from('users u');
                $this->db->join('executive_address as ead', 'ead.user_id = u.id');
                $this->db->where('u.id', $executive_id);

                $query = $this->db->get();
                $execut_name = $query->result();
                $this->data['exe_name'] = $execut_name[0]->executive_name;
                $this->data['executive_type_id'] = $execut_name[0]->executive_type_id;

                $userResult = $this->user_model->get_users_count($executive_id);

                $this->data['users'] = $userResult['count'];

                $vendorResult = $this->vendor_list_model->get_vendor_count($executive_id);

                $this->data['vendor'] = $vendorResult['count'];

                $deliveryCaptainResult = $this->executive_model->get_executive_delivery_captain_list('', $executive_id);

                $this->data['deliveryCaptain'] = $deliveryCaptainResult['count'];

                $subscribed_vendors = $this->vendor_list_model->get_vendor_count($executive_id, 'subscribed');
                $this->data['subscribed_vendor_count'] = $subscribed_vendors['count'];
                $this->data['subscribed_vendor_list'] = $subscribed_vendors['vendor_details'];

                $unsubscribed_vendors = $this->vendor_list_model->get_vendor_count($executive_id, 'unsubscribed');
                $this->data['unsubscribed_vendor_count'] = $unsubscribed_vendors['count'];
                $this->data['unsubscribed_vendor_list'] = $unsubscribed_vendors['vendor_details'];

                $pending_vendors = $this->vendor_list_model->get_vendor_count($executive_id, 'pending');
                $this->data['pending_vendor_count'] = $pending_vendors['count'];
                $this->data['pending_vendor_list'] = $pending_vendors['vendor_details'];

                $target_achieved_captains = $this->executive_model->get_executive_delivery_captain_list('target_achieved', $executive_id);
                $this->data['target_achieved_captain_count'] = $target_achieved_captains['count'];
                $this->data['target_achieved_captains_list'] = $target_achieved_captains['captain_details'];

                $target_not_achieved_captains = $this->executive_model->get_executive_delivery_captain_list('target_not_achieved', $executive_id);
                $this->data['target_not_achieved_captain_count'] = $target_not_achieved_captains['count'];
                $this->data['target_not_achieved_captains_list'] = $target_not_achieved_captains['captain_details'];

                $pending_captains = $this->executive_model->get_executive_delivery_captain_list('pending', $executive_id);
                $this->data['pending_captain_count'] = $pending_captains['count'];
                $this->data['pending_captains_list'] = $pending_captains['captain_details'];

                $ordered_users = $this->user_model->get_executive_user_list('ordered', $executive_id);
                $this->data['ordered_user_count'] = $ordered_users['count'];
                $this->data['ordered_user_list'] = $ordered_users['user_details'];

                $not_ordered_users = $this->user_model->get_executive_user_list('not_ordered', $executive_id);
                $this->data['not_ordered_user_count'] = $not_ordered_users['count'];
                $this->data['not_ordered_user_list'] = $not_ordered_users['user_details'];

                $wallet_details = $this->executive_model->get_wallet_details($executive_id);

                if ($wallet_details) {
                    $this->data['total_vendor_amount'] = isset($wallet_details['total_vendor_amount']) ? $wallet_details['total_vendor_amount'] : 0;
                    $this->data['total_user_amount'] = isset($wallet_details['total_user_amount']) ? $wallet_details['total_user_amount'] : 0;
                    $this->data['total_delivery_boy_amount'] = isset($wallet_details['total_delivery_boy_amount']) ? $wallet_details['total_delivery_boy_amount'] : 0;
                    $this->data['total_all_amount'] = isset($wallet_details['total_all_amount']) ? $wallet_details['total_all_amount'] : 0;
                } else {
                    $this->data['total_vendor_amount'] = 0;
                    $this->data['total_user_amount'] = 0;
                    $this->data['total_delivery_boy_amount'] = 0;
                    $this->data['total_all_amount'] = 0;
                }

                $this->data['vendor_transaction_details'] = $this->executive_model->get_transaction_details($executive_id, 'vendor');
                $this->data['user_transaction_details'] = $this->executive_model->get_transaction_details($executive_id, 'user');
                $this->data['captain_transaction_details'] = $this->executive_model->get_transaction_details($executive_id, 'delivery_boy');

                $this->load->view('vendorCrm/executive_list', $this->data);
            } elseif (isset($_GET['eye_id'])) {
                $executive_id = $_GET['eye_id'];
                $this->data['title'] = 'Executive Details';
                $this->data['nav_type'] = 'executives';
                $this->data['type'] = 'executive';
                $this->data['users'] = $this->executive_model->get_executive_details($executive_id);

                $this->data['executive_bank_details'] = $this->executive_model->get_executive_bank_details($executive_id);
                $this->load->view('vendorCrm/executive_eye', $this->data);
            } else {
                $this->data['title'] = 'Executive List';
                $this->data['nav_type'] = 'executives';
                $this->data['type'] = 'executive';

                $this->data['executives'] = $this->executive_model->get_executive_list();

                $this->load->view('vendorCrm/executives', $this->data);
            }
        } else if ($type == 'vendors') {
            if ($status == 'submit') {

                $this->form_validation->set_rules('executive_id', 'Executive Name', 'required');
                if ($this->form_validation->run() == FALSE) {

                    $this->data['title'] = 'Vendor List';
                    $this->data['nav_type'] = 'vendors';
                    $this->data['type'] = 'vendors';

                    $subscribed_vendors = $this->vendor_list_model->get_executive_vendor_list('subscribed');
                    $this->data['subscribed_vendor_count'] = $subscribed_vendors['count'];
                    $this->data['subscribed_vendor_list'] = $subscribed_vendors['vendor_details'];

                    $unsubscribed_vendors = $this->vendor_list_model->get_executive_vendor_list('unsubscribed');
                    $this->data['unsubscribed_vendor_count'] = $unsubscribed_vendors['count'];
                    $this->data['unsubscribed_vendor_list'] = $unsubscribed_vendors['vendor_details'];

                    $pending_vendors = $this->vendor_list_model->get_executive_vendor_list('pending');
                    $this->data['pending_vendor_count'] = $pending_vendors['count'];
                    $this->data['pending_vendor_list'] = $pending_vendors['vendor_details'];

                    $this->data['executives'] = $this->user_model->order_by('id', 'DESC')
                        ->fields('id, first_name, last_name, email, phone, unique_id, created_at, status')
                        ->with_groups('fields: id, name', 'where: name = \'executive\'')
                        ->get_all();

                    $this->load->view('vendorCrm/executive_vendor_list', $this->data);
                } else {
                    $executive_id = $this->input->post('executive_id');

                    $this->data['title'] = 'Vendor List';
                    $this->data['nav_type'] = 'vendors';
                    $this->data['type'] = 'vendors';

                    $subscribed_vendors = $this->vendor_list_model->get_vendor_count($executive_id, 'subscribed');
                    $this->data['subscribed_vendor_count'] = $subscribed_vendors['count'];
                    $this->data['subscribed_vendor_list'] = $subscribed_vendors['vendor_details'];

                    $unsubscribed_vendors = $this->vendor_list_model->get_vendor_count($executive_id, 'unsubscribed');
                    $this->data['unsubscribed_vendor_count'] = $unsubscribed_vendors['count'];
                    $this->data['unsubscribed_vendor_list'] = $unsubscribed_vendors['vendor_details'];

                    $pending_vendors = $this->vendor_list_model->get_vendor_count($executive_id, 'pending');
                    $this->data['pending_vendor_count'] = $pending_vendors['count'];
                    $this->data['pending_vendor_list'] = $pending_vendors['vendor_details'];

                    $this->data['executives'] = $this->user_model->order_by('id', 'DESC')
                        ->fields('id, first_name, last_name, email, phone, unique_id, created_at, status')
                        ->with_groups('fields: id, name', 'where: name = \'executive\'')
                        ->get_all();

                    $this->load->view('vendorCrm/executive_vendor_list', $this->data);
                }
            } else {
                $this->data['title'] = 'Vendor List';
                $this->data['nav_type'] = 'vendors';
                $this->data['type'] = 'vendors';

                $subscribed_vendors = $this->vendor_list_model->get_executive_vendor_list('subscribed');
                $this->data['subscribed_vendor_count'] = $subscribed_vendors['count'];
                $this->data['subscribed_vendor_list'] = $subscribed_vendors['vendor_details'];

                $unsubscribed_vendors = $this->vendor_list_model->get_executive_vendor_list('unsubscribed');
                $this->data['unsubscribed_vendor_count'] = $unsubscribed_vendors['count'];
                $this->data['unsubscribed_vendor_list'] = $unsubscribed_vendors['vendor_details'];

                $pending_vendors = $this->vendor_list_model->get_executive_vendor_list('pending');
                $this->data['pending_vendor_count'] = $pending_vendors['count'];
                $this->data['pending_vendor_list'] = $pending_vendors['vendor_details'];

                $this->data['executives'] = $this->user_model->order_by('id', 'DESC')
                    ->fields('id, first_name, last_name, email, phone, unique_id, created_at, status')
                    ->with_groups('fields: id, name', 'where: name = \'executive\'')
                    ->get_all();

                $this->load->view('vendorCrm/executive_vendor_list', $this->data);
            }
        } else if ($type == 'users') {
            if ($status == 'submit') {

                $this->form_validation->set_rules('executive_id', 'Executive Name', 'required');
                if ($this->form_validation->run() == FALSE) {

                    $this->data['title'] = 'User List';
                    $this->data['nav_type'] = 'users';
                    $this->data['type'] = 'users';

                    $ordered_users = $this->user_model->get_executive_user_list('ordered');
                    $this->data['ordered_user_count'] = $ordered_users['count'];
                    $this->data['ordered_user_list'] = $ordered_users['user_details'];

                    $not_ordered_users = $this->user_model->get_executive_user_list('not_ordered');
                    $this->data['not_ordered_user_count'] = $not_ordered_users['count'];
                    $this->data['not_ordered_user_list'] = $not_ordered_users['user_details'];

                    $this->data['executives'] = $this->user_model->order_by('id', 'DESC')
                        ->fields('id, first_name, last_name, email, phone, unique_id, created_at, status')
                        ->with_groups('fields: id, name', 'where: name = \'executive\'')
                        ->get_all();

                    $this->load->view('vendorCrm/executive_users', $this->data);
                } else {
                    $executive_id = $this->input->post('executive_id');

                    $this->data['title'] = 'User List';
                    $this->data['nav_type'] = 'users';
                    $this->data['type'] = 'users';

                    $ordered_users = $this->user_model->get_executive_user_list('ordered', $executive_id);
                    $this->data['ordered_user_count'] = $ordered_users['count'];
                    $this->data['ordered_user_list'] = $ordered_users['user_details'];

                    $not_ordered_users = $this->user_model->get_executive_user_list('not_ordered', $executive_id);
                    $this->data['not_ordered_user_count'] = $not_ordered_users['count'];
                    $this->data['not_ordered_user_list'] = $not_ordered_users['user_details'];

                    $this->data['executives'] = $this->user_model->order_by('id', 'DESC')
                        ->fields('id, first_name, last_name, email, phone, unique_id, created_at, status')
                        ->with_groups('fields: id, name', 'where: name = \'executive\'')
                        ->get_all();

                    $this->load->view('vendorCrm/executive_users', $this->data);
                }
            } else {
                $this->data['title'] = 'User List';
                $this->data['nav_type'] = 'users';
                $this->data['type'] = 'users';

                $ordered_users = $this->user_model->get_executive_user_list('ordered');
                $this->data['ordered_user_count'] = $ordered_users['count'];
                $this->data['ordered_user_list'] = $ordered_users['user_details'];

                $not_ordered_users = $this->user_model->get_executive_user_list('not_ordered');
                $this->data['not_ordered_user_count'] = $not_ordered_users['count'];
                $this->data['not_ordered_user_list'] = $not_ordered_users['user_details'];

                $this->data['executives'] = $this->user_model->order_by('id', 'DESC')
                    ->fields('id, first_name, last_name, email, phone, unique_id, created_at, status')
                    ->with_groups('fields: id, name', 'where: name = \'executive\'')
                    ->get_all();

                $this->load->view('vendorCrm/executive_users', $this->data);
            }
        } else if ($type == 'delivery_captains') {
            if ($status == 'submit') {

                $this->form_validation->set_rules('executive_id', 'Executive Name', 'required');
                if ($this->form_validation->run() == FALSE) {

                    $this->data['title'] = 'Captain List';
                    $this->data['nav_type'] = 'delivery_captains';
                    $this->data['type'] = 'delivery_captains';

                    $target_achieved_captains = $this->executive_model->get_executive_delivery_captain_list('target_achieved');
                    $this->data['target_achieved_captain_count'] = $target_achieved_captains['count'];
                    $this->data['target_achieved_captains_list'] = $target_achieved_captains['captain_details'];

                    $target_not_achieved_captains = $this->executive_model->get_executive_delivery_captain_list('target_not_achieved');
                    $this->data['target_not_achieved_captain_count'] = $target_not_achieved_captains['count'];
                    $this->data['target_not_achieved_captains_list'] = $target_not_achieved_captains['captain_details'];

                    $pending_captains = $this->executive_model->get_executive_delivery_captain_list('pending');
                    $this->data['pending_captain_count'] = $pending_captains['count'];
                    $this->data['pending_captains_list'] = $pending_captains['captain_details'];


                    $this->data['executives'] = $this->user_model->order_by('id', 'DESC')
                        ->fields('id, first_name, last_name, email, phone, unique_id, created_at, status')
                        ->with_groups('fields: id, name', 'where: name = \'executive\'')
                        ->get_all();

                    $this->load->view('vendorCrm/executive_delivery_captains', $this->data);
                } else {
                    $executive_id = $this->input->post('executive_id');

                    $this->data['title'] = 'Captain List';
                    $this->data['nav_type'] = 'delivery_captains';
                    $this->data['type'] = 'delivery_captains';

                    $target_achieved_captains = $this->executive_model->get_executive_delivery_captain_list('target_achieved', $executive_id);
                    $this->data['target_achieved_captain_count'] = $target_achieved_captains['count'];
                    $this->data['target_achieved_captains_list'] = $target_achieved_captains['captain_details'];

                    $target_not_achieved_captains = $this->executive_model->get_executive_delivery_captain_list('target_not_achieved', $executive_id);
                    $this->data['target_not_achieved_captain_count'] = $target_not_achieved_captains['count'];
                    $this->data['target_not_achieved_captains_list'] = $target_not_achieved_captains['captain_details'];

                    $pending_captains = $this->executive_model->get_executive_delivery_captain_list('pending', $executive_id);
                    $this->data['pending_captain_count'] = $pending_captains['count'];
                    $this->data['pending_captains_list'] = $pending_captains['captain_details'];


                    $this->data['executives'] = $this->user_model->order_by('id', 'DESC')
                        ->fields('id, first_name, last_name, email, phone, unique_id, created_at, status')
                        ->with_groups('fields: id, name', 'where: name = \'executive\'')
                        ->get_all();

                    $this->load->view('vendorCrm/executive_delivery_captains', $this->data);
                }
            } else {
                $this->data['title'] = 'Captain List';
                $this->data['nav_type'] = 'delivery_captains';
                $this->data['type'] = 'delivery_captains';

                $target_achieved_captains = $this->executive_model->get_executive_delivery_captain_list('target_achieved');
                $this->data['target_achieved_captain_count'] = $target_achieved_captains['count'];
                $this->data['target_achieved_captains_list'] = $target_achieved_captains['captain_details'];

                $target_not_achieved_captains = $this->executive_model->get_executive_delivery_captain_list('target_not_achieved');
                $this->data['target_not_achieved_captain_count'] = $target_not_achieved_captains['count'];
                $this->data['target_not_achieved_captains_list'] = $target_not_achieved_captains['captain_details'];

                $pending_captains = $this->executive_model->get_executive_delivery_captain_list('pending');
                $this->data['pending_captain_count'] = $pending_captains['count'];
                $this->data['pending_captains_list'] = $pending_captains['captain_details'];


                $this->data['executives'] = $this->user_model->order_by('id', 'DESC')
                    ->fields('id, first_name, last_name, email, phone, unique_id, created_at, status')
                    ->with_groups('fields: id, name', 'where: name = \'executive\'')
                    ->get_all();

                $this->load->view('vendorCrm/executive_delivery_captains', $this->data);
            }
        } else if ($type == 'wallet') {
            if ($status == 'submit') {

                $this->form_validation->set_rules('executive_id', 'Executive Name', 'required');

                if ($this->form_validation->run() == FALSE) {
                    $this->data['title'] = 'Wallet List';
                    $this->data['nav_type'] = 'wallet';
                    $this->data['type'] = 'wallet';
                    $this->data['executives'] = $this->user_model->order_by('id', 'DESC')
                        ->fields('id, first_name, last_name, email, phone, unique_id, created_at, status')
                        ->with_executive_address('fields: executive_type_id', 'where: executive_type_id = 1')
                        ->with_groups('fields: id, name', 'where: name = \'executive\'')
                        ->get_all();

                    $wallet_details = $this->executive_model->get_wallet_details();


                    if ($wallet_details) {
                        $this->data['total_vendor_amount'] = isset($wallet_details['total_vendor_amount']) ? $wallet_details['total_vendor_amount'] : 0;
                        $this->data['total_user_amount'] = isset($wallet_details['total_user_amount']) ? $wallet_details['total_user_amount'] : 0;
                        $this->data['total_delivery_boy_amount'] = isset($wallet_details['total_delivery_boy_amount']) ? $wallet_details['total_delivery_boy_amount'] : 0;
                        $this->data['total_all_amount'] = isset($wallet_details['total_all_amount']) ? $wallet_details['total_all_amount'] : 0;
                    } else {
                        $this->data['total_vendor_amount'] = 0;
                        $this->data['total_user_amount'] = 0;
                        $this->data['total_delivery_boy_amount'] = 0;
                        $this->data['total_all_amount'] = 0;
                    }

                    $this->data['vendor_transaction_details'] = $this->executive_model->get_transaction_details('', 'vendor');
                    $this->data['user_transaction_details'] = $this->executive_model->get_transaction_details('', 'user');
                    $this->data['captain_transaction_details'] = $this->executive_model->get_transaction_details('', 'delivery_boy');

                    $this->load->view('vendorCrm/executive_wallet', $this->data);
                } else {
                    $this->data['title'] = 'Wallet List';
                    $this->data['nav_type'] = 'wallet';
                    $this->data['type'] = 'wallet';
                    $executive_id = $this->input->post('executive_id');

                    $this->data['executives'] = $this->user_model->order_by('id', 'DESC')
                        ->fields('id, first_name, last_name, email, phone, unique_id, created_at, status')
                        ->with_groups('fields: id, name', 'where: name = \'executive\'')
                        ->get_all();

                    $wallet_details = $this->executive_model->get_wallet_details($executive_id);


                    if ($wallet_details) {
                        $this->data['total_vendor_amount'] = isset($wallet_details['total_vendor_amount']) ? $wallet_details['total_vendor_amount'] : 0;
                        $this->data['total_user_amount'] = isset($wallet_details['total_user_amount']) ? $wallet_details['total_user_amount'] : 0;
                        $this->data['total_delivery_boy_amount'] = isset($wallet_details['total_delivery_boy_amount']) ? $wallet_details['total_delivery_boy_amount'] : 0;
                        $this->data['total_all_amount'] = isset($wallet_details['total_all_amount']) ? $wallet_details['total_all_amount'] : 0;
                    } else {
                        $this->data['total_vendor_amount'] = 0;
                        $this->data['total_user_amount'] = 0;
                        $this->data['total_delivery_boy_amount'] = 0;
                        $this->data['total_all_amount'] = 0;
                    }

                    $this->data['vendor_transaction_details'] = $this->executive_model->get_transaction_details($executive_id, 'vendor');
                    $this->data['user_transaction_details'] = $this->executive_model->get_transaction_details($executive_id, 'user');
                    $this->data['captain_transaction_details'] = $this->executive_model->get_transaction_details($executive_id, 'delivery_boy');

                    $this->load->view('vendorCrm/executive_wallet', $this->data);

                }
            } else {

                $this->data['title'] = 'Wallet List';
                $this->data['nav_type'] = 'wallet';
                $this->data['type'] = 'wallet';
                $this->data['executives'] = $this->user_model->order_by('id', 'DESC')
                    ->fields('id, first_name, last_name, email, phone, unique_id, created_at, status')
                    ->with_executive_address('fields: executive_type_id', 'where: executive_type_id = 1')
                    ->with_groups('fields: id, name', 'where: name = \'executive\'')
                    ->get_all();

                $wallet_details = $this->executive_model->get_wallet_details();


                if ($wallet_details) {
                    $this->data['total_vendor_amount'] = isset($wallet_details['total_vendor_amount']) ? $wallet_details['total_vendor_amount'] : 0;
                    $this->data['total_user_amount'] = isset($wallet_details['total_user_amount']) ? $wallet_details['total_user_amount'] : 0;
                    $this->data['total_delivery_boy_amount'] = isset($wallet_details['total_delivery_boy_amount']) ? $wallet_details['total_delivery_boy_amount'] : 0;
                    $this->data['total_all_amount'] = isset($wallet_details['total_all_amount']) ? $wallet_details['total_all_amount'] : 0;
                } else {
                    $this->data['total_vendor_amount'] = 0;
                    $this->data['total_user_amount'] = 0;
                    $this->data['total_delivery_boy_amount'] = 0;
                    $this->data['total_all_amount'] = 0;
                }

                $this->data['vendor_transaction_details'] = $this->executive_model->get_transaction_details('', 'vendor');
                $this->data['user_transaction_details'] = $this->executive_model->get_transaction_details('', 'user');
                $this->data['captain_transaction_details'] = $this->executive_model->get_transaction_details('', 'delivery_boy');

                $this->load->view('vendorCrm/executive_wallet', $this->data);
            }
        }
    }

    public function vendors($type = 'all')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('vendor_list'))
         * redirect('admin');
         */
        if ($type == 'all') {
            $this->data['title'] = 'All Vendors';
            $this->data['type'] = 'all';
            $this->data['nav_type'] = 'executives';
            $this->data['categories'] = $this->category_model->get_all();
            $this->data['executive'] = $this->user_model->get_all();
            $this->data['constituency'] = $this->constituency_model->get_all();
            $this->data['vendors'] = $this->vendor_list_model->order_by('id', 'DESC')
                ->with_location('fields:id, address')
                ->with_trashed()
                ->get_all();
            $this->load->view('vendorCrm/vendor_list', $this->data);
        } elseif ($type == 'approved') {
            $this->data['title'] = 'Approved Vendors';
            $this->data['content'] = 'master/vendor_list';
            $this->data['type'] = 'approved';
            $this->data['categories'] = $this->category_model->get_all();
            $this->data['vendors'] = $this->vendor_list_model->order_by('id', 'DESC')
                ->with_location('fields:id, address')
                ->where([
                    'status' => 1
                ])
                ->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'pending') {
            $this->data['title'] = 'Pending Vendors';
            $this->data['content'] = 'master/vendor_list';
            $this->data['type'] = 'pending';
            $this->data['categories'] = $this->category_model->get_all();
            $this->data['vendors'] = $this->vendor_list_model->order_by('id', 'DESC')
                ->with_location('fields:id, address')
                ->where([
                    'status' => 2
                ])
                ->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'vendor') {
            if (!empty($_GET['vendor_id'])) {
                $this->data['title'] = 'Vendor Details';
                $this->data['content'] = 'master/vendor_view';
                $this->data['type'] = 'vendor_view';
                $this->data['vendor_list'] = $this->vendor_list_model->with_location('fields: id, address, latitude, longitude')
                    ->with_category('fields: id, name')
                    ->with_users('fields: phone')
                    ->with_constituency('fields: id, name, state_id, district_id')
                    ->with_contacts('fields: id, std_code, number, type')
                    ->with_links('fields: id,   url, type')
                    ->with_amenities('fields: id, name')
                    ->with_services('fields: id, name')
                    ->with_holidays('fields: id')
                    ->with_executive('fields:id,unique_id')
                    ->where('id', $_GET['vendor_id'])
                    ->get();
                $this->_render_page($this->template, $this->data);
            }
        } elseif ($type == 'd') {
            $this->vendor_list_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->db->where('id', $this->input->post('id'));
            echo $this->db->update('vendors_list', [
                'status' => 0
            ]);
        } elseif ($type == 'cancelled') {
            $this->data['title'] = 'Cancelled Vendors';
            $this->data['content'] = 'master/vendor_list';
            $this->data['type'] = 'cancelled';
            $this->data['categories'] = $this->category_model->get_all();
            $this->data['vendors'] = $this->vendor_list_model->order_by('id', 'DESC')
                ->with_location('fields:id, address')
                ->only_trashed()
                ->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'change_status') {
            $vendorObj = $this->vendor_list_model->where([
                'id' => $this->input->post('vendor_id')
            ])->get();
            $vendor_data = $this->user_model->where([
                'id' => $vendorObj['vendor_user_id']
            ])->get();
            if ($vendorObj['is_vendor_approved_email_sent'] == 0 && $this->input->post('is_checked') == 'true') {
                $data = array(
                    'vendor_name' => $vendorObj['name']
                );
                $message = $this->load->view('vendor_approval_tem', $data, true);
                $this->email->clear();
                $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
                $this->email->to($vendor_data['email']);
                $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - Approval Mail');
                $this->email->message($message);
                $this->email->send();

                $this->email->send();
                $this->vendor_list_model->update([
                    'status' => ($this->input->post('is_checked') == 'true') ? 1 : 2,
                    'is_vendor_approved_email_sent' => 1
                ], $this->input->post('vendor_id'));
            } else {
                $this->vendor_list_model->update([
                    'status' => ($this->input->post('is_checked') == 'true') ? 1 : 2
                ], $this->input->post('vendor_id'));
            }

            $exe = $this->vendor_list_model->with_executive('fields: id, wallet')
                ->where('id', $this->input->post('vendor_id'))
                ->as_array()
                ->get();
            $userAccount = $this->user_account_model->where([
                'user_id' => $exe['executive']['id']
            ])->get();

            if ($_POST['is_checked'] == 'true') {
                // $this->send_sms('\'Congratulations! , now you are a member to the Nextclick Family Login ID : ' . $exe['unique_id'] . '. Regards, NEXTCLICK.\'', $mobile);
            }

            if ($_POST['is_checked'] == 'true') {
                if (!empty($exe['executive']['id'])) {
                    $wallet_type = 'wallet';
                    $user = $this->user_model->with_executive_address('fields: executive_type_id')->where('id', $exe['executive']['id'])->get();
                    if (isset($user['executive_address']) && $user['executive_address']['executive_type_id'] == 1) { // Wallet update only for Freelancer
                        $this->user_account_model->update([
                            'wallet' => ($this->input->post('is_checked') == 'true') ? $userAccount['wallet'] + floatval($this->setting_model->where('key', 'pay_per_vendor')
                                ->get()['value']) : $userAccount['wallet']
                        ], ['user_id' => $exe['executive']['id']]);
                        $id = $this->wallet_transaction_model->insert([
                            'account_user_id' => $exe['executive']['id'],
                            'created_user_id' => !empty($this->ion_auth->get_user_id()) ? $this->ion_auth->get_user_id() : $exe['executive']['id'],
                            'amount' => floatval($this->setting_model->where('key', 'pay_per_vendor')->get()['value']),
                            'balance' => (floatval($userAccount['wallet'])) + (floatval($this->setting_model->where('key', 'pay_per_vendor')->get()['value'])),
                            'txn_id' => 'NC-' . generate_trasaction_no(),
                            'ecom_order_id' => NULL,
                            'type' => 'CREDIT',
                            'message' => NULL,
                            'status' => 1
                        ]);
                    }
                }
                $this->user_group_model->approveGroup($vendorObj['vendor_user_id'], 'vendor');
                echo json_encode($exe);
            } else {
                $this->user_group_model->disApproveGroup($vendorObj['vendor_user_id'], 'vendor');
            }
        } elseif ($type == 'cover_update') {
            $user_id = $this->input->post('id');
            if ($_FILES['cover']['name'] !== '') {
                move_uploaded_file($_FILES['cover']['tmp_name'], "./uploads/list_cover_image/list_cover_$user_id.jpg");
            }
            redirect('vendors/vendor?vendor_id=' . $user_id);
        }
    }

    public function employee($type = 'r', $rowno = 0)
    {
        if ($type == 'c') {
            $this->form_validation->set_rules($this->user_model->rules['creation']);
            if ($this->form_validation->run() == false) {
                $this->data['title'] = 'Add employee';
                $this->data['nav_type'] = 'executives';
                $this->data['groups'] = $this->group_model->order_by('id', 'DESC')->get_all();
                $this->load->view('vendorCrm/add_employee', $this->data);
            } else {
                $email = strtolower($this->input->post('email'));
                $identity = ($this->config->item('identity', 'ion_auth') === 'email') ? $email : $this->input->post('identity');
                $password = $this->input->post('password');
                $role_ids = $this->input->post('role');
                $groups = [];
                foreach ($role_ids as $id) {
                    array_push($groups, $this->group_model->where('id', $id)->get());
                }
                $groupname = $groups[0]['name'];
                $additional_data = array(
                    'primary_intent' => $groupname,
                    'email' => $this->input->post('email'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone' => $this->input->post('phone'),
                    'active' => 1
                );

                foreach ($groups as $group) {
                    if (min(array_column($groups, 'priority')) == $group['priority']) {
                        $additional_data['unique_id'] = generate_serial_no($group['code'], 4, $group['last_id']);
                        $this->group_model->update([
                            'last_id' => $group['last_id'] + 1
                        ], $group['id']);
                    }
                }

                $this->ion_auth->register($identity, $password, $email, $additional_data, $groupname);
                redirect("add_vehicle/r/0", 'refresh');
            }
        } elseif ($type == 'r') {

            $this->data['title'] = 'List of Users';
            $this->data['nav_type'] = 'executives';
            // Search text
            $search_text = $unique_id = "";

            //$group = 1;
            //$noofrows = 1;
            if (isset($_POST['submit'])) {
                $search_text = $this->input->post('q');
                $unique_id = $this->input->post('unique_id');
                $group = $this->input->post('group');
                $noofrows = $this->input->post('noofrows');
                $this->session->set_userdata(
                    array(
                        "q" => $search_text,
                        'unique_id' => $unique_id,
                        'group' => $group,
                        'noofrows' => $noofrows
                    )
                );
            } else {
                if ($this->session->userdata('q') != NULL || $this->session->userdata('unique_id') != NULL || $this->session->userdata('group') != NULL || $this->session->userdata('noofrows') != NULL) {
                    $search_text = $this->session->userdata('q');
                    $unique_id = $this->session->userdata('unique_id');
                    $group = $this->session->userdata('group');
                    $noofrows = $this->session->userdata('noofrows');
                }
            }

            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }

            $allcount = $this->user_model->users_count($group, $search_text, $unique_id);
            $users_record = $this->user_model->get_users($rowperpage, $rowno, $group, $search_text, $unique_id);

            $config['full_tag_open'] = "<ul class='pagination'>";
            $config['full_tag_close'] = "</ul>";
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
            $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tagl_close'] = "</li>";
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tagl_close'] = "</li>";
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tagl_close'] = "</li>";
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tagl_close'] = "</li>";
            $config['base_url'] = base_url() . 'add_vehicle/r';
            $config['first_url'] = base_url() . 'add_vehicle/r/0';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $rowperpage;

            // Initialize
            $this->pagination->initialize($config);
            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['users'] = $users_record;
            foreach ($this->data['users'] as $key => $user) {

                $this->db->select("b.name , b.description , a.id");
                $this->db->from("users_groups as a");
                $this->db->join("groups as b", "b.id = a.group_id");
                $this->db->where("a.user_id", $user['id']);
                $this->db->order_by("b.priority", "ASC");

                $result = $this->db->get();
                $this->data['users'][$key]['groups'] = $result->result_array();

                /* $this->data['users'][$key]['groups'] = $this->db->query("SELECT groups.id, name, description FROM `users_groups` JOIN groups on groups.id = users_groups.group_id WHERE users_groups.user_id = ".$user['id']." ORDER BY groups.priority ASC")->result_array(); */
            }

            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['unique_id'] = $unique_id;
            $this->data['group'] = $group;
            $this->data['noofrows'] = $rowperpage;
            // print_array($this->data['users']);
            $this->data['groups'] = $this->group_model->order_by('id', 'DESC')->get_all();
            $this->load->view('vendorCrm/employee', $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->user_model->rules['update']);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {

                $this->user_model->update([
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'permanent_address' => $this->input->post('permanent_address'),
                    'aadhar_number' => $this->input->post('aadhar_number')
                ], $this->input->post('id'));
                // Update the groups user belongs to
                if ($_FILES['aadhar_card_front']['name'] !== '') {
                    $path = $_FILES['aadhar_card_front']['name'];
                    if (!file_exists('uploads/' . 'aadhar_card' . '_image/')) {
                        mkdir('uploads/' . 'aadhar_card' . '_image/', 0777, true);
                    }
                    if (file_exists('uploads/' . 'aadhar_card' . '_image/' . 'aadhar_card_front' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'aadhar_card' . '_image/' . 'aadhar_card_front' . '_' . $this->input->post('id') . '.jpg');
                    }
                    move_uploaded_file($_FILES['aadhar_card_front']['tmp_name'], 'uploads/' . 'aadhar_card' . '_image/' . 'aadhar_card_front' . '_' . $this->input->post('id') . '.jpg');
                }
                if ($_FILES['aadhar_card_back']['name'] !== '') {
                    $path = $_FILES['aadhar_card_back']['name'];
                    if (!file_exists('uploads/' . 'aadhar_card' . '_image/')) {
                        mkdir('uploads/' . 'aadhar_card' . '_image/', 0777, true);
                    }
                    if (file_exists('uploads/' . 'aadhar_card' . '_image/' . 'aadhar_card_back' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'aadhar_card' . '_image/' . 'aadhar_card_back' . '_' . $this->input->post('id') . '.jpg');
                    }
                    move_uploaded_file($_FILES['aadhar_card_back']['tmp_name'], 'uploads/' . 'aadhar_card' . '_image/' . 'aadhar_card_back' . '_' . $this->input->post('id') . '.jpg');
                }
                if ($_FILES['bank_passbook_image']['name'] !== '') {
                    $path = $_FILES['bank_passbook_image']['name'];
                    if (!file_exists('uploads/' . 'bank_passbook' . '_image/')) {
                        mkdir('uploads/' . 'bank_passbook' . '_image/', 0777, true);
                    }
                    if (file_exists('uploads/' . 'bank_passbook' . '_image/' . 'bank_passbook' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'bank_passbook' . '_image/' . 'bank_passbook' . '_' . $this->input->post('id') . '.jpg');
                    }
                    move_uploaded_file($_FILES['bank_passbook_image']['tmp_name'], 'uploads/' . 'bank_passbook' . '_image/' . 'bank_passbook' . '_' . $this->input->post('id') . '.jpg');
                }

                $groupData = $this->input->post('role');
                if (isset($groupData) && !empty($groupData)) {
                    $this->ion_auth->remove_from_group('', $this->input->post('id'));
                    foreach ($groupData as $grp) {
                        $this->ion_auth->add_to_group($grp, $this->input->post('id'));
                    }
                }
                redirect("employee/r/0", 'refresh');
            }
        } elseif ($type == 'd') {
            $this->user_model->update([
                'active' => 0
            ], $this->input->post('id'));
            echo $this->user_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'employee';
            $this->data['content'] = 'emp/edit';
            $this->data['nav_type'] = 'employee';
            $this->data['type'] = 'user';
            $this->data['users'] = $this->user_model->with_groups('fields: name, id')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->data['locations'] = $this->location_model->where('id', $this->data['users']['location_id'])->get();
            $this->data['groups'] = $this->group_model->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'eye') {
            $this->data['title'] = 'employee';
            $this->data['content'] = 'emp/emp_eye';
            $this->data['nav_type'] = 'employee';
            $this->data['type'] = 'user';
            $this->data['users'] = $this->user_model->with_groups('fields: name, id')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->data['tc'] = $this->termsconditions_model->users_tc($this->input->get('id'));
            $this->_render_page($this->template, $this->data);
        }
    }
}
