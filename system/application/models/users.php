<?php

	class Users extends Model
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->description = 'Add, remove and edit users.';
			$this->fields = array
			(
				'user_id' 	=> array('type' => 'pk', 'name' => 'Key'),
				'user' 		=> array('type' => 'text', 'name' => 'User', 'validate' => array('required', 'alpha_num')),
				'pass' 		=> array('type' => 'password', 'name' => 'Pass', 'validate' => array('alpha_num_dash')),
				'role' 		=> array('type' => 'select', 'name' => 'Role', 'options' => Pep::get_roles())
			);
		}
		
		public function get_user($id)
		{
			$result = $this->select('*', array('user_id' => $id), 1);
			return $result[0];
		}
		
		public function get_users()
		{
			return $this->select('*');
		}
	}
	
?>