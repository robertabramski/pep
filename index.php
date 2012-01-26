<?php
	
	/**
	 * Copyright (c) 2012 Robert Abramski
	 *
	Ê* Permission is hereby granted, free of charge, to any person
	 * obtaining a copy of this software and associated documentation
	Ê* files (the "Software"), to deal in the Software without
	Ê* restriction, including without limitation the rights to use,
	Ê* copy, modify, merge, publish, distribute, sublicense, and/or sell
	Ê* copies of the Software, and to permit persons to whom the
	Ê* Software is furnished to do so, subject to the following
	Ê* conditions:
	 *
	Ê* The above copyright notice and this permission notice shall be
	Ê* included in all copies or substantial portions of the Software.
	 *
	Ê* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
	Ê* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
	Ê* OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
	Ê* NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
	Ê* HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
	Ê* WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
	Ê* FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
	Ê* OTHER DEALINGS IN THE SOFTWARE.
	 *
	 */
	
	// Configure constants.
	define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
	define('APP_DIR', ROOT_DIR .'system/application/');
	define('DB_DIR', ROOT_DIR .'system/database/');
	define('CORE_DIR', ROOT_DIR .'system/core/');
	define('THEME_DIR', ROOT_DIR .'themes/');
	
	// Get required core classes.
	require(CORE_DIR.'pep.php');
	require(CORE_DIR.'model.php');
	require(CORE_DIR.'view.php');
	require(CORE_DIR.'controller.php');
	
	Pep::init();

?>
