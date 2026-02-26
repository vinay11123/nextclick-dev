<?php
require('vendor/autoload.php');

use Rakit\Validation\Validator;

class Business_sub_category_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendors_sub_categories';
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

    public function mutate($vendorID, $subCategories)
    {
        try {
            $this->db->delete($this->table, array(
                "list_id" => $vendorID
            ));
            $data = [];
            foreach ($subCategories as $key => $value) {
                array_push($data, [
                    'list_id' => $vendorID,
                    'sub_category_id' => $value
                ]);
            }
            $this->db->insert_batch($this->table, $data);
            return [
                'success'=> true
            ];
        } catch (Exception $ex) {
            return [
                'success' => false,
                'error' => $ex
            ];
        }
    }
}
