<?php

class Food extends MY_Controller
{

    function __construct()
    {
        error_reporting(E_ERROR | E_PARSE);
        parent::__construct();
        $this->template = 'template/admin/main';
        if (!$this->ion_auth->logged_in()) // || ! $this->ion_auth->is_admin()
            redirect('auth/login');

        $this->load->library('pagination');
        $this->load->model('category_model');
        $this->load->model('sub_category_model');
        $this->load->model('food_menu_model');
        $this->load->model('food_item_model');
        $this->load->model('food_section_model');
        $this->load->model('food_sec_item_model');
        $this->load->model('food_orders_model');
        $this->load->model('food_order_items_model');
        $this->load->model('food_sub_order_items_model');
        $this->load->model('food_order_deal_model');
        $this->load->model('food_settings_model');
        $this->load->model('user_model');
        $this->load->model('vendor_list_model');
        $this->load->model('vendor_leads_model');
        $this->load->model('order_support_model');
        $this->load->model('Food_sub_menu_model');
        $this->load->model('shop_by_category_model');
        $this->load->model('brand_model');
        $this->load->model('food_item_image_model');
        $this->load->model('categoriesbrands_model');
        $this->load->model('ecom_order_status_model');
        $this->load->model('pickup_orders_model');
        $this->load->model('vendor_product_variant_model');
        $this->load->model('delivery_job_model');
        $this->load->model('notifications_model');
        $this->load->model('notification_type_model');
        $this->load->model('tax_model');
    }

    /**
     * Food Products approve
     *
     * To Manage Food Item approvals
     *
     * @author Mahesh
     * @param string $type
     */
    public function products_approve($rowno = 0, $type = 'r')
    {
        if ($type == 'edit') {
            $this->data['title'] = 'Products Approve';
            $this->data['nav_type'] = 'products_approve';
            $this->data['content'] = 'food/food/vendor_product_details';
            $id = base64_decode(base64_decode($this->input->get('id')));

            $this->data['vendourproduct'] = $this->db->query("SELECT vpv.*,fi.product_code,fi.name as food_name,fi.desc,fi.status as food_status,fi.created_user_id ,fs.name as food_section_name,fm.name as food_menu_name ,sc.name as sub_cat_name,fsi.weight,fii.id as image_id  FROM vendor_product_variants as vpv 
                join food_item as fi on fi.id = vpv.item_id 
                join food_section as fs on fs.id = vpv.section_id 
                join food_sec_item as fsi on fsi.id = vpv.section_item_id 
                join food_menu as fm  on fm.id = fi.menu_id 
                left join food_item_images as fii  on fii.item_id =  fi.id
                join sub_categories as sc  on sc.id = fi.sub_cat_id where vpv.id = '$id'")->result_array();

            $itid = $this->data['vendourproduct'][0]['item_id'];

            $user_id = $this->data['vendourproduct'][0]['created_user_id'];
            $this->data['userinfo'] = $this->db->query("SELECT * from users where id = '$user_id'")->result_array();

            $this->data['se_food_itm'] = $this->db->query("SELECT vpv.*,fi.product_code,fi.name  as food_name,fi.id as food_id ,fi.desc,fi.status as food_status, fs.name as food_section_name,fm.name as food_menu_name ,u.username as vendor_name,u.unique_id ,sc.name as sub_cat_name,fsi.weight   FROM vendor_product_variants as vpv 
                join food_item as fi on fi.id = vpv.item_id 
                join food_section as fs on fs.id = vpv.section_id 
                join food_sec_item as fsi on fsi.id = vpv.section_item_id 
                join users as u on u.id = vpv.vendor_user_id 
                join food_menu as fm  on fm.id = fi.menu_id 
                join sub_categories as sc  on sc.id = fi.sub_cat_id and vpv.id = '$id'")->result_array();
            $this->_render_page($this->template, $this->data);
        } else if ($type == 'changecat') {
            $id = $this->input->get('id');

            $is_updated = $this->food_item_model->update([
                'id' => $id,
                'status' => 1
            ], 'id');
            redirect('products_approve/0/r', 'refresh');
        } else if ($type == 'foodapprovestatus') {

            $id = $this->input->post('item_id');

            $item = $this->food_item_model->where('id', $id)->get();

            $is_updated = $this->food_item_model->update([
                'id' => $id,
                'status' => 2
            ], 'id');
            $this->send_notification($item['created_user_id'], VENDOR_APP_CODE, "Product status " . $item['product_code'] . " ", "Your Product" . $item['name'] . " has been approved By NEXCLICK ", ['order_id' => $order_id, 'notification_type' => $this->notification_type_model->where(['app_details_id' => VENDOR_APP_CODE, 'notification_code' => 'PROD'])->get()]);
            redirect('products_approve/0/r', 'refresh');
        }
    }

    public function shop_by_category_approve($type = 'r')
    {
        if ($type == 'r') {
            $this->data['title'] = 'shop_by_category_approve Approve';
            $this->data['content'] = 'food/food/shop_by_category_approve';
            $this->data['nav_type'] = 'shop_by_category_approve';
            $this->data['shop_by_categories'] = $this->db->query("SELECT sc.id, sc.cat_id, c.name AS category, sc.type, sc.name AS sub_category, sc.desc, sc.status, sbc.vendor_id, vl.name AS vendor_name, vl.unique_id FROM `shop_by_categories` AS sbc JOIN sub_categories AS sc ON sbc.sub_cat_id = sc.id JOIN vendors_list AS vl ON vl.vendor_user_id = sbc.vendor_id JOIN categories AS c ON c.id = sc.cat_id WHERE sbc.vendor_id NOT IN(1) AND sc.type = 2 AND sc.status = 0")->result_array();

            $this->data['approved_shop_by_categories'] = $this->db->query("SELECT sc.id, sc.cat_id, c.name AS category, sc.type, sc.name AS sub_category, sc.desc, sc.status, sbc.vendor_id, vl.name AS vendor_name, vl.unique_id FROM `shop_by_categories` AS sbc JOIN sub_categories AS sc ON sbc.sub_cat_id = sc.id JOIN vendors_list AS vl ON vl.vendor_user_id = sbc.vendor_id JOIN categories AS c ON c.id = sc.cat_id WHERE sbc.vendor_id NOT IN(1) AND sc.type = 2 AND sc.status != 0")->result_array();

            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'approve') {
            $id = base64_decode(base64_decode($this->input->get('id')));
            $this->sub_category_model->update(array(
                'status' => 1
            ), $id);
            redirect('shop_by_category_approve/r', 'refresh');
        } elseif ($type == 'disapprove') {
            $id = base64_decode(base64_decode($this->input->get('id')));
            $this->sub_category_model->update(array(
                'status' => 0
            ), $id);
            redirect('shop_by_category_approve/r', 'refresh');
        }
    }

    /**
     * Shop By Categories Crud
     *
     * To Manage Shop By Categorie
     *
     * @author Mehar
     * @param string $type
     */
    public function shop_by_categories($type = 'r')
    {
        if ($type == 'c') {
            $this->form_validation->set_rules($this->sub_category_model->rules['shop_by_category']);

            if (empty($_FILES['file']['name'])) {
                $this->form_validation->set_rules('file', 'sub_category Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Shop by category';
                $this->data['content'] = 'food/food/shop_by_category';
                $this->data['nav_type'] = 'shop_by_category';
                $this->data['sub_categories'] = $this->db->query("SELECT sc.id, sc.cat_id, sc.type, sc.name, sc.desc, sc.status, sbc.vendor_id FROM `shop_by_categories` AS sbc JOIN sub_categories AS sc ON sbc.sub_cat_id = sc.id WHERE sbc.`vendor_id` IN (1," . $this->ion_auth->get_user_id() . ") AND sc.type = 2 AND sbc.cat_id=" . $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get()['category_id'])
                    ->result_array();
                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->sub_category_model->insert([
                    'cat_id' => $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                        ->get()['category_id'],
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'status' => 0,
                    'type' => 2
                ]);
                $this->db->insert('shop_by_categories', [
                    'vendor_id' => $this->ion_auth->get_user_id(),
                    'cat_id' => $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                        ->get()['category_id'],
                    'sub_cat_id' => $id
                ]);
                $this->db->insert('vendor_in_active_shop_by_categories', [
                    'sub_cat_id' => $id,
                    'vendor_id' => $this->ion_auth->get_user_id()
                ]);
                $this->file_up("file", "sub_category", $id, '', 'no');
                redirect('shop_by_categories/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Shop by category';
            $this->data['content'] = 'food/food/shop_by_category';
            $this->data['nav_type'] = 'shop_by_category';
            $this->data['sub_categories'] = $this->db->query("SELECT sc.id, sc.cat_id, sc.type, sc.name, sc.desc, sc.status, sbc.vendor_id FROM `shop_by_categories` AS sbc JOIN sub_categories AS sc ON sbc.sub_cat_id = sc.id WHERE sbc.`vendor_id` IN (1," . $this->ion_auth->get_user_id() . ") AND sc.type = 2 AND sbc.cat_id=" . $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                ->get()['category_id'] . " ORDER BY sbc.id DESC")
                ->result_array();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->sub_category_model->rules['shop_by_category']);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Edit Shop by categories';
                $this->data['content'] = 'food/food/edit';
                $this->data['type'] = 'shop_by_category';
                $this->data['nav_type'] = 'shop_by_category';
                $this->data['sub_categories'] = $this->sub_category_model->get($_POST['id']);
                $this->data['is_vendor'] = ($this->ion_auth->get_user_id() === $this->data['sub_categories']['created_user_id']) ? TRUE : FALSE;
                $this->_render_page($this->template, $this->data);
            } else {
                if ($this->input->post('status') == 1) {
                    $this->db->where([
                        'sub_cat_id' => $this->input->post('id'),
                        'vendor_id' => $this->ion_auth->get_user_id()
                    ]);
                    $this->db->delete('vendor_in_active_shop_by_categories');
                } else {
                    $this->db->insert('vendor_in_active_shop_by_categories', [
                        'sub_cat_id' => $this->input->post('id'),
                        'vendor_id' => $this->ion_auth->get_user_id()
                    ]);
                }
                if ($this->input->post('is_vendor') === 1) {
                    $this->sub_category_model->update([
                        'id' => $this->input->post('id'),
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc')
                    ], 'id');
                }
                if (!empty($_FILES['file']['tmp_name'])) {
                    if (!file_exists('uploads/' . 'sub_category' . '_image/')) {
                        mkdir('uploads/' . 'sub_category' . '_image/', 0777, true);
                    }
                    $path = $_FILES['file']['name'];
                    unlink('uploads/' . 'sub_category' . '_image/' . 'sub_category' . '_' . $this->input->post('id') . '.jpg');
                    move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'sub_category' . '_image/' . 'sub_category' . '_' . $this->input->post('id') . '.jpg');
                }
                redirect('shop_by_categories/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->db->where([
                'vendor_id' => $this->ion_auth->get_user_id(),
                'sub_cat_id' => $this->input->post('id')
            ]);
            $this->db->delete('shop_by_categories');
            echo $this->sub_category_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Shop by categories';
            $this->data['content'] = 'food/food/edit';
            $this->data['nav_type'] = 'shop_by_category';
            $this->data['type'] = 'shop_by_category';
            $this->data['sub_categories'] = $this->sub_category_model->get($_GET['id']);
            $this->data['is_vendor'] = ($this->ion_auth->get_user_id() === $this->data['sub_categories']['created_user_id']) ? TRUE : FALSE;
            $this->_render_page($this->template, $this->data);
        }
    }

    /**
     * To Manage Food Items
     *
     * @author Mahesh
     * @param string $type
     */
    public function food_menu($type = 'r')
    {
        if ($type == 'c') {
            $this->form_validation->set_rules($this->food_menu_model->rules);

            if ($this->input->post('sub_cat_id') != '' && $this->input->post('name') != '') {
                $this->db->select("*");
                $this->db->from("food_menu");
                $this->db->where('sub_cat_id', $this->input->post('sub_cat_id'));
                $this->db->where('name', $this->input->post('name'));
                $menu = $this->db->get('')->result_array();

                if (count($menu) > 0) {
                    $value = true;
                } else {
                    $value = false;
                }
            }

            if ($this->form_validation->run() == FALSE) {
                $this->food_item('r');
            } else {
                if ($value == '') {
                    $input_data = array(
                        'vendor_id' => $this->ion_auth->get_user_id(),
                        'sub_cat_id' => $this->input->post('sub_cat_id'),
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc')
                    );
                    $id = $this->food_menu_model->insert($input_data);
                    $this->file_up("file", "food_menu", $id, '', 'no');
                    $this->session->set_flashdata('upload_status', ["success" => 'Menu has been added successfully']);
                    redirect('food_menu/r', 'refresh');
                } else {
                    $this->session->set_flashdata('upload_status', ["error" => 'Menu name is already exist']);
                    $this->data['title'] = 'Menu';
                    $this->data['content'] = 'food/food/food_menu';
                    $this->data['nav_type'] = 'food_menu';
                    if ($this->ion_auth->is_admin()) {
                        $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                        $r = array();
                        foreach ($cat_data as $c) {
                            $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                                ->where([
                                    'cat_id' => $c['id'],
                                    'type' => 2,
                                    'created_user_id' => $this->ion_auth->get_user_id()
                                ])
                                ->get_all();
                            $r[] = $c;
                        }
                        $this->data['sub_categories'] = $r;
                    } else {
                        $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                        $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                            ->get();
                        $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                            ->where($w_r1)
                            ->where([
                                'cat_id' => $cat_id['category_id'],
                                'type' => 2
                            ])
                            ->get_all();
                    }
                    $this->data['food_items'] = $this->food_menu_model->with_shop_by_category('fields:id,name')
                        ->fields('id,name,desc,vendor_id,sub_cat_id')
                        ->order_by('id', 'ASCE')
                        ->where('vendor_id', $this->ion_auth->get_user_id())
                        ->get_all();

                    if ($this->ion_auth->is_admin()) {
                        $me = $this->food_menu_model->with_shop_by_category('fields:id,name')
                            ->order_by('name', 'asc')
                            ->get_all();
                    } else {
                        $me = array();
                        foreach ($this->data['sub_categories'] as $sub_categories) {

                            $a = $this->data['food_sub_items'] = $this->food_menu_model->with_shop_by_category('fields:id,name')
                                ->where('vendor_id', $this->ion_auth->get_user_id())
                                ->where('sub_cat_id', $sub_categories['id'])
                                ->order_by('id', 'ASCE')
                                ->get_all();
                            if (!empty($a)) {
                                foreach ($a as $s) {
                                    $me[] = $s;
                                }
                            }
                        }
                    }
                    $this->data['food_items'] = $me;

                    $this->_render_page($this->template, $this->data);
                }
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Menu';
            $this->data['content'] = 'food/food/food_menu';
            $this->data['nav_type'] = 'food_menu';

            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])
                        ->get_all();
                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])
                    ->get_all();
            }
            $this->data['food_items'] = $this->food_menu_model->with_shop_by_category('fields:id,name')
                ->fields('id,name,desc,vendor_id,sub_cat_id')
                ->order_by('id', 'ASCE')
                ->where('vendor_id', $this->ion_auth->get_user_id())
                ->get_all();

