<?php

class Delivery_insentive_config_model extends MY_Model {

    public function __construct()
    {
        parent::__construct();
        $this->table = 'delivery_insentive_config';
        $this->primary_key = 'id';
        
        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';
        
       $this->_config();
    //     $this->_form();
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

    private function _config()
    {
        $this->timestamps = TRUE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }

    private function _relations()
    {
        $this->has_one['state_object'] = array(
            'foreign_model' => 'State_model',
            'local_key' => 'state',
            'foreign_key' => 'id',
            'get_relate' => FALSE
        );

        $this->has_one['district_object'] = array(
            'foreign_model' => 'District_model',
            'local_key' => 'district',
            'foreign_key' => 'id',
            'get_relate' => FALSE
        );

        $this->has_one['constituency_object'] = array(
            'foreign_model' => 'Constituency_model',
            'local_key' => 'constituency',
            'foreign_key' => 'id',
            'get_relate' => FALSE
        );
        $this->has_many['shift_config'] = array(
            'foreign_model' => 'Delivery_insentive_shift_config_model',
            'local_key' => 'id',
            'foreign_key' => 'insentive_id',
            'get_relate' => FALSE
        );
        $this->has_many['rating_config'] = array(
            'foreign_model' => 'Delivery_ratings_insentive_config_model',
            'local_key' => 'id',
            'foreign_key' => 'insentive_id',
            'get_relate' => FALSE
        );
    }

    public function getAll() {
        return $this->with('state_object')->with('district_object')->with('constituency_object')->get_all();
    }

    public function insertData($data) {
        $data['status'] = 1;
        $this->insert($data);
        return $this->db->insert_id();
    }

    public function getDataById($id) {
        $detail = $this->where($id)->with('shift_config')->with('rating_config')->get();
        $shiftConfig = [];
        if($detail['shift_config']){
            foreach($detail['shift_config'] as $shiftConf){
                $shiftConfig[$shiftConf['shift_id']] = $shiftConf;
            }
            $detail['shift_config'] = $shiftConfig;
        }
        $ratingConfig = [];
        if($detail['rating_config']){
            foreach($detail['rating_config'] as $ratingConf){
                $ratingConfig[(int) $ratingConf['rating']] = $ratingConf;
            }
            $detail['rating_config'] = $ratingConfig;
        }
        return $detail;
    }

    public function getFullDetailById($id) {
        return $this->where($id)
        ->with('state_object')
        ->with('district_object')
        ->with('constituency_object')
        ->with('shift_configs')
        ->get();
    }

    public function updateData($id,$data) {
        $this->update($data, $id);
        return true;
    }

    public function changeStatus($id) {
        $detail=$this->getDataById($id);
             if($detail['status']==0)
             {
                $this->update(array('status' => '1'), $id);
                return "Activated";
             }else{
                $this->update(array('status' => '0'), $id);
                return "Deactivated";
             }
    }

    public function getInsentiveConfig($deliveryBoyAddress){
        $config = $this->where([
            'constituency' =>$deliveryBoyAddress['constituency']
        ])->get();
        if(!$config){
            $config = $this->where([
                'district' =>$deliveryBoyAddress['district'],
                'constituency'=>Null,
            ])->get();
        }if(!$config){
            $config = $this->where([
                'state' => $deliveryBoyAddress['state'],
                'district' =>Null,
                'constituency'=>Null,
            ])->with('shift_config')->get();
        }
        return $config;
    }

}