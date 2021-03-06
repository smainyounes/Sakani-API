<?php 

	/**
	 * 
	 */
	class model_local extends lib_database
	{
		
		function __construct()
		{
			parent::__construct();
		}

		/**
		 * Getters
		 */

		public function GetAll($page, $limit = 21)
		{
			$limit = (int) $limit;
			$start = ($page - 1) * $limit;

			$sql = "SELECT *, local.id_local AS id_local
					FROM ((local
					INNER JOIN agence ON local.id_agence = agence.id_agence)
					LEFT JOIN image ON local.id_local = image.id_local AND image.main = 1) 
					WHERE agence.etat_agence = :etat AND local.etat_local = :etat2 ORDER BY local.id_local DESC LIMIT :lim OFFSET :start";

			$this->query($sql);
			$this->bind(":etat", "active");
			$this->bind(":etat2", "active");
			$this->bind(":lim", $limit);
			$this->bind(":start", $start);
			
			return $this->resultSet();
		}

		public function CountAll()
		{
			$sql = "SELECT COUNT(local.id_local) nbr
					FROM (local
					INNER JOIN agence ON local.id_agence = agence.id_agence) 
					WHERE agence.etat_agence = :etat AND local.etat_local = :etat2";
			$this->query($sql);
			$this->bind(":etat", "active");
			$this->bind(":etat2", "active");

			$res = $this->single();

			return $res->nbr;
		}

		public function Stats($id_agence)
		{
			$this->query("SELECT SUM(CASE when etat_local = 'active' then 1 else 0 end) AS active, SUM(CASE when etat_local = 'desactive' then 1 else 0 end) AS desactive, SUM(CASE when etat_local = 'vendu' then 1 else 0 end) AS vendu FROM local WHERE id_agence = :id");

			$this->bind(":id", $id_agence);

			try {
				return $this->single();
			} catch (Exception $e) {
				return false;
			}
		}

		public function Detail($id_local, $owner)
		{
			$sql = "SELECT *, local.id_local AS id_local
					FROM ((local
					INNER JOIN agence ON local.id_agence = agence.id_agence)
					LEFT JOIN image ON local.id_local = image.id_local AND image.main = 1) 
					WHERE local.id_local = :id";

			$etat = "active";

			if ($owner) {
				$sql .= " AND etat_local != :etat";
				$etat = "deleted";
			}else{
				$sql .= " AND etat_local = :etat";
			}

			$this->query($sql);

			$this->bind(":id", $id_local);
			$this->bind(":etat", $etat);

			return $this->single();
		}

		public function ByAgence($id_agence, $page, $owner, $vl, $type, $etat_local)
		{
			$limit = 21;
			$start = ($page - 1) * $limit;

			$conc = "";

			if ($owner) {
				if ($etat_local === "tout") {
					$conc = "AND local.etat_local != :etat_local";
					$etat_local = "deleted";
				}else{
					$conc = "AND local.etat_local = :etat_local";
				}
				
			}else{
				$conc = "AND agence.etat_agence = :etat AND local.etat_local = :etat_local";
				$etat_local = "active";
			}

			if ($vl !== "tout") {
				$conc .= " AND local.vl = :vl";
			}

			if ($type !== "tout") {
				$conc .= " AND local.type = :type";
			}

			$sql = "SELECT *, local.id_local AS id_local
							FROM ((local
							INNER JOIN agence ON local.id_agence = agence.id_agence)
							LEFT JOIN image ON local.id_local = image.id_local AND image.main = 1) 
							WHERE agence.id_agence = :id $conc ORDER BY local.id_local DESC LIMIT $limit OFFSET $start";

			$this->query($sql);

			$this->bind(":id", $id_agence);
			$this->bind(":etat_local", $etat_local);

			if (!$owner) {
				$this->bind(":etat", "active");
			}

			if ($vl !== "tout") {
				$this->bind(":vl", $vl);
			}

			if ($type !== "tout") {
				$this->bind(":type", $type);
			}

			return $this->resultSet();
		}

		public function Search($page, $wilaya, $commune, $type, $vl)
		{
			$limit = 21;
			$start = ($page - 1) * $limit;

			$conc = "";

			if ($wilaya !== "0") {
				$conc .= " AND local.wilaya = :wilaya";
			}

			if ($commune !== "0") {
				$conc .= " AND local.commune = :commune";
			}

			if ($type !== "tout") {
				$conc .= " AND local.type = :type";
			}

			if ($vl !== "tout") {
				$conc .= " AND local.vl = :vl";
			}

			$sql = "SELECT *, local.id_local AS id_local
					FROM ((local
					INNER JOIN agence ON local.id_agence = agence.id_agence)
					LEFT JOIN image ON local.id_local = image.id_local AND image.main = 1) 
					WHERE agence.etat_agence = :etat AND local.etat_local = :etat2 $conc ORDER BY local.id_local DESC LIMIT $limit OFFSET $start";

			$this->query($sql);

			$this->bind(":etat", "active");
			$this->bind(":etat2", "active");


			if ($wilaya !== "0") {
				$this->bind(":wilaya", $wilaya);
			}

			if ($commune !== "0") {
				$this->bind(":commune", $commune);
			}

			if ($type !== "tout") {
				$this->bind(":type", $type);
			}

			if ($vl !== "tout") {
				$this->bind(":vl", $vl);
			}

			return $this->resultSet();
		}

		public function SearchCount($wilaya, $commune, $type, $vl)
		{
			$conc = "";

			if ($wilaya !== "0") {
				$conc .= " AND local.wilaya = :wilaya";
			}

			if ($commune !== "0") {
				$conc .= " AND local.commune = :commune";
			}

			if ($type !== "tout") {
				$conc .= " AND local.type = :type";
			}

			if ($vl !== "tout") {
				$conc .= " AND local.vl = :vl";
			}

			$sql = "SELECT COUNT(id_local) nbr FROM local INNER JOIN agence ON agence.id_agence = local.id_agence WHERE agence.etat_agence = :etat AND local.etat_local = :etat2 $conc";

			$this->query($sql);

			$this->bind(":etat", "active");
			$this->bind(":etat2", "active");


			if ($wilaya !== "0") {
				$this->bind(":wilaya", $wilaya);
			}

			if ($commune !== "0") {
				$this->bind(":commune", $commune);
			}

			if ($type !== "tout") {
				$this->bind(":type", $type);
			}

			if ($vl !== "tout") {
				$this->bind(":vl", $vl);
			}

			$res = $this->single();
			return $res->nbr;
		}

		public function CountByAgence($id_agence, $owner, $vl, $type, $etat_local)
		{
			if ($owner) {
				if ($etat_local === "tout") {
					$conc = "AND local.etat_local != :etat_local";
					$etat_local = "deleted";
				}else{
					$conc = "AND local.etat_local = :etat_local";
				}
				
			}else{
				$conc = "AND agence.etat_agence = :etat AND local.etat_local = :etat_local";
				$etat_local = "active";
			}

			if ($vl !== "tout") {
				$conc .= " AND local.vl = :vl";
			}

			if ($type !== "tout") {
				$conc .= " AND local.type = :type";
			}

			$sql = "SELECT COUNT(id_local) nbr FROM local INNER JOIN agence ON agence.id_agence = local.id_agence WHERE agence.id_agence = :id $conc";

			$this->query($sql);

			$this->bind(":id", $id_agence);
			$this->bind(":etat_local", $etat_local);

			if (!$owner) {
				$this->bind(":etat", "active");
			}

			if ($vl !== "tout") {
				$this->bind(":vl", $vl);
			}

			if ($type !== "tout") {
				$this->bind(":type", $type);
			}

			$res = $this->single();
			return $res->nbr;
		}

		public function Suggest($wilaya, $commune)
		{
			# code...
		}

		public function Latest($limit)
		{
			$sql = "SELECT *, local.id_local AS id_local
					FROM ((local
					INNER JOIN agence ON local.id_agence = agence.id_agence)
					LEFT JOIN image ON local.id_local = image.id_local AND image.main = 1) 
					WHERE agence.etat_agence = :etat AND local.etat_local = :etat2 ORDER BY local.id_local DESC LIMIT :lim";

			$this->query($sql);

			$this->bind(":etat", "active");
			$this->bind(":etat2", "active");
			$this->bind(":lim", $limit);

			return $this->resultSet();
		}

		public function Random($limit)
		{
			$sql = "SELECT *, local.id_local AS id_local
					FROM ((local
					INNER JOIN agence ON local.id_agence = agence.id_agence)
					LEFT JOIN image ON local.id_local = image.id_local AND image.main = 1) 
					WHERE agence.etat_agence = :etat AND local.etat_local = :etat2 ORDER BY RAND() LIMIT :lim";

			$this->query($sql);

			$this->bind(":etat", "active");
			$this->bind(":etat2", "active");
			$this->bind(":lim", $limit);

			return $this->resultSet();
		}

		/**
		 * Setters
		 */

		public function AjouterInfos($id_agence)
		{
			$this->nulling();

			if ($_POST['unit'] !== "da" && $_POST['unit'] !== "million" && $_POST['unit'] !== "milliard") {
				return false;
			}

			$pap = NULL;

			if (isset($_POST['papier'])) {
				$pap = [];
				$_POST['papier'] = json_decode($_POST['papier']);
				foreach ($_POST['papier'] as $papier) {
					if ($papier === "act" || $papier === "livret" || $papier === "permit" || $papier === "promesse") {
						$pap[] = $papier;
					}
				}
				if (empty($pap)) {
					$pap = NULL;
				}else{
					$pap = json_encode($pap);
				}
			}

			switch (strtolower($_POST['type'])) {
				case 'immeuble':
				case 'niveau':
				case 'appartement':
					$this->query("INSERT INTO local(id_agence, wilaya, commune, type, vl, surface, nbr_chambre, etage, nbr_bain, meuble, description_local, papier, prix, unit, etat_local) VALUES(:id, :wil, :com, :ty, :vl, :sur, :nc, :et, :bain, :meuble, :descr, :papier, :pri, :unit, :etat)");
					$this->bind(":id", $id_agence);
					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":nc", strip_tags(trim($_POST['nbr_chambre'])));
					$this->bind(":et", strip_tags(trim($_POST['etage'])));
					$this->bind(":bain", strip_tags(trim($_POST['nbr_bain'])));
					$this->bind(":meuble", strip_tags(trim($_POST['meuble'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":papier", $pap);
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":unit", strip_tags(trim($_POST['unit'])));
					$this->bind(":etat", "active");
					break;
				
				case 'villa':
					$this->query("INSERT INTO local(id_agence, wilaya, commune, type, vl, surface, nbr_chambre, etage, nbr_bain, piscine, nbr_garage, jardin, meuble, description_local, papier, prix, unit, etat_local) VALUES(:id, :wil, :com, :ty, :vl, :sur, :nc, :et, :bain, :pisc, :gar, :jard, :meuble, :descr, :papier, :pri, :unit, :etat)");
					$this->bind(":id", $id_agence);
					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":nc", strip_tags(trim($_POST['nbr_chambre'])));
					$this->bind(":et", strip_tags(trim($_POST['etage'])));
					$this->bind(":bain", strip_tags(trim($_POST['nbr_bain'])));
					$this->bind(":pisc", strip_tags(trim($_POST['piscine'])));
					$this->bind(":gar", strip_tags(trim($_POST['garage'])));
					$this->bind(":jard", strip_tags(trim($_POST['jardin'])));
					$this->bind(":meuble", strip_tags(trim($_POST['meuble'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":papier", $pap);
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":unit", strip_tags(trim($_POST['unit'])));
					$this->bind(":etat", "active");
					break;
				
				case 'arab':
					$this->query("INSERT INTO local(id_agence, wilaya, commune, type, vl, surface, nbr_chambre, nbr_bain, jardin, nbr_garage, meuble, description_local, papier, prix, unit, etat_local) VALUES(:id, :wil, :com, :ty, :vl, :sur, :nc, :bain, :jard, :gar, :meuble, :descr, :papier, :pri, :unit, :etat)");
					$this->bind(":id", $id_agence);
					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":nc", strip_tags(trim($_POST['nbr_chambre'])));
					$this->bind(":bain", strip_tags(trim($_POST['nbr_bain'])));
					$this->bind(":jard", strip_tags(trim($_POST['jardin'])));
					$this->bind(":gar", strip_tags(trim($_POST['garage'])));
					$this->bind(":meuble", strip_tags(trim($_POST['meuble'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":papier", $pap);
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":unit", strip_tags(trim($_POST['unit'])));
					$this->bind(":etat", "active");
					break;
				
				case 'studio':
					$this->query("INSERT INTO local(id_agence, wilaya, commune, type, vl, surface, nbr_bain, meuble, description_local, papier, prix, unit, etat_local) VALUES(:id, :wil, :com, :ty, :vl, :sur, :bain, :meuble, :descr, :papier, :pri, :unit, :etat)");
					$this->bind(":id", $id_agence);
					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":bain", strip_tags(trim($_POST['nbr_bain'])));
					$this->bind(":meuble", strip_tags(trim($_POST['meuble'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":papier", $pap);
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":unit", strip_tags(trim($_POST['unit'])));
					$this->bind(":etat", "active");
					break;
				
				case 'autre':
				case 'local':
				case 'hangar':
				case 'usine':
				case 'terrain':
					$this->query("INSERT INTO local(id_agence, wilaya, commune, type, vl, surface, description_local, papier, prix, unit, etat_local) VALUES(:id, :wil, :com, :ty, :vl, :sur, :descr, :papier, :pri, :unit, :etat)");
					$this->bind(":id", $id_agence);
					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":papier", $pap);
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":unit", strip_tags(trim($_POST['unit'])));
					$this->bind(":etat", "active");
					break;

				case 'carcasse':
					$this->query("INSERT INTO local(id_agence, wilaya, commune, type, vl, surface, nbr_chambre, etage, nbr_garage, jardin, description_local, papier, prix, unit, etat_local) VALUES(:id, :wil, :com, :ty, :vl, :sur, :nc, :et, :gar, :jard, :descr, :papier, :pri, :unit, :etat)");
					$this->bind(":id", $id_agence);
					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":nc", strip_tags(trim($_POST['nbr_chambre'])));
					$this->bind(":et", strip_tags(trim($_POST['etage'])));
					$this->bind(":gar", strip_tags(trim($_POST['garage'])));
					$this->bind(":jard", strip_tags(trim($_POST['jardin'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":papier", $pap);
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":unit", strip_tags(trim($_POST['unit'])));
					$this->bind(":etat", "active");
					break;

				case 'bungalow':
					$this->query("INSERT INTO local(id_agence, wilaya, commune, type, vl, surface, nbr_chambre, nbr_bain, meuble, description_local, papier, prix, unit, etat_local) VALUES(:id, :wil, :com, :ty, :vl, :sur, :nc, :bain, :meuble, :descr, :papier, :pri, :unit, :etat)");
					$this->bind(":id", $id_agence);
					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":nc", strip_tags(trim($_POST['nbr_chambre'])));
					$this->bind(":bain", strip_tags(trim($_POST['nbr_bain'])));
					$this->bind(":meuble", strip_tags(trim($_POST['meuble'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":papier", $pap);
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":unit", strip_tags(trim($_POST['unit'])));
					$this->bind(":etat", "active");
					break;

				default:
					return false;
					break;
			}

			try {
				$this->execute();
				return $this->LastId();
			} catch (Exception $e) {
				return false;
			}
		}

		public function EditInfos($id_local)
		{
			$this->nulling();
			
			if ($_POST['unit'] !== "da" && $_POST['unit'] !== "million" && $_POST['unit'] !== "milliard") {
				return false;
			}

			$pap = NULL;

			if (isset($_POST['papier'])) {
				$pap = [];
				$_POST['papier'] = json_decode($_POST['papier']);
				foreach ($_POST['papier'] as $papier) {
					if ($papier === "act" || $papier === "livret" || $papier === "permit" || $papier === "promesse") {
						$pap[] = $papier;
					}
				}
				if (empty($pap)) {
					$pap = NULL;
				}else{
					$pap = json_encode($pap);
				}
			}

			switch (strtolower($_POST['type'])) {
				case 'immeuble':
				case 'niveau':
				case 'appartement':
					$this->query("UPDATE local SET wilaya = :wil, commune = :com, type = :ty, vl = :vl, surface = :sur, nbr_chambre = :nc, etage = :et, nbr_bain = :bain, prix = :pri, meuble = :meuble, description_local = :descr, papier = :papier, unit = :unit WHERE id_local = :id");

					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":nc", strip_tags(trim($_POST['nbr_chambre'])));
					$this->bind(":et", strip_tags(trim($_POST['etage'])));
					$this->bind(":bain", strip_tags(trim($_POST['nbr_bain'])));
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":meuble", strip_tags(trim($_POST['meuble'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":papier", $pap);
					$this->bind(":unit", strip_tags(trim($_POST['unit'])));

					$this->bind(":id", $id_local);
					break;
				
				case 'villa':
					$this->query("UPDATE local SET wilaya = :wil, commune = :com, type = :ty, vl = :vl, surface = :sur, nbr_chambre = :nc, etage = :et, nbr_bain = :bain, piscine = :pisc, nbr_garage = :gar, jardin = :jard, prix = :pri, meuble = :meuble, description_local = :descr, papier = :papier, unit = :unit WHERE id_local = :id");

					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":nc", strip_tags(trim($_POST['nbr_chambre'])));
					$this->bind(":et", strip_tags(trim($_POST['etage'])));
					$this->bind(":bain", strip_tags(trim($_POST['nbr_bain'])));
					$this->bind(":pisc", strip_tags(trim($_POST['piscine'])));
					$this->bind(":gar", strip_tags(trim($_POST['garage'])));
					$this->bind(":jard", strip_tags(trim($_POST['jardin'])));
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":meuble", strip_tags(trim($_POST['meuble'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":papier", $pap);
					$this->bind(":unit", strip_tags(trim($_POST['unit'])));

					$this->bind(":id", $id_local);
					break;
				
				case 'arab':
					$this->query("UPDATE local SET wilaya = :wil, commune = :com, type = :ty, vl = :vl, surface = :sur, nbr_chambre = :nc, nbr_bain = :bain, jardin = :jard, nbr_garage = :gar, prix = :pri, meuble = :meuble, description_local = :descr, papier = :papier, unit = :unit WHERE id_local = :id");

					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":nc", strip_tags(trim($_POST['nbr_chambre'])));
					$this->bind(":bain", strip_tags(trim($_POST['nbr_bain'])));
					$this->bind(":jard", strip_tags(trim($_POST['jardin'])));
					$this->bind(":gar", strip_tags(trim($_POST['garage'])));
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":meuble", strip_tags(trim($_POST['meuble'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":papier", $pap);
					$this->bind(":unit", strip_tags(trim($_POST['unit'])));

					$this->bind(":id", $id_local);
					break;
				
				case 'studio':
					$this->query("UPDATE local SET wilaya = :wil, commune = :com, type = :ty, vl = :vl, surface = :sur, nbr_bain = :bain, prix = :pri, meuble = :meuble, description_local = :descr, papier = :papier, unit = :unit WHERE id_local = :id");

					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":bain", strip_tags(trim($_POST['nbr_bain'])));
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":meuble", strip_tags(trim($_POST['meuble'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":papier", $pap);
					$this->bind(":unit", strip_tags(trim($_POST['unit'])));

					$this->bind(":id", $id_local);
					break;
				
				case 'autre':
				case 'local':
				case 'hangar':
				case 'usine':
				case 'terrain':
					$this->query("UPDATE local SET wilaya = :wil, commune = :com, type = :ty, vl = :vl, surface = :sur, prix = :pri, description_local = :descr, papier = :papier, unit = :unit WHERE id_local = :id");

					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":papier", $pap);
					$this->bind(":unit", strip_tags(trim($_POST['unit'])));

					$this->bind(":id", $id_local);
					break;
				
				case 'carcasse':
					$this->query("UPDATE local SET wilaya = :wil, commune = :com, type = :ty, vl = :vl, surface = :sur, nbr_chambre = :nc, etage = :et, nbr_garage = :gar, jardin = :jard, prix = :pri, description_local = :descr, papier = :papier, unit = :unit WHERE id_local = :id");

					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":nc", strip_tags(trim($_POST['nbr_chambre'])));
					$this->bind(":et", strip_tags(trim($_POST['etage'])));
					$this->bind(":gar", strip_tags(trim($_POST['garage'])));
					$this->bind(":jard", strip_tags(trim($_POST['jardin'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":papier", $pap);
					$this->bind(":unit", strip_tags(trim($_POST['unit'])));
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));

					$this->bind(":id", $id_local);
					break;

				case 'bungalow':
					$this->query("UPDATE local SET wilaya = :wil, commune = :com, type = :ty, vl = :vl, surface = :sur, nbr_chambre = :nc, nbr_bain = :bain, prix = :pri, meuble = :meuble, description_local = :descr, papier = :papier, unit = :unit WHERE id_local = :id");

					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":nc", strip_tags(trim($_POST['nbr_chambre'])));
					$this->bind(":bain", strip_tags(trim($_POST['nbr_bain'])));
					$this->bind(":meuble", strip_tags(trim($_POST['meuble'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":papier", $pap);
					$this->bind(":unit", strip_tags(trim($_POST['unit'])));
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));

					$this->bind(":id", $id_local);
					break;

				default:
					return false;
					break;
			}

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}
		
		public function ChangeStat($id_local, $stat)
		{
			if ($stat !== "active" && $stat !== "desactive" && $stat !== "vendu") {
				return false;
			}

			$this->query("UPDATE local SET etat_local = :stat WHERE id_local = :id");

			$this->bind(":stat", $stat);
			$this->bind(":id", $id_local);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function Delete($id_local)
		{
			$this->query("UPDATE local SET etat_local = :etat WHERE id_local = :id");

			$this->bind(":etat", "deleted");
			$this->bind(":id", $id_local);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		private function nulling()
		{
			if (!isset($_POST['surface']) || empty($_POST['surface'])) {
				$_POST['surface'] = null;
			}

			if (!isset($_POST['nbr_chambre']) || empty($_POST['nbr_chambre'])) {
				$_POST['nbr_chambre'] = null;
			}

			if (!isset($_POST['etage']) || empty($_POST['etage'])) {
				$_POST['etage'] = null;
			}

			if (!isset($_POST['nbr_garage']) || empty($_POST['nbr_garage'])) {
				$_POST['nbr_garage'] = null;
			}

			if (!isset($_POST['nbr_bain']) || empty($_POST['nbr_bain'])) {
				$_POST['nbr_bain'] = null;
			}

			if (!isset($_POST['jardin']) || empty($_POST['jardin'])) {
				$_POST['jardin'] = null;
			}

			if (!isset($_POST['piscine']) || empty($_POST['piscine'])) {
				$_POST['piscine'] = null;
			}

			if (!isset($_POST['description_local']) || empty($_POST['description_local'])) {
				$_POST['description_local'] = null;
			}

			if (!isset($_POST['prix']) || empty($_POST['prix'])) {
				$_POST['prix'] = null;
			}

			if (!isset($_POST['meuble']) || empty($_POST['meuble'])) {
				$_POST['meuble'] = 0;
			}

			if (!isset($_POST['unit']) || empty($_POST['unit'])) {
				$_POST['unit'] = "da";
			}

			if (!isset($_POST['papier']) || empty($_POST['papier'])) {
				$_POST['papier'] = null;
			}
		}

	}

 ?>