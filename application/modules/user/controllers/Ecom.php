<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';
use Firebase\JWT\JWT;

class Ecom extends MY_REST_Controller
{

    public $user_id = NULL;

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('food_menu_model');
        $this->load->model('user_model');
        $this->load->model('food_cart_model');
        $this->load->model('food_item_model');
        $this->load->model('food_section_model');
        $this->load->model('food_sec_item_model');
        $this->load->model('food_orders_model');
        $this->load->model('food_order_items_model');
        $this->load->model('food_sub_order_items_model');
        $this->load->model('food_order_deal_model');
        $this->load->model('food_settings_model');
        $this->load->model('delivery_boy_status_model');
        $this->load->model('users_address_model');
        $this->load->model('order_rating_model');
        $this->load->model('vendor_list_model');
        $this->load->model('notification_type_model');
        $this->load->model('contact_model');
        $this->load->model('food_item_image_model');
        $this->load->model('payment_method_model');
        $this->load->model('delivery_mode_model');
        $this->load->model('location_model');
        $this->load->model('vendor_product_variant_model');
        $this->load->model('delivery_fee_model');
        $this->load->model('delivery_job_model');
        $this->load->model('vehicle_model');
        $this->load->model('ecom_order_model');
        $this->load->model('pickup_orders_model');
        $this->load->model('ecom_order_deatils_model');
        $this->load->model('ecom_order_status_model');
        $this->load->model('ecom_payment_model');
        $this->load->model('stock_settings_model');
        $this->load->model('used_promo_codes_model');
        $this->load->model('promotion_banner_model');
        $this->load->model('constituency_model');
        $this->load->model('notifications_model');
        $this->load->model('promotion_banner_shop_by_category_model');
        $this->load->model('setting_model');
        $this->load->model('return_policies_model');
        $this->load->model('ecom_order_reject_request_model');
        $this->load->model('delivery_job_rejection_model');
        $this->load->model('pickupcategory_model');
		$this->load->model('cupons_model');
		$this->load->model('used_cupons_model');
        $this->load->model('Admin_banners_model');
    }
    
    
    public function shop_by_categories_get($target = 0){
        $vendor_user_id = $this->input->get('vendor_user_id');
        $vendor = $this->vendor_list_model->fields('category_id')->where('vendor_user_id', $vendor_user_id)->get();
		$cat_id = ! empty($vendor['category_id'])? $vendor['category_id']: $this->input->get('cat_id');
        if(! empty($target)){
			
            $query = "
                select sc.*, 
                ( select count(fi.id) as product_count from food_item as fi join vendor_product_variants as vpv on vpv.item_id = fi.id where fi.sub_cat_id = sc.id and vpv.stock != 0 and vpv.vendor_user_id = ".$vendor_user_id.") as product_count 
                from sub_categories as sc where sc.type = 2 and sc.id = ".$target.";
            ";
            $shop_by_category = $this->db->query($query)->result_array();
            $this->set_response_simple((empty($shop_by_category)) ? NULL : $shop_by_category, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }else {
            $query = "
                select sc.*, 
                ( select count(fi.id) as product_count from food_item as fi join vendor_product_variants as vpv on vpv.item_id = fi.id where fi.sub_cat_id = sc.id and vpv.stock != 0 and vpv.vendor_user_id = ".$vendor_user_id." and fi.availability != 0 and vpv.status = 1) as product_count 
                from sub_categories as sc 
                join food_item as fitem on fitem.sub_cat_id = sc.id
                join vendor_product_variants as vpv2 on vpv2.item_id = fitem.id 
                where sc.type = 2 and cat_id = ".$cat_id." and fitem.sub_cat_id = sc.id and vpv2.stock != 0 and vpv2.vendor_user_id = ".$vendor_user_id." and fitem.availability != 0  group by sc.id  having product_count > 0;
            ";
			$shop_by_categories = $this->db->query($query)->result_array();
            if(! empty($shop_by_categories)){foreach ($shop_by_categories as $key => $shop_by_category){
                $shop_by_categories[$key]['image'] = base_url() . 'uploads/sub_category_image/sub_category_' . $shop_by_category['id'] . '.jpg'.'?'.time();
            }}
            $this->set_response_simple((empty($shop_by_categories)) ? NULL : $shop_by_categories, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
        
    }
    
    public function menus_get($target = 0){
		$vendor_user_id = $this->input->get('vendor_user_id');
        $sub_cat_id = $this->input->get('sub_cat_id');
        if(! empty($target)){
            $query = "
                select fm.*, 
                ( select count(fi.id) as product_count from food_item as fi join vendor_product_variants as vpv on vpv.item_id = fi.id where fi.menu_id = fm.id and vpv.stock != 0 and vpv.vendor_user_id = ".$vendor_user_id." and fi.availability != 0) as product_count 
                from food_menu as fm where fm.id = ".$target.";
            ";
			$menu = $this->db->query($query)->result_array();
            $this->set_response_simple((empty($menu)) ? NULL : $menu, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }else {
            $query = "
                select fm.*, 
                ( select count(fi.id) as product_count from food_item as fi join vendor_product_variants as vpv on vpv.item_id = fi.id where fi.menu_id = fm.id and vpv.stock != 0 and vpv.vendor_user_id = ".$vendor_user_id." and fi.availability != 0 and vpv.status = 1) as product_count 
                from food_menu as fm 
                join food_item as fitem on fitem.menu_id = fm.id
                join vendor_product_variants as vpv2 on vpv2.item_id = fitem.id 
                where fm.sub_cat_id = ".$sub_cat_id." and vpv2.stock != 0 and vpv2.vendor_user_id = ".$vendor_user_id." and fitem.availability != 0 group by fm.id  having product_count > 0;
            ";
            $menus = $this->db->query($query)->result_array();
            foreach ($menus as $key => $menu){
                $menus[$key]['image'] = base_url() . 'uploads/food_menu_image/food_menu_' . $menu['id'] . '.jpg';
            }
            $this->set_response_simple((empty($menus)) ? NULL : $menus, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
        
    }

    /**
     * To get list of products
     *
     * @author Mehar
     *        
     * @param number $limit
     * @param number $offset
     */
    public function products_get($limit = 10, $offset = 0)
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        if (empty($_GET['item_id'])) {
            if(! empty($_GET['hit_from']) && $_GET['hit_from'] == 'cehckout'){
                $vendors = $this->vendor_list_model->get_vendors_near_by_given_latlong((isset($_GET['latitude'])) ? $this->input->get('latitude') : NUll, (isset($_GET['longitude'])) ? $this->input->get('longitude') : NUll, (isset($_GET['category_id'])) ? $this->input->get('category_id') : NUll);
                $_GET['vendor_user_id'] = (empty($vendors))? []: array_column($vendors, 'vendor_user_id');
                $data = $this->food_item_model->all($limit, $offset, (isset($_GET['item_type'])) ? $this->input->get('item_type') : NUll, (isset($_GET['sub_cat_id'])) ? $this->input->get('sub_cat_id') : NUll, (isset($_GET['menu_id'])) ? $this->input->get('menu_id') : NUll, (isset($_GET['brand_id'])) ? $this->input->get('brand_id') : NUll, (isset($_GET['q'])) ? $this->input->get('q') : NUll, (isset($_GET['vendor_user_id'])) ? $this->input->get('vendor_user_id') : NUll, 'checkout');
            }else {
                $data = $this->food_item_model->all($limit, $offset, (isset($_GET['item_type'])) ? $this->input->get('item_type') : NUll, (isset($_GET['sub_cat_id'])) ? $this->input->get('sub_cat_id') : NUll, (isset($_GET['menu_id'])) ? $this->input->get('menu_id') : NUll, (isset($_GET['brand_id'])) ? $this->input->get('brand_id') : NUll, (isset($_GET['q'])) ? $this->input->get('q') : NUll, (isset($_GET['vendor_user_id'])) ? $this->input->get('vendor_user_id') : NUll, NULL);
            }
            if (! empty($data['result']) && ! empty($data['result'][0]['id'])) {
                foreach ($data['result'] as $key => $val) {
                    $is_product_available_on_cart = $this->food_cart_model->where([
                        'item_id' => $val['id'],
                        'created_user_id' => $token_data->id,
                        'vendor_user_id' => $val['vendor_user_id']
                    ])->get_all();
                    if (! empty($is_product_available_on_cart)) {
                        $data['result'][$key]['cart_qty'] = array_sum(array_column($is_product_available_on_cart, 'qty'));
                    } else {
                        $data['result'][$key]['cart_qty'] = 0;
                    }
                    
                    if(! empty($val['id'])){
                        $data['result'][$key]['vendor'] = $this->vendor_list_model->fields('id, name, unique_id')->where('vendor_user_id', $val['vendor_user_id'])->get();
                        $data['result'][$key]['vendor_varinats'] = $this->vendor_product_variant_model->fields('id, item_id, section_id, section_item_id, sku, price, stock, discount, list_id, vendor_user_id, status')
                        ->with_section_item('fields: id, name, weight')
                        ->where([
                            'item_id' => $val['id'],
                            'stock >=' => 1,
                            'vendor_user_id' => $val['vendor_user_id'],
                            'status' => 1
                        ])->order_by('price')->get_all();
                        $data['result'][$key]['max_price'] = ! empty($data['result'][$key]['vendor_varinats'])? max(array_column($data['result'][$key]['vendor_varinats'], 'price')): $data['result'][$key]['max_price'];
                        $data['result'][$key]['max_stock'] = ! empty($data['result'][$key]['vendor_varinats'])? max(array_column($data['result'][$key]['vendor_varinats'], 'stock')): $data['result'][$key]['max_stock'];
                        $data['result'][$key]['max_discount'] = ! empty($data['result'][$key]['vendor_varinats'])? max(array_column($data['result'][$key]['vendor_varinats'], 'discount')): $data['result'][$key]['max_discount'];
                        if(! empty($data['result'][$key]['vendor_varinats']))
                        {
                            foreach ($data['result'][$key]['vendor_varinats'] as $k => $variant){
                            $cart = $this->food_cart_model->fields('qty')->where([
                                'vendor_product_variant_id' => $variant['id'],
                                'created_user_id' => $token_data->id,
                                'vendor_user_id' => $val['vendor_user_id']
                            ])->get();
                            $data['result'][$key]['vendor_varinats'][$k]['cart'] =  empty($cart)? null : $cart;
                            
                        }}
                        
                        $data['result'][$key]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $val['image_id'] . '.' . $val['ext'] . '?' . time();
                    }
                }
                $this->set_response_simple((empty($data['result'])) ? NULL : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }else {
                $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }
            
        } else {
            $catalogue_product = $this->food_item_model->fields('id, product_code, name, desc, item_type, availability')
                ->with_menu('fields: id, name')
                ->with_sub_category('fields: id, name')
                ->with_brand('fields: id, name')
                ->with_sections('fields: id, name')
                ->with_item_images('fields: id, serial_number, ext')
                ->with_section_items('fields: id, section_item_code, name, desc, price, weight, status, created_at, updated_at')
                ->with_vendor_product_varinats('fields: id, item_id, section_id, section_item_id, sku, price, stock, discount, list_id, vendor_user_id,return_available,return_id,status', 'where: vendor_user_id=' . $_GET['vendor_user_id'].' and status = 1 and stock >= 1')
                ->get($_GET['item_id']);
            if (! empty($catalogue_product['vendor_product_varinats'])) {
                foreach ($catalogue_product['vendor_product_varinats'] as $key => $val) {
                    $name = $this->food_sec_item_model->fields('name, weight, section_item_code, desc')
                        ->where('id', $val['section_item_id'])
                        ->get();
                    $catalogue_product['vendor_product_varinats'][$key]['section_item_name'] = ! empty($name) ? $name['name'] : NULL;
                    $catalogue_product['vendor_product_varinats'][$key]['weight'] = ! empty($name) ? $name['weight'] : NULL;
                    $catalogue_product['vendor_product_varinats'][$key]['section_item_code'] = ! empty($name) ? $name['section_item_code'] : NULL;
                    $catalogue_product['vendor_product_varinats'][$key]['desc'] = ! empty($name) ? $name['desc'] : NULL;
                                        //$catalogue_product['vendor_product_varinats'][$key]['returns'] = $this->return_policies_model->fields('id,return_days,terms_conditions')->where('id',$val['return_id'])->get();
                        if (!empty($val['return_id'])) {

                        $return = $this->return_policies_model
                                       ->fields('id,return_days,terms_conditions')
                                       ->where('id', $val['return_id'])
                                       ->get();
                    
                        $catalogue_product['vendor_product_varinats'][$key]['returns'] = !empty($return) ? $return : null;
                    
                    } else {
                        $catalogue_product['vendor_product_varinats'][$key]['returns'] = null;
                    };
                   }
            }

            if (! empty($catalogue_product)) {
                if (! empty($catalogue_product['item_images'])) {
                    foreach ($catalogue_product['item_images'] as $k => $img) {
                        $catalogue_product['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                    }
                } else {
                    $catalogue_product['item_images'] = NULL;
                }
            }
            $this->set_response_simple($catalogue_product, "Success..!", REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To Handle ecommerce cart
     *
     * @author bhagyeshwar
     *        
     * @param string $type
     */
    public function cart_post($type = 'r', $target = 0)
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $this->food_cart_model->user_id = $token_data->id;
		
        if ($type == 'c') {
            $this->form_validation->set_rules($this->food_cart_model->rules['create_rules']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {

                $cart_vendor_user_id = $this->db->query("SELECT distinct `vendor_user_id` FROM `food_cart` WHERE 1 and `created_user_id` = $token_data->id")->row()->vendor_user_id;

                /* if(!empty($cart_vendor_user_id) && $cart_vendor_user_id != $this->input->post('vendor_user_id')) {
                    $this->set_response_simple(NULL, 'Multi Vendor products are not allowed', REST_Controller::HTTP_OK, FALSE);
                    return;
                }  */           

                $is_there = $this->food_cart_model->where([
                    'created_user_id' => $token_data->id,
                    'vendor_product_variant_id' => $this->input->post('vendor_product_variant_id')
                ])->get();

                if (! empty($is_there)) {
                    $is_inserted = $this->food_cart_model->update([
                        'id' => $is_there['id'],
                        'vendor_product_variant_id' => $this->input->post('vendor_product_variant_id'),
                        'qty' => intval($is_there['qty']) + 1
                    ], 'id');
                } else {
                    $is_inserted = $this->food_cart_model->insert([
                        'item_id' => $this->input->post('item_id'),
                        'vendor_product_variant_id' => $this->input->post('vendor_product_variant_id'),
                        'qty' => $this->input->post('qty'),
                        'vendor_user_id' => $this->input->post('vendor_user_id'),
                        'status' => 1
                    ]);
                }

                if ($is_inserted) {
                    $this->set_response_simple(($is_inserted == FALSE) ? NULL : $is_inserted, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($is_inserted == FALSE) ? NULL : $is_inserted, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        } elseif ($type == 'd') {
            if (! empty($target)) {
                $cart_product = $this->food_cart_model->get($target);
                if (! empty($cart_product) && $cart_product['created_user_id'] == $token_data->id) {
                    $this->food_cart_model->delete([
                        'id' => $target
                    ]);
                    $this->set_response_simple(NULL, 'Cart has deleted..!', REST_Controller::HTTP_OK, TRUE);
                } else {
                    $this->set_response_simple(NULL, 'No privilege to  delete..!', REST_Controller::HTTP_OK, FALSE);
                }
            } else {
                $product_id = $this->input->post('vendor_product_variant_id');
                if(empty($this->input->post('vendor_product_variant_id'))){
                    $this->food_cart_model->delete([
                        'created_user_id' => $token_data->id,
                    ]);
                    $this->set_response_simple(NULL, 'Cart has been cleared..!', REST_Controller::HTTP_OK, TRUE);
                }else {
                    $this->food_cart_model->delete([
                        'created_user_id' => $token_data->id,
                        'vendor_product_variant_id' => $product_id
                    ]);
                    $this->set_response_simple(NULL, 'Product has been deleted..!', REST_Controller::HTTP_OK, TRUE);
                }
                
            }
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->food_cart_model->rules['update_rules']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $is_updated = $this->food_cart_model->update([
                    'id' => $this->input->post('id'),
                    'vendor_product_variant_id' => $this->input->post('vendor_product_variant_id'),
                    'qty' => $this->input->post('qty'),
                    'vendor_user_id' => $this->input->post('vendor_user_id')
                ], 'id');
                if ($is_updated) {
                    $this->set_response_simple(($is_updated == FALSE) ? NULL : $is_updated, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
                } else {
                    $this->set_response_simple(($is_updated == FALSE) ? NULL : $is_updated, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        } elseif ($type == 'r') {
            $cart_products = $this->food_cart_model->fields('id, qty, status')
                ->with_vendor('fields: id, name, desc')
                ->with_item('fields:id, name, desc,sub_cat_id, menu_id,brand_id')
                ->with_item_images('fields: id, ext')
                ->with_vendor_product_variant('fields: id, section_item_id, stock, price, discount, status')
                ->where('created_user_id', $token_data->id)
                ->get_all();
            if (! empty($cart_products)) {
                foreach ($cart_products as $key => $product) {
                    // $cart_products[$key]['vendor_product_variant']['details'] = $this->food_sec_item_model->fields('name, weight')->where('id', $product['vendor_product_variant']['section_item_id'])->get();
                    if (! empty($cart_products[$key]['item_images'])) {
                        foreach ($cart_products[$key]['item_images'] as $k => $img) {
                            $cart_products[$key]['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                        }
                    } else {
                        $cart_products[$key]['item_images'] = [];
                    }
                    if (! empty($product['vendor_product_variant']['section_item_id'])) {
                        $cart_products[$key]['vendor_product_variant']['details'] = $this->food_sec_item_model->fields('name, weight')
                            ->where('id', $product['vendor_product_variant']['section_item_id'])
                            ->get();
                    } else {
                        $cart_products[$key]['vendor_product_variant']['details'] = [
                            "name" => NULL,
                            'weight' => NULL
                        ];
                    }
                    $cart_products[$key]['status_ref'] = [
                        '1' => "Cart Products",
                        '2' => 'Wishlist products'
                    ];
                    $cart_products[$key]['vendor_product_variant']['status_ref'] = [
                        '1' => "Active product",
                        '2' => 'In-Active product'
                    ];
                }
            }
            $this->set_response_simple(empty($cart_products) ? [] : $cart_products, 'Cart Data', REST_Controller::HTTP_OK, TRUE);
        } elseif ($type == 'change_status') {
            $is_updated = $this->food_cart_model->update([
                'id' => $this->input->post('cart_id'),
                'status' => $this->input->post('status')
            ], 'id');

            if ($is_updated) {
                $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
            } else {
                $this->set_response_simple(NULL, 'Failed..!', REST_Controller::HTTP_OK, FALSE);
            }
        } elseif ($type == 'cart_count') {  
            $this->food_cart_model->delete([
                'created_user_id' => $token_data->id,
                'qty' => 0
            ]);          
            $cart_count_data = $this->db->query("SELECT count(id) cart_count FROM `food_cart` WHERE `created_user_id` = $token_data->id and `status` = 1")->row();
            $this->set_response_simple($cart_count_data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
        
    }

    /**
     * To Handle Shipping addresses
     *
     * @author bhagyeshwar
     *        
     * @param string $type
     */
    public function shipping_address_post($type = 'r', $target = 0)
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->users_address_model->user_id = $token_data->id;
        if ($type == 'c') {
            $this->form_validation->set_rules($this->users_address_model->rules['create_rules']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $vendors = $this->input->post('vendors');
                if(! empty($vendors)){
                    $vendor = $this->vendor_list_model->with_location('fields: id, latitude, longitude, address')->with_address('fields: constituency')->fields('constituency_id, location_id')->where("vendor_user_id", array_column($vendors, "vendor_user_id"))->get();
                     $distance =  haversineGreatCircleDistance($vendor['location']['latitude'], $vendor['location']['longitude'], $this->input->post('latitude'), $this->input->post('longitude'));
                    $constituency = $this->constituency_model->where('id', $vendor['address']['constituency'])->get();
					$state_id= $constituency['state_id'];
					$district_id= $constituency['district_id'];
					$constituency_id= $constituency['id'];
                    $constituency_distance=$this->db->query('SELECT constituency_km FROM `delivery_fee` WHERE constituency_id ='.$constituency_id.' AND district_id ='.$district_id.' and state_id='.$state_id.' and deleted_at is null')->row()->constituency_km;
					 $max_order_distance =	$this->setting_model->where("key", 'max_order_distance')->get();
					if(floatval($distance) <= $max_order_distance['value']){
                        $data1 = $this->location_model->fields('id, address')
                        ->where('latitude', $this->input->post('latitude'))
                        ->where('longitude', $this->input->post('longitude'))
                        ->get();
                        if ($data1 && count(array(
                            $data1
                        )) > 0) {
                            $locid = $data1['id'];
                        } else {
                            $is_geo_inserted = $this->location_model->insert([
                                'latitude' => $this->input->post('latitude'),
                                'longitude' => $this->input->post('longitude'),
                                'address' => $this->input->post('address')
                            ]);
                            $locid = $is_geo_inserted;
                        }
                        $this->users_address_model->update([
                            'created_user_id' => $token_data->id,
                            'status' => 2
                        ], 'created_user_id');
                        
                        $is_inserted = $this->users_address_model->insert([
                            'state_id' => $constituency['state_id'],
                            'district_id' => $constituency['district_id'],
                            'constituency_id' => $constituency['id'],
                            'phone' => $this->input->post('mobile'),
                            'email' => $this->input->post('email'),
                            'name' => $this->input->post('name'),
                            'address' => $this->input->post('address'),
                            'landmark' => $this->input->post('landmark'),
                            'pincode' => $this->input->post('pincode'),
                            'location_id' => $locid,
                            'status' => 1
                        ]);
                        if ($is_inserted) {
                            $this->set_response_simple(($is_inserted == FALSE) ? NULL : $is_inserted, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                        } else {
                            $this->set_response_simple(($is_inserted == FALSE) ? NULL : $is_inserted, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                        }
                    }else {
                        $this->set_response_simple(NULL, 'Selected location is far away from vendors.!', REST_Controller::HTTP_OK, FALSE);
                    }
                }else {
                    $this->set_response_simple(NULL, 'Please provide vendor details.!', REST_Controller::HTTP_OK, FALSE);
                }
            }
        } elseif ($type == 'd') {

            $user_address = $this->users_address_model->get($target);
            if (! empty($user_address) && $user_address['created_user_id'] == $token_data->id) {
                $this->users_address_model->delete([
                    'id' => $target
                ]);

                $this->set_response_simple(NULL, 'User Address deleted..!', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'No privilege to  delete..!', REST_Controller::HTTP_OK, FALSE);
            }
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->users_address_model->rules['update_rules']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $data1 = $this->location_model->fields('id, address')
                    ->where('latitude', $this->input->post('latitude'))
                    ->where('longitude', $this->input->post('longitude'))
                    ->get();
                if ($data1 && count($data1) > 0) {
                    $locid = $data1['id'];
                } else {
                    $is_geo_inserted = $this->location_model->insert([
                        'latitude' => $this->input->post('latitude'),
                        'longitude' => $this->input->post('longitude'),
                        'address' => $this->input->post('geo_location')
                    ]);
                    $locid = $is_geo_inserted;
                }
                
                if($this->input->post('is_default')){
                    $this->users_address_model->update([
                        'created_user_id' => $token_data->id,
                        'status' => 2
                    ], 'created_user_id');
                }
                $is_updated = $this->users_address_model->update([
                    'id' => $this->input->post('id'),
                    'state_id' => $this->input->post('state_id'),
                    'district_id' => $this->input->post('district_id'),
                    'constituency_id' => $this->input->post('constituency_id'),
                    'phone' => $this->input->post('mobile'),
                    'email' => $this->input->post('email'),
                    'name' => $this->input->post('name'),
                    'address' => $this->input->post('address'),
                    'landmark' => $this->input->post('landmark'),
                    'pincode' => $this->input->post('pincode'),
                    'location_id' => $locid,
                    'status' => $this->input->post('is_default')
                ], 'id');

                if ($is_updated) {
                    $this->set_response_simple(($is_updated == FALSE) ? NULL : $is_updated, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
                } else {
                    $this->set_response_simple(($$is_updated == FALSE) ? NULL : $is_updated, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        } elseif ($type == 'r') {
            if (empty($target)) {
                $vendors = $this->input->post('vendors');
        if(! empty($vendors)){
                    $vendor = $this->vendor_list_model->with_location('fields: id, latitude, longitude, address')->fields('constituency_id, location_id')->where("vendor_user_id", array_column($vendors, "vendor_user_id"))->get();
                    $users_address = $this->users_address_model
                    ->with_location('fields: id, latitude, longitude, address')
                    ->with_state('fields: id, name')
                    ->with_district('fields: id, state_id, name')
                    ->with_constituency('fields: id, state_id, district_id, name, pincode')
                    ->where('state_id', '<>',0)
                    ->where('created_user_id', $token_data->id)
                    ->order_by('id', 'DESC')
                    ->get_all();
					
                    foreach ($users_address as $key => $address){
						$distance =  haversineGreatCircleDistance($vendor['location']['latitude'], $vendor['location']['longitude'], $address['location']['latitude'],$address['location']['longitude']);
						/*echo floatval($distance);
						echo "\n";
						echo "ve";
												echo "\n";

						echo $vendor['constituency_id'];
						echo "\n";
						echo "ad";
												echo "\n";

						echo $address['constituency']['id'];
						echo "\n";*/
						$max_order_distance =	$this->setting_model->where("key", 'max_order_distance')->get();
                        $users_address[$key]['is_shipping_available'] = floatval($distance <=$max_order_distance['value']) ? 1 : 0;
                    }
                    $this->set_response_simple( empty($users_address)? NULL : $users_address, 'User Addresses List', REST_Controller::HTTP_OK, TRUE);
                }else {
                    $this->set_response_simple(NULL, 'Please provide vendor details.!', REST_Controller::HTTP_OK, FALSE);
                }
            } else {
                $users_address = $this->users_address_model
                    ->with_location('fields: id, latitude, longitude, address')
                    ->with_state('fields: id, name')
                    ->with_district('fields: id, state_id, name')
                    ->with_constituency('fields: id, state_id, district_id, name, pincode')
                    ->where('id', $target)
                    ->get();
                    $this->set_response_simple( empty($users_address)? NULL : $users_address, 'User Address Details', REST_Controller::HTTP_OK, TRUE);
            }
        }
    }
    
   

    /**
     * To get list of Delivery modes
     *
     * @author Mehar
     */
    public function delivery_modes_get()
    {
        $delivery_modes = $this->delivery_mode_model->get_all();
        $this->set_response_simple($delivery_modes, 'List of delivery modes', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * To get list of Payment methods
     *
     * @author Mehar
     */
    public function payment_methods_get()
    {
        $payemnt_methods['payment_modes'] = $this->payment_method_model->get_all();
        $payemnt_methods['max_amount_cod'] = $this->setting_model->where("key", 'max_amount')->get_all();
        $this->set_response_simple($payemnt_methods, 'List of Payment methods', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * To checkout an order
     *
     * @author Mehar
     */
    public function checkout_post($type = 'r', $target = 0)   
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $delivery_mode = $this->delivery_mode_model->where("id", $this->input->post("dalivery_mode_id"))
            ->get();
        if (empty($delivery_mode)) {
            $this->set_response_simple(NULL, 'Sorry, Please select valid delivery mode.', REST_Controller::HTTP_OK, FALSE);
        } else {
            $is_this_permissible_wight = [
                'status' => FALSE,
                'permissible_weight' => $this->db->query('SELECT min(min_capacity) as min_capacity, max(max_capacity_end) as_max_capacity FROM vehicle_type where deleted_at is null;')->result_array()
            ];

            $data['can_i_proceed_to_pay'] = TRUE;
            $shipping_address_id = $this->input->post('shipping_address_id');
            $promotion_banner_id = $this->input->post('promotion_banner_id');
            $cupon_id = $this->input->post('cupon_id');
			
			if($this->input->post('cupon_id')) {
			$cupon = $this->cupons_model->where('id', $cupon_id)->where('(date(`valid_from`) <= current_date())  and (date(`valid_to`) >= current_date())', NULL, NULL, FALSE, FALSE, TRUE)->get();
			
			$used_cupon = $this->used_cupons_model->where([
                            'cupon_id' => $cupon['id'],
                            'user_id' => $token_data->id,
                            'used_date' => date('Y-m-d')
                        ])->get();
			}
            //$shipping_address = NULL;
            if (! empty($shipping_address_id)) {
                $shipping_address = $this->users_address_model->where('id', $shipping_address_id)->get();
            }else {
                $shipping_address = $this->users_address_model->where('state_id','<>',0)->where(['created_user_id' => $token_data->id, 'status' => 1])->get();
            }
			//print_r($this->db->last_query());
            //$delivery_fee = NULL;
            $products = $this->input->post("products");
            $products_group_by_vendor = [];
            if (! is_array($products) && empty($products)) {
                return $this->set_response_simple(NULL, 'Sorry, Please provide products.', REST_Controller::HTTP_OK, FALSE);
            } else {
                foreach ($products as $key => $item) {
                    $products[$key] = $this->vendor_product_variant_model->fields('id, sku, price, stock, discount, status')
                        ->with_tax('fields: id, tax, rate')
                        ->with_item_images('fields: id, ext')
                        ->with_item('fields: id, product_code, name, desc, sub_cat_id, menu_id, brand_id, status')
                        ->with_section_item('fields: id, name, desc, weight, status')
                        ->where("id", $item['vendor_product_variant_id'])
                        ->get();
                        $products[$key]['promo_code_request_data'] = $item;
                        $promotin_offer = $this->promotion_banner_model
                        ->fields('id, title, max_offer_steps, promotion_banner_discount_type_id, discount, promotion_banner_position_id')
                        ->with_offer_products('fields: id, promotion_banner_id, product_id, promotion_banner_shop_by_category_id, vendor_product_variant_id, vendor_user_id')
                        ->with_promotion_products('fields: id, promotion_banner_id, product_id, vendor_product_variant_id, promotion_banner_shop_by_category_id, additional_discount, vendor_user_id', 'where: vendor_product_variant_id='. $item['vendor_product_variant_id'])
                        ->where('published_on <= \''.date('Y-m-d').'\' and expired_on >= \''.date('Y-m-d').'\'', NULL, NULL, FALSE, FALSE, TRUE)
                        ->where(['id' => $promotion_banner_id, 'content_type' => 4])->get();
                        // if(! empty($products[$key])){
                        //     foreach($products[$key] as $k =>$val){
                        //         $products[$key][$k]['returns'] = $this->return_policies_model->fields('id,return_days,terms_conditions')-> where('id',$val['return_id'])->get();
                        //     }
                        // }
                        if(! empty($promotin_offer['offer_products']))
                        { 
                            foreach ($promotin_offer['offer_products'] as $offer_key => $offer_product){
                            $offer_product_shop_by_category_id = $this->promotion_banner_shop_by_category_model->where('id', $offer_product['promotion_banner_shop_by_category_id'])->get();
                            if($products[$key]['item']['sub_cat_id'] == $offer_product_shop_by_category_id['sub_cat_id']){
                                $promotion_offer_product = $this->vendor_product_variant_model->fields('id, sku, price, stock, discount, status')
                                ->with_tax('fields: id, tax, rate')
                                ->with_item_images('fields: id, ext')
                                ->with_section_item('fields: id, name, desc, weight, status')
                                ->with_item('fields: id, product_code, name, desc, status')
                                ->where("id", $offer_product['vendor_product_variant_id'])
                                ->get();
                                if (! empty($promotion_offer_product['item_images'])) {
                                    foreach ($promotion_offer_product['item_images'] as $image_key => $img) {
                                        $promotion_offer_product['item_images'][$image_key]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                                    }
                                } else {
                                    $promotion_offer_product['item_images'] = [];
                                }
                                $promotin_offer['offer_products'][$offer_key]['details'] = empty($promotion_offer_product)? NULL : $promotion_offer_product;
                            }else {
                                unset($promotin_offer['offer_products'][$offer_key]);
                            }
                        }
                        $promotin_offer['offer_products'] = array_values($promotin_offer['offer_products']);
                        }
                        $products[$key]['promotin_offer'] = (empty($promotin_offer) || empty($promotin_offer['promotion_products']))? NULL : $promotin_offer;
                    if (! empty($products[$key]['item_images'])) {
                        foreach ($products[$key]['item_images'] as $k => $img) {
                            $products[$key]['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                        }
                    } else {
                        $products[$key]['item_images'] = [];
                    }

                    $products[$key]['qty'] = $item['qty'];
                    if (intval($item['qty']) <= intval($products[$key]['stock'])) {
                        $products[$key]['is_available'] = TRUE;
                    } else {
                        $data['can_i_proceed_to_pay'] = FALSE;
                        $products[$key]['is_available'] = FALSE;
                    }
                    for ($i = 0; $i < $item['qty']; $i++) {
                        $products[$key]['each_product_weights'][$i]['weight'] = $products[$key]['section_item']['weight'];
                        $products[$key]['each_product_weights'][$i]['qty'] = 1;
                        $products[$key]['each_product_weights'][$i]['name'] = $products[$key]['item']['name'];
                        $products[$key]['each_product_weights'][$i]['section_item_name'] = $products[$key]['section_item']['name'];
                        $products[$key]['each_product_weights'][$i]['price'] = $products[$key]['price'];
                        $products[$key]['each_product_weights'][$i]['item_id'] = $products[$key]['item_id'];
                        $products[$key]['each_product_weights'][$i]['tax'] = $products[$key]['tax'];
                        $products[$key]['each_product_weights'][$i]['discount'] = $products[$key]['discount'];
                        $products[$key]['each_product_weights'][$i]['vendor_product_variant_id'] = $products[$key]['id'];
                        $products[$key]['each_product_weights'][$i]['promotin_offer'] = $products[$key]['promotin_offer'];
                    } 
                    $products_group_by_vendor[$item['vendor_user_id']][$key] = $products[$key];
                    //print_array($products_group_by_vendor);
                }
                foreach ($products_group_by_vendor as $key => $product) {
					
                    $vendor = $this->vendor_list_model->fields('id, name, unique_id, address, landmark, pincode, availability, vendor_user_id, category_id')
						->with_address('fields: id, list_id,line1')
                        ->where('vendor_user_id', $key)
                        ->get();
					//$vendor1 =$this->db->query("SELECT `vendors_list`.`id`,`vendors_list`.`name`, `vendors_list`.`unique_id`, `vendors_list`. `availability`,`vendors_list`. `vendor_user_id`, `vendors_list`. `vendor_user_id`, `vendors_list`. `category_id`,vendor_address.state,vendor_address.district,vendor_address.constituency,vendor_address.zip_code as pincode, vendor_address.location as address FROM vendors_list JOIN vendor_address ON vendors_list.id=vendor_address.list_id where `vendors_list`.`vendor_user_id` = '$key'")->result();
					//	$vendor=json_decode(json_encode($vendor1,JSON_FORCE_OBJECT));

                    $v_query=$this->db->query("SELECT * FROM `vendor_address` where list_id='".$vendor['id']."'");
				    $v_address_data=$v_query->row();
						
						
                    if ($delivery_mode['is_having_delivery_fee'] == 1) {
						$weights_of_products_multi_dimenstion = array_column($product, 'each_product_weights');
					   
                        $weights_of_products_single_dimenstion = call_user_func_array('array_merge', $weights_of_products_multi_dimenstion);
					
                         $total_weight_of_this_bag = array_sum(array_column($weights_of_products_single_dimenstion, 'weight'));

                        //$vehicle = $this->db->query("SELECT * FROM vehicle_type where min_capacity < $max_wight and max_capacity_end >= $max_wight having max(priority);")->result_array();
						//echo "SELECT * FROM `vehicle_type` where deleted_at is null and min_capacity>=CAST(".$total_weight_of_this_bag." AS DECIMAL(7,2) ) or max_capacity<=CAST(".$total_weight_of_this_bag." AS DECIMAL(7,2) );";exit;
                        //$vehicles = $this->db->query("SELECT * FROM `vehicle_type` where deleted_at is null and min_capacity>=CAST(".$total_weight_of_this_bag." AS DECIMAL(7,2) ) or max_capacity_end<=CAST(".$total_weight_of_this_bag." AS DECIMAL(7,2) )")->result_array();
						$vehicles = $this->db->query("SELECT id, name, `desc`, min_capacity, max_capacity_end FROM vehicle_type WHERE deleted_at is null order by id ASC;")->result_array();
                        if(! empty($vehicles)){
							usort($weights_of_products_single_dimenstion, function($a, $b) {
                                return  $b['weight'] - $a['weight'];
                            });
							
                            $watch_capacity = 1;
                            if(count($weights_of_products_single_dimenstion) != 0 && $watch_capacity != 0) {
                                $max_wight = max(array_column($weights_of_products_single_dimenstion, 'weight'));
                                 $total_weight = array_sum(array_column($weights_of_products_single_dimenstion, 'weight'));
                            
							
                            foreach ($vehicles as $v_k => $vehicle){
								
								
										if(floatval($vehicle['min_capacity']) < floatval($total_weight) && floatval($vehicle['max_capacity_end']) >= floatval($total_weight)){
                                $vehicles[$v_k]['delivery_fee'] = [];
							
                                $this->db->reset_query();
                                if (! empty($shipping_address)) { 
                                    // print_r($shipping_address);
                                                                      
									if (empty($vehicles[$v_k]['delivery_fee']) && ! empty($v_address_data->state) && ! empty($v_address_data->district) && ! empty($v_address_data->constituency)) {
										// echo "all";
										$ve_id=$vehicle['id'];
										//$ship_state=$shipping_address['state_id'];
										//$ship_district=$shipping_address['district_id'];
										//$ship_constituency=$shipping_address['constituency_id'];                                  
										$query1 = $this->db->query('SELECT * FROM delivery_fee where vehicle_type_id="'.$ve_id.'" and state_id="'.$v_address_data->state.'" and district_id="'.$v_address_data->district.'" and constituency_id="'.$v_address_data->constituency.'"'." and deleted_at is null");
										$result1=$query1->num_rows();
										if($result1 >0)
										{
										$this->db->where('vehicle_type_id', $vehicle['id']);
                                        $this->db->where('state_id', $v_address_data->state);
                                        $this->db->where('district_id', $v_address_data->district);
                                        $this->db->where('constituency_id', $v_address_data->constituency);
                                        $vehicles[$v_k]['delivery_fee'] = $this->delivery_fee_model->fields('id, flat_rate, per_km,  nc_flat_rate, nc_per_km, vehicle_type_id,flat_distance,constituency_km,vendor_to_user_max_distance')->get();
										// print_r($vehicles[$v_k]['delivery_fee']);
										}
										else{                                            
											$query2 = $this->db->query('SELECT * FROM delivery_fee where vehicle_type_id="'.$ve_id.'" and state_id="'.$v_address_data->state.'" and district_id="'.$v_address_data->district.'"'." and deleted_at is null");
											$result2=$query2->num_rows();
											if($result2 >0)
											{
											$this->db->where('vehicle_type_id', $vehicle['id']);
											$this->db->where('state_id', $v_address_data->state);
											$this->db->where('district_id', $v_address_data->district);
											$vehicles[$v_k]['delivery_fee'] = $this->delivery_fee_model->fields('id, flat_rate, per_km,  nc_flat_rate, nc_per_km, vehicle_type_id,flat_distance,constituency_km,vendor_to_user_max_distance')->get();
											//print_r($vehicles[$v_k]['delivery_fee']);
											}
											else{                                                
											$query3 = $this->db->query('SELECT * FROM delivery_fee where vehicle_type_id="'.$ve_id.'" and state_id="'.$v_address_data->state.'"'." and COALESCE(`district_id`,0) = 0 and COALESCE(`constituency_id`,0) = 0 and deleted_at is null");
											$result3=$query3->num_rows();
											if($result3 >0)
											{
											$this->db->where('vehicle_type_id', $vehicle['id']);
											$this->db->where('state_id', $v_address_data->state);
											$vehicles[$v_k]['delivery_fee'] = $this->delivery_fee_model->fields('id, flat_rate, per_km,  nc_flat_rate, nc_per_km, vehicle_type_id,flat_distance,constituency_km,vendor_to_user_max_distance')->get();
											//print_r($vehicles[$v_k]['delivery_fee']);
											//print_r($this->db->last_query());
											}
											}
										}
                                    }
									
									elseif (empty($vehicles[$v_k]['delivery_fee']) && ! empty($v_address_data->state) && ! empty($v_address_data->district)) {
                                      //  echo "state and dist";
										$this->db->where('vehicle_type_id', $vehicle['id']);
                                        $this->db->where('state_id', $v_address_data->state);
                                        $this->db->where('district_id', $v_address_data->district);
                                        $this->db->where('constituency_id', NULL);
                                        $vehicles[$v_k]['delivery_fee'] = $this->delivery_fee_model->fields('id, flat_rate, per_km,  nc_flat_rate, nc_per_km, vehicle_type_id,flat_distance,constituency_km,vendor_to_user_max_distance')->get();
										//print_r($vehicles[$v_k]['delivery_fee']);
                                    }
									
									elseif (! empty($v_address_data->constituency)) {
                                        //echo "constituency_id";
										$vehicles[$v_k]['delivery_fee'] = $this->delivery_fee_model->fields('id, flat_rate, per_km,  nc_flat_rate, nc_per_km, vehicle_type_id,flat_distance,constituency_km,vendor_to_user_max_distance')
                                        ->where('vehicle_type_id', $vehicle['id'])
                                        ->where('constituency_id', $v_address_data->constituency)->get();
									//	print_r($vehicles[$v_k]['delivery_fee']);
                                    }
									
									elseif (empty($vehicles[$v_k]['delivery_fee']) && ! empty($v_address_data->district)) {
                                      //  echo "dist_id";
										$this->db->where('vehicle_type_id', $vehicle['id']);
                                        $this->db->where('district_id', $v_address_data->district);
                                        $this->db->where('constituency_id', NULL);
                                        $vehicles[$v_k]['delivery_fee'] = $this->delivery_fee_model->fields('id, flat_rate, per_km,  nc_flat_rate, nc_per_km, vehicle_type_id,flat_distance,constituency_km,vendor_to_user_max_distance')->get();
									//	print_r($vehicles[$v_k]['delivery_fee']);
                                    }
									
									else {
                                  //      echo "state_id";
										$this->db->where('vehicle_type_id', $vehicle['id']);
                                        $this->db->where('state_id', $v_address_data->state);
                                        $this->db->where('district_id', NULL);
                                        $this->db->where('constituency_id', NULL);
                                        $vehicles[$v_k]['delivery_fee'] = $this->delivery_fee_model->fields('id, flat_rate, per_km,  nc_flat_rate, nc_per_km, vehicle_type_id,flat_distance,constituency_km,vendor_to_user_max_distance')->get();
								//		print_r($vehicles[$v_k]['delivery_fee']);
                                    }
									
									//print_r($this->db->last_query());exit;
									
                                    $delivery_fee = $vehicles[$v_k]['delivery_fee']['flat_rate'] + $vehicles[$v_k]['delivery_fee']['nc_flat_rate'];
                                    $db_delivery_fee = $vehicles[$v_k]['delivery_fee']['flat_rate'];
                                    $nc_delivery_fee = $vehicles[$v_k]['delivery_fee']['nc_flat_rate'];
                                    $delivery_per_km = $vehicles[$v_k]['delivery_fee']['per_km'] + $vehicles[$v_k]['delivery_fee']['nc_per_km'];
                                    $db_delivery_per_km = $vehicles[$v_k]['delivery_fee']['per_km'];
                                    $nc_delivery_per_km = $vehicles[$v_k]['delivery_fee']['nc_per_km'];
									$delivery_flat_distance=$vehicles[$v_k]['delivery_fee']['flat_distance'];
                                    $vendor_to_user_max_distance = $vehicles[$v_k]['delivery_fee']['vendor_to_user_max_distance'];
									$delivery_constituency_distance=$vehicles[$v_k]['delivery_fee']['constituency_km'];
                                    $is_this_permissible_wight['status'] = TRUE;
                                } else {
                                    $vehicles[$v_k]['delivery_fee'] = [];
								}
								}
                            }
							}
                            //echo "fghfghfhgfhgf";
							//		echo $delivery_fee;
									$batch = [];
                            $delivey_fee_sum = 0;
                            $db_delivery_fee_sum = 0;
                            $nc_delivery_fee_sum = 0;
                            usort($weights_of_products_single_dimenstion, function($a, $b) {
                                return  $b['weight'] - $a['weight'];
                            });
							
                            $watch_capacity = 1;
                            while (count($weights_of_products_single_dimenstion) != 0 && $watch_capacity != 0) {
                                $max_wight = max(array_column($weights_of_products_single_dimenstion, 'weight'));
                                $total_weight = array_sum(array_column($weights_of_products_single_dimenstion, 'weight'));
                                foreach ($vehicles as $v){
									if(floatval($v['min_capacity']) < floatval($total_weight) && floatval($v['max_capacity_end']) >= floatval($total_weight)){
									   
                                        $b = [
                                            'vehicle' => $v,
                                            'bag_weight' => 0,
                                            'delivery_fee' => $vehicles[$v_k]['delivery_fee'],
                                            'products' => []
                                        ];
										
                                        foreach ($weights_of_products_single_dimenstion as $weight_key => $val) {
                                            // if (count($b['products']) == 0 || array_sum(array_column($b['products'], 'weight')) + $weights_of_products_single_dimenstion[$weight_key]['weight'] <= floatval($v['max_capacity_end'])) {
                                                array_push($b['products'], $weights_of_products_single_dimenstion[$weight_key]);
                                                unset($weights_of_products_single_dimenstion[$weight_key]);
                                            // }
                                        }
											
                                        if(! empty($b['products'])){
                                            $b['bag_weight'] = array_sum(array_column($b['products'], 'weight'));
                                            array_push($batch, $b);
                                            $delivey_fee_sum = $delivery_fee;
                                            $db_delivery_fee_sum = $db_delivery_fee;
                                            $nc_delivery_fee_sum = $nc_delivery_fee;
                                        }
                                   }else {
                                       $watch_capacity = 0;
                                   }
                                }
                            }
                        } else {
                            $batch = [];
                        }
                    }else {
                        $batch = [];
                    }
					//delivery_fee calculation start
					$shipping_address_id=$shipping_address['id'];
					 $vendor_address_id=$vendor['id'];
				//$vendor_address_data = $this->users_address_model
                //->with_location('fields: id, latitude, longitude, address')->where('id', $vendor_address_id)->get();
				$query=$this->db->query("SELECT id,lat as latitude,lng as longitude, location as address FROM `vendor_address` where list_id='".$vendor_address_id."'");
				$vendor_address_data=$query->result();
//print_r($this->db->last_query());
                $shipping_address_data = $this->users_address_model
                     ->with_location('fields: id, latitude, longitude, address')->where('id', $shipping_address_id)->get();
				foreach ($query->result() as $row)
				{
					     $vendor_address_data['latitude'] = $row->latitude;
					     $vendor_address_data['longitude'] = $row->longitude;
				}
				$distance =  haversineGreatCircleDistance($vendor_address_data['latitude'], $vendor_address_data['longitude'], $shipping_address_data['location']['latitude'],$shipping_address_data['location']['longitude']);
                $gmap_distance =  getGoogleMapDistance($vendor_address_data['latitude'], $vendor_address_data['longitude'], $shipping_address_data['location']['latitude'],$shipping_address_data['location']['longitude']);
                // $data['test1'] = $vendor_address_data;
                // $data['test2'] = $shipping_address_data;
                if(empty($gmap_distance)) $gmap_distance = $distance;
                
				$remDisatnce =ceil($gmap_distance - $delivery_flat_distance);
                $data['vendor_address_data'] = $vendor_address_data;
                $data['shipping_address_data'] = $shipping_address_data;
                // $data['distance'] = $distance;
                // $data['remDisatnce'] = $remDisatnce;
                // $data['gmap_distance'] = $gmap_distance;
				//echo "dis".$distance." del_flat".$delivery_flat_distance;
				//echo "rem".$remDisatnce;
				// $max_order_distance =	$this->setting_model->where("key", 'max_order_distance')->get();
                $max_order_distance =	$vendor_to_user_max_distance;
					if($this->input->post("dalivery_mode_id") == 2 && (floatval($gmap_distance) > $max_order_distance))
					{

						$data['long_dis']=1;
					}
					else{
						$data['long_dis']=0;
						
					}

                    if($remDisatnce>0)
						{
							   $delivey_fee_sum = $delivey_fee_sum  + ($remDisatnce * $delivery_per_km);
                               $db_delivery_fee_sum = $db_delivery_fee_sum  + ($remDisatnce * $db_delivery_per_km);
                               $nc_delivery_fee_sum = $nc_delivery_fee_sum  + ($remDisatnce * $nc_delivery_per_km);
						}
						else{
						       $delivey_fee_sum= $delivey_fee_sum;
                               $db_delivery_fee_sum= $db_delivery_fee_sum;
                               $nc_delivery_fee_sum= $nc_delivery_fee_sum;
						}
						
					//delivery_fee calculation end
					
					$set_delivery_fee = $delivey_fee_sum;
                    $set_db_delivery_fee = $db_delivery_fee_sum;
                    $set_nc_delivery_fee = $nc_delivery_fee_sum;
					
					$is_cupon_discount_available = 0;
					$set_delivery_fee_discount = 0;
						
					if($this->input->post('cupon_id')) {
						if(empty($used_cupon)) {
							$set_delivery_fee = 0;
							$is_cupon_discount_available = 1;
							$set_delivery_fee_discount = $delivey_fee_sum;
							
						}
					}
					
					$max_order_weight = $this->setting_model->where('key','max_order_weight')->get()['value'];
					
                    $data['products_group_by_vendor'][$key]['vendor_details'] = empty($vendor) ? NULL : $vendor;
                    $data['products_group_by_vendor'][$key]['total_weight_of_this_bag'] = $total_weight_of_this_bag;
                    $data['products_group_by_vendor'][$key]['order_disable'] = $total_weight_of_this_bag > $max_order_weight ? true : false;
					$data['products_group_by_vendor'][$key]['max_order_weight'] = $max_order_weight;

                    $data['products_group_by_vendor'][$key]['delivery_fee'] = $set_delivery_fee;
                    $data['products_group_by_vendor'][$key]['delivery_boy_delivery_fee'] = $set_db_delivery_fee;
                    $data['products_group_by_vendor'][$key]['nc_delivery_fee'] = $set_nc_delivery_fee;
                    $data['products_group_by_vendor'][$key]['actual_distance'] = $distance;
                    $data['products_group_by_vendor'][$key]['gmap_distance'] = $gmap_distance;
                    $data['products_group_by_vendor'][$key]['is_cupon_discount_available'] = $is_cupon_discount_available;
					$data['products_group_by_vendor'][$key]['delivery_fee_discount'] = $set_delivery_fee_discount;
                    $data['products_group_by_vendor'][$key]['is_this_permissible_wight'] = $is_this_permissible_wight;
                    $data['products_group_by_vendor'][$key]['products'] = array_values($product);
                    $data['products_group_by_vendor'][$key]['sub_orders'] = $batch;
                    $data['shipping_address']= empty($shipping_address)? NULL : $shipping_address;
					
                }
                $data['products_group_by_vendor'] = array_values($data['products_group_by_vendor']);
				$data['cod_max_amount'] = $this->setting_model->where("key", 'max_amount')->get_all();
                $data['vendor_to_user_max_distance'] = $vendor_to_user_max_distance;

                $this->set_response_simple($data, 'success', REST_Controller::HTTP_OK, TRUE);
            }
        }
    }


    
    public function sub_order_batching_based_on_vehicle($shipping_address = [], $vehicle = [], $weights_of_products_single_dimenstion){
        if (! empty($shipping_address)) {
            if (! empty($shipping_address['constituency_id'])) {
                $this->db->where('constituency_id', $shipping_address['constituency_id']);
            } elseif (! empty($shipping_address['disctrict_id'])) {
                $this->db->where('disctrict_id', $shipping_address['disctrict_id']);
                $this->db->where('constituency_id', NULL);
            } elseif (! empty($shipping_address['state_id'])) {
                $this->db->where('state_id', $shipping_address['state_id']);
                $this->db->where('disctrict_id', NULL);
                $this->db->where('constituency_id', NULL);
            }
            if (! empty($vehicle)) {
                $this->db->where('vehicle_type_id', $vehicle[0]['id']);
                $delivery_fee = $this->delivery_fee_model->fields('id, flat_rate, per_km,  nc_flat_rate, nc_per_km, vehicle_type_id')->get();
            } else {
                $delivery_fee = NULL;
            }
        } else {
            $delivery_fee = NULL;
        }
        $batch = [
            'delivey_fee_sum' => 0,
            'bag' =>[]
        ];
        while (count($weights_of_products_single_dimenstion) != 0) {
            $b = [
                'delivery_fee' => $delivery_fee,
                'products' => []
            ];
            $batch['delivey_fee_sum'] += $delivery_fee['flat_rate'];
            foreach ($weights_of_products_single_dimenstion as $key => $val) {
                if (count($b['products']) == 0 || array_sum(array_column($b['products'], 'weight')) + $weights_of_products_single_dimenstion[$key]['weight'] <= floatval($vehicle['max_capacity_end'])) {
                    array_push($b['products'], $weights_of_products_single_dimenstion[$key]);
                    unset($weights_of_products_single_dimenstion[$key]);
                }
            }
            array_push($batch['bag'], $b);
        }
        return $batch;
    }

    /**
     * To get list of orders
     *
     * @author Mehar
     *        
     * @param string $type
     */
    public function order_statuses_list_get()
    {}
    
    
    /**
     * @desc user can reach the delivery boy when the order rejected by DB
     * @author Mehar
     * 
     */
    public function cancel_dj_rejection_get(){
        $rejection_id = $this->input->get('id');
        $rejection_request = $this->delivery_job_rejection_model
        ->with_delivery_job('fields: id, ecom_order_id')
        ->where('id', $rejection_id)->get();
        if($rejection_request){
            $job = $this->delivery_job_model
            ->with_order('fields: id, track_id')
            ->where('id', $rejection_request['job_id'])
            ->get();
            $is_rejection_cancelled = $this->delivery_job_rejection_model->update([
                'id' =>  $rejection_id,
                'status' => 3
            ], 'id');
            if($is_rejection_cancelled){
                $this->delivery_job_model->update([
                    'id' => $rejection_request['job_id'],
                    'status' => $rejection_request['current_order_status']
                ], 'id');
                /**
                 * trigger push notificatios *
                 */
                $this->send_notification($rejection_request['rejected_by'], DELIVERY_APP_CODE, "Order status of( " . $job['order']['track_id'] . " )", "Congrats, Customer is available now", [
                    'order_id' => $job['order']['id'],
                    'notification_type' => $this->notification_type_model->where([
                        'app_details_id' => DELIVERY_APP_CODE,
                        'notification_code' => 'OD'
                    ])->get()
                ]);
            }
            $this->set_response_simple(NULL, "Success", REST_Controller::HTTP_OK, TRUE);
        }else {
            $this->set_response_simple(NULL, "Rejection request is not found", REST_Controller::HTTP_OK, FALSE);
        }
    }

    /**
     * To handle orders from main application
     *
     * @author Mehar
     *        
     * @param string $type
     */
    public function orders_post($type = 'c')
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if ($type == 'c') {
            $this->form_validation->set_rules($this->ecom_order_model->rules['create']);
            $this->ecom_order_model->user_id = $token_data->id;
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_OK, FALSE);
            } else {
                $bags = $this->input->post('bags');

                $this->db->trans_start();
				if(!empty($this->input->post('cupon_id'))){

                                $check_cupon_exist = $this->cupons_model->where(['id' => $this->input->post('cupon_id')])->get();
                                if(!empty($check_cupon_exist)){
									$is_available = $this->used_cupons_model->where([
														 'cupon_id' => $check_cupon_exist['id'],
															'user_id' => $token_data->id,
															'used_date' => date('Y-m-d')
														])->get();
										if(empty($is_available)) {
                                    $promoupdate = $this->used_cupons_model->insert([
                                        'user_id' => $token_data->id,
                                        'created_user_id' => $token_data->id,
                                        'updated_user_id' => $token_data->id,
                                        'cupon_discount' => $this->input->post('cupon_discount'),
                                        'cupon_id' => $check_cupon_exist['id'],
                                        'cupon_code' => $check_cupon_exist['code'],
                                        'used_date' => date('Y-m-d'),
                                   ]);
                                }
                            }
				}
                // $this->db->db_debug = false;
                foreach ($bags as $key => $bag) {
                    $order_track = generate_order_track_id($bag['vendor_user_id']);
                    $delivery_gst_percentage =	$this->setting_model->where("key", 'ecom_delivery_partner_earning_gst_percentage')->get();
                    //calculate delivery fee with and  without gsts                    
                    $delivery_fee_without_gst = round($bag['delivery_fee'] / (1+ $delivery_gst_percentage['value'] / 100), 2);                    
                    $delivery_fee_gst_value = $bag['delivery_fee'] - $delivery_fee_without_gst;

                    $delivery_boy_delivery_fee_without_gst = round($bag['delivery_boy_delivery_fee'] / (1+ $delivery_gst_percentage['value'] / 100), 2); 
                    $delivery_boy_delivery_fee_gst_value =  $bag['delivery_boy_delivery_fee'] - $delivery_boy_delivery_fee_without_gst;

                    $nc_delivery_fee_without_gst = round($bag['nc_delivery_fee'] / (1+ $delivery_gst_percentage['value'] / 100), 2); 
                    $nc_delivery_fee_gst_value =  $bag['nc_delivery_fee'] - $nc_delivery_fee_without_gst;

                    $order_id = $this->ecom_order_model->insert([
                        'track_id' => $order_track,
                        'shipping_address_id' => ! empty($this->input->post('shipping_address_id')) ? $this->input->post('shipping_address_id') : NULL,
                        'delivery_mode_id' => $this->input->post('delivery_mode_id'),
                        'payment_id' => $this->input->post('payment_id'),
                        'promocode_id' => $this->input->post('promo_id'),
                        'cupon_id' => $this->input->post('cupon_id') ? $this->input->post('cupon_id') : 0,
                        'cupon_discount' => $this->input->post('cupon_discount') ? $this->input->post('cupon_discount') : 0,
                        'promocode_discount' => $this->input->post('promo_discount'),
                        'delivery_fee' => $bag['delivery_fee'],
                        'delivery_fee_without_gst' => $delivery_fee_without_gst,
                        'delivery_fee_gst_value' => $delivery_fee_gst_value,
                        'delivery_boy_delivery_fee_without_gst' => $delivery_boy_delivery_fee_without_gst,
                        'delivery_boy_delivery_fee_gst_value' => $delivery_boy_delivery_fee_gst_value,
                        'nc_delivery_fee_without_gst' => $nc_delivery_fee_without_gst,
                        'nc_delivery_fee_gst_value' => $nc_delivery_fee_gst_value,
                        'delivery_fee_id' => $bag['delivery_fee_id'],
                        'flat_rate' => $bag['flat_rate'],
                        'per_km' => $bag['per_km'],
                        'nc_flat_rate' => $bag['nc_flat_rate'],
                        'nc_per_km' => $bag['nc_per_km'],
                        'constituency_km' => $bag['constituency_km'],
                        'vehicle_type_id' => $bag['vehicle_type_id'],
                        'flat_distance' => $bag['flat_distance'],
                        'delivery_boy_delivery_fee' => $bag['delivery_boy_delivery_fee'],
                        'nc_delivery_fee' => $bag['nc_delivery_fee'],
                        'delivery_gst_percentage' => $delivery_gst_percentage['value'],
                        'vehicle_type' => $bag['vehicle_type'],
                        'total' => $bag['total'],
                        'used_wallet_amount' => $bag['used_wallet_amount'],
                        'vendor_user_id' => $bag['vendor_user_id'],
                        'actual_distance' => $bag['actual_distance'],
                        'gmap_distance' => $bag['gmap_distance'],
                        'order_status_id' => $this->ecom_order_status_model->fields('id')
                            ->where([
                            'delivery_mode_id' => $this->input->post('delivery_mode_id'),
                            'serial_number' => 100
                        ])->get()['id'],
                        'current_order_status_id' => ORDER_STATUS_ORDER_IS_PLACED_ID,
                    ]);
                    

                    $is_error = 0;

                    if (! empty($order_id) && ! empty($bag['products'])) {
                        foreach ($bag['products'] as $product) {
                            $is_inserted = $this->ecom_order_deatils_model->insert([
                                'ecom_order_id' => $order_id,
                                'promocode_id' => ! empty($product['promocode_id'])? $product['promocode_id'] : NULL,
                                'promotion_banner_id' => ! empty($product['promotion_banner_id'])? $product['promotion_banner_id'] : NULL,
                                'item_id' => $product['item_id'],
                                'vendor_product_variant_id' => $product['vendor_product_variant_id'],
                                'qty' => $product['qty'],
                                'offer_product_id' => ! empty($product['offer_product_id'])? $product['offer_product_id'] : NULL,
                                'offer_product_variant_id' => ! empty($product['offer_product_variant_id'])? $product['offer_product_variant_id'] : NULL,
                                'offer_product_qty' => ! empty($product['offer_product_qty'])? $product['offer_product_qty'] : NULL,
                                'price' => $product['price'],
                                'rate_of_discount' => $product['rate_of_discount'],
                                'sub_total' => $product['sub_total'],
                                'discount' => $product['discount'],
                                'promocode_discount' => ! empty($product['promocode_discount'])? $product['promocode_discount'] : NULL,
                                'promotion_banner_discount' => ! empty($product['promotion_banner_discount'])? $product['promotion_banner_discount'] : NULL,
                                'tax' => $product['tax'],
                                'total' => $product['total'],
                            ]);
                            if (! $is_inserted) {
                                $is_error ++;
                            }
                        
                        if ($is_error > 0) {
                            $this->db->trans_rollback();
                            $this->set_response_simple(NULL, "Order failed due to insufficient stock", REST_Controller::HTTP_OK, FALSE);
                            break;
                        } else {
                            /**
                             * delete cart *
                             */
                            $this->food_cart_model->where('vendor_product_variant_id', array_column($bag['products'], 'vendor_product_variant_id'))
                                ->where('created_user_id', $token_data->id)
                                ->delete();

                            /**
                             * trigger push notificatios *
                             */
                            $this->send_notification($bag['vendor_user_id'], VENDOR_APP_CODE, "Order Alert", "You have received an New Order(id:" . $order_track . ") successfully.", [
                                'order_id' => $order_id,
                                'notification_type' => $this->notification_type_model->where([
                                    'app_details_id' => VENDOR_APP_CODE,
                                    'notification_code' => 'ODC'
                                ])
                                    ->get()
                            ]);
							
							 $this->send_notification($token_data->id, USER_APP_CODE, "Order Alert", "Congratulations! Your New Order(id:" . $order_track . ") has been placed successfully.", [
                                'order_id' => $order_id,
                                'order_type' => 'ecom_order',
                                'order_track_id' => $order_track,
                                'notification_type' => $this->notification_type_model->where([
                                    'app_details_id' => USER_APP_CODE,
                                    'notification_code' => 'ODC'
                                ])
                                    ->get()
                            ]);
                            /**
                             * update promocodes uses count in used_promo_codes table
                             */
                            if(!empty($product['promocode_id'])){
                                $check_promo_exist = $this->used_promo_codes_model->fields('id,uses')->where(['promo_id' => $product['promocode_id']])->get();
                                if(!empty($check_promo_exist)){
                                    $promoupdate = $this->used_promo_codes_model->update([
                                        'id' => $check_promo_exist['id'],
                                        'user_id' => $token_data->id,
                                        'status' => 1,
                                        // 'uses' => $check_promo_exist['uses'] + 1
                                   ], 'id');
                                }
                            }
							

                            /**
                             * Update order id in payments table *
                             */
                            $this->ecom_payment_model->update([
                                'id' => $this->input->post('payment_id'),
                                'ecom_order_id' => $order_id
                            ], 'id');

                            /**
                             * Dt: 13/08/2021
                             * Added to fetch Payment Medhd Info
                             */
                            $ecomPaymentInfo= $this->ecom_payment_model->get([
                                "id" => $this->input->post('payment_id')
                            ]);

                            if ($ecomPaymentInfo["payment_method_id"] == 2) {
                                $txn_id = 'NC-' . generate_trasaction_no();
                                $amount = floatval($bag['total']);
                                $this->user_model->payment_update($this->config->item('super_admin_user_id'), $amount, 'CREDIT', "wallet", $txn_id, $order_id);
                            }elseif ($ecomPaymentInfo["payment_method_id"] == 3){
                                $txn_id = 'NC-' . generate_trasaction_no();
                                $amount = floatval($bag['total']);
                                $this->user_model->payment_update($this->ecom_order_model->user_id, $amount, 'DEBIT', "wallet", $txn_id, $order_id);
                                $txn_id = 'NC-' . generate_trasaction_no();
                                $amount = floatval($bag['total']);
                                $this->user_model->payment_update($this->config->item('super_admin_user_id'), $amount, 'CREDIT', "wallet", $txn_id, $order_id);
                            }
                            $this->db->trans_complete();
                            $this->set_response_simple(NULL, "Success", REST_Controller::HTTP_OK, TRUE);
                        }
						}
                    } else {
                        break;
                        $this->set_response_simple(NULL, "Something went wrong with order", REST_Controller::HTTP_OK, FALSE);
                    }
                }
            }
        } elseif ($type == 'order_history') {
            $orders = $this->ecom_order_model->get_orders((! empty($this->input->post('limit'))) ? $this->input->post('limit') : NUll, (! empty($this->input->post('offset'))) ? $this->input->post('offset') : NUll, $token_data->id, NULL, NULL, (empty($this->input->post('last_days'))) ? NULL : $this->input->post('last_days'), (empty($this->input->post('last_years'))) ? NULL : $this->input->post('last_years'), (empty($this->input->post('status'))) ? NULL : $this->input->post('status'), (empty($this->input->post('delivery_boy_status'))) ? NULL : $this->input->post('delivery_boy_status'), FALSE, 'order_history');
            $pickuporders = $this->pickup_orders_model->get_orders((! empty($this->input->post('limit'))) ? $this->input->post('limit') : NUll, (! empty($this->input->post('offset'))) ? $this->input->post('offset') : NUll, $token_data->id, NULL, NULL, (empty($this->input->post('last_days'))) ? NULL : $this->input->post('last_days'), (empty($this->input->post('last_years'))) ? NULL : $this->input->post('last_years'), (empty($this->input->post('status'))) ? NULL : $this->input->post('status'), (empty($this->input->post('delivery_boy_status'))) ? NULL : $this->input->post('delivery_boy_status'), FALSE, 'order_history');
			
            if (! empty($orders) || !empty($pickuporders) ) {
                if(! empty($orders))
                {
                    foreach ($orders as $key => $order) {
						
                        if (! empty($order['payment_id']))
                            $orders[$key]['payment'] = $this->ecom_payment_model->fields('id, txn_id, amount, created_at, message, status')
                                ->with_payment_method('fields: id, name, description')
                                ->where('id', $order['payment_id'])
                                ->get();
                        else
                            $orders[$key]['payment'] = NULL;

                            $orders[$key]['is_ecom_order'] = TRUE;
                            $orders[$key]['is_pickup_order'] = FALSE;
                        
                        if (! empty($order['vendor_user_id']))
                            $orders[$key]['vendor'] = $this->vendor_list_model->fields('id, unique_id, name')
                            ->where('vendor_user_id', $order['vendor_user_id'])
                            ->get();
                        else
                            $orders[$key]['vendor'] = NULL;
    
                        if (! empty($order['order_status_id']))
                            $orders[$key]['order_status'] = $this->ecom_order_status_model->fields('id, delivery_mode_id, status, serial_number')
                                ->where('id', $order['order_status_id'])
                                ->get();
                        else
                            $orders[$key]['order_status'] = NULL;
                    }   
                }
                if(!empty($pickuporders))
                {
                    foreach ($pickuporders as $key => $order) {
                        if (! empty($order['payment_id']))
                            $pickuporders[$key]['payment'] = $this->ecom_payment_model->fields('id, txn_id, amount, created_at, message, status')
                                ->with_payment_method('fields: id, name, description')
                                ->where('id', $order['payment_id'])
                                ->get();
                        else
                            $pickuporders[$key]['payment'] = NULL;

                        $pickuporders[$key]['vendor'] = NULL;
                         $pickuporders[$key]['is_ecom_order'] = FALSE;
                         $pickuporders[$key]['is_pickup_order'] = TRUE;
    
                        if (! empty($order['order_status_id']))
                            $pickuporders[$key]['order_status'] = $this->ecom_order_status_model->fields('id, delivery_mode_id, status, serial_number')
                                ->where('id', $order['order_status_id'])
                                ->get();
                        else
                            $pickuporders[$key]['order_status'] = NULL;
                    }   
                }

                $response = [];
                $response['orders']=$orders;
                $response['pickuporders']=$pickuporders;

                $this->set_response_simple($response, 'Success.', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'No orders found.!', REST_Controller::HTTP_OK, TRUE);
            }
        } elseif ($type == 'cancel') {
            $order_id = $this->input->post('order_id');
            if (! empty($order_id)) {
                $order_details = $this->ecom_order_model->fields('id,track_id,vendor_user_id, delivery_mode_id, total')
                    ->with_payment('fields: id, payment_method_id, amount, status, created_user_id')
                    ->where('id', $order_id)
                    ->get();
                if($order_details){
                    if($order_details['payment']['payment_method_id']!=1 || ($order_details['payment']['payment_method_id']==1 &&  $order_details['payment']['status'] ==2)){
                        $this->user_model->debitFromWallet($this->config->item('super_admin_user_id'), $order_details["total"], $order_id);
                    }
                    if($order_details['payment']['payment_method_id']==3){
                        $this->user_model->creditToWallet($order_details['payment']['created_user_id'], $order_details['total'],$order_id);
                    }else if ($order_details['payment']['payment_method_id']==2 || ($order_details['payment']['payment_method_id']==1 && $order_details['payment']['status']==2)){
                        $this->load->module('payment/api/payment');
                        $this->payment->initiateRefund($order_id);
                    }
                }
                $is_delivery_job_started = $this->delivery_job_model->where([
                    'ecom_order_id' => $order_id,
                ])->get();
                if (! empty($order_details) && (empty($is_delivery_job_started) || $is_delivery_job_started['status']<502)) {
                    $is_updated = $this->ecom_order_model->update([
                        'id' => $order_id,
                        'order_status_id' => $this->ecom_order_status_model->fields('id')
                            ->where([
                            'delivery_mode_id' => 1,
                            'serial_number' => 301
                        ])
                            ->get()['id']
                    ], 'id');
                    if(!empty($is_delivery_job_started)){
                        $job_update = $this->delivery_job_model->update([
                            'id' => $is_delivery_job_started['id'],
                            'status' => 500
                        ], 'id');
                        $notificationType = $this->notification_type_model->where(['app_details_id' => DELIVERY_APP_CODE, 'notification_code' => 'OD'])->get();
                        $this->invalidate_notification("ORDER", $notificationType['id'], DELIVERY_APP_CODE, $order_id);
                    }
                    if ($is_updated) {
                        $is_exist = $this->ecom_order_reject_request_model->where('ecom_order_id', $order_id)->get();
                        if($is_exist){
                            $this->ecom_order_reject_request_model->update([
                                'ecom_order_id' => $order_id,
                                'status' => 0
                            ], 'ecom_order_id');
                        }
                        $this->set_response_simple(NULL, 'Order has been cancelled.', REST_Controller::HTTP_OK, TRUE);
                     /**
                             * trigger push notificatios *
                             */
                        $this->send_notification($order_details['vendor_user_id'], VENDOR_APP_CODE, "Order Alert", "Sorry! Your Order(id:" . $order_details['track_id'] . ") has been cancelled by user.", [
                                'order_id' => $order_id,
                                'notification_type' => $this->notification_type_model->where([
                                    'app_details_id' => VENDOR_APP_CODE,
                                    'notification_code' => 'OD'
                                ])->get()
                            ]);
                    } else {
                        $this->set_response_simple(NULL, 'Something went wrong.!', REST_Controller::HTTP_OK, FALSE);
                    }
                } else {
                    $this->set_response_simple(NULL, 'You can not cancel the order at this moment', REST_Controller::HTTP_OK, FALSE);
                }
            } else {
                $this->set_response_simple(NULL, 'Please provide order_id.', REST_Controller::HTTP_OK, FALSE);
            }
        }elseif ($type == 'cancel_a_rejected_order'){
            $orderID = $this->input->post('order_id');
            $orderDetails= [];
            if($orderID){
                $orderDetails = $this->ecom_order_model->getOrderDetailswithPayment($orderID);
            }
            if(! empty($orderDetails)){
                // OrderStatusID means we are collecting the amount from delivery boy. So we are not debiting from super admin.
                if($orderDetails["order_status_id"] == 12){
                    if($orderDetails['payment']['payment_method_id']==3){
                        $this->user_model->creditToWallet($orderDetails['created_user_id'], $orderDetails['total'], $orderID);
                    }else if ($orderDetails['payment']['payment_method_id']==2 || ($orderDetails['payment']['payment_method_id']==1 && $orderDetails['payment']['status']==2)){
                        $this->load->module('payment/api/payment');
                        $this->payment->initiateRefund($orderID);
                    }
                }
                $is_updated = $this->ecom_order_model->update([
                    'id' => $this->input->post('order_id'),
                    'after_rejected_by_delivery_partner' => 1
                ], 'id');
                
                if($is_updated)
                    $this->set_response_simple(NULL, 'Order has been cancelled successfully.', REST_Controller::HTTP_OK, TRUE);
                else 
                    $this->set_response_simple(NULL, 'Failed', REST_Controller::HTTP_OK, FALSE);
                
            }else {
                $this->set_response_simple(NULL, 'Invalid order.', REST_Controller::HTTP_OK, FALSE);
            }
            
        }elseif ($type == 'reorder_a_rejected_order'){
            $is_updated = $this->ecom_order_model->update([
                'id' => $this->input->post('order_id'),
                'after_rejected_by_delivery_partner' => 2
            ], 'id');
            
            if($is_updated)
                $this->set_response_simple(NULL, 'Reordered successfully.', REST_Controller::HTTP_OK, TRUE);
            else 
                $this->set_response_simple(NULL, 'Failed.', REST_Controller::HTTP_OK, FALSE);
        }
    }
    
    /** iwin123
     * @author Mehar
     * @desc To accept rejected orders request.
     */
    public function accept_rejected_orders_post(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $order_id = $this->input->post('order_id');
        if(! empty($order_id)){
            $order_details = $this->ecom_order_model
            ->fields('id, track_id, delivery_mode_id, created_user_id, vendor_user_id, total')
            ->with_payment('fields: id, payment_method_id, amount, status')
            ->with_vendor('fields: id, name')
            ->with_ecom_order_details('fields: id, ecom_order_id, item_id, vendor_product_variant_id, qty, total, cancellation_message, status', 'where: status = 4')
            ->where('id', $order_id)
            ->get();
            if(! empty($order_details['ecom_order_details'])){
                $sum_of_rejcted_products_amount = array_sum(array_column($order_details['ecom_order_details'], 'total'));
                $final_total = floatval($order_details['total']) - floatval($sum_of_rejcted_products_amount);
                $is_order_updated = $this->ecom_order_model->update([
                    'id' => $order_details['id'],
                    'total' => $final_total
                ], 'id');
                if($is_order_updated){
                    $this->ecom_order_reject_request_model->update([
                        'id' => $this->input->post('request_id'),
                        'status' => 2
                    ], 'id');
                    if($order_details['payment']['payment_method_id'] == 1){
                        $this->ecom_payment_model->update([
                            'id' => $order_details['payment']['id'],
                            'amount' => $final_total
                        ], 'id');
                    }elseif ($order_details['payment']['payment_method_id'] == 2){
                        $this->ecom_payment_model->update([
                            'id' => $order_details['payment']['id'],
                            'amount' => $final_total
                        ], 'id');
                        $this->load->module('payment/api/payment');
                        $this->payment->initiateRefund($order_id, TRUE, $sum_of_rejcted_products_amount);
                    }elseif ($order_details['payment']['payment_method_id'] == 3){
                        $txn_id = 'NC-' . generate_trasaction_no();
                        $amount = floatval($sum_of_rejcted_products_amount);
                        $this->user_model->payment_update($this->config->item('super_admin_user_id'), $amount, 'DEBIT', "wallet", $txn_id, $order_id, "Refund on(".$order_details['track_id'].")");
                        $txn_id = 'NC-' . generate_trasaction_no();
                        $amount = floatval($sum_of_rejcted_products_amount);
                        $this->user_model->payment_update($order_details['created_user_id'], $amount, 'CREDIT', "wallet", $txn_id, $order_id, "Refund on(".$order_details['track_id'].")");
                    }
                    
                    $this->send_notification($order_details['vendor_user_id'], VENDOR_APP_CODE, "Order Alert", "Congratulations!  Order reject request of(id:" . $order_details['track_id'] . ") has been accepted by user.", [
                        'order_id' => $order_id,
                        'notification_type' => $this->notification_type_model->where([
                            'app_details_id' => VENDOR_APP_CODE,
                            'notification_code' => 'OD'
                        ])->get()
                    ]);
                }
                $this->set_response_simple(NULL, 'Success.!', REST_Controller::HTTP_OK, TRUE);
            }else {
                $this->set_response_simple(NULL, 'Invalid Order id.!', REST_Controller::HTTP_OK, FALSE);
            }
        }else {
            $this->set_response_simple(NULL, 'Please send order id', REST_Controller::HTTP_OK, FALSE);
        }
    }

    public function stock_settings_post($type = 'r', $target = 0)
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->stock_settings_model->user_id = $token_data->id;

        if ($type == 'c') {

            $this->form_validation->set_rules($this->stock_settings_model->rules['create_rules']);
            
            if ($this->form_validation->run() == FALSE) {

                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_OK, FALSE);
            } else {
                $is_inserted = $this->stock_settings_model->insert([
                    'min_stock' => $this->input->post('min_stock'),
                    'created_user_id' => $token_data->id
                ]);

                if ($is_inserted) {
                    $this->set_response_simple(($is_inserted == FALSE) ? NULL : $is_inserted, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($is_inserted == FALSE) ? NULL : $is_inserted, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        } elseif ($type == 'd') {

            $setting_id = $this->stock_settings_model->get($target);
            if (! empty($setting_id) && $setting_id['created_user_id'] == $token_data->id) {
                $this->stock_settings_model->delete([
                    'id' => $target
                ]);
                $this->set_response_simple(NULL, 'data has deleted..!', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'No privilege to  delete..!', REST_Controller::HTTP_OK, FALSE);
            }
        } elseif ($type == 'u') {
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $this->form_validation->set_rules($this->stock_settings_model->rules['update_rules']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $is_updated = $this->stock_settings_model->update([
                    'id' => $this->input->post('id'),
                    'min_stock' => $this->input->post('min_stock')
                ], 'id');

                if ($is_updated) {
                    $this->set_response_simple(($is_updated == FALSE) ? NULL : $is_updated, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
                } else {
                    $this->set_response_simple(($is_updated == FALSE) ? NULL : $is_updated, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        } elseif ($type == 'r') {
            $query = "SELECT * FROM ecom_settings where created_user_id =". $token_data->id;
            $this->data = $this->db->query($query)->result_array();
            $this->set_response_simple($this->data, 'Stock data List', REST_Controller::HTTP_OK, TRUE);
        }
    }

    public function request_type_post($type = 'r')
    {
        if ($type == 'r') {

            $query = "SELECT * FROM request_type";
            $this->data = $this->db->query($query)->result_array();
            $this->set_response_simple($this->data, 'Stock data List', REST_Controller::HTTP_OK, TRUE);
        }
    }
   
     /**
     * To Handle user addresses
     *
     * @author Uma
     *        
     * @param string $type
     */
    public function user_address_post($type = 'r', $target = 0)
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->users_address_model->user_id = $token_data->id;
        if ($type == 'c') {
            $this->form_validation->set_rules($this->users_address_model->rules['create_rules']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $data1 = $this->location_model->fields('id, address')
                    ->where('latitude', $this->input->post('latitude'))
                    ->where('longitude', $this->input->post('longitude'))
                    ->get();
                    if ($data1 && count(array(
                        $data1
                    )) > 0) {
                        $locid = $data1['id'];
                    } else {
                        $is_geo_inserted = $this->location_model->insert([
                            'latitude' => $this->input->post('latitude'),
                            'longitude' => $this->input->post('longitude'),
                            'address' => $this->input->post('address')
                        ]);
                        $locid = $is_geo_inserted;
                    }
                    $this->users_address_model->update([
                        'created_user_id' => $token_data->id,
                        'status' => 2
                    ], 'created_user_id');
                    
                    $is_inserted = $this->users_address_model->insert([
                        'state_id' => 0,
                        'district_id' => 0,
                        'constituency_id' => 0,
                        'phone' => $this->input->post('mobile'),
                        'email' => $this->input->post('email'),
                        'name' => $this->input->post('name'),
                        'address' => $this->input->post('address'),
                        'landmark' => $this->input->post('landmark'),
                        'pincode' => $this->input->post('pincode'),
                        'location_id' => $locid,
                        'status' => 1
                    ]);
                    if ($is_inserted) {
                        $this->set_response_simple(($is_inserted == FALSE) ? NULL : $is_inserted, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                    } else {
                        $this->set_response_simple(($is_inserted == FALSE) ? NULL : $is_inserted, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                    }
            }
        } elseif ($type == 'd') {

            $user_address = $this->users_address_model->get($target);
            if (! empty($user_address) && $user_address['created_user_id'] == $token_data->id) {
                $this->users_address_model->delete([
                    'id' => $target
                ]);

                $this->set_response_simple(NULL, 'User Address deleted..!', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'No privilege to  delete..!', REST_Controller::HTTP_OK, FALSE);
            }
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->users_address_model->rules['update_rules']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $data1 = $this->location_model->fields('id, address')
                    ->where('latitude', $this->input->post('latitude'))
                    ->where('longitude', $this->input->post('longitude'))
                    ->get();
                if ($data1 && count($data1) > 0) {
                    $locid = $data1['id'];
                } else {
                    $is_geo_inserted = $this->location_model->insert([
                        'latitude' => $this->input->post('latitude'),
                        'longitude' => $this->input->post('longitude'),
                        'address' => $this->input->post('geo_location')
                    ]);
                    $locid = $is_geo_inserted;
                }
                
                if($this->input->post('is_default')){
                    $this->users_address_model->update([
                        'created_user_id' => $token_data->id,
                        'status' => 2
                    ], 'created_user_id');
                }
                $is_updated = $this->users_address_model->update([
                    'id' => $this->input->post('id'),
                    'state_id' =>empty($this->input->post('state_id'))? 0 : $this->input->post('state_id'),
                    'district_id' => empty($this->input->post('district_id'))? 0 : $this->input->post('district_id'),
                    'constituency_id' => empty($this->input->post('constituency_id'))? 0 : $this->input->post('constituency_id'),
                    'phone' => $this->input->post('mobile'),
                    'email' => $this->input->post('email'),
                    'name' => $this->input->post('name'),
                    'address' => $this->input->post('address'),
                    'landmark' => $this->input->post('landmark'),
                    'pincode' => $this->input->post('pincode'),
                    'location_id' => $locid,
                    'status' => $this->input->post('is_default')
                ], 'id');

                if ($is_updated) {
                    $this->set_response_simple(($is_updated == FALSE) ? NULL : $is_updated, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
                } else {
                    $this->set_response_simple(($$is_updated == FALSE) ? NULL : $is_updated, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        } elseif ($type == 'r') {
            if (empty($target)) {
                $users_address = $this->users_address_model
                    ->with_location('fields: id, latitude, longitude, address')
                    ->with_state('fields: id, name')
                    ->with_district('fields: id, state_id, name')
                    ->with_constituency('fields: id, state_id, district_id, name, pincode')
                    ->where('state_id', 0)
                    ->where('created_user_id', $token_data->id)
                    ->order_by('id', 'DESC')
                    ->get_all();
                    foreach ($users_address as $key => $address){
                        $users_address[$key]['is_shipping_available'] = ($vendor['constituency_id'] == $address['constituency']['id'])? 1 : 0;
                    }
                    $this->set_response_simple( empty($users_address)? NULL : $users_address, 'User Addresses List', REST_Controller::HTTP_OK, TRUE);
            } else {
                $users_address = $this->users_address_model
                    ->with_location('fields: id, latitude, longitude, address')
                    ->with_state('fields: id, name')
                    ->with_district('fields: id, state_id, name')
                    ->with_constituency('fields: id, state_id, district_id, name, pincode')
                    ->where('id', $target)
                    ->get();
                    $this->set_response_simple( empty($users_address)? NULL : $users_address, 'User Address Details', REST_Controller::HTTP_OK, TRUE);
            }
        }
    }

   /**
     * To calculate the amount based on weight and category type for pick up and drop the package
     *
     * @author UMA
     */
    public function pickupanddrop_pricing_post()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->users_address_model->user_id = $token_data->id;

        $shipping_address_id = $this->input->post('shipping_address_id');
        $delivery_address_id = $this->input->post('delivery_address_id');
        $category_id = $this->input->post('category');
        $max_wight = $this->input->post('total_weight_inkg') ? $this->input->post('total_weight_inkg') * 1000 : 0;//gms
        
        if (empty($shipping_address_id) || empty($delivery_address_id)) {
            $this->set_response_simple(NULL, 'Sorry, Please select valid delivery_address_id and shipping_address_id.', REST_Controller::HTTP_OK, FALSE);
        } 
        else 
        {
            //$shipping_address = NULL;
           $shipping_address = $this->users_address_model
                ->with_location('fields: id, latitude, longitude, address')->where('id', $shipping_address_id)->get();
                $delivery_address = $this->users_address_model
                     ->with_location('fields: id, latitude, longitude, address')->where('id', $delivery_address_id)->get();

           $pickanddropcategory = $this->pickupcategory_model
                     ->with_location('fields: id, flat_distance, flat_rate, per_km')->where('id', $category_id)->get();

         
            $distance =  haversineGreatCircleDistance($shipping_address['location']['latitude'], $shipping_address['location']['longitude'], 
            $delivery_address['location']['latitude'],$delivery_address['location']['longitude']);
            $gmap_distance =  getGoogleMapDistance($delivery_address['location']['latitude'], $delivery_address['location']['longitude'], $shipping_address['location']['latitude'],$shipping_address['location']['longitude']);
            if(empty($gmap_distance)) $gmap_distance = $gmap_distance;

            $max_order_distance =	$this->setting_model->where("key", 'pickup_address_to_delivery_address_max_distance')->get();
            $pickup_address_to_delivery_address_max_distance = $max_order_distance['value'];
			
            if($pickup_address_to_delivery_address_max_distance >= $gmap_distance){
                //$data['shipping_address']= empty($shipping_address)? NULL : $shipping_address;
                //$data['delivery_address']= empty($delivery_address)? NULL : $delivery_address;

                $vehicle_type = NULL;
                $min_capacity = Null;
                $max_capacity = Null;
                $vehicles = $this->db->query("SELECT id, name, `desc`, min_capacity, max_capacity_end FROM vehicle_type WHERE deleted_at is null order by id ASC;")->result_array();                
                foreach ($vehicles as $v)
                {
                    if(floatval($v['min_capacity']) < floatval($max_wight) && floatval($v['max_capacity_end']) >= floatval($max_wight))
                    {
                         $vehicle_type = $v;
                         break;
                    }
                }
                if(empty($vehicle_type))
                {
                    $this->set_response_simple(NULL, "Weight should be between ".($vehicles[0]['min_capacity']/1000)." kgs to ".$vehicles[0]['max_capacity_end']/1000 . " kgs", REST_Controller::HTTP_OK, FALSE);
                }
                else
                {                    
                    
                    $remDisatnce =ceil($gmap_distance - $pickanddropcategory['flat_distance']);
                    $delivery_fee = $pickanddropcategory['flat_rate'] + $pickanddropcategory['nc_flat_rate'];
                    $db_delivery_fee = $pickanddropcategory['flat_rate'];
                    $nc_delivery_fee = $pickanddropcategory['nc_flat_rate'];
                    $per_km_fee = $pickanddropcategory['per_km'] + $pickanddropcategory['nc_per_km'];

                    if($remDisatnce > 0)
                    {
                        $delivery_fee  = $delivery_fee  + ($remDisatnce * $per_km_fee);
                        $db_delivery_fee = $db_delivery_fee  + ($remDisatnce * $pickanddropcategory['per_km']);
                        $nc_delivery_fee = $nc_delivery_fee  + ($remDisatnce * $pickanddropcategory['nc_per_km']);
                    }
                    

                    $data['vehicle_type'] = $vehicle_type['id'];
                    $data['delivery_distance_in_km'] = round($distance,2);
                    $data['delivery_fee'] = round($delivery_fee,2);

                    //new keys by jvn
                    $data['pickupanddropcategory_id'] = $pickanddropcategory['id'];
                    $data['per_km'] = $pickanddropcategory['per_km'];
                    $data['flat_rate'] = $pickanddropcategory['flat_rate'];
                    $data['nc_flat_rate'] = $pickanddropcategory['nc_flat_rate'];
                    $data['nc_per_km'] = $pickanddropcategory['nc_per_km'];
                    $data['flat_distance'] = $pickanddropcategory['flat_distance'];

                    $data['delivery_boy_delivery_fee'] = $db_delivery_fee;
                    $data['nc_delivery_fee'] = $nc_delivery_fee;
                    $data['actual_distance'] = $distance;
                    $data['gmap_distance'] = $gmap_distance;

                   // $data['delivery_fee'] = round($distance * 15,2);//rs.15 for now later we can read from setting
                    $this->set_response_simple($data, 'success', REST_Controller::HTTP_OK, TRUE);
                }
            }
            else {
                $this->set_response_simple(NULL, 'Selected location is far away to enable this service.!', REST_Controller::HTTP_OK, FALSE);
            }
        }

       
    }

    
    public function pickupanddrop_post($type = 'c')
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
		$_POST = json_decode(file_get_contents("php://input"), TRUE);
        if ($type == 'c') {
            $this->form_validation->set_rules($this->pickup_orders_model->rules['create']);
            $this->pickup_orders_model->user_id = $token_data->id;
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_OK, FALSE);
            } else {
                $this->db->trans_start();
                $order_track = generate_order_track_id($token_data->id);

                $delivery_gst_percentage =	$this->setting_model->where("key", 'ecom_delivery_partner_earning_gst_percentage')->get();
                //calculate delivery fee with and  without gsts                    
                $delivery_fee_without_gst = round($this->input->post('delivery_fee') / (1+ $delivery_gst_percentage['value'] / 100), 2);                    
                $delivery_fee_gst_value = $this->input->post('delivery_fee') - $delivery_fee_without_gst;

                $delivery_boy_delivery_fee_without_gst = round($this->input->post('delivery_boy_delivery_fee') / (1+ $delivery_gst_percentage['value'] / 100), 2); 
                $delivery_boy_delivery_fee_gst_value =  $this->input->post('delivery_boy_delivery_fee') - $delivery_boy_delivery_fee_without_gst;

                $nc_delivery_fee_without_gst = round($this->input->post('nc_delivery_fee') / (1+ $delivery_gst_percentage['value'] / 100), 2); 
                $nc_delivery_fee_gst_value =  $this->input->post('nc_delivery_fee') - $nc_delivery_fee_without_gst;

				$order_id = $this->pickup_orders_model->insert([
                    'track_id' => $order_track,
                    'instructions' => ! empty($this->input->post('instructions')) ? $this->input->post('instructions') : NULL,
                    'pickup_address_id' => ! empty($this->input->post('pickup_address_id')) ? $this->input->post('pickup_address_id') : NULL,
                    'delivery_address_id' => ! empty($this->input->post('delivery_address_id')) ? $this->input->post('delivery_address_id') : NULL,
                    'payment_id' => $this->input->post('payment_id'),
                    'order_pickup_otp' => rand(99999, 999999),
					'order_delivery_otp' => rand(99999, 999999),
                    'delivery_fee' => $this->input->post('delivery_fee'),
                    'vehicle_type' => $this->input->post('vehicle_type'),
                    //'total' => $bag['total'],
                    //'used_wallet_amount' => $bag['used_wallet_amount'],
                    'pickupanddropcategory_id' => ! empty($this->input->post('pickupanddropcategory_id')) ? $this->input->post('pickupanddropcategory_id') : NULL,
                    'per_km' => ! empty($this->input->post('per_km')) ? $this->input->post('per_km') : NULL,
                    'flat_rate' => ! empty($this->input->post('flat_rate')) ? $this->input->post('flat_rate') : NULL,
                    'nc_flat_rate' => ! empty($this->input->post('nc_flat_rate')) ? $this->input->post('nc_flat_rate') : NULL,
                    'nc_per_km' => ! empty($this->input->post('nc_per_km')) ? $this->input->post('nc_per_km') : NULL,
                    'flat_distance' => ! empty($this->input->post('flat_distance')) ? $this->input->post('flat_distance') : NULL,
                    'delivery_boy_delivery_fee' => ! empty($this->input->post('delivery_boy_delivery_fee')) ? $this->input->post('delivery_boy_delivery_fee') : NULL,
                    'nc_delivery_fee' => ! empty($this->input->post('nc_delivery_fee')) ? $this->input->post('nc_delivery_fee') : NULL,
                    'actual_distance' => ! empty($this->input->post('actual_distance')) ? $this->input->post('actual_distance') : NULL,
                    'gmap_distance' => ! empty($this->input->post('gmap_distance')) ? $this->input->post('gmap_distance') : NULL,
                    'delivery_gst_percentage' => $delivery_gst_percentage['value'],
                    'delivery_fee_without_gst' => $delivery_fee_without_gst,
                    'delivery_fee_gst_value' => $delivery_fee_gst_value,
                    'delivery_boy_delivery_fee_without_gst' => $delivery_boy_delivery_fee_without_gst,
                    'delivery_boy_delivery_fee_gst_value' => $delivery_boy_delivery_fee_gst_value,
                    'nc_delivery_fee_without_gst' => $nc_delivery_fee_without_gst,
                    'nc_delivery_fee_gst_value' => $nc_delivery_fee_gst_value,
                    'order_status_id' => $this->ecom_order_status_model->fields('id')
                        ->where([
                        'delivery_mode_id' => 2,
                        'serial_number' => 102
                    ])->get()['id']
                ]);
				
                $this->load->module('delivery/api/delivery');
                $this->delivery->create_pickup_order_id($order_id);
                $this->db->trans_complete();


                 /**
                      * trigger push notificatios *
                  */
                 $this->send_notification_pickupanddrop($token_data->id, USER_APP_CODE, "Order status of( " . $order_track . " )", "Your Pick up and drop Order successfully Sent", [
                            'order_id' => $order_id,
                            'order_type' => 'pickup_order',
                            'order_track_id' => $order_track,
                            'notification_type' => $this->notification_type_model->where([
                                'app_details_id' => USER_APP_CODE,
                                'notification_code' => 'OD'
                     ])
                     ->get()
                ]);
				/* trigger push notification by manoj */
				
				$order = $this->pickup_orders_model->where('id', $order_id)->get();
				if ($order['order_status_id'] == 11) {
					$pickup_address_id=$order['pickup_address_id'];
					$shipping_address = $this->users_address_model
                ->with_location('fields: id, latitude, longitude, address')->where('id', $pickup_address_id)->get();
				

					$lat = $shipping_address['location']['latitude'];
                    $lng = $shipping_address['location']['longitude'];
					
					
					$max_order_distance =	$this->setting_model->where("key", 'max_order_distance')->get();
                    $distance = $max_order_distance['value']; 
					
                    /* $query = $this->db->query('SELECT *, (6371 * 2 * ASIN(SQRT(POWER(SIN(RADIANS(? - ABS(delivery_boy_address.lat))), 2) + COS(RADIANS(?)) * COS(RADIANS(ABS(delivery_boy_address.lat))) * POWER(SIN(RADIANS(? - delivery_boy_address.lng)), 2)))) * 1.60934 AS distance
                    FROM delivery_boy_address HAVING distance < ?
                    ', [
                        $lat,
                        $lat,
                        $lng,
                        $distance
                    ]); */

                    $query = $this->db->query('SELECT delivery_partner_location_tracking.delivery_partner_user_id as user_id
                    , (6371.0088 * 2 * ASIN(SQRT(POWER(SIN(RADIANS(? - delivery_partner_location_tracking.latitude)) / 2, 2) + COS(RADIANS(?)) * COS(RADIANS(delivery_partner_location_tracking.latitude)) * POWER(SIN(RADIANS(? - delivery_partner_location_tracking.longitude)/2), 2)))) AS distance
                    FROM delivery_partner_location_tracking join users on users.id = delivery_partner_location_tracking.delivery_partner_user_id  where users.delivery_partner_status=1 HAVING distance < ?
                    ', [
                        $lat,
                        $lat,
                        $lng,
                        $distance
                    ]);

					$deal = $query->result_array();
					for ($i = 0; $i < count($deal); $i ++) {
						$delivered_id=$deal[$i]['user_id'];
					    $acc = $this->food_order_deal_model->insert([
                            'order_id' => $order_id,
                            'deal_id' => $deal[$i]['user_id']
                        ]);
						
						
						$this->send_notification_pickupanddrop($delivered_id, DELIVERY_APP_CODE, "Order status", "New Order(id:".$order_track.") is Placed.! TRACK NOW",['order_id' => $order['id'], 'notification_type' => $this->notification_type_model->where(['app_details_id' => DELIVERY_APP_CODE, 'notification_code' => 'OD'])->get()]);
                    }
                }
				/* trigger push notification by manoj */
                

                $this->set_response_simple(NULL, "Success", REST_Controller::HTTP_OK, TRUE);
            }
        } elseif ($type == 'order_history') {
            $orders = $this->ecom_order_model->get_orders((! empty($this->input->post('limit'))) ? $this->input->post('limit') : NUll, (! empty($this->input->post('offset'))) ? $this->input->post('offset') : NUll, $token_data->id, NULL, NULL, (empty($this->input->post('last_days'))) ? NULL : $this->input->post('last_days'), (empty($this->input->post('last_years'))) ? NULL : $this->input->post('last_years'), (empty($this->input->post('status'))) ? NULL : $this->input->post('status'), (empty($this->input->post('delivery_boy_status'))) ? NULL : $this->input->post('delivery_boy_status'), FALSE, 'order_history');
            if (! empty($orders)) {
                foreach ($orders as $key => $order) {
                    if (! empty($order['payment_id']))
                        $orders[$key]['payment'] = $this->ecom_payment_model->fields('id, txn_id, amount, created_at, message, status')
                            ->with_payment_method('fields: id, name, description')
                            ->where('id', $order['payment_id'])
                            ->get();
                    else
                        $orders[$key]['payment'] = NULL;
                    
                    if (! empty($order['vendor_user_id']))
                        $orders[$key]['vendor'] = $this->vendor_list_model->fields('id, unique_id, name')
                        ->where('vendor_user_id', $order['vendor_user_id'])
                        ->get();
                    else
                        $orders[$key]['vendor'] = NULL;

                    if (! empty($order['order_status_id']))
                        $orders[$key]['order_status'] = $this->ecom_order_status_model->fields('id, delivery_mode_id, status, serial_number')
                            ->where('id', $order['order_status_id'])
                            ->get();
                    else
                        $orders[$key]['order_status'] = NULL;
                }
                $this->set_response_simple($orders, 'Success.', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'No orders found.!', REST_Controller::HTTP_OK, TRUE);
            }
        } elseif ($type == 'cancel') {
            $order_id = $this->input->post('order_id');
            if (! empty($order_id)) {
                $order_details = $this->ecom_order_model->fields('id,track_id,vendor_user_id, delivery_mode_id, total')
                    ->with_payment('fields: id, payment_method_id, amount, status, created_user_id')
                    ->where('id', $order_id)
                    ->get();
                if($order_details){
                    if($order_details['payment']['payment_method_id']!=1 || ($order_details['payment']['payment_method_id']==1 &&  $order_details['payment']['status'] ==2)){
                        $this->user_model->debitFromWallet($this->config->item('super_admin_user_id'), $order_details["total"], $order_id);
                    }
                    if($order_details['payment']['payment_method_id']==3){
                        $this->user_model->creditToWallet($order_details['payment']['created_user_id'], $order_details['total'],$order_id);
                    }else if ($order_details['payment']['payment_method_id']==2 || ($order_details['payment']['payment_method_id']==1 && $order_details['payment']['status']==2)){
                        $this->load->module('payment/api/payment');
                        $this->payment->initiateRefund($order_id);
                    }
                }
                $is_delivery_job_started = $this->delivery_job_model->where([
                    'ecom_order_id' => $order_id,
                ])->get();
                if (! empty($order_details) && (empty($is_delivery_job_started) || $is_delivery_job_started['status']<502)) {
                    $is_updated = $this->ecom_order_model->update([
                        'id' => $order_id,
                        'order_status_id' => $this->ecom_order_status_model->fields('id')
                            ->where([
                            'delivery_mode_id' => 1,
                            'serial_number' => 301
                        ])
                            ->get()['id']
                    ], 'id');
                    if(!empty($is_delivery_job_started)){
                        $job_update = $this->delivery_job_model->update([
                            'id' => $is_delivery_job_started['id'],
                            'status' => 500
                        ], 'id');
                        $notificationType = $this->notification_type_model->where(['app_details_id' => DELIVERY_APP_CODE, 'notification_code' => 'OD'])->get();
                        $this->invalidate_notification("ORDER", $notificationType['id'], DELIVERY_APP_CODE, $order_id);
                    }
                    if ($is_updated) {
                        $is_exist = $this->ecom_order_reject_request_model->where('ecom_order_id', $order_id)->get();
                        if($is_exist){
                            $this->ecom_order_reject_request_model->update([
                                'ecom_order_id' => $order_id,
                                'status' => 0
                            ], 'ecom_order_id');
                        }
                        $this->set_response_simple(NULL, 'Order has been cancelled.', REST_Controller::HTTP_OK, TRUE);
                     /**
                             * trigger push notificatios *
                             */
                        $this->send_notification($order_details['vendor_user_id'], VENDOR_APP_CODE, "Order Alert", "Sorry! Your Order(id:" . $order_details['track_id'] . ") has been cancelled by user.", [
                                'order_id' => $order_id,
                                'notification_type' => $this->notification_type_model->where([
                                    'app_details_id' => VENDOR_APP_CODE,
                                    'notification_code' => 'OD'
                                ])->get()
                            ]);
                    } else {
                        $this->set_response_simple(NULL, 'Something went wrong.!', REST_Controller::HTTP_OK, FALSE);
                    }
                } else {
                    $this->set_response_simple(NULL, 'You can not cancel the order at this moment', REST_Controller::HTTP_OK, FALSE);
                }
            } else {
                $this->set_response_simple(NULL, 'Please provide order_id.', REST_Controller::HTTP_OK, FALSE);
            }
        }elseif ($type == 'cancel_a_rejected_order'){
            $orderID = $this->input->post('order_id');
            $orderDetails= [];
            if($orderID){
                $orderDetails = $this->ecom_order_model->getOrderDetailswithPayment($orderID);
            }
            if(! empty($orderDetails)){
                // OrderStatusID means we are collecting the amount from delivery boy. So we are not debiting from super admin.
                if($orderDetails["order_status_id"] == 12){
                    if($orderDetails['payment']['payment_method_id']==3){
                        $this->user_model->creditToWallet($orderDetails['created_user_id'], $orderDetails['total'], $orderID);
                    }else if ($orderDetails['payment']['payment_method_id']==2 || ($orderDetails['payment']['payment_method_id']==1 && $orderDetails['payment']['status']==2)){
                        $this->load->module('payment/api/payment');
                        $this->payment->initiateRefund($orderID);
                    }
                }
                $is_updated = $this->ecom_order_model->update([
                    'id' => $this->input->post('order_id'),
                    'after_rejected_by_delivery_partner' => 1
                ], 'id');
                
                if($is_updated)
                    $this->set_response_simple(NULL, 'Order has been cancelled successfully.', REST_Controller::HTTP_OK, TRUE);
                else 
                    $this->set_response_simple(NULL, 'Failed', REST_Controller::HTTP_OK, FALSE);
                
            }else {
                $this->set_response_simple(NULL, 'Invalid order.', REST_Controller::HTTP_OK, FALSE);
            }
            
        }elseif ($type == 'reorder_a_rejected_order'){
            $is_updated = $this->ecom_order_model->update([
                'id' => $this->input->post('order_id'),
                'after_rejected_by_delivery_partner' => 2
            ], 'id');
            
            if($is_updated)
                $this->set_response_simple(NULL, 'Reordered successfully.', REST_Controller::HTTP_OK, TRUE);
            else 
                $this->set_response_simple(NULL, 'Failed.', REST_Controller::HTTP_OK, FALSE);
        }
    }

    public function get_free_delivery_info_get() {
        $data = $this->cupons_model->where('id', 1)->get();
        $data['image'] = base_url().'/uploads/free_delivery_image/'.$data['image'].'?'.time();;
        $this->set_response_simple($data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    public function get_admin_banners_get() {
        $banners = $this->Admin_banners_model->with_position('fields: id, title')->where('status', 1)->get_all();
        if(!empty($banners))  {
            foreach($banners as $key=>$val) {
                $banners[$key]['banner_image'] = base_url().'/uploads/admin_banners/'.$val['banner_image'].'?'.time();
            }
        }
        $this->set_response_simple($banners, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    public function verify_given_phone_no_belonngs_to_vendor_post() {

        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $phone_no = $this->input->post('phone_no');

        $sql = "SELECT * FROM `vendors_list` vl join users u on u.id = vl.vendor_user_id where u.phone = '".$phone_no."'";

        $data = $this->db->query($sql)->row();

        $result = ['status' => false];

        if(isset($data->id)) {
            $result['status'] = true;
        }
        
        $this->set_response_simple($result, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    public function verify_given_phone_no_belonngs_to_delivery_boy_post() {

        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $phone_no = $this->input->post('phone_no');

        $sql = "SELECT * FROM `delivery_boy_address` da join users u on u.id = da.user_id where u.phone = '".$phone_no."'";

        $data = $this->db->query($sql)->row();

        $result = ['status' => false];

        if(isset($data->id)) {
            $result['status'] = true;
        }
        
        $this->set_response_simple($result, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    public function isUserExist_post() {

        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $phone_no = $this->input->post('phone_no');

        $phone_no_esc = $this->db->escape_str($phone_no);        

        $data = $this->db->query("SELECT * FROM users WHERE phone = '{$phone_no_esc}'")->row();
        
        $result = ['status' => false];

        if(isset($data->id) && !empty($data->id)) {
            $result['status'] = true;
        }        
        
        $this->set_response_simple($result, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }
}