            if ($this->ion_auth->is_admin()) {
                $me = $this->food_menu_model->with_shop_by_category('fields:id,name')
                    ->order_by('name', 'asc')
                    ->get_all();
            } else {
                $me = array();
                foreach ($this->data['sub_categories'] as $sub_categories) {

                    $a = $this->data['food_sub_items'] = $this->food_menu_model->with_shop_by_category('fields:id,name')
                        ->where('vendor_id', $this->ion_auth->get_user_id())
                        ->where('sub_cat_id', $sub_categories['id'])
                        ->order_by('id', 'ASCE')
                        ->get_all();
                    if (!empty($a)) {
                        foreach ($a as $s) {
                            $me[] = $s;
                        }
                    }
                }
            }
            $this->data['food_items'] = $me;

            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->food_menu_model->rules);

            if ($this->input->post('sub_cat_id') != '' && $this->input->post('name') != '') {
                $this->db->select("*");
                $this->db->from("food_menu");
                $this->db->where('sub_cat_id', $this->input->post('sub_cat_id'));
                $this->db->where('name', $this->input->post('name'));
                $this->db->where('id !=', $this->input->post('id'));
                $menu = $this->db->get('')->result_array();

                if (count($menu) > 0) {
                    $value = true;
                } else {
                    $value = false;
                }
            }

            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                if ($value == '') {
                    $this->food_menu_model->update([
                        'id' => $this->input->post('id'),
                        'sub_cat_id' => $this->input->post('sub_cat_id'),
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc')
                    ], 'id');
                    $this->session->set_flashdata('upload_status', 'Menu has been updated successfully');
                    redirect('food_menu/r', 'refresh');
                } else {
                    $this->session->set_flashdata('upload_status', ["error" => 'Menu Name is already exist']);
                    $this->data['title'] = 'Edit Menu';
                    $this->data['content'] = 'food/food/edit';
                    $this->data['type'] = 'food_menu';
                    $this->data['nav_type'] = 'food_menu';
                    if ($this->ion_auth->is_admin()) {
                        $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                        $r = array();
                        foreach ($cat_data as $c) {
                            $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                                ->where([
                                    'cat_id' => $c['id'],
                                    'type' => 2,
                                    'created_user_id' => $this->ion_auth->get_user_id()
                                ])
                                ->get_all();
                            $r[] = $c;
                        }
                        $this->data['sub_categories'] = $r;
                    } else {
                        $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                        $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                            ->get();
                        $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                            ->where($w_r1)
                            ->where([
                                'cat_id' => $cat_id['category_id'],
                                'type' => 2
                            ])
                            ->get_all();
                    }
                    $this->data['food_items'] = $this->food_menu_model->with_shop_by_category('fields:id,name')
                        ->fields('id,name,desc,vendor_id,sub_cat_id')
                        ->order_by('id', 'ASCE')
                        ->where('vendor_id', $this->ion_auth->get_user_id())
                        ->get_all();

                    if ($this->ion_auth->is_admin()) {
                        $me = $this->food_menu_model->with_shop_by_category('fields:id,name')
                            ->order_by('name', 'asc')
                            ->get_all();
                    } else {
                        $me = array();
                        foreach ($this->data['sub_categories'] as $sub_categories) {

                            $a = $this->data['food_sub_items'] = $this->food_menu_model->with_shop_by_category('fields:id,name')
                                ->where('vendor_id', $this->ion_auth->get_user_id())
                                ->where('sub_cat_id', $sub_categories['id'])
                                ->order_by('id', 'ASCE')
                                ->get_all();
                            if (!empty($a)) {
                                foreach ($a as $s) {
                                    $me[] = $s;
                                }
                            }
                        }
                    }
                    $this->data['food_items'] = $me;

                    $this->_render_page($this->template, $this->data);
                }
            }
        } elseif ($type == 'd') {
            echo $this->food_menu_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->session->set_flashdata('delete_status', 'Menu has been deleted successfully');
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Menu';
            $this->data['content'] = 'food/food/edit';
            $this->data['type'] = 'food_menu';
            $this->data['nav_type'] = 'food_menu';
            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where('cat_id', $c['id'])
                        ->get_all();

                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where('cat_id', $cat_id['category_id'])
                    ->get_all();
            }
            $this->data['item'] = $this->food_menu_model->fields('id,name,desc,vendor_id,sub_cat_id')
                ->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();
            $this->data['i'] = $this->food_menu_model->where('file', $this->input->get('file'))
                ->get();
            $this->data['food_item'] = $this->food_menu_model->fields('id,name,desc,vendor_id')
                ->order_by('id', 'DESC')
                ->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();
            $this->_render_page($this->template, $this->data);
        }
    }

    /**
     * @desc Inventory management
     * @dt 20-08-2021
     * @author Mehar
     */
    public function inventory($type = 'r', $rowno = 0)
    {
        if ($type == 'r') {
            $this->data['title'] = 'food Products list';
            $this->data['content'] = 'food/food/inventory';
            $this->data['nav_type'] = 'inventory';
            $search_text = "";
            $noofrows = 10;
            if ($this->input->server('REQUEST_METHOD') === 'POST') {
                $search_text = $this->input->post('q');
                $sub_cat_id = $this->input->post('sub_cat_id');
                $availability = $this->input->post('statusdata');
                $menu_id = $this->input->post('menu_id');
                $stock_type = $this->input->post('stock_type');
                $noofrows = $this->input->post('noofrows');
                $this->session->set_userdata(array(
                    "q" => $search_text,
                    'noofrows' => $noofrows,
                    'availability' => $availability,
                    'menu_id' => $menu_id,
                    'sub_cat_id' => $sub_cat_id,
                    'stock_type' => $stock_type
                ));
            } elseif($rowno > 0 &&($this->session->userdata('q') != NULL || $noofrows != NULL || $this->session->userdata('sub_cat_id') != NULL || $this->session->userdata('menu_id') != NULL || $this->session->userdata('stock_type') != NULL)) { 
                
				$search_text = $this->session->userdata('q');
				$noofrows = $this->session->userdata('noofrows');
				$sub_cat_id = $this->session->userdata('sub_cat_id');
				$menu_id = $this->session->userdata('menu_id');
				$stock_type = $this->session->userdata('stock_type');
            }else{
				$this->session->unset_userdata(['q','noofrows','sub_cat_id', 'menu_id','stock_type']);
				
			}

            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }
            echo $this->input->post('q');
            $data = $this->vendor_product_variant_model->all($rowperpage, $rowno, (!empty($this->input->post('sub_cat_id'))) ? $this->input->post('sub_cat_id') : NUll, (!empty($this->input->post('menu_id'))) ? $this->input->post('menu_id') : NUll, (!empty($this->input->post('brand_id'))) ? $this->input->post('brand_id') : NUll, (!empty($this->input->post('q'))) ? $this->input->post('q') : NUll, $this->ion_auth->get_user_id(), (!empty($this->input->post('stock_type'))) ? $this->input->post('stock_type') : NUll);

            $all_count = $this->vendor_product_variant_model->all($rowperpage, $rowno, (!empty($this->input->post('sub_cat_id'))) ? $this->input->post('sub_cat_id') : NUll, (!empty($this->input->post('menu_id'))) ? $this->input->post('menu_id') : NUll, (!empty($this->input->post('brand_id'))) ? $this->input->post('brand_id') : NUll, (!empty($this->input->post('q'))) ? $this->input->post('q') : NUll, $this->ion_auth->get_user_id(), (!empty($this->input->post('stock_type'))) ? $this->input->post('stock_type') : NUll, TRUE);

            if (!$this->ion_auth->in_group('admin', $this->ion_auth->get_user_id())) {
                $cat_id = 0;
                $where_in_vendor_product = "vendor_user_id= " . $this->ion_auth->get_user_id();
                $min_stock = $this->db->query("SELECT sum(min_stock) as min_stock FROM ecom_settings where created_user_id = " . $this->ion_auth->get_user_id() . ";")->result_array()[0]['min_stock'];
            } else {
                $vendor = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
                $cat_id = $vendor['category_id'];
                $where_in_vendor_product = " ";
                $min_stock = 0;
            }
            $min_stock = (empty($min_stock)) ? 0 : $min_stock;
            $where_in_vendor_product = "vendor_user_id= " . $this->ion_auth->get_user_id();
            if (is_null($stock_type)) {
                $where_in_vendor_product .= " and stock>" . $min_stock;
            } elseif ($stock_type == 'instock') {
                $where_in_vendor_product .= " and stock>" . $min_stock;
            } elseif ($stock_type == 'outofstock') {
                $where_in_vendor_product .= " and stock <=" . $min_stock;
            }

            if (!empty($data['result'])) {
                foreach ($data['result'] as $key => $val) {
                    $data['result'][$key]['vendor'] = $this->vendor_list_model->fields('id, vendor_user_id, unique_id,business_name')->where('vendor_user_id', $val['vendor_user_id'])->get();
                    $data['result'][$key]['sub_category'] = $this->sub_category_model->fields('id, name, desc')->where('id', $val['sub_cat_id'])->get();
                    $data['result'][$key]['menu'] = $this->food_menu_model->fields('id, name, desc')->where('id', $val['menu_id'])->get();
                    $data['result'][$key]['brand'] = $this->brand_model->fields('id, name, desc')->where('id', $val['brand_id'])->get();
                    $data['result'][$key]['vendor_product_variants'] = $this->vendor_product_variant_model->fields('id,status')->where('item_id', $val['id'])->where('vendor_user_id', $val['vendor_user_id'])->get();

                    $data['result'][$key]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $val['image_id'] . '.' . $val['ext'] . '?' . time();
                }
            }
            $config['full_tag_open'] = "<ul class='pagination'>";
            $config['full_tag_close'] = "</ul>";
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
            $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tagl_close'] = "</li>";
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tagl_close'] = "</li>";
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tagl_close'] = "</li>";
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tagl_close'] = "</li>";
            $config['base_url'] = base_url() . 'food/food/inventory/r';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $all_count;
            $config['per_page'] = $rowperpage;
            $this->pagination->initialize($config);
            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['products'] = $data['result'];
            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['menu_id'] = $menu_id;
            $this->data['sub_cat_id'] = $sub_cat_id;
            $this->data['stock_type'] = $stock_type;
            $this->data['noofrows'] = $rowperpage;
            $this->data['brands'] = $this->brand_model->fields('id,name,desc')->get_all();
            if (empty($cat_id))
                $where = ['type' => 2];
            else
                $where = ['cat_id' => $cat_id, 'type' => 2];


            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])->get_all();

                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])
                    ->get_all();
            }
            //print_array($this->data);
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'outstock') {

            $this->data['title'] = 'Out of Stock list';
            $this->data['content'] = 'food/food/outstock';
            $this->data['nav_type'] = 'inventory';
            $search_text = "";
            $noofrows = 10;
            if ($this->input->post('submit') != NULL) {
                $search_text = $this->input->post('q');
                $sub_cat_id = $this->input->post('sub_cat_id');
                $availability = $this->input->post('statusdata');
                $menu_id = $this->input->post('menu_id');
                $stock_type = $this->input->post('stock_type');
                $noofrows = $this->input->post('noofrows');
                $this->session->set_userdata(array(
                    "q" => $search_text,
                    'noofrows' => $noofrows,
                    'availability' => $availability,
                    'menu_id' => $menu_id,
                    'sub_cat_id' => $sub_cat_id,
                    'stock_type' => $stock_type
                ));
            } else {
                if ($this->session->userdata('q') != NULL || $noofrows != NULL || $this->session->userdata('sub_cat_id') != NULL || $this->session->userdata('menu_id') != NULL || $this->session->userdata('stock_type') != NULL) {
                    $search_text = $this->session->userdata('q');
                    $noofrows = $this->session->userdata('noofrows');
                    $sub_cat_id = $this->session->userdata('sub_cat_id');
                    $menu_id = $this->session->userdata('menu_id');
                    $stock_type = $this->session->userdata('stock_type');
                }
            }

            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }

            $data = $this->vendor_product_variant_model->all($rowperpage, $rowno, (!empty($this->input->post('sub_cat_id'))) ? $this->input->post('sub_cat_id') : NUll, (!empty($this->input->post('menu_id'))) ? $this->input->post('menu_id') : NUll, (!empty($this->input->post('brand_id'))) ? $this->input->post('brand_id') : NUll, (!empty($this->input->post('q'))) ? $this->input->post('q') : NUll, $this->ion_auth->get_user_id(), 'outstock');
            //print_r($data);exit;
            $all_count = $this->vendor_product_variant_model->all($rowperpage, $rowno, (!empty($this->input->post('sub_cat_id'))) ? $this->input->post('sub_cat_id') : NUll, (!empty($this->input->post('menu_id'))) ? $this->input->post('menu_id') : NUll, (!empty($this->input->post('brand_id'))) ? $this->input->post('brand_id') : NUll, (!empty($this->input->post('q'))) ? $this->input->post('q') : NUll, $this->ion_auth->get_user_id(), (!empty($this->input->post('stock_type'))) ? $this->input->post('stock_type') : NUll, TRUE);

            if (!$this->ion_auth->in_group('admin', $this->ion_auth->get_user_id())) {
                $cat_id = 0;
                $where_in_vendor_product = "vendor_user_id= " . $this->ion_auth->get_user_id();
                $min_stock = $this->db->query("SELECT sum(min_stock) as min_stock FROM ecom_settings where created_user_id = " . $this->ion_auth->get_user_id() . ";")->result_array()[0]['min_stock'];
            } else {
                $vendor = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
                $cat_id = $vendor['category_id'];
                $where_in_vendor_product = " ";
                $min_stock = 0;
            }
            $min_stock = (empty($min_stock)) ? 0 : $min_stock;
            $where_in_vendor_product = "vendor_user_id= " . $this->ion_auth->get_user_id();
            if (is_null($stock_type)) {
                $where_in_vendor_product .= " and stock>" . $min_stock;
            } elseif ($stock_type == 'instock') {
                $where_in_vendor_product .= " and stock>" . $min_stock;
            } elseif ($stock_type == 'outofstock') {
                $where_in_vendor_product .= " and stock <=" . $min_stock;
            }
            if (!empty($data['result'])) {
                foreach ($data['result'] as $key => $val) {
                    $data['result'][$key]['vendor'] = $this->vendor_list_model->fields('id, vendor_user_id, unique_id,business_name')->where('vendor_user_id', $val['vendor_user_id'])->get();
                    $data['result'][$key]['sub_category'] = $this->sub_category_model->fields('id, name, desc')->where('id', $val['sub_cat_id'])->get();
                    $data['result'][$key]['menu'] = $this->food_menu_model->fields('id, name, desc')->where('id', $val['menu_id'])->get();
                    $data['result'][$key]['brand'] = $this->brand_model->fields('id, name, desc')->where('id', $val['brand_id'])->get();
                    $data['result'][$key]['vendor_product_variants'] = $this->vendor_product_variant_model->fields('id,status')->where('item_id', $val['id'])->where('vendor_user_id', $val['vendor_user_id'])->get();

                    $data['result'][$key]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $val['image_id'] . '.' . $val['ext'] . '?' . time();
                }
            }
            $config['full_tag_open'] = "<ul class='pagination'>";
            $config['full_tag_close'] = "</ul>";
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
            $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tagl_close'] = "</li>";
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tagl_close'] = "</li>";
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tagl_close'] = "</li>";
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tagl_close'] = "</li>";
            $config['base_url'] = base_url() . 'food/food/inventory/r';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $all_count;
            $config['per_page'] = $rowperpage;
            $this->pagination->initialize($config);
            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['products'] = $data['result'];
            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['menu_id'] = $menu_id;
            $this->data['sub_cat_id'] = $sub_cat_id;
            $this->data['stock_type'] = $stock_type;
            $this->data['noofrows'] = $rowperpage;
            $this->data['brands'] = $this->brand_model->fields('id,name,desc')->get_all();
            if (empty($cat_id))
                $where = ['type' => 2];
            else
                $where = ['cat_id' => $cat_id, 'type' => 2];


            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])->get_all();

                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])
                    ->get_all();
            }
            //print_array($this->data);
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'view_details') {
            $this->data['title'] = 'Inventory details';
            $this->data['content'] = 'food/food/vendor_product_details';
            $this->data['nav_type'] = 'inventory';
            $item_id = base64_decode(base64_decode($this->input->get('id')));
            $vendor_user_id = base64_decode(base64_decode($this->input->get('vendor_user_id')));
            $this->data['product_details'] = $this->food_item_model
                ->with_menu('fields: id, name, vendor_id')
                ->with_sub_category('fields: id, name')
                ->with_brands('fields: id, name')
                ->with_created_by('fields: id, first_name, last_name, unique_id')
                ->with_item_images('fields: id, item_id, serial_number, ext')
                ->with_section_items('fields: id, section_item_code, name, desc, price, weight, status, created_at, updated_at')
                ->with_vendor_product_varinats('fields: id, item_id, section_id, section_item_id, sku, price, stock, discount, tax_id, list_id, vendor_user_id, status, created_at, updated_at', 'where: vendor_user_id=' . $vendor_user_id)
                ->where('id', $item_id)->get();
            if (!empty($this->data['product_details']['item_images'])) {
                foreach ($this->data['product_details']['item_images'] as $k => $img) {
                    $this->data['product_details']['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                }
            } else {
                $this->data['product_details']['item_images']  = NULL;
            }

            if (!empty($this->data['product_details']['vendor_product_varinats'])) {
                foreach ($this->data['product_details']['vendor_product_varinats'] as $key => $val) {
                    $this->data['product_details']['vendor_product_varinats'][$key]['section_item'] = $this->food_sec_item_model->fields('id, section_item_code, name, desc, price, weight, status, created_at, updated_at')->where('id', $val['vendor_product_varinats']['section_item_id'])->get();
                    $this->data['product_details']['vendor_product_varinats'][$key]['tax'] = $this->tax_model->fields('tax')->where('id', $val['tax_id'])->get();
                }
            } else {
                $this->data['product_details']['vendor_product_varinats'] = NULL;
            }
            $this->data['user_details'] = $this->vendor_list_model->fields('id, unique_id,business_name')->where('vendor_user_id', $vendor_user_id)->get();
            //print_array($this->data['product_details']);
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'update_data') {
            $vendor = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
            $item = $this->food_item_model->with_menu('fields: id, name')
                ->with_sub_category('fields: id, name')
                ->with_section_items('fields: id, sec_id, item_id, name, desc, price, status, created_at, updated_at')
                ->where('id', $this->input->post('item_id'))
                ->get();
            if (!empty($item) && !empty($item['section_items'])) {
                $are_items_existed = $this->vendor_product_variant_model->where([
                    'item_id' => $item['id'],
                    'vendor_user_id' => $vendor['vendor_user_id']
                ])->get_all();

                // print_r($are_items_existed);
                // print_r($this->input->post());
                // exit;

                if (empty($are_items_existed)) {
                    $section_items = [];
                    array_push($section_items, [
                        'item_id' => $this->input->post('item_id'),
                        'section_id' => $this->input->post('section_id'),
                        'section_item_id' => $this->input->post('section_item_id'),
                        'sku' => generate_serial_no($vendor['unique_id'] . '-' . metaphone($item['sub_category']['name']) . '-' . metaphone($item['menu']['name']) . '-', 2, $key),
                        'price' => $this->input->post('price'),
                        'stock' => $this->input->post('stock'),
                        'discount' => $this->input->post('discount'),
                        'tax_id' => $this->input->post('tax_id'),
                        'vendor_user_id' => $this->ion_auth->get_user_id(),
                        'list_id' => !empty($vendor) ? $vendor['id'] : NULL
                    ]);
                    $is_inserted = $this->vendor_product_variant_model->insert($section_items);
                } else {
                    $is_updated = $this->vendor_product_variant_model->update([
                        'item_id' => $this->input->post('item_id'),
                        'section_id' => $this->input->post('section_id'),
                        'section_item_id' => $this->input->post('section_item_id'),
                        'sku' => generate_serial_no($vendor['unique_id'] . '-' . metaphone($item['sub_category']['name']) . '-' . metaphone($item['menu']['name']) . '-', 2, $key),
                        'price' => $this->input->post('price'),
                        'stock' => $this->input->post('stock'),
                        'discount' => $this->input->post('discount'),
                        'tax_id' => $this->input->post('tax_id'),
                        'vendor_user_id' => $this->ion_auth->get_user_id(),
                        'list_id' => !empty($vendor) ? $vendor['id'] : NULL
                    ], $this->input->post('id'));
                }
            }

            $this->data['content'] = 'food/food/catalogue/r';
            $this->_render_page($this->template);
        } elseif ($type == 'update') {
            $vendor = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
            $item = $this->food_item_model->with_menu('fields: id, name')
                ->with_sub_category('fields: id, name')
                ->with_section_items('fields: id, sec_id, item_id, name, desc, price, status, created_at, updated_at')
                ->where('id', $this->input->post('item_id'))
                ->get();
            if (!empty($item) && !empty($item['section_items'])) {
                $are_items_existed = $this->vendor_product_variant_model->where([
                    'item_id' => $item['id'],
                    'vendor_user_id' => $vendor['vendor_user_id']
                ])->get_all();

                if (empty($are_items_existed)) {
                    $section_items = [];
                    array_push($section_items, [
                        'item_id' => $this->input->post('item_id'),
                        'section_id' => $this->input->post('section_id'),
                        'section_item_id' => $this->input->post('section_item_id'),
                        'sku' => generate_serial_no($vendor['unique_id'] . '-' . metaphone($item['sub_category']['name']) . '-' . metaphone($item['menu']['name']) . '-', 2, $key),
                        'price' => $this->input->post('price'),
                        'stock' => $this->input->post('stock'),
                        'discount' => $this->input->post('discount'),
                        'tax_id' => $this->input->post('tax_id'),
                        'vendor_user_id' => $this->ion_auth->get_user_id(),
                        'list_id' => !empty($vendor) ? $vendor['id'] : NULL
                    ]);
                    $is_inserted = $this->vendor_product_variant_model->insert($section_items);
                } else {
                    $is_updated = $this->vendor_product_variant_model->update([
                        'item_id' => $this->input->post('item_id'),
                        'section_id' => $this->input->post('section_id'),
                        'section_item_id' => $this->input->post('section_item_id'),
                        'sku' => generate_serial_no($vendor['unique_id'] . '-' . metaphone($item['sub_category']['name']) . '-' . metaphone($item['menu']['name']) . '-', 2, $key),
                        'price' => $this->input->post('price'),
                        'stock' => $this->input->post('stock'),
                        'discount' => $this->input->post('discount'),
                        'tax_id' => $this->input->post('tax_id'),
                        'vendor_user_id' => $this->ion_auth->get_user_id(),
                        'list_id' => !empty($vendor) ? $vendor['id'] : NULL
                    ], $this->input->post('id'));
                }
            }
            $this->data['content'] = 'food/food/catalogue';
            $this->_render_page($this->template);
        } elseif ($type == 'add') {
            $this->data['title'] = 'Inventory details';
            $this->data['content'] = 'food/food/inventory_add';
            $this->data['nav_type'] = 'catalogue';
            $item_id = base64_decode(base64_decode($this->input->get('id')));
            $vendor_user_id = base64_decode(base64_decode($this->input->get('vendor_user_id')));
            $this->data['sub_items'] = $this->food_item_model->order_by('id', 'DESC')
                ->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();

            if ($this->ion_auth->is_admin()) {
                $w_r = '(vendor_id = ' . $this->ion_auth->get_user_id() . ')';
            } else {
                $w_r = '(vendor_id = ' . $this->ion_auth->get_user_id() . ' OR vendor_id = 1)';
            }
            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])
                        ->get_all();
                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])
                    ->get_all();
            }

            $tempid = $this->data['sub_items']['id'];
            $this->data['sec_item1'] = $this->food_sec_item_model->where('item_id', $tempid)->get_all();
            $this->data['food_sec'] = $this->food_section_model->where('item_id', $tempid)->get_all();
            // $this->db->where($w_r);
            $this->data['items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')
                ->order_by('id', 'DESC')
                ->get_all();
            $this->data['food_sub_items'] = $this->food_item_model->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();

            $this->data['food_sub_items'] = $this->food_item_model->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();

            $this->data['food_sec'] = $this->food_section_model->where('item_id', $tempid)->get_all();

            $this->data['img'] = $this->food_item_image_model->where('item_id', $tempid)->get_all();

            $this->data['brands'] = $this->brand_model->fields('id,name,desc')->get_all();
            $this->data['product_details'] = $this->food_item_model
                ->with_menu('fields: id, name, vendor_id')
                ->with_sub_category('fields: id, name')
                ->with_brands('fields: id, name')
                ->with_created_by('fields: id, first_name, last_name, unique_id')
                ->with_item_images('fields: id, item_id, serial_number, ext')
                ->with_section_items('fields: id, section_item_code, name, desc, price, weight, status, created_at, updated_at')
                ->with_vendor_product_varinats('fields: id, item_id, section_id, section_item_id, sku, price, stock, discount, tax_id, list_id, vendor_user_id, status, created_at, updated_at', 'where: vendor_user_id=' . $vendor_user_id)
                ->where('id', $item_id)->get();

            if (!empty($this->data['product_details']['item_images'])) {
                foreach ($this->data['product_details']['item_images'] as $k => $img) {
                    $this->data['product_details']['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                }
            } else {
                $this->data['product_details']['item_images']  = NULL;
            }
            if (!empty($this->data['product_details']['vendor_product_varinats'])) {
                foreach ($this->data['product_details']['vendor_product_varinats'] as $key => $val) {

                    $this->data['product_details']['vendor_product_varinats'][$key]['section_item'] = $this->food_sec_item_model->fields('id, section_item_code, name, desc, price, weight, status, created_at, updated_at')->where('id', $val['section_item_id'])->get_all();
                    $this->data['product_details']['vendor_product_varinats'][$key]['tax'] = $this->tax_model->fields('id,tax')->where('id', $val['tax_id'])->get_all();
                }
            } else {


                $vendor = $this->vendor_list_model->where('vendor_user_id', $vendor_user_id)->get_all();
                $section_items = [];
                foreach ($this->data['product_details']['section_items'] as $i => $valu) {

                    array_push($section_items, [
                        'item_id' => $valu['item_id'],
                        'section_id' => $valu['item_id'],
                        'section_item_id' => $valu['id'],
                        'sku' => generate_serial_no('SKU', 2, rand(99999, 999999)),
                        'price' => $valu['price'],
                        'stock' => 0,
                        'tax_id' => 0,
                        'discount' => 0,
                        'vendor_user_id' => $vendor_user_id,
                        'created_user_id' => $vendor_user_id,
                        'list_id' => !empty($vendor) ? $vendor['id'] : NULL
                    ]);
                }
                $is_inserted = $this->db->insert_batch('vendor_product_variants', $section_items);

                $currentURL = current_url();

                $params   = $_SERVER['QUERY_STRING']; //my_id=1,3

                $fullURL = $currentURL . '?' . $params;


                redirect($fullURL, 'refresh');
            }
            $this->data['user_details'] = $this->vendor_list_model->fields('id, unique_id,business_name')->where('vendor_user_id', $vendor_user_id)->get();
            $this->data['taxs'] = $this->tax_model->fields('id,tax')->get_all();
            //print_array($this->data['product_details']);
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'change_status') {
            $vendor_id = $this->input->post('vendor_id');
            $item_id = $this->input->post('item_id');
            $status = ($this->input->post('is_checked') == 'true') ? 1 : 2;

            if ($status == 1) {
                $query = $this->db->query("update vendor_product_variants SET status='1' where item_id='" . $item_id . "' and vendor_user_id='" . $vendor_id . "'");
            } else {
                $query = $this->db->query("update vendor_product_variants SET status='2' where item_id='" . $item_id . "' and vendor_user_id='" . $vendor_id . "'");
            }
        }
    }


    /**
     * @desc catalogue management
     * @dt 20-08-2021
     * @author Mehar
     */
    public function catalogue($type = 'r', $rowno = 0)
    {
        if ($type == 'r') {
            $this->data['title'] = 'food Products list';
            $this->data['content'] = 'food/food/catalogue';
            $this->data['nav_type'] = 'catalogue';
            $search_text = "";
            $noofrows = 10;
            if ($this->input->post('submit') != NULL) {
                $search_text = $this->input->post('q');
                $sub_cat_id = $this->input->post('sub_cat_id');
                $availability = $this->input->post('statusdata');
                $menu_id = $this->input->post('menu_id');
                $noofrows = $this->input->post('noofrows');
                $this->session->set_userdata(array(
                    "q" => $search_text,
                    'noofrows' => $noofrows,
                    'availability' => $availability,
                    'menu_id' => $menu_id,
                    'sub_cat_id' => $sub_cat_id,
                ));
            } else {
                if ($this->session->userdata('q') != NULL || $noofrows != NULL || $this->session->userdata('sub_cat_id') != NULL || $this->session->userdata('menu_id') != NULL) {
                    $search_text = $this->session->userdata('q');
                    $noofrows = $this->session->userdata('noofrows');
                    $sub_cat_id = $this->session->userdata('sub_cat_id');
                    $menu_id = $this->session->userdata('menu_id');
                }
            }

            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }
            //api call start

            $admin_ids = $this->get_users_by_group(1);
            $admindetails = $this->user_model->where('id', $this->ion_auth->get_user_id())->get();
            if ($admindetails['primary_intent'] != 'vendor') {
                array_push($admin_ids, $this->ion_auth->get_user_id());
            }

            $vendor = $this->vendor_list_model->fields('category_id')
                ->where('vendor_user_id', $this->ion_auth->get_user_id())
                ->get();
            $shop_by_categories = $this->sub_category_model->where([
                'cat_id' => $vendor['category_id'],
                'type' => 2
            ])->get_all();
            $sub_cat_ids = (empty($shop_by_categories)) ? NULL : array_column($shop_by_categories, 'id');
            $sub_cat_id = $this->input->post('sub_cat_id');
            $menu_id = $this->input->post('menu_id');
            $search_text = $this->input->post('q');
            /*Code for adding search products to the table for history*/
            $created_user_id = $this->ion_auth->get_user_id();
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

            if (!empty($search_text)) {
                $this->db->like('name', $search_text);
                //  $this->db->or_like('sounds_like', metaphone($search_text));


            }

            if (!empty($status)) {
                if ($status != 1) {
                    $admin_ids = [
                        $this->ion_auth->get_user_id()
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
                ->where('status', 1)
                ->count_rows();

            if (!empty($sub_cat_id))
                $sub_cat_ids = [
                    $sub_cat_id
                ];

            if (!empty($menu_id))
                $this->db->where('menu_id', $menu_id);

            if (!empty($search_text)) {
                $this->db->like('name', $search_text);

                /*foreach (explode(' ', $search_text) as $s) {
                    $this->db->or_like('sounds_like', metaphone($s));
                }*/
            }

            if ($this->data['user']->primary_intent == 'admin') {
                $catalogue_products = $this->food_item_model
                    ->with_menu('fields: id, name')
                    ->with_sub_category('fields: id, name')
                    ->with_item_images('fields: id, item_id, serial_number, ext')
                    ->where('sub_cat_id', $sub_cat_ids)
                    ->where('created_user_id', $admin_ids)
                    ->order_by('id', 'DESC')
                    ->where('status', 1)
                    ->limit($rowperpage, $rowno)
                    ->get_all();
            } else {
                $catalogue_products = $this->food_item_model
                    ->with_menu('fields: id, name')
                    ->with_sub_category('fields: id, name')
                    ->with_item_images('fields: id, item_id, serial_number, ext')
                    ->where('sub_cat_id', $sub_cat_ids)
                    ->where('created_user_id', $admin_ids)
                    ->order_by('id', 'DESC')
                    ->limit($rowperpage, $rowno)
                    ->get_all();
            }
            if (!empty($catalogue_products)) {
                foreach ($catalogue_products as $food_item => $item) {
                    $vendor_products[$food_item] = $this->vendor_product_variant_model->where(['item_id' => $item['id'], 'vendor_user_id' => $this->ion_auth->get_user_id()])->get_all();
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
                            $catalogue_products[$key]['pitem_images'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                        }
                    } else {
                        $catalogue_products[$key]['item_images'] = NULL;
                    }
                }
            }

            //api call end



            $config['full_tag_open'] = "<ul class='pagination'>";
            $config['full_tag_close'] = "</ul>";
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
            $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tagl_close'] = "</li>";
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tagl_close'] = "</li>";
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tagl_close'] = "</li>";
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tagl_close'] = "</li>";
            $config['base_url'] = base_url() . 'food/food/catalogue/r';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $all_catalogue_products;
            $config['per_page'] = $rowperpage;
            $this->pagination->initialize($config);
            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['products'] = $catalogue_products;
            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['menu_id'] = $menu_id;
            $this->data['sub_cat_id'] = $sub_cat_id;
            $this->data['stock_type'] = $stock_type;
            $this->data['noofrows'] = $rowperpage;
            $this->data['brands'] = $this->brand_model->fields('id,name,desc')->get_all();
            if (empty($cat_id))
                $where = ['type' => 2];
            else
                $where = ['cat_id' => $cat_id, 'type' => 2];


            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])->get_all();

                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])
                    ->get_all();
            }

            //print_array($this->data['user_details']);exit;
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'view_details') {
            $this->data['title'] = 'Inventory details';
            $this->data['content'] = 'food/food/vendor_product_details';
            $this->data['nav_type'] = 'inventory';
            $item_id = base64_decode(base64_decode($this->input->get('id')));
            $vendor_user_id = base64_decode(base64_decode($this->input->get('vendor_user_id')));
            $this->data['product_details'] = $this->food_item_model
                ->with_menu('fields: id, name, vendor_id')
                ->with_sub_category('fields: id, name')
                ->with_brands('fields: id, name')
                ->with_created_by('fields: id, first_name, last_name, unique_id')
                ->with_item_images('fields: id, item_id, serial_number, ext')
                ->with_section_items('fields: id, section_item_code, name, desc, price, weight, status, created_at, updated_at')
                ->with_vendor_product_varinats('fields: id, item_id, section_id, section_item_id, sku, price, stock, discount, tax_id, list_id, vendor_user_id, status, created_at, updated_at', 'where: vendor_user_id=' . $vendor_user_id)
                ->where('id', $item_id)->get();

            if (!empty($this->data['product_details']['item_images'])) {
                foreach ($this->data['product_details']['item_images'] as $k => $img) {
                    $this->data['product_details']['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                }
            } else {
                $this->data['product_details']['item_images']  = NULL;
            }

            if (!empty($this->data['product_details']['vendor_product_varinats'])) {
                foreach ($this->data['product_details']['vendor_product_varinats'] as $key => $val) {
                    $this->data['product_details']['vendor_product_varinats'][$key]['section_item'] = $this->food_sec_item_model->fields('id, section_item_code, name, desc, price, weight, status, created_at, updated_at')->where('id', $val['vendor_product_varinats']['section_item_id'])->get();
                    $this->data['product_details']['vendor_product_varinats'][$key]['tax'] = $this->tax_model->fields('tax')->where('id', $val['tax_id'])->get();
                }
            } else {
                $this->data['product_details']['vendor_product_varinats'] = NULL;
            }
            $this->data['user_details'] = $this->vendor_list_model->fields('id, unique_id,business_name')->where('vendor_user_id', $vendor_user_id)->get();
            //print_array($this->data['product_details']);
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'change_status') {
            $vendor_id = $this->input->post('vendor_id');
            $item_id = $this->input->post('item_id');
            $status = ($this->input->post('is_checked') == 'true') ? 1 : 2;

            if ($status == 1) {
                $query = $this->db->query("update vendor_product_variants SET status='1' where item_id='" . $item_id . "' and vendor_user_id='" . $vendor_id . "'");
            } else {
                $query = $this->db->query("update vendor_product_variants SET status='2' where item_id='" . $item_id . "' and vendor_user_id='" . $vendor_id . "'");
            }
        }
    }


    /**
     * Food Sub Item crud
     *
     * To Manage Food Sub Items
     *
     * @author Mahesh
     * @param string $type
     * @param string $target
     */
    public function food_product($rowno = 0, $type = 'r')
    {
        if ($type == 'r') {
            $this->data['title'] = 'food Products list';
            $this->data['content'] = 'food/food/food_product_details';
            $this->data['nav_type'] = 'Products';
            $search_text = "";
            $noofrows = 10;
            if ($this->input->server('REQUEST_METHOD') === 'POST') {
                $search_text = $this->input->post('q');
                $group = $this->input->post('sub_cat_id');
                $availability = $this->input->post('statusdata');
                $menu_id = $this->input->post('menu_id');
                // $noofrows = $this->input->post('menu_id');

                $noofrows = $this->input->post('noofrows');
                $this->session->set_userdata(array(
                    "q" => $search_text,
                    'noofrows' => $noofrows,
                    'availability' => $availability,
                    'menu_id' => $menu_id
                ));
            }  elseif($rowno > 0 &&($this->session->userdata('q') != NULL || $noofrows != NULL)){
                    $search_text = $this->session->userdata('q');
                    $noofrows = $this->session->userdata('noofrows');
                    $availability = $this->session->userdata('availability');
                    $group = $this->session->userdata('sub_cat_id');
                    $menu_id = $this->session->userdata('menu_id');
                }else{
				$this->session->unset_userdata(['q','noofrows','availability', 'sub_cat_id','menu_id']);	
					
				}

            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }

            // $rowno = ($this->uri->segment(3)) ? ($this->uri->segment(3) - 1) : 0;
            // if (! $this->ion_auth->in_group(1)) {
            if ($this->ion_auth->is_admin()) {
                $admin_ids = $this->get_users_by_group(1);
                array_push($admin_ids, $this->ion_auth->get_user_id());
                $deleted_items = $this->db->get_where('deleted_items', [
                    'vendor_id' => $this->ion_auth->get_user_id()
                ])
                    ->result_array();
                if ($deleted_items) {
                    $deleted_items = array_column($deleted_items, 'item_id');
                } else {
                    $deleted_items = [
                        0
                    ];
                }

                if ($search_text != null) {
                    $this->db->like('sounds_like', metaphone($search_text));
                    $this->db->where('sub_cat_id', $group);
                    $this->db->where('menu_id', $menu_id);
                    $this->db->or_where('product_code', $search_text);
                }
                $allcount = $this->food_item_model->where('created_user_id', $admin_ids)
                    ->where('id NOT', $deleted_items)
                    ->count_rows();
                if ($group != null) {
                    $this->db->where('food_item.menu_id', $menu_id);
                    $this->db->where('food_item.sub_cat_id', $group);
                }
                $this->db->where('status', 1);
                $this->db->like('food_item.sounds_like', metaphone($search_text));
                $this->db->or_where('food_item.product_code', $statusdata);

                $catalogue_products = $this->food_item_model->with_menu('fields: id, name, vendor_id')
                    ->with_sub_category('fields: id, name')
                    ->where('created_user_id', $admin_ids)
                    ->where('id NOT', $deleted_items)
                    ->order_by('id', 'DESC')
                    ->limit($rowperpage, $rowno)
                    ->get_all();
                //print_r($this->db->last_query());exit;
                $allcount = $this->food_item_model->count_rows();
            } else {
                $this->db->like('sounds_like', metaphone($search_text));
                if ($this->input->post('submit') != NULL) {
                    $this->db->like('availability', $availability);
                }
                if ($this->input->post('submit') != NULL) {
                    $this->db->like('sub_cat_id', $group);
                }
                if ($this->input->post('submit') != NULL) {
                    $this->db->like('menu_id', $menu_id);
                }

                $allcount = $this->food_item_model->where('created_user_id', $this->ion_auth->get_user_id())->count_rows();
                $this->db->like('food_item.sounds_like', metaphone($search_text));
                if ($this->input->post('submit') != NULL) {
                    $this->db->like('food_item.availability', $availability);
                }

                if ($this->input->post('submit') != NULL) {
                    $this->db->like('food_item.sub_cat_id', $group);
                }

                if ($this->input->post('submit') != NULL) {
                    $this->db->like('food_item.menu_id', $menu_id);
                }

                $catalogue_products = $this->food_item_model->with_menu('fields: id, name, vendor_id')
                    ->with_sub_category('fields: id, name')
                    ->with_brands('fields: id, name')
                    ->where('created_user_id', $this->ion_auth->get_user_id())
                    ->where('id NOT', $deleted_items)
                    ->order_by('id', 'DESC')
                    ->limit($rowperpage, $rowno)
                    ->get_all();
            }

            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])->order_by('name')->get_all();
                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])->order_by('name')
                    ->get_all();
            }

            $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->get_all();
            $this->data['brands'] = $this->brand_model->fields('id,name,desc')->get_all();


            if (!empty($catalogue_products)) {
                foreach ($catalogue_products as $key => $val) {
                    $catalogue_products[$key]['product_image'] = base_url() . 'uploads/food_item_image/food_item_' . $val['id'] . '.jpg';
                }
            }

            $config['full_tag_open'] = "<ul class='pagination'>";
            $config['full_tag_close'] = "</ul>";
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
            $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tagl_close'] = "</li>";
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tagl_close'] = "</li>";
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tagl_close'] = "</li>";
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tagl_close'] = "</li>";
            $config['base_url'] = base_url() . 'food/food/food_product';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $rowperpage;
            $this->pagination->initialize($config);
            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['products'] = $catalogue_products;
            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['noofrows'] = $rowperpage;
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'd') {
            $this->food_item_image_model->delete([
                'item_id' => $this->input->post('id')
            ]);
            $this->food_sec_item_model->delete([
                'item_id' => $this->input->post('id')
            ]);
            $this->food_section_model->delete([
                'item_id' => $this->input->post('id')
            ]);
            echo $this->food_item_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->session->set_flashdata('delete_status', 'Product has been deleted successfully');
            // redirect('food_product/0/r', 'refresh');
        } else if ($type == 'view') {

            $this->data['title'] = 'Edit Item';
            $this->data['content'] = 'food/food/view_food_product';
            $this->data['nav_type'] = 'food_item';
            $this->data['type'] = 'food_item';
            $id = base64_decode(base64_decode($this->input->get('id')));

            $this->data['vendourproduct'] = $this->db->query("SELECT fi.id, fi.product_code,fi.name as food_item_name ,fii.id as image_id , u.username as vendor_name,u.unique_id,fi.created_at,fi.updated_at,fi.availability,fi.status,fi.created_user_id,fi.updated_user_id,fm.name as menu_name , sc.name as sub_name FROM food_item as fi
            join food_section as fs on fs.item_id = fi.id 
            join food_sec_item as fsi on fsi.item_id = fi.id 
            join users as u on u.id = fi.created_user_id 
            join food_menu as fm  on fm.id = fi.menu_id 
            join sub_categories as sc  on sc.id = fi.sub_cat_id 
            left join food_item_images as fii  on fii.item_id =  fi.id 
            where fi.id = '$id'")->result_array();

            $user_id = $this->data['vendourproduct'][0]['created_user_id'];
            $this->data['userinfo'] = $this->db->query("SELECT * from users where id = '$user_id'")->result_array();

            $this->data['se_food_itm'] = $this->db->query("SELECT fsi.name as variant_name ,fsi.desc ,
            fsi.price,fsi.weight,fsi .created_at,fsi.updated_at, fs. name as section_name , u.first_name as vendor_name  FROM food_sec_item as fsi
            join food_section as fs on fs.id = fsi.sec_id 
            join users as u on u.id = fsi.created_user_id where fsi.item_id = '$id'")->result_array();

            $this->_render_page($this->template, $this->data);
        } else if ($type == 'changecat') {
            $id = $this->input->get('id');
            $is_updated = $this->food_item_model->update([
                'id' => $id,
                'status' => 1
            ], 'id');
            redirect('food_product/0/r', 'refresh');
        } else if ($type == 'foodapprovestatus') {

            $id = $this->input->post('item_id');
            $food_item_data = $this->food_item_model->where('id', $id)->get();
            $is_updated = $this->food_item_model->update([
                'id' => $id,
                'status' => 2
            ], 'id');

            $this->send_notification($food_item_data['created_user_id'], VENDOR_APP_CODE, $food_item_data['name'] . " product got approved", "Your Product code item " . $food_item_data['product_code'] . " has been approved By NEXCLICK ", ['order_id' => $id, 'notification_type' => $this->notification_type_model->where(['app_details_id' => VENDOR_APP_CODE, 'notification_code' => 'PROD'])->get()]);

            redirect('food_product/0/r', 'refresh');
        } else if ($type == 'foodpendingstatus') {
            $id = $this->input->get('id');
            $is_updated = $this->food_item_model->update([
                'id' => $id,
                'status' => 2
            ], 'id');
            redirect('food_product/0/r', 'refresh');
        } else if ($type == 'u') {
            $this->form_validation->set_rules($this->food_item_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {

                $section_id = $this->input->post('section_id');

                $sounds_like = $this->sounds_like($this->input->post('name'), $this->input->post('shop_by_cat_id'), $this->input->post('menu_id'));

                $sub_cat_id = $this->input->post('sub_cat_id');
                $menu_id = $this->input->post('menu_id');
                $item_id = $this->input->post('id');
                $section_id = $this->input->post('section_id');

                $brand_id = $this->input->post('brand_id');

                $is_updated = $this->food_item_model->update([
                    'id' => $item_id,
                    'sub_cat_id' => $sub_cat_id,
                    'menu_id' => $menu_id,
                    'brand_id' => $brand_id,
                    'item_type' => $this->input->post('item_type'),
                    'name' => $this->input->post('name'),
                    'desc' => (empty($this->input->post('desc'))) ? NULL : $this->input->post('desc'),
                    'sounds_like' => $sounds_like,
                    'availability' => 1,
                ], 'id');

                if (!empty($this->input->post('proname'))) {
                    $section_items = [];
                    $values1 = $this->input->post('proname');
                    $values2 = $this->input->post('proprice');
                    $values3 = $this->input->post('proweight');
                    $values4 = $this->input->post('id1');

                    for ($k = 0; $k < count($this->input->post('proname')); $k++) {
                        $id1 = $values4[$k];
                        if ($id1) {
                            $id1 = $id1;
                        } else {
                            $id1 = 0;
                        }
                        $this->data['sec_item12'] = $this->food_sec_item_model->where('id', $id1)->get_all();
                        if (count($this->data['sec_item12'][0]) > 0) {
                            $is_updated = $this->food_sec_item_model->update([
                                'id' => $this->data['sec_item12'][0]['id'],
                                'menu_id' => $menu_id,
                                'item_id' => $item_id,
                                'sec_id' => $section_id,
                                'section_item_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                                'name' => $values1[$k],
                                'price' => $values2[$k],
                                'weight' => $values3[$k],
                                'status' => 1
                            ], 'id');
                        } else {
                            $data1 = array(
                                'menu_id' => $menu_id,
                                'item_id' => $item_id,
                                'sec_id' => $section_id,
                                'section_item_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                                'name' => $values1[$k],
                                'price' => $values2[$k],
                                'weight' => $values3[$k],
                                'status' => 1
                            );
                            $this->food_sec_item_model->insert($data1);
                        }
                    }
                }

                if ($_FILES["item_images"]["name"][0] != "") {
                    $dt = $this->food_item_image_model->where('item_id', $this->input->post('id'))
                        ->get_all();
                    foreach ($dt as $d) {
                        unlink('./uploads/' . 'food_item' . '_image/' . 'food_item' . '_' . $d['id'] . '.jpg');
                    }

                    $is_deleted = $this->food_item_image_model->delete([
                        'item_id' => $this->input->post('id')
                    ]);
                    if ($is_deleted) {
                        foreach ($_FILES['item_images']['name'] as $key => $name) {
                            $product_image_id = $this->food_item_image_model->insert([
                                'item_id' => $item_id,
                                'serial_number' => ++$i,
                                'ext' => 'jpg'
                            ]);
                            $uploadFileDir = './uploads/food_item_image/';
                            $dest_path = $uploadFileDir;
                            $dest_path = $uploadFileDir . "food_item_" . $product_image_id . ".jpg";
                            move_uploaded_file($_FILES['item_images']['tmp_name'][$key], $dest_path);
                        }
                    }
                }
                $this->session->set_flashdata('upload_status', 'Product has been updated successfully');
                //redirect('food_product/0/r', 'refresh');
                $page = $this->input->post('page') ?? 1;

				redirect('food/food/food_product/' . $page, 'refresh');
            }
        } else if ($type == 'e') {

            $this->form_validation->set_rules($this->food_item_model->rules);

            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } elseif (empty($this->input->post('proname'))) {
                echo "Please add variants.";
                die();
            } else {
                $sounds_like = $this->sounds_like($this->input->post('name'), $this->input->post('shop_by_cat_id'), $this->input->post('menu_id'));

                $sub_cat_id = $this->input->post('sub_cat_id');
                $menu_id = $this->input->post('menu_id');
                $proname = $this->input->post('proname');
                $brand_id = $this->input->post('brand_id');
                $this->db->trans_begin();
                if ($this->ion_auth->is_admin()) {
                    $item_id = $this->food_item_model->insert([
                        'sub_cat_id' => $sub_cat_id,
                        'menu_id' => $menu_id,
                        'brand_id' => $brand_id,
                        'item_type' => $this->input->post('item_type'),
                        'product_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                        'name' => $this->input->post('name'),
                        'desc' => (empty($this->input->post('desc'))) ? NULL : $this->input->post('desc'),
                        'sounds_like' => $sounds_like,
                        'availability' => 1,
                        'status' => 1
                    ]);
                } else {
                    $item_id = $this->food_item_model->insert([
                        'sub_cat_id' => $sub_cat_id,
                        'menu_id' => $menu_id,
                        'brand_id' => $brand_id,
                        'item_type' => $this->input->post('item_type'),
                        'product_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                        'name' => $this->input->post('name'),
                        'desc' => (empty($this->input->post('desc'))) ? NULL : $this->input->post('desc'),
                        'sounds_like' => $sounds_like,
                        'availability' => 1,
                        'status' => 3
                    ]);
                }

                if ($item_id) {
                    $section_id = $this->food_section_model->insert([
                        'menu_id' => $menu_id,
                        'item_id' => $item_id,
                        'name' => $this->input->post('name')
                    ]);

                    if ($section_id && !empty($this->input->post('proname'))) {
                        $section_items = [];
                        $values1 = $this->input->post('proname');
                        $values2 = $this->input->post('proprice');
                        $values3 = $this->input->post('proweight');
                        for ($k = 0; $k < count($this->input->post('proname')); $k++) {
                            array_push($section_items, [
                                'menu_id' => $menu_id,
                                'item_id' => $item_id,
                                'sec_id' => $section_id,
                                'section_item_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                                'name' => $values1[$k],
                                'price' => $values2[$k],
                                'weight' => $values3[$k],
                                'status' => 1
                            ]);
                        }
                        $is__section_items_inserted = $this->food_sec_item_model->insert($section_items);
                        if ($this->db->trans_status() === FALSE && empty($is__section_items_inserted)) {
                            $this->session->set_flashdata('upload_status', 'variants data is missed, please create the product again.');
                            $this->db->trans_rollback();
                        } else {
                            $this->session->set_flashdata('upload_status', 'Product has been added successfully');
                            $this->db->trans_commit();
                        }
                    }
                    $i = 0;
                    foreach ($_FILES['item_images']['name'] as $key => $name) {
                        $product_image_id = $this->food_item_image_model->insert([
                            'item_id' => $item_id,
                            'serial_number' => ++$i,
                            'ext' => 'jpg'
                        ]);
                        if (!file_exists('uploads/' . 'food_item_image/')) {
                            mkdir('uploads/' . 'food_item_image/', 0777, true);
                        }

                        $uploadFileDir = './uploads/food_item_image/';
                        $dest_path = $uploadFileDir;
                        $dest_path = $uploadFileDir . "food_item_" . $product_image_id . ".jpg";
                        move_uploaded_file($_FILES['item_images']['tmp_name'][$key], $dest_path);
                    }
                }
            }
            redirect('food_product/0/r', 'refresh');
        } elseif ($type == 'l') {
            $this->data['title'] = 'Edit Item';
            $this->data['content'] = 'food/food/excel_product';
            $this->data['nav_type'] = 'food_item';
            $this->data['type'] = 'food_item';
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'k') {
            if (!$this->input->post('submit')) {
                $path = 'uploads/';
                require_once APPPATH . "/third_party/PHPExcel.php";
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'xlsx|xls';
                $config['remove_spaces'] = TRUE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('uploadFile')) {
                    $error = array(
                        'error' => $this->upload->display_errors()
                    );
                } else {
                    $data = array(
                        'upload_data' => $this->upload->data()
                    );
                }

                if (!empty($data['upload_data']['file_name'])) {
                    $import_xls_file = $data['upload_data']['file_name'];
                } else {
                    $import_xls_file = 0;
                }
                $inputFileName = $path . $import_xls_file;

                try {

                    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);
                    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    $flag = true;
                    $i = 0;

                    $sounds_like = $this->sounds_like($this->input->post('name'), $this->input->post('shop_by_cat_id'), $this->input->post('menu_id'));

                    foreach ($allDataInSheet as $value) {
                        if ($flag) {
                            $flag = false;
                            continue;
                        }
                        $sounds_like = $this->sounds_like($value['D'], $value['A'], $value['B']);
                        $sub_cat_id = $value['A'];
                        $menu_id = $value['B'];
                        $brand_id = $value['C'];
                        $name = $value['D'];
                        $desc = $value['E'];
                        $availability = $value['F'];
                        $status = $value['G'];

                        $item_id = $this->food_item_model->insert([
                            'sub_cat_id' => $sub_cat_id,
                            'menu_id' => $menu_id,
                            'brand_id' => $brand_id,
                            'product_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                            'name' => $name,
                            'desc' => (empty($this->input->post('desc'))) ? NULL : $this->input->post('desc'),
                            'sounds_like' => $sounds_like,
                            'availability' => 1,
                            'status' => 1
                        ]);

                        if ($item_id) {
                            $section_id = $this->food_section_model->insert([
                                'menu_id' => $menu_id,
                                'item_id' => $item_id,
                                'name' => $name
                            ]);

                            $values1 = $value['H'];
                            $arrname = explode(',', $values1);

                            if ($section_id && !empty($arrname)) {

                                $section_items = [];

                                $values1 = $value['H'];
                                $values2 = $value['I'];
                                $values3 = $value['J'];
                                $arrname = explode(',', $values1);
                                $arrprice = explode(',', $values2);
                                $arrweight = explode(',', $values3);

                                for ($k = 0; $k < count($arrname); $k++) {
                                    array_push($section_items, [
                                        'menu_id' => $menu_id,
                                        'item_id' => $item_id,
                                        'sec_id' => $section_id,
                                        'section_item_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                                        'name' => $arrname[$k],
                                        'price' => $arrprice[$k],
                                        'weight' => $arrweight[$k],
                                        'status' => 1
                                    ]);
                                }

                                $result = $this->food_sec_item_model->insert($section_items);

                                $product_image_id = $this->food_item_image_model->insert([
                                    'item_id' => $item_id,
                                    'serial_number' => ++$i,
                                    'ext' => 'jpg'
                                ]);

                                // $url=$d[1];

                                $url = $value['K'];

                                $name_temp = basename($url);

                                // $wotname = basename($name_temp,".php");
                                $name_temp1 = "food_item_" . $product_image_id . ".jpg";

                                if (!file_exists('uploads/' . 'food_item_image/')) {
                                    mkdir('uploads/' . 'food_item_image/', 0777, true);
                                }

                                $my_location = './uploads/food_item_image/';

                                if (file_exists($my_location . '' . $name_temp1)) {
                                    $fileinfo = pathinfo($url);
                                    $name = $fileinfo['filename'] . '_' . rand(1, 10000) . '.' . $fileinfo['extension'];
                                } else {
                                    $name = $name_temp1;
                                }

                                if (!defined('IMAGE_DIR'))
                                    define('IMAGE_DIR', $my_location);

                                $img = file_get_contents($url);
                                if (!$img) {
                                    die('Getting that file failed');
                                }

                                /* if (! $f = fopen(IMAGE_DIR . '/' . $name, 'w')) {
                                    die('Opening file for writing failed');
                                } */

                                $is_updated = file_put_contents(IMAGE_DIR . '/' . $name, $img);

                                if ($is_updated === FALSE) {
                                    die('Could not write to the file');
                                }
                                fclose($f);
                            }
                        }
                    }

                    if ($result) {

                        $this->session->set_flashdata('upload_status', [
                            "success" => "Imported successfully imported..!"
                        ]);

                        // echo "Imported successfully";
                        redirect('food_product/0/r', 'refresh');
                    } else {
                        echo "ERROR !";
                    }
                } catch (Exception $e) {

                    die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                }
            }
        } elseif ($type == 'sec_item') {
            $this->food_sec_item_model->delete([
                'id' => base64_decode(base64_decode($this->input->get('id')))
            ]);
            redirect($_SERVER['HTTP_REFERER']);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Item';
            $this->data['content'] = 'food/food/edit_food_product';
            $this->data['nav_type'] = 'food_item';
            $this->data['type'] = 'food_item';
            $this->data['sub_items'] = $this->food_item_model->order_by('id', 'DESC')
                ->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();
            $subcat_id = $this->data['sub_items']['sub_cat_id'];

            if ($this->ion_auth->is_admin()) {
                $w_r = '(vendor_id = ' . $this->ion_auth->get_user_id() . ')';
            } else {
                $w_r = '(vendor_id = ' . $this->ion_auth->get_user_id() . ' OR vendor_id = 1)';
            }
            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])
                        ->get_all();
                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])
                    ->get_all();
            }

            $tempid = $this->data['sub_items']['id'];
            //echo $tempid; exit;
            $this->data['sec_item1'] = $this->food_sec_item_model->where('item_id', $tempid)->get_all();
            $this->data['food_sec'] = $this->food_section_model->where('item_id', $tempid)->get_all();
            //$this->db->where($w_r);
            $this->data['items'] = $this->food_menu_model->where('sub_cat_id', $subcat_id)->fields('id,name,desc,vendor_id')->order_by('id', 'DESC')->get_all();
            //$this->data['items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->order_by('id', 'DESC')->get_all();
            $this->data['food_sub_items'] = $this->food_item_model->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();

            $this->data['food_sub_items'] = $this->food_item_model->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();

            $this->data['food_sec'] = $this->food_section_model->where('item_id', $tempid)->get_all();

            $this->data['img'] = $this->food_item_image_model->where('item_id', $tempid)->get_all();

            $this->data['brands'] = $this->brand_model->fields('id,name,desc')->get_all();

            $this->_render_page($this->template, $this->data);
        } else if ($type = 'c') {
            $this->data['title'] = 'Product';
            $this->data['content'] = 'food/food/food_product';
            $this->data['nav_type'] = 'food_product';

            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])
                        ->get_all();

                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])
                    ->get_all();
            }

            $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->get_all();
            $this->data['brands'] = $this->brand_model->fields('id,name,desc')->get_all();
            $this->_render_page($this->template, $this->data);
        }
    }

    public function foodproductstatus($type = 'change__st')
    {
        $this->food_item_model->update([
            'availability' => ($this->input->post('is_checked') == 'true') ? 1 : 0
        ], $this->input->post('vendor_id'));
    }

    /*
     * public function foodproducttogglestatus($type = 'changestatus')
     * {
     * $st = ($this->input->post('is_checked') == 'true') ? 3 : 2;
     * echo $this->input->post('vendor_id');
     *
     * $this->food_item_model->update([
     * 'status' => $st
     * ], $this->input->post('vendor_id'));
     * }
     */
    public function food_item($type = 'r')
    {
        if ($type == 'c') {

            $this->form_validation->set_rules($this->food_item_model->rules);

            if (empty($_FILES['file']['name'])) {
                $this->form_validation->set_rules('file', 'Food Item Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->food_item('r');
            } else {
                $sounds_like = $this->sounds_like($this->input->post('name'), $this->input->post('sub_cat_id'), $this->input->post('menu_id'));
                $input_data = array(
                    'sub_cat_id' => $this->input->post('sub_cat_id'),
                    'menu_id' => $this->input->post('menu_id'),
                    'product_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                    'price' => $this->input->post('price'),
                    'quantity' => $this->input->post('quantity'),
                    'status' => $this->input->post('status'),
                    'item_type' => $this->input->post('item_type'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'discount' => $this->input->post('discount'),
                    'approval_status' => ($this->ion_auth->is_admin()) ? 1 : 2,
                    'sounds_like' => $sounds_like
                );
                $id = $this->food_item_model->insert($input_data);
                $this->file_up("file", "food_item", $id, '', 'no');
                redirect('products/0', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Items';
            $this->data['content'] = 'food/food/food_item';
            $this->data['nav_type'] = 'food_item';
            /*
             * if ($this->ion_auth->is_admin()) {
             * $w_r = '(vendor_id = ' . $this->ion_auth->get_user_id() . ')';
             * } else {
             * $w_r = '(vendor_id = ' . $this->ion_auth->get_user_id() . ' OR vendor_id = 1)';
             * }
             * if (! $this->ion_auth->is_admin()) {
             * $cat_id = $this->vendor_list_model->with_sub_categories('fields: id, name, status', 'where: type = 2')
             * ->where('vendor_user_id', $this->ion_auth->get_user_id())
             * ->get();
             * $sub_cats = $this->db->query("SELECT sub_categories.id, sub_categories.name FROM `shop_by_categories` JOIN sub_categories ON sub_categories.id = shop_by_categories.sub_cat_id where shop_by_categories.vendor_id = " . $this->ion_auth->get_user_id() . " OR shop_by_categories.vendor_id = 1 AND sub_categories.type = 2 AND sub_categories.cat_id=" . $cat_id['category_id'])
             * ->result_array();
             * $sub_categories = $cat_id['sub_categories'];
             *
             * $su = '';
             * foreach ($sub_cats as $s) {
             * if ($su == '') {
             * $su = 'sub_cat_id = ' . $s['id'];
             * } else {
             * $su = $su . ' OR sub_cat_id = ' . $s['id'];
             * }
             * }
             * if($su != ''){
             * $w_r = $w_r . ' and (' . $su . ')';
             * }
             * }
             *
             * $this->db->where($w_r);
             * $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->get_all();
             *
             * $menus = array_column($this->data['food_items'], 'id');
             * if ($this->ion_auth->is_admin()) {
             * $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ')';
             * } else {
             * $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR vendor_id = 1)';
             * }
             * $me = array();
             * foreach ($this->data['food_items'] as $menu) {
             * $a = $this->data['food_sub_items'] = $this->food_item_model->with_menu('fields:id,name,vendor_id')
             * ->where($w_r1)
             * ->where('menu_id', $menu['id'])
             * ->order_by('id', 'ASCE')
             * ->get_all();
             * if (! empty($a)) {
             * foreach ($a as $s) {
             * $cou = $this->db->get_where('deleted_items', array(
             * 'vendor_id' => $this->ion_auth->get_user_id(),
             * 'item_id' => $s['id']
             * ))
             * ->num_rows();
             * if ($cou == 0) {
             * $me[] = $s;
             * }
             * }
             * }
             * }
             */

            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])
                        ->get_all();

                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])
                    ->get_all();
            }

            // $this->data['food_sub_items'] = $me;
            // $this->data['food_sub_items'] = $this->food_item_model->with_menu('fields:id,name,vendor_id','where: vendor_id='.$this->ion_auth->get_user_id())->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {

            $this->form_validation->set_rules($this->food_item_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
                die();
            } else {
                $sounds_like = $this->sounds_like($this->input->post('name'), NULL, $this->input->post('menu_id'));
                $input_data = array(
                    'menu_id' => $this->input->post('menu_id'),
                    'price' => $this->input->post('price'),
                    'quantity' => $this->input->post('quantity'),
                    'status' => $this->input->post('status'),
                    'item_type' => $this->input->post('item_type'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'sounds_like' => $sounds_like,
                    'discount' => $this->input->post('discount')
                );
                $item_id = $this->input->post('id');
                $item = $this->food_item_model->where('id', $this->input->post('id'))
                    ->get();
                $s = 0;
                if ($this->ion_auth->is_admin() || $item['created_user_id'] == $this->ion_auth->get_user_id()) {
                    $s = 1;
                } else {
                    $cou = $this->db->get_where('deleted_items', array(
                        'vendor_id' => $this->ion_auth->get_user_id(),
                        'item_id' => $item_id
                    ))
                        ->num_rows();
                    if ($cou > 0) {
                        $s = 1;
                    } else {
                        $s = 2;
                    }
                }

                if ($s == 1) {
                    $this->food_item_model->update($input_data, $item_id);
                } elseif ($s == 2) {
                    $this->db->insert('deleted_items', array(
                        'vendor_id' => $this->ion_auth->get_user_id(),
                        'item_id' => $item_id,
                        'deleted_at' => date('Y-m-d h:i:s')
                    ));
                    $old = $item_id;
                    $input_data['approval_status'] = ($this->ion_auth->is_admin()) ? 1 : 2;
                    $item_id = $this->food_item_model->insert($input_data);
                    copy('uploads/food_item_image/food_item_' . $old . '.jpg', 'uploads/food_item_image/food_item_' . $item_id . '.jpg');
                }

                if ($_FILES['file']['name'] !== '') {
                    unlink('uploads/' . 'food_item' . '_image/' . 'food_item' . '_' . $this->input->post('id') . '.jpg');
                    move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'food_item' . '_image/' . 'food_item' . '_' . $this->input->post('id') . '.jpg');
                }
                redirect('products/0', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->food_item_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'ven_item') {
            $this->db->insert('deleted_items', array(
                'vendor_id' => $this->ion_auth->get_user_id(),
                'item_id' => $this->input->post('id'),
                'deleted_at' => date('Y-m-d h:i:s')
            ));
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Item';
            $this->data['content'] = 'food/food/edit';
            $this->data['nav_type'] = 'food_item';
            $this->data['type'] = 'food_item';
            $this->data['sub_items'] = $this->food_item_model->order_by('id', 'DESC')
                ->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();

            if ($this->ion_auth->is_admin()) {
                $w_r = '(vendor_id = ' . $this->ion_auth->get_user_id() . ')';
            } else {
                $w_r = '(vendor_id = ' . $this->ion_auth->get_user_id() . ' OR vendor_id = 1)';
            }
            if (!$this->ion_auth->is_admin()) {
                $cat_id = $this->vendor_list_model->with_sub_categories('fields: id, name, status')
                    ->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $sub_categories = $cat_id['sub_categories'];

                $su = '';
                foreach ($sub_categories as $s) {
                    if ($su == '') {
                        $su = 'sub_cat_id = ' . $s['id'];
                    } else {
                        $su = $su . ' OR sub_cat_id = ' . $s['id'];
                    }
                }
                $w_r = $w_r . ' and (' . $su . ')';
            }

            // $this->db->where($w_r);
            $this->data['items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')
                ->order_by('id', 'DESC')
                ->get_all();
            $this->data['food_sub_items'] = $this->food_item_model->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();
            $this->_render_page($this->template, $this->data);
        }
    }

    public function products($rowno = 0)
    {
        $this->data['title'] = 'Products list';
        $this->data['content'] = 'food/food/products';
        $this->data['nav_type'] = 'products';
        // Search text
        $search_text = "";
        $noofrows = 10;
        if ($this->input->post('submit') != NULL) {
            $search_text = $this->input->post('q');
            $noofrows = $this->input->post('noofrows');
            $this->session->set_userdata(array(
                "q" => $search_text,
                'noofrows' => $noofrows
            ));
        } else {
            if ($this->session->userdata('q') != NULL || $noofrows != NULL) {
                $search_text = $this->session->userdata('q');
                $noofrows = $this->session->userdata('noofrows');
            }
        }

        $rowperpage = $noofrows ? $noofrows : 10;
        if ($rowno != 0) {
            $rowno = ($rowno - 1) * $rowperpage;
        }
        if (!$this->ion_auth->in_group(1)) {
            $admin_ids = $this->get_users_by_group(1);
            array_push($admin_ids, $this->ion_auth->get_user_id());
            $deleted_items = $this->db->get_where('deleted_items', [
                'vendor_id' => $this->ion_auth->get_user_id()
            ])
                ->result_array();
            if ($deleted_items) {
                $deleted_items = array_column($deleted_items, 'item_id');
            } else {
                $deleted_items = [
                    0
                ];
            }

            $this->db->like('sounds_like', metaphone($search_text));
            $this->db->or_where('product_code', $search_text);
            $allcount = $this->food_item_model->where('created_user_id', $admin_ids)
                ->where('id NOT', $deleted_items)
                ->count_rows();

            $this->db->like('food_item.sounds_like', metaphone($search_text));
            $this->db->or_where('food_item.product_code', $search_text);
            $catalogue_products = $this->food_item_model->with_menu('fields: id, name, vendor_id')
                ->with_sub_category('fields: id, name')
                ->where('created_user_id', $admin_ids)
                ->where('id NOT', $deleted_items)
                ->order_by('id', 'DESC')
                ->limit($rowperpage, $rowno)
                ->get_all();
        } else {
            $this->db->like('sounds_like', metaphone($search_text));
            $this->db->or_where('product_code', $search_text);
            $allcount = $this->food_item_model->count_rows();

            $this->db->like('food_item.sounds_like', metaphone($search_text));
            $this->db->or_where('food_item.product_code', $search_text);
            $catalogue_products = $this->food_item_model->with_menu('fields: id, name, vendor_id')
                ->with_sub_category('fields: id, name')
                ->order_by('id', 'DESC')
                ->limit($rowperpage, $rowno)
                ->get_all();
        }

        if (!empty($catalogue_products)) {
            foreach ($catalogue_products as $key => $val) {
                $catalogue_products[$key]['product_image'] = base_url() . 'uploads/food_item_image/food_item_' . $val['id'] . '.jpg';
            }
        }

        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tagl_close'] = "</li>";
        $config['base_url'] = base_url() . 'food/food/products';
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $allcount;
        $config['per_page'] = $rowperpage;

        // Initialize
        $this->pagination->initialize($config);

        $this->data['pagination'] = $this->pagination->create_links();
        $this->data['products'] = $catalogue_products;
        $this->data['row'] = $rowno;
        $this->data['q'] = $search_text;
        $this->data['noofrows'] = $rowperpage;
        $this->_render_page($this->template, $this->data);
    }

    public function menu_by_category()
    {
        $menu = $this->food_menu_model->where('sub_cat_id', $_POST['cat_id'])->get_all();

        echo json_encode($menu);
    }

    public function menu_by_category1()
    {
        $arr = array();
        if ($_POST['sub_cat_id'] == 'all') {
            echo "1";
            return;
        }
        $menu1 = $this->food_menu_model->where('sub_cat_id', $_POST['sub_cat_id'])->get_all();
        $id = $_POST['sub_cat_id'];
        $ctid = $this->db->query("SELECT cat_id from sub_categories where id = '$id'")->result_array();
        $cat_id1 = $ctid[0]['cat_id'];
        $brid = $this->db->query("SELECT brand_id from categories_brands where cat_id = '$cat_id1'")->result_array();

        foreach ($brid as $a) {
            $arr[] = $a['brand_id'];
        }

        $arr1 = $brid[0]['brand_id'];
        $brand_string = implode(',', $arr);
        $brname = $this->db->query("SELECT *  FROM brands WHERE id IN ($brand_string)")->result_array();
        echo json_encode(array(
            $menu1,
            $brname
        ));
    }

    public function category_changed()
    {
        $arr = array();
        $sub_cat_id = $this->sub_category_model->where([
            'cat_id' => $_POST['cat_id'],
            'type' => 2
        ])->get_all();
        echo json_encode($sub_cat_id);
    }

    public function menu_by_brands()
    {
        $menu = $this->categoriesbrands_model->where('cat_id', $_POST['cat_id'])->get_all();
        $arr = array();
        foreach ($menu as $m) {
            $arr[] = $m['brand_id'];
        }
        $str = implode(',', $arr);
        $menu1 = $this->brand_model->fetchdata($arr);
        echo json_encode($menu1);
    }

    public function items_by_menu()
    {
        $menu = $this->food_item_model->where('menu_id', $_POST['menu_id'])->get_all();
        echo json_encode($menu);
    }

    public function sections_by_item()
    {
        $sec_item = $this->vendor_product_variant_model->where('item_id', $_POST['item_id'])->get_all();
        $arr = array();
        foreach ($sec_item as $sec) {
            $arr[] = $sec['section_item_id'];
        }
        $str = implode(',', $arr);
        $varient = $this->food_sec_item_model->where('id', $str)->get_all();
        echo json_encode($varient);
    }

    public function sections_by_product()
    {
        $menu = $this->food_section_model->where('item_id', $_POST['product_id'])->get_all();
        echo json_encode($menu);
    }

    /**
     * To Manage Food Sections
     *
     * @author Mahesh
     * @param string $type
     */
    public function food_section($type = 'r')
    {
        if ($type == 'c') {
            $this->form_validation->set_rules($this->food_section_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->food_section('r');
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

                redirect('sections/0', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Section';
            $this->data['content'] = 'food/food/section';
            $this->data['nav_type'] = 'section';

            /*
             * if ($this->ion_auth->is_admin()) {
             * $w_r = '(vendor_id = ' . $this->ion_auth->get_user_id() . ')';
             * } else {
             * $w_r = '(vendor_id = ' . $this->ion_auth->get_user_id() . ' OR vendor_id = 1)';
             * }
             * if (! $this->ion_auth->is_admin()) {
             * $cat_id = $this->vendor_list_model->with_sub_categories('fields: id, name, status', 'where: type = 2')
             * ->where('vendor_user_id', $this->ion_auth->get_user_id())
             * ->get();
             * $sub_cats = $this->db->query("SELECT sub_categories.id, sub_categories.name FROM `shop_by_categories` JOIN sub_categories ON sub_categories.id = shop_by_categories.sub_cat_id where shop_by_categories.vendor_id = " . $this->ion_auth->get_user_id() . " OR shop_by_categories.vendor_id = 1 AND sub_categories.type = 2 AND sub_categories.cat_id=" . $cat_id['category_id'])
             * ->result_array();
             * $sub_categories = $cat_id['sub_categories'];
             *
             * $su = '';
             * foreach ($sub_cats as $s) {
             * if ($su == '') {
             * $su = 'sub_cat_id = ' . $s['id'];
             * } else {
             * $su = $su . ' OR sub_cat_id = ' . $s['id'];
             * }
             * }
             * $w_r = $w_r . ' and (' . $su . ')';
             * }
             *
             * $this->db->where($w_r);
             * $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->get_all();
             * $w_r = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
             * $me = array();
             * foreach ($this->data['food_items'] as $menu) {
             * $this->db->where($w_r);
             * $a = $this->food_section_model->with_menu('fields:id,name,vendor_id')
             * ->with_item('fields:name')
             * ->where('menu_id', $menu['id'])
             * ->order_by('id', 'ASCE')
             * ->get_all();
             * if (! empty($a)) {
             * foreach ($a as $s) {
             * $me[] = $s;
             * }
             * }
             * }
             */
            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])
                        ->get_all();

                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])
                    ->get_all();
            }
            // $this->data['food_section'] = $me;
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->food_section_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                if ($this->input->post('item_field') == 2) {
                    $sec_price = 1;
                } elseif ($this->input->post('item_field') == 1) {
                    $sec_price = $this->input->post('sec_price');
                }
                $this->food_section_model->update([
                    'id' => $this->input->post('id'),
                    'menu_id' => $this->input->post('menu_id'),
                    'item_id' => $this->input->post('item_id'),
                    'item_field' => $this->input->post('item_field'),
                    'sec_price' => $sec_price,
                    'required' => $this->input->post('require_items'),
                    'name' => $this->input->post('name')
                ], 'id');

                redirect('sections/0', 'refresh');
            }
        } elseif ($type == 'd') {
            echo $this->food_section_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Section';
            $this->data['content'] = 'food/food/edit';
            $this->data['nav_type'] = 'section';
            $this->data['type'] = 'food_section';
            $this->data['section'] = $this->food_section_model->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();
            $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')
                ->order_by('id', 'DESC')
                ->get_all();
            $this->data['food_sub_items'] = $this->food_item_model->order_by('id', 'DESC')
                ->where('menu_id', $this->data['section']['menu_id'])
                ->get_all();
            $this->_render_page($this->template, $this->data);
        }
    }

    public function sections($rowno = 0)
    {
        $this->data['title'] = 'Sections list';
        $this->data['content'] = 'food/food/sections';
        $this->data['nav_type'] = 'section';
        // Search text
        $search_text = "";
        $noofrows = 10;
        if ($this->input->post('submit') != NULL) {
            $search_text = $this->input->post('q');
            $noofrows = $this->input->post('noofrows');
            $this->session->set_userdata(array(
                "q" => $search_text,
                'noofrows' => $noofrows
            ));
        } else {
            if ($this->session->userdata('q') != NULL || $noofrows != NULL) {
                $search_text = $this->session->userdata('q');
                $noofrows = $this->session->userdata('noofrows');
            }
        }

        $rowperpage = $noofrows ? $noofrows : 10;
        if ($rowno != 0) {
            $rowno = ($rowno - 1) * $rowperpage;
        }
        if (!$this->ion_auth->in_group(1)) {
            $admin_ids = $this->get_users_by_group(1);
            array_push($admin_ids, $this->ion_auth->get_user_id());
            $this->db->like('food_section.name', $search_text);
            $allcount = $this->food_section_model->where('created_user_id', $admin_ids)->count_rows();

            $this->db->like('food_section.name', $search_text);
            $this->data['sections'] = $this->food_section_model->with_item('fields:name')
                ->with_menu('fields:id, name, vendor_id')
                ->where('created_user_id', $admin_ids)
                ->order_by('id', 'DESC')
                ->limit($rowperpage, $rowno)
                ->get_all();
        } else {
            $this->db->like('food_section.name', $search_text);
            $allcount = $this->food_section_model->count_rows();

            $this->db->like('food_section.name', $search_text);
            $this->data['sections'] = $this->food_section_model->with_item('fields:name')
                ->with_menu('fields:id, name, vendor_id')
                ->order_by('id', 'DESC')
                ->limit($rowperpage, $rowno)
                ->get_all();
        }

        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tagl_close'] = "</li>";
        $config['base_url'] = base_url() . 'food/food/sections';
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $allcount;
        $config['per_page'] = $rowperpage;

        // Initialize
        $this->pagination->initialize($config);

        $this->data['pagination'] = $this->pagination->create_links();
        $this->data['row'] = $rowno;
        $this->data['q'] = $search_text;
        $this->data['noofrows'] = $rowperpage;
        $this->_render_page($this->template, $this->data);
    }

    /**
     * Food Sub Item crud
     *
     * To Manage Food Sub Items
     *
     * @author Mahesh
     * @param string $type
     * @param string $target
     */
    public function food_section_item($type = 'r')
    {

        /*
         * if (! $this->ion_auth_acl->has_permission('food'))
         * redirect('admin');
         */
        if ($type == 'c') {

            $this->form_validation->set_rules($this->food_sec_item_model->rules);

            if ($this->form_validation->run() == FALSE) {
                $this->food_section_item('r');
            } else {
                $id = $this->food_sec_item_model->insert([
                    'menu_id' => $this->input->post('menu_id'),
                    'item_id' => $this->input->post('item_id'),
                    'sec_id' => $this->input->post('sec_id'),
                    'sku' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                    'price' => $this->input->post('price'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'status' => $this->input->post('status')
                ]);

                redirect('section_items/0', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Section Item';
            $this->data['content'] = 'food/food/sec_item';
            $this->data['nav_type'] = 'sec_item';

            /*
             * if ($this->ion_auth->is_admin()) {
             * $w_r = '(vendor_id = ' . $this->ion_auth->get_user_id() . ')';
             * } else {
             * $w_r = '(vendor_id = ' . $this->ion_auth->get_user_id() . ' OR vendor_id = 1)';
             * }
             * if (! $this->ion_auth->is_admin()) {
             * $cat_id = $this->vendor_list_model->with_sub_categories('fields: id, name, status', 'where: type = 2')
             * ->where('vendor_user_id', $this->ion_auth->get_user_id())
             * ->get();
             * $sub_cats = $this->db->query("SELECT sub_categories.id, sub_categories.name FROM `shop_by_categories` JOIN sub_categories ON sub_categories.id = shop_by_categories.sub_cat_id where shop_by_categories.vendor_id = " . $this->ion_auth->get_user_id() . " OR shop_by_categories.vendor_id = 1 AND sub_categories.type = 2 AND sub_categories.cat_id=" . $cat_id['category_id'])
             * ->result_array();
             * $sub_categories = $cat_id['sub_categories'];
             *
             * $su = '';
             * foreach ($sub_cats as $s) {
             * if ($su == '') {
             * $su = 'sub_cat_id = ' . $s['id'];
             * } else {
             * $su = $su . ' OR sub_cat_id = ' . $s['id'];
             * }
             * }
             * $w_r = $w_r . ' and (' . $su . ')';
             * }
             *
             * $this->db->where($w_r);
             * $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->get_all();
             * $me = array();
             * foreach ($this->data['food_items'] as $menu) {
             * $a = $this->food_sec_item_model->with_menu('fields:name')
             * ->with_item('fields:name')
             * ->with_sec('fields:name')
             * ->where('menu_id', $menu['id'])
             * ->order_by('id', 'ASCE')
             * ->get_all();
             * if (! empty($a)) {
             * foreach ($a as $s) {
             * $me[] = $s;
             * }
             * }
             * }
             */
            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])
                        ->get_all();

                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])
                    ->get_all();
            }
            // $this->data['food_sec_items'] = $me;
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->food_sec_item_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $this->food_sec_item_model->update([
                    'menu_id' => $this->input->post('menu_id'),
                    'item_id' => $this->input->post('item_id'),
                    'sec_id' => $this->input->post('sec_id'),
                    'price' => $this->input->post('price'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'status' => $this->input->post('status')
                ], $this->input->post('id'));
                redirect('section_items/0', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->food_sec_item_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Section Item';
            $this->data['content'] = 'food/food/edit';
            $this->data['type'] = 'food_sec_item';
            $this->data['nav_type'] = 'sec_item';
            $this->data['sec_item'] = $this->food_sec_item_model->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();
            $this->data['section'] = $this->food_section_model->order_by('id', 'DESC')
                ->where('id', $this->data['sec_item']['sec_id'])
                ->get();
            $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')
                ->order_by('id', 'DESC')
                ->get_all();
            $this->data['sub_items'] = $this->food_item_model->order_by('id', 'DESC')
                ->where('menu_id', $this->data['section']['menu_id'])
                ->get_all();
            $this->data['sections'] = $this->food_section_model->order_by('id', 'DESC')
                ->where('item_id', $this->data['section']['item_id'])
                ->get_all();

            $this->_render_page($this->template, $this->data);
        }
    }

    public function section_items($rowno = 0)
    {
        $this->data['title'] = 'Section items list';
        $this->data['content'] = 'food/food/section_items';
        $this->data['nav_type'] = 'sec_item';
        // Search text
        $search_text = "";
        $noofrows = 10;
        if ($this->input->post('submit') != NULL) {
            $search_text = $this->input->post('q');
            $noofrows = $this->input->post('noofrows');
            $this->session->set_userdata(array(
                "q" => $search_text,
                'noofrows' => $noofrows
            ));
        } else {
            if ($this->session->userdata('q') != NULL || $noofrows != NULL) {
                $search_text = $this->session->userdata('q');
                $noofrows = $this->session->userdata('noofrows');
            }
        }

        $rowperpage = $noofrows ? $noofrows : 10;
        if ($rowno != 0) {
            $rowno = ($rowno - 1) * $rowperpage;
        }

        if (!$this->ion_auth->in_group(1)) {
            $admin_ids = $this->get_users_by_group(1);
            array_push($admin_ids, $this->ion_auth->get_user_id());
            $this->db->like('food_sec_item.name', $search_text);
            $allcount = $this->food_sec_item_model->where('created_user_id', $admin_ids)->count_rows();

            $this->db->like('food_sec_item.name', $search_text);
            $this->data['section_items'] = $this->food_sec_item_model->with_menu('fields:id, name, vendor_id')
                ->with_item('fields:id, name')
                ->with_sec('fields:id, name')
                ->where('created_user_id', $admin_ids)
                ->order_by('id', 'DESC')
                ->limit($rowperpage, $rowno)
                ->get_all();
        } else {
            $this->db->like('food_sec_item.name', $search_text);
            $allcount = $this->food_sec_item_model->count_rows();

            $this->db->like('food_sec_item.name', $search_text);
            $this->data['section_items'] = $this->food_sec_item_model->with_menu('fields:id, name, vendor_id')
                ->with_item('fields:id, name')
                ->with_sec('fields:id, name')
                ->order_by('id', 'DESC')
                ->limit($rowperpage, $rowno)
                ->get_all();
        }

        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tagl_close'] = "</li>";
        $config['base_url'] = base_url() . 'food/food/sections';
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $allcount;
        $config['per_page'] = $rowperpage;

        // Initialize
        $this->pagination->initialize($config);

        $this->data['pagination'] = $this->pagination->create_links();
        $this->data['row'] = $rowno;
        $this->data['q'] = $search_text;
        $this->data['noofrows'] = $rowperpage;
        $this->_render_page($this->template, $this->data);
    }

    /**
     * To Manage Food Orders History
     *
     * @author Mahesh
     * @param string $type
     */
    public function pickup_orders($type = 'r', $rowno = 0)
    {
        if ($type == 'r') {
            $this->data['title'] = 'Pickup Orders';
            $this->data['content'] = 'food/food/pickup_orders';
            $this->data['nav_type'] = 'pickup_orders';

            $this->data['orders'] = $this->db->query("SELECT po.*, v.name as vehicle_name,
            ep.id as payment_id, uap.address as pickup_address, uad.address as delivery_address,
            eos.status as order_status, u.first_name as customer_name, us.first_name as delivery_boy_name
            ,pl.latitude as pl_latitude
            ,pl.longitude as pl_longitude
            ,dl.latitude as dl_latitude
            ,dl.longitude as dl_longitude
            ,pdc.name as category_name
            from pickup_orders as po
            left join vehicle_type as v on v.id = po.vehicle_type
            left join ecom_payments as ep on ep.id = po.payment_id 
            left join users_address as uap on uap.id = po.pickup_address_id
            left join users_address as uad on uad.id = po.delivery_address_id
            left join locations as pl on pl.id = uap.location_id
            left join locations as dl on dl.id = uad.location_id
            left join ecom_order_statuses as eos on eos.id = po.order_status_id
            left join users as u on u.id = po.created_user_id
            left join delivery_jobs as dj on dj.pickup_order_id = po.id
            left join users as us on us.id = dj.delivery_boy_user_id
            join pickupanddropcategories as pdc on pdc.id = po.pickupanddropcategory_id
            ORDER BY po.id DESC
            ")->result_array();
            // print_r($this->data);
            // die();


            echo $this->_render_page($this->template, $this->data);
        }
    }
    public function food_orders($type = 'r', $rowno = 0)
    {
        if ($type == 'r') {
            $this->data['title'] = 'Orders';
            $this->data['content'] = 'food/food/orders';
            $this->data['nav_type'] = 'food_order';

            // $rowperpage = $noofrows ? $noofrows : 10;
            // if ($rowno != 0) {
            //     $rowno = ($rowno - 1) * $rowperpage;
            // }

            if ($this->input->post('submit') != NULL) {

                if ($this->input->post('vname') != NULL || $this->input->post('cname') != NULL || $this->input->post('tid') != NULL || $this->input->post('statusname') != NULL || $this->input->post('payment_method_name') != NULL || $this->input->post('delivery_boy_name') != NULL) {

                    $where = ' where true ';

                    if ($this->input->post('vname') != NULL) {
                        $search_text = $this->input->post('vname');

                        $where .= " and vl.vendor_user_id = '$search_text'";
                    }

                    if ($this->input->post('cname') != NULL) {
                        $search_text = $this->input->post('cname');

                        $where .= " and u.id = '$search_text'";
                    }

                    if ($this->input->post('tid') != NULL) {
                        $search_text = $this->input->post('tid');

                        $where .= " and eo.track_id = '$search_text'";
                    }

                    if ($this->input->post('statusname') != NULL) {
                        $search_text = $this->input->post('statusname');

                        $where .= " and eo.order_status_id = '$search_text'";
                    }
                    if ($this->input->post('payment_method_name') != NULL) {
                        $search_text = $this->input->post('payment_method_name');

                        $where .= " and ep.payment_method_id = '$search_text'";
                    }

                    if ($this->input->post('delivery_boy_name') != NULL) {
                        $search_text = $this->input->post('delivery_boy_name');
                        $where .= " and dj.delivery_boy_user_id = '$search_text'";
                    }

                    // $allcount = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,us.first_name as delivery_boy_name,vl.name as vandor_name,dm.name as delivery_mode_name FROM ecom_orders as eo join users_address as ua on ua.id =eo.shipping_address_id join users as u on u.id = eo.created_user_id join vendors_list as vl on vl.id = eo.vendor_user_id join ecom_payments as ep on ep.id = eo.payment_id join delivery_modes as dm on dm.id = eo.delivery_mode_id 
                    // left join delivery_jobs as dj on dj.ecom_order_id = eo.id  
                    // left join users as us on us.id = dj.delivery_boy_user_id" . $where)->num_rows();

                    $this->data['orders'] = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,us.first_name as delivery_boy_name,vl.name as vandor_name,dm.name as delivery_mode_name,ep.txn_id as payment_txn_id,ep.payment_method_id,vl.id as vandorpreid FROM ecom_orders as eo 
                    left join users_address as ua on ua.id =eo.shipping_address_id 
                    left join users as u on u.id = eo.created_user_id 
                    left join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id 
                    left join delivery_modes as dm on dm.id = eo.delivery_mode_id 
                    left join ecom_payments as ep on ep.id = eo.payment_id    
                    left join delivery_jobs as dj on dj.ecom_order_id = eo.id  
                    left join users as us on us.id = dj.delivery_boy_user_id" . $where . "ORDER BY eo.id DESC ")->result_array();
                } else {
                    // $allcount = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,us.first_name as delivery_boy_name,vl.name as vandor_name,dm.name as delivery_mode_name FROM ecom_orders as eo join users_address as ua on ua.id =eo.shipping_address_id join users as u on u.id = eo.created_user_id join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id join delivery_modes as dm on dm.id = eo.delivery_mode_id
                    // left join delivery_jobs as dj on dj.ecom_order_id = eo.id  
                    // left join users as us on us.id = dj.delivery_boy_user_id")->num_rows();


                    $this->data['orders'] = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,us.first_name as delivery_boy_name,vl.name as vandor_name,dm.name as delivery_mode_name,ep.txn_id as payment_txn_id,ep.payment_method_id,vl.id as vandorpreid FROM ecom_orders as eo 
                    left join users_address as ua on ua.id =eo.shipping_address_id 
                    left join users as u on u.id = eo.created_user_id 
                    left join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id 
                    left join delivery_modes as dm on dm.id = eo.delivery_mode_id 
                    left join ecom_payments as ep on ep.id = eo.payment_id  
                    left join delivery_jobs as dj on dj.ecom_order_id = eo.id  
                    left join users as us on us.id = dj.delivery_boy_user_id 
                    ORDER BY eo.id DESC ")->result_array();
                }
            }

            if ($this->input->post('submit') == NULL) {

                // $allcount = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,us.first_name as delivery_boy_name,vl.name as vandor_name,dm.name as delivery_mode_name FROM ecom_orders as eo join users_address as ua on ua.id =eo.shipping_address_id join users as u on u.id = eo.created_user_id join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id join delivery_modes as dm on dm.id = eo.delivery_mode_id
                // left join delivery_jobs as dj on dj.ecom_order_id = eo.id  
                // left join users as us on us.id = dj.delivery_boy_user_id")->num_rows();


                $this->data['orders'] = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,us.first_name as delivery_boy_name,us.id as delivery_boy_id,vl.name as vandor_name,dm.name as delivery_mode_name,ep.txn_id as payment_txn_id,ep.payment_method_id,vl.id as vandorpreid FROM ecom_orders as eo 
                left join users_address as ua on ua.id =eo.shipping_address_id 
                left join users as u on u.id = eo.created_user_id 
                left join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id 
                left join delivery_modes as dm on dm.id = eo.delivery_mode_id 
                left join ecom_payments as ep on ep.id = eo.payment_id  
                left join delivery_jobs as dj on dj.ecom_order_id = eo.id  
                left join users as us on us.id = dj.delivery_boy_user_id 
                ORDER BY eo.id DESC")->result_array();
            }

            $this->data['sts'] = $this->db->query("SELECT * FROM ecom_order_statuses")->result_array();
            $this->data['vendors'] = $this->db->query("SELECT * FROM vendors_list ORDER BY name asc")->result_array();
            $this->data['customers'] = $this->db->query("SELECT * FROM users ORDER BY first_name asc")->result_array();
            $this->data['payment_modes'] = $this->db->query("SELECT * FROM payment_methods")->result_array();
            $this->data['delivery_boy_names'] = $this->db->query("SELECT * FROM delivery_jobs as dj join users as u on  u.id=dj.delivery_boy_user_id group by dj.delivery_boy_user_id ")->result_array();

            // $config['full_tag_open'] = "<ul class='pagination'>";
            // $config['full_tag_close'] = "</ul>";
            // $config['num_tag_open'] = '<li class="page-item">';
            // $config['num_tag_close'] = '</li>';
            // $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
            // $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
            // $config['next_tag_open'] = '<li class="page-item">';
            // $config['next_tagl_close'] = "</li>";
            // $config['prev_tag_open'] = '<li class="page-item">';
            // $config['prev_tagl_close'] = "</li>";
            // $config['first_tag_open'] = '<li class="page-item">';
            // $config['first_tagl_close'] = "</li>";
            // $config['last_tag_open'] = '<li class="page-item">';
            // $config['last_tagl_close'] = "</li>";
            // $config['base_url'] = base_url() . 'food/food/food_orders/r';
            // $config['use_page_numbers'] = TRUE;
            // $config['total_rows'] = $allcount;
            // $config['per_page'] = $rowperpage;

            // Initialize
            // $this->pagination->initialize($config);

            // $this->data['pagination'] = $this->pagination->create_links();
            // $this->data['row'] = $rowno;
            // $this->data['q'] = $search_text;
            // $this->data['noofrows'] = $rowperpage;
            // echo "<pre>";
            // print_r($this->data); exit;
            echo $this->_render_page($this->template, $this->data);
        }
        if ($type == 'today') {

            $this->data['title'] = 'Orders';
            $this->data['content'] = 'food/food/orders';
            $this->data['nav_type'] = 'food_order';

            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }

            if ($this->input->post('vname') != NULL) {
                $search_text = $this->input->post('vname');
                $allcount = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,vl.name as vandor_name,dm.name as delivery_mode_name FROM ecom_orders as eo join users_address as ua on ua.id =eo.shipping_address_id join users as u on u.id = eo.created_user_id join vendors_list as vl on vl.id = eo.vendor_user_id join delivery_modes as dm on dm.id = eo.delivery_mode_id where vl.name like '%$search_text%' and date(eo.created_at)= CURDATE()
")->num_rows();

                $this->data['orders'] = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,vl.name as vandor_name,dm.name as delivery_mode_name,ep.txn_id as payment_txn_id,ep.payment_method_id,vl.id as vandorpreid FROM ecom_orders as eo 
        join users_address as ua on ua.id =eo.shipping_address_id 
        join users as u on u.id = eo.created_user_id 
        join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id 
        join delivery_modes as dm on dm.id = eo.delivery_mode_id 
        join ecom_payments as ep on ep.id = eo.payment_id  
        where vl.name like '%$search_text%' and date(eo.created_at)= CURDATE()
ORDER BY eo.id DESC LIMIT " . $rowno . ',' . $rowperpage)->result_array();
            }

            if ($this->input->post('cname') != NULL) {
                $search_text = $this->input->post('cname');
                $allcount = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,vl.name as vandor_name,dm.name as delivery_mode_name FROM ecom_orders as eo join users_address as ua on ua.id =eo.shipping_address_id join users as u on u.id = eo.created_user_id join vendors_list as vl on vl.id = eo.vendor_user_id join delivery_modes as dm on dm.id = eo.delivery_mode_id where u.first_name like '%$search_text%' and date(eo.created_at)= CURDATE()
")->num_rows();

                $this->data['orders'] = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,vl.name as vandor_name,dm.name as delivery_mode_name,ep.txn_id as payment_txn_id,ep.payment_method_id,vl.id as vandorpreid FROM ecom_orders as eo 
        join users_address as ua on ua.id =eo.shipping_address_id 
        join users as u on u.id = eo.created_user_id 
        join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id 
        join delivery_modes as dm on dm.id = eo.delivery_mode_id 
        join ecom_payments as ep on ep.id = eo.payment_id  
        where u.first_name like '%$search_text%' and date(eo.created_at)= CURDATE()
        ORDER BY eo.id DESC LIMIT " . $rowno . ',' . $rowperpage)->result_array();
            }

            if ($this->input->post('tid') != NULL) {

                $search_text = $this->input->post('tid');

                $allcount = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,vl.name as vandor_name,dm.name as delivery_mode_name FROM ecom_orders as eo join users_address as ua on ua.id =eo.shipping_address_id join users as u on u.id = eo.created_user_id join vendors_list as vl on vl.id = eo.vendor_user_id join delivery_modes as dm on dm.id = eo.delivery_mode_id where eo.track_id = '$search_text' and date(eo.created_at)= CURDATE()
")->num_rows();

                $this->data['orders'] = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,vl.name as vandor_name,dm.name as delivery_mode_name,ep.txn_id as payment_txn_id,vl.id as vandorpreid FROM ecom_orders as eo 
        join users_address as ua on ua.id =eo.shipping_address_id 
        join users as u on u.id = eo.created_user_id 
        join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id 
        join delivery_modes as dm on dm.id = eo.delivery_mode_id 
        join ecom_payments as ep on ep.id = eo.payment_id  
        where eo.track_id = '$search_text' and date(eo.created_at)= CURDATE()
        ORDER BY eo.id DESC LIMIT " . $rowno . ',' . $rowperpage)->result_array();
            }

            if ($this->input->post('statusname') != NULL) {
                $search_text = $this->input->post('statusname');
                $allcount = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,vl.name as vandor_name,dm.name as delivery_mode_name FROM ecom_orders as eo join users_address as ua on ua.id =eo.shipping_address_id join users as u on u.id = eo.created_user_id join vendors_list as vl on vl.id = eo.vendor_user_id join delivery_modes as dm on dm.id = eo.delivery_mode_id where eo.order_status_id = '$search_text' and date(eo.created_at)= CURDATE()
")->num_rows();

                $this->data['orders'] = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,vl.name as vandor_name,dm.name as delivery_mode_name,ep.txn_id as payment_txn_id,ep.payment_method_id,vl.id as vandorpreid FROM ecom_orders as eo 
        join users_address as ua on ua.id =eo.shipping_address_id 
        join users as u on u.id = eo.created_user_id 
        join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id 
        join delivery_modes as dm on dm.id = eo.delivery_mode_id 
        join ecom_payments as ep on ep.id = eo.payment_id  
        where eo.order_status_id = '$search_text' and date(eo.created_at)= CURDATE()
ORDER BY eo.id DESC LIMIT " . $rowno . ',' . $rowperpage)->result_array();
            }

            if ($this->input->post('submit') == NULL) {

                $allcount = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,vl.name as vandor_name,dm.name as delivery_mode_name FROM ecom_orders as eo join users_address as ua on ua.id =eo.shipping_address_id join users as u on u.id = eo.created_user_id join vendors_list as vl on vl.id = eo.vendor_user_id join delivery_modes as dm on dm.id = eo.delivery_mode_id and date(eo.created_at)= CURDATE()")->num_rows();

                $this->data['orders'] = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,vl.name as vandor_name,dm.name as delivery_mode_name,ep.txn_id as payment_txn_id,ep.payment_method_id,vl.id as vandorpreid FROM ecom_orders as eo 
        join users_address as ua on ua.id =eo.shipping_address_id 
        join users as u on u.id = eo.created_user_id 
        join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id 
        join delivery_modes as dm on dm.id = eo.delivery_mode_id 
        join ecom_payments as ep on ep.id = eo.payment_id  and date(eo.created_at)= CURDATE()
ORDER BY eo.id DESC LIMIT " . $rowno . ',' . $rowperpage)->result_array();
            }

            $this->data['sts'] = $this->db->query("SELECT * FROM ecom_order_statuses")->result_array();

            $config['full_tag_open'] = "<ul class='pagination'>";
            $config['full_tag_close'] = "</ul>";
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
            $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tagl_close'] = "</li>";
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tagl_close'] = "</li>";
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tagl_close'] = "</li>";
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tagl_close'] = "</li>";
            $config['base_url'] = base_url() . 'food/food/food_orders/r';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $rowperpage;

            // Initialize
            $this->pagination->initialize($config);

            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['noofrows'] = $rowperpage;
            // echo "<pre>";
            // print_r($this->data); exit;
            $this->_render_page($this->template, $this->data);
        }

        if ($type == 'edit') {

            $this->data['title'] = 'Orders';
            $this->data['content'] = 'food/food/order_product_details';
            $this->data['nav_type'] = 'food_order';
            $id = base64_decode(base64_decode($this->input->get('id')));

            $this->data['orderst'] = $this->db->query("SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,u.last_name,u.email,u.phone,vl.name as vandor_name,dm.name as delivery_mode_name,ep.payment_method_id,pm.name as payment_method_name FROM ecom_orders as eo 
        join users_address as ua on ua.id =eo.shipping_address_id 
        join users as u on u.id = eo.created_user_id 
        join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id 
        join ecom_payments as ep on ep.id = eo.payment_id
        join payment_methods as pm on pm.id = ep.payment_method_id 
        join delivery_modes as dm on dm.id = eo.delivery_mode_id 
        where eo.id = '$id'")->result_array();

            $this->data['custprod'] = $this->db->query("SELECT eod.* ,eo.track_id, fi.name as food_name,fi.desc,fii.id as image_id,fs.name as section_name FROM ecom_order_details as eod
        join food_item as fi on fi.id = eod.item_id
        join ecom_orders as eo on eo.id = eod.ecom_order_id
        join food_section as fs on fs.item_id  = fi.id
        left join food_item_images as fii  on fii.item_id =  fi.id 
        where ecom_order_id ='$id'")->result_array();

            $this->_render_page($this->template, $this->data);
        }
    }

    //vendor login ongoing orders
    public function ongoing_orders($type = 'r', $rowno = 0)
    {
        if ($type == 'r') {
            $this->data['title'] = 'Orders';
            $this->data['content'] = 'food/food/ongoing';
            $this->data['nav_type'] = 'food_order';
            $loguser_id = $this->ion_auth->get_user_id();

            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }

            $allcount_query = "SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,vl.name as vandor_name,dm.name as delivery_mode_name FROM ecom_orders as eo join users_address as ua on ua.id =eo.shipping_address_id join users as u on u.id = eo.created_user_id join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id join delivery_modes as dm on dm.id = eo.delivery_mode_id where eo.vendor_user_id=$loguser_id";

            $order_query = "SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,vl.name as vandor_name,dm.name as delivery_mode_name,ep.txn_id as payment_txn_id,ep.payment_method_id,vl.id as vandorpreid FROM ecom_orders as eo 
        join users_address as ua on ua.id =eo.shipping_address_id 
        join users as u on u.id = eo.created_user_id 
        join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id 
        join delivery_modes as dm on dm.id = eo.delivery_mode_id 
        join ecom_payments as ep on ep.id = eo.payment_id where eo.vendor_user_id=$loguser_id";

            if ($this->input->post('cname') != NULL) {
                $search_text = $this->input->post('cname');
                $allcount_query = $allcount_query . " and u.first_name like '%$search_text%' ";
                $order_query = $order_query . " and u.first_name like '%$search_text%'";
            }

            if ($this->input->post('tid') != NULL) {

                $search_text = $this->input->post('tid');
                $allcount_query = $allcount_query . " and eo.track_id = '$search_text' ";
                $order_query = $order_query . " and eo.track_id = '$search_text'";
            }



            if ($this->input->post('submit') == NULL) {

                $allcount = $this->db->query($allcount_query)->num_rows();
                $this->data['orders'] = $this->db->query($order_query . " ORDER BY eo.id DESC LIMIT " . $rowno . ',' . $rowperpage)->result_array();
            } else {
                $allcount = $this->db->query($allcount_query)->num_rows();
                $this->data['orders'] = $this->db->query($order_query . " ORDER BY eo.id DESC LIMIT " . $rowno . ',' . $rowperpage)->result_array();
            }

            $this->data['sts'] = $this->db->query("SELECT * FROM ecom_order_statuses")->result_array();

            $config['full_tag_open'] = "<ul class='pagination'>";
            $config['full_tag_close'] = "</ul>";
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
            $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tagl_close'] = "</li>";
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tagl_close'] = "</li>";
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tagl_close'] = "</li>";
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tagl_close'] = "</li>";
            $config['base_url'] = base_url() . 'food/food/food_orders/r';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $rowperpage;

            // Initialize
            $this->pagination->initialize($config);

            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['noofrows'] = $rowperpage;
            // echo "<pre>";
            // print_r($this->data); exit;
            $this->_render_page($this->template, $this->data);
        }
    }

    //vendor login Pending orders
    public function pending_orders($type = 'r', $rowno = 0)
    {
        if ($type == 'r') {
            $this->data['title'] = 'Orders';
            $this->data['content'] = 'food/food/pending';
            $this->data['nav_type'] = 'food_order';
            $loguser_id = $this->ion_auth->get_user_id();
            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }



            $allcount_query = "SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,vl.name as vandor_name,dm.name as delivery_mode_name FROM ecom_orders as eo join users_address as ua on ua.id =eo.shipping_address_id join users as u on u.id = eo.created_user_id join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id join delivery_modes as dm on dm.id = eo.delivery_mode_id where eo.vendor_user_id=$loguser_id";

            $order_query = "SELECT eo.*,eo.total as grand_total,ua.address,u.first_name,vl.name as vandor_name,dm.name as delivery_mode_name,ep.txn_id as payment_txn_id,ep.payment_method_id,vl.id as vandorpreid FROM ecom_orders as eo 
        join users_address as ua on ua.id =eo.shipping_address_id 
        join users as u on u.id = eo.created_user_id 
        join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id 
        join delivery_modes as dm on dm.id = eo.delivery_mode_id 
        join ecom_payments as ep on ep.id = eo.payment_id where eo.vendor_user_id=$loguser_id";

            if ($this->input->post('cname') != NULL) {
                $search_text = $this->input->post('cname');
                $allcount_query = $allcount_query . " and u.first_name like '%$search_text%' ";
                $order_query = $order_query . " and u.first_name like '%$search_text%'";
            }

            if ($this->input->post('tid') != NULL) {

                $search_text = $this->input->post('tid');
                $allcount_query = $allcount_query . " and eo.track_id = '$search_text' ";
                $order_query = $order_query . " and eo.track_id = '$search_text'";
            }



            if ($this->input->post('submit') == NULL) {

                $allcount = $this->db->query($allcount_query)->num_rows();
                $this->data['orders'] = $this->db->query($order_query . " ORDER BY eo.id DESC LIMIT " . $rowno . ',' . $rowperpage)->result_array();
            } else {
                $allcount = $this->db->query($allcount_query)->num_rows();
                $this->data['orders'] = $this->db->query($order_query . " ORDER BY eo.id DESC LIMIT " . $rowno . ',' . $rowperpage)->result_array();
            }

            $this->data['sts'] = $this->db->query("SELECT * FROM ecom_order_statuses")->result_array();

            $config['full_tag_open'] = "<ul class='pagination'>";
            $config['full_tag_close'] = "</ul>";
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
            $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tagl_close'] = "</li>";
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tagl_close'] = "</li>";
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tagl_close'] = "</li>";
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tagl_close'] = "</li>";
            $config['base_url'] = base_url() . 'food/food/food_orders/r';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $rowperpage;

            // Initialize
            $this->pagination->initialize($config);

            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['noofrows'] = $rowperpage;
            // echo "<pre>";
            // print_r($this->data); exit;
            $this->_render_page($this->template, $this->data);
        }
    }

    /**
     * To Manage Vendor Leads History
     *
     * @author Mahesh
     * @param string $type
     */
    public function VendorLeads($type = 'r', $lead_type = 'Received')
    {
        if ($type == 'r') {
            $this->data['title'] = 'Vendor Leads';
            $this->data['content'] = 'food/food/vendor_leads';
            $this->data['nav_type'] = 'vendor_leads';
            $c = 0;
            $s = $this->vendor_leads_model->where('vendor_id', $this->ion_auth->get_user_id())
                ->get_all();
            if ($s != '') {
                $c = count($s);
            }
            $this->data['leads_count'] = $c;
            $this->data['lead_type'] = $lead_type;
            if ($lead_type == 'Received') {
                $this->db->where('lead_status', 1);
            } elseif ($lead_type == 'Processing') {
                $this->db->where('lead_status', 2);
            } elseif ($lead_type == 'Completed') {
                $this->db->where('lead_status', 3);
            } elseif ($lead_type == 'Canceled') {
                $this->db->where('lead_status', 4);
            }

            $data = $this->vendor_leads_model->with_user('fields:first_name,unique_id,phone,email')
                ->with_vendor('fields:name')
                ->fields('id,user_id,vendor_id,created_at,lead_status')
                ->where('vendor_id', $this->ion_auth->get_user_id())
                ->order_by('id', 'DESC')
                ->get_all();
            $this->data['leads'] = $data;
            $this->_render_page($this->template, $this->data);
        }
    }

    public function vendor_lead_status($id, $status)
    {
        $res = $this->vendor_leads_model->update([
            'id' => $id,
            'lead_status' => $status
        ], 'id');
        redirect($this->session->userdata('last_page'));
    }

    /**
     * Food Settings
     *
     * To Manage Food Settings
     *
     * @author Mahesh
     * @param string $type
     * @param string $target
     */
    public function food_settings($type = 'r')
    {

        /*
         * if (! $this->ion_auth_acl->has_permission('food'))
         * redirect('admin');
         */
        if ($type == 'r') {
            $this->data['title'] = 'Section Item';
            $this->data['content'] = 'food/food/food_settings';
            $this->data['nav_type'] = 'food_settings';
            /*
             * $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc')->order_by('id', 'ASCE')->get_all();
             * $this->data['food_sec_items'] = $this->food_sec_item_model->with_menu('fields:name')->with_item('fields:name')->with_sec('fields:name')->order_by('id', 'ASCE')->get_all();
             */
            $this->data['food_settings'] = $this->food_settings_model->fields('id,min_order_price,delivery_free_range,preparation_time,min_delivery_fee,ext_delivery_fee,restaurant_status')
                ->where('vendor_id', $this->ion_auth->get_user_id())
                ->get();

            $this->load->model('vendor_bank_details_model');

            $this->data['bank_details'] = $this->vendor_bank_details_model->fields('id,bank_name,bank_branch,ifsc,ac_holder_name,ac_number')
                ->where('list_id', $this->ion_auth->get_user_id())
                ->get();

            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->food_settings_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $r = $this->food_settings_model->fields('id')
                    ->where('vendor_id', $this->ion_auth->get_user_id())
                    ->get();
                if (!empty($r)) {
                    $this->food_settings_model->update([
                        'vendor_id' => $this->ion_auth->get_user_id(),
                        'preparation_time' => $this->input->post('preparation_time'),
                        'restaurant_status' => 1
                    ], 'vendor_id');
                    redirect('food_settings/r', 'refresh');
                } else {
                    $this->food_settings_model->insert([
                        'preparation_time' => $this->input->post('preparation_time'),
                        'restaurant_status' => 1,
                        'vendor_id' => $this->ion_auth->get_user_id()
                    ]);
                    redirect('food_settings/r', 'refresh');
                }
            }
        }
    }

    public function get_orders_count($count)
    {
        $data = $this->food_orders_model->where('vendor_id', $this->ion_auth->get_user_id())
            ->get_all();
        $mes['status'] = 0;
        $mes['message'] = 'hi';
        if ($data != '') {
            if ($count < count($data)) {
                $c = count($data) - $count;
                $mes['message'] = '<a href="' . base_url('food_orders/r/') . '" class="btn btn-success">New Order Received <span class="badge badge-dark">' . $c . '</span></a>';
                $mes['status'] = 1;
            }
        }
        echo json_encode($mes);
    }

    public function reject_food_order()
    {
        $mes['status'] = 0;
        $res = $this->food_orders_model->update([
            'id' => $this->input->post('order_id'),
            'rejected_reason' => $this->input->post('reason'),
            'order_status' => 0
        ], 'id');
        if ($res) {
            $mes['status'] = 1;
        }
        echo json_encode($mes);
    }

    public function manual_assign_order()
    {
        $mes['status'] = 0;

        $ord_del_id = $this->food_order_deal_model->insert([
            'order_id' => $this->input->post('order_id'),
            'deal_id' => $this->input->post('del_id'),
            'ord_deal_status' => 2,
            'otp' => rand(1234, 9567)
        ]);

        if ($ord_del_id) {
            $r = $this->food_order_deal_model->update([
                'deleted_at' => date('Y-m-d H:i:s')
            ], [
                'id !=' => $ord_del_id,
                'order_id' => $this->input->post('order_id'),
                'ord_deal_status' => 1
            ]);
            $res = $this->food_orders_model->update([
                'id' => $this->input->post('order_id'),
                'order_status' => 4
            ], 'id');
        }

        if ($ord_del_id) {
            $mes['status'] = 1;
        }
        echo json_encode($mes);
    }

    public function food_out_for_delivery()
    {
        $order_id = $this->input->post('order_id');
        $otp = $this->input->post('otp');
        $mes['status'] = 0;
        /* $deal=$this->food_order_deal_model->where('id',$this->input->post('ord_deal_id'))->get(); */
        $order = $this->food_orders_model->where('id', $order_id)->get();
        if ($this->input->post('ord_type') == 'delivery') {
            if ($order['otp'] == $this->input->post('otp')) {
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
                if ($res) {
                    $mes['status'] = 1;
                }
            }
        }
        if ($this->input->post('ord_type') == 'courier') {
            $res = $this->food_orders_model->update([
                'id' => $order_id,
                'otp' => $otp,
                'order_status' => 6
            ], 'id');
            $this->user_model->update_walet($order['vendor_id'], $order['total'], 'Order: ' . $order['order_track']);
            if ($res) {
                $mes['status'] = 1;
            }
        }
        echo json_encode($mes);
    }

    public function food_order_status($id, $status, $r_t = '')
    {
        $res = $this->food_orders_model->update([
            'id' => $id,
            'order_status' => $status
        ], 'id');

        if ($status == 6) {
            $order = $this->food_orders_model->where('id', $id)->get();
            $this->user_model->update_walet($order['vendor_id'], $order['total'], 'Order: ' . $order['order_track']);
        }

        if ($res && ($status == 2 || ($status == 3 && $r_t == 1))) {
            $order = $this->food_orders_model->where('id', $id)->get();
            if ($order['delivery'] == 1) {
                /*
                 * $lat = '17.4468978';
                 * $lng = '78.3788169';
                 */
                /* $ip = $_SERVER['REMOTE_ADDR']; */
                /* $ip='223.230.124.22'; */
                /* $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json")); */
                /* $d=explode(',',$details->loc); */
                $l = $this->vendor_list_model->with_location('fields: id, address, latitude, longitude')
                    ->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $lat = $l['location']['latitude'];
                $lng = $l['location']['longitude'];
                $distance = 10; // Kilometers
                // echo $lat.'/'.$lng;
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
                /*
                 * $setlat = 13.5234412;
                 * $setlong = 144.8320897;
                 */
                /*
                 * $awaka = "SELECT 'location_id',
                 * ( 3959 * acos( cos( radians(?) ) * cos( radians('location_latitude') ) * cos( radians(?) - radians('location_longitude') )
                 * + sin( radians(?) ) * sin( radians('location_latitude') ) ) ) AS 'distance'
                 * FROM 'locations' HAVING 'distance < 10'";
                 * $result = $this->db->query($awaka, array($lat, $lng, $lat));
                 */

                // echo $this->db->last_query();
                // print_r($_POST);die;
                /* $deal=$this->user_model->fields('id')->get_all(); */
                for ($i = 0; $i < count($deal); $i++) {
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
        }
        // redirect($this->session->userdata('last_page'));
        redirect(base_url('/food_orders/r'));
    }

    public function get_orders_list($order_type)
    {
        if (!$this->ion_auth_acl->has_permission('food'))
            redirect('admin');

        if ($order_type == 'past') {
            $this->db->where('order_status', 6);
        } elseif ($order_type == 'upcomping') {
            $this->db->where('order_status !=', 0);
            $this->db->where('order_status !=', 6);
            $this->db->where('order_status !=', 7);
        } elseif ($order_type == 'cancelled') {
            $this->db->where('order_status', 0);
        }

        $data = $this->food_orders_model->with_order_items('fields:item_id,price,quantity')
            ->with_sub_order_items('fields:sec_item_id,price,quantity')
            ->fields('id,discount,delivery_fee,tax,total,deal_id,order_track,order_status')
            ->where('user_id', $this->ion_auth->get_user_id())
            ->order_by('id', 'DESC')
            ->get_all();
        if (!empty($data)) {
            $status = [
                '0' => 'Rejected',
                '1' => 'Order Received',
                '2' => 'Accepted',
                '3' => 'Preparing',
                '4' => 'Out for delivery',
                '5' => 'Order on the way',
                '6' => 'Order Completed',
                '7' => 'Cancelled'
            ];
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['order_status'] = $status[$data[$i]['order_status']];
            }
        }
        // print_r($data);die;
        echo json_encode($data);
    }

    public function get_sub_item_list($item_id)
    {
        $sub_items = $this->food_item_model->order_by('id', 'DESC')
            ->where('menu_id', $item_id)
            ->get_all();
        echo '<option value="" selected disabled>--select--</option>';
        foreach ($sub_items as $item) {
            echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
        }
    }

    public function get_food_menus($vendor_id)
    {
        $sub_items = $this->food_menu_model->order_by('id', 'DESC')
            ->where('vendor_id', $vendor_id)
            ->get_all();
        echo '<option value="" selected disabled>--select--</option>';
        foreach ($sub_items as $item) {
            echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
        }
    }

    public function get_food_sections_list($item_id)
    {
        $sub_items = $this->food_section_model->order_by('id', 'DESC')
            ->where('item_id', $item_id)
            ->get_all();
        echo '<option value="" selected disabled>--select--</option>';
        foreach ($sub_items as $item) {
            echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
        }
    }

    public function view_order()
    {
        $order_id = base64_decode(base64_decode($_GET['order_id']));
        $this->data['title'] = 'Order';
        $this->data['content'] = 'food/food/view_order';
        $this->data['nav_type'] = 'food_order';
        $data = $this->food_orders_model->with_user('fields:first_name,email,phone')
            ->with_vendor('fields:name,address')
            ->with_order_items('fields:item_id,order_id,price,quantity')
            ->with_sub_order_items('fields:sec_item_id,order_id,item_id,price,quantity')
            ->fields('id,discount,delivery_fee,payment_method_id,created_at,tax,total,deal_id,order_track,order_status,delivery,rejected_reason,otp,instructions,promo_code,promo_id,used_walet,used_walet_amount')
            ->where('id', $order_id)
            ->get();
        if (!empty($data)) {
            $cat_id = $this->vendor_list_model->where('vendor_user_id', $data['vendor_id'])->get();
            $vendor_category_id = $cat_id['category_id'];
            $status = [
                '0' => (($this->ion_auth->is_admin()) ? 'Order Rejected' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_rejected')),
                '1' => (($this->ion_auth->is_admin()) ? 'Order Received' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_received')),
                '2' => (($this->ion_auth->is_admin()) ? 'Order Accept' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_accepted')),
                '3' => (($this->ion_auth->is_admin()) ? 'Order Preparing' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_preparing')),
                '4' => (($this->ion_auth->is_admin()) ? 'Out for delivery' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_out_delivery')),
                '5' => (($this->ion_auth->is_admin()) ? 'Order on the way' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_on_way')),
                '6' => (($this->ion_auth->is_admin()) ? 'Order Completed' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_completed')),
                '7' => (($this->ion_auth->is_admin()) ? 'Order Cancelled' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_canceled'))
            ];

            $data['order_stat'] = $status[$data['order_status']];
            $deal = $this->food_order_deal_model->with_deal_boy('fields:first_name')
                ->fields('id,deal_id,otp')
                ->where('ord_deal_status', 2)
                ->where('order_id', $data['id'])
                ->get();
            $this->load->model('order_rating_model');
            $ord_r = $this->order_rating_model->where('order_id', $data['id'])->get();
            $data['ord_rating'] = 0;
            if ($ord_r != '') {
                $data['ord_rating'] = 1;
                $data['rating'] = $ord_r['rating'];
                $data['review'] = $ord_r['review'];
                $data['del_rating'] = $ord_r['del_rating'];
                $data['del_review'] = $ord_r['del_review'];
            }
            /* $data[$i]['deal_id'] = $deal['deal_id']; */
            $data['ord_deal_id'] = $deal['id'];
            /* $data[$i]['otp'] = $deal['otp']; */
            $data['deal_name'] = $deal['deal_boy']['first_name'];
        }
        /*
         * echo "<pre>";
         * print_r($data['order_stat']);die;
         */
        $this->data['order'] = $data;
        $this->_render_page($this->template, $this->data);
    }

    public function order_support($type = 'r')
    {
        if ($type == 'r') {
            $this->data['title'] = 'Order Support';
            $this->data['content'] = 'food/food/order_support';
            $this->data['nav_type'] = 'order_support';
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'ul') {
            $mer = array();
            $support = $this->order_support_model->get_support_chat($this->ion_auth->get_user_id())
                ->result_array();

            $from_u = array_column($support, 'from_id');
            $to_u = array_column($support, 'to_id');
            $mer = array_unique(array_merge($from_u, $to_u));
            $mer = array_keys(array_count_values($mer));

            $j = 1;
            for ($i = 0; $i < count($mer); $i++) {
                if ($mer[$i] != $this->ion_auth->get_user_id()) {
                    $c = '';
                    $c = $this->order_support_model->get_support_chat_unread_c($mer[$i]);
                    echo '<li class="" onclick="user_chat_support(' . $mer[$i] . ')" id="active_chat_user' . $mer[$i] . '">
                            <div class="d-flex bd-highlight">
                                <div class="img_cont">
                                    <img src="' . base_url($this->order_support_model->get_image_url($mer[$i])) . '" class="rounded-circle user_img">' . (($c != 0) ? '<span class="online_icon badge badge-dark">' . $c . '</span>' : "") . '
                                    
                                </div>
                                <div class="user_info">
                                    <span>' . ucwords($this->order_support_model->get_type_name_by_where('users', 'id', $mer[$i], 'first_name')) . '</span>
                                    <p>' . ucwords($this->order_support_model->get_type_name_by_where('users', 'id', $mer[$i], 'unique_id')) . '</p>
                                </div>
                            </div>
                </li>';
                    $j++;
                }
            }
        }
    }

    public function get_support_chat()
    {
        $mer = array();

        // $support=$this->order_support_model->get_support_chat()->result_array();
        /* $support=$this->order_support_model->with_order('fields:order_track')->with_from('fields:first_name,unique_id')->with_to('fields:first_name,unique_id')->where('row_status',1)->order_by('id', 'DESC')->get_all(); */
        $support = $this->order_support_model->fields('id,order_id')
            ->where('row_status', 1)
            ->order_by('id', 'DESC')
            ->get_all();

        /*
         * echo "<pre>";
         * print_r($support);die;
         */
        // $from_u=array_column($support, 'from_id');
        // $to_u=array_column($support, 'to_id');
        $order = array_column($support, 'order_id');
        $mer = array_unique($order);
        $mer = array_keys(array_count_values($mer));
        $j = 1;
        for ($i = 0; $i < count($mer); $i++) {
            $c = '';
            $c = $this->order_support_model->get_support_chat_unread_c($mer[$i], $this->ion_auth->get_user_id());
            echo '<li class="" onclick="user_chat_support(' . $mer[$i] . ')" id="active_chat_user' . $mer[$i] . '">
                            <div class="d-flex bd-highlight">
                                <div class="img_cont">
                                    <img src="' . base_url($this->order_support_model->get_image_url($mer[$i])) . '" class="rounded-circle user_img">' . (($c != 0) ? '<span class="online_icon badge badge-dark"><span class="mynum">' . $c . '</span></span>' : "") . '
                                </div>
                                <div class="user_info">
                                    <span>' . ucwords($this->order_support_model->get_type_name_by_where('food_orders', 'id', $mer[$i], 'order_track')) . '</span>
                                </div>
                            </div>
                </li>';
            $j++;
        }
    }

    public function get_support_chat_box($order_id)
    {
        /* $support=$this->order_support_model->get_support_chat_box($u_id,$this->ion_auth->get_user_id())->result_array(); */
        /* $support=$this->order_support_model->with_order('fields:order_track')->with_from('fields:first_name,unique_id')->with_to('fields:first_name,unique_id')->where('order_id',$u_id)->get_all(); */
        $support = $this->food_orders_model->fields('id,order_track')
            ->where('id', $order_id)
            ->get();
        $data = '<div class="card">
                        <div class="card-header msg_head">
                            <div class="d-flex bd-highlight">
                                <div class="user_info">
                                    <span class="mtext">' . $support['order_track'] . '</span>
                                </div>
                                <div class="video_cam">
                                    <a href="' . base_url('view_order') . '?order_id=' . base64_encode(base64_encode($order_id)) . '" target="_blank"><span><i class="fas fa-eye" style="font-size: 30px;color: #fff;"></i></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body msg_card_body" id="chat_body_content' . $order_id . '">
                            

                        </div>
                        <div class="card-footer">
                            <div class="input-group">
                                
                                <textarea name="" class="form-control type_msg" placeholder="Type your message..." id="my_chat_sms"></textarea>
                                <div class="input-group-append">
                                    <span class="input-group-text send_btn" onclick="send_chat_sms();"><i class="fas fa-location-arrow"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>';
        echo $data;
    }

    public function chat_body_content($order_id)
    {
        /* $support=$this->order_support_model->get_support_chat_box($u_id,$this->ion_auth->get_user_id())->result_array(); */
        $support = $this->order_support_model->where('order_id', $order_id)
            ->order_by('id', 'DESC')
            ->get_all();

        foreach ($support as $su) {
            if ($su['from_id'] == $this->ion_auth->get_user_id()) {
                echo '<div class="d-flex justify-content-end mb-4">
                                <div class="msg_cotainer_send">' . $su['message'] . '<span class="msg_time_send"><br/>' . date('h:i A, d-D-Y', strtotime($su['created_at'])) . '</span>
                                </div>
                                <div class="img_cont_msg">
                        <img src="' . base_url($this->order_support_model->get_image_url($su['from_id'])) . '" class="rounded-circle user_img_msg">
                                </div>
                            </div>';
            } else {
                echo '<div class="d-flex justify-content-start mb-4">
                                <div class="img_cont_msg">
                                    <img src="' . base_url($this->order_support_model->get_image_url($su['to_id'])) . '" class="rounded-circle user_img_msg">
                                </div>
                                <div class="msg_cotainer">' . $su['message'] . '<span class="msg_time">' . date('h:i A, d-D-Y', strtotime($su['created_at'])) . '</span>
                                </div>
                            </div>';
            }
        }
    }

    public function send_chat_sms()
    {
        $input = $this->input->post();
        $support = $this->food_orders_model->fields('id,user_id')
            ->where('id', $input['to_id'])
            ->get();
        $arr = array(
            'message' => $input['message'],
            'from_id' => $this->ion_auth->get_user_id(),
            'to_id' => $support['user_id'],
            'order_id' => $input['to_id']
        );
        $res = $this->db->insert('order_support', $arr);

        if ($res) {
            return $this->db->insert_id();
        } else {
            return false;
        }
        // $this->order_support_model->saving_insert_details('support',$arr);
    }

    public function update_sms_read_tick($order_id)
    {
        $arr = array(
            'read_status' => 1
        );
        $where = array(
            'to_id' => $this->ion_auth->get_user_id(),
            'order_id' => $order_id
        );
        return $this->db->where($where)->update('order_support', $arr);
        // $this->order_support_model->update_operation($arr,'support',$where);
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
            $sounds_like .= metaphone($name) . ' ';
        }
        return $sounds_like;
    }


    /*Show only approved products for vendor login*/
    public function approved($type = 'r', $rowno = 0)
    {
        if ($type == 'r') {
            $this->data['title'] = 'Food Products Approved List';
            $this->data['content'] = 'food/food/approved';
            $this->data['nav_type'] = 'approved';
            $search_text = "";
            $noofrows = 10;
            if ($this->input->post('submit') != NULL) {
                $search_text = $this->input->post('q');
                $sub_cat_id = $this->input->post('sub_cat_id');
                $availability = $this->input->post('statusdata');
                $menu_id = $this->input->post('menu_id');
                $noofrows = $this->input->post('noofrows');
                $this->session->set_userdata(array(
                    "q" => $search_text,
                    'noofrows' => $noofrows,
                    'availability' => $availability,
                    'menu_id' => $menu_id,
                    'sub_cat_id' => $sub_cat_id
                ));
            }

            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }

            $count_query = "SELECT * FROM `food_item` where created_user_id=" . $this->ion_auth->get_user_id() . " and status=2";
            if ($search_text != '') {
                $count_query = $count_query . " and name like '%" . $search_text . "%'";
            }
            if ($sub_cat_id != '') {
                $count_query = $count_query . " and sub_cat_id=" . $sub_cat_id;
            }
            $this->data['results'] = $this->db->query($count_query)->result_array();

            $all_count = $this->db->query($count_query)->num_rows();


            if (!empty($this->data['results'])) {
                foreach ($this->data['results'] as $key => $data) {

                    $this->data['results'][$key]['vendor'] = $this->vendor_list_model->fields('id, vendor_user_id, unique_id,business_name')->where('vendor_user_id', $this->ion_auth->get_user_id())->get();

                    $this->data['results'][$key]['sub_category'] = $this->sub_category_model->fields('id, name, desc')->where('id', $data['sub_cat_id'])->get();

                    $this->data['results'][$key]['menu'] = $this->food_menu_model->fields('id, name, desc')->where('id', $data['menu_id'])->get();
                    $this->data['results'][$key]['brand'] = $this->brand_model->fields('id, name, desc')->where('id', $data['brand_id'])->get();
                    $this->data['results'][$key]['vendor_product_variants'] = $this->vendor_product_variant_model->fields('id,status')->where('item_id', $datas['id'])->where('vendor_user_id', $data['id'])->get();

                    $this->image = $this->db->query("SELECT * FROM `food_item_images` where item_id=" . $data['id'])->result_array();
                    foreach ($this->image as $keys => $img) {

                        $this->data['results'][$key]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                    }
                }
            }
            $config['full_tag_open'] = "<ul class='pagination'>";
            $config['full_tag_close'] = "</ul>";
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
            $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tagl_close'] = "</li>";
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tagl_close'] = "</li>";
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tagl_close'] = "</li>";
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tagl_close'] = "</li>";
            $config['base_url'] = base_url() . 'food/food/approved/r';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $all_count;
            $config['per_page'] = $rowperpage;
            $this->pagination->initialize($config);
            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['products'] = $data[0];
            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['menu_id'] = $menu_id;
            $this->data['sub_cat_id'] = $sub_cat_id;
            $this->data['stock_type'] = $stock_type;
            $this->data['noofrows'] = $rowperpage;
            $this->data['brands'] = $this->brand_model->fields('id,name,desc')->get_all();
            if (empty($cat_id))
                $where = ['type' => 2];
            else
                $where = ['cat_id' => $cat_id, 'type' => 2];


            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])->get_all();

                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])
                    ->get_all();
            }
            //print_array($this->data);
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'view_details') {
            $this->data['title'] = 'Inventory details';
            $this->data['content'] = 'food/food/vendor_product_details';
            $this->data['nav_type'] = 'inventory';
            $item_id = base64_decode(base64_decode($this->input->get('id')));
            $vendor_user_id = base64_decode(base64_decode($this->input->get('vendor_user_id')));
            $this->data['product_details'] = $this->food_item_model
                ->with_menu('fields: id, name, vendor_id')
                ->with_sub_category('fields: id, name')
                ->with_brands('fields: id, name')
                ->with_created_by('fields: id, first_name, last_name, unique_id')
                ->with_item_images('fields: id, item_id, serial_number, ext')
                ->with_section_items('fields: id, section_item_code, name, desc, price, weight, status, created_at, updated_at')
                ->with_vendor_product_varinats('fields: id, item_id, section_id, section_item_id, sku, price, stock, discount, tax_id, list_id, vendor_user_id, status, created_at, updated_at', 'where: vendor_user_id=' . $vendor_user_id)
                ->where('id', $item_id)->get();
            if (!empty($this->data['product_details']['item_images'])) {
                foreach ($this->data['product_details']['item_images'] as $k => $img) {
                    $this->data['product_details']['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                }
            } else {
                $this->data['product_details']['item_images']  = NULL;
            }

            if (!empty($this->data['product_details']['vendor_product_varinats'])) {
                foreach ($this->data['product_details']['vendor_product_varinats'] as $key => $val) {
                    $this->data['product_details']['vendor_product_varinats'][$key]['section_item'] = $this->food_sec_item_model->fields('id, section_item_code, name, desc, price, weight, status, created_at, updated_at')->where('id', $val['vendor_product_varinats']['section_item_id'])->get();
                    $this->data['product_details']['vendor_product_varinats'][$key]['tax'] = $this->tax_model->fields('tax')->where('id', $val['tax_id'])->get();
                }
            } else {
                $this->data['product_details']['vendor_product_varinats'] = NULL;
            }
            $this->data['user_details'] = $this->vendor_list_model->fields('id, unique_id,business_name')->where('vendor_user_id', $vendor_user_id)->get();
            //print_array($this->data['product_details']);
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'change_status') {
            $vendor_id = $this->input->post('vendor_id');
            $item_id = $this->input->post('item_id');
            $status = ($this->input->post('is_checked') == 'true') ? 1 : 2;

            if ($status == 1) {
                $query = $this->db->query("update vendor_product_variants SET status='1' where item_id='" . $item_id . "' and vendor_user_id='" . $vendor_id . "'");
            } else {
                $query = $this->db->query("update vendor_product_variants SET status='2' where item_id='" . $item_id . "' and vendor_user_id='" . $vendor_id . "'");
            }
        }
    }
    /*Show only pending products for vendor login*/
    public function pendingproducts($type = 'r', $rowno = 0)
    {
        if ($type == 'r') {
            $this->data['title'] = 'food Products pendingproducts list';
            $this->data['content'] = 'food/food/pendingproducts';
            $this->data['nav_type'] = 'pendingproducts';
            $search_text = "";
            $noofrows = 10;
            if ($this->input->post('submit') != NULL) {
                $search_text = $this->input->post('q');
                $sub_cat_id = $this->input->post('sub_cat_id');
                $availability = $this->input->post('statusdata');
                $menu_id = $this->input->post('menu_id');
                $noofrows = $this->input->post('noofrows');
                $this->session->set_userdata(array(
                    "q" => $search_text,
                    'noofrows' => $noofrows,
                    'availability' => $availability,
                    'menu_id' => $menu_id,
                    'sub_cat_id' => $sub_cat_id
                ));
            }

            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }

            $count_query = "SELECT * FROM `food_item` where created_user_id=" . $this->ion_auth->get_user_id() . " and status=3";
            if ($search_text != '') {
                $count_query = $count_query . " and name like '%" . $search_text . "%'";
            }
            if ($sub_cat_id != '') {
                $count_query = $count_query . " and sub_cat_id=" . $sub_cat_id;
            }
            $this->data['results'] = $this->db->query($count_query)->result_array();

            $all_count = $this->db->query($count_query)->num_rows();


            if (!empty($this->data['results'])) {
                foreach ($this->data['results'] as $key => $data) {

                    $this->data['results'][$key]['vendor'] = $this->vendor_list_model->fields('id, vendor_user_id, unique_id,business_name')->where('vendor_user_id', $this->ion_auth->get_user_id())->get();

                    $this->data['results'][$key]['sub_category'] = $this->sub_category_model->fields('id, name, desc')->where('id', $data['sub_cat_id'])->get();

                    $this->data['results'][$key]['menu'] = $this->food_menu_model->fields('id, name, desc')->where('id', $data['menu_id'])->get();
                    $this->data['results'][$key]['brand'] = $this->brand_model->fields('id, name, desc')->where('id', $data['brand_id'])->get();
                    $this->data['results'][$key]['vendor_product_variants'] = $this->vendor_product_variant_model->fields('id,status')->where('item_id', $datas['id'])->where('vendor_user_id', $data['id'])->get();

                    $this->image = $this->db->query("SELECT * FROM `food_item_images` where item_id=" . $data['id'])->result_array();
                    foreach ($this->image as $keys => $img) {

                        $this->data['results'][$key]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                    }
                }
            }
            $config['full_tag_open'] = "<ul class='pagination'>";
            $config['full_tag_close'] = "</ul>";
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
            $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tagl_close'] = "</li>";
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tagl_close'] = "</li>";
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tagl_close'] = "</li>";
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tagl_close'] = "</li>";
            $config['base_url'] = base_url() . 'food/food/pendingproducts/r';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $all_count;
            $config['per_page'] = $rowperpage;
            $this->pagination->initialize($config);
            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['products'] = $data[0];
            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['menu_id'] = $menu_id;
            $this->data['sub_cat_id'] = $sub_cat_id;
            $this->data['stock_type'] = $stock_type;
            $this->data['noofrows'] = $rowperpage;
            $this->data['brands'] = $this->brand_model->fields('id,name,desc')->get_all();
            if (empty($cat_id))
                $where = ['type' => 2];
            else
                $where = ['cat_id' => $cat_id, 'type' => 2];


            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])->get_all();

                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])
                    ->get_all();
            }
            //print_array($this->data);
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'view_details') {
            $this->data['title'] = 'Inventory details';
            $this->data['content'] = 'food/food/vendor_product_details';
            $this->data['nav_type'] = 'inventory';
            $item_id = base64_decode(base64_decode($this->input->get('id')));
            $vendor_user_id = base64_decode(base64_decode($this->input->get('vendor_user_id')));
            $this->data['product_details'] = $this->food_item_model
                ->with_menu('fields: id, name, vendor_id')
                ->with_sub_category('fields: id, name')
                ->with_brands('fields: id, name')
                ->with_created_by('fields: id, first_name, last_name, unique_id')
                ->with_item_images('fields: id, item_id, serial_number, ext')
                ->with_section_items('fields: id, section_item_code, name, desc, price, weight, status, created_at, updated_at')
                ->with_vendor_product_varinats('fields: id, item_id, section_id, section_item_id, sku, price, stock, discount, tax_id, list_id, vendor_user_id, status, created_at, updated_at', 'where: vendor_user_id=' . $vendor_user_id)
                ->where('id', $item_id)->get();
            if (!empty($this->data['product_details']['item_images'])) {
                foreach ($this->data['product_details']['item_images'] as $k => $img) {
                    $this->data['product_details']['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                }
            } else {
                $this->data['product_details']['item_images']  = NULL;
            }

            if (!empty($this->data['product_details']['vendor_product_varinats'])) {
                foreach ($this->data['product_details']['vendor_product_varinats'] as $key => $val) {
                    $this->data['product_details']['vendor_product_varinats'][$key]['section_item'] = $this->food_sec_item_model->fields('id, section_item_code, name, desc, price, weight, status, created_at, updated_at')->where('id', $val['vendor_product_varinats']['section_item_id'])->get();
                    $this->data['product_details']['vendor_product_varinats'][$key]['tax'] = $this->tax_model->fields('tax')->where('id', $val['tax_id'])->get();
                }
            } else {
                $this->data['product_details']['vendor_product_varinats'] = NULL;
            }
            $this->data['user_details'] = $this->vendor_list_model->fields('id, unique_id,business_name')->where('vendor_user_id', $vendor_user_id)->get();
            //print_array($this->data['product_details']);
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'change_status') {
            $vendor_id = $this->input->post('vendor_id');
            $item_id = $this->input->post('item_id');
            $status = ($this->input->post('is_checked') == 'true') ? 1 : 2;

            if ($status == 1) {
                $query = $this->db->query("update vendor_product_variants SET status='1' where item_id='" . $item_id . "' and vendor_user_id='" . $vendor_id . "'");
            } else {
                $query = $this->db->query("update vendor_product_variants SET status='2' where item_id='" . $item_id . "' and vendor_user_id='" . $vendor_id . "'");
            }
        }
    }



    /**
     * Food Sub Item crud
     *
     * To Manage Food Sub Items
     *
     * @author Mahesh
     * @param string $type
     * @param string $target
     */
    public function vendor_req_product($rowno = 0, $type = 'r')
    {
        if ($type == 'r') {
            $this->data['title'] = 'food Products list';
            $this->data['content'] = 'food/food/vendor_req_product';
            $this->data['nav_type'] = 'inventory';
            if ($this->ion_auth->get_user_id() == 1) {
                $notification_update = "update notifications set status=2 where notified_user_id=1 and notification_type_id=28";
                $query_update = $this->db->query($notification_update);
            }
            $search_text = "";
            $noofrows = 10;
            if ($this->input->post('submit') != NULL) {
                $search_text = $this->input->post('q');
                $group = $this->input->post('sub_cat_id');
                $availability = $this->input->post('statusdata');
                $menu_id = $this->input->post('menu_id');
                // $noofrows = $this->input->post('menu_id');

                $noofrows = $this->input->post('noofrows');
                $this->session->set_userdata(array(
                    "q" => $search_text,
                    'noofrows' => $noofrows,
                    'availability' => $availability,
                    'menu_id' => $menu_id
                ));
            } else {
                if ($this->session->userdata('q') != NULL || $noofrows != NULL) {
                    $search_text = $this->session->userdata('q');
                    $noofrows = $this->session->userdata('noofrows');
                    $availability = $this->session->userdata('availability');
                    $group = $this->session->userdata('sub_cat_id');
                    $menu_id = $this->session->userdata('menu_id');
                }
            }

            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }

            // $rowno = ($this->uri->segment(3)) ? ($this->uri->segment(3) - 1) : 0;
            // if (! $this->ion_auth->in_group(1)) {
            if ($this->ion_auth->is_admin()) {
                $admin_ids = $this->get_users_by_group(1);
                array_push($admin_ids, $this->ion_auth->get_user_id());
                $deleted_items = $this->db->get_where('deleted_items', [
                    'vendor_id' => $this->ion_auth->get_user_id()
                ])
                    ->result_array();
                if ($deleted_items) {
                    $deleted_items = array_column($deleted_items, 'item_id');
                } else {
                    $deleted_items = [
                        0
                    ];
                }

                if ($search_text != null) {
                    $this->db->like('sounds_like', metaphone($search_text));
                    $this->db->where('sub_cat_id', $group);
                    $this->db->where('menu_id', $menu_id);
                    $this->db->or_where('product_code', $search_text);
                }
                $allcount = $this->food_item_model->where('created_user_id', $admin_ids)
                    ->where('id NOT', $deleted_items)
                    ->count_rows();
                if ($group != null) {
                    $this->db->where('food_item.menu_id', $menu_id);
                    $this->db->where('food_item.sub_cat_id', $group);
                }
                $this->db->where('status =', 2);
                $this->db->or_where('status =', 3);
                $this->db->like('food_item.sounds_like', metaphone($search_text));
                $this->db->or_where('food_item.product_code', $statusdata);

                $catalogue_products = $this->food_item_model->with_menu('fields: id, name, vendor_id')
                    ->with_sub_category('fields: id, name')
                    ->where('created_user_id', $admin_ids)
                    ->where('id NOT', $deleted_items)
                    ->order_by('id', 'DESC')
                    ->limit($rowperpage, $rowno)
                    ->get_all();
                //print_r($this->db->last_query());exit;
                $allcount = $this->food_item_model->count_rows();
            } else {
                $this->db->like('sounds_like', metaphone($search_text));
                if ($this->input->post('submit') != NULL) {
                    $this->db->like('availability', $availability);
                }
                if ($this->input->post('submit') != NULL) {
                    $this->db->like('sub_cat_id', $group);
                }
                if ($this->input->post('submit') != NULL) {
                    $this->db->like('menu_id', $menu_id);
                }

                $allcount = $this->food_item_model->where('created_user_id', $this->ion_auth->get_user_id())->count_rows();
                $this->db->like('food_item.sounds_like', metaphone($search_text));
                if ($this->input->post('submit') != NULL) {
                    $this->db->like('food_item.availability', $availability);
                }

                if ($this->input->post('submit') != NULL) {
                    $this->db->like('food_item.sub_cat_id', $group);
                }

                if ($this->input->post('submit') != NULL) {
                    $this->db->like('food_item.menu_id', $menu_id);
                }

                $catalogue_products = $this->food_item_model->with_menu('fields: id, name, vendor_id')
                    ->with_sub_category('fields: id, name')
                    ->with_brands('fields: id, name')
                    ->where('created_user_id', $this->ion_auth->get_user_id())
                    ->where('id NOT', $deleted_items)
                    ->order_by('id', 'DESC')
                    ->limit($rowperpage, $rowno)
                    ->get_all();
            }

            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])->get_all();

                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])
                    ->get_all();
            }

            $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->get_all();
            $this->data['brands'] = $this->brand_model->fields('id,name,desc')->get_all();


            if (!empty($catalogue_products)) {
                foreach ($catalogue_products as $key => $val) {
                    $catalogue_products[$key]['product_image'] = base_url() . 'uploads/food_item_image/food_item_' . $val['id'] . '.jpg';
                }
            }

            $config['full_tag_open'] = "<ul class='pagination'>";
            $config['full_tag_close'] = "</ul>";
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
            $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tagl_close'] = "</li>";
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tagl_close'] = "</li>";
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tagl_close'] = "</li>";
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tagl_close'] = "</li>";
            $config['base_url'] = base_url() . 'food/food/vendor_req_product';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $rowperpage;
            $this->pagination->initialize($config);
            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['products'] = $catalogue_products;
            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['noofrows'] = $rowperpage;
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'd') {
            $this->food_item_image_model->delete([
                'item_id' => $this->input->post('id')
            ]);
            $this->food_sec_item_model->delete([
                'item_id' => $this->input->post('id')
            ]);
            $this->food_section_model->delete([
                'item_id' => $this->input->post('id')
            ]);
            echo $this->food_item_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->session->set_flashdata('delete_status', 'Product has been deleted successfully');
            // redirect('food_product/0/r', 'refresh');
        } else if ($type == 'view') {

            $this->data['title'] = 'Edit Item';
            $this->data['content'] = 'food/food/vendor_food_product';
            $this->data['nav_type'] = 'food_item';
            $this->data['type'] = 'food_item';
            $id = base64_decode(base64_decode($this->input->get('id')));

            $this->data['vendourproduct'] = $this->db->query("SELECT fi.id, fi.product_code,fi.name as food_item_name ,fii.id as image_id , u.username as vendor_name,u.unique_id,fi.created_at,fi.updated_at,fi.availability,fi.status,fi.created_user_id,fi.updated_user_id,fm.name as menu_name , sc.name as sub_name FROM food_item as fi
            join food_section as fs on fs.item_id = fi.id 
            join food_sec_item as fsi on fsi.item_id = fi.id 
            join users as u on u.id = fi.created_user_id 
            join food_menu as fm  on fm.id = fi.menu_id 
            join sub_categories as sc  on sc.id = fi.sub_cat_id 
            left join food_item_images as fii  on fii.item_id =  fi.id 
            where fi.id = '$id'")->result_array();

            $user_id = $this->data['vendourproduct'][0]['created_user_id'];
            $this->data['userinfo'] = $this->db->query("SELECT * from users where id = '$user_id'")->result_array();

            $this->data['se_food_itm'] = $this->db->query("SELECT fsi.name as variant_name ,fsi.desc ,
            fsi.price,fsi.weight,fsi .created_at,fsi.updated_at, fs. name as section_name , u.first_name as vendor_name  FROM food_sec_item as fsi
            join food_section as fs on fs.id = fsi.sec_id 
            join users as u on u.id = fsi.created_user_id where fsi.item_id = '$id'")->result_array();

            $this->_render_page($this->template, $this->data);
        } else if ($type == 'changecat') {
            $id = $this->input->get('id');
            $is_updated = $this->food_item_model->update([
                'id' => $id,
                'status' => 1
            ], 'id');
            redirect('vendor_req_product/0/r', 'refresh');
        } else if ($type == 'foodapprovestatus') {
            $id = $this->input->get('id');

            $food_item_data = $this->food_item_model->where('id', $id)->get();
            $is_updated = $this->food_item_model->update([
                'id' => $id,
                'status' => 2
            ], 'id');

            $this->send_notification($food_item_data['created_user_id'], VENDOR_APP_CODE, $food_item_data['name'] . " product got approved", "Your Product code item " . $food_item_data['product_code'] . " has been approved By NEXCLICK ", ['order_id' => $id, 'notification_type' => $this->notification_type_model->where(['app_details_id' => VENDOR_APP_CODE, 'notification_code' => 'PROD'])->get()]);

            redirect('vendor_req_product/0/r', 'refresh');
        } else if ($type == 'foodpendingstatus') {
            $id = $this->input->get('id');
            $food_item_data = $this->food_item_model->where('id', $id)->get();

            $is_updated = $this->food_item_model->update([
                'id' => $id,
                'status' => 2
            ], 'id');

            $this->send_notification($food_item_data['created_user_id'], VENDOR_APP_CODE, $food_item_data['name'] . " product got rejected", "Your Product code item " . $food_item_data['product_code'] . " got rejected by NEXCLICK ", ['order_id' => $id, 'notification_type' => $this->notification_type_model->where(['app_details_id' => VENDOR_APP_CODE, 'notification_code' => 'PROD'])->get()]);
            redirect('vendor_req_product/0/r', 'refresh');
        } else if ($type == 'u') {
            $this->form_validation->set_rules($this->food_item_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {

                $section_id = $this->input->post('section_id');

                $sounds_like = $this->sounds_like($this->input->post('name'), $this->input->post('shop_by_cat_id'), $this->input->post('menu_id'));

                $sub_cat_id = $this->input->post('sub_cat_id');
                $menu_id = $this->input->post('menu_id');
                $item_id = $this->input->post('id');
                $section_id = $this->input->post('section_id');

                $brand_id = $this->input->post('brand_id');

                $is_updated = $this->food_item_model->update([
                    'id' => $item_id,
                    'sub_cat_id' => $sub_cat_id,
                    'menu_id' => $menu_id,
                    'brand_id' => $brand_id,
                    'item_type' => $this->input->post('item_type'),
                    'name' => $this->input->post('name'),
                    'desc' => (empty($this->input->post('desc'))) ? NULL : $this->input->post('desc'),
                    'sounds_like' => $sounds_like,
                    'availability' => 1,
                ], 'id');

                if (!empty($this->input->post('proname'))) {
                    $section_items = [];
                    $values1 = $this->input->post('proname');
                    $values2 = $this->input->post('proprice');
                    $values3 = $this->input->post('proweight');
                    $values4 = $this->input->post('id1');

                    for ($k = 0; $k < count($this->input->post('proname')); $k++) {
                        $id1 = $values4[$k];
                        if ($id1) {
                            $id1 = $id1;
                        } else {
                            $id1 = 0;
                        }
                        $this->data['sec_item12'] = $this->food_sec_item_model->where('id', $id1)->get_all();
                        if (count($this->data['sec_item12'][0]) > 0) {
                            $is_updated = $this->food_sec_item_model->update([
                                'id' => $this->data['sec_item12'][0]['id'],
                                'menu_id' => $menu_id,
                                'item_id' => $item_id,
                                'sec_id' => $section_id,
                                'section_item_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                                'name' => $values1[$k],
                                'price' => $values2[$k],
                                'weight' => $values3[$k],
                                'status' => 1
                            ], 'id');
                        } else {
                            $data1 = array(
                                'menu_id' => $menu_id,
                                'item_id' => $item_id,
                                'sec_id' => $section_id,
                                'section_item_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                                'name' => $values1[$k],
                                'price' => $values2[$k],
                                'weight' => $values3[$k],
                                'status' => 1
                            );
                            $this->food_sec_item_model->insert($data1);
                        }
                    }
                }

                if ($_FILES["item_images"]["name"][0] != "") {
                    $dt = $this->food_item_image_model->where('item_id', $this->input->post('id'))
                        ->get_all();
                    foreach ($dt as $d) {
                        unlink('./uploads/' . 'food_item' . '_image/' . 'food_item' . '_' . $d['id'] . '.jpg');
                    }

                    $is_deleted = $this->food_item_image_model->delete([
                        'item_id' => $this->input->post('id')
                    ]);
                    if ($is_deleted) {
                        foreach ($_FILES['item_images']['name'] as $key => $name) {
                            $product_image_id = $this->food_item_image_model->insert([
                                'item_id' => $item_id,
                                'serial_number' => ++$i,
                                'ext' => 'jpg'
                            ]);
                            $uploadFileDir = './uploads/food_item_image/';
                            $dest_path = $uploadFileDir;
                            $dest_path = $uploadFileDir . "food_item_" . $product_image_id . ".jpg";
                            move_uploaded_file($_FILES['item_images']['tmp_name'][$key], $dest_path);
                        }
                    }
                }
                $this->session->set_flashdata('upload_status', 'Product has been updated successfully');
                redirect('vendor_req_product/0/r', 'refresh');
            }
        } else if ($type == 'e') {

            $this->form_validation->set_rules($this->food_item_model->rules);

            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } elseif (empty($this->input->post('proname'))) {
                echo "Please add variants.";
                die();
            } else {
                $sounds_like = $this->sounds_like($this->input->post('name'), $this->input->post('shop_by_cat_id'), $this->input->post('menu_id'));

                $sub_cat_id = $this->input->post('sub_cat_id');
                $menu_id = $this->input->post('menu_id');
                $proname = $this->input->post('proname');
                $brand_id = $this->input->post('brand_id');
                $this->db->trans_begin();
                $item_id = $this->food_item_model->insert([
                    'sub_cat_id' => $sub_cat_id,
                    'menu_id' => $menu_id,
                    'brand_id' => $brand_id,
                    'item_type' => $this->input->post('item_type'),
                    'product_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                    'name' => $this->input->post('name'),
                    'desc' => (empty($this->input->post('desc'))) ? NULL : $this->input->post('desc'),
                    'sounds_like' => $sounds_like,
                    'availability' => 1,
                    'status' => 1
                ]);

                if ($item_id) {
                    $section_id = $this->food_section_model->insert([
                        'menu_id' => $menu_id,
                        'item_id' => $item_id,
                        'name' => $this->input->post('name')
                    ]);

                    if ($section_id && !empty($this->input->post('proname'))) {
                        $section_items = [];
                        $values1 = $this->input->post('proname');
                        $values2 = $this->input->post('proprice');
                        $values3 = $this->input->post('proweight');
                        for ($k = 0; $k < count($this->input->post('proname')); $k++) {
                            array_push($section_items, [
                                'menu_id' => $menu_id,
                                'item_id' => $item_id,
                                'sec_id' => $section_id,
                                'section_item_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                                'name' => $values1[$k],
                                'price' => $values2[$k],
                                'weight' => $values3[$k],
                                'status' => 1
                            ]);
                        }
                        $is__section_items_inserted = $this->food_sec_item_model->insert($section_items);
                        if ($this->db->trans_status() === FALSE && empty($is__section_items_inserted)) {
                            $this->session->set_flashdata('upload_status', 'variants data is missed, please create the product again.');
                            $this->db->trans_rollback();
                        } else {
                            $this->session->set_flashdata('upload_status', 'Product has been added successfully');
                            $this->db->trans_commit();
                        }
                    }
                    $i = 0;
                    foreach ($_FILES['item_images']['name'] as $key => $name) {
                        $product_image_id = $this->food_item_image_model->insert([
                            'item_id' => $item_id,
                            'serial_number' => ++$i,
                            'ext' => 'jpg'
                        ]);
                        if (!file_exists('uploads/' . 'food_item_image/')) {
                            mkdir('uploads/' . 'food_item_image/', 0777, true);
                        }

                        $uploadFileDir = './uploads/food_item_image/';
                        $dest_path = $uploadFileDir;
                        $dest_path = $uploadFileDir . "food_item_" . $product_image_id . ".jpg";
                        move_uploaded_file($_FILES['item_images']['tmp_name'][$key], $dest_path);
                    }
                }
            }
            redirect('vendor_req_product/0/r', 'refresh');
        } elseif ($type == 'l') {
            $this->data['title'] = 'Edit Item';
            $this->data['content'] = 'food/food/excel_product';
            $this->data['nav_type'] = 'food_item';
            $this->data['type'] = 'food_item';
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'k') {
            if (!$this->input->post('submit')) {
                $path = 'uploads/';
                require_once APPPATH . "/third_party/PHPExcel.php";
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'xlsx|xls';
                $config['remove_spaces'] = TRUE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('uploadFile')) {
                    $error = array(
                        'error' => $this->upload->display_errors()
                    );
                } else {
                    $data = array(
                        'upload_data' => $this->upload->data()
                    );
                }

                if (!empty($data['upload_data']['file_name'])) {
                    $import_xls_file = $data['upload_data']['file_name'];
                } else {
                    $import_xls_file = 0;
                }
                $inputFileName = $path . $import_xls_file;

                try {

                    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);
                    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    $flag = true;
                    $i = 0;

                    $sounds_like = $this->sounds_like($this->input->post('name'), $this->input->post('shop_by_cat_id'), $this->input->post('menu_id'));

                    foreach ($allDataInSheet as $value) {
                        if ($flag) {
                            $flag = false;
                            continue;
                        }
                        $sounds_like = $this->sounds_like($value['D'], $value['A'], $value['B']);
                        $sub_cat_id = $value['A'];
                        $menu_id = $value['B'];
                        $brand_id = $value['C'];
                        $name = $value['D'];
                        $desc = $value['E'];
                        $availability = $value['F'];
                        $status = $value['G'];

                        $item_id = $this->food_item_model->insert([
                            'sub_cat_id' => $sub_cat_id,
                            'menu_id' => $menu_id,
                            'brand_id' => $brand_id,
                            'product_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                            'name' => $name,
                            'desc' => (empty($this->input->post('desc'))) ? NULL : $this->input->post('desc'),
                            'sounds_like' => $sounds_like,
                            'availability' => 1,
                            'status' => 1
                        ]);

                        if ($item_id) {
                            $section_id = $this->food_section_model->insert([
                                'menu_id' => $menu_id,
                                'item_id' => $item_id,
                                'name' => $name
                            ]);

                            $values1 = $value['H'];
                            $arrname = explode(',', $values1);

                            if ($section_id && !empty($arrname)) {

                                $section_items = [];

                                $values1 = $value['H'];
                                $values2 = $value['I'];
                                $values3 = $value['J'];
                                $arrname = explode(',', $values1);
                                $arrprice = explode(',', $values2);
                                $arrweight = explode(',', $values3);

                                for ($k = 0; $k < count($arrname); $k++) {
                                    array_push($section_items, [
                                        'menu_id' => $menu_id,
                                        'item_id' => $item_id,
                                        'sec_id' => $section_id,
                                        'section_item_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                                        'name' => $arrname[$k],
                                        'price' => $arrprice[$k],
                                        'weight' => $arrweight[$k],
                                        'status' => 1
                                    ]);
                                }

                                $result = $this->food_sec_item_model->insert($section_items);

                                $product_image_id = $this->food_item_image_model->insert([
                                    'item_id' => $item_id,
                                    'serial_number' => ++$i,
                                    'ext' => 'jpg'
                                ]);

                                // $url=$d[1];

                                $url = $value['K'];

                                $name_temp = basename($url);

                                // $wotname = basename($name_temp,".php");
                                $name_temp1 = "food_item_" . $product_image_id . ".jpg";

                                if (!file_exists('uploads/' . 'food_item_image/')) {
                                    mkdir('uploads/' . 'food_item_image/', 0777, true);
                                }

                                $my_location = './uploads/food_item_image/';

                                if (file_exists($my_location . '' . $name_temp1)) {
                                    $fileinfo = pathinfo($url);
                                    $name = $fileinfo['filename'] . '_' . rand(1, 10000) . '.' . $fileinfo['extension'];
                                } else {
                                    $name = $name_temp1;
                                }

                                if (!defined('IMAGE_DIR'))
                                    define('IMAGE_DIR', $my_location);

                                $img = file_get_contents($url);
                                if (!$img) {
                                    die('Getting that file failed');
                                }

                                /* if (! $f = fopen(IMAGE_DIR . '/' . $name, 'w')) {
                                    die('Opening file for writing failed');
                                } */

                                $is_updated = file_put_contents(IMAGE_DIR . '/' . $name, $img);

                                if ($is_updated === FALSE) {
                                    die('Could not write to the file');
                                }
                                fclose($f);
                            }
                        }
                    }

                    if ($result) {

                        $this->session->set_flashdata('upload_status', [
                            "success" => "Imported successfully imported..!"
                        ]);

                        // echo "Imported successfully";
                        redirect('food_product/0/r', 'refresh');
                    } else {
                        echo "ERROR !";
                    }
                } catch (Exception $e) {

                    die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                }
            }
        } elseif ($type == 'sec_item') {
            $this->food_sec_item_model->delete([
                'id' => base64_decode(base64_decode($this->input->get('id')))
            ]);
            redirect($_SERVER['HTTP_REFERER']);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Item';
            $this->data['content'] = 'food/food/vendor_edit_food_product';
            $this->data['nav_type'] = 'food_item';
            $this->data['type'] = 'food_item';
            $this->data['sub_items'] = $this->food_item_model->order_by('id', 'DESC')
                ->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();

            if ($this->ion_auth->is_admin()) {
                $w_r = '(vendor_id = ' . $this->ion_auth->get_user_id() . ')';
            } else {
                $w_r = '(vendor_id = ' . $this->ion_auth->get_user_id() . ' OR vendor_id = 1)';
            }
            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])
                        ->get_all();
                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])
                    ->get_all();
            }

            $tempid = $this->data['sub_items']['id'];
            $this->data['sec_item1'] = $this->food_sec_item_model->where('item_id', $tempid)->get_all();
            $this->data['food_sec'] = $this->food_section_model->where('item_id', $tempid)->get_all();
            // $this->db->where($w_r);
            $this->data['items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')
                ->order_by('id', 'DESC')
                ->get_all();
            $this->data['food_sub_items'] = $this->food_item_model->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();

            $this->data['food_sub_items'] = $this->food_item_model->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();

            $this->data['food_sec'] = $this->food_section_model->where('item_id', $tempid)->get_all();

            $this->data['img'] = $this->food_item_image_model->where('item_id', $tempid)->get_all();

            $this->data['brands'] = $this->brand_model->fields('id,name,desc')->get_all();

            $this->_render_page($this->template, $this->data);
        } else if ($type = 'c') {
            $this->data['title'] = 'Product';
            $this->data['content'] = 'food/food/vendor_req_product';
            $this->data['nav_type'] = 'food_product';

            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $r = array();
                foreach ($cat_data as $c) {
                    $c['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                        ->where([
                            'cat_id' => $c['id'],
                            'type' => 2,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ])
                        ->get_all();

                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
                    ->where($w_r1)
                    ->where([
                        'cat_id' => $cat_id['category_id'],
                        'type' => 2
                    ])
                    ->get_all();
            }

            $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->get_all();
            $this->data['brands'] = $this->brand_model->fields('id,name,desc')->get_all();
            $this->_render_page($this->template, $this->data);
        }
    }
     public function deleteproduct()
	{
		$id = $this->input->post('id'); // Get the product ID from the POST request
		
		// Validate the ID
		if (!empty($id)) {
	   
			// Delete related data from other tables
			$this->db->where('item_id', $id);
			$this->db->delete('food_section'); // Delete from food_section table

			$this->db->where('item_id', $id);
			$this->db->delete('food_sec_item'); // Delete from food_sec_item table

			$this->db->where('item_id', $id);
			$this->db->delete('food_item_images'); // Delete from food_item_images table
			$this->db->where('id', $id);
			$this->db->delete('food_item'); 

			// Set success message
			$this->session->set_flashdata('delete_status', 'Product and related data deleted successfully.');
		} else {
			// Set error message if ID is invalid
			$this->session->set_flashdata('delete_status', 'Invalid product ID.');
		}
	}
}
