<?php
require('vendor/autoload.php');

use Rakit\Validation\Validator;

class Package_setting_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'package_settings';
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
        $this->has_one['master_setting'] = array(
            'foreign_model' => 'Master_package_setting_model',
            'local_key' => 'setting_key',
            'foreign_key' => 'setting_key',
            'get_relate' => FALSE
        );
        $this->has_many['packages'] = array(
            'foreign_model' => 'Package_model',
            'foreign_table' => 'packages',
            'local_key' => 'package_id',
            'foreign_key' => 'id',
            'get_relate' => FALSE
        );
    }

    public function saveDefalutValues($packageID)
    {
        $this->load->model('master_package_setting_model');
        $featuresList = $this->master_package_setting_model->where([
            'status' => 1
        ])->order_by('description', 'ASC')->get_all();
        foreach ($featuresList as $key => $feature) {
            $this->insert([
                'package_id' => $packageID,
                "setting_key" => $feature['setting_key'],
                'status' => 0
            ]);
        }
    }
}
