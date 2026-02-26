<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';
use Firebase\JWT\JWT;

class Utility extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('notifications_model');
        $this->load->model('app_details_model');
        $this->load->model('notification_type_model');
        $this->load->model('food_item_model');
        $this->load->model('vendor_product_variant_model');
        $this->load->model('notifications_model');
        $this->load->model('vendor_list_model');
        $this->load->model('sub_category_model');
    }

    public function products_count_get(){
        $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
		if(! $this->ion_auth->in_group('admin', $token_data->id)){
            $min_stock = $this->db->query("SELECT sum(min_stock) as min_stock FROM ecom_settings where created_user_id = ".$token_data->id.";")->result_array()[0]['min_stock'];
        }else {
            $min_stock = 0;
        }
        $vendor_cat_id = $this->vendor_list_model->where('vendor_user_id', $token_data->id)->get()['category_id'];
        $sub_cat_ids = $this->sub_category_model->fields('id')->where('cat_id', $vendor_cat_id)->where('type', 2)->get_all();
        $sub_cat_id = implode(',', array_column($sub_cat_ids, 'id'));
        $data['catalogue_count'] = $this->db->query("select count(*) as catalogue_count from food_item where status = 1 and availability = 1 and sub_cat_id in(".$sub_cat_id.") and deleted_at is null;")->result_array()[0]['catalogue_count'];
        $data['inventory_instock_count'] = $this->db->query("select count(*) as inventory_count from vendor_product_variants where stock > ".$min_stock." and vendor_user_id = ".$token_data->id." and deleted_at is null;")->result_array()[0]['inventory_count'];
        $data['inventory_outofstock_count'] =$this->db->query("select count(*) as inventory_count from vendor_product_variants where stock <= ".$min_stock." and vendor_user_id = ".$token_data->id." and deleted_at is null;")->result_array()[0]['inventory_count'];
        $data['pendig_count'] = $this->db->query("select count(*) as pending_count from food_item where status = 3 and created_user_id = ".$token_data->id." and deleted_at is null;")->result_array()[0]['pending_count'];
        $data['approved_count'] = $this->db->query("select count(*) as approved_count from food_item where status = 2 and created_user_id = ".$token_data->id." and deleted_at is null;")->result_array()[0]['approved_count'];
        $this->set_response_simple($data, 'Success', REST_Controller::HTTP_OK, TRUE);
    }

    public function notification_count_get(){
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $app_details = $this->app_details_model->where('app_id', $this->input->get_request_header('APP_ID'))->get();
        $notif_type_id = $this->input->get('notification_type_id');
        if (empty($notif_type_id)) {
            $list_of_notif = $this->notifications_model->where(['app_details_id' => $app_details['id'],'notified_user_id' => $token_data->id,'status'=>1])->get_all();
            if(! empty($list_of_notif)){
                $list_of_notif['count'] = count($list_of_notif);
            }
            $this->set_response_simple($list_of_notif ? $list_of_notif : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        } else {
            $notif = $this->notifications_model->where('notification_type_id', $notif_type_id)->where('status',1)->get();
            $this->set_response_simple($notif ? $notif : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
}
