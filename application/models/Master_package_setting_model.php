<?php
require('vendor/autoload.php');

use Rakit\Validation\Validator;

class Master_package_setting_model extends MY_Model {
    public function __construct()
    {
        parent::__construct();
        $this->table = 'master_package_settings';
        $this->primary_key = 'id';

        $this->_config();
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

    public function _config()
    {
        $this->timestamps = TRUE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }

    public function getAll() {
        return $this->order_by("id", "desc")->get_all();
    }

    public function getDataById($id) {
        return $this->where([
            'id' => $id
        ])->get();
    }

    public function changeStatus($id) {
        $table=$this->getDataById($id);
             if($table['status']==0)
             {
                $this->update(array('status' => '1'), $id);
                return "Activated";
             }else{
                $this->update(array('status' => '0'), $id);
                return "Deactivated";
             }
    }

    private function _relations()
    {
        $this->has_many['package_setting'] = array(
            'foreign_model' => 'Package_setting_model',
            'foreign_table' => 'package_settings',
            'local_key' => 'setting_key',
            'foreign_key' => 'setting_key',
            'get_relate' => FALSE
        );
    }

}
