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

		public function Checklogin($id_agence, $tokken)
		{
			$mod = new model_agence();

			if ($mod->CheckAgence($id_agence, $tokken)) {
				//$new_tokken = $mod->GenTokken($id_agence);
				$new_tokken = $tokken;
				
				$agence = $mod->Detail($id_agence);
				
				if (isset($new_tokken) && $agence) {
					echo json_encode(['status' => 'success', 'data' => ['msg' => 'user logged in', 'tokken' => $new_tokken, 'etat_agence' => $agence->etat_agence]]);
				}else{
					echo json_encode(['status' => 'error', 'data' => ['msg' => 'error updating tokken']]);
				}
				
			}else{
				echo json_encode(['status' => 'error', 'data' => ['msg' => 'user not logged in']]);
			}
		}

		public function Latest($limit = 9)
		{
			$v = new view_agence();

			$v->Latest($limit);
		}

		public function Random($limit = 9)
		{
			$v = new view_agence();

			$v->Random($limit);
		}

		public function Detail($nom_url)
		{

			$tab = explode("-", $nom_url);

			$id_agence = array_pop($tab);

			$name = implode(" ", $tab);

			$v = new view_agence();

			$v->Detail($id_agence, $name);
		}

		public function Login($full = null)
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$mod = new model_agence();
				$data = $mod->Login();
				if ($data) {
					$tokken = $mod->GenTokken($data->id_agence);

					if (isset($tokken)) {
						$json = ['status' => 'success'];

						if (isset($full)) {
							$v = new view_agence();

							$json['data'] = array_merge(['tokken' => $tokken], $v->JsonAgence($data));
						}else{
							$json['data'] = ['id_agence' => $data->id_agence, 'nom_agence' => $data->nom, 'tokken' => $tokken, 'nom_url' => str_replace(" ", "-", trim($data->nom)) . "-" . $data->id_agence];
						}

						echo json_encode($json);

					}else{
						echo json_encode(['status' => 'error', 'data' => ['msg' => 'tokken could not be generated']]);
					}

				}else{
					echo json_encode(['status' => 'error', 'data' => ['msg' => 'wrong username or password']]);
				}

			}
		}

		public function Logout($id_agence, $tokken)
		{
			$mod = new model_agence();

			if ($mod->Logout($id_agence, $tokken)) {
				echo json_encode(['status' => 'success']);
			}else{
				echo json_encode(['status' => 'error', 'data' => ['msg' => 'logout failed']]);
			}
		}

		public function Inscription()
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$mod = new model_agence();

				if ($mod->CheckEmailExists($_POST['email'])) {
					die(json_encode(['status' => 'error', 'data' => ['msg' => 'email exists']]));
				}

				$id = $mod->Inscription();

				// error inscription
				if (!$id) {
					die(json_encode(['status' => 'error', 'data' => ['msg' => 'signup failed']]));
				}

				$rc = $this->Ajoutregistre($id);

				if ($rc['status'] === 'error') {
					$mod->DeleteAgence($id_agence);
					die(json_encode(['status' => 'error', 'data' => ['msg' => 'error upload img rc']]));
				}

				$hanout = $this->Ajouthanout($id);

				if ($hanout['status'] === 'error') {
					DeletePic("../../img/".$rc['data']['filename']);
					$mod->DeleteAgence($id_agence);
					die(json_encode(['status' => 'error', 'data' => ['msg' => 'error upload img rc']]));
				}
				
				$this->SendVMail(strtolower($_POST['email']));

				echo json_encode(['status' => 'success']);
			}
		}

		private function SendVMail($email)
		{
			$mod = new model_agence();
			
			// Delete all validation tokkens
			$mod->DeleteValidation($email);

			// genValidation tokken
			$res = $mod->GenValidation($email);

			if ($res['status'] === 'error') {
				die(json_encode($res));
			}

			// send the email
			$link = "https://soukna-dz.com/verify-email/".$res['data']['selector'] ."/".bin2hex($res['data']['tokken']);
			$msg = '<p>Here s your verification link:  </p>';
			$msg .= '<a href="'.$link.'">Here</a>';

			$headers = "From: Soukna-dz <test@soukna-dz.com>\r\n";
			$headers .= "Reply-To: support@soukna-dz.com\r\n";
			$headers .= "Content-type: text/html\r\n";

			mail($email, "Email verification", $msg, $headers);			
		}

		public function Resendemail($email = null)
		{
			if (isset($email)) {
				$mod = new model_agence();

				if (!$mod->CheckEmailExists($email)) {
					die(json_encode(['status' => 'error', 'data' => ['msg' => 'email doesnt exists']]));
				}

				$this->SendVMail(strtolower($email));
				die(json_encode(['status' => 'success']));
			}

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$mod = new model_agence();

				if (!$mod->CheckEmailExists($_POST['email'])) {
					die(json_encode(['status' => 'error', 'data' => ['msg' => 'email doesnt exists']]));
				}

				$this->SendVMail(strtolower($_POST['email']));
				echo json_encode(['status' => 'success']);
			}

		}

		public function Verifyemail()
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$mod = new model_agence();

				$res = $mod->CheckValidation($_POST['selector'], $_POST['tokken']);
				
				if ($res['status'] === 'error') {
					die(json_encode($res));
				}

				// change etat agence to pending
				if (!$mod->SetToPending($res['data']['email'])) {
					$this->Resendemail($res['data']['email']);
					die(json_encode(['status' => 'error', 'data' => ['msg' => 'retry']]));
				}

				echo json_encode(['status' => 'success']);
			}
		}

		public function Updateinfos($id_agence)
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$this->forbidden($id_agence, $_POST['tokken']);

				$mod = new model_agence();

				if ($mod->UpdateInfos($id_agence)) {
					$data = $mod->Detail($id_agence);
					echo json_encode(['status' => 'success', 'data' => ['tel1' => $data->tel1, 'tel2' => $data->tel2, 'fb' => $data->fb]]);
				}else{
					echo json_encode(['status' => 'error', 'data' => ['msg' => 'could not update infos']]);
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
					
					echo json_encode(['status' => 'success', 'data' => ['img_link' => PUBLIC_URL.'img/'.$res['data']['filename']]]);
				}else{
					// not inserted in DB
					DeletePic('img/'.$res['data']['filename']);
					echo json_encode(['status' => 'error', 'data' => ['msg' => 'file not inserted in database']]);
				}
			}else{
				// not uploaded
				echo json_encode($res);
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

					echo json_encode(['status' => 'success', 'data' => ['img_link' => PUBLIC_URL.'img/'.$res['data']['filename']]]);
				}else{
					// not inserted in DB
					DeletePic('img/'.$res['data']['filename']);
					echo json_encode(['status' => 'error', 'data' => ['msg' => 'file not inserted in database']]);
				}
			}else{
				// not uploaded
				echo json_encode($res);
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
							echo json_encode(['status' => 'success', 'data' => ['msg' => 'password changed']]);
						}else{
							// password didnt change
							echo json_encode(['status' => 'error', 'data' => ['msg' => 'password didnt change']]);
						}
						
					}else{
						// new password doesnt match
						echo json_encode(['status' => 'error', 'data' => ['msg' => 'new password didnt match']]);
					}
				}else{
					// wrong old password
					echo json_encode(['status' => 'error', 'data' => ['msg' => 'Wrong password']]);
				}
			}
		}

		private function Ajoutregistre($id_agence)
		{
			$res = UploadPic($_FILES['rc'], "regitre", "../../img/");

			if ($res['status'] === "success") {
				$mod = new model_agence();

				if ($mod->ImgRC($id_agence, $res['data']['filename'])) {
					return ['status' => 'success', 'data' => ['filename' => $res['data']['filename']]];
				}else{
					// not inserted in db
					DeletePic("../../img/".$res['data']['filename']);
					return ['status' => 'error', 'data' => ['msg' => 'error adding to the database']];
				}
			}else{
				// error with file
				return json_encode($res);
			}
		}

		public function Getrc($id_agence, $tokken)
		{
			$this->forbidden($id_agence, $tokken);

			$mod = new model_agence();

			$img = $mod->GetRC($id_agence);

			if ($img && file_exists("../../img/".$img->rc)) {
				header('Content-type: image/jpeg');
				readfile("../../img/".$img->rc);
			}else{
				echo "no img";
			}			
		}

		private function Ajouthanout($id_agence)
		{
			$res = UploadPic($_FILES['hanout'], "hanout", "../../img/");

			if ($res['status'] === "success") {
				$mod = new model_agence();

				if ($mod->ImgLocal($id_agence, $res['data']['filename'])) {
					return ['status' => 'success', 'data' => ['filename' => $res['data']['filename']]];
				}else{
					// not inserted in db
					DeletePic("../../img/".$res['data']['filename']);
					return ['status' => 'error', 'data' => ['msg' => 'error adding to the database']];
				}
			}else{
				// error with file
				return json_encode($res);
			}
		}

		public function Gethanout($id_agence, $tokken)
		{
			$this->forbidden($id_agence, $tokken);

			$mod = new model_agence();

			$img = $mod->GetHanout($id_agence);

			if ($img && file_exists("../../img/".$img->hanout)) {
				header('Content-type: image/jpeg');
				readfile("../../img/".$img->rc);
			}else{
				echo "no img";
			}
		}

		public function Forgottenpswd()
		{
			// get the posted email address
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$mod = new model_agence();
				// gen validation
				$res = $mod->GenValidation($_POST['email']);

				if ($res['status'] === "success") {
					// send the email
					$link = "https://soukna-dz.com/reset-password/".$res['data']['selector'] ."/".bin2hex($res['data']['tokken']);
					$msg = '<p>Here s your reset link:  </p>';
					$msg .= '<a href="'.$link.'">Here</a>';

					$headers = "From: Soukna-dz <test@soukna-dz.com>\r\n";
					$headers .= "Reply-To: support@soukna-dz.com\r\n";
					$headers .= "Content-type: text/html\r\n";

					mail(strtolower($_POST['email']), "Reset Password", $msg, $headers);

					echo json_encode(['status' => 'success', 'data' => ['msg' => 'email sent']]);
				}else{
					echo json_encode(['status' => 'success', 'data' => ['msg' => 'email sent']]);
				}
			}
		}

		public function Resetpswd()
		{
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				$mod = new model_agence();

				$res = $mod->CheckValidation($_POST['selector'], $_POST['tokken']);
				
				if ($res['status'] === 'error') {
					die(json_encode($res));
				}

				if ($mod->ResetPswd($res['data']['email'])) {
					echo json_encode(['status' => 'success']);
				}else{
					echo json_encode(['status' => 'error', 'data' => ['msg' => 'could not change password']]);
				}

			}
		}

	}

 ?>