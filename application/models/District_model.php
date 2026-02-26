<?php

class District_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'districts';
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
    private function _config()
    {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }

    private function _relations()
    {
        $this->has_many['constituenceis'] = array(
            'foreign_model' => 'Constituency_model',
            'foreign_table' => 'constituencies',
            'local_key' => 'id',
            'foreign_key' => 'district_id'
        );
    }

    private function _form()
    {
        $this->rules = array(
            array(
                'field' => 'state_id',
                'lable' => 'State Id',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please select state'
                )
            ),
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim|required',
            ),
        );
    }

    public function is_district_name_exists($name, $state_id, $id = '')
    {
        $this->db->where('name', trim($name));
        $this->db->where('state_id', trim($state_id));
        if (!empty($id)) {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->get('districts');
        return $query->num_rows() > 0;
    }
}

