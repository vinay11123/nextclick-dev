<?php

class Ecom_order_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'ecom_orders';
        $this->primary_key = 'id';

        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';

        $this->_config();
        $this->_form();
        $this->_relations();
    }

    protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id() ? $this->ion_auth->get_user_id() : $this->user_id; //add user_id
        return $data;
    }

    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id() ? $this->ion_auth->get_user_id() : $this->user_id; //add user_id
        return $data;
    }

    public function _config()
    {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }

    public function _relations()
    {

        $this->has_one['shipping_address'] = array(
            'Users_address_model',
            'id',
            'shipping_address_id'
        );

        $this->has_one['customer'] = array(
            'User_model',
            'id',
            'created_user_id'
        );

        $this->has_one['vendor'] = array(
            'Vendor_list_model',
            'vendor_user_id',
            'vendor_user_id'
        );

        $this->has_one['order_status'] = array(
            'Ecom_order_status_model',
            'id',
            'order_status_id'
        );

        $this->has_one['delivery_mode'] = array(
            'Delivery_mode_model',
            'id',
            'delivery_mode_id'
        );

        $this->has_one['payment'] = array(
            'Ecom_payment_model',
            'id',
            'payment_id'
        );

        $this->has_many['reject_request'] = array(
            'foreign_model' => 'Ecom_order_reject_request_model',
            'foreign_table' => 'ecom_order_reject_requests',
            'local_key' => 'id',
            'foreign_key' => 'ecom_order_id',
            'get_relate' => FALSE
        );

        $this->has_many['ecom_order_details'] = array(
            'foreign_model' => 'Ecom_order_deatils_model',
            'foreign_table' => 'ecom_order_details',
            'local_key' => 'id',
            'foreign_key' => 'ecom_order_id',
            'get_relate' => FALSE
        );

    }

    public function _form()
    {
        $this->rules['create'] = array(
            array(
                'field' => 'delivery_mode_id',
                'label' => 'Delivery Mode',
                'rules' => 'trim|required'
            ),

            array(
                'field' => 'payment_id',
                'label' => 'Payment Id',
                'rules' => 'trim|required'
            )
        );

    }

    public function get_orders($limit = 0, $offset = 0, $user_id = NULL, $start_date = NULL, $end_date = NULL, $last_days = NULL, $last_years = NULL, $status = NULL, $delivery_status = NULL, $is_count = FALSE, $uri = 'order_history')
    {
        $this->_query_orders($user_id, $start_date, $end_date, $last_days, $last_years, $status, $delivery_status, $uri);
        $this->db->order_by('`ecom_orders`.id', 'DESC');
        $this->db->group_by('`ecom_orders`.id');

        if ($is_count) {
            return $this->db->count_all_results($this->table);
        }
        if (!empty($limit)) {
            $this->db->limit($limit, $offset);
        }

        $rs = $this->db->get($this->table);
        //  print_array($this->db->last_query());
        if (!empty($rs))
            $result = $rs->result_array();
        else
            $result = NULL;

        return $result;
    }

    private function _query_orders($user_id = NULL, $start_date = NULL, $end_date = NULL, $last_days = NULL, $last_years = NULL, $status = NULL, $delivery_status = NULL, $uri = 'order_history')
    {
        $table = '`' . $this->table . '`';
        $selected_colums_list = [];
        if ($uri == 'order_history') {
            $this->db->select(' delivery_jobs.id as delivery_job_id, delivery_jobs.job_id, delivery_jobs.status as delivery_job_status');
            $this->db->join('`delivery_jobs`', "ecom_orders.id=delivery_jobs.ecom_order_id", 'left');
            $selected_colums_list = ['id', 'track_id', 'order_delivery_otp', 'preparation_time', 'payment_id', 'vendor_user_id', 'delivery_fee', 'delivery_gst_percentage', 'total', 'created_at', 'updated_at', 'order_status_id', 'cupon_id'];
        } elseif ($uri == 'vendor_orders') {
            $this->db->join('`delivery_jobs`', "ecom_orders.id=delivery_jobs.ecom_order_id", 'left');
            $selected_colums_list = ['id', 'track_id', 'preparation_time', 'shipping_address_id', 'delivery_mode_id', 'delivery_fee', 'delivery_gst_percentage', 'total', 'payment_id', 'message', 'created_user_id', 'vendor_user_id', 'created_at', 'updated_at', 'order_status_id'];
        } elseif ($uri == 'delivery_orders') {
            $this->db->select(' delivery_jobs.id as delivery_job_id, delivery_jobs.job_id, delivery_jobs.status');
            $this->db->join('`delivery_jobs`', "ecom_orders.id=delivery_jobs.ecom_order_id", 'left');
            $selected_colums_list = ['id', 'track_id', 'order_pickup_otp', 'order_pickup_otp', 'preparation_time', 'shipping_address_id', 'payment_id', 'delivery_fee', 'delivery_gst_percentage', 'total', 'message', 'created_user_id', 'vendor_user_id', 'created_at', 'updated_at', 'order_status_id',];
        }

        $str_select_order = '';
        if (!empty($selected_colums_list)) {
            foreach ($selected_colums_list as $v) {
                $str_select_order .= "$table.`$v`,";
            }
        } else {
            $str_select_order = "$table.*";
        }

        $this->db->select($str_select_order . "delivery_jobs.status as delivery_job_status, delivery_jobs.id as delivery_job_id");
        $this->db->join('`ecom_order_details`', "ecom_order_details.ecom_order_id=$table.id", 'left');
        $this->db->join('`users`', "ecom_orders.created_user_id=users.id", 'left');


        if (!empty($last_days)) {
            $this->db->where("$table.created_at BETWEEN CURDATE() - INTERVAL " . $last_days . " DAY AND CURDATE()");
        }

        if (!empty($last_years)) {
            $this->db->where("$table.created_at BETWEEN CURDATE() - INTERVAL " . $last_years . " YEAR AND CURDATE()");
        }

        if (!empty($q)) {
            $this->db->where("$table.track_id", $q);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $this->db->or_where('date(`ecom_orders`.`created_at`) BETWEEN "' . date('Y-m-d', strtotime($start_date)) . '" and "' . date('Y-m-d', strtotime($end_date)) . '"');
        } elseif (!empty($start_date) && empty($end_date)) {
            $this->db->or_where("date($table.`created_at`)=", date('Y-m-d', strtotime($start_date)));
        }

        if ($status != NULL) {
            $this->db->where_in($table . '.`order_status_id`', explode(',', $status));
        }

        if (($delivery_status != NULL) && ($delivery_status == 500 || $delivery_status == 501 || $delivery_status == 502 || $delivery_status == 503 || $delivery_status == 504 || $delivery_status == 505 || $delivery_status == 506 || $delivery_status == 507 || $delivery_status == 508)) {
            $this->db->where('`delivery_jobs`' . '.`status`', $delivery_status);
        }

        if ($uri == 'order_history') {
            $this->db->where("$table.created_user_id", $user_id);
        } elseif ($uri == 'vendor_orders') {
            $this->db->where("$table.vendor_user_id", $user_id);
        } elseif ($uri == 'delivery_orders') {
            $this->db->where("delivery_jobs.delivery_boy_user_id", $user_id);
        }

        $this->db->where("$table.deleted_at =", NULL);
        return $this;
    }

    public function income_reports_by_months($start_date = NULL, $end_date = null, $vendor_user_id = NULL)
    {
        $start_date = (empty($start_date)) ? date('Y-m-d') : date('Y-m-d', strtotime($start_date));
        $end_date = (empty($end_date)) ? date('Y-m-d') : date('Y-m-d', strtotime($end_date));
        $query = '
            select DATE_FORMAT(eo.created_at, "%b") AS month, DATE_FORMAT(eo.created_at,"%m") as m, DATE_FORMAT(eo.created_at, "%Y") AS year, SUM(eo.total) as amount
            from ecom_orders as eo where date(eo.created_at) >= date(\'' . $start_date . '\') and date(eo.created_at) <= date(\'' . $end_date . '\') and eo.vendor_user_id = ' . $vendor_user_id . ' and (eo.order_status_id = 5 or eo.order_status_id = 12)
            group by year(created_at),month(created_at)
            order by year(created_at),month(created_at);
        ';
        $rs = $this->db->query($query);
        if (!empty($rs))
            $result = $rs->result_array();
        else
            $result = NULL;

        return $result;
    }

    public function income_reports_by_week($start_date = NULL, $end_date = null, $vendor_user_id = NULL)
    {
        $start_date = (empty($start_date)) ? date('Y-m-d') : date('Y-m-d', strtotime($start_date));
        $end_date = (empty($end_date)) ? date('Y-m-d') : date('Y-m-d', strtotime($end_date));
        $query = '
            select DATE_FORMAT(eo.created_at, "%b") AS month,week(created_at) as week,sum(total) as amount
            from ecom_orders as eo where date(eo.created_at) >= date(\'' . $start_date . '\') and date(eo.created_at) <= date(\'' . $end_date . '\') and eo.vendor_user_id = ' . $vendor_user_id . ' and (eo.order_status_id = 5 or eo.order_status_id = 12)
            group by month(created_at),week(created_at)
            order by month(created_at),week(created_at);
        ';

        $rs = $this->db->query($query);
        if (!empty($rs))
            $result = $rs->result_array();
        else
            $result = NULL;

        return $result;
    }

    public function orders_count_by_status($start_date = NULL, $end_date = null, $vendor_user_id = NULL, $status_serial_number = 100)
    {
        $start_date = (empty($start_date)) ? date('Y-m-d') : date('Y-m-d', strtotime($start_date));
        $end_date = (empty($end_date)) ? date('Y-m-d') : date('Y-m-d', strtotime($end_date));
        $query = '
            select count(eo.order_status_id)as count
            from ecom_orders as eo
            join ecom_order_statuses as eos on eos.id = eo.order_status_id
            where date(eo.created_at) >= date(\'' . $start_date . '\') and date(eo.created_at) <= date(\'' . $end_date . '\') and eo.vendor_user_id = ' . $vendor_user_id . ' and eos.serial_number = ' . $status_serial_number . '
        ';
        return $this->db->query($query)->result_array()[0]['count'];
    }

    public function getByTrackID($trackingID)
    {
        try {
            $orderDetails = $this->get(["track_id" => $trackingID]);
            return $orderDetails;
        } catch (Exception $e) {
            return null;
        }
    }

    public function getOrderDetailswithPayment($orderID)
    {
        try {
            return $this->with_payment('fields: id, payment_method_id, txn_id, amount, status')->where('id', $orderID)->get();
        } catch (Exception $e) {
            return null;
        }
    }

    public function updateOrderStatus($orderID, $deliveryMode, $statusCode)
    {
        try {
            $this->ecom_order_model->update([
                'id' => $orderID,
                'order_status_id' => $this->ecom_order_status_model->fields('id')->where(['delivery_mode_id' => $deliveryMode, 'serial_number' => $statusCode])->get()['id']
            ], 'id');
            return [
                "success" => true,
                "data" => [
                    "id" => $orderID
                ]
            ];
        } catch (Exception $e) {
            return [
                "success" => false,
                "error" => $e
            ];
        }
    }

    public function get_order_details($order_id)
    {
        return $this->db->select('ecom_orders.id, ecom_orders.track_id, ecom_orders.payment_id, ecom_orders.delivery_mode_id, ecom_orders.created_user_id, (ecom_orders.total - ecom_orders.delivery_fee) AS amount, ecom_orders.delivery_fee_id, vendors_list.id as vendor_id, vendors_list.name as vendor_name')
            ->from('ecom_orders')
            ->join('vendors_list', 'vendors_list.id = ecom_orders.vendor_user_id', 'left')
            ->where('ecom_orders.id', $order_id)
            ->get()
            ->row_array();
    }

    public function get_order_details_reject($order_id)
    {
        return $this->db->select('e.id, e.track_id, e.payment_id, e.vendor_user_id, e.delivery_mode_id, e.created_user_id, e.total, e.delivery_fee_id, p.id as payment_id, p.payment_method_id ,p.amount as payment_amount, p.status as payment_status, v.id as vendor_id, v.name as vendor_name')
            ->from('ecom_orders e')
            ->join('ecom_payments p', 'p.ecom_order_id = e.id', 'left')
            ->join('vendors_list v', 'v.vendor_user_id = e.vendor_user_id', 'left')
            ->where('e.id', $order_id)
            ->get()
            ->row_array();
    }

    public function update_order($order_id, $data)
    {
        $this->db->where('id', $order_id)
            ->update('ecom_orders', $data);

        return $this->db->affected_rows() > 0;
    }

    public function get_order_details_verify_otp($order_id)
    {
        return $this->db->select('e.id, e.track_id, e.order_pickup_otp, e.vendor_user_id, e.delivery_mode_id, e.created_user_id, e.total, e.delivery_fee, ed.promocode_id, ed.promotion_banner_id, ed.item_id, ed.vendor_product_variant_id, ed.qty, ed.offer_product_id, ed.offer_product_variant_id, ed.offer_product_qty, ed.price, ed.rate_of_discount, ed.sub_total, ed.discount, ed.promocode_discount, ed.promotion_banner_discount, ed.tax, ed.total, ed.service_charge_amount, ed.final_amount, ed.cancellation_message, ed.status, p.id as payment_id, p.payment_method_id, p.amount as payment_amount, p.status as payment_status, v.id as vendor_id, v.name as vendor_name, v.constituency_id, v.category_id')
            ->from('ecom_orders e')
            ->join('ecom_order_details ed', 'ed.ecom_order_id = e.id', 'left')
            ->join('ecom_payments p', 'p.ecom_order_id = e.id', 'left')
            ->join('vendors_list v', 'v.vendor_user_id = e.vendor_user_id', 'left')
            ->where('e.id', $order_id)
            ->get()
            ->row_array();
    }

    
}

