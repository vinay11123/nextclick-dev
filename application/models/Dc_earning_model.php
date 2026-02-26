<?php

class Dc_earning_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_delivery_pickup_orders_with_status_508()
    {
        $this->db->select('u.first_name, p.*, d.status');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('DATE(p.created_at)', date('Y-m-d'));
        $query = $this->db->get();
        if (!$query) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            // If the query is successful, retrieve the result and process chart data
            return $query->result();
        }

    }

    public function get_delivery_ecom_orders_with_status_508()
    {
        $this->db->select('u.first_name, e.*, d.status');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('DATE(e.created_at)', date('Y-m-d'));
        $query = $this->db->get();
        if (!$query) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            // If the query is successful, retrieve the result and process chart data
            return $query->result();
        }
    }

    public function get_delivery_pickup_orders_with_status_508_by_date_range($start_date, $end_date, $deliver_captain_id)
    {
        $this->db->select('u.first_name, p.*, d.status');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('p.created_at >=', $start_date);
        $this->db->where('p.created_at <=', $end_date);

        if (!empty ($deliver_captain_id)) {
            $this->db->where('d.delivery_boy_user_id', $deliver_captain_id);
        }
        $query = $this->db->get();
        if (!$query) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            return $query->result();
        }
    }

    public function get_delivery_ecom_orders_with_status_508_by_date_range($start_date, $end_date, $deliver_captain_id)
    {

        $this->db->select('u.first_name, e.*, d.status');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('e.created_at >=', $start_date);
        $this->db->where('e.created_at <=', $end_date);
        if (!empty ($deliver_captain_id)) {
            $this->db->where('d.delivery_boy_user_id', $deliver_captain_id);
        }
        $query = $this->db->get();
        if (!$query) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            // If the query is successful, retrieve the result and process chart data
            return $query->result();
        }
    }


    public function get_day_wise_delivery_ecom_orders_with_status_508()
    {
        $this->db->select('u.first_name,d.delivery_boy_user_id,DATE(e.created_at) as created_date, COUNT(*) as order_count, SUM(e.delivery_boy_delivery_fee) as order_total_amount, SUM(e.delivery_boy_delivery_fee_gst_value) as gst_total_amount, SUM(e.delivery_boy_delivery_fee_without_gst) as without_gst_total_amount');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $currentMonthStart = date('Y-m-01'); // 1st day of the current month
        $currentDate = date('Y-m-d'); // Current date

        $this->db->where('e.created_at >=', $currentMonthStart);
        $this->db->where('e.created_at <=', $currentDate);
        $this->db->group_by('DATE(e.created_at), d.delivery_boy_user_id');
        $query = $this->db->get();

        $this->db->select('DATE(e.created_at) as created_date, COUNT(*) as order_count, SUM(e.delivery_boy_delivery_fee) as order_total_amount, SUM(e.delivery_boy_delivery_fee) as order_total_amount, SUM(e.delivery_boy_delivery_fee_gst_value) as gst_total_amount, SUM(e.delivery_boy_delivery_fee_without_gst) as without_gst_total_amount');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->where('d.status', 508);
        $currentMonthStart = date('Y-m-01'); // 1st day of the current month
        $currentDate = date('Y-m-d'); // Current date

        $this->db->where('e.created_at >=', $currentMonthStart);
        $this->db->where('e.created_at <=', $currentDate);
        $this->db->group_by('DATE(e.created_at)');
        $query_day_wise = $this->db->get();


        $this->db->select('u.first_name, d.delivery_boy_user_id, COUNT(*) AS order_count, SUM(e.delivery_boy_delivery_fee) AS order_total_amount, SUM(e.delivery_boy_delivery_fee_gst_value) AS gst_total_amount, SUM(e.delivery_boy_delivery_fee_without_gst) AS without_gst_total_amount');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('e.created_at >=', date('Y-m-01')); // 1st day of the current month
        $this->db->where('e.created_at <=', date('Y-m-d')); // Current date
        $this->db->group_by('d.delivery_boy_user_id, u.first_name');
        $query_captain = $this->db->get();

        $stackedChartData = $this->getStackedChart($query_day_wise, $query_captain);


        if (!$query || !$query_day_wise || !$query_captain) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            // If the query is successful, retrieve the result and process chart data
            $chartData = $this->day_wise_getChartData($query);
            $dayChartData = $this->date_wise_getChartData($query_day_wise);
            $captainChartData = $this->captain_wise_getChartData($query_captain);
            return array(
                'query_result' => $query->result(),
                'chart_data' => $chartData,
                'day_chart_data' => $dayChartData,
                'captain_chart_data' => $captainChartData,
                'dataLabelsStacked' => $stackedChartData['labels'],
                'dataDataSetStacked' => $stackedChartData['datasets']
            );
        }

    }


    public function get_day_wise_delivery_pickup_orders_with_status_508_by_date_range($start_date, $end_date, $deliver_captain_id)
    {
        $this->db->select('u.first_name,d.delivery_boy_user_id,DATE(p.created_at) as created_date, COUNT(*) as order_count, SUM(p.delivery_boy_delivery_fee) as order_total_amount, SUM(p.delivery_boy_delivery_fee) as order_total_amount, SUM(p.delivery_boy_delivery_fee_gst_value) as gst_total_amount, SUM(p.delivery_boy_delivery_fee_without_gst) as without_gst_total_amount');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('p.created_at >=', $start_date);
        $this->db->where('p.created_at <=', $end_date);
        $this->db->group_by('DATE(p.created_at), d.delivery_boy_user_id');

        if (!empty ($deliver_captain_id)) {
            $this->db->where('d.delivery_boy_user_id', $deliver_captain_id);
        }
        $query = $this->db->get();

        $this->db->select('DATE(p.created_at) as created_date, COUNT(*) as order_count, SUM(p.delivery_boy_delivery_fee) as order_total_amount, SUM(p.delivery_boy_delivery_fee) as order_total_amount, SUM(p.delivery_boy_delivery_fee_gst_value) as gst_total_amount, SUM(p.delivery_boy_delivery_fee_without_gst) as without_gst_total_amount');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->where('d.status', 508);

        $this->db->where('p.created_at >=', $start_date);
        $this->db->where('p.created_at <=', $end_date);
        if (!empty ($deliver_captain_id)) {
            $this->db->where('d.delivery_boy_user_id', $deliver_captain_id);
        }
        $this->db->group_by('DATE(p.created_at)');
        $query_day_wise = $this->db->get();


        $this->db->select('u.first_name, d.delivery_boy_user_id, COUNT(*) AS order_count, SUM(p.delivery_boy_delivery_fee) AS order_total_amount, SUM(p.delivery_boy_delivery_fee_gst_value) AS gst_total_amount, SUM(p.delivery_boy_delivery_fee_without_gst) AS without_gst_total_amount');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('p.created_at >=', $start_date);
        $this->db->where('p.created_at <=', $end_date);
        if (!empty ($deliver_captain_id)) {
            $this->db->where('d.delivery_boy_user_id', $deliver_captain_id);
        }
        $this->db->group_by('d.delivery_boy_user_id, u.first_name');
        $query_captain = $this->db->get();

        $stackedPickupChartData = $this->getPickupStackedChart($query_day_wise, $query_captain);

        if (!$query || !$query_day_wise || !$query_captain) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            // If the query is successful, retrieve the result and process chart data
            $chartData = $this->day_wise_getChartData($query);
            $dayChartData = $this->date_wise_getChartData($query_day_wise);
            $captainChartData = $this->captain_wise_getChartData($query_captain);
            return array(
                'query_result' => $query->result(),
                'chart_data' => $chartData,
                'day_chart_data' => $dayChartData,
                'captain_chart_data' => $captainChartData,
                'dataLabelsStacked' => $stackedPickupChartData['labels'],
                'dataDataSetStacked' => $stackedPickupChartData['datasets']
            );
        }
    }


    public function get_day_wise_delivery_ecom_orders_with_status_508_by_date_range($start_date, $end_date, $deliver_captain_id)
    {
        $this->db->select('u.first_name,d.delivery_boy_user_id,DATE(e.created_at) as created_date, COUNT(*) as order_count, SUM(e.delivery_boy_delivery_fee) as order_total_amount, SUM(e.delivery_boy_delivery_fee_gst_value) as gst_total_amount, SUM(e.delivery_boy_delivery_fee_without_gst) as without_gst_total_amount');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('e.created_at >=', $start_date);
        $this->db->where('e.created_at <=', $end_date);
        $this->db->group_by('DATE(e.created_at), d.delivery_boy_user_id');
        if (!empty ($deliver_captain_id)) {
            $this->db->where('d.delivery_boy_user_id', $deliver_captain_id);
        }
        $query = $this->db->get();
        $this->db->select('DATE(e.created_at) as created_date, COUNT(*) as order_count, SUM(e.delivery_boy_delivery_fee) as order_total_amount, SUM(e.delivery_boy_delivery_fee) as order_total_amount, SUM(e.delivery_boy_delivery_fee_gst_value) as gst_total_amount, SUM(e.delivery_boy_delivery_fee_without_gst) as without_gst_total_amount');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->where('d.status', 508);

        $this->db->where('e.created_at >=', $start_date);
        $this->db->where('e.created_at <=', $end_date);
        if (!empty ($deliver_captain_id)) {
            $this->db->where('d.delivery_boy_user_id', $deliver_captain_id);
        }
        $this->db->group_by('DATE(e.created_at)');
        $query_day_wise = $this->db->get();


        $this->db->select('u.first_name, d.delivery_boy_user_id, COUNT(*) AS order_count, SUM(e.delivery_boy_delivery_fee) AS order_total_amount, SUM(e.delivery_boy_delivery_fee_gst_value) AS gst_total_amount, SUM(e.delivery_boy_delivery_fee_without_gst) AS without_gst_total_amount');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('e.created_at >=', $start_date);
        $this->db->where('e.created_at <=', $end_date);
        if (!empty ($deliver_captain_id)) {
            $this->db->where('d.delivery_boy_user_id', $deliver_captain_id);
        }
        $this->db->group_by('d.delivery_boy_user_id, u.first_name');
        $query_captain = $this->db->get();

        $stackedChartData = $this->getStackedChart($query_day_wise, $query_captain);

        if (!$query || !$query_day_wise || !$query_captain) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            // If the query is successful, retrieve the result and process chart data
            $chartData = $this->day_wise_getChartData($query);
            $dayChartData = $this->date_wise_getChartData($query_day_wise);
            $captainChartData = $this->captain_wise_getChartData($query_captain);
            return array(
                'query_result' => $query->result(),
                'chart_data' => $chartData,
                'day_chart_data' => $dayChartData,
                'captain_chart_data' => $captainChartData,
                'dataLabelsStacked' => $stackedChartData['labels'],
                'dataDataSetStacked' => $stackedChartData['datasets']
            );
        }

    }




    public function getStackedChart($query_day_wise, $query_captain)
    {
        $chartDataStacked = [];

        $dates = [];

        // Extract unique dates from the result set
        foreach ($query_day_wise->result() as $row) {
            $dateVal = $row->created_date;
            if (!in_array($dateVal, $dates)) {
                $dates[] = $dateVal;
            }
        }

        $caps = [];
        foreach ($query_captain->result() as $row_cap) {
            $capVal = $row_cap->first_name;
            if (!in_array($capVal, $caps)) {
                $caps[] = $capVal;
            }
        }

        $dateData = [];
        // Assuming $dates is already defined with an array of dates
        foreach ($dates as $date) {
            foreach ($caps as $cap) {
                $this->db->select('e.created_at, u.first_name AS delivery_captain_name, d.delivery_boy_user_id AS delivery_captain_id, COUNT(*) AS orders_delivered, SUM(e.delivery_boy_delivery_fee) AS total_order_amount');
                $this->db->from('ecom_orders e');
                $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
                $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
                $this->db->where('d.status', 508);
                $this->db->where('DATE(e.created_at)', $date);
                $this->db->where('u.first_name', $cap);
                $this->db->group_by('d.delivery_boy_user_id, u.first_name');
                $query_get_data = $this->db->get();

                // Process the query results
                $result_get_data = $query_get_data->result_array();


                if (count($result_get_data) > 0) {
                    foreach ($result_get_data as $row_data) {
                        $dateData[$row_data['delivery_captain_name']] = [
                            'total_order_amount' => $row_data['total_order_amount']
                        ];
                    }
                } else {
                    $dateData[$cap] = [
                        'total_order_amount' => 0
                    ];
                }
                // Add the dateData array to the chartData array, with the date as the key
                $chartDataStacked[$date] = $dateData;
            }
        }

        $dateLabels = array_keys($chartDataStacked); // Dates
        $labels = array_map(function ($dateLabels) {
            return date('d-m-Y', strtotime($dateLabels));
        }, $dateLabels);
        $datasets = [];

        foreach ($chartDataStacked as $date => $dateData) {
            foreach ($dateData as $captainName => $captainData) {
                $total_order_amount = $captainData['total_order_amount'] ? $captainData['total_order_amount'] : 0;

                // Add data to the corresponding dataset
                $datasets[$captainName]['name'] = $captainName;
                $datasets[$captainName]['data'][] = [
                    'x' => $date,
                    'y' => $total_order_amount
                ];
            }
        }

        return array(
            'labels' => $labels,
            'datasets' => $datasets
        );

    }


    public function getPickupStackedChart($query_day_wise, $query_captain)
    {
        $chartDataStacked = [];

        $dates = [];

        // Extract unique dates from the result set
        foreach ($query_day_wise->result() as $row) {
            $dateVal = $row->created_date;
            if (!in_array($dateVal, $dates)) {
                $dates[] = $dateVal;
            }
        }

        $caps = [];
        foreach ($query_captain->result() as $row_cap) {
            $capVal = $row_cap->first_name;
            if (!in_array($capVal, $caps)) {
                $caps[] = $capVal;
            }
        }

        $dateData = [];
        // Assuming $dates is already defined with an array of dates
        foreach ($dates as $date) {
            foreach ($caps as $cap) {
                $this->db->select('p.created_at, u.first_name AS delivery_captain_name, d.delivery_boy_user_id AS delivery_captain_id, COUNT(*) AS orders_delivered, SUM(p.delivery_boy_delivery_fee) AS total_order_amount');
                $this->db->from('pickup_orders p');
                $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
                $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
                $this->db->where('d.status', 508);
                $this->db->where('DATE(p.created_at)', $date);
                $this->db->where('u.first_name', $cap);
                $this->db->group_by('d.delivery_boy_user_id, u.first_name');
                $query_get_data = $this->db->get();

                // Process the query results
                $result_get_data = $query_get_data->result_array();


                if (count($result_get_data) > 0) {
                    foreach ($result_get_data as $row_data) {
                        $dateData[$row_data['delivery_captain_name']] = [
                            'total_order_amount' => $row_data['total_order_amount']
                        ];
                    }
                } else {
                    $dateData[$cap] = [
                        'total_order_amount' => 0
                    ];
                }
                // Add the dateData array to the chartData array, with the date as the key
                $chartDataStacked[$date] = $dateData;
            }
        }

        $dateLabels = array_keys($chartDataStacked); // Dates
        $labels = array_map(function ($dateLabels) {
            return date('d-m-Y', strtotime($dateLabels));
        }, $dateLabels);

        $datasets = [];

        foreach ($chartDataStacked as $date => $dateData) {
            foreach ($dateData as $captainName => $captainData) {
                $total_order_amount = $captainData['total_order_amount'] ? $captainData['total_order_amount'] : 0;

                // Add data to the corresponding dataset
                $datasets[$captainName]['name'] = $captainName;
                $datasets[$captainName]['data'][] = [
                    'x' => $date,
                    'y' => $total_order_amount
                ];
            }
        }

        return array(
            'labels' => $labels,
            'datasets' => $datasets
        );

    }




    public function delivery_captain()
    {
        $this->db->select('u.id, u.first_name, u.phone, db.user_id');
        $this->db->from('users u');
        $this->db->join('delivery_boy_address db', 'u.id = db.user_id');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function get_day_wise_delivery_pickup_orders_with_status_508()
    {
        $this->db->select('u.first_name,d.delivery_boy_user_id,DATE(p.created_at) as created_date, COUNT(*) as order_count, SUM(p.delivery_boy_delivery_fee) as order_total_amount, SUM(p.delivery_boy_delivery_fee) as order_total_amount, SUM(p.delivery_boy_delivery_fee_gst_value) as gst_total_amount, SUM(p.delivery_boy_delivery_fee_without_gst) as without_gst_total_amount');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $currentMonthStart = date('Y-m-01'); // 1st day of the current month
        $currentDate = date('Y-m-d'); // Current date

        $this->db->where('p.created_at >=', $currentMonthStart);
        $this->db->where('p.created_at <=', $currentDate);
        $this->db->group_by('DATE(p.created_at), d.delivery_boy_user_id');
        $query = $this->db->get();


        $this->db->select('DATE(p.created_at) as created_date, COUNT(*) as order_count, SUM(p.delivery_boy_delivery_fee) as order_total_amount, SUM(p.delivery_boy_delivery_fee) as order_total_amount, SUM(p.delivery_boy_delivery_fee_gst_value) as gst_total_amount, SUM(p.delivery_boy_delivery_fee_without_gst) as without_gst_total_amount');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->where('d.status', 508);
        $currentMonthStart = date('Y-m-01'); // 1st day of the current month
        $currentDate = date('Y-m-d'); // Current date

        $this->db->where('p.created_at >=', $currentMonthStart);
        $this->db->where('p.created_at <=', $currentDate);
        $this->db->group_by('DATE(p.created_at)');
        $query_day_wise = $this->db->get();


        $this->db->select('u.first_name, d.delivery_boy_user_id, COUNT(*) AS order_count, SUM(p.delivery_boy_delivery_fee) AS order_total_amount, SUM(p.delivery_boy_delivery_fee_gst_value) AS gst_total_amount, SUM(p.delivery_boy_delivery_fee_without_gst) AS without_gst_total_amount');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('p.created_at >=', date('Y-m-01')); // 1st day of the current month
        $this->db->where('p.created_at <=', date('Y-m-d')); // Current date
        $this->db->group_by('d.delivery_boy_user_id, u.first_name');
        $query_captain = $this->db->get();


        $stackedPickupChartData = $this->getPickupStackedChart($query_day_wise, $query_captain);

        if (!$query || !$query_day_wise || !$query_captain) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            // If the query is successful, retrieve the result and process chart data
            $chartData = $this->day_wise_getChartData($query);
            $dayChartData = $this->date_wise_getChartData($query_day_wise);
            $captainChartData = $this->captain_wise_getChartData($query_captain);
            return array(
                'query_result' => $query->result(),
                'chart_data' => $chartData,
                'day_chart_data' => $dayChartData,
                'captain_chart_data' => $captainChartData,
                'dataLabelsStacked' => $stackedPickupChartData['labels'],
                'dataDataSetStacked' => $stackedPickupChartData['datasets']
            );
        }
    }

    public function day_wise_getChartData($query)
    {
        $results = $query->result_array();

        $data = [
            'labels' => [],
            'firstNames' => [],
            'orderCounts' => [],
            'orderTotalAmounts' => [],
            'gstTotalAmounts' => [],
            'withoutGstTotalAmounts' => []
        ];

        foreach ($results as $row) {
            $data['labels'][] = date('d-m-Y', strtotime($row['created_date']));
            $data['firstNames'][] = $row['first_name'];
            $data['orderCounts'][] = $row['order_count'];
            $data['orderTotalAmounts'][] = $row['order_total_amount'];
            $data['gstTotalAmounts'][] = $row['gst_total_amount'];
            $data['withoutGstTotalAmounts'][] = $row['without_gst_total_amount'];
        }

        return $data;
    }

    public function date_wise_getChartData($query)
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
            $data['orderTotalAmounts'][] = $row['order_total_amount'];
            $data['gstTotalAmounts'][] = $row['gst_total_amount'];
            $data['withoutGstTotalAmounts'][] = $row['without_gst_total_amount'];
        }

        return $data;
    }

    public function captain_wise_getChartData($query)
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
            $data['labels'][] = $row['first_name'];
            $data['orderCounts'][] = $row['order_count'];
            $data['orderTotalAmounts'][] = $row['order_total_amount'];
            $data['gstTotalAmounts'][] = $row['gst_total_amount'];
            $data['withoutGstTotalAmounts'][] = $row['without_gst_total_amount'];
        }

        return $data;
    }



    public function getEcomOrdersByDate($date, $deliveryBoyId)
    {

        $this->db->select('u.first_name, e.*, d.status');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('DATE(e.created_at)', $date);
        $this->db->where('d.delivery_boy_user_id', $deliveryBoyId);
        $query = $this->db->get();
        return $query->result();
    }

    public function getGraphEcomOrdersByDate($date)
    {

        $this->db->select('u.first_name, e.*, d.status');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('DATE(e.created_at)', $date);
        $query = $this->db->get();
        return $query->result();
    }

    public function getGraphEcomCaptainOrdersByDate($captainName, $from_date, $to_date)
    {

        $this->db->select('u.first_name, e.*, d.status');
        $this->db->from('ecom_orders e');
        $this->db->join('delivery_jobs d', 'e.id = d.ecom_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('u.first_name', $captainName);
        $this->db->where('e.created_at >=', $from_date);
        $this->db->where('e.created_at <=', $to_date);
        $query = $this->db->get();
        return $query->result();
    }



    public function getPickupOrdersByDate($date, $deliveryBoyId)
    {
        $this->db->select('u.first_name, p.*, d.status');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('DATE(p.created_at)', $date);
        $this->db->where('d.delivery_boy_user_id', $deliveryBoyId);
        $query = $this->db->get();
        return $query->result();
    }

    public function getGraphPickupOrdersByDate($date)
    {
        $this->db->select('u.first_name, p.*, d.status');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('DATE(p.created_at)', $date);
        $query = $this->db->get();
        return $query->result();
    }

    public function getGraphPickupCaptainOrdersByDate($captainName, $from_date, $to_date)
    {
        $this->db->select('u.first_name, p.*, d.status');
        $this->db->from('pickup_orders p');
        $this->db->join('delivery_jobs d', 'p.id = d.pickup_order_id');
        $this->db->join('users u', 'd.delivery_boy_user_id = u.id');
        $this->db->where('d.status', 508);
        $this->db->where('u.first_name', $captainName);
        $this->db->where('p.created_at >=', $from_date);
        $this->db->where('p.created_at <=', $to_date);
        $query = $this->db->get();
        return $query->result();
    }
}
