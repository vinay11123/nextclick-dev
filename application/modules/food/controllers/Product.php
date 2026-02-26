<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Product extends MY_Controller
{

    function __construct()
    {
        error_reporting(E_ERROR | E_PARSE);
        parent::__construct();
        $this->template = 'template/admin/main';
        if (!$this->ion_auth->logged_in())
            redirect('auth/login');

        $this->load->model('food_item_model');
        $this->load->model('food_section_model');
        $this->load->model('food_sec_item_model');
        $this->load->model('user_model');
        $this->load->model('vendor_list_model');
        $this->load->model('food_item_image_model');
        $this->load->model('sub_category_model');
        $this->load->model('food_menu_model');
    }

    /**
     * @desc To update products from excel sheet along with images zip
     * @author Mehar
     * @dt 30/08/2021
     */
    public function product_bulk_upload()
    {
        if (isset($_POST['import'])) {
            $zip_file = $_FILES['images_zip'];
            $excel_file = $_FILES['excel_file'];
            $base_path = dirname(BASEPATH) . '/uploads/products_zip/';
            if (!file_exists($base_path)) {
                mkdir($base_path, 0777, true);
            }

            $random_digit = time();
            $new_file_name = $random_digit . ".zip";
            mkdir($base_path . $random_digit, 0777, true);

            $zip_file_path = $base_path . $random_digit . '/' . $new_file_name;
            if (isset($zip_file['tmp_name']) && copy($zip_file['tmp_name'], $zip_file_path)) {
                // zip extraction
                $zip = new ZipArchive();
                if ($zip->open($zip_file_path) === TRUE) {
                    $zip->extractTo($base_path . $random_digit);
                    $zip->close();
                    $is_updated = $this->upload_excel_with_images($excel_file, $base_path, $random_digit);
                } else {
                    $this->session->set_flashdata('upload_status', ["error" => "Unable to extract ZIP"]);
                }
            } else {
                $this->session->set_flashdata('upload_status', ["error" => "Uploading ZIP is failed"]);
            }
        }
        $this->data['title'] = 'Product bulk upload';
        $this->data['content'] = 'food/product/upload_page';
        $this->data['nav_type'] = 'product_upload';
        $this->_render_page($this->template, $this->data);
    }

    public function upload_excel_with_images($file, $base_path, $random_digit)
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
            $createArray = array('category_name', 'sub_category_name', 'menu_name', 'brand_name', 'product_type', 'product_name', 'product_desc', 'availability', 'status', 'varinat_name', 'varinat_price', 'variant_weight', 'image_name');
            $makeArray = array('category_name' => 'category_name', 'sub_category_name' => 'sub_category_name', 'menu_name' => 'menu_name', 'brand_name' => 'brand_name', 'product_type' => 'product_type', 'product_name' => 'product_name', 'product_desc' => 'product_desc', 'availability' => 'availability', 'status' => 'status', 'varinat_name' => 'varinat_name', 'varinat_price' => 'varinat_price', 'variant_weight' => 'variant_weight', 'image_name' => 'image_name');
            $SheetDataKey = array();

            foreach ($allDataInSheet as $dataInSheet) {
                foreach ($dataInSheet as $key => $value) {
                    if (in_array(trim($value), $createArray)) {
                        $value = preg_replace('/\s+/', '', $value);
                        $SheetDataKey[trim($value)] = $key;
                    }
                }
            }
            //print_array($SheetDataKey);
            $dataDiff = array_diff_key($makeArray, $SheetDataKey);
            if (empty($dataDiff)) {
                $flag = 1;
            }
            // match excel sheet column
            if ($flag == 1) {
                $k = 0;
                $error_cat = "<ul>";
                $error_sub_cat = "<ul>";
                $error_sub_cat_type = "<ul>";
                $error_menu = "<ul>";
                $error_brand = "<ul>";
                $error_product_variation = "<ul>";
                $error_product_type = "<ul>";
                $error_file = "<ul>";
                $error_empty_row_numbers = "<ul>";
                $item_id = '';
                $p_type = '';
                $create_record = '';
                $categorie = array();
                $sub_categorie = array();
                $menu = array();
                $brand = array();
                $product_variation_duplicate = array();
                $product_duplicate = array();
                for ($i = 2; $i <= $arrayCount; $i++) {

                    $category_name = $SheetDataKey['category_name'];
                    $sub_category_name = $SheetDataKey['sub_category_name'];
                    $menu_name = $SheetDataKey['menu_name'];
                    $brand_name = $SheetDataKey['brand_name'];
                    $product_type = $SheetDataKey['product_type'];
                    $product_name = $SheetDataKey['product_name'];
                    $product_desc = $SheetDataKey['product_desc'];
                    $availability = $SheetDataKey['availability'];
                    $status = $SheetDataKey['status'];
                    $varinat_name = $SheetDataKey['varinat_name'];
                    $varinat_price = $SheetDataKey['varinat_price'];
                    $variant_weight = $SheetDataKey['variant_weight'];
                    $image_name = $SheetDataKey['image_name'];

                    $category_name = filter_var(trim($allDataInSheet[$i][$category_name]), FILTER_SANITIZE_STRING);
                    $sub_category_name = filter_var(trim($allDataInSheet[$i][$sub_category_name]), FILTER_SANITIZE_STRING);
                    $menu_name = filter_var(trim($allDataInSheet[$i][$menu_name]), FILTER_SANITIZE_STRING);
                    $brand_name = filter_var(trim($allDataInSheet[$i][$brand_name]), FILTER_SANITIZE_STRING);
                    $product_type = filter_var(trim($allDataInSheet[$i][$product_type]), FILTER_SANITIZE_STRING);
                    $product_name = filter_var(trim($allDataInSheet[$i][$product_name]), FILTER_SANITIZE_STRING);
                    $product_desc = filter_var(trim($allDataInSheet[$i][$product_desc]), FILTER_SANITIZE_STRING);
                    $availability = filter_var(trim($allDataInSheet[$i][$availability]), FILTER_SANITIZE_STRING);
                    $status = filter_var(trim($allDataInSheet[$i][$status]), FILTER_SANITIZE_STRING);
                    $varinat_name = filter_var(trim($allDataInSheet[$i][$varinat_name]), FILTER_SANITIZE_STRING);
                    $varinat_price = filter_var(trim($allDataInSheet[$i][$varinat_price]), FILTER_SANITIZE_STRING);
                    $variant_weight = filter_var(trim($allDataInSheet[$i][$variant_weight]), FILTER_SANITIZE_STRING);
                    $image_name = filter_var(trim($allDataInSheet[$i][$image_name]), FILTER_SANITIZE_STRING);


                    $this->db->select("*");
                    $this->db->from("categories");
                    $this->db->where('name', $category_name);
                    $categorie = $this->db->get('')->result_array();

                    $this->db->select("*");
                    $this->db->from("sub_categories");
                    $this->db->where('cat_id', $categorie[0]['id']);
                    $this->db->where('name', $sub_category_name);
                    $this->db->where('type', 2);
                    $sub_categorie = $this->db->get('')->result_array();

                    $this->db->select("*");
                    $this->db->from("food_menu");
                    $this->db->where('sub_cat_id', $sub_categorie[0]['id']);
                    $this->db->where('name', $menu_name);
                    $menu = $this->db->get('')->result_array();

                    $this->db->select("*");
                    $this->db->from("brands");
                    $this->db->where('name', $brand_name);
                    $brand = $this->db->get('')->result_array();

                    if ($product_type != '' && strtolower($product_type) == 'veg') {
                        $p_type = 1;
                    } else if ($product_type != '' && strtolower($product_type) == 'non veg') {
                        $p_type = 2;
                    } else if ($product_type != '' && strtolower($product_type) == 'other') {
                        $p_type = 3;
                    }

                    $this->db->select("*");
                    $this->db->from("food_item");
                    $this->db->where('food_item.sub_cat_id', $sub_categorie[0]['id']);
                    $this->db->where('food_item.menu_id', $menu[0]['id']);
                    $this->db->where('food_item.brand_id', $brand[0]['id']);
                    $this->db->where('food_item.item_type', $p_type);
                    $this->db->where('food_item.name', $product_name);
                    $product_duplicate = $this->db->get('')->result_array();

                    $this->db->select("*");
                    $this->db->from("food_item");
                    $this->db->join('food_sec_item', 'food_sec_item.item_id = food_item.id');
                    $this->db->where('food_item.sub_cat_id', $sub_categorie[0]['id']);
                    $this->db->where('food_item.menu_id', $menu[0]['id']);
                    $this->db->where('food_item.brand_id', $brand[0]['id']);
                    $this->db->where('food_item.item_type', $p_type);
                    $this->db->where('food_item.name', $product_name);
                    $this->db->where('food_sec_item.name', $varinat_name);
                    $product_variation_duplicate = $this->db->get('')->result_array();


                    $sounds_like = $this->sounds_like($product_name, $sub_categorie[0]['id'], $menu[0]['id']);

                    if (!empty($category_name)  &&  !empty($sub_category_name) &&  !empty($menu_name) &&  !empty($brand_name) &&  !empty($product_type) &&  !empty($product_name) &&  !empty($product_desc) &&  !empty($availability) &&  !empty($status) &&  !empty($varinat_name) &&  !empty($varinat_price) &&  !empty($variant_weight) &&  !empty($image_name)) {
                        if (count($categorie) > 0) {
                            if (count($sub_categorie) > 0) {
                                if ($sub_categorie[0]['type'] == 2) {
                                    if (count($menu) > 0) {
                                        if (count($brand) > 0) {
                                            if (count($product_variation_duplicate) == 0) {
                                                if (strtolower($product_type) == 'veg' || strtolower($product_type) == 'non veg' || strtolower($product_type) == 'other') {
                                                    if (file_exists($base_path . $random_digit . '/' . $image_name)) {
                                                        $create_record = true;
                                                    } else {
                                                        $create_record = false;
                                                        $error_file .= "<li>Row No: $i ---Please check image name</li>";
                                                        $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_file"]);
                                                    }
                                                } else {
                                                    $create_record = false;
                                                    $error_product_type .= "<li>Row No: $i ---product type must be Veg/Non veg/Others</li>";
                                                    $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_product_type"]);
                                                }
                                            } else {
                                                $create_record = false;
                                                $error_product_variation .= "<li>Row No: $i ---variation name $varinat_name already exist for this product $product_name</li>";
                                                $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_product_variation"]);
                                            }
                                        } else {
                                            $create_record = false;
                                            $error_brand .= "<li>Row No: $i ---menu name $brand_name does not exist</li>";
                                            $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_brand"]);
                                        }
                                    } else {
                                        $create_record = false;
                                        $error_menu .= "<li>Row No: $i ---menu name $menu_name does not exist for this sub category name $sub_category_name</li>";
                                        $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_menu"]);
                                    }
                                } else {
                                    $create_record = false;
                                    $error_sub_cat_type .= "<li>Row No: $i ---add Shop By Category Product sub category only</li>";
                                    $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_sub_cat_type"]);
                                }
                            } else {
                                $create_record = false;
                                $error_sub_cat .= "<li>Row No: $i ---sub category name $sub_category_name does not exist for this category name $category_name</li>";
                                $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_sub_cat"]);
                            }
                        } else {
                            $create_record = false;
                            $error_cat .= "<li>Row No: $i ---category name $category_name does not exist</li>";
                            $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_cat"]);
                        }
                    } else {
                        $create_record = false;
                        $error_empty_row_numbers .= "<li>Row No: $i </li>";
                        $this->session->set_flashdata('upload_status', ["error" => "Empty filds occured at $error_empty_row_numbers"]);
                        $this->data['vendor'] = array('category_name' => $category_name, 'sub_category_name' => $sub_category_name, 'menu_name' => $menu_name, 'brand_name' => $brand_name, 'product_name' => $product_name, 'product_desc' => $product_desc, 'availability' => $availability, 'status' => $status, 'varinat_name' => $varinat_name, 'varinat_price' => $varinat_price, 'variant_weight,' => $variant_weight, 'image_name,' => $image_name);
                    }
                }
                if ($create_record == true) {
                    for ($i = 2; $i <= $arrayCount; $i++) {

                        $category_name = $SheetDataKey['category_name'];
                        $sub_category_name = $SheetDataKey['sub_category_name'];
                        $menu_name = $SheetDataKey['menu_name'];
                        $brand_name = $SheetDataKey['brand_name'];
                        $product_type = $SheetDataKey['product_type'];
                        $product_name = $SheetDataKey['product_name'];
                        $product_desc = $SheetDataKey['product_desc'];
                        $availability = $SheetDataKey['availability'];
                        $status = $SheetDataKey['status'];
                        $varinat_name = $SheetDataKey['varinat_name'];
                        $varinat_price = $SheetDataKey['varinat_price'];
                        $variant_weight = $SheetDataKey['variant_weight'];
                        $image_name = $SheetDataKey['image_name'];

                        $category_name = filter_var(trim($allDataInSheet[$i][$category_name]), FILTER_SANITIZE_STRING);
                        $sub_category_name = filter_var(trim($allDataInSheet[$i][$sub_category_name]), FILTER_SANITIZE_STRING);
                        $menu_name = filter_var(trim($allDataInSheet[$i][$menu_name]), FILTER_SANITIZE_STRING);
                        $brand_name = filter_var(trim($allDataInSheet[$i][$brand_name]), FILTER_SANITIZE_STRING);
                        $product_type = filter_var(trim($allDataInSheet[$i][$product_type]), FILTER_SANITIZE_STRING);
                        $product_name = filter_var(trim($allDataInSheet[$i][$product_name]), FILTER_SANITIZE_STRING);
                        $product_desc = filter_var(trim($allDataInSheet[$i][$product_desc]), FILTER_SANITIZE_STRING);
                        $availability = filter_var(trim($allDataInSheet[$i][$availability]), FILTER_SANITIZE_STRING);
                        $status = filter_var(trim($allDataInSheet[$i][$status]), FILTER_SANITIZE_STRING);
                        $varinat_name = filter_var(trim($allDataInSheet[$i][$varinat_name]), FILTER_SANITIZE_STRING);
                        $varinat_price = filter_var(trim($allDataInSheet[$i][$varinat_price]), FILTER_SANITIZE_STRING);
                        $variant_weight = filter_var(trim($allDataInSheet[$i][$variant_weight]), FILTER_SANITIZE_STRING);
                        $image_name = filter_var(trim($allDataInSheet[$i][$image_name]), FILTER_SANITIZE_STRING);


                        $this->db->select("*");
                        $this->db->from("categories");
                        $this->db->where('name', $category_name);
                        $categorie = $this->db->get('')->result_array();

                        $this->db->select("*");
                        $this->db->from("sub_categories");
                        $this->db->where('cat_id', $categorie[0]['id']);
                        $this->db->where('name', $sub_category_name);
                        $this->db->where('type', 2);
                        $sub_categorie = $this->db->get('')->result_array();

                        $this->db->select("*");
                        $this->db->from("food_menu");
                        $this->db->where('sub_cat_id', $sub_categorie[0]['id']);
                        $this->db->where('name', $menu_name);
                        $menu = $this->db->get('')->result_array();

                        $this->db->select("*");
                        $this->db->from("brands");
                        $this->db->where('name', $brand_name);
                        $brand = $this->db->get('')->result_array();

                        if ($product_type != '' && strtolower($product_type) == 'veg') {
                            $p_type = 1;
                        } else if ($product_type != '' && strtolower($product_type) == 'non veg') {
                            $p_type = 2;
                        } else if ($product_type != '' && strtolower($product_type) == 'other') {
                            $p_type = 3;
                        }

                        $this->db->select("*");
                        $this->db->from("food_item");
                        $this->db->where('food_item.sub_cat_id', $sub_categorie[0]['id']);
                        $this->db->where('food_item.menu_id', $menu[0]['id']);
                        $this->db->where('food_item.brand_id', $brand[0]['id']);
                        $this->db->where('food_item.item_type', $p_type);
                        $this->db->where('food_item.name', $product_name);
                        $product_duplicate = $this->db->get('')->result_array();

                        $this->db->select("*");
                        $this->db->from("food_item");
                        $this->db->join('food_sec_item', 'food_sec_item.item_id = food_item.id');
                        $this->db->where('food_item.sub_cat_id', $sub_categorie[0]['id']);
                        $this->db->where('food_item.menu_id', $menu[0]['id']);
                        $this->db->where('food_item.brand_id', $brand[0]['id']);
                        $this->db->where('food_item.item_type', $p_type);
                        $this->db->where('food_item.name', $product_name);
                        $this->db->where('food_sec_item.name', $varinat_name);
                        $product_variation_duplicate = $this->db->get('')->result_array();


                        $sounds_like = $this->sounds_like($product_name, $sub_categorie[0]['id'], $menu[0]['id']);
                        if (!empty($category_name)  &&  !empty($sub_category_name) &&  !empty($menu_name) &&  !empty($brand_name) &&  !empty($product_type) &&  !empty($product_name) &&  !empty($product_desc) &&  !empty($availability) &&  !empty($status) &&  !empty($varinat_name) &&  !empty($varinat_price) &&  !empty($variant_weight) &&  !empty($image_name)) {
                            if (count($categorie) > 0) {
                                if (count($sub_categorie) > 0) {
                                    if ($sub_categorie[0]['type'] == 2) {
                                        if (count($menu) > 0) {
                                            if (count($brand) > 0) {
                                                if (count($product_variation_duplicate) == 0) {
                                                    if (strtolower($product_type) == 'veg' || strtolower($product_type) == 'non veg' || strtolower($product_type) == 'other') {
                                                        if (file_exists($base_path . $random_digit . '/' . $image_name)) {
                                                            if (count($product_duplicate) == 0) {
                                                                $item_id = $this->food_item_model->insert([
                                                                    'sub_cat_id' => $sub_categorie[0]['id'],
                                                                    'menu_id' => $menu[0]['id'],
                                                                    'brand_id' => $brand[0]['id'],
                                                                    'product_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                                                                    'item_type' => $p_type,
                                                                    'name' => $product_name,
                                                                    'desc' => $product_desc,
                                                                    'sounds_like' => $sounds_like,
                                                                    'availability' => 1,
                                                                    'status' => 1
                                                                ]);
                                                            } else {
                                                                $item_id = $product_duplicate[0]['id'];
                                                            }
                                                            if ($item_id) {
                                                                if (count($product_duplicate) == 0) {
                                                                    $section_id = $this->food_section_model->insert([
                                                                        'menu_id' => $menu[0]['id'],
                                                                        'item_id' => $item_id,
                                                                        'name' => $product_name
                                                                    ]);
                                                                }
                                                                $this->food_sec_item_model->insert([
                                                                    'menu_id' => $menu[0]['id'],
                                                                    'item_id' => $item_id,
                                                                    'sec_id' => $section_id,
                                                                    'section_item_code' => implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4)),
                                                                    'name' => $varinat_name,
                                                                    'price' => $varinat_price,
                                                                    'weight' => $variant_weight,
                                                                    'status' => 1
                                                                ]);
                                                                if (count($product_duplicate) == 0) {
                                                                    $file_name = str_replace(array("#", "'", ";", "@[^0-9a-zA-Z.]+"), '', $product_name);
                                                                    $space_file_name = str_replace('  ', ' ', $file_name);
                                                                    $image_file_name = strtolower(str_replace(' ', '_', $space_file_name)) . '.jpg';
                                                                    if (file_exists($base_path . $random_digit . '/' . $image_name)) {
                                                                        $product_image_id = $this->food_item_image_model->insert([
                                                                            'item_id' => $item_id,
                                                                            'serial_number' => 1,
                                                                            'ext' => 'jpg'
                                                                        ]);
                                                                        if (!file_exists('./uploads/food_item_image/')) {
                                                                            mkdir('./uploads/food_item_image/', 0777, true);
                                                                        }
                                                                        $source_image = file_get_contents($base_path . $random_digit . '/' . $image_name);
                                                                        file_put_contents('./uploads/food_item_image/' . "food_item_" . $product_image_id . ".jpg", $source_image);
                                                                    }
                                                                }
                                                            }
                                                            $this->session->set_flashdata('upload_status', ["success" => "Products successfully imported..!"]);
                                                        } else {
                                                            $error_file .= "<li>Row No: $i ---Please check image name</li>";
                                                            $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_file"]);
                                                        }
                                                    } else {
                                                        $error_product_type .= "<li>Row No: $i ---product type must be Veg/Non veg/Others</li>";
                                                        $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_product_type"]);
                                                    }
                                                } else {
                                                    $error_product_variation .= "<li>Row No: $i ---variation name $varinat_name already exist for this product $product_name</li>";
                                                    $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_product_variation"]);
                                                }
                                            } else {
                                                $error_brand .= "<li>Row No: $i ---brand name $brand_name does not exist</li>";
                                                $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_brand"]);
                                            }
                                        } else {
                                            $error_menu .= "<li>Row No: $i ---menu name $menu_name does not exist for this sub category name $sub_category_name</li>";
                                            $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_menu"]);
                                        }
                                    } else {
                                        $create_record = false;
                                        $error_sub_cat_type .= "<li>Row No: $i ---add Shop By Category Product sub category only</li>";
                                        $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_sub_cat_type"]);
                                    }
                                } else {
                                    $error_sub_cat .= "<li>Row No: $i ---sub category name $sub_category_name does not exist for this category name $category_name</li>";
                                    $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_sub_cat"]);
                                }
                            } else {
                                $error_cat .= "<li>Row No: $i ---category name $category_name does not exist</li>";
                                $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_cat"]);
                            }
                        } else {
                            $error_empty_row_numbers .= "<li>Row No: $i </li>";
                            $this->session->set_flashdata('upload_status', ["error" => "Empty filds occured at $error_empty_row_numbers"]);
                            $this->data['vendor'] = array('category_name' => $category_name, 'sub_category_name' => $sub_category_name, 'menu_name' => $menu_name, 'brand_name' => $brand_name, 'product_name' => $product_name, 'product_desc' => $product_desc, 'availability' => $availability, 'status' => $status, 'varinat_name' => $varinat_name, 'varinat_price' => $varinat_price, 'variant_weight,' => $variant_weight, 'image_name,' => $image_name);
                        }
                    }
                }

                $error_cat .= "</ul>";
                $error_sub_cat .= "</ul>";
                $error_sub_cat_type .= "</ul>";
                $error_menu .= "</ul>";
                $error_brand .= "</ul>";
                $error_product_type .= "</ul>";
                $error_product_variation .= "</ul>";
                $error_file .= "</ul>";
                $error_empty_row_numbers .= "</ul>";
            } else {
                $this->session->set_flashdata('upload_status', ["error" => "Please import correct file, did not match excel sheet column"]);
            }
        }
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
}
