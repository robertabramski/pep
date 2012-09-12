<?php
	
	function redirect($loc) { Pep::redirect($loc); }
	function show_error($message = '', $title = 'Error') { Pep::show_error($message, $title); }
	
	function print_q($data) { Pep::print_q($data); }
	
	function site_url($loc = '') { return Pep::site_url($loc); }
	function base_url() { return Pep::base_url(); }
	function current_url() { return Pep::current_url(); }
	function segment($seg) { return Pep::segment($seg); }
	
?>