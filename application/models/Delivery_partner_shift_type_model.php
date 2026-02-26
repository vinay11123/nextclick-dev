<?php

class Delivery_partner_shift_type_model extends MY_Model
{
    public $rules , $user_id;
    public $id;
	public $executive_type;

	public function __construct()
	{
		parent::__construct();
		$this->table = 'shifts';
		$this->primary_key = 'id';
	}
}

