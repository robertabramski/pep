<?php
	
	/**
	* Copyright (c) 2012 Robert Abramski
	*
	* Permission is hereby granted, free of charge, to any person
	* obtaining a copy of this software and associated documentation
	* files (the "Software"), to deal in the Software without
	* restriction, including without limitation the rights to use,
	* copy, modify, merge, publish, distribute, sublicense, and/or sell
	* copies of the Software, and to permit persons to whom the
	* Software is furnished to do so, subject to the following
	* conditions:
	*
	* The above copyright notice and this permission notice shall be
	* included in all copies or substantial portions of the Software.
	*
	* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
	* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
	* OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
	* NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
	* HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
	* WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
	* FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
	* OTHER DEALINGS IN THE SOFTWARE.
	*
	*/
	
	// Configure constants.
	define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
	define('APP_DIR', ROOT_DIR .'system/application/');
	define('DB_DIR', ROOT_DIR .'system/database/');
	define('CORE_DIR', ROOT_DIR .'system/core/');
	define('THEME_DIR', ROOT_DIR .'themes/');
	
	// Set to false for production.
	define('DEV_MODE', true);
	
	// Get required core classes.
	require(CORE_DIR.'pep.php');
	require(CORE_DIR.'model.php');
	require(CORE_DIR.'view.php');
	require(CORE_DIR.'controller.php');
	require(CORE_DIR.'functions.php');
	require(CORE_DIR.'auth.php');
	require(CORE_DIR.'input.php');
	require(CORE_DIR.'load.php');
	
	Pep::init();

?>
