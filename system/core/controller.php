<?php
	
	class Controller 
	{
		protected $auth;
		protected $input;
		protected $session;
		protected $load;
		
		public function __construct()
		{
			$this->auth = new Auth();
			$this->input = new Input();
			$this->session = new Session();
			$this->load = new Load();
		}
	}

?>