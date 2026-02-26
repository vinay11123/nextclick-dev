<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Scripts extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('food_item_model');
        $this->load->model('user_model');
        $this->load->model('vendor_list_model');
        $this->load->model('vendor_product_variant_model');
    }
    
    public function change_menu_ids_get() {
        $all_menu_ids = $this->db->query(
            "SELECT id, sub_cat_id, menu_id FROM u928323410_pre_production.food_item where (sub_cat_id >= 236 and sub_cat_id <= 261) or (sub_cat_id >= 276 and sub_cat_id <= 286) order by sub_cat_id;"
            )->result_array();
        $count = 1;
        foreach ($all_menu_ids as $item){
             $this->food_item_model->update([
                'id' => $item['id'],
                'menu_id' => $item['menu_id'] - 1
            ], 'id'); 
            $count++;
        }
        $this->set_response_simple($count, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        
    }
    
    public function add_catalogue_to_inventory_get($vendor_mobile){
        $user = $this->user_model->where('phone', $vendor_mobile)->get();
        if(! empty($user)){
            $vendor = $this->vendor_list_model->where('vendor_user_id', $user['id'])->get();
            if(! empty($vendor)){
                $section_items = $this->db->query(
                    "SELECT fsi.id, fsi.sec_id, fsi.item_id, fsi.section_item_code, fsi.price  FROM food_sec_item as fsi
                    join food_item as fi on fi.id = fsi.item_id 
                    where fi.status = 1 and fi.sub_cat_id in (SELECT id FROM sub_categories where cat_id = ".$vendor['category_id']." and type = 2);"
                    )->result_array();
                    $vendor_variants = [];
                    $this->vendor_product_variant_model->user_id = $user['id'];
                    if(!empty($section_items)){ foreach ($section_items as $key => $section_item){
                    array_push($vendor_variants, [
                        'item_id' => $section_item['item_id'],
                        'section_id' => $section_item['sec_id'],
                        'section_item_id' => $section_item['id'], 
                        'sku' => $section_item['section_item_code'].$key, 
                        'price' => $section_item['price'], 
                        'stock' => 250, 
                        'discount' => 10,  
                        'tax_id' => 2, 
                        'list_id' => $vendor['id'], 
                        'vendor_user_id' => $user['id'], 
                        'created_user_id' => $user['id'], 
                        'status' => 1
                    ]);
                }
                $are_varinats_existed = $this->vendor_product_variant_model->where('vendor_user_id', $user['id'])->get_all();
                if(empty($are_varinats_existed)){
                    $this->db->insert_batch('vendor_product_variants', $vendor_variants);
                    $this->set_response_simple("Inventory count: ".count($vendor_variants), 'Success!', REST_Controller::HTTP_OK, TRUE);
                }else {
                    $this->set_response_simple(NULL, 'Inventory products are already existed!', REST_Controller::HTTP_OK, FALSE);
                }
                }
            }else {
                $this->set_response_simple(NULL, 'No vendor found', REST_Controller::HTTP_OK, FALSE);
            }
        }else {
            $this->set_response_simple(NULL, 'No vendor found', REST_Controller::HTTP_OK, FALSE);
        }
        
    }
    
}

