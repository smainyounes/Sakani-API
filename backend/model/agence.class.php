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
			$this->query("SELECT * FROM agence ORDER BY id_agence DESC LIMIT :num");

			$this->bind(":num", $limit);

			return $this->resultSet();
		}

		public function Detail($id_agence)
		{
			$this->query("SELECT * FROM agence WHERE id_agence = :id");

			$this->bind(":id", $id_agence);

			return $this->single();
		}

		public function GetConnectedInfos()
		{
			if (isset($_SESSION['agence'])) {
				return $this->Detail($_SESSION['agence']);
			}else{
				return null;
			}
		}

		public function Login()
		{
			$this->query("SELECT * FROM agence WHERE email = :email");

			$this->bind(":email", $_POST['email']);

			$res = $this->single();
			if ($res && password_verify($_POST['password'], $res->password)) {
				$_SESSION['agence'] = $res->id_agence;
				return true;
			}else{
				return false;
			}
		}

		public function TestOwner($id_local)
		{
			if (isset($_SESSION['agence'])) {
				$this->query("SELECT * FROM local WHERE id_agence = :id_agence AND id_local = :id_local");

				$this->bind(":id_local", $id_local);
				$this->bind(":id_agence", $_SESSION['agence']);

				$res = $this->single();
				
				if ($res) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Setters
		 */

		public function Inscription()
		{
			$this->query("INSERT INTO agence (`email`, `nom`, `address`, `password`, `tel1`, `tel2`, `fb`, `date_exp`,`registre`, `local`)
			VALUES (:email, :nom, :address, '$password', :tel1, :tel2, :fb, '$date_exp','$registre', '$local');");

			$this->bind(":email",strip_tags(trim($_POST['email'])));
			$this->bind(":nom",strip_tags(trim($_POST['nom'])));
			$this->bind(":address",strip_tags(trim($_POST['address'])));
			$this->bind(":tel1",strip_tags(trim($_POST['tel1'])));
			$this->bind(":tel2", strip_tags($_POST['tel2']));
			$this->bind(":fb", strip_tags($_POST['fb']));

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function UpdateInfos()
		{
			$this->query("UPDATE `agence` SET `tel1` =:tel1 , `tel2` =:tel2 , `fb` =:fb  WHERE `id_agence`=".$_SESSION['user']." ;");

			$this->bind(":tel1", strip_tags(trim($_POST['tel1'])));
			$this->bind(":tel2", $tel2);
			$this->bind(":fb", $fb);
			
			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}			
		}

		public function ChangePassword()
		{
			$this->query("UPDATE agence SET password = :password WHERE id_agence = :id");
			
			$this->bind(":password", password_hash($_POST['password'], PASSWORD_DEFAULT));
			$this->bind(":id", $_SESSION['agence']);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function UpdateProfilePic($link)
		{
			$this->query("UPDATE agence SET img_prof = :img_prof WHERE id_agence = :id");
			
			$this->bind(":img_prof", $link);
			$this->bind(":id", $_SESSION['agence']);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function ChangeCoverPic($link)
		{
			$this->query("UPDATE agence SET img_cover = :img_cover WHERE id_agence = :id");
			
			$this->bind(":img_cover", $link);
			$this->bind(":id", $_SESSION['agence']);

			try {
				$this->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function Logout()
		{
			if(isset($_SESSION['agence'])){
				unset($_SESSION['agence']);
				return true;
			}else{
				return false;
			}
		}
	}

 ?>