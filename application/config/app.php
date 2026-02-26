<?php
defined('BASEPATH') or exit('No direct script access allowed');


/*
 |--------------------------------------------------------------------------
 | Our service distace
 |--------------------------------------------------------------------------
 |
 | The secret key for Firebase API
 |
 */
$config['service_distance_in_km'] = 5;
$config['pickupanddrop_service_distance_in_km'] = 15;
$config['service_distance_in_mile'] = 2.4;
$config['razorpay'] =[
    "key" => "rzp_live_PZA0wSco3EvtMo", //rzp_test_8FHdfbzKqsFwEy
    "secret" => "8No4ySshz2pGCSdqTl8hYF6N", //QuB0R6SDEuWa7SPJi2FshdIH
    "payout_account"=> "4564567855631773"
];
$config['super_admin_user_id']        = 1;
