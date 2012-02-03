<?php

	class Input
	{
		public function __construct()
		{
			unset($_GET);
			
			if(get_magic_quotes_gpc())
			{
				$_POST = array_map('stripslashes', $_POST);
			}
			
			foreach($_POST as $key => $value)
			{
				$_POST[$key] = $this->sanitize($value);
			}
		}
		
		public function post($value = '')
		{
			return empty($value) ? $_POST : $_POST[$value];
		}
		
		public function has_post()
		{
			return !empty($_POST);
		}
		
		private function sanitize($value)
		{
			if(is_array($value))
			{
				//TODO: Sanitize this recursively.
				return $value;
			}
			
			return strip_tags(addslashes($value));
		}
	}
	
?>