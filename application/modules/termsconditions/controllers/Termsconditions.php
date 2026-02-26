<?php

class Termsconditions extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template = 'admin/admin/termsconditions';

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->library('pagination');
        $this->load->library('user_agent');
        $this->load->model('termsconditions_model');
    }
    public function termsandconditions($type = 'r')
    {
        if ($type == 'c') {
            $this->form_validation->set_rules($this->termsconditions_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['title'] = 'Terms&Conditions Add';
                $this->template = 'admin/admin/add_termsconditions';
                $this->data['nav_type'] = 'terms_conditions';
                $this->data['app_details'] = $this->app_details_model->get_all();

                $this->_render_page($this->template, $this->data);
            } else {
                $id = $this->termsconditions_model->insert([
                    'app_details_id' => $this->input->post('app_id'),
                    'page_id' => $this->input->post('page_id'),
                    'title' => $this->input->post('title'),
                    'desc' => $this->input->post('desc'),
                    'created_user_id' => $this->ion_auth->get_user_id()
                ]);

                redirect('terms_conditions/r', 'refresh');
            }
        } elseif ($type == 'r') {
            $this->data['title'] = 'T & C';
            $this->template = 'admin/admin/termsconditions';
            $this->data['nav_type'] = 'terms_conditions';
            $this->data['termsconditions'] = $this->termsconditions_model->get_all();
            $this->data['app_details'] = $this->app_details_model->get_all();
            $this->_render_page($this->template, $this->data);
            // echo json_encode($this->data);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->termsconditions_model->rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $this->termsconditions_model->update([
                    'id' => $this->input->post('id'),
                    'app_details_id' => $this->input->post('app_id'),
                    'page_id' => $this->input->post('page_id'),
                    'title' => $this->input->post('title'),
                    'desc' => $this->input->post('desc'),
                    'updated_user_id' => $this->ion_auth->get_user_id()
                ], 'id');
                redirect('terms_conditions/r', 'refresh');
            }
        } elseif ($type == 'd') {
            $this->termsconditions_model->delete([
                'id' => $this->input->post('id')
            ]);
        } elseif ($type == 'edit') {
            $this->data['title'] = 'Edit TC';
            $this->template = 'admin/admin/edit';
            $this->data['nav_type'] = 'terms_conditions';
            $this->data['type'] = 'termsconditions';
            $this->data['termsconditions'] = $this->termsconditions_model->order_by('id', 'DESC')
                ->where('id', $this->input->get('id'))
                ->get();
            $this->data['app_details'] = $this->app_details_model->get_all();

            $this->_render_page($this->template, $this->data);
        }
    }
}