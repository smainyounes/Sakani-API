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

		public function GetAll($page, $limit)
		{
			// LIMIT $limit OFFSET $start

			// $start = ($page - 1) * $limit;

			$this->query("SELECT * FROM agence ORDER BY id_agence DESC LIMIT :num OFFSET :start");

			$this->bind(":num", $limit);
			$this->bind(":start", ($page - 1) * $limit);

			return $this->resultSet();
		}

		public function Search($page, $limit, $filter, $keyword)
		{
			$conc = "";
			if ($filter !== "all") {
				$conc = "AND etat_agence = :filter";
			}

			$sql = "SELECT * FROM agence WHERE (nom LIKE :keyword OR email LIKE :keyword) $conc ORDER BY id_agence DESC LIMIT :num OFFSET :start";
			

			$this->query($sql);

			if ($filter !== "all") {
				$this->bind(":filter", $filter);
			}
			
			$this->bind(":keyword", "%{$keyword}%");
			$this->bind(":num", $limit);
			$this->bind(":start", (($page - 1) * $limit));

			return $this->resultSet();
		}

		public function CountSearch($filter, $keyword)
		{
			$this->query("SELECT COUNT(id_agence) nbr FROM agence WHERE etat_agence = :filter AND (nom LIKE :keyword OR email LIKE :keyword)");

			$this->bind(":filter", $filter);
			$this->bind(":keyword", "%{$keyword}%");

			$res = $this->single();

			return $res->nbr;

		}

		public function GetLatest($limit = 9)
		{
			$this->query("SELECT * FROM agence WHERE etat_agence = :etat ORDER BY id_agence DESC LIMIT :num");

			$this->bind(":etat", "active");
			$this->bind(":num", $limit);

			return $this->resultSet();
		}

		public function Random($limit = 9)
		{
			$this->query("SELECT * FROM agence WHERE etat_agence = :etat ORDER BY RAND() LIMIT :num");

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

			if ($res && password_verify($_POST['password'], $res->password)) {
				return $res;				
			}else{
				return null;
			}
		}

		public function CheckAgence($id_agence, $tokken)
		{
			$this->query("SELECT * FROM agence INNER JOIN agence_login ON agence.id_agence = agence_login.id_agence WHERE agence.id_agence = :id AND agence_login.session_tokken = :tokken");

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

		public function GetRC($id_agence)
		{
			$this->query("SELECT registre AS rc FROM agence WHERE id_agence = :id");

			$this->bind(":id", $id_agence);

			return $this->single();
		}

		public function GetHanout($id_agence)
		{
			$this->query("SELECT local AS hanout FROM agence WHERE id_agence = :id");

			$this->bind(":id", $id_agence);

			return $this->single();
		}

		public function CheckEmailExists($email)
		{
			$this->query("SELECT email FROM agence WHERE email = :email");

			$this->bind(":email", strtolower($email));

			$res = $this->single();

			if ($res) {
				return true;
			}else{
				return false;
			}
		}

		public function CheckValidation($selector, $tokken)
		{
			// check if empty
			if (empty($selector) || empty($tokken)) {
				return ['status' => 'error', 'data' => ['msg' => 'empty tokken or selector']];
			}

			// check if data r hex
			if (!ctype_xdigit($selector) || !ctype_xdigit($tokken)) {
				return ['status' => 'error', 'data' => ['msg' => 'wrong tokken & selector format']];
			}		

			$current = date("U");

			$this->query("SELECT * FROM `validation-tokken` WHERE selector = :selector AND exp_time >= :current");

			$this->bind(":selector", $selector);
			$this->bind(":current", $current);

			$res = $this->single();

			if ($res) {
				if (password_verify(hex2bin($tokken), $res->validation)) {
					$this->DeleteValidation($res->email);
					return ['status' => 'success', 'data' => ['email' => $res->email]];
				}
			}

			return ['status' => 'error', 'data' => ['msg' => 'tokken or selector not checked']];
		}

		/**
		 * Setters
		 */

		public function GenTokken($id_agence)
		{
			$tokken = token(10) . uniqid();
			$this->query("INSERT INTO agence_login(`id_agence`, `session_tokken`, `ip`, `infos`) VALUES(:id, :tokken, :ip, :infos)");

			$this->bind(":id", $id_agence);
			$this->bind(":tokken", $tokken);
			$this->bind(":ip", $_SERVER['REMOTE_ADDR']);
			$this->bind(":infos", $_SERVER['HTTP_USER_AGENT']);

			try {
				$this->execute();
				return $tokken;
			} catch (Exception $e) {
				return null;
			}
		}

		public function GenValidation($email)
		{
			// check if email exists
			if (!$this->CheckEmailExists($email)) {
				return ['status' => 'error', 'data' => ['msg' => 'email doesnt exists']];
			}

			// generate tokken 
			$tokken = random_bytes(32);

			// generate selector
			$selector = bin2hex(random_bytes(8));

			// generate exp_time
			$exp_time = date("U") + 1800;

			// delete old ones
			if (!$this->DeleteValidation($email)) {
				return ['status' => 'error', 'data' => ['msg' => 'could not delete old data']];
			}
			
			// insert them in database
			$this->query("INSERT INTO `validation-tokken`(email, selector, validation, exp_time) VALUES(:email, :selector, :validation, :exp_time)");

			$this->bind(":email", strtolower($email));
			$this->bind(":selector", $selector);
			$this->bind(":validation", password_hash($tokken, PASSWORD_DEFAULT));
			$this->bind(":exp_time", $exp_time);

			try {
				$this->execute();
			} catch (Exception $e) {
				return ['status' => 'error', 'data' => ['msg' => 'could not generate new tokken']];
			}

			// return both selector & tokken
			return ['status' => 'success', 'data' => ['tokken' => $tokken, 'selector' => $selector, 'email' => $email]];
		}

		public function DeleteValidation($email)
		{
			$this->query("DELETE FROM `validation-tokken` WHERE email = :email");
			$this->bind(":email", strtolower($email));
			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
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

		public function ResetPswd($email)
		{
			// check if password is sent
			if (empty($_POST['password']) || empty($_POST['repassword'])) {
				return false;
			}

			// check if password match
			if ($_POST['password'] !== $_POST['repassword']) {
				return false;
			}

			$this->query("UPDATE agence SET password = :password WHERE email = :email");
			
			$this->bind(":password", password_hash($_POST['password'], PASSWORD_DEFAULT));
			$this->bind(":email", $email);

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
				$this->query("DELETE FROM agence_login WHERE id_agence = :id AND session_tokken = :tokken");

				$this->bind(":id", $id_agence);
				$this->bind(":tokken", $tokken);

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