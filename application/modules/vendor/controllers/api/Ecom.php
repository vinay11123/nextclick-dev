<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Ecom extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
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
        $this->load->model('delivery_job_model');
        $this->load->model('delivery_boy_biometric_model');
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
        $this->load->model('pickup_orders_model');
        $this->load->model('ecom_order_deatils_model');
        $this->load->model('ecom_payment_model');
        $this->load->model('ecom_order_status_model');
        $this->load->model('ecom_order_status_log_model');
        $this->load->model('location_model');
        $this->load->model('promotion_banner_model');
        $this->load->model('promos_model');
        $this->load->model('Vendor_in_active_menu_model');
        $this->load->model('vendor_in_active_shop_by_category_model');
        $this->load->model('ecom_order_reject_request_model');
        $this->load->model('return_policies_model');
        $this->load->model('setting_model');
        $this->load->model('service_tax_model');
        $this->load->model('business_address_model');
        $this->load->model('brand_model');
        $this->load->model('delivery_mode_model');
        $this->load->model('user_account_model');
        $this->load->model('vehicle_model');
        $this->load->model('floating_data_payments_model');
        $this->load->model('delivery_partner_location_tracking_model');
        $this->load->model('notifications_model');
        $this->load->model('delivery_fee_model');
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
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

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

            $admin_ids = [];
            array_push($admin_ids, $token_data->id);
            if (empty($target)) {
                $query = "SELECT sc.id, sc.cat_id, sc.type, sc.name, sc.desc, sc.product_type_widget_status FROM sub_categories as sc WHERE";
                if (!empty($this->input->post('q'))) {
                    $query .= "  sc.name LIKE('" . $this->input->post('q') . "%') and ";
                }
                if (!$this->ion_auth->in_group('admin', $token_data->id)) {
                    $query .= " sc.cat_id=" . $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get()['category_id'] . " and ";
                }
                $query .= " sc.type = 2 and sc.deleted_at is null  ORDER BY sc.name asc";
                $this->data['sub_categories'] = $this->db->query($query)->result_array();
                /*Code for adding search products to the table for history*/
                $product_search = $this->input->post('q');
                $created_user_id = $token_data->id;
                $data = array(
                    'product_search' => $product_search,
                    'created_user_id' => $created_user_id
                );

                $this->db->insert('product_search_history', $data);
                /*Code for adding search products to the table for history*/
                foreach ($this->data['sub_categories'] as $key => $sbc) {
                    // $is_exist = $this->vendor_in_active_shop_by_category_model->where(['vendor_id' => $token_data->id, 'sub_cat_id' => $sbc['id']])->get();
                    // $this->data['sub_categories'][$key]['status'] = 1;
                    $this->data['sub_categories'][$key]['image'] = base_url() . 'uploads/sub_category_image/sub_category_' . $sbc['id'] . '.jpg';
                }

                $this->set_response_simple($this->data['sub_categories'], 'shop by categories list', REST_Controller::HTTP_OK, TRUE);
            } else {
                $sub_category = $this->sub_category_model->fields('id, cat_id, name, desc, status,product_type_widget_status')
                    ->where('id', $target)
                    ->with_menus('fields: id, sub_cat_id, name, desc, status')
                    ->get();
                if (!empty($sub_category['menus'])) {
                    foreach ($sub_category['menus'] as $key => $menu) {
                        $sub_category['menus'][$key]['image'] = base_url() . 'uploads/food_menu_image/food_menu_' . $menu['id'] . '.jpg';
                    }
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
                if ($subcat['created_user_id'] == $token_data->id) {
                    $this->sub_category_model->update([
                        'id' => $this->input->post('id'),
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc'),
                        'status' => $this->input->post('status')
                    ], 'id');

                    if (!empty($this->input->post('image'))) {
                        if (!file_exists('uploads/' . 'sub_category' . '_image/')) {
                            mkdir('uploads/' . 'sub_category' . '_image/', 0777, true);
                        }
                        if (!file_exists(base_url() . "uploads/sub_category_image/sub_category_" . $this->input->post('id') . ".jpg")) {
                            unlink(base_url() . "uploads/sub_category_image/sub_category_" . $this->input->post('id') . ".jpg");
                        }
                        file_put_contents("./uploads/sub_category_image/sub_category_" . $this->input->post('id') . ".jpg", base64_decode($this->input->post('image')));
                    }
                } else {
                    $this->sub_category_model->update([
                        'id' => $this->input->post('id'),
                        'status' => $this->input->post('status')
                    ], 'id');
                }

                $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
            }
        } elseif ($type == 'd') {
            $sub_category = $this->sub_category_model->get($target);
            if (!empty($sub_category) && $sub_category['created_user_id'] == $token_data->id) {
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
            } else {
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
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

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
            $admin_ids = [];
            array_push($admin_ids, $token_data->id);
            if (empty($target)) {
                $vendor = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get();
                if (!empty($vendor)) {
                    $sub_categories = $this->sub_category_model->fields('id, name, desc, cat_id')
                        ->where('created_user_id', $admin_ids)
                        ->where([
                            'cat_id' => $vendor['category_id'],
                            'type' => 2
                        ])
                        ->get_all();
                    //echo $this->db->last_query();exit;
                    if (!empty($sub_categories)) {
                        if (!empty($this->input->post('q'))) {
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
                $menu = $this->food_menu_model->fields('id, name, desc, status')
                    ->with_shop_by_category('fields: id, name, desc')
                    ->with_items('fields: id, name', 'where: created_user_id IN(' . implode(",", $admin_ids) . ')')
                    ->get($target);
                $menu['image'] = base_url() . 'uploads/food_menu_image/food_menu_' . $target . '.jpg';
                $this->set_response_simple($menu, 'menu details', REST_Controller::HTTP_OK, TRUE);
            }
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->food_menu_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $menu = $this->food_menu_model->get($this->input->post('id'));
                if ($this->input->post('status') == 1) {
                    $this->db->where([
                        'sub_cat_id' => $this->input->post('sub_cat_id'),
                        'menu_id' => $this->input->post('id'),
                        'created_user_id' => $token_data->id
                    ]);
                    $this->db->delete('vendor_in_active_menus');
                } else {
                    $this->db->insert('vendor_in_active_menus', [
                        'sub_cat_id' => $this->input->post('sub_cat_id'),
                        'menu_id' => $this->input->post('id'),
                        'created_user_id' => $token_data->id
                    ]);
                }
                if (!empty($menu) && $menu['created_user_id'] == $token_data->id) {
                    $is_updated = $this->food_menu_model->update([
                        'id' => $this->input->post('id'),
                        'sub_cat_id' => $this->input->post('sub_cat_id'),
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc')
                    ], 'id');

                    if (!empty($this->input->post('image'))) {
                        if (!file_exists('uploads/' . 'food_menu' . '_image/')) {
                            mkdir('uploads/' . 'food_menu' . '_image/', 0777, true);
                        }
                        if (!file_exists(base_url() . "uploads/food_menu_image/food_menu_" . $this->input->post('id') . ".jpg")) {
                            unlink(base_url() . "uploads/food_menu_image/food_menu_" . $this->input->post('id') . ".jpg");
                        }
                        file_put_contents("./uploads/food_menu_image/food_menu_" . $this->input->post('id') . ".jpg", base64_decode($this->input->post('image')));
                    }
                    $this->set_response_simple($is_updated, 'Menu Updated', REST_Controller::HTTP_ACCEPTED, TRUE);
                } else {
                    $this->set_response_simple(NULL, 'No privilege to Update', REST_Controller::HTTP_OK, TRUE);
                }
            }
        } elseif ($type == 'd') {
            $menu = $this->food_menu_model->get($target);
            if (!empty($menu) && $menu['created_user_id'] == $token_data->id) {
                $this->food_menu_model->delete([
                    'id' => $target
                ]);
                $this->set_response_simple(NULL, 'Menu deleted..!', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'No privilege to  delete..!', REST_Controller::HTTP_OK, TRUE);
            }
        } elseif ($type == 'change_status') {
            $this->Vendor_in_active_menu_model->delete();
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
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

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
                    'desc' => (empty($this->input->post('desc'))) ? NULL : $this->input->post('desc'),
                    'sounds_like' => $sounds_like,
                    'availability' => 1,
                    'item_type' => $this->input->post('item_type'),
                    'status' => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 3
                ]);
                if ($item_id) {
                    $section_id = $this->food_section_model->insert([
                        'menu_id' => $menu_id,
                        'item_id' => $item_id,
                        'name' => NULL
                    ]);
                    if ($section_id && !empty($variants)) {
                        $section_items = [];
                        foreach ($variants as $key => $variant) {
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

                    if (!empty($this->input->post('item_images'))) {
                        if (!file_exists('uploads/' . 'food_item' . '_image/')) {
                            mkdir('uploads/' . 'food_item' . '_image/', 0777, true);
                        }
                        foreach ($this->input->post('item_images') as $key => $image) {
                            $product_image_id = $this->food_item_image_model->insert([

                                'item_id' => $item_id,
                                'serial_number' => ++$key,
                                'ext' => 'jpg'
                            ]);
                            file_put_contents("./uploads/food_item_image/food_item_" . $product_image_id . ".jpg", base64_decode($image));
                        }
                    }
                    $vendor_business_name = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get()['business_name'];
                    $notification_id = $this->notifications_model->insert([
                        'notification_type_id' => 28,
                        'app_details_id' => 5,
                        'title' => $this->input->post('product_name') . " Product is Cretaed!",
                        'message' => 'New product is cretaed by ' . $vendor_business_name,
                        'notified_user_id' => 1
                    ]);
                    $this->set_response_simple(($item_id == FALSE) ? FALSE : $item_id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($item_id == FALSE) ? FALSE : $item_id, 'Failed..!', REST_Controller::HTTP_OK, FALSE);
                }
            }
        } elseif ($type == 'list') {
            $admin_ids = $this->get_users_by_group(1);
            array_push($admin_ids, $token_data->id);
            $vendor = $this->vendor_list_model->fields('category_id')
                ->where('vendor_user_id', $token_data->id)
                ->get();
            $shop_by_categories = $this->sub_category_model->where([
                'cat_id' => $vendor['category_id'],
                'type' => 2
            ])->get_all();
            $sub_cat_ids = (empty($shop_by_categories)) ? NULL : array_column($shop_by_categories, 'id');
            $sub_cat_id = $this->input->post('shop_by_cat_id');
            $menu_id = $this->input->post('menu_id');
            $status = $this->input->post('status');
            $search_text = $this->input->post('q');
            /*Code for adding search products to the table for history*/
            $created_user_id = $token_data->id;
            $data = array(
                'product_search' => $search_text,
                'created_user_id' => $created_user_id
            );

            $this->db->insert('product_search_history', $data);
            /*Code for adding search products to the table for history*/
            if (!empty($sub_cat_id))
                $sub_cat_ids = [
                    $sub_cat_id
                ];

            if (!empty($menu_id))
                $this->db->where('menu_id', $menu_id);
            // if (!empty($search_text)) {
            //     $this->db->like('name', $search_text);
            //     $this->db->or_like('sounds_like', metaphone($search_text));
            // }
                if (!empty($search_text)) {
                    $this->db->group_start()
                        ->like('name', $search_text)
                        ->or_like('sounds_like', metaphone($search_text))
                    ->group_end();
                }


            if (!empty($status)) {
                if ($status != 1) {
                    $admin_ids = [
                        $token_data->id
                    ];
                }
                $status = [
                    $status
                ];
            } else {
                $status = [];
            }

            $all_catalogue_products = $this->food_item_model->where('sub_cat_id', $sub_cat_ids)
              ->where('created_user_id', $admin_ids)
                ->where('status', $status)
                ->count_rows();

            if (!empty($sub_cat_id))
                $sub_cat_ids = [
                    $sub_cat_id
                ];

            if (!empty($menu_id))
                $this->db->where('menu_id', $menu_id);

            // if (!empty($search_text)) {
            //     $this->db->like('name', $search_text);

            //   foreach (explode(' ', $search_text) as $s) {
            //         $this->db->or_like('sounds_like', metaphone($s));
            //     }
            // }
                    if (!empty($search_text)) {
            $this->db->group_start()
                ->like('name', $search_text);
        
            foreach (explode(' ', $search_text) as $s) {
                $this->db->or_like('sounds_like', metaphone($s));
            }
        
            $this->db->group_end();
        }
            $catalogue_products = $this->food_item_model
                ->with_menu('fields: id, name')
                ->with_sub_category('fields: id, name')
                ->with_item_images('fields: id, item_id, serial_number, ext')
                ->where('sub_cat_id', $sub_cat_ids)
                //->where('created_user_id', $admin_ids)
                ->where('status', $status)
                ->order_by('id', 'DESC')
                ->paginate(10, $all_catalogue_products, (empty($this->input->post('page_no'))) ? 1 : $this->input->post('page_no'));

            if (!empty($catalogue_products)) {
                foreach ($catalogue_products as $food_item => $item) {
                    $vendor_products[$food_item] = $this->vendor_product_variant_model->where(['item_id' => $item['id'], 'vendor_user_id' => $token_data->id])->get_all();
                    if (!empty($vendor_products[$food_item])) {
                        $catalogue_products[$food_item]['myinventory'] = 1;
                    } else {
                        $catalogue_products[$food_item]['myinventory'] = 0;
                    }
                }
            }

            if (!empty($catalogue_products)) {
                foreach ($catalogue_products as $key => $v) {
                    if (!empty($catalogue_products[$key]['item_images'])) {
                        foreach ($catalogue_products[$key]['item_images'] as $k => $img) {
                            $catalogue_products[$key]['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                        }
                    } else {
                        $catalogue_products[$key]['item_images'] = NULL;
                    }
                }
            }
            if (!empty($catalogue_products)) {
                $this->set_response_simple([
                    "result" => $catalogue_products,
                    "total_products_count" => $all_catalogue_products,
                    "products_per_page" => 10,
                    'total number of pages' => ceil($all_catalogue_products / 10),
                    'current_page' => (empty($this->input->post('page_no'))) ? 1 : $this->input->post('page_no')
                ], (empty($this->input->post('page_no'))) ? 'Page No:' . '1' : 'Page No:' . '\'' . $this->input->post('page_no') . '\'', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple([
                    "result" => NULL,
                    "total_products_count" => 0,
                    "products_per_page" => 10,
                    'total number of pages' => 1,
                    'current_page' => (empty($this->input->post('page_no'))) ? 1 : $this->input->post('page_no')
                ], 1, REST_Controller::HTTP_OK, TRUE);
            }
        } elseif ($type == 'item_details') {
            $catalogue_product = $this->food_item_model->with_menu('fields: id, name')
                ->with_sub_category('fields: id, name')
                ->with_sections('fields: id, name')
                ->with_brand('fields: id, name')
                ->with_item_images('fields: id, item_id, serial_number, ext')
                ->with_section_items('fields: id, section_item_code, name, desc, price, weight, status, created_at, updated_at')
                ->order_by('id', 'DESC')
                ->get($target);
            if (!empty($catalogue_product)) {
                foreach ($catalogue_product as $key => $v) {
                    if (!empty($catalogue_product[$key]['item_images'])) {
                        foreach ($catalogue_product[$key]['item_images'] as $k => $img) {
                            $catalogue_product[$key]['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                        }
                    } else {
                        $catalogue_product[$key]['item_images'] = NULL;
                    }
                }
            }
            $this->set_response_simple($catalogue_product, "Success..!", REST_Controller::HTTP_OK, TRUE);
        } elseif ($type == 'r') {
            $admin_ids = $this->get_users_by_group(1);
            array_push($admin_ids, $token_data->id);
            $deleted_items = $this->db->get_where('deleted_items', [
                'vendor_id' => $token_data->id
            ])->result_array();
            if ($deleted_items) {
                $deleted_items = array_column($deleted_items, 'item_id');
            } else {
                $deleted_items = [
                    0
                ];
            }
            if (empty($target)) {
                $vendor = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get();
                $shop_by_categories = $this->shop_by_category_model->where('cat_id', $vendor['category_id'])
                    ->where('vendor_id', $admin_ids)
                    ->get_all();
                $sub_cat_ids = (empty($shop_by_categories)) ? NULL : array_column($shop_by_categories, 'sub_cat_id');
                if (!empty($this->input->post('shop_by_cat_id')))
                    $this->db->where('sub_cat_id', $this->input->post('shop_by_cat_id'));

                if (!empty($this->input->post('menu_id')))
                    $this->db->where('menu_id', $this->input->post('menu_id'));

                if (!empty($this->input->post('q'))) {
                    foreach (explode(' ', $this->input->post('q')) as $s) {
                        $this->db->or_like('sounds_like', metaphone($s));
                    }
                }

                $all_catalogue_products = $this->food_item_model->where('created_user_id', $admin_ids)
                    ->where('sub_cat_id', $sub_cat_ids)
                    ->where('id NOT', $deleted_items)
                    ->count_rows();

                if (!empty($this->input->post('q'))) {
                    foreach (explode(' ', $this->input->post('q')) as $s) {
                        $this->db->or_like('sounds_like', metaphone($s));
                    }
                }

                if (!empty($this->input->post('shop_by_cat_id')))
                    $this->db->where('sub_cat_id', $this->input->post('shop_by_cat_id'));

                if (!empty($this->input->post('menu_id')))
                    $this->db->where('menu_id', $this->input->post('menu_id'));

                $catalogue_products = $this->food_item_model->with_menu('fields: id, name')
                    ->with_sub_category('fields: id, name')
                    ->with_brand('fields: id, name')
                    ->with_item_images('fields: id, serial_number, ext')
                    ->where('created_user_id', $admin_ids)
                    ->where('sub_cat_id', $sub_cat_ids)
                    ->where('id NOT', $deleted_items)
                    ->order_by('id', 'DESC')
                    ->paginate(10, $all_catalogue_products, (empty($this->input->post('page_no'))) ? 1 : $this->input->post('page_no'));
                if (!empty($catalogue_products)) {
                    if (!empty($catalogue_product['item_images'])) {
                        foreach ($catalogue_product['item_images'] as $k => $img) {
                            $catalogue_product['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                        }
                    } else {
                        $catalogue_product['item_images'] = NULL;
                    }
                }
                if (!empty($shop_by_categories)) {
                    $this->set_response_simple([
                        "result" => $catalogue_products,
                        "total_products_count" => $all_catalogue_products,
                        "products_per_page" => 10,
                        'total number of pages' => ceil($all_catalogue_products / 10),
                        'current_page' => (empty($this->input->post('page_no'))) ? 1 : $this->input->post('page_no')
                    ], (empty($this->input->post('page_no'))) ? 'Page No:' . '1' : 'Page No:' . '\'' . $this->input->post('page_no') . '\'', REST_Controller::HTTP_OK, TRUE);
                } else {
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
                $catalogue_product['product_image'] = base_url() . 'uploads/food_item_image/food_item_' . $catalogue_product['id'] . '.jpg' . '?' . time();
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
                    'desc' => (empty($this->input->post('desc'))) ? NULL : $this->input->post('desc'),
                    'sounds_like' => $sounds_like,
                    'availability' => $this->input->post('availability'),
                    'item_type' => $this->input->post('item_type'),
                    'status' => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 3
                ], 'id');
                if ($is_updated) {
                    if (!empty($variants)) {
                        foreach ($variants as $key => $variant) {
                            if (!empty($variant['variant_id'])) {
                                $this->food_sec_item_model->update([
                                    'id' => $variant['variant_id'],
                                    'price' => (empty($variant['price'])) ? NULL : $variant['price'],
                                    'weight' => (empty($variant['weight'])) ? NULL : $variant['weight'],
                                    'desc' => (empty($variant['desc'])) ? NULL : $variant['desc'],
                                    'name' => $variant['option_name'],
                                    'status' => $variant['status']
                                ], 'id');
                            } else {
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

                    if (!empty($this->input->post('item_images'))) {
                        if (!file_exists('uploads/' . 'food_item' . '_image/')) {
                            mkdir('uploads/' . 'food_item' . '_image/', 0777, true);
                        }
                        foreach ($this->input->post('item_images') as $key => $image) {
                            if (!empty($image['id'])) {
                                $this->food_item_image_model->update([
                                    'id' => $image['id'],
                                    'ext' => 'jpg'
                                ], 'id');
                                $product_image_id = $image['id'];
                            } else {
                                $product_images = $this->food_item_image_model->where('item_id', $item_id)->get_all();
                                $last_image_id = 0;
                                if (!empty($product_images))
                                    $last_image_id = max(array_column($product_images, 'serial_number'));

                                $product_image_id = $this->food_item_image_model->insert([
                                    'item_id' => $item_id,
                                    'serial_number' => ++$last_image_id,
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
            if (!empty($food_item) && $food_item['created_user_id'] == $token_data->id) {
                $this->food_item_model->delete($target);
                $this->set_response_simple(NULL, 'Product deleted..!', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'No privilege to  delete..!', REST_Controller::HTTP_OK, TRUE);
            }
        } elseif ($type == 'delete_image') {
            $is_deleted = $this->food_item_image_model->delete([
                'id' => $this->input->post('image_id')
            ]);
            if ($is_deleted) {
                if (file_exists("./uploads/food_item_image/food_item_" . $this->input->post('image_id') . ".jpg")) {
                    unlink('./uploads/' . 'food_item' . '_image/' . 'food_item' . '_' . $this->input->post('image_id') . '.jpg');
                }
                $this->set_response_simple(NULL, 'Product Image is deleted..!', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'Sorry, Failed to  delete..!', REST_Controller::HTTP_OK, FALSE);
            }
        }
    }

    public function product_new_variants_post()
    {
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->food_sec_item_model->user_id = $token_data->id;

        $catalogue_product = $this->food_item_model->with_menu('fields: id, name')
            ->with_sub_category('fields: id, name')
            ->with_sections('fields: id, name')
            ->where('id', $this->input->post('product_id'))
            ->get();

        $vendor = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get();
        $product_id = $this->input->post('product_id');
        $menu_id = $catalogue_product['menu_id'];
        $section_id = $catalogue_product['sections'][0]['id'];
        $variants = $this->input->post('variants');
        $is_added = false;
        $result = [];

        if ($product_id) {
            if (!empty($variants)) {
                $section_items = [];
                $variant_names = [];
                foreach ($variants as $key => $variant_data) {
                    $variant_names[] = $variant_data['option_name'];
                    $duplicate_sql = "SELECT count(id) duplicate_name_length FROM food_sec_item WHERE `item_id`=" . $product_id . " AND `name`='" . $variant_data['option_name'] . "'";
                    $query = $this->db->query($duplicate_sql);
                    $duplicate = $query->result_array();
                    if ($duplicate[0]['duplicate_name_length'] > 0) {
                        $this->set_response_simple($variant_data['option_name'] . ' is already exist', 'Failed..!', REST_Controller::HTTP_OK, FALSE);
                        return;
                    }
                }
                foreach (array_count_values($variant_names) as $key => $val) {
                    if ($val > 1) $result[] = $key;   //Push the key to the array sice the value is more than 1
                }

                if (count($result) == 0) {
                    foreach ($variants as $key => $variant) {
                        $item_sec_id = $this->food_sec_item_model->insert([
                            'menu_id' => $menu_id,
                            'item_id' => $product_id,
                            'sec_id' => $section_id,
                            'section_item_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                            'price' => (empty($variant['price'])) ? NULL : $variant['price'],
                            'weight' => (empty($variant['weight'])) ? NULL : $variant['weight'],
                            'name' => $variant['option_name'],
                            'status' => 1
                        ]);
                        if ($item_sec_id) {
                            $item_sec_sql = "SELECT fsi.id variant_id,sc.name sub_cat_name,fm.name menu_name FROM `food_sec_item` fsi
                        JOIN food_item fi on fi.id=fsi.item_id
                        JOIN sub_categories sc on sc.id=fi.sub_cat_id
                        JOIN food_menu fm on fm.id=fsi.menu_id
                        where fsi.id=" . $item_sec_id;
                            $query_sqc = $this->db->query($item_sec_sql);
                            $item_sec = $query_sqc->result_array();

                            $section_item_vp = [];
                            array_push($section_item_vp, [
                                'item_id' => $product_id,
                                'section_id' => $section_id,
                                'section_item_id' => $item_sec_id,
                                'sku' => generate_serial_no($vendor['unique_id'] . '-' . metaphone($item_sec[0]['sub_cat_name']) . '-' . metaphone($item_sec[0]['menu_name']) . '-', 2, $key),
                                'price' => (empty($variant['price'])) ? NULL : $variant['price'],
                                'stock' => (empty($variant['stock'])) ? NULL : $variant['stock'],
                                'discount' => $variant['discount'],
                                'tax_id' => (empty($variant['tax_id'])) ? NULL : $variant['tax_id'],
                                'vendor_user_id' => $token_data->id,
                                'created_user_id' => $token_data->id,
                                'list_id' => $vendor['id'],
                                'status' => 1,
                            ]);
                            if ($this->db->insert_batch('vendor_product_variants', $section_item_vp)) {
                                $is_added = true;
                            }
                        }
                    }
                    if ($is_added) {
                        $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                    }
                } else {
                    $this->set_response_simple('Duplicate option names are not allowed', 'Failed..!', REST_Controller::HTTP_OK, FALSE);
                }
            }
        } else {
            $this->set_response_simple(NULL, 'Failed..!', REST_Controller::HTTP_OK, FALSE);
        }
    }

    /**
     * To manage vendor products
     *
     * @author Mehar
     *        
     * @param string $type
     * @param number $target
     */
    public function vendor_products_post($type = 'my_products', $target = 0)
    {
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if ($type == 'my_products') {
            $stock_type = $this->input->post('stock_type');
            $data = $this->vendor_product_variant_model->all((!empty($this->input->post('limit'))) ? $this->input->post('limit') : NUll, (!empty($this->input->post('offset'))) ? $this->input->post('offset') : NUll, (!empty($this->input->post('shop_by_cat_id'))) ? $this->input->post('shop_by_cat_id') : NUll, (!empty($this->input->post('menu_id'))) ? $this->input->post('menu_id') : NUll, (!empty($this->input->post('brand_id'))) ? $this->input->post('brand_id') : NUll, (!empty($this->input->post('q'))) ? $this->input->post('q') : NUll, $token_data->id, (!empty($this->input->post('stock_type'))) ? $this->input->post('stock_type') : NUll);
            $min_stock = $this->db->query("SELECT sum(min_stock) as min_stock FROM ecom_settings where created_user_id = " . $token_data->id . ";")->result_array()[0]['min_stock'];
            $min_stock = (!empty($min_stock)) ? $min_stock : 0;
            $where_in_vendor_product = " ";
            if (is_null($stock_type)) {
                $where_in_vendor_product .= " and stock>" . $min_stock;
            } elseif ($stock_type == 'instock') {
                $where_in_vendor_product .= " and stock>" . $min_stock;
            } elseif ($stock_type == 'outofstock') {
                $where_in_vendor_product .= " and stock <=" . $min_stock;
            }
            if (!empty($data['result'])) {
                foreach ($data['result'] as $key => $val) {
                    $data['result'][$key]['sub_category'] = $this->sub_category_model->fields('id, name, desc')
                        ->where('id', $data['result'][$key]['sub_cat_id'])
                        ->get();
                    $data['result'][$key]['menu'] = $this->food_menu_model->fields('id, name, desc')
                        ->where('id', $data['result'][$key]['menu_id'])
                        ->get();
                    $data['result'][$key]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $val['image_id'] . '.' . $val['ext'] . '?' . time();
                    $data['result'][$key]['vendor_product_details'] = $this->food_item_model->with_menu('fields: id, name')
                        ->with_sub_category('fields: id, name')
                        ->with_brand('fields: id, name')
                        ->with_sections('fields: id, name')
                        ->with_item_images('fields: id, serial_number, ext')
                        ->with_section_items('fields: id, section_item_code, name, desc, price, weight, status, created_at, updated_at')
                        ->with_vendor_product_varinats('fields: id, item_id, section_id, section_item_id, sku, price, stock, discount, tax_id, list_id, vendor_user_id, status', 'where: vendor_user_id=' . $token_data->id . $where_in_vendor_product)
                        ->where('id', $data['result'][$key]['id'])
                        ->get();
                    if (!empty($data['result'][$key]['vendor_product_details']['vendor_product_varinats'])) {
                        foreach ($data['result'][$key]['vendor_product_details']['vendor_product_varinats'] as $k => $val) {
                            // $data['result'][$key]['vendor_product_details']['vendor_product_varinats'][$k]['section_item_details'] = $this->food_sec_item_model->fields('name, weight')
                            //     ->where('id', $val['section_item_id'])
                            //     ->get();
                            $sectionItem = $this->food_sec_item_model->fields('name, weight')
                        ->where('id', $val['section_item_id'])
                        ->get();
                    
                    $data['result'][$key]['vendor_product_details']['vendor_product_varinats'][$k]['section_item_details'] =
                        ($sectionItem === false) ? NULL : $sectionItem;
                            if (!empty($val['tax_id'])) {
                                $tax = $this->tax_model->fields('id, tax, rate')
                                    ->with_tax_type('fields: id, name, desc')
                                    ->where('id', $val['tax_id'])
                                    ->get();
                            } else {
                                $tax = NULL;
                            }

                            $data['result'][$key]['vendor_product_details']['vendor_product_varinats'][$k]['tax'] = $tax;
                        }
                    }
                    if (!empty($data['result'][$key]['vendor_product_details'])) {
                        if (!empty($data['result'][$key]['vendor_product_details']['item_images'])) {
                            foreach ($data['result'][$key]['vendor_product_details']['item_images'] as $k => $img) {
                                $data['result'][$key]['vendor_product_details']['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                            }
                        } else {
                            $data['result'][$key]['vendor_product_details']['item_images'] = NULL;
                        }
                    }
                }
            }
            $this->set_response_simple((empty($data['result'])) ? NULL : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } elseif ($type == 'vendor_product_details') {
            $catalogue_product = $this->food_item_model->with_menu('fields: id, name')
                ->with_sub_category('fields: id, name')
                ->with_brand('fields: id, name')
                ->with_sections('fields: id, name')
                ->with_item_images('fields: id, serial_number, ext')
                ->with_section_items('fields: id, section_item_code, name, desc, price, weight, status, created_at, updated_at')
                ->with_vendor_product_varinats('fields: id, item_id, section_id, section_item_id, sku, price, stock, discount, tax_id, list_id, vendor_user_id,return_id,return_available, status', 'where: vendor_user_id=' . $token_data->id)
                ->get($target);
            if (!empty($catalogue_product['vendor_product_varinats'])) {
                foreach ($catalogue_product['vendor_product_varinats'] as $key => $val) {
                    $name = $this->food_sec_item_model->fields('name, weight')
                        ->where('id', $val['section_item_id'])
                        ->get();
                    $catalogue_product['vendor_product_varinats'][$key]['section_item_name'] = !empty($name) ? $name['name'] : NULL;
                    $catalogue_product['vendor_product_varinats'][$key]['weight'] = !empty($name) ? $name['weight'] : NULL;
                    if (!empty($val['tax_id'])) {
                        $tax = $this->tax_model->fields('id, tax, rate')
                            ->with_tax_type('fields: id, name, desc')
                            ->where('id', $val['tax_id'])
                            ->get();
                    } else {
                        $tax = NULL;
                    }
                    $catalogue_product['vendor_product_varinats'][$key]['tax'] = $tax;
                }
            }
            if (!empty($catalogue_product)) {
                if (!empty($catalogue_product['item_images'])) {
                    foreach ($catalogue_product['item_images'] as $k => $img) {
                        $catalogue_product['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                    }
                } else {
                    $catalogue_product['item_images'] = NULL;
                }
            }
            $this->set_response_simple($catalogue_product, "Success..!", REST_Controller::HTTP_OK, TRUE);
        } elseif ($type == 'add_to_my_list') {
            $this->form_validation->set_rules($this->vendor_product_variant_model->rules['create']);
            $this->vendor_product_variant_model->user_id = $token_data->id;
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_OK, FALSE);
            } else {
                $vendor = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get();
                $item = $this->food_item_model->with_menu('fields: id, name')
                    ->with_sub_category('fields: id, name')
                    ->with_section_items('fields: id, sec_id, item_id, name, desc, price, status, created_at, updated_at')
                    ->where('id', $this->input->post('item_id'))
                    ->get();
                if (!empty($item) && !empty($item['section_items'])) {
                    $are_items_existed = $this->vendor_product_variant_model->where([
                        'item_id' => $item['id'],
                        'vendor_user_id' => $token_data->id
                    ])->get_all();
                    if (empty($are_items_existed)) {
                        $section_items = [];
                        foreach ($item['section_items'] as $key => $section_item) {
                            array_push($section_items, [
                                'item_id' => $section_item['item_id'],
                                'section_id' => $section_item['sec_id'],
                                'section_item_id' => $section_item['id'],
                                'sku' => generate_serial_no($vendor['unique_id'] . '-' . metaphone($item['sub_category']['name']) . '-' . metaphone($item['menu']['name']) . '-', 2, $key),
                                'price' => $section_item['price'],
                                'stock' => 0,
                                'discount' => 0,
                                'vendor_user_id' => $token_data->id,
                                'list_id' => !empty($vendor) ? $vendor['id'] : NULL
                            ]);
                        }
                        $is_inserted = $this->vendor_product_variant_model->insert($section_items);
                        $this->set_response_simple($is_inserted, "Success..!", REST_Controller::HTTP_OK, TRUE);
                    } else {
                        $this->set_response_simple(NULL, "Item is already added to the list.!", REST_Controller::HTTP_OK, FALSE);
                    }
                } else {
                    $this->set_response_simple(NULL, "Sorry, Item is not available..!", REST_Controller::HTTP_OK, FALSE);
                }
            }
        } elseif ($type == 'update_variant') {
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
                    'return_id' => $this->input->post('return_id'),
                    'return_available' => $this->input->post('return_available'),
                ], 'id');
                $this->set_response_simple($is_updated, "Success..!", REST_Controller::HTTP_OK, TRUE);
            }
        }
    }

    /**
     * @desc To create bulk products in inventory
     * @author Mehar
     * @param string $type
     * @return unknown
     */
    public function vendor_variants_bulk_post($type = 'r')
    {
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

        $this->vendor_product_variant_model->user_id = $token_data->id;
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if ($type == 'r') {
            $data = $this->food_item_model->get_product_by_ids($this->input->post('products'));
            $this->set_response_simple($data, "Success..!", REST_Controller::HTTP_OK, TRUE);
        } elseif ($type == 'update') {
            $vendor = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get();
            $variants = $this->input->post('variants');
            if (empty($variants)) {
                return $this->set_response_simple(NULL, "Invalid data..!", REST_Controller::HTTP_OK, FALSE);
            }
            $section_items = [];

            foreach ($variants as $key => $variant) {
                array_push($section_items, [
                    'item_id' => $variant['item_id'],
                    'section_id' => $variant['sec_id'],
                    'section_item_id' => $variant['section_item_id'],
                    'sku' => generate_serial_no('SKU', 2, rand(99999, 999999)),
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                    'tax_id' => $variant['tax_id'],
                    'discount' => $variant['discount'],
                    'vendor_user_id' => $token_data->id,
                    'created_user_id' => $token_data->id,
                    'list_id' => !empty($vendor) ? $vendor['id'] : NULL,
                    'return_id' => empty($variant['return_id']) ? NULL : $variant['return_id'],
                    'return_available' => empty($variant['return_available']) ? 0 : $variant['return_available']
                ]);
            }
            $is_inserted = $this->db->insert_batch('vendor_product_variants', $section_items);
            $this->set_response_simple($is_inserted, "Success..!", REST_Controller::HTTP_OK, TRUE);
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
    public function ecom_orders_post($type = 'vendor_orders')
    {
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

        $this->ecom_order_model->user_id = $token_data->id;
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if ($type == 'vendor_orders') {
            $orders = $this->ecom_order_model->get_orders(NULL, NULL, $token_data->id, (empty($this->input->post('start_date'))) ? NULL : $this->input->post('start_date'), (empty($this->input->post('end_date'))) ? NULL : $this->input->post('end_date'), NULL, NULL, (empty($this->input->post('status'))) ? NULL : $this->input->post('status'), (empty($this->input->post('delivery_boy_status'))) ? NULL : $this->input->post('delivery_boy_status'), FALSE, 'vendor_orders');

            if (!empty($orders)) {
                foreach ($orders as $key => $order) {
                    if (!empty($order['payment_id']))
                        $orders[$key]['delivery_job'] = $this->delivery_job_model->with_rejected_reason('fields: id, reason')
                            ->fields('id, job_id, rating, feedback, job_type, delivery_boy_user_id, status, rejected_reason_id')
                            ->with_delivery_boy('fields: id, first_name, last_name, phone, unique_id')
                            ->where('ecom_order_id', $order['id'])
                            ->order_by('id', 'DESC')
                            ->get();
                    else
                        $orders[$key]['delivery_job'] = NULL;

                    if (!empty($order['payment_id']))
                        $orders[$key]['payment'] = $this->ecom_payment_model->fields('id, txn_id, amount, created_at, message, status')
                            ->with_payment_method('fields: id, name, description')
                            ->where('id', $order['payment_id'])
                            ->get();
                    else
                        $orders[$key]['payment'] = NULL;

                    if (!empty($order['order_status_id']))
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
        } elseif ($type == 'order_details') {
            //echo $this->input->post('order_id');exit;
            $order = $this->ecom_order_model->fields('id, track_id, order_delivery_otp, order_pickup_otp, order_return_otp, delivery_fee, total, used_wallet_amount, message, preparation_time, created_at, after_rejected_by_delivery_partner, customer_penalty, created_user_id')
                ->with_shipping_address('fields: id, phone, email, name, landmark, address, location_id')
                ->with_delivery_mode('fields: id, name, desc')
                ->with_customer('fields: id, unique_id, first_name, phone')
                ->with_vendor('fields: id, name, unique_id, location_id, constituency_id, category_id, vendor_user_id')
                ->with_order_status('fields: id, delivery_mode_id, status, serial_number')
                ->with_payment('fields: id, payment_method_id, txn_id, amount, created_at, message, status')
                ->with_ecom_order_details('fields: id, item_id, promocode_id, promotion_banner_id, offer_product_id, offer_product_variant_id, offer_product_qty, promocode_discount, promotion_banner_discount, vendor_product_variant_id, qty, price, rate_of_discount, sub_total, discount, tax, total, cancellation_message, status')
                ->with_reject_request('fields: id, ecom_order_id, customer_user_id, vendor_user_id, created_at, updated_at, deleted_at, status')
                ->where('id', $this->input->post('order_id'))
                ->get();

            $order['is_ecom_order'] = TRUE;
            $order['is_pickup_order'] = FALSE;
            //print_r($order);exit;
            $vendorConstituency = $this->business_address_model->where([
                'list_id' => $order['vendor']['id']
            ])->get();
            $order['vendor']['constituency_id'] = $vendorConstituency['constituency'];
            $order['order_confirmation_time'] = $this->setting_model->where('key', 'order_confirmation_time')->get()['value'];
            $order['order_cancellation_time'] = $this->setting_model->where('key', 'order_cancellation_time')->get()['value'];
            if (!empty($order)) {
                $delivery_job = $this->delivery_job_model->with_rejected_reason('fields: id, reason')
                    ->fields('id, job_id, rating, feedback, job_type, delivery_boy_user_id, status, rejected_reason_id')
                    ->with_delivery_boy('fields: id, first_name, last_name, phone, unique_id')
                    ->with_delivery_rejections('fields: id, job_id, add_waiting_time, rejected_reason_id, rejection_reason, created_at, status')
                    ->where('ecom_order_id', $order['id'])
                    ->order_by('id', 'DESC')
                    ->get();
                $vehichleType = $this->delivery_boy_biometric_model->where([
                    'user_id' => $delivery_job['delivery_boy_user_id']
                ])->get();
                $delivery_job['vehicle_type_id'] = $vehichleType['vehicle_type_id'];
                $order['delivery_job'] = empty($delivery_job) ? NULL : $delivery_job;
                if (!empty($order['delivery_job'])) {
                    $order['delivery_job']['delivery_boy']['profile_image'] = base_url() . 'uploads/profile_image/profile_' . $order['delivery_job']['delivery_boy']['unique_id'] . '.jpg?' . time();
                    $order['delivery_job']['delivery_boy']['delivery_boy_pickup_image'] = base_url() . 'uploads/delivery_boy_pickup_image/delivery_boy_pickup_' . $order['delivery_job']['id'] . '.jpg?' . time();
                    $order['delivery_job']['delivery_boy']['delivery_boy_delivery_image'] = base_url() . 'uploads/delivery_boy_delivery_image/delivery_boy_delivery_' . $order['delivery_job']['id'] . '.jpg?' . time();
                }
                $order['delivery_mode']['order_statuses'] = $this->ecom_order_status_model->where([
                    'delivery_mode_id' => $order['delivery_mode']['id'],
                    'serial_number <' => 200
                ])->get_all();
                $order['order_status']['time'] = $this->ecom_order_status_log_model->fields('created_at')
                    ->where([
                        'ecom_order_id' => $order['id'],
                        'ecom_order_status_id' => $order['order_status']['id']
                    ])
                    ->get();
                $order['order_status']['accepted_time'] = $this->db->query("SELECT `ecom_order_status_log`.id, `ecom_order_status_log`.ecom_order_id,  `ecom_order_status_log`.`ecom_order_status_id`, `ecom_order_status_log`.`created_at` FROM `ecom_order_status_log` WHERE `ecom_order_status_log`.`ecom_order_id` = " . $order['id'] . " and ( `ecom_order_status_log`.`ecom_order_status_id` = 3 OR `ecom_order_status_log`.`ecom_order_status_id` = 11) limit 1")->result_array();
                $order['shipping_address']['location'] = $this->location_model->fields('id,latitude, longitude, address')
                    ->where('id', $order['shipping_address']['location_id'])
                    ->get();

                $order['vendor']['phone'] = $this->user_model->fields('phone')
                    ->where('id', $order['vendor']['vendor_user_id'])
                    ->get()['phone'];
                $order['customer_phone_number'] = $this->user_model->fields('phone')
                    ->where('id', $order['created_user_id'])
                    ->get()['phone'];
                $order['vendor']['location'] = $this->location_model->fields('id, latitude, longitude, address')
                    ->where('id', $order['vendor']['location_id'])
                    ->get();
                $order['vendor']['cover_image'] = base_url() . 'uploads/list_cover_image/list_cover_' . $order['vendor']['id'] . '.jpg' . '?' . time();
                $order['payment']['payment_method'] = $this->payment_method_model->where('id', $order['payment']['payment_method_id'])->get();
                if (!empty($order['ecom_order_details'])) {
                    foreach ($order['ecom_order_details'] as $key => $detials) {
                        // order details > item
                        $order['ecom_order_details'][$key]['item'] = $this->food_item_model->fields('id, name, sub_cat_id, menu_id, desc')
                            ->with_item_images('fields: id, ext')
                            ->where('id', $detials['item_id'])
                            ->get();
                        if (!empty($order['ecom_order_details'][$key]['item'])) {

                            foreach ($order['ecom_order_details'][$key]['item'] as $service => $value) {

                                $data = $this->service_tax_model->compute_service_charge(
                                    $order['vendor']['category_id'],
                                    $order['ecom_order_details'][$key]['item']['sub_cat_id'],
                                    $order['ecom_order_details'][$key]['item']['menu_id'],
                                    $order['vendor']['constituency_id'],
                                    $vendorConstituency['state'],
                                    $vendorConstituency['district']
                                );
                                $order['ecom_order_details'][$key]['item']['service_charge'] = ($data['success'] == TRUE && !empty($data['data'])) ? $data['data'] : Null;

                                /*$ServiceCharge  = $this->service_tax_model->fields('id,service_tax')
                                    ->where('cat_id' , $order['vendor']['category_id']) 
                                    ->where('sub_cat_id' , $order['ecom_order_details'][$key]['item']['sub_cat_id'])
                                    ->where('menu_id' , $order['ecom_order_details'][$key]['item']['menu_id'])
                                    ->where('constituency_id', $order['vendor']['constituency_id'])
                                    ->get();
                                if (empty($ServiceCharge)) {//menu id null
                                    $ServiceCharge  =  $this->db->query("SELECT id, service_tax FROM service_tax where 
                                    cat_id = " . $order['vendor']['category_id'] . " AND sub_cat_id = " . $order['ecom_order_details'][$key]['item']['sub_cat_id'] . " AND
                                    menu_id IS NULL AND constituency_id = " . $order['vendor']['constituency_id'] . "")->result_array()[0];
                                }
                                if (empty($ServiceCharge)) {
                                    $ServiceCharge  =  $this->db->query("SELECT id, service_tax FROM service_tax where 
                                    cat_id = " . $order['vendor']['category_id'] . " AND sub_cat_id IS NULL
                                    AND menu_id IS NULL AND constituency_id = " . $order['vendor']['constituency_id'] . "")->result_array()[0];
                                }
                                $order['ecom_order_details'][$key]['item']['service_charge'] =$ServiceCharge;*/
                            }
                        }
                        if (!empty($order['ecom_order_details'][$key]['item']['item_images'])) {
                            foreach ($order['ecom_order_details'][$key]['item']['item_images'] as $i => $val) {
                                $order['ecom_order_details'][$key]['item']['item_images'][$i]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $val['id'] . '.' . $val['ext'] . '?' . time();
                            }
                        } else {
                            $order['ecom_order_details'][$key]['item']['images'] = [];
                        }
                        $order['ecom_order_details'][$key]['item']['varinat'] = $this->vendor_product_variant_model->fields('id, sku, price, stock, discount, tax_id, return_id, return_available, status')
                            ->with_section_item('fields: id, name, weight')
                            ->with_return('fields: id,sub_cat_id,menu_id,return_days,terms_conditions')
                            ->where('id', $detials['vendor_product_variant_id'])
                            ->get();

                        // order details > offer item
                        if (!empty($order['ecom_order_details'][$key]['offer_product_id'])) {
                            $order['ecom_order_details'][$key]['offer_item'] = $this->food_item_model->fields('id, name, desc')
                                ->with_item_images('fields: id, ext')
                                ->where('id', $detials['offer_product_id'])
                                ->get();
                            if (!empty($order['ecom_order_details'][$key]['offer_item']['item_images'])) {
                                foreach ($order['ecom_order_details'][$key]['offer_item']['item_images'] as $i => $val) {
                                    $order['ecom_order_details'][$key]['offer_item']['item_images'][$i]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $val['id'] . '.' . $val['ext'] . '?' . time();
                                }
                            } else {
                                $order['ecom_order_details'][$key]['offer_item']['images'] = [];
                            }
                            $order['ecom_order_details'][$key]['offer_item']['varinat'] = $this->vendor_product_variant_model->fields('id, sku, price, stock, discount, tax_id, return_id, return_available, status')
                                ->with_section_item('fields: id, name, weight')
                                ->with_return('fields: id,sub_cat_id,menu_id,return_days,terms_conditions')
                                ->where('id', $detials['offer_product_variant_id'])
                                ->get();
                        }

                        // order details > Promotion banner
                        if (!empty($order['ecom_order_details'][$key]['promotion_banner_id'])) {
                            $order['ecom_order_details'][$key]['promotion_banner'] = $this->promotion_banner_model->where('id', $order['ecom_order_details'][$key]['promotion_banner_id'])->get();
                        } else {
                            $order['ecom_order_details'][$key]['promotion_banner'] = NULL;
                        }

                        // order details > Promotion Code
                        if (!empty($order['ecom_order_details'][$key]['promocode_id'])) {
                            $order['ecom_order_details'][$key]['promotion_code'] = $this->promos_model->where('id', $order['ecom_order_details'][$key]['promocode_id'])->get();
                        } else {
                            $order['ecom_order_details'][$key]['promotion_code'] = NULL;
                        }
                    }
                }
                $this->set_response_simple($order, 'Success.', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'Not found.', REST_Controller::HTTP_OK, FALSE);
            }
        } elseif ($type == 'accept') {
            $order_id = $this->input->post('order_id');

            if (!empty($order_id)) {

                $order_details = $this->ecom_order_model->fields('id, track_id, payment_id, delivery_mode_id, created_user_id, (ecom_orders.total - ecom_orders.delivery_fee) AS amount, delivery_fee_id')
                    ->with_vendor('fields: id, name')
                    ->where('id', $order_id)
                    ->get();

                if (!empty($order_details)) {

                    $is_updated = $this->ecom_order_model->update([
                        'id' => $order_id,
                        'preparation_time' => $this->input->post('preparation_time'),
                        'order_pickup_otp' => rand(99999, 999999),
                        'current_order_status_id' => ORDER_STATUS_ORDER_HAS_BEEN_PREPARING_ID,
                        'order_status_id' => $this->ecom_order_status_model->fields('id')
                            ->where([
                                'delivery_mode_id' => $order_details['delivery_mode_id'],
                                'serial_number' => 102
                            ])
                            ->get()['id']
                    ], 'id');

                    if ($is_updated) {
                        // $this->ecom_order_deatils_model->where(['ecom_order_id' => $order_id, 'status !=' => 4])->update(['status' => 2]);
                        if ($order_details['delivery_mode_id'] == 2) {
                            $this->load->module('delivery/api/delivery');
                            Modules::run('delivery/api/delivery/create', $order_id);
                            /* trigger notification  by manoj*/
                            $order = $this->ecom_order_model->where('id', $order_id)->get();
                            if ($order['delivery_mode_id'] == 2) {

                                $l = $this->vendor_list_model->with_location('fields: id, address, latitude, longitude')
                                    ->where('vendor_user_id', $token_data->id)
                                    ->get();

                                $lat = $l['location']['latitude'];
                                $lng = $l['location']['longitude'];
                                $max_order_distance =    $this->delivery_fee_model->where("id", $order_details['delivery_fee_id'])->get();
                                $distance = $max_order_distance['vendor_to_delivery_captain_max_distance'];
                                // $max_order_distance =    $this->setting_model->where("key", 'max_order_distance')->get();
                                // $distance = $max_order_distance['value'];

                                // $query = $this->db->query('SELECT delivery_boy_address.*, 6371 * 2 * ASIN(SQRT(POWER(SIN(RADIANS(? - ABS(delivery_boy_address.lat))), 2) + COS(RADIANS(?)) * COS(RADIANS(ABS(delivery_boy_address.lat))) * POWER(SIN(RADIANS(? - delivery_boy_address.lng)), 2))) AS distance
                                // FROM delivery_boy_address join users on users.id = delivery_boy_address.user_id where users.delivery_partner_status=1 HAVING distance < ?
                                // ', [
                                //     $lat,
                                //     $lat,
                                //     $lng,
                                //     $distance
                                // ]);

                    //             $query = $this->db->query('SELECT delivery_partner_location_tracking.delivery_partner_user_id as user_id
                    // , (6371.0088 * 2 * ASIN(SQRT(POWER(SIN(RADIANS(? - delivery_partner_location_tracking.latitude)) / 2, 2) + COS(RADIANS(?)) * COS(RADIANS(delivery_partner_location_tracking.latitude)) * POWER(SIN(RADIANS(? - delivery_partner_location_tracking.longitude)/2), 2)))) AS distance
                    // FROM delivery_partner_location_tracking join users on users.id = delivery_partner_location_tracking.delivery_partner_user_id  where users.delivery_partner_status=1 HAVING distance < ?
                    // ', [
                    //                 $lat,
                    //                 $lat,
                    //                 $lng,
                    //                 $distance
                    //             ]);
                          $earthRadius = 6371.0088; // KM
                        $max_order_distance =    $this->setting_model->where("key", 'max_order_distance')->get();    
                              log_message('error', 'MAX_ORDER_DISTANCE: ' . json_encode($max_order_distance));
                               log_message('error', 'distance: ' . $distance);
                        $primaryDistance  =  $distance;  // main radius
                        $secondaryDistance = $primaryDistance *2 ;  // extended radius
                        
                        // --------------------
                        // Bounding box for secondary radius (bigger one)
                        // --------------------
                        $latMin = $lat - ($secondaryDistance / 111);
                        $latMax = $lat + ($secondaryDistance / 111);
                        $lngMin = $lng - ($secondaryDistance / (111 * cos(deg2rad($lat))));
                        $lngMax = $lng + ($secondaryDistance / (111 * cos(deg2rad($lat))));
                        
                        $query = $this->db->query("
                            SELECT 
                                dplt.delivery_partner_user_id AS user_id,
                                (
                                    $earthRadius * 2 * ASIN(
                                        SQRT(
                                            POWER(SIN(RADIANS(? - dplt.latitude) / 2), 2) +
                                            COS(RADIANS(?)) *
                                            COS(RADIANS(dplt.latitude)) *
                                            POWER(SIN(RADIANS(? - dplt.longitude) / 2), 2)
                                        )
                                    )
                                ) AS distance
                            FROM delivery_partner_location_tracking dplt
                            INNER JOIN users u 
                                ON u.id = dplt.delivery_partner_user_id
                            WHERE 
                                u.delivery_partner_status = 1
                                AND dplt.latitude IS NOT NULL
                                AND dplt.longitude IS NOT NULL
                                AND dplt.latitude BETWEEN ? AND ?
                                AND dplt.longitude BETWEEN ? AND ?
                            HAVING distance <= ?
                            ORDER BY distance ASC
                        ", [
                            $lat,
                            $lat,
                            $lng,
                            $latMin,
                            $latMax,
                            $lngMin,
                            $lngMax,
                            $secondaryDistance
                        ]);
                        
                        $deliveryPartners = $query->result_array();
                        
                        log_message('error', 'FOUND DELIVERY PARTNERS: ' . json_encode($deliveryPartners));


                                $deal = $query->result_array();
                                                        foreach ($deliveryPartners as $partner) {
                        
                            $delivered_id = $partner['user_id'];
                            $partnerDistance = $partner['distance'];
                        
                            // Insert Deal
                            $this->food_order_deal_model->insert([
                                'order_id' => $order_id,
                                'deal_id'  => $delivered_id
                            ]);
                        
                            // Notification message based on distance
                            if ($partnerDistance <= $primaryDistance) {
                                $title = "New Nearby Order!";
                                $message = "Order #" . $order['track_id'] . " is very close to you.";
                            } else {
                                $title = "New Order Available";
                                $message = "Order #" . $order['track_id'] . " is available near your area.";
                            }
                        
                            // Send Notification
                            $this->send_notification(
                                $delivered_id,
                                DELIVERY_APP_CODE,
                                $title,
                                $message,
                                [
                                    'order_id' => $order['id'],
                                    'notification_type' => $this->notification_type_model
                                        ->where([
                                            'app_details_id' => DELIVERY_APP_CODE,
                                            'notification_code' => 'OD'
                                        ])
                                        ->get()
                                ]
                            );
                        }

                                $user_ids = array();
                                foreach ($deal as $val) {
                                    $user_ids[] = $val['user_id'];
                                }
                                $paymentType = $this->getPaymentType($order_id)[0]['paymentType'];
                                if ($paymentType == 'COD') {
                                    $userDeals = $this->verifySecurityDepositeValue($user_ids, $order_id, $order_details['amount']);
                                } else {
                                    $userDeals = $deal;
                                }
                                for ($i = 0; $i < count($userDeals); $i++) {
                                    $delivered_id = $userDeals[$i]['user_id'];
                                    $acc = $this->food_order_deal_model->insert([
                                        'order_id' => $order_id,
                                        'deal_id' => $userDeals[$i]['user_id']
                                    ]);
                                    $this->send_notification($delivered_id, DELIVERY_APP_CODE, "Order status", "New Order(id:" . $order['track_id'] . ") is Placed.! TRACK NOW", ['order_id' => $order['id'], 'notification_type' => $this->notification_type_model->where(['app_details_id' => DELIVERY_APP_CODE, 'notification_code' => 'OD'])->get()]);
                                }
                            } elseif ($order['delivery_mode_id'] == 1) {
                                $acc = $this->ec->update([
                                    'id' => $order_id,
                                    'otp' => rand(1234, 9567)
                                ], 'id');
                            }

                            $this->set_response_simple(NULL, 'Accepted', REST_Controller::HTTP_OK, TRUE);
                            /* trigger notification  by manoj*/
                        }

                        /**
                         * trigger push notificatios *
                         */
                        $this->send_notification($order_details['created_user_id'], USER_APP_CODE, "Order status of( " . $order_details['track_id'] . " )", "Your Order successfully accepted by " . strtoupper($order_details['vendor']['name']) . ".", ['order_id' => $order_id, 'notification_type' => $this->notification_type_model->where(['app_details_id' => USER_APP_CODE, 'notification_code' => 'OD'])->get()]);

                        $this->set_response_simple(NULL, 'Order has been accepted.', REST_Controller::HTTP_OK, TRUE);
                    } else {
                        $this->set_response_simple(NULL, 'Something went wrong.!', REST_Controller::HTTP_OK, FALSE);
                    }
                } else {
                    $this->set_response_simple(NULL, 'Not found.', REST_Controller::HTTP_OK, FALSE);
                }
            } else {
                $this->set_response_simple(NULL, 'Please provide order_id.', REST_Controller::HTTP_OK, FALSE);
            }
        } elseif ($type == 'reject') {
            $order_id = $this->input->post('order_id');
            if (!empty($order_id)) {
                $order_details = $this->ecom_order_model->fields('id, track_id, delivery_mode_id, created_user_id, total')
                    ->with_payment('fields: id, payment_method_id, amount, status')
                    ->with_vendor('fields: id, name')
                    ->where('id', $order_id)
                    ->get();
                if (!empty($order_details)) {
                    $is_updated = $this->ecom_order_model->update([
                        'id' => $order_id,
                        'message' => $this->input->post('reason'),
                        'order_status_id' => $this->ecom_order_status_model->fields('id')
                            ->where([
                                'delivery_mode_id' => 1,
                                'serial_number' => 300
                            ])
                            ->get()['id']
                    ], 'id');
                    if ($is_updated) {
                        $paymentDetails = $this->ecom_payment_model->fields('payment_method_id')
                            ->where('ecom_order_id', $order_id)
                            ->get();
                        if ($order_details['payment']['payment_method_id'] != 1 || ($order_details['payment']['payment_method_id'] == 1 && $order_details['payment']['status'] == 2)) {
                            $this->user_model->debitFromWallet($this->config->item('super_admin_user_id'), $order_details["total"], $order_id);
                        }
                        if ($paymentDetails["payment_method_id"] == 3) {
                            $this->user_model->creditToWallet($order_details['created_user_id'], $order_details["total"], $order_id);
                        } else if ($order_details['payment']['payment_method_id'] == 2 || ($order_details['payment']['payment_method_id'] == 1 && $order_details['payment']['status'] == 2)) {
                            $this->load->module('payment/api/payment');
                            $this->payment->initiateRefund($order_id);
                        }
                        /**
                         * trigger push notificatios *
                         */
                        $this->send_notification($order_details['created_user_id'], USER_APP_CODE, "Order status of( " . $order_details['track_id'] . " )", "We're sorry to say, That your order has been rejectd by " . strtoupper($order_details['vendor']['name']) . " Due to " . $this->input->post('reason') . ".", [
                            'order_id' => $order_id,
                            'notification_type' => $this->notification_type_model->where([
                                'app_details_id' => USER_APP_CODE,
                                'notification_code' => 'OD'
                            ])->get()
                        ]);
                        $this->set_response_simple(NULL, 'Order has been rejected.', REST_Controller::HTTP_OK, TRUE);
                    } else {
                        $this->set_response_simple(NULL, 'Something went wrong.!', REST_Controller::HTTP_OK, FALSE);
                    }
                } else {
                    $this->set_response_simple(NULL, 'Not found.', REST_Controller::HTTP_OK, FALSE);
                }
            } else {
                $this->set_response_simple(NULL, 'Please provide order_id.', REST_Controller::HTTP_OK, FALSE);
            }
        } elseif ($type == 'extend_preparation_time') {
            $order_id = $this->input->post('order_id');
            if (!empty($order_id)) {
                $order_details = $this->ecom_order_model->fields('id, track_id, delivery_mode_id, created_user_id')
                    ->with_vendor('fields: id, name')
                    ->where('id', $order_id)
                    ->get();
                if (!empty($order_details)) {
                    $is_updated = $this->ecom_order_model->update([
                        'id' => $order_id,
                        'preparation_time' => $this->input->post('preparation_time')
                    ], 'id');
                    if ($is_updated) {
                        /**
                         * trigger push notificatios *
                         */
                        $delivery_job = $this->delivery_job_model->where([
                            'ecom_order_id' => $order_id,
                            'status' => 1
                        ])->get();
                        $notify_users = [
                            $order_details['created_user_id']
                        ];
                        if (!empty($delivery_job))
                            array_push($notify_users, $delivery_job['delivery_boy_user_id']);

                        $this->send_notification($notify_users, USER_APP_CODE, "Order status of( " . $order_details['track_id'] . " )", "Preparation time is updated to " . $this->input->post('preparation_time') . " by " . strtoupper($order_details['vendor']['name']) . ".", [
                            'order_id' => $order_id,
                            'notification_type' => $this->notification_type_model->where([
                                'app_details_id' => USER_APP_CODE,
                                'notification_code' => 'OD'
                            ])->get()
                        ]);
                        $this->set_response_simple(NULL, 'Preparation time has been modified.', REST_Controller::HTTP_OK, TRUE);
                    } else {
                        $this->set_response_simple(NULL, 'Something went wrong.!', REST_Controller::HTTP_OK, FALSE);
                    }
                } else {
                    $this->set_response_simple(NULL, 'Not found.', REST_Controller::HTTP_OK, FALSE);
                }
            } else {
                $this->set_response_simple(NULL, 'Please provide order_id.', REST_Controller::HTTP_OK, FALSE);
            }
        } elseif ($type == 'verify_out_for_delivery') {
            $order_id = $this->input->post('order_id');
            if (!empty($order_id)) {
                $order_details = $this->ecom_order_model->fields('id, track_id, delivery_mode_id, order_pickup_otp, total, delivery_fee, created_user_id, vendor_user_id')
                    ->with_ecom_order_details('fields: id, ecom_order_id, promocode_id, promotion_banner_id, item_id, vendor_product_variant_id, qty, offer_product_id, offer_product_variant_id, offer_product_qty, price, rate_of_discount, sub_total, discount, promocode_discount, promotion_banner_discount, tax, total, service_charge_amount, final_amount, cancellation_message, status')
                    ->with_payment('fields: id, payment_method_id, amount, status')
                    ->with_vendor('fields: id, name, constituency_id, category_id')
                    ->where('id', $order_id)
                    ->get();

                if (!empty($order_details)) {
                    if ($order_details['order_pickup_otp'] == $this->input->post('otp')) {
                        $is_updated = $this->ecom_order_model->update([
                            'id' => $order_id,
                            'order_status_id' => $this->ecom_order_status_model->fields('id')
                                ->where([
                                    'delivery_mode_id' => $order_details['delivery_mode_id'],
                                    'serial_number' => ($order_details['delivery_mode_id'] == 1) ? 104 : 103
                                ])
                                ->get()['id']
                        ], 'id');
                        if ($is_updated) {
                            /*
                             * $delivery_job = $this->delivery_job_model->where(['ecom_order_id' => $order_id, 'status >=' => 501])->get();
                             * $this->delivery_job_model->update([
                             * 'id' => $delivery_job['id'],
                             * 'status' => 505,
                             * ], 'id');
                             */
                            $notify_users = [
                                $order_details['created_user_id']
                            ];
                            /*
                             * if(! empty($delivery_job))
                             * array_push($notify_users, $delivery_job['delivery_boy_user_id']);
                             */
                            // Tringger notification
                            $this->send_notification($notify_users, USER_APP_CODE, "Order status of( " . $order_details['track_id'] . " )", "Your order is out for delivery by " . strtoupper($order_details['vendor']['name']) . ".", [
                                'order_id' => $order_id,
                                'notification_type' => $this->notification_type_model->where([
                                    'app_details_id' => USER_APP_CODE,
                                    'notification_code' => 'OD'
                                ])
                                    ->get()
                            ]);
                            // Wallet money distribution
                            if (!($order_details['delivery_mode_id'] == 1 && ($order_details['payment']['payment_method_id'] == 1 && $order_details['payment']['status'] != 2))) {
                                $total_service_charge = 0;
                                if (!empty($order_details['ecom_order_details'])) {
                                    $vendorConstituency = $this->business_address_model->where([
                                        'list_id' => $order_details['vendor']['id']
                                    ])->get();

                                    foreach ($order_details['ecom_order_details'] as $key => $order_item) {
                                        $data = $this->service_tax_model->calculate_service_charge($order_item, $order_details['vendor']['category_id'], $vendorConstituency['constituency'], $vendorConstituency['state'], $vendorConstituency['district']);

                                        $each_item_service_charge = ($data['success'] == TRUE && !empty($data['data'])) ? floatval($order_item['total']) * (intval($data['data']['service_tax']) / 100) : 0;

                                        $final_amount_after_sc = floatval($order_item['total']) - $each_item_service_charge;
                                        $total_service_charge += $each_item_service_charge;
                                        $this->ecom_order_deatils_model->update([
                                            'id' => $order_item['id'],
                                            'service_charge_amount' => $each_item_service_charge,
                                            'final_amount' => $final_amount_after_sc
                                        ], 'id');
                                    }
                                }
                                $this->ecom_order_model->update([
                                    'id' => $order_details['id'],
                                    'total_service_charge' => $total_service_charge
                                ], 'id');
                                // $this->user_model->creditToIncomeWallet($this->config->item('super_admin_user_id', 'ion_auth'), $total_service_charge, $order_id);
                                $txn_id = 'NC-' . generate_trasaction_no();
                                $amount = floatval($order_details['total']) - floatval($order_details['delivery_fee']) - floatval($total_service_charge);
                                $this->user_model->payment_update($this->config->item('super_admin_user_id'), $amount, 'DEBIT', "wallet", $txn_id, $order_id);
                                $txn_id = 'NC-' . generate_trasaction_no();
                                $this->user_model->payment_update($order_details['vendor_user_id'], $amount, 'CREDIT', "wallet", $txn_id, $order_id);
                            }
                            // trigger push notification to user//

                            $this->send_notification($order_details['created_user_id'], USER_APP_CODE, "Delivery Boy Pickup the order ", "And heading to your delivery location", [
                                'order_id' => $order_id,
                                'notification_type' => $this->notification_type_model->where([
                                    'app_details_id' => USER_APP_CODE,
                                    'notification_code' => 'OD'
                                ])
                                    ->get()
                            ]);
                            $this->set_response_simple(NULL, $this->ecom_order_status_model->fields('status')
                                ->where([
                                    'delivery_mode_id' => $order_details['delivery_mode_id'],
                                    'serial_number' => ($order_details['delivery_mode_id'] == 1) ? 104 : 103
                                ])
                                ->get()['status'], REST_Controller::HTTP_OK, TRUE);
                        } else {
                            $this->set_response_simple(NULL, 'Something went wrong.!', REST_Controller::HTTP_OK, FALSE);
                        }
                    } else {
                        $this->set_response_simple(NULL, 'Invalid OTP.!', REST_Controller::HTTP_OK, FALSE);
                    }
                } else {
                    $this->set_response_simple(NULL, 'Not found.', REST_Controller::HTTP_OK, FALSE);
                }
            } else {
                $this->set_response_simple(NULL, 'Please provide order_id.', REST_Controller::HTTP_OK, FALSE);
            }
        } elseif ($type == 'pickuporder_details') {
            $order = $this->pickup_orders_model->fields('id, track_id, pickupanddropcategory_id, order_delivery_otp, order_pickup_otp, delivery_fee, instructions, created_at')
                ->with_pickup_address('fields: id, phone, email, name, landmark, address, location_id')
                ->with_delivery_address('fields: id, phone, email, name, landmark, address, location_id')
                ->with_customer('fields: id, unique_id, first_name, phone')
                ->with_delivery_mode('fields: id, name, desc')
                ->with_order_status('fields: id, delivery_mode_id, status, serial_number')
                ->with_payment('fields: id, payment_method_id, txn_id, amount, created_at, message, status')
                ->with_pickupanddropcategory('fields: id, name, desc')
                ->where('id', $this->input->post('order_id'))
                ->get();
            $order['is_ecom_order'] = FALSE;
            $order['is_pickup_order'] = TRUE;
            $od_type = $order['order_status']['delivery_mode_id'];
            $order['delivery_mode'] = $this->delivery_mode_model->fields('id, name, desc')->where('id', $od_type)->get();


            $order['order_confirmation_time'] = $this->setting_model->where('key', 'order_confirmation_time')->get()['value'];
            $order['order_cancellation_time'] = $this->setting_model->where('key', 'order_cancellation_time')->get()['value'];
            if (!empty($order)) {
                $delivery_job = $this->delivery_job_model
                    ->fields('id, job_id, rating, feedback, job_type, delivery_boy_user_id, status, rejected_reason_id')
                    ->with_delivery_boy('fields: id, first_name, last_name, phone, unique_id')
                    ->with_delivery_rejections('fields: id, job_id, add_waiting_time, rejected_reason_id, rejection_reason, created_at, status')
                    ->with_rejected_reason('fields: id, reason')
                    ->where('pickup_order_id', $order['id'])
                    ->order_by('id', 'DESC')
                    ->get();
                $vehichleType = $this->delivery_boy_biometric_model->where([
                    'user_id' => $delivery_job['delivery_boy_user_id']
                ])->get();
                $delivery_job['vehicle_type_id'] = $vehichleType['vehicle_type_id'];
                $order['delivery_job'] = empty($delivery_job) ? NULL : $delivery_job;
                if (!empty($order['delivery_job'])) {
                    $order['delivery_job']['delivery_boy']['profile_image'] = base_url() . 'uploads/profile_image/profile_' . $order['delivery_job']['delivery_boy']['unique_id'] . '.jpg?' . time();
                    $order['delivery_job']['delivery_boy']['delivery_boy_pickup_image'] = base_url() . 'uploads/delivery_boy_pickup_image/delivery_boy_pickup_' . $order['delivery_job']['id'] . '.jpg?' . time();
                    $order['delivery_job']['delivery_boy']['delivery_boy_delivery_image'] = base_url() . 'uploads/delivery_boy_delivery_image/delivery_boy_delivery_' . $order['delivery_job']['id'] . '.jpg?' . time();
                }
                $order['delivery_mode']['order_statuses'] = $this->ecom_order_status_model->where([
                    'delivery_mode_id' => 2,
                    'serial_number <' => 200
                ])->get_all();
                $order['order_status']['time'] = $this->ecom_order_status_log_model->fields('created_at')
                    ->where([
                        'pickup_order_id' => $order['id'],
                        'ecom_order_status_id' => $order['order_status']['id']
                    ])
                    ->get();
                // $order['order_status']['accepted_time'] = $this->db->query("SELECT `ecom_order_status_log`.id, `ecom_order_status_log`.ecom_order_id,  `ecom_order_status_log`.`ecom_order_status_id`, `ecom_order_status_log`.`created_at` FROM `ecom_order_status_log` WHERE `ecom_order_status_log`.`ecom_order_id` = " . $order['id'] . " and ( `ecom_order_status_log`.`ecom_order_status_id` = 3 OR `ecom_order_status_log`.`ecom_order_status_id` = 11) limit 1")->result_array();
                $order['pickup_address']['location'] = $this->location_model->fields('id,latitude, longitude, address')
                    ->where('id', $order['pickup_address']['location_id'])
                    ->get();
                $order['delivery_address']['location'] = $this->location_model->fields('id,latitude, longitude, address')
                    ->where('id', $order['delivery_address']['location_id'])
                    ->get();


                $order['payment']['payment_method'] = $this->payment_method_model->where('id', $order['payment']['payment_method_id'])->get();

                if (!empty($order['pickupanddropcategory'])) {
                    $order['pickupanddropcategory']['image'] = base_url() . 'uploads/pickupanddropcategory_image/pickupanddropcategory_' . $order['pickupanddropcategory']['id'] . '.jpg?' . time();
                }

                $this->set_response_simple($order, 'Success.', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'Not found.', REST_Controller::HTTP_OK, FALSE);
            }
        }
    }

    public function verifySecurityDepositeValue($userIds, $order_id, $amount)
    {

        $securityDeposite = (int) $this->vehicle_model->get_all()[0]['security_deposited_amount'];
        $userAccData      =  $this->db->select('*')
            ->from('user_accounts')
            ->where('floating_wallet <=', $securityDeposite)
            ->where_in('user_id', $userIds)
            ->get()
            ->result();

        $finalUserIds    = array();
        //print_r($userAccData);die();
        foreach ($userAccData as $val) {
            $finalAmount = $val->floating_wallet + $amount;

            if ($finalAmount <= $securityDeposite) {
                $finalUserIds[]['user_id'] = $val->user_id;
            }
        }
        return $finalUserIds;
    }
    public function getPaymentType($orderId)
    {
        $this->db->select('pm.name as paymentType');
        $this->db->from('ecom_payments');
        $this->db->join('payment_methods pm', 'pm ON pm.id=ecom_payments.payment_method_id', 'inner');
        $this->db->where('ecom_payments.ecom_order_id', $orderId);
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    /**
     * @author Mehar
     * 
     */
    public function ecom_order_reject_post()
    {
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $order_id = $this->input->post('order_id');
        $reason = $this->input->post('reason');
        if (!empty($order_id)) {
            $order_details = $this->ecom_order_model->fields('id, track_id, delivery_mode_id, created_user_id, vendor_user_id, total')
                ->with_payment('fields: id, payment_method_id, amount, status')
                ->with_vendor('fields: id, name')
                ->where('id', $order_id)
                ->get();
            if ($this->input->post('is_total_order_rejected') === 1) {
                if (!empty($order_details)) {
                    $is_updated = $this->ecom_order_model->update([
                        'id' => $order_id,
                        'message' => $reason,
                        'order_status_id' => $this->ecom_order_status_model->fields('id')
                            ->where([
                                'serial_number' => 300
                            ])->get()['id']
                    ], 'id');
                    if ($is_updated) {
                        $paymentDetails = $this->ecom_payment_model->fields('payment_method_id')
                            ->where('ecom_order_id', $order_id)
                            ->get();
                        if ($order_details['payment']['payment_method_id'] != 1 || ($order_details['payment']['payment_method_id'] == 1 && $order_details['payment']['status'] == 2)) {
                            $this->user_model->debitFromWallet($this->config->item('super_admin_user_id'), $order_details["total"], $order_id);
                        }
                        if ($paymentDetails["payment_method_id"] == 3) {
                            $this->user_model->creditToWallet($order_details['created_user_id'], $order_details["total"], $order_id);
                        } else if ($order_details['payment']['payment_method_id'] == 2 || ($order_details['payment']['payment_method_id'] == 1 && $order_details['payment']['status'] == 2)) {
                            $this->load->module('payment/api/payment');
                            $this->payment->initiateRefund($order_id);
                        }
                        /**
                         * trigger push notificatios *
                         */
                        $this->send_notification($order_details['created_user_id'], USER_APP_CODE, "Order status of( " . $order_details['track_id'] . " )", "We're sorry to say, That your order has been rejectd by " . strtoupper($order_details['vendor']['name']) . " Due to " . $this->input->post('reason') . ".", [
                            'order_id' => $order_id,
                            'notification_type' => $this->notification_type_model->where([
                                'app_details_id' => USER_APP_CODE,
                                'notification_code' => 'OD'
                            ])->get()
                        ]);
                        $this->set_response_simple(NULL, 'Order has been rejected.', REST_Controller::HTTP_OK, TRUE);
                    } else {
                        $this->set_response_simple(NULL, 'Something went wrong.!', REST_Controller::HTTP_OK, FALSE);
                    }
                } else {
                    $this->set_response_simple(NULL, 'Not found.', REST_Controller::HTTP_OK, FALSE);
                }
            } else {
                $rejected_products = $this->input->post('rejected_products');
                if (!empty($rejected_products)) {
                    $this->ecom_order_model->update([
                        'id' => $order_id,
                        'message' => $reason,
                    ], 'id');

                    $this->ecom_order_deatils_model->update([
                        'status' => 2,
                        'ecom_order_id' => $order_id
                    ], 'ecom_order_id');

                    foreach ($rejected_products as $rp_key => $product) {
                        $this->ecom_order_deatils_model->update([
                            'status' => 4
                        ], [
                            'ecom_order_id' => $order_id,
                            'item_id' => $product['product_id'],
                            'vendor_product_variant_id' => $product['product_varient_id']
                        ]);
                    }
                    $this->ecom_order_reject_request_model->insert([
                        'ecom_order_id' => $order_id,
                        'customer_user_id' => $order_details['created_user_id'],
                        'vendor_user_id' => $order_details['vendor_user_id'],
                        'status' => 1,
                    ]);
                    /**
                     * trigger push notificatios *
                     */
                    $this->send_notification($order_details['created_user_id'], USER_APP_CODE, "Order status of( " . $order_details['track_id'] . " )", "We're sorry to say, That your order has been rejectd by " . strtoupper($order_details['vendor']['name']) . " Due to " . $this->input->post('reason') . ".", [
                        'order_id' => $order_id,
                        'notification_type' => $this->notification_type_model->where([
                            'app_details_id' => USER_APP_CODE,
                            'notification_code' => 'OD'
                        ])->get()
                    ]);
                    $this->set_response_simple(NULL, 'Order has been rejected', REST_Controller::HTTP_OK, TRUE);
                } else {
                    $this->set_response_simple(NULL, 'Please add rejected products', REST_Controller::HTTP_OK, FALSE);
                }
            }
        } else {
            $this->set_response_simple(NULL, 'Please provide order_id.', REST_Controller::HTTP_OK, FALSE);
        }
    }

    /**
     * To show statistics bars on dashboard
     *
     * @author Mehar
     */
    public function statistics_get()
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

        $admin_ids = $this->get_users_by_group(1);
        array_push($admin_ids, $token_data->id);
        $deleted_items = $this->db->get_where('deleted_items', [
            'vendor_id' => $token_data->id
        ])->result_array();
        if ($deleted_items) {
            $deleted_items = array_column($deleted_items, 'item_id');
        } else {
            $deleted_items = [
                0
            ];
        }

        $cat_id = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get()['category_id'];
        $shop_by_categories = $this->sub_category_model->where([
            'type' => 2,
            'cat_id' => $cat_id
        ])->get_all();
        $sub_cat_ids = (empty($shop_by_categories)) ? NULL : array_column($shop_by_categories, 'id');

        $data['active_products_count'] = $this->food_item_model->where('approval_status', 1)
            ->where('created_user_id', $admin_ids)
            ->where('id NOT', $deleted_items)
            ->where('sub_cat_id', $sub_cat_ids)
            ->count_rows();
        $data['in_active__products_count'] = $this->food_item_model->where('approval_status', 2)
            ->where('created_user_id', $admin_ids)
            ->where('id NOT', $deleted_items)
            ->where('sub_cat_id', $sub_cat_ids)
            ->count_rows();
        $data['pending_orders_count'] = $this->food_orders_model->where('order_status !=', 0)
            ->where('order_status !=', 6)
            ->where('order_status !=', 7)
            ->where('vendor_id', $token_data->id)
            ->count_rows();
        $data['completed_orders_count'] = $this->food_orders_model->where('order_status', 6)
            ->where('vendor_id', $token_data->id)
            ->count_rows();
        $data['cancelled_orders_count'] = $this->food_orders_model->where('order_status', 7)
            ->where('vendor_id', $token_data->id)
            ->count_rows();
        $data['rejected_orders_count'] = $this->food_orders_model->where('order_status', 0)
            ->where('vendor_id', $token_data->id)
            ->count_rows();
        $this->set_response_simple($data ? $data : NULL, 'My statistics', REST_Controller::HTTP_OK, TRUE);
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
        if (!is_null($menu_id)) {
            $menu_name = $this->food_menu_model->fields('name')
                ->where('id', $menu_id)
                ->get();
            $sounds_like .= metaphone($menu_name['name']) . ' ';
        }
        if (!is_null($shop_by_cat_id)) {
            $cat_name = $this->sub_category_model->fields('name')
                ->where('id', $shop_by_cat_id)
                ->get();
            $sounds_like .= metaphone($cat_name['name']) . ' ';
        }
        if (!is_null($name)) {
            foreach (explode(' ', $name) as $n) {
                $sounds_like .= metaphone($n) . ' ';
            }
        }
        return $sounds_like;
    }

    /**
     * To manage Brands
     *
     * @author UMA
     *        
     * @param string $type
     * @param number $target11
     */
    public function brands_post($type = 'r', $target = 0)
    {
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->brand_model->user_id = $token_data->id;
        if ($type == 'c') {
            $this->form_validation->set_rules($this->brand_model->rules['create_rules']);
            if (empty($this->input->post('image'))) {
                $this->form_validation->set_rules('image', 'Ecommerce Brands Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $id = $this->brand_model->insert([
                    'created_user_id' => $token_data->id,
                    'name' => $this->input->post('name'),
                    'desc' => (empty($this->input->post('desc'))) ? NULL : $this->input->post('desc'),
                    'status' => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 3
                ]);
                if ($id) {
                    file_put_contents("./uploads/brands_image/brands_" . $id . ".jpg", base64_decode($this->input->post('image')));
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        }
    }

    public function order_auto_cancel_post()
    {
        $order_details = "SELECT id,track_id,created_at,order_status_id, date(created_at) as created_date, time(created_at) as created_time,delivery_mode_id FROM `ecom_orders` where date(created_at)=CURRENT_DATE";
        $result = $this->db->query($order_details)->result_array();
        foreach ($result as $rowdata) {
            $order_id = $rowdata['id'];
            $track_id = $rowdata['track_id'];
            $delivery_mode_id = $rowdata['delivery_mode_id'];
            $created_at = $rowdata['created_at'];
            $order_status_id = $rowdata['order_status_id'];
            $created_time = $rowdata['created_time'];
            $statusCode = 302;
            $current_time = date("H:i:s");
            $start = strtotime($created_time);
            $end = strtotime($current_time);
            $mins = ($end - $start) / 60;

            if ($order_status_id == 9 and $mins >= 2) {
                $orders = $this->ecom_order_model->updateOrderStatus($order_id, $delivery_mode_id, $statusCode);
            }
        }
    }

    public function vendor_product_search_get()
    {
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);
        $search_details = "SELECT * FROM `product_search_history` where created_user_id='$token_data->id' order by id desc limit 5";
        $result = $this->db->query($search_details)->result_array();
        $this->set_response_simple($result, 'Success.', REST_Controller::HTTP_OK, TRUE);
    }

    public function saveFloatingPayments_post()
    {
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->form_validation->set_rules($this->sub_category_model->rules['floating_save_validation']);
        if ($this->form_validation->run() == FALSE) {
            $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
        } else {
            $amount = $this->input->post('amount');
            $txn_id = $this->input->post('txn_id');
            $response_data = $this->input->post('response_data');
            $this->user_account_model->update([
                'user_id' => $token_data->id,
                'floating_wallet' => 0
            ], 'user_id');
            $insertData = [
                'txn_id' => $txn_id,
                'amount' => $amount,
                'created_user_id' => $token_data->id,
                'response_data' => json_encode($response_data),
                'status' => 1
            ];
            $this->db->insert('floating_payments', $insertData);
            //$id = $this->floating_data_payments_model->insert($insertData);
            $this->set_response_simple(NULL, 'Floating Data Saved Successfully.', REST_Controller::HTTP_OK, TRUE);
        }
    }
    public function getFloatingValue_get()
    {

        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $getFloatingValue = "SELECT floating_wallet FROM `user_accounts` where user_id='$token_data->id'";
        $result = $this->db->query($getFloatingValue)->result_array();
        $this->set_response_simple($result, 'Success.', REST_Controller::HTTP_OK, TRUE);
    }
}
