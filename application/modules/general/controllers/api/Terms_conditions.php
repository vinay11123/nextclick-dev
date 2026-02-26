<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Terms_conditions extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('app_details_model');
        $this->load->model('termsconditions_model');
        $this->load->model('user_accepted_tc_model');
    }

    /**
     * @desc get api to retrieve tems and conditions data
     * @author Tejaswini
     * @date 17/06/2021
     *  */

    public function termsconditions_get(){			 
        $tc_id = $this->input->get('tc_id');
        if(empty($tc_id)){
            $app_details = $this->app_details_model->where('app_id', $this->input->get_request_header('APP_ID'))->get();
            $list_of_tcs = $this->termsconditions_model->fields('id, app_details_id, page_id, title, desc, status')->where(['app_details_id' => $app_details['id'], 'page_id' => $this->input->get('page_id')])->get_all();
            $this->set_response_simple($list_of_tcs ? $list_of_tcs : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }else {
            $tc = $this->termsconditions_model->where('tc_id', $tc_id)->get();
            $this->set_response_simple($tc ? $tc : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }
            
    }
    
    /**
     * @desc To validate user
     * 
     */
    public function validate_user_post(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
      $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $is_accepted = $this->user_accepted_tc_model->where([
            'app_details_id' => base64_decode(base64_decode($this->input->post('APP_ID'))),
            'page_id' => $this->input->post('page_id'),
            'tc_id' => $this->input->post('tc_id'),
            'created_user_id' => $token_data->id,
        ])->get();
       //echo $this->db->last_query();
        if($is_accepted)
            $this->set_response_simple(NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        else
            $this->set_response_simple(NULL, 'Failed..!', REST_Controller::HTTP_OK, FALSE);
    }
    
    /**
     * @desc To store accepted terms and conditions
     * 
     */
    public function accept_tc_post(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->user_accepted_tc_model->user_id = $token_data->id;
        $is_inserted = $this->user_accepted_tc_model->insert([
            'tc_id' => $this->input->post('tc_id'),
            'page_id' => $this->input->post('page_id'),
            'app_details_id' => base64_decode(base64_decode($this->input->post('APP_ID'))),
        ]);
        if($is_inserted)
            $this->set_response_simple(NULL, 'success..!', REST_Controller::HTTP_CREATED, TRUE);
        else 
            $this->set_response_simple(NULL, 'Failed..!', REST_Controller::HTTP_OK, FALSE);
    }
    

}
