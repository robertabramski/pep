<?php
	
	/**
	 * PEP framework class. The PEP framework is a
	 * fork of the PIP framework by Gilbert Pellegrom.
	 *
	 * @author     Robert Abramski
	 * @license    MIT License
	 * @copyright  2012 Robert Abramski
	 * 
	 */
	class Pep
	{
		public static function init()
		{
			$controller = Pep::get_setting('default_controller');
			$action = 'index';
			$url = '';
			
			$request_url = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
			$script_url = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : '';
		    
			if($request_url != $script_url)
			{
				$url = trim(preg_replace('/'. str_replace('/', '\/', str_replace('index.php', '', $script_url)) .'/', '', $request_url, 1), '/');
			}
		    
			$segments = explode('/', $url);
			
			if(isset($segments[0]) && $segments[0] != '') $controller = $segments[0];
			if(isset($segments[1]) && $segments[1] != '') $action = $segments[1];
			
			$path = APP_DIR . 'controllers/' . $controller . '.php';
			
			if(file_exists($path))
			{
		        require_once($path);
			}
			else 
			{
		        $controller = Pep::get_setting('error_controller');
		        require_once(APP_DIR . 'controllers/' . $controller . '.php');
			}
		    
		    if(!method_exists($controller, $action))
		    {
		        $controller = Pep::get_setting('error_controller');
		        require_once(APP_DIR . 'controllers/' . $controller . '.php');
		        $action = 'index';
		    }
			
			$obj = new $controller;
		    die(call_user_func_array(array($obj, $action), array_slice($segments, 2)));
		}
		
		public static function redirect($loc)
		{
			header('Location: ' . Pep::get_setting('base_url') . $loc);
		}
		
		public static function print_q($data)
		{
			echo '<pre>'; print_r($data); echo '</pre>'; exit();
		}
		
		public static function get_setting($name)
		{
			require_once(APP_DIR . 'models/admin_m.php');
			$model = new Admin_m();
			
			return $model->get_setting($name);
		}
	}
	
?>