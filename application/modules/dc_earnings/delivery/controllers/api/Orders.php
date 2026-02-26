<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Orders extends MY_REST_Controller
{
    
    public function __construct(){
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('users_address_model');
        $this->load->model('vendor_list_model');
        $this->load->model('location_model');
        $this->load->model('delivery_job_model');
        $this->load->model('delivery_partner_session_model');
        $this->load->model('delivery_partner_location_tracking_model');
        $this->load->model('vendor_list_model');
        $this->load->model('ecom_order_model');
        $this->load->model('pickup_orders_model');
        $this->load->model('ecom_payment_model');
        $this->load->model('delivery_job_model');
        $this->load->model('ecom_order_status_model');
        $this->load->model('notification_type_model');
		$this->load->model('notifications_model');
        $this->load->model('delivery_job_rejected_reason_model');
        $this->load->model('notifications_model');
        $this->load->model('delivery_job_rejection_model');
        $this->load->model('ecom_order_reject_request_model');
        $this->load->model('Delivery_boy_address_model');
    }
    
    public function delivery_orders_post($type = 'r'){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $orderID = $this->input->post('order_id');
        $orderDetails= [];
        if($orderID){
            $orderDetails = $this->ecom_order_model->getOrderDetailswithPayment($orderID);
        }
        $this->delivery_job_model->user_id = $token_data->id;
        if($type == 'r'){
            $orders = $this->ecom_order_model->get_orders(
                NULL,
                NULL,
                $token_data->id,
                (empty($this->input->post('start_date')))? NULL: $this->input->post('start_date'),
                (empty($this->input->post('end_date')))? NULL: $this->input->post('end_date'),
                NULL,
                NULL,
                (empty($this->input->post('status')))? NULL: $this->input->post('status'),
                (empty($this->input->post('delivery_boy_status')))? NULL: $this->input->post('delivery_boy_status'),
                FALSE,
                'delivery_orders'
                );                   
            
            if (! empty($orders)) {
                        if(! empty($orders))
                        {
                            foreach ($orders as $key => $order)
                            {
                                $delivery_fee_without_gst = $orders[$key]['delivery_fee'] / (1+$orders[$key]['delivery_gst_percentage']/100);
                                $orders[$key]['delivery_fee_without_gst'] = round($delivery_fee_without_gst, 2);
                                if(! empty($order['payment_id']))
                                    $orders[$key]['payment'] = $this->ecom_payment_model->fields('id, txn_id, amount, created_at, message, status')->with_payment_method('fields: id, name, description')->where('id', $order['payment_id'])->get();
                                else
                                    $orders[$key]['payment'] = NULL;
                                    
                                if(! empty($order['order_status_id']))
                                    $orders[$key]['order_status'] = $this->ecom_order_status_model->fields('id, delivery_mode_id, status, serial_number')->where('id', $order['order_status_id'])->get();
                                else
                                    $orders[$key]['order_status'] = NULL;
                            }
                        }                        
                $response = [];
                $response['orders']=$orders;
                $this->set_response_simple($response, 'Success.', REST_Controller::HTTP_OK, TRUE);
            }else {
                $this->set_response_simple(NULL, 'No orders found.!', REST_Controller::HTTP_OK, TRUE);
            }
        }elseif($type == 'pickup_orders_list'){            

                $pickuporders = $this->pickup_orders_model->get_orders(
                    NULL,
                    NULL,
                    $token_data->id,
                    (empty($this->input->post('start_date')))? NULL: $this->input->post('start_date'),
                    (empty($this->input->post('end_date')))? NULL: $this->input->post('end_date'),
                    NULL,
                    NULL,
                    (empty($this->input->post('status')))? NULL: $this->input->post('status'),
                    (empty($this->input->post('delivery_boy_status')))? NULL: $this->input->post('delivery_boy_status'),
                    FALSE,
                    'delivery_orders'
                    );    
            
            if (!empty($pickuporders) ) {                        
                        if(!empty($pickuporders))
                        {
                            foreach ($pickuporders as $key => $order)
                            {
                                $delivery_fee_without_gst = $pickuporders[$key]['delivery_fee'] / (1+18/100);
                                $pickuporders[$key]['delivery_fee_without_gst'] = round($delivery_fee_without_gst, 2);
                                if(! empty($order['payment_id']))
                                    $pickuporders[$key]['payment'] = $this->ecom_payment_model->fields('id, txn_id, amount, created_at, message, status')->with_payment_method('fields: id, name, description')->where('id', $order['payment_id'])->get();
                                else
                                    $pickuporders[$key]['payment'] = NULL;
                                    
                                if(! empty($order['order_status_id']))
                                    $pickuporders[$key]['order_status'] = $this->ecom_order_status_model->fields('id, delivery_mode_id, status, serial_number')->where('id', $order['order_status_id'])->get();
                                else
                                    $pickuporders[$key]['order_status'] = NULL;
                            }
                        }
                $response = [];
                $response['pickuporders']=$pickuporders;
                $this->set_response_simple($response, 'Success.', REST_Controller::HTTP_OK, TRUE);
            }else {
                $this->set_response_simple(NULL, 'No orders found.!', REST_Controller::HTTP_OK, TRUE);
            }
        }elseif ($type == 'change_status'){
			$order_details = $this->pickup_orders_model->fields('id, track_id, order_pickup_otp,delivery_fee, created_user_id')
                        ->with_payment('fields: id, payment_method_id, amount, status')
                        ->where('id', $orderID)
                        ->get();
					$notify_users = [
                                        $order_details['created_user_id']
                                    ];
									
            $delivery_job_id = $this->input->post('delivery_job_id');
            $delivery_job = $this->delivery_job_model->where('id', $delivery_job_id)->get();
            $status = $this->input->post('status');
            if(! empty($delivery_job)){
                if($status == 504 || $status == 506){
                    $is_job_updated = $this->delivery_job_model->update([
                        'id' => $delivery_job_id,
                        'status' => $status
                    ], 'id');                    
					
                    $message = ($status === 504)? "Delivery partner is reached to pickup point" : "Delivery partner is reached to delivery point";
                    // Trigger notification
                    $this->send_notification_pickupanddrop($notify_users, USER_APP_CODE, 'Delivery alert', $message, ['order_id' => $orderID, 'notification_type' => $this->notification_type_model->where(['app_details_id' => USER_APP_CODE, 'notification_code' => 'OD'])->get()]);
                    $this->set_response_simple(NULL, 'Success.', REST_Controller::HTTP_OK, TRUE);
                }else {
                    $this->set_response_simple(NULL, 'Invalid status.!', REST_Controller::HTTP_OK, FALSE);
                }
                
            }else {
                $this->set_response_simple(NULL, 'No order found.!', REST_Controller::HTTP_OK, FALSE);
            }
            
        }elseif ($type == 'deliver'){
            $order = $this->ecom_order_model->fields('id, track_id, order_delivery_otp, total, delivery_fee, vendor_user_id')->with_customer('fields: id, email, phone, unique_id, passcode, executive_user_id, first_order_id')->with_payment('fields: id, payment_method_id')->where('id', $this->input->post('order_id'))->get();
            $is_valid = 0;
            if(! empty($order)){
                if($order['order_delivery_otp'] == $this->input->post('otp')){
                    $is_valid = 1;
                }elseif (
                    // strtolower($order['customer']['email']) == strtolower($this->input->post('passcode')) || 
                    // strtolower($order['customer']['phone']) == strtolower($this->input->post('passcode')) || 
                    ( $this->input->post('passcode') && !empty($order['customer']['passcode']) && strtolower($order['customer']['passcode']) === strtolower($this->input->post('passcode')))){
                    $is_valid = 1;
                }
                if($is_valid === 1){
                    $is_updated = $this->delivery_job_model->update([
                        'ecom_order_id' => $order['id'],
                        'delivery_boy_user_id' => $token_data->id,
                        'status' => 508,
                        'amount_collected' => $this->input->post('amount_collected'),
                        'remarks' => $this->input->post('remarks')
                    ], ['ecom_order_id' => $order['id'], 'delivery_boy_user_id' => $token_data->id, 'id' => $this->input->post('delivery_job_id')]);
                    if($is_updated){
                        $this->user_model->updateDeliveryBoyEarningWallet($token_data->id);
                        $this->user_model->updateVendorEarningWallet($order['vendor_user_id']);
                        // Trigger notification
                        $this->send_notification($token_data->id, USER_APP_CODE, 'Delivery alert', "Your order(track id: ".$order['track_id'].") has been delivered successfully", ['order_id' => $order['id'], 'notification_type' => $this->notification_type_model->where(['app_details_id' => USER_APP_CODE, 'notification_code' => 'OD'])->get()]);
                        $this->send_notification($order['vendor_user_id'], VENDOR_APP_CODE, 'Delivery alert', "order(track id: ".$order['track_id'].") has been delivered successfully to the user", ['order_id' => $order['id'], 'notification_type' => $this->notification_type_model->where(['app_details_id' => VENDOR_APP_CODE, 'notification_code' => 'OD'])->get()]);
                        // wallet money distribution
                        if(! empty($order['payment']) && $order['payment']['payment_method_id'] == 1 && $order['payment']['status'] !=2){
                            $txn_id = 'NC-'.generate_trasaction_no();
                            // $amount = floatval($order['total']);
                            $delivery_fee = floatval($order['delivery_fee']);
                            // $amount = floatval($order['total']) - $delivery_fee;
                            $amount = floatval($order['total']);
                            $this->user_model->payment_update($this->config->item('super_admin_user_id'), $amount, 'CREDIT', "wallet", $txn_id, $order['id']);
                            // $txn_id = 'NC-'.generate_trasaction_no();
                            // $this->user_model->payment_update($token_data->id, $delivery_fee, 'CREDIT', "wallet", $txn_id, $order['id']);
                            $txn_id = 'NC-'.generate_trasaction_no();
                            $this->user_model->payment_update($token_data->id, floatval($amount), 'CREDIT', "floating_wallet", $txn_id, $order['id']);
                            $this->ecom_payment_model->update([
                                'id' => $order['payment']['id'],
                                'status' => 2
                            ], 'id');
                        }else {
                            $txn_id = 'NC-'.generate_trasaction_no();
                            $delivery_fee = floatval($order['delivery_fee']);
                            $this->user_model->payment_update($this->config->item('super_admin_user_id'), $delivery_fee, 'DEBIT', "wallet", $txn_id, $order['id']);
                            $txn_id = 'NC-'.generate_trasaction_no();
                            $this->user_model->payment_update($token_data->id, $delivery_fee, 'CREDIT', "wallet", $txn_id, $order['id']);
                            // $amount = floatval($orderDetails['total']) - $delivery_fee;
                            // $this->user_model->debitFromFloatingWallet($token_data->id, $amount, $orderID);
                            $this->set_response_simple(NULL, 'Success.', REST_Controller::HTTP_OK, TRUE);
                        }
                        // Add amount to executive by giving reference to user Starting

                        // check executive_user_id is exist or not and first_order_id is empty or not  in users table.
                        if(!empty($order['customer']['executive_user_id']) && empty($order['customer']['first_order_id'])) {
                            // check the user's order is free delivery or not.
                            if(!empty($order['delivery_fee'])) {
                                // update users first_order_id, first_order_at
                                $this->user_model->update([
                                    'first_order_id' => $order['id'],
                                    'first_order_at' => date('Y-m-d H:i:s'),
                                    'is_executive_referral_amount_added' => true
                                ], ['id' => $order['customer']['id']]);
                            }
                        }  
                        
                        
                        // Get the delivery boy address
                        $delivery_boy_address = $this->Delivery_boy_address_model->fields('id,user_id,executive_user_id,target_given_count,target_achieved_count,is_target_achieved')->where('user_id',$token_data->id)->get();

                        

                        // Check delivery boy reached the target or not if he is referral user

                        if(!empty($delivery_boy_address['executive_user_id']) && empty($delivery_boy_address['is_target_achieved'])) {
                            
                            $is_target_achieved = $delivery_boy_address['is_target_achieved'];
                            $target_achieved_at = null;
                            $is_executive_referral_amount_added = false;
                            // Get Current achieved count
                            $current_target_achieved_count = $delivery_boy_address['target_achieved_count'] ? $delivery_boy_address['target_achieved_count'] : 0;
                            // Increase the current target achieved count by 1
                            $current_target_achieved_count += 1;
                            $target_given_count = $delivery_boy_address['target_given_count'];
                            // Update the is_target_achieved value to true if current and targent counts are equal
                            if($current_target_achieved_count == $target_given_count) {
                                $is_target_achieved = true;
                                $target_achieved_at = date('Y-m-d H:i:s');
                                $is_executive_referral_amount_added = true;
                            }                            

                            $this->Delivery_boy_address_model->update([
                                'target_achieved_count' => $current_target_achieved_count,
                                'is_target_achieved' => $is_target_achieved,
                                'target_achieved_at' => $target_achieved_at,
                                'is_executive_referral_amount_added' => $is_executive_referral_amount_added
                            ], ['user_id' => $token_data->id]);

                        }                        

                        $this->set_response_simple(NULL, 'Success.', REST_Controller::HTTP_OK, TRUE);
                    }else {
                        $this->set_response_simple(NULL, 'Something went wrong.', REST_Controller::HTTP_OK, FALSE);
                    }
                }else {
                    $this->set_response_simple(NULL, 'Invalid attempt.', REST_Controller::HTTP_OK, FALSE);
                }
            }else {
                $this->set_response_simple(NULL, 'No order found.!', REST_Controller::HTTP_OK, FALSE);
            }
        }elseif ($type == 'deliver_pickuporder'){
            $order = $this->pickup_orders_model->fields('id, track_id, order_delivery_otp, delivery_fee')->with_customer('fields: id, email, phone, unique_id, passcode')->with_payment('fields: id, payment_method_id')->where('id', $this->input->post('order_id'))->get();
            $is_valid = 0;
            if(! empty($order)){
                if($order['order_delivery_otp'] == $this->input->post('otp')){
                    $is_valid = 1;
                }elseif (
                    // strtolower($order['customer']['email']) == strtolower($this->input->post('passcode')) || 
                    // strtolower($order['customer']['phone']) == strtolower($this->input->post('passcode')) || 
                    ( $this->input->post('passcode') && !empty($order['customer']['passcode']) && strtolower($order['customer']['passcode']) === strtolower($this->input->post('passcode')))){
                    $is_valid = 1;
                }
                if($is_valid === 1){
                    $is_updated = $this->delivery_job_model->update([
                        'pickup_order_id' => $order['id'],
                        'delivery_boy_user_id' => $token_data->id,
                        'status' => 508,
                        'amount_collected' => $this->input->post('amount_collected'),
                        'remarks' => $this->input->post('remarks')
                    ], ['pickup_order_id' => $order['id'], 'delivery_boy_user_id' => $token_data->id, 'id' => $this->input->post('delivery_job_id')]);
					$order_details = $this->pickup_orders_model->fields('id, track_id, order_pickup_otp,delivery_fee, created_user_id')
                        ->with_payment('fields: id, payment_method_id, amount, status')
                        ->where('id', $order['id'])
                        ->get();
					$notify_users = [
                                        $order_details['created_user_id']
                                    ];
                    if($is_updated){
                        // Trigger notification
                        $this->send_notification_pickupanddrop($notify_users, USER_APP_CODE, 'Delivery alert', "Your pickup and drop order(track id: ".$order['track_id'].") has been delivered successfully", ['pickup_order_id' => $order['id'], 'notification_type' => $this->notification_type_model->where(['app_details_id' => USER_APP_CODE, 'notification_code' => 'OD'])->get()]);
                        $this->send_notification_pickupanddrop($order['vendor_user_id'], DELIVERY_APP_CODE, 'Delivery alert', "pickup and drop order(track id: ".$order['track_id'].") has been delivered successfully to the user", ['pickup_order_id' => $order['id'], 'notification_type' => $this->notification_type_model->where(['app_details_id' => DELIVERY_APP_CODE, 'notification_code' => 'OD'])->get()]);
                        // wallet money distribution
                        if(! empty($order['payment']) && $order['payment']['payment_method_id'] == 1 && $order['payment']['status'] !=2){
                            $txn_id = 'NC-'.generate_trasaction_no();
                            // $amount = floatval($order['total']);
                            $delivery_fee = floatval($order['delivery_fee']);
                            $amount = /*floatval($order['total']) - */$delivery_fee;//no profit for nextclick now
                            $this->user_model->payment_update($this->config->item('super_admin_user_id'), $amount, 'CREDIT', "wallet", $txn_id, $order['id']);
                            // $txn_id = 'NC-'.generate_trasaction_no();
                            // $this->user_model->payment_update($token_data->id, $delivery_fee, 'CREDIT', "wallet", $txn_id, $order['id']);
                            $txn_id = 'NC-'.generate_trasaction_no();
                            $this->user_model->payment_update($token_data->id, floatval($amount), 'CREDIT', "floating_wallet", $txn_id, $order['id']);
                            $this->ecom_payment_model->update([
                                'id' => $order['payment']['id'],
                                'status' => 2
                            ], 'id');
                        }else {
                            $txn_id = 'NC-'.generate_trasaction_no();
                            $delivery_fee = floatval($order['delivery_fee']);
                            $this->user_model->payment_update($this->config->item('super_admin_user_id'), $delivery_fee, 'DEBIT', "wallet", $txn_id, $order['id']);
                            $txn_id = 'NC-'.generate_trasaction_no();
                            $this->user_model->payment_update($token_data->id, $delivery_fee, 'CREDIT', "wallet", $txn_id, $order['id']);
                            // $amount = floatval($orderDetails['total']) - $delivery_fee;
                            // $this->user_model->debitFromFloatingWallet($token_data->id, $amount, $orderID);
                            $this->set_response_simple(NULL, 'Success.', REST_Controller::HTTP_OK, TRUE);
                        }
                        $this->set_response_simple(NULL, 'Success.', REST_Controller::HTTP_OK, TRUE);
                    }else {
                        $this->set_response_simple(NULL, 'Something went wrong.', REST_Controller::HTTP_OK, FALSE);
                    }
                }else {
                    $this->set_response_simple(NULL, 'Invalid attempt.', REST_Controller::HTTP_OK, FALSE);
                }
            }else {
                $this->set_response_simple(NULL, 'No order found.!', REST_Controller::HTTP_OK, FALSE);
            }
        }elseif ($type == 'picked'){
            $this->delivery_job_model->update([
                'id' => $this->input->post('delivery_job_id'),
                'status' => 505,
            ], 'id');
            // In Ideal case Amount should be added to delivery boy floating wallet here
            //$amount = floatval($orderDetails['total']) - floatval($orderDetails['delivery_fee']);
            //$this->user_model->creditToFloatingWallet($token_data->id, $amount, $orderID);
            $this->set_response_simple(NULL, 'Success.', REST_Controller::HTTP_OK, TRUE);
        }elseif ($type == 'picked_pickuporder'){
			
            $order_id = $this->input->post('order_id');
            if (! empty($order_id)) {
                $order_details = $this->pickup_orders_model->fields('id, track_id, order_pickup_otp,delivery_fee, created_user_id')
                        ->with_payment('fields: id, payment_method_id, amount, status')
                        ->where('id', $order_id)
                        ->get();
			    if (! empty($order_details)) {
                    if ($order_details['order_pickup_otp'] == $this->input->post('otp')) {
						
                                $is_updated = $this->pickup_orders_model->update([
                                    'id' => $order_id,
                                    'order_status_id' => $this->ecom_order_status_model->fields('id')
                                        ->where([
                                        'delivery_mode_id' => 2,
                                        'serial_number' => 103
                                    ])->get()['id']
                                ], 'id');

                                if ($is_updated) {
                                    /*
                                     * $delivery_job = $this->delivery_job_model->where(['ecom_order_id' => $order_id, 'status >=' => 501])->get();
                                     * $this->delivery_job_model->update([
                                     * 'id' => $delivery_job['id'],
                                     * 'status' => 505,
                                     * ], 'id');
                                     */
                                    $notify_users = [
                                        $order_details['created_user_id']
                                    ];
                                    /*
                                     * if(! empty($delivery_job))
                                     * array_push($notify_users, $delivery_job['delivery_boy_user_id']);
                                     */
                                    // Tringger notification
                                    $this->send_notification_pickupanddrop($notify_users, USER_APP_CODE, "Order status of( " . $order_details['track_id'] . " )", "Your order is out for delivery by " . strtoupper($order_details['vendor']['name']) . ".", [
                                        'pickup_order_id' => $order_id,
                                        'notification_type' => $this->notification_type_model->where([
                                            'app_details_id' => USER_APP_CODE,
                                            'notification_code' => 'OD'
                                        ])
                                            ->get()
                                    ]);
#                                    								 echo  $this->db->last_query();
#						print_r($order_details);exit;
                                    // trigger push notification to user//
        
                                    $this->send_notification_pickupanddrop($notify_users, USER_APP_CODE, "Delivery Boy Pickup the order ", "And heading to your delivery location", [
                                        'pickup_order_id' => $order_id,
                                        'notification_type' => $this->notification_type_model->where([
                                            'app_details_id' => USER_APP_CODE,
                                            'notification_code' => 'OD'
                                        ])
                                            ->get()
                                    ]);
									//echo  $this->db->last_query();
									//print_r($order_details);exit;
                                    $this->set_response_simple(NULL, $this->ecom_order_status_model->fields('status')
                                        ->where([
                                        'delivery_mode_id' => 2,
                                        'serial_number' => 103
                                    ])
                                        ->get()['status'], REST_Controller::HTTP_OK, TRUE);

                                     $this->delivery_job_model->update([
                                            'id' => $this->input->post('delivery_job_id'),
                                            'status' => 505,
                                        ], 'id');
                                        // In Ideal case Amount should be added to delivery boy floating wallet here
                                        //$amount = floatval($orderDetails['total']) - floatval($orderDetails['delivery_fee']);
                                        //$this->user_model->creditToFloatingWallet($token_data->id, $amount, $orderID);
                                        $this->set_response_simple(NULL, 'Success.', REST_Controller::HTTP_OK, TRUE);

                                } else {
                                    $this->set_response_simple(NULL, 'Something went wrong.!', REST_Controller::HTTP_OK, FALSE);
                                }
                            } else {
                                $this->set_response_simple(NULL, 'Invalid OTP.!', REST_Controller::HTTP_OK, FALSE);
                            }
                        } else {
                            $this->set_response_simple(NULL, 'Not found.', REST_Controller::HTTP_OK, FALSE);
                        }        

                
            }
            else {
                $this->set_response_simple(NULL, 'Something went wrong.!', REST_Controller::HTTP_OK, FALSE);
            }
        }
        elseif ($type == 'reject'){
            $order_id = $this->input->post('order_id');
            $delivery_job_id = $this->input->post('delivery_job_id');
            $rejected_reason_id = $this->input->post('rejected_reason_id');
            $rejected_reason = $this->delivery_job_rejected_reason_model->get([
                "id" => $rejected_reason_id
            ]);
            $deliveryJob= $this->delivery_job_model->get([
                'id' => $delivery_job_id
            ]);
            if(! empty($orderDetails)){
                if(! empty($rejected_reason_id)){
                        $newStatus= null;
                        if($deliveryJob['status']>=505){
                            $newStatus = 500;
                        }
						if($deliveryJob['status'] == 502 || $deliveryJob['status'] == 504) {
							$newStatus = 503;

						}
						else {
                            $newStatus = 501;
                        }
						if($newStatus = 501) {
							$delivery_user_id = null;
						}else {
							$delivery_user_id = $deliveryJob['delivery_boy_user_id'];
						}
                        $is_updated = $this->delivery_job_model->update([
                            'id' => $delivery_job_id,
                            'rejected_reason_id' => $rejected_reason_id,
                            'delivery_boy_user_id'=> $delivery_user_id ,
                            'status' => ($rejected_reason_id == 4)? 600: $newStatus
                        ], 'id');
                        if(!empty($is_updated)){
                            if($orderDetails["order_status_id"] == 12 && $newStatus==500){
                                if($rejected_reason_id != 4){
                                    $amount = floatval($orderDetails['total']) - floatval($orderDetails['delivery_fee']);
                                    $this->user_model->creditToFloatingWallet($token_data->id, $amount, $orderID);
                                }
                            } else if($newStatus==500 || $newStatus = 503){
                                if(!($orderDetails['payment']['payment_method_id']==1 && $orderDetails['payment']['status']!=2)){
                                    $amount = floatval($orderDetails['total']);
                                    $this->user_model->debitFromWallet($this->config->item('super_admin_user_id'), $amount, $orderID);
                                }
								
                                if($orderDetails['payment']['payment_method_id']==3){
                                    $this->user_model->creditToWallet($orderDetails['created_user_id'], $orderDetails['total'], $orderID);
                                }else if ($orderDetails['payment']['payment_method_id']==2 || ($orderDetails['payment']['payment_method_id']==1 && $orderDetails['payment']['status']==2)){
                                    $this->load->module('payment/api/payment');
                                    $this->payment->initiateRefund($order_id);
                                }
								
								// order status update
								$this->ecom_order_model->update([
									'id' => $order_id,
									'order_status_id' => $this->ecom_order_status_model->fields('id')
									->where([
										'delivery_mode_id' => 1,
										'serial_number' => 303
									])
									->get()['id']
								], 'id'); 
								
                                /** trigger push notificatios **/
                                $this->send_notification_pickupanddrop($orderDetails['created_user_id'], USER_APP_CODE, "Order status of( ".$orderDetails['track_id']." )", "We're sorry to say, That your order has been cancelled due to delivery boy ".$rejected_reason['reason'].".",['order_id' => $order_id, 'notification_type' => $this->notification_type_model->where(['app_details_id' => USER_APP_CODE, 'notification_code' => 'OD'])->get()]);
								
								
								$this->send_notification_pickupanddrop($orderDetails['vendor_user_id'], VENDOR_APP_CODE, "Order status of( ".$orderDetails['track_id']." )", "We're sorry to say, That order has been cancelled due to delivery boy ".$rejected_reason['reason'].".",['order_id' => $order_id, 'notification_type' => $this->notification_type_model->where(['app_details_id' => VENDOR_APP_CODE, 'notification_code' => 'OD'])->get()]);
                            }
							
                            $this->delivery_job_rejection_model->saveRejection($delivery_job_id, $deliveryJob['status'], $token_data->id, $rejected_reason_id, $rejected_reason['reason']);
							$notification = $this->notifications_model->where('app_details_id', 4)->where('ecom_order_id', $order_id)->where('notified_user_id', $token_data->id)->get();
								$is_updated = $this->notifications_model->update([
									'id'=> $notification['id'],
									'status' => 2
								], 'id');
                            $this->load->module('delivery/api/delivery');
                            $this->delivery->saveDeliveryJobEvent($delivery_job_id, "REJECTED", $token_data->id);
                            // $amount = floatval($order['total']) - floatval($order['delivery_fee']);
                            // $txn_id = 'NC-' . generate_trasaction_no();
                            // $reason = $this->delivery_job_rejected_reason_model->where('id', $rejected_reason_id)->get();
                            // $is_transaction_updated = $this->user_model->payment_update($token_data->id, $amount, 'CREDIT', 'floating_wallet', $txn_id, $order['id'], $reason['reason']);
                            $this->set_response_simple(['is_rejected' => $is_updated, 'is_transaction_updated' => 1], 'Success.', REST_Controller::HTTP_OK, TRUE);
                        } else {
                            $this->set_response_simple(NULL, 'Failed.', REST_Controller::HTTP_OK, FALSE);
                        }
                }else {
                    $this->set_response_simple(NULL, 'Please tell us the reason.', REST_Controller::HTTP_OK, FALSE);
                }
            }else {
                $this->set_response_simple(NULL, 'Invalid order id', REST_Controller::HTTP_OK, FALSE);
            }
        }
        elseif ($type == 'pickup_order_reject'){
            $order_id = $this->input->post('order_id');
            $delivery_job_id = $this->input->post('delivery_job_id');
            $rejected_reason_id = $this->input->post('rejected_reason_id');
            $rejected_reason = $this->delivery_job_rejected_reason_model->get([
                "id" => $rejected_reason_id
            ]);
            $deliveryJob= $this->delivery_job_model->get([
                'id' => $delivery_job_id
            ]);
            $orderDetails = $this->pickup_orders_model->fields('id, track_id, order_pickup_otp,delivery_fee, created_user_id')
                    // ->with_payment('fields: id, payment_method_id, amount, status')
                    ->where('id', $order_id)
                    ->get();
            
            if(! empty($orderDetails)){
                if(! empty($rejected_reason_id)){
                        $newStatus= null;
                        if($deliveryJob['status']>=505){
                            $newStatus = 500;
                        }
						if($deliveryJob['status'] == 502 || $deliveryJob['status'] == 504) {
							$newStatus = 503;

						}
						else {
                            $newStatus = 501;
                        }
						if($newStatus = 501) {
							$delivery_user_id = null;
						}else {
							$delivery_user_id = $deliveryJob['delivery_boy_user_id'];
						}
                        $is_updated = $this->delivery_job_model->update([
                            'id' => $delivery_job_id,
                            'rejected_reason_id' => $rejected_reason_id,
                            'delivery_boy_user_id'=> $delivery_user_id ,
                            'status' => ($rejected_reason_id == 4)? 600: $newStatus
                        ], 'id');
                        if(!empty($is_updated)){
                            // order status update
								$this->pickup_orders_model->update([
									'id' => $order_id,
									'order_status_id' => $this->ecom_order_status_model->fields('id')
									->where([
										'delivery_mode_id' => 2,
										'serial_number' => 303
									])
									->get()['id']
								], 'id'); 
								
                                /** trigger push notificatios **/
                                $this->send_notification_pickupanddrop($orderDetails['created_user_id'], USER_APP_CODE, "Order status of( ".$orderDetails['track_id']." )", "We're sorry to say, That your order has been cancelled due to delivery boy ".$rejected_reason['reason'].".",['order_id' => $order_id, 'notification_type' => $this->notification_type_model->where(['app_details_id' => USER_APP_CODE, 'notification_code' => 'OD'])->get()]);
								
							
                            $this->delivery_job_rejection_model->saveRejection($delivery_job_id, $deliveryJob['status'], $token_data->id, $rejected_reason_id, $rejected_reason['reason']);
							$notification = $this->notifications_model->where('app_details_id', 4)->where('pickup_order_id', $order_id)->where('notified_user_id', $token_data->id)->get();
								$is_updated = $this->notifications_model->update([
									'id'=> $notification['id'],
									'status' => 2
								], 'id');
                            
                            $this->load->module('delivery/api/delivery');
                            $this->delivery->saveDeliveryJobEvent($delivery_job_id, "REJECTED", $token_data->id);
                            // $amount = floatval($order['total']) - floatval($order['delivery_fee']);
                            // $txn_id = 'NC-' . generate_trasaction_no();
                            // $reason = $this->delivery_job_rejected_reason_model->where('id', $rejected_reason_id)->get();
                            // $is_transaction_updated = $this->user_model->payment_update($token_data->id, $amount, 'CREDIT', 'floating_wallet', $txn_id, $order['id'], $reason['reason']);
                            $this->set_response_simple(['is_rejected' => $is_updated, 'is_transaction_updated' => 1], 'Success.', REST_Controller::HTTP_OK, TRUE);
                        } else {
                            $this->set_response_simple(NULL, 'Failed.', REST_Controller::HTTP_OK, FALSE);
                        }
                }else {
                    $this->set_response_simple(NULL, 'Please tell us the reason.', REST_Controller::HTTP_OK, FALSE);
                }
            }else {
                $this->set_response_simple(NULL, 'Invalid order id', REST_Controller::HTTP_OK, FALSE);
            }
        }

        
    }
    
    /**
     * @desc to capture delivery pickup and delivery confirmation images
     * @author mehar
     * 
     * @param string $type
     */
    public function delivery_images_post($type = 'pickup'){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if($type == 'pickup'){
            if (! empty($this->input->post('delivery_boy_pickup_image'))) {
                if (! file_exists('uploads/' . 'delivery_boy_pickup' . '_image/')) {
                    mkdir('uploads/' . 'delivery_boy_pickup' . '_image/', 0777, true);
                }
                file_put_contents("./uploads/delivery_boy_pickup_image/delivery_boy_pickup_" . $this->input->post('delivery_job_id') . ".jpg", base64_decode($this->input->post('delivery_boy_pickup_image')));
                
                if (! file_exists(base_url() ."uploads/delivery_boy_pickup_image/delivery_boy_pickup_" . $this->input->post('delivery_job_id') . ".jpg")) {
                    unlink("./uploads/delivery_boy_pickup_image/delivery_boy_pickup_" . $this->input->post('delivery_job_id') . ".jpg");
                    file_put_contents("./uploads/delivery_boy_pickup_image/delivery_boy_pickup_" . $this->input->post('delivery_job_id') . ".jpg", base64_decode($this->input->post('delivery_boy_pickup_image')));
                }
                $this->set_response_simple(NULL, 'Success.', REST_Controller::HTTP_OK, TRUE);
            }else {
                $this->set_response_simple(NULL, 'Please send Pickup image.', REST_Controller::HTTP_OK, FALSE);
            }
        }elseif ($type == 'delivery'){
            if (! empty($this->input->post('delivery_boy_delivery_image'))) {
                if (! file_exists('uploads/' . 'delivery_boy_delivery' . '_image/')) {
                    mkdir('uploads/' . 'delivery_boy_delivery' . '_image/', 0777, true);
                }
                file_put_contents("./uploads/delivery_boy_delivery_image/delivery_boy_delivery_" . $this->input->post('delivery_job_id') . ".jpg", base64_decode($this->input->post('delivery_boy_delivery_image')));

                if (! file_exists(base_url() ."uploads/delivery_boy_delivery_image/delivery_boy_delivery_" . $this->input->post('delivery_job_id') . ".jpg")) {
                    unlink("./uploads/delivery_boy_delivery_image/delivery_boy_delivery_" . $this->input->post('delivery_job_id') . ".jpg");
                    file_put_contents("./uploads/delivery_boy_delivery_image/delivery_boy_delivery_" . $this->input->post('delivery_job_id') . ".jpg", base64_decode($this->input->post('delivery_boy_delivery_image')));
                }
                $this->set_response_simple(NULL, 'Success.', REST_Controller::HTTP_OK, TRUE);
            }else {
                $this->set_response_simple(NULL, 'Please send delivery image.', REST_Controller::HTTP_OK, FALSE);
            }
        }
    }
    
    /**
     * @desc To get list of rejected resons for a delivery boy
     * @author Mehar
     */
    public function delivery_rejected_reasons_get(){
        $reasons = $this->delivery_job_rejected_reason_model->get_all();
        $this->set_response_simple($reasons, 'List of rejected order reasons.', REST_Controller::HTTP_OK, TRUE);
    }
}
