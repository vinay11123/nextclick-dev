<?php
error_reporting(E_ERROR | E_PARSE);
class Reports extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template = 'reports/vendor_agreement';

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->library('pagination');
        $this->load->library('user_agent');
        $this->load->library('form_validation');
        $this->load->model('reports_model');
    }

    public function vendor_agreement_reports($type = 'r')
    {
        $this->data['title'] = 'Reports';
        $this->template = 'reports/vendor_agreement';
        $this->data['nav_type'] = 'reports';

        if ($type === 'r') {
            $this->data['vendorReports'] = $this->reports_model->get_vendor_reports();
        } else if ($type === 'submitted') {
            $typeVal = $this->input->post('q');
            if ($typeVal === 'accepted') {
                $this->data['vendorReports'] = $this->reports_model->get_vendor_reports();
            } else if ($typeVal === 'unaccepted') {
                $this->data['vendorUnacceptedReports'] = $this->reports_model->get_vendor_unaccepted_reports();
            }
        }

        $this->load->view($this->template, $this->data);
    }
    public function sendmail($id)
    {
        $vendorObj = $this->vendor_list_model->where([
            'id' => $id
        ])->get();
        $vendor_data = $this->user_model->where([
            'id' => $vendorObj['vendor_user_id']
        ])->get();
        $data = array(
            'vendor_name'        => $vendorObj['name']
        );
        $attched_file= $_SERVER["DOCUMENT_ROOT"]."/exports/vendor_agreement_pdfs/".$vendorObj['agreement_accepted_file'];
        $message = $this->load->view('vendor_agrement_tem', $data, true);
        $this->email->clear();
        $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
        $this->email->to($vendor_data['email']);
        $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - Acknowledgement of Agreement Acceptance');
        $this->email->message($message);
        $this->email->attach($attched_file);
        $this->email->send();

        $this->email->send();

        $this->data['title'] = 'Reports';
        $this->template = 'reports/vendor_agreement';
        $this->data['nav_type'] = 'reports';
        $this->data['vendorReports'] = $this->reports_model->get_vendor_reports();
        $this->load->view($this->template, $this->data);
    }
}
