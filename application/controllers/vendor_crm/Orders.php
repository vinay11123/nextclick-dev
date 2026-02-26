<?php
error_reporting(E_ERROR | E_PARSE);
class Orders extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in())
            redirect('auth/login');

        $this->load->library('form_validation');
        $this->load->model('Ecommerce_model');
        $this->load->model('Order_statuses_model');
        $this->load->model('Order_status_logs_model');
        $this->load->model('Ecom_order_model');
        $this->load->model('Ecom_payment_model');
        $this->load->model('Ecom_order_status_model');
        $this->load->model('User_account_model');
        $this->load->model('User_model');
        $this->load->model('Notification_type_model');
        $this->load->model('Payment_type_model');
        $this->load->model('Food_order_deal_model');
        $this->load->model('Delivery_job_model');
        $this->load->model('Delivery_job_event_model');
        $this->load->model('Delivery_fee_model');
        $this->load->model('App_details_model');
        $this->load->model('Fcm_model');
        $this->load->model('Notifications_model');
        $this->load->model('Ecom_order_deatils_model');
        $this->load->model('Ecom_order_reject_request_model');
        $this->load->model('Business_address_model');
        $this->load->model('service_tax_model');

    }

    public function index()
    {
        $this->data['title'] = 'Vendor Orders';
        $this->template = 'vendorCrm/vendor_orders';
        $this->data['nav_type'] = 'vOrders';
        $vendor_id = $this->ion_auth->get_user_id();

        $filterStatus = $this->input->post('status');
        $onGoingFilterStatus = $this->input->post('onGoingStatus');
        $outForDeliveryFilterStatus = $this->input->post('outForDeliveryStatus');
        $rejectFilterOrderStatus = $this->input->post('rejectFilterStatus');


        if (isset($filterStatus)) {

            $this->data['vendorOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, '', $filterStatus);
            $this->data['orderStatus'] = $this->Order_statuses_model->get_all();

            $this->data['vendorOngoingOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'ongoing');
            $this->data['vendorOutfordeliveryOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'outfordelivery');
            $this->data['vendorRejectedOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'rejectedByVendor');
            // $this->data['vendorCancelledOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'cancelled');
            // $this->data['vendorRejectedbyPartnerOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'rejectedbyPartner');

            $this->data['allStatus'] = true;

            $this->load->view($this->template, $this->data);
        } else if (isset($onGoingFilterStatus)) {

            $this->data['vendorOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id);
            $this->data['orderStatus'] = $this->Order_statuses_model->get_all();

            $this->data['vendorOngoingOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'ongoing', $onGoingFilterStatus);
            $this->data['vendorOutfordeliveryOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'outfordelivery');
            $this->data['vendorRejectedOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'rejectedByVendor');
            // $this->data['vendorCancelledOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'cancelled');
            // $this->data['vendorRejectedbyPartnerOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'rejectedbyPartner');

            $this->data['ongoingStatus'] = true;
            $this->load->view($this->template, $this->data);
        } else if (isset($outForDeliveryFilterStatus)) {

            $this->data['vendorOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id);
            $this->data['orderStatus'] = $this->Order_statuses_model->get_all();

            $this->data['vendorOngoingOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'ongoing');
            $this->data['vendorOutfordeliveryOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'outfordelivery', $outForDeliveryFilterStatus);
            $this->data['vendorRejectedOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'rejectedByVendor');
            // $this->data['vendorCancelledOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'cancelled');
            // $this->data['vendorRejectedbyPartnerOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'rejectedbyPartner');

            $this->data['outStatus'] = true;
            $this->load->view($this->template, $this->data);
        } else if (isset($rejectFilterOrderStatus)) {

            $this->data['vendorOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id);
            $this->data['orderStatus'] = $this->Order_statuses_model->get_all();

            $this->data['vendorOngoingOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'ongoing');
            $this->data['vendorOutfordeliveryOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'outfordelivery');
            $this->data['vendorRejectedOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'rejected', $rejectFilterOrderStatus);
            // $this->data['vendorCancelledOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'cancelled');
            // $this->data['vendorRejectedbyPartnerOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'rejectedbyPartner');

            $this->data['rejectedStatus'] = true;
            $this->data['rejectFilterStatus'] = $rejectFilterOrderStatus;
            $this->load->view($this->template, $this->data);
        } else {

            $this->data['vendorOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id);

            $this->data['orderStatus'] = $this->Order_statuses_model->get_all();

            $this->data['vendorOngoingOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'ongoing');

            $this->data['vendorOutfordeliveryOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'outfordelivery');

            $this->data['vendorRejectedOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'rejectedByVendor');

            // $this->data['vendorCancelledOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'cancelled');

            // $this->data['vendorRejectedbyPartnerOrders'] = $this->Ecommerce_model->get_vendor_orders($vendor_id, 'rejectedbyPartner');

            $this->data['allStatus'] = true;

            $this->load->view($this->template, $this->data);
        }
    }

    public function order_details()
    {
        $this->data['title'] = 'Vendor Orders Details';
        $this->data['nav_type'] = 'vOrders';
        $vendor_id = $this->ion_auth->get_user_id();
        $id = base64_decode(base64_decode($this->input->get('id')));

        $queryResult = $this->Ecommerce_model->get_vendor_order_details($vendor_id, '', $id);

        $this->data['vendorOrders'] = $queryResult['query_result'];
        $this->data['custprod'] = $queryResult['status_result'];

        $this->load->view('vendorCrm/view_order_details', $this->data);
    }

    public function vendorEcomOrder($type)
    {
        if ($type == 'accept') {
            $vendor_id = $this->ion_auth->get_user_id();
            $order_id = $this->input->post('order_id');
            $preparation_time = $this->input->post('preparation_time');


            if (!empty($order_id)) {
                if ($preparation_time >= 10) {
                    $order_details = $this->Ecom_order_model->get_order_details($order_id);

                    $data = [
                        'preparation_time' => $this->input->post('preparation_time'),
                        'order_pickup_otp' => rand(99999, 999999),
                        'order_status_id' => $this->Ecom_order_status_model->fields('id')
                            ->where([
                                'delivery_mode_id' => $order_details['delivery_mode_id'],
                                'serial_number' => 102
                            ])
                            ->get()['id'],
                        'current_order_status_id' => ORDER_STATUS_ORDER_HAS_BEEN_PREPARING_ID,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    $is_updated = $this->Ecom_order_model->update_order($order_id, $data);

                    if ($is_updated) {
                        if ($order_details['delivery_mode_id'] == 2) {

                            $this->createDeliveryJob($order_id);

                            $order = $this->Ecom_order_model->where('id', $order_id)->get();
                            if ($order['delivery_mode_id'] == 2) {
                                $l = $this->vendor_list_model->with_location('fields: id, address, latitude, longitude')
                                    ->where('vendor_user_id', $vendor_id)
                                    ->get();

                                $lat = $l['location']['latitude'];
                                $lng = $l['location']['longitude'];
                                $max_order_distance = $this->Delivery_fee_model->where("id", $order_details['delivery_fee_id'])->get();
                                $distance = $max_order_distance['vendor_to_delivery_captain_max_distance'];

                                $query = $this->db->query('SELECT delivery_partner_location_tracking.delivery_partner_user_id as user_id, (6371.0088 * 2 * ASIN(SQRT(POWER(SIN(RADIANS(? - delivery_partner_location_tracking.latitude)) / 2, 2) + COS(RADIANS(?)) * COS(RADIANS(delivery_partner_location_tracking.latitude)) * POWER(SIN(RADIANS(? - delivery_partner_location_tracking.longitude)/2), 2)))) AS distance FROM delivery_partner_location_tracking join users on users.id = delivery_partner_location_tracking.delivery_partner_user_id where users.delivery_partner_status=1 HAVING distance < ?', [
                                    $lat,
                                    $lat,
                                    $lng,
                                    $distance
                                ]);

                                $deal = $query->result_array();
                                $user_ids = array();
                                foreach ($deal as $val) {
                                    $user_ids[] = $val['user_id'];
                                }
                                $paymentType = $this->Payment_type_model->getPaymentType($order_id)[0]['paymentType'];
                                if ($paymentType == 'COD') {
                                    $userDeals = $this->User_account_model->verifySecurityDepositeValue($user_ids, $order_id, $order_details['amount']);
                                } else {
                                    $userDeals = $deal;
                                }
                                for ($i = 0; $i < count($userDeals); $i++) {
                                    $delivered_id = $userDeals[$i]['user_id'];
                                    $acc = $this->Food_order_deal_model->insert([
                                        'order_id' => $order_id,
                                        'deal_id' => $userDeals[$i]['user_id']
                                    ]);
                                    $this->send_notification($delivered_id, DELIVERY_APP_CODE, "Order status", "New Order(id:" . $order['track_id'] . ") is Placed.! TRACK NOW", ['order_id' => $order['id'], 'notification_type' => $this->Notification_type_model->where(['app_details_id' => DELIVERY_APP_CODE, 'notification_code' => 'OD'])->get()]);
                                }
                            } elseif ($order['delivery_mode_id'] == 1) {
                                $acc = $this->ec->update([
                                    'id' => $order_id,
                                    'otp' => rand(1234, 9567)
                                ], 'id');
                            }

                            $response['job_status'] = 'Accepted';
                        }

                        $this->send_notification($order_details['created_user_id'], USER_APP_CODE, "Order status of( " . $order_details['track_id'] . " )", "Your Order successfully accepted by " . strtoupper($order_details['vendor_name']) . ".", ['order_id' => $order_id, 'notification_type' => $this->Notification_type_model->where(['app_details_id' => USER_APP_CODE, 'notification_code' => 'OD'])->get()]);

                        $response['message'] = 'Order has been accepted.';
                        $response['status'] = 'success';

                        header('Content-Type: application/json');
                        echo json_encode($response);
                        exit();
                    } else {

                        $response['message'] = 'Something went wrong.';

                        header('Content-Type: application/json');
                        echo json_encode($response);
                        exit();
                    }
                } else if (empty($preparation_time)) {
                    $response['message'] = 'Preparation time is required.';

                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit();
                } else {
                    $response['message'] = 'Minimum time is 10 mins.';

                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit();
                }
            } else {
                $response['message'] = 'Please provide order_id.';

                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            }
        } else if ($type == 'reject') {

            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $order_id = $this->input->post('order_id');
            $reason = $this->input->post('reason');
            $is_total_order_rejected = $this->input->post('is_total_order_rejected');

            if (!empty($order_id)) {

                $order_details = $this->Ecom_order_model->get_order_details_reject($order_id);

                if ($is_total_order_rejected == 1) {

                    if (!empty($order_details)) {
                        $is_updated = $this->Ecom_order_model->update([
                            'id' => $order_id,
                            'message' => $reason,
                            'current_order_status_id' => ORDER_STATUS_REJECTED_BY_VENDOR_ID,
                            'order_status_id' => $this->Ecom_order_status_model->fields('id')
                                ->where([
                                    'serial_number' => 300
                                ])->get()['id']
                        ], 'id');
                        if ($is_updated) {
                            $paymentDetails = $this->Ecom_payment_model->fields('payment_method_id')
                                ->where('ecom_order_id', $order_id)
                                ->get();
                            if ($order_details['payment']['payment_method_id'] != 1 || ($order_details['payment']['payment_method_id'] == 1 && $order_details['payment']['status'] == 2)) {
                                $this->User_model->debitFromWallet($this->config->item('super_admin_user_id'), $order_details["total"], $order_id);
                            }
                            if ($paymentDetails["payment_method_id"] == 3) {
                                $this->User_model->creditToWallet($order_details['created_user_id'], $order_details["total"], $order_id);
                            } else if ($order_details['payment']['payment_method_id'] == 2 || ($order_details['payment']['payment_method_id'] == 1 && $order_details['payment']['status'] == 2)) {
                                $this->load->module('payment/api/payment');
                                $this->payment->initiateRefund($order_id);
                            }
                            /**
                             * trigger push notificatios *
                             */
                            $this->send_notification($order_details['created_user_id'], USER_APP_CODE, "Order status of( " . $order_details['track_id'] . " )", "We're sorry to say, That your order has been rejectd by " . strtoupper($order_details['vendor']['name']) . " Due to " . $this->input->post('reason') . ".", [
                                'order_id' => $order_id,
                                'notification_type' => $this->Notification_type_model->where([
                                    'app_details_id' => USER_APP_CODE,
                                    'notification_code' => 'OD'
                                ])->get()
                            ]);
                            $response['job_status'] = 'Rejected';
                            $response['status'] = 'success';
                            $response['message'] = 'Order has been rejected.';

                            header('Content-Type: application/json');
                            echo json_encode($response);
                            exit();
                        } else {
                            $response['message'] = 'Something went wrong.!';

                            header('Content-Type: application/json');
                            echo json_encode($response);
                            exit();
                        }
                    } else {
                        echo 'Not found';
                    }
                } else {
                    $rejected_products = $this->input->post('rejected_products');
                    if (!empty($rejected_products)) {
                        $this->Ecom_order_model->update([
                            'id' => $order_id,
                            'message' => $reason,
                        ], 'id');

                        $this->Ecom_order_deatils_model->update([
                            'status' => 2,
                            'ecom_order_id' => $order_id
                        ], 'ecom_order_id');

                        foreach ($rejected_products as $rp_key => $product) {
                            $this->Ecom_order_deatils_model->update([
                                'status' => 4
                            ], [
                                'ecom_order_id' => $order_id,
                                'item_id' => $product['product_id'],
                                'vendor_product_variant_id' => $product['product_variant_id']
                            ]);
                        }

                        $this->Ecom_order_reject_request_model->insert([
                            'ecom_order_id' => $order_id,
                            'customer_user_id' => $order_details['created_user_id'],
                            'vendor_user_id' => $order_details['vendor_user_id'],
                            'status' => 1,
                        ]);
                        /**
                         * trigger push notificatios *
                         */
                        $this->send_notification($order_details['created_user_id'], USER_APP_CODE, "Order status of( " . $order_details['track_id'] . " )", "We're sorry to say, That your order has been rejectd by " . strtoupper($order_details['vendor']['name']) . " Due to " . $this->input->post('reason') . ".", [
                            'order_id' => $order_id,
                            'notification_type' => $this->Notification_type_model->where([
                                'app_details_id' => USER_APP_CODE,
                                'notification_code' => 'OD'
                            ])->get()
                        ]);
                        $response['job_status'] = 'Rejected';
                        $response['status'] = 'success';
                        $response['message'] = 'Order has been rejected.';

                        header('Content-Type: application/json');
                        echo json_encode($response);
                        exit();
                    } else {

                        $response['message'] = 'Please add rejected products.';

                        header('Content-Type: application/json');
                        echo json_encode($response);
                        exit();
                    }
                }
            } else {

                $response['message'] = 'Please provide order_id.';

                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            }
        } elseif ($type == 'verify_out_for_delivery') {
            $order_id = $this->input->post('order_id');
            if (!empty($order_id)) {
                $order_details = $this->Ecom_order_model->fields('id, track_id, delivery_mode_id, order_pickup_otp, total, delivery_fee, created_user_id, vendor_user_id')
                    ->with_ecom_order_details('fields: id, ecom_order_id, promocode_id, promotion_banner_id, item_id, vendor_product_variant_id, qty, offer_product_id, offer_product_variant_id, offer_product_qty, price, rate_of_discount, sub_total, discount, promocode_discount, promotion_banner_discount, tax, total, service_charge_amount, final_amount, cancellation_message, status')
                    ->with_payment('fields: id, payment_method_id, amount, status')
                    ->with_vendor('fields: id, name, constituency_id, category_id')
                    ->where('id', $order_id)
                    ->get();

                if (!empty($order_details)) {
                    if ($order_details['order_pickup_otp'] == $this->input->post('otp')) {
                        $is_updated = $this->Ecom_order_model->update([
                            'id' => $order_id,
                            'current_order_status_id' => ORDER_STATUS_OUT_FOR_DELIVERY_ID,
                            'order_status_id' => $this->Ecom_order_status_model->fields('id')
                                ->where([
                                    'delivery_mode_id' => $order_details['delivery_mode_id'],
                                    'serial_number' => ($order_details['delivery_mode_id'] == 1) ? 104 : 103
                                ])
                                ->get()['id']
                        ], 'id');
                        if ($is_updated) {
                            /*
                             * $delivery_job = $this->delivery_job_model->where(['ecom_order_id' => $order_id, 'status >=' => 501])->get();
                             * $this->delivery_job_model->update([
                             * 'id' => $delivery_job['id'],
                             * 'status' => 505,
                             * ], 'id');
                             */
                            $notify_users = [
                                $order_details['created_user_id']
                            ];
                            /*
                             * if(! empty($delivery_job))
                             * array_push($notify_users, $delivery_job['delivery_boy_user_id']);
                             */
                            // Tringger notification
                            $this->send_notification($notify_users, USER_APP_CODE, "Order status of( " . $order_details['track_id'] . " )", "Your order is out for delivery by " . strtoupper($order_details['vendor']['name']) . ".", [
                                'order_id' => $order_id,
                                'notification_type' => $this->Notification_type_model->where([
                                    'app_details_id' => USER_APP_CODE,
                                    'notification_code' => 'OD'
                                ])
                                    ->get()
                            ]);
                            // Wallet money distribution
                            if (!($order_details['delivery_mode_id'] == 1 && ($order_details['payment']['payment_method_id'] == 1 && $order_details['payment']['status'] != 2))) {
                                $total_service_charge = 0;
                                if (!empty($order_details['ecom_order_details'])) {
                                    $vendorConstituency = $this->Business_address_model->where([
                                        'list_id' => $order_details['vendor']['id']
                                    ])->get();

                                    foreach ($order_details['ecom_order_details'] as $key => $order_item) {

                                        $data = $this->service_tax_model->calculate_service_charge($order_item, $order_details['vendor']['category_id'], $vendorConstituency['constituency'], $vendorConstituency['state'], $vendorConstituency['district']);

                                        $each_item_service_charge = ($data['success'] == TRUE && !empty($data['data'])) ? floatval($order_item['total']) * (intval($data['data']['service_tax']) / 100) : 0;

                                        $final_amount_after_sc = floatval($order_item['total']) - $each_item_service_charge;
                                        $total_service_charge += $each_item_service_charge;
                                        $this->ecom_order_deatils_model->update([
                                            'id' => $order_item['id'],
                                            'service_charge_amount' => $each_item_service_charge,
                                            'final_amount' => $final_amount_after_sc
                                        ], 'id');
                                    }
                                }
                                $this->Ecom_order_model->update([
                                    'id' => $order_details['id'],
                                    'total_service_charge' => $total_service_charge
                                ], 'id');
                                // $this->user_model->creditToIncomeWallet($this->config->item('super_admin_user_id', 'ion_auth'), $total_service_charge, $order_id);
                                $txn_id = 'NC-' . generate_trasaction_no();
                                $amount = floatval($order_details['total']) - floatval($order_details['delivery_fee']) - floatval($total_service_charge);
                                $this->user_model->payment_update($this->config->item('super_admin_user_id'), $amount, 'DEBIT', "wallet", $txn_id, $order_id);
                                $txn_id = 'NC-' . generate_trasaction_no();
                                $this->user_model->payment_update($order_details['vendor_user_id'], $amount, 'CREDIT', "wallet", $txn_id, $order_id);
                            }
                            // trigger push notification to user//

                            $this->send_notification($order_details['created_user_id'], USER_APP_CODE, "Delivery Boy Pickup the order ", "And heading to your delivery location", [
                                'order_id' => $order_id,
                                'notification_type' => $this->Notification_type_model->where([
                                    'app_details_id' => USER_APP_CODE,
                                    'notification_code' => 'OD'
                                ])
                                    ->get()
                            ]);
                            $response['message'] = $this->Ecom_order_status_model->fields('status')
                                ->where([
                                    'delivery_mode_id' => $order_details['delivery_mode_id'],
                                    'serial_number' => ($order_details['delivery_mode_id'] == 1) ? 104 : 103
                                ])
                                ->get()['status'];

                            $response['job_status'] = 'Valid';
                            $response['status'] = 'success';

                            header('Content-Type: application/json');
                            echo json_encode($response);
                            exit();
                        } else {
                            echo 'Something went wrong.!';
                        }
                    } else if (empty($this->input->post('otp'))) {
                        echo 'OTP is required.';
                    } else {
                        echo 'Invalid OTP.!';
                    }
                } else {
                    echo 'Not found.';
                }
            } else {
                echo 'Please provide order_id.';
            }
        } elseif ($type == 'extend_preparation_time') {
            $order_id = $this->input->post('order_id');
            $preparation_time = $this->input->post('preparation_time');

            if (!empty($order_id)) {
                if ($preparation_time >= 10) {
                    $order_details = $this->Ecom_order_model->fields('id, track_id, preparation_time, delivery_mode_id, created_user_id')
                        ->with_vendor('fields: id, name')
                        ->where('id', $order_id)
                        ->get();
                    if (!empty($order_details)) {
                        $is_updated = $this->Ecom_order_model->update([
                            'id' => $order_id,
                            'preparation_time' => $order_details['preparation_time'] + $this->input->post('preparation_time')
                        ], 'id');
                        if ($is_updated) {
                            /**
                             * trigger push notificatios *
                             */
                            $delivery_job = $this->Delivery_job_model->where([
                                'ecom_order_id' => $order_id,
                                'status' => 1
                            ])->get();
                            $notify_users = [
                                $order_details['created_user_id']
                            ];
                            if (!empty($delivery_job))
                                array_push($notify_users, $delivery_job['delivery_boy_user_id']);

                            $this->send_notification($notify_users, USER_APP_CODE, "Order status of( " . $order_details['track_id'] . " )", "Preparation time is updated to " . $this->input->post('preparation_time') . " by " . strtoupper($order_details['vendor']['name']) . ".", [
                                'order_id' => $order_id,
                                'notification_type' => $this->Notification_type_model->where([
                                    'app_details_id' => USER_APP_CODE,
                                    'notification_code' => 'OD'
                                ])->get()
                            ]);

                            $response['job_status'] = 'Modified';
                            $response['status'] = 'success';
                            $response['message'] = 'Preparation time has been modified.';

                            header('Content-Type: application/json');
                            echo json_encode($response);
                            exit();
                        } else {

                            $response['message'] = 'Something went wrong.!';

                            header('Content-Type: application/json');
                            echo json_encode($response);
                            exit();
                        }
                    } else {

                        $response['message'] = 'Not found.';

                        header('Content-Type: application/json');
                        echo json_encode($response);
                        exit();
                    }
                } else {
                    $response['message'] = 'Minimum Preparation time is 10 mins.';

                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit();
                }
            } else {
                $response['message'] = 'Please provide order_id.';

                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            }
        }
    }


    public function send_notification($user_id = [], $app_details_id = NULL, $title = NULL, $message = NULL, $payload = [])
    {
        $tokens = [];
        if (!empty($user_id)) {
            $app_details = $this->App_details_model->where('id', $app_details_id)->get();
            $result = $this->Fcm_model->where('user_id', $user_id)->where('app_details_id', $app_details_id)->get_all();
            $tokens = !empty($result) ? array_unique(array_column($result, 'token')) : [];
            $notifications = [];
            if (is_array($user_id)) {
                foreach ($user_id as $id) {
                    if (!empty($notifications) && !in_array($id, array_column($notifications, 'notified_user_id'))) {
                        array_push($notifications, [
                            'notification_type_id' => $payload['notification_type']['id'],
                            'app_details_id' => $app_details_id,
                            'title' => $title,
                            'message' => $message,
                            'notified_user_id' => $id,
                            'ecom_order_id' => !empty($payload['order_id']) ? $payload['order_id'] : NULL,
                            'ticket_id' => !empty($payload['ticket_id']) ? $payload['ticket_id'] : NULL
                        ]);
                    } elseif (empty($notifications)) {
                        array_push($notifications, [
                            'notification_type_id' => $payload['notification_type']['id'],
                            'app_details_id' => $app_details_id,
                            'title' => $title,
                            'message' => $message,
                            'notified_user_id' => $id,
                            'ecom_order_id' => !empty($payload['order_id']) ? $payload['order_id'] : NULL,
                            'ticket_id' => !empty($payload['ticket_id']) ? $payload['ticket_id'] : NULL
                        ]);
                    }
                }
            } else {
                array_push($notifications, [
                    'notification_type_id' => $payload['notification_type']['id'],
                    'app_details_id' => $app_details_id,
                    'title' => $title,
                    'message' => $message,
                    'notified_user_id' => $user_id,
                    'ecom_order_id' => !empty($payload['order_id']) ? $payload['order_id'] : NULL,
                    'ticket_id' => !empty($payload['ticket_id']) ? $payload['ticket_id'] : NULL
                ]);
            }

            $this->Notifications_model->insert($notifications);
        }
        $this->fcm->setTitle($title);
        $this->fcm->setMessage($message);
        $this->fcm->setPayload($payload);
        $this->fcm->setImage('https://firebase.google.com/_static/9f55fd91be/images/firebase/lockup.png');
        $this->fcm->setIsBackground(false);
        /**
         * Get the compiled notification data as an array
         */
        $json = $this->fcm->getPush();
        return json_decode($this->fcm->sendMultiple($tokens, $json, $app_details['fcm_server_key']));
    }

    private function createDeliveryJob($order_id)
    {
        try {
            $responseArr = [];
            $deliveryJob = $this->Delivery_job_model->get([
                'ecom_order_id' => $order_id,
                'job_type' => 1
            ]);
            if (!$deliveryJob) {
                $jobID = $this->Delivery_job_model->insert([
                    'job_id' => generate_serial_no('DJ', 3, rand(999, 9999)),
                    "ecom_order_id" => $order_id,
                    'status' => 501
                ]);
                $eventID = $this->saveDeliveryJobEvent($jobID, 'CREATED');
                $responseArr = $this->Delivery_job_model->fields('id, job_id, ecom_order_id, status')->get($jobID); // Assuming get() function fetches by ID
                // Adjust response format based on your application needs
                $this->output->set_content_type('application/json')->set_output(json_encode($responseArr)); // Example JSON output
            } else {
                $this->output->set_content_type('application/json')->set_output(json_encode(["error" => "JOB_ALREADY_EXISTS"])); // Example JSON output
            }
        } catch (Exception $ex) {
            $this->output->set_status_header(500);
            $this->output->set_output(json_encode(["error" => "INTERNAL_SERVER_ERROR"])); // Example JSON output
        }
    }

    private function saveDeliveryJobEvent($jobID, $event, $deliveryBoyID = null)
    {
        try {
            $eventID = null;
            switch ($event) {
                case 'CREATED':
                    $eventID = $this->Delivery_job_event_model->insert([
                        'job_id' => $jobID,
                        'event' => $event
                    ]);
                    break;
                case 'ACCEPTED':
                case 'REJECTED':
                    $eventID = $this->Delivery_job_event_model->insert([
                        'job_id' => $jobID,
                        'delivery_boy_user_id' => $deliveryBoyID,
                        'event' => $event
                    ]);
                    break;
                default:
                    break;
            }
            return [
                "success" => true,
                "data" => [
                    "event_id" => $eventID
                ]
            ];
        } catch (Exception $ex) {
            return [
                "success" => false,
                "data" => $ex
            ];
        }
    }

    public function order_details_ongoing()
    {
        $this->data['title'] = 'Vendor Ongoing Orders Details';
        $this->data['nav_type'] = 'vOrders';
        $vendor_id = $this->ion_auth->get_user_id();
        $id = base64_decode(base64_decode($this->input->get('id')));

        $queryResult = $this->Ecommerce_model->get_vendor_order_details($vendor_id, '', $id);

        $this->data['vendorOrders'] = $queryResult['query_result'];
        $this->data['custprod'] = $queryResult['status_result'];
        $this->load->view('vendorCrm/view_order_details_ongoing', $this->data);
    }
}
