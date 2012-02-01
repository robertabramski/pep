<?php
	
	namespace models;
	
	class Settings extends Model
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
	}
	
?>