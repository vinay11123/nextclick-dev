<?php
require('vendor/autoload.php');

use Rakit\Validation\Validator;

class Executive_biometric_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'executive_biometrics';
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
                'aadhar'                       => 'required|min:12|max:12'
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
            $biometricInfo = $this->where(['user_id' => $userID])->get();
            $biometricInfoID = null;
            $valid = $this->customValidate($postData);
            if($valid['success']){
                if ($biometricInfo && $biometricInfo['id']) { //Update
                    $biometricInfoID = $biometricInfo['id'];
                    $this->update($postData, $biometricInfo['id']);
                } else { //Insert
                    $postData['user_id'] = $userID;
                    $biometricInfoID = $this->insert($postData);
                }
                return [
                    'success' => true,
                    'biometric_info_id' => $biometricInfoID
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
