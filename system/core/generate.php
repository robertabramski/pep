<?php

	class Generate
	{
		private function gen($name, $options, $contents)
		{
			require_once(CORE_DIR . 'parser.php');
			
			$parser = new Parser();
			$data = html_entity_decode($parser->parse($contents, $options));
			
			return file_put_contents(strtolower($name). '.php', $data) ? true : false;
		}
		
		public function model($name, $options)
		{
			chdir(APP_DIR . 'models/');
			return $this->gen($name, $options, $this->get_model_contents());
		}
		
		private function get_model_contents()
		{
			return 
			
			"<?php\n\n" .
					
				"\tclass {{name}} extends Model\n" .
				"\t{\n" .
					"\t\tpublic function __construct()\n" .
					"\t\t{\n" .
						"\t\t\tparent::__construct();\n\n" .
						
						"\t\t\t" . '$this->allow' . " = {{allow}};\n" .
						"\t\t\t" . '$this->creatable' . " = {{creatable}};\n" .
						"\t\t\t" . '$this->updateable' . " = {{updateable}};\n" .
						"\t\t\t" . '$this->deletable' . " = {{deletable}};\n" .
						"\t\t\t" . '$this->description' . " = '{{description}}';\n" .
						"\t\t\t" . '$this->fields' . " = array\n" .
						"\t\t\t(\n" .
						"\t\t\t\t/**\n" .
						"\t\t\t\t * Add your fields to display in the admin interface here. See the examples below.\n" . 
						"\t\t\t\t * \n" . 
						"\t\t\t\t * 'example_id' 		=> array('type' => 'pk', 'name' => 'Key'), \n" . 
						"\t\t\t\t * 'example_value' 		=> array('type' => 'text', 'name' => 'Value', 'validate' => array('required', 'alpha_num')), \n" . 
						"\t\t\t\t * 'example_options'	=> array('type' => 'select', 'name' => 'Options', 'options' => array('option1', 'option2')) \n" . 
						"\t\t\t\t * \n" . 
						"\t\t\t\t * Parameters are input as key/value pairs. Valid key types are type, name and validate. \n" . 
						"\t\t\t\t * The type option determines what type of form input is displayed in the admin interface. \n" . 
						"\t\t\t\t * The following are the supported types. If no type key is given, no input is displayed.\n" . 
						"\t\t\t\t * \n" . 
						"\t\t\t\t * pk			A required type which refers to the primary key of the table.\n" . 
						"\t\t\t\t * select		Displays a select form input. An extra key of options is requires for this type.\n" . 
						"\t\t\t\t * 				See the above example_options for proper usage.\n" . 
						"\t\t\t\t * checkbox		Displays a checkbox form input. This value saves as either the string value\n" . 
						"\t\t\t\t * 				of 'on' or 'off' in the database.\n" . 
						"\t\t\t\t * text			Displays a text input.\n" . 
						"\t\t\t\t * password		Displays a password form input. Leaving this blank will keep the same password.\n" . 
						"\t\t\t\t * textarea		Displays a textarea form input.\n" . 
						"\t\t\t\t * none			Displays plain text on updates and a text form input on creation.\n" . 
						"\t\t\t\t * \n" . 
						"\t\t\t\t * The name key will set the name of the column in the admin. Leaving this blank will use the \n" . 
						"\t\t\t\t * column name for the database.\n" . 
						"\t\t\t\t * \n" . 
						"\t\t\t\t * The validate key will set validation functions to run when the form is submitted. See the \n" . 
						"\t\t\t\t * above example_value for proper usage. This validation array can contain any validation function \n" . 
						"\t\t\t\t * in the validate helper.\n" . 
						"\t\t\t\t * \n" . 
						"\t\t\t\t */\n" . 
						"\t\t\t\t \n" . 
						"\t\t\t\t" . "{{fields}}\n" .
						"\t\t\t);\n" .
					"\t\t}\n" .
				"\t}\n\n" .
				
			"?>";
		}
		
	}
	
?>