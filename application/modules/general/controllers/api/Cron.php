<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Cron extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ecom_order_model');
        $this->load->model('ecom_order_deatils_model');
        $this->load->model('ecom_payment_model');
        $this->load->model('ecom_order_status_model');
        $this->load->model('ecom_order_status_log_model');
        $this->load->model('ecom_order_reject_request_model');
        $this->load->model('setting_model');
		$this->load->model('vendor_package_model');
        $this->load->model('user_model');
        $this->load->model('notification_type_model');
        $this->load->model('delivery_job_model');
        $this->load->model('delivery_job_rejection_model');
    }
    
	/**
     * @author Mehar
     * To check the vendor subscription before 1 day 
     */
	 public function vendor_subscription_one_day_get() {
		 
		 $vendors = $this->user_model->fields('id')->where('primary_intent', 'vendor')->get_all();
		 if($vendors) {
			 foreach($vendors as $key => $vendor)
			 {
				$service_id = 2;
				$list_vendor_packages = $this->vendor_package_model->fields('id,service_id,package_id,created_at, status')->with_packages('fields: id, title,desc,days,display_price,price')->where(['created_user_id' => $vendor['id'], 'service_id' => $service_id, 'status'=>1])->get_all(); 
			
					if($list_vendor_packages) {
						foreach ($list_vendor_packages as $k => $package) {
							$validity = date('Y-m-d H:i:s', strtotime(($list_vendor_packages[$k]['created_at']) . "+" . $list_vendor_packages[$k]['packages']['days'] . "days"));
							$expiry_date=date('Y-m-d',strtotime($validity));
							$before_date=date('Y-m-d', strtotime('-1 day', strtotime($validity)));
							$today_date = date('Y-m-d');
							if($today_date == $before_date) {
								$this->send_notification($vendor['id'], VENDOR_APP_CODE, "Your subscription will end by tomorrow", "Please subscribe to plan by today", [
											'notification_type' => $this->notification_type_model->where([
												'app_details_id' => VENDOR_APP_CODE,
												'notification_code' => 'SUBS'
											])->get()
										]);
							}
						}
					}
			 } 
		 }
		 
	 }
	 
	 /**
     * @author Mehar
     * To check the vendor subscription end and update status 
	 */
	 public function vendor_subscription_end_get() {
		 echo date('Y-m-d H:i:s');
		$vendors = $this->user_model->fields('id')->where('primary_intent', 'vendor')->get_all();
		 if($vendors) {		 
			 foreach($vendors as $key => $vendor)
			 {
				$service_id = 2;
				$list_vendor_packages = $this->vendor_package_model->fields('id,service_id,package_id,created_at, status')->with_packages('fields: id, title,desc,days,display_price,price')->where(['created_user_id' => $vendor['id'], 'service_id' => $service_id, 'status'=>1])->get_all(); 
				if($list_vendor_packages) {
					foreach ($list_vendor_packages as $k => $package) {
						$validity = date('Y-m-d H:i:s', strtotime(($list_vendor_packages[$k]['created_at']) . "+" . $list_vendor_packages[$k]['packages']['days'] . "days"));
						
						$expiry_date=date('Y-m-d',strtotime($validity));
						$today_date = date('Y-m-d');
						if($today_date == $expiry_date) {
							$this->vendor_package_model->update([
								'id' => $list_vendor_packages[$k]['id'],
								'status' => 2,
							], 'id');
							
							$this->send_notification($vendor['id'], VENDOR_APP_CODE, "Your subscription has been ended ", "Please do subscribe resume the services", [
										'notification_type' => $this->notification_type_model->where([
											'app_details_id' => VENDOR_APP_CODE,
											'notification_code' => 'SUBS'
										])->get()
							]);
						}
					}	
				}
			 }
		 }
		 
	 }
	 
	 /**
     * @author Mehar
     * To check the vendor subscription before 3 day 
     */
	 public function vendor_subscription_three_day_get() {
		 
		 $vendors = $this->user_model->fields('id')->where('primary_intent', 'vendor')->get_all();
		 if($vendors) {	
			 foreach($vendors as $key => $vendor)
			 {
				$service_id = 2;
				$list_vendor_packages = $this->vendor_package_model->fields('id,service_id,package_id,created_at, status')->with_packages('fields: id, title,desc,days,display_price,price')->where(['created_user_id' => $vendor['id'], 'service_id' => $service_id, 'status'=>1])->get_all(); 
				
				if($list_vendor_packages) {
					foreach ($list_vendor_packages as $k => $package) {
						$validity = date('Y-m-d H:i:s', strtotime(($list_vendor_packages[$k]['created_at']) . "+" . $list_vendor_packages[$k]['packages']['days'] . "days"));
						$expiry_date=date('Y-m-d',strtotime($validity));
						$before_date=date('Y-m-d', strtotime('-3 day', strtotime($validity)));
						$today_date = date('Y-m-d');
						if($today_date == $before_date) {
							$this->send_notification($vendor['id'], VENDOR_APP_CODE, "Your subscription will end with 3 days ", "Please renewal subscription to plan as soon as possible", [
										'notification_type' => $this->notification_type_model->where([
											'app_details_id' => VENDOR_APP_CODE,
											'notification_code' => 'SUBS'
										])->get()
									]);
						}
					}
				 }
			 }
		 }
	 }
	 
	 
	 /**
     * @author Mehar
     * To check the vendor subscription before one week day 
     */
	 public function vendor_subscription_one_week_get() {
		 
		$vendors = $this->user_model->fields('id')->where('primary_intent', 'vendor')->get_all();
		 if($vendors) {			 
			 foreach($vendors as $key => $vendor)
			 {
				$service_id = 2;
				$list_vendor_packages = $this->vendor_package_model->fields('id,service_id,package_id,created_at, status')->with_packages('fields: id, title,desc,days,display_price,price')->where(['created_user_id' => $vendor['id'], 'service_id' => $service_id, 'status'=>1])->get_all(); 
				if($list_vendor_packages) {
					foreach ($list_vendor_packages as $k => $package) {
						 $validity = date('Y-m-d H:i:s', strtotime(($list_vendor_packages[$k]['created_at']) . "+" . $list_vendor_packages[$k]['packages']['days'] . "days"));
						  $expiry_date=date('Y-m-d',strtotime($validity));
						$before_date=date('Y-m-d', strtotime('-7 day', strtotime($validity)));
						$today_date = date('Y-m-d');
						if($today_date == $before_date) {
							$this->send_notification($vendor['id'], VENDOR_APP_CODE, "Your subscription will end with one week ", "Please renewal subscription to plan early", [
										'notification_type' => $this->notification_type_model->where([
											'app_details_id' => VENDOR_APP_CODE,
											'notification_code' => 'SUBS'
										])->get()
									]);
						}
					}
				}
			 }
		 }
	 }
	 
    /**
     * @author Mehar
     * To confirm an order after certain time if customer does not respond on the request
     */
    public function order_confirmation_after_vendor_rejection_get(){
        $order_confirmation_time = $this->setting_model->where('key','order_confirmation_time')->get()['value'];
        $latest_requests = $this->db->query("
            select * from ecom_order_reject_requests as eorr
            where eorr.status = 1 and eorr.created_at <= CONVERT_TZ(now(),'+00:00','+05:30') - INTERVAL $order_confirmation_time MINUTE
        ")->result_array();
        if(! empty($latest_requests)){foreach ($latest_requests as $key => $request){
            $order_details = $this->ecom_order_model
            ->fields('id, track_id, delivery_mode_id, created_user_id, vendor_user_id, total')
            ->with_payment('fields: id, payment_method_id, amount, status')
            ->with_vendor('fields: id, name')
            ->with_ecom_order_details('fields: id, ecom_order_id, item_id, vendor_product_variant_id, qty, total, cancellation_message, status', 'where: status = 4')
            ->where('id', $request['ecom_order_id'])
            ->get();
            if(! empty($order_details['ecom_order_details'])){
                $sum_of_rejcted_products_amount = array_sum(array_column($order_details['ecom_order_details'], 'total'));
                $final_total = floatval($order_details['total']) - floatval($sum_of_rejcted_products_amount);
                $is_order_updated = $this->ecom_order_model->update([
                    'id' => $order_details['id'],
                    'total' => $final_total
                ], 'id');
                if($is_order_updated){
                    $this->ecom_order_reject_request_model->update([
                        'id' => $request['id'],
                        'status' => 2
                    ], 'id');
                    if($order_details['payment']['payment_method_id'] == 1){
                        $this->ecom_payment_model->update([
                            'id' => $order_details['payment']['id'],
                            'amount' => $final_total
                        ], 'id');
                    }elseif ($order_details['payment']['payment_method_id'] == 2){
                        $this->ecom_payment_model->update([
                            'id' => $order_details['payment']['id'],
                            'amount' => $final_total
                        ], 'id');
                        $this->load->module('payment/api/payment');
                        $this->payment->initiateRefund($request['ecom_order_id'], TRUE, $sum_of_rejcted_products_amount);
                    }elseif ($order_details['payment']['payment_method_id'] == 3){
                        $txn_id = 'NC-' . generate_trasaction_no();
                        $amount = floatval($sum_of_rejcted_products_amount);
                        $this->user_model->payment_update($this->config->item('super_admin_user_id'), $amount, 'DEBIT', "wallet", $txn_id, $order_id, "Refund on(".$order_details['track_id'].")");
                        $txn_id = 'NC-' . generate_trasaction_no();
                        $amount = floatval($sum_of_rejcted_products_amount);
                        $this->user_model->payment_update($order_details['created_user_id'], $amount, 'CREDIT', "wallet", $txn_id, $order_id, "Refund on(".$order_details['track_id'].")");
                    }
                    
                    $this->send_notification($order_details['vendor_user_id'], VENDOR_APP_CODE, "Order Alert", "Congratulations!  Order reject request of(id:" . $order_details['track_id'] . ") has been accepted by user.", [
                        'order_id' => $request['ecom_order_id'],
                        'notification_type' => $this->notification_type_model->where([
                            'app_details_id' => VENDOR_APP_CODE,
                            'notification_code' => 'OD'
                        ])->get()
                    ]);
                }
                $this->set_response_simple($request, 'Success.!', REST_Controller::HTTP_OK, TRUE);
            }else {
                $this->set_response_simple(NULL, 'Invalid Order id.!', REST_Controller::HTTP_OK, FALSE);
            }
        }}
        $this->set_response_simple($latest_requests, 'Success.!', REST_Controller::HTTP_OK, TRUE);
    }
    
    /**
     * @author Mehar
     * To cancel an order after certain time if vendor does not respond 
     */
    public function order_auto_cancellation_get(){
        $order_cancellation_time = $this->setting_model->where('key','order_cancellation_time')->get()['value'];
        $idle_orders = $this->db->query("
            SELECT eo.id, eo.created_at, eo.order_status_id FROM `ecom_order_statuses` as eos
            JOIN ecom_orders as eo on eos.id = eo.order_status_id  and eos.serial_number = 100
            where eo.created_at <= CONVERT_TZ(now(),'+00:00','+05:30') - INTERVAL $order_cancellation_time MINUTE
            order by eo.id
        ")->result_array();
        if(! empty($idle_orders)){ foreach ($idle_orders as $key => $order){
            $order_id = $order['id'];
            if (! empty($order_id)) {
                $order_details = $this->ecom_order_model->fields('id,track_id,vendor_user_id, delivery_mode_id, total,created_user_id')
                ->with_payment('fields: id, payment_method_id, amount, status, created_user_id')
                ->where('id', $order_id)
                ->get();
                if($order_details){
                    if($order_details['payment']['payment_method_id']!=1 || ($order_details['payment']['payment_method_id']==1 &&  $order_details['payment']['status'] ==2)){
                        $this->user_model->debitFromWallet($this->config->item('super_admin_user_id'), $order_details["total"], $order_id);
                    }
                    if($order_details['payment']['payment_method_id']==3){
                        $this->user_model->creditToWallet($order_details['payment']['created_user_id'], $order_details['total'],$order_id);
                    }else if ($order_details['payment']['payment_method_id']==2 || ($order_details['payment']['payment_method_id']==1 && $order_details['payment']['status']==2)){
                        $this->load->module('payment/api/payment');
                        $this->payment->initiateRefund($order_id);
                    }
                }
                $is_delivery_job_started = $this->delivery_job_model->where([
                    'ecom_order_id' => $order_id,
                ])->get();
                if (! empty($order_details) && (empty($is_delivery_job_started) || $is_delivery_job_started['status']<502)) {
                    $is_updated = $this->ecom_order_model->update([
                        'id' => $order_id,
                        'order_status_id' => $this->ecom_order_status_model->fields('id')
                        ->where([
                            'delivery_mode_id' => 1,
                            'serial_number' => 301
                        ])
                        ->get()['id']
                    ], 'id');
                    if(!empty($is_delivery_job_started)){
                        $job_update = $this->delivery_job_model->update([
                            'id' => $is_delivery_job_started['id'],
                            'status' => 500
                        ], 'id');
                        $notificationType = $this->notification_type_model->where(['app_details_id' => DELIVERY_APP_CODE, 'notification_code' => 'OD'])->get();
                        $this->invalidate_notification("ORDER", $notificationType['id'], DELIVERY_APP_CODE, $order_id);
                    }
                    if ($is_updated) {
                        $this->set_response_simple(NULL, 'Order has been cancelled.', REST_Controller::HTTP_OK, TRUE);
                        /**
                         * trigger push notificatios *
                         */
                        $this->send_notification($order_details['payment']['created_user_id'], USER_APP_CODE, "Order Alert", "Sorry! Your Order(id:" . $order_details['track_id'] . ") got rejected automatically, please try with another vendor",['order_id' => $order_id, 'notification_type' => $this->notification_type_model->where(['app_details_id' => USER_APP_CODE, 'notification_code' => 'OD'])->get()]);

                        
                        $this->send_notification($order_details['vendor_user_id'], VENDOR_APP_CODE, "Order Alert", "Sorry! Your Order(id:" . $order_details['track_id'] . ") got rejected automatically.", [
                            'order_id' => $order_id,
                            'notification_type' => $this->notification_type_model->where([
                                'app_details_id' => VENDOR_APP_CODE,
                                'notification_code' => 'OD'
                            ])->get()
                        ]);
                    } else {
                        $this->set_response_simple(NULL, 'Something went wrong.!', REST_Controller::HTTP_OK, FALSE);
                    }
                } else {
                    $this->set_response_simple(NULL, 'You can not cancel the order at this moment', REST_Controller::HTTP_OK, FALSE);
                }
            } else {
                $this->set_response_simple(NULL, 'Please provide order_id.', REST_Controller::HTTP_OK, FALSE);
            }
        }}
        $this->set_response_simple(NULL, 'Success.!', REST_Controller::HTTP_OK, TRUE);
    }
    
    /**
     * @desc this cron is used to confirm that customer is not available.
     * @author Mehar
     */
    public function auto_confirm_a_delivery_reject_request_get(){
        $order_confirmation_time = $this->setting_model->where('key','order_confirmation_time')->get()['value'];
        $idle_requests = $this->db->query("
            SELECT djr.id, djr.created_at FROM `delivery_job_rejections` as djr
            where djr.created_at <= (CONVERT_TZ(now(),'+00:00','+05:30') - INTERVAL $order_confirmation_time MINUTE) and status = 0
            order by djr.id
        ")->result_array();
        if(! empty($idle_requests)){ foreach ($idle_requests as $key => $idle_request){
            $data = $this->delivery_job_rejection_model->accept($idle_request['id'], 'system');
            if($data['success'] ==  TRUE){
                return $this->send_notification($data['rejection_request']['rejected_by'], DELIVERY_APP_CODE, "Order status of( " . $data['job']['order']['track_id'] . " )", "Congrats, Reject request has been accepted", [
                    'order_id' => $data['job']['order']['id'],
                    'notification_type' => $this->notification_type_model->where([
                        'app_details_id' => DELIVERY_APP_CODE,
                        'notification_code' => 'OD'
                    ])->get()
                ]);
                $this->set_response_simple(NULL, $data['job'], REST_Controller::HTTP_OK, TRUE);
            }else {
                $this->set_response_simple(NULL, $data['error'], REST_Controller::HTTP_OK, FALSE);
            }
        }}else {
            $this->set_response_simple(NULL, "No requests found!", REST_Controller::HTTP_OK, TRUE);
        }
    }

}

