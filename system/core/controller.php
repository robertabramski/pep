<?php

	class Controller 
	{
		public function load_model($name)
		{
			$file = APP_DIR . 'models/' . strtolower($name) . '.php';
			
			if(file_exists($file))
			{
				require_once($file);
				$model = new $name;
				return $model;
			}
			else
			{
				Pep::show_error(sprintf('The model file %s.php failed to load.', $name));
			}
		}
		
		public function load_view($name)
		{
			$view = new View($name);
			return $view;
		}
		
		public function load_plugin($name)
		{
			$file = APP_DIR . 'plugins/' . strtolower($name) . '.php';
			
			if(file_exists($file)) require_once($file);
			else Pep::show_error(sprintf('The plugin file %s.php failed to load.', $name));
		}
		
		public function load_lang($name)
		{
			$file = APP_DIR . 'languages/' . strtolower($name) . '.php';
			
			if(file_exists($file)) return $lang;
			else Pep::show_error(sprintf('The language file %s.php failed to load.', $name));
		}
		
		public function load_helper($name)
		{
			$file = APP_DIR . 'helpers/' . strtolower($name) . '.php';
			
			if(file_exists($file))
			{
				require_once($file);
				$helper = new $name;
				return $helper;
			}
			else
			{
				Pep::show_error(sprintf('The helper file %s.php failed to load.', $name));
			}
		}
	}

?>