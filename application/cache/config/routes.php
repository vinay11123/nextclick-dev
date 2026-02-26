<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
 * Auth
 */
$route['category/(:any)'] = 'admin/master/category/$1';

/*
 *Admin 
 */
$route['dashboard'] = 'admin/dashboard';
$route['sample'] = 'admin/dashboard/sample';
$route['settings/(:any)'] = 'admin/settings/$1';
$route['stock_settings/(:any)/(:any)'] = 'admin/stock_settings/$1/$2';

$route['vendor_settings/(:any)'] = 'admin/vendor_settings/$1';
$route['sliders/(:any)'] = 'admin/sliders/$1';
$route['category_banner/(:any)'] = 'admin/category_banner/$1';
$route['cat_ban_delete/(:any)'] = 'admin/cat_ban_delete/$1';
$route['cat_bottom_banners/(:any)'] = 'admin/cat_bottom_banners/$1';
$route['site_logo/(:any)'] = 'admin/site_logo/$1';
$route['advertisements/(:any)'] = 'admin/advertisements/$1';
$route['profile/(:any)'] = 'admin/profile/$1';
$route['user_services/(:any)'] = 'admin/user_services/$1';
$route['wallet'] = 'admin/dashboard/wallet';
// $route['termsconditions/(:any)'] = 'admin/termsconditions/$1';
$route['faq/(:any)'] = 'admin/faq/$1';
$route['stocksetting/(:any)'] = 'admin/stock_settings_details/$1';
$route['vendor_faq/(:any)'] = 'admin/vendor_faq/$1';
$route['lead_management/(:any)'] = 'admin/dashboard/lead_management/$1';
$route['pickanddropcategories/(:any)'] = 'admin/dashboard/pickanddropcategories/$1';
$route['terms/(:any)'] = 'admin/terms/$1';
$route['manage_manual_payments'] = 'admin/master/manageManualPayments';
$route['manual_payments_list'] = 'admin/master/manageManualPaymentslist';

/*Categories*/
$route['category/(:any)'] = 'admin/master/category/$1';
$route['amenity/(:any)'] = 'admin/master/amenity/$1';
$route['sub_category/(:any)/(:any)'] = 'admin/master/sub_category/$1/$2';
$route['service/(:any)'] = 'admin/master/service/$1';
$route['state/(:any)'] = 'admin/master/state/$1';
$route['district/(:any)'] = 'admin/master/district/$1';
$route['constituency/(:any)'] = 'admin/master/constituency/$1';
$route['brands/(:any)/(:any)'] = 'admin/master/brands/$1/$2';
$route['request/(:any)'] = 'admin/master/request/$1';
$route['update_delivery_message'] = 'admin/master/update_delivery_message';


//$route['support/(:any)'] = 'admin/master/support/$1';

$route['specialities/(:any)'] = 'admin/master/specialities/$1';
$route['od_categories/(:any)'] = 'admin/master/od_categories/$1';
$route['doctors_approve/(:any)'] = 'admin/master/doctors_approve/$1';
$route['od_categories_approve/(:any)'] = 'admin/master/od_categories_approve/$1';
$route['doctors/(:any)'] = 'admin/master/doctors/$1';

$route['od_services/(:any)'] = 'admin/master/od_services/$1';
$route['doctors_booking/(:any)/(:any)'] = 'admin/master/doctors_booking/$1/$2';
$route['od_service_booking/(:any)/(:any)'] = 'admin/master/od_service_booking/$1/$2';

/*Employees*/
$route['employee/(:any)/(:any)'] = 'admin/employee/$1/$2';
$route['role/(:any)'] = 'admin/role/$1';
$route['module_permissions/(:any)'] = 'admin/module_permissions/$1';
$route['group_module_permissions/(:any)'] = 'admin/group_module_permissions/$1';
$route['emp_list/(:any)'] = 'admin/emp_list/$1';
$route['executivestatus/(:any)'] = 'admin/executivestatus/$1';
$route['delivery_partner/(:any)/(:any)'] = 'admin/delivery_partner/$1/$2';

