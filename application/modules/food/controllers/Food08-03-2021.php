<?php

class Food extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->template = 'template/admin/main';
        if (! $this->ion_auth->logged_in()) // || ! $this->ion_auth->is_admin()
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
    }

    /**
     * Food Products approve
     *
     * To Manage Food Item approvals
     *
     * @author Mahesh
     * @param string $type
     */
    public function products_approve($type = 'r')
    {
        if ($type == 'r') {
            $this->data['title'] = 'Products Approve';
            $this->data['nav_type'] = 'products_approve';
            $this->data['content'] = 'food/food/products_approve';
            $me = $this->food_item_model->with_menu('fields:id,name,vendor_id')
                ->where([
                'approval_status' => 2,
                'created_user_id !=' => $this->ion_auth->get_user_id()
            ])
                ->order_by('id', 'DESC')
                ->get_all();
            $apprved = $this->food_item_model->with_menu('fields:id,name,vendor_id')
                ->where([
                'approval_status' => 1,
                'created_user_id !=' => $this->ion_auth->get_user_id()
            ])
                ->order_by('id', 'DESC')
                ->get_all();
            $this->data['food_sub_items'] = $me;
            $this->data['approved_food_sub_items'] = $apprved;
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'approve') {
            $id = base64_decode(base64_decode($this->input->get('id')));
            $this->food_item_model->update(array(
                'approval_status' => 1
            ), $id);
            redirect('products_approve/r', 'refresh');
        } elseif ($type == 'disapprove') {
            $id = base64_decode(base64_decode($this->input->get('id')));
            $this->food_item_model->update(array(
                'approval_status' => 2
            ), $id);
            redirect('products_approve/r', 'refresh');
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->food_item_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
                die();
            } else {
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                $vendor_category_id = $cat_id['category_id'];
                if ($this->ion_auth->is_admin()) {
                    $approval_status = 1;
                } else {
                    $approval_status = 2;
                }
                /*
                 * if($vendor_category_id == 5)
                 * {
                 */
                $input_data = array(
                    'menu_id' => $this->input->post('menu_id'),
                    'price' => $this->input->post('price'),
                    'quantity' => $this->input->post('quantity'),
                    'status' => $this->input->post('status'),
                    'item_type' => $this->input->post('item_type'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'discount' => $this->input->post('discount'),
                    'approval_status' => $approval_status
                );
                /* } */
                if ($vendor_category_id == 6) {
                    $input_data['exp'] = $this->input->post('exp');
                    $input_data['qualification'] = $this->input->post('qualification');
                }

                $this->food_item_model->update($input_data, $this->input->post('id'));
                if ($_FILES['file']['name'] !== '') {
                    $path = $_FILES['file']['name'];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $this->file_up("file", "food_item", $this->input->post('id'), '', 'no');
                }
                redirect('food_item/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->ecom_sub_category_model->delete([
                'id' => $this->input->post('id')
            ]);
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
                if (! empty($_FILES['file']['tmp_name'])) {
                    if (! file_exists('uploads/' . 'sub_category' . '_image/')) {
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
            if (empty($_FILES['file']['name'])) {
                $this->form_validation->set_rules('file', 'Food Menu Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->food_item('r');
            } else {
                $input_data = array(
                    'vendor_id' => $this->ion_auth->get_user_id(),
                    'sub_cat_id' => $this->input->post('sub_cat_id'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc')
                );
                $id = $this->food_menu_model->insert($input_data);
                $this->file_up("file", "food_menu", $id, '', 'no');
                redirect('food_menu/r', 'refresh');
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
                    ->where('vendor_id', $this->ion_auth->get_user_id())
                    ->order_by('id', 'ASCE')
                    ->get_all();
            } else {
                $me = array();
                foreach ($this->data['sub_categories'] as $sub_categories) {

                    $a = $this->data['food_sub_items'] = $this->food_menu_model->with_shop_by_category('fields:id,name')
                        ->where('vendor_id', $this->ion_auth->get_user_id())
                        ->where('sub_cat_id', $sub_categories['id'])
                        ->order_by('id', 'ASCE')
                        ->get_all();
                    if (! empty($a)) {
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
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $this->food_menu_model->update([
                    'id' => $this->input->post('id'),
                    'sub_cat_id' => $this->input->post('sub_cat_id'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc')
                ], 'id');

                if ($_FILES['file']['name'] !== '') {
                    unlink('uploads/' . 'food_menu' . '_image/' . 'food_menu' . '_' . $this->input->post('id') . '.jpg');
                    move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'food_menu' . '_image/' . 'food_menu' . '_' . $this->input->post('id') . '.jpg');
                }
                redirect('food_menu/r', 'refresh');
            }
        } elseif ($type == 'd') {
            echo $this->food_menu_model->delete([
                'id' => $this->input->post('id')
            ]);
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
     * Food Sub Item crud
     *
     * To Manage Food Sub Items
     *
     * @author Mahesh
     * @param string $type
     * @param string $target
     */
 

    public function food_product($type = 'r')
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
                    'product_code' => implode( '-', str_split( substr( strtoupper( md5( time() . rand( 1000, 9999 ) ) ), 0, 20 ), 4 ) ),
                    'price' => $this->input->post('price'),
                    'quantity' => $this->input->post('quantity'),
                    'status' => $this->input->post('status'),
                    'item_type' => $this->input->post('item_type'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'discount' => $this->input->post('discount'),
                    'approval_status' => ($this->ion_auth->is_admin())? 1 : 2,
                    'sounds_like' => $sounds_like
                );
                $id = $this->food_item_model->insert($input_data);
                $this->file_up("file", "food_item", $id, '', 'no');
                redirect('products/0', 'refresh');
            }
        } elseif ($type == 'r') {
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
                        
                    ])->get_all();
                    
                    $r[] = $c;
                }
                $this->data['sub_categories'] = $r;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                ->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')->where($w_r1)
                ->where([
                    'cat_id' => $cat_id['category_id'],
                    'type' => 2
                ])
                ->get_all();
            }

             $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->get_all();


             $this->data['brands'] = $this->brand_model->fields('id,name,desc')->get_all();

            //$this->data['food_sub_items'] = $me;
            // $this->data['food_sub_items'] = $this->food_item_model->with_menu('fields:id,name,vendor_id','where: vendor_id='.$this->ion_auth->get_user_id())->get_all();
            $this->_render_page($this->template, $this->data);
        } 

    }



  

   public function foodproductstatus($type = 'change__st')
    {
        echo "dfdsfsd";
       
        $this->food_item_model->update([
                 'status' => ($this->input->post('is_checked') == 'true') ? 1 : 0
            ], $this->input->post('vendor_id'));
    }

/*        public function sounds_like($name = NULL, $shop_by_cat_id = NULL, $menu_id = NULL)
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
*/

=======
        }
    }

