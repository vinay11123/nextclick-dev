<?php
error_reporting(E_ERROR | E_PARSE);
class ExecutiveLogin extends MY_Controller
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
        $this->load->model('vendor_bank_details_model');
        $this->load->model('otp_model');
        $this->load->model('group_model');
        $this->load->model('setting_model');
        $this->load->model('state_model');
        $this->load->model('district_model');
        $this->load->model('constituency_model');
        $this->load->model('executive_address_model');
        $this->load->model('executive_biometric_model');
        $this->load->model('user_doc_model');
        $this->load->model('executive_model');
        $this->load->model('termsconditions_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('user_account_model');


        if ($this->session->userdata('user_id')) {
            if ($this->uri->segment(1) === 'executive' && $this->uri->segment(2) === 'login') {
                $this->session->set_userdata('custom-error', 'User already logged in!');
                redirect('executive/dashboard');
            }
        }
    }

    public function dashboard11()
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            if ($login_user['status'] == 1) {
                $executive_id = $this->session->user_id;
                if (!empty($executive_id)) {

                    $this->data['executive_address'] = $this->executive_address_model->where([
                        'user_id' => $executive_id
                    ])->get();

                    $queryResult = $this->user_model->get_users_count($executive_id);

                    $this->data['users'] = $queryResult['count'];

                    $vendorResult = $this->vendor_list_model->get_vendor_count($executive_id);

                    $this->data['vendor'] = $vendorResult['count'];

                    $deliveryCaptainResult = $this->executive_model->get_executive_delivery_captain_list('', $executive_id);

                    $this->data['deliveryCaptain'] = $deliveryCaptainResult['count'];
                  $deliveryWalletResult = $this->executive_model->get_executive_wallet_amount($executive_id);

                    $this->data['walletAmount'] = $deliveryWalletResult[0]->total_amount;
                   $this->data['referral_code'] = $login_user['referral_code'];
                   /* ✅ DIRECT DATA FROM exc_roles TABLE */
    $this->data['exc_roles'] = $this->db
        ->where('user_id', $executive_id)   // column name
        ->get('exc_roles')                       // table name
        ->row_array();                           // use result_array() if multiple rows
        
            $this->load->view('executive_app/dashboard', $this->data);
                }
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }
    public function dashboard()
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
            return;
        }
    
        $executive_id = $this->session->user_id;
    
        // Get executive details
        $login_user = $this->user_model
            ->where(['id' => $executive_id])
            ->get();
    
        if (empty($login_user) || $login_user['status'] != 1) {
            $this->session->sess_destroy();
            $this->load->view('executive_app/login');
            return;
        }
    
        /* ==============================
           BASIC EXECUTIVE DETAILS
        ================================ */
    
        $this->data['executive']      = $login_user;
        $this->data['referral_code']  = $login_user['referral_code'];
    
        /* ==============================
           EXECUTIVE ADDRESS
        ================================ */
    
        $this->data['executive_address'] = $this->executive_address_model
            ->where(['user_id' => $executive_id])
            ->get();
    
        /* ==============================
           COUNTS
        ================================ */
    
        // Users count
        $usersResult = $this->user_model->get_users_count($executive_id);
        //echo $this->db->last_query(); exit;
        $this->data['users_count'] = $usersResult['count'] ?? 0;
        
        //echo $this->data['users_count']; exit;
    
        // Vendors count
        $vendorResult = $this->vendor_list_model->get_vendor_count($executive_id);
        $this->data['vendor_count'] = $vendorResult['count'] ?? 0;
    
        // Delivery captains count
        $deliveryCaptainResult = $this->executive_model
            ->get_executive_delivery_captain_list('', $executive_id);
    
        $this->data['deliveryCaptain'] = $deliveryCaptainResult['count'] ?? 0;
    
        /* ==============================
           EXECUTIVE WALLET
        ================================ */
    
        $deliveryWalletResult = $this->executive_model
            ->get_executive_wallet_amount($executive_id);
    
        $this->data['walletAmount'] = $deliveryWalletResult[0]->total_amount ?? 0;
    
        $this->data['executive_wallet'] = [
            'wallet'          => $login_user['wallet'],
            'floating_wallet' => $login_user['floating_wallet']
        ];
    
        /* ==============================
           REFERRAL AMOUNTS (FROM USER TABLE ✅)    referral_code
        ================================ */
    
        $this->data['vendor_touser_referral_amount'] =
            $login_user['vendor_touser_referral_amount'] ?? 0;
    
        $this->data['executive_referral_amount'] =
            $login_user['executive_referral_amount'] ?? 0;
            



     
        /* ==============================
           REFERRAL COUNTS
        ================================ */
    
        // Referred users
        $this->data['referred_users_count'] = $this->db
            ->where('executive_user_id', $executive_id)
            ->where('primary_intent', 'users')
            ->where('deleted_at IS NULL', null, false)
            ->count_all_results('users');
    
        // Referred vendors
        $this->data['referred_vendors_count'] = $this->db
            ->where('executive_user_id', $executive_id)
            ->where('primary_intent', 'vendor')
            ->where('deleted_at IS NULL', null, false)
            ->count_all_results('users');
    
        /* ==============================
           EXECUTIVE ROLES
        ================================ */
    
        $this->data['exc_roles'] = $this->db
            ->where('user_id', $executive_id)
            ->get('exc_roles')
            ->row_array();
            
