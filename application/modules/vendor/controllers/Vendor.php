    <?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class Vendor extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->template = 'template/admin/main';

        $this->load->library('pagination');
        $this->load->model('vendor_bank_details_model');
        $this->load->model('vendor_list_model');
        $this->load->model('setting_model');
        $this->load->model('contact_model');
        $this->load->model('social_model');
        $this->load->model('sub_category_model');
        $this->load->model('permission_model');
        $this->load->model('amenity_model');
        $this->load->model('vendor_amenity_model');
        $this->load->model('Notifications_model');
        $this->load->model('vendor_service_model');
        $this->load->model('vendor_sub_category_model');
        $this->load->model('vendor_brand_model');
        $this->load->model('vendor_banner_model');
        $this->load->model('vendor_speciality_model');
        $this->load->model('vendor_od_category_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('group_model');
        $this->load->model('location_model');
        $this->load->model('user_model');
        $this->load->model('constituency_model');
        $this->load->model('details_by_vendor_model');
        $this->load->model('od_category_model');
        $this->load->model('business_address_model');
        $this->load->model('ion_auth_model');
        $this->load->model('user_account_model');
        $this->load->model('payout_model');
        $this->load->model('vendor_package_model');
        $this->load->model('state_model');
        $this->load->model('district_model');
    }

    /**
     * Vendor Profile Settings
     *
     * To Manage Vendor Details
     *
     * @author Mehar
     * @param string $type
     * @param string $target
     */
    public function vendor_profile($type = 'r', $u_type = '')
    {
        if (! $this->ion_auth->logged_in())
            redirect('auth/login');
        
        if ($type == 'r') {
            $this->data['title'] = 'Vendor Profile';
            $this->data['content'] = 'vendor/vendor/vendor_profile';
            $this->data['vendor_details'] = $this->vendor_list_model->with_location('fields: id, address, latitude, longitude')
                ->with_category('fields: id, name')
                ->with_constituency('fields: id, name, state_id, district_id')
                ->with_contacts('fields: id, std_code, number, type')
                ->with_links('fields: id,   url, type')
                ->with_amenities('fields: id, name')
                ->with_services('fields: id, name')
                ->with_brands('fields: id, name')
                ->with_holidays('fields: id')
                ->where('id', $this->ion_auth->get_user_id())
                ->order_by('name', 'DESC')
                ->get();
                $this->data['bank_details'] = $this->vendor_bank_details_model->fields('id,bank_name,bank_branch,ifsc,ac_holder_name,ac_number')
                ->where([
                    'list_id' => $this->data['vendor_details']['id'],
                    'status' => 1
                ])->get();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            if ($u_type == 'bank_details') {
                $this->form_validation->set_rules($this->vendor_bank_details_model->rules);
                if ($this->form_validation->run() == FALSE) {
                    redirect('emp_list/executive?eye_id='.$this->input->post('list_id'));
                } else {
                    $vendor = $this->vendor_list_model->where([
                        'id' => $this->input->post('list_id')
                    ])->get();
                    $userID = $vendor['vendor_user_id'];
                    $r = $this->vendor_bank_details_model->fields('id, ac_number')
                    ->where('list_id', $this->input->post('list_id'))
                        ->get();
                        if (! empty($r)) {
                            
                            
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
                
                       $id = $this->input->post('id');

                        $data = [
                            'user_id'            => $this->input->post('list_id'),
                            'vendor_type'        => $this->input->post('vendor_type'),
                            'executive_name'     => $this->input->post('executive_name'),
                            'executive_id'       => $this->input->post('executive_id'),
                            'team_lead'          => '',
                            'amount'             => $this->input->post('amount'),
                            'area_type'          => $this->input->post('area_type'),
                            'city_name'          => $this->input->post('city_name'),
                            'circle'             => $this->input->post('circle'),
                            'ward'               => $this->input->post('ward'),
                            'executive_target'   => $this->input->post('executive_target'),
                            'target_freelancer'  => $this->input->post('target_freelancer'),
                            'monthly_target'     => $this->input->post('monthly_target'),
                            'team_members'       => json_encode($this->input->post('team') ?? []),
                            'status'             => $this->input->post('status'),
                            'permissions'        => $permissions,
                            'role_type'          => $onboard_roles_str,
                        ];
                        if (!empty($id)) {

                            $exists = $this->db
                                           ->where('id', $id)
                                           ->get('exc_roles')
                                           ->row();
                        
                            if ($exists) {
                                // UPDATE
                                $this->db->where('id', $id)->update('exc_roles', $data);
                            } else {
                                // INSERT
                                $this->db->insert('exc_roles', $data);
                            }
                        
                        } else {
                            // INSERT if no ID provided
                            $this->db->insert('exc_roles', $data);
                        }
                        
                        
                        if($this->input->post('status') == 'approved'){
                            $status = "1";
                            
                        }elseif($this->input->post('status') == 'rejected'){
                            $status = "2";
                        }else{
                            $status = "0";
                        }
                        $this->db->where('id', $this->input->post('list_id'))->update('users', [
                            'status'         => $status
                        ]);
                        
                       
                        if($r['ac_number'] !=$this->input->post('ac_number')){
                            $this->vendor_bank_details_model->update([
                                'list_id' => $this->input->post('list_id'),
                                'status' => 2
                            ], 'list_id');
                            $this->vendor_bank_details_model->insert([
                                'bank_name' => $this->input->post('bank_name'),
                                'bank_branch' => $this->input->post('bank_branch'),
                                'ifsc' => $this->input->post('ifsc'),
                                'ac_holder_name' => $this->input->post('ac_holder_name'),
                                'ac_number' => $this->input->post('ac_number'),
                                'list_id' => $this->input->post('list_id')
                            ]);
                            $this->user_account_model->checkandUpdateAccount($userID, $this->input->post('list_id'));
                        }else{
                             
                            $this->vendor_bank_details_model->update([
                                'list_id' => $this->input->post('list_id'),
                                'bank_name' => $this->input->post('bank_name'),
                                'bank_branch' => $this->input->post('bank_branch'),
                                'ifsc' => $this->input->post('ifsc'),
                                'ac_holder_name' => $this->input->post('ac_holder_name'),
                                'ac_number' => $this->input->post('ac_number')
                            ], 'list_id');
                        }
                        redirect('emp_list/executive?eye_id='.$this->input->post('list_id'));
                    } else {
                        
                        $this->vendor_bank_details_model->insert([
                            'bank_name' => $this->input->post('bank_name'),
                            'bank_branch' => $this->input->post('bank_branch'),
                            'ifsc' => $this->input->post('ifsc'),
                            'ac_holder_name' => $this->input->post('ac_holder_name'),
                            'ac_number' => $this->input->post('ac_number'),
                            'list_id' => $this->input->post('list_id')
                        ]);
                        $this->user_account_model->checkandUpdateAccount($userID, $this->input->post('list_id'));
                        redirect('emp_list/executive?eye_id='.$this->input->post('list_id'));
                    }
                }
            } 
        }elseif ($type == 'edit'){
            $this->data['title'] = 'Vendor Profile edit';
            $this->data['content'] = 'vendor/vendor/edit_profile';
            $this->data['nav_type'] = 'vendors_filter';
            $this->data['states'] = $this->state_model->order_by('id', 'DESC')->get_all();
            $this->data['districts'] = $this->district_model->order_by('id', 'DESC')->get_all();
            $this->data['constituencies'] = $this->constituency_model->with_state('fields:id,name')->with_district('fields:id,name')->order_by('id', 'DESC')->get_all();
            $this->data['vendor_details'] = $this->vendor_list_model
            ->with_location('fields: id, address, latitude, longitude')
            ->with_address()
            ->with_users()
            ->with_category('fields: id, name')
            ->with_constituency('fields: id, name, state_id, district_id')
            ->with_sub_categories('fields: id, name')
            ->with_contacts('fields: id, std_code, number, type')
            ->with_links('fields: id,   url, type')
            ->with_amenities('fields: id, name')
            ->with_services('fields: id, name')
            ->with_brands('fields: id, name')
            ->with_banners('fields: id, image, ext')
            ->with_holidays('fields: id')
            ->with_on_demand_categories('fields:id,name')
            ->with_specialities('fields:id,name')
            ->where('id', $_GET['id'])
            ->get();
            $this->data['vendor_details']['constituency'] = $this->constituency_model->fields('id,name, state_id, district_id')
            ->where('id', $this->data['vendor_details']['address']['constituency'])
            ->get();
            $this->data['vendor_details']['unique_id'] = $this->data['vendor_details']['vendor_user_id'];
            $this->data['vendor_details']['name'] = $this->data['vendor_details']['name'];
            $this->data['vendor_details']['email'] = $this->data['vendor_details']['users']['email'];

           //print_array( $this->data['vendor_specialities']);exit();
            $this->data['bank_details'] = $this->vendor_bank_details_model->fields('id,bank_name,bank_branch,ifsc,ac_holder_name,ac_number')
            ->where('list_id', $this->data['vendor_details']['id'])
            ->get();
            $this->data['categories'] = $this->category_model->get_all();
            $this->data['amenities'] = $this->amenity_model->order_by('name', 'ASC')->where('cat_id', $this->data['vendor_details']['category_id'])->get_all();
            $this->data['sub_categories'] = $this->sub_category_model->where(['cat_id'=> $this->data['vendor_details']['category_id'], 'type' =>1])->order_by('name', 'ASC')->get_all();
            $this->data['od_categories'] = $this->od_category_model->where(['cat_id'=> $this->data['vendor_details']['category_id']])->order_by('name', 'ASC')->get_all();
            $this->data['services'] = $this->service_model->order_by('name', 'ASC')->get_all();
            $this->data['brands'] = $this->brand_model->order_by('name', 'ASC')->get_all();
            $this->data['brands'] = $this->brand_model->order_by('name', 'ASC')->get_all();
            $this->data['vendor_specialities'] = $this->hosp_speciality_model->get_all();
            $list_vendor_packages = $this->vendor_package_model->fields('id,service_id,package_id,created_at, status')->with_packages('fields: id, title,desc,days,display_price,price')->where(['created_user_id' =>$this->data['vendor_details']['vendor_user_id'], 'service_id' => 2, 'status' => 1])->get_all();
            $this->data['vendor_packages'] =[];
            if (!empty($list_vendor_packages)) {
                foreach ($list_vendor_packages as $k => $package) {
                    $validity = date('Y-m-d H:i:s', strtotime(($list_vendor_packages[$k]['created_at']) . "+" . $list_vendor_packages[$k]['packages']['days'] . "days"));
                    if((strtotime($validity) >= now()))
                    {
                        $mydate=getdate(date("U")); 
                        $today=date_create("$mydate[year]-$mydate[mon]-$mydate[mday]"); 
                        $createdAt=date_create($list_vendor_packages[$k]['created_at']);
                        $diff=date_diff($createdAt,$today);
                        $days = $list_vendor_packages[$k]['packages']['days'] - $diff->format("%a");
                        $list_vendor_packages[$k]['days_left'] = $days;
                        array_push($this->data['vendor_packages'],$list_vendor_packages[$k]);
                    }
                }
            }
            $this->_render_page($this->template, $this->data);
        }elseif ($type == 'profile'){
            $this->form_validation->set_rules($this->vendor_list_model->rules['profile']);
            if ($this->form_validation->run() == FALSE) {
                redirect('vendor_profile/edit?id='.$this->input->post('id'));
            } else {
                $this->vendor_list_model->update([
                    'id' => $this->input->post('id'),
                    'name' => $this->input->post('name'),
                    'business_description' => $this->input->post('business_description'),
                    'owner_name' => $this->input->post('owner_name'),
                    'email' => $this->input->post('email'),
                    'secondary_contact' => $this->input->post('helpline'),
                    'whats_app_no' => $this->input->post('whatsapp'),
                    'landmark' => $this->input->post('landmark'),
                    'desc' => $this->input->post('desc'),
                    'gst_number' => $this->input->post('gst_number'),
                    'labour_certificate_number' => $this->input->post('labour_certificate_number'),
                    'fssai_number' => $this->input->post('fssai_number'),
                    'availability' => $this->input->post('availability'),
                    'from' => $this->input->post('from_date'),
                    'to' => $this->input->post('to_date'),
                    'extend_time_days' => $this->input->post('total_days')
                ], 'id');
                
               // echo $this->db->last_query(); exit;
                $this->business_address_model->mutateAddressAndConstituency($this->input->post('id'), $this->input->post('address'), $this->input->post('state'), $this->input->post('district'), $this->input->post('constituency'));
                $is_location_exist = $this->location_model->where(['latitude' => $this->input->post('latitude'), 'longitude' => $this->input->post('longitude')])->get();
                if(empty($is_location_exist)){
                    $location_id = $this->location_model->insert([
                        'address' => $this->input->post('location_name'),
                        'latitude' => $this->input->post('latitude'),
                        'longitude' => $this->input->post('longitude'),
                    ]);
                }else{
                    $location_id = $is_location_exist['id'];
                }
                $this->vendor_list_model->update(['location_id' => $location_id], $this->input->post('id'));
                
                // if($this->contact_model->where(['list_id' => $this->input->post('id'), 'type' => 1])->get() != FALSE)
                //     $this->contact_model->update(['std_code' => $this->input->post('mobile_code'), 'number' => $this->input->post('mobile')], ['list_id' => $this->input->post('id'), 'type' => 1]);
                // else 
                //     $this->contact_model->insert(['list_id' => $this->input->post('id'), 'std_code' => $this->input->post('mobile_code'), 'number' => $this->input->post('mobile'), 'type' => 1]);
                
                // if($this->contact_model->where(['list_id' => $this->input->post('id'), 'type' => 2])->get() != FALSE)
                //     $this->contact_model->update(['std_code' => $this->input->post('landline_code'), 'number' => $this->input->post('landline')], ['list_id' => $this->input->post('id'), 'type' => 2]);
                // else
                //     $this->contact_model->insert(['list_id' => $this->input->post('id'), 'std_code' => $this->input->post('landline_code'), 'number' => $this->input->post('landline'), 'type' => 2]);
                
                // if($this->contact_model->where(['list_id' => $this->input->post('id'), 'type' => 3])->get() != FALSE)
                //     $this->contact_model->update(['std_code' => $this->input->post('whatsapp_code'), 'number' => $this->input->post('whatsapp')], ['list_id' => $this->input->post('id'), 'type' => 3]);
                // else 
                //     $this->contact_model->insert(['list_id' => $this->input->post('id'), 'std_code' => $this->input->post('whatsapp_code'), 'number' => $this->input->post('whatsapp'), 'type' => 3]);
                
                // if($this->contact_model->where(['list_id' => $this->input->post('id'), 'type' => 4])->get() != FALSE)
                //     $this->contact_model->update(['std_code' => $this->input->post('helpline_code'), 'number' => $this->input->post('helpline')], ['list_id' => $this->input->post('id'), 'type' => 4]);
                // else 
                //     $this->contact_model->insert(['list_id' => $this->input->post('id'), 'std_code' => $this->input->post('helpline_code'), 'number' => $this->input->post('helpline'), 'type' => 4]);
                $vendorRec = $this->vendor_list_model->where([
                    'id' => $this->input->post('id')
                ])->get();
                $this->user_model->update([
				'email' => $this->input->post('email'),
                    'phone' => $this->input->post('mobile')
                ], $vendorRec['vendor_user_id']);
                    $page = $this->input->post('page') ?? 1;
                    redirect('vendor/vendors_filter/' . $page, 'refresh');
                //redirect('vendor_profile/edit?id='.$this->input->post('id'));
            }
        }elseif ($type == 'filters'){
                $sub_categories_data  = $amenities_data = $services_data = $brands_data = $od_categories_data = $specialities_data = [];
                $m = $n = $o = $j = $od = $sd = 0;
                $sub_categories = $this->input->post('sub_categories');
                $amenities = $this->input->post('amenities');
                $services = $this->input->post('services');
                $brands = $this->input->post('brands');
                $od_categories = $this->input->post('od_categories');
                $specialities = $this->input->post('specialities');
                if(! empty($services)){
                    foreach ($services as $key => $val) {
                        $services_data[$o ++] = [
                            'list_id' => $this->input->post('id'),
                            'service_id' => $val
                        ];
                    }
                    $this->db->where('list_id', $this->input->post('id'));
                    $this->db->delete('vendor_services');
                    $this->vendor_service_model->insert($services_data);
                    $this->db->where('user_id', $this->input->post('vendor_user_id'));
                    $is_deleted = $this->db->delete('users_permissions');
                    if($is_deleted){
                        foreach ($services as $service){
                            $service_details = $this->db->select('permission_parent_ids')->where('id', $service)->get('services')->result_array();
                            $perms = explode(',', $service_details[0]['permission_parent_ids']);
                            foreach ($perms as $perm){
                                $child_permissions = $this->permission_model->where('parent_status', $perm)->as_array()->get_all();
                                if(!empty($child_permissions)){
                                    foreach($child_permissions as $child_permission){
                                        $get_perm = $this->db->get_where('users_permissions', ['user_id' => $this->input->post('vendor_user_id'), 'perm_id' => $child_permission['id'], 'value' => 1])->result_array();
                                        if(empty($get_perm))
                                            $this->db->insert('users_permissions', ['user_id' => $this->input->post('vendor_user_id'), 'perm_id' => $child_permission['id'], 'value' => 1]);
                                    }
                                }
                                $get_perm = $this->db->get_where('users_permissions', ['user_id' => $this->input->post('vendor_user_id'), 'perm_id' => $perm, 'value' => 1])->result_array();
                                    if(empty($get_perm))
                                $this->db->insert('users_permissions', ['user_id' => $this->input->post('vendor_user_id'), 'perm_id' => $perm, 'value' => 1]);
                            }
                        }
                    }
                }
                
                if(! empty($brands)){foreach ($brands as $key => $val) {
                    $brands_data[$j ++] = [
                        'list_id' => $this->input->post('id'),
                        'brand_id' => $val
                    ];
                }
                $this->db->where('list_id', $this->input->post('id'));
                $this->db->delete('vendor_brands');
                $this->vendor_brand_model->insert($brands_data);
                }
                
                if(! empty($amenities)){foreach ($amenities as $key => $val) {
                    $amenities_data[$n ++] = [
                        'list_id' => $this->input->post('id'),
                        'amenity_id' => $val
                    ];
                }
                $this->db->where('list_id', $this->input->post('id'));
                $this->db->delete('vendor_amenities');
                $this->vendor_amenity_model->insert($amenities_data);
                }
                
                if(! empty($sub_categories)){foreach ($sub_categories as $key => $val) {
                    $sub_categories_data[$m ++] = [
                        'list_id' => $this->input->post('id'),
                        'sub_category_id' => $val
                    ];
                }
                $this->db->where('list_id', $this->input->post('id'));
                $this->db->delete('vendors_sub_categories');
                $this->vendor_sub_category_model->insert($sub_categories_data);
                }

                // <!-trupti-->
                if(! empty($od_categories)){foreach ($od_categories as $key => $val) {
                    $od_categories_data[$od ++] = [
                        'list_id' => $this->input->post('id'),
                        'od_cat_id' => $val
                    ];
                }
                $this->db->where('list_id', $this->input->post('id'));
                $this->db->delete('vendors_od_categories');
                $this->vendor_od_category_model->insert($od_categories_data);
                }

                 if(! empty($specialities)){foreach ($specialities as $key => $val) {
                    $specialities_data[$sd ++] = [
                        'list_id' => $this->input->post('id'),
                        'speciality_id' => $val
                    ];
                }
                $this->db->where('list_id', $this->input->post('id'));
                $this->db->delete('vendors_specialties');
                $this->vendor_speciality_model->insert($specialities_data);
                }
                redirect('vendor_profile/edit?id='.$this->input->post('id'));
        }elseif ($type == 'social'){
            $this->form_validation->set_rules($this->vendor_list_model->rules['social']);
            if ($this->form_validation->run() == FALSE) {
                redirect('vendor_profile/edit?id='.$this->input->post('id'));
            } else {
                if($this->social_model->where(['list_id' => $this->input->post('id'), 'type' => 1])->get() != FALSE)
                    $this->social_model->update(['url' => $this->input->post('facebook')], ['list_id' => $this->input->post('id'), 'type' => 1]);
                else 
                    $this->social_model->insert(['list_id' => $this->input->post('id'), 'url' => $this->input->post('facebook'), 'type' => 1]);
                
                if($this->social_model->where(['list_id' => $this->input->post('id'), 'type' => 2])->get() != FALSE)
                    $this->social_model->update(['url' => $this->input->post('twitter')], ['list_id' => $this->input->post('id'), 'type' => 2]);
                else
                    $this->social_model->insert(['list_id' => $this->input->post('id'), 'url' => $this->input->post(''), 'type' => 2]);
                
                if($this->social_model->where(['list_id' => $this->input->post('id'), 'type' => 3])->get() != FALSE)
                    $this->social_model->update(['url' => $this->input->post('instagram')], ['list_id' => $this->input->post('id'), 'type' => 3]);
                else
                    $this->social_model->insert(['list_id' => $this->input->post('id'), 'url' => $this->input->post('instagram'), 'type' => 3]);
                
                if($this->social_model->where(['list_id' => $this->input->post('id'), 'type' => 4])->get() != FALSE)
                    $this->social_model->update(['url' => $this->input->post('website')], ['list_id' => $this->input->post('id'), 'type' => 4]);
                else
                    $this->social_model->insert(['list_id' => $this->input->post('id'), 'url' => $this->input->post('website'), 'type' => 4]);
                
                redirect('vendor_profile/edit?id='.$this->input->post('id'));
            }
        }elseif ($type == 'cover'){
            $id = $this->input->post('id');
            if ($_FILES['file']['name'] !== '') {
                if (!file_exists('uploads/' . 'list_cover' . '_image/')) {
                    mkdir('uploads/' . 'list_cover' . '_image/', 0777, true);
                }
                move_uploaded_file($_FILES['file']['tmp_name'], "./uploads/list_cover_image/list_cover_$id.jpg");
            }
            redirect('vendor_profile/edit?id='.$this->input->post('id'));
        }elseif ($type == 'banners'){
            $image_id = $this->vendor_banner_model->insert([
                'list_id' => $this->input->post('id'),
                'image' => 'banner_'.$this->input->post('id').'.jpg',
                'ext' => 'jpg'
            ]);
            if ($_FILES['banner']['name'] !== '') {
                if (!file_exists('uploads/' . 'list_banner' . '_image/')) {
                    mkdir('uploads/' . 'list_banner' . '_image/', 0777, true);
                }
                move_uploaded_file($_FILES['banner']['tmp_name'], "./uploads/list_banner_image/list_banner_$image_id.jpg");
            }
            redirect('vendor_profile/edit?id='.$this->input->post('id'));
        }elseif ($type == 'banner_edit'){
            $this->data['title'] = 'Vendor Profile edit';
            $this->data['content'] = 'vendor/vendor/edit_banner';
            $this->data['nav_type'] = 'vendors_filter';
            $this->data['banner'] = $this->vendor_banner_model->where('id', $_GET['id'])->get();
            $this->_render_page($this->template, $this->data);
        }elseif ($type == 'update_banner'){
            if ($_FILES['banner']['name'] !== '') {
                if (!file_exists('uploads/' . 'list_banner' . '_image/')) {
                    mkdir('uploads/' . 'list_banner' . '_image/', 0777, true);
                }
                move_uploaded_file($_FILES['banner']['tmp_name'], "./uploads/list_banner_image/list_banner_".$this->input->post('id').".jpg");
            }
            redirect('vendor_profile/edit?id='.$this->input->post('list_id'));
        }elseif ($type == 'd'){
            echo $this->vendor_banner_model->delete(['id' => $this->input->post('id')]);
        }
    } 
    
    /**
     * @author Mehar
     * @desc list of vendors with relevent filters with pagination
     * @param number $rowno
     */
    public function vendorNotifyStatusChange($id){
        $this->Notifications_model->update([
            'notification_type_id' => $id,
            'read_status' => '1'
        ], 'notification_type_id');
        return true;
    }
    
    public function vendors_filter($rowno = 0){
        if (! $this->ion_auth->logged_in())
            redirect('auth/login');
        
        $this->data['title'] = 'All Vendors';
        $this->data['content'] = 'vendor/vendor/vendor_filter';
        $this->data['nav_type'] = 'vendors_filter';
        $this->data['categories'] = $this->category_model->get_all();
        $this->data['subcategories'] = $this->sub_category_model->get_all();
        $this->data['executive'] = $this->user_model->get_all();
        $this->data['constituency'] = $this->constituency_model->get_all();
        
        //serach Filters
        $search_text = $exe_text =  $mobile_text = ""; $status= 0;$noofrows = 10;
        if($this->input->server('REQUEST_METHOD') === 'POST'){
			//var_dump($_POST); exit;
            $search_text = $this->input->post('q'); 
            $exe_text = $this->input->post('exe');
            $mobile_text = $this->input->post('mobile');
            $status = $this->input->post('status');
            $noofrows = $this->input->post('noofrows');
            $this->session->set_userdata(array("q"=>$search_text, 'exe' => $exe_text, 'mobile' => $mobile_text, 'status' => $status, 'noofrows' => $noofrows));
        }elseif($rowno > 0 && ($this->session->userdata('q') != NULL || $this->session->userdata('exe') != NULL || $this->session->userdata('mobile') != NULL || $status != NULL || $noofrows != NULL)){            
			$search_text = $this->session->userdata('q');
			$exe_text = $this->session->userdata('exe');
			$mobile_text = $this->session->userdata('mobile');
			$status = $this->session->userdata('status');
			$noofrows = $this->session->userdata('noofrows');
        }else {
			$this->session->unset_userdata(['q','exe','mobile','status','noofrows']);
		}
        $rowperpage = $noofrows? $noofrows: 10;
        if($rowno != 0){
            $rowno = ($rowno-1) * $rowperpage;
        }
         
        $allcount = $this->vendor_list_model->vendor_count($status ? $status : 1, NULL, NULL, NULL, $search_text, $exe_text, $mobile_text);

        $users_record = $this->vendor_list_model->get_vendors($rowperpage, $rowno, $status ? $status : NULL, NULL, NULL, NULL,  $search_text, $exe_text, $mobile_text);

         
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] ="</ul>";
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
        $config['base_url'] = base_url().'vendor/vendors_filter';
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $allcount;
        $config['per_page'] = $rowperpage;
        $this->pagination->initialize($config);
        
        $this->data['pagination'] = $this->pagination->create_links();
        $this->data['vendors'] = $users_record;
      $arr =  array_column($users_record, 'id');
      
        $this->data['contacts'] = $this->contact_model->where(['list_id' => implode( ',' ,$arr), 'type' => 1] )->get_all();
        // $this->data['vendor_subcategories'] = $this->vendor_sub_category_model->with_subcategory('fields:id,name')->where(['list_id' => implode( ',' ,$arr)] )->get_all();
        $this->data['row'] = $rowno;
        $this->data['q'] = $search_text;
        $this->data['exe'] = $exe_text;
        $this->data['mobile'] = $mobile_text;
        $this->data['status'] = $status;
        $this->data['noofrows'] = $rowperpage;
        $this->_render_page($this->template, $this->data);
    }
    
    public function vendor_payments($type = 'r'){
        if (! $this->ion_auth->logged_in())
            redirect('auth/login');
        
        if($type == 'r'){
            $this->data['title'] = 'Vendor Payments';
            $this->data['content'] = 'vendor/vendor/vendor_payments';

            if(isset($_POST['id'])){
                $this->data['transactions'] = $this->wallet_transaction_model->all($_POST['id'], (empty($_POST['start_date']))? NULL: $_POST['start_date'], (empty($_POST['end_date']))? NULL: $_POST['end_date']);
                
                $this->session->set_flashdata('txn_search',[
                    'id' => $_POST['id'],
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date']
                ]);
                $this->data['vendor'] = $this->user_model->where('id', $_POST['id'])->get();
            }else{
             $this->data['transactions'] = $this->wallet_transaction_model->all($_GET['id']);
            $this->data['vendor'] = $this->user_model->where('id', $_GET['id'])->get();
            }
            $this->_render_page($this->template, $this->data);
        }
    }
    
    public function modify_category(){
        if (! $this->ion_auth->logged_in())
            redirect('auth/login');
        
        $is_updated = $this->vendor_list_model->update([
            'id' => $this->input->post('list_id'),
            'category_id' => $this->input->post('cat_id')
        ], 'id');
        if($is_updated){
            $sub_categories = $this->sub_category_model->where('cat_id', $this->input->post('cat_id'))->get_all();
            if(isset($sub_categories)){$sub_categories_data = []; $m = 0;foreach ($sub_categories as $key => $val) {
                $sub_categories_data[$m ++] = [
                    'list_id' => $this->input->post('list_id'),
                    'sub_category_id' => $val['id']
                ];
            }}
            $this->db->where('list_id', $this->input->post('list_id'));
            $this->db->delete('vendors_sub_categories');
            $this->vendor_sub_category_model->insert($sub_categories_data); 
            echo 200;
        }else{
            echo 500;
        }
        
    }
    
    // file upload functionality
    public function vendor_excel_import() {
        if (! $this->ion_auth->logged_in())
            redirect('auth/login');
        
        $this->data['title'] = 'Vendor Excel Important';
        $this->data['content'] = 'vendor/vendor/vendor_excel_import';
        $this->form_validation->set_rules('fileURL', 'Upload File', 'callback_checkFileValidation');
        $message = "";
        if($this->form_validation->run() == false) {
            $this->_render_page($this->template, $this->data);
        } else {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', '0');
            // If file uploaded
            if(!empty($_FILES['fileURL']['name'])) {
                // get file extension
                $extension = pathinfo($_FILES['fileURL']['name'], PATHINFO_EXTENSION);
                
                if($extension == 'csv'){
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } elseif($extension == 'xlsx') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                }
                // file path
                $spreadsheet = $reader->load($_FILES['fileURL']['tmp_name']);
                $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                
                // array Count
                $arrayCount = count($allDataInSheet);
                $flag = 0;
                // $createArray = array('Executive', 'Category', 'Company','Locality', 'Address', 'PIN', 'Email', 'WhatsApp', 'Phone #1', 'Phone #2', 'Phone #3', 'Phone #4', 'Latitude', 'Longitude', 'Rating', 'Reviews', 'Verified', 'Paid', 'Website');
                // $makeArray = array('Executive' => 'Executive', 'Category' => 'Category', 'Company' => 'Company', 'Locality' => 'Locality', 'Address' => 'Address', 'PIN' => 'PIN', 'Email' => 'Email', 'WhatsApp' => 'WhatsApp', 'Phone#1' => 'Phone #1', 'Phone#2' => 'Phone #2', 'Phone#3' => 'Phone #3', 'Phone#4' => 'Phone #4', 'Latitude' => 'Latitude', 'Longitude' => 'Longitude', 'Rating' => 'Rating', 'Reviews' => 'Reviews', 'Verified' => 'Verified', 'Paid' => 'Paid', 'Website' => 'Website');
                $createArray = array('Executive', 'Owner', 'Category', 'SubCategory', 'BusinessName', 'Locality', 'Address', 'PIN', 'Email', 'Mobile', 'WhatsApp', 'AdditionalMobile', 'GST', 'FSSAI', 'LabourCertificationNumber', 'Latitude', 'Longitude', 'Rating', 'Reviews', 'Verified', 'Paid', 'Website', 'Constituency');
                $makeArray = array('Executive' => 'Executive', 'Owner' => 'Owner', 'Category' => 'Category', 'SubCategory' => 'SubCategory', 'BusinessName' => 'BusinessName', 'Locality' => 'Locality', 'Address' => 'Address', 'PIN' => 'PIN', 'Email' => 'Email', 'Mobile' => 'Mobile', 'WhatsApp' => 'WhatsApp', 'AdditionalMobile' => 'AdditionalMobile', 'GST' => 'GST', 'FSSAI' => 'FSSAI', 'LabourCertificationNumber' => 'LabourCertificationNumber', 'Latitude' => 'Latitude', 'Longitude' => 'Longitude', 'Rating' => 'Rating', 'Reviews' => 'Reviews', 'Verified' => 'Verified', 'Paid' => 'Paid', 'Website' => 'Website', 'Constituency' => 'Constituency');
                /* $createArray = array('Name', 'Category_Id','Mobile', 'Email', 'Address', 'Landmark', 'Pincode', 'Latitude', 'Longitude', 'Location_Address');
                $makeArray = array('Name' => 'Name', 'Category_Id' => 'Category_Id', 'Mobile' => 'Mobile', 'Email' => 'Email', 'Address' => 'Address', 'Landmark' => 'Landmark', 'Pincode' => 'Pincode', 'Latitude' => 'Latitude', 'Longitude' => 'Longitude', 'Location_Address' => 'Location_Address'); */
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
                if ($flag == 1) { $k = 0;
                    for ($i = 2; $i <= $arrayCount; $i++) {
                        
                        $executive = $SheetDataKey['Executive'];
                        $cat_id = $SheetDataKey['Category'];
                        $sub_cat_ids = $SheetDataKey['SubCategory'];
                        $company = $SheetDataKey['BusinessName'];
                        $email = $SheetDataKey['Email'];
                        $address = $SheetDataKey['Address'];
                        $landmark = $SheetDataKey['Locality'];
                        $pincode = $SheetDataKey['PIN'];
                        $latitude = $SheetDataKey['Latitude'];
                        $longitude = $SheetDataKey['Longitude'];
                        $whatsapp = $SheetDataKey['WhatsApp'];
                        $website = $SheetDataKey['Website'];
                        $owner = $SheetDataKey['Owner'];
                        $mobile = $SheetDataKey['Mobile'];
                        $secondaryContact = $SheetDataKey['AdditionalMobile'];
                        $gst = $SheetDataKey['GST'];
                        $fssai = $SheetDataKey['FSSAI'];
                        $labourCertificate = $SheetDataKey['LabourCertificationNumber'];
                        $constituency = $SheetDataKey['Constituency'];
                        $group_id = [];
                        
                        $executive = filter_var(trim($allDataInSheet[$i][$executive]), FILTER_SANITIZE_STRING);
                        $company = filter_var(trim($allDataInSheet[$i][$company]), FILTER_SANITIZE_STRING);
                        $cat_id = filter_var(trim($allDataInSheet[$i][$cat_id]), FILTER_SANITIZE_STRING);
                        $whatsapp = filter_var(trim($allDataInSheet[$i][$whatsapp]), FILTER_SANITIZE_STRING);
                        $email = filter_var(trim($allDataInSheet[$i][$email]), FILTER_SANITIZE_EMAIL);
                        $address = filter_var(trim($allDataInSheet[$i][$address]), FILTER_SANITIZE_STRING);
                        $landmark = filter_var(trim($allDataInSheet[$i][$landmark]), FILTER_SANITIZE_STRING);
                        $pincode = filter_var(trim($allDataInSheet[$i][$pincode]), FILTER_SANITIZE_STRING);
                        $latitude = filter_var(trim($allDataInSheet[$i][$latitude]), FILTER_SANITIZE_STRING);
                        $longitude = filter_var(trim($allDataInSheet[$i][$longitude]), FILTER_SANITIZE_STRING);
                        $website = filter_var(trim($allDataInSheet[$i][$website]), FILTER_SANITIZE_STRING);
                        $owner = filter_var(trim($allDataInSheet[$i][$owner]), FILTER_SANITIZE_STRING);
                        $mobile = filter_var(trim($allDataInSheet[$i][$mobile]), FILTER_SANITIZE_STRING);
                        $secondaryContact = filter_var(trim($allDataInSheet[$i][$secondaryContact]), FILTER_SANITIZE_STRING);
                        $gst = filter_var(trim($allDataInSheet[$i][$gst]), FILTER_SANITIZE_STRING);
                        $fssai = filter_var(trim($allDataInSheet[$i][$fssai]), FILTER_SANITIZE_STRING);
                        $labourCertificate = filter_var(trim($allDataInSheet[$i][$labourCertificate]), FILTER_SANITIZE_STRING);
                        $sub_cat_ids = filter_var(trim($allDataInSheet[$i][$sub_cat_ids]), FILTER_SANITIZE_STRING);
                        $constituency = filter_var(trim($allDataInSheet[$i][$constituency]), FILTER_SANITIZE_STRING);
                        $sounds_like = $this->sounds_like($company, $cat_id, $landmark);
                        $subCatArry = explode(',', $sub_cat_ids);
                        if(! empty($company)  &&  ! empty($cat_id)){
                            // $group = $this->group_model->where('name', 'vendor')->get();
                            // $unique_id = generate_serial_no($group['code'], 4, $group['last_id']);
                            // $this->group_model->update([
                            //     'last_id' => $group['last_id'] + 1
                            // ], $group['id']);
                            
                            $identity = $mobile;
                            $additional_data = array(
                                'first_name' => $owner,
                                'display_name' => $owner,
                                'email' => $email,
                                // 'unique_id' => $unique_id,
                                'phone' => $mobile,
                                'active' => 1
                            );

                            // $group_id[] = $group['id'];
                            
                            if ($this->check_email($email) == FALSE) {
                                $this->session->set_flashdata('upload_status', ["error" => "Error occured at row no($i)--- Email is already exist"]);
                                $message .= "$email Email is already exist<br/>";
                            }elseif ($this->check_user_phone($mobile) == FALSE){
                                $this->session->set_flashdata('upload_status', ["error" => "Error occured at row no($i)--- Whatsapp Number is already exist"]);
                                $message .= "$mobile Mobile is already exist<br/>";
                            } else {

                                $user_id = $this->ion_auth_model->register($identity,  '1234', $email, $additional_data, 'vendor', TRUE);
                                if(! empty($user_id)){
                                    $is_location_exist = $this->location_model->where(['latitude' => $latitude, 'longitude' => $longitude])->get();
                                    if(empty($is_location_exist)){
                                        $location_id = $this->location_model->insert([
                                            'address' => $landmark,
                                            'latitude' => $latitude,
                                            'longitude' => $longitude,
                                        ]);
                                    }else{
                                        $location_id = $is_location_exist['id'];
                                    }

                                    $vendors_list_data = [
                                        'owner_name' => $owner,
                                        'business_name' => $company,
                                        'name' => $company,
                                        'email' => $email,
                                        'vendor_user_id' => $user_id,
                                        'executive_user_id' => $executive,
                                        // 'unique_id' => $unique_id,
                                        'category_id' => $cat_id,
                                        'location_id' => $location_id,
                                        // 'address' => $address,
                                        // 'landmark' => $landmark,
                                        // 'pincode' => $pincode,
                                        'fssai_number'=> $fssai,
                                        'gst_number'=> $gst,
                                        'labour_certificate_number'=> $labourCertificate,
                                        'secondary_contact'=> $secondaryContact,
                                        'whats_app_no'=> $whatsapp,
                                        'status' => 1,
                                        'sounds_like' => $sounds_like
                                    ];
                                    
                                    //$this->add_permissions_to_user($user_id, $cat_id);
                                    $this->db->insert('vendors_list', $vendors_list_data);
                                    $insert_id = $this->db->insert_id();
                                    $this->business_address_model->insert([
                                        'list_id'=> $insert_id,
                                        'lat'=> $latitude,
                                        'lng'=> $longitude,
                                        'line1'=> $address,
                                        'location'=> $landmark,
                                        'zip_code'=> $pincode,
                                        'constituency'=> $constituency
                                    ]);

                                    if(!empty($executive)){
                                        $userAccount = $this->user_account_model->where([
                                            'user_id' => $executive
                                        ])->get();
                                        if(!empty($userAccount)){
                                            $this->wallet_transaction_model->insert([
                                                'account_user_id' => $executive,
                                                'created_user_id' => !empty($this->ion_auth->get_user_id()) ? $this->ion_auth->get_user_id() : $executive,
                                                'amount' => floatval($this->setting_model->where('key', 'pay_per_vendor')->get()['value']),
                                                'balance' => (floatval($userAccount['wallet'])) + (floatval($this->setting_model->where('key', 'pay_per_vendor')->get()['value'])),
                                                'txn_id' => 'NC-' . generate_trasaction_no(),
                                                'ecom_order_id' => NULL,
                                                'type' => 'CREDIT',
                                                'message' => NULL,
                                                'status' => 1
                                            ]);
                                            $this->user_account_model->update([
                                                'wallet' => $userAccount['wallet'] + floatval($this->setting_model->where('key', 'pay_per_vendor')
                                                    ->get()['value'])
                                            ], ['user_id' => $executive]);
                                        }

                                    }
                                    // $this->contact_model->insert([['list_id' =>  $insert_id, 'std_code' => '', 'number' => $whatsapp, 'type' => 1, 'created_user_id' => $user_id],['list_id' => $insert_id, 'std_code' => '', 'number' => $whatsapp, 'type' => 3, 'created_user_id' => $user_id]]);
                                    $this->social_model->insert([ 'list_id' => $insert_id, 'url' => $website, 'type' => 4 , 'created_user_id' => $user_id]);
                                    $sub_categories = $this->sub_category_model->where('cat_id', $cat_id)->get_all();
                                    if(isset($sub_categories)){$sub_categories_data = []; $m = 0;foreach ($sub_categories as $key => $val) {
                                        if(in_array($val['id'], $subCatArry)){
                                            $sub_categories_data[$m ++] = [
                                                'list_id' => $insert_id,
                                                'sub_category_id' => $val['id'],
                                                'created_user_id' => $user_id
                                            ];
                                        }
                                    }}
                                    $this->db->where('list_id', $insert_id);
                                    $this->db->delete('vendors_sub_categories');
                                    $this->vendor_sub_category_model->insert($sub_categories_data); 
                                }else{
                                    $this->session->set_flashdata('upload_status', ["error" => "Error occured at row no($i)---Please check email and mobile"]);
                                } 
                            }
                            $successMessage = "Vendors successfully imported..!";
                            if($message!=""){
                                $successMessage = $message;
                            }
                            $this->session->set_flashdata('upload_status', ["success" =>  $successMessage]);
                        }else{
                            $this->session->set_flashdata('upload_status', ["error" => "Error occured at row no($i)"]);
                            $this->data['vendor'] = array('Executive' => $executive, 'Category' => $cat_id, 'Company' => $company, 'Locality' => $landmark, 'Address' => $address, 'PIN' => $pincode, 'Email' => $email, 'WhatsApp' => $whatsapp, 'Phone#1' => 'Phone #1', 'Phone#2' => 'Phone #2', 'Phone#3' => 'Phone #3', 'Phone#4' => 'Phone #4', 'Latitude' => $latitude, 'Longitude' => $longitude, 'Rating' => 'Rating', 'Reviews' => 'Reviews', 'Verified' => 'Verified', 'Paid' => 'Paid', 'Website' => 'Website');
                        }
                    }
                } else {
                    $this->session->set_flashdata('upload_status', ["error" => "Please import correct file, did not match excel sheet column"]);
                }
                $this->_render_page($this->template, $this->data);
            }
        }
    }
    
    public function sounds_like($name = NULL, $cat_id = NULL, $landmark = NULL){
        $sounds_like = '';
        if(! is_null($cat_id)){
            $cat_name = $this->category_model->fields('name')->where('id', $cat_id)->get();
            $sounds_like .= metaphone($cat_name['name']). ' ';
        }
        
        if (! is_null($name)) {foreach (explode(' ', $name) as $n){
            $sounds_like .= metaphone($n) . ' ';
        }}
        
        if (! is_null($landmark)){
            $sounds_like .= metaphone($landmark). ' ';
        }
        return $sounds_like;
    }
    
    // checkFileValidation
    public function checkFileValidation($string) {
        $file_mimes = array('text/x-comma-separated-values',
            'text/comma-separated-values',
            'application/octet-stream',
            'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            'application/excel',
            'application/vnd.msexcel',
            'text/plain',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
        if(isset($_FILES['fileURL']['name'])) {
            $arr_file = explode('.', $_FILES['fileURL']['name']);
            $extension = end($arr_file);
            if(($extension == 'xlsx' || $extension == 'xls' || $extension == 'csv') && in_array($_FILES['fileURL']['type'], $file_mimes)){
                return true;
            }else{
                $this->form_validation->set_message('checkFileValidation', 'Please choose correct file.');
                return false;
            }
        }else{
            $this->form_validation->set_message('checkFileValidation', 'Please choose a file.');
            return false;
        }
    }
    
    
    function add_permissions_to_user($user_id, $cat_id){
        $this->db->where('cat_id', $cat_id);
        $services = $this->db->get('categories_services')->result_array();
        $this->db->where('user_id', $user_id);
        $this->db->delete('users_permissions');
        foreach ($services as $service){
            $service_details = $this->db->select('permission_parent_ids')->where('id', $service['service_id'][0])->get('services')->result_array();
            $perms = explode(',', $service_details[0]['permission_parent_ids']);
            foreach ($perms as $perm){
                $child_permissions[] = $this->permission_model->where('parent_status', $perm)->as_array()->get_all();
                //print_array($child_permissions);
                foreach($child_permissions[0] as $child_permission){
                    $this->db->insert('users_permissions', ['user_id' => $user_id, 'perm_id' => $child_permission['id'], 'value' => 1]);
                }
                //$this->db->insert('users_permissions', ['user_id' => $user_id, 'perm_id' => $perm, 'value' => 1]);
            }
        }
    }
    
    public function details_by_vendor($type = 'r', $rowno=0){
        if($type == 'c'){
            $this->template = 'template/home/main';
            $this->data['title'] = 'Vendor data submission';
            $this->data['nav_type'] = 'details_by_vendor';
            $this->data['content'] = 'vendor/vendor/details_by_vendor';
            $this->data['categories'] = $this->category_model->get_all();
            unset($_SESSION['unique_id']);
            $this->session->set_userdata('unique_id', $this->input->post('id'));
            $this->form_validation->set_rules($this->details_by_vendor_model->rules);
            if($this->form_validation->run() == false) {
                $this->_render_page($this->template, $this->data);
            } else {
                $vendor_list = $this->vendor_list_model->where('unique_id', (! empty($this->input->post('id')))? $this->input->post('id') : $this->session->userdata('unique_id'))->get();
                if(! empty($this->input->post('id'))){
                    $vendor_details = $this->details_by_vendor_model->where('unique_id', $vendor_list['unique_id'])->get();
                    if($vendor_details){
                        $status = $this->details_by_vendor_model->update([
                            'unique_id' => $vendor_list['unique_id'],
                            'list_id' => $vendor_list['id'],
                            'customer_name' => $this->input->post('customer_name'),
                            'shop_name' => $this->input->post('shop_name'),
                            'landmark' => $this->input->post('landmark'),
                            'address' => $this->input->post('address'),
                            'phone' => $this->input->post('phone'),
                            'email' => $this->input->post('email'),
                            'cat_id' => $this->input->post('cat_id'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ], 'unique_id');

                    }else{
                     $vendor_list = $this->vendor_list_model->get(['unique_id' => $this->session->userdata('unique_id')]);

                     $status = $this->details_by_vendor_model->insert([
                         'unique_id' => $vendor_list['unique_id'],
                         'list_id' => $vendor_list['id'],
                         'cat_id' => $this->input->post('cat_id'),
                         'customer_name' => $this->input->post('customer_name'),
                         'shop_name' => $this->input->post('shop_name'),
                         'landmark' => $this->input->post('landmark'),
                         'address' => $this->input->post('address'),
                         'phone' => $this->input->post('phone'),
                         'email' => $this->input->post('email'),
                         'created_at' => date('Y-m-d H:i:s')
                    ]);

                    }
                    $this->vendor_list_model->update([
                        'unique_id' =>$vendor_list['unique_id'],
                        'customer_name' => $this->input->post('customer_name'),
                        'landmark' => $this->input->post('landmark'),
                        'address' => $this->input->post('address'),
                        'name' => $this->input->post('shop_name'),
                        'email' => $this->input->post('email'),
                    ], 'unique_id');
                    
                    $this->contact_model->update([
                        'std_code' => '+91',
                        'number' => $this->input->post('phone'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ], ['list_id' => $vendor_list['id'], 'type' => 1]);
                    $this->user_model->update([
                        'id' => $vendor_list['vendor_user_id'],
                        'first_name' => $this->input->post('customer_name'),
                        'phone' => $this->input->post('phone'),
                        'email' => $this->input->post('email'),
                        'username' => $this->input->post('email')
                    ], 'id');
                    $this->session->set_flashdata('success', 'Successfully sumitted..! We will get back to you. ');
                    redirect('details_by_vendor/c/0');
                }else{
                    $this->session->set_flashdata('failed', 'Sorry..! No reference found.');
                    redirect('details_by_vendor/c/0');
                }
            }
        }elseif ($type == 'r'){
            
            $this->template = 'template/admin/main';
            $this->data['title'] = 'Vendor data submission';
            $this->data['nav_type'] = 'details_by_vendor';
            $this->data['content'] = 'vendor/vendor/vendor_details_submission';
            $this->data['categories'] = $this->category_model->get_all();
            
            // Search text
            $search_text = $till_date =  $mobile_text = ""; $noofrows = 10;
            if($this->input->post('submit') != NULL ){
                $search_text = $this->input->post('q');
                $till_date = $this->input->post('till_date');
                $mobile_text = $this->input->post('mobile');
                $noofrows = $this->input->post('noofrows');
     $this->session->set_userdata(array("q"=>$search_text, 'till_date' => $till_date, 'mobile' => $mobile_text, 'noofrows' => $noofrows));
            }else{
                if($this->session->userdata('q') != NULL || $this->session->userdata('till_date') != NULL || $this->session->userdata('mobile') != NULL || $noofrows != NULL){
                    $search_text = $this->session->userdata('q');
                    $till_date = $this->session->userdata('till_date');
                    $mobile_text = $this->session->userdata('mobile');
                    $noofrows = $this->session->userdata('noofrows');
                }
            }

            $rowperpage = $noofrows? $noofrows: 2;

            if($rowno != 0){

                $rowno = ($rowno-1) * $rowperpage;
            }
            
            $allcount = $this->details_by_vendor_model->vendor_count($search_text, $till_date, $mobile_text);
            $users_record = $this->details_by_vendor_model->get_vendors($rowperpage, $rowno, $search_text, $till_date, $mobile_text);
            
            $config['full_tag_open'] = "<ul class='pagination'>";
            $config['full_tag_close'] ="</ul>";
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
            $config['base_url'] = base_url().'vendors_filter';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $rowperpage;
            
             
            $this->pagination->initialize($config);
            
            $this->data['pagination'] = $this->pagination->create_links();
            $this->data['vendors'] = $users_record;

            $this->data['contacts'] = $this->contact_model->where(['list_id' => implode(',',array_column($users_record, 'list_id')), 'type' => 1] )->get_all();
            //print_array($this->data['vendors']);
            $this->data['row'] = $rowno;
            $this->data['q'] = $search_text;
            $this->data['till_date'] = $till_date;
            $this->data['mobile'] = $mobile_text;
            $this->data['noofrows'] = $rowperpage;
            $this->_render_page($this->template, $this->data);
        }
    }

    public function payouts($rowno = 0){
$search_text = ""; $noofrows = 10;	        
        if($this->input->post('submit') != NULL ){
            $search_text = $this->input->post('q'); 
			$noofrows = $this->input->post('noofrows');
            $this->session->set_userdata(array("q"=>$search_text, 'noofrows' => $noofrows));
        }else{
            if($this->session->userdata('q') != NULL ||  $noofrows != NULL){
                $search_text = $this->session->userdata('q');
                $noofrows = $this->session->userdata('noofrows');
            }
        }
		$rowperpage = $noofrows? $noofrows: 10;
		if($rowno != 0){
            $rowno = ($rowno-1) * $rowperpage;
        }
		$this->data['title'] = 'Vendor Payment Distrubution';
        $this->data['content'] = 'vendor/vendor/payment_distribution';
        $this->data['nav_type'] = 'vendor_payment_distribution';
        $this->data['vendor_payouts'] = $this->user_account_model->prepareVendorPayouts_data($rowperpage, $rowno, $search_text);
        $this->data['total_payout'] = $this->user_account_model->fetcTotalPayouts($search_text);
		

        
         
        $allcount = $this->user_account_model->fetcTotalPayouts_count($search_text);
		
        $users_record = $this->user_account_model->prepareVendorPayouts_data($rowperpage, $rowno, $search_text);

         
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] ="</ul>";
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
        $config['base_url'] = base_url().'vendor/payouts';
		$config['first_url'] = base_url().'vendor/payouts/0';;

        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $allcount;
        $config['per_page'] = $rowperpage;
        $this->pagination->initialize($config);
        
        $this->data['pagination'] = $this->pagination->create_links();
        $this->data['vendor_payout'] = $users_record;
      $arr =  array_column($users_record, 'id');
      
        $this->data['row'] = $rowno;
        $this->data['q'] = $search_text;
        $this->data['noofrows'] = $rowperpage;
        $this->_render_page($this->template, $this->data);
        
    }

    public function process_payout(){
        $payouts = $this->user_account_model->prepareVendorPayouts();
        foreach($payouts as $payout){
            if((float) $payout['wallet']>0 && $payout['external_id']){
                $settlementAmount = 100; //((float) $payout['wallet'])*100;
                $settlementAmountInBucks = $settlementAmount / 100;
                $txn_id = 'DBT-' . generate_trasaction_no(10);
                $this->user_model->payment_update($payout['id'], $settlementAmountInBucks, 'DEBIT', 'wallet', $txn_id, null, "Bank Payout");
                $externalPayout = $this->payThroughRazorpay($settlementAmount, $payout['external_id']);
                $this->payout_model->insert([
                    'user_id'=> $payout['id'],
                    'user_type'=> 1,
                    'vendor_bank_id'=> $payout['vendor_bank_id'],
                    'external_id'=> $externalPayout->id,
                    'payment_value'=> $settlementAmountInBucks,
                    'status'=> 1
                ]);
            }
        }
        redirect('vendor/payouts');
    }

    public function payThroughRazorpay($amount, $fundAccount){
        try{
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
            curl_setopt($cURLConnection, CURLOPT_USERPWD, $razorPayInfo['key'].":".$razorPayInfo["secret"]);
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, json_encode($accountDetails));
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            ));
            $apiResponse = curl_exec($cURLConnection);
            curl_close($cURLConnection);
            $jsonArrayResponse = json_decode($apiResponse);
            return $jsonArrayResponse;
        }catch(Exception $ex){
            print_r($ex);exit;
        }
    }

