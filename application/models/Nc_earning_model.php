<?php

class Nc_earning_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_pickup_orders_with_status_508()
    {
        $this->db->select('p.*, d.status');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->where('d.status', 508);
        $this->db->where('DATE(p.created_at)', date('Y-m-d'));
        $query = $this->db->get();
        if (!$query) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
        } else {
            // If the query is successful, retrieve the result and process chart data
            return $query->result();
        }
    }

    public function get_ecom_orders_with_status_508()
    {
        $this->db->select('e.*, d.status');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->where('d.status', 508);
        $this->db->where('DATE(e.created_at)', date('Y-m-d'));
        $query = $this->db->get();
        if (!$query) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
        } else {
            // If the query is successful, retrieve the result and process chart data
            return $query->result();
        }
    }

    public function get_pickup_orders_with_status_508_by_date_range($start_date, $end_date)
    {
        $this->db->select('p.*, d.status');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->where('d.status', 508);

        $this->db->where('p.created_at >=', $start_date);
        $this->db->where('p.created_at <=', $end_date);

        $query = $this->db->get();
        if (!$query) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
        } else {
            // If the query is successful, retrieve the result and process chart data
            return $query->result();
        }
    }

    public function get_ecom_orders_with_status_508_by_date_range($start_date, $end_date)
    {
        $this->db->select('e.*, d.status');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->where('d.status', 508);

        $this->db->where('e.created_at >=', $start_date);
        $this->db->where('e.created_at <=', $end_date);

        $query = $this->db->get();
        if (!$query) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
        } else {
            // If the query is successful, retrieve the result and process chart data
            return $query->result();
        }
    }

    public function get_day_wise_ecom_orders_with_status_508()
    {
        $this->db->select('DATE(e.created_at) as created_date, COUNT(*) as order_count, SUM(e.nc_delivery_fee) as total_order_amount, SUM(e.nc_delivery_fee_gst_value) as gst_total_amount, SUM(e.nc_delivery_fee_without_gst) as without_gst_total_amount');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->where('d.status', 508);
        $currentMonthStart = date('Y-m-01'); // 1st day of the current month
        $currentDate = date('Y-m-d'); // Current date

        $this->db->where('e.created_at >=', $currentMonthStart);
        $this->db->where('e.created_at <=', $currentDate);

        $this->db->group_by('DATE(e.created_at)');
        $query = $this->db->get();
        if (!$query) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
        } else {
            // If the query is successful, retrieve the result and process chart data
            $chartData = $this->day_wise_getChartData($query);
            return array(
                'query_result' => $query->result(),
                'chart_data' => $chartData
            );
        }
    }

    public function get_day_wise_pickup_orders_with_status_508()
    {
        $this->db->select('DATE(p.created_at) as created_date, COUNT(*) as order_count, SUM(p.nc_delivery_fee) as total_order_amount, SUM(p.nc_delivery_fee_gst_value) as gst_total_amount, SUM(p.nc_delivery_fee_without_gst) as without_gst_total_amount');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->where('d.status', 508);
        $currentMonthStart = date('Y-m-01'); // 1st day of the current month
        $currentDate = date('Y-m-d'); // Current date

        $this->db->where('p.created_at >=', $currentMonthStart);
        $this->db->where('p.created_at <=', $currentDate);
        $this->db->group_by('DATE(p.created_at)');
        $query = $this->db->get();
        if (!$query) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
        } else {
            // If the query is successful, retrieve the result and process chart data
            $chartData = $this->day_wise_getChartData($query);
            return array(
                'query_result' => $query->result(),
                'chart_data' => $chartData
            );
        }
    }

    public function get_day_wise_pickup_orders_with_status_508_by_date_range($start_date, $end_date)
    {
        $this->db->select('DATE(p.created_at) as created_date, COUNT(*) as order_count, SUM(p.nc_delivery_fee) as total_order_amount, SUM(p.nc_delivery_fee_gst_value) as gst_total_amount, SUM(p.nc_delivery_fee_without_gst) as without_gst_total_amount');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->where('d.status', 508);
        $this->db->group_by('DATE(p.created_at)');

        $this->db->where('p.created_at >=', $start_date);
        $this->db->where('p.created_at <=', $end_date);

        $query = $this->db->get();
        if (!$query) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
        } else {
            // If the query is successful, retrieve the result and process chart data
            $chartData = $this->day_wise_getChartData($query);
            return array(
                'query_result' => $query->result(),
                'chart_data' => $chartData
            );
        }
    }


    public function get_day_wise_ecom_orders_with_status_508_by_date_range($start_date, $end_date)
    {
        $this->db->select('DATE(e.created_at) as created_date, COUNT(*) as order_count, SUM(e.nc_delivery_fee) as total_order_amount, SUM(e.nc_delivery_fee_gst_value) as gst_total_amount, SUM(e.nc_delivery_fee_without_gst) as without_gst_total_amount');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->where('d.status', 508);
        $this->db->group_by('DATE(e.created_at)');

        $this->db->where('e.created_at >=', $start_date);
        $this->db->where('e.created_at <=', $end_date);

        $query = $this->db->get();
        if (!$query) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
        } else {
            // If the query is successful, retrieve the result and process chart data
            $chartData = $this->day_wise_getChartData($query);
            return array(
                'query_result' => $query->result(),
                'chart_data' => $chartData
            );
        }
    }


    public function delivery_captain()
    {
        $this->db->select('u.id, u.first_name, u.phone, db.user_id');
        $this->db->from('users u');
        $this->db->join('delivery_boy_address db', 'u.id = db.user_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function day_wise_getChartData($query)
    {
        $results = $query->result_array();

        $data = [
            'labels' => [],
            'orderCounts' => [],
            'orderTotalAmounts' => [],
            'gstTotalAmounts' => [],
            'withoutGstTotalAmounts' => []
        ];

        foreach ($results as $row) {

            $data['labels'][] = date('d-m-Y', strtotime($row['created_date']));
            $data['orderCounts'][] = $row['order_count'];
            $data['orderTotalAmounts'][] = $row['total_order_amount'];
            $data['gstTotalAmounts'][] = $row['gst_total_amount'];
            $data['withoutGstTotalAmounts'][] = $row['without_gst_total_amount'];
        }

        return $data;
    }

    public function getEcomOrdersByDate($date)
    {
        $this->db->select('e.*, d.status');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->where('d.status', 508);
        $this->db->where('DATE(e.created_at)', $date);
        $query = $this->db->get();
        return $query->result();
    }

    public function getPickupOrdersByDate($date)
    {
        $this->db->select('p.*, d.status');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->where('d.status', 508);
        $this->db->where('DATE(p.created_at)', $date);
        $query = $this->db->get();
        return $query->result();
    }
}
