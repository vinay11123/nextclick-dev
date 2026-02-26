<?php

class Vendor_bank_details_model extends MY_Model
{
    public $rules;
    public $user_id =1; 
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendor_bank_details';
        $this->primary_key = 'id';
        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';
        
       $this->_config();
       $this->_form();
       $this->_relations();
    }
    
    protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id()? $this->ion_auth->get_user_id(): $this->user_id; //add user_id
        return $data;
    }
    
    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id()? $this->ion_auth->get_user_id(): $this->user_id; //add user_id
        return $data;
    }
    public function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
        
    }
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'bank_name',
                'lable' => 'Bank Name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'bank_branch',
                'lable' => 'Bank Branch',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'ifsc',
                'lable' => 'IFSC',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'ac_holder_name',
                'lable' => 'Account Holder Name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'ac_number',
                'lable' => 'Account Number',
                'rules' => 'trim|required'
            ),
        );
    }

    public function checkUpdateExternalAccount($userID, $listingID, $externalID){
        try{
            $externalFundAccount = $this->createFundAccount($userID, $listingID, $externalID);
            $this->update([
                'external_id' => $externalFundAccount
            ], [
                'list_id' => $listingID,
                'status' => 1
            ]);
        }catch(Exception $ex){
            print_r($ex);exit;
        }
    }

    public function createFundAccount($userID, $listingID, $externalID){
        try{
            $vedorBankDetails = $this->fields('bank_name, ifsc, ac_number')->where([
                'list_id' => $listingID,
                'status'=> 1
            ])->get();
            $accountType = 'bank_account';
            $accountDetails = [];
            $accountDetails['contact_id'] = $externalID;
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
                        'name'=> $vedorBankDetails['bank_name'],
                        'ifsc'=> $vedorBankDetails['ifsc'],
                        'account_number'=> $vedorBankDetails['ac_number']
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
            return $jsonArrayResponse->id;
        }catch(Exception $ex){
            print_r($ex);exit;
        }
    }
}

