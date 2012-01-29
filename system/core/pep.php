<?php
	
	/**
	 * The PEP framework class. The PEP framework is a fork of the PIP framework by Gilbert Pellegrom.
	 * This class holds the initialization logic for the system. It also contains static functions
	 * used throughout core classes for dealing with user and settings data contained in the database.
	 *
	 * @author     Robert Abramski
	 * @license    MIT License
	 * @copyright  2012 Robert Abramski
	 * 
	 */
	class Pep
	{
		private static $instance;
		private static $admin;
		private static $authed_user;
		
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
			require_once(APP_DIR . 'models/admin.php');
			self::$admin = new models\Admin();
			
			// Set session for helper if loaded.
			$session_name = Pep::get_setting('session_name');
			
			if(!empty($session_name))
			{
				session_name($session_name);
				session_start();
			}
			
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
		 * Returns the site url based on the base url set.
		 * 
		 * @access	public
		 * @see 	base_url()
		 * @param 	string $loc The location segment of segments to append to the base url.
		 * @return 	string
		 * 
		 */
		public static function site_url($loc = '')
		{
			return rtrim(self::get_setting('base_url'), '/') . '/' . $loc;
		}
		
		/**
		 * Returns the base url as set in the settings section of the admin.
		 * 
		 * @access	public
		 * @return	string
		 * 
		 */
		public static function base_url()
		{
			return rtrim(self::get_setting('base_url'), '/') . '/';
		}
		
		/**
		 * Returns the url segement at the requested position. 
		 * 
		 * @param 	int 	$seg 	The position of the segment to return.
		 * @return 	string	The segment at the specified position.
		 * 
		 */
		public static function segment($seg)
		{
			if(!is_int($seg)) return false;
			
			$parts = explode('/', $_SERVER['REQUEST_URI']);
	        return isset($parts[$seg]) ? $parts[$seg] : false;
		}
		
		/**
		 * Executes the PHP print_r function in a pre tag and exits. This is 
		 * useful for debugging purposes. 
		 * 
		 * @access 	public
		 * @param 	mixed 	$data	The data to print out.
		 * @return 	void
		 * 
		 */
		public static function print_q($data)
		{
			echo '<pre>'; print_r($data); echo '</pre>'; exit();
		}
		
		/**
		 * Authenticates a user. The password should be hashed with md5
		 * before passing in as an argument.
		 * 
		 * @param 	string 	$username		The user to authenticate.
		 * @param 	string 	$md5password	The hashed password to authenticate.
		 * @return 	bool	True if valid user, otherwise false.
		 * 
		 */
		public static function auth_user($username, $md5pass)
		{
			$users = self::$admin->get_users();

			foreach($users as $user)
			{
				if($user['user'] == $username && $user['pass'] == $md5pass)
				{
					self::$authed_user = $user;
					return true;
				}
			}
			
			return false;
		}
		
		/**
		 * Gets the active user currently logged in as an array.
		 * 
		 * @access	public
		 * @return	mixed	The user as an array or false.
		 * 
		 */
		public static function get_authed_user()
		{
			return self::$authed_user ? self::$authed_user : false;
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
			return self::$admin->get_setting($name);
		}
	}
	
?>