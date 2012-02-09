<?php

	class Validate
	{
		private $results;
		private $messages = array
		(
			'alpha'					=> 'The %s field must be alphabet characters.',
			'alpha_num'				=> 'The %s field must be alphanumeric characters.',
			'alpha_num_dash'		=> 'The %s field must be alphanumeric characters, a dash or an underscore.',
			'alpha_num_plus'		=> 'The %s field must be alphanumeric characters, a space, a dash or an underscore.',
			'alpha_num_sp'			=> 'The %s field must be alphanumeric characters or a space.',
			'bool'					=> 'The %s field must evaluate to a boolean value.',
			'decimal'				=> 'The %s field must be a decimal number.',
			'email'					=> 'The %s field must be a valid email address.',
			'in_range'				=> 'The %s field must be within a specific number range.',
			'int'					=> 'The %s field must be an integer.',
			'ip'					=> 'The %s field must be a valid IP address.',
			'numeric'				=> 'The %s field must be numeric.',
			'required'				=> 'The %s field is required.',
			'url'					=> 'The %s field must be a valid URL.'
		);
		
		public function run($rules)
		{
			if(is_array($rules) && !empty($rules))
			{
				$results = array();
				
				foreach($rules as $key => $value)
				{
					$input = $_POST[$key];
					
					if(is_array($value) && !empty($value))
					{
						foreach($value as $rule)
						{
							if(method_exists($this, $rule))
							{
								$result = $this->$rule($input);
								$result ? $message = '' : $message = sprintf($this->messages[$rule], $key);
								$results[] = array('rule' => $rule, 'result' => $result, 'message' => $message);
							}
							else
							{
								show_error(sprintf('The validation function %s does not exist.', $rule));
							}
						}
					}
				}
				
				$this->results = $results;
				
				foreach($results as $result)
				{
					if(!$result['result']) return false;
				}
			}
			
			return true;
		}
		
		public function get_results()
		{
			return $this->results;
		}
		
		public function set_message($rule, $message)
		{
			$this->messages[$rule] = $message;
		}
		
		public function required($value)
		{
			if(!is_array($value))
			{
				return (trim($value) == '') ? false : true;
			}
			else
			{
				return (!empty($value));
			}
		}
		
		public function email($value)
		{
			if(empty($value)) return true;
			$valid = filter_var($value, FILTER_VALIDATE_EMAIL);
			return $valid ? true : false;
		}
		
		public function url($value)
		{
			if(empty($value)) return true;
			$valid = filter_var($value, FILTER_VALIDATE_URL);
			return $valid ? true : false;		
		}
		
		public function ip($value)
		{
			if(empty($value)) return true;
			$valid = filter_var($value, FILTER_VALIDATE_IP);
			return $valid ? true : false;
		}
		
		public function bool($value)
		{
			$valid = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
			return $valid ? true : false;
		}
		
		public function alpha($value)
		{
			if(empty($value)) return true;
			$options = array('options' => array('regexp' => "/^[a-z]+$/i"));
			$valid = filter_var($value, FILTER_VALIDATE_REGEXP, $options);
			return $valid ? true : false;
		}
		
		public function numeric($value)
		{
			if(empty($value)) return true;
			$options = array('options' => array('regexp' => "/^[\-+]?[0-9]*\.?[0-9]+$/"));
			$valid = filter_var($value, FILTER_VALIDATE_REGEXP, $options);
			return $valid ? true : false;
		}
		
		public function decimal($value)
		{
			if(empty($value)) return true;
			$options = array('options' => array('regexp' => "/^[\-+]?[0-9]+\.[0-9]+$/"));
			$valid = filter_var($value, FILTER_VALIDATE_REGEXP, $options);
			return $valid ? true : false;
		}
		
		public function alpha_num($value)
		{
			if(empty($value)) return true;
			$options = array('options' => array('regexp' => '/^([a-z0-9])+$/i'));
			$valid = filter_var($value, FILTER_VALIDATE_REGEXP, $options);
			return $valid ? true : false;
		}
		
		public function alpha_num_dash($value)
		{
			if(empty($value)) return true;
			$options = array('options' => array('regexp' => '/^([a-z0-9_-])+$/i'));
			$valid = filter_var($value, FILTER_VALIDATE_REGEXP, $options);
			return $valid ? true : false;
		}
		
		public function alpha_num_sp($value)
		{
			if(empty($value)) return true;
			$options = array('options' => array('regexp' => '/^([a-z0-9 ])+$/i'));
			$valid = filter_var($value, FILTER_VALIDATE_REGEXP, $options);
			return $valid ? true : false;
		}
		
		public function alpha_num_plus($value)
		{
			if(empty($value)) return true;
			$options = array('options' => array('regexp' => '/^([a-z0-9_- ])+$/i'));
			$valid = filter_var($value, FILTER_VALIDATE_REGEXP, $options);
			return $valid ? true : false;
		}
		
		public function int($value)
		{
			if(empty($value)) return true;
			$options = array('options' => array('regexp' => '/^[\-+]?[0-9]+$/'));
			$valid = filter_var($value, FILTER_VALIDATE_REGEXP, $options);
			return $valid ? true : false;
		}
		
		public function in_range($value, $min, $max)
		{
			if(empty($value)) return true;
			$options = array('options' => array('min_range' => $min, 'max_range' => $max));
			$valid = filter_var($value, FILTER_VALIDATE_INT, $options);
			return $valid ? true : false;
		}
	}
	
?>