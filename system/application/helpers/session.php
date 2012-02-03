<?php

	/**
	 * Helps handle session data. Session name must not be blank in the 
	 * config for this helper to work.
	 * 
	 * @author robertabramski
	 *
	 */
	class Session
	{
		public function set($name, $value)
		{
			$_SESSION[$name] = $value;
		}
		
		public function get($name)
		{
			return isset($_SESSION[$name]) ? $_SESSION[$name] : false;
		}
		
		public function delete($name)
		{
			unset($_SESSION[$name]);
		}
		
		public function destroy()
		{
			if(isset($_SESSION))
			{
				$_SESSION = array();
				session_destroy();
			}
		}
	}
	
?>