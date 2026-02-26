<?php
class User_model extends MY_Model
{
	public $rules;
	public $user_id;
	public function __construct()
	{
		parent::__construct();
		$this->table = 'users';
		$this->primary_key = 'id';

		$this->before_create[] = '_add_created_by';
		$this->before_update[] = '_add_updated_by';

		$this->_config();
		$this->_form();
		$this->_relations();

		$this->load->model('wallet_transaction_model');
	}

	protected function _add_created_by($data)
	{
		$data['created_user_id'] = $this->user_id ? $this->user_id : $this->ion_auth->get_user_id(); //add user_id
		return $data;
	}

	protected function _add_updated_by($data)
	{
		$data['updated_user_id'] = $this->user_id ? $this->user_id : $this->ion_auth->get_user_id(); //add user_id
		return $data;
	}

	private function _config()
	{
		$this->timestamps = TRUE;
		$this->soft_deletes = TRUE;
		$this->delete_cache_on_save = TRUE;
	}

	public function update_walet($user_id, $amount, $description, $type = 'CREDIT')
	{
		$user = $this->user_model->where('id', $user_id)->get();
		if ($type == 'CREDIT') {
			$balance = $user['wallet'] + floatval($amount);
		} elseif ($type == 'DEBIT') {
			$balance = $user['wallet'] - floatval($amount);
		}

		$is_updated = $this->user_model->update([
			'id' => $user_id,
			'wallet' => $balance
		], 'id');

		$this->load->model('wallet_transaction_model');
		$id = $this->wallet_transaction_model->insert([
			'user_id' => $user_id,
			'type' => 'CREDIT',
			'cash' => $amount,
			'balance' => $balance,
			'description' => $description,
			'status' => 1
		]);
	}

