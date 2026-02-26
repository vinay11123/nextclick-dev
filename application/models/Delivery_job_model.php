<?php

class Delivery_job_model extends MY_Model
{
    public $rules, $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'delivery_jobs';
        $this->primary_key = 'id';

        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';

        $this->_config();
        $this->_form();
        $this->_relations();
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
        $this->has_one['rejected_reason'] = array('Delivery_job_rejected_reason_model', 'id', 'rejected_reason_id');
        $this->has_one['order'] = array('ecom_order_model', 'id', 'ecom_order_id');
        $this->has_one['delivery_boy'] = array('User_model', 'id', 'delivery_boy_user_id');
        $this->has_one['created_user'] = array('User_model', 'id', 'created_user_id');
        $this->has_one['updated_user'] = array('User_model', 'id', 'updated_user_id');
        $this->has_many['delivery_rejections'] = array(
            'foreign_model' => 'Delivery_job_rejection_model',
            'foreign_table' => 'delivery_job_rejections',
            'local_key' => 'id',
            'foreign_key' => 'job_id',
            'get_relate' => FALSE
        );
    }

    private function _form()
    {

        $this->rules['update_rules'] = array(
            array(
                'field' => 'rating',
                'lable' => 'rating',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'feedback',
                'lable' => 'feedback',
                'rules' => 'trim'
            ),
        );
    }

    public function updateAmountCollected($orderID, $amount)
    {
        try {
            $jobInfo = $this->get([
                "ecom_order_id" => $orderID,
                "job_type" => 1
            ]);
            if ($jobInfo && $jobInfo["id"]) {
                $this->update([
                    "amount_collected" => $amount
                ], $jobInfo["id"]);
                return $jobInfo["delivery_boy_user_id"];
            }
            return null;
        } catch (Exception $e) {
            echo "Unable to Process";
        }
    }

    public function extractPerformanceInfo($fromDate, $tillBeforeDate)
    {
        $fromDate = "$fromDate 00:00:00";
        $tillBeforeDate = "$tillBeforeDate 00:00:00";
        $this->load->model(array(
            'ecom_order_model'
        ));
        $table = $this->table;
        $str_select_delivery_job = '';
        foreach (array(
            'delivery_boy_user_id'
        ) as $v) {
            $str_select_delivery_job .= "$table.`$v`,";
        }
        $ecom_order_table = '`' . $this->ecom_order_model->table . '`';
        $delivery_job_foriegn_key = '`' . 'ecom_order_id' . '`';
        $delivery_job_primary_key = '`' . $this->ecom_order_model->primary_key . '`';
        $this->db->select($str_select_delivery_job . " count(*) as count");
        $this->db->join($ecom_order_table, "$ecom_order_table.$delivery_job_primary_key=$table.$delivery_job_foriegn_key", 'inner');
        $this->db->where("$ecom_order_table.created_at >= '$fromDate' AND $ecom_order_table.created_at < '$tillBeforeDate'");
        $this->db->where("$table.`status`=", '508');
        $this->db->where("$ecom_order_table.`order_status_id`=", '12');
        $this->db->group_by("$table.delivery_boy_user_id");
        $deviveryBoyJobs = $this->db->get($table)->result();

        $this->db->select($str_select_delivery_job . " count(*) as count, rating");
        $this->db->join($ecom_order_table, "$ecom_order_table.$delivery_job_primary_key=$table.$delivery_job_foriegn_key", 'inner');
        $this->db->where("$ecom_order_table.created_at >= '$fromDate' AND $ecom_order_table.created_at < '$tillBeforeDate'");
        $this->db->where("$table.`status`=", '508');
        $this->db->where("$ecom_order_table.`order_status_id`=", '12');
        $this->db->group_by("$table.delivery_boy_user_id, $table.rating");
        $ratingsArr = $this->db->get($table)->result();
        foreach($ratingsArr as $key=>$rating ){
            foreach($deviveryBoyJobs as $key1=>$deviveryBoyJob ){
                if($rating->delivery_boy_user_id ==$deviveryBoyJob->delivery_boy_user_id){
                    $deviveryBoyJob->ratings[] = [
                        "rating" => $rating->rating,
                        "count" => $rating->count
                    ];
                }
            }
        }
        return $deviveryBoyJobs;
    }
}
