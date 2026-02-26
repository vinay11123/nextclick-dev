<?php
if(! function_exists('generate_serial_no')){
    function generate_serial_no($prefix = 'EX', $no_of_zeros = 5, $last_serial_number = 1){
        return $prefix.str_pad($last_serial_number + 1, $no_of_zeros, 0, STR_PAD_LEFT);
    }
}


if(! function_exists('generate_trasaction_no')){
    function generate_trasaction_no($length = 20){
        mt_srand((double)microtime()*10000);
        $charid = md5(uniqid(rand(), true));
        $c = unpack("C*", $charid);
        $c = implode("", $c);
        return substr($c, 0, $length);
    }
}

if(! function_exists('generate_order_track_id')){
    function generate_order_track_id($id = 1){
        return generate_serial_no('NC', 3, $id).'-'.time().'-'.mt_rand(999,9999);
    }
}

if(! function_exists('wallet_arithmetic_operations')){
    function wallet_arithmetic_operations($operation = 'CREDIT', $source = 0, $new = 0){
        if($operation == 'CREDIT'){
            if(isNegative($source)){
                return -1 * (abs($source) - $new);
            }else {
                return 1 * (abs($source) + $new);
            }
        }elseif ($operation == 'DEBIT'){
            if(isNegative($source)){
                return -1 * (abs($source) + $new);
            }else {
                return 1 * (abs($source) - $new);
            }
        }
    }
}

if(! function_exists('isNegative')){
    function isNegative($value){
        if(isset($value)) {
            if ((int)$value > 0) {
                return false;
            }
            return (int)$value < 0 && substr(strval($value), 0, 1) === "-";
        }
    }
}

if( ! function_exists('time_elapsed_string')){
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
        
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
        
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}
/****** Send Email ******/
if ( ! function_exists('sendEmail'))
{
    function sendEmail($from = null, $to = null, $sub = null, $msg = null, $reply_to = null, $cc = null, $bcc = null, $attachment = null)
    {//return TRUE;
        
        if(!filter_var($from, FILTER_VALIDATE_EMAIL) ) {
            return false;
        }
        
        $CI = & get_instance();
        if($msg != "") {
            
            $CI->load->library('email');
            //$CI->email->clear();
            $smtp_host = $smtp_port = $smtp_user = $smtp_password = $mandrill_api_key = '';
            
            
            $config = Array(
                'protocol' 	=> 'smtp',
                'smtp_host' => $smtp_host,
                'smtp_port' => $smtp_port,
                'smtp_user' => $smtp_user,
                'smtp_pass' => $smtp_password,
                'charset' 	=> 'utf-8',
                'mailtype' 	=> 'html',
                'newline' 	=> "\r\n",
                'wordwrap' 	=> TRUE
                );
            
            $CI->email->initialize($config);
            
            $CI->email->from($smtp_user, $CI->config->item('site_settings')->site_title);
            
            $CI->email->to($to);
            
            if($reply_to != "" && filter_var($reply_to, FILTER_VALIDATE_EMAIL))
                $CI->email->reply_to($reply_to);
                if($cc != "" && filter_var($cc, FILTER_VALIDATE_EMAIL))
                    $CI->email->cc($cc);
                    if($bcc != "" && filter_var($bcc, FILTER_VALIDATE_EMAIL))
                        $CI->email->bcc($bcc);
                        
                        if($attachment != "")
                            $CI->email->attach($attachment);
                            
                            $CI->email->subject($sub);
                            $CI->email->message($msg);
                            
                            if( $CI->email->send() )
                                return true;
                                
        }
        return false;
    }
    
}

//Get User Type
if( ! function_exists('getTemplate'))
{
    function getTemplate($user_id='')
    {
        $CI =& get_instance();
        $user_type='';
        $template='';
        if($user_id=='')
        {
            $user_id = getUserRec()->id;
        }
        $user_groups = $CI->ion_auth->get_users_groups($user_id)->result();
        switch($user_groups[0]->id)
        {
            case 1:
                $user_type='admin';
                $template = $user_type.'-template';
                break;
            case 2:
                $user_type='student';
                $template = 'site-template';
                break;
            case 3:
                $user_type='school';
                $template = 'site-template';
                break;
        }
        return $template;
    }
}

/*To print array  or object*/
if( !function_exists('print_array')){
    function print_array($data = []){
        echo "<pre>";print_r($data);exit();
    }
}

/**
 * Check for logged in uyser
 *
 * @access    public
 * @param    string
 * @return    string
 */
if ( ! function_exists('check_access'))
{
    function check_access( $type = 'admin')
    {
        $CI	=&	get_instance();
        
        if (!$CI->ion_auth->logged_in())
        {
            redirect(URL_AUTH_LOGIN, 'refresh');
        }
        elseif($type == 'admin')
        {
            if (!$CI->ion_auth->is_admin())
            {
                prepare_flashmessage('No Entry',2);
                redirect(SITEURL2);
            }
        }
        elseif($type == 'student')
        {
            if (!$CI->ion_auth->is_student())
            {
                prepare_flashmessage('No Entry',2);
                redirect(SITEURL2);
            }
        }
        elseif($type == 'school')
        {
            if (!$CI->ion_auth->is_school())
            {
                prepare_flashmessage('No Entry',2);
                redirect(SITEURL2);
            }
        }
    }
}

/**
 * Prepare message
 *
 */
