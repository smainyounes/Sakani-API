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

		public function Random($limit = 9)
		{
			$v = new view_local();
			$v->Random($limit);
		}

		public function Search($page = 1, $wilaya = "0", $commune = "0", $type = "tout", $vl = "tout")
		{
			$v = new view_local();
			$v->Search($page, $wilaya, $commune, $type, $vl);
		}

		public function Byagence($id_agence, $page = 1, $vl = "tout", $type = "tout")
		{
			$owner = false;

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$mod = new model_agence();
				$owner = $mod->CheckAgence($id_agence, $_POST['tokken']);
			}

			$v = new view_local();
			$v->Byagence($id_agence, $page, $owner, $vl, $type);
		}

		public function Detail($id_local, $id_agence = null, $tokken = null)
		{
			$owner = false;

			if (isset($id_agence) && isset($tokken)) {
				$this->forbidden($id_agence, $tokken, $id_local);
				$owner = true;
			}

			$v = new view_local();
			$v->Detail($id_local, $owner);
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
		
		public function Editinfos($id_local)
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$this->forbidden($_POST['id_agence'], $_POST['tokken'], $id_local);

				$mod = new model_local();

				if ($mod->EditInfos($id_local)) {
					echo json_encode(['status' => 'success']);
				}else{
					echo json_encode(['status' => 'error', 'data' => ['msg' => 'infos were not edited']]);
				}
			}
		}

		public function Addimg($id_local, $id_agence, $tokken)
		{
			
			$this->forbidden($id_agence, $tokken, $id_local);

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$mod = new model_local();

				$res = array();

				$res = UploadPic($_FILES['img'], "local");

				if ($res['status'] === 'success') {
					// file uploaded
					$mod = new model_image();

					// insert in db
					echo json_encode($mod->AddImg($id_local, $res['data']['filename']));

				}else{
					echo json_encode($res);
				}
			}
		}

		public function GetImages($id_local)
		{
			$v = new view_local();
			$v->Images($id_local);
		}

		public function Selectmainimg($id_local)
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$this->forbidden($_POST['id_agence'], $_POST['tokken'], $id_local);

				$mod = new model_image();

				if ($mod->SelectMain($id_local, $_POST['id_img'])) {
					$mod = new model_local();
					if ($mod->ChangeStat($id_local, "active")) {
						echo json_encode(['status' => 'success']);
					}else{
						echo json_encode(['status' => 'error', 'data' => ['msg' => 'etat local not changed']]);
					}

					
				}else{
					echo json_encode(['status' => 'error', 'data' => ['msg' => 'main img could not be changed']]);
				}
			}
		}

		public function Deleteimg($id_local, $id_img)
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$this->forbidden($_POST['id_agence'], $_POST['tokken'], $id_local);

				$mod = new model_image();

				if (DeletePic("img/" . $mod->GetImgLink($id_img)) && DeletePic("img/preview/" . $mod->GetImgLink($id_img))) {
					if ($mod->DeleteImg($id_img)) {
						echo json_encode(['status' => 'success']);
					}else{
						// not deleted from db
						echo json_encode(['status' => 'error', 'data' => ['msg' => 'not deleted from db']]);
					}
				}else{
					// img file could not be deleted
					echo json_encode(['status' => 'error', 'data' => ['msg' => 'file not deleted']]);
				}			
			}
		}

		public function Changestat($id_local)
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$this->forbidden($_POST['id_agence'], $_POST['tokken'], $id_local);

				$mod = new model_local();

				if ($mod->ChangeStat($id_local, $_POST['etat'])) {
					echo json_encode(['status' => 'success']);
				}else{
					echo json_encode(['status' => 'error', 'data' => ['msg' => 'state cound not be changed']]);
				}
			}
		}

		public function Deletelocal($id_local)
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$this->forbidden($_POST['id_agence'], $_POST['tokken'], $id_local);

				$mod = new model_image();

				$data = $mod->GetImages($id_local);

				foreach ($data as $img) {
					DeletePic('img/'.$img->lien);
					DeletePic('img/preview/'.$img->lien);
				}

				$mod->DeleteAllImgLocal($id_local);

				$mod = new model_local();

				if ($mod->Delete($id_local)) {
					echo json_encode(['status' => 'success']);
				}else{
					echo json_encode(['status' => 'error', 'data' => ['msg' => 'not deleted']]);
				}

			}
		}

	}

 ?>