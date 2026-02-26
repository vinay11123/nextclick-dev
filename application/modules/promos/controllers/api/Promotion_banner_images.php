<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Promotion_banner_images extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('app_details_model');
        $this->load->model('promotion_banner_model');
        $this->load->model('promotion_banner_images_model');
        $this->load->model('category_model');
        $this->load->model('vendor_list_model');
        
    }
    /**
     * @desc To  Promotion banner images Crud
     * @author : tejaswini
     */
    public function promotion_banner_images_post(string $type = 'r', $target = NULL)
    {
       if($type == 'r'){
			$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
			$token_data =$this->validate_token($authorization_exp[1]);
		
            $cat_id = $this->vendor_list_model->fields('category_id')->where('vendor_user_id', $token_data->id)->get();
            if(! empty($cat_id)){
                $banner_images = $this->promotion_banner_images_model->where('cat_id', $cat_id['category_id'])->get_all();
                foreach ($banner_images as $key => $image) {
                    $banner_images[$key]['image'] = base_url() . 'uploads/promotion_banner_suggestion_image/promotion_banner_suggestion_' . $image['id'] . '.jpg';
                }
            $this->set_response_simple($banner_images ? $banner_images : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
            }
        }
    }
}