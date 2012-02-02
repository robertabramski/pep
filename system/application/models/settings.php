<?php
	
	class Settings extends Model
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->fields = array
			(
				'name' 	=> array('type' => 'label'),
				'value' => array('type' => 'text')
			);
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