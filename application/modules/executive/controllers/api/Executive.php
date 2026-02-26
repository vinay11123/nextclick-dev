<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Executive extends MY_REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('user_group_model');
        $this->load->model('group_model');
        $this->load->helper('date');
        $this->load->model('social_auth_model');
        $this->load->model('app_details_model');
        $this->load->model('otp_model');
        $this->load->model('location_model');
        $this->load->model('user_doc_model');
        $this->load->model('users_address_model');
        $this->load->model('vendor_list_model');
        $this->load->model('executive_type_model');
        
    }

    /**
     * @desc To register Excutive
     * @author Tejaswini
     * 
     * @param string $user_type
     */
    public function register_post($user_type = 'executive'){
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->form_validation->set_rules($this->user_model->rules['executive']);
        if($this->form_validation->run() ==  FALSE){
            $this->set_response_simple(NULL, explode('.', validation_errors())[0], REST_Controller::HTTP_OK, FALSE);
        }else{
            if($user_type == 'executive'){
                $email = $this->input->post('email');
                $mobile = $this->input->post('mobile');
                $group = $this->group_model->where('id', $this->config->item('executive_group_id', 'ion_auth'))->get();
                $insert_data = [
                    'phone' => $mobile, 
                    'active' => 1, 
                    "first_name" => $this->input->post('first_name'),
                    "last_name" => (empty($this->input->post('last_name'))) ? NULL : $this->input->post('last_name'),
                    "email" => $email,
                    "aadhar_number" => $this->input->post('aadhar_number'),
                ];
            }
             if(! empty($group)){ 
                 $user = $this->user_model->with_groups('fields: id, name')->where('phone', $this->input->post('mobile'))->get();
                if(! empty($user)){
                    $unique_id = $user['unique_id'];
                    $user_id = $user['id'];
                    $this->set_response_simple(NULL, "Sorry, Email is already existed!", REST_Controller::HTTP_OK, FALSE);
                }else { 
                    if($this->check_executive_email($email) && $this->check_user_phone($mobile)){ 
                        $unique_id = generate_serial_no($group['code'], 4, $group['last_id']);
                        $this->group_model->update([
                            'last_id' => $group['last_id'] + 1
                        ], $group['id']);
                        $insert_data['unique_id'] = $unique_id;
                        $group_id[0] =  $group['id'];
                        $user_id = $this->ion_auth->register($unique_id, (empty($this->input->post('password')))? '123456': $this->input->post('password'), $this->input->post('email'), $insert_data, $group_id);
                    } 
                } 
                
                if(! empty($user_id)){
                    $is_location_exist = $this->location_model->where(['latitude' => $this->input->post('latitude'), 'longitude' => $this->input->post('longitude')])->get();
                    if(empty($is_location_exist)){
                        $location_id = $this->location_model->insert([
                            'address' => $this->input->post('geo_lcoation_address'),
                            'latitude' => $this->input->post('latitude'),
                            'longitude' => $this->input->post('longitude'),
                        ]);
                    } else {
                        $location_id = $is_location_exist['id'];
                    }
                    $this->user_model->update(
                        array_merge($insert_data, [
                            'id' => $user_id,
                            'location_id' => $location_id
                        ]), 'id');

                    $this->user_doc_model->user_id = $user_id;
                    $is_docs_existed = $this->user_doc_model->where('created_user_id', $user_id)->get();
                    if(empty($is_docs_existed)){
                        $this->user_doc_model->insert([
                            'unique_id' => $unique_id
                        ]);
                    }
                    
                    
                    if(! empty($user) &&  array_search($this->config->item('executive_group_id', 'ion_auth'), array_column($user['groups'], 'id')) === FALSE){
                        $this->user_group_model->insert([
                            'user_id' => $user_id,
                            'group_id' => $this->config->item('executive_group_id', 'ion_auth')
                        ]);
                    }
                    
                    if (! file_exists('uploads/' . 'aadhar_card' . '_image/')) {
                        mkdir('uploads/' . 'aadhar_card' . '_image/', 0777, true);
                    }
                    file_put_contents("./uploads/aadhar_card_image/aadhar_card_front_" . $unique_id . ".jpg", base64_decode($this->input->post('aadhar_card_image_front')));
                    file_put_contents("./uploads/aadhar_card_image/aadhar_card_back_" . $unique_id . ".jpg", base64_decode($this->input->post('aadhar_card_image_back')));
                    
                    if (! file_exists('uploads/' . 'profile' . '_image/')) {
                        mkdir('uploads/' . 'profile' . '_image/', 0777, true);
                    }
                    file_put_contents("./uploads/profile_image/profile_" . $unique_id . ".jpg", base64_decode($this->input->post('profile')));
                    
                    if (! file_exists('uploads/' . 'bank_passbook' . '_image/')) {
                        mkdir('uploads/' . 'bank_passbook' . '_image/', 0777, true);
                    }
                    file_put_contents("./uploads/bank_passbook_image/bank_passbook_" . $unique_id . ".jpg", base64_decode($this->input->post('bank_passbook_image')));
                    
                    
                    $this->set_response_simple(NULL, "Successfully Registered.!", REST_Controller::HTTP_OK, TRUE);
                }else {
                    $this->set_response_simple(NULL, "Sorry, Registration is failed. please try again!", REST_Controller::HTTP_OK, FALSE);
                }
            }else {
                $this->set_response_simple(NULL, "Sorry, You're already an existing delivery partner.", REST_Controller::HTTP_OK, FALSE);
            } 
       }
    }

     /**
     * @desc To get Excutive profile
     * @author Tejaswini
     * 
     * @param string 
     */
    public function profile_get(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $data = $this->user_model->with_location('fields: latitude, longitude, address')->where($token_data->id)->get();
        $data['image'] = base_url() . 'uploads/profile_image/profile_' . $data['unique_id']. '.jpg';
        $data['aadhar_front'] = base_url() . 'uploads/aadhar_card_image/aadhar_card_front_' . $data['unique_id']. '.jpg';
        $data['aadhar_back'] = base_url() . 'uploads/aadhar_card_image/aadhar_card_back_' . $data['unique_id']. '.jpg';
        $data['passbook'] = base_url() . 'uploads/bank_passbook_image/bank_passbook_' . $data['unique_id']. '.jpg';
        $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * @desc API for Excutive wallet payment
     * @author Tejaswini
     * 
     * @param string 
     */
    public function executive_payment_status_post(){
        $executive_id = $this->input->post('executive_id');
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $wallet_txn_id = 'NC-' . generate_trasaction_no();
            $amount = $this->input->post('amount');
            $order_id = (empty($this->input->post('order_id'))) ? NULL : $this->input->post('order_id');
            $data = $this->user_model->payment_update($executive_id, $amount, 'CREDIT', "wallet", $wallet_txn_id,$order_id );
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        
    }


     /**
     * @desc To get Excutive wallet
     * @author Tejaswini
     * 
     * @param string 
     */
    public function executive_wallet_get(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $data = $this->user_model->fields('id, unique_id, wallet')->where(['unique_id' => $this->input->get('unique_id'), 'id' =>  $token_data->id])->get_all();
        $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }
    /**
     * @desc To get Excutive type
     * @author 
     * 
     * @param string 
     */
    public function executive_type_get(){
        try {
        //$executive_id = $this->input->post('executive_id');
		//$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		//$token_data =$this->validate_token($authorization_exp[1]);
        $data = $this->executive_type_model->fields('id, executive_type')->get_all();
        
        $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        //print_r($data);
        }
        catch (Exception $ex) {
            $this->set_response_simple(NULL, $ex->getMessage(), REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
       
    }


    /**
     * @desc To get vendor list with Excutive id
     * @author Tejaswini
     * 
     * @param string 
     */
    public function vendor_list_get(){

        $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $data= $this->vendor_list_model->with_location("fields:id,address")->where('executive_user_id',$token_data->id)->get_all();
        if(!empty($data)){
            foreach($data as $k => $vendor){
                $data[$k]['image'] = base_url() . 'uploads/list_cover_image/list_cover_' . $data[$k]['id']. '.jpg';
            }
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }else{
            $this->set_response_simple(NULL, "Sorry, no vendors found!", REST_Controller::HTTP_OK, FALSE);
        }
        
    }

    /**
     * @desc To Generate Otp for excutive app
     * @author author tejaswini
     */
    public function otp_gen_post() {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->form_validation->set_rules($this->user_model->rules['otp']);
        if($this->form_validation->run() ==  FALSE){
            $this->set_response_simple(NULL, explode('.', validation_errors())[0], REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
        }else{
            $mobile = $this->input->post('mobile');
            $password = (empty($this->input->post('passowrd')))? 1234 : $this->input->post('passowrd');
            $check_mobile = $this->user_model->where('phone', $mobile)->as_array()->get();
            $otp = '';
            if(! empty($check_mobile)){
                $user_id = $check_mobile['id'];
                $otp = rand(154564, 564646);
                $user_exist_in_otp = $this->otp_model->where('user_id', $check_mobile['id'])->as_array()->get();
                
                if(! empty($user_exist_in_otp)){
                    $this->otp_model->update([
                        'user_id' => $check_mobile['id'],
                        'otp' => $otp,
                        'is_expired' => 0,
                        'updated_at' => date('y-m-d h:i:s')
                    ], 'user_id');
                }else{
                $this->otp_model->insert(['user_id' => $check_mobile['id'], 'otp' => $otp]);
                }
            }else{
                $user_group = $this->user_group_model->where('user_id', $check_mobile['id'])->get();
                $group = $this->group_model->where('id', $this->config->item('executive_group_id', 'ion_auth'))->get();
                    
                    if($user_group['group_id'] != $group['id']){
                        $unique_id = generate_serial_no($group['code'], 4, $group['last_id']);
                        $this->group_model->update([
                            'last_id' => $group['last_id'] + 1
                        ], $group['id']);
                    
                        $additional_data = array(
                            'unique_id' => $unique_id,
                            'phone' => $mobile,
                            'active' => 1,
                            'created_at' => date('Y-m-d H:i:s')
                        );

                        $group_id[0] = $this->config->item('executive_group_id', 'ion_auth'); //$group['id'];
                        $user_id = $this->ion_auth->register($mobile, $password,NULL, $additional_data, $group_id);
                        log_message('error', $this->ion_auth->errors());
                        /* $user_id = $this->user_model->insert(['phone' => $mobile, 'active' => 1, 'unique_id' => $unique_id]);
                        $this->db->insert('users_groups', ['user_id' => $user_id, 'group_id' => $group['id']]); */
                        if(! empty($user_id)){
                            $otp = rand(154564, 564646);
                            $this->otp_model->insert(['user_id' => (empty($user_id))? NULL : $user_id, 'otp' => $otp]);
                        }
                    } else {
                    $this->set_response_simple(NULL, 'Invalid Group', REST_Controller::HTTP_OK, FALSE);
                }
            }
            
            if(! empty($otp)){
                $user = $this->user_model->fields('first_name, last_name')->where('id', $user_id)->get();
                //$this->send_sms('OTP : '.$otp.' is your Nextclick verfication code. Please do not share it with any one. Than Q.', $mobile);
                $this->send_sms('\'Dear User your OTP for Registration is '.$otp.', Use this Password to validate your Login. Regards, NEXTCLICK.\'', $mobile);
                $this->set_response_simple(['user' => $user, 'otp' => $otp], 'Otp Generated', REST_Controller::HTTP_OK, TRUE);
            }else{
                $this->set_response_simple(NULL, 'Internal server error', REST_Controller::HTTP_CONFLICT, FALSE);
            }
            
        }
    }
    
}