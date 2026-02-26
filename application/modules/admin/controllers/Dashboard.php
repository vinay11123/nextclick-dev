<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Dashboard.php
 *
 * @package     CI-ACL
 * @author      Steve Goodwin
 * @copyright   2015 Plumps Creative Limited
 */
class Dashboard extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->template = 'template/admin/main';
        
        if (! $this->ion_auth->logged_in())
            redirect('auth/login');
        
            $this->load->model('vendor_list_model');
            $this->load->model('wallet_transaction_model');
            $this->load->model('vendor_leads_model');
            $this->load->model('user_model');
            $this->load->model('pickupcategory_model');
    }

    public function index()
    {
        $this->data['users_groups']           =   $this->ion_auth->get_users_groups()->result();
        $this->data['users_permissions']      =   $this->ion_auth_acl->build_acl();
        $this->data['title'] = 'Dashboard';
        $this->data['content'] = 'admin/dashboard';
        $this->data['nav_type'] = 'dashboard';
        $this->_render_page($this->template, $this->data);
    }
    
    public function sample(){
        $this->data['title'] = 'Sample';
        $this->data['content'] = 'admin/sample';
        $this->_render_page($this->template, $this->data);
    }
    
    public function wallet(){
        $this->data['title'] = 'Admin Wallet Transactions';
        $this->data['content'] = 'admin/admin/wallet';
        $this->data['transactions'] = $this->wallet_transaction_model->where('user_id', $this->ion_auth->get_user_id() )->order_by('id', 'DESC')->get_all();
        $this->_render_page($this->template, $this->data);
    }
    
    public function lead_management($type = 'r'){
        if($type == 'r'){
            $this->data['title'] = 'Sample';
            $this->data['content'] = 'admin/admin/lead_management';
            $this->data['vendor_leads'] = $this->vendor_leads_model->order_by('id', 'DESC')->with_lead('fields: id, user_id')->where('vendor_id', $this->ion_auth->get_user_id())->get_all();
			
			if(! empty($this->data['vendor_leads']))   {
            foreach($this->data['vendor_leads'] as $key => $lead){
                $this->data['vendor_leads'][$key]['lead']['user'] = $this->user_model->get($lead['lead']['user_id']);
            }
			}
			
            $this->_render_page($this->template, $this->data);
        }
    }

    public function pickanddropcategories($type = 'r'){
        if($type == 'r'){
            $this->data['title'] = 'Category';
            $this->data['content'] = 'admin/admin/pickanddropcategories';
            $this->data['nav_type'] = 'pickanddropcategories';
            $this->data['categories'] = $this->pickupcategory_model
                ->order_by('name', 'ASC')
                ->get_all();
            $this->_render_page($this->template, $this->data);
        }
        else if($type == 'u'){
            $postData = $this->input->post();

            $this->pickupcategory_model->update([
                'is_pickup_allowed' =>0
            ]);//reseta all

            foreach($postData as $catID=>$val){
                $this->pickupcategory_model->update([
                    'is_pickup_allowed' =>1
                ], [
                    'id' => $catID
                ]);
            }
            redirect('pickanddropcategories/r', 'refresh');
        }
        else if($type == 'uc'){
            $postData = $this->input->post();

            $this->pickupcategory_model->update([
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'desc' => $this->input->post('desc'),
                'terms' => $this->input->post('terms'),
                'flat_distance' => $this->input->post('flatdistance'),
                'flat_rate' => $this->input->post('rlatrate'),
                'per_km' => $this->input->post('per_km'),
                'nc_flat_rate' => $this->input->post('nc_flat_rate'),
                'nc_per_km' => $this->input->post('nc_per_km')
            ], $this->input->post('id'));

           
            if ($_FILES['file']['name'] !== '') {
                $path = $_FILES['file']['name'];
                if (!file_exists('uploads/' . 'pickupanddropcategory' . '_image/')) {
                    mkdir('uploads/' . 'pickupanddropcategory' . '_image/', 0777, true);
                }
                if (file_exists('uploads/' . 'pickupanddropcategory' . '_image/' . 'pickupanddropcategory' . '_' . $this->input->post('id') . '.jpg')) {
                    unlink('uploads/' . 'pickupanddropcategory' . '_image/' . 'pickupanddropcategory' . '_' . $this->input->post('id') . '.jpg');
                }
                move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . 'pickupanddropcategory' . '_image/' . 'pickupanddropcategory' . '_' . $this->input->post('id') . '.jpg');
            }
            
            $this->session->set_flashdata('upload_status', 'pickupanddrop category has been updated successfully');
            redirect('pickanddropcategories/r', 'refresh');
        }
        else if($type == 'c'){
            $this->form_validation->set_rules($this->pickupcategory_model->rules);
            if (empty($_FILES['file']['name'])) {
                $this->form_validation->set_rules('file', 'Category Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Category';
                $this->data['content'] = 'master/add_pickupanddropcategory';
                $this->data['nav_type'] = 'category';
                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->pickupcategory_model->insert([
                    'name' => $this->input->post('name'),
                    'desc' => $this->input->post('desc'),
                    'terms' => $this->input->post('terms'),
                    'flat_distance' => $this->input->post('flatdistance'),
                    'flat_rate' => $this->input->post('rlatrate'),
                    'per_km' => $this->input->post('per_km'),
                    'nc_flat_rate' => $this->input->post('nc_flat_rate'),
                    'nc_per_km' => $this->input->post('nc_per_km')
                ]);


                $path = $_FILES['file']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $this->file_up("file", "pickupanddropcategory", $id, '', 'no');
                $this->session->set_flashdata('upload_status', 'Pickup and drop category has been added successfully');
                redirect('pickanddropcategories/r', 'refresh');
            }
        }
        elseif ($type == 'd') {
            $this->pickupcategory_model->delete([
                'id' => $this->input->get('id')
            ]);
            $this->session->set_flashdata('delete_status', 'pickupanddrop category has been deleted successfully');
            redirect('pickanddropcategories/r', 'refresh');
        } elseif ($type == 'edit') {

            $this->data['title'] = 'Edit Category';
            $this->data['content'] = 'master/edit_pudcategory';
            $this->data['nav_type'] = 'category';
            $this->data['type'] = 'category';
            $this->data['category'] = $this->pickupcategory_model->where('id', $this->input->get('id'))
                ->get();

            $this->_render_page($this->template, $this->data);

            
        }
    }
    

}
?>