<?php

	namespace models;
	
	class Users extends Model
	{
		public function get_user($id)
		{
			$this->from('users');
			$result = $this->select('*', array('user_id' => $id), 1);
			return $result[0];
		}
		
		public function get_users()
		{
			$this->from('users');
			return $this->select('*');
		}
	}
	
?>