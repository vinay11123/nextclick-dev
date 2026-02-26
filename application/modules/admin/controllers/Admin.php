<?php
(defined('BASEPATH')) or exit('No direct script access allowed');

/**
 *
 * @author Mehar
 *         Admin module
 */
class Admin extends MY_Controller
{

    function __construct()
    {
        error_reporting(E_ERROR | E_PARSE);
        parent::__construct();
        $this->template = 'template/admin/main';
        if (!$this->ion_auth->logged_in()) // || ! $this->ion_auth->is_admin()
            redirect('auth/login');

        $this->load->library('pagination');
        $this->load->model('vendor_bank_details_model');
        $this->load->library('upload');
        $this->load->library('form_validation');
        $this->load->model('group_model');
        $this->load->model('user_model');
        $this->load->model('permission_model');
        $this->load->model('permission_batch_model');
        $this->load->model('group_permission_model');
        $this->load->model('setting_model');
        $this->load->model('sliders_model');
        $this->load->model('advertisements_model');
        $this->load->model('user_service_model');
        $this->load->model('category_model');
        $this->load->model('vendor_list_model');
        $this->load->model('cat_banners_model');
        $this->load->model('faq_model');
        $this->load->model('app_details_model');
        $this->load->model('location_model');
        $this->load->model('stock_settings_model');
        $this->load->model('termsconditions_model');
        $this->load->model('user_doc_model');
        $this->load->model('package_model');
        $this->load->model('service_model');
        $this->load->model('vendor_package_model');
        $this->load->model('vendor_list_model');
        $this->load->model('user_group_model');
        $this->load->model('return_policies_model');
        $this->load->model('service_tax_model');
        $this->load->model('state_model');
        $this->load->model('district_model');
        $this->load->model('constituency_model');
        $this->load->model('sub_category_model');
        $this->load->model('food_menu_model');
        $this->load->model('master_package_setting_model');
        $this->load->model('package_setting_model');
        $this->load->model('delivery_boy_address_model');
        $this->load->model('delivery_boy_bank_details_model');
        $this->load->model('vehicle_model');
        $this->load->model('user_account_model');
        $this->load->model('payout_model');
        $this->load->model('delivery_boy_payment_model');
        $this->load->model('delivery_partner_location_tracking_model');
        $this->load->model('cupons_model');
    }

    public function index()
    {
        if ($this->ion_auth->get_users_groups()->result()[0]->name == 'vendor') {
            redirect('vendor_crm/dashboard');
        } else {
            redirect('admin/dashboard');
        }
    }

    /**
     * Employee Management
     *
     * @author Mehar
     * @param string $type
     */
    public function employee($type = 'r', $rowno = 0)
    {
        if ($type == 'c') {
            $this->form_validation->set_rules($this->user_model->rules['creation']);
            if ($this->form_validation->run() == false) {
                $this->data['title'] = 'Add employee';
                $this->data['nav_type'] = 'employee';
                $this->data['content'] = 'emp/add_employee';
                $this->data['groups'] = $this->group_model->order_by('id', 'DESC')->get_all();
                $this->_render_page($this->template, $this->data);
            } else {
                $email = strtolower($this->input->post('email'));
                $identity = ($this->config->item('identity', 'ion_auth') === 'email') ? $email : $this->input->post('identity');
                $password = $this->input->post('password');
                $role_ids = $this->input->post('role');
                $groups = [];
                foreach ($role_ids as $id) {
                    array_push($groups, $this->group_model->where('id', $id)->get());
                }
                $groupname = $groups[0]['name'];
                $additional_data = array(
                    'primary_intent' => $groupname,
                    'email' => $this->input->post('email'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone' => $this->input->post('phone'),
                    'active' => 1
                );

                foreach ($groups as $group) {
                    if (min(array_column($groups, 'priority')) == $group['priority']) {
                        $additional_data['unique_id'] = generate_serial_no($group['code'], 4, $group['last_id']);
                        $this->group_model->update([
                            'last_id' => $group['last_id'] + 1
                        ], $group['id']);
                    }
                }

                $this->ion_auth->register($identity, $password, $email, $additional_data, $groupname);
                redirect("employee/r/0", 'refresh');
            }
        } elseif ($type == 'r') {

            $this->data['title'] = 'List of Users';
            $this->data['content'] = 'emp/employee';
            $this->data['nav_type'] = 'employee';
            // Search text
            $search_text = $unique_id = "";

            //$group = 1;
            //$noofrows = 1;
            if (isset($_POST['submit'])) {
                $search_text = $this->input->post('q');
                $unique_id = $this->input->post('unique_id');
                $group = $this->input->post('group');
                $noofrows = $this->input->post('noofrows');
                $this->session->set_userdata(
                    array(
                        "q" => $search_text,
                        'unique_id' => $unique_id,
                        'group' => $group,
                        'noofrows' => $noofrows
                    )
                );
            } else {
                if ($this->session->userdata('q') != NULL || $this->session->userdata('unique_id') != NULL || $this->session->userdata('group') != NULL || $this->session->userdata('noofrows') != NULL) {
                    $search_text = $this->session->userdata('q');
                    $unique_id = $this->session->userdata('unique_id');
                    $group = $this->session->userdata('group');
                    $noofrows = $this->session->userdata('noofrows');
                }
            }

            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }

            $allcount = $this->user_model->users_count($group, $search_text, $unique_id);
            $users_record = $this->user_model->get_users($rowperpage, $rowno, $group, $search_text, $unique_id);

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
            $config['base_url'] = base_url() . 'employee/r';
            $config['first_url'] = base_url() . 'employee/r/0';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $rowperpage;

            // Initialize
            $this->pagination->initialize($config);
            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['users'] = $users_record;
            foreach ($this->data['users'] as $key => $user) {

                $this->db->select("b.name , b.description , a.id");
                $this->db->from("users_groups as a");
                $this->db->join("groups as b", "b.id = a.group_id");
                $this->db->where("a.user_id", $user['id']);
                $this->db->order_by("b.priority", "ASC");

                $result = $this->db->get();
                $this->data['users'][$key]['groups'] = $result->result_array();

                /* $this->data['users'][$key]['groups'] = $this->db->query("SELECT groups.id, name, description FROM `users_groups` JOIN groups on groups.id = users_groups.group_id WHERE users_groups.user_id = ".$user['id']." ORDER BY groups.priority ASC")->result_array(); */
            }

            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['unique_id'] = $unique_id;
            $this->data['group'] = $group;
            $this->data['noofrows'] = $rowperpage;
            // print_array($this->data['users']);
            $this->data['groups'] = $this->group_model->order_by('id', 'DESC')->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->user_model->rules['update']);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {

                $this->user_model->update([
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'permanent_address' => $this->input->post('permanent_address'),
                    'aadhar_number' => $this->input->post('aadhar_number')
                ], $this->input->post('id'));
                // Update the groups user belongs to
                if ($_FILES['aadhar_card_front']['name'] !== '') {
                    $path = $_FILES['aadhar_card_front']['name'];
                    if (!file_exists('uploads/' . 'aadhar_card' . '_image/')) {
                        mkdir('uploads/' . 'aadhar_card' . '_image/', 0777, true);
                    }
                    if (file_exists('uploads/' . 'aadhar_card' . '_image/' . 'aadhar_card_front' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'aadhar_card' . '_image/' . 'aadhar_card_front' . '_' . $this->input->post('id') . '.jpg');
                    }
                    move_uploaded_file($_FILES['aadhar_card_front']['tmp_name'], 'uploads/' . 'aadhar_card' . '_image/' . 'aadhar_card_front' . '_' . $this->input->post('id') . '.jpg');
                }
                if ($_FILES['aadhar_card_back']['name'] !== '') {
                    $path = $_FILES['aadhar_card_back']['name'];
                    if (!file_exists('uploads/' . 'aadhar_card' . '_image/')) {
                        mkdir('uploads/' . 'aadhar_card' . '_image/', 0777, true);
                    }
                    if (file_exists('uploads/' . 'aadhar_card' . '_image/' . 'aadhar_card_back' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'aadhar_card' . '_image/' . 'aadhar_card_back' . '_' . $this->input->post('id') . '.jpg');
                    }
                    move_uploaded_file($_FILES['aadhar_card_back']['tmp_name'], 'uploads/' . 'aadhar_card' . '_image/' . 'aadhar_card_back' . '_' . $this->input->post('id') . '.jpg');
                }
                if ($_FILES['bank_passbook_image']['name'] !== '') {
                    $path = $_FILES['bank_passbook_image']['name'];
                    if (!file_exists('uploads/' . 'bank_passbook' . '_image/')) {
                        mkdir('uploads/' . 'bank_passbook' . '_image/', 0777, true);
                    }
                    if (file_exists('uploads/' . 'bank_passbook' . '_image/' . 'bank_passbook' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'bank_passbook' . '_image/' . 'bank_passbook' . '_' . $this->input->post('id') . '.jpg');
                    }
                    move_uploaded_file($_FILES['bank_passbook_image']['tmp_name'], 'uploads/' . 'bank_passbook' . '_image/' . 'bank_passbook' . '_' . $this->input->post('id') . '.jpg');
                }

                $groupData = $this->input->post('role');
                if (isset($groupData) && !empty($groupData)) {
                    $this->ion_auth->remove_from_group('', $this->input->post('id'));
                    foreach ($groupData as $grp) {
                        $this->ion_auth->add_to_group($grp, $this->input->post('id'));
                    }
                }
                $page = $this->input->post('page') ?? 1;
				redirect('employee/r/' . $page, 'refresh');
                //redirect("employee/r/0", 'refresh');
            }
        } elseif ($type == 'd') {
            $this->user_model->update([
                'active' => 0
            ], $this->input->post('id'));
            echo $this->user_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'employee';
            $this->data['content'] = 'emp/edit';
            $this->data['nav_type'] = 'employee';
            $this->data['type'] = 'user';
            $this->data['users'] = $this->user_model->with_groups('fields: name, id')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->data['locations'] = $this->location_model->where('id', $this->data['users']['location_id'])->get();
            $this->data['groups'] = $this->group_model->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'eye') {
            $this->data['title'] = 'employee';
            $this->data['content'] = 'emp/emp_eye';
            $this->data['nav_type'] = 'employee';
            $this->data['type'] = 'user';
            $this->data['users'] = $this->user_model->with_groups('fields: name, id')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->data['tc'] = $this->termsconditions_model->users_tc($this->input->get('id'));
            $this->_render_page($this->template, $this->data);
        }
    }

    /**
     * Role Management
     *
     * @author Mehar
     * @param string $type
     */
    public function role($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('role'))
         * redirect('admin');
         */
        if ($type == 'c') {
            $this->form_validation->set_rules($this->group_model->rules);
            if ($this->form_validation->run() == true) {
                $group_id = $this->group_model->insert([
                    'name' => $this->input->post('name'),
                    'code' => $this->input->post('prefix'),
                    'priority' => $this->input->post('priority'),
                    'description' => $this->input->post('desc'),
                    'terms' => $this->input->post('terms'),
                    'privacy' => $this->input->post('privacy')
                ]);
                if ($group_id > 0) {
                    foreach ($this->input->post() as $k => $v) {
                        if (substr($k, 0, 5) == 'perm_') {
                            $permission_id = str_replace("perm_", "", $k);
                            if ($v == "X")
                                $this->ion_auth_acl->remove_permission_from_group($group_id, $permission_id);
                            else
                                $this->ion_auth_acl->add_permission_to_group($group_id, $permission_id, $v);
                        }
                    }
                    redirect("role/r", 'refresh');
                } else {
                    echo 'internal server error';
                }
            } else {
                echo validation_errors();
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Roles';
            $this->data['content'] = 'emp/role';
            $this->data['nav_type'] = 'role';
            $this->data['groups'] = $this->group_model->order_by('id', 'DESC')
                ->with_permissions('fields: id, perm_name, perm_key, parent_status')
                ->get_all();
            if (!empty($this->data['groups'])) {
                foreach ($this->data['groups'] as $key => $group) {
                    $unique_array = (!empty($group['permissions'])) ? array_unique(array_column($group['permissions'], 'parent_status')) : [];
                    $find_parent = array_search('parent', $unique_array);
                    if ($find_parent !== FALSE) {
                        unset($unique_array[$find_parent]);
                    }
                    $this->data['groups'][$key]['permissions'] = !empty($unique_array) ? $this->permission_model->fields('id, perm_name')->where('id', $unique_array)->get_all() : [];
                }
            }
            /*  $this->data['permissions'] = $this->ion_auth_acl->permissions('full', 'perm_key', [ // 'parent_status' => 'parent'
            ]); */
            $this->data['permissions'] = $this->permission_model->with_batch('fields: id, batch_name')->where('parent_status !=', 'parent')->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->group_model->update([
                'name' => $this->input->post('name'),
                'code' => $this->input->post('prefix'),
                'priority' => $this->input->post('priority'),
                'description' => $this->input->post('desc'),
                'terms' => $this->input->post('terms'),
                'privacy' => $this->input->post('privacy')
            ], $this->input->post('id'));
            foreach ($this->input->post() as $k => $v) {
                if (substr($k, 0, 5) == 'perm_') {
                    $permission_id = str_replace("perm_", "", $k);
                    if ($v == "X")
                        $this->ion_auth_acl->remove_permission_from_group($this->input->post('id'), $permission_id);
                    else
                        $this->ion_auth_acl->add_permission_to_group($this->input->post('id'), $permission_id, $v);
                }
            }
            redirect("role/r", 'refresh');
        } elseif ($type == 'd') {
            echo $this->group_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'employee';
            $this->data['content'] = 'emp/edit';
            $this->data['type'] = 'role';
            $this->data['nav_type'] = 'role';
            $this->data['group'] = $this->group_model->order_by('id', 'DESC')
                ->with_permissions('fields: perm_key, id')
                ->where('id', $this->input->get('id'))
                ->get();
            /* $this->data['permissions'] = $this->ion_auth_acl->permissions('full', 'perm_key', [ // 'parent_status' => 'parent'
            ]); */
            $this->data['permissions'] = $this->permission_model->with_batch('fields: id, batch_name')->where('parent_status !=', 'parent')->get_all();
            $this->data['group_permissions'] = $this->ion_auth_acl->get_group_permissions($this->input->get('id'));
            $this->_render_page($this->template, $this->data);
        }
    }
