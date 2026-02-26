<?php


class Master_package_setting extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template = 'template/admin/main';
        $this->load->model('master_package_setting_model');
        $this->load->library('session');
    }

    public function manageMaster_package_settings()
    {
        $this->data['title'] = 'Package Settings';
        $this->data['content'] = 'master_package_setting/master_package_setting/manage-master_package_settings';
        $this->data['nav_type'] = 'master_package_setting';
        $this->data['master_package_settings'] = $this->master_package_setting_model->getAll();
        $this->_render_page($this->template, $this->data);
    }

    public function addMaster_package_settings()
    {
        $this->data['title'] = 'Add Master Package Setting';
        $this->data['content'] = 'master_package_setting/master_package_setting/add-master_package_settings';
        $this->data['nav_type'] = 'master_package_setting';
        $this->_render_page($this->template);
    }

    public function addMaster_package_settingsPost()
    {
        $data['setting_key'] = $this->input->post('setting_key');
        $data['description'] = $this->input->post('description');
        $data['status'] = 1;
        $this->master_package_setting_model->insert($data);
        $this->session->set_flashdata('success', 'Package setting added Successfully');
        redirect('master_package_setting');
    }

    public function editMaster_package_settings($master_package_settings_id)
    {
        $this->data['title'] = 'Edit Master Package Setting';
        $this->data['content'] = 'master_package_setting/master_package_setting/edit-master_package_settings';
        $this->data['nav_type'] = 'master_package_setting';
        $this->data['master_package_settings_id'] = $master_package_settings_id;
        $this->data['master_package_settings'] = $this->master_package_setting_model->getDataById($master_package_settings_id);
        $this->_render_page($this->template, $this->data);
        // $this->load->view('master_package_settings/edit-master_package_settings', $data);
    }

    public function editMaster_package_settingsPost()
    {
        $master_package_settings_id = $this->input->post('master_package_settings_id');
        $data['setting_key'] = $this->input->post('setting_key');
        $data['description'] = $this->input->post('description');
        $edit = $this->master_package_setting_model->update($data, $master_package_settings_id);
        if ($edit) {
            $this->session->set_flashdata('success', 'Master package settings Updated');
            redirect('master_package_setting');
        }
    }

    public function viewMaster_package_settings($master_package_settings_id)
    { 
        $this->data['title'] = 'View Master Package Setting';
        $this->data['content'] = 'master_package_setting/master_package_setting/view-master_package_settings';
        $this->data['nav_type'] = 'master_package_setting';
        $this->data['master_package_settings_id'] = $master_package_settings_id;
        $this->data['master_package_settings'] = $this->master_package_setting_model->getDataById($master_package_settings_id);
        $this->_render_page($this->template, $this->data);
    }

    public function changeStatusMaster_package_settings($master_package_settings_id)
    {
        $edit = $this->master_package_setting_model->changeStatus($master_package_settings_id);
        $this->session->set_flashdata('success', 'master_package_settings ' . $edit . ' Successfully');
        redirect('master_package_setting');
    }
}
