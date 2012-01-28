<?php

	class Validate
	{
		public function email($value)
		{
			$valid = filter_var($value, FILTER_VALIDATE_EMAIL);
			return $valid ? true : false;  
		}
		
		public function url($value)
		{
			$valid = filter_var($value, FILTER_VALIDATE_URL);
			return $valid ? true : false;			
		}
	}
	
?>