/*Added by manoj*/
	public function payout_detials($type = 'r', $rowno = 0){
		
		if ($type == 'details') {
				$v_did=$this->input->get('vendor_user_id');
$this->data['v_did']=$v_did;
				$v_id=base64_decode(base64_decode($this->input->get('vendor_user_id')));
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
        $url = base_url() . '/vendor/payout_detials/details/0?vendor_user_id='.$v_did;
        $this->pagination_config($allcount, $rowperpage, $url);

        $this->data['title'] = 'Vendor Payout Details';
        $this->data['content'] = 'vendor/vendor/payout_details';
        //$this->data['nav_type'] = 'payment_reports';
        // print_array($this->data);
        $this->_render_page($this->template, $this->data);
				
		}
if ($type == 'edit') {

            $this->data['title'] = 'Orders';
            $this->data['content'] = 'vendor/vendor/order_details';
            
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

	public function filter_config()
    {
        $search_text = "";
        $noofrows = 10;
		if ($this->input->post('start_date') != NULL) {
            $search_text = $this->input->post('q');
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $noofrows = $this->input->post('noofrows');
            $this->session->set_flashdata(array(
                "q" => $search_text,
                'noofrows' => $noofrows,
                'start_date' => $start_date,
                'end_date' => $end_date
            ));
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