	private function _relations()
	{
		$this->has_one['location'] = array(
			'Location_model',
			'id',
			'location_id'
		);

		$this->has_many_pivot['groups'] = array(
			'foreign_model' => 'Group_model',
			'pivot_table' => 'users_groups',
			'local_key' => 'id',
			'pivot_local_key' => 'user_id',
			'pivot_foreign_key' => 'group_id',
			'foreign_key' => 'id',
			'get_relate' => FALSE
		);

		$this->has_many_pivot['permissions'] = array(
			'foreign_model' => 'Permission_model',
			'pivot_table' => 'users_permissions',
			'local_key' => 'id',
			'pivot_local_key' => 'user_id',
			'pivot_foreign_key' => 'perm_id',
			'foreign_key' => 'id',
			'get_relate' => FALSE
		);

		$this->has_many_pivot['wishlist'] = array(
			'foreign_model' => 'vendor_list_model',
			'pivot_table' => 'wishlist',
			'local_key' => 'id',
			'pivot_local_key' => 'user_id',
			'pivot_foreign_key' => 'list_id',
			'foreign_key' => 'id',
			'get_relate' => FALSE
		);

		$this->has_many['addresses'] = array(
			'foreign_model' => 'Users_address_model',
			'foreign_table' => 'users_address',
			'local_key' => 'id',
			'foreign_key' => 'user_id',
			'get_relate' => FALSE
		);

		$this->has_many['vendors'] = array(
			'foreign_model' => 'Vendor_list_model',
			'foreign_table' => 'vendors_list',
			'local_key' => 'id',
			'foreign_key' => 'executive_id',
			'get_relate' => FALSE
		);

		$this->has_one['executive_biometric'] = array(
			'foreign_model' => 'Executive_biometric_model',
			'local_key' => 'id',
			'foreign_key' => 'user_id',
			'get_relate' => FALSE
		);

		$this->has_one['executive_address'] = array(
			'foreign_model' => 'Executive_address_model',
			'local_key' => 'id',
			'foreign_key' => 'user_id',
			'get_relate' => FALSE
		);

		$this->has_one['business_info'] = array(
			'foreign_model' => 'Business_info_model',
			'local_key' => 'id',
			'foreign_key' => 'vendor_user_id',
			'get_relate' => FALSE
		);

		$this->has_one['delivery_boy_address'] = array(
			'foreign_model' => 'Delivery_boy_address_model',
			'local_key' => 'id',
			'foreign_key' => 'user_id',
			'get_relate' => FALSE
		);

		$this->has_one['delivery_boy_biometrics'] = array(
			'foreign_model' => 'Delivery_boy_biometric_model',
			'local_key' => 'id',
			'foreign_key' => 'user_id',
			'get_relate' => FALSE
		);

		$this->has_one['account'] = array(
			'foreign_model' => 'User_account_model',
			'local_key' => 'id',
			'foreign_key' => 'user_id',
			'get_relate' => FALSE
		);
	}
	private function _form()
	{

		$tables = $this->config->item('tables', 'ion_auth');
		$this->rules['user'] = array(
			array(
				'label' => 'First Name',
				'field' => 'first_name',
				'rules' => 'required'
			),
			array(
				'label' => 'Last Name',
				'field' => 'last_name',
				'rules' => 'required'
			),
			array(
				'label' => 'Mobile Number',
				'field' => 'mobile',
				'rules' => 'required|min_length[10]|max_length[12]|regex_match[/^[0-9]{10}$/]|callback_check_user_phone',
				'errors' => array(
					'min_length' => 'Please give minimum 10 digits number',
					'max_length' => 'You can give maximum 10 digits number',
					'regex_match' => 'Please give a valid number',
				)
			),
			array(
				'label' => 'email',
				'field' => 'email',
				'rules' => 'required|valid_email|callback_check_user_email',
				'errors' => array(
					'valid_email' => 'Please give valid email!',
				)
			),
			array(
				'label' => 'Password',
				'field' => 'password',
				'rules' => 'trim|required|required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[confirm_password]'
			),
			array(
				'label' => 'Confirm Password',
				'field' => 'confirm_password',
				'rules' => 'trim|required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password]',
				'errors' => array(
					'matches' => 'Sorry!Password Not Matched!'
				)
			)
		);
		$this->rules['creation'] = array(
			array(
				'lable' => 'First Name',
				'field' => 'first_name',
				'rules' => 'trim|required',
			),
			array(
				'lable' => 'Last Name',
				'field' => 'last_name',
				'rules' => 'trim|required'
			),
			array(
				'lable' => 'Role',
				'field' => 'role[]',
				'rules' => 'trim|required'
			),
			array(
				'lable' => 'Phone Number',
				'field' => 'phone',
				'rules' => 'trim|required|min_length[10]|max_length[10]|regex_match[/^[0-9]{10}$/]',
				'errors' => array(
					'min_length' => 'Please give minimum 10 digits number',
					'max_length' => 'You can give maximum 10 digits number',
					'regex_match' => 'Please give a valid number',
				)
			),
			array(
				'lable' => 'email',
				'field' => 'email',
				'rules' => 'trim|required|valid_email',
				'errors' => array(
					'valid_email' => 'Please give valid email!'
				)
			),
			array(
				'lable' => 'Password',
				'field' => 'password',
				'rules' => 'trim|required|required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[confirm_password]',
			),
			array(
				'lable' => 'Confirm Password',
				'field' => 'confirm_password',
				'rules' => 'trim|required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password]',
				'errors' => array(
					'matches' => 'Sorry!Password Not Matched!',
				)
			)
		);
		$this->rules['login'] = array(
			array(
				'lable' => 'Identity',
				'field' => 'identity',
				'rules' => 'trim|required',
				'errors' => array(
					'required' => 'Please give password',
				)
			),
			array(
				'lable' => 'Password',
				'field' => 'password',
				'rules' => 'trim|required',
			),
			array(
				'lable' => 'Intent',
				'field' => 'intent',
				'rules' => 'trim',
			)
		);

		$this->rules['otp'] = array(
			array(
				'lable' => 'Mobile number',
				'field' => 'mobile',
				'rules' => 'trim|required',
				'errors' => array(
					'required' => 'Please Provide Mobile number',
				)
			),
		);

		$this->rules['update'] = array(
			array(
				'lable' => 'First Name',
				'field' => 'first_name',
				'rules' => 'trim|required'
			),
			array(
				'lable' => 'Last Name',
				'field' => 'last_name',
				'rules' => 'trim|required'
			),
			array(
				'lable' => 'Role',
				'field' => 'role[]',
				'rules' => 'trim|required',
				'errors' => array(
					'required' => 'Please give an role!'
				)
			),
			array(
				'lable' => 'Phone Number',
				'field' => 'phone',
				'rules' => 'trim|required|min_length[10]|max_length[10]|regex_match[/^[0-9]{10}$/]',
				'errors' => array(
					'min_length' => 'Please give minimum 10 digits number',
					'max_length' => 'You can give maximum 10 digits number',
					'regex_match' => 'Please give a valid number'
				)
			),
			array(
				'lable' => 'email',
				'field' => 'email',
				'rules' => 'trim|required|valid_email',
				'errors' => array(
					'valid_email' => 'Please give valid email'
				)
			),
		);

		$this->rules['profile'] = array(
			array(
				'lable' => 'First Name',
				'field' => 'first_name',
				'rules' => 'required'
			),
			array(
				'lable' => 'Unique Id',
				'field' => 'unique_id',
				'rules' => 'required'
			),
			array(
				'label' => 'Phone Number',
				'field' => 'mobile',
				'rules' => 'required|min_length[10]|max_length[12]|regex_match[/^[0-9]{10}$/]|callback_is_unique_mobile',
				'errors' => array(
					'min_length' => 'Please give minimum 10 digits number',
					'max_length' => 'You can give maximum 10 digits number',
					'regex_match' => 'Please give a valid number',
					'is_unique' => 'Sorry! Mobile number is already exist!'
				)
			),
			array(
				'label' => 'email',
				'field' => 'email',
				'rules' => 'valid_email|callback_is_unique_email',
				'errors' => array(
					'valid_email' => 'Please give valid email!',
					'is_unique' => 'Sorry! Email id is already exist!'
				)
			),
		);

		$this->rules['reset'] = array(
			array(
				'lable' => 'Old Password',
				'field' => 'opass',
				'rules' => 'trim|required'
			),
			array(
				'lable' => 'New Password',
				'field' => 'npass',
				'rules' => 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[cpass]'
			),
			array(
				'lable' => 'Confirm Password',
				'field' => 'cpass',
				'rules' => 'trim|required'
			),
		);

		$this->rules['delivery_partner'] = array(

			array(
				'label' => 'First Name',
				'field' => 'first_name',
				'rules' => 'required'
			),
			array(
				'label' => 'Last Name',
				'field' => 'last_name',
				'rules' => 'required'
			),
			/* array(
																																																																							  'label' => 'Mobile Number',
																																																																							  'field' => 'mobile',
																																																																							  'rules' => 'required|min_length[10]|max_length[12]|regex_match[/^[0-9]{10}$/]|callback_check_user_phone',
																																																																							  'errors' => array(
																																																																								  'min_length' => 'Please give minimum 10 digits number',
																																																																								  'max_length' => 'You can give maximum 10 digits number',
																																																																								  'regex_match' => 'Please give a valid number',
																																																																							  )
																																																																						  ), */
			/* array(
																																																																							  'label' => 'email',
																																																																							  'field' => 'email',
																																																																							  'rules' => 'required|valid_email|callback_check_user_email',
																																																																							  'errors' => array(
																																																																								  'valid_email' => 'Please give valid email!',
																																																																								  'is_unique' => 'Sorry! Email id is already exist!'
																																																																							  )
																																																																						  ), */
			array(
				'label' => 'Password',
				'field' => 'password',
				'rules' => 'trim|required|required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']'
			)
		);

