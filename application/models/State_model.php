<?php

class State_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'states';
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
        $this->has_many['districts'] = array(
            'foreign_model' => 'District_model',
            'foreign_table' => 'districts',
            'local_key' => 'id',
            'foreign_key' => 'state_id'
        );
    }

    private function _form()
    {
        $this->rules = array(
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim|required',
                'errors' => array(
                    'min_length' => 'Please give at least 5 characters'
                )
            ),
        );
    }

    public function is_state_name_exists($name, $id = '')
    {
        $this->db->where('name', trim($name));
        if (!empty($id)) {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->get('states');
        return $query->num_rows() > 0;
    }


}

