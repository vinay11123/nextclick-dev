<?php
error_reporting(E_ERROR | E_PARSE);
class Agreements extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template = 'agreements/agreement';

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->library('pagination');
        $this->load->library('user_agent');
        $this->load->library('form_validation');
        $this->load->model('agreement_model');
    }

    public function agreement_details($type = 'r')
    {

        if ($type == 'c') {

            // Set validation rules
            $this->form_validation->set_rules('app_id', 'App ID', 'trim|required');
            $this->form_validation->set_rules('title', 'Title', 'trim|required|callback_check_unique');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');


            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Terms&Conditions Add';
                $this->template = 'agreements/add_agreement';
                $this->data['nav_type'] = 'agreements';
                $this->data['app_details'] = $this->app_details_model->get_all();
                $this->_render_page($this->template, $this->data);
            } else {

                $this->data['insert_query'] = $this->agreement_model->insert_agreement();

                if ($this->data['insert_query'] == TRUE) {
                    redirect('agreements/r', 'refresh');
                }

            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'Agreements';
            $this->template = 'agreements/agreement';
            $this->data['nav_type'] = 'agreements';
            $this->data['aggrementDetails'] = $this->agreement_model->get_agreement_details();
            $this->load->view($this->template, $this->data);
        } 
        // elseif ($type == 'edit') {
        //     $this->data['title'] = 'Edit Agreement';
        //     $this->template = 'agreements/edit_agreement';
        //     $this->data['nav_type'] = 'agreements';
        //     $this->data['aggrementDetails'] = $this->agreement_model->order_by('id', 'DESC')
        //         ->where('id', $this->input->get('id'))
        //         ->get();
        //     $this->data['app_details'] = $this->app_details_model->get_all();

        //     $this->_render_page($this->template, $this->data);
        // } elseif ($type == 'u') {
        //     $this->form_validation->set_rules('app_id', 'App ID', 'trim|required');
        //     $this->form_validation->set_rules('title', 'Title', 'trim|required');
        //     $this->form_validation->set_rules('description', 'Description', 'trim|required');

        //     if ($this->form_validation->run() == FALSE) {
        //         $this->template = 'agreements/edit_agreement';
        //         $this->data['nav_type'] = 'agreements';
        //         $this->data['aggrementDetails'] = $this->agreement_model->order_by('id', 'DESC')
        //             ->where('id', $this->input->post('id'))
        //             ->get();
        //         $this->data['app_details'] = $this->app_details_model->get_all();

        //         $this->_render_page($this->template, $this->data);
        //     } else {
        //         $this->agreement_model->update([
        //             'id' => $this->input->post('id'),
        //             'app_details_id' => $this->input->post('app_id'),
        //             'title' => $this->input->post('title'),
        //             'description' => $this->input->post('description'),
        //             'updated_user_id' => $this->ion_auth->get_user_id()
        //         ], 'id');
        //         redirect('agreements/r', 'refresh');
        //     }
        // } elseif ($type == 'd') {
        //     $this->agreement_model->delete([
        //         'id' => $this->input->get('id')
        //     ]);
        //     redirect('agreements/r', 'refresh');
        // }
    }

    public function check_unique()
    {
        $app_id = $this->input->post('app_id');
        $title = $this->input->post('title');
        $existing_record = $this->agreement_model->uniqueCheck($app_id, $title);

        if ($existing_record == FALSE) {
            $this->form_validation->set_message('check_unique', 'The {field} already exists for this App Type.');
            return FALSE;
        } else {
            return TRUE;
        }
    }


    public function update_status()
    {
        $agreementId = $this->input->post('agreementId');
        $newStatus = $this->input->post('newStatus');
        $updatedUserId = $this->ion_auth->get_user_id();

        $this->agreement_model->update([
            'status' => $newStatus,
            'updated_user_id' => $updatedUserId
        ], $agreementId);

        $response = array(
            'status' => 'success'
        );

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

}





