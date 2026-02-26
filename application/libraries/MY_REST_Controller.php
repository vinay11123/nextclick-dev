<?php
/**
 * Custome class to set response format 
 * @package         CodeIgniter
 * @subpackage      Class
 * @category        Class
 * @author          Kapil Barad|Agile Infoways, Devang Naghera|Agile Infoways  
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/Fcm.php';

use \Firebase\JWT\JWT;
use phpDocumentor\Reflection\Types\Boolean;
class MY_REST_Controller extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('fcm_model');
        $this->load->library('fcm');
        $this->load->model('notifications_model');
        $this->load->model('app_details_model');
    }
    
    // public function is_access_available($app_id = '', $groups = []){
    //     $app_id = base64_decode(base64_decode($app_id));
    //     $app_details = $this->app_details_model->where('id', $app_id)->get();
    //     if($app_details){
    //         $allowed_groups = explode(',', $app_details['allowed_groups']);
    //         foreach ($groups as $group){
    //             if(in_array($group['id'], $allowed_groups))
    //                 return TRUE;
    //         }
    //         return FALSE;
    //     }else{
    //         return FALSE;
    //     }
    // }

    public function is_access_available($app_id = '', $groups = []){
        $app_id = base64_decode(base64_decode($app_id));
        $app_details = $this->app_details_model->where('id', $app_id)->get();
        if($app_details){
            $allowed_groups = explode(',', $app_details['allowed_groups']);
            foreach ($groups as $group){
                if(in_array($group['id'], $allowed_groups))
                    return TRUE;
            }
            return FALSE;
        }else{
            return FALSE;
        }
    }
    
    /*phone availability- registration*/
    function check_user_phone($phone)
    {
        $return_value = $this->user_model->where('phone', $phone)->get();
        if ($return_value)
        {
            $this->form_validation->set_message('check_user_phone', 'Sorry, This mobile is already used by another user.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

	/*promo availability validation- promos*/
	
	function check_promo_code($promo_code)
	{
        $authorization_exp = explode(" ",$this->input->get_request_header('Authorization'),2);
		$token_data =$this->validate_token($authorization_exp[1]);
		$return_value = $this->promos_model->where(['promo_code'=> $promo_code,'created_user_id'=>$token_data->id])->get();
	    if ($return_value)
	    {
	        $this->form_validation->set_message('check_promo_code', 'Sorry, This promocode already exist.');
	        return FALSE;
	    }
	    else
	    {
	        return TRUE;
	    }
	}
    /*service id availability validation- packages*/
	
	function check_service_id($service_id)
	{
	    $return_value = $this->package_model->where('service_id', $service_id)->get();
	    if ($return_value)
	    {
	        $this->form_validation->set_message('check_service_id', 'Sorry, This servcice already exist.');
	        return FALSE;
	    }
	    else
	    {
	        return TRUE;
	    }
	}
    
	/*email availability- registration*/
	function check_user_email($email)
	{
	    $return_value = $this->user_model->where('email', $email)->get();
        if (! empty($return_value)){
            $this->form_validation->set_message('check_user_email', 'Sorry, This email is already used by another user.');
	        return FALSE;
	    }else{
            return TRUE;
	    }
	}
    /* email availability for executive*/

    function check_executive_email($email)
    {
       
        $email_value = $this->user_model->where('email', $email)->get();
        if (! empty($email_value))
        {
            $this->form_validation->set_message('check_executive_email', 'Sorry, This email is already used by another user.');
            return FALSE;
        }
        else
        { 
            //print_array($email_value);
            return TRUE;
        }
    }
   
    
    /*email availability*/
    function check_email($email)
    {
        $return_value = $this->vendor_list_model->where('email', $email)->get();
        if ($return_value)
        {
            $this->form_validation->set_message('email_check', 'Sorry, This email is already used by another user please select another one');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    /*phone profile availability*/
    function is_unique_mobile($mobile)
    {
        if (! $this->user_model->is_unique_mobile($mobile))
        {
            $this->form_validation->set_message('is_unique_mobile', 'Sorry, This Mobile Number is already used by another user..!');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    /*email profile availability*/
    function is_unique_email($email)
    {
        if (! $this->user_model->is_unique_email($email))
        {
            $this->form_validation->set_message('is_unique_email', 'Sorry, This email is already used by another user.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    /*MOBILE availability*/
    function check_mobile($mobile)
    {
        $return_value = $this->vendor_list_model->where('mobile', $mobile)->get();
        if ($return_value)
        {
            $this->form_validation->set_message('mobile_check', 'Sorry, This Mobile is already used by another user please select another one');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    /**
     * @desc to send fcm push notifications 
     * @author mehar
     * 
     * @param array $token
     * @param string $title
     * @param string $message
     * @param string $image
     * @param array $payload
     */
    public function send_notification($user_id = [], $app_details_id = NULL, $title = NULL, $message = NULL, $payload = [])
    {
        $tokens = [];
        if(! empty($user_id)){
            $app_details = $this->app_details_model->where('id', $app_details_id)->get();
            $result = $this->fcm_model->where('user_id', $user_id)->where('app_details_id', $app_details_id)->get_all();
            $tokens = ! empty($result) ? array_unique(array_column($result, 'token')) : [];
            $notifications = [];
            if(is_array($user_id)){
                foreach ($user_id as $id){
                    if(! empty($notifications) && ! in_array($id, array_column($notifications, 'notified_user_id'))){
                        array_push($notifications, [
                            'notification_type_id' => $payload['notification_type']['id'],
                            'app_details_id' => $app_details_id,
                            'title' => $title,
                            'message' => $message,
                            'notified_user_id' => $id,
                            'ecom_order_id' => ! empty($payload['order_id'])? $payload['order_id'] : NULL,
                            'ticket_id' => ! empty($payload['ticket_id'])? $payload['ticket_id'] : NULL
                        ]);
                    }elseif (empty($notifications)){
                        array_push($notifications, [
                            'notification_type_id' => $payload['notification_type']['id'],
                            'app_details_id' => $app_details_id,
                            'title' => $title,
                            'message' => $message,
                            'notified_user_id' => $id,
                            'ecom_order_id' => ! empty($payload['order_id'])? $payload['order_id'] : NULL,
							'ticket_id' => ! empty($payload['ticket_id'])? $payload['ticket_id'] : NULL
                        ]);
                    }
                   
                }
            }else {
                array_push($notifications, [
                    'notification_type_id' => $payload['notification_type']['id'],
                    'app_details_id' => $app_details_id,
                    'title' => $title,
                    'message' => $message,
                    'notified_user_id' => $user_id,
                    'ecom_order_id' => ! empty($payload['order_id'])? $payload['order_id'] : NULL,
					'ticket_id' => ! empty($payload['ticket_id'])? $payload['ticket_id'] : NULL
                ]);
            }
           
            $this->notifications_model->insert($notifications);
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
        return json_decode($this->fcm->sendMultiple($tokens, $json, $app_details['fcm_server_key']), $url);
    }
    
    function send_sms($message = "hello", $template_id="1207169203114731853", $mobile_number= NULL) {
        $sms_config = $this->config->item('sms_settings');
        
        //Enter your login username
        $username = $sms_config->sms_username;
        
        //Enter your login password
        $password = $sms_config->sms_hash;
        
        //Enter your Sender ID
        $sender = $sms_config->sms_sender;
       
		
	   
	    $data = $this->get_remote_data('https://api.bulksmsgateway.in/sendmessage.php?', "user=$username&password=$password&mobile=$mobile_number&message=$message&sender=$sender&type=3&template_id=$template_id" );
		
       /* $data = $this->get_remote_data('http://api.bulksmsgateway.in/sendmessage.php?', "user=$username&password=$password&mobile=$mobile_number&message=$message&sender=$sender&type=3&template_id=$template_id" );
		print_r($data);*/
    }
    
    function send_by_msg91($message = "hello", $mobile_number= NULL) {
        $extra=array('schemeless'=>true, 'replace_src'=>true, 'return_array'=>false, "curl_opts"=>array(
            CURLOPT_URL => "https://api.msg91.com/api/v2/sendsms",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{ \"sender\": \"TESTDEV\", \"route\": \"4\", \"country\": \"91\", \"sms\": [ { \"message\": \"$message\", \"to\": [ \"$mobile_number\"] } ] }",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => array(
                "authkey: 316667A8CHupOhsB5e399ceaP1321",
                "content-type: application/json"
            ))
        );
        return $data = $this->get_remote_data('https://api.msg91.com/api/v2/sendsms', false, $extra );
    }
    
    /*
     ##############################################################################################
     ############### useful PHP cURL function for your library (TT's version) #####################
     ##############################################################################################
     ### echo get_remote_data("http://example.com/");                                   //GET request
     ### echo get_remote_data("http://example.com/", "var2=something&var3=blabla" );    //POST request
     ###
     ###    * Automatically handles FOLLOWLOCATION problem;
     ###    * Using 'replace_src'=>true, it fixes domain-relative urls  (i.e.:   src="./file.jpg"  ----->  src="http://example.com/file.jpg" )
     ###    * Using 'schemeless'=>true, it converts urls in schemeless  (i.e.:   src="http://exampl..  ----->  src="//exampl... )\
     ###### // source: https://github.com/ttodua/useful-php-scripts          	 ##########
     ###########################################################################################
     */
    
    function get_remote_data($url, $post_paramtrs=false,            $extra=array('schemeless'=>true, 'replace_src'=>true, 'return_array'=>false, "curl_opts"=>[]))
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        //if parameters were passed to this function, then transform into POST method.. (if you need GET request, then simply change the passed URL)
        if($post_paramtrs){ curl_setopt($c, CURLOPT_POST,TRUE);  curl_setopt($c, CURLOPT_POSTFIELDS, (is_array($post_paramtrs)? http_build_query($post_paramtrs) : $post_paramtrs) ); }
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
        $headers[]= "User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:76.0) Gecko/20100101 Firefox/76.0";	 $headers[]= "Pragma: ";  $headers[]= "Cache-Control: max-age=0";
        if (!empty($post_paramtrs) && !is_array($post_paramtrs) && is_object(json_decode($post_paramtrs))){ $headers[]= 'Content-Type: application/json'; $headers[]= 'Content-Length: '.strlen($post_paramtrs); }
        curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($c, CURLOPT_MAXREDIRS, 10);
        //if SAFE_MODE or OPEN_BASEDIR is set,then FollowLocation cant be used.. so...
        $follow_allowed= ( ini_get('open_basedir') || ini_get('safe_mode')) ? false:true;  if ($follow_allowed){curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);}
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
        curl_setopt($c, CURLOPT_REFERER, $url);
        curl_setopt($c, CURLOPT_TIMEOUT, 60);
        curl_setopt($c, CURLOPT_AUTOREFERER, true);
        curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($c, CURLOPT_HEADER, !empty($extra['return_array']));
        //set extra options if passed
        if(!empty($extra['curl_opts'])) foreach($extra['curl_opts'] as $key=>$value) curl_setopt($c, $key, $value);
        $data = curl_exec($c);
        if(!empty($extra['return_array'])) {
            preg_match("/(.*?)\r\n\r\n((?!HTTP\/\d\.\d).*)/si",$data, $x); preg_match_all('/(.*?): (.*?)\r\n/i', trim('head_line: '.$x[1]), $headers_, PREG_SET_ORDER); foreach($headers_ as $each){ $header[$each[1]] = $each[2]; }   $data=trim($x[2]);
        }
        $status=curl_getinfo($c); curl_close($c);
        // if redirected, then get that redirected page
        if($status['http_code']==301 || $status['http_code']==302) {
            //if we FOLLOWLOCATION was not allowed, then re-get REDIRECTED URL
            //p.s. WE dont need "else", because if FOLLOWLOCATION was allowed, then we wouldnt have come to this place, because 301 could already auto-followed by curl  :)
            if (!$follow_allowed){
                //if REDIRECT URL is found in HEADER
                if(empty($redirURL)){if(!empty($status['redirect_url'])){$redirURL=$status['redirect_url'];}}
                //if REDIRECT URL is found in RESPONSE
                if(empty($redirURL)){preg_match('/(Location:|URI:)(.*?)(\r|\n)/si', $data, $m);	                if (!empty($m[2])){ $redirURL=$m[2]; } }
                //if REDIRECT URL is found in OUTPUT
                if(empty($redirURL)){preg_match('/moved\s\<a(.*?)href\=\"(.*?)\"(.*?)here\<\/a\>/si',$data,$m); if (!empty($m[1])){ $redirURL=$m[1]; } }
                //if URL found, then re-use this function again, for the found url
                if(!empty($redirURL)){$t=debug_backtrace(); return call_user_func( $t[0]["function"], trim($redirURL), $post_paramtrs);}
            }
        }
        // if not redirected,and nor "status 200" page, then error..
        elseif ( $status['http_code'] != 200 ) { $data =  "ERRORCODE22 with $url<br/><br/>Last status codes:".json_encode($status)."<br/><br/>Last data got:$data";}
        //URLS correction
        if(function_exists('url_corrections_for_content_HELPER')){	    $data= url_corrections_for_content_HELPER($data, $status['url'],   array('schemeless'=>!empty($extra['schemeless']), 'replace_src'=>!empty($extra['replace_src']), 'rawgit_replace'=>!empty($extra['rawgit_replace']) )  );    	}
        $answer = ( !empty($extra['return_array']) ? array('data'=>$data, 'header'=>$header, 'info'=>$status) : $data);
        return $answer;      }     function url_corrections_for_content_HELPER( $content=false, $url=false, 	$extra_opts=array('schemeless'=>false, 'replace_src'=>false, 'rawgit_replace'=>false) ) {
            $GLOBALS['rdgr']['schemeless'] =$extra_opts['schemeless'];
            $GLOBALS['rdgr']['replace_src']=$extra_opts['replace_src'];
            $GLOBALS['rdgr']['rawgit_replace']=$extra_opts['rawgit_replace'];
            if($GLOBALS['rdgr']['schemeless'] || $GLOBALS['rdgr']['replace_src'] ) {
                if($url) {
                    $GLOBALS['rdgr']['parsed_url']			= parse_url($url);
                    $GLOBALS['rdgr']['urlparts']['domain_X']= $GLOBALS['rdgr']['parsed_url']['scheme'].'://'.$GLOBALS['rdgr']['parsed_url']['host'];
                    $GLOBALS['rdgr']['urlparts']['path_X']	= stripslashes(dirname($GLOBALS['rdgr']['parsed_url']['path']).'/');
                    $GLOBALS['rdgr']['all_protocols']= array('adc','afp','amqp','bacnet','bittorrent','bootp','camel','dict','dns','dsnp','dhcp','ed2k','empp','finger','ftp','gnutella','gopher','http','https','imap','irc','isup','javascript','ldap','mime','msnp','map','modbus','mosh','mqtt','nntp','ntp','ntcip','openadr','pop3','radius','rdp','rlogin','rsync','rtp','rtsp','ssh','sisnapi','sip','smtp','snmp','soap','smb','ssdp','stun','tup','telnet','tcap','tftp','upnp','webdav','xmpp');
                }
                $GLOBALS['rdgr']['ext_array'] 	= array(
                    'src'	=> array('audio','embed','iframe','img','input','script','source','track','video'),
                    'srcset'=> array('source'),
                    'data'	=> array('object'),
                    'href'	=> array('link','area','a'),
                    'action'=> array('form')
                    //'param', 'applet' and 'base' tags are exclusion, because of a bit complex structure
                );
                $content= preg_replace_callback(
                    '/<(((?!<).)*?)>/si', 	//avoids unclosed & closing tags
                    function($matches_A){
                        $content_A = $matches_A[0];
                        $tagname = preg_match('/((.*?)(\s|$))/si', $matches_A[1], $n) ? $n[2] : "";
                        foreach($GLOBALS['rdgr']['ext_array'] as $key=>$value){
                            if(in_array($tagname,$value)){
                                preg_match('/ '.$key.'=(\'|\")/i', $content_A, $n);
                                if(!empty($n[1])){
                                    $GLOBALS['rdgr']['aphostrope_type']= $n[1];
                                    $content_A = preg_replace_callback(
                                        '/( '.$key.'='.$GLOBALS['rdgr']['aphostrope_type'].')(.*?)('.$GLOBALS['rdgr']['aphostrope_type'].')/i',
                                        function($matches_B){
                                            $full_link = $matches_B[2];
                                            //correction to files/urls
                                            if(!empty($GLOBALS['rdgr']['replace_src'])	){
                                                //if not schemeless url
                                                if(substr($full_link, 0,2) != '//'){
                                                    $replace_src_allow=true;
                                                    //check if the link is a type of any special protocol
                                                    foreach($GLOBALS['rdgr']['all_protocols'] as $each_protocol){
                                                        //if protocol found - dont continue
                                                        if(substr($full_link, 0, strlen($each_protocol)+1) == $each_protocol.':'){
                                                            $replace_src_allow=false; break;
                                                        }
                                                    }
                                                    if($replace_src_allow){
                                                        $full_link = $GLOBALS['rdgr']['urlparts']['domain_X']. (str_replace('//','/',  $GLOBALS['rdgr']['urlparts']['path_X'].$full_link) );
                                                    }
                                                }
                                            }
                                            //replace http(s) with sheme-less urls
                                            if(!empty($GLOBALS['rdgr']['schemeless'])){
                                                $full_link=str_replace(  array('https://','http://'), '//', $full_link);
                                            }
                                            //replace github mime
                                            if(!empty($GLOBALS['rdgr']['rawgit_replace'])){
                                                $full_link= str_replace('//raw.github'.'usercontent.com/','//rawgit.com/', $full_link);
                                            }
                                            $matches_B[2]=$full_link;
                                            unset($matches_B[0]);
                                            $content_B=''; foreach ($matches_B as $each){$content_B .= $each; }
                                            return $content_B;
                                        },
                                        $content_A
                                        );
                                }
                            }
                        }
                        return $content_A;
                    },
                    $content
                    );
                $content= preg_replace_callback(
                    '/style="(.*?)background(\-image|)(.*?|)\:(.*?|)url\((\'|\"|)(.*?)(\'|\"|)\)/i',
                    function($matches_A){
                        $url = $matches_A[7];
                        $url = (substr($url,0,2)=='//' || substr($url,0,7)=='http://' || substr($url,0,8)=='https://' ? $url : '#');
                        return 'style="'.$matches_A[1].'background'.$matches_A[2].$matches_A[3].':'.$matches_A[4].'url('.$url.')'; //$matches_A[5] is url taged ,7 is url
                    },
                    $content
                    );
            }
			//print_r($content);
            return $content;
        }
    
    public function get_users_by_group($group_id = 1){
        $ids = $this->db->query("SELECT user_id FROM users_groups where group_id =".$group_id)->result_array();
        if($ids)
            return array_column($ids, 'user_id');
        else 
            return [1];
    }

    // For new set_response
    public function response_without_numeric_check($data = NULL, $http_code = NULL, $continue = FALSE)
    {
        // If the HTTP status is not NULL, then cast as an integer
        if ($http_code !== NULL)
        {
            // So as to be safe later on in the process
            $http_code = (int) $http_code;
        }

        // Set the output as NULL by default
        $output = NULL;

        // If data is NULL and no HTTP status code provided, then display, error and exit
        if ($data === NULL && $http_code === NULL)
        {
            $http_code = self::HTTP_NOT_FOUND;
        }

        // If data is not NULL and a HTTP status code provided, then continue
        elseif ($data !== NULL)
        {
            // If the format method exists, call and return the output in that format
            if (method_exists($this->format, 'to_' . $this->response->format))
            {
                // Set the format header
                $this->output->set_content_type($this->_supported_formats[$this->response->format], strtolower($this->config->item('charset')));
                
                if($this->response->format === 'json'){
                    $output = json_encode($data);
                }

                // An array must be parsed as a string, so as not to cause an array to string error
                // Json is the most appropriate form for such a datatype
                /*if ($this->response->format === 'array')
                {
                    $output = $this->format->factory($output)->{'to_json'}();
                }*/
            }
            else
            {
                // If an array or object, then parse as a json, so as to be a 'string'
                if (is_array($data) || is_object($data))
                {
                    $data = json_encode($data);
                }

                // Format is not supported, so output the raw data as a string
                $output = $data;
            }
        }

        // If not greater than zero, then set the HTTP status code as 200 by default
        // Though perhaps 500 should be set instead, for the developer not passing a
        // correct HTTP status code
        $http_code > 0 || $http_code = self::HTTP_OK;

        $this->output->set_status_header($http_code);

        // JC: Log response code only if rest logging enabled
        if ($this->config->item('rest_enable_logging') === TRUE)
        {
            $this->_log_response_code($http_code);
        }

        // Output the data
        $this->output->set_output($output);

        if ($continue === FALSE)
        {
            // Display the data and exit execution
            $this->output->_display();
            exit;
        }

        // Otherwise dump the output automatically
    }
    
    /** Overwrite the set_response
    * As it's default converting all string to number if string contains only numbers
    * @param NULL $data Data to be sent in api response
    * @param NULL $message Message to be sent for api
    * @param NULL $http_code Status code of api
    * @param FALSE $continueExe If want to continuwe execution 
    **/
    public function set_response($data = NULL,$message=NULL, $http_code = NULL,$continueExe = FALSE)
    {
        $response = array("status_code"=>$http_code,"message"=>$message,"data"=>$data);
        $this->response_without_numeric_check($response, $http_code, $continueExe);
    }

    /** 
    * Default Rest Controller response function is used to send response
    * @param NULL $data Data to be sent in api response
    * @param NULL $message Message to be sent for api
    * @param NULL $http_code Status code of api
    * @param Boolean $status
    **/
    public function set_response_simple($data = NULL,$message = "", $http_code = NULL, $status = TRUE)
    {
        $response = array("status" => $status, "http_code"=>$http_code,"message"=>$message,"data"=>$data);
        $this->response($response, $http_code, TRUE);
    }

    /** 
    * Validate access token sent in headers
    * Modify this function according to your requirements
    * @param NULL $data Data to be sent in api response
    * @param NULL $message Message to be sent for api
    * @param NULL $http_code Status code of api
    **/
    public function validate_token($access_token)
    {        
        if(empty($access_token))
        {
            return $this->set_response(new stdclass(),"Access token missing",MY_REST_Controller::HTTP_BAD_REQUEST);
        }
        
        try
        {
        $token = JWT::decode($access_token, $this->config->item('jwt_key'), array('HS256'));

            $hoursDiff = (time() - $token->time)/3600; 

            if($hoursDiff > $this->config->item('expire_time'))
            {
                return $this->set_response(new stdclass(),"Access token expired.",MY_REST_Controller::HTTP_UNAUTHORIZED);    
            }
            return $token;
        }catch(Exception $e){
            return $this->set_response(new stdclass(),"Invalid access token",MY_REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function invalidate_notification($module, $notificationType, $app_details_id, $referenceID="", $userReferenceID=""){
        try{
            if($referenceID=="" && $userReferenceID==""){
                throw new Exception("INVALID_INPUT");
            }
            $deleteWhere= array(
                "app_details_id" => $app_details_id,
                "notification_type_id" => $notificationType
            );
            if($module=="ORDER"){
                $deleteWhere['ecom_order_id'] = $referenceID;
            }
            $this->db->delete($this->notifications_model->table, $deleteWhere);
            return [
                "success"=> true
            ];
        }catch(Exception $ex){
            return [
                "success"=> false,
                "error"=> $ex
            ];
        }
    }
}
?>