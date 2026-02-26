<?php
error_reporting(E_ERROR | E_PARSE);

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Catalogue extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->template = 'vendorCrm/catalogue';

        if (!$this->ion_auth->logged_in())
            redirect('auth/login');

        $this->load->model('vendor_list_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('vendor_leads_model');
        $this->load->model('user_model');
        $this->load->model('pickupcategory_model');
        $this->load->model('vendor_product_variant_model');
        $this->load->model('sub_category_model');
        $this->load->model('food_sec_item_model');
        $this->load->model('tax_model');
        $this->load->model('food_menu_model');
    }

    public function export()
    {


        $vendor = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();

        $sql = "SELECT c.name cat_name,sc.name sub_cat_name,fm.name menu_name,b.name brand_name,fi.item_type pro_type,fi.name pro_name,fsc.name var_name FROM `food_sec_item` fsc
        join food_item fi on fi.id=fsc.item_id
        join sub_categories sc on sc.id=fi.sub_cat_id
        join brands b on b.id=fi.brand_id
        join food_menu fm on fm.id=fi.menu_id
        join categories c on c.id=sc.cat_id
        where c.id=" . $vendor['category_id'] . " order by fsc.id";

        $product_variations = $this->db->query($sql);

        $data = $product_variations->result_array();

        $file_name = 'catalogue_bulk.xlsx';

        $spreadsheet = new Spreadsheet();

        $from = "A1";
        $to = "K1";
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'category_name');
        $sheet->setCellValue('B1', 'sub_category_name');
        $sheet->setCellValue('C1', 'menu_name');
        $sheet->setCellValue('D1', 'brand_name');
        $sheet->setCellValue('E1', 'product_type');
        $sheet->setCellValue('F1', 'product_name');
        $sheet->setCellValue('G1', 'varinat_name');
        $sheet->setCellValue('H1', 'price');
        $sheet->setCellValue('I1', 'discount');
        $sheet->setCellValue('J1', 'stock');
        $sheet->setCellValue('K1', 'tax');


        $count = 2;

        foreach ($data as $row) {

            if ($row['pro_type'] == 1) {
                $type = 'Veg';
            } elseif ($row['pro_type'] == 2) {
                $type = 'Non Veg';
            } elseif ($row['pro_type'] == 3) {
                $type = 'Other';
            }

            $sheet->setCellValue('A' . $count, $row['cat_name']);
            $sheet->setCellValue('B' . $count, $row['sub_cat_name']);
            $sheet->setCellValue('C' . $count, $row['menu_name']);
            $sheet->setCellValue('D' . $count, $row['brand_name']);
            $sheet->setCellValue('E' . $count, $type);
            $sheet->setCellValue('F' . $count, $row['pro_name']);
            $sheet->setCellValue('G' . $count, $row['var_name']);
            $sheet->setCellValue('H' . $count, '');
            $sheet->setCellValue('I' . $count, '');
            $sheet->setCellValue('J' . $count, '');
            $sheet->setCellValue('K' . $count, '');

            $count++;
        }

        $sheet = $spreadsheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);

        $writer = new Xlsx($spreadsheet);

        $writer->save($file_name);

        ob_end_clean();
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");

        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');

        header('Expires: 0');

        header('Cache-Control: max-age=0');

        header('Pragma: public');

        header('Content-Length:' . filesize($file_name));

        flush();

        readfile($file_name);

        exit;
    }

    public function catalogue_upload()
    {

        $this->data['users_groups']           =   $this->ion_auth->get_users_groups()->result();
        $this->data['users_permissions']      =   $this->ion_auth_acl->build_acl();
        $this->data['title'] = 'Catalogue';
        $this->data['content'] = 'vendor/catalogue';
        $this->data['nav_type'] = 'catalogue';

        if (isset($_POST['import'])) {
            $excel_file = $_FILES['excel_file'];
            $is_updated = $this->upload_catalogue($excel_file);
        }

        $this->_render_page($this->template, $this->data);
    }

    public function upload_catalogue1($file)
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
                        $this->db->where('vendor_user_id', $this->ion_auth->get_user_id());
                        $vendor_product_variation = $this->db->get('')->result_array();

                        if ($tax == '') {
                            $tax = 'Nill';
                        } else {
                            $tax = $tax;
                        }
                        $this->db->select("*");
                        $this->db->from("taxes");
                        $this->db->where('tax', $tax.'%');
                        $taxes = $this->db->get('')->result_array();

                        $vendor = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();

                        if (count($categorie) > 0) {
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
                            $this->db->where('vendor_user_id', $this->ion_auth->get_user_id());
                            $vendor_product_variation = $this->db->get('')->result_array();

                            if ($tax == '') {
                                $tax = 'Nill';
                            } else {
                                $tax = $tax;
                            }
                            $this->db->select("*");
                            $this->db->from("taxes");
                            $this->db->where('tax', $tax.'%');
                            $taxes = $this->db->get('')->result_array();

                            $vendor = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();

                            if (count($categorie) > 0) {
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
                                                            'vendor_user_id' => $this->ion_auth->get_user_id(),
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
                $error_cat .= "</ul>";
                $stocks_error .= "</ul>";
                $price_error .= "</ul>";
                $success_menu .= "</ul>";
            } else {
                $this->session->set_flashdata('upload_status', ["error" => "Please import correct file, did not match excel sheet column"]);
            }
        }
    }
        public function upload_catalogue($file)
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
                
                    $category_name = trim($allDataInSheet[$i][$SheetDataKey['category_name']]);
                    $sub_category_name = trim($allDataInSheet[$i][$SheetDataKey['sub_category_name']]);
                    $menu_name = trim($allDataInSheet[$i][$SheetDataKey['menu_name']]);
                    $brand_name = trim($allDataInSheet[$i][$SheetDataKey['brand_name']]);
                    $product_type = trim($allDataInSheet[$i][$SheetDataKey['product_type']]);
                    $product_name = trim($allDataInSheet[$i][$SheetDataKey['product_name']]);
                    $variant_name = trim($allDataInSheet[$i][$SheetDataKey['varinat_name']]);
                    $price = trim($allDataInSheet[$i][$SheetDataKey['price']]);
                    $discount = trim($allDataInSheet[$i][$SheetDataKey['discount']]);
                    $stock = trim($allDataInSheet[$i][$SheetDataKey['stock']]);
                    $tax = trim($allDataInSheet[$i][$SheetDataKey['tax']]);
                
                    if ($price == '' || $stock == '') {
                        continue;
                    }
                
                    // ================= CATEGORY =================
                    $categorie = $this->db->where('name', $category_name)->get('categories')->row_array();
                    if (!$categorie) continue;
                
                    // ================= SUB CATEGORY =================
                    $sub_categorie = $this->db
                        ->where('cat_id', $categorie['id'])
                        ->where('name', $sub_category_name)
                        ->where('type', 2)
                        ->get('sub_categories')
                        ->row_array();
                    if (!$sub_categorie) continue;
                
                    // ================= MENU =================
                    $menu = $this->db
                        ->where('sub_cat_id', $sub_categorie['id'])
                        ->where('name', $menu_name)
                        ->get('food_menu')
                        ->row_array();
                    if (!$menu) continue;
                
                    // ================= BRAND =================
                    $brand = $this->db->where('name', $brand_name)->get('brands')->row_array();
                    if (!$brand) continue;
                
                    // ================= PRODUCT TYPE =================
                    $p_type = 3;
                    if (strtolower($product_type) == 'veg') $p_type = 1;
                    if (strtolower($product_type) == 'non veg') $p_type = 2;
                
                    // ================= PRODUCT (AUTO CREATE) =================
                    $product = $this->db
                        ->where('sub_cat_id', $sub_categorie['id'])
                        ->where('menu_id', $menu['id'])
                        ->where('brand_id', $brand['id'])
                        ->where('item_type', $p_type)
                        ->where('LOWER(name)', strtolower($product_name))
                        ->get('food_item')
                        ->row_array();
                
                    if (!$product) {
                
                        $this->db->insert('food_item', [
                            'sub_cat_id' => $sub_categorie['id'],
                            'menu_id' => $menu['id'],
                            'brand_id' => $brand['id'],
                            'item_type' => $p_type,
                            'name' => $product_name,
                            'product_code' => strtoupper(uniqid()),
                            'status' => 1,
                            'created_user_id' => $this->ion_auth->get_user_id(),
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                
                        $product_id = $this->db->insert_id();
                    } else {
                        $product_id = $product['id'];
                    }
                
                    // ================= VARIATION (AUTO CREATE) =================
                    $variation = $this->db
                        ->where('item_id', $product_id)
                        ->where('name', $variant_name)
                        ->get('food_sec_item')
                        ->row_array();
                
                    if (!$variation) {
                
                        $this->db->insert('food_sec_item', [
                            'item_id' => $product_id,
                            'sec_id' => 1,
                            'name' => $variant_name,
                            'weight' => 0,
                            'status' => 1,
                            'created_user_id' => $this->ion_auth->get_user_id()
                        ]);
                
                        $variation_id = $this->db->insert_id();
                        $section_id = 1;
                
                    } else {
                        $variation_id = $variation['id'];
                        $section_id = $variation['sec_id'];
                    }
                
                    // ================= TAX (AUTO CREATE) =================
                    if ($tax == '') $tax = 'Nill';
                
                    $tax_row = $this->db
                        ->like('tax', $tax . '%')
                        ->get('taxes')
                        ->row_array();
                
                    if (!$tax_row) {
                
                        $this->db->insert('taxes', [
                            'tax' => $tax . '%',
                            'rate' => $tax,
                            'type_id' => 20,
                            'created_user_id' => 1,
                            'status' => 1
                        ]);
                
                        $tax_id = $this->db->insert_id();
                    } else {
                        $tax_id = $tax_row['id'];
                    }
                
                    // ================= CHECK DUPLICATE =================
                    $exists = $this->db
                        ->where('item_id', $product_id)
                        ->where('section_id', $section_id)
                        ->where('section_item_id', $variation_id)
                        ->where('vendor_user_id', $this->ion_auth->get_user_id())
                        ->get('vendor_product_variants')
                        ->row_array();
                
                    if ($exists) continue;
                
                    // ================= INSERT VENDOR PRODUCT =================
                    $vendor = $this->vendor_list_model
                        ->where('vendor_user_id', $this->ion_auth->get_user_id())
                        ->get();
                
                    $this->db->insert('vendor_product_variants', [
                        'item_id' => $product_id,
                        'section_id' => $section_id,
                        'section_item_id' => $variation_id,
                        'sku' => generate_serial_no($vendor['unique_id'].'-', 2, $i),
                        'price' => $price,
                        'stock' => $stock,
                        'discount' => $discount,
                        'tax_id' => $tax_id,
                        'vendor_user_id' => $this->ion_auth->get_user_id(),
                        'created_user_id' => $this->ion_auth->get_user_id(),
                        'list_id' => $vendor['id']
                    ]);
                
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
                            $this->db->where('vendor_user_id', $this->ion_auth->get_user_id());
                            $vendor_product_variation = $this->db->get('')->result_array();

                            if ($tax == '') {
                                $tax = 'Nill';
                            } else {
                                $tax = $tax;
                            }
                            $this->db->select("*");
                            $this->db->from("taxes");
                            $this->db->where('tax', $tax.'%');
                            $taxes = $this->db->get('')->result_array();

                            $vendor = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();

                            if (count($categorie) > 0) {
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
                                                            'vendor_user_id' => $this->ion_auth->get_user_id(),
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
                $error_cat .= "</ul>";
                $stocks_error .= "</ul>";
                $price_error .= "</ul>";
                $success_menu .= "</ul>";
            } else {
                $this->session->set_flashdata('upload_status', ["error" => "Please import correct file, did not match excel sheet column"]);
            }
        }
    }

    public function catalogue_list()
    {
        $vendor = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
        $this->data['users_groups']           =   $this->ion_auth->get_users_groups()->result();
        $this->data['users_permissions']      =   $this->ion_auth_acl->build_acl();
        $this->data['title'] = 'Catalogue';
        $this->data['content'] = 'vendor/catalogue_list';
        $this->data['nav_type'] = 'catalogue_list';
        $this->template = 'vendorCrm/catalogue_list';

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

        $catalogue_sql = "SELECT fi.*,c.name category_name,fii.id image_id,fii.ext image_ext,sc.name sub_cat_name,sc.id sub_cat_id,fm.id menu_id,fm.name menu_name FROM `food_item` fi 
        JOIN food_menu fm on fm.id=fi.menu_id
        JOIN sub_categories sc on sc.id=fi.sub_cat_id
        JOIN categories c on c.id=sc.cat_id
        JOIN food_item_images fii on fii.item_id=fi.id
        WHERE fi.status = 1 and fi.availability = 1 and fi.sub_cat_id in(" . $sub_cat_id . ") and fi.deleted_at is null " . $where_sub_cat_id . $where_menu_id . $where_search;
        $query = $this->db->query($catalogue_sql);
        $this->data['catalogue_lists'] = $query->result_array();

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

    public function menu_by_category()
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

    public function tax_type()
    {
        $arr = array();
        $taxes = $this->tax_model->where('type_id', $_POST['tax_type'])->get_all();
        echo json_encode(array(
            $taxes
        ));
    }
    public function catalogue_add()
    {
        $this->data['users_groups']           =   $this->ion_auth->get_users_groups()->result();
        $this->data['users_permissions']      =   $this->ion_auth_acl->build_acl();
        $this->data['title'] = 'Catalogue';
        $this->data['content'] = 'vendor/catalogue_add';
        $this->data['nav_type'] = 'catalogue_add';
        $this->template = 'vendorCrm/catalogue_add';
        if (isset($_POST['apply'])) {
            $item_ids = implode(',', $_POST['item_id']);

            $catalogue_products_sql = "SELECT fi.*,c.name category_name,fii.id image_id,fii.ext image_ext,sc.name sub_cat_name,sc.id sub_cat_id,fm.id menu_id,fm.name menu_name,b.name brand_name FROM `food_item` fi 
            JOIN food_menu fm on fm.id=fi.menu_id
            JOIN sub_categories sc on sc.id=fi.sub_cat_id
            JOIN categories c on c.id=sc.cat_id
            JOIN food_item_images fii on fii.item_id=fi.id
            JOIN brands b on b.id=fi.brand_id
            where fi.id IN($item_ids)";
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

    public function catalogue_update()
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
        $data['title'] = 'Catalogue';
        $data['content'] = 'vendor/catalogue_list';
        $data['nav_type'] = 'catalogue_list';
        $vendor_cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get()['category_id'];
        $sub_cat_ids = $this->sub_category_model->fields('id')->where('cat_id', $vendor_cat_id)->where('type', 2)->get_all();
        $sub_cat_id = implode(',', array_column($sub_cat_ids, 'id'));

        $catalogue_sql = "SELECT fi.*,c.name category_name,fii.id image_id,fii.ext image_ext,sc.name sub_cat_name,sc.id sub_cat_id,fm.id menu_id,fm.name menu_name FROM `food_item` fi 
        JOIN food_menu fm on fm.id=fi.menu_id
        JOIN sub_categories sc on sc.id=fi.sub_cat_id
        JOIN categories c on c.id=sc.cat_id
        JOIN food_item_images fii on fii.item_id=fi.id
        WHERE fi.status = 1 and fi.availability = 1 and fi.sub_cat_id in(" . $sub_cat_id . ") and fi.deleted_at is null ";
        $query = $this->db->query($catalogue_sql);
        $data['catalogue_lists'] = $query->result_array();

        $where_condition = '(created_user_id = ' . $this->ion_auth->get_user_id() . ' OR created_user_id = 1)';
        $data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')
            ->where($where_condition)
            ->where([
                'cat_id' => $vendor['category_id'],
                'type' => 2
            ])
            ->get_all();
        $this->session->set_flashdata('upload_status', ["success" => "Catalogue updated successfully"]);
        $this->load->view('vendorCrm/catalogue_list', $data);
    }
}
