<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Return_policies extends MY_REST_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('return_policies_model');
    }

    /**
     * @desc get api to retrieve return policies data
     * @author Tejaswini
     * @date 06/09/2021
     *  */

    public function return_policies_get(){
        $sub_cat_id = $this->input->get('sub_cat_id');
        $menu_id = $this->input->get('menu_id');
        if(empty($menu_id)){
            $return_policies = $this->return_policies_model->where(['sub_cat_id' => $this->input->get('sub_cat_id')])->get_all();
            $this->set_response_simple($return_policies ? $return_policies : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }else {
            $return_policies = $this->return_policies_model->where(['sub_cat_id' => $this->input->get('sub_cat_id'),'menu_id' =>$this->input->get('menu_id')])->get();
            $this->set_response_simple($return_policies ? $return_policies : NULL, 'success..!', REST_Controller::HTTP_OK, TRUE);
        }
            
    }
    
    

}
