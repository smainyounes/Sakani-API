<?php 

	/**
	 * 
	 */
	class view_agence
	{
		private $mod_agence;
		
		function __construct()
		{
			$this->mod_agence = new model_agence();
		}

		public function JsonAgence($agence)
		{
			return ['id_agence' => $agence->id_agence,
					'nom' => $agence->nom,
					'address' => $agence->address,
					'tel1' => $agence->tel1,
					'tel2' => $agence->tel2,
					'fb' => $agence->fb,
					'img_prof' => PUBLIC_URL."img/".$agence->Img_prof,
					'img_cover' => PUBLIC_URL."img/".$agence->Img_cover,
					'nom_url' => str_replace(" ", "-", trim($agence->nom)) . "-" . $agence->id_agence];
		}

		public function Latest($limit)
		{
			$data = $this->mod_agence->GetLatest($limit);

			$json = [];

			if ($data) {
				$json["status"] = "success";

				foreach ($data as $agence) {
					$json["data"]["agence"][] = $this->JsonAgence($agence);
				}

			}else{
				$json = ['status' => 'error', 'data' => ['msg' => 'no result found']];
			}

			echo json_encode($json);
		}
	
		public function Detail($id_agence, $nom = null)
		{
			$data = $this->mod_agence->Detail($id_agence, $nom);

			$json = [];

			if ($data) {
				$json = ['status' => 'success', 'data' => $this->JsonAgence($data)];
			}else{
				$json = ['status' => 'error', 'data' => ['msg' => 'no result found']];
			}

			echo json_encode($json);
		}

	}

 ?>