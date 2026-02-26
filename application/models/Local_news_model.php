<?php

class Local_news_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'local_news';
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
    public function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
        
    }
    
    public function _relations(){
       // $this->has_one = 'News_category_model';
        $this->has_many['news_categories'] = array(
            'foreign_model' => 'news_category_model',
            'foreign_table' => 'news_categories',
            'local_key' => 'id',
            'foreign_key' => 'id',
            'get_relate' => FALSE
        );
        
        $this->has_one['location'] = array('Location_model', 'id', 'location_id');
        
    }
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'title',
                'lable' => 'Title',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'category',
                'lable' => 'Categoty',
                'rules' => 'trim',
            ),
           
            array(
                'field' => 'news',
                'lable' => 'news',
                'rules' => 'trim',
            ),
            array(
                'field' => 'video_link',
                'lable' => 'Video Link',
                'rules' => 'trim',
            )
        );
    }
    public function all($cat_id = NULL, $lat = NULL, $long = NULL){
        $this->_query_all($cat_id, $lat, $long);
        $this->db->order_by('id', 'DESC');
        $this->db->order_by('title', 'ASC');
        $this->db->order_by('created_at', 'DESC');
        $this->db->order_by('updated_at', 'DESC');
        $rs     = $this->db->get($this->table);
        $result = $rs->result_array();
        return  array(
            "result" => $result,
        );
    }
    public function _query_all($cat_id = NULL, $lat = NULL, $long = NULL){
        $this->load->model(array('news_category_model','location_model'));
        
        $location_table       = '`' . $this->location_model->table . '`';
        $location_primary_key = '`' . $this->location_model->primary_key . '`';
        $location_foreign_key = '`' . 'location_id' . '`';
        
        $category_table       = '`' . $this->news_category_model->table . '`';
        $category_primary_key = '`' . $this->news_category_model->primary_key . '`';
        $category_foreign_key = '`' . 'category' . '`';
        
        $primary_key = '`' . $this->primary_key . '`';
        $table       = '`' . $this->table . '`';
        
        $str_select_news = '';
        foreach (array('id','title','video_link', 'news', 'user_id', 'created_at','updated_at') as $v)
        {
            $str_select_news .= "$table.`$v`,";
        }
        $this->db->select($str_select_news."$category_table.`name` as category_name,"."$location_table.`address`,");
        $this->db->join($category_table, "$category_table.$primary_key=$table.$category_foreign_key", 'left');
        $this->db->join($location_table,"$location_table.$primary_key=$table.$location_foreign_key", 'left');
        
        if ($cat_id)
        {
            $this->db->where("$category_table.$category_primary_key=", $cat_id);
        }
        if(! empty($lat) && ! empty($long)){
            $locations = $this->db->query("SELECT id, ( 3959 * acos( cos( radians($lat) ) * cos( radians( locations.latitude ) ) * cos( radians( locations.longitude ) - radians($long) ) + sin( radians($lat) ) * sin(radians(locations.latitude)) ) ) AS distance FROM locations HAVING distance < 3.16 ORDER BY distance")->result_array();
            $this->db->where_in("$table.`location_id`", (empty(array_column($locations, 'id')))? 0: array_column($locations, 'id'));
        }
        $this->db->where("$table.`status`=", '2');
        $this->db->where("$table.`deleted_at` =", NULL);
        return $this;
    }
}

class Local_news_row
{
    public $id;
    public $title;
    public $category;
    public $video_link;
    public $news;
  //  public $news_date;
    public $created_at;
    public $updated_at;
    public $created_user_id;
    public $updated_user_id;
    
}