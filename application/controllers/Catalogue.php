<?php
error_reporting(E_ERROR | E_PARSE);

class Catalogue extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->template = 'template/admin/main';

        if (!$this->ion_auth->logged_in())
            redirect('auth/login');

        $this->load->model('vendor_list_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('vendor_leads_model');
        $this->load->model('user_model');
        $this->load->model('pickupcategory_model');
    }

    public function catalogue_upload()
    {
        $this->data['users_groups']           =   $this->ion_auth->get_users_groups()->result();
        $this->data['users_permissions']      =   $this->ion_auth_acl->build_acl();
        $this->data['title'] = 'Catalogue';
        $this->data['content'] = 'catalogue';
        $this->data['nav_type'] = 'catalogue';
        $query = $this->db->query("SELECT u.id vendor_id,u.first_name vendor_name,vl.business_name vendor_business_name,u.phone vendor_phone_no FROM `vendors_list` vl
        join users u on u.id=vl.vendor_user_id
        where 1;");
        $this->data['vendor_lists'] = $query->result_array();
        if (isset($_POST['import'])) {
            $excel_file = $_FILES['excel_file'];
            $vendor_id = $_POST['vendor_id'];
            $is_updated = $this->upload_catalogue($excel_file, $vendor_id);
        }

        $this->_render_page($this->template, $this->data);
    }

    public function upload_catalogue($file, $vendor_id)
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
            $createArray = array('category_name', 'sub_category_name', 'menu_name', 'brand_name', 'product_type', 'product_name', 'varinat_name', 'price', 'discount', 'stock', 'tax');
            $makeArray = array('category_name' => 'category_name', 'sub_category_name' => 'sub_category_name', 'menu_name' => 'menu_name', 'brand_name' => 'brand_name', 'product_type' => 'product_type', 'product_name' => 'product_name', 'varinat_name' => 'varinat_name', 'price' => 'price', 'discount' => 'discount', 'stock' => 'stock', 'tax' => 'tax');
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
                $error_vendor_product_variation = "<ul>";
                $error_product_variation = "<ul>";
                $error_product = "<ul>";
                $error_brand = "<ul>";
                $error_menu = "<ul>";
                $error_sub_cat = "<ul>";
                $error_vendor_cat = "<ul>";
                $error_cat = "<ul>";
                $stocks_error = "<ul>";
                $price_error = "<ul>";
                $success_menu = "<ul>";
                $create_record = '';
                $price = '';
                $discount = '';
                $stock = '';
                $tax_id = '';
                $tax = '';
                for ($i = 2; $i <= $arrayCount; $i++) {

                    $category_name = $SheetDataKey['category_name'];
                    $sub_category_name = $SheetDataKey['sub_category_name'];
                    $menu_name = $SheetDataKey['menu_name'];
                    $brand_name = $SheetDataKey['brand_name'];
                    $product_type = $SheetDataKey['product_type'];
                    $product_name = $SheetDataKey['product_name'];
                    $varinat_name = $SheetDataKey['varinat_name'];
                    $price = $SheetDataKey['price'];
                    $discount = $SheetDataKey['discount'];
                    $stock = $SheetDataKey['stock'];
                    $tax = $SheetDataKey['tax'];

                    $category_name = filter_var(trim($allDataInSheet[$i][$category_name]), FILTER_SANITIZE_STRING);
                    $sub_category_name = filter_var(trim($allDataInSheet[$i][$sub_category_name]), FILTER_SANITIZE_STRING);
                    $menu_name = filter_var(trim($allDataInSheet[$i][$menu_name]), FILTER_SANITIZE_STRING);
                    $brand_name = filter_var(trim($allDataInSheet[$i][$brand_name]), FILTER_SANITIZE_STRING);
                    $product_type = filter_var(trim($allDataInSheet[$i][$product_type]), FILTER_SANITIZE_STRING);
                    $product_name = filter_var(trim($allDataInSheet[$i][$product_name]), FILTER_SANITIZE_STRING);
                    $varinat_name = filter_var(trim($allDataInSheet[$i][$varinat_name]), FILTER_SANITIZE_STRING);
                    $price = filter_var(trim($allDataInSheet[$i][$price]), FILTER_SANITIZE_STRING);
                    $discount = filter_var(trim($allDataInSheet[$i][$discount]), FILTER_SANITIZE_STRING);
                    $stock = filter_var(trim($allDataInSheet[$i][$stock]), FILTER_SANITIZE_STRING);
                    $tax = filter_var(trim($allDataInSheet[$i][$tax]), FILTER_SANITIZE_STRING);


                    if ($price != '' && $stock != '') {
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
                        $product = $this->db->get('')->result_array();

                        $this->db->select("*");
                        $this->db->from("food_item");
                        $this->db->join('food_sec_item', 'food_sec_item.item_id = food_item.id');
                        $this->db->where('food_item.sub_cat_id', $sub_categorie[0]['id']);
                        $this->db->where('food_item.menu_id', $menu[0]['id']);
                        $this->db->where('food_item.brand_id', $brand[0]['id']);
                        $this->db->where('food_item.item_type', $p_type);
                        $this->db->where('food_item.name', $product_name);
                        $this->db->where('food_sec_item.name', $varinat_name);
                        $product_variation = $this->db->get('')->result_array();

                        $this->db->select("*");
                        $this->db->from("vendor_product_variants");
                        $this->db->where('item_id', $product_variation[0]['item_id']);
                        $this->db->where('section_id', $product_variation[0]['sec_id']);
                        $this->db->where('section_item_id', $product_variation[0]['id']);
                        $this->db->where('vendor_user_id', $vendor_id);
                        $vendor_product_variation = $this->db->get('')->result_array();

                        if ($tax == '') {
                            $tax = 'Nill';
                        } else {
                            $tax = $tax;
                        }
                        $this->db->select("*");
                        $this->db->from("taxes");
                        $this->db->where('tax', $tax . '%');
                        $taxes = $this->db->get('')->result_array();

                        $vendor = $this->vendor_list_model->where('vendor_user_id', $vendor_id)->get();

                        if (count($categorie) > 0) {
                            if ($vendor['category_id'] == $categorie[0]['id']) {
                                if (count($sub_categorie) > 0) {
                                    if (count($menu) > 0) {
                                        if (count($brand) > 0) {
                                            if (count($product) > 0) {
                                                if (count($product_variation) > 0) {
                                                    if (count($vendor_product_variation) == 0) {
                                                        $create_record = true;
                                                    } else {
                                                        $create_record = false;
                                                        $error_vendor_product_variation .= "<li>Row No: $i ---vendor have this product name $product_name</li>";
                                                        $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_vendor_product_variation"]);
                                                    }
                                                } else {
                                                    $create_record = false;
                                                    $error_product_variation .= "<li>Row No: $i ---variation name $varinat_name does not exist for this product name $product_name</li>";
                                                    $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_product_variation"]);
                                                }
                                            } else {
                                                $create_record = false;
                                                $error_product .= "<li>Row No: $i ---product name $product_name does not exist</li>";
                                                $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_product"]);
                                            }
                                        } else {
                                            $create_record = false;
                                            $error_brand .= "<li>Row No: $i ---brand name $brand_name does not exist</li>";
                                            $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_brand"]);
                                        }
                                    } else {
                                        $create_record = false;
                                        $error_menu .= "<li>Row No: $i ---menu name $menu_name does not exist for this sub category name $sub_category_name</li>";
                                        $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_menu"]);
                                    }
                                } else {
                                    $create_record = false;
                                    $error_sub_cat .= "<li>Row No: $i ---sub category name $sub_category_name does not exist for this category name $category_name</li>";
                                    $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_sub_cat"]);
                                }
                            } else {
                                $create_record = false;
                                $error_vendor_cat .= "<li>Row No: $i ---category name $category_name does not exist for this Vendor " . $vendor['business_name'] . "</li>";
                                $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_vendor_cat"]);
                            }
                        } else {
                            $create_record = false;
                            $error_cat .= "<li>Row No: $i ---category name $category_name does not exist</li>";
                            $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_cat"]);
                        }
                    } elseif ($price != '' && $stock == '') {
                        $create_record = false;
                        $stocks_error .= "<li>Row No: $i ---Stocks field is empty.</li>";
                        $this->session->set_flashdata('upload_status', ["error" => $stocks_error]);
                    } elseif ($price == '' && $stock != '') {
                        $create_record = false;
                        $price_error .= "<li>Row No: $i ---Price field is empty</li>";
                        $this->session->set_flashdata('upload_status', ["error" => $price_error]);
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
                        $varinat_name = $SheetDataKey['varinat_name'];
                        $price = $SheetDataKey['price'];
                        $discount = $SheetDataKey['discount'];
                        $stock = $SheetDataKey['stock'];
                        $tax = $SheetDataKey['tax'];

                        $category_name = filter_var(trim($allDataInSheet[$i][$category_name]), FILTER_SANITIZE_STRING);
                        $sub_category_name = filter_var(trim($allDataInSheet[$i][$sub_category_name]), FILTER_SANITIZE_STRING);
                        $menu_name = filter_var(trim($allDataInSheet[$i][$menu_name]), FILTER_SANITIZE_STRING);
                        $brand_name = filter_var(trim($allDataInSheet[$i][$brand_name]), FILTER_SANITIZE_STRING);
                        $product_type = filter_var(trim($allDataInSheet[$i][$product_type]), FILTER_SANITIZE_STRING);
                        $product_name = filter_var(trim($allDataInSheet[$i][$product_name]), FILTER_SANITIZE_STRING);
                        $varinat_name = filter_var(trim($allDataInSheet[$i][$varinat_name]), FILTER_SANITIZE_STRING);
                        $price = filter_var(trim($allDataInSheet[$i][$price]), FILTER_SANITIZE_STRING);
                        $discount = filter_var(trim($allDataInSheet[$i][$discount]), FILTER_SANITIZE_STRING);
                        $stock = filter_var(trim($allDataInSheet[$i][$stock]), FILTER_SANITIZE_STRING);
                        $tax = filter_var(trim($allDataInSheet[$i][$tax]), FILTER_SANITIZE_STRING);


                        if ($price != '' && $stock != '') {
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
                            $product = $this->db->get('')->result_array();

                            $this->db->select("*");
                            $this->db->from("food_item");
                            $this->db->join('food_sec_item', 'food_sec_item.item_id = food_item.id');
                            $this->db->where('food_item.sub_cat_id', $sub_categorie[0]['id']);
                            $this->db->where('food_item.menu_id', $menu[0]['id']);
                            $this->db->where('food_item.brand_id', $brand[0]['id']);
                            $this->db->where('food_item.item_type', $p_type);
                            $this->db->where('food_item.name', $product_name);
                            $this->db->where('food_sec_item.name', $varinat_name);
                            $product_variation = $this->db->get('')->result_array();

                            $this->db->select("*");
                            $this->db->from("vendor_product_variants");
                            $this->db->where('item_id', $product_variation[0]['item_id']);
                            $this->db->where('section_id', $product_variation[0]['sec_id']);
                            $this->db->where('section_item_id', $product_variation[0]['id']);
                            $this->db->where('vendor_user_id', $vendor_id);
                            $vendor_product_variation = $this->db->get('')->result_array();

                            if ($tax == '') {
                                $tax = 'Nill';
                            } else {
                                $tax = $tax;
                            }
                            $this->db->select("*");
                            $this->db->from("taxes");
                            $this->db->where('tax', $tax . '%');
                            $taxes = $this->db->get('')->result_array();

                            $vendor = $this->vendor_list_model->where('vendor_user_id', $vendor_id)->get();

                            if (count($categorie) > 0) {
                                if ($vendor['category_id'] == $categorie[0]['id']) {
                                    if (count($sub_categorie) > 0) {
                                        if (count($menu) > 0) {
                                            if (count($brand) > 0) {
                                                if (count($product) > 0) {
                                                    if (count($product_variation) > 0) {
                                                        if (count($vendor_product_variation) == 0) {


                                                            if (count($taxes) > 0) {
                                                                $tax_id = $tax[0]['id'];
                                                            } else {
                                                                $tax_items = [];
                                                                array_push($tax_items, [
                                                                    'tax' => $tax . '%',
                                                                    'rate' => $tax,
                                                                    'type_id' => '20',
                                                                    'created_user_id' => '1',
                                                                    'status' => '0',
                                                                ]);
                                                                $is_inserted = $this->db->insert_batch('taxes', $tax_items);

                                                                if ($is_inserted) {
                                                                    $this->db->select("*");
                                                                    $this->db->from("taxes");
                                                                    $this->db->where('tax', $tax . '%');
                                                                    $tax_new_record = $this->db->get('')->result_array();

                                                                    $tax_id = $tax_new_record[0]['id'];
                                                                }
                                                            }
                                                            $section_items = [];
                                                            array_push($section_items, [
                                                                'item_id' => $product_variation[0]['item_id'],
                                                                'section_id' => $product_variation[0]['sec_id'],
                                                                'section_item_id' => $product_variation[0]['id'],
                                                                'sku' => generate_serial_no($vendor['unique_id'] . '-' . metaphone($sub_category_name) . '-' . metaphone($menu_name) . '-', 2, $key),
                                                                'price' => $price,
                                                                'stock' => $stock,
                                                                'discount' => $discount,
                                                                'tax_id' => $tax_id,
                                                                'vendor_user_id' => $vendor_id,
                                                                'created_user_id' => $this->ion_auth->get_user_id(),
                                                                'list_id' => $vendor['id']
                                                            ]);

                                                            $is_inserted = $this->db->insert_batch('vendor_product_variants', $section_items);

                                                            $success_menu .= "<li>Row No: $i ---Catalogue successfully imported..!</li>";
                                                            $this->session->set_flashdata('upload_status', ["success" => $success_menu]);
                                                        } else {
                                                            $error_vendor_product_variation .= "<li>Row No: $i ---vendor have this product name $product_name</li>";
                                                            $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_vendor_product_variation"]);
                                                        }
                                                    } else {
                                                        $error_product_variation .= "<li>Row No: $i ---variation name $varinat_name does not exist for this product name $product_name</li>";
                                                        $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_product_variation"]);
                                                    }
                                                } else {
                                                    $error_product .= "<li>Row No: $i ---product name $product_name does not exist</li>";
                                                    $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_product"]);
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
                                        $error_sub_cat .= "<li>Row No: $i ---sub category name $sub_category_name does not exist for this category name $category_name</li>";
                                        $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_sub_cat"]);
                                    }
                                } else {
                                    $error_vendor_cat .= "<li>Row No: $i ---category name $category_name does not exist for this Vendor " . $vendor['business_name'] . "</li>";
                                    $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_vendor_cat"]);
                                }
                            } else {
                                $error_cat .= "<li>Row No: $i ---category name $category_name does not exist</li>";
                                $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_cat"]);
                            }
                        } elseif ($price != '' && $stock == '') {
                            $stocks_error .= "<li>Row No: $i ---Stocks field is empty.</li>";
                            $this->session->set_flashdata('upload_status', ["error" => $stocks_error]);
                        } elseif ($price == '' && $stock != '') {
                            $price_error .= "<li>Row No: $i ---Price field is empty</li>";
                            $this->session->set_flashdata('upload_status', ["error" => $price_error]);
                        }
                    }
                }

                $error_vendor_product_variation .= "</ul>";
                $error_product_variation .= "</ul>";
                $error_product .= "</ul>";
                $error_brand .= "</ul>";
                $error_menu .= "</ul>";
                $error_sub_cat .= "</ul>";
                $error_vendor_cat .= "</ul>";
                $error_cat .= "</ul>";
                $stocks_error .= "</ul>";
                $price_error .= "</ul>";
                $success_menu .= "</ul>";
            } else {
                $this->session->set_flashdata('upload_status', ["error" => "Please import correct file, did not match excel sheet column"]);
            }
        }
    }
}
