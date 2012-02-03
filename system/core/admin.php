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
			
			// Load language. 
			$this->lang = $this->load->lang(Pep::get_setting('language'));
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
					'user'		=> $this->auth->authed_user('user'),
					'result'	=> $this->session->get('result')
				);
				
				$template = $this->load->view('admin');
				$template->render($data);
				
				$this->session->delete('result');
			}
			else
			{
				redirect('admin/login');
			}
		}
		
		public function create($name = '')
		{
			if(empty($name)) show_error();
			
			if($this->input->has_post())
			{
				$post = $this->input->post();
				
				$model = $this->load->model($name);
				$fields = $model->fields;
				
				foreach($fields as $key => $value)
				{
					$opts =& $fields[$key];
					
					// Set blanks to null.
					if($opts['type'] != 'none')
					{
						if(empty($post[$key])) $post[$key] = null;
					}
					
					// Let primary key auto increment.
					if($opts['type'] == 'pk') unset($post[$key]);
					
					if($opts['type'] == 'password')
					{
						// Hash passwords sent.
						if(!empty($post[$key])) $post[$key] = md5($post[$key]);
					}
				}
				
				// Set session data for success or failure.
				if($model->insert($post))
				{
					$this->session->set('result', sprintf('The %s insert was successful.', $model->table));
				}
				else
				{
					$this->session->set('result', sprintf($this->lang['admin.insert_fail'], $model->table, $model->table));			
				}
				
				redirect('admin');
			}
			else
			{
				$model = $this->load->model($name);
				$fields = $model->fields;
				
				// Add markup for create view.
				foreach($fields as $key => $value)
				{
					$opts =& $fields[$key];
					
					if(empty($opts['type']))
			        {
			        	$row[$key] = '<input name="'.$key.'" type="text" />'; 
			        }
					
					switch($opts['type'])
			        {
			        	case 'select':
			        		$options = ''; $selected = $row[$key];
			        		foreach($opts['options'] as $option) $options .= '<option'.($selected == $option ? ' selected="selected"' : '').'>'.$option.'</option>'; 
			        		$row[$key] = '<select name="'.$key.'">'.$options.'</select>';
			        	break;
			        	
			        	case 'checkbox':
			        		$checked = $row[$key];
			        		$row[$key]  = '<input name="'.$key.'" type="checkbox"'.($checked == 'on' ? ' checked="checked"' : '').' />';
			        		$row[$key] .= '<input name="'.$key.'" type="hidden" value="'.($checked == 'on' ? 'on' : 'off').'" />';
			        	break;
			        	
			        	case 'text': 		$row[$key] = '<input name="'.$key.'" type="text" />'; break;
			        	case 'password':	$row[$key] = '<input name="'.$key.'" type="password" />'; break;
			        	case 'textarea': 	$row[$key] = '<textarea name="'.$key.'"></textarea>'; break;
			        	case 'none':	 	$row[$key] = '<input name="'.$key.'" type="text" />'; break;
			        	default:		 	$row[$key] = '<input name="'.$key.'" type="text" />'; break;
			        }
			        
			        // Nice name doesn't exist, use column name.
			        if(!isset($opts['name'])) $opts['name'] = $key;
			        
					if($opts['type'] == 'pk')
					{
				        // Get bottom row to show insert id. 
				        $row[$key] = $model->bottom_row($key) + 1;
					}
				}
				
				$data = array
				(
					'title' 	=> 'Admin',
					'message' 	=> 'Create '. $model->menu,
					'fields'	=> $fields,
					'row'		=> $row
				);
				
				$template = $this->load->view('create');
				$template->render($data);
			}
		}
		
		public function update($name = '', $id = '')
		{
			if(empty($name) || empty($id)) show_error();
			
			if($this->input->has_post())
			{
				$post = $this->input->post();
				//print_q($post);
				$model = $this->load->model($name);
				$fields = $model->fields;
				
				foreach($fields as $key => $value)
				{
					// Get primary key name for query.
					if($fields[$key]['type'] == 'pk') $pk = $key;
				}
				
				foreach($fields as $key => $value)
				{
					$opts =& $fields[$key];
					
					// Set blanks to null.
					if($opts['type'] != 'none')
					{
						if(empty($post[$key])) $post[$key] = null;
					}
					
					// Remove primary key, it exists already.
					if($opts['type'] == 'pk') unset($post[$key]);
					
					if($opts['type'] == 'password')
					{
						if(empty($post[$key])) unset($post[$key]);
						else $post[$key] = md5($post[$key]);
					}
					
					if($opts['type'] == 'checkbox')
					{
						$post[$key] = ($post[$key] == 'on' ? 'on' : 'off'); 
					}
				}
				
				// Set session data for success or failure.
				if($model->update($post, array($pk => $id)) > 0)
				{
					$this->session->set('result', sprintf('The %s update was successful.', $model->table));
				}
				else
				{
					$this->session->set('result', sprintf('The %s update has failed.', $model->table));			
				}
				
				redirect('admin');
			}
			else
			{
				$model = $this->load->model($name);
				$fields = $model->fields;
				
				foreach($fields as $key => $value)
				{
					$opts =& $fields[$key];
					
					if($opts['type'] == 'pk')
					{
						// Get primary key name for query.
						$pk = $key;
					}
					
					// Nice name doesn't exist, use column name.
			        if(!isset($opts['name'])) $opts['name'] = $key;
				}
				
				// Select item to update.
				$model->from($model->table);
				$rows = $model->select(array_keys($model->fields), array($pk => $id), 1);
				$row = $rows[0];
				
				// Add markup for update view.
				foreach($fields as $key => $value)
				{
					switch($fields[$key]['type'])
			        {
			        	case 'select':
			        		$options = ''; $selected = $row[$key];
			        		foreach($opts['options'] as $option) $options .= '<option'.($selected == $option ? ' selected="selected"' : '').'>'.$option.'</option>'; 
			        		$row[$key] = '<select name="'.$key.'">'.$options.'</select>';
			        	break;
			        	
			        	case 'checkbox':
			        		$checked = $row[$key];
			        		$row[$key]  = '<input name="'.$key.'" type="checkbox"'.($checked == 'on' ? ' checked="checked"' : '').' />';
			        	break;
			        	
			        	case 'text': 		$row[$key] = '<input name="'.$key.'" type="text" value="'.$row[$key].'" />'; break;
			        	case 'password':	$row[$key] = '<input name="'.$key.'" type="password" />'; break;
			        	case 'textarea': 	$row[$key] = '<textarea name="'.$key.'">'.$row[$key].'</textarea>'; break;
			        	case 'none':	 	$row[$key] = $row[$key]; break;
			        }
				}
				
				$data = array
				(
					'title' 	=> 'Admin',
					'message' 	=> 'Update '. $model->menu,
					'fields'	=> $fields,
					'row'		=> $row
				);
				
				$template = $this->load->view('update');
				$template->render($data);
			}
		}
		
		public function delete($name = '', $id = '')
		{
			$model = $this->load->model($name);
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
					$this->session->delete('user');
					$this->session->delete('failed');
					$this->session->delete('errors');
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
						$name = $this->string->remove_ext($file);
						$model = $this->load->model($name);
						
						$rows = null;
						
						// Check role to see if user can see this.
						$allowed = in_array($this->auth->authed_user('role'), $model->allow);
						
						if($allowed)
						{
							if(!$model->fields) 
							{
								// Fields must be set so user can see it.
								Pep::show_error(sprintf('The fields array in the model %s must be set to show up in the admin.', $file));
							}
							
							// Select what to show.
							$model->from($model->table);
							$rows = $model->select(array_keys($model->fields));
						}
						
						// Create sections array.
						$sections[] = array
						(
							'rows'			=> $rows,
							'allowed'		=> $allowed,
							'menu' 			=> $model->menu,
							'table'			=> $model->table,
							'fields' 		=> $model->fields,
							'updateable' 	=> $model->updateable,
							'deletable' 	=> $model->deletable
						);
					}
				}
				
				// Post process sections array.
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
			        				// Add action links from primary key.
			        				if($section['updateable']) $row['Actions'] .= '<a href="'.site_url('admin/update/' . $section['table'] . '/' . $col).'">Update</a>';
			        				
			        				// Allow delete for all except primary admin.
			        				if($col != 1 && $section['table'] == 'users')
			        				{
			        					if($section['deletable'])  $row['Actions'] .= '<a href="'.site_url('admin/delete/' . $section['table'] . '/' . $col).'">Delete</a>';
			        				}
			        			}
			        			
			        			if($opts['type'] == 'password')
			        			{
			        				// Show password as bullets instead of hash. 
			        				$row[$key] = '&bull;&bull;&bull;&bull;&bull;';
			        			}
			        			
			        			// Nice name doesn't exist, use column name.
			        			if(!isset($opts['name'])) $opts['name'] = $key;
			        		}
						}
					}
				}
			}
			
			return $sections;
		}
	}
	
?>