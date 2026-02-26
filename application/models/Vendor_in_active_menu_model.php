 <?php

 class Vendor_in_active_menu_model extends MY_Model
{

    public $rules, $user_id;

    public function __construct()
    {
        parent::__construct();
        $this->table = "vendor_in_active_menus";
        $this->primary_key = "id";
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

    public function _config()
    {
        $this->timestamps = TRUE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
        
        $this->pagination_delimiters = array('<li class="page-item">','</li>');
        $this->pagination_arrows = array('&lt;','&gt;');
    }

    public function _relations()
    {
        
        $this->has_one['menu'] = array(
            'Food_menu_model',
            'id',
            'menu_id'
        );
        
        $this->has_one['sub_category'] = array(
            'Sub_category_model',
            'id',
            'sub_cat_id'
        );
        
    }

    public function _form()
    {
        $this->rules = array(
            array(
                'field' => 'menu_id',
                'label' => 'Menu',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please select Menu'
                )
            ),
            array(
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required|min_length[3]',
                'errors' => array(
                    'min_length' => 'Please give minimum 3 characters'
                )
            )
        );
    }
}
?>