/*vendors*/
$route['vendors/(:any)'] = 'admin/master/vendors/$1';
$route['deliveryboy/(:any)'] = 'admin/master/deliveryboy/$1';
$route['deliveryboystatus/(:any)'] = 'admin/master/deliveryboystatus/$1';
$route['foodproductstatus/(:any)'] = 'food/foodproductstatus/$1';
$route['foodproducttogglestatus/(:any)'] = 'food/foodproducttogglestatus/$1';
$route['vendors_filter/(:any)'] = 'vendor/vendors_filter/$1';
$route['vendor_payments/(:any)'] = 'vendor/vendor_payments/$1';
$route['vendor_excel_import'] = 'vendor/vendor_excel_import';
$route['details_by_vendor/(:any)/(:any)'] = 'vendor/details_by_vendor/$1/$2';

/*News*/
$route['news_categories/(:any)'] = 'admin/news/news_categories/$1';
$route['news/(:any)'] = 'admin/news/news/$1';
$route['local_news/(:any)'] = 'admin/news/local_news/$1';

/*Food*/
$route['shop_by_categories/(:any)'] = 'food/shop_by_categories/$1';
$route['food_menu/(:any)'] = 'food/food_menu/$1';
$route['food_product_delete/(:any)'] = 'food/food_product/food_product_delete/$1';
$route['deleteproduct'] = 'food/deleteproduct';
$route['food_product/(:any)/(:any)'] = 'food/food_product/$1/$2';
$route['food_item/(:any)'] = 'food/food_item/$1';
$route['products/(:any)'] = 'food/products/$1';
$route['sections/(:any)'] = 'food/sections/$1';
$route['section_items/(:any)'] = 'food/section_items/$1';
$route['food_section/(:any)'] = 'food/food_section/$1';
$route['food_section_item/(:any)'] = 'food/food_section_item/$1';
$route['food_orders/(.+)'] = 'food/food_orders/$1/$2';
$route['pickup_orders/(.+)'] = 'food/pickup_orders/$1/$2';
$route['ongoing_orders/(.+)'] = 'food/ongoing_orders/$1/$2';
$route['pending_orders/(.+)'] = 'food/pending_orders/$1/$2';
$route['food_settings/(:any)'] = 'food/food_settings/$1';
$route['food_order_status/(.+)'] = 'food/food_order_status/$1';
$route['vendor_profile/(.+)'] = 'vendor/vendor_profile/$1';
$route['modify_category'] = 'vendor/modify_category';
$route['vendor_leads/(.+)'] = 'food/VendorLeads/$1';
$route['vendor_lead_status/(.+)'] = 'food/vendor_lead_status/$1';
$route['view_order'] = 'food/view_order';
$route['products_approve/(:any)/(:any)'] = 'food/products_approve/$1/$2';
$route['inventory/(:any)/(:any)'] = 'food/inventory/$1/$2';
$route['vendor_req_product/(:any)/(:any)'] = 'food/vendor_req_product/$1/$2';
$route['catalogue/(:any)/(:any)'] = 'food/catalogue/$1/$2';
$route['approved/(:any)/(:any)'] = 'food/approved/$1/$2';
$route['pendingproducts/(:any)/(:any)'] = 'food/pendingproducts/$1/$2';
$route['shop_by_category_approve/(:any)'] = 'food/shop_by_category_approve/$1';
$route['order_support/(:any)'] = 'food/order_support/$1';
/**
 * Promo Codes
 * */
$route['promotion_banners/(:any)/(:any)'] = 'promos/promotion_banners/manage_promotion_banners/$1/$2';
$route['promotion_codes/(:any)'] = 'promos/promotion_codes/promotion_list/$1';
$route['banner_images/(:any)'] = 'promos/promotion_banners/banner_images_list/$1';
$route['admin_banners/(:any)'] = 'promos/promotion_banners/admin_banners_list/$1';
$route['vendor_promotion_banners/(:any)'] = 'promos/promotion_banners/vendor_promotion_banners/$1';
$route['bannerstatus/(:any)'] = 'promos/promotion_banners/bannerstatus/$1';
$route['banner_cost/(:any)/(:any)'] = 'promos/promotion_banners/banner_cost/$1/$2';
$route['add_banner_cost/(:any)/(:any)'] = 'promos/promotion_banners/banner_cost/$1/$2';
/**
 * Returns
 */
