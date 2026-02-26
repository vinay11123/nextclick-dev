<?php

class Product_search_history_model extends MY_Model
{
    public  $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'product_search_history';
        $this->primary_key = 'id';
        
     }
  

}

