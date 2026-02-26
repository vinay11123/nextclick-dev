<?php
error_reporting(E_ERROR | E_PARSE);

class MyInventory extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->template = 'vendorCrm/my_inventory';

        if (!$this->ion_auth->logged_in())
            redirect('auth/login');

        $this->load->model('vendor_list_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('vendor_leads_model');
        $this->load->model('user_model');
        $this->load->model('pickupcategory_model');
        $this->load->model('vendor_product_variant_model');
        $this->load->model('category_model');
        $this->load->model('sub_category_model');
        $this->load->model('food_sec_item_model');
        $this->load->model('food_item_model');
        $this->load->model('tax_model');
        $this->load->model('food_menu_model');
        $this->load->model('food_item_model');
        $this->load->model('food_section_model');
        $this->load->model('food_item_image_model');
        $this->load->model('brand_model');
        $this->load->model('notifications_model');
    }

    function my_inventory()
    {
        $vendor = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
        $this->data['users_groups']           =   $this->ion_auth->get_users_groups()->result();
        $this->data['users_permissions']      =   $this->ion_auth_acl->build_acl();
        $this->data['title'] = 'My Inventory';
        $this->data['content'] = 'vendor/my_inventory';
        $this->data['nav_type'] = 'my_inventory';

        if (isset($_POST['submit'])) {

            if ($_POST['search'] != '') {
                $where_search = " AND (c.name LIKE '%" . $_POST['search'] . "%' OR fi.name LIKE '%" . $_POST['search'] . "%' OR sc.name LIKE '%" . $_POST['search'] . "%')";
            }

            if ($_POST['sub_cat_id'] != '') {
                $where_sub_cat_id = " AND sc.id=" . $_POST['sub_cat_id'];
            }

            if ($_POST['menu_id'] != '') {
                if ($_POST['menu_id'] == 'all') {
                    $where_menu_id = " AND 1";
                } else {
                    $where_menu_id = " AND fm.id=" . $_POST['menu_id'];
                }
            }
        }

        $sql_items = "SELECT GROUP_CONCAT(item_id) item_ids FROM `vendor_product_variants` where `vendor_user_id`=" . $this->ion_auth->get_user_id();
        $query = $this->db->query($sql_items);
        $items = $query->result_array();
        $item_ids = $items[0]['item_ids'];

        if (!$this->ion_auth->in_group('admin', $this->ion_auth->get_user_id())) {
            $min_stock = $this->db->query("SELECT sum(min_stock) as min_stock FROM ecom_settings where created_user_id = " . $this->ion_auth->get_user_id() . ";")->result_array()[0]['min_stock'];
        } else {
            $min_stock = 0;
        }

        $instock_sql = "SELECT count(vpvv.id) instock_count FROM `food_sec_item` fsi
        JOIN vendor_product_variants vpvv on vpvv.section_item_id=fsi.id
        JOIN food_item fi on fi.id=fsi.item_id
        JOIN food_menu fm on fm.id=fi.menu_id
        JOIN sub_categories sc on sc.id=fi.sub_cat_id
        JOIN categories c on c.id=sc.cat_id 
        where fsi.item_id in (" . $item_ids . ") and vpvv.vendor_user_id=" . $this->ion_auth->get_user_id() . " AND vpvv.stock>$min_stock" . $where_sub_cat_id . $where_menu_id . $where_search;
        $query = $this->db->query($instock_sql);
        $instock = $query->result_array();
        $this->data['instock'] = $instock[0]['instock_count'];

        $outstock_sql = "SELECT COUNT(vpvv.id) outstock_count FROM `food_sec_item` fsi
        JOIN vendor_product_variants vpvv on vpvv.section_item_id=fsi.id
        JOIN food_item fi on fi.id=fsi.item_id
        JOIN food_menu fm on fm.id=fi.menu_id
        JOIN sub_categories sc on sc.id=fi.sub_cat_id
        JOIN categories c on c.id=sc.cat_id
        where fsi.item_id in (" . $item_ids . ") and vpvv.vendor_user_id=" . $this->ion_auth->get_user_id() . " AND vpvv.stock<=$min_stock" . $where_sub_cat_id . $where_menu_id . $where_search;
        $query = $this->db->query($outstock_sql);
        $outstock = $query->result_array();
        $this->data['out_stock'] = $outstock[0]['outstock_count'];

        $instock_records_sql = "SELECT fi.*,c.name category_name,fii.id image_id,fii.ext image_ext,sc.name sub_cat_name,sc.id sub_cat_id,fm.id menu_id,fm.name menu_name FROM `food_sec_item` fsi 
        JOIN vendor_product_variants vpvv on vpvv.section_item_id=fsi.id
        JOIN food_item fi on fi.id=fsi.item_id
        JOIN food_menu fm on fm.id=fi.menu_id
        JOIN sub_categories sc on sc.id=fi.sub_cat_id
        JOIN categories c on c.id=sc.cat_id 
        JOIN food_item_images fii on fii.item_id=fi.id 
        where fsi.item_id in (" . $item_ids . ") and vpvv.vendor_user_id=" . $this->ion_auth->get_user_id() . " AND vpvv.stock>0" . $where_sub_cat_id . $where_menu_id . $where_search . " GROUP BY fi.id";
        $query = $this->db->query($instock_records_sql);
        $this->data['in_stock_records'] = $query->result_array();

        $outstock_records_sql = "SELECT fi.*,c.name category_name,fii.id image_id,fii.ext image_ext,sc.name sub_cat_name,sc.id sub_cat_id,fm.id menu_id,fm.name menu_name FROM `food_sec_item` fsi 
        JOIN vendor_product_variants vpvv on vpvv.section_item_id=fsi.id
        JOIN food_item fi on fi.id=fsi.item_id
        JOIN food_menu fm on fm.id=fi.menu_id
        JOIN sub_categories sc on sc.id=fi.sub_cat_id
        JOIN categories c on c.id=sc.cat_id 
        JOIN food_item_images fii on fii.item_id=fi.id 
        where fsi.item_id in (" . $item_ids . ") and vpvv.vendor_user_id=" . $this->ion_auth->get_user_id() . " AND vpvv.stock=0" . $where_sub_cat_id . $where_menu_id . $where_search . " GROUP BY fi.id";
        $query = $this->db->query($outstock_records_sql);
        $this->data['out_stock_records'] = $query->result_array();

        $where_condition = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
        $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
            ->where($where_condition)
            ->where([
                'cat_id' => $vendor['category_id'],
                'type' => 2
            ])
            ->get_all();

        $this->_render_page($this->template, $this->data);
    }

    function stock_edit()
    {
        if ($_GET) {
            $_POST['item_id'][] = $_GET['item_id'];
        }
        $this->data['users_groups']           =   $this->ion_auth->get_users_groups()->result();
        $this->data['users_permissions']      =   $this->ion_auth_acl->build_acl();
        $this->data['title'] = 'My Inventory';
        $this->data['content'] = 'vendor/stock_edit';
        $this->data['nav_type'] = 'stock_edit';
        $this->template = 'vendorCrm/stock_edit';
        if (count($_POST['item_id']) > 0) {
            $item_ids = implode(',', $_POST['item_id']);

            $catalogue_products_sql = "SELECT fi.*,c.name category_name,fii.id image_id,fii.ext image_ext,sc.name sub_cat_name,sc.id sub_cat_id,fm.id menu_id,fm.name menu_name,b.name brand_name FROM `food_item` fi 
            JOIN food_menu fm on fm.id=fi.menu_id
            JOIN sub_categories sc on sc.id=fi.sub_cat_id
            JOIN categories c on c.id=sc.cat_id
            JOIN food_item_images fii on fii.item_id=fi.id
            JOIN brands b on b.id=fi.brand_id
            where sc.type=2 and fi.id IN($item_ids) group by fi.id";
            $query = $this->db->query($catalogue_products_sql);
            $this->data['catalogue_products'] = $query->result_array();

            $taxes_sql = "Select * from taxes order by id asc";
            $query = $this->db->query($taxes_sql);
            $this->data['taxes'] = $query->result_array();
            $tax_type_sql = "Select * from tax_types order by id asc";
            $query = $this->db->query($tax_type_sql);
            $this->data['tax_types'] = $query->result_array();
        }
        $this->_render_page($this->template, $this->data);
    }

    function stock_update()
    {
        $is_true = 0;
        $tax_id = '';
        $vendor = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
        for ($i = 0; $i < count($_POST['item_id']); $i++) {
            $item_id_value = $_POST['item_id'][$i];
            $product_section_sql = "SELECT * FROM `food_section` where item_id=" . $item_id_value;
            $query = $this->db->query($product_section_sql);
            $product_section = $query->result_array();
            $section_id = $product_section[0]['id'];

            $catalogue_product_sql = "SELECT fi.*,c.name category_name,fii.id image_id,fii.ext image_ext,sc.name sub_cat_name,sc.id sub_cat_id,fm.id menu_id,fm.name menu_name,b.name brand_name FROM `food_item` fi 
            JOIN food_menu fm on fm.id=fi.menu_id
            JOIN sub_categories sc on sc.id=fi.sub_cat_id
            JOIN categories c on c.id=sc.cat_id
            JOIN food_item_images fii on fii.item_id=fi.id
            JOIN brands b on b.id=fi.brand_id
            where sc.type=2 and fi.id=" . $item_id_value . " group by fi.id";
            $query = $this->db->query($catalogue_product_sql);
            $catalogue_product = $query->result_array();

            for ($j = 0; $j < count($_POST['variations'][$i]['price']); $j++) {

                if ($_POST['variations'][$i]['price'][$j] > 0 && $_POST['variations'][$i]['stock'][$j] > 0) {
                    if ($_POST['variations'][$i]['tax_id'][$j] == '') {
                        $tax_id = 1;
                    } else {
                        $tax_id = $_POST['variations'][$i]['tax_id'][$j];
                    }

                    $catalogue_variant_sql = "SELECT * FROM vendor_product_variants where item_id=" . $_POST['item_id'][$i] . " AND section_item_id=" . $_POST['variations'][$i]['variation_id'][$j] . " AND vendor_user_id=" . $this->ion_auth->get_user_id();
                    $query = $this->db->query($catalogue_variant_sql);
                    $catalogue_variant = $query->result_array();
                    if ($catalogue_variant[0]['id'] != '') {
                        $is_inserted = $this->vendor_product_variant_model->update([
                            'item_id' => $_POST['item_id'][$i],
                            'section_id' => $section_id,
                            'section_item_id' => $_POST['variations'][$i]['variation_id'][$j],
                            'sku' => generate_serial_no($vendor['unique_id'] . '-' . metaphone($catalogue_product[0]['sub_cat_name']) . '-' . metaphone($catalogue_product[0]['menu_name']) . '-', 2, $i),
                            'price' => $_POST['variations'][$i]['price'][$j],
                            'stock' => $_POST['variations'][$i]['stock'][$j],
                            'discount' => $_POST['variations'][$i]['discount'][$j],
                            'tax_id' => $tax_id,
                            'vendor_user_id' => $this->ion_auth->get_user_id(),
                            'list_id' => $vendor['id']
                        ], $catalogue_variant[0]['id']);
                    }

                    if ($is_inserted) {
                        $is_true = 1;
                    }
                }
            }
            if (count($_POST["new_variant_name_$i"]) > 0) {
                $item_sql = "SELECT * FROM food_sec_item where item_id=" . $_POST["new_variant_item_id_$i"];
                $query = $this->db->query($item_sql);
                $item = $query->result_array();
                if ($item[0]['item_id']) {
                    for ($k = 0; $k < count($_POST["new_variant_name_$i"]); $k++) {
                        $item_sec_id = $this->food_sec_item_model->insert([
                            'menu_id' => $item[0]['menu_id'],
                            'item_id' => $item[0]['item_id'],
                            'sec_id' => $item[0]['sec_id'],
                            'section_item_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                            'name' => $_POST["new_variant_name_$i"][$k],
                            'price' => $_POST["new_variant_weight_$i"][$k],
                            'weight' => $_POST["new_variant_price_$i"][$k],
                            'status' => 1
                        ]);

                        if ($_POST["new_variant_stock_$i"][$k] != '' && $_POST["new_variant_tax_id_$i"][$k] == '') {
                            $tax_id = 1;
                        } elseif ($_POST["new_variant_tax_id_$i"][$k] != '') {
                            $tax_id = $_POST["new_variant_tax_id_$i"][$k];
                        } else {
                            $tax_id = '';
                        }

                        if ($item_sec_id) {

                            $item_sec_sql = "SELECT fsi.id variant_id,sc.name sub_cat_name,fm.name menu_name FROM `food_sec_item` fsi
                            JOIN food_item fi on fi.id=fsi.item_id
                            JOIN sub_categories sc on sc.id=fi.sub_cat_id
                            JOIN food_menu fm on fm.id=fsi.menu_id
                            where fsi.id=" . $item_sec_id;
                            $query_sqc = $this->db->query($item_sec_sql);
                            $item_sec = $query_sqc->result_array();

                            $section_items = [];
                            array_push($section_items, [
                                'item_id' => $item[0]['item_id'],
                                'section_id' => $item[0]['sec_id'],
                                'section_item_id' => $item_sec_id,
                                'sku' => generate_serial_no($vendor['unique_id'] . '-' . metaphone($item_sec[0]['sub_cat_name']) . '-' . metaphone($item_sec[0]['menu_name']) . '-', 2, $i),
                                'price' => $_POST["new_variant_price_$i"][$k],
                                'stock' => $_POST["new_variant_stock_$i"][$k],
                                'discount' => $_POST["new_variant_discount_$i"][$k],
                                'tax_id' => $tax_id,
                                'vendor_user_id' => $this->ion_auth->get_user_id(),
                                'created_user_id' => $this->ion_auth->get_user_id(),
                                'list_id' => $vendor['id'],
                                'status' => $_POST["new_variant_status_$i"][$k],
                            ]);
                            $is_inserted = $this->db->insert_batch('vendor_product_variants', $section_items);
                        }
                    }
                }
            }
        }
        $this->data['title'] = 'My Inventory';
        $this->data['content'] = 'vendor/my_inventory';
        $this->data['nav_type'] = 'my_inventory';

        $sql_items = "SELECT GROUP_CONCAT(item_id) item_ids FROM `vendor_product_variants` where `vendor_user_id`=" . $this->ion_auth->get_user_id();
        $query = $this->db->query($sql_items);
        $items = $query->result_array();
        $item_ids = $items[0]['item_ids'];

        if (!$this->ion_auth->in_group('admin', $this->ion_auth->get_user_id())) {
            $min_stock = $this->db->query("SELECT sum(min_stock) as min_stock FROM ecom_settings where created_user_id = " . $this->ion_auth->get_user_id() . ";")->result_array()[0]['min_stock'];
        } else {
            $min_stock = 0;
        }

        $instock_sql = "SELECT count(vpvv.id) instock_count FROM `food_sec_item` fsi
        JOIN vendor_product_variants vpvv on vpvv.section_item_id=fsi.id
        JOIN food_item fi on fi.id=fsi.item_id
        JOIN food_menu fm on fm.id=fi.menu_id
        JOIN sub_categories sc on sc.id=fi.sub_cat_id
        JOIN categories c on c.id=sc.cat_id 
        where fsi.item_id in (" . $item_ids . ") and vpvv.vendor_user_id=" . $this->ion_auth->get_user_id() . " AND vpvv.stock>$min_stock";
        $query = $this->db->query($instock_sql);
        $instock = $query->result_array();
        $this->data['instock'] = $instock[0]['instock_count'];

        $outstock_sql = "SELECT COUNT(vpvv.id) outstock_count FROM `food_sec_item` fsi
        JOIN vendor_product_variants vpvv on vpvv.section_item_id=fsi.id
        JOIN food_item fi on fi.id=fsi.item_id
        JOIN food_menu fm on fm.id=fi.menu_id
        JOIN sub_categories sc on sc.id=fi.sub_cat_id
        JOIN categories c on c.id=sc.cat_id
        where fsi.item_id in (" . $item_ids . ") and vpvv.vendor_user_id=" . $this->ion_auth->get_user_id() . " AND vpvv.stock<=$min_stock";
        $query = $this->db->query($outstock_sql);
        $outstock = $query->result_array();
        $this->data['out_stock'] = $outstock[0]['outstock_count'];

        $instock_records_sql = "SELECT fi.*,c.name category_name,fii.id image_id,fii.ext image_ext,sc.name sub_cat_name,sc.id sub_cat_id,fm.id menu_id,fm.name menu_name FROM `food_sec_item` fsi 
        JOIN vendor_product_variants vpvv on vpvv.section_item_id=fsi.id
        JOIN food_item fi on fi.id=fsi.item_id
        JOIN food_menu fm on fm.id=fi.menu_id
        JOIN sub_categories sc on sc.id=fi.sub_cat_id
        JOIN categories c on c.id=sc.cat_id 
        JOIN food_item_images fii on fii.item_id=fi.id 
        where fsi.item_id in (" . $item_ids . ") and vpvv.vendor_user_id=" . $this->ion_auth->get_user_id() . " AND vpvv.stock>0 GROUP BY fi.id";
        $query = $this->db->query($instock_records_sql);
        $this->data['in_stock_records'] = $query->result_array();

        $outstock_records_sql = "SELECT fi.*,c.name category_name,fii.id image_id,fii.ext image_ext,sc.name sub_cat_name,sc.id sub_cat_id,fm.id menu_id,fm.name menu_name FROM `food_sec_item` fsi 
        JOIN vendor_product_variants vpvv on vpvv.section_item_id=fsi.id
        JOIN food_item fi on fi.id=fsi.item_id
        JOIN food_menu fm on fm.id=fi.menu_id
        JOIN sub_categories sc on sc.id=fi.sub_cat_id
        JOIN categories c on c.id=sc.cat_id 
        JOIN food_item_images fii on fii.item_id=fi.id 
        where fsi.item_id in (" . $item_ids . ") and vpvv.vendor_user_id=" . $this->ion_auth->get_user_id() . " AND vpvv.stock=0 GROUP BY fi.id";
        $query = $this->db->query($outstock_records_sql);
        $this->data['out_stock_records'] = $query->result_array();

        $where_condition = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
        $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
            ->where($where_condition)
            ->where([
                'cat_id' => $vendor['category_id'],
                'type' => 2
            ])
            ->get_all();
        $this->session->set_flashdata('upload_status', ["success" => "My inventory updated successfully"]);
        $this->load->view('vendorCrm/my_inventory', $this->data);
    }
    function duplicate_check_variant_name()
    {
        $duplicate_sql = "SELECT count(id) duplicate_name_length FROM food_sec_item WHERE `item_id`=" . $_POST['item_id'] . " AND `name`='" . $_POST['variant_name'] . "'";
        $query = $this->db->query($duplicate_sql);
        $duplicate = $query->result_array();
        echo json_encode($duplicate[0]['duplicate_name_length']);
    }
    function new_product()
    {
        $this->data['users_groups']           =   $this->ion_auth->get_users_groups()->result();
        $this->data['users_permissions']      =   $this->ion_auth_acl->build_acl();
        $this->data['title'] = 'New Product';
        $this->data['content'] = 'vendor/new_product';
        $this->data['nav_type'] = 'new_product';
        $this->template = 'vendorCrm/new_product';

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

    function duplucate_check()
    {
        $duplicate_sql = "SELECT count(id) duplicate_name_length FROM food_item WHERE `name`='" . $_POST['product_name'] . "' AND `sub_cat_id`='" . $_POST['sub_cat_id'] . "' AND `menu_id`='" . $_POST['menu_id'] . "' AND `brand_id`='" . $_POST['brand_id'] . "' AND item_type=3";
        $query = $this->db->query($duplicate_sql);
        $duplicate = $query->result_array();
        echo json_encode($duplicate[0]['duplicate_name_length']);
    }

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

    function new_product_add()
    {
        $sounds_like = $this->sounds_like($this->input->post('product_name'), $this->input->post('sub_cat_id'), $this->input->post('menu_id'));
        $shop_by_cat_id = $this->input->post('sub_cat_id');
        $menu_id = $this->input->post('menu_id');
        $brand_id = $this->input->post('brand_id');

        $item_id = $this->food_item_model->insert([
            'sub_cat_id' => $shop_by_cat_id,
            'menu_id' => $menu_id,
            'brand_id' => $brand_id,
            'product_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
            'name' => $this->input->post('product_name'),
            'desc' => (empty($this->input->post('desc'))) ? NULL : $this->input->post('desc'),
            'sounds_like' => $sounds_like,
            'availability' => 1,
            'item_type' => 3,
            'status' => ($this->ion_auth->in_group('admin', $this->ion_auth->get_user_id())) ? 1 : 3
        ]);
        if ($item_id) {
            $section_id = $this->food_section_model->insert([
                'menu_id' => $menu_id,
                'item_id' => $item_id,
                'name' => $this->input->post('product_name')
            ]);
            if ($section_id) {
                $section_items = [];
                for ($i = 0; $i < count($this->input->post('variant_name')); $i++) {
                    array_push($section_items, [
                        'menu_id' => $menu_id,
                        'item_id' => $item_id,
                        'sec_id' => $section_id,
                        'section_item_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                        'price' => $this->input->post("variant_price[$i]"),
                        'weight' => $this->input->post("variant_weight[$i]"),
                        'name' => $this->input->post("variant_name[$i]"),
                        'status' => 1
                    ]);
                }
                $this->food_sec_item_model->insert($section_items);
            }

            if ($_FILES['image']['name'] !== '') {
                $path = $_FILES['image']['name'];
                if (!file_exists('uploads/' . 'food_item' . '_image/')) {
                    mkdir('uploads/' . 'category' . '_image/', 0777, true);
                }
                $product_image_id = $this->food_item_image_model->insert([

                    'item_id' => $item_id,
                    'serial_number' => 1,
                    'ext' => 'jpg'
                ]);
                move_uploaded_file($_FILES['image']['tmp_name'], './uploads/food_item_image/food_item_' . $product_image_id . '.jpg');
            }
            $vendor_business_name = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get()['business_name'];
            $notification_id = $this->notifications_model->insert([
                'notification_type_id' => 28,
                'app_details_id' => 5,
                'title' => $this->input->post('product_name') . " Product is Cretaed!",
                'message' => 'New product is cretaed by ' . $vendor_business_name,
                'notified_user_id' => 1
            ]);
            if ($notification_id) {
                redirect('vendor_crm/myInventory/Pending_list', 'refresh');
            }
        }
    }

    function Pending_list()
    {
        $vendor = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
        $this->data['users_groups']           =   $this->ion_auth->get_users_groups()->result();
        $this->data['users_permissions']      =   $this->ion_auth_acl->build_acl();
        $this->data['title'] = 'Pending Product';
        $this->data['content'] = 'vendor/pending_product';
        $this->data['nav_type'] = 'pending';
        $this->template = 'vendorCrm/pending_product_list';

        if (isset($_POST['submit'])) {

            if ($_POST['search'] != '') {
                $where_search = " AND (c.name LIKE '%" . $_POST['search'] . "%' OR fi.name LIKE '%" . $_POST['search'] . "%' OR sc.name LIKE '%" . $_POST['search'] . "%')";
            }

            if ($_POST['sub_cat_id'] != '') {
                $where_sub_cat_id = " AND sc.id=" . $_POST['sub_cat_id'];
            }

            if ($_POST['menu_id'] != '') {
                if ($_POST['menu_id'] == 'all') {
                    $where_menu_id = " AND 1";
                } else {
                    $where_menu_id = " AND fm.id=" . $_POST['menu_id'];
                }
            }
        }

        $vendor_cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get()['category_id'];
        $sub_cat_ids = $this->sub_category_model->fields('id')->where('cat_id', $vendor_cat_id)->where('type', 2)->get_all();
        $sub_cat_id = implode(',', array_column($sub_cat_ids, 'id'));

        $pending_sql = "SELECT fi.*,c.name category_name,fii.id image_id,fii.ext image_ext,sc.name sub_cat_name,sc.id sub_cat_id,fm.id menu_id,fm.name menu_name FROM `food_item` fi 
        JOIN food_menu fm on fm.id=fi.menu_id
        JOIN sub_categories sc on sc.id=fi.sub_cat_id
        JOIN categories c on c.id=sc.cat_id
        JOIN food_item_images fii on fii.item_id=fi.id
        WHERE fi.status = 3 and fi.availability = 1 and fi.created_user_id=" . $this->ion_auth->get_user_id() . " and fi.sub_cat_id in(" . $sub_cat_id . ") and fi.deleted_at is null " . $where_sub_cat_id . $where_menu_id . $where_search . "group by fii.item_id";
        $query = $this->db->query($pending_sql);
        $this->data['pending_lists'] = $query->result_array();

        $where_condition = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
        $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
            ->where($where_condition)
            ->where([
                'cat_id' => $vendor['category_id'],
                'type' => 2
            ])
            ->get_all();

        $this->_render_page($this->template, $this->data);
    }
    function approved_list()
    {
        $vendor = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
        $this->data['users_groups']           =   $this->ion_auth->get_users_groups()->result();
        $this->data['users_permissions']      =   $this->ion_auth_acl->build_acl();
        $this->data['title'] = 'Approved Product';
        $this->data['content'] = 'vendor/approved_product';
        $this->data['nav_type'] = 'approved';
        $this->template = 'vendorCrm/approved_product_list';

        if (isset($_POST['submit'])) {

            if ($_POST['search'] != '') {
                $where_search = " AND (c.name LIKE '%" . $_POST['search'] . "%' OR fi.name LIKE '%" . $_POST['search'] . "%' OR sc.name LIKE '%" . $_POST['search'] . "%')";
            }

            if ($_POST['sub_cat_id'] != '') {
                $where_sub_cat_id = " AND sc.id=" . $_POST['sub_cat_id'];
            }

            if ($_POST['menu_id'] != '') {
                if ($_POST['menu_id'] == 'all') {
                    $where_menu_id = " AND 1";
                } else {
                    $where_menu_id = " AND fm.id=" . $_POST['menu_id'];
                }
            }
        }

        $vendor_cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get()['category_id'];
        $sub_cat_ids = $this->sub_category_model->fields('id')->where('cat_id', $vendor_cat_id)->where('type', 2)->get_all();
        $sub_cat_id = implode(',', array_column($sub_cat_ids, 'id'));

        $approved_sql = "SELECT fi.*,c.name category_name,fii.id image_id,fii.ext image_ext,sc.name sub_cat_name,sc.id sub_cat_id,fm.id menu_id,fm.name menu_name FROM `food_item` fi 
        JOIN food_menu fm on fm.id=fi.menu_id
        JOIN sub_categories sc on sc.id=fi.sub_cat_id
        JOIN categories c on c.id=sc.cat_id
        JOIN food_item_images fii on fii.item_id=fi.id
        WHERE fi.status = 2 and fi.availability = 1 and fi.created_user_id=" . $this->ion_auth->get_user_id() . " and fi.sub_cat_id in(" . $sub_cat_id . ") and fi.deleted_at is null " . $where_sub_cat_id . $where_menu_id . $where_search . "group by fii.item_id";
        $query = $this->db->query($approved_sql);
        $this->data['approved_lists'] = $query->result_array();

        $where_condition = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
        $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
            ->where($where_condition)
            ->where([
                'cat_id' => $vendor['category_id'],
                'type' => 2
            ])
            ->get_all();

        $this->_render_page($this->template, $this->data);
    }

    function deleteproduct()
    {
        $delete_sql = "Update food_item set deleted_at='" . date('Y-m-d h:i:s') . "' where id=" . $this->input->post('id');
        $query = $this->db->query($delete_sql);
        $this->session->set_flashdata('delete_status', 'Product deleted successfully');
    }

    public function approved_add()
    {
        $this->data['users_groups']           =   $this->ion_auth->get_users_groups()->result();
        $this->data['users_permissions']      =   $this->ion_auth_acl->build_acl();
        $this->data['title'] = 'Approved';
        $this->data['content'] = 'vendor/approved_add';
        $this->data['nav_type'] = 'approved_add';
        $this->template = 'vendorCrm/approved_add_product';
        if (isset($_GET['item_id'])) {
            $catalogue_products_sql = "SELECT fi.*,c.name category_name,fii.id image_id,fii.ext image_ext,sc.name sub_cat_name,sc.id sub_cat_id,fm.id menu_id,fm.name menu_name,b.name brand_name FROM `food_item` fi 
            JOIN food_menu fm on fm.id=fi.menu_id
            JOIN sub_categories sc on sc.id=fi.sub_cat_id
            JOIN categories c on c.id=sc.cat_id
            JOIN food_item_images fii on fii.item_id=fi.id
            JOIN brands b on b.id=fi.brand_id
            where fi.id =" . $_GET['item_id'];
            $query = $this->db->query($catalogue_products_sql);
            $this->data['catalogue_products'] = $query->result_array();

            $tax_type_sql = "Select * from tax_types order by id asc";
            $query = $this->db->query($tax_type_sql);
            $this->data['tax_types'] = $query->result_array();
            $taxes_sql = "Select * from taxes order by id asc";
            $query = $this->db->query($taxes_sql);
            $this->data['taxes'] = $query->result_array();
        }
        $this->_render_page($this->template, $this->data);
    }

    public function approved_update()
    {
        $is_true = 0;
        $vendor = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
        for ($i = 0; $i < count($_POST['item_id']); $i++) {
            $item_id_value = $_POST['item_id'][$i];
            $product_section_sql = "SELECT * FROM `food_section` where item_id=" . $item_id_value;
            $query = $this->db->query($product_section_sql);
            $product_section = $query->result_array();
            $section_id = $product_section[0]['id'];

            $catalogue_product_sql = "SELECT fi.*,c.name category_name,fii.id image_id,fii.ext image_ext,sc.name sub_cat_name,sc.id sub_cat_id,fm.id menu_id,fm.name menu_name,b.name brand_name FROM `food_item` fi 
            JOIN food_menu fm on fm.id=fi.menu_id
            JOIN sub_categories sc on sc.id=fi.sub_cat_id
            JOIN categories c on c.id=sc.cat_id
            JOIN food_item_images fii on fii.item_id=fi.id
            JOIN brands b on b.id=fi.brand_id
            where sc.type=2 and fi.id=" . $item_id_value;
            $query = $this->db->query($catalogue_product_sql);
            $catalogue_product = $query->result_array();

            for ($j = 0; $j < count($_POST['variations'][$i]['price']); $j++) {
                if ($_POST['variations'][$i]['stock'][$j] != '' && $_POST['variations'][$i]['tax_id'][$j] == '') {
                    $tax_id = 1;
                } elseif ($_POST['variations'][$i]['tax_id'][$j] != '') {
                    $tax_id = $_POST['variations'][$i]['tax_id'][$j];
                } else {
                    $tax_id = '';
                }
                $section_items = [];
                array_push($section_items, [
                    'item_id' => $_POST['item_id'][$i],
                    'section_id' => $section_id,
                    'section_item_id' => $_POST['variations'][$i]['variation_id'][$j],
                    'sku' => generate_serial_no($vendor['unique_id'] . '-' . metaphone($catalogue_product[0]['sub_cat_name']) . '-' . metaphone($catalogue_product[0]['menu_name']) . '-', 2, $i),
                    'price' => $_POST['variations'][$i]['price'][$j],
                    'stock' => $_POST['variations'][$i]['stock'][$j] ? $_POST['variations'][$i]['stock'][$j] : 0,
                    'discount' => $_POST['variations'][$i]['discount'][$j] ? $_POST['variations'][$i]['discount'][$j] : 0,
                    'tax_id' => $tax_id,
                    'vendor_user_id' => $this->ion_auth->get_user_id(),
                    'created_user_id' => $this->ion_auth->get_user_id(),
                    'list_id' => $vendor['id'],
                    'status' => $_POST['variations'][$i]['status'][$j],
                ]);
                $is_inserted = $this->db->insert_batch('vendor_product_variants', $section_items);

                if ($is_inserted) {
                    $is_true = 1;
                }
            }
        }
        $vendor = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
        $this->data['users_groups']           =   $this->ion_auth->get_users_groups()->result();
        $this->data['users_permissions']      =   $this->ion_auth_acl->build_acl();
        $this->data['title'] = 'Approved Product';
        $this->data['content'] = 'vendor/approved_product';
        $this->data['nav_type'] = 'approved';
        $this->template = 'vendorCrm/approved_product_list';
        $vendor_cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get()['category_id'];
        $sub_cat_ids = $this->sub_category_model->fields('id')->where('cat_id', $vendor_cat_id)->where('type', 2)->get_all();
        $sub_cat_id = implode(',', array_column($sub_cat_ids, 'id'));
        $approved_sql = "SELECT fi.*,c.name category_name,fii.id image_id,fii.ext image_ext,sc.name sub_cat_name,sc.id sub_cat_id,fm.id menu_id,fm.name menu_name FROM `food_item` fi 
        JOIN food_menu fm on fm.id=fi.menu_id
        JOIN sub_categories sc on sc.id=fi.sub_cat_id
        JOIN categories c on c.id=sc.cat_id
        JOIN food_item_images fii on fii.item_id=fi.id
        WHERE fi.status = 2 and fi.availability = 1 and fi.created_user_id=" . $this->ion_auth->get_user_id() . " and fi.sub_cat_id in(" . $sub_cat_id . ") and fi.deleted_at is null group by fii.item_id";
        $query = $this->db->query($approved_sql);
        $this->data['approved_lists'] = $query->result_array();

        $where_condition = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
        $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
            ->where($where_condition)
            ->where([
                'cat_id' => $vendor['category_id'],
                'type' => 2
            ])
            ->get_all();

        $this->session->set_flashdata('upload_status', ["success" => "Approved product updated successfully"]);
        $this->_render_page($this->template, $this->data);
    }
}
