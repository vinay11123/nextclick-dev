<?php

class Delivery_insentive_shift_config_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'delivery_insentive_shift_config';
        $this->primary_key = 'id';

        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';

        $this->_config();
        //     $this->_form();
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

    private function _config()
    {
        $this->timestamps = TRUE;
        $this->soft_deletes = FALSE;
        $this->delete_cache_on_save = TRUE;
    }

    private function _relations()
    {
        $this->has_one['insentive'] = array(
            'foreign_model' => 'Delivery_insentive_config_model',
            'local_key' => 'insentive_id',
            'foreign_key' => 'id',
            'get_relate' => FALSE
        );

        $this->has_one['shift'] = array(
            'foreign_model' => 'Shift_model',
            'local_key' => 'shift_id',
            'foreign_key' => 'id',
            'get_relate' => FALSE
        );
    }
    public function updateData($id, $data)
    {
        $this->update($data, $id);
        return true;
    }

    public function upsertData($InsentiveID, $shiftID, $data)
    {
        $delivery_insentive_config = $this->where([
            'insentive_id' => $InsentiveID,
            'shift_id' => $shiftID
        ])->get();
        if ($delivery_insentive_config) {
            $this->updateData($delivery_insentive_config['id'], $data);
        } else {
            $data['insentive_id'] = $InsentiveID;
            $data['shift_id'] = $shiftID;
            $this->insert($data);
        }
        return true;
    }

    public function getInsentives($config, $deliveryBoyPerformance, $daywiseTouchpoint, $ratingsArr=[])
    {
        $this->load->model('delivery_ratings_insentive_config_model');
        $config = $config['shift_config'][0];
        $insentive = 0;
        $finalRatingIncentiveArr = $this->delivery_ratings_insentive_config_model->calculateRatingIncentives($config, $ratingsArr);
        if ($daywiseTouchpoint['touch_points'] >= $config['min_touch_points']) {
            $insentive = $daywiseTouchpoint['touch_points'] * $config['amount_for_addtional_touch_point'];
        }
        $insentive+= $finalRatingIncentiveArr['total'];
        return [
            "insentive" => $insentive,
            "config" => $config,
            'rating_summary'=> $finalRatingIncentiveArr['summary']
        ];
    }

    // public function calculateInsentiveForUser($deliveryBoyPerformance)
    // {
    //     $this->load->model('delivery_boy_address_model');
    //     $this->load->model('delivery_insentive_config_model');
    //     $this->load->model('delivery_boy_performance_extraction_model');
    //     $insentive = 0;
    //     $deliveryBoyAddress = $this->delivery_boy_address_model->where([
    //         'user_id' => $deliveryBoyPerformance['delivery_boy_user_id']
    //     ])->get();
    //     $daywiseTouchpoints = $this->delivery_boy_performance_extraction_model->getDayWiseTouchpoints($deliveryBoyPerformance['id']);
    //     $config = $this->delivery_insentive_config_model->getInsentiveConfig($deliveryBoyAddress);
    //     $insentiveConfig = [];
    //     foreach ($daywiseTouchpoints as $daywiseTouchpoint) {
    //         $insentiveConfig = $this->getInsentives($config, $deliveryBoyPerformance, $daywiseTouchpoint); //, $daywiseTouchpoint['touch_points']
    //         $insentive += $insentiveConfig['insentive'];
    //     }
    //     if ($insentiveConfig && $insentiveConfig['config']) {
    //         if ($insentiveConfig['config']['max_limit'] < $insentive) {
    //             $insentive = $insentiveConfig['config']['max_limit'];
    //         }
    //     }
    //     $deliveryBoyPerformance['insentive'] = $insentive;
    //     return $deliveryBoyPerformance;
    // }

    public function calculateDayInsentiveForUser($deliveryBoyPerformance, $ratingsArr)
    {
        $this->load->model('delivery_boy_address_model');
        $this->load->model('delivery_insentive_config_model');
        $this->load->model('delivery_boy_performance_extraction_model');
        $insentive = 0;
        $deliveryBoyAddress = $this->delivery_boy_address_model->where([
            'user_id' => $deliveryBoyPerformance['delivery_boy_user_id']
        ])->get();
        $config = $this->delivery_insentive_config_model->getInsentiveConfig($deliveryBoyAddress);
        $insentivObj = [];
        $insentivObj = $this->getInsentives($config, $deliveryBoyPerformance, $deliveryBoyPerformance, $ratingsArr); //, $daywiseTouchpoint['touch_points']
        $insentive = $insentivObj['insentive'];
        if ($insentivObj && $insentivObj['config']) {
            if ($insentivObj['config']['max_limit'] < $insentive) {
                $insentive = $insentivObj['config']['max_limit'];
            }
        }
        $deliveryBoyPerformance['amount'] = $insentive;
        $deliveryBoyPerformance['ratings'] = $insentivObj['rating_summary'];
        return $deliveryBoyPerformance;
    }

    public function calculateInsentives($deliveryBoyPerformances)
    {
        foreach ($deliveryBoyPerformances as $key => $deliveryBoyPerformance) {
            $deliveryBoyPerformanceObj = $this->calculateInsentiveForUser($deliveryBoyPerformance);
            $deliveryBoyPerformances[$key] = $deliveryBoyPerformanceObj;
        }
        return $deliveryBoyPerformances;
    }
}
