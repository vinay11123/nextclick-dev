<?php
error_reporting(E_ERROR | E_PARSE);
class Bulk_upload extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template = 'template/admin/main';
        $this->load->library('user_agent');
        if (!$this->ion_auth->logged_in())
            redirect('auth/login');

        $this->load->model('category_model');
        $this->load->model('sub_category_model');
        $this->load->model('shop_by_category_model');
        $this->load->model('brand_model');
        $this->load->model('food_menu_model');
    }

    /**
     * Categories Bulk upload
     * @author Mehar
     */
    public function category()
    {
        $zip_file = $_FILES['images_zip'];
        $excel_file = $_FILES['excel_file'];
        $base_path = dirname(BASEPATH) . '/uploads/categories_zip/';
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
                $is_updated = $this->upload_categories_excel_with_images($excel_file, $base_path, $random_digit);
            } else {
                $this->session->set_flashdata('upload_status', ["error" => "Unable to extract ZIP"]);
            }
        } else {
            $this->session->set_flashdata('upload_status', ["error" => "Uploading ZIP is failed"]);
        }

        $this->data['title'] = 'Category Bulk';
        $this->data['content'] = 'bulk_upload/category_bulk_upload';
        $this->data['nav_type'] = 'category';
        $this->_render_page($this->template, $this->data);
    }

    public function upload_categories_excel_with_images($file, $base_path, $random_digit)
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
            $createArray = array('name', 'desc');
            $makeArray = array('name' => 'name', 'desc' => 'desc');
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
                    $name = $SheetDataKey['name'];
                    $desc = $SheetDataKey['desc'];

                    $name = filter_var(trim($allDataInSheet[$i][$name]), FILTER_SANITIZE_STRING);
                    $desc = filter_var(trim($allDataInSheet[$i][$desc]), FILTER_SANITIZE_STRING);

                    if (!empty($name)) {
                        $cat_id = $this->category_model->insert([
                            'name' => $name,
                            'desc' => $desc,
                        ]);

                        if ($cat_id) {

                            $file_name = str_replace(array("#", "'", ";", "@[^0-9a-zA-Z.]+"), '', $name);
                            $space_file_name = str_replace('  ', ' ', $file_name);
                            $image_file_name = strtolower(str_replace(' ', '_', $space_file_name)) . '.jpg';

                            if (file_exists($base_path . $random_digit . '/nxccategories/' . $image_file_name)) {
                                if (!file_exists('uploads/' . 'category' . '_image/')) {
                                    mkdir('uploads/' . 'category' . '_image/', 0777, true);
                                }
                                if (file_exists('uploads/' . 'category' . '_image/' . 'category' . '_' . $cat_id . '.jpg')) {
                                    unlink('uploads/' . 'category' . '_image/' . 'category' . '_' . $cat_id . '.jpg');
                                }
                                $source_image = file_get_contents($base_path . $random_digit . '/nxccategories/' . $image_file_name);
                                file_put_contents('./uploads/category_image/' . "category_" . $cat_id . ".jpg", $source_image);
                            } else {
                                $this->session->set_flashdata('upload_status', ["error" => "Error occured at row no($i)---Please check name and image name"]);
                            }
                        } else {
                            $this->session->set_flashdata('upload_status', ["error" => "Error occured at row no($i)---Please check email and mobile"]);
                            break;
                        }
                    }
                    $this->session->set_flashdata('upload_status', ["success" => "Categories successfully imported..!"]);
                }
            } else {
                $this->session->set_flashdata('upload_status', ["error" => "Please import correct file, did not match excel sheet column"]);
            }
        }
    }

    /**
     * E-Commerce brand Bulk upload
     *
     * @author Mehar
     */
    public function brands_upload1()
    {
        if (isset($_POST['import'])) {
            $zip_file = $_FILES['images_zip'];
            $excel_file = $_FILES['excel_file'];
            $base_path = dirname(BASEPATH) . '/uploads/brands_zip/';
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
                    $is_updated = $this->upload_brands_with_images($excel_file, $base_path, $random_digit);
                } else {
                    $this->session->set_flashdata('upload_status', ["error" => "Unable to extract ZIP"]);
                }
            } else {
                $this->session->set_flashdata('upload_status', ["error" => "Uploading ZIP is failed"]);
            }
        }
        $this->data['title'] = 'Brands Bulk';
        $this->data['content'] = 'bulk_upload/brands_bulk_upload';
        $this->data['nav_type'] = 'brands';
        $this->_render_page($this->template, $this->data);
    }
