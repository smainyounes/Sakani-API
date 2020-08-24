<?php 

	/**
	 * 
	 */
	class model_agence extends lib_database
	{
		
		function __construct()
		{
			parent::__construct();
		}

		/**
		 * Getters
		 */

		public function GetLatest($limit = 9)
		{
			$this->query("SELECT * FROM agence WHERE etat_agence = :etat ORDER BY id_agence DESC LIMIT :num");

			$this->bind(":etat", "active");
			$this->bind(":num", $limit);

			return $this->resultSet();
		}

		public function Detail($id_agence, $nom = null)
		{
			$sql = "SELECT * FROM agence WHERE id_agence = :id";

			if (isset($nom)) {
				$sql .= " AND nom = :nom";
			}

			$this->query($sql);

			$this->bind(":id", $id_agence);
			if (isset($nom)) {
				$this->bind(":nom", $nom);
			}
			
			return $this->single();
		}

		public function Login()
		{
			$this->query("SELECT * FROM agence WHERE email = :email");

			$this->bind(":email", $_POST['email']);

			$res = $this->single();
			$resp = [];

			if ($res && password_verify($_POST['password'], $res->password)) {
				// generate and insert tokken
				$tokken = $this->GenTokken($res->id_agence);
				
				if (isset($tokken)) {
					return ['status' => 'success', 'data' => ['id_agence' => $res->id_agence, 'nom_agence' => $res->nom, 'tokken' => $tokken, 'nom_url' => str_replace(" ", "-", trim($res->nom)) . "-" . $res->id_agence]];
				}else{
					return ['status' => 'error', 'data' => ['msg' => 'tokken could not be generated']];
				}
				
			}else{
				return ['status' => 'error', 'data' => ['msg' => 'wrong username or password']];
			}
		}

		public function CheckAgence($id_agence, $tokken)
		{
			$this->query("SELECT id_agence FROM agence WHERE id_agence = :id AND tokken IS NOT NULL AND tokken = :tokken");

			$this->bind(":id", $id_agence);
			$this->bind(":tokken", $tokken);

			$res = $this->single();

			if ($res) {
				return true;
			}else{
				return false;
			}
		}

		public function TestOwner($id_agence, $id_local)
		{
			$this->query("SELECT id_local FROM local WHERE id_local = :id_local AND id_agence = :id_agence");

			$this->bind(":id_local", $id_local);
			$this->bind(":id_agence", $id_agence);

			$res = $this->single();
			if ($res) {
				return true;
			}else{
				return false;
			}
		}

		/**
		 * Setters
		 */

		public function GenTokken($id_agence)
		{
			$tokken = token(10) . uniqid();
			$this->query("UPDATE agence SET tokken = :tokken WHERE id_agence = :id");

			$this->bind(":tokken", $tokken);
			$this->bind(":id", $id_agence);

			try {
				$this->execute();
				return $tokken;
			} catch (Exception $e) {
				return null;
			}
		}

		public function Inscription()
		{
			$this->query("INSERT INTO agence (`email`, `nom`, `address`, `password`, `tel1`, `tel2`, `fb`)
			VALUES (:email, :nom, :address, :password, :tel1, :tel2, :fb)");

			$this->bind(":email",strip_tags(trim($_POST['email'])));
			$this->bind(":nom",strip_tags(trim($_POST['nom'])));
			$this->bind(":address",strip_tags(trim($_POST['address'])));
			$this->bind(":password", password_hash($_POST['password'], PASSWORD_DEFAULT));
			$this->bind(":tel1",strip_tags(trim($_POST['tel1'])));
			$this->bind(":tel2", strip_tags($_POST['tel2']));
			$this->bind(":fb", strip_tags($_POST['fb']));

			try {
				$this->execute();
				return $this->LastId();
			} catch (Exception $e) {
				return 0;
			}
		}

		public function ImgLocal($id_agence, $link)
		{
			$this->query("UPDATE agence SET local = :link WHERE id_agence = :id");

			$this->bind(":link", $link);
			$this->bind(":id", $id_agence);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function ImgRC($id_agence, $link)
		{
			$this->query("UPDATE agence SET registre = :link WHERE id_agence = :id");

			$this->bind(":link", $link);
			$this->bind(":id", $id_agence);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function UpdateInfos($id_agence)
		{
			$this->query("UPDATE `agence` SET `tel1` =:tel1 , `tel2` =:tel2 , `fb` =:fb  WHERE `id_agence`= :id");

			$this->bind(":tel1", strip_tags(trim($_POST['tel1'])));
			$this->bind(":tel2", strip_tags($_POST['tel2']));
			$this->bind(":fb", strip_tags($_POST['fb']));
			$this->bind(":id", $id_agence);
			
			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}			
		}

		public function ChangePassword($id_agence)
		{
			$this->query("UPDATE agence SET password = :password WHERE id_agence = :id");
			
			$this->bind(":password", password_hash($_POST['password'], PASSWORD_DEFAULT));
			$this->bind(":id", $id_agence);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function UpdateProfilePic($id_agence, $link)
		{
			$this->query("UPDATE agence SET img_prof = :img_prof WHERE id_agence = :id");
			
			$this->bind(":img_prof", $link);
			$this->bind(":id", $id_agence);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function ChangeCoverPic($id_agence, $link)
		{
			$this->query("UPDATE agence SET img_cover = :img_cover WHERE id_agence = :id");
			
			$this->bind(":img_cover", $link);
			$this->bind(":id", $id_agence);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function Logout($id_agence, $tokken)
		{
			if ($this->CheckAgence($id_agence, $tokken)) {
				$this->query("UPDATE agence SET tokken = NULL WHERE id_agence = :id");

				$this->bind(":id", $id_agence);

				try {
					$this->execute();
					return true;
				} catch (Exception $e) {
					return false;
				}
			}else{
				return false;
			}
		}
	}

 ?>