<?php

class News_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'news';
        $this->primary_key = 'id';
      //  $this->before_create[] = '_add_created_by';
     //   $this->before_update[] = '_add_updated_by';
        
     
       $this->_config();
       $this->_form();
       $this->_relations();
    }
   /* protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    }
    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    } */
    public function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
        
    }
    
    public function _relations(){
        $this->has_one = 'News_category_model';
    }
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'title',
                'lable' => 'Title',
                'rules' => 'trim',
            ),
            array(
                'field' => 'category',
                'lable' => 'Categoty',
                'rules' => 'trim',
            ),
            array(
                'field' => 'news_date',
                'lable' => 'News Date',
                'rules' => 'trim',
            ),
            array(
                'field' => 'news',
                'lable' => 'News',
                'rules' => 'trim',
            ),
            array(
                'field' => 'url',
                'lable' => 'Video Link',
                'rules' => 'trim',
            )
        );
    }
    
    public function all($limit = 10, $offset = 0, $cat_id = 1)
    {
        $cache_name =  $limit . $offset. $cat_id;
        $this->set_cache($cache_name); //just to set cache_name using MY_model
        $result     = $this->_get_from_cache(); //MY_model
        
        if ( ! (isset($result) && $result !== FALSE))
        {
            $this->_query_all($cat_id);
            $this->db->order_by('id', 'DESC');
            $this->db->order_by('title', 'ASC');
            $this->db->order_by('created_at', 'DESC');
            $this->db->order_by('updated_at', 'DESC');
            if ( $limit != NULL && $offset != NULL)
            {
                $this->db->limit($limit, $offset);
            }
            $rs     = $this->db->get($this->table);
            $result = $rs->custom_result_object('News_row');
            $this->_write_to_cache($result); //MY_model
        }
        //print_array($this->db->last_query());
        
        $this->db->reset_query($cat_id);
        
        $this->_query_all();
        $count = $this->db->count_all_results($this->table);
        
        return  array(
            'result' => $result,
            'count'  => $count
        );
    }
    
    private function _query_all($cat_id = 0)
    {
        
        $this->load->model(array('news_category_model'));
        
        $category_table       = '`' . $this->news_category_model->table . '`';
        $category_primary_key = '`' . $this->news_category_model->primary_key . '`';
        $category_foreign_key = '`' . $this->news_category_model->foreign_key . '`';
        
        $primary_key = '`' . $this->primary_key . '`';
        $table       = '`' . $this->table . '`';
        
        $str_select_news = '';
        foreach (array('created_at', 'updated_at', 'created_user_id', 'updated_user_id', 'id', 'title', 'category', 'video_link', 'news', 'news_date') as $v)
        {
            $str_select_news .= "$table.`$v`,";
        }
        
        $this->db->select($str_select_news);
        $this->db->join($category_table, "$category_table.$primary_key=$table.$category_foreign_key");
        
        if (! empty($cat_id))
        {
            $this->db->where("$category_table.$category_primary_key=", $cat_id);
        } 
        
        //$this->db->where("$table.`news_date`", date('Y-m-d'));
        $this->db->where("$table.`status`=", '1');
        return $this;
    }
    
}

class News_row
{
    public $id;
    public $title;
    public $category;
    public $video_link;
    public $news;
    public $news_date;
    public $created_at;
    public $updated_at;
    public $created_user_id;
    public $updated_user_id;
    
}