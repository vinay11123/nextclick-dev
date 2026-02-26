<?php
class Promotion_banners extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->template = 'template/admin/main';
        if (!$this->ion_auth->logged_in()  || !$this->ion_auth->is_admin())
            redirect('auth/login');

        $this->load->library('pagination');
        $this->load->model('user_model');
        $this->load->model('app_details_model');
        $this->load->model('banner_cost_model');
        $this->load->model('promotion_banner_model');
        $this->load->model('promotion_banner_discount_type_model');
        $this->load->model('vendor_list_model');
        $this->load->model('sub_category_model');
        $this->load->model('food_menu_model');
        $this->load->model('brand_model');
        $this->load->model('promotion_banner_images_model');
        $this->load->model('state_model');
        $this->load->model('district_model');
        $this->load->model('constituency_model');
        $this->load->model('promotion_banner_position_model');
        $this->load->model('category_model');
        $this->load->model('promotion_banner_shop_by_category_model');
        $this->load->model('admin_banners_model');
    }

    public function manage_promotion_banners($type = 'r', $rowno = 0)
    {
        if ($type == 'c') {
            $this->data['title'] = 'Add Promotion';
            $this->data['nav_type'] = 'promotion_banners';
            $this->data['content'] = 'promotion_banners/add_banner';
            $this->data['states'] = $this->state_model->order_by('id', 'DESC')->get_all();
            $this->data['discount_type'] = $this->promotion_banner_discount_type_model->order_by('id', 'ASC')->get_all();
            $this->data['positions'] = $this->promotion_banner_position_model->where('id', 4)->order_by('id', 'ASC')->get_all();
            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $this->data['categories'] = $cat_data;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $this->data['categories'] = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
            }
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 's') {
            $this->form_validation->set_rules($this->promotion_banner_model->rules['create']);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $shop_by_category_files = [];
                if ($_FILES['img']) {
                    foreach ($_FILES['img']['name'] as $key => $name) {
                        array_push($shop_by_category_files, [
                            'name' => $name,
                            'type' => $_FILES['img']['type'][$key],
                            'tmp_name' => $_FILES['img']['tmp_name'][$key],
                            'error' => $_FILES['img']['error'][$key],
                            'size' => $_FILES['img']['size'][$key]
                        ]);
                    }
                }
                $token_data = $this->ion_auth->get_user_id();
                $id = $this->promotion_banner_model->insert([
                    'title' => empty($this->input->post('title')) ? NULL : $this->input->post('title'),
                    'cat_id' => empty($this->input->post('cat_id')) ? NULL : $this->input->post('cat_id'),
                    //'image_id' => empty($this->input->post('imgvalue'))? NULL : $this->input->post('imgvalue'),
                    //'sub_cat_id' => empty($this->input->post('sub_cat_id'))? NULL : $this->input->post('sub_cat_id'),
                    'constituency_id' => empty($this->input->post('constituency')) ? NULL : $this->input->post('constituency'),
                    //'promotion_banner_position_id' => empty($this->input->post('image-position'))? NULL : $this->input->post('image-position'),
                    'promotion_banner_position_id' => 4,
                    'content_type' => 4,
                    'published_on' => empty($this->input->post('start_date')) ? NULL : $this->input->post('start_date'),
                    'promotion_banner_discount_type_id' => empty($this->input->post('discount_type')) ? NULL : $this->input->post('discount_type'),
                    'discount' => empty($this->input->post('discount')) ? NULL : $this->input->post('discount'),
                    'max_offer_steps' => empty($this->input->post('max_offer_steps')) ? NULL : $this->input->post('max_offer_steps'),
                    'expired_on' => empty($this->input->post('end_date')) ? NULL : $this->input->post('end_date'),
                    'owner' => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 2,
                    'status' => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 0
                ]);

                if (!file_exists('uploads/' . 'promotion_banner' . '_image/')) {
                    mkdir('uploads/' . 'promotion_banner' . '_image/', 0777, true);
                }
                file_put_contents("./uploads/promotion_banner_image/promotion_banner_" . $id . ".jpg", base64_decode($this->input->post('file')));

                if ($id) {
                    if ($this->input->post('sub_cat_id2')) {
                        if (!file_exists('uploads/' . 'promotion_banner_shop_by_category' . '_image/')) {
                            mkdir('uploads/' . 'promotion_banner_shop_by_category' . '_image/', 0777, true);
                        }
                        if (!empty($this->input->post('sub_cat_id2'))) {
                            foreach ($this->input->post('sub_cat_id2') as $key => $val) {
                                $shop_by_category_banner_id = $this->promotion_banner_shop_by_category_model->insert([
                                    'sub_cat_id' => $val,
                                    'promotion_banner_id' => $id
                                ]);
                            }
                        }
                        file_put_contents("./uploads/promotion_banner_shop_by_category_image/promotion_banner_shop_by_category_" . $shop_by_category_banner_id . ".jpg", base64_decode($this->input->post['image']));
                    }
                    if ($this->input->post('sub_cat_id')) {
                        if (!file_exists('uploads/' . 'promotion_banner_shop_by_category' . '_image/')) {
                            mkdir('uploads/' . 'promotion_banner_shop_by_category' . '_image/', 0777, true);
                        }
                        if (!empty($this->input->post('sub_cat_id'))) {
                            foreach ($this->input->post('sub_cat_id') as $k => $value) {
                                $shop_by_category_banner_id = $this->promotion_banner_shop_by_category_model->insert([
                                    'sub_cat_id' => $value,
                                    'promotion_banner_id' => $id
                                ]);
                                move_uploaded_file($shop_by_category_files[$k]['tmp_name'], "./uploads/promotion_banner_shop_by_category_image/promotion_banner_shop_by_category_" . $shop_by_category_banner_id . ".jpg");
                                // file_put_contents("./uploads/promotion_banner_shop_by_category_image/promotion_banner_shop_by_category_" . $shop_by_category_banner_id . ".jpg", $shop_by_category_files[$k]);
                            }
                        }
                    }
                }
                $path = $_FILES['file']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $this->file_up("file", "promotion_banner", $id, '', 'no');
                redirect('promotion_banners/r/0', 'refresh');
            }
        } elseif ($type == 'r') {
            $noofrows = 10;
            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }
            $this->data['title'] = 'All Promotions';
            $this->data['nav_type'] = 'promotion_banners';
            $this->data['content'] = 'promotion_banners/list_of_promotions';
            $this->data['categories'] = $this->category_model->fields('id,name,desc')->get_all();
            $this->data['shop_by_cat'] = $this->sub_category_model->fields('id,name,desc')->where('type', 2)->get_all();
            $this->data['banners'] = $this->promotion_banner_model->fields('id, image_id,title, desc, cat_id, sub_cat_id, brand_id, constituency_id, promotion_banner_position_id, max_offer_steps,content_type, published_on, promotion_banner_discount_type_id, expired_on, owner, accessibility, status')
                ->with_category('fields:id,name')
                ->with_sub_category('fields: id,name')
                ->with_promotion_banners_shop_by_categories('fields: id,name')
                ->with_position('fields:id,title')->where('owner', 1)->order_by('id', 'desc')->limit($rowperpage, $rowno)->get_all();
            //print_r($this->db->last_query());exit;
            $all_data = $this->promotion_banner_model->fields('id, image_id,title, desc, cat_id, sub_cat_id, brand_id, constituency_id, promotion_banner_position_id, max_offer_steps,content_type, published_on, promotion_banner_discount_type_id, expired_on, owner, accessibility, status')
                ->with_category('fields:id,name')
                ->with_sub_category('fields: id,name')
                ->with_promotion_banners_shop_by_categories('fields: id,name')
                ->with_position('fields:id,title')->where('owner', 1)->order_by('id', 'desc')->get_all();


            $allcount = (count($all_data));
            $users_record = $this->promotion_banner_model->fields('id, image_id,title, desc, cat_id, sub_cat_id, brand_id, constituency_id, promotion_banner_position_id, max_offer_steps,content_type, published_on, promotion_banner_discount_type_id, expired_on, owner, accessibility, status')
                ->with_category('fields:id,name')
                ->with_sub_category('fields: id,name')
                ->with_promotion_banners_shop_by_categories('fields: id,name')
                ->with_position('fields:id,title')->where('owner', 1)->order_by('id', 'desc')->limit($rowperpage, $rowno)->get_all();


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
            $config['base_url'] = base_url() . 'promotion_banners/r';
            $config['first_url'] = base_url() . 'promotion_banners/r/0';;

            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $rowperpage;
            $this->pagination->initialize($config);

            $this->data['pagination'] = $this->pagination->create_links();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->promotion_banner_model->rules['update']);
            if ($this->form_validation->run() == false) {
                $this->data['title'] = 'Add Promotion';
                $this->data['nav_type'] = 'promotion_banners';
                $this->data['content'] = 'promotion_banners/add_banner';
                $this->_render_page($this->template, $this->data);
            } else {
                $token_data = $this->ion_auth->get_user_id();
                $id = $this->promotion_banner_model->update([
                    'id' => $this->input->post('id'),
                    'title' => $this->input->post('title'),
                    'cat_id' => $this->input->post('cat_id'),
                    'sub_cat_id' => $this->input->post('sub_cat_id'),
                    'content_type' => 4,
                    'constituency_id' => $this->input->post('constituency'),
                    'promotion_banner_position_id' => empty($this->input->post('image-position')) ? NULL : $this->input->post('image-position'),
                    'promotion_banner_position_id' => 4,
                    'published_on' => $this->input->post('start_date'),
                    'promotion_banner_discount_type_id' => $this->input->post('discount_type'),
                    'discount' => $this->input->post('discount'),
                    'max_offer_steps' => $this->input->post('max_offer_steps'),
                    'expired_on' => $this->input->post('end_date'),
                    'owner' => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 2,
                    'status' => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 0,
                ], 'id');

                if (!empty($this->input->post('sub_cat_id'))) {
                    $existingShopByCategory = $this->promotion_banner_shop_by_category_model->where([
                        'promotion_banner_id' => $this->input->post('id')
                    ])->get();
                    if ($existingShopByCategory) {
                        $this->promotion_banner_shop_by_category_model->update([
                            'sub_cat_id' => $this->input->post('sub_cat_id')
                        ], [
                            'id' => $existingShopByCategory['id']
                        ]);
                    } else {
                        $this->promotion_banner_shop_by_category_model->insert([
                            'sub_cat_id' => $this->input->post('sub_cat_id'),
                            'promotion_banner_id' => $id
                        ]);
                    }
                }

                if ($_FILES['file']['name'] !== '') {
                    $path = $_FILES['file']['name'];
                    if (!file_exists('uploads/' . 'promotion_banner' . '_image/')) {
                        mkdir('uploads/' . 'promotion_banner' . '_image/', 0777, true);
                    }
                    if (file_exists('uploads/' . 'promotion_banner' . '_image/' . 'promotion_banner' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'promotion_banner' . '_image/' . 'promotion_banner' . '_' . $this->input->post('id') . '.jpg');
                    }
                    move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'promotion_banner' . '_image/' . 'promotion_banner' . '_' . $this->input->post('id') . '.jpg');
                }

                redirect('promotion_banners/r/0', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->promotion_banner_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Promotion';
            $this->data['nav_type'] = 'promotion_banners';
            $this->data['content'] = 'promotion_banners/edit';
            $this->data['discount_type'] = $this->promotion_banner_discount_type_model->order_by('id', 'ASC')->get_all();
            $this->data['states'] = $this->state_model->order_by('id', 'DESC')->get_all();
            $this->data['districts'] = $this->district_model->order_by('id', 'DESC')->get_all();
            $this->data['constituencies'] = $this->constituency_model->with_state('fields:id,name')->with_district('fields:id,name')->order_by('id', 'DESC')->get_all();
            $this->data['subcategories'] = $this->sub_category_model->order_by('id', 'DESC')->get_all();
            $this->data['promotion_banners'] = $this->promotion_banner_model
                ->with_category('fields:id,name')
                ->with_sub_category('fields:id,name')
                ->with_constituency('fields:id,state_id,district_id,name')
                ->with_discount_type('fields:id,name')
                ->order_by('id', 'DESC')
                ->where('id', $this->input->get('id'))
                ->get();
            $promotionBanners = $this->promotion_banner_shop_by_category_model
                ->with_sub_category('fields:id,name')
                ->where([
                    'promotion_banner_id' => $this->data['promotion_banners']['id']
                ])->get_all();
            $subCategories = [];
            foreach ($promotionBanners as $promotionBanner) {
                array_push($subCategories, $promotionBanner['sub_category']);
            }
            $this->data['promotion_banners']['sub_category'] = $subCategories;
            $this->data['positions'] = $this->promotion_banner_position_model->where('id', 4)->order_by('id', 'ASC')->get_all();
            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $this->data['categories'] = $cat_data;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $this->data['categories'] = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
            }
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'banner_images') {
            $images =  $this->promotion_banner_images_model->where('cat_id', $_POST['cat_id'])->get_all();
            echo json_encode($images);
        } elseif ($type == 'promotion_bulk_upload') {
            if (!$this->ion_auth->logged_in())
                redirect('auth/login');

            if (!empty($_FILES['excel_file']) && !empty($_FILES['excel_file']['name']) && !empty($_FILES['images_zip'])) {
                $zip_file = $_FILES['images_zip'];
                $excel_file = $_FILES['excel_file'];
                $base_path = dirname(BASEPATH) . '/uploads/promotions_zip/';
                if (!file_exists($base_path)) {
                    mkdir($base_path, 0777, true);
                }

                $random_digit = time();
                $new_file_name = $random_digit . ".zip";
                mkdir($base_path . $random_digit, 0777, true);

                $zip_file_path = $base_path . $random_digit . '/' . $new_file_name;
                if (copy($zip_file['tmp_name'], $zip_file_path)) {
                    // zip extraction
                    $zip = new ZipArchive();
                    if ($zip->open($zip_file_path) === TRUE) {
                        $zip->extractTo($base_path . $random_digit);
                        $zip->close();
                        $is_updated = $this->upload_bulk_offer_promotion($excel_file, $base_path, $random_digit);
                    } else {
                        $this->session->set_flashdata('upload_status', ["error" => "Unable to extract ZIP"]);
                    }
                } else {
                    $this->session->set_flashdata('upload_status', ["error" => "Uploading ZIP is failed"]);
                }
            }
            $this->data['title'] = 'Offer Promotion bulk upload';
            $this->data['content'] = 'promotion_banners/bulkupload_offer_promotions';
            $this->data['nav_type'] = 'offer_promotion_upload';
            $this->_render_page($this->template, $this->data);
        }
    }
    public function upload_bulk_offer_promotion($file, $base_path, $random_digit)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');
        // If file uploaded
        if (!empty($file['name'])) {
            // get file extension
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

            if ($extension == 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } elseif ($extension == 'xlsx') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }
            // file path
            $spreadsheet = $reader->load($file['tmp_name']);
            $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            // array Count
            $arrayCount = count($allDataInSheet);
            $flag = 0;
            $createArray = array(
                'title', 'category_id', 'state_id', 'district_id',
                'constituency_id', 'published_date', 'expiry_date', 'discount_type',
                'offer_max_qty', 'discount', 'shop_by_categories', 'images'
            );
            $makeArray = array(
                'title' => 'title', 'category_id' => 'category_id', 'state_id' => 'state_id', 'district_id' => 'district_id',
                'constituency_id' => 'constituency_id', 'published_date' => 'published_date', 'expiry_date' => 'expiry_date', 'discount_type' => 'discount_type',
                'offer_max_qty' => 'offer_max_qty', 'discount' => 'discount', 'shop_by_categories' => 'shop_by_categories', 'images' => 'images'
            );
            $SheetDataKey = array();

            foreach ($allDataInSheet as $dataInSheet) {
                foreach ($dataInSheet as $key => $value) {
                    if (in_array(trim($value), $createArray)) {
                        $value = preg_replace('/\s+/', '', $value);
                        $SheetDataKey[trim($value)] = $key;
                    }
                }
            }
            $dataDiff = array_diff_key($makeArray, $SheetDataKey);
            if (empty($dataDiff)) {
                $flag = 1;
            }
            // match excel sheet column
            if ($flag == 1) {
                $k = 0;
                for ($i = 2; $i <= $arrayCount; $i++) {

                    $title = $SheetDataKey['title'];
                    $category_id = $SheetDataKey['category_id'];
                    $state_id = $SheetDataKey['state_id'];
                    $district_id = $SheetDataKey['district_id'];
                    $constituency_id = $SheetDataKey['constituency_id'];
                    $published_date = $SheetDataKey['published_date']; //mm-dd-yyyy
                    $expiry_date = $SheetDataKey['expiry_date']; //mm-dd-yyyy
                    $discount_type = $SheetDataKey['discount_type'];
                    $offer_max_qty = $SheetDataKey['offer_max_qty'];
                    $discount = $SheetDataKey['discount'];
                    $shop_by_categories = $SheetDataKey['shop_by_categories'];
                    $images = $SheetDataKey['images'];

                    $title = filter_var(trim($allDataInSheet[$i][$title]), FILTER_SANITIZE_STRING);
                    $category_id = filter_var(trim($allDataInSheet[$i][$category_id]), FILTER_SANITIZE_STRING);
                    $state_id = filter_var(trim($allDataInSheet[$i][$state_id]), FILTER_SANITIZE_STRING);
                    $district_id = filter_var(trim($allDataInSheet[$i][$district_id]), FILTER_SANITIZE_EMAIL);
                    $constituency_id = filter_var(trim($allDataInSheet[$i][$constituency_id]), FILTER_SANITIZE_STRING);
                    $published_date = filter_var(trim($allDataInSheet[$i][$published_date]), FILTER_SANITIZE_STRING);
                    $expiry_date = filter_var(trim($allDataInSheet[$i][$expiry_date]), FILTER_SANITIZE_STRING);
                    $discount_type = filter_var(trim($allDataInSheet[$i][$discount_type]), FILTER_SANITIZE_EMAIL);
                    $offer_max_qty = filter_var(trim($allDataInSheet[$i][$offer_max_qty]), FILTER_SANITIZE_STRING);
                    $discount = filter_var(trim($allDataInSheet[$i][$discount]), FILTER_SANITIZE_STRING);
                    $shop_by_categories = filter_var(trim($allDataInSheet[$i][$shop_by_categories]), FILTER_SANITIZE_STRING);
                    $images = filter_var(trim($allDataInSheet[$i][$images]), FILTER_SANITIZE_STRING);


                    if (!empty($category_id)  &&  !empty($constituency_id)) {
                        $token_data = $this->ion_auth->get_user_id();
                        $uploadedImages = explode(',', $images); //comma separated images to array


                        $id = $this->promotion_banner_model->insert([
                            'title' => empty($title) ? NULL : $title,
                            'cat_id' => empty($category_id) ? NULL : $category_id,
                            'constituency_id' => empty($constituency_id) ? NULL : $constituency_id,
                            'promotion_banner_position_id' => 4,
                            'content_type' => 4,
                            'published_on' => empty($published_date) ? NULL : $published_date,
                            'expired_on' => empty($expiry_date) ? NULL : $expiry_date,
                            'promotion_banner_discount_type_id' => empty($discount_type) ? NULL : $discount_type,
                            'discount' => empty($discount) ? NULL : $discount,
                            'max_offer_steps' => empty($offer_max_qty) ? NULL : $offer_max_qty,
                            'owner' => ($this->ion_auth->in_group('admin', $token_data)) ? 1 : 2,
                            'status' => ($this->ion_auth->in_group('admin', $token_data)) ? 1 : 0
                        ]);

                        if ($id) {
                            $image_file_name = $uploadedImages[0];
                            if (file_exists($base_path . $random_digit . '/promotions/' . $image_file_name)) {
                                if (!file_exists('uploads/' . 'promotion_banner' . '_image/')) {
                                    mkdir('uploads/' . 'promotion_banner' . '_image/', 0777, true);
                                }
                                if (file_exists('uploads/' . 'promotion_banner' . '_image/' . 'promotion_banner_' . '_' . $id . '.jpg')) {
                                    unlink('uploads/' . 'promotion_banner' . '_image/' . 'promotion_banner_' . '_' . $id . '.jpg');
                                }
                                $source_image = file_get_contents($base_path . $random_digit . '/promotions/' . $image_file_name);
                                file_put_contents('./uploads/promotion_banner_image/' . "promotion_banner_" . $id . ".jpg", $source_image);
                            }
                            if ($shop_by_categories) {
                                if (!file_exists('uploads/' . 'promotion_banner_shop_by_category' . '_image/')) {
                                    mkdir('uploads/' . 'promotion_banner_shop_by_category' . '_image/', 0777, true);
                                }
                                if (!empty($shop_by_categories)) {
                                    $shop_by_category = explode(',', $shop_by_categories);
                                    foreach ($shop_by_category as $row) {
                                        $shop_by_category_banner_id = $this->promotion_banner_shop_by_category_model->insert([
                                            'sub_cat_id' => $row,
                                            'promotion_banner_id' => $id
                                        ]);
                                        $image_file_name = $row . '.jpg';
                                        if ($shop_by_category_banner_id && file_exists($base_path . $random_digit . '/promotions/' . $image_file_name)) {
                                            $source_image = file_get_contents($base_path . $random_digit . '/promotions/' . $image_file_name);
                                            file_put_contents('./uploads/promotion_banner_shop_by_category_image/' . "promotion_banner_shop_by_category_" . $shop_by_category_banner_id . ".jpg", $source_image);
                                        }
                                        //move_uploaded_file($shop_by_category_files[$k]['tmp_name'], "./uploads/promotion_banner_shop_by_category_image/promotion_banner_shop_by_category_" . $shop_by_category_banner_id . ".jpg");
                                    }
                                }
                            }
                        }
                        $this->session->set_flashdata('upload_status', ["success" => "Offer Promotions successfully imported..!"]);
                    } else {
                        $this->session->set_flashdata('upload_status', ["error" => "Error occured at row no($i)"]);
                        $this->data['menu'] = array('vendor_id' => $vendor_id, 'sub_cat_id' => $sub_cat_id, 'name' => $name, 'desc' => $desc);
                        break;
                    }
                }
            } else {
                $this->session->set_flashdata('upload_status', ["error" => "Please import correct file, did not match excel sheet column"]);
            }
        }
    }

    public function bannerstatus($type = 'change_status')
    {
        if ($type == 'change_status') {
            $this->promotion_banner_model->update([
                'status' => ($this->input->post('is_checked') == 'true') ? 1 : 0
            ], $this->input->post('id'));
        }
    }
    public function banner_images_list($type = 'r')
    {
        if ($type == 'c') {
            $this->data['title'] = 'Add Banner Image';
            $this->data['nav_type'] = 'banner_images';
            $this->data['content'] = 'promotion_banners/add_banner_image';
            $this->data['categories'] = $this->category_model->order_by('id', 'DESC')->get_all();
            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->category_model->fields('id,name,desc')->get_all();
                $this->data['categories'] = $cat_data;
            } else {
                $w_r1 = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
                $this->data['categories'] = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
            }
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 's') {
            $this->form_validation->set_rules($this->promotion_banner_images_model->rules['create_rules']);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $id = $this->promotion_banner_images_model->insert([
                    'cat_id' => $this->input->post('cat_id'),
                    'created_user_id' => $this->ion_auth->get_user_id()
                ]);
                $path = $_FILES['file']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $this->file_up("file", "promotion_banner_suggestion", $id, '', 'no');

                redirect('banner_images/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'All Banner Images';
            $this->data['nav_type'] = 'banner_images';
            $this->data['content'] = 'promotion_banners/banner_image_list';
            $this->data['banners'] = $this->promotion_banner_images_model->fields('id, cat_id')->with_category('fields: name')->with_sub_category('fields: name')->order_by('id', 'desc')->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->promotion_banner_images_model->rules['update_rules']);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $id = $this->promotion_banner_images_model->update([
                    'cat_id' => $this->input->post('cat_id'),
                    'updated_user_id' => $this->ion_auth->get_user_id()
                ], $this->input->post('id'));

                if ($_FILES['file']['name'] !== '') {
                    if (!file_exists('uploads/' . 'promotion_banner_suggestion' . '_image/')) {
                        mkdir('uploads/' . 'promotion_banner_suggestion' . '_image/', 0777, true);
                    }

                    if (file_exists('uploads/' . 'promotion_banner_suggestion' . '_image/' . 'promotion_banner_suggestion' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'promotion_banner_suggestion' . '_image/' . 'promotion_banner_suggestion' . '_' . $this->input->post('id') . '.jpg');
                    }
                    move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'promotion_banner_suggestion' . '_image/' . 'promotion_banner_suggestion' . '_' . $this->input->post('id') . '.jpg');
                }

                redirect('banner_images/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->promotion_banner_images_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Banner Image';
            $this->data['nav_type'] = 'banner_images';
            $this->data['content'] = 'promotion_banners/edit_banner_image';
            $this->data['categories'] = $this->category_model->order_by('id', 'DESC')->get_all();
            $this->data['banners'] = $this->promotion_banner_images_model->fields('id, cat_id')->where('id', $this->input->get('id'))->get();
            $this->_render_page($this->template, $this->data);
        }
    }

    public function vendor_promotion_banners($type = 'r')
    {
        if ($type == 'r') {
            $this->data['title'] = 'Vendor Promotion Banners';
            $this->data['content'] = 'promotion_banners/venor_banners_list';
            $this->data['nav_type'] = 'vendor_promotion_banners';
            $this->data['categories'] = $this->category_model->fields('id,name,desc')->get_all();
            $this->data['banners'] = $this->promotion_banner_model
                ->fields('id, image_id,title, desc,offer_details, cat_id, sub_cat_id, brand_id, constituency_id, promotion_banner_position_id, content_type, published_on, expired_on, owner,created_user_id, accessibility, status')
                ->with_position('fields:id,title')
                ->with_category('fields:id,name')
                ->with_sub_category('fields: id,name')
                ->with_vendor_list('fields:name')
                ->with_joined_promotion_banner_payments('fields:txn_id,amount,created_at')
                ->where('owner', 2)->order_by('id', 'desc')->get_all();
            // echo "<pre>";print_r($this->data);echo "</pre>";die();

            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'view') {
            $this->data['title'] = 'Vendor Promotion Banners';
            $this->data['content'] = 'promotion_banners/venor_banners_list';
            $this->data['nav_type'] = 'vendor_promotion_banners';
            $this->data['type'] = 'vendor_promotion_banners';
            $this->data['banners'] = $this->promotion_banner_model->fields('id, image_id,title, desc, cat_id, sub_cat_id, brand_id, constituency_id, promotion_banner_position_id, content_type, published_on, expired_on, owner, accessibility, status')
                ->with_position('fields:id,title')
                ->with_vendor_list('fields:name')
                ->where('owner', 2)->order_by('id', 'desc')->get_all();
            $this->_render_page($this->template, $this->data);
        }
    }

    public function banner_cost($type = 'r', $rowno = 0)
    {

        if ($type == 'c') {
            $this->form_validation->set_rules($this->banner_cost_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Add Banner Cost';
                $this->data['content'] = 'promotion_banners/add_banner_cost';
                $this->data['nav_type'] = 'banner_cost';
                $this->data['state'] = $this->state_model->get_all();
                $this->_render_page($this->template, $this->data);
            } else {

                if ($this->input->post('district_id') == 'stateall' || $this->input->post('district_id') == '') {
                    $districtid = null;
                    $this->db->where('district_id', $districtid);
                } else {
                    $districtid = $this->input->post('district_id');
                    $this->db->where('district_id', $districtid);
                }

                if ($this->input->post('constituency_id') == 'conall' || $this->input->post('constituency_id') == '') {
                    $constid = null;
                    $this->db->where('constituency_id', $constid);
                } else {
                    $constid = $this->input->post('constituency_id');
                    $this->db->where('constituency_id', $constid);
                }

                if ($this->input->post('state_id') == 'conall' || $this->input->post('state_id') == '') {
                    $stateid = null;
                    $this->db->where('state_id', $stateid);
                } else {
                    $stateid = $this->input->post('state_id');
                    $this->db->where('state_id', $stateid);
                }



                $id = $this->banner_cost_model->insert([
                    'state_id' => $stateid,
                    'district_id' => $districtid,
                    'constituency_id' => $constid,
                    'rate' => $this->input->post('rlatrate'),
                    'banner_type' => $this->input->post('banner_type'),
                ]);
                redirect('banner_cost/r/0', 'refresh');
            }
        }
        if ($type == 'r') {
            $this->data['title'] = 'List Banner Rates';
            $this->data['content'] = 'promotion_banners/list_banner_cost';
            $this->data['nav_type'] = 'banner_cost';
            $this->data['bannerrates'] = $this->banner_cost_model->get_all();

            $this->_render_page($this->template, $this->data);
        }
        if ($type == 'u') {
            $this->form_validation->set_rules($this->banner_cost_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'update Banner Cost';
                $this->data['content'] = 'promotion_banners/edit_banner_cost';
                $this->data['nav_type'] = 'banner_cost';
                $this->data['updatearea'] = $this->banner_cost_model->get($rowno);

                $this->data['state'] = $this->state_model->get_all();

                $this->_render_page($this->template, $this->data);
            } else {

                if ($this->input->post('district_id') == "") {
                    $districtval = "null";
                } else {
                    $districtval = $this->input->post('district_id');
                }

                if ($this->input->post('constituancy_id') == "") {
                    $constituancyval = "null";
                } else {
                    $constituancyval = $this->input->post('constituancy_id');
                }

                $is_updated = $this->banner_cost_model->update([
                    'id' => $this->input->post('id'),
                    'state_id' => $this->input->post('state_id'),
                    'district_id' => $districtval,
                    'constituency_id' => $constituancyval,
                    'banner_type' => $this->input->post('banner_type'),
                    'rate' => $this->input->post('rlatrate'),
                ], 'id');

                redirect('banner_cost/r/0', 'refresh');
            }
        }
        if ($type == 'd') {
            $this->banner_cost_model->delete([
                'id' => $rowno
            ]);
            redirect('banner_cost/r/0', 'refresh');
        }
    }
    public function admin_banners_list($type = 'r')
    {
        if ($type == 'c') {
            $this->data['title'] = 'Add Banner Image';
            $this->data['nav_type'] = 'banner_images';
            $this->data['content'] = 'admin_banners/add_admin_banner';
            $this->data['positions'] = $this->promotion_banner_position_model->order_by('id', 'DESC')->get_all();
            if ($this->ion_auth->is_admin()) {
                $cat_data = $this->promotion_banner_position_model->fields('id,title')->get_all();
                $this->data['positions'] = $cat_data;
            }
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 's') {
            $this->form_validation->set_rules($this->admin_banners_model->rules['addmin_banner']);
            if (empty($_FILES['image']['name'])) {
                $this->form_validation->set_rules('image', 'Upload Image', 'required',array('required' => 'Please Upload an Image'));
            }
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Add Banner Image';
                $this->data['content'] = 'admin_banners/add_admin_banner';
                $this->data['nav_type'] = 'banner_images';
                $this->data['positions'] = $this->promotion_banner_position_model->order_by('id', 'DESC')->get_all();
                $this->_render_page($this->template, $this->data);
            }  else {
                $config = array(
                    'upload_path' => "./uploads/admin_banners/",
                    'allowed_types' => "jpg|png|jpeg|gif",
                    'max_size' => "1024000", // file size , here it is 1 MB(1024 Kb)
                );
                $this->load->library('upload', $config);

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, TRUE);
                }
                if ($this->upload->do_upload('image')) {

                    $this->admin_banners_model->insert([
                        'status' => $this->input->post('status'),
                        'promotion_banner_position_id' => $_POST['promotion_banner_position_id'],
                        'banner_image' => $this->upload->data('file_name')
                    ], 'id');
                } else {
                    $this->admin_banners_model->insert([
                        'status' => $this->input->post('status'),
                        'promotion_banner_position_id' => $_POST['promotion_banner_position_id'],
                    ], 'id');
                }

                redirect('admin_banners/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'All Admin Banners';
            $this->data['nav_type'] = 'admin_banners';
            $this->data['content'] = 'admin_banners/admin_banners_list';
            $this->data['banners'] = $this->admin_banners_model->fields('id, promotion_banner_position_id, banner_image, status')->with_position('fields: title')->order_by('id', 'desc')->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $banners = $this->admin_banners_model->fields('id, promotion_banner_position_id, banner_image')->where('id', $this->input->post('id'))->get();
            $config = array(
                'upload_path' => "./uploads/admin_banners/",
                'allowed_types' => "jpg|png|jpeg|gif",
                'max_size' => "1024000", // file size , here it is 1 MB(1024 Kb)
            );
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('image')) {
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, TRUE);
                }
                if (file_exists($config['upload_path'] . $banners['banner_image'])) {
                    unlink($config['upload_path'] . $banners['banner_image']);
                }
                $id = $this->admin_banners_model->update([
                    'id' => $this->input->post('id'),
                    'promotion_banner_position_id' => $this->input->post('promotion_banner_position_id'),
                    'banner_image' => $this->upload->data('file_name'),
                    'status' => $this->input->post('pos_status'),
                ], 'id');
            } else {

                $id = $this->admin_banners_model->update([
                    'id' => $this->input->post('id'),
                    'promotion_banner_position_id' => $this->input->post('promotion_banner_position_id'),
                    'status' => $this->input->post('pos_status'),
                ], 'id');
            }
            redirect('admin_banners/r', 'refresh');
        } elseif ($type == 'd') {
            $banners = $this->admin_banners_model->fields('id, promotion_banner_position_id, banner_image')->where('id', $this->input->get('id'))->get();
            $this->admin_banners_model->delete([
                'id' => $this->input->post('id')
            ]);
            $upload_path = "./uploads/admin_banners/" . $banners['banner_image'];
            unlink($upload_path);
        } elseif ($type == 'admin_banner_status') {
            $this->admin_banners_model->update([
                'status' => ($this->input->post('is_checked') == 'true') ? 1 : 0
            ], $this->input->post('id'));
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Banner Image';
            $this->data['nav_type'] = 'admin_banners';
            $this->data['content'] = 'admin_banners/edit_banner_image';
            $this->data['positions'] = $this->promotion_banner_position_model->order_by('id', 'DESC')->get_all();
            $this->data['banners'] = $this->admin_banners_model->fields('id, promotion_banner_position_id, banner_image, status')->where('id', $this->input->get('id'))->get();
            $this->_render_page($this->template, $this->data);
        }
    }
}
