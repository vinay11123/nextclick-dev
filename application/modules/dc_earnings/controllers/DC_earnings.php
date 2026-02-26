<?php
error_reporting(E_ERROR | E_PARSE);

class Dc_earnings extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $route_Val = $this->router->fetch_class() . '/' . $this->router->fetch_method();

        if ($route_Val == 'DC_earnings/delivery_ecom_EarningsDateFilter') {
            $this->template = 'dc_earnings/delivery_ecom_earning';
        } elseif ($route_Val == 'DC_earnings/delivery_pickup_EarningsDateFilter') {
            $this->template = 'dc_earnings/delivery_pickup_earning';
        } elseif ($route_Val == 'DC_earnings/day_wise_delivery_ecom_earnings') {
            $this->template = 'dc_earnings/day_wise_delivery_ecom_earning';
        } elseif ($route_Val == 'DC_earnings/day_wise_delivery_pickup_earnings') {
            $this->template = 'dc_earnings/day_wise_delivery_pickup_earning';
        } else {
            $this->template = 'vendorCrm/master';
        }

        $this->load->library('user_agent');
        if (!$this->ion_auth->logged_in())
            redirect('auth/login');

        $this->load->model('dc_earning_model');
    }

    public function delivery_pickup_EarningsDateFilter()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('from_date', 'Start Date', 'required');
        $this->form_validation->set_rules('to_date', 'End Date', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->data['title'] = 'Pickup Earnings';
            $this->data['content'] = 'dc_earnings/delivery_pickup_earning';
            $this->data['nav_type'] = 'delivery_pickup_earnings';

            $this->data['pickupdata'] = $this->dc_earning_model->get_delivery_pickup_orders_with_status_508();
            $this->data['captaindata'] = $this->dc_earning_model->delivery_captain();
        } else {
            $start_date = $this->input->post('from_date');
            $end_date = $this->input->post('to_date');
            $delivery_captain_id = $this->input->post('delivery_captain_id');

            $this->data['pickupdata'] = $this->dc_earning_model->get_delivery_pickup_orders_with_status_508_by_date_range($start_date, $end_date, $delivery_captain_id);
            $this->data['captaindata'] = $this->dc_earning_model->delivery_captain();
        }

        $this->data['title'] = 'Pickup Earnings';
        $this->data['content'] = 'dc_earnings/delivery_pickup_earning';
        $this->data['nav_type'] = 'delivery_pickup_earnings';
        $this->_render_page($this->template, $this->data);
    }

    public function delivery_ecom_EarningsDateFilter()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('from_date', 'Start Date', 'required');
        $this->form_validation->set_rules('to_date', 'End Date', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->data['title'] = 'Ecom Earnings';
            $this->data['content'] = 'dc_earnings/delivery_ecom_earning';
            $this->data['nav_type'] = 'delivery_ecom_earnings';

            $this->data['ecomdata'] = $this->dc_earning_model->get_delivery_ecom_orders_with_status_508();
            $this->data['captaindata'] = $this->dc_earning_model->delivery_captain();
        } else {
            $start_date = $this->input->post('from_date');
            $end_date = $this->input->post('to_date');
            $delivery_captain_id = $this->input->post('delivery_captain_id');

            $this->data['ecomdata'] = $this->dc_earning_model->get_delivery_ecom_orders_with_status_508_by_date_range($start_date, $end_date, $delivery_captain_id);
            $this->data['captaindata'] = $this->dc_earning_model->delivery_captain();
        }

        $this->data['title'] = 'Ecom Earnings';
        $this->data['content'] = 'dc_earnings/delivery_ecom_earning';
        $this->data['nav_type'] = 'delivery_ecom_earnings';
        $this->_render_page($this->template, $this->data);
    }

    public function day_wise_delivery_pickup_earnings()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('from_date', 'Start Date', 'required');
        $this->form_validation->set_rules('to_date', 'End Date', 'required');

        if ($this->form_validation->run() == FALSE) {

            $queryResult = $this->dc_earning_model->get_day_wise_delivery_pickup_orders_with_status_508();
            $this->data['pickupdata'] = $queryResult['query_result'];
            $this->data['captaindata'] = $this->dc_earning_model->delivery_captain();
            $this->data['chartData'] = $queryResult['chart_data'];
            $this->data['day_chart_data'] = $queryResult['day_chart_data'];
            $this->data['captain_chart_data'] = $queryResult['captain_chart_data'];
            $this->data['day_captain_chart_data'] = $queryResult['day_captain_chart_data'];
            $this->data['dataLabelsStacked'] = $queryResult['dataLabelsStacked'];
            $this->data['dataDataSetStacked'] = $queryResult['dataDataSetStacked'];


        } else {
            $start_date = $this->input->post('from_date');
            $end_date = $this->input->post('to_date');
            $delivery_captain_id = $this->input->post('delivery_captain_id');

            $queryResult = $this->dc_earning_model->get_day_wise_delivery_pickup_orders_with_status_508_by_date_range($start_date, $end_date, $delivery_captain_id);
            $this->data['pickupdata'] = $queryResult['query_result'];
            $this->data['captaindata'] = $this->dc_earning_model->delivery_captain();
            $this->data['chartData'] = $queryResult['chart_data'];
            $this->data['day_chart_data'] = $queryResult['day_chart_data'];
            $this->data['captain_chart_data'] = $queryResult['captain_chart_data'];
            $this->data['day_captain_chart_data'] = $queryResult['day_captain_chart_data'];
            $this->data['dataLabelsStacked'] = $queryResult['dataLabelsStacked'];
            $this->data['dataDataSetStacked'] = $queryResult['dataDataSetStacked'];

        }
        $this->data['title'] = 'Day Wise Pickup Earnings';
        $this->data['content'] = 'dc_earnings/day_wise_delivery_pickup_earning';
        $this->data['nav_type'] = 'day_wise_delivery_pickup_earnings';
        $this->_render_page($this->template, $this->data);

    }

    public function day_wise_delivery_ecom_earnings()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('from_date', 'Start Date', 'required');
        $this->form_validation->set_rules('to_date', 'End Date', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->data['title'] = 'Day Wise Ecom Earnings';
            $this->data['content'] = 'dc_earnings/day_wise_delivery_ecom_earning';
            $this->data['nav_type'] = 'day_wise_delivery_ecom_earnings';
            $queryResult = $this->dc_earning_model->get_day_wise_delivery_ecom_orders_with_status_508();
            $this->data['ecomdata'] = $queryResult['query_result'];
            $this->data['captaindata'] = $this->dc_earning_model->delivery_captain();
            $this->data['chartData'] = $queryResult['chart_data'];
            $this->data['day_chart_data'] = $queryResult['day_chart_data'];
            $this->data['captain_chart_data'] = $queryResult['captain_chart_data'];
            $this->data['dataLabelsStacked'] = $queryResult['dataLabelsStacked'];
            $this->data['dataDataSetStacked'] = $queryResult['dataDataSetStacked'];
          
        } else {
            $start_date = $this->input->post('from_date');
            $end_date = $this->input->post('to_date');
            $delivery_captain_id = $this->input->post('delivery_captain_id');

            $queryResult = $this->dc_earning_model->get_day_wise_delivery_ecom_orders_with_status_508_by_date_range($start_date, $end_date, $delivery_captain_id);
            $this->data['ecomdata'] = $queryResult['query_result'];
            $this->data['captaindata'] = $this->dc_earning_model->delivery_captain();
            $this->data['chartData'] = $queryResult['chart_data'];
            $this->data['day_chart_data'] = $queryResult['day_chart_data'];
            $this->data['captain_chart_data'] = $queryResult['captain_chart_data'];
            $this->data['day_captain_chart_data'] = $queryResult['day_captain_chart_data'];

            $this->data['dataLabelsStacked'] = $queryResult['dataLabelsStacked'];
            $this->data['dataDataSetStacked'] = $queryResult['dataDataSetStacked'];


        }
        $this->data['title'] = 'Day Wise Ecom Earnings';
        $this->data['content'] = 'dc_earnings/day_wise_delivery_ecom_earning';
        $this->data['nav_type'] = 'day_wise_delivery_ecom_earnings';
        $this->_render_page($this->template, $this->data);

    }

    public function dc_get_ecom_order_details()
    {
        $date = $this->input->post('date');
        $deliveryBoyId = $this->input->post('deliveryBoyId');
        $orders = $this->dc_earning_model->getEcomOrdersByDate($date, $deliveryBoyId);
        echo json_encode($orders);
    }

    public function dc_get_graph_ecom_order_details()
    {
        $date = $this->input->post('date');
        $graphOrders = $this->dc_earning_model->getGraphEcomOrdersByDate($date);
        echo json_encode($graphOrders);
    }

    public function dc_day_wise_ecom_captain_graph_earnings_modal()
    {
        $captainName = $this->input->post('captainName');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $graphCaptainOrders = $this->dc_earning_model->getGraphEcomCaptainOrdersByDate($captainName, $from_date, $to_date);
        echo json_encode($graphCaptainOrders);
    }

    public function dc_get_pickup_order_details()
    {
        $date = $this->input->post('date');
        $deliveryBoyId = $this->input->post('deliveryBoyId');
        $orders = $this->dc_earning_model->getPickupOrdersByDate($date, $deliveryBoyId);
        echo json_encode($orders);
    }

    public function dc_get_graph_pickup_order_details()
    {
        $date = $this->input->post('date');
        $graphOrders = $this->dc_earning_model->getGraphPickupOrdersByDate($date);
        echo json_encode($graphOrders);
    }

    public function dc_day_wise_pickup_captain_graph_earnings_modal()
    {
        $captainName = $this->input->post('captainName');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $graphCaptainOrders = $this->dc_earning_model->getGraphPickupCaptainOrdersByDate($captainName, $from_date, $to_date);
        echo json_encode($graphCaptainOrders);
    }


}