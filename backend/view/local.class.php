<?php 

	/**
	 * 
	 */
	class view_local
	{
		private $local_mod;

		function __construct()
		{
			$this->local_mod = new model_local();
		}

		private function LocalJson($local)
		{
			switch ($local->type) {
				case 'appartement':
					return ['id_local' => $local->id_local,
								'img_local' => PUBLIC_URL."img/".$local->lien,
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => $local->surface, 
								'chambre' => $local->nbr_chambre, 
								'bain' => $local->nbr_bain, 
								'etage' => $local->etage, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => $local->description_local,
								'prix' => $local->prix,
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom];

					break;

				case 'villa':
					return ['id_local' => $local->id_local,
								'img_local' => PUBLIC_URL."img/".$local->lien,
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => $local->surface, 
								'chambre' => $local->nbr_chambre, 
								'bain' => $local->nbr_bain, 
								'piscine' => $local->piscine, 
								'nbr_garage' => $local->nbr_garage, 
								'jardin' => $local->jardin, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => $local->description_local,
								'prix' => $local->prix,
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom];

					break;

				case 'arab':
					return ['id_local' => $local->id_local,
								'img_local' => PUBLIC_URL."img/".$local->lien,
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => $local->surface, 
								'chambre' => $local->nbr_chambre, 
								'bain' => $local->nbr_bain, 
								'nbr_garage' => $local->nbr_garage, 
								'jardin' => $local->jardin, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => $local->description_local,
								'prix' => $local->prix,
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom];
					break;

				case 'studio':
					return ['id_local' => $local->id_local,
								'img_local' => PUBLIC_URL."img/".$local->lien,
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => $local->surface, 
								'bain' => $local->nbr_bain, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => $local->description_local,
								'prix' => $local->prix,
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom];
					break;

				case 'terrain':
					return ['id_local' => $local->id_local,
								'img_local' => PUBLIC_URL."img/".$local->lien,
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => $local->surface, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => $local->description_local,
								'prix' => $local->prix,
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Imgprof,
								'nom_agence' => $local->nom];
					break;
				
			}
		}

		public function Search($page, $wilaya, $commune, $type, $vl)
		{
			$data = $this->local_mod->Search($page, $wilaya, $commune, $type, $vl);
			$json = [];

			if ($data) {
				// there is data
				$json["status"] = "success";
				$json["data"]["infos"] = ['nombre_page' => ceil($this->local_mod->SearchCount($wilaya, $commune, $type, $vl) / 20)];

				foreach ($data as $local) {
					$json["data"]["local"][] = $this->LocalJson($local);
				}

			}else{
				$json = ['status' => 'error', 'data' => ['msg' => 'no local found']];
			}

			echo json_encode($json);
		}
	
		public function Latest($limit)
		{
			$data = $this->local_mod->Latest($limit);
			$json = [];

			if ($data) {
				$json["status"] = "success";
				foreach ($data as $local) {
					$json["data"]["local"][] = $this->LocalJson($local);
				}
			}else{
				$json = ['status' => 'error', 'data' => ['msg' => 'no result found']];
			}
			echo json_encode($json);
		}

		public function Byagence($id_agence, $page, $owner)
		{
			$data = $this->local_mod->ByAgence($id_agence, $page, $owner);
			$json = [];

			if ($data) {
				$json["status"] = ['status' => "success"];
				$json["data"] = ['nombre_page' => ceil($this->local_mod->CountByAgence($id_agence, $owner) / 20)];

				foreach ($data as $local) {
					$json["data"]["local"][] = $this->LocalJson($local);
				}
			}else{
				$json = ['status' => 'error', 'data' => ['msg' => 'no result found']];
			}

			echo json_encode($json);

		}

		public function Detail($id_local)
		{
			$data = $this->local_mod->Detail($id_agence);
			$json = [];

			if ($data) {
				$json["status"] = "success";
				$json['data'] = $this->LocalJson($data);
			}else{
				$json = ['status' => 'error', 'data' => ['msg' => 'no result found']];
			}

			echo json_encode($json);
		}

	}

 ?>