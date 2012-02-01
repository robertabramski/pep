<?php

	namespace models;

	class Admin extends Model
	{
		public function get_setting($name)
		{
			$this->from('settings');
			$result = $this->select('value', array('name' => $name), 1);
			return $result[0]['value'];
		}
		
		public function get_settings()
		{
			$this->from('settings');
			return $this->select('*');
		}
		
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
