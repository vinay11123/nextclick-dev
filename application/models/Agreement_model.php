<?php
class Agreement_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->table = 'agreements';
        $this->primary_key = 'id';

        $this->_config();
    }

    public function _config()
    {
        $this->timestamps = TRUE;
        $this->delete_cache_on_save = TRUE;
    }

    public function get_agreement_details()
    {

        $this->db->select('*');
        $this->db->from('agreements');
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get();
        if (!$query) {
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
        } else {
            return $query->result();
        }
    }

    public function insert_agreement()
    {
        $app_details_id = $this->input->post('app_id');
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $created_user_id = $this->ion_auth->get_user_id();

        // Check if a record already exists with the provided app_details_id
        $this->db->where('app_details_id', $app_details_id);
        $query = $this->db->get('agreements');

        if ($query->num_rows() > 0) {
            // If a record exists, update all existing rows with the same app_details_id to set their status to 0
            $this->db->where('app_details_id', $app_details_id);
            $this->db->update('agreements', array('status' => 0));
        }

        $data = array(
            'app_details_id' => $app_details_id,
            'title' => $title,
            'description' => $description,
            'created_user_id' => $created_user_id,
            'status' => 1
        );

        $this->db->insert('agreements', $data);

        if ($this->db->affected_rows() == 0) {
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            return true;
        }

    }



    public function uniqueCheck($id, $title)
    {
        $this->db->select('*');
        $this->db->from('agreements');
        $this->db->where('app_details_id', $id);
        $this->db->where('title', $title);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }



}