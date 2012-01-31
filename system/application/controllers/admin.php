<?php

	class Admin extends Controller 
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->session = $this->load->helper('session');
			$this->validate = $this->load->helper('validate');
		} 
		
		public function index()
		{
			if(!$this->auth->logged_in())
			{
				redirect('admin/login');
			}
			else
			{
				$model = $this->load->model('admin');
				
				$data = array
				(
					'title' 	=> 'Admin',
					'message' 	=> 'Welcome to admin.',
					'settings'	=> $model->get_settings(),
					'user'		=> $this->auth->authed_user('user')
				);
				
				$template = $this->load->view('admin');
				$template->render($data);
			}
		}
		
		public function login($action = '')
		{
			if($action == 'submit')
			{
				$user = $this->input->post('user');
				$pass = $this->input->post('pass');
				
				$rules = array
				(
					'user' => array('required', 'alpha_num'),
					'pass' => array('required', 'alpha_num_dash')
				);
				
				$this->validate->set_message('required', 'The %s field cannot be left blank.');
				
				if($this->validate->run($rules))
				{
					if($this->auth->login($user, $pass))
					{
						redirect('admin');
					}
					else
					{
						$this->session->set('user', $user);
						$this->session->set('failed', 'The login was invalid.');
						
						redirect('admin/login');
					}
				}
				else
				{
					$results = $this->validate->get_results();
					
					$this->session->set('failed', 'Validation failed. See errors below.');
					$this->session->set('errors', serialize($results));
					
					redirect('admin/login');
				}
			}
			else
			{
				if($this->auth->logged_in())
				{
					redirect('admin');
				}
				else
				{
					$failed = $this->session->get('failed');
					$errors = $this->session->get('errors');
					
					$data = array
					(
						'title' 	=> 'Login',
						'message' 	=> empty($failed) ? 'You are not logged in.' : $failed,
						'errors'	=> empty($errors) ? null : unserialize($errors),
						'user'		=> $this->session->get('user')
					);
					
					$template = $this->load->view('login');
					$template->render($data);
					
					$this->session->destroy_all();
				}
			}
		}
		
		public function logout()
		{
			$this->auth->logout();
			redirect('admin/login');
		}
	}
	
?>