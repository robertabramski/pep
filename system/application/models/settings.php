<?php
	
	class Settings extends Model
	{
		public function __construct()
		{
			$this->menu = 'Settings';
			$this->table = 'settings';
			$this->allow = array('admin', 'user');
			
			$this->fields = array
			(
				'name' 	=> array('type' => 'label'),
				'value' => array('type' => 'text')
			);
			
			parent::__construct();
		}
		
		public function get_setting($name)
		{
			$result = $this->select('value', array('name' => $name), 1);
			return $result[0]['value'];
		}
		
		public function get_settings()
		{
			return $this->select('*');
		}
	}
	
?>