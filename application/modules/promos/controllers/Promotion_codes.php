<?php
class Promotion_codes extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->template = 'template/admin/main';
        if (! $this->ion_auth->logged_in()  || ! $this->ion_auth->is_admin())
            redirect('auth/login');
            $this->load->model('category_model');
            $this->load->model('sub_category_model');
            $this->load->model('food_item_model');
            $this->load->model('user_model');
            $this->load->model('vendor_list_model');
            $this->load->model('promos_model');
            $this->load->model('promotion_code_products_model');
            $this->load->model('vendor_product_variant_model');
            $this->load->model('food_menu_model');
            $this->load->model('brand_model');

    }
    public function promotion_list($type = 'r')
    {
       if ($type == 'c') {
             $this->form_validation->set_rules($this->promos_model->rules['create_rules']);
                if ($this->form_validation->run() == FALSE) {
                    $this->promotion_list('r');
                } else {
                    $is_inserted=$this->promos_model->insert([
                        'promo_title' => empty($this->input->post('promo_title')) ? NULL : $this->input->post('promo_title'),
                        'promo_code' => empty($this->input->post('promo_code')) ? NULL : $this->input->post('promo_code'),
                        'promo_type' => 1,
                        'valid_from' => empty($this->input->post('start_date')) ? null : $this->input->post('start_date'),
                        'valid_to' => empty($this->input->post('end_date')) ? NULL : $this->input->post('end_date'),
                        'category' => empty($this->input->post('cat_id')) ? NULL : $this->input->post('cat_id'),
                        'shop_by_category' => empty($this->input->post('sub_cat_id')) ? NULL : $this->input->post('sub_cat_id'),
                        'menu' => empty($this->input->post('menu_id')) ? NULL : $this->input->post('menu_id'),
                        'brand' => empty($this->input->post('brand_id')) ? NULL : $this->input->post('brand_id') ,
                        'discount_type' => empty($this->input->post('discount_type')) ? NULL : $this->input->post('discount_type'),
                        'discount' => empty($this->input->post('discount')) ? NULL : $this->input->post('discount'),
                        'uses' => empty($this->input->post('uses')) ? NULL : $this->input->post('uses') ,
                    ]);
                    if(! empty($this->input->post('item_id'))){
                        $data['variant'] = $this->promotion_code_products_model->insert([
                                'promotion_code_id' => $is_inserted,
                                'product_id' => $this->input->post('item_id'),
                                'vendor_product_variant_id' => $this->input->post('sec_list')
                            ]);
                        
                    }

                    redirect('promotion_codes/r', 'refresh');
                }
            } elseif ($type == 'r') {
                $this->data['title'] = 'Promotion Codes';
                $this->data['content'] = 'promos/promotion_codes/promotion_codes';
                $this->data['nav_type'] = 'promotion_codes';
                $this->data['products'] = $this->promotion_code_products_model->fields('id,product_id,vendor_product_variant_id')->get_all();
                $this->data['vendors'] = $this->vendor_list_model->fields('id,name,vendor_user_id,status')->order_by('id', 'DESC')->where(['status'=> 1])->get_all();
                $this->data['promos'] = $this->promos_model->fields('id,promo_title,promo_code,promo_type,valid_from,valid_to,discount_type,discount,uses,status')->order_by('id', 'DESC')->where(['status !='=> 0])->get_all();
                $this->data['promos'] = $this->promos_model->fields('id,promo_title,promo_code,promo_type,valid_from,valid_to,discount_type,discount,uses,status')->order_by('id', 'DESC')->where(['status !='=> 0])->get_all();
                if ($this->ion_auth->is_admin()) {
                $this->data['categories'] = $this->category_model->fields('id,name,desc')->get_all();
                $cat_data = $this->data['categories'];
               
                } else {
                    $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                    ->get();
                    
                }
               $this->_render_page($this->template, $this->data);
            }  elseif ($type == 'edit'){
                $this->data['title'] = 'Edit Promotion Codes';
                $this->data['nav_type'] = 'promotion_codes';
                $this->data['content'] = 'promos/promotion_codes/edit';
                $this->data['promos'] = $this->promos_model->with_promo_products('fields: product_id,vendor_product_variant_id')->where('id', $this->input->get('id'))->get();
                $this->data['products'] = $this->food_item_model->fields('id,name')->where('id', $this->data['promos']['promo_products'][0]['product_id'])->get_all();
                $this->data['variants'] = $this->vendor_product_variant_model->with_section_item('fields:id, name')->where('id', $this->data['promos']['promo_products'][0]['vendor_product_variant_id'])->get();
                if ($this->ion_auth->is_admin()) {
                    $this->data['categories'] = $this->category_model->fields('id,name,desc')->get_all();
                    $cat_data = $this->data['categories'];
                    
                    } else {
                        $cat_id = $this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())
                        ->get();
                    
                }
                $this->data['subcategories'] = $this->sub_category_model->fields('id,name')->where('type', 2)->get_all();
                $this->data['menus'] = $this->food_menu_model->fields('id,name')->order_by('id', 'DESC')->get_all();
                $this->data['brands'] = $this->brand_model->fields('id,name')->order_by('id', 'DESC')->get_all(); 
                
                $this->_render_page($this->template, $this->data);
                
            } elseif ($type == 'u') {
                $token_data = $this->ion_auth->get_user_id();
                $this->form_validation->set_rules($this->promos_model->rules['update_rules']);
                if ($this->form_validation->run() == FALSE) {
                    echo validation_errors();
                } else {
                    $update_array = $this->promos_model->update([
                        'id' => $this->input->post('id'),
                        'promo_title' => empty($this->input->post('promo_title')) ? NULL : $this->input->post('promo_title'),
                        'promo_code' => empty($this->input->post('promo_code')) ? NULL : $this->input->post('promo_code'),
                        'promo_type' => 1 ,
                        'valid_from' => empty($this->input->post('start_date')) ? null : $this->input->post('start_date'),
                        'valid_to' => empty($this->input->post('end_date')) ? NULL : $this->input->post('end_date'),
                        'category' => empty($this->input->post('cat_id')) ? NULL : $this->input->post('cat_id'),
                        'shop_by_category' => empty($this->input->post('sub_cat_id')) ? NULL : $this->input->post('sub_cat_id'),
                        'menu' => empty($this->input->post('menu_id')) ? NULL : $this->input->post('menu_id'),
                        'brand' => empty($this->input->post('brand_id')) ? NULL : $this->input->post('brand_id') ,
                        //'promo_image' => empty($this->input->post('promo_image')) ? NULL : $this->input->post('promo_image'),
                        'discount_type' => empty($this->input->post('discount_type')) ? NULL : $this->input->post('discount_type'),
                        'discount' => empty($this->input->post('discount')) ? NULL : $this->input->post('discount'),
                        'uses' => empty($this->input->post('uses')) ? NULL : $this->input->post('uses') ,
                        'updated_user_id' =>  $token_data->id
                    ], 'id');
                    if(! empty($this->input->post('item_id'))){
                        $data['variant'] = $this->promotion_code_products_model->update([
                                'promotion_code_id' => empty($this->input->post('id')) ? NULL : $this->input->post('id'),
                                'product_id' => empty($this->input->post('item_id')) ? NULL : $this->input->post('item_id'),
                                'vendor_product_variant_id' => empty($this->input->post('sec_list')) ? NULL : $this->input->post('sec_list')
                            ]);
                        
                    }
                    redirect('promotion_codes/r', 'refresh');
                }
            }elseif ($type == 'd') {

                $this->promos_model->delete([
                    'id' => $this->input->post('id')
                ]);

            } 
    }
    
    
    public function check_promo_code_unique($value='')
    {
        $validation = $this->db->get_where('promo_codes',array('promo_code'=>$value))->num_rows();
            if($validation != 0){
                $this->form_validation->set_message('check_promo_code_unique','Promo Code Existed');
                return false;
            }
            return true;
    }
    
}