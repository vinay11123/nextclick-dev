<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';
require_once FCPATH . 'vendor/autoload.php';

use Dompdf\Dompdf;

use Firebase\JWT\JWT;

class Vendor extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('pagination');
        $this->load->model('vendor_bank_details_model');
        $this->load->model('vendor_list_model');
        $this->load->model('Notifications_model');
        $this->load->model('setting_model');
        $this->load->model('contact_model');
        $this->load->model('social_model');
        $this->load->model('sub_category_model');
        $this->load->model('permission_model');
        $this->load->model('amenity_model');
        $this->load->model('vendor_amenity_model');
        $this->load->model('vendor_service_model');
        $this->load->model('vendor_sub_category_model');
        $this->load->model('vendor_brand_model');
        $this->load->model('vendor_banner_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('group_model');
        $this->load->model('location_model');
        $this->load->model('user_model');
        $this->load->model('constituency_model');
        $this->load->model('details_by_vendor_model');
        $this->load->model('package_model');
        $this->load->model('vendor_package_model');
        $this->load->model('vendor_settings_model');
        $this->load->model('food_settings_model');
        $this->load->model('vendor_leads_model');
        $this->load->model('ecom_order_model');
        $this->load->model('agreement_model');
    }

    /**
     * To manage profile 
     *
     * @author Mehar
     *        
     * @param string $type
     */
    public function profile_post($type = 'r', $u_type = 'bank_details')
    {

        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->vendor_list_model->user_id = $token_data->id;
        $vendor = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get();

    if ($type == 'r') {
    
        $this->data['vendor_details'] = $this->vendor_list_model
            ->with_address('fields: id, line1, lat, lng, state, district, constituency, zip_code, location')
            ->with_users('fields: id, phone')
            ->with_category('fields: id, name')
            ->with_vendor_sub_categories('fields: id, sub_category_id')
            ->with_constituency('fields: id, name, state_id, district_id')
            ->with_contacts('fields: id, std_code, number, type')
            ->with_links('fields: id, url, type')
            ->with_amenities('fields: id, name')
            ->with_services('fields: id, name,desc,languages')
            ->with_brands('fields: id, name')
            ->with_holidays('fields: id')
            ->where('vendor_user_id', $token_data->id)
            ->get();
    
        // ✅ ADD REFERRAL COUNT AFTER FETCH
        $this->data['vendor_details']['vendor_referral_count'] = $vendor_referral_count;
    
        $this->data['vendor_details']['is_admin'] =
            $this->ion_auth->in_group('admin', $token_data->id);
    
        if (!empty($this->data['vendor_details']['services'])) {
            foreach ($this->data['vendor_details']['services'] as $k => $v) {
                $this->data['vendor_details']['services'][$k]['image'] =
                    base_url() . "uploads/service_image/service_" . $v['id'] . ".jpg?" . time();
            }
        }
    
        if (!empty($this->data['vendor_details']['vendor_sub_categories'])) {
            foreach ($this->data['vendor_details']['vendor_sub_categories'] as $sub => $item) {
                $this->data['vendor_details']['vendor_sub_categories'][$sub]['sub_categories'] =
                    $this->sub_category_model
                        ->fields('id,name')
                        ->where('id', $item['sub_category_id'])
                        ->get();
            }
        }
    
        $this->data['vendor_details']['bank_details'] =
            $this->vendor_bank_details_model
                ->fields('id,bank_name,bank_branch,ifsc,ac_holder_name,ac_number')
                ->where('list_id', $this->data['vendor_details']['id'])
                ->get();
    
        $vendor_banners = $this->vendor_banner_model
            ->where('list_id', $vendor['id'])
            ->get_all();
    
        $this->data['vendor_details']['cover'] =
            base_url() . "uploads/list_cover_image/list_cover_" . $vendor['id'] . ".jpg";
    
        $this->data['vendor_details']['banners'] = [];
    
        if ($vendor_banners) {
            foreach ($vendor_banners as $key => $banner) {
                $this->data['vendor_details']['banners'][$key] = [
                    'id' => $banner['id'],
                    'image' => base_url() . "uploads/list_banner_image/list_banner_" . $banner['id'] . ".jpg"
                ];
            }
        }
    
        $this->set_response_simple(
            $this->data['vendor_details'],
            'Success..!',
            REST_Controller::HTTP_CREATED,
            TRUE
        );
    } elseif ($type == 'u') {
            if ($u_type == 'bank_details') {
                $this->form_validation->set_rules($this->vendor_bank_details_model->rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                } else {
                    $this->vendor_bank_details_model->user_id = $token_data->id;
                    $r = $this->vendor_bank_details_model->fields('id')->where(['list_id' => $vendor['id'], 'created_user_id' => $token_data->id])->get();
                    if (!empty($r)) {
                        $id = $this->vendor_bank_details_model->update([
                            'list_id' => $vendor['id'],
                            'bank_name' => $this->input->post('bank_name'),
                            'bank_branch' => $this->input->post('bank_branch'),
                            'ifsc' => $this->input->post('ifsc'),
                            'ac_holder_name' => $this->input->post('ac_holder_name'),
                            'ac_number' => $this->input->post('ac_number')
                        ], 'list_id');
                        $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Updated..!', REST_Controller::HTTP_ACCEPTED, TRUE);
                    } else {
                        $id = $this->vendor_bank_details_model->insert([
                            'bank_name' => $this->input->post('bank_name'),
                            'bank_branch' => $this->input->post('bank_branch'),
                            'ifsc' => $this->input->post('ifsc'),
                            'ac_holder_name' => $this->input->post('ac_holder_name'),
                            'ac_number' => $this->input->post('ac_number'),
                            'list_id' => $vendor['id']
                        ]);
                        $this->set_response_simple(($id == FALSE) ? FALSE : $id, 'Inserted..!', REST_Controller::HTTP_CREATED, TRUE);
                    }
                }
            }
        } elseif ($type == 'profile') {
            $this->form_validation->set_rules($this->vendor_list_model->rules['profile']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {

                $this->vendor_list_model->update([
                    'id' => $vendor['id'],
                    'name' => $this->input->post('name'),
                    'address' => $this->input->post('address'),
                    'email' => $this->input->post('email'),
                    'landmark' => $this->input->post('landmark'),
                    'desc' => $this->input->post('desc'),
                    'availability' => $this->input->post('availability'),
                    //'business_name' => $this->input->post('business_name'),
                    'business_description' => $this->input->post('business_description'),
                    'owner_name' => $this->input->post('owner'),
                    'gst_number' => $this->input->post('gst_number'),
                    'labour_certificate_number' => $this->input->post('labour_certificate_number'),
                    'fssai_number' => $this->input->post('fssai_number'),
                    'pincode' => $this->input->post('pincode'),
                    'constituency_id' => $this->input->post('constituency')
                ], 'id');


                $is_location_exist = $this->location_model->where([
                    'latitude' => $this->input->post('business_address')['lat'],
                    'longitude' => $this->input->post('business_address')['lng']
                ])->get();

                if (empty($is_location_exist)) {
                    $location_id = $this->location_model->insert([
                        'address' => $this->input->post('business_address')['location'],
                        'latitude' => $this->input->post('business_address')['lat'],
                        'longitude' => $this->input->post('business_address')['lng']
                    ]);
                } else {
                    $location_id = $is_location_exist['id'];
                }

                $this->vendor_list_model->update([
                    'id' => $vendor['id'],
                    'location_id' => $location_id
                ], 'id');
                if (!empty($this->input->post('contacts'))) {
                    foreach ($this->input->post('contacts') as $key => $val) {

                        if ($this->contact_model->where(['list_id' => $vendor['id'], 'type' => $key])->get() != FALSE) {
                            $this->contact_model->update(['std_code' => $val['code'], 'number' => $val['number']], ['list_id' => $vendor['id'], 'type' => $key]);
                        } else {
                            $this->contact_model->insert(['std_code' => $val['code'], 'number' => $val['number']], ['list_id' => $vendor['id'], 'type' => $key]);
                        }
                    }
                }
                $notification_id = $this->Notifications_model->insert([
                    'notification_type_id' => 26,
                    'app_details_id' => 2,
                    'title' => "New Vendor is Cretaed!",
                    'message' => 'New Vendor is Cretaed!',
                    'notified_user_id' => $token_data->id
                ]);
                $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
            }
        } elseif ($type == 'availability') {
            $this->vendor_list_model->update([
                'id' => $vendor['id'],
                'availability' => $this->input->post('availability')
            ], 'id');
            $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
        } elseif ($type == 'social') {
            $this->form_validation->set_rules($this->vendor_list_model->rules['social']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                if ($this->social_model->where(['list_id' => $vendor['id'], 'type' => 1])->get() != FALSE)
                    $this->social_model->update(['url' => $this->input->post('facebook')], ['list_id' => $vendor['id'], 'type' => 1]);
                else
                    $this->social_model->insert(['list_id' => $vendor['id'], 'url' => $this->input->post('facebook'), 'type' => 1]);

                if ($this->social_model->where(['list_id' => $vendor['id'], 'type' => 2])->get() != FALSE)
                    $this->social_model->update(['url' => $this->input->post('twitter')], ['list_id' => $vendor['id'], 'type' => 2]);
                else
                    $this->social_model->insert(['list_id' => $vendor['id'], 'url' => $this->input->post('twitter'), 'type' => 2]);

                if ($this->social_model->where(['list_id' => $vendor['id'], 'type' => 3])->get() != FALSE)
                    $this->social_model->update(['url' => $this->input->post('instagram')], ['list_id' => $vendor['id'], 'type' => 3]);
                else
                    $this->social_model->insert(['list_id' => $vendor['id'], 'url' => $this->input->post('instagram'), 'type' => 3]);

                if ($this->social_model->where(['list_id' => $vendor['id'], 'type' => 4])->get() != FALSE)
                    $this->social_model->update(['url' => $this->input->post('website')], ['list_id' => $vendor['id'], 'type' => 4]);
                else
                    $this->social_model->insert(['list_id' => $vendor['id'], 'url' => $this->input->post('website'), 'type' => 4]);
            }
            $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
        } elseif ($type == 'cover_and_banner_images') {
            $banners = $this->input->post('banners');
            if ($this->input->post('cover_image')) {
                if (file_exists(base_url() . "uploads/list_cover_image/list_cover_" . $vendor['id'] . ".jpg")) {
                    unlink(base_url() . "uploads/list_cover_image/list_cover_" . $vendor['id'] . ".jpg");
                    file_put_contents("./uploads/list_cover_image/list_cover_" . $vendor['id'] . ".jpg", base64_decode($this->input->post('cover_image')));
                } else {
                    file_put_contents("./uploads/list_cover_image/list_cover_" . $vendor['id'] . ".jpg", base64_decode($this->input->post('cover_image')));
                }
            }
            if (!empty($banners)) {
                foreach ($banners as $banner) {

                    $image_id = $this->vendor_banner_model->insert([
                        'list_id' => $vendor['id'],
                        'image' => 'banner_' . $vendor['id'] . '.jpg',
                        'ext' => 'jpg'
                    ]);

                    file_put_contents("uploads/list_banner_image/list_banner_$image_id.jpg", base64_decode($banner['image']));
                }
            }
            $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
        } elseif ($type == 'delete_banner') {
            $this->vendor_banner_model->delete([
                'id' => $this->input->post('id')
            ]);
            if (file_exists(base_url() . "uploads/list_banner_image/list_banner_" . $this->input->post('id') . ".jpg")) {
                unlink(base_url() . "uploads/list_banner_image/list_banner_" . $this->input->post('id') . ".jpg");
            }
            $this->set_response_simple(NULL, 'Banner deleted..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To get list of packages available
     *
     * @author Mehar
     *
     */
    public function packages_get()
    {
        $packages = $this->package_model->get_all();
        $this->set_response_simple($packages, 'List of packages..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * To get list of Keads
     *
     * @author Mehar
     *
     */
    public function leads_get()
    {
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

        $vendor_leads = $this->vendor_leads_model->order_by('id', 'DESC')->with_lead('fields: id, user_id')->where('vendor_id', $token_data->id)->get_all();
        if (!empty($vendor_leads)) {
            foreach ($vendor_leads as $key => $lead) {
                $vendor_leads[$key]['lead']['user'] = $this->user_model->fields('unique_id, email, first_name, last_name, phone')->get($lead['lead']['user_id']);
            }
        }
        $this->set_response_simple($vendor_leads, 'List of Leads..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * To get list of packages available
     *
     * @author Mehar
     *
     * @param string $type
     */

    public function settings_post($type = 'r')
    {
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->vendor_settings_model->user_id = $token_data->id;
        if ($type == 'r') {
            $data['shop_settings'] = $this->food_settings_model->where('vendor_id', $token_data->id)->get();
            $this->set_response_simple($data, 'Settings..!', REST_Controller::HTTP_OK, TRUE);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->vendor_settings_model->rules['food']);
            if ($this->form_validation->run() == FALSE) { //echo validation_errors();
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $settings = $this->food_settings_model->fields('id')->where('vendor_id', $token_data->id)->get();
                if (!empty($settings)) {
                    $this->food_settings_model->update([
                        'min_order_price' => $this->input->post('min_order_price'),
                        'min_delivery_fee' => $this->input->post('min_delivery_fee'),
                        'ext_delivery_fee' => $this->input->post('ext_delivery_fee'),
                        'delivery_free_range' => $this->input->post('delivery_free_range'),
                        'label' => $this->input->post('label'),
                        'tax' => $this->input->post('tax'),
                    ], ['vendor_id' => $token_data->id]);
                    $this->set_response_simple(NULL, 'Settings updated..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->food_settings_model->insert([
                        'min_order_price' => $this->input->post('min_order_price'),
                        'delivery_free_range' => $this->input->post('delivery_free_range'),
                        'min_delivery_fee' => $this->input->post('min_delivery_fee'),
                        'ext_delivery_fee' => $this->input->post('ext_delivery_fee'),
                        'label' => $this->input->post('label'),
                        'tax' => $this->input->post('tax'),
                        'vendor_id' => $token_data->id
                    ]);
                    $this->set_response_simple(NULL, 'Settings created..!', REST_Controller::HTTP_CREATED, TRUE);
                }
            }
        }
    }

    /**
     * To visualize sales reports
     *
     * @author Mehar
     *
     * @param string $type
     */
    public function sales_reports_get()
    {
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

        $satrt_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $data['mothly_reports'] = $this->ecom_order_model->income_reports_by_months($satrt_date, $end_date, $token_data->id);
        $data['weekly_reports'] = $this->ecom_order_model->income_reports_by_week($satrt_date, $end_date, $token_data->id);
        $data['orders_count_by_statuses']['received'] = $this->ecom_order_model->orders_count_by_status($satrt_date, $end_date, $token_data->id, 100);
        $data['orders_count_by_statuses']['accepted'] = $this->ecom_order_model->orders_count_by_status($satrt_date, $end_date, $token_data->id, 101);
        $data['orders_count_by_statuses']['You order has been preparing'] = $this->ecom_order_model->orders_count_by_status($satrt_date, $end_date, $token_data->id, 102);
        $data['orders_count_by_statuses']['Out for delivery'] = $this->ecom_order_model->orders_count_by_status($satrt_date, $end_date, $token_data->id, 103);
        $data['orders_count_by_statuses']['Rejected'] = $this->ecom_order_model->orders_count_by_status($satrt_date, $end_date, $token_data->id, 300);
        $data['orders_count_by_statuses']['Cancelled'] = $this->ecom_order_model->orders_count_by_status($satrt_date, $end_date, $token_data->id, 301);
        $this->set_response_simple($data, 'Reports data..!', REST_Controller::HTTP_CREATED, TRUE);
    }

    /**
     * To manage profile
     *
     * @author Mehar
     *        
     * @param string $type
     */
    public function subscriptions_post($type = 'list_of_subscriptions')
    {
        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if (empty($this->input->post('service_id')) & $this->input->post('service_id') != 0) {
            $this->set_response_simple(NULL, 'Please provide service_id.', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
        } else {
            if ($type == 'verify') {
                $vendor_package = $this->vendor_package_model->where(['created_user_id' => $token_data->id, 'status' => 1])->get();
                if (!empty($vendor_package)) {
                    if ($vendor_package['id'] == 1) {
                        $vendor_package['subscribed_at'] = $vendor_package['created_at'];
                        $vendor_package['expires_at'] = date('Y-m-d', strtotime($vendor_package['subscribed_at'] . ' + ' . $vendor_package['package']['days'] . ' days'));
                        $this->set_response_simple($vendor_package, 'Success..!', REST_Controller::HTTP_OK, TRUE);
                    } else {
                        $active_package = $this->vendor_package_model->with_package('fields: id, title, desc, days', 'where: service_id = ' . $this->input->post('service_id'))->where(['status' => 1, 'created_user_id' => $token_data->id])->get();
                        if (!empty($active_package)) {
                            $active_package['subscribed_at'] = $active_package['created_at'];
                            $active_package['expires_at'] = date('Y-m-d', strtotime($active_package['subscribed_at'] . ' + ' . $active_package['package']['days'] . ' days'));
                            $this->set_response_simple($active_package, 'Success..!', REST_Controller::HTTP_OK, TRUE);
                        } else {
                            $this->set_response_simple($vendor_package, 'No Active subscriptions..!', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                        }
                    }
                } else {
                    $this->set_response_simple($vendor_package, 'No subscriptions..!', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                }
            } elseif ($type == 'c') {
                $active_package = $this->vendor_package_model->with_package('fields: id, title, desc, days', 'where: service_id = ' . $this->input->post('service_id'))->where(['package_id' => $this->input->post('package_id'), 'created_user_id' => $token_data->id])->get();
                if (empty($active_package)) {
                    $this->vendor_package_model->update([
                        'status' => 2,
                        'service_id' => (empty($this->input->post('service_id'))) ? NULL : $this->input->post('service_id'),
                        'created_user_id' => $token_data->id,
                        'updated_user_id' => $token_data->id,
                        'updated_at' => date('Y-m-d H:i:s')
                    ], ['created_user_id', 'service_id']);
                    $id = $this->vendor_package_model->insert([
                        'package_id' => $this->input->post('package_id'),
                        'service_id' => (empty($this->input->post('service_id'))) ? NULL : $this->input->post('service_id'),
                        'created_user_id' => $token_data->id,
                        'status' => 1
                    ]);
                    $this->set_response_simple($id, 'Success..!', REST_Controller::HTTP_OK, TRUE);
                } else {
                    $active_package['subscribed_at'] = $active_package['created_at'];
                    $active_package['expires_at'] = date('Y-m-d', strtotime($active_package['subscribed_at'] . ' + ' . $active_package['package']['days'] . ' days'));
                    $this->set_response_simple($active_package, 'You already have subscribed.', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                }
            } elseif ($type == 'list_of_subscriptions') {
                $active_package = $this->vendor_package_model->with_package('fields: id, title, desc, days')->where(['created_user_id' => $token_data->id, 'service_id' => $this->input->post('service_id')])->get();

                if (!empty($active_package)) {
                    $active_package['subscribed_at'] = $active_package['created_at'];
                    $active_package['expires_at'] = date('Y-m-d', strtotime($active_package['subscribed_at'] . ' + ' . $active_package['package']['days'] . ' days'));
                }
                $this->set_response_simple($active_package, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            } elseif ($type == 'all_plans') {
                //$packages['all_plans'] = $this->package_model->where(['service_id' => $this->input->post('service_id')])->fields('id, title, desc, price, days')->get_all();
                $checkPlan = $this->vendor_package_model->with_package('fields: id, title, desc, days')->where(['created_user_id' => $token_data->id])->get_all();
                $allPlans = $this->package_model->fields('id, title, desc, price, days')->get_all();
                if (!empty($checkPlan)) {
                    foreach ($allPlans as $key => $plan) {
                        if ($plan['title'] == 'Basic') {
                            unset($allPlans[$key]);
                        }
                    }
                    $packages['all_plans'] = $allPlans;
                } else {
                    $packages['all_plans'] = $this->package_model->fields('id, title, desc, price, days')->get_all();
                }
                $packages['active_plan'] = $this->vendor_package_model->with_package('fields: id, title, desc, days')->where(['created_user_id' => $token_data->id, 'service_id' => $this->input->post('service_id')])->get();
                if (!empty($packages['active_plan'])) {
                    $packages['active_plan']['subscribed_at'] = $packages['active_plan']['created_at'];
                    $packages['active_plan']['expires_at'] = date('Y-m-d', strtotime($packages['active_plan']['subscribed_at'] . ' + ' . $packages['active_plan']['package']['days'] . ' days'));
                }
                $this->set_response_simple((!empty($packages)) ? $packages : NULL, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }
        }
    }

    public function is_vendor_accepted_the_agreement_get()
    {

        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->vendor_list_model->user_id = $token_data->id;
        $vendor = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get();

        $data = ['status' => false];

        if ($vendor['agreement_id']) {
            $data['status'] = true;
        } else {
            $agreement = $this->agreement_model->where([
                'app_details_id' => 2,
                'status' => 1
            ])->get();
            $data['agreement'] = $agreement;
        }

        $this->set_response_simple($data, "Vendor Agreement Status", REST_Controller::HTTP_OK, TRUE);
    }

    private function imageToBase64($path)
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

    public function save_vendor_agreement_post()
    {

        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->vendor_list_model->user_id = $token_data->id;
        $vendor = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get();

        $this->db->select("v.*, v.whats_app_no as vendor_phone, CONCAT_WS(' ', u.first_name, u.last_name) as vendor_name, co.name AS con_name,dst.name AS dist_name, CONCAT_WS(', ', vad.location, vad.line1, st.name, dst.name, vad.zip_code) AS vendor_address");
        $this->db->from('vendors_list v');
        $this->db->join('users u', 'u.id = v.vendor_user_id');
        $this->db->join('vendor_address as vad', 'vad.list_id = v.id');
        $this->db->join('states as st', 'vad.state = st.id');
        $this->db->join('constituencies as co', 'vad.constituency = co.id');
        $this->db->join('districts as dst', 'vad.district = dst.id');
        $this->db->where('v.vendor_user_id', $token_data->id);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get();

        $vendor_data = $query->result();

        $vendor_data_email = $this->user_model->where([
            'id' => $vendor['vendor_user_id']
        ])->get();

        $user_signature = $this->setting_model->get_user_signature();

        $agreement_id = $this->input->post('agreement_id');

        if ($vendor['vendor_user_id'] && $agreement_id) {
            if (empty($vendor['agreement_id'])) {

                $dompdf = new Dompdf();
                $vendor_image_path = $_SERVER["DOCUMENT_ROOT"] . 'uploads/list_banner_image/list_banner_' . $vendor['vendor_user_id'] . ".jpg";
                if (file_exists($vendor_image_path)) {
                    $vendor_image = $_SERVER["DOCUMENT_ROOT"] . "/uploads/list_banner_image/list_banner_" . $vendor['vendor_user_id'] . ".jpg";
                    $image_path = $this->imageToBase64($vendor_image);
                } else {
                    $vendor_image = '';
                    $image_path = '';
                }

                $vendor_sign = '';
                $signatureFilePath = base_url('uploads/admin/' . $user_signature);
                if ($user_signature != '') {
                    $signatureFilePathVal = $this->imageToBase64($signatureFilePath);
                } else {
                    $signatureFilePathVal = '';
                }


                $data = [
                    'imageSrc1' => $this->imageToBase64('https://nextclick.in/email_images/agr_top.jpg'),
                    'imageSrc2' => $this->imageToBase64('https://nextclick.in/email_images/agr_toptab_bg.jpg'),
                    'imageSrc3' => $this->imageToBase64('https://nextclick.in/email_images/agr_logo.png'),
                    'imageSrc4' => $this->imageToBase64('https://nextclick.in/email_images/agr_footer_bg.jpg'),
                    'imageSrc5' => $this->imageToBase64('https://nextclick.in/email_images/agr_footer.jpg'),
                    'imageSrc6' => $vendor_sign,
                    'imageSrc7' => $signatureFilePathVal,
                    'imageSrc8' => $image_path,

                    'vendor_address' => $vendor_data[0]->vendor_address,
                    'vendor_phone' => $vendor_data[0]->vendor_phone,
                    'vendor_id' => $vendor_data[0]->vendor_user_id,
                    'vendor_email' => $vendor_data_email['email'],
                    'vendor_business_name' => $vendor_data[0]->business_name,
                    'vendor_name' => $vendor_data[0]->vendor_name,
                    'con_name' => $vendor_data[0]->con_name,
                    'dist_name' => $vendor_data[0]->dist_name,
                ];

                $html = $this->load->view('static_agreement_page', $data, true);
                // echo $html;
                
                $dompdf->loadHtml($html);
                $dompdf->render();

                $pdfFilename = 'accepted_agreement_' . time() . '.pdf';
                $pdfFilePath = FCPATH . 'exports/vendor_agreement_pdfs/' . $pdfFilename;
                if (!file_exists('exports/' . 'vendor_agreement_pdfs/')) {
                    mkdir('exports/' . 'vendor_agreement_pdfs/', 0777, true);
                }
                file_put_contents($pdfFilePath, $dompdf->output());

                $vendorObj = $this->vendor_list_model->where([
                    'vendor_user_id',
                    $token_data->id
                ])->get();
                $vendor_data_email_to = $this->user_model->where([
                    'id' => $vendorObj['vendor_user_id']
                ])->get();
                $data = array(
                    'vendor_name' => $vendorObj['name']
                );
                $attched_file = $_SERVER["DOCUMENT_ROOT"] . "/exports/vendor_agreement_pdfs/" . $vendorObj['agreement_accepted_file'];
                $message = $this->load->view('vendor_agrement_tem', $data, true);
                $this->email->clear();
                $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
                $this->email->to($vendor_data_email_to['email']);
                $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - Acknowledgement of Agreement Acceptance');
                $this->email->message($message);
                $this->email->attach($attched_file);
                $this->email->send();

                $this->email->send();

                $status = $this->vendor_list_model->update([
                    'agreement_id' => $agreement_id,
                    'agreement_accepted_at' => date('Y-m-d H:i:s'),
                    'agreement_accepted_file' => $pdfFilename,
                ], ['vendor_user_id' => $token_data->id]);

                if ($status) {
                    $this->set_response_simple(NULL, 'Success', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(NULL, 'Failed', REST_Controller::HTTP_OK, FALSE);
                }
            } else {
                $this->set_response_simple("Vendor already has an agreement", 'Failed', REST_Controller::HTTP_OK, FALSE);
            }
        } else {
            $this->set_response_simple("Something went wrong.", 'Failed', REST_Controller::HTTP_OK, FALSE);
        }
    }

    public function get_vendor_agreement_get()
    {

        $authorization_exp = explode(" ", $this->input->get_request_header('Authorization'), 2);
        $token_data = $this->validate_token($authorization_exp[1]);

        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->vendor_list_model->user_id = $token_data->id;
        $vendor = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get();

        if (!empty($vendor['agreement_id'])) {
            $agreement = $this->agreement_model->where('id', $vendor['agreement_id'])->get();
            if ($agreement['id']) {
                $this->set_response_simple($agreement, 'Success', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple("Something went wrong.", 'Failed', REST_Controller::HTTP_OK, FALSE);
            }
        } else {
            $this->set_response_simple("This vendor has no agreement", 'Failed', REST_Controller::HTTP_OK, FALSE);
        }
    }
}
