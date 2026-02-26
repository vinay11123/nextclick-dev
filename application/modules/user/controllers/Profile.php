<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Rakit\Validation\Validator;

class Profile extends MY_REST_Controller
{

    public $user_id = NULL;
    public $intentsArr = ["user", "delivery_partner", "vendor", "executive"];
    public $handledExceptions = ["INVALID_INTENT"];
    public function __construct()
    {
        parent::__construct();

        $this->load->model('business_info_model');
        $this->load->model('business_address_model');
        $this->load->model('user_group_model');
        $this->load->model('delivery_boy_address_model');
        $this->load->model('delivery_boy_biometric_model');
        $this->load->model('user_doc_model');
        $this->load->model('vendor_banner_model');
        $this->load->model('executive_biometric_model');
        $this->load->model('executive_address_model');
        $this->load->model('user_model');
        $this->load->model('location_model');
        $this->load->model('user_credential_model');
        $this->load->model('vehicle_model');
        $this->load->model('setting_model');
    }

    public function me_get()
    {
        try {
            $intent = $this->input->get('intent');
            $result = null;
			$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
			$token_data =$this->validate_token($authorization_exp[1]);
			
            $userID = $token_data->id;
            $userInfo = $this->user_model->fields(['first_name', 'last_name', 'display_name', 'phone', 'email', 'passcode', 'created_at']);
            if ($intent == 'executive') {
                $userInfo = $userInfo->with_executive_address('fields: lat, lng, line1, state, district, constituency, zip_code, location,executive_type_id');
                $result = $userInfo->where($userID)->get();

            } else if ($intent == 'vendor') {
                $userInfo = $userInfo->with_business_info('fields: id, business_name, owner_name, gst_number, labour_certificate_number, fssai_number, secondary_contact, whats_app_no, status, availability');
                $result = $userInfo->where($userID)->get();
                $address = $this->business_address_model->where([
                    'list_id' => $result['business_info']['id']
                ])->fields(['lat', 'lng', 'line1', 'state', 'district', 'constituency', 'zip_code'])->get();
                $result['business_address'] = $address;

                $vendor_banners = $this->vendor_banner_model->where('list_id', $result['business_info']['id'])->get_all();
                $result['cover'] = base_url() . "uploads/list_cover_image/list_cover_" . $result['business_info']['id'] . ".jpg";
                $result['banners'] = [];
                if ($vendor_banners) {
                    foreach ($vendor_banners as $key => $banner) {
                        $result['banners'][$key]['id'] = $banner['id'];
                        $result['banners'][$key]['image'] = base_url() . "uploads/list_banner_image/list_banner_" . $banner['id'] . ".jpg";
                    }
                }

            } else if ($intent == 'delivery_partner') {
                $userInfo = $userInfo->with_delivery_boy_address('fields: lat, lng, line1, state, district, constituency, zip_code')
                    ->with_delivery_boy_biometrics('fields: vehicle_type_id,shift_id');
                $result = $userInfo->where($userID)->get();
                $vehicle_details = $this->vehicle_model->where([
                    'id' => $result['delivery_boy_biometrics']['vehicle_type_id']
                ])->get();
                $result['security_deposited_amount'] =$vehicle_details['security_deposited_amount'];
            } else {
                $result = $userInfo->where($userID)->get();
            }
            $result['profile_image'] = base_url() . 'uploads/profile_image/profile_' . $userID . '.jpg';
            $this->set_response_simple($result, Null, REST_Controller::HTTP_OK, TRUE);
        } catch (Exception $ex) {
            $this->set_response_simple(NULL, $ex->getMessage(), REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }
    private function generateReferralCode($prefix = 'VEN', $length = 6)
    {
        do {
            $code = $prefix . strtoupper(substr(bin2hex(random_bytes(4)), 0, $length));
            $exists = $this->user_model->where('referral_code', $code)->get();
        } while (!empty($exists));
    
        return $code;
    }

    public function manage_post()
    {
        try {
			$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
			$token_data =$this->validate_token($authorization_exp[1]);
			
            $userID = $token_data->id;
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $postData = $_POST;
            if (!in_array($postData['intent'], $this->intentsArr)) {
                throw new Exception("INVALID_INTENT");
            }
            $intent = $postData['intent'];
            $executiveID = null;
            unset($postData['intent']);
            if ($intent == 'vendor') {
                $vendorID= $this->input->post('vendor_id');
                if (isset($vendorID) && !empty($vendorID)) {
                    $executiveID = $userID;
                    $userID = $vendorID;
                }
                $busunessAddress = $postData['business_address'];
                unset($postData['business_address']);
                
                $referral_code = isset($postData['referral_code']) ? $postData['referral_code'] : null;
                $executive_user_id = null;
                $executive_referral_amount = null;

                if(!empty($referral_code)) {
                    $check_referral_existing = $this->user_model->where('referral_code', $postData['referral_code'])->get();
                    if (!isset($check_referral_existing['id'])) {
                        $this->set_response_simple(Null, "Invalid Referral Code", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                        return;
                    }
                    else {
                        $executive_user_id = $check_referral_existing['id'];
                        //Get vendor referral amount from settings.
                        $settings =	$this->setting_model->where("key", 'vendor_referral_amount')->get();
                        $executive_referral_amount = $settings['value'];
                    }
                }

                $businessInfo = [
                    'business_name'                => $postData['name'],
                    'name'                         => $postData['name'],
                    'category_id'                  => $postData['business_category'],
                    'owner_name'                   => $postData['owner'],
                    'fssai_number'                 => $postData['fssai'],
                    'gst_number'                   => $postData['gst'],
                    'labour_certificate_number'    => $postData['labour_certificate_number'],
                    'secondary_contact'            => $postData['secondary_contact'],
                    'whats_app_no'                 => $postData['whats_app_no'],
                    'sub_categories'               => $postData['sub_categories'],
                    'availability'                 => isset($postData['availability']) ? $postData['availability'] : 0,
                ];
                /* if(!empty($executiveID)){
                    $businessInfo['executive_user_id'] = $executiveID;
                } */
                if(!empty($executive_user_id)){
                    $businessInfo['executive_user_id'] = $executive_user_id;
                }
                if(!empty($executive_referral_amount)){
                    $businessInfo['executive_referral_amount'] = $executive_referral_amount;
                }
                $result = $this->business_info_model->mutate($userID, $businessInfo);

                $userdetail = $this->user_model->where('id', $token_data->id)->get();
                $data = array(
                    'vendor_name'        => $postData['owner']
                );
                $message = $this->load->view('vendor_reg_tem', $data, true);
                $this->email->clear();
                $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
                $this->email->to($userdetail['email']);
                $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - Register Mail');
                $this->email->message($message);
                $this->email->send();

                $this->email->send();

                if ($result['success']) {
                     // AUTO REFERRAL CODE FOR VENDOR
                    $vendor_referral_code = $this->generateReferralCode('VEN');
                    $this->user_model->update([
                        'referral_code' => $vendor_referral_code
                    ], $userID);
                    if (
                        !empty($busunessAddress) &&
                        isset($busunessAddress['lat']) &&
                        isset($busunessAddress['lng']) &&
                        isset($busunessAddress['location'])
                    ) {
                        $is_location_exist = $this->location_model->where([
                            'latitude' => $busunessAddress['lat'],
                            'longitude' => $busunessAddress['lng']
                        ])->get();
                        if (empty($is_location_exist)) {
                            $location_id = $this->location_model->insert([
                                'address' => $busunessAddress['location'],
                                'latitude' => $busunessAddress['lat'],
                                'longitude' => $busunessAddress['lng']
                            ]);
                        } else {
                            $location_id = $is_location_exist['id'];
                        }

                        $this->business_info_model->update([
                            'id' => $result['list_id'],
                            'location_id' => $location_id
                        ], 'id');
                    }
                    $is_location_exist = $this->location_model->where([
                        'latitude' => $busunessAddress['lat'],
                        'longitude' => $busunessAddress['lng']
                    ])->get();
                    $this->business_address_model->mutate($result['list_id'], $busunessAddress);
                    $banners = [];
                    if(isset($postData['banners'])){
                        $banners = $postData['banners'];
                    }else if (isset($postData['banner'])) {
                        $banners = [$postData['banner']];
                    }
                    if (isset($postData['logo'])) {
                        if (file_exists(base_url() . "uploads/list_cover_image/list_cover_" . $result['list_id'] . ".jpg")) {
                            unlink(base_url() . "uploads/list_cover_image/list_cover_" . $result['list_id'] . ".jpg");
                            file_put_contents("./uploads/list_cover_image/list_cover_" . $result['list_id'] . ".jpg", base64_decode($postData['logo']));
                        } else {
                            file_put_contents("./uploads/list_cover_image/list_cover_" . $result['list_id'] . ".jpg", base64_decode($postData['logo']));
                        }
                    }
                    if (!empty($banners)) {
                        $this->db->delete($this->vendor_banner_model->table, array(
                            "list_id" => $result['list_id']
                        ));
                        foreach ($banners as $banner) {

                            $image_id = $this->vendor_banner_model->insert([
                                'list_id' => $result['list_id'],
                                'image' => 'banner_' . $result['list_id'] . '.jpg',
                                'ext' => 'jpg'
                            ]);

                            file_put_contents("uploads/list_banner_image/list_banner_$image_id.jpg", base64_decode($banner));
                        }
                    }
                    $this->user_group_model->updateToDocsSubmitted($userID, $intent);
                 
                    $responseData = [
                        'referral_code'     => $vendor_referral_code  // auto generated
                       
                    ];
                    
                    $this->set_response_simple(
                        $responseData,
                        "Successfully Registered.!",
                        REST_Controller::HTTP_OK,
                        TRUE
                    );

                } else {
                    $this->set_response_simple(NULL, $result['error'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
                }
            } else if ($intent == 'delivery_partner') {
                $deliveryBoyAddress = $postData['delivery_boy_address'];
                unset($postData['delivery_boy_address']);

                $referral_code = isset($postData['referral_code']) ? $postData['referral_code'] : null;
                $executive_user_id = null;

                if(!empty($referral_code)) {
                    $check_referral_existing = $this->user_model->where('referral_code', $postData['referral_code'])->get();
                    if (!isset($check_referral_existing['id'])) {
                        $this->set_response_simple(Null, "Invalid Referral Code", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                        return;
                    }
                    else {
                        $executive_user_id = $check_referral_existing['id'];
                        $deliveryBoyAddress['executive_user_id'] = $executive_user_id;
                        
                        $delivery_boy_referral_amount_info =    $this->setting_model->where("key", 'delivery_boy_referral_amount')->get();
                        $deliveryBoyAddress['executive_referral_amount'] = $delivery_boy_referral_amount_info['value'];

                        $delivery_boy_target_order_count =    $this->setting_model->where("key", 'delivery_boy_target_order_count')->get();
                        $deliveryBoyAddress['target_given_count'] = $delivery_boy_target_order_count['value'];
                    }
                }

                $deliveryBoyBiometrics = [
                    'vehicle_type_id'              => $postData['vehicle_type_id'],
                    'shift_id'                     => $postData['shift_id']
                ];
                if(isset($postData['aadhar']))
                {
                    $deliveryBoyBiometrics['aadhar']  = $postData['aadhar'];
                }
                if(isset($postData['pan']))
                {
                    $deliveryBoyBiometrics['pan']  = $postData['pan'];
                }
                if(isset($postData['vehicle_insurance']))
                {
                    $deliveryBoyBiometrics['vehicle_insurance']  = $postData['vehicle_insurance'];
                }
                if(isset($postData['driving_license']))
                {
                    $deliveryBoyBiometrics['driving_license']  = $postData['driving_license'];
                }
                if(isset($postData['vehicle_number']))
                {
                    $deliveryBoyBiometrics['vehicle_number']  = $postData['vehicle_number'];
                }
                $result = $this->delivery_boy_biometric_model->mutate($userID, $deliveryBoyBiometrics);
                $result = $this->delivery_boy_address_model->mutate($userID, $deliveryBoyAddress);

                $userdetail = $this->user_model->where('id', $token_data->id)->get();
                $data = array(
                    'delivery_boy_name'        => $userdetail['first_name']
                );
                $message = $this->load->view('delivery_boy_approval_tem', $data, true);
                $this->email->clear();
                $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
                $this->email->to($userdetail['email']);
                $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - Register Mail');
                $this->email->message($message);
                $this->email->send();
    
                $this->email->send();

                $this->user_doc_model->user_id = $userID;
                $is_docs_existed = $this->user_doc_model->where('created_user_id', $userID)->get();
                $unique_id = $userID;
                if (empty($is_docs_existed)) {
                    $this->user_doc_model->insert([
                        'unique_id' => $unique_id
                    ]);
                }

                if (!file_exists('uploads/' . 'aadhar_card' . '_image/')) {
                    mkdir('uploads/' . 'aadhar_card' . '_image/', 0777, true);
                }
                file_put_contents("./uploads/aadhar_card_image/aadhar_card_front_" . $unique_id . ".jpg", base64_decode($this->input->post('aadhar_card_image_front')));
                file_put_contents("./uploads/aadhar_card_image/aadhar_card_back_" . $unique_id . ".jpg", base64_decode($this->input->post('aadhar_card_image_back')));

                if (!file_exists('uploads/' . 'pan_card' . '_image/')) {
                    mkdir('uploads/' . 'pan_card' . '_image/', 0777, true);
                }
                file_put_contents("./uploads/pan_card_image/pan_card_front_" . $unique_id . ".jpg", base64_decode($this->input->post('pan_card_image_front')));
                file_put_contents("./uploads/pan_card_image/pan_card_back_" . $unique_id . ".jpg", base64_decode($this->input->post('pan_card_image_back')));

                if (!file_exists('uploads/' . 'driving_license' . '_image/')) {
                    mkdir('uploads/' . 'driving_license' . '_image/', 0777, true);
                }
                file_put_contents("./uploads/driving_license_image/driving_license_front_" . $unique_id . ".jpg", base64_decode($this->input->post('driving_license_image_front')));
                file_put_contents("./uploads/driving_license_image/driving_license_back_" . $unique_id . ".jpg", base64_decode($this->input->post('driving_license_image_back')));

                if (!file_exists('uploads/' . 'rc' . '_image/')) {
                    mkdir('uploads/' . 'rc' . '_image/', 0777, true);
                }
                file_put_contents("./uploads/rc_image/rc_front_" . $unique_id . ".jpg", base64_decode($this->input->post('rc_image_front')));
                file_put_contents("./uploads/rc_image/rc_back_" . $unique_id . ".jpg", base64_decode($this->input->post('rc_image_back')));

                if (!file_exists('uploads/' . 'vehicle' . '_image/')) {
                    mkdir('uploads/' . 'vehicle' . '_image/', 0777, true);
                }
                file_put_contents("./uploads/vehicle_image/vehicle_front_" . $unique_id . ".jpg", base64_decode($this->input->post('vehicle_image_front')));
                file_put_contents("./uploads/vehicle_image/vehicle_back_" . $unique_id . ".jpg", base64_decode($this->input->post('vehicle_image_back')));

                if (!file_exists('uploads/' . 'vehicle_insurance' . '_image/')) {
                    mkdir('uploads/' . 'vehicle_insurance' . '_image/', 0777, true);
                }
                file_put_contents("./uploads/vehicle_insurance_image/vehicle_insurance_front_" . $unique_id . ".jpg", base64_decode($this->input->post('vehicle_insurance_image_front')));
                file_put_contents("./uploads/vehicle_insurance_image/vehicle_insurance_back_" . $unique_id . ".jpg", base64_decode($this->input->post('vehicle_insurance_image_back')));

                if (!file_exists('uploads/' . 'bank_passbook' . '_image/')) {
                    mkdir('uploads/' . 'bank_passbook' . '_image/', 0777, true);
                }
                file_put_contents("./uploads/bank_passbook_image/bank_passbook_" . $unique_id . ".jpg", base64_decode($this->input->post('bank_passbook_image')));

                if (!file_exists('uploads/' . 'cancellation_cheque' . '_image/')) {
                    mkdir('uploads/' . 'cancellation_cheque' . '_image/', 0777, true);
                }
                file_put_contents("./uploads/cancellation_cheque_image/cancellation_cheque_" . $unique_id . ".jpg", base64_decode($this->input->post('cancellation_cheque_image')));
                $this->user_group_model->updateToDocsSubmitted($userID, $intent);
                $this->set_response_simple(NULL, "Successfully Registered.!", REST_Controller::HTTP_OK, TRUE);
            } else if ($intent == 'executive') {
                $executiveAddress = $postData['executive_address'];
                unset($postData['executive_address']);
                $executiveBiometrics = [
                    'aadhar'      => $postData['aadhar']
                ];
                $result = $this->executive_biometric_model->mutate($userID, $executiveBiometrics);
                $result = $this->executive_address_model->mutate($userID, $executiveAddress);
                $this->user_doc_model->user_id = $userID;
                $is_docs_existed = $this->user_doc_model->where('created_user_id', $userID)->get();
                $unique_id = $userID;
                if (empty($is_docs_existed)) {
                    $this->user_doc_model->insert([
                        'unique_id' => $unique_id
                    ]);
                }

                if (!file_exists('uploads/' . 'aadhar_card' . '_image/')) {
                    mkdir('uploads/' . 'aadhar_card' . '_image/', 0777, true);
                }
                file_put_contents("./uploads/aadhar_card_image/aadhar_card_front_" . $unique_id . ".jpg", base64_decode($this->input->post('aadhar_card_image_front')));
                file_put_contents("./uploads/aadhar_card_image/aadhar_card_back_" . $unique_id . ".jpg", base64_decode($this->input->post('aadhar_card_image_back')));

                if (!file_exists('uploads/' . 'bank_passbook' . '_image/')) {
                    mkdir('uploads/' . 'bank_passbook' . '_image/', 0777, true);
                }
                file_put_contents("./uploads/bank_passbook_image/bank_passbook_" . $unique_id . ".jpg", base64_decode($this->input->post('bank_passbook_image')));

                $this->user_group_model->updateToDocsSubmitted($userID, $intent);
                $this->set_response_simple(NULL, "Successfully Registered.!", REST_Controller::HTTP_OK, TRUE);
            }
        } catch (Exception $ex) {
            $this->set_response_simple(NULL, $ex->getMessage(), REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }

    public function update_post()
    {
        try {
			$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
			$token_data =$this->validate_token($authorization_exp[1]);
			
            $userID = $token_data->id;
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $postData = $_POST;
            $profile_image = $this->input->post('profile_image');
            $userInfo = [
                'first_name'            => $postData['first_name'],
                'last_name'             => $postData['last_name'],
                'display_name'          => $postData['display_name'],
                'passcode'              => $postData['passcode']
            ];
            if (!empty($userInfo))
                $this->user_model->mutate($userID, $userInfo);

                if(isset($profile_image) && !empty($profile_image)){
                    file_put_contents("./uploads/profile_image/profile_" . $userID . ".jpg", base64_decode($profile_image));
                }

            $this->set_response_simple(NULL, "Updated Succcessful.!", REST_Controller::HTTP_OK, TRUE);
        } catch (Exception $ex) {
            $this->set_response_simple(NULL, $ex->getMessage(), REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }

    // public function approval_status_get()
    // {
    //     try {
    //         $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		//$token_data =$this->validate_token($authorization_exp[1]);
	//$userID = $token_data->id;
    //         $intent = $this->input->get('intent');
    //         if (!in_array($intent, $this->intentsArr)) {
    //             throw new Exception("INVALID_INTENT");
    //         }
    //         if ($intent == 'executive') {
    //             $this->user_doc_model->fields([''])->get();
    //         }
    //     } catch (Exception $ex) {
    //     }
    // }
    
    /**
     * Update profile including email and phone
     */
    public function update_primary_details_post()
    {
        try {
			$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
            $userID = $token_data->id;
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $postData = $_POST;
            $profile_image = $this->input->post('profile_image');
            $userInfo = [
                'first_name'            => $postData['first_name'],
                'last_name'             => $postData['last_name'],
                'display_name'          => $postData['display_name']
            ];
            if(isset($postData['passcode']))
            {
                $userInfo['passcode']  = $postData['passcode'];
            }

            if(isset($postData['mobile']) || isset($postData['email']))
            {
                    $userDetails = $this->user_model->where('id', $userID)
                    ->get();

                    if(isset($postData['mobile']) && $postData['mobile'] != $userDetails['phone'])
                    {
                        //update mobile if no record exists with given mobile
                        $mobileDetails = $this->user_model->where('phone', $postData['mobile'])
                        ->get();
                        if(empty($mobileDetails))
                        {
                            $userInfo['phone']  = $postData['mobile'];
                        }
                        else{
                            return $this->set_response_simple(NULL, 'Given mobile Already used by another user', REST_Controller::HTTP_OK, FALSE);
                        }
                    }
                    if(isset($postData['email']) && $postData['email'] != $userDetails['email'])
                    {
                         //update mail if no record exists with given mail
                         $mailDetails = $this->user_model->where('email', $postData['email'])
                         ->get();
                         if(empty($mailDetails))
                         {
                             $userInfo['email']  = $postData['email'];
                         }
                         else{
                             return $this->set_response_simple(NULL, 'Given email Already used by another user', REST_Controller::HTTP_OK, FALSE);
                         }
                    }
            }
            if (!empty($userInfo))
                $this->user_model->mutate($userID, $userInfo);

                if(isset($profile_image) && !empty($profile_image)){
                    file_put_contents("./uploads/profile_image/profile_" . $userID . ".jpg", base64_decode($profile_image));
                }

            $this->set_response_simple(NULL, "Updated Succcessful.!", REST_Controller::HTTP_OK, TRUE);
        } catch (Exception $ex) {
            $this->set_response_simple(NULL, $ex->getMessage(), REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }
}
