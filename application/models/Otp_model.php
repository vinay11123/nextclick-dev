<?php

class Otp_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'otp_expiry';
        $this->primary_key = 'id';

        $this->_config();
        $this->_relations();
    }

    private function _config()
    {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }

    private function _relations()
    {
        $this->has_one = array('User_model', 'id', 'user_id');
    }

    public function verify_otp($mobile, $otp)
    {
        error_reporting(E_ERROR | E_WARNING | E_PARSE);

        $otp_data = $this->db->where('user_id', $mobile['id'])->get($this->table)->row();
        if (!empty($otp_data)) {
            $this->db->select('*');
            $this->db->from($this->table);
            if ($otp_data->updated_at == null)
                $this->db->where(['is_expired' => 0, 'otp' => $otp, 'user_id' => $mobile['id'], 'DATE_ADD(created_at,INTERVAL 30 HOUR) >' => date('Y-m-d h:i:s')], FALSE);
            else
                $this->db->where(['is_expired' => 0, 'otp' => $otp,  'user_id' => $mobile['id'], 'DATE_ADD(updated_at,INTERVAL 30 HOUR) >' => date('Y-m-d h:i:s')], FALSE);

            return $this->db->get()->row();
        } else {
            return FALSE;
        }
    }

    public function validate($mobile, $otp)
    {
        $record =$this->where([
            'is_expired' => 0, 
            'otp' => $otp, 
            'mobile' => $mobile, 'DATE_ADD(created_at,INTERVAL 30 HOUR) >' => date('Y-m-d h:i:s')
        ])->get();
        if ($record && $record['id']) {
            $this->update(['is_expired' => 1], $record['id']);
            return [
                'success' => true
            ];
        } else {
            return [
                'success' => false
            ];
        }
    }
}
