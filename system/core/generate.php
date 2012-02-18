<?php

	class Generate
	{
		public function table($name, $options = null, $with_model = true)
		{
			$options = array
			(
				'name' => ucfirst($name),
				'creatable' => 'true',
				'updateable' => 'false',
				'deletable' => 'true',
				'description' => 'This is the description.'
			);
			
			if($with_model)
			{
				chdir(APP_DIR . 'models/');
				require_once(CORE_DIR . 'parser.php');
				
				$parser = new Parser();
				$data = html_entity_decode($parser->parse($this->get_model_contents(), $options));
				
				$model_generated = file_put_contents($name . '.php', $data);
			}
			
			return $model_generated ? true : false;
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
						
						"\t\t\t" . '$this->creatable' . " = {{creatable}};\n" .
						"\t\t\t" . '$this->updateable' . " = {{updateable}};\n" .
						"\t\t\t" . '$this->deletable' . " = {{deletable}};\n" .
						"\t\t\t" . '$this->description' . " = '{{description}}';\n" .
						"\t\t\t" . '$this->fields' . " = array\n" .
						"\t\t\t(\n" .
						"\t\t\t\t // Add your fields to display in the admin interface here.\n" . 
						"\t\t\t);\n" .
					"\t\t}\n" .
				"\t}\n\n" .
				
			"?>";
		}
		
	}
	
?>