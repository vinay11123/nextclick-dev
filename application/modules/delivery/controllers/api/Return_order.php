<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Return_order extends MY_REST_Controller
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
        $this->load->model('ecom_payment_model');
        $this->load->model('delivery_job_model');
        $this->load->model('ecom_order_status_model');
        $this->load->model('notification_type_model');
        $this->load->model('delivery_job_rejected_reason_model');
        $this->load->model('notifications_model');
        $this->load->model('delivery_job_rejection_model');
        $this->load->model('ecom_order_reject_request_model');
        $this->load->model('vendor_product_variant_model');
        $this->load->model('ecom_order_deatils_model');
        $this->load->model('setting_model');
    }
    
    public function delivery_boy_return_post($type = 'reched'){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);       
		
		$_POST = json_decode(file_get_contents("php://input"), TRUE);
        if($type == 'reched'){
            $this->delivery_job_model->update([
                'id' => $this->input->post('delivery_job_id'),
                'status' => 602 
            ], 'id');
            return $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
        }elseif ($type == 'retured_the_order'){
            $otp = $this->input->post('order_return_otp');
            $order_id = $this->input->post('order_id');
            $delivery_job_id = $this->input->post('delivery_job_id');
            $delivery_job_id = $this->input->post('products');
            $delivery_job = $this->delivery_job_model->where('id', $delivery_job_id)->get();
            $order = $this->ecom_order_model
            ->with_ecom_order_details('fields: id, ecom_order_id, promocode_id, promotion_banner_id, item_id, vendor_product_variant_id, qty, offer_product_id, offer_product_variant_id, offer_product_qty, price, rate_of_discount, sub_total, discount, promocode_discount, promotion_banner_discount, tax, total, cancellation_message, status')
            ->where('id', $order_id)->get();
            if(! empty($order) && $order['order_return_otp'] == $otp){
                $extra_delivery_fee = floatval(
                    ((floatval($order['delivery_fee'])) / 2)
                        + floatval($order['delivery_fee'])
                    );
                $total_service_charge = 0;
                $returned_total = 0;
                foreach ($order['ecom_order_details'] as $key => $product){
                    $variant = $this->vendor_product_variant_model->fields('id, return_available, return_id')
                    ->where('id', $product['vendor_product_variant_id'])->get();
                    if($variant['return_available'] != 0 || ! empty($variant['return_available'])){
                        $total_service_charge += floatval($product['service_charge_amount']);
                        $returned_total += floatval($product['total']);
                        $this->ecom_order_deatils_model->update([
                            'id' => $product['id'],
                            'status' => 5
                        ], 'id');
                    }
                    
                    
                }
                
                // total service charge - returned items service charge
                $actual_service_charge_loss = floatval($order['total_service_charge']) - floatval($total_service_charge);
                $loss_total = floatval($extra_delivery_fee) + floatval($actual_service_charge_loss);
                
                
                
                // deduct service charge from incom (total along with non returned)
                $this->user_model->debitFromIncomeWallet($this->config->item('super_admin_user_id', 'ion_auth'),floatval($order['total_service_charge']), $order_id);
                
                // add Admin loss
                $this->user_model->creditToFloatingWallet($this->config->item('super_admin_user_id', 'ion_auth'),$loss_total, $order_id);
                
                // deduct returend order cost from vendor
                $this->user_model->debitFromWallet($order['vendor_user_id'], $returned_total, $order['id']);
                
                // add user penalty
                $rateOfPenalty = $this->setting_model->where('key','customer_penalty_in_percentage')->get()['value'];
                $userPenalty = $order['total'] * ($rateOfPenalty / 100);
                $this->user_model->debitFromWallet($order['created_user_id'], $userPenalty, $order['id']);
                
                // delivery boy fee
                $this->user_model->creditToWallet($delivery_job['delivery_boy_user_id'], $extra_delivery_fee, $order_id);
                
                $this->ecom_order_model->update([
                    'id' => $order['id'],
                    'is_rerturn_initiated' => 1,
                    'customer_penalty' => $userPenalty
                ], 'id');
                
                $this->delivery_job_model->update([
                    'id' => $this->input->post('delivery_job_id'),
                    'status' => 603
                ], 'id');
                $notificationType = $this->notification_type_model->where(['app_details_id' => DELIVERY_APP_CODE, 'notification_code' => 'OD'])->get();
                $orderDeliveryJob = $this->delivery_job_model->where(['ecom_order_id' => $order['id']])->get();
                $this->invalidate_notification("ORDER", $notificationType['id'], DELIVERY_APP_CODE, $orderDeliveryJob['ecom_order_id']);
                return $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }else {
                return $this->set_response_simple(NULL, 'Invalid attempt..!', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            }
        }
    }
}

