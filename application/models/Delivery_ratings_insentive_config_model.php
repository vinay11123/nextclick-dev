<?php

class Delivery_ratings_insentive_config_model extends MY_Model {

    public function __construct()
    {
        parent::__construct();
        $this->table = 'delivery_ratings_insentive_config';
        $this->primary_key = 'id';
        
        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';
        
       $this->_config();
    //     $this->_form();
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
    }
    public function updateData($id,$data) {
        $this->update($data, $id);
        return true;
    }

    public function upsertData($InsentiveID, $rating, $data) {
        $delivery_insentive_config = $this->where([
            'insentive_id' => $InsentiveID,
            'rating' => $rating
        ])->get();
        if($delivery_insentive_config){
            $this->updateData($delivery_insentive_config['id'], $data);
        }else{
            $data['insentive_id'] = $InsentiveID;
            $data['rating'] = $rating;
            $this->insert($data);
        }
        return true;
    }

    public function calculateRatingIncentives($config, $ratingsArr){
        $finalRatingConfig = [];
        $totalAmnt = 0;
        $ratingConfigArr = $this->where([
            'insentive_id'=> $config['insentive_id']
        ])->get_all();
        foreach($ratingConfigArr as $key =>$ratingConfig){
            $finalRatingConfig[(int) $ratingConfig['rating']] = $ratingConfig['amount'];
        }
        ksort($finalRatingConfig);
        $ratingAmount = [];
        foreach($ratingsArr as $key =>$rating){
            $ratingVal = round($rating['rating']);
            $ratingVal= ($ratingVal<1) ? Null : $ratingVal;
            $countVal = $rating['count'];
            $amount = 0;
            if($ratingVal && $finalRatingConfig[$ratingVal]){
                $amount = $countVal*$finalRatingConfig[$ratingVal];
            }
            $totalAmnt+= $amount;
            array_push($ratingAmount, [
                'rating' => $ratingVal,
                'count' => $countVal,
                'amount' => $amount
            ]);
        }
        return ['total' => $totalAmnt, 'summary'=> $ratingAmount];
    }

}