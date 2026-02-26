
<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';
use Firebase\JWT\JWT;

class Delivery extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('delivery_boy_status_model');
        $this->load->model('users_address_model');
        $this->load->model('vendor_list_model');
    }

    /* Delivery Boy App Start */
    /**
     *
     * @author Mahesh
     *         To Update Delivery Boy Status
     */
    public function DeliveryBoyStatus_POST($target = 'r')
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
	   $v = $this->delivery_boy_status_model->where('user_id', $token_data->id)->get();
        if ($target == 'u') {
            //$input = $this->post();
            $_POST = json_decode(file_get_contents("php://input"), TRUE);

            if ($v != '') {
                $id_deal = $this->delivery_boy_status_model->update([
                    'delivery_boy_status' => $_POST['delivery_boy_status']
                ], [
                    'user_id'=>
                    $token_data->id
                ]);
            } else {
                $id_deal = $this->delivery_boy_status_model->insert([
                    'delivery_boy_status' => $_POST['delivery_boy_status'],
                    'user_id' => $token_data->id
                ]);
            }
        } elseif ($target == 'r') {
            $id_deal['status'] = $v['delivery_boy_status'];
            $id_deal['latitude'] = $v['latitude'];
            $id_deal['longitude'] = $v['longitude'];
        }

        $this->set_response_simple(($id_deal == FALSE) ? FALSE : $id_deal, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    public function DeliveryBoyLatLong_POST()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $v = $this->delivery_boy_status_model->where('user_id', $token_data->id)->get();
        
        if ($v != '') {
            $id_deal = $this->delivery_boy_status_model->update([
                'latitude' => $_POST['latitude'],
                'longitude' => $_POST['longitude']
            ], [
                'user_id'=>
                $token_data->id
            ]);
        } else {
            $id_deal = $this->delivery_boy_status_model->insert([
                'latitude' => $_POST['latitude'],
                'longitude' => $_POST['longitude'],
                'user_id' => $token_data->id
            ]);
        }
        $this->set_response_simple(($id_deal == FALSE) ? FALSE : $id_deal, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }
    /* Delivery Boy App End */
}