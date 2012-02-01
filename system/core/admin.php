<?php

	class Admin extends Controller 
	{
		public function __construct()
		{
			parent::__construct();
			
			// Load helpers in the constructor.
			$this->session = $this->load->helper('session');
			$this->validate = $this->load->helper('validate');
		} 
		
		public function index()
		{
			if($this->auth->logged_in())
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
			else
			{
				redirect('admin/login');
			}
		}
		
		public function login($action = '')
		{
			// If segment is login submission.
			if($action == 'submit')
			{
				$rules = array
				(
					// Rule array is the POST field name and an array of validate functions to run.
					'user' => array('required', 'alpha_num'),
					'pass' => array('required', 'alpha_num_dash')
				);
				
				// Custom messages can be set before running validation.
				$this->validate->set_message('required', 'The %s field cannot be left blank.');
				
				if($this->validate->run($rules))
				{
					// Get POST variables.
					$user = $this->input->post('user');
					$pass = $this->input->post('pass');
					
					// Check credentials.
					if($this->auth->login($user, $pass))
					{
						redirect('admin');
					}
					else
					{
						// Login failed. Set session data to display on redirect.
						$this->session->set('user', $user);
						$this->session->set('failed', 'The login was invalid.');
						
						redirect('admin/login');
					}
				}
				else
				{
					// Validation failed. Get validation results array.
					$results = $this->validate->get_results();
					
					// Set session data. Serialize validation results for display on redirect.
					$this->session->set('failed', 'Validation failed. See errors below.');
					$this->session->set('errors', serialize($results));
					
					redirect('admin/login');
				}
			}
			else
			{
				if($this->auth->logged_in())
				{
					// Already logged in.
					redirect('admin');
				}
				else
				{
					// Retrieve any session variables.
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
					
					// Session data no longer needed.
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