$this->data['users'] = $this->db
        ->where('executive_user_id', $executive_id)
        ->where('primary_intent', 'user')
        ->where('active', 1)
        ->get('users')
        ->row_array();

/* ==============================
   EXECUTIVE VENDORS LIST
================================ */

$this->data['vendors'] = $this->db
    ->select('*')
    ->from('users')
    ->where('id', $executive_id)
    ->where('primary_intent', 'vendor')
    ->where('deleted_at IS NULL', null, false)
    ->get()
    ->row_array();
    
        /* ==============================
           LOAD VIEW
        ================================ */
    
        $this->load->view('executive_app/dashboard', $this->data);
    }
    


    public function vendors()
    {
       
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            if ($login_user['status'] == 1) {
                $approvedResult = $this->vendor_list_model->get_vendor_count($this->session->user_id, 'approved');

                $this->data['vendor_approved'] = $approvedResult['count'];

                $pendingResult = $this->vendor_list_model->get_vendor_count($this->session->user_id, 'pending');

                $this->data['vendor_pending'] = $pendingResult['count'];

                $this->load->view('executive_app/vendors', $this->data);
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }
public function myarchive()
{
    if (!$this->ion_auth->executive_logged_in()) {
        redirect('executive/login');
    }

    $user_id = $this->session->user_id;

    // Fetch full archive row (NO vendor_type dependency)
    $archive = $this->db
        ->select('vendor_type, executive_id, monthly_target, executive_target, target_freelancer')
        ->where('user_id', $user_id)
        ->get('exc_roles')
        ->row_array();

    if (!$archive) {
        $this->data['archive'] = [];
        $this->load->view('executive_app/myarchive', $this->data);
        return;
    }

    $this->data['archive'] = $archive;
    $this->data['archive_vendor_type'] = $archive['vendor_type'];


    $this->load->view('executive_app/myarchive', $this->data);
}




    public function approved_vendors_list()
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            if ($login_user['status'] == 1) {
                $executive_id = $this->session->user_id;

                if (!empty($executive_id)) {

                    $subscribed_vendors = $this->vendor_list_model->get_vendor_count($executive_id, 'subscribed');
                    $this->data['subscribed_vendor_count'] = $subscribed_vendors['count'];
                    $this->data['subscribed_vendor_list'] = $subscribed_vendors['vendor_details'];

                    $unsubscribed_vendors = $this->vendor_list_model->get_vendor_count($executive_id, 'unsubscribed');
                    $this->data['unsubscribed_vendor_count'] = $unsubscribed_vendors['count'];
                    $this->data['unsubscribed_vendor_list'] = $unsubscribed_vendors['vendor_details'];

                    $approvedResult = $this->vendor_list_model->get_vendor_count($executive_id, 'approved');

                    $this->data['vendor_approved'] = $approvedResult['count'];
                    $this->data['vendor_approved_list'] = $approvedResult['vendor_details'];

                    $this->load->view('executive_app/approved_vendors_list', $this->data);
                }
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }

    public function pending_vendors_list()
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            if ($login_user['status'] == 1) {
                $pendingResult = $this->vendor_list_model->get_vendor_count($this->session->user_id, 'pending');

                $this->data['vendor_pending'] = $pendingResult['count'];
                $this->data['vendor_pending_list'] = $pendingResult['vendor_details'];

                $this->load->view('executive_app/pending_vendors_list', $this->data);
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }

    public function delivery_boys()
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            if ($login_user['status'] == 1) {
                $executive_id = $this->session->user_id;
                if (!empty($executive_id)) {

                    $approved_captains = $this->executive_model->get_executive_delivery_captain_list('approved', $executive_id);
                    $this->data['approved_captain_count'] = $approved_captains['count'];

                    $pending_captains = $this->executive_model->get_executive_delivery_captain_list('pending', $executive_id);
                    $this->data['pending_captain_count'] = $pending_captains['count'];

                    $this->load->view('executive_app/delivery_boys', $this->data);
                }
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }

    public function approved_delivery_boys()
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            if ($login_user['status'] == 1) {
                $executive_id = $this->session->user_id;
                if (!empty($executive_id)) {

                    $approved_captains = $this->executive_model->get_executive_delivery_captain_list('approved', $executive_id);
                    $this->data['approved_captain_count'] = $approved_captains['count'];

                    $target_achieved_captains = $this->executive_model->get_executive_delivery_captain_list('target_achieved', $executive_id);
                    $this->data['target_achieved_captain_count'] = $target_achieved_captains['count'];
                    $this->data['target_achieved_captains_list'] = $target_achieved_captains['captain_details'];

                    $target_not_achieved_captains = $this->executive_model->get_executive_delivery_captain_list('target_not_achieved', $executive_id);
                    $this->data['target_not_achieved_captain_count'] = $target_not_achieved_captains['count'];
                    $this->data['target_not_achieved_captains_list'] = $target_not_achieved_captains['captain_details'];

                    $this->load->view('executive_app/approved_delivery_boys', $this->data);
                }
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }

    public function pending_delivery_boys()
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            if ($login_user['status'] == 1) {
                $executive_id = $this->session->user_id;
                if (!empty($executive_id)) {

                    $pending_captains = $this->executive_model->get_executive_delivery_captain_list('pending', $executive_id);
                    $this->data['pending_captain_count'] = $pending_captains['count'];
                    $this->data['pending_captains_list'] = $pending_captains['captain_details'];
                    $this->load->view('executive_app/pending_delivery_boys', $this->data);
                }
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }

    public function users()
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            if ($login_user['status'] == 1) {
                $executive_id = $this->session->user_id;
                if ($executive_id != '') {
                    $ordered_users = $this->user_model->get_executive_user_list('ordered', $executive_id);
                    $this->data['ordered_user_count'] = $ordered_users['count'];
                    $this->data['ordered_user_list'] = $ordered_users['user_details'];

                    $not_ordered_users = $this->user_model->get_executive_user_list('not_ordered', $executive_id);
                    $this->data['not_ordered_user_count'] = $not_ordered_users['count'];
                    $this->data['not_ordered_user_list'] = $not_ordered_users['user_details'];

                    $queryResult = $this->user_model->get_users_count($this->session->user_id);
                    $this->data['users'] = $queryResult['count'];

                    $this->load->view('executive_app/users', $this->data);
                }
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }

    public function wallet()
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            $executive_id = $this->session->user_id;
            if ($login_user['status'] == 1) {
                $executive_address = $this->executive_address_model->where([
                    'user_id' => $executive_id
                ])->get();
                if ($executive_address['executive_type_id'] == 1) {
                    $wallet_details = $this->executive_model->get_wallet_details($executive_id);

                    if ($wallet_details) {
                        $this->data['total_vendor_amount'] = isset($wallet_details['total_vendor_amount']) ? $wallet_details['total_vendor_amount'] : 0;
                        $this->data['total_user_amount'] = isset($wallet_details['total_user_amount']) ? $wallet_details['total_user_amount'] : 0;
                        $this->data['total_delivery_boy_amount'] = isset($wallet_details['total_delivery_boy_amount']) ? $wallet_details['total_delivery_boy_amount'] : 0;
                        $this->data['total_all_amount'] = isset($wallet_details['total_all_amount']) ? $wallet_details['total_all_amount'] : 0;
                    } else {
                        $this->data['total_vendor_amount'] = 0;
                        $this->data['total_user_amount'] = 0;
                        $this->data['total_delivery_boy_amount'] = 0;
                        $this->data['total_all_amount'] = 0;
                    }

                    $this->load->view('executive_app/wallet', $this->data);
                } else {
                    redirect('executive/dashboard');
                }
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }

    public function withdraw_amount()
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            if ($login_user['status'] == 1) {
                $executive_id = $this->session->user_id;

                $executive_address = $this->executive_address_model->where([
                    'user_id' => $executive_id
                ])->get();
                if ($executive_address['executive_type_id'] == 1) {

                    $wallet_details = $this->executive_model->get_wallet_details($executive_id);

                    if ($wallet_details) {
                        $this->data['total_vendor_amount'] = isset($wallet_details['total_vendor_amount']) ? $wallet_details['total_vendor_amount'] : 0;
                        $this->data['total_user_amount'] = isset($wallet_details['total_user_amount']) ? $wallet_details['total_user_amount'] : 0;
                        $this->data['total_delivery_boy_amount'] = isset($wallet_details['total_delivery_boy_amount']) ? $wallet_details['total_delivery_boy_amount'] : 0;
                        $this->data['total_all_amount'] = isset($wallet_details['total_all_amount']) ? $wallet_details['total_all_amount'] : 0;
                    } else {
                        $this->data['total_vendor_amount'] = 0;
                        $this->data['total_user_amount'] = 0;
                        $this->data['total_delivery_boy_amount'] = 0;
                        $this->data['total_all_amount'] = 0;
                    }
                    $this->load->view('executive_app/withdraw_amount', $this->data);
                } else {
                    redirect('executive/dashboard');
                }
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }

    public function transactions($type = '')
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else if ($type == 'submit') {
            $this->form_validation->set_rules('to_date', 'To Date', 'callback_check_date');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('executive_app/transactions');
            } else {
                $executive_id = $this->session->user_id;

                $role = $this->input->post('role');
                $type = $this->input->post('type');
                $from_date = $this->input->post('from_date');
                $to_date = $this->input->post('to_date');
                $this->data['transaction_details'] = $this->executive_model->get_transaction_details($executive_id, $role, $type, $from_date, $to_date);
                $this->load->view('executive_app/transactions', $this->data);
            }
        } else if ($type == 'r') {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            if ($login_user['status'] == 1) {
                $executive_id = $this->session->user_id;

                $executive_address = $this->executive_address_model->where([
                    'user_id' => $executive_id
                ])->get();
                if ($executive_address['executive_type_id'] == 1) {

                    $this->data['transaction_details'] = $this->executive_model->get_transaction_details($executive_id);
                    $this->load->view('executive_app/transactions', $this->data);
                } else {
                    redirect('executive/dashboard');
                }
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }


    public function check_date($to_date)
    {
        if (!empty($to_date)) {
            $from_date = $this->input->post('from_date');

            if (!empty($to_date) && empty($from_date)) {
                $this->form_validation->set_message('check_date', 'From Date is required.');
                return FALSE;
            }

            // Convert date strings to timestamps for comparison
            $from_date_timestamp = strtotime($from_date);
            $to_date_timestamp = strtotime($to_date);

            if ($from_date_timestamp > $to_date_timestamp) {
                $this->form_validation->set_message('check_date', 'From Date cannot be greater than To Date.');
                return FALSE;
            }

            return TRUE;
        }
    }


    public function bank_account($type = '')
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else if ($type == 'submit') {
            $this->form_validation->set_rules('ac_holder_name', 'Name', 'trim|required|regex_match[/^[a-zA-Z\s]+$/]');
            $this->form_validation->set_rules('ac_number', 'Account Number', 'trim|required|numeric');
            $this->form_validation->set_rules('bank_id', 'Bank Name', 'trim|required');
            $this->form_validation->set_rules('ifsc', 'IFSC', 'trim|required|regex_match[/^[a-zA-Z0-9]+$/]');
            if ($this->form_validation->run() == FALSE) {
                $this->data['banks'] = $this->db->query("SELECT * FROM banks ORDER BY name ASC")->result_array();
                $this->load->view('executive_app/bank_account', $this->data);
                // $this->load->view('executive_app/bank_account');
            } else {
                $executive_id = $this->session->user_id;

                $bank_id = $this->input->post('bank_id');
                $ac_number = $this->input->post('ac_number');
                $ac_holder_name = $this->input->post('ac_holder_name');
                $ifsc = $this->input->post('ifsc');

                $form_data = array(
                    'bank_id' => $bank_id,
                    'ifsc' => $ifsc,
                    'ac_number' => $ac_number,
                    'ac_holder_name' => $ac_holder_name
                );

                $data = array(
                    'bankAccount' => $ac_number,
                    'ifsc' => $ifsc
                );

                $jsonData = json_encode($data);

                $url = base_url('verify_bank_details_post');

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);

                curl_setopt($ch, CURLOPT_POST, 1);

                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($ch);


                if (curl_errno($ch)) {
                    $error_msg = curl_error($ch);
                    curl_close($ch);

                    log_message('error', 'cURL error: ' . $error_msg);
                    show_error('An error occurred while verifying bank details. Please try again later.');
                } else {
                    curl_close($ch);

                    $response_data = json_decode($response, true);

                    if ($response_data['status'] === true) {


                        $this->data['executive_bank_details_exists'] = $this->executive_model->check_bank_details($executive_id, $ifsc, $ac_number);

                        if ($this->data['executive_bank_details_exists']) {
                            // Bank name and account number already exist
                            $this->session->set_flashdata('error_message', 'Account number already exist.');
                            $this->session->set_flashdata('form_data', $form_data);
                            redirect('executive/bank_account/r', 'refresh');
                        } else {

                            if ($response_data['data']['status'] == 'SUCCESS' && $response_data['data']['accountStatus'] == 'VALID') {

                                $data_executive_bank = array(
                                    'executive_id' => $executive_id,
                                    'bank_id' => $bank_id,
                                    'ifsc' => $ifsc,
                                    'ac_holder_name' => $ac_holder_name,
                                    'ac_number' => $ac_number,
                                    'is_primary' => 1,
                                );
                                $this->db->insert('executive_bank_details', $data_executive_bank);
                                if ($this->db->affected_rows() > 0) {
                                    $this->data['executive_bank_details'] = $this->executive_model->get_bank_details($executive_id);
                                    redirect("executive/bank_account/r", 'refresh');
                                }
                            } else if ($response_data['data']['status'] == 'ERROR' && $response_data['data']['subCode'] == 422) {
                                $this->session->set_flashdata('error_message', $response_data['data']['message']);
                                $this->session->set_flashdata('form_data', $form_data);
                                redirect('executive/bank_account/r', 'refresh');
                            } else if ($response_data['data']['status'] == 'ERROR' && $response_data['data']['subCode'] == 424) {
                                $this->session->set_flashdata('error_message', 'Account Number is not valid');
                                $this->session->set_flashdata('form_data', $form_data);
                                redirect('executive/bank_account/r', 'refresh');
                            } else if ($response_data['data']['status'] == 'SUCCESS' && $response_data['data']['accountStatus'] == 'VALID') {
                                $this->session->set_flashdata('error_message', $response_data['data']['message']);
                                $this->session->set_flashdata('form_data', $form_data);
                                redirect('executive/bank_account/r', 'refresh');
                            } else {
                                $this->session->set_flashdata('error_message', $response_data['data']['message']);
                                $this->session->set_flashdata('form_data', $form_data);
                                redirect('executive/bank_account/r', 'refresh');
                            }
                        }
                    } else {

                        if (!empty($response_data['message']['bankAccount']) || !empty($response_data['message']['ifsc'])) {
                            if (!empty($response_data['message']['bankAccount'])) {
                                $this->session->set_flashdata('error_bank_account', $response_data['message']['bankAccount']);
                            }
                            if (!empty($response_data['message']['ifsc'])) {
                                $this->session->set_flashdata('error_ifsc', $response_data['message']['bankAccount']);
                            }
                            redirect('executive/bank_account/r', 'refresh');
                        }
                    }
                }
            }
        } else if ($type == 'update_primary_account') {
            $account_number = $this->input->post('account_number');
            $ifsc = $this->input->post('ifsc');
            $executive_id = $this->session->user_id;

            if ($account_number) {
                $this->executive_model->set_primary_account($executive_id, $account_number, $ifsc);
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Account number is missing']);
            }
        } else if ($type == 'r') {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            if ($login_user['status'] == 1) {
                $executive_id = $this->session->user_id;

                $executive_address = $this->executive_address_model->where([
                    'user_id' => $executive_id
                ])->get();
                if ($executive_address['executive_type_id'] == 1) {

                    $this->data['executive_bank_details'] = $this->executive_model->get_bank_details($executive_id);
                    $this->data['banks'] = $this->db->query("SELECT * FROM banks ORDER BY name ASC")->result_array();
                    $this->load->view('executive_app/bank_account', $this->data);
                } else {
                    redirect('executive/dashboard');
                }
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }

    public function referral_link()
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            if ($login_user['status'] == 1) {
                $user_id = $this->session->userdata('user_id');
                $data = $this->user_model->get_user_by_id($user_id);
                $this->load->view('executive_app/referral_links', $data[0]);
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }

    public function profile1()
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            
            if ($login_user['status'] == 1) {
                $user_id = $this->session->userdata('user_id');
                $data = $this->user_model->get_user_by_id($user_id);
                $user_signature = $this->setting_model->get_user_signature();
                $data[0]['company_signature'] = base_url('uploads/admin/' . $user_signature);
                $this->load->view('executive_app/profile', $data[0]);
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }
    public function profile()
{
    if (!$this->ion_auth->executive_logged_in()) {
        redirect('executive/login');
        return;
    }

    $user_id = $this->session->userdata('user_id');

    // Logged user basic info
    $login_user = $this->user_model->where(['id' => $user_id])->get();

    if (empty($login_user) || $login_user['status'] != 1) {
        $this->session->sess_destroy();
        $this->load->view('executive_app/login');
        return;
    }

    // Signature
    $user_signature = $this->setting_model->get_user_signature();
    $data['company_signature'] = base_url('uploads/admin/' . $user_signature);

    /* ===================================================
       ROLE BASED PROFILE DATA
    =================================================== */

        // 🔵 EXECUTIVE PROFILE
        $exec = $this->user_model->get_user_by_id($user_id);

        $data['id']             = $exec[0]['id'];
        $data['display_name']   = $exec[0]['first_name'] . ' ' . $exec[0]['last_name'];
        $data['email']          = $exec[0]['email'];
        $data['phone']          = $exec[0]['phone'];
        $data['primary_intent'] = $exec[0]['primary_intent'];


    $this->load->view('executive_app/profile', $data);
}


    public function edit_profile($type = '')
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            if ($type == 'r') {
                $login_user = $this->user_model->where([
                    'id' => $this->session->user_id
                ])->get();
                if ($login_user['status'] == 1) {
                    $user_id = $this->session->userdata('user_id');
                    //echo $user_id;
                    $data = $this->user_model->get_user_by_id($user_id);
                    $this->load->view('executive_app/edit_profile', $data[0]);
                } else {
                    $this->session->sess_destroy();
                    $this->load->view('executive_app/login');
                }
            } else if ($type == 'c') {

                $this->form_validation->set_rules('first_name', 'First Name', 'required|regex_match[/^[a-zA-Z\s]+$/]');
                $this->form_validation->set_rules('last_name', 'Last Name', 'required|regex_match[/^[a-zA-Z\s]+$/]');

                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('executive_app/edit_profile');
                } else {
                    $this->load->helper(array('form', 'url'));

                    $user_id = $this->session->userdata('user_id');


                    $first_name = $this->input->post('first_name');
                    $last_name = $this->input->post('last_name');


                    $sql_update_user = "UPDATE `users` SET `first_name`='$first_name', `last_name`='$last_name' WHERE id=$user_id";
                    
                   // echo $sql_update_user; exit;
                    $this->db->query($sql_update_user);

                    // if ($this->db->affected_rows() > 0) {
                    if (!empty($_FILES['profile_image']['name'])) {
                        $upload_path = './uploads/profile_image/';
                        if (file_exists($upload_path . 'profile_' . $user_id . '.jpg')) {
                            unlink($upload_path . 'profile_' . $user_id . '.jpg');
                        }

                        if (!is_dir($upload_path)) {
                            mkdir($upload_path, 0777, true);
                        }

                        $config['upload_path'] = $upload_path;
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] = 'profile_' . $user_id . '.jpg';

                        $this->load->library('upload', $config);
                        $phone_number = $this->input->post('phone_number');

                        if ($this->upload->do_upload('profile_image')) {
                            $uploadData = $this->upload->data();
                            $file_name = $uploadData['file_name'];
                            $imageTemp = $uploadData['full_path'];
                            $imageSize = $uploadData['file_size'];

                            $status = false;

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
                                    $status = true;
                                } else {
                                    $this->session->set_flashdata('error_message', 'Something went wrong while compressing the image.');
                                    $this->load->view('executive_app/edit_profile');
                                }
                            } else {
                                $compressedImage = $imageTemp;
                                $compressedImageSize = $imageSize;
                                $status = true;
                            }
                            if ($status == true) {
                                $this->session->set_flashdata('success_message', 'User updated successfully.');
                                redirect("executive/profile", 'refresh');
                            }
                        }
                    } else {
                        $this->session->set_flashdata('success_message', 'User updated successfully.');
                        redirect("executive/profile", 'refresh');
                    }
                    // }
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

    public function validate_profile_image()
    {
        if (empty($_FILES['profile_image']['name'])) {
            $this->form_validation->set_message('validate_profile_image', 'The {field} field is required.');
            return FALSE;
        } else {
            return TRUE;
        }
    }







    public function referral_video()
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            if ($login_user['status'] == 1) {

                $referral_video = $this->setting_model->get_executive_referral_video();
                $data['referral_video'] = $referral_video;
                $this->load->view('executive_app/referral_video', $data);
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }

    public function executive_terms_conditions()
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            $login_user = $this->user_model->where([
                'id' => $this->session->user_id
            ])->get();
            if ($login_user['status'] == 1) {
                $data['termandconditions'] = $this->termsconditions_model->get_executive_register_terms();
                $this->load->view('executive_app/executive_terms_conditions', $data);
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }




    public function terms()
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
            $this->load->view('executive_app/terms');
        }
    }
    public function reports($from_date = null, $to_date = null)
    {
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
        $login_user = $this->user_model->where([
            'id' => $this->session->user_id
        ])->get();
        if ($login_user['status'] == 1) {
            //echo $login_user['primary_intent'];
            //echo $this->session->user_id; exit;
                $from_date = $this->input->get('from_date');
                $to_date   = $this->input->get('to_date');
                $filter_type = $this->input->get('filter_type');
                
                    if ($filter_type == 'weekly') {
                    $from_date = date('Y-m-d', strtotime('monday this week'));
                    $to_date   = date('Y-m-d', strtotime('sunday this week'));
                }
            
                // Monthly filter
                if ($filter_type == 'monthly') {
                    $from_date = date('Y-m-01');
                    $to_date   = date('Y-m-t');
                }
            
                // Yearly filter
                if ($filter_type == 'yearly') {
                    $from_date = date('Y-01-01');
                    $to_date   = date('Y-12-31');
                }
                if (!empty($from_date) && !empty($to_date)) {
            
                    $from_datetime = $from_date . " 00:00:00";
                    $to_datetime   = $to_date . " 23:59:59";
            
                    $this->db->where('created_at >=', $from_datetime);
                    $this->db->where('created_at <=', $to_datetime);
                }
  
            $this->data['executives']= $this->vendor_list_model->fields('id, name, email, unique_id, category_id, executive_id, status,created_at')
             ->where([ 'executive_user_id' => $this->session->user_id ])->get_all();
            // echo $this->db->last_query(); exit;

           $this->load->view('executive_app/reports', $this->data);
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }
    
    
    public function vendorchecklist(){
        if (!$this->ion_auth->executive_logged_in()) {
            redirect('executive/login');
        } else {
        $login_user = $this->user_model->where([
            'id' => $this->session->user_id
        ])->get();
        if ($login_user['status'] == 1) {
            //echo $login_user['primary_intent'];
            //echo $this->session->user_id; exit;
                $from_date = $this->input->get('from_date');
                $to_date   = $this->input->get('to_date');
                $filter_type = $this->input->get('filter_type');
                
                    if ($filter_type == 'weekly') {
                    $from_date = date('Y-m-d', strtotime('monday this week'));
                    $to_date   = date('Y-m-d', strtotime('sunday this week'));
                }
            
                // Monthly filter
                if ($filter_type == 'monthly') {
                    $from_date = date('Y-m-01');
                    $to_date   = date('Y-m-t');
                }
            
                // Yearly filter
                if ($filter_type == 'yearly') {
                    $from_date = date('Y-01-01');
                    $to_date   = date('Y-12-31');
                }
                if (!empty($from_date) && !empty($to_date)) {
            
                    $from_datetime = $from_date . " 00:00:00";
                    $to_datetime   = $to_date . " 23:59:59";
            
                    $this->db->where('created_at >=', $from_datetime);
                    $this->db->where('created_at <=', $to_datetime);
                }
  
            $this->data['executives']= $this->vendor_list_model->fields('id, name, email, unique_id, category_id, executive_id, status,created_at')
             ->where([ 'executive_user_id' => $this->session->user_id ])->get_all();
            // echo $this->db->last_query(); exit;

           $this->load->view('executive_app/vendorchecklist', $this->data);
            } else {
                $this->session->sess_destroy();
                $this->load->view('executive_app/login');
            }
        }
    }
    
    
    public function viewdetails($id){
        $this->data['states'] = $this->state_model->order_by('id', 'DESC')->get_all();
        $this->data['districts'] = $this->district_model->order_by('id', 'DESC')->get_all();
        $this->data['constituencies'] = $this->constituency_model->with_state('fields:id,name')->with_district('fields:id,name')->order_by('id', 'DESC')->get_all();
        

    $this->data['vendor'] = $this->vendor_list_model->with_location('fields: id, address, latitude, longitude')
        ->with_category('fields: id, name')
        ->with_constituency('fields: id, name, state_id, district_id')
        ->with_contacts('fields: id, std_code, number, type')
        ->with_links('fields: id,   url, type')
        ->with_amenities('fields: id, name')
        ->with_services('fields: id, name')
        ->with_brands('fields: id, name')
        ->with_holidays('fields: id')
        ->where('id', $id)
        ->order_by('name', 'DESC')
        ->get();
        $this->data['bank_details'] = $this->vendor_bank_details_model->fields('id,bank_name,bank_branch,ifsc,ac_holder_name,ac_number')
        ->where([
            'list_id' => $id,
            'status' => 1
        ])->get();
    //echo $this->db->last_query(); 
    $this->load->view('executive_app/view_details', $this->data);
        
    }
}
