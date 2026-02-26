<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';
use Firebase\JWT\JWT;

class Support extends MY_REST_Controller
{
    public $user_id = NULL;
    public function __construct()
    {
        parent::__construct();
         $this->load->model('support_model');
         $this->load->model('customer_support_model');
         $this->load->model('notification_type_model');
         $this->load->model('request_model');
         $this->load->model('app_details_model');
    }

	/**
     * To get faqs based on app
     *
     * @author sandhip
     * @param string $app_id
     */
    public function customer_support_post()
    {		
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
		$_POST = json_decode(file_get_contents("php://input"), TRUE);
		$this->form_validation->set_rules($this->customer_support_model->rules['create_rules']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
			}
			else {
				$app_details_id = $this->input->post('app_details_id');
				$request_type = $this->input->post('request_type');
				$title = $this->input->post('title');
				$description = $this->input->post('description');
				$severity = $this->input->post('severity');

				if($severity == 0)
					$severity_text = "Low";
				if($severity == 1)
					$severity_text = "Medium";
				if($severity == 2)
					$severity_text = "High";
				if($severity == 3)
					$severity_text = "Critical";
				
					$form_data = [
						'app_details_id' => $app_details_id,
						'request_type' => $request_type,
						'severity' => $severity,
						'severity_text'=>$severity_text,
						'title' => $title,
						'description' => $description,
						'status' => 1,
						'status_text' => "Open",
						'created_user_id' => $token_data->id,

					];
					
					
				 $inserted = $this->customer_support_model->insert($form_data);
				 
				    $insert_id = $this->db->insert_id();

				 
				 if($app_details_id == 2) {				 
					$this->send_notification($token_data->id, VENDOR_APP_CODE, "Support Alert", "Your request is saved and our customer care/Admin team will get as soon as possible.", [
                                'notification_type' => $this->notification_type_model->where([
                                    'app_details_id' => VENDOR_APP_CODE,
                                    'notification_code' => 'CS'
                                ])->get(),
								'ticket_id' => $insert_id
                            ]);
				 }
				 if ($app_details_id == 1) {
					 $this->send_notification($token_data->id, USER_APP_CODE, "Support Alert", "Your request is saved and our customer care/Admin team will get as soon as possible.", [
                                'notification_type' => $this->notification_type_model->where([
                                    'app_details_id' => USER_APP_CODE,
                                    'notification_code' => 'CS'
                                ])->get(),
								'ticket_id' => $insert_id
                            ]);
				 }
				 if ($app_details_id == 4) {
					 $this->send_notification($token_data->id, DELIVERY_APP_CODE, "Support Alert", "Your request is saved and our customer care/Admin team will get as soon as possible.", [
                                'notification_type' => $this->notification_type_model->where([
                                    'app_details_id' => DELIVERY_APP_CODE,
                                    'notification_code' => 'CS'
                                ])->get(),
								'ticket_id' => $insert_id
                            ]);
					 
				 }
					 if (! empty($inserted)){
							$this->set_response_simple(($inserted == FALSE) ? NULL : $is_inserted, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
					} else {
							$this->set_response_simple(($inserted == FALSE) ? NULL : $is_inserted, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
					}
			}
    }

	/**
     * To get faqs based on app
     *
     * @author sandhip
     * @param string $app_id
     */
    public function customer_support_list_get()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
		 $app_details_id = $this->input->get('app_details_id');
				
				 $data = $this->customer_support_model->where('app_details_id', $app_details_id)->where('created_user_id', $token_data->id)->order_by('id', 'DESC')->get_all();
			if($data) {
				 $this->set_response_simple($data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
			}
			else {
				$this->set_response_simple([], 'No Customer Care Support list..!', REST_Controller::HTTP_OK, FALSE);

			}

    }
	
	/**
     * To get support ticket in detail
     *
     * @author sandhip
     * @param string $support_id
     */
    public function customer_support_detail_get()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
		 $support_id = $this->input->get('support_id');
				
				 $data = $this->customer_support_model->with_assigned('fields: first_name,last_name')->where('id', $support_id)->get_all();
			if($data) {
				 $this->set_response_simple($data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
			}
			else {
				$this->set_response_simple([], 'No Detail of support is not fpund..!', REST_Controller::HTTP_OK, FALSE);

			}

    }
	
    public function support_queries_post($type = 'r', $target = 0)
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
         $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->support_model->user_id = $token_data->id;

        if ($type == 'c') {
            $this->form_validation->set_rules($this->support_model->rules['create_rules']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                  
                   $appid = $this->input->get_request_header('APP_ID');
                   $app_id = base64_decode(base64_decode($appid));
                  $token =  generate_serial_no('AZ', 3, rand(999, 9999));
            
                 $is_inserted = $this->support_model->insert([
                    'token_no' => $token,//$this->input->post('token_no'),
                    'app_details_id' => $app_id ,//$this->input->post('app_details_id'),
                    'request_type_id'=> $this->input->post('request_type_id'),
                    'mobile' => $this->input->post('mobile'),
                    'email' => $this->input->post('email'),
                    'subject' => $this->input->post('subject'),
                    'message' => $this->input->post('message'),
                    'created_user_id' => $token_data->id,
                    'query_owner_id' => 1 
                    ]);

                    if ($is_inserted) {
                    $this->set_response_simple(($is_inserted == FALSE) ? NULL : $is_inserted, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($is_inserted == FALSE) ? NULL : $is_inserted, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }

            } 
             
        
           
        }  elseif ($type == 'd') {
            
                $supportdata = $this->support_model->get($target);
                if (! empty($supportdata) && $supportdata['created_user_id'] == $token_data->id) {
                    $this->support_model->delete([
                        'id' => $target
                    ]);
                    $this->set_response_simple(NULL, 'data has deleted..!', REST_Controller::HTTP_OK, TRUE);
                } else {
                    $this->set_response_simple(NULL, 'No privilege to  delete..!', REST_Controller::HTTP_OK, FALSE);
                }
             
        }   elseif ($type == 'u') {
             $_POST = json_decode(file_get_contents("php://input"), TRUE);        
        $this->form_validation->set_rules($this->support_model->rules['update_rules']);
        
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                
                   $appid = $this->input->get_request_header('APP_ID');
                   $app_id = base64_decode(base64_decode($appid));
                   $id = $this->input->post('id');
                   $query = "SELECT token_no FROM support WHERE id = '$id'";

                $this->data  = $this->db->query($query)->result_array();
        
                $is_updated = $this->support_model->update([
                    'id' => $this->input->post('id'),
                   'token_no' => $this->data[0]['token_no'],//$this->input->post('token_no'),
                    'app_details_id' => $app_id,//$this->input->post('app_details_id'),
                    'request_type_id'=> $this->input->post('request_type_id'),
                    'mobile' => $this->input->post('mobile'),
                    'email' => $this->input->post('email'),
                    'subject' => $this->input->post('subject'),
                    'message' => $this->input->post('message'),
                    'created_user_id' => $token_data->id,
                    'query_owner_id' => 1
                ], 'id');

                if ($is_updated) {
                    $this->set_response_simple(($is_updated == FALSE) ? NULL : $is_updated, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
                } else {
                    $this->set_response_simple(($is_updated == FALSE) ? NULL : $is_updated, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        }  elseif ($type == 'r') {  
            $query = "SELECT s.*,ad.app_id,ad.app_name, rt.title FROM support AS s JOIN app_details AS ad ON s.app_details_id  = ad.id JOIN request_type AS rt ON s.request_type_id  = rt.id where s.created_user_id = '$token_data->id'";

                $this->data  = $this->db->query($query)->result_array();

                $this->set_response_simple($this->data , 'Support data list', REST_Controller::HTTP_OK, TRUE); 
        }

    }

}