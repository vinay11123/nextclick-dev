<?php
require('vendor/autoload.php');

use Rakit\Validation\Validator;

class Executive_address_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'executive_address';
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
                'lat'                       => 'nullable',
                'lng'                       => 'nullable',
                'line1'                      => 'nullable|max:500',
                'state'                      => 'nullable',
                'district'                   => 'nullable',
                'constituency'               => 'nullable',
                'zip_code'                   => 'nullable',
                'location'                   => 'nullable',
                'executive_type_id'          => 'nullable'
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
            $executiveAddress = $this->where(['user_id' => $userID])->get();
            $executiveAddressID = null;
            $valid = $this->customValidate($postData);
            if($valid['success']){
                if ($executiveAddress && $executiveAddress['id']) { //Update
                    $executiveAddressID = $executiveAddress['id'];
                    $this->update($postData, $executiveAddress['id']);
                } else { //Insert
                    $postData['user_id'] = $userID;
                    $executiveAddressID = $this->insert($postData);
                }
                return [
                    'success' => true,
                    'executive_address_id' => $executiveAddressID
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
