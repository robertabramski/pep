<?php

	class Roles extends Model
	{
		public function __construct()
		{
			parent::__construct();

			$this->allow = array('admin');
			$this->creatable = true;
			$this->updateable = false;
			$this->deletable = false;
			$this->description = 'Add user roles.';
			$this->fields = array
			(
				'role_id' => array('type' => 'pk', 'name' => 'Key'), 
				'role' => array('type' => 'text', 'name' => 'Role', 'validate' => array('required', 'alpha'))
			);
		}
		
		public function get_roles()
		{
			$rows = $this->select('*');
			foreach($rows as $row) $roles[] = $row['role'];
			return $roles;
		}
	}

?>