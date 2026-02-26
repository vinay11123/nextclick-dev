<?php

class Delivery_boy_performance_extraction_rating_model extends MY_Model {

    public function __construct()
    {
        parent::__construct();
        $this->table = 'delivery_boy_performance_extraction_ratings';
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
        
    }
    public function updateData($id,$data) {
        $this->update($data, $id);
        return true;
    }

    public function upsertData($performanceExtrationID, $rating, $data) {
        $delivery_insentive_extraction = $this->where([
            'performance_extraction_id' => $performanceExtrationID,
            'rating' => $rating
        ])->get();
        if($delivery_insentive_extraction){
            $this->updateData($delivery_insentive_extraction['id'], $data);
        }else{
            $data['performance_extraction_id'] = $performanceExtrationID;
            $data['rating'] = $rating;
            $this->insert($data);
        }
        return $data;
    }

}