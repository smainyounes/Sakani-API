<?php 

	// public link (after hosting remove the /framework/public_html)
	define("PUBLIC_URL", "http://$_SERVER[HTTP_HOST]/sakani-api/public_html/");

	// backend link
	define("BACKEND_URL", dirname(dirname(dirname(__FILE__)))."/backend/");

	// website name
	define('WEBSITE_NAME', 'sakani-api');

	// database Infos
	define('DB_HOST', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_NAME', 'sakani');
	define('DB_PASS', '');
	

 ?>