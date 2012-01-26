<?php

	class Admin extends Controller 
	{	
		function index()
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
	}
	
?>