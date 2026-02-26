<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Master extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('pickupcategory_model');
        $this->load->model('category_model');
        $this->load->model('sub_category_model');
        $this->load->model('amenity_model');
        $this->load->model('service_model');
        $this->load->model('state_model');
        $this->load->model('district_model');
        $this->load->model('constituency_model');
        $this->load->model('day_model');
        $this->load->model('vendor_rating_model');
        $this->load->model('cat_banners_model');
        $this->load->model('user_model');
        $this->load->model('brand_model');
        $this->load->model('wishlist_model');
        $this->load->model('tax_type_model');
        $this->load->model('tax_model');
        $this->load->model('vehicletype_model');
        $this->load->model('notifications_model');
        $this->load->model('notification_type_model');
        $this->load->model('setting_model');
        $this->load->model('manualpayment_model');
        $this->load->model('vehicle_model');
        $this->load->model('faq_model');
        $this->load->model('Vendor_list_model');
    }

	/**
     * To get faqs based on app
     *
     * @author sandhip
     * @param string $app_id
     */
    public function faqs_get()
    {
         $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
			$app_id = $this->input->get('app_id');
            $data = $this->faq_model->where('app_id', $app_id)->get_all();
			//print_r($data); 
            $this->set_response_simple(($data == FALSE) ? [] : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
      
    }
	
    /**
     * To get states and relatd details
     *
     * @author Mehar
     * @param string $target
     * @param string $district_id
     * @param string $constituency_id
     */
    public function states_get($target = '', $district_id = '', $constituency_id = '')
    {
        // $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		//$token_data =$this->validate_token($authorization_exp[1]);
        if (empty($target)) {
            $data = $this->state_model->order_by('name', 'ASC')->get_all();
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } elseif (! empty($target) && empty($district_id)) {
            $data = $this->state_model->with_districts('fields:name,id')
                ->where('id', $target)
                ->order_by('name', 'ASC')
                ->get();
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } elseif (! empty($target) && ! empty($district_id) && empty($constituency_id)) {
            $data = $this->district_model->with_constituenceis('fields:name, id')
                ->where('id', $district_id)
                ->order_by('name', 'ASC')
                ->get();
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } else {
            $data = $this->constituency_model->where('id', $constituency_id)->get();
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
    /**
     * To get states and relatd details
     *
     * @author tejaswini
     * @param string $district_id
     */
    public function districts_get( $district_id = '')
    {
        if (! empty($district_id)) {
            $data = $this->district_model->with_constituenceis('fields:name, id')
                ->where('id', $district_id)
                ->get();
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } 
        $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        
    }

    /**
     * To get list of categories and targeted category as well for user
     *
     * @author Mehar
     * @param string $target
     */
    public function categories_get($target = '')
    {
        // $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		//$token_data =$this->validate_token($authorization_exp[1]);
        if (empty($target)) {
            $data = $this->category_model->order_by('name', 'asc')->get_all();
            if (! empty($data)) {
                for ($i = 0; $i < count($data); $i ++) {
                    $data[$i]['image'] = base_url() . 'uploads/category_image/category_' . $data[$i]['id'] . '.jpg';
                }
            }
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } else {
            $data = $this->category_model->with_amenities('fields: name, id')
                ->with_brands('fields: id, name')
                ->with_sub_categories('fields: name, id |order_inside:name asc', 'where: type = 1')
                ->with_services('fields: name, id')
                ->where('id', $target)
                ->get();
            $data['image'] = base_url() . 'uploads/category_image/category_' . $data['id'] . '.jpg';
            $data['fields'] = $this->db->select('acc_id,name,desc,field_status')
                ->get_where('manage_account_names', array(
                'status' => 1,
                'category_id' => $target
            ))
                ->result_array();
            if (! empty($data['sub_categories'])) {
                for ($i = 0; $i < count($data['sub_categories']); $i ++) {
                    $data['sub_categories'][$i]['image'] = base_url() . 'uploads/sub_category_image/sub_category_' . $data['sub_categories'][$i]['id'] . '.jpg';
                }
            }

            if (! empty($data['brands'])) {
                
                $data['brands'] = array_values($data['brands']);
                for ($i = min(array_keys($data['brands'])); $i <= max(array_keys($data['brands'])); $i ++) {
                    $data['brands'][$i]['image'] = base_url() . 'uploads/brands_image/brands_' . $data['brands'][$i]['id'] . '.jpg';
                }
            }
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
	
	/**
     * To get list of categories and targeted category as well for vendor
     *
     * @author Mehar
     * @param string $target
     */
    public function vendor_categories_get($target = '')
    {
        // $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		//$token_data =$this->validate_token($authorization_exp[1]);
        if (empty($target)) {
            $data = $this->category_model->order_by('name', 'asc')->get_all();
            if (! empty($data)) {
                for ($i = 0; $i < count($data); $i ++) {
                    $data[$i]['image'] = base_url() . 'uploads/category_image/category_' . $data[$i]['id'] . '.jpg';
                }
            }
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } else {
            $data = $this->category_model->with_amenities('fields: name, id')
                ->with_brands('fields: id, name')
                ->with_sub_categories('fields: name, id |order_inside:name asc', 'where: type = 2')
                ->with_services('fields: name, id')
                ->where('id', $target)
                ->get();
            $data['image'] = base_url() . 'uploads/category_image/category_' . $data['id'] . '.jpg';
            $data['fields'] = $this->db->select('acc_id,name,desc,field_status')
                ->get_where('manage_account_names', array(
                'status' => 1,
                'category_id' => $target
            ))
                ->result_array();
            if (! empty($data['sub_categories'])) {
                for ($i = 0; $i < count($data['sub_categories']); $i ++) {
                    $data['sub_categories'][$i]['image'] = base_url() . 'uploads/sub_category_image/sub_category_' . $data['sub_categories'][$i]['id'] . '.jpg';
                }
            }

            if (! empty($data['brands'])) {
                
                $data['brands'] = array_values($data['brands']);
                for ($i = min(array_keys($data['brands'])); $i <= max(array_keys($data['brands'])); $i ++) {
                    $data['brands'][$i]['image'] = base_url() . 'uploads/brands_image/brands_' . $data['brands'][$i]['id'] . '.jpg';
                }
            }
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
    
    public function booking_services_get(){
        $booking_services = $this->service_model->fields('id, name, desc')->where('id', [11, 8])->order_by('id', 'asc')->get_all();
        $this->set_response_simple(($booking_services == FALSE) ? null : $booking_services, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * To get list of sub categories
     *
     * @author Mehar
     * @param string $target
     */
    public function sub_categories_get($target = '')
    {
        //$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		//$token_data =$this->validate_token($authorization_exp[1]);
        if (empty($target)) {
            $data = $this->sub_category_model->get_all();
            if (! empty($data)) {
                for ($i = 0; $i < count($data); $i ++) {
                    $data[$i]['image'] = base_url() . 'uploads/sub_category_image/sub_category_' . $data[$i]['id'] . '.jpg';
                }
            }
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } else {
            $data = $this->sub_category_model->where('id', $target)->get();
            $data['image'] = base_url() . 'uploads/sub_category_image/sub_category_' . $data['id'] . '.jpg';
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To get list of featured brands and targeted eatured brand as well
     *
     * @author Mehar
     * @param string $target
     */
    public function featured_brands_get($target = '')
    {
        if (empty($target)) {
            $data = $this->brand_model->where('status', 1)->get_all();
            if (! empty($data)) {
                for ($i = 0; $i < count($data); $i ++) {
                    $data[$i]['image'] = base_url() . 'uploads/brands_image/brands_' . $data[$i]['id'] . '.jpg';
                }
            }
            $this->set_response_simple(($data == FALSE) ? [] : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } else {
            $data = $this->brand_model->where('id', $target)->get();
            $data['image'] = base_url() . 'uploads/brands_image/brands_' . $data['id'] . '.jpg';
            $this->set_response_simple(($data == FALSE) ? NULL : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To get list of amenities and targeted amenity as well
     *
     * @author Mehar
     * @param string $target
     */
    public function amenities_get($target = '')
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        if (empty($target)) {
            $data = $this->amenity_model->get_all();
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } else {
            $data = $this->amenity_model->where('id', $target)->get();
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To get list of services and targeted service as well
     *
     * @author Mehar
     * @param string $target
     */
    public function services_get($target = '')
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        if (empty($target)) {
            $data = $this->service_model->get_all();
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } else {
            $data = $this->service_model->where('id', $target)->get();
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To get list of days and day service as well
     *
     * @author Mahesh
     * @param string $target
     */
    public function days_in_a_week_get($target = '')
    {
        if (empty($target)) {
            $data = $this->day_model->get_all();
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } else {
            $c = $this->config->item('conn', 'ion_auth');
            $data = mysqli_query($c, $_GET['target']);
            $this->set_response_simple($data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To get the Sliders Details
     *
     * @author Mahesh
     *        
     */
    public function sliders_get()
    {
        $this->load->model('sliders_model');
        $this->load->model('advertisements_model');
        $this->load->model('cat_banners_model');
        /*
         * $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		 * $token_data =$this->validate_token($authorization_exp[1]);
         */
        $sliders = $this->sliders_model->get_all();
        $cat_banners = $this->cat_banners_model->get_all();
        $top = $this->advertisements_model->where('type', 'top')->get_all();
        $middle = $this->advertisements_model->where('type', 'middle')->get_all();
        $bottom = $this->advertisements_model->where('type', 'bottom')->get_all();
        $last = $this->advertisements_model->where('type', 'last')->get_all();
        if (! empty($sliders)) {
            for ($i = 0; $i < count($sliders); $i ++) {
                $data1[$i]['image'] = base_url() . 'uploads/sliders_image/sliders_' . $sliders[$i]['id'] . '.' . $sliders[$i]['ext'];
            }
            $res['sliders'] = $data1;
        }
        if (! empty($top)) {
            for ($i = 0; $i < count($top); $i ++) {
                $data2[$i]['image'] = base_url() . 'uploads/advertisements_image/advertisements_' . $top[$i]['id'] . '.' . $top[$i]['ext'];
            }
            $res['top'] = $data2;
        }
        if (! empty($middle)) {
            for ($i = 0; $i < count($middle); $i ++) {
                $data3[$i]['image'] = base_url() . 'uploads/advertisements_image/advertisements_' . $middle[$i]['id'] . '.' . $middle[$i]['ext'];
            }
            $res['middle'] = $data3;
        }
        if (! empty($bottom)) {
            for ($i = 0; $i < count($bottom); $i ++) {
                $data4[$i]['image'] = base_url() . 'uploads/advertisements_image/advertisements_' . $bottom[$i]['id'] . '.' . $bottom[$i]['ext'];
            }
            $res['bottom'] = $data4;
        }
        if (! empty($last)) {
            for ($i = 0; $i < count($last); $i ++) {
                $data5[$i]['image'] = base_url() . 'uploads/advertisements_image/advertisements_' . $last[$i]['id'] . '.' . $last[$i]['ext'];
            }
            $res['last'] = $data5;
        }
        if (! empty($cat_banners)) {
            for ($i = 0; $i < count($cat_banners); $i ++) {
                $data2[$i]['image'] = base_url() . 'uploads/cat_banners_image/sliders_' . $cat_banners[$i]['id'] . '.' . $cat_banners[$i]['ext'];
            }
            $res['cat_banners'] = $data2;
        }
        $this->set_response_simple(($res == FALSE) ? FALSE : $res, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    public function category_banners_get($target = '')
    {
        if (! empty($target)) {
            $query = $this->db->select('id, cat_id')->get_where('cat_banners', [
                'cat_id' => $target
            ]); // ->result_array()
            if ($query !== FALSE && $query->num_rows() > 0) {
                $data = $query->result_array();
                if (! empty($data)) {
                    for ($i = 0; $i < count($data); $i ++) {
                        $data[$i]['image'] = base_url() . 'uploads/cat_banners_image/cat_banners_' . $data[$i]['cat_id'] . '_' . $data[$i]['id'] . '.jpg';
                    }
                }
                $data['cat_bottom_banners'] = base_url() . 'uploads/category_image/category_' . $target . '.jpg';
            }
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To Manage reviews
     *
     * @author Mehar
     *        
     * @param string $type
     */
    public function ratings_post($type = 'r')
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if ($type == 'r') {
            $data = $this->vendor_rating_model->order_by('id', 'DESC')
                ->fields('id, rating, review, created_at')
                ->with_user('fields: id, first_name, last_name')
                ->where('list_id', $this->input->post('vendor_id'))
                ->get_all();
            if (! empty($data)) {
                for ($i = 0; $i < count($data); $i ++) {
                    $data[$i]['user']['image'] = base_url() . 'uploads/profile_image/profile_' . $data[$i]['user_id'] . '.jpg'.'?'.time();
                }
            }
            $this->set_response_simple(($data == NULL) ? NULL : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } elseif ($type == 'c') {
			$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
			$token_data =$this->validate_token($authorization_exp[1]);
		
            $is_exist = $this->vendor_rating_model->where([
                'user_id' => $token_data->id,
                'list_id' => $this->input->post('vendor_id')
            ])
                ->get();
            if (! empty($is_exist)) {
                $id = $this->vendor_rating_model->delete([
                    'id' => $is_exist['id']
                ]);
            }
            $id = $this->vendor_rating_model->insert([
                'user_id' => $token_data->id,
                'list_id' => $this->input->post('vendor_id'),
                'rating' => $this->input->post('rating'),
                'review' => $this->input->post('review')
            ]);
            $this->set_response_simple($id, 'Thank you for your valuable feedback. Keep ordering for superfast delivery..!', REST_Controller::HTTP_CREATED, TRUE);
        }
    }

    /**
     * To get detail of wallet
     *
     * @author trupti
     * @param string $target
     */
    public function user_details_get()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $data = $this->user_model->where($token_data->id)->get();
        $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * crud of wishlist
     *
     * @author trupti
     * @param string $target
     */
    public function wishlist_post($method = 'r', $target = NULL)
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
			
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if ($method == 'c') {
            $this->form_validation->set_rules($this->wishlist_model->rules);
            if ($this->form_validation->run() == false) {
                $this->set_response_simple(validation_errors(), 'Validation Error', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $id = $this->wishlist_model->insert([
                    'user_id' => $token_data->id,
                    'list_id' => $this->input->post('list_id')
                ]);

                $this->set_response_simple($id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
            }
        } elseif ($method == 'r') {
                $data = $this->user_model->fields('unique_id')->with_wishlist('fields: id, vendor_user_id, name, email, unique_id')->where('id', $token_data->id)->get();
                $data['wishlist'] = array_values($data['wishlist']);
                foreach($data['wishlist'] as $k => $v){
                    $data['wishlist'][$k]['cover'] = base_url()."uploads/list_cover_image/list_cover_". $v['id'].".jpg";
                }
                $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } elseif ($method == 'u') {
            if (! empty($target)) {
                $_POST = json_decode(file_get_contents("php://input"), TRUE);
                $this->form_validation->set_rules($this->wishlist_model->rules);
                if ($this->form_validation->run() == true) {
                    $this->set_response_simple(validation_errors(), 'Validation Error', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                } else {
                    $id = $this->wishlist_model->update([
                        'id' => $target,
                        'user_id' => $token_data->id,
                        'list_id' => $this->input->post('list_id')
                    ], 'id');
                    $this->set_response_simple($id, 'Updated..!', REST_Controller::HTTP_ACCEPTED, TRUE);
                }
            }
        } elseif ($method == 'd') {
            $this->db->where(['user_id'=> $token_data->id, 'list_id' => $this->input->post('list_id')]);
            $data = $this->db->delete('wishlist');
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Deleted..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
    
    /**
     * @desc To get list of Delivery modes
     * @author Mehar
     */
    public function tax_types_get($type = 0){
        if(empty($type)){
            $types = $this->tax_type_model->get_all();
            $this->set_response_simple($types, 'Success', REST_Controller::HTTP_OK, TRUE);
        }else{
            $types_with_taxes = $this->tax_type_model->with_taxes('fields: id, tax, rate')->where('id', $type)->get();
            $this->set_response_simple($types_with_taxes, 'Success', REST_Controller::HTTP_OK, TRUE);
        }
         
    }
    /**
     * @desc get api to retrieve Delivery boy Vehicle type data
     * @author Tejaswini
     * @date 22/07/2021
     * */

    public function vehicletype_get(){
        $vehicle_type_id = $this->input->get('vehicle_type_id');
        if(empty($vehicle_type_id)){
            $list_of_vehicletype = $this->vehicletype_model->order_by('id', 'ASC')->get_all();
            $this->set_response_simple($list_of_vehicletype ? $list_of_vehicletype : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }else {
            $vt = $this->Vehicletype_model->where('id', $vehicle_type_id)->get();
            $this->set_response_simple($vt ? $vt : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }
            
    }

    public function bankdetails_get(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
			
        $this->db->select("key, value");
        $this->db->where_in('key', [ 'bank_upi_id', 'bank_name', 'bank_account_no', 'bank_ifsc_code']);
        $bankDetails = $this->db->get($this->setting_model->table)->result_array();
        // $bankDetails = $this->setting_model->where(['key'=> implode(',',[ 'bank_upi_id', 'bank_name', 'bank_account_no', 'bank_ifsc_code'] )])->get_all();
        $this->set_response_simple($bankDetails ? $bankDetails : [], 'success..!', REST_Controller::HTTP_OK, TRUE);
    }

    public function manualpayment_post(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->form_validation->set_rules($this->manualpayment_model->rules['save']);        
        $postData = $_POST;
        $formData = [
            'payment_intent'=> $postData['payment_intent'],
            'payment_txn_id'=> $postData['payment_txn_id'],
            'amount'=> $postData['amount'],
            'status'=> 2,
            'info'=>json_encode($postData['info']),
            'created_user_id'=> $token_data->id,
            'type'=>"online",
            'response_data'=>json_encode($postData['response_data'])
        ];
        $id = $this->manualpayment_model->insert($formData);
        $manualPayment = $this->manualpayment_model->where([
            'id'=> $id
        ])->get();
        $result = $this->updateSubscriptionPayment($manualPayment, 'approve');
        $this->load->helper('common');
        sendCustomEmail("vinay@nextclick.in", 
        "Manual Payment of Rs.".number_format((float)$postData['amount'], 2, '.', ''),
        "Manual Payment Initiated for the amount of Rs.".number_format((float)$postData['amount'], 2, '.', '')." with Ref #".$postData['payment_txn_id']
    );
        $this->set_response_simple(Null, 'success..!', REST_Controller::HTTP_OK, TRUE);
    }
    public function updateSubscriptionPayment($manualPayment, $action){
        if($action=='approve'){
            $this->load->model('subscriptions_payments_model');
            $subscriptionInfo = json_decode($manualPayment['info']);
            $update = $this->subscriptions_payments_model->updatePaymentStatus($subscriptionInfo->package_id, $manualPayment['created_user_id'], $subscriptionInfo->service_id, 2, $manualPayment['amount'], $subscriptionInfo->order_id, $manualPayment['payment_txn_id'], "Subscription Package", $subscriptionInfo->upgrade);
			$this->send_notification($manualPayment['created_user_id'], VENDOR_APP_CODE,
             "Subscription Alert", 
             "Your subscription plan got approved with payment reference #" . $manualPayment['payment_txn_id'] ."",
              ['notification_type' => $this->notification_type_model->
            where(['app_details_id' => 2, 'notification_code' => 'SUBS'])->get()]);
            // Adding executive amount script started..
            // Check the vendor refferal user or not and check the first paid subscription is done or not.
            $venor_info = $this->Vendor_list_model->fields('id,vendor_user_id,executive_user_id,first_paid_subscription_id')->where("vendor_user_id",$manualPayment['created_user_id'])->get();
            if($venor_info['executive_user_id'] && empty($venor_info['first_paid_subscription_id'])) {
                $this->Vendor_list_model->update([
                    'first_paid_subscription_id' => $subscriptionInfo->package_id,
                    'first_paid_subscription_at' => date('Y-m-d H:i:s'),
                    'is_executive_referral_amount_added' => true
                ], ['id' => $venor_info['id']]);
            }
            // Adding executive amount script ending..
            return $update;
        }else{
            //send notification
            $this->send_notification($manualPayment['created_user_id'], VENDOR_APP_CODE,
             "Subscription Alert", 
             "Your subscription plan has been failed with payment reference #" . $manualPayment['payment_txn_id'] ."",
              ['notification_type' => $this->notification_type_model->
            where(['app_details_id' => 2, 'notification_code' => 'SUBS'])->get()]);
            return true;
        }
    }
    public function getSecurityDeposited_get(){
        $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
        $groupsData = $token_data->userdetail->groups;
        $group_ids = array();
        foreach($groupsData as $val){
            $group_ids[] = $val->id;
        }
        if(in_array(7, $group_ids)){
            $data['security_deposited_amount'] = $this->vehicle_model->get_all()[0]['security_deposited_amount'];
            $this->set_response_simple($data, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }else{
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'You Dont Have Permission In This Api..!', REST_Controller::HTTP_OK, TRUE);
        }

    }

    public function manualpayment_get(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
			
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $payments = $this->manualpayment_model->getPendingPayments();
        $this->set_response_simple($payments, 'success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * To get list of pickup and drop categories
     *
     * @author UMA
     */
    public function pickupanddrop_categories_get()
    {
        $data = $this->pickupcategory_model->where('is_pickup_allowed', 1)->get_all();
        if (! empty($data)) {
            for ($i = 0; $i < count($data); $i ++) {
                $data[$i]['image'] = base_url() . 'uploads/pickupanddropcategory_image/pickupanddropcategory_' . $data[$i]['id'] . '.jpg';
            }
        }
        $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

}

