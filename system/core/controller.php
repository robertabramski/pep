<?php
	
	class Controller 
	{
		protected $auth;
		protected $input;
		protected $load;
		
		public function __construct()
		{
			$this->auth = new Auth();
			$this->input = new Input();
			$this->load = new Load();
		}
	}

?>