$route['return_policies/(:any)'] = 'admin/return_policies/$1';
/**
 * service tax
 */
$route['service_tax/(:any)'] = 'admin/service_tax/$1';

/**
 * Subscriptions
 */

$route['subscriptions_packages/(:any)'] = 'admin/subscriptions_packages/$1';
$route['vendor_packages/(:any)'] = 'admin/vendor_packages/$1';

/*Delivery*/
$route['adhar_card/(:any)'] = 'admin/master/adhar_card/$1';
$route['pan_card/(:any)'] = 'admin/master/pan_card/$1';
$route['cancel_cheque/(:any)'] = 'admin/master/cancel_cheque/$1';
$route['driving_licence/(:any)'] = 'admin/master/driving_licence/$1';
$route['pass_book/(:any)'] = 'admin/master/pass_book/$1';
$route['rc/(:any)'] = 'admin/master/rc/$1';
$route['vehicle/(:any)/(:any)'] = 'delivery/vehicle/$1/$2';
$route['delivery_area/(:any)/(:any)'] = 'delivery/delivery_area/$1/$2';


/*Ecom orders*/
$route['delivery_job_rejection_requests'] = 'admin/ecom_orders/delivery_job_rejection_requests';
$route['delivery_job_accept_requests'] = 'admin/ecom_orders/delivery_job_accept_requests';
$route['delivery_boy_wallet_transactions/(.+)'] = 'admin/ecom_orders/delivery_boy_wallet_transactions/$1/$2';
$route['accept_dj_rejection'] = 'admin/ecom_orders/accept_dj_rejection';
$route['cancel_dj_rejection'] = 'admin/ecom_orders/cancel_dj_rejection';
$route['orders_dashboard'] = 'admin/ecom_orders/orders_dashboard';

/*Payment*/
$route['wallet_transactions/(:any)/(:any)'] = 'payment/wallet_transactions/$1/$2';
$route['admin_wallet_reports/(:any)'] = 'payment/admin_wallet_reports/$1';
$route['vendor_gst_reports/(:any)'] = 'payment/vendor_gst_reports/$1';
$route['vendor_reports/(:any)'] = 'payment/vendor_reports/$1';
$route['delivery_boy_gst_reports'] = 'payment/delivery_boy_gst_reports';
$route['delivery_boy_wise_gst_report/(:any)'] = 'payment/delivery_boy_wise_gst_report/$1';



/* General settings*/
$route['support_queries/(:any)/(:any)'] = 'general/Support/support_queries/$1/$2';
$route['customer_support/(:any)/(:any)'] = 'general/Support/customer/$1/$2';


// routes for master_package_settings.
$route['master_package_setting'] = "master_package_setting/ManageMaster_package_settings";
$route['change-status-master_package_settings/(:num)'] = "master_package_setting/changeStatusMaster_package_settings/$1";
$route['edit-master_package_settings/(:num)'] = "master_package_setting/editMaster_package_settings/$1";
$route['edit-master_package_settings-post'] = "master_package_setting/editMaster_package_settingsPost";
$route['master_package_settings/add'] = "master_package_setting/addMaster_package_settings";
$route['add-master_package_settings-post'] = "master_package_setting/addMaster_package_settingsPost";
$route['view-master_package_settings/(:num)'] = "master_package_setting/viewMaster_package_settings/$1";
// end of master_package_settings routes

$route['shift'] = "shift/ManageShifts";
$route['shift/change-status/(:num)'] = "shift/changeStatusShifts/$1";
$route['shift/edit/(:num)'] = "shift/editShifts/$1";
$route['shift/edit-shift'] = "shift/editShiftsPost";
$route['shift/add'] = "shift/addShifts";
$route['shift/add-shift'] = "shift/addShiftsPost";
$route['shift/view/(:num)'] = "shift/viewShifts/$1";

