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
				
				$template = $this->load->view('admin/main');
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
					
					// Get validation rules.
					$rules[$key] = $fields[$key]['validate'];
					
					// Let primary key auto increment.
					if($opts['type'] == 'pk') unset($post[$key]);
					
					if($opts['type'] == 'password')
					{
						// Hash passwords sent.
						if(!empty($post[$key])) $post[$key] = md5($post[$key]);
					}
				}
				
				// Run validation.
				if($this->validate->run($rules))
				{
					// Set session data for success or failure.
					if($model->insert($post))
					{
						$this->session->set('result', sprintf($this->lang['admin.insert_pass'], $model->table));
					}
					else
					{
						$db_error = $this->show_db_error($model);
						$this->session->set('result', sprintf($this->lang['admin.insert_fail'], $model->table, $db_error));
					}
					
					redirect('admin');
				}
				else
				{					
					// Validation failed. Get validation results array.
					$results = $this->validate->get_results();
					
					// Set session data. Serialize validation results for display on redirect.
					$this->session->set('failed', $this->lang['admin.valid_fail']);
					$this->session->set('errors', serialize($results));
					
					redirect('admin/create/'.$name);
				}
			}
			else
			{
				$model = $this->load->model($name);
				$fields = $model->fields;
				
				// Add markup for create view.
				foreach($fields as $key => $value)
				{
					$opts =& $fields[$key];
					
					switch($opts['type'])
			        {
			        	case 'select':
			        		$options = ''; $selected = $row[$key];
			        		foreach($opts['options'] as $option) $options .= '<option'.($selected == $option ? ' selected="selected"' : '').'>'.$option.'</option>'; 
			        		$row[$key] = '<select name="'.$key.'">'.$options.'</select>';
			        	break;
			        	
			        	case 'checkbox':
			        		$checked = $row[$key];
			        		$row[$key]  = '<input name="'.$key.'" type="hidden" value="off" />';
			        		$row[$key] .= '<input name="'.$key.'" type="checkbox"'.($checked == 'on' ? ' checked="checked"' : '').' />';
			        	break;
			        	
			        	case 'text': 		$row[$key] = '<input name="'.$key.'" type="text" />'; break;
			        	case 'password':	$row[$key] = '<input name="'.$key.'" type="password" />'; break;
			        	case 'textarea': 	$row[$key] = '<textarea name="'.$key.'"></textarea>'; break;
			        	case 'none':	 	$row[$key] = '<input name="'.$key.'" type="text" />'; break;
			        }
			        
			        // Nice name doesn't exist, use column name.
			        if(!isset($opts['name'])) $opts['name'] = $key;
			        
					if($opts['type'] == 'pk')
					{
				        // Get bottom row to show insert id. 
				        $row[$key] = $model->bottom_row($key) + 1;
					}
				}
				
				// Retrieve any session variables.
				$failed = $this->session->get('failed');
				$errors = $this->session->get('errors');
				
				$data = array
				(
					'title' 	=> 'Admin',
					'message' 	=> empty($failed) ? 'Create '. $model->menu : $failed,
					'errors'	=> empty($errors) ? null : unserialize($errors),
					'fields'	=> $fields,
					'row'		=> $row
				);
				
				$template = $this->load->view('admin/create');
				$template->render($data);
				
				$this->session->delete('failed');
				$this->session->delete('errors');
			}
		}
		
		public function update($name = '', $id = '')
		{
			if(empty($name) || empty($id)) show_error();
			
			if($this->input->has_post())
			{
				$post = $this->input->post();
				
				$model = $this->load->model($name);
				$fields = $model->fields;
				
				foreach($fields as $key => $value)
				{
					$opts =& $fields[$key];
					
					// Get validation rules.
					$rules[$key] = $fields[$key]['validate'];
					
					// Remove primary key, it exists already.
					if($opts['type'] == 'pk') unset($post[$key]);
					
					if($opts['type'] == 'password')
					{
						// Store clear pass in case of authed user.
						$pass = $post[$key];
						
						// Remove if password is blank or hash it.
						if(empty($post[$key])) unset($post[$key]);
						else $post[$key] = md5($post[$key]);
					}
					
					if($opts['type'] == 'checkbox')
					{
						$post[$key] = ($post[$key] == 'on' ? 'on' : 'off');
					}
				}
				
				// Run validation.
				if($this->validate->run($rules))
				{
					// Set session data for success or failure.
					if($model->update($post, 'ROWID = '. $id) > 0)
					{
						$this->session->set('result', sprintf($this->lang['admin.update_pass'], $model->table));
						
						// If user is changing their password.
						if($this->auth->authed_user('user_id') == $id)
						{
							// Reauth after password change.
							$this->auth->login($post['user'], $pass);
						}
					}
					else
					{
						$db_error = $this->show_db_error($model);
						$this->session->set('result', sprintf($this->lang['admin.update_fail'], $model->table, $db_error));		
					}
					
					redirect('admin');
				}
				else
				{					
					// Validation failed. Get validation results array.
					$results = $this->validate->get_results();
					
					// Set session data. Serialize validation results for display on redirect.
					$this->session->set('failed', $this->lang['admin.valid_fail']);
					$this->session->set('errors', serialize($results));
					
					redirect('admin/update/'.$name.'/'.$id);
				}
			}
			else
			{
				$model = $this->load->model($name);
				$fields = $model->fields;
				
				// Select item to update.
				$model->from($model->table);
				$rows = $model->select(array_keys($model->fields), 'ROWID = '. $id, 1);
				$row = $rows[0];
				
				// Add markup for update view.
				foreach($fields as $key => $value)
				{
					$opts =& $fields[$key];
					
					// Nice name doesn't exist, use column name.
			        if(!isset($opts['name'])) $opts['name'] = $key;
					
					switch($opts['type'])
			        {
			        	case 'select':
			        		$options = ''; $selected = $row[$key];
			        		foreach($opts['options'] as $option) $options .= '<option'.($selected == $option ? ' selected="selected"' : '').'>'.$option.'</option>'; 
			        		$row[$key] = '<select name="'.$key.'">'.$options.'</select>';
				        break;
			        	
			        	case 'checkbox':
			        		$checked = $row[$key];
			        		$row[$key]  = '<input name="'.$key.'" type="hidden" value="off" />';
			        		$row[$key] .= '<input name="'.$key.'" type="checkbox"'.($checked == 'on' ? ' checked="checked"' : '').' />';
			        	break;
			        	
			        	case 'text': 		$row[$key] = '<input name="'.$key.'" type="text" value="'.$row[$key].'" />'; break;
			        	case 'password':	$row[$key] = '<input name="'.$key.'" type="password" />'; break;
			        	case 'textarea': 	$row[$key] = '<textarea name="'.$key.'">'.$row[$key].'</textarea>'; break;
			        	case 'none':	 	$row[$key] = $row[$key]; break;
			        }
				}
				
				// Retrieve any session variables.
				$failed = $this->session->get('failed');
				$errors = $this->session->get('errors');
				
				$data = array
				(
					'title' 	=> 'Admin',
					'message' 	=> empty($failed) ? 'Update '. $model->menu : $failed,
					'errors'	=> empty($errors) ? null : unserialize($errors),
					'fields'	=> $fields,
					'row'		=> $row
				);
				
				$template = $this->load->view('admin/update');
				$template->render($data);
				
				$this->session->delete('failed');
				$this->session->delete('errors');
			}
		}
		
		public function delete($name = '', $id = '')
		{
			if(empty($name) || empty($id)) show_error();
			
			// You cannot delete yourself.
			if($this->auth->authed_user('user_id') == $id && $name == 'users')
			{
				$this->session->set('result', $this->lang['admin.delete_self']);
				redirect('admin');
			}
			
			$model = $this->load->model($name);
			$fields = $model->fields;
			
			if($model->delete('ROWID = '.$id) > 0)
			{
				$this->session->set('result', sprintf($this->lang['admin.delete_pass'], $model->table));
			}
			else
			{
				$db_error = $this->show_db_error($model);
				$this->session->set('result', sprintf($this->lang['admin.delete_fail'], $model->table, $db_error));	
			}
			
			redirect('admin');
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
						$this->session->set('failed', $this->lang['admin.login_fail']);
						
						redirect('admin/login');
					}
				}
				else
				{
					// Validation failed. Get validation results array.
					$results = $this->validate->get_results();
					
					// Set session data. Serialize validation results for display on redirect.
					$this->session->set('user', $user);
					$this->session->set('failed', $this->lang['admin.valid_fail']);
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
						'message' 	=> empty($failed) ? $this->lang['admin.login_none'] : $failed,
						'errors'	=> empty($errors) ? null : unserialize($errors),
						'user'		=> $this->session->get('user')
					);
					
					$template = $this->load->view('admin/login');
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
		
		public function generate($type = '')
		{
			if(empty($type)) show_error();
			
			if($type == 'model')
			{
				if($this->input->has_post())
				{
					require(CORE_DIR . 'generate.php');
					$generate = new Generate();
					
					$name = $this->input->post('name');
					$allow = $this->input->post('allow');
					$creatable = $this->input->post('creatable');
					$updateable = $this->input->post('updateable');
					$deletable = $this->input->post('deletable');
					$description = $this->input->post('description');
					
					// Add string quotess for generated file.
					for($i = 0; $i < count($allow); $i++) $allow[$i] = "'". $allow[$i] ."'";
					
					$options = array
					(
						'name' => ucfirst($name),
						'allow' => 'array('.rtrim(implode(', ', $allow)).')',
						'creatable' => $creatable ? 'true' : 'false',
						'updateable' => $updateable ? 'true' : 'false',
						'deletable' => $deletable ? 'true' : 'false',
						'description' => $description
					);
					
					if($generate->model($name, $options, true))
					{
						// Create the table.
						$model = new Model();
						// CREATE TABLE test(test_id INTEGER PRIMARY KEY NOT NULL, name TEXT, value TEXT)
						$model->query('CREATE TABLE IF NOT EXISTS ' . strtolower($name) . '(test_id INTEGER PRIMARY KEY NOT NULL, name TEXT, value TEXT)');
						
						$this->session->set('result', 'The model '. strtolower($name) . ' was created successfully.');
						redirect('admin');
					}
					else
					{
						redirect('admin/model');
					}
				}
				else
				{
					$data = array
					(
						'title' => 'Generate Model'
					);
					
					$template = $this->load->view('admin/model');
					$template->render($data);
				}
			}
		}
		
		private function show_db_error($model)
		{
			return $model->get_type() . ' '.$this->lang['error'].': ' . $model->get_error();
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
					if($file != '..' && $file != '.' && substr($file, 0, 1) != '.')
					{
						// Load each model.
						$name = $this->string->remove_ext($file);
						$model = $this->load->model($name);
						
						$rows = null;
						
						// Check role to see if user can see this.
						$allowed = in_array($this->auth->authed_user('role'), $model->allow);
						
						if($allowed)
						{
							// If fields don't exist, skip it.
							if(count($model->fields) == 0) continue;
							
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
							'creatable' 	=> $model->creatable,
							'updateable' 	=> $model->updateable,
							'deletable' 	=> $model->deletable,
							'description'	=> $model->description
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
			        				//TODO: Make JavaScript modal for delete when things get pretty.
			        				if($section['updateable']) $row[$this->lang['actions']] .= '<a href="'.site_url('admin/update/' .$section['table']. '/' . $col).'">'.$this->lang['update'].'</a>';
			        				if($section['deletable'])  $row[$this->lang['actions']] .= '<a href="'.site_url('admin/delete/' .$section['table']. '/' . $col).'">'.$this->lang['delete'].'</a>';
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