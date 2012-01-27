<?php

	class URL
	{
		public function site_url($loc = '')
		{
			return rtrim(Pep::get_setting('base_url'), '/') . '/' . $loc;
		}
		
		public function base_url()
		{
			return rtrim(Pep::get_setting('base_url'), '/') . '/';
		}
		
		public function segment($seg)
		{
			if(!is_int($seg)) return false;
			
			$parts = explode('/', $_SERVER['REQUEST_URI']);
	        return isset($parts[$seg]) ? $parts[$seg] : false;
		}
	}

?>