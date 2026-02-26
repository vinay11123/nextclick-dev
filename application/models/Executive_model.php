<?php

class Executive_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insert_data_executive()
    {
    }

    public function get_executive_list()
    {

        $this->db->select('e.id, e.first_name, e.last_name, e.email, e.phone, e.unique_id, e.created_at, e.status, et.executive_type, es.name as state_name, ed.name as district_name, ec.name as constitution_name, (SELECT COUNT(*) FROM vendors_list v WHERE v.executive_user_id = e.id) AS vendor_count, (SELECT COUNT(*) FROM users du JOIN delivery_boy_address dba ON du.id = dba.executive_user_id WHERE dba.executive_user_id = e.id) AS delivery_captain_count, (SELECT COUNT(*) FROM users us WHERE us.executive_user_id = e.id) AS user_count');
        $this->db->from('users AS e');
        $this->db->join('executive_address as ead', 'ead.user_id = e.id', 'left');
        $this->db->join('states as es', 'es.id = ead.state', 'left');
        $this->db->join('districts as ed', 'ed.id = ead.district', 'left');
        $this->db->join('constituencies as ec', 'ec.id = ead.constituency', 'left');
        $this->db->join('executive_type as et', 'et.id = ead.executive_type_id', 'left');
        $this->db->join('users_groups AS ug', 'e.id = ug.user_id', 'inner');
        $this->db->join('groups AS g', 'ug.group_id = g.id AND g.name = "executive"', 'inner');
        $this->db->order_by('e.id', 'DESC');

        $query = $this->db->get();

        if (!$query) {
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            return $query->result_array();
        }
    }

    public function get_executive_delivery_captain_list($type = '', $executive_id = '')
    {

        if ($type == 'target_achieved') {

            $this->db->select("dad.*, u.phone as captain_phone, CONCAT_WS(' ', u.first_name, u.last_name) as captain_name, CONCAT_WS(' ', exec.first_name, exec.last_name) as executive_name, et.executive_type");
            $this->db->from('users u');
            $this->db->join('delivery_boy_address as dad', 'dad.user_id = u.id');
            $this->db->join('users as exec', 'dad.executive_user_id = exec.id');
            $this->db->join('executive_address as ead', 'ead.user_id = exec.id', 'left');
            $this->db->join('executive_type as et', 'et.id = ead.executive_type_id', 'left');

            $this->db->where('dad.executive_user_id IS NOT NULL');
            $this->db->where('u.delivery_partner_approval_status', '1');
            $this->db->where('dad.target_given_count IS NOT NULL');
            $this->db->where('dad.target_achieved_count IS NOT NULL');
            $this->db->where('dad.is_target_achieved', '1');
            $this->db->where('dad.target_achieved_at IS NOT NULL');
            if (!empty($executive_id)) {
                $this->db->where('dad.executive_user_id', $executive_id);
            }
            $this->db->order_by('dad.created_at', 'DESC');
        } else {

            $this->db->select("dad.*, u.phone as captain_phone, CONCAT_WS(' ', u.first_name, u.last_name) as captain_name, CONCAT_WS(' ', exec.first_name, exec.last_name) as executive_name, et.executive_type");
            $this->db->from('users u');
            $this->db->join('delivery_boy_address as dad', 'dad.user_id = u.id');
            $this->db->join('users as exec', 'dad.executive_user_id = exec.id');
            $this->db->join('executive_address as ead', 'ead.user_id = exec.id', 'left');
            $this->db->join('executive_type as et', 'et.id = ead.executive_type_id', 'left');

            if ($type == 'approved') {
                $this->db->where('u.delivery_partner_approval_status', '1');
                $this->db->where('dad.target_given_count IS NOT NULL');
                if (!empty($executive_id)) {
                    $this->db->where('dad.executive_user_id', $executive_id);
                }
            } else if ($type == 'pending') {
                $this->db->where('u.delivery_partner_approval_status', '0');
                if (!empty($executive_id)) {
                    $this->db->where('dad.executive_user_id', $executive_id);
                }
            } else if ($type == 'target_not_achieved') {
                $this->db->where('u.delivery_partner_approval_status', '1');
                $this->db->where('dad.target_given_count IS NOT NULL');
                $this->db->where('dad.is_target_achieved', '0');
                if (!empty($executive_id)) {
                    $this->db->where('dad.executive_user_id', $executive_id);
                }
            } else {
                if (!empty($executive_id)) {
                    $this->db->where('dad.executive_user_id', $executive_id);
                } else {
                    $this->db->where('dad.executive_user_id IS NOT NULL');
                }
            }

            $this->db->order_by('dad.created_at', 'DESC');
        }

        $query = $this->db->get();

        $count = $query->num_rows();

        if (!$query) {
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            return array(
                'count' => $count,
                'captain_details' => $query->result()
            );
        }
    }



    public function get_executive_wallet_amount($executive_id)
    {
        $this->db->select("sum(`executive_referral_amount`) total_amount,executive_user_id");
        $this->db->from('executive_earning_view');
        $this->db->where('executive_user_id', $executive_id);
        $this->db->order_by('prority', 'ASC');

        $query = $this->db->get();

        if (!$query) {
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            return $query->result();
        }
    }

    public function get_bank_details($executive_id)
    {
        $this->db->select("ebd.*,b.name as bank_name");
        $this->db->from('executive_bank_details ebd');
        $this->db->join('banks AS b', 'b.id = ebd.bank_id');
        $this->db->where('ebd.executive_id', $executive_id);
        $this->db->order_by('ebd.created_at', 'DESC');

        $query = $this->db->get();

        if (!$query) {
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            return $query->result();
        }
    }

    public function check_bank_details($executive_id, $ifsc, $ac_number)
    {
        $this->db->select("*");
        $this->db->from('executive_bank_details');
        $this->db->where('executive_id', $executive_id);
        $this->db->where('ifsc', $ifsc);
        $this->db->where('ac_number', $ac_number);
        $this->db->order_by('created_at', 'DESC');

        $query = $this->db->get();

        if (!$query) {
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            $count = $query->num_rows();
            return $count > 0;
        }
    }

    public function set_primary_account($executive_id, $ac_number, $ifsc)
    {
        $this->db->where('executive_id', $executive_id);
        $this->db->update('executive_bank_details', ['is_primary' => 0]);

        $this->db->where('executive_id', $executive_id);
        $this->db->where('ac_number', $ac_number);
        $this->db->where('ifsc', $ifsc);
        $this->db->update('executive_bank_details', ['is_primary' => 1]);

        if ($this->db->affected_rows() == 0) {
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        }

        return true;
    }

    public function get_wallet_details($executive_id = '')
    {
        $this->db->select("SUM(CASE WHEN user_type = 'vendor' THEN executive_referral_amount ELSE 0 END) AS total_vendor_amount");
        $this->db->select("SUM(CASE WHEN user_type = 'user' THEN executive_referral_amount ELSE 0 END) AS total_user_amount");
        $this->db->select("SUM(CASE WHEN user_type = 'delivery_boy' THEN executive_referral_amount ELSE 0 END) AS total_delivery_boy_amount");
        $this->db->from('executive_earning_view');
        if (!empty($executive_id)) {
            $this->db->where('executive_user_id', $executive_id);
        }

        $query = $this->db->get();
        $row = $query->row();

        $total_vendor_amount = $row->total_vendor_amount;
        $total_user_amount = $row->total_user_amount;
        $total_delivery_boy_amount = $row->total_delivery_boy_amount;

        $total_all_amount = $total_vendor_amount + $total_user_amount + $total_delivery_boy_amount;


        return array(
            'total_vendor_amount' => $total_vendor_amount,
            'total_user_amount' => $total_user_amount,
            'total_delivery_boy_amount' => $total_delivery_boy_amount,
            'total_all_amount' => isset($total_all_amount) ? $total_all_amount : null // Return total_all_amount if set
        );
    }



    public function get_transaction_details($executive_id = '', $role = '', $type = '', $from_date = '', $to_date = '')
    {
        $this->db->select("ev.*, CONCAT_WS(' ', u.first_name, u.last_name) AS user_name,CONCAT_WS(' ', ue.first_name, ue.last_name) AS executive_name, u.phone, v.business_name as vendor_business_name");
        $this->db->from('executive_earning_view ev');
        $this->db->join('users AS u', 'u.id = ev.user_id');
        $this->db->join('users AS ue', 'ue.id = ev.executive_user_id');
        $this->db->join('vendors_list AS v', 'v.vendor_user_id = ev.user_id', 'left');
        if (!empty($executive_id)) {
            $this->db->where('ev.executive_user_id', $executive_id);
        }
        if (!empty($role)) {
            $this->db->where('ev.user_type', $role);
        }
        if (!empty($type)) {
            $this->db->where('ev.payment_type', $type);
        }
        if (!empty($from_date) && !empty($to_date)) {
            $from_date_formatted = date('Y-m-d', strtotime($from_date));
            $to_date_formatted = date('Y-m-d', strtotime($to_date));
            $to_date_adjusted = date('Y-m-d', strtotime($to_date . ' +1 day'));

            $this->db->where('ev.date_time >=', $from_date_formatted);
            $this->db->where('ev.date_time <', $to_date_adjusted);
        } else if (!empty($from_date)) {
            $from_date_formatted = date('Y-m-d', strtotime($from_date));
            $this->db->where('ev.date_time >=', $from_date_formatted);
        }
        $this->db->order_by('ev.date_time', 'DESC');

        $query = $this->db->get();

        return $query->result();
    }




    public function get_executive_details($eye_id) {
        $this->db->select('e.id, e.first_name, e.last_name, e.email, e.phone, e.permanent_address, eb.aadhar as eaadhar_number, e.status, e.referral_code, ed.name AS district_name, ec.name AS constituency_name, es.name AS state_name, et.executive_type')
                 ->from('users AS e')
                 ->join('users_groups AS ug', 'e.id = ug.user_id', 'inner')
                 ->join('groups AS g', 'ug.group_id = g.id AND g.name = \'executive\'', 'inner')
                 ->join('executive_biometrics AS eb', 'eb.user_id = e.id', 'left')
                 ->join('executive_address AS ead', 'ead.user_id = e.id', 'left')
                 ->join('states AS es', 'es.id = ead.state', 'left')
                 ->join('districts AS ed', 'ed.id = ead.district', 'left')
                 ->join('constituencies AS ec', 'ec.id = ead.constituency', 'left')
                 ->join('executive_type AS et', 'et.id = ead.executive_type_id', 'left')
                 ->where('e.id', $eye_id)
                 ->order_by('e.id', 'DESC');

        $query = $this->db->get();
        return $query->row_array();

    }

    public function get_executive_bank_details($executive_id)
    {
        $this->db->select("ebd.*");
        $this->db->from('executive_bank_details ebd');
        $this->db->where('ebd.executive_id', $executive_id);
        $this->db->order_by('ebd.created_at', 'DESC');

        $query = $this->db->get();

        if (!$query) {
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            return $query->result();
        }
    }
}


