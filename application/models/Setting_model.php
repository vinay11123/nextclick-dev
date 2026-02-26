<?php

class Setting_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'settings';
        $this->primary_key = 'id';

        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';

        $this->_config();
        $this->_form();
        $this->_relations();
    }
    protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    }
    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    }
    public function _config()
    {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }

    public function _relations()
    {

    }

    public function _form()
    {
        $this->rules['site'] = array(
            array(
                'lable' => 'system name',
                'field' => 'system_name',
                'rules' => 'trim|required'
            ),
            array(
                'lable' => 'system title',
                'field' => 'system_title',
                'rules' => 'trim|required'
            ),
            array(
                'lable' => 'mobile',
                'field' => 'mobile',
                'rules' => 'trim|required|min_length[10]|max_length[10]|regex_match[/^[0-9]{10}$/]',
                'errors' => array(
                    'min_length' => 'Please give minimum 10 digits number',
                    'max_length' => 'You can give maximum 10 digits number',
                    'regex_match' => 'Please give a valid number',
                )
            ),
            array(
                'lable' => 'address',
                'field' => 'address',
                'rules' => 'trim|required'
            ),
            array(
                'lable' => 'facebook',
                'field' => 'facebook',
                'rules' => 'trim|callback_valid_url',
                'errors' => array(
                    'callback_valid_url' => 'Please give valid url'
                )
            ),
            array(
                'lable' => 'twiter',
                'field' => 'twiter',
                'rules' => 'trim|callback_valid_url',
                'errors' => array(
                    'callback_valid_url' => 'Please give valid url'
                )
            ),
            array(
                'lable' => 'youtube',
                'field' => 'youtube',
                'rules' => 'trim|callback_valid_url',
                'errors' => array(
                    'callback_valid_url' => 'Please give valid url'
                )
            ),
            array(
                'lable' => 'skype',
                'field' => 'skype',
                'rules' => 'trim|callback_valid_url',
                'errors' => array(
                    'callback_valid_url' => 'Please give valid url'
                )
            ),
            array(
                'lable' => 'pinterest',
                'field' => 'pinterest',
                'rules' => 'trim|callback_valid_url',
                'errors' => array(
                    'callback_valid_url' => 'Please give valid url'
                )
            ),

            array(
                'lable' => 'max_order_weight',
                'field' => 'max_order_weight',
                'rules' => 'trim|required',

            )
        );

        $this->rules['sms'] = array(
            array(
                'lable' => 'sms_username',
                'field' => 'sms_username',
                'rules' => 'trim|required'
            ),
            array(
                'lable' => 'Sender',
                'field' => 'sms_sender',
                'rules' => 'trim|required'
            ),
            array(
                'lable' => 'Hash Key',
                'field' => 'sms_hash',
                'rules' => 'trim|required'
            )
        );

        $this->rules['smtp'] = array(
            array(
                'label' => 'SMTP Port',
                'field' => 'smtp_port',
                'rules' => 'trim|required'
            ),
            array(
                'label' => 'SMTP Host',
                'field' => 'smtp_host',
                'rules' => 'trim|required'
            ),
            array(
                'label' => 'SMTP Username',
                'field' => 'smtp_username',
                'rules' => 'trim|required'
            ),
            array(
                'label' => 'SMTP Password',
                'field' => 'smtp_password',
                'rules' => 'trim|required'
            )

        );

        $this->rules['news'] = array(
            array(
                'label' => 'Pay per news',
                'field' => 'pay_per_news',
                'rules' => 'trim|required'
            )
        );

        $this->rules['bank'] = array(
            array(
                'label' => 'UIP ID',
                'field' => 'bank_upi_id',
                'rules' => 'trim|required'
            ),
            array(
                'label' => 'Bank',
                'field' => 'bank_name',
                'rules' => 'trim|required'
            ),
            array(
                'label' => 'Account No.',
                'field' => 'bank_account_no',
                'rules' => 'trim|required'
            ),
            array(
                'label' => 'IFSC Code',
                'field' => 'bank_ifsc_code',
                'rules' => 'trim|required'
            )

        );

        $this->rules['referral_amount'] = array(
            array(
                'label' => 'User referral amount',
                'field' => 'user_referral_amount',
                'rules' => 'trim|required'
            ),
            array(
                'label' => 'Vendor referral amount',
                'field' => 'vendor_referral_amount',
                'rules' => 'trim|required'
            ),
            array(
                'label' => 'Delivery boy referral amount',
                'field' => 'delivery_boy_referral_amount',
                'rules' => 'trim|required'
            ),
            array(
                'label' => 'Delivery boy target order count',
                'field' => 'delivery_boy_target_order_count',
                'rules' => 'trim|required'
            ),
            array(
                'label' => 'Vendor to User Referral Amount',
                'field' => 'vendor_touser_referral_amount',
                'rules' => 'trim|required'
            )            

        );
    }

    public function get_user_signature()
    {
        $query = $this->db->select('value')
            ->from('settings')
            ->where('key', 'digital_signature')
            ->get();

        if ($query && $query->num_rows() > 0) {
            $row = $query->row();
            $user_signature = $row->value;

            return $user_signature;
        } else {
            return NULL;
        }
    }

    public function get_executive_referral_video()
    {
        $query = $this->db->select('value')
            ->from('settings')
            ->where('key', 'executive_referral_video_id')
            ->get();

        if ($query && $query->num_rows() > 0) {
            $row = $query->row();
            $executive_referral_video = $row->value;

            return $executive_referral_video;
        } else {
            return NULL;
        }
    }

}

