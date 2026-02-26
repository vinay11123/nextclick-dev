<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';
require('vendor/autoload.php');

use Firebase\JWT\JWT;
use Rakit\Validation\Validator;

class Auth extends MY_REST_Controller
{
    public $intentsArr = ["user", "delivery_partner", "vendor", "executive"];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
		$this->load->library('input');
        $this->load->model('user_group_model');
        $this->load->model('group_model');
        $this->load->helper('date');
        $this->load->model('social_auth_model');
        $this->load->model('app_details_model');
        $this->load->model('otp_model');
        $this->load->model('location_model');
        $this->load->model('user_doc_model');
        $this->load->model('user_session_model');
        $this->load->model('business_info_model');
        $this->load->model('setting_model');
    }

    /**
     *
     * @author Mehar
     * Login Api
     */
    public function login_post()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
		
        $errorMessage = "";
        $identity = html_escape($this->input->post('identity'));
        $this->ion_auth_model->identity_column = 'phone';
        $this->config->set_item('identity', 'phone');
        $password = html_escape($this->input->post('password'));
        $intent = html_escape($this->input->post('intent'));
        $otp = html_escape($this->input->post('otp'));
        $userID = null;
        $login_one = null;
        $userExists = $this->user_model->where('phone', $identity)->get();
        if (empty($userExists)) {
            $errorMessage = "USER_NOT_EXISTS";
            $login_one = FALSE;
            $data = null;
            if ($intent == 'user') {
                $result = $this->otp_model->validate($identity, $otp);
                if ($result && $result['success']) {
                    $data = "VALID_OTP";
                }
            }
            return $this->set_response_simple($data, $errorMessage, REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
        } else {
            $userID = $userExists['id'];
        }
        if (empty($password) && empty($otp)) {
            return $this->set_response_simple(null, 'REQUIRED_FIELDS_MISSING', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
        } else {
            if (isset($otp) && !empty(isset($otp))) {
                $login_one = $this->otp_model->validate($identity, $otp);
                $login_one = $login_one['success'];
                if (!$login_one) {
                    $errorMessage = "INCORRECT_OTP";
                    $login_one = FALSE;
                }
            } else {
                $login_one = $this->ion_auth->login($identity, $password);

                $userID = $this->ion_auth->get_user_id();
                if (!$login_one) {
                    $errorMessage = "INCORRECT_PASSWORD";
                }
            }
            if (!$login_one) {
                return $this->set_response_simple(null, $errorMessage, REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                $this->ion_auth_model->identity_column = 'unique_id';
                $this->config->set_item('identity', 'unique_id');
                $login_two = $this->ion_auth->login($identity, $password);
                if ($login_two) {

                    $user_data = $this->user_model->fields('unique_id,username,email,phone')
                        ->with_groups('fields: id, name')
                        ->where('id', $this->ion_auth->get_user_id())
                        ->get();
                    $timestamp = now();
                    $token = array(
                        "id" => $this->ion_auth->get_user_id(),
                        "userdetail" => $user_data,
                        "time" => $timestamp
                    );
                    $jwt = JWT::encode($token, $this->config->item('jwt_key'));

                    $is_access_available = $this->is_access_available($this->input->get_request_header('APP_ID'), $user_data['groups']);
                    if ($is_access_available) {
                        $this->set_response_simple([
                            "token" => $jwt
                        ], 'Login SuccessFully.!', http_response_code(), TRUE);
                    } else {
                        $this->set_response_simple("Sorry, Access is not available", 'Valdation error..!', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                    }
                } else {
                    $this->set_response_simple($this->ion_auth->errors(), 'Failed', http_response_code(), FALSE);
                }
            } else {
                $userGroup = $this->user_group_model->isApprovalPending($userID, $intent);
                // 🔹 FETCH REFERRAL CODE (ONCE)
                $user_referral = $this->user_model
                    ->fields('referral_code')
                    ->where('id', $userID)
                    ->get();
                
                $referral_code = $user_referral['referral_code'] ?? null;

                $user_data = $this->user_model->fields('display_name,email,phone')
                    ->with_groups('fields: id, name')
                    ->where('id', $userID)
                    ->get();

                $timestamp = now();
                $token = array(
                    "id" => $userID,
                    "userdetail" => $user_data,
                    "time" => $timestamp
                );
                $jwt = JWT::encode($token, $this->config->item('jwt_key'));

                $is_access_available = $this->is_access_available($this->input->post('APP_ID'), $user_data['groups']);
				//print_r($userGroup);
				//echo $is_access_available; exit;
				
                if ($userGroup['success'] && $userGroup['status']) {
                    // && $userGroup['status'] !== 2
                    if ($is_access_available && !empty($userGroup['status'])) {
                        if ($userGroup['status'] == 1) {
                            $this->user_session_model->save($userID, array_key_first($user_data['groups']), $jwt, $timestamp);
                        }
                    $this->set_response_simple([
                        "token"           => $jwt,
                        "approval_status" => $userGroup['status'],
                        "referral_code"   => $referral_code
                    ], 'Login SuccessFully.!', http_response_code(), TRUE);

                    } else {
                        $this->set_response_simple(null, 'ACCESS_NOT_AVAILABLE', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                    }
                } else if ($is_access_available) {
                    $this->user_session_model->save($userID, array_key_first($user_data['groups']), $jwt, $timestamp);
                    $this->set_response_simple([
                        "token" => $jwt
                    ], 'Login SuccessFully.!', http_response_code(), TRUE);
                } else {
                    $this->set_response_simple(null, 'ACCESS_NOT_AVAILABLE', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                }
            }
        }
    }


    /**
     * @desc To login
     * @author Tejaswini
     * 
     * @param string $type
     */
    public function social_login_post($type = 'google')
    {
        if ($type == 'google') {
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $is_auth_id_exist = $this->social_auth_model->where('auth_id', $this->input->post('auth_id'))->get();
            if (empty($is_auth_id_exist)) {

                if (empty($this->input->post('mail')) && empty($this->input->post('mobile'))) {
                    //This block gets invoked when user login with facebook and both email and mobile not provided by facebook sdk
                    $userID = null;
                    $social_id = $this->social_auth_model->insert([
                        'auth_id' => $this->input->post('auth_id'),
                        'auth_token' => $this->input->post('auth_token'),
                        'mail' => $this->input->post('mail'),
                        'name' => $this->input->post('name'),
                        'mobile' => $this->input->post('mobile'),
                    ]);
                    $group = $this->group_model->where('name', 'user')->get();
                    if (!empty($group)) {
                        $unique_id = generate_serial_no($group['code'], 4, $group['last_id']);
                        $this->group_model->update([
                            'last_id' => $group['last_id'] + 1
                        ], $group['id']);
                        $email = strtolower($this->input->post('mail'));
                        $mobile = strtolower($this->input->post('mobile'));
                        $identity = (!empty($email)) ? $email : $unique_id;
                        $additional_data = array(
                            'first_name' => $this->input->post('name'),
                            'display_name' => $this->input->post('name'),
                            'unique_id' => $unique_id,
                            'phone' => $mobile,
                            'email' => $email,
                            'active' => 1
                        );
                        $group_id[] = $group['id'];
                        if ($group['id'] != $this->config->item('user_group_id', 'ion_auth'))
                            array_push($group_id, $this->config->item('user_group_id', 'ion_auth'));

                        $password = rand();
                        if($userID){
                            $user_id = $userID;
                        }else{
                            $user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, 'user');
                        }
                        if ($user_id) {
                            $this->social_auth_model->update([
                                'id' => $social_id,
                                'unique_id' => $unique_id,
                                'user_id' => $user_id,
                                'password' => base64_encode($password),
                            ], 'id');
                            $this->user_model->update([
                                'primary_intent' => 'user',
                            ], $user_id);
                            $timestamp = now();
                            $login = $this->user_model->where('id', $user_id)->get();
                            $token = array(
                                "id" => $user_id,
                                "time" => $timestamp
                            );
                            $jwt = JWT::encode($token, $this->config->item('jwt_key'));
                            $this->set_response_simple([
                                "token" => $jwt,
                                "is_mail_existed" => (!empty($login['email'])) ? 1 : 0,
                                "is_phone_existed" => (!empty($login['phone'])) ? 1 : 0,
                            ], 'Login SuccessFully.!', http_response_code(), TRUE);
                            $check_mobile = $this->user_model->where('id', $user_id)->get();
                            if(empty($check_mobile['phone'])){
                                $user_mobile = $this->user_model->update([
                                    'phone' => $check_mobile['id']
                                ],$check_mobile['id']);
                            }
                            if(empty($check_mobile['email'])){
                                $user_mobile = $this->user_model->update([
                                    'email' => $check_mobile['id']
                                ],$check_mobile['id']);
                            }
                        } else {
                            return $this->set_response_simple(NULL, $this->ion_auth->errors(), REST_Controller::HTTP_OK, FALSE);
                        }
                    } else {
                        return $this->set_response_simple(NULL, 'Group is not available', REST_Controller::HTTP_CONFLICT, FALSE);
                    }
                }
                else if (($this->check_user_email($this->input->post('mail')) == FALSE) && ($this->check_user_phone($this->input->post('mobile')) == FALSE)) {
                    $user_email = null;
                    if($this->input->post('mail')){
                        $user_email = $this->user_model->where('email', $this->input->post('mail'))->get();
                    }
                    if(!empty($this->input->post('mobile'))){
                        $user_mobile = $this->user_model->where('phone', $this->input->post('mobile'))->get();
                        // if($user_email['id'] == $user_mobile['id']){
                            $user= (empty($user_mobile)) ? (!empty($user_email) ? $user_email['id'] : null) : $user_mobile['id'];
                            $unique_id= (empty($user_mobile)) ? (!empty($user_email) ? $user_email['unique_id'] : null) : $user_mobile['unique_id'];
                            $timestamp = now();
                            $social_user =null;
                            if($user){
                                $social_user = $this->social_auth_model->where('user_id', $user)->get();
                            }
                            if( empty($social_user)){
                                $social_id = $this->social_auth_model->insert([
                                    'auth_id' => $this->input->post('auth_id'),
                                    'auth_token' => $this->input->post('auth_token'),
                                    'mail' => $this->input->post('mail'),
                                    'name' => $this->input->post('name'),
                                    'mobile' => $this->input->post('mobile'),
                                    "user_id" =>  $user,
                                    'unique_id' => $unique_id,

                                ]);
                                $details_check = $this->social_auth_model->where('user_id', $user)->get();
                                $token = array(
                                    "id" => $social_id,
                                    "time" => $timestamp
                                );
                                $jwt = JWT::encode($token, $this->config->item('jwt_key'));
                                $this->set_response_simple([
                                    "token" => $jwt,
                                    "is_mail_existed" => (! empty($details_check['mail'])) ? 1 : 0,
                                    "is_phone_existed" => (! empty($details_check['mobile'])) ? 1 : 0,
                                ], 'Login SuccessFully.!', http_response_code(), TRUE);
                                $check_mobile = $this->user_model->where('id', $details_check['user_id'])->get();
                                if(empty($check_mobile['phone'])){
                                    $user_mobile = $this->user_model->update([
                                        'phone' => $check_mobile['id']
                                    ],$check_mobile['id']);
                                }
                            }else{
                                $token = array(
                                    "id" => $social_user['user_id'],
                                    "time" => $timestamp
                                );
                                $jwt = JWT::encode($token, $this->config->item('jwt_key'));
                                $this->set_response_simple([
                                    "token" => $jwt,
                                    "is_mail_existed" => (! empty($social_user['mail'])) ? 1 : 0,
                                    "is_phone_existed" => (! empty($social_user['mobile'])) ? 1 : 0,
                                ], 'Login SuccessFully.!', http_response_code(), TRUE);
                                $check_mobile = $this->user_model->where('id', $social_user['user_id'])->get();
                                if(empty($check_mobile['phone'])){
                                    $updateRecord = [];
                                    $updateRecord['phone'] = $check_mobile['id'];
                                    $user_mobile = $this->user_model->update($updateRecord, $check_mobile['id']);
                                }
                            }
                        // }else {
                        //     $this->set_response_simple(NULL, 'User already registered with given email or phone', REST_Controller::HTTP_OK, FALSE);
                        // }
                    } else{
                        $user= $user_email['id'];
                        $unique_id= $user_email['unique_id'];
                        $mobile = $user_email['phone'];
                        $timestamp = now();
                        $social_user = $this->social_auth_model->where('user_id', $user)->get();
                        if( empty($social_user)){
                            $social_id = $this->social_auth_model->insert([
                                'auth_id' => $this->input->post('auth_id'),
                                'auth_token' => $this->input->post('auth_token'),
                                'mail' => $this->input->post('mail'),
                                'name' => $this->input->post('name'),
                                'mobile' => $mobile,
                                "user_id" =>  $user,
                                'unique_id' => $unique_id,

                            ]);
                            
                                $details_check = $this->social_auth_model->where('user_id', $user)->get();
                                $token = array(
                                    "id" => $social_id,
                                    "time" => $timestamp
                                );
                                $jwt = JWT::encode($token, $this->config->item('jwt_key'));
                                $this->set_response_simple([
                                    "token" => $jwt,
                                    "is_mail_existed" => (! empty($details_check['mail'])) ? 1 : 0,
                                    "is_phone_existed" => (! empty($details_check['mobile'])) ? 1 : 0,
                                ], 'Login SuccessFully.!', http_response_code(), TRUE);
                                $check_mobile = $this->user_model->where('id', $details_check['user_id'])->get();
                                if(empty($check_mobile['phone'])){
                                    $updateRecord = [];
                                    $updateRecord['phone'] = $check_mobile['id'];
                                    if($this->input->post('name') && !empty($this->input->post('name'))){
                                        $updateRecord['first_name'] = $this->input->post('name');
                                        $updateRecord['display_name'] = $this->input->post('name');
                                    }
                                    $user_mobile = $this->user_model->update($updateRecord, $check_mobile['id']);
                                }
                        }
                    }
                
                } else {
                    $userID = null;
                    if(!empty($this->input->post('mail'))){
                        $dupMail = $this->user_model->where([
                            'email'=> $this->input->post('mail')
                        ])->get();
                        if(!empty($dupMail)){
                            $userID =  $dupMail['id'];
                        }
                    }
                    if(!empty($this->input->post('mobile')) && $userID===null){
                        $dupMobile = $this->user_model->where([
                            'phone'=> $this->input->post('mobile')
                        ])->get();
                        $userID =  $dupMobile['id'];
                    }
                    $social_id = $this->social_auth_model->insert([
                        'auth_id' => $this->input->post('auth_id'),
                        'auth_token' => $this->input->post('auth_token'),
                        'mail' => $this->input->post('mail'),
                        'name' => $this->input->post('name'),
                        'mobile' => $this->input->post('mobile'),
                    ]);
                    $group = $this->group_model->where('name', 'user')->get();
                    if (!empty($group)) {
                        $unique_id = generate_serial_no($group['code'], 4, $group['last_id']);
                        $this->group_model->update([
                            'last_id' => $group['last_id'] + 1
                        ], $group['id']);
                        $email = strtolower($this->input->post('mail'));
                        $mobile = strtolower($this->input->post('mobile'));
                        $identity = (!empty($email)) ? $email : $unique_id;
                        $additional_data = array(
                            'first_name' => $this->input->post('name'),
                            'display_name' => $this->input->post('name'),
                            'unique_id' => $unique_id,
                            'phone' => $mobile,
                            'email' => $email,
                            'active' => 1
                        );
                        $group_id[] = $group['id'];
                        if ($group['id'] != $this->config->item('user_group_id', 'ion_auth'))
                            array_push($group_id, $this->config->item('user_group_id', 'ion_auth'));

                        $password = rand();
                        if($userID){
                            $user_id = $userID;
                        }else{
                            $user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, 'user');
                        }
                        if ($user_id) {
                            $this->social_auth_model->update([
                                'id' => $social_id,
                                'unique_id' => $unique_id,
                                'user_id' => $user_id,
                                'password' => base64_encode($password),
                            ], 'id');
                            $this->user_model->update([
                                'primary_intent' => 'user',
                            ], $user_id);
                            $timestamp = now();
                            $login = $this->user_model->where('id', $user_id)->get();
                            $token = array(
                                "id" => $user_id,
                                "time" => $timestamp
                            );
                            $jwt = JWT::encode($token, $this->config->item('jwt_key'));
                            $this->set_response_simple([
                                "token" => $jwt,
                                "is_mail_existed" => (!empty($login['email'])) ? 1 : 0,
                                "is_phone_existed" => (!empty($login['phone'])) ? 1 : 0,
                            ], 'Login SuccessFully.!', http_response_code(), TRUE);
                            $check_mobile = $this->user_model->where('id', $user_id)->get();
                            if(empty($check_mobile['phone'])){
                                $user_mobile = $this->user_model->update([
                                    'phone' => $check_mobile['id']
                                ],$check_mobile['id']);
                            }
                        } else {
                            return $this->set_response_simple(NULL, $this->ion_auth->errors(), REST_Controller::HTTP_OK, FALSE);
                        }
                    } else {
                        return $this->set_response_simple(NULL, 'Group is not available', REST_Controller::HTTP_CONFLICT, FALSE);
                    }
                }
            } else {
                $login = $this->social_auth_model->where('auth_id', $is_auth_id_exist['auth_id'])->get();
                $user = $this->user_model->where('id', $is_auth_id_exist['user_id'])->get();
                if ($user) {
                    $timestamp = now();
                    $token = array(
                        "id" => $login['user_id'],
                        "time" => $timestamp
                    );
                    $jwt = JWT::encode($token, $this->config->item('jwt_key'));
                    return $this->set_response_simple([
                        "token" => $jwt,
                        "is_mail_existed" => (! empty($user['email']))? 1 : 0,
                        "is_phone_existed" => (! empty($user['phone']))? 1 : 0,
                    ], 'Login SuccessFully.!', http_response_code(), TRUE);
                } else {
                    return $this->set_response_simple(NULL, "Login Failed..!", REST_Controller::HTTP_CONFLICT, FALSE);
                }
               /* if(! empty($this->input->post('mail'))){
                    if(empty($login['email']) && $this->check_user_email($this->input->post('mail')) == TRUE ){
                        $user_id = $this->user_model->update([
                            'id' => $login['user_id'],
                            'email' => $this->input->post('mail'),
                        ],'id');
                        $social_id = $this->social_auth_model->update([
                            'user_id' => $login['user_id'],
                            'mail' => $this->input->post('mail'),
                        ],'user_id');
                    }else {
                        $userd = $this->user_model->where('email', $this->input->post('mail'))->get();
                        $timestamp = now();
                        $token = array(
                            "id" => $userd['id'],
                            "time" => $timestamp
                        );
                        $jwt = JWT::encode($token, $this->config->item('jwt_key'));
                        return $this->set_response_simple([
                                "token" => $jwt,
                                "is_mail_existed" => 1,
                                "is_phone_existed" => (!empty($userd['phone'])) ? 1 : 0,
                            ], 'Login SuccessFully.!', http_response_code(), TRUE);
                    }
                }
                if(! empty($this->input->post('mobile'))){
                    if(empty($login['phone']) && $this->check_user_phone($this->input->post('mobile')) == TRUE ){
                        $user_id = $this->user_model->update([
                            'id' => $login['user_id'],
                            'phone' => $this->input->post('mobile'),
                        ],'id');
                        $social_id = $this->social_auth_model->update([
                            'user_id' => $login['user_id'],
                            'mobile' => $this->input->post('mobile'),
                        ], 'user_id');
                    }else {
                        $userd = $this->user_model->where('phone', $this->input->post('mobile'))->get();
                        $timestamp = now();
                        $token = array(
                            "id" => $userd['id'],
                            "time" => $timestamp
                        );
                        $jwt = JWT::encode($token, $this->config->item('jwt_key'));
                        return $this->set_response_simple([
                                "token" => $jwt,
                                "is_mail_existed" => (!empty($userd['email'])) ? 1 : 0,
                                "is_phone_existed" => 1,
                            ], 'Login SuccessFully.!', http_response_code(), TRUE);
                        return $this->set_response_simple(NULL, 'Given mobile Already used by another user', REST_Controller::HTTP_OK, FALSE);
                    }
                }
                $details_check = $this->social_auth_model->where('auth_id', $is_auth_id_exist['auth_id'])->get();
                if ($login) {
                    $timestamp = now();
                    $token = array(
                        "id" => $login['user_id'],
                        "time" => $timestamp
                    );
                    $jwt = JWT::encode($token, $this->config->item('jwt_key'));
                    return $this->set_response_simple([
                        "token" => $jwt,
                        "is_mail_existed" => (! empty($details_check['mail']))? 1 : 0,
                        "is_phone_existed" => (! empty($details_check['mobile']))? 1 : 0,
                    ], 'Login SuccessFully.!', http_response_code(), TRUE);
                    $check_mobile = $this->user_model->where('id', $details_check['user_id'])->get();
                    if(empty($check_mobile['phone'])){
                        $user_mobile = $this->user_model->update([
                            'phone' => $check_mobile['id']
                        ],$check_mobile['id']);
                    }
                } else {
                    return $this->set_response_simple(NULL, "Login Failed..!", REST_Controller::HTTP_CONFLICT, FALSE);
                }*/
            }
            if (!file_exists(base_url() . 'uploads/profile_image/')) {
                mkdir(base_url() . 'uploads/profile_image/', 0777, true);
            }
            file_put_contents("./uploads/profile_image/profile_" . $this->input->post('user_id') . ".jpg", base64_decode($this->input->post('profile_image')));
        }
    }

    /**
     * @desc Forgot password Recovery
     * @param string emial
     * @author Mehar
     */
    public function forgot_password_post()
    {
		$_POST = json_decode(file_get_contents("php://input"), TRUE);
        $identity_column = $this->config->item('identity', 'ion_auth');
        $mobile = $this->input->post('mobile');
        $email = $this->input->post('email');
        $identity = null;
		
        if (empty($mobile) && empty($email)) {
            $this->set_response_simple(NULL, 'Identity not found', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
        } else {
            if (!empty($mobile)) {
                $identity = $this->ion_auth->where($identity_column, $mobile)->users()->row();
            } else {
                $identity = $this->ion_auth->where('email', $email)->users()->row();
            }
        }
		
        if (empty($identity) || $identity == null) {
            $this->set_response_simple(NULL, 'Identity not found', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
        } else {
			
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});
			
            if ($forgotten) {
                $this->set_response_simple(NULL, $this->ion_auth->messages(), REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, $this->ion_auth->errors(), REST_Controller::HTTP_NO_CONTENT, FALSE);
            }
        }
    }

    public function verify_post()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);

        $this->set_response_simple([
            "token" => $this->validate_token($authorization_exp[1])
        ], 'verify', http_response_code(), TRUE);
    }

    public function register_post()
    {
		
        try {
            $validator = new Validator;
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $postData = $_POST;


            if (!in_array($postData['primary_intent'], $this->intentsArr)) {
                throw new Exception("INVALID_INTENT");
            }
            $validation = $validator->make($postData, [
                'first_name'                  => 'required|max:45',
                'last_name'                 => 'nullable|max:45',
                'display_name'              => 'required|max:90',
                'email'      => 'required|email',
                'phone'                => 'required|regex:^[6-9]\d{9}$^',
                'primary_intent'                => 'required',
                'password'              => 'required|min:6',
                'profile_image'                 => 'nullable',
            ]);
            $validation->validate();
            if ($validation->fails()) {
                $errors = $validation->errors();
                $this->set_response_simple(NULL, $errors->firstOfAll(), REST_Controller::HTTP_FORBIDDEN, FALSE);
                return;
            } else {
                $postData['email'] = strtolower($postData['email']);
                $unique_id = $postData['phone'];
                $referral_code = isset($postData['referral_code']) ? $postData['referral_code'] : null;
                $executive_user_id = null;
                $executive_referral_amount = null;
                $check_existing = $this->user_model->where('email', $postData['email'])
                    ->or_where('phone', $postData['phone'])
                    ->get();
                if ($check_existing && $check_existing['id']) {
                    $this->set_response_simple(Null, "DUPLICATE_IDENTITY", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                    return;
                }
                if(!empty($referral_code)) {
                    $check_referral_existing = $this->user_model->where('referral_code', $postData['referral_code'])->get();
                    if (!isset($check_referral_existing['id'])) {
                        $this->set_response_simple(Null, "Invalid Referral Code", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                        return;
                    }
                    else {
                        $executive_user_id = $check_referral_existing['id'];
                        $referral_used_count = $this->user_model->getReferralUsedCount($executive_user_id);
                        //Get user referral amount from settings.
                        $settings =	$this->setting_model->where("key", 'user_referral_amount')->get();
                        $executive_referral_amount = $settings['value'];
                    }
                }
                $identity = ($this->config->item('identity', 'ion_auth') === 'email') ? $postData['email'] : $unique_id;
				$additional_data = array(
                    'first_name' => $postData['first_name'],
                    'last_name' => $postData['last_name'],
                    'display_name' => $postData['display_name'],
                    'email' => $postData['email'],
                    'phone' => $postData['phone'],
                    'primary_intent' => $postData['primary_intent'],
                    'executive_user_id' => $executive_user_id,
                    'executive_referral_amount' => $executive_referral_amount
                );
                $user_id = $this->ion_auth->register($identity, $postData['password'], $postData['email'], $additional_data, $postData['primary_intent']);
                if ($user_id) {
                    // $user_unique_id = $this->user_model->fields('unique_id')->where('id', $user_id)->get();
                    $this->user_model->createUserDefaults($user_id);
                    if (isset($postData['profile_image']) && !empty($postData['profile_image'])) {

                        if (!file_exists('./uploads/profile_image/')) {
                            mkdir('./uploads/profile_image/', 0777, true);
                        }
                        if (file_exists("./uploads/profile_image/profile_" . $user_id . ".jpg")) {
                            unlink('./uploads/' . 'profile' . '_image/' . 'profile' . '_' . $user_id . '.jpg');
                            file_put_contents("./uploads/profile_image/profile_" . $user_id . ".jpg", base64_decode($postData['profile_image']));
                        } else {
                            file_put_contents("./uploads/profile_image/profile_" . $user_id . ".jpg", base64_decode($postData['profile_image']));
                        }
                    }
                    $login_one = $this->ion_auth->login($identity, $postData['password']);
                    $returnData = [
                        'phone' => $postData['phone'],
                        'user_id' => $user_id
                    ];

                    /**
                     * Login Logic Starts Here
                     */
                    $userGroup = $this->user_group_model->isApprovalPending($user_id, $postData['primary_intent']);
                    $user_data = $this->user_model->fields('display_name,email,phone')
                        ->with_groups('fields: id, name')
                        ->where('id', $user_id)
                        ->get();
                    $timestamp = now();
                    $token = array(
                        "id" => $user_id,
                        "userdetail" => $user_data,
                        "time" => $timestamp
                    );
                    $jwt = JWT::encode($token, $this->config->item('jwt_key'));
                    $is_access_available = $this->is_access_available($this->input->get_request_header('APP_ID'), $user_data['groups']);
                    if ($userGroup['success'] && $userGroup['status']) {
                        // && $userGroup['status'] !== 2
                        if ($is_access_available && !empty($userGroup['status'])) {
                            if ($userGroup['status'] == 1) {
                                $this->user_session_model->save($user_id, array_key_first($user_data['groups']), $jwt, $timestamp);
                            }
                            $returnData['session'] = [
                                "success" => true,
                                "token" => $jwt,
                                "approval_status" => $userGroup['status']
                            ];
                        } else {
                            $returnData['session'] = [
                                "success" => false,
                                "message" => 'ACCESS_NOT_AVAILABLE'
                            ];
                        }
                    } else if ($is_access_available) {
                        $this->user_session_model->save($user_id, array_key_first($user_data['groups']), $jwt, $timestamp);
                        $returnData['session'] = [
                            "success" => true,
                            "token" => $jwt
                        ];
                    } else {
                        $returnData['session'] = [
                            "success" => false,
                            "message" => 'ACCESS_NOT_AVAILABLE'
                        ];
                    }
                    /**
                     * Login logic Ends Here
                     */
                    $this->set_response_simple($returnData, $this->ion_auth->messages(), REST_Controller::HTTP_OK, TRUE);
                } else {
                    $this->set_response_simple($user_id, $this->ion_auth->errors(), REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                }
            }
        } catch (Exception $ex) {
            $this->set_response_simple(NULL, NULL, REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }

    /**
     * @desc To Generate Otp
     * @author author Mehar
     */
    public function otp_gen_post()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $this->form_validation->set_rules($this->user_model->rules['otp']);
        if ($this->form_validation->run() ==  FALSE) {
            $this->set_response_simple(NULL, explode('.', validation_errors())[0], REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
        } else {
            $mobile = $this->input->post('mobile');
            $password = (empty($this->input->post('passowrd'))) ? 1234 : $this->input->post('passowrd');
            $check_mobile = $this->user_model->where('phone', $mobile)->as_array()->get();

            $otp = '';
            if (!empty($check_mobile)) {
                $user_id = $check_mobile['id'];
                $otp = rand(154564, 564646);
				//$otp = '123456';
                $user_exist_in_otp = $this->otp_model->where('user_id', $check_mobile['id'])->as_array()->get();

                if (!empty($user_exist_in_otp)) {
                    $this->otp_model->update([
                        'user_id' => $check_mobile['id'],
                        'otp' => $otp,
                        'is_expired' => 0,
                        'updated_at' => date('y-m-d h:i:s')
                    ], 'user_id');
                } else {
                    $this->otp_model->insert(['user_id' => $check_mobile['id'], 'otp' => $otp]);
                }
            } else {
                $group = $this->group_model->where('id', $this->config->item('user_group_id', 'ion_auth'))->get();
                if (!empty($this->input->get_request_header('APP_ID'))) {
                    $app = $this->app_details_model->where('app_id', $this->input->get_request_header('APP_ID'))->get();
                    if (!empty($app)) {
                        $group = $this->group_model->where('id', max(explode(',', $app['allowed_groups'])))->get();
                    }
                }
                if (!empty($group)) {
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

                    $group_id[0] = $this->config->item('user_group_id', 'ion_auth'); //$group['id'];
                    $user_id = $this->ion_auth->register($mobile, $password, NULL, $additional_data, $group_id);
                    log_message('error', $this->ion_auth->errors());
                    /* $user_id = $this->user_model->insert(['phone' => $mobile, 'active' => 1, 'unique_id' => $unique_id]);
                    $this->db->insert('users_groups', ['user_id' => $user_id, 'group_id' => $group['id']]); */
                    if (!empty($user_id)) {
                        $otp = rand(154564, 564646);
						//$otp = '123456';
                        $this->otp_model->insert(['user_id' => (empty($user_id)) ? NULL : $user_id, 'otp' => $otp]);
                    }
                } else {
                    $this->set_response_simple(NULL, 'Invalid Group', REST_Controller::HTTP_OK, FALSE);
                }
            }

            if (!empty($otp)) {
                $user = $this->user_model->fields('first_name, last_name')->where('id', $user_id)->get();
                //$this->send_sms('OTP : '.$otp.' is your Nextclick verfication code. Please do not share it with any one. Than Q.', $mobile);
                $this->send_sms('Dear User your OTP for Registration is ' . $otp . ' ,Use this Password to validate your Login. Regards, NEXTCLICK INFO SOLUTIONS PRIVATE LIMITED', '1207169203114731853', $mobile);
                $this->set_response_simple(['user' => $user, 'otp' => $otp], 'Otp Generated', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'Internal server error', REST_Controller::HTTP_CONFLICT, FALSE);
            }
        }
    }

    public function otp_post()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $intent = $this->input->post('intent');
        $this->form_validation->set_rules($this->user_model->rules['otp']);
        if ($this->form_validation->run() ==  FALSE) {
            $this->set_response_simple(NULL, explode('.', validation_errors())[0], REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
        } else {
            $mobile = $this->input->post('mobile');
            if ($intent == "create_user") {
                $user = $this->user_model->where([
                    'phone' => $mobile
                ])->get();
                if (!empty($user)) {
                    $this->set_response_simple(Null, 'USER_ALREADY_EXISTS', REST_Controller::HTTP_OK, TRUE);
                    return;
                }
            }
            $otp = rand(154564, 564646);
			//$otp = '123456';
            $this->otp_model->insert(['mobile' => $mobile, 'otp' => $otp]);
            if (!empty($otp)) {
				$this->send_sms('Dear User your OTP for Registration is ' . $otp . ' ,Use this OTP to validate your Login. Regards, NEXTCLICK INFO SOLUTIONS PRIVATE LIMITED', '1207170582452344693', $mobile);
                $this->set_response_simple([
                    'otp' => $otp
                ], 'Otp Generated', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'Internal server error', REST_Controller::HTTP_CONFLICT, FALSE);
            }
        }
    }

    public function validate_otp_post()
    {
        $validator = new Validator;
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $postData = $_POST;
        $validation = $validator->make($postData, [
            'mobile'                  => 'required|regex:^[6-9]\d{9}$^',
            'otp'                 => 'required|min:6|max:6'
        ]);
        $validation->validate();
        if ($validation->fails()) {
            $errors = $validation->errors();
            $this->set_response_simple(NULL, $errors->firstOfAll(), REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            return;
        } else {
            $result = $this->otp_model->validate($postData['mobile'], $postData['otp']);
            if ($result && $result['success']) {
                $this->set_response_simple(Null, 'Otp Validated', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'Otp Validation Failed', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            }
        }
    }

    public function new_user_post($identity = NULL, $password = NULL, $mobile = NULL, $additional_data = NULL)
    {
        return $this->ion_auth->register($identity, (empty($password)) ? '123456' : $password, $mobile, $additional_data, [3]);
    }

    /**
     * @desc To Verify Otp
     * @author author Mehar
     */
    public function verify_otp_post()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->form_validation->set_rules($this->user_model->rules['otp']);
        $this->form_validation->set_rules('otp', 'OTP', 'required');
        if ($this->form_validation->run() ==  FALSE) {
            $this->set_response_simple(NULL, explode('.', validation_errors())[0], REST_Controller::HTTP_OK, FALSE);
        } else {

            $mobile = $this->input->post('mobile');
            $otp = $this->input->post('otp');
            $check_mobile = $this->user_model->with_groups('fields:id, name')->where('phone', $mobile)->as_array()->get();
            $is_verified = $this->otp_model->verify_otp($check_mobile, $otp);

            if (!empty($is_verified)) {

                $this->otp_model->update([
                    'user_id' => $check_mobile['id'],
                    'otp' => $otp,
                    'is_expired' => 1,
                    'updated_at' => date('y-m-d h:i:s')
                ], 'user_id');
                $timestamp = now();
                $token = array(
                    "id" => $check_mobile['id'],
                    "userdetail" => $check_mobile,
                    "time" => $timestamp
                );
                $jwt = JWT::encode($token, $this->config->item('jwt_key'));

                $is_access_available = $this->is_access_available($this->input->get_request_header('APP_ID'), $check_mobile['groups']);
                //if($is_access_available){
                $data = ['token' => $jwt, 'unique_id' => $check_mobile['unique_id'], 'first_name' => $check_mobile['first_name'], 'last_name' => $check_mobile['last_name']];

                $this->set_response_simple((empty($data)) ? NULL : $data, 'verified..!', REST_Controller::HTTP_OK, TRUE);
                //}else{
                //   $this->set_response_simple(NULL, "Sorry, Access is not available", REST_Controller::HTTP_OK, FALSE);
                // }
            } else {
                $this->set_response_simple(NULL, 'Invalid otp..!', REST_Controller::HTTP_OK, FALSE);
            }
        }
    }

    /**
     * @desc To register any user
     * @author Mehar
     * 
     * @param string $user_type
     */
    public function register1_post($user_type = 'delivery_partner')
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $this->form_validation->set_rules($this->user_model->rules[$user_type]);
        if ($this->form_validation->run() ==  FALSE) {
            $this->set_response_simple(NULL, explode('.', validation_errors())[0], REST_Controller::HTTP_OK, FALSE);
        } else {
            if ($user_type == 'delivery_partner') {
                $email = $this->input->post('email');
                $group = $this->group_model->where('id', $this->config->item('delivery_partner_group_id', 'ion_auth'))->get();
                $insert_data = [
                    'phone' => $this->input->post('mobile'),
                    'active' => 1,
                    "first_name" => $this->input->post('first_name'),
                    "last_name" => $this->input->post('last_name'),
                    "email" => $this->input->post('email'),
                    "vehicle_number" => $this->input->post('vehicle_number'),
                    "vehicle_insurance_number" => $this->input->post('vehicle_insurance_number'),
                    "vehicle_type_id" => $this->input->post('vehicle_type_id'),
                    "aadhar_number" => $this->input->post('aadhar_number'),
                    "pan_card_number" => $this->input->post('pan_card_number'),
                    "driving_license_number" => $this->input->post('driving_license_number'),
                    "permanent_address" => $this->input->post('permanent_address'),
                    "state" => $this->input->post('state'),
                    "district" => $this->input->post('district'),
                    "constituency" => $this->input->post('constituency'),
                    "pincode" => $this->input->post('pincode'),

                ];
            }
            if (!empty($group)) {
                $user = $this->user_model->with_groups('fields: id, name')->where('phone', $this->input->post('mobile'))->get();
                if (!empty($user)) {
                    $unique_id = $user['unique_id'];
                    $user_id = $user['id'];
                } else {
                    if ($this->check_user_email($email)) {
                        $unique_id = generate_serial_no($group['code'], 4, $group['last_id']);
                        $this->group_model->update([
                            'last_id' => $group['last_id'] + 1
                        ], $group['id']);
                        $insert_data['unique_id'] = $unique_id;
                        $group_id[0] =  $group['id'];
                        $user_id = $this->ion_auth->register($unique_id, (empty($this->input->post('password'))) ? '123456' : $this->input->post('password'), $this->input->post('email'), $insert_data, $group_id);
                    }
                }

                if (!empty($user_id)) {
                    $is_location_exist = $this->location_model->where(['latitude' => $this->input->post('latitude'), 'longitude' => $this->input->post('longitude')])->get();
                    if (empty($is_location_exist)) {
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
                        ]),
                        'id'
                    );

                    $this->user_doc_model->user_id = $user_id;
                    $is_docs_existed = $this->user_doc_model->where('created_user_id', $user_id)->get();
                    if (empty($is_docs_existed)) {
                        $this->user_doc_model->insert([
                            'unique_id' => $unique_id
                        ]);
                    }


                    if (!empty($user) &&  array_search($this->config->item('delivery_partner_group_id', 'ion_auth'), array_column($user['groups'], 'id')) === FALSE) {
                        $this->user_group_model->insert([
                            'user_id' => $user_id,
                            'group_id' => $this->config->item('delivery_partner_group_id', 'ion_auth')
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

                    if (!file_exists('uploads/' . 'profile' . '_image/')) {
                        mkdir('uploads/' . 'profile' . '_image/', 0777, true);
                    }
                    file_put_contents("./uploads/profile_image/profile_" . $unique_id . ".jpg", base64_decode($this->input->post('profile_image')));

                    $this->set_response_simple(NULL, "Successfully Registered.!", REST_Controller::HTTP_OK, TRUE);
                } else {
                    $this->set_response_simple(NULL, "Sorry, Email is already existed!", REST_Controller::HTTP_OK, FALSE);
                }
            } else {
                $this->set_response_simple(NULL, "Sorry, You're already an existing delivery partner.", REST_Controller::HTTP_OK, FALSE);
            }
        }
    }

    public function roles_get()
    {
        $data = $this->group_model->fields('id, name')->where('status', 1)->get_all();
        $this->set_response_simple($data, "List of roles", REST_Controller::HTTP_OK, TRUE);
    }

    // Cashfree APIs
    public function pan_no_verification_post() {

        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $pan_no = $this->input->post('pan_no');

        $messages = [];

        if(!empty($pan_no)) {            

            $input = ["pan" => $pan_no];

            $settings_client_id = $this->setting_model->where("key", 'cashfree_client_id')->get();
            $settings_client_secreat = $this->setting_model->where("key", 'cashfree_client_secret')->get();

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.cashfree.com/verification/pan',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($input),
            CURLOPT_HTTPHEADER => array(
                'x-client-id: ' . $settings_client_id['value'],
                'x-client-secret: ' . $settings_client_secreat['value'],
                'x-api-version: v1',
                'Content-Type: application/json'
            ),
            ));

            $json = curl_exec($curl);

            curl_close($curl);
            
            $response = json_decode($json, true);

            $this->set_response_simple($response, 'success', REST_Controller::HTTP_OK, TRUE);
        }
        else {
            $messages['pan_no'] = 'Pan number is required';            
            $this->set_response_simple(NULL, $messages, REST_Controller::HTTP_FORBIDDEN, FALSE);
                
        }
    }

    public function send_aadhaar_otp_post() {

        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $aadhaar_number = $this->input->post('aadhaar_number');

        $messages = [];

        if(!empty($aadhaar_number)) {            

            $input = ["aadhaar_number" => $aadhaar_number];

            $settings_client_id = $this->setting_model->where("key", 'cashfree_client_id')->get();
            $settings_client_secreat = $this->setting_model->where("key", 'cashfree_client_secret')->get();

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.cashfree.com/verification/offline-aadhaar/otp',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($input),
            CURLOPT_HTTPHEADER => array(
                'x-client-id: ' . $settings_client_id['value'],
                'x-client-secret: ' . $settings_client_secreat['value'],
                'x-api-version: v1',
                'Content-Type: application/json'
            ),
            ));

            $json = curl_exec($curl);

            curl_close($curl);
            
            $response = json_decode($json, true);

            $this->set_response_simple($response, 'success', REST_Controller::HTTP_OK, TRUE);
        }
        else {
            $messages['aadhaar_number'] = 'Aadhaar Number is required';            
            $this->set_response_simple(NULL, $messages, REST_Controller::HTTP_FORBIDDEN, FALSE);                
        }
    }

    public function verify_aadhaar_by_otp_post() {

        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $otp = $this->input->post('otp');
        $ref_id = $this->input->post('ref_id');

        $messages = [];

        if(empty($otp)) {
            $messages['otp'] = 'OTP is required';
        }

        if(empty($ref_id)) {
            $messages['ref_id'] = 'ref_id is required';
        }

        if(!empty($otp) && !empty($ref_id)) {            

            $input = [
                "otp" => $otp,
                "ref_id" => $ref_id
            ];

            $settings_client_id = $this->setting_model->where("key", 'cashfree_client_id')->get();
            $settings_client_secreat = $this->setting_model->where("key", 'cashfree_client_secret')->get();

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.cashfree.com/verification/offline-aadhaar/verify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($input),
            CURLOPT_HTTPHEADER => array(
                'x-client-id: ' . $settings_client_id['value'],
                'x-client-secret: ' . $settings_client_secreat['value'],
                'x-api-version: v1',
                'Content-Type: application/json'
            ),
            ));

            $json = curl_exec($curl);

            curl_close($curl);
            
            $response = json_decode($json, true);

            $this->set_response_simple($response, 'success', REST_Controller::HTTP_OK, TRUE);
        }
        else {                       
            $this->set_response_simple(NULL, $messages, REST_Controller::HTTP_FORBIDDEN, FALSE);                
        }
    }

    public function verify_gst_post() {

        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $gstin = $this->input->post('gstin');

        $messages = [];

        if(!empty($gstin)) {            

            $input = ["GSTIN" => $gstin];

            $settings_client_id = $this->setting_model->where("key", 'cashfree_client_id')->get();
            $settings_client_secreat = $this->setting_model->where("key", 'cashfree_client_secret')->get();

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.cashfree.com/verification/gstin',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($input),
            CURLOPT_HTTPHEADER => array(
                'x-client-id: ' . $settings_client_id['value'],
                'x-client-secret: ' . $settings_client_secreat['value'],
                'x-api-version: v1',
                'Content-Type: application/json'
            ),
            ));

            $json = curl_exec($curl);

            curl_close($curl);
            
            $response = json_decode($json, true);

            $this->set_response_simple($response, 'success', REST_Controller::HTTP_OK, TRUE);
        }
        else {
            $messages['gstin'] = 'GSTIN number is required';            
            $this->set_response_simple(NULL, $messages, REST_Controller::HTTP_FORBIDDEN, FALSE);
                
        }
    }

    public function verify_bank_details_post() {

        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $bankAccount = $this->input->post('bankAccount');
        $ifsc = $this->input->post('ifsc');

        $messages = [];

        if(empty($bankAccount)) {
            $messages['bankAccount'] = 'Bank Account is required';
        }

        if(empty($ifsc)) {
            $messages['ifsc'] = 'IFSC is required';
        }

        if(!empty($bankAccount) && !empty($ifsc)) {   
            
            $token = $this->getCashfreeToken();                              

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://payout-api.cashfree.com/payout/v1.2/validation/bankDetails?bankAccount='.$bankAccount.'&ifsc='.$ifsc,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$token
            ),
            ));

            $json = curl_exec($curl);

            curl_close($curl); 

            $response = json_decode($json, true);

            $this->set_response_simple($response, 'success', REST_Controller::HTTP_OK, TRUE);
        }
        else {                       
            $this->set_response_simple(NULL, $messages, REST_Controller::HTTP_FORBIDDEN, FALSE);                
        }
    }

    public function getCashfreeToken() {  
        
        $settings_client_id = $this->setting_model->where("key", 'cashfree_client_id')->get();
        $settings_client_secreat = $this->setting_model->where("key", 'cashfree_client_secret')->get();

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.cashfree.com/cac/v1/authorize',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'x-client-id: ' . $settings_client_id['value'],
            'x-client-secret: ' . $settings_client_secreat['value'],
        ),
        ));
        

        $json = curl_exec($curl);

            curl_close($curl);
            
        $response = json_decode($json, true);  
        
        if(isset($response['data']['token'])){
            return $response['data']['token'];
        }
        else{
            return null;
        }
 
    }

    public function verify_driving_licence_post() {

        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $driving_licence_no = $this->input->post('driving_licence_no');

        $messages = [];

        if(empty($driving_licence_no)) {
            $messages['driving_licence_no'] = 'Driving Licence number is required';
        }        

        if(!empty($driving_licence_no)) {            

            $input = [
                "verification_id" => $driving_licence_no."_".time(),
                "dl_number" => $driving_licence_no,
                "dob" => "1994-08-05"
            ];

            $settings_client_id = $this->setting_model->where("key", 'cashfree_client_id')->get();
            $settings_client_secreat = $this->setting_model->where("key", 'cashfree_client_secret')->get();

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.cashfree.com/verification/driving-license',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($input),
            CURLOPT_HTTPHEADER => array(
                'x-client-id: ' . $settings_client_id['value'],
                'x-client-secret: ' . $settings_client_secreat['value'],
                'x-api-version: v1',
                'Content-Type: application/json'
            ),
            ));

            $json = curl_exec($curl);

            curl_close($curl);
            
            $response = json_decode($json, true);

            $this->set_response_simple($response, 'success', REST_Controller::HTTP_OK, TRUE);
        }
        else {                       
            $this->set_response_simple(NULL, $messages, REST_Controller::HTTP_FORBIDDEN, FALSE);                
        }
    }

    public function verify_vehicle_registration_no_post() {

        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $vehicle_registration_no = $this->input->post('vehicle_registration_no');

        $messages = [];

        if(empty($vehicle_registration_no)) {
            $messages['vehicle_registration_no'] = 'Vehicle Registration number is required';
        }        

        if(!empty($vehicle_registration_no)) {            

            $input = [
                "verification_id" => $vehicle_registration_no."_".time(),
                "vehicle_number" => $vehicle_registration_no
            ];

            $settings_client_id = $this->setting_model->where("key", 'cashfree_client_id')->get();
            $settings_client_secreat = $this->setting_model->where("key", 'cashfree_client_secret')->get();

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.cashfree.com/verification/vehicle-rc',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($input),
            CURLOPT_HTTPHEADER => array(
                'x-client-id: ' . $settings_client_id['value'],
                'x-client-secret: ' . $settings_client_secreat['value'],
                'x-api-version: v1',
                'Content-Type: application/json'
            ),
            ));

            $json = curl_exec($curl);

            curl_close($curl);
            
            $response = json_decode($json, true);

            $this->set_response_simple($response, 'success', REST_Controller::HTTP_OK, TRUE);
        }
        else {                       
            $this->set_response_simple(NULL, $messages, REST_Controller::HTTP_FORBIDDEN, FALSE);                
        }
    }
}
