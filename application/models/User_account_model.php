<?php

class User_account_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Vehicle_model');

        $this->table = 'user_accounts';
        $this->primary_key = 'id';

        $this->_config();
        $this->_relations();
    }
    private function _config()
    {
        $this->timestamps = TRUE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }

    private function _relations()
    {
    }

    public function create($userID)
    {
        try {
            $isUserAccountExists = $this->where([
                'user_id' => $userID
            ])->get();
            if ($isUserAccountExists) {
                return [
                    'success' => true,
                    'data' => [
                        'id' => $isUserAccountExists['id']
                    ]
                ];
            }
            $UserWalletRef = $this->insert([
                'user_id' => $userID,
                'wallet' => 0,
                'floating_wallet' => 0
            ]);
            return [
                'success' => true,
                'data' => [
                    'id' => $UserWalletRef
                ]
            ];
        } catch (Exception $ex) {
            return [
                'success' => false,
                'error' => $ex
            ];
        }
    }

    public function getWallet($userID)
    {
        return $this->where([
            'user_id' => $userID
        ])->get();
    }

    public function checkandUpdateAccount($userID, $listingID = null, $intent = "vendor")
    {
        $this->load->model('user_model');
        $this->load->model('vendor_bank_details_model');
        $this->load->model('delivery_boy_bank_details_model');
        $record = $this->getWallet($userID);
        if (!$record['external_id']) {
            $userRecord = $this->user_model->where([
                'id' => $userID
            ])->get();
            $externalID = $this->createContact($userRecord['first_name'] . " " . $userRecord['last_name'], $userRecord['email'], $userRecord['phone'], $userID);
            $this->update([
                'external_id' => $externalID
            ], [
                'user_id' => $userID
            ]);
            if ($intent == 'vendor') {
                $this->vendor_bank_details_model->checkUpdateExternalAccount($userID, $listingID, $externalID);
            } else {
                $this->delivery_boy_bank_details_model->checkUpdateExternalAccount($userID, $externalID);
            }
            return true;
        } else {
            if ($intent == 'vendor') {
                $this->vendor_bank_details_model->checkUpdateExternalAccount($userID, $listingID, $record['external_id']);
                return true;
            } else {
                $this->delivery_boy_bank_details_model->checkUpdateExternalAccount($userID, $record['external_id']);
                return true;
            }
        }
    }

    public function createContact($name, $email, $phone, $userID)
    {
        try {
            $postRequest = array(
                'name' => $name,
                'email' => $email,
                'contact' => $phone,
                'type' => 'customer',
                'reference_id' => "Account for " . $userID
            );
            $razorPayInfo = $this->config->item('razorpay');
            $cURLConnection = curl_init('https://api.razorpay.com/v1/contacts');
            curl_setopt($cURLConnection, CURLOPT_USERPWD, $razorPayInfo['key'] . ":" . $razorPayInfo["secret"]);
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, json_encode($postRequest));
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            )
            );
            $apiResponse = curl_exec($cURLConnection);
            curl_close($cURLConnection);

            // $apiResponse - available data from the API request
            $jsonArrayResponse = json_decode($apiResponse);
            return $jsonArrayResponse->id;
            // print_r($jsonArrayResponse);exit;
        } catch (Exception $e) {
            print_r($e);
            exit;
        }
    }

    public function prepareVendorPayouts()
    {
        $this->load->model(
            array(
                'user_model',
                'vendor_list_model',
                'vendor_bank_details_model'
            )
        );
        $table = $this->table;
        $user_table = '`' . $this->user_model->table . '`';
        $vendor_list_table = '`' . $this->vendor_list_model->table . '`';
        $vendor_bank_details_table = '`' . $this->vendor_bank_details_model->table . '`';
        $vendor_bank_foriegn_key = '`' . 'list_id' . '`';
        $vendor_list_foriegn_key = '`' . 'vendor_user_id' . '`';
        $user_table_foriegn_key = '`' . 'user_id' . '`';
        $user_table_primary_key = '`' . $this->user_model->primary_key . '`';
        $this->db->select("$table.wallet, $vendor_list_table.business_name, $user_table.id, $user_table.first_name, $user_table.last_name, $vendor_bank_details_table.external_id, $vendor_bank_details_table.id as vendor_bank_id");
        $this->db->join($table, "$table.$user_table_foriegn_key=$user_table.$user_table_primary_key", 'inner');
        $this->db->join($vendor_list_table, "$vendor_list_table.$vendor_list_foriegn_key=$user_table.$user_table_primary_key", 'inner');
        $this->db->join($vendor_bank_details_table, "$vendor_bank_details_table.$vendor_bank_foriegn_key=$vendor_list_table.id AND " . $vendor_bank_details_table . ".`status`=1", 'left');
        $rs = $this->db->get($user_table)->result_array();
        return $rs;
    }

    public function prepareVendorPayouts_data($rowperpage, $rowno, $search_text)
    {
        $this->load->model(
            array(
                'user_model',
                'vendor_list_model',
                'vendor_bank_details_model'
            )
        );
        $table = $this->table;
        $user_table = '`' . $this->user_model->table . '`';
        $vendor_list_table = '`' . $this->vendor_list_model->table . '`';
        $vendor_bank_details_table = '`' . $this->vendor_bank_details_model->table . '`';
        $vendor_bank_foriegn_key = '`' . 'list_id' . '`';
        $vendor_list_foriegn_key = '`' . 'vendor_user_id' . '`';
        $user_table_foriegn_key = '`' . 'user_id' . '`';
        $user_table_primary_key = '`' . $this->user_model->primary_key . '`';
        $this->db->select("$table.wallet, $vendor_list_table.business_name, $user_table.id, $user_table.first_name, $user_table.last_name, $vendor_bank_details_table.external_id, $vendor_bank_details_table.id as vendor_bank_id");
        $this->db->join($table, "$table.$user_table_foriegn_key=$user_table.$user_table_primary_key", 'inner');
        $this->db->join($vendor_list_table, "$vendor_list_table.$vendor_list_foriegn_key=$user_table.$user_table_primary_key", 'inner');
        if ($search_text != "") {
            $this->db->like('vendors_list.name', $search_text);

        }
        $this->db->join($vendor_bank_details_table, "$vendor_bank_details_table.$vendor_bank_foriegn_key=$vendor_list_table.id AND " . $vendor_bank_details_table . ".`status`=1", 'left');
        $this->db->limit($rowperpage, $rowno);
        $rs = $this->db->get($user_table)->result_array();
        //echo $this->db->last_query();exit;
        return $rs;
    }

    public function prepareDeliveryPartnerPayouts()
    {
        $this->load->model(
            array(
                'user_model',
                'delivery_boy_bank_details_model'
            )
        );
        $table = $this->table;
        $user_table = '`' . $this->user_model->table . '`';
        $delivery_boy_bank_details_table = '`' . $this->delivery_boy_bank_details_model->table . '`';
        $delivery_boy_bank_foriegn_key = '`' . 'user_id' . '`';
        $user_table_foriegn_key = '`' . 'user_id' . '`';
        $user_table_primary_key = '`' . $this->user_model->primary_key . '`';
        $this->db->select("$table.wallet, $user_table.id, $user_table.first_name, $user_table.last_name, $delivery_boy_bank_details_table.external_id, $delivery_boy_bank_details_table.id as delivery_boy_bank_id");
        $this->db->join($table, "$table.$user_table_foriegn_key=$user_table.$user_table_primary_key", 'inner');
        $this->db->join($delivery_boy_bank_details_table, "$delivery_boy_bank_details_table.$delivery_boy_bank_foriegn_key=$user_table.id AND " . $delivery_boy_bank_details_table . ".`status`=1", 'left');
        $this->db->where("$user_table.primary_intent", 'delivery_partner'); //added by manoj for delivery partner details
        $rs = $this->db->get($user_table)->result_array();
        //print_r($this->db->last_query());exit;
        return $rs;
    }

    public function prepareDeliveryPartnerPayouts_data($rowperpage, $rowno, $search_text)
    {
        $this->load->model(
            array(
                'user_model',
                'delivery_boy_bank_details_model'
            )
        );
        $table = $this->table;
        $user_table = '`' . $this->user_model->table . '`';
        $delivery_boy_bank_details_table = '`' . $this->delivery_boy_bank_details_model->table . '`';
        $delivery_boy_bank_foriegn_key = '`' . 'user_id' . '`';
        $user_table_foriegn_key = '`' . 'user_id' . '`';
        $user_table_primary_key = '`' . $this->user_model->primary_key . '`';
        $this->db->select("$table.wallet, $user_table.id, $user_table.first_name, $user_table.last_name, $delivery_boy_bank_details_table.external_id, $delivery_boy_bank_details_table.id as delivery_boy_bank_id");
        if ($search_text != "") {
            $this->db->like("CONCAT($user_table.first_name, ' ', $user_table.last_name)", $search_text);
            //$this->db->or_like("$user_table.last_name", $search_text);

        }
        $this->db->join($table, "$table.$user_table_foriegn_key=$user_table.$user_table_primary_key", 'inner');
        $this->db->join($delivery_boy_bank_details_table, "$delivery_boy_bank_details_table.$delivery_boy_bank_foriegn_key=$user_table.id AND " . $delivery_boy_bank_details_table . ".`status`=1", 'left');
        $this->db->where("$user_table.primary_intent", 'delivery_partner'); //added by manoj for delivery partner details
        $this->db->limit($rowperpage, $rowno);
        $rs = $this->db->get($user_table)->result_array();
        //print_r($this->db->last_query());exit;
        return $rs;
    }

    public function fetcTotalPayouts($search_text)
    {
        $this->load->model(
            array(
                'user_model',
                'vendor_list_model',
                'vendor_bank_details_model'
            )
        );
        $table = $this->table;
        $user_table = '`' . $this->user_model->table . '`';
        $vendor_list_table = '`' . $this->vendor_list_model->table . '`';
        $vendor_bank_details_table = '`' . $this->vendor_bank_details_model->table . '`';
        $vendor_bank_foriegn_key = '`' . 'list_id' . '`';
        $vendor_list_foriegn_key = '`' . 'vendor_user_id' . '`';
        $user_table_foriegn_key = '`' . 'user_id' . '`';
        $user_table_primary_key = '`' . $this->user_model->primary_key . '`';
        $this->db->select("SUM($table.wallet) as total");
        $this->db->join($table, "$table.$user_table_foriegn_key=$user_table.$user_table_primary_key", 'inner');
        $this->db->join($vendor_list_table, "$vendor_list_table.$vendor_list_foriegn_key=$user_table.$user_table_primary_key", 'inner');
        //$this->db->join($vendor_bank_details_table, "$vendor_bank_details_table.$vendor_bank_foriegn_key=$vendor_list_table.id AND ".$vendor_bank_details_table.".`status`=1", 'inner');
        if ($search_text != "") {
            $this->db->like('vendors_list.name', $search_text);

        }
        //$this->db->where("$vendor_bank_details_table.`external_id`!=", NULL);
        $rs = $this->db->get($user_table)->result_array();
        //echo $this->db->last_query();exit;
        return $rs[0]['total'];
    }

    public function fetcTotalPayouts_count($search_text)
    {
        $this->load->model(
            array(
                'user_model',
                'vendor_list_model',
                'vendor_bank_details_model'
            )
        );
        $table = $this->table;
        $user_table = '`' . $this->user_model->table . '`';
        $vendor_list_table = '`' . $this->vendor_list_model->table . '`';
        $vendor_bank_details_table = '`' . $this->vendor_bank_details_model->table . '`';
        $vendor_bank_foriegn_key = '`' . 'list_id' . '`';
        $vendor_list_foriegn_key = '`' . 'vendor_user_id' . '`';
        $user_table_foriegn_key = '`' . 'user_id' . '`';
        $user_table_primary_key = '`' . $this->user_model->primary_key . '`';
        $this->db->select("count(*) as count");
        $this->db->join($table, "$table.$user_table_foriegn_key=$user_table.$user_table_primary_key", 'inner');
        $this->db->join($vendor_list_table, "$vendor_list_table.$vendor_list_foriegn_key=$user_table.$user_table_primary_key", 'inner');
        //$this->db->join($vendor_bank_details_table, "$vendor_bank_details_table.$vendor_bank_foriegn_key=$vendor_list_table.id AND ".$vendor_bank_details_table.".`status`=1", 'inner');
        if ($search_text != "") {
            $this->db->like('vendors_list.name', $search_text);

        }
        //$this->db->where("$vendor_bank_details_table.`external_id`!=", NULL);
        $rs = $this->db->get($user_table)->result_array();
        //echo $this->db->last_query();exit;
        return $rs[0]['count'];
    }

    public function fetchDeliveryBoyTotalPayouts($search_text)
    {
        $this->load->model(
            array(
                'user_model',
                'delivery_boy_bank_details_model'
            )
        );
        $table = $this->table;
        $user_table = '`' . $this->user_model->table . '`';
        $delivery_boy_bank_details_table = '`' . $this->delivery_boy_bank_details_model->table . '`';
        $delivery_boy_bank_foriegn_key = '`' . 'user_id' . '`';
        $user_table_foriegn_key = '`' . 'user_id' . '`';
        $user_table_primary_key = '`' . $this->user_model->primary_key . '`';
        $this->db->select("SUM($table.wallet) as total");
        $this->db->join($table, "$table.$user_table_foriegn_key=$user_table.$user_table_primary_key", 'inner');
        if ($search_text != "") {
            $this->db->like("$user_table.first_name", $search_text);
            $this->db->or_like("$user_table.last_name", $search_text);

        }
        $this->db->where("$user_table.primary_intent", 'delivery_partner');
        //$this->db->join($delivery_boy_bank_details_table, "$delivery_boy_bank_details_table.$delivery_boy_bank_foriegn_key=$user_table.id AND ".$delivery_boy_bank_details_table.".`status`=1", 'inner');
        $rs = $this->db->get($user_table)->result_array();
        //echo $this->db->last_query();exit;
        return $rs;
    }

    public function fetcTotaldeliveryPayouts_count($search_text)
    {
        $this->load->model(
            array(
                'user_model',
                'delivery_boy_bank_details_model'
            )
        );
        $table = $this->table;
        $user_table = '`' . $this->user_model->table . '`';
        $delivery_boy_bank_details_table = '`' . $this->delivery_boy_bank_details_model->table . '`';
        $delivery_boy_bank_foriegn_key = '`' . 'user_id' . '`';
        $user_table_foriegn_key = '`' . 'user_id' . '`';
        $user_table_primary_key = '`' . $this->user_model->primary_key . '`';
        $this->db->select("count(*)as count");
        $this->db->join($table, "$table.$user_table_foriegn_key=$user_table.$user_table_primary_key", 'inner');
        $this->db->where("$user_table.primary_intent", 'delivery_partner');
        if ($search_text != "") {
            $this->db->like("CONCAT($user_table.first_name, ' ', $user_table.last_name)", $search_text);
            // $this->db->or_like("$user_table.last_name", $search_text);

        }
        //$this->db->join($delivery_boy_bank_details_table, "$delivery_boy_bank_details_table.$delivery_boy_bank_foriegn_key=$user_table.id AND ".$delivery_boy_bank_details_table.".`status`=1", 'inner');
        $rs = $this->db->get($user_table)->result_array();
        //echo $this->db->last_query();exit;
        return $rs[0]['count'];
    }


    public function verifySecurityDepositeValue($userIds, $order_id, $amount)
    {

        $securityDeposite = (int) $this->Vehicle_model->get_all()[0]['security_deposited_amount'];
        $userAccData = $this->db->select('*')
            ->from('user_accounts')
            ->where('floating_wallet <=', $securityDeposite)
            ->where_in('user_id', $userIds)
            ->get()
            ->result();

        $finalUserIds = array();
        //print_r($userAccData);die();
        foreach ($userAccData as $val) {
            $finalAmount = $val->floating_wallet + $amount;

            if ($finalAmount <= $securityDeposite) {
                $finalUserIds[]['user_id'] = $val->user_id;
            }
        }
        return $finalUserIds;
    }
}
