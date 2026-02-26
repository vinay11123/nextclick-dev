<?php
require('vendor/autoload.php');

use Rakit\Validation\Validator;

class Delivery_boy_address_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'delivery_boy_address';
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
                'state'                      => 'required',
                'district'                   => 'required',
                'constituency'               => 'required',
                'zip_code'                   => 'required',
                'location'                   => 'nullable'
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
            $deliveryBoyAddress = $this->where(['user_id' => $userID])->get();
            $deliveryBoyAddressID = null;
            $valid = $this->customValidate($postData);
            if($valid['success']){
                if ($deliveryBoyAddress && $deliveryBoyAddress['id']) { //Update
                    $deliveryBoyAddressID = $deliveryBoyAddress['id'];
                    $this->update($postData, $deliveryBoyAddress['id']);
                } else { //Insert
                    $postData['user_id'] = $userID;
                    $deliveryBoyAddressID = $this->insert($postData);
                }
                return [
                    'success' => true,
                    'delivery_boy_address_id' => $deliveryBoyAddressID
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
