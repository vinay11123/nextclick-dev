<?php
require('vendor/autoload.php');

use Rakit\Validation\Validator;

class Business_info_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendors_list';
        $this->primary_key = 'id';

        $this->_config();
        $this->_relations();
    }
    private function _config()
    {
        $this->timestamps = TRUE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }

    private function _relations()
    {
        // $this->has_one['document'] = array(
        //     'foreign_model' => 'Document_type_model',
        //     'local_key' => 'doc_type_id',
        //     'foreign_key' => 'id'
        // );
    }

    public function customValidate($postData)
    {
        try {
            $validator = new Validator;
            $validation = $validator->make($postData, [
                'business_name'              => 'required|max:100',
                'category_id'                => 'required',
                'owner_name'                 => 'required|max:90',
                'fssai_number'               => 'nullable|max:45',
                'gst_number'               => 'nullable|max:45',
                'labour_certificate_number'  => 'nullable|max:45',
                'secondary_contact'          => 'nullable',
                'whats_app_no'               => 'nullable',
                'sub_categories'             => 'required|array',
                'availability'               => 'nullable',
                'executive_user_id'               => 'nullable'
            ]);
            $validation->validate();
            if ($validation->fails()) {
                $errors = $validation->errors();
                return [
                    'success' => false,
                    'error' => $errors->firstOfAll()
                ];
            } else {
                return [
                    'success' => true
                ];
            }
        } catch (Exception $ex) {
            return [
                'success' => true,
                'error'=> $ex
            ];
        }
    }

    public function mutate($userID, $postData)
    {
        try {
            $this->load->model('business_sub_category_model');
            $businessInfo = $this->where(['vendor_user_id' => $userID])->get();
            $businessInfoID = null;
            $valid = $this->customValidate($postData);
            $subCategories = $postData['sub_categories'];
            unset($postData['sub_categories']);
            if($valid['success']){
                if ($businessInfo && $businessInfo['id']) { //Update
                    $businessInfoID = $businessInfo['id'];
                    $this->update($postData, $businessInfo['id']);
                } else { //Insert
                    $postData['vendor_user_id'] = $userID;
                    $businessInfoID = $this->insert($postData);
                }
                $this->business_sub_category_model->mutate($businessInfoID, $subCategories);
                return [
                    'success' => true,
                    'list_id' => $businessInfoID
                ];
            }else{
                return [
                    'success' => false,
                    'error' => $valid['error']
                ];
            }
        } catch (Exception $ex) {
            return [
                'success' => false,
                'error' => $ex
            ];
        }
    }
}
