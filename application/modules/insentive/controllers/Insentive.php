<?php


class Insentive extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template = 'template/admin/main';
        $this->load->model('delivery_insentive_config_model');
        $this->load->model('delivery_insentive_shift_config_model');
        $this->load->model('delivery_ratings_insentive_config_model');
        $this->load->model('state_model');
        $this->load->model('district_model');
        $this->load->model('constituency_model');
        $this->load->model('shift_model');
        $this->load->library('session');
        $this->load->model('delivery_boy_performance_extraction_model');
    }

    public function manageDelivery_insentive_config()
    {
        $this->data['title'] = 'Delivery Insentives';
        $this->data['content'] = 'insentive/insentive/manage-delivery_insentive_config';
        $this->data['nav_type'] = 'delivery_insentive';
        $this->data['delivery_insentive_configs'] = $this->delivery_insentive_config_model->getAll();
        $this->_render_page($this->template, $this->data);
    }

    public function addDelivery_insentive_config()
    {
        $this->data['title'] = 'Add Delivery Insentive';
        $this->data['content'] = 'insentive/insentive/add-delivery_insentive_config';
        $this->data['nav_type'] = 'delivery_insentive';
        $this->data['state'] = $this->state_model->get_all();
        $this->data['shift'] = $this->shift_model->get_all();
        $this->_render_page($this->template);
    }

    public function addDelivery_insentive_configPost()
    {
        $postData = $this->input->post();
        $data['state'] = $this->input->post('state');
        $data['district'] = $this->input->post('district');
        $data['constituency'] = $this->input->post('constituency');
        $data['status'] = 1;
        $delivery_insentive_config_id = $this->delivery_insentive_config_model->insertData($data);
        $shifts = $this->input->post('shift');
        foreach ($shifts as $k => $shift) {
            $shiftConfig = [];
            $shiftConfig['allowed_delivery_boys_count'] = $postData['allowed_delivery_boys_count_' . $shift];
            $shiftConfig['min_touch_points'] = $postData['min_touch_points_' . $shift];
            $shiftConfig['req_ontime_delivery_percentage'] = $postData['req_ontime_delivery_percentage_' . $shift];
            $shiftConfig['amount_for_addtional_touch_point'] = $postData['amount_for_addtional_touch_point_' . $shift];
            $shiftConfig['max_limit'] = $postData['max_limit_' . $shift];
        $this->delivery_insentive_shift_config_model->upsertData($delivery_insentive_config_id, $shift, $shiftConfig);
        }

        $ratings = $this->input->post('ratings');
        foreach ($ratings as $k => $rating) {
            $ratingConfig = [];
            $ratingConfig['amount'] = $postData['ratings_' . $rating];
            $this->delivery_ratings_insentive_config_model->upsertData($delivery_insentive_config_id, $rating, $ratingConfig);
        }
        $this->session->set_flashdata('success', 'Delivery Insentive Config Created!');
        redirect('delivery_insentive');
    }

    public function editDelivery_insentive_config($delivery_insentive_config_id)
    {
        $this->data['title'] = 'Edit Delivery Insentive';
        $this->data['content'] = 'insentive/insentive/edit-delivery_insentive_config';
        $this->data['nav_type'] = 'delivery_insentive';
        $this->data['delivery_insentive_config_id'] = $delivery_insentive_config_id;
        $this->data['delivery_insentive_config'] = $this->delivery_insentive_config_model->getDataById($delivery_insentive_config_id);
        $this->data['state'] = $this->state_model->get_all();
        $this->data['shift'] = $this->shift_model->get_all();
        $this->_render_page($this->template, $this->data);
    }

    public function editDelivery_insentive_configPost()
    {
        $postData = $this->input->post();
        $delivery_insentive_config_id = $this->input->post('delivery_insentive_config_id');
        $data['state'] = $this->input->post('state_id') ? $this->input->post('state_id') : Null;
        $data['district'] = $this->input->post('district') ? $this->input->post('district') : Null;
        $data['constituency'] = $this->input->post('constituency') ? $this->input->post('constituency') : Null;
        $edit = $this->delivery_insentive_config_model->updateData($delivery_insentive_config_id, $data);
        if ($edit) {
            $shifts = $this->input->post('shift');
            foreach ($shifts as $k => $shift) {
                $shiftConfig = [];
                $shiftConfig['allowed_delivery_boys_count'] = $postData['allowed_delivery_boys_count_' . $shift];
                $shiftConfig['min_touch_points'] = $postData['min_touch_points_' . $shift];
                $shiftConfig['req_ontime_delivery_percentage'] = $postData['req_ontime_delivery_percentage_' . $shift];
                $shiftConfig['amount_for_addtional_touch_point'] = $postData['amount_for_addtional_touch_point_' . $shift];
                $shiftConfig['max_limit'] = $postData['max_limit_' . $shift];
                $this->delivery_insentive_shift_config_model->upsertData($delivery_insentive_config_id, $shift, $shiftConfig);
            }

            $ratings = $this->input->post('ratings');
            foreach ($ratings as $k => $rating) {
                $ratingConfig = [];
                $ratingConfig['amount'] = $postData['ratings_' . $rating];
                $this->delivery_ratings_insentive_config_model->upsertData($delivery_insentive_config_id, $rating, $ratingConfig);
            }

            $this->session->set_flashdata('success', 'Delivery Insentive Config Updated!');
            redirect('delivery_insentive');
        }
    }

    public function viewDelivery_insentive_config($delivery_insentive_config_id)
    {
        $this->data['title'] = 'View Delivery Insentive';
        $this->data['content'] = 'insentive/insentive/view-delivery_insentive_config';
        $this->data['nav_type'] = 'delivery_insentive';
        $this->data['delivery_insentive_config_id'] = $delivery_insentive_config_id;
        $this->data['delivery_insentive_config'] = $this->delivery_insentive_config_model->getDataById($delivery_insentive_config_id);
        $this->_render_page($this->template, $this->data);
    }

    public function changeStatusDelivery_insentive_config($delivery_insentive_config_id)
    {
        $edit = $this->delivery_insentive_config_model->changeStatus($delivery_insentive_config_id);
        $this->session->set_flashdata('success', 'Delivery Insentive Config ' . $edit . ' Successfully');
        redirect('delivery_insentive');
    }

    public function ManagePending_insentive() {
        $this->data['title'] = 'Manage Pending Insentives';
        $this->data['content'] = 'insentive/insentive/manage-pending_insentive';
        $this->data['nav_type'] = 'pending_insentives';
        $this->data['deivery_boy_performances'] = $this->delivery_boy_performance_extraction_model->fetchPerformances();
        $this->data['total_insentive'] = $this->delivery_boy_performance_extraction_model->fetcTotalInsentive();
        $this->_render_page($this->template, $this->data);
    }

    public function Process_insentive() {
        $this->load->model('user_account_model');
        $this->config->load('ion_auth', TRUE);
        $superAdminID = $this->config->item('super_admin_user_id', 'ion_auth');
        $deliveryBoyPerformanceArr = $this->delivery_boy_performance_extraction_model->fetchPerformances();
        $totalAmount = $this->delivery_boy_performance_extraction_model->fetcTotalInsentive();
        $superAdminAccount = $this->user_account_model->getWallet($superAdminID);
        if((float)$totalAmount < (float)$superAdminAccount['wallet']){
            foreach($deliveryBoyPerformanceArr as $key=> $deliveryBoyPerformance){
                $txn_id = 'NC-'.generate_trasaction_no();
                if((float) $deliveryBoyPerformance['amount'] >0){
                    $this->user_model->payment_update($superAdminID, $deliveryBoyPerformance['amount'], 'DEBIT', "wallet", $txn_id);
                    $this->user_model->payment_update($deliveryBoyPerformance['id'], $deliveryBoyPerformance['amount'], 'CREDIT', "wallet", $txn_id);
                }
                $this->delivery_boy_performance_extraction_model->update([
                    'status'=>2
                ], [
                    'delivery_boy_user_id' =>$deliveryBoyPerformance['id'],
                    'status' =>1
                ]);
            }
        }
        redirect('delivery_insentive/pending');
    }
}
