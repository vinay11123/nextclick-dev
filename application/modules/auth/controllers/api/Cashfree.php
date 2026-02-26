<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';
require('vendor/autoload.php');

use Firebase\JWT\JWT;
use Rakit\Validation\Validator;

class Cashfree extends MY_REST_Controller
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

    public function aadhar_otp_genrate_post()
    {
        // $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (isset($_POST['aadhaar_number']) && !empty($_POST['aadhaar_number'])) {
            $settings_client_id = $this->setting_model->where("key", 'cashfree_client_id')->get();
            $settings_client_secreat = $this->setting_model->where("key", 'cashfree_client_secret')->get();
            $aadhaar_number = $this->input->post('aadhaar_number');
            $curl = curl_init();
            $input = ["aadhaar_number" => $aadhaar_number];

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
                    'Content-Type: application/json'
                ),
            ));

            $json = curl_exec($curl);

            curl_close($curl);

            $response = json_decode($json, true);

            echo $this->set_response_simple($response, 'success', REST_Controller::HTTP_OK, TRUE);
        }
    }
    public function aadhar_otp_verify_post()
    {
        // $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (isset($_POST['ref_id']) && !empty($_POST['ref_id']) && isset($_POST['otp']) && !empty($_POST['otp'])) {
            $settings_client_id = $this->setting_model->where("key", 'cashfree_client_id')->get();
            $settings_client_secreat = $this->setting_model->where("key", 'cashfree_client_secret')->get();
            $ref_id = $this->input->post('ref_id');
            $otp = $this->input->post('otp');
            $curl = curl_init();
            $input = ["otp" => $otp, "ref_id" => $ref_id];

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
                    'accept: application/json',
                    'content-type: application/json',
                    'x-client-id: ' . $settings_client_id['value'],
                    'x-client-secret: ' . $settings_client_secreat['value'],
                ),
            ));

            $json = curl_exec($curl);

            curl_close($curl);

            $response = json_decode($json, true);

            $this->set_response_simple($response, 'success', REST_Controller::HTTP_OK, TRUE);
        }
    }
}
