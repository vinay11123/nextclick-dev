<?php
(defined('BASEPATH')) or exit('No direct script access allowed');
use Firebase\JWT\JWT;
/**
 *
 * @author Mehar
 *         Admin module
 */
class App extends MY_Controller
{

    function __construct()
    {
        error_reporting(E_ERROR | E_PARSE);
        parent::__construct();
        $this->template = 'template/admin/main';
        if (!$this->ion_auth->logged_in()) // || ! $this->ion_auth->is_admin()
            redirect('auth/login');

        $this->load->library('pagination');
        $this->load->model('vendor_bank_details_model');
        $this->load->library('upload');
        $this->load->library('form_validation');
        $this->load->model('group_model');
        $this->load->model('user_model');
        $this->load->model('permission_model');
        $this->load->model('permission_batch_model');
        $this->load->model('group_permission_model');
        $this->load->model('setting_model');
        $this->load->model('sliders_model');
        $this->load->model('advertisements_model');
        $this->load->model('user_service_model');
        $this->load->model('category_model');
        $this->load->model('vendor_list_model');
        $this->load->model('cat_banners_model');
        $this->load->model('faq_model');
        $this->load->model('app_details_model');
        $this->load->model('location_model');
        $this->load->model('stock_settings_model');
        $this->load->model('termsconditions_model');
        $this->load->model('user_doc_model');
        $this->load->model('package_model');
        $this->load->model('service_model');
        $this->load->model('vendor_package_model');
        $this->load->model('vendor_list_model');
        $this->load->model('user_group_model');
        $this->load->model('return_policies_model');
        $this->load->model('service_tax_model');
        $this->load->model('state_model');
        $this->load->model('district_model');
        $this->load->model('constituency_model');
        $this->load->model('sub_category_model');
        $this->load->model('food_menu_model');
        $this->load->model('master_package_setting_model');
        $this->load->model('package_setting_model');
        $this->load->model('delivery_boy_address_model');
        $this->load->model('delivery_boy_bank_details_model');
        $this->load->model('vehicle_model');
        $this->load->model('user_account_model');
        $this->load->model('payout_model');
        $this->load->model('delivery_boy_payment_model');
        $this->load->model('delivery_partner_location_tracking_model');
        $this->load->model('cupons_model');
        $this->load->model('app_model');
    }

    public function index()
    {
        if ($this->ion_auth->get_users_groups()->result()[0]->name == 'vendor') {
            redirect('vendor_crm/dashboard');
        } else {
            redirect('admin/dashboard');
        }
    }

    /**
     * Employee Management
     *
     * @author Mehar
     * @param string $type
     */
    public function allusers()
    {
        $this->data['title'] = 'All Users List';
        $this->data['nav_type'] = 'users';
        $this->data['content'] = 'app/allusers';
        $this->data['executives'] = $this->app_model->get_users();
        $this->_render_page($this->template, $this->data);
        //$this->load->view('app/allusers', $data);
     
    }
    
    public function allvendors()
    {
        $this->data['title'] = 'All Vendors List';
        $this->data['nav_type'] = 'vendors';
        $this->data['content'] = 'app/allvendors';
        $this->data['executives'] = $this->app_model->get_vendors();
        $this->_render_page($this->template, $this->data);
        //$this->load->view('app/allusers', $data);
     
    }
    
    public function alldeliverypartner()
    {
        $this->data['title'] = 'All Delivery Partner List';
        $this->data['nav_type'] = 'delivery_partner';
        $this->data['content'] = 'app/alldelivered';
        $this->data['executives'] = $this->app_model->get_delivery_partner();
        $this->_render_page($this->template, $this->data);
        //$this->load->view('app/allusers', $data);
     
    }
    
    
    public function allexecutive()
    {
        $this->data['title'] = 'All Executive List';
        $this->data['nav_type'] = 'executive';
        $this->data['content'] = 'app/allexecutive';
        $this->data['executives'] = $this->app_model->get_executive();
        $this->_render_page($this->template, $this->data);
        //$this->load->view('app/allusers', $data);
     
    }