		$this->rules['executive'] = array(

			array(
				'label' => 'First Name',
				'field' => 'first_name',
				'rules' => 'required'
			),

			array(
				'label' => 'Mobile Number',
				'field' => 'mobile',
				'rules' => 'required|min_length[10]|max_length[12]|regex_match[/^[0-9]{10}$/]|callback_check_user_phone',
				'errors' => array(
					'min_length' => 'Please give minimum 10 digits number',
					'max_length' => 'You can give maximum 10 digits number',
					'regex_match' => 'Please give a valid number',
				)
			),
			array(
				'label' => 'email',
				'field' => 'email',
				'rules' => 'required|valid_email|callback_check_executive_email',
				'errors' => array(
					'valid_email' => 'Please give valid email!',
				)
			),
			array(
				'label' => 'Password',
				'field' => 'password',
				'rules' => 'trim|required|required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']'
			)
		);
	}

	public function get_users($limit = NULL, $offset = NULL, $group = NULL, $search = NULL, $unique_id = NULL)
	{
		$this->_query_users($group, $search, $unique_id);
		$this->db->order_by('`users`.id', 'DESC');
		$this->db->order_by('`users`.created_at', 'DESC');
		$this->db->order_by('`users`.updated_at', 'DESC');
		$this->db->group_by('`users`.`phone`');
		$this->db->limit($limit, $offset);
		$rs = $this->db->get($this->table);
		return $rs->result_array();
	}

	public function users_count($group = NULL, $search = NULL, $unique_id = NULL)
	{
		$this->_query_users($group, $search, $unique_id);
		return $this->db->count_all_results($this->table);
	}

	private function _query_users($group = NULL, $search = NULL, $unique_id = NULL)
	{
		$this->load->model(array('group_model'));

		$group_table = '`' . $this->group_model->table . '`';
		$group_primary_key = '`' . $this->group_model->primary_key . '`';
		$group_foreign_key = '`' . 'group_id' . '`';

		$primary_key = '`' . $this->primary_key . '`';
		$table = '`' . $this->table . '`';

		$str_select_vendor = '';
		foreach (array('created_at', 'updated_at', 'deleted_at', 'id', 'first_name', 'last_name', 'email', 'unique_id', 'phone', 'status , delivery_partner_status', 'delivery_partner_approval_status') as $v) {
			$str_select_vendor .= "$table.`$v`,";
		}
		$str_select_vendor .= "user_accounts.`wallet`,";

		$this->db->select($str_select_vendor);

		if (!empty($search)) {
			$this->db->or_like($table . '.`first_name`', $search);
			$this->db->or_like($table . '.`last_name`', $search);
			$this->db->or_where($table . '.`phone`', $search);
		}
		if (!empty($unique_id)) {
			$this->db->where($table . '.`id`', $unique_id);
			//   $this->db->or_like($table . '.`unique_id`', $unique_id);
		}


		$this->db->join('user_accounts', "`user_accounts`.`user_id`=$table.$primary_key");
		if (!empty($group)) {

			$this->db->join($group_table, "$group_table.$primary_key='users_groups.group_id'", 'left');
			$this->db->join('users_groups', "`users_groups`.`user_id`=$table.$primary_key");
			$this->db->where('`users_groups`.`group_id`', $group);
		}

		$this->db->where("$table.deleted_at", NULL);
		return $this;
	}

	public function is_unique_mobile($mobile)
	{
		if ($this->user_model->where(['phone' => $mobile, 'id !=' => $this->user_id])->get())
			return FALSE;
		else
			return TRUE;
	}

	public function is_unique_email($email)
	{
		if ($this->user_model->where(['email' => $email, 'id !=' => $this->user_id])->get())
			return FALSE;
		else
			return TRUE;
	}

	/**
	 * @desc To Update payments of users
	 * @author Mehar
	 * 
	 * @param number $user_id
	 * @param real $amount
	 * @param string $transaction_type
	 * @param string $wallet_type
	 * @param unknown $txn_id
	 * @param unknown $order_id
	 * @return boolean
	 */
	public function payment_update($user_id = 1, $amount = 0.0, $transaction_type = 'CREDIT', $wallet_type, $txn_id = NULL, $order_id = NULL, $message = NULL, $delivery_boy_payment_id = NULL, $promotion_banner_payment_id = NULL, $ecom_payment_id = NULL)
	{
		$this->load->model('user_account_model');
		$user = $this->user_model->with_account()->where('id', $user_id)->get();
		if (empty($user))
			return FALSE;
		$floatingCash = !empty($user['account']['floating_wallet']) ? $user['account']['floating_wallet'] : 0;
		if ($wallet_type == 'wallet') {
			if ($transaction_type == 'CREDIT') {
				$balance = wallet_arithmetic_operations('CREDIT', floatval($user['account'][$wallet_type]), floatval($amount));
			} elseif ($transaction_type) {
				$balance = wallet_arithmetic_operations('DEBIT', floatval($user['account'][$wallet_type]), floatval($amount));
			}
			$is_wallet_updated = $this->user_account_model->update([
				$wallet_type => floatval($balance),
				'user_id' => $user_id
			], 'user_id');
		} elseif ($wallet_type == 'floating_wallet') {
			if ($floatingCash != 0 && $floatingCash >= floatval($amount)) {
				$balance = (floatval($floatingCash) - floatval($amount));
			} else {
				$balance = floatval($amount);
			}
			/* $is_wallet_updated = $this->user_account_model->update([
																																																																							  $wallet_type => floatval($balance),
																																																																							  'user_id' => $user_id
																																																																						  ], 'user_id'); */
			$is_wallet_updated = true;
		} else {
			$is_wallet_updated = true;
		}
		switch ($wallet_type) {
			case 'wallet':
				$status = 1;
				break;
			case 'floating_wallet':
				$status = 2;
				break;
			case 'income_wallet':
				$status = 3;
				break;
			case 'security_deposite':
				$status = 4;
				break;
			default:
				$status = 1;
				break;
		}

		if ($is_wallet_updated) {
			$is_transaction_updated = $this->wallet_transaction_model->insert([
				'account_user_id' => $user['id'],
				'created_user_id' => !empty($this->ion_auth->get_user_id()) ? $this->ion_auth->get_user_id() : $user['id'],
				'amount' => floatval($amount),
				'balance' => floatval($balance),
				'txn_id' => $txn_id,
				'ecom_order_id' => $order_id,
				'type' => $transaction_type,
				'message' => $message,
				'delivery_boy_payment_id' => $delivery_boy_payment_id,
				'promotion_banner_payment_id' => $promotion_banner_payment_id,
				'ecom_payment_id' => $ecom_payment_id,
				'status' => $status
			]);
			if ($is_transaction_updated) {
				$this->updateUserAccountFloatingWallet($user['id']);
				return $is_transaction_updated;
			} else
				return FALSE;
		} else {
			return FALSE;
		}
	}


	public function fetchdata($id)
	{
		$this->db->select("*");
		$this->db->from("user_docs");
		$this->db->where('created_user_id', $id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function change_adhar($data)
	{
		$this->db->where('id', $data['id']);
		$this->db->update('user_docs', $data);
	}

	public function change_pan($data)
	{
		$this->db->where('id', $data['id']);
		$this->db->update('user_docs', $data);
	}

	public function change_cancel_cheque($data)
	{
		$this->db->where('id', $data['id']);
		$this->db->update('user_docs', $data);
	}

	public function change_driving_licence($data)
	{
		$this->db->where('id', $data['id']);
		$this->db->update('user_docs', $data);
	}

	public function change_pass_book($data)
	{
		$this->db->where('id', $data['id']);
		$this->db->update('user_docs', $data);
	}

	public function fetchbrandname($data)
	{


		$this->db->select("*");
		$this->db->from("categories_brands");
		$this->db->where('brand_id', $data['brand_id']);
		$query = $this->db->get();


		//return $query->result_array();
	}

	public function creditToWallet($userID, $amount, $orderID)
	{
		try {
			$this->load->helper('common');
			$txn_id = 'NC-' . generate_trasaction_no();
			$amount = floatval($amount);
			$this->payment_update($userID, $amount, 'CREDIT', "wallet", $txn_id, $orderID);
		} catch (Exception $e) {
			echo "Update Failed";
		}
	}

	public function debitFromWallet($userID, $amount, $orderID)
	{
		try {
			$this->load->helper('common');
			$txn_id = 'NC-' . generate_trasaction_no();
			$amount = floatval($amount);
			$this->payment_update($userID, $amount, 'DEBIT', "wallet", $txn_id, $orderID);
		} catch (Exception $e) {
			echo "Update Failed";
		}
	}

	public function creditToFloatingWallet($userID, $amount, $orderID)
	{
		try {
			$this->load->helper('common');
			$txn_id = 'NC-' . generate_trasaction_no();
			$amount = floatval($amount);
			$this->payment_update($userID, $amount, 'CREDIT', "floating_wallet", $txn_id, $orderID);
		} catch (Exception $e) {
			echo "Update Failed";
		}
	}

	public function debitFromFloatingWallet($userID, $amount, $orderID = null)
	{
		try {
			$this->load->helper('common');
			$txn_id = 'NC-' . generate_trasaction_no();
			$amount = floatval($amount);
			$this->payment_update($userID, $amount, 'DEBIT', "floating_wallet", $txn_id, $orderID);
			return [
				"success" => true
			];
		} catch (Exception $e) {
			return [
				"success" => false,
				"error" => $e
			];
		}
	}

	public function creditToIncomeWallet($userID, $amount, $orderID)
	{
		try {
			$this->load->helper('common');
			$txn_id = 'NC-' . generate_trasaction_no();
			$amount = floatval($amount);
			$this->payment_update($userID, $amount, 'CREDIT', "income_wallet", $txn_id, $orderID);
		} catch (Exception $e) {
			echo "Update Failed";
		}
	}

	public function debitFromIncomeWallet($userID, $amount, $orderID = null)
	{
		try {
			$this->load->helper('common');
			$txn_id = 'NC-' . generate_trasaction_no();
			$amount = floatval($amount);
			$this->payment_update($userID, $amount, 'DEBIT', "income_wallet", $txn_id, $orderID);
			return [
				"success" => true
			];
		} catch (Exception $e) {
			return [
				"success" => false,
				"error" => $e
			];
		}
	}

	public function createUserDefaults($userID)
	{
		try {
			$this->load->model('user_account_model');
			$userAccountRef = $this->user_account_model->create($userID);
			return [
				'success' => true,
				'data' => [
					'id' => $userAccountRef
				]
			];
		} catch (Exception $ex) {
			return [
				'success' => false,
				'error' => $ex
			];
		}
	}

	public function mutate($userID, $data)
	{
		try {
			$this->update($data, $userID);
			return [
				'success' => true
			];
		} catch (Exception $ex) {
			return [
				'success' => false,
				'error' => $ex
			];
		}
	}

	public function updateUserAccountFloatingWallet($user_id)
	{
		$sql = "select user_id,`floating_wallet`,c_account_user_id,credit_amount,  d_account_user_id,debit_amount,(COALESCE(credit_amount,0) - COALESCE(debit_amount,0)) final_floating_cash  from (
			SELECT * FROM user_accounts ua
			left join 
			(
				SELECT `account_user_id` as c_account_user_id, sum(amount) credit_amount FROM `wallet_transactions` WHERE 1 and `status` = 2 and type = 'CREDIT' group by account_user_id
			) credit_temp on ua.user_id = credit_temp.c_account_user_id
			left join 
			(
				SELECT `account_user_id` d_account_user_id, sum(amount) debit_amount FROM `wallet_transactions` WHERE 1 and `status` = 2 and type = 'DEBIT' group by account_user_id
			) debit_temp on ua.user_id = debit_temp.d_account_user_id
			) temp where user_id = " . $user_id;

		$floating_wallet_info = $this->db->query($sql)->row();

		$this->user_account_model->update([
			'floating_wallet' => floatval($floating_wallet_info->final_floating_cash),
			'user_id' => $user_id
		], 'user_id');
	}

	public function updateDeliveryBoyEarningWallet($user_id)
	{
		$sql = "update user_accounts ua 
		join (
			SELECT delivery_boy_user_id, sum(amount) amount from (
				SELECT 
					dj.delivery_boy_user_id
					,sum(round((`eo`.`delivery_fee`/(1+(`eo`.`delivery_gst_percentage`/100))),2)) amount
				FROM `ecom_orders` eo 
				join delivery_jobs dj on eo.id = dj.ecom_order_id
				join ecom_payments as ep on ep.id = eo.payment_id  
				where true 
				and  dj.status = 508
				group by dj.delivery_boy_user_id
				union all 
				SELECT 
					account_user_id as delivery_boy_user_id
					,-sum(amount) amount
				FROM `wallet_transactions` wt
				WHERE 1
				and `type` = 'DEBIT'
				and status = 1
				and `account_user_role_as` = 'delivery_boy'
				group by account_user_id)
				tem group by delivery_boy_user_id
		) temp on ua.user_id = temp.delivery_boy_user_id
		set ua.delivery_boy_earning_wallet = temp.amount
		where ua.user_id = " . $user_id;

		$this->db->query($sql);
	}

	public function updateVendorEarningWallet($user_id)
	{
		$sql = "update user_accounts ua 
		join (
			SELECT vendor_user_id, sum(amount) amount from (
				SELECT ve.vendor_user_id, sum(amount) amount from (
					SELECT 
					eo.vendor_user_id
					,(eo.total - eo.delivery_fee + eo.cupon_discount) as amount
					FROM `ecom_orders` eo 
					join delivery_jobs dj on eo.id = dj.ecom_order_id
					join ecom_payments as ep on ep.id = eo.payment_id 
					where true 
					and  dj.status = 508
				) ve 
				group by ve.vendor_user_id
				UNION ALL 
				SELECT 
					account_user_id as vendor_user_id
					,-sum(amount) amount
				FROM `wallet_transactions` wt
				WHERE 1
				and `type` = 'DEBIT'
				and status = 1
				and `account_user_role_as` = 'vendor'
				group by account_user_id
			) tem group by vendor_user_id)
		temp on ua.user_id = temp.vendor_user_id
		set ua.vendor_earning_wallet = temp.amount
		where ua.user_id = " . $user_id;

		$this->db->query($sql);
	}

	public function get_user_by_phone($phone)
	{
		$this->db->select('uc.password as user_password, u.*');
		$this->db->from('users u');
		$this->db->join('user_credentials uc', "`u`.`id`=`uc`.`user_id`");
		$this->db->where('u.phone', $phone);
		$query = $this->db->get();

		if (!$query) {
			$error = $this->db->error();
			echo "Error: " . $error['code'] . " - " . $error['message'];
			return false;
		} else {
			return $query->result_array();
		}
	}

	public function get_user_roles($id)
	{
		$this->db->select('GROUP_CONCAT(group_id) as group_ids');
		$this->db->from('users_groups');
		$this->db->where('user_id', $id);
		$query = $this->db->get();

		if (!$query) {
			$error = $this->db->error();
			echo "Error: " . $error['code'] . " - " . $error['message'];
			return false;
		} else {
			return $query->result_array();
		}
	}

	public function get_user_by_id($id)
	{
		$this->db->select('u.*');
        $this->db->from('users u');
        $this->db->where('u.id', $id);
        
        $this->db->group_start();
        $this->db->where('u.primary_intent', 'executive');
        $this->db->or_where('u.primary_intent', 'vendor');
        $this->db->or_where('u.primary_intent', 'delivery_partner');
        $this->db->or_where('u.primary_intent', 'user');
        $this->db->group_end();
        
        $query = $this->db->get();


		if (!$query) {
			$error = $this->db->error();
			echo "Error: " . $error['code'] . " - " . $error['message'];
			return false;
		} else {
			return $query->result_array();
		}
	}

	public function referral_check($code)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('referral_code', $code);
		$query = $this->db->get();

		if (!$query) {
			$error = $this->db->error();
			echo "Error: " . $error['code'] . " - " . $error['message'];
			return false;
		} else {
			return $query->result_array();
		}
	}

	public function get_users_count($executive_id)
	{
		$this->db->select("u.*, CONCAT_WS(' ', exec.first_name, exec.last_name) as executive_name");
		$this->db->from('users u');
		$this->db->join('users as exec', 'u.executive_user_id = exec.id');
		$this->db->where('u.executive_user_id', $executive_id);
		$this->db->order_by('u.created_at', 'DESC');

		$query = $this->db->get();
		$count = $query->num_rows();

		if (!$query) {
			$error = $this->db->error();
			echo "Error: " . $error['code'] . " - " . $error['message'];
			return false;
		} else {
			return array(
				'count' => $count,
				'user_details' => $query->result()
			);
		}
	}



	public function get_executive_user_list($status = '', $executive_id = '')
	{
		$this->db->select("u.*, CONCAT_WS(' ', exec.first_name, exec.last_name) as executive_name, et.executive_type");
		$this->db->from('users u');
		$this->db->join('users as exec', 'u.executive_user_id = exec.id');
		$this->db->join('executive_address as ead', 'ead.user_id = exec.id', 'left');
		$this->db->join('executive_type as et', 'et.id = ead.executive_type_id', 'left');

		if ($status == 'ordered' && $executive_id != '') {
			$this->db->where('u.executive_user_id', $executive_id);
			$this->db->where('u.first_order_id IS NOT NULL');
			$this->db->where('u.first_order_at IS NOT NULL');
		} else if ($status == 'not_ordered' && $executive_id != '') {
			$this->db->where('u.executive_user_id', $executive_id);
			$this->db->where('u.first_order_id', NULL);
			$this->db->where('u.first_order_at', NULL);
		} else if ($status == 'ordered') {
			$this->db->where('u.executive_user_id IS NOT NULL');
			$this->db->where('u.first_order_id IS NOT NULL');
			$this->db->where('u.first_order_at IS NOT NULL');
		} else if ($status == 'not_ordered') {
			$this->db->where('u.executive_user_id IS NOT NULL');
			$this->db->where('u.first_order_id', NULL);
			$this->db->where('u.first_order_at', NULL);
		} else {
			if ($executive_id != '') {
				$this->db->where('u.executive_user_id', $executive_id);
			} else {
				$this->db->where('u.executive_user_id IS NOT NULL');
			}
		}
		$this->db->order_by('u.created_at', 'DESC');

		$query = $this->db->get();
		$count = $query->num_rows();

		if (!$query) {
			$error = $this->db->error();
			echo "Error: " . $error['code'] . " - " . $error['message'];
			return false;
		} else {
			return array(
				'count' => $count,
				'user_details' => $query->result()
			);
		}
	}

	public function is_aadhar_exists($aadhar)
	{
		$aadhar_number = trim($aadhar);
		$query1 = $this->db->select('aadhar_number')
			->from('users')
			->where('aadhar_number', $aadhar_number)
			->get_compiled_select();

		$query2 = $this->db->select('aadhar as aadhar_number')
			->from('delivery_boy_biometrics')
			->where('aadhar', $aadhar_number)
			->get_compiled_select();


		$final_query = $query1 . ' UNION ALL ' . $query2;

		$result = $this->db->query($final_query);

		$count = $result->num_rows();

		return $count > 0;
	}

	public function is_aadhar_existsjs($aadhar)
	{
		$aadhar_number = trim($aadhar);
		$query1 = $this->db->select('aadhar_number')
			->from('users')
			->where('aadhar_number', $aadhar_number)
			->get_compiled_select();

		$query2 = $this->db->select('aadhar as aadhar_number')
			->from('delivery_boy_biometrics')
			->where('aadhar', $aadhar_number)
			->get_compiled_select();


		$query3 = $this->db->select('aadhar as aadhar_number')
			->from('executive_biometrics')
			->where('aadhar', $aadhar_number)
			->get_compiled_select();


		$final_query = $query1 . ' UNION ALL ' . $query2 . ' UNION ALL ' . $query3;
		$result = $this->db->query($final_query);

		$count = $result->num_rows();

		return $count > 0;
	}
	
    	public function getReferralUsedCount($executive_user_id)
    {
        return $this->db
            ->where('executive_user_id', $executive_user_id)
            ->count_all_results('users');
    }
    public function getVendorReferralCount($vendor_user_id)
    {
        return $this->db
            ->where('executive_user_id', $vendor_user_id)
            ->count_all_results('users');
    }

	
}
