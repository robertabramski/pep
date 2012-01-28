<?php

	class Error extends Controller 
	{	
		public function index()
		{
			$data = array
			(
				'title' 	=> 'Error',
				'message' 	=> '404 Not Found.'
			);
		
			$template = $this->load->view('error');
			$template->render($data);
		}
	}

?>