if ( ! function_exists('prepare_message'))
{
    function prepare_message($msg,$type = 2)
    {
        $returnmsg='';
        switch($type){
            case 0: $returnmsg = " <div class='col-md-12'>
    										<div class='alert alert-success'>
    											<a href='#' class='close' data-dismiss='alert'>&times;</a>
    											<strong>Scuccess..!</strong> ". $msg."
    										</div>
    									</div>";
            break;
            case 1: $returnmsg = " <div class='col-md-12'>
    										<div class='alert alert-danger'>
    											<a href='#' class='close' data-dismiss='alert'>&times;</a>
    											<strong>Error..!</strong> ". $msg."
    										</div>
    									</div>";
            break;
            case 2: $returnmsg = " <div class='col-md-12'>
    										<div class='alert alert-info'>
    											<a href='#' class='close' data-dismiss='alert'>&times;</a>
    											<strong>Info..!</strong> ". $msg."
    										</div>
    									</div>";
            break;
            case 3: $returnmsg = " <div class='col-md-12'>
    										<div class='alert alert-warning'>
    											<a href='#' class='close' data-dismiss='alert'>&times;</a>
    											<strong>Warning..!</strong> ". $msg."
    										</div>
    									</div>";
            break;
        }
        
        return $returnmsg;
    }
}

if (! function_exists('haversineGreatCircleDistance')) {
    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    function haversineGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371.0088)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
        
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return ($angle * $earthRadius);
    }
}

if (! function_exists('vincentyGreatCircleDistance')) {
    /**
 * Calculates the great-circle distance between two points, with
 * the Vincenty formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m]- 6371000 (same as earthRadius) km- 6,371.0088
 */
function vincentyGreatCircleDistance(
  $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371.0088)
{
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $lonDelta = $lonTo - $lonFrom;
  $a = pow(cos($latTo) * sin($lonDelta), 2) +
    pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
  $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

  $angle = atan2(sqrt($a), $b);
  return $angle * $earthRadius;
}
}


/**
 * Prepare flash message
 *
 */
if ( ! function_exists('prepare_flashmessage'))
{
    
    function prepare_flashmessage($msg,$type = 2)
    {
        $returnmsg='';
        switch($type){
            case 0: $returnmsg = " <!-- <div class='col-md-12'> -->
										<div class='alert alert-success'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>Success..!</strong> ". $msg."
										</div>
									<!-- </div> -->";
            break;
            case 1: $returnmsg = " <!-- <div class='col-md-12'> -->
										<div class='alert alert-danger'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>Error..!</strong> ". $msg."
										</div>
									<!-- </div> -->";
            break;
            case 2: $returnmsg = " <!-- <div class='col-md-12'> -->
										<div class='alert alert-info'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>Info..!</strong> ". $msg."
										</div>
									<!-- </div> -->";
            break;
            case 3: $returnmsg = " <!-- <div class='col-md-12'> -->
										<div class='alert alert-warning'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>Warning..!</strong> ". $msg."
										</div>
									<!-- </div> -->";
            break;
        }
        $CI =& get_instance();
        $CI->session->set_flashdata("message",$returnmsg);
    }
}

/****** Send Custom Email ******/
if ( ! function_exists('sendCustomEmail'))
{
    function sendCustomEmail($to = null, $sub = null, $msg = null, $reply_to = null, $cc = null, $bcc = null, $attachment = null)
    {
        $CI = & get_instance();
        $CI->load->library('email');
        $email_settings = $CI->config->item('email_settings');
        if($msg != "") {
            $config = Array(
                'protocol' 	=> 'smtp',
                'smtp_host' => $email_settings->smtp_host,
                'smtp_port' => $email_settings->smtp_port,
                'smtp_user' => $email_settings->smtp_username,
                'smtp_pass' => $email_settings->smtp_password,
                'charset' 	=> 'utf-8',
                'mailtype' 	=> 'html',
                'newline' 	=> "\r\n",
                'wordwrap' 	=> TRUE
                );
            $CI->email->initialize($config);
            
            $CI->email->from($email_settings->smtp_username, $CI->config->item('site_settings')->site_title);
            
            $CI->email->to($to);
            
            if($reply_to != "" && filter_var($reply_to, FILTER_VALIDATE_EMAIL))
                $CI->email->reply_to($reply_to);
                if($cc != "" && filter_var($cc, FILTER_VALIDATE_EMAIL))
                    $CI->email->cc($cc);
                    if($bcc != "" && filter_var($bcc, FILTER_VALIDATE_EMAIL))
                        $CI->email->bcc($bcc);
                        
                        if($attachment != "")
                            $CI->email->attach($attachment);
                            
                            $CI->email->subject($sub);
                            $CI->email->message($msg);
                            
                            if( $CI->email->send() )
                                return true;
                                
        }
        return false;
    }
    
}

if (! function_exists('getGoogleMapDistance')) {
    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    function getGoogleMapDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        // convert from degrees to radians
        $api = "https://maps.googleapis.com/maps/api/directions/json?origin=$latitudeFrom,$longitudeFrom&destination=$latitudeTo,$longitudeTo&sensor=true&mode=driving&key=AIzaSyAkP6fBu1G3DukkUJzFEvMxvi4G0KgxMnQ";
        $response = Requests::get($api, array());
        //$this->get_ee_api(); take this line off. The function is calling it self over and over.
        $data = json_decode($response->body, true);

        return round($data['routes'][0]['legs']['0']['distance']['value'] / 1000, 2);

        // return preg_replace('/\s+/', '', $distance);
    }
}