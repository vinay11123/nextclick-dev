<?php

class Delivery_job_rejection_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'delivery_job_rejections';
        $this->primary_key = 'id';
        
       $this->_config();
        $this->_form();
       $this->_relations();
    }
    
    public function _config() {
    }
    
    public function _relations(){
        $this->has_one['delivery_job'] = array('Delivery_job_model', 'id', 'job_id');
        $this->has_one['delivery_boy'] = array('User_model', 'id', 'rejected_by');
    }

    private function _form(){}

    public function saveRejection($jobID, $currentOrderStatus, $rejectedBy, $rejectReasonID, $rejectReason){
        try{
            $saveInfo = [
                "job_id" =>$jobID,
                "current_order_status" =>$currentOrderStatus,
                "rejected_by" =>$rejectedBy,
                "rejected_reason_id" =>$rejectReasonID,
                "rejection_reason" =>$rejectReason,
                'status' => ($rejectReasonID == 4)? 0 : 1
            ];
            $isRejectionExisted = $this->where([
                "job_id" =>$jobID,
                "rejected_reason_id" =>$rejectReasonID
            ])->get();
            
            if(! $isRejectionExisted){
                $lastInsertedID = $this->insert($saveInfo);
                return [
                    "success"=> true,
                    "record_id"=>$lastInsertedID
                ];
            }else {
                return [
                    "success"=> false,
                    "error"=>'Not inserted!'
                ];
            }
                
        }catch(Exception $ex){
            return [
                "success"=> false,
                "error"=>$ex
            ];
        }
    }
    
    public function accept($rejection_id = NULL, $from = 'admin'){
        try {
            $rejection_request = $this->with_delivery_job('fields: id, ecom_order_id')->where('id', $rejection_id)->get();
            if($rejection_request){
                $is_rejection_updated = $this->update([
                    'id' => $rejection_request['id'],
                    'status' => ($from == 'admin')? 1 : 4
                ], 'id');
                if($is_rejection_updated){
                    $this->load->model('delivery_job_model');
                    $this->load->model('ecom_order_model');
                    $job = $this->delivery_job_model
                    ->with_order('fields: id, track_id')
                    ->where('id', $rejection_request['job_id'])
                    ->get();
                    $this->delivery_job_model->update([
                        'id' => $rejection_request['job_id'],
                        'status' => 601
                    ], 'id');
                    
                    $this->ecom_order_model->update([
                        'id' => $job['order']['id'],
                        'order_return_otp' => rand(99999, 999999)
                    ], 'id');
                    
                    return [
                        "success"=> TRUE,
                        "job"=>$job,
                        'rejection_request' => $rejection_request
                    ];
                }else {
                    return [
                        "success"=> FALSE,
                        "error"=> 'Something went wrong'
                    ];
                }
            }else {
                return [
                    "success"=> FALSE,
                    "error"=> 'Invalid rejection id'
                ];
            }
            
        } catch (Exception $e) {
            return [
                "success"=> FALSE,
                "error"=> $e
            ];
        }
    }
}

