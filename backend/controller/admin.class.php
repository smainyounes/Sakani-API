<?php 

	/**
	 * 
	 */
	class controller_admin
	{
		private $view;
		function __construct()
		{
			session_start();
			$this->view = new view_admin();
		}

		public function Index()
		{
			if (isset($_SESSION['admin'])) {
				$this->Home();
			}else{
				$this->Login();
			}
		}

		public function Login()
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				$mod = new model_admin();

				if (isset($_SESSION['tokken']) && $_POST['tokken'] === $_SESSION['tokken']) {
					if ($mod->Login()) {
						header("Location: ".PUBLIC_URL."admin");
					}
					
				}
			}

			$_SESSION['tokken'] = token();

			$this->view->Header();

			$this->view->Login();

			$this->view->Footer();
		}

		public function Dc()
		{
			$mod = new model_admin();

			$mod->Logout();

			header("Location: ".PUBLIC_URL."admin");
		}

		public function Home()
		{
			$this->view->Header();
			$this->view->SideBar();
			$this->view->Navbar();


			$this->view->Home();


			$this->view->Footer();
		}

		public function Locals($page = 1)
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				$id_local = $_POST['local'];
				echo "id = $id_local";
				$mod = new model_image();

				$data = $mod->GetImages($id_local);

				foreach ($data as $img) {
					DeletePic('img/'.$img->lien);
					DeletePic('img/preview/'.$img->lien);
				}
				if ($mod->DeleteAllImgLocal($id_local)) {
					$alert = ['status' => 'success', 'msg' => 'local deleted'];
				}else{
					$alert = ['status' => 'error', 'msg' => 'error deleting images'];
				}
				

				$mod = new model_local();

				if ($alert['status'] === "success" && $mod->Delete($id_local)) {
					$alert = ['status' => 'success', 'msg' => 'local deleted'];
				}else{
					$alert = ['status' => 'error', 'msg' => 'error deleting local'];
				}
			}

			$this->view->Header("Locals");
			$this->view->SideBar("locals");
			$this->view->Navbar();

			if (isset($alert)) {
				$this->view->Alert($alert['msg'], $alert['status']);
			}

			$this->view->ListLocals($page);


			$this->view->Footer();
		}

		public function Agences($page = 1, $filter = "all", $keyword = "")
		{
			$this->view->Header("Agences");
			$this->view->SideBar("agences");
			$this->view->Navbar();

			if (isset($_GET['filter'])) {
				$filter = $_GET['filter'];
			}

			if (isset($_GET['keyword'])) {
				$keyword = $_GET['keyword'];
			}

			$this->view->Agences($page, $filter, $keyword);


			$this->view->Footer();
		}

		public function Agence($id_agence)
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				$mod = new model_admin();

				if ($mod->EtatAgence($id_agence, $_POST['etat'])) {
					$alert = ['status' => 'success', 'msg' => 'etat changed with success'];
				}else{
					$alert = ['status' => 'error', 'msg' => 'etat could not be changed'];
				}
			}

			$this->view->Header("Agency Detail");
			$this->view->SideBar("agence detail");
			$this->view->Navbar();

			if (isset($alert)) {
				$this->view->Alert($alert['msg'], $alert['status']);
			}
			
			$this->view->AgenceDetail($id_agence);

			$this->view->Footer();
		}

		public function Admins()
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {

				$mod = new model_admin();

				if (isset($_POST['add'])) {
					if ($mod->AddAdmin()) {
						$alert = ['status' => 'success', 'msg' => 'New admin added successfuly'];
					}else{
						$alert = ['status' => 'error', 'msg' => 'Admin not added'];
					}
				}

				if (isset($_POST['edit'])) {
					if ($mod->AddAdmin()) {
						$alert = ['status' => 'success', 'msg' => 'infos edited with success'];
					}else{
						$alert = ['status' => 'error', 'msg' => 'infos has not been edited'];
					}
				}
			}

			$this->view->Header("Admins");
			$this->view->SideBar("admins");
			$this->view->Navbar();

			if (isset($alert)) {
				$this->view->Alert($alert['msg'], $alert['status']);
			}

			$this->view->AdminUsers();


			$this->view->Footer();
		}

		public function Imgregister($filename)
		{
			if (file_exists("../../img/".$filename)) {
				header('Content-type: image/jpeg');
				readfile("../../img/".$filename);
			}else{
				echo "no img";
			}
		}

		public function Imglocal($filename)
		{
			if (file_exists("../../img/".$filename)) {
				header('Content-type: image/jpeg');
				readfile("../../img/".$filename);
			}else{
				echo "no img";
			}
		}
	}

 ?>