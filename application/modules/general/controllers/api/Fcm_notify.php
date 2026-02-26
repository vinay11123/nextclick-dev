<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Fcm_notify extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('fcm');
        
        $this->load->model('user_model');
        $this->load->model('app_details_model');
        $this->load->model('fcm_model');
        $this->load->model('notifications_model');
    }
    
    public function grant_fcm_permission_post(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
			
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $id= null;
        $app_details = $this->app_details_model->where('app_id', $this->input->get_request_header('APP_ID'))->get();
        if(! empty($app_details)){
            $is_exist = $this->fcm_model->where(['app_details_id' => $app_details['id'], 'token' => $this->input->post('token')])->get();
            if(empty($is_exist)){
                $id = $this->fcm_model->insert([
                    'app_details_id' => $app_details['id'],
                    'user_id' => $token_data->id,
                    'token' => $this->input->post('token'),
                    'status' => 1
                ]);
            }else {
                $this->fcm_model->update([
                    'id' => $is_exist['id'],
                    'status' => 1
                ], 'id');
            }
            $this->set_response_simple($id, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }else{
            $this->set_response_simple("Sorry, Please check APP_ID", 'Sorry, Please check APP_ID', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
        }
    }
    
    public function remove_fcm_permission_post(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
			
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $app_details = $this->app_details_model->where('app_id', $this->input->get_request_header('APP_ID'))->get();
        if(! empty($app_details)){
            $id = $this->fcm_model->where(['app_details_id' => $app_details['id'], 'user_id' => $token_data->id, 'token' => $this->input->post('token')])->delete();
            $this->set_response_simple($id, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }else{
            $this->set_response_simple("Sorry, Please check APP_ID", 'Sorry, Please check APP_ID', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
        }
    }
    
    public function notifications_get11($type = 'r', $target = NULL){
        if($type == 'r'){
			$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
			$token_data =$this->validate_token($authorization_exp[1]);
			
            $app_details = $this->app_details_model->where('app_id', $this->input->get_request_header('APP_ID'))->get();
			//echo "SELECT n.id, n.ecom_order_id,n.pickup_order_id,n.ticket_id, n.title, n.message, nt.notification_code, nt.notification_name, n.status FROM notifications as n join notification_types as nt on nt.id = n.notification_type_id where n.notified_user_id = ".$token_data->id." and nt.app_details_id =".$app_details['id']." order by id desc";
            $notifications = $this->db->query("SELECT n.id, n.ecom_order_id,n.pickup_order_id,n.ticket_id, n.title, n.message, nt.notification_code, nt.notification_name, n.status FROM notifications as n join notification_types as nt on nt.id = n.notification_type_id where n.notified_user_id = ".$token_data->id." and nt.app_details_id =".$app_details['id']." order by id desc")->result_array();
            $this->set_response_simple($notifications ? $notifications : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }elseif ($type = 'in_active'){
            $notifications =  $this->notifications_model->update([
                'id' => $target,
                'status' => 2
            ], 'id');
            $this->set_response_simple($notifications, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
    
      public function notifications_get($type = 'r', $target = NULL)
      {
        if ($type == 'r') {
    
            $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
            $token_data = $this->validate_token($authorization_exp[1]);   // ✅ semicolon added
    
            $app_details = $this->app_details_model
                                ->where('app_id', $this->input->get_request_header('APP_ID'))
                                ->get();
    
            $notifications = $this->db->query("
                SELECT n.id, n.ecom_order_id, n.pickup_order_id, n.ticket_id, 
                       n.title, n.message, nt.notification_code, nt.notification_name, n.status 
                FROM notifications as n 
                JOIN notification_types as nt ON nt.id = n.notification_type_id 
                WHERE n.notified_user_id = ".$token_data->id." 
                  AND nt.app_details_id = ".$app_details['id']." 
                ORDER BY id DESC
            ")->result_array();
    
            $this->set_response_simple(
                $notifications ? $notifications : NULL,
                'success..!',
                REST_Controller::HTTP_OK,
                TRUE
            );
    
        } elseif ($type == 'in_active') {   // ✅ comparison fixed
    
            $notifications = $this->notifications_model->update([
                'id'     => $target,
                'status' => 2
            ], 'id');
    
            $this->set_response_simple(
                $notifications,
                'success..!',
                REST_Controller::HTTP_OK,
                TRUE
            );
        }
    }

    
    /**
     * Send to a single device
     */
    public function sendNotification_get()
    {
        $token = 'fotM-t1zS4a2iNy70CFyyO:APA91bFsIgcITg2BaVIHBl_k4z34oRGVYMn7mR7B9PpLvzQ02MjKtPfdoz56nhAx0EuGyvoJWv1XKk_AN3cyIZGebzLxbZ-sJypG-Ef90e-6MxSsKGsbiqEZL6bEGQTQzojTE1aFC-ZD'; // push token
        $message = "Test notification message by mehar";
        
        $this->fcm->setTitle('Test FCM Notification');
        $this->fcm->setMessage($message);
        
        /**
         * set to true if the notificaton is used to invoke a function
         * in the background
         */
        $this->fcm->setIsBackground(false);
        
        /**
         * payload is userd to send additional data in the notificationkjsdfk
         * This is purticularly useful for invoking functions in background
         * -----------------------------------------------------------------
         * set payload as null if no custom data is passing in the notification
         */
        $payload = array('notification' => 'mehar trinadh', '');
        $this->fcm->setPayload($payload);
        
        /**
         * Send images in the notification
         */
        $this->fcm->setImage('https://firebase.google.com/_static/9f55fd91be/images/firebase/lockup.png');
        
        /**
         * Get the compiled notification data as an array
         */
        $json = $this->fcm->getPush();
        
        $p = json_decode($this->fcm->send($token, $json));
        
        print_r($p->success);
    }
    
    /**
     * Send to multiple devices
     */
    public function sendToMultiple_get()
    {
        $token = array(
            'fotM-t1zS4a2iNy70CFyyO:APA91bFsIgcITg2BaVIHBl_k4z34oRGVYMn7mR7B9PpLvzQ02MjKtPfdoz56nhAx0EuGyvoJWv1XKk_AN3cyIZGebzLxbZ-sJypG-Ef90e-6MxSsKGsbiqEZL6bEGQTQzojTE1aFC-ZD',
            'fotM-t1zS4a2iNy70CFyyO:APA91bFsIgcITg2BaVIHBl_k4z34oRGVYMn7mR7B9PpLvzQ02MjKtPfdoz56nhAx0EuGyvoJWv1XKk_AN3cyIZGebzLxbZ-sJypG-Ef90e-6MxSsKGsbiqEZL6bEGQTQzojTE1aFC-ZD2'
        );
        $message = "Test notification message";
        
        
        $this->fcm->setTitle('Test FCM Notification');
        $this->fcm->setMessage($message);
        $this->fcm->setIsBackground(false);
        // set payload as null
        $payload = array('notification' => '');
        $this->fcm->setPayload($payload);
        $this->fcm->setImage('https://firebase.google.com/_static/9f55fd91be/images/firebase/lockup.png');
        $json = $this->fcm->getPush();
        
        /**
         * Send to multiple
         *
         * @param array  $token     array of firebase registration ids (push tokens)
         * @param array  $json      return data from getPush() method
         */
        $result = json_decode($this->fcm->sendMultiple($token, $json));
        print_r($result);
    }

}

