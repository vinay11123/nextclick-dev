<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Subscriptions extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('app_details_model');
        $this->load->model('package_model');
        $this->load->model('service_model');
        $this->load->model('vendor_package_model');
        $this->load->model('subscriptions_payments_model');
        $this->load->model('package_setting_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('user_model');
        $this->load->model('notifications_model');
        $this->load->model('notification_type_model');
        $this->load->model('manualpayment_model');
        $this->load->model('master_package_setting_model');        
    }
 
    /**
     * @desc get api to retrieve Subscription Package data
     * @author Tejaswini
     * @date 06/07/2021
     *  */
    public function list_packages_get()
    {
        $service_id = $this->input->get('service_id');
        $package_id = $this->input->get('package_id');
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        if (empty($package_id)) {
			$check_data = $this->vendor_package_model->where(['created_user_id' => $token_data->id, 'service_id' => $service_id])->get_all();
			if($check_data) {
				$list_of_packages = $this->package_model->fields('id, service_id, title, desc, days,display_price,price,created_at,status')->where(['service_id' => $service_id])->where('id !=' , 15)->get_all();

			} else {
			$list_of_packages = $this->package_model->fields('id, service_id, title, desc, days,display_price,price,created_at,status')->where(['service_id' => $service_id])->get_all();

			}
			
			
			
			if($list_of_packages) {
				foreach ($list_of_packages as $k => $package) {
					
					$list_of_packages[$k]['pending_payment_status'] = $check_pending_payment ? true : false;
					$list_of_packages[$k]['package_features'] = $this->package_model->vendorPackageFeatures($service_id, $package['id']);

					$list_of_packages[$k]['services'] = $this->service_model->fields('id, name, desc')->where('id', $package['service_id'])->get();
					$list_of_packages[$k]['image'] = base_url() . 'uploads/subscriptions_image/subscriptions_' . $package['id'] . '.jpg';
				}
			}
            $this->set_response_simple($list_of_packages ? $list_of_packages : [], 'success..!', REST_Controller::HTTP_OK, TRUE);
        } else {
            $package = $this->package_model->where(['id' => $package_id, 'service_id' => $service_id])->get();
            $package['services'] = $this->service_model->fields('id, name, desc')->where('id', $package['id'])->get();
			$package['package_features'] = $this->package_model->vendorPackageFeatures($service_id, $package['id']);
			$package['pending_payment_status'] = $check_pending_payment ? true : false;

            $package['image'] = base_url() . 'uploads/subscriptions_image/subscriptions_' . $package['id'] . '.jpg';
            $this->set_response_simple($package ? $package : [], 'success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * @desc To vendor packages list old/new
     * 
     */
    public function vendor_package_get()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $service_id = $this->input->get('service_id');
        $list_vendor_packages = $this->vendor_package_model->fields('id,service_id,package_id,payment_txn_id,created_at, status')->with_packages('fields: id, title,desc,days,display_price,price')->where(['created_user_id' => $token_data->id, 'service_id' => $service_id])->get_all();
		
		if($list_vendor_packages) {
			foreach ($list_vendor_packages as $k => $package) {
				$validity = date('Y-m-d H:i:s', strtotime(($list_vendor_packages[$k]['created_at']) . "+" . $list_vendor_packages[$k]['packages']['days'] . "days"));
				$list_vendor_packages[$k]['start_date']=$list_vendor_packages[$k]['created_at'];
				$list_vendor_packages[$k]['end_date']=$validity;
				$list_vendor_packages[$k]['package_features'] = $this->package_model->packageFeatures($package['service_id'], $package['id']);
                
                if($package['payment_txn_id']) {
                    $list_vendor_packages[$k]['payment_info'] = $this->subscriptions_payments_model->fields('id,txn_id,amount,created_at')->where('id', $package['payment_txn_id'])->get();
                }
                else {
                    $list_vendor_packages[$k]['payment_info'] = null;
                }
                

				$list_vendor_packages[$k]['services'] = $this->service_model->fields('id, name, desc')->where('id', $service_id)->get();
				$list_vendor_packages[$k]['status']  = (strtotime($validity) >= now()) && $list_vendor_packages[$k]['status']==1 ? 'Active' : 'Inactive';
				$list_vendor_packages[$k]['packages']['image'] = base_url() . 'uploads/subscriptions_image/subscriptions_' . $list_vendor_packages[$k]['packages']['id'] . '.jpg';
			}
		}
        $this->set_response_simple($list_vendor_packages ? $list_vendor_packages : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * @desc To validate user subscription package
     * 
     */

    public function validity_vendor_package_post()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $list_vendor_packages = $this->vendor_package_model-> fields('id,service_id,package_id,created_at, status')->
                                with_packages('fields: id, title,desc,days,display_price,price')-> where(['created_user_id' => $token_data->id, 'service_id' =>$this->input->post('service_id'),'status'=>1])->get_all();
								
        if (!empty($list_vendor_packages)) {
            $hasActivePackage = FALSE;
            foreach ($list_vendor_packages as $k => $package) {
                $validity = date('Y-m-d', strtotime(($list_vendor_packages[$k]['created_at']) . "+" . $list_vendor_packages[$k]['packages']['days'] . "days"));
				
				
			$expiry_date=date('Y-m-d',strtotime($validity));
			$today_date = date('Y-m-d');
			
                if( $expiry_date > $today_date)
                {
                    $hasActivePackage = TRUE;
                    break;
                }
            }
            if($hasActivePackage == TRUE) {
                $data[] = $this->package_model->where(['id' => $this->input->post('package_id'), 'service_id' => $this->input->post('service_id')])->get();
                $this->set_response_simple($data, 'package is already activated..!', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'no activated packages found..!', REST_Controller::HTTP_OK, FALSE);
            }
        } else {
            $this->set_response_simple(NULL, 'no activated packages found..!', REST_Controller::HTTP_OK, FALSE);
        }
    }

    public function upgradable_vendor_packages_post()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $serviceID = $this->input->post('service_id');
        $acivePackage = $this->getVendorPackage($token_data, $serviceID, true);
        $enabledPackageID =$acivePackage['package_id'];
        $now = time();
        $your_date = strtotime($acivePackage['created_at']);
        $datediff = $now - $your_date;
        $daysDifference =  round($datediff / (60 * 60 * 24));
        $upgradablePackages = $this->package_model->getUpgradablePackages($enabledPackageID, $daysDifference, $token_data->id);
        if (!empty($upgradablePackages)) {
            $this->set_response_simple($upgradablePackages, Null , REST_Controller::HTTP_OK, TRUE);
        } else {
            $this->set_response_simple(NULL, 'no activated packages found..!', REST_Controller::HTTP_OK, FALSE);
        }
    }

    /**
     * To handle payment gateway response for subscriptions
     *
     * @author tejaswini
     */
    public function payment_status_post()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $package = $this->package_model->where('id', $this->input->post('package_id'))->get();
        $this->form_validation->set_rules($this->subscriptions_payments_model->rules);
        $this->subscriptions_payments_model->user_id = $token_data->id;
        if ($this->form_validation->run() == FALSE) {
            $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_OK, FALSE);
        } else {
            if ((float) ($package['price']) ==0) {
                $is_payment = $this->vendor_package_model->insert([
                    'service_id' => empty($this->input->post('service_id')) ? NULL : $this->input->post('service_id'),
                    'package_id' => empty($this->input->post('package_id')) ? NULL : $this->input->post('package_id'),
                    'payment_method_id' => empty($this->input->post('payment_method_id')) ? NULL : $this->input->post('payment_method_id'),
                    'created_user_id' => $token_data->id,
                    'status' => 1
                ]);
                $this->set_response_simple(['vendor_package_id' => $is_payment], 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                $this->send_notification($token_data->id, VENDOR_APP_CODE, "You are now subscribed to " . $package['title'] . "", "In which you'll enjoy the listed benefits for " . $package['days'] . " days we will serve you notify remainder before the plan expiry.", ['notification_type' => $this->notification_type_model->where(['app_details_id' => 2, 'notification_code' => 'SUBS'])->get()]);
            } else {
                if ($this->input->post('payment_method_id') == 2) {
                    $txn_id = (empty($this->input->post('payment_gw_txn_id'))) ? uniqid() : $this->input->post('payment_gw_txn_id');
                    $order_id = (empty($this->input->post('order_id'))) ? NULL : $this->input->post('order_id');
                    $is_inserted = $this->subscriptions_payments_model->insert([
                        'payment_method_id' => $this->input->post('payment_method_id'),
                        'txn_id' => $txn_id,
                        'amount' => $this->input->post('amount'),
                        'message' => !empty($this->input->post('message')) ? $this->input->post('message') : NULL,
                        'status' => 1,
                    ]);
                    $wallet_txn_id = 'NC-' . generate_trasaction_no();
                    $amount = $this->input->post('amount');
                    $this->user_model->payment_update($this->config->item('super_admin_user_id'), $amount, 'CREDIT', "wallet", $wallet_txn_id, $order_id);
                    if($this->input->post('upgrade')==1){
                        $this->vendor_package_model->update(['status'=>2], [
                            'service_id' => empty($this->input->post('service_id')) ? NULL : $this->input->post('service_id'),
                            'created_user_id' => $token_data->id,
                            'status' => 1
                        ]);
                    }
                    $is_payment = $this->vendor_package_model->insert([
                        'service_id' => empty($this->input->post('service_id')) ? NULL : $this->input->post('service_id'),
                        'package_id' => empty($this->input->post('package_id')) ? NULL : $this->input->post('package_id'),
                        'payment_method_id' => empty($this->input->post('payment_method_id')) ? NULL : $this->input->post('payment_method_id'),
                        'payment_txn_id' => empty($is_inserted) ? NULL : $is_inserted,
                        'created_user_id' => $token_data->id
                    ]);                   

                } elseif ($this->input->post('payment_method_id') == 3) {
                    $txn_id = 'NC-' . generate_trasaction_no();
                    $amount = floatval($this->input->post('amount'));
                    $order_id = (empty($this->input->post('order_id'))) ? NULL : $this->input->post('order_id');
                    $is_inserted = $this->user_model->payment_update($token_data->id, $amount, 'DEBIT', "wallet", $txn_id, $order_id);
                    $this->user_model->payment_update($this->config->item('super_admin_user_id'), $amount, 'CREDIT', "wallet", $txn_id, $order_id);
                    if (!empty($is_inserted)) {
                        if($this->input->post('upgrade')==1){
                            $this->vendor_package_model->update(['status'=>2], [
                                'service_id' => empty($this->input->post('service_id')) ? NULL : $this->input->post('service_id'),
                                'created_user_id' => $token_data->id,
                                'status' => 1    
                            ]);
                        }
                        $is_payment = $this->vendor_package_model->insert([
                            'service_id' => empty($this->input->post('service_id')) ? NULL : $this->input->post('service_id'),
                            'package_id' => empty($this->input->post('package_id')) ? NULL : $this->input->post('package_id'),
                            'payment_method_id' => empty($this->input->post('payment_method_id')) ? NULL : $this->input->post('payment_method_id'),
                            'wallet_txn_id' => empty($is_inserted) ? NULL : $is_inserted,
                            'created_user_id' => $token_data->id
                        ]);
                    } else {
                        $this->set_response_simple(NULL, 'payment Failed..!', REST_Controller::HTTP_OK, FALSE);
                    }
                }


                if ($is_inserted) {
                    $this->set_response_simple(['payment_id' => $is_inserted], 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                    $this->send_notification($token_data->id, VENDOR_APP_CODE, "You are now subscribed to " . $package['title'] . "", "In which you'll enjoy the listed benefits for " . $package['days'] . " days we will serve you notify remainder before the plan expiry.", ['notification_type' => $this->notification_type_model->where(['app_details_id' => 2, 'notification_code' => 'SUBS'])->get()]);
					//push notification code here
					
                } else {
                    $this->set_response_simple(NULL, 'Failed..!', REST_Controller::HTTP_OK, FALSE);
                }
            }
        }
    }

    public function my_package_get()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        try {
            $service_id = $this->input->get('service_id');
            $acivePackage = $this->getVendorPackage($token_data, $service_id, true);
            $this->set_response_simple($acivePackage, NULL, REST_Controller::HTTP_OK, TRUE);
        } catch (Exception $ex) {
            $this->set_response_simple(NULL, NULL, REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }

    private function getVendorPackage($token_data, $serviceID, $withSettings = false){
        $service_id = $serviceID;
        $acivePackage = null;
        $list_vendor_packages = $this->vendor_package_model->fields('id,service_id,package_id,created_at')->with_packages('fields: id, title,desc,days,price')->where(['created_user_id' => $token_data->id, 'service_id' => $service_id])->get_all();
        if($list_vendor_packages){
            foreach ($list_vendor_packages as $k => $package) {
                $validity = date('Y-m-d H:i:s', strtotime(($list_vendor_packages[$k]['created_at']) . "+" . $list_vendor_packages[$k]['packages']['days'] . "days"));
                if (strtotime($validity) >= now()) {
                    $acivePackage = $list_vendor_packages[$k];
                }
            }
        }
        if(!empty($withSettings && $acivePackage)){
            $settings = $this->package_setting_model->fields('setting_key, status')->where([
                'package_id' => $acivePackage['package_id']
            ])->with_master_setting('fields: description')->get_all();
            $acivePackage['settings'] = $settings;
        }
        return $acivePackage;
    }

    public function package_settings_get()
    {
        $service_id = $this->input->get('service_id');
        $package_id = $this->input->get('package_id');
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        if (empty($package_id)) {
            $master_packages_settings = $this->master_package_setting_model->where('status',1)->get_all();
            foreach ($master_packages_settings as $k => $setting) {
                $master_packages_settings[$k]['package_settings'] = $this->package_setting_model->fields('package_id')->where('setting_key', $setting['setting_key'])->get_all();
                foreach($master_packages_settings[$k]['package_settings'] as $key => $package){
                    $master_packages_settings[$k]['package_settings'][$key]['package'] = $this->package_model->fields('id,title')->with_services('fields:id,name')->where('id',$package['package_id'])->get();
                }
            }
            $this->set_response_simple($master_packages_settings ? $master_packages_settings : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        } else {
            $packages_settings = $this->package_setting_model->where('package_id' , $package_id)->group_by("setting_key")->get_all();
            $this->set_response_simple($packages_settings ? $packages_settings : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }
        
    }

    public function package_features_get(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $service_id = $this->input->get('service_id');
        $acivePackage = $this->getVendorPackage($token_data, $service_id, false);
        $packageFeatures = $this->package_model->packageFeatures($service_id, $acivePackage['package_id']);
        if($packageFeatures){
            $this->set_response_simple($packageFeatures ? $packageFeatures : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }else{
            $this->set_response_simple(NULL, NULL, REST_Controller::HTTP_NO_CONTENT, FALSE);
        }
    }

}