    public function allorders(){
        
            $this->data['title'] = 'All Orders';
            $this->data['content'] = 'app/allorders';
            $this->data['nav_type'] = 'allorders';
            $this->data['toporders'] = $this->db->query("SELECT 
            u.id AS user_id,
            u.first_name,
            u.last_name,
            u.email,
            u.phone,
            eo.created_user_id,
            COUNT(eo.id) AS total_orders
        
        FROM ecom_orders eo
        
        LEFT JOIN users u ON u.id = eo.created_user_id
        
        WHERE eo.deleted_at IS NULL
        AND eo.created_user_id IS NOT NULL
        AND u.id IS NOT NULL
        
        GROUP BY eo.created_user_id
        
        HAVING COUNT(eo.id) > 3
        
        ORDER BY total_orders DESC;")->result_array();
           // echo $this->db->last_query(); exit;
            
            $this->data['loworders'] = $this->db->query("SELECT 
            u.id AS user_id,
            u.first_name,
            u.last_name,
            u.email,
            u.phone,
            eo.created_user_id,
            COUNT(eo.id) AS total_orders
        
        FROM ecom_orders eo
        
        LEFT JOIN users u ON u.id = eo.created_user_id
        
        WHERE eo.deleted_at IS NULL
        AND eo.created_user_id IS NOT NULL
        AND u.id IS NOT NULL
        
        GROUP BY eo.created_user_id
        
        HAVING COUNT(eo.id) < 3
        
        ORDER BY total_orders DESC;")->result_array();
            
        echo $this->_render_page($this->template, $this->data);
    }


   public function orderdetails()
{
    $uid = (int) $this->input->get('uid'); // safe casting

    if(empty($uid)){
        show_error("Invalid User ID");
    }

    $this->data['title'] = 'Order Details';
    $this->data['content'] = 'app/orderdetails';
    $this->data['nav_type'] = 'orderdetails';

    $this->db->select("
        eo.*,
        eo.total as grand_total,
        ua.address,
        u.first_name,
        us.first_name as delivery_boy_name,
        us.id as delivery_boy_id,
        vl.name as vendor_name,
        dm.name as delivery_mode_name,
        ep.txn_id as payment_txn_id,
        ep.payment_method_id,
        vl.id as vendorpreid,
        vl.business_name as ordervendor_name
    ");

    $this->db->from('ecom_orders as eo');
    $this->db->join('users_address as ua', 'ua.id = eo.shipping_address_id', 'left');
    $this->db->join('users as u', 'u.id = eo.created_user_id', 'left');
    $this->db->join('vendors_list as vl', 'vl.vendor_user_id = eo.vendor_user_id', 'left');
    $this->db->join('delivery_modes as dm', 'dm.id = eo.delivery_mode_id', 'left');
    $this->db->join('ecom_payments as ep', 'ep.id = eo.payment_id', 'left');
    $this->db->join('delivery_jobs as dj', 'dj.ecom_order_id = eo.id', 'left');
    $this->db->join('users as us', 'us.id = dj.delivery_boy_user_id', 'left');

    $this->db->where('eo.created_user_id', $uid);
    $this->db->where('eo.deleted_at IS NULL', null, false);
    $this->db->order_by('eo.id', 'DESC');

    $this->data['orders'] = $this->db->get()->result_array();

    // other dropdown data
    $this->data['sts'] = $this->db->get('ecom_order_statuses')->result_array();
    $this->data['vendors'] = $this->db->order_by('name','asc')->get('vendors_list')->result_array();
    $this->data['customers'] = $this->db->order_by('first_name','asc')->get('users')->result_array();
    $this->data['payment_modes'] = $this->db->get('payment_methods')->result_array();
    $this->data['delivery_boy_names'] = $this->db->query("
        SELECT *
        FROM delivery_jobs as dj 
        JOIN users as u ON u.id = dj.delivery_boy_user_id 
        GROUP BY dj.delivery_boy_user_id
    ")->result_array();

    echo $this->_render_page($this->template, $this->data);
}


    public function sendPNotification()
{
    $user_ids  = json_decode($_POST['user_ids'], true);
    $title     = $this->input->post('title');
    $message   = $this->input->post('message');
    $serverKey = $this->input->post('server_key');
    
 // Debug (remove after testing)
   // print_r($this->input->post()); exit;


    if (empty($user_ids)) {
        echo json_encode(['status' => 0, 'message' => 'No users selected']);
        return;
    }

    foreach ($user_ids as $user_id) {
 // echo $user_id; exit;
        // Get user FCM token
        $this->db->where('user_id', $user_id);
        $this->db->order_by('id', 'DESC');   // 🔥 important
        $user = $this->db->get('fcm')->row();

        if (!empty($user->token)) {

            $fields = [
                "message" => [
                    "token" => $user->token,
                    "notification" => [
                        "title" => $title,
                        "body"  => $message
                    ],
                    "data" => [
                        "click_action" => "FLUTTER_NOTIFICATION_CLICK"
                    ]
                ]
            ];

            $this->sendPushNotification($fields, $serverKey);
        }
    }

    echo json_encode(['status' => 1, 'message' => 'Notification Sent Successfully']);
}

    private function sendPushNotification($fields, $server_key)
    {

echo '<pre>';
var_dump($fields);
var_dump($server_key);
exit;
    // Define valid server keys and corresponding service account JSON paths and URLs
    $validKeys = [
        'App Users' => [
            'serviceAccountJson' => APPPATH . 'config/service_accounts/nextclickuser.json',
            'url' => 'https://fcm.googleapis.com/v1/projects/nextclickuser-df6c4/messages:send' // Replace with the actual project ID for Vendor Application
        ]
    ];
    if (array_key_exists($server_key, $validKeys)) {
        
        // Load the service account JSON file and send URL based on the server key
        $serviceAccountJson = $validKeys[$server_key]['serviceAccountJson'];
        $url = $validKeys[$server_key]['url'];
 
        // Generate the access token
        $accessToken = $this->generateAccessToken($serviceAccountJson);

        if (!$accessToken) {
            return ['status' => 'error', 'message' => 'Failed to generate access token'];
        }

        // Send the actual push notification using the determined URL
        return $this->sendPush($fields, $accessToken, $url);
    } else {
        return ['status' => 'error', 'message' => 'Invalid server key'];
    }
}

private function generateAccessToken($serviceAccountJsonPath)
{
    // Load service account JSON file
    $serviceAccount = json_decode(file_get_contents($serviceAccountJsonPath), true);
    
    // Check if service account is valid and contains required keys
    if (!$serviceAccount) {
        return null;
    }

    $clientEmail = $serviceAccount['client_email'];
    $privateKey = trim(str_replace('\\n', "\n", $serviceAccount['private_key'])); // Make sure private key is correctly formatted
    
    // Ensure private key is not empty
    if (empty($privateKey)) {
        log_message('error', 'Private key is empty or improperly formatted.');
        return null;
    }

    // Create the JWT
    $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
    $claimSet = json_encode([
        'iss' => $clientEmail,
        'scope' => 'https://www.googleapis.com/auth/cloud-platform', // Ensure this is correct
        'aud' => 'https://oauth2.googleapis.com/token',
        'exp' => time() + 3600, // Token is valid for 1 hour
        'iat' => time()
    ]);

    // Encode to Base64URL
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlClaimSet = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($claimSet));

    // Signing
    $signatureInput = $base64UrlHeader . '.' . $base64UrlClaimSet;
    $signature = '';
    if (!openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
        log_message('error', 'Failed to sign JWT with private key.');
        return null;
    }

    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    
    // Create the final JWT
    $jwt = $signatureInput . '.' . $base64UrlSignature;

    // Prepare the POST request to get the access token
    $url = 'https://oauth2.googleapis.com/token';
    $postFields = http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt
    ]);

    // Send the request using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    
    $response = curl_exec($ch);
    
    // Handle cURL errors
    if ($response === false) {
        return null;
    }

    curl_close($ch);

    // Decode the response
    $responseData = json_decode($response, true);

    // Check for access token in response
    if (isset($responseData['access_token'])) {
        return $responseData['access_token'];
    } else {
        return null;
    }
}

private function sendPush($fields, $accessToken ,$url)
{
   // $url = 'https://fcm.googleapis.com/v1/projects/nextclickuser-df6c4/messages:send'; // Replace with your project ID

    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Only for testing; consider removing in production
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    // Execute post
    $result = curl_exec($ch);

    // Check for cURL errors
    if ($result === false) {
        log_message('error', 'cURL error: ' . curl_error($ch));
        return ['status' => 'error', 'message' => 'cURL error: ' . curl_error($ch)];
    }

    // Always log the result
    log_message('error', 'FCM Response: ' . $result);

    // Close connection
    curl_close($ch);

    return $result;
}


}
