<?php 

	/**
	 * 
	 */
	class lib_core
	{
		protected $currentController = 'controller_home';
		protected $currentMethod = 'Index';
		protected $params = [];
		
		public function __construct()
		{
		  //print_r($this->getUrl());

		  $url = $this->getUrl();

		  // Look in controllers for first value
		  if(file_exists(BACKEND_URL.'controller/' . strtolower($url[0]). '.class.php')){
		    // If exists, set as controller
		    $this->currentController = "controller_".strtolower($url[0]);
		    // Unset 0 Index
		    unset($url[0]);
		  }else{
		  	if ($url != "") {
		  		header("Location: ".PUBLIC_URL."error");
		  	}
		  }

		  // Instantiate controller class
		  $this->currentController = new $this->currentController;

		  // Check for second part of url
		  if(isset($url[1])){
		    // Check to see if method exists in controller
		    if(method_exists($this->currentController, $url[1])){
		      $this->currentMethod = $url[1];
		      // Unset 1 index
		      unset($url[1]);
		    }
		  }

		  // Get params
		  $this->params = $url ? array_values($url) : [];

		  // Call a callback with array of params
		  call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
		}

		public function getUrl(){
		  if(isset($_GET['cmd'])){
		    $url = rtrim($_GET['cmd'], '/');
		    $url = filter_var($url, FILTER_SANITIZE_URL);
		    $url = htmlspecialchars($url);
		    $url = explode('/', $url);
		    return $url;
		  }
		}

	}


 ?>