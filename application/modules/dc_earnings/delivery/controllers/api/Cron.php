<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Cron extends MY_REST_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('notification_type_model');
        $this->load->model('notifications_model');
    }
    
    
    public function allocate_orders_old_get() {
        $fcm = NULL;
        //all accepted orders
        $all_accepted_orders_query = $this->db->query("
                select eo.id, eo.vendor_user_id, eo.created_at, eo.track_id, l.latitude, l.longitude from ecom_orders as eo
                join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id
                join locations as l on l.id = vl.location_id
                where eo.order_status_id = 11 and eo.updated_at >= CURRENT_TIMESTAMP - INTERVAL 5 MINUTE;
            ");
        if(! empty($all_accepted_orders_query)){
            $all_accepted_orders = $all_accepted_orders_query->result_array();
        }else {
            $all_accepted_orders = [];
        }
        
        if(! empty($all_accepted_orders)){foreach ($all_accepted_orders as $key => $order){
            if(! empty($order['latitude']) && ! empty($order['longitude'])){
                $notified_user_ids = $this->notifications_model->fields('notified_user_id')->where('ecom_order_id', $order['id'])->where('app_details_id', DELIVERY_APP_CODE)->get_all();
                $where = ' ';
                if(! empty($notified_user_ids)){
                    $where = " u.id not in(".implode(",", array_column($notified_user_ids, 'notified_user_id')).") and";
                }
                //3959 for miles, 6371 for KM
                $delivery_boys_tokens_query = $this->db->query("
                    SELECT u.id, fcm.token, ( 6371 * acos( cos( radians('".$order['latitude']."') ) * cos( radians( dplt.latitude ) ) * cos( radians( dplt.longitude ) - radians('".$order['longitude']."') ) + sin( radians('".$order['latitude']."') ) * sin(radians(dplt.latitude)) ) ) AS distance
                    FROM delivery_partner_location_tracking as dplt
                    join users as u on u.id = dplt.delivery_partner_user_id
                    left join delivery_partner_sessions as dps on dps.delivery_partner_user_id = dplt.delivery_partner_user_id
                    left join fcm on fcm.user_id = dplt.delivery_partner_user_id
                    where ".$where." u.delivery_partner_status = 1  and u.delivery_partner_approval_status = 1 and fcm.app_details_id = ".DELIVERY_APP_CODE."
                    group by u.id
                    HAVING distance < ".$this->config->item('service_distance_in_km')." ORDER BY distance
                ");
                if($delivery_boys_tokens_query){
                    $delivery_boys_tokens = $delivery_boys_tokens_query->result_array();
                    $fcm = $this->send_notification(array_unique(array_column($delivery_boys_tokens, 'id')), DELIVERY_APP_CODE, "Order Alert", "New Order(id:".$order['track_id'].") is placed.", ['order_id' => $order['id'], 'notification_type' => $this->notification_type_model->where(['app_details_id' => DELIVERY_APP_CODE, 'notification_code' => 'OD'])->get()]);
                }else {
                    $delivery_boys_tokens = [];
                }
            }
            
        }}
        
        $this->set_response_simple($delivery_boys_tokens, 'Success.', REST_Controller::HTTP_OK, TRUE);
    }

    public function allocate_orders_get() {
        $this->allocate_pickupanddrop_orders_get();
        $fcm = NULL;
        //all accepted orders
        $all_accepted_orders_query = $this->db->query("
                select eo.id, eo.vendor_user_id, eo.created_at, eo.track_id, l.latitude, l.longitude from delivery_jobs as dj 
                INNER JOIN ecom_orders as eo ON dj.ecom_order_id = eo.id
                join vendors_list as vl on vl.vendor_user_id = eo.vendor_user_id
                join locations as l on l.id = vl.location_id
                where dj.job_type=1 AND dj.delivery_boy_user_id is null AND eo.order_status_id = 11 and eo.updated_at >= CONVERT_TZ(now(),'+00:00','+05:30') - INTERVAL 5 MINUTE
            ");
        if(! empty($all_accepted_orders_query)){
            $all_accepted_orders = $all_accepted_orders_query->result_array();
        }else {
            $all_accepted_orders = [];
        }
        if(! empty($all_accepted_orders)){foreach ($all_accepted_orders as $key => $order){
            if(! empty($order['latitude']) && ! empty($order['longitude'])){
                $notified_user_ids = $this->notifications_model->fields('notified_user_id')->where('ecom_order_id', $order['id'])->where('app_details_id', DELIVERY_APP_CODE)->get_all();
                $where = ' ';
                if(! empty($notified_user_ids)){
                    $where = " u.id not in(".implode(",", array_column($notified_user_ids, 'notified_user_id')).") and";
                }
                $notinQuery = " u.id NOT IN (select delivery_job_events.delivery_boy_user_id from delivery_jobs as dj INNER JOIN delivery_job_events ON delivery_job_events.job_id=dj.id AND delivery_job_events.event='REJECTED' WHERE ecom_order_id=".$order["id"].") AND ";
                //3959 for miles, 6371 for KM
                $delivery_boys_tokens_query = $this->db->query("
                    SELECT u.id, fcm.token, ( 6371 * acos( cos( radians('".$order['latitude']."') ) * cos( radians( dplt.latitude ) ) * cos( radians( dplt.longitude ) - radians('".$order['longitude']."') ) + sin( radians('".$order['latitude']."') ) * sin(radians(dplt.latitude)) ) ) AS distance, COUNT(dev.id) AS delivery_count
                    FROM delivery_partner_location_tracking as dplt
                    join users as u on u.id = dplt.delivery_partner_user_id
                    left join delivery_partner_sessions as dps on dps.delivery_partner_user_id = dplt.delivery_partner_user_id
                    left join fcm on fcm.user_id = dplt.delivery_partner_user_id
                    left join delivery_job_events as dev ON dev.delivery_boy_user_id= u.id AND DATE(dev.created_at) = CURDATE()
                    where ".$where.$notinQuery." u.delivery_partner_status = 1  and u.delivery_partner_approval_status = 1 and fcm.app_details_id = ".DELIVERY_APP_CODE."
                    group by u.id
                    HAVING distance < ".$this->config->item('service_distance_in_km')." ORDER BY delivery_count, distance
                ");
                if($delivery_boys_tokens_query){
                    $delivery_boys_tokens = $delivery_boys_tokens_query->result_array();
                    $fcm = $this->send_notification(array_unique(array_column($delivery_boys_tokens, 'id')), DELIVERY_APP_CODE, "Order Alert", "New Order(id:".$order['track_id'].") is placed.", [
                        'order_id' => $order['id'],
                        'additional_info' => [
                            "delivery_count" => $order['delivery_count']
                        ],
                        'notification_type' => $this->notification_type_model->where(['app_details_id' => DELIVERY_APP_CODE, 'notification_code' => 'OD'])->get()
                    ]);
                }else {
                    $delivery_boys_tokens = [];
                }
            }
            
        }}
        
        $this->set_response_simple($delivery_boys_tokens, 'Success.', REST_Controller::HTTP_OK, TRUE);
    }

    public function allocate_pickupanddrop_orders_get() {
        $fcm = NULL;
        //all accepted orders
        $all_accepted_orders_query = $this->db->query("
                select eo.id, eo.created_at, eo.track_id, l.latitude, l.longitude from delivery_jobs as dj 
                INNER JOIN pickup_orders as eo ON dj.pickup_order_id = eo.id
                join users_address as vl on vl.id = eo.pickup_address_id
                join locations as l on l.id = vl.location_id
                where dj.job_type=1 AND dj.delivery_boy_user_id is null AND eo.order_status_id = 11 and
                 eo.created_at >= CONVERT_TZ(now(),'+00:00','+05:30') - INTERVAL 60 MINUTE
            ");
        if(! empty($all_accepted_orders_query)){
            $all_accepted_orders = $all_accepted_orders_query->result_array();
        }else {
            $all_accepted_orders = [];
        }
        if(! empty($all_accepted_orders)){foreach ($all_accepted_orders as $key => $order){
            if(! empty($order['latitude']) && ! empty($order['longitude'])){
                $notified_user_ids = $this->notifications_model->fields('notified_user_id')->where('pickup_order_id', $order['id'])->where('app_details_id', DELIVERY_APP_CODE)->get_all();
                $where = ' ';
                if(! empty($notified_user_ids)){
                    $where = " u.id not in(".implode(",", array_column($notified_user_ids, 'notified_user_id')).") and";
                }
                $notinQuery = " u.id NOT IN (select delivery_job_events.delivery_boy_user_id from delivery_jobs as dj INNER JOIN delivery_job_events ON delivery_job_events.job_id=dj.id AND delivery_job_events.event='REJECTED' WHERE ecom_order_id=".$order["id"].") AND ";
                //3959 for miles, 6371 for KM
                $delivery_boys_tokens_query = $this->db->query("
                    SELECT u.id, fcm.token, ( 6371 * acos( cos( radians('".$order['latitude']."') ) * cos( radians( dplt.latitude ) ) * cos( radians( dplt.longitude ) - radians('".$order['longitude']."') ) + sin( radians('".$order['latitude']."') ) * sin(radians(dplt.latitude)) ) ) AS distance, COUNT(dev.id) AS delivery_count
                    FROM delivery_partner_location_tracking as dplt
                    join users as u on u.id = dplt.delivery_partner_user_id
                    left join delivery_partner_sessions as dps on dps.delivery_partner_user_id = dplt.delivery_partner_user_id
                    left join fcm on fcm.user_id = dplt.delivery_partner_user_id
                    left join delivery_job_events as dev ON dev.delivery_boy_user_id= u.id AND DATE(dev.created_at) = CURDATE()
                    where ".$where.$notinQuery." u.delivery_partner_status = 1  and u.delivery_partner_approval_status = 1 and fcm.app_details_id = ".DELIVERY_APP_CODE."
                    group by u.id
                    HAVING distance < ".$this->config->item('service_distance_in_km')." ORDER BY delivery_count, distance
                ");
                if($delivery_boys_tokens_query){
                    $delivery_boys_tokens = $delivery_boys_tokens_query->result_array();
                    $fcm = $this->send_notification_pickupanddrop(array_unique(array_column($delivery_boys_tokens, 'id')), DELIVERY_APP_CODE, "Order Alert", "New Order(id:".$order['track_id'].") is placed.", [
                        'pickup_order_id' => $order['id'],
                        'additional_info' => [
                            "delivery_count" => $order['delivery_count']
                        ],
                        'notification_type' => $this->notification_type_model->where(['app_details_id' => DELIVERY_APP_CODE, 'notification_code' => 'OD'])->get()
                    ]);
                }else {
                    $delivery_boys_tokens = [];
                }
            }
            
        }}
        
        $this->set_response_simple($delivery_boys_tokens, 'Success.', REST_Controller::HTTP_OK, TRUE);
    }
}

