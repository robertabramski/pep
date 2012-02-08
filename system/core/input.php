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
		}
		
		public function post($value = '', $sanitize = true)
		{
			if($sanitize) array_walk_recursive($_POST, array($this, 'sanitize_input'));
			return empty($value) ? $_POST : $_POST[$value];
		}
		
		public function has_post()
		{
			return !empty($_POST);
		}
		
		private function sanitize_input(&$item, $key)
		{
			$item = utf8_encode(strip_tags(addslashes($item)));
		}
	}
	
?>