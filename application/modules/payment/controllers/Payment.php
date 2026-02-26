<?php

class Payment extends MY_Controller
{

    public function __construct()
    {
        error_reporting(E_ERROR | E_PARSE);
        parent::__construct();
        $this->load->library('pagination');
        $this->template = 'template/admin/main';
        $this->load->model('vendor_list_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('user_model');
        $this->load->model('user_account_model');
        $this->load->helper('common');
    }

    public function wallet_transactions($type = 'list', $rowno = 0)
    {
        if ($type == 'list') {
            $this->data['title'] = 'Transactions List';
            $this->data['content'] = 'payment/list';
            $this->data['nav_type'] = 'Transactions';
            if ($this->input->post('submit') != NULL) {
                $where = ' where true and wt.status = "1" ';
                if ($this->input->post('start_date') != NULL) {
                    $start_date = Date($this->input->post('start_date'));
                    $end_date = Date($this->input->post('end_date'));
                    $where .= " and  DATE(wt.created_at) BETWEEN '$start_date' and '$end_date' ";
                }
                if ($this->input->post('type') != NULL) {
                    $searchtext = $this->input->post('type');
                    $where .= " and  wt.type LIKE '$searchtext' ";
                }
                $this->data['delivery_boy_transactions'] = $this->db->query("SELECT wt.*, u.first_name, eo.track_id FROM delivery_boy_ecom_earnings_view AS wt JOIN users AS u ON wt.account_user_id  = u.id LEFT JOIN ecom_orders AS eo ON wt.ecom_order_id  = eo.id " . $where . " ORDER BY wt.created_at DESC ")->result_array();
            } else {
                $this->data['delivery_boy_transactions'] = $this->db->query("SELECT wt.*, u.first_name, eo.track_id FROM delivery_boy_ecom_earnings_view AS wt JOIN users AS u ON wt.account_user_id  = u.id LEFT JOIN ecom_orders AS eo ON wt.ecom_order_id  = eo.id WHERE wt.status = '1' ORDER BY wt.created_at DESC")->result_array();
            }
            if ($this->input->post('v_submit') != NULL) {
                $where = ' where true and wt.status = "1" ';
                if ($this->input->post('v_start_date') != NULL) {
                    $start_date = Date($this->input->post('v_start_date'));
                    $end_date = Date($this->input->post('v_end_date'));
                    $where .= " and  DATE(wt.created_at) BETWEEN '$start_date' and '$end_date' ";
                }
                if ($this->input->post('v_type') != NULL) {
                    $searchtext = $this->input->post('v_type');
                    $where .= " and  wt.type LIKE '$searchtext' ";
                }
                $this->data['vendor_transactions'] = $this->db->query("SELECT wt.*, u.first_name, eo.track_id FROM vendor_earnings_view AS wt JOIN users AS u ON wt.account_user_id  = u.id LEFT JOIN ecom_orders AS eo ON wt.ecom_order_id  = eo.id " . $where . " ORDER BY wt.created_at DESC")->result_array();
            } else {
                $this->data['vendor_transactions'] = $this->db->query("SELECT wt.*, u.first_name, eo.track_id FROM vendor_earnings_view AS wt JOIN users AS u ON wt.account_user_id  = u.id LEFT JOIN ecom_orders AS eo ON wt.ecom_order_id  = eo.id WHERE wt.status = '1' ORDER BY wt.created_at DESC")->result_array();
            }
            // $this->data['transactions'] = $this->db->query("SELECT wt.*, u.first_name FROM wallet_transactions AS wt JOIN users AS u ON wt.account_user_id  = u.id WHERE wt.status = '1' ORDER BY wt.created_at DESC")->result_array();
            // $this->data['delivery_boy_transactions'] = $this->db->query("SELECT wt.*, u.first_name, eo.track_id FROM delivery_boy_ecom_earnings_view AS wt JOIN users AS u ON wt.account_user_id  = u.id LEFT JOIN ecom_orders AS eo ON wt.ecom_order_id  = eo.id WHERE wt.status = '1' ORDER BY wt.created_at DESC")->result_array();
            // $this->data['vendor_transactions'] = $this->db->query("SELECT wt.*, u.first_name, eo.track_id FROM vendor_earnings_view AS wt JOIN users AS u ON wt.account_user_id  = u.id LEFT JOIN ecom_orders AS eo ON wt.ecom_order_id  = eo.id WHERE wt.status = '1' ORDER BY wt.created_at DESC")->result_array();
            $this->data['delivery_boy_floatings'] = $this->db->query("SELECT wt.*, u.first_name, eo.track_id FROM wallet_transactions AS wt JOIN users AS u ON wt.account_user_id  = u.id LEFT JOIN ecom_orders AS eo ON wt.ecom_order_id  = eo.id WHERE wt.status = '2' ORDER BY wt.created_at DESC")->result_array();
            $delivery_boy_wallets_sql = "SELECT
                                            u.id
                                            ,u.first_name
                                            ,u.last_name
                                            ,u.phone
                                            ,ua.delivery_boy_earning_wallet
                                            ,ua.floating_wallet
                                        FROM `delivery_boy_address` dba
                                        join users u on u.id = dba.user_id
                                        join user_accounts ua on ua.user_id = u.id";
            $vendor_wallets_sql = "SELECT
                                    u.id
                                    ,u.first_name
                                    ,u.last_name
                                    ,u.phone
                                    ,ua.vendor_earning_wallet
                                    ,vl.business_name
                                    FROM vendors_list vl
                                    join users u on u.id = vl.vendor_user_id 
                                    join user_accounts ua on ua.user_id = u.id";
            $this->data['delivery_boy_wallets'] = $this->db->query($delivery_boy_wallets_sql)->result_array();
            $this->data['vendor_wallets'] = $this->db->query($vendor_wallets_sql)->result_array();
            $this->_render_page($this->template, $this->data);
        } else if ($type == 'c') {

            $this->data['title'] = 'Transactions List';
            $this->data['content'] = 'payment/create_transations';
            $this->data['nav_type'] = 'Create Transactions';
            $this->data['user'] = $this->db->query("SELECT * from users WHERE status = '1' order by 'DESC'")->result_array();
            $this->_render_page($this->template, $this->data);
        } else if ($type == 'st') {

            $uid = $this->input->post('userid');
            $tr = $this->db->query("SELECT ua.`wallet`, ua. `floating_wallet`,ua.delivery_boy_earning_wallet,ua.vendor_earning_wallet,u.id, u.email,u.first_name,u.phone FROM user_accounts AS ua JOIN users AS u ON ua.user_id = u.id WHERE u.id=$uid order by ua.id desc limit 1;")->row();
            //print_r($tr);exit;
            echo json_encode($tr);
        } else if ($type == 'srh') {

            $searchText = $this->input->post('search');
            $this->data['user'] = $this->db->query("SELECT phone,id FROM users where phone like '%" . $searchText . "%' limit 5")->result_array();
            foreach ($this->data['user'] as $a) {
                $phone = $a['phone'];
                $id = $a['id'];
                $search_arr[] = array(
                    "id" => $id,
                    "phone" => $phone
                );
            }
            echo json_encode($search_arr);
        } else if ($type == 'e') {

            $user_id = $this->input->post('id');
            $amount = $this->input->post('amount');
            // $type1 = 'DEBIT'; // $this->input->post('modetype');
            $message = $this->input->post('message');
            $walletamount = $this->input->post('walletamount');
            $earning_type = $this->input->post('earning_type');

            if ($earning_type == "User Earnings") {
                $account_user_role_as = "user";
            } else if ($earning_type == "Vendor Earnings") {
                $account_user_role_as = "vendor";
            } else if ($earning_type == "Delivery Boy Earnings") {
                $account_user_role_as = "delivery_boy";
            }

            if ($amount > 0) {
                $txn_id = 'NC-' . generate_trasaction_no();
                // $tr = $this->user_model->payment_update($user_id, $amount, $type1, $wallet_type = 'wallet', $txn_id, $order_id = NULL, $message);
                $tr = $this->wallet_transaction_model->insert([
                    'account_user_id' => $user_id,
                    'created_user_id' => !empty($this->ion_auth->get_user_id()) ? $this->ion_auth->get_user_id() : null,
                    'amount' => floatval($amount),
                    'txn_id' => $txn_id,
                    'type' => 'DEBIT',
                    'message' => $message,
                    'status' => 1,
                    'account_user_role_as' => $account_user_role_as
                ]);
                if ($tr) {
                    if ($earning_type == "Delivery Boy Earnings") {
                        $this->user_model->updateDeliveryBoyEarningWallet($user_id);
                    } else if ($earning_type == "Vendor Earnings") {
                        $this->user_model->updateVendorEarningWallet($user_id);
                    }
                }
            } else {
                $this->session->set_flashdata('error', 'Cannot Add below 0 amount');
                redirect('payment/wallet_transactions/list/0');
            }
            /*
             * $user_id = $this->session->userdata('user_id');
             * if($walletamount > 0)
             * {
             * $tr = $this->user_model->payment_update($user_id, $amount, $type1, $wallet_type = 'wallet', $txn_id = 'L200453212', $order_id = NULL,$message);
             * }
             */
            if ($tr) {
                $this->session->set_flashdata('error', 'Successfully Added');
                redirect('payment/wallet_transactions/list/0');
            } else {
                redirect('payment/wallet_transactions/c/0');
            }
        } else if ($type == 'refund') {

            $id = base64_decode(base64_decode($this->input->get('id')));

            $this->data['title'] = 'Transactions List';
            $this->data['content'] = 'payment/create_transations';
            $this->data['nav_type'] = 'Create Transactions';
            $this->data['refnd'] = 'refunds';
            $this->data['dt'] = $this->db->query("SELECT * FROM ecom_orders WHERE id = '$id'")->result_array();
            $uid = $this->data['dt'][0]['created_user_id'];
            $this->data['user'] = $this->db->query("SELECT * FROM users WHERE id = '$uid' AND status = '1'")->result_array();
            $this->_render_page($this->template, $this->data);
        }
    }

    public function wallet_refunds($type = 'list', $rowno = 0)
    {
        if ($type == 'list') {

            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }

            $this->data['title'] = 'Wallet Refunds';
            $this->data['content'] = 'payment/wallet_refunds';
            $this->data['nav_type'] = 'Wallet Refunds';

            $allcount = $this->db->query("SELECT eo.track_id,eo.total, u.username,u.unique_id,u.first_name,u.last_name,u.email,u.phone FROM ecom_orders AS eo
           JOIN users AS u ON eo.created_user_id  = u.id WHERE eo.order_status_id = '17'")->num_rows();

            $this->data['transactions'] = $this->db->query("SELECT eo.track_id,eo.total, u.username,u.unique_id,u.first_name,u.last_name,u.email,u.phone FROM ecom_orders AS eo
           JOIN users AS u ON eo.created_user_id  = u.id WHERE eo.order_status_id = '17'")->result_array();

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
            $config['base_url'] = base_url() . 'payment/payment/wallet_transactions/list';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $rowperpage;
            $this->pagination->initialize($config);
            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['row'] = $rowno;
            $this->data['noofrows'] = $rowperpage;
            $this->_render_page($this->template, $this->data);
        }
    }

    public function vendor_gst_reports($rowno = 0)
    {
        $this->data['title'] = 'Transactions List';
        $this->data['content'] = 'payment/vendor_gst_report';
        $this->data['nav_type'] = 'vendor_gst_reports';
        $this->data['vendor_reports'] = $this->db->query("SELECT vl.vendor_user_id,u.first_name, vl.business_name, sum(eod.tax) total_tax FROM ecom_order_details eod 
        join ecom_orders eo on eo.id = eod.ecom_order_id
        join vendors_list vl on vl.vendor_user_id = eo.vendor_user_id
        left join users u on u.id = eo.vendor_user_id 
        group by vl.vendor_user_id order by vl.business_name;")->result_array();
        $this->_render_page($this->template, $this->data);
    }

    public function delivery_boy_gst_reports($rowno = 0)
    {
        $this->data['title'] = 'Transactions List';
        $this->data['content'] = 'payment/delivery_boy_gst_report';
        $this->data['nav_type'] = 'delivery_boy_gst_reports';
        $sql = "SELECT 
                u.id
                ,u.first_name
                ,u.last_name
                ,u.phone
                ,total_gst_amount
                ,delivery_boy_user_id
                from (
                    SELECT 
                        dj.delivery_boy_user_id    
                        ,sum((eo.delivery_fee - round((`eo`.`delivery_fee`/(1+(`eo`.`delivery_gst_percentage`/100))),2))) as total_gst_amount    
                    FROM `ecom_orders` eo 
                    join delivery_jobs dj on eo.id = dj.ecom_order_id
                    join ecom_payments as ep on ep.id = eo.payment_id  
                    join users u on u.id = dj.delivery_boy_user_id
                    where true 
                    and delivery_fee > 0
                    and  dj.status = 508
                    group by dj.delivery_boy_user_id
                ) temp
                join users u on u.id = temp.delivery_boy_user_id";
        $this->data['reports'] = $this->db->query($sql)->result_array();
        $this->_render_page($this->template, $this->data);
    }

    public function vendor_reports($rowno = 0)
    {
        $this->data['title'] = 'Transactions List';
        $this->data['content'] = 'payment/vendor_reports';
        $this->data['nav_type'] = 'vendor_gst_reports';
        $stu_id = $this->input->get('id');
        $this->data['reports'] = $this->db->query("SELECT vl.vendor_user_id,vl.gst_number,ft.name, eo.track_id,eo.id o_id, eo.created_at, u.first_name, vl.business_name, eod.tax total_tax FROM ecom_order_details eod 
        join ecom_orders eo on eo.id = eod.ecom_order_id
        join vendors_list vl on vl.vendor_user_id = eo.vendor_user_id
        left join vendor_product_variants vp on vp.vendor_user_id = eo.vendor_user_id 
        left join food_item ft on ft.id = vp.item_id 
        left join users u on u.id = eo.vendor_user_id WHERE vl.vendor_user_id = $stu_id and eod.tax != 0 order by eo.created_at DESC")->result_array();
        $this->_render_page($this->template, $this->data);
    }

    public function delivery_boy_wise_gst_report($id)
    {
        $this->data['title'] = 'Transactions List';
        $this->data['content'] = 'payment/delivery_boy_wise_gst_report';
        $this->data['nav_type'] = 'delivery_boy_gst_reports';
        $sql = "SELECT 
                    dj.delivery_boy_user_id
                    ,eo.delivery_fee
                    ,round((`eo`.`delivery_fee`/(1+(`eo`.`delivery_gst_percentage`/100))),2) as without_gst_amount
                    ,(eo.delivery_fee - round((`eo`.`delivery_fee`/(1+(`eo`.`delivery_gst_percentage`/100))),2)) as gst_amount
                    ,track_id
                    ,eo.id ecom_order_id
                    ,eo.updated_at delivered_at
                    ,u.first_name
                    ,delivery_gst_percentage
                FROM `ecom_orders` eo 
                join delivery_jobs dj on eo.id = dj.ecom_order_id
                join ecom_payments as ep on ep.id = eo.payment_id  
                join users u on u.id = dj.delivery_boy_user_id
                where true 
                and delivery_fee > 0
                and  dj.status = 508 and dj.delivery_boy_user_id=" . $id . " order by eo.updated_at desc";

        $this->data['reports'] = $this->db->query($sql)->result_array();
        $this->_render_page($this->template, $this->data);
    }

    public function admin_wallet_reports($rowno = 0)
    {
        $noofrows = $this->filter_config();

        $rowperpage = $noofrows ? $noofrows : 10;
        if ($rowno != 0) {
            $rowno = ($rowno - 1) * $rowperpage;
        }
        $user_id = $this->config->item('super_admin_user_id', 'ion_auth');
        $allcount = $this->wallet_transaction_model->all($rowperpage, $rowno, $user_id, $this->data['start_date'], $this->data['end_date'], NULL, NULL, NULL, NULL, TRUE);
        $this->data['wallet_details'] = $this->user_account_model->where('user_id', $user_id)->get();
        $this->data['transactions'] = $this->wallet_transaction_model->all($rowperpage, $rowno, $this->config->item('super_admin_user_id', 'ion_auth'), $this->data['start_date'], $this->data['end_date'], NULL, NULL, NULL, NULL, FALSE);

        if ($this->data['transactions']) {
            foreach ($this->data['transactions'] as $key => $txn) {
                $this->data['transactions'][$key]['user_account'] = $this->user_model->fields('id, display_name, phone, first_name')
                    ->where('id', $txn['account_user_id'])
                    ->get();
            }
        } else {
            $this->data['transactions'] = [];
        }
        $url = base_url() . 'payment/admin_wallet_reports';
        $this->pagination_config($allcount, $rowperpage, $url);

        $this->data['title'] = 'Admin wallet reports';
        $this->data['content'] = 'admin_wallet_reports';
        $this->data['nav_type'] = 'payment_reports';
        // print_array($this->data);
        $this->_render_page($this->template, $this->data);
    }

    public function admin_earnings_wallet()
    {
    }

    public function admin_floating_wallet()
    {
    }

    public function admin_income_wallet()
    {
    }

    public function filter_config()
    {
        $search_text = "";
        $noofrows = 10;
        if ($this->input->post('submit') != NULL) {
            $search_text = $this->input->post('q');
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $noofrows = $this->input->post('noofrows');
            $this->session->set_flashdata(
                array(
                    "q" => $search_text,
                    'noofrows' => $noofrows,
                    'start_date' => $start_date,
                    'end_date' => $end_date
                )
            );
        } else {
            if ($this->session->flashdata('q') != NULL || $noofrows != NULL || $this->session->flashdata('start_date') != NULL || $this->session->flashdata('end_date') != NULL) {
                $search_text = $this->session->flashdata('q');
                $noofrows = $this->session->flashdata('noofrows');
                $start_date = $this->session->flashdata('start_date');
                $end_date = $this->session->flashdata('end_date');
            }
        }

        $this->data['q'] = $search_text;
        $this->data['noofrows'] = $noofrows;
        $this->data['start_date'] = $start_date;
        $this->data['end_date'] = $end_date;
        return $noofrows;
    }

    public function pagination_config($allcount, $rowperpage, $url)
    {
        $rowperpage = $noofrows ? $noofrows : 10;
        if ($rowno != 0) {
            $rowno = ($rowno - 1) * $rowperpage;
        }

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
        $config['base_url'] = $url;
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $allcount;
        $config['per_page'] = $rowperpage;
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
    }
}
