 <?php

class Food_item_model extends MY_Model
{

    public $rules, $user_id, $file_upload_rules;

    public function __construct()
    {
        parent::__construct();
        $this->table = "food_item";
        $this->primary_key = "id";
        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';

        $this->_config();
        $this->_form();
        $this->_relations();
    }

    protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id() ? $this->ion_auth->get_user_id() : $this->user_id; // add user_id
        return $data;
    }

    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id() ? $this->ion_auth->get_user_id() : $this->user_id; // add user_id
        return $data;
    }

    public function _config()
    {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;

        $this->pagination_delimiters = array(
            '<li class="page-item">',
            '</li>'
        );
        $this->pagination_arrows = array(
            '&lt;',
            '&gt;'
        );
    }

    public function _relations()
    {
        $this->has_one['brand'] = array(
            'Brand_model',
            'id',
            'brand_id'
        );

        $this->has_one['menu'] = array(
            'Food_menu_model',
            'id',
            'menu_id'
        );

        $this->has_one['created_by'] = array(
            'User_model',
            'id',
            'created_user_id'
        );

        $this->has_one['sub_category'] = array(
            'Sub_category_model',
            'id',
            'sub_cat_id'
        );

        $this->has_many['sections'] = array(
            'foreign_model' => 'Food_section_model',
            'foreign_table' => 'food_section',
            'local_key' => 'id',
            'foreign_key' => 'item_id',
            'get_relate' => FALSE
        );

        $this->has_many['section_items'] = array(
            'foreign_model' => 'Food_sec_item_model',
            'foreign_table' => 'food_sec_item',
            'local_key' => 'id',
            'foreign_key' => 'item_id',
            'get_relate' => FALSE
        );

        $this->has_many['vendor_product_varinats'] = array(
            'foreign_model' => 'Vendor_product_variant_model',
            'foreign_table' => 'vendor_product_variants',
            'local_key' => 'id',
            'foreign_key' => 'item_id',
            'get_relate' => FALSE
        );

        $this->has_many['item_images'] = array(
            'foreign_model' => 'Food_item_image_model',
            'foreign_table' => 'food_item_images',
            'local_key' => 'id',
            'foreign_key' => 'item_id',
            'get_relate' => FALSE
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
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please Enter Name'
                )
            )
        );

        $this->file_upload_rules = array(
            array(
                'field' => 'excel_file',
                'label' => 'Excel File',
                'rules' => 'required'
            ),
            array(
                'field' => 'images_zip',
                'label' => 'Image ZIP',
                'rules' => 'required'
            )
        );
    }

    public function all($limit = NULL, $offset = NULL, $item_type = NULL, $sub_cat_id = NULL, $menu_id = NULL, $brand_id = NULL, $search = NULL, $vendor_id = NULL, $hit_from = NULL)
    {
        $this->_query_all($item_type, $sub_cat_id, $menu_id, $brand_id, $search, $vendor_id, $hit_from);
        if(empty($hit_from)){
            $this->db->group_by($this->table . '.`id`');
        }else {
            $this->db->group_by('`vendor_product_variants`.`vendor_user_id`');
        }
        
        if (! empty($limit)) {
            $this->db->limit($limit, $offset);
        }
        $rs = $this->db->get($this->table);
        if ($rs) {
            $result = $rs->result_array();
        }
        //print_array($this->db->last_query());
        $this->db->reset_query();

        if (! empty($rs)) {
            $this->_query_all($item_type, $sub_cat_id, $menu_id, $brand_id, $search, $vendor_id, $hit_from);
            $count = $this->db->count_all_results($this->table);
            return array(
                'result' => $result,
                'count' => $count
            );
        }
    }

    private function _query_all($item_type = NULL, $sub_cat_id = NULL, $menu_id = NULL, $brand_id = NULL, $search = NULL, $vendor_id = NULL, $hit_from = NULL)
    {
        $primary_key = '`' . $this->primary_key . '`';
        $table = '`' . $this->table . '`';

        $str_select_item = '';
        foreach (array(
            'id',
            'sub_cat_id',
            'menu_id',
            'brand_id',
            'product_code',
            'name',
            'desc',
            'item_type'
        ) as $i) {
            $str_select_item .= "$table.`$i`,";
        }

        $this->db->select($str_select_item . "MAX(vendor_product_variants.price) as max_price, MAX(vendor_product_variants.stock) as max_stock, MAX(vendor_product_variants.discount) as max_discount, vendor_product_variants.vendor_user_id, vendor_product_variants.status, food_item_images.id as image_id, food_item_images.ext");
        $this->db->join('food_sec_item', "food_sec_item.item_id=$table.$primary_key", 'left');
        $this->db->join('vendor_product_variants', "vendor_product_variants.item_id=$table.$primary_key", 'left');
        $this->db->join('food_item_images', "food_item_images.item_id=$table.$primary_key", 'left');

        if ($sub_cat_id) {
            $this->db->where("$table.`sub_cat_id`=", $sub_cat_id);
        }

        if (! empty($menu_id)) {
            $this->db->where("$table.menu_id=", $menu_id);
        }
        
        if (! empty($item_type)) {
            $this->db->where("$table.item_type=", $item_type);
        }

        if (! empty($brand_id)) {
            $this->db->where("$table.`brand_id`=", $brand_id);
        }

        if (! empty($vendor_id)) {
            $vendor_id = (is_array($vendor_id))? $vendor_id : [$vendor_id];
            $this->db->where_in("vendor_product_variants.`vendor_user_id`", $vendor_id);
        }

        if (! is_null($search)) {
            foreach (explode(' ', $search) as $s) {
                $this->db->or_like($table . '.`sounds_like`', metaphone($s));
            }
        }

        /*
         * if (! is_null($search)) {foreach (explode(' ', $search) as $s){
         * $this->db->or_like($table . '.`sounds_like`', metaphone($s));
         * }}
         */

        $this->db->where("$table.`availability`=", 1);

        $this->db->where("vendor_product_variants.`status`=", 1);
        $this->db->where("vendor_product_variants.`stock` >=", 1);
        $this->db->where("$table.`deleted_at` =", NULL);
        return $this;
    }
    
    public function get_product_by_ids($product_ids = array()){
        try {
            $this->load->model('food_sec_item_model');
            $this->load->model('tax_model');
            $this->load->model('return_policies_model');
            if(empty($product_ids)){
                return [
                    'success' => FALSE,
                    'error' => 'invalid input'
                ];
            }
            $catalogue_product = $this->with_menu('fields: id, name')
            ->with_sub_category('fields: id, name')
            ->with_brand('fields: id, name')
            ->with_sections('fields: id, name')
            ->with_item_images('fields: id, serial_number, ext')
            ->with_section_items('fields: id, section_item_code, name, desc, price, weight, status, created_at, updated_at')
            ->with_vendor_product_varinats('fields: id, item_id, section_id, section_item_id, sku, price, stock, discount, tax_id, list_id, vendor_user_id,return_id,return_available, status', 'where: vendor_user_id=' . $token_data->id)
            ->where('id', $product_ids)
            ->get_all();
            if(! empty($catalogue_product)){foreach ($catalogue_product as $product_key => $product){
                if (! empty($catalogue_product[$product_key]['vendor_product_varinats'])) {
                    foreach ($catalogue_product[$product_key]['vendor_product_varinats'] as $key => $val) {
                        $name = $this->food_sec_item_model->fields('name, weight')
                        ->where('id', $val['section_item_id'])
                        ->get();
                        $catalogue_product[$product_key]['vendor_product_varinats'][$key]['section_item_name'] = ! empty($name) ? $name['name'] : NULL;
                        $catalogue_product[$product_key]['vendor_product_varinats'][$key]['weight'] = ! empty($name) ? $name['weight'] : NULL;
                        if (! empty($val['tax_id'])) {
                            $tax = $this->tax_model->fields('id, tax, rate')
                            ->with_tax_type('fields: id, name, desc')
                            ->where('id', $val['tax_id'])
                            ->get();
                        } else {
                            $tax = NULL;
                        }
                        $catalogue_product[$product_key]['vendor_product_varinats'][$key]['tax'] = $tax;
                    }
                }
                $catalogue_product[$product_key]['return_policy'] = $this->return_policies_model->where('menu_id', $product['menu_id'])->get();
                $catalogue_product[$product_key]['return_policy'] = (empty($catalogue_product[$product_key]['return_policy']))? NULL : $catalogue_product[$product_key]['return_policy'];
                if (! empty($catalogue_product[$product_key])) {
                    if (! empty($catalogue_product[$product_key]['item_images'])) {
                        foreach ($catalogue_product[$product_key]['item_images'] as $k => $img) {
                            $catalogue_product[$product_key]['item_images'][$k]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $img['id'] . '.' . $img['ext'] . '?' . time();
                        }
                    } else {
                        $catalogue_product[$product_key]['item_images'] = NULL;
                    }
                }
            }
            return [
                'success' => TRUE,
                'data' => $catalogue_product
            ];
            }else {
                return [
                    'success' => TRUE,
                    'data' => []
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => FALSE,
                'error' => $e
            ];
        }
    }
}
?>