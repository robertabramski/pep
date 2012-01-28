<?php

	class Session
	{
		public function __construct()
		{
			session_name(Pep::get_setting('session_name'));
			session_start();
		}
		
		public function set($name, $value)
		{
			$_SESSION[$name] = $value;
		}
		
		public function get($name)
		{
			return isset($_SESSION[$name]) ? $_SESSION[$name] : false;
		}
		
		public function destroy($name)
		{
			unset($_SESSION[$name]);
		}
		
		public function destroy_all()
		{
			$_SESSION = array();
			session_destroy();
		}
	}
	
?>