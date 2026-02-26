<?php

class Categoriesbrands_model extends MY_Model
{

    public $rules;
    public function __construct()
    {
        parent::__construct();
       
       
            $this->table="categories_brands";
            $this->primary_key="id";
            
            $this->before_create[] = '_add_created_by';
            $this->before_update[] = '_add_updated_by';
            $this->config();
            $this->forms();
            $this->relations();
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
    public function config(){
        $this->timestamps = FALSE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
        
    }
    public function relations() {
       
    }
    public function forms(){
        $this->rules = array(
            array(
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required',
                'errors' =>  array(
                    'required' => 'You must provide a %s.',
                   
                )
            ),
            array(
                'field' => 'desc',
                'lable' => 'Description',
                'rules' => 'trim|required|max_length[200]',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            )
        );
    }
}

