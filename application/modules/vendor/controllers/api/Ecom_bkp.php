<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';
use Firebase\JWT\JWT;

class Ecom extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('food_menu_model');
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
        $this->load->model('shop_by_category_model');
        $this->load->model('sub_category_model');
        $this->load->model('payment_method_model');
        $this->load->model('user_model');
        $this->load->model('notifications_model');
        $this->load->model('notification_type_model');
        $this->load->model('vendor_product_variant_model');
        $this->load->model('food_item_image_model');
        $this->load->model('tax_model');
        $this->load->model('ecom_order_model');
        $this->load->model('ecom_payment_model');
    }

    /**
     * To manage shop by category
     *
     * @author Mehar
     *        
     * @param string $type
     * @param number $target
     */
    public function shop_by_category_post($type = 'r', $target = 0)
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->sub_category_model->user_id = $token_data->id;
        if ($type == 'c') {
            $this->form_validation->set_rules($this->sub_category_model->rules['shop_by_category']);
            if (empty($this->input->post('image'))) {
                $this->form_validation->set_rules('image', 'Sub Category Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $id = $this->sub_category_model->insert([
                    'cat_id' => $this->vendor_list_model->where('vendor_user_id', $token_data->id)
                        ->get()['category_id'],
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'status' => 0,
                    'type' => 2
                ]);
                if ($id) {
                    $this->shop_by_category_model->insert([
                        'vendor_id' => $token_data->id,
                        'cat_id' => $this->vendor_list_model->where('vendor_user_id', $token_data->id)
                            ->get()['category_id'],
                        'sub_cat_id' => $id
                    ]);
                    $this->db->insert('vendor_in_active_shop_by_categories', [
                        'sub_cat_id' => $id,
                        'vendor_id' => $token_data->id
                    ]);
                    file_put_contents("./uploads/sub_category_image/sub_category_" . $id . ".jpg", base64_decode($this->input->post('image')));
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        } elseif ($type == 'r') {
            $admin_ids = $this->get_users_by_group(1);
            array_push($admin_ids, $token_data->id);
            if (empty($target)) {
                $query = "SELECT sc.id, sc.cat_id, sc.type, sc.name, sc.desc, sc.status, sbc.vendor_id FROM `shop_by_categories` AS sbc JOIN sub_categories AS sc ON sbc.sub_cat_id = sc.id WHERE sbc.`vendor_id` IN (" . implode(',', $admin_ids) . ") ";
                if (! empty($this->input->post('q'))) {
                    $query .= " AND sc.name LIKE('%" . $this->input->post('q') . "%')";
                }
                $query .= "AND sc.type = 2 and sc.deleted_at is null AND sbc.cat_id=" . $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get()['category_id'] . " ORDER BY sc.id DESC";
                $this->data['sub_categories'] = $this->db->query($query)->result_array();
                foreach ($this->data['sub_categories'] as $key => $sbc) {
                    $this->data['sub_categories'][$key]['image'] = base_url() . 'uploads/sub_category_image/sub_category_' . $sbc['id'] . '.jpg';
                }
                $this->set_response_simple($this->data['sub_categories'], 'shop by categories list', REST_Controller::HTTP_OK, TRUE);
            } else {
                $sub_category = $this->sub_category_model->fields('id, cat_id, name, desc, status')
                    ->where('id', $target)
                    ->with_menus('fields: id, sub_cat_id, name, desc, status', 'where: vendor_id IN(' . implode(',', $admin_ids) . "," . $token_data->id . ')')
                    ->get();
                foreach ($sub_category['menus'] as $key => $menu) {
                    $sub_category['menus'][$key]['image'] = base_url() . 'uploads/food_menu_image/food_menu_' . $menu['id'] . '.jpg';
                }
                $sub_category['image'] = base_url() . 'uploads/sub_category_image/sub_category_' . $target . '.jpg';
                $this->set_response_simple($sub_category, 'shop by category', REST_Controller::HTTP_OK, TRUE);
            }
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->sub_category_model->rules['shop_by_category']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                if ($this->input->post('status') == 1) {
                    $this->db->where([
                        'sub_cat_id' => $this->input->post('id'),
                        'vendor_id' => $token_data->id
                    ]);
                    $this->db->delete('vendor_in_active_shop_by_categories');
                } else {
                    $this->db->insert('vendor_in_active_shop_by_categories', [
                        'sub_cat_id' => $this->input->post('id'),
                        'vendor_id' => $token_data->id
                    ]);
                }
                $subcat = $this->sub_category_model->where('id', $this->input->post('id'))->get();
                if($subcat['created_user_id'] == $token_data->id){
                    $this->sub_category_model->update([
                        'id' => $this->input->post('id'),
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc'),
                        'status' => $this->input->post('status')
                    ], 'id');
                    
                    if (! empty($this->input->post('image'))) {
                        if (! file_exists('uploads/' . 'sub_category' . '_image/')) {
                            mkdir('uploads/' . 'sub_category' . '_image/', 0777, true);
                        }
                        if (! file_exists(base_url() ."uploads/sub_category_image/sub_category_" . $this->input->post('id') . ".jpg")) {
                            unlink(base_url() ."uploads/sub_category_image/sub_category_" . $this->input->post('id') . ".jpg");
                        }
                        file_put_contents("./uploads/sub_category_image/sub_category_" . $this->input->post('id') . ".jpg", base64_decode($this->input->post('image')));
                    }
                }else{
                    $this->sub_category_model->update([
                        'id' => $this->input->post('id'),
                        'status' => $this->input->post('status')
                    ], 'id');
                }
                
                $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
            }
        } elseif ($type == 'd') {
            $sub_category = $this->sub_category_model->get($target);
            if(! empty($sub_category) && $sub_category['created_user_id'] == $token_data->id){
                $this->db->where([
                    'vendor_id' => $token_data->id,
                    'sub_cat_id' => $target
                ]);
                $this->db->delete('shop_by_categories');
                $this->db->where([
                    'vendor_id' => $token_data->id,
                    'sub_cat_id' => $target
                ]);
                $this->db->delete('vendor_in_active_shop_by_categories');
                $this->sub_category_model->delete([
                    'id' => $target
                ]);
                $this->set_response_simple(NULL, 'Shop by category deleted..!', REST_Controller::HTTP_OK, TRUE);
            }else{
                $this->set_response_simple(NULL, 'No privilege to  delete..!', REST_Controller::HTTP_OK, FALSE);
            }
        }
    }

    /**
     * To manage Menu
     *
     * @author Mehar
     *        
     * @param string $type
     * @param number $target11
     */
    public function menus_post($type = 'r', $target = 0)
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->food_menu_model->user_id = $token_data->id;
        if ($type == 'c') {
            $this->form_validation->set_rules($this->food_menu_model->rules);
            if (empty($this->input->post('image'))) {
                $this->form_validation->set_rules('image', 'Food Menu Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $id = $this->food_menu_model->insert([
                    'vendor_id' => $token_data->id,
                    'sub_cat_id' => $this->input->post('sub_cat_id'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc')
                ]);
                if ($id) {
                    file_put_contents("./uploads/food_menu_image/food_menu_" . $id . ".jpg", base64_decode($this->input->post('image')));
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        } elseif ($type == 'r') {
            $admin_ids = $this->get_users_by_group(1);
            array_push($admin_ids, $token_data->id);
            if (empty($target)) {
                $vendor = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get();
                if (! empty($vendor)) {
                    $sub_categories = $this->sub_category_model->fields('id, name, desc, cat_id')
                        ->where('created_user_id', $admin_ids)
                        ->where([
                        'cat_id' => $vendor['category_id'],
                        'type' => 2
                    ])->get_all();

                    if (! empty($sub_categories)) {
                        if (! empty($this->input->post('q'))) {
                            $where = "food_menu.name LIKE('%" . $this->input->post('q') . "%')";
                            $this->db->where($where);
                        }
                        $menus = $this->food_menu_model->fields('id, name, desc')
                            ->with_subcat('fields:id,name')
                            ->where('vendor_id', $admin_ids)
                            ->where('sub_cat_id', array_column($sub_categories, 'id'))
                            ->order_by('id', 'DESC')
                            ->get_all();
                        foreach ($menus as $key => $menu) {
                            $menus[$key]['image'] = base_url() . 'uploads/food_menu_image/food_menu_' . $menu['id'] . '.jpg';
                        }
                        $this->set_response_simple($menus, 'List of menus', REST_Controller::HTTP_OK, TRUE);
                    } else {
                        $this->set_response_simple(NULL, 'No shop by categories found..!', REST_Controller::HTTP_CONFLICT, FALSE);
                    }
                } else {
                    $this->set_response_simple(NULL, 'No vendor found..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            } else {
                $menu = $this->food_menu_model->fields('id, name, desc, status')->with_shop_by_category('fields: id, name, desc')->with_items('fields: id, name', 'where: created_user_id IN('.implode(",", $admin_ids).')')->get($target);
                $menu['image'] = base_url() . 'uploads/food_menu_image/food_menu_' . $target . '.jpg';
                $this->set_response_simple($menu, 'menu details', REST_Controller::HTTP_OK, TRUE);
            }
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->food_menu_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $menu = $this->food_menu_model->get($this->input->post('id'));
                if(! empty($menu) && $menu['created_user_id'] == $token_data->id){
                    $is_updated = $this->food_menu_model->update([
                        'id' => $this->input->post('id'),
                        'sub_cat_id' => $this->input->post('sub_cat_id'),
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc')
                    ], 'id');
    
                    if (! empty($this->input->post('image'))) {
                        if (! file_exists('uploads/' . 'food_menu' . '_image/')) {
                            mkdir('uploads/' . 'food_menu' . '_image/', 0777, true);
                        }
                        if (! file_exists(base_url() ."uploads/food_menu_image/food_menu_" . $this->input->post('id') . ".jpg")) {
                            unlink(base_url() ."uploads/food_menu_image/food_menu_" . $this->input->post('id') . ".jpg");
                        }
                        file_put_contents("./uploads/food_menu_image/food_menu_" . $this->input->post('id') . ".jpg", base64_decode($this->input->post('image')));
                    }
                    $this->set_response_simple($is_updated, 'Menu Updated', REST_Controller::HTTP_ACCEPTED, TRUE);
                }else{
                    $this->set_response_simple(NULL, 'No privilege to Update', REST_Controller::HTTP_OK, TRUE);
                }
                
            }
        } elseif ($type == 'd') {
            $menu = $this->food_menu_model->get($target);
            if(! empty($menu) && $menu['created_user_id'] == $token_data->id){
                $this->food_menu_model->delete([
                    'id' => $target
                ]);
                $this->set_response_simple(NULL, 'Menu deleted..!', REST_Controller::HTTP_OK, TRUE);
            }else{
                $this->set_response_simple(NULL, 'No privilege to  delete..!', REST_Controller::HTTP_OK, TRUE);
            }
        }
    }

    /**
     * To manage product
     *
     * @author Mehar
     * @param string $type
     * @param number $target
     */
    public function products_post($type = 'r', $target = 0)
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->food_item_model->user_id = $token_data->id;
        $this->food_section_model->user_id = $token_data->id;
        $this->food_sec_item_model->user_id = $token_data->id;
        if ($type == 'c') {
            $this->form_validation->set_rules($this->food_item_model->rules);
            if (empty($this->input->post('item_images'))) {
                $this->form_validation->set_rules('item_images', 'Product Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $sounds_like = $this->sounds_like($this->input->post('name'), $this->input->post('shop_by_cat_id'), $this->input->post('menu_id'));
                $shop_by_cat_id = $this->input->post('shop_by_cat_id');
                $menu_id = $this->input->post('menu_id');
                $variants = $this->input->post('variants');
                $brand_id = $this->input->post('brand_id');
                
                $item_id = $this->food_item_model->insert([
                    'sub_cat_id' => $shop_by_cat_id,
                    'menu_id' => $menu_id,
                    'brand_id' => $brand_id,
                    'product_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                    'name' => $this->input->post('name'),
                    'desc' => (empty($this->input->post('desc'))) ? NULL :$this->input->post('desc'),
                    'sounds_like' => $sounds_like,
                    'availability' => 1,
                    'status' => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 3,
                ]);
                if ($item_id) {
                    $section_id = $this->food_section_model->insert([
                        'menu_id' => $menu_id,
                        'item_id' => $item_id,
                        'name' => NULL
                    ]);
                    if($section_id && ! empty($variants)){
                        $section_items = [];
                        foreach ($variants as $key => $variant){
                            array_push($section_items, [
                                'menu_id' => $menu_id,
                                'item_id' => $item_id,
                                'sec_id' => $section_id,
                                'section_item_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                                'price' => (empty($variant['price'])) ? NULL : $variant['price'],
                                'weight' => (empty($variant['weight'])) ? NULL : $variant['weight'],
                                'desc' => (empty($variant['desc'])) ? NULL : $variant['desc'],
                                'name' => $variant['option_name'],
                                'status' => 1
                            ]);
                        }
                        $this->food_sec_item_model->insert($section_items);
                    }
                    
                    if (! empty($this->input->post('item_images'))) {
                        if (! file_exists('uploads/' . 'food_item' . '_image/')) {
                            mkdir('uploads/' . 'food_item' . '_image/', 0777, true);
                        }
                        foreach ($this->input->post('item_images') as $key => $image) {
                            $product_image_id = $this->food_item_image_model->insert([
                                'item_id' => $item_id,
                                'serial_number' => ++ $key,
                                'ext' => 'jpg'
                            ]);
                            file_put_contents("./uploads/food_item_image/food_item_" . $product_image_id . ".jpg", base64_decode($image));
                        }
                    }
                    $this->set_response_simple(($item_id == FALSE) ? FALSE : $item_id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($item_id == FALSE) ? FALSE : $item_id, 'Failed..!', REST_Controller::HTTP_OK, FALSE);
                }
            }
        }elseif ($type == 'list'){
            $admin_ids = $this->get_users_by_group(1);
            array_push($admin_ids, $token_data->id);
            $vendor = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get();
            $shop_by_categories = $this->shop_by_category_model->where('cat_id', $vendor['category_id'])->where('vendor_id', $admin_ids)->get_all();
            $sub_cat_ids = (empty($shop_by_categories))? NULL :array_column($shop_by_categories, 'sub_cat_id');
            $sub_cat_id = $this->input->post('shop_by_cat_id');
            $menu_id = $this->input->post('menu_id');
            $status = $this->input->post('status');
            $search_text = $this->input->post('q');
            
            if (! empty($sub_cat_id))
                $this->db->where('sub_cat_id', $sub_cat_id);
                
            if (! empty($menu_id))
                $this->db->where('menu_id', $menu_id);
                
            if (! empty($search_text)) {foreach (explode(' ', $search_text) as $s){
                $this->db->or_like('sounds_like', metaphone($s));
            }}
            
            if (! empty($status)){
                if($status != 1){
                    $this->db->where('created_user_id', $token_data->id);
                }
                $this->db->where('status', $status);
            }
            
            $all_catalogue_products = $this->food_item_model->where('sub_cat_id', $sub_cat_ids)->count_rows();
            
            if (! empty($sub_cat_id))
                $this->db->where('sub_cat_id', $sub_cat_id);
                
            if (! empty($menu_id))
                $this->db->where('menu_id', $menu_id);
                    
            if (! empty($search_text)) {foreach (explode(' ', $search_text) as $s){
                $this->db->or_like('sounds_like', metaphone($s));
            }}
                    
            if (! empty($status)){
                if($status != 1){
                    $this->db->where('created_user_id', $token_data->id);
                }
                $this->db->where('status', $status);
            }
                
                
                $catalogue_products = $this->food_item_model
                ->with_menu('fields: id, name')
                ->with_sub_category('fields: id, name')
                ->with_item_images('fields: id, item_id, serial_number, ext')
                ->where('sub_cat_id', $sub_cat_ids)
                ->order_by('id', 'DESC')
                ->paginate(10, $all_catalogue_products, (empty($this->input->post('page_no'))) ? 1 : $this->input->post('page_no'));
                if (! empty($catalogue_products)) {
                    foreach ($catalogue_products as $key => $v){
                        if (! empty($catalogue_products[$key]['item_images'])) {
                            foreach ($catalogue_products[$key]['item_images'] as $k => $img) {
                                $catalogue_products[$key]['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'].'?'.time();
                            }
                        }else {
                            $catalogue_products[$key]['item_images'] = NULL;
                        }
                    }
                }
                if(! empty($catalogue_products)){
                    $this->set_response_simple([
                        "result" => $catalogue_products,
                        "total_products_count" => $all_catalogue_products,
                        "products_per_page" => 10,
                        'total number of pages' => ceil($all_catalogue_products/10),
                        'current_page' => (empty($this->input->post('page_no'))) ? 1 : $this->input->post('page_no')
                    ], (empty($this->input->post('page_no'))) ? 'Page No:' . '1' : 'Page No:' . '\'' . $this->input->post('page_no') . '\'', REST_Controller::HTTP_OK, TRUE);
                }else{
                    $this->set_response_simple([
                        "result" => NULL,
                        "total_products_count" => 0,
                        "products_per_page" => 10,
                        'total number of pages' => 1,
                        'current_page' => (empty($this->input->post('page_no'))) ? 1 : $this->input->post('page_no')
                    ], 1, REST_Controller::HTTP_OK, TRUE);
                }
        }elseif ($type == 'item_details'){
            $catalogue_product = $this->food_item_model->with_menu('fields: id, name')
            ->with_sub_category('fields: id, name')
            ->with_sections('fields: id, name')
            ->with_brand('fields: id, name')
            ->with_item_images('fields: id, item_id, serial_number, ext')
            ->with_section_items('fields: id, section_item_code, name, desc, price, weight, status, created_at, updated_at')
            ->get($target);
            if (! empty($catalogue_product)) {
                foreach ($catalogue_product as $key => $v){
                    if (! empty($catalogue_product[$key]['item_images'])) {
                        foreach ($catalogue_product[$key]['item_images'] as $k => $img) {
                            $catalogue_product[$key]['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'].'?'.time();
                        }
                    }else {
                        $catalogue_product[$key]['item_images'] = NULL;
                    }
                }
            }
            $this->set_response_simple($catalogue_product, "Success..!", REST_Controller::HTTP_OK, TRUE);
        }elseif ($type == 'r') {
            $admin_ids = $this->get_users_by_group(1);
            array_push($admin_ids, $token_data->id);
            $deleted_items = $this->db->get_where('deleted_items', ['vendor_id' => $token_data->id])->result_array();
            if($deleted_items){
                $deleted_items = array_column($deleted_items, 'item_id');
            }else{
                $deleted_items = [0];
            }
            if (empty($target)) {
                $vendor = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get();
                $shop_by_categories = $this->shop_by_category_model->where('cat_id', $vendor['category_id'])->where('vendor_id', $admin_ids)->get_all();
                $sub_cat_ids = (empty($shop_by_categories))? NULL :array_column($shop_by_categories, 'sub_cat_id');
                if (! empty($this->input->post('shop_by_cat_id')))
                    $this->db->where('sub_cat_id', $this->input->post('shop_by_cat_id'));

                if (! empty($this->input->post('menu_id')))
                    $this->db->where('menu_id', $this->input->post('menu_id'));

                if (! empty($this->input->post('q'))) {foreach (explode(' ', $this->input->post('q')) as $s){
                    $this->db->or_like('sounds_like', metaphone($s));
                }}

                $all_catalogue_products = $this->food_item_model->where('created_user_id', $admin_ids) ->where('sub_cat_id', $sub_cat_ids)
                    ->where('id NOT', $deleted_items)
                    ->count_rows();
                
                if (! empty($this->input->post('q'))) {foreach (explode(' ', $this->input->post('q')) as $s){
                    $this->db->or_like('sounds_like', metaphone($s));
                }}
                
                if (! empty($this->input->post('shop_by_cat_id')))
                    $this->db->where('sub_cat_id', $this->input->post('shop_by_cat_id'));
                    
                if (! empty($this->input->post('menu_id')))
                    $this->db->where('menu_id', $this->input->post('menu_id'));

                
                $catalogue_products = $this->food_item_model
                    ->with_menu('fields: id, name')
                    ->with_sub_category('fields: id, name')
                    ->with_brand('fields: id, name')
                    ->with_item_images('fields: id, serial_number, ext')
                    ->where('created_user_id', $admin_ids)
                    ->where('sub_cat_id', $sub_cat_ids)
                    ->where('id NOT', $deleted_items)
                    ->order_by('id', 'DESC')
                    ->paginate(10, $all_catalogue_products, (empty($this->input->post('page_no'))) ? 1 : $this->input->post('page_no'));
                if (! empty($catalogue_products)) {
                    if (! empty($catalogue_product['item_images'])) {
                        foreach ($catalogue_product['item_images'] as $k => $img) {
                            $catalogue_product['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'].'?'.time();
                        }
                    }else {
                        $catalogue_product['item_images'] = NULL;
                    }
                }
                if(! empty($shop_by_categories)){
                    $this->set_response_simple([
                        "result" => $catalogue_products,
                        "total_products_count" => $all_catalogue_products,
                        "products_per_page" => 10,
                        'total number of pages' => ceil($all_catalogue_products/10),
                        'current_page' => (empty($this->input->post('page_no'))) ? 1 : $this->input->post('page_no')
                    ], (empty($this->input->post('page_no'))) ? 'Page No:' . '1' : 'Page No:' . '\'' . $this->input->post('page_no') . '\'', REST_Controller::HTTP_OK, TRUE);
                }else{
                    $this->set_response_simple([
                        "result" => [],
                        "total_products_count" => 0,
                        "products_per_page" => 10,
                        'total number of pages' => 1,
                        'current_page' => (empty($this->input->post('page_no'))) ? 1 : $this->input->post('page_no')
                    ], 1, REST_Controller::HTTP_OK, TRUE);
                }
                
            } else {
                $catalogue_product = $this->food_item_model->with_menu('fields: id, name')
                    ->with_sub_category('fields: id, name')
                    ->with_sections('fields: id, name')
                    ->get($target);
                $catalogue_product['type'] = ($catalogue_product['item_type'] == 1) ? [
                    'id' => 1,
                    'type' => 'Veg'
                ] : [
                    'id' => 2,
                    'type' => 'Non-Veg'
                ];
                $catalogue_product['product_image'] = base_url() . 'uploads/food_item_image/food_item_' . $catalogue_product['id'] . '.jpg'.'?'.time();
                $this->set_response_simple($catalogue_product, "Success..!", REST_Controller::HTTP_OK, TRUE);
            }
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->food_item_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $sounds_like = $this->sounds_like($this->input->post('name'), $this->input->post('shop_by_cat_id'), $this->input->post('menu_id'));
                
                $shop_by_cat_id = $this->input->post('shop_by_cat_id');
                $menu_id = $this->input->post('menu_id');
                $item_id = $this->input->post('item_id');
                $section_id = $this->input->post('section_id');
                $variants = $this->input->post('variants');
                $brand_id = $this->input->post('brand_id');
                $is_updated = $this->food_item_model->update([
                    'id' => $item_id,
                    'sub_cat_id' => $shop_by_cat_id,
                    'menu_id' => $menu_id,
                    'brand_id' => $brand_id,
                    'name' => $this->input->post('name'),
                    'desc' => (empty($this->input->post('desc'))) ? NULL :$this->input->post('desc'),
                    'sounds_like' => $sounds_like,
                    'availability' => $this->input->post('availability'),
                    'status' => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 3,
                ], 'id');
                if ($is_updated) {
                    if(! empty($variants)){
                        foreach ($variants as $key => $variant){
                            if(! empty($variant['variant_id'])){
                                $this->food_sec_item_model->update([
                                    'id' => $variant['variant_id'],
                                    'price' => (empty($variant['price'])) ? NULL : $variant['price'],
                                    'weight' => (empty($variant['weight'])) ? NULL : $variant['weight'],
                                    'desc' => (empty($variant['desc'])) ? NULL : $variant['desc'],
                                    'name' => $variant['option_name'],
                                    'status' => $variant['status']
                                ], 'id');
                            }else{
                                $this->food_sec_item_model->insert([
                                    'menu_id' => $menu_id,
                                    'item_id' => $item_id,
                                    'sec_id' => $section_id,
                                    'section_item_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                                    'price' => (empty($variant['price'])) ? NULL : $variant['price'],
                                    'weight' => (empty($variant['weight'])) ? NULL : $variant['weight'],
                                    'desc' => (empty($variant['desc'])) ? NULL : $variant['desc'],
                                    'name' => $variant['option_name'],
                                    'status' => $variant['status']
                                ]);
                            }
                        }
                    }
                    
                    if (! empty($this->input->post('item_images'))) {
                        if (! file_exists('uploads/' . 'food_item' . '_image/')) {
                            mkdir('uploads/' . 'food_item' . '_image/', 0777, true);
                        }
                        foreach ($this->input->post('item_images') as $key => $image) {
                            if (! empty($image['id'])) {
                                $this->food_item_image_model->update([
                                    'id' => $image['id'],
                                    'ext' => 'jpg'
                                ], 'id');
                                $product_image_id = $image['id'];
                            } else {
                                $product_images = $this->food_item_image_model->where('item_id', $item_id)->get_all();
                                $last_image_id = 0;
                                if (! empty($product_images))
                                    $last_image_id = max(array_column($product_images, 'serial_number'));
                                    
                                    $product_image_id = $this->food_item_image_model->insert([
                                        'item_id' => $item_id,
                                        'serial_number' => ++ $last_image_id,
                                        'ext' => 'jpg'
                                    ]);
                            }
                            file_put_contents("./uploads/food_item_image/food_item_" . $product_image_id . ".jpg", base64_decode($image['image']));
                        }
                    }
                    $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
                }
            }
                
        } elseif ($type == 'd') {
            $food_item = $this->food_item_model->get($target);
            if(! empty($food_item) && $food_item['created_user_id'] == $token_data->id){
                $this->food_item_model->delete([
                    'id' => $target
                ]);
                $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Product deleted..!', REST_Controller::HTTP_OK, TRUE);
            }else{
                $this->set_response_simple(NULL, 'No privilege to  delete..!', REST_Controller::HTTP_OK, TRUE);
            }
        } elseif ($type == 'delete_image') {
            $is_deleted = $this->food_item_image_model->delete([
                'id' => $this->input->post('image_id')
            ]);
            if($is_deleted){
                if (file_exists("./uploads/food_item_image/food_item_" . $this->input->post('image_id') . ".jpg")) {
                    unlink('./uploads/' . 'food_item' . '_image/' . 'food_item' . '_' . $this->input->post('image_id') . '.jpg');
                }
                $this->set_response_simple(NULL, 'Product Image is deleted..!', REST_Controller::HTTP_OK, TRUE);
            }else{
                $this->set_response_simple(NULL, 'Sorry, Failed to  delete..!', REST_Controller::HTTP_OK, FALSE);
            }
        }
    }
    
    /**
     * @desc To manage vendor products
     * @author Mehar
     * 
     * @param string $type
     * @param number $target
     */
    public function vendor_products_post($type = 'my_products', $target = 0){
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if ($type == 'my_products'){
            $data = $this->vendor_product_variant_model->all((! empty($this->input->post('limit'))) ? $this->input->post('limit') : NUll, (! empty($this->input->post('offset'))) ? $this->input->post('offset') : NUll, (! empty($this->input->post('shop_by_cat_id'))) ? $this->input->post('shop_by_cat_id') : NUll, (! empty($this->input->post('menu_id'))) ? $this->input->post('menu_id') : NUll, (!empty($this->input->post('brand_id'))) ? $this->input->post('brand_id') : NUll, (! empty($this->input->post('q'))) ? $this->input->post('q') : NUll, $token_data->id);
            if (! empty($data['result'])) {
                foreach ($data['result'] as $key => $val) {
                    $data['result'][$key]['sub_category'] = $this->sub_category_model->fields('id, name, desc')->where('id', $data['result'][$key]['sub_cat_id'])->get();
                    $data['result'][$key]['menu'] = $this->food_menu_model->fields('id, name, desc')->where('id', $data['result'][$key]['menu_id'])->get();
                    $data['result'][$key]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $val['image_id'] . '.' . $val['ext'] . '?' . time();
                }
            }
            $this->set_response_simple((empty($data['result'])) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }elseif ($type == 'vendor_product_details'){
            $catalogue_product = $this->food_item_model->with_menu('fields: id, name')
            ->with_sub_category('fields: id, name')
            ->with_brand('fields: id, name')
            ->with_sections('fields: id, name')
            ->with_item_images('fields: id, serial_number, ext')
            ->with_section_items('fields: id, section_item_code, name, desc, price, weight, status, created_at, updated_at')
            ->with_vendor_product_varinats('fields: id, item_id, section_id, section_item_id, sku, price, stock, discount, tax_id, list_id, vendor_user_id, status', 'where: vendor_user_id='.$token_data->id)
            ->get($target);
            if(! empty($catalogue_product['vendor_product_varinats'])){ foreach ($catalogue_product['vendor_product_varinats'] as $key => $val){
                $name = $this->food_sec_item_model->fields('name, weight')->where('id', $val['section_item_id'])->get();
                $catalogue_product['vendor_product_varinats'][$key]['section_item_name'] = ! empty($name)? $name['name'] : NULL;
                $catalogue_product['vendor_product_varinats'][$key]['weight'] = ! empty($name)? $name['weight'] : NULL;
                if(! empty($val['tax_id'])){
                    $tax = $this->tax_model->fields('id, tax, rate')->with_tax_type('fields: id, name, desc')->where('id', $val['tax_id'])->get();
                }else{
                    $tax = NULL;
                }
                
                $catalogue_product['vendor_product_varinats'][$key]['tax'] = $tax;
            }}
            
            if (! empty($catalogue_product)) {
                if (! empty($catalogue_product['item_images'])) {
                    foreach ($catalogue_product['item_images'] as $k => $img) {
                        $catalogue_product['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'].'?'.time();
                    }
                }else {
                    $catalogue_product['item_images'] = NULL;
                }
            }
            $this->set_response_simple($catalogue_product, "Success..!", REST_Controller::HTTP_OK, TRUE);
        }elseif ($type == 'add_to_my_list'){
            $this->form_validation->set_rules($this->vendor_product_variant_model->rules['create']);
            $this->vendor_product_variant_model->user_id = $token_data->id;
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_OK, FALSE);
            } else {
                $vendor = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get();
                $item = $this->food_item_model
                ->with_menu('fields: id, name')
                ->with_sub_category('fields: id, name')
                ->with_section_items('fields: id, sec_id, item_id, name, desc, price, status, created_at, updated_at')
                ->where('id', $this->input->post('item_id'))->get();
                if(! empty($item) && ! empty($item['section_items'])){
                    $are_items_existed = $this->vendor_product_variant_model->where(['item_id' => $item['id'], 'vendor_user_id' => $token_data->id])->get_all();
                    if(empty($are_items_existed)){
                        $section_items = [];
                        foreach ($item['section_items'] as $key => $section_item){
                            array_push($section_items, [
                                'item_id' => $section_item['item_id'],
                                'section_id' => $section_item['sec_id'],
                                'section_item_id' => $section_item['id'],
                                'sku' => generate_serial_no($vendor['unique_id'].'-'.metaphone($item['sub_category']['name']).'-'.metaphone($item['menu']['name']).'-', 2, $key),
                                'price' => $section_item['price'],
                                'stock' => 0,
                                'discount' => 0,
                                'vendor_user_id' => $token_data->id,
                                'list_id' => ! empty($vendor)? $vendor['id'] : NULL
                            ]);
                        }
                        $is_inserted = $this->vendor_product_variant_model->insert($section_items);
                        $this->set_response_simple($is_inserted, "Success..!", REST_Controller::HTTP_OK, TRUE);
                    }else {
                        $this->set_response_simple(NULL, "Item is already added to the list.!", REST_Controller::HTTP_OK, FALSE);
                    }
                }else {
                    $this->set_response_simple(NULL, "Sorry, Item is not available..!", REST_Controller::HTTP_OK, FALSE);
                }
            }
        }elseif ($type == 'update_variant'){
             
            $this->form_validation->set_rules($this->vendor_product_variant_model->rules['update']);
            $this->vendor_product_variant_model->user_id = $token_data->id;
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_OK, FALSE);
            } else {
                $is_updated = $this->vendor_product_variant_model->update([
                    'id' => $this->input->post('variant_id'),
                    'price' => $this->input->post('price'),
                    'stock' => $this->input->post('stock'),
                    'discount' => $this->input->post('discount'),
                    'tax_id' => $this->input->post('tax_id'),
                    'status' => $this->input->post('status'),
                ], 'id');
                $this->set_response_simple($is_updated, "Success..!", REST_Controller::HTTP_OK, TRUE);
            }
        }
    }

    /**
     * To manage section
     *
     * @author Mehar
     * @param string $type
     * @param number $target
     */
    public function sections_post($type = 'r', $target = 0)
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->food_section_model->user_id = $token_data->id;
        if ($type == 'c') {
            $this->form_validation->set_rules($this->food_section_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                if ($this->input->post('item_field') == 2) {
                    $sec_price = 1;
                } elseif ($this->input->post('item_field') == 1) {
                    $sec_price = $this->input->post('sec_price');
                }
                $id = $this->food_section_model->insert([
                    'menu_id' => $this->input->post('menu_id'),
                    'item_id' => $this->input->post('item_id'),
                    'item_field' => $this->input->post('item_field'),
                    'sec_price' => $sec_price,
                    'required' => $this->input->post('require_items'),
                    'name' => $this->input->post('name')
                ]);

                if ($id) {
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        } elseif ($type == 'r') {
            $admin_ids = $this->get_users_by_group(1);
            array_push($admin_ids, $token_data->id);
            if (empty($target)) {
                if (! $this->ion_auth->in_group('admin', $token_data->id)) {
                    $cat_id = $this->vendor_list_model->with_sub_categories('fields: id, name, status', 'where: type = 2')
                        ->where('vendor_user_id', $token_data->id)
                        ->get();
                    if (empty($this->input->post('shop_by_cat_id')))
                        $sub_cats = $this->db->query("SELECT sub_categories.id, sub_categories.name FROM `shop_by_categories` JOIN sub_categories ON sub_categories.id = shop_by_categories.sub_cat_id where shop_by_categories.vendor_id IN(" . implode(",", $admin_ids) . ") AND sub_categories.type = 2 AND sub_categories.cat_id=" . $cat_id['category_id'])->result_array();
                    else
                        $sub_cats = $this->db->query("SELECT sub_categories.id, sub_categories.name FROM sub_categories where sub_categories.id = " . $this->input->post('shop_by_cat_id'))
                            ->result_array();
                    
                    if ($sub_cats) {
                        $sub_cat_ids = array_column($sub_cats, 'id');

                        if (empty($this->input->post('menu_id'))) {
                            $menus = $this->food_menu_model->fields('id, name, desc, vendor_id')
                                ->where('sub_cat_id', $sub_cat_ids)
                                ->where('vendor_id', $admin_ids)
                                ->get_all();
                        } else {
                            $menus = $this->food_menu_model->fields('id, name, desc, vendor_id')
                                ->where('id', $this->input->post('menu_id'))
                                ->get_all();
                        }
                        if ($menus) {
                            if (! empty($this->input->post('q'))) {
                                $this->db->like('food_section.name', $this->input->post('q'));
                            }
                            $sections = $this->food_section_model
                                ->with_item('fields:name')
                                ->with_menu('fields:id, name, vendor_id')
                                ->where('menu_id', array_column($menus, 'id'))
                                ->where('created_user_id', $admin_ids)
                                ->order_by('id', 'DESC')
                                ->get_all();
                            
                            if ($sections) {
                                $this->set_response_simple(($sections == FALSE) ? FALSE : $sections, 'Success..!', REST_Controller::HTTP_OK, TRUE);
                            } else {
                                $this->set_response_simple(($sections == FALSE) ? FALSE : $sections, 'Failed..!', REST_Controller::HTTP_OK, FALSE);
                            }
                        }
                    }
                }
            } else {
                $sections = $this->food_section_model->with_menu('fields:id, name, vendor_id, sub_cat_id')
                    ->with_section_items('fields: id, name, desc, price, sku')
                    ->with_item('fields:name')
                    ->where('id', $target)
                    ->get();
                $sections['shop_by_category'] = $this->sub_category_model->get($sections['menu']['sub_cat_id']);
                $this->set_response_simple(($sections == FALSE) ? FALSE : $sections, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->food_section_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                if ($this->input->post('item_field') == 2) {
                    $sec_price = 1;
                } elseif ($this->input->post('item_field') == 1) {
                    $sec_price = $this->input->post('sec_price');
                }
                $id = $this->food_section_model->update([
                    'id' => $this->input->post('id'),
                    'menu_id' => $this->input->post('menu_id'),
                    'item_id' => $this->input->post('item_id'),
                    'item_field' => $this->input->post('item_field'),
                    'sec_price' => $sec_price,
                    'required' => $this->input->post('require_items'),
                    'name' => $this->input->post('name')
                ], 'id');
                if ($id) {
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
                } else {
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        } elseif ($type == 'd') {
            $food_section = $this->food_section_model->get($target);
            if(! empty($food_section) && $food_section['created_user_id'] == $token_data->id){
                $this->food_section_model->delete([
                    'id' => $target
                ]);
                $this->set_response_simple(NULL, 'Section deleted..!', REST_Controller::HTTP_OK, TRUE);
            }else{
                $this->set_response_simple(NULL, 'No privilege to  delete..!', REST_Controller::HTTP_OK, TRUE);
            }
        }
    }

    /**
     * To manage section item
     *
     * @author Mehar
     * @param string $type
     * @param number $target
     */
    public function section_items_post($type = 'r', $target = 0)
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->food_sec_item_model->user_id = $token_data->id;
        if ($type == 'c') {
            $this->form_validation->set_rules($this->food_sec_item_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $id = $this->food_sec_item_model->insert([
                    'menu_id' => $this->input->post('menu_id'),
                    'item_id' => $this->input->post('item_id'),
                    'sec_id' => $this->input->post('sec_id'),
                    'sku' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                    'price' => $this->input->post('price'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'status' => 1
                ]);

                if ($id) {
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        } elseif ($type == 'r') {
            $admin_ids = $this->get_users_by_group(1);
            array_push($admin_ids, $token_data->id);
            if (empty($target)) {
                if (! $this->ion_auth->in_group('admin', $token_data->id)) {
                    $cat_id = $this->vendor_list_model->with_sub_categories('fields: id, name, status', 'where: type = 2')
                        ->where('vendor_user_id', $token_data->id)
                        ->get();
                    if (empty($this->input->post('shop_by_cat_id')))
                        $sub_cats = $this->db->query("SELECT sub_categories.id, sub_categories.name FROM `shop_by_categories` JOIN sub_categories ON sub_categories.id = shop_by_categories.sub_cat_id where shop_by_categories.vendor_id IN(" . implode(",", $admin_ids) . ") AND sub_categories.type = 2 AND sub_categories.cat_id=" . $cat_id['category_id'])->result_array();
                    else
                        $sub_cats = $this->db->query("SELECT sub_categories.id, sub_categories.name FROM sub_categories where sub_categories.id = " . $this->input->post('shop_by_cat_id'))
                            ->result_array();
                    
                    if (! empty($sub_cats)) {
                        $sub_cat_ids = array_column($sub_cats, 'id');
                        if (empty($this->input->post('menu_id'))) {
                            $menus = $this->food_menu_model->fields('id, name, desc, vendor_id')
                                ->where('sub_cat_id', $sub_cat_ids)
                                ->where('vendor_id', $admin_ids)
                                ->get_all();
                        } else {
                            $menus = $this->food_menu_model->fields('id, name, desc, vendor_id')
                                ->where('id', $this->input->post('menu_id'))
                                ->get_all();
                        }
                        
                        if ($menus) {
                            if (! empty($this->input->post('q'))) {
                                $this->db->like('food_sec_item.name', $this->input->post('q'));
                            }
                            
                            $sections = $this->food_sec_item_model->with_menu('fields:id, name, vendor_id')
                                ->with_item('fields:id, name')
                                ->with_sec('fields:id, name')
                                ->where('menu_id', array_column($menus, 'id'))
                                ->where('created_user_id', $admin_ids)
                                ->order_by('id', 'DESC')
                                ->get_all();
                            $this->set_response_simple(($sections == FALSE) ? FALSE : $sections, 'Success..!', REST_Controller::HTTP_OK, TRUE);
                        }
                    }
                }
            } else {
                $sections = $this->food_sec_item_model->with_menu('fields:id, name, vendor_id, sub_cat_id')
                    ->with_sec('fields: id, name')
                    ->with_item('fields:id, name')
                    ->where('id', $target)
                    ->get();
                $sections['shop_by_category'] = $this->sub_category_model->get($sections['menu']['sub_cat_id']);
                $this->set_response_simple(($sections == FALSE) ? FALSE : $sections, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->food_sec_item_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $id = $this->food_sec_item_model->update([
                    'id' => $this->input->post('id'),
                    'menu_id' => $this->input->post('menu_id'),
                    'item_id' => $this->input->post('item_id'),
                    'sec_id' => $this->input->post('sec_id'),
                    'price' => $this->input->post('price'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'status' => 1
                ], 'id');
                if ($id) {
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
                } else {
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        } elseif ($type == 'd') {
            $food_sec_item = $this->food_sec_item_model->get($target);
            if(! empty($food_sec_item) && $food_sec_item['created_user_id'] == $token_data->id){
                $this->food_sec_item_model->delete([
                    'id' => $target
                ]);
                $this->set_response_simple(NULL, 'Section item deleted..!', REST_Controller::HTTP_OK, TRUE);
            }else{
                $this->set_response_simple(NULL, 'No privilege to  delete..!', REST_Controller::HTTP_OK, TRUE);
            }
        }
    }
    
    /**
     * To manage orders
     *
     * @author Mehar
     * @param string $type
     * @param number $target
     * @date 31-03-2021
     */
    public function ecom_orders_post($type = 'vendor_orders'){
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if($type == 'vendor_orders'){
            $orders = $this->ecom_order_model->get_orders(
                NULL,
                NULL,
                $token_data->id,
                (empty($this->input->post('start_date')))? NULL: $this->input->post('start_date'),
                (empty($this->input->post('end_date')))? NULL: $this->input->post('end_date'),
                NULL,
                NULL,
                (empty($this->input->post('status')))? NULL: $this->input->post('status'),
                (empty($this->input->post('delivery_boy_status')))? NULL: $this->input->post('delivery_boy_status'),
                FALSE,
                'vendor_orders'
                );
            
            if(! empty($orders)){
                foreach ($orders as $key => $order){
                    if(! empty($order['payment_id']))
                        $orders[$key]['payment'] = $this->ecom_payment_model->fields('id, txn_id, amount, created_at, message, status')->with_payment_method('fields: id, name, description')->where('id', $order['payment_id'])->get();
                        else
                            $orders[$key]['payment'] = NULL;
                            
                            if(! empty($order['order_status_id']))
                                $orders[$key]['order_status'] = $this->ecom_order_status_model->fields('id, delivery_mode_id, status, serial_number')->where('id', $order['order_status_id'])->get();
                                else
                                    $orders[$key]['order_status'] = NULL;
                                    
                }
                $this->set_response_simple($orders, 'Success.', REST_Controller::HTTP_OK, TRUE);
            }else {
                $this->set_response_simple(NULL, 'No orders found.!', REST_Controller::HTTP_OK, TRUE);
            }
        }elseif ($type == 'order_details'){
            $order = $this->ecom_order_model->fields('track_id, delivery_fee, total, used_wallet_amount, message, preparation_time, created_at')
            ->with_shipping_address('fields: id, phone, email, name, landmark, address, location_id')
            ->with_delivery_mode('fields: id, name, desc')
            ->with_customer('fields: id, unique_id, first_name, phone')
            ->with_order_status('fields: id, delivery_mode_id, status, serial_number')
            ->with_payment('fields: id, payment_method_id, txn_id, amount, created_at, message, status')
            ->with_ecom_order_details('fields: id, item_id, vendor_product_variant_id, qty, price, rate_of_discount, sub_total, discount, tax, total, cancellation_message, status')
            ->where('id', $this->input->post('order_id'))
            ->get();
            
            if(! empty($order)){
                if(! empty($order['ecom_order_details'])){ foreach ($order['ecom_order_details'] as $key => $detials){
                    $order['ecom_order_details'][$key]['item'] = $this->food_item_model->fields('id, name, desc')->with_images('fields: id, ext')->where('id', $detials['item_id'])->get();
                    if(! empty($order['ecom_order_details'][$key]['item']['images'])){
                        foreach ($order['ecom_order_details'][$key]['item']['images'] as $i => $val){
                            $order['ecom_order_details'][$key]['item']['images'][$i]['image']  = base_url() . 'uploads/food_item_image/food_item_' . $val['id'] . '.' . $val['ext'] . '?' . time();
                        }
                    }else {
                        $order['ecom_order_details'][$key]['item']['images'] = [];
                    }
                    $order['ecom_order_details'][$key]['item']['varinat'] = $this->vendor_product_variant_model->fields('id, sku, price, stock, discount, tax_id, status')->with_section_item('fields: id, name, weight')->where('id', $detials['vendor_product_variant_id'])->get();
                }}
                $this->set_response_simple($order, 'Success.', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'Not found.', REST_Controller::HTTP_OK, TRUE);
            }
            
        }
    }

    /**
     * To manage orders
     *
     * @author Mehar
     * @param string $type
     * @param number $target
     */
    public function orders_get($type = 'r', $order_type = 'all', $target = 0)
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        if ($type == 'r') {
            if(empty($target)){
                $count = 0;
                $orders = $this->food_orders_model->where('vendor_id', $token_data->id)->get_all();
                if (! empty($orders)) {
                    $count = count($orders);
                }
                $data['info']['orders_count'] =$count;
                $data['info']['order_type'] = $order_type;
                $where_order_status = '';
                if ($order_type == 'past') {
                    $where_order_status = 'order_status = 6';
                } elseif ($order_type == 'upcoming') {
                    $where_order_status = 'order_status != 0 AND order_status != 6 AND order_status != 7';
                } elseif ($order_type == 'cancelled') {
                    $where_order_status = 'order_status = 7';
                } elseif ($order_type == 'rejected') {
                    $where_order_status = 'order_status = 0';
                }
                
                if (! $this->ion_auth->in_group('admin', $token_data->id)) {
                    if ($order_type == 'all') {
                        $where_order_status = 'vendor_id = ' . $token_data->id;
                    } else {
                        $where_order_status .= ' AND vendor_id = ' . $token_data->id;
                    }
                }
                
                if ((isset($_GET['start_date']) && $_GET['start_date'] != '') && (isset($_GET['end_date']) && $_GET['end_date'] != '')) {
                    $start_date = $_GET['start_date'];
                    $end_date = $_GET['end_date'];
                }
                if (! empty($start_date)&& ! empty($end_date)) {
                    /* $start_date = date('Y-m-d');
                    $end_date = date('Y-m-d', strtotime('+2 months')); */
                    if ($where_order_status != '') {
                        $where_order_status .= ' AND created_at >= "' . date('Y-m-d', strtotime($start_date)) . ' 00:00:00" AND created_at <= "' . date('Y-m-d', strtotime($end_date)) . ' 23:59:59"';
                    } else {
                        $where_order_status .= 'created_at >= "' . date('Y-m-d', strtotime($start_date)) . ' 00:00:00" AND created_at <= "' . date('Y-m-d', strtotime($end_date)) . ' 23:59:59"';
                    }
                }
                
                if ($where_order_status != '') {
                    $this->db->where($where_order_status);
                }
                
                $data['result'] = $this->food_orders_model->with_user('fields:id, first_name')
                ->with_vendor('fields:id, name')
                ->with_delvery_boy('fields:id, name')
                ->with_promo('fields:id, promo_title, 	promo_code, promo_type, promo_label, discount_type, discount')
                ->with_order_items('fields:item_id,order_id,price,quantity')
                ->with_sub_order_items('fields:sec_item_id,order_id,item_id,price,quantity')
                ->fields('id,discount,delivery_fee,payment_method_id,created_at,tax,total,deal_id,order_track,order_status,delivery,rejected_reason,otp')
                ->order_by('id', 'DESC')
                ->get_all();
                
                if(! empty($data['result'])){foreach ($data['result'] as $key => $order){
                    if(empty($order['sub_order_items'])){
                        $data['result'][$key]['sub_order_items'] = [];
                    }
                    $data['result'][$key]['payment_method'] = $this->payment_method_model->fields('id, name, description')->get($order['payment_method_id']);
                }}
                $this->set_response_simple($data ? $data: NULL, 'List of orders', REST_Controller::HTTP_OK, TRUE);
            }else {
                $data = $this->food_orders_model->with_user('fields:id, first_name')
                ->with_vendor('fields:id, name')
                ->with_delvery_boy('fields:id, name')
                ->with_promo('fields:id, promo_title, 	promo_code, promo_type, promo_label, discount_type, discount')
                ->with_order_items('fields:item_id,order_id,price,quantity')
                ->with_sub_order_items('fields:sec_item_id,order_id,item_id,price,quantity')
                ->fields('id,discount,delivery_fee,payment_method_id,created_at,tax,total,deal_id,order_track,order_status,delivery,rejected_reason,otp')
                ->order_by('id', 'DESC')
                ->get($target);
                $data['payment_method'] = $this->payment_method_model->fields('id, name, description')->get($data['payment_method_id']);
                if(! empty($data['order_items'])){foreach ($data['order_items'] as $key => $order_item){
                    $data['order_items'][$key]['item'] = $this->food_item_model->fields('id, product_code, name, desc, price, discount')->get($order_item['item_id']);
                }}
                if(! empty($data['sub_order_items'])){foreach ($data['sub_order_items'] as $key => $sub_order_item){
                    $data['sub_order_items'][$key]['item'] = $this->food_item_model->fields('id, product_code, name, desc, price, discount')->get($sub_order_item['item_id']);
                    $data['sub_order_items'][$key]['section_item'] = $this->food_sec_item_model->fields('id, sku, name, desc, price')->get($sub_order_item['sec_item_id']);
                }}
                $this->set_response_simple($data ? $data: NULL, 'List of orders', REST_Controller::HTTP_OK, TRUE);
            }
        }elseif ($type == 'our_for_delivery'){
            $order_id = $this->input->get('order_id');
            $order = $this->food_orders_model->where('id', $order_id)->get();
            if ($order['otp'] == $this->input->get('otp')) {
                if ($order['delivery'] == 1) {
                    $res = $this->food_orders_model->update([
                        'id' => $order_id,
                        'order_status' => 4
                    ], 'id');
                }
                if ($order['delivery'] == 2) {
                    $res = $this->food_orders_model->update([
                        'id' => $order_id,
                        'order_status' => 6
                    ], 'id');
                    $this->user_model->update_walet($order['vendor_id'], $order['total'], 'Order: ' . $order['order_track']);
                }
             $this->send_notification($order['user_id'], USER_APP_CODE, "Order status", "Your Order(id:".$order['order_track'].") is Out for Delivery.",['order_id' => $order['id'], 'notification_type' => $this->notification_type_model->where(['app_details_id' => 1, 'notification_code' => 'OD'])->get()]);
             $this->set_response_simple(NULL, 'delivered', REST_Controller::HTTP_OK, TRUE);
            }
        }elseif ($type == 'reject_order'){
            $res = $this->food_orders_model->update([
                'id' => $this->input->get('order_id'),
                'rejected_reason' => $this->input->get('reason'),
                'order_status' => 0
            ], 'id');
            $order = $this->food_orders_model->where('id', $this->input->get('order_id'))->get();
            $this->send_notification($order['user_id'], USER_APP_CODE, "Order status", "Sorry, Your Order(id:".$order['order_track'].") is Rejected.",['order_id' => $order['id'], 'notification_type' => $this->notification_type_model->where(['app_details_id' => 1, 'notification_code' => 'OD'])->get()]);
            $this->set_response_simple(NULL, 'rejected', REST_Controller::HTTP_OK, TRUE);
        }elseif ($type == 'accept_order'){
            $id = $this->input->get('order_id');
            $res = $this->food_orders_model->update([
                'id' => $id,
                'order_status' => 2
            ], 'id');
            
            if ($res) {
                $order = $this->food_orders_model->where('id', $id)->get();
                if ($order['delivery'] == 1) {
                    $l = $this->vendor_list_model->with_location('fields: id, address, latitude, longitude')
                    ->where('vendor_user_id', $token_data->id)
                    ->get();
                    $lat = $l['location']['latitude'];
                    $lng = $l['location']['longitude'];
                    $distance = 10; 
                    $query = $this->db->query('
                    SELECT
                        *,
                        6371 * 2 * ASIN(SQRT(POWER(SIN(RADIANS(? - ABS(delivery_boy_settings.latitude))), 2) + COS(RADIANS(?)) * COS(RADIANS(ABS(delivery_boy_settings.latitude))) * POWER(SIN(RADIANS(? - delivery_boy_settings.longitude)), 2))) AS distance
                    FROM delivery_boy_settings where delivery_boy_status=1
                    HAVING distance < ?
                    ', [
                        $lat,
                        $lat,
                        $lng,
                        $distance
                    ]);
                    $deal = $query->result_array();
                    for ($i = 0; $i < count($deal); $i ++) {
                        $acc = $this->food_order_deal_model->insert([
                            'order_id' => $id,
                            'deal_id' => $deal[$i]['user_id']
                        ]);
                    }
                } elseif ($order['delivery'] == 2) {
                    $acc = $this->food_orders_model->update([
                        'id' => $id,
                        'otp' => rand(1234, 9567)
                    ], 'id');
                }
                $this->send_notification($order['user_id'], USER_APP_CODE, "Order status", "Your Order(id:".$order['order_track'].") is Accepted.! TRACK NOW",['order_id' => $order['id'], 'notification_type' => $this->notification_type_model->where(['app_details_id' => 1, 'notification_code' => 'OD'])->get()]);
                $this->set_response_simple(NULL, 'Accepted', REST_Controller::HTTP_OK, TRUE);
            }else{
                $this->set_response_simple(NULL, 'Something went wrong', REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
            }
        }
    }

    /**
     * To manage orders
     *
     * @author Mehar
     * @param string $type
     * @param number $target
     */
    public function reports_get($type = 'sales', $format = 'monthly')
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        if($type == 'sales'){
            if($format == 'monthly'){
                $data = $this->db->query(
                    "SELECT year(o.created_at) as year, month(o.created_at) as month, ROUND(sum(o.total),2) as sales FROM food_orders as o where o.vendor_id = ".$token_data->id." and o.order_status = 6 group by year, month;"
                    )->result_array();
                $this->set_response_simple($data ? $data: NULL, 'Monthly Sales', REST_Controller::HTTP_OK, TRUE);
            }elseif ($format == 'weekly'){
                $data = $this->db->query(
                    "SELECT year(o.created_at) as year, month(o.created_at) as month, week(o.created_at) as week, ROUND(sum(o.total),2) as sales FROM food_orders as o where o.vendor_id = ".$token_data->id." and o.order_status = 6 group by year, week;"
                    )->result_array();
                    $this->set_response_simple($data ? $data: NULL, 'Weekly Sales', REST_Controller::HTTP_OK, TRUE);
            }elseif ($format == 'yearly'){
                $data = $this->db->query(
                    "SELECT year(o.created_at) as year, ROUND(sum(o.total),2) as sales FROM food_orders as o where o.vendor_id = ".$token_data->id." and o.order_status = 6 group by year;"
                    )->result_array();
                    $this->set_response_simple($data ? $data: NULL, 'Yearly Sales', REST_Controller::HTTP_OK, TRUE);
            }elseif ($format == 'daily'){
                $data = $this->db->query(
                    "SELECT year(o.created_at) as year, month(o.created_at) as month, day(o.created_at) as day, ROUND(sum(o.total),2) as sales FROM food_orders as o where o.vendor_id = ".$token_data->id." and o.order_status = 6 group by year, month, day;"
                    )->result_array();
                    $this->set_response_simple($data ? $data: NULL, 'Daily Sales', REST_Controller::HTTP_OK, TRUE);
            }
        }
    }
    
    /**
     * To show statistics bars on dashboard
     *
     * @author Mehar
     */
    public function statistics_get(){
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $admin_ids = $this->get_users_by_group(1);
        array_push($admin_ids, $token_data->id);
        $deleted_items = $this->db->get_where('deleted_items', ['vendor_id' => $token_data->id])->result_array();
        if($deleted_items){
            $deleted_items = array_column($deleted_items, 'item_id');
        }else{
            $deleted_items = [0];
        }
        
        $cat_id = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get()['category_id'];
        $shop_by_categories = $this->sub_category_model->where(['type' => 2, 'cat_id' =>$cat_id])->get_all();
        $sub_cat_ids = (empty($shop_by_categories))? NULL :array_column($shop_by_categories, 'id');
        
        $data['active_products_count'] = $this->food_item_model->where('approval_status', 1)->where('created_user_id', $admin_ids)->where('id NOT', $deleted_items)->where('sub_cat_id', $sub_cat_ids)->count_rows();
        $data['in_active__products_count'] = $this->food_item_model->where('approval_status', 2)->where('created_user_id', $admin_ids)->where('id NOT', $deleted_items)->where('sub_cat_id', $sub_cat_ids)->count_rows();
        $data['pending_orders_count'] = $this->food_orders_model->where('order_status !=', 0)->where('order_status !=', 6)->where('order_status !=' ,7)->where('vendor_id', $token_data->id)->count_rows();
        $data['completed_orders_count'] = $this->food_orders_model->where('order_status', 6)->where('vendor_id', $token_data->id)->count_rows();
        $data['cancelled_orders_count'] = $this->food_orders_model->where('order_status' ,7)->where('vendor_id', $token_data->id)->count_rows();
        $data['rejected_orders_count'] = $this->food_orders_model->where('order_status', 0)->where('vendor_id', $token_data->id)->count_rows();
        $this->set_response_simple($data ? $data: NULL, 'My statistics', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * To generate phonatic sounds which helps us to search more accurately
     *
     * @author Mehar
     *        
     * @param string $name
     * @param integer $menu_id
     * @param integer $cat_id
     * @param integer $sub_cat_id
     * @param integer $brand_id
     * @return string
     */
    public function sounds_like($name = NULL, $shop_by_cat_id = NULL, $menu_id = NULL)
    {
        $sounds_like = '';
        if (! is_null($menu_id)) {
            $menu_name = $this->food_menu_model->fields('name')
                ->where('id', $menu_id)
                ->get();
            $sounds_like .= metaphone($menu_name['name']) . ' ';
        }
        if (! is_null($shop_by_cat_id)) {
            $cat_name = $this->sub_category_model->fields('name')
                ->where('id', $shop_by_cat_id)
                ->get();
            $sounds_like .= metaphone($cat_name['name']) . ' ';
        }
        if (! is_null($name)) {foreach (explode(' ', $name) as $n){
            $sounds_like .= metaphone($n) . ' ';
        }}
        return $sounds_like;
    }
}