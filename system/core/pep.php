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
		private static $instance;
		private static $admin_m;
		
		/**
		 * Initializes the PEP framework.
		 * 
		 * @access public
		 * @return void
		 * 
		 */
		public static function init()
		{
			if(!self::$instance) self::$instance = new Pep();
		}
		
		private function __construct()
		{
			// Load admin model and store instance.
			require_once(APP_DIR . 'models/admin_m.php');
			self::$admin_m = new Admin_m();
			
			// Set defaults.
			$controller = self::get_setting('default_controller');
			$action = 'index';
			$url = '';
			
			// Get request and scripts urls.
			$request_url = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
			$script_url = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : '';
		    
			if($request_url != $script_url)
			{
				// Trim url path of its slash.
				$url = trim(preg_replace('/'. str_replace('/', '\/', str_replace('index.php', '', $script_url)) .'/', '', $request_url, 1), '/');
			}
		    
			// Get url segments.
			$segments = explode('/', $url);
			
			// Attempt to get controller and action segments.
			if(isset($segments[0]) && $segments[0] != '') $controller = $segments[0];
			if(isset($segments[1]) && $segments[1] != '') $action = $segments[1];
			
			// Get controller.
			$path = APP_DIR . 'controllers/' . $controller . '.php';
			
			if(file_exists($path))
			{
		        require_once($path);
			}
			else 
			{
		        $controller = self::get_setting('error_controller');
		        require_once(APP_DIR . 'controllers/' . $controller . '.php');
			}
		    
			// Check for action.
		    if(!method_exists($controller, $action))
		    {
		        $controller = self::get_setting('error_controller');
		        require_once(APP_DIR . 'controllers/' . $controller . '.php');
		        $action = 'index';
		    }
			
		    // Create controller and call page.
			$obj = new $controller;
		    die(call_user_func_array(array($obj, $action), array_slice($segments, 2)));
		}
		
		/**
		 * Shows an error. Attempts to use a theme to show error and falls back to
		 * the error view. 
		 * 
		 * @access public
		 * @param 	string 	$message	The message to be displayed.
		 * @param 	string 	$title		The page title to display.
		 * @return	void
		 * 
		 */
		public static function show_error($message, $title = 'Error')
		{
			$view = APP_DIR . 'views/error.php';
			$theme = THEME_DIR . self::get_setting('theme') . '/error.html';
			
			if(file_exists($theme))
			{
				require_once(ROOT_DIR.'system/core/parser.php');
				
				// Parse the error in the theme.
				$data = array('title' => $title, 'message' => $message);
				$parser = new Parser();
				die($parser->parse(file_get_contents($theme), $data, 'View::parse_callback'));
			}
			else
			{
				// Just show the view.
				die(include($view));
			}
		}
		
		/**
		 * Redirects to a page in the site.
		 * 
		 * @access 	public
		 * @param 	string	$loc	The location segments to redirect to.
		 * @return	void
		 * 
		 */
		public static function redirect($loc)
		{
			header('Location: ' . rtrim(self::get_setting('base_url'), '/') . '/' . $loc);
		}
		
		/**
		 * Authenticates a user. The password should be hashed with md5
		 * before passing in as an argument.
		 * 
		 * @param string $username	The user to authenticate.
		 * @param string $password	The hashed password to authenticate.
		 * 
		 */
		public static function auth_user($username, $password)
		{
			$users = self::$admin_m->get_users();

			foreach($users as $user)
			{
				if($user['user'] == $username && $user['pass'] == $password) return true;
			}
			
			return false;
		}
		
		/**
		 * Returns a setting value from the database by name.
		 * 
		 * @access 	public
		 * @param 	string 	$name	The name of value to return.
		 * @return 	mixed
		 * 
		 */
		public static function get_setting($name)
		{
			return self::$admin_m->get_setting($name);
		}
	}
	
?>