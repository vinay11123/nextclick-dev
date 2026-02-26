<?php

class News_category_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
       
            $this->table = "news_categories";
            $this->primary_key = "id";
            $this->foreign_key = "category";
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
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
        
    }
    public function relations() {
//         $this->has_many['news_categories'] = array(
//             'foreign_model' => 'news_model',
//             'foreign_table' => 'local_news',
//             'local_key' => 'id',
//             'foreign_key' => 'category',
//             'get_relate' => FALSE
//         );
    }
    public function forms(){
        $this->rules = array(
            array(
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required',
                'errors' =>  array(
                    'required' => 'You must provide a %s.',
                    'min_length' => 'you need to give minimum 5 characters'
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

