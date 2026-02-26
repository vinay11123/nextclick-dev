 <?php

 class Promotion_banner_model extends MY_Model
{

    public $rules, $user_id;
    
    public $static_columns = [
        'owner' => [
            '1' => 'Nextclick',
            '2' => 'Others'
        ],
        'accessibility' => [
            '1' => 'Public',
            '2' => 'Private'
        ],
        'status' => [
            '0' => 'disapproved',
            '1' => 'Approved',
            '2' => 'Published',
            '3' => 'Unpublished',
        ]
    ];

    public function __construct()
    {
        parent::__construct();
        $this->table = "promotion_banners";
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
        
    }

    public function _relations()
    {
        $this->has_one['position'] = array(
            'Promotion_banner_position_model',
            'id',
            'promotion_banner_position_id'
        );
        
        $this->has_one['content_type'] = array(
            'promotion_banner_content_type_model',
            'id',
            'content_type'
        );
        
        $this->has_many['promotion_products'] = array(
            'foreign_model' => 'Promotion_banner_vendor_product_model',
            'foreign_table' => 'promotion_banners_vendor_products',
            'local_key' => 'id',
            'foreign_key' => 'promotion_banner_id',
            'get_relate' => FALSE
        );
        
        $this->has_many['offer_products'] = array(
            'foreign_model' => 'Promotion_banner_vendor_offer_product_model',
            'foreign_table' => 'promotion_banners_vendor_offer_products',
            'local_key' => 'id',
            'foreign_key' => 'promotion_banner_id',
            'get_relate' => FALSE
        );

        $this->has_many_pivot['promotion_banners_shop_by_categories'] = array(
            'foreign_model' => 'Sub_category_model',
            'pivot_table' => 'promotion_banners_shop_by_categories',
            'local_key' => 'id',
            'pivot_local_key' => 'promotion_banner_id',
            'pivot_foreign_key' => 'sub_cat_id',
            'foreign_key' => 'id',
            'get_relate' => FALSE
        );
        
        $this->has_many['joined_users'] = array(
            'foreign_model' => 'Promotion_banner_joined_user_model',
            'foreign_table' => 'promotion_banners_joined_users',
            'local_key' => 'id',
            'foreign_key' => 'promotion_banner_id',
            'get_relate' => FALSE
        );

        $this->has_many['joined_promotion_banner_payments'] = array(
            'foreign_model' => 'Promotion_banner_payment_model',
            'foreign_table' => 'promotion_banner_payments',
            'local_key' => 'id',
            'foreign_key' => 'promotion_banner_id',
            'get_relate' => FALSE
        );
        
        $this->has_one['category'] = array('Category_model', 'id', 'cat_id');
        $this->has_one['sub_category'] = array('Sub_category_model', 'id', 'sub_cat_id');
        $this->has_one['constituency'] = array('Constituency_model', 'id', 'constituency_id');
        $this->has_one['vendor_list'] = array('Vendor_list_model', 'vendor_user_id', 'created_user_id');
        $this->has_one['discount_type'] = array('Promotion_banner_discount_type_model', 'id', 'promotion_banner_discount_type_id');
    }

    public function _form()
    {
        $this->rules['create'] = array(
            array(
                'field' => 'title',
                'label' => 'Title',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'cat_id',
                'label' => 'Category',
                'rules' => 'required'
            )
        );
        
        $this->rules['update'] = array(
            array(
                'field' => 'title',
                'label' => 'Title',
                'rules' => 'trim|required'
            )
        );
    }
    
}
?>