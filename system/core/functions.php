<?php
	
	/**
	 * Redirects to a page in the site.
	 * 
	 * @access 	public
	 * @param 	string	$loc	The location segments to redirect to.
	 * @return	void
	 * 
	 */
	function redirect($loc)
	{
		Pep::redirect($loc);
	}
	
	/**
	 * Shows an error. Attempts to use a theme to show error and falls back to
	 * the error view. 
	 * 
	 * @access public
	 * @param 	string 	$message	The message to be displayed.
	 * @param 	string 	$title		The page title to display.
	 * @return	void
	 * 
	 */
	function show_error($message, $title = 'Error')
	{
		Pep::show_error($message, $title);
	}
	
	/**
	 * Executes the PHP print_r function in a pre tag and exits. This is 
	 * useful for debugging purposes. 
	 * 
	 * @access 	public
	 * @param 	mixed 	$data	The data to print out.
	 * @return 	void
	 * 
	 */
	function print_q($data)
	{
		echo '<pre>'; print_r($data); echo '</pre>'; exit();
	}
	
?>