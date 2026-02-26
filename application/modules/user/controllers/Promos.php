
<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';
use Firebase\JWT\JWT;

class Promos extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('vendor_list_model');
        $this->load->model('promos_model');
        $this->load->model('promo_vendors_model');
        $this->load->model('promos_used_model');
    }

    /* GET Promos */
    /**
     * To get list of Promos and based on vendors
     *
     * @author Mahesh
     * @param string $target
     */
    public function Promos_get()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $res = $data = array();
        $where = 'status = 1 AND valid_to >= "' . date('Y-m-d') . '"';
        if ((isset($_GET['vendor_id'])) && ($_GET['vendor_id'] != '')) {
            $data = $this->promo_vendors_model->with_promos('fields:id,promo_title,promo_code,promo_type,valid_from,valid_to,discount_type,discount,uses,status')
                ->where('vendor_id', $_GET['vendor_id'])
                ->get_all();
            /* $data = $this->promos_model->fields('id,promo_type,promo_code,promo_type,promo_label,valid_from,valid_to,discount_type,discount,uses,status')->order_by('id', 'DESC')->where(['status'=> 1])->get_all(); */
        } else {
            $data = $this->promos_model->fields('id,promo_title,promo_code,promo_type,valid_from,valid_to,discount_type,discount,uses,status')
                ->order_by('id', 'DESC')
                ->where($where)
                ->get_all();
        }
        if (! empty($data)) {
            foreach ($data as $d) {
                $us = $this->promos_used_model->where([
                    'user_id' => $token_data->id,
                    'promo_id' => $d['promo_id']
                ])->get_all();
                if ($us == '' || count($us) <= $d['promos']['uses']) {
                    $res[] = $d;
                }
            }
        }

        $this->set_response_simple(($res == FALSE) ? FALSE : $res, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * Food Order
     *
     * To Manage Food Order
     *
     * @author Mahesh
     * @param
     *            string
     */
    public function CheckPromo_POST()
    {
		$authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $data = array();
        if ((isset($_POST['promo_code'])) && ($_POST['promo_code'] != '')) {
            $vendor_id = $_POST['vendor_id'];
            $coupon = $_POST['promo_code'];
            $total = $_POST['total'];
            $where = 'status = 1 AND promo_code = "' . $coupon . '" AND valid_from <= "' . date('Y-m-d') . '" AND valid_to >= "' . date('Y-m-d') . '"';
            // echo $where;die;
            $this->db->where($where);
            $promo_code = $this->promos_model->get();
            if ($promo_code) {
                /*
                 * if($promo_code['promo_type']==1 || $promo_code['promo_type'] == 2){
                 * if($promo_code['disc'])
                 * }else
                 */
                $stat = 0;
                if ($promo_code['promo_type'] == 3) {
                    $ven_pro = $this->promo_vendors_model->where([
                        'promo_id' => $promo_code['id'],
                        'vendor_id' => $vendor_id
                    ])->get();
                    if ($ven_pro) {
                        $stat = 1;
                    }
                } elseif ($promo_code['promo_type'] == 1 || $promo_code['promo_type'] == 2) {
                    $stat = 1;
                }

                if ($stat == 1) {
                    if ($promo_code['discount_type'] == 1) {
                        $dis_amount = $promo_code['discount'];
                    } elseif ($promo_code['discount_type'] == 2) {
                        $dis_amount = (($total * $promo_code['discount']) / 100);
                    }
                    $data['promo_id'] = $promo_code['id'];
                    $data['promo_code'] = $coupon;
                    $data['discount'] = $dis_amount;
                    $data['status'] = 1;
                }
            }
        }
        $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

      

}