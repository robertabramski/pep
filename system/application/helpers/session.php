<?php

	class Session
	{
		function start()
		{
			session_start();
		}
		
		function set($key, $val)
		{
			$_SESSION["$key"] = $val;
		}
		
		function get($key)
		{
			return $_SESSION["$key"];
		}
		
		function destroy()
		{
			session_destroy();
		}
	}

?>