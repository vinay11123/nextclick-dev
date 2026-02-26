<?php

class Vendor_product_variant_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendor_product_variants';
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
        $this->has_one['item'] = array('Food_item_model','id','item_id');
        $this->has_one['cart'] = array('Food_cart_model','vendor_product_variant_id','id');
        $this->has_one['section'] = array('Food_section_model','id','section_id');
        $this->has_one['section_item'] = array('Food_sec_item_model','id','section_item_id');
        $this->has_one['return'] = array('Return_policies_model','id','return_id');
        $this->has_one['list_id'] = array('Vendor_list_model','id','list_id');
        $this->has_one['Vendor_user'] = array('User_model','id','vendor_user_id');
        $this->has_one['service_charge'] = array('User_model','id','vendor_user_id');
        $this->has_one['tax'] = array('Tax_model','id','tax_id');
        $this->has_many['item_images'] = array(
            'foreign_model' => 'Food_item_image_model',
            'foreign_table' => 'food_item_images',
            'local_key' => 'item_id',
            'foreign_key' => 'item_id',
            'get_relate' => FALSE
        );
    }
    
    public function _form(){
        $this->rules['create'] = array(
            array(
                'field'=>'item_id',
                'label'=>'Item Id',
                'rules'=>'required',
                'errors'=>array(
                    'required'=>'Item id is required.'
                )
            )
        );
        
        $this->rules['update'] = array(
            array(
                'field'=>'variant_id',
                'label'=>'Variant Id',
                'rules'=>'required',
                'errors'=>array(
                    'required'=>'Variant id is required.'
                )
            )
        );
    }
    
    public function all($limit = NULL, $offset = NULL, $sub_cat_id = NULL, $menu_id = NULL, $brand_id = NULL, $search = NULL, $vendor_id = NULL, $stock_type = NULL, $is_count = FALSE)
    {
        $this->_query_all($sub_cat_id, $menu_id, $brand_id, $search, $vendor_id, $stock_type);
        $this->db->group_by('`' . $this->food_item_model->table . '`'.'.`id`');
        $this->db->order_by('`' . $this->food_item_model->table . '`'.'.`id`', 'DESC');
        if(! empty($limit)){
            $this->db->limit($limit, $offset);
        }
        $rs = $this->db->get('`' . $this->food_item_model->table . '`');
        if ($rs) {
            $result = $rs->result_array();
        }
        if($is_count){
            return $this->db->count_all_results('`' . $this->food_item_model->table . '`');
        }
        
        //print_array($this->db->last_query());
        $this->db->reset_query();
        
        if (! empty($rs)) {
            $this->_query_all($sub_cat_id, $menu_id, $brand_id, $search, $vendor_id, $stock_type);
            $count = $this->db->count_all_results('`' . $this->food_item_model->table . '`');
            return array(
                'result' => $result,
                'count' => $count
            );
        }
    }
    
    private function _query_all($sub_cat_id = NULL, $menu_id = NULL, $brand_id = NULL, $search = NULL, $vendor_id = NULL, $stock_type = NULL)
    {
        if(! $this->ion_auth->in_group('admin', $vendor_id)){
            $min_stock = $this->db->query("SELECT sum(min_stock) as min_stock FROM ecom_settings where created_user_id = ".$vendor_id.";")->result_array()[0]['min_stock'];
        }else {
            $min_stock = 0;
        }
        
        $primary_key = '`' . $this->primary_key . '`';
        $table = '`' . $this->food_item_model->table . '`';
        
        $str_select_item = '';
        foreach (array(
            'id',
            'sub_cat_id',
            'menu_id',
            'brand_id',
            'product_code',
            'name',
            'desc',
            'item_type',
            'availability'
        ) as $i) {
            $str_select_item .= "$table.`$i`,";
        }
        
        $this->db->select($str_select_item."MIN(vendor_product_variants.price) as min_price, MAX(vendor_product_variants.stock) as max_stock, AVG(vendor_product_variants.discount) as avg_discount, vendor_product_variants.vendor_user_id, vendor_product_variants.status, food_item_images.id as image_id, food_item_images.ext");
        $this->db->join('food_sec_item', "food_sec_item.item_id=$table.$primary_key", 'left');
        $this->db->join('vendor_product_variants', "vendor_product_variants.item_id=$table.$primary_key");
        $this->db->join('food_item_images', "food_item_images.item_id=$table.$primary_key", 'left');
        
        if ($sub_cat_id) {
            $this->db->where("$table.`sub_cat_id`=", $sub_cat_id);
        }
        
        if (! empty($menu_id)) {
            $this->db->where("$table.menu_id=", $menu_id);
        }
        
        if (! empty($brand_id)) {
            $this->db->where("$table.`brand_id`=", $brand_id);
        }
        
        if (! is_null($search)) {foreach (explode(' ', $search) as $s){
            $this->db->or_like($table . '.`sounds_like`', metaphone($s));
        }}
        
        if(is_null($stock_type)){
            $this->db->where("vendor_product_variants.`stock`>", (!empty($min_stock))? $min_stock : 0);
        }elseif ($stock_type == 'instock'){
            $this->db->where("vendor_product_variants.`stock`>", (!empty($min_stock))? $min_stock : 0);
        }elseif ($stock_type == 'outofstock'){
            $this->db->where("vendor_product_variants.`stock`<=", (!empty($min_stock))? $min_stock : 0);
        }elseif ($stock_type == 'outstock'){
            $this->db->where("vendor_product_variants.`stock`<", (!empty($min_stock))? $min_stock : 0);
        }
        
        if (! empty($vendor_id) && ! $this->ion_auth->in_group('admin', $vendor_id)) {
            $this->db->where("vendor_product_variants.`vendor_user_id`=", $vendor_id);
        }
        $this->db->where("$table.`availability`=", 1);
        $this->db->where("$table.`deleted_at` =", NULL);
        return $this;
    }
}

