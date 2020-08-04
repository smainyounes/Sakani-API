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

		public function GetAll($page)
		{
			$limit = 20;
			$start = ($page - 1) * $limit;

			$sql = "SELECT *, local.id_local AS id_local
					FROM ((local
					INNER JOIN agence ON local.id_agence = agence.id_agence)
					LEFT JOIN image ON local.id_local = image.id_image AND image.main = 1) 
					WHERE agence.etat_agence = :etat AND local.etat_local = :etat2 ORDER BY local.id_local DESC LIMIT $limit OFFSET $start";

			$this->query($sql);
			$this->bind(":etat", "active");
			$this->bind(":etat2", "active");
			
			return $this->resultSet();
		}

		public function Detail($id_local)
		{
			$this->query("SELECT * FROM local WHERE id_local = :id AND etat_local = :etat");

			$this->bind(":id", $id_local);
			$this->bind(":etat", "active");

			return $this->single();
		}

		public function ByAgence($id_agence, $page, $owner)
		{
			$limit = 20;
			$start = ($page - 1) * $limit;

			$conc = "";
			$etat = "";

			if ($owner) {
				$conc = "AND local.etat_local != :etat";
				$etat = "deleted";
			}else{
				$conc = "AND agence.etat_agence = :etat AND local.etat_local = :etat";
				$etat = "active";
			}

			$sql = "SELECT *, local.id_local AS id_local
							FROM ((local
							INNER JOIN agence ON local.id_agence = agence.id_agence)
							LEFT JOIN image ON local.id_local = image.id_image AND image.main = 1) 
							WHERE agence.id_agence = :id $conc ORDER BY local.id_local DESC LIMIT $limit OFFSET $start";

			$this->query($sql);

			$this->bind(":id", $id_agence);
			$this->bind(":etat", $etat);

			return $this->resultSet();
		}

		public function Search($page, $wilaya, $commune, $type, $vl)
		{
			$limit = 20;
			$start = ($page - 1) * $limit;

			$conc = "";

			if ($wilaya !== "tout") {
				$conc .= " AND local.wilaya = :wilaya";
			}

			if ($commune !== "tout") {
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
					LEFT JOIN image ON local.id_local = image.id_image AND image.main = 1) 
					WHERE agence.etat_agence = :etat AND local.etat_local = :etat2 $conc ORDER BY local.id_local DESC LIMIT $limit OFFSET $start";

			$this->query($sql);

			$this->bind(":etat", "active");
			$this->bind(":etat2", "active");


			if ($wilaya !== "tout") {
				$this->bind(":wilaya", $wilaya);
			}

			if ($commune !== "tout") {
				$this->bind(":commune", $commune);
			}

			if ($type !== "tout") {
				$this->bind(":type", $type);
			}

			if ($vl !== "tout") {
				$$this->bind(":vl", $vl);
			}

			return $this->resultSet();
		}

		public function SearchCount($wilaya, $commune, $type, $vl)
		{
			$conc = "";

			if ($wilaya !== "tout") {
				$conc .= " AND local.wilaya = :wilaya";
			}

			if ($commune !== "tout") {
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


			if ($wilaya !== "tout") {
				$this->bind(":wilaya", $wilaya);
			}

			if ($commune !== "tout") {
				$this->bind(":commune", $commune);
			}

			if ($type !== "tout") {
				$this->bind(":type", $type);
			}

			if ($vl !== "tout") {
				$$this->bind(":vl", $vl);
			}

			$res = $this->single();
			return $res->nbr;
		}

		public function CountByAgence($id_agence, $owner)
		{
			if ($owner) {
				$conc = "AND local.etat_local != :etat";
				$etat = "deleted";
			}else{
				$conc = "AND agence.etat_agence = :etat AND local.etat_local = :etat";
				$etat = "active";
			}

			$sql = "SELECT COUNT(id_local) nbr FROM local INNER JOIN agence ON agence.id_agence = local.id_agence WHERE id_agence = :id $conc";

			$this->query($sql);

			$this->bind(":id", $id_agence);
			$this->bind(":etat", $etat);

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
					LEFT JOIN image ON local.id_local = image.id_image AND image.main = 1) 
					WHERE agence.etat_agence = :etat AND local.etat_local = :etat2 ORDER BY local.id_local DESC LIMIT :lim";

			$this->query($sql);

			$this->bind(":etat", "active");
			$this->bind(":etat2", "active");
			$this->bind(":lim", $limit);

			return $this->resultSet();
		}

		/**
		 * Setters
		 */

		public function AjouterInfos($id_local)
		{
			switch (strtolower($_POST['type'])) {
				case 'appartement':
					$this->query("INSERT INTO local(id_agence, wilaya, commune, type, vl, surface, nbr_chambre, etage, nbr_bain, description_local, prix, etat_local) VALUES(:id, :wil, :com, :ty, :vl, :sur, :nc, :et, :bain, :descr, :pri, :etat)");
					$this->bind(":id", $id_local);
					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":nc", strip_tags(trim($_POST['nbr_chambre'])));
					$this->bind(":et", strip_tags(trim($_POST['etage'])));
					$this->bind(":bain", strip_tags(trim($_POST['nbr_bain'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":etat", "desactive");
					break;
				
				case 'villa':
					$this->query("INSERT INTO local(id_agence, wilaya, commune, type, vl, surface, nbr_chambre, etage, nbr_bain, piscine, nbr_garage, jardin, description_local, prix, etat_local) VALUES(:id, :wil, :com, :ty, :vl, :sur, :nc, :et, :bain, :pisc, :gar, :jard, :descr, :pri, :etat)");
					$this->bind(":id", $id_local);
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
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":etat", "desactive");
					break;
				
				case 'arab':
					$this->query("INSERT INTO local(id_agence, wilaya, commune, type, vl, surface, nbr_chambre, nbr_bain, jardin, nbr_garage, description_local, prix, etat_local) VALUES(:id, :wil, :com, :ty, :vl, :sur, :nc, :bain, :jard, :gar, :descr, :pri, :etat)");
					$this->bind(":id", $id_local);
					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":nc", strip_tags(trim($_POST['nbr_chambre'])));
					$this->bind(":bain", strip_tags(trim($_POST['nbr_bain'])));
					$this->bind(":jard", strip_tags(trim($_POST['jardin'])));
					$this->bind(":gar", strip_tags(trim($_POST['garage'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":etat", "desactive");
					break;
				
				case 'studio':
					$this->query("INSERT INTO local(id_agence, wilaya, commune, type, vl, surface, nbr_bain, description_local, prix, etat_local) VALUES(:id, :wil, :com, :ty, :vl, :sur, :bain, :descr, :pri, :etat)");
					$this->bind(":id", $id_local);
					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":bain", strip_tags(trim($_POST['nbr_bain'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":etat", "desactive");
					break;
				
				case 'terrain':
					$this->query("INSERT INTO local(id_agence, wilaya, commune, type, vl, surface, description_local, prix, etat_local) VALUES(:id, :wil, :com, :ty, :vl, :sur, :descr, :pri, :etat)");
					$this->bind(":id", $id_local);
					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":descr", strip_tags(trim($_POST['description'])));
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));
					$this->bind(":etat", "desactive");
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
			switch (strtolower($_POST['type'])) {
				case 'appartement':
					$this->query("UPDATE local SET wilaya = :wil, commune = :com, type = :ty, vl = :vl, surface = :sur, nbr_chambre = :nc, etage = :et, nbr_bain = :bain, prix = :pri WHERE id_local = :id");

					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":nc", strip_tags(trim($_POST['nbr_chambre'])));
					$this->bind(":et", strip_tags(trim($_POST['etage'])));
					$this->bind(":bain", strip_tags(trim($_POST['nbr_bain'])));
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));

					$this->bind(":id", $id_local);
					break;
				
				case 'villa':
					$this->query("UPDATE local SET wilaya = :wil, commune = :com, type = :ty, vl = :vl, surface = :sur, nbr_chambre = :nc, etage = :et, nbr_bain = :bain, piscine = :pisc, nbr_garage = :gar, jardin = :jard, prix = :pri WHERE id_local = :id");

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

					$this->bind(":id", $id_local);
					break;
				
				case 'arab':
					$this->query("UPDATE local SET wilaya = :wil, commune = :com, type = :ty, vl = :vl, surface = :sur, nbr_chambre = :nc, nbr_bain = :bain, jardin = :jard, nbr_garage = :gar, prix = :pri WHERE id_local = :id");

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

					$this->bind(":id", $id_local);
					break;
				
				case 'studio':
					$this->query("UPDATE local SET wilaya = :wil, commune = :com, type = :ty, vl = :vl, surface = :sur, nbr_bain = :bain, prix = :pri WHERE id_local = :id");

					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
					$this->bind(":bain", strip_tags(trim($_POST['nbr_bain'])));
					$this->bind(":pri", strip_tags(trim($_POST['prix'])));

					$this->bind(":id", $id_local);
					break;
				
				case 'terrain':
					$this->query("UPDATE local SET wilaya = :wil, commune = :com, type = :ty, vl = :vl, surface = :sur, prix = :pri WHERE id_local = :id");

					$this->bind(":wil", strip_tags(trim($_POST['wilaya'])));
					$this->bind(":com", strip_tags(trim($_POST['commune'])));
					$this->bind(":ty", strip_tags(trim($_POST['type'])));
					$this->bind(":vl", strip_tags(trim($_POST['vl'])));
					$this->bind(":sur", strip_tags(trim($_POST['surface'])));
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
			if ($stat !== "active" || $stat !== "desactive" || $stat !== "vendu") {
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
			$this->query("UPDATE local SET etat_local = 'deleted' WHERE id_local = :id_local");

			$this->bind(":id", $id_local);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

	}

 ?>