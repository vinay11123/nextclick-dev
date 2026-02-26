<?php

class Vehicletype_model extends MY_Model
{
    public $rules , $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vehicle_type';
        $this->primary_key = 'id';
        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';
        
     
       $this->_config();
       $this->_form();
       $this->_relations();
    }
    protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id()? $this->ion_auth->get_user_id(): $this->user_id; //add user_id
        return $data;
    }

    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id()? $this->ion_auth->get_user_id(): $this->user_id; //add user_id
        return $data;
    }
    
    public function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
        
    }
    
     public function _relations(){
        $this->has_one['vendor'] = array('vendor_list_model', 'id', 'list_id');
    } 
    
    public function _form(){
        
        $this->rules = array(
           
            array(
                'field' => 'vehiclename',
                'lable' => 'vehicle name',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must Select a %s.'
                )
            ),
            array(
                'field' => 'mincapecity',
                'lable' => 'minimum capecity',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must Select a %s.'
                )
            ),
            array(
                'field' => 'maxcapecity',
                'lable' => 'maximum capecity',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must Select a %s.'
                )
            )
        );

       
    }
}

