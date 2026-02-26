<?php

class Service_tax_model extends MY_Model
{
    public $rules;
    //public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'service_tax';
        $this->primary_key = 'id';
       // $this->foreign_key = 'service_id';
        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';
        
       $this->_config();
       $this->_form();
       $this->_relations();
    }
    protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    }
    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    } 
    public function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
        $this->has_one['category'] = array('Category_model', 'id', 'cat_id');
        $this->has_one['subcategory'] = array('Sub_category_model', 'id', 'sub_cat_id');
        $this->has_one['menu'] = array('Food_menu_model', 'id', 'menu_id');
        $this->has_one['state'] = array('State_model', 'id', 'state_id');
        $this->has_one['district'] = array('District_model', 'id', 'district_id');
        $this->has_one['constituency'] = array('Constituency_model', 'id', 'constituency_id');
    }
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'cat_id',
                'lable' => 'Category',
                'rules' => 'required',
                'errors'=>array(
                    'required' => 'You must provide a %s.',
                 )
            ),
            array(
                'field' => 'state_id',
                'lable' => 'State',
                'rules' => 'required',
                'errors'=>array(
                    'required' => 'You must provide a %s.',
                 )
            ),
        );
    }
    
    public function service_charge_by_produt_id($product_id = NULL, $constituency_id = NULL){
        try {
            if(empty($product_id)){
                return [
                    'success' => FALSE,
                    'error' => 'Product id is missed.'
                ];
            }
            $this->load->model('food_item_model');
            $item = $this->food_item_model->where('id', $product_id)->get();
            $sc = $this->fields('menu_id, service_tax')->where(['menu_id' => $item['menu_id'], 'constituency_id' => $constituency_id])->get();
            return [
                'success' => TRUE,
                'error' => NULL,
                'data' => $sc
            ];
                
        } catch (Exception $e) {
            return [
                'success' => FALSE,
                'error' => $e
            ];
        }
    }

    public function calculate_service_charge($order_item = NULL, $cat_id = NULL, $constituency_id = NULL, $state_id = NULL, $district_id = NULL){
      
        try {
            if(empty($order_item)){
                return [
                    'success' => FALSE,
                    'error' => 'Product id is missed.'
                ];
            }
            $this->load->model('food_item_model');
            $item = $this->food_item_model->where('id', $order_item['item_id'])->get();
            //$this->fields('menu_id, service_tax')->where(['menu_id' => $item['menu_id'], 'constituency_id' => $constituency_id])->get();

            $sc = $this->compute_service_charge($cat_id,$item['sub_cat_id'],$item['menu_id'],$constituency_id,$state_id,$district_id);
            if($sc['success'] == TRUE && ! empty($sc['data'])) 
            {
                return [
                    'success' => TRUE,
                    'error' => NULL,
                    'data' => $sc['data']
                ];
             }
            else{
                return [
                    'success' => FALSE,
                    'error' => NULL
                ];
             }
                
        } catch (Exception $e) {
            return [
                'success' => FALSE,
                'error' => $e
            ];
        }
    }

    public function compute_service_charge($cat_id = NULL, $sub_cat_id = NULL, $menu_id = NULL, $constituency_id = NULL, $state_id = NULL, $district_id = NULL){
        try {

            $ServiceCharge  = $this->service_tax_model->fields('id,service_tax')
                                    ->where('cat_id' , $cat_id) 
                                    ->where('sub_cat_id' , $sub_cat_id)
                                    ->where('menu_id' , $menu_id)
                                    //->where('state_id', $state_id)
                                    //->where('district_id', $district_id)
                                    //->where('constituency_id', $constituency_id)
                                    ->get_all();

                                  
            if (empty($ServiceCharge)) {//menu id null
                $ServiceCharge  =  $this->db->query("SELECT id, service_tax FROM service_tax where 
                cat_id = " . $cat_id . " AND sub_cat_id = " . $sub_cat_id . " AND
                menu_id IS NULL")->result_array();
            }
            if (empty($ServiceCharge)) {
                $ServiceCharge  =  $this->db->query("SELECT id, service_tax FROM service_tax where 
                            cat_id = " . $cat_id . " AND sub_cat_id IS NULL
                            AND menu_id IS NULL")->result_array();
            }
            //now match state,district and constituency
            if($ServiceCharge)
            {
	            $service_tax_ids = (array_column($ServiceCharge, 'id'));
                $ServiceCharge  = $this->service_tax_model->fields('id,service_tax')
                                    ->where('id', $service_tax_ids)
                                    ->where('state_id', $state_id)
                                    ->where('district_id', $district_id)
                                    ->where('constituency_id', $constituency_id)
                                    ->get_all();
                if (empty($ServiceCharge)) {
                    $ServiceCharge  = $this->service_tax_model->fields('id,service_tax')
                                    ->where('id', $service_tax_ids)
                                    ->where('state_id', $state_id)
                                    ->where('district_id', $district_id)
                                   // ->where('constituency_id', 0) - bcoz we are already checking with id's 
                                    ->get_all();
                }
                if (empty($ServiceCharge)) {
                    $ServiceCharge  = $this->service_tax_model->fields('id,service_tax')
                                    ->where('id', $service_tax_ids)
                                    ->where('state_id', $state_id)
                                    //->where('district_id', 0)
                                   // ->where('constituency_id', 0)
                                    ->get_all();
                }
            }
            if(empty($ServiceCharge))
            {
                return [
                    'success' => FALSE,
                    'error' => NULL
                ];
            }
            else{
                return [
                    'success' => TRUE,
                    'error' => NULL,
                    'data' => $ServiceCharge[0]
                ];     
            }                   
                
        } catch (Exception $e) {
            return [
                'success' => FALSE,
                'error' => $e
            ];
        }
    }
}

