<?php
	
	class Settings extends Model
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->deletable = false;
			$this->fields = array
			(
				'setting_id'	=> array('type' => 'pk'),
				'name' 			=> array('type' => 'label', 'name' => 'Name'),
				'value' 		=> array('type' => 'text', 'name' => 'Value')
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