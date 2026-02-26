<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';
use Firebase\JWT\JWT;

class Master extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('vendor_list_model');
        $this->load->model('news_model');
        $this->load->model('users_address_model');
        $this->load->model('location_model');
        $this->load->model('setting_model');
        $this->load->model('user_service_model');
        $this->load->model('vendor_banner_model');
        $this->load->model('vendor_service_model');
        $this->load->model('user_model');
        $this->load->model('setting_model');
        $this->load->model('hosp_speciality_model');
        $this->load->model('hosp_doctor_model');
        $this->load->model('od_category_model');
        $this->load->model('od_service_model');
        $this->load->model('user_doc_model');
        $this->load->model('user_account_model');
        $this->load->model('Notifications_model');
    }

    /**
     * To get list of vendor depends upon category, search & near by location
     *
     * @author Mehar
     * @param integer $limit
     * @param integer $offset
     * @param integer $cat_id
     */
    public function vendor_list_get($limit = 10, $offset = 0)
    {
		$que = str_replace('%20', ' ', $this->input->get('q'));
		
        $data = $this->vendor_list_model->all($limit, $offset, (isset($_GET['cat_id'])) ? $this->input->get('cat_id') : NUll, (isset($_GET['sub_cat_id'])) ? $this->input->get('sub_cat_id') : NUll, (isset($que)) ? $que : NUll, (isset($_GET['latitude'])) ? $this->input->get('latitude') : NUll, (isset($_GET['longitude'])) ? $this->input->get('longitude') : NUll, (isset($_GET['brand_id'])) ? $this->input->get('brand_id') : NUll);
		
		if (! empty($data['result'])) {
            foreach ($data['result'] as $d) {
                $d->services = $this->vendor_service_model->with_service('fields: id, name, desc, deleted_at')->fields('service_id')->where('list_id', $d->id)->get_all();
                $d->is_having_lead = FALSE;
                if(empty($d->services)){
                    $d->is_having_lead = FALSE;
                }elseif (in_array($this->config->item('leads_service_id', 'ion_auth'), array_column($d->services, 'service_id'))){
                    $d->is_having_lead = TRUE;
                }
                $d->avg_rating = $this->db->query('SELECT AVG(rating) as avg_rating FROM `vendor_ratings` WHERE list_id ='.$d->id)->row()->avg_rating;
                $d->image = base_url() . 'uploads/list_cover_image/list_cover_' . $d->id . '.jpg'.'?'.time();
                $d->actual_distance = $d->distance;
                $d->gmap_distance = getGoogleMapDistance((isset($_GET['latitude'])) ? $this->input->get('latitude') : NULL, (isset($_GET['longitude'])) ? $this->input->get('longitude') : NUll, $d->lat, $d->lng);
                $d->distance = $d->gmap_distance;
            }
        }else {
            $data = NULL;
        }
        $this->set_response_simple((empty($data['result'])) ? NULL : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * User address
     *
     * To Manage address
     *
     * @author Trupti
     * @param string $type
     */
    public function user_address_post($type = 'r')
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if ($type == 'c') {
            $this->form_validation->set_rules($this->users_address_model->rules);
            if ($this->form_validation->run() == false) {
                $this->set_response_simple(validation_errors(), 'Validation Error', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $v = $this->location_model->where('latitude', $this->input->post('latitude'))
                    ->where('longitude', $this->input->post('longitude'))
                    ->get();
                if ($v != '') {
                    $l_id = $v['id'];
                } else {
                    $l_id = $this->location_model->insert([
                        'latitude' => $this->input->post('latitude'),
                        'longitude' => $this->input->post('longitude'),
                        'address' => $this->input->post('address')
                    ]);
                }

                $id = $this->users_address_model->insert([
                    'user_id' => $token_data->id,
                    'name' => $this->input->post('name'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'address' => $this->input->post('address'),
                    'location_id' => $l_id,
                    'status' => 1
                ]);
                $this->set_response_simple($id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
            }
        } elseif ($type == 'r') {
            $lat = $this->input->post('latitude');
            $long = $this->input->post('longitude');
            if(! is_null($lat) && ! is_null($long)){
                $locations = $this->db->query("SELECT id, ( 3959 * acos( cos( radians($lat) ) * cos( radians( locations.latitude ) ) * cos( radians( locations.longitude ) - radians($long) ) + sin( radians($lat) ) * sin(radians(locations.latitude)) ) ) AS distance FROM locations HAVING distance < 3.16 ORDER BY distance")->result_array();
                $location_ids = (empty(array_column($locations, 'id')))? 0: implode(',', array_column($locations, 'id'));
            }

            $data = $this->db->query("SELECT `users_address`.`id`, `users_address`. `created_user_id`, `users_address`. `name`, `users_address`. `phone`, `users_address`. `email`, `users_address`. `address`, `users_address`. `location_id` FROM `users_address` WHERE `users_address`.`created_user_id` = ".$token_data->id." AND users_address.deleted_at IS NULL AND `users_address`.`location_id` IN (".$location_ids.")")->result_array();
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } elseif ($type == 's') {
            $data = $this->users_address_model->fields('id, user_id, name, phone, email, address, location_id')->get('id', $this->input->post('id'));
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->users_address_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), 'Validation Error', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $v = $this->location_model->where('latitude', $this->input->post('latitude'))
                    ->where('longitude', $this->input->post('longitude'))
                    ->get();
                    
                if ($v != '') {
                    $l_id = $v['id'];
                } else {
                    $l_id = $this->location_model->insert([
                        'latitude' => $this->input->post('latitude'),
                        'longitude' => $this->input->post('longitude'),
                        'address' => $this->input->post('address')
                    ]);
                }
                $ll = $this->users_address_model->update([
                    'id' => $this->input->post('id'),
                    'user_id' => $token_data->id,
                    'name' => $this->input->post('name'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'address' => $this->input->post('address'),
                    'location_id' => $l_id
                ], 'id');
                $this->set_response_simple($ll, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
            }
        } elseif ($type == 'd') {
            $ll = $this->users_address_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->set_response_simple($ll, 'Deleted..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To get the individual vendor details
     *
     * @author Mehar
     * @param number $target
     */
    public function vendor_get($target = 1)
    {
		
    $data = $this->vendor_list_model->fields('id, vendor_user_id, customer_name, business_name, business_description, name, email, unique_id, location_id, executive_id, constituency_id, category_id, desc, no_of_banners, address, landmark, pincode, sounds_like, owner_name, gst_number, labour_certificate_number, fssai_number, secondary_contact, whats_app_no, created_at, updated_at, deleted_at, status, availability, executive_user_id')
            ->with_users('fields: phone,email')
            ->with_timings('fields: list_id, start_time, end_time')
            ->with_location('fields: id, address, latitude, longitude')
            ->with_category('fields: id, name, status')
            ->with_sub_categories('fields: id, name, status')
            ->with_constituency('fields: id, name, state_id, district_id')
            ->with_contacts('fields: id, std_code, number, type')
            ->with_links('fields: id, url, type')
            ->with_amenities('fields: id, name')
            ->with_ratings('fields: id, user_id, rating, review')
            ->with_services('fields: id, name')
            ->with_specialities('fields: id, name, desc')
            ->with_on_demand_categories('fields: id, name, desc')
            ->with_holidays('fields: id')
            ->where('id', $target)
            ->get(); 
            $shop_by_categoris = $this->db->query("SELECT sc.id, sc.name, sc.type, sc.desc FROM shop_by_categories as sbc join sub_categories as sc on sc.id = sbc.sub_cat_id where vendor_id = ".$data['vendor_user_id']." and sub_cat_id not in(select sub_cat_id from vendor_in_active_shop_by_categories where vendor_id = ".$data['vendor_user_id']." ) and sc.status = 1 and sc.deleted_at is null")->result_array();
            $data['sub_categories'] = (! empty($shop_by_categoris)) ? $shop_by_categoris : NULL;
            $data['specialities'] = is_array($data['specialities'])?array_values($data['specialities']) : NULL;
            $data['on_demand_categories'] = is_array($data['on_demand_categories'])?array_values($data['on_demand_categories']) : NULL;
            $data['services'] = is_array($data['services'])? array_values($data['services']) : NULL;
            $data['shop_by_categories'] = $this->db->query("SELECT sc.id, sc.cat_id, sc.type, sc.name, sc.desc, sc.status, sbc.vendor_id FROM `shop_by_categories` AS sbc JOIN sub_categories AS sc ON sbc.sub_cat_id = sc.id WHERE sbc.`vendor_id` IN (1,".$data['vendor_user_id'].") AND sc.type = 2 AND sc.status = 1 AND sc.deleted_at is null AND sbc.cat_id=".$data['category_id']." AND sbc.sub_cat_id NOT IN(SELECT sub_cat_id FROM `vendor_in_active_shop_by_categories` WHERE vendor_id = ".$data['vendor_user_id'].")")->result_array();
            $data['category']['coming_soon_image'] = base_url(). 'uploads/coming_soon_image/coming_soon_'.$data['category']['id'].'.jpg'.'?'.time();
            $data['primary_contact'] = $data['users']['phone'];
        $data['user_services'] = $this->user_service_model->order_by('id', 'DESC')
            ->where('created_user_id', $data['vendor_user_id'])
            ->get_all();
        if ($data != FALSE) {
            $vendor_banners = $this->vendor_banner_model->where('list_id', $data['id'])->get_all();
            $data['banners'] = [];
            if ($vendor_banners) {
                foreach ($vendor_banners as $key => $banner) {
                    $data['banners'][$key] = base_url() . "uploads/list_banner_image/list_banner_" . $banner['id'] . ".jpg".'?'.time();
                }
            }
            if (! empty($data['sub_categories'])) {
                for ($i = 0; $i < count($data['sub_categories']); $i ++) {
                    $data['sub_categories'][$i]['image'] = base_url() . 'uploads/sub_category_image/sub_category_' . $data['sub_categories'][$i]['id'] . '.jpg'.'?'.time();
                }
            }
            if (! empty($data['shop_by_categories'])) {
                for ($i = 0; $i < count($data['shop_by_categories']); $i ++) {
                    $data['shop_by_categories'][$i]['image'] = base_url() . 'uploads/sub_category_image/sub_category_' . $data['shop_by_categories'][$i]['id'] . '.jpg'.'?'.time();
                }
            }
            if (! empty($data['services'])) {
                for ($i = 0; $i < count($data['services']); $i ++) {
                    $data['services'][$i]['image'] = base_url() . 'uploads/service_image/service_' . $data['services'][$i]['id'] . '.jpg'.'?'.time();
                }
            }
            if (! empty($data['specialities'])) {
                for ($i = 0; $i < count($data['specialities']); $i ++) {
                    $data['specialities'][$i]['image'] = base_url() . 'uploads/speciality_image/speciality_' . $data['specialities'][$i]['id'] . '.jpg'.'?'.time();
                }
            }
            if (! empty($data['on_demand_categories'])) {
                for ($i = 0; $i < count($data['on_demand_categories']); $i ++) {
                    $data['on_demand_categories'][$i]['image'] = base_url() . 'uploads/od_category_image/od_category_' . $data['on_demand_categories'][$i]['id'] . '.jpg'.'?'.time();
                }
            }
            $avg_rating = 0;
            if (! empty($data['ratings'])) {
                $ratings_array = array_column($data['ratings'], 'rating');
                $avg_rating = array_sum($ratings_array)/count($ratings_array);
                for ($i = 0; $i < count($data['ratings']); $i ++) {
                    $data['ratings'][$i]['user'] = $this->user_model->fields('id, first_name, unique_id')->get($data['ratings'][$i]['user_id']);
                }
            }
            $data['avg_rating'] = $avg_rating;
            $data['cover'] = base_url() . "uploads/list_cover_image/list_cover_" . $data['id'] . ".jpg".'?'.time();
            $field_where="(`desc` = 'app_ord_label' OR `desc` = 'app_ord_quantity' OR `desc` = 'app_ord_address')";
            $data['fields']=$this->db->where($field_where)->select('acc_id,name,desc,field_status')->get_where('manage_account_names',array('status'=>1,'category_id'=>$data['category_id']))->result();
        }
        $this->set_response_simple($data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * To get the news
     *
     * @author Mehar
     * @param number $limit,offset
     */
    public function news_get($limit = 10, $offset = 0)
    {
        $data = $this->news_model->all($limit, $offset);
        if (! empty($data['result'])) {
            foreach ($data['result'] as $d) {
                $d->image = base_url() . 'uploads/news_image/news_' . $d->id . '.jpg';
            }
        }
        $this->set_response_simple((empty($data['result'])) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * To get the User Dettails
     *
     * @author Mahesh
     *        
     */
    public function user_details_get()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $this->load->model('user_model');
        $result = $this->user_model->order_by('id', 'DESC')
            ->fields('id,unique_id,first_name,last_name,email,phone, wallet')
            ->with_groups('fields:name,id')
            ->where('id', $token_data->id)
            ->get();
        $this->set_response_simple(($result == FALSE) ? FALSE : $result, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }
    
    /**
     * @desc Manage profile
     * 
     * @author Mehar
     */
    public function profile_post($type = 'r'){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $this->user_model->user_id = $token_data->id;
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if($type == 'r'){
            $data = $this->user_model
            ->with_groups('fields: id, name')->with_location('fields: id, latitude, longitude, address')->where('id', $token_data->id)->get();
            if(! empty($data)){
                $data['is_admin'] = $this->ion_auth->in_group('admin', $token_data->id);
                $data['profile_image'] = base_url() . 'uploads/profile_image/profile_' . $data['id'] . '.jpg';
                $user_docs = $this->user_doc_model->where('created_user_id', $data['id'])->get();

                $user_wallet = $this->user_account_model->fields('wallet, floating_wallet')->where('user_id', $token_data->id)->get();
                if(!empty($user_wallet))
                {
                    $data['wallet'] =(empty($user_wallet['wallet']))? 0: $user_wallet['wallet'];
                    $data['floating_wallet'] =(empty($user_wallet['floating_wallet']))? 0: $user_wallet['floating_wallet'];
                }
                
                $data['aadhar_front']['image'] =  base_url() . 'uploads/aadhar_card_image/aadhar_card_front_' . $data['unique_id'] . '.jpg';
                $data['aadhar_back']['image'] =  base_url() . 'uploads/aadhar_card_image/aadhar_card_back_' . $data['unique_id'] . '.jpg';
                $data['aadhar']['message'] =  (empty($user_docs['adhar_card_message']))? NULL: $user_docs['adhar_card_message'];
                $data['aadhar']['status'] =  (empty($user_docs['adhar_card_status']))? NULL: $user_docs['adhar_card_status'];
                
                $data['pan_card_front']['image'] =  base_url() . 'uploads/pan_card_image/pan_card_front_' . $data['unique_id'] . '.jpg';
                $data['pan_card_back']['image'] =  base_url() . 'uploads/pan_card_image/pan_card_back_' . $data['unique_id'] . '.jpg';
                $data['pan_card']['message'] = (empty($user_docs['pan_card_message']))? NULL:  $user_docs['pan_card_message'];
                $data['pan_card']['status'] =  (empty($user_docs['pan_card_status']))? NULL: $user_docs['pan_card_status'];
                
                $data['cancel_cheque']['image'] =  base_url() . 'uploads/cancellation_cheque_image/cancellation_cheque_' . $data['unique_id'] . '.jpg';
                $data['cancel_cheque']['message'] =  (empty($user_docs['cancel_cheque_message']))? NULL: $user_docs['cancel_cheque_message'];
                $data['cancel_cheque']['status'] =  (empty($user_docs['cancel_cheque_status']))? NULL: $user_docs['cancel_cheque_status'];
                
                $data['driving_licence_front']['image'] =  base_url() . 'uploads/driving_license_image/driving_license_front_' . $data['unique_id'] . '.jpg';
                $data['driving_licence_back']['image'] =  base_url() . 'uploads/driving_license_image/driving_license_back_' . $data['unique_id'] . '.jpg';
                $data['driving_licence']['message'] =  (empty($user_docs['driving_licence_message']))? NULL: $user_docs['driving_licence_message'];
                $data['driving_licence']['status'] =  (empty($user_docs['driving_licence_status']))? NULL: $user_docs['driving_licence_status'];
                
                $data['pass_book']['image'] =  base_url() . 'uploads/bank_passbook_image/bank_passbook_' . $data['unique_id'] . '.jpg';
                $data['pass_book']['message'] =  (empty($user_docs['pass_book_message']))? NULL: $user_docs['pass_book_message'];
                $data['pass_book']['status'] =  (empty($user_docs['pass_book_status']))? NULL: $user_docs['pass_book_status'];
                
                $data['rc_front']['image'] =  base_url() . 'uploads/rc_image/rc_front_' . $data['unique_id'] . '.jpg';
                $data['rc_back']['image'] =  base_url() . 'uploads/rc_image/rc_back_' . $data['unique_id'] . '.jpg';
                $data['rc']['message'] =  (empty($user_docs['rc_message']))? NULL: $user_docs['rc_message'];
                $data['rc']['status'] =  (empty($user_docs['rc_status']))? NULL: $user_docs['rc_status'];
                
                $data['vehicle_front']['image'] =  base_url() . 'uploads/vehicle_image/vehicle_front_' . $data['unique_id'] . '.jpg';
                $data['vehicle_back']['image'] =  base_url() . 'uploads/vehicle_image/vehicle_back_' . $data['unique_id'] . '.jpg';
                $data['vehicle']['message'] =  (empty($user_docs['vehicle_images_message']))? NULL: $user_docs['vehicle_images_message'];
                $data['vehicle']['status'] =  (empty($user_docs['vehicle_images_status']))? NULL: $user_docs['vehicle_images_status'];
                
                $data['vehicle_insurance_front_']['image'] =  base_url() . 'uploads/vehicle_insurance_image/vehicle_insurance_front_' . $data['unique_id'] . '.jpg';
                $data['vehicle_insurance_back_']['image'] =  base_url() . 'uploads/vehicle_insurance_image/vehicle_insurance_back_' . $data['unique_id'] . '.jpg';
                $data['insurance']['message'] =  (empty($user_docs['insurance_images_message']))? NULL: $user_docs['insurance_images_message'];
                $data['insurance']['status'] =  (empty($user_docs['insurance_images_status']))? NULL: $user_docs['insurance_images_status'];
                
            }
            
            $this->set_response_simple(($data == FALSE) ? NULL : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }elseif($type == 'u'){

            $this->form_validation->set_rules($this->user_model->rules['profile']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_OK, FALSE);
            } else {                
                $is_updated = $this->user_model->update([
                    'id' => $token_data->id,
                    'first_name' => empty($this->input->post('first_name'))? NULL : $this->input->post('first_name'),
                    'last_name' => empty($this->input->post('last_name')) ? NULL : $this->input->post('last_name'),
                    'email' => empty($this->input->post('email')) ? NULL : $this->input->post('email'),
                    'phone' => empty($this->input->post('mobile')) ? NULL : $this->input->post('mobile'),
                    "vehicle_number" => empty($this->input->post('vehicle_number'))? NULL :$this->input->post('vehicle_number'),
                    "vehicle_insurance_number" => empty($this->input->post('vehicle_insurance_number'))? NULL : $this->input->post('vehicle_insurance_number'),
                    "vehicle_type_id" => empty($this->input->post('vehicle_type_id')) ? NULL : $this->input->post('vehicle_type_id') ,
                    "aadhar_number" => empty($this->input->post('aadhar_number'))? NULL :$this->input->post('aadhar_number'),
                    "pan_card_number" => empty($this->input->post('pan_card_number'))? NULL :$this->input->post('pan_card_number'),
                    "driving_license_number" => empty($this->input->post('driving_license_number'))? NULL :$this->input->post('driving_license_number'),
                    "permanent_address" => empty($this->input->post('permanent_address')) ? NULL : $this->input->post('permanent_address'),
                    "state" => empty($this->input->post('state')) ? NULL : $this->input->post('state'),
                    "district" => empty($this->input->post('district')) ? NULL : $this->input->post('district'),
                    "constituency" => empty($this->input->post('constituency')) ? NULL : $this->input->post('constituency'),
                    "passcode" => empty($this->input->post('passcode'))? NULL :$this->input->post('passcode'),
                    "pincode" => $this->input->post('pincode'),
                ], 'id');
                
                if(! empty($this->input->post('latitude')) && ! empty($this->input->post('longitude')))
                {
                    $is_location_exist = $this->location_model->where(['latitude' => $this->input->post('latitude'), 'longitude' => $this->input->post('longitude')])->get();

                    if(empty($is_location_exist)){
                        $location_id = $this->location_model->insert([
                            'address' => $this->input->post('geo_lcoation_address'),
                            'latitude' => $this->input->post('latitude'),
                            'longitude' => $this->input->post('longitude'),
                        ]);
                    }else{
                        $location_id = $is_location_exist['id'];
                    }
                    
                    $this->user_model->update([
                        'id' => $token_data->id,
                        'location_id' => $location_id
                    ], 'id');
                }
                
                
                if(! empty($this->input->post('profile_image'))){

                    if (!file_exists('./uploads/profile_image/')) {
                        mkdir('./uploads/profile_image/', 0777, true);
                    }
                    if (file_exists("./uploads/profile_image/profile_" . $this->input->post('unique_id') . ".jpg")) {
                        unlink('./uploads/' . 'profile' . '_image/' . 'profile' . '_' . $this->input->post('unique_id') . '.jpg');
                        file_put_contents("./uploads/profile_image/profile_".$this->input->post('unique_id').".jpg", base64_decode($this->input->post('profile_image')));
                   
                    }else{
                    file_put_contents("./uploads/profile_image/profile_".$this->input->post('unique_id').".jpg", base64_decode($this->input->post('profile_image')));
                    }
                }
                
                if(! empty($this->input->post('aadhar_card_image_front'))){

                    if (! file_exists('./uploads/' . 'aadhar_card' . '_image/')) {
                        mkdir('./uploads/' . 'aadhar_card' . '_image/', 0777, true);
                    }
                    if (file_exists("./uploads/aadhar_card_image/aadhar_card_front_" . $this->input->post('unique_id') . ".jpg")) {
                        unlink('./uploads/' . 'aadhar_card' . '_image/' . 'aadhar_card_front' . '_' . $this->input->post('unique_id') . '.jpg');
                        file_put_contents("./uploads/aadhar_card_image/aadhar_card_front_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('aadhar_card_image_front')));
                    }else{
                    file_put_contents("./uploads/aadhar_card_image/aadhar_card_front_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('aadhar_card_image_front')));
                    }
                }

                if(! empty($this->input->post('aadhar_card_image_back'))){

                    if (! file_exists('./uploads/' . 'aadhar_card' . '_image/')) {
                        mkdir('./uploads/' . 'aadhar_card' . '_image/', 0777, true);
                    }
                    if (file_exists("./uploads/aadhar_card_image/aadhar_card_back_" . $this->input->post('unique_id') . ".jpg")) {
                        unlink('./uploads/' . 'aadhar_card' . '_image/' . 'aadhar_card_back' . '_' . $this->input->post('unique_id') . '.jpg');
                        file_put_contents("./uploads/aadhar_card_image/aadhar_card_back_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('aadhar_card_image_back')));
                    }else{
                    file_put_contents("./uploads/aadhar_card_image/aadhar_card_back_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('aadhar_card_image_back')));
                    }
                }
                
                if(! empty($this->input->post('pan_card_image_front')))
                {
                    if (! file_exists('./uploads/' . 'pan_card' . '_image/')) {
                        mkdir('./uploads/' . 'pan_card' . '_image/', 0777, true);
                    }
                    if (file_exists("./uploads/pan_card_image/pan_card_front_" . $this->input->post('unique_id') . ".jpg")) {
                        unlink('./uploads/' . 'pan_card' . '_image/' . 'pan_card_front' . '_' . $this->input->post('unique_id') . '.jpg');
                        file_put_contents("./uploads/pan_card_image/pan_card_front_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('pan_card_image_front')));
                    }else{
                    file_put_contents("./uploads/pan_card_image/pan_card_front_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('pan_card_image_front')));
                    }
                }

                if(! empty($this->input->post('pan_card_image_back')))
                {
                    if (! file_exists('./uploads/' . 'pan_card' . '_image/')) {
                        mkdir('./uploads/' . 'pan_card' . '_image/', 0777, true);
                    }
                    if (file_exists("./uploads/pan_card_image/pan_card_back_" . $this->input->post('unique_id') . ".jpg")) {
                        unlink('./uploads/' . 'pan_card' . '_image/' . 'pan_card_back' . '_' . $this->input->post('unique_id') . '.jpg');
                        file_put_contents("./uploads/pan_card_image/pan_card_back_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('pan_card_image_back')));
                    }else{
                    file_put_contents("./uploads/pan_card_image/pan_card_back_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('pan_card_image_back')));
                    }
                }
                
                if(! empty($this->input->post('bank_passbook_image')))
                {
                    if (! file_exists('./uploads/' . 'bank_passbook' . '_image/')) {
                        mkdir('./uploads/' . 'bank_passbook' . '_image/', 0777, true);
                    }
                    if (file_exists("./uploads/bank_passbook_image/bank_passbook_" . $this->input->post('unique_id') . ".jpg")) {
                        unlink('./uploads/' . 'bank_passbook' . '_image/' . 'bank_passbook' . '_' . $this->input->post('unique_id') . '.jpg');
                        file_put_contents("./uploads/bank_passbook_image/bank_passbook_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('bank_passbook_image')));
                    }else{
                    file_put_contents("./uploads/bank_passbook_image/bank_passbook_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('bank_passbook_image')));
                    }
                }
                
                if(! empty($this->input->post('cancellation_cheque_image'))){

                    if (! file_exists('./uploads/' . 'cancellation_cheque' . '_image/')) {
                        mkdir('./uploads/' . 'cancellation_cheque' . '_image/', 0777, true);
                    }
                    if (file_exists("./uploads/cancellation_cheque_image/cancellation_cheque_" . $this->input->post('unique_id') . ".jpg")) {
                        unlink('./uploads/' . 'cancellation_cheque' . '_image/' . 'cancellation_cheque' . '_' . $this->input->post('unique_id') . '.jpg');
                        file_put_contents("./uploads/cancellation_cheque_image/cancellation_cheque_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('cancellation_cheque_image')));
                    }else{
                    file_put_contents("./uploads/cancellation_cheque_image/cancellation_cheque_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('cancellation_cheque_image')));
                    }
                }
                
                if(! empty($this->input->post('rc_image_front'))){
                    if (! file_exists('./uploads/' . 'rc' . '_image/')) {
                        mkdir('./uploads/' . 'rc' . '_image/', 0777, true);
                    }
                    if (file_exists("./uploads/rc_image/rc_front_" . $this->input->post('unique_id') . ".jpg")) {
                        unlink('./uploads/' . 'rc' . '_image/' . 'rc_front' . '_' . $this->input->post('unique_id') . '.jpg');
                        file_put_contents("./uploads/rc_image/rc_front_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('rc_image_front')));
                    }else{
                    file_put_contents("./uploads/rc_image/rc_front_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('rc_image_front')));
                    }
                }

                if(! empty($this->input->post('rc_image_back'))){
                    if (! file_exists('./uploads/' . 'rc' . '_image/')) {
                        mkdir('./uploads/' . 'rc' . '_image/', 0777, true);
                    }
                    if (file_exists("./uploads/rc_image/rc_back_" . $this->input->post('unique_id') . ".jpg")) {
                        unlink('./uploads/' . 'rc' . '_image/' . 'rc_back' . '_' . $this->input->post('unique_id') . '.jpg');
                        file_put_contents("./uploads/rc_image/rc_back_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('rc_image_back')));
                    }else{
                    file_put_contents("./uploads/rc_image/rc_back_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('rc_image_back')));
                    }
                }
                
                if(! empty($this->input->post('driving_license_image_front'))){
                    if (! file_exists('./uploads/' . 'driving_license' . '_image/')) {
                        mkdir('./uploads/' . 'driving_license' . '_image/', 0777, true);
                    }
                    if (file_exists("./uploads/driving_license_image/driving_license_front_" . $this->input->post('unique_id') . ".jpg")) {
                        unlink('./uploads/' . 'driving_license' . '_image/' . 'driving_license_front' . '_' . $this->input->post('unique_id') . '.jpg');
                        file_put_contents("./uploads/driving_license_image/driving_license_front_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('driving_license_image_front')));
                    }else{
                    file_put_contents("./uploads/driving_license_image/driving_license_front_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('driving_license_image_front')));
                    }
                }

                if(! empty($this->input->post('driving_license_image_back'))){
                    if (! file_exists('./uploads/' . 'driving_license' . '_image/')) {
                        mkdir('./uploads/' . 'driving_license' . '_image/', 0777, true);
                    }
                    if (file_exists("./uploads/driving_license_image/driving_license_back_" . $this->input->post('unique_id') . ".jpg")) {
                        unlink('./uploads/' . 'driving_license' . '_image/' . 'driving_license_back' . '_' . $this->input->post('unique_id') . '.jpg');
                        file_put_contents("./uploads/driving_license_image/driving_license_back_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('driving_license_image_back')));
                    }else{
                    file_put_contents("./uploads/driving_license_image/driving_license_back_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('driving_license_image_back')));
                    }
                }

                if(! empty($this->input->post('vehicle_image_front'))){
                    if (! file_exists('./uploads/' . 'vehicle' . '_image/')) {
                        mkdir('./uploads/' . 'vehicle' . '_image/', 0777, true);
                    }
                    if (file_exists("./uploads/vehicle_image/vehicle_front_" . $this->input->post('unique_id') . ".jpg")) {
                        unlink('./uploads/' . 'vehicle' . '_image/' . 'vehicle_front' . '_' . $this->input->post('unique_id') . '.jpg');
                        file_put_contents("./uploads/vehicle_image/vehicle_front_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('vehicle_image_front')));
                    }else{
                    file_put_contents("./uploads/vehicle_image/vehicle_front_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('vehicle_image_front')));
                    }
                }

                if(! empty($this->input->post('vehicle_image_back'))){
                    if (! file_exists('./uploads/' . 'vehicle' . '_image/')) {
                        mkdir('./uploads/' . 'vehicle' . '_image/', 0777, true);
                    }
                    if (file_exists("./uploads/vehicle_image/vehicle_back_" . $this->input->post('unique_id') . ".jpg")) {
                        unlink('./uploads/' . 'vehicle' . '_image/' . 'vehicle_back' . '_' . $this->input->post('unique_id') . '.jpg');
                        file_put_contents("./uploads/vehicle_image/vehicle_back_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('vehicle_image_back')));
                    }else{
                    file_put_contents("./uploads/vehicle_image/vehicle_back_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('vehicle_image_back')));
                    }
                }

                if(! empty($this->input->post('vehicle_insurance_image_front'))){
                    if (! file_exists('./uploads/' . 'vehicle_insurance' . '_image/')) {
                        mkdir('./uploads/' . 'vehicle_insurance' . '_image/', 0777, true);
                    }
                    if (file_exists("./uploads/vehicle_insurance_image/vehicle_insurance_front_" . $this->input->post('unique_id') . ".jpg")) {
                        unlink('./uploads/' . 'vehicle_insurance' . '_image/' . 'vehicle_insurance_front' . '_' . $this->input->post('unique_id') . '.jpg');
                        file_put_contents("./uploads/vehicle_insurance_image/vehicle_insurance_front_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('vehicle_insurance_image_front')));
                    }else{
                    file_put_contents("./uploads/vehicle_insurance_image/vehicle_insurance_front_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('vehicle_insurance_image_front')));
                    }
                }

                if(! empty($this->input->post('vehicle_insurance_image_back'))){
                    if (! file_exists('./uploads/' . 'vehicle_insurance' . '_image/')) {
                        mkdir('./uploads/' . 'vehicle_insurance' . '_image/', 0777, true);
                    }
                    if (file_exists("./uploads/vehicle_insurance_image/vehicle_insurance_back_" . $this->input->post('unique_id') . ".jpg")) {
                        unlink('./uploads/' . 'vehicle_insurance' . '_image/' . 'vehicle_insurance_back' . '_' . $this->input->post('unique_id') . '.jpg');
                        file_put_contents("./uploads/vehicle_insurance_image/vehicle_insurance_back_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('vehicle_insurance_image_back')));
                    }else{
                    file_put_contents("./uploads/vehicle_insurance_image/vehicle_insurance_back_" . $this->input->post('unique_id') . ".jpg", base64_decode($this->input->post('vehicle_insurance_image_back')));
                    }
                }
                if($is_updated ){
                    $notification_id = $this->Notifications_model->insert([
                        'notification_type_id' => 27,
                        'app_details_id' => 2,
                        'title' => "New Delivery Partner is Cretaed!",
                        'message' => 'New Delivery Partner is Cretaed!',
                        'notified_user_id' => $token_data->id                    
                    ]);
                    $this->set_response_simple(($is_updated == FALSE) ? FALSE : $is_updated, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
                }else {
                    $this->set_response_simple(($is_updated == FALSE) ? FALSE : $is_updated, 'Failed..!', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, TRUE);
                }
            }
        }
    }


    public function payment_settings_get()
    {
        $result['pay_per_vendor'] = $this->setting_model->where('key', 'pay_per_vendor')->get()['value'];
        $result['vendor_validation'] = $this->setting_model->where('key', 'vendor_validation')->get()['value'];
        $this->set_response_simple(($result == FALSE) ? FALSE : $result, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * Vendor Lead Generation
     *
     * To Manage Lead Generation
     *
     * @author Mahesh
     * @param string $type
     */
    public function LeadGeneration_post($type = 'c')
    {
        $this->load->model('vendor_leads_model');
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        if ($type == 'c') {
            $id = $this->vendor_leads_model->insert([
                'user_id' => $token_data->id,
                'vendor_id' => $this->input->post('vendor_id'),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $this->set_response_simple($id, 'We Will Contact You Soon', REST_Controller::HTTP_CREATED, TRUE);
        }
        if ($type == 'array') {
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $lead_allocation_time = $this->setting_model->where('key','lead_allocation_time')->get()['value'];
            $this->db->insert('leads', [
                'user_id' => $token_data->id,
                'lead_code' => rand(999, 9999),
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 1,
                'validity' => date('Y-m-d H:i:s', strtotime("+".(count($_POST['vendors'])*$lead_allocation_time)." minutes"))
            ]);
            $lead_id = $this->db->insert_id();
            $reporting_end_time = '';$reporting_start_time= date('Y-m-d H:i:s');
            foreach ($_POST['vendors'] as $vendor) {
                if(empty($reporting_end_time)){
                    $reporting_end_time = date('Y-m-d H:i:s', strtotime($reporting_start_time. "+$lead_allocation_time minutes"));
                } else {
                    $reporting_start_time = $reporting_end_time;
                    $reporting_end_time = date('Y-m-d H:i:s', strtotime($reporting_end_time. "+$lead_allocation_time minutes"));
                }
                $data[] = array(
                    'lead_id' => $lead_id,
                    'vendor_id' => $vendor['vendor_id'],
                    'reporting_start_time' => $reporting_start_time,
                    'reporting_end_time' => $reporting_end_time,
                    'status' => 1
                );
            }
            $id = $this->db->insert_batch('lead_details', $data);
            $this->set_response_simple($id, 'We Will Contact You Soon', REST_Controller::HTTP_CREATED, TRUE);
        }
    }
    
    public function lead_cron_get(){
        $this->db->select('lead_details.id, lead_details.lead_id, leads.lead_code, leads.user_id, leads.validity, lead_details.vendor_id, lead_details.reporting_start_time, lead_details.reporting_end_time');
        $this->db->join('lead_details', 'leads.id = lead_details.lead_id');
        $this->db->where("leads.validity >=",  date('Y-m-d H:i:s'));
        $this->db->where("leads.created_at <=", date('Y-m-d H:i:s'));
        $this->db->where("leads.status", 1);
        $this->db->where("lead_details.reporting_start_time <=", date('Y-m-d H:i:s'));
        $this->db->where("lead_details.reporting_end_time >=", date('Y-m-d H:i:s'));
        $this->db->where("lead_details.status", 1);
        $query = $this->db->get('leads');
        $lead_list = $query->result_array();
        
        //$lead_list = $this->db->query("SELECT ld.id, ld.lead_id, l.lead_code, l.user_id, l.validity, ld.vendor_id, ld.reporting_start_time, ld.reporting_end_time FROM `leads` as l JOIN lead_details as ld ON l.id = ld.lead_id WHERE l.validity >= ".date('Y-m-d H:i:s')." AND l.created_at <= ".date('Y-m-d H:i:s')." AND l.status = 1 AND ld.reporting_start_time <= ".date('Y-m-d H:i:s')." AND ld.reporting_end_time >= ".date('Y-m-d H:i:s')." AND ld.status = 1")->result_array();
        if(! empty($lead_list)){
            $vendor_ids = [];
            foreach ($lead_list as $key => $lead){
                $is_exist = $this->db->where([ 'vendor_id' => $lead['vendor_id'], 'lead_id' => $lead['lead_id']])->get('vendor_leads')->result_array();
                if(! $is_exist){
                    $this->db->insert('vendor_leads', [
                        'vendor_id' => $lead['vendor_id'],
                        'lead_id' => $lead['lead_id'],
                        'lead_status' => 1
                    ]);
                    array_push($vendor_ids, $lead['vendor_id']);
                    $this->db->where([ 'vendor_id' => $lead['vendor_id'], 'lead_id' => $lead['lead_id']]);
                    $this->db->update('lead_details', ['status' => 2]);
                }
            }
            $this->send_notification($vendor_ids, VENDOR_APP_CODE, "Alert", "New Lead is generated..!",['notification_type' => $this->notification_type_model->where(['app_details_id' => 2, 'notification_code' => 'LD'])->get()]);
        }
        $this->set_response_simple($lead_list, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }



      /**
     * User address
     *
     * To Manage address
     *
     * @author Trupti
     * @param string $type
     */
    public function test_sale_post($type = 'r')
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if ($type == 'c') {

            $this->form_validation->set_rules($this->state_model->rules);
            if ($this->form_validation->run() == false) {
                $this->set_response_simple(validation_errors(), 'Validation Error', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {

                $id = $this->state_model->insert([
                    'user_id' => $token_data->id,
                    'name' => $this->input->post('name'),
                ]);
                $this->set_response_simple($id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
            }

        } 
        elseif ($type == 'r') {

            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);

        } 
         elseif ($type == 'u') {

            $this->form_validation->set_rules($this->users_address_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), 'Validation Error', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {

                $ll = $this->users_address_model->update([
                    'id' => $this->input->post('id'),
                    'user_id' => $token_data->id,
                    'name' => $this->input->post('name')
                  
                ], 'id');

        $this->set_response_simple($ll, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
            }

        } elseif ($type == 'd') {

            $ll = $this->state_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->set_response_simple($ll, 'Deleted..!', REST_Controller::HTTP_OK, TRUE);

        }
    }
 
}

?>