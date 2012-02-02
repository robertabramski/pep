<?php

	class Admin extends Controller 
	{
		public function __construct()
		{
			parent::__construct();
			
			// Load helpers in the constructor.
			$this->session = $this->load->helper('session');
			$this->validate = $this->load->helper('validate');
			$this->string = $this->load->helper('string');
		} 
		
		public function index()
		{
			if($this->auth->logged_in())
			{
				$data = array
				(
					'title' 	=> 'Admin',
					'message' 	=> 'Welcome to admin.',
					'sections'	=> $this->get_admin_sections(),
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
		
		public function create($table)
		{
			
		}
		
		public function update($table, $id)
		{
			
		}
		
		public function delete($table, $id)
		{
			
		}
		
		public function login($action = '')
		{
			// If segment is login submission.
			if($action == 'submit')
			{
				// Get POST variables.
				$user = $this->input->post('user');
				$pass = $this->input->post('pass');
				
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
					$this->session->set('user', $user);
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
					$this->session->destroy('user');
					$this->session->destroy('failed');
					$this->session->destroy('errors');
				}
			}
		}
		
		public function logout()
		{
			$this->auth->logout();
			redirect('admin/login');
		}
		
		private function get_admin_sections()
		{
			// Scan model directory.
			$files = scandir(APP_DIR . 'models/');
			
			if($files)
			{
				// Build sections to display.
				foreach($files as $file)
				{
					if($file != '..' && $file != '.')
					{
						// Load each model.
						require_once(APP_DIR . 'models/' . $file);
						$name = $this->string->remove_ext($file);
						$model = new $name;
						
						// No rows yet.
						$rows = null;
						
						// Check role to see if user can see this.
						if(in_array($this->auth->authed_user('role'), $model->allow))
						{
							if(!$model->fields) 
							{
								// Fields must be set so user can see it.
								Pep::show_error(sprintf('The fields array in the model %s must be set show up in the admin.', $file));
							}
							
							// Select what to show.
							$model->from($model->table);
							$rows = $model->select(array_keys($model->fields));
						}
						
						$sections[] = array
						(
							'rows'			=> $rows,
							'menu' 			=> $model->menu,
							'table'			=> $model->table,
							'fields' 		=> $model->fields,
							'updateable' 	=> $model->updateable,
							'deletable' 	=> $model->deletable
						);
					}
				}
				
				// Loop sections to post process.
				foreach($sections as &$section)
				{
					if($section['rows'])
					{
						foreach($section['rows'] as &$row)
						{
			        		foreach($row as $key => $value)
			        		{
			        			$col = $row[$key]; 
			        			$opts =& $section['fields'][$key];
			        			
			        			if($opts['type'] == 'pk')
			        			{
			        				// Primary key name defaults to actions.
			        				if(!isset($opts['name'])) $opts['name'] = 'Actions';
			        				
			        				$row[$key] = '';
			        				
			        				// Generate links from primary key type.
			        				if($section['updateable']) $row[$key] .= '<a href="'.site_url('admin/update/' . $section['table'] . '/' . $col).'">Update</a>';
			        				if($section['deletable'])  $row[$key] .= '<a href="'.site_url('admin/delete/' . $section['table'] . '/' . $col).'">Delete</a>';
			        			}
			        			
			        			if($opts['type'] == 'password')
			        			{
			        				$row[$key] = '&bull;&bull;&bull;&bull;&bull;';
			        			}
			        			
			        			// Nice name doesn't exist, use column name.
			        			if(!isset($opts['name'])) $opts['name'] = $key;
			        			
			        			/*switch($display['type'])
			        			{
			        				case 'select': 
			        					
			        					$row[$key] =  '<select name="'.$section['table'].'[]">';
			        					
			        					foreach($display['options'] as $option)
			        					{
			        						$row[$key] .= '<option'.($option == $col ? ' selected="selected"' : '').'>'.$option.'</option>';
			        					}
			        					
			        					$row[$key] .=  '</select>';
			        					
			        				break;
			        				
			        				case 'pk':			$row[$key] = '<a href="'.site_url('admin/update/' . $section['table'] . '/' . $col).'">Update</a>'; break;
			        				case 'label':		$row[$key] = '<label>'.$col.'</label>'; break;
			        				case 'text': 		$row[$key] = '<input name="'.$section['table'].'[]" type="text" value="'.$col.'" />'; break;
			        				case 'password': 	$row[$key] = '<input name="'.$section['table'].'[]" type="password" value="'.$col.'" />'; break;
			        				case 'textarea': 	$row[$key] = '<textarea>'.$col.'</textarea>'; break;
			        				default: 			$row[$key] = $col; break;
			        			}*/
			        		}
						}
					}
				}
			}
			//print_q($sections);
			return $sections;
		}
	}
	
?>