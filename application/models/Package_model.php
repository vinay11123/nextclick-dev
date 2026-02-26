<?php

class Package_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'packages';
        $this->primary_key = 'id';
        
        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';
        $this->load->model('manualpayment_model');

       $this->_config();
       $this->_form();
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
    public function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
        $this->has_one['services'] = array('Service_model', 'id', 'service_id');
        $this->has_many['features'] = array('Package_setting_model', 'package_id', 'id');
    }
    
    public function _form(){
        $this->rules= array(
            array(
		        'label' => 'Title',
		        'field' => 'title',
		        'rules' => 'trim|required'
		    ),
		   
		);
    }

    public function getUpgradablePackages($existingPackageID, $completedDays, $userId){

		$existingPackage = $this->where([
			'id'=> $existingPackageID
		])->get();
        $amount = $this->getPackageUtization($existingPackageID, $completedDays);
		$upgradablePackages = [];
		if($existingPackage){
			$this->where('service_id=', $existingPackage['service_id']);
			$upgradablePackages = $this->where('price>', $existingPackage['price'])->order_by('price')->get_all();
		}
		
		$check_pending_payment = $this->manualpayment_model->where(['payment_intent' => 'Subscription', 'created_user_id' => $userId,'status' =>1 ])->get_all();
		if($upgradablePackages) {
			foreach($upgradablePackages as $key=>$package){
				
				$upgradablePackages[$key]['package_features'] = $this->package_model->vendorPackageFeatures($existingPackage['service_id'], $existingPackageID);
				$upgradablePackages[$key]['pending_payment_status'] = $check_pending_payment ? true : false;
				$upgradablePackages[$key]['image'] = base_url() . 'uploads/subscriptions_image/subscriptions_' . $upgradablePackages[$key]['id'] . '.jpg';
				$upgradablePackages[$key]['differential'] = number_format($package['price'] - $amount, 2, '.', '');
				$upgradablePackages[$key]['differential'] = floatval($upgradablePackages[$key]['differential']);
			}
		}
		
        return $upgradablePackages;
	}

    public function getPackageUtization($existingPackageID, $completedDays){
        $existingPackage = $this->where([
			'id'=> $existingPackageID
		])->get();
        $dayPrice = 0;
        if($existingPackage['price']){
            $dayPrice = (float) number_format($existingPackage['price'] / $existingPackage['days'], 2);
        }
        $consumption = $completedDays * $dayPrice;
        $amount = $existingPackage['price'] - $consumption;
        return $amount;
    }

    public function packageFeatures($service_id, $packageID=null){
        $this->load->model(array(
            'master_package_setting_model',
            'package_setting_model'
        ));
        $pacage_table = $this->table;

        $package_setting_table = '`' . $this->package_setting_model->table . '`';
        $master_package_setting_table = '`' . $this->master_package_setting_model->table . '`';
        $package_setting_model_foriegn_key = '`' . 'package_id' . '`';
        $master_package_setting_foriegn_key = '`' . 'setting_key' . '`';
        $this->db->select("$pacage_table.id, $pacage_table.title, $master_package_setting_table.description, $package_setting_table.status");
        $this->db->join($package_setting_table, "$package_setting_table.$package_setting_model_foriegn_key=$pacage_table.id", 'inner');
        $this->db->join($master_package_setting_table, "$master_package_setting_table.$master_package_setting_foriegn_key=$package_setting_table.$master_package_setting_foriegn_key", 'inner');
        $this->db->where("$pacage_table.service_id", $service_id);
		$this->db->where("$pacage_table.deleted_at", null);


        $this->db->order_by("$pacage_table.id");
        $this->db->order_by("$pacage_table.title");
        $this->db->order_by("$master_package_setting_table.description");
        $this->db->group_by("$pacage_table.title");
        $this->db->group_by("$master_package_setting_table.description");
        $rs = $this->db->get($pacage_table)->result_array();
        $finalData = [];
        foreach($rs as $key=>$rec){
            $active= 0;
            if($packageID==$rec['id']){
                $active= 1;
            }
            if(!($finalData[$rec['title']])){
                $finalData[$rec['title']] = [
                    'title' => $rec['title'],
                    "is_active"=> $active,
                    'features'=> []
                ];
            }
            array_push($finalData[$rec['title']]['features'], [
                'description'=>$rec['description'],
                'status'=>$rec['status']
            ]);
        }
        return array_values($finalData);
    }
	
	public function vendorPackageFeatures($service_id, $packageID=null){
        $this->load->model(array(
            'master_package_setting_model',
            'package_setting_model'
        ));
        $pacage_table = $this->table;

        $package_setting_table = '`' . $this->package_setting_model->table . '`';
        $master_package_setting_table = '`' . $this->master_package_setting_model->table . '`';
        $package_setting_model_foriegn_key = '`' . 'package_id' . '`';
        $master_package_setting_foriegn_key = '`' . 'setting_key' . '`';
        $this->db->select("$pacage_table.id, $pacage_table.title, $master_package_setting_table.description, $package_setting_table.status, $package_setting_table.setting_key");
        $this->db->join($package_setting_table, "$package_setting_table.$package_setting_model_foriegn_key=$pacage_table.id", 'inner');
        $this->db->join($master_package_setting_table, "$master_package_setting_table.$master_package_setting_foriegn_key=$package_setting_table.$master_package_setting_foriegn_key", 'inner');
        $this->db->where("$pacage_table.service_id", $service_id);
        $this->db->where("$pacage_table.id", $packageID);
		$this->db->where("$pacage_table.deleted_at", null);
		$this->db->where("$package_setting_table.status", 1);
		$this->db->where("$master_package_setting_table.status", 1);



        $this->db->order_by("$pacage_table.id");
        $this->db->order_by("$pacage_table.title");
        $this->db->order_by("$master_package_setting_table.description");
        $this->db->group_by("$pacage_table.title");
        $this->db->group_by("$master_package_setting_table.description");
        $rs = $this->db->get($pacage_table)->result_array();
        $finalData = [];
        foreach($rs as $key=>$rec){
            $active= 0;
            if($packageID==$rec['id']){
                $active= 1;
            }
            if(!($finalData[$rec['title']])){
                $finalData[$rec['title']] = [
                    'title' => $rec['title'],
                    "is_active"=> $active,
                    'features'=> []
                ];
            }
            array_push($finalData[$rec['title']]['features'], [
                'description'=>$rec['description'],
                'status'=>$rec['status'],
                'setting_key'=>$rec['setting_key']
            ]);
        }
        return array_values($finalData);
    }
}