// routes for delivery_insentive_config.
$route['delivery_insentive/pending'] = "insentive/ManagePending_insentive";
$route['delivery_insentive/process'] = "insentive/Process_insentive";
$route['delivery_insentive'] = "insentive/ManageDelivery_insentive_config";
$route['delivery_insentive/mutate_status/(:num)'] = "insentive/changeStatusDelivery_insentive_config/$1";
$route['delivery_insentive/edit/(:num)'] = "insentive/editDelivery_insentive_config/$1";
$route['update-delivery_insentive_config'] = "insentive/editDelivery_insentive_configPost";
$route['delivery_insentive/add'] = "insentive/addDelivery_insentive_config";
$route['add-delivery_insentive_config'] = "insentive/addDelivery_insentive_configPost";
$route['delivery_insentive/view/(:num)'] = "insentive/viewDelivery_insentive_config/$1";
// end of delivery_insentive_config routes


// routes for NC Earnings

$route['pickup_earnings'] = 'nc_earnings/NC_Earnings/EarningsDateFilter';
$route['ecom_earnings'] = 'nc_earnings/NC_Earnings/ecom_EarningsDateFilter';

$route['day_wise_pickup_earnings'] = 'nc_earnings/NC_Earnings/day_wise_pickup_earnings';
$route['day_wise_ecom_earnings'] = 'nc_earnings/NC_Earnings/day_wise_ecom_earnings';

$route['nc_day_wise_ecom_earnings_modal'] = 'nc_earnings/NC_Earnings/nc_get_ecom_order_details';
$route['nc_day_wise_pickup_earnings_modal'] = 'nc_earnings/NC_Earnings/nc_get_pickup_order_details';
// end of NC Earnings routes

// routes for Delivery Earnings

$route['delivery_pickup_earnings'] = 'dc_earnings/DC_earnings/delivery_pickup_EarningsDateFilter';
$route['delivery_ecom_earnings'] = 'dc_earnings/DC_earnings/delivery_ecom_EarningsDateFilter';

$route['day_wise_delivery_pickup_earnings'] = 'dc_earnings/DC_earnings/day_wise_delivery_pickup_earnings';
$route['day_wise_delivery_ecom_earnings'] = 'dc_earnings/DC_earnings/day_wise_delivery_ecom_earnings';

$route['dc_day_wise_ecom_earnings_modal'] = 'dc_earnings/DC_earnings/dc_get_ecom_order_details';
$route['dc_day_wise_ecom_graph_earnings_modal'] = 'dc_earnings/DC_earnings/dc_get_graph_ecom_order_details';
$route['dc_day_wise_ecom_captain_graph_earnings_modal'] = 'dc_earnings/DC_earnings/dc_day_wise_ecom_captain_graph_earnings_modal';


$route['dc_day_wise_pickup_earnings_modal'] = 'dc_earnings/DC_earnings/dc_get_pickup_order_details';
$route['dc_day_wise_pickup_graph_earnings_modal'] = 'dc_earnings/DC_earnings/dc_get_graph_pickup_order_details';
$route['dc_day_wise_pickup_captain_graph_earnings_modal'] = 'dc_earnings/DC_earnings/dc_day_wise_pickup_captain_graph_earnings_modal';

// end of Delivery Earnings routes

//Ecommerce
$route['ecom_pickup_orders'] = 'ecommerce/pickup_order_entry';
$route['epickup_orders/(.+)'] = 'ecommerce/pickup_orders/$1';

$route['ecom_ecom_orders'] = 'ecommerce/ecom_order_entry';
$route['eecom_orders/(.+)'] = 'ecommerce/ecom_orders/$1';
$route['ecom/delete_file'] = 'ecommerce/deleteFile';

//end of Ecommerce


//agreements
$route['agreements/(.+)'] = 'agreements/Agreements/agreement_details/$1';

