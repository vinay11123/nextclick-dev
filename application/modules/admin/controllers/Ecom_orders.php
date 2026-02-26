<?php
error_reporting(E_ERROR | E_PARSE);
class Ecom_orders extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template = 'template/admin/main';
        $this->load->library('user_agent');
        if (!$this->ion_auth->logged_in())
            redirect('auth/login');

        $this->load->model('ecom_order_model');
        $this->load->model('ecom_order_deatils_model');
        $this->load->model('delivery_job_model');
        $this->load->model('delivery_job_rejection_model');
        $this->load->model('notification_type_model');
    }
    
    public function delivery_job_rejection_requests(){
        $this->data['title'] = 'Rejected orders';
        $this->data['content'] = 'admin/ecom_orders/rejected_orders_list';
        $this->data['nav_type'] = 'rejected_order_veiw';
        $this->data['rejected_orders'] = $this->delivery_job_rejection_model
        ->with_delivery_job('fields: id, job_id, ecom_order_id')
        ->with_delivery_boy('fields: id, phone, email, display_name')
        ->group_by('job_id')
        ->order_by('id', 'DESC')
        ->get_all();
        if(! empty($this->data['rejected_orders'])){foreach ($this->data['rejected_orders'] as $key => $order){
            $this->data['rejected_orders'][$key]['order_details'] = $this->ecom_order_model
            ->fields('track_id, shipping_address_id, delivery_fee, payment_id, vendor_user_id, created_at')
            ->with_shipping_address('fields: id, state_id, district_id, constituency_id, phone, email, name, landmark, pincode, address, location_id, created_user_id, updated_user_id, created_at, updated_at, deleted_at, status')
            ->with_vendor('fields: id, whats_app_no, secondary_contact, name, vendor_user_id')
            ->where('id', $order['delivery_job']['ecom_order_id'])
            ->get();
        }}else {
            $this->data['rejected_orders'] = [];
        }
        $this->_render_page($this->template, $this->data);
    }

    public function delivery_job_accept_requests(){
        $this->data['title'] = 'Accepted orders';
        $this->data['content'] = 'admin/ecom_orders/accepted_orders_list';
        $this->data['nav_type'] = 'accepted_orders_list';
        $this->data['rejected_orders'] = $this->delivery_job_rejection_model
        ->with_delivery_job('fields: id, job_id, ecom_order_id')
        ->with_delivery_boy('fields: id, phone, email, display_name')
        ->group_by('job_id')
        ->order_by('id', 'DESC')
        ->get_all();
        if(! empty($this->data['rejected_orders'])){foreach ($this->data['rejected_orders'] as $key => $order){
            $this->data['rejected_orders'][$key]['order_details'] = $this->ecom_order_model
            ->fields('track_id, shipping_address_id, delivery_fee, payment_id, vendor_user_id, created_at')
            ->with_shipping_address('fields: id, state_id, district_id, constituency_id, phone, email, name, landmark, pincode, address, location_id, created_user_id, updated_user_id, created_at, updated_at, deleted_at, status')
            ->with_vendor('fields: id, whats_app_no, secondary_contact, name, vendor_user_id')
            ->where('id', $order['delivery_job']['ecom_order_id'])
            ->get();
        }}else {
            $this->data['rejected_orders'] = [];
        }
        $this->_render_page($this->template, $this->data);
    }
    
    public function delivery_boy_wallet_transactions($type = 'r', $rowno = 0)
    {
        // echo 'hi';
        // die();
        if ($type == 'r') {
            // echo 'hello';
            // die();
            $this->load->library('pagination');
            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }
            $this->data['title'] = 'Delivery Boy Wallet Transactions List';
            $this->data['content'] = 'admin/ecom_orders/delivery_boy_wallet_transactions';
            $this->data['nav_type'] = 'delivery_boy_wallet_transactions';
            $allcount = $this->db->query("SELECT wt.*, u.first_name, eo.id FROM wallet_transactions AS wt 
           
            JOIN ecom_orders AS eo ON  eo.id = wt.ecom_order_id
            JOIN users AS u ON u.id  = wt.account_user_id
            WHERE wt.status = '1' and  u.primary_intent LIKE 'delivery_partner'" )->num_rows();

            $this->data['transactions'] = $this->db->query("SELECT wt.*, u.first_name, eo.id FROM wallet_transactions AS wt 
           
            JOIN ecom_orders AS eo ON  eo.id = wt.ecom_order_id
            JOIN users AS u ON u.id  = wt.account_user_id
            WHERE wt.status = '1' and  u.primary_intent LIKE 'delivery_partner' ORDER BY wt.created_at DESC LIMIT " . $rowno . ',' . $rowperpage)->result_array();

            $this->data['delivery_boy_names'] = $this->db->query("SELECT * FROM delivery_jobs as dj join users as u on  u.id=dj.delivery_boy_user_id group by dj.delivery_boy_user_id ")->result_array();
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
            $config['base_url'] = base_url() . 'admin/ecom_orders/delivery_boy_wallet_transactions/r';
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

    public function accept_dj_rejection(){
        $rejection_id = base64_decode(base64_decode($this->input->get('id')));
        $data = $this->delivery_job_rejection_model->accept($rejection_id, 'admin');
        if($data['success'] ==  TRUE){
            $this->send_notification($data['rejection_request']['rejected_by'], DELIVERY_APP_CODE, "Order status of( " . $data['job']['order']['track_id'] . " )", "Congrats, Reject request has been accepted", [
                'order_id' => $data['job']['order']['id'],
                'notification_type' => $this->notification_type_model->where([
                    'app_details_id' => DELIVERY_APP_CODE,
                    'notification_code' => 'OD'
                ])->get()
            ]);
        }
        redirect('delivery_job_rejection_requests');
    }
    
    public function cancel_dj_rejection(){
        $rejection_id = base64_decode(base64_decode($this->input->get('id')));
        $rejection_request = $this->delivery_job_rejection_model
        ->with_delivery_job('fields: id, ecom_order_id')
        ->where('id', $rejection_id)->get();
        if($rejection_request){
            $job = $this->delivery_job_model
            ->with_order('fields: id, track_id')
            ->where('id', $rejection_request['job_id'])
            ->get();
            $is_rejection_cancelled = $this->delivery_job_rejection_model->update([
                'id' =>  $rejection_id,
                'status' => 2
            ], 'id');
            if($is_rejection_cancelled){
                $this->delivery_job_model->update([
                    'id' => $rejection_request['job_id'],
                    'status' => $rejection_request['current_order_status']
                ], 'id');
                /**
                 * trigger push notificatios *
                 */
                $this->send_notification($rejection_request['rejected_by'], DELIVERY_APP_CODE, "Order status of( " . $job['order']['track_id'] . " )", "Congrats, Customer is available now", [
                    'order_id' => $job['order']['id'],
                    'notification_type' => $this->notification_type_model->where([
                        'app_details_id' => DELIVERY_APP_CODE,
                        'notification_code' => 'OD'
                    ])->get()
                ]);
            }
            redirect('delivery_job_rejection_requests');
        }else {
            $response = [
                'status' => FALSE,
                'message' => 'Rejection request is not found'
            ];
        }
        
    }
    
    public function orders_dashboard(){
        $this->data['title'] = 'Orders dashboard';
        $this->data['content'] = 'admin/ecom_orders/orders_dashboard';
        $this->data['nav_type'] = 'rejected_order_veiw';
        $this->_render_page($this->template, $this->data);
    }
    
}
