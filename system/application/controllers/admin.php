<?php

	class Admin extends Controller 
	{	
		public function index()
		{
			$model = $this->load_model('admin_m');
			
			$data = array
			(
				'title' 	=> 'Admin',
				'message' 	=> 'Welcome to admin.',
				'settings'	=> $model->get_settings(),
				'admin'		=> $model->get_user(1),
			);
		
			$template = $this->load_view('admin');
			$template->render($data);
		}
		
		public function login()
		{
			if($this->auth->logged_in())
			{
				echo 'You are logged in.';
			}
			else
			{
				echo 'You must login.';
			}
			
			Pep::print_q($this->auth->decrypt($_COOKIE['bubba']));
		}
		
		public function submit($user, $pass)
		{
			$this->auth->login($user, $pass);
		}
		
		public function logout()
		{
			$this->auth->logout();
		}
	}
	
?>