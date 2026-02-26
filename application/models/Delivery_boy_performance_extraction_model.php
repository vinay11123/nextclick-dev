<?php

class Delivery_boy_performance_extraction_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'delivery_boy_performance_extraction';
        $this->primary_key = 'id';

        $this->_config();
        //     $this->_form();
        $this->_relations();
    }

    private function _config()
    {
        $this->timestamps = TRUE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }

    private function _relations()
    {
        $this->has_many['ratings'] = array(
            'foreign_model' => 'Delivery_boy_performance_extraction_rating_model',
            'local_key' => 'id',
            'foreign_key' => 'performance_extraction_id',
            'get_relate' => FALSE
        );
    }
    public function updateData($id, $data)
    {
        $this->update($data, $id);
        return true;
    }

    public function saveAggrigations($date, $agggregationsArr)
    {
        $this->load->model('delivery_insentive_shift_config_model');
        $this->load->model('delivery_boy_performance_extraction_rating_model');
        foreach ($agggregationsArr as $key => $agggregation) {
            $record = $this->where([
                'business_date' => $date,
                'delivery_boy_user_id' => $agggregation->delivery_boy_user_id
            ])->get();
            if ($record && $record['status']==1) {
                $touchPointsCount = $agggregation->count * 2;
                $record['touch_points'] = $touchPointsCount;
                $deliveryBoyInsentive = $this->delivery_insentive_shift_config_model->calculateDayInsentiveForUser($record, $agggregation->ratings);
                $record['amount'] = $deliveryBoyInsentive['amount'];
                $this->updateData($record['id'], [
                    'touch_points' => $touchPointsCount,
                    'amount' =>$deliveryBoyInsentive['amount']
                ]);
                foreach($deliveryBoyInsentive['ratings'] as $key1=> $rating){
                    $this->delivery_boy_performance_extraction_rating_model->upsertData($record['id'], $rating['rating'], ['count'=>$rating['count']]);
                }
            } else {
                $data = [];
                $touchPointsCount = $agggregation->count * 2;
                $data['business_date'] = $date;
                $data['delivery_boy_user_id'] = $agggregation->delivery_boy_user_id;
                $data['touch_points'] = $touchPointsCount;
                $deliveryBoyInsentive = $this->delivery_insentive_shift_config_model->calculateDayInsentiveForUser($record, $agggregation->rating);
                $record['amount'] = $deliveryBoyInsentive['amount'];
                $extractionID = $this->insert($data);
                foreach($deliveryBoyInsentive['ratings'] as $key1=> $rating){
                    $this->delivery_boy_performance_extraction_rating_model->upsertData($extractionID, $rating['rating'], ['count'=>$rating['count']]);
                }
            }
        }
        return true;
    }

    public function fetchPerformances()
    {
        $this->load->model(array(
            'user_model'
        ));
        $table = $this->table;
        $user_table = '`' . $this->user_model->table . '`';
        $user_table_foriegn_key = '`' . 'delivery_boy_user_id' . '`';
        $user_table_primary_key = '`' . $this->user_model->primary_key . '`';
        $this->db->select("SUM(touch_points) as touch_points, $user_table.id, $user_table.first_name, $user_table.last_name, SUM(amount) as amount");
        $this->db->join($user_table, "$user_table.$user_table_primary_key=$table.$user_table_foriegn_key", 'inner');
        $this->db->where("$table.`status`=", 1);
        $this->db->group_by("$table.delivery_boy_user_id");
        $rs = $this->db->get($table)->result_array();
        return $rs;
    }

    public function fetcTotalInsentive()
    {
        $table = $this->table;
        $this->db->select("SUM(amount) as amount");
        $this->db->where("$table.`status`=", 1);
        $rs = $this->db->get($table)->result_array();
        return $rs[0]['amount'];
    }

    public function getDayWiseTouchpoints($userID)
    {
        $businessDayTouchPoints = $this->where([
            'delivery_boy_user_id' => $userID,
            'status' => 1
        ])->get_all();
        return $businessDayTouchPoints;
    }
}
