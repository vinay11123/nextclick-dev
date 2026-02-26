<?php
require_once APPPATH . '/libraries/MY_REST_Controller.php';
require_once APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use GuzzleHttp\Promise\Create;

class Delivery extends MY_REST_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('user_model');
        $this->load->model('users_address_model');
        $this->load->model('vendor_list_model');
        $this->load->model('location_model');
        $this->load->model('delivery_job_model');
        $this->load->model('delivery_partner_session_model');
        $this->load->model('delivery_partner_location_tracking_model');
        $this->load->model('ecom_order_model');
        $this->load->model('pickup_orders_model');
        $this->load->model('vendor_list_model');
        $this->load->model('notifications_model');
        $this->load->model('ecom_order_status_log_model');
        $this->load->model('food_item_model');
        $this->load->model('vendor_product_variant_model');
        $this->load->model('notification_type_model');
        $this->load->model('delivery_boy_performance_extraction_model');
        $this->load->model('vehicle_model');
        $this->load->model('delivery_partner_shift_type_model');
        $this->load->model('Delivery_boy_biometric_model');
        $this->load->model('delivery_boy_address_model');
        $this->load->model('User_account_model');
    }
    
    /**
     * @desc To get the stuff related to dashboard page 
     * @author Mehar
     *  
     */
    public function dashboard_get(){
        $this->load->helper('money');
        $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		//print_r($token_data->id);die();
        $on_going_order = $this->delivery_job_model->where('status !=', 508)->where(['status >=' => 502,  'status !=' => 603, 'delivery_boy_user_id' => $token_data->id])->order_by('id', 'DESC')->get();
        if(! empty($on_going_order))
        {
            if(!empty($on_going_order['ecom_order_id']))
            {
                $order = $this->ecom_order_model->fields('id, track_id, order_delivery_otp, order_pickup_otp, delivery_fee, total, used_wallet_amount, message, preparation_time, created_at')
                ->with_shipping_address('fields: id, phone, email, name, landmark, address, location_id')
                ->with_delivery_mode('fields: id, name, desc')
                ->with_customer('fields: id, unique_id, first_name, phone')
                ->with_vendor('fields: id, name, unique_id, location_id')
                ->with_order_status('fields: id, delivery_mode_id, status, serial_number')
                ->with_payment('fields: id, payment_method_id, txn_id, amount, created_at, message, status')
                ->with_ecom_order_details('fields: id, item_id, vendor_product_variant_id, qty, price, rate_of_discount, sub_total, discount, tax, total, cancellation_message, status')
                ->where('id', $on_going_order['ecom_order_id'])
                ->get();
                
                $order['is_ecom_order'] =TRUE;
                $order['is_pickup_order'] =FALSE;

                    $delivery_job = $this->delivery_job_model->fields('job_id, rating, feedback, job_type, delivery_boy_user_id, status')->where('ecom_order_id', $order['id'])->order_by('id', 'DESC')->get();
                    $order['delivery_job'] = empty($delivery_job)? NULL : $delivery_job;
                    $order['delivery_mode']['order_statuses'] = $this->ecom_order_status_model->where(['delivery_mode_id' => $order['delivery_mode']['id'], 'serial_number <' => 200])->get_all();
                    $order['order_status']['time'] = $this->ecom_order_status_log_model->fields('created_at')->where(['ecom_order_id' => $order['id'], 'ecom_order_status_id' => $order['order_status']['id']])->get();
                    $order['shipping_address']['location'] = $this->location_model->fields('id, latitude, longitude, address')->where('id', $order['shipping_address']['location_id'])->get();
                    $order['vendor']['location'] = $this->location_model->fields('id, latitude, longitude, address')->where('id', $order['vendor']['location_id'])->get();
                    if(! empty($order['ecom_order_details'])){ foreach ($order['ecom_order_details'] as $key => $detials){
                        $order['ecom_order_details'][$key]['item'] = $this->food_item_model->fields('id, name, desc')->with_item_images('fields: id, ext')->where('id', $detials['item_id'])->get();
                        if(! empty($order['ecom_order_details'][$key]['item']['item_images'])){
                            foreach ($order['ecom_order_details'][$key]['item']['item_images'] as $i => $val){
                                $order['ecom_order_details'][$key]['item']['item_images'][$i]['image']  = base_url() . 'uploads/food_item_image/food_item_' . $val['id'] . '.' . $val['ext'] . '?' . time();
                            }
                        }else {
                            $order['ecom_order_details'][$key]['item']['images'] = [];
                        }
                        $order['ecom_order_details'][$key]['item']['varinat'] = $this->vendor_product_variant_model->fields('id, sku, price, stock, discount, tax_id, status')->with_section_item('fields: id, name, weight')->where('id', $detials['vendor_product_variant_id'])->get();
                    }}
                    $data['on_going_order'] =  $order;
            }
            else if(!empty($on_going_order['pickup_order_id']))
            {
                $order = $this->pickup_orders_model->fields('id, track_id, order_delivery_otp, order_pickup_otp, delivery_fee,created_at')
                ->with_pickup_address('fields: id, phone, email, name, landmark, address, location_id')
                ->with_delivery_address('fields: id, phone, email, name, landmark, address, location_id')
                ->with_customer('fields: id, unique_id, first_name, phone')
                ->with_order_status('fields: id, delivery_mode_id, status, serial_number')
                ->with_payment('fields: id, payment_method_id, txn_id, amount, created_at, message, status')
                ->where('id', $on_going_order['pickup_order_id'])
                ->get();
        
                $order['is_ecom_order'] =FALSE;
                $order['is_pickup_order'] =TRUE;
                
            $delivery_job = $this->delivery_job_model->fields('job_id, rating, feedback, job_type, delivery_boy_user_id, status')->where('pickup_order_id', $order['id'])->order_by('id', 'DESC')->get();
            $order['delivery_job'] = empty($delivery_job)? NULL : $delivery_job;
            $order['delivery_mode']['order_statuses'] = $this->ecom_order_status_model->where(['delivery_mode_id' => 2, 'serial_number <' => 200])->get_all();
            //$order['order_status']['time'] = $this->ecom_order_status_log_model->fields('created_at')->where(['pickup_order_id' => $order['id'], 'ecom_order_status_id' => $order['order_status']['id']])->get();
            //$order['shipping_address']['location'] = $this->location_model->fields('id, latitude, longitude, address')->where('id', $order['shipping_address']['location_id'])->get();
            $order['pickup_address']['location'] = $this->location_model->fields('id,latitude, longitude, address')
            ->where('id', $order['pickup_address']['location_id'])
            ->get();
            $order['delivery_address']['location'] = $this->location_model->fields('id,latitude, longitude, address')
            ->where('id', $order['delivery_address']['location_id'])
            ->get();
            $data['on_going_order'] =  $order;
            }
        }else {
            $data['on_going_order'] = NULL;
        }
        $delivey_boy_status = $this->user_model->fields('delivery_partner_status')->where('id', $token_data->id)->get();
        $data['current_status'] = (! empty($delivey_boy_status['delivery_partner_status']) && $delivey_boy_status['delivery_partner_status'] == 1)? TRUE : FALSE;
        //$data['today_earnings'] = $this->db->query("SELECT sum(amount) as today_earnings FROM wallet_transactions where account_user_id = ".$token_data->id." and type = 'CREDIT' and date(created_at) = CURDATE() and status = 1;")->result_array()[0]['today_earnings'];
        //$data['today_earnings'] = empty($data['today_earnings'])? 0 : $data['today_earnings'];
        $todayFloatingCashCredit = $this->db->query("SELECT sum(amount) as today_floatings FROM wallet_transactions where account_user_id = ".$token_data->id." and type = 'CREDIT' and date(created_at) = '".date('Y-m-d')."' and status = 2;")->result_array()[0]['today_floatings'];
        $totalFloatingCashCredit = $this->db->query("SELECT sum(amount) as total_floatings FROM wallet_transactions where account_user_id = ".$token_data->id." and type = 'CREDIT' and status = 2;")->result_array()[0]['total_floatings'];

        $data['today_ecom_earnings'] = $this->db->query("SELECT COALESCE(sum(amount),0) as today_earnings FROM delivery_boy_ecom_earnings_view where account_user_id = ".$token_data->id." and date(created_at) = '".date('Y-m-d')."'")->result_array()[0]['today_earnings'];
        $data['total_ecom_earnings'] = $this->db->query("SELECT COALESCE(sum(amount),0) as total_earnings FROM delivery_boy_ecom_earnings_view where account_user_id = ".$token_data->id)->result_array()[0]['total_earnings'];
        // $todayFloatingCashDebit = $this->db->query("SELECT sum(amount) as today_floatings FROM wallet_transactions where account_user_id = ".$token_data->id." and type = 'DEBIT' and date(created_at) = CURDATE() and status = 2;")->result_array()[0]['today_floatings'];
        // $todayFloatingCashDebit = 0;
        // $deliveryBoyFloatingAmt = substract(floatval($todayFloatingCashCredit), floatval($todayFloatingCashDebit));

        $today_pickup_earnings_sql = "SELECT COALESCE(sum(delivery_fee_without_gst),0) amount FROM `delivery_jobs` dj 
                                        join pickup_orders po on po.id = dj.pickup_order_id
                                        WHERE `status` = 508 and `delivery_boy_user_id` = ".$token_data->id." and date(dj.`created_at`) = '".date('Y-m-d')."'";
        $data['today_pickup_earnings'] = $this->db->query($today_pickup_earnings_sql)->result_array()[0]['amount'];

        $total_pickup_earnings_sql = "SELECT COALESCE(sum(delivery_fee_without_gst),0) amount FROM `delivery_jobs` dj 
                                        join pickup_orders po on po.id = dj.pickup_order_id
                                        WHERE `status` = 508 and `delivery_boy_user_id` = ".$token_data->id;
        $data['total_pickup_earnings'] = $this->db->query($total_pickup_earnings_sql)->result_array()[0]['amount'];
        
        
        $data['today_floating_cash'] = empty($todayFloatingCashCredit)? 0 : $todayFloatingCashCredit;
        $data['total_floating_cash'] = empty($totalFloatingCashCredit)? 0 : $totalFloatingCashCredit;
        
        $data['today_ecom_deliveries'] = $this->db->query("SELECT count(*) as count FROM delivery_jobs where ecom_order_id is not null and delivery_boy_user_id = ".$token_data->id." and date(created_at) = '".date('Y-m-d')."' and status = 508;")->result_array()[0]['count'];
        $data['today_ecom_deliveries'] = empty($data['today_ecom_deliveries'])? 0 : $data['today_ecom_deliveries'];
        $data['total_ecom_deliveries'] = $this->db->query("SELECT count(*) as count FROM delivery_jobs where ecom_order_id is not null and delivery_boy_user_id = ".$token_data->id." and status = 508;")->result_array()[0]['count'];;
        $data['total_ecom_deliveries'] = empty($data['total_ecom_deliveries'])? 0 : $data['total_ecom_deliveries']; 
        
        $data['today_pickup_deliveries'] = $this->db->query("SELECT count(*) as count FROM delivery_jobs where pickup_order_id is not null and delivery_boy_user_id = ".$token_data->id." and date(created_at) = '".date('Y-m-d')."' and status = 508;")->result_array()[0]['count'];
        $data['today_pickup_deliveries'] = empty($data['today_pickup_deliveries'])? 0 : $data['today_pickup_deliveries'];
        $data['total_pickup_deliveries'] = $this->db->query("SELECT count(*) as count FROM delivery_jobs where pickup_order_id is not null and delivery_boy_user_id = ".$token_data->id." and status = 508;")->result_array()[0]['count'];;
        $data['total_pickup_deliveries'] = empty($data['total_pickup_deliveries'])? 0 : $data['total_pickup_deliveries'];  

        $this->set_response_simple($data, 'Success.', REST_Controller::HTTP_CREATED, TRUE);
    }
    
    /**
     * @desc Manage Delivery boy login sessions
     *
     * @author Mehar
     */
    public function manage_login_session_post()
    {
        $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->delivery_partner_session_model->user_id = $token_data->id;
        $this->delivery_partner_location_tracking_model->user_id = $token_data->id;
        $user = $this->user_model->fields('id, delivery_partner_status, delivery_partner_approval_status, last_delivery_partner_sesion_id, status')->where('id', $token_data->id)->get();
        $login_status = $this->input->post('login_status');
        if($login_status == 1){
            if($user['delivery_partner_approval_status'] == 1){
                $is_created = $this->delivery_partner_session_model->insert([
                    'session_started_at' => date('Y-m-d H:i:s'),
                    'status' => $login_status
                ]);
                if($is_created){
                    $is_location_exist = $this->delivery_partner_location_tracking_model->where('delivery_partner_user_id', $token_data->id)->get();
                    if(empty($is_location_exist)){
                        $this->delivery_partner_location_tracking_model->insert([
                            'latitude' => ! empty($this->input->post('latitude'))? $this->input->post('latitude') : NULL,
                            'longitude' => ! empty($this->input->post('longitude'))? $this->input->post('longitude') : NULL,
                            'address' => ! empty($this->input->post('address'))? $this->input->post('address') : NULL,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    }else{
                        $this->delivery_partner_location_tracking_model->update([
                            'delivery_partner_user_id' => $token_data->id,
                            'latitude' => ! empty($this->input->post('latitude'))? $this->input->post('latitude') : NULL,
                            'longitude' => ! empty($this->input->post('longitude'))? $this->input->post('longitude') : NULL,
                            'address' => ! empty($this->input->post('address'))? $this->input->post('address') : NULL,
                            'created_at' => date('Y-m-d H:i:s'),
                        ], 'delivery_partner_user_id');
                    }
                    
                    $this->user_model->update([
                        'id' => $token_data->id,
                        'delivery_partner_status' => $login_status,
                        'last_delivery_partner_sesion_id' => $is_created
                    ], 'id');
                }
                $this->set_response_simple(NULL, 'Session started.', REST_Controller::HTTP_CREATED, TRUE);
            }else {
                $this->set_response_simple(NULL, 'Sorry, Your profile is under verfication.', REST_Controller::HTTP_OK, FALSE);
            }
            
        }elseif ($login_status == 2){
            $user = $this->user_model->fields('id, delivery_partner_status, last_delivery_partner_sesion_id, status')->where('id', $token_data->id)->get();
            if(!empty($user) && $user['delivery_partner_status'] == 1){
                $this->delivery_partner_session_model->update([
                    'id' => $user['last_delivery_partner_sesion_id'],
                    'session_ended_at' => date('Y-m-d H:i:s'),
                    'status' => $login_status
                ], 'id');
                
                $this->user_model->update([
                    'id' => $token_data->id,
                    'delivery_partner_status' => 2,
                ], 'id');
                $this->set_response_simple(NULL, 'Session ended.', REST_Controller::HTTP_ACCEPTED, TRUE);
            }else {
                $this->set_response_simple(NULL, 'You are not authorised user.', REST_Controller::HTTP_OK, FALSE);
            }
        }else {
            $this->set_response_simple(NULL, 'In valid request', REST_Controller::HTTP_OK, FALSE);
        }
        
    }
    
    /**
     * @desc To GET/SET delivery boy current location
     *
     * @author Mehar
     * @param string $typel
     */
    public function current_location_post($type = 'get'){
       $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->delivery_partner_location_tracking_model->user_id = $token_data->id;
        if($type == 'get'){
            $location = $this->delivery_partner_location_tracking_model->fields('id, delivery_partner_user_id, latitude, longitude, address')->where('delivery_partner_user_id', $this->input->post('delivery_partner_user_id'))->get();
            $data['current_location'] = ! empty($location)? $location : NULL;
            if(! empty($this->input->post('job_id')))
                $data['job'] = $this->delivery_job_model->fields('id, ecom_order_id, status')->where('id', $this->input->post('job_id'))->get();
            else 
                $data['job'] = NULL;
            
            $this->set_response_simple($data, 'Success.', REST_Controller::HTTP_OK, TRUE);
        }elseif ($type == 'set'){
            $is_location_exist = $this->delivery_partner_location_tracking_model->where('delivery_partner_user_id', $token_data->id)->get();
            if(empty($is_location_exist)){
                $this->delivery_partner_location_tracking_model->insert([
                    'latitude' => ! empty($this->input->post('latitude'))? $this->input->post('latitude') : NULL,
                    'longitude' => ! empty($this->input->post('longitude'))? $this->input->post('longitude') : NULL,
                    'address' => ! empty($this->input->post('address'))? $this->input->post('address') : NULL,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }else{
                $this->delivery_partner_location_tracking_model->update([
                    'delivery_partner_user_id' => $token_data->id,
                    'latitude' => ! empty($this->input->post('latitude'))? $this->input->post('latitude') : NULL,
                    'longitude' => ! empty($this->input->post('longitude'))? $this->input->post('longitude') : NULL,
                    'address' => ! empty($this->input->post('address'))? $this->input->post('address') : NULL,
                    'created_at' => date('Y-m-d H:i:s'),
                ], 'delivery_partner_user_id');
            }
            $this->set_response_simple(NULL, 'Location created.', REST_Controller::HTTP_CREATED, TRUE);
        }
    }
    
    /**
     * @desc To get near by vendors to show on map
     * @author Mehar
     */
    public function near_by_vendors_get(){
        $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $delivery_partner_lcoation = $this->delivery_partner_location_tracking_model->fields('latitude, longitude, address')->where('delivery_partner_user_id', $token_data->id)->get();
        if(! empty($delivery_partner_lcoation)){
            $data = $this->vendor_list_model->get_vendors_nearby_delivery($delivery_partner_lcoation['latitude'], $delivery_partner_lcoation['longitude']);
            $this->set_response_simple((empty($data)) ? NULL : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
        
    }
    
    /**
     * @desc To give all active notfications for delivery boy application
     * @author Mehar
     */
    public function notifications_post($type = 'r') {
		
        $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->delivery_job_model->user_id = $token_data->id;
        if($type == 'r'){
            $notifications = $this->notifications_model
            ->fields('id, notification_type_id, ecom_order_id,pickup_order_id, title, message, notified_user_id, created_at, status')
            ->with_order('fields: id, track_id, preparation_time, delivery_fee, delivery_gst_percentage, vendor_user_id, shipping_address_id, created_at, order_status_id,delivery_boy_delivery_fee_without_gst')
            ->with_pickup_order('fields: id, track_id,  delivery_fee, pickup_address_id,delivery_address_id, created_at, order_status_id,delivery_boy_delivery_fee_without_gst')
            ->where("created_at >= CURRENT_TIMESTAMP - INTERVAL 1 DAY", NULL, NULL, NULL, NULL, TRUE)
            ->where(['notified_user_id' => $token_data->id, 'status' => 1, 'app_details_id' => DELIVERY_APP_CODE])
            ->order_by('id', 'DESC')
            ->get_all();
//print_r("dsafdsfa111 ".$this->db->last_query());exit;
            
            if(! empty($notifications)){ 
                foreach ($notifications as $key => $notification)
                {
                    if(!empty($notification['order']))
                    {    
                        $delivery_fee_without_gst = $notifications[$key]['order']['delivery_fee'] / (1+$notifications[$key]['order']['delivery_gst_percentage']/100);
                        $shipping_address = $this->users_address_model->fields('id, location_id')->where('id', $notification['order']['shipping_address_id'])->get();
                        $vendor = $this->vendor_list_model->fields('id, location_id')->where('vendor_user_id', $notification['order']['vendor_user_id'])->get();
                        $notifications[$key]['order']['delivery_fee_without_gst'] = round($delivery_fee_without_gst, 2);
                        $notifications[$key]['order']['vendor_location'] = $this->location_model->fields('id, latitude, longitude, address')->where('id', $vendor['location_id'])->get();
                        $notifications[$key]['order']['shipping_location'] = $this->location_model->fields('id, latitude, longitude, address')->where('id', $shipping_address['location_id'])->get();
                    }
                    else if(!empty($notification['pickup_order']))
                    {
                        $delivery_fee_without_gst = $notifications[$key]['pickup_order']['delivery_fee'] / (1+18/100);
						$notifications[$key]['pickup_order']['preparation_time']=0;
                        $pickup_address = $this->users_address_model->fields('id, location_id')->where('id', $notification['pickup_order']['pickup_address_id'])->get();
                        $delivery_address = $this->users_address_model->fields('id, location_id')->where('id', $notification['pickup_order']['delivery_address_id'])->get();
                        $notifications[$key]['pickup_order']['delivery_fee_without_gst'] = round($delivery_fee_without_gst, 2);
                        $notifications[$key]['pickup_order']['delivery_location'] = $this->location_model->fields('id, latitude, longitude, address')->where('id', $delivery_address['location_id'])->get();
						
                        $notifications[$key]['pickup_order']['pickup_location'] = $this->location_model->fields('id, latitude, longitude, address')->where('id', $pickup_address['location_id'])->get();
                    }
                }
            }
            $this->set_response_simple((empty($notifications)) ? NULL : $notifications, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }elseif ($type == 'accept'){
            $is_he_on_any_job_right_now = $this->delivery_job_model->where(['delivery_boy_user_id' => $token_data->id, 'status >=' =>502, 'status <' => 508])->get();
            $is_any_one_accepted_this_job = $this->delivery_job_model->where(['ecom_order_id' => $this->input->get('order_id'), 'status >=' => 502])->get();
            if( empty($is_he_on_any_job_right_now)){
                if(empty($is_any_one_accepted_this_job)){
                    $query = $this->db->query("CALL create_job_if_not_assigned('".generate_serial_no('DJ', 3, rand(999, 9999))."', ".$this->input->post('order_id').", ". $this->input->post('job_type').",  ".$token_data->id.", ".$token_data->id.", 502, '".date('Y-m-d H:i:s')."', @result);");
                    $result = $query->result_array();
                    /* $is_created = $this->delivery_job_model->insert([
                        'job_id' => generate_serial_no('DJ', 3, rand(999, 9999)),
                        'ecom_order_id' => $this->input->post('order_id'),
                        'job_type' => $this->input->post('job_type'),
                        'delivery_boy_user_id' => $token_data->id,
                        'status' => 502
                    ]); */
                    if($result[0]['result']){
                        $query->next_result();
                        $query->free_result(); 
                        if(!empty($this->input->post('order_id'))){
                            $this->notifications_model->update([
                                'ecom_order_id' => $this->input->post('order_id'),
                                'status' => 2
                            ], 'ecom_order_id');
                            
                            $this->ecom_order_model->update([
                                'id' => $this->input->post('order_id'),
                                'order_delivery_otp' => rand(99999, 999999),
                            ], 'id');
                            $this->set_response_simple(NULL, 'Job has been accepted', REST_Controller::HTTP_OK, TRUE);
                        }
                        else if(!empty($this->input->post('pickup_order_id'))){
                            $this->notifications_model->update([
                                'pickup_order_id' => $this->input->post('pickup_order_id'),
                                'status' => 2
                            ], 'pickup_order_id');
                            
                            $this->pickup_orders_model->update([
                                'id' => $this->input->post('pickup_order_id'),
                                'order_delivery_otp' => rand(99999, 999999),
                            ], 'id');
                            $this->set_response_simple(NULL, 'Job has been accepted', REST_Controller::HTTP_OK, TRUE);
                        }
                        else{
                            $this->set_response_simple(NULL, 'Sorry, You are missing input params', REST_Controller::HTTP_OK, FALSE);
                        }
                    }else {
                        $this->set_response_simple(NULL, 'Sorry, You are a bit late.', REST_Controller::HTTP_OK, FALSE);
                    }
                }else {
                    $this->set_response_simple(NULL, 'Sorry, You are a bit late.', REST_Controller::HTTP_OK, FALSE);
                }
                
            }else {
                $this->set_response_simple(NULL, 'You have alreay been on a job, So finish that and get a new job', REST_Controller::HTTP_OK, FALSE);
            }
        }elseif ($type == 'in_active'){
            $is_status_updated = $this->notifications_model->update([
                'id' => $this->input->post('notification_id'),
                'status' => 2
            ], 'id');
            
            if($is_status_updated)
                $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            else 
                $this->set_response_simple(NULL, 'Failed..!', REST_Controller::HTTP_OK, FALSE);
        }
    }

    /**
     * @desc to update the customer rating 
     * @author Bhagyeswar
     */
    public function customer_rating_post()
    {
        $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->form_validation->set_rules($this->delivery_job_model->rules['update_rules']);
        if ($this->form_validation->run() == FALSE) {
            $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
        } else {
            $is_updated = $this->delivery_job_model->update([
                'id' => $this->input->post('delivery_job_id'),
                'rating'=> $this->input->post('rating'),
                'feedback' => $this->input->post('feedback')
               ], 'id');
            if ($is_updated) {
                $this->set_response_simple(($is_updated == FALSE) ? NULL : $is_updated, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(($is_updated == FALSE) ? NULL : $is_updated, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
            }
        }
    }

    public function saveDeliveryJobEvent($jobID, $event, $deliveryBoyID = null){
        try{
            $this->load->model('delivery_job_event_model');
            $eventID= null;
            switch($event){
                case 'CREATED': 
                    $eventID = $this->delivery_job_event_model->insert([
                        'job_id' => $jobID,
                        'event' => $event
                    ]);
                    break;
                case 'ACCEPTED': 
                    $eventID= $this->delivery_job_event_model->insert([
                        'job_id' => $jobID,
                        'delivery_boy_user_id' => $deliveryBoyID,
                        'event' => $event
                    ]);
                    break;
                case 'REJECTED':
                    $eventID = $this->delivery_job_event_model->insert([
                        'job_id' => $jobID,
                        'delivery_boy_user_id' => $deliveryBoyID,
                        'event' => $event
                    ]);
                    break;
                default:
                    break;
            }
            return [
                "success" =>true,
                "data" => [
                    "event_id"=>$eventID
                ]
            ];
        }catch(Exception $ex){
            return [
                "success" =>false,
                "data" => $ex
            ];
        }
    }

    public function create($ecomOrderID){
        try{
            $responseArr = [];
            $deliveryJob = $this->delivery_job_model->get([
                'ecom_order_id' => $ecomOrderID,
                'job_type' => 1
            ]);
            if(!$deliveryJob){
                $jobID= $this->delivery_job_model->insert([
                    'job_id' => generate_serial_no('DJ', 3, rand(999, 9999)),
                    "ecom_order_id"=>$ecomOrderID,
                    'status' =>501
                ]);
                $eventID = $this->saveDeliveryJobEvent($jobID, 'CREATED');
                $responseArr = $this->delivery_job_model->fields('id, job_id, ecom_order_id, status')->get($deliveryJob);
                $this->set_response($responseArr, null, REST_Controller::HTTP_OK, TRUE);
            }else{
                $this->set_response(null, "JOB_ALREADY_EXISTS", REST_Controller::HTTP_BAD_REQUEST);
            }
        }catch(Exception $ex){
            $this->set_response(null, null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }
    public function create_pickup_order_id($orderID){
        try{
            $responseArr = [];
            $deliveryJob = $this->delivery_job_model->get([
                'pickup_order_id' => $orderID,
                'job_type' => 1
            ]);
            if(!$deliveryJob){
                $jobID= $this->delivery_job_model->insert([
                    'job_id' => generate_serial_no('DJ', 3, rand(999, 9999)),
                    "pickup_order_id"=>$orderID,
                    'status' =>501
                ]);
                $eventID = $this->saveDeliveryJobEvent($jobID, 'CREATED');
                $responseArr = $this->delivery_job_model->fields('id, job_id, pickup_order_id, status')->get($deliveryJob);
                $this->set_response($responseArr, null, REST_Controller::HTTP_OK, TRUE);
            }else{
                $this->set_response(null, "JOB_ALREADY_EXISTS", REST_Controller::HTTP_BAD_REQUEST);
            }
        }catch(Exception $ex){
            $this->set_response(null, null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }

    public function accept_post($orderID){
        try{
            $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
			$token_data =$this->validate_token($authorization_exp[1]);
			
            $userID = $token_data->id;
            $responseArr = [];
            $this->db->trans_begin();
            $existingJob = $this->delivery_job_model->where(['delivery_boy_user_id' => $userID, 'status >=' =>502, 'status <' => 508])->get();
            $orderDeliveryJob = $this->delivery_job_model->where(['ecom_order_id' => $orderID])->get();
            $ecomOrder = $this->ecom_order_model->where(['id'=>$orderID])->get();
            $notificationType = $this->notification_type_model->where(['app_details_id' => DELIVERY_APP_CODE, 'notification_code' => 'OD'])->get();
            $deliveryBoyBiometrics = $this->Delivery_boy_biometric_model->where(['user_id'=> $userID])->get();
            if(!$ecomOrder['vehicle_type'] || ($ecomOrder['vehicle_type'] && $deliveryBoyBiometrics['vehicle_type_id']==$ecomOrder['vehicle_type'])){
                if($orderDeliveryJob["status"] >= 502){
                    $this->set_response_simple(null, "JOB_ALREADY_ALLOCATED", REST_Controller::HTTP_OK);
                }else if(!$existingJob && !$orderDeliveryJob["delivery_boy_user_id"]){
                    $this->delivery_job_model->update([
                        "status" => 502,
                        "delivery_boy_user_id" => $userID
                    ], $orderDeliveryJob["id"]);
                    $this->ecom_order_model->update([
                        'id' => $orderID,
                        'order_delivery_otp' => rand(99999, 999999),
                        'current_order_status_id' => ORDER_STATUS_DELIVERY_BOY_ASSIGNED_ID
                    ], 'id');
                    $eventID = $this->saveDeliveryJobEvent($orderDeliveryJob["id"], 'ACCEPTED', $userID);
                    $responseArr = $this->delivery_job_model->fields('id, job_id, ecom_order_id, status')->get($orderDeliveryJob["id"]);
                    $this->invalidate_notification("ORDER", $notificationType['id'], DELIVERY_APP_CODE, $orderDeliveryJob['ecom_order_id']);
                    $this->db->trans_commit();
                    $this->set_response($responseArr, null, REST_Controller::HTTP_OK);
                }else{
                    $this->db->trans_commit();
                    $message="";
                    if($existingJob){
                        $message = "OTHER_JOB_INPROGRESS";
                    }else{
                        $message = "JOB_ALREADY_ALLOCATED";
                    }
                    $this->set_response_simple(null, $message, REST_Controller::HTTP_OK, FALSE);
                }   
            }else{
                $message = "VEHICLE_TYPE_MISMATCH";
                $this->set_response_simple(null, $message, REST_Controller::HTTP_OK, FALSE);
            }
        }catch(Exception $ex){
            $this->db->trans_rollback();
            $this->set_response_simple(null, null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }

    public function pickupanddrop_order_accept_post($orderID){
        try{
            $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
			$token_data =$this->validate_token($authorization_exp[1]);
		
            $userID = $token_data->id;
            $responseArr = [];
            $this->db->trans_begin();
            $existingJob = $this->delivery_job_model->where(['delivery_boy_user_id' => $userID, 'status >=' =>502, 'status <' => 508])->get();
            $orderDeliveryJob = $this->delivery_job_model->where(['pickup_order_id' => $orderID])->get();
            $ecomOrder = $this->pickup_orders_model->where(['id'=>$orderID])->get();
            $notificationType = $this->notification_type_model->where(['app_details_id' => DELIVERY_APP_CODE, 'notification_code' => 'OD'])->get();
            $deliveryBoyBiometrics = $this->Delivery_boy_biometric_model->where(['user_id'=> $userID])->get();
            if(!$ecomOrder['vehicle_type'] || ($ecomOrder['vehicle_type'] && $deliveryBoyBiometrics['vehicle_type_id']==$ecomOrder['vehicle_type'])){
                if($orderDeliveryJob["status"] >= 502){
                    $this->set_response_simple(null, "JOB_ALREADY_ALLOCATED", REST_Controller::HTTP_OK);
                }else if(!$existingJob && !$orderDeliveryJob["delivery_boy_user_id"]){
                    $this->delivery_job_model->update([
                        "status" => 502,
                        "delivery_boy_user_id" => $userID
                    ], $orderDeliveryJob["id"]);
                    $this->pickup_orders_model->update([
                        'id' => $orderID,
                        'order_delivery_otp' => rand(99999, 999999),
                    ], 'id');
                    $eventID = $this->saveDeliveryJobEvent($orderDeliveryJob["id"], 'ACCEPTED', $userID);
                    $responseArr = $this->delivery_job_model->fields('id, job_id, pickup_order_id, status')->get($orderDeliveryJob["id"]);
                    $this->invalidate_notification("ORDER", $notificationType['id'], DELIVERY_APP_CODE, $orderDeliveryJob['pickup_order_id']);
                    $this->db->trans_commit();
                    $this->set_response($responseArr, null, REST_Controller::HTTP_OK);
                }else{
                    $this->db->trans_commit();
                    $message="";
                    if($existingJob){
                        $message = "OTHER_JOB_INPROGRESS";
                    }else{
                        $message = "JOB_ALREADY_ALLOCATED";
                    }
                    $this->set_response_simple(null, $message, REST_Controller::HTTP_OK, FALSE);
                }   
            }else{
                $message = "VEHICLE_TYPE_MISMATCH";
                $this->set_response_simple(null, $message, REST_Controller::HTTP_OK, FALSE);
            }
        }catch(Exception $ex){
            $this->db->trans_rollback();
            $this->set_response_simple(null, null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }

    public function pay_post(){
        try{
            $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
			$token_data =$this->validate_token($authorization_exp[1]);
			
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $responseArr = [];
            $paymentAmt = $this->input->post('payment');
            $this->load->module('payment/api/payment');
            $responseArr = $this->payment->savePaymentLink(2, $token_data->id, "Payment to NextClick", $paymentAmt, $token_data->id, $token_data->id);
            if($responseArr["success"]){
                $this->set_response($responseArr["data"], null, REST_Controller::HTTP_OK);
            }else{
                $this->set_response(null, null, REST_Controller::HTTP_BAD_REQUEST);
            }
        }catch(Exception $ex){
            $this->db->trans_rollback();
            $this->set_response(null, null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }

    public function extract_post(){
        try{
            $fromDate = date("Y-m-d", strtotime( '-1 days' ) );
            $tillBeforeDate = date("Y-m-d");
            $performanceArr = $this->delivery_job_model->extractPerformanceInfo($fromDate, $tillBeforeDate);
            $this->delivery_boy_performance_extraction_model->saveAggrigations($fromDate, $performanceArr);
            $this->set_response(null, null, REST_Controller::HTTP_OK);
        }catch(Exception $ex){
            $this->db->trans_rollback();
            $this->set_response(null, null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }

    public function vehicles_get(){
        try{
            $vehicles = $this->vehicle_model->fields('id, name')->get_all();
            if(!$vehicles){
                $vehicles =[];
            }
            $this->set_response($vehicles, null, REST_Controller::HTTP_OK);
        }catch(Exception $ex){
            $this->set_response(null, null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }

    public function shift_types_get(){
        try{
            $shifts = $this->delivery_partner_shift_type_model->fields('id, name')->get_all();
            if(!$shifts){
                $shifts =[];
            }
            $this->set_response($shifts, null, REST_Controller::HTTP_OK);
        }catch(Exception $ex){
            $this->set_response(null, null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }
    public function updateDeliveryBoyFloatingValue_get(){
        $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
        //get ALl delivery boy ids
        $query = $this->db->query("SELECT `user_id` FROM `delivery_boy_address`");
        $result = $query->result();
        $DBoyIds = array();
        foreach($result as $val){
            $DBoyIds[] = $val->user_id;
            $getdBoyFloatValue = $this->db->select('eo.total,
                                    eo.delivery_fee,
                                    sum(eo.total - eo.delivery_fee) - (SELECT sum(amount) FROM `floating_payments` WHERE `created_user_id` = "'.$val->user_id.'") as total')
                                    ->from('ecom_orders eo')
                                    ->where('ep.payment_method_id',1)
                                    ->where('dj.delivery_boy_user_id',$val->user_id)
                                    ->join('ecom_payments ep','eo.id = ep.ecom_order_id')
                                    ->join('delivery_jobs dj','eo.id = dj.ecom_order_id')
                                    ->get();
            $result = $getdBoyFloatValue->result()[0]->total;
            if($result !=''){
                $this->User_account_model->update([
                    'user_id' => $val->user_id,
                    'floating_wallet' => $result,                    
                ], 'user_id');
            }
        }        
        $this->set_response_simple(null, 'Success.', REST_Controller::HTTP_CREATED, TRUE); 
    }
}

