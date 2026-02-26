<?php
require_once APPPATH . '/libraries/MY_REST_Controller.php';
require_once APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class Payment extends MY_REST_Controller
{

    public $checkSum;

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        $this->load->model('user_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('bank_details_model');
        $this->load->model('ecom_payment_model');
        $this->load->model('delivery_boy_payment_model');
        $this->load->model('delivery_job_model');
        $this->load->model('payment_link_model');
        $this->load->model('payment_refund_model');
        $this->load->model('ecom_order_model');
        $this->load->model('bank_model');
        $this->load->model('user_account_model');
        $this->load->model('vehicle_model');
    }

    /**
     * To manage wallet amount
     *
     * @author Mehar
     *        
     */
     
     
     
public function create_order_post()
{
    // Read RAW JSON
    $raw = json_decode(file_get_contents("php://input"), true);

    if (empty($raw['amount'])) {
        return $this->set_response([
            'status' => false,
            'error'  => 'Amount required'
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    $amount  = $raw['amount'];   // rupees
    $receipt = $raw['receipt'] ?? null;
    $notes   = $raw['notes'] ?? [];

    try {
        $order = $this->createRazorpayOrder($amount, $receipt, $notes);

        return $this->set_response([
            'status' => true,
            'order'  => $order->toArray()
        ], REST_Controller::HTTP_OK);

    } catch (Exception $e) {
        return $this->set_response([
            'status' => false,
            'error'  => $e->getMessage()
        ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    }
}
private function createRazorpayOrder($amount, $receipt = null, $notes = [])
{
    $key_id = 'rzp_test_uKuFPZt9ZS7T5b';
    $secret = 'fciVpAiiObBcxdpVLPtFr6lK';

    $api = new Api($key_id, $secret);

    return $api->order->create([
        'amount'   => $amount ,   // rupees → paise
        'currency' => 'INR',
        'receipt'  => $receipt ?? uniqid('rcpt_'),
        'notes'    => $notes
    ]);
}

public function cashfreeorder_post()
{
    $raw = json_decode(file_get_contents("php://input"), true);

    if (!isset($raw['order_amount']) || $raw['order_amount'] <= 0) {
        return $this->set_response([
            'status' => false,
            'error'  => 'Order amount required'
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    $amount = $raw['order_amount'];

    $customer = [
        'id'    => $raw['customer_details']['customer_id'] ?? null,
        'phone' => $raw['customer_details']['customer_phone'] ?? null,
        'email' => $raw['customer_details']['customer_email'] ?? null
    ];

    try {
        $order = $this->createCashfreeOrder($amount, $customer);

        if (!$order['status']) {
            throw new Exception($order['error'] ?? 'Order creation failed');
        }

        return $this->set_response([
            'status' => true,
            'order'  => $order['response']
        ], REST_Controller::HTTP_OK);

    } catch (Exception $e) {
        return $this->set_response([
            'status' => false,
            'error'  => $e->getMessage()
        ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    }
}


private function createCashfreeOrder($amount, $customer = [])
{
    $clientId     = 'TEST1017980643114c0164e5a402b3fb60897101';     // define in config/constants.php
    $clientSecret = 'cfsk_ma_test_7cab5e6e05d0d42b8c15c77767fb5ae5_4db5bc8a';
    $apiVersion   = "2025-01-01";

    $env = "sandbox"; // change to "api" for LIVE
    $baseUrl = ($env === "sandbox")
        ? "https://sandbox.cashfree.com/pg/orders"
        : "https://api.cashfree.com/pg/orders";

    $payload = [
        "order_id" => "ORD_" . time(),
        "order_currency" => "INR",
        "order_amount" => (float)$amount,
        "customer_details" => [
            "customer_id"    => $customer['id'] ?? 'cust_' . time(),
            "customer_phone" => $customer['phone'] ?? '9999999999',
            "customer_email" => $customer['email'] ?? 'test@example.com'
        ]
    ];

    $headers = [
        "Content-Type: application/json",
        "x-client-id: $clientId",
        "x-client-secret: $clientSecret",
        "x-api-version: $apiVersion"
    ];

    $ch = curl_init($baseUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        curl_close($ch);
        return [
            "status" => false,
            "error" => curl_error($ch)
        ];
    }

    curl_close($ch);
    $decoded = json_decode($response, true);

    if ($httpCode != 200 && $httpCode != 201) {
        return [
            "status" => false,
            "error" => $decoded['message'] ?? 'Cashfree API Error',
            "raw" => $decoded
        ];
    }

    return [
        "status" => true,
        "response" => $decoded
    ];
}

    public function wallet_post()
    {       
        $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);		
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

        $data = null;

        if($this->input->post('role') == "user") {
            $data['wallet_transactions'] = [];
            $data['user']['wallet'] = 0;
        }
        else if($this->input->post('role') == "vendor") {
            $data['wallet_transactions'] = $this->wallet_transaction_model->getVendorEarningTransactions($token_data->id, (empty($this->input->post('start_date'))) ? NULL : $this->input->post('start_date'), (empty($this->input->post('end_date'))) ? NULL : $this->input->post('end_date'));
            $data['user'] = $this->user_account_model->fields('vendor_earning_wallet as wallet')->where('user_id', $token_data->id)->get();
        }
        else {

            if(($this->input->post('status') == 1 || $this->input->post('status') == 2 || $this->input->post('status') == 3) && (!empty($this->input->post('start_date')) && !empty($this->input->post('end_date')))) {    
                if($this->input->post('status') == 1 || $this->input->post('status') == 3) { 
                    $data['wallet_transactions'] = $this->wallet_transaction_model->getDeleveryBoyEarningTransactions($token_data->id, (empty($this->input->post('start_date'))) ? NULL : $this->input->post('start_date'), (empty($this->input->post('end_date'))) ? NULL : $this->input->post('end_date'));
                }
                else if($this->input->post('status') == 2) {
                    $data['wallet_transactions'] = $this->wallet_transaction_model->all(NULL, NULL, $token_data->id, (empty($this->input->post('start_date'))) ? NULL : $this->input->post('start_date'), (empty($this->input->post('end_date'))) ? NULL : $this->input->post('end_date'), NULL, NULL, (empty($this->input->post('type'))) ? NULL : $this->input->post('type'), (empty($this->input->post('status'))) ? NULL : $this->input->post('status'), FALSE);
                }
            }
            elseif($this->input->post('status') == 4) {    
                    
                $data['wallet_transactions'] = $this->wallet_transaction_model->all(NULL, NULL, $token_data->id, '01-01-2020',  date('d-m-Y') , NULL, NULL, (empty($this->input->post('type'))) ? NULL : $this->input->post('type'), (empty($this->input->post('status'))) ? NULL : $this->input->post('status'), FALSE);
            }
            else {
                $data['wallet_transactions'] = [];
            }

            $data['user'] = $this->user_account_model->fields('delivery_boy_earning_wallet as ecom_wallet, floating_wallet')->where('user_id', $token_data->id)->get();
            $data['user']['pickup_wallet'] = 250;
            $security_deposit_wallet = $this->wallet_transaction_model->where('created_user_id', $token_data->id)->where('status', 4)->get();
            $data['user']['security_deposit_wallet'] = !empty($security_deposit_wallet['amount']) ? $security_deposit_wallet['amount'] : 0;
        }
        
        
        // $data['user'] = $this->user_model->fields('id, unique_id, wallet, floating_wallet')->where('id', $token_data->id)->get();
        
        $this->set_response_simple($data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * @desc to accept the payment from delivery boy to get COD orcers
     * @author Mehar
     * 
     * 
     */
    public function delivery_boy_wallet_topup_post()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $txn_id = 'DBT-' . generate_trasaction_no(10);
        $amount = $this->input->post('amount');
        $note = $this->input->post('note');
        $wallet_type = $this->input->post('wallet_type');
        if($wallet_type == 'floating_wallet' || $wallet_type == 'security_deposite') {
            $transaction_type = 'DEBIT';
        }
        else {
            $transaction_type = 'CREDIT';
        }
        $transaction_id = $this->input->post('transaction_id');
        $this->delivery_boy_payment_model->user_id = $token_data->id;
        $is_payment_done = $this->delivery_boy_payment_model->insert([
            'txn_id' => $transaction_id,
            'payment_mode' => 1,
            'amount' => $amount,
            'status' => 1
        ]);
        if ($is_payment_done) {
            $status = $this->user_model->payment_update($token_data->id, $amount, $transaction_type, $wallet_type, $txn_id, NULL, $note, $is_payment_done);
            if ($status) {
                $this->user_model->payment_update($this->config->item('super_admin_user_id'), $amount, $transaction_type, $wallet_type, $txn_id, NULL, NULL, $is_payment_done);
                $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'Something went wrong..!', REST_Controller::HTTP_OK, FALSE);
            }
            $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } else {
            $this->set_response_simple(NULL, 'Something went wrong..!', REST_Controller::HTTP_OK, FALSE);
        }
    }

    /**
     * To handle payment gateway response
     *
     * @author Mehar
     */
    public function payment_status_post()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->form_validation->set_rules($this->ecom_payment_model->rules);
        $this->ecom_payment_model->user_id = $token_data->id;
        if ($this->form_validation->run() == FALSE) {
            $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_OK, FALSE);
        } else {
            $txn_id = $this->input->post('payment_gw_txn_id');
            if (empty($this->input->post('payment_gw_txn_id'))) {
                $txn_id = uniqid();
            }
            $is_inserted = $this->ecom_payment_model->insert([
                'payment_method_id' => $this->input->post('payment_method_id'),
                'txn_id' => $txn_id,
                'amount' => $this->input->post('amount'),
                'message' => !empty($this->input->post('message')) ? $this->input->post('message') : NULL,
                'status' => $this->input->post('status')
            ]);
            if ($is_inserted) {
                $this->set_response_simple([
                    'payment_id' => $is_inserted
                ], 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
            } else {
                $this->set_response_simple(NULL, 'Failed..!', REST_Controller::HTTP_OK, FALSE);
            }
        }
    }
/*
    public function create_intent_post()
    {
        try {
            $keyId = "rzp_test_8FHdfbzKqsFwEy";
            $keySecret = "QuB0R6SDEuWa7SPJi2FshdIH";
            $api = new Api($keyId, $keySecret);
            $orderData = [
                'receipt'         => 3456,
                'amount'          => 123 * 100, // 2000 rupees in paise
                'currency'        => 'INR',
                'payment_capture' => 1 // auto capture
            ];
            $razorpayOrder = $api->order->create($orderData);
            $this->set_response([
                "order_ref" => $razorpayOrder->id
            ], Null, REST_Controller::HTTP_OK, TRUE);
        } catch (Exception $e) {
            $this->set_response(NULL, Null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR, TRUE);
        }
    }
*/
    public function create_payment_link_post()
    {
        try {
			$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
			$token_data =$this->validate_token($authorization_exp[1]);
		
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $orderID = $this->input->post('order');
            $razorPayInfo = $this->config->item('razorpay');
            $api = new Api($razorPayInfo["key"], $razorPayInfo["secret"]);
            $orderDetails = $this->ecom_order_model->getByTrackID($orderID);
            $orderPaymentLink = $this->payment_link_model->get([
                "ecom_order_id" =>$orderDetails["id"]
            ]);
            $userInfo = $this->user_model->get([
                "id" => $orderDetails["created_user_id"]
            ]);
            if($orderPaymentLink && $orderPaymentLink["id"]){
                sendCustomEmail($userInfo["email"], "Next Click Payment Link", "Please click on link to proceed with payment: ".$orderPaymentLink["payment_link"]);
                $this->set_response(NULL, "Link Has been Resent", REST_Controller::HTTP_OK);
            }else{
                $returnJson = $this->savePaymentLink(1, $orderDetails["id"], "Payment Related to Order #" . $orderDetails['track_id'], $orderDetails["total"], $orderDetails["created_user_id"], $token_data->id);
                if($returnJson["success"]){
                    $this->set_response($returnJson["data"], Null, REST_Controller::HTTP_OK);
                }else{
                    $this->set_response(Null, Null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        } catch (SignatureVerificationError $e) {
            $this->set_response(NULL, Null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function savePaymentLink($category=1, $referenceID, $descripption, $paymentValue, $targetUserID, $createdUserID){
        try{
            $this->load->helper('money');
            $razorPayInfo = $this->config->item('razorpay');
            $api = new Api($razorPayInfo["key"], $razorPayInfo["secret"]);
            $userInfo = $this->user_model->get([
                "id" => $targetUserID
            ]);
            $orderTotal = toPaise($paymentValue);
            if($category==2){
                $userInfo["email"]= "ksvskumar31@gmail.com";
                $userInfo["phone"]= "9502187787";
            }
            $attributes = array(
                'type' => 'link',
                'amount' => $orderTotal,
                'description' => $descripption,
                'customer' => array(
                    'email' => $userInfo["email"],
                    'contact' => $userInfo["phone"]
                )
            );
            $razorpayPaymentLink = $api->invoice->create($attributes);
            $data = [
                "category" => $category,
                "payment_link" => $razorpayPaymentLink->short_url,
                "payment_ref" => $razorpayPaymentLink->order_id,
                "payment_value" => $paymentValue,
                "created_user_id" => $createdUserID,
                "status" => 1
            ];
            if($category==1){
                $data["ecom_order_id"] = $referenceID;
            }else{
                $data["user_id"] = $referenceID;
            }
            $paymentLinkRecID = $this->payment_link_model->insert($data);
            $paymentLinkInfo = $this->payment_link_model->get(["id" => $paymentLinkRecID]);
            sendCustomEmail($userInfo["email"], "Next Click Payment Link", "Please click on link to proceed with payment: ".$razorpayPaymentLink->short_url);
            return [
                "success" => true,
                "data" => $paymentLinkInfo
            ];
        }catch(Exception $ex){
            return [
                "success" => false,
                "error" => $ex
            ];
        }
    }

    public function payment_update_post()
    {
        try {
            $this->load->helper('money', 'common');
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $event = $_POST["event"];
            $paymentInfo = $_POST["payload"]["payment"]["entity"];
            $response = null;
            switch ($event) {
                case "payment.authorized":
                    $response = $this->capturePayment($paymentInfo);
                    break;
                case "payment.captured":
                    $response = $this->updateCapturedPayment($paymentInfo);
                    break;
                case "payment.failed":
                    $response = $this->updatePaymentasFailed($paymentInfo);
                    break;
                default: 
                    break;
            }
            if($response["success"]){
                $this->set_response(true, Null, REST_Controller::HTTP_OK);
            }else{
                $this->set_response(NULL, Null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $e) {
            $this->set_response(NULL, NULL, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function banks_get()
    {
        try {
            $banksList = $this->bank_model->fields("name, code")->get_all();
            $this->set_response($banksList, Null, REST_Controller::HTTP_OK, TRUE);
        } catch (Exception $e) {
            $this->set_response(NULL, Null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR, TRUE);
        }
    }

    private function capturePayment($paymentInfo){
        try {
            $paymentRef = $paymentInfo["order_id"];
            $internalPaymentLinkInfo = $this->payment_link_model->get(["payment_ref" => $paymentRef]);
            if($internalPaymentLinkInfo){
                $this->payment_link_model->update([
                    "status" => 1
                ], $internalPaymentLinkInfo["id"]);
            }
            $razorPayInfo = $this->config->item('razorpay');
            $api = new Api($razorPayInfo["key"], $razorPayInfo["secret"]);
            $payment = $api->payment->fetch($paymentInfo["id"])->capture(array('amount'=>$paymentInfo["amount"], 'currency'=>$paymentInfo["currency"]));
            return [
                "success" =>true,
                "data" =>$payment
            ];
        } catch (Exception $e) {
            return [
                "success" =>false,
                "error" =>$e
            ];
        }
    }

    private function updateCapturedPayment($paymentInfo){
        try {
            $paymentRef = $paymentInfo["order_id"];
            $paymentRecieved= $paymentInfo["amount"];
            $this->db->trans_begin();
            $internalPaymentInfo = $this->payment_link_model->get(["payment_ref" => $paymentRef]);
            if ($internalPaymentInfo && $internalPaymentInfo["id"] && $internalPaymentInfo["status"]!==2) {
                $internalPaymentInfo["status"] = 2;
                $internalPaymentInfo["payment_recieved"] = $paymentRecieved;
                $this->payment_link_model->update([
                    "status" => $internalPaymentInfo["status"],
                    "payment_recieved" => $internalPaymentInfo["payment_recieved"]
                ], $internalPaymentInfo["id"]);
                switch($internalPaymentInfo["category"]){
                    case 1: 
                        $orderPayment = $this->ecom_payment_model->getPaymentByOrderID($internalPaymentInfo["ecom_order_id"]);
                        $orderInfo = $this->ecom_order_model->get([
                            "id" => $internalPaymentInfo["ecom_order_id"]
                        ]);
                        if ($orderInfo["delivery_mode_id"] == 1) {
                            $recepentUserID = $this->delivery_job_model->updateAmountCollected($internalPaymentInfo["ecom_order_id"], toRupees($paymentRecieved));
                            $this->user_model->creditToWallet($this->config->item('super_admin_user_id'), toRupees($paymentRecieved), $internalPaymentInfo["ecom_order_id"]);
                        }
                        $this->ecom_payment_model->markPaid($orderPayment["id"], $paymentInfo["id"], toRupees($paymentRecieved));
                        break;

                    case 2:
                        $this->user_model->debitFromFloatingWallet($internalPaymentInfo["user_id"], toRupees($paymentRecieved));
                        break;
                    
                    default:
                        break;
                }
            }
            $this->db->trans_complete();
            return [
                "success" => true,
                "data" => $internalPaymentInfo
            ];
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return [
                "success" => false,
                "error" => $e
            ];
        }
    }

    private function updatePaymentasFailed($paymentInfo){
        try {
            $paymentRef = $paymentInfo["order_id"];
            $internalPaymentInfo = $this->payment_link_model->get(["payment_ref" => $paymentRef]);
            if($internalPaymentInfo){
                $this->payment_link_model->update([
                    "status" => 3
                ], $internalPaymentInfo["id"]);
            }
            return [
                "success" =>true,
                "data" =>$internalPaymentInfo
            ];
        } catch (Exception $e) {
            return [
                "success" =>false,
                "error" =>$e
            ];
        }
    }

    public function initiateRefund($orderID, $isPartial= false, $amount=0){
        try {
            $this->load->helper('money');
            $razorPayInfo = $this->config->item('razorpay');
            $api = new Api($razorPayInfo["key"], $razorPayInfo["secret"]);
            $orderDetails = $this->ecom_order_model->getOrderDetailswithPayment($orderID);
            if($orderDetails["payment"]["status"]==2){
            $refundPostInfo = [
                'payment_id' => $orderDetails["payment"]["txn_id"],
                'speed'=> "normal"
            ];
            if($isPartial){
                $amount = toPaise($amount);
                array_push($refundPostInfo, [
                    'amount'=>$amount
                ]);
            }
                $refund = $api->refund->create($refundPostInfo);
                $this->saveRefundInfo($orderID, $orderDetails["payment"]["id"], $isPartial, $refund);
            }
            return true;
        } catch (Exception $e) {
            return false;

        }
    }

    private function saveRefundInfo($orderID, $paymentID, $isPartial, $refund){
        try {
            $refundInfo = [
                "ecom_order_id" => $orderID,
                "ecom_payment_id" => $paymentID,
                "is_partial" => $isPartial,
                "refund_ref" => $refund["id"],
                "amount" => $refund["amount"],
                "status" => 1
            ];
            $saveRefund = $this->payment_refund_model->insert($refundInfo);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function refund_update_post()
    {
        try {
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $event = $_POST["event"];
            $refundInfo = $_POST["payload"]["refund"]["entity"];
            $refundStatus = null;
            switch ($event) {
                case "refund.processed":
                    $refundStatus= 2;
                    break;
                case "refund.failed":
                    $refundStatus= 3;
                    break;
                default: 
                    break;
            }
            if($refundStatus){
                $refundRef = $refundInfo["id"];
                $internalRefundInfo = $this->payment_refund_model->get(["refund_ref" => $refundRef]);
                if($internalRefundInfo){
                    $this->payment_refund_model->update([
                        "status" => $refundStatus
                    ], $internalRefundInfo["id"]);
                }
            }
            $this->set_response(true, Null, REST_Controller::HTTP_OK, TRUE);
        } catch (Exception $e) {
            $this->set_response(NULL, Null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR, TRUE);
        }
    }

    public function refund_status_post(){
        try{
			$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
			$token_data =$this->validate_token($authorization_exp[1]);
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $razorPayInfo = $this->config->item('razorpay');
            $api = new Api($razorPayInfo["key"], $razorPayInfo["secret"]);
            $orderID = $_POST["order_id"];
            $paymentID = $_POST["payment_id"];
            $internalRefundInfo = $this->payment_refund_model->get([
                "ecom_order_id" => $orderID,
                "ecom_payment_id"=>$paymentID
            ]);
            if($internalRefundInfo){
                $refund = $api->refund->fetch($internalRefundInfo["refund_ref"]);
                $this->set_response([
                    "status" => $refund["status"],
                    "refund_info" =>$internalRefundInfo
                ], Null, REST_Controller::HTTP_OK, TRUE);
            }else{
                $this->set_response(Null, Null, REST_Controller::HTTP_NO_CONTENT, TRUE);
            }
        }catch(Exception $e){
            $this->set_response(NULL, Null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR, TRUE);
        }
    }

    public function contact_post(){
        try{
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $postRequest = array(
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'contact' => $_POST['phone'],
                'type' => 'customer',
                'reference_id' => "Account for ".$_POST['userID']
            );
            $razorPayInfo = $this->config->item('razorpay');
            $cURLConnection = curl_init('https://api.razorpay.com/v1/contacts');
            curl_setopt($cURLConnection, CURLOPT_USERPWD, $razorPayInfo['key'].":".$razorPayInfo["secret"]);
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, json_encode($postRequest));
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            ));
            $apiResponse = curl_exec($cURLConnection);
            curl_close($cURLConnection);
            
            // $apiResponse - available data from the API request
            $jsonArrayResponse = json_decode($apiResponse);
            
            print_r($jsonArrayResponse);exit;
        }catch(Exception $e){
            print_r($e);exit;
        }
    }

    public function add_account_to_contact_post(){
        try{
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $accountType = $_POST['account_type'];
            $accountID = $_POST['contact_id'];
            $accountDetails = [];
            $accountDetails['contact_id'] = $accountID;
            switch($accountType){
                case 'upi':
                    $accountDetails['account_type'] = "vpa"; 
                    $accountDetails['vpa'] = array(
                        'address'=> $_POST['upi']
                    );
                    break;
                
                case 'bank_account': 
                    $accountDetails['account_type'] = "bank_account";
                    $accountDetails['bank_account'] = array(
                        'name'=> $_POST['bank_account']['name'],
                        'ifsc'=> $_POST['bank_account']['ifsc'],
                        'account_number'=> $_POST['bank_account']['account_number']
                    );
                    break;
                default:
                    break;
            }
            $razorPayInfo = $this->config->item('razorpay');
            $cURLConnection = curl_init('https://api.razorpay.com/v1/fund_accounts');
            curl_setopt($cURLConnection, CURLOPT_USERPWD, $razorPayInfo['key'].":".$razorPayInfo["secret"]);
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, json_encode($accountDetails));
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            ));
            $apiResponse = curl_exec($cURLConnection);
            curl_close($cURLConnection);
            $jsonArrayResponse = json_decode($apiResponse);
            
            print_r($jsonArrayResponse);exit;
        }catch(Exception $ex){
            print_r($ex);exit;
        }
    }

    public function pay_out_post(){
        try{
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $accountID = $_POST['fund_account'];
            $accountDetails = [];
            $accountDetails['fund_account_id'] = $accountID;
            $accountDetails['amount'] = 100;
            $accountDetails['currency'] = "INR";
            $accountDetails['mode'] = "UPI";
            $accountDetails['purpose'] = "payout";
            $razorPayInfo = $this->config->item('razorpay');
            $accountDetails['account_number'] = $razorPayInfo['payout_account'];
            $cURLConnection = curl_init('https://api.razorpay.com/v1/payouts');
            curl_setopt($cURLConnection, CURLOPT_USERPWD, $razorPayInfo['key'].":".$razorPayInfo["secret"]);
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, json_encode($accountDetails));
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            ));
            $apiResponse = curl_exec($cURLConnection);
            curl_close($cURLConnection);
            $jsonArrayResponse = json_decode($apiResponse);
            
            print_r($jsonArrayResponse);exit;
        }catch(Exception $ex){
            print_r($ex);exit;
        }
    }

    public function direct_pay_post(){
        $razorPayInfo = $this->config->item('razorpay');
        $api = new Api($razorPayInfo["key"], $razorPayInfo["secret"]);
        $response = $api->transfer->create(array('account' => "acc_IKfuFTmOQij5Hv", 'amount' => 100, 'currency' => 'INR'));
        print_r($response); exit;
    }

    public function enterAmountValidation($enterAmount){
        $security_deposited = $this->vehicle_model->get_all()[0]['security_deposited_amount'];        
        if($enterAmount !=0 && $security_deposited >= floatval($enterAmount)){
            return 1;
        }else{       
            $this->set_response_simple(NULL, 'Something went wrong..!', REST_Controller::HTTP_OK, FALSE);
        }
    }
}