public function exc_role111($type = 'r')
{
    // CREATE ROLE ONLY IF type = 'c'
    $group_id = 0;

    if ($type == 'c' && $this->input->post('name')) {

        // Insert role
        $group_id = $this->group_model->insert([
            'name'        => $this->input->post('name'),
            'code'        => $this->input->post('prefix'),
            'priority'    => $this->input->post('priority'),
            'description' => $this->input->post('desc'),
            'terms'       => $this->input->post('terms'),
            'privacy'     => $this->input->post('privacy')
        ]);
     redirect("exc_roles_list/r", 'refresh');
    }

    // ==========================
    // CREATE EXECUTIVE (type = c)
    // ==========================
if ($type == 'c') {

    // ------------------------------------
    // Build comma-separated permissions
    // ------------------------------------
    $permissionArr = [];

    foreach ($this->input->post() as $key => $value) {
        if (strpos($key, 'perm_') === 0) {

            // perm_user_view → user_view=1
            $permission = str_replace('perm_', '', $key);
            $permissionArr[] = $permission . '=' . (int)$value;
        }
    }

    // Convert array to string
    $permissions = implode(',', $permissionArr);

    // ------------------------------------
    // Insert into SAME TABLE
    // ------------------------------------
    $this->db->insert('exc_roles', [
        'vendor_type'    => $this->input->post('vendor_type'),
        'executive_name' => $this->input->post('executive_name'),
        'team_lead' => $this->input->post('team_lead'),
        'executive_id'   => $this->input->post('executive_id'),
        'amount'         => $this->input->post('amount'),
        'area_type'      => $this->input->post('area_type'),
        'city_name'      => $this->input->post('city_name'),
        'circle'         => $this->input->post('circle'),
        'ward'           => $this->input->post('ward'),
        'executive_target'  => $this->input->post('executive_target'),
        'target_freelancer' => $this->input->post('target_freelancer'),
        'monthly_target' => $this->input->post('monthly_target'),
        'team_members'   => json_encode($this->input->post('team') ?? []),
        'permissions'    => $permissions, // ✅ COMMA SEPARATED
        'created_at'     => date('Y-m-d H:i:s')
    ]);

    redirect("exc_role/r", 'refresh');
}

    // ==========================
    // READ LIST
    // ==========================
if ($type == 'r') {

    $this->data['title'] = 'Exc Roles';
    $this->data['content'] = 'emp/exc_role';
    $this->data['nav_type'] = 'exc_role';

    // 🔍 GET SEARCH INPUTS
    $name      = $this->input->get('executive_name');
    $exec_id   = $this->input->get('executive_id');
    $from_date = $this->input->get('from_date');
    $to_date   = $this->input->get('to_date');

    // 🔍 SEARCH QUERY
    $this->db->from('exc_roles');

    if (!empty($name)) {
        $this->db->like('executive_name', $name);
    }

    if (!empty($exec_id)) {
        $this->db->like('executive_id', $exec_id);
    }

    if (!empty($from_date) && !empty($to_date)) {
        $this->db->where('DATE(created_at) >=', $from_date);
        $this->db->where('DATE(created_at) <=', $to_date);
    }

    $this->db->order_by('id', 'DESC');

    $this->data['exec_list'] = $this->db->get()->result();

    // cities
    $this->data['exc_cities'] = $this->db->get('exc_cities')->result();

    $this->_render_page($this->template, $this->data);
}



    // ==========================
    // UPDATE ROLE
    // ==========================
    elseif ($type == 'u') {

        $this->group_model->update([
            'name' => $this->input->post('name'),
            'code' => $this->input->post('prefix'),
            'priority' => $this->input->post('priority'),
            'description' => $this->input->post('desc'),
            'terms' => $this->input->post('terms'),
            'privacy' => $this->input->post('privacy')
        ], $this->input->post('id'));

        foreach ($this->input->post() as $k => $v) {
            if (substr($k, 0, 5) == 'perm_') {
                $permission_id = str_replace("perm_", "", $k);
                if ($v == "X")
                    $this->ion_auth_acl->remove_permission_from_group($this->input->post('id'), $permission_id);
                else
                    $this->ion_auth_acl->add_permission_to_group($this->input->post('id'), $permission_id, $v);
            }
        }

        redirect("exc_role/r", 'refresh');
    }

    // ==========================
    // DELETE ROLE
    // ==========================
    elseif ($type == 'd') {
        echo $this->group_model->delete([
            'id' => $this->input->post('id')
        ]);
    }

    // ==========================
    // EDIT ROLE
    // ==========================
    elseif ($type == 'edit') {

        $this->data['title'] = 'Edit Exc Role';
        $this->data['content'] = 'emp/exc_role_edit';
        $this->data['type'] = 'exc_role';
        $this->data['nav_type'] = 'exc_role';

        $this->data['group'] = $this->group_model->order_by('id', 'DESC')
            ->with_permissions('fields: perm_key, id')
            ->where('id', $this->input->get('id'))
            ->get();

        $this->data['permissions'] = $this->permission_model
            ->with_batch('fields: id, batch_name')
            ->where('parent_status !=', 'parent')
            ->get_all();

        $this->data['group_permissions'] =
            $this->ion_auth_acl->get_group_permissions(
                $this->input->get('id')
            );

        $this->_render_page($this->template, $this->data);
    }
}
public function exc_role($type = 'r')
{
    // ==========================
    // CREATE ROLE (type = c)
    // ==========================
    if ($type == 'c') {

        // ------------------------------------
        // Build comma-separated permissions
        // ------------------------------------
        $permissionArr = [];
        foreach ($this->input->post() as $key => $value) {
            if (strpos($key, 'perm_') === 0) {
                $permission = str_replace('perm_', '', $key);
                $permissionArr[] = $permission . '=' . (int)$value;
            }
        }
        $permissions = implode(',', $permissionArr);

        // ------------------------------------
        // Insert into exc_roles table
        // ------------------------------------
        $this->db->insert('exc_roles', [
            'vendor_type'    => $this->input->post('vendor_type'),
            'executive_name' => $this->input->post('executive_name'),
            'team_lead' => $this->input->post('team_lead'),
            'executive_id'   => $this->input->post('executive_id'),
            'amount'         => $this->input->post('amount'),
            'area_type'      => $this->input->post('area_type'),
            'city_name'      => $this->input->post('city_name'),
            'circle'         => $this->input->post('circle'),
            'ward'           => $this->input->post('ward'),
            'executive_target'  => $this->input->post('executive_target'),
            'target_freelancer' => $this->input->post('target_freelancer'),
            'monthly_target' => $this->input->post('monthly_target'),
            'team_members'   => json_encode($this->input->post('team') ?? []),
            'permissions'    => $permissions,
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        redirect("admin/exc_role/r", 'refresh');
    }

    // ==========================
    // READ / LIST EXECUTIVES (type = r)
    // ==========================
if ($type == 'r') {

    $this->data['title']   = 'Exc Roles';
    $this->data['content'] = 'emp/exc_role';
    $this->data['nav_type']= 'exc_role';

    // 🔍 SEARCH INPUTS
    $name      = trim($this->input->get('executive_name'));
    $exec_id   = trim($this->input->get('executive_id'));
    $from_date = $this->input->get('from_date');
    $to_date   = $this->input->get('to_date');

    $this->db->select('*')->from('exc_roles');

    if ($name !== '') {
        $this->db->like('executive_name', $name);
    }

    if ($exec_id !== '') {
        $this->db->like('executive_id', $exec_id);
    }

    // 📅 DATE FILTER
    if (!empty($from_date) && !empty($to_date)) {
        $this->db->where('created_at >=', $from_date . ' 00:00:00');
        $this->db->where('created_at <=', $to_date . ' 23:59:59');
    } elseif (!empty($from_date)) {
        $this->db->where('created_at >=', $from_date . ' 00:00:00');
    } elseif (!empty($to_date)) {
        $this->db->where('created_at <=', $to_date . ' 23:59:59');
    }

    $this->db->order_by('id', 'DESC');

    $this->data['exec_list'] = $this->db->get()->result();

    $this->data['exc_cities'] = $this->db->get('exc_cities')->result();

    // keep search values for the form
    $this->data['search'] = [
        'executive_name' => $name,
        'executive_id'   => $exec_id,
        'from_date'      => $from_date,
        'to_date'        => $to_date,
    ];

    $this->_render_page($this->template, $this->data);
}

    // ==========================
    // UPDATE EXECUTIVE (type = u)
    // ==========================
    elseif ($type == 'u') {

        $id = $this->input->post('id');

        // Build permissions string
        $permissionArr = [];
        foreach ($this->input->post() as $key => $value) {
            if (strpos($key, 'perm_') === 0) {
                $permissionArr[] = str_replace('perm_', '', $key) . '=' . (int)$value;
            }
        }
        $permissions = implode(',', $permissionArr);
        $onboard_roles = $this->input->post('onboard_roles');
    $onboard_roles_str = '';

    if (!empty($onboard_roles) && is_array($onboard_roles)) {
        $onboard_roles_str = implode(',', $onboard_roles);
    }

        $this->db->where('id', $id)->update('exc_roles', [
            'vendor_type'    => $this->input->post('vendor_type'),
            'executive_name' => $this->input->post('executive_name'),
            'executive_id'   => $this->input->post('executive_id'),
            'amount'         => $this->input->post('amount'),
            'area_type'      => $this->input->post('area_type'),
            'city_name'      => $this->input->post('city_name'),
            'circle'         => $this->input->post('circle'),
            'ward'           => $this->input->post('ward'),
            'executive_target'  => $this->input->post('executive_target'),
            'target_freelancer' => $this->input->post('target_freelancer'),
            'monthly_target' => $this->input->post('monthly_target'),
            'team_members'   => json_encode($this->input->post('team') ?? []),
            'status'         => $this->input->post('status'),
            'permissions'    => $permissions,
            'role_type'    => $onboard_roles_str,
        ]);

        redirect("admin/exc_role/r", 'refresh');
    }

    // ==========================
    // DELETE EXECUTIVE (type = d)
    // ==========================
    elseif ($type == 'd') {
        $id = $this->input->post('id');
        $this->db->where('id', $id)->delete('exc_roles');
        echo json_encode(['status' => true]);
    }

    // ==========================
    // EDIT EXECUTIVE (type = edit)
    // ==========================
  if ($type == 'edit') {

    if (!$this->ion_auth->logged_in()) {
        redirect('auth/login', 'refresh');
    }

    $id = $this->input->get('id');

    $this->data['title']        = 'Edit Executive Role';
    $this->data['content']      = 'emp/exc_role';
    $this->data['nav_type']     = 'exc_role';

    $this->data['exec_edit']    = $this->db->where('id', $id)->get('exc_roles')->row();

    $this->data['exc_cities']   = $this->db->get('exc_cities')->result();
    $this->data['exec_list']    = $this->db->order_by('id', 'DESC')->get('exc_roles')->result();
    
    // Make sure permissions list available for form
    $this->data['permissions'] = $this->permission_model
        ->with_batch('fields: id, batch_name')
        ->where('parent_status !=', 'parent')
        ->get_all();

    $this->_render_page($this->template, $this->data);
}

}

public function exc_cities($type = 'r')
{
    // CREATE CITY
    if ($type == 'c') {

        $this->form_validation->set_rules('city_name', 'City Name', 'required');
        $this->form_validation->set_rules('circle', 'Circle', 'required');
        $this->form_validation->set_rules('ward', 'Ward', 'required');

        if ($this->form_validation->run() == true) {

            $data = [
                'city_name' => $this->input->post('city_name'),
                'circle'    => $this->input->post('circle'),
                'ward'      => $this->input->post('ward'),
                'status'    => 1
            ];

            $this->db->insert('exc_cities', $data);

            redirect("admin/exc_cities/r", 'refresh');
        } 
        else {
            echo validation_errors();
        }
    }

    // READ CITY LIST
    elseif ($type == 'r') {

        $this->data['title'] = 'Exc Cities';
        $this->data['content'] = 'emp/exc_cities';   // view file
        $this->data['nav_type'] = 'exc_cities';

        $this->data['cities'] = $this->db->order_by('id', 'DESC')->get('exc_cities')->result_array();

        $this->_render_page($this->template, $this->data);
    }

    // UPDATE CITY
    elseif ($type == 'u') {

        $data = [
            'city_name' => $this->input->post('city_name'),
            'circle'    => $this->input->post('circle'),
            'ward'      => $this->input->post('ward')
        ];

        $this->db->where('id', $this->input->post('id'))->update('exc_cities', $data);

        redirect("admin/exc_cities/r", 'refresh');
    }

    // DELETE CITY
    elseif ($type == 'd') {

        echo $this->db->where('id', $this->input->post('id'))->delete('exc_cities');
    }

    // EDIT CITY
    elseif ($type == 'edit') {
        


        $this->data['title'] = 'Edit City';
        $this->data['content'] = 'emp/exc_cities_edit';
        $this->data['nav_type'] = 'exc_cities';

        $this->data['city'] = $this->db->where('id', $this->input->get('id'))->get('exc_cities')->row_array();
        
       // echo $this->db->last_query(); exit;
       
       //$this->load->view('emp/exc_cities_edit',$this->data);

        $this->_render_page($this->template, $this->data);
    }

    // TOGGLE STATUS (ACTIVE/INACTIVE)
    elseif ($type == 'status') {

        $city = $this->db->where('id', $this->input->get('id'))->get('exc_cities')->row();
        $new_status = ($city->status == 1) ? 0 : 1;

        $this->db->where('id', $this->input->get('id'))->update('exc_cities', ['status' => $new_status]);

        redirect("admin/exc_cities/r", 'refresh');
    }
}

    /**
     * settings Management
     *
     * @author Mehar
     * @param string $type
     */
    public function settings($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('settings'))
         * redirect('admin');
         */
        if ($type == 'r') {
            $this->data['title'] = 'Settings';
            $this->data['content'] = 'admin/admin/settings';
            $this->data['nav_type'] = 'settings';
            $this->data['settings'] = $this->setting_model->where('id', $this->input->get('id'))
                ->get();
            $this->data['user_signature'] = $this->setting_model->get_user_signature();
            $this->data['free_delivery_settings'] = $this->cupons_model->where('id', 1)
                ->get();
            // echo "<pre>"; print_r($this->data['free_delivery_settings']);
            // exit;
            $this->data['vechile'] = $this->vehicle_model->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'site') {
            // $this->form_validation->set_rules($this->setting_model->rules['site']);
            // if ($this->form_validation->run() == FALSE) {
            //     $this->settings();
            // } 
            // else {      

            if (!empty($_FILES['digital_signature']['name'])) {

                $sigFilename = $_FILES['digital_signature']['name'];

                $upload_path = "uploads/admin/";
                $pdfFilePath = FCPATH . $upload_path . $sigFilename;

                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                $this->upload->data();

                if (file_exists($pdfFilePath)) {
                    unlink($pdfFilePath);
                }
                move_uploaded_file($_FILES['digital_signature']['tmp_name'], $pdfFilePath);
                $this->setting_model->update([
                    'key' => 'digital_signature',
                    'value' => $sigFilename
                ], 'key');

            }

            $this->setting_model->update([
                'key' => 'system_name',
                'value' => $this->input->post('system_name')
            ], 'key');
            $this->setting_model->update([
                'key' => 'system_title',
                'value' => $this->input->post('system_title')
            ], 'key');
            $this->setting_model->update([
                'key' => 'mobile',
                'value' => $this->input->post('mobile')
            ], 'key');
            $this->setting_model->update([
                'key' => 'address',
                'value' => $this->input->post('address')
            ], 'key');
            $this->setting_model->update([
                'key' => 'facebook',
                'value' => $this->input->post('facebook')
            ], 'key');
            $this->setting_model->update([
                'key' => 'twiter',
                'value' => $this->input->post('twiter')
            ], 'key');
            $this->setting_model->update([
                'key' => 'youtube',
                'value' => $this->input->post('youtube')
            ], 'key');
            $this->setting_model->update([
                'key' => 'skype',
                'value' => $this->input->post('skype')
            ], 'key');
            $this->setting_model->update([
                'key' => 'pinterest',
                'value' => $this->input->post('pinterest')
            ], 'key');
            $this->setting_model->update([
                'key' => 'lead_allocation_time',
                'value' => $this->input->post('lead_allocation_time')
            ], 'key');
            $this->setting_model->update([
                'key' => 'ecom_delivery_partner_earning_gst_percentage',
                'value' => $this->input->post('ecom_delivery_partner_earning_gst_percentage')
            ], 'key');
            redirect('settings/r', 'refresh');
            // }
        } elseif ($type == 'sms') {
            $this->form_validation->set_rules($this->setting_model->rules['sms']);
            if ($this->form_validation->run() == FALSE) {
                $this->settings();
            } else {
                $this->setting_model->update([
                    'key' => 'sms_username',
                    'value' => $this->input->post('sms_username')
                ], 'key');
                $this->setting_model->update([
                    'key' => 'sms_sender',
                    'value' => $this->input->post('sms_sender')
                ], 'key');
                $this->setting_model->update([
                    'key' => 'sms_hash',
                    'value' => $this->input->post('sms_hash')
                ], 'key');
                redirect('settings/r', 'refresh');
            }
        } elseif ($type == 'smtp') {
            $this->form_validation->set_rules($this->setting_model->rules['smtp']);
            if ($this->form_validation->run() == FALSE) {
                $this->settings();
            } else {
                $this->setting_model->update([
                    'key' => 'smtp_port',
                    'value' => $this->input->post('smtp_port')
                ], 'key');
                $this->setting_model->update([
                    'key' => 'smtp_host',
                    'value' => $this->input->post('smtp_host')
                ], 'key');
                $this->setting_model->update([
                    'key' => 'smtp_username',
                    'value' => $this->input->post('smtp_username')
                ], 'key');
                $this->setting_model->update([
                    'key' => 'smtp_password',
                    'value' => $this->input->post('smtp_password')
                ], 'key');
                redirect('settings/r', 'refresh');
            }
        } elseif ($type == 'payment') {
            $this->setting_model->update([
                'key' => 'pay_per_vendor',
                'value' => $this->input->post('pay_per_vendor')
            ], 'key');
            $this->setting_model->update([
                'key' => 'vendor_validation',
                'value' => $this->input->post('vendor_validation')
            ], 'key');
            redirect('settings/r', 'refresh');
        } elseif ($type == 'news') {
            $this->setting_model->update([
                'key' => 'pay_per_news',
                'value' => $this->input->post('pay_per_news')
            ], 'key');

            redirect('settings/r', 'refresh');
        } elseif ($type == 'cod') {
            $this->setting_model->update([
                'key' => 'max_amount',
                'value' => $this->input->post('max_amount')
            ], 'key');

            redirect('settings/r', 'refresh');
        } elseif ($type == 'maxTotalWeight') {
            $this->setting_model->update([
                'key' => 'max_order_weight',
                'value' => $this->input->post('max_order_weight')
            ], 'key');

            redirect('settings/r', 'refresh');
        } elseif ($type == 'maxTotalDistance') {
            // $this->setting_model->update([
            //     'key' => 'max_order_distance',
            //     'value' => $this->input->post('max_order_distance')
            // ], 'key');

            $this->setting_model->update([
                'key' => 'vendor_to_user_max_distance',
                'value' => $this->input->post('vendor_to_user_max_distance')
            ], 'key');
            $this->setting_model->update([
                'key' => 'vendor_to_delivery_captain_max_distance',
                'value' => $this->input->post('vendor_to_delivery_captain_max_distance')
            ], 'key');
            $this->setting_model->update([
                'key' => 'pickup_address_to_delivery_captain_max_distance',
                'value' => $this->input->post('pickup_address_to_delivery_captain_max_distance')
            ], 'key');
            $this->setting_model->update([
                'key' => 'pickup_address_to_delivery_address_max_distance',
                'value' => $this->input->post('pickup_address_to_delivery_address_max_distance')
            ], 'key');

            redirect('settings/r', 'refresh');
        } elseif ($type == 'orders') {
            $wideAreaSearch = 0;
            if ($this->input->post('wide_area_search') && $this->input->post('wide_area_search') == 'on') {
                $wideAreaSearch = 1;
            }
            $this->setting_model->update([
                'key' => 'order_cancellation_time',
                'value' => $this->input->post('order_cancellation_time')
            ], 'key');

            $this->setting_model->update([
                'key' => 'order_confirmation_time',
                'value' => $this->input->post('order_confirmation_time')
            ], 'key');

            $this->setting_model->update([
                'key' => 'customer_penalty_in_percentage',
                'value' => $this->input->post('customer_penalty_in_percentage')
            ], 'key');

            $this->setting_model->update([
                'key' => 'wide_area_search',
                'value' => $wideAreaSearch
            ], 'key');

            redirect('settings/r', 'refresh');
        } elseif ($type == 'bank') {
            $this->form_validation->set_rules($this->setting_model->rules['bank']);
            if ($this->form_validation->run() == FALSE) {
                $this->settings();
            } else {
                $this->setting_model->update([
                    'key' => 'bank_upi_id',
                    'value' => $this->input->post('bank_upi_id')
                ], 'key');
                $this->setting_model->update([
                    'key' => 'bank_name',
                    'value' => $this->input->post('bank_name')
                ], 'key');
                $this->setting_model->update([
                    'key' => 'bank_account_no',
                    'value' => $this->input->post('bank_account_no')
                ], 'key');
                $this->setting_model->update([
                    'key' => 'bank_ifsc_code',
                    'value' => $this->input->post('bank_ifsc_code')
                ], 'key');
                redirect('settings/r', 'refresh');
            }
        } elseif ($type == 'delivery_partner_security_deposit') {
            $vehicles = $this->vehicle_model->get_all();
            $argc = count($vehicles);
            for ($i = 0; $i < $argc; $i++) {
                $is_updated = $this->vehicle_model->update([
                    'id' => $vehicles[$i]['id'],
                    'name' => $vehicles[$i]['name'],
                    'min_capacity' => $vehicles[$i]['min_capacity'],
                    'max_capacity_end' => $vehicles[$i]['max_capacity_end'],
                    'desc' => $vehicles[$i]['desc'],
                    'security_deposited_amount' => $_POST['vehicle_output'][$i]
                ], 'id');
            }
            redirect('settings/r', 'refresh');
        } elseif ($type == 'free_delivery_min_amount_update') {
            $config = array(
                'upload_path' => "./uploads/free_delivery_image/",
                'allowed_types' => "jpg|png|jpeg|gif",
                'max_size' => "1024000", // file size , here it is 1 MB(1024 Kb)
            );
            $this->load->library('upload', $config);

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }
            if ($this->upload->do_upload('image')) {

                $this->cupons_model->update([
                    'id' => 1,
                    'minimum_amount' => $_POST['fd_minimum_amount'],
                    'image' => $this->upload->data('file_name')
                ], 'id');
            } else {
                $this->cupons_model->update([
                    'id' => 1,
                    'minimum_amount' => $_POST['fd_minimum_amount'],
                ], 'id');
            }
            redirect('settings/r', 'refresh');
        } elseif ($type == 'referral_amount') {
            $this->form_validation->set_rules($this->setting_model->rules['referral_amount']);
            if ($this->form_validation->run() == FALSE) {
                $this->settings();
            } else {
                $this->setting_model->update([
                    'key' => 'user_referral_amount',
                    'value' => $this->input->post('user_referral_amount')
                ], 'key');
                $this->setting_model->update([
                    'key' => 'delivery_boy_referral_amount',
                    'value' => $this->input->post('delivery_boy_referral_amount')
                ], 'key');
                $this->setting_model->update([
                    'key' => 'delivery_boy_target_order_count',
                    'value' => $this->input->post('delivery_boy_target_order_count')
                ], 'key');
                $this->setting_model->update([
                    'key' => 'vendor_referral_amount',
                    'value' => $this->input->post('vendor_referral_amount')
                ], 'key');
                $this->setting_model->update([
                    'key' => 'vendor_touser_referral_amount',
                    'value' => $this->input->post('vendor_touser_referral_amount')
                ], 'key');                
                redirect('settings/r', 'refresh');
            }
        } elseif ($type == 'cashfree') {
            $this->form_validation->set_rules('cashfree_client_id', 'Client Id', 'required|trim');
            $this->form_validation->set_rules('cashfree_client_secret', 'Client Secret', 'required|trim');
            if ($this->form_validation->run() == FALSE) {
                $this->settings();
            } else {
                $this->setting_model->update([
                    'key' => 'cashfree_client_id',
                    'value' => $this->input->post('cashfree_client_id')
                ], 'key');
                $this->setting_model->update([
                    'key' => 'cashfree_client_secret',
                    'value' => $this->input->post('cashfree_client_secret')
                ], 'key');
                redirect('settings/r', 'refresh');
            }
        } elseif ($type == 'executive_referral_video') {

            $this->form_validation->set_rules('executive_referral_video_id', 'Video ID', 'required|trim');
            if ($this->form_validation->run() == FALSE) {
                $this->settings();
            } else {
                $this->setting_model->update([
                    'key' => 'executive_referral_video_id',
                    'value' => $this->input->post('executive_referral_video_id')
                ], 'key');

                redirect('settings/r', 'refresh');
            }
        }


    }


    public function stock_settings($type = 'r', $rowno = 0)
    {

        if ($type == 'r') {
            $this->data['title'] = 'Slides';
            $this->data['content'] = 'admin/admin/stock_settings_details';
            $this->data['nav_type'] = 'stock_settings';

            $rowperpage = $noofrows ? $noofrows : 10;

            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }

            $allcount = $this->db->query("SELECT * FROM ecom_settings ")->num_rows();
            $user_id = $this->ion_auth->get_user_id();
            $this->data['stock_setting'] = $this->db->query("SELECT * FROM ecom_settings where created_user_id=" . $user_id . " LIMIT " . $rowno . ',' . $rowperpage)->result_array();

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
            $config['base_url'] = base_url() . 'admin/admin/stock_settings/r';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $rowperpage;
            $this->pagination->initialize($config);
            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['row'] = $rowno;
            $this->data['noofrows'] = $rowperpage;
            $this->_render_page($this->template, $this->data);
        } else if ($type == 'c') {

            $this->form_validation->set_rules($this->stock_settings_model->rules['create_rules']);
            if ($this->form_validation->run() == FALSE) {

                $this->data['title'] = 'Support';
                $this->data['content'] = 'admin/admin/create_stock_setting';
                $this->data['nav_type'] = 'stock_settings';
                $this->_render_page($this->template, $this->data);
            } else {

                $id = $this->stock_settings_model->insert([
                    'min_stock' => $this->input->post('min_stock'),
                    'created_user_id' => $this->ion_auth->get_user_id()

                ]);
                redirect('admin/admin/stock_settings/r/0', 'refresh');
            }
        } else if ($type == 'delete') {

            $id = base64_decode(base64_decode($this->input->get('id')));

            $this->stock_settings_model->delete([
                'id' => $id
            ]);
            redirect('admin/admin/stock_settings/r/0', 'refresh');
        } elseif ($type == 'edit') {

            $id = base64_decode(base64_decode($this->input->get('id')));

            $this->data['title'] = 'Edit request';
            $this->data['content'] = 'admin/admin/edit_stock_settings';
            $this->data['nav_type'] = 'stock_settings';
            $this->data['stock'] = $this->db->query("SELECT * FROM ecom_settings  WHERE id = '$id'")->result_array();

            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {

            $this->stock_settings_model->update([
                'id' => $this->input->post('id'),
                'min_stock' => $this->input->post('min_stock'),
                'created_user_id' => $this->ion_auth->get_user_id()
            ], 'id');
            redirect('admin/admin/stock_settings/r/0', 'refresh');
        }
    }

    /**
     * Sliders Management
     *
     * @author Mahesh
     * @param string $type
     */
    public function sliders($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('settings'))
         * redirect('admin');
         */
        if ($type == 'r') {

            $this->data['title'] = 'Slides';
            $this->data['content'] = 'admin/admin/sliders';
            $this->data['nav_type'] = 'sliders';
            $this->data['sliders'] = $this->sliders_model->get_all();
            $this->data['cat_banner'] = $this->cat_banners_model->get_all();
            $this->data['top'] = $this->advertisements_model->where('type', 'top')->get_all();
            $this->data['categories'] = $this->category_model->get_all();
            $this->data['middle'] = $this->advertisements_model->where('type', 'middle')->get_all();
            $this->data['bottom'] = $this->advertisements_model->where('type', 'bottom')->get_all();
            $this->data['last'] = $this->advertisements_model->where('type', 'last')->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'slide') {
            if ($_FILES['slide']['name'] !== '') {
                $path = $_FILES['slide']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $slider_id = $this->sliders_model->insert([
                    'image' => $path,
                    'ext' => $ext
                ]);
                $this->file_up("slide", "sliders", $slider_id, '', 'no', '.' . $ext);
            }
            redirect('sliders/r', 'refresh');
        } elseif ($type == 'd') {
            $this->sliders_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'cat_banners') {
            if ($_FILES['cat_banners']['name'] !== '') {
                $path = $_FILES['cat_banners']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $cat_id = $this->input->post('cat_id');
                $catb_id = $this->cat_banners_model->insert([
                    'image' => $path,
                    'ext' => $ext,
                    'cat_id' => $cat_id
                ]);
                $this->file_up("cat_banners", "cat_banners", $catb_id, '', 'no', '.' . $ext);
            }
            redirect('sliders/r', 'refresh');
        } elseif ($type == 'cat_bottom_banners') {
            if ($_FILES['file']['name'] !== '') {
                $path = $_FILES['file']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $cat_id = $this->input->post('cat_id');
                /*
                 * ([
                 * 'name' => $this->input->post('name'),
                 * 'desc' => $this->input->post('desc'),
                 * 'terms' => $this->input->post('terms'),
                 * ]);
                 */
                // $this->file_up("cat_bottom_banners", "cat_bottom_banners", '', 'no', '.' . $ext);
                $this->file_up("file", "cat_bottom_banners", $cat_id, '', 'no');
            }
            redirect('sliders/r', 'refresh');
        }
    }

    public function cat_ban_delete($type = 'd')
    {
        if ($type == 'd') {
            $this->cat_banners_model->delete([
                'id' => $this->input->post('id')
            ]);
        }
    }

    public function update_cat_bottom_banners()
    {
        $cat_id = $this->input->post('cat_id');
        if ($_FILES['cat_bottom_banners']['name'] !== '') {
            if (!file_exists('uploads/' . 'cat_bottom_banners' . '_image/')) {
                mkdir('uploads/' . 'cat_bottom_banners' . '_image/', 0777, true);
            }
            move_uploaded_file($_FILES['cat_bottom_banners']['tmp_name'], "./uploads/cat_bottom_banners_image/cat_bottom_banners_$cat_id.jpg");
        }
        redirect('sliders/r', 'refresh');
    }

    public function category_banner($type = 'r')
    {
        if ($type == 'r') {
            $this->data['title'] = 'Slides';
            $this->data['content'] = 'admin/admin/cat_banners';
            $this->data['nav_type'] = 'category_banner';
            $this->data['categories'] = $this->category_model->get_all();
            $this->data['sliders'] = $this->sliders_model->get_all();
            $this->data['cat_banner'] = $this->cat_banners_model->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'cat_banners') {
            if ($_FILES['cat_banners']['name'] !== '') {
                $path = $_FILES['cat_banners']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $cat_id = $this->input->post('cat_id');
                $catb_id = $this->cat_banners_model->insert([
                    'image' => $path,
                    'ext' => $ext,
                    'cat_id' => $cat_id
                ]);
                // $this->file_up("cat_banners", "cat_banners", $catb_id, '', 'no', '.jpg');
                if (!file_exists('uploads/' . 'cat_banners' . '_image/')) {
                    mkdir('uploads/' . 'cat_banners' . '_image/', 0777, true);
                }
                move_uploaded_file($_FILES['cat_banners']['tmp_name'], 'uploads/' . 'cat_banners' . '_image/' . 'cat_banners' . '_' . $cat_id . '_' . $catb_id . '.jpg');
            }
            redirect('category_banner/r', 'refresh');
        } elseif ($type == 'u') {
            if ($_FILES['file']['name'] !== '') {
                $path = $_FILES['file']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $cat_id = $this->input->post('cat_id');
                $this->cat_banners_model->update([
                    'id' => $this->input->post('banner_id'),
                    'image' => $path,
                    'ext' => $ext,
                    'cat_id' => $this->input->post('cat_id')
                ]);
                if (!file_exists('uploads/' . 'cat_banners' . '_image/')) {
                    mkdir('uploads/' . 'cat_banners' . '_image/', 0777, true);
                }
                unlink('uploads/' . 'cat_banners' . '_image/' . 'cat_banners' . '_' . $this->input->post('cat_id') . '_' . $this->input->post('banner_id') . '.jpg');
                move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'cat_banners' . '_image/' . 'cat_banners' . '_' . $this->input->post('cat_id') . '_' . $this->input->post('banner_id') . '.jpg');
                // $this->file_up("cat_banners", "cat_banners", $catb_id, '', 'no', '.jpg');
            }
            redirect('category_banner/r', 'refresh');
        } elseif ($type == 'd') {
            $this->cat_banners_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Category Banner';
            $this->data['nav_type'] = 'category_banner';
            $this->data['content'] = 'admin/admin/edit';
            $this->data['type'] = 'category_banner';
            $this->data['category'] = $this->cat_banners_model->where('id', $this->input->get('id'))
                ->get();
            $this->data['i'] = $this->cat_banners_model->where('file', $this->input->get('file'))
                ->get();
            $this->data['categories'] = $this->cat_banners_model->where('id', $this->input->get('id'))
                ->get();
            $this->_render_page($this->template, $this->data);
        }
    }

    /**
     * Advertisements Management
     *
     * @author Mahesh
     * @param string $type
     */
    public function advertisements($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('settings'))
         * redirect('admin');
         */
        if ($type == 'adver') {
            if ($_FILES['advertisement']['name'] !== '') {
                if ($_FILES['file']['name'] !== '') {
                    $path = $_FILES['file']['name'];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $this->file_up("file", "food_menu", $this->input->post('id'), '', 'no');
                }
                $path = $_FILES['advertisement']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $slider_id = $this->advertisements_model->insert([
                    'type' => $this->input->post('type'),
                    'image' => $path,
                    'ext' => $ext
                ]);
                $this->file_up("advertisement", "advertisements", $slider_id, '', 'no', '.' . $ext);
            }
            redirect('sliders/r', 'refresh');
        } elseif ($type == 'd') {
            $this->advertisements_model->delete([
                'id' => $this->input->post('id')
            ]);
        }
    }

    /**
     * Advertisements Management
     *
     * @author Mahesh
     * @param string $type
     */
    public function vendor_settings($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('settings'))
         * redirect('admin');
         */
        $this->load->model('vendor_settings_model');
        $this->load->model('vendor_list_model');
        $this->load->model('food_settings_model');
        $this->load->model('food_item_model');
        if ($type == 'r') {
            $this->data['title'] = 'Vendor Settings';
            $this->data['content'] = 'admin/admin/vendor_settings';
            $this->data['nav_type'] = 'vendor_settings';
            // $this->data['settings'] = $this->vendor_settings_model->get();
            $this->data['vendors'] = $this->vendor_list_model->fields('id,name,vendor_user_id,status')
                ->order_by('id', 'DESC')
                ->where([
                    'status' => 1
                ])
                ->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'food') {
            $this->form_validation->set_rules($this->vendor_settings_model->rules['food']);
            if ($this->form_validation->run() == FALSE) { // echo validation_errors();
                redirect('vendor_settings/r', 'refresh');
            } else {
                if ($this->input->post('vendor_id') == '' || $this->input->post('vendor_id') == 'all') {
                    $this->vendor_settings_model->update([
                        'key' => 'min_order_price',
                        'value' => $this->input->post('min_order_price')
                    ], 'key');
                    $this->vendor_settings_model->update([
                        'key' => 'delivery_free_range',
                        'value' => $this->input->post('delivery_free_range')
                    ], 'key');
                    $this->vendor_settings_model->update([
                        'key' => 'min_delivery_fee',
                        'value' => $this->input->post('min_delivery_fee')
                    ], 'key');
                    $this->vendor_settings_model->update([
                        'key' => 'ext_delivery_fee',
                        'value' => $this->input->post('ext_delivery_fee')
                    ], 'key');
                    $this->vendor_settings_model->update([
                        'key' => 'tax',
                        'value' => $this->input->post('tax')
                    ], 'key');

                    if ($this->input->post('vendor_id') == 'all') {
                        $all_v = $this->vendor_list_model->fields('vendor_user_id,status')
                            ->order_by('id', 'DESC')
                            ->where([
                                'status' => 1
                            ])
                            ->get_all();
                        foreach ($all_v as $ven) {
                            $r = $this->food_settings_model->fields('id')
                                ->where('vendor_id', $ven['vendor_user_id'])
                                ->get();
                            if ($r != '') {
                                $this->food_settings_model->update([
                                    'min_order_price' => $this->input->post('min_order_price'),
                                    'min_delivery_fee' => $this->input->post('min_delivery_fee'),
                                    'ext_delivery_fee' => $this->input->post('ext_delivery_fee'),
                                    'delivery_free_range' => $this->input->post('delivery_free_range'),
                                    'tax' => $this->input->post('tax')
                                ], [
                                    'vendor_id' => $ven['vendor_user_id']
                                ]);
                            } else {
                                $this->food_settings_model->insert([
                                    'min_order_price' => $this->input->post('min_order_price'),
                                    'delivery_free_range' => $this->input->post('delivery_free_range'),
                                    'min_delivery_fee' => $this->input->post('min_delivery_fee'),
                                    'ext_delivery_fee' => $this->input->post('ext_delivery_fee'),
                                    'label' => $this->input->post('label'),
                                    'tax' => $this->input->post('tax'),
                                    'vendor_id' => $ven['vendor_user_id']
                                ]);
                            }
                        }
                        /*
                         * $this->food_settings_model->update([
                         * 'min_order_price' => $this->input->post('min_order_price'),
                         * 'min_delivery_fee' => $this->input->post('min_delivery_fee'),
                         * 'ext_delivery_fee' => $this->input->post('ext_delivery_fee'),
                         * 'delivery_free_range' => $this->input->post('delivery_free_range'),
                         * 'tax' => $this->input->post('tax'),
                         * 'label' => $this->input->post('label')
                         * ]);
                         */
                    }
                } else {

                    $r = $this->food_settings_model->fields('id')
                        ->where('vendor_id', $this->input->post('vendor_id'))
                        ->get();
                    if ($r != '') {
                        $this->food_settings_model->update([
                            'min_order_price' => $this->input->post('min_order_price'),
                            'min_delivery_fee' => $this->input->post('min_delivery_fee'),
                            'ext_delivery_fee' => $this->input->post('ext_delivery_fee'),
                            'delivery_free_range' => $this->input->post('delivery_free_range'),
                            'label' => $this->input->post('label'),
                            'tax' => $this->input->post('tax')
                        ], [
                            'vendor_id' => $this->input->post('vendor_id')
                        ]);
                    } else {
                        $this->food_settings_model->insert([
                            'min_order_price' => $this->input->post('min_order_price'),
                            'delivery_free_range' => $this->input->post('delivery_free_range'),
                            'min_delivery_fee' => $this->input->post('min_delivery_fee'),
                            'ext_delivery_fee' => $this->input->post('ext_delivery_fee'),
                            'label' => $this->input->post('label'),
                            'tax' => $this->input->post('tax'),
                            'vendor_id' => $this->input->post('vendor_id')
                        ]);
                    }
                }
                redirect('vendor_settings/r', 'refresh');
            }
        } elseif ($type == 'food_item_label') {
            $this->form_validation->set_rules($this->vendor_settings_model->rules['food_item_label']);
            if ($this->form_validation->run() == FALSE) {
                redirect('vendor_settings/r', 'refresh');
            } else {
                $res = $this->food_item_model->update([
                    'id' => $this->input->post('item_id'),
                    'label' => $this->input->post('label')
                ], 'id');
                redirect('vendor_settings/r', 'refresh');
            }
        }
    }

    /**
     * Logo & fave Favicon
     *
     * @author Mahesh
     * @param string $type
     */
    public function site_logo($type)
    {
        if ($type == 'logo') {
            if ($_FILES['file']['name'] !== '') {
                move_uploaded_file($_FILES["file"]["tmp_name"], "assets/img/logo.png");
            }
        }
        if ($type == 'favicon') {
            if ($_FILES['file']['name'] !== '') {
                move_uploaded_file($_FILES["file"]["tmp_name"], "assets/img/favicon.png");
            }
        }
        redirect('settings/r');
    }

    /**
     * Profile Management
     *
     * @author Mehar
     * @param string $type
     */
    public function profile($type = 'r')
    {
        if ($type == 'u') {
            $this->form_validation->set_rules($this->user_model->rules['profile']);
            if ($this->form_validation->run() == FALSE) {
                $this->profile();
            } else {
                $this->user_model->update([
                    'first_name' => $this->input->post('fname'),
                    'last_name' => $this->input->post('lname'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone')
                ], $this->session->userdata('user_id'));
                redirect('profile/r', 'refresh');
            }
        } elseif ($type == 'reset') {
            $this->form_validation->set_rules($this->user_model->rules['reset']);
            if (!$this->ion_auth->logged_in()) {
                redirect('auth/login', 'refresh');
            }

            if ($this->form_validation->run() == false) {
                $this->profile();
            } else {
                $identity = $this->session->userdata('identity');
                $change = $this->ion_auth->change_password($identity, $this->input->post('opass'), $this->input->post('npass'));
                if ($change) {
                    $this->prepare_flashmessage($this->ion_auth->messages(), 2);
                    redirect('auth/logout', 'refresh');
                } else {
                    $this->prepare_flashmessage($this->ion_auth->errors(), 1);
                    redirect('profile/r', 'refresh');
                }
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Profile';
            $this->data['content'] = 'admin/admin/profile';
            $this->data['nav_type'] = 'dashboard';
            $this->data['user'] = $this->ion_auth->user()->row();
            $this->_render_page($this->template, $this->data);
        }
    }

    public function emp_list($type = 'executive')
    {
        if ($type == 'executive') {
            if (isset($_GET['exe_id'])) {
                $this->data['title'] = 'Vendors List';
                $this->data['content'] = 'emp/emp_vendors';
                $this->data['nav_type'] = 'executive';
                $this->data['categories'] = $this->category_model->get_all();
                $this->data['vendors'] = $this->vendor_list_model->order_by('id', 'DESC')
                    ->with_location('fields:id, address')
                    ->where('executive_user_id', $_GET['exe_id'])
                    ->get_all();
                // $column = count($this->data['vendors']);
                $a = $d = 1;
                if (!empty($this->data['vendors'])) {
                    foreach ($this->data['vendors'] as $vendors) {
                        if ($vendors['status'] == 1) {
                            $this->data['approved_count'] = $a++;
                        } else {
                            $this->data['disapproved_count'] = $d++;
                        }
                    }
                }
                $this->_render_page($this->template, $this->data);
            } elseif (isset($_GET['eye_id'])) {
                $this->data['title'] = 'Executive Details';
                $this->data['content'] = 'emp/executive_eye';
                $this->data['nav_type'] = 'executive';
                $this->data['type'] = 'executive';
                $this->data['users'] = $this->user_model->order_by('id', 'DESC')
                    ->fields('id,first_name, last_name, email,phone,permanent_address, aadhar_number, unique_id, status')
                    ->with_location("fields:id,address")
                    ->with_executive_biometric()
                    ->with_executive_address()
                    ->with_groups('fields: id, name', 'where: name = \'executive\'')
                    ->where('id', $_GET['eye_id'])
                    ->get();
                $this->data['tc'] = $this->termsconditions_model->users_tc($this->input->get('id'));
                   $this->data['edit'] = $edit;
                   
            $this->data['bank_details'] = $this->vendor_bank_details_model->fields('id,bank_name,bank_branch,ifsc,ac_holder_name,ac_number')
            ->where('list_id', $_GET['eye_id'])
            ->get();
            $this->data['exc_cities'] = $this->db->get('exc_cities')->result();

            // Fetch executive role & team members
                $role_data = $this->db->where('user_id', $_GET['eye_id'])->get('exc_roles')->row();
                $this->data['edit'] = $role_data; // for pre-filling form
                // print_r($this->data['edit']);
                // die()
                $this->data['team_members'] = $role_data ? json_decode($role_data->team_members ?? '[]', true) : [];
                            $this->_render_page($this->template, $this->data);
            } else {
                $this->data['title'] = 'Executives';
                $this->data['content'] = 'emp/emp_list';
                $this->data['nav_type'] = 'executive';
                $this->data['type'] = 'executive';
                $this->data['executives'] = $this->user_model->order_by('id', 'DESC')
                    ->fields('id, first_name, last_name, email, unique_id, created_at, status')
                    ->with_groups('fields: id, name', 'where: name = \'executive\'')
                    ->get_all();
                foreach ($this->data['executives'] as $key => $val) {
                    $this->data['executives'][$key]['vendors'] = $this->vendor_list_model->fields('id, name, email, unique_id, category_id, executive_id, status')
                        ->where([
                            'executive_user_id' => $this->data['executives'][$key]['id']
                        ])
                        ->get_all();
                }
                $this->_render_page($this->template, $this->data);
            }
        }
    }
public function emp_list22($type = 'executive')
{
    if ($type == 'executive') {

        // =========================
        // Vendors List for an Executive
        // =========================
        if (isset($_GET['exe_id'])) {
            $this->data['title'] = 'Vendors List';
            $this->data['content'] = 'emp/emp_vendors';
            $this->data['nav_type'] = 'executive';
            $this->data['categories'] = $this->category_model->get_all();
            $this->data['vendors'] = $this->vendor_list_model->order_by('id', 'DESC')
                ->with_location('fields:id, address')
                ->where('executive_user_id', $_GET['exe_id'])
                ->get_all();

            $a = $d = 1;
            if (!empty($this->data['vendors'])) {
                foreach ($this->data['vendors'] as $vendors) {
                    if ($vendors['status'] == 1) {
                        $this->data['approved_count'] = $a++;
                    } else {
                        $this->data['disapproved_count'] = $d++;
                    }
                }
            }

            $this->_render_page($this->template, $this->data);

        // =========================
        // Executive Details (Edit View)
        // =========================
        } elseif (isset($_GET['eye_id'])) {

            $user_id = $_GET['eye_id'];

            $this->data['title'] = 'Executive Details';
            $this->data['content'] = 'emp/executive_eye';
            $this->data['nav_type'] = 'executive';
            $this->data['type'] = 'executive';

            // Fetch executive with all relations
            $edit = $this->user_model->order_by('id', 'DESC')
                ->fields('id, first_name, last_name, email, phone, permanent_address, aadhar_number, unique_id, status')
                ->with_location('fields:id,address')
                ->with_executive_biometric()
                ->with_executive_address()
                ->with_bank_details() // make sure this relation exists
                ->with_groups('fields: id, name', 'where: name = \'executive\'')
                ->where('id', $user_id)
                ->get();

            if (!$edit) {
                show_error('Executive not found.');
            }

            $this->data['edit'] = $edit;

            // Fetch team members from exc_roles table
            $role_data = $this->db->where('user_id', $user_id)->get('exc_roles')->row();
            $this->data['team_members'] = $role_data ? json_decode($role_data->team_members ?? '[]', true) : [];

            // Fetch roles/permissions
            $this->data['permissions'] = $this->permission_model
                ->with_batch('fields: id, batch_name')
                ->where('parent_status !=', 'parent')
                ->get_all();

            // Terms & Conditions
            $this->data['tc'] = $this->termsconditions_model->users_tc($user_id);

            $this->_render_page($this->template, $this->data);

        // =========================
        // All Executives List
        // =========================
        } else {
            $this->data['title'] = 'Executives';
            $this->data['content'] = 'emp/emp_list';
            $this->data['nav_type'] = 'executive';
            $this->data['type'] = 'executive';

            $this->data['executives'] = $this->user_model->order_by('id', 'DESC')
                ->fields('id, first_name, last_name, email, unique_id, created_at, status')
                ->with_groups('fields: id, name', 'where: name = \'executive\'')
                ->get_all();

            foreach ($this->data['executives'] as $key => $val) {
                $this->data['executives'][$key]['vendors'] = $this->vendor_list_model->fields('id, name, email, unique_id, category_id, executive_id, status')
                    ->where(['executive_user_id' => $val['id']])
                    ->get_all();
            }

            $this->_render_page($this->template, $this->data);
        }
    }
}

    public function executivestatus($type = 'change_status')
    {
        if ($type == 'change_status') {
            $this->user_model->update([
                'status' => ($this->input->post('is_checked') == 'true') ? 1 : 0
            ], $this->input->post('id'));
        }

        if ($_POST['is_checked'] == 'true') {
            // $this->send_sms('\'Congratulations! , now you are a member to the Nextclick Family . Regards, NEXTCLICK.\'', $mobile);
            $this->user_group_model->approveGroup($this->input->post('id'), 'executive');
        } else {
            $this->user_group_model->disApproveGroup($this->input->post('id'), 'executive');
        }
        echo true;
    }

    public function manage()
    {
        $this->load->view('manage');
    }

    public function permissions()
    {
        $data['permissions'] = $this->ion_auth_acl->permissions('full');

        $this->load->view('permissions', $data);
    }

    public function add_permission()
    {
        if ($this->input->post() && $this->input->post('cancel'))
            redirect('admin/permissions', 'refresh');

        $this->form_validation->set_rules('perm_key', 'key', 'required|trim');
        $this->form_validation->set_rules('perm_name', 'name', 'required|trim');
        $this->form_validation->set_rules('desc', 'Description', 'trim');
        $this->form_validation->set_rules('parent_status', 'Parent Status', 'trim');
        $this->form_validation->set_message('required', 'Please enter a %s');

        if ($this->form_validation->run() === FALSE) {
            $data['message'] = ($this->ion_auth_acl->errors() ? $this->ion_auth_acl->errors() : $this->session->flashdata('message'));
            $data['permissions'] = $this->permission_model->where('parent_status', 'parent')->get_all();

            $this->load->view('add_permission', $data);
        } else {
            $parent_status = $this->input->post('parent_status');
            if ($this->input->post('parent_status') == null) {
                $parent_status = 'parent';
            }
            $new_permission_id = $this->ion_auth_acl->create_permission($this->input->post('perm_key'), $this->input->post('perm_name'), $parent_status, $this->input->post('desc'));
            if ($new_permission_id) {
                // check to see if we are creating the permission
                // redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("admin/permissions", 'refresh');
            }
        }
    }

    public function update_permission()
    {
        if ($this->input->post() && $this->input->post('cancel'))
            redirect('admin/permissions', 'refresh');

        $permission_id = $this->uri->segment(3);

        if (!$permission_id) {
            $this->session->set_flashdata('message', "No permission ID passed");
            redirect("admin/permissions", 'refresh');
        }

        $permission = $this->ion_auth_acl->permission($permission_id);

        $this->form_validation->set_rules('perm_key', 'key', 'required|trim');
        $this->form_validation->set_rules('perm_name', 'name', 'required|trim');

        $this->form_validation->set_message('required', 'Please enter a %s');

        if ($this->form_validation->run() === FALSE) {
            $data['message'] = ($this->ion_auth_acl->errors() ? $this->ion_auth_acl->errors() : $this->session->flashdata('message'));
            $data['permission'] = $permission;

            $this->load->view('edit_permission', $data);
        } else {
            $additional_data = array(
                'perm_name' => $this->input->post('perm_name')
            );

            $update_permission = $this->ion_auth_acl->update_permission($permission_id, $this->input->post('perm_key'), $additional_data);
            if ($update_permission) {
                // check to see if we are creating the permission
                // redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("admin/permissions", 'refresh');
            }
        }
    }

    public function delete_permission()
    {
        if ($this->input->post() && $this->input->post('cancel'))
            redirect('admin/permissions', 'refresh');

        $permission_id = $this->uri->segment(3);

        if (!$permission_id) {
            $this->session->set_flashdata('message', "No permission ID passed");
            redirect("admin/permissions", 'refresh');
        }

        if ($this->input->post() && $this->input->post('delete')) {
            if ($this->ion_auth_acl->remove_permission($permission_id)) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("admin/permissions", 'refresh');
            } else {
                echo $this->ion_auth_acl->messages();
            }
        } else {
            $data['message'] = ($this->ion_auth_acl->errors() ? $this->ion_auth_acl->errors() : $this->session->flashdata('message'));

            $this->load->view('delete_permission', $data);
        }
    }

    public function groups()
    {
        $data['groups'] = $this->ion_auth->groups()->result();

        $this->load->view('groups', $data);
    }

    public function group_permissions()
    {
        if ($this->input->post() && $this->input->post('cancel'))
            redirect('admin/groups', 'refresh');

        $group_id = $this->uri->segment(3);

        if (!$group_id) {
            $this->session->set_flashdata('message', "No group ID passed");
            redirect("admin/groups", 'refresh');
        }

        if ($this->input->post() && $this->input->post('save')) {
            foreach ($this->input->post() as $k => $v) {
                if (substr($k, 0, 5) == 'perm_') {
                    $permission_id = str_replace("perm_", "", $k);

                    if ($v == "X")
                        $this->ion_auth_acl->remove_permission_from_group($group_id, $permission_id);
                    else
                        $this->ion_auth_acl->add_permission_to_group($group_id, $permission_id, $v);
                }
            }

            redirect('admin/groups', 'refresh');
        }

        $data['permissions'] = $this->ion_auth_acl->permissions('full', 'perm_key');
        $data['group_permissions'] = $this->ion_auth_acl->get_group_permissions($group_id);

        $this->load->view('group_permissions', $data);
    }

    public function users()
    {
        $data['users'] = $this->ion_auth->users()->result();

        $this->load->view('users', $data);
    }

    public function manage_user()
    {
        $user_id = $this->uri->segment(3);

        if (!$user_id) {
            $this->session->set_flashdata('message', "No user ID passed");
            redirect("admin/users", 'refresh');
        }

        $data['user'] = $this->ion_auth->user($user_id)->row();
        $data['user_groups'] = $this->ion_auth->get_users_groups($user_id)->result();
        $data['user_acl'] = $this->ion_auth_acl->build_acl($user_id);

        $this->load->view('manage_user', $data);
    }

    public function user_permissions()
    {
        $user_id = $this->uri->segment(3);

        if (!$user_id) {
            $this->session->set_flashdata('message', "No user ID passed");
            redirect("admin/users", 'refresh');
        }

        if ($this->input->post() && $this->input->post('cancel'))
            redirect("admin/manage-user/{$user_id}", 'refresh');

        if ($this->input->post() && $this->input->post('save')) {
            foreach ($this->input->post() as $k => $v) {
                if (substr($k, 0, 5) == 'perm_') {
                    $permission_id = str_replace("perm_", "", $k);

                    if ($v == "X")
                        $this->ion_auth_acl->remove_permission_from_user($user_id, $permission_id);
                    else
                        $this->ion_auth_acl->add_permission_to_user($user_id, $permission_id, $v);
                }
            }

            redirect("admin/manage-user/{$user_id}", 'refresh');
        }

        $user_groups = $this->ion_auth_acl->get_user_groups($user_id);

        $data['user_id'] = $user_id;
        $data['permissions'] = $this->ion_auth_acl->permissions('full', 'perm_key');
        $data['group_permissions'] = $this->ion_auth_acl->get_group_permissions($user_groups);
        $data['users_permissions'] = $this->ion_auth_acl->build_acl($user_id);

        $this->load->view('user_permissions', $data);
    }

    /**
     * user_services crud
     *
     * @author Trupti
     * @param string $type
     * @param string $target
     */
    public function user_services($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('state'))
         * redirect('admin');
         */
        if ($type == 'c') {
            $this->form_validation->set_rules($this->user_service_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->state('r');
            } else {
                $id = $this->user_service_model->insert([
                    'name' => $this->input->post('name')
                ]);
                redirect('user_services/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Services';
            $this->data['content'] = 'admin/admin/services';
            $this->data['services'] = $this->user_service_model->order_by('id', 'DESC')->get_all();
            $this->_render_page($this->template, $this->data);
            // echo json_eencode($this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->user_service_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $this->user_service_model->update([
                    'id' => $this->input->post('id'),
                    'name' => $this->input->post('name')
                ], 'id', 'name');
                redirect('user_services/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->user_service_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit State';
            $this->data['content'] = 'admin/admin/edit';
            $this->data['type'] = 'user_services';
            $this->data['services'] = $this->user_service_model->order_by('id', 'DESC')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->_render_page($this->template, $this->data);
        }
    }

    function popup($page_name = '', $param2 = '', $param3 = '')
    {
        $account_type = $this->session->userdata('login_type');
        $page_data['param2'] = $param2;
        $page_data['param3'] = $param3;
        $this->load->view('backend/main/' . $page_name . '.php', $page_data);

        echo '<script src="assets/js/neon-custom-ajax.js"></script>';
        echo '<script>$(".html5editor").wysihtml5();</script>';
    }

    public function my_test($value = '')
    {
        echo "string";
    }

    /**
     * FAQ crud
     *
     * @author Trupti
     * @param string $type
     * @param string $target
     */
    public function faq($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('faq'))
         * redirect('admin');
         */
        if ($type == 'c') {
            $this->form_validation->set_rules($this->faq_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'FAQ Add';
                $this->data['content'] = 'admin/admin/add_faq';
                $this->data['nav_type'] = 'faq';
                $this->data['app_details'] = $this->app_details_model->get_all();

                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->faq_model->insert([
                    'app_id' => $this->input->post('app_id'),
                    'question' => $this->input->post('question'),
                    'answer' => $this->input->post('answer')
                ]);

                redirect('faq/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'faq';
            $this->data['content'] = 'admin/admin/faq';
            $this->data['nav_type'] = 'faq';
            $this->data['faq'] = $this->faq_model->order_by('id', 'DESC')->get_all();
            $this->data['app_details'] = $this->app_details_model->get_all();
            $this->_render_page($this->template, $this->data);
            // echo json_encode($this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->faq_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $this->faq_model->update([
                    'id' => $this->input->post('id'),
                    'app_id' => $this->input->post('app_id'),
                    'question' => $this->input->post('question'),
                    'answer' => $this->input->post('answer')
                ], 'id');
                redirect('faq/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->faq_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit faq';
            $this->data['content'] = 'admin/admin/edit';
            $this->data['nav_type'] = 'faq';
            $this->data['type'] = 'faq';
            $this->data['faq'] = $this->faq_model->order_by('id', 'DESC')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->data['app_details'] = $this->app_details_model->get_all();

            $this->_render_page($this->template, $this->data);
        }
    }

    /**
     * Terms&Conditions crud
     *
     * @author Tejaswini
     * @param string $type
     * @param string $target
     */
    public function termsconditions($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('termsconditions'))
         * redirect('admin');
         */
        if ($type == 'c') {
            $this->form_validation->set_rules($this->termsconditions_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Terms&Conditions Add';
                $this->data['content'] = 'admin/admin/add_termsconditions';
                $this->data['nav_type'] = 'termsconditions';
                $this->data['app_details'] = $this->app_details_model->get_all();

                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->termsconditions_model->insert([
                    'app_details_id' => $this->input->post('app_id'),
                    'page_id' => $this->input->post('page_id'),
                    'title' => $this->input->post('title'),
                    'desc' => $this->input->post('desc'),
                    'created_user_id' => $this->ion_auth->get_user_id()
                ]);

                redirect('termsconditions/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'T & C';
            $this->data['content'] = 'admin/admin/termsconditions';
            $this->data['nav_type'] = 'termsconditions';
            $this->data['termsconditions'] = $this->termsconditions_model->get_all();
            $this->data['app_details'] = $this->app_details_model->get_all();
            $this->_render_page($this->template, $this->data);
            // echo json_encode($this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->termsconditions_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $this->termsconditions_model->update([
                    'id' => $this->input->post('id'),
                    'app_details_id' => $this->input->post('app_id'),
                    'page_id' => $this->input->post('page_id'),
                    'title' => $this->input->post('title'),
                    'desc' => $this->input->post('desc'),
                    'updated_user_id' => $this->ion_auth->get_user_id()
                ], 'id');
                redirect('termsconditions/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->termsconditions_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit TC';
            $this->data['content'] = 'admin/admin/edit';
            $this->data['nav_type'] = 'termsconditions';
            $this->data['type'] = 'termsconditions';
            $this->data['termsconditions'] = $this->termsconditions_model->order_by('id', 'DESC')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->data['app_details'] = $this->app_details_model->get_all();

            $this->_render_page($this->template, $this->data);
        }
    }


    /**
     * FAQ crud
     *
     * @author Trupti
     * @param string $type
     * @param string $target
     */
    public function vendor_faq($type = 'r')
    {
        /*
         * if (! $this->ion_auth_acl->has_permission('faq'))
         * redirect('admin');
         */
        if ($type == 'c') {
            $this->form_validation->set_rules($this->faq_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'FAQ Add';
                $this->data['content'] = 'admin/admin/add_faq';
                $this->data['nav_type'] = 'faq';
                $this->data['app_details'] = $this->app_details_model->get_all();

                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->faq_model->insert([
                    'app_id' => $this->input->post('app_id'),
                    'question' => $this->input->post('question'),
                    'answer' => $this->input->post('answer')
                ]);

                redirect('faq/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'faq';
            $this->data['content'] = 'admin/admin/vendor_faq';
            $this->data['nav_type'] = 'faq';
            $this->data['faq'] = $this->faq_model->get_all();
            // $this->data['faq'] = $this->faq_model->order_by('id', 'DESC')->where('id',$this->input->get('id'))->get();
            $this->_render_page($this->template, $this->data);
            // echo json_encode($this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->faq_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $this->faq_model->update([
                    'id' => $this->input->post('id'),
                    'app_id' => $this->input->post('app_id'),
                    'question' => $this->input->post('question'),
                    'answer' => $this->input->post('answer')
                ], 'id');
                redirect('faq/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->faq_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit faq';
            $this->data['nav_type'] = 'faq';
            $this->data['content'] = 'admin/admin/edit';
            $this->data['type'] = 'faq';
            $this->data['faq'] = $this->faq_model->order_by('id', 'DESC')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->data['app_details'] = $this->app_details_model->get_all();

            $this->_render_page($this->template, $this->data);
        }
    }

    public function terms($type = 'r')
    {
        $this->data['title'] = 'Terms And Conditions';
        $this->data['content'] = 'admin/admin/terms_and_conditions';
        $this->data['nav_type'] = 'terms';
        $this->data['categories'] = $this->category_model->get_all();
        $vendor_id = $this->ion_auth->get_user_id();
        $this->data['vendor'] = $this->vendor_list_model->with_category('fields:id, terms')
            ->where('vendor_user_id', $vendor_id)
            ->get_all();
        // print_array($this->data['vendor']);
        $this->_render_page($this->template, $this->data);
        // echo json_encode($this->data);
    }

    public function delivery_partner($type = 'r', $rowno = 0)
    {
        if ($type == 'c') {
            $this->data['partner'] = $this->user_model->where('id', $this->input->post('id'))->get();
            $unique_id = $this->data['partner']['unique_id'];
            $rt = $this->user_model->update([
                "first_name" => $this->input->post('first_name'),
                "last_name" => $this->input->post('last_name'),
                "email" => $this->input->post('email'),
                "location_id" => $this->input->post('location'),
                "unique_id" => $this->data['partner']['unique_id'],
                'phone' => $this->input->post('phone'),
                'password' => $this->data['partner']['password'],
                "vehicle_number" => $this->input->post('vehicle_number'),
                "aadhar_number" => $this->input->post('adhar_card_number'),
                "pan_card_number" => $this->input->post('pan_card_number'),
                "driving_license_number" => $this->input->post('driving_license_number'),
                "vehicle_number" => $this->input->post('vehicle_number'),
                'active' => 1
            ], $this->input->post('id'));

            if ($_FILES['adhar_image']['name'] != '') {
                unlink('assets/aadhar_card_image/' . "aadhar_card_" . $unique_id . ".jpg");
                $uploadFileDir = './assets/aadhar_card_image/';
                $dest_path = $uploadFileDir;
                $dest_path = $uploadFileDir . "aadhar_card_" . $unique_id . ".jpg";
                move_uploaded_file($_FILES['adhar_image']['tmp_name'], $dest_path);
            }

            if ($_FILES['pan_image']['name'] != '') {
                unlink('assets/pan_card_image/' . "pan_card" . $unique_id . ".jpg");
                $uploadFileDir = './assets/pan_card_image/';
                $dest_path = $uploadFileDir;
                $dest_path = $uploadFileDir . "pan_card" . $unique_id . ".jpg";
                move_uploaded_file($_FILES['pan_image']['tmp_name'], $dest_path);
            }

            if ($_FILES['bank_passbook']['name'] != '') {
                unlink('assets/passbook_image/' . "pass_book" . $unique_id . ".jpg");
                $uploadFileDir = './assets/passbook_image/';
                $dest_path = $uploadFileDir;
                $dest_path = $uploadFileDir . "pass_book" . $unique_id . ".jpg";
                move_uploaded_file($_FILES['bank_passbook']['tmp_name'], $dest_path);
            }

            if ($_FILES['cancelcheck_image']['name'] != '') {
                unlink('assets/cancel_cheque_image/' . "cancel_cheque" . $unique_id . ".jpg");

                $uploadFileDir = './assets/cancel_cheque_image/';
                $dest_path = $uploadFileDir;
                $dest_path = $uploadFileDir . "cancel_cheque" . $unique_id . ".jpg";
                move_uploaded_file($_FILES['cancelcheck_image']['tmp_name'], $dest_path);
            }

            if ($_FILES['rc_doc']['name'] != '') {
                unlink('assets/rc_image/' . "rc_doc" . $unique_id . ".jpg");
                $uploadFileDir = './assets/rc_image/';
                $dest_path = $uploadFileDir;
                $dest_path = $uploadFileDir . "rc_doc" . $unique_id . ".jpg";
                move_uploaded_file($_FILES['rc_doc']['tmp_name'], $dest_path);
            }
            if ($_FILES['dirving_licence_image']['name'] != '') {
                unlink('assets/dirving_licence_image/' . "dirving_licence" . $unique_id . ".jpg");
                $uploadFileDir = './assets/dirving_licence_image/';
                $dest_path = $uploadFileDir;
                $dest_path = $uploadFileDir . "dirving_licence" . $unique_id . ".jpg";
                move_uploaded_file($_FILES['dirving_licence_image']['tmp_name'], $dest_path);
            }

            if ($_FILES['profile_image']['name'] != '') {
                unlink('assets/profile_image/' . "profile" . $unique_id . ".jpg");
                $uploadFileDir = './assets/profile_image/';
                $dest_path = $uploadFileDir;
                $dest_path = $uploadFileDir . "profile" . $unique_id . ".jpg";
                move_uploaded_file($_FILES['profile_image']['tmp_name'], $dest_path);
            }

            redirect("delivery_partner/r/0", 'refresh');
        } elseif ($type == 'r') {
            if (isset($_POST['submit'])) {
                $search_text = $this->input->post('q');
                $unique_id = $this->input->post('unique_id');
                //$group = $this->input->post('group');
                $noofrows = $this->input->post('noofrows');
                $this->session->set_userdata(
                    array(
                        "q" => $search_text,
                        'unique_id' => $unique_id,
                        'group' => $group,
                        'noofrows' => $noofrows
                    )
                );
            } else {
                if ($this->session->userdata('q') != NULL || $this->session->userdata('unique_id') != NULL || $this->session->userdata('group') != NULL || $this->session->userdata('noofrows') != NULL) {
                    $search_text = $this->session->userdata('q');
                    // $unique_id = $this->session->userdata('unique_id');
                    $group = $this->session->userdata('group');
                    $noofrows = $this->session->userdata('noofrows');
                }
            }
            $this->data['title'] = 'Delivery Partner';
            $this->data['content'] = 'admin/delivery_partner_details';
            $this->data['nav_type'] = 'delivery_partners';
            // $search_text = $unique_id = "";
            $group = $this->config->item('delivery_partner_group_id', 'ion_auth');
            $noofrows = 3;
            $rowperpage = 10;
            $rowno = ($this->uri->segment(3)) ? ($this->uri->segment(3) - 1) : 0;
            $rowno = ($rowno) * $rowperpage;
            $group = 7;
            $allcount = $this->user_model->users_count($group, $search_text, $unique_id);
            $users_record = $this->user_model->get_users($rowperpage, $rowno, $group, $search_text, $unique_id);
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

            $config['base_url'] = base_url() . 'delivery_partner/r';
            $config['first_url'] = base_url() . 'delivery_partner/r/0';

            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $rowperpage;

            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['unique_id'] = $unique_id;
            $this->data['group'] = $group;
            $this->data['noofrows'] = $rowperpage;

            $this->pagination->initialize($config);
            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['users'] = $users_record;

            foreach ($this->data['users'] as $key => $user) {
                $this->db->select("b.name , b.description , a.id");
                $this->db->from("users_groups as a");
                $this->db->join("groups as b", "a.group_id = b.id");
                $this->db->where("a.user_id", $user['id']);
                $result = $this->db->get();
                $this->data['users'][$key]['groups'] = $result->result_array();
            }
            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['unique_id'] = $unique_id;
            $this->data['group'] = $group;
            $this->data['noofrows'] = $rowperpage;
            $this->data['groups'] = $this->group_model->order_by('id', 'DESC')->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'eye') {

            $this->data['nav_type'] = 'delivery_partners';
            $this->data['content'] = 'admin/edit_delivery_partner';
            $this->data['partner'] = $this->user_model
                ->with_delivery_boy_biometrics()
                ->where('id', $this->input->get('id'))
                ->get();
            $this->data['doc'] = $this->user_doc_model->where('created_user_id', $this->input->get('id'))->get();
            $this->data['current_location'] = $this->delivery_partner_location_tracking_model->where('delivery_partner_user_id', $this->data['partner']['id'])->get();
            // print_r($this->data['current_location']);
            // die();
            $this->data['location'] = $this->delivery_boy_address_model->where('user_id', $this->data['partner']['id'])->get();
            $this->data['constituency'] = $this->constituency_model->order_by('id', 'DESC')
                ->where('id', $this->data['location']['constituency'])
                ->get();
            $this->data['bank_details'] = $this->delivery_boy_bank_details_model->where([
                'user_id' => $this->input->get('id'),
                'status' => 1
            ])
                ->get();
            $this->data['security_deposite'] = $this->vehicle_model->get_all()[0]['security_deposited_amount'];
            $this->data['security_deposite_payment'] = $this->delivery_boy_payment_model->where('created_user_id', $this->input->get('id'))->get();

            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'edit') {
            $this->data['nav_type'] = 'delivery_partners';
            $this->data['content'] = 'admin/add_delivery_partner';
            $this->data['partner'] = $this->user_model->where('id', $this->input->get('id'))->get();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'bank_details') {
            $this->form_validation->set_rules($this->delivery_boy_bank_details_model->rules);
            if ($this->form_validation->run() == FALSE) {
                redirect('admin/delivery_partner/eye?id=' . $this->input->post('user_id'));
            } else {
                $userID = $this->input->post('user_id');
                $r = $this->delivery_boy_bank_details_model->fields('id, ac_number')
                    ->where([
                        'user_id' => $this->input->post('user_id'),
                        'status' => 1
                    ])
                    ->get();
                if (!empty($r)) {
                    if ($r['ac_number'] != $this->input->post('ac_number')) {
                        $this->delivery_boy_bank_details_model->update([
                            'user_id' => $this->input->post('user_id'),
                            'status' => 2
                        ], 'user_id');
                        $this->delivery_boy_bank_details_model->insert([
                            'bank_name' => $this->input->post('bank_name'),
                            'bank_branch' => $this->input->post('bank_branch'),
                            'ifsc' => $this->input->post('ifsc'),
                            'ac_holder_name' => $this->input->post('ac_holder_name'),
                            'ac_number' => $this->input->post('ac_number'),
                            'user_id' => $this->input->post('user_id')
                        ]);
                        $this->user_account_model->checkandUpdateAccount($userID, null, "delivery_boy");
                    } else {
                        $this->delivery_boy_bank_details_model->update([
                            'user_id' => $this->input->post('user_id'),
                            'bank_name' => $this->input->post('bank_name'),
                            'bank_branch' => $this->input->post('bank_branch'),
                            'ifsc' => $this->input->post('ifsc'),
                            'ac_holder_name' => $this->input->post('ac_holder_name'),
                            'ac_number' => $this->input->post('ac_number')
                        ], 'user_id');
                    }
                    redirect('admin/delivery_partner/eye?id=' . $this->input->post('user_id'));
                } else {
                    $this->delivery_boy_bank_details_model->insert([
                        'bank_name' => $this->input->post('bank_name'),
                        'bank_branch' => $this->input->post('bank_branch'),
                        'ifsc' => $this->input->post('ifsc'),
                        'ac_holder_name' => $this->input->post('ac_holder_name'),
                        'ac_number' => $this->input->post('ac_number'),
                        'user_id' => $this->input->post('user_id')
                    ]);
                    $this->user_account_model->checkandUpdateAccount($userID, null, "delivery_boy");
                    redirect('admin/delivery_partner/eye?id=' . $this->input->post('user_id'));
                }
            }
        } else if ($type == 'payouts') {
            $search_text = "";
            $noofrows = 10;
            if ($this->input->post('submit') != NULL) {
                $search_text = $this->input->post('q');
                $noofrows = $this->input->post('noofrows');
                $this->session->set_userdata(array("q" => $search_text, 'noofrows' => $noofrows));
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
            $this->data['title'] = 'Delivery Partner Payment Distrubution';
            $this->data['content'] = 'admin/admin/delivery_payment_distribution';
            $this->data['nav_type'] = 'delivery_partner_payout';
            $this->data['delivery_partner_payouts'] = $this->user_account_model->prepareDeliveryPartnerPayouts_data($rowperpage, $rowno, $search_text);
            $this->data['total_payout'] = $this->user_account_model->fetchDeliveryBoyTotalPayouts($search_text);
            // echo "<pre>";
            // print_r($this->data); exit;
            $allcount = $this->user_account_model->fetcTotaldeliveryPayouts_count($search_text);

            $users_record = $this->user_account_model->prepareDeliveryPartnerPayouts_data($rowperpage, $rowno, $search_text);


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
            $config['base_url'] = base_url() . 'admin/delivery_partner/payouts';
            $config['first_url'] = base_url() . 'admin/delivery_partner/payouts/0';

            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $rowperpage;
            $this->pagination->initialize($config);

            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['vendor_payout'] = $users_record;
            $arr = array_column($users_record, 'id');

            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['noofrows'] = $rowperpage;
            $this->_render_page($this->template, $this->data);
        } else if ($type == 'process_payouts') {
            $payouts = $this->user_account_model->prepareDeliveryPartnerPayouts();
            foreach ($payouts as $payout) {
                if ((float) $payout['wallet'] > 0 && $payout['external_id']) {
                    $settlementAmount = 100; //((float) $payout['wallet'])*100;
                    $settlementAmountInBucks = $settlementAmount / 100;
                    $txn_id = 'DBT-' . generate_trasaction_no(10);
                    $this->user_model->payment_update($payout['id'], $settlementAmountInBucks, 'DEBIT', 'wallet', $txn_id, null, "Bank Payout");
                    $externalPayout = $this->payThroughRazorpay($settlementAmount, $payout['external_id']);
                    $this->payout_model->insert([
                        'user_id' => $payout['id'],
                        'user_type' => 1,
                        'delivery_partner_bank_id' => $payout['delivery_boy_bank_id'],
                        'external_id' => $externalPayout->id,
                        'payment_value' => $settlementAmountInBucks,
                        'status' => 1
                    ]);
                }
            }
            redirect('admin/delivery_partner/payouts');
        }
    }

    public function payThroughRazorpay($amount, $fundAccount)
    {
        try {
            $accountID = $fundAccount;
            $accountDetails = [];
            $accountDetails['fund_account_id'] = $accountID;
            $accountDetails['amount'] = $amount;
            $accountDetails['currency'] = "INR";
            $accountDetails['mode'] = "NEFT";
            $accountDetails['purpose'] = "payout";
            $razorPayInfo = $this->config->item('razorpay');
            $accountDetails['account_number'] = $razorPayInfo['payout_account'];
            $cURLConnection = curl_init('https://api.razorpay.com/v1/payouts');
            curl_setopt($cURLConnection, CURLOPT_USERPWD, $razorPayInfo['key'] . ":" . $razorPayInfo["secret"]);
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, json_encode($accountDetails));
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(
                $cURLConnection,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json'
                )
            );
            $apiResponse = curl_exec($cURLConnection);
            curl_close($cURLConnection);
            $jsonArrayResponse = json_decode($apiResponse);
            return $jsonArrayResponse;
        } catch (Exception $ex) {
            print_r($ex);
            exit;
        }
    }

    /**
     * Subscriptions crud
     *
     * @author Tejaswini
     * @param string $type
     * @param string $target
     */
    public function subscriptions_packages($type = 'r')
    {
        if ($type == 'c') {
            $this->data['title'] = 'Subscription Packages Add';
            $this->data['content'] = 'admin/admin/add_packages';
            $this->data['nav_type'] = 'subscriptions_packages';
            $this->data['sevices'] = $this->service_model->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 's') {
            $this->form_validation->set_rules($this->package_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $id = $this->package_model->insert([
                    'service_id' => $this->input->post('service_id'),
                    'title' => $this->input->post('title'),
                    'desc' => $this->input->post('desc'),
                    'days' => $this->input->post('days'),
                    'display_price' => $this->input->post('display_price'),
                    'price' => $this->input->post('price'),
                    'created_user_id' => $this->ion_auth->get_user_id()
                ]);

                $path = $_FILES['file']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $this->file_up("file", "subscriptions", $id, '', 'no');
                $this->package_setting_model->saveDefalutValues($id);
                redirect('subscriptions_packages/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Subscription Packages';
            $this->data['content'] = 'admin/admin/subscriptions_packages';
            $this->data['nav_type'] = 'subscriptions_packages';
            $this->data['subscriptions_packages'] = $this->package_model->get_all();
            $this->data['app_details'] = $this->app_details_model->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->package_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $this->package_model->update([
                    'id' => $this->input->post('id'),
                    'service_id' => $this->input->post('service_id'),
                    'title' => $this->input->post('title'),
                    'desc' => $this->input->post('desc'),
                    'days' => $this->input->post('days'),
                    'display_price' => $this->input->post('display_price'),
                    'price' => $this->input->post('price'),
                    'updated_user_id' => $this->ion_auth->get_user_id()
                ], 'id');

                if ($_FILES['file']['name'] !== '') {
                    if (!file_exists('uploads/' . 'subscriptions' . '_image/')) {
                        mkdir('uploads/' . 'subscriptions' . '_image/', 0777, true);
                    }

                    if (file_exists('uploads/' . 'subscriptions' . '_image/' . 'subscriptions' . '_' . $this->input->post('id') . '.jpg')) {
                        unlink('uploads/' . 'subscriptions' . '_image/' . 'subscriptions' . '_' . $this->input->post('id') . '.jpg');
                    }
                    move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'subscriptions' . '_image/' . 'subscriptions' . '_' . $this->input->post('id') . '.jpg');
                }

                redirect('subscriptions_packages/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->package_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit TC';
            $this->data['content'] = 'admin/admin/edit_packages';
            $this->data['nav_type'] = 'subscriptions_packages';
            $this->data['type'] = 'subscriptions_packages';
            $this->data['subscriptions_packages'] = $this->package_model->order_by('id', 'DESC')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->data['app_details'] = $this->app_details_model->get_all();

            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'manage_features') {
            $subcriptionID = $this->input->get('id');
            $this->data['title'] = 'Manage Features';
            $this->data['content'] = 'admin/admin/subscription_features';
            $this->data['nav_type'] = 'subscriptions_packages';
            $this->data['type'] = 'subscriptions_packages';
            $this->data['features'] = $this->master_package_setting_model->where([
                'status' => 1
            ])->order_by('description', 'ASC')->get_all();
            $this->data['enabled_features'] = [];
            $this->data['subscriptions_package_id'] = $subcriptionID;
            $enabledFeatures = $this->package_setting_model->where([
                'package_id' => $subcriptionID,
                'status' => 1
            ])->get_all();
            foreach ($enabledFeatures as $key => $enabledFeature) {
                array_push($this->data['enabled_features'], $enabledFeature['setting_key']);
            }
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'update_features') {
            $postData = $this->input->post();
            $packageID = $postData['id'];
            unset($postData['id']);
            $this->package_setting_model->update([
                'status' => 0
            ], [
                'package_id' => $packageID
            ]);
            foreach ($postData as $setting => $val) {
                $check = $this->package_setting_model->where([
                    'package_id' => $packageID,
                    'setting_key' => $setting
                ])->get();
                if ($check) {
                    $this->package_setting_model->update([
                        'status' => 1
                    ], [
                        'package_id' => $packageID,
                        'setting_key' => $setting
                    ]);
                } else {
                    $this->package_setting_model->insert([
                        'package_id' => $packageID,
                        'setting_key' => $setting,
                        'status' => 1
                    ]);
                }
            }
            redirect('subscriptions_packages/r', 'refresh');
        }
    }
    /**
     * vendor Subscriptions list
     *
     * @author Tejaswini
     * @param string $type
     * @param string $target
     */
    public function vendor_packages($type = 'r')
    {
        if ($type == 'r') {
            $this->data['title'] = 'Vendor Packages';
            $this->data['content'] = 'admin/admin/vendor_packages';
            $this->data['nav_type'] = 'vendor_packages';
            $this->data['vendor_packages'] = $this->vendor_package_model->with_packages('fields: id, title, days,status')->with_services('fields: id,name')->with_vendors('fields:name,email,vendor_user_id')->get_all();
            $this->_render_page($this->template, $this->data);
        }
    }

    /**
     * Return Policies crud
     *
     * @author Tejaswini
     * @param string $type
     * @param string $target
     */
    public function return_policies($type = 'r')
    {
        if ($type == 'c') {
            $this->data['title'] = 'Return Policies Add';
            $this->data['content'] = 'admin/admin/add_returns';
            $this->data['nav_type'] = 'return_policies';
            $this->data['categories'] = $this->category_model->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 's') {
            $this->form_validation->set_rules($this->return_policies_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $return_exist = $this->return_policies_model
                    ->where(['sub_cat_id' => $this->input->post('sub_cat_id'), 'menu_id' => $this->input->post('menu_id')])->get();
                if (!empty($return_exist)) {
                    $this->session->set_flashdata('delete_status', 'Already have a return policy for this product');
                    redirect('return_policies/r', 'refresh');
                } else {
                    $id = $this->return_policies_model->insert([
                        'sub_cat_id' => $this->input->post('sub_cat_id'),
                        'menu_id' => $this->input->post('menu_id'),
                        'return_days' => $this->input->post('return_days'),
                        'terms_conditions' => $this->input->post('return_terms'),
                    ]);
                    $this->session->set_flashdata('upload_status', 'Return Policies has been added successfully');
                    redirect('return_policies/r', 'refresh');
                }
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Return Policies';
            $this->data['content'] = 'admin/admin/return_policies';
            $this->data['nav_type'] = 'return_policies';
            $this->data['return_policies'] = $this->return_policies_model->with_sub_category('fields: id, name')->with_menu('fields: id, name')->order_by('id', DESC)->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'd') {
            $this->return_policies_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->session->set_flashdata('delete_status', 'Return Policy has been deleted successfully');
        }
    }

    /**
     * Service tax crud
     *
     * @author Tejaswini
     * @param string $type
     * @param string $target
     */
    public function service_tax($type = 'r')
    {
        if ($type == 'c') {
            $this->data['title'] = 'Service Tax Add';
            $this->data['content'] = 'admin/admin/add_service_tax';
            $this->data['nav_type'] = 'service_tax';
            $this->data['categories'] = $this->category_model->get_all();
            $this->data['states'] = $this->state_model->order_by('id', 'DESC')->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 's') {
            $this->form_validation->set_rules($this->service_tax_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $id = $this->service_tax_model->insert([
                    'cat_id' => $this->input->post('cat_id'),
                    'sub_cat_id' => $this->input->post('sub_cat_id') == 'all' ? null : $this->input->post('sub_cat_id'),
                    'menu_id' => $this->input->post('menu_id') == 'all' ? null : $this->input->post('menu_id'),
                    'state_id' => $this->input->post('state_id') == 'all' ? null : $this->input->post('state_id'),
                    'district_id' => $this->input->post('district_id') == 'all' ? 0 : $this->input->post('district_id'),
                    'constituency_id' => $this->input->post('constituancy_id') == 'all' ? 0 : $this->input->post('constituancy_id'),
                    'service_tax' => $this->input->post('service_tax'),
                    'rate' => $this->input->post('rate'),
                ]);

                redirect('service_tax/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Service Tax';
            $this->data['content'] = 'admin/admin/service_tax';
            $this->data['nav_type'] = 'service_tax';
            $this->data['service_tax'] = $this->service_tax_model
                ->with_category('fields: id, name')
                ->with_constituency('fields:id,name')
                ->with_state('fields:id,name')
                ->with_district('fields:id,name')
                ->with_subcategory('fields:id,name')
                ->with_menu('fields:id,name')->get_all();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'd') {
            $this->service_tax_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit Service Tax';
            $this->data['content'] = 'admin/admin/edit_service_tax';
            $this->data['nav_type'] = 'service_tax';
            $this->data['type'] = 'service_tax';
            if ($this->ion_auth->is_admin()) {
                $this->data['categories'] = $this->category_model->fields('id,name,desc')->get_all();
                $cat_data = $this->data['categories'];
            } else {
                $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
            }
            $this->data['subcategories'] = $this->sub_category_model->fields('id,name')->where('type', 2)->get_all();
            $this->data['menus'] = $this->food_menu_model->fields('id,name')->order_by('id', 'DESC')->get_all();
            $this->data['states'] = $this->state_model->order_by('id', 'DESC')->get_all();
            $this->data['districts'] = $this->district_model->order_by('id', 'DESC')->get_all();
            $this->data['constituencies'] = $this->constituency_model->with_state('fields:id,name')->with_district('fields:id,name')->order_by('id', 'DESC')->get_all();

            $this->data['service_tax'] = $this->service_tax_model
                ->with_category('fields: id, name')
                ->with_constituency('fields:id,state_id,district_id,name')
                ->with_subcategory('fields:id,name')
                ->with_menu('fields:id,name')
                ->where('id', $this->input->get('id'))
                ->get();
            if ($this->data['service_tax']['sub_cat_id'] == null) {
                $this->data['service_tax']['sub_cat_id'] = "all";
            }
            if ($this->data['service_tax']['menu_id'] == null) {
                $this->data['service_tax']['menu_id'] = "all";
            }
            if ($this->data['service_tax']['district_id'] == null || $this->data['service_tax']['district_id'] == 0) {
                $this->data['service_tax']['district_id'] = "all";
            }
            if ($this->data['service_tax']['constituency_id'] == null || $this->data['service_tax']['constituency_id'] == 0) {
                $this->data['service_tax']['constituency_id'] = "all";
            }
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->service_tax_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $this->service_tax_model->update([
                    'cat_id' => $this->input->post('cat_id'),
                    'sub_cat_id' => $this->input->post('sub_cat_id') == 'all' ? null : $this->input->post('sub_cat_id'),
                    'menu_id' => $this->input->post('menu_id') == 'all' ? null : $this->input->post('menu_id'),
                    'state_id' => $this->input->post('state_id') == 'all' ? null : $this->input->post('state_id'),
                    'district_id' => $this->input->post('district_id') == 'all' ? null : $this->input->post('district_id'),
                    'constituency_id' => $this->input->post('constituancy_id') == 'all' ? null : $this->input->post('constituancy_id'),
                    'service_tax' => $this->input->post('service_tax'),
                    'rate' => $this->input->post('rate'),
                    'updated_user_id' => $this->ion_auth->get_user_id()
                ], $this->input->post('id'));

                redirect('service_tax/r', 'refresh');
            }
        }
    }
    public function check_number()
    {
        $searchText = $this->input->post('search');

        $this->data['user'] = $this->db->query("SELECT phone,id FROM users where phone like '%" . $searchText . "%' limit 5")->result_array();
        foreach ($this->data['user'] as $a) {
            $phone = $a['phone'];
            $id = $a['id'];
            $search_arr[] = array(
                "id" => $id,
                "phone" => $phone
            );
        }
        echo json_encode($search_arr);
    }


    /* code added by manoj for delivery boy payout */
    /*Added by manoj*/
    public function payout_detials($type = 'r', $id, $rowno = 0)
    {

        if ($type == 'details') {
            $v_did = $id;
            $this->data['v_did'] = $v_did;
            $v_id = base64_decode(base64_decode($id));
            $noofrows = $this->filter_config();

            $rowperpage = $noofrows ? $noofrows : 10;
            if ($rowno != 0) {
                $rowno = ($rowno - 1) * $rowperpage;
            }
            $user_id = $v_id;
            $allcount = $this->wallet_transaction_model->all($rowperpage, $rowno, $user_id, $this->data['start_date'], $this->data['end_date'], NULL, NULL, NULL, NULL, TRUE);
            $this->data['wallet_details'] = $this->user_account_model->where('user_id', $user_id)->get();
            $this->data['transactions'] = $this->wallet_transaction_model->all($rowperpage, $rowno, $user_id, $this->data['start_date'], $this->data['end_date'], NULL, NULL, NULL, NULL, FALSE);
            if ($this->data['transactions']) {
                foreach ($this->data['transactions'] as $key => $txn) {
                    $this->data['transactions'][$key]['user_account'] = $this->user_model->fields('id, display_name, phone, first_name')
                        ->where('id', $txn['account_user_id'])
                        ->get();
                }
            } else {
                $this->data['transactions'] = [];
            }
            $url = base_url() . 'admin/payout_detials/details/' . $v_did;
            $this->pagination_config($allcount, $rowperpage, $url);

            $this->data['title'] = 'Delivery Payout Details';
            $this->data['content'] = 'admin/admin/payout_details';
            //$this->data['nav_type'] = 'payment_reports';
            // print_array($this->data);
            $this->_render_page($this->template, $this->data);
        }
        if ($type == 'edit') {

            $this->data['title'] = 'Orders';
            $this->data['content'] = 'admin/admin/order_details';
            $id = base64_decode(base64_decode($id));

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

    public function filter_config()
    {
        $search_text = "";
        $noofrows = 10;
        if ($this->input->post('start_date') != NULL) {
            $search_text = $this->input->post('q');
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $noofrows = $this->input->post('noofrows');
            $this->session->set_flashdata(
                array(
                    "q" => $search_text,
                    'noofrows' => $noofrows,
                    'start_date' => $start_date,
                    'end_date' => $end_date
                )
            );
        } else {
            if ($this->session->flashdata('q') != NULL || $noofrows != NULL || $this->session->flashdata('start_date') != NULL || $this->session->flashdata('end_date') != NULL) {
                $search_text = $this->session->flashdata('q');
                $noofrows = $this->session->flashdata('noofrows');
                $start_date = $this->session->flashdata('start_date');
                $end_date = $this->session->flashdata('end_date');
            }
        }

        $this->data['q'] = $search_text;
        $this->data['noofrows'] = $noofrows;
        $this->data['start_date'] = $start_date;
        $this->data['end_date'] = $end_date;
        return $noofrows;
    }

    public function pagination_config($allcount, $rowperpage, $url)
    {
        $rowperpage = $noofrows ? $noofrows : 10;
        if ($rowno != 0) {
            $rowno = ($rowno - 1) * $rowperpage;
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
        $config['base_url'] = $url;
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $allcount;
        $config['per_page'] = $rowperpage;
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
    }

}
