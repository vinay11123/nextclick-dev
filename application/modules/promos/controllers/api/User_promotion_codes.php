<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class User_promotion_codes extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('app_details_model');
        $this->load->model('promos_model');
        $this->load->model('sub_category_model');
        $this->load->model('promotion_scratch_cards_model');
        $this->load->model('promotion_code_products_model');
        $this->load->model('food_item_model');
        $this->load->model('vendor_product_variant_model');
        $this->load->model('used_promo_codes_model');
        $this->load->model('used_cupons_model');
		$this->load->model('cupons_model');

    }
	
	
	/**
     * @desc get api to retrieve cupons list data
     * @author Sandhip
     * @date 21/06/2021
     *  */

    public function normal_notification_get()
    {
		//$_POST = json_decode(file_get_contents("php://input"), TRUE);
        $server_key = $this->input->post('server_key');
       //API URL of FCM
    $url = 'https://fcm.googleapis.com/fcm/send';

    /*api_key available in:
    Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/
    //$api_key = $server_key;
	$api_key = 'AAAAWRthHjU:APA91bGEBZbfeIQsL4ob1wSc1R6Hok9dYFVtozzqd2JX6zT8sii_pdafMyq4ZHadpM4q3LfyfzutDz-S_i0Vp3w7MWlvrKaqYWAaCmCa_IdCsTbgUgIEV6XbwbZ5deJxt1obTGQWNaxw';            
    $fields = array (
        'registration_ids' => array (
'd8FLKGARR6SjxQfrQCm0lM:APA91bGA2LGFVKLuyfffGgZ1gh8x8X5gVTGPNR6wk64JjsENz4WK6JNaQQaq5iEiQK8Z2f1s4NTjr0XiAgi81bwhjzCiQFvgd-yGAHPL3umO6ft9TwzQLkvaGrmJoAvO6LcMTYfFGZ7N'),
        'data' => array (
                 "title" => "Game Reques1t",
        "body" => "Bob wants to play poker",
        )
    );
    //header includes Content type and api key 
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
    );
                
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    print_r($result);exit;
    }
	
	/**
     * @desc get api to retrieve cupons list data
     * @author Sandhip
     * @date 21/06/2021
     *  */

    public function cupons_list_get()
    {
        $cupon_code = $this->input->get('cupon_code');
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        if(empty($cupon_code)){
           $list_of_cupons = $this->cupons_model->where('created_user_id',$token_data->id)->get_all();
           if(!empty($list_of_cupons)){
           foreach($list_of_cupons as $k => $cupon){
              $list_of_cupons[$k]['promocode_status'] = ((date($list_of_cupons[$k]['valid_to'])) >= (date("Y-m-d"))) ? 'Active' : 'Expired' ;
            }}
            
           $this->set_response_simple($list_of_cupons ? $list_of_cupons : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
          }else {
            $cupon_codes = $this->cupons_model->where('code', $cupon_code)->get();
            $this->set_response_simple($cupon_codes ? $cupon_codes : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
	
	/**
     * @desc Get List of cupons under each position/all
     * @author sandhip
     */
    public function get_cupons_codes_get(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
       if(! empty($this->input->get('cupon_id'))){
            $cupon = $this->cupons_model->where('id', $this->input->get('cupon_id'))->get();
            if(! empty($cupon)){
               
                $this->set_response_simple($cupon, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }else {
                $this->set_response_simple(NULL, 'Invalid Cupon Code..!', REST_Controller::HTTP_OK, FALSE);
            }
        }else {
                    $cupons = $this->cupons_model
                    ->where('(date(`valid_from`) <= current_date())  and (date(`valid_to`) >= current_date())', NULL, NULL, FALSE, FALSE, TRUE)
                    ->get_all();

                    if(! empty($cupons)){
						foreach ($cupons as $k => $v){
                        $is_available = $this->used_cupons_model->where([
                            'cupon_id' => $cupons[$k]['id'],
                            'user_id' => $token_data->id,
                            'used_date' => date('Y-m-d')
                        ])->get();
                        $cupons[$k]['is_used'] = (empty($is_available)) ? 0 : 1;
                    }
					   $this->set_response_simple($cupons, 'Success..!', REST_Controller::HTTP_OK, TRUE);

					}
					else {
						$this->set_response_simple(NULL, 'No cupons available..!', REST_Controller::HTTP_OK, TRUE);
					}
            }
        
    }
	
    /**
     * @desc Get verify promo codes
     * @author tejaswini
     */

    public function verify_promo_post()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $promocode = $this->input->post('promocode');
        $promorequest = $this->input->post('promorequest');
        if(!empty($promocode)){
        $validate_promo_code = $this->promos_model->with_category('fields: id, name')
            ->with_promo_products('fields: id, promotion_code_id, product_id, vendor_product_variant_id')
            ->where('promo_code', $promocode)
            ->where('status', 1)
            ->where('(date(`valid_from`) <= current_date())  and (date(`valid_to`) >= current_date())')
            ->get();
          
        if(!empty($validate_promo_code)){
			
            $used_scrached = $this->used_promo_codes_model->where('status', 1)->where('promo_id',$validate_promo_code['id'])->where('user_id',$token_data->id)->get();
		  if(empty(!$used_scrached)){
                    $this->set_response_simple(NULL, 'already used..!', REST_Controller::HTTP_OK, TRUE);
                }else if(!empty($promorequest) && is_array($promorequest)){
                    $data['products_list'] = [];
                    foreach($promorequest as $request){
                        if ($request['vendor_id'] != $validate_promo_code['created_user_id']){
                            $this->set_response_simple(NULL, 'Promocode Not Valid for this vendor..!', REST_Controller::HTTP_OK, TRUE);
                        }else{
                            $products = $request['products'];
                            if(!empty($products) && is_array($products) ){
                                foreach ($products as $key => $item){
                                    $products[$key]['product_details'] = $this->food_item_model->fields('id, name, desc')->where('id', $item['product_id'])->get();
                                    $products[$key]['vendor_product_variant_details'] = $this->vendor_product_variant_model->with_list_id('fields:vendor_user_id, name')->with_section_item('fields:id, name')->where('id', $item['vendor_product_variant_id'])->get();
                                    if(!empty($products[$key]['vendor_product_variant_details'])){
                                        if($validate_promo_code['discount_type'] == 1){
                                            $sub_total = intval($item['qty']) * floatval($products[$key]['vendor_product_variant_details']['price']);
                                            //print_array($products[$key]['vendor_product_variant_details']['price']);
                                            $product_discount = $sub_total * (intval($item['product_discount']) / 100);
                                            $coupon_discount = $validate_promo_code['discount'];
                                            $total_discount = $product_discount + $coupon_discount;
                                            $tax = $sub_total * (intval($item['tax']) / 100);
                                            $subtotal_discount = $sub_total - $total_discount;
                                            $grand_total = $subtotal_discount + $tax;

                                            $products[$key]['sub_total'] =  $sub_total;
                                            $products[$key]['tax_amount'] =  $tax;
                                            $products[$key]['discount_amount'] =  $total_discount;
                                            $products[$key]['grand_total'] =  $grand_total;

                                        }else{
                                            $sub_total = intval($item['qty']) * floatval($products[$key]['vendor_product_variant_details']['price']);
                                            //print_array($products[$key]);
                                            $total_discount = $validate_promo_code['discount']  +  $item['product_discount'];
                                            $percentage_discount = $sub_total * (intval($total_discount) / 100);
                                            $tax = $sub_total * (intval($item['tax']) / 100);
                                            $subtotal_discount = $sub_total - $percentage_discount;
                                            $grand_total = $subtotal_discount + $tax;

                                             $products[$key]['sub_total'] =  $sub_total;
                                             $products[$key]['tax_amount'] =  $tax;
                                             $products[$key]['discount_amount'] =  $percentage_discount;
                                             $products[$key]['grand_total'] =  $grand_total;
                                        }
                                    }
                                    $all_varinat_ids = array_column($validate_promo_code['promo_products'], 'vendor_product_variant_id');
                                    if(in_array($item['vendor_product_variant_id'], $all_varinat_ids)){
                                        $products[$key]['is_applied'] = 1;
                                    }else{
                                        $products[$key]['is_applied'] = 0;
                                    }
                                    array_push($data['products_list'], $products[$key]);
                                }   
                            }
                            
                        }
                            
                    }
                    unset($validate_promo_code['promo_products']);
                    $data['promotion_code_details'] = $validate_promo_code;
                    $this->set_response_simple($data, 'Promocode Valid..!', REST_Controller::HTTP_OK, TRUE);

                    }
                  }else{
                    $this->set_response_simple(NULL, 'Promocode Not Valid..!', REST_Controller::HTTP_OK, TRUE);
                  }
                
         }else{
            $this->set_response_simple(NULL, 'Promocode Not Valid..!', REST_Controller::HTTP_OK, TRUE);
        }
        

    }
    
    /**
     * @desc Get List of banner under each position/all
     * @author Mehar
     */
    public function get_promotion_codes_get(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
       if(! empty($this->input->get('promotion_code_id'))){
            $promotion = $this->promos_model->with_promo_products('fields: id, promotion_code_id, product_id, vendor_product_variant_id')->where('id', $this->input->get('promotion_code_id'))->get();
            if(! empty($promotion)){
                if(! empty($promotion['promo_products'])){foreach ($promotion['promo_products'] as $key => $val){
                    $promotion['promo_products'][$key]['details'] = $this->food_item_model->with_item_images('fields: id, item_id, ext')->where('id', $val['product_id'])->get();
                    $promotion['promo_products'][$key]['details']['image'] = base_url() . 'uploads/food_item_image/food_item_' . $promotion['promo_products'][$key]['details']['item_images'][0]['id'] . '.' . $promotion['promo_products'][$key]['details']['item_images'][0]['ext'] . '?' . time();
                    $promotion['promo_products'][$key]['details']['variant_details'] = $this->vendor_product_variant_model->fields('id, item_id, section_id, section_item_id, sku, price, stock, discount, list_id, vendor_user_id, status')
                    ->with_section_item('fields: id, name, weight')
                    ->where([
                        'item_id' => $val['product_id'],
                        'id' => $val['vendor_product_variant_id']
                    ])->get();
                }}
				//print_r($this->db->last_query());exit;
                $this->set_response_simple($promotion, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }else {
                $this->set_response_simple(NULL, 'Invalid scratchcard..!', REST_Controller::HTTP_OK, FALSE);
            }
        }else {
            $vendors = $this->vendor_list_model->get_vendors_nearby_delivery($this->input->get('latitude'), $this->input->get('longitude'));
			//print_r($vendors);exit;
            if (! empty($vendors) && is_array($vendors)){
                $vendor_user_ids = array_column($vendors, 'vendor_user_id');
                if(! empty($vendor_user_ids)){
                    $promo_codes = $this->promos_model
                    ->with_promo_products('fields: id, promotion_code_id, product_id, vendor_product_variant_id')
                    ->with_vendor('fields: id, name')
                    ->where('created_user_id', $vendor_user_ids)
                    ->where('(date(`valid_from`) <= current_date())  and (date(`valid_to`) >= current_date())', NULL, NULL, FALSE, FALSE, TRUE)
                    ->get_all();
                    if(! empty($promo_codes)){foreach ($promo_codes as $k => $v){
                        if(! empty($promo_codes[$k]['promo_products'])){foreach ($promo_codes[$k]['promo_products'] as $key => $val){
                            $promo_codes[$k]['promo_products'][$key]['details'] = $this->food_item_model->with_item_images('fields: id, item_id, ext')->where('id', $val['product_id'])->get();
                            $promo_codes[$k]['promo_products'][$key]['details']['image'] = base_url() . 'uploads/food_item_image/food_item_' . $promo_codes[$k]['promo_products'][$key]['details']['item_images'][0]['id'] . '.' . $promo_codes[$k]['promo_products'][$key]['details']['item_images'][0]['ext'] . '?' . time();
                            $promo_codes[$k]['promo_products'][$key]['details']['variant_details'] = $this->vendor_product_variant_model->fields('id, item_id, section_id, section_item_id, sku, price, stock, discount, list_id, vendor_user_id, status')
                            ->with_section_item('fields: id, name, weight')
                            ->where([
                                'item_id' => $val['product_id'],
                                'id' => $val['vendor_product_variant_id']
                            ])->get();
                        }}
                        $promo_codes[$k]['image'] = base_url().'/uploads/scratch_cards/scratch_'.$v['promo_image'].'.jpg';
                        $promo_codes[$k]['valid_from'] = $v['valid_from'];
                        $promo_codes[$k]['valid_to'] = $v['valid_to'];
                        $promo_codes[$k]['vendor']['cover_image'] = base_url() . 'uploads/list_cover_image/list_cover_' . $promo_codes[$k]['vendor']['id'] . '.jpg'.'?'.time();
                        $is_available = $this->used_promo_codes_model->where([
                            'promo_id' => $promo_codes[$k]['id'],
                            'user_id' => $token_data->id,
                        ])->get();
                        $promo_codes[$k]['is_scratched'] = (empty($is_available)) ? 0 : 1;
                        if(isset($is_available['status'])) {
						    $promo_codes[$k]['is_used'] = $is_available['status'] == 1 ? 1 : 0;
                        }
                        else {
                            $promo_codes[$k]['is_used'] = 0;
                        }
                        
                    }}
                    if($promo_codes)
                        $this->set_response_simple($promo_codes, 'Success..!', REST_Controller::HTTP_OK, TRUE);
                    else
                        $this->set_response_simple([], 'No scratch cards available..!', REST_Controller::HTTP_OK, TRUE);
                }else {
                    $this->set_response_simple(NULL, 'No scratch cards available..!', REST_Controller::HTTP_OK, TRUE);
                }
            }else {
                $this->set_response_simple(NULL, 'No scratch cards available..!', REST_Controller::HTTP_OK, TRUE);
            }
        }
        
    }
    
    /**
     * @desc To update scratch card status
     * @author Mehar
     */
    public function promo_code_status_changer_get(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $is_available = $this->used_promo_codes_model->where([
            'promo_id' => $this->input->get('promotion_code_id'),
            'user_id' => $token_data->id,
        ])->get();
        if(empty($is_available)){
            $is_updated = $this->used_promo_codes_model->insert([
                'promo_id' => $this->input->get('promotion_code_id'),
                'user_id' => $token_data->id,
                'created_user_id' => $token_data->id,
                'uses' => 0,
                'status' => 2,
            ]);
            if(! empty($is_updated)){
                $this->set_response_simple($is_updated, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }else {
                $this->set_response_simple(NULL, 'Failed..!', REST_Controller::HTTP_OK, FALSE);
            }
        }else {
            $this->set_response_simple(NULL, 'Already scratched..!', REST_Controller::HTTP_OK, FALSE);
        }
        
        
        
    }
}
