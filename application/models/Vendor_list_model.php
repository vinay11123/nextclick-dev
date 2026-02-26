<?php

class Vendor_list_model extends MY_Model
{

    public $rules;
    public $user_id = 1;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendors_list';
        $this->primary_key = 'id';

        $this->_config();
        $this->_form();
        $this->_relations();

        $this->pagination_delimiters = array(
            '<li class="page-item">',
            '</li>'
        );
        $this->pagination_arrows = array(
            '&lt;',
            '&gt;'
        );
    }

    protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id() ? $this->ion_auth->get_user_id() : $this->user_id; //add user_id
        return $data;
    }
    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id() ? $this->ion_auth->get_user_id() : $this->user_id; //add user_id
        return $data;
    }

    public function _config()
    {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }

    public function _relations()
    {
        $this->has_one['location'] = array(
            'Location_model',
            'id',
            'location_id'
        );
        $this->has_one['users'] = array(
            'User_model',
            'id',
            'vendor_user_id'
        );
        $this->has_one['executive'] = array(
            'User_model',
            'id',
            'executive_user_id'
        );
        $this->has_one['category'] = array(
            'Category_model',
            'id',
            'category_id'
        );
        $this->has_one['constituency'] = array(
            'Constituency_model',
            'id',
            'constituency_id'
        );

        $this->has_many['ratings'] = array(
            'foreign_model' => 'Vendor_rating_model',
            'foreign_table' => 'vendor_ratings',
            'local_key' => 'id',
            'foreign_key' => 'list_id'
        );
        $this->has_many['links'] = array(
            'foreign_model' => 'Social_model',
            'foreign_table' => 'social',
            'local_key' => 'id',
            'foreign_key' => 'list_id'
        );
        $this->has_many_pivot['amenities'] = array(
            'foreign_model' => 'Amenity_model',
            'pivot_table' => 'vendor_amenities',
            'local_key' => 'id',
            'pivot_local_key' => 'list_id',
            'pivot_foreign_key' => 'amenity_id',
            'foreign_key' => 'id'
        );
        $this->has_many_pivot['services'] = array(
            'foreign_model' => 'Service_model',
            'pivot_table' => 'vendor_services',
            'local_key' => 'id',
            'pivot_local_key' => 'list_id',
            'pivot_foreign_key' => 'service_id',
            'foreign_key' => 'id'
        );
        $this->has_many_pivot['brands'] = array(
            'foreign_model' => 'Brand_model',
            'pivot_table' => 'vendor_brands',
            'local_key' => 'id',
            'pivot_local_key' => 'list_id',
            'pivot_foreign_key' => 'brand_id',
            'foreign_key' => 'id'
        );
        $this->has_many_pivot['holidays'] = array(
            'foreign_model' => 'Day_model',
            'foreign_table' => 'days',
            'pivot_table' => 'vendors_holidays',
            'local_key' => 'id',
            'pivot_local_key' => 'list_id',
            'foreign_key' => 'id',
            'pivot_foreign_key' => 'day_id'
        );

        $this->has_many_pivot['sub_categories'] = array(
            'foreign_model' => 'sub_category_model',
            'foreign_table' => 'sub_categories',
            'pivot_table' => 'vendors_sub_categories',
            'local_key' => 'id',
            'pivot_local_key' => 'list_id',
            'foreign_key' => 'id',
            'pivot_foreign_key' => 'sub_category_id'
        );

        $this->has_many['contacts'] = array(
            'foreign_model' => 'Contact_model',
            'foreign_table' => 'contacts',
            'local_key' => 'id',
            'foreign_key' => 'list_id'
        );

        $this->has_many['vendor_sub_categories'] = array(
            'foreign_model' => 'Vendor_sub_category_model',
            'foreign_table' => 'vendors_sub_categories',
            'local_key' => 'id',
            'foreign_key' => 'list_id'
        );

        $this->has_many['timings'] = array(
            'foreign_model' => 'vendor_timings_model',
            'foreign_table' => 'vendor_timings',
            'local_key' => 'id',
            'foreign_key' => 'list_id'
        );
        $this->has_one['fields'] = array(
            'foreign_model' => '',
            'foreign_table' => '',
            'local_key' => '',
            'foreign_key' => ''
        );
        $this->has_many['categories'] = array(
            'foreign_model' => 'category_model',
            'foreign_table' => 'categories',
            'local_key' => 'category_id',
            'foreign_key' => 'id'
        );

        $this->has_many['banners'] = array(
            'foreign_model' => 'Vendor_banner_model',
            'foreign_table' => 'vendor_banners',
            'local_key' => 'id',
            'foreign_key' => 'list_id'
        );

        $this->has_many_pivot['on_demand_categories'] = array(
            'foreign_model' => 'od_category_model',
            'foreign_table' => 'od_categories',
            'pivot_table' => 'vendors_od_categories',
            'local_key' => 'id',
            'pivot_local_key' => 'list_id',
            'foreign_key' => 'id',
            'pivot_foreign_key' => 'od_cat_id'
        );
        $this->has_many_pivot['specialities'] = array(
            'foreign_model' => 'hosp_speciality_model',
            'pivot_table' => 'vendors_specialties',
            'local_key' => 'id',
            'pivot_local_key' => 'list_id',
            'pivot_foreign_key' => 'speciality_id',
            'foreign_key' => 'id'
        );
        $this->has_one['address'] = array(
            'Business_address_model',
            'list_id',
            'id'
        );
    }

    public function _form()
    {
        $this->rules = array(
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'ref_id',
                'lable' => 'Referal Id',
                'rules' => 'min_length[5]|max_length[10]|callback_check_referance',
                'errors' => array(
                    'min_length' => 'you need to give minimum 5 characters',
                    'check_referance' => 'Referal id is not valid'
                )
            ),
            /* array(
                'field' => 'email',
                'lable' => 'Email',
                'rules' => 'trim|required|valid_email|callback_check_email',
                'errors' => array(
                    'callback_check_email' => 'email already exists'
                )
            ), */

            /*  array(
                 'field' => 'mobile',
                 'lable' => 'Mobile',
                 'rules' => 'required|callback_check_mobile',
                 'errors' => array(
                     'callback_check_mobile' =>'Mobile already exists'
                 )
             ), */
            array(
                'field' => 'constituency_id',
                'lable' => 'Constituency Id',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'category_id',
                'lable' => 'Category Id',
                'rules' => 'trim|required'
            ),
            /*array(
                'field' => 'address',
                'lable' => 'Address',
                'rules' => 'trim|required'
            ),*/
            // array(
            //     'field' => 'landmark',
            //     'lable' => 'Landmark',
            //     'rules' => 'trim|required'
            // ),
            array(
                'field' => 'pincode',
                'lable' => 'Pincode',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'cover',
                'lable' => 'Cover Image',
                'rules' => 'trim|required'
            )
        );

        $this->rules['profile'] = array(
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim'
            )
        );
        $this->rules['social'] = array(
            array(
                'field' => 'facebook',
                'lable' => 'Facebook Link',
                'rules' => 'trim|required'
            )
        );

        $this->rules['filters'] = array(
            array(
                'field' => 'sub_categories[]',
                'lable' => 'Sub categories',
                'rules' => ''
            )
        );
    }

    public function get_vendors($limit = NULL, $offset = NULL, $status = NULL, $state = NULL, $district = NULL, $constituency = NULL, $search = NULL, $exe = NULL, $mobile = NULL)
    {
        //         $cache_name = $limit . $offset . $cat_id. $search. $lat . $long . $sub_cat_id. $brand_id;
//         $this->set_cache($cache_name); // just to set cache_name using MY_model
//         $result = $this->_get_from_cache(); // MY_model

        //         if (! (isset($result) && $result !== FALSE)) {
        $this->_query_vendors($status, $state, $district, $constituency, $search, $exe, $mobile, $status);
        $this->db->order_by('`vendors_list`.id', 'DESC');
        $this->db->order_by('`vendors_list`.created_at', 'DESC');
        $this->db->order_by('`vendors_list`.updated_at', 'DESC');
        $this->db->group_by('`vendors_list`.`vendor_user_id`');
        $this->db->limit($limit, $offset);
        $rs = $this->db->get($this->table);
        //print_array($this->db->last_query());
        if (!empty($rs))
            $result = $rs->result_array();
        else
            $result = [];

        //         $this->_write_to_cache($result); // MY_model
//         }

        return $result;
    }

    public function vendor_count($status = NULL, $state = NULL, $district = NULL, $constituency = NULL, $search = NULL, $exe = NULL, $mobile = NULL)
    {
        $this->_query_vendors($status, $state, $district, $constituency, $search, $exe, $mobile);
        return $this->db->count_all_results($this->table);
    }

    private function _query_vendors($status = NULL, $state = NULL, $district = NULL, $constituency = NULL, $search = NULL, $exe = NULL, $mobile = NULL)
    {
        $this->load->model(
            array(
                'location_model',
                'category_model',
                'sub_category_model',
                'business_address_model',
                'user_model'
            )
        );

        $location_table = '`' . $this->location_model->table . '`';
        $location_primary_key = '`' . $this->location_model->primary_key . '`';
        $location_foreign_key = '`' . 'location_id' . '`';

        $category_table = '`' . $this->category_model->table . '`';
        $user_table = '`' . $this->user_model->table . '`';
        $business_address_table = '`' . $this->business_address_model->table . '`';
        $category_primary_key = '`' . $this->category_model->primary_key . '`';
        $category_foreign_key = '`' . 'category_id' . '`';
        $user_foreign_key = '`' . 'vendor_user_id' . '`';
        $business_address_foreign_key = '`' . 'list_id' . '`';
        $primary_key = '`' . $this->primary_key . '`';
        $table = '`' . $this->table . '`';

        $str_select_vendor = '';
        foreach (array('created_at', 'updated_at', 'deleted_at', 'id', 'name', 'business_description', 'unique_id', 'category_id', 'executive_id', 'executive_user_id', 'address', 'landmark', 'vendor_user_id', 'status', 'availability') as $v) {
            $str_select_vendor .= "$table.`$v`,";
        }


        $this->db->select($str_select_vendor . "$location_table.`latitude`, $location_table.`longitude`, $location_table.`address` as location_address, $user_table.`phone`, $user_table.`email`, $business_address_table.`constituency as constituency_id`");
        $this->db->join($category_table, "$category_table.$primary_key=$table.$category_foreign_key", 'left');
        $this->db->join($location_table, "$location_table.$primary_key=$table.$location_foreign_key");
        $this->db->join($user_table, "$user_table.$primary_key=$table.$user_foreign_key");
        $this->db->join($business_address_table, "$business_address_table.$business_address_foreign_key=$table.$primary_key");

        if (!empty($search)) {
            $this->db->or_like($table . '.`name`', $search);
            $this->db->or_like($category_table . '.`name`', $search);
            $this->db->or_like($table . '.`address`', $search);
        }

        // if (! is_null($search)) {foreach (explode(' ', $search) as $s){
        //     $this->db->or_like($table . '.`sounds_like`', metaphone($s));
        // }}

        if (!empty($exe)) {
            /* $this->db->join('`users`', "vendors_list.executive_id=users.id", 'left');
            $this->db->where('users.unique_id', $exe); 
            $this->db->where($table . '.`id`', $exe);*/
            $this->db->where($table . '.`executive_user_id`', $exe);
        }
        if (!empty($mobile)) {
            // $this->db->join('`contacts`', "vendors_list.id=contacts.list_id", 'left');
            $this->db->where($user_table . '.phone', $mobile);
        }

        if ($status == 1 || $status == 2) {
            $this->db->where($table . '.`status`', $status);
        } else {
            $this->db->where("$table.deleted_at =", NULL);
        }
        return $this;
    }

    public function all($limit = NULL, $offset = NULL, $cat_id = NULL, $sub_cat_id = NULL, $search = NULL, $lat = FALSE, $long = NULL, $brand_id = NULL, $vendor_user_ids = [])
    {

        $this->_query_all($cat_id, $sub_cat_id, $search, $lat, $long, $brand_id, $vendor_user_ids);
        $this->db->order_by('RAND()');
        $this->db->group_by('`vendors_list`.`id`');
        if (is_null($search) && !is_null($limit) && !is_null($offset)) {
            $this->db->limit($limit, $offset);
        }
        $rs = $this->db->get($this->table);
        // echo $this->db->last_query();
        if ($rs) {
            $result = $rs->custom_result_object('Vendor_list_row');
        }

        //print_array($this->db->last_query());
        $this->db->reset_query();

        if (!empty($rs)) {
            $this->_query_all($cat_id, $sub_cat_id, $search, $lat, $long, $brand_id, $vendor_user_ids);
            $count = $this->db->count_all_results($this->table);
            return array(
                'result' => $result,
                'count' => $count
            );
        }
    }

    private function _query_all($cat_id = NULL, $sub_cat_id = NULL, $search = NULL, $lat = NULL, $long = NULL, $brand_id = NULL, $vendor_user_ids = [])
    {

        $this->load->model(
            array(
                'location_model',
                'category_model',
                'sub_category_model',
                'business_address_model',
                'setting_model'
            )
        );
        $wideAreaSearch = $this->setting_model->where('key', 'wide_area_search')->get()['value'];
        $location_table = '`' . $this->location_model->table . '`';
        $business_address_table = '`' . $this->business_address_model->table . '`';
        $location_primary_key = '`' . $this->location_model->primary_key . '`';
        $location_foreign_key = '`' . 'location_id' . '`';

        $category_table = '`' . $this->category_model->table . '`';
        $category_primary_key = '`' . $this->category_model->primary_key . '`';
        $category_foreign_key = '`' . 'category_id' . '`';

        $primary_key = '`' . $this->primary_key . '`';
        $table = '`' . $this->table . '`';

        $str_select_vendor = '';
        foreach (array('created_at', 'updated_at', 'deleted_at', 'id', 'name', 'email', 'unique_id', // 'constituency_id',
            'category_id', 'executive_id', // 'address',
            'landmark', 'vendor_user_id', 'status', 'availability') as $v) {
            $str_select_vendor .= "$table.`$v`,";
        }

        $this->db->select($str_select_vendor . "(6371.0088 * 2 * ASIN(SQRT(POWER(SIN(RADIANS($lat - locations.latitude)) / 2, 2) + COS(RADIANS($lat)) * COS(RADIANS(locations.latitude)) * POWER(SIN(RADIANS($long - locations.longitude)/2), 2)))) AS distance,$business_address_table.line1 as address, $business_address_table.lat, $business_address_table.lng, $business_address_table.location as location, $business_address_table.constituency as constituency_id");
        //$this->db->join($category_table, "$category_table.$primary_key=$table.$category_foreign_key", 'left');
        $this->db->join($location_table, "$location_table.$primary_key=$table.$location_foreign_key", 'left');
        if (empty($vendor_user_ids)) {
            if (!empty($cat_id)) {
                $this->db->where("$table.category_id=", $cat_id);
            }

            if ($sub_cat_id) {
                $this->db->join('`vendors_sub_categories`', "$table.$primary_key=vendors_sub_categories.list_id", 'left');
                $this->db->where("`vendors_sub_categories`.`sub_category_id`=", $sub_cat_id);
            }

            if ($brand_id) {
                $this->db->join('`vendor_brands`', "$table.$primary_key=vendor_brands.list_id", 'left');
                $this->db->where("`vendor_brands`.`brand_id`=", $brand_id);
            }

            if ($lat && $long) {

                $locations = $this->db->query("SELECT id, address,(6371.0088 * 2 * ASIN(SQRT(POWER(SIN(RADIANS($lat - locations.latitude)) / 2, 2) + COS(RADIANS($lat)) * COS(RADIANS(locations.latitude)) * POWER(SIN(RADIANS($long - locations.longitude)/2), 2)))) AS distance  FROM locations HAVING distance < " . $this->config->item('service_distance_in_km') . " ORDER BY distance")->result_array();

                if (empty($locations) && $wideAreaSearch == 1) {
                    $locations = $this->db->query("SELECT id, (6371.0088 * 2 * ASIN(SQRT(POWER(SIN(RADIANS($lat - locations.latitude)) / 2, 2) + COS(RADIANS($lat)) * COS(RADIANS(locations.latitude)) * POWER(SIN(RADIANS($long - locations.longitude)/2), 2)))) AS distance FROM locations HAVING distance < 25 ORDER BY distance")->result_array();
                }
                $this->db->where_in("$table.`location_id`", (empty(array_column($locations, 'id'))) ? 0 : array_column($locations, 'id'));
            }

            if ($search) {
                $this->db->join("food_item", "food_item.created_user_id=$table.vendor_user_id", 'left');
                $this->db->group_start();
                //this is removed for the purpose of spaces in the content
                //foreach (explode(' ', $search) as $s){

                $this->db->like($table . '.`business_name`', $search);
                $this->db->or_like('food_item.`sounds_like`', metaphone($search));
                //}
                $this->db->group_end();
            }


        } else {
            $this->db->where_in("$table.`vendor_user_id`", $vendor_user_ids);
        }
        $this->db->join($business_address_table, "$table.$primary_key=$business_address_table.list_id", 'left');
        $this->db->group_by('id');
        $this->db->where("$table.`status`=", '1');
        $this->db->where("$table.`deleted_at` =", NULL);
        return $this;
    }


    public function get_vendors_nearby_delivery($lat = NULL, $long = NULL)
    {
        if (!is_null($lat) && !is_null($long)) {
            $this->db->select('vendors_list.id, vendor_user_id, vendor_address.constituency as constituency_id, name, unique_id, location_id, locations.latitude, locations.longitude');
            $this->db->join('locations', "locations.id=$this->table.location_id", 'left');
            $this->db->join('vendor_address', "vendors_list.id=vendor_address.list_id", 'left');
            $locations = $this->db->query("SELECT id, (6371.0088 * 2 * ASIN(SQRT(POWER(SIN(RADIANS($lat - locations.latitude)) / 2, 2) + COS(RADIANS($lat)) * COS(RADIANS(locations.latitude)) * POWER(SIN(RADIANS($long - locations.longitude)/2), 2)))) AS distance 
            FROM locations HAVING distance < " . $this->config->item('service_distance_in_km') . " ORDER BY distance")->result_array();
            $this->db->where_in("$this->table.`location_id`", (empty(array_column($locations, 'id'))) ? 0 : array_column($locations, 'id'));
            $this->db->where("$this->table.`status`", 1);
            $rs = $this->db->get($this->table);
            if ($rs) {
                return $rs->result_array();
            }
        }
    }

    public function get_vendors_near_by_given_latlong($lat = NULL, $long = NULL, $cat_id = NULL)
    {
        $locations = $this->db->query("SELECT id, (6371.0088 * 2 * ASIN(SQRT(POWER(SIN(RADIANS($lat - locations.latitude)) / 2, 2) + COS(RADIANS($lat)) * COS(RADIANS(locations.latitude)) * POWER(SIN(RADIANS($long - locations.longitude)/2), 2)))) AS distance  FROM locations HAVING distance < " . $this->config->item('service_distance_in_km') . " ORDER BY distance")->result_array();
        $this->db->select('id, vendor_user_id, name, location_id, unique_id, constituency_id, category_id');

        if (!empty($cat_id)) {
            $this->db->where("`category_id`", $cat_id);
        }

        if (!empty($lat) & $long) {
            $this->db->where_in("`location_id`", (empty(array_column($locations, 'id'))) ? 0 : array_column($locations, 'id'));
        }
        return $this->db->get('vendors_list')->result_array();
    }

    public function get_vendor_count($executive_id, $type = '')
    {

        if ($type == 'subscribed') {
            $this->db->select("v.*, vad.location, CONCAT_WS(' ', exec.first_name, exec.last_name) as executive_name, et.executive_type, pa.title as package_plan");
            $this->db->from('users u');
            $this->db->join('vendors_list v', 'u.id = v.vendor_user_id');
            $this->db->join('vendor_address as vad', 'vad.list_id = v.id');
            $this->db->join('users as exec', 'v.executive_user_id = exec.id');
            $this->db->join('executive_address as ead', 'ead.user_id = exec.id', 'left');
            $this->db->join('executive_type as et', 'et.id = ead.executive_type_id', 'left');
            $this->db->join('packages pa', 'v.first_paid_subscription_id = pa.id', 'left');
            $this->db->where('v.executive_user_id', $executive_id);

            $this->db->where('v.status', '1');
            $this->db->where('v.first_paid_subscription_id IS NOT NULL');
            $this->db->where('v.first_paid_subscription_at IS NOT NULL');
            $this->db->order_by('v.created_at', 'DESC');
        } else {
            $this->db->select("v.*, vad.location, CONCAT_WS(' ', exec.first_name, exec.last_name) as executive_name, et.executive_type");
            $this->db->from('users u');
            $this->db->join('vendors_list v', 'u.id = v.vendor_user_id');
            $this->db->join('vendor_address as vad', 'vad.list_id = v.id');
            $this->db->join('users as exec', 'v.executive_user_id = exec.id');
            $this->db->join('executive_address as ead', 'ead.user_id = exec.id', 'left');
            $this->db->join('executive_type as et', 'et.id = ead.executive_type_id', 'left');
            $this->db->where('v.executive_user_id', $executive_id);
            if ($type == 'approved') {
                $this->db->where('v.status', '1');
            } else if ($type == 'pending') {
                $this->db->where('v.status', '2');
            } else if ($type == 'unsubscribed') {
                $this->db->where('v.status', '1');
                $this->db->where('v.first_paid_subscription_id', NULL);
                $this->db->where('v.first_paid_subscription_at', NULL);
            }
            $this->db->order_by('v.created_at', 'DESC');

        }

        $query = $this->db->get();

        $count = $query->num_rows();

        if (!$query) {
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            return array(
                'count' => $count,
                'vendor_details' => $query->result()
            );

        }
    }


    public function get_executive_vendor_list($type = '')
    {

        if ($type == 'subscribed') {
            $this->db->select("v.*, vad.location, CONCAT_WS(' ', exec.first_name, exec.last_name) as executive_name, et.executive_type, pa.title as package_plan");
            $this->db->from('users u');
            $this->db->join('vendors_list v', 'u.id = v.vendor_user_id');
            $this->db->join('vendor_address as vad', 'vad.list_id = v.id');
            $this->db->join('users as exec', 'v.executive_user_id = exec.id');
            $this->db->join('executive_address as ead', 'ead.user_id = exec.id', 'left');
            $this->db->join('executive_type as et', 'et.id = ead.executive_type_id', 'left');
            $this->db->join('packages pa', 'v.first_paid_subscription_id = pa.id', 'left');
            $this->db->where('v.executive_user_id IS NOT NULL');
            $this->db->where('v.status', '1');
            $this->db->where('v.first_paid_subscription_id IS NOT NULL');
            $this->db->where('v.first_paid_subscription_at IS NOT NULL');
            $this->db->order_by('v.created_at', 'DESC');
            
        } else {

            $this->db->select("v.*, vad.location, CONCAT_WS(' ', exec.first_name, exec.last_name) as executive_name, et.executive_type");
            $this->db->from('users u');
            $this->db->join('vendors_list v', 'u.id = v.vendor_user_id');
            $this->db->join('vendor_address as vad', 'vad.list_id = v.id');
            $this->db->join('users as exec', 'v.executive_user_id = exec.id');
            $this->db->join('executive_address as ead', 'ead.user_id = exec.id', 'left');
            $this->db->join('executive_type as et', 'et.id = ead.executive_type_id', 'left');
            $this->db->where('v.executive_user_id IS NOT NULL');
            if ($type == 'approved') {
                $this->db->where('v.status', '1');
            } else if ($type == 'pending') {
                $this->db->where('v.status', '2');
            } else if ($type == 'unsubscribed') {
                $this->db->where('v.status', '1');
                $this->db->where('v.first_paid_subscription_id', NULL);
                $this->db->where('v.first_paid_subscription_at', NULL);
            }
            $this->db->order_by('`v`.created_at', 'DESC');
        }
        $query = $this->db->get();

        $count = $query->num_rows();

        if (!$query) {
            $error = $this->db->error();
            echo "Error: " . $error['code'] . " - " . $error['message'];
            return false;
        } else {
            return array(
                'count' => $count,
                'vendor_details' => $query->result()
            );

        }
    }
}

class Vendor_list_row
{

    public $id;

    public $name;

    public $email;

    public $unique_id;

    public $address;

    public $landmark;

    public $created_at;

    public $updated_at;

    public $availability;
}





