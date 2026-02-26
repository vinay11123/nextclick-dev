<?php
error_reporting(E_ERROR | E_PARSE);

class DeleteUser extends MY_Controller
{
    public function delete_user()
    {
        $user_sql = "select * from users where phone='" . $_GET['phone_no'] . "'";
        $query = $this->db->query($user_sql);
        $user_data = $query->result_array();
        $user_id = $user_data[0]['id'];

        $this->load->helper('url');
        $url_parts = parse_url(current_url());
        $host = str_replace('www.', '', $url_parts['host']);

        if ($user_id && $host != 'app.nextclick.in') {

            $sql_advertisements = "DELETE FROM `advertisements` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_advertisements);

            $sql_amenities = "DELETE FROM `amenities` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_amenities);

            $sql_banner_cost = "DELETE FROM `banner_cost` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_banner_cost);

            $sql_booking_items = "DELETE FROM `booking_items` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_booking_items);


            $sql_booking_items = "DELETE FROM `booking_items` WHERE `booking_id` IN (SELECT id FROM `bookings` WHERE `created_user_id` = " . $user_id . ")";
            $query = $this->db->query($sql_booking_items);


            $sql_booking_items = "DELETE FROM `booking_items` WHERE `booking_id` IN (SELECT id FROM `bookings` WHERE `vendor_id` IN (SELECT id FROM `vendors_list` WHERE `vendor_user_id` =" . $user_id . "))";
            $query = $this->db->query($sql_booking_items);

            $sql_bookings = "DELETE FROM `bookings` WHERE `vendor_id` IN (SELECT id  vendor_id FROM `vendors_list` WHERE `vendor_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_bookings);

            $sql_customer_support = "DELETE FROM `customer_support` WHERE `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_customer_support);


            $sql_deleted_items = "DELETE FROM `deleted_items` WHERE `vendor_id` IN (SELECT id  vendor_id FROM `vendors_list` WHERE `vendor_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_deleted_items);

            $sql_deliveryboy_ratings = "DELETE FROM `deliveryboy_ratings` where `list_id` in (SELECT id vendor_id FROM `vendors_list` where `vendor_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_deliveryboy_ratings);

            $sql_deliveryboy_ratings = "DELETE FROM `deliveryboy_ratings` where `user_id` in (SELECT id FROM `users` where id =" . $user_id . ")";
            $query = $this->db->query($sql_deliveryboy_ratings);

            $sql_delivery_boy_address = "DELETE FROM `delivery_boy_address` where `user_id` in (SELECT id FROM `users` where id =" . $user_id . ")";
            $query = $this->db->query($sql_delivery_boy_address);

            $sql_delivery_boy_biometrics = "DELETE FROM `delivery_boy_biometrics` where `user_id` in (SELECT id FROM `users` where id =" . $user_id . ")";
            $query = $this->db->query($sql_delivery_boy_biometrics);

            $sql_delivery_boy_payments = "DELETE FROM `delivery_boy_payments` where `created_user_id` in (SELECT id FROM `users` where id =" . $user_id . ")";
            $query = $this->db->query($sql_delivery_boy_payments);

            $sql_delivery_boy_performance_extraction_ratings = "DELETE FROM `delivery_boy_performance_extraction_ratings` where `performance_extraction_id` in (SELECT id FROM `delivery_boy_performance_extraction` where `delivery_boy_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_delivery_boy_performance_extraction_ratings);

            $sql_delivery_boy_performance_extraction_ratings = "DELETE FROM `delivery_boy_performance_extraction` where `delivery_boy_user_id` in (SELECT id FROM `users` where id =" . $user_id . ")";
            $query = $this->db->query($sql_delivery_boy_performance_extraction_ratings);


            $sql_delivery_job_events = "DELETE FROM `delivery_job_events` where `job_id` in (SELECT id FROM `delivery_jobs` WHERE `ecom_order_id` in(SELECT id FROM `ecom_orders` where `created_user_id` =" . $user_id . ") or `pickup_order_id` in (SELECT id FROM `ecom_orders` where `created_user_id` =" . $user_id . "))";
            $query = $this->db->query($sql_delivery_job_events);

            $sql_delivery_job_logs = "DELETE FROM `delivery_job_logs` where `delivery_job_id` in (SELECT id FROM `delivery_jobs` WHERE `ecom_order_id` in(SELECT id FROM `ecom_orders` where `created_user_id` =" . $user_id . ") or `pickup_order_id` in (SELECT id FROM `ecom_orders` where `created_user_id` =" . $user_id . "))";
            $query = $this->db->query($sql_delivery_job_logs);

            $sql_delivery_job_rejections = "DELETE FROM `delivery_job_rejections` where `rejected_by` in (SELECT id FROM `users` where id =" . $user_id . ")";
            $query = $this->db->query($sql_delivery_job_rejections);


            $sql_delivery_jobs = "DELETE FROM `delivery_jobs` WHERE `ecom_order_id` in(SELECT id FROM `ecom_orders` where `created_user_id` =" . $user_id . ") or `pickup_order_id` in (SELECT id FROM `ecom_orders` where `created_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_delivery_jobs);

            $sql_delivery_partner_bank_details = "DELETE FROM `delivery_partner_bank_details` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_delivery_partner_bank_details);

            $sql_delivery_partner_location_tracking = "DELETE FROM `delivery_partner_location_tracking` where `delivery_partner_user_id` =" . $user_id;
            $query = $this->db->query($sql_delivery_partner_location_tracking);

            $sql_delivery_partner_sessions = "DELETE FROM `delivery_partner_sessions` where `delivery_partner_user_id` =" . $user_id;
            $query = $this->db->query($sql_delivery_partner_sessions);

            $sql_ecom_order_details = "DELETE FROM `ecom_order_details` WHERE `ecom_order_id` in (SELECT id FROM `ecom_orders` where `created_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_ecom_order_details);

            $sql_ecom_order_reject_requests = "DELETE FROM `ecom_order_reject_requests` WHERE `ecom_order_id` in (SELECT id FROM `ecom_orders` where `created_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_ecom_order_reject_requests);

            $sql_ecom_order_reject_requests = "DELETE FROM `ecom_order_reject_requests` WHERE `customer_user_id` = " . $user_id . " OR `vendor_user_id` = " . $user_id;
            $query = $this->db->query($sql_ecom_order_reject_requests);


            $sql_ecom_order_status_log = "DELETE FROM `ecom_order_status_log` WHERE `ecom_order_id` in (SELECT id FROM `ecom_orders` where `created_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_ecom_order_status_log);

            $sql_order_ecom_status_log = "DELETE FROM `order_status_logs` WHERE `order_id` in (SELECT id FROM `ecom_orders` where `created_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_order_ecom_status_log);


            $sql_ecom_payments = "DELETE FROM `ecom_payments` WHERE `ecom_order_id` in (SELECT id FROM `ecom_orders` where `created_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_ecom_payments);

            $sql_ecom_payments = "DELETE FROM `ecom_payments` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_ecom_payments);


            $sql_payment_links = "DELETE FROM `payment_links` WHERE `ecom_order_id` in (SELECT id FROM `ecom_orders` where `created_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_payment_links);

            $sql_payment_refunds = "DELETE FROM `payment_refunds` WHERE `ecom_order_id` in (SELECT id FROM `ecom_orders` where `created_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_payment_refunds);

            $sql_ecom_orders = "DELETE FROM `ecom_orders` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_ecom_orders);

            $sql_ecom_settings = "DELETE FROM `ecom_settings` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_ecom_settings);

            $sql_executive_address = "DELETE FROM `executive_address` where `user_id` =" . $user_id;
            $query = $this->db->query($sql_executive_address);

            $sql_executive_biometrics = "DELETE FROM `executive_biometrics` where `user_id` =" . $user_id;
            $query = $this->db->query($sql_executive_biometrics);

            $sql_fcm = "DELETE FROM `fcm` where `user_id` =" . $user_id;
            $query = $this->db->query($sql_fcm);


            $sql_floating_payments = "DELETE FROM `floating_payments` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_floating_payments);

            $sql_food_cart = "DELETE FROM `food_cart` WHERE `created_user_id` =" . $user_id . " or `vendor_user_id` =" . $user_id;
            $query = $this->db->query($sql_food_cart);

            $sql_lead_details = "DELETE FROM `lead_details` where `vendor_id` in (SELECT id vendor_id FROM `vendors_list` where `vendor_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_lead_details);


            $sql_lead_details = "DELETE FROM `lead_details` where `lead_id` in (SELECT id FROM `leads` where `user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_lead_details);

            $sql_leads = "DELETE FROM `leads` where `user_id` =" . $user_id;
            $query = $this->db->query($sql_leads);

            $sql_manual_payments = "DELETE FROM `manual_payments` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_manual_payments);

            $sql_notifications = "DELETE FROM `notifications` where `notified_user_id` =" . $user_id;
            $query = $this->db->query($sql_notifications);

            $sql_payment_links = "DELETE FROM `payment_links` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_payment_links);

            $sql_payouts = "DELETE FROM `payouts` where `user_id` =" . $user_id;
            $query = $this->db->query($sql_payouts);

            $sql_pickup_order_status_log = "DELETE FROM `ecom_order_status_log` WHERE `pickup_order_id` in (SELECT id FROM `pickup_orders` where `created_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_pickup_order_status_log);

            $sql_order_pickup_status_log = "DELETE FROM `order_status_logs` WHERE `order_id` in (SELECT id FROM `pickup_orders` where `created_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_order_ecom_status_log);

            $sql_pickup_orders = "DELETE FROM `pickup_orders` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_pickup_orders);


            $sql_product_search_history = "DELETE FROM `product_search_history` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_product_search_history);

            $sql_promotion_banners_joined_users = "DELETE FROM `promotion_banners_joined_users` where `joined_user_id` =" . $user_id;
            $query = $this->db->query($sql_promotion_banners_joined_users);


            $sql_promotion_banners_vendor_offer_products = "DELETE FROM `promotion_banners_vendor_offer_products` where `vendor_user_id` =" . $user_id;
            $query = $this->db->query($sql_promotion_banners_vendor_offer_products);

            $sql_promotion_banners_vendor_products = "DELETE FROM `promotion_banners_vendor_products` where `vendor_user_id` =" . $user_id;
            $query = $this->db->query($sql_promotion_banners_vendor_products);


            $sql_promotion_banner_payments = "DELETE FROM `promotion_banner_payments` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_promotion_banner_payments);

            $sql_promotion_codes = "DELETE FROM `promotion_codes` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_promotion_codes);

            $sql_promotion_code_products = "DELETE FROM `promotion_code_products` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_promotion_code_products);

            $sql_social_auth = "DELETE FROM `social_auth` where `user_id` =" . $user_id;
            $query = $this->db->query($sql_social_auth);


            $sql_subscriptions_payments = "DELETE FROM `subscriptions_payments` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_subscriptions_payments);

            $sql_support = "DELETE FROM `support` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_support);

            $sql_used_cupons = "DELETE FROM `used_cupons` where `created_user_id` = " . $user_id . " or `user_id` =" . $user_id;
            $query = $this->db->query($sql_used_cupons);

            $sql_used_promo_codes = "DELETE FROM `used_promo_codes` where `created_user_id` =2015 or `user_id` =" . $user_id;
            $query = $this->db->query($sql_used_promo_codes);

            $sql_users_accepted_tc = "DELETE FROM `users_accepted_tc` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_users_accepted_tc);

            $sql_users_address = "DELETE FROM `users_address` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_users_address);

            $sql_users_groups = "DELETE FROM `users_groups` where `user_id` =" . $user_id;
            $query = $this->db->query($sql_users_groups);

            $sql_users_permissions = "DELETE FROM `users_permissions` where `user_id` =" . $user_id;
            $query = $this->db->query($sql_users_permissions);

            $sql_user_accounts = "DELETE FROM `user_accounts` where `user_id` =" . $user_id;
            $query = $this->db->query($sql_user_accounts);

            $sql_user_credentials = "DELETE FROM `user_credentials` where `user_id` =" . $user_id;
            $query = $this->db->query($sql_user_credentials);

            $sql_user_docs = "DELETE FROM `user_docs` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_user_docs);

            $sql_user_sessions = "DELETE FROM `user_sessions` where `user_id` =" . $user_id;
            $query = $this->db->query($sql_user_sessions);

            $sql_vendors_sub_categories = "DELETE FROM `vendors_sub_categories` where `list_id` in (SELECT id vendor_id FROM `vendors_list` where `vendor_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_vendors_sub_categories);

            $sql_vendor_address = "DELETE FROM `vendor_address` where `list_id` in (SELECT id  vendor_id FROM `vendors_list` where `vendor_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_vendor_address);


            $sql_vendor_bank_details = "DELETE FROM `vendor_bank_details` where `list_id` in (SELECT id vendor_id FROM `vendors_list` where `vendor_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_vendor_bank_details);

            $sql_vendor_banners = "DELETE FROM `vendor_banners` where `list_id` in (SELECT id  vendor_id FROM `vendors_list` where `vendor_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_vendor_banners);

            $sql_vendor_brands = "DELETE FROM `vendor_brands` where `list_id` in (SELECT id  vendor_id FROM `vendors_list` where `vendor_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_vendor_brands);

            $sql_vendor_packages = "DELETE FROM `vendor_packages` where `created_user_id` =" . $user_id;
            $query = $this->db->query($sql_vendor_packages);

            $sql_vendor_product_variants = "DELETE FROM `vendor_product_variants` where `vendor_user_id` =" . $user_id;
            $query = $this->db->query($sql_vendor_product_variants);

            $sql_vendor_ratings = "DELETE FROM `vendor_ratings` where `user_id` =" . $user_id . " or `list_id` in (SELECT id vendor_id FROM `vendors_list` where `vendor_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_vendor_ratings);

            $sql_vendor_services = "DELETE FROM `vendor_services` where `list_id` in (SELECT id vendor_id FROM `vendors_list` where `vendor_user_id` =" . $user_id . ")";
            $query = $this->db->query($sql_vendor_services);

            $sql_vendors_list = "DELETE FROM `vendors_list` where `vendor_user_id` =" . $user_id;
            $query = $this->db->query($sql_vendors_list);

            $sql_wallet_transactions = "DELETE FROM `wallet_transactions` where `account_user_id` =" . $user_id . " or created_user_id =" . $user_id;
            $query = $this->db->query($sql_wallet_transactions);

            $sql_update_excutive_vendors = 'UPDATE `vendors_list` SET `executive_user_id`=NULL,`first_paid_subscription_id`=NULL,`first_paid_subscription_at`=NULL,`executive_referral_amount`=NULL,`is_executive_referral_amount_added`=0 WHERE `executive_user_id`=' . $user_id;
            $query = $this->db->query($sql_update_excutive_vendors);

            $sql_update_excutive_users = 'UPDATE `users` SET `executive_user_id`=NULL,`first_order_id`=NULL,`first_order_at`=NULL,`executive_referral_amount`=NULL,`is_executive_referral_amount_added`=0 WHERE `executive_user_id`=' . $user_id;
            $query = $this->db->query($sql_update_excutive_users);

            $sql_update_excutive_delivery_patterns = 'UPDATE `delivery_boy_address` SET `executive_user_id`=NULL,`target_given_count`=NULL,`target_achieved_count`=NULL,`is_target_achieved`=0,`target_achieved_at`=NULL,`executive_referral_amount`=NULL,`is_executive_referral_amount_added`=0 WHERE `executive_user_id`=' . $user_id;
            $query = $this->db->query($sql_update_excutive_delivery_patterns);

            $sql_user = "DELETE FROM `users` where id=" . $user_id;
            $query = $this->db->query($sql_user);

            echo 'User Deleted Succesfully...';
        } else {
            echo 'Invalid phone number';
        }
    }
}
