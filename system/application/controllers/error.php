<?php

	class Error extends Controller 
	{	
		function index()
		{
			$data = array
			(
				'title' 	=> 'Error',
				'message' 	=> '404 Not Found.'
			);
		
			$template = $this->load_view('error');
			$template->render($data);
		}
	}

?>
