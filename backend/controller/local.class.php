<?php 

	/**
	 * 
	 */
	class controller_local
	{
		
		function __construct()
		{
			# code...
		}

		public function Index()
		{
			echo "hello local!";
		}

		private function forbidden($id_agence, $tokken, $id_local = null)
		{
			$json = ['status' => 'error', 'data' => ['msg' => 'access forbidden']];

			$mod = new model_agence();

			if (!$mod->CheckAgence($id_agence, $tokken)) {
				die(json_encode($json));
			}

			if (isset($id_local) && !$mod->TestOwner($id_agence, $id_local)) {
				die(json_encode($json));
			}
		}

		public function Latest($limit = 9)
		{
			$v = new view_local();
			$v->Latest($limit);
		}

		public function Search($page = 1, $wilaya = "tout", $commune = "tout", $type = "tout", $vl = "tout")
		{
			$v = new view_local();
			$v->Search($page, $wilaya, $commune, $type, $vl);
		}

		public function Byagence($id_agence, $page = 1)
		{
			$owner = false;

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$mod = new model_agence();
				$owner = $mod->CheckAgence($id_agence, $_POST['tokken']);
			}

			$v = new view_local();
			$v->Byagence($id_agence, $page, $owner);
		}

		public function Detail($id_local)
		{
			$v = new view_local();
			$v->Detail($id_local);
		}

		public function Addinfos()
		{
			$this->forbidden($_POST['id_agence'], $_POST['tokken']);

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$mod = new model_local();
				$json = [];

				$id = $mod->AjouterInfos($_POST['id_agence']);

				if ($id) {
					$json = ['status' => 'success', 'data' => ['id_local' => $id]];
				}else{
					$json = ['status' => 'error', 'data' => ['msg' => 'infos has not been added']];
				}

				echo json_encode($json);

			}
		}
		
		public function Addimg($id_local, $id_agence, $tokken)
		{
			
			$this->forbidden($id_agence, $tokken, $id_local);

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$mod = new model_local();

				$res = array();

				$res = UploadPic($_FILES['img']);

				if ($res['status'] === 'success') {
					// file uploaded
					$mod = new model_image();

					// insert in db
					echo json_encode($mod->AddImg($id_local, $res['filename']));

				}else{
					echo json_encode($res);
				}
			}
		}

	}

 ?>