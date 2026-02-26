<?php


class Shift extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->template = 'template/admin/main';
        $this->load->model('shift_model');
        $this->load->library('session');
    }

    public function manageShifts() { 
        $this->data['title'] = 'Shifts List';
        $this->data['content'] = 'shift/shift/manage-shifts';
        $this->data['nav_type'] = 'shift_filter';
        $this->data['shifts'] = $this->shift_model->getAll();
        $this->_render_page($this->template, $this->data);
    }

    public function addShifts() {
        $this->data['title'] = 'Add Shift';
        $this->data['content'] = 'shift/shift/add-shifts';
        $this->data['nav_type'] = 'shift_filter';
        $this->_render_page($this->template);
        // $this->load->view('shifts/add-shifts');
    }

    public function addShiftsPost() {
        $data['name'] = $this->input->post('name');
        $data['from'] = $this->input->post('from');
        $data['duration'] = $this->input->post('duration');
        $data['min_duration'] = (float) $this->input->post('min_duration');
        $data['min_duration'] = (int) ($data['min_duration']*60);
    $this->shift_model->insert($data);
        $this->session->set_flashdata('success', 'Shift added Successfully');
        redirect('shift');
    }

    public function editShifts($shifts_id) {
        $this->data['title'] = 'Edit Shift';
        $this->data['content'] = 'shift/shift/edit-shifts';
        $this->data['nav_type'] = 'shift_filter';
        $this->data['shift_id'] = $shifts_id;
        $this->data['shift'] = $this->shift_model->getDataById($shifts_id);
        $this->_render_page($this->template, $this->data);
    }

    public function editShiftsPost() {
        $shift_id = $this->input->post('shift_id');
        $data['name'] = $this->input->post('name');
        $data['from'] = $this->input->post('from');
        $data['duration'] = $this->input->post('duration');
        $data['min_duration'] = $this->input->post('min_duration');
        $data['status'] = $this->input->post('status');
    $edit = $this->shift_model->update($data, $shift_id);
        if ($edit) {
            $this->session->set_flashdata('success', 'Shift Updated');
            redirect('shift');
        }
    }

    public function viewShifts($shift_id) {
        $this->data['title'] = 'View Shift';
        $this->data['content'] = 'shift/shift/view-shifts';
        $this->data['nav_type'] = 'shift_filter';
        $this->data['shift_id'] = $shift_id;
        $this->data['shift'] = $this->shift_model->getDataById($shift_id);
        $this->_render_page($this->template, $this->data);
    }

    public function changeStatusShifts($shifts_id) {
        $edit = $this->shift_model->changeStatus($shifts_id);
        $this->session->set_flashdata('success', 'shifts '.$edit.' Successfully');
        redirect('shift');
    }
    
}