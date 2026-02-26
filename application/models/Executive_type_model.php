<?php
class Executive_type_model extends MY_Model
{
    public $id;
	public $executive_type;

	public function __construct()
	{
		parent::__construct();
		$this->table = 'executive_type';
		$this->primary_key = 'id';

		

		//$this->_config();
		//$this->_form();
		//$this->_relations();

		
	}
}
    ?>