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
				case 'immeuble':
				case 'niveau':
				case 'appartement':
					return ['id_local' => $local->id_local,
								'img_local' => ($local->lien && file_exists("img/preview/".$local->lien)) ? PUBLIC_URL."img/preview/".$local->lien : "",
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => ($local->surface) ? $local->surface : "", 
								'chambre' => ($local->nbr_chambre) ? $local->nbr_chambre : "", 
								'bain' => ($local->nbr_bain) ? $local->nbr_bain : "", 
								'etage' => ($local->etage) ? $local->etage : "", 
								'meuble' => ($local->meuble) ? $local->meuble : "", 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => ($local->description_local) ? $local->description_local : "",
								'papier' => ($local->papier) ? json_decode($local->papier) : [],
								'prix' => ($local->prix) ? $local->prix : "",
								'unit' => ($local->unit) ? $local->unit : "da",
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'nom_url' => str_replace(" ", "-", trim($local->nom)) . "-" . $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom];

					break;

				case 'villa':
					return ['id_local' => $local->id_local,
								'img_local' => ($local->lien && file_exists("img/preview/".$local->lien)) ? PUBLIC_URL."img/preview/".$local->lien : "",
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => ($local->surface) ? $local->surface : "", 
								'chambre' => ($local->nbr_chambre) ? $local->nbr_chambre : "", 
								'bain' => ($local->nbr_bain) ? $local->nbr_bain : "", 
								'etage' => $local->etage, 
								'piscine' => $local->piscine, 
								'nbr_garage' => $local->nbr_garage, 
								'jardin' => $local->jardin, 
								'meuble' => $local->meuble, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => ($local->description_local) ? $local->description_local : "",
								'papier' => ($local->papier) ? json_decode($local->papier) : [],
								'prix' => ($local->prix) ? $local->prix : "",
								'unit' => ($local->unit) ? $local->unit : "da",
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'nom_url' => str_replace(" ", "-", trim($local->nom)) . "-" . $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom];

					break;

				case 'arab':
					return ['id_local' => $local->id_local,
								'img_local' => ($local->lien && file_exists("img/preview/".$local->lien)) ? PUBLIC_URL."img/preview/".$local->lien : "",
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => ($local->surface) ? $local->surface : "", 
								'chambre' => ($local->nbr_chambre) ? $local->nbr_chambre : "", 
								'bain' => ($local->nbr_bain) ? $local->nbr_bain : "", 
								'nbr_garage' => $local->nbr_garage, 
								'jardin' => $local->jardin, 
								'meuble' => $local->meuble, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => ($local->description_local) ? $local->description_local : "",
								'papier' => ($local->papier) ? json_decode($local->papier) : [],
								'prix' => ($local->prix) ? $local->prix : "",
								'unit' => ($local->unit) ? $local->unit : "da",
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'nom_url' => str_replace(" ", "-", trim($local->nom)) . "-" . $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom];
					break;

				case 'studio':
					return ['id_local' => $local->id_local,
								'img_local' => ($local->lien && file_exists("img/preview/".$local->lien)) ? PUBLIC_URL."img/preview/".$local->lien : "",
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => ($local->surface) ? $local->surface : "", 
								'bain' => ($local->nbr_bain) ? $local->nbr_bain : "", 
								'meuble' => $local->meuble, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => ($local->description_local) ? $local->description_local : "",
								'papier' => ($local->papier) ? json_decode($local->papier) : [],
								'prix' => ($local->prix) ? $local->prix : "",
								'unit' => ($local->unit) ? $local->unit : "da",
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'nom_url' => str_replace(" ", "-", trim($local->nom)) . "-" . $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom];
					break;

				case 'autre':
				case 'local':
				case 'hangar':
				case 'usine':
				case 'terrain':
					return ['id_local' => $local->id_local,
								'img_local' => ($local->lien && file_exists("img/preview/".$local->lien)) ? PUBLIC_URL."img/preview/".$local->lien : "",
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => ($local->surface) ? $local->surface : "", 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => ($local->description_local) ? $local->description_local : "",
								'papier' => ($local->papier) ? json_decode($local->papier) : [],
								'prix' => ($local->prix) ? $local->prix : "",
								'unit' => ($local->unit) ? $local->unit : "da",
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'nom_url' => str_replace(" ", "-", trim($local->nom)) . "-" . $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom];
					break;
				
				case 'carcasse':
					return ['id_local' => $local->id_local,
								'img_local' => ($local->lien && file_exists("img/preview/".$local->lien)) ? PUBLIC_URL."img/preview/".$local->lien : "",
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => ($local->surface) ? $local->surface : "", 
								'chambre' => ($local->nbr_chambre) ? $local->nbr_chambre : "", 
								'bain' => ($local->nbr_bain) ? $local->nbr_bain : "", 
								'etage' => $local->etage, 
								'nbr_garage' => $local->nbr_garage, 
								'jardin' => $local->jardin, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => ($local->description_local) ? $local->description_local : "",
								'papier' => ($local->papier) ? json_decode($local->papier) : [],
								'prix' => ($local->prix) ? $local->prix : "",
								'unit' => ($local->unit) ? $local->unit : "da",
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'nom_url' => str_replace(" ", "-", trim($local->nom)) . "-" . $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom];

					break;
				
				case 'bungalow':
					return ['id_local' => $local->id_local,
								'img_local' => ($local->lien && file_exists("img/preview/".$local->lien)) ? PUBLIC_URL."img/preview/".$local->lien : "",
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => ($local->surface) ? $local->surface : "", 
								'chambre' => ($local->nbr_chambre) ? $local->nbr_chambre : "", 
								'bain' => ($local->nbr_bain) ? $local->nbr_bain : "", 
								'meuble' => $local->meuble, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => ($local->description_local) ? $local->description_local : "",
								'papier' => ($local->papier) ? json_decode($local->papier) : [],
								'prix' => ($local->prix) ? $local->prix : "",
								'unit' => ($local->unit) ? $local->unit : "da",
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'nom_url' => str_replace(" ", "-", trim($local->nom)) . "-" . $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom];
					break;
			}
		}

		private function DetailLocalJson($local)
		{
			switch ($local->type) {
				case 'immeuble':
				case 'niveau':
				case 'appartement':
					return ['id_local' => $local->id_local,
								'img_local' => ($local->lien && file_exists("img/preview/".$local->lien)) ? PUBLIC_URL."img/preview/".$local->lien : "",
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => ($local->surface) ? $local->surface : "",
								'chambre' => ($local->nbr_chambre) ? $local->nbr_chambre : "", 
								'bain' => ($local->nbr_bain) ? $local->nbr_bain : "", 
								'etage' => $local->etage, 
								'meuble' => $local->meuble, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => ($local->description_local) ? $local->description_local : "",
								'papier' => ($local->papier) ? json_decode($local->papier) : [],
								'prix' => ($local->prix) ? $local->prix : "",
								'unit' => ($local->unit) ? $local->unit : "da",
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'nom_url' => str_replace(" ", "-", trim($local->nom)) . "-" . $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom,
								'address' => $local->address,
								'email' => $local->email,
								'tel1' => $local->tel1,
								'tel2' => $local->tel2,
								'fb' => $local->fb];

					break;

				case 'villa':
					return ['id_local' => $local->id_local,
								'img_local' => ($local->lien && file_exists("img/preview/".$local->lien)) ? PUBLIC_URL."img/preview/".$local->lien : "",
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => ($local->surface) ? $local->surface : "", 
								'chambre' => ($local->nbr_chambre) ? $local->nbr_chambre : "", 
								'bain' => ($local->nbr_bain) ? $local->nbr_bain : "", 
								'etage' => $local->etage, 
								'piscine' => $local->piscine, 
								'nbr_garage' => $local->nbr_garage, 
								'jardin' => $local->jardin, 
								'meuble' => $local->meuble, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => ($local->description_local) ? $local->description_local : "",
								'papier' => ($local->papier) ? json_decode($local->papier) : [],
								'prix' => ($local->prix) ? $local->prix : "",
								'unit' => ($local->unit) ? $local->unit : "da",
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'nom_url' => str_replace(" ", "-", trim($local->nom)) . "-" . $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom,
								'address' => $local->address,
								'email' => $local->email,
								'tel1' => $local->tel1,
								'tel2' => $local->tel2,
								'fb' => $local->fb];

					break;

				case 'arab':
					return ['id_local' => $local->id_local,
								'img_local' => ($local->lien && file_exists("img/preview/".$local->lien)) ? PUBLIC_URL."img/preview/".$local->lien : "",
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => ($local->surface) ? $local->surface : "", 
								'chambre' => ($local->nbr_chambre) ? $local->nbr_chambre : "", 
								'bain' => ($local->nbr_bain) ? $local->nbr_bain : "", 
								'nbr_garage' => $local->nbr_garage, 
								'jardin' => $local->jardin, 
								'meuble' => $local->meuble, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => ($local->description_local) ? $local->description_local : "",
								'papier' => ($local->papier) ? json_decode($local->papier) : [],
								'prix' => ($local->prix) ? $local->prix : "",
								'unit' => ($local->unit) ? $local->unit : "da",
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'nom_url' => str_replace(" ", "-", trim($local->nom)) . "-" . $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom,
								'address' => $local->address,
								'email' => $local->email,
								'tel1' => $local->tel1,
								'tel2' => $local->tel2,
								'fb' => $local->fb];
					break;

				case 'studio':
					return ['id_local' => $local->id_local,
								'img_local' => ($local->lien && file_exists("img/preview/".$local->lien)) ? PUBLIC_URL."img/preview/".$local->lien : "",
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => ($local->surface) ? $local->surface : "",
								'bain' => ($local->nbr_bain) ? $local->nbr_bain : "", 
								'meuble' => $local->meuble, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => ($local->description_local) ? $local->description_local : "",
								'papier' => ($local->papier) ? json_decode($local->papier) : [],
								'prix' => ($local->prix) ? $local->prix : "",
								'unit' => ($local->unit) ? $local->unit : "da",
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'nom_url' => str_replace(" ", "-", trim($local->nom)) . "-" . $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom,
								'address' => $local->address,
								'email' => $local->email,
								'tel1' => $local->tel1,
								'tel2' => $local->tel2,
								'fb' => $local->fb];
					break;

				case 'autre':
				case 'local':
				case 'hangar':
				case 'usine':
				case 'terrain':
					return ['id_local' => $local->id_local,
								'img_local' => ($local->lien && file_exists("img/preview/".$local->lien)) ? PUBLIC_URL."img/preview/".$local->lien : "",
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => ($local->surface) ? $local->surface : "",
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => ($local->description_local) ? $local->description_local : "",
								'papier' => ($local->papier) ? json_decode($local->papier) : [],
								'prix' => ($local->prix) ? $local->prix : "",
								'unit' => ($local->unit) ? $local->unit : "da",
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'nom_url' => str_replace(" ", "-", trim($local->nom)) . "-" . $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom,
								'address' => $local->address,
								'email' => $local->email,
								'tel1' => $local->tel1,
								'tel2' => $local->tel2,
								'fb' => $local->fb];
					break;
				
				case 'carcasse':
					return ['id_local' => $local->id_local,
								'img_local' => ($local->lien && file_exists("img/preview/".$local->lien)) ? PUBLIC_URL."img/preview/".$local->lien : "",
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => ($local->surface) ? $local->surface : "",
								'chambre' => ($local->nbr_chambre) ? $local->nbr_chambre : "", 
								'bain' => ($local->nbr_bain) ? $local->nbr_bain : "", 
								'etage' => $local->etage, 
								'nbr_garage' => $local->nbr_garage, 
								'jardin' => $local->jardin, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => ($local->description_local) ? $local->description_local : "",
								'papier' => ($local->papier) ? json_decode($local->papier) : [],
								'prix' => ($local->prix) ? $local->prix : "",
								'unit' => ($local->unit) ? $local->unit : "da",
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'nom_url' => str_replace(" ", "-", trim($local->nom)) . "-" . $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom,
								'address' => $local->address,
								'email' => $local->email,
								'tel1' => $local->tel1,
								'tel2' => $local->tel2,
								'fb' => $local->fb];

					break;
				
				case 'bungalow':
					return ['id_local' => $local->id_local,
								'img_local' => ($local->lien && file_exists("img/preview/".$local->lien)) ? PUBLIC_URL."img/preview/".$local->lien : "",
								'wilaya' => $local->wilaya, 
								'commune' => $local->commune, 
								'type' => $local->type, 
								'vente_location' => $local->vl, 
								'surface' => ($local->surface) ? $local->surface : "",
								'chambre' => ($local->nbr_chambre) ? $local->nbr_chambre : "", 
								'bain' => ($local->nbr_bain) ? $local->nbr_bain : "", 
								'meuble' => $local->meuble, 
								'date' => date("d-m-Y", strtotime($local->date)), 
								'description' => ($local->description_local) ? $local->description_local : "",
								'papier' => ($local->papier) ? json_decode($local->papier) : [],
								'prix' => ($local->prix) ? $local->prix : "",
								'unit' => ($local->unit) ? $local->unit : "da",
								'etat' => $local->etat_local,
								'id_agence' => $local->id_agence,
								'nom_url' => str_replace(" ", "-", trim($local->nom)) . "-" . $local->id_agence,
								'img_agence' => PUBLIC_URL."img/".$local->Img_prof,
								'nom_agence' => $local->nom,
								'address' => $local->address,
								'email' => $local->email,
								'tel1' => $local->tel1,
								'tel2' => $local->tel2,
								'fb' => $local->fb];
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
				$json["data"]["infos"] = ['nombre_page' => ceil($this->local_mod->SearchCount($wilaya, $commune, $type, $vl) / 21), 'page' => $page];

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

		public function Random($limit)
		{
			$data = $this->local_mod->Random($limit);
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

		public function Byagence($id_agence, $page, $owner, $vl, $type, $etat_local)
		{
			$data = $this->local_mod->ByAgence($id_agence, $page, $owner, $vl, $type, $etat_local);
			$json = [];

			if ($data) {
				$json["status"] = ['status' => "success"];
				$json["data"] = ['nombre_page' => ceil($this->local_mod->CountByAgence($id_agence, $owner, $vl, $type, $etat_local) / 21), 'page' => $page];

				foreach ($data as $local) {
					$json["data"]["local"][] = $this->LocalJson($local);
				}
			}else{
				$json = ['status' => 'error', 'data' => ['msg' => 'no result found']];
			}

			echo json_encode($json);

		}

		public function Detail($id_local, $owner = false)
		{
			$data = $this->local_mod->Detail($id_local, $owner);
			$json = [];

			if ($data) {
				$json["status"] = "success";
				$json['data'] = $this->DetailLocalJson($data);
			}else{
				$json = ['status' => 'error', 'data' => ['msg' => 'no result found']];
			}

			echo json_encode($json);
		}

		public function Images($id_local)
		{
			$mod = new model_image();

			$data = $mod->GetImages($id_local);

			if ($data) {
				$json = ['status' => 'success'];
				foreach($data as $img){
					$json['data']['images'][] = ['id_img' => $img->id_image,
												'img_link' => PUBLIC_URL . 'img/' . $img->lien,
												'img_name' => $img->lien];
				}

				echo json_encode($json);
			}else{
				echo json_encode(['status' => 'error', 'data' => ['msg' => 'no images found']]);
			}
		}

	}

 ?>