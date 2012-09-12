<?php

	class View 
	{
		private $template;
		private $theme;
		private $view;
		
		public function __construct($view)
		{
			$this->theme = Pep::get_setting('theme');
			$this->view = $view;
			
			if(empty($this->theme))
			{
				$this->template = APP_DIR . 'views/layouts/' . $view . '.php';
			}
			else
			{
				$this->template = THEME_DIR . $this->theme . '/layouts/' . $view . '.html';
				require_once(CORE_DIR . 'parser.php');
			}
		}
		
		public function render($data = null)
		{
			// Some basic output handling.
			if(!is_null($data)) array_walk_recursive($data, array($this, 'encode_output'));
			
			if(empty($this->theme))
			{
				$this->render_view($data);
			}
			else
			{
				if(file_exists($this->template))
				{
					$parser = new Parser();
					$buffer = $parser->parse(file_get_contents($this->template), $data, array($this, 'parse_template'));
					
					$minified = Pep::get_setting('minify');
					echo ($minified ? $this->minify($buffer) : $buffer);
				}
				else
				{
					// Fall back to application view if no theme file exists. 
					$this->template = APP_DIR .'views/layouts/'. $this->view .'.php';
					$this->render_view($data);
				}
			}
		}
		
		private function encode_output(&$item, $key)
		{
			$item = utf8_encode(stripslashes($item));
		}
		
		public function parse_template($name, $attributes, $content)
		{
			if($name == 'partial')
			{
				$parser = new Parser();
				return $parser->parse($this->theme_partial($attributes['name']));
			}
			else if($name == 'setting')
			{
				return Pep::get_setting($attributes['name']);
			}
			else if($name == 'lang')
			{
				$language = Pep::get_setting('language');
				
				if(!empty($language))
				{
					$file = APP_DIR . 'languages/' . strtolower($language) . '.php';
					
					if(file_exists($file))
					{
						require_once($file);
						return $lang[$attributes['name']];
					}
					else
					{
						Pep::show_error(sprintf('The language file %s.php does not exist.', $language));
					}
				}
			}
		}
		
		private function theme_partial($name)
		{
			$theme = Pep::get_setting('theme');
			
			$file = THEME_DIR . $theme . '/partials/' . $name . '.html';
			
			if(file_exists($file)) return file_get_contents($file);
			else Pep::show_error(sprintf('The theme partial file %s.html does not exist.', $name));
		}
		
		public function partial($name)
		{
			$file = APP_DIR . 'views/partials/' . $name . '.php';
			
			if(file_exists($file)) return $file;
			else Pep::show_error(sprintf('The view partial file %s.php does not exist.', $name));
		}
		
		private function minify($buffer)
		{
			$search = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s');
			$replace = array('>', '<', '\\1');
			return preg_replace($search, $replace, $buffer);
		}
		
		private function render_view($data = null)
		{
			$language = Pep::get_setting('language');
			
			if(!empty($language))
			{
				$file = APP_DIR . 'languages/' . strtolower($language) . '.php';
				
				if(file_exists($file)) include($file);
				else Pep::show_error(sprintf('The language file %s.php does not exist.', $language));
			}
			
			if($data) extract($data);
			
			if(file_exists($this->template))
			{
				$minified = Pep::get_setting('minify');
				$minified ? ob_start(array($this, 'minify')) : ob_start();
				require($this->template);
				ob_end_flush();
			}
			else
			{
				// No view exists at all.
				Pep::show_error(sprintf('The view file %s.php does not exist.', $this->view));
			}
		}
	}

?>