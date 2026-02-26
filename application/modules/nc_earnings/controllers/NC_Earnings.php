<?php
error_reporting(E_ERROR | E_PARSE);

class Nc_earnings extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $route_Val = $this->router->fetch_class() . '/' . $this->router->fetch_method();

        if ($route_Val == 'NC_Earnings/ecom_EarningsDateFilter') {
            $this->template = 'nc_earnings/ecom_earning';
        } elseif ($route_Val == 'NC_Earnings/EarningsDateFilter') {
            $this->template = 'nc_earnings/pickup_earning';
        } elseif ($route_Val == 'NC_Earnings/day_wise_ecom_earnings') {
            $this->template = 'nc_earnings/day_wise_ecom_earning';
        } elseif ($route_Val == 'NC_Earnings/day_wise_pickup_earnings') {
            $this->template = 'nc_earnings/day_wise_pickup_earning';
        } else {
            $this->template = 'vendorCrm/master';
        }



        $this->load->library('user_agent');
        if (!$this->ion_auth->logged_in())
            redirect('auth/login');

        // Load the nc_earning_model
        $this->load->model('nc_earning_model');
    }


    public function EarningsDateFilter()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('from_date', 'Start Date', 'required');
        $this->form_validation->set_rules('to_date', 'End Date', 'required');

        if ($this->form_validation->run() == FALSE) {

            $this->data['pickupdata'] = $this->nc_earning_model->get_pickup_orders_with_status_508();
        } else {
            $start_date = $this->input->post('from_date');
            $end_date = $this->input->post('to_date');

            $this->data['pickupdata'] = $this->nc_earning_model->get_pickup_orders_with_status_508_by_date_range($start_date, $end_date);
        }
        $this->data['title'] = 'Pickup Earnings';
        $this->data['content'] = 'nc_earnings/pickup_earning';
        $this->data['nav_type'] = 'pickup_earnings';
        $this->_render_page($this->template, $this->data);
    }

    public function ecom_EarningsDateFilter()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('from_date', 'Start Date', 'required');
        $this->form_validation->set_rules('to_date', 'End Date', 'required');

        if ($this->form_validation->run() == FALSE) {

            $this->data['ecomdata'] = $this->nc_earning_model->get_ecom_orders_with_status_508();
        } else {
            $start_date = $this->input->post('from_date');
            $end_date = $this->input->post('to_date');

            $this->data['ecomdata'] = $this->nc_earning_model->get_ecom_orders_with_status_508_by_date_range($start_date, $end_date);
        }
        $this->data['title'] = 'Ecom Earnings';
        $this->data['content'] = 'nc_earnings/ecom_earning';
        $this->data['nav_type'] = 'ecom_earnings';
        $this->_render_page($this->template, $this->data);
    }

    public function day_wise_pickup_earnings()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('from_date', 'Start Date', 'required');
        $this->form_validation->set_rules('to_date', 'End Date', 'required');

        if ($this->form_validation->run() == FALSE) {

            $queryResult = $this->nc_earning_model->get_day_wise_pickup_orders_with_status_508();
            $this->data['pickupdata'] = $queryResult['query_result'];
            $this->data['chartData'] = $queryResult['chart_data'];

        } else {
            $start_date = $this->input->post('from_date');
            $end_date = $this->input->post('to_date');

            $queryResult = $this->nc_earning_model->get_day_wise_pickup_orders_with_status_508_by_date_range($start_date, $end_date);
            $this->data['pickupdata'] = $queryResult['query_result'];
            $this->data['chartData'] = $queryResult['chart_data'];
        }
        $this->data['title'] = 'Day Wise Pickup Earnings';
        $this->data['content'] = 'nc_earnings/day_wise_pickup_earning';
        $this->data['nav_type'] = 'day_wise_pickup_earnings';
        $this->_render_page($this->template, $this->data);
    }


    public function day_wise_ecom_earnings()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('from_date', 'Start Date', 'required');
        $this->form_validation->set_rules('to_date', 'End Date', 'required');

        if ($this->form_validation->run() == FALSE) {

            $queryResult = $this->nc_earning_model->get_day_wise_ecom_orders_with_status_508();
            $this->data['ecomdata'] = $queryResult['query_result'];
            $this->data['chartData'] = $queryResult['chart_data'];

        } else {
            $start_date = $this->input->post('from_date');
            $end_date = $this->input->post('to_date');

            $queryResult = $this->nc_earning_model->get_day_wise_ecom_orders_with_status_508_by_date_range($start_date, $end_date);
            $this->data['ecomdata'] = $queryResult['query_result'];
            $this->data['chartData'] = $queryResult['chart_data'];

        }
        $this->data['title'] = 'Day Wise Ecom Earnings';
        $this->data['content'] = 'nc_earnings/day_wise_ecom_earning';
        $this->data['nav_type'] = 'day_wise_ecom_earnings';
        $this->_render_page($this->template, $this->data);
    }

    public function nc_get_ecom_order_details()
    {
        $date = $this->input->post('date');
        $orders = $this->nc_earning_model->getEcomOrdersByDate($date);
        echo json_encode($orders);
    }

    public function nc_get_pickup_order_details()
    {
        $date = $this->input->post('date');
        $orders = $this->nc_earning_model->getPickupOrdersByDate($date);
        echo json_encode($orders);
    }


}
