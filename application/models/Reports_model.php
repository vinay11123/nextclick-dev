<?php
class Reports_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->table = 'vendors_list';
        $this->primary_key = 'id';

        $this->_config();
    }

    private function _config()
    {
        $this->timestamps = TRUE;
        $this->delete_cache_on_save = TRUE;
    }

    public function get_vendor_reports()
    {
        $this->db->select("v.*, a.title, CONCAT_WS(', ', vad.location, vad.line1, st.name, dst.name, vad.zip_code) AS vendor_address");
        $this->db->from('vendors_list v');
        $this->db->join('users u', 'u.id = v.vendor_user_id');
        $this->db->join('agreements a', 'a.id = v.agreement_id');
        $this->db->join('vendor_address as vad', 'vad.list_id = v.id');
        $this->db->join('states as st', 'vad.state = st.id');
        $this->db->join('districts as dst', 'vad.district = dst.id');
        $this->db->where('agreement_id IS NOT NULL');
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get();


        if (!$query) {
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            return $query->result();
        }
    }

    public function get_vendor_unaccepted_reports()
    {
        $this->db->select("v.*, CONCAT_WS(', ', vad.location, vad.line1, st.name, dst.name, vad.zip_code) AS vendor_address");
        $this->db->from('vendors_list v');
        $this->db->join('users u', 'u.id = v.vendor_user_id');
        $this->db->join('vendor_address as vad', 'vad.list_id = v.id');
        $this->db->join('states as st', 'vad.state = st.id');
        $this->db->join('districts as dst', 'vad.district = dst.id');
        $this->db->where('agreement_id', NULL);
        $this->db->order_by('created_at', 'DESC');
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