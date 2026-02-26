<?php
require('vendor/autoload.php');

use Rakit\Validation\Validator;

class Business_address_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendor_address';
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
                'lat'                       => 'required',
                'lng'                       => 'required',
                'line1'                      => 'required|max:500',
                'state'                      => 'required',
                'district'                   => 'required',
                'constituency'               => 'required',
                'zip_code'                   => 'required', 
                'location'                   => 'required'
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
                'error' => $ex
            ];
        }
    }

    public function mutate($businessInfoID, $postData)
    {
        try {
            $businessAddress = $this->where(['list_id' => $businessInfoID])->get();
            $businessAddressID = null;
            $valid = $this->customValidate($postData);
            if ($valid['success']) {
                if ($businessAddress && $businessAddress['id']) { //Update
                    $businessAddressID = $businessAddress['id'];
                    $this->update($postData, $businessAddress['id']);
                } else { //Insert
                    $postData['list_id'] = $businessInfoID;
                    $businessAddressID = $this->insert($postData);
                }
                return [
                    'success' => true,
                    'list_id' => $businessAddressID
                ];
            } else {
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

    public function mutateAddress($businessInfoID, $address)
    {
        try {
            $businessAddress = $this->where(['list_id' => $businessInfoID])->get();
            if ($businessAddress && $businessAddress['id']) { //Update
                $businessAddressID = $businessAddress['id'];
                $this->update([
                    'line1' => $address
                ], $businessAddress['id']);
            }
            return [
                'success' => true,
                'list_id' => $businessAddressID
            ];
        } catch (Exception $ex) {
            return [
                'success' => false,
                'error' => $ex
            ];
        }
    }

    public function mutateAddressAndConstituency($businessInfoID, $address, $state, $district, $constituency)
    {
        try {
            $businessAddress = $this->where(['list_id' => $businessInfoID])->get();
            if ($businessAddress && $businessAddress['id']) { //Update
                $businessAddressID = $businessAddress['id'];
                $this->update([
                    'line1' => $address,
                    'state' => $state,
                    'district' => $district,
                    'constituency' => $constituency
                ], $businessAddress['id']);
            }
            return [
                'success' => true,
                'list_id' => $businessAddressID
            ];
        } catch (Exception $ex) {
            return [
                'success' => false,
                'error' => $ex
            ];
        }
    }
}
