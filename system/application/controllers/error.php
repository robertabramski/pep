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
		
		function code($id = 0)
		{
			$lang = $this->load_lang(Pep::get_setting('language'));
			
			$data = array
			(
				'title' 	=> 'Error',
				'message' 	=> $lang['error'.$id]
			);
		
			$template = $this->load_view('error');
			$template->render($data);
		}
	}

?>
