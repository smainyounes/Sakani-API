<?php 

	/**
	 * 
	 */
	class controller_user
	{
		
		function __construct()
		{
			# code...
		}

		public function NewUser()
		{
			$mod = new model_user();

			$res = $mod->NewGuest();

			if ($res) {
				echo json_encode($res);
			}else{
				echo json_encode(['status' => 'error']);
			}
		}

		public function CheckUser($id_user, $tokken)
		{
			$mod = new model_user();

			if ($mod->CheckTokken($id_user, $tokken)) {
				echo json_encode(['status' => 'success', 'result' => 'true']);
			}else{
				echo json_encode(['status' => 'success', 'result' => 'false']);
			}
		}
	}

 ?>