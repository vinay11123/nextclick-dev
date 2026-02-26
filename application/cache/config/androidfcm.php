<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
|| Android Firebase Push Notification Configurations
|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
 */

/*
|--------------------------------------------------------------------------
| Firebase API Key
|--------------------------------------------------------------------------
|
| The secret key for Firebase API
|
 */
$config['key'] = 'AAAAvtAqjbc:APA91bFtwJQJqcBeVPJolstMUUaZtLoweYac-5b-PmLw3_kKj4YUhz9ig2t9mC2U5aXHexoH6MnixUwkgFPRJ7O6bdR7tXeqQaN340JbJT3RJMjPZuCh-LBoHO9y6AeNJaJZIUab93op';

/*
|--------------------------------------------------------------------------
| Firebase Cloud Messaging API URL
|--------------------------------------------------------------------------
|
| The URL for Firebase Cloud Messafing
|
 */

$config['fcm_url'] = 'https://fcm.googleapis.com/v1/projects/nextclickuser-df6c4/messages:send';
