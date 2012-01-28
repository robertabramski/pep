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
		
		public function post($value)
		{
			return addslashes($_POST[$value]);
		}
	}
	
?>