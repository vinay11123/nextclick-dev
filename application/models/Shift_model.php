<?php

class Shift_model extends MY_Model {

    public function getAll() {
        return $this->db->get('shifts')->result();
    }

    public function getDataById($id) {
        $this->db->where('id', $id);
        return $this->db->get('shifts')->result();
    }

    public function changeStatus($id) {
        $table=$this->getDataById($id);
             if($table[0]->status==0 || empty($table[0]->status))
             {
                $this->update(array('status' => '1'), $id);
                return "Activated";
             }else{
                $this->update(array('status' => '0'), $id);
                return "Deactivated";
             }
    }

}