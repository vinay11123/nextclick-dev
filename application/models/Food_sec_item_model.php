 <?php

  
class Food_sec_item_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table="food_sec_item";
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
        $this->timestamps = FALSE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = FALSE;
        
    }
    public function _relations()
    {  
        $this->has_one['menu'] = array('Food_menu_model','id','menu_id');
        $this->has_one['item'] = array('Food_item_model','id','item_id');
        $this->has_one['sec'] = array('Food_section_model','id','sec_id');
    }


    public function _form(){
        $this->rules = array(
            array(
                'field'=>'menu_id',
                'label'=>'Menu Id',
                'rules'=>'trim|required',
                'errors'=>array(
                    'required'=>'Please select Menu'
                )
            ),
            array(
                'field'=>'item_id',
                'label'=>'Item Id',
                'rules'=>'trim|required',
                'errors'=>array(
                    'required'=>'Please select Item'
                )
            ),
            array(
                'field'=>'sec_id',
                'label'=>'Section Id',
                'rules'=>'trim|required',
                'errors'=>array(
                    'required'=>'Please select Section'
                )
            ),
            array(
                'field'=>'name',
                'label'=>'Name',
                'rules'=>'trim|required|min_length[3]',
                'errors'=>array(
                    'min_length'=>'Please give minimum 3 characters'
                )
            ),
            array(
                'field'=>'price',
                'label'=>'Price',
                'rules'=>'trim|required',
                'errors'=>array(
                    'required'=>'Please Give Price'
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
    }
}
?>