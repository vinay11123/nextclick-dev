<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Promotion_codes extends MY_REST_Controller
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
    }

    /**
     * @desc get api to retrieve promo codes list data
     * @author Tejaswini
     * @date 21/06/2021
     *  */

    public function promocodes_list_get()
    {
        $promo_code = $this->input->get('promo_code');
        $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
		if(empty($promo_code)){
           $list_of_promos = $this->promos_model->with_category('fields: id, name')->with_promo_products('fields: id, promotion_code_id, product_id, vendor_product_variant_id')->where('created_user_id',$token_data->id)->get_all();
           if(!empty($list_of_promos)){
           foreach($list_of_promos as $k => $promo){
              $list_of_promos[$k]['promocode_status'] = ((date($list_of_promos[$k]['valid_to'])) >= (date("Y-m-d"))) ? 'Active' : 'Expired' ;
            if(! empty($promo['promo_products']))
            { foreach($promo['promo_products'] as $key => $val){
                $list_of_promos[$k]['promo_products'][$key]['product_details'] = $this->food_item_model->fields('id, name, desc')->with_item_images('fields: id, item_id, ext')->where('id', $val['product_id'])->get();
                $list_of_promos[$k]['promo_products'][$key]['product_details']['image'] = base_url() . 'uploads/food_item_image/food_item_' . $list_of_promos[$k]['promo_products'][$key]['product_details']['item_images'][0]['id'] . '.' . $list_of_promos[$k]['promo_products'][$key]['product_details']['item_images'][0]['ext'] . '?' . time();
                $list_of_promos[$k]['promo_products'][$key]['vendor_product_variant_details'] = $this->vendor_product_variant_model->fields('id')->with_section_item('fields:id, name')->where('id', $val['vendor_product_variant_id'])->get();
            
            }}}}
            
           $this->set_response_simple($list_of_promos ? $list_of_promos : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
          }else {
            $promo_codes = $this->promos_model->where('promo_code', $promo_code)->get();
            $this->set_response_simple($promo_codes ? $promo_codes : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
    /**
     * @desc To  Promotion codes Crud
     * 
     */
    public function promotion_codes_post(string $type = 'r', $target = NULL)
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->promos_model->user_id = $token_data->id;
        $this->promotion_code_products_model->user_id = $token_data->id;

        if ($type == 'c') {
            $this->form_validation->set_rules($this->promos_model->rules['create_rules']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Vallidation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $products = $this->input->post('products');
                $is_inserted = $this->promos_model->insert([
                        'promo_title' => empty($this->input->post('promo_title')) ? NULL : $this->input->post('promo_title'),
                        'promo_code' => empty($this->input->post('promo_code')) ? NULL : $this->input->post('promo_code'),
                        'promo_type' => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 0,
                        'valid_from' => empty($this->input->post('start_date')) ? null : $this->input->post('start_date'),
                        'valid_to' => empty($this->input->post('end_date')) ? NULL : $this->input->post('end_date'),
                        'category' => empty($this->input->post('cat_id')) ? NULL : $this->input->post('cat_id'),
                        'shop_by_category' => empty($this->input->post('sho_by_cat_id')) ? NULL : $this->input->post('sho_by_cat_id'),
                        'menu' => empty($this->input->post('menu')) ? NULL : $this->input->post('menu'),
                        'brand' => empty($this->input->post('brand')) ? NULL : $this->input->post('brand') ,
                        'promo_image' => empty($this->input->post('promo_image')) ? NULL : $this->input->post('promo_image'),
                        'discount_type' => empty($this->input->post('discount_type')) ? NULL : $this->input->post('discount_type'),
                        'discount' => empty($this->input->post('discount')) ? NULL : $this->input->post('discount'),
                        'uses' => empty($this->input->post('uses')) ? NULL : $this->input->post('uses') ,
                ]);
                    if(! empty($products)){
                       foreach($products as $product){
                        $product_check = $this->promotion_code_products_model
                        ->where(['created_user_id' => $token_data->id , 'product_id' => $product['product_id'],'vendor_product_variant_id' => $product['varient_id']])
                        ->limit(1)
                        ->order_by('id', 'desc')
                        ->get();
                        if(empty($product_check)){
                            $data['variant'] = $this->promotion_code_products_model->insert([
                                'promotion_code_id' => $is_inserted,
                                'product_id' => $product['product_id'],
                                'vendor_product_variant_id' => $product['varient_id'],
                                'valid_from' => empty($this->input->post('start_date')) ? null : $this->input->post('start_date'),
                                'valid_to' => empty($this->input->post('end_date')) ? NULL : $this->input->post('end_date'),
                                'created_user_id' => $token_data->id
                            ]);
                            
                        }else{
                            // if(strtotime($product_check['valid_to']) >= strtotime(date('Y-m-d'))){
                            //     $this->set_response_simple($product['variant'], 'Promocode already available for this products..!', REST_Controller::HTTP_OK, TRUE);
                            // }
                            // else{
                                $data['variant'] = $this->promotion_code_products_model->insert([
                                    'promotion_code_id' => $is_inserted,
                                    'product_id' => $product['product_id'],
                                    'vendor_product_variant_id' => $product['varient_id'],
                                    'valid_from' => empty($this->input->post('start_date')) ? null : $this->input->post('start_date'),
                                    'valid_to' => empty($this->input->post('end_date')) ? NULL : $this->input->post('end_date'),
                                    'created_user_id' => $token_data->id
                                ]);
                            // }
                            }
                        
                        }
                        if (! empty($data['variant'])){
                            $this->set_response_simple($data['variant'], 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                        } else {
                            $this->set_response_simple($product['variant'], 'Promocode already available for this products..!', REST_Controller::HTTP_OK, TRUE);
                        }
                    }
                   
                }
                
            } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->promos_model->rules['update_rules']);
            $products = $this->input->post('products');
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), "Validation errors", REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $update_array = $this->promos_model->update([
                    'id' => $this->input->post('id'),
                    'promo_title' => empty($this->input->post('promo_title')) ? NULL : $this->input->post('promo_title'),
                    'promo_code' => empty($this->input->post('promo_code')) ? NULL : $this->input->post('promo_code'),
                    'promo_type' => ($this->ion_auth->in_group('admin', $token_data->id)) ? 1 : 0,
                    'valid_from' => empty($this->input->post('start_date')) ? null : $this->input->post('start_date'),
                    'valid_to' => empty($this->input->post('end_date')) ? NULL : $this->input->post('end_date'),
                    'category' => empty($this->input->post('cat_id')) ? NULL : $this->input->post('cat_id'),
                    'shop_by_category' => empty($this->input->post('sho_by_cat_id')) ? NULL : $this->input->post('sho_by_cat_id'),
                    'menu' => empty($this->input->post('menu')) ? NULL : $this->input->post('menu'),
                    'brand' => empty($this->input->post('brand')) ? NULL : $this->input->post('brand') ,
                    'promo_image' => empty($this->input->post('promo_image')) ? NULL : $this->input->post('promo_image'),
                    'discount_type' => empty($this->input->post('discount_type')) ? NULL : $this->input->post('discount_type'),
                    'discount' => empty($this->input->post('discount')) ? NULL : $this->input->post('discount'),
                    'uses' => empty($this->input->post('uses')) ? NULL : $this->input->post('uses') ,
                    'updated_user_id' =>  $token_data->id
                ], 'id');
                if(! empty($products)){
                    foreach($products as $product){
                        $data['variant'] = $this->promotion_code_products_model->insert([
                            'promotion_code_id' => empty($this->input->post('id')) ? NULL : $this->input->post('id'),
                            'product_id' => empty($product['product_id']) ? NULL : $product['product_id'],
                            'vendor_product_variant_id' => empty($product['varient_id']) ? NULL : $product['varient_id']
                        ]);
                    }
                }
                
                if (!empty($update_array))
                {
                 $this->set_response_simple(($update_array == FALSE) ? FALSE : $update_array, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                } else {
                    $this->set_response_simple(($update_array == FALSE) ? FALSE : $update_array, 'Failed..!', REST_Controller::HTTP_CONFLICT, FALSE);
                }
            }
        } elseif ($type == 'd') {
            $this->promos_model->delete([
                'id' =>$this->input->get('id')
            ]);
            $this->set_response_simple(NULL, 'Promotion Code deleted..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * @desc To  Promotion codes scratch cards
     * 
     */
    
    public function scratchcards_get()
    {
        $scratch_id = $this->input->get('scratch_id');
        if(empty($scratch_id)){
            $scratchcards = $this->promotion_scratch_cards_model->get_all();
            foreach ($scratchcards as $key => $image) {
                $scratchcards[$key]['id'] = base_url() . 'uploads/scratch_cards/scratch_' . $image['id'] . '.png';
            }
           $this->set_response_simple($scratchcards ? $scratchcards : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }else {
            $scratch_cards = base_url() . 'uploads/scratch_cards/scratch_' . $scratch_id . '.png';
            $this->set_response_simple($scratch_cards ? $scratch_cards : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * @desc To  shop by category
     * 
     */
    
    public function shop_by_category_get($target = 0)
    {
        $cat_id = $this->input->get('cat_id');
        if(empty($cat_id)){
           $shop_by_category = $this->sub_category_model->fields('id,name')->where('type', 2)->order_by('id', 'DESC')->get_all();
           $this->set_response_simple($shop_by_category ? $shop_by_category : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }else {
            $shop_by_category = $this->sub_category_model->fields('id,name')->where('cat_id', $cat_id)->get_all();;
            $this->set_response_simple($shop_by_category ? $shop_by_category : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }
    
    }
}