<?php

	class URL
	{
		function base_url()
		{
			return Pep::get_setting('base_url');
		}
		
		function segment($seg)
		{
			$url = trim($_GET['_url'], '/');
			$parts = explode('/', $url);
			
	        return isset($parts[$seg]) ? $parts[$seg] : false;
		}
	}

?>