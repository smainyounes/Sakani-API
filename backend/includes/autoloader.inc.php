<?php 
	
	// include confid
	include 'config.inc.php';

	// include helpers.php
	include BACKEND_URL."/helpers/helpers.php";

	// starting session
	session_start();

	// defining autoloader function
	spl_autoload_register("myloader");

	function myloader($class_name) 
	{
	    $filename = str_replace('_', DIRECTORY_SEPARATOR, strtolower($class_name)).'.class.php';

	    $file = BACKEND_URL.$filename;

	    if ( ! file_exists($file))
	    {
	        return FALSE;
	    }
	    include $file;
	}
?>