<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MX_Controller
{
	public $data;
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->config->load('ion_auth', TRUE);
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
		$this->load->model('user_model');
		$this->load->model('otp_model');
	}

	// redirect if needed, otherwise display the user list
	public function index()
	{

		if (!$this->ion_auth->logged_in()) {
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		// 		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		// 		{
		// 			// redirect them to the home page because they must be an administrator to view this
		// 			return show_error('You must be an administrator to view this page.');
		// 		}
		else {
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
			$this->data['users'] = $this->ion_auth->users()->result();
			foreach ($this->data['users'] as $k => $user) {
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}
			redirect('admin', 'refresh');
			//$this->_render_page('auth/index', $this->data);
		}
	}

// Log the user in
	public function login()
	{
		$this->data['title'] = $this->lang->line('login_heading');

		// Validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

		if ($this->form_validation->run() == true) {
			// Check for "remember me"
			$remember = (bool) $this->input->post('remember');
			$login_one = $this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember);

			if ($login_one) {
				// Get user data after successful login
				$user = $this->ion_auth->user()->row();

				// Query `vendors_list` table to get the user's status
				$this->load->database();
				$this->db->select('status');
				$this->db->from('vendors_list');
				$this->db->where('vendor_user_id', $user->id);  // assuming `user_id` links `users` and `vendors_list`
				$query = $this->db->get();
				$vendor = $query->row();

				// Check if `status` in `vendors_list` is 2
				if ($vendor && $vendor->status == 2) {
					// Log the user out and set an error message
					$this->ion_auth->logout();
					$this->session->set_flashdata('message', 'You cannot log in as a vendor.');
					redirect('auth/login', 'refresh');
				} else {
					// Generate OTP
					$otp = rand(100000, 999999);

					// Check if OTP already exists for this user
					$this->db->where('user_id', $user->id);
					$existing_otp = $this->db->get('otp_requests')->row();

					if ($existing_otp) {
						// Update the existing OTP record
						$this->db->where('user_id', $user->id);
						$this->db->update('otp_requests', [
							'otp' => $otp,
							'created_at' => date('Y-m-d H:i:s'),
							'expires_at' => date('Y-m-d H:i:s', strtotime('+10 minutes')), // OTP expiry time
						]);
					} else {
						// Insert a new OTP record
						$this->db->insert('otp_requests', [
							'user_id' => $user->id,
							'otp' => $otp,
							'created_at' => date('Y-m-d H:i:s'),
							'expires_at' => date('Y-m-d H:i:s', strtotime('+10 minutes')), // OTP expiry time
						]);
					}

					// Send OTP to user (via email, SMS, etc.)
					$this->_send_otp_email($user->email, $otp);

					// Save OTP and user info in session for verification
					$this->session->set_userdata([
						'user_id' => $user->id,
						'otp_verified' => false, // OTP verification status
					]);

					// Redirect to OTP verification page
					redirect('auth/verify_otp_auth', 'refresh');
				}
			} else {
				// Second login attempt with modified identity column
				$this->ion_auth_model->identity_column = 'unique_id';
				$this->config->set_item('identity', 'unique_id');
				$login_two = $this->ion_auth->login(strtoupper($this->input->post('identity')), $this->input->post('password'), $remember);

				if ($login_two) {
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					redirect('admin', 'refresh');
				} else {
					$this->session->set_flashdata('message', 'Incorrect Login');
					redirect('auth/login', 'refresh');
				}
			}
		} else {
			// Display the login page with validation errors
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array(
				'name' => 'identity',
				'id'    => 'identity',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array(
				'name' => 'password',
				'id'   => 'password',
				'type' => 'password',
			);

			$this->_render_page('auth/login', $this->data);
		}
	}


    private function _send_otp_email($email, $otp)
    {
        // Prepare email content
        $subject = 'Your OTP Code';
        $message = '
            <html>
            <head>
                <title>Your OTP Code</title>
            </head>
            <body>
                <p>Your OTP code is: <strong>' . $otp . '</strong></p>
            </body>
            </html>
        ';
        $headers = "From: login@nextclick.com\r\n";
        $headers .= "Reply-To: login@nextclick.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
        // Attempt to send the email
        if (mail($email, $subject, $message, $headers)) { 
            return true;
        } else {
            return false;
        }
    }

public function verify_otp_auth()
{
    // Set form validation rules for OTP
    $this->form_validation->set_rules('otp', 'OTP', 'required|numeric');
   

    if ($this->form_validation->run() == true) {
        // Get the input OTP and user_id from the form
        $input_otp = $this->input->post('otp');
        $user_id = $this->session->userdata('user_id');  // Assuming user_id is stored in session
      

        // Fetch the OTP from the database for the specific user_id
        $this->db->where('user_id', $user_id);  // Match OTP request to the correct user
        $this->db->where('expires_at >', date('Y-m-d H:i:s')); // Check if OTP is still valid (not expired)
        $otp_record = $this->db->get('otp_requests')->row();  // Fetch OTP record from the database

        if ($otp_record) {
            // OTP is valid and not expired
            // Check if the entered OTP matches the one from the database
            if ($input_otp == $otp_record->otp) {
                // OTP is valid, mark it as verified
                $this->db->set('verified', 1); // Assuming you have a 'verified' field in the OTP table
                $this->db->where('user_id', $user_id); // Use OTP record ID to mark as verified
                $this->db->update('otp_requests');
				
				     // Set success message
                    $this->session->set_flashdata('message', $this->ion_auth->messages());

                    // Redirect to the admin dashboard
                    redirect('admin', 'refresh');
            } else {
                // OTP does not match the one from the database
                $this->session->set_flashdata('message', 'Invalid OTP. Please try again.');
                redirect('auth/verify_otp_auth', 'refresh');
            }
        } else {
            // OTP is invalid or expired
            $this->session->set_flashdata('message', 'Invalid OTP or OTP has expired. Please try again.');
            redirect('auth/verify_otp_auth', 'refresh');
        }
    }

    // Show the OTP verification page
    $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
    $this->_render_page('auth/verify_auth_otp', $this->data); // Show OTP input form
}


	public function login_otp()
	{
		$this->form_validation->set_rules('phone_no', str_replace(':', '', $this->lang->line('loginotp_identity_label')), 'required');
		if ($this->form_validation->run() == true) {

			$mobile = $this->input->post('phone_no');
			$user = $this->user_model->where([
				'phone' => $mobile
			])->get();

			if (!empty($user)) {
				$otp = rand(154564, 564646);
				$this->otp_model->insert(['mobile' => $mobile, 'otp' => $otp]);
				if (!empty($otp)) {
					$this->send_sms('Dear User your OTP for Registration is ' . $otp . ' ,Use this OTP to validate your Login. Regards, NEXTCLICK INFO SOLUTIONS PRIVATE LIMITED', '1207170582452344693', $mobile);
					$this->data['phone_no'] = array(
						'name' => 'phone_no',
						'id'    => 'phone_no',
						'type'  => 'text',
						'value' => $this->form_validation->set_value('phone_no'),
					);
					$this->_render_page('auth/login_otp', $this->data);
				}
			}
		} else {
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['phone_no'] = array(
				'name' => 'phone_no',
				'id'    => 'phone_no',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone_no'),
			);

			$this->_render_page('auth/login_otp', $this->data);
		}
	}

	public function verify_otp()
	{
		$mobile = $this->input->post('phone_number');
		$otp = $this->input->post('otp');
		$this->form_validation->set_rules('otp', str_replace(':', '', $this->lang->line('otp_identity_label')), 'required');
		if ($this->form_validation->run() == true) {

			$login = $this->otp_model->validate($mobile, $otp);
			if ($login) {
				$login_one = $this->ion_auth->login($this->input->post('phone_number'), '7aj9fQ48', False);
				$this->session->set_flashdata('message', 'Login SuccessFully.');
				redirect('admin', 'refresh');
			}
		} else {
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['otp'] = array(
				'name' => 'otp',
				'id'    => 'otp',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('otp'),
			);
			$this->data['phone_no'] = array(
				'name' => 'phone_no',
				'id'    => 'phone_no',
				'type'  => 'text',
				'value' => $mobile,
			);

			$this->_render_page('auth/login_otp', $this->data);
		}
	}

	function send_sms($message = "hello", $template_id = "1207169203114731853", $mobile_number = NULL)
	{
		$sms_config = $this->config->item('sms_settings');

		//Enter your login username
		$username = $sms_config->sms_username;

		//Enter your login password
		$password = $sms_config->sms_hash;

		//Enter your Sender ID
		$sender = $sms_config->sms_sender;



		$data = $this->get_remote_data('https://api.bulksmsgateway.in/sendmessage.php?', "user=$username&password=$password&mobile=$mobile_number&message=$message&sender=$sender&type=3&template_id=$template_id");

		/* $data = $this->get_remote_data('http://api.bulksmsgateway.in/sendmessage.php?', "user=$username&password=$password&mobile=$mobile_number&message=$message&sender=$sender&type=3&template_id=$template_id" );
		print_r($data);*/
	}

	function get_remote_data($url, $post_paramtrs = false,            $extra = array('schemeless' => true, 'replace_src' => true, 'return_array' => false, "curl_opts" => []))
	{
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		//if parameters were passed to this function, then transform into POST method.. (if you need GET request, then simply change the passed URL)
		if ($post_paramtrs) {
			curl_setopt($c, CURLOPT_POST, TRUE);
			curl_setopt($c, CURLOPT_POSTFIELDS, (is_array($post_paramtrs) ? http_build_query($post_paramtrs) : $post_paramtrs));
		}
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
		$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:76.0) Gecko/20100101 Firefox/76.0";
		$headers[] = "Pragma: ";
		$headers[] = "Cache-Control: max-age=0";
		if (!empty($post_paramtrs) && !is_array($post_paramtrs) && is_object(json_decode($post_paramtrs))) {
			$headers[] = 'Content-Type: application/json';
			$headers[] = 'Content-Length: ' . strlen($post_paramtrs);
		}
		curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($c, CURLOPT_MAXREDIRS, 10);
		//if SAFE_MODE or OPEN_BASEDIR is set,then FollowLocation cant be used.. so...
		$follow_allowed = (ini_get('open_basedir') || ini_get('safe_mode')) ? false : true;
		if ($follow_allowed) {
			curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
		}
		curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
		curl_setopt($c, CURLOPT_REFERER, $url);
		curl_setopt($c, CURLOPT_TIMEOUT, 60);
		curl_setopt($c, CURLOPT_AUTOREFERER, true);
		curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($c, CURLOPT_HEADER, !empty($extra['return_array']));
		//set extra options if passed
		if (!empty($extra['curl_opts'])) foreach ($extra['curl_opts'] as $key => $value) curl_setopt($c, $key, $value);
		$data = curl_exec($c);
		if (!empty($extra['return_array'])) {
			preg_match("/(.*?)\r\n\r\n((?!HTTP\/\d\.\d).*)/si", $data, $x);
			preg_match_all('/(.*?): (.*?)\r\n/i', trim('head_line: ' . $x[1]), $headers_, PREG_SET_ORDER);
			foreach ($headers_ as $each) {
				$header[$each[1]] = $each[2];
			}
			$data = trim($x[2]);
		}
		$status = curl_getinfo($c);
		curl_close($c);
		// if redirected, then get that redirected page
		if ($status['http_code'] == 301 || $status['http_code'] == 302) {
			//if we FOLLOWLOCATION was not allowed, then re-get REDIRECTED URL
			//p.s. WE dont need "else", because if FOLLOWLOCATION was allowed, then we wouldnt have come to this place, because 301 could already auto-followed by curl  :)
			if (!$follow_allowed) {
				//if REDIRECT URL is found in HEADER
				if (empty($redirURL)) {
					if (!empty($status['redirect_url'])) {
						$redirURL = $status['redirect_url'];
					}
				}
				//if REDIRECT URL is found in RESPONSE
				if (empty($redirURL)) {
					preg_match('/(Location:|URI:)(.*?)(\r|\n)/si', $data, $m);
					if (!empty($m[2])) {
						$redirURL = $m[2];
					}
				}
				//if REDIRECT URL is found in OUTPUT
				if (empty($redirURL)) {
					preg_match('/moved\s\<a(.*?)href\=\"(.*?)\"(.*?)here\<\/a\>/si', $data, $m);
					if (!empty($m[1])) {
						$redirURL = $m[1];
					}
				}
				//if URL found, then re-use this function again, for the found url
				if (!empty($redirURL)) {
					$t = debug_backtrace();
					return call_user_func($t[0]["function"], trim($redirURL), $post_paramtrs);
				}
			}
		}
		// if not redirected,and nor "status 200" page, then error..
		elseif ($status['http_code'] != 200) {
			$data =  "ERRORCODE22 with $url<br/><br/>Last status codes:" . json_encode($status) . "<br/><br/>Last data got:$data";
		}
		//URLS correction
		if (function_exists('url_corrections_for_content_HELPER')) {
			$data = url_corrections_for_content_HELPER($data, $status['url'],   array('schemeless' => !empty($extra['schemeless']), 'replace_src' => !empty($extra['replace_src']), 'rawgit_replace' => !empty($extra['rawgit_replace'])));
		}
		$answer = (!empty($extra['return_array']) ? array('data' => $data, 'header' => $header, 'info' => $status) : $data);
		return $answer;
	}
	function url_corrections_for_content_HELPER($content = false, $url = false, 	$extra_opts = array('schemeless' => false, 'replace_src' => false, 'rawgit_replace' => false))
	{
		$GLOBALS['rdgr']['schemeless'] = $extra_opts['schemeless'];
		$GLOBALS['rdgr']['replace_src'] = $extra_opts['replace_src'];
		$GLOBALS['rdgr']['rawgit_replace'] = $extra_opts['rawgit_replace'];
		if ($GLOBALS['rdgr']['schemeless'] || $GLOBALS['rdgr']['replace_src']) {
			if ($url) {
				$GLOBALS['rdgr']['parsed_url']			= parse_url($url);
				$GLOBALS['rdgr']['urlparts']['domain_X'] = $GLOBALS['rdgr']['parsed_url']['scheme'] . '://' . $GLOBALS['rdgr']['parsed_url']['host'];
				$GLOBALS['rdgr']['urlparts']['path_X']	= stripslashes(dirname($GLOBALS['rdgr']['parsed_url']['path']) . '/');
				$GLOBALS['rdgr']['all_protocols'] = array('adc', 'afp', 'amqp', 'bacnet', 'bittorrent', 'bootp', 'camel', 'dict', 'dns', 'dsnp', 'dhcp', 'ed2k', 'empp', 'finger', 'ftp', 'gnutella', 'gopher', 'http', 'https', 'imap', 'irc', 'isup', 'javascript', 'ldap', 'mime', 'msnp', 'map', 'modbus', 'mosh', 'mqtt', 'nntp', 'ntp', 'ntcip', 'openadr', 'pop3', 'radius', 'rdp', 'rlogin', 'rsync', 'rtp', 'rtsp', 'ssh', 'sisnapi', 'sip', 'smtp', 'snmp', 'soap', 'smb', 'ssdp', 'stun', 'tup', 'telnet', 'tcap', 'tftp', 'upnp', 'webdav', 'xmpp');
			}
			$GLOBALS['rdgr']['ext_array'] 	= array(
				'src'	=> array('audio', 'embed', 'iframe', 'img', 'input', 'script', 'source', 'track', 'video'),
				'srcset' => array('source'),
				'data'	=> array('object'),
				'href'	=> array('link', 'area', 'a'),
				'action' => array('form')
				//'param', 'applet' and 'base' tags are exclusion, because of a bit complex structure
			);
			$content = preg_replace_callback(
				'/<(((?!<).)*?)>/si', 	//avoids unclosed & closing tags
				function ($matches_A) {
					$content_A = $matches_A[0];
					$tagname = preg_match('/((.*?)(\s|$))/si', $matches_A[1], $n) ? $n[2] : "";
					foreach ($GLOBALS['rdgr']['ext_array'] as $key => $value) {
						if (in_array($tagname, $value)) {
							preg_match('/ ' . $key . '=(\'|\")/i', $content_A, $n);
							if (!empty($n[1])) {
								$GLOBALS['rdgr']['aphostrope_type'] = $n[1];
								$content_A = preg_replace_callback(
									'/( ' . $key . '=' . $GLOBALS['rdgr']['aphostrope_type'] . ')(.*?)(' . $GLOBALS['rdgr']['aphostrope_type'] . ')/i',
									function ($matches_B) {
										$full_link = $matches_B[2];
										//correction to files/urls
										if (!empty($GLOBALS['rdgr']['replace_src'])) {
											//if not schemeless url
											if (substr($full_link, 0, 2) != '//') {
												$replace_src_allow = true;
												//check if the link is a type of any special protocol
												foreach ($GLOBALS['rdgr']['all_protocols'] as $each_protocol) {
													//if protocol found - dont continue
													if (substr($full_link, 0, strlen($each_protocol) + 1) == $each_protocol . ':') {
														$replace_src_allow = false;
														break;
													}
												}
												if ($replace_src_allow) {
													$full_link = $GLOBALS['rdgr']['urlparts']['domain_X'] . (str_replace('//', '/',  $GLOBALS['rdgr']['urlparts']['path_X'] . $full_link));
												}
											}
										}
										//replace http(s) with sheme-less urls
										if (!empty($GLOBALS['rdgr']['schemeless'])) {
											$full_link = str_replace(array('https://', 'http://'), '//', $full_link);
										}
										//replace github mime
										if (!empty($GLOBALS['rdgr']['rawgit_replace'])) {
											$full_link = str_replace('//raw.github' . 'usercontent.com/', '//rawgit.com/', $full_link);
										}
										$matches_B[2] = $full_link;
										unset($matches_B[0]);
										$content_B = '';
										foreach ($matches_B as $each) {
											$content_B .= $each;
										}
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
			$content = preg_replace_callback(
				'/style="(.*?)background(\-image|)(.*?|)\:(.*?|)url\((\'|\"|)(.*?)(\'|\"|)\)/i',
				function ($matches_A) {
					$url = $matches_A[7];
					$url = (substr($url, 0, 2) == '//' || substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://' ? $url : '#');
					return 'style="' . $matches_A[1] . 'background' . $matches_A[2] . $matches_A[3] . ':' . $matches_A[4] . 'url(' . $url . ')'; //$matches_A[5] is url taged ,7 is url
				},
				$content
			);
		}
		//print_r($content);
		return $content;
	}


	// log the user out
	public function logout()
	{
		$this->data['title'] = "Logout";

		// log the user out
		$logout = $this->ion_auth->logout();

		// redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('auth/login', 'refresh');
	}

	// change password
	public function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false) {
			// display the form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name'    => 'new',
				'id'      => 'new',
				'type'    => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name'    => 'new_confirm',
				'id'      => 'new_confirm',
				'type'    => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);

			// render
			$this->_render_page('auth/change_password', $this->data);
		} else {
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change) {
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			} else {
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password', 'refresh');
			}
		}
	}

	// forgot password
	public function forgot_password()
	{

		// setting validation rules by checking whether identity is username or email
		if ($this->config->item('identity', 'ion_auth') != 'email') {
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		} else {
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() == false) {

			$this->data['type'] = $this->config->item('identity', 'ion_auth');
			// setup the input
			$this->data['identity'] = array(
				'name' => 'identity',
				'id' => 'identity',
			);

			if ($this->config->item('identity', 'ion_auth') != 'email') {
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			} else {
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('auth/forgot_password', $this->data);
		} else {


			$identity_column = $this->config->item('identity', 'ion_auth');
			$this->input->post('identity');
			$identity = $this->ion_auth->where('email', $this->input->post('identity'))->users()->row();

			if (empty($identity)) {
				if ($this->config->item('identity', 'ion_auth') != 'email') {
					$this->ion_auth->set_error('forgot_password_identity_not_found');
				} else {
					$this->ion_auth->set_error('forgot_password_email_not_found');
				}


				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			} else {
				// run the forgotten password method to email an activation code to the user
				$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});
				if ($forgotten) {
					// if there were no errors
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
				} else {
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					redirect("auth/forgot_password", 'refresh');
				}
			}
			exit;
		}
	}

	// reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{

		if ($code) {
			$user = $this->ion_auth->forgotten_password_check($code);
		} else {
			if (isset($_POST['id'])) {
				$user = $this->user_model->where('id', $_POST['id'])->as_object()->get();
			} else {
				$user = (object)['id' => $_GET['id']];
				$this->user_model->update([
					'id' => $_GET['id'],
					'active' => 1
				], 'id');
			}
		}

		if ($user) {
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false) {
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name'    => 'new_confirm',
					'id'      => 'new_confirm',
					'type'    => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				$this->_render_page('auth/reset_password', $this->data);
			} else {
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));
				} else {
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change) {
						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("auth/login", 'refresh');
					} else {
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		} else {
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}


	// activate the user
	public function activate($id, $code = false)
	{
		if ($code !== false) {
			$activation = $this->ion_auth->activate($id, $code);
		} else if ($this->ion_auth->is_admin()) {
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation) {
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		} else {
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	// deactivate the user
	public function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}

		$id = (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE) {
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();

			$this->_render_page('auth/deactivate_user', $this->data);
		} else {
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes') {
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
					$this->ion_auth->deactivate($id);
				}
			}

			// redirect them back to the auth page
			redirect('auth', 'refresh');
		}
	}

	// create a new user
	public function create_user()
	{
		$this->data['title'] = $this->lang->line('create_user_heading');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
		if ($identity_column !== 'email') {
			$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
		} else {
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
		}
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
		$this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() == true) {
			$email    = strtolower($this->input->post('email'));
			$identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
			$password = $this->input->post('password');

			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company'),
				'phone'      => $this->input->post('phone'),
			);
		}
		if ($this->form_validation->run() == true && $this->ion_auth->register($identity, $password, $email, $additional_data)) {
			// check to see if we are creating the user
			// redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		} else {
			// display the create user form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = array(
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			);
			$this->data['last_name'] = array(
				'name'  => 'last_name',
				'id'    => 'last_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('last_name'),
			);
			$this->data['identity'] = array(
				'name'  => 'identity',
				'id'    => 'identity',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['company'] = array(
				'name'  => 'company',
				'id'    => 'company',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('company'),
			);
			$this->data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone'),
			);
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);

			$this->_render_page('auth/create_user', $this->data);
		}
	}

	// edit a user
	public function edit_user($id)
	{
		$this->data['title'] = $this->lang->line('edit_user_heading');

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id))) {
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups = $this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required');

		if (isset($_POST) && !empty($_POST)) {
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
				show_error($this->lang->line('error_csrf'));
			}

			// update the password if it was posted
			if ($this->input->post('password')) {
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE) {
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
					'company'    => $this->input->post('company'),
					'phone'      => $this->input->post('phone'),
				);

				// update the password if it was posted
				if ($this->input->post('password')) {
					$data['password'] = $this->input->post('password');
				}



				// Only allow updating groups if user is admin
				if ($this->ion_auth->is_admin()) {
					//Update the groups user belongs to
					$groupData = $this->input->post('groups');

					if (isset($groupData) && !empty($groupData)) {

						$this->ion_auth->remove_from_group('', $id);

						foreach ($groupData as $grp) {
							$this->ion_auth->add_to_group($grp, $id);
						}
					}
				}

				// check to see if we are updating the user
				if ($this->ion_auth->update($user->id, $data)) {
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					if ($this->ion_auth->is_admin()) {
						redirect('auth', 'refresh');
					} else {
						redirect('/', 'refresh');
					}
				} else {
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					if ($this->ion_auth->is_admin()) {
						redirect('auth', 'refresh');
					} else {
						redirect('/', 'refresh');
					}
				}
			}
		}

		// display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);
		$this->data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
		);
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);

		$this->_render_page('auth/edit_user', $this->data);
	}

	// create a new group
	public function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash');

		if ($this->form_validation->run() == TRUE) {
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if ($new_group_id) {
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth", 'refresh');
			}
		} else {
			// display the create group form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			$this->_render_page('auth/create_group', $this->data);
		}
	}

	// edit a group
	public function edit_group($id)
	{
		// bail if no group id given
		if (!$id || empty($id)) {
			redirect('auth', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if ($group_update) {
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				} else {
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("auth", 'refresh');
			}
		}

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['group'] = $group;

		$readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

		$this->data['group_name'] = array(
			'name'    => 'group_name',
			'id'      => 'group_name',
			'type'    => 'text',
			'value'   => $this->form_validation->set_value('group_name', $group->name),
			$readonly => $readonly,
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);

		$this->_render_page('auth/edit_group', $this->data);
	}


	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	public function _valid_csrf_nonce()
	{
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue')) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function _render_page($view, $data = null, $returnhtml = false) //I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data : $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html; //This will return html on 3rd argument being true
	}


	public function fb($type = 'callback')
	{
		if ($type == 'callback') {
			header('Content-Type: application/json');

			$signed_request = $_POST['signed_request'];
			$data = parse_signed_request($signed_request);
			$user_id = $data['user_id'];

			// Start data deletion

			$status_url = base_url() . 'auth/fb/deletion?id=' . $user_id; // URL to track the deletion
			$confirmation_code = $user_id; // unique code for the deletion request

			$data = array(
				'url' => $status_url,
				'confirmation_code' => $confirmation_code
			);
			echo json_encode($data);

			function parse_signed_request($signed_request)
			{
				list($encoded_sig, $payload) = explode('.', $signed_request, 2);

				$secret = "5fa2e074330a01a97a9c052b9860e007"; // Use your app secret here

				// decode the data
				$sig = base64_url_decode($encoded_sig);
				$data = json_decode(base64_url_decode($payload), true);

				// confirm the signature
				$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
				if ($sig !== $expected_sig) {
					error_log('Bad Signed JSON signature!');
					return null;
				}

				return $data;
			}

			function base64_url_decode($input)
			{
				return base64_decode(strtr($input, '-_', '+/'));
			}
		} elseif ($type == 'deletion') {
			$this->data['title'] = 'FB DELETE TRACKING';
			$this->data['content'] = 'fb_deletion';
			$this->data['nav_type'] = 'role';
			$this->_render_page('template/home/main', $this->data);
		}
	}
}