$route['update_status'] = 'agreements/Agreements/update_status';
// end of agreements routes

//terms & conditions
$route['terms_conditions/(:any)'] = 'termsconditions/Termsconditions/termsandconditions/$1';
// end of terms & conditions routes

//Reports
$route['vendor_agreements/(:any)'] = 'reports/Reports/vendor_agreement_reports/$1';
$route['vendor_agreements_email/(:any)'] = 'reports/Reports/sendmail/$1';
// end of reports routes


$route['pdftoEmail'] = 'pdfEmail';


//Executive_app
$route['executive/login'] = 'executive_app/Authorize';
$route['executive/login/(.+)'] = 'executive_app/Authorize/index/$1';
$route['register/(.+)'] = 'executive_app/Authorize/register/$1';

$route['register_otp/(.+)'] = 'executive_app/Authorize/register_otp/$1';
$route['create_account/(.+)'] = 'executive_app/Authorize/create_account/$1';
$route['confirm_kyc'] = 'executive_app/Authorize/kyc_confirmation';
$route['kyc_details'] = 'executive_app/Authorize/user_kyc_details';
$route['executive/dashboard'] = 'executive_app/ExecutiveLogin/dashboard';
$route['forgot_password/(.+)'] = 'executive_app/Authorize/forgot_password/$1';
$route['login_otp_phone/(.+)'] = 'executive_app/Authorize/login_otp_phone/$1';
$route['login_otp/(.+)'] = 'executive_app/Authorize/login_otp/$1';
$route['executive/resend_otp'] = 'executive_app/Authorize/register_resend_otp';
$route['executive/login_resend_otp'] = 'executive_app/Authorize/login_resend_otp';


$route['executive/profile'] = 'executive_app/ExecutiveLogin/profile';
$route['executive/edit_profile/(.+)'] = 'executive_app/ExecutiveLogin/edit_profile/$1';
$route['executive/referral_video'] = 'executive_app/ExecutiveLogin/referral_video';
$route['executive/executive_terms'] = 'executive_app/ExecutiveLogin/executive_terms_conditions';
$route['executive/terms'] = 'executive_app/ExecutiveLogin/terms';
$route['executive/referral_link'] = 'executive_app/ExecutiveLogin/referral_link';
$route['executive/vendors'] = 'executive_app/ExecutiveLogin/vendors';
$route['executive/approved_vendors_list'] = 'executive_app/ExecutiveLogin/approved_vendors_list';
$route['executive/pending_vendors_list'] = 'executive_app/ExecutiveLogin/pending_vendors_list';
$route['executive/delivery_boys'] = 'executive_app/ExecutiveLogin/delivery_boys';
$route['executive/approved_delivery_boys'] = 'executive_app/ExecutiveLogin/approved_delivery_boys';
$route['executive/pending_delivery_boys'] = 'executive_app/ExecutiveLogin/pending_delivery_boys';
$route['executive/users'] = 'executive_app/ExecutiveLogin/users';
$route['executive/wallet'] = 'executive_app/ExecutiveLogin/wallet';
$route['executive/withdraw_amount'] = 'executive_app/ExecutiveLogin/withdraw_amount';
$route['executive/transactions/(:any)'] = 'executive_app/ExecutiveLogin/transactions/$1';
$route['executive/bank_account/(:any)'] = 'executive_app/ExecutiveLogin/bank_account/$1';
$route['executive_list/(.+)/?(.*)'] = 'executive_app/Executive/emp_list/$1/$2';
$route['evendor_list/(.+)'] = 'executive_app/Executive/vendors/all';

$route['add_vehicle/(:any)/(:any)'] = 'executive_app/Executive/employee/$1/$2';

// end of Executive_app routes

$route['vendorOrders'] = 'vendor_crm/Orders';
$route['vendor/order_details'] = 'vendor_crm/Orders/order_details';

$route['vendorOrders/(.+)'] = 'vendor_crm/Orders/vendorEcomOrder/$1';

$route['verify_bank_details_post'] = 'auth/api/auth/verify_bank_details';








