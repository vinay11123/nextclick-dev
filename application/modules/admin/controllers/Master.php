<?php
error_reporting(E_ERROR | E_PARSE);

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Master extends MY_Controller
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
        $this->load->model('amenity_model');
        $this->load->model('service_model');
        $this->load->model('state_model');
        $this->load->model('district_model');
        $this->load->model('constituency_model');
        $this->load->model('vendor_list_model');
        $this->load->model('user_model');
        $this->load->model('user_account_model');
        $this->load->model('setting_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('permission_model');
        $this->load->model('brand_model');
        $this->load->model('request_model');
        $this->load->model('vendor_support_model');
        $this->load->model('hosp_speciality_model');
        $this->load->model('hosp_doctor_model');
        $this->load->model('od_category_model');
        $this->load->model('hosp_doctor_details_model');
        $this->load->model('od_service_model');
        $this->load->model('booking_model');
        $this->load->model('contact_model');
        $this->load->model('booking_item_model');
        $this->load->model('od_service_details_model');
        $this->load->model('service_timings_model');
        $this->load->model('arearate_model');
        $this->load->model('vehicle_model');
        $this->load->library('pagination');
        $this->load->library('session');
        $this->load->model('user_doc_model');
        $this->load->model('Categoriesbrands_model');
        $this->load->model('user_group_model');
        $this->load->model('manualpayment_model');
        $this->load->model('notification_type_model');
    }

    /**
     * Categories crud
     *
     * @author Mehar
     * @param string $type
     * @param string $target
     */
    public function category($type = 'r')
    {
        if ($type == 'c') {
            $this->form_validation->set_rules($this->category_model->rules);
            if (empty($_FILES['file']['name'])) {
                $this->form_validation->set_rules('file', 'Category Image', 'required');
            }

            if (empty($_FILES['file']['name'])) {
                $this->form_validation->set_rules('coming_soon_file', 'coming_soon_file Image', 'required');
            }

            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Category';
                $this->data['content'] = 'master/add_category';
                $this->data['nav_type'] = 'category';
                $this->data['categories'] = $this->category_model->with_brands('fields:id, name')
                    ->with_services('fields:name,desc')
                    ->with_amenities('fields:name, desc')
                    ->with_categories_services('fields:service_id')
                    ->get_all();
                $this->data['services'] = $this->service_model->order_by('id', 'DESC')->get_all();
                $this->data['brands'] = $this->brand_model->order_by('id', 'DESC')->where('status', 1)->get_all();
                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->category_model->insert([
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'terms' => $this->input->post('terms')
                ]);

                $path = $_FILES['file']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $this->file_up("file", "category", $id, '', 'no');
                $this->file_up("coming_soon_file", "coming_soon", $id, '', 'no');

                if (!empty($this->input->post('service_id'))) {
                    foreach ($this->input->post('service_id') as $sid) {
                        $this->db->insert('categories_services', [
                            'cat_id' => $id,
                            'service_id' => $sid
                        ]);
                    }
                }
                if (!empty($this->input->post('brand_id'))) {
                    foreach ($this->input->post('brand_id') as $bid) {
                        $this->db->insert('categories_brands', [
                            'cat_id' => $id,
                            'brand_id' => $bid
                        ]);
                    }
                }
                $this->session->set_flashdata('upload_status', 'category has been added successfully');
                redirect('category/r', 'refresh');
            }
        } elseif ($type == 'r') {

            $this->data['title'] = 'Category';
            $this->data['content'] = 'master/category';
            $this->data['nav_type'] = 'category';
            $this->data['categories'] = $this->category_model->with_brands('fields:id, name')
                ->with_services('fields:name,desc')
                ->with_amenities('fields:name, desc')
                ->with_categories_services('fields:service_id')
                ->order_by('name', 'ASC')
                ->get_all();
            // print_array($this->data['categories']);
            $this->data['services'] = $this->service_model->order_by('id', 'DESC')->get_all();
            $this->data['brands'] = $this->brand_model->order_by('id', 'DESC')->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {

            $this->form_validation->set_rules($this->category_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Edit Category';
                $this->data['content'] = 'master/edit';
                $this->data['type'] = 'category';
                $this->data['nav_type'] = 'category';
                $this->data['category'] = $this->category_model->where('id', $this->input->post('id'))->get();
                $this->data['categories'] = $this->category_model->with_brands('fields: id, name')
                    ->with_services('fields:id, name')
                    ->where('id', $this->input->post('id'))
                    ->get();
                $this->data['services'] = $this->service_model->get_all();
                $this->data['brands'] = $this->brand_model->order_by('id', 'DESC')->get_all();
                $this->_render_page($this->template, $this->data);
            } else {

                $this->category_model->update([
                    'id' => $this->input->post('id'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'terms' => $this->input->post('terms')
                ], $this->input->post('id'));

                $this->db->delete('categories_services', [
                    'cat_id' => $this->input->post('id')
                ]);
                if (!empty($this->input->post('service_id'))) {
                    foreach ($this->input->post('service_id') as $sid) {
                        $this->db->insert('categories_services', [
                            'cat_id' => $this->input->post('id'),
                            'service_id' => $sid
                        ]);
                    }
                }

                $this->db->delete('categories_brands', [
                    'cat_id' => $this->input->post('id')
                ]);
                if (!empty($this->input->post('brand_id'))) {
                    foreach ($this->input->post('brand_id') as $bid) {
                        $this->db->insert('categories_brands', [
                            'cat_id' => $this->input->post('id'),
                            'brand_id' => $bid
                        ]);
                    }
                }
                if ($_FILES['file']['name'] !== '') {
                    $path = $_FILES['file']['name'];
                    if (!file_exists('uploads/' . 'category' . '_image/')) {
                        mkdir('uploads/' . 'category' . '_image/', 0777, true);
                    }
                    if (file_exists('uploads/' . 'category' . '_image/' . 'category' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'category' . '_image/' . 'category' . '_' . $this->input->post('id') . '.jpg');
                    }
                    move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'category' . '_image/' . 'category' . '_' . $this->input->post('id') . '.jpg');
                }
                if ($_FILES['coming_soon_file']['name'] !== '') {
                    if (!file_exists('uploads/' . 'coming_soon' . '_image/')) {
                        mkdir('uploads/' . 'coming_soon' . '_image/', 0777, true);
                    }
                    if (file_exists('uploads/' . 'coming_soon' . '_image/' . 'coming_soon' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'coming_soon' . '_image/' . 'coming_soon' . '_' . $this->input->post('id') . '.jpg');
                    }

                    move_uploaded_file($_FILES['coming_soon_file']['tmp_name'], 'uploads/' . 'coming_soon' . '_image/' . 'coming_soon' . '_' . $this->input->post('id') . '.jpg');
                }
                $this->session->set_flashdata('upload_status', 'category has been updated successfully');
                redirect('category/r', 'refresh');
            }
        } elseif ($type == 'm') {
            $manage = $this->db->get_where(
                'manage_account',
                array(
                    'status' => 1
                )
            )->result_array();
            $i = 0;
            foreach ($manage as $ma) {
                $cat_name = $this->db->get_where(
                    'manage_account_names',
                    array(
                        'status' => 1,
                        'category_id' => $this->input->post('id'),
                        'acc_id' => $ma['id']
                    )
                );
                if ($cat_name->num_rows() == 0) {
                    $this->db->insert('manage_account_names', [
                        'category_id' => $this->input->post('id'),
                        'acc_id' => $ma['id'],
                        'name' => $this->input->post($ma['desc']),
                        'desc' => $ma['desc'],
                        'field_status' => $this->input->post('r' . $ma['desc'])
                    ]);
                } else {
                    $this->db->where('id', $cat_name->row()->id)
                        ->update(
                            'manage_account_names',
                            array(
                                'name' => $this->input->post($ma['desc']),
                                'desc' => $ma['desc'],
                                'acc_id' => $ma['id'],
                                'field_status' => $this->input->post('r' . $ma['desc'])
                            )
                        );
                }
                $i++;
            }

            redirect('category/r', 'refresh');
        } elseif ($type == 'd') {
            echo $this->category_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->session->set_flashdata('delete_status', 'category has been deleted successfully');
        } elseif ($type == 'edit') {

            $this->data['title'] = 'Edit Category';
            $this->data['content'] = 'master/edit';
            $this->data['nav_type'] = 'category';
            $this->data['type'] = 'category';
            $this->data['category'] = $this->category_model->where('id', $this->input->get('id'))
                ->get();
            $this->data['categories'] = $this->category_model->with_brands('fields: id, name')
                ->with_services('fields:id, name')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->data['services'] = $this->service_model->get_all();
            $this->data['brands'] = $this->brand_model->order_by('id', 'DESC')->where('status', 1)->get_all();

            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'export') {
            $sql = "SELECT c.name cat_name,sc.name sub_cat_name,fm.name menu_name,b.name brand_name,fi.item_type pro_type,fi.name pro_name,fsc.name var_name FROM `food_sec_item` fsc
            join food_item fi on fi.id=fsc.item_id
            join sub_categories sc on sc.id=fi.sub_cat_id
            join brands b on b.id=fi.brand_id
            join food_menu fm on fm.id=fi.menu_id
            join categories c on c.id=sc.cat_id
            where c.id=" . $this->input->get('id') . " order by fsc.id";

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
        } elseif ($type == 'change_status') {
            echo $this->category_model->update([
                'status' => ($this->input->post('is_checked') == 'true') ? 1 : 0
            ], $this->input->post('cat_id'));
        } elseif ($type == 'lead_mng_status') {
            echo $this->category_model->update([
                'is_having_lead_managemet' => ($this->input->post('is_checked') == 'true') ? 1 : 0
            ], $this->input->post('cat_id'));
        }
    }

    /**
     * E-Commerce brand crud
     *
     * To Manage Ecommerce Sub Categories
     *
     * @author Trupti
     * @param string $type
     */
    public function brands($type = 'r', $rowno = 0)
    {
        $value = '';
        if ($type == 'c') {
            $this->form_validation->set_rules($this->brand_model->rules['create_rules']);
            if (empty($_FILES['file']['name'])) {
                $this->form_validation->set_rules('file', 'Ecommerce Brands Image', 'required');
            }
            if ($this->input->post('name') != '') {
                $this->db->select("*");
                $this->db->from("brands");
                $this->db->where('name', $this->input->post('name'));
                $hasil = $this->db->get('')->result_array();
                if (count($hasil) > 0) {
                    $value = true;
                } else {
                    $value = false;
                }
            }
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'E-Commerece Brands';
                $this->data['content'] = 'admin/master/add_brands';
                $this->data['nav_type'] = 'brands';
                $this->data['ecom_brands'] = $this->brand_model->order_by('id', 'ASCE')->get_all();
                $this->data['categorys'] = $this->category_model->order_by('id', 'DESC')->where('status', 1)->get_all();
                $this->_render_page($this->template, $this->data);
            } else {
                if ($value == '') {
                    $id = $this->brand_model->insert([
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc')
                    ]);

                    $path = $_FILES['file']['name'];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $this->file_up("file", "brands", $id, '', 'no');

                    if (!empty($this->input->post('categorys_id'))) {
                        foreach ($this->input->post('categorys_id') as $cid) {
                            $this->db->insert('categories_brands', [
                                'cat_id' => $cid,
                                'brand_id' => $id
                            ]);
                        }
                    }
                    $this->session->set_flashdata('upload_status', 'Brands has been added successfully');
                    redirect('brands/r/0', 'refresh');
                } else {
                    $this->session->set_flashdata('upload_status', ["error" => 'Brand Name is already exist']);
                    $this->data['title'] = 'E-Commerece Brands';
                    $this->data['content'] = 'admin/master/add_brands';
                    $this->data['nav_type'] = 'brands';
                    $this->data['ecom_brands'] = $this->brand_model->order_by('id', 'ASCE')->get_all();
                    $this->_render_page($this->template, $this->data);
                }
            }
        } elseif ($type == 'r') {
            $search_text = "";
            $noofrows = 10;
            $this->data['title'] = 'E-Commerece Brands';
            $this->data['content'] = 'admin/master/brands';
            $this->data['nav_type'] = 'brands';

            if ($this->input->server('REQUEST_METHOD') === 'POST') {
                $search_text = $this->input->post('q');
                $group = $this->input->post('group');
                $this->session->set_userdata(
                    array(
                        "q" => $search_text,
                        'group' => $group,
                    )
                );
            } elseif ($rowno > 0 &&  ($this->session->userdata('q') != NULL || $this->session->userdata('group') != NULL)) {
                    $search_text = $this->session->userdata('q');
                    $group = $this->session->userdata('group');
                
            }else{
				$this->session->unset_userdata(['q','group']);
			}
            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }
            $this->data['q'] = $search_text;
            $this->data['group'] = $group;
            $this->data['ecom_brands'] = $this->brand_model->get_brands_data($rowperpage, $rowno, $group, $search_text);
            $allcount = $this->brand_model->get_brands_count($group, $search_text);
            //print_r($this->db->last_query());exit;

            $users_record = $this->brand_model->get_brands_data($rowperpage, $rowno, $group, $search_text);


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
            $config['base_url'] = base_url() . 'brands/r';
            $config['first_url'] = base_url() . 'brands/r/0';
            ;

            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $rowperpage;
            $this->pagination->initialize($config);

            $this->data['pagination'] = $this->pagination->create_links();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->brand_model->rules['update_rules']);
            if ($this->input->post('name') != '') {
                $this->db->select("*");
                $this->db->from("brands");
                $this->db->where('name', $this->input->post('name'));
                $this->db->where('id !=', $this->input->post('id'));
                $hasil = $this->db->get('')->result_array();
                if (count($hasil) > 0) {
                    $value = true;
                } else {
                    $value = false;
                }
            }
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Edit E-Commerce Brands';
                $this->data['content'] = 'admin/master/edit';
                $this->data['type'] = 'brand';
                $this->data['nav_type'] = 'brands';
                $this->data['ecom_brands'] = $this->brand_model->order_by('id', 'DESC')
                    ->where('id', $this->input->post('id'))
                    ->get();
                $this->_render_page($this->template, $this->data);
            } else {
                if ($value == '') {
                    $this->brand_model->update([
                        'id' => $this->input->post('id'),
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc')
                    ], 'id');

                    if ($_FILES['file']['name'] !== '') {
                        if (!file_exists('uploads/' . 'brands' . '_image/')) {
                            mkdir('uploads/' . 'brands' . '_image/', 0777, true);
                        }

                        if (file_exists('uploads/' . 'brands' . '_image/' . 'brands' . '_' . $this->input->post('id') . '.jpg')) {
                            unlink('uploads/' . 'brands' . '_image/' . 'brands' . '_' . $this->input->post('id') . '.jpg');
                        }
                        move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'brands' . '_image/' . 'brands' . '_' . $this->input->post('id') . '.jpg');
                    }

                    $this->db->delete('categories_brands', [
                        'brand_id' => $this->input->post('id')
                    ]);
                    if (!empty($this->input->post('categorys_id'))) {
                        foreach ($this->input->post('categorys_id') as $cid) {
                            $this->db->insert('categories_brands', [
                                'cat_id' => $cid,
                                'brand_id' => $this->input->post('id')
                            ]);
                        }
                    }
                    $this->session->set_flashdata('upload_status', 'Brands has been updated successfully');
                    $page = $this->input->post('page') ?? 1;
					redirect('brands/r/' . $page, 'refresh');
                  //  redirect('brands/r/0', 'refresh');
                } else {
                    $this->session->set_flashdata('upload_status', ["error" => 'Brand Name is already exist']);
                    $this->data['title'] = 'Edit E-Commerce Brands';
                    $this->data['content'] = 'admin/master/edit';
                    $this->data['type'] = 'brand';
                    $this->data['nav_type'] = 'brands';
                    $this->data['ecom_brands'] = $this->brand_model->order_by('id', 'DESC')
                        ->where('id', $this->input->post('id'))
                        ->get();
                    $this->_render_page($this->template, $this->data);
                }
            }
        } elseif ($type == 'd') {
            echo $this->brand_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->session->set_flashdata('delete_status', 'Brands has been deleted successfully');
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit E-Commerce Brands';
            $this->data['content'] = 'admin/master/edit';
            $this->data['nav_type'] = 'brands';
            $this->data['type'] = 'brand';
            $this->data['i'] = $this->brand_model->where('file', $this->input->get('file'))
                ->get();
            $this->data['ecom_brands'] = $this->brand_model->order_by('id', 'DESC')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->db->select("group_concat(cat_id) as categories_ids");
            $this->db->from("categories_brands");
            $this->db->where('brand_id', $this->input->get('id'));
            $this->data['categories'] = $this->db->get('')->result();
            $this->data['categorys'] = $this->category_model->order_by('id', 'DESC')->where('status', 1)->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'list') {
            $data = $this->ecom_sub_category_model->with_brands('fields:id, name, desc')
                ->with_ecom_sub_sub_categories('fields:id, name, desc')
                ->where([
                    'id' => $this->input->post('sub_cat_id')
                ])
                ->get_all();
            echo json_encode($data);
        } elseif ($type == 'change_status') {
            echo $this->brand_model->update([
                'status' => ($this->input->post('is_checked') == 'true') ? 1 : 2
            ], $this->input->post('brand_id'));
        }
    }

    /**
     * Sub_Category crud
     *
     * @author Mehar
     * @param string $type
     * @param string $target
     */
    public function sub_category($type = 'r', $rowno = 0)
    {
        if ($type == 'c') {

            $this->form_validation->set_rules($this->sub_category_model->rules['sub_category']);

            if (empty($_FILES['file']['name'])) {
                $this->form_validation->set_rules('file', 'sub_category Image', 'required');
            }

            if ($this->input->post('cat_id') != '' && $this->input->post('name') && $this->input->post('type')) {
                $this->db->select("*");
                $this->db->from("sub_categories");
                $this->db->where('name', $this->input->post('name'));
                $this->db->where('cat_id', $this->input->post('cat_id'));
                $this->db->where('type', $this->input->post('type'));
                $hasil = $this->db->get('')->result_array();
                if (count($hasil) > 0) {
                    $value = true;
                } else {
                    $value = false;
                }
            }
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Sub_Category';
                $this->data['content'] = 'master/add_sub_category';
                $this->data['nav_type'] = 'sub_category';
                $this->data['categories'] = $this->category_model->order_by('name', 'asc')->get_all();
                $this->data['sub_categories'] = $this->sub_category_model->order_by('id', 'DESC')->get_all();
                $this->_render_page($this->template, $this->data);
            } else {
                if ($value == '') {
                    $id = $this->sub_category_model->insert([
                        'cat_id' => $this->input->post('cat_id'),
                        'name' => $this->input->post('name'),
                        'type' => $this->input->post('type'),
                        'desc' => $this->input->post('desc'),
                        'product_type_widget_status' => 2,
                        'status' => 1
                    ]);

                    if ($this->input->post('type') == 2) {
                        $this->db->insert('shop_by_categories', [
                            'vendor_id' => $this->ion_auth->get_user_id(),
                            'cat_id' => $this->input->post('cat_id'),
                            'sub_cat_id' => $id
                        ]);
                    }
                    $path = $_FILES['file']['name'];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $this->file_up("file", "sub_category", $id, '', 'no');
                    $this->session->set_flashdata('upload_status', 'Sub Category has been added successfully');
                    redirect('sub_category/r/0', 'refresh');
                } else {
                    $this->session->set_flashdata('upload_status', ["error" => 'Sub category is already exist']);
                    $this->data['title'] = 'Sub_Category';
                    $this->data['content'] = 'master/add_sub_category';
                    $this->data['nav_type'] = 'sub_category';
                    $this->data['categories'] = $this->category_model->order_by('name', 'asc')->get_all();
                    $this->data['sub_categories'] = $this->sub_category_model->order_by('id', 'DESC')->get_all();
                    $this->_render_page($this->template, $this->data);
                }
            }
} elseif ($type == 'r') {

    // Default values
    $search_text = "";
    $noofrows = 10;

    // If search form is submitted
    if ($this->input->post('submit')) {

        $search_text = $this->input->post('q');
        $noofrows    = $this->input->post('noofrows');

        // Store in session
        $this->session->set_userdata(array(
            "q" => $search_text,
            "noofrows" => $noofrows
        ));

    } else {

        // Load stored session values
        if ($this->session->userdata('q') != NULL) {
            $search_text = $this->session->userdata('q');
        }

        if ($this->session->userdata('noofrows') != NULL) {
            $noofrows = $this->session->userdata('noofrows');
        }
    }

    // Rows per page
    $rowperpage = (!empty($noofrows)) ? $noofrows : 10;

    // Calculate offset
    if ($rowno != 0) {
        $rowno = ($rowno - 1) * $rowperpage;
    }

    // Page Data
    $this->data['title']      = 'Sub_Category';
    $this->data['content']    = 'master/sub_category';
    $this->data['nav_type']   = 'sub_category';
    $this->data['categories'] = $this->category_model->get_all();

    // Fetch results
    $this->data['sub_categories'] = $this->sub_category_model->get_users($rowperpage, $rowno, $search_text);

    // Total records
    $allcount = $this->sub_category_model->users_count($search_text);

    // Pagination Config
    $config['full_tag_open']  = "<ul class='pagination'>";
    $config['full_tag_close'] = "</ul>";
    $config['num_tag_open']   = '<li class="page-item">';
    $config['num_tag_close']  = '</li>';
    $config['cur_tag_open']   = "<li class='page-item active'><a href='#'>";
    $config['cur_tag_close']  = "<span class='sr-only'></span></a></li>";
    $config['next_tag_open']  = '<li class="page-item">';
    $config['next_tagl_close'] = "</li>";
    $config['prev_tag_open']  = '<li class="page-item">';
    $config['prev_tagl_close'] = "</li>";
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tagl_close'] = "</li>";
    $config['last_tag_open']  = '<li class="page-item">';
    $config['last_tagl_close'] = "</li>";

    $config['base_url'] = base_url() . 'sub_category/r';
    $config['first_url'] = base_url() . 'sub_category/r/0';

    $config['use_page_numbers'] = TRUE;
    $config['total_rows'] = $allcount;
    $config['per_page']   = $rowperpage;

    // Initialize pagination
    $this->pagination->initialize($config);

    $this->data['pagination'] = $this->pagination->create_links();

    // Load View
    $this->_render_page($this->template, $this->data);
}
 elseif ($type == 'u') {
            $this->form_validation->set_rules($this->sub_category_model->rules['sub_category']);
            if ($this->input->post('cat_id') != '' && $this->input->post('name') && $this->input->post('type')) {
                $this->db->select("*");
                $this->db->from("sub_categories");
                $this->db->where('name', $this->input->post('name'));
                $this->db->where('cat_id', $this->input->post('cat_id'));
                $this->db->where('type', $this->input->post('type'));
                $this->db->where('id !=', $this->input->post('id'));
                $hasil = $this->db->get('')->result_array();
                if (count($hasil) > 0) {
                    $value = true;
                } else {
                    $value = false;
                }
            }
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Edit sub_category';
                $this->data['content'] = 'master/edit';
                $this->data['nav_type'] = 'sub_category';
                $this->data['type'] = 'sub_category';
                $this->data['sub_categories'] = $this->sub_category_model->order_by('id', 'DESC')
                    ->where('id', $this->input->post('id'))
                    ->get();
                $this->data['categories'] = $this->category_model->order_by('id', 'DESC')->get_all();
                $this->_render_page($this->template, $this->data);
            } else {
                if ($value == '') {
                    $this->sub_category_model->update([
                        'cat_id' => $this->input->post('cat_id'),
                        'name' => $this->input->post('name'),
                        'type' => $this->input->post('type'),
                        'product_type_widget_status' => $this->input->post('product_type_widget_status'),
                        'desc' => $this->input->post('desc')
                    ], $this->input->post('id'));
                    if ($_FILES['file']['name'] !== '') {
                        if (!file_exists('uploads/' . 'sub_category' . '_image/')) {
                            mkdir('uploads/' . 'sub_category' . '_image/', 0777, true);
                        }
                        if (file_exists('uploads/' . 'sub_category' . '_image/' . 'sub_category' . '_' . $this->input->post('id') . '.jpg')) {
                            unlink('uploads/' . 'sub_category' . '_image/' . 'sub_category' . '_' . $this->input->post('id') . '.jpg');
                        }
                        move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'sub_category' . '_image/' . 'sub_category' . '_' . $this->input->post('id') . '.jpg');
                    }
                    $this->session->set_flashdata('upload_status', 'Sub Category has been updated successfully');
                    $page = $this->input->post('page') ?? 1;
					redirect('sub_category/r/' . $page, 'refresh');
                    //redirect('sub_category/r/0', 'refresh');
                } else {
                    $this->session->set_flashdata('upload_status', ["error" => 'Sub category is already exist']);
                    $this->data['title'] = 'Edit sub_category';
                    $this->data['content'] = 'master/edit';
                    $this->data['nav_type'] = 'sub_category';
                    $this->data['type'] = 'sub_category';
                    $this->data['sub_categories'] = $this->sub_category_model->order_by('id', 'DESC')
                        ->where('id', $this->input->post('id'))
                        ->get();
                    $this->data['categories'] = $this->category_model->order_by('id', 'DESC')->get_all();
                    $this->_render_page($this->template, $this->data);
                }
            }
        } elseif ($type == 'd') {
            $this->sub_category_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->session->set_flashdata('delete_status', 'Sub Category has been deleted successfully');
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit sub_category';
            $this->data['content'] = 'master/edit';
            $this->data['type'] = 'sub_category';
            $this->data['nav_type'] = 'sub_category';
            $this->data['sub_categories'] = $this->sub_category_model->order_by('id', 'DESC')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->data['categories'] = $this->category_model->order_by('id', 'DESC')->get_all();
            $this->_render_page($this->template, $this->data);
        }
    }

    /**
     * Amenities crud
     *
     * @author Mehar
     * @param string $type
     * @param string $target
     */
    public function amenity($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('amenity'))
         * redirect('admin');
         */
        if ($type == 'c') {
            $this->form_validation->set_rules($this->amenity_model->rules['create_rules']);
            if (empty($_FILES['file']['name'])) {
                $this->form_validation->set_rules('file', 'Amenity Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Amenity';
                $this->data['content'] = 'master/add_amenity';
                $this->data['nav_type'] = 'amenity';
                $this->data['categories'] = $this->category_model->get_all();
                $this->data['amenities'] = $this->amenity_model->order_by('id', 'DESC')->get_all();
                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->amenity_model->insert([
                    'cat_id' => $this->input->post('cat_id'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc')
                ]);
                $path = $_FILES['file']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $this->file_up("file", "amenity", $id, '', 'no');
                $this->session->set_flashdata('upload_status', 'Amenity has been added successfully');
                redirect('amenity/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Amenity';
            $this->data['content'] = 'master/amenity';
            $this->data['nav_type'] = 'amenity';
            $this->data['categories'] = $this->category_model->get_all();
            $this->data['amenities'] = $this->amenity_model->order_by('id', 'DESC')->get_all();
            $this->_render_page($this->template, $this->data);
            // echo json_encode($this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->amenity_model->rules['update_rules']);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Edit Amenity';
                $this->data['content'] = 'master/edit';
                $this->data['nav_type'] = 'amenity';
                $this->data['type'] = 'amenity';
                $this->data['amenity'] = $this->amenity_model->order_by('id', 'DESC')
                    ->where('id', $this->input->post('id'))
                    ->get();
                $this->data['categories'] = $this->category_model->order_by('id', 'DESC')->get_all();
                $this->_render_page($this->template, $this->data);
            } else {
                $this->amenity_model->update([
                    'id' => $this->input->post('id'),
                    'cat_id' => $this->input->post('cat_id'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc')
                ], 'id');
                if ($_FILES['file']['name'] !== '') {
                    // $this->file_up("file", "amenity", $this->input->post('id'), '', 'no');
                    if (file_exists('uploads/' . 'amenity' . '_image/' . 'amenity' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'amenity' . '_image/' . 'amenity' . '_' . $this->input->post('id') . '.jpg');
                    }
                    move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'amenity' . '_image/' . 'amenity' . '_' . $this->input->post('id') . '.jpg');
                }
                $this->session->set_flashdata('upload_status', 'Amenity has been updated successfully');
                redirect('amenity/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->amenity_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->session->set_flashdata('delete_status', 'Amenity has been deleted successfully');
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Amenity';
            $this->data['content'] = 'master/edit';
            $this->data['nav_type'] = 'amenity';
            $this->data['type'] = 'amenity';
            $this->data['amenity'] = $this->amenity_model->order_by('id', 'DESC')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->data['categories'] = $this->category_model->order_by('id', 'DESC')->get_all();
            $this->_render_page($this->template, $this->data);
        }
    }

    /**
     * Services crud
     *
     * @author Mehar
     * @param string $type
     * @param string $target
     */
    public function service($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('service'))
         * redirect('admin');
         */
        if ($type == 'c') {
            $this->form_validation->set_rules($this->service_model->rules);

            if (empty($_FILES['file']['name'])) {
                $this->form_validation->set_rules('file', 'Service Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Service';
                $this->data['content'] = 'master/add_service';
                $this->data['nav_type'] = 'service';
                $this->data['services'] = $this->service_model->order_by('id', 'DESC')
                    ->with_permissions('fields: perm_name, perm_key')
                    ->get_all();
                $this->data['permissions'] = $this->ion_auth_acl->permissions('full', 'perm_key', [
                    'parent_status' => 'parent'
                ]);
                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->service_model->insert([
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'languages' => $this->input->post('languages'),
                    'permission_parent_ids' => implode(',', $this->input->post('perm_id'))
                ]);
                $path = $_FILES['file']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $this->file_up("file", "service", $id, '', 'no');
                foreach ($this->input->post('perm_id') as $pid) {
                    $child_permissions = $this->permission_model->where('parent_status', $pid)->get_all();
                    foreach ($child_permissions as $permission) {
                        $this->db->insert('services_permissions', [
                            'service_id' => $id,
                            'perm_id' => $permission['id']
                        ]);
                    }
                    $this->db->insert('services_permissions', [
                        'service_id' => $id,
                        'perm_id' => $pid
                    ]);
                }
                $this->session->set_flashdata('upload_status', 'Service has been added successfully');
                redirect('service/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Service';
            $this->data['content'] = 'master/service';
            $this->data['nav_type'] = 'service';
            $this->data['services'] = $this->service_model->order_by('id', 'DESC')
                ->with_permissions('fields: perm_name, perm_key')
                ->get_all();
            $this->data['permissions'] = $this->ion_auth_acl->permissions('full', 'perm_key', [
                'parent_status' => 'parent'
            ]);
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->service_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Edit Service';
                $this->data['content'] = 'master/edit';
                $this->data['type'] = 'service';
                $this->data['nav_type'] = 'service';
                $this->data['services'] = $this->service_model->where('id', $this->input->post('id'))
                    ->get();
                $this->data['perm_ids'] = explode(',', $this->data['services']['permission_parent_ids']);
                $this->data['permissions'] = $this->ion_auth_acl->permissions('full', 'perm_key', [
                    'parent_status' => 'parent'
                ]);
                $this->_render_page($this->template, $this->data);
            } else {
                $this->service_model->update([
                    'id' => $this->input->post('id'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'languages' => $this->input->post('languages'),
                    'permission_parent_ids' => implode(',', $this->input->post('perm_id'))
                ], 'id');
                if ($_FILES['file']['name'] !== '') {
                    if (file_exists('uploads/' . 'service' . '_image/' . 'service' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'service' . '_image/' . 'service' . '_' . $this->input->post('id') . '.jpg');
                    }
                    move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'service' . '_image/' . 'service' . '_' . $this->input->post('id') . '.jpg');
                }
                $this->db->delete('services_permissions', [
                    'service_id' => $this->input->post('id')
                ]);
                foreach ($this->input->post('perm_id') as $pid) {
                    $child_permissions = $this->permission_model->where('parent_status', $pid)->get_all();
                    foreach ($child_permissions as $permission) {
                        $this->db->insert('services_permissions', [
                            'service_id' => $this->input->post('id'),
                            'perm_id' => $permission['id']
                        ]);
                    }
                    $this->db->insert('services_permissions', [
                        'service_id' => $this->input->post('id'),
                        'perm_id' => $pid
                    ]);
                }
                $this->session->set_flashdata('upload_status', 'Service has been updated successfully');
                $page = $this->input->post('page') ?? 1;
				redirect('service/r/' . $page, 'refresh');
               // redirect('service/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->service_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->session->set_flashdata('delete_status', 'Service has been deleted successfully');
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Service';
            $this->data['content'] = 'master/edit';
            $this->data['type'] = 'service';
            $this->data['nav_type'] = 'service';
            $this->data['services'] = $this->service_model->where('id', $this->input->get('id'))
                ->get();
            $this->data['perm_ids'] = explode(',', $this->data['services']['permission_parent_ids']);
            $this->data['permissions'] = $this->ion_auth_acl->permissions('full', 'perm_key', [
                'parent_status' => 'parent'
            ]);
            // print_array( $this->data['services']);
            $this->_render_page($this->template, $this->data);
        }
    }

    /**
     * States crud
     *
     * @author Mehar
     * @param string $type
     * @param string $target
     */
    public function state($type = 'r')
    {
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));


        /*
         * if (! $this->ion_auth_acl->has_permission('state'))
         * redirect('admin');
         */
        if ($type == 'c') {

            $this->form_validation->set_rules('name', 'State Name', 'callback_check_duplicate_state');

            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'States';
                $this->data['content'] = 'master/state';
                $this->data['nav_type'] = 'state';
                $this->data['states'] = $this->state_model->order_by('id', 'DESC')->get_all();
                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->state_model->insert([
                    'name' => $this->input->post('name')
                ]);
                $this->session->set_flashdata('upload_status', 'States has been added successfully');
                redirect('state/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'States';
            $this->data['content'] = 'master/state';
            $this->data['nav_type'] = 'state';
            $this->data['states'] = $this->state_model->order_by('id', 'DESC')->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules('name', 'State Name', 'callback_check_duplicate_state');

            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Edit State';
                $this->data['content'] = 'master/edit';
                $this->data['nav_type'] = 'state';
                $this->data['type'] = 'state';
                $this->data['state'] = $this->state_model->order_by('id', 'DESC')
                    ->where('id', $this->input->post('id'))
                    ->get();
                $this->_render_page($this->template, $this->data);
            } else {
                $this->state_model->update([
                    'id' => $this->input->post('id'),
                    'name' => $this->input->post('name')
                ], 'id', 'name');
                $this->session->set_flashdata('upload_status', 'States has been updated successfully');
                redirect('state/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->state_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->session->set_flashdata('delete_status', 'States has been deleted successfully');
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit State';
            $this->data['nav_type'] = 'state';
            $this->data['content'] = 'master/edit';
            $this->data['type'] = 'state';
            $this->data['state'] = $this->state_model->order_by('id', 'DESC')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'state_bulk_upload') {
            if (!$this->ion_auth->logged_in())
                redirect('auth/login');

            $excel_file = $_FILES['excel_file'];
            $is_updated = $this->upload_bulk_state($excel_file);
            $this->data['title'] = 'State bulk upload';
            $this->data['content'] = 'admin/master/upload_states_page';
            $this->data['nav_type'] = 'state_upload';
            $this->_render_page($this->template, $this->data);
        }
    }

    function check_duplicate_state($name)
    {

        $this->load->model('state_model');

        if (empty($name)) {
            $this->form_validation->set_message('check_duplicate_state', 'The {field} field is required.');
            return FALSE;
        } else {
            $state_id = $this->input->post('id');

            if (!empty($state_id)) {

                if ($this->state_model->is_state_name_exists($name, $state_id)) {

                    $this->form_validation->set_message('check_duplicate_state', 'The {field} already exists.');
                    return FALSE;
                } else {
                    return TRUE;
                }

            } else {

                if ($this->state_model->is_state_name_exists($name)) {

                    $this->form_validation->set_message('check_duplicate_state', 'The {field} already exists.');
                    return FALSE;
                } else {
                    return TRUE;
                }
            }
        }
    }

    public function upload_bulk_state($file)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        // If file uploaded
        if (!empty($file['name'])) {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', '0');
            // If file uploaded
            if (!empty($_FILES['excel_file']['name'])) {
                // get file extension
                $extension = pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION);

                if ($extension == 'csv') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } elseif ($extension == 'xlsx') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                }
                // file path
                $spreadsheet = $reader->load($_FILES['excel_file']['tmp_name']);
                $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                $arrayCount = count($allDataInSheet);
                $flag = 0;
                $createArray = array('name');
                $makeArray = array('name' => 'name');
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
                        $state = $SheetDataKey['name'];
                        $state = filter_var(trim($allDataInSheet[$i][$state]), FILTER_SANITIZE_STRING);

                        $id = $this->state_model->insert([
                            'name' => $state
                        ]);
                    }
                    $successMessage = "States successfully imported..!";
                    $this->session->set_flashdata('upload_status', ["success" => $successMessage]);
                } else {
                    $this->session->set_flashdata('upload_status', ["error" => "Please import correct file, did not match excel sheet column"]);
                }
            }
        }
    }

    /**
     * Districts crud
     *
     * @author Mehar
     * @param string $type
     * @param string $target
     */
    public function district($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('district'))
         * redirect('admin');
         */
        if ($type == 'c') {

            $this->form_validation->set_rules('name', 'District Name', 'callback_check_duplicate_district');
            $this->form_validation->set_rules('state_id', 'State Name', 'required');


            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'District';
                $this->data['content'] = 'master/district';
                $this->data['nav_type'] = 'district';
                $this->data['states'] = $this->state_model->get_all();
                $this->data['districts'] = $this->district_model->order_by('id', 'DESC')->get_all();
                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->district_model->insert([
                    'state_id' => $this->input->post('state_id'),
                    'name' => $this->input->post('name')
                ]);
                $this->session->set_flashdata('upload_status', 'Districts has been added successfully');
                redirect('district/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'District';
            $this->data['content'] = 'master/district';
            $this->data['nav_type'] = 'district';
            $this->data['states'] = $this->state_model->get_all();
            $this->data['districts'] = $this->district_model->order_by('id', 'DESC')->get_all();
            $this->_render_page($this->template, $this->data);
            // echo json_encode($this->data);
        } elseif ($type == 'u') {

            $this->form_validation->set_rules('name', 'District Name', 'callback_check_duplicate_district');

            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Edit District';
                $this->data['content'] = 'master/edit';
                $this->data['nav_type'] = 'district';
                $this->data['type'] = 'district';
                $this->data['states'] = $this->state_model->get_all();
                $this->data['district'] = $this->district_model->order_by('id', 'DESC')
                    ->where('id', $this->input->post('id'))
                    ->get();
                $this->_render_page($this->template, $this->data);
            } else {
                $this->district_model->update([
                    'id' => $this->input->post('id'),
                    'state_id' => $this->input->post('state_id'),
                    'name' => $this->input->post('name')
                ], 'id');
                $this->session->set_flashdata('upload_status', 'Districts has been updated successfully');
                redirect('district/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->district_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->session->set_flashdata('delete_status', 'Districts has been deleted successfully');
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit District';
            $this->data['content'] = 'master/edit';
            $this->data['nav_type'] = 'district';
            $this->data['type'] = 'district';
            $this->data['states'] = $this->state_model->get_all();
            $this->data['district'] = $this->district_model->order_by('id', 'DESC')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'district_bulk_upload') {
            if (!$this->ion_auth->logged_in())
                redirect('auth/login');

            $excel_file = $_FILES['excel_file'];
            $is_updated = $this->upload_bulk_district($excel_file);
            $this->data['title'] = 'District bulk upload';
            $this->data['content'] = 'admin/master/upload_districts_page';
            $this->data['nav_type'] = 'district_upload';
            $this->_render_page($this->template, $this->data);
        }
    }

    function check_duplicate_district($name)
    {

        $this->load->model('district_model');
        $state_id = $this->input->post('state_id');
        if (empty($name)) {
            $this->form_validation->set_message('check_duplicate_district', 'The {field} field is required.');
            return FALSE;
        } else {

            $district_id = $this->input->post('id');
            if (!empty($district_id)) {

                if ($this->district_model->is_district_name_exists($name, $state_id, $district_id)) {

                    $this->form_validation->set_message('check_duplicate_district', 'The {field} already exists.');
                    return FALSE;
                } else {
                    return TRUE;
                }

            } else {
                if (!empty($state_id)) {
                    if ($this->district_model->is_district_name_exists($name, $state_id)) {
                        $this->form_validation->set_message('check_duplicate_district', 'The {field} already exists.');
                        return FALSE;
                    } else {
                        return TRUE;
                    }
                }
            }
        }
    }

    public function upload_bulk_district($file)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        // If file uploaded
        if (!empty($file['name'])) {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', '0');
            // If file uploaded
            if (!empty($_FILES['excel_file']['name'])) {
                // get file extension
                $extension = pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION);

                if ($extension == 'csv') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } elseif ($extension == 'xlsx') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                }
                // file path
                $spreadsheet = $reader->load($_FILES['excel_file']['tmp_name']);
                $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                $arrayCount = count($allDataInSheet);
                $flag = 0;
                $createArray = array('state_id', 'name');
                $makeArray = array('state_id' => 'state_id', 'name' => 'name');
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
                        $state_id = $SheetDataKey['state_id'];
                        $district = $SheetDataKey['name'];

                        $state_id = filter_var(trim($allDataInSheet[$i][$state_id]), FILTER_SANITIZE_STRING);
                        $district = filter_var(trim($allDataInSheet[$i][$district]), FILTER_SANITIZE_STRING);

                        $id = $this->district_model->insert([
                            'state_id' => $state_id,
                            'name' => $district
                        ]);
                    }
                    $successMessage = "Districts successfully imported..!";
                    $this->session->set_flashdata('upload_status', ["success" => $successMessage]);
                } else {
                    $this->session->set_flashdata('upload_status', ["error" => "Please import correct file, did not match excel sheet column"]);
                }
            }
        }
    }

    /**
     * Constituency crud
     *
     * @author Mehar
     * @param string $type
     * @param string $target
     */
    public function constituency($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('constituency'))
         * redirect('admin');
         */
        if ($type == 'c') {
            $this->form_validation->set_rules('name', 'Constituency Name', 'callback_check_duplicate_constituency');
            $this->form_validation->set_rules('state_id', 'State Name', 'required');
            $this->form_validation->set_rules('dist_id', 'District Name', 'required');
            $this->form_validation->set_rules('pincode', 'Pincode', 'callback_check_duplicate_pincode');

            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Constituency';
                $this->data['content'] = 'master/constituency';
                $this->data['nav_type'] = 'constituency';
                $this->data['states'] = $this->state_model->get_all();
                $this->data['districts'] = $this->district_model->get_all();
                $this->data['constituencies'] = $this->constituency_model->order_by('id', 'DESC')->get_all();
                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->constituency_model->insert([
                    'state_id' => $this->input->post('state_id'),
                    'district_id' => $this->input->post('dist_id'),
                    'name' => $this->input->post('name'),
                    'pincode' => $this->input->post('pincode')
                ]);
                $this->session->set_flashdata('upload_status', 'Constituency has been added successfully');
                redirect('constituency/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Constituency';
            $this->data['content'] = 'master/constituency';
            $this->data['nav_type'] = 'constituency';
            $this->data['states'] = $this->state_model->get_all();
            $this->data['districts'] = $this->district_model->get_all();
            $this->data['constituencies'] = $this->constituency_model->order_by('id', 'DESC')->get_all();
            $this->_render_page($this->template, $this->data);
            // echo json_encode($this->data);
        } elseif ($type == 'u') {

            $this->form_validation->set_rules('name', 'Constituency Name', 'callback_check_duplicate_constituency');
            $this->form_validation->set_rules('state_id', 'State Name', 'required');
            $this->form_validation->set_rules('dist_id', 'District Name', 'required');
            $this->form_validation->set_rules('pincode', 'Pincode', 'callback_check_duplicate_pincode');

            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Edit Constituency';
                $this->data['content'] = 'master/edit';
                $this->data['type'] = 'constituency';
                $this->data['nav_type'] = 'constituency';
                $this->data['districts'] = $this->district_model->get_all();
                $this->data['states'] = $this->state_model->get_all();
                $this->data['constituency'] = $this->constituency_model->order_by('id', 'DESC')
                    ->where('id', $this->input->post('id'))
                    ->get();
                $this->_render_page($this->template, $this->data);
            } else {
                $this->constituency_model->update([
                    'id' => $this->input->post('id'),
                    'state_id' => $this->input->post('state_id'),
                    'district_id' => $this->input->post('dist_id'),
                    'name' => $this->input->post('name'),
                    'pincode' => $this->input->post('pincode')
                ], 'id');
                $this->session->set_flashdata('upload_status', 'Constituency has been updated successfully');
                redirect('constituency/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->constituency_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->session->set_flashdata('delete_status', 'Constituency has been deleted successfully');
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Constituency';
            $this->data['content'] = 'master/edit';
            $this->data['nav_type'] = 'constituency';
            $this->data['type'] = 'constituency';
            $this->data['districts'] = $this->district_model->get_all();
            $this->data['states'] = $this->state_model->get_all();
            $this->data['constituency'] = $this->constituency_model->order_by('id', 'DESC')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'constituency_bulk_upload') {
            if (!$this->ion_auth->logged_in())
                redirect('auth/login');

            $excel_file = $_FILES['excel_file'];
            $is_updated = $this->upload_bulk_constituency($excel_file);
            $this->data['title'] = 'Constituency bulk upload';
            $this->data['content'] = 'admin/master/upload_constituency_page';
            $this->data['nav_type'] = 'constituency_upload';
            $this->_render_page($this->template, $this->data);
        }
    }

    public function check_duplicate_constituency($name)
    {

        $this->load->model('constituency_model');
        $state_id = $this->input->post('state_id');
        $district_id = $this->input->post('dist_id');

        if (empty($name)) {
            $this->form_validation->set_message('check_duplicate_constituency', 'The {field} field is required.');
            return FALSE;
        } else {

            $constituency_id = $this->input->post('id');
            if (!empty($constituency_id)) {

                if ($this->constituency_model->is_constituency_name_exists($name, $state_id, $district_id, $constituency_id)) {

                    $this->form_validation->set_message('check_duplicate_constituency', 'The {field} already exists.');
                    return FALSE;
                } else {
                    return TRUE;
                }

            } else {
                if (!empty($state_id) && !empty($district_id)) {
                    if ($this->constituency_model->is_constituency_name_exists($name, $state_id, $district_id)) {
                        $this->form_validation->set_message('check_duplicate_constituency', 'The {field} already exists.');
                        return FALSE;
                    } else {
                        return TRUE;
                    }
                }

            }
        }
    }

    public function check_duplicate_pincode($pincode)
    {

        $this->load->model('constituency_model');

        if (!empty($pincode)) {

            $constituency_id = $this->input->post('id');
            if (!empty($constituency_id)) {

                if ($this->constituency_model->is_constituency_pincode_exists($pincode, $constituency_id)) {

                    $this->form_validation->set_message('check_duplicate_pincode', 'The {field} already exists.');
                    return FALSE;
                } else {
                    return TRUE;
                }


            } else {

                if ($this->constituency_model->is_constituency_pincode_exists($pincode)) {

                    $this->form_validation->set_message('check_duplicate_pincode', 'The {field} already exists.');
                    return FALSE;
                } else {
                    return TRUE;
                }
            }
        }
    }

    public function upload_bulk_constituency($file)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        // If file uploaded
        if (!empty($file['name'])) {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', '0');
            // If file uploaded
            if (!empty($_FILES['excel_file']['name'])) {
                // get file extension
                $extension = pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION);

                if ($extension == 'csv') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } elseif ($extension == 'xlsx') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                }
                // file path
                $spreadsheet = $reader->load($_FILES['excel_file']['tmp_name']);
                $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                $arrayCount = count($allDataInSheet);
                $flag = 0;
                $createArray = array('state_id', 'district_id', 'name', 'pincode');
                $makeArray = array('state_id' => 'state_id', 'district_id' => 'district_id', 'name' => 'name', 'pincode' => 'pincode');
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

                        $state_id = $SheetDataKey['state_id'];
                        $district_id = $SheetDataKey['district_id'];
                        $constituency = $SheetDataKey['name'];
                        $pincode = $SheetDataKey['pincode'];

                        $state_id = filter_var(trim($allDataInSheet[$i][$state_id]), FILTER_SANITIZE_STRING);
                        $district_id = filter_var(trim($allDataInSheet[$i][$district_id]), FILTER_SANITIZE_STRING);
                        $constituency = filter_var(trim($allDataInSheet[$i][$constituency]), FILTER_SANITIZE_STRING);
                        $pincode = filter_var(trim($allDataInSheet[$i][$pincode]), FILTER_SANITIZE_STRING);

                        $id = $this->constituency_model->insert([
                            'state_id' => $state_id,
                            'district_id' => $district_id,
                            'name' => $constituency,
                            'pincode' => $pincode
                        ]);
                    }
                    $successMessage = "Constituencies successfully imported..!";
                    $this->session->set_flashdata('upload_status', ["success" => $successMessage]);
                } else {
                    $this->session->set_flashdata('upload_status', ["error" => "Please import correct file, did not match excel sheet column"]);
                }
            }
        }
    }

    public function adhar_card($type = 'change__st')
    {
        $is_updated = $this->user_doc_model->update([
            'created_user_id' => $this->input->post('user_id'),
            'adhar_card_status' => ($this->input->post('is_checked') == 'true') ? 1 : 0,
            'adhar_card_message' => $this->input->post('aadhar_reason'),
        ], 'created_user_id');
        echo json_encode($is_updated);
    }

    public function pan_card($type = 'change__st')
    {

        $is_updated = $this->user_doc_model->update([
            'created_user_id' => $this->input->post('user_id'),
            'pan_card_status' => ($this->input->post('is_checked') == 'true') ? 1 : 0,
            'pan_card_message' => $this->input->post('pan_card_reason'),
        ], 'created_user_id');
        echo json_encode($is_updated);
    }



    public function cancel_cheque($type = 'change__st')
    {
        $is_updated = $this->user_doc_model->update([
            'created_user_id' => $this->input->post('user_id'),
            'cancel_cheque_status' => ($this->input->post('is_checked') == 'true') ? 1 : 0,
            'cancel_cheque_message' => $this->input->post('cancel_cheque_reason'),
        ], 'created_user_id');
        echo json_encode($is_updated);
    }

    public function driving_licence($type = 'change__st')
    {

        $is_updated = $this->user_doc_model->update([
            'created_user_id' => $this->input->post('user_id'),
            'driving_licence_status' => ($this->input->post('is_checked') == 'true') ? 1 : 0,
            'driving_licence_message' => $this->input->post('driving_licence_reason'),
        ], 'created_user_id');
        echo json_encode($is_updated);
    }

    public function pass_book($type = 'change__st')
    {
        $is_updated = $this->user_doc_model->update([
            'created_user_id' => $this->input->post('user_id'),
            'pass_book_status' => ($this->input->post('is_checked') == 'true') ? 1 : 0,
            'pass_book_message' => $this->input->post('pass_book_reason'),
        ], 'created_user_id');
        echo json_encode($is_updated);
    }

    public function rc($type = 'change__st')
    {
        $is_updated = $this->user_doc_model->update([
            'created_user_id' => $this->input->post('user_id'),
            'rc_status' => ($this->input->post('is_checked') == 'true') ? 1 : 0,
            'rc_message' => $this->input->post('rc_reason'),
        ], 'created_user_id');
        echo json_encode($is_updated);
    }

    public function user_status()
    {
        $id = $this->input->get('id');

        $this->user_model->update([
            'status' => 1
        ], $id);

        $URL = $this->agent->referrer();
        redirect($URL);
    }


    public function deliveryboystatus($type = 'change__st_active')
    {
        $delivery_boy_data = $this->user_model->where([
            'id' => $this->input->post('user_id')
        ])->get();
        if ($delivery_boy_data['is_delivery_partner_email_sent'] == 0 && $this->input->post('is_checked') == 'true') {
            $data = array(
                'delivery_boy_name' => $delivery_boy_data['first_name']
            );
            $message = $this->load->view('delivery_boy_approval_tem', $data, true);
            $this->email->clear();
            $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
            $this->email->to($delivery_boy_data['email']);
            $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - Approval Mail');
            $this->email->message($message);
            $this->email->send();

            $this->email->send();

            $this->user_model->update([
                'delivery_partner_approval_status' => ($this->input->post('is_checked') == 'true') ? 1 : 0,
                'is_delivery_partner_email_sent' => 1
            ], $this->input->post('user_id'));
        } else {
            $this->user_model->update([
                'delivery_partner_approval_status' => ($this->input->post('is_checked') == 'true') ? 1 : 0
            ], $this->input->post('user_id'));
        }
        if ($this->input->post('is_checked') == 'true') {
            $this->user_group_model->approveGroup($this->input->post('user_id'), 'delivery_partner');
        } else {
            $this->user_group_model->disApproveGroup($this->input->post('user_id'), 'delivery_partner');
        }
    }

    public function deliveryboy($type = 'change__st')
    {


        $this->user_model->update([
            'delivery_partner_status' => ($this->input->post('is_checked') == 'true') ? 1 : 2
        ], $this->input->post('vendor_id'));

        /*  $exe = $this->vendor_list_model->with_executive('fields: id, wallet')
                ->where('id', $this->input->post('vendor_id'))
                ->as_array()
                ->get();

            $this->user_model->update([
                'id' => $exe['executive']['id'],
                'wallet' => ($this->input->post('is_checked') == 'true') ? $exe['executive']['wallet'] + floatval($this->setting_model->where('key', 'pay_per_vendor')
                    ->get()['value']) : $exe['executive']['wallet']
            ], 'id');


            if ($_POST['is_checked'] == 'true') {
                $id = $this->wallet_transaction_model->insert([
                    'user_id' => $exe['executive']['id'],
                    'type' => 'CREDIT',
                    'cash' => floatval($this->setting_model->where('key', 'pay_per_vendor')
                        ->get()['value']),
                    'description' => $exe['name'],
                    'status' => 1
                ]);
                echo json_encode($exe);*/
    }
    public function vendors($type = 'all')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('vendor_list'))
         * redirect('admin');
         */
        if ($type == 'all') {
            $this->data['title'] = 'All Vendors';
            $this->data['content'] = 'master/vendor_list';
            $this->data['type'] = 'all';
            $this->data['categories'] = $this->category_model->get_all();
            $this->data['executive'] = $this->user_model->get_all();
            $this->data['constituency'] = $this->constituency_model->get_all();
            $this->data['vendors'] = $this->vendor_list_model->order_by('id', 'DESC')
                ->with_location('fields:id, address')
                ->with_trashed()
                ->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'approved') {
            $this->data['title'] = 'Approved Vendors';
            $this->data['content'] = 'master/vendor_list';
            $this->data['type'] = 'approved';
            $this->data['categories'] = $this->category_model->get_all();
            $this->data['vendors'] = $this->vendor_list_model->order_by('id', 'DESC')
                ->with_location('fields:id, address')
                ->where([
                    'status' => 1
                ])
                ->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'pending') {
            $this->data['title'] = 'Pending Vendors';
            $this->data['content'] = 'master/vendor_list';
            $this->data['type'] = 'pending';
            $this->data['categories'] = $this->category_model->get_all();
            $this->data['vendors'] = $this->vendor_list_model->order_by('id', 'DESC')
                ->with_location('fields:id, address')
                ->where([
                    'status' => 2
                ])
                ->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'vendor') {
            if (!empty($_GET['vendor_id'])) {
                $this->data['title'] = 'Vendor Details';
                $this->data['content'] = 'master/vendor_view';
                $this->data['type'] = 'vendor_view';
                $this->data['vendor_list'] = $this->vendor_list_model->with_location('fields: id, address, latitude, longitude')
                    ->with_category('fields: id, name')
                    ->with_users('fields: phone')
                    ->with_constituency('fields: id, name, state_id, district_id')
                    ->with_contacts('fields: id, std_code, number, type')
                    ->with_links('fields: id,   url, type')
                    ->with_amenities('fields: id, name')
                    ->with_services('fields: id, name')
                    ->with_holidays('fields: id')
                    ->with_executive('fields:id,unique_id')
                    ->where('id', $_GET['vendor_id'])
                    ->get();
                $this->_render_page($this->template, $this->data);
            }
        } elseif ($type == 'd') {
            $this->vendor_list_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->db->where('id', $this->input->post('id'));
            echo $this->db->update('vendors_list', [
                'status' => 0
            ]);
        } elseif ($type == 'cancelled') {
            $this->data['title'] = 'Cancelled Vendors';
            $this->data['content'] = 'master/vendor_list';
            $this->data['type'] = 'cancelled';
            $this->data['categories'] = $this->category_model->get_all();
            $this->data['vendors'] = $this->vendor_list_model->order_by('id', 'DESC')
                ->with_location('fields:id, address')
                ->only_trashed()
                ->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'change_status') {
            $vendorObj = $this->vendor_list_model->where([
                'id' => $this->input->post('vendor_id')
            ])->get();
            $vendor_data = $this->user_model->where([
                'id' => $vendorObj['vendor_user_id']
            ])->get();
            if ($vendorObj['is_vendor_approved_email_sent'] == 0 && $this->input->post('is_checked') == 'true') {
                $data = array(
                    'vendor_name' => $vendorObj['name']
                );
                $message = $this->load->view('vendor_approval_tem', $data, true);
                $this->email->clear();
                $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
                $this->email->to($vendor_data['email']);
                $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - Approval Mail');
                $this->email->message($message);
                $this->email->send();

                $this->email->send();
                $this->vendor_list_model->update([
                    'status' => ($this->input->post('is_checked') == 'true') ? 1 : 2,
                    'is_vendor_approved_email_sent' => 1
                ], $this->input->post('vendor_id'));
            } else {
                $this->vendor_list_model->update([
                    'status' => ($this->input->post('is_checked') == 'true') ? 1 : 2
                ], $this->input->post('vendor_id'));
            }

            $exe = $this->vendor_list_model->with_executive('fields: id, wallet')
                ->where('id', $this->input->post('vendor_id'))
                ->as_array()
                ->get();
            $userAccount = $this->user_account_model->where([
                'user_id' => $exe['executive']['id']
            ])->get();

            if ($_POST['is_checked'] == 'true') {
                // $this->send_sms('\'Congratulations! , now you are a member to the Nextclick Family Login ID : ' . $exe['unique_id'] . '. Regards, NEXTCLICK.\'', $mobile);
            }

            if ($_POST['is_checked'] == 'true') {
                if (!empty($exe['executive']['id'])) {
                    $wallet_type = 'wallet';
                    $user = $this->user_model->with_executive_address('fields: executive_type_id')->where('id', $exe['executive']['id'])->get();
                    if (isset($user['executive_address']) && $user['executive_address']['executive_type_id'] == 1) { // Wallet update only for Freelancer
                        $this->user_account_model->update([
                            'wallet' => ($this->input->post('is_checked') == 'true') ? $userAccount['wallet'] + floatval($this->setting_model->where('key', 'pay_per_vendor')
                                ->get()['value']) : $userAccount['wallet']
                        ], ['user_id' => $exe['executive']['id']]);
                        $id = $this->wallet_transaction_model->insert([
                            'account_user_id' => $exe['executive']['id'],
                            'created_user_id' => !empty($this->ion_auth->get_user_id()) ? $this->ion_auth->get_user_id() : $exe['executive']['id'],
                            'amount' => floatval($this->setting_model->where('key', 'pay_per_vendor')->get()['value']),
                            'balance' => (floatval($userAccount['wallet'])) + (floatval($this->setting_model->where('key', 'pay_per_vendor')->get()['value'])),
                            'txn_id' => 'NC-' . generate_trasaction_no(),
                            'ecom_order_id' => NULL,
                            'type' => 'CREDIT',
                            'message' => NULL,
                            'status' => 1
                        ]);
                    }
                }
                $this->user_group_model->approveGroup($vendorObj['vendor_user_id'], 'vendor');
                echo json_encode($exe);
            } else {
                $this->user_group_model->disApproveGroup($vendorObj['vendor_user_id'], 'vendor');
            }
        } elseif ($type == 'cover_update') {
            $user_id = $this->input->post('id');
            if ($_FILES['cover']['name'] !== '') {
                move_uploaded_file($_FILES['cover']['tmp_name'], "./uploads/list_cover_image/list_cover_$user_id.jpg");
            }
            redirect('vendors/vendor?vendor_id=' . $user_id);
        }
    }

    /**
     * Repquest crud
     *
     * @author Trupti
     * @param string $type
     * @param string $target
     */
    public function request($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('state'))
         * redirect('admin');
         */
        if ($type == 'c') {
            $this->form_validation->set_rules($this->request_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Add Request';
                $this->data['nav_type'] = 'request';
                $this->data['content'] = 'master/add_request';
                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->request_model->insert([
                    'title' => $this->input->post('title'),
                    'desc' => $this->input->post('desc')
                ]);
                if (!empty($id))
                    $this->session->set_flashdata('request_success', 'Request Submitted Successfully..!');
                else
                    $this->session->set_flashdata('error', 'Something went wrong..!');

                redirect('request/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Request';
            $this->data['content'] = 'master/request';
            $this->data['nav_type'] = 'request';
            $this->data['request'] = $this->request_model->order_by('id', 'DESC')->get_all();
            $this->_render_page($this->template, $this->data);
            // echo json_encode($this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->request_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $this->request_model->update([
                    'id' => $this->input->post('id'),
                    'title' => $this->input->post('title'),
                    'desc' => $this->input->post('desc')
                ], 'id');
                redirect('request/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->request_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit request';
            $this->data['content'] = 'master/edit';
            $this->data['type'] = 'request';
            $this->data['nav_type'] = 'request';
            $this->data['request'] = $this->request_model->order_by('id', 'DESC')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->_render_page($this->template, $this->data);
        }
    }

    /**
     * Support crud
     *
     * @author Trupti
     * @param string $type
     * @param string $target
     */
    /*  public function support($type = 'r')
    {
        if ($type == 'c') {
            $this->form_validation->set_rules($this->vendor_support_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Add Request';
                $this->data['content'] = 'master/vendor_support';
                $this->data['nav_type'] = 'support';
                $this->data['request_type'] = $this->request_model->get_all();

                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->vendor_support_model->insert([
                    'req_id' => $this->input->post('req_id'),
                    'vendor_id' => $this->ion_auth->get_user_id(),
                    'name'=> $this->input->post('fullname'),
                    'contact_mail' => $this->input->post('contact_mail'),
                    'req_content' => $this->input->post('req_content')
                ]);
                redirect('admin/master/support/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Request';
            $this->data['content'] = 'master/support';
            $this->data['nav_type'] = 'support';

if ($this->input->post('req_id') != NULL) {

            $this->data['request_type'] = $this->request_model->get_all();
            $this->data['requests'] = $this->vendor_support_model->where('status = ', 1)->where('req_id = ',$this->input->post('req_id'))
                    ->with_users('fields:id,unique_id,email')->get_all();
}


if($this->input->post('submit') == NULL)
{
     $this->data['request_type'] = $this->request_model->get_all();
     $this->data['requests'] = $this->vendor_support_model->where('status = ', 1)
     ->with_users('fields:id,unique_id,email')->get_all();
}
           
        $this->_render_page($this->template, $this->data);
            

        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->vendor_support_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {

                $this->vendor_support_model->update([
                    'id' => $this->input->post('id'),
                    'req_id' => $this->input->post('req_id'),
                    'vendor_id' => $this->ion_auth->get_user_id(),
                     'name'=> $this->input->post('fullname'),
                    'contact_mail' => $this->input->post('contact_mail'),
                    'req_content' => $this->input->post('req_content')
                ], 'id');
                redirect('admin/master/support/r', 'refresh');
            }
        } elseif ($type == 'delete') {
             $id = base64_decode(base64_decode($this->input->get('id')));
            $this->vendor_support_model->delete([
                'id' => $id
            ]);
            redirect('admin/master/support/r', 'refresh');
        } elseif ($type == 'edit') {

   $id = base64_decode(base64_decode($this->input->get('id')));
 
            $this->data['title'] = 'Edit request';
            $this->data['content'] = 'master/edit_support';
            $this->data['type'] = 'request';
            $this->data['nav_type'] = 'support';
             $this->data['request_type'] = $this->request_model->get_all();
            $this->data['requests'] = $this->vendor_support_model->order_by('id', 'DESC')
                ->where('id', $id)
                ->get();
            $this->_render_page($this->template, $this->data);

        } elseif ($type == 'list') {
            $this->data['title'] = 'Edit request';
            $this->data['content'] = 'master/support';
            $this->data['nav_type'] = 'support';
            $this->data['type'] = 'request';
            $from = $_POST['fromdate'];
            $to = $_POST['todate'];

            if ($from != NULL && $to == NULL) {

                $this->data['request_type'] = $this->request_model->get_all();
                // $this->data['request'] = $this->vendor_support_model->order_by('id', 'DESC')->where('created_at')->get();

                $converted_date = date("Y-m-d" . ' 00:00:00', strtotime($from));
                $this->data['requests'] = $this->vendor_support_model->where('created_at >= ', $from)
                    ->with_users('fields:id,unique_id,email')
                    ->get_all(); // print_r( $this->data['request']);
            } elseif ($from != NULL && $to != NULL) {
                $this->data['request_type'] = $this->request_model->get_all();
                // $this->data['request'] = $this->vendor_support_model->order_by('id', 'DESC')->get_all();
                $converted_date = date("Y-m-d" . ' 00:00:00', strtotime($from));
                $this->data['requests'] = $this->vendor_support_model->where('created_at BETWEEN $from AND $to')
                    ->with_users('fields:id,unique_id,email')
                    ->get_all();
            } elseif ($from == NULL && $to != NULL) {
                $this->data['request_type'] = $this->request_model->get_all();
                $converted_date = date("Y-m-d" . ' 00:00:00', strtotime($to));
                $this->data['requests'] = $this->vendor_support_model->where('created_at <= ', $to)
                    ->with_users('fields:id,unique_id,email')
                    ->get_all();
            } else {
                $this->data['request_type'] = $this->request_model->get_all();
                $this->data['requests'] = $this->vendor_support_model->with_users('fields:id,unique_id,email')
                    ->order_by('id', 'DESC')
                    ->get_all();
                // print_array($this->data['requests']);exit();
            }

            $this->_render_page($this->template, $this->data);
        }
    }*/

    /**
     * Specialities crud
     *
     * @author Trupti
     * @param string $type
     * @param string $target
     */
    public function specialities($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('admin'))
         * redirect('admin');
         */
        if ($type == 'c') {
            $this->form_validation->set_rules($this->hosp_speciality_model->rules);

            if (empty($_FILES['file']['name'])) {
                $this->form_validation->set_rules('file', 'Speciality Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Specialty';
                $this->data['content'] = 'master/add_speciality';
                $this->data['nav_type'] = 'specialty';
                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->hosp_speciality_model->insert([
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc')
                ]);

                $this->file_up("file", "speciality", $id, '', 'no');

                redirect('specialities/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Specialty ';
            $this->data['nav_type'] = 'specialty';
            $this->data['content'] = 'master/specialities_list';
            $this->data['specialities'] = $this->hosp_speciality_model->order_by('id', 'DESC')->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->hosp_speciality_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Edit specialities';
                $this->data['content'] = 'master/edit';
                $this->data['nav_type'] = 'specialty';
                $this->data['type'] = 'specialities';
                $this->data['specialities'] = $this->hosp_speciality_model->where('id', $this->input->post('id'))
                    ->get();
                $this->_render_page($this->template, $this->data);
            } else {
                $this->hosp_speciality_model->update([
                    'id' => $this->input->post('id'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc')
                ], 'id');

                if ($_FILES['file']['name'] !== '') {
                    if (file_exists('uploads/' . 'speciality' . '_image/' . 'speciality' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'speciality' . '_image/' . 'speciality' . '_' . $this->input->post('id') . '.jpg');
                    }
                    move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'speciality' . '_image/' . 'speciality' . '_' . $this->input->post('id') . '.jpg');
                }

                redirect('specialities/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->hosp_speciality_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit specialities';
            $this->data['nav_type'] = 'specialty';
            $this->data['content'] = 'master/edit';
            $this->data['type'] = 'specialities';
            $this->data['specialities'] = $this->hosp_speciality_model->where('id', $this->input->get('id'))
                ->get();

            // print_array( $this->data['services']);
            $this->_render_page($this->template, $this->data);
        }
    }

    public function doctors_approve($type = 'r')
    {
        if ($type == 'r') {
            $this->data['title'] = 'Pending List';
            $this->data['content'] = 'master/list_of_doctors';
            $this->data['nav_type'] = 'vendor_doctors';
            $this->data['type'] = 'doctors_approve';
            // Code by Trupti
            // $this->data['pending_list'] = $this->hosp_doctor_model->with_doctor_details('fields:id,name,qualification,experience')->where(['status'=> 3]) ->get_all();
            // $this->data['approved_list'] = $this->hosp_doctor_model->with_doctor_details('fields:id,name,qualification,experience')->where(['status'=> 2]) ->get_all();
            // print_array( $this->data['pending_list']);
            // Code with he help of mehar
            $this->data['approved_list'] = $this->db->query("SELECT  hdd.id, hdd.hosp_doctor_id, hdd.hosp_specialty_id, hdd.name, hdd.desc, hdd.qualification, hdd.experience, hdd.languages, hdd.fee, hdd.discount, hdd.holidays, hdd.created_user_id from hosp_doctors as hd
                    join hosp_doctors_details as hdd on (hd.id = hdd.hosp_doctor_id and hd.created_user_id = hdd.created_user_id)
                    where hdd.deleted_at is null and hd.status = 2")->result_array();
            // print_array( $this->data['approved_list']);
            $this->data['pending_list'] = $this->db->query("SELECT  hdd.id, hdd.hosp_doctor_id, hdd.hosp_specialty_id, hdd.name, hdd.desc, hdd.qualification, hdd.experience, hdd.languages, hdd.fee, hdd.discount, hdd.holidays, hdd.created_user_id from hosp_doctors as hd
                    join hosp_doctors_details as hdd on (hd.id = hdd.hosp_doctor_id and hd.created_user_id = hdd.created_user_id)
                    where hdd.deleted_at is null and hd.status = 3")->result_array();

            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'approve') {
            // $id = ($this->input->get('id'));
            $id = ($this->input->get('id'));
            $this->hosp_doctor_model->update(
                array(
                    'status' => 2
                ),
                $id
            );
            // print_array([$id]);
            redirect('doctors_approve/r', 'refresh');
        } elseif ($type == 'disapprove') {
            $id = ($this->input->get('id'));
            $this->hosp_doctor_model->update(
                array(
                    'status' => 3
                ),
                $id
            );
            redirect('doctors_approve/r', 'refresh');
        }
    }

    public function od_categories_approve($type = 'r')
    {
        if ($type == 'r') {
            $this->data['title'] = 'Pending List';
            $this->data['content'] = 'master/od_category_approve';
            $this->data['nav_type'] = 'vendor_od_services';
            $this->data['type'] = 'od_categories_approve';
            // Code by Trupti
            // $this->data['pending_list'] = $this->hosp_doctor_model->with_doctor_details('fields:id,name,qualification,experience')->where(['status'=> 3]) ->get_all();
            // $this->data['approved_list'] = $this->hosp_doctor_model->with_doctor_details('fields:id,name,qualification,experience')->where(['status'=> 2]) ->get_all();
            // print_array( $this->data['pending_list']);
            // Code with he help of mehar
            $this->data['approved_list'] = $this->db->query("SELECT osd.id, osd.od_service_id, osd.od_cat_id, osd.name, osd.desc, osd.service_duration, osd.price, osd.discount from od_services as os join od_services_details as osd on (os.id = osd.od_service_id and os.created_user_id = osd.created_user_id) where osd.deleted_at is null and os.status = 2")->result_array();

            $this->data['pending_list'] = $this->db->query("SELECT osd.id, osd.od_service_id, osd.od_cat_id, osd.name, osd.desc, osd.service_duration, osd.price, osd.discount from od_services as os join od_services_details as osd on (os.id = osd.od_service_id and os.created_user_id = osd.created_user_id) where osd.deleted_at is null and os.status = 3")->result_array();

            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'approve') {
            $id = ($this->input->get('id'));
            $this->od_service_model->update(
                array(
                    'status' => 2
                ),
                $id
            );
            redirect('od_categories_approve/r', 'refresh');
        } elseif ($type == 'disapprove') {
            $id = ($this->input->get('id'));
            $this->od_service_model->update(
                array(
                    'status' => 3
                ),
                $id
            );
            redirect('od_categories_approve/r', 'refresh');
        }
    }

    /**
     * On Demand Categories crud
     *
     * @author Trupti
     * @param string $type
     * @param string $target
     */
    public function od_categories($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('service'))
         * redirect('admin');
         */
        if ($type == 'c') {
            $this->form_validation->set_rules($this->od_category_model->rules);

            if (empty($_FILES['file']['name'])) {
                $this->form_validation->set_rules('file', 'On Demand Category Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'On Demand Category';
                $this->data['content'] = 'master/add_od_category';
                $this->data['nav_type'] = 'od_category';
                $this->data['categories'] = $this->category_model->get_all();
                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->od_category_model->insert([
                    'cat_id' => $this->input->post('cat_id'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc')
                ]);

                $this->file_up("file", "od_category", $id, '', 'no');

                redirect('od_categories/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'On Demand Category ';
            $this->data['content'] = 'master/od_category_list';
            $this->data['nav_type'] = 'od_category';
            $this->data['od_categories'] = $this->od_category_model->with_category('fields:id,name')
                ->order_by('id', 'DESC')
                ->get_all();
            // print_array($this->data['od_categories']);exit();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->od_category_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Edit On Demand Category';
                $this->data['content'] = 'master/edit';
                $this->data['nav_type'] = 'od_category';
                $this->data['type'] = 'od_categories';
                $this->data['od_categories'] = $this->od_category_model->where('id', $this->input->post('id'))
                    ->get();
                $this->_render_page($this->template, $this->data);
            } else {
                $this->od_category_model->update([
                    'id' => $this->input->post('id'),
                    'cat_id' => $this->input->post('cat_id'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc')
                ], 'id');

                if ($_FILES['file']['name'] !== '') {
                    if (file_exists('uploads/' . 'od_category' . '_image/' . 'od_category' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'od_category' . '_image/' . 'od_category' . '_' . $this->input->post('id') . '.jpg');
                    }
                    move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'od_category' . '_image/' . 'od_category' . '_' . $this->input->post('id') . '.jpg');
                }

                redirect('od_categories/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->od_category_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit On Demand Category';
            $this->data['content'] = 'master/edit';
            $this->data['type'] = 'od_categories';
            $this->data['nav_type'] = 'od_category';
            $this->data['od_categories'] = $this->od_category_model->where('id', $this->input->get('id'))
                ->get();
            $this->data['categories'] = $this->category_model->get_all();
            $this->_render_page($this->template, $this->data);
        }
    }

    public function doctors($type = 'r')
    {
        if ($type == 'c') {
            $this->form_validation->set_rules($this->hosp_doctor_details_model->rules);
            if (empty($_FILES['file']['name'])) {
                $this->form_validation->set_rules('file', 'Doctor Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Doctors By Admin';
                $this->data['content'] = 'master/add_doctors';
                $this->data['type'] = 'doctors';
                $this->data['nav_type'] = 'doctor';
                $this->data['specialities'] = $this->hosp_speciality_model->get_all();
                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->hosp_doctor_model->insert([
                    "hosp_specialty_id" => $this->input->post("hosp_specialty_id"),

                    "status" => 1
                ]);

                if ($id) {
                    $is_id = $this->hosp_doctor_details_model->insert([
                        'hosp_specialty_id' => $this->input->post('hosp_specialty_id'),
                        'hosp_doctor_id' => $id,
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('qualification'),
                        'experience' => $this->input->post('experience'),
                        'languages' => $this->input->post('languages'),
                        'fee' => $this->input->post('fee'),
                        'discount' => $this->input->post('discount'),
                        'qualification' => $this->input->post('qualification')
                    ]);
                }
                $this->file_up("file", "doctors", $id, '', 'no');

                redirect('doctors/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Doctors By Admin ';
            $this->data['content'] = 'master/doctors_list';
            $this->data['nav_type'] = 'doctor';
            $this->data['type'] = 'doctors';
            $this->data['doctors'] = $this->hosp_doctor_model->with_doctor_details('fields:id,name, desc,qualification,experience,languages,holidays,fee, discount')
                ->order_by('id', 'DESC')
                ->get_all();
            // print_array($this->data['doctors']);exit();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->hosp_doctor_model->rules['create']);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Doctors By Admin ';
                $this->data['content'] = 'master/doctors_list';
                $this->data['nav_type'] = 'doctor';
                $this->data['type'] = 'doctors';
                // $this->data['doctors'] = $this->hosp_doctor_model->with_doctor_details('fields:id,name, desc,qualification,experience,languages,holidays,fee, discount')->order_by('id', 'DESC')->get_all();
                $this->data['doctors'] = $this->hosp_doctor_details_model->where('id', $this->input->post('id'))
                    ->get();
            } else {
                $this->hosp_doctor_details_model->update([
                    'id' => $this->input->post('id'),
                    'hosp_doctor_id' => $this->input->post('hosp_doctor_id'),
                    'hosp_specialty_id' => $this->input->post('hosp_specialty_id'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'experience' => $this->input->post('experience'),
                    'languages' => $this->input->post('languages'),
                    'fee' => $this->input->post('fee'),
                    'discount' => $this->input->post('discount'),
                    'qualification' => $this->input->post('qualification')
                ], [
                    'id',
                    'hosp_doctor_id'
                ]);

                if ($_FILES['file']['name'] !== '') {
                    // $this->file_up("file", "amenity", $this->input->post('id'), '', 'no');
                    if (file_exists('uploads/' . 'doctors' . '_image/' . 'doctors' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'doctors' . '_image/' . 'doctors' . '_' . $this->input->post('id') . '.jpg');
                    }
                    move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'doctors' . '_image/' . 'doctors' . '_' . $this->input->post('id') . '.jpg');
                }
            }
        } elseif ($type == 'd') {
            $this->hosp_doctor_details_model->delete([
                'id' => $this->input->post('id')
            ]);
            $is_exist = $this->hosp_doctor_model->where([
                'id' => $this->input->post('hosp_doctor_id')
            ])
                ->get();
            if (!empty($is_exist)) {
                $this->hosp_doctor_model->delete([
                    'id' => $this->input->post('hosp_doctor_id')
                ]);
            }
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Doctors';
            $this->data['content'] = 'master/edit';
            $this->data['nav_type'] = 'doctor';
            $this->data['type'] = 'doctors';
            $this->data['doctors'] = $this->hosp_doctor_details_model->where('id', $this->input->get('id'))
                ->get();
            $this->data['specialities'] = $this->hosp_speciality_model->get_all();
            // print_array( $this->data['services']);
            $this->_render_page($this->template, $this->data);
        }
    }

    /**
     * On Demand Categories crud
     *
     * @author Trupti
     * @param string $type
     * @param string $target
     */
    public function od_services($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('service'))
         * redirect('admin');
         */
        if ($type == 'c') {
            $this->form_validation->set_rules($this->od_service_details_model->rules);

            if (empty($_FILES['file']['name'])) {
                $this->form_validation->set_rules('file', 'On Demand Service Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'On Demand Category';
                $this->data['content'] = 'master/add_od_service';
                $this->data['nav_type'] = 'od_service';
                $this->data['od_categories'] = $this->od_category_model->get_all();
                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->od_service_model->insert([
                    'od_cat_id' => $this->input->post('od_cat_id'),
                    "status" => 1
                ]);

                if ($id) {
                    $is_id = $this->od_service_details_model->insert([
                        'od_cat_id' => $this->input->post('od_cat_id'),
                        'od_service_id' => $id,
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc'),
                        'service_duration' => $this->input->post('service_duration'),
                        'price' => $this->input->post('price'),
                        'discount' => $this->input->post('discount')
                    ]);
                }

                $this->file_up("file", "od_service", $id, '', 'no');

                redirect('od_services/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'On Demand Services ';
            $this->data['content'] = 'master/od_service_list';
            $this->data['nav_type'] = 'od_service';
            $this->data['od_services'] = $this->od_service_details_model->order_by('id', 'DESC')->get_all();
            $this->data['od_categories'] = $this->od_category_model->get_all();
            // print_array($this->data['od_categories']);exit();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->od_category_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Edit On Demand Category';
                $this->data['content'] = 'master/edit';
                $this->data['nav_type'] = 'od_service';
                $this->data['type'] = 'od_categories';
                $this->data['od_categories'] = $this->od_service_details_model->where('id', $this->input->post('id'))
                    ->get();
                $this->_render_page($this->template, $this->data);
            } else {
                $this->od_service_details_model->update([
                    'id' => $this->input->post('id'),
                    'od_cat_id' => $this->input->post('od_cat_id'),
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'service_duration' => $this->input->post('service_duration'),
                    'price' => $this->input->post('price'),
                    'discount' => $this->input->post('discount')
                ], 'id');
            }

            if ($_FILES['file']['name'] !== '') {
                if (file_exists('uploads/' . 'od_service' . '_image/' . 'od_service' . '_' . $this->input->post('id') . '.jpg')) {
                    unlink('uploads/' . 'od_service' . '_image/' . 'od_service' . '_' . $this->input->post('id') . '.jpg');
                }
                move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'od_service' . '_image/' . 'od_service' . '_' . $this->input->post('id') . '.jpg');
            }

            redirect('od_services/r', 'refresh');
        } elseif ($type == 'd') {
            $this->od_service_details_model->delete([
                'id' => $this->input->post('id')
            ]);
            $is_exist = $this->od_service_model->where([
                'id' => $this->input->post('od_service_id')
            ])
                ->get();
            print_r($is_exist);
            exit();
            if (!empty($is_exist)) {
                $this->od_service_model->delete([
                    'id' => $this->input->post('od_service_id')
                ]);
            }
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit On Demand Service';
            $this->data['content'] = 'master/edit';
            $this->data['type'] = 'od_services';
            $this->data['nav_type'] = 'od_service';
            $this->data['od_servicees'] = $this->od_service_details_model->where('id', $this->input->get('id'))
                ->get();
            $this->data['od_categories'] = $this->od_category_model->get_all();
            $this->_render_page($this->template, $this->data);
        }
    }

    public function bookings($type = 'r', $rowno = 0)
    {
        if ($type == 'r') {
            $this->data['title'] = 'Bookings';
            $this->data['content'] = 'master/bookings';
            $service_id = (!empty($this->input->get('service_id'))) ? $this->input->get('service_id') : $this->input->post('service_id');
            if ($service_id == 11) {
                $this->data['nav_type'] = 'doctors_booking';
            } else {
                $this->data['nav_type'] = 'od_service_booking';
            }

            $vendor_unique_id = "";
            $status = 1;
            $noofrows = 10;
            if ($this->input->post('submit') != NULL) {
                $search_text = $this->input->post('q');
                $vendor_unique_id = $this->input->post('vendor_unique_id');
                $status = $this->input->post('booking_status');
                $noofrows = $this->input->post('noofrows');
                $this->session->set_userdata(
                    array(
                        'vendor_unique_id' => $vendor_unique_id,
                        'booking_status' => $status,
                        'service_id' => $service_id,
                        'noofrows' => $noofrows
                    )
                );
            } else {
                if ($this->session->userdata('q') != NULL || $this->session->userdata('vendor_unique_id') != NULL || $this->session->userdata('booking_status') != NULL || $this->session->userdata('service_id') != NULL || $noofrows != NULL) {
                    $search_text = $this->session->userdata('q');
                    $vendor_unique_id = $this->session->userdata('vendor_unique_id');
                    $status = $this->session->userdata('booking_status');
                    $noofrows = $this->session->userdata('noofrows');
                    $service_id = $this->session->userdata('service_id');
                }
            }
            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }
            $allcount = $this->booking_model->get_bookings(NULL, NULL, $search_text, $status ? $status : 1, $vendor_unique_id, $service_id, TRUE);
            $booking_records = $this->booking_model->get_bookings($rowperpage, $rowno, $search_text, $status ? $status : 1, $vendor_unique_id, $service_id, FALSE);

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
            $config['base_url'] = base_url() . 'master/doctors_booking';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $rowperpage;

            // Initialize
            $this->pagination->initialize($config);

            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['bookings'] = $booking_records;
            $this->data['row'] = $rowno;
            $this->data['vendor_unique_id'] = $vendor_unique_id;
            $this->data['q'] = $search_text;
            $this->data['booking_status'] = $status;
            $this->data['service_id'] = $service_id;
            $this->data['noofrows'] = $rowperpage;
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'view') {
            $this->data['title'] = 'View Booking';
            $this->data['content'] = 'master/booking_view';
            $query = "SELECT bi.id, bi.price, bi.qty, bi.total, bi.discount, bi.booking_date, st.start_time, st.end_time, second.name FROM booking_items as bi  JOIN services_timings as st on st.id = bi.service_timing_id";
            if ($_GET['service_id'] = 11) {
                $this->data['nav_type'] = 'doctors_booking';
                $query .= " join hosp_doctors_details as second on second.id = bi.service_item_id";
            } else {
                $this->data['nav_type'] = 'od_service_booking';
                $query .= " join od_services_details as second on second.id = bi.service_item_id ";
            }
            $query .= " where booking_id = " . $_GET['id'];
            $rs = $this->db->query($query);
            if (!empty($rs))
                $this->data['booking_items'] = $rs->result_array();
            else
                $this->data['booking_items'] = [];

            $this->_render_page($this->template, $this->data);
        }
    }


    public function delivery_area($type = 'r', $rowno = 0)
    {
        if ($type == 'c') {
            $this->form_validation->set_rules($this->vehicle_model->rules['create_rules']);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Add Delivery Area';
                $this->data['content'] = 'master/add_delivery_area';
                $this->data['nav_type'] = 'Delivery Area';
                $this->data['state'] = $this->state_model->get_all();
                $this->data['vechile'] = $this->vehicle_model->get_all();
                $this->_render_page($this->template, $this->data);
            } else {
                if ($this->input->post('district_id') == 'stateall' && $this->input->post('constituancy_id') == 'conall') {
                    $districtdata = $this->district_model->where('state_id', $this->input->post('state_id'))->get_all();
                    $arr = array();
                    foreach ($districtdata as $s) {
                        $constituencydata = $this->constituency_model->where('district_id', $s['id'])
                            ->get_all();
                        foreach ($constituencydata as $c) {

                            $dat = $this->arearate_model->where('state_id', $this->input->post('state_id'))->where('district_id', $c['district_id'])->where('constituencies_id', $c['id'])->where('vechile_type', $this->input->post('vechile'))->get_all();
                            if (count($dat) > 0) {
                                $this->arearate_model->update([
                                    'id' => $dat[0]['id'],
                                    'state_id' => $this->input->post('state_id'),
                                    'district_id' => $c['district_id'],
                                    'constituencies_id' => $c['id'],
                                    'vechile_type' => $this->input->post('vechile'),
                                    'flat_rate' => $this->input->post('rlatrate'),
                                    'per_km' => $this->input->post('Perkm')
                                ], $dat[0]['id']);
                            } else {
                                $id = $this->arearate_model->insert([
                                    'state_id' => $this->input->post('state_id'),
                                    'district_id' => $c['district_id'],
                                    'constituencies_id' => $c['id'],
                                    'vechile_type' => $this->input->post('vechile'),
                                    'flat_rate' => $this->input->post('rlatrate'),
                                    'per_km' => $this->input->post('Perkm')
                                ]);
                            }
                        }
                    }
                    redirect('delivery_area/r/0', 'refresh');
                } else if ($this->input->post('constituancy_id') == 'conall' && $this->input->post('district_id') != 'stateall') {
                    $constituencydata = $this->constituency_model->where('district_id', $s['id'])
                        ->get_all();
                    foreach ($constituencydata as $c) {
                        $dat = $this->arearate_model->where('state_id', $this->input->post('state_id'))->where('district_id', $c['district_id'])->where('constituencies_id', $c['id'])->where('vechile_type', $this->input->post('vechile'))->get_all();
                        if (count($dat) > 0) {
                            $this->arearate_model->update([
                                'id' => $dat[0]['id'],
                                'state_id' => $this->input->post('state_id'),
                                'district_id' => $this->input->post('district_id'),
                                'constituencies_id' => $c['id'],
                                'vechile_type' => $this->input->post('vechile'),
                                'flat_rate' => $this->input->post('rlatrate'),
                                'per_km' => $this->input->post('Perkm')
                            ], $dat[0]['id']);
                        } else {

                            $id = $this->arearate_model->insert([
                                'state_id' => $this->input->post('state_id'),
                                'district_id' => $this->input->post('district_id'),
                                'constituencies_id' => $c['id'],
                                'vechile_type' => $this->input->post('vechile'),
                                'flat_rate' => $this->input->post('rlatrate'),
                                'per_km' => $this->input->post('Perkm')
                            ]);
                        }
                    }
                    redirect('delivery_area/r/0', 'refresh');
                } else {

                    $dat = $this->arearate_model->where('state_id', $this->input->post('state_id'))->where('district_id', $c['district_id'])->where('constituencies_id', $c['id'])->where('vechile_type', $this->input->post('vechile'))->get_all();
                    if (count($dat) > 0) {
                        $this->arearate_model->update([
                            'id' => $dat[0]['id'],
                            'state_id' => $this->input->post('state_id'),
                            'district_id' => $this->input->post('district_id'),
                            'constituencies_id' => $this->input->post('constituancy_id'),
                            'vechile_type' => $this->input->post('vechile'),
                            'flat_rate' => $this->input->post('rlatrate'),
                            'per_km' => $this->input->post('Perkm')
                        ], $dat[0]['id']);
                    } else {
                        $id = $this->arearate_model->insert([
                            'state_id' => $this->input->post('state_id'),
                            'district_id' => $this->input->post('district_id'),
                            'constituencies_id' => $this->input->post('constituancy_id'),
                            'vechile_type' => $this->input->post('vechile'),
                            'flat_rate' => $this->input->post('rlatrate'),
                            'per_km' => $this->input->post('Perkm')
                        ]);
                    }

                    redirect('delivery_area/r/0', 'refresh');
                }
            }
        }
        if ($type == 'r') {
        }
        if ($type == 'u') {
        }
        if ($type == 'd') {
        }
    }

    public function fetchdisdata()
    {
        $data = $this->district_model->where('state_id', $this->input->post('state_id'))
            ->get_all();
        echo "<option value=''>--select--</option>";
        echo "<option value='stateall'>All</option>";
        foreach ($data as $a) {
            echo "<option value='" . $a['id'] . "'>" . $a['name'] . "</option>";
        }
    }

    public function fetchcondata()
    {
        $data = $this->constituency_model->where('district_id', $this->input->post('district_id'))
            ->get_all();
        echo "<option value=''>--select--</option>";
        echo "<option value='conall'>All</option>";
        foreach ($data as $a) {
            echo "<option value='" . $a['id'] . "'>" . $a['name'] . "</option>";
        }
    }

    public function manageManualPayments()
    {
        $payments = $this->manualpayment_model->getPendingPayments();
        $this->data['title'] = 'Manage Manual Payments';
        $this->data['content'] = 'admin/master/manual_payments';
        $this->data['nav_type'] = 'manual_payments';
        $this->data['manual_payments'] = $this->manualpayment_model->getPendingPayments();
        $this->_render_page($this->template, $this->data);
    }

    public function manageManualPaymentslist()
    {
        $payments = $this->manualpayment_model->getPendingPayments();
        $this->data['title'] = 'Manage Manual Payments';
        $this->data['content'] = 'admin/master/manual_payments_list';
        $this->data['nav_type'] = 'manual_payments';
        $this->data['manual_payments'] = $this->manualpayment_model->order_by('created_at', 'DESC')->getall();
        $this->_render_page($this->template, $this->data);
    }

    public function process_payment()
    {
        //
        $this->load->helper('common');
        $postData = $_REQUEST;

        try {

            $manualPayment = $this->manualpayment_model->where([
                'id' => $postData['payment_ref']
            ])->get();

            switch ($manualPayment['payment_intent']) {
                case "Subscription":
                    $result = $this->updateSubscriptionPayment($manualPayment, $postData['action']);
                    //print_r($postData);die();
                    if ($result) {
                        $this->manualpayment_model->update([
                            'status' => ($postData['action'] && $postData['action'] == 'approve') ? 2 : 3
                        ], [
                            'id' => $postData['payment_ref']
                        ]);
                    }
                    return true;
                    break;
                default:
                    break;
            }
            ;
        } catch (Exception $ex) {
            print_r($ex);
            exit;
        }
    }

    public function updateSubscriptionPayment($manualPayment, $action)
    {
        if ($action == 'approve') {
            $this->load->model('subscriptions_payments_model');
            $subscriptionInfo = json_decode($manualPayment['info']);
            $update = $this->subscriptions_payments_model->updatePaymentStatus($subscriptionInfo->package_id, $manualPayment['created_user_id'], $subscriptionInfo->service_id, 3, $manualPayment['amount'], $subscriptionInfo->order_id, $manualPayment['payment_txn_id'], "Subscription Package", $subscriptionInfo->upgrade);
            $this->send_notification(
                $manualPayment['created_user_id'],
                VENDOR_APP_CODE,
                "Subscription Alert",
                "Your subscription plan got approved with payment reference #" . $manualPayment['payment_txn_id'] . "",
                ['notification_type' => $this->notification_type_model->where(['app_details_id' => 2, 'notification_code' => 'SUBS'])->get()]
            );
            return $update;
        } else {
            //send notification
            $this->send_notification(
                $manualPayment['created_user_id'],
                VENDOR_APP_CODE,
                "Subscription Alert",
                "Your subscription plan has been failed with payment reference #" . $manualPayment['payment_txn_id'] . "",
                ['notification_type' => $this->notification_type_model->where(['app_details_id' => 2, 'notification_code' => 'SUBS'])->get()]
            );
            return true;
        }
    }
    public function sub_cat_delete($type = 'd')
    {
        if ($type == 'd') {
            $this->sub_category_model->delete([
                'id' => $this->input->post('id')
            ]);
        }
    }
    
 public function export_all()
{
    $this->db->select('
        sc.id,
        sc.name as subcategory_name,
        sc.desc,
        sc.type,
        c.name as category_name
    ');

    $this->db->from('sub_categories sc');
    $this->db->join('categories c', 'c.id = sc.cat_id', 'left');
    $this->db->order_by('sc.id', 'DESC');

    $query = $this->db->get();
    $data = $query->result_array();   // ✅ GET ACTUAL DATA

    // Excel Headers
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=subcategories.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Column Headings
    echo "SNO\tSubcategory Name\tCategory Name\tType\tDescription\n";
    
    $sno = 1;
    
    foreach ($data as $row) {
    
        echo $sno++ . "\t";
        echo $row['subcategory_name'] . "\t";
        echo $row['category_name'] . "\t";
        echo ($row['type'] == 1 ? 'Listing Sub Category' : 'Shop By Category') . "\t";
        echo $row['desc'] . "\n";   // ✅ End row here only
    }


    exit;
}

}
