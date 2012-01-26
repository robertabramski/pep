<?php

	class Controller 
	{
		public function load_model($name)
		{
			require_once(APP_DIR . 'models/' . strtolower($name) . '.php');
			$model = new $name;
			return $model;
		}
		
		public function load_view($name)
		{
			$view = new View($name);
			return $view;
		}
		
		public function load_plugin($name)
		{
			require_once(APP_DIR.'plugins/'.strtolower($name).'.php');
		}
		
		public function load_lang($name)
		{
			require_once(APP_DIR . 'languages/' . strtolower($name) . '.php');
			return $lang;
		}
		
		public function load_helper($name)
		{
			require_once(APP_DIR . 'helpers/' . strtolower($name) . '.php');
			$helper = new $name;
			return $helper;
		}
	}

?>