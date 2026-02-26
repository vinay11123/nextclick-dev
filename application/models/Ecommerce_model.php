<?php
class Ecommerce_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_pickup_orders_table($postData = null)
    {


        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        $filterSearchQuery = '';

        if (!empty($postData['formData'])) {
            parse_str($postData['formData'], $formDataArray);
            $filterColumnName = $formDataArray['q'] ?? null;
            $filterColumnValue = $formDataArray['search'] ?? null;
            $filterDropdownValue = $formDataArray['qdropdown'] ?? null;

            if ($filterColumnName == 'track_id') {
                $filterSearchQuery = "po.track_id='$filterColumnValue'";
            } else if ($filterColumnName == 'first_name' && !empty($filterDropdownValue)) {
                $filterSearchQuery = "us.id='$filterDropdownValue'";
            } else if ($filterColumnName == 'txn_id') {
                $filterSearchQuery = "ep.txn_id='$filterColumnValue'";
            } else if ($filterColumnName == 'customer_name' && !empty($filterDropdownValue)) {
                $filterSearchQuery = "u.id='$filterDropdownValue'";
            } else if ($filterColumnName == 'payment_mode' && !empty($filterDropdownValue)) {
                $filterSearchQuery = "pm.id='$filterDropdownValue'";
            } else if ($filterColumnName == 'status' && !empty($filterDropdownValue)) {
                $filterSearchQuery = "eos.status='$filterDropdownValue'";
            }
        }


        $searchQuery = "";

        if ($searchValue != '') {
            $searchQuery = "(pdc.name LIKE '%" . $searchValue . "%' OR po.track_id LIKE '%" . $searchValue . "%' OR u.first_name LIKE '%" . $searchValue . "%' OR us.first_name LIKE '%" . $searchValue . "%' OR uap.address LIKE '%" . $searchValue . "%' OR uad.address LIKE '%" . $searchValue . "%' OR pl.latitude LIKE '%" . $searchValue . "%' OR pl.longitude LIKE '%" . $searchValue . "%' OR dl.latitude LIKE '%" . $searchValue . "%' OR dl.longitude LIKE '%" . $searchValue . "%' OR po.delivery_fee LIKE '%" . $searchValue . "%' OR po.delivery_gst_percentage LIKE '%" . $searchValue . "%' OR po.delivery_fee_without_gst LIKE '%" . $searchValue . "%' OR po.delivery_fee_gst_value LIKE '%" . $searchValue . "%' OR po.delivery_boy_delivery_fee LIKE '%" . $searchValue . "%' OR po.delivery_boy_delivery_fee_without_gst LIKE '%" . $searchValue . "%' OR po.delivery_boy_delivery_fee_gst_value LIKE '%" . $searchValue . "%' OR po.nc_delivery_fee LIKE '%" . $searchValue . "%' OR po.nc_delivery_fee_without_gst LIKE '%" . $searchValue . "%' OR po.nc_delivery_fee_gst_value LIKE '%" . $searchValue . "%' OR po.actual_distance LIKE '%" . $searchValue . "%' OR po.gmap_distance LIKE '%" . $searchValue . "%' OR po.flat_distance LIKE '%" . $searchValue . "%' OR po.flat_rate LIKE '%" . $searchValue . "%' OR po.nc_flat_rate LIKE '%" . $searchValue . "%' OR po.per_km LIKE '%" . $searchValue . "%' OR po.nc_per_km LIKE '%" . $searchValue . "%' OR ep.txn_id LIKE '%" . $searchValue . "%' OR v.name LIKE '%" . $searchValue . "%' OR eos.status LIKE '%" . $searchValue . "%' OR DATE_FORMAT(po.created_at, '%d-%b-%Y') LIKE '%" . $searchValue . "%'  OR uad.phone LIKE '%" . $searchValue . "%' OR us.phone LIKE '%" . $searchValue . "%' OR pm.name LIKE '%" . $searchValue . "%' OR pm.name LIKE '%" . $searchValue . "%' OR delivery_boy_user_id LIKE '%" . $searchValue . "%') ";
        }

        // Total number of records without filtering
        $this->db->select('count(*) as allcount')
            ->from('pickup_orders as po')
            ->join('vehicle_type as v', 'v.id = po.vehicle_type', 'left')
            ->join('ecom_payments as ep', 'ep.id = po.payment_id', 'left')
            ->join('payment_methods as pm', 'ep.payment_method_id = pm.id', 'left')
            ->join('users_address as uap', 'uap.id = po.pickup_address_id', 'left')
            ->join('users_address as uad', 'uad.id = po.delivery_address_id', 'left')
            ->join('locations as pl', 'pl.id = uap.location_id', 'left')
            ->join('locations as dl', 'dl.id = uad.location_id', 'left')
            ->join('ecom_order_statuses as eos', 'eos.id = po.order_status_id', 'left')
            ->join('users as u', 'u.id = po.created_user_id', 'left')
            ->join('delivery_jobs as dj', 'dj.pickup_order_id = po.id', 'left')
            ->join('users as us', 'us.id = dj.delivery_boy_user_id', 'left')
            ->join('pickupanddropcategories as pdc', 'pdc.id = po.pickupanddropcategory_id');

        $totalRecordsQuery = $this->db->get_compiled_select(); // Get the total records query

        $totalRecords = $this->db->query($totalRecordsQuery)->row()->allcount;

        // Total number of records with filtering
        $this->db->select('count(*) as allcount')
            ->from('pickup_orders as po')
            ->join('vehicle_type as v', 'v.id = po.vehicle_type', 'left')
            ->join('ecom_payments as ep', 'ep.id = po.payment_id', 'left')
            ->join('payment_methods as pm', 'ep.payment_method_id = pm.id', 'left')
            ->join('users_address as uap', 'uap.id = po.pickup_address_id', 'left')
            ->join('users_address as uad', 'uad.id = po.delivery_address_id', 'left')
            ->join('locations as pl', 'pl.id = uap.location_id', 'left')
            ->join('locations as dl', 'dl.id = uad.location_id', 'left')
            ->join('ecom_order_statuses as eos', 'eos.id = po.order_status_id', 'left')
            ->join('users as u', 'u.id = po.created_user_id', 'left')
            ->join('delivery_jobs as dj', 'dj.pickup_order_id = po.id', 'left')
            ->join('users as us', 'us.id = dj.delivery_boy_user_id', 'left')
            ->join('pickupanddropcategories as pdc', 'pdc.id = po.pickupanddropcategory_id');

        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        if ($filterSearchQuery != '') {
            $this->db->where($filterSearchQuery);
        }
        $totalRecordwithFilterQuery = $this->db->get_compiled_select(); // Get the total records with filtering query

        $totalRecordwithFilter = $this->db->query($totalRecordwithFilterQuery)->row()->allcount;

        $this->db->select('po.*, v.name as vehicle_name, uap.address as pickup_address, uad.address as delivery_address, uad.phone as delivery_phone,
        eos.status as order_status, u.id as customer_id, u.first_name as customer_name, us.id as captain_id, us.first_name as delivery_boy_name, us.phone as delivery_boy_phone, ep.txn_id as payment_txn_id,
        pl.latitude as pl_latitude,
        pl.longitude as pl_longitude,
        dl.latitude as dl_latitude,
        dl.longitude as dl_longitude,
        pdc.name as category_name, delivery_boy_user_id as delivery_boy_id, pm.id as payment_id, pm.name as payment_name')
            ->from('pickup_orders as po')
            ->join('vehicle_type as v', 'v.id = po.vehicle_type', 'left')
            ->join('ecom_payments as ep', 'ep.id = po.payment_id', 'left')
            ->join('payment_methods as pm', 'ep.payment_method_id = pm.id', 'left')
            ->join('users_address as uap', 'uap.id = po.pickup_address_id', 'left')
            ->join('users_address as uad', 'uad.id = po.delivery_address_id', 'left')
            ->join('locations as pl', 'pl.id = uap.location_id', 'left')
            ->join('locations as dl', 'dl.id = uad.location_id', 'left')
            ->join('ecom_order_statuses as eos', 'eos.id = po.order_status_id', 'left')
            ->join('users as u', 'u.id = po.created_user_id', 'left')
            ->join('delivery_jobs as dj', 'dj.pickup_order_id = po.id', 'left')
            ->join('users as us', 'us.id = dj.delivery_boy_user_id', 'left')
            ->join('pickupanddropcategories as pdc', 'pdc.id = po.pickupanddropcategory_id')
            ->order_by($columnName, $columnSortOrder);



        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        if ($filterSearchQuery != '') {
            $this->db->where($filterSearchQuery);
        }

        $this->db->limit($rowperpage, $start);

        $records = $this->db->get()->result();

        //   echo $this->db->last_query();

        $data = [];
        $sno = $start + 1;

        foreach ($records as $record) {

            $delivery_address = $record->delivery_address;
            $max_length = 30;

            if (strlen($delivery_address) > $max_length) {
                // If length exceeds max length, break into two lines
                $delivery_address = wordwrap($delivery_address, $max_length, "<br>", true);
            }

            $pickup_address = $record->pickup_address;
            $max_length = 30;

            if (strlen($pickup_address) > $max_length) {
                // If length exceeds max length, break into two lines
                $pickup_address = wordwrap($pickup_address, $max_length, "<br>", true);
            }


            $order_info = '<strong class="font-weight-bold">Order ID:</strong> ' . $record->track_id . '<br />' .
                '<strong class="font-weight-bold">Category Name:</strong> ' . $record->category_name . '<br />' .
                '<strong class="font-weight-bold">Customer Name:</strong> ' . $record->customer_name . '<br />' .
                '<strong class="font-weight-bold">Customer Phone:</strong> ' . $record->delivery_phone . '<br />' .
                '<strong class="font-weight-bold">Delivery Boy ID:</strong> ' . $record->delivery_boy_id . '<br />' .
                '<strong class="font-weight-bold">Delivery Boy Name:</strong> ' . $record->delivery_boy_name . '<br />' .
                '<strong class="font-weight-bold">Delivery Boy Phone:</strong> ' . $record->delivery_boy_phone . '<br />' .
                '<strong class="font-weight-bold">Payment ID:</strong> ' . $record->payment_txn_id . '<br />' .
                '<strong class="font-weight-bold">Payment Method:</strong> ' . $record->payment_name . '<br />' .
                '<strong class="font-weight-bold">Order Status:</strong> ' . $record->order_status . '<br />' .
                '<strong class="font-weight-bold">Created at:</strong> ' . date('d-M-Y H:i A', strtotime($record->created_at)) . '<br />';
            // 
            $delivery_info = '<strong class="font-weight-bold">Total Fee:</strong> ' . $record->delivery_fee . '<br />' .
                '<strong class="font-weight-bold">GST(%):</strong> ' . $record->delivery_gst_percentage . '<br />' .
                '<strong class="font-weight-bold">Delivery Fee Without GST:</strong> ' . $record->delivery_fee_without_gst . '<br />' .
                '<strong class="font-weight-bold">Delivery Fee GST Value:</strong> ' . $record->delivery_fee_gst_value . '<br />' .
                '<strong class="font-weight-bold">Delivery Boy Earnings:</strong> ' . $record->delivery_boy_delivery_fee . '<br />' .
                '<strong class="font-weight-bold">Delivery Boy Earnings Without GST:</strong> ' . $record->delivery_boy_delivery_fee_without_gst . '<br />' .
                '<strong class="font-weight-bold">Delivery Boy Earnings GST Value:</strong> ' . $record->delivery_boy_delivery_fee_gst_value . '<br />' .
                '<strong class="font-weight-bold">NC Earnings:</strong> ' . $record->nc_delivery_fee . '<br />' .
                '<strong class="font-weight-bold">NC Earnings Without GST:</strong> ' . $record->nc_delivery_fee_without_gst . '<br />' .
                '<strong class="font-weight-bold">NC Earnings GST Value:</strong> ' . $record->nc_delivery_fee_gst_value . '<br />' .
                '<strong class="font-weight-bold">Pickup Address:</strong> ' . $pickup_address . '<br />' .
                '<strong class="font-weight-bold">Delivery Address:</strong> ' . $delivery_address . '<br />' .
                '<strong class="font-weight-bold">Vehicle Type:</strong> ' . $record->vehicle_name . '<br />';

            $distance_info = '<strong class="font-weight-bold">Real Distance:</strong> ' . $record->actual_distance . '<br />' .
                '<strong class="font-weight-bold">Gmap Distance:</strong> ' . $record->gmap_distance . '<br />' .
                '<strong class="font-weight-bold">Flat Distance in km:</strong> ' . $record->flat_distance . '<br />' .
                '<strong class="font-weight-bold">DB Flat Rate:</strong> ' . $record->flat_rate . '<br />' .
                '<strong class="font-weight-bold">NC Flat Rate:</strong> ' . $record->nc_flat_rate . '<br />' .
                '<strong class="font-weight-bold">DB Per km Rate after flat distance:</strong> ' . $record->per_km . '<br />' .
                '<strong class="font-weight-bold">NC Per km Rate after flat distance:</strong> ' . $record->nc_per_km . '<br />' .
                '<strong class="font-weight-bold">Pickup Addr Coords :</strong> <br />' . $record->pl_latitude . ', ' . $record->pl_longitude . '<br />' .
                '<strong class="font-weight-bold">Delivery Addr Coords :</strong> <br />' . $record->dl_latitude . ', ' . $record->dl_longitude . '<br />';

            $editUrl = base_url('epickup_orders/edit?id=' . base64_encode(base64_encode($record->id)));
            $EditButton = "<a href='{$editUrl}' class='mr-2'><i class='feather icon-eye'></i></a>";


            // Populate data array
            $data[] = [
                "sno" => $sno++,
                "order_info" => $order_info,
                "delivery_info" => $delivery_info,
                "distance_info" => $distance_info,
                "order_status" => $record->order_status,
                "created_at" => date('d-M-Y H:i A', strtotime($record->created_at)),
                "action" => $EditButton,

                "category_name" => $record->category_name,
                "track_id" => $record->track_id,
                "customer_name" => $record->customer_name,
                "customer_phone" => $record->delivery_phone,
                "delivery_boy_id" => $record->delivery_boy_id,
                "delivery_boy_name" => $record->delivery_boy_name,
                "delivery_boy_phone" => $record->delivery_boy_phone,
                "pickup_address" => $record->pickup_address,
                "delivery_address" => $record->delivery_address,
                "pl_latitude" => $record->pl_latitude,
                "pl_longitude" => $record->pl_longitude,
                "dl_latitude" => $record->dl_latitude,
                "dl_longitude" => $record->dl_longitude,
                "delivery_fee" => $record->delivery_fee,
                "delivery_gst_percentage" => $record->delivery_gst_percentage,
                "delivery_fee_without_gst" => $record->delivery_fee_without_gst,
                "delivery_fee_gst_value" => $record->delivery_fee_gst_value,
                "delivery_boy_delivery_fee" => $record->delivery_boy_delivery_fee,
                "delivery_boy_delivery_fee_without_gst" => $record->delivery_boy_delivery_fee_without_gst,
                "delivery_boy_delivery_fee_gst_value" => $record->delivery_boy_delivery_fee_gst_value,
                "nc_delivery_fee" => $record->nc_delivery_fee,
                "nc_delivery_fee_without_gst" => $record->nc_delivery_fee_without_gst,
                "nc_delivery_fee_gst_value" => $record->nc_delivery_fee_gst_value,
                "actual_distance" => $record->actual_distance,
                "gmap_distance" => $record->gmap_distance,
                "flat_distance" => $record->flat_distance,
                "flat_rate" => $record->flat_rate,
                "nc_flat_rate" => $record->nc_flat_rate,
                "per_km" => $record->per_km,
                "nc_per_km" => $record->nc_per_km,
                "payment_id" => $record->payment_txn_id,
                "payment_mode" => $record->payment_name,
                "vehicle_name" => $record->vehicle_name,
            ];
        }

        // Response
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        return $response;
    }
    public function get_pickup_orders_table_by_id($id = null)
    {

        if ($id != '') {
            $this->db->select('po.*, v.name as vehicle_name, uap.address as pickup_address, uad.address as delivery_address,uad.phone as delivery_phone,uad.email as delivery_email,
            eos.status as order_status, u.first_name as customer_name, us.first_name as delivery_boy_name,
            pdc.name as category_name,  pdc.desc as product_desc, dj.id as delivery_id')
                ->from('pickup_orders as po')
                ->join('vehicle_type as v', 'v.id = po.vehicle_type', 'left')
                ->join('ecom_payments as ep', 'ep.id = po.payment_id', 'left')
                ->join('users_address as uap', 'uap.id = po.pickup_address_id', 'left')
                ->join('users_address as uad', 'uad.id = po.delivery_address_id', 'left')
                ->join('ecom_order_statuses as eos', 'eos.id = po.order_status_id', 'left')
                ->join('users as u', 'u.id = po.created_user_id', 'left')
                ->join('delivery_jobs as dj', 'dj.pickup_order_id = po.id', 'left')
                ->join('users as us', 'us.id = dj.delivery_boy_user_id', 'left')
                ->join('pickupanddropcategories as pdc', 'pdc.id = po.pickupanddropcategory_id');

            $this->db->where('po.id', $id);

            $records = $this->db->get()->result_array();

            $this->db->select('djl.status')
                ->from('delivery_job_logs AS djl')
                ->join('delivery_jobs AS dj', 'dj.id = djl.delivery_job_id', 'left')
                ->join('pickup_orders AS po', 'dj.pickup_order_id = po.id', 'left')
                ->where('po.id', $id);

            $records_get_status = $this->db->get()->result();

            if (!$records || !$records_get_status) {
                // If there's an error in the query execution, display the error details
                $error = $this->db->error();
                echo "Error: " . $error['code'] . " - " . $error['message'];
            } else {
                return array(
                    'query_result' => $records,
                    'status_result' => $records_get_status
                );
            }
        }
    }

    public function get_ecom_orders_table_by_id($id = null)
    {

        if ($id != '') {
            $this->db->select("eo.*, CONCAT_WS(', ', vad.line1, vad.location, st.name, dst.name, vad.zip_code) AS vendor_address, dj.id as dj_id, eos.name as order_status, eo.total as grand_total, ua.address, u.first_name, u.last_name, u.email, u.phone, vl.name as vendor_name, vl.id as vendor_id, dm.name as delivery_mode_name, ep.payment_method_id, pm.name as payment_method_name");
            $this->db->from('ecom_orders as eo');
            $this->db->join('order_statuses as eos', 'eos.id = eo.current_order_status_id');
            $this->db->join('delivery_jobs as dj', 'dj.ecom_order_id = eo.id', 'left');
            $this->db->join('users_address as ua', 'ua.id = eo.shipping_address_id');
            $this->db->join('users as u', 'u.id = eo.created_user_id');
            $this->db->join('vendors_list as vl', 'vl.vendor_user_id = eo.vendor_user_id');
            $this->db->join('vendor_address as vad', 'vad.list_id = vl.id');
            $this->db->join('states as st', 'vad.state = st.id');
            $this->db->join('districts as dst', 'vad.district = dst.id');
            $this->db->join('ecom_payments as ep', 'ep.id = eo.payment_id');
            $this->db->join('payment_methods as pm', 'pm.id = ep.payment_method_id');
            $this->db->join('delivery_modes as dm', 'dm.id = eo.delivery_mode_id');
            $this->db->where('eo.id', $id);

            $records = $this->db->get()->result_array();

            $this->db->select('eod.*, eo.cupon_discount, eo.delivery_gst_percentage, eo.track_id, fi.name as food_name, fi.desc, fii.id as image_id, fs.name as section_name, fsi.name as product_quantity');
            $this->db->from('ecom_order_details as eod');
            $this->db->join('food_item as fi', 'fi.id = eod.item_id', 'left');
            $this->db->join('ecom_orders as eo', 'eo.id = eod.ecom_order_id', 'left');
            $this->db->join('food_section as fs', 'fs.item_id = fi.id', 'left');
            $this->db->join('food_sec_item as fsi', 'fsi.sec_id = fi.id', 'left');
            $this->db->join('food_item_images as fii', 'fii.item_id = fi.id', 'left');
            $this->db->where('eod.ecom_order_id', $id);
            $this->db->group_by('fii.item_id');

            $records_customer = $this->db->get()->result_array();

            $this->db->select('osl.*,os.name as order_status_name')
                ->from('order_status_logs AS osl')
                ->join('order_statuses AS os', 'os.id = osl.order_status_id', 'left')
                ->join('ecom_orders AS eo', 'eo.id = osl.order_id', 'left')
                ->where('eo.id', $id)
                ->where('order_type', 'ecom');

            $records_status = $this->db->get()->result_array();


            if (!$records || !$records_customer || !$records_status) {
                // If there's an error in the query execution, display the error details
                $error = $this->db->error();
                echo "Error: " . $error['code'] . " - " . $error['message'];
            } else {
                return array(
                    'query_result' => $records,
                    'status_result' => $records_customer,
                    'order_status_result' => $records_status
                );
            }
        }
    }

    public function get_user_details()
    {
        $statusRecords = $this->db->query("SELECT DISTINCT name as status FROM order_statuses ORDER BY name ASC")->result_array();
        $vendorRecords = $this->db->query("SELECT DISTINCT name, id, vendor_user_id FROM vendors_list ORDER BY name ASC")->result_array();
        $customerRecords = $this->db->query("SELECT id,first_name,phone FROM users ORDER BY first_name ASC")->result_array();
        $paymentRecords = $this->db->query("SELECT DISTINCT name, id FROM payment_methods ORDER BY name ASC")->result_array();
        $deliveryRecords = $this->db->query("SELECT u.id,first_name,phone FROM delivery_jobs AS dj JOIN users AS u ON u.id = dj.delivery_boy_user_id GROUP BY dj.delivery_boy_user_id")->result_array();

        return array(
            'status_result' => $statusRecords,
            'vendor_result' => $vendorRecords,
            'customer_result' => $customerRecords,
            'payment_result' => $paymentRecords,
            'delivery_result' => $deliveryRecords
        );
    }

    public function get_ecom_orders_table($postData = null)
    {


        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        $filterSearchQuery = '';

        if (!empty($postData['formData'])) {
            parse_str($postData['formData'], $formDataArray);
            $filterColumnName = $formDataArray['q'] ?? null;
            $filterColumnValue = $formDataArray['search'] ?? null;
            $filterDropdownValue = $formDataArray['qdropdown'] ?? null;

            if ($filterColumnName == 'track_id') {
                $filterSearchQuery = "eo.track_id='$filterColumnValue'";
            } else if ($filterColumnName == 'first_name' && !empty($filterDropdownValue)) {
                $filterSearchQuery = "us.id='$filterDropdownValue'";
            } else if ($filterColumnName == 'txn_id') {
                $filterSearchQuery = "ep.txn_id='$filterColumnValue'";
            } else if ($filterColumnName == 'vendor_name') {
                $filterSearchQuery = "eo.vendor_user_id='$filterDropdownValue'";
            } else if ($filterColumnName == 'customer_name' && !empty($filterDropdownValue)) {
                $filterSearchQuery = "u.id='$filterDropdownValue'";
            } else if ($filterColumnName == 'payment_mode' && !empty($filterDropdownValue)) {
                $filterSearchQuery = "pm.id='$filterDropdownValue'";
            } else if ($filterColumnName == 'status' && !empty($filterDropdownValue)) {
                $filterSearchQuery = "eos.name='$filterDropdownValue'";
            }
        }


        $searchQuery = "";

        if ($searchValue != '') {
            $searchQuery = "(eo.track_id LIKE '%" . $searchValue . "%' OR u.first_name LIKE '%" . $searchValue . "%' OR us.first_name LIKE '%" . $searchValue . "%' OR ua.address LIKE '%" . $searchValue . "%' OR dl.latitude LIKE '%" . $searchValue . "%' OR dl.longitude LIKE '%" . $searchValue . "%' OR eo.delivery_fee LIKE '%" . $searchValue . "%' OR eo.delivery_gst_percentage LIKE '%" . $searchValue . "%' OR eo.delivery_fee_without_gst LIKE '%" . $searchValue . "%' OR eo.delivery_fee_gst_value LIKE '%" . $searchValue . "%' OR eo.delivery_boy_delivery_fee LIKE '%" . $searchValue . "%' OR eo.delivery_boy_delivery_fee_without_gst LIKE '%" . $searchValue . "%' OR eo.delivery_boy_delivery_fee_gst_value LIKE '%" . $searchValue . "%' OR eo.nc_delivery_fee LIKE '%" . $searchValue . "%' OR eo.nc_delivery_fee_without_gst LIKE '%" . $searchValue . "%' OR eo.nc_delivery_fee_gst_value LIKE '%" . $searchValue . "%' OR eo.actual_distance LIKE '%" . $searchValue . "%' OR eo.gmap_distance LIKE '%" . $searchValue . "%' OR eo.flat_distance LIKE '%" . $searchValue . "%' OR eo.flat_rate LIKE '%" . $searchValue . "%' OR eo.nc_flat_rate LIKE '%" . $searchValue . "%' OR eo.per_km LIKE '%" . $searchValue . "%' OR eo.nc_per_km LIKE '%" . $searchValue . "%' OR ep.txn_id LIKE '%" . $searchValue . "%' OR v.name LIKE '%" . $searchValue . "%' OR eos.name LIKE '%" . $searchValue . "%' OR eo.created_at LIKE '%" . $searchValue . "%'  OR u.phone LIKE '%" . $searchValue . "%' OR us.phone LIKE '%" . $searchValue . "%' OR pm.name LIKE '%" . $searchValue . "%' OR us.id LIKE '%" . $searchValue . "%' OR vl.name LIKE '%" . $searchValue . "%' OR (eo.total - eo.delivery_fee) LIKE '%" . $searchValue . "%' OR eo.total LIKE '%" . $searchValue . "%' OR eo.promocode_discount LIKE '%" . $searchValue . "%' OR eo.cupon_discount LIKE '%" . $searchValue . "%' OR DATE_FORMAT(eo.created_at, '%d-%b-%Y') LIKE '%" . $searchValue . "%') ";
            if (strtolower($searchValue) == 'yes') {
                $searchQuery .= " OR eo.cupon_id = 1";
            } else if (strtolower($searchValue) == 'no') {
                $searchQuery .= " OR eo.cupon_id = 0";
            }
        }

        // Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('ecom_orders as eo');
        $this->db->join('vehicle_type as v', 'v.id = eo.vehicle_type', 'left');
        $this->db->join('users_address as ua', 'ua.id = eo.shipping_address_id', 'left');
        $this->db->join('locations as dl', 'dl.id = ua.location_id', 'left');
        $this->db->join('users as u', 'u.id = eo.created_user_id', 'left');
        $this->db->join('vendors_list as vl', 'vl.vendor_user_id = eo.vendor_user_id', 'left');
        $this->db->join('delivery_modes as dm', 'dm.id = eo.delivery_mode_id', 'left');
        $this->db->join('ecom_payments as ep', 'ep.id = eo.payment_id', 'left');
        $this->db->join('payment_methods as pm', 'ep.payment_method_id = pm.id', 'left');
        $this->db->join('order_statuses as eos', 'eos.id = eo.current_order_status_id', 'left');
        $this->db->join('delivery_jobs as dj', 'dj.ecom_order_id = eo.id', 'left');
        $this->db->join('users as us', 'us.id = dj.delivery_boy_user_id', 'left');

        $totalRecordsQuery = $this->db->get_compiled_select(); // Get the total records query

        $totalRecords = $this->db->query($totalRecordsQuery)->row()->allcount;

        // Total number of records with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('ecom_orders as eo');
        $this->db->join('vehicle_type as v', 'v.id = eo.vehicle_type', 'left');
        $this->db->join('users_address as ua', 'ua.id = eo.shipping_address_id', 'left');
        $this->db->join('locations as dl', 'dl.id = ua.location_id', 'left');
        $this->db->join('users as u', 'u.id = eo.created_user_id', 'left');
        $this->db->join('vendors_list as vl', 'vl.vendor_user_id = eo.vendor_user_id', 'left');
        $this->db->join('delivery_modes as dm', 'dm.id = eo.delivery_mode_id', 'left');
        $this->db->join('ecom_payments as ep', 'ep.id = eo.payment_id', 'left');
        $this->db->join('payment_methods as pm', 'ep.payment_method_id = pm.id', 'left');
        $this->db->join('order_statuses as eos', 'eos.id = eo.current_order_status_id', 'left');
        $this->db->join('delivery_jobs as dj', 'dj.ecom_order_id = eo.id', 'left');
        $this->db->join('users as us', 'us.id = dj.delivery_boy_user_id', 'left');

        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        if ($filterSearchQuery != '') {
            $this->db->where($filterSearchQuery);
        }
        $totalRecordwithFilterQuery = $this->db->get_compiled_select(); // Get the total records with filtering query

        $totalRecordwithFilter = $this->db->query($totalRecordwithFilterQuery)->row()->allcount;

        $this->db->select('eo.*, (eo.total-eo.delivery_fee) as total_without_shipping, eo.total as grand_total, v.name as vehicle_name, dl.latitude as dl_latitude,
        dl.longitude as dl_longitude,ua.address, u.first_name, u.phone as customer_phone, us.first_name as delivery_boy_name, us.id as delivery_boy_id, us.phone as delivery_boy_phone, vl.name as vendor_name, dm.name as delivery_mode_name, ep.txn_id as payment_txn_id, ep.payment_method_id, pm.name as payment_method, vl.id as vendorpreid, eos.name as order_status');
        $this->db->from('ecom_orders as eo');
        $this->db->join('vehicle_type as v', 'v.id = eo.vehicle_type', 'left');
        $this->db->join('users_address as ua', 'ua.id = eo.shipping_address_id', 'left');
        $this->db->join('locations as dl', 'dl.id = ua.location_id', 'left');
        $this->db->join('users as u', 'u.id = eo.created_user_id', 'left');
        $this->db->join('vendors_list as vl', 'vl.vendor_user_id = eo.vendor_user_id', 'left');
        $this->db->join('delivery_modes as dm', 'dm.id = eo.delivery_mode_id', 'left');
        $this->db->join('ecom_payments as ep', 'ep.id = eo.payment_id', 'left');
        $this->db->join('payment_methods as pm', 'ep.payment_method_id = pm.id', 'left');
        $this->db->join('order_statuses as eos', 'eos.id = eo.current_order_status_id', 'left');
        $this->db->join('delivery_jobs as dj', 'dj.ecom_order_id = eo.id', 'left');
        $this->db->join('users as us', 'us.id = dj.delivery_boy_user_id', 'left');
        $this->db->group_by('eo.id');
        $this->db->order_by($columnName, $columnSortOrder);




        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        if ($filterSearchQuery != '') {
            $this->db->where($filterSearchQuery);
        }

        $this->db->limit($rowperpage, $start);

        $records = $this->db->get()->result();

        $data = [];
        $sno = $start + 1;

        foreach ($records as $record) {

            $delivery_address = $record->address;
            $max_length = 25;

            if (strlen($delivery_address) > $max_length) {
                // If length exceeds max length, break into two lines
                $delivery_address = wordwrap($delivery_address, $max_length, "<br>", true);
            }

            $order_info = '<strong class="font-weight-bold">Order ID:</strong> ' . $record->track_id . '<br />' .
                '<strong class="font-weight-bold">Customer Name:</strong> ' . $record->first_name . '<br />' .
                '<strong class="font-weight-bold">Customer Phone:</strong> ' . $record->customer_phone . '<br />' .
                '<strong class="font-weight-bold">Vendor Name:</strong> ' . $record->vendor_name . '<br />' .
                '<strong class="font-weight-bold">Delivery Boy ID:</strong> ' . $record->delivery_boy_id . '<br />' .
                '<strong class="font-weight-bold">Delivery Boy Name:</strong> ' . $record->delivery_boy_name . '<br />' .
                '<strong class="font-weight-bold">Delivery Boy Phone:</strong> ' . $record->delivery_boy_phone . '<br />' .
                '<strong class="font-weight-bold">Payment ID:</strong> ' . $record->payment_txn_id . '<br />' .
                '<strong class="font-weight-bold">Payment Method:</strong> ' . $record->payment_method . '<br />' .
                '<strong class="font-weight-bold">Order Status:</strong> ' . $record->order_status . '<br />' .
                '<strong class="font-weight-bold">Created at:</strong> ' . date('d-M-Y H:i A', strtotime($record->created_at)) . '<br />';

            $delivery_info = '<strong class="font-weight-bold">Grand Total:</strong> ' . $record->grand_total . '<br />' .
                '<strong class="font-weight-bold">Total Fee Without Shipping:</strong> ' . $record->total_without_shipping . '<br />' .
                '<strong class="font-weight-bold">Delivery Fee:</strong> ' . $record->delivery_fee . '<br />' .
                '<strong class="font-weight-bold">GST(%):</strong> ' . $record->delivery_gst_percentage . '<br />' .
                '<strong class="font-weight-bold">Delivery Fee Without GST:</strong> ' . $record->delivery_fee_without_gst . '<br />' .
                '<strong class="font-weight-bold">Delivery Fee GST Value:</strong> ' . $record->delivery_fee_gst_value . '<br />' .
                '<strong class="font-weight-bold">Delivery Boy Earnings:</strong> ' . $record->delivery_boy_delivery_fee . '<br />' .
                '<strong class="font-weight-bold">Delivery Boy Earnings Without GST:</strong> ' . $record->delivery_boy_delivery_fee_without_gst . '<br />' .
                '<strong class="font-weight-bold">Delivery Boy Earnings GST Value:</strong> ' . $record->delivery_boy_delivery_fee_gst_value . '<br />' .
                '<strong class="font-weight-bold">NC Earnings:</strong> ' . $record->nc_delivery_fee . '<br />' .
                '<strong class="font-weight-bold">NC Earnings Without GST:</strong> ' . $record->nc_delivery_fee_without_gst . '<br />' .
                '<strong class="font-weight-bold">NC Earnings GST Value:</strong> ' . $record->nc_delivery_fee_gst_value . '<br />' .
                '<strong class="font-weight-bold">Delivery Address:</strong> ' . $delivery_address . '<br />' .
                '<strong class="font-weight-bold">Vehicle Type:</strong> ' . $record->vehicle_name . '<br />' .
                '<strong class="font-weight-bold">Free Delivery:</strong> ' . (($record->cupon_id == 1) ? 'Yes' : 'No') . '<br />' .
                '<strong class="font-weight-bold">Coupon Discount:</strong> ' . (!empty($record->cupon_discount) ? $record->cupon_discount : 0) . '<br />' .
                '<strong class="font-weight-bold">Promocode Discount:</strong> ' . (!empty($record->promocode_discount) ? $record->promocode_discount : 0) . '<br />' .


                $distance_info = '<strong class="font-weight-bold">Real Distance:</strong> ' . $record->actual_distance . '<br />' .
                '<strong class="font-weight-bold">Gmap Distance:</strong> ' . $record->gmap_distance . '<br />' .
                '<strong class="font-weight-bold">Flat Distance in km:</strong> ' . $record->flat_distance . '<br />' .
                '<strong class="font-weight-bold">DB Flat Rate:</strong> ' . $record->flat_rate . '<br />' .
                '<strong class="font-weight-bold">NC Flat Rate:</strong> ' . $record->nc_flat_rate . '<br />' .
                '<strong class="font-weight-bold">DB Per km Rate after flat distance:</strong> ' . $record->per_km . '<br />' .
                '<strong class="font-weight-bold">NC Per km Rate after flat distance:</strong> ' . $record->nc_per_km . '<br />' .
                '<strong class="font-weight-bold">Delivery Addr Coords :</strong><br /> ' . $record->dl_latitude . ', ' . $record->dl_longitude . '<br />';

            $editUrl = base_url('eecom_orders/edit?id=' . base64_encode(base64_encode($record->id)));
            $EditButton = "<a href='{$editUrl}' class='mr-2'><i class='feather icon-eye'></i></a>";


            // Populate data array
            $data[] = [
                "sno" => $sno++,
                "order_info" => $order_info,
                "delivery_info" => $delivery_info,
                "distance_info" => $distance_info,
                "order_status" => $record->order_status,
                "created_at" => date('d-M-Y H:i A', strtotime($record->created_at)),
                "action" => $EditButton,

                "track_id" => $record->track_id,
                "customer_name" => $record->first_name,
                "customer_phone" => $record->customer_phone,
                "delivery_boy_id" => $record->delivery_boy_id,
                "delivery_boy_name" => $record->delivery_boy_name,
                "delivery_boy_phone" => $record->delivery_boy_phone,
                "delivery_address" => $delivery_address,
                "dl_latitude" => $record->dl_latitude,
                "dl_longitude" => $record->dl_longitude,

                "grand_total" => $record->grand_total,
                "total_without_shipping" => $record->total_without_shipping,
                "delivery_fee" => $record->delivery_fee,
                "delivery_gst_percentage" => $record->delivery_gst_percentage,
                "delivery_fee_without_gst" => $record->delivery_fee_without_gst,
                "delivery_fee_gst_value" => $record->delivery_fee_gst_value,
                "delivery_boy_delivery_fee" => $record->delivery_boy_delivery_fee,
                "delivery_boy_delivery_fee_without_gst" => $record->delivery_boy_delivery_fee_without_gst,
                "delivery_boy_delivery_fee_gst_value" => $record->delivery_boy_delivery_fee_gst_value,
                "nc_delivery_fee" => $record->nc_delivery_fee,
                "nc_delivery_fee_without_gst" => $record->nc_delivery_fee_without_gst,
                "nc_delivery_fee_gst_value" => $record->nc_delivery_fee_gst_value,
                "cupon_discount" => $record->cupon_discount,
                "promocode_discount" => $record->promocode_discount,
                "free_delivery" => (($record->cupon_id == 1) ? 'Yes' : 'No'),

                "actual_distance" => $record->actual_distance,
                "gmap_distance" => $record->gmap_distance,
                "flat_distance" => $record->flat_distance,
                "flat_rate" => $record->flat_rate,
                "nc_flat_rate" => $record->nc_flat_rate,
                "per_km" => $record->per_km,
                "nc_per_km" => $record->nc_per_km,
                "payment_id" => $record->payment_txn_id,
                "payment_mode" => $record->payment_method,
                "vehicle_name" => $record->vehicle_name,
            ];
        }

        // Response
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ];

        return $response;
    }

    public function get_vendor_details($id = null)
    {

        $this->db->select("v.*, co.name AS con_name,dst.name AS dist_name, CONCAT_WS(', ', vad.location, vad.line1, st.name, dst.name, vad.zip_code) AS vendor_address");
        $this->db->from('vendors_list v');
        $this->db->join('users u', 'u.id = v.vendor_user_id');
        $this->db->join('vendor_address as vad', 'vad.list_id = v.id');
        $this->db->join('states as st', 'vad.state = st.id');
        $this->db->join('constituencies as co', 'vad.constituency = co.id');
        $this->db->join('districts as dst', 'vad.district = dst.id');
        $this->db->where('v.vendor_user_id', $id);
        $this->db->order_by('created_at', 'DESC');

        $query = $this->db->get();
        if (!$query) {
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            return $query->result();
        }
    }

    public function get_vendor_orders($id, $status = '', $filterStatus = '')
    {
        $this->db->select("eo.*, os.name as order_status, eo.total as grand_total, pm.name as payment_method_name");
        $this->db->from('ecom_orders as eo');
        $this->db->join('order_statuses as os', 'os.id = eo.current_order_status_id');
        $this->db->join('ecom_payments as ep', 'ep.id = eo.payment_id', 'left');
        $this->db->join('payment_methods as pm', 'pm.id = ep.payment_method_id');

        $this->db->where('eo.vendor_user_id', $id);

        if ($status == 'ongoing') {
            $this->db->where_in('eo.current_order_status_id', array(2, 3, 4, 5));
        } else if ($status == 'outfordelivery') {
            $this->db->where_in('eo.current_order_status_id', array(6, 7, 8));
        } else if ($status == 'rejectedByVendor') {
            $this->db->where('eo.current_order_status_id', 10);
        } else if ($status == 'rejected') {
            $this->db->where_in('eo.current_order_status_id', array(9, 10, 11));
        }

        if (!empty($filterStatus) && $filterStatus != 'all') {
            $this->db->where('eo.current_order_status_id', $filterStatus);
        }

        $records = $this->db->get()->result();
        return $records;
    }


    public function get_vendor_order_details($vendor_id, $status = '', $id)
    {
        $this->db->select("eo.*, os.name as order_status, eo.total as grand_total, dm.name as delivery_mode_name,pm.name as payment_method_name, er.status as reject_request_status");
        $this->db->from('ecom_orders as eo');
        $this->db->join('order_statuses as os', 'os.id = eo.current_order_status_id');
        $this->db->join('users as u', 'u.id = eo.created_user_id', 'left');
        $this->db->join('ecom_payments as ep', 'ep.id = eo.payment_id', 'left');
        $this->db->join('payment_methods as pm', 'pm.id = ep.payment_method_id', 'left');
        $this->db->join('delivery_modes as dm', 'dm.id = eo.delivery_mode_id', 'left');
        $this->db->join('ecom_order_reject_requests as er', 'er.ecom_order_id = eo.id', 'left');

        $this->db->where('eo.vendor_user_id', $vendor_id);
        $this->db->where('eo.id', $id);

        $records = $this->db->get()->result_array();

        $this->db->select('eod.*, eod.promotion_banner_discount,eod.total as total_without_shipping,eo.cupon_discount, eo.delivery_gst_percentage, eo.track_id, fi.name as food_name, fi.desc, fii.id as image_id, fs.name as section_name, fsi.name as product_quantity, fsi.weight as product_weight');
        $this->db->from('ecom_order_details as eod');
        $this->db->join('food_item as fi', 'fi.id = eod.item_id', 'left');
        $this->db->join('ecom_orders as eo', 'eo.id = eod.ecom_order_id', 'left');
        $this->db->join('food_section as fs', 'fs.item_id = fi.id', 'left');
        $this->db->join('food_sec_item as fsi', 'fsi.sec_id = fi.id', 'left');
        $this->db->join('food_item_images as fii', 'fii.item_id = fi.id', 'left');
        $this->db->where('eod.ecom_order_id', $id);
        $this->db->group_by('fii.item_id');

        $records_customer = $this->db->get()->result_array();

        if (!$records || !$records_customer) {
            // If there's an error in the query execution, display the error details
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
        } else {
            return array(
                'query_result' => $records,
                'status_result' => $records_customer
            );
        }
    }
}
