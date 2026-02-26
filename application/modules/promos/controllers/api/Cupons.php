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
        $this->load->model('cupons_model');
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
						/*foreach ($promo_codes as $k => $v){
                        $is_available = $this->used_promo_codes_model->where([
                            'promo_id' => $promo_codes[$k]['id'],
                            'user_id' => $token_data->id,
                        ])->get();
                        $promo_codes[$k]['is_scratched'] = (empty($is_available)) ? 0 : 1;
						$promo_codes[$k]['is_used'] = $is_available['status'] == 1 ? 1 : 0;
                    }*/
					   $this->set_response_simple($cupons, 'Success..!', REST_Controller::HTTP_OK, TRUE);

					}
					else {
						$this->set_response_simple(NULL, 'No cupons available..!', REST_Controller::HTTP_OK, TRUE);
					}
            }
        
    }
	
}