public function brands_upload()
{
    if (isset($_POST['import'])) {

        $zip_file   = $_FILES['images_zip'];
        $excel_file = $_FILES['excel_file'];

        $base_path = dirname(BASEPATH) . '/uploads/brands_zip/';
        if (!file_exists($base_path)) {
            mkdir($base_path, 0777, true);
        }

        $random_digit = time();
        $new_file_name = $random_digit . ".zip";

        // create random folder
        if (!file_exists($base_path . $random_digit)) {
            mkdir($base_path . $random_digit, 0777, true);
        }

        $zip_file_path = $base_path . $random_digit . '/' . $new_file_name;

        if (copy($zip_file['tmp_name'], $zip_file_path)) {

            // unzip file
            $zip = new ZipArchive();
            if ($zip->open($zip_file_path) === TRUE) {

                $zip->extractTo($base_path . $random_digit);
                $zip->close();

                // 🔍 AUTO FIND IMAGE FOLDER INSIDE ZIP
                $extract_path = $base_path . $random_digit . '/';
                $files = scandir($extract_path);

                $images_folder = '';

                foreach ($files as $f) {
                    if ($f != '.' && $f != '..' && is_dir($extract_path . $f)) {
                        // skip the zip file folder name if any
                        if ($f != $random_digit) {
                            $images_folder = $f;   // example: nxcproducts
                            break;
                        }
                    }
                }

                // if inner folder found, use it — else use main path
                if ($images_folder != '') {
                    $final_image_path = $extract_path . $images_folder . '/';
                } else {
                    $final_image_path = $extract_path;
                }

                // 🔁 CALL IMPORT WITH CORRECT IMAGE FOLDER PATH
                $is_updated = $this->upload_brands_with_images(
                    $excel_file,
                    $final_image_path   // ✅ now this is exact folder containing images
                );

            } else {
                $this->session->set_flashdata('upload_status', ["error" => "Unable to extract ZIP"]);
            }

        } else {
            $this->session->set_flashdata('upload_status', ["error" => "Uploading ZIP is failed"]);
        }
    }

    $this->data['title'] = 'Brands Bulk';
    $this->data['content'] = 'bulk_upload/brands_bulk_upload';
    $this->data['nav_type'] = 'brands';
    $this->_render_page($this->template, $this->data);
}




    public function upload_brands_with_images1($file, $base_path, $random_digit)
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
            $createArray = array('name', 'desc', 'image_name');
            $makeArray = array('name' => 'name', 'desc' => 'desc', 'image_name' => 'image_name');
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
                $error_row_numbers = "<ul>";
                $error_path_row_numbers = "<ul>";
                $error_empty_row_numbers = "<ul>";
                $create_record = '';
                for ($i = 2; $i <= $arrayCount; $i++) {

                    $name = $SheetDataKey['name'];
                    $desc = $SheetDataKey['desc'];
                    $image_name = $SheetDataKey['image_name'];

                    $name = filter_var(trim($allDataInSheet[$i][$name]), FILTER_SANITIZE_STRING);
                    $desc = filter_var(trim($allDataInSheet[$i][$desc]), FILTER_SANITIZE_STRING);
                    $image_name = filter_var(trim($allDataInSheet[$i][$image_name]), FILTER_SANITIZE_STRING);

                    if (!empty($name) && !empty($desc) && !empty($image_name)) {

                        if (file_exists($base_path . $random_digit . '/' . $image_name)) {
                            $dataCheck = $this->methodCheck($name);
                            if ($dataCheck == '') {
                                $create_record = true;
                            } else {
                                $create_record = false;
                                $error_row_numbers .= "<li>Row No: $i ---Name $name is already exist</li>";
                                $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_row_numbers"]);
                            }
                        } else {
                            $create_record = false;
                            $error_path_row_numbers .= "<li>Row No: $i ---Please check name and image name</li>";
                            $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_path_row_numbers"]);
                        }
                    } else {
                        $create_record = false;
                        $error_empty_row_numbers .= "<li>Row No: $i </li>";
                        $this->session->set_flashdata('upload_status', ["error" => "Empty filds occured at $error_empty_row_numbers"]);
                        $this->data['brands'] = array('name' => $name, 'desc' => $desc, 'image_name' => $image_name);
                    }
                }
                if ($create_record == true) {
                    for ($i = 2; $i <= $arrayCount; $i++) {

                        $name = $SheetDataKey['name'];
                        $desc = $SheetDataKey['desc'];
                        $image_name = $SheetDataKey['image_name'];

                        $name = filter_var(trim($allDataInSheet[$i][$name]), FILTER_SANITIZE_STRING);
                        $desc = filter_var(trim($allDataInSheet[$i][$desc]), FILTER_SANITIZE_STRING);
                        $image_name = filter_var(trim($allDataInSheet[$i][$image_name]), FILTER_SANITIZE_STRING);

                        if (!empty($name) && !empty($desc) && !empty($image_name)) {

                            if (file_exists($base_path . $random_digit . '/' . $image_name)) {
                                $dataCheck = $this->methodCheck($name);
                                if ($dataCheck == '') {
                                    $brand_id = $this->brand_model->insert([
                                        'name' => $name,
                                        'desc' => $desc,
                                        'status' => 1
                                    ]);
                                    if (!file_exists('uploads/' . 'brands' . '_image/')) {
                                        mkdir('uploads/' . 'brands' . '_image/', 0777, true);
                                    }
                                    if (file_exists('uploads/' . 'brands' . '_image/' . 'brands' . '_' . $brand_id . '.jpg')) {
                                        unlink('uploads/' . 'brands' . '_image/' . 'brands' . '_' . $brand_id . '.jpg');
                                    }
                                    $source_image = file_get_contents($base_path . $random_digit . '/' . $image_name);
                                    file_put_contents('./uploads/brands_image/' . "brands_" . $brand_id . ".jpg", $source_image);
                                    $this->session->set_flashdata('upload_status', ["success" => "Brands successfully imported..!"]);
                                } else {
                                    $error_row_numbers .= "<li>Row No: $i ---Name $name is already exist</li>";
                                    $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_row_numbers"]);
                                }
                            } else {
                                $error_path_row_numbers .= "<li>Row No: $i ---Please check name and image name</li>";
                                $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_path_row_numbers"]);
                            }
                        } else {
                            $error_empty_row_numbers .= "<li>Row No: $i </li>";
                            $this->session->set_flashdata('upload_status', ["error" => "Empty filds occured at $error_empty_row_numbers"]);
                            $this->data['brands'] = array('name' => $name, 'desc' => $desc, 'image_name' => $image_name);
                        }
                    }
                }
                $error_row_numbers .= "</ul>";
                $error_path_row_numbers .= "</ul>";
                $error_empty_row_numbers .= "</ul>";
                delete_files($base_path . $random_digit);
            } else {
                $this->session->set_flashdata('upload_status', ["error" => "Please import correct file, did not match excel sheet column"]);
            }
        }
    }
    public function upload_brands_with_images($file, $base_path)
{
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', '0');

    if (!empty($file['name'])) {

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

        if ($extension == 'csv') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } elseif ($extension == 'xlsx') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }

        $spreadsheet = $reader->load($file['tmp_name']);
        $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $arrayCount = count($allDataInSheet);
        $flag = 0;

        $createArray = array('name', 'desc', 'image_name');
        $makeArray   = array('name' => 'name', 'desc' => 'desc', 'image_name' => 'image_name');
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

        if ($flag == 1) {

            $error_row_numbers = "<ul>";
            $error_path_row_numbers = "<ul>";
            $error_empty_row_numbers = "<ul>";
            $create_record = '';

            /* 🔎 FIRST VALIDATION LOOP */
            for ($i = 2; $i <= $arrayCount; $i++) {

                $name_col  = $SheetDataKey['name'];
                $desc_col  = $SheetDataKey['desc'];
                $image_col = $SheetDataKey['image_name'];

                $name  = filter_var(trim($allDataInSheet[$i][$name_col]), FILTER_SANITIZE_STRING);
                $desc  = filter_var(trim($allDataInSheet[$i][$desc_col]), FILTER_SANITIZE_STRING);
                $image_name = filter_var(trim($allDataInSheet[$i][$image_col]), FILTER_SANITIZE_STRING);

                if (!empty($name) && !empty($desc) && !empty($image_name)) {

                    $image_path = $base_path . $image_name;

                    if (file_exists($image_path)) {

                        $dataCheck = $this->methodCheck($name);
                        if ($dataCheck == '') {
                            $create_record = true;
                        } else {
                            $create_record = false;
                            $error_row_numbers .= "<li>Row No: $i ---Name $name is already exist</li>";
                        }

                    } else {
                        $create_record = false;
                        $error_path_row_numbers .= "<li>Row No: $i ---Image not found : $image_name</li>";
                    }

                } else {
                    $create_record = false;
                    $error_empty_row_numbers .= "<li>Row No: $i ---Empty fields</li>";
                }
            }

            /* 🔁 INSERT LOOP */
            if ($create_record == true) {

                for ($i = 2; $i <= $arrayCount; $i++) {

                    $name_col  = $SheetDataKey['name'];
                    $desc_col  = $SheetDataKey['desc'];
                    $image_col = $SheetDataKey['image_name'];

                    $name  = filter_var(trim($allDataInSheet[$i][$name_col]), FILTER_SANITIZE_STRING);
                    $desc  = filter_var(trim($allDataInSheet[$i][$desc_col]), FILTER_SANITIZE_STRING);
                    $image_name = filter_var(trim($allDataInSheet[$i][$image_col]), FILTER_SANITIZE_STRING);

                    if (!empty($name) && !empty($desc) && !empty($image_name)) {

                        $image_path = $base_path . $image_name;

                        if (file_exists($image_path)) {

                            $dataCheck = $this->methodCheck($name);
                            if ($dataCheck == '') {

                                $brand_id = $this->brand_model->insert([
                                    'name'   => $name,
                                    'desc'   => $desc,
                                    'status' => 1
                                ]);

                                if (!file_exists('uploads/brands_image/')) {
                                    mkdir('uploads/brands_image/', 0777, true);
                                }

                                $source_image = file_get_contents($image_path);
                                file_put_contents('./uploads/brands_image/' . "brands_" . $brand_id . ".jpg", $source_image);

                                $this->session->set_flashdata('upload_status', ["success" => "Brands successfully imported..!"]);

                            } else {
                                $error_row_numbers .= "<li>Row No: $i ---Name $name is already exist</li>";
                            }

                        } else {
                            $error_path_row_numbers .= "<li>Row No: $i ---Image not found : $image_name</li>";
                        }

                    } else {
                        $error_empty_row_numbers .= "<li>Row No: $i ---Empty fields</li>";
                    }
                }
            }

            $error_row_numbers .= "</ul>";
            $error_path_row_numbers .= "</ul>";
            $error_empty_row_numbers .= "</ul>";

            if (!$create_record) {
                $this->session->set_flashdata('upload_status', ["error" =>
                    "Error occured at $error_row_numbers $error_path_row_numbers $error_empty_row_numbers"
                ]);
            }

        } else {
            $this->session->set_flashdata('upload_status', ["error" => "Please import correct file, did not match excel sheet column"]);
        }
    }
}


    function methodCheck($param)
    {
        $this->db->select("*");
        $this->db->from("brands");
        $this->db->where('name', $param);
        $hasil = $this->db->get('')->result_array();
        if (count($hasil) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sub_Category Bulk upload
     *
     * @author Mehar
     */
    public function sub_category_upload()
    {
        if (isset($_POST['import'])) {
            $zip_file = $_FILES['images_zip'];
            $excel_file = $_FILES['excel_file'];
            $base_path = dirname(BASEPATH) . '/uploads/subcategory_zip/';
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
                    $is_updated = $this->upload_subexcel_with_images($excel_file, $base_path, $random_digit);
                } else {
                    $this->session->set_flashdata('upload_status', ["error" => "Unable to extract ZIP"]);
                }
            } else {
                $this->session->set_flashdata('upload_status', ["error" => "Uploading ZIP is failed"]);
            }
        }
        $this->data['title'] = 'SubCategory bulk upload';
        $this->data['content'] = 'admin/bulk_upload/sub_category_bulk_upload';
        $this->data['nav_type'] = 'sub_category_upload';
        $this->_render_page($this->template, $this->data);
    }

    public function upload_subexcel_with_images($file, $base_path, $random_digit)
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
            $createArray = array('categorie_name', 'sub_categorie_type', 'sub_categorie_name', 'sub_categorie_desc', 'image_name');
            $makeArray = array('categorie_name' => 'categorie_name', 'sub_categorie_type' => 'sub_categorie_type', 'sub_categorie_name' => 'sub_categorie_name', 'sub_categorie_desc' => 'sub_categorie_desc', 'image_name' => 'image_name');
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
                $error_sub_cat_type = "<ul>";
                $error_sub_cat = "<ul>";
                $error_path_row_numbers = "<ul>";
                $error_empty_row_numbers = "<ul>";
                $create_record = '';
                for ($i = 2; $i <= $arrayCount; $i++) {

                    $categorie_name = $SheetDataKey['categorie_name'];
                    $sub_categorie_type = $SheetDataKey['sub_categorie_type'];
                    $sub_categorie_name = $SheetDataKey['sub_categorie_name'];
                    $sub_categorie_desc = $SheetDataKey['sub_categorie_desc'];
                    $image_name = $SheetDataKey['image_name'];

                    $categorie_name = filter_var(trim($allDataInSheet[$i][$categorie_name]), FILTER_SANITIZE_STRING);
                    $sub_categorie_type = filter_var(trim($allDataInSheet[$i][$sub_categorie_type]), FILTER_SANITIZE_STRING);
                    $sub_categorie_name = filter_var(trim($allDataInSheet[$i][$sub_categorie_name]), FILTER_SANITIZE_STRING);
                    $sub_categorie_desc = filter_var(trim($allDataInSheet[$i][$sub_categorie_desc]), FILTER_SANITIZE_STRING);
                    $image_name = filter_var(trim($allDataInSheet[$i][$image_name]), FILTER_SANITIZE_STRING);

                    $this->db->select("*");
                    $this->db->from("categories");
                    $this->db->where('name', $categorie_name);
                    $categorie = $this->db->get('')->result_array();

                    $this->db->select("*");
                    $this->db->from("sub_category_type");
                    $this->db->where('name', $sub_categorie_type);
                    $sub_category_type = $this->db->get('')->result_array();

                    $this->db->select("*");
                    $this->db->from("sub_categories");
                    $this->db->where('name', $sub_categorie_name);
                    $this->db->where('cat_id', $categorie[0]['id']);
                    $this->db->where('type', $sub_category_type[0]['id']);
                    $sub_categorie = $this->db->get('')->result_array();

                    if ($categorie_name != '' && $sub_categorie_type != '' && $sub_categorie_name != '' && $sub_categorie_desc != '' && $image_name != '') {
                        if (count($categorie) > 0) {
                            if (count($sub_category_type) > 0) {
                                if (count($sub_categorie) == 0) {

                                    if (file_exists($base_path . $random_digit . '/' . $image_name)) {
                                        $create_record = true;
                                    } else {
                                        $create_record = false;
                                        $error_path_row_numbers .= "<li>Row No: $i ---Please check name and image name</li>";
                                        $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_path_row_numbers"]);
                                    }
                                } else {
                                    $create_record = false;
                                    $error_sub_cat .= "<li>Row No: $i ---sub category $sub_categorie_name is already exist for this category $categorie_name & sub category type $sub_categorie_type</li>";
                                    $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_sub_cat"]);
                                }
                            } else {
                                $create_record = false;
                                $error_sub_cat_type .= "<li>Row No: $i ---sub category type $sub_categorie_type does not exist</li>";
                                $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_sub_cat_type"]);
                            }
                        } else {
                            $create_record = false;
                            $error_cat .= "<li>Row No: $i ---category name $categorie_name does not exist</li>";
                            $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_cat"]);
                        }
                    } else {
                        $create_record = false;
                        $error_empty_row_numbers .= "<li>Row No: $i </li>";
                        $this->session->set_flashdata('upload_status', ["error" => "Empty filds occured at $error_empty_row_numbers"]);
                        $this->data['subcategory'] = array('categorie_name' => $categorie_name, 'sub_categorie_type' => $sub_categorie_type, 'sub_categorie_name' => $sub_categorie_name, 'sub_categorie_desc' => $sub_categorie_desc, 'image_name' => $image_name);
                    }
                }

                if ($create_record == true) {
                    for ($i = 2; $i <= $arrayCount; $i++) {

                        $categorie_name = $SheetDataKey['categorie_name'];
                        $sub_categorie_type = $SheetDataKey['sub_categorie_type'];
                        $sub_categorie_name = $SheetDataKey['sub_categorie_name'];
                        $sub_categorie_desc = $SheetDataKey['sub_categorie_desc'];
                        $image_name = $SheetDataKey['image_name'];

                        $categorie_name = filter_var(trim($allDataInSheet[$i][$categorie_name]), FILTER_SANITIZE_STRING);
                        $sub_categorie_type = filter_var(trim($allDataInSheet[$i][$sub_categorie_type]), FILTER_SANITIZE_STRING);
                        $sub_categorie_name = filter_var(trim($allDataInSheet[$i][$sub_categorie_name]), FILTER_SANITIZE_STRING);
                        $sub_categorie_desc = filter_var(trim($allDataInSheet[$i][$sub_categorie_desc]), FILTER_SANITIZE_STRING);
                        $image_name = filter_var(trim($allDataInSheet[$i][$image_name]), FILTER_SANITIZE_STRING);

                        $this->db->select("*");
                        $this->db->from("categories");
                        $this->db->where('name', $categorie_name);
                        $categorie = $this->db->get('')->result_array();

                        $this->db->select("*");
                        $this->db->from("sub_category_type");
                        $this->db->where('name', $sub_categorie_type);
                        $sub_category_type = $this->db->get('')->result_array();

                        $this->db->select("*");
                        $this->db->from("sub_categories");
                        $this->db->where('name', $sub_categorie_name);
                        $this->db->where('cat_id', $categorie[0]['id']);
                        $this->db->where('type', $sub_category_type[0]['id']);
                        $sub_categorie = $this->db->get('')->result_array();


                        if ($categorie_name != '' && $sub_categorie_type != '' && $sub_categorie_name != '' && $sub_categorie_desc != '' && $image_name != '') {
                            if (count($categorie) > 0) {
                                if (count($sub_category_type) > 0) {
                                    if (count($sub_categorie) == 0) {

                                        if (file_exists($base_path . $random_digit . '/' . $image_name)) {
                                            $sub_cat_id = $this->sub_category_model->insert([
                                                'cat_id' => $categorie[0]['id'],
                                                'type' => $sub_category_type[0]['id'],
                                                'name' => $sub_categorie_name,
                                                'desc' => $sub_categorie_desc,
                                                'product_type_widget_status' => 2,
                                                'status' => 1
                                            ]);

                                            if ($sub_category_type[0]['id'] == 2) {
                                                $shop_cat_id = $this->shop_by_category_model->insert([
                                                    'vendor_id' => 1,
                                                    'cat_id' => $categorie[0]['id'],
                                                    'sub_cat_id' => $sub_cat_id
                                                ]);
                                            }

                                            if (!file_exists('uploads/' . 'sub_category' . '_image/')) {
                                                mkdir('uploads/' . 'sub_category' . '_image/', 0777, true);
                                            }
                                            if (file_exists('uploads/' . 'sub_category' . '_image/' . 'sub_category' . '_' . $sub_cat_id . '.jpg')) {
                                                unlink('uploads/' . 'sub_category' . '_image/' . 'sub_category' . '_' . $sub_cat_id . '.jpg');
                                            }
                                            $source_image = file_get_contents($base_path . $random_digit . '/' . $image_name);
                                            file_put_contents('./uploads/sub_category_image/' . "sub_category_" . $sub_cat_id . ".jpg", $source_image);
                                            $this->session->set_flashdata('upload_status', ["success" => "Sub Categories successfully imported..!"]);
                                        } else {
                                            $error_path_row_numbers .= "<li>Row No: $i ---Please check name and image name</li>";
                                            $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_path_row_numbers"]);
                                        }
                                    } else {
                                        $error_sub_cat .= "<li>Row No: $i ---sub category $sub_categorie_name is already exist for this category $categorie_name & sub category type $sub_categorie_type</li>";
                                        $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_sub_cat"]);
                                    }
                                } else {
                                    $error_sub_cat_type .= "<li>Row No: $i ---sub category type $sub_categorie_type does not exist</li>";
                                    $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_sub_cat_type"]);
                                }
                            } else {
                                $error_cat .= "<li>Row No: $i ---category name $categorie_name does not exist</li>";
                                $this->session->set_flashdata('upload_status', ["error" => "Error occured at $error_cat"]);
                            }
                        } else {
                            $error_empty_row_numbers .= "<li>Row No: $i </li>";
                            $this->session->set_flashdata('upload_status', ["error" => "Empty filds occured at $error_empty_row_numbers"]);
                            $this->data['subcategory'] = array('categorie_name' => $categorie_name, 'sub_categorie_type' => $sub_categorie_type, 'sub_categorie_name' => $sub_categorie_name, 'sub_categorie_desc' => $sub_categorie_desc, 'image_name' => $image_name);
                        }
                    }
                }
                $error_cat .= "</ul>";
                $error_sub_cat_type .= "</ul>";
                $error_sub_cat .= "</ul>";
                $error_path_row_numbers .= "</ul>";
                $error_empty_row_numbers .= "</ul>";
                delete_files($base_path . $random_digit);
            } else {
                $this->session->set_flashdata('upload_status', ["error" => "Please import correct file, did not match excel sheet column"]);
            }
        }
    }

    /**
     * Menu Bulk upload
     *
     * @author Mehar
     */
    public function menu_upload()
    {
        if (isset($_POST['import'])) {
            $excel_file = $_FILES['excel_file'];
            $is_updated = $this->upload_menu_with_images($excel_file);
        }
        $this->data['title'] = 'Menu bulk upload';
        $this->data['content'] = 'admin/bulk_upload/menu_bulk_upload';
        $this->data['nav_type'] = 'menu_upload';
        $this->_render_page($this->template, $this->data);
    }

    public function upload_menu_with_images($file)
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
            $createArray = array('category_name', 'sub_category_name', 'menu_name', 'menu_desc');
            $makeArray = array('category_name' => 'category_name', 'sub_category_name' => 'sub_category_name', 'menu_name' => 'menu_name', 'menu_desc' => 'menu_desc');
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
                $error_sub_cat = "<ul>";
                $error_cat = "<ul>";
                $error_menu = "<ul>";
                $success_menu = "<ul>";
                $error_empty_row_numbers = "<ul>";
                $create_record = '';
                for ($i = 2; $i <= $arrayCount; $i++) {

                    $category_name = $SheetDataKey['category_name'];
                    $sub_category_name = $SheetDataKey['sub_category_name'];
                    $menu_name = $SheetDataKey['menu_name'];
                    $menu_desc = $SheetDataKey['menu_desc'];

                    $category_name = filter_var(trim($allDataInSheet[$i][$category_name]), FILTER_SANITIZE_STRING);
                    $sub_category_name = filter_var(trim($allDataInSheet[$i][$sub_category_name]), FILTER_SANITIZE_STRING);
                    $menu_name = filter_var(trim($allDataInSheet[$i][$menu_name]), FILTER_SANITIZE_STRING);
                    $menu_desc = filter_var(trim($allDataInSheet[$i][$menu_desc]), FILTER_SANITIZE_STRING);

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

                    if (!empty($menu_name)  &&  !empty($sub_category_name) &&  !empty($category_name) &&  !empty($menu_desc)) {
                        if (count($categorie) > 0) {
                            if (count($sub_categorie) > 0) {
                                if (count($menu) == 0) {
                                    $create_record = true;
                                } else {
                                    $create_record = false;
                                    $error_menu .= "<li>Row No: $i ---menu name $menu_name is already exist for this sub category $sub_category_name</li>";
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
                    } else {
                        $create_record = false;
                        $error_empty_row_numbers .= "<li>Row No: $i </li>";
                        $this->session->set_flashdata('upload_status', ["error" => "Empty filds occured at $error_empty_row_numbers"]);
                        $this->data['menu'] = array('category_name' => $category_name, 'sub_category_name' => $sub_category_name, 'menu_name' => $menu_name, 'menu_desc' => $menu_desc);
                    }
                }
                if ($create_record == true) {
                    for ($i = 2; $i <= $arrayCount; $i++) {

                        $category_name = $SheetDataKey['category_name'];
                        $sub_category_name = $SheetDataKey['sub_category_name'];
                        $menu_name = $SheetDataKey['menu_name'];
                        $menu_desc = $SheetDataKey['menu_desc'];

                        $category_name = filter_var(trim($allDataInSheet[$i][$category_name]), FILTER_SANITIZE_STRING);
                        $sub_category_name = filter_var(trim($allDataInSheet[$i][$sub_category_name]), FILTER_SANITIZE_STRING);
                        $menu_name = filter_var(trim($allDataInSheet[$i][$menu_name]), FILTER_SANITIZE_STRING);
                        $menu_desc = filter_var(trim($allDataInSheet[$i][$menu_desc]), FILTER_SANITIZE_STRING);

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

                        if (!empty($menu_name)  &&  !empty($sub_category_name) &&  !empty($category_name) &&  !empty($menu_desc)) {
                            if (count($categorie) > 0) {
                                if (count($sub_categorie) > 0) {
                                    if (count($menu) == 0) {
                                        $menu_id = $this->food_menu_model->insert([
                                            'vendor_id' => 1,
                                            'sub_cat_id' => $sub_categorie[0]['id'],
                                            'name' => $menu_name,
                                            'desc' => $menu_desc,
                                            'status' => 1
                                        ]);
                                        if ($menu_id) {
                                            $success_menu .= "<li>Row No: $i ---Menus successfully imported..!</li>";
                                            $this->session->set_flashdata('upload_status', ["success" => $success_menu]);
                                        }
                                    } else {
                                        $error_menu .= "<li>Row No: $i ---menu name $menu_name is already exist for this sub category $sub_category_name</li>";
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
                        } else {
                            $error_empty_row_numbers .= "<li>Row No: $i </li>";
                            $this->session->set_flashdata('upload_status', ["error" => "Empty filds occured at $error_empty_row_numbers"]);
                            $this->data['menu'] = array('category_name' => $category_name, 'sub_category_name' => $sub_category_name, 'menu_name' => $menu_name, 'menu_desc' => $menu_desc);
                        }
                    }
                }
                $error_sub_cat .= "</ul>";
                $error_cat .= "</ul>";
                $error_menu .= "</ul>";
                $success_menu .= "</ul>";
                $error_empty_row_numbers .= "</ul>";
            } else {
                $this->session->set_flashdata('upload_status', ["error" => "Please import correct file, did not match excel sheet column"]);
            }
        }
    }
}
