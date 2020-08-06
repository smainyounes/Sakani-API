<?php 

	/**
	 * 
	 */
	class controller_agence
	{
		
		function __construct()
		{
			# code...
		}

		public function Index()
		{
			echo "Hello agence!";
		}

		private function forbidden($id_agence, $tokken)
		{
			$json = ['status' => 'error', 'data' => ['msg' => 'access forbidden']];

			$mod = new model_agence();

			if (!$mod->CheckAgence($id_agence, $tokken)) {
				die(json_encode($json));
			}
		}

		public function Latest($limit = 9)
		{
			$v = new view_agence();

			$v->Latest($limit);
		}

		public function Detail($id_agence)
		{
			$v = new view_agence();

			$v->Detail($id_agence);
		}

		public function Login()
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$mod = new model_agence();

				echo json_encode($mod->Login());
			}
		}

		public function Logout($id_agence, $tokken)
		{
			$mod = new model_agence();

			if ($mod->Logout($id_agence, $tokken)) {
				json_encode(['status' => 'success']);
			}else{
				json_encode(['status' => 'error', 'data' => ['msg' => 'logout failed']]);
			}
		}

		public function Inscription()
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$mod = new model_agence();

				$id = $mod->Inscription();
				if ($id) {
					json_encode(['status' => 'success', 'data' => ['id_agence' => $id]]);
				}else{
					json_encode(['status' => 'error', 'data' => ['msg' => 'signup failed'] ]);
				}
			}
		}

		public function Updateinfos($id_agence)
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$this->forbidden($id_agence, $_POST['tokken']);

				$mod = new model_agence();

				if ($mod->UpdateInfos($id_agence)) {
					json_encode(['status' => 'success']);
				}else{
					json_encode(['status' => 'success', 'data' => ['msg' => 'could not update infos']]);
				}

			}
		}

		public function Profileimg($id_agence, $tokken)
		{
			$this->forbidden($id_agence, $tokken);

			$res = UploadPic($_FILES['img'], "agence");

			if ($res['status'] === 'success') {
				// file uploaded
				$mod = new model_agence();

				$agence = $mod->Detail($id_agence);
				$img = $agence->Img_prof;

				if ($mod->UpdateProfilePic($id_agence, $res['data']['filename'])) {
					// inserted in DB

					if (isset($img)) {
						DeletePic('img/'.$img);
					}
					
					json_encode(['status' => 'success', 'data' => ['img_link' => PUBLIC_URL.'img/'.$res['data']['filename']]]);
				}else{
					// not inserted in DB
					DeletePic('img/'$res['data']['filename']);
					json_encode(['status' => 'error', 'data' => ['msg' => 'file not inserted in database']]);
				}
			}else{
				// not uploaded
				json_encode($res);
			}
		}

		public function Coverimg($id_agence, $tokken)
		{
			$this->forbidden($id_agence, $tokken);

			$res = UploadPic($_FILES['img'], "agence");

			if ($res['status'] === 'success') {
				// file uploaded
				$mod = new model_agence();

				$agence = $mod->Detail($id_agence);
				$img = $agence->Img_cover;

				if ($mod->ChangeCoverPic($id_agence, $res['data']['filename'])) {
					// inserted in DB

					if (isset($img)) {
						DeletePic('img/'.$img);
					}

					json_encode(['status' => 'success', 'data' => ['img_link' => PUBLIC_URL.'img/'.$res['data']['filename']]]);
				}else{
					// not inserted in DB
					DeletePic('img/'.$res['data']['filename']);
					json_encode(['status' => 'error', 'data' => ['msg' => 'file not inserted in database']]);
				}
			}else{
				// not uploaded
				json_encode($res);
			}
		}

		public function Changepassword($id_agence)
		{

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$this->forbidden($id_agence, $_POST['tokken']);

				$mod = new model_agence();

				$agence = $mod->Detail($id_agence);

				if (password_verify($_POST["oldpassword"], $agence->password)) {
					// old password verified
					if ($_POST['password'] === $_POST['repassword']) {
						// new password verified
						if ($mod->ChangePassword($id_agence)) {
							// password changed
							json_encode(['status' => 'success', 'data' => ['msg' => 'password changed']]);
						}else{
							// password didnt change
							json_encode(['status' => 'error', 'data' => ['msg' => 'password didnt change']]);
						}
						
					}else{
						// new password doesnt match
						json_encode(['status' => 'error', 'data' => ['msg' => 'new password didnt match']]);
					}
				}else{
					// wrong old password
					json_encode(['status' => 'error', 'data' => ['msg' => 'Wrong password']]);
				}
			}
		}
	}

 ?>