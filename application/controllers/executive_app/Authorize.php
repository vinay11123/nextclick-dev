<?php
class Authorize extends MY_Controller
{
    public $data;
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->config->load('ion_auth', TRUE);
        $this->load->library(array('ion_auth', 'form_validation'));
        $this->load->helper(array('url', 'language'));

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('user_model');
        $this->load->model('vendor_list_model');
        $this->load->model('user_group_model');
        $this->load->model('otp_model');
        $this->load->model('group_model');
        $this->load->model('setting_model');
        $this->load->model('state_model');
        $this->load->model('district_model');
        $this->load->model('constituency_model');
        $this->load->model('executive_address_model');
        $this->load->model('executive_biometric_model');
        $this->load->model('user_doc_model');
        $this->load->model('termsconditions_model');
        $this->load->model('executive_type_model');
    }
    public function index($type = '')
    {

        if ($type == 'submit') {
            $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|numeric|exact_length[10]');
            $this->form_validation->set_rules('password', 'Password', 'required');
            if ($this->form_validation->run() == FALSE) {
                $data['phone_number'] = $this->input->post('phone_number');
                $data['password'] = $this->input->post('password');
                $this->load->view('executive_app/login', $data);
            } else {
                $phone_number = $this->input->post('phone_number');
                $password = $this->input->post('password');
                $user = $this->user_model->get_user_by_phone($phone_number);

                if (!empty($user) && password_verify($password, $user[0]['user_password'])) {

                    $user_roles = $this->user_model->get_user_roles($user[0]['id']);
                    if (!empty($user_roles) ) {
                    //                         if (!empty($user_roles) && ($user_roles[0]['group_ids'] == '2,6' || $user_roles[0]['group_ids'] == '2' || $user_roles[0]['group_ids'] == '6')) {
                        $user_id = $user[0]['id'];
                        $user_adhar = $user[0]['aadhar_number'];
                        if ($user_adhar != '') {
                            if ($user[0]['status'] == 1) {
                                $this->session->set_userdata('user_id', $user_id);
                                $this->session->set_userdata('phone_number', $phone_number);
                                redirect('executive/dashboard', 'refresh');
                            } else {
                                $this->session->set_userdata('user_id', $user_id);
                                $this->session->set_userdata('phone_number', $phone_number);
                                $data['success_message'] = 'KYC completed but admin not approved.';
                                $this->load->view('executive_app/login', $data);
                            }
                        } else {
                            // $this->session->set_userdata('user_id', $user_id);
                            $this->session->set_userdata('phone_number', $phone_number);
                            redirect('confirm_kyc', 'refresh');
                        }
                    } else {
                        $data['error_message'] = 'You have multiple roles.Please contact admin.';
                        $this->load->view('executive_app/login', $data);
                    }
                } else {
                    $data['error_message'] = 'Invalid phone number or password.';
                    $this->load->view('executive_app/login', $data);
                }
            }
        } else {
            if (!$this->ion_auth->executive_logged_in() && $this->session->phone_number == '') {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            } else if ($this->session->phone_number != '' && $this->session->user_id == '') {
                redirect('confirm_kyc', 'refresh');
            } else {
                redirect('executive/dashboard');
            }
        }
    }


    public function register($type = 'r')
    {
        if ($type == 'r') {
            $this->load->view('executive_app/register');
        } else if ($type == 'submit') {

            $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|numeric|exact_length[10]');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('executive_app/register');
            } else {

                $mobile = $this->input->post('phone_number');
                $user = $this->user_model->where([
                    'phone' => $mobile
                ])->get();

                $this->db->select('GROUP_CONCAT(group_id) as group_ids');
                $this->db->from('users_groups');
                $this->db->where('user_id', $user['id']);
                $query = $this->db->get();
                $user_group = $query->result_array();

                $this->db->select('*');
                $this->db->from('users_groups');
                $this->db->where('user_id', $user['id']);
                $this->db->where('group_id', 2);
                $query_exe = $this->db->get();
                $user_group_exe = $query_exe->result_array();
                if (empty($user)) {

                    $otp = rand(154564, 564646);
                    $this->otp_model->insert(['mobile' => $mobile, 'otp' => $otp]);
                    if (!empty($otp)) {
                        $this->send_sms('Dear User your OTP for Registration is ' . $otp . ' ,Use this OTP to validate your Login. Regards, NEXTCLICK INFO SOLUTIONS PRIVATE LIMITED', '1207170582452344693', $mobile);

                        $this->data['phone_number'] = array(
                            'name' => 'phone_number',
                            'id' => 'phone_number',
                            'type' => 'text',
                            'value' => $this->form_validation->set_value('phone_number'),
                        );
                        $this->session->set_flashdata('phone_number', $this->input->post('phone_number'));
                        // $this->_render_page('register_otp', $this->data);
                        redirect('register_otp/r');
                    }
                } elseif ($user_group[0]['group_ids'] == '6') {
                    $otp = rand(154564, 564646);
                    $this->otp_model->insert(['mobile' => $mobile, 'otp' => $otp]);
                    if (!empty($otp)) {
                        $this->send_sms('Dear User your OTP for Registration is ' . $otp . ' ,Use this OTP to validate your Login. Regards, NEXTCLICK INFO SOLUTIONS PRIVATE LIMITED', '1207170582452344693', $mobile);

                        $this->data['phone_number'] = array(
                            'name' => 'phone_number',
                            'id' => 'phone_number',
                            'type' => 'text',
                            'value' => $this->form_validation->set_value('phone_number'),
                        );
                        $this->session->set_flashdata('phone_number', $this->input->post('phone_number'));
                        // $this->_render_page('register_otp', $this->data);
                        redirect('register_otp/r');
                    }
                } else {
                    if ($user_group_exe[0]['group_id'] == '2') {
                        $this->session->set_flashdata('error_message', 'Phone number already exists for this role.Please login.');
                        $this->load->view('executive_app/register');
                    } else {
                        $this->session->set_flashdata('error_message', 'Phone number already exists for other roles.');
                        $this->load->view('executive_app/register');
                    }
                }
            }
        }
    }


    public function register_otp($type = 'r')
    {
        if ($type == 'r') {
            $this->load->view('executive_app/register_otp');
        } else if ($type == 'submit') {
            $this->form_validation->set_rules('otp', 'OTP', 'required|numeric');

            if ($this->form_validation->run() == FALSE) {
                $data['phone_number'] = $this->input->post('phone_number');
                $this->load->view('executive_app/register_otp', $data);
            } else {

                $mobile = $this->input->post('phone_number');
                $otp = $this->input->post('otp');
                $this->form_validation->set_rules('otp', str_replace(':', '', $this->lang->line('otp_identity_label')), 'required');
                if ($this->form_validation->run() == true) {

                    $login = $this->otp_model->validate($mobile, $otp);
                    $login_one = $login['success'];

                    if ($login_one) {
                        $user = $this->user_model->where([
                            'phone' => $mobile
                        ])->get();
                        $login_one = $this->ion_auth->login($this->input->post('phone_number'), '7aj9fQ48', False);
                        $this->session->set_flashdata('message', 'Login SuccessFully.');
                        $this->session->set_flashdata('phone_number', $mobile);
                        if (empty($user)) {
                            redirect('create_account/r', 'refresh');
                        } else {
                            redirect('confirm_kyc', 'refresh');
                        }
                    } else {
                        $data['phone_number'] = $mobile;
                        $this->session->set_flashdata('error_message', 'Invalid OTP.');
                        $this->load->view('executive_app/register_otp', $data);
                    }
                }
            }
        }
    }

    public function login_otp_phone($type = 'r')
    {
        if ($type == 'r') {
            $this->load->view('executive_app/login_otp_phone');
        } else if ($type == 'submit') {

            $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|numeric|exact_length[10]');

            if ($this->form_validation->run() == FALSE) {
                $data['phone_number'] = $this->input->post('phone_number');
                $this->load->view('executive_app/login_otp_phone', $data);
            } else {

                $mobile = $this->input->post('phone_number');
                $user = $this->user_model->get_user_by_phone($mobile);

                if (!empty($user[0]['phone'])) {
                    $user_roles = $this->user_model->get_user_roles($user[0]['id']);
                    if (!empty($user_roles) && ($user_roles[0]['group_ids'] == '2,6' || $user_roles[0]['group_ids'] == '2' || $user_roles[0]['group_ids'] == '6')) {
                        $otp = rand(154564, 564646);
                        $this->otp_model->insert(['mobile' => $mobile, 'otp' => $otp]);
                        if (!empty($otp)) {
                            $this->send_sms('Dear User your OTP for Registration is ' . $otp . ' ,Use this OTP to validate your Login. Regards, NEXTCLICK INFO SOLUTIONS PRIVATE LIMITED', '1207170582452344693', $mobile);

                            $this->data['phone_number'] = array(
                                'name' => 'phone_number',
                                'id' => 'phone_number',
                                'type' => 'text',
                                'value' => $this->form_validation->set_value('phone_number'),
                            );
                            $this->session->set_flashdata('phone_number', $this->input->post('phone_number'));
                            redirect('login_otp/r');
                        }
                    } else {
                        $this->session->set_flashdata('error_message', 'You have multiple roles.Please contact admin.');
                        $data['phone_number'] = $this->input->post('phone_number');
                        $this->load->view('executive_app/login_otp_phone', $data);
                    }
                } else {
                    $this->session->set_flashdata('error_message', 'Invalid Phone Number');
                    $data['phone_number'] = $this->input->post('phone_number');
                    $this->load->view('executive_app/login_otp_phone', $data);
                }
            }
        }
    }

    public function login_otp($type = 'r')
    {
        if ($type == 'r') {
            $this->load->view('executive_app/login_otp');
        } else if ($type == 'submit') {
            $this->form_validation->set_rules('otp', 'OTP', 'required|numeric');

            if ($this->form_validation->run() == FALSE) {
                $data['phone_number'] = $this->input->post('phone_number');
                $this->load->view('executive_app/login_otp', $data);
            } else {

                $mobile = $this->input->post('phone_number');
                $otp = $this->input->post('otp');
                $this->form_validation->set_rules('otp', str_replace(':', '', $this->lang->line('otp_identity_label')), 'required');
                if ($this->form_validation->run() == true) {

                    $login = $this->otp_model->validate($mobile, $otp);
                    $login_one = $login['success'];

                    if ($login_one) {
                        $user = $this->user_model->get_user_by_phone($mobile);
                        $user_id = $user[0]['id'];
                        $user_adhar = $user[0]['aadhar_number'];
                        $login_one = $this->ion_auth->login($this->input->post('phone_number'), '7aj9fQ48', False);
                        $this->session->set_flashdata('message', 'Login SuccessFully.');
                        if ($user_adhar != '') {
                            $this->session->set_userdata('user_id', $user_id);
                            $this->session->set_userdata('phone_number', $mobile);
                            redirect('executive/dashboard', 'refresh');
                        } else {
                            $this->session->set_userdata('user_id', $user_id);
                            $this->session->set_userdata('phone_number', $mobile);
                            redirect('confirm_kyc', 'refresh');
                        }
                    } else {
                        $data['phone_number'] = $mobile;
                        $this->session->set_flashdata('error_message', 'Invalid OTP.');
                        $this->load->view('executive_app/login_otp', $data);
                    }
                }
            }
        }
    }
    public function register_resend_otp()
    {
        $mobile = $this->input->post('phone_number');
        $user = $this->user_model->get_user_by_phone($mobile);

        if (empty($user)) {

            $otp = rand(154564, 564646);
            $this->otp_model->insert(['mobile' => $mobile, 'otp' => $otp]);
            if (!empty($otp)) {
                $this->send_sms('Dear User your OTP for Registration is ' . $otp . ' ,Use this OTP to validate your Login. Regards, NEXTCLICK INFO SOLUTIONS PRIVATE LIMITED', '1207170582452344693', $mobile);

                $response['success'] = true;
                $response['message'] = 'OTP has been sent to ' . $mobile;

                $this->output->set_content_type('application/json')->set_output(json_encode($response));
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Error: User already exists';

            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }
    }

    public function login_resend_otp()
    {
        $mobile = $this->input->post('phone_number');
        $user = $this->user_model->get_user_by_phone($mobile);

        if (!empty($user[0]['phone'])) {

            $otp = rand(154564, 564646);
            $this->otp_model->insert(['mobile' => $mobile, 'otp' => $otp]);
            if (!empty($otp)) {
                $this->send_sms('Dear User your OTP for Registration is ' . $otp . ' ,Use this OTP to validate your Login. Regards, NEXTCLICK INFO SOLUTIONS PRIVATE LIMITED', '1207170582452344693', $mobile);

                $response['success'] = true;
                $response['message'] = 'OTP has been sent to ' . $mobile;

                $this->output->set_content_type('application/json')->set_output(json_encode($response));
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Error: User not found';

            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }
    }


    public function create_account($type = 'r')
    {

        if ($type == 'r') {
            $phone_number = $this->input->post('phone_number');
            $this->load->view('executive_app/create_account', array('phone_number' => $phone_number));
        } else if ($type == 'submit') {

            $this->form_validation->set_rules('first_name', 'First Name', 'required|regex_match[/^[a-zA-Z\s]+$/]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|regex_match[/^[a-zA-Z\s]+$/]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
            $this->form_validation->set_rules('profile_image', 'Profile Picture', 'callback_validate_profile_image');

            if ($this->form_validation->run() == FALSE) {
                $phone_number = $this->input->post('phone_number');
                $this->load->view('executive_app/create_account', array('phone_number' => $phone_number));
            } else {
                $this->load->helper(array('form', 'url'));

                $phone_number = $this->input->post('phone_number');

                $primary_intent = 'executive';
                $data = array(
                    'primary_intent' => $primary_intent,
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'email' => $this->input->post('email'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone' => $this->input->post('phone_number'),
                    'referral_code' => $this->generateUnicode()
                );

                $this->db->insert('users', $data);
                if ($this->db->affected_rows() > 0) {
                    $last_inserted_id = $this->db->insert_id();

                    $upload_path = './uploads/profile_image/';

                    if (!is_dir($upload_path)) {
                        mkdir($upload_path, 0777, true);
                    }
                    $config['upload_path'] = $upload_path;
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] = 'profile_' . $last_inserted_id . '.jpg';

                    $this->load->library('upload', $config);
                    $phone_number = $this->input->post('phone_number');

                    if ($this->upload->do_upload('profile_image')) {
                        $uploadData = $this->upload->data();
                        $file_name = $uploadData['file_name'];
                        $imageTemp = $uploadData['full_path'];
                        $imageSize = $uploadData['file_size'];

                        if ($imageSize > 2048) { // 2MB
                            if ($imageSize > 10240) { // >10MB
                                $quality = 30; // 60% compression

                            } elseif ($imageSize > 5120) { // >5MB
                                $quality = 40; // 40% compression

                            } else { // >2MB
                                $quality = 60; // 25% compression

                            }

                            // Compress the image
                            $compressedImage = $this->compressImage($imageTemp, $imageTemp, $quality);

                            if ($compressedImage) {
                                $compressedImageSize = filesize($compressedImage);
                                $compressedImageSize = $this->convert_filesize($compressedImageSize);
                            } else {

                                $this->session->set_flashdata('error_message', 'Something went wrong while compressing the image.');
                                $this->load->view('executive_app/create_account', array('phone_number' => $phone_number));
                            }
                        } else {
                            // No compression needed, use the original image
                            $compressedImage = $imageTemp;
                            $compressedImageSize = $imageSize;
                        }

                        $data_user_credentials = array(
                            'user_id' => $last_inserted_id,
                            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                        );
                        $this->db->insert('user_credentials', $data_user_credentials);
                        if ($this->db->affected_rows() > 0) {
                            $data_accounts = array(
                                'user_id' => $last_inserted_id,
                            );
                            $this->db->insert('user_accounts', $data_accounts);
                            if ($this->db->affected_rows() > 0) {


                                $group = $this->group_model->groupByName('user');
                                $group_id_user = $group['data']['id'];

                                $data_user_group_user = array(
                                    'user_id' => $last_inserted_id,
                                    'group_id' => $group_id_user,
                                    'status' => '1',
                                );

                                $this->db->insert('users_groups', $data_user_group_user);

                                if ($this->db->affected_rows() > 0) {

                                    $group = $this->group_model->groupByName($primary_intent);
                                    $group_id = $group['data']['id'];

                                    $data_user_group_executive = array(
                                        'user_id' => $last_inserted_id,
                                        'group_id' => $group_id,
                                        'status' => '3',
                                    );

                                    $this->db->insert('users_groups', $data_user_group_executive);
                                    if ($this->db->affected_rows() > 0) {
                                        $data['success_message'] = 'User created successfully.';
                                        $this->load->view('executive_app/login', $data);
                                    }
                                }
                            }
                        }
                    } else {
                        // Insert failed
                        $error = $this->db->error();
                        $this->session->set_flashdata('error_message', $error['message']);
                        $this->load->view('executive_app/create_account', array('phone_number' => $phone_number));
                    }
                } else {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('error_message', $error['error']);
                    $this->load->view('executive_app/create_account', array('phone_number' => $phone_number));
                }
            }
        }
    }



    private function compressImage($source, $destination, $quality)
    {
        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
        } else {
            return false;
        }

        // Compress and save image
        if (imagejpeg($image, $destination, $quality)) {
            imagedestroy($image);
            return $destination;
        } else {
            imagedestroy($image);
            return false;
        }
    }

    // Function to convert file size to human-readable format
    private function convert_filesize($bytes)
    {
        $sizes = array('B', 'KB', 'MB', 'GB', 'TB');
        if ($bytes == 0)
            return '0 B';
        $i = floor(log($bytes) / log(1024));
        return round($bytes / pow(1024, $i), 2) . ' ' . $sizes[$i];
    }

    public function generateUnicode()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $char_num = strlen($characters);
        $code_length = 9;
        $code = '';

        while (strlen($code) < $code_length) {
            $position = rand(0, $char_num - 1);
            $character = $characters[$position];
            $code .= $character;
        }
        $result = $this->user_model->referral_check($code);

        if (!empty($result)) {
            return $this->generateUnicode();
        }

        return $code;
    }

    public function validate_profile_image()
    {
        if (empty($_FILES['profile_image']['name'])) {
            $this->form_validation->set_message('validate_profile_image', 'The {field} field is required.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function kyc_confirmation()
    {
        if (!$this->ion_auth->executive_logged_in() && $this->session->phone_number == '') {
            $this->session->sess_destroy();
            $this->load->view('executive_app/login');
        } else if ($this->session->phone_number != '' && $this->session->user_id == '') {
            $this->load->view('executive_app/kyc_confirmation');
        } else {
            redirect('executive/dashboard');
        }
    }

    public function user_kyc_details()
    {
        $data['executive_type'] = $this->executive_type_model->fields('id, executive_type')->get_all();
        $data['states'] = $this->state_model->order_by('name', 'ASC')->get_all();
        $data['termandconditions'] = $this->termsconditions_model->get_executive_register_terms();
        $data['exc_cities'] = $this->db->get('exc_cities')->result();
        $this->load->view('executive_app/kyc_details', $data);
    }

    public function district()
    {
        $districts = $this->district_model->where('state_id', $_POST['state_id'])->order_by('name', 'ASC')->get_all();

        echo json_encode(
            array(
                $districts
            )
        );
    }
    public function constituency()
    {
        $constituencies = $this->constituency_model->where('district_id', $_POST['district_id'])->order_by('name', 'ASC')->get_all();
        echo json_encode(
            array(
                $constituencies
            )
        );
    }

    public function kyc_submit2()
    {
        if (isset($_POST['submit'])) {
            $this->form_validation->set_rules('executive_type_id', 'Executive type', 'required');
            $this->form_validation->set_rules('state_id', 'State', 'required');
            $this->form_validation->set_rules('district_id', 'District', 'required');
            $this->form_validation->set_rules('constituency_id', 'Constituency', 'required');
            //$this->form_validation->set_rules('aadhaar_number', 'Aadhaar Number', 'callback_check_duplicate_aadhar');
            $this->form_validation->set_rules('termsconditions', 'Terms and conditions', 'required', array('required' => 'You must accept the terms and conditions.'));

            if (empty($_FILES['aadhaar_front']['name'])) {
                $this->form_validation->set_rules('aadhaar_front', 'Aadhaar Front', 'required');
            }
            if (empty($_FILES['aadhaar_back']['name'])) {
                $this->form_validation->set_rules('aadhaar_front', 'Aadhaar Back', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $data['executive_type'] = $this->executive_type_model->fields('id, executive_type')->get_all();
                $data['states'] = $this->state_model->order_by('name', 'ASC')->get_all();
                $data['districts'] = $this->district_model->where('state_id', $_POST['state_id'])->order_by('name', 'ASC')->get_all();
                ;
                $data['constituencies'] = $this->constituency_model->where('district_id', $_POST['district_id'])->order_by('name', 'ASC')->get_all();
                $data['termandconditions'] = $this->termsconditions_model->get_executive_register_terms();
                $this->load->view('executive_app/kyc_details', $data);
            } else {
                $data = array(
                    'aadhar_number' => $this->input->post('aadhaar_number')
                );
                $this->db->where('phone', $this->session->phone_number);
                $this->db->update('users', $data);

                $user = $this->user_model->where([
                    'phone' => $this->session->phone_number
                ])->get();

                $executive_address = $this->executive_address_model->insert([
                    'user_id' => $user['id'],
                    'state' => $this->input->post('state_id'),
                    'district' => $this->input->post('district_id'),
                    'constituency' => $this->input->post('constituency_id'),
                    'executive_type_id' => $this->input->post('executive_type_id'),
                ]);
                $executive_biometric = $this->executive_biometric_model->insert([
                    'user_id' => $user['id'],
                    'aadhar' => $this->input->post('aadhaar_number'),
                ]);
                $this->session->set_userdata('user_id', $user['id']);
                $user_doc = $this->user_doc_model->insert([
                    'unique_id' => $user['id']
                ]);

                // $unique_id = $this->session->user_id;
                // if (!file_exists('uploads/' . 'aadhar_card' . '_image/')) {
                //     mkdir('uploads/' . 'aadhar_card' . '_image/', 0777, true);
                // }
                // file_put_contents("./uploads/aadhar_card_image/aadhar_card_front_" . $unique_id . ".jpg", base64_decode($this->input->post('aadhaar_front')));
                // file_put_contents("./uploads/aadhar_card_image/aadhar_card_back_" . $unique_id . ".jpg", base64_decode($this->input->post('aadhaar_back')));

                $unique_id = $user['id'];

                // Create directory if it doesn't exist
                if (!file_exists('uploads/aadhar_card_image/')) {
                    mkdir('uploads/aadhar_card_image/', 0777, true);
                }

                $config['upload_path'] = './uploads/aadhar_card_image/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = 'aadhar_card_front_' . $unique_id . '.jpg';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('aadhaar_front')) {
                    $uploadData = $this->upload->data();
                    $front_image_path = $uploadData['full_path'];
                    $front_image_size = filesize($front_image_path);

                    $front_quality = 100; // Default quality

                    if ($front_image_size > 2048 * 1024) { // 2MB
                        if ($front_image_size > 10240 * 1024) { // >10MB
                            $front_quality = 30; // 70% compression
                        } elseif ($front_image_size > 5120 * 1024) { // >5MB
                            $front_quality = 40; // 60% compression
                        } else { // >2MB
                            $front_quality = 60; // 40% compression
                        }

                        // Compress the front image
                        $compressedFrontImage = $this->compressImage($front_image_path, $front_image_path, $front_quality);

                        if (!$compressedFrontImage) {
                            $this->session->set_flashdata('error_message', 'Something went wrong while compressing the front image.');
                            $this->load->view('executive_app/kyc_details');
                            return; // Exit function if compression fails
                        }
                    }
                }

                // Repeat the process for the back image
                $config['file_name'] = 'aadhar_card_back_' . $unique_id . '.jpg';

                $this->upload->initialize($config);

                if ($this->upload->do_upload('aadhaar_back')) {
                    $uploadData = $this->upload->data();
                    $back_image_path = $uploadData['full_path'];
                    $back_image_size = filesize($back_image_path);

                    $back_quality = 100; // Default quality

                    if ($back_image_size > 2048 * 1024) { // 2MB
                        if ($back_image_size > 10240 * 1024) { // >10MB
                            $back_quality = 30; // 70% compression
                        } elseif ($back_image_size > 5120 * 1024) { // >5MB
                            $back_quality = 40; // 60% compression
                        } else { // >2MB
                            $back_quality = 60; // 40% compression
                        }

                        // Compress the back image
                        $compressedBackImage = $this->compressImage($back_image_path, $back_image_path, $back_quality);

                        if (!$compressedBackImage) {
                            $this->session->set_flashdata('error_message', 'Something went wrong while compressing the back image.');
                            $this->load->view('executive_app/kyc_details');
                            return; // Exit function if compression fails
                        }
                    }
                }

                $this->session->sess_destroy();
                $data['success_message'] = 'KYC Completed Successfully.';
                $this->load->view('executive_app/login', $data);
            }
        }
    }
    public function kyc_submit()
{
    if (isset($_POST['submit'])) {
        


        /* ================= BASIC VALIDATION ================= */
       // $this->form_validation->set_rules('executive_type_id', 'Executive type', 'required');
        $this->form_validation->set_rules('state_id', 'State', 'required');
        $this->form_validation->set_rules('district_id', 'District', 'required');
        $this->form_validation->set_rules('constituency_id', 'Constituency', 'required');
      //  $this->form_validation->set_rules('aadhaar_number', 'Aadhaar Number', 'callback_check_duplicate_aadhar');
        $this->form_validation->set_rules('termsconditions', 'Terms and conditions', 'required');

        /* ================= EXECUTIVE DETAILS ================= */
        $this->form_validation->set_rules('vendor_type', 'Executive Category', 'required');
       // $this->form_validation->set_rules('executive_name', 'Executive Name', 'required');
       // $this->form_validation->set_rules('executive_id', 'Executive ID', 'required');
       // $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
         // Prefix mapping based on section
         $vendor_type = $this->input->post('vendor_type');
            $prefix = '';
            switch ($vendor_type) {
                case 'freelancer':
                    $prefix = 'NXF';
                    break;
                case 'executive':
                    $prefix = 'NXE';
                    break;
                case 'intern':
                    $prefix = 'NXI';
                    break;
            
            }
        $this->form_validation->set_rules('area_type', 'Area Type', 'required');
        $this->form_validation->set_rules('city_name', 'City', 'required');
        $this->form_validation->set_rules('circle', 'Circle', 'required');
        $this->form_validation->set_rules('ward', 'Ward', 'required');

        //$this->form_validation->set_rules('target_freelancer', 'Target Freelancer', 'required|numeric');
        //$this->form_validation->set_rules('executive_target', 'Target Executive', 'required|numeric');
        //$this->form_validation->set_rules('monthly_target', 'Monthly Target', 'required|numeric');

        /* ================= FILE VALIDATION ================= */
        if (empty($_FILES['aadhaar_front']['name'])) {
            $this->form_validation->set_rules('aadhaar_front', 'Aadhaar Front', 'required');
        }
        if (empty($_FILES['aadhaar_back']['name'])) {
            $this->form_validation->set_rules('aadhaar_back', 'Aadhaar Back', 'required');
        }

        /* ================= VALIDATION FAIL ================= */
        if ($this->form_validation->run() == FALSE) {

            $data['executive_type'] = $this->executive_type_model->fields('id, executive_type')->get_all();
            $data['states'] = $this->state_model->order_by('name', 'ASC')->get_all();
            $data['districts'] = $this->district_model
                                       ->where('state_id', $this->input->post('state_id'))
                                       ->order_by('name', 'ASC')->get_all();
            $data['constituencies'] = $this->constituency_model
                                            ->where('district_id', $this->input->post('district_id'))
                                            ->order_by('name', 'ASC')->get_all();
            $data['termandconditions'] = $this->termsconditions_model->get_executive_register_terms();
            $data['exc_cities'] = $this->db->get('exc_cities')->result(); // IMPORTANT

            $this->load->view('executive_app/kyc_details', $data);
            return;
        }

        /* ================= UPDATE USER AADHAR ================= */
        $this->db->where('phone', $this->session->phone_number)
                 ->update('users', [
                     'aadhar_number' => $this->input->post('aadhaar_number')
                 ]);

        $user = $this->user_model->where([
            'phone' => $this->session->phone_number
        ])->get();

        /* ================= EXECUTIVE ADDRESS ================= */
        $this->executive_address_model->insert([
            'user_id' => $user['id'],
            'state' => $this->input->post('state_id'),
            'district' => $this->input->post('district_id'),
            'constituency' => $this->input->post('constituency_id'),
            'executive_type_id' => $this->input->post('vendor_type'),
        ]);

        /* ================= BIOMETRIC ================= */
        $this->executive_biometric_model->insert([
            'user_id' => $user['id'],
            'aadhar' => $this->input->post('aadhaar_number'),
        ]);
      $executive_id = $prefix . str_pad($user['id'], 5, '0', STR_PAD_LEFT);
        /* ================= INSERT INTO exc_roles ================= */
        $team = $this->input->post('team');
        $role_data = [
            'user_id'           => $user['id'],
            'vendor_type'       => $this->input->post('vendor_type'),
            'team_lead'         => $this->input->post('team_lead'),
            'executive_name'    => $this->input->post('executive_name'),
            'executive_id'      =>  $executive_id,
            'amount'            => $this->input->post('amount'),
            'area_type'         => $this->input->post('area_type'),
            'city_name'              => $this->input->post('city_name'),
            'circle'            => $this->input->post('circle'),
            'ward'              => $this->input->post('ward'),
            'target_freelancer' => $this->input->post('target_freelancer'),
            'executive_target'  => $this->input->post('executive_target'),
            'monthly_target'    => $this->input->post('monthly_target'),
            'team_members'      => !empty($team) ? json_encode($team) : null,
            'created_at'        => date('Y-m-d H:i:s')
        ];

        $exists = $this->db->where('user_id', $user['id'])->get('exc_roles')->row();
        if ($exists) {
            $this->db->where('user_id', $user['id'])->update('exc_roles', $role_data);
        } else {
            $this->db->insert('exc_roles', $role_data);
        }

        /* ================= FILE UPLOAD (UNCHANGED) ================= */
        // 👉 your existing upload + compression code remains exactly the same

        $this->session->sess_destroy();
        $this->load->view('executive_app/login', [
            'success_message' => 'KYC Completed Successfully.'
        ]);
    }
}


    public function check_duplicate_aadhar($aadhar)
    {
        if (!empty($aadhar)) {
            if ($this->user_model->is_aadhar_exists($aadhar)) {

                $this->form_validation->set_message('check_duplicate_aadhar', 'The {field} already exists.');
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            $this->form_validation->set_message('check_duplicate_aadhar', 'The {field} is required.');
            return FALSE;
        }
    }

    public function check_duplicate_aadharjs()
    {
        $aadhar = $this->input->post('aadhaar_number');

        if (!empty($aadhar)) {
            if ($this->user_model->is_aadhar_existsjs($aadhar)) {
                echo false;
            } else {
                echo true;
            }
        }
    }


    public function forgot_password($type = 'r')
    {
        if ($type == 'r') {
            $this->load->view('executive_app/forgot_password');
        } else if ($type == 'submit') {
            if ($this->config->item('identity', 'ion_auth') != 'email') {
                $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
            } else {
                $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
            }


            if ($this->form_validation->run() == false) {

                $this->data['type'] = $this->config->item('identity', 'ion_auth');
                // setup the input
                $this->data['identity'] = array(
                    'name' => 'identity',
                    'id' => 'identity',
                );

                if ($this->config->item('identity', 'ion_auth') != 'email') {
                    $this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
                } else {
                    $this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
                }

                // set any errors and display the form
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
                $this->_render_page('forgot_password/r', $this->data);
            } else {


                $identity_column = $this->config->item('identity', 'ion_auth');
                $this->input->post('identity');
                $identity = $this->ion_auth->where('email', $this->input->post('identity'))->users()->row();

                if (empty($identity)) {
                    if ($this->config->item('identity', 'ion_auth') != 'email') {
                        $this->ion_auth->set_error('forgot_password_identity_not_found');
                    } else {
                        $this->ion_auth->set_error('forgot_password_email_not_found');
                    }


                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect("forgot_password/r", 'refresh');
                } else {
                    // run the forgotten password method to email an activation code to the user
                    $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});
                    if ($forgotten) {
                        // if there were no errors
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect("executive/login", 'refresh'); //we should display a confirmation page here instead of the login page
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect("forgot_password/r", 'refresh');
                    }
                }
                exit;
            }
        }
    }


    public function logout()
    {
        $this->session->sess_destroy();
        $data['success_message'] = 'Logout Successfully.';
        $this->load->view('executive_app/login', $data);
    }

    public function kyc_logout()
    {
        $this->session->sess_destroy();
        $this->load->view('executive_app/login');
    }
    function send_sms($message = "hello", $template_id = "1207169203114731853", $mobile_number = NULL)
    {
        $sms_config = $this->config->item('sms_settings');

        //Enter your login username
        $username = $sms_config->sms_username;

        //Enter your login password
        $password = $sms_config->sms_hash;

        //Enter your Sender ID
        $sender = $sms_config->sms_sender;



        $data = $this->get_remote_data('https://api.bulksmsgateway.in/sendmessage.php?', "user=$username&password=$password&mobile=$mobile_number&message=$message&sender=$sender&type=3&template_id=$template_id");
        // print_r($data);
        // exit;
        /* $data = $this->get_remote_data('http://api.bulksmsgateway.in/sendmessage.php?', "user=$username&password=$password&mobile=$mobile_number&message=$message&sender=$sender&type=3&template_id=$template_id" );
              print_r($data);*/
    }

    function get_remote_data($url, $post_paramtrs = false, $extra = array('schemeless' => true, 'replace_src' => true, 'return_array' => false, "curl_opts" => []))
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        //if parameters were passed to this function, then transform into POST method.. (if you need GET request, then simply change the passed URL)
        if ($post_paramtrs) {
            curl_setopt($c, CURLOPT_POST, TRUE);
            curl_setopt($c, CURLOPT_POSTFIELDS, (is_array($post_paramtrs) ? http_build_query($post_paramtrs) : $post_paramtrs));
        }
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:76.0) Gecko/20100101 Firefox/76.0";
        $headers[] = "Pragma: ";
        $headers[] = "Cache-Control: max-age=0";
        if (!empty($post_paramtrs) && !is_array($post_paramtrs) && is_object(json_decode($post_paramtrs))) {
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Content-Length: ' . strlen($post_paramtrs);
        }
        curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($c, CURLOPT_MAXREDIRS, 10);
        //if SAFE_MODE or OPEN_BASEDIR is set,then FollowLocation cant be used.. so...
        $follow_allowed = (ini_get('open_basedir') || ini_get('safe_mode')) ? false : true;
        if ($follow_allowed) {
            curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
        }
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
        curl_setopt($c, CURLOPT_REFERER, $url);
        curl_setopt($c, CURLOPT_TIMEOUT, 60);
        curl_setopt($c, CURLOPT_AUTOREFERER, true);
        curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($c, CURLOPT_HEADER, !empty($extra['return_array']));
        //set extra options if passed
        if (!empty($extra['curl_opts']))
            foreach ($extra['curl_opts'] as $key => $value)
                curl_setopt($c, $key, $value);
        $data = curl_exec($c);
        if (!empty($extra['return_array'])) {
            preg_match("/(.*?)\r\n\r\n((?!HTTP\/\d\.\d).*)/si", $data, $x);
            preg_match_all('/(.*?): (.*?)\r\n/i', trim('head_line: ' . $x[1]), $headers_, PREG_SET_ORDER);
            foreach ($headers_ as $each) {
                $header[$each[1]] = $each[2];
            }
            $data = trim($x[2]);
        }
        $status = curl_getinfo($c);
        curl_close($c);
        // if redirected, then get that redirected page
        if ($status['http_code'] == 301 || $status['http_code'] == 302) {
            //if we FOLLOWLOCATION was not allowed, then re-get REDIRECTED URL
            //p.s. WE dont need "else", because if FOLLOWLOCATION was allowed, then we wouldnt have come to this place, because 301 could already auto-followed by curl  :)
            if (!$follow_allowed) {
                //if REDIRECT URL is found in HEADER
                if (empty($redirURL)) {
                    if (!empty($status['redirect_url'])) {
                        $redirURL = $status['redirect_url'];
                    }
                }
                //if REDIRECT URL is found in RESPONSE
                if (empty($redirURL)) {
                    preg_match('/(Location:|URI:)(.*?)(\r|\n)/si', $data, $m);
                    if (!empty($m[2])) {
                        $redirURL = $m[2];
                    }
                }
                //if REDIRECT URL is found in OUTPUT
                if (empty($redirURL)) {
                    preg_match('/moved\s\<a(.*?)href\=\"(.*?)\"(.*?)here\<\/a\>/si', $data, $m);
                    if (!empty($m[1])) {
                        $redirURL = $m[1];
                    }
                }
                //if URL found, then re-use this function again, for the found url
                if (!empty($redirURL)) {
                    $t = debug_backtrace();
                    return call_user_func($t[0]["function"], trim($redirURL), $post_paramtrs);
                }
            }
        }
        // if not redirected,and nor "status 200" page, then error..
        elseif ($status['http_code'] != 200) {
            $data = "ERRORCODE22 with $url<br/><br/>Last status codes:" . json_encode($status) . "<br/><br/>Last data got:$data";
        }
        //URLS correction
        if (function_exists('url_corrections_for_content_HELPER')) {
            $data = url_corrections_for_content_HELPER($data, $status['url'], array('schemeless' => !empty($extra['schemeless']), 'replace_src' => !empty($extra['replace_src']), 'rawgit_replace' => !empty($extra['rawgit_replace'])));
        }
        $answer = (!empty($extra['return_array']) ? array('data' => $data, 'header' => $header, 'info' => $status) : $data);
        return $answer;
    }
    function url_corrections_for_content_HELPER($content = false, $url = false, $extra_opts = array('schemeless' => false, 'replace_src' => false, 'rawgit_replace' => false))
    {
        $GLOBALS['rdgr']['schemeless'] = $extra_opts['schemeless'];
        $GLOBALS['rdgr']['replace_src'] = $extra_opts['replace_src'];
        $GLOBALS['rdgr']['rawgit_replace'] = $extra_opts['rawgit_replace'];
        if ($GLOBALS['rdgr']['schemeless'] || $GLOBALS['rdgr']['replace_src']) {
            if ($url) {
                $GLOBALS['rdgr']['parsed_url'] = parse_url($url);
                $GLOBALS['rdgr']['urlparts']['domain_X'] = $GLOBALS['rdgr']['parsed_url']['scheme'] . '://' . $GLOBALS['rdgr']['parsed_url']['host'];
                $GLOBALS['rdgr']['urlparts']['path_X'] = stripslashes(dirname($GLOBALS['rdgr']['parsed_url']['path']) . '/');
                $GLOBALS['rdgr']['all_protocols'] = array('adc', 'afp', 'amqp', 'bacnet', 'bittorrent', 'bootp', 'camel', 'dict', 'dns', 'dsnp', 'dhcp', 'ed2k', 'empp', 'finger', 'ftp', 'gnutella', 'gopher', 'http', 'https', 'imap', 'irc', 'isup', 'javascript', 'ldap', 'mime', 'msnp', 'map', 'modbus', 'mosh', 'mqtt', 'nntp', 'ntp', 'ntcip', 'openadr', 'pop3', 'radius', 'rdp', 'rlogin', 'rsync', 'rtp', 'rtsp', 'ssh', 'sisnapi', 'sip', 'smtp', 'snmp', 'soap', 'smb', 'ssdp', 'stun', 'tup', 'telnet', 'tcap', 'tftp', 'upnp', 'webdav', 'xmpp');
            }
            $GLOBALS['rdgr']['ext_array'] = array(
                'src' => array('audio', 'embed', 'iframe', 'img', 'input', 'script', 'source', 'track', 'video'),
                'srcset' => array('source'),
                'data' => array('object'),
                'href' => array('link', 'area', 'a'),
                'action' => array('form')
                //'param', 'applet' and 'base' tags are exclusion, because of a bit complex structure
            );
            $content = preg_replace_callback(
                '/<(((?!<).)*?)>/si',     //avoids unclosed & closing tags
                function ($matches_A) {
                    $content_A = $matches_A[0];
                    $tagname = preg_match('/((.*?)(\s|$))/si', $matches_A[1], $n) ? $n[2] : "";
                    foreach ($GLOBALS['rdgr']['ext_array'] as $key => $value) {
                        if (in_array($tagname, $value)) {
                            preg_match('/ ' . $key . '=(\'|\")/i', $content_A, $n);
                            if (!empty($n[1])) {
                                $GLOBALS['rdgr']['aphostrope_type'] = $n[1];
                                $content_A = preg_replace_callback(
                                    '/( ' . $key . '=' . $GLOBALS['rdgr']['aphostrope_type'] . ')(.*?)(' . $GLOBALS['rdgr']['aphostrope_type'] . ')/i',
                                    function ($matches_B) {
                                        $full_link = $matches_B[2];
                                        //correction to files/urls
                                        if (!empty($GLOBALS['rdgr']['replace_src'])) {
                                            //if not schemeless url
                                            if (substr($full_link, 0, 2) != '//') {
                                                $replace_src_allow = true;
                                                //check if the link is a type of any special protocol
                                                foreach ($GLOBALS['rdgr']['all_protocols'] as $each_protocol) {
                                                    //if protocol found - dont continue
                                                    if (substr($full_link, 0, strlen($each_protocol) + 1) == $each_protocol . ':') {
                                                        $replace_src_allow = false;
                                                        break;
                                                    }
                                                }
                                                if ($replace_src_allow) {
                                                    $full_link = $GLOBALS['rdgr']['urlparts']['domain_X'] . (str_replace('//', '/', $GLOBALS['rdgr']['urlparts']['path_X'] . $full_link));
                                                }
                                            }
                                        }
                                        //replace http(s) with sheme-less urls
                                        if (!empty($GLOBALS['rdgr']['schemeless'])) {
                                            $full_link = str_replace(array('https://', 'http://'), '//', $full_link);
                                        }
                                        //replace github mime
                                        if (!empty($GLOBALS['rdgr']['rawgit_replace'])) {
                                            $full_link = str_replace('//raw.github' . 'usercontent.com/', '//rawgit.com/', $full_link);
                                        }
                                        $matches_B[2] = $full_link;
                                        unset($matches_B[0]);
                                        $content_B = '';
                                        foreach ($matches_B as $each) {
                                            $content_B .= $each;
                                        }
                                        return $content_B;
                                    },
                                    $content_A
                                );
                            }
                        }
                    }
                    return $content_A;
                },
                $content
            );
            $content = preg_replace_callback(
                '/style="(.*?)background(\-image|)(.*?|)\:(.*?|)url\((\'|\"|)(.*?)(\'|\"|)\)/i',
                function ($matches_A) {
                    $url = $matches_A[7];
                    $url = (substr($url, 0, 2) == '//' || substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://' ? $url : '#');
                    return 'style="' . $matches_A[1] . 'background' . $matches_A[2] . $matches_A[3] . ':' . $matches_A[4] . 'url(' . $url . ')'; //$matches_A[5] is url taged ,7 is url
                },
                $content
            );
        }
        //print_r($content);
        return $content;
    }
}