>>>>>>> f997282c6fc4a5574dd84ceaa58a24580bf9a671
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
            if (! $this->ion_auth->is_admin()) {
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
        if (! $this->ion_auth->in_group(1)) {
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

        if (! empty($catalogue_products)) {
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

    public function items_by_menu()
    {
        $menu = $this->food_item_model->where('menu_id', $_POST['menu_id'])->get_all();
        echo json_encode($menu);
    }

    public function sections_by_item()
    {
        $menu = $this->food_section_model->where('item_id', $_POST['item_id'])->get_all();
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
        if (! $this->ion_auth->in_group(1)) {
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

        if (! $this->ion_auth->in_group(1)) {
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
    public function food_orders($type = 'r', $order_type = 'upcoming')
    {
        if ($type == 'r') {
            $this->data['title'] = 'Orders';
            $this->data['content'] = 'food/food/orders';
            $this->data['nav_type'] = 'food_order';
            /* $this->data['users'] = $this->user_model->fields('id,first_name')->get_all(); */
            $c = 0;
            $s = $this->food_orders_model->where('vendor_id', $this->ion_auth->get_user_id())
                ->get_all();
            if ($s != '') {
                $c = count($s);
            }
            $this->data['orders_count'] = $c;
            $this->data['order_type'] = $order_type;

            if ($order_type == 'past') {
                // $this->db->where('order_status',6);
                $where_order_status = 'order_status = 6';
            } elseif ($order_type == 'upcoming') {
                $where_order_status = 'order_status != 0 AND order_status != 6 AND order_status != 7';
                /*
                 * $this->db->where('order_status !=',0);
                 * $this->db->where('order_status !=',6);
                 * $this->db->where('order_status !=',7);
                 */
            } elseif ($order_type == 'cancelled') {
                $where_order_status = 'order_status = 7';
                // $this->db->where('order_status',7);
            } elseif ($order_type == 'rejected') {
                $where_order_status = 'order_status = 0';
                // $this->db->where('order_status',0);
            }
            $data = array();
            /*
             * if ($this->ion_auth->is_admin()){
             * $this->db->where($where_order_status);
             * $data = $this->food_orders_model->with_user('fields:first_name')->with_vendor('fields:name')->with_order_items('fields:item_id,order_id,price,quantity,sec_item_id')->with_sub_order_items('fields:sec_item_id,order_id,price,quantity')->fields('id,discount,delivery_fee,payment_method_id,created_at,tax,total,deal_id,order_track,order_status,delivery,rejected_reason')->order_by('id', 'DESC')->get_all();
             *
             * }else{
             * $this->db->where($where_order_status);
             * $data = $this->food_orders_model->with_user('fields:first_name')->with_vendor('fields:name')->with_order_items('fields:item_id,order_id,price,quantity,sec_item_id')->with_sub_order_items('fields:sec_item_id,order_id,price,quantity')->fields('id,discount,delivery_fee,payment_method_id,created_at,tax,total,deal_id,order_track,order_status,delivery,rejected_reason')->where('vendor_id',$this->ion_auth->get_user_id())->order_by('id', 'DESC')->get_all();
             * }
             */

            if (! $this->ion_auth->is_admin()) {
                if ($order_type == 'all') {
                    $where_order_status = 'vendor_id = ' . $this->ion_auth->get_user_id();
                } else {
                    $where_order_status .= ' AND vendor_id = ' . $this->ion_auth->get_user_id();
                }
            }

            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime('+2 months'));
            if ((isset($_GET['start_date']) && $_GET['start_date'] != '') && (isset($_GET['end_date']) && $_GET['end_date'] != '')) {
                $start_date = $_GET['start_date'];
                $end_date = $_GET['end_date'];
            }
            if ($start_date != '' && $end_date != '') {
                if ($where_order_status != '') {
                    $where_order_status .= ' AND created_at >= "' . $start_date . ' 00:00:00" AND created_at <= "' . $end_date . ' 23:59:59"';
                } else {
                    $where_order_status .= 'created_at >= "' . $start_date . ' 00:00:00" AND created_at <= "' . $end_date . ' 23:59:59"';
                }
            }
            $this->data['start_date'] = $start_date;
            $this->data['end_date'] = $end_date;

            if ($where_order_status != '') {
                $this->db->where($where_order_status);
            }

            $data = $this->food_orders_model->with_user('fields:first_name')
                ->with_vendor('fields:name')
                ->with_promo('fields:id, promo_title, 	promo_code, promo_type, promo_label, discount_type, discount')
                ->with_order_items('fields:item_id,order_id,price,quantity')
                ->with_sub_order_items('fields:sec_item_id,order_id,item_id,price,quantity')
                ->fields('id,discount,delivery_fee,payment_method_id,created_at,tax,total,deal_id,order_track,order_status,delivery,rejected_reason,otp')
                ->order_by('id', 'DESC')
                ->get_all();

            /*
             * echo "<pre>";
             * print_r($data);die;
             */
            if (! empty($data)) {
                /* $status=['0'=>'Rejected','1'=>'Order Received','2'=>'Accepted','3'=>'Preparing','4'=>'Out for delivery','5'=>'Order on the way','6'=>'Order Completed','7'=>'Cancelled']; */
                for ($i = 0; $i < count($data); $i ++) {
                    $cat_id = $this->vendor_list_model->where('vendor_user_id', $data[$i]['vendor_id'])->get();
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

                    $data[$i]['order_stat'] = $status[$data[$i]['order_status']];
                    $deal = $this->food_order_deal_model->with_deal_boy('fields:first_name')
                        ->fields('id,deal_id,otp')
                        ->where('ord_deal_status', 2)
                        ->where('order_id', $data[$i]['id'])
                        ->get();
                    /* $data[$i]['deal_id'] = $deal['deal_id']; */
                    $data[$i]['ord_deal_id'] = $deal['id'];
                    /* $data[$i]['otp'] = $deal['otp']; */
                    $data[$i]['deal_name'] = $deal['deal_boy']['first_name'];
                }
            }
            $this->data['orders'] = $data;
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
                if (! empty($r)) {
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
        }
        // redirect($this->session->userdata('last_page'));
        redirect(base_url('/food_orders/r'));
    }

    public function get_orders_list($order_type)
    {
        if (! $this->ion_auth_acl->has_permission('food'))
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
        if (! empty($data)) {
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
            for ($i = 0; $i < count($data); $i ++) {
                $data[$i]['order_status'] = $status[$data[$i]['order_status']];
            }
        }
        // print_r($data);die;
        echo json_encode($data);
    }

    public function get_sub_item_list($item_id)
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('food'))
         * redirect('admin');
         */
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
        /*
         * if (! $this->ion_auth_acl->has_permission('food'))
         * redirect('admin');
         */
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
        /*
         * if (! $this->ion_auth_acl->has_permission('food'))
         * redirect('admin');
         */
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
        if (! empty($data)) {
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
            for ($i = 0; $i < count($mer); $i ++) {
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
                    $j ++;
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
        for ($i = 0; $i < count($mer); $i ++) {
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
            $j ++;
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
        if (! is_null($name)) {
            $sounds_like .= metaphone($name) . ' ';
        }
        return $sounds_like;
    }
}