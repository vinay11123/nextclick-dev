<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';
use Firebase\JWT\JWT;
require('vendor/autoload.php');
use Rakit\Validation\Validator;

class Partner extends MY_REST_Controller
{

    public $user_id = NULL;
    public $intentsArr = ["user", "delivery_partner", "vendor", "executive"];
    public $handledExceptions = ["INVALID_INTENT"];
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('partnership_applicable_document_model');
        $this->load->model('partnership_intent_model');
        $this->load->model('user_credential_model');
    }
    
    public function documents_list_get($intent){
        try{
            if(!in_array($intent, $this->intentsArr)){
                throw new Exception("INVALID_INTENT");
            }
            $documentsList = $this->partnership_applicable_document_model->getList($intent);
            if($documentsList['success']){
                $this->set_response_simple($documentsList['data'], Null, REST_Controller::HTTP_OK, TRUE);
            }else{
                throw new Exception($documentsList['error']->getMessage());
            }

        }catch(Exception $ex){
            if(in_array($ex->getMessage(), $this->handledExceptions))
                $this->set_response_simple(Null, $ex->getMessage(), REST_Controller::HTTP_BAD_REQUEST, FALSE);
            else
                $this->set_response_simple(Null, $ex->getMessage(), REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }

    public function register_post(){
        try{
            $validator = new Validator;
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $ip = $this->input->ip_address();
            $postData = $_POST;
            $postData['ip_address'] = $ip;
            if(!in_array($postData['primary_intent'], $this->intentsArr)){
                throw new Exception("INVALID_INTENT");
            }
            $validation = $validator->make($postData, [
                'first_name'                  => 'required|max:45',
                'last_name'                 => 'nullable|max:45',
                'display_name'              => 'required|max:90',
                'email'      => 'required|email',
                'mobile'                => 'required|regex:^[6-9]\d{9}$^',
                'primary_intent'                => 'required'
            ]);
            $validation->validate();
            if ($validation->fails()) {
                $errors = $validation->errors();
                $this->set_response_simple(NULL, $errors->firstOfAll(), REST_Controller::HTTP_FORBIDDEN, FALSE);
                return;
            } else {
                $this->partnership_intent_model->save($postData);
                // validation passes
                print_r($postData);exit;
                echo "Success!";
            }
            print_r($validation);exit;
            $documentsList = $this->partnership_applicable_document_model->getList($postData['intent']);
            if($documentsList['success']){
                $this->set_response_simple($documentsList['data'], Null, REST_Controller::HTTP_OK, TRUE);
            }else{
                throw new Exception($documentsList['error']->getMessage());
            }

        }catch(Exception $ex){
            if(in_array($ex->getMessage(), $this->handledExceptions))
                $this->set_response_simple(Null, $ex->getMessage(), REST_Controller::HTTP_BAD_REQUEST, FALSE);
            else
                $this->set_response_simple(Null, $ex->getMessage(), REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }

    public function credentials_put(){
        try{
            $validator = new Validator;
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $postData = $_POST;
            $validation = $validator->make($postData, [
                'user_id'                => 'required',
                'password'               => 'required|min:8',
                'intent'                 => 'required|in:create,change',
                'old_password'           => 'required_if:intent:change'
            ]);
            $validation->validate();
            if ($validation->fails()) {
                $errors = $validation->errors();
                $this->set_response_simple(NULL, $errors->firstOfAll(), REST_Controller::HTTP_FORBIDDEN, FALSE);
                return;
            } else {
                $result = $this->user_credential_model->updateCredentials($postData);
                // validation passes
                print_r($postData);exit;
                if($result['success']){
                    $this->set_response_simple(Null, Null, REST_Controller::HTTP_OK, TRUE);
                }else{
                    throw new Exception($result['error']->getMessage());
                }
            }

        }catch(Exception $ex){
            if(in_array($ex->getMessage(), $this->handledExceptions))
                $this->set_response_simple(Null, $ex->getMessage(), REST_Controller::HTTP_BAD_REQUEST, FALSE);
            else
                $this->set_response_simple(Null, $ex->getMessage(), REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
        }
    }
}