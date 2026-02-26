<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Promotion_banners extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('fcm');
        
        $this->load->model('user_model');
        $this->load->model('app_details_model');
        $this->load->model('promotion_banner_model');
        $this->load->model('promotion_banner_position_model');
        $this->load->model('promotion_banner_content_type_model');
        $this->load->model('vendor_list_model');
        $this->load->model('promotion_banner_shop_by_category_model');
        $this->load->model('promotion_banner_joined_user_model');
        $this->load->model('promotion_banner_vendor_product_model');
        $this->load->model('vendor_service_model');
        $this->load->model('sub_category_model');
        $this->load->model('food_item_model');
        $this->load->model('vendor_product_variant_model');
        $this->load->model('food_cart_model');
        $this->load->model('promotion_banner_discount_type_model');
        $this->load->model('promotion_banner_vendor_offer_product_model');
        $this->load->model('promotion_banner_payment_model');
        $this->load->model('business_address_model');
        
    }
    
    /**
     * @desc To manage promotions from CRM App
     * @author Mehar
     * 
     * @param string $type
     */
    public function manage_promotion_banners_post($type = 'c'){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $is_inserted = $this->promotion_banner_model->user_id = $token_data->id;
        if($type == 'c'){
            $this->form_validation->set_rules($this->promotion_banner_model->rules['create']);
            if ($this->input->post('content_type') == 4 && empty($this->input->post('image'))) {
                $this->form_validation->set_rules('image', 'Banner Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_OK, FALSE);
            } else {
                $is_inserted = $this->promotion_banner_model->insert([
                    'title' => $this->input->post('title'),
                    'offer_title' => $this->input->post('offer_title'),
                    'offer_details' => $this->input->post('offer_details'),
                    'desc' => $this->input->post('desc'),
                    'cat_id' => !empty($this->input->post('cat_id')) ? $this->input->post('cat_id') : NULL,
                    'image_id' => empty($this->input->post('image_id'))? NULL : $this->input->post('image_id'),
                    'sub_cat_id' => empty($this->input->post('sub_cat_id'))? NULL : $this->input->post('sub_cat_id'),
                    'brand_id' => empty($this->input->post('brand_id'))? NULL : $this->input->post('brand_id'),
                    'constituency_id' => empty($this->input->post('constituency_id'))? NULL : $this->input->post('constituency_id'),
                    'promotion_banner_position_id' => empty($this->input->post('promotion_banner_position_id'))? NULL : $this->input->post('promotion_banner_position_id'),
                    'content_type' => empty($this->input->post('content_type'))? NULL : $this->input->post('content_type'),
                    'max_offer_steps' => empty($this->input->post('max_offer_steps'))? NULL : $this->input->post('max_offer_steps'),
                    'published_on' => empty($this->input->post('published_on'))? NULL : $this->input->post('published_on'),
                    'promotion_banner_discount_type_id' => empty($this->input->post('discount_type_id'))? NULL : $this->input->post('discount_type_id'),
                    'discount' => empty($this->input->post('discount'))? NULL : $this->input->post('discount'),
                    'expired_on' => empty($this->input->post('expired_on'))? NULL : $this->input->post('expired_on'),
                    'owner' => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 2,
                    //'status' => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 0,
                    'status' =>  1 ,
                ]);
                if ($is_inserted) {
                    if($this->input->post('content_type') == 4){
                        if (! file_exists('uploads/' . 'promotion_banner_shop_by_category' . '_image/')) {
                            mkdir('uploads/' . 'promotion_banner_shop_by_category' . '_image/', 0777, true);
                        }
                        if(! empty($this->input->post('shop_by_categories')))
                        { foreach ($this->input->post('shop_by_categories') as $key => $val){
                            $shop_by_category_banner_id = $this->promotion_banner_shop_by_category_model->insert([
                                'sub_cat_id' => $val['id'],
                                'promotion_banner_id' => $is_inserted
                            ]);
                            file_put_contents("./uploads/promotion_banner_shop_by_category_image/promotion_banner_shop_by_category_" . $shop_by_category_banner_id . ".jpg", base64_decode($val['image']));
                        }}
                        
                    }elseif ($this->input->post('content_type') == 3){
                        $this->promotion_banner_joined_user_model->insert([
                            'joined_user_id' => $token_data->id,
                            'promotion_banner_id' => $is_inserted
                        ]);
                        $this->promotion_banner_payments($this->input->post('txn_id'), $is_inserted, $this->input->post('amount'), $token_data->id);
                    }
                    if (! file_exists('uploads/' . 'promotion_banner' . '_image/')) {
                        mkdir('uploads/' . 'promotion_banner' . '_image/', 0777, true);
                    }
                    file_put_contents("./uploads/promotion_banner_image/promotion_banner_" . $is_inserted . ".jpg", base64_decode($this->input->post('image')));
                    $this->set_response_simple(($is_inserted == FALSE) ? NULL : $is_inserted, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($is_inserted == FALSE) ? NULL : $is_inserted, 'Failed..!', REST_Controller::HTTP_OK, FALSE);
                }
            }
        }elseif ($type == 'u'){
            $this->form_validation->set_rules($this->promotion_banner_model->rules['update']);
            if (empty($this->input->post('image'))) {
                $this->form_validation->set_rules('image', 'Banner Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(NULL, validation_errors(), REST_Controller::HTTP_OK, FALSE);
            } else {
                $is_updated = $this->promotion_banner_model->update([
                    'id' => $this->input->post('id'),
                    'title' => $this->input->post('title'),
                    'offer_title' => $this->input->post('offer_title'),
                    'offer_details' => $this->input->post('offer_details'),
                    'desc' => $this->input->post('desc'),
                    'cat_id' => empty($this->input->post('cat_id'))? NULL : $this->input->post('cat_id'),
                    'sub_cat_id' => empty($this->input->post('sub_cat_id'))? NULL : $this->input->post('sub_cat_id'),
                    'brand_id' => empty($this->input->post('brand_id'))? NULL : $this->input->post('brand_id'),
                    'constituency_id' => empty($this->input->post('constituency_id'))? NULL : $this->input->post('constituency_id'),
                    'promotion_banner_position_id' => empty($this->input->post('promotion_banner_position_id'))? NULL : $this->input->post('promotion_banner_position_id'),
                    'promotion_banner_discount_type_id' => empty($this->input->post('discount_type_id'))? NULL : $this->input->post('discount_type_id'),
                    'discount' => empty($this->input->post('discount'))? NULL : $this->input->post('discount'),
                    'content_type' => empty($this->input->post('content_type'))? NULL : $this->input->post('content_type'),
                    'published_on' => empty($this->input->post('published_on'))? NULL : $this->input->post('published_on'),
                    'expired_on' => empty($this->input->post('expired_on'))? NULL : $this->input->post('expired_on'),
                    'status' => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 0,
                ], 'id');
                if($this->input->post('content_type') === 4){
                    if (! file_exists('uploads/' . 'promotion_banner_shop_by_category' . '_image/')) {
                        mkdir('uploads/' . 'promotion_banner_shop_by_category' . '_image/', 0777, true);
                    }
                    if(! empty($this->input->post('shop_by_categories'))){ foreach ($this->input->post('shop_by_categories') as $key => $val){
                        $is_available = $this->promotion_banner_shop_by_category_model->where([
                            'sub_cat_id' => $val['id'],
                            'promotion_banner_id' => $this->input->post('id')
                        ])->get();
                        if($is_available){
                            $shop_by_category_banner_id = $this->promotion_banner_shop_by_category_model->update([
                                'sub_cat_id' => $val['id'],
                                'promotion_banner_id' => $this->input->post('id')
                            ], 'sub_cat_id');
                            file_put_contents("./uploads/promotion_banner_shop_by_category_image/promotion_banner_shop_by_category_" . $is_available['id'] . ".jpg", base64_decode($val['image']));
                        }else {
                            $shop_by_category_banner_id = $this->promotion_banner_shop_by_category_model->insert([
                                'sub_cat_id' => $val['id'],
                                'promotion_banner_id' => $this->input->post('id')
                            ]);
                            file_put_contents("./uploads/promotion_banner_shop_by_category_image/promotion_banner_shop_by_category_" . $shop_by_category_banner_id . ".jpg", base64_decode($val['image']));
                        }
                    }}
                    
                }
                if(! empty($this->input->post('image'))){
                    if (! file_exists('uploads/' . 'promotion_banner' . '_image/')) {
                        mkdir('uploads/' . 'promotion_banner' . '_image/', 0777, true);
                    }
                    file_put_contents("./uploads/promotion_banner_image/promotion_banner_" . $this->input->post('id') . ".jpg", base64_decode($this->input->post('image')));
                }
                if ($is_updated) {
                    $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
                } else {
                    $this->set_response_simple(NULL, 'Failed..!', REST_Controller::HTTP_OK, FALSE);
                }
            }
        }elseif ($type == 'r'){
            if(! empty($this->input->post('id'))){
                $data['banner'] = $this->promotion_banner_model->with_position('fields: id, title, banners_limit, per_day_charge')->get($this->input->post('id'));
                if($data['banner']['content_type'] == 3){
                    $data['banner']['image'] = base_url()."uploads/promotion_banner_suggestion_image/promotion_banner_suggestion_" . $data['banner']['image_id'] . ".jpg?".time();
                }else{
                    $data['banner']['image'] = base_url()."uploads/promotion_banner_image/promotion_banner_" . $data['banner']['id'] . ".jpg?".time();
                }
                
                $data['offers_on_shop_by_categories'] = $this->promotion_banner_shop_by_category_model->where('promotion_banner_id', $data['banner']['id'])->get_all();
                if(! empty($data['offers_on_shop_by_categories'])){ foreach ($data['offers_on_shop_by_categories'] as $key => $value){
                    $data['offers_on_shop_by_categories'][$key]['details'] = $this->sub_category_model->where('id', $value['sub_cat_id'])->get();
                    $data['offers_on_shop_by_categories'][$key]['image'] = base_url()."uploads/promotion_banner_shop_by_category_image/promotion_banner_shop_by_category_" . $value['id'] . ".jpg";
                }}
                $data['static_columns'] = $this->promotion_banner_model->static_columns;
                $this->set_response_simple($data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }else {
                $vendor = $this->vendor_list_model->fields('id, vendor_user_id, category_id')->where('vendor_user_id', $token_data->id)->get();
                $vendorConstituency = $this->business_address_model->where([
                    'list_id'=>$vendor['id']
                ])->get();
                $vendor['constituency_id'] = $vendorConstituency['constituency'];
                $admin_ids = $this->get_users_by_group(1);
                array_push($admin_ids, $token_data->id);
                $banners = $this->promotion_banner_model
                ->fields('id, image_id, title, offer_title, offer_details, desc, cat_id, sub_cat_id, brand_id, constituency_id, promotion_banner_position_id, content_type, published_on, expired_on, owner, accessibility, status, created_user_id')
                ->order_by('id', 'desc')
                ->where('(date(`published_on`) <= current_date())  and (date(`expired_on`) >= current_date())', NULL, FALSE)
                ->where('cat_id', $vendor['category_id'])->where('constituency_id', $vendor['constituency_id'])->where('created_user_id', $admin_ids)->get_all();
                
                if(! empty($banners)){
                    $data['banners'] = $banners;
                    foreach ($data['banners'] as $key => $val){
                        $is_joined = $this->promotion_banner_joined_user_model->fields('id, promotion_banner_id, joined_user_id, created_at')->where(['joined_user_id' => $token_data->id, 'promotion_banner_id' => $val['id']])->get();
                        $data['banners'][$key]['is_joined'] = empty($is_joined)? NULL : $is_joined; 
                        if($data['banners'][$key]['content_type'] == 3){
                            $data['banners'][$key]['banner_image'] = base_url()."uploads/promotion_banner_suggestion_image/promotion_banner_suggestion_" . $val['image_id'] . ".jpg?".time();
                            if($data['banners'][$key]['created_user_id'] != $token_data->id){
                                unset($data['banners'][$key]);
                            }
                        }else{
                            $data['banners'][$key]['banner_image'] = base_url()."uploads/promotion_banner_image/promotion_banner_" . $val['id'] . ".jpg?".time();
                        }
                    }
                }else {
                        $data['banners'] = [];
                }
                $data['banners'] = array_values($data['banners']);
                $data['static_columns'] = $this->promotion_banner_model->static_columns;
                $this->set_response_simple($data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }
        }elseif ($type == 'd'){
            $is_deleted = $this->promotion_banner_model->delete($this->input->post('id'));
            if ($is_deleted) {
                $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            } else {
                $this->set_response_simple(NULL, 'Failed..!', REST_Controller::HTTP_OK, FALSE);
            }
        }elseif ($type == 'history'){
            $banners_history['banners'] = $this->promotion_banner_joined_user_model->where('joined_user_id', $token_data->id)->get_all();
            if(! empty($banners_history['banners'])){foreach ($banners_history['banners'] as $key => $bh){
                $banner = $this->promotion_banner_model->get($bh['promotion_banner_id']);
                $banners_history['banners'][$key]['details'] = (empty($banner))? NULL : $banner;
                if($banner['content_type'] == 3){
                    $banners_history['banners'][$key]['banner_image'] = base_url().'/uploads/promotion_banner_suggestion_image/promotion_banner_suggestion_'.$banner['image_id'].'.jpg';
                }else {
                    $banners_history['banners'][$key]['banner_image'] = base_url().'/uploads/promotion_banner_image/promotion_banner_'.$bh['promotion_banner_id'].'.jpg';
                }
            }}
            $banners_history['static_columns'] = $this->promotion_banner_model->static_columns;
            $this->set_response_simple($banners_history, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
    
    /**
     * @desc To get list of discount types
     * @author Mehar
     */
    public function discount_types_get(){
        $promotion_discount_types = $this->promotion_banner_discount_type_model->where('id !=', 5)->get_all();
        $this->set_response_simple($promotion_discount_types, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }
    
    /**
     * @desc To check the banner availability based on constituency and publish date
     * @author Mehar
     */
    public function banner_availability_check_get(){
        $constituency_id = $this->input->get('constituency_id');
        $publish_date = $this->input->get('publish_date');
        if(! empty($constituency_id) && ! empty($publish_date)){
            $promotions = $this->db->query('
            select pbs.id, pbs.title, pbs.banners_limit, pbs.per_day_charge,
            (select count(id) from promotion_banners as pb where pb.constituency_id = '.$constituency_id.' and pb.promotion_banner_position_id = pbs.id and pb.published_on <= \''.$publish_date.'\' and pb.expired_on >= \''.$publish_date.'\') as filled_positions,
            (select date_add(min(pbn.expired_on), INTERVAL 1 DAY) from promotion_banners as pbn where pbn.constituency_id = '.$constituency_id.' and pbn.promotion_banner_position_id = pbs.id and pbn.published_on <= \''.$publish_date.'\' and pbn.expired_on >= \''.$publish_date.'\') as next_available_date
            from promotion_banner_postions as pbs where 1;
        ')->result_array();
			//print_r($this->db->last_query());exit;
            $this->set_response_simple($promotions, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }else {
            $this->set_response_simple(NULL, 'Please provide constituency_id/publish_date.', REST_Controller::HTTP_OK, FALSE);
        }
        
    }
    
    /**
     * @desc To join the promotion
     * @author Mehar
     */
    public function join_the_promotion_post(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $promotion_banner_id = $this->input->post('promotion_banner_id');
        if(! empty($promotion_banner_id)){
            $promotion_banner = $this->promotion_banner_model->where(['status' =>  1, 'id' => $promotion_banner_id])->get();
            if(! empty($promotion_banner)){
                $is_joined = $this->promotion_banner_joined_user_model->where([
                    'joined_user_id' => $token_data->id,
                    'promotion_banner_id' => $promotion_banner_id
                ])->get();
                if(empty($is_joined)){
                    $joinee_id = $this->promotion_banner_joined_user_model->insert([
                        'joined_user_id' => $token_data->id,
                        'promotion_banner_id' => $promotion_banner_id
                    ]);
                    if ($promotion_banner['content_type'] == 4){
                        foreach ($this->input->post('products') as $key => $values){
                            $this->promotion_banner_vendor_product_model->insert([
                                'promotion_banner_id' => $promotion_banner_id,
                                'promotion_banner_shop_by_category_id' => $values['shop_by_category_id'],
                                'product_id' => $values['product_id'],
                                'vendor_product_variant_id' => $values['product_variant_id'],
                                'vendor_user_id' => $token_data->id,
                                'promotion_banner_joinee_id' => $joinee_id
                            ]);
                        }
                            
                        if(! empty($this->input->post('offer_products'))){foreach ($this->input->post('offer_products') as $key => $value){
                            $this->promotion_banner_vendor_offer_product_model->insert([
                                'promotion_banner_id' => $promotion_banner_id,
                                'product_id' => $value['product_id'],
                                'promotion_banner_shop_by_category_id' => $value['shop_by_category_id'],
                                'vendor_product_variant_id' => $value['product_variant_id'],
                                'vendor_user_id' => $token_data->id,
                                'promotion_banner_joinee_id' => $joinee_id
                            ]);
                            
                        }}
                        
                        $this->promotion_banner_payments($this->input->post('txn_id'), $promotion_banner_id, $this->input->post('amount'), $token_data->id);
                        $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_OK, TRUE);
                    }
                }else{
                    $this->set_response_simple(NULL, 'Already joined..!', REST_Controller::HTTP_OK, FALSE);
                }
            }else {
                $this->set_response_simple(NULL, 'Invalid promotion banner', REST_Controller::HTTP_OK, FALSE);
            }
        }else {
            $this->set_response_simple(NULL, 'Please provide Promotion banner id', REST_Controller::HTTP_OK, FALSE);
        }
    }
    
    /**
     * @desc Get all banner positions using on application
     * @author Mehar
     */
    public function banner_positions_get(){
        $positions = $this->promotion_banner_position_model->get_all();
        if($positions){foreach ($positions as $key => $val){
            $positions['image'] = base_url().'uploads/promotion_banner_position_'.$val['id'].'.jpg';
        }}
        $this->set_response_simple($positions, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }
    
    /**
     * @desc Get all banner positions using on application
     * @author Mehar
     */
    public function content_types_list_get(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        if(! $this->ion_auth->in_group('admin', $token_data->id)){
            $this->db->where_in('id', '3');
        }else {
            $this->db->where_in('id', [3, 4]);
        }
        $content_type = $this->promotion_banner_content_type_model->order_by('id', 'DESC')->get_all();
        $this->set_response_simple($content_type, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }
    
    /**
     * @desc Get List of banner under each position/all
     * @author Mehar
     */
    public function get_banners_get(){
        if(! empty($this->input->get('promotion_id'))){
            $promotion = $this->promotion_banner_model->where('id', $this->input->get('promotion_id'))->get();
            if(! empty($promotion)){
                if($promotion['content_type'] == 2){
                    $vendors = $this->promotion_banner_joined_user_model->with_promotion('fields: id, content_type, title', 'where: content_type=2')->where('promotion_banner_id', $promotion['id'])->get_all();
                    $promotion['vendors'] = NULL;
                    if(! empty($vendors)){ 
                        $data = $this->vendor_list_model->all(
                                NULL,
                                NULL,
                                NUll,
                                NUll,
                                NUll,
                                (isset($_GET['latitude'])) ? $this->input->get('latitude') : NUll,
                                (isset($_GET['longitude'])) ? $this->input->get('longitude') : NUll,
                                NUll,
                                array_column($vendors, 'joined_user_id')
                            );
                        if (! empty($data['result'])) {
                            foreach ($data['result'] as $d) {
                                $d->services = $this->vendor_service_model->with_service('fields: id, name, desc')->fields('service_id')->where('list_id', $d->id)->get_all();
                                $d->is_having_lead = FALSE;
                                if(empty($d->services)){
                                    $d->is_having_lead = FALSE;
                                }elseif (in_array($this->config->item('leads_service_id', 'ion_auth'), array_column($d->services, 'service_id'))){
                                    $d->is_having_lead = TRUE;
                                }
                                $d->avg_rating = $this->db->query('SELECT AVG(rating) as avg_rating FROM `vendor_ratings` WHERE list_id ='.$d->id)->row()->avg_rating;
                                $d->image = base_url() . 'uploads/list_cover_image/list_cover_' . $d->id . '.jpg'.'?'.time();
                            }
                            $promotion['vendors'] = $data['result'];
                        }else {
                            $promotion['vendors'] = NULL;
                        }
                    }
                }elseif ($promotion['content_type'] == 4){
                    $promotion['banner_image'] = base_url().'/uploads/promotion_banner_image/promotion_banner_'.$promotion['id'].'.jpg?'.time();
                    $promotion['offers_on_shop_by_categories'] = $this->promotion_banner_shop_by_category_model->where('promotion_banner_id', $promotion['id'])->get_all();
                    if(! empty($promotion['offers_on_shop_by_categories'])){ foreach ($promotion['offers_on_shop_by_categories'] as $key => $value){
                        $promotion['offers_on_shop_by_categories'][$key]['details'] = $this->sub_category_model->where('id', $value['sub_cat_id'])->get();
                        $promotion['offers_on_shop_by_categories'][$key]['image'] = base_url()."uploads/promotion_banner_shop_by_category_image/promotion_banner_shop_by_category_" . $value['id'] . ".jpg?".time();
                    }}
                }elseif ($promotion['content_type'] == 3){
                    $promotion['banner_image'] = base_url().'/uploads/promotion_banner_suggestion_image/promotion_banner_suggestion_'.$promotion['image_id'].'.jpg?'.time();
                    $promotion['vendor'] = $this->vendor_list_model->fields('id, unique_id, name, category_id, location_id,vendor_user_id')->where('vendor_user_id', $promotion['created_user_id'])->get();
                }
                
                $this->set_response_simple($promotion, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }else {
                $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_OK, FALSE);
            }
            
        }else {
            $vendors = $this->vendor_list_model->get_vendors_nearby_delivery($this->input->get('latitude'), $this->input->get('longitude'));
            if (! empty($vendors) && is_array($vendors)){
                $constituencies = array_unique(array_column($vendors, 'constituency_id'));
                $unique_vendors = array_unique(array_column($vendors, 'vendor_user_id'));
                if(! empty($this->input->get('position'))){
                    $this->db->where('promotion_banner_position_id', $this->input->get('position'));
                }
                
                if(! empty($this->input->get('cat_id'))){
                    $this->db->where('cat_id', $this->input->get('cat_id'));
                }
                if(! empty($constituencies)){ //promotion_banner_suggestion
                    $banners = $this->promotion_banner_model
                    ->with_position('fields: id, title')
                    ->with_content_type('fields: id, name')
                    ->where('constituency_id', $constituencies)
                    // ->where('created_user_id', $unique_vendors)
                    ->where('(date(`published_on`) <= current_date())  and (date(`expired_on`) >= current_date())', NULL, NULL, FALSE, FALSE, TRUE)
                    ->where('status', 1)
                    ->get_all();                     
                    if(! empty($banners)){foreach ($banners as $key => $val){
                        $banners[$key]['vendor'] = $this->vendor_list_model->fields('id, unique_id, name, vendor_user_id')->where('vendor_user_id', $val['created_user_id'])->get();
                        if($val['content_type']['id'] == 3){
                            $banners[$key]['banner_image'] = base_url().'/uploads/promotion_banner_suggestion_image/promotion_banner_suggestion_'.$val['image_id'].'.jpg?'.time();
                        }else {
                            $banners[$key]['banner_image'] = base_url().'/uploads/promotion_banner_image/promotion_banner_'.$val['id'].'.jpg?'.time();
                        }
                    }}
                    $data['banners'] = (empty($banners))? NULL : $banners;
                    $data['content_types'] = $this->promotion_banner_content_type_model->get_all();
                    $this->set_response_simple($data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
                }else {
                    $banners = $this->promotion_banner_model
                    ->with_position('fields: id, title')
                    ->with_content_type('fields: id, name')
                    ->where('(date(`published_on`) <= current_date())  and (date(`expired_on`) >= current_date())', NULL, NULL, FALSE, FALSE, TRUE)
                    ->where('owner', 1)
                    ->get_all();
                    
                    if(! empty($banners)){foreach ($banners as $key => $val){
                        if($val['content_type']['id'] == 3){
                            $banners[$key]['banner_image'] = base_url().'/uploads/promotion_banner_suggestion_image/promotion_banner_suggestion_'.$val['image_id'].'.jpg?'.time();
                        }else {
                            $banners[$key]['banner_image'] = base_url().'/uploads/promotion_banner_image/promotion_banner_'.$val['id'].'.jpg?'.time();
                        }
                    }}
                    $data['banners'] = $banners;
                    $data['content_types'] = $this->promotion_banner_content_type_model->get_all();
                    $this->set_response_simple($data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
                }
            }else {
                $this->set_response_simple(NULL, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }
        }
        
    }
    
    /**
     * @desc To take payments
     * @author Mehar
     * @param unknown $txn_id
     * @param unknown $promotion_banner_id
     * @param number $amount
     * @param unknown $created_user_id
     */
    public function promotion_banner_payments($txn_id = NULL, $promotion_banner_id = NULL, $amount = 0, $created_user_id = NULL){
        $this->promotion_banner_payment_model->user_id = $created_user_id;
        $promotion_banner_payment_id = $this->promotion_banner_payment_model->insert([
            'txn_id' => $txn_id,
            'promotion_banner_id' => $promotion_banner_id,
            'amount' => $amount,
        ]);
        if(! empty($promotion_banner_payment_id)){
            $txn_id = 'NCP-' . generate_trasaction_no();
            $this->user_model->payment_update($this->config->item('super_admin_user_id'), $amount, 'CREDIT', "wallet", $txn_id, NULL, "Promotions Payment", NULL, $promotion_banner_payment_id);
        }
        
    }
    
    /**
     * @desc To get products under each shop by category banner
     * @author Mehar
     */
    public function promotion_banner_products_get(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $promotion_banner_id = $this->input->get('promotion_banner_id');
        $promotion_banner_shop_by_category_id = $this->input->get('sub_cat_id');
        if(! empty($promotion_banner_id) && ! empty($promotion_banner_shop_by_category_id)){
            $products = $this->promotion_banner_vendor_product_model->where(['promotion_banner_id' => $promotion_banner_id, 'promotion_banner_shop_by_category_id' => $promotion_banner_shop_by_category_id])->get_all();
            if(! empty($products)){ 
                foreach ($products as $key => $product){
                    $products[$key]['details'] = $this->food_item_model->with_item_images('fields: id, item_id, ext')->where('id', $product['product_id'])->get();
                    $products[$key]['details']['image'] = base_url() . 'uploads/food_item_image/food_item_' . $products[$key]['details']['item_images'][0]['id'] . '.' . $products[$key]['details']['item_images'][0]['ext'] . '?' . time();
                    $is_product_available_on_cart = $this->food_cart_model->where([
                        'vendor_product_variant_id' => $product['vendor_product_variant_id'],
                        'created_user_id' => $token_data->id,
                        'vendor_user_id' => $product['vendor_user_id']
                    ])->get_all();
                    if (! empty($is_product_available_on_cart)) {
                        $products[$key]['details']['cart_qty'] = array_sum(array_column($is_product_available_on_cart, 'qty'));
                    } else {
                        $products[$key]['details']['cart_qty'] = 0;
                    }
                    
                    $products[$key]['variant_details'] = $this->vendor_product_variant_model->fields('id, item_id, section_id, section_item_id, sku, price, stock, discount, list_id, vendor_user_id, status')
                    ->with_section_item('fields: id, name, weight')
                    ->where([
                        'item_id' => $product['product_id'],
                        'id' => $product['vendor_product_variant_id']
                    ])->get_all();
                    $min_price = (!empty($products[$key]['variant_details'])) ? array_column($products[$key]['variant_details'], 'price') : [];
                    $max_stock = (!empty($products[$key]['variant_details'])) ? array_column($products[$key]['variant_details'], 'stock') : [];
                    $max_discount = (!empty($products[$key]['variant_details'])) ? array_column($products[$key]['variant_details'], 'discount') : [];
                    $products[$key]['details']['max_stock'] = empty($max_stock)? 0 : max($max_stock);
                    $products[$key]['details']['min_price'] = empty($min_price)? 0 : min($min_price);
                    $products[$key]['details']['avg_discount'] = empty($max_discount)? 0 : max($max_discount);
                    
                    if(! empty($products[$key]['variant_details'])){foreach ($products[$key]['variant_details']  as $k => $variant){
                        $cart = $this->food_cart_model->fields('qty')->where([
                            'vendor_product_variant_id' => $variant['id'],
                            'created_user_id' => $token_data->id,
                            'vendor_user_id' => $product['vendor_user_id']
                        ])->get();
                        $products[$key]['variant_details'][$k]['cart'] =  empty($cart)? null : $cart;
                    }}
                    $products[$key]['vendor_details'] = $this->vendor_list_model->fields('id, vendor_user_id, name, desc, landmark, email, unique_id, created_at, status, availability')
                    ->where('vendor_user_id', $product['vendor_user_id'])
                    ->get();
                    
                    $products[$key]['vendor_details']['cover_image'] = base_url() . 'uploads/list_cover_image/list_cover_' . $product[$key]['vendor_details']['id'] . '.jpg'.'?'.time();
                }
                $this->set_response_simple($products, 'success', REST_Controller::HTTP_OK, TRUE);
            }else {
                $this->set_response_simple(NULL, 'No products available..!', REST_Controller::HTTP_OK, TRUE);
            }
        }else {
            $this->set_response_simple(NULL, 'Please provide Promotion id and subcategory id', REST_Controller::HTTP_OK, FALSE);
        }
        
    }
    
}

