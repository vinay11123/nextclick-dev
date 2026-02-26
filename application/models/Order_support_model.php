<?php

class Order_support_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
         $this->table = 'order_support';
        $this->primary_key = 'id';
        $this->foreign_key = 'order_id';

       $this->_relations();
    }


    public function _relations(){
        $this->has_one['order'] = array('Food_orders_model', 'id', 'order_id');
        $this->has_one['from'] = array('User_model', 'id', 'from_id');
        $this->has_one['to'] = array('User_model', 'id', 'to_id');
    }
    function get_support_chat(){
        $this->db->select('s.id, s.order_id, s.from_id,s.to_id,s.message,s.read_status,s.created_at');
        $this->db->from('order_support s');
        $this->db->where('s.row_status',1);
        $this->db->order_by('s.id', 'DESC');
        return $query = $this->db->get();
    }
    function get_support_chat_box($u_id,$login_id){
        $array1=array('s.from_id'=>$login_id,'s.to_id'=>$u_id);
        $array2=array('s.to_id'=>$login_id,'s.from_id'=>$u_id);
        $where='(s.from_id ='.$login_id .' AND '.'s.to_id = '.$u_id.') OR (s.to_id = '.$login_id.' AND s.from_id = '.$u_id.')';
        $this->db->select('s.id, s.from_id,s.to_id,s.message,s.read_status,s.created_at');
        $this->db->from('order_support s');
        $this->db->where($where);
        $this->db->where('s.row_status',1);
        $this->db->order_by('s.id', 'DESC');
        return $query = $this->db->get();            
    }
    function get_support_chat_unread_c($id,$login_id){
        $this->db->where('to_id',$login_id);
        $this->db->where('order_id',$id);
        $this->db->where('read_status',2);
        return $query = $this->db->get('order_support')->num_rows();
    }
     function get_type_name_by_where($type, $where_column = 'id', $type_id = '', $field = 'name')
    {
        if ($type_id != '') {
            $l = $this->db->get_where($type, array($where_column => $type_id));
            $n = $l->num_rows();
            if ($n > 0) {
                return $l->row()->$field;
            }else{
                return FALSE;
            }
        }
    }
      function get_image_url($id = '') {
         if (file_exists('uploads/users/'. $id . '.jpg')){
            $image_url ='assets/uploads/users/'. $id . '.jpg';
        }else{
            $image_url ='assets/img/user.png';
        }
        return $image_url;
    }
}

