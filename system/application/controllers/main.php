<?php

	class Main extends Controller 
	{
		public function index()
		{
			$data = array
			(
				'title' => 'Main'
			);
			
			$template = $this->load->view('main');
			$template->render($data);
		}
	}

?>
