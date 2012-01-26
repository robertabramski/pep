<?php

	class Main extends Controller 
	{
		function index()
		{
			$data = array
			(
				'title' => 'Main'
			);
			
			$template = $this->load_view('main');
			$template->render($data);
		}
	}

?>
