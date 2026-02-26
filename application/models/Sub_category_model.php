 <?php

class Sub_category_model extends MY_Model
{
    public $rules;
    public $user_id =1;
    public function __construct()
    {
        parent::__construct();
        $this->table="sub_categories";
        $this->primary_key="id";
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
   
    public function _config(){
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
        
    }
    public function _relations()
    {

        $this->has_one['users'] = array('user_model', 'id', 'unique_id');
        $this->has_many['menus'] = array(
            'foreign_model' => 'Food_menu_model',
            'foreign_table' => 'food_menu',
            'local_key' => 'id',
            'foreign_key' => 'sub_cat_id',
            'get_relate' => FALSE
        );
        
    }
    public function _form(){
        $this->rules['sub_category'] = array(
            array(
                'field'=>'cat_id',
                'label'=>'Category Id',
                'rules'=>'trim|required',
                'errors'=>array(
                    'required'=>'Please select category'
                )
            ), 
            array(
                'field'=>'name',
                'label'=>'Name',
                'rules'=>'trim|required|regex_match[/^[a-zA-Z]/]',
                'errors'=>array(
                    'regex_match' => 'You must provide characters only',
                    'required' => 'You must provide a %s.',
                )
            ),
            array(
                'field'=>'desc',
                'label'=>'Description',
                'rules'=>'trim|required|max_length[200]',
                'erors'=>array(
                    'max_length'=>'You can give maximum 200 characters'
                )
                
            )
        );
        
        $this->rules['shop_by_category'] = array(
            array(
                'field'=>'name',
                'label'=>'Name',
                'rules'=>'trim|required|regex_match[/^[a-zA-Z]/]',
                'errors'=>array(
                    'regex_match' => 'You must provide characters only',
                    'required' => 'You must provide a %s.',
                )
            ),
        );
        $this->rules['floating_save_validation'] = array(
            
            
            array(
                'field'=>'amount',
                'label'=>'Amount',
                'rules'=>'trim|required',
                'errors'=>array(
                    'required'=>'Please send Amount'
                )
            ),
            array(
                'field'=>'txn_id',
                'label'=>'Transation Id',
                'rules'=>'trim|required',
                'errors'=>array(
                    'required'=>'Please send Transation Id'
                )
            ),
        );
    }
	
		public function get_users($limit = NULL, $offset = NULL, $search = NULL)
	{
		if($search!= null){
			$this->db->like('sub_categories.name',$search);
		}
		$this->db->where('sub_categories.deleted_at',NULL);
		$this->db->order_by('`sub_categories`.id', 'DESC');
		$this->db->limit($limit, $offset);
		$rs     = $this->db->get($this->table);
		return   $rs->result_array();
	}
	
	public function users_count($search = NULL)
	{
		if($search!= null){
			$this->db->like('sub_categories.name',$search);
		}
		return $this->db->count_all_results($this->table);
	}
	
	
}
?>