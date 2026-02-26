<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* load the MX_Router class */
require APPPATH . "third_party/MX/Controller.php";

class MY_Controller extends MX_Controller
{	
    protected $template;
    protected $data;
	function __construct() 
	{
		parent::__construct();
		$this->_hmvc_fixes();
		
		if ( $this->ion_auth->logged_in() )
		   $this->data['user'] = $this->ion_auth->user()->row();
		
		   $this->load->model('category_model');
		   date_default_timezone_set('Asia/Kolkata');
		   $this->load->model('fcm_model');
		   $this->load->library('fcm');
		   $this->load->model('notifications_model');
		   $this->load->model('app_details_model');
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
	    return json_decode($this->fcm->sendMultiple($tokens, $json, $app_details['fcm_server_key']));
	}

	/**
	 * @desc to send fcm push notifications for pick up and drop orders
	 * @author mehar
	 *
	 * @param array $token
	 * @param string $title
	 * @param string $message
	 * @param string $image
	 * @param array $payload
	 */
	public function send_notification_pickupanddrop($user_id = [], $app_details_id = NULL, $title = NULL, $message = NULL, $payload = [])
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
	                        'pickup_order_id' => ! empty($payload['pickup_order_id'])? $payload['pickup_order_id'] : NULL
	                    ]);
	                }elseif (empty($notifications)){
	                    array_push($notifications, [
	                        'notification_type_id' => $payload['notification_type']['id'],
	                        'app_details_id' => $app_details_id,
	                        'title' => $title,
	                        'message' => $message,
	                        'notified_user_id' => $id,
	                        'pickup_order_id' => ! empty($payload['pickup_order_id'])? $payload['pickup_order_id'] : NULL
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
	                'pickup_order_id' => ! empty($payload['order_id'])? $payload['order_id'] : NULL
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
	    return json_decode($this->fcm->sendMultiple($tokens, $json, $app_details['fcm_server_key'], $url));
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
	    if ($return_value){
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
		
		if ($email_value)
		{
			$this->form_validation->set_message('check_user_email', 'Sorry, This email is already used by another user.');
			return FALSE;
		}
		else
		{
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
	
	public function get_users_by_group($group_id = 1){
	    $ids = $this->db->query("SELECT user_id FROM users_groups where group_id =".$group_id)->result_array();
	    if($ids)
	        return array_column($ids, 'user_id');
	        else
	            return [1];
	}
	
	function _hmvc_fixes()
	{		
		//fix callback form_validation		
		//https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this;
	}
	
	/**
	 * Displays the specified view
	 * @param array $data
	 */
	function _render_page($view, $data=null, $returnhtml=false)
	{
	    $this->viewdata = (empty($data)) ? $this->data: $data;
	    $view_html = $this->load->view($view, $this->viewdata, $returnhtml);
	    if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
	
	
	/**
	 * Prepare flash message
	 *
	 */
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
	    
	    $this->session->set_flashdata("message",$returnmsg);
	}
	
	function prepare_message($msg,$type = 2)
	{
	    $returnmsg='';
	    switch($type){
	        case 0: $returnmsg = " <!-- <div class='col-md-12'> -->
										<div class='alert alert-success'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>Success..!!</strong> ". $msg."
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
	    
	    return $returnmsg;
	}
	
	function set_pagination($url,$offset,$numrows,$perpage,$pagingfunction='')
	{
	    $config['base_url'] = SITEURL.$url;  //Setting Pagination parameters
	    $config['per_page'] = $perpage;
	    $config['offset'] = $offset;
	    $config['num_tag_open'] = '<li>';
	    $config['num_tag_close'] = '</li>';
	    $config['num_links'] = 4; // numlinks before and after current page
	    $config['total_rows'] =  $numrows;
	    
	    $config['first_link'] = 'First';
	    $config['last_link'] = 'Last';
	    if(!empty($pagingfunction))
	        $config['paging_function'] = $pagingfunction;
	        else	$config['paging_function'] = 'ajax_paging';
	        $this->pagination->initialize($config);
	}
	
	/**
	 * Validate URL
	 *
	 * @access    public
	 * @param    string
	 * @return    string
	 */
	function valid_url($url)
	{
	    $pattern = "|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i";
	    if (!preg_match($pattern, $url))
	    {
	        return FALSE;
	    }
	    
	    return TRUE;
	}
	
	function get_safe_template() {
	    if ( $this->ion_auth->is_student() ) {
	        $this->_render_page('template/site/student-template', $this->data);
	    } elseif( $this->ion_auth->is_tutor() ) {
	        $this->_render_page('template/site/tutor-template', $this->data);
	    } elseif( $this->ion_auth->is_institute() ) {
	        $this->_render_page('template/site/institute-template', $this->data);
	    } else {
	        $this->_render_page('template/admin/admin-template', $this->data);
	    }
	}
	
	
	// FILE_UPLOAD
	function img_thumb($type, $id, $ext = '.jpg', $width = '400', $height = '400')
	{
	    $this->load->library('image_lib');
	    ini_set("memory_limit", "-1");
	    
	    $config1['image_library']  = 'gd2';
	    $config1['create_thumb']   = TRUE;
	    $config1['maintain_ratio'] = TRUE;
	    $config1['width']          = $width;
	    $config1['height']         = $height;
	    $config1['source_image']   = 'uploads/' . $type . '_image/' . $type . '_' . $id . $ext;
	    
	    $this->image_lib->initialize($config1);
	    $this->image_lib->resize();
	    $this->image_lib->clear();
	}
	
	// FILE_UPLOAD
	function file_up($name, $type, $id, $multi = '', $no_thumb = '', $ext = '.jpg')
	{
	    if (!file_exists('uploads/' . $type . '_image/')) {
	        mkdir('uploads/' . $type . '_image/', 0777, true);
	    }
	    if ($multi == '') {
	        move_uploaded_file($_FILES[$name]['tmp_name'], 'uploads/' . $type . '_image/' . $type . '_' . $id . $ext);
	        if ($no_thumb == '') {
	            $this->img_thumb($type, $id, $ext);
	        }
	    } elseif ($multi == 'multi') {
	        $ib = 1;
	        foreach ($_FILES[$name]['name'] as $i => $row) {
	            $ib = $this->file_exist_ret($type, $id, $ib);
	            move_uploaded_file($_FILES[$name]['tmp_name'][$i], 'uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $ib . $ext);
	            if ($no_thumb == '') {
	                $this->crud_model->img_thumb($type, $id . '_' . $ib, $ext);
	            }
	        }
	    }
	}
	
	// FILE_UPLOAD : EXT :: FILE EXISTS
	function file_exist_ret($type, $id, $ib, $ext = '.jpg')
	{
	    if (file_exists('uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $ib . $ext)) {
	        $ib = $ib + 1;
	        $ib = $this->file_exist_ret($type, $id, $ib);
	        return $ib;
	    } else {
	        return $ib;
	    }
	}


// FILE_VIEW
function file_view($type, $id, $width = '100', $height = '100', $thumb = 'no', $src = 'no', $multi = '', $multi_num = '', $ext = '.jpg')
{
    if ($multi == '') {
        if (file_exists('uploads/' . $type . '_image/' . $type . '_' . $id . $ext)) {
            if ($thumb == 'no') {
                $srcl = base_url() . 'uploads/' . $type . '_image/' . $type . '_' . $id . $ext;
            } elseif ($thumb == 'thumb') {
                $srcl = base_url() . 'uploads/' . $type . '_image/' . $type . '_' . $id . '_thumb' . $ext;
            }
            
            if ($src == 'no') {
                return '<img src="' . $srcl . '" height="' . $height . '" width="' . $width . '" />';
            } elseif ($src == 'src') {
                return $srcl;
            }
        }
        else{
            return base_url() . 'uploads/'. $type.'_image/default.jpg';
        }
        
    } else if ($multi == 'multi') {
       // $num    = $this->crud_model->get_type_name_by_id($type, $id, 'num_of_imgs');
        $num = 200;
        $i      = 0;
        $p      = 0;
        $q      = 0;
        $return = array();
        while ($p < $num) {
            $i++;
            if (file_exists('uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $i . $ext)) {
                if ($thumb == 'no') {
                    $srcl = base_url() . 'uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $i . $ext;
                } elseif ($thumb == 'thumb') {
                    $srcl = base_url() . 'uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $i . '_thumb' . $ext;
                }
                
                if ($src == 'no') {
                    $return[] = '<img src="' . $srcl . '" height="' . $height . '" width="' . $width . '" />';
                } elseif ($src == 'src') {
                    $return[] = $srcl;
                }
                $p++;
            } else {
                $q++;
                if ($q == 10) {
                    break;
                }
            }
        }
        if (!empty($return)) {
            if ($multi_num == 'one') {
                return $return[0];
            } else if ($multi_num == 'all') {
                return $return;
            } else {
                $n = $multi_num - 1;
                unset($return[$n]);
                return $return;
            }
        } else {
            if ($multi_num == 'one') {
                return base_url() . 'uploads/'. $type.'_image/default.jpg';
            } else if ($multi_num == 'all') {
                return array(base_url() . 'uploads/'. $type.'_image/default.jpg');
            } else {
                return array(base_url() . 'uploads/'. $type.'_image/default.jpg');
            }
        }
    }
}


// FILE_VIEW
function file_dlt($type, $id, $ext = '.jpg', $multi = '', $m_sin = '')
{
    if ($multi == '') {
        if (file_exists('uploads/' . $type . '_image/' . $type . '_' . $id . $ext)) {
            unlink("uploads/" . $type . "_image/" . $type . "_" . $id . $ext);
        }
        if (file_exists("uploads/" . $type . "_image/" . $type . "_" . $id . "_thumb" . $ext)) {
            unlink("uploads/" . $type . "_image/" . $type . "_" . $id . "_thumb" . $ext);
        }
        
    } else if ($multi == 'multi') {
        //$num = $this->crud_model->get_type_name_by_id($type, $id, 'num_of_imgs');
        $num=200;
        if ($m_sin == '') {
            $i = 0;
            $p = 0;
            while ($p < $num) {
                $i++;
                if (file_exists('uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $i . $ext)) {
                    unlink("uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $i . $ext);
                    $p++;
                    $data['num_of_imgs'] = $num - 1;
                    $this->db->where($type . '_id', $id);
                    $this->db->update($type, $data);
                }
                
                if (file_exists("uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $i . "_thumb" . $ext)) {
                    unlink("uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $i . "_thumb" . $ext);
                }
                if ($i > 50) {
                    break;
                }
            }
        } else {
            if (file_exists('uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $m_sin . $ext)) {
                unlink("uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $m_sin . $ext);
            }
            if (file_exists("uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $m_sin . "_thumb" . $ext)) {
                unlink("uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $m_sin . "_thumb" . $ext);
            }
            $data['num_of_imgs'] = $num - 1;
            $this->db->where($type . '_id', $id);
            $this->db->update($type, $data);
        }
    }
